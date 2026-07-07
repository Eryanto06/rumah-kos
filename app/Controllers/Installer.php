<?php

namespace App\Controllers;

use CodeIgniter\Controller;

/**
 * Installer & Health Check Controller
 *
 * Akses via browser: http://localhost/installer
 * 
 * Fungsi:
 * 1. Cek apakah migration revisi sudah dijalankan (kolom rekening & refund ada di DB?)
 * 2. Cek apakah seeder sudah dijalankan (setting kontak & metode pembayaran ada?)
 * 3. Auto-fix: jalankan migration & seeder dari browser
 * 4. Tampilkan status koneksi database
 *
 * Tujuan: membantu admin yang kesulitan jalankan `php spark migrate` dari CLI.
 */
class Installer extends Controller
{
    public function index()
    {
        $db = \Config\Database::connect();

        $checks = [];
        $errors = [];
        $fixes = [];

        // ====================================
        // 1. CEK KONEKSI DATABASE
        // ====================================
        try {
            $db->initialize();
            $checks['db_connection'] = [
                'status' => 'ok',
                'message' => 'Terhubung ke database: ' . $db->getDatabase(),
            ];
        } catch (\Throwable $e) {
            $errors[] = 'Database connection failed: ' . $e->getMessage();
            $checks['db_connection'] = [
                'status' => 'error',
                'message' => 'GAGAL: ' . $e->getMessage(),
            ];
            // Kalau DB gak connect, gak perlu lanjut
            return $this->renderPage($checks, $errors, $fixes);
        }

        // ====================================
        // 2. CEK TABEL USER punya kolom rekening?
        // ====================================
        $userCols = $this->getTableColumns($db, 'user');
        $requiredUserCols = ['nama_bank', 'nomor_rekening', 'nama_pemilik_rek', 'ewallet_type', 'ewallet_number'];
        $missingUserCols = array_diff($requiredUserCols, $userCols);

        if (empty($missingUserCols)) {
            $checks['user_columns'] = [
                'status' => 'ok',
                'message' => 'Semua 5 kolom rekening user sudah ada',
            ];
        } else {
            $errors[] = 'Tabel user kekurangan kolom: ' . implode(', ', $missingUserCols);
            $checks['user_columns'] = [
                'status' => 'error',
                'message' => 'KURANG: ' . implode(', ', $missingUserCols),
            ];
        }

        // ====================================
        // 3. CEK TABEL SEWA punya kolom refund?
        // ====================================
        $sewaCols = $this->getTableColumns($db, 'sewa');
        $requiredSewaCols = ['bukti_refund', 'tanggal_refund', 'total_refund', 'refund_status', 'refund_metode'];
        $missingSewaCols = array_diff($requiredSewaCols, $sewaCols);

        if (empty($missingSewaCols)) {
            $checks['sewa_columns'] = [
                'status' => 'ok',
                'message' => 'Semua 5 kolom refund sewa sudah ada',
            ];
        } else {
            $errors[] = 'Tabel sewa kekurangan kolom: ' . implode(', ', $missingSewaCols);
            $checks['sewa_columns'] = [
                'status' => 'error',
                'message' => 'KURANG: ' . implode(', ', $missingSewaCols),
            ];
        }

        // ====================================
        // 4. CEK SETTING KONTAK & METODE PEMBAYARAN di tabel pengaturan
        // ====================================
        $requiredSettings = [
            // Metode pembayaran
            'bank_name_1', 'bank_account_1', 'bank_holder_1',
            'ewallet_dana', 'ewallet_ovo', 'ewallet_gopay', 'ewallet_shopeepay',
            'payment_instructions',
            // Kontak kos
            'nama_kos', 'tagline', 'alamat', 'email_kos', 'telepon_kos', 'wa_admin',
            'facebook', 'instagram', 'tiktok', 'youtube',
            'jam_operasional', 'maps_embed', 'maps_link', 'footer_text',
        ];

        $existingSettings = [];
        try {
            $rows = $db->table('pengaturan')->whereIn('kunci', $requiredSettings)->get()->getResultArray();
            $existingSettings = array_column($rows, 'kunci');
        } catch (\Throwable $e) {
            $errors[] = 'Tidak bisa cek tabel pengaturan: ' . $e->getMessage();
        }

        $missingSettings = array_diff($requiredSettings, $existingSettings);
        if (empty($missingSettings)) {
            $checks['settings'] = [
                'status' => 'ok',
                'message' => 'Semua ' . count($requiredSettings) . ' setting kontak & metode pembayaran sudah ada',
            ];
        } else {
            $errors[] = 'Setting kurang: ' . implode(', ', $missingSettings);
            $checks['settings'] = [
                'status' => 'error',
                'message' => 'KURANG ' . count($missingSettings) . ' setting: ' . implode(', ', array_slice($missingSettings, 0, 5)) . (count($missingSettings) > 5 ? '...' : ''),
            ];
        }

        // ====================================
        // 5. CEK TABEL YANG WAJIB ADA
        // ====================================
        $requiredTables = ['user', 'kamar', 'sewa', 'pembayaran', 'pengaturan', 'notifikasi', 'keluhan', 'pengumuman', 'peraturan', 'pengajuan_pindah', 'pengajuan_checkout'];
        $existingTables = $this->getAllTables($db);
        $missingTables = array_diff($requiredTables, $existingTables);

        if (empty($missingTables)) {
            $checks['tables'] = [
                'status' => 'ok',
                'message' => 'Semua ' . count($requiredTables) . ' tabel utama sudah ada',
            ];
        } else {
            $errors[] = 'Tabel kurang: ' . implode(', ', $missingTables);
            $checks['tables'] = [
                'status' => 'error',
                'message' => 'KURANG: ' . implode(', ', $missingTables),
            ];
        }

        // ====================================
        // 6. CEK ADMIN USER
        // ====================================
        try {
            $adminCount = $db->table('user')->where('role', 'admin')->countAllResults();
            if ($adminCount > 0) {
                $checks['admin_user'] = [
                    'status' => 'ok',
                    'message' => "Ada $adminCount admin terdaftar",
                ];
            } else {
                $errors[] = 'Belum ada admin. Jalankan seeder: php spark db:seed InitialSeeder';
                $checks['admin_user'] = [
                    'status' => 'error',
                    'message' => 'Belum ada user admin',
                ];
            }
        } catch (\Throwable $e) {
            $errors[] = 'Tidak bisa cek admin: ' . $e->getMessage();
        }

        // ====================================
        // HANDLE POST: AUTO-FIX
        // ====================================
        if ($this->request->getMethod() === 'post') {
            $action = $this->request->getPost('action');

            if ($action === 'run_migration') {
                try {
                    // Run migration via library
                    $migration = \Config\Services::migrations();
                    $migration->setNamespace('App');
                    if ($migration->latest()) {
                        $fixes[] = '✅ Migration berhasil dijalankan. Semua kolom baru sudah ditambahkan.';
                    } else {
                        $errors[] = 'Migration dijalankan tapi tidak ada yang berubah. Mungkin sudah pernah dijalankan.';
                    }
                } catch (\Throwable $e) {
                    $errors[] = 'Migration GAGAL: ' . $e->getMessage();
                    $fixes[] = '❌ Migration gagal. Coba alternatif: import file database_revisi_v1_v2.sql via phpMyAdmin.';
                }
            } elseif ($action === 'run_seeder') {
                try {
                    $seeder = \Config\Database::seeder();
                    $seeder->call('InitialSeeder');
                    $fixes[] = '✅ Seeder berhasil dijalankan. Setting default sudah ditambahkan.';
                } catch (\Throwable $e) {
                    $errors[] = 'Seeder GAGAL: ' . $e->getMessage();
                }
            } elseif ($action === 'run_both') {
                try {
                    $migration = \Config\Services::migrations();
                    $migration->setNamespace('App');
                    $migration->latest();
                    $fixes[] = '✅ Migration selesai.';
                } catch (\Throwable $e) {
                    $errors[] = 'Migration GAGAL: ' . $e->getMessage() . '. Coba import database_revisi_v1_v2.sql via phpMyAdmin.';
                }
                try {
                    $seeder = \Config\Database::seeder();
                    $seeder->call('InitialSeeder');
                    $fixes[] = '✅ Seeder selesai.';
                } catch (\Throwable $e) {
                    $errors[] = 'Seeder GAGAL: ' . $e->getMessage();
                }
            }
        }

        return $this->renderPage($checks, $errors, $fixes);
    }

