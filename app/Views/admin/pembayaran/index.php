<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-3">
        <!-- KOTAK PENCARIAN + FILTER -->
        <div class="row g-2 align-items-center">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-primary text-white"><i class="bi bi-search"></i></span>
                    <input type="text" id="searchBayar" class="form-control" placeholder="Cari nama penghuni atau nomor kamar..." oninput="filterPembayaran()">
                </div>
            </div>
            <div class="col-md-4">
                <select id="filterStatus" class="form-select" onchange="filterPembayaran()">
                    <option value="all">📋 Semua Status</option>
                    <option value="belum_bayar">❌ Belum Bayar</option>
                    <option value="menunggu_verifikasi">⏳ Menunggu Verifikasi</option>
                    <option value="lunas">✅ Lunas</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-secondary w-100" onclick="resetFilter()">
                    <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                </button>
            </div>
        </div>
    </div>
</div>

<!-- STATISTIK CEPAT -->
<div class="row g-2 mb-3">
    <div class="col-md-4">
        <div class="card border-0 bg-danger bg-opacity-25 text-center py-2">
            <div class="fs-4 fw-bold text-danger" id="countBelum"><?= $total_belum ?></div>
            <small class="text-muted">Belum Bayar</small>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 bg-warning bg-opacity-25 text-center py-2">
            <div class="fs-4 fw-bold text-warning" id="countMenunggu"><?= $total_menunggu ?></div>
            <small class="text-muted">Menunggu Verifikasi</small>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 bg-success bg-opacity-25 text-center py-2">
            <div class="fs-4 fw-bold text-success" id="countLunas"><?= $total_lunas ?></div>
            <small class="text-muted">Lunas</small>
        </div>
    </div>
</div>

<div id="pembayaranContainer">
<?php if (empty($grouped)): ?>
<div class="alert alert-info text-center py-4">
    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
    Belum ada data pembayaran.
</div>
<?php else: ?>

<div class="accordion" id="accordionPembayaran">
    <?php $i = 0; foreach ($grouped as $idSewa => $g): $i++; ?>
    <div class="accordion-item mb-2 shadow-sm rounded pembayaran-item" 
         data-nama="<?= strtolower(esc($g['nama'])) ?>" 
         data-kamar="<?= strtolower(esc($g['nomor_kamar'])) ?>"
         data-belum="<?= $g['total_belum'] ?>"
         data-menunggu="<?= $g['total_menunggu'] ?>"
         data-lunas="<?= $g['total_lunas'] ?>">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button"
                    data-bs-toggle="collapse" data-bs-target="#collapse<?= $idSewa ?>">
                <div class="d-flex justify-content-between align-items-center w-100 me-3">
                    <span>
                        <i class="bi bi-person-circle me-2"></i>
                        <strong><?= esc($g['nama']) ?></strong>
                        <span class="text-muted ms-2">(<?= esc($g['kode_kamar']) ?> - No. <?= esc($g['nomor_kamar']) ?>)</span>
                    </span>
                    <span class="d-flex gap-2">
                        <?php if ($g['total_belum'] > 0): ?>
                            <span class="badge bg-danger"><?= $g['total_belum'] ?> Belum</span>
                        <?php endif; ?>
                        <?php if ($g['total_menunggu'] > 0): ?>
                            <span class="badge bg-warning text-dark"><?= $g['total_menunggu'] ?> Verifikasi</span>
                        <?php endif; ?>
                        <?php if ($g['total_lunas'] > 0): ?>
                            <span class="badge bg-success"><?= $g['total_lunas'] ?> Lunas</span>
                        <?php endif; ?>
                    </span>
                </div>
            </button>
        </h2>
        <div id="collapse<?= $idSewa ?>" class="accordion-collapse collapse" data-bs-parent="#accordionPembayaran">
            <div class="accordion-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Bulan Ke</th>
                            <th>Jumlah</th>
                            <th>Tgl Bayar</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($g['items'] as $p): ?>
                        <tr>
                            <td>
                                <?= label_bulan_ke($p['bulan_ke']) ?>
                            </td>
                            <td>
                                Rp <?= number_format($p['jumlah_bayar'],0,',','.') ?>
                                <?php if (($p['total_denda'] ?? 0) > 0): ?>
                                    <br><small class="text-danger">+ Denda Rp <?= number_format($p['total_denda'],0,',','.') ?></small>
                                <?php endif; ?>
                            </td>
                            <td><small><?= $p['tanggal_bayar'] ?? '-' ?></small></td>
                            <td>
                                <?php
                                $badges = ['belum_bayar'=>'danger','menunggu_verifikasi'=>'warning text-dark','lunas'=>'success'];
                                $labels = ['belum_bayar'=>'Belum Bayar','menunggu_verifikasi'=>'Menunggu Verifikasi','lunas'=>'Lunas'];
                                $b = $badges[$p['status']] ?? 'secondary';
                                $l = $labels[$p['status']] ?? $p['status'];
                                ?>
                                <span class="badge bg-<?= $b ?>"><?= $l ?></span>
                            </td>
                            <td>
                                <a href="/admin/pembayaran/detail/<?= $p['id_pembayaran'] ?>" class="btn btn-info btn-sm text-white">
                                    <i class="bi bi-eye"></i> Cek
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php endif; ?>
</div>

<script>
function filterPembayaran() {
    const keyword = document.getElementById('searchBayar').value.toLowerCase().trim();
    const statusFilter = document.getElementById('filterStatus').value;
    const items = document.querySelectorAll('.pembayaran-item');
    
    let tampilBelum = 0, tampilMenunggu = 0, tampilLunas = 0;

    items.forEach(item => {
        const nama = item.dataset.nama;
        const kamar = item.dataset.kamar;
        const belum = parseInt(item.dataset.belum);
        const menunggu = parseInt(item.dataset.menunggu);
        const lunas = parseInt(item.dataset.lunas);

        const cocokKata = keyword === '' || nama.includes(keyword) || kamar.includes(keyword);
        
        let cocokStatus = true;
        if (statusFilter !== 'all') {
            if (statusFilter === 'belum_bayar') cocokStatus = belum > 0;
            else if (statusFilter === 'menunggu_verifikasi') cocokStatus = menunggu > 0;
            else if (statusFilter === 'lunas') cocokStatus = lunas > 0;
        }

        if (cocokKata && cocokStatus) {
            item.style.display = '';
            tampilBelum += belum;
            tampilMenunggu += menunggu;
            tampilLunas += lunas;
        } else {
            item.style.display = 'none';
        }
    });

    // Update statistik sesuai hasil filter
    document.getElementById('countBelum').textContent = tampilBelum;
    document.getElementById('countMenunggu').textContent = tampilMenunggu;
    document.getElementById('countLunas').textContent = tampilLunas;
}

function resetFilter() {
    document.getElementById('searchBayar').value = '';
    document.getElementById('filterStatus').value = 'all';
    filterPembayaran();
}
</script>

<?= $this->endSection() ?>