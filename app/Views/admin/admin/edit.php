<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="card border-0 shadow-sm" style="max-width:650px;margin:0 auto;">
    <div class="card-header bg-transparent fw-bold py-3">
        <i class="bi bi-pencil-square me-2 text-primary"></i>Edit Data Admin
    </div>
    <div class="card-body">
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle me-2"></i><strong>Terjadi kesalahan:</strong>
                <ul class="mb-0 mt-1">
                    <?php foreach (session()->getFlashdata('errors') as $e): ?>
                        <li><?= esc($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="/admin/admin/update/<?= $admin['id_user'] ?>" method="post">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" value="<?= esc($admin['nama']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Email</label>
                <input type="email" name="email" class="form-control" value="<?= esc($admin['email']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Username</label>
                <input type="text" name="username" class="form-control" value="<?= esc($admin['username']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">No. HP</label>
                <input type="text" name="no_hp" class="form-control" value="<?= esc($admin['no_hp']) ?>" required>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Password Baru (Opsional)</label>
                <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin ganti password">
                <small class="text-muted">Isi hanya jika ingin mengubah password. Min. 6 karakter.</small>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i>Simpan Perubahan
            </button>
            <a href="/admin/admin" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

<?= $this->endSection() ?>