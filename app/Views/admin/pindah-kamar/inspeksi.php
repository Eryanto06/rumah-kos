<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<style>
    .btn-template { font-size: 0.8rem; padding: 6px 12px; border-radius: 20px; transition: all 0.2s; }
    .btn-template:hover { transform: translateY(-1px); box-shadow: 0 3px 8px rgba(0,0,0,0.1); }
</style>

<div class="card border-0 shadow-sm" style="max-width:900px;margin:0 auto;">
    <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
        <span><i class="bi bi-clipboard-check me-2"></i><?= esc($title) ?></span>
        <a href="/admin/pindah-kamar" class="btn btn-sm btn-light"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
    <div class="card-body p-4">

        <!-- INFO PENGAJUAN -->
        <div class="card bg-light border-0 mb-4">
            <div class="card-body">
                <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-info-circle me-1"></i>Informasi Pindah Kamar</h6>
                <div class="row">
                    <div class="col-md-6">
                        <small class="text-muted d-block">Penghuni</small>
                        <strong class="fs-6"><?= esc($p['nama_user'] ?? '-') ?></strong>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Kamar Lama -> Kamar Baru</small>
                        <strong>No. <?= esc($p['nomor_kamar_lama'] ?? '-') ?> -> No. <?= esc($p['nomor_kamar_baru'] ?? '-') ?></strong>
                    </div>
                    <div class="col-md-6 mt-2">
                        <small class="text-muted d-block">Harga Lama -> Harga Baru</small>
                        <strong>Rp <?= number_format($p['harga_lama'] ?? 0,0,',','.') ?> -> Rp <?= number_format($p['harga_baru'] ?? 0,0,',','.') ?></strong>
                    </div>
                    <div class="col-md-6 mt-2">
                        <small class="text-muted d-block">Sisa Durasi</small>
                        <strong><?= esc($p['durasi_bulan'] ?? '-') ?> bulan</strong>
                    </div>
                    <div class="col-md-6 mt-2">
                        <small class="text-muted d-block">Tanggal Pengajuan</small>
                        <strong><?= !empty($p['tanggal_pengajuan']) ? esc(date('d M Y', strtotime($p['tanggal_pengajuan']))) : '-' ?></strong>
                    </div>
                    <div class="col-md-6 mt-2">
                        <small class="text-muted d-block">Status</small>
                        <?php if ($p['status'] == 'menunggu'): ?>
                            <span class="badge bg-warning text-dark">Menunggu Persetujuan</span>
                        <?php elseif ($p['status'] == 'disetujui'): ?>
                            <span class="badge bg-success">Disetujui</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Ditolak</span>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-12 mt-2">
                        <small class="text-muted d-block">Alasan Pindah</small>
                        <strong><?= esc($p['alasan'] ?? '-') ?></strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- ====== FIX BUG: REKENING USER untuk refund selisih deposit ====== -->
        <div class="card border-warning border-2 mb-4">
            <div class="card-header bg-warning text-dark fw-semibold py-2">
                <i class="bi bi-bank me-1"></i>Rekening Penghuni (untuk Refund Selisih Deposit)
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
                        Hubungi user via WhatsApp <a href="<?= link_wa($p['no_hp'] ?? '', 'Halo ' . ($p['nama_user'] ?? '') . ', untuk proses refund selisih deposit pindah kamar, mohon kirim no rekening / e-wallet Anda. Terima kasih.') ?>" target="_blank" class="alert-link">di sini</a>
                        sebelum upload bukti transfer.
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- INFO DEPOSIT -->
        <div class="card border-warning mb-4">
            <div class="card-header bg-warning text-dark fw-semibold py-2">
                <i class="bi bi-cash-coin me-1"></i>Informasi Deposit
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr>
                        <td>Deposit Lama (kamar lama)</td>
                        <td class="text-end fw-bold text-primary">Rp <?= number_format($depositLama,0,',','.') ?></td>
                    </tr>
                    <tr>
                        <td>Deposit Baru (kamar baru)</td>
                        <td class="text-end fw-bold text-success">Rp <?= number_format($depositBaru,0,',','.') ?></td>
                    </tr>
                    <?php if ($selisihDeposit > 0): ?>
                    <tr class="table-warning">
                        <td><strong>Selisih yang harus dibayar user</strong></td>
                        <td class="text-end"><strong class="text-danger">Rp <?= number_format($selisihDeposit,0,',','.') ?></strong></td>
                    </tr>
                    <?php else: ?>
                    <tr class="table-success">
                        <td><strong>Uang kembalian ke user</strong></td>
                        <td class="text-end"><strong class="text-success">Rp <?= number_format(abs($selisihDeposit),0,',','.') ?></strong></td>
                    </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>

        <?php if (!empty($isReadOnly)): ?>
        <!-- ============ MODE READ-ONLY (status disetujui/ditolak) ============ -->

        <?php if (!empty($p['keterangan_admin'])): ?>
        <div class="card border-info mb-4">
            <div class="card-header bg-info text-white fw-semibold py-2">
                <i class="bi bi-chat-left-text me-1"></i>Catatan Admin / Hasil Proses
            </div>
            <div class="card-body">
                <p class="mb-0"><?= nl2br(esc($p['keterangan_admin'])) ?></p>
                <?php if (!empty($p['tanggal_proses'])): ?>
                <small class="text-muted d-block mt-2">
                    <i class="bi bi-calendar-check me-1"></i>Diproses pada: <?= esc(date('d M Y', strtotime($p['tanggal_proses']))) ?>
                </small>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($p['status'] == 'disetujui'): ?>
        <!-- INFO HASIL KEUANGAN (kalau disetujui) -->
        <div class="card border-success mb-4">
            <div class="card-header bg-success text-white fw-semibold py-2">
                <i class="bi bi-cash-stack me-1"></i>Hasil Keuangan
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr>
                        <td>Total Refund ke User</td>
                        <td class="text-end fw-bold text-success">Rp <?= number_format($p['total_refund'] ?? 0,0,',','.') ?></td>
                    </tr>
                    <tr>
                        <td>Tanggal Refund</td>
                        <td class="text-end fw-bold"><?= !empty($p['tanggal_refund']) ? esc(date('d M Y', strtotime($p['tanggal_refund']))) : '-' ?></td>
                    </tr>
                </table>

                <?php if (!empty($p['bukti_refund'])): ?>
                <hr>
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <small class="text-muted d-block">Bukti Refund</small>
                        <strong>Sudah diupload admin</strong>
                    </div>
                    <button type="button" onclick="bukaBukti('<?= esc($p['bukti_refund'], 'js') ?>')" class="btn btn-success btn-sm" title="Lihat Bukti Refund">
                        <i class="bi bi-eye me-1"></i>Lihat Bukti Refund
                    </button>
                    <a href="/uploads/<?= esc($p['bukti_refund']) ?>" target="_blank" class="btn btn-outline-success btn-sm ms-1" title="Download">
                        <i class="bi bi-download"></i>
                    </a>
                </div>
                <?php else: ?>
                <hr>
                <div class="alert alert-warning mb-0 small">
                    <i class="bi bi-exclamation-triangle me-1"></i>Belum ada bukti refund diupload.
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($p['status'] == 'ditolak'): ?>
        <!-- ALERT: BISA UNDO PENOLAKAN -->
        <div class="alert alert-warning border-warning">
            <h6 class="fw-bold mb-2"><i class="bi bi-exclamation-triangle-fill me-1"></i>Salah Tekan TOLAK?</h6>
            <p class="mb-2 small">
                Jika Anda <strong>salah tekan TOLAK</strong> (seharusnya inspeksi & setujui), Anda bisa membatalkan penolakan ini.
                Status akan kembali ke <strong>MENUNGGU</strong> dan form inspeksi akan muncul lagi.
            </p>
            <p class="mb-0 small text-danger">
                <i class="bi bi-shield-exclamation me-1"></i>
                <strong>PERHATIAN:</strong> Pastikan Anda <strong>BELUM refund/kembalikan deposit</strong> ke user.
                Jika sudah, <strong>JANGAN</strong> batalkan penolakan ini.
            </p>
        </div>
        <?php endif; ?>

        <div class="alert alert-secondary text-center py-3">
            <i class="bi bi-lock-fill fs-4 d-block mb-2"></i>
            <strong>Pengajuan ini sudah diproses dan tidak bisa diubah lagi.</strong>
            <br>
            <small class="text-muted">Status: <?= esc(ucfirst($p['status'])) ?> pada <?= !empty($p['tanggal_proses']) ? esc(date('d M Y', strtotime($p['tanggal_proses']))) : '-' ?></small>
        </div>

        <div class="text-center">
            <?php if ($p['status'] == 'ditolak'): ?>
            <form action="/admin/pindah-kamar/batalkan-tolak/<?= $p['id_pindah'] ?>" method="post" style="display:inline;">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-warning"
                   onclick="return confirm('⚠️ BATALKAN PENOLAKAN pengajuan pindah kamar ini?\n\nStatus akan kembali ke MENUNGGU dan form inspeksi akan muncul lagi.\n\nPERHATIAN:\n- Pastikan Anda BELUM refund deposit ke user!\n- Pastikan kamar tujuan masih tersedia.\n\nLanjutkan batalkan penolakan?')">
                    <i class="bi bi-arrow-counterclockwise me-1"></i>Batalkan Penolakan (Undo Reject)
                </button>
            </form>
            <?php endif; ?>
            <a href="/admin/pindah-kamar" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar Pengajuan
            </a>
        </div>

        <?php else: ?>
        <!-- ============ MODE FORM (status menunggu) ============ -->

        <form action="/admin/pindah-kamar/setujui/<?= $p['id_pindah'] ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-pencil-square me-1"></i>Hasil Inspeksi Kamar Lama</h6>

            <div class="mb-3">
                <label class="form-label fw-semibold">Catatan Inspeksi <span class="text-danger">*</span></label>
                
                <div class="mb-2">
                    <small class="text-muted d-block mb-1"><i class="bi bi-hand-index me-1"></i>Klik tombol untuk isi otomatis (bisa diedit):</small>
                    <div class="d-flex flex-wrap gap-1">
                        <button type="button" class="btn btn-sm btn-outline-success btn-template" onclick="isiCatatan('Kamar lama dalam kondisi bersih dan terawat. Tidak ada kerusakan. Kunci lengkap.')">
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

                <textarea name="catatan_inspeksi" id="textareaCatatan" class="form-control" rows="3" required
                          placeholder="Contoh: Kamar lama dalam kondisi bersih. Terdapat goresan di dinding."><?= esc(old('catatan_inspeksi')) ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Potongan Kerusakan (Rp)</label>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="number" name="potongan_kerusakan" id="potongan" class="form-control"
                           value="<?= old('potongan_kerusakan', 0) ?>" min="0" max="<?= max(0, (int)$depositLama) ?>"
                           oninput="hitungDeposit()" placeholder="0">
                </div>
                <small class="text-muted">Isi 0 jika tidak ada kerusakan. Maksimal Rp <?= number_format($depositLama,0,',','.') ?> (sebesar deposit lama).</small>
                <?php if ($depositLama <= 0): ?>
                <small class="text-danger d-block mt-1"><i class="bi bi-exclamation-triangle"></i> Deposit lama kosong. Tidak bisa isi potongan. Hubungi developer kalau seharusnya ada deposit.</small>
                <?php endif; ?>
            </div>

            <!-- HASIL PERHITUNGAN -->
            <div class="card bg-light border-0 mb-4">
                <div class="card-body">
                    <h6 class="fw-bold mb-3 text-success"><i class="bi bi-calculator me-1"></i>Perhitungan Deposit Pindah</h6>
                    <table class="table table-borderless mb-0">
                        <tr>
                            <td>Deposit Lama</td>
                            <td class="text-end">Rp <?= number_format($depositLama,0,',','.') ?></td>
                        </tr>
                        <tr>
                            <td>Potongan Kerusakan</td>
                            <td class="text-end text-danger" id="potonganDisplay">Rp 0</td>
                        </tr>
                        <tr style="border-top:2px solid #dee2e6;">
                            <td><strong>Deposit Dipindah ke Kamar Baru</strong></td>
                            <td class="text-end"><strong class="text-primary" id="depositDipindah">Rp <?= number_format($depositLama,0,',','.') ?></strong></td>
                        </tr>
                        <tr>
                            <td>Deposit Kamar Baru</td>
                            <td class="text-end">Rp <?= number_format($depositBaru,0,',','.') ?></td>
                        </tr>
                        <tr id="rowSelisihBayar" class="table-warning">
                            <td><strong>User Bayar Selisih</strong></td>
                            <td class="text-end"><strong class="text-danger" id="selisihBayar">Rp <?= number_format($selisihDeposit,0,',','.') ?></strong></td>
                        </tr>
                        <tr id="rowRefundUser" class="table-success" style="display:none;">
                            <td><strong>Refund ke User</strong></td>
                            <td class="text-end"><strong class="text-success" id="refundUser">Rp 0</strong></td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- UPLOAD BUKTI REFUND (OPSIONAL) -->
            <div class="alert alert-info border-info mb-3">
                <h6 class="fw-bold text-info mb-2"><i class="bi bi-upload me-1"></i>Upload Bukti Refund (Opsional)</h6>
                <p class="small mb-2">Jika kamar baru lebih murah dan ada uang kembalian ke user, upload bukti transfer di sini.</p>
                <input type="file" name="bukti_refund" class="form-control" accept="image/*,application/pdf">
                <small class="text-muted">Format: JPG, PNG, atau PDF. Maks 2MB.</small>
            </div>

            <div class="alert alert-info py-2 small">
                <i class="bi bi-info-circle"></i> Setelah disetujui:
                <ul class="mb-0 mt-1">
                    <li>Sewa lama -> <strong>Selesai</strong>, kamar lama -> <strong>Tersedia</strong></li>
                    <li>Sewa baru dibuat, kamar baru -> <strong>Terisi</strong>, kunci -> <strong>Siap Diambil</strong></li>
                    <li>Tagihan bulan berjalan & bulan depan yang sudah dibayar user <strong>DIPINDAHKAN</strong> ke sewa baru (tidak hangus)</li>
                    <li>User hanya wajib bayar <strong>selisih deposit</strong> jika kamar baru lebih mahal</li>
                    <li>Tagihan bulan ke-3 dst dipindah ke sewa baru (harga diupdate ke harga kamar baru)</li>
                    <li>Deposit lama (setelah dipotong) dipindah ke kamar baru</li>
                </ul>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-circle me-1"></i>Setujui Pindah Kamar
                </button>
                <a href="/admin/pindah-kamar" class="btn btn-secondary"><i class="bi bi-x-circle me-1"></i>Batal</a>
            </div>
        </form>
        <?php endif; ?>
    </div>
