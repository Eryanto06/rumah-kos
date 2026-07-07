<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\PengaturanModel;
use App\Models\PembayaranModel;
use App\Models\NotifikasiModel;
use App\Models\SewaModel;

class DailyAutomation extends BaseCommand
{
    protected $group       = 'rumahkos';
    protected $name        = 'rumahkos:daily';
    protected $description = 'Jalankan otomatisasi harian (denda + notif tagihan + notif kontrak H-7,3,2,1,0)';

    public function run(array $params)
    {
        $pengaturanModel = new PengaturanModel();
        $pembayaranModel = new PembayaranModel();
        $notifModel      = new NotifikasiModel();
        $sewaModel       = new SewaModel();

        // === GUARD: Cegah dobel eksekusi ===
        if (!$pengaturanModel->tryClaimAutomationRun(date('Y-m-d'))) {
            CLI::write("Otomatisasi hari ini sudah pernah jalan. Skip.", 'yellow');
            return;
        }

        CLI::write("===== Otomatisasi Harian " . date('Y-m-d H:i:s') . " =====", 'yellow');

        $dendaUpdated = 0; $kontrakKirim = 0; $jatuhTempoKirim = 0; $terlambatKirim = 0;

        $dendaPerHari = (int) $pengaturanModel->get('denda_per_hari');
        if ($dendaPerHari > 0) {
            $dendaUpdated = $pembayaranModel->hitungDendaOtomatis($dendaPerHari);
            CLI::write("[DENDA] {$dendaUpdated} pembayaran diperbarui.", 'green');
        }

        $jatuhTempo = $pembayaranModel->getTagihanJatuhTempoHariIni();
        foreach ($jatuhTempo as $t) {
            $notifModel->kirim($t['id_user'], 'Tagihan Jatuh Tempo Hari Ini', 'Tagihan sewa kamar No. ' . $t['nomor_kamar'] . ' (Bulan ke-' . $t['bulan_ke'] . ') jatuh tempo HARI INI.', 'tagihan');
            $jatuhTempoKirim++;
        }

        $terlambat = $pembayaranModel->getTagihanTerlambat();
        foreach ($terlambat as $t) {
            $denda = $t['total_denda'] ?? 0;
            $notifModel->kirim($t['id_user'], 'Tagihan Terlambat - Denda Rp ' . number_format($denda, 0, ',', '.'), 'Tagihan telat. Denda: Rp ' . number_format($denda, 0, ',', '.') . '. Segera bayar!', 'tagihan');
            $terlambatKirim++;
        }

        $hariNotif = [7, 3, 2, 1, 0];
        foreach ($hariNotif as $h) {
            $sewaList = $sewaModel->getSewaBySisaHari($h);
            foreach ($sewaList as $s) {
                $pesan = $h == 0 ? 'Kontrak kamar ' . $s['nomor_kamar'] . ' berakhir HARI INI.' : 'Kontrak kamar ' . $s['nomor_kamar'] . ' sisa ' . $h . ' hari.';
                $notifModel->kirim($s['id_user'], 'Kontrak Sewa Sisa ' . $h . ' Hari', $pesan, 'kontrak');
                $kontrakKirim++;
            }
        }

        // FIX H22: markAutomationRun() tidak diperlukan - claim atomic di awal.
        file_put_contents(WRITEPATH . 'logs/automation.log', "[" . date('Y-m-d H:i:s') . "] DENDA: {$dendaUpdated} | JATUH TEMPO: {$jatuhTempoKirim} | TELAT: {$terlambatKirim} | KONTRAK: {$kontrakKirim}\n", FILE_APPEND);
        CLI::write("Selesai.", 'yellow');
    }
}