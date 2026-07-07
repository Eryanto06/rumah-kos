<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// PUBLIK
 $routes->get('/', 'Home::index');
 $routes->get('/kamar', 'Publik\Kamar::index');
 $routes->get('/kamar/detail/(:num)', 'Publik\Kamar::detail/$1');
 $routes->get('/peraturan', 'Publik\Peraturan::index');

// INSTALLER & HEALTH CHECK (akses: /installer)
// FIX BUG #2 (review): tambah filter authAdmin supaya tidak bisa diakses publik.
// Catatan: kalau admin belum bisa login (DB kosong), hapus filter sementara via phpMyAdmin/kode.
$routes->get('/installer', 'Installer::index', ['filter' => 'authAdmin']);
$routes->post('/installer', 'Installer::index', ['filter' => 'authAdmin']);

// AUTH
 $routes->get('/login', 'Auth::index');
 $routes->post('/login', 'Auth::login');
 $routes->get('/register', 'Auth::register');
 $routes->post('/register', 'Auth::registerSave');
 
// FIX S8: Logout diubah ke POST
 $routes->post('/logout', 'Auth::logout');

// LUPA PASSWORD
 $routes->get('/lupa-password', 'Auth::lupaPassword');
 $routes->post('/lupa-password/kirim', 'Auth::kirimResetLink');
 $routes->get('/reset-password/(:any)', 'Auth::resetPassword/$1');
 $routes->post('/reset-password/update', 'Auth::updatePassword');

// ADMIN
 $routes->group('admin', ['filter' => 'authAdmin'], function ($routes) {
    $routes->get('dashboard', 'Admin\Dashboard::index');

    // KAMAR
    $routes->get('kamar', 'Admin\Kamar::index');
    $routes->get('kamar/tambah', 'Admin\Kamar::tambah');
    $routes->post('kamar/simpan', 'Admin\Kamar::simpan');
    $routes->get('kamar/edit/(:num)', 'Admin\Kamar::edit/$1');
    $routes->post('kamar/update/(:num)', 'Admin\Kamar::update/$1');
    // FIX S6: Hapus diubah ke POST
    $routes->post('kamar/hapus/(:num)', 'Admin\Kamar::hapus/$1');

    // USER (PENGHUNI & PENDAFTAR)
    $routes->get('user', 'Admin\User::index');
    $routes->post('user/hapus/(:num)', 'Admin\User::hapus/$1');

    // MANAJEMEN ADMIN
    $routes->get('admin', 'Admin\Admin::index');
    $routes->get('admin/tambah', 'Admin\Admin::tambah');
    $routes->post('admin/simpan', 'Admin\Admin::simpan');
    $routes->get('admin/edit/(:num)', 'Admin\Admin::edit/$1');
    $routes->post('admin/update/(:num)', 'Admin\Admin::update/$1');
    $routes->post('admin/hapus/(:num)', 'Admin\Admin::hapus/$1');

    // SEWA
    $routes->get('sewa', 'Admin\Sewa::index');
    // FIX S6: Setujui & Tolak diubah ke POST
    $routes->post('sewa/setujui/(:num)', 'Admin\Sewa::setujui/$1');
    $routes->post('sewa/tolak/(:num)', 'Admin\Sewa::tolak/$1');
    $routes->post('sewa/batalkan-tolak/(:num)', 'Admin\Sewa::batalkanTolak/$1');
    $routes->get('sewa/detail/(:num)', 'Admin\Sewa::detail/$1');
    $routes->post('sewa/kunci-diambil/(:num)', 'Admin\Sewa::kunciDiambil/$1');
    // FIX S9: set-kunci diubah ke POST untuk mencegah CSRF via GET
    $routes->post('sewa/set-kunci/(:num)/(:segment)', 'Admin\Sewa::setKunci/$1/$2');
    // FIX BUG: route refund deposit untuk sewa ditolak (setelah user bayar deposit)
    $routes->post('sewa/refund-deposit/(:num)', 'Admin\Sewa::refundDeposit/$1');

    // PEMBAYARAN
    $routes->get('pembayaran', 'Admin\Pembayaran::index');
    $routes->get('pembayaran/detail/(:num)', 'Admin\Pembayaran::detail/$1');
    $routes->post('pembayaran/verifikasi/(:num)', 'Admin\Pembayaran::verifikasi/$1');

    // KELUHAN
    $routes->get('keluhan', 'Admin\Keluhan::index');
    $routes->get('keluhan/detail/(:num)', 'Admin\Keluhan::detail/$1');
    $routes->post('keluhan/update-status/(:num)', 'Admin\Keluhan::updateStatus/$1');

    // NOTIFIKASI ADMIN
    $routes->get('notifikasi', 'Admin\Notifikasi::index');
    // FIX S10: baca & baca-semua diubah ke POST — GET bisa di-CSRF via <img src=...>.
    $routes->post('notifikasi/baca/(:num)', 'Admin\Notifikasi::baca/$1');
    $routes->post('notifikasi/baca-semua', 'Admin\Notifikasi::bacaSemua');

    // PINDAH KAMAR
    $routes->get('pindah-kamar', 'Admin\PindahKamar::index');
    $routes->get('pindah-kamar/form-inspeksi/(:num)', 'Admin\PindahKamar::formInspeksi/$1');
    $routes->post('pindah-kamar/setujui/(:num)', 'Admin\PindahKamar::setujui/$1');
    $routes->post('pindah-kamar/tolak/(:num)', 'Admin\PindahKamar::tolak/$1');
    $routes->post('pindah-kamar/batalkan-tolak/(:num)', 'Admin\PindahKamar::batalkanTolak/$1');

    // CHECKOUT
    $routes->get('checkout', 'Admin\Checkout::index');
    $routes->get('checkout/form-inspeksi/(:num)', 'Admin\Checkout::formInspeksi/$1');
    $routes->post('checkout/setujui/(:num)', 'Admin\Checkout::setujui/$1');
    $routes->post('checkout/tolak/(:num)', 'Admin\Checkout::tolak/$1');

    // PENGUMUMAN
    $routes->get('pengumuman', 'Admin\Pengumuman::index');
    $routes->post('pengumuman/simpan', 'Admin\Pengumuman::simpan');
    $routes->post('pengumuman/hapus/(:num)', 'Admin\Pengumuman::hapus/$1');
    $routes->post('pengumuman/toggle/(:num)', 'Admin\Pengumuman::toggleStatus/$1');

    // PERATURAN
    $routes->get('peraturan', 'Admin\Peraturan::index');
    $routes->post('peraturan/simpan', 'Admin\Peraturan::simpan');
    $routes->post('peraturan/hapus/(:num)', 'Admin\Peraturan::hapus/$1');
    $routes->post('peraturan/toggle/(:num)', 'Admin\Peraturan::toggleStatus/$1');

    // PENGATURAN SISTEM
    $routes->get('pengaturan', 'Admin\Pengaturan::index');
    $routes->post('pengaturan/update', 'Admin\Pengaturan::update');
    // FIX S9: maintenance & backup diubah ke POST + CSRF
    $routes->post('pengaturan/notif-kontrak-habis', 'Admin\Pengaturan::notifKontrakHabis');
    $routes->post('pengaturan/backup', 'Admin\Pengaturan::backupDatabase');
    $routes->post('pengaturan/recalculate-denda', 'Admin\Pengaturan::recalculateDenda');

    // LAPORAN
    $routes->get('laporan', 'Admin\Laporan::index');
    $routes->get('laporan/kamar', 'Admin\Laporan::kamar');
    $routes->get('laporan/penghuni', 'Admin\Laporan::penghuni');
    $routes->get('laporan/pembayaran', 'Admin\Laporan::pembayaran');
    $routes->get('laporan/keluhan', 'Admin\Laporan::keluhan');

    // EXPORT LAPORAN KE EXCEL
    $routes->get('laporan/export-kamar', 'Admin\Laporan::exportKamar');
    $routes->get('laporan/export-penghuni', 'Admin\Laporan::exportPenghuni');
    $routes->get('laporan/export-pembayaran', 'Admin\Laporan::exportPembayaran');
    $routes->get('laporan/export-keluhan', 'Admin\Laporan::exportKeluhan');
});

