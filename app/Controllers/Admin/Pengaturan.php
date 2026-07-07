<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PengaturanModel;
use App\Models\PembayaranModel;
use App\Models\NotifikasiModel;
use App\Models\SewaModel;

class Pengaturan extends BaseController
{
    protected $pengaturanModel;
    protected $pembayaranModel;
    protected $notifikasiModel;
    protected $sewaModel;

    public function __construct()
    {
        $this->pengaturanModel = new PengaturanModel();
        $this->pembayaranModel = new PembayaranModel();
        $this->notifikasiModel = new NotifikasiModel();
        $this->sewaModel       = new SewaModel();
    }

    public function index()
    {
        $data = [
            'title'      => 'Pengaturan Sistem',
            'pengaturan' => $this->pengaturanModel->getAll(),
        ];
        return view('admin/pengaturan/index', $data);
    }

    public function update()
    {
        $dendaPerHari       = $this->request->getPost('denda_per_hari');
        $defaultDeposit    = $this->request->getPost('default_deposit_kali');
        $durasiMinimal     = $this->request->getPost('durasi_minimal');
        $durasiMaksimal    = $this->request->getPost('durasi_maksimal');
        $batasTanggalBayar = $this->request->getPost('batas_tanggal_bayar');

        $rules = [
            'denda_per_hari'        => 'required|numeric|greater_than_equal_to[0]',
            'default_deposit_kali'  => 'required|numeric|greater_than_equal_to[1]',
            'durasi_minimal'        => 'required|numeric|greater_than_equal_to[1]',
            'durasi_maksimal'       => 'required|numeric|greater_than_equal_to[1]',
            'batas_tanggal_bayar'   => 'required|numeric|greater_than_equal_to[1]|less_than_equal_to[31]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        if ((int)$durasiMinimal > (int)$durasiMaksimal) {
            return redirect()->back()->withInput()->with('error', 'Durasi minimal tidak boleh lebih besar dari durasi maksimal.');
        }

        $this->pengaturanModel->setSetting('denda_per_hari', $dendaPerHari);
        $this->pengaturanModel->setSetting('default_deposit_kali', $defaultDeposit);
        $this->pengaturanModel->setSetting('durasi_minimal', $durasiMinimal);
        $this->pengaturanModel->setSetting('durasi_maksimal', $durasiMaksimal);
        $this->pengaturanModel->setSetting('batas_tanggal_bayar', $batasTanggalBayar);

        // === FIX BUG: simpan setting metode pembayaran kos ===
        // Tanpa ini, user tidak tahu ke mana harus transfer. Mereka harus chat admin
        // via WhatsApp manual, rawan salah transfer / penipuan.
        $metodeBayar = [
            'bank_name_1', 'bank_account_1', 'bank_holder_1',
            'bank_name_2', 'bank_account_2', 'bank_holder_2',
            'ewallet_dana', 'ewallet_ovo', 'ewallet_gopay', 'ewallet_shopeepay',
            'payment_instructions',
        ];

        foreach ($metodeBayar as $key) {
            $val = $this->request->getPost($key);
            if ($val !== null) {
                // Sanitasi: buang karakter berbahaya. Rekening hanya boleh digit/spasi/-. 
                if (in_array($key, ['bank_account_1', 'bank_account_2', 'ewallet_dana', 'ewallet_ovo', 'ewallet_gopay', 'ewallet_shopeepay'], true)) {
                    $val = preg_replace('/[^0-9\s\-]/', '', $val);
                }
                $this->pengaturanModel->setSetting($key, $val);
            }
        }

        // === FIX: simpan setting KONTAK KOS (untuk landing page editable) ===
        // Tanpa ini, kontak di landing hardcoded di view — admin gak bisa ubah
        // tanpa minta developer. Sekarang bisa diubah di form Pengaturan.
        $kontakKeys = [
            'nama_kos', 'tagline', 'alamat', 'email_kos', 'telepon_kos',
            'wa_admin', 'facebook', 'instagram', 'tiktok', 'youtube',
            'jam_operasional', 'maps_embed', 'maps_link', 'footer_text',
        ];

        foreach ($kontakKeys as $key) {
            $val = $this->request->getPost($key);
            if ($val !== null) {
                // Sanitasi URL untuk social media
                if (in_array($key, ['facebook', 'instagram', 'tiktok', 'youtube', 'maps_link', 'maps_embed'], true)) {
                    $val = strip_tags($val);
                    $val = trim($val);
                }
                // Sanitasi telepon & WA: hanya digit/spasi/-/+
                if (in_array($key, ['telepon_kos', 'wa_admin'], true)) {
                    $val = preg_replace('/[^0-9\s\-\+]/', '', $val);
                }
                $this->pengaturanModel->setSetting($key, $val);
            }
        }

        return redirect()->to('/admin/pengaturan')->with('success', 'Pengaturan disimpan.');
    }

