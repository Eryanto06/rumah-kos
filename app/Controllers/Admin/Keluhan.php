<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KeluhanModel;
use App\Models\NotifikasiModel;

class Keluhan extends BaseController
{
    protected $keluhanModel;
    protected $notifikasiModel;

    public function __construct()
    {
        $this->keluhanModel = new KeluhanModel();
        $this->notifikasiModel = new NotifikasiModel();
    }

    public function index()
    {
        $kategori     = $this->request->getGet('kategori');
        $status       = $this->request->getGet('status');
        $jenisPelapor = $this->request->getGet('jenis');

        $keluhan = $this->keluhanModel->getKeluhanWithUser($kategori, $status, $jenisPelapor);
        $counts  = $this->keluhanModel->countByJenisPelapor();

        $data = [
            'title'          => 'Data Keluhan',
            'keluhan'        => $keluhan,
            'filter_kategori'=> $kategori,
            'filter_status'  => $status,
            'filter_jenis'   => $jenisPelapor,
            'total_semua'    => $counts['total'],
            'total_penghuni' => $counts['penghuni'],
            'total_pendaftar'=> $counts['pendaftar'],
            'total_menunggu' => $this->keluhanModel->where('status', 'menunggu')->countAllResults(),
            'total_diproses' => $this->keluhanModel->where('status', 'diproses')->countAllResults(),
            'total_selesai'  => $this->keluhanModel->where('status', 'selesai')->countAllResults(),
        ];
        return view('admin/keluhan/index', $data);
    }

    public function detail($id)
    {
        $keluhan = $this->keluhanModel->getKeluhanDetail($id);
        if (!$keluhan) {
            return redirect()->to('/admin/keluhan')->with('error', 'Keluhan tidak ditemukan.');
        }

        $data = [
            'title'   => 'Detail Keluhan',
            'keluhan' => $keluhan,
        ];
        return view('admin/keluhan/detail', $data);
    }

    public function updateStatus($id)
    {
        $keluhan = $this->keluhanModel->find($id);
        if (!$keluhan) {
            return redirect()->to('/admin/keluhan')->with('error', 'Keluhan tidak ditemukan.');
        }

        $statusBaru = $this->request->getPost('status');
        $balasan    = $this->request->getPost('balasan');

        // === FIX Bug #13: WHITELIST status keluhan yang diizinkan ===
        $allowedStatus = ['menunggu', 'diproses', 'selesai'];
        if (!in_array($statusBaru, $allowedStatus)) {
            return redirect()->to('/admin/keluhan')
                             ->with('error', 'Status tidak valid! Pilihan: Menunggu, Diproses, atau Selesai.');
        }

        // === Opsional: Anti-dobel - kalau sudah selesai, tidak bisa diubah lagi (kecuali admin paksa) ===
        if ($keluhan['status'] === 'selesai' && $statusBaru !== 'selesai') {
            return redirect()->to('/admin/keluhan')
                             ->with('error', 'Keluhan ini sudah berstatus "Selesai" dan tidak bisa diubah ke status lain.');
        }

        // FIX BUG: keluhan private (is_private=1) punya id_user=NULL di DB.
        // Kirim notifikasi ke id_user=NULL akan crash karena kolom id_user NOT NULL
        // di tabel notifikasi (DB constraint error). Skip notif untuk private keluhan.
        $this->keluhanModel->builder()->where('id_keluhan', $id)->update([
            'status'  => $statusBaru,
            'balasan' => $balasan,
        ]);

        if (!empty($keluhan['id_user'])) {
            $this->notifikasiModel->kirim(
                $keluhan['id_user'],
                'Keluhan Diperbarui: ' . $keluhan['judul'],
                'Status keluhan Anda: ' . ucfirst($statusBaru) . '. ' . ($balasan ? 'Balasan admin: ' . $balasan : ''),
                'keluhan'
            );
        } else {
            // Private keluhan — tidak bisa kirim notif ke user (anonim).
            log_message('info', '[Keluhan::updateStatus] Keluhan #' . $id . ' is private — notif ke user di-skip.');
        }

        return redirect()->to('/admin/keluhan')->with('success', 'Status keluhan diupdate & notifikasi dikirim ke penghuni!');
    }
}