<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center py-3">
        <span class="fw-bold fs-5"><i class="bi bi-door-closed me-2 text-primary"></i>Data Kamar</span>
        <a href="/admin/kamar/tambah" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Tambah Kamar
        </a>
    </div>
    <div class="card-body">

        <!-- KOTAK PENCARIAN + FILTER STATUS -->
        <div class="row g-2 mb-3">
            <div class="col-md-7">
                <div class="input-group">
                    <span class="input-group-text bg-primary text-white"><i class="bi bi-search"></i></span>
                    <input type="text" id="searchKamar" class="form-control" placeholder="Cari kode, nomor kamar, atau fasilitas..." oninput="cariKamar()">
                </div>
            </div>
            <div class="col-md-3">
                <select id="filterStatus" class="form-select" onchange="cariKamar()">
                    <option value="all">📋 Semua Status</option>
                    <option value="tersedia">✅ Tersedia</option>
                    <option value="terisi">🏠 Terisi</option>
                    <option value="perbaikan">🔧 Perbaikan</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-secondary w-100" onclick="resetFilter()">
                    <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                </button>
            </div>
        </div>

        <!-- INFO HASIL PENCARIAN -->
        <div class="d-flex justify-content-between align-items-center mb-2">
            <small class="text-muted" id="searchInfo">Menampilkan semua kamar</small>
            <small class="text-muted">
                <span class="badge bg-success">Tersedia: <span id="countTersedia">0</span></span>
                <span class="badge bg-danger">Terisi: <span id="countTerisi">0</span></span>
            </small>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle" id="tabelKamar">
                <thead class="table-dark">
                    <tr>
                        <th width="50">No</th>
                        <th>Kode</th>
                        <th>No. Kamar</th>
                        <th>Harga Sewa</th>
                        <th>Fasilitas</th>
                        <th>Status</th>
                        <th width="100">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($kamar)): ?>
                        <?php $no = 1; foreach ($kamar as $k): ?>
                        <tr data-kode="<?= esc($k['kode_kamar']) ?>" data-nomor="<?= esc($k['nomor_kamar'], 'attr') ?>" data-fasilitas="<?= esc($k['fasilitas']) ?>" data-status="<?= esc($k['status']) ?>">
                            <td><?= $no++ ?></td>
                            <td>
                                <span class="badge bg-secondary fs-6">
                                    <i class="bi bi-tag me-1"></i><?= esc($k['kode_kamar']) ?>
                                </span>
                            </td>
                            <td>
                                <strong class="fs-6">No. <?= esc($k['nomor_kamar']) ?></strong>
                            </td>
                            <td>
                                <span class="text-success fw-bold">Rp <?= number_format($k['harga_sewa'], 0, ',', '.') ?></span>
                                <small class="text-muted d-block">/ bulan</small>
                            </td>
                            <td>
                                <small class="text-muted text-truncate d-inline-block" style="max-width:200px;" title="<?= esc($k['fasilitas']) ?>">
                                    <?= esc($k['fasilitas']) ?>
                                </small>
                            </td>
                            <td>
                                <?php if ($k['status'] == 'tersedia'): ?>
                                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Tersedia</span>
                                <?php elseif ($k['status'] == 'terisi'): ?>
                                    <span class="badge bg-danger"><i class="bi bi-house-fill me-1"></i>Terisi</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark"><i class="bi bi-tools me-1"></i>Perbaikan</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="/admin/kamar/edit/<?= $k['id_kamar'] ?>" class="btn btn-warning btn-sm" title="Edit Kamar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="/admin/kamar/hapus/<?= $k['id_kamar'] ?>" method="post" style="display:inline;">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-danger btn-sm"
                                       onclick="return confirm('Yakin hapus kamar No. <?= esc($k['nomor_kamar'], 'js') ?>? Tidak bisa dibatalkan.')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                            Belum ada data kamar
                        </td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function cariKamar() {
    const keyword = document.getElementById('searchKamar').value.toLowerCase().trim();
    const statusFilter = document.getElementById('filterStatus').value;
    const rows = document.querySelectorAll('#tabelKamar tbody tr');
    let tampil = 0, tersedia = 0, terisi = 0;

    rows.forEach(row => {
        if (!row.dataset.kode) return;

        const cocokKata = keyword === '' ||
            row.dataset.kode.toLowerCase().includes(keyword) ||
            row.dataset.nomor.toLowerCase().includes(keyword) ||
            row.dataset.fasilitas.toLowerCase().includes(keyword);

        const cocokStatus = statusFilter === 'all' || row.dataset.status === statusFilter;

        if (cocokKata && cocokStatus) {
            row.style.display = '';
            tampil++;
            if (row.dataset.status === 'tersedia') tersedia++;
            if (row.dataset.status === 'terisi') terisi++;
        } else {
            row.style.display = 'none';
        }
    });

    // Update info
    const info = document.getElementById('searchInfo');
    if (keyword === '' && statusFilter === 'all') {
        info.innerHTML = 'Menampilkan semua kamar (' + tampil + ')';
    } else {
        info.innerHTML = 'Menampilkan <strong>' + tampil + '</strong> kamar untuk pencarian "' + (keyword || 'semua') + '"';
    }
    document.getElementById('countTersedia').textContent = tersedia;
    document.getElementById('countTerisi').textContent = terisi;
}

function resetFilter() {
    document.getElementById('searchKamar').value = '';
    document.getElementById('filterStatus').value = 'all';
    cariKamar();
}

// Inisialisasi counter saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    cariKamar();
});
</script>

<?= $this->endSection() ?>