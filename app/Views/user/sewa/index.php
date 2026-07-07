<?= $this->extend('layouts/user') ?>
<?= $this->section('content') ?>

<style>
    .rincian-card {
        background: linear-gradient(135deg, #fff9e6 0%, #fff3cd 100%);
        border-left: 5px solid #ffc107;
        border-radius: 10px;
        padding: 18px;
        margin-bottom: 16px;
    }
    .alert-jatuh-tempo {
        background: linear-gradient(135deg, #f8d7da 0%, #f5c2c7 100%);
        border-left: 5px solid #dc3545;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 16px;
    }
    .konfirmasi-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-left: 5px solid #0d6efd;
    }
    .pertanyaan-item {
        background: white;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 12px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        transition: all 0.2s;
    }
    .pertanyaan-item:hover {
        box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    }
    .btn-check:checked + .btn-outline-success {
        background-color: #198754;
        border-color: #198754;
        color: white;
    }
    .btn-check:checked + .btn-outline-danger {
        background-color: #dc3545;
        border-color: #dc3545;
        color: white;
    }
</style>

<?php
$defaultDurasi = 1;
$kaliDep = $kaliDeposit ?? 2;
?>

<div class="card mb-4 border-0 shadow-sm">
    <div class="card-header fw-semibold bg-primary text-white py-3">
        <i class="bi bi-file-earmark-plus me-2"></i>Form Pengajuan Sewa Baru
    </div>
    <div class="card-body">
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <?php foreach (session()->getFlashdata('errors') as $e): ?>
                    <div><?= esc($e) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="/user/sewa/ajukan" method="post" id="formSewa">
            <?= csrf_field() ?>

            <!-- Pilih Kamar -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Pilih Kamar <span class="text-danger">*</span></label>
                <select name="id_kamar" id="idKamar" class="form-select" required onchange="updateInfoKamar()">
                    <option value="">-- Pilih Kamar Tersedia --</option>
                    <?php if (!empty($kamar)): ?>
                        <?php foreach ($kamar as $k):
                            $dep = $k['harga_sewa'] * $kaliDep;
                        ?>
                            <option value="<?= $k['id_kamar'] ?>"
                                    data-harga="<?= $k['harga_sewa'] ?>"
                                    data-deposit="<?= $dep ?>"
                                    data-kode="<?= esc($k['kode_kamar']) ?>"
                                    data-nomor="<?= esc($k['nomor_kamar']) ?>"
                                    data-fasilitas="<?= esc($k['fasilitas']) ?>"
                                    <?= (!empty($kamarDipilih) && $kamarDipilih['id_kamar'] == $k['id_kamar']) ? 'selected' : '' ?>>
                                No. <?= esc($k['nomor_kamar']) ?> (<?= esc($k['kode_kamar']) ?>) - Rp <?= number_format($k['harga_sewa'], 0, ',', '.') ?>/bln - Deposit: Rp <?= number_format($dep,0,',','.') ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <!-- Info Kamar Dipilih -->
            <div id="infoKamar" class="alert alert-light border mb-3" style="display:none;">
                <h6 class="fw-bold mb-2" id="infoJudul">Detail Kamar</h6>
                <div class="row small">
                    <div class="col-md-6 mb-2">
                        <strong>Fasilitas:</strong><br>
                        <span id="infoFasilitas">-</span>
                    </div>
                    <div class="col-md-6 mb-2">
                        <strong>Harga Sewa:</strong><br>
                        <span id="infoHarga" class="text-primary fw-bold">Rp 0/bln</span>
                    </div>
                </div>
            </div>

            <!-- Tanggal Mulai & Durasi -->
            <div class="row g-3">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Tanggal Mulai Huni <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_mulai" id="tglMulai" class="form-control" value="<?= esc(old('tanggal_mulai', date('Y-m-d')), 'attr') ?>" required onchange="hitungSelesai()">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Durasi Sewa (Bulan) <span class="text-danger">*</span></label>
                    <input type="number" name="durasi_bulan" id="durasiBulan" class="form-control"
                           value="<?= old('durasi_bulan', $defaultDurasi) ?>" min="1" required onchange="hitungSelesai()" placeholder="Masukkan jumlah bulan">
                    <small class="text-muted">Bebas masukkan berapa bulan sesuai kebutuhan Anda (minimum 1 bulan)</small>
                </div>
            </div>

            <!-- RINCIAN PEMBAYARAN AWAL -->
            <div class="rincian-card" id="rincianCard" style="display:none;">
                <h6 class="fw-bold mb-3 text-warning"><i class="bi bi-cash-coin me-1"></i>Rincian Pembayaran Awal</h6>
                <table class="table table-borderless mb-0">
                    <tr>
                        <td>Deposit (<?= $kaliDep ?>x harga sewa, dibayar sekali)</td>
                        <td class="text-end fw-bold text-primary">Rp <span id="rincDeposit">0</span></td>
                    </tr>
                    <tr>
                        <td>Sewa Bulan Ke-1</td>
                        <td class="text-end fw-bold">Rp <span id="rincBulan1">0</span></td>
                    </tr>
                    <tr style="border-top:2px solid #dee2e6;">
                        <td><strong>TOTAL WAJIB BAYAR AWAL</strong></td>
                        <td class="text-end"><strong class="fs-5 text-danger">Rp <span id="rincTotal">0</span></strong></td>
                    </tr>
                </table>
                <hr class="my-2">
                <small class="text-muted d-block">
                    <i class="bi bi-info-circle me-1"></i>
                    Setelah bulan ke-1, tagihan bulan ke-2 dst dibayar bulanan sesuai tanggal mulai huni.
                </small>
            </div>

            <!-- ALERT JATUH TEMPO DEPOSIT -->
            <div class="alert-jatuh-tempo" id="alertJatuhTempo" style="display:none;">
                <h6 class="fw-bold text-danger mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i>WAJIB BAYAR DEPOSIT + SEWA BULAN 1 DALAM 3 HARI</h6>
                <p class="mb-0 small">
                    Setelah pengajuan dikirim, Anda <strong>WAJIB BAYAR</strong> deposit + sewa bulan ke-1 dalam <strong>3 hari</strong>.
                    <br>
                    <span class="text-muted">Jatuh tempo deposit: <strong id="jatuhTempoDisplay">-</strong>. Jika tidak dibayar, pengajuan bisa ditolak admin.</span>
                </p>
            </div>

            <!-- Hasil Perhitungan Kontrak -->
            <div class="alert alert-info border-0 mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="d-block text-muted">Tanggal Selesai Kontrak:</small>
                        <strong id="tglSelesai">-</strong>
                    </div>
                    <div class="text-end">
                        <small class="d-block text-muted">Estimasi Total Sewa (<?= $kaliDep ?>x deposit + <?= '<span id="durasiText">1</span>' ?> bln sewa):</small>
                        <strong class="text-danger fs-5" id="totalBayarAwal">Rp 0</strong>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Keterangan (Opsional)</label>
                <textarea name="keterangan" class="form-control" rows="2" placeholder="Catatan tambahan untuk admin..."><?= esc(old('keterangan')) ?></textarea>
            </div>

            <!-- KOTAK KETENTUAN + KONFIRMASI -->
            <div class="card konfirmasi-card border-0 mb-3 mt-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-2 text-primary"><i class="bi bi-info-circle me-1"></i> Ketentuan Sewa Kos:</h6>
                    <ul class="small text-muted mb-3">
                        <li><strong>Deposit = <?= $kaliDep ?>x harga sewa</strong>, dibayar <strong>SEKALI di awal</strong> bersamaan dengan sewa bulan ke-1.</li>
                        <li><strong>Pembayaran awal (Deposit + Sewa Bulan 1) WAJIB DILUNASI dalam 3 hari</strong> setelah pengajuan dikirim. Jika tidak, pengajuan bisa <strong>DITOLAK</strong> admin.</li>
                        <li>Setelah bulan ke-1, tagihan bulan ke-2 dst dibayar <strong>bulanan</strong> dengan jatuh tempo <strong>tanggal <?= $batasTanggal ?? 5 ?> setiap bulan</strong> (sesuai pengaturan kos).</li>
                        <li><strong>Deposit akan DIKEMBALIKAN saat checkout</strong> (setelah dipotong kerusakan kamar jika ada).</li>
                        <li><strong class="text-danger">Jika checkout SEBELUM kontrak berakhir (early checkout), deposit akan DIPOTONG 50% secara otomatis.</strong></li>
                        <li><strong class="text-danger">Jika checkout ATAU pindah kamar di awal/pertengahan bulan, uang sewa untuk bulan tersebut TIDAK dikembalikan (hangus). Hanya deposit yang bisa dikembalikan (dengan potongan kerusakan/early checkout jika ada).</strong></li>
                        <li>Setelah pembayaran diverifikasi, admin akan <strong>menyetujui</strong> & kunci kamar siap diambil.</li>
                        <li>Kontrak dimulai dari tanggal mulai huni yang Anda pilih, berakhir sesuai durasi.</li>
                        <li>Anda bisa memperpanjang kontrak nanti lewat menu Perpanjangan.</li>
                    </ul>

                    
            <!-- KONFIRMASI: 11 PERTANYAAN (setuju semua = tombol aktif) -->
            <h6 class="fw-bold mb-3 text-danger"><i class="bi bi-exclamation-triangle me-1"></i> Konfirmasi Kepastian Sewa:</h6>
            <p class="small text-muted mb-4">Silakan jawab <strong>Iya</strong> pada <strong>SEMUA</strong> pertanyaan di bawah ini:</p>
                    <div class="pertanyaan-item">
                        <p class="fw-semibold mb-2">1. Saya yakin ingin menyewa kamar ini dan tidak akan membatalkan pengajuan.</p>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check konfirmasi" name="konf1" id="konf1_ya" value="ya" onchange="cekSemuaKonfirmasi()">
                            <label class="btn btn-outline-success" for="konf1_ya"><i class="bi bi-check-circle"></i> Iya</label>
                            <input type="radio" class="btn-check konfirmasi" name="konf1" id="konf1_tidak" value="tidak" onchange="cekSemuaKonfirmasi()">
                            <label class="btn btn-outline-danger" for="konf1_tidak"><i class="bi bi-x-circle"></i> Tidak</label>
                        </div>
                    </div>

                    <div class="pertanyaan-item">
                        <p class="fw-semibold mb-2">2. Saya mengerti bahwa saya WAJIB BAYAR Deposit (2x harga sewa) + Sewa Bulan Ke-1 sebagai pembayaran awal, dan WAJIB DILUNASI dalam 3 hari. Jika tidak, pengajuan bisa ditolak.</p>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check konfirmasi" name="konf2" id="konf2_ya" value="ya" onchange="cekSemuaKonfirmasi()">
                            <label class="btn btn-outline-success" for="konf2_ya"><i class="bi bi-check-circle"></i> Iya</label>
                            <input type="radio" class="btn-check konfirmasi" name="konf2" id="konf2_tidak" value="tidak" onchange="cekSemuaKonfirmasi()">
                            <label class="btn btn-outline-danger" for="konf2_tidak"><i class="bi bi-x-circle"></i> Tidak</label>
                        </div>
                    </div>

                    

                    <div class="pertanyaan-item">
                        <p class="fw-semibold mb-2">3. Saya mengerti bahwa Deposit dibayar sekali di awal & akan dikembalikan saat checkout (setelah dipotong kerusakan jika ada).</p>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check konfirmasi" name="konf3" id="konf3_ya" value="ya" onchange="cekSemuaKonfirmasi()">
                            <label class="btn btn-outline-success" for="konf3_ya"><i class="bi bi-check-circle"></i> Iya</label>
                            <input type="radio" class="btn-check konfirmasi" name="konf3" id="konf3_tidak" value="tidak" onchange="cekSemuaKonfirmasi()">
                            <label class="btn btn-outline-danger" for="konf3_tidak"><i class="bi bi-x-circle"></i> Tidak</label>
                        </div>
                    </div>

                    <div class="pertanyaan-item">
                        <p class="fw-semibold mb-2">4. Saya mengerti bahwa setelah bulan ke-1, tagihan bulan ke-2 dst dibayar bulanan dengan jatuh tempo tanggal 5 setiap bulan (sesuai pengaturan kos).</p>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check konfirmasi" name="konf4" id="konf4_ya" value="ya" onchange="cekSemuaKonfirmasi()">
                            <label class="btn btn-outline-success" for="konf4_ya"><i class="bi bi-check-circle"></i> Iya</label>
                            <input type="radio" class="btn-check konfirmasi" name="konf4" id="konf4_tidak" value="tidak" onchange="cekSemuaKonfirmasi()">
                            <label class="btn btn-outline-danger" for="konf4_tidak"><i class="bi bi-x-circle"></i> Tidak</label>
                        </div>
                    </div>

                    <div class="pertanyaan-item">
                        <p class="fw-semibold mb-2">5. Saya bersedia menunggu persetujuan admin setelah saya membayar deposit & upload bukti pembayaran.</p>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check konfirmasi" name="konf5" id="konf5_ya" value="ya" onchange="cekSemuaKonfirmasi()">
                            <label class="btn btn-outline-success" for="konf5_ya"><i class="bi bi-check-circle"></i> Iya</label>
                            <input type="radio" class="btn-check konfirmasi" name="konf5" id="konf5_tidak" value="tidak" onchange="cekSemuaKonfirmasi()">
                            <label class="btn btn-outline-danger" for="konf5_tidak"><i class="bi bi-x-circle"></i> Tidak</label>
                        </div>
                    </div>

                    <div class="pertanyaan-item">
                        <p class="fw-semibold mb-2">6. Saya mengerti bahwa kontrak dimulai dari tanggal mulai huni yang saya pilih, berakhir sesuai durasi, dan bisa diperpanjang lewat menu Perpanjangan sebelum kontrak berakhir.</p>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check konfirmasi" name="konf6" id="konf6_ya" value="ya" onchange="cekSemuaKonfirmasi()">
                            <label class="btn btn-outline-success" for="konf6_ya"><i class="bi bi-check-circle"></i> Iya</label>
                            <input type="radio" class="btn-check konfirmasi" name="konf6" id="konf6_tidak" value="tidak" onchange="cekSemuaKonfirmasi()">
                            <label class="btn btn-outline-danger" for="konf6_tidak"><i class="bi bi-x-circle"></i> Tidak</label>
                        </div>
                    </div>

                    <div class="pertanyaan-item">
                        <p class="fw-semibold mb-2">7. Saya bersedia mematuhi semua peraturan kos yang berlaku.</p>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check konfirmasi" name="konf7" id="konf7_ya" value="ya" onchange="cekSemuaKonfirmasi()">
                            <label class="btn btn-outline-success" for="konf7_ya"><i class="bi bi-check-circle"></i> Iya</label>
                            <input type="radio" class="btn-check konfirmasi" name="konf7" id="konf7_tidak" value="tidak" onchange="cekSemuaKonfirmasi()">
                            <label class="btn btn-outline-danger" for="konf7_tidak"><i class="bi bi-x-circle"></i> Tidak</label>
                        </div>
                    </div>

                    <div class="pertanyaan-item">
                        <p class="fw-semibold mb-2">8. Saya mengerti bahwa jika saya checkout SEBELUM kontrak berakhir (early checkout), deposit akan DIPOTONG 50% secara otomatis.</p>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check konfirmasi" name="konf8" id="konf8_ya" value="ya" onchange="cekSemuaKonfirmasi()">
                            <label class="btn btn-outline-success" for="konf8_ya"><i class="bi bi-check-circle"></i> Iya</label>
                            <input type="radio" class="btn-check konfirmasi" name="konf8" id="konf8_tidak" value="tidak" onchange="cekSemuaKonfirmasi()">
                            <label class="btn btn-outline-danger" for="konf8_tidak"><i class="bi bi-x-circle"></i> Tidak</label>
                        </div>
                    </div>

                    <div class="pertanyaan-item">
                        <p class="fw-semibold mb-2">9. <strong class="text-danger">Saya mengerti bahwa jika saya checkout atau pindah kamar di awal/pertengahan bulan, uang sewa untuk bulan tersebut TIDAK dikembalikan (hangus).</strong> Hanya deposit yang bisa dikembalikan (dengan potongan kerusakan/early checkout jika ada).</p>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check konfirmasi" name="konf9" id="konf9_ya" value="ya" onchange="cekSemuaKonfirmasi()">
                            <label class="btn btn-outline-success" for="konf9_ya"><i class="bi bi-check-circle"></i> Iya</label>
                            <input type="radio" class="btn-check konfirmasi" name="konf9" id="konf9_tidak" value="tidak" onchange="cekSemuaKonfirmasi()">
                            <label class="btn btn-outline-danger" for="konf9_tidak"><i class="bi bi-x-circle"></i> Tidak</label>
                        </div>
                    </div>

                    <div class="pertanyaan-item">
                        <p class="fw-semibold mb-2">10. Semua data yang saya isi pada form ini adalah benar dan dapat dipertanggungjawabkan.</p>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check konfirmasi" name="konf10" id="konf10_ya" value="ya" onchange="cekSemuaKonfirmasi()">
                            <label class="btn btn-outline-success" for="konf10_ya"><i class="bi bi-check-circle"></i> Iya</label>
                            <input type="radio" class="btn-check konfirmasi" name="konf10" id="konf10_tidak" value="tidak" onchange="cekSemuaKonfirmasi()">
                            <label class="btn btn-outline-danger" for="konf10_tidak"><i class="bi bi-x-circle"></i> Tidak</label>
                        </div>
                    </div>

            <button type="submit" class="btn btn-primary w-100 py-3 fw-bold fs-5" id="btnSewa">
                <i class="bi bi-send me-2"></i>Ajukan Sewa Sekarang
            </button>
        </form>
    </div>
</div>

<?php if (!empty($riwayat)): ?>
<div class="card border-0 shadow-sm">
    <div class="card-header fw-semibold py-3">
        <i class="bi bi-clock-history me-2"></i>Riwayat Pengajuan Sewa
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Kamar</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Mulai Huni</th>
                        <th>Durasi</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($riwayat as $r): ?>
                    <tr>
                        <td><strong>No. <?= esc($r['nomor_kamar']) ?></strong></td>
                        <td><small><?= esc($r['tanggal_pengajuan']) ?></small></td>
                        <td><small><?= esc($r['tanggal_mulai']) ?></small></td>
                        <td><?= esc($r['durasi_bulan']) ?> bln</td>
                        <td>
                            <?php
                            $badge = ['menunggu'=>'warning text-dark','aktif'=>'success','ditolak'=>'danger','selesai'=>'secondary'];
                            $b = $badge[$r['status']] ?? 'secondary';
                            ?>
                            <span class="badge bg-<?= $b ?>"><?= ucfirst($r['status']) ?></span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
const kaliDepositJs = <?= (int)$kaliDep ?>;

function formatRupiah(n) {
    return Number(n).toLocaleString('id-ID');
}

function updateInfoKamar() {
    const select = document.getElementById('idKamar');
    const option = select.options[select.selectedIndex];
    const infoBox = document.getElementById('infoKamar');

    if (option.value === "") {
        infoBox.style.display = 'none';
        document.getElementById('rincianCard').style.display = 'none';
        document.getElementById('alertJatuhTempo').style.display = 'none';
        document.getElementById('totalBayarAwal').textContent = 'Rp 0';
        document.getElementById('tglSelesai').textContent = '-';
        return;
    }

    const harga = parseInt(option.dataset.harga || 0);
    const deposit = parseInt(option.dataset.deposit || 0);

    document.getElementById('infoJudul').textContent = 'Kamar No. ' + option.dataset.nomor + ' (' + option.dataset.kode + ')';
    document.getElementById('infoFasilitas').textContent = option.dataset.fasilitas || '-';
    document.getElementById('infoHarga').textContent = 'Rp ' + formatRupiah(harga) + '/bln';

    infoBox.style.display = 'block';
    hitungSelesai();
}

function hitungSelesai() {
    const tglMulai = document.getElementById('tglMulai').value;
    const durasi = parseInt(document.getElementById('durasiBulan').value) || 0;
    const select = document.getElementById('idKamar');
    const option = select.options[select.selectedIndex];

    if (!option.value || !tglMulai || durasi <= 0) {
        document.getElementById('rincianCard').style.display = 'none';
        document.getElementById('alertJatuhTempo').style.display = 'none';
        document.getElementById('tglSelesai').textContent = '-';
        document.getElementById('totalBayarAwal').textContent = 'Rp 0';
        return;
    }

    const harga = parseInt(option.dataset.harga || 0);
    const deposit = parseInt(option.dataset.deposit || 0);

    // Hitung tanggal selesai kontrak
    const tgl = new Date(tglMulai);
    tgl.setMonth(tgl.getMonth() + durasi);
    const tglSelesai = tgl.toISOString().split('T')[0];
    document.getElementById('tglSelesai').textContent = tglSelesai;

    // Update durasi text
    const durasiTextEl = document.getElementById('durasiText');
    if (durasiTextEl) durasiTextEl.textContent = durasi;

    // Rincian pembayaran awal
    const sewaBulan1 = harga;
    const totalWajibBayar = deposit + sewaBulan1;

    document.getElementById('rincDeposit').textContent = formatRupiah(deposit);
    document.getElementById('rincBulan1').textContent = formatRupiah(sewaBulan1);
    document.getElementById('rincTotal').textContent = formatRupiah(totalWajibBayar);
    document.getElementById('rincianCard').style.display = '';

    // Hitung jatuh tempo (H+3 dari hari ini)
    const jatuhTempo = new Date();
    jatuhTempo.setDate(jatuhTempo.getDate() + 3);
    const jtStr = jatuhTempo.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
    document.getElementById('jatuhTempoDisplay').textContent = jtStr;
    document.getElementById('alertJatuhTempo').style.display = '';

    // Total bayar awal (deposit + total sewa semua bulan untuk info saja)
    const totalKeseluruhan = deposit + (harga * durasi);
    document.getElementById('totalBayarAwal').textContent = 'Rp ' + formatRupiah(totalKeseluruhan);
}

function cekSemuaKonfirmasi() {
    const radios = document.querySelectorAll('.konfirmasi');
    const btn = document.getElementById('btnSewa');

    let yaCount = 0;
    radios.forEach(radio => {
        if (radio.checked && radio.value === 'ya') {
            yaCount++;
        }
    });

    // Total pertanyaan: 11 (ditambah konf11 untuk early checkout)
    if (yaCount === 10) {
        btn.disabled = false;
        btn.classList.remove('btn-secondary');
        btn.classList.add('btn-primary');
    } else {
        btn.disabled = true;
        btn.classList.remove('btn-primary');
        btn.classList.add('btn-secondary');
    }
}

// Init saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('idKamar').value !== "") {
        updateInfoKamar();
    }
});
</script>

<?= $this->endSection() ?>
