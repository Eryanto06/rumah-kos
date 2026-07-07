<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\NotifikasiModel;

class Notifikasi extends BaseController
{
    public function index()
    {
        $model = new NotifikasiModel();
        $idUser = session()->get('id_user');

        $data = [
            'title'        => 'Notifikasi',
            'notifikasi'   => $model->where('id_user', $idUser)->orderBy('created_at', 'DESC')->findAll(),
            'unread_count' => $model->getUnreadCount($idUser),
        ];
        return view('admin/notifikasi/index', $data);
    }

    public function baca($id)
    {
        $model  = new NotifikasiModel();
        $idUser = session()->get('id_user');

        // CEK KEPEMILIKAN - cegah IDOR (user akses notif orang lain)
        $notif = $model->find($id);
        if (!$notif || $notif['id_user'] != $idUser) {
            return redirect()->to('/admin/notifikasi')->with('error', 'Akses ditolak. Notifikasi tidak ditemukan.');
        }

        // Tandai dibaca
        $model->tandaiDibaca($id);

        // === REDIRECT KE HALAMAN TERKAIT BERDASARKAN TIPE ===
        $redirectMap = [
            'keluhan'    => '/admin/keluhan',
            'sewa'       => '/admin/sewa',
            'pengumuman' => '/admin/pengumuman',
            'user_baru'  => '/admin/user',
            'pembayaran' => '/admin/pembayaran',
            'checkout'   => '/admin/checkout',
            'pindah'     => '/admin/pindah-kamar',
            'kontrak'    => '/admin/sewa',
            'tagihan'    => '/admin/pembayaran',
            'info'       => '/admin/dashboard',
        ];

        $targetUrl = $redirectMap[$notif['tipe']] ?? '/admin/notifikasi';
        return redirect()->to($targetUrl);
    }

    public function bacaSemua()
    {
        $model  = new NotifikasiModel();
        $idUser = session()->get('id_user');
        $model->tandaiSemuaDibaca($idUser);
        return redirect()->to('/admin/notifikasi')->with('success', 'Semua notifikasi ditandai dibaca.');
    }
}