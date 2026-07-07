<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\PindahKamarModel;
use App\Models\SewaModel;
use App\Models\KamarModel;
use App\Models\PembayaranModel;
use App\Models\NotifikasiModel;
use App\Models\UserModel;
use App\Models\PengaturanModel;

class PindahKamar extends BaseController
{
    public function index()
    {
        $id_user = session()->get('id_user');

        $pindahModel = new PindahKamarModel();
        $sewaModel   = new SewaModel();
        $kamarModel  = new KamarModel();

        $sewaAktif = $sewaModel->getSewaAktifByUser($id_user);
        $pengajuanMenunggu = $pindahModel->getPengajuanMenungguByUser($id_user);
        $riwayat = $pindahModel->getByUser($id_user);
        $kamarTersedia = $kamarModel->getKamarTersedia();

        // Ambil konfigurasi kaliDeposit dari pengaturan sistem
        $pengaturanModel = new PengaturanModel();
        $kaliDeposit = (int) $pengaturanModel->get('default_deposit_kali') ?: 2;
        $depositLama = $sewaAktif['deposit'] ?? 0;

        $data = [
            'title'             => 'Pindah Kamar',
            'sewaAktif'         => $sewaAktif,
            'pengajuanMenunggu' => $pengajuanMenunggu,
            'riwayat'           => $riwayat,
            'kamarTersedia'     => $kamarTersedia,
            'kaliDeposit'       => $kaliDeposit,
            'depositLama'       => $depositLama,
        ];

        return view('user/pindah-kamar/index', $data);
    }

    public function ajukan()
    {
        $id_user = session()->get('id_user');

        // === VALIDASI SESSION ===
        if (empty($id_user)) {
            return redirect()->to('/login')
                             ->with('error', 'Session habis. Silakan login kembali.');
        }

        $pindahModel = new PindahKamarModel();
        $sewaModel   = new SewaModel();

        $sewaAktif = $sewaModel->getSewaAktifByUser($id_user);
        if (!$sewaAktif) {
            return redirect()->to('/user/pindah-kamar')
                             ->with('error', 'Anda tidak memiliki sewa aktif. Tidak bisa mengajukan pindah kamar.');
        }

        $sudahAda = $pindahModel->getPengajuanMenungguByUser($id_user);
        if ($sudahAda) {
            return redirect()->to('/user/pindah-kamar')
                             ->with('error', 'Anda sudah memiliki pengajuan pindah yang sedang menunggu persetujuan admin.');
        }

        // === CEK TAGIHAN WAJIB LUNAS (Bulan Ini & Bulan Depan) ===
        $pembayaranModel = new PembayaranModel();
        $batasWajibLunas = date('Y-m-t', strtotime('+1 month'));

        $tunggakanWajib = $pembayaranModel
            ->where('id_sewa', $sewaAktif['id_sewa'])
            ->whereIn('status', ['belum_bayar', 'menunggu_verifikasi'])
            ->where('tanggal_jatuh_tempo <=', $batasWajibLunas)
            ->countAllResults();

        if ($tunggakanWajib > 0) {
            return redirect()->to('/user/pindah-kamar')
                             ->with('error', 'TIDAK BISA PINDAH! Anda wajib melunasi tagihan untuk bulan ini dan bulan depan terlebih dahulu sebelum mengajukan pindah kamar.');
        }

        $rules = [
            'id_kamar_baru' => 'required|numeric',
            'alasan'        => 'required|min_length[5]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $idKamarBaru = $this->request->getPost('id_kamar_baru');

        if ($idKamarBaru == $sewaAktif['id_kamar']) {
            return redirect()->back()->with('error', 'Kamar baru tidak boleh sama dengan kamar yang Anda tempati sekarang.');
        }

        $kamarModel = new KamarModel();
        $kamarBaru = $kamarModel->find($idKamarBaru);
        if (!$kamarBaru || $kamarBaru['status'] !== 'tersedia') {
            return redirect()->back()->with('error', 'Kamar yang Anda pilih sudah tidak tersedia. Silakan pilih kamar lain.');
        }

        // === SIMPAN PENGAJUAN ===
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $pindahModel->save([
                'id_user'           => $id_user,
                'id_sewa_lama'      => $sewaAktif['id_sewa'],
                'id_kamar_lama'     => $sewaAktif['id_kamar'],
                'id_kamar_baru'     => $idKamarBaru,
                'alasan'            => $this->request->getPost('alasan'),
                'tanggal_pengajuan' => date('Y-m-d'),
                'status'            => 'menunggu',
            ]);

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Gagal menyimpan pengajuan pindah kamar ke database.');
            }
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', '[PindahKamar::ajukan] Gagal save pengajuan: ' . $e->getMessage());
            return redirect()->to('/user/pindah-kamar')
                             ->with('error', 'Gagal mengajukan pindah kamar: ' . $e->getMessage());
        }

