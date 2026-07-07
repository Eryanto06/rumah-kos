<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<style>
    .info-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 10px;
        padding: 16px;
        border-left: 4px solid #0d6efd;
    }
    .status-badge { font-size: 0.9rem; padding: 6px 14px; border-radius: 20px; font-weight: 600; }
    .bukti-thumb {
        max-width: 200px; max-height: 200px; border-radius: 8px;
        border: 3px solid #dee2e6; cursor: pointer; transition: all 0.3s;
    }
    .bukti-thumb:hover { transform: scale(1.05); border-color: #0d6efd; }
    .form-keterangan { border: 2px solid #dee2e6; border-radius: 8px; padding: 12px; }
    .form-keterangan:focus { border-color: #0d6efd; box-shadow: 0 0 0 0.2rem rgba(13,110,253,0.15); }
    .btn-template { font-size: 0.8rem; padding: 6px 12px; border-radius: 20px; }
    .photo-modal {
        display: none; position: fixed; z-index: 9999; left: 0; top: 0;
        width: 100%; height: 100%; background: rgba(0,0,0,0.92);
        justify-content: center; align-items: center; padding: 20px;
        backdrop-filter: blur(2px);
    }
    .photo-modal.show { display: flex; }
    .photo-modal-content {
        max-width: 92%; max-height: 90vh; background: white;
        border-radius: 12px; padding: 20px; position: relative;
        box-shadow: 0 20px 60px rgba(0,0,0,0.5);
    }
    .photo-modal-content img { max-width: 100%; max-height: 75vh; display: block; margin: 0 auto; border-radius: 8px; }
    .photo-modal-content iframe { max-width: 100%; width: 80vw; max-height: 75vh; height: 75vh; display: block; margin: 0 auto; border: none; border-radius: 8px; }
    .photo-modal-close {
        position: absolute; top: 10px; right: 10px; background: #dc3545;
        color: white; border: none; width: 44px; height: 44px;
        border-radius: 50%; font-size: 1.3rem; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.3); z-index: 2;
        transition: all 0.2s;
    }
    .photo-modal-close:hover { background: #bb2d3b; transform: scale(1.1) rotate(90deg); }
    .photo-modal-close-outer {
        position: fixed; top: 20px; right: 20px; background: rgba(255,255,255,0.15);
        color: white; border: 2px solid rgba(255,255,255,0.4); width: 50px; height: 50px;
        border-radius: 50%; font-size: 1.5rem; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        backdrop-filter: blur(4px); transition: all 0.2s; z-index: 10000;
    }
    .photo-modal-close-outer:hover { background: rgba(220,53,69,0.8); border-color: #dc3545; transform: scale(1.1); }
    .photo-modal-caption {
        text-align: center; margin-top: 12px; padding: 8px;
        background: #f8f9fa; border-radius: 6px; font-size: 0.85rem; color: #495057;
        word-break: break-all;
    }
</style>

<div class="row">
    <!-- KIRI: Info Pembayaran -->
    <div class="col-md-7">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                <span><i class="bi bi-receipt me-2"></i>Detail Pembayaran #<?= $pembayaran['id_pembayaran'] ?></span>
                <a href="/admin/pembayaran" class="btn btn-sm btn-light"><i class="bi bi-arrow-left"></i> Kembali</a>
            </div>
            <div class="card-body">
                <!-- INFO PENGHUNI -->
                <div class="info-card mb-3">
                    <h6 class="fw-bold mb-2 text-primary"><i class="bi bi-person me-1"></i>Informasi Penghuni</h6>
                    <div class="row">
                        <div class="col-md-6"><small class="text-muted d-block">Nama</small><strong><?= esc($pembayaran['nama'] ?? '-') ?></strong></div>
                        <div class="col-md-6"><small class="text-muted d-block">No. HP</small><strong><?= esc($pembayaran['no_hp'] ?? '-') ?></strong></div>
                        <div class="col-md-6 mt-2"><small class="text-muted d-block">Email</small><strong><?= esc($pembayaran['email'] ?? '-') ?></strong></div>
                        <div class="col-md-6 mt-2"><small class="text-muted d-block">Kamar</small><strong>No. <?= esc($pembayaran['nomor_kamar'] ?? '-') ?></strong></div>
                    </div>
                </div>

                <!-- ====== FIX BUG: REKENING USER (untuk admin kirim WA / verifikasi) ====== -->
                <?php
                $hasRekening = !empty($pembayaran['nomor_rekening']) || !empty($pembayaran['ewallet_number']);
                ?>
                <div class="info-card mb-3 border-start border-warning border-4">
                    <h6 class="fw-bold mb-2 text-warning"><i class="bi bi-bank me-1"></i>Rekening Penghuni (untuk Refund)</h6>
                    <?php if ($hasRekening): ?>
                        <?php if (!empty($pembayaran['nomor_rekening'])): ?>
                        <div class="mb-2">
                            <small class="text-muted d-block">Bank</small>
                            <strong><?= esc($pembayaran['nama_bank']) ?> - <?= esc($pembayaran['nomor_rekening']) ?></strong>
                            <small class="text-muted d-block">a.n. <?= esc($pembayaran['nama_pemilik_rek'] ?? '-') ?></small>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($pembayaran['ewallet_number'])): ?>
                        <div>
                            <small class="text-muted d-block">E-Wallet</small>
                            <strong><?= esc($pembayaran['ewallet_type']) ?> - <?= esc($pembayaran['ewallet_number']) ?></strong>
                        </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="alert alert-warning py-2 mb-0 small">
                            <i class="bi bi-exclamation-triangle me-1"></i>User belum mengisi rekening di profil. Hubungi via WhatsApp untuk minta no rekening sebelum proses refund.
                        </div>
                    <?php endif; ?>
                </div>

                <!-- DETAIL TAGIHAN -->
                <h6 class="fw-bold mb-2 text-primary"><i class="bi bi-cash-coin me-1"></i>Detail Tagihan Ini</h6>
                <table class="table table-bordered">
                    <tr><th width="40%">Jenis Tagihan</th><td>
                        <?php if ($pembayaran['bulan_ke'] == 0): ?>
                            <span class="badge bg-warning text-dark">Deposit Awal</span>
                        <?php else: ?>
                            <span class="badge bg-info text-dark">Sewa Bulan ke-<?= esc($pembayaran['bulan_ke']) ?></span>
                        <?php endif; ?>
                    </td></tr>
                    <tr><th>Jumlah Tagihan</th><td class="fw-bold text-success">Rp <?= number_format($pembayaran['jumlah_bayar'],0,',','.') ?></td></tr>
                    <tr><th>Tanggal Bayar</th><td><?= !empty($pembayaran['tanggal_bayar']) ? esc(date('d M Y', strtotime($pembayaran['tanggal_bayar']))) : '<span class="text-muted">Belum bayar</span>' ?></td></tr>
                    <tr><th>Status</th><td>
                        <?php
                        $badge = ['lunas'=>['success','Lunas'], 'belum_bayar'=>['danger','Belum Bayar'], 'menunggu_verifikasi'=>['info','Menunggu Verifikasi']];
                        $b = $badge[$pembayaran['status']] ?? ['secondary', esc(ucfirst($pembayaran['status']))];
                        ?>
                        <span class="badge bg-<?= $b[0] ?> status-badge"><?= $b[1] ?></span>
                    </td></tr>
                </table>

                <!-- TAGIHAN TERKAIT (BUKTI SAMA) -->
                <?php if (count($relatedPayments) > 1): ?>
                <div class="alert alert-warning border-warning">
                    <h6 class="fw-bold text-warning mb-2"><i class="bi bi-info-circle me-1"></i>Ada <?= count($relatedPayments) ?> Tagihan dengan Bukti yang Sama!</h6>
                    <p class="small mb-2">User membayar beberapa bulan sekaligus dengan 1 bukti transfer. Anda bisa verifikasi semua sekaligus di form kanan.</p>
                    <table class="table table-sm table-bordered mb-0">
                        <thead class="table-light">
                            <tr><th>Bulan</th><th>Jumlah</th><th>Status</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($relatedPayments as $rp): ?>
                            <tr>
                                <td><?= label_bulan_ke($rp['bulan_ke']) ?></td>
                                <td>Rp <?= number_format($rp['jumlah_bayar'],0,',','.') ?></td>
                                <td><span class="badge bg-info">Menunggu</span></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="table-light">
                            <tr class="fw-bold">
                                <td>TOTAL (<?= count($relatedPayments) ?> tagihan)</td>
                                <td colspan="2" class="text-success">Rp <?= number_format(array_sum(array_column($relatedPayments, 'jumlah_bayar')),0,',','.') ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <?php endif; ?>

                <!-- BUKTI BAYAR -->
                <?php if (!empty($pembayaran['bukti_bayar'])):
                    // FIX BUG: kalau bukti berupa PDF, <img> akan broken (PDF gak bisa render sebagai gambar).
                    // Deteksi extension: kalau PDF, tampilkan thumbnail PDF icon; kalau image, tampilkan <img>.
                    $extBukti = strtolower(pathinfo($pembayaran['bukti_bayar'], PATHINFO_EXTENSION));
                    $isPdfBukti = ($extBukti === 'pdf');
                ?>
                <div class="mt-3">
                    <h6 class="fw-bold mb-2 text-primary"><i class="bi bi-image me-1"></i>Bukti Pembayaran</h6>
                    <div class="text-center p-3 bg-light rounded">
                        <?php if ($isPdfBukti): ?>
                            <!-- Thumbnail PDF: tampilkan icon besar, klik untuk buka modal iframe -->
                            <div class="bukti-thumb d-flex flex-column align-items-center justify-content-center"
                                 style="width:200px;height:200px;margin:0 auto;background:#fff;border:3px solid #dee2e6;border-radius:8px;cursor:pointer;"
                                 onclick="bukaFoto('<?= esc($pembayaran['bukti_bayar'], 'js') ?>')">
                                <i class="bi bi-file-earmark-pdf" style="font-size:4rem;color:#dc3545;"></i>
                                <small class="text-muted mt-2">PDF Document</small>
                                <small class="text-muted">Klik untuk lihat</small>
                            </div>
                        <?php else: ?>
                            <img src="/uploads/<?= esc($pembayaran['bukti_bayar']) ?>" alt="Bukti Bayar" class="bukti-thumb"
                                 onclick="bukaFoto('<?= esc($pembayaran['bukti_bayar'], 'js') ?>')">
                        <?php endif; ?>
                        <div class="mt-2">
                            <button type="button" class="btn btn-primary btn-sm" onclick="bukaFoto('<?= esc($pembayaran['bukti_bayar'], 'js') ?>')">
                                <i class="bi bi-zoom-in me-1"></i>Perbesar <?= $isPdfBukti ? 'Dokumen' : 'Foto' ?>
                            </button>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <div class="alert alert-warning text-center mt-3">
                    <i class="bi bi-exclamation-triangle fs-3 d-block mb-2"></i>
                    <strong>Belum ada bukti pembayaran</strong>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- KANAN: Form Verifikasi -->
    <div class="col-md-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-warning text-dark py-3">
                <i class="bi bi-check2-circle me-2"></i>Verifikasi Pembayaran
            </div>
            <div class="card-body">
                <form action="/admin/pembayaran/verifikasi/<?= $pembayaran['id_pembayaran'] ?>" method="post">
                    <?= csrf_field() ?>

                    <!-- CEKBOX VERIFIKASI SEMUA -->
                    <?php if (count($relatedPayments) > 1): ?>
                    <div class="alert alert-success py-2 mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="verifikasi_semua" value="1" id="verifikasiSemua" checked>
                            <label class="form-check-label fw-bold" for="verifikasiSemua">
                                <i class="bi bi-check2-all me-1"></i>Verifikasi SEMUA <?= count($relatedPayments) ?> tagihan sekaligus
                            </label>
                            <small class="text-muted d-block mt-1">Total: Rp <?= number_format(array_sum(array_column($relatedPayments, 'jumlah_bayar')),0,',','.') ?></small>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Update Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="lunas" <?= ($pembayaran['status'] ?? '') == 'menunggu_verifikasi' ? 'selected' : '' ?>>✅ Lunas</option>
                            <option value="belum_bayar">❌ Tolak (Belum Bayar)</option>
                            <option value="menunggu_verifikasi">⏳ Tetap Menunggu</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-chat-dots text-primary me-1"></i>Pesan untuk Penghuni
                            <span class="badge bg-info text-dark ms-1">Dikirim ke User</span>
                        </label>
                        <div class="mb-2">
                            <small class="text-muted d-block mb-1"><i class="bi bi-hand-index me-1"></i>Klik tombol untuk isi otomatis:</small>
                            <div class="d-flex flex-wrap gap-1">
                                <button type="button" class="btn btn-sm btn-outline-success btn-template" onclick="isiPesan('Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏')">
                                    <i class="bi bi-check-circle"></i> Verifikasi OK
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger btn-template" onclick="isiPesan('Mohon maaf, bukti pembayaran kurang jelas/tidak terbaca. Mohon upload ulang dengan resolusi yang lebih jelas. Terima kasih atas pengertiannya. 🙏')">
                                    <i class="bi bi-x-circle"></i> Bukti Tidak Jelas
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger btn-template" onclick="isiPesan('Mohon maaf, nominal yang dibayarkan tidak sesuai dengan tagihan. Silakan hubungi admin via WhatsApp. Terima kasih. 🙏')">
                                    <i class="bi bi-x-circle"></i> Nominal Salah
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-secondary btn-template" onclick="isiPesan('')">
                                    <i class="bi bi-eraser"></i> Hapus
                                </button>
                            </div>
                        </div>
                        <textarea name="keterangan" id="textareaPesan" class="form-control form-keterangan" rows="4" placeholder="Tulis pesan untuk penghuni di sini..."><?= old('keterangan', $pembayaran['keterangan'] ?? '') ?></textarea>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i>
                            <?php if (count($relatedPayments) > 1): ?>
                                Verifikasi <?= count($relatedPayments) ?> Tagihan Sekaligus
                            <?php else: ?>
                                Verifikasi Pembayaran
                            <?php endif; ?>
                        </button>
                        <a href="/admin/pembayaran" class="btn btn-outline-secondary"><i class="bi bi-x-circle me-1"></i>Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- MODAL FOTO (image + PDF) -->
<div id="photoModal" class="photo-modal" onclick="if(event.target.id==='photoModal')tutupFoto()">
    <button type="button" class="photo-modal-close-outer" onclick="tutupFoto()" title="Tutup (ESC)">
        <i class="bi bi-x-lg"></i>
    </button>
    <div class="photo-modal-content">
        <button type="button" class="photo-modal-close" onclick="tutupFoto()" title="Tutup">
            <i class="bi bi-x-lg"></i>
        </button>
        <div id="photoModalBody"></div>
        <div id="photoModalCaption" class="photo-modal-caption"></div>
        <div class="text-center mt-2"><small class="text-muted"><i class="bi bi-info-circle me-1"></i>Klik tombol X, klik area gelap di luar, atau tekan ESC untuk tutup</small></div>
    </div>
</div>

<script>
function bukaFoto(file) {
    const url = '/uploads/' + file;
    const body = document.getElementById('photoModalBody');
    const caption = document.getElementById('photoModalCaption');
    const ext = file.split('.').pop().toLowerCase();
    if (ext === 'pdf') {
        body.innerHTML = '<iframe src="' + url + '" title="Bukti PDF"></iframe>';
    } else {
        body.innerHTML = '<img src="' + url + '" alt="Bukti">';
    }
    caption.innerHTML = '<i class="bi bi-file-earmark me-1"></i><strong>File:</strong> ' + file;
    document.getElementById('photoModal').classList.add('show');
    document.body.style.overflow = 'hidden';
}
function tutupFoto() {
    document.getElementById('photoModal').classList.remove('show');
    document.body.style.overflow = '';
    setTimeout(() => {
        document.getElementById('photoModalBody').innerHTML = '';
    }, 200);
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') tutupFoto(); });
function isiPesan(teks) {
    const ta = document.getElementById('textareaPesan');
    ta.value = teks; ta.focus();
    ta.style.backgroundColor = '#fff3cd';
    setTimeout(() => ta.style.backgroundColor = '', 500);
}
</script>

<?= $this->endSection() ?>