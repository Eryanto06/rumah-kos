<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KamarModel;

class Kamar extends BaseController
{
    protected $kamarModel;

    public function __construct()
    {
        $this->kamarModel = new KamarModel();
    }

    public function index()
    {
        $data = [
            'title'  => 'Data Kamar',
            'kamar'  => $this->kamarModel->findAll(),
        ];
        return view('admin/kamar/index', $data);
    }

    public function tambah()
    {
        $allKamar = $this->kamarModel->select('kode_kamar')->findAll();
        $maxKodeNum = 0;
        foreach ($allKamar as $k) {
            if (preg_match('/KOS-(\d+)/', $k['kode_kamar'], $m)) {
                $num = (int)$m[1];
                if ($num > $maxKodeNum) $maxKodeNum = $num;
            }
        }
        $nextKodeNum = $maxKodeNum + 1;
        $kodeOtomatis = 'KOS-' . str_pad($nextKodeNum, 3, '0', STR_PAD_LEFT);

        $allNomor = $this->kamarModel->select('nomor_kamar')->findAll();
        $maxNomor = 100;
        foreach ($allNomor as $n) {
            $num = (int)$n['nomor_kamar'];
            if ($num > $maxNomor) $maxNomor = $num;
        }
        $nomorOtomatis = $maxNomor + 1;

        return view('admin/kamar/tambah', [
            'title'          => 'Tambah Kamar',
            'kode_otomatis'  => $kodeOtomatis,
            'nomor_otomatis' => $nomorOtomatis,
        ]);
    }

