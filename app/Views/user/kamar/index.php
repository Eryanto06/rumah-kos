<?= $this->extend('layouts/user') ?>
<?= $this->section('content') ?>
<div class="row g-3">
    <?php if (!empty($kamar)): foreach ($kamar as $k): ?>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-door-open me-2 text-success"></i>Kamar No. <?= $k['nomor_kamar'] ?></h5>
                <p class="text-muted mb-1"><small><?= $k['kode_kamar'] ?></small></p>
                <h4 class="text-success fw-bold">Rp <?= number_format($k['harga_sewa'],0,',','.') ?><small class="fs-6 text-muted">/bulan</small></h4>
                <p class="card-text"><small><?= $k['fasilitas'] ?></small></p>
                <span class="badge bg-success mb-2">Tersedia</span>
            </div>
            <div class="card-footer bg-transparent">
                <a href="/user/kamar/detail/<?= $k['id_kamar'] ?>" class="btn btn-outline-success btn-sm w-100">
                    <i class="bi bi-eye me-1"></i>Lihat Detail
                </a>
            </div>
        </div>
    </div>
    <?php endforeach; else: ?>
    <div class="col-12"><div class="alert alert-info">Tidak ada kamar tersedia saat ini.</div></div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
