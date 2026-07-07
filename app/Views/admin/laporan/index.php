<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<!-- HEADER -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4" style="background:linear-gradient(135deg,#1a237e,#00897b); border-radius:10px; color:white;">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1 fw-bold"><i class="bi bi-file-earmark-bar-graph me-2"></i>Laporan Sistem</h4>
                <p class="mb-0 opacity-75 small">Pilih jenis laporan untuk melihat detail dan cetak</p>
            </div>
            <i class="bi bi-bar-chart-line fs-1 opacity-50"></i>
        </div>
    </div>
</div>

<!-- MENU LAPORAN -->
<div class="row g-3">
    <div class="col-md-3 col-sm-6">
        <a href="/admin/laporan/kamar" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <div class="card-body text-center p-4">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                         style="width:70px;height:70px;background:#e3f2fd;color:#1a237e;">
                        <i class="bi bi-door-closed fs-2"></i>
                    </div>
                    <h6 class="fw-bold mb-1">Laporan Kamar</h6>
                    <small class="text-muted">Status, harga, fasilitas</small>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3 col-sm-6">
        <a href="/admin/laporan/penghuni" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <div class="card-body text-center p-4">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                         style="width:70px;height:70px;background:#e8f5e9;color:#00695c;">
                        <i class="bi bi-people fs-2"></i>
                    </div>
                    <h6 class="fw-bold mb-1">Laporan Penghuni</h6>
                    <small class="text-muted">Data penghuni & kontrak</small>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3 col-sm-6">
        <a href="/admin/laporan/pembayaran" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <div class="card-body text-center p-4">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                         style="width:70px;height:70px;background:#fff3e0;color:#e65100;">
                        <i class="bi bi-cash-coin fs-2"></i>
                    </div>
                    <h6 class="fw-bold mb-1">Laporan Pembayaran</h6>
                    <small class="text-muted">Pemasukan & tagihan</small>
                </div>
            </div>
        </a>
    </div>
    <div class="col-md-3 col-sm-6">
        <a href="/admin/laporan/keluhan" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100 hover-lift">
                <div class="card-body text-center p-4">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                         style="width:70px;height:70px;background:#f3e5f5;color:#6a1b9a;">
                        <i class="bi bi-chat-dots fs-2"></i>
                    </div>
                    <h6 class="fw-bold mb-1">Laporan Keluhan</h6>
                    <small class="text-muted">Statistik & daftar keluhan</small>
                </div>
            </div>
        </a>
    </div>
</div>

<style>
.hover-lift { transition: transform 0.3s ease, box-shadow 0.3s ease; }
.hover-lift:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important; }
</style>

<?= $this->endSection() ?>