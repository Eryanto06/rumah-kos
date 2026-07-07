<?php

namespace App\Models;

use CodeIgniter\Model;

class PembayaranModel extends Model
{
    protected $table            = 'pembayaran';
    protected $primaryKey       = 'id_pembayaran';
    // FIX C8: 'total_denda' & 'denda_per_hari' dihapus (admin-only, pakai builder()).
    // 'status' dikembalikan karena INSERT tagihan baru & user upload butuh set status
    // (hardcoded di controller, bukan user input).
    protected $allowedFields    = [
        'id_sewa', 'bulan_ke', 'tanggal_bayar', 'tanggal_jatuh_tempo',
        'jumlah_bayar', 'bukti_bayar', 'kode_transaksi', 'keterangan', 'status'
    ];
    protected $useTimestamps    = false;

    public function getPembayaranWithDetail()
    {
        return $this->db->table('pembayaran p')
            ->select('p.*, u.nama as nama_user, k.nomor_kamar, k.kode_kamar')
            ->join('sewa s', 's.id_sewa = p.id_sewa', 'left')
            ->join('user u', 'u.id_user = s.id_user', 'left')
            ->join('kamar k', 'k.id_kamar = s.id_kamar', 'left')
            ->orderBy('p.id_pembayaran', 'DESC')
            ->get()->getResultArray();
    }

    public function getPembayaranByUser($id_user)
    {
        return $this->db->table('pembayaran p')
            ->select('p.*, k.nomor_kamar, k.kode_kamar')
            ->join('sewa s', 's.id_sewa = p.id_sewa', 'left')
            ->join('kamar k', 'k.id_kamar = s.id_kamar', 'left')
            ->where('s.id_user', $id_user)
            ->orderBy('p.bulan_ke', 'ASC')
            ->get()->getResultArray();
    }

    public function getPembayaranBySewa($id_sewa)
    {
        return $this->db->table('pembayaran p')
            ->select('p.*, k.nomor_kamar, k.kode_kamar')
            ->join('sewa s', 's.id_sewa = p.id_sewa', 'left')
            ->join('kamar k', 'k.id_kamar = s.id_kamar', 'left')
            ->where('p.id_sewa', $id_sewa)
            ->orderBy('p.bulan_ke', 'ASC')
            ->get()->getResultArray();
    }

    /**
     * Hitung denda otomatis (Per Hari)
     * 
     * FIX Bug #15: Denda dihitung berdasarkan selisih hari jatuh tempo dengan hari ini.
     * Denda harian = selisih_hari × denda_per_hari.
     * Ini selalu di-reset setiap hari (tidak akumulatif) karena sistem tidak punya
     * fitur "cicil denda sebagian". Denda total = hari terlambat × tarif harian.
     * 
     * Catatan: Kalau nanti ada fitur "bayar denda sebagian", perlu ubah logika ini
     * jadi akumulatif (total_denda = denda_lama + denda_hari_ini).
     */
    public function hitungDendaOtomatis($dendaPerHari = 5000)
    {
        $today = date('Y-m-d');

        $terlambat = $this->where('status', 'belum_bayar')
                          ->where('tanggal_jatuh_tempo IS NOT NULL')
                          ->where('tanggal_jatuh_tempo <', $today)
                          ->findAll();

        $totalUpdated = 0;
        foreach ($terlambat as $p) {
            // Null safety: skip kalau tanggal_jatuh_tempo null/kosong
            if (empty($p['tanggal_jatuh_tempo'])) {
                continue;
            }

            // FIX BUG #3 (review): HAPUS block skip denda.
            // Sebelumnya: kalau denda sudah dihitung sekali, cron skip selamanya
            // → user telat 10 hari tetap cuma bayar denda 1 hari.
            // Sekarang: selalu hitung ulang $totalDenda = $selisih_hari × $denda_per_hari.

            $jatuhTempo = new \DateTime($p['tanggal_jatuh_tempo']);
            $hariIni    = new \DateTime($today);
            $selisih    = $jatuhTempo->diff($hariIni)->days;

            // Denda harian = selisih hari × tarif per hari
            $totalDenda = $selisih * $dendaPerHari;

            // FIX C8延伸: pakai builder() karena 'denda_per_hari' & 'total_denda'
            // gak di $allowedFields (mass-assignment guard).
            $this->builder()->where('id_pembayaran', $p['id_pembayaran'])->update([
                'denda_per_hari' => $dendaPerHari,
                'total_denda'    => $totalDenda,
            ]);
            $totalUpdated++;
        }
        return $totalUpdated;
    }

    /**
     * Ambil tagihan yang JATUH TEMPO HARI INI (H-0)
     */
    public function getTagihanJatuhTempoHariIni()
    {
        $today = date('Y-m-d');
        return $this->db->table('pembayaran p')
            ->select('p.*, u.id_user, u.nama, k.nomor_kamar')
            ->join('sewa s', 's.id_sewa = p.id_sewa', 'left')
            ->join('user u', 'u.id_user = s.id_user', 'left')
            ->join('kamar k', 'k.id_kamar = s.id_kamar', 'left')
            ->where('p.status', 'belum_bayar')
            ->where('p.tanggal_jatuh_tempo', $today)
            ->get()->getResultArray();
    }

    /**
     * Ambil tagihan yang TELAT (jatuh tempo < hari ini, belum bayar)
     */
    public function getTagihanTerlambat()
    {
        $today = date('Y-m-d');
        return $this->db->table('pembayaran p')
            ->select('p.*, u.id_user, u.nama, k.nomor_kamar')
            ->join('sewa s', 's.id_sewa = p.id_sewa', 'left')
            ->join('user u', 'u.id_user = s.id_user', 'left')
            ->join('kamar k', 'k.id_kamar = s.id_kamar', 'left')
            ->where('p.status', 'belum_bayar')
            ->where('p.tanggal_jatuh_tempo <', $today)
            ->where('p.tanggal_jatuh_tempo IS NOT NULL')
            ->get()->getResultArray();
    }

    /**
     * Ambil nomor bulan_ke terakhir (terbesar) untuk sewa tertentu.
     */
    public function getBulanKeTerakhir($id_sewa)
    {
        $row = $this->where('id_sewa', $id_sewa)
                    ->orderBy('bulan_ke', 'DESC')
                    ->first();
        return $row ? (int) $row['bulan_ke'] : 0;
    }

    /**
     * Ambil ID-ID pembayaran dari $ids yang BENAR-BENAR milik id_user ini.
     * Dipakai untuk mencegah IDOR saat user upload bukti bayar.
     */
    public function filterIdMilikUser(array $ids, $id_user)
    {
        if (empty($ids)) {
            return [];
        }

        $rows = $this->db->table('pembayaran p')
            ->select('p.id_pembayaran')
            ->join('sewa s', 's.id_sewa = p.id_sewa', 'left')
            ->where('s.id_user', $id_user)
            // FIX BUG: sertakan status 'menunggu' supaya user bisa bayar deposit
            // SEBELUM admin approve sewa. Alur: user ajukan → bayar deposit → admin approve.
            // Tanpa 'menunggu' di sini, user gak bisa upload bukti deposit & admin gak bisa approve.
            ->whereIn('s.status', ['menunggu', 'aktif', 'disetujui'])
            ->whereIn('p.id_pembayaran', $ids)
            ->get()->getResultArray();

        return array_column($rows, 'id_pembayaran');
    }
}