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
        }
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
        .hero {
            background: linear-gradient(135deg, rgba(26,35,126,0.9), rgba(0,137,123,0.9));
            color: #fff;
            padding: 50px 20px;
            border-radius: 0 0 30px 30px;
            margin-bottom: 30px;
        }
        .stat-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.07);
            transition: transform 0.2s;
        }
        .stat-card:hover { transform: translateY(-3px); }
        .kamar-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
            height: 100%;
        }
        .kamar-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }
        .kamar-card .badge-tersedia {
            position: absolute;
            top: 12px;
            right: 12px;
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
            height: 180px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255,255,255,0.6);
        }
        .kamar-foto-placeholder i { font-size: 4rem; }
        .kamar-foto {
            height: 180px;
            object-fit: cover;
            width: 100%;
        }
        .price-tag {
            color: #00897b;
            font-weight: 700;
        }
        .footer-publik {
            background: #1a237e;
            color: rgba(255,255,255,0.7);
            padding: 30px 0;
            margin-top: 50px;
        }
    </style>
</head>
<body>

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
                <li class="nav-item"><a class="nav-link" href="/peraturan"><i class="bi bi-shield-check me-1"></i>Peraturan</a></li>
                <li class="nav-item">
                    <a class="nav-link active" href="/kamar"><i class="bi bi-door-open me-1"></i>Kamar Tersedia</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-outline-light px-3" href="/login">
                        <i class="bi bi-box-arrow-in-right me-1"></i>Login / Daftar
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="hero">
    <div class="container text-center">
        <h1 class="fw-bold mb-2">Selamat Datang di Rumah Kos</h1>
        <p class="mb-0 opacity-75 lead">Temukan kamar kos nyaman dengan harga terbaik. Lihat ketersediaan kamar tanpa perlu login.</p>
    </div>
</div>

<div class="container">

    <!-- Statistik Ringkas -->
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card stat-card text-white" style="background:linear-gradient(135deg,#00695c,#00897b);">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fs-3 fw-bold"><?= $kamar_kosong ?></div>
                        <div class="opacity-75">Kamar Tersedia</div>
                    </div>
                    <i class="bi bi-door-open fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card stat-card text-white" style="background:linear-gradient(135deg,#1a237e,#3949ab);">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fs-3 fw-bold"><?= $total_kamar ?></div>
                        <div class="opacity-75">Total Kamar</div>
                    </div>
                    <i class="bi bi-building fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <h4 class="fw-bold mb-3"><i class="bi bi-door-open me-2 text-success"></i>Daftar Kamar Tersedia</h4>

    <div class="row g-4">
        <?php if (!empty($kamar)): ?>
            <?php foreach ($kamar as $k): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card kamar-card position-relative">
                    <span class="badge-tersedia"><i class="bi bi-check-circle me-1"></i>Tersedia</span>

                    <?php if (!empty($k['foto'])): ?>
                        <img src="/uploads/<?= esc($k['foto']) ?>" alt="Foto Kamar No. <?= esc($k['nomor_kamar']) ?>" class="kamar-foto">
                    <?php else: ?>
                        <div class="kamar-foto-placeholder">
                            <i class="bi bi-image"></i>
                        </div>
                    <?php endif; ?>

                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-1">Kamar No. <?= esc($k['nomor_kamar']) ?></h5>
                        <p class="text-muted mb-2"><small><i class="bi bi-tag me-1"></i>Kode: <?= esc($k['kode_kamar']) ?></small></p>
                        <h4 class="price-tag mb-2">Rp <?= number_format($k['harga_sewa'], 0, ',', '.') ?><small class="fs-6 text-muted fw-normal">/bulan</small></h4>
                        <p class="card-text text-muted" style="min-height: 40px;">
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

    <!-- CTA Login -->
    <?php if (!session()->get('logged_in')): ?>
    <div class="card mt-5 border-0 shadow" style="background:linear-gradient(135deg,#1a237e,#00897b);">
        <div class="card-body p-4 text-center text-white">
            <h4 class="fw-bold mb-2">Tertarik menyewa?</h4>
            <p class="mb-3 opacity-75">Login atau daftar akun untuk mengajukan sewa kamar secara online.</p>
            <a href="/register" class="btn btn-light me-2 px-4"><i class="bi bi-person-plus me-1"></i>Daftar Sekarang</a>
            <a href="/login" class="btn btn-outline-light px-4"><i class="bi bi-box-arrow-in-right me-1"></i>Sudah punya akun? Login</a>
        </div>
    </div>
    <?php endif; ?>

</div>

<footer class="footer-publik text-center">
    <div class="container">
        <p class="mb-1"><i class="bi bi-house-fill me-1"></i><strong>Rumah Kos</strong> - Sistem Informasi Manajemen Kos</p>
        <p class="mb-0 small">
            <a href="/" class="text-white text-decoration-none">Beranda</a> &bull;
            <a href="/kamar" class="text-white text-decoration-none">Kamar Tersedia</a> &bull;
            <a href="/login" class="text-white text-decoration-none">Login</a> &bull;
            &copy; <?= date('Y') ?> Rumah Kos. All rights reserved.
        </p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>