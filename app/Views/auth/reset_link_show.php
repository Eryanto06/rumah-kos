<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Link Reset Dibuat - Rumah Kos</title>
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
            max-width: 550px;
        }
        .card-header {
            background: linear-gradient(135deg, #198754, #00897b);
            border-radius: 15px 15px 0 0 !important;
            padding: 25px;
            text-align: center;
            color: white;
        }
        .card-body { padding: 30px; }
        .link-box {
            background: #f8f9fa;
            border: 2px dashed #198754;
            padding: 15px;
            border-radius: 8px;
            word-break: break-all;
            font-family: monospace;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>

<div class="card">
    <div class="card-header">
        <i class="bi bi-check-circle-fill" style="font-size:2.5rem;"></i>
        <h4 class="mt-2 mb-0 fw-bold">Link Reset Berhasil Dibuat!</h4>
    </div>
    <div class="card-body">
        <p>Halo <strong><?= esc($nama) ?></strong>,</p>
        <p>Link reset password untuk email <strong><?= esc($email) ?></strong> telah dibuat. Link berlaku selama <strong>1 jam</strong>.</p>

        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>Mode Demo:</strong> Karena server belum dikonfigurasi SMTP email, link reset ditampilkan langsung di bawah ini. Di production, link ini akan dikirim ke email user.
        </div>

        <label class="form-label fw-semibold"><i class="bi bi-link-45deg me-1"></i>Link Reset Password:</label>
        <div class="link-box mb-3"><?= esc($resetLink) ?></div>

        <div class="d-grid gap-2">
            <a href="<?= esc($resetLink) ?>" class="btn btn-success py-2 fw-semibold">
                <i class="bi bi-key me-2"></i>Klik di Sini untuk Reset Password
            </a>
            <a href="/login" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Kembali ke Login
            </a>
        </div>
    </div>
</div>

</body>
</html>