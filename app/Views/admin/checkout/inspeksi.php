<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<style>
    .btn-template { font-size: 0.8rem; padding: 6px 12px; border-radius: 20px; transition: all 0.2s; }
    .btn-template:hover { transform: translateY(-1px); box-shadow: 0 3px 8px rgba(0,0,0,0.1); }
</style>

<div class="card border-0 shadow-sm" style="max-width:900px;margin:0 auto;">
    <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
        <span><i class="bi bi-clipboard-check me-2"></i>Inspeksi & Setujui Checkout</span>
        <a href="/admin/checkout" class="btn btn-sm btn-light"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
    <div class="card-body p-4">

        <!-- INFO PENGAJUAN -->
        <div class="card bg-light border-0 mb-4">
            <div class="card-body">
                <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-info-circle me-1"></i>Informasi Checkout</h6>
                <div class="row">
                    <div class="col-md-6">
                        <small class="text-muted d-block">Nama Penghuni</small>
                        <strong class="fs-6"><?= esc($p['nama'] ?? '-') ?></strong>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Kamar</small>
                        <strong>No. <?= esc($p['nomor_kamar'] ?? '-') ?> (<?= esc($p['kode_kamar'] ?? '-') ?>)</strong>
                    </div>
                    <div class="col-md-6 mt-2">
                        <small class="text-muted d-block">Tanggal Checkout Diajukan</small>
                        <strong><?= !empty($p['tanggal_checkout_diajukan']) ? esc(date('d M Y', strtotime($p['tanggal_checkout_diajukan']))) : '-' ?></strong>
                    </div>
                    <div class="col-md-6 mt-2">
                        <small class="text-muted d-block">Alasan Checkout</small>
                        <strong><?= esc($p['alasan'] ?? '-') ?></strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- ====== FIX BUG: REKENING USER untuk refund ====== -->
        <div class="card border-warning border-2 mb-4">
            <div class="card-header bg-warning text-dark fw-semibold py-2">
                <i class="bi bi-bank me-1"></i>Rekening Penghuni untuk Refund
            </div>
            <div class="card-body">
                <?php $hasRekening = !empty($p['nomor_rekening']) || !empty($p['ewallet_number']); ?>
                <?php if ($hasRekening): ?>
                    <div class="row">
                        <?php if (!empty($p['nomor_rekening'])): ?>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Bank</small>
                            <strong class="fs-6"><?= esc($p['nama_bank']) ?> - <?= esc($p['nomor_rekening']) ?></strong>
                            <small class="text-muted d-block">a.n. <?= esc($p['nama_pemilik_rek'] ?? '-') ?></small>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($p['ewallet_number'])): ?>
                        <div class="col-md-6">
                            <small class="text-muted d-block">E-Wallet</small>
                            <strong class="fs-6"><?= esc($p['ewallet_type']) ?> - <?= esc($p['ewallet_number']) ?></strong>
                        </div>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning mb-0">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                        <strong>User belum mengisi rekening di profil!</strong>
                        Hubungi user via WhatsApp <a href="<?= link_wa($p['no_hp'] ?? '', 'Halo ' . ($p['nama'] ?? '') . ', untuk proses refund checkout, mohon kirim no rekening / e-wallet Anda. Terima kasih.') ?>" target="_blank" class="alert-link">di sini</a>
                        sebelum upload bukti transfer. Tanpa rekening tujuan, refund tidak bisa diproses.
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- INFO HUNI & REFUND SEWA -->
        <div class="card border-info mb-4">
            <div class="card-header bg-info text-white fw-semibold py-2">
                <i class="bi bi-cash-coin me-1"></i>Informasi Huni & Pengembalian Sisa Sewa
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr>
                        <td>Tanggal Mulai Sewa</td>
                        <td class="text-end fw-bold"><?= !empty($p['tanggal_mulai']) ? esc(date('d M Y', strtotime($p['tanggal_mulai']))) : '-' ?></td>
                    </tr>
                    <tr>
                        <td>Total Durasi Kontrak</td>
                        <td class="text-end fw-bold"><?= $p['durasi_bulan'] ?> bulan</td>
                    </tr>
                    <tr>
                        <td>Lama Dihuni (sampai hari ini)</td>
                        <td class="text-end fw-bold text-primary"><?= $bulanDihuni ?> bulan</td>
                    </tr>
                    <tr>
                        <td>Sisa Bulan Belum Dihuni</td>
                        <td class="text-end fw-bold text-success"><?= $sisaBulan ?> bulan</td>
                    </tr>
                    <tr style="border-top:2px solid #dee2e6;">
                        <td><strong>REFUND SISA SEWA (dari tagihan lunas)</strong></td>
                        <td class="text-end"><strong class="fs-5 text-success">Rp <?= number_format($refundSewa,0,',','.') ?></strong></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- INFO DEPOSIT -->
        <div class="card border-warning mb-4">
            <div class="card-header bg-warning text-dark fw-semibold py-2">
                <i class="bi bi-shield-check me-1"></i>Informasi Deposit
            </div>
            <div class="card-body">
                <?php if (!empty($isEarlyCheckout)): ?>
                <!-- ALERT EARLY CHECKOUT -->
                <div class="alert alert-danger border-danger mb-3">
                    <h6 class="fw-bold text-danger mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i>EARLY CHECKOUT TERDETEKSI</h6>
                    <p class="small mb-1">
                        Tanggal checkout diajukan <strong><?= !empty($p['tanggal_checkout_diajukan']) ? esc(date('d M Y', strtotime($p['tanggal_checkout_diajukan']))) : '-' ?></strong>
                        lebih awal dari tanggal berakhir kontrak <strong><?= !empty($tanggalSelesaiKontrak) ? esc(date('d M Y', strtotime($tanggalSelesaiKontrak))) : '-' ?></strong>.
                    </p>
                    <p class="small mb-0">
                        Sesuai kebijakan, deposit akan <strong>dipotong 50% secara otomatis</strong>:
                        <span class="badge bg-danger ms-1">-Rp <?= number_format($potonganEarlyCheckout, 0, ',', '.') ?></span>
                    </p>
                </div>
                <?php else: ?>
                <div class="alert alert-success border-success mb-3 py-2">
                    <small class="mb-0"><i class="bi bi-check-circle me-1"></i>Checkout sesuai/waktu kontrak berakhir. <strong>Tidak ada potongan 50% early checkout.</strong></small>
                </div>
                <?php endif; ?>

                <table class="table table-borderless mb-0">
                    <tr>
                        <td>Total Deposit Disetor</td>
                        <td class="text-end fw-bold">Rp <?= number_format($p['deposit'] ?? 0, 0, ',', '.') ?></td>
                    </tr>
                    <?php if (!empty($isEarlyCheckout)): ?>
                    <tr>
                        <td>Potongan Early Checkout (50%)</td>
                        <td class="text-end fw-bold text-danger">-Rp <?= number_format($potonganEarlyCheckout, 0, ',', '.') ?></td>
                    </tr>
                    <tr style="border-top:2px solid #dee2e6;">
                        <td><strong>Sisa Deposit untuk Inspeksi Kerusakan</strong></td>
                        <td class="text-end"><strong class="text-primary">Rp <?= number_format($maxPotonganKerusakan, 0, ',', '.') ?></strong></td>
                    </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>

        <!-- FORM INSPEKSI -->
        <form action="/admin/checkout/setujui/<?= $p['id_checkout'] ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-pencil-square me-1"></i>Hasil Inspeksi Kamar</h6>

            <div class="mb-3">
                <label class="form-label fw-semibold">Catatan Inspeksi <span class="text-danger">*</span></label>
                
                <?php if (!isset($isReadOnly) || !$isReadOnly): ?>
                <!-- TOMBOL TEMPLATE CEPAT (hanya jika belum disetujui) -->
                <div class="mb-2">
                    <small class="text-muted d-block mb-1"><i class="bi bi-hand-index me-1"></i>Klik tombol untuk isi otomatis (bisa diedit):</small>
                    <div class="d-flex flex-wrap gap-1">
                        <button type="button" class="btn btn-sm btn-outline-success btn-template" onclick="isiCatatan('Kamar dalam kondisi bersih dan terawat. Tidak ada kerusakan. Kunci lengkap.')">
                            <i class="bi bi-check-circle"></i> Bersih & Baik
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-warning btn-template" onclick="isiCatatan('Kamar dalam kondisi cukup bersih. Terdapat goresan pada dinding. Kunci lengkap.')">
                            <i class="bi bi-exclamation-triangle"></i> Goresan Dinding
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger btn-template" onclick="isiCatatan('Kamar kotor dan tidak terawat. Terdapat kerusakan pada kaca jendela. Kunci lengkap.')">
                            <i class="bi bi-x-circle"></i> Kotor & Rusak
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary btn-template" onclick="isiCatatan('')">
                            <i class="bi bi-eraser"></i> Hapus
                        </button>
                    </div>
                </div>
                <?php endif; ?>

                <textarea name="catatan_inspeksi" id="textareaCatatan" class="form-control" rows="3" required 
                          <?= isset($isReadOnly) && $isReadOnly ? 'readonly' : '' ?> 
                          placeholder="Contoh: Kamar dalam kondisi bersih. Terdapat goresan di dinding."><?= esc(old('catatan_inspeksi', isset($p['keterangan_admin']) ? explode(' | ', $p['keterangan_admin'] ?? '')[0] : '')) ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Potongan Kerusakan (dari Sisa Deposit)</label>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="number" name="potongan_kerusakan" id="potongan" class="form-control"
                           value="<?= old('potongan_kerusakan', 0) ?>" min="0" max="<?= $maxPotonganKerusakan ?? ($p['deposit'] ?? 0) ?>"
                           oninput="hitungRefund()" placeholder="0"
                           <?= isset($isReadOnly) && $isReadOnly ? 'readonly' : '' ?>>
                </div>
                <small class="text-muted">
                    Isi 0 jika tidak ada kerusakan.
                    <?php if (!empty($isEarlyCheckout)): ?>
                        <strong>Maksimal Rp <?= number_format($maxPotonganKerusakan, 0, ',', '.') ?></strong> (sisa deposit setelah dipotong 50% early checkout).
                    <?php else: ?>
                        Maksimal Rp <?= number_format($p['deposit'] ?? 0, 0, ',', '.') ?> (sebesar deposit).
                    <?php endif; ?>
                </small>
            </div>

            <!-- HASIL PERHITUNGAN TOTAL REFUND -->
            <div class="card bg-light border-0 mb-4">
                <div class="card-body">
                    <h6 class="fw-bold mb-3 text-success"><i class="bi bi-calculator me-1"></i>Perhitungan Total Pengembalian Dana</h6>
                    <table class="table table-borderless mb-0">
                        <tr>
                            <td>Refund Sisa Sewa (<?= $sisaBulan ?> bulan)</td>
                            <td class="text-end text-success">Rp <?= number_format($refundSewa, 0, ',', '.') ?></td>
                        </tr>
                        <tr>
                            <td>Deposit Disetor</td>
                            <td class="text-end">Rp <?= number_format($p['deposit'] ?? 0, 0, ',', '.') ?></td>
                        </tr>
                        <?php if (!empty($isEarlyCheckout)): ?>
                        <tr>
                            <td>Potongan Early Checkout (50%)</td>
                            <td class="text-end text-danger">-Rp <?= number_format($potonganEarlyCheckout, 0, ',', '.') ?></td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                            <td>Potongan Kerusakan</td>
                            <td class="text-end text-danger" id="potonganDisplay">Rp 0</td>
                        </tr>
                        <tr style="border-top:2px solid #dee2e6;">
                            <td><strong>Refund Deposit</strong></td>
                            <td class="text-end"><strong class="text-primary" id="refundDeposit">Rp <?= number_format(($p['deposit'] ?? 0) - ($potonganEarlyCheckout ?? 0), 0, ',', '.') ?></strong></td>
                        </tr>
                        <tr class="table-success">
                            <td><strong class="fs-5">TOTAL DIKEMBALIKAN KE USER</strong></td>
                            <td class="text-end"><strong class="fs-4 text-success" id="totalRefund">Rp <?= number_format($refundSewa + ($p['deposit'] ?? 0) - ($potonganEarlyCheckout ?? 0), 0, ',', '.') ?></strong></td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- UPLOAD BUKTI REFUND -->
            <?php if (!isset($isReadOnly) || !$isReadOnly): ?>
            <div class="alert alert-warning border-warning mb-3">
                <h6 class="fw-bold text-warning mb-2"><i class="bi bi-upload me-1"></i>Upload Bukti Transfer Refund</h6>
                <p class="small mb-2">Wajib upload bukti transfer pengembalian dana ke user. File akan dikirim ke user via notifikasi & bisa didownload.</p>
                <input type="file" name="bukti_refund" class="form-control" accept="image/*,application/pdf" required>
                <small class="text-muted">Format: JPG, PNG, atau PDF. Maks 2MB.</small>
            </div>
            <?php else: ?>
                <?php if (!empty($p['bukti_refund'])): ?>
                <div class="alert alert-success border-success mb-3">
                    <h6 class="fw-bold text-success mb-2"><i class="bi bi-check-circle me-1"></i>Bukti Refund Sudah Diupload</h6>
                    <button type="button" onclick="bukaBukti('<?= esc($p['bukti_refund'], 'js') ?>')" class="btn btn-sm btn-outline-success" title="Lihat Bukti Refund">
                        <i class="bi bi-eye me-1"></i>Lihat Bukti Refund
                    </button>
                    <a href="/uploads/<?= esc($p['bukti_refund']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary ms-1" title="Download">
                        <i class="bi bi-download"></i>
                    </a>
                </div>
                <?php endif; ?>
            <?php endif; ?>

            <div class="alert alert-info py-2 small">
                <i class="bi bi-info-circle"></i> Setelah disetujui:
                <ul class="mb-0 mt-1">
                    <li>Sewa user → <strong>Selesai</strong>, kamar → <strong>Tersedia</strong></li>
                    <li>Kunci → <strong>Sudah Dikembalikan</strong></li>
                    <li>User dapat notifikasi dengan rincian pengembalian dana (sisa sewa + deposit)</li>
                </ul>
            </div>

            <div class="d-flex gap-2">
                <?php if (isset($isReadOnly) && $isReadOnly): ?>
                    <!-- Jika sudah disetujui, tampilkan tombol kembali saja -->
                    <div class="alert alert-success w-100 text-center mb-0">
                        <i class="bi bi-check-circle-fill me-1"></i> Checkout ini sudah disetujui. Form dalam mode read-only.
                    </div>
                <?php else: ?>
                    <!-- Jika belum disetujui, tampilkan tombol submit -->
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-1"></i>Setujui Checkout
                    </button>
                <?php endif; ?>
                
                <a href="/admin/checkout" class="btn btn-secondary"><i class="bi bi-x-circle me-1"></i>Kembali</a>
            </div>
        </form>
    </div>
