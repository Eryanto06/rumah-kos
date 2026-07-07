<?php namespace App\Controllers\User;
use App\Controllers\BaseController;
use App\Models\PembayaranModel;
use App\Models\NotifikasiModel;
use App\Models\UserModel;

class Pembayaran extends BaseController {
    public function index() {
        $id_user = session()->get('id_user');
        $model   = new PembayaranModel();
        return view('user/pembayaran/index', ['title' => 'Riwayat Pembayaran', 'pembayaran' => $model->getPembayaranByUser($id_user)]);
    }

    public function upload() {
        $id_user = session()->get('id_user');
        $idsInput = $this->request->getPost('id_pembayaran');
        $file     = $this->request->getFile('bukti_bayar');

        if (empty($idsInput)) {
            return redirect()->to('/user/pembayaran')->with('error', 'Pilih minimal 1 bulan yang ingin dibayar!');
        }

        if (!$file || !$file->isValid()) {
            return redirect()->to('/user/pembayaran')->with('error', 'Bukti pembayaran wajib diupload!');
        }

        // === FIX Bug #7: Validasi file upload (mime type & ukuran) ===
        // FIX CRITICAL: getMimeType() baca Content-Type header yang bisa di-spoof attacker.
        // getRandomName() tetap pertahankan extension asli — kalau .php, file jadi webshell.
        // Validasi extension eksplisit + MIME.
        $allowedMime = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp', 'application/pdf'];
        $allowedExt  = ['jpg', 'jpeg', 'png', 'webp', 'pdf'];
        $ext = strtolower($file->getExtension());
        if (!in_array($ext, $allowedExt, true) || !in_array($file->getMimeType(), $allowedMime, true)) {
            return redirect()->to('/user/pembayaran')->with('error', 'Format file tidak didukung! Gunakan JPG, PNG, WEBP, atau PDF.');
        }
        if ($file->getSize() > 2 * 1024 * 1024) {
            return redirect()->to('/user/pembayaran')->with('error', 'Ukuran file maksimal 2MB!');
        }

        // FIX: paksa rename extension file supaya tidak ada .php/.html/.phtml yang lolos.
        // Generate nama baru dengan extension yang di-whitelist.
        $nama = $file->getRandomName();
        // Override extension dengan yang aman (jpg/png/webp/pdf).
        $namaFinal = pathinfo($nama, PATHINFO_FILENAME) . '.' . $ext;

        $model = new PembayaranModel();

        // === CEGAH IDOR: hanya proses ID tagihan yang benar-benar milik user ini ===
        $ids = $model->filterIdMilikUser($idsInput, $id_user);

        if (empty($ids)) {
            return redirect()->to('/user/pembayaran')->with('error', 'Tagihan tidak valid atau bukan milik Anda.');
        }

        // === FIX Bug #6: FILTER lagi - hanya tagihan berstatus 'belum_bayar' yang boleh diupload ===
        $idsValid = [];
        foreach ($ids as $id) {
            $tagihan = $model->find($id);
            if ($tagihan && $tagihan['status'] === 'belum_bayar') {
                $idsValid[] = $id;
            }
        }

        if (empty($idsValid)) {
            return redirect()->to('/user/pembayaran')->with('error', 'Tidak ada tagihan yang bisa diproses. Pastikan tagihan berstatus "Belum Bayar" (bukan Lunas atau Menunggu Verifikasi).');
        }

        // FIX: pindah file dengan nama aman (extension dari whitelist).
        // FIX BUG: $file->move() throw HTTPException saat gagal — bukan return false.
        // Bungkus try-catch supaya kalau gagal, user dapat pesan error jelas.
        try {
            $file->move(ROOTPATH . 'public/uploads/', $namaFinal);
        } catch (\Throwable $e) {
            log_message('error', '[Pembayaran::upload] Gagal upload bukti: ' . $e->getMessage());
            return redirect()->to('/user/pembayaran')->with('error', 'Gagal upload bukti pembayaran: ' . $e->getMessage() . '. Coba lagi.');
        }

        // FIX C8: pakai builder() karena 'status' gak di $allowedFields PembayaranModel.
        // FIX BUG: kolom tanggal_bayar adalah DATETIME (bukan DATE). Pakai format DATETIME
        // supaya info waktu upload tidak hilang (berguna untuk audit & laporan periode).
        foreach ($idsValid as $id) {
            $model->builder()->where('id_pembayaran', $id)->update([
                'bukti_bayar'   => $namaFinal,
                'tanggal_bayar' => date('Y-m-d H:i:s'),
                'status'        => 'menunggu_verifikasi',
            ]);
        }

        // AUTO-REPLY KE USER
        $notifModel = new NotifikasiModel();
        $notifModel->kirim(
            $id_user,
            'Pembayaran Diterima',
            'Bukti pembayaran Anda telah diupload untuk ' . count($idsValid) . ' tagihan. Admin akan verifikasi dalam 1x24 jam. Status tagihan akan berubah jadi "Lunas" setelah diverifikasi.',
            'pembayaran'
        );

        // Notif ke admin
        $userModel = new UserModel();
        $admins = $userModel->where('role', 'admin')->findAll();
        foreach ($admins as $admin) {
            $notifModel->kirim(
                $admin['id_user'],
                'Pembayaran Baru Perlu Verifikasi',
                'Penghuni ' . session()->get('nama') . ' upload bukti pembayaran untuk ' . count($idsValid) . ' tagihan. Segera verifikasi di menu Pembayaran.',
                'pembayaran'
            );
        }

        $skipCount = count($ids) - count($idsValid);
        $pesan = 'Bukti pembayaran berhasil diupload untuk ' . count($idsValid) . ' bulan! Cek notifikasi.';
        if ($skipCount > 0) {
            $pesan .= ' (' . $skipCount . ' tagihan di-skip karena sudah diproses.)';
        }

        return redirect()->to('/user/pembayaran')->with('success', $pesan);
    }

    /**
     * Generate Invoice/Nota Pembayaran
     */
    public function invoice($id)
    {
        $id_user = session()->get('id_user');
        $model   = new PembayaranModel();
        
        $db = \Config\Database::connect();
        $pembayaran = $db->table('pembayaran p')
            ->select('p.*, u.nama, u.email, u.no_hp, k.kode_kamar, k.nomor_kamar, k.harga_sewa, s.tanggal_mulai, s.tanggal_selesai')
            ->join('sewa s', 's.id_sewa = p.id_sewa')
            ->join('user u', 'u.id_user = s.id_user')
            ->join('kamar k', 'k.id_kamar = s.id_kamar')
            ->where('p.id_pembayaran', $id)
            ->where('s.id_user', $id_user)
            ->get()->getRowArray();

        if (!$pembayaran) {
            return redirect()->to('/user/pembayaran')->with('error', 'Data pembayaran tidak ditemukan.');
        }

        if ($pembayaran['status'] !== 'lunas') {
            return redirect()->to('/user/pembayaran')->with('error', 'Invoice hanya tersedia untuk pembayaran yang sudah lunas.');
        }

        $data = [
            'title'      => 'Invoice Pembayaran',
            'pembayaran' => $pembayaran,
        ];
        
        return view('user/pembayaran/invoice', $data);
    }
}