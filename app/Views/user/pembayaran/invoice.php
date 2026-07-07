<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #<?= esc($pembayaran['id_pembayaran']) ?> - Rumah Kos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: #f0f2f5;
            font-family: 'Segoe UI', system-ui, sans-serif;
            padding: 20px 0;
        }
        .invoice-wrapper {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            border-radius: 12px;
            overflow: hidden;
        }
        .invoice-header {
            background: linear-gradient(135deg, #1a237e, #00897b);
            color: white;
            padding: 30px 40px;
        }
        .invoice-body {
            padding: 40px;
        }
        .invoice-footer {
            padding: 20px 40px;
            background: #f8f9fa;
            border-top: 1px solid #eee;
            text-align: center;
            color: #6c757d;
            font-size: 0.85rem;
        }
        .table-invoice th {
            background: #f8f9fa;
            font-weight: 600;
        }
        .total-row {
            background: #e8f5e9 !important;
            font-weight: 700;
            font-size: 1.1rem;
        }
        .no-print {
            margin-bottom: 20px;
        }
        @media print {
            body { background: white; padding: 0; }
            .no-print { display: none !important; }
            .invoice-wrapper { box-shadow: none; border-radius: 0; max-width: 100%; }
            .invoice-header { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body>

<!-- TOMBOL AKSI (Hilang saat print) -->
<div class="container no-print">
    <div class="d-flex justify-content-between align-items-center mb-3" style="max-width:800px;margin:0 auto;">
        <a href="/user/pembayaran" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
        <button onclick="window.print()" class="btn btn-primary btn-sm">
            <i class="bi bi-printer me-1"></i>Print / Save PDF
        </button>
    </div>
</div>

<div class="invoice-wrapper">
    <!-- HEADER INVOICE -->
    <div class="invoice-header">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h3 class="fw-bold mb-0"><i class="bi bi-house-fill me-2"></i>RUMAH KOS</h3>
                <p class="mb-0 opacity-75 small">Sistem Informasi Manajemen Kos</p>
            </div>
            <div class="text-end">
                <h4 class="fw-bold mb-0">INVOICE</h4>
                <p class="mb-0 opacity-75">No: INV-<?= date('Ym', strtotime($pembayaran['tanggal_bayar'])) ?>-<?= str_pad($pembayaran['id_pembayaran'], 4, '0', STR_PAD_LEFT) ?></p>
            </div>
        </div>
    </div>

    <!-- BODY INVOICE -->
    <div class="invoice-body">
        <div class="row mb-4">
            <div class="col-md-6">
                <h6 class="text-muted text-uppercase fw-bold mb-2">Ditagihkan Kepada</h6>
                <p class="mb-1"><strong><?= esc($pembayaran['nama']) ?></strong></p>
                <p class="mb-1 text-muted small"><?= esc($pembayaran['no_hp']) ?></p>
                <p class="mb-0 text-muted small"><?= esc($pembayaran['email']) ?></p>
            </div>
            <div class="col-md-6 text-md-end">
                <h6 class="text-muted text-uppercase fw-bold mb-2">Detail Sewa</h6>
                <p class="mb-1"><strong>Kamar No. <?= esc($pembayaran['nomor_kamar']) ?></strong> (<?= esc($pembayaran['kode_kamar']) ?>)</p>
                <p class="mb-1 text-muted small">Mulai: <?= date('d M Y', strtotime($pembayaran['tanggal_mulai'])) ?></p>
                <p class="mb-0 text-muted small">Selesai: <?= date('d M Y', strtotime($pembayaran['tanggal_selesai'])) ?></p>
            </div>
        </div>

        <!-- ====== FIX BUG: BAGIAN PEMBAYARAN KE (rekening kos) ====== -->
        <?php $metodeBayar = get_metode_pembayaran_safe(); ?>
        <?php if ($metodeBayar['ada']): ?>
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-info border-info">
                    <h6 class="text-uppercase fw-bold mb-3"><i class="bi bi-bank me-1"></i>Pembayaran Ke (Rekening Rumah Kos)</h6>
                    <?php if (!empty($metodeBayar['banks'])): ?>
                        <div class="row">
                            <?php foreach ($metodeBayar['banks'] as $b): ?>
                                <div class="col-md-6 mb-2">
                                    <div class="border rounded p-2 bg-white">
                                        <span class="badge bg-primary"><?= esc($b['nama']) ?></span>
                                        <div class="fw-bold fs-5" style="font-family:monospace;letter-spacing:1px;"><?= esc($b['rekening']) ?></div>
                                        <small class="text-muted">a.n. <strong><?= esc($b['pemilik'] ?: '-') ?></strong></small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($metodeBayar['ewallets'])): ?>
                        <div class="mt-2">
                            <small class="text-muted d-block mb-1">E-Wallet:</small>
                            <?php foreach ($metodeBayar['ewallets'] as $e): ?>
                                <span class="badge bg-success me-2 mb-1"><?= esc($e['type']) ?>: <?= esc($e['nomor']) ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($metodeBayar['instruksi'])): ?>
                        <hr class="my-2">
                        <small class="text-muted"><i class="bi bi-info-circle me-1"></i><?= esc($metodeBayar['instruksi']) ?></small>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <!-- ====== END PEMBAYARAN KE ====== -->

        <hr class="my-4">

        <h6 class="text-muted text-uppercase fw-bold mb-3">Rincian Pembayaran</h6>
        <table class="table table-bordered table-invoice">
            <thead>
                <tr>
                    <th width="60">No</th>
                    <th>Deskripsi</th>
                    <th class="text-end">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>
                        <strong>
                        <?php if ($pembayaran['bulan_ke'] == 0): ?>
                            Deposit Awal Sewa
                        <?php else: ?>
                            Sewa Kamar Bulan Ke-<?= $pembayaran['bulan_ke'] ?>
                        <?php endif; ?>
                        </strong>
                        <br>
                        <small class="text-muted">Dibayar pada: <?= date('d M Y', strtotime($pembayaran['tanggal_bayar'])) ?></small>
                    </td>
                    <td class="text-end">Rp <?= number_format($pembayaran['jumlah_bayar'], 0, ',', '.') ?></td>
                </tr>
                
                <?php if (($pembayaran['total_denda'] ?? 0) > 0): ?>
                <tr>
                    <td>2</td>
                    <td>
                        <strong>Denda Keterlambatan</strong>
                        <br>
                        <small class="text-muted">Terlambat pembayaran</small>
                    </td>
                    <td class="text-end text-danger">Rp <?= number_format($pembayaran['total_denda'], 0, ',', '.') ?></td>
                </tr>
                <?php endif; ?>

                <tr class="total-row">
                    <td colspan="2" class="text-end">TOTAL DIBAYAR</td>
                    <td class="text-end text-success">
                        Rp <?= number_format($pembayaran['jumlah_bayar'] + ($pembayaran['total_denda'] ?? 0), 0, ',', '.') ?>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="alert alert-success border-0 bg-success bg-opacity-10">
                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                    <strong>LUNAS</strong><br>
                    <small class="text-muted">Pembayaran telah diverifikasi oleh admin pada <?= date('d M Y H:i', strtotime($pembayaran['tanggal_bayar'])) ?> WIB.</small>
                </div>
            </div>
            <div class="col-md-6 text-md-end">
                <p class="text-muted small mb-1">Hormat Kami,</p>
                <h6 class="fw-bold">Admin Rumah Kos</h6>
                <div style="border-bottom: 1px solid #ddd; width: 150px; margin-left: auto; margin-top: 20px;"></div>
            </div>
        </div>
    </div>

    <!-- FOOTER INVOICE -->
    <div class="invoice-footer">
        <p class="mb-0">Invoice ini diterbitkan oleh sistem Rumah Kos secara otomatis.</p>
        <p class="mb-0">&copy; <?= date('Y') ?> Rumah Kos. Simpan invoice ini sebagai bukti pembayaran.</p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>