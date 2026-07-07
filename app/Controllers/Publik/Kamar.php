<?php

namespace App\Controllers\Publik;

use App\Controllers\BaseController;
use App\Models\KamarModel;
use App\Models\PengaturanModel;
use App\Models\UserModel;

class Kamar extends BaseController
{
    /**
     * Halaman publik: daftar kamar yang tersedia (tanpa login)
     */
    public function index()
    {
        $model = new KamarModel();

        $data = [
            'title'        => 'Kamar Tersedia',
            'kamar'        => $model->getKamarTersedia(),
            'total_kamar'  => $model->getTotalKamar(),
            'kamar_kosong' => $model->getKamarKosong(),
        ];

        return view('publik/kamar/index', $data);
    }

    /**
     * Halaman publik: detail kamar (tanpa login)
     * Lengkap dengan estimasi biaya, ketentuan sewa, kontak admin
     */
    public function detail($id = null)
    {
        $model = new KamarModel();
        $kamar = $model->find($id);

        if (!$kamar) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Kamar tidak ditemukan.');
        }

        // Ambil setting dari tabel pengaturan
        $pengaturan = new PengaturanModel();
        $userModel  = new UserModel();

        $defaultDepositKali = (int) $pengaturan->get('default_deposit_kali') ?: 2;
        $durasiMinimal      = (int) $pengaturan->get('durasi_minimal') ?: 1;
        $durasiMaksimal     = (int) $pengaturan->get('durasi_maksimal') ?: 24;
        $dendaPerHari       = (int) $pengaturan->get('denda_per_hari') ?: 5000;
        $batasTanggalBayar  = (int) $pengaturan->get('batas_tanggal_bayar') ?: 5;

        // Hitung estimasi biaya awal (deposit + 1 bulan pertama)
        $depositNominal    = $kamar['harga_sewa'] * $defaultDepositKali;
        $estimasiTotalAwal = $depositNominal + $kamar['harga_sewa'];

        // Ambil data admin (untuk kontak WhatsApp)
        $admin = $userModel->where('role', 'admin')->first();

        $data = [
            'title'                => 'Detail Kamar No. ' . $kamar['nomor_kamar'],
            'kamar'                => $kamar,
            'deposit_kali'         => $defaultDepositKali,
            'deposit_nominal'      => $depositNominal,
            'estimasi_total_awal'  => $estimasiTotalAwal,
            'durasi_minimal'       => $durasiMinimal,
            'durasi_maksimal'      => $durasiMaksimal,
            'denda_per_hari'       => $dendaPerHari,
            'batas_tanggal_bayar'  => $batasTanggalBayar,
            'admin'                => $admin,
        ];

        return view('publik/kamar/detail', $data);
    }
}