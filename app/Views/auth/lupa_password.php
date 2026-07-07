<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Rumah Kos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1a237e 0%, #00897b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 450px;
        }
        .card-header {
            background: linear-gradient(135deg, #1a237e, #00897b);
            border-radius: 15px 15px 0 0 !important;
            padding: 30px;
            text-align: center;
            color: white;
        }
        .card-body { padding: 30px; }
    </style>
</head>
<body>
<a href="/" class="position-fixed top-0 start-0 m-3 btn btn-sm btn-light">
    <i class="bi bi-arrow-left me-1"></i>Beranda
</a>

<div class="card">
    <div class="card-header">
        <i class="bi bi-key-fill" style="font-size:2.5rem;"></i>
        <h4 class="mt-2 mb-0 fw-bold">Lupa Password?</h4>
        <p class="mb-0 opacity-75 mt-2 small">Masukkan email Anda untuk reset password</p>
    </div>
    <div class="card-body">
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle me-2"></i><?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            Masukkan email yang Anda pakai saat registrasi. Sistem akan generate link reset password yang berlaku 1 jam.
        </div>

        <form action="/lupa-password/kirim" method="post">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label class="form-label fw-semibold">Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email" class="form-control" placeholder="email@contoh.com" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                <i class="bi bi-send me-2"></i>Kirim Link Reset
            </button>
        </form>
        <hr>
        <p class="text-center mb-2"><a href="/login" class="text-decoration-none">
            <i class="bi bi-arrow-left me-1"></i>Kembali ke Login
        </a></p>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>