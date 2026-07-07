<?php
/**
 * Partial: Info Metode Pembayaran Kos
 *
 * Dipanggil dari view user/pembayaran/index & user/pembayaran/invoice.
 * Menampilkan rekening bank & e-wallet yang sudah admin set di Pengaturan.
 *
 * @var array $metode Output dari get_metode_pembayaran_safe()
 */
?>
<div class="card border-0 shadow-sm mb-4" style="border-left: 4px solid #0d6efd;">
    <div class="card-header bg-gradient-primary text-white py-3" style="background: linear-gradient(135deg, #1a237e, #00897b);">
        <h6 class="mb-0 fw-bold"><i class="bi bi-bank2 me-2"></i>Transfer Pembayaran ke Rekening Berikut</h6>
    </div>
    <div class="card-body p-4">
        <?php if (!empty($metode['banks'])): ?>
            <!-- ===== TRANSFER BANK ===== -->
            <h6 class="fw-bold mb-3 text-primary d-flex align-items-center">
                <i class="bi bi-credit-card-2-front me-2 fs-5"></i>Transfer Bank
                <span class="badge bg-primary ms-2"><?= count($metode['banks']) ?> bank</span>
            </h6>
            <div class="row g-3 mb-4">
                <?php foreach ($metode['banks'] as $idx => $b): ?>
                    <div class="col-md-6">
                        <div class="card border-2 h-100" style="border-color: #e3f2fd; background: #fafbff;">
                            <div class="card-body p-3">
                                <!-- Header bank dengan nama -->
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge bg-primary fs-6 px-3 py-2"><?= esc($b['nama']) ?></span>
                                    <i class="bi bi-bank fs-3 text-primary opacity-50"></i>
                                </div>
                                <!-- Nomor rekening besar -->
                                <div class="d-flex align-items-center justify-content-between bg-white rounded p-2 border">
                                    <div>
                                        <small class="text-muted d-block">No. Rekening</small>
                                        <strong class="fs-5 text-dark" style="font-family: 'Courier New', monospace; letter-spacing: 2px;"><?= esc($b['rekening']) ?></strong>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-primary copy-btn"
                                            data-nomor="<?= esc($b['rekening'], 'attr') ?>"
                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Salin nomor rekening">
                                        <i class="bi bi-clipboard"></i>
                                    </button>
                                </div>
                                <!-- Pemilik -->
                                <div class="mt-2">
                                    <small class="text-muted">Atas Nama:</small>
                                    <strong class="d-block text-dark"><?= esc($b['pemilik'] ?: '-') ?></strong>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($metode['ewallets'])): ?>
            <!-- ===== E-WALLET ===== -->
            <h6 class="fw-bold mb-3 text-success d-flex align-items-center mt-2">
                <i class="bi bi-wallet2 me-2 fs-5"></i>E-Wallet
                <span class="badge bg-success ms-2"><?= count($metode['ewallets']) ?> wallet</span>
            </h6>
            <div class="row g-2 mb-3">
                <?php foreach ($metode['ewallets'] as $e):
                    // Pilih warna per e-wallet type
                    $warna = [
                        'DANA' => ['bg' => '#118eea', 'icon' => 'bi-wallet-fill'],
                        'OVO' => ['bg' => '#4c3494', 'icon' => 'bi-wallet-fill'],
                        'GoPay' => ['bg' => '#00aa13', 'icon' => 'bi-wallet-fill'],
                        'ShopeePay' => ['bg' => '#ee4d2d', 'icon' => 'bi-wallet-fill'],
                        'LinkAja' => ['bg' => '#e11931', 'icon' => 'bi-wallet-fill'],
                    ][$e['type']] ?? ['bg' => '#6c757d', 'icon' => 'bi-wallet-fill'];
                ?>
                    <div class="col-md-3 col-sm-6">
                        <div class="card border-0 h-100" style="background: #f8f9fa;">
                            <div class="card-body p-3 text-center">
                                <!-- Badge type -->
                                <div class="d-inline-flex align-items-center justify-content-center mb-2"
                                     style="background: <?= esc($warna['bg']) ?>; color: white; width: 100%; padding: 6px 10px; border-radius: 6px; font-weight: 600; font-size: 0.9rem;">
                                    <i class="bi <?= esc($warna['icon']) ?> me-1"></i>
                                    <?= esc($e['type']) ?>
                                </div>
                                <!-- Nomor -->
                                <div class="mb-2">
                                    <small class="text-muted d-block">No. HP terdaftar</small>
                                    <strong style="font-family: 'Courier New', monospace; font-size: 0.95rem;"><?= esc($e['nomor']) ?></strong>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-success w-100 copy-btn"
                                        data-nomor="<?= esc($e['nomor'], 'attr') ?>">
                                    <i class="bi bi-clipboard me-1"></i>Salin Nomor
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- ===== INSTRUKSI ===== -->
        <?php if (!empty($metode['instruksi'])): ?>
            <div class="alert alert-info border-0 mb-0 mt-3" style="background: #e3f2fd; border-radius: 8px;">
                <div class="d-flex">
                    <i class="bi bi-info-circle-fill me-2 fs-5 text-primary"></i>
                    <div>
                        <strong class="text-primary">Instruksi Pembayaran:</strong><br>
                        <small class="text-dark"><?= nl2br(esc($metode['instruksi'])) ?></small>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-info border-0 mb-0 mt-3" style="background: #e3f2fd; border-radius: 8px;">
                <div class="d-flex">
                    <i class="bi bi-info-circle-fill me-2 fs-5 text-primary"></i>
                    <div>
                        <strong class="text-primary">Instruksi Pembayaran:</strong><br>
                        <small class="text-dark">
                            1. Transfer tepat sesuai nominal tagihan ke salah satu rekening di atas.<br>
                            2. Setelah transfer, upload bukti pembayaran di form di bawah ini.<br>
                            3. Admin akan verifikasi dalam 1x24 jam.<br>
                            4. Status tagihan akan berubah jadi "Lunas" setelah diverifikasi.
                        </small>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
});

