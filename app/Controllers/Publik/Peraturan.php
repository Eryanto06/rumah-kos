<?php

namespace App\Controllers\Publik;

use App\Controllers\BaseController;
use App\Models\PeraturanModel;

class Peraturan extends BaseController
{
    /**
     * Halaman publik: tampilkan peraturan kos ke pengunjung/pendaftar/penghuni
     */
    public function index()
    {
        $model = new PeraturanModel();

        $data = [
            'title'     => 'Peraturan Kos',
            'peraturan' => $model->getGroupedByKategori(),
            'total'     => count($model->getAktif()),
        ];
        return view('publik/peraturan/index', $data);
    }
}