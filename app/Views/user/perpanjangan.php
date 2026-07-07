<?= $this->extend('layouts/user') ?>
<?= $this->section('content') ?>

<style>
    .perpanjangan-card {
        max-width: 650px;
        margin: 0 auto;
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    .header-gradient {
        background: linear-gradient(135deg, #1a237e 0%, #00897b 100%);
        color: white;
        padding: 20px;
        text-align: center;
    }
    .header-gradient h5 { margin: 0; font-weight: 700; }
    .info-kontrak {
        background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
        border-radius: 10px;
        padding: 16px;
        margin-bottom: 20px;
    }
    .info-kontrak .label {
        font-size: 0.75rem;
        text-transform: uppercase;
        color: #6c757d;
        font-weight: 600;
        letter-spacing: 0.5px;
    }
    .info-kontrak .value {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1a237e;
    }
    .quick-btn {
        border: 2px solid #00897b;
        background: white;
        color: #00897b;
        padding: 10px 16px;
        border-radius: 25px;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.2s;
        cursor: pointer;
    }
    .quick-btn:hover {
        background: #00897b;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 137, 123, 0.3);
    }
    .quick-btn.active {
        background: #00897b;
        color: white;
    }
    .input-durasi {
        font-size: 1.5rem;
        font-weight: 700;
        text-align: center;
        border: 2px solid #00897b;
        border-radius: 10px;
        padding: 12px;
    }
    .input-durasi:focus {
        box-shadow: 0 0 0 0.2rem rgba(0, 137, 123, 0.25);
        border-color: #00897b;
    }
    .hasil-card {
        background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%);
        border-left: 4px solid #ff9800;
        border-radius: 8px;
        padding: 16px;
        margin-top: 16px;
    }
    .btn-perpanjang {
        background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
        color: white;
        border: none;
        padding: 14px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 1.1rem;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(255, 152, 0, 0.3);
    }
    .btn-perpanjang:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255, 152, 0, 0.4);
        color: white;
    }
</style>

