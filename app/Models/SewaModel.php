<?php

namespace App\Models;

use CodeIgniter\Model;

class SewaModel extends Model
{
    protected $table            = 'sewa';
    protected $primaryKey       = 'id_sewa';
    // FIX C8: 'deposit_dikembalikan' dihapus (admin-only, pakai builder()).
    // 'status' dikembalikan karena INSERT sewa baru (pindah kamar) butuh set 'aktif'
    // (hardcoded di controller, bukan user input).
    protected $allowedFields    = [
        'id_user', 'id_kamar', 'tanggal_pengajuan', 'tanggal_mulai',
        'tanggal_selesai', 'durasi_bulan', 'deposit',
        'keterangan', 'status',
        'status_kunci', 'tanggal_ambil_kunci', 'lokasi_ambil_kunci'
    ];
    protected $useTimestamps    = false;

    public function getSewaWithDetail($status = null)
    {
        $builder = $this->db->table('sewa s')
            ->select('s.*, u.nama, u.email, u.no_hp, k.kode_kamar, k.nomor_kamar, k.harga_sewa')
            ->join('user u', 'u.id_user = s.id_user', 'left')
            ->join('kamar k', 'k.id_kamar = s.id_kamar', 'left');

        if (!empty($status)) {
            $builder->where('s.status', $status);
        }

        return $builder->orderBy('s.tanggal_pengajuan', 'DESC')
            ->get()->getResultArray();
    }

    /**
     * Hitung jumlah sewa per status (untuk tab filter di admin)
     */
    public function countByStatus()
    {
        $result = $this->db->table('sewa')
            ->select("
                COUNT(*) as total,
                SUM(CASE WHEN status = 'menunggu' THEN 1 ELSE 0 END) as menunggu,
                SUM(CASE WHEN status = 'aktif' THEN 1 ELSE 0 END) as aktif,
                SUM(CASE WHEN status = 'disetujui' THEN 1 ELSE 0 END) as disetujui,
                SUM(CASE WHEN status = 'ditolak' THEN 1 ELSE 0 END) as ditolak,
                SUM(CASE WHEN status = 'selesai' THEN 1 ELSE 0 END) as selesai
            ")
            ->get()
            ->getRowArray();

        return [
            'total'     => (int)($result['total'] ?? 0),
            'menunggu'  => (int)($result['menunggu'] ?? 0),
            'aktif'     => (int)($result['aktif'] ?? 0),
            'disetujui' => (int)($result['disetujui'] ?? 0),
            'ditolak'   => (int)($result['ditolak'] ?? 0),
            'selesai'   => (int)($result['selesai'] ?? 0),
        ];
    }

    public function getSewaByUser($id_user)
    {
        return $this->db->table('sewa s')
            ->select('s.*, k.kode_kamar, k.nomor_kamar, k.harga_sewa, k.fasilitas')
            ->join('kamar k', 'k.id_kamar = s.id_kamar', 'left')
            ->where('s.id_user', $id_user)
            ->orderBy('s.tanggal_pengajuan', 'DESC')
            ->get()->getResultArray();
    }

    public function getSewaAktifByUser($id_user)
    {
        // FIX H26: hanya 'aktif' (sudah ambil kunci). 'disetujui' = sudah approve
        // tapi belum ambil kunci, belum resmi menghuni.
        return $this->db->table('sewa s')
            ->select('s.*, k.kode_kamar, k.nomor_kamar, k.harga_sewa, k.fasilitas')
            ->join('kamar k', 'k.id_kamar = s.id_kamar', 'left')
            ->where('s.id_user', $id_user)
            ->where('s.status', 'aktif')
            ->get()->getRowArray();
    }

    /**
     * FIX H26: method baru untuk sewa 'disetujui' (sudah approve, belum ambil kunci).
     */
    public function getSewaDisetujuiByUser($id_user)
    {
        return $this->db->table('sewa s')
            ->select('s.*, k.kode_kamar, k.nomor_kamar, k.harga_sewa, k.fasilitas')
            ->join('kamar k', 'k.id_kamar = s.id_kamar', 'left')
            ->where('s.id_user', $id_user)
            ->where('s.status', 'disetujui')
            ->get()->getRowArray();
    }

    public function getPengajuanTerbaru()
    {
        return $this->db->table('sewa s')
            ->select('s.*, u.nama, k.nomor_kamar')
            ->join('user u', 'u.id_user = s.id_user', 'left')
            ->join('kamar k', 'k.id_kamar = s.id_kamar', 'left')
            ->where('s.status', 'menunggu')
            ->orderBy('s.tanggal_pengajuan', 'DESC')
            ->limit(5)
            ->get()->getResultArray();
    }

    public function getTotalPenghuni()
    {
        return $this->where('status', 'aktif')->countAllResults();
    }

    public function getSewaHampirHabis($hari = 30)
    {
        $tanggalBatas = date('Y-m-d', strtotime("+{$hari} days"));
        $today = date('Y-m-d');

        return $this->db->table('sewa s')
            ->select('s.*, u.nama, u.email, k.nomor_kamar, k.kode_kamar')
            ->join('user u', 'u.id_user = s.id_user', 'left')
            ->join('kamar k', 'k.id_kamar = s.id_kamar', 'left')
            ->where('s.status', 'aktif')
            ->where('s.tanggal_selesai >=', $today)
            ->where('s.tanggal_selesai <=', $tanggalBatas)
            ->orderBy('s.tanggal_selesai', 'ASC')
            ->get()->getResultArray();
    }

    public function getSewaBySisaHari($hari)
    {
        $targetDate = date('Y-m-d', strtotime("+{$hari} days"));
        return $this->db->table('sewa s')
            ->select('s.*, u.id_user, u.nama, u.email, k.nomor_kamar, k.kode_kamar')
            ->join('user u', 'u.id_user = s.id_user', 'left')
            ->join('kamar k', 'k.id_kamar = s.id_kamar', 'left')
            ->where('s.status', 'aktif')
            ->where('s.tanggal_selesai', $targetDate)
            ->get()->getResultArray();
    }
}