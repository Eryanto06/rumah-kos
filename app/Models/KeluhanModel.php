<?php

namespace App\Models;

use CodeIgniter\Model;

class KeluhanModel extends Model
{
    protected $table            = 'keluhan';
    protected $primaryKey       = 'id_keluhan';
    protected $allowedFields    = [
        'id_user', 'judul', 'deskripsi', 'kategori', 'id_pelapor',
        'is_private', 'prioritas', 'tanggal', 'status', 'balasan',
        'created_at'
    ];
    protected $useTimestamps    = false;
    protected $useSoftDeletes    = false; // FIX: revert ke false supaya app jalan tanpa migration. Enable=true setelah jalankan 'php spark migrate' (migration sudah add kolom deleted_at).

    /**
     * Ambil semua keluhan + info user, dengan filter opsional
     * 
     * @param string|null $kategori Filter kategori
     * @param string|null $status Filter status
     * @param string|null $jenisPelapor Filter: 'penghuni' / 'pendaftar' / null (semua)
     */
    public function getKeluhanWithUser($kategori = null, $status = null, $jenisPelapor = null)
    {
        $builder = $this->db->table('keluhan k')
            ->select("k.*, u.nama as nama_user, u.email, u.no_hp,
                      p.nama as nama_pelapor,
                      (SELECT COUNT(*) FROM sewa s WHERE s.id_user = k.id_user AND s.status = 'aktif') as is_penghuni")
            ->join('user u', 'u.id_user = k.id_user', 'left')
            ->join('user p', 'p.id_user = k.id_pelapor', 'left');

        if (!empty($kategori)) {
            $builder->where('k.kategori', $kategori);
        }
        if (!empty($status)) {
            $builder->where('k.status', $status);
        }

        // Filter jenis pelapor: penghuni (punya sewa aktif) atau pendaftar (belum sewa)
        if ($jenisPelapor === 'penghuni') {
            $builder->having('is_penghuni >', 0);
        } elseif ($jenisPelapor === 'pendaftar') {
            $builder->having('is_penghuni', 0);
        }

        $builder->orderBy('k.status', 'ASC');
        $builder->orderBy('k.tanggal', 'DESC');
        $builder->orderBy('k.id_keluhan', 'DESC');

        return $builder->get()->getResultArray();
    }

    /**
     * Ambil 1 keluhan + info user untuk halaman detail
     */
    public function getKeluhanDetail($id)
    {
        return $this->db->table('keluhan k')
            ->select("k.*, u.nama as nama_user, u.email, u.no_hp,
                      p.nama as nama_pelapor,
                      (SELECT COUNT(*) FROM sewa s WHERE s.id_user = k.id_user AND s.status = 'aktif') as is_penghuni")
            ->join('user u', 'u.id_user = k.id_user', 'left')
            ->join('user p', 'p.id_user = k.id_pelapor', 'left')
            ->where('k.id_keluhan', $id)
            ->get()->getRowArray();
    }

    public function getKeluhanByUser($id_user)
    {
        return $this->db->table('keluhan k')
            ->select('k.*, u.nama as nama_pelapor')
            ->join('user u', 'u.id_user = k.id_pelapor', 'left')
            ->where('k.id_user', $id_user)
            ->orderBy('k.tanggal', 'DESC')
            ->orderBy('k.id_keluhan', 'DESC')
            ->get()->getResultArray();
    }

    public function getKeluhanTerbaru()
    {
        return $this->db->table('keluhan k')
            ->select('k.*, u.nama as nama_user, p.nama as nama_pelapor')
            ->join('user u', 'u.id_user = k.id_user', 'left')
            ->join('user p', 'p.id_user = k.id_pelapor', 'left')
            ->orderBy('k.tanggal', 'DESC')
            ->orderBy('k.id_keluhan', 'DESC')
            ->limit(5)
            ->get()->getResultArray();
    }

    /**
     * Hitung jumlah keluhan per jenis pelapor
     */
    public function countByJenisPelapor()
    {
        $result = $this->db->table('keluhan k')
            ->select("
                COUNT(*) as total,
                SUM(CASE WHEN (SELECT COUNT(*) FROM sewa s WHERE s.id_user = k.id_user AND s.status = 'aktif') > 0 THEN 1 ELSE 0 END) as penghuni,
                SUM(CASE WHEN (SELECT COUNT(*) FROM sewa s WHERE s.id_user = k.id_user AND s.status = 'aktif') = 0 THEN 1 ELSE 0 END) as pendaftar
            ")
            ->get()
            ->getRowArray();

        return [
            'total'     => (int)($result['total'] ?? 0),
            'penghuni'  => (int)($result['penghuni'] ?? 0),
            'pendaftar' => (int)($result['pendaftar'] ?? 0),
        ];
    }
}