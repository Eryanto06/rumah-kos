<?= $this->extend('layouts/user') ?>
<?= $this->section('content') ?>

<style>
    /* CSS UNTUK NEWS TICKER (Teks Berjalan Estetik) */
    .news-ticker {
        background: #ffffff;
        border: 1px solid #e0e0e0;
        border-left: 4px solid #0d6efd;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        display: flex;
        align-items: center;
        overflow: hidden;
        margin-bottom: 20px;
    }
    .news-ticker-icon {
        background: #0d6efd;
        color: #fff;
        padding: 10px 15px;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        flex-shrink: 0;
    }
    .news-ticker-mask {
        overflow: hidden;
        flex-grow: 1;
        padding: 0 15px;
    }
    .news-ticker-content {
        display: inline-block;
        white-space: nowrap;
        animation: ticker 25s linear infinite;
        font-weight: 500;
        color: #333;
    }
    @keyframes ticker {
        0% { transform: translateX(100%); }
        100% { transform: translateX(-100%); }
    }
</style>

<!-- HEADER SELAMAT DATANG -->
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body p-4" style="background:linear-gradient(135deg,#1a237e,#00897b); color:white; border-radius:10px;">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h4 class="mb-1 fw-bold"><i class="bi bi-house-door me-2"></i>Selamat Datang, <?= esc(session()->get('nama')) ?>!</h4>
                <p class="mb-0 opacity-75 small">
                    <?php if ($sewa_aktif): ?>
                        Anda terdaftar sebagai <strong>Penghuni Aktif</strong> di Kamar No. <?= esc($sewa_aktif['nomor_kamar'] ?? '-') ?>
                    <?php else: ?>
                        Anda belum menyewa kamar. <a href="/user/sewa" class="text-white text-decoration-none fw-bold">Ajukan Sewa Sekarang →</a>
                    <?php endif; ?>
                </p>
            </div>
            <div class="text-end">
                <div class="small opacity-75" id="tanggalHariIni"><?= date('l, d M Y') ?></div>
                <div class="fs-4 fw-bold" id="jamRealtime"><?= date('H:i:s') ?></div>
            </div>
        </div>
    </div>
</div>

<!-- NEWS TICKER (TEKS BERJALAN) -->
<?php if (!empty($pengumuman_terbaru)): ?>
<div class="news-ticker">
    <div class="news-ticker-icon">
        <i class="bi bi-megaphone-fill"></i>
    </div>
    <div class="news-ticker-mask">
        <div class="news-ticker-content">
            <?php foreach ($pengumuman_terbaru as $p): ?>
                <strong><?= esc($p['judul']) ?>:</strong> <?= esc(strip_tags($p['isi'])) ?> &nbsp;&nbsp;✦&nbsp;&nbsp;
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if (!empty($rekening_belum_lengkap) || session()->getFlashdata('warning_rekening')): ?>
<!-- BANNER PERINGATAN: REKENING BELUM LENGKAP -->
<div class="alert alert-danger border-0 shadow-sm d-flex align-items-center mb-3" style="border-left: 4px solid #dc3544;">
    <i class="bi bi-bank2 fs-3 me-3 text-danger"></i>
    <div class="flex-grow-1">
        <strong class="text-danger"><i class="bi bi-exclamation-triangle-fill me-1"></i>LENGKAPI DATA REKENING ANDA</strong><br>
        <small>Anda belum mengisi nomor rekening bank atau e-wallet di profil. Data ini <strong>wajib</strong> diisi untuk:
        <ul class="mb-1 small">
            <li>Menerima pengembalian deposit (refund) saat checkout</li>
            <li>Menerima selisih deposit saat pindah kamar</li>
            <li>Menerima refund deposit kalau pengajuan sewa ditolak</li>
        </ul>
        Tanpa rekening, proses refund akan tertunda dan admin harus menghubungi Anda manual.</small>
    </div>
    <a href="/user/profil" class="btn btn-danger btn-sm">
        <i class="bi bi-pencil-square me-1"></i>Isi Rekening Sekarang
    </a>
</div>
<?php endif; ?>

<?php if ($kontrak_hampir_habis): ?>
<!-- ALERT KONTRAK HAMPIR HABIS -->
<div class="alert alert-warning border-0 shadow-sm d-flex align-items-center mb-3">
    <i class="bi bi-exclamation-triangle-fill fs-3 me-3 text-warning"></i>
    <div class="flex-grow-1">
        <strong>Kontrak Sewa Anda Hampir Habis!</strong><br>
        <small>Sisa waktu <?= $hari_tersisa ?> hari. Segera lakukan <a href="/user/perpanjangan" class="alert-link">perpanjangan kontrak</a> jika ingin tetap tinggal.</small>
    </div>
    <a href="/user/perpanjangan" class="btn btn-warning btn-sm">Perpanjang</a>
</div>
<?php endif; ?>

<!-- KARTU STATISTIK -->
<div class="row g-3 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <small class="text-muted text-uppercase fw-semibold">Kamar Saya</small>
                        <?php if ($sewa_aktif): ?>
                            <div class="fs-4 fw-bold text-success mt-1">No. <?= esc($sewa_aktif['nomor_kamar']) ?></div>
                            <small class="text-muted">Sampai <?= !empty($sewa_aktif['tanggal_selesai']) ? esc(date('d M Y', strtotime($sewa_aktif['tanggal_selesai']))) : '-' ?></small>
                        <?php else: ?>
                            <div class="fs-4 fw-bold text-muted mt-1">-</div>
                            <small class="text-muted">Belum sewa</small>
                        <?php endif; ?>
                    </div>
                    <div class="rounded-circle p-2" style="background:#e8f5e9; color:#198754;">
                        <i class="bi bi-door-closed fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <small class="text-muted text-uppercase fw-semibold">Tagihan Belum Bayar</small>
                        <div class="fs-4 fw-bold text-danger mt-1"><?= count($tagihan_belum) ?></div>
                        <small class="text-muted">Rp <?= number_format($total_tunggakan,0,',','.') ?></small>
                    </div>
                    <div class="rounded-circle p-2" style="background:#ffebee; color:#dc3545;">
                        <i class="bi bi-receipt fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <small class="text-muted text-uppercase fw-semibold">Sudah Dibayar</small>
                        <div class="fs-4 fw-bold text-success mt-1"><?= count($tagihan_lunas) ?></div>
                        <small class="text-muted">Rp <?= number_format($total_bayar,0,',','.') ?></small>
                    </div>
                    <div class="rounded-circle p-2" style="background:#e8f5e9; color:#198754;">
                        <i class="bi bi-check-circle fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <small class="text-muted text-uppercase fw-semibold">Keluhan Saya</small>
                        <div class="fs-4 fw-bold text-info mt-1"><?= count($keluhan) ?></div>
                        <small class="text-muted">
                            <?php
                            $proses = array_filter($keluhan, fn($k) => $k['status'] != 'selesai');
                            echo count($proses) . ' aktif';
                            ?>
                        </small>
                    </div>
                    <div class="rounded-circle p-2" style="background:#e3f2fd; color:#0d6efd;">
                        <i class="bi bi-chat-dots fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <!-- KIRI: TAGIHAN JATUH TEMPO + INFO KAMAR -->
    <div class="col-md-8">
        <?php if (!empty($jatuh_tempo_terdekat)): ?>
        <!-- TAGIHAN JATUH TEMPO -->
        <div class="card border-0 shadow-sm mb-3 border-start border-warning border-4">
            <div class="card-header bg-transparent fw-semibold d-flex justify-content-between align-items-center">
                <span><i class="bi bi-alarm me-2 text-warning"></i>Tagihan Jatuh Tempo Terdekat</span>
                <a href="/user/pembayaran" class="btn btn-sm btn-outline-warning">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>Bulan</th><th>Jumlah</th><th>Jatuh Tempo</th><th>Aksi</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($jatuh_tempo_terdekat as $p): ?>
                        <tr>
                            <td>
                                <?php if ($p['bulan_ke'] == 0): ?>
                                    <span class="badge bg-warning text-dark">Deposit</span>
                                <?php else: ?>
                                    Bulan ke-<?= $p['bulan_ke'] ?>
                                <?php endif; ?>
                            </td>
                            <td class="fw-bold">Rp <?= number_format($p['jumlah_bayar'],0,',','.') ?></td>
                            <td><small class="text-danger"><?= esc($p['tanggal_jatuh_tempo']) ?></small></td>
                            <td><a href="/user/pembayaran" class="btn btn-sm btn-success">Bayar</a></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($sewa_aktif): ?>
        <!-- DETAIL KAMAR -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent fw-semibold">
                <i class="bi bi-info-circle me-2 text-primary"></i>Informasi Kamar Saya
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless table-sm">
                            <tr><td class="text-muted">Kode Kamar</td><td class="fw-bold"><?= esc($sewa_aktif['kode_kamar']) ?></td></tr>
                            <tr><td class="text-muted">Nomor Kamar</td><td class="fw-bold"><?= esc($sewa_aktif['nomor_kamar']) ?></td></tr>
                            <tr><td class="text-muted">Harga Sewa</td><td class="fw-bold text-success">Rp <?= number_format($sewa_aktif['harga_sewa'],0,',','.') ?>/bln</td></tr>
                            <tr>
                                <td class="text-muted">Status Kunci</td>
                                <td>
                                    <?php 
                                    $statusKunci = $sewa_aktif['status_kunci'] ?? 'belum_siap';
                                    $kunciBadge = [
                                        'belum_siap'         => ['secondary', 'Belum Siap'],
                                        'siap_diambil'       => ['warning text-dark', '🔑 Siap Diambil'],
                                        'sudah_diambil'      => ['success', '✅ Sudah Diambil'],
                                        'sudah_dikembalikan' => ['info', 'Dikembalikan'],
                                    ];
                                    $kb = $kunciBadge[$statusKunci] ?? ['secondary', 'Belum Siap'];
                                    ?>
                                    <span class="badge bg-<?= $kb[0] ?>"><?= $kb[1] ?></span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless table-sm">
                            <tr><td class="text-muted">Mulai Sewa</td><td class="fw-bold"><?= !empty($sewa_aktif['tanggal_mulai']) ? esc(date('d M Y', strtotime($sewa_aktif['tanggal_mulai']))) : '-' ?></td></tr>
                            <tr><td class="text-muted">Selesai Sewa</td><td class="fw-bold <?= $kontrak_hampir_habis ? 'text-danger' : '' ?>"><?= !empty($sewa_aktif['tanggal_selesai']) ? esc(date('d M Y', strtotime($sewa_aktif['tanggal_selesai']))) : '-' ?></td></tr>
                            <tr><td class="text-muted">Durasi</td><td class="fw-bold"><?= esc($sewa_aktif['durasi_bulan']) ?> bulan</td></tr>
                        </table>
                    </div>
                </div>
                <hr>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="/user/kamar-saya" class="btn btn-outline-primary btn-sm"><i class="bi bi-eye me-1"></i>Detail Kamar</a>
                    <a href="/user/perpanjangan" class="btn btn-outline-success btn-sm"><i class="bi bi-arrow-repeat me-1"></i>Perpanjang Kontrak</a>
                    <a href="/user/pindah-kamar" class="btn btn-outline-warning btn-sm"><i class="bi bi-arrow-left-right me-1"></i>Pindah Kamar</a>
                    <a href="/user/checkout" class="btn btn-outline-danger btn-sm"><i class="bi bi-door-open me-1"></i>Check Out</a>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-house-slash fs-1 text-muted d-block mb-3"></i>
                <h5 class="fw-bold">Anda Belum Menyewa Kamar</h5>
                <p class="text-muted mb-4">Mulai sewa kamar untuk mengakses fitur lengkap seperti pembayaran, keluhan, dan manajemen kontrak.</p>
                <a href="/user/sewa" class="btn btn-primary btn-lg"><i class="bi bi-door-open me-2"></i>Ajukan Sewa Kamar</a>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- KANAN: QUICK ACTION + PENGUMUMAN + NOTIFIKASI -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-transparent fw-semibold">
                <i class="bi bi-lightning-charge me-2 text-warning"></i>Aksi Cepat
            </div>
            <div class="card-body p-2">
                <a href="/user/pembayaran" class="btn btn-outline-success w-100 text-start mb-2">
                    <i class="bi bi-credit-card me-2"></i>Bayar Tagihan
                </a>
                <a href="/user/keluhan" class="btn btn-outline-danger w-100 text-start mb-2">
                    <i class="bi bi-megaphone me-2"></i>Kirim Keluhan
                </a>
                <a href="/user/notifikasi" class="btn btn-outline-info w-100 text-start mb-2">
                    <i class="bi bi-bell me-2"></i>Lihat Notifikasi
                </a>
                <a href="/user/profil" class="btn btn-outline-secondary w-100 text-start">
                    <i class="bi bi-person me-2"></i>Edit Profil
                </a>
            </div>
        </div>

        <!-- PENGUMUMAN TERBARU -->
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-transparent fw-semibold d-flex justify-content-between align-items-center py-3">
                <span><i class="bi bi-megaphone me-2 text-info"></i>Pengumuman</span>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($pengumuman_terbaru)): ?>
                    <?php foreach ($pengumuman_terbaru as $p): ?>
                    <div class="border-bottom p-3 <?= $p['status'] == 'aktif' ? 'bg-light' : '' ?>">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <small class="fw-bold text-primary"><?= esc($p['judul']) ?></small>
                            <small class="text-muted" style="font-size: 0.7rem; white-space: nowrap;">
                                <i class="bi bi-clock me-1"></i><?= esc(date('d M H:i', strtotime($p['created_at'] ?? 'now'))) ?>
                            </small>
                        </div>
                        <p class="mb-1 text-muted" style="font-size: 0.85rem; white-space: pre-wrap;"><?= esc($p['isi']) ?></p>
                        
                        <?php if (!empty($p['waktu_mulai'])): ?>
                        <div class="mt-2 p-2 bg-white rounded border" style="font-size: 0.75rem;">
                            <i class="bi bi-clock-history me-1 text-warning"></i>
                            <strong>Jadwal:</strong> 
                            <?= esc(date('d M Y H:i', strtotime($p['waktu_mulai']))) ?> 
                            s/d 
                            <?= esc(!empty($p['waktu_selesai']) ? date('d M Y H:i', strtotime($p['waktu_selesai'])) : 'Selesai') ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-bell-slash fs-3 d-block mb-2"></i>
                        <small>Belum ada pengumuman</small>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent fw-semibold d-flex justify-content-between align-items-center">
                <span><i class="bi bi-bell me-2 text-info"></i>Notifikasi</span>
                <a href="/user/notifikasi" class="small">Semua →</a>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($notif_terbaru)): ?>
                    <?php foreach ($notif_terbaru as $n): ?>
                        <div class="border-bottom p-2 <?= $n['dibaca'] ? '' : 'bg-light' ?>">
                            <small class="fw-bold"><?= esc($n['judul']) ?></small>
                            <p class="mb-0 text-muted" style="font-size:0.8rem;"><?= esc($n['pesan']) ?></p>
                            <small class="text-muted" style="font-size:0.7rem;"><?= esc($n['created_at']) ?></small>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-bell-slash fs-3 d-block mb-2"></i>
                        <small>Belum ada notifikasi</small>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function updateJam() {
    const now = new Date();
    const jam = String(now.getHours()).padStart(2, '0');
    const menit = String(now.getMinutes()).padStart(2, '0');
    const detik = String(now.getSeconds()).padStart(2, '0');
    document.getElementById('jamRealtime').textContent = jam + ':' + menit + ':' + detik;
    
    const hari = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'][now.getDay()];
    const bulan = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'][now.getMonth()];
    const tanggal = String(now.getDate()).padStart(2, '0');
    const tahun = now.getFullYear();
    document.getElementById('tanggalHariIni').textContent = hari + ', ' + tanggal + ' ' + bulan + ' ' + tahun;
}
setInterval(updateJam, 1000);
updateJam();
</script>

<?= $this->endSection() ?>