<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<?php if (!empty($automation['message'])): ?>
<div class="alert alert-info alert-dismissible fade show d-flex align-items-center" role="alert">
    <i class="bi bi-lightning-charge-fill me-2 fs-5"></i>
    <div class="flex-grow-1">
        <strong>Otomatisasi Harian Berjalan:</strong>
        <?= esc($automation['message']) ?>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<!-- ===== FILTER PERIODE ===== -->
<div class="card mb-3 border-0 shadow-sm">
    <div class="card-body py-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-calendar-range fs-4 text-primary"></i>
                <span class="fw-semibold">Periode Statistik:</span>
            </div>
            <div class="btn-group" role="group">
                <a href="/admin/dashboard?periode=hari_ini" class="btn btn-sm <?= $periode==='hari_ini'?'btn-primary':'btn-outline-primary' ?>">Hari Ini</a>
                <a href="/admin/dashboard?periode=minggu_ini" class="btn btn-sm <?= $periode==='minggu_ini'?'btn-primary':'btn-outline-primary' ?>">Minggu Ini</a>
                <a href="/admin/dashboard?periode=bulan_ini" class="btn btn-sm <?= $periode==='bulan_ini'?'btn-primary':'btn-outline-primary' ?>">Bulan Ini</a>
                <a href="/admin/dashboard" class="btn btn-sm <?= $periode==='semua'?'btn-primary':'btn-outline-primary' ?>">Semua</a>
            </div>
        </div>
    </div>
</div>

