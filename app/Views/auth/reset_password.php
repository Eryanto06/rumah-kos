<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Rumah Kos</title>
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

<div class="card">
    <div class="card-header">
        <i class="bi bi-shield-lock-fill" style="font-size:2.5rem;"></i>
        <h4 class="mt-2 mb-0 fw-bold">Reset Password</h4>
        <p class="mb-0 opacity-75 mt-2 small">Untuk akun: <?= esc($email) ?></p>
    </div>
    <div class="card-body">
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle me-2"></i><?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <form action="/reset-password/update" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="token" value="<?= esc($token) ?>">

            <div class="mb-3">
                <label class="form-label fw-semibold">Password Baru</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Min. 6 karakter" required minlength="6">
                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password', this)">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Konfirmasi Password Baru</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                    <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Ulangi password" required>
                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('confirm_password', this)">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>

            <div class="form-text mb-3">
                <i class="bi bi-info-circle"></i> Password minimal 6 karakter. Gunakan kombinasi huruf, angka, dan simbol untuk keamanan.
            </div>

            <button type="submit" class="btn btn-success w-100 py-2 fw-semibold">
                <i class="bi bi-check-circle me-2"></i>Ubah Password
            </button>
        </form>
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