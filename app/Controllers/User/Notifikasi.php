<?php namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\NotifikasiModel;

class Notifikasi extends BaseController {

    public function index()
    {
        $model  = new NotifikasiModel();
        $idUser = session()->get('id_user');

        $data = [
            'title'        => 'Notifikasi',
            'notifikasi'   => $model->where('id_user', $idUser)->orderBy('created_at', 'DESC')->findAll(),
            'unread_count' => $model->getUnreadCount($idUser),
        ];
        return view('user/notifikasi/index', $data);
    }

    public function baca($id)
    {
        $model  = new NotifikasiModel();
        $idUser = session()->get('id_user');

        // CEK KEPEMILIKAN - cegah IDOR
        $notif = $model->find($id);
        if (!$notif || $notif['id_user'] != $idUser) {
            return redirect()->to('/user/notifikasi')->with('error', 'Akses ditolak. Notifikasi tidak ditemukan.');
        }

        // Tandai dibaca
        $model->tandaiDibaca($id);

        // === REDIRECT KE HALAMAN TERKAIT BERDASARKAN TIPE ===
        $redirectMap = [
            'keluhan'    => '/user/keluhan',
            'sewa'       => '/user/sewa', // FIX: Ganti dari kamar-saya ke sewa
            'pengumuman' => '/user/dashboard',
            'kontrak'    => '/user/perpanjangan',
            'tagihan'    => '/user/pembayaran',
            'pembayaran' => '/user/pembayaran',
            'checkout'   => '/user/checkout',
            'pindah'     => '/user/pindah-kamar',
            'info'       => '/user/dashboard',
        ];

        $targetUrl = $redirectMap[$notif['tipe']] ?? '/user/notifikasi';
        return redirect()->to($targetUrl);
    }

    public function bacaSemua()
    {
        $model  = new NotifikasiModel();
        $idUser = session()->get('id_user');
        $model->tandaiSemuaDibaca($idUser);
        return redirect()->to('/user/notifikasi')->with('success', 'Semua notifikasi ditandai dibaca.');
    }
}