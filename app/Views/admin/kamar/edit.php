<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="card" style="max-width:600px">
    <div class="card-header fw-semibold">
        <i class="bi bi-pencil me-2"></i>Edit Kamar
    </div>
    <div class="card-body">
        <form action="/admin/kamar/update/<?= $kamar['id_kamar'] ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label class="form-label fw-semibold">Kode Kamar</label>
                <input type="text" name="kode_kamar" class="form-control" value="<?= $kamar['kode_kamar'] ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Nomor Kamar</label>
                <input type="text" name="nomor_kamar" class="form-control" value="<?= $kamar['nomor_kamar'] ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Harga Sewa</label>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="number" name="harga_sewa" class="form-control" value="<?= $kamar['harga_sewa'] ?>" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Fasilitas</label>
                <textarea name="fasilitas" class="form-control" rows="3"><?= esc($kamar['fasilitas']) ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Foto Kamar</label>
                <?php if (!empty($kamar['foto'])): ?>
                    <div class="mb-2">
                        <img src="/uploads/<?= esc($kamar['foto']) ?>" alt="Foto Kamar" class="img-thumbnail" style="max-height:200px;">
                        <small class="text-muted d-block mt-1">Foto saat ini. Upload file baru untuk mengganti.</small>
                    </div>
                <?php else: ?>
                    <small class="text-muted d-block mb-2">Belum ada foto. Upload sekarang untuk menambahkan.</small>
                <?php endif; ?>
                <input type="file" name="foto" class="form-control" accept="image/*">
                <small class="text-muted">Format: JPG, PNG, WEBP. Maks 2MB. Opsional.</small>
                <div class="mt-2">
                    <img id="previewFoto" src="" alt="Preview Foto" class="img-thumbnail" style="max-height:200px; display:none;">
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Status</label>
                <select name="status" class="form-select">
                    <option value="tersedia" <?= $kamar['status'] == 'tersedia' ? 'selected' : '' ?>>Tersedia</option>
                    <option value="terisi" <?= $kamar['status'] == 'terisi' ? 'selected' : '' ?>>Terisi</option>
                    <option value="perbaikan" <?= $kamar['status'] == 'perbaikan' ? 'selected' : '' ?>>Perbaikan</option>
                </select>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-warning">
                    <i class="bi bi-save me-1"></i> Update
                </button>
                <a href="/admin/kamar" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<script>
document.querySelector('input[name="foto"]').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('previewFoto');
    if (file) {
        const reader = new FileReader();
        reader.onload = function(ev) {
            preview.src = ev.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
    }
});
</script>

<?= $this->endSection() ?>