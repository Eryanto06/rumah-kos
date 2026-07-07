<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'user';
    protected $primaryKey       = 'id_user';
    // FIX C7: 'role' dihapus dari $allowedFields untuk cegah mass-assignment.
    // Controller register/admin hardcode 'role' saat save(), tapi kalau ada
    // controller lain yang save($_POST) raw, user bisa POST role=admin.
    // Untuk set role, pakai method setRole() di bawah (dengan guard admin).
    protected $allowedFields    = [
        'nama', 'email', 'username', 'password', 'no_hp', 'foto',
        // FIX: tambah field rekening supaya user bisa terima refund (checkout, pindah, sewa ditolak)
        'nama_bank', 'nomor_rekening', 'nama_pemilik_rek',
        'ewallet_type', 'ewallet_number',
    ];
    protected $useTimestamps    = false;
    protected $useSoftDeletes    = false; // FIX: revert ke false supaya app jalan tanpa migration. Enable=true setelah jalankan 'php spark migrate' (migration sudah add kolom deleted_at).

    // FIX (defense-in-depth): hash password otomatis di model, jadi controller
    // mana pun yang lupa manggil password_hash() tetap aman.
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    protected function hashPassword(array $data): array
    {
        $isInsert = ($data['method'] ?? '') === 'insert';

        if (isset($data['data']['password']) && $data['data']['password'] !== '') {
            // FIX: pakai password_get_info() untuk deteksi hash mana pun (bcrypt/argon2)
            // supaya tidak re-hash kalau PHP default berubah ke argon2id di masa depan.
            $info = password_get_info($data['data']['password']);
            if ($info['algo'] === 0) {
                // Bukan hash — hash sekarang.
                $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
            }
        } else {
            if ($isInsert) {
                // FIX: tolak INSERT tanpa password — kalau di-unset, DB column bisa NULL
                // dan user dibuat tanpa password (gak bisa login tapi account ada).
                throw new \RuntimeException('Password wajib diisi saat membuat user baru.');
            }
            // Untuk update: jangan overwrite password dengan empty string.
            unset($data['data']['password']);
        }
        return $data;
    }

    public function findByUsername($username)
    {
        return $this->where('username', $username)->first();
    }

    public function findByEmail($email)
    {
        return $this->where('email', $email)->first();
    }

    public function getAllUser()
    {
        return $this->where('role', 'user')->findAll();
    }

    /**
     * Ambil pendaftar = user yang TIDAK punya sewa aktif/disetujui/menunggu
     * Termasuk user yang sewa-nya ditolak/selesai (belum sewa lagi)
     */
    public function getPendaftar()
    {
        return $this->db->table('user u')
            ->select('u.id_user, u.nama, u.email, u.username, u.no_hp, u.foto, u.role, "pendaftar" as status_penghuni')
            ->where('u.role', 'user')
            ->whereNotIn('u.id_user', function($builder) {
                $builder->select('id_user')->from('sewa')->whereIn('status', ['aktif', 'disetujui', 'menunggu']);
            })
            ->orderBy('u.id_user', 'DESC')
            ->get()->getResultArray();
    }

    public function countPendaftar()
    {
        return count($this->getPendaftar());
    }

    /**
     * Ambil ID semua pendaftar (untuk kirim notifikasi masal)
     */
    public function getIdPendaftar()
    {
        return $this->db->table('user u')
            ->select('u.id_user')
            ->where('u.role', 'user')
            ->whereNotIn('u.id_user', function($builder) {
                $builder->select('id_user')->from('sewa')->whereIn('status', ['aktif', 'disetujui', 'menunggu']);
            })
            ->get()->getResultArray();
    }

    /**
     * Ambil penghuni = user yang punya sewa aktif
     */
    public function getPenghuni()
    {
        return $this->db->table('user u')
            ->select('u.id_user, u.nama, u.email, u.username, u.no_hp, u.foto, u.role, s.status as status_sewa, s.tanggal_mulai, s.tanggal_selesai, k.nomor_kamar, k.kode_kamar')
            ->join('sewa s', 's.id_user = u.id_user', 'inner')
            ->join('kamar k', 'k.id_kamar = s.id_kamar', 'left')
            ->where('u.role', 'user')
            ->where('s.status', 'aktif')
            ->orderBy('k.nomor_kamar', 'ASC')
            ->get()->getResultArray();
    }

    public function countPenghuni()
    {
        return count($this->getPenghuni());
    }

    /**
     * Ambil ID semua penghuni aktif (untuk kirim notifikasi masal)
     */
    public function getIdPenghuniAktif()
    {
        return $this->db->table('user u')
            ->select('u.id_user')
            ->join('sewa s', 's.id_user = u.id_user', 'inner')
            ->where('u.role', 'user')
            ->where('s.status', 'aktif')
            ->get()->getResultArray();
    }

    /**
     * FIX C7: Set role user dengan guard eksplisit.
     * Dipakai oleh controller yang perlu ubah role (mis. admin promote/demote).
     *
     * @return bool true kalau berhasil, false kalau role tidak valid.
     */
    public function setRole(int $id_user, string $role): bool
    {
        $allowed = ['admin', 'user'];
        if (!in_array($role, $allowed, true)) {
            return false;
        }
        return $this->builder()
            ->where('id_user', $id_user)
            ->update(['role' => $role]);
    }

    /**
     * FIX H23: select u.* bocor hash password. Pakai method ini
     * untuk ambil user tanpa password.
     */
    public function findSafe($id = null)
    {
        return $this->select('id_user, nama, email, username, no_hp, foto, role, nama_bank, nomor_rekening, nama_pemilik_rek, ewallet_type, ewallet_number')
                     ->find($id);
    }
}