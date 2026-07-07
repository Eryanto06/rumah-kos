<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin' ?> - Rumah Kos</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: #f4f6f9;
            font-family: 'Segoe UI', sans-serif;
        }

        .sidebar {
            height: 100vh;
            background: linear-gradient(180deg, #1a237e 0%, #283593 100%);
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
            box-shadow: 2px 0 10px rgba(0,0,0,0.2);
            overflow-y: auto;
            transition: transform .3s ease;
            border-right: 3px solid #0d1452;
        }

        .sidebar .brand {
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,.1);
            color: #fff;
            font-size: 1.1rem;
            font-weight: 700;
            background: rgba(0,0,0,.15);
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,.75);
            padding: 12px 20px;
            border-radius: 0;
            transition: all .2s;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #fff;
            background: rgba(255,255,255,.15);
            border-left: 3px solid #fff;
        }

        .sidebar .nav-link i {
            margin-right: 8px;
        }

        /* Sidebar heading - kategori menu */
        .sidebar-heading {
            color: rgba(255,255,255,.4);
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 14px 20px 6px;
            margin-top: 8px;
        }
        .sidebar-heading:first-child { margin-top: 0; }

        /* ===== Logout ===== */
        .logout-form { width: 100%; margin: 0; }
        .logout-btn {
            width: 100%;
            border: none;
            background: transparent;
            color: #dc3545;
            padding: 12px 20px;
            text-align: left;
            cursor: pointer;
            transition: .2s;
        }
        .logout-btn:hover {
            background: rgba(220,53,69,.15);
            color: #fff;
            border-left: 3px solid #dc3545;
        }
        .logout-btn i { margin-right: 8px; }

        .main-content {
            margin-left: 250px;
            padding: 20px;
            transition: margin-left .3s ease;
        }

        .topbar {
            background: #fff;
            padding: 12px 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,.08);
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-left: 4px solid #1a237e;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,.07);
        }

        .card-header {
            border-radius: 10px 10px 0 0 !important;
        }

        .notif-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .notif-icon-wrapper {
            position: relative;
            display: inline-block;
        }

        .btn-toggle-sidebar {
            display: none;
            background: #1a237e;
            color: #fff;
            border: none;
            padding: 8px 12px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.2rem;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,.5);
            z-index: 99;
        }

        @media(max-width:768px) {
            .sidebar {
                transform: translateX(-100%);
                z-index: 1000;
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
                padding: 15px;
            }
            .btn-toggle-sidebar {
                display: inline-block;
            }
            .sidebar-overlay.show {
                display: block;
            }
        }
    </style>
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<div class="sidebar" id="sidebar">
    <div class="brand">
        <i class="bi bi-house-fill me-2"></i> Rumah Kos
    </div>
    <nav class="nav flex-column mt-2">
        <a href="/admin/dashboard" class="nav-link <?= (current_url() == base_url('admin/dashboard')) ? 'active' : '' ?>"><i class="bi bi-speedometer2"></i> Dashboard</a>

        <div class="sidebar-heading">MANAJEMEN</div>
        <a href="/admin/kamar" class="nav-link <?= strpos(current_url(),'admin/kamar') !== false ? 'active' : '' ?>"><i class="bi bi-door-closed"></i> Data Kamar</a>
        <a href="/admin/user" class="nav-link <?= strpos(current_url(),'admin/user') !== false ? 'active' : '' ?>"><i class="bi bi-people"></i> Data User</a>
        <a href="/admin/admin" class="nav-link <?= strpos(current_url(),'admin/admin') !== false ? 'active' : '' ?>"><i class="bi bi-person-badge"></i> Data Admin</a>

        <div class="sidebar-heading">TRANSAKSI</div>
        <a href="/admin/sewa" class="nav-link <?= strpos(current_url(),'admin/sewa') !== false ? 'active' : '' ?>"><i class="bi bi-file-earmark-text"></i> Pengajuan Sewa</a>
        <a href="/admin/pembayaran" class="nav-link <?= strpos(current_url(),'admin/pembayaran') !== false ? 'active' : '' ?>"><i class="bi bi-cash-coin"></i> Pembayaran</a>
        <a href="/admin/pindah-kamar" class="nav-link <?= strpos(current_url(),'admin/pindah-kamar') !== false ? 'active' : '' ?>"><i class="bi bi-arrow-left-right"></i> Pindah Kamar</a>
        <a href="/admin/checkout" class="nav-link <?= strpos(current_url(),'admin/checkout') !== false ? 'active' : '' ?>"><i class="bi bi-door-open"></i> Checkout Penghuni</a>

        <div class="sidebar-heading">LAINNYA</div>
        <a href="/admin/keluhan" class="nav-link <?= strpos(current_url(),'admin/keluhan') !== false ? 'active' : '' ?>"><i class="bi bi-chat-dots"></i> Keluhan</a>
        <a href="/admin/pengumuman" class="nav-link <?= strpos(current_url(),'admin/pengumuman') !== false ? 'active' : '' ?>"><i class="bi bi-megaphone"></i> Pengumuman</a>
        <a href="/admin/peraturan" class="nav-link <?= strpos(current_url(),'admin/peraturan') !== false ? 'active' : '' ?>"><i class="bi bi-shield-check"></i> Peraturan Kos</a>
        <a href="/admin/laporan" class="nav-link <?= strpos(current_url(),'admin/laporan') !== false ? 'active' : '' ?>"><i class="bi bi-bar-chart"></i> Laporan</a>

        <div class="sidebar-heading">SISTEM</div>
        <a href="/admin/pengaturan" class="nav-link <?= strpos(current_url(),'admin/pengaturan') !== false ? 'active' : '' ?>"><i class="bi bi-gear"></i> Pengaturan</a>
        <hr style="border-color:rgba(255,255,255,.2);margin:10px 20px;">
        <a href="/" target="_blank" class="nav-link text-info"><i class="bi bi-globe2"></i> Lihat Website</a>
        
        <!-- LOGOUT -->
        <form action="<?= base_url('logout') ?>" method="post" class="logout-form">
            <?= csrf_field() ?>
            <button type="submit" class="logout-btn">
                <i class="bi bi-box-arrow-left"></i> Logout
            </button>
        </form>
    </nav>
