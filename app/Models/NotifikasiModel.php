<?php

namespace App\Models;

use CodeIgniter\Model;

class NotifikasiModel extends Model
{
    protected $table            = 'notifikasi';
    protected $primaryKey       = 'id_notifikasi';
    protected $allowedFields    = ['id_user', 'judul', 'pesan', 'tipe', 'dibaca', 'created_at'];
    protected $useTimestamps    = false;

    public function getByUser($id_user)
    {
        return $this->where('id_user', $id_user)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    public function getUnreadCount($id_user)
    {
        return $this->where('id_user', $id_user)
                    ->where('dibaca', 0)
                    ->countAllResults();
    }

    public function kirim($id_user, $judul, $pesan, $tipe = 'info')
    {
        return $this->save([
            'id_user'    => $id_user,
            'judul'      => $judul,
            'pesan'      => $pesan,
            'tipe'       => $tipe,
            'dibaca'     => 0,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function tandaiDibaca($id_notifikasi)
    {
        return $this->update($id_notifikasi, ['dibaca' => 1]);
    }

    public function tandaiSemuaDibaca($id_user)
    {
        return $this->where('id_user', $id_user)
                    ->where('dibaca', 0)
                    ->set(['dibaca' => 1])
                    ->update();
    }
}