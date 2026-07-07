
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Rumah Kos</title>
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
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }
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
        .btn-back i { transition: transform 0.3s ease; }
        .btn-back:hover i { transform: translateX(-3px); }
        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 25px 70px rgba(0,0,0,0.35);
            width: 100%;
            max-width: 480px;
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
            padding: 30px;
            text-align: center;
            color: white;
        }
        .card-header .logo-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 65px;
            height: 65px;
            background: rgba(255,255,255,0.15);
            border-radius: 50%;
            margin-bottom: 10px;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255,255,255,0.2);
        }
        .card-body { padding: 30px; }
        .btn-daftar {
            background: linear-gradient(135deg, #198754, #00897b);
            border: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(25,135,84,0.3);
        }
        .btn-daftar:hover {
            opacity: 0.95;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(25,135,84,0.4);
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
            <i class="bi bi-person-plus-fill" style="font-size:1.6rem;"></i>
        </div>
        <h4 class="mt-2 mb-0 fw-bold">Daftar Akun Baru</h4>
        <p class="mb-0 opacity-75 small mt-1">Isi data Anda dengan benar</p>
    </div>
    <div class="card-body">
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>Terjadi kesalahan:</strong>
                <ul class="mb-0 mt-1">
                    <?php foreach (session()->getFlashdata('errors') as $e): ?>
                        <li><?= esc($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="/register" method="post">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Lengkap</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person-vcard"></i></span>
                    <input type="text" name="nama" class="form-control" placeholder="John Doe" value="<?= esc(old('nama'), 'attr') ?>" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email" class="form-control" placeholder="email@contoh.com" value="<?= esc(old('email'), 'attr') ?>" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Username</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" name="username" class="form-control" placeholder="johndoe" value="<?= esc(old('username'), 'attr') ?>" required>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">No. HP</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-phone"></i></span>
                        <input type="text" name="no_hp" class="form-control" placeholder="08123456789" value="<?= esc(old('no_hp'), 'attr') ?>" required>
                    </div>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Min. 6 karakter" required>
                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password', this)" style="border-left:none;">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>
            <button type="submit" class="btn btn-daftar btn-success w-100 py-2 fw-semibold">
                <i class="bi bi-person-check me-2"></i>Daftar Sekarang
            </button>
        </form>
        <hr class="my-4">
        <div class="text-center">
            <p class="mb-3 small text-muted">Sudah punya akun?</p>
            <a href="/login" class="btn btn-outline-primary w-100 mb-3">
                <i class="bi bi-box-arrow-in-right me-2"></i>Masuk Sekarang
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