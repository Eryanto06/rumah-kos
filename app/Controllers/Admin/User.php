<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\SewaModel;

class User extends BaseController
{
    public function index()
    {
        $model = new UserModel();
        $tab = $this->request->getGet('tab') ?: 'penghuni';

        $data = [
            'title'           => 'Data Penghuni & Pendaftar',
            'tab'             => $tab,
            'pendaftar'       => $model->getPendaftar(),
            'penghuni'        => $model->getPenghuni(),
            'total_pendaftar' => $model->countPendaftar(),
            'total_penghuni'  => $model->countPenghuni(),
            'users'           => $model->getAllUser(),
        ];
        return view('admin/user/index', $data);
    }

    public function hapus($id)
    {
        $model     = new UserModel();
        $sewaModel = new SewaModel();

        // Cek user exists
        $user = $model->find($id);
        if (!$user) {
            return redirect()->to('/admin/user')->with('error', 'User tidak ditemukan.');
        }

        // FIX: jangan izinkan hapus akun admin dari menu ini (bisa lockout)
        if ($user['role'] === 'admin') {
            return redirect()->to('/admin/user')
                             ->with('error', 'Admin tidak bisa dihapus dari menu ini. Gunakan menu Manajemen Admin.');
        }

        // Cegah hapus diri sendiri
        if ((string)$id === (string)session()->get('id_user')) {
            return redirect()->to('/admin/user')
                             ->with('error', 'Anda tidak bisa menghapus akun sendiri!');
        }

        // === CEK 1: User masih punya sewa aktif/menunggu? ===
        $sewaAktif = $sewaModel->where('id_user', $id)
                               ->whereIn('status', ['aktif', 'disetujui', 'menunggu'])
                               ->countAllResults();
        if ($sewaAktif > 0) {
            return redirect()->to('/admin/user')
                             ->with('error', 'TIDAK BISA HAPUS! User "' . $user['nama'] . '" masih memiliki ' . $sewaAktif . ' sewa aktif/menunggu. Selesaikan/tolak sewa-nya dulu.');
        }

        // === CEK 2: User masih punya tagihan belum lunas? ===
        $db = \Config\Database::connect();
        $tagihanBelumLunas = $db->table('pembayaran p')
                                ->join('sewa s', 's.id_sewa = p.id_sewa')
                                ->where('s.id_user', $id)
                                ->whereIn('p.status', ['belum_bayar', 'menunggu_verifikasi'])
                                ->countAllResults();
        if ($tagihanBelumLunas > 0) {
            return redirect()->to('/admin/user')
                             ->with('error', 'TIDAK BISA HAPUS! User masih punya ' . $tagihanBelumLunas . ' tagihan belum lunas.');
        }

        // === CEK 3: User masih punya pengajuan pindah/checkout menunggu? ===
        $pindahMenunggu = $db->table('pengajuan_pindah')->where('id_user', $id)->where('status', 'menunggu')->countAllResults();
        $checkoutMenunggu = $db->table('pengajuan_checkout')->where('id_user', $id)->whereIn('status', ['menunggu', 'inspeksi'])->countAllResults();
        if ($pindahMenunggu > 0 || $checkoutMenunggu > 0) {
            return redirect()->to('/admin/user')
                             ->with('error', 'TIDAK BISA HAPUS! User masih punya pengajuan pindah/checkout yang belum selesai. Tolak dulu pengajuannya.');
        }

        // Aman untuk hapus - data riwayat tetap tersimpan
        $model->delete($id);
        return redirect()->to('/admin/user')
                         ->with('success', 'User "' . $user['nama'] . '" berhasil dihapus. Riwayat sewa & pembayaran tetap tersimpan di sistem.');
    }
}