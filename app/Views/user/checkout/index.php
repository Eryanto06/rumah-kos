<?= $this->extend('layouts/user') ?>
<?= $this->section('content') ?>

<style>
    .refund-card {
        background: linear-gradient(135deg, #d1e7dd 0%, #a3cfbb 100%);
        border-left: 5px solid #198754;
    }
    .konfirmasi-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-left: 5px solid #dc3545;
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

<?php if (!empty($riwayat)): ?>
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header fw-semibold py-3">
        <i class="bi bi-clock-history me-2"></i>Riwayat Pengajuan Checkout
    </div>
    <div class="card-body">
        <?php foreach ($riwayat as $r): ?>
        <div class="card border-0 shadow-sm mb-3 <?= $r['status'] == 'disetujui' ? 'refund-card' : '' ?>">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                    <div>
                        <h6 class="fw-bold mb-1">
                            Checkout Kamar No. <?= esc($r['nomor_kamar'] ?? '-') ?>
                            <?php if ($r['status'] == 'menunggu'): ?>
                                <span class="badge bg-warning text-dark">Menunggu</span>
                            <?php elseif ($r['status'] == 'inspeksi'): ?>
                                <span class="badge bg-info text-dark">Inspeksi</span>
                            <?php elseif ($r['status'] == 'disetujui'): ?>
                                <span class="badge bg-success">Disetujui</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Ditolak</span>
                            <?php endif; ?>
                        </h6>
                        <p class="text-muted small mb-1">
                            <i class="bi bi-calendar me-1"></i>Diajukan: <?= !empty($r['tanggal_checkout_diajukan']) ? esc(date('d M Y', strtotime($r['tanggal_checkout_diajukan']))) : '-' ?>
                        </p>
                        <p class="text-muted small mb-0">
                            <i class="bi bi-chat me-1"></i>Alasan: <?= esc($r['alasan']) ?>
                        </p>
                        <?php if (!empty($r['keterangan_admin'])): ?>
                        <p class="small mb-0 mt-1">
                            <i class="bi bi-info-circle me-1"></i>Catatan Admin: <?= esc($r['keterangan_admin']) ?>
                        </p>
                        <?php endif; ?>
                    </div>
                    
                    <!-- TOMBOL BUKTI REFUND (kalau disetujui) -->
                    <?php if ($r['status'] == 'disetujui' && !empty($r['bukti_refund'])): ?>
                    <div class="text-end">
                        <?php if (!empty($r['total_refund'])): ?>
                        <div class="mb-2">
                            <small class="text-muted d-block">Total Refund</small>
                            <strong class="fs-5 text-success">Rp <?= number_format($r['total_refund'],0,',','.') ?></strong>
                        </div>
                        <?php endif; ?>
                        <button type="button" onclick="bukaBukti('<?= esc($r['bukti_refund'], 'js') ?>')" class="btn btn-success btn-sm" title="Lihat Bukti Refund">
                            <i class="bi bi-eye me-1"></i>Lihat Bukti Refund
                        </button>
                        <a href="/uploads/<?= esc($r['bukti_refund']) ?>" target="_blank" class="btn btn-outline-success btn-sm ms-1" title="Download">
                            <i class="bi bi-download"></i>
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<?php if (!empty($sewaAktif) && empty($pengajuanMenunggu)): ?>
<!-- FORM AJUKAN CHECKOUT (Hanya untuk penghuni yang belum ajukan) -->
<div class="card border-0 shadow-sm" style="max-width:850px;margin:0 auto;">
    <div class="card-header bg-transparent fw-semibold py-3">
        <i class="bi bi-door-open me-2 text-danger"></i>Ajukan Check-Out
    </div>
    <div class="card-body">
        <div class="alert alert-info border-0 mb-3">
            <div class="d-flex justify-content-between">
                <div>
                    <small class="d-block text-muted">Kamar Aktif</small>
                    <strong class="fs-5">No. <?= esc($sewaAktif['nomor_kamar']) ?></strong>
                </div>
                <div class="text-end">
                    <small class="d-block text-muted">Kontrak Berakhir</small>
                    <strong class="fs-5"><?= !empty($sewaAktif['tanggal_selesai']) ? esc(date('d M Y', strtotime($sewaAktif['tanggal_selesai']))) : '-' ?></strong>
                </div>
            </div>
        </div>

        <form action="/user/checkout/ajukan" method="post" id="formCheckout">
            <?= csrf_field() ?>
            
            <div class="mb-3">
                <label class="form-label fw-semibold">Tanggal Checkout <span class="text-danger">*</span></label>
                <input type="date" name="tanggal_checkout" class="form-control" value="<?= date('Y-m-d') ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Alasan Checkout <span class="text-danger">*</span></label>
                <textarea name="alasan" class="form-control" rows="3" required placeholder="Contoh: Pindah kota, kerja di luar kota, dll."></textarea>
            </div>

            <!-- KOTAK KONFIRMASI ESTETIK -->
            <div class="card konfirmasi-card border-0 mb-3 mt-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-2 text-danger"><i class="bi bi-info-circle me-1"></i> Ketentuan Check-Out:</h6>
                    <ul class="small text-muted mb-3">
                        <li>Anda <strong>TIDAK PERLU melunasi semua tagihan</strong> untuk ajukan checkout.</li>
                        <li>Tagihan yang masih belum dibayar akan <strong>OTOMATIS DIBATALKAN</strong> saat checkout disetujui admin.</li>
                        <li><strong>Sisa sewa untuk bulan BELUM dihuni (bulan depan dst, yang sudah lunas) akan dikembalikan ke Anda. Tapi uang sewa untuk bulan berjalan TIDAK dikembalikan (hangus) jika checkout di awal/pertengahan bulan.</strong></li>
                        <li>Deposit akan dikembalikan setelah inspeksi kamar.</li>
                        <li><strong class="text-danger">Jika checkout SEBELUM kontrak berakhir (early checkout), deposit akan DIPOTONG 50% secara otomatis.</strong></li>
                        <li>Deposit bisa dipotong tambahan jika ada kerusakan kamar saat inspeksi.</li>
                        <li>Kunci kamar wajib dikembalikan ke admin setelah checkout disetujui.</li>
                        <li>Admin akan inspeksi kamar sebelum menyetujui checkout.</li>
                    </ul>
                    
                    
                <!-- KONFIRMASI: 11 PERTANYAAN -->
                <h6 class="fw-bold mb-3 text-danger"><i class="bi bi-exclamation-triangle me-1"></i> Konfirmasi Kepastian Check-Out:</h6>
                <p class="small text-muted mb-4">Silakan jawab <strong>Iya</strong> pada <strong>SEMUA</strong> pertanyaan di bawah ini:</p>
                    <div class="pertanyaan-item">
                        <p class="fw-semibold mb-2">1. Saya yakin ingin checkout dan tidak akan membatalkan pengajuan ini.</p>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check konfirmasi" name="konf1" id="konf1_ya" value="ya" onchange="cekSemuaKonfirmasi()">
                            <label class="btn btn-outline-success" for="konf1_ya"><i class="bi bi-check-circle"></i> Iya</label>
                            <input type="radio" class="btn-check konfirmasi" name="konf1" id="konf1_tidak" value="tidak" onchange="cekSemuaKonfirmasi()">
                            <label class="btn btn-outline-danger" for="konf1_tidak"><i class="bi bi-x-circle"></i> Tidak</label>
                        </div>
                    </div>

                    <div class="pertanyaan-item">
                        <p class="fw-semibold mb-2">2. Saya mengerti bahwa saya TIDAK PERLU melunasi semua tagihan untuk checkout, dan tagihan belum dibayar akan dibatalkan otomatis.</p>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check konfirmasi" name="konf2" id="konf2_ya" value="ya" onchange="cekSemuaKonfirmasi()">
                            <label class="btn btn-outline-success" for="konf2_ya"><i class="bi bi-check-circle"></i> Iya</label>
                            <input type="radio" class="btn-check konfirmasi" name="konf2" id="konf2_tidak" value="tidak" onchange="cekSemuaKonfirmasi()">
                            <label class="btn btn-outline-danger" for="konf2_tidak"><i class="bi bi-x-circle"></i> Tidak</label>
                        </div>
                    </div>

                    

                    <div class="pertanyaan-item">
                        <p class="fw-semibold mb-2">3. Saya mengerti bahwa deposit saya akan dikembalikan setelah inspeksi kamar oleh admin.</p>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check konfirmasi" name="konf3" id="konf3_ya" value="ya" onchange="cekSemuaKonfirmasi()">
                            <label class="btn btn-outline-success" for="konf3_ya"><i class="bi bi-check-circle"></i> Iya</label>
                            <input type="radio" class="btn-check konfirmasi" name="konf3" id="konf3_tidak" value="tidak" onchange="cekSemuaKonfirmasi()">
                            <label class="btn btn-outline-danger" for="konf3_tidak"><i class="bi bi-x-circle"></i> Tidak</label>
                        </div>
                    </div>

                    <div class="pertanyaan-item">
                        <p class="fw-semibold mb-2">4. Saya mengerti bahwa deposit saya bisa dipotong jika kamar ditemukan rusak/kotor saat inspeksi.</p>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check konfirmasi" name="konf4" id="konf4_ya" value="ya" onchange="cekSemuaKonfirmasi()">
                            <label class="btn btn-outline-success" for="konf4_ya"><i class="bi bi-check-circle"></i> Iya</label>
                            <input type="radio" class="btn-check konfirmasi" name="konf4" id="konf4_tidak" value="tidak" onchange="cekSemuaKonfirmasi()">
                            <label class="btn btn-outline-danger" for="konf4_tidak"><i class="bi bi-x-circle"></i> Tidak</label>
                        </div>
                    </div>

                    <div class="pertanyaan-item">
                        <p class="fw-semibold mb-2">5. Saya bersedia menunggu proses inspeksi dari admin sebelum pengajuan checkout disetujui.</p>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check konfirmasi" name="konf5" id="konf5_ya" value="ya" onchange="cekSemuaKonfirmasi()">
                            <label class="btn btn-outline-success" for="konf5_ya"><i class="bi bi-check-circle"></i> Iya</label>
                            <input type="radio" class="btn-check konfirmasi" name="konf5" id="konf5_tidak" value="tidak" onchange="cekSemuaKonfirmasi()">
                            <label class="btn btn-outline-danger" for="konf5_tidak"><i class="bi bi-x-circle"></i> Tidak</label>
                        </div>
                    </div>

                    <div class="pertanyaan-item">
                        <p class="fw-semibold mb-2">6. Saya mengerti bahwa kunci kamar wajib dikembalikan ke admin setelah checkout disetujui, dan saya sudah memastikan tidak ada barang pribadi tertinggal di kamar.</p>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check konfirmasi" name="konf6" id="konf6_ya" value="ya" onchange="cekSemuaKonfirmasi()">
                            <label class="btn btn-outline-success" for="konf6_ya"><i class="bi bi-check-circle"></i> Iya</label>
                            <input type="radio" class="btn-check konfirmasi" name="konf6" id="konf6_tidak" value="tidak" onchange="cekSemuaKonfirmasi()">
                            <label class="btn btn-outline-danger" for="konf6_tidak"><i class="bi bi-x-circle"></i> Tidak</label>
                        </div>
                    </div>

                    <div class="pertanyaan-item">
                        <p class="fw-semibold mb-2">7. Saya mengerti bahwa keputusan admin terkait potongan kerusakan adalah final dan tidak dapat diganggu gugat.</p>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check konfirmasi" name="konf7" id="konf7_ya" value="ya" onchange="cekSemuaKonfirmasi()">
                            <label class="btn btn-outline-success" for="konf7_ya"><i class="bi bi-check-circle"></i> Iya</label>
                            <input type="radio" class="btn-check konfirmasi" name="konf7" id="konf7_tidak" value="tidak" onchange="cekSemuaKonfirmasi()">
                            <label class="btn btn-outline-danger" for="konf7_tidak"><i class="bi bi-x-circle"></i> Tidak</label>
                        </div>
                    </div>

                    <div class="pertanyaan-item">
                        <p class="fw-semibold mb-2">8. <strong class="text-danger">PERNYATAAN PERSETUJUAN POTONGAN 50% DEPOSIT:</strong> Jika checkout saya sebelum tanggal berakhir kontrak, saya SETUJU deposit dipotong 50% otomatis sesuai kebijakan early checkout.</p>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check konfirmasi" name="konf8" id="konf8_ya" value="ya" onchange="cekSemuaKonfirmasi()">
                            <label class="btn btn-outline-success" for="konf8_ya"><i class="bi bi-check-circle"></i> Iya</label>
                            <input type="radio" class="btn-check konfirmasi" name="konf8" id="konf8_tidak" value="tidak" onchange="cekSemuaKonfirmasi()">
                            <label class="btn btn-outline-danger" for="konf8_tidak"><i class="bi bi-x-circle"></i> Tidak</label>
                        </div>
                    </div>

                    <div class="pertanyaan-item">
                        <p class="fw-semibold mb-2">9. <strong class="text-danger">Saya mengerti bahwa jika saya checkout di awal/pertengahan bulan, uang sewa untuk bulan tersebut TIDAK dikembalikan (hangus).</strong> Hanya deposit yang bisa dikembalikan (dengan potongan kerusakan & early checkout 50% jika ada).</p>
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

            <button type="submit" class="btn btn-danger w-100 py-3 fw-bold fs-5" id="btnCheckout">
                <i class="bi bi-door-open me-2"></i>Ajukan Check-Out
            </button>
        </form>
    </div>
</div>

<?php elseif (!empty($pengajuanMenunggu)): ?>
<!-- SEDANG DIPROSES (Penghuni yang sudah ajukan tapi belum disetujui admin) -->
<div class="alert alert-warning text-center py-4">
    <i class="bi bi-clock-history fs-1 d-block mb-2"></i>
    <h5 class="fw-bold">Pengajuan Checkout Sedang Diproses</h5>
    <p class="mb-0">Mohon tunggu admin inspeksi kamar & proses pengembalian dana.</p>
</div>

<?php else: ?>
<!-- TIDAK PUNYA SEWA AKTIF (Untuk Pendaftar yang belum sewa) -->
<div class="card border-0 shadow-sm text-center" style="max-width:600px;margin:0 auto;">
    <div class="card-body py-5">
        <i class="bi bi-house-slash fs-1 text-muted d-block mb-3"></i>
        <h4 class="fw-bold">Anda Belum Menyewa Kamar</h4>
        <p class="text-muted mb-4">Menu Check-Out hanya tersedia untuk penghuni yang memiliki sewa aktif. Silakan ajukan sewa kamar terlebih dahulu.</p>
        <a href="/user/sewa" class="btn btn-primary btn-lg">
            <i class="bi bi-door-open me-2"></i>Ajukan Sewa Kamar Sekarang
        </a>
    </div>
</div>
<?php endif; ?>

<script>
function cekSemuaKonfirmasi() {
    const radios = document.querySelectorAll('.konfirmasi');
    const btn = document.getElementById('btnCheckout');
    
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
        btn.classList.add('btn-danger');
    } else {
        btn.disabled = true;
        btn.classList.remove('btn-danger');
        btn.classList.add('btn-secondary');
    }
}
</script>