// Copy function
document.querySelectorAll('.copy-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        const nomor = this.dataset.nomor;
        const original = this.innerHTML;
        const btnEl = this;

        navigator.clipboard.writeText(nomor).then(function() {
            btnEl.innerHTML = '<i class="bi bi-check-lg"></i> Tersalin!';
            btnEl.classList.remove('btn-outline-primary', 'btn-outline-success');
            btnEl.classList.add('btn-success');

            // Update tooltip
            const tooltip = bootstrap.Tooltip.getInstance(btnEl);
            if (tooltip) {
                tooltip.setContent({ '.tooltip-inner': 'Tersalin!' });
            }

            setTimeout(function() {
                btnEl.innerHTML = original;
                btnEl.classList.remove('btn-success');
                // Restore class asal berdasarkan posisi (bank=primary, e-wallet=success)
                if (btnEl.closest('.card-body')?.querySelector('.badge.bg-primary')) {
                    btnEl.classList.add('btn-outline-primary');
                } else {
                    btnEl.classList.add('btn-outline-success');
                }
            }, 1500);
        }).catch(function() {
            // Fallback: copy pakai execCommand
            const textarea = document.createElement('textarea');
            textarea.value = nomor;
            document.body.appendChild(textarea);
            textarea.select();
            try {
                document.execCommand('copy');
                btnEl.innerHTML = '<i class="bi bi-check-lg"></i> Tersalin!';
                setTimeout(function() {
                    btnEl.innerHTML = original;
                }, 1500);
            } catch (e) {
                alert('Gagal menyalin. Silakan salin manual: ' + nomor);
            }
            document.body.removeChild(textarea);
        });
    });
});
</script>
