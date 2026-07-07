<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\SewaModel;
use App\Models\PembayaranModel;
use App\Models\KeluhanModel;
use App\Models\NotifikasiModel;
use App\Models\PengumumanModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $id_user         = session()->get('id_user');
        $sewaModel       = new SewaModel();
        $pembayaranModel = new PembayaranModel();
        $keluhanModel    = new KeluhanModel();
        $notifModel      = new NotifikasiModel();
        $pengumumanModel = new PengumumanModel();

        $today = date('Y-m-d');

        if (session()->get('role') === 'user') {
            
            // 1. Notif Jatuh Tempo Hari Ini (Gabung 1 notif)
            $jatuhTempo = $pembayaranModel->where('pembayaran.status', 'belum_bayar')
                ->where('tanggal_jatuh_tempo', $today)
                ->join('sewa s', 's.id_sewa = pembayaran.id_sewa')
                ->where('s.id_user', $id_user)
                ->where('s.status', 'aktif')
                ->findAll();

            if (!empty($jatuhTempo)) {
                $sudahNotif = $notifModel->where('id_user', $id_user)
                    ->where('judul', 'Tagihan Jatuh Tempo Hari Ini')
                    ->where('DATE(created_at)', $today)
                    ->countAllResults();

                if ($sudahNotif == 0) {
                    $detail = [];
                    $total = 0;
                    foreach ($jatuhTempo as $jt) {
                        $label = $jt['bulan_ke'] == 0 ? 'Deposit' : 'Bulan ke-' . $jt['bulan_ke'];
                        $detail[] = '• ' . $label . ': Rp ' . number_format($jt['jumlah_bayar'], 0, ',', '.');
                        $total += $jt['jumlah_bayar'];
                    }
                    $notifModel->kirim(
                        $id_user,
                        'Tagihan Jatuh Tempo Hari Ini',
                        "Anda memiliki " . count($jatuhTempo) . " tagihan jatuh tempo HARI INI:\n" .
                        implode("\n", $detail) . "\n\nTotal: Rp " . number_format($total, 0, ',', '.') . "\nSegera bayar di menu Pembayaran.",
                        'tagihan'
                    );
                }
            }

            // 2. Notif Tagihan Telat (Gabung 1 notif)
            $telat = $pembayaranModel->where('pembayaran.status', 'belum_bayar')
                ->where('tanggal_jatuh_tempo <', $today)
                ->where('tanggal_jatuh_tempo IS NOT NULL')
                ->join('sewa s', 's.id_sewa = pembayaran.id_sewa')
                ->where('s.id_user', $id_user)
                ->where('s.status', 'aktif')
                ->findAll();

            if (!empty($telat)) {
                $sudahNotifTelat = $notifModel->where('id_user', $id_user)
                    ->where('judul LIKE', '%Tagihan Terlambat%')
                    ->where('DATE(created_at)', $today)
                    ->countAllResults();

                if ($sudahNotifTelat == 0) {
                    $detail = [];
                    $totalDenda = 0;
                    foreach ($telat as $t) {
                        $denda = $t['total_denda'] ?? 0;
                        $label = $t['bulan_ke'] == 0 ? 'Deposit' : 'Bulan ke-' . $t['bulan_ke'];
                        $detail[] = '• ' . $label . ' (Denda: Rp ' . number_format($denda, 0, ',', '.') . ')';
                        $totalDenda += $denda;
                    }
                    $notifModel->kirim(
                        $id_user,
                        'Tagihan Terlambat - Total Denda Rp ' . number_format($totalDenda, 0, ',', '.'),
                        "Anda memiliki " . count($telat) . " tagihan TERLAMBAT:\n" .
                        implode("\n", $detail) . "\n\nTotal Denda: Rp " . number_format($totalDenda, 0, ',', '.') . "\nSegera bayar sebelum denda bertambah!",
                        'tagihan'
                    );
                }
            }
        }

        $sewaAktif = $sewaModel->getSewaAktifByUser($id_user);
        $pembayaran = $pembayaranModel->getPembayaranByUser($id_user);

        // FIX: Cek apakah user sudah isi rekening (untuk refund)
        $rekeningBelumLengkap = false;
        if (kolom_ada('user', 'nomor_rekening')) {
            $userModel = new \App\Models\UserModel();
            $user = $userModel->select('nomor_rekening, ewallet_number')->find($id_user);
            if ($user) {
                $rekeningBelumLengkap = empty($user['nomor_rekening']) && empty($user['ewallet_number']);
            }
        }
        $keluhan = $keluhanModel->getKeluhanByUser($id_user);

        $tagihanBelum = array_filter($pembayaran, fn($p) => $p['status'] == 'belum_bayar');
        $tagihanLunas = array_filter($pembayaran, fn($p) => $p['status'] == 'lunas');
        $totalBayar = array_sum(array_map(fn($p) => $p['status'] == 'lunas' ? $p['jumlah_bayar'] : 0, $pembayaran));
        $totalTunggakan = array_sum(array_map(fn($p) => $p['status'] == 'belum_bayar' ? $p['jumlah_bayar'] : 0, $pembayaran));

        $jatuhTempoTerdekat = [];
        $weekLater = date('Y-m-d', strtotime('+7 days'));
        foreach ($tagihanBelum as $p) {
            if (!empty($p['tanggal_jatuh_tempo']) && $p['tanggal_jatuh_tempo'] >= $today && $p['tanggal_jatuh_tempo'] <= $weekLater) {
                $jatuhTempoTerdekat[] = $p;
            }
        }

        $kontrakHampirHabis = false;
        $hariTersisa = 0;
        if ($sewaAktif && !empty($sewaAktif['tanggal_selesai'])) {
            $selisih = strtotime($sewaAktif['tanggal_selesai']) - time();
            $hariTersisa = floor($selisih / (60 * 60 * 24));
            if ($hariTersisa <= 30) $kontrakHampirHabis = true;
        }

        $notifTerbaru = $notifModel->where('id_user', $id_user)->orderBy('created_at', 'DESC')->limit(3)->findAll();

        $isPenghuni = !empty($sewaAktif);
        // FIX BUG: orWhere('tanggal_selesai', null) di CI4 tidak generate IS NULL
        // dengan benar di dalam groupStart. Pakai raw where string supaya pasti.
        $pengumumanTerbaru = $pengumumanModel->where('status', 'aktif')
            ->where('tanggal_mulai <=', $today)
            ->groupStart()
                ->where('tanggal_selesai >=', $today)
                ->orWhere('tanggal_selesai IS NULL', null, false)
            ->groupEnd()
            ->groupStart()
                ->where('target', 'semua')
                ->orWhere('target', $isPenghuni ? 'penghuni_aktif' : 'pendaftar')
            ->groupEnd()
            ->orderBy('id_pengumuman', 'DESC')->limit(3)->findAll();

        $data = [
            'title'                => 'Dashboard',
            'rekening_belum_lengkap' => $rekeningBelumLengkap,
            'sewa_aktif'           => $sewaAktif,
            'pembayaran'           => $pembayaran,
            'keluhan'              => $keluhan,
            'tagihan_belum'        => $tagihanBelum,
            'tagihan_lunas'        => $tagihanLunas,
            'total_bayar'          => $totalBayar,
            'total_tunggakan'      => $totalTunggakan,
            'jatuh_tempo_terdekat' => $jatuhTempoTerdekat,
            'kontrak_hampir_habis' => $kontrakHampirHabis,
            'hari_tersisa'         => $hariTersisa,
            'notif_terbaru'        => $notifTerbaru,
            'pengumuman_terbaru'   => $pengumumanTerbaru,
        ];
        
        return view('user/dashboard', $data);
    }
}