</div>

<script>
const refundSewa = <?= $refundSewa ?>;
const deposit = <?= $p['deposit'] ?? 0 ?>;
const potonganEarly = <?= $potonganEarlyCheckout ?? 0 ?>;
const maxPotonganKerusakan = <?= $maxPotonganKerusakan ?? ($p['deposit'] ?? 0) ?>;

function hitungRefund() {
    let potongan = parseInt(document.getElementById('potongan').value) || 0;
    // Maksimal potongan kerusakan = sisa deposit setelah potongan early checkout
    if (potongan > maxPotonganKerusakan) {
        potongan = maxPotonganKerusakan;
        document.getElementById('potongan').value = potongan;
    }
    if (potongan < 0) potongan = 0;

    // Refund deposit = deposit - potonganEarlyCheckout - potonganKerusakan
    const refundDep = Math.max(deposit - potonganEarly - potongan, 0);
    const total = refundSewa + refundDep;

    document.getElementById('potonganDisplay').textContent = 'Rp ' + potongan.toLocaleString('id-ID');
    document.getElementById('refundDeposit').textContent = 'Rp ' + refundDep.toLocaleString('id-ID');
    document.getElementById('totalRefund').textContent = 'Rp ' + total.toLocaleString('id-ID');
}

function isiCatatan(teks) {
    const ta = document.getElementById('textareaCatatan');
    ta.value = teks;
    ta.focus();
    ta.style.backgroundColor = '#fff3cd';
    setTimeout(() => ta.style.backgroundColor = '', 500);
}

document.addEventListener('DOMContentLoaded', hitungRefund);
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