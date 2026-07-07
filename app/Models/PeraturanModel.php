<?php

namespace App\Models;

use CodeIgniter\Model;

class PeraturanModel extends Model
{
    protected $table            = 'peraturan';
    protected $primaryKey       = 'id_peraturan';
    protected $allowedFields    = ['judul', 'isi', 'kategori', 'urutan', 'status', 'created_at'];
    protected $useTimestamps    = false;
    protected $useSoftDeletes    = false; // FIX: revert ke false supaya app jalan tanpa migration. Enable=true setelah jalankan 'php spark migrate' (migration sudah add kolom deleted_at).

    public function getAktif()
    {
        return $this->where('status', 'aktif')
                    ->orderBy('kategori', 'ASC')
                    ->orderBy('urutan', 'ASC')
                    ->findAll();
    }

    public function getAll()
    {
        return $this->orderBy('kategori', 'ASC')
                    ->orderBy('urutan', 'ASC')
                    ->findAll();
    }

    public function getGroupedByKategori()
    {
        $all = $this->getAktif();
        $grouped = [];
        foreach ($all as $p) {
            $grouped[$p['kategori']][] = $p;
        }
        return $grouped;
    }
}