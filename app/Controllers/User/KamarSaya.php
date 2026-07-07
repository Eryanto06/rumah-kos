<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;

class KamarSaya extends BaseController
{
    public function index()
    {
        $id_user = session()->get('id_user');

        $db = \Config\Database::connect();
        $sewa = $db->table('sewa s')
            ->select('s.*, k.kode_kamar, k.nomor_kamar, k.harga_sewa, k.fasilitas, k.foto')
            ->join('kamar k', 'k.id_kamar = s.id_kamar', 'left')
            ->where('s.id_user', $id_user)
            ->where('s.status', 'aktif')
            ->get()->getRowArray();

        return view('user/kamar-saya', [
            'title' => 'Kamar Saya',
            'sewa'  => $sewa,
        ]);
    }
}