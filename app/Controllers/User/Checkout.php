<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\CheckoutModel;
use App\Models\SewaModel;
use App\Models\NotifikasiModel;
use App\Models\UserModel;

class Checkout extends BaseController
{
    public function index()
    {
        $id_user = session()->get('id_user');

        $checkoutModel = new CheckoutModel();
        $sewaModel     = new SewaModel();

        $sewaAktif = $sewaModel->getSewaAktifByUser($id_user);
        $pengajuanMenunggu = $checkoutModel->getPengajuanMenungguByUser($id_user);
        $riwayat = $checkoutModel->getByUser($id_user);

        $data = [
            'title'             => 'Pengajuan Check-Out',
            'sewaAktif'         => $sewaAktif,
            'pengajuanMenunggu' => $pengajuanMenunggu,
            'riwayat'           => $riwayat,
        ];

        return view('user/checkout/index', $data);
    }

    public function ajukan()
    {
        $id_user = session()->get('id_user');

        $checkoutModel = new CheckoutModel();
        $sewaModel     = new SewaModel();

        $sewaAktif = $sewaModel->getSewaAktifByUser($id_user);
        if (!$sewaAktif) {
            return redirect()->to('/user/checkout')->with('error', 'Anda tidak memiliki sewa aktif.');
        }

        // === CEK: Tidak boleh ada pengajuan checkout yang masih aktif (menunggu/inspeksi) ===
        $pengajuanAktif = $checkoutModel->where('id_user', $id_user)
                                        ->whereIn('status', ['menunggu', 'inspeksi'])
                                        ->countAllResults();
        if ($pengajuanAktif > 0) {
            return redirect()->to('/user/checkout')->with('error', 'Anda sudah mengajukan checkout dan sedang diproses admin. Tunggu hingga selesai.');
        }

        // === KEBIJAKAN BARU ===
        // User TIDAK PERLU melunasi semua tagihan untuk ajukan checkout.
        // Tagihan belum dibayar akan dihapus saat admin menyetujui checkout.
        // Sisa sewa (bulan belum dihuni) + deposit (setelah potongan kerusakan) dikembalikan admin.

        $rules = [
            'tanggal_checkout' => 'required|valid_date',
            'alasan'           => 'required|min_length[5]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $checkoutModel->save([
            'id_user'                    => $id_user,
            'id_sewa'                    => $sewaAktif['id_sewa'],
            'id_kamar'                   => $sewaAktif['id_kamar'],
            'tanggal_checkout_diajukan'  => $this->request->getPost('tanggal_checkout'),
            'alasan'                     => $this->request->getPost('alasan'),
            'status'                     => 'menunggu',
        ]);

        // === DETEKSI EARLY CHECKOUT untuk info notifikasi ===
        $tanggalCheckout = $this->request->getPost('tanggal_checkout');
        $tanggalSelesai  = $sewaAktif['tanggal_selesai'] ?? null;
        $isEarlyCheckout = ($tanggalSelesai && strtotime($tanggalCheckout) < strtotime($tanggalSelesai));

        // AUTO-REPLY KE USER - tipe 'checkout' supaya klik redirect ke /user/checkout
        $notifModel = new NotifikasiModel();
        $infoEarlyCheckout = $isEarlyCheckout
            ? "\n" . '- ⚠️ Karena Anda checkout SEBELUM kontrak berakhir, deposit akan DIPOTONG 50% secara otomatis sesuai kebijakan early checkout.'
            : '';
        $notifModel->kirim(
            $id_user,
            'Pengajuan Checkout Diterima',
            'Pengajuan checkout Anda diterima. Admin akan proses & menjadwalkan inspeksi kamar.' . "\n\n" .
            'Catatan:' . "\n" .
            '- Anda TIDAK perlu melunasi semua tagihan untuk checkout.' . "\n" .
            '- Tagihan yang masih belum dibayar akan dibatalkan saat checkout disetujui.' . "\n" .
            '- Sisa sewa (bulan belum dihuni) + deposit (setelah potongan kerusakan) akan dikembalikan ke Anda.' .
            $infoEarlyCheckout,
            'checkout'
        );

        // Notif ke admin - tipe 'checkout' supaya klik redirect ke /admin/checkout
        $userModel = new UserModel();
        $admins = $userModel->where('role', 'admin')->findAll();
        foreach ($admins as $admin) {
            $notifModel->kirim(
                $admin['id_user'],
                'Pengajuan Checkout Baru',
                session()->get('nama') . ' mengajukan checkout. Segera proses di menu Checkout untuk inspeksi kamar & pengembalian dana.',
                'checkout'
            );
        }

        return redirect()->to('/user/checkout')->with('success', 'Pengajuan check-out berhasil dikirim! Cek notifikasi untuk info selanjutnya.');
    }
}
