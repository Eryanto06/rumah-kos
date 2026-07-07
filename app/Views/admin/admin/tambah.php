<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="card border-0 shadow-sm" style="max-width:600px;margin:0 auto;">
    <div class="card-header bg-transparent fw-bold py-3">
        <i class="bi bi-person-plus me-2 text-primary"></i>Tambah Admin Baru
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

        <form action="/admin/admin/simpan" method="post">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" placeholder="John Doe" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Email</label>
                <input type="email" name="email" class="form-control" placeholder="email@contoh.com" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Username</label>
                <input type="text" name="username" class="form-control" placeholder="johndoe" required>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">No. HP</label>
                <input type="text" name="no_hp" class="form-control" placeholder="08123456789" required>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Min. 6 karakter" required>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i>Simpan Admin
            </button>
            <a href="/admin/admin" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

<?= $this->endSection() ?>