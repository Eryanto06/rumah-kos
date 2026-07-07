<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PeraturanModel;

class Peraturan extends BaseController
{
    protected $peraturanModel;

    public function __construct()
    {
        $this->peraturanModel = new PeraturanModel();
    }

    public function index()
    {
        $data = [
            'title'     => 'Peraturan Kos',
            'peraturan' => $this->peraturanModel->getAll(),
        ];
        return view('admin/peraturan/index', $data);
    }

    public function simpan()
    {
        $rules = [
            'judul'    => 'required|min_length[3]',
            'isi'      => 'required',
            'kategori' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->peraturanModel->save([
            'judul'    => $this->request->getPost('judul'),
            'isi'      => $this->request->getPost('isi'),
            'kategori' => $this->request->getPost('kategori'),
            'urutan'   => $this->request->getPost('urutan') ?: 0,
            'status'   => $this->request->getPost('status') ?? 'aktif',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/admin/peraturan')->with('success', 'Peraturan berhasil ditambahkan.');
    }

    public function hapus($id)
    {
        $this->peraturanModel->delete($id);
        return redirect()->to('/admin/peraturan')->with('success', 'Peraturan dihapus.');
    }

    public function toggleStatus($id)
    {
        $p = $this->peraturanModel->find($id);
        if (!$p) return redirect()->to('/admin/peraturan')->with('error', 'Peraturan tidak ditemukan.');

        $newStatus = $p['status'] === 'aktif' ? 'nonaktif' : 'aktif';
        $this->peraturanModel->builder()->where('id_peraturan', $id)->update(['status' => $newStatus]);
        return redirect()->to('/admin/peraturan')->with('success', 'Status peraturan diubah jadi ' . $newStatus . '.');
    }
}