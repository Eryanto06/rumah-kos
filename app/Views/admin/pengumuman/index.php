<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="row">
    <div class="col-md-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header fw-semibold bg-primary text-white py-3">
                <i class="bi bi-megaphone me-2"></i>Buat Pengumuman Baru
            </div>
            <div class="card-body">
                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="alert alert-danger">
                        <?php foreach (session()->getFlashdata('errors') as $e): ?><div><?= esc($e) ?></div><?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- TEMPLATE CEPAT -->
                <div class="mb-3">
                    <label class="form-label fw-semibold small text-muted"><i class="bi bi-lightning me-1"></i>Template Cepat (klik untuk pakai):</label>
                    <div class="d-flex flex-wrap gap-2">
                        <button type="button" class="btn btn-sm btn-outline-info" onclick="pakaiTemplate('mati_air')">💧 Mati Air</button>
                        <button type="button" class="btn btn-sm btn-outline-warning" onclick="pakaiTemplate('mati_listrik')">⚡ Mati Listrik</button>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="pakaiTemplate('wifi_down')">📶 Wi-Fi Down</button>
                        <button type="button" class="btn btn-sm btn-outline-info" onclick="pakaiTemplate('air_normal')">💧 Air Normal</button>
                        <button type="button" class="btn btn-sm btn-outline-warning" onclick="pakaiTemplate('listrik_normal')">⚡ Listrik Normal</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="pakaiTemplate('kerja_baik')">✅ Selesai</button>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="pakaiTemplate('darurat')">🚨 Darurat</button>
                        <button type="button" class="btn btn-sm btn-outline-success" onclick="pakaiTemplate('info_umum')">📢 Info Umum</button>
                    </div>
                </div>

                <form action="/admin/pengumuman/simpan" method="post" id="formPengumuman">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Judul Pengumuman</label>
                        <input type="text" name="judul" id="judulPengumuman" class="form-control" placeholder="Contoh: Pemadaman Listrik" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Isi Pengumuman</label>
                        <textarea name="isi" id="isiPengumuman" class="form-control" rows="4" placeholder="Tulis isi pengumuman..." required></textarea>
                    </div>

                    <!-- INPUT WAKTU MULAI & SELESAI -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold"><i class="bi bi-clock me-1"></i>Waktu Mulai</label>
                            <input type="datetime-local" name="waktu_mulai" id="waktuMulai" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold"><i class="bi bi-clock-fill me-1"></i>Waktu Selesai</label>
                            <input type="datetime-local" name="waktu_selesai" id="waktuSelesai" class="form-control">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Tanggal Tampil</label>
                            <input type="date" name="tanggal_mulai" id="tglMulai" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Tanggal Sembunyi (Opsional)</label>
                            <input type="date" name="tanggal_selesai" id="tglSelesai" class="form-control">
                        </div>
                    </div>

                    <!-- TARGET PENERIMA -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Target Penerima Notifikasi</label>
                        <select name="target" class="form-select" required>
                            <option value="penghuni_aktif">📢 Penghuni Aktif (Yang sudah sewa)</option>
                            <option value="pendaftar">👤 Pendaftar (Belum sewa kamar)</option>
                            <option value="semua" selected>👥 Semua User</option>
                        </select>
                        <small class="text-muted">Pilih siapa yang akan menerima notifikasi pengumuman ini.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status Tampil di Dashboard</label>
                        <select name="status" class="form-select">
                            <option value="aktif" selected>Aktif (Tampilkan)</option>
                            <option value="nonaktif">Nonaktif (Sembunyikan)</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                        <i class="bi bi-send me-1"></i>Publikasikan & Kirim Notifikasi
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- DAFTAR PENGUMUMAN (ESTETIK) -->
    <div class="col-md-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header fw-semibold py-3 d-flex justify-content-between align-items-center">
                <span><i class="bi bi-list me-2"></i>Daftar Pengumuman</span>
                <span class="badge bg-primary"><?= count($pengumuman) ?> Total</span>
            </div>
            <div class="card-body" style="max-height: 700px; overflow-y: auto;">
                <?php if (!empty($pengumuman)): ?>
                    <?php foreach ($pengumuman as $p): ?>
                    <div class="border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h6 class="mb-1 fw-bold d-flex align-items-center flex-wrap gap-1">
                                    <?= esc($p['judul']) ?>
                                    <?php if ($p['status'] == 'aktif'): ?>
                                        <span class="badge bg-success ms-1">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary ms-1">Nonaktif</span>
                                    <?php endif; ?>
                                    <?php
                                    // BADGE TARGET PENERIMA
                                    $targetBadge = [
                                        'penghuni_aktif' => ['primary', '📢 Penghuni'],
                                        'pendaftar'      => ['info text-dark', '👤 Pendaftar'],
                                        'semua'          => ['secondary', '👥 Semua'],
                                    ];
                                    $tb = $targetBadge[$p['target'] ?? 'semua'] ?? $targetBadge['semua'];
                                    ?>
                                    <span class="badge bg-<?= $tb[0] ?> ms-1"><?= $tb[1] ?></span>
                                </h6>
                                
                                <!-- ISI PENGUMUMAN (Rapi dengan border) -->
                                <div class="bg-light p-2 rounded mb-2" style="font-size: 0.9rem; color: #555; white-space: pre-wrap;"><?= esc($p['isi']) ?></div>
                                
                                <!-- JADWAL WAKTU (Highlight) -->
                                <?php if (!empty($p['waktu_mulai']) || !empty($p['waktu_selesai'])): ?>
                                <div class="alert alert-warning py-2 px-3 mb-2" style="font-size: 0.85rem;">
                                    <i class="bi bi-clock-history me-1"></i>
                                    <strong>Jadwal Pemadaman:</strong><br>
                                    Mulai: <strong><?= !empty($p['waktu_mulai']) ? date('d M Y H:i', strtotime($p['waktu_mulai'])) : '-' ?></strong> 
                                    | 
                                    Selesai: <strong><?= !empty($p['waktu_selesai']) ? date('d M Y H:i', strtotime($p['waktu_selesai'])) : '-' ?></strong>
                                </div>
                                <?php endif; ?>

                                <small class="text-muted d-block mt-1">
                                    <i class="bi bi-calendar3 me-1"></i>Tampil: <?= esc($p['tanggal_mulai']) ?>
                                    <?php if ($p['tanggal_selesai']): ?> s/d <?= esc($p['tanggal_selesai']) ?><?php endif; ?>
                                </small>
                            </div>
                            <div class="d-flex flex-column gap-1 ms-2">
                                <form action="/admin/pengumuman/toggle/<?= $p['id_pengumuman'] ?>" method="post" style="display:inline;">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-outline-warning" title="Aktif/Nonaktifkan">
                                        <i class="bi bi-toggle-on"></i>
                                    </button>
                                </form>
                                <form action="/admin/pengumuman/hapus/<?= $p['id_pengumuman'] ?>" method="post" style="display:inline;">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus"
                                        onclick="return confirm('Hapus pengumuman ini? Tidak bisa dibatalkan.')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-megaphone fs-1 d-block mb-2"></i>
                        <p>Belum ada pengumuman</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function pakaiTemplate(jenis) {
    // Ambil tanggal hari ini (YYYY-MM-DD)
    const today = new Date().toISOString().split('T')[0];
    
    // Ambil Waktu Sekarang (YYYY-MM-DDTHH:mm) untuk Waktu Mulai
    const now = new Date();
    now.setMinutes(now.getMinutes() - now.getTimezoneOffset()); // Sesuaikan timezone
    const currentDateTime = now.toISOString().slice(0, 16);
    
    const templates = {
        mati_air: {
            judul: 'Pengumuman Pemadaman Air',
            isi: 'Diberitahukan kepada seluruh penghuni kos bahwa akan ada pemadaman air sementara.\n\nMohon maaf atas ketidaknyamanannya. Pastikan Anda sudah menampung air untuk kebutuhan sejenak.\n\nTerima kasih atas pengertiannya.',
            selesai: 'T12:00'
        },
        mati_listrik: {
            judul: 'Pengumuman Pemadaman Listrik',
            isi: 'Diberitahukan kepada seluruh penghuni kos bahwa akan ada pemadaman listrik sementara.\n\nMohon siapkan senter/flashlight dan charge HP/powerbank sebelumnya.\n\nTerima kasih atas pengertiannya.',
            selesai: 'T15:00'
        },
        wifi_down: {
            judul: 'Pengumuman Gangguan Wi-Fi',
            isi: 'Diberitahukan kepada seluruh penghuni kos bahwa koneksi Wi-Fi sedang mengalami gangguan.\n\nTim teknisi sedang berusaha memperbaikinya. Estimasi normal kembali dalam 1-2 jam.\n\nMohon maaf atas ketidaknyamanannya.',
            selesai: 'T10:00'
        },
        air_normal: {
            judul: 'Pengumuman: Air Sudah Normal',
            isi: 'Diberitahukan kepada seluruh penghuni kos bahwa pasangan air SUDAH BERJALAN KEMBALI dan kembali normal.\n\nTerima kasih atas kesabaran dan pengertian Anda.\n\nSalam,\nAdmin Rumah Kos.',
            selesai: ''
        },
        listrik_normal: {
            judul: 'Pengumuman: Listrik Sudah Normal',
            isi: 'Diberitahukan kepada seluruh penghuni kos bahwa pasokan listrik SUDAH MENYALA KEMBALI dan kembali normal.\n\nTerima kasih atas kesabaran dan pengertian Anda.\n\nSalam,\nAdmin Rumah Kos.',
            selesai: ''
        },
        kerja_baik: {
            judul: 'Pengumuman: Gangguan Telah Selesai',
            isi: 'Diberitahukan kepada seluruh penghuni kos bahwa gangguan yang sebelumnya terjadi SUDAH SELESAI dan kembali normal.\n\nTerima kasih atas kesabaran dan pengertian Anda.\n\nSalam,\nAdmin Rumah Kos.',
            selesai: ''
        },
        darurat: {
            judul: 'PENGUMUMAN DARURAT',
            isi: 'PERHATIAN! Mohon perhatikan pengumuman darurat ini dengan seksama.\n\n[Tulis detail situasi darurat di sini]\n\nUntuk keselamatan bersama, mohon ikuti instruksi dari admin.',
            selesai: ''
        },
        info_umum: {
            judul: 'Pengumuman',
            isi: 'Diberitahukan kepada seluruh penghuni kos:\n\n[Tulis isi pengumuman di sini]\n\nTerima kasih atas perhatiannya.',
            selesai: ''
        }
    };

    const t = templates[jenis];
    if (!t) return;

    document.getElementById('judulPengumuman').value = t.judul;
    document.getElementById('isiPengumuman').value = t.isi;

    // SET WAKTU MULAI = JAM SEKARANG
    document.getElementById('waktuMulai').value = currentDateTime;

    // SET WAKTU SELESAI
    document.getElementById('waktuSelesai').value = t.selesai ? today + t.selesai : '';

    // SET TANGGAL TAMPIL & SEMBUNYI OTOMATIS
    document.getElementById('tglMulai').value = today;
    if (jenis === 'air_normal' || jenis === 'listrik_normal' || jenis === 'kerja_baik') {
        document.getElementById('tglSelesai').value = today;
    } else {
        document.getElementById('tglSelesai').value = '';
    }

    document.getElementById('formPengumuman').scrollIntoView({ behavior: 'smooth', block: 'start' });
    document.getElementById('judulPengumuman').focus();
}
</script>

<?= $this->endSection() ?>