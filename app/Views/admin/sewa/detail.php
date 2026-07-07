<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="row">
    <div class="col-md-8">
        <!-- DETAIL PENGAJUAN SEWA -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-primary text-white fw-semibold py-3">
                <i class="bi bi-file-earmark-text me-2"></i>Detail Pengajuan Sewa
                <?php
                $badge = ['menunggu'=>'warning text-dark','aktif'=>'success','ditolak'=>'danger','selesai'=>'secondary'];
                $b = $badge[$sewa['status']] ?? 'secondary';
                ?>
                <span class="badge bg-<?= $b ?> ms-2"><?= esc(ucfirst($sewa['status'])) ?></span>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th width="30%">Nama Pendaftar</th>
                        <td>
                            <strong><?= esc($sewa['nama'] ?? '-') ?></strong><br>
                            <small class="text-muted">
                                <i class="bi bi-envelope"></i> <?= esc($sewa['email'] ?? '-') ?><br>
                                <i class="bi bi-whatsapp"></i> <?= esc($sewa['no_hp'] ?? '-') ?><br>
                                <i class="bi bi-bank"></i> 
                                <?php if (!empty($sewa['nomor_rekening'])): ?>
                                    <strong>Bank <?= esc($sewa['nama_bank']) ?> - <?= esc($sewa['nomor_rekening']) ?></strong> a.n. <?= esc($sewa['nama_pemilik_rek'] ?? '-') ?>
                                <?php elseif (!empty($sewa['ewallet_number'])): ?>
                                    <strong><?= esc($sewa['ewallet_type']) ?> - <?= esc($sewa['ewallet_number']) ?></strong>
                                <?php else: ?>
                                    <span class="text-danger">Belum isi rekening (hubungi user untuk refund)</span>
                                <?php endif; ?>
                            </small>
                        </td>
                    </tr>
                    <tr>
                        <th>Kamar Dipilih</th>
                        <td>
                            <strong>Kamar No. <?= esc($sewa['nomor_kamar'] ?? '-') ?></strong>
                            <small class="text-muted">(Kode: <?= esc($sewa['kode_kamar'] ?? '-') ?>)</small>
                        </td>
                    </tr>
                    <tr>
                        <th>Harga Sewa / Bulan</th>
                        <td><strong class="text-success">Rp <?= number_format($sewa['harga_sewa'] ?? 0, 0, ',', '.') ?></strong></td>
                    </tr>
                    <tr>
                        <th>Durasi Sewa</th>
                        <td><?= esc($sewa['durasi_bulan'] ?? 0) ?> bulan</td>
                    </tr>
                    <tr>
                        <th>Tanggal Pengajuan</th>
                        <td><?= esc($sewa['tanggal_pengajuan'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th>Tanggal Mulai Huni</th>
                        <td>
                            <strong><?= esc($sewa['tanggal_mulai'] ?? '-') ?></strong>
                            <small class="text-muted">(User ingin masuk tanggal ini)</small>
                        </td>
                    </tr>
                    <tr>
                        <th>Tanggal Selesai</th>
                        <td><?= esc($sewa['tanggal_selesai'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th>Deposit</th>
                        <td>
                            <?php if (!empty($sewa['deposit'])): ?>
                                <strong class="text-warning">Rp <?= number_format($sewa['deposit'], 0, ',', '.') ?></strong>
                                <small class="text-muted">(Dikembalikan saat checkout)</small>
                            <?php else: ?>
                                <span class="text-muted">Belum diset (akan otomatis 2x harga sewa saat disetujui)</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Status Kunci</th>
                        <td>
                            <?php
                            $kunciBadge = [
                                'belum_siap'         => ['secondary', 'Belum Siap'],
                                'siap_diambil'       => ['warning text-dark', '🔑 Siap Diambil'],
                                'sudah_diambil'      => ['success', '✅ Sudah Diambil'],
                                'sudah_dikembalikan' => ['info', '🔄 Dikembalikan'],
                            ];
                            $kb = $kunciBadge[$sewa['status_kunci'] ?? 'belum_siap'] ?? ['secondary', 'Belum Siap'];
                            ?>
                            <span class="badge bg-<?= $kb[0] ?>"><?= $kb[1] ?></span>
                            <?php if (!empty($sewa['tanggal_ambil_kunci'])): ?>
                                <small class="text-muted ms-2">
                                    <i class="bi bi-clock"></i> <?= date('d M Y H:i', strtotime($sewa['tanggal_ambil_kunci'])) ?>
                                </small>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Keterangan</th>
                        <td><?= esc($sewa['keterangan'] ?? '-') ?></td>
                    </tr>
                </table>

                <!-- TOMBOL AKSI -->
                <?php if ($sewa['status'] == 'menunggu'): ?>
                <hr>
                <h6 class="fw-bold mb-3"><i class="bi bi-check-circle me-2"></i>Aksi Admin</h6>
                <div class="d-flex gap-2">
                    <form action="/admin/sewa/setujui/<?= $sewa['id_sewa'] ?>" method="post" style="display:inline;">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-success"
                           onclick="return confirm('Setujui pengajuan sewa ini?\n\nCatatan:\n- Pastikan Deposit user sudah LUNAS.\n- Status akan jadi AKTIF\n- Tagihan bulanan dibuat otomatis\n- Status kunci otomatis jadi SIAP DIAMBIL\n- Notifikasi dikirim ke user')">
                            <i class="bi bi-check-lg me-1"></i>Setujui Pengajuan
                        </button>
                    </form>
                    <form action="/admin/sewa/tolak/<?= $sewa['id_sewa'] ?>" method="post" style="display:inline;">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-danger"
                           onclick="return confirm('Tolak pengajuan ini? Notifikasi akan dikirim ke user.')">
                            <i class="bi bi-x-lg me-1"></i>Tolak
                        </button>
                    </form>
                </div>
                <?php elseif ($sewa['status'] == 'aktif' && ($sewa['status_kunci'] ?? '') === 'siap_diambil'): ?>
                <hr>
                <form action="/admin/sewa/kunci-diambil/<?= $sewa['id_sewa'] ?>" method="post" style="display:inline;">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-success"
                       onclick="return confirm('Tandai kunci sudah diambil oleh <?= esc($sewa['nama'], 'js') ?>?')">
                        <i class="bi bi-key me-1"></i>Tandai Kunci Sudah Diambil
                    </button>
                </form>
                <?php elseif ($sewa['status'] == 'ditolak'): ?>
                <hr>
                <div class="alert alert-warning border-warning">
                    <h6 class="fw-bold mb-2"><i class="bi bi-exclamation-triangle-fill me-1"></i>Pengajuan Ini Sudah DITOLAK</h6>
                    <p class="mb-2 small">
                        Jika Anda <strong>salah tekan TOLAK</strong> (seharusnya SETUJU), Anda bisa membatalkan penolakan ini.
                        Status akan kembali ke <strong>MENUNGGU</strong> dan Anda bisa klik SETUJUI.
                    </p>
                    <p class="mb-0 small text-danger">
                        <i class="bi bi-shield-exclamation me-1"></i>
                        <strong>PERHATIAN:</strong> Pastikan Anda <strong>BELUM melakukan refund deposit</strong> ke user.
                        Jika sudah refund, <strong>JANGAN</strong> batalkan penolakan ini karena akan bikin keuangan ganda.
                    </p>
                </div>
                <form action="/admin/sewa/batalkan-tolak/<?= $sewa['id_sewa'] ?>" method="post" style="display:inline;">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-warning"
                       onclick="return confirm('⚠️ BATALKAN PENOLAKAN pengajuan sewa ini?\n\nStatus akan kembali ke MENUNGGU dan Anda bisa klik SETUJUI lagi.\n\nPERHATIAN:\n- Pastikan Anda BELUM refund deposit ke user!\n- Pastikan kamar masih tersedia (bukan ditempati user lain).\n\nLanjutkan batalkan penolakan?')">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>Batalkan Penolakan (Undo Reject)
                    </button>
                </form>
                

                <!-- ====== FIX BUG #3: SECTION REFUND DEPOSIT untuk sewa ditolak ====== -->
                <?php
                // Cek apakah deposit sudah lunas (untuk tampilkan form refund)
                $depositRow = null;
                foreach ($pembayaran as $p) {
                    if ($p['bulan_ke'] == 0) { $depositRow = $p; break; }
                }
                $depositLunas = $depositRow && $depositRow['status'] === 'lunas';
                $refundSudahDiupload = ($sewa['refund_status'] ?? 'tidak_ada') === 'selesai';
                ?>
                <?php if ($depositLunas): ?>
                <div class="card border-info border-2 mt-4">
                    <div class="card-header bg-info text-white fw-semibold py-2">
                        <i class="bi bi-cash-coin me-1"></i>Refund Deposit
                    </div>
                    <div class="card-body">
                        <?php if ($refundSudahDiupload): ?>
                            <!-- REFUND SUDAH DIUPLOAD: tampilkan info -->
                            <div class="alert alert-success mb-3">
                                <h6 class="fw-bold mb-2"><i class="bi bi-check-circle-fill me-1"></i>Refund Sudah Diproses</h6>
                                <p class="mb-1 small">Metode: <strong><?= esc($sewa['refund_metode'] ?? '-') ?></strong></p>
                                <p class="mb-1 small">Total: <strong class="text-success">Rp <?= number_format($sewa['total_refund'] ?? 0, 0, ',', '.') ?></strong></p>
                                <p class="mb-1 small">Tanggal: <?= esc($sewa['tanggal_refund'] ?? '-') ?></p>
                                <?php if (!empty($sewa['bukti_refund'])): ?>
                                    <a href="/uploads/<?= esc($sewa['bukti_refund']) ?>" target="_blank" class="btn btn-sm btn-outline-success mt-2">
                                        <i class="bi bi-download me-1"></i>Download Bukti Refund
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <!-- FORM UPLOAD REFUND -->
                            <div class="alert alert-warning mb-3">
                                <h6 class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i>Deposit User Sudah Lunas - Wajib Refund!</h6>
                                <p class="mb-0 small">User sudah bayar deposit <strong class="text-success">Rp <?= number_format($depositRow['jumlah_bayar'], 0, ',', '.') ?></strong> tapi pengajuannya ditolak. Silakan transfer refund ke rekening user di atas, lalu upload bukti transfer.</p>
                            </div>
                            <form action="/admin/sewa/refund-deposit/<?= $sewa['id_sewa'] ?>" method="post" enctype="multipart/form-data">
                                <?= csrf_field() ?>
                                <div class="mb-2">
                                    <label class="form-label fw-semibold small">Metode Refund</label>
                                    <select name="metode_refund" class="form-select form-select-sm">
                                        <option value="Transfer Bank">Transfer Bank</option>
                                        <option value="Transfer E-Wallet">Transfer E-Wallet</option>
                                        <option value="Cash">Cash (Diambil di Office)</option>
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label fw-semibold small">Total Refund (Rp)</label>
                                    <input type="number" name="total_refund" class="form-control form-control-sm" value="<?= esc($depositRow['jumlah_bayar']) ?>" required>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label fw-semibold small">Bukti Transfer <span class="text-danger">*</span></label>
                                    <input type="file" name="bukti_refund" class="form-control form-control-sm" accept="image/jpeg,image/png,image/jpg,application/pdf" required>
                                    <small class="text-muted">Format: JPG, PNG, atau PDF. Maks 2MB.</small>
                                </div>
                                <button type="submit" class="btn btn-info btn-sm"
                                    onclick="return confirm('Upload bukti refund? Pastikan Anda sudah transfer ke rekening user. Setelah upload, tidak bisa diubah.')">
                                    <i class="bi bi-upload me-1"></i>Upload Bukti Refund
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
                <!-- ====== END REFUND DEPOSIT ====== -->
<?php endif; ?>
                
                <a href="/admin/sewa" class="btn btn-secondary mt-2">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- KANAN: TAGIHAN -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent fw-semibold py-3">
                <i class="bi bi-receipt me-2 text-primary"></i>Tagihan (<?= count($pembayaran) ?>)
            </div>
            <div class="card-body p-0">
                <?php if (!empty($pembayaran)): ?>
                    <?php
                    $totalLunas = 0;
                    $totalBelum = 0;
                    foreach ($pembayaran as $p):
                        if ($p['status'] == 'lunas') $totalLunas += $p['jumlah_bayar'];
                        else $totalBelum += $p['jumlah_bayar'];
                    ?>
                    <div class="border-bottom p-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <?php if ($p['bulan_ke'] == 0): ?>
                                    <span class="badge bg-warning text-dark mb-1">DEPOSIT</span>
                                <?php else: ?>
                                    <span class="badge bg-info text-dark mb-1">BULAN <?= $p['bulan_ke'] ?></span>
                                <?php endif; ?>
                                <div class="fw-bold">Rp <?= number_format($p['jumlah_bayar'], 0, ',', '.') ?></div>
                                <small class="text-muted">
                                    <?php if ($p['status'] == 'lunas'): ?>
                                        <span class="text-success">✓ Lunas</span>
                                        <?php if (!empty($p['tanggal_bayar'])): ?>
                                            · <?= date('d M Y', strtotime($p['tanggal_bayar'])) ?>
                                        <?php endif; ?>
                                    <?php elseif ($p['status'] == 'menunggu_verifikasi'): ?>
                                        <span class="text-warning">⏳ Menunggu Verifikasi</span>
                                    <?php else: ?>
                                        <span class="text-danger">✗ Belum Bayar</span>
                                        <?php if (!empty($p['tanggal_jatuh_tempo'])): ?>
                                            · Jatuh tempo: <?= date('d M Y', strtotime($p['tanggal_jatuh_tempo'])) ?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </small>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <div class="p-3 bg-light">
                        <div class="d-flex justify-content-between text-success fw-bold mb-1">
                            <span>Lunas:</span>
                            <span>Rp <?= number_format($totalLunas, 0, ',', '.') ?></span>
                        </div>
                        <div class="d-flex justify-content-between text-danger fw-bold">
                            <span>Belum Bayar:</span>
                            <span>Rp <?= number_format($totalBelum, 0, ',', '.') ?></span>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                        <small>Belum ada tagihan.<br>Pengajuan masih menunggu persetujuan.</small>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>