    public function recalculateDenda()
    {
        $dendaPerHari = (int) $this->pengaturanModel->get('denda_per_hari');
        $today = date('Y-m-d');

        $terlambat = $this->pembayaranModel->where('status', 'belum_bayar')
                                          ->where('tanggal_jatuh_tempo IS NOT NULL')
                                          ->where('tanggal_jatuh_tempo <', $today)
                                          ->findAll();

        $updated = 0;
        foreach ($terlambat as $p) {
            $jatuhTempo = new \DateTime($p['tanggal_jatuh_tempo']);
            $hariIni    = new \DateTime($today);
            $selisih    = $jatuhTempo->diff($hariIni)->days;
            
            $totalDendaBaru = $selisih * $dendaPerHari;

            $this->pembayaranModel->builder()->where('id_pembayaran', $p['id_pembayaran'])->update([
                'denda_per_hari' => $dendaPerHari,
                'total_denda'    => $totalDendaBaru,
            ]);
            $updated++;
        }

        return redirect()->to('/admin/pengaturan')->with('success', "Denda berhasil dihitung ulang! {$updated} tagihan terlambat diperbarui memakai tarif Rp " . number_format($dendaPerHari, 0, ',', '.') . " / hari.");
    }

    public function notifKontrakHabis()
    {
        $titikPengingat = [7, 3, 2, 1, 0];
        $kirim = 0;

        foreach ($titikPengingat as $h) {
            $sewaList = $this->sewaModel->getSewaBySisaHari($h);
            foreach ($sewaList as $s) {
                $judul = $h > 0
                    ? 'Kontrak Sewa Hampir Habis (H-' . $h . ')'
                    : 'Kontrak Sewa Berakhir Hari Ini';
                $pesan = $h > 0
                    ? 'Kontrak sewa kamar Anda (No. ' . $s['nomor_kamar'] . ') akan berakhir pada ' . $s['tanggal_selesai'] . ' (' . $h . ' hari lagi). Segera lakukan perpanjangan jika ingin tetap tinggal.'
                    : 'Kontrak sewa kamar Anda (No. ' . $s['nomor_kamar'] . ') berakhir HARI INI (' . $s['tanggal_selesai'] . '). Segera perpanjang atau ajukan check-out.';

                $this->notifikasiModel->kirim($s['id_user'], $judul, $pesan, 'kontrak');
                $kirim++;
            }
        }

        return redirect()->to('/admin/pengaturan')->with('success', "Notifikasi kontrak hampir habis terkirim ke {$kirim} penghuni (titik H-7/H-3/H-2/H-1/H-0).");
    }

    // FIX Bug #6: Auto-detect OS + escapeshellarg
    public function backupDatabase()
    {
        $host = env('database.default.hostname', 'localhost');
        $user = env('database.default.username', 'root');
        $pass = env('database.default.password', '');
        $db   = env('database.default.database', 'db_rumah_kos');
        
        $mysqldump = '';
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $paths = ['C:\\xampp\\mysql\\bin\\mysqldump.exe', 'C:\\wamp64\\bin\\mysql\\mysql8.0.31\\bin\\mysqldump.exe', 'mysqldump.exe'];
            foreach ($paths as $p) {
                if ($p === 'mysqldump.exe' || file_exists($p)) { $mysqldump = $p; break; }
            }
        } else {
            $mysqldump = trim(shell_exec('which mysqldump 2>/dev/null') ?: '/usr/bin/mysqldump');
        }

        if (empty($mysqldump)) {
            return redirect()->to('/admin/pengaturan')->with('error', 'mysqldump tidak ditemukan.');
        }

        $filename = 'backup_' . $db . '_' . date('Y-m-d_His') . '.sql';
        $filepath = WRITEPATH . 'uploads/' . $filename;
        
        // FIX H6+H7: pakai --defaults-file supaya password gak muncul di ps aux.
        // stderr di-redirect ke /dev/null supaya warning mysqldump gak bocor ke browser.
        $cnfFile = tempnam(sys_get_temp_dir(), 'mysql_');
        @chmod($cnfFile, 0600);
        $cnfContent = "[client]\nhost={$host}\nuser={$user}\npassword={$pass}\n";
        file_put_contents($cnfFile, $cnfContent);

        $command = escapeshellarg($mysqldump)
            . " --defaults-file=" . escapeshellarg($cnfFile)
            . " " . escapeshellarg($db)
            . " > " . escapeshellarg($filepath)
            . " 2> " . escapeshellarg(WRITEPATH . 'logs/mysqldump-stderr.log');

        system($command, $returnVar);

        // Hapus file temporary berisi password secepatnya
        @unlink($cnfFile);

        // FIX: cek returnVar dulu — bukan hanya filesize.
        // Hapus file backup SETELAH dikirim ke client via shutdown function.
        if ($returnVar !== 0 || !file_exists($filepath) || filesize($filepath) === 0) {
            if (file_exists($filepath)) {
                @unlink($filepath);
            }
            return redirect()->to('/admin/pengaturan')->with('error', 'Backup gagal (mysqldump exit code ' . $returnVar . ').');
        }

        // Hapus file setelah response selesai dikirim ke client.
        register_shutdown_function(function () use ($filepath) {
            if (file_exists($filepath)) {
                @unlink($filepath);
            }
        });

        return $this->response->download($filepath, null)->setFileName($filename);
    }
}