<?php

namespace App\Models;

use CodeIgniter\Model;

class KamarModel extends Model
{
    protected $table            = 'kamar';
    protected $primaryKey       = 'id_kamar';
    protected $allowedFields    = ['kode_kamar', 'nomor_kamar', 'harga_sewa', 'fasilitas', 'foto', 'status'];
    protected $useTimestamps    = false;
    protected $useSoftDeletes    = false; // FIX: revert ke false supaya app jalan tanpa migration. Enable=true setelah jalankan 'php spark migrate' (migration sudah add kolom deleted_at).

    public function getKamarTersedia()
    {
        return $this->where('status', 'tersedia')->findAll();
    }

    public function getTotalKamar()
    {
        return $this->countAll();
    }

    public function getKamarKosong()
    {
        return $this->where('status', 'tersedia')->countAllResults();
    }

    public function getKamarTerisi()
    {
        return $this->where('status', 'terisi')->countAllResults();
    }
}
