<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<style media="print">
    @page { size: A4 portrait; margin: 10mm; }
    .no-print, .sidebar, .topbar { display: none !important; }
    .main-content { margin-left: 0 !important; padding: 0 !important; width: 100% !important; }
    body { background: white !important; font-family: Arial, sans-serif; }
    .card { border: none !important; box-shadow: none !important; }
    .print-header { display: flex !important; border-bottom: 3px solid #1a237e; padding-bottom: 10px; margin-bottom: 15px; justify-content: space-between; align-items: flex-start; }
    .print-footer { display: block !important; margin-top: 20px; border-top: 1px solid #ddd; padding-top: 8px; text-align: center; font-size: 10px; color: #888; }
    .table { width: 100% !important; border-collapse: collapse !important; }
    .table th, .table td { border: 1px solid #000 !important; color: #000 !important; padding: 5px !important; font-size: 10px; }
    .table-dark th { background-color: #1a237e !important; color: #fff !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .badge { border: 1px solid #000; padding: 2px 6px; font-size: 9px; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .stat-box { background-color: #f8f9fa !important; border: 1px solid #999 !important; border-radius: 5px !important; padding: 8px !important; text-align: center !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
</style>

<style media="screen">
    .print-header, .print-footer { display: none; }
    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 18px;
        background: #fff;
        color: #374151;
        border: 1.5px solid #d1d5db;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.875rem;
        text-decoration: none;
        transition: background 0.2s, border-color 0.2s, color 0.2s;
    }
    .btn-back:hover {
        background: #f3f4f6;
        border-color: #9ca3af;
        color: #111827;
    }
    .btn-export {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 18px;
        background: #16a34a;
        color: #fff;
        border: none;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.875rem;
        text-decoration: none;
        transition: background 0.2s;
    }
    .btn-export:hover { background: #15803d; color: #fff; }
    .btn-print {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 18px;
        background: #1d4ed8;
        color: #fff;
        border: none;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.875rem;
        cursor: pointer;
        transition: background 0.2s;
    }
    .btn-print:hover { background: #1e40af; }
</style>

<div class="print-header">
    <div>
        <h2 style="margin:0; color:#1a237e; font-weight: bold;">RUMAH KOS</h2>
        <p style="margin:0; font-size: 11px; color: #555;">Jl. Contoh Alamat No. 123, Kota Contoh</p>
        <p style="margin:0; font-size: 11px; color: #555;">Telp: 0812-3456-7890 | Email: admin@rumahkos.id</p>
    </div>
    <div style="text-align: right;">
        <h3 style="margin:0; font-weight: bold;">LAPORAN PEMBAYARAN</h3>
        <p style="margin:0; font-size: 11px;">Dicetak: <?= date('d M Y H:i') ?></p>
    </div>
</div>

<div class="card border-0 shadow-sm mb-3 no-print">
    <div class="card-body d-flex justify-content-between align-items-center py-3">
        <a href="/admin/laporan" class="btn-back">
            <i class="bi bi-arrow-left-circle"></i> Kembali
        </a>
        <div class="d-flex gap-2">
            <a href="/admin/laporan/export-pembayaran" class="btn-export"><i class="bi bi-file-earmark-excel"></i> Export Excel</a>
            <button onclick="window.print()" class="btn-print"><i class="bi bi-printer"></i> Cetak / PDF</button>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-primary text-white text-center py-3 no-print">
        <h5 class="mb-0 fw-bold"><i class="bi bi-cash-coin me-2"></i>LAPORAN PEMBAYARAN</h5>
        <small>Rumah Kos &middot; Dicetak: <?= date('d M Y H:i') ?></small>
    </div>
    <div class="card-body">
        <div style="display: flex; flex-direction: row; flex-wrap: nowrap; gap: 8px; margin-bottom: 15px; width: 100%;">
            <div style="flex: 1 1 25%; max-width: 25%; min-width: 0;">
                <div class="stat-box" style="background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 8px; padding: 12px; text-align: center;">
                    <div style="font-size: 22px; font-weight: bold; color: #0d6efd; margin-bottom: 2px;"><?= (string)$total ?></div>
                    <small style="color: #6c757d; font-size: 11px;">Total Transaksi</small>
                </div>
            </div>
            <div style="flex: 1 1 25%; max-width: 25%; min-width: 0;">
                <div class="stat-box" style="background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 8px; padding: 12px; text-align: center;">
                    <div style="font-size: 22px; font-weight: bold; color: #198754; margin-bottom: 2px;"><?= (string)$lunas ?></div>
                    <small style="color: #6c757d; font-size: 11px;">Sudah Lunas</small>
                </div>
            </div>
            <div style="flex: 1 1 25%; max-width: 25%; min-width: 0;">
                <div class="stat-box" style="background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 8px; padding: 12px; text-align: center;">
                    <div style="font-size: 14px; font-weight: bold; color: #198754; margin-bottom: 2px;">Rp <?= number_format((int)$total_pemasukan,0,',','.') ?></div>
                    <small style="color: #6c757d; font-size: 11px;">Total Pemasukan</small>
                </div>
            </div>
            <div style="flex: 1 1 25%; max-width: 25%; min-width: 0;">
                <div class="stat-box" style="background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 8px; padding: 12px; text-align: center;">
                    <div style="font-size: 14px; font-weight: bold; color: #dc3545; margin-bottom: 2px;">Rp <?= number_format((int)$total_tunggakan,0,',','.') ?></div>
                    <small style="color: #6c757d; font-size: 11px;">Total Tunggakan</small>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-dark text-center">
                    <tr><th>No</th><th>Penghuni</th><th>Kamar</th><th>Bulan Ke</th><th>Jumlah</th><th>Jatuh Tempo</th><th>Tgl Bayar</th><th>Status</th></tr>
                </thead>
                <tbody>
                    <?php $no=1; foreach ($pembayaran as $p): ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td><strong><?= esc($p['nama_user'] ?? $p['nama'] ?? '-') ?></strong></td>
                        <td class="text-center">No. <?= esc($p['nomor_kamar'] ?? '-') ?></td>
                        <td class="text-center"><?= label_bulan_ke($p['bulan_ke']) ?></td>
                        <td class="text-end fw-bold">Rp <?= number_format($p['jumlah_bayar'],0,',','.') ?></td>
                        <td class="text-center"><small><?= esc($p['tanggal_jatuh_tempo'] ?? '-') ?></small></td>
                        <td class="text-center"><small><?= esc($p['tanggal_bayar'] ?? '-') ?></small></td>
                        <td class="text-center">
                            <?php if ($p['status'] == 'lunas'): ?><span class="badge bg-success">Lunas</span>
                            <?php elseif ($p['status'] == 'menunggu_verifikasi'): ?><span class="badge bg-info">Verifikasi</span>
                            <?php else: ?><span class="badge bg-danger">Belum Bayar</span><?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="print-footer"><p>&copy; <?= date('Y') ?> Rumah Kos - Sistem Informasi Manajemen Kos</p></div>

<?= $this->endSection() ?>