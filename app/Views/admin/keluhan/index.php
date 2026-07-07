<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent fw-semibold d-flex justify-content-between align-items-center py-3">
        <span><i class="bi bi-chat-dots me-2 text-primary"></i>Data Keluhan</span>
        <?php if (!empty($filter_kategori) || !empty($filter_status) || !empty($filter_jenis)): ?>
            <a href="/admin/keluhan" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-x-circle me-1"></i>Reset Filter
            </a>
        <?php endif; ?>
    </div>
    <div class="card-body">

        <!-- TAB PEMISAH PENGHUNI VS PENDAFTAR -->
        <div class="btn-group w-100 mb-3" role="group">
            <a href="/admin/keluhan" class="btn <?= empty($filter_jenis) ? 'btn-primary' : 'btn-outline-primary' ?>">
                <i class="bi bi-people me-1"></i>Semua
                <span class="badge bg-light text-dark ms-1"><?= $total_semua ?></span>
            </a>
            <a href="/admin/keluhan?jenis=penghuni" class="btn <?= $filter_jenis==='penghuni' ? 'btn-success' : 'btn-outline-success' ?>">
                <i class="bi bi-house-door me-1"></i>Keluhan Penghuni
                <span class="badge bg-light text-dark ms-1"><?= $total_penghuni ?></span>
            </a>
            <a href="/admin/keluhan?jenis=pendaftar" class="btn <?= $filter_jenis==='pendaftar' ? 'btn-warning' : 'btn-outline-warning' ?>">
                <i class="bi bi-person-plus me-1"></i>Keluhan Pendaftar
                <span class="badge bg-light text-dark ms-1"><?= $total_pendaftar ?></span>
            </a>
        </div>

        <!-- Statistik Cepat (klik untuk filter status) -->
        <div class="row g-2 mb-3">
            <div class="col-6 col-md-3">
                <a href="/admin/keluhan<?= !empty($filter_jenis) ? '?jenis='.$filter_jenis : '' ?>" class="text-decoration-none">
                    <div class="card border-0 bg-light text-center py-2">
                        <div class="fs-5 fw-bold text-dark"><?= $total_semua ?></div>
                        <small class="text-muted">Semua</small>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="/admin/keluhan?status=menunggu<?= !empty($filter_jenis) ? '&jenis='.$filter_jenis : '' ?>" class="text-decoration-none">
                    <div class="card border-0 bg-warning bg-opacity-25 text-center py-2">
                        <div class="fs-5 fw-bold text-warning"><?= $total_menunggu ?></div>
                        <small class="text-muted">Menunggu</small>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="/admin/keluhan?status=diproses<?= !empty($filter_jenis) ? '&jenis='.$filter_jenis : '' ?>" class="text-decoration-none">
                    <div class="card border-0 bg-info bg-opacity-25 text-center py-2">
                        <div class="fs-5 fw-bold text-info"><?= $total_diproses ?></div>
                        <small class="text-muted">Diproses</small>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="/admin/keluhan?status=selesai<?= !empty($filter_jenis) ? '&jenis='.$filter_jenis : '' ?>" class="text-decoration-none">
                    <div class="card border-0 bg-success bg-opacity-25 text-center py-2">
                        <div class="fs-5 fw-bold text-success"><?= $total_selesai ?></div>
                        <small class="text-muted">Selesai</small>
                    </div>
                </a>
            </div>
        </div>

        <!-- Filter Kategori -->
        <div class="mb-3 d-flex gap-2 flex-wrap align-items-center">
            <span class="fw-semibold small text-muted me-1"><i class="bi bi-funnel me-1"></i>Kategori:</span>
            <a href="/admin/keluhan<?= !empty($filter_jenis) ? '?jenis='.$filter_jenis : '' ?>" class="btn btn-sm <?= empty($filter_kategori) ? 'btn-secondary' : 'btn-outline-secondary' ?>">Semua</a>
            <a href="/admin/keluhan?kategori=kebisingan<?= !empty($filter_jenis) ? '&jenis='.$filter_jenis : '' ?>" class="btn btn-sm <?= $filter_kategori==='kebisingan' ? 'btn-danger' : 'btn-outline-danger' ?>">🔊 Kebisingan</a>
            <a href="/admin/keluhan?kategori=tetangga<?= !empty($filter_jenis) ? '&jenis='.$filter_jenis : '' ?>" class="btn btn-sm <?= $filter_kategori==='tetangga' ? 'btn-warning' : 'btn-outline-warning' ?>">Tetangga</a>
            <a href="/admin/keluhan?kategori=fasilitas_kamar<?= !empty($filter_jenis) ? '&jenis='.$filter_jenis : '' ?>" class="btn btn-sm <?= $filter_kategori==='fasilitas_kamar' ? 'btn-primary' : 'btn-outline-primary' ?>">Fasilitas Kamar</a>
            <a href="/admin/keluhan?kategori=listrik_air<?= !empty($filter_jenis) ? '&jenis='.$filter_jenis : '' ?>" class="btn btn-sm <?= $filter_kategori==='listrik_air' ? 'btn-warning' : 'btn-outline-warning' ?>">Listrik & Air</a>
            <a href="/admin/keluhan?kategori=wifi<?= !empty($filter_jenis) ? '&jenis='.$filter_jenis : '' ?>" class="btn btn-sm <?= $filter_kategori==='wifi' ? 'btn-info' : 'btn-outline-info' ?>">Wi-Fi</a>
            <a href="/admin/keluhan?kategori=keamanan<?= !empty($filter_jenis) ? '&jenis='.$filter_jenis : '' ?>" class="btn btn-sm <?= $filter_kategori==='keamanan' ? 'btn-danger' : 'btn-outline-danger' ?>">Keamanan</a>
            <a href="/admin/keluhan?kategori=kebersihan<?= !empty($filter_jenis) ? '&jenis='.$filter_jenis : '' ?>" class="btn btn-sm <?= $filter_kategori==='kebersihan' ? 'btn-success' : 'btn-outline-success' ?>">Kebersihan</a>
            <a href="/admin/keluhan?kategori=parkir<?= !empty($filter_jenis) ? '&jenis='.$filter_jenis : '' ?>" class="btn btn-sm <?= $filter_kategori==='parkir' ? 'btn-secondary' : 'btn-outline-secondary' ?>">Parkir</a>
            <a href="/admin/keluhan?kategori=kendala_akun<?= !empty($filter_jenis) ? '&jenis='.$filter_jenis : '' ?>" class="btn btn-sm <?= $filter_kategori==='kendala_akun' ? 'btn-primary' : 'btn-outline-primary' ?>">🔑 Kendala Akun</a>
            <a href="/admin/keluhan?kategori=website_bug<?= !empty($filter_jenis) ? '&jenis='.$filter_jenis : '' ?>" class="btn btn-sm <?= $filter_kategori==='website_bug' ? 'btn-dark' : 'btn-outline-dark' ?>">🐛 Website Bug</a>
            <a href="/admin/keluhan?kategori=status_sewa<?= !empty($filter_jenis) ? '&jenis='.$filter_jenis : '' ?>" class="btn btn-sm <?= $filter_kategori==='status_sewa' ? 'btn-info' : 'btn-outline-info' ?>">⏳ Status Sewa</a>
            <a href="/admin/keluhan?kategori=info_kamar<?= !empty($filter_jenis) ? '&jenis='.$filter_jenis : '' ?>" class="btn btn-sm <?= $filter_kategori==='info_kamar' ? 'btn-success' : 'btn-outline-success' ?>">🏠 Info Kamar</a>
            <a href="/admin/keluhan?kategori=tagihan_sewa<?= !empty($filter_jenis) ? '&jenis='.$filter_jenis : '' ?>" class="btn btn-sm <?= $filter_kategori==='tagihan_sewa' ? 'btn-warning' : 'btn-outline-warning' ?>">💰 Tagihan</a>
            <a href="/admin/keluhan?kategori=lainnya<?= !empty($filter_jenis) ? '&jenis='.$filter_jenis : '' ?>" class="btn btn-sm <?= $filter_kategori==='lainnya' ? 'btn-secondary' : 'btn-outline-secondary' ?>">Lainnya</a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th width="50">No</th>
                        <th>Tanggal</th>
                        <th>Pelapor</th>
                        <th>Status Pelapor</th>
                        <th>Kategori</th>
                        <th>Prioritas</th>
                        <th>Judul</th>
                        <th>Private?</th>
                        <th>Status</th>
                        <th width="80">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($keluhan)): ?>
                        <?php $no=1; foreach ($keluhan as $k): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><small><?= esc((string)($k['tanggal'] ?? '')) ?></small></td>
                            <td>
                                <?php if (!empty($k['is_private'])): ?>
                                    <span class="text-danger">
                                        <i class="bi bi-incognito"></i> <strong>Anonim</strong>
                                    </span>
                                    <small class="text-muted d-block">Private (rahasia)</small>
                                <?php else: ?>
                                    <strong><?= esc($k['nama_user'] ?? '-') ?></strong>
                                    <?php if (!empty($k['no_hp'])): ?>
                                        <small class="text-muted d-block">
                                            <a href="<?= link_wa($k['no_hp']) ?>" target="_blank" class="text-decoration-none">
                                                <i class="bi bi-whatsapp text-success"></i> <?= esc($k['no_hp']) ?>
                                            </a>
                                        </small>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($k['is_penghuni'])): ?>
                                    <span class="badge bg-success">
                                        <i class="bi bi-house-door"></i> Penghuni
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-person-plus"></i> Pendaftar
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                $badges = [
                                    'fasilitas_kamar' => 'primary','listrik_air' => 'warning','wifi' => 'info',
                                    'kebersihan' => 'success','parkir' => 'secondary','kebisingan' => 'danger',
                                    'tetangga' => 'warning','keamanan' => 'danger','lainnya' => 'secondary',
                                    'kendala_akun' => 'primary','website_bug' => 'dark','status_sewa' => 'info',
                                    'info_kamar' => 'success','tagihan_sewa' => 'warning',
                                ];
                                $labels = [
                                    'fasilitas_kamar' => 'Fasilitas Kamar','listrik_air' => 'Listrik & Air','wifi' => 'Wi-Fi',
                                    'kebersihan' => 'Kebersihan','parkir' => 'Parkir','kebisingan' => '🔊 Kebisingan',
                                    'tetangga' => 'Tetangga','keamanan' => 'Keamanan','lainnya' => 'Lainnya',
                                    'kendala_akun' => '🔑 Kendala Akun','website_bug' => '🐛 Website Bug','status_sewa' => '⏳ Status Sewa',
                                    'info_kamar' => '🏠 Info Kamar','tagihan_sewa' => '💰 Tagihan',
                                ];
                                $b = $badges[$k['kategori']] ?? 'secondary';
                                $l = $labels[$k['kategori']] ?? esc(ucfirst($k['kategori'] ?? '-'));
                                ?>
                                <span class="badge bg-<?= $b ?>"><?= $l ?></span>
                            </td>
                            <td>
                                <?php
                                $pBadges = ['rendah'=>'secondary', 'normal'=>'info text-dark', 'tinggi'=>'warning text-dark', 'urgent'=>'danger'];
                                $pLabels = ['rendah'=>'Rendah', 'normal'=>'Normal', 'tinggi'=>'Tinggi', 'urgent'=>'Urgent'];
                                ?>
                                <span class="badge bg-<?= $pBadges[$k['prioritas']] ?? 'secondary' ?>">
                                    <?= $pLabels[$k['prioritas']] ?? esc(ucfirst($k['prioritas'] ?? '-')) ?>
                                </span>
                            </td>
                            <td>
                                <strong><?= esc($k['judul']) ?></strong>
                                <small class="text-muted d-block text-truncate" style="max-width:250px;">
                                    <?= esc($k['deskripsi']) ?>
                                </small>
                            </td>
                            <td class="text-center">
                                <?php if (!empty($k['is_private'])): ?>
                                    <span class="badge bg-danger" title="Identitas pelapor dirahasiakan">
                                        <i class="bi bi-lock-fill"></i> Private
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted">Publik</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($k['status'] == 'menunggu'): ?>
                                    <span class="badge bg-warning text-dark">Menunggu</span>
                                <?php elseif ($k['status'] == 'diproses'): ?>
                                    <span class="badge bg-info">Diproses</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Selesai</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="/admin/keluhan/detail/<?= $k['id_keluhan'] ?>" class="btn btn-info btn-sm text-white" title="Lihat Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                <h5>Tidak ada keluhan</h5>
                                <p class="mb-0">
                                    <?php if (!empty($filter_kategori) || !empty($filter_status) || !empty($filter_jenis)): ?>
                                        Tidak ada keluhan dengan filter yang dipilih.
                                        <a href="/admin/keluhan" class="btn btn-sm btn-outline-primary ms-2">Reset Filter</a>
                                    <?php else: ?>
                                        Belum ada keluhan dari penghuni.
                                    <?php endif; ?>
                                </p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="alert alert-info mt-3">
    <i class="bi bi-info-circle me-2"></i>
    <strong>Informasi Sistem Keluhan:</strong>
    <ul class="mb-0">
        <li><strong>🏠 Penghuni:</strong> User yang sudah sewa kamar (aktif). Keluhan terkait: fasilitas, listrik/air, Wi-Fi, kebisingan, tetangga, keamanan, dll.</li>
        <li><strong>👤 Pendaftar:</strong> User yang sudah daftar akun tapi belum sewa. Keluhan terkait: kendala akun, status sewa, info kamar, tagihan, website bug.</li>
        <li><strong>🔊 Kebisingan:</strong> Khusus keluhan suara berisik. Biasanya private.</li>
        <li><strong>Private:</strong> Identitas pelapor dirahasiakan. Hanya admin yang tahu via database.</li>
        <li><strong>Filter:</strong> Klik tab di atas (Semua/Penghuni/Pendaftar) atau kartu statistik untuk menyaring data.</li>
    </ul>
</div>

<?= $this->endSection() ?>