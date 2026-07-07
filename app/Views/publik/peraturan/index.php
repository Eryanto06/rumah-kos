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
        .hero {
            background: linear-gradient(135deg, rgba(26,35,126,0.92), rgba(0,137,123,0.92));
            color: #fff;
            padding: 50px 20px;
            border-radius: 0 0 30px 30px;
            margin-bottom: 30px;
        }
        .peraturan-card {
            border: none;
            border-radius: 14px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            overflow: hidden;
            margin-bottom: 20px;
        }
        .peraturan-card .card-header {
            border-radius: 14px 14px 0 0 !important;
            font-weight: 700;
            padding: 15px 20px;
        }
        .peraturan-item {
            padding: 15px 20px;
            border-bottom: 1px solid #f0f0f0;
        }
        .peraturan-item:last-child {
            border-bottom: none;
        }
        .peraturan-item h6 {
            font-weight: 700;
            color: #1a237e;
            margin-bottom: 5px;
        }
        .peraturan-item p {
            color: #555;
            margin: 0;
            font-size: 0.95rem;
            line-height: 1.6;
        }
        .footer-publik {
            background: #1a237e;
            color: rgba(255,255,255,0.7);
            padding: 30px 0;
            margin-top: 50px;
        }
        .kategori-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
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
                <li class="nav-item"><a class="nav-link" href="/kamar">Kamar</a></li>
                <li class="nav-item"><a class="nav-link active" href="/peraturan">Peraturan</a></li>
                <li class="nav-item">
                    <a class="nav-link btn btn-outline-light px-3" href="/login">Login / Daftar</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="hero text-center">
    <div class="container">
        <h1 class="fw-bold mb-2"><i class="bi bi-shield-check me-2"></i>Peraturan Kos</h1>
        <p class="mb-0 opacity-75 lead">Berikut peraturan yang berlaku di Rumah Kos. Wajib dibaca & dipatuhi semua penghuni.</p>
    </div>
</div>

<div class="container">

    <!-- Info Box -->
    <div class="alert alert-info border-0 shadow-sm">
        <div class="d-flex align-items-center gap-3">
            <i class="bi bi-info-circle-fill fs-2 text-info"></i>
            <div>
                <strong>Total <?= $total ?> peraturan aktif.</strong>
                <br><small class="text-muted">Dengan menyewa kamar di Rumah Kos, Anda dianggap setuju & mematuhi semua peraturan di bawah ini.</small>
            </div>
        </div>
    </div>

    <?php
    $kategoriConfig = [
        'umum' => ['label' => '📋 Peraturan Umum', 'bg' => 'primary'],
        'jam_operasional' => ['label' => '⏰ Jam Operasional', 'bg' => 'info'],
        'fasilitas' => ['label' => '🏠 Fasilitas', 'bg' => 'success'],
        'keamanan' => ['label' => '🔒 Keamanan', 'bg' => 'danger'],
        'pembayaran' => ['label' => '💰 Pembayaran', 'bg' => 'warning'],
        'tamu' => ['label' => '👥 Tamu', 'bg' => 'secondary'],
        'larangan' => ['label' => '🚫 Larangan', 'bg' => 'danger'],
        'lainnya' => ['label' => '📌 Lainnya', 'bg' => 'secondary'],
    ];
    ?>

    <?php if (!empty($peraturan)): ?>
        <?php foreach ($peraturan as $kategori => $items): ?>
            <?php $config = $kategoriConfig[$kategori] ?? ['label' => esc(ucfirst($kategori)), 'bg' => 'secondary']; ?>
            <div class="card peraturan-card">
                <div class="card-header bg-<?= $config['bg'] ?> text-white">
                    <i class="bi bi-shield-fill me-2"></i><?= $config['label'] ?>
                    <span class="badge bg-light text-dark ms-2"><?= count($items) ?> peraturan</span>
                </div>
                <div class="card-body p-0">
                    <?php $no = 1; foreach ($items as $p): ?>
                        <div class="peraturan-item">
                            <h6>
                                <span class="badge bg-<?= $config['bg'] ?> me-2"><?= $no++ ?></span>
                                <?= esc($p['judul']) ?>
                            </h6>
                            <p><?= nl2br(esc($p['isi'])) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="alert alert-warning text-center py-5">
            <i class="bi bi-exclamation-triangle fs-1 d-block mb-3"></i>
            <h4>Belum Ada Peraturan</h4>
            <p class="mb-0">Peraturan kos belum dipublikasikan. Silakan hubungi admin untuk informasi lebih lanjut.</p>
        </div>
    <?php endif; ?>

    <!-- CTA Login/Register -->
    <?php if (!session()->get('logged_in')): ?>
    <div class="card mt-5 border-0 shadow" style="background:linear-gradient(135deg,#1a237e,#00897b);">
        <div class="card-body p-4 text-center text-white">
            <h4 class="fw-bold mb-2">Setuju & Ingin Sewa Kamar?</h4>
            <p class="mb-3 opacity-75">Daftar akun sekarang untuk mulai mengajukan sewa kamar di Rumah Kos.</p>
            <a href="/register" class="btn btn-light me-2 px-4"><i class="bi bi-person-plus me-1"></i>Daftar Sekarang</a>
            <a href="/login" class="btn btn-outline-light px-4"><i class="bi bi-box-arrow-in-right me-1"></i>Sudah punya akun? Login</a>
        </div>
    </div>
    <?php endif; ?>

</div>

<footer class="footer-publik text-center">
    <div class="container">
        <p class="mb-1"><i class="bi bi-house-fill me-1"></i><strong>Rumah Kos</strong> - Sistem Informasi Manajemen Kos</p>
        <p class="mb-0 small">&copy; <?= date('Y') ?> Rumah Kos. All rights reserved.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>