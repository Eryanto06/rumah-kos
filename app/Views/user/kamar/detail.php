<?= $this->extend('layouts/user') ?>
<?= $this->section('content') ?>
<div class="card" style="max-width:600px">
    <div class="card-header fw-semibold"><i class="bi bi-door-open me-2"></i>Detail Kamar</div>
    <div class="card-body">
        <table class="table table-bordered">
            <tr><th>Kode Kamar</th><td><?= $kamar['kode_kamar'] ?></td></tr>
            <tr><th>Nomor Kamar</th><td><?= $kamar['nomor_kamar'] ?></td></tr>
            <tr><th>Harga Sewa</th><td>Rp <?= number_format($kamar['harga_sewa'],0,',','.') ?>/bulan</td></tr>
            <tr><th>Fasilitas</th><td><?= $kamar['fasilitas'] ?></td></tr>
            <tr><th>Status</th><td><span class="badge bg-success">Tersedia</span></td></tr>
        </table>
        <a href="/user/sewa" class="btn btn-success">
            <i class="bi bi-file-earmark-plus me-1"></i>Ajukan Sewa
        </a>
        <a href="/user/kamar" class="btn btn-secondary ms-2">Kembali</a>
    </div>
</div>
<?= $this->endSection() ?>