    public function simpan()
    {
        $rules = [
            'kode_kamar'   => 'required|is_unique[kamar.kode_kamar]',
            'nomor_kamar'  => 'required|is_unique[kamar.nomor_kamar]',
            'harga_sewa'   => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $fasilitasChecklist = $this->request->getPost('fasilitas_checklist') ?? [];
        $fasilitasManual    = $this->request->getPost('fasilitas_manual') ?? '';
        
        $semuaFasilitas = $fasilitasChecklist;
        
        if (!empty($fasilitasManual)) {
            $manual = array_map('trim', explode(',', $fasilitasManual));
            $manual = array_filter($manual);
            $semuaFasilitas = array_merge($semuaFasilitas, $manual);
        }
        
        $fasilitasFinal = implode(', ', array_unique($semuaFasilitas));

        if (empty($fasilitasFinal)) {
            return redirect()->back()->withInput()->with('error', 'Fasilitas wajib diisi (pilih checklist atau ketik manual).');
        }

        $namaFoto = $this->uploadFoto();

        $this->kamarModel->save([
            'kode_kamar'  => $this->request->getPost('kode_kamar'),
            'nomor_kamar' => $this->request->getPost('nomor_kamar'),
            'harga_sewa'  => $this->request->getPost('harga_sewa'),
            'fasilitas'   => $fasilitasFinal,
            'foto'        => $namaFoto,
            'status'      => 'tersedia',
        ]);

        return redirect()->to('/admin/kamar')->with('success', 'Kamar ' . $this->request->getPost('kode_kamar') . ' berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $kamar = $this->kamarModel->find($id);
        if (!$kamar) {
            return redirect()->to('/admin/kamar')->with('error', 'Kamar tidak ditemukan.');
        }
        $data = [
            'title' => 'Edit Kamar',
            'kamar' => $kamar,
        ];
        return view('admin/kamar/edit', $data);
    }

    public function update($id)
    {
        $kamarLama = $this->kamarModel->find($id);
        if (!$kamarLama) {
            return redirect()->to('/admin/kamar')->with('error', 'Kamar tidak ditemukan.');
        }

        // === FIX Bug #12: Validasi unik kode_kamar & nomor_kamar (kecuali dirinya sendiri) ===
        $kodeBaru  = $this->request->getPost('kode_kamar');
        $nomorBaru = $this->request->getPost('nomor_kamar');

        $cekKode = $this->kamarModel->where('kode_kamar', $kodeBaru)
                                     ->where('id_kamar !=', $id)
                                     ->countAllResults();
        if ($cekKode > 0) {
            return redirect()->back()->withInput()->with('error', 'Kode kamar "' . $kodeBaru . '" sudah dipakai kamar lain.');
        }

        $cekNomor = $this->kamarModel->where('nomor_kamar', $nomorBaru)
                                      ->where('id_kamar !=', $id)
                                      ->countAllResults();
        if ($cekNomor > 0) {
            return redirect()->back()->withInput()->with('error', 'Nomor kamar "' . $nomorBaru . '" sudah dipakai kamar lain.');
        }

        $rules = [
            'harga_sewa' => 'required|numeric',
            'fasilitas'  => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $statusBaru = $this->request->getPost('status');

        // FIX: whitelist status yang diizinkan untuk mencegah tampering via POST
        $allowedStatus = ['tersedia', 'terisi', 'perbaikan', 'dibooking'];
        if (!in_array($statusBaru, $allowedStatus, true)) {
            return redirect()->back()->withInput()->with('error', 'Status kamar tidak valid.');
        }

        // === FIX Bug #11: Cek apakah kamar sedang disewa aktif ===
        $sewaAktifModel = new \App\Models\SewaModel();
        $sewaAktif = $sewaAktifModel->where('id_kamar', $id)
                                     ->whereIn('status', ['aktif', 'disetujui'])
                                     ->countAllResults();

        if ($sewaAktif > 0 && $statusBaru === 'tersedia') {
            return redirect()->back()->withInput()
                             ->with('error', 'TIDAK BISA ubah status ke "Tersedia"! Kamar ini sedang disewa aktif. Selesaikan checkout/pindah kamar penghuni dulu.');
        }

        $this->kamarModel->update($id, [
            'kode_kamar'  => $kodeBaru,
            'nomor_kamar' => $nomorBaru,
            'harga_sewa'  => $this->request->getPost('harga_sewa'),
            'fasilitas'   => $this->request->getPost('fasilitas'),
            'status'      => $statusBaru,
        ]);

        $namaFoto = $this->uploadFoto($id);
        if ($namaFoto) {
            $this->kamarModel->update($id, ['foto' => $namaFoto]);
        }

        return redirect()->to('/admin/kamar')->with('success', 'Kamar berhasil diupdate!');
    }

    public function hapus($id)
    {
        $kamar = $this->kamarModel->find($id);
        if (!$kamar) {
            return redirect()->to('/admin/kamar')->with('error', 'Kamar tidak ditemukan.');
        }

        // === FIX Bug #10: Cek apakah kamar sedang disewa aktif/menunggu ===
        $sewaModel = new \App\Models\SewaModel();
        $sewaAktif = $sewaModel->where('id_kamar', $id)
                                ->whereIn('status', ['aktif', 'disetujui', 'menunggu'])
                                ->countAllResults();

        if ($sewaAktif > 0) {
            return redirect()->to('/admin/kamar')
                             ->with('error', 'TIDAK BISA HAPUS! Kamar ini masih memiliki ' . $sewaAktif . ' sewa aktif/menunggu. Selesaikan/tolak pengajuan sewa dulu sebelum menghapus kamar.');
        }

        // Hapus foto dari server
        if (!empty($kamar['foto'])) {
            $path = ROOTPATH . 'public/uploads/' . $kamar['foto'];
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $this->kamarModel->delete($id);
        return redirect()->to('/admin/kamar')->with('success', 'Kamar berhasil dihapus!');
    }

    private function uploadFoto($idKamar = null)
    {
        $file = $this->request->getFile('foto');

        if (!$file || !$file->isValid() || $file->hasMoved()) {
            return null;
        }

        $allowedMime = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
        if (!in_array($file->getMimeType(), $allowedMime)) {
            return null;
        }
        if ($file->getSize() > 2 * 1024 * 1024) {
            return null;
        }

        // FIX: $file->move() throw HTTPException saat gagal — bukan return false.
        // Bungkus try-catch supaya kalau gagal, return null & controller bisa handle error.
        try {
            $namaBaru = $file->getRandomName();
            $file->move(ROOTPATH . 'public/uploads/', $namaBaru);
        } catch (\Throwable $e) {
            log_message('error', '[Kamar::uploadFoto] Gagal upload foto: ' . $e->getMessage());
            return null;
        }

        // FIX: hapus foto LAMA hanya SETELAH foto baru berhasil dipindah,
        // supaya jika move() gagal, kamar tidak kehilangan fotonya.
        if ($idKamar) {
            $kamar = $this->kamarModel->find($idKamar);
            if ($kamar && !empty($kamar['foto'])) {
                $pathLama = ROOTPATH . 'public/uploads/' . $kamar['foto'];
                if (file_exists($pathLama) && $kamar['foto'] !== $namaBaru) {
                    @unlink($pathLama);
                }
            }
        }

        return $namaBaru;
    }
}