<?php namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\SewaModel;
use App\Models\KamarModel;
use App\Models\PembayaranModel;
use App\Models\NotifikasiModel;
use App\Models\UserModel;
use App\Models\PengaturanModel;

class Perpanjangan extends BaseController {

    public function index() {
        $id_user   = session()->get('id_user');
        $sewaModel = new SewaModel();
        $pembayaranModel = new PembayaranModel();

        $sewa = $sewaModel->getSewaAktifByUser($id_user);

        // Cek apakah masih ada tagihan belum lunas (untuk tampilkan info ke user)
        $tunggakan = 0;
        if ($sewa) {
            $tunggakan = $pembayaranModel->where('id_sewa', $sewa['id_sewa'])
                                         ->whereIn('status', ['belum_bayar', 'menunggu_verifikasi'])
                                         ->countAllResults();
        }

        return view('user/perpanjangan', [
            'title'     => 'Perpanjangan Sewa',
            'sewa'      => $sewa,
            'tunggakan' => $tunggakan,
        ]);
    }

    public function ajukan() {
        $id_user   = session()->get('id_user');
        $sewaModel = new SewaModel();
        $sewa      = $sewaModel->getSewaAktifByUser($id_user);

        if (!$sewa) {
            return redirect()->back()->with('error', 'Tidak ada sewa aktif!');
        }

        // FIX H30: cegah perpanjangan sewa yang sudah berakhir.
        // Cron belum auto-expire sewa, jadi 'aktif' dengan tanggal_selesai lewat
        // masih bisa diperpanjang. Tagihan baru jatuh tempo di masa lalu → langsung kena denda.
        if (!empty($sewa['tanggal_selesai']) && strtotime($sewa['tanggal_selesai']) < strtotime(date('Y-m-d'))) {
            return redirect()->back()->with('error', 'Kontrak sewa Anda sudah berakhir tanggal ' . date('d M Y', strtotime($sewa['tanggal_selesai'])) . '. Hubungi admin untuk perpanjangan manual — fitur perpanjangan otomatis hanya untuk kontrak yang masih aktif.');
        }

        // Cek apakah masih ada tagihan belum lunas
        $pembayaranModel = new PembayaranModel();
        $tunggakan = $pembayaranModel->where('id_sewa', $sewa['id_sewa'])
                                     ->whereIn('status', ['belum_bayar', 'menunggu_verifikasi'])
                                     ->countAllResults();
        if ($tunggakan > 0) {
            return redirect()->back()->with('error', 'Perpanjangan tidak bisa dilakukan! Anda masih memiliki ' . $tunggakan . ' tagihan yang belum lunas. Selesaikan pembayaran terlebih dahulu.');
        }

        $rules = [
            'durasi_bulan' => 'required|numeric|greater_than_equal_to[1]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $tambah_bulan = (int) $this->request->getPost('durasi_bulan');

        // === FIX: Baca batas durasi dari Pengaturan Admin (dinamis) ===
        $pengaturanModel = new PengaturanModel();
        $maxDurasi = (int) $pengaturanModel->get('durasi_maksimal') ?: 120; // default 120 kalau belum diset
        $minDurasi = (int) $pengaturanModel->get('durasi_minimal') ?: 1;    // default 1 kalau belum diset

        // Validasi: minimal
        if ($tambah_bulan < $minDurasi) {
            return redirect()->back()->withInput()->with('error', 'Durasi perpanjangan minimal ' . $minDurasi . ' bulan.');
        }

        // Validasi: maksimal (anti typo/abuse)
        if ($tambah_bulan > $maxDurasi) {
            return redirect()->back()->withInput()->with('error', 'Durasi perpanjangan terlalu besar! Maksimal ' . $maxDurasi . ' bulan. Ubah di menu Pengaturan Admin kalau perlu lebih lama.');
        }

        $tanggal_selesai_lama = $sewa['tanggal_selesai'];
        $tanggal_selesai_baru = date('Y-m-d', strtotime($tanggal_selesai_lama . " +{$tambah_bulan} months"));

        // === TRANSACTION: update sewa + generate tagihan dalam 1 transaksi ===
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // === ANTI-DOBEL SUBMIT pakai atomic update ===
            $builder = $db->table('sewa');
            $builder->where('id_sewa', $sewa['id_sewa']);
            $builder->where('tanggal_selesai', $tanggal_selesai_lama); // Lock: cek nilai lama
            $builder->update([
                'tanggal_selesai' => $tanggal_selesai_baru,
                'durasi_bulan'    => $sewa['durasi_bulan'] + $tambah_bulan,
            ]);

            // FIX: pakai affectedRows() bukan return value update().
            if ($db->affectedRows() < 1) {
                $db->transRollback();
                return redirect()->back()->with('error', 'Gagal! Kontrak Anda baru saja diperpanjang (kemungkinan dobel submit). Refresh halaman & cek tanggal selesai kontrak terbaru Anda.');
            }

            // === GENERATE TAGIHAN BARU untuk setiap bulan tambahan ===
            $kamarModel      = new KamarModel();
            $kamar           = $kamarModel->find($sewa['id_kamar']);
            $hargaSewa       = $kamar['harga_sewa'] ?? ($sewa['harga_sewa'] ?? 0);

            $bulanTerakhir   = $pembayaranModel->getBulanKeTerakhir($sewa['id_sewa']);
            $tanggalAcuan    = $tanggal_selesai_lama;

            for ($i = 1; $i <= $tambah_bulan; $i++) {
                $bulanKe    = $bulanTerakhir + $i;
                $jatuhTempo = date('Y-m-d', strtotime($tanggalAcuan . " +{$i} months"));

                $pembayaranModel->save([
                    'id_sewa'             => $sewa['id_sewa'],
                    'bulan_ke'            => $bulanKe,
                    'jumlah_bayar'        => $hargaSewa,
                    'tanggal_jatuh_tempo' => $jatuhTempo,
                    'status'              => 'belum_bayar',
                    'keterangan'          => 'Sewa bulan ke-' . $bulanKe . ' (Perpanjangan)',
                ]);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Database transaction failed');
            }

        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', '[Perpanjangan::ajukan] Gagal: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperpanjang kontrak: ' . $e->getMessage() . '. Tidak ada data yang berubah.');
        }

        // === NOTIFIKASI (di luar transaction) ===
        $notifModel = new NotifikasiModel();
        $notifModel->kirim(
            $id_user,
            'Perpanjangan Kontrak Berhasil',
            'Kontrak sewa kamar Anda (No. ' . ($sewa['nomor_kamar'] ?? '-') . ') berhasil diperpanjang selama ' . $tambah_bulan . ' bulan. Tanggal selesai baru: ' . date('d M Y', strtotime($tanggal_selesai_baru)) . '. Tagihan untuk ' . $tambah_bulan . ' bulan tambahan sudah dibuat, cek menu Pembayaran.',
            'kontrak'
        );

        // Notif ke admin
        $userModel = new UserModel();
        $admins = $userModel->where('role', 'admin')->findAll();
        foreach ($admins as $admin) {
            $notifModel->kirim(
                $admin['id_user'],
                'Perpanjangan Kontrak',
                session()->get('nama') . ' memperpanjang kontrak kamar No. ' . ($sewa['nomor_kamar'] ?? '-') . ' selama ' . $tambah_bulan . ' bulan. Tagihan baru otomatis dibuat.',
                'kontrak'
            );
        }

        return redirect()->to('/user/perpanjangan')->with('success', 'Perpanjangan sewa berhasil! Tagihan baru sudah dibuat, cek notifikasi untuk detail kontrak.');
    }
}