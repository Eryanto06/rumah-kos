<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center py-3">
        <span class="fw-bold fs-5"><i class="bi bi-person-badge me-2 text-primary"></i>Data Admin</span>
        <a href="/admin/admin/tambah" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Tambah Admin
        </a>
    </div>
    <div class="card-body">
        <div class="alert alert-info border-0">
            <i class="bi bi-info-circle me-2"></i>
            Admin memiliki akses penuh ke seluruh sistem. Hanya admin lain yang bisa menambah/menghapus admin.
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Username</th>
                        <th>No. HP</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($admins)): $no=1; foreach ($admins as $a): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td>
                            <strong><?= esc($a['nama']) ?></strong>
                            <?php if ($a['id_user'] == session()->get('id_user')): ?>
                                <span class="badge bg-info ms-1">Anda</span>
                            <?php endif; ?>
                        </td>
                        <td><?= esc($a['email']) ?></td>
                        <td><?= esc($a['username']) ?></td>
                        <td><?= esc($a['no_hp']) ?></td>
                        <td>
                            <!-- TOMBOL EDIT -->
                            <a href="/admin/admin/edit/<?= $a['id_user'] ?>" class="btn btn-warning btn-sm" title="Edit Admin">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <?php if ($a['id_user'] != session()->get('id_user')): ?>
                            <form action="/admin/admin/hapus/<?= $a['id_user'] ?>" method="post" style="display:inline;">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-danger btn-sm"
                                   onclick="return confirm('Yakin hapus admin <?= esc($a['nama'], 'js') ?>? Tidak bisa dibatalkan.')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            <?php else: ?>
                            <span class="text-muted small">Akun Sendiri</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada admin</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>