</div>

<!-- BAGIAN INI YANG TADI HILANG DI KODE KAMU -->
<div class="main-content">
    <div class="topbar">
        <div class="d-flex align-items-center gap-2">
            <button class="btn-toggle-sidebar" onclick="toggleSidebar()"><i class="bi bi-list"></i></button>
            <h5 class="mb-0 fw-bold"><?= $title ?? '' ?></h5>
        </div>
        <div class="d-flex align-items-center gap-3">
            <a href="/admin/notifikasi" class="text-decoration-none text-dark notif-icon-wrapper" title="Notifikasi">
                <i class="bi bi-bell fs-5"></i>
                <?php
                $notifModel = new \App\Models\NotifikasiModel();
                $unread = $notifModel->getUnreadCount(session()->get('id_user'));
                if ($unread > 0):
                ?>
                <span class="notif-badge"><?= $unread > 99 ? '99+' : $unread ?></span>
                <?php endif; ?>
            </a>
            <i class="bi bi-person-circle fs-5"></i>
            <span class="d-none d-sm-inline"><?= esc(session()->get('nama')) ?></span>
            <span class="badge" style="background:#1a237e;">Admin</span>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show"><?= esc(session()->getFlashdata('success')) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show"><?= esc(session()->getFlashdata('error')) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('warning')): ?>
        <div class="alert alert-warning alert-dismissible fade show"><?= esc(session()->getFlashdata('warning')) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>

    <?= $this->renderSection('content') ?>
</div>
<!-- AKHIR BAGIAN YANG HILANG -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('show');
    document.getElementById('sidebarOverlay').classList.toggle('show');
}
</script>
</body>
</html>