<?= $this->extend('layouts/user') ?>
<?= $this->section('content') ?>

<?php if (!empty($sewa) && $sewa['status'] === 'aktif'): 
    $statusKunci = $sewa['status_kunci'] ?? 'belum_siap';
    $kunciInfo = [
        'belum_siap'         => ['warning', 'Menunggu Persetujuan Admin', 'Kunci belum siap. Tunggu admin menyetujui pengajuan sewa Anda.'],
        'siap_diambil'       => ['info', 'Kunci Siap Diambil!', 'Kunci kamar Anda siap diambil. Segera ambil di lokasi yang ditentukan.'],
        'sudah_diambil'      => ['success', 'Kunci Sudah Diambil', 'Anda sudah resmi menjadi penghuni. Selamat menempati!'],
        'sudah_dikembalikan' => ['secondary', 'Kunci Dikembalikan', 'Kunci sudah dikembalikan saat checkout.'],
    ];
    $info = $kunciInfo[$statusKunci] ?? $kunciInfo['belum_siap'];
?>
<!-- CARD INFO KUNCI -->
<div class="card border-0 shadow-sm mb-4 border-start border-<?= $info[0] ?> border-4">
    <div class="card-body">
        <div class="d-flex align-items-center gap-3 flex-wrap">
            <div class="flex-grow-1">
                <h6 class="fw-bold mb-1 text-<?= $info[0] ?>">
                    <?php
                    $ikon = ['warning'=>'⏳','info'=>'🔑','success'=>'✅','secondary'=>'🔄'];
                    echo $ikon[$info[0]] ?? '🔑';
                    ?> <?= $info[1] ?>
                </h6>
                <p class="mb-0 text-muted small"><?= $info[2] ?></p>
                <?php if ($statusKunci === 'siap_diambil'): ?>
                    <div class="mt-2 p-2 bg-light rounded">
                        <small>
                            <strong>Lokasi:</strong> <?= esc($sewa['lokasi_ambil_kunci'] ?? 'Office Rumah Kos') ?><br>
                            <strong>Yang dibawa:</strong> KTP asli + bukti bayar deposit<br>
                            <strong>Jam:</strong> 08:00 - 17:00 WIB
                        </small>
                    </div>
                <?php elseif ($statusKunci === 'sudah_diambil' && !empty($sewa['tanggal_ambil_kunci'])): ?>
                    <small class="text-muted mt-1 d-block">
                        <i class="bi bi-clock"></i> Diambil pada: <?= date('d M Y H:i', strtotime($sewa['tanggal_ambil_kunci'])) ?>
                    </small>
                <?php endif; ?>
            </div>
            <div class="text-center">
                <i class="bi bi-key-fill fs-1 text-<?= $info[0] ?> opacity-75"></i>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if (!empty($sewa)): ?>
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-transparent fw-semibold py-3">
        <i class="bi bi-house-door me-2 text-primary"></i>Informasi Kamar Saya
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-borderless table-sm">
                    <tr><td class="text-muted">Kode Kamar</td><td class="fw-bold"><?= esc($sewa['kode_kamar']) ?></td></tr>
                    <tr><td class="text-muted">Nomor Kamar</td><td class="fw-bold"><?= esc($sewa['nomor_kamar']) ?></td></tr>
                    <tr><td class="text-muted">Harga Sewa</td><td class="fw-bold text-success">Rp <?= number_format($sewa['harga_sewa'],0,',','.') ?>/bln</td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless table-sm">
                    <tr><td class="text-muted">Mulai Sewa</td><td class="fw-bold"><?= !empty($sewa['tanggal_mulai']) ? esc(date('d M Y', strtotime($sewa['tanggal_mulai']))) : '-' ?></td></tr>
                    <tr><td class="text-muted">Selesai Sewa</td><td class="fw-bold"><?= !empty($sewa['tanggal_selesai']) ? esc(date('d M Y', strtotime($sewa['tanggal_selesai']))) : '-' ?></td></tr>
                    <tr><td class="text-muted">Durasi</td><td class="fw-bold"><?= esc($sewa['durasi_bulan']) ?> bulan</td></tr>
                </table>
            </div>
        </div>
        <hr>
        <div class="d-flex gap-2 flex-wrap">
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

<?= $this->endSection() ?>