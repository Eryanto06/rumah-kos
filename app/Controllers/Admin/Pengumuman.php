<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PengumumanModel;
use App\Models\NotifikasiModel;
use App\Models\UserModel;

class Pengumuman extends BaseController
{
    protected $pengumumanModel;
    protected $notifikasiModel;
    protected $userModel;

    public function __construct()
    {
        $this->pengumumanModel = new PengumumanModel();
        $this->notifikasiModel = new NotifikasiModel();
        $this->userModel       = new UserModel();
    }

    public function index()
    {
        $data = [
            'title'       => 'Pengumuman',
            'pengumuman'  => $this->pengumumanModel->getAll(),
        ];
        return view('admin/pengumuman/index', $data);
    }

    public function simpan()
    {
        $rules = [
            'judul'        => 'required|min_length[3]',
            'isi'          => 'required',
            'tanggal_mulai' => 'required|valid_date',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $idUser = session()->get('id_user');
        $target = $this->request->getPost('target') ?? 'semua';

        // FIX Bug #8: Tambah created_at
        $this->pengumumanModel->save([
            'judul'          => $this->request->getPost('judul'),
            'isi'            => $this->request->getPost('isi'),
            'waktu_mulai'    => $this->request->getPost('waktu_mulai') ?: null,
            'waktu_selesai'  => $this->request->getPost('waktu_selesai') ?: null,
            'tanggal_mulai'  => $this->request->getPost('tanggal_mulai'),
            'tanggal_selesai'=> $this->request->getPost('tanggal_selesai') ?: null,
            'status'         => $this->request->getPost('status') ?? 'aktif',
            'target'         => $target,
            'created_by'     => $idUser,
            'created_at'     => date('Y-m-d H:i:s'),
        ]);

        if ($target == 'penghuni_aktif') {
            $penerima = $this->userModel->getIdPenghuniAktif();
        } elseif ($target == 'pendaftar') {
            $penerima = $this->userModel->getIdPendaftar();
        } else {
            $penerima = $this->userModel->select('id_user')->where('role', 'user')->findAll();
        }

        $totalKirim = 0;
        foreach ($penerima as $u) {
            $this->notifikasiModel->kirim(
                $u['id_user'],
                'Pengumuman: ' . $this->request->getPost('judul'),
                strip_tags(substr($this->request->getPost('isi'), 0, 150)),
                'pengumuman'
            );
            $totalKirim++;
        }

        $targetLabel = $target == 'penghuni_aktif' 
            ? 'Penghuni Aktif' 
            : ($target == 'pendaftar' ? 'Pendaftar' : 'Semua User');
        
        return redirect()->to('/admin/pengumuman')
                         ->with('success', "Pengumuman berhasil dibuat & dikirim ke {$totalKirim} {$targetLabel}.");
    }

    public function hapus($id)
    {
        $this->pengumumanModel->delete($id);
        return redirect()->to('/admin/pengumuman')->with('success', 'Pengumuman dihapus.');
    }

    public function toggleStatus($id)
    {
        $p = $this->pengumumanModel->find($id);
        if (!$p) return redirect()->to('/admin/pengumuman')->with('error', 'Pengumuman tidak ditemukan.');

        $newStatus = $p['status'] === 'aktif' ? 'nonaktif' : 'aktif';
        $this->pengumumanModel->builder()->where('id_pengumuman', $id)->update(['status' => $newStatus]);
        return redirect()->to('/admin/pengumuman')->with('success', 'Status pengumuman diubah menjadi ' . $newStatus . '.');
    }
}