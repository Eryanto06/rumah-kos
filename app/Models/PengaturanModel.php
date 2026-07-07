<?php

namespace App\Models;

use CodeIgniter\Model;

class PengaturanModel extends Model
{
    protected $table            = 'pengaturan';
    protected $primaryKey       = 'id_setting';
    protected $allowedFields    = ['kunci', 'nilai', 'keterangan'];
    protected $useTimestamps    = false;

    public function get($kunci)
    {
        $row = $this->where('kunci', $kunci)->first();
        return $row ? $row['nilai'] : null;
    }

    public function setSetting($kunci, $nilai)
    {
        // FIX C9: atomic upsert cegah race condition (cron + dashboard bersamaan
        // bisa bikin 2 row untuk kunci yang sama). Pakai INSERT ... ON DUPLICATE KEY UPDATE.
        $sql = "INSERT INTO `{$this->table}` (`kunci`, `nilai`) VALUES (?, ?)
                ON DUPLICATE KEY UPDATE `nilai` = VALUES(`nilai`)";
        return $this->db->query($sql, [$kunci, $nilai]);
    }

    /**
     * FIX C10: atomic claim untuk automation run.
     * Dipakai di Dashboard::index & DailyAutomation command supaya
     * hanya 1 proses yang jalan per hari.
     *
     * @return bool true kalau berhasil claim (proses lain belum jalan hari ini).
     */
    public function tryClaimAutomationRun(string $today): bool
    {
        // FIX BUG #4 (review): Pakai INSERT ... ON DUPLICATE KEY UPDATE supaya
        // jalan juga saat baris automation_last_run belum ada di DB (first run).
        // Sebelumnya pakai UPDATE ... WHERE nilai != today -> match 0 baris kalau
        // baris belum ada -> return false -> automation tidak pernah jalan.
        $sql = "INSERT INTO `{$this->table}` (`kunci`, `nilai`) VALUES ('automation_last_run', ?)
                ON DUPLICATE KEY UPDATE `nilai` = IF(`nilai` = VALUES(`nilai`), `nilai`, VALUES(`nilai`))";
        $this->db->query($sql, [$today]);

        // Cek apakah nilai sekarang = today (artinya claim berhasil, atau sudah di-claim hari ini)
        $current = $this->get('automation_last_run');
        return $current === $today;
    }

    public function getAll()
    {
        return $this->findAll();
    }
}