        // === KIRIM NOTIFIKASI KE USER & ADMIN ===
        $notifModel = new NotifikasiModel();
        $pengaturanModel2 = new PengaturanModel();
        $kaliDep = (int) $pengaturanModel2->get('default_deposit_kali') ?: 2;
        $depLama = $sewaAktif['deposit'] ?? 0;
        $depBaru = $kamarBaru['harga_sewa'] * $kaliDep;
        $selisihDep = $depBaru - $depLama;

        $pesanUser = 'Pengajuan pindah kamar Anda diterima. Admin akan cek ketersediaan kamar tujuan & menyetujui dalam 1x24 jam. Mohon tunggu konfirmasi.';
        $pesanUser .= "\n\n📊 Info Deposit Kamar:";
        $pesanUser .= "\n• Deposit lama (kamar Anda sekarang): Rp " . number_format($depLama,0,',','.');
        $pesanUser .= "\n• Deposit baru (kamar tujuan): Rp " . number_format($depBaru,0,',','.');
        if ($selisihDep > 0) {
            $pesanUser .= "\n\n💰 ANDA WAJIB BAYAR SELISIH DEPOSIT: Rp " . number_format($selisihDep,0,',','.');
            $pesanUser .= "\n   (karena kamar baru lebih mahal dari kamar lama)";
            $pesanUser .= "\n   Selisih ini akan jadi tagihan tambahan setelah pindah disetujui admin.";
        } elseif ($selisihDep < 0) {
            $pesanUser .= "\n\n💰 ANDA DAPAT UANG KEMBALIAN: Rp " . number_format(abs($selisihDep),0,',','.');
            $pesanUser .= "\n   (karena kamar baru lebih murah dari kamar lama)";
            $pesanUser .= "\n   Uang kembalian akan ditransfer admin setelah pindah disetujui.";
        } else {
            $pesanUser .= "\n\n✅ Tidak ada selisih deposit. Deposit lama langsung dipindahkan ke kamar baru.";
        }
        $pesanUser .= "\n\n⚠️ Catatan: Deposit lama bisa dipotong kalau kamar lama rusak/kotor saat inspeksi.";

        // Notif ke USER (yang ajukan)
        try {
            $resultUser = $notifModel->kirim(
                $id_user,
                'Pengajuan Pindah Kamar Diterima',
                $pesanUser,
                'pindah'
            );
            if ($resultUser === false) {
                log_message('error', '[PindahKamar::ajukan] NotifModel->kirim() ke USER return false. id_user=' . $id_user);
            } else {
                log_message('info', '[PindahKamar::ajukan] Notif ke USER berhasil. id_user=' . $id_user);
            }
        } catch (\Exception $e) {
            log_message('error', '[PindahKamar::ajukan] Exception notif ke USER: ' . $e->getMessage());
        }

        // Notif ke SEMUA ADMIN
        try {
            $userModel = new UserModel();
            $admins = $userModel->where('role', 'admin')->findAll();

            if (empty($admins)) {
                log_message('error', '[PindahKamar::ajukan] Tidak ada admin ditemukan di tabel user!');
            } else {
                $namaPengaju = session()->get('nama') ?? 'User #' . $id_user;
                foreach ($admins as $admin) {
                    $notifModel->kirim(
                        $admin['id_user'],
                        'Pengajuan Pindah Kamar Baru',
                        $namaPengaju . ' mengajukan pindah kamar. Segera review di menu Pindah Kamar.',
                        'pindah'
                    );
                }
                log_message('info', '[PindahKamar::ajukan] Notif ke ' . count($admins) . ' admin berhasil.');
            }
        } catch (\Exception $e) {
            log_message('error', '[PindahKamar::ajukan] Exception notif ke ADMIN: ' . $e->getMessage());
        }

        return redirect()->to('/user/pindah-kamar')
                         ->with('success', 'Pengajuan pindah kamar berhasil dikirim! Cek notifikasi untuk info selanjutnya.');
    }
}