<!-- MODAL BUKTI (image + PDF) dengan tombol X close -->
<style>
.bukti-modal-overlay {
    display: none; position: fixed; z-index: 9999; left: 0; top: 0;
    width: 100%; height: 100%; background: rgba(0,0,0,0.92);
    justify-content: center; align-items: center; padding: 20px;
    backdrop-filter: blur(2px);
}
.bukti-modal-overlay.show { display: flex; }
.bukti-modal-box {
    max-width: 92%; max-height: 90vh; background: white;
    border-radius: 12px; padding: 20px; position: relative;
    box-shadow: 0 20px 60px rgba(0,0,0,0.5);
}
.bukti-modal-box img { max-width: 100%; max-height: 75vh; display: block; margin: 0 auto; border-radius: 8px; }
.bukti-modal-box iframe { max-width: 100%; width: 80vw; max-height: 75vh; height: 75vh; display: block; margin: 0 auto; border: none; border-radius: 8px; }
.bukti-modal-close-inner {
    position: absolute; top: 10px; right: 10px; background: #dc3545;
    color: white; border: none; width: 44px; height: 44px;
    border-radius: 50%; font-size: 1.3rem; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.3); z-index: 2;
    transition: all 0.2s;
}
.bukti-modal-close-inner:hover { background: #bb2d3b; transform: scale(1.1) rotate(90deg); }
.bukti-modal-close-outer {
    position: fixed; top: 20px; right: 20px; background: rgba(255,255,255,0.15);
    color: white; border: 2px solid rgba(255,255,255,0.4); width: 50px; height: 50px;
    border-radius: 50%; font-size: 1.5rem; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    backdrop-filter: blur(4px); transition: all 0.2s; z-index: 10000;
}
.bukti-modal-close-outer:hover { background: rgba(220,53,69,0.8); border-color: #dc3545; transform: scale(1.1); }
.bukti-modal-caption {
    text-align: center; margin-top: 12px; padding: 8px;
    background: #f8f9fa; border-radius: 6px; font-size: 0.85rem; color: #495057;
    word-break: break-all;
}
.bukti-modal-hint {
    text-align: center; margin-top: 8px; font-size: 0.75rem; color: #6c757d;
}
</style>

<div id="buktiModalOverlay" class="bukti-modal-overlay" onclick="if(event.target.id==='buktiModalOverlay')tutupBukti()">
    <button type="button" class="bukti-modal-close-outer" onclick="tutupBukti()" title="Tutup (ESC)">
        <i class="bi bi-x-lg"></i>
    </button>
    <div class="bukti-modal-box">
        <button type="button" class="bukti-modal-close-inner" onclick="tutupBukti()" title="Tutup">
            <i class="bi bi-x-lg"></i>
        </button>
        <div id="buktiModalContent"></div>
        <div id="buktiModalCaption" class="bukti-modal-caption"></div>
        <div class="bukti-modal-hint"><i class="bi bi-info-circle me-1"></i>Klik tombol X, klik area gelap di luar, atau tekan ESC untuk tutup</div>
    </div>
</div>

<script>
function bukaBukti(file) {
    const overlay = document.getElementById('buktiModalOverlay');
    const content = document.getElementById('buktiModalContent');
    const caption = document.getElementById('buktiModalCaption');
    const url = '/uploads/' + file;

    // Cek extension — kalau PDF, pakai iframe; kalau image, pakai <img>
    const ext = file.split('.').pop().toLowerCase();
    if (ext === 'pdf') {
        content.innerHTML = '<iframe src="' + url + '" title="Bukti PDF"></iframe>';
    } else {
        content.innerHTML = '<img src="' + url + '" alt="Bukti">';
    }
    caption.innerHTML = '<i class="bi bi-file-earmark me-1"></i><strong>File:</strong> ' + file;
    overlay.classList.add('show');
    document.body.style.overflow = 'hidden';
}
function tutupBukti() {
    const overlay = document.getElementById('buktiModalOverlay');
    overlay.classList.remove('show');
    document.body.style.overflow = '';
    // Clear content supaya video/audio (kalau ada) berhenti
    setTimeout(() => {
        document.getElementById('buktiModalContent').innerHTML = '';
    }, 200);
}
// ESC untuk tutup
document.addEventListener('keydown', e => { if (e.key === 'Escape') tutupBukti(); });
</script>

<?= $this->endSection() ?>