// USER
 $routes->group('user', ['filter' => 'authUser'], function ($routes) {
    $routes->get('dashboard', 'User\Dashboard::index');

    $routes->get('profil', 'User\Profil::index');
    $routes->post('profil/update', 'User\Profil::update');

    $routes->get('kamar-saya', 'User\KamarSaya::index');

    $routes->get('sewa', 'User\Sewa::index');
    $routes->post('sewa/ajukan', 'User\Sewa::ajukan');

    $routes->get('perpanjangan', 'User\Perpanjangan::index');
    $routes->post('perpanjangan/ajukan', 'User\Perpanjangan::ajukan');

    $routes->get('pindah-kamar', 'User\PindahKamar::index');
    $routes->post('pindah-kamar/ajukan', 'User\PindahKamar::ajukan');

    $routes->get('checkout', 'User\Checkout::index');
    $routes->post('checkout/ajukan', 'User\Checkout::ajukan');

    $routes->get('pembayaran', 'User\Pembayaran::index');
    $routes->post('pembayaran/upload', 'User\Pembayaran::upload');
    $routes->get('pembayaran/invoice/(:num)', 'User\Pembayaran::invoice/$1');

    $routes->get('keluhan', 'User\Keluhan::index');
    $routes->post('keluhan/kirim', 'User\Keluhan::kirim');

    $routes->get('notifikasi', 'User\Notifikasi::index');
    // FIX S10: baca & baca-semua diubah ke POST.
    $routes->post('notifikasi/baca/(:num)', 'User\Notifikasi::baca/$1');
    $routes->post('notifikasi/baca-semua', 'User\Notifikasi::bacaSemua');
});