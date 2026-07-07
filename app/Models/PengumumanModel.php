<?php

namespace App\Models;

use CodeIgniter\Model;

class PengumumanModel extends Model
{
    protected $table            = 'pengumuman';
    protected $primaryKey       = 'id_pengumuman';
    protected $allowedFields    = [
        'judul', 'isi', 'waktu_mulai', 'waktu_selesai', 
        'tanggal_mulai', 'tanggal_selesai', 'status', 
        'target', 'created_by', 'created_at'
    ];
    protected $useTimestamps    = false;
    protected $useSoftDeletes    = false; // FIX: revert ke false supaya app jalan tanpa migration. Enable=true setelah jalankan 'php spark migrate' (migration sudah add kolom deleted_at).

    public function getAll()
    {
        return $this->orderBy('id_pengumuman', 'DESC')->findAll();
    }
}