<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<style>
    .pindah-card {
        border: none;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        margin-bottom: 12px;
        border-left: 4px solid #dee2e6;
        transition: box-shadow .2s;
    }
    .pindah-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
    .pindah-card.status-menunggu  { border-left-color: #ffc107; }
    .pindah-card.status-disetujui { border-left-color: #198754; background-color: #f8fff9; }
    .pindah-card.status-ditolak   { border-left-color: #dc3545; opacity: 0.85; }
    .btn-action { border-radius: 6px; padding: 6px 14px; font-size: 0.85rem; font-weight: 500; }
    .filter-bar { background: #f8f9fa; padding: 14px 16px; border-radius: 8px; margin-bottom: 16px; }
</style>

<h4 class="mb-3"><i class="bi bi-arrow-left-right me-2"></i>Persetujuan Pindah Kamar</h4>

<!-- STATISTIK -->
<div class="row g-2 mb-3">
    <div class="col-md-3 col-sm-6">
        <div class="card text-center py-2">
            <div class="fs-5 fw-bold text-primary"><?= $total ?? 0 ?></div>
            <small class="text-muted">Total Pengajuan</small>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card text-center py-2">
            <div class="fs-5 fw-bold text-warning"><?= $menunggu ?? 0 ?></div>
            <small class="text-muted">Menunggu</small>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card text-center py-2">
            <div class="fs-5 fw-bold text-success"><?= $disetujui ?? 0 ?></div>
            <small class="text-muted">Disetujui</small>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="card text-center py-2">
            <div class="fs-5 fw-bold text-danger"><?= $ditolak ?? 0 ?></div>
            <small class="text-muted">Ditolak</small>
        </div>
    </div>
</div>

<!-- FILTER BAR -->
<form method="get" class="filter-bar">
    <div class="row g-2 align-items-end">
        <div class="col-md-5">
            <label class="form-label small mb-1 fw-semibold">Cari Nama / Kamar</label>
            <div class="input-group input-group-sm">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Ketik nama penghuni atau no kamar..."
                       value="<?= esc($search_nama ?? '') ?>">
            </div>
        </div>
        <div class="col-md-4">
            <label class="form-label small mb-1 fw-semibold">Filter Status</label>
            <select name="status" class="form-select form-select-sm">
                <option value="">Semua Status</option>
                <option value="menunggu"  <?= ($filter_status ?? '') === 'menunggu'  ? 'selected' : '' ?>>Menunggu</option>
                <option value="disetujui" <?= ($filter_status ?? '') === 'disetujui' ? 'selected' : '' ?>>Disetujui</option>
                <option value="ditolak"   <?= ($filter_status ?? '') === 'ditolak'   ? 'selected' : '' ?>>Ditolak</option>
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-sm flex-grow-1"><i class="bi bi-funnel me-1"></i>Filter</button>
            <a href="/admin/pindah-kamar" class="btn btn-outline-secondary btn-sm"><i class="bi bi-x-circle"></i></a>
        </div>
    </div>
</form>

<!-- DAFTAR PENGAJUAN -->
<?php if (!empty($pengajuan)): ?>
    <?php foreach ($pengajuan as $p):
        $statusClass = 'status-' . $p['status'];
        $badges = [
            'menunggu'  => ['warning text-dark', 'Menunggu'],
            'disetujui' => ['success', 'Disetujui'],
            'ditolak'   => ['danger', 'Ditolak']
        ];
        $b = $badges[$p['status']] ?? ['secondary', esc(ucfirst($p['status']))];

        $selisihHarga = ($p['harga_baru'] ?? 0) - ($p['harga_lama'] ?? 0);
    ?>
    <div class="card pindah-card <?= $statusClass ?>">
        <div class="card-body p-3">
            <div class="row align-items-center">
                <!-- INFO PENGGUNA -->
                <div class="col-md-4">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width:42px;height:42px;">
                            <i class="bi bi-person-fill text-primary"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold"><?= esc($p['nama_user'] ?? '-') ?></h6>
                            <small class="text-muted">
                                <i class="bi bi-calendar-event me-1"></i><?= !empty($p['tanggal_pengajuan']) ? esc(date('d M Y', strtotime($p['tanggal_pengajuan']))) : '-' ?>
                            </small>
                        </div>
                    </div>
                </div>

                <!-- INFO PINDAH KAMAR -->
                <div class="col-md-5 mt-2 mt-md-0">
                    <small class="text-muted d-block">
                        Kamar No. <strong><?= esc($p['nomor_kamar_lama'] ?? '-') ?></strong>
                        <i class="bi bi-arrow-right mx-1"></i>
                        Kamar No. <strong><?= esc($p['nomor_kamar_baru'] ?? '-') ?></strong>
                    </small>
                    <small class="text-muted d-block mt-1">
                        Rp <?= number_format($p['harga_lama'] ?? 0,0,',','.') ?>
                        <i class="bi bi-arrow-right mx-1"></i>
                        Rp <?= number_format($p['harga_baru'] ?? 0,0,',','.') ?>/bln
                        <?php if ($selisihHarga > 0): ?>
                            <span class="badge bg-danger ms-1">+Rp <?= number_format($selisihHarga,0,',','.') ?></span>
                        <?php elseif ($selisihHarga < 0): ?>
                            <span class="badge bg-success ms-1">-Rp <?= number_format(abs($selisihHarga),0,',','.') ?></span>
                        <?php endif; ?>
                    </small>
                    <small class="text-muted d-block mt-1">
                        <i class="bi bi-chat me-1"></i><?= esc($p['alasan'] ?? '-') ?>
                    </small>
                </div>

                <!-- STATUS & AKSI -->
                <div class="col-md-3 mt-2 mt-md-0 text-md-end">
                    <span class="badge bg-<?= $b[0] ?> mb-2"><?= $b[1] ?></span>
                    <div>
                        <?php if ($p['status'] == 'menunggu'): ?>
                            <a href="/admin/pindah-kamar/form-inspeksi/<?= $p['id_pindah'] ?>" class="btn btn-info btn-action text-white">
                                <i class="bi bi-search me-1"></i>Inspeksi
                            </a>
                            <form action="/admin/pindah-kamar/tolak/<?= $p['id_pindah'] ?>" method="post" style="display:inline;">
                                <?= csrf_field() ?>
                                <input type="hidden" name="alasan" value="Tidak memenuhi syarat">
                                <button type="submit" class="btn btn-outline-danger btn-action"
                                    onclick="return confirm('Tolak pengajuan pindah kamar ini?')">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </form>
                        <?php elseif ($p['status'] == 'disetujui'): ?>
                            <a href="/admin/pindah-kamar/form-inspeksi/<?= $p['id_pindah'] ?>" class="btn btn-warning btn-action">
                                <i class="bi bi-eye me-1"></i>Detail
                            </a>
                        <?php else: ?>
                            <a href="/admin/pindah-kamar/form-inspeksi/<?= $p['id_pindah'] ?>" class="btn btn-warning btn-action">
                                <i class="bi bi-eye me-1"></i>Detail
                            </a>
                            <form action="/admin/pindah-kamar/batalkan-tolak/<?= $p['id_pindah'] ?>" method="post" style="display:inline;">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-outline-success btn-action"
                                    title="Batalkan Penolakan"
                                    onclick="return confirm('BATALKAN PENOLAKAN pengajuan pindah dari <?= esc($p['nama_user'] ?? 'user', 'js') ?>? Status kembali ke MENUNGGU.')">
                                    <i class="bi bi-arrow-counterclockwise"></i> Undo
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <?php if (!empty($p['keterangan_admin'])): ?>
            <div class="mt-2 pt-2 border-top">
                <small class="text-muted">
                    <i class="bi bi-info-circle me-1"></i>
                    <strong>Catatan:</strong> <?= esc($p['keterangan_admin']) ?>
                </small>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>

<?php else: ?>
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <i class="bi bi-inbox fs-1 text-muted opacity-50 d-block mb-2"></i>
            <h6 class="fw-bold text-muted">
                <?= !empty($search_nama) || !empty($filter_status) ? 'Tidak Ada Hasil Filter' : 'Belum Ada Pengajuan Pindah Kamar' ?>
            </h6>
            <p class="text-muted small mb-0">
                <?= !empty($search_nama) || !empty($filter_status) ? 'Coba ubah kata kunci atau filter.' : 'Pengajuan pindah kamar dari penghuni akan muncul di sini.' ?>
            </p>
            <?php if (!empty($search_nama) || !empty($filter_status)): ?>
            <a href="/admin/pindah-kamar" class="btn btn-outline-primary btn-sm mt-3"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset Filter</a>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>
