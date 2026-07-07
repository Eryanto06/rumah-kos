<?php

namespace App\Controllers;

use App\Models\KamarModel;
use App\Models\PeraturanModel;

class Home extends BaseController
{
    /**
     * Landing page publik (beranda).
     * Bisa diakses tanpa login. Menampilkan: hero, statistik, fitur,
     * preview kamar tersedia, peraturan kos (dari DB), kebijakan 50% deposit, dan CTA.
     */
    public function index(): string
    {
        $kamarModel     = new KamarModel();
        $peraturanModel = new PeraturanModel();

        // Ambil maksimal 6 kamar tersedia untuk preview di landing page
        $kamarPreview = $kamarModel->getKamarTersedia();
        if (count($kamarPreview) > 6) {
            $kamarPreview = array_slice($kamarPreview, 0, 6);
        }

        // Ambil peraturan aktif (grouped per kategori)
        $peraturanGrouped = $peraturanModel->getGroupedByKategori();
        $peraturanFlat    = $peraturanModel->getAktif();

        $data = [
            'title'             => 'Beranda',
            'total_kamar'       => $kamarModel->getTotalKamar(),
            'kamar_kosong'      => $kamarModel->getKamarKosong(),
            'kamar_terisi'      => $kamarModel->getKamarTerisi(),
            'kamar'             => $kamarPreview,
            'peraturan_grouped' => $peraturanGrouped,
            'total_peraturan'   => count($peraturanFlat),
        ];

        return view('publik/landing', $data);
    }
}