<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Token Invalid - Rumah Kos</title>
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
            background: linear-gradient(135deg, #dc3545, #c82333);
            border-radius: 15px 15px 0 0 !important;
            padding: 30px;
            text-align: center;
            color: white;
        }
        .card-body { padding: 30px; text-align: center; }
    </style>
</head>
<body>

<div class="card">
    <div class="card-header">
        <i class="bi bi-x-circle-fill" style="font-size:2.5rem;"></i>
        <h4 class="mt-2 mb-0 fw-bold">Token Tidak Valid</h4>
    </div>
    <div class="card-body">
        <p class="text-muted mb-4"><?= esc($message) ?></p>
        <a href="/lupa-password" class="btn btn-primary w-100 py-2 fw-semibold">
            <i class="bi bi-arrow-repeat me-2"></i>Ajukan Reset Ulang
        </a>
        <hr>
        <a href="/login" class="text-decoration-none small">
            <i class="bi bi-arrow-left me-1"></i>Kembali ke Login
        </a>
    </div>
</div>

</body>
</html>