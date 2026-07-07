<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Rumah Kos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body {
            background: linear-gradient(135deg, #1a237e 0%, #00897b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-x: hidden;
        }
        /* Background decorative blobs */
        body::before, body::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            opacity: 0.3;
            z-index: 0;
        }
        body::before {
            width: 400px; height: 400px;
            background: #00897b;
            top: -100px; left: -100px;
            animation: float 8s ease-in-out infinite;
        }
        body::after {
            width: 350px; height: 350px;
            background: #1a237e;
            bottom: -100px; right: -100px;
            animation: float 10s ease-in-out infinite reverse;
        }
        @keyframes float {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(30px, 30px); }
        }

        /* Tombol Back Keren */
        .btn-back {
            position: fixed;
            top: 25px;
            left: 25px;
            z-index: 100;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            color: #fff !important;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .btn-back:hover {
            background: rgba(255, 255, 255, 0.95);
            color: #1a237e !important;
            transform: translateX(-3px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.2);
        }
        .btn-back i {
            font-size: 1rem;
            transition: transform 0.3s ease;
        }
        .btn-back:hover i {
            transform: translateX(-3px);
        }

        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 25px 70px rgba(0,0,0,0.35);
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 1;
            animation: slideUp 0.6s ease-out;
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .card-header {
            background: linear-gradient(135deg, #1a237e, #00897b);
            border-radius: 20px 20px 0 0 !important;
            padding: 35px 30px 25px;
            text-align: center;
            color: white;
            position: relative;
        }
        .card-header .logo-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 70px;
            height: 70px;
            background: rgba(255,255,255,0.15);
            border-radius: 50%;
            margin-bottom: 10px;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255,255,255,0.2);
        }
        .card-header h4 { margin: 8px 0 0; font-weight: 700; font-size: 1.4rem; }
        .card-body { padding: 30px; }
        .btn-login {
            background: linear-gradient(135deg, #1a237e, #00897b);
            border: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(26,35,126,0.3);
        }
        .btn-login:hover {
            opacity: 0.95;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(26,35,126,0.4);
        }
        .input-group-text {
            background: #f8f9fa;
            border-right: none;
        }
        .form-control { border-left: none; }
        .form-control:focus {
            border-color: #00897b;
            box-shadow: 0 0 0 0.2rem rgba(0,137,123,0.15);
        }
        .form-control:focus + .input-group-text,
        .input-group:focus-within .input-group-text {
            border-color: #00897b;
            background: #e8f5f3;
        }
        .link-back-footer {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 50px;
            background: #f8f9fa;
            color: #1a237e;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.3s ease;
        }
        .link-back-footer:hover {
            background: #1a237e;
            color: #fff;
            transform: translateX(-3px);
        }
    </style>
</head>
<body>

<!-- Tombol Back Keren -->
<a href="/" class="btn-back">
    <i class="bi bi-arrow-left"></i>
    <span>Beranda</span>
</a>

<div class="card">
    <div class="card-header">
        <div class="logo-icon">
            <i class="bi bi-house-fill" style="font-size:1.8rem;"></i>
        </div>
        <h4>Rumah Kos</h4>
        <p class="mb-0 opacity-75 small">Silakan login untuk masuk</p>
    </div>
    <div class="card-body">
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger d-flex align-items-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <div><?= session()->getFlashdata('error') ?></div>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                <div><?= session()->getFlashdata('success') ?></div>
            </div>
        <?php endif; ?>

        <form action="/login" method="post">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label class="form-label fw-semibold">Username</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input type="text" name="username" class="form-control" placeholder="Masukkan username" required autofocus>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password" required>
                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password', this)" style="border-left:none;">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>
            <div class="d-flex justify-content-end mb-3">
                <a href="/lupa-password" class="text-decoration-none small fw-semibold">
                    <i class="bi bi-key me-1"></i>Lupa Password?
                </a>
            </div>
            <button type="submit" class="btn btn-login btn-primary w-100 py-2 fw-semibold">
                <i class="bi bi-box-arrow-in-right me-2"></i>Masuk Sekarang
            </button>
        </form>
        <hr class="my-4">
        <div class="text-center">
            <p class="mb-3 small text-muted">Belum punya akun?</p>
            <a href="/register" class="btn btn-outline-success w-100 mb-3">
                <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
            </a>
            <a href="/" class="link-back-footer">
                <i class="bi bi-arrow-left"></i>
                <span>Kembali ke Beranda</span>
            </a>
        </div>
    </div>
</div>

<script>
function togglePassword(id, btn) {
    const input = document.getElementById(id);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'bi bi-eye';
    }
}
</script>

</body>
</html>