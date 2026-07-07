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
    .kategori-box { background-color: #f8f9fa !important; border: 1px solid #ccc !important; border-radius: 5px !important; padding: 8px !important; margin-bottom: 12px !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
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
        <h3 style="margin:0; font-weight: bold;">LAPORAN KELUHAN</h3>
        <p style="margin:0; font-size: 11px;">Dicetak: <?= date('d M Y H:i') ?></p>
    </div>
</div>

<div class="card border-0 shadow-sm mb-3 no-print">
    <div class="card-body d-flex justify-content-between align-items-center py-3">
        <a href="/admin/laporan" class="btn-back">
            <i class="bi bi-arrow-left-circle"></i> Kembali
        </a>
        <div class="d-flex gap-2">
            <a href="/admin/laporan/export-keluhan" class="btn-export"><i class="bi bi-file-earmark-excel"></i> Export Excel</a>
            <button onclick="window.print()" class="btn-print"><i class="bi bi-printer"></i> Cetak / PDF</button>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-primary text-white text-center py-3 no-print">
        <h5 class="mb-0 fw-bold"><i class="bi bi-chat-dots me-2"></i>LAPORAN KELUHAN</h5>
        <small>Rumah Kos &middot; Dicetak: <?= date('d M Y H:i') ?></small>
    </div>
    <div class="card-body">
        <div style="display: flex; flex-direction: row; flex-wrap: nowrap; gap: 8px; margin-bottom: 15px; width: 100%;">
            <div style="flex: 1 1 25%; max-width: 25%; min-width: 0;">
                <div class="stat-box" style="background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 8px; padding: 12px; text-align: center;">
                    <div style="font-size: 22px; font-weight: bold; color: #0d6efd; margin-bottom: 2px;"><?= (string)$total ?></div>
                    <small style="color: #6c757d; font-size: 11px;">Total Keluhan</small>
                </div>
            </div>
            <div style="flex: 1 1 25%; max-width: 25%; min-width: 0;">
                <div class="stat-box" style="background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 8px; padding: 12px; text-align: center;">
                    <div style="font-size: 22px; font-weight: bold; color: #fd7e14; margin-bottom: 2px;"><?= (string)$menunggu ?></div>
                    <small style="color: #6c757d; font-size: 11px;">Menunggu</small>
                </div>
            </div>
            <div style="flex: 1 1 25%; max-width: 25%; min-width: 0;">
                <div class="stat-box" style="background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 8px; padding: 12px; text-align: center;">
                    <div style="font-size: 22px; font-weight: bold; color: #0dcaf0; margin-bottom: 2px;"><?= (string)$diproses ?></div>
                    <small style="color: #6c757d; font-size: 11px;">Diproses</small>
                </div>
            </div>
            <div style="flex: 1 1 25%; max-width: 25%; min-width: 0;">
                <div class="stat-box" style="background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 8px; padding: 12px; text-align: center;">
                    <div style="font-size: 22px; font-weight: bold; color: #198754; margin-bottom: 2px;"><?= (string)$selesai ?></div>
                    <small style="color: #6c757d; font-size: 11px;">Selesai</small>
                </div>
            </div>
        </div>

        <?php
        $katLabels = [
            'fasilitas_kamar' => 'Fasilitas Kamar', 'listrik_air' => 'Listrik & Air', 'wifi' => 'Wi-Fi', 
            'kebersihan' => 'Kebersihan', 'parkir' => 'Parkir', 'kebisingan' => 'Kebisingan', 
            'tetangga' => 'Tetangga', 'keamanan' => 'Keamanan', 'lainnya' => 'Lainnya',
            'kendala_akun' => 'Kendala Akun', 'website_bug' => 'Bug Website', 
            'status_sewa' => 'Status Sewa', 'info_kamar' => 'Info Kamar', 'tagihan_sewa' => 'Tagihan Sewa',
        ];
        ?>
        <div class="kategori-box" style="background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 8px; padding: 10px; margin-bottom: 15px;">
            <h6 class="fw-bold mb-2"><i class="bi bi-bar-chart me-2"></i>Statistik per Kategori</h6>
            <div class="d-flex flex-wrap gap-2">
                <?php
                if (!empty($per_kategori)) {
                    foreach ($per_kategori as $kat => $jumlah) {
                        $katStr = (string)$kat;
                        $jumlahInt = (int)$jumlah;
                        $label = isset($katLabels[$katStr]) ? $katLabels[$katStr] : esc(ucfirst($katStr));
                        echo '<span class="badge bg-secondary fs-6">' . htmlspecialchars((string)$label) . ': ' . $jumlahInt . '</span>';
                    }
                } else {
                    echo '<small class="text-muted">Tidak ada data</small>';
                }
                ?>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-dark text-center">
                    <tr><th>No</th><th>Tanggal</th><th>Pelapor</th><th>Kategori</th><th>Judul</th><th>Prioritas</th><th>Status</th></tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($keluhan as $k): ?>
                    <?php
                        $tglRaw = isset($k['tanggal']) ? $k['tanggal'] : '';
                        if ($tglRaw instanceof \DateTime) { $tglStr = $tglRaw->format('Y-m-d'); } else { $tglStr = (string)$tglRaw; }
                        $katKey   = (string)(isset($k['kategori']) ? $k['kategori'] : '');
                        $katBadge = isset($katLabels[$katKey]) ? $katLabels[$katKey] : esc(ucfirst($katKey));
                        $namaUser = (string)(isset($k['nama_user']) ? $k['nama_user'] : '-');
                        $judul    = (string)(isset($k['judul']) ? $k['judul'] : '');
                        $prioritas= (string)(isset($k['prioritas']) ? $k['prioritas'] : 'normal');
                        $status   = (string)(isset($k['status']) ? $k['status'] : 'menunggu');
                        $isPrivate= !empty($k['is_private']);
                    ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td class="text-center"><small><?= htmlspecialchars($tglStr) ?></small></td>
                        <td><?php if ($isPrivate): ?><span class="text-danger"><i class="bi bi-incognito"></i> Anonim</span><?php else: ?><?= htmlspecialchars($namaUser) ?><?php endif; ?></td>
                        <td class="text-center"><span class="badge bg-secondary"><?= htmlspecialchars($katBadge) ?></span></td>
                        <td><?= htmlspecialchars($judul) ?></td>
                        <td class="text-center"><?php $pBadges = ['rendah'=>'secondary', 'normal'=>'info', 'tinggi'=>'warning text-dark', 'urgent'=>'danger']; ?><span class="badge bg-<?= $pBadges[$prioritas] ?? 'secondary' ?>"><?= htmlspecialchars(ucfirst($prioritas)) ?></span></td>
                        <td class="text-center">
                            <?php if ($status == 'menunggu'): ?><span class="badge bg-warning text-dark">Menunggu</span>
                            <?php elseif ($status == 'diproses'): ?><span class="badge bg-info">Diproses</span>
                            <?php else: ?><span class="badge bg-success">Selesai</span><?php endif; ?>
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