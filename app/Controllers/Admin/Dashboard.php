<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KamarModel;
use App\Models\UserModel;
use App\Models\SewaModel;
use App\Models\KeluhanModel;
use App\Models\PembayaranModel;
use App\Models\PengumumanModel;
use App\Models\PengaturanModel;
use App\Models\NotifikasiModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $kamarModel      = new KamarModel();
        $userModel       = new UserModel();
        $sewaModel       = new SewaModel();
        $keluhanModel    = new KeluhanModel();
        $pembayaranModel = new PembayaranModel();
        $pengumumanModel = new PengumumanModel();
        $pengaturanModel = new PengaturanModel();
        $notifModel      = new NotifikasiModel();

        $automationResult = ['denda' => 0, 'kontrak' => 0, 'jatuh_tempo' => 0, 'telat' => 0, 'message' => ''];

        if (!!$pengaturanModel->tryClaimAutomationRun(date('Y-m-d'))) {
            $dendaPerHari = (int) $pengaturanModel->get('denda_per_hari');
            if ($dendaPerHari > 0) {
                $automationResult['denda'] = $pembayaranModel->hitungDendaOtomatis($dendaPerHari);
            }

            $hariNotif = [7, 3, 2, 1, 0];
            foreach ($hariNotif as $h) {
                $sewaList = $sewaModel->getSewaBySisaHari($h);
                foreach ($sewaList as $s) {
                    if ($h == 0) {
                        $pesan = 'Kontrak sewa kamar Anda (No. ' . $s['nomor_kamar'] . ') berakhir HARI INI.';
                    } else {
                        $pesan = 'Kontrak sewa kamar Anda (No. ' . $s['nomor_kamar'] . ') akan berakhir dalam ' . $h . ' hari lagi. Segera perpanjang.';
                    }
                    $notifModel->kirim($s['id_user'], 'Kontrak Sewa Sisa ' . $h . ' Hari', $pesan, 'kontrak');
                    $automationResult['kontrak']++;
                }
            }

            $jatuhTempo = $pembayaranModel->getTagihanJatuhTempoHariIni();
            foreach ($jatuhTempo as $t) {
                $notifModel->kirim(
                    $t['id_user'],
                    'Tagihan Jatuh Tempo Hari Ini',
                    'Tagihan sewa kamar No. ' . $t['nomor_kamar'] . ' (Bulan ke-' . $t['bulan_ke'] . ') sebesar Rp ' . number_format($t['jumlah_bayar'], 0, ',', '.') . ' jatuh tempo HARI INI. Segera lakukan pembayaran.',
                    'tagihan'
                );
                $automationResult['jatuh_tempo']++;
            }

            $terlambat = $pembayaranModel->getTagihanTerlambat();
            foreach ($terlambat as $t) {
                $denda = $t['total_denda'] ?? 0;
                $notifModel->kirim(
                    $t['id_user'],
                    'Tagihan Terlambat - Denda Rp ' . number_format($denda, 0, ',', '.'),
                    'Tagihan sewa kamar No. ' . $t['nomor_kamar'] . ' (Bulan ke-' . $t['bulan_ke'] . ') sudah melewati jatuh tempo. Denda: Rp ' . number_format($denda, 0, ',', '.') . '. Segera bayar!',
                    'tagihan'
                );
                $automationResult['telat']++;
            }

            // FIX H22: markAutomationRun() tidak diperlukan - claim dilakukan atomic di awal via tryClaimAutomationRun().

            $parts = [];
            if ($automationResult['denda'] > 0)         $parts[] = $automationResult['denda'] . ' denda diperbarui';
            if ($automationResult['kontrak'] > 0)       $parts[] = $automationResult['kontrak'] . ' notif kontrak';
            if ($automationResult['jatuh_tempo'] > 0)   $parts[] = $automationResult['jatuh_tempo'] . ' notif jatuh tempo';
            if ($automationResult['telat'] > 0)         $parts[] = $automationResult['telat'] . ' notif telat bayar';
            
            $automationResult['message'] = !empty($parts)
                ? '⚡ Otomatisasi harian: ' . implode(' · ', $parts)
                : '⚡ Otomatisasi harian: Tidak ada aksi (semua aman)';
        }

        // FIX: whitelist $periode supaya tidak bisa di-inject via ?periode=<script>.
        $periodeAllowed = ['hari_ini', 'minggu_ini', 'bulan_ini', 'semua'];
        $periode = $this->request->getGet('periode') ?? 'semua';
        if (!in_array($periode, $periodeAllowed, true)) {
            $periode = 'semua';
        }
        $tanggalAwal = null;
        $tanggalAkhir = date('Y-m-d');

        switch ($periode) {
            case 'hari_ini':  $tanggalAwal = date('Y-m-d'); break;
            case 'minggu_ini': $tanggalAwal = date('Y-m-d', strtotime('monday this week')); break;
            case 'bulan_ini':  $tanggalAwal = date('Y-m-01'); break;
            default: $tanggalAwal = null;
        }

        $notifTerbaru = $notifModel->where('id_user', session()->get('id_user'))
                                   ->orderBy('created_at', 'DESC')
                                   ->limit(5)
                                   ->findAll();

        // FIX Bug #5: Filter pengumuman aktif & belum expired
        $today = date('Y-m-d');
        $pengumumanTerbaru = $pengumumanModel->where('status', 'aktif')
            ->where('tanggal_mulai <=', $today)
            ->groupStart()
                ->where('tanggal_selesai >=', $today)
                ->orWhere('tanggal_selesai', null)
            ->groupEnd()
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->findAll();

        $data = [
            'title'              => 'Dashboard Admin',
            'total_kamar'        => $kamarModel->getTotalKamar(),
            'kamar_kosong'       => $kamarModel->getKamarKosong(),
            'kamar_terisi'       => $kamarModel->getKamarTerisi(),
            'total_penghuni'     => $userModel->countPenghuni(),
            'total_pendaftar'    => $userModel->countPendaftar(),
            'pengajuan_periode'  => $this->getPengajuanPeriode($tanggalAwal, $tanggalAkhir),
            'keluhan_periode'    => $this->getKeluhanPeriode($tanggalAwal, $tanggalAkhir),
            'pembayaran_periode' => $this->getPembayaranPeriode($tanggalAwal, $tanggalAkhir),
            'total_pendapatan'   => $this->getTotalPendapatan($tanggalAwal, $tanggalAkhir),
            'periode'            => $periode,
            'pengajuan_terbaru'  => $sewaModel->getPengajuanTerbaru(),
            'keluhan_terbaru'    => $keluhanModel->getKeluhanTerbaru(),
            'notif_terbaru'      => $notifTerbaru,
            'pengumuman_terbaru' => $pengumumanTerbaru,
            'automation'         => $automationResult,
        ];

        return view('admin/dashboard', $data);
    }

    private function getPengajuanPeriode($awal, $akhir) {
        $db = \Config\Database::connect();
        $builder = $db->table('sewa');
        if ($awal) $builder->where('tanggal_pengajuan >=', $awal);
        $builder->where('tanggal_pengajuan <=', $akhir);
        return $builder->countAllResults();
    }

    private function getKeluhanPeriode($awal, $akhir) {
        $db = \Config\Database::connect();
        $builder = $db->table('keluhan');
        if ($awal) $builder->where('tanggal >=', $awal);
        $builder->where('tanggal <=', $akhir);
        return $builder->countAllResults();
    }

    private function getPembayaranPeriode($awal, $akhir) {
        $db = \Config\Database::connect();
        $builder = $db->table('pembayaran')->where('status', 'lunas');
        if ($awal) $builder->where('tanggal_bayar >=', $awal);
        $builder->where('tanggal_bayar <=', $akhir . ' 23:59:59');
        return $builder->countAllResults();
    }

    private function getTotalPendapatan($awal, $akhir) {
        $db = \Config\Database::connect();
        $builder = $db->table('pembayaran')->select('SUM(jumlah_bayar) as total')->where('status', 'lunas');
        if ($awal) $builder->where('tanggal_bayar >=', $awal);
        $builder->where('tanggal_bayar <=', $akhir . ' 23:59:59');
        $result = $builder->get()->getRowArray();
        return $result['total'] ?? 0;
    }
}