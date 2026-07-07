<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="card" style="max-width:800px;margin:0 auto;">
    <div class="card-header fw-semibold d-flex justify-content-between align-items-center">
        <span><i class="bi bi-chat-left-text me-2"></i>Detail Keluhan</span>
        <?php if (!empty($keluhan['is_private'])): ?>
            <span class="badge bg-danger"><i class="bi bi-lock"></i> Private</span>
        <?php else: ?>
            <span class="badge bg-success">Publik</span>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <?php
        $badges = [
            'fasilitas_kamar' => 'primary', 'listrik_air' => 'warning', 'wifi' => 'info',
            'kebersihan' => 'success', 'parkir' => 'secondary', 'kebisingan' => 'danger',
            'tetangga' => 'warning', 'keamanan' => 'danger', 'lainnya' => 'secondary',
            'kendala_akun' => 'primary', 'website_bug' => 'dark', 'status_sewa' => 'info',
            'info_kamar' => 'success', 'tagihan_sewa' => 'warning',
        ];
        $labels = [
            'fasilitas_kamar' => 'Fasilitas Kamar', 'listrik_air' => 'Listrik & Air', 'wifi' => 'Wi-Fi',
            'kebersihan' => 'Kebersihan', 'parkir' => 'Parkir', 'kebisingan' => '🔊 Kebisingan',
            'tetangga' => 'Tetangga', 'keamanan' => 'Keamanan', 'lainnya' => 'Lainnya',
            'kendala_akun' => 'Kendala Akun', 'website_bug' => 'Website Bug', 'status_sewa' => 'Status Sewa',
            'info_kamar' => 'Info Kamar', 'tagihan_sewa' => 'Tagihan/Deposit',
        ];
        $b = $badges[$keluhan['kategori']] ?? 'secondary';
        $l = $labels[$keluhan['kategori']] ?? esc(ucfirst($keluhan['kategori'] ?? '-'));

        $pBadges = ['rendah'=>'secondary', 'normal'=>'info text-dark', 'tinggi'=>'warning text-dark', 'urgent'=>'danger'];
        $pLabels = ['rendah'=>'Rendah', 'normal'=>'Normal', 'tinggi'=>'Tinggi', 'urgent'=>'Urgent'];
        $pb = $pBadges[$keluhan['prioritas']] ?? 'secondary';
        $pl = $pLabels[$keluhan['prioritas']] ?? esc(ucfirst($keluhan['prioritas'] ?? '-'));
        ?>
        <table class="table table-bordered mb-3">
            <tr><th width="30%">Judul</th><td><strong><?= esc($keluhan['judul']) ?></strong></td></tr>
            <tr><th>Kategori</th><td><span class="badge bg-<?= $b ?>"><?= $l ?></span></td></tr>
            <tr><th>Prioritas</th><td><span class="badge bg-<?= $pb ?>"><?= $pl ?></span></td></tr>
            <tr>
                <th>Pelapor</th>
                <td>
                    <?php if (!empty($keluhan['is_private'])): ?>
                        <span class="text-danger"><i class="bi bi-incognito"></i> <strong>Anonim (Private)</strong></span><br>
                        <small class="text-muted">Identitas pelapor disembunyikan sesuai permintaan. Tidak ada data user yang tersimpan pada keluhan ini.</small>
                    <?php else: ?>
                            <strong><?= esc($keluhan['nama_user'] ?? '-') ?></strong><br>
                            <small class="text-muted d-block mb-1">
                                <i class="bi bi-envelope"></i> <?= esc($keluhan['email'] ?? '-') ?>
                            </small>
                            <small class="text-muted d-block mb-2">
                                <i class="bi bi-whatsapp"></i> <?= esc($keluhan['no_hp'] ?? '-') ?>
                            </small>
                            <?php if (!empty($keluhan['no_hp'])): ?>
                            <a href="<?= link_wa($keluhan['no_hp'], 'Halo ' . ($keluhan['nama_user'] ?? '') . ', saya admin Rumah Kos. Membalas keluhan Anda: ' . ($keluhan['judul'] ?? '')) ?>" 
                            target="_blank" 
                            class="btn btn-success btn-sm">
                                <i class="bi bi-whatsapp me-1"></i>Chat WhatsApp
                            </a>
                            <?php endif; ?>
                        <?php endif; ?>
                </td>
            </tr>
            <tr><th>Tanggal</th><td><?= esc($keluhan['tanggal']) ?></td></tr>
            <tr><th>Deskripsi</th><td><?= nl2br(esc($keluhan['deskripsi'])) ?></td></tr>
            <tr><th>Status</th><td>
                <?php if ($keluhan['status'] == 'menunggu'): ?>
                    <span class="badge bg-warning text-dark">Menunggu</span>
                <?php elseif ($keluhan['status'] == 'diproses'): ?>
                    <span class="badge bg-info">Diproses</span>
                <?php else: ?>
                    <span class="badge bg-success">Selesai</span>
                <?php endif; ?>
            </td></tr>
            <?php if (!empty($keluhan['balasan'])): ?>
            <tr><th>Balasan Sebelumnya</th><td><?= nl2br(esc($keluhan['balasan'])) ?></td></tr>
            <?php endif; ?>
        </table>

        <hr>
        <h6 class="fw-bold mb-3"><i class="bi bi-reply me-2"></i>Tanggapi Keluhan</h6>

        <!-- TEMPLATE BALASAN CEPAT (OPSI B) -->
        <div class="mb-3">
            <label class="form-label fw-semibold small text-muted"><i class="bi bi-lightning me-1"></i>Template Balasan Cepat (klik untuk pakai):</label>
            <div class="d-flex flex-wrap gap-2 mb-2">
                <button type="button" class="btn btn-sm btn-outline-info" onclick="pakaiTemplate('Sudah ditangani teknisi. Silakan cek kembali. Terima kasih.')">
                    ✅ Sudah Ditangani
                </button>
                <button type="button" class="btn btn-sm btn-outline-warning" onclick="pakaiTemplate('Sedang dalam proses penanganan. Mohon ditunggu 1x24 jam.')">
                    ⏳ Sedang Diproses
                </button>
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="pakaiTemplate('Mohon info tambahan: kapan terjadi & lokasi persisnya?')">
                    ❓ Butuh Info
                </button>
                <button type="button" class="btn btn-sm btn-outline-success" onclick="pakaiTemplate('Sudah saya tegur pihak terkait. Jika berulang, hubungi saya lagi. Terima kasih.')">
                    👤 Sudah Tegur
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="pakaiTemplate('Mohon maaf atas ketidaknyamanan. Akan segera kami perbaiki.')">
                    🙏 Mohon Maaf
                </button>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="pakaiTemplate('Mohon hubungi admin via WhatsApp 0812-XXXX-XXXX untuk penanganan cepat.')">
                    📞 Hubungi WA
                </button>
            </div>
        </div>

        <!-- FORM BALASAN MANUAL (bisa ketik sendiri) -->
        <form action="/admin/keluhan/update-status/<?= $keluhan['id_keluhan'] ?>" method="post">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label class="form-label fw-semibold">Update Status</label>
                <select name="status" class="form-select">
                    <option value="menunggu" <?= $keluhan['status']=='menunggu'?'selected':'' ?>>Menunggu</option>
                    <option value="diproses" <?= $keluhan['status']=='diproses'?'selected':'' ?>>Diproses</option>
                    <option value="selesai" <?= $keluhan['status']=='selesai'?'selected':'' ?>>Selesai</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Balasan untuk Penghuni</label>
                <textarea name="balasan" id="balasanText" class="form-control" rows="4" placeholder="Ketik balasan Anda di sini, atau klik template di atas untuk mengisi otomatis..."><?= esc($keluhan['balasan'] ?? '') ?></textarea>
                <small class="text-muted">Balasan ini akan dikirim sebagai notifikasi ke penghuni. Anda bisa edit template atau ketik balasan sendiri.</small>
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-send me-1"></i>Kirim & Update Status</button>
            <a href="/admin/keluhan" class="btn btn-secondary ms-2"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
        </form>
    </div>
</div>

<script>
function pakaiTemplate(pesan) {
    document.getElementById('balasanText').value = pesan;
    document.getElementById('balasanText').focus();
    // Scroll ke textarea
    document.getElementById('balasanText').scrollIntoView({ behavior: 'smooth', block: 'center' });
}
</script>

<?= $this->endSection() ?>