<!-- ===== STATISTIK UTAMA (Kamar) ===== -->
<h6 class="fw-bold text-muted mb-2"><i class="bi bi-building me-1"></i>STATISTIK KAMAR</h6>
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card text-white border-0" style="background:linear-gradient(135deg,#1a237e,#3949ab)">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="fs-3 fw-bold"><?= $total_kamar ?></div>
                    <div class="small">Total Kamar</div>
                </div>
                <i class="bi bi-door-closed fs-1 opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <a href="/admin/kamar" class="text-decoration-none">
            <div class="card text-white border-0" style="background:linear-gradient(135deg,#00695c,#00897b)">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fs-3 fw-bold"><?= $kamar_kosong ?></div>
                        <div class="small">Kamar Kosong</div>
                    </div>
                    <i class="bi bi-door-open fs-1 opacity-50"></i>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <div class="card text-white border-0" style="background:linear-gradient(135deg,#e65100,#f57c00)">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="fs-3 fw-bold"><?= $kamar_terisi ?></div>
                    <div class="small">Kamar Terisi</div>
                </div>
                <i class="bi bi-house-fill fs-1 opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white border-0" style="background:linear-gradient(135deg,#37474f,#546e7a)">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="fs-3 fw-bold"><?= $total_kamar > 0 ? round(($kamar_terisi/$total_kamar)*100, 1) : 0 ?>%</div>
                    <div class="small">Okupansi</div>
                </div>
                <i class="bi bi-pie-chart fs-1 opacity-50"></i>
            </div>
        </div>
    </div>
</div>

<!-- ===== STATISTIK USER (Pendaftar vs Penghuni) ===== -->
<h6 class="fw-bold text-muted mb-2"><i class="bi bi-people me-1"></i>STATISTIK PENGGUNA</h6>
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <a href="/admin/user?tab=pendaftar" class="text-decoration-none">
            <div class="card text-white border-0" style="background:linear-gradient(135deg,#f57c00,#ff9800)">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fs-3 fw-bold"><?= $total_pendaftar ?></div>
                        <div class="small">Pendaftar (Belum Sewa)</div>
                    </div>
                    <i class="bi bi-person-plus fs-1 opacity-50"></i>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="/admin/user?tab=penghuni" class="text-decoration-none">
            <div class="card text-white border-0" style="background:linear-gradient(135deg,#198754,#20c997)">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fs-3 fw-bold"><?= $total_penghuni ?></div>
                        <div class="small">Penghuni Aktif</div>
                    </div>
                    <i class="bi bi-house-heart fs-1 opacity-50"></i>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3">
        <div class="card text-white border-0" style="background:linear-gradient(135deg,#6a1b9a,#8e24aa)">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="fs-3 fw-bold"><?= $total_pendaftar + $total_penghuni ?></div>
                    <div class="small">Total User</div>
                </div>
                <i class="bi bi-people-fill fs-1 opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white border-0" style="background:linear-gradient(135deg,#0d47a1,#1976d2)">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="fs-3 fw-bold"><?= $pengajuan_periode ?></div>
                    <div class="small">Pengajuan Sewa (<?= esc(ucfirst(str_replace('_', ' ', $periode))) ?>)</div>
                </div>
                <i class="bi bi-file-earmark-text fs-1 opacity-50"></i>
            </div>
        </div>
    </div>
</div>

<!-- ===== STATISTIK AKTIVITAS (Per Periode) ===== -->
<h6 class="fw-bold text-muted mb-2"><i class="bi bi-graph-up me-1"></i>AKTIVITAS (<?= esc(ucfirst(str_replace('_', ' ', $periode))) ?>)</h6>
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-3">
                <i class="bi bi-file-earmark-plus fs-2 text-primary"></i>
                <div class="fs-3 fw-bold text-primary mt-1"><?= $pengajuan_periode ?></div>
                <small class="text-muted">Pengajuan Sewa</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-3">
                <i class="bi bi-chat-dots fs-2 text-danger"></i>
                <div class="fs-3 fw-bold text-danger mt-1"><?= $keluhan_periode ?></div>
                <small class="text-muted">Keluhan Masuk</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-3">
                <i class="bi bi-cash-coin fs-2 text-success"></i>
                <div class="fs-3 fw-bold text-success mt-1"><?= $pembayaran_periode ?></div>
                <small class="text-muted">Pembayaran Lunas</small>
            </div>
        </div>
    </div>
</div>

<!-- ===== PENDAPATAN ===== -->
<div class="card mb-4 border-0 shadow-sm" style="background:linear-gradient(135deg,#198754,#00897b);">
    <div class="card-body text-white d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <i class="bi bi-wallet2 fs-4 me-2"></i>
            <strong>Total Pendapatan (<?= esc(ucfirst(str_replace('_', ' ', $periode))) ?>)</strong>
            <div class="small opacity-75">Dari pembayaran yang sudah lunas</div>
        </div>
        <div class="fs-3 fw-bold">Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></div>
    </div>
</div>

<!-- ===== QUICK ACTIONS ===== -->
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-header bg-transparent fw-semibold">
        <i class="bi bi-lightning-charge me-2 text-warning"></i>Aksi Cepat
    </div>
    <div class="card-body">
        <div class="row g-2">
            <div class="col-md-3 col-6">
                <a href="/admin/kamar/tambah" class="btn btn-outline-primary w-100 py-3">
                    <i class="bi bi-plus-circle d-block fs-4 mb-1"></i>
                    <small>Tambah Kamar</small>
                </a>
            </div>
            <div class="col-md-3 col-6">
                <a href="/admin/pengumuman" class="btn btn-outline-info w-100 py-3">
                    <i class="bi bi-megaphone d-block fs-4 mb-1"></i>
                    <small>Buat Pengumuman</small>
                </a>
            </div>
            <div class="col-md-3 col-6">
                <a href="/admin/peraturan" class="btn btn-outline-secondary w-100 py-3">
                    <i class="bi bi-shield-check d-block fs-4 mb-1"></i>
                    <small>Atur Peraturan</small>
                </a>
            </div>
            <div class="col-md-3 col-6">
                <a href="/admin/pengaturan" class="btn btn-outline-warning w-100 py-3">
                    <i class="bi bi-gear d-block fs-4 mb-1"></i>
                    <small>Pengaturan</small>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- ===== TABEL LIST TERBARU ===== -->
<div class="row g-3">
    <!-- Pengajuan Sewa Terbaru -->
    <div class="col-md-6">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-header bg-primary text-white fw-semibold d-flex justify-content-between align-items-center">
                <span><i class="bi bi-file-earmark-text me-2"></i>Pengajuan Sewa Terbaru</span>
                <a href="/admin/sewa" class="text-white text-decoration-none small">Lihat Semua →</a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light"><tr><th>Nama</th><th>Kamar</th><th>Status</th></tr></thead>
                    <tbody>
                        <?php if (!empty($pengajuan_terbaru)): ?>
                            <?php foreach ($pengajuan_terbaru as $p): ?>
                            <tr>
                                <td><?= esc($p['nama']) ?></td>
                                <td>No. <?= esc($p['nomor_kamar']) ?></td>
                                <td><span class="badge bg-warning text-dark">Menunggu</span></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="3" class="text-center text-muted py-3">Tidak ada pengajuan</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Keluhan Terbaru -->
    <div class="col-md-6">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-header bg-danger text-white fw-semibold d-flex justify-content-between align-items-center">
                <span><i class="bi bi-chat-dots me-2"></i>Keluhan Terbaru</span>
                <a href="/admin/keluhan" class="text-white text-decoration-none small">Lihat Semua →</a>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light"><tr><th>Nama</th><th>Judul</th><th>Status</th></tr></thead>
                    <tbody>
                        <?php if (!empty($keluhan_terbaru)): ?>
                            <?php foreach ($keluhan_terbaru as $k): ?>
                            <tr>
                                <td>
                                    <?php if (!empty($k['is_private'])): ?>
                                        <span class="text-danger"><i class="bi bi-incognito"></i> Anonim</span>
                                    <?php else: ?>
                                        <?= esc($k['nama_user'] ?? $k['nama'] ?? '-') ?>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?= esc($k['judul']) ?>
                                    <?php
                                    $katLabels = [
                                        'kebisingan'=>'🔊','tetangga'=>'👥','fasilitas_kamar'=>'🏠',
                                        'listrik_air'=>'⚡','wifi'=>'📶','keamanan'=>'🔒',
                                        'kebersihan'=>'🧹','parkir'=>'🚗','lainnya'=>'📌',
                                        'kendala_akun'=>'🔑','website_bug'=>'🐛',
                                        'status_sewa'=>'⏳','info_kamar'=>'🏠','tagihan_sewa'=>'💰',
                                    ];
                                    $katBadges = [
                                        'kebisingan'=>'danger','tetangga'=>'warning',
                                        'fasilitas_kamar'=>'primary','listrik_air'=>'warning',
                                        'wifi'=>'info','keamanan'=>'danger','kebersihan'=>'success',
                                        'parkir'=>'secondary','lainnya'=>'secondary',
                                        'kendala_akun'=>'primary','website_bug'=>'dark',
                                        'status_sewa'=>'info','info_kamar'=>'success','tagihan_sewa'=>'warning',
                                    ];
                                    $kl = $katLabels[$k['kategori']] ?? '';
                                    $kb = $katBadges[$k['kategori']] ?? 'secondary';
                                    if (!empty($kl)):
                                    ?>
                                    <span class="badge bg-<?= $kb ?> ms-1"><?= $kl ?> <?= ucfirst(str_replace('_',' ',$k['kategori'])) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($k['status'] == 'menunggu'): ?>
                                        <span class="badge bg-warning text-dark">Menunggu</span>
                                    <?php elseif ($k['status'] == 'diproses'): ?>
                                        <span class="badge bg-info">Diproses</span>
                                    <?php else: ?>
                                        <span class="badge bg-success">Selesai</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="3" class="text-center text-muted py-3">Tidak ada keluhan</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Notifikasi Saya -->
    <div class="col-md-6">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-header bg-info text-white fw-semibold d-flex justify-content-between align-items-center">
                <span><i class="bi bi-bell me-2"></i>Notifikasi Terbaru</span>
                <a href="/admin/notifikasi" class="text-white text-decoration-none small">Lihat Semua →</a>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($notif_terbaru)): ?>
                    <?php foreach ($notif_terbaru as $n): ?>
                        <div class="border-bottom p-2 <?= $n['dibaca'] ? '' : 'bg-light' ?>">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <small class="fw-bold"><?= esc($n['judul']) ?></small>
                                    <p class="mb-0 text-muted" style="font-size:0.8rem;"><?= esc($n['pesan']) ?></p>
                                    <small class="text-muted" style="font-size:0.7rem;"><?= esc($n['created_at']) ?></small>
                                </div>
                                <?php if (!$n['dibaca']): ?>
                                    <span class="badge bg-danger">Baru</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-bell-slash fs-3 d-block mb-2"></i>
                        <small>Tidak ada notifikasi</small>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Pengumuman Terbaru -->
    <div class="col-md-6">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-header bg-success text-white fw-semibold d-flex justify-content-between align-items-center">
                <span><i class="bi bi-megaphone me-2"></i>Pengumuman Terbaru</span>
                <a href="/admin/pengumuman" class="text-white text-decoration-none small">Lihat Semua →</a>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($pengumuman_terbaru)): ?>
                    <?php foreach ($pengumuman_terbaru as $p): ?>
                        <div class="border-bottom p-2">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <small class="fw-bold"><?= esc($p['judul']) ?>
                                        <?php if ($p['status'] == 'aktif'): ?>
                                            <span class="badge bg-success ms-1">Aktif</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary ms-1">Nonaktif</span>
                                        <?php endif; ?>
                                    </small>
                                    <p class="mb-0 text-muted text-truncate" style="font-size:0.8rem;max-width:300px;"><?= esc($p['isi']) ?></p>
                                    <small class="text-muted" style="font-size:0.7rem;"><?= esc($p['created_at']) ?></small>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-megaphone fs-3 d-block mb-2"></i>
                        <small>Belum ada pengumuman</small>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>