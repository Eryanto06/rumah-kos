<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\RawSql;

class CheckoutModel extends Model
{
    protected $table            = 'pengajuan_checkout';
    protected $primaryKey       = 'id_checkout';
    // FIX C8: field admin-only dihapus. 'status' dikembalikan karena INSERT
    // pengajuan baru perlu set 'menunggu' (hardcoded di controller, bukan user input).
    protected $allowedFields    = [
        'id_user', 'id_sewa', 'id_kamar', 'tanggal_checkout_diajukan',
        'alasan', 'status',
    ];
    protected $useTimestamps    = false;

    public function getAllWithDetail()
    {
        $orderRaw = new RawSql(
            "CASE WHEN c.status = 'menunggu' THEN 1 
                  WHEN c.status = 'inspeksi' THEN 2 
                  WHEN c.status = 'disetujui' THEN 3 
                  ELSE 4 END, c.id_checkout DESC"
        );

        return $this->db->table('pengajuan_checkout c')
            ->select('c.*, u.nama, u.no_hp, u.email' . rekening_select_clause('u') . ', s.id_kamar, s.deposit, s.tanggal_mulai, s.tanggal_selesai, s.durasi_bulan, k.nomor_kamar, k.kode_kamar, k.harga_sewa')
            ->join('user u', 'u.id_user = c.id_user', 'left')
            ->join('sewa s', 's.id_sewa = c.id_sewa', 'left')
            ->join('kamar k', 'k.id_kamar = s.id_kamar', 'left')
            ->orderBy($orderRaw)
            ->get()->getResultArray();
    }

    public function getByUser($id_user)
    {
        return $this->db->table('pengajuan_checkout c')
            ->select('c.*, k.nomor_kamar, k.kode_kamar')
            ->join('kamar k', 'k.id_kamar = c.id_kamar', 'left')
            ->where('c.id_user', $id_user)
            ->orderBy('c.id_checkout', 'DESC')
            ->get()->getResultArray();
    }

    public function getPengajuanMenungguByUser($id_user)
    {
        return $this->where('id_user', $id_user)
                    ->whereIn('status', ['menunggu', 'inspeksi'])
                    ->first();
    }

    public function getDetailForRefund($id)
    {
        return $this->db->table('pengajuan_checkout c')
            ->select('c.*, u.nama, u.no_hp, u.email' . rekening_select_clause('u') . ', s.tanggal_mulai, s.tanggal_selesai, s.durasi_bulan, s.deposit, k.nomor_kamar, k.kode_kamar, k.harga_sewa')
            ->join('user u', 'u.id_user = c.id_user', 'left')
            ->join('sewa s', 's.id_sewa = c.id_sewa', 'left')
            ->join('kamar k', 'k.id_kamar = s.id_kamar', 'left')
            ->where('c.id_checkout', $id)
            ->get()->getRowArray();
    }
}