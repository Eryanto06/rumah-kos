<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<style>
    .notif-item {
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        color: inherit;
        display: block;
    }
    .notif-item:hover {
        background-color: #e9ecef !important;
        transform: translateX(4px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    .notif-item.unread {
        background-color: #fff3cd !important;
        border-left: 4px solid #ffc107;
    }
    .notif-item.unread:hover {
        background-color: #ffeaa7 !important;
    }
    .notif-card-icon {
        transition: transform 0.2s ease;
    }
    .notif-item:hover .notif-card-icon {
        transform: scale(1.1);
    }
</style>

<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body p-3" style="background:linear-gradient(135deg,#1a237e,#00897b);">
        <div class="d-flex justify-content-between align-items-center text-white">
            <div>
                <h4 class="mb-0 fw-bold"><i class="bi bi-bell-fill me-2"></i>Notifikasi Admin</h4>
                <small class="opacity-75">
                    <?php if ($unread_count > 0): ?>
                        Ada <?= $unread_count ?> notifikasi belum dibaca
                    <?php else: ?>
                        Semua notifikasi sudah dibaca ✨
                    <?php endif; ?>
                </small>
            </div>
            <?php if ($unread_count > 0): ?>
            <form action="/admin/notifikasi/baca-semua" method="post" style="display:inline;">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-light btn-sm">
                    <i class="bi bi-check2-all me-1"></i>Tandai Semua Dibaca
                </button>
            </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <?php if (!empty($notifikasi)): ?>
            <?php foreach ($notifikasi as $n): ?>
                <?php
                $tipeConfig = [
                    'pengumuman' => ['icon'=>'bi-megaphone-fill', 'bg'=>'#0d6efd', 'label'=>'Pengumuman'],
                    'keluhan'    => ['icon'=>'bi-chat-dots-fill', 'bg'=>'#dc3545', 'label'=>'Keluhan'],
                    'sewa'       => ['icon'=>'bi-door-closed-fill', 'bg'=>'#198754', 'label'=>'Sewa'],
                    'user_baru'  => ['icon'=>'bi-person-plus-fill', 'bg'=>'#fd7e14', 'label'=>'User Baru'],
                    'kontrak'    => ['icon'=>'bi-calendar-event-fill', 'bg'=>'#0dcaf0', 'label'=>'Kontrak'],
                    'info'       => ['icon'=>'bi-info-circle-fill', 'bg'=>'#6c757d', 'label'=>'Info'],
                    'tagihan'    => ['icon'=>'bi-cash-coin', 'bg'=>'#fd7e14', 'label'=>'Tagihan'],
                    'pembayaran' => ['icon'=>'bi-credit-card-fill', 'bg'=>'#198754', 'label'=>'Pembayaran'],
                    'checkout'   => ['icon'=>'bi-door-open-fill', 'bg'=>'#dc3545', 'label'=>'Checkout'],
                    'pindah'     => ['icon'=>'bi-arrow-left-right', 'bg'=>'#6f42c1', 'label'=>'Pindah Kamar'],
                ];
                $tc = $tipeConfig[$n['tipe']] ?? ['icon'=>'bi-bell-fill', 'bg'=>'#6c757d', 'label'=>ucfirst($n['tipe'])];
                
                // SEMUA notif bisa diklik (dibaca/belum) → redirect ke halaman terkait
                $linkTarget = '/admin/notifikasi/baca/' . $n['id_notifikasi'];
                $cssClass = !$n['dibaca'] ? 'unread' : 'read';
                ?>
                <form action="<?= $linkTarget ?>" method="post" class="notif-form">
                    <?= csrf_field() ?>
                <button type="submit" class="notif-item <?= $cssClass ?> border-bottom p-3 d-block w-100 text-start" style="border:none;background:none;cursor:pointer;">
                    <div class="d-flex gap-3 align-items-start">
                        <div class="notif-card-icon rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:44px;height:44px;background:<?= $tc['bg'] ?>22;color:<?= $tc['bg'] ?>;">
                            <i class="bi <?= $tc['icon'] ?> fs-5"></i>
                        </div>

                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <div>
                                    <h6 class="mb-1 <?= $n['dibaca'] ? 'fw-normal' : 'fw-bold' ?>">
                                        <?= esc($n['judul']) ?>
                                        <?php if (!$n['dibaca']): ?>
                                            <span class="badge bg-danger ms-1">Baru</span>
                                        <?php endif; ?>
                                    </h6>
                                    <p class="mb-1 text-muted small"><?= esc($n['pesan']) ?></p>
                                </div>
                                <?php if (!$n['dibaca']): ?>
                                <span class="badge bg-warning text-dark flex-shrink-0">
                                    <i class="bi bi-hand-index me-1"></i>Klik untuk dibaca
                                </span>
                                <?php else: ?>
                                <span class="badge bg-light text-muted flex-shrink-0">
                                    <i class="bi bi-box-arrow-up-right me-1"></i>Buka
                                </span>
                                <?php endif; ?>
                            </div>
                            <div class="d-flex gap-3 mt-2 small text-muted">
                                <span>
                                    <i class="bi bi-clock me-1"></i><?= esc($n['created_at']) ?>
                                </span>
                                <span>
                                    <i class="bi bi-tag me-1" style="color:<?= $tc['bg'] ?>;"></i><?= $tc['label'] ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </button>
                </form>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-bell-slash fs-1 d-block mb-3 text-muted opacity-50"></i>
                <h5 class="fw-bold">Belum Ada Notifikasi</h5>
                <p class="mb-0">Notifikasi keluhan, pengajuan sewa, dan pengumuman akan muncul di sini.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>