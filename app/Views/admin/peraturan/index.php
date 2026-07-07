<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="row">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header fw-semibold bg-primary text-white">
                <i class="bi bi-shield-check me-2"></i>Tambah Peraturan Baru
            </div>
            <div class="card-body">
                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="alert alert-danger">
                        <?php foreach (session()->getFlashdata('errors') as $e): ?>
                            <div><?= esc($e) ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form action="/admin/peraturan/simpan" method="post">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Judul Peraturan</label>
                        <input type="text" name="judul" class="form-control" placeholder="Contoh: Jam Malam" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kategori</label>
                        <select name="kategori" class="form-select" required>
                            <option value="umum">📋 Umum</option>
                            <option value="jam_operasional">⏰ Jam Operasional</option>
                            <option value="fasilitas">🏠 Fasilitas</option>
                            <option value="keamanan">🔒 Keamanan</option>
                            <option value="pembayaran">💰 Pembayaran</option>
                            <option value="tamu">👥 Tamu</option>
                            <option value="larangan">🚫 Larangan</option>
                            <option value="lainnya">📌 Lainnya</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Isi Peraturan</label>
                        <textarea name="isi" class="form-control" rows="4" placeholder="Tulis isi peraturan detail..." required></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Urutan</label>
                            <input type="number" name="urutan" class="form-control" value="0" min="0">
                            <small class="text-muted">Angka kecil = tampil lebih atas</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select">
                                <option value="aktif" selected>Aktif</option>
                                <option value="nonaktif">Nonaktif</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>Simpan Peraturan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card">
            <div class="card-header fw-semibold">
                <i class="bi bi-list me-2"></i>Daftar Peraturan
            </div>
            <div class="card-body">
                <?php if (!empty($peraturan)): ?>
                    <?php
                    $kategoriLabels = [
                        'umum' => '📋 Umum',
                        'jam_operasional' => '⏰ Jam Operasional',
                        'fasilitas' => '🏠 Fasilitas',
                        'keamanan' => '🔒 Keamanan',
                        'pembayaran' => '💰 Pembayaran',
                        'tamu' => '👥 Tamu',
                        'larangan' => '🚫 Larangan',
                        'lainnya' => '📌 Lainnya',
                    ];
                    $kategoriBadges = [
                        'umum' => 'primary',
                        'jam_operasional' => 'info',
                        'fasilitas' => 'success',
                        'keamanan' => 'danger',
                        'pembayaran' => 'warning',
                        'tamu' => 'secondary',
                        'larangan' => 'danger',
                        'lainnya' => 'secondary',
                    ];
                    ?>
                    <?php foreach ($peraturan as $p): ?>
                        <div class="border-bottom pb-3 mb-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold">
                                        <?= esc($p['judul']) ?>
                                        <span class="badge bg-<?= $kategoriBadges[$p['kategori']] ?? 'secondary' ?> ms-1">
                                            <?= $kategoriLabels[$p['kategori']] ?? esc(ucfirst($p['kategori'])) ?>
                                        </span>
                                        <?php if ($p['status'] == 'aktif'): ?>
                                            <span class="badge bg-success ms-1">Aktif</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary ms-1">Nonaktif</span>
                                        <?php endif; ?>
                                    </h6>
                                    <p class="mb-1 text-muted small"><?= nl2br(esc($p['isi'])) ?></p>
                                    <small class="text-muted">
                                        <i class="bi bi-sort-numeric-down me-1"></i>Urutan: <?= $p['urutan'] ?>
                                        · <i class="bi bi-clock me-1"></i><?= esc($p['created_at']) ?>
                                    </small>
                                </div>
                                <div class="d-flex flex-column gap-1">
                                    <form action="/admin/peraturan/toggle/<?= $p['id_peraturan'] ?>" method="post" style="display:inline;">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-outline-warning" title="Aktifkan/Nonaktifkan">
                                            <i class="bi bi-toggle-on"></i>
                                        </button>
                                    </form>
                                    <form action="/admin/peraturan/hapus/<?= $p['id_peraturan'] ?>" method="post" style="display:inline;">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Hapus peraturan ini? Tidak bisa dibatalkan.')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-shield-x fs-1 d-block mb-2"></i>
                        <p>Belum ada peraturan. Tambahkan peraturan pertama Anda di form sebelah kiri.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>