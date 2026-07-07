<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KamarModel;
use App\Models\SewaModel;
use App\Models\PembayaranModel;
use App\Models\KeluhanModel;

class Laporan extends BaseController
{
    public function index()
    {
        return view('admin/laporan/index', ['title' => 'Laporan']);
    }

    public function kamar()
    {
        $model = new KamarModel();
        $kamar = $model->findAll();

        $data = [
            'title'        => 'Laporan Kamar',
            'kamar'        => $kamar,
            'total'        => count($kamar),
            'tersedia'     => count(array_filter($kamar, fn($k) => $k['status'] === 'tersedia')),
            'terisi'       => count(array_filter($kamar, fn($k) => $k['status'] === 'terisi')),
            'perbaikan'    => count(array_filter($kamar, fn($k) => $k['status'] === 'perbaikan')),
            'total_nilai'  => array_sum(array_column($kamar, 'harga_sewa')),
        ];
        return view('admin/laporan/kamar', $data);
    }

    public function penghuni()
    {
        $model = new SewaModel();
        $penghuni = $model->getSewaWithDetail();

        $aktif = array_filter($penghuni, fn($p) => $p['status'] === 'aktif');

        $data = [
            'title'         => 'Laporan Penghuni',
            'penghuni'      => $penghuni,
            'total'         => count($penghuni),
            'aktif'         => count($aktif),
            'total_deposit' => array_sum(array_column($aktif, 'deposit')),
        ];
        return view('admin/laporan/penghuni', $data);
    }

    public function pembayaran()
    {
        $model = new PembayaranModel();
        $pembayaran = $model->getPembayaranWithDetail();

        $data = [
            'title'            => 'Laporan Pembayaran',
            'pembayaran'       => $pembayaran,
            'total'            => count($pembayaran),
            'lunas'            => count(array_filter($pembayaran, fn($p) => $p['status'] === 'lunas')),
            'belum_bayar'      => count(array_filter($pembayaran, fn($p) => $p['status'] === 'belum_bayar')),
            'total_pemasukan'  => array_sum(array_map(fn($p) => $p['status'] === 'lunas' ? $p['jumlah_bayar'] : 0, $pembayaran)),
            'total_tunggakan'  => array_sum(array_map(fn($p) => $p['status'] === 'belum_bayar' ? $p['jumlah_bayar'] : 0, $pembayaran)),
        ];
        return view('admin/laporan/pembayaran', $data);
    }

    public function keluhan()
    {
        $model = new KeluhanModel();
        $keluhan = $model->getKeluhanWithUser();

        $perKategori = [];
        foreach ($keluhan as $k) {
            $kat = $k['kategori'] ?? 'lainnya';
            if (!isset($perKategori[$kat])) $perKategori[$kat] = 0;
            $perKategori[$kat]++;
        }

        $data = [
            'title'        => 'Laporan Keluhan',
            'keluhan'      => $keluhan,
            'total'        => count($keluhan),
            'menunggu'     => count(array_filter($keluhan, fn($k) => $k['status'] === 'menunggu')),
            'diproses'     => count(array_filter($keluhan, fn($k) => $k['status'] === 'diproses')),
            'selesai'      => count(array_filter($keluhan, fn($k) => $k['status'] === 'selesai')),
            'per_kategori' => $perKategori,
        ];
        return view('admin/laporan/keluhan', $data);
    }

    // ============================================
    // METHOD EXPORT KE EXCEL (.xls)
    // ============================================

    public function exportKamar()
    {
        $model = new KamarModel();
        $kamar = $model->findAll();
        $this->exportExcel('laporan_kamar', ['Kode', 'No Kamar', 'Harga Sewa', 'Fasilitas', 'Status'], $kamar, ['kode_kamar', 'nomor_kamar', 'harga_sewa', 'fasilitas', 'status']);
    }

    public function exportPenghuni()
    {
        $model = new SewaModel();
        $penghuni = $model->getSewaWithDetail();
        $this->exportExcel('laporan_penghuni', ['Nama', 'Email', 'No HP', 'Kamar', 'Mulai', 'Selesai', 'Durasi', 'Deposit', 'Status'], $penghuni, ['nama', 'email', 'no_hp', 'nomor_kamar', 'tanggal_mulai', 'tanggal_selesai', 'durasi_bulan', 'deposit', 'status']);
    }

    public function exportPembayaran()
    {
        $model = new PembayaranModel();
        $pembayaran = $model->getPembayaranWithDetail();
        $this->exportExcel('laporan_pembayaran', ['Nama', 'Kamar', 'Bulan Ke', 'Jumlah', 'Jatuh Tempo', 'Tanggal Bayar', 'Status'], $pembayaran, ['nama', 'nomor_kamar', 'bulan_ke', 'jumlah_bayar', 'tanggal_jatuh_tempo', 'tanggal_bayar', 'status']);
    }

    public function exportKeluhan()
    {
        $model = new KeluhanModel();
        $keluhan = $model->getKeluhanWithUser();
        $this->exportExcel('laporan_keluhan', ['Tanggal', 'Pelapor', 'Kategori', 'Judul', 'Prioritas', 'Status'], $keluhan, ['tanggal', 'nama_user', 'kategori', 'judul', 'prioritas', 'status']);
    }

    private function exportExcel($filename, $headers, $data, $fields)
    {
        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '_' . date('Y-m-d') . '.xls"');
        header('Pragma: no-cache');
        header('Expires: 0');

        echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        echo '<table border="1" style="border-collapse: collapse; font-family: Arial, sans-serif; font-size: 12px;">';
        
        // Header
        echo '<thead><tr style="background-color: #1a237e; color: #fff; text-align: center;">';
        echo '<th style="padding: 8px; border: 1px solid #000;">No</th>';
        foreach ($headers as $h) {
            echo '<th style="padding: 8px; border: 1px solid #000;">' . htmlspecialchars($h) . '</th>';
        }
        echo '</tr></thead>';
        
        // Body
        echo '<tbody>';
        $no = 1;
        foreach ($data as $row) {
            $bgColor = ($no % 2 == 0) ? '#f8f9fa' : '#ffffff';
            echo '<tr style="background-color: ' . $bgColor . ';">';
            
            // Kolom No
            echo '<td style="text-align: center; padding: 6px; border: 1px solid #ddd;">' . $no++ . '</td>';
            
            foreach ($fields as $field) {
                $val = $row[$field] ?? '';
                if ($val instanceof \DateTime) $val = $val->format('Y-m-d');
                
                $style = 'padding: 6px; border: 1px solid #ddd;';
                if ($field == 'harga_sewa' || $field == 'jumlah_bayar' || $field == 'deposit') {
                    $style .= ' text-align: right; mso-number-format: "\#\,\#\#0\.00";';
                }
                
                echo '<td style="' . $style . '">' . htmlspecialchars((string)$val) . '</td>';
            }
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
        exit;
    }
}