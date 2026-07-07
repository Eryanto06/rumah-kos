<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\RawSql;

class PindahKamarModel extends Model
{
    protected $table            = 'pengajuan_pindah';
    protected $primaryKey       = 'id_pindah';
    // FIX C8: field admin-only dihapus. 'status' dikembalikan karena INSERT
    // pengajuan baru perlu set 'menunggu' (hardcoded di controller, bukan user input).
    protected $allowedFields    = [
        'id_user', 'id_sewa_lama', 'id_kamar_lama', 'id_kamar_baru',
        'alasan', 'tanggal_pengajuan', 'status',
    ];
    protected $useTimestamps    = false;

    public function getAllWithDetail()
    {
        $orderRaw = new RawSql(
            "CASE WHEN p.status = 'menunggu' THEN 1 
                  WHEN p.status = 'disetujui' THEN 2 
                  ELSE 3 END, p.id_pindah DESC"
        );

        return $this->db->table('pengajuan_pindah p')
            ->select('p.*, u.nama as nama_user, u.no_hp' . rekening_select_clause('u') . ',
                      kl.nomor_kamar as nomor_kamar_lama, kl.kode_kamar as kode_kamar_lama, kl.harga_sewa as harga_lama,
                      kb.nomor_kamar as nomor_kamar_baru, kb.kode_kamar as kode_kamar_baru, kb.harga_sewa as harga_baru,
                      s.tanggal_mulai, s.tanggal_selesai, s.durasi_bulan')
            ->join('user u', 'u.id_user = p.id_user', 'left')
            ->join('sewa s', 's.id_sewa = p.id_sewa_lama', 'left')
            ->join('kamar kl', 'kl.id_kamar = p.id_kamar_lama', 'left')
            ->join('kamar kb', 'kb.id_kamar = p.id_kamar_baru', 'left')
            ->orderBy($orderRaw)
            ->get()->getResultArray();
    }

    public function getByUser($id_user)
    {
        return $this->db->table('pengajuan_pindah p')
            ->select('p.*, kl.nomor_kamar as nomor_kamar_lama, kl.kode_kamar as kode_kamar_lama,
                      kb.nomor_kamar as nomor_kamar_baru, kb.kode_kamar as kode_kamar_baru, kb.harga_sewa as harga_baru')
            ->join('kamar kl', 'kl.id_kamar = p.id_kamar_lama', 'left')
            ->join('kamar kb', 'kb.id_kamar = p.id_kamar_baru', 'left')
            ->where('p.id_user', $id_user)
            ->orderBy('p.id_pindah', 'DESC')
            ->get()->getResultArray();
    }

    public function getPengajuanMenungguByUser($id_user)
    {
        return $this->where('id_user', $id_user)
                    ->whereIn('status', ['menunggu'])
                    ->first();
    }
}