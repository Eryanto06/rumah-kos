<?= $this->extend('layouts/user') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <ul class="mb-0">
            <?php foreach (session()->getFlashdata('errors') as $e): ?>
                <li><?= esc($e) ?></li>
            <?php endforeach; ?>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row">
    <!-- FORM KIRIM KELUHAN -->
    <div class="col-md-5">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent fw-semibold py-3">
                <i class="bi bi-megaphone me-2 text-danger"></i>Kirim Keluhan
            </div>
            <div class="card-body">
                <?php if ($is_penghuni): ?>
                    <div class="alert alert-success py-2 small">
                        <i class="bi bi-info-circle"></i> Anda terdaftar sebagai <strong>Penghuni Aktif</strong>. Pilih kategori keluhan sesuai kondisi kamar Anda.
                    </div>
                <?php else: ?>
                    <div class="alert alert-info py-2 small">
                        <i class="bi bi-info-circle"></i> Anda terdaftar sebagai <strong>Pendaftar</strong>. Silakan pilih kategori yang sesuai dengan kendala Anda.
                    </div>
                <?php endif; ?>

                <form action="/user/keluhan/kirim" method="post">
                    <?= csrf_field() ?>
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Judul Keluhan</label>
                        <input type="text" name="judul" class="form-control" required placeholder="Contoh: Kran air bocor">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kategori</label>
                        <select name="kategori" class="form-select" required>
                            <?php if ($is_penghuni): ?>
                                <option value="fasilitas_kamar">Fasilitas Kamar</option>
                                <option value="listrik_air">Listrik & Air</option>
                                <option value="wifi">Wi-Fi</option>
                                <option value="kebersihan">Kebersihan</option>
                                <option value="parkir">Parkir</option>
                                <option value="kebisingan">Kebisingan</option>
                                <option value="tetangga">Tetangga</option>
                                <option value="keamanan">Keamanan</option>
                                <option value="lainnya">Lainnya</option>
                            <?php else: ?>
                                <option value="kendala_akun">Kendala Akun</option>
                                <option value="website_bug">Website Bug</option>
                                <option value="status_sewa">Status Sewa</option>
                                <option value="info_kamar">Info Kamar</option>
                                <option value="tagihan_sewa">Tagihan Sewa</option>
                                <option value="lainnya">Lainnya</option>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Deskripsi Keluhan</label>
                        <textarea name="deskripsi" class="form-control" rows="4" required placeholder="Jelaskan keluhan Anda secara detail..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Prioritas</label>
                        <select name="prioritas" class="form-select">
                            <option value="rendah">Rendah (Tidak mendesak)</option>
                            <option value="normal" selected>Normal</option>
                            <option value="tinggi">Tinggi (Mendesak)</option>
                            <option value="urgent">Urgent (Darurat)</option>
                        </select>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="is_private" value="1" id="privateCheck">
                        <label class="form-check-label" for="privateCheck">
                            Kirim sebagai Anonim (Sembunyikan identitas saya)
                        </label>
                    </div>

                    <button type="submit" class="btn btn-danger w-100">
                        <i class="bi bi-send me-1"></i>Kirim Keluhan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- RIWAYAT KELUHAN -->
    <div class="col-md-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent fw-semibold py-3">
                <i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Keluhan
            </div>
            <div class="card-body p-0">
                <?php if (!empty($keluhan)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Judul</th>
                                    <th>Kategori</th>
                                    <th>Prioritas</th>
                                    <th>Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($keluhan as $k): ?>
                                <tr>
                                    <td><small><?= esc(date('d M Y', strtotime($k['tanggal']))) ?></small></td>
                                    <td>
                                        <strong><?= esc($k['judul']) ?></strong>
                                        <?php if (!empty($k['is_private'])): ?>
                                            <span class="badge bg-secondary ms-1"><i class="bi bi-incognito"></i> Anonim</span>
                                        <?php endif; ?>
                                        <br>
                                        <small class="text-muted"><?= esc(mb_strimwidth($k['deskripsi'], 0, 60, '...')) ?></small>
                                    </td>
                                    <td>
                                        <?php
                                        $katBadges = [
                                            'fasilitas_kamar' => 'primary','listrik_air' => 'warning text-dark','wifi' => 'info text-dark',
                                            'kebersihan' => 'success','parkir' => 'secondary','kebisingan' => 'danger',
                                            'tetangga' => 'warning text-dark','keamanan' => 'danger','lainnya' => 'secondary',
                                            'kendala_akun' => 'primary','website_bug' => 'dark','status_sewa' => 'info text-dark',
                                            'info_kamar' => 'success','tagihan_sewa' => 'warning text-dark',
                                        ];
                                        $katLabels = [
                                            'fasilitas_kamar' => 'Fasilitas Kamar','listrik_air' => 'Listrik & Air','wifi' => 'Wi-Fi',
                                            'kebersihan' => 'Kebersihan','parkir' => 'Parkir','kebisingan' => 'Kebisingan',
                                            'tetangga' => 'Tetangga','keamanan' => 'Keamanan','lainnya' => 'Lainnya',
                                            'kendala_akun' => 'Kendala Akun','website_bug' => 'Website Bug','status_sewa' => 'Status Sewa',
                                            'info_kamar' => 'Info Kamar','tagihan_sewa' => 'Tagihan Sewa',
                                        ];
                                        $kb = $katBadges[$k['kategori']] ?? 'secondary';
                                        $kl = $katLabels[$k['kategori']] ?? ucfirst(str_replace('_', ' ', $k['kategori']));
                                        ?>
                                        <span class="badge bg-<?= $kb ?>"><?= esc($kl) ?></span>
                                    </td>
                                    <td>
                                        <?php
                                        $pBadges = ['rendah'=>'secondary', 'normal'=>'info text-dark', 'tinggi'=>'warning text-dark', 'urgent'=>'danger'];
                                        $pLabels = ['rendah'=>'Rendah', 'normal'=>'Normal', 'tinggi'=>'Tinggi', 'urgent'=>'Urgent'];
                                        $pb = $pBadges[$k['prioritas']] ?? 'secondary';
                                        $pl = $pLabels[$k['prioritas']] ?? esc(ucfirst($k['prioritas'] ?? '-'));
                                        ?>
                                        <span class="badge bg-<?= $pb ?>"><?= esc($pl) ?></span>
                                    </td>
                                    <td>
                                        <?php 
                                        $badges = [
                                            'menunggu' => 'warning text-dark',
                                            'diproses' => 'info text-dark',
                                            'selesai'  => 'success'
                                        ];
                                        $b = $badges[$k['status']] ?? 'secondary';
                                        ?>
                                        <span class="badge bg-<?= $b ?>"><?= esc(ucfirst($k['status'])) ?></span>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="modal" data-bs-target="#keluhanModal<?= $k['id_keluhan'] ?>"
                                                title="Lihat Detail & Balasan Admin">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                        <p>Belum ada riwayat keluhan</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- MODAL DETAIL KELUHAN (untuk lihat balasan admin) -->
<?php if (!empty($keluhan)): ?>
<?php foreach ($keluhan as $k): ?>
<div class="modal fade" id="keluhanModal<?= $k['id_keluhan'] ?>" tabindex="-1" aria-labelledby="keluhanModalLabel<?= $k['id_keluhan'] ?>" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="keluhanModalLabel<?= $k['id_keluhan'] ?>">
                    <i class="bi bi-chat-dots me-1"></i>Detail Keluhan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-sm">
                    <tr>
                        <th width="30%">Judul</th>
                        <td><strong><?= esc($k['judul']) ?></strong></td>
                    </tr>
                    <tr>
                        <th>Tanggal</th>
                        <td><?= esc(date('d M Y', strtotime($k['tanggal']))) ?></td>
                    </tr>
                    <tr>
                        <th>Kategori</th>
                        <td>
                            <?php
                            $katBadges2 = [
                                'fasilitas_kamar' => 'primary','listrik_air' => 'warning text-dark','wifi' => 'info text-dark',
                                'kebersihan' => 'success','parkir' => 'secondary','kebisingan' => 'danger',
                                'tetangga' => 'warning text-dark','keamanan' => 'danger','lainnya' => 'secondary',
                                'kendala_akun' => 'primary','website_bug' => 'dark','status_sewa' => 'info text-dark',
                                'info_kamar' => 'success','tagihan_sewa' => 'warning text-dark',
                            ];
                            $katLabels2 = [
                                'fasilitas_kamar' => 'Fasilitas Kamar','listrik_air' => 'Listrik & Air','wifi' => 'Wi-Fi',
                                'kebersihan' => 'Kebersihan','parkir' => 'Parkir','kebisingan' => 'Kebisingan',
                                'tetangga' => 'Tetangga','keamanan' => 'Keamanan','lainnya' => 'Lainnya',
                                'kendala_akun' => 'Kendala Akun','website_bug' => 'Website Bug','status_sewa' => 'Status Sewa',
                                'info_kamar' => 'Info Kamar','tagihan_sewa' => 'Tagihan Sewa',
                            ];
                            $kb2 = $katBadges2[$k['kategori']] ?? 'secondary';
                            $kl2 = $katLabels2[$k['kategori']] ?? ucfirst(str_replace('_', ' ', $k['kategori']));
                            ?>
                            <span class="badge bg-<?= $kb2 ?>"><?= esc($kl2) ?></span>
                        </td>
                    </tr>
                    <tr>
                        <th>Prioritas</th>
                        <td>
                            <?php
                            $pBadges2 = ['rendah'=>'secondary', 'normal'=>'info text-dark', 'tinggi'=>'warning text-dark', 'urgent'=>'danger'];
                            $pLabels2 = ['rendah'=>'Rendah', 'normal'=>'Normal', 'tinggi'=>'Tinggi', 'urgent'=>'Urgent'];
                            $pb2 = $pBadges2[$k['prioritas']] ?? 'secondary';
                            $pl2 = $pLabels2[$k['prioritas']] ?? esc(ucfirst($k['prioritas'] ?? '-'));
                            ?>
                            <span class="badge bg-<?= $pb2 ?>"><?= esc($pl2) ?></span>
                        </td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <?php 
                            $badges2 = ['menunggu'=>'warning text-dark','diproses'=>'info text-dark','selesai'=>'success'];
                            $b2 = $badges2[$k['status']] ?? 'secondary';
                            ?>
                            <span class="badge bg-<?= $b2 ?>"><?= esc(ucfirst($k['status'])) ?></span>
                        </td>
                    </tr>
                    <tr>
                        <th>Privasi</th>
                        <td>
                            <?php if (!empty($k['is_private'])): ?>
                                <span class="badge bg-danger"><i class="bi bi-lock-fill"></i> Anonim</span>
                                <small class="text-muted">Identitas Anda disembunyikan dari admin</small>
                            <?php else: ?>
                                <span class="text-muted">Publik (identitas terlihat admin)</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Deskripsi</th>
                        <td><?= nl2br(esc($k['deskripsi'])) ?></td>
                    </tr>
                    <tr>
                        <th>Balasan Admin</th>
                        <td>
                            <?php if (!empty($k['balasan'])): ?>
                                <div class="alert alert-info mb-0 py-2">
                                    <i class="bi bi-reply me-1"></i><?= nl2br(esc($k['balasan'])) ?>
                                </div>
                            <?php else: ?>
                                <span class="text-muted"><i class="bi bi-clock me-1"></i>Belum ada balasan dari admin. Mohon tunggu.</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle me-1"></i>Tutup</button>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>
<?php endif; ?>

<?= $this->endSection() ?>