    private function getTableColumns($db, $table)
    {
        try {
            $result = $db->getFieldNames($table);
            return $result ?: [];
        } catch (\Throwable $e) {
            return [];
        }
    }

    private function getAllTables($db)
    {
        try {
            $tables = $db->listTables();
            return $tables ?: [];
        } catch (\Throwable $e) {
            return [];
        }
    }

    private function renderPage($checks, $errors, $fixes)
    {
        $allOk = empty($errors);
        header('Content-Type: text/html; charset=utf-8');
        echo '<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Installer & Health Check - Rumah Kos</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
body { background: #f4f6f9; padding: 30px 0; font-family: "Segoe UI", system-ui, sans-serif; }
.container-max { max-width: 900px; margin: 0 auto; }
.check-item { padding: 16px; border-radius: 8px; margin-bottom: 12px; }
.check-ok { background: #d1e7dd; border-left: 4px solid #198754; }
.check-error { background: #f8d7da; border-left: 4px solid #dc3545; }
.status-icon { font-size: 1.5rem; vertical-align: middle; }
.code-block { background: #1e1e1e; color: #f8f8f2; padding: 12px 16px; border-radius: 6px; font-family: monospace; font-size: 0.9rem; overflow-x: auto; margin: 8px 0; }
.code-block .cmd { color: #66d9ef; }
.code-block .arg { color: #a6e22e; }
</style>
</head>
<body>
<div class="container-max container">
<h1 class="mb-4"><i class="bi bi-wrench-adjustable text-primary"></i> Installer & Health Check</h1>

<div class="alert ' . ($allOk ? 'alert-success' : 'alert-danger') . '">
    <h5 class="alert-heading">
        ' . ($allOk ? '<i class="bi bi-check-circle-fill"></i> Sistem OK!' : '<i class="bi bi-exclamation-triangle-fill"></i> Ada Masalah!') . '
    </h5>
    <p class="mb-0">' . ($allOk ? 'Semua pengecekan berhasil. Aplikasi siap digunakan.' : 'Ditemukan ' . count($errors) . ' masalah. Silakan perbaiki dengan tombol di bawah.') . '</p>
</div>

';
        if (!empty($fixes)) {
            echo '<div class="alert alert-info"><h5 class="alert-heading"><i class="bi bi-info-circle-fill"></i> Hasil Perbaikan</h5><ul class="mb-0">';
            foreach ($fixes as $fix) {
                echo '<li>' . htmlspecialchars($fix) . '</li>';
            }
            echo '</ul></div>';
        }
        echo '

';
        if (!empty($errors)) {
            echo '<div class="alert alert-warning"><h5 class="alert-heading"><i class="bi bi-bug-fill"></i> Daftar Error</h5><ul class="mb-0">';
            foreach ($errors as $err) {
                echo '<li>' . htmlspecialchars($err) . '</li>';
            }
            echo '</ul></div>';
        }
        echo '

<h3 class="mb-3"><i class="bi bi-list-check"></i> Status Pengecekan</h3>';

        foreach ($checks as $key => $check) {
            $icon = $check['status'] === 'ok' ? 'bi-check-circle-fill text-success' : 'bi-x-circle-fill text-danger';
            $cls = $check['status'] === 'ok' ? 'check-ok' : 'check-error';
            echo "
<div class=\"check-item $cls\">
    <i class=\"bi $icon status-icon me-2\"></i>
    <strong>" . ucfirst(str_replace('_', ' ', $key)) . ":</strong>
    " . htmlspecialchars($check['message']) . "
</div>";
        }

        echo '

<h3 class="mb-3 mt-4"><i class="bi bi-tools"></i> Aksi Perbaikan Otomatis</h3>
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <p class="text-muted small">Klik tombol di bawah untuk menjalankan perbaikan otomatis. Tombol ini akan menjalankan migration dan seeder dari browser — alternatif untuk yang tidak bisa akses CLI/terminal.</p>
        <form method="post" class="d-flex gap-2 flex-wrap">
            <button type="submit" name="action" value="run_migration" class="btn btn-primary">
                <i class="bi bi-database-up me-1"></i>Jalankan Migration
            </button>
            <button type="submit" name="action" value="run_seeder" class="btn btn-success">
                <i class="bi bi-seedling me-1"></i>Jalankan Seeder
            </button>
            <button type="submit" name="action" value="run_both" class="btn btn-warning">
                <i class="bi bi-lightning-fill me-1"></i>Jalankan Keduanya
            </button>
        </form>
    </div>
</div>

<h3 class="mb-3 mt-4"><i class="bi bi-life-preserver"></i> Alternatif Manual (kalau tombol atas gagal)</h3>
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <h6 class="fw-bold">Opsi A: Import file SQL via phpMyAdmin</h6>
        <ol class="small">
            <li>Buka phpMyAdmin di browser (biasanya <code>http://localhost/phpmyadmin</code>)</li>
            <li>Pilih database rumah kos Anda di sidebar kiri</li>
            <li>Klik tab <strong>"SQL"</strong> di atas</li>
            <li>Buka file <code>database_revisi_v1_v2.sql</code> (ada di root folder project) dengan text editor</li>
            <li>Copy seluruh isi, paste ke kotak SQL phpMyAdmin</li>
            <li>Klik tombol <strong>"Go"</strong> / <strong>"Kirim"</strong></li>
            <li>Refresh halaman ini — semua check harus hijau</li>
        </ol>

        <h6 class="fw-bold mt-4">Opsi B: Jalankan spark CLI (kalau ada akses terminal)</h6>
        <div class="code-block">
<span class="cmd">cd</span> /path/to/rumah-kos<br>
<span class="cmd">php</span> <span class="arg">spark</span> migrate<br>
<span class="cmd">php</span> <span class="arg">spark</span> db:seed InitialSeeder
        </div>

        <h6 class="fw-bold mt-4">Opsi C: XAMPP/Windows</h6>
        <div class="code-block">
<span class="cmd">cd</span> C:\\xampp\\htdocs\\rumah-kos<br>
<span class="cmd">C:\\xampp\\php\\php.exe</span> <span class="arg">spark</span> migrate<br>
<span class="cmd">C:\\xampp\\php\\php.exe</span> <span class="arg">spark</span> db:seed InitialSeeder
        </div>
    </div>
</div>

<div class="alert alert-info small">
    <i class="bi bi-info-circle"></i> Setelah semua check berstatus <span class="badge bg-success">OK</span>, Anda bisa login sebagai admin:
    <ul class="mb-0 mt-2">
        <li>Buka: <code>/login</code></li>
        <li>Username: <code>admin</code> | Password: <code>admin123</code></li>
        <li>Setelah login, buka menu <strong>Pengaturan</strong> untuk isi rekening kos, kontak, sosial media, dll.</li>
    </ul>
</div>

<p class="text-center text-muted small mt-4">
    &copy; ' . date('Y') . ' Rumah Kos - Installer & Health Check<br>
    Akses halaman ini kapan saja di: <code>/installer</code>
</p>

</div>
</body>
</html>';
    }
}
