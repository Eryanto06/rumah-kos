<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> - Rumah Kos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f4f6f9 0%, #e8eef5 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        /* ===== NAVBAR ===== */
        .navbar-publik {
            background: linear-gradient(135deg, #1a237e 0%, #00897b 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.15);
        }
        .navbar-publik .navbar-brand,
        .navbar-publik .nav-link {
            color: #fff !important;
            font-weight: 600;
        }
        .navbar-publik .nav-link.btn-outline-light:hover {
            background: #fff;
            color: #1a237e !important;
        }

        /* ===== CARD UTAMA ===== */
        .detail-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        .detail-foto {
            width: 100%;
            height: 400px;
            object-fit: cover;
            background: linear-gradient(135deg, #1a237e, #00897b);
        }
        .detail-foto-placeholder {
            width: 100%;
            height: 400px;
            background: linear-gradient(135deg, #1a237e, #00897b);
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255,255,255,0.6);
        }
        .detail-foto-placeholder i { font-size: 6rem; }

        /* ===== SECTION TITLE ===== */
        .section-title {
            border-left: 4px solid #00897b;
            padding-left: 12px;
            font-weight: 700;
            color: #1a237e;
        }

        /* ===== KEBIJAKAN HIGHLIGHT ===== */
        .kebijakan-box {
            background: linear-gradient(135deg, #fff9f2 0%, #fff3e0 100%);
            border: 1px solid #fd7e14;
            border-left: 5px solid #fd7e14;
            border-radius: 12px;
            padding: 20px 24px;
            box-shadow: 0 4px 14px rgba(253, 126, 20, 0.12);
        }
        .kebijakan-box .icon-circle {
            width: 50px; height: 50px;
            border-radius: 50%;
            background: #fff3e0;
            color: #fd7e14;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem;
            flex-shrink: 0;
        }

        /* ===== INFO ROW ===== */
        .info-row th { width: 40%; background: #f8f9fa; }

        /* ===== ATURAN BOX ===== */
        .aturan-box {
            background: #f8f9fa;
            border-left: 4px solid #0d6efd;
            border-radius: 8px;
            padding: 16px 20px;
        }
        .aturan-box ul { padding-left: 18px; margin: 0; }

        /* ===== BIAYA CARD ===== */
        .biaya-card {
            background: linear-gradient(135deg, #00897b, #1a237e);
            color: white;
            border-radius: 12px;
            padding: 20px 22px;
        }
        .price-big {
            color: #00897b;
            font-weight: 700;
        }

        /* ===== FAQ ===== */
        .accordion-button:not(.collapsed) {
            background: #e7f1ff;
            color: #1a237e;
            font-weight: 600;
        }
        .accordion-button:focus {
            box-shadow: 0 0 0 0.2rem rgba(26,35,126,0.15);
            border-color: #1a237e;
        }

        /* ===== FOOTER ===== */
        .footer-publik {
            background: #1a237e;
            color: rgba(255,255,255,0.7);
            padding: 30px 0;
            margin-top: 50px;
        }
        .btn-wa {
            background: #25D366;
            color: white;
            border: none;
        }
        .btn-wa:hover { background: #1da851; color: white; }
    </style>
</head>
<body>

<!-- ===== NAVBAR ===== -->
<nav class="navbar navbar-expand-lg navbar-publik sticky-top">
    <div class="container">
        <a class="navbar-brand" href="/">
            <i class="bi bi-house-fill me-2"></i>Rumah Kos
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navPublik">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navPublik">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                <li class="nav-item"><a class="nav-link" href="/">Beranda</a></li>
                <li class="nav-item">
                    <a class="nav-link active" href="/kamar"><i class="bi bi-door-open me-1"></i>Kamar Tersedia</a>
                </li>
                <li class="nav-item"><a class="nav-link" href="/peraturan"><i class="bi bi-shield-check me-1"></i>Peraturan</a></li>
                <li class="nav-item">
                    <a class="nav-link btn btn-outline-light px-3" href="/login">
                        <i class="bi bi-box-arrow-in-right me-1"></i>Login / Daftar
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- ===== KONTEN ===== -->
<div class="container py-4">
    <div class="mb-3">
        <a href="/kamar" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar Kamar
        </a>
    </div>

    <div class="row g-4">
        <!-- ===== KIRI: FOTO + INFO UTAMA ===== -->
        <div class="col-lg-8">
            <div class="card detail-card mb-4">
                <?php if (!empty($kamar['foto'])): ?>
                    <img src="/uploads/<?= esc($kamar['foto']) ?>" alt="Foto Kamar No. <?= esc($kamar['nomor_kamar']) ?>" class="detail-foto">
                <?php else: ?>
                    <div class="detail-foto-placeholder">
                        <i class="bi bi-image"></i>
                    </div>
                <?php endif; ?>

                <div class="card-body p-4">
                    <!-- JUDUL + STATUS -->
                    <div class="d-flex justify-content-between align-items-start mb-3 flex-wrap gap-2">
                        <div>
                            <h3 class="fw-bold mb-1">Kamar No. <?= esc($kamar['nomor_kamar']) ?></h3>
                            <p class="text-muted mb-0"><i class="bi bi-tag me-1"></i>Kode: <?= esc($kamar['kode_kamar']) ?></p>
                        </div>
                        <?php
                        $statusKamar = $kamar['status'] ?? 'tersedia';
                        $statusBadgeMap = [
                            'tersedia'  => ['class' => 'bg-success', 'icon' => 'bi-check-circle',     'label' => 'Tersedia'],
                            'terisi'    => ['class' => 'bg-danger',  'icon' => 'bi-x-circle',         'label' => 'Terisi'],
                            'perbaikan' => ['class' => 'bg-warning text-dark', 'icon' => 'bi-tools', 'label' => 'Perbaikan'],
                            'dibooking' => ['class' => 'bg-info text-dark',    'icon' => 'bi-clock-history', 'label' => 'Di-booking'],
                        ];
                        $sb = $statusBadgeMap[$statusKamar] ?? $statusBadgeMap['tersedia'];
                        ?>
                        <span class="badge <?= esc($sb['class']) ?> fs-6 px-3 py-2"><i class="bi <?= esc($sb['icon']) ?> me-1"></i><?= esc($sb['label']) ?></span>
                    </div>

                    <!-- HARGA -->
                    <div class="card bg-light border-0 mb-4">
                        <div class="card-body text-center py-3">
                            <small class="text-muted d-block">Harga Sewa per Bulan</small>
                            <h2 class="price-big mb-0">Rp <?= number_format($kamar['harga_sewa'], 0, ',', '.') ?></h2>
                        </div>
                    </div>

                    <!-- ===== KEBIJAKAN POTONGAN 50% DEPOSIT (HIGHLIGHT) ===== -->
                    <h5 class="section-title mb-3"><i class="bi bi-shield-exclamation me-2"></i>Kebijakan Penting</h5>
                    <div class="kebijakan-box mb-4">
                        <div class="d-flex align-items-start gap-3">
                            <div class="icon-circle">
                                <i class="bi bi-shield-exclamation"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold text-warning mb-2">
                                    <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                    Kebijakan Potongan 50% Deposit (Early Checkout)
                                </h6>
                                <p class="mb-2 text-dark small">
                                    <strong>Penting diketahui sebelum menyewa:</strong> Jika Anda melakukan
                                    <strong>checkout sebelum masa kontrak berakhir</strong>, maka deposit Anda akan
                                    <strong>dipotong 50% secara otomatis</strong> sesuai kebijakan yang berlaku.
                                </p>
                                <ul class="mb-0 small text-muted">
                                    <li>Potongan 50% berlaku untuk penghuni yang keluar sebelum tanggal berakhir kontrak.</li>
                                    <li>Sisa 50% deposit bisa dipotong lagi jika ada kerusakan kamar saat inspeksi.</li>
                                    <li>Potongan ini terpisah dari potongan kerusakan inspeksi kamar.</li>
                                    <li>Deposit dikembalikan penuh (100%) jika checkout sesuai tanggal berakhir kontrak.</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- ===== INFORMASI KAMAR ===== -->
                    <h5 class="section-title mb-3"><i class="bi bi-info-circle me-2"></i>Informasi Kamar</h5>
                    <table class="table table-bordered mb-4">
                        <tr class="info-row"><th><i class="bi bi-tag me-2"></i>Kode Kamar</th><td><?= esc($kamar['kode_kamar']) ?></td></tr>
                        <tr class="info-row"><th><i class="bi bi-door-closed me-2"></i>Nomor Kamar</th><td><?= esc($kamar['nomor_kamar']) ?></td></tr>
                        <tr class="info-row"><th><i class="bi bi-cash me-2"></i>Harga Sewa</th><td>Rp <?= number_format($kamar['harga_sewa'], 0, ',', '.') ?> / bulan</td></tr>
                        <tr class="info-row"><th><i class="bi bi-stars me-2"></i>Fasilitas</th><td><?= esc($kamar['fasilitas']) ?></td></tr>
                        <tr class="info-row"><th><i class="bi bi-check-circle me-2"></i>Status</th><td><span class="badge <?= esc($sb['class']) ?>"><?= esc($sb['label']) ?></span></td></tr>
                    </table>

                    <!-- ===== ATURAN DASAR ===== -->
                    <h5 class="section-title mb-3"><i class="bi bi-shield-check me-2"></i>Aturan Dasar Kos</h5>
                    <div class="aturan-box mb-4">
                        <ul class="small">
                            <li>Jam malam: pukul <strong>22:00 WIB</strong> (untuk pengunjung)</li>
                            <li>Tidak boleh membawa hewan peliharaan</li>
                            <li>Tidak boleh merokok di dalam kamar</li>
                            <li>Jaga kebersihan kamar & area bersama</li>
                            <li>Kontraktor listrik & air ditanggung pengelola</li>
                            <li>Wi-Fi gratis tersedia di area kos</li>
                            <li>Pengunjung dilarang menginap tanpa izin admin</li>
                        </ul>
                    </div>

                    <!-- ===== FAQ ===== -->
                    <h5 class="section-title mb-3"><i class="bi bi-question-circle me-2"></i>Pertanyaan Umum (FAQ)</h5>
                    <div class="accordion mb-4" id="faqAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                    Bagaimana cara menyewa kamar ini?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body small">
                                    <ol class="mb-0">
                                        <li>Daftar akun / login di website</li>
                                        <li>Buka menu "Ajukan Sewa", pilih kamar & tanggal mulai huni</li>
                                        <li>Bayar deposit (<?= $deposit_kali ?>x harga sewa = Rp <?= number_format($deposit_nominal, 0, ',', '.') ?>) via upload bukti transfer</li>
                                        <li>Admin verifikasi pembayaran deposit</li>
                                        <li>Admin setujui sewa → kunci siap diambil di Office</li>
                                        <li>Ambil kunci + mulai huni</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                    Apakah deposit bisa dikembalikan?
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body small">
                                    Ya! Deposit dikembalikan saat checkout setelah inspeksi kamar. Jika ada kerusakan, deposit akan dipotong sesuai kerusakan. Sisanya dikembalikan ke penyewa.
                                    <br><br>
                                    <strong>⚠️ Catatan:</strong> Jika checkout sebelum kontrak berakhir, deposit akan <strong>dipotong 50% secara otomatis</strong> (kebijakan early checkout).
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                                    <i class="bi bi-exclamation-triangle-fill text-warning me-1"></i>
                                    Apa itu kebijakan potongan 50% deposit?
                                </button>
                            </h2>
                            <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body small">
                                    <p class="mb-2"><strong>Kebijakan Early Checkout (Potongan 50% Deposit):</strong></p>
                                    <ul class="mb-0">
                                        <li>Berlaku jika Anda checkout <strong>sebelum tanggal berakhir kontrak</strong>.</li>
                                        <li>Deposit akan <strong>dipotong 50% secara otomatis</strong>.</li>
                                        <li>Sisa 50% bisa dipotong lagi kalau ada kerusakan kamar saat inspeksi.</li>
                                        <li>Potongan ini terpisah dari potongan kerusakan inspeksi.</li>
                                        <li>Deposit dikembalikan <strong>100% penuh</strong> jika checkout sesuai tanggal berakhir kontrak.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                    Bisakah pindah kamar setelah sewa?
                                </button>
                            </h2>
                            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body small">
                                    Bisa. Ajukan pindah kamar melalui menu "Pindah Kamar" di dashboard. Pilih kamar tujuan yang masih tersedia. Admin akan proses & sisa durasi sewa Anda dipindahkan ke kamar baru. Deposit & tagihan belum bayar juga dipindahkan.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== KANAN: BIAYA + CTA + KONTAK ===== -->
        <div class="col-lg-4">
            <!-- ESTIMASI BIAYA AWAL -->
            <div class="biaya-card mb-3">
                <h5 class="fw-bold mb-3"><i class="bi bi-calculator me-2"></i>Estimasi Biaya Awal</h5>
                <table class="table table-borderless text-white mb-0">
                    <tr>
                        <td>Deposit (<?= $deposit_kali ?>x sewa)</td>
                        <td class="text-end">Rp <?= number_format($deposit_nominal, 0, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <td>Sewa Bulan Pertama</td>
                        <td class="text-end">Rp <?= number_format($kamar['harga_sewa'], 0, ',', '.') ?></td>
                    </tr>
                    <tr style="border-top: 2px solid rgba(255,255,255,0.3);">
                        <td><strong>TOTAL DIBAYAR AWAL</strong></td>
                        <td class="text-end"><strong>Rp <?= number_format($estimasi_total_awal, 0, ',', '.') ?></strong></td>
                    </tr>
                </table>
                <small class="d-block mt-2 opacity-75">* Harga estimasi, bisa berubah sesuai pengaturan admin</small>
            </div>

            <!-- CTA -->
            <?php if (!session()->get('logged_in')): ?>
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body text-center p-4">
                    <i class="bi bi-person-plus fs-1 text-primary mb-2"></i>
                    <h5 class="fw-bold mb-2">Tertarik Menyewa?</h5>
                    <p class="text-muted small mb-3">Daftar akun untuk mengajukan sewa kamar ini secara online.</p>
                    <a href="/register" class="btn btn-success w-100 mb-2">
                        <i class="bi bi-person-plus me-1"></i>Daftar Sekarang
                    </a>
                    <a href="/login" class="btn btn-outline-primary w-100">
                        <i class="bi bi-box-arrow-in-right me-1"></i>Sudah Punya Akun? Login
                    </a>
                </div>
            </div>
            <?php else: ?>
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body text-center p-4">
                    <?php if (session()->get('role') === 'user'): ?>
                        <i class="bi bi-check-circle fs-1 text-success mb-2"></i>
                        <h5 class="fw-bold mb-2">Anda Sudah Login</h5>
                        <p class="text-muted small mb-3">Ajukan sewa kamar No. <?= esc($kamar['nomor_kamar']) ?> sekarang!</p>
                        <a href="/user/sewa" class="btn btn-success w-100">
                            <i class="bi bi-file-earmark-plus me-1"></i>Ajukan Sewa Kamar Ini
                        </a>
                    <?php else: ?>
                        <i class="bi bi-shield-lock fs-1 text-warning mb-2"></i>
                        <h5 class="fw-bold mb-2">Anda Login sebagai Admin</h5>
                        <p class="text-muted small mb-3">Admin tidak bisa mengajukan sewa.</p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- KONTAK ADMIN -->
            <?php if (!empty($admin) && !empty($admin['no_hp'])): ?>
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3"><i class="bi bi-headset me-2 text-primary"></i>Hubungi Pengelola</h6>
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-person-circle fs-3 text-primary me-3"></i>
                        <div>
                            <strong><?= esc($admin['nama'] ?? 'Admin Rumah Kos') ?></strong><br>
                            <small class="text-muted">Pengelola Kos</small>
                        </div>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted d-block"><i class="bi bi-telephone me-1"></i>No. HP</small>
                        <strong><?= esc($admin['no_hp']) ?></strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block"><i class="bi bi-envelope me-1"></i>Email</small>
                        <strong><?= esc($admin['email'] ?? '-') ?></strong>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block"><i class="bi bi-clock me-1"></i>Jam Operasional</small>
                        <strong>Senin - Sabtu, 08:00 - 17:00 WIB</strong>
                    </div>
                    <?php
                    $nomorWa = preg_replace('/[^0-9]/', '', $admin['no_hp']);
                    if (substr($nomorWa, 0, 1) === '0') $nomorWa = '62' . substr($nomorWa, 1);
                    elseif (substr($nomorWa, 0, 1) === '8') $nomorWa = '62' . $nomorWa;
                    $pesanWa = "Halo Admin Rumah Kos, saya tertarik dengan Kamar No. " . $kamar['nomor_kamar'] . " (Kode: " . $kamar['kode_kamar'] . "). Harga: Rp " . number_format($kamar['harga_sewa'], 0, ',', '.') . "/bln. Mohon info ketersediaan & prosedur sewanya. Terima kasih.";
                    $linkWa = 'https://web.whatsapp.com/send?phone=' . $nomorWa . '&text=' . urlencode($pesanWa);
                    ?>
                    <a href="<?= $linkWa ?>" target="_blank" class="btn btn-wa w-100">
                        <i class="bi bi-whatsapp me-1"></i>Chat via WhatsApp
                    </a>
                </div>
            </div>
            <?php endif; ?>

            <!-- INFO LOKASI -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3"><i class="bi bi-geo-alt me-2 text-primary"></i>Lokasi Rumah Kos</h6>
                    <p class="small mb-2">
                        <i class="bi bi-geo-alt-fill me-1 text-danger"></i>
                        Jl. Contoh Alamat No. 123, Kota Contoh
                    </p>
                    <p class="small mb-2">
                        <i class="bi bi-train-front me-1 text-info"></i>
                        Dekat transportasi umum
                    </p>
                    <p class="small mb-0">
                        <i class="bi bi-shop me-1 text-success"></i>
                        Dekat minimarket & tempat makan
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ===== FOOTER ===== -->
<footer class="footer-publik text-center">
    <div class="container">
        <p class="mb-1"><i class="bi bi-house-fill me-1"></i><strong>Rumah Kos</strong> - Sistem Informasi Manajemen Kos</p>
        <p class="mb-0 small">&copy; <?= date('Y') ?> Rumah Kos. All rights reserved.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>