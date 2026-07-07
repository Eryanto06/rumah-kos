<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php $kontak = get_kontak_kos_safe(); ?>
    <title><?= esc($kontak['nama_kos']) ?> - <?= esc($kontak['tagline']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * { scroll-behavior: smooth; }
        body {
            background: #f4f6f9;
            min-height: 100vh;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        /* ===== NAVBAR ===== */
        .navbar-publik {
            background: linear-gradient(135deg, #1a237e 0%, #00897b 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.15);
            padding: 12px 0;
        }
        .navbar-publik .navbar-brand,
        .navbar-publik .nav-link {
            color: #fff !important;
            font-weight: 600;
        }
        .navbar-publik .navbar-brand { font-size: 1.4rem; }
        .navbar-publik .nav-link.btn-outline-light:hover {
            background: #fff;
            color: #1a237e !important;
        }

        /* ===== HERO ===== */
        .hero {
            background: linear-gradient(135deg, rgba(26,35,126,0.92), rgba(0,137,123,0.92)),
                        url('https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=1600&q=80') center/cover;
            color: #fff;
            padding: 90px 20px 110px;
            position: relative;
            overflow: hidden;
        }
        .hero::after {
            content: "";
            position: absolute;
            bottom: -1px; left: 0; right: 0;
            height: 60px;
            background: #f4f6f9;
            clip-path: polygon(0 100%, 100% 100%, 100% 0, 0 60%);
        }
        .hero h1 {
            font-size: 2.8rem;
            font-weight: 800;
            margin-bottom: 18px;
            text-shadow: 0 2px 20px rgba(0,0,0,0.3);
        }
        .hero p.lead {
            font-size: 1.15rem;
            opacity: 0.95;
            max-width: 680px;
            margin: 0 auto 30px;
        }
        .hero .btn { padding: 12px 28px; font-weight: 600; border-radius: 50px; }

        /* ===== STATISTIK ===== */
        .stat-section { margin-top: -50px; position: relative; z-index: 2; }
        .stat-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            transition: transform 0.25s, box-shadow 0.25s;
            color: #fff;
            padding: 26px 20px;
        }
        .stat-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 40px rgba(0,0,0,0.12);
        }
        .stat-card .icon-circle {
            width: 56px; height: 56px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.6rem;
            margin-bottom: 12px;
        }
        .stat-card .stat-num { font-size: 2rem; font-weight: 800; line-height: 1; }
        .stat-card .stat-label { opacity: 0.9; font-size: 0.92rem; }

        /* ===== SECTION TITLE ===== */
        .section-title {
            text-align: center;
            margin-bottom: 40px;
        }
        .section-title h2 {
            font-weight: 800;
            color: #1a237e;
            margin-bottom: 10px;
        }
        .section-title p { color: #6c757d; max-width: 600px; margin: 0 auto; }
        .section-title .accent-line {
            width: 70px; height: 4px;
            background: linear-gradient(90deg, #1a237e, #00897b);
            margin: 0 auto 16px;
            border-radius: 2px;
        }

        /* ===== FITUR ===== */
        .fitur-card {
            border: none;
            border-radius: 14px;
            box-shadow: 0 4px 18px rgba(0,0,0,0.06);
            padding: 30px 24px;
            height: 100%;
            transition: transform 0.25s;
            background: #fff;
        }
        .fitur-card:hover { transform: translateY(-5px); }
        .fitur-card .fitur-icon {
            width: 64px; height: 64px;
            border-radius: 16px;
            background: linear-gradient(135deg, #1a237e, #00897b);
            color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.7rem;
            margin-bottom: 18px;
        }
        .fitur-card h5 { font-weight: 700; color: #1a237e; margin-bottom: 10px; }
        .fitur-card p { color: #6c757d; margin: 0; font-size: 0.95rem; line-height: 1.6; }

        /* ===== KAMAR CARD ===== */
        .kamar-card {
            border: none;
            border-radius: 14px;
            box-shadow: 0 4px 18px rgba(0,0,0,0.08);
            overflow: hidden;
            transition: transform 0.25s, box-shadow 0.25s;
            height: 100%;
            background: #fff;
        }
        .kamar-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.13);
        }
        .kamar-card .badge-tersedia {
            position: absolute;
            top: 12px; right: 12px;
            background: #198754;
            color: #fff;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        .kamar-foto-placeholder {
            background: linear-gradient(135deg, #1a237e, #00897b);
            height: 190px;
            display: flex; align-items: center; justify-content: center;
            color: rgba(255,255,255,0.6);
        }
        .kamar-foto-placeholder i { font-size: 4rem; }
        .kamar-foto { height: 190px; object-fit: cover; width: 100%; }
        .price-tag { color: #00897b; font-weight: 700; }

        /* ===== CTA ===== */
        .cta-section {
            background: linear-gradient(135deg, #1a237e, #00897b);
            color: #fff;
            border-radius: 20px;
            padding: 50px 30px;
            box-shadow: 0 12px 40px rgba(26,35,126,0.25);
        }
        .cta-section h3 { font-weight: 800; margin-bottom: 12px; }
        .cta-section .btn { padding: 12px 28px; font-weight: 600; border-radius: 50px; }

        /* ===== FOOTER ===== */
        .footer-publik {
            background: #1a237e;
            color: rgba(255,255,255,0.75);
            padding: 40px 0 20px;
            margin-top: 60px;
        }
        .footer-publik h6 { color: #fff; font-weight: 700; margin-bottom: 14px; }
        .footer-publik a { color: rgba(255,255,255,0.75); text-decoration: none; }
        .footer-publik a:hover { color: #fff; }
        .footer-publik .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.15);
            padding-top: 18px;
            margin-top: 28px;
            font-size: 0.88rem;
        }
    </style>
</head>
<body>

<!-- ===== NAVBAR ===== -->
<nav class="navbar navbar-expand-lg navbar-publik sticky-top">
    <div class="container">
        <a class="navbar-brand" href="/">
            <i class="bi bi-house-fill me-2"></i><?= esc($kontak['nama_kos']) ?>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navPublik">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navPublik">
          <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                <li class="nav-item"><a class="nav-link" href="/">Beranda</a></li>
                <li class="nav-item"><a class="nav-link" href="/#fitur">Fitur</a></li>
                <li class="nav-item"><a class="nav-link" href="/kamar">Kamar Tersedia</a></li>
                <li class="nav-item"><a class="nav-link" href="/peraturan"><i class="bi bi-shield-check me-1"></i>Peraturan</a></li>
                <li class="nav-item"><a class="nav-link" href="/#kontak">Kontak</a></li>
                <?php if (session()->get('logged_in')): ?>
                    <li class="nav-item">
                        <a class="nav-link btn btn-light px-3 text-primary" href="<?= session()->get('role') === 'admin' ? '/admin/dashboard' : '/user/dashboard' ?>">
                            <i class="bi bi-speedometer2 me-1"></i>Dashboard
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="/login">Login</a></li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-outline-light px-3" href="/register">
                            <i class="bi bi-person-plus me-1"></i>Daftar
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- ===== HERO ===== -->
<section class="hero text-center">
    <div class="container">
        <span class="badge bg-light text-primary mb-3 px-3 py-2 rounded-pill">
            <i class="bi bi-stars me-1"></i>Solusi Sewa Kos Modern & Mudah
        </span>
        <h1>Selamat Datang di <?= esc($kontak['nama_kos']) ?></h1>
        <p class="lead">
            Temukan kamar kos nyaman dengan harga terbaik. Lihat ketersediaan kamar,
            ajukan sewa, bayar tagihan, dan kelola kontrak — semua dalam satu platform.
        </p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="/kamar" class="btn btn-success btn-lg">
                <i class="bi bi-door-open me-2"></i>Lihat Kamar Tersedia
            </a>
            <?php if (!session()->get('logged_in')): ?>
            <a href="/register" class="btn btn-light btn-lg text-primary">
                <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
            </a>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ===== STATISTIK ===== -->
<div class="container stat-section">
    <div class="row g-3">
        <div class="col-md-4">
            <div class="stat-card" style="background:linear-gradient(135deg,#00695c,#00897b);">
                <div class="icon-circle"><i class="bi bi-door-open"></i></div>
                <div class="stat-num"><?= $kamar_kosong ?></div>
                <div class="stat-label">Kamar Tersedia Saat Ini</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card" style="background:linear-gradient(135deg,#1a237e,#3949ab);">
                <div class="icon-circle"><i class="bi bi-building"></i></div>
                <div class="stat-num"><?= $total_kamar ?></div>
                <div class="stat-label">Total Kamar Dikelola</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card" style="background:linear-gradient(135deg,#5d4037,#795548);">
                <div class="icon-circle"><i class="bi bi-people-fill"></i></div>
                <div class="stat-num"><?= $kamar_terisi ?></div>
                <div class="stat-label">Kamar Terisi / Penghuni Aktif</div>
            </div>
        </div>
    </div>
</div>

<!-- ===== FITUR UNGGULAN ===== -->
<section id="fitur" class="py-5 mt-4">
    <div class="container">
        <div class="section-title">
            <div class="accent-line"></div>
            <h2>Kenapa Memilih Rumah Kos?</h2>
            <p>Sistem manajemen kos terpadu yang memudahkan penghuni dan pengelola dalam mengelola seluruh proses sewa.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <div class="fitur-card">
                    <div class="fitur-icon"><i class="bi bi-laptop"></i></div>
                    <h5>Pengajuan Sewa Online</h5>
                    <p>Ajukan sewa kamar kapan saja dari mana saja. Pilih tanggal mulai huni, durasi sewa, dan dapatkan konfirmasi cepat dari admin.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="fitur-card">
                    <div class="fitur-icon"><i class="bi bi-credit-card-2-front"></i></div>
                    <h5>Pembayaran Tagihan Mudah</h5>
                    <p>Lihat seluruh tagihan bulanan dan deposit dalam satu halaman. Upload bukti pembayaran, lalu tunggu verifikasi admin — selesai!</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="fitur-card">
                    <div class="fitur-icon"><i class="bi bi-shield-check"></i></div>
                    <h5>Deposit Aman & Transparan</h5>
                    <p>Setiap sewa mencatat deposit yang akan dikembalikan saat checkout. Semua riwayat transaksi terdokumentasi rapi dalam sistem.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="fitur-card">
                    <div class="fitur-icon"><i class="bi bi-arrow-left-right"></i></div>
                    <h5>Pindah Kamar & Perpanjang</h5>
                    <p>Mau pindah kamar atau perpanjang kontrak? Semua bisa diajukan lewat sistem dengan status pelacakan real-time.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="fitur-card">
                    <div class="fitur-icon"><i class="bi bi-chat-dots"></i></div>
                    <h5>Keluhan & Notifikasi</h5>
                    <p>Sampaikan keluhan fasilitas langsung ke pengelola. Dapatkan notifikasi otomatis untuk tagihan jatuh tempo dan kontrak hampir habis.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="fitur-card">
                    <div class="fitur-icon"><i class="bi bi-graph-up-arrow"></i></div>
                    <h5>Laporan Lengkap untuk Admin</h5>
                    <p>Admin dapat melihat laporan kamar, penghuni, pembayaran, hingga keluhan dalam satu dashboard yang informatif dan mudah dibaca.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== PREVIEW KAMAR ===== -->
<section class="py-5" style="background:#fff;">
    <div class="container">
        <div class="section-title">
            <div class="accent-line"></div>
            <h2>Kamar Pilihan Tersedia</h2>
            <p>Pratinjau beberapa kamar yang saat ini siap huni. Klik untuk melihat detail lengkap.</p>
        </div>
        <div class="row g-4">
            <?php if (!empty($kamar)): ?>
                <?php foreach ($kamar as $k): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card kamar-card position-relative">
                        <span class="badge-tersedia"><i class="bi bi-check-circle me-1"></i>Tersedia</span>
                        <?php if (!empty($k['foto'])): ?>
                            <img src="/uploads/<?= esc($k['foto']) ?>" alt="Kamar No. <?= esc($k['nomor_kamar']) ?>" class="kamar-foto">
                        <?php else: ?>
                            <div class="kamar-foto-placeholder"><i class="bi bi-image"></i></div>
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title fw-bold mb-1">Kamar No. <?= esc($k['nomor_kamar']) ?></h5>
                            <p class="text-muted mb-2"><small><i class="bi bi-tag me-1"></i>Kode: <?= esc($k['kode_kamar']) ?></small></p>
                            <h4 class="price-tag mb-2">Rp <?= number_format($k['harga_sewa'], 0, ',', '.') ?><small class="fs-6 text-muted fw-normal">/bulan</small></h4>
                            <p class="card-text text-muted" style="min-height:40px;">
                                <small><?= esc($k['fasilitas']) ?></small>
                            </p>
                        </div>
                        <div class="card-footer bg-transparent border-top-0 pb-3">
                            <a href="/kamar/detail/<?= $k['id_kamar'] ?>" class="btn btn-outline-success w-100">
                                <i class="bi bi-eye me-1"></i>Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info text-center py-4">
                        <i class="bi bi-info-circle fs-3 d-block mb-2"></i>
                        <h5>Belum ada kamar tersedia saat ini</h5>
                        <p class="mb-0">Silakan kembali lagi nanti atau hubungi pengelola kos.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <?php if (!empty($kamar)): ?>
        <div class="text-center mt-4">
            <a href="/kamar" class="btn btn-success btn-lg px-4">
                Lihat Semua Kamar <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- ===== CTA LOGIN / REGISTER ===== -->
<?php if (!session()->get('logged_in')): ?>
<section class="py-5">
    <div class="container">
        <div class="cta-section text-center">
            <h3>Siap Memulai Sewa Kos Online?</h3>
            <p class="mb-4 opacity-75 lead">Daftar akun sekarang untuk mengajukan sewa kamar, atau login jika sudah punya akun.</p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="/register" class="btn btn-light text-primary btn-lg">
                    <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
                </a>
                <a href="/login" class="btn btn-outline-light btn-lg">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Sudah punya akun? Login
                </a>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ===== SECTION KONTAK + MAPS (data dari Pengaturan) ===== -->
<?php $kontak = get_kontak_kos_safe(); ?>
<?php if (!empty($kontak['maps_embed']) || !empty($kontak['alamat']) || !empty($kontak['email']) || !empty($kontak['telepon']) || !empty($kontak['wa_admin'])): ?>
<section class="py-5 bg-white">
    <div class="container">
        <div class="row g-4 align-items-stretch">
            <div class="col-lg-6">
                <h3 class="fw-bold mb-3"><i class="bi bi-geo-alt-fill text-primary me-2"></i>Lokasi & Kontak</h3>
                <ul class="list-unstyled mb-0">
                    <?php if (!empty($kontak['alamat'])): ?>
                        <li class="mb-3"><i class="bi bi-geo-alt me-2 text-primary"></i> <?= esc($kontak['alamat']) ?></li>
                    <?php endif; ?>
                    <?php if (!empty($kontak['telepon'])): ?>
                        <li class="mb-3"><i class="bi bi-telephone me-2 text-primary"></i> <a href="tel:<?= esc($kontak['telepon']) ?>" class="text-decoration-none text-dark"><?= esc($kontak['telepon']) ?></a></li>
                    <?php endif; ?>
                    <?php if (!empty($kontak['email'])): ?>
                        <li class="mb-3"><i class="bi bi-envelope me-2 text-primary"></i> <a href="mailto:<?= esc($kontak['email']) ?>" class="text-decoration-none text-dark"><?= esc($kontak['email']) ?></a></li>
                    <?php endif; ?>
                    <?php if (!empty($kontak['jam_operasional'])): ?>
                        <li class="mb-3"><i class="bi bi-clock me-2 text-primary"></i> Jam Operasional: <?= esc($kontak['jam_operasional']) ?></li>
                    <?php endif; ?>
                    <?php if (!empty($kontak['wa_admin'])): ?>
                        <li class="mb-3"><a href="<?= link_wa($kontak['wa_admin'], 'Halo, saya tertarik dengan kamar kos Anda. Mohon info lebih lanjut.') ?>" target="_blank" class="btn btn-success btn-sm"><i class="bi bi-whatsapp me-1"></i>Chat WhatsApp Admin</a></li>
                    <?php endif; ?>
                    <?php if (!empty($kontak['maps_link'])): ?>
                        <li class="mb-3"><a href="<?= esc($kontak['maps_link']) ?>" target="_blank" class="btn btn-outline-primary btn-sm"><i class="bi bi-map me-1"></i>Lihat di Google Maps</a></li>
                    <?php endif; ?>
                </ul>
            </div>
            <?php if (!empty($kontak['maps_embed'])): ?>
            <div class="col-lg-6">
                <div class="ratio ratio-4x3 rounded overflow-hidden shadow-sm">
                    <iframe src="<?= esc($kontak['maps_embed']) ?>" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ===== FOOTER ===== -->
<footer class="footer-publik">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <h5 class="text-white fw-bold mb-3"><i class="bi bi-house-fill me-2"></i><?= esc($kontak['nama_kos']) ?></h5>
                <p class="mb-0"><?= esc($kontak['tagline']) ?></p>
            </div>
            <div class="col-lg-3 col-md-6">
                <h6>Menu</h6>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="/">Beranda</a></li>
                    <li class="mb-2"><a href="/#fitur">Fitur</a></li>
                    <li class="mb-2"><a href="/kamar">Kamar Tersedia</a></li>
                    <li class="mb-2"><a href="/peraturan">Peraturan Kos</a></li>
                    <li class="mb-2"><a href="/login">Login</a></li>
                    <li class="mb-2"><a href="/register">Daftar Akun</a></li>
                </ul>
            </div>
            <div class="col-lg-3 col-md-6">
                <h6>Hubungi Kami</h6>
                <ul class="list-unstyled">
                    <?php if (!empty($kontak['alamat'])): ?>
                        <li class="mb-2"><i class="bi bi-geo-alt me-2"></i><?= esc($kontak['alamat']) ?></li>
                    <?php endif; ?>
                    <?php if (!empty($kontak['telepon'])): ?>
                        <li class="mb-2"><i class="bi bi-telephone me-2"></i><?= esc($kontak['telepon']) ?></li>
                    <?php endif; ?>
                    <?php if (!empty($kontak['email'])): ?>
                        <li class="mb-2"><i class="bi bi-envelope me-2"></i><?= esc($kontak['email']) ?></li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="col-lg-2">
                <h6>Ikuti Kami</h6>
                <div class="d-flex gap-2 fs-5">
                    <?php if (!empty($kontak['instagram'])): ?>
                        <a href="<?= esc($kontak['instagram']) ?>" target="_blank" title="Instagram"><i class="bi bi-instagram"></i></a>
                    <?php endif; ?>
                    <?php if (!empty($kontak['facebook'])): ?>
                        <a href="<?= esc($kontak['facebook']) ?>" target="_blank" title="Facebook"><i class="bi bi-facebook"></i></a>
                    <?php endif; ?>
                    <?php if (!empty($kontak['tiktok'])): ?>
                        <a href="<?= esc($kontak['tiktok']) ?>" target="_blank" title="TikTok"><i class="bi bi-tiktok"></i></a>
                    <?php endif; ?>
                    <?php if (!empty($kontak['youtube'])): ?>
                        <a href="<?= esc($kontak['youtube']) ?>" target="_blank" title="YouTube"><i class="bi bi-youtube"></i></a>
                    <?php endif; ?>
                    <?php if (!empty($kontak['wa_admin'])): ?>
                        <a href="<?= link_wa($kontak['wa_admin']) ?>" target="_blank" title="WhatsApp"><i class="bi bi-whatsapp"></i></a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="footer-bottom text-center">
            &copy; <?= date('Y') ?> <?= esc(!empty($kontak['footer_text']) ? $kontak['footer_text'] : $kontak['nama_kos']) ?>. All rights reserved.
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>