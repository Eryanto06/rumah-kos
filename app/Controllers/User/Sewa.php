<?php namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\SewaModel;
use App\Models\KamarModel;
use App\Models\NotifikasiModel;
use App\Models\UserModel;
use App\Models\PembayaranModel;
use App\Models\PengaturanModel;

class Sewa extends BaseController {

    public function index() {
        $id_user    = session()->get('id_user');
        $sewaModel  = new SewaModel();
        $kamarModel = new KamarModel();
        $pengaturanModel = new PengaturanModel();

        $idKamarDipilih = $this->request->getGet('id_kamar');
        $kamarDipilih = $idKamarDipilih ? $kamarModel->find($idKamarDipilih) : null;

        // FIX: Kirim kaliDeposit & batasTanggal ke view
        $kaliDeposit = (int) $pengaturanModel->get('default_deposit_kali') ?: 2;
        $batasTanggal = (int) $pengaturanModel->get('batas_tanggal_bayar') ?: 5;

        return view('user/sewa/index', [
            'title'         => 'Pengajuan Sewa',
            'kamar'         => $kamarModel->getKamarTersedia(),
            'kamarDipilih'  => $kamarDipilih,
            'riwayat'       => $sewaModel->getSewaByUser($id_user),
            'kaliDeposit'   => $kaliDeposit,
            'batasTanggal'  => $batasTanggal,
        ]);
    }

    public function ajukan() {
        $id_user = session()->get('id_user');
        $model   = new SewaModel();

        $aktif = $model->getSewaAktifByUser($id_user);
        if ($aktif) {
            return redirect()->back()->with('error', 'Anda sudah memiliki kamar aktif!');
        }

        $menunggu = $model->where('id_user', $id_user)->where('status', 'menunggu')->countAllResults();
        if ($menunggu > 0) {
            return redirect()->back()->with('error', 'Anda sudah memiliki pengajuan sewa yang masih menunggu.');
        }

        $rules = [
            'id_kamar'      => 'required|numeric',
            'durasi_bulan'  => 'required|numeric|greater_than_equal_to[1]',
            'tanggal_mulai' => 'required|valid_date',
            'keterangan'    => 'permit_empty',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $idKamar    = $this->request->getPost('id_kamar');
        $durasiBln  = (int) $this->request->getPost('durasi_bulan');
        $tglMulai   = $this->request->getPost('tanggal_mulai');

        // FIX: Batas durasi dari pengaturan
        $pengaturanModel = new PengaturanModel();
        $maxDurasi = (int) $pengaturanModel->get('durasi_maksimal') ?: 120;
        $minDurasi = (int) $pengaturanModel->get('durasi_minimal') ?: 1;

        if ($durasiBln < $minDurasi) {
            return redirect()->back()->withInput()->with('error', 'Durasi sewa minimal ' . $minDurasi . ' bulan.');
        }
        if ($durasiBln > $maxDurasi) {
            return redirect()->back()->withInput()->with('error', 'Durasi sewa terlalu besar! Maksimal ' . $maxDurasi . ' bulan.');
        }

        if ($tglMulai < date('Y-m-d')) {
            return redirect()->back()->withInput()->with('error', 'Tanggal mulai huni tidak boleh lampau.');
        }

        $kamarModel = new KamarModel();
        $kamar = $kamarModel->find($idKamar);
        if (!$kamar || $kamar['status'] !== 'tersedia') {
            return redirect()->back()->withInput()->with('error', 'Kamar tidak tersedia.');
        }

        $tanggalSelesai = date('Y-m-d', strtotime($tglMulai . " +{$durasiBln} months"));
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $builder = $db->table('kamar');
            $builder->where('id_kamar', $idKamar)->where('status', 'tersedia');
            $builder->update(['status' => 'dibooking']);

            // FIX: $builder->update() return bool, BUKAN row count. Pakai affectedRows().
            if ($db->affectedRows() < 1) {
                $db->transRollback();
                return redirect()->back()->withInput()->with('error', 'Kamar sudah tidak tersedia.');
            }

            $model->save([
                'id_user'          => $id_user,
                'id_kamar'         => $idKamar,
                'tanggal_pengajuan'=> date('Y-m-d'),
                'tanggal_mulai'    => $tglMulai,
                'tanggal_selesai'  => $tanggalSelesai,
                'durasi_bulan'     => $durasiBln,
                'status'           => 'menunggu',
                'keterangan'       => $this->request->getPost('keterangan'),
            ]);
            $idSewaBaru = $model->getInsertID();

            // FIX H1: JANGAN reset kamar ke 'tersedia' setelah sewa dibuat.
            // Biarkan 'dibooking' sampai admin approve (-> 'terisi') atau reject (-> 'tersedia').
            // Sebelumnya kamar di-reset ke 'tersedia' → user lain bisa booking kamar yang sama.

            // FIX H2: Pindah insert deposit tagihan ke DALAM transaction.
            // Sebelumnya di luar transaction → bisa orphan kalau DB error.
            $kaliDeposit = (int) ($pengaturanModel->get('default_deposit_kali') ?? 2);
            $nominalDeposit = $kamar['harga_sewa'] * $kaliDeposit;

            // Deposit = bayar awal SEKALI. Jatuh tempo 3 hari dari pengajuan.
            // (Tanggal 5 dari pengaturan = batas pembayaran SEWA BULANAN, BUKAN deposit.)
            $pembayaranModel = new PembayaranModel();
            $pembayaranModel->save([
                'id_sewa' => $idSewaBaru, 'bulan_ke' => 0, 'jumlah_bayar' => $nominalDeposit,
                'tanggal_jatuh_tempo' => date('Y-m-d', strtotime('+3 days')), 'status' => 'belum_bayar',
                'keterangan' => 'Deposit Awal Sewa',
            ]);

            $db->transComplete();

            if ($db->transStatus() === false) throw new \Exception('DB Error');
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Gagal: ' . $e->getMessage());
        }

        $notifModel = new NotifikasiModel();
        $notifModel->kirim($id_user, 'Pengajuan Sewa Diterima', "Pengajuan kamar No. " . $kamar['nomor_kamar'] . " diterima. WAJIB bayar Deposit Rp " . number_format($nominalDeposit, 0, ',', '.') . " dalam 3 hari.", 'sewa');

        $userModel = new UserModel();
        foreach ($userModel->where('role', 'admin')->findAll() as $admin) {
            $notifModel->kirim($admin['id_user'], 'Pengajuan Sewa Baru', session()->get('nama') . " mengajukan sewa kamar.", 'sewa');
        }

        return redirect()->to('/user/sewa')->with('success', 'Pengajuan sewa berhasil! Segera bayar Deposit.');
    }
}