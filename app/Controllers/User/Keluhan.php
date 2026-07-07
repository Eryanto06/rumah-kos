<?php namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\KeluhanModel;
use App\Models\NotifikasiModel;
use App\Models\UserModel;
use App\Models\SewaModel;

class Keluhan extends BaseController {
    public function index() {
        $id_user = session()->get('id_user');
        $model   = new KeluhanModel();
        $sewaModel = new SewaModel();

        $sewaAktif = $sewaModel->getSewaAktifByUser($id_user);

        return view('user/keluhan/index', [
            'title'       => 'Keluhan',
            'keluhan'     => $model->getKeluhanByUser($id_user),
            'sewa_aktif'  => $sewaAktif,
            'is_penghuni' => !empty($sewaAktif),
        ]);
    }

    public function kirim() {
        $id_user = session()->get('id_user');
        $model   = new KeluhanModel();
        $notifModel = new NotifikasiModel();
        $sewaModel  = new SewaModel();

        $sewaAktif = $sewaModel->getSewaAktifByUser($id_user);
        $isPenghuni = !empty($sewaAktif);

        $kategoriPenghuni  = ['fasilitas_kamar','listrik_air','wifi','kebersihan','parkir','kebisingan','tetangga','keamanan','lainnya'];
        $kategoriPendaftar = ['kendala_akun','website_bug','status_sewa','info_kamar','tagihan_sewa','lainnya'];

        $kategoriDipilih = $this->request->getPost('kategori');
        $kategoriValid = $isPenghuni ? $kategoriPenghuni : $kategoriPendaftar;

        if (!in_array($kategoriDipilih, $kategoriValid)) {
            return redirect()->back()->with('error', 'Kategori tidak valid untuk status Anda.');
        }

        $isPrivate = $this->request->getPost('is_private') ? 1 : 0;
        // FIX BUG: untuk keluhan private (anonim), TETAP simpan id_user supaya
        // user bisa lihat riwayat keluhan mereka di halaman Keluhan.
        // Sebelumnya id_user diset NULL → keluhan private tidak muncul di riwayat user.
        // Admin UI tetap tampilkan "Anonim" kalau is_private=1 (lihat view admin/keluhan).
        // Admin bisa inspect DB langsung, tapi di UI nama tetap tersembunyi.
        $idPelapor = $isPrivate ? null : $id_user;
        $idUserForKeluhan = $id_user; // SELALU diisi supaya user bisa lihat riwayat

        $model->save([
            'id_user'    => $idUserForKeluhan,
            'judul'      => $this->request->getPost('judul'),
            'deskripsi'  => $this->request->getPost('deskripsi'),
            'kategori'   => $kategoriDipilih,
            'id_pelapor' => $idPelapor,
            'is_private' => $isPrivate,
            'prioritas'  => $this->request->getPost('prioritas'),
            'tanggal'    => date('Y-m-d H:i:s'),
            'status'     => 'menunggu',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $autoReply = $this->getAutoReplyKategori($kategoriDipilih, $isPenghuni);
        $notifModel->kirim(
            $id_user,
            'Keluhan Diterima: ' . $this->request->getPost('judul'),
            $autoReply,
            'keluhan'
        );

        $userModel = new UserModel();
        $admins = $userModel->where('role', 'admin')->findAll();
        $tipePelapor = $isPenghuni ? 'PENGHUNI' : 'PENDAFTAR';
        foreach ($admins as $admin) {
            $pesan = "[{$tipePelapor}] " . $this->request->getPost('judul');
            $notifModel->kirim($admin['id_user'], 'Keluhan Baru (' . $tipePelapor . ')', $pesan, 'keluhan');
        }

        return redirect()->to('/user/keluhan')->with('success', 'Keluhan berhasil dikirim! Cek notifikasi Anda untuk balasan otomatis dari sistem.');
    }

    private function getAutoReplyKategori($kategori, $isPenghuni) {
        $replies = [
            'fasilitas_kamar' => 'Keluhan fasilitas kamar Anda telah diterima. Tim maintenance akan memeriksa dalam 1x24 jam.',
            'listrik_air'     => 'Keluhan listrik/air Anda diterima. Tim teknisi akan segera mengecek. Estimasi penanganan: 2-4 jam.',
            'wifi'            => 'Keluhan Wi-Fi Anda diterima. Admin akan restart router/cek koneksi. Estimasi normal: 1-2 jam.',
            'kebersihan'      => 'Keluhan kebersihan diterima. Petugas kebersihan akan ditugaskan dalam 1x24 jam.',
            'parkir'          => 'Keluhan parkir diterima. Admin akan cek situasi & koordinasi dengan penghuni lain.',
            'kebisingan'      => 'Keluhan kebisingan diterima. Admin akan menegur pihak terkait secara privat.',
            'tetangga'        => 'Keluhan konflik tetangga diterima. Admin akan mediasi secara bijak.',
            'keamanan'        => 'LAPORAN URGENT DITERIMA! Admin akan segera menindaklanjuti.',
            'kendala_akun'    => 'Keluhan akun Anda diterima. Admin akan cek & balas via notifikasi dalam 1x24 jam.',
            'website_bug'     => 'Laporan bug website diterima. Tim IT akan investigasi.',
            'status_sewa'     => 'Pertanyaan status sewa Anda diterima. Admin akan cek pengajuan Anda & balas segera.',
            'info_kamar'      => 'Permintaan info kamar diterima. Admin akan kirim detail kamar tersedia via notifikasi/WA.',
            'tagihan_sewa'    => 'Pertanyaan tagihan/deposit diterima. Admin akan jelaskan rincian biaya & cara bayar.',
            'lainnya'         => 'Keluhan Anda diterima. Admin akan review & balas dalam 1x24 jam. Terima kasih.',
        ];

        return $replies[$kategori] ?? $replies['lainnya'];
    }
}