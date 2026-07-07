<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<style>
    .header-gradient {
        background: linear-gradient(135deg, #1a237e 0%, #00897b 100%);
        color: white;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
    }
    .stat-card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        transition: transform 0.2s, box-shadow 0.2s;
        cursor: pointer;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .stat-card.active-filter {
        border: 2px solid #1a237e;
        background: linear-gradient(135deg, #f0f4ff 0%, #e8f5e9 100%);
    }

    .sewa-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        transition: all 0.2s;
        margin-bottom: 16px;
        border-left: 5px solid #dee2e6;
        overflow: hidden;
    }
    .sewa-card:hover {
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .sewa-card.status-menunggu { border-left-color: #ffc107; background-color: #fffdf5; }
    .sewa-card.status-aktif    { border-left-color: #198754; background-color: #f8fff9; }
    .sewa-card.status-ditolak  { border-left-color: #dc3545; background-color: #fff5f5; opacity: 0.95; }
    .sewa-card.status-selesai  { border-left-color: #6c757d; background-color: #fafafa; }

    .btn-action {
        border-radius: 8px;
        padding: 8px 14px;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.2s;
        white-space: nowrap;
    }
    .btn-action:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(0,0,0,0.12);
    }

    .info-row {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 6px;
        font-size: 0.9rem;
    }
    .info-row:last-child { margin-bottom: 0; }
    .info-label {
        color: #6c757d;
        min-width: 75px;
        font-size: 0.8rem;
    }
    .info-value {
        color: #212529;
        font-weight: 500;
    }

    .avatar-circle {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .avatar-circle.ditolak {
        background: linear-gradient(135deg, #ffcdd2 0%, #ef9a9a 100%);
    }
    .avatar-circle.aktif {
        background: linear-gradient(135deg, #c8e6c9 0%, #a5d6a7 100%);
    }
    .avatar-circle.selesai {
        background: linear-gradient(135deg, #eceff1 0%, #cfd8dc 100%);
    }

    .status-badge {
        font-size: 0.85rem;
        padding: 6px 14px;
        border-radius: 20px;
        font-weight: 600;
    }

    @media (max-width: 992px) {
        .info-label { min-width: 65px; font-size: 0.75rem; }
        .info-row { font-size: 0.85rem; }
    }
</style>

<!-- HEADER -->
<div class="header-gradient">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h4 class="mb-1 fw-bold"><i class="bi bi-file-earmark-text me-2"></i>Pengajuan Sewa</h4>
            <small class="opacity-75">Kelola semua pengajuan sewa kamar dari pendaftar</small>
        </div>
        <?php if (!empty($filter_status)): ?>
            <a href="/admin/sewa" class="btn btn-light btn-sm">
                <i class="bi bi-x-circle me-1"></i>Reset Filter
            </a>
        <?php endif; ?>
    </div>
</div>

<!-- STATISTIK CEPAT (klik untuk filter) -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md">
        <a href="/admin/sewa" class="text-decoration-none">
            <div class="card stat-card text-center py-3 <?= empty($filter_status) ? 'active-filter' : '' ?>">
                <div class="fs-3 fw-bold text-primary"><?= $counts['total'] ?></div>
                <small class="text-muted">Semua</small>
            </div>
        </a>
    </div>
    <div class="col-6 col-md">
        <a href="/admin/sewa?status=menunggu" class="text-decoration-none">
            <div class="card stat-card text-center py-3 <?= $filter_status==='menunggu' ? 'active-filter' : '' ?>" style="background:#fffdf5;">
                <div class="fs-3 fw-bold text-warning"><?= $counts['menunggu'] ?></div>
                <small class="text-muted">Menunggu</small>
            </div>
        </a>
    </div>
    <div class="col-6 col-md">
        <a href="/admin/sewa?status=aktif" class="text-decoration-none">
            <div class="card stat-card text-center py-3 <?= $filter_status==='aktif' ? 'active-filter' : '' ?>" style="background:#f8fff9;">
                <div class="fs-3 fw-bold text-success"><?= $counts['aktif'] ?></div>
                <small class="text-muted">Aktif</small>
            </div>
        </a>
    </div>
    <div class="col-6 col-md">
        <a href="/admin/sewa?status=ditolak" class="text-decoration-none">
            <div class="card stat-card text-center py-3 <?= $filter_status==='ditolak' ? 'active-filter' : '' ?>" style="background:#fff5f5;">
                <div class="fs-3 fw-bold text-danger"><?= $counts['ditolak'] ?></div>
                <small class="text-muted">Ditolak</small>
            </div>
        </a>
    </div>
    <div class="col-6 col-md">
        <a href="/admin/sewa?status=selesai" class="text-decoration-none">
            <div class="card stat-card text-center py-3 <?= $filter_status==='selesai' ? 'active-filter' : '' ?>" style="background:#fafafa;">
                <div class="fs-3 fw-bold text-secondary"><?= $counts['selesai'] ?></div>
                <small class="text-muted">Selesai</small>
            </div>
        </a>
    </div>
</div>

<!-- DAFTAR PENGAJUAN SEWA (CARD LAYOUT) -->
<?php if (!empty($sewa)): ?>
    <?php
    $badge = [
        'menunggu' => ['warning text-dark', 'Menunggu'],
        'aktif'    => ['success', 'Aktif'],
        'ditolak'  => ['danger', 'Ditolak'],
        'selesai'  => ['secondary', 'Selesai'],
    ];
    $kunciBadge = [
        'belum_siap'         => ['secondary', 'Belum Siap'],
        'siap_diambil'       => ['warning text-dark', 'Siap Diambil'],
        'sudah_diambil'      => ['success', 'Sudah Diambil'],
        'sudah_dikembalikan' => ['info text-dark', 'Dikembalikan'],
    ];
    ?>
    <?php foreach ($sewa as $s):
        $b = $badge[$s['status']] ?? ['secondary', esc(ucfirst($s['status']))];
        $kb = $kunciBadge[$s['status_kunci'] ?? 'belum_siap'] ?? ['secondary', 'Belum Siap'];
        $statusClass = 'status-' . $s['status'];
        $avatarClass = $s['status'];
        $statusKunciSekarang = $s['status_kunci'] ?? 'belum_siap';
    ?>
    <div class="card sewa-card <?= $statusClass ?>">
        <div class="card-body p-3 p-md-4">
            <div class="row align-items-center g-3">

                <!-- KOLOM 1: AVATAR + NAMA + STATUS -->
                <div class="col-md-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-circle <?= $avatarClass ?>">
                            <i class="bi bi-person-fill fs-3 text-white"></i>
                        </div>
                        <div class="flex-grow-1 min-w-0">
                            <h6 class="mb-0 fw-bold text-truncate" title="<?= esc($s['nama'], 'attr') ?>"><?= esc($s['nama']) ?></h6>
                            <span class="badge bg-<?= $b[0] ?> status-badge d-inline-block mt-1"><?= $b[1] ?></span>
                        </div>
                    </div>
                </div>

                <!-- KOLOM 2: INFO KAMAR & HARGA -->
                <div class="col-md-3">
                    <div class="info-row">
                        <span class="info-label"><i class="bi bi-door-closed me-1"></i>Kamar</span>
                        <span class="info-value">
                            <span class="badge bg-secondary me-1"><?= esc($s['kode_kamar']) ?></span>
                            No. <?= esc($s['nomor_kamar']) ?>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="bi bi-cash me-1"></i>Harga</span>
                        <span class="info-value text-success">Rp <?= number_format($s['harga_sewa'],0,',','.') ?><small class="text-muted">/bln</small></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="bi bi-calendar3 me-1"></i>Durasi</span>
                        <span class="info-value"><?= esc($s['durasi_bulan']) ?> bulan</span>
                    </div>
                </div>

                <!-- KOLOM 3: PERIODE & TANGGAL -->
                <div class="col-md-3">
                    <div class="info-row">
                        <span class="info-label"><i class="bi bi-calendar-event me-1"></i>Mulai</span>
                        <span class="info-value">
                            <?php if (!empty($s['tanggal_mulai'])): ?>
                                <?= esc(date('d M Y', strtotime($s['tanggal_mulai']))) ?>
                            <?php else: ?>
                                <span class="text-muted fst-italic">Belum dimulai</span>
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="bi bi-calendar-check me-1"></i>Selesai</span>
                        <span class="info-value">
                            <?php if (!empty($s['tanggal_selesai'])): ?>
                                <?= esc(date('d M Y', strtotime($s['tanggal_selesai']))) ?>
                            <?php else: ?>
                                <span class="text-muted fst-italic">-</span>
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><i class="bi bi-clock-history me-1"></i>Diajukan</span>
                        <span class="info-value"><?= esc(date('d M Y', strtotime($s['tanggal_pengajuan']))) ?></span>
                    </div>
                </div>

                <!-- KOLOM 4: STATUS KUNCI + AKSI -->
                <div class="col-md-3">
                    <div class="d-flex flex-column gap-2 align-items-md-end">
                        <!-- Status Kunci -->
                        <div class="text-md-end">
                            <small class="text-muted d-block mb-1"><i class="bi bi-key me-1"></i>Status Kunci</small>
                            <span class="badge bg-<?= $kb[0] ?>"><?= $kb[1] ?></span>
                            <?php if (!empty($s['tanggal_ambil_kunci'])): ?>
                                <small class="text-muted d-block mt-1" style="font-size:0.7rem;">
                                    <i class="bi bi-clock"></i> <?= date('d M Y H:i', strtotime($s['tanggal_ambil_kunci'])) ?>
                                </small>
                            <?php endif; ?>
                            <?php
                            // FIX BUG #17: Tampilkan warning kalau kunci 'belum_siap' untuk sewa aktif
                            // (kemungkinan user pindah kamar dengan selisih deposit belum lunas).
                            if ($s['status'] === 'aktif' && $statusKunciSekarang === 'belum_siap'):
                            ?>
                            <small class="text-danger d-block mt-1" style="font-size:0.7rem;">
                                <i class="bi bi-exclamation-triangle-fill"></i> Cek deposit user lunas sebelum siapkan kunci
                            </small>
                            <?php endif; ?>
                        </div>

                        <!-- Tombol Aksi Kunci (untuk sewa aktif) -->
                        <?php if ($s['status'] === 'aktif'): ?>
                            <?php if ($statusKunciSekarang === 'belum_siap'): ?>
                                <form action="/admin/sewa/set-kunci/<?= $s['id_sewa'] ?>/siap_diambil" method="post" style="display:inline;">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-outline-warning btn-action"
                                       onclick="return confirm('Tandai kunci kamar No. <?= esc($s['nomor_kamar']) ?> sebagai SIAP DIAMBIL?')">
                                        <i class="bi bi-key me-1"></i>Set Siap Kunci
                                    </button>
                                </form>
                            <?php elseif ($statusKunciSekarang === 'siap_diambil'): ?>
                                <form action="/admin/sewa/kunci-diambil/<?= $s['id_sewa'] ?>" method="post">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-success btn-action w-100"
                                        onclick="return confirm('Tandai kunci sudah diambil oleh <?= esc($s['nama'], 'js') ?>?')">
                                        <i class="bi bi-key me-1"></i>Kunci Sudah Diambil
                                    </button>
                                </form>
                            <?php endif; ?>
                        <?php endif; ?>

                        <!-- Tombol Aksi Utama -->
                        <div class="d-flex gap-2 flex-wrap justify-content-md-end w-100">
                            <a href="/admin/sewa/detail/<?= $s['id_sewa'] ?>" class="btn btn-info btn-action text-white">
                                <i class="bi bi-eye me-1"></i>Detail
                            </a>

                            <?php if ($s['status'] == 'menunggu'): ?>
                            <form action="/admin/sewa/setujui/<?= $s['id_sewa'] ?>" method="post" style="display:inline;">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-success btn-action"
                                   onclick="return confirm('Setujui pengajuan sewa dari <?= esc($s['nama'], 'js') ?>?\n\nPastikan deposit user sudah LUNAS sebelum disetujui.')">
                                    <i class="bi bi-check-lg me-1"></i>Setujui
                                </button>
                            </form>
                            <form action="/admin/sewa/tolak/<?= $s['id_sewa'] ?>" method="post" style="display:inline;">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-danger btn-action"
                                   onclick="return confirm('Tolak pengajuan sewa dari <?= esc($s['nama'], 'js') ?>?')">
                                    <i class="bi bi-x-lg me-1"></i>Tolak
                                </button>
                            </form>
                            <?php elseif ($s['status'] == 'ditolak'): ?>
                            <form action="/admin/sewa/batalkan-tolak/<?= $s['id_sewa'] ?>" method="post" style="display:inline;">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-warning btn-action"
                                   title="Batalkan Penolakan (Undo Reject) - untuk kasus salah tekan"
                                   onclick="return confirm('⚠️ BATALKAN PENOLAKAN pengajuan dari <?= esc($s['nama'], 'js') ?>?\n\nStatus kembali ke MENUNGGU.\n\nPastikan BELUM refund deposit ke user!\n\nLanjutkan?')">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i>Undo Tolak
                                </button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <?php endforeach; ?>

<?php else: ?>
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <i class="bi bi-inbox fs-1 text-muted opacity-50 d-block mb-3"></i>
            <h5 class="fw-bold text-muted">Tidak Ada Data Sewa</h5>
            <p class="text-muted mb-0">
                <?php if (!empty($filter_status)): ?>
                    Tidak ada sewa dengan status <strong><?= esc(ucfirst($filter_status)) ?></strong>.
                    <a href="/admin/sewa" class="btn btn-sm btn-outline-primary ms-2">Lihat Semua</a>
                <?php else: ?>
                    Belum ada pengajuan sewa masuk.
                <?php endif; ?>
            </p>
        </div>
    </div>
<?php endif; ?>

<!-- INFO BANTUAN -->
<div class="alert alert-info mt-3">
    <h6 class="alert-heading fw-bold"><i class="bi bi-info-circle me-1"></i>Informasi Pengajuan Sewa</h6>
    <ul class="mb-0 small">
        <li><strong class="text-warning">Menunggu:</strong> Pengajuan baru masuk, menunggu admin setujui. Pastikan deposit user sudah lunas sebelum disetujui.</li>
        <li><strong class="text-success">Aktif:</strong> Sewa berjalan. User sedang menempati kamar. Status kunci bisa diupdate (Siap Diambil → Sudah Diambil).</li>
        <li><strong class="text-danger">Ditolak:</strong> Pengajuan ditolak admin. Tagihan belum bayar dihapus otomatis. Jika user sudah bayar deposit, wajib refund manual. Bisa di-undo kalau salah tekan.</li>
        <li><strong class="text-secondary">Selesai:</strong> Sewa berakhir (checkout atau pindah kamar). Kamar kembali tersedia.</li>
        <li><strong>Filter:</strong> Klik kartu statistik di atas untuk menyaring data berdasarkan status.</li>
    </ul>
</div>

<?= $this->endSection() ?>