<div class="card perpanjangan-card">
    <div class="header-gradient">
        <i class="bi bi-arrow-repeat fs-3 d-block mb-2"></i>
        <h5>Perpanjangan Kontrak Sewa</h5>
        <small class="opacity-75">Tambah durasi sewa kamar Anda</small>
    </div>

    <div class="card-body p-4">
        <?php if ($sewa): ?>
        <!-- INFO KONTRAK AKTIF -->
        <div class="info-kontrak">
            <div class="row text-center">
                <div class="col-md-6 border-end">
                    <div class="label">Kamar Aktif</div>
                    <div class="value">No. <?= esc($sewa['nomor_kamar']) ?></div>
                    <small class="text-muted"><?= esc($sewa['kode_kamar'] ?? '') ?></small>
                </div>
                <div class="col-md-6">
                    <div class="label">Kontrak Berakhir</div>
                    <div class="value"><?= !empty($sewa['tanggal_selesai']) ? esc(date('d M Y', strtotime($sewa['tanggal_selesai']))) : '-' ?></div>
                    <small class="text-muted">Durasi: <?= esc($sewa['durasi_bulan']) ?> bulan</small>
                </div>
            </div>
        </div>

        <?php if (!empty($tunggakan) && $tunggakan > 0): ?>
        <!-- PERINGATAN: ADA TUNGGAKAN -->
        <div class="alert alert-danger border-danger d-flex align-items-center mb-3">
            <i class="bi bi-exclamation-triangle-fill fs-4 me-3 text-danger"></i>
            <div class="flex-grow-1">
                <strong class="text-danger">Anda masih punya <?= $tunggakan ?> tagihan belum lunas!</strong><br>
                <small>Anda <strong>WAJIB melunasi semua tagihan sewa</strong> terlebih dahulu sebelum bisa mengajukan perpanjangan kontrak. Silakan bayar tagihan Anda di menu <a href="/user/pembayaran" class="alert-link">Pembayaran</a>.</small>
            </div>
            <a href="/user/pembayaran" class="btn btn-danger btn-sm ms-2">
                <i class="bi bi-cash-coin me-1"></i>Bayar Sekarang
            </a>
        </div>
        <?php else: ?>
        <div class="alert alert-success border-0 d-flex align-items-center mb-3 py-2">
            <i class="bi bi-check-circle-fill me-2 text-success"></i>
            <small><strong>Semua tagihan sudah lunas!</strong> Anda bisa mengajukan perpanjangan kontrak.</small>
        </div>
        <?php endif; ?>

        <form action="/user/perpanjangan/ajukan" method="post" id="formPerpanjangan">
            <?= csrf_field() ?>

            <!-- PILIHAN CEPAT -->
            <label class="form-label fw-bold mb-2"><i class="bi bi-lightning-fill text-warning"></i> Pilihan Cepat</label>
            <div class="d-flex flex-wrap gap-2 mb-3">
                <button type="button" class="quick-btn" onclick="setDurasi(3)">3 Bulan</button>
                <button type="button" class="quick-btn" onclick="setDurasi(6)">6 Bulan</button>
                <button type="button" class="quick-btn" onclick="setDurasi(12)">1 Tahun (12 Bln)</button>
                <button type="button" class="quick-btn" onclick="setDurasi(24)">2 Tahun (24 Bln)</button>
                <button type="button" class="quick-btn" onclick="setDurasi(36)">3 Tahun (36 Bln)</button>
            </div>

            <!-- INPUT DURASI BEBAS -->
            <div class="mb-3">
                <label class="form-label fw-bold mb-2"><i class="bi bi-calendar-plus text-primary"></i> Atau Masukkan Durasi Sendiri</label>
                <div class="input-group">
                    <button type="button" class="btn btn-outline-secondary" onclick="kurangDurasi()">
                        <i class="bi bi-dash-lg"></i>
                    </button>
                    <input type="number" name="durasi_bulan" id="durasiBulan" class="form-control input-durasi" 
                           value="<?= esc(old('durasi_bulan', 1)) ?>" min="1" required 
                           oninput="hitungSelesaiBaru()" placeholder="Masukkan jumlah bulan">
                    <button type="button" class="btn btn-outline-secondary" onclick="tambahDurasi()">
                        <i class="bi bi-plus-lg"></i>
                    </button>
                    <span class="input-group-text fw-bold">Bulan</span>
                </div>
                <small class="text-muted">Bebas masukkan berapa bulan sesuai kebutuhan Anda</small>
            </div>

            <!-- HASIL PERHITUNGAN -->
            <div class="hasil-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted d-block"><i class="bi bi-calendar-event me-1"></i>Tanggal Selesai Baru</small>
                        <strong class="fs-5 text-primary" id="tglSelesaiBaru">-</strong>
                    </div>
                    <div class="text-end">
                        <small class="text-muted d-block"><i class="bi bi-arrow-up-circle me-1"></i>Total Durasi Kontrak</small>
                        <strong class="fs-5 text-success" id="totalDurasi"><?= esc($sewa['durasi_bulan']) ?> bulan</strong>
                    </div>
                </div>
            </div>

            <button type="button" class="btn btn-perpanjang w-100 mt-4" id="btnPerpanjang" data-bs-toggle="modal" data-bs-target="#modalKonfirmasi">
                <i class="bi bi-check-circle me-1"></i>Perpanjang Sewa Sekarang
            </button>
        </form>

        <!-- MODAL KONFIRMASI -->
        <div class="modal fade" id="modalKonfirmasi" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title"><i class="bi bi-exclamation-triangle me-2"></i>Konfirmasi Perpanjangan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center">
                        <i class="bi bi-question-circle text-warning" style="font-size:3rem;"></i>
                        <h6 class="mt-3">Apakah Anda yakin ingin memperpanjang sewa?</h6>
                        <p class="text-muted small mb-0">Pastikan durasi yang Anda pilih sudah benar. Tagihan baru akan dibuat otomatis.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i>Tidak
                        </button>
                        <button type="button" class="btn btn-success" onclick="document.getElementById('formPerpanjangan').submit();">
                            <i class="bi bi-check-circle me-1"></i>Ya, Perpanjang
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <?php else: ?>
        <div class="alert alert-warning text-center py-4">
            <i class="bi bi-exclamation-triangle fs-1 d-block mb-2"></i>
            <h5 class="fw-bold">Anda tidak memiliki sewa aktif</h5>
            <p class="text-muted mb-3">Ajukan sewa kamar dulu sebelum bisa perpanjang kontrak.</p>
            <a href="/user/sewa" class="btn btn-primary">
                <i class="bi bi-door-open me-1"></i>Ajukan Sewa Dulu
            </a>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
const tglSelesaiLama = '<?= esc($sewa['tanggal_selesai'] ?? '') ?>';
const durasiLama = <?= (int)($sewa['durasi_bulan'] ?? 0) ?>;

function setDurasi(bulan) {
    document.getElementById('durasiBulan').value = bulan;
    hitungSelesaiBaru();
    document.querySelectorAll('.quick-btn').forEach(b => b.classList.remove('active'));
    event.target.classList.add('active');
}

function tambahDurasi() {
    const input = document.getElementById('durasiBulan');
    input.value = parseInt(input.value || 0) + 1;
    hitungSelesaiBaru();
}

function kurangDurasi() {
    const input = document.getElementById('durasiBulan');
    const val = parseInt(input.value || 1);
    if (val > 1) {
        input.value = val - 1;
        hitungSelesaiBaru();
    }
}

function hitungSelesaiBaru() {
    const durasi = parseInt(document.getElementById('durasiBulan').value) || 0;
    if (tglSelesaiLama && durasi > 0) {
        const tgl = new Date(tglSelesaiLama);
        tgl.setMonth(tgl.getMonth() + durasi);
        const bulan = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        const tglFormat = tgl.getDate() + ' ' + bulan[tgl.getMonth()] + ' ' + tgl.getFullYear();
        document.getElementById('tglSelesaiBaru').textContent = tglFormat;
        document.getElementById('totalDurasi').textContent = (durasiLama + durasi) + ' bulan';
    } else {
        document.getElementById('tglSelesaiBaru').textContent = '-';
        document.getElementById('totalDurasi').textContent = durasiLama + ' bulan';
    }
}
document.addEventListener('DOMContentLoaded', hitungSelesaiBaru);
</script>

<?= $this->endSection() ?>