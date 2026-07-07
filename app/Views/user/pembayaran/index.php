<?= $this->extend('layouts/user') ?>
<?= $this->section('content') ?>

<?php
$belumBayar = array_filter($pembayaran, fn($p) => $p['status'] == 'belum_bayar' || $p['status'] == 'menunggu_verifikasi');
$belumUpload = !empty($belumBayar) ? array_filter($belumBayar, fn($p) => empty($p['bukti_bayar'])) : [];
$sudahUpload = !empty($belumBayar) ? array_filter($belumBayar, fn($p) => !empty($p['bukti_bayar'])) : [];
$sudahLunas = array_filter($pembayaran, fn($p) => $p['status'] == 'lunas');
$totalTunggakan = array_sum(array_map(fn($p) => $p['status'] == 'belum_bayar' ? $p['jumlah_bayar'] + ($p['total_denda'] ?? 0) : 0, $pembayaran));
$totalLunas = array_sum(array_map(fn($p) => $p['status'] == 'lunas' ? $p['jumlah_bayar'] : 0, $pembayaran));
?>

<h4 class="mb-3"><i class="bi bi-cash-stack me-2"></i>Pembayaran Saya</h4>

<?php if (empty($pembayaran)): ?>
<div class="card border-0 shadow-sm">
    <div class="card-body text-center py-5 text-muted">
        <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
        <p class="mb-3">Belum ada tagihan. Tagihan muncul setelah pengajuan sewa disetujui admin.</p>
        <a href="/user/sewa" class="btn btn-primary"><i class="bi bi-door-open me-1"></i>Ajukan Sewa</a>
    </div>
</div>
<?php endif; ?>

<?php $metodeBayar = get_metode_pembayaran_safe(); ?>
<?php if ($metodeBayar['ada'] && !empty($belumUpload)): ?>
    <?= view('user/_metode_bayar', ['metode' => $metodeBayar]) ?>
<?php endif; ?>

<?php if (!empty($belumUpload)): ?>
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-light fw-semibold py-2">
        <i class="bi bi-cash-coin me-2"></i>Tagihan Belum Dibayar
        <span class="badge bg-warning text-dark ms-2"><?= count($belumUpload) ?></span>
    </div>
    <div class="card-body">
        <form action="/user/pembayaran/upload" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                <small class="text-muted">Centang tagihan yang ingin dibayar (boleh lebih dari 1).</small>
                <div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-outline-primary" onclick="pilihSemua()">Pilih Semua</button>
                    <button type="button" class="btn btn-outline-secondary" onclick="batalSemua()">Reset</button>
                </div>
            </div>

            <div class="table-responsive mb-3">
                <table class="table table-bordered align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="40"><input type="checkbox" id="checkAll" onchange="toggleAll()"></th>
                            <th>Bulan</th>
                            <th>Jumlah</th>
                            <th>Denda</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($belumUpload as $p):
                            $denda = $p['total_denda'] ?? 0;
                            $totalBayar = $p['jumlah_bayar'] + $denda;
                        ?>
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" name="id_pembayaran[]" value="<?= $p['id_pembayaran'] ?>"
                                       class="cek-bulan" data-jumlah="<?= $totalBayar ?>" onchange="hitungTotal()">
                            </td>
                            <td>
                                <?php if ($p['bulan_ke'] == 0): ?>
                                    <span class="badge bg-warning text-dark">Deposit</span>
                                <?php else: ?>
                                    Bulan <?= $p['bulan_ke'] ?>
                                <?php endif; ?>
                                <?php if (!empty($p['tanggal_jatuh_tempo'])): ?>
                                    <small class="text-muted d-block">Jatuh tempo: <?= date('d M Y', strtotime($p['tanggal_jatuh_tempo'])) ?></small>
                                <?php endif; ?>
                            </td>
                            <td>Rp <?= number_format($p['jumlah_bayar'],0,',','.') ?></td>
                            <td><?= $denda > 0 ? '<span class="text-danger">Rp ' . number_format($denda,0,',','.') . '</span>' : '<span class="text-muted">-</span>' ?></td>
                            <td class="text-end"><strong>Rp <?= number_format($totalBayar,0,',','.') ?></strong></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3 p-3 bg-light rounded">
                <span class="fw-semibold">Total dibayar:</span>
                <span class="fs-5 fw-bold text-success" id="totalBayar">Rp 0</span>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Upload Bukti Pembayaran <span class="text-danger">*</span></label>
                <input type="file" name="bukti_bayar" class="form-control" accept="image/jpeg,image/png,image/webp,application/pdf" required>
                <small class="text-muted">Format: JPG, PNG, WEBP, atau PDF. Maks 2MB. 1 file untuk semua tagihan yang dipilih.</small>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2">
                <i class="bi bi-upload me-1"></i>Kirim Bukti Pembayaran
            </button>
        </form>
    </div>
</div>
<?php endif; ?>

<?php if (!empty($sudahUpload)): ?>
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-light fw-semibold py-2">
        <i class="bi bi-clock-history me-2"></i>Menunggu Verifikasi Admin
        <span class="badge bg-info ms-2"><?= count($sudahUpload) ?></span>
    </div>
    <div class="card-body">
        <p class="text-muted small mb-3">Tagihan berikut sudah diupload buktinya. Tunggu admin verifikasi (1x24 jam).</p>
        <div class="table-responsive">
            <table class="table table-sm table-bordered mb-0">
                <thead class="table-light">
                    <tr><th>Bulan</th><th>Jumlah</th><th>Tgl Bayar</th><th>Status</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($sudahUpload as $p): ?>
                    <tr>
                        <td><?= label_bulan_ke($p['bulan_ke']) ?></td>
                        <td>Rp <?= number_format($p['jumlah_bayar'],0,',','.') ?></td>
                        <td><small><?= !empty($p['tanggal_bayar']) ? date('d M Y', strtotime($p['tanggal_bayar'])) : '-' ?></small></td>
                        <td><span class="badge bg-info">Menunggu</span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if (!empty($sudahLunas)): ?>
<div class="card border-0 shadow-sm">
    <div class="card-header bg-light fw-semibold py-2">
        <i class="bi bi-receipt me-2"></i>Riwayat Pembayaran Lunas
        <span class="badge bg-success ms-2"><?= count($sudahLunas) ?></span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Bulan</th>
                        <th>Jumlah</th>
                        <th>Tgl Bayar</th>
                        <th class="text-center">Bukti</th>
                        <th class="text-center">Invoice</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no=1; foreach ($sudahLunas as $p): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= label_bulan_ke($p['bulan_ke']) ?></td>
                        <td class="fw-bold text-success">Rp <?= number_format($p['jumlah_bayar'],0,',','.') ?></td>
                        <td><small><?= !empty($p['tanggal_bayar']) ? date('d M Y', strtotime($p['tanggal_bayar'])) : '-' ?></small></td>
                        <td class="text-center">
                            <?php if (!empty($p['bukti_bayar'])): ?>
                            <button type="button" onclick="bukaBukti('<?= esc($p['bukti_bayar'], 'js') ?>')" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></button>
                            <?php else: ?>
                            <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <a href="/user/pembayaran/invoice/<?= $p['id_pembayaran'] ?>" target="_blank" class="btn btn-sm btn-outline-primary"><i class="bi bi-receipt"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
function toggleAll() {
    const c = document.getElementById('checkAll').checked;
    document.querySelectorAll('.cek-bulan').forEach(el => el.checked = c);
    hitungTotal();
}
function pilihSemua() {
    document.querySelectorAll('.cek-bulan').forEach(el => el.checked = true);
    document.getElementById('checkAll').checked = true;
    hitungTotal();
}
function batalSemua() {
    document.querySelectorAll('.cek-bulan').forEach(el => el.checked = false);
    document.getElementById('checkAll').checked = false;
    hitungTotal();
}
function hitungTotal() {
    let total = 0;
    document.querySelectorAll('.cek-bulan:checked').forEach(el => total += parseFloat(el.dataset.jumlah));
    document.getElementById('totalBayar').textContent = 'Rp ' + total.toLocaleString('id-ID');
}
</script>

<style>
.bukti-modal-overlay { display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); justify-content: center; align-items: center; padding: 20px; }
.bukti-modal-overlay.show { display: flex; }
.bukti-modal-box { max-width: 92%; max-height: 90vh; background: white; border-radius: 8px; padding: 16px; position: relative; }
.bukti-modal-box img { max-width: 100%; max-height: 75vh; display: block; margin: 0 auto; }
.bukti-modal-box iframe { max-width: 100%; width: 80vw; max-height: 75vh; height: 75vh; display: block; margin: 0 auto; border: none; }
.bukti-modal-close { position: absolute; top: 6px; right: 6px; background: #dc3545; color: white; border: none; width: 32px; height: 32px; border-radius: 50%; cursor: pointer; z-index: 2; }
</style>

<div id="buktiModalOverlay" class="bukti-modal-overlay" onclick="if(event.target.id==='buktiModalOverlay')tutupBukti()">
    <button type="button" class="bukti-modal-close" onclick="tutupBukti()"><i class="bi bi-x-lg"></i></button>
    <div class="bukti-modal-box">
        <div id="buktiModalContent"></div>
    </div>
</div>

<script>
function bukaBukti(file) {
    const overlay = document.getElementById('buktiModalOverlay');
    const content = document.getElementById('buktiModalContent');
    const url = '/uploads/' + file;
    const ext = file.split('.').pop().toLowerCase();
    content.innerHTML = ext === 'pdf'
        ? '<iframe src="' + url + '"></iframe>'
        : '<img src="' + url + '" alt="Bukti">';
    overlay.classList.add('show');
    document.body.style.overflow = 'hidden';
}
function tutupBukti() {
    document.getElementById('buktiModalOverlay').classList.remove('show');
    document.body.style.overflow = '';
    setTimeout(() => { document.getElementById('buktiModalContent').innerHTML = ''; }, 200);
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') tutupBukti(); });
</script>

<?= $this->endSection() ?>