</div>

<?php if (empty($isReadOnly)): ?>
<script>
const depositLama = <?= $depositLama ?>;
const depositBaru = <?= $depositBaru ?>;

function hitungDeposit() {
    let potongan = parseInt(document.getElementById('potongan').value) || 0;
    if (potongan > depositLama) {
        potongan = depositLama;
        document.getElementById('potongan').value = potongan;
    }
    if (potongan < 0) potongan = 0;

    const dipindah = depositLama - potongan;
    const selisih = depositBaru - dipindah;

    document.getElementById('potonganDisplay').textContent = 'Rp ' + potongan.toLocaleString('id-ID');
    document.getElementById('depositDipindah').textContent = 'Rp ' + dipindah.toLocaleString('id-ID');

    if (selisih > 0) {
        document.getElementById('selisihBayar').textContent = 'Rp ' + selisih.toLocaleString('id-ID');
        document.getElementById('rowSelisihBayar').style.display = '';
        document.getElementById('rowRefundUser').style.display = 'none';
    } else {
        document.getElementById('refundUser').textContent = 'Rp ' + Math.abs(selisih).toLocaleString('id-ID');
        document.getElementById('rowSelisihBayar').style.display = 'none';
        document.getElementById('rowRefundUser').style.display = '';
    }
}

function isiCatatan(teks) {
    const ta = document.getElementById('textareaCatatan');
    ta.value = teks;
    ta.focus();
    ta.style.backgroundColor = '#fff3cd';
    setTimeout(() => ta.style.backgroundColor = '', 500);
}

document.addEventListener('DOMContentLoaded', hitungDeposit);
</script>
<?php endif; ?>

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