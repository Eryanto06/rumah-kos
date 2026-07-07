<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<?php
// Buat array assosiatif supaya mudah akses nilainya
 $settings = [];
foreach ($pengaturan as $p) {
    $settings[$p['kunci']] = $p['nilai'];
}
?>

<div class="row">
    <div class="col-md-7">
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header fw-semibold bg-primary text-white">
                <i class="bi bi-gear me-2"></i>Pengaturan Sistem Kos
            </div>
            <div class="card-body">
                <form action="/admin/pengaturan/update" method="post">
                    <?= csrf_field() ?>

                    <h6 class="text-primary fw-bold border-bottom pb-2 mb-3"><i class="bi bi-cash-coin me-2"></i>Pengaturan Keuangan</h6>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Denda Keterlambatan / Hari (Rp)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="denda_per_hari" class="form-control" value="<?= esc($settings['denda_per_hari'] ?? '5000') ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Default Deposit (Kali Harga Sewa)</label>
                            <div class="input-group">
                                <input type="number" name="default_deposit_kali" class="form-control" value="<?= esc($settings['default_deposit_kali'] ?? '2') ?>" required min="1">
                                <span class="input-group-text">x</span>
                            </div>
                        </div>
                    </div>

                    <h6 class="text-primary fw-bold border-bottom pb-2 mb-3 mt-4"><i class="bi bi-calendar-event me-2"></i>Pengaturan Operasional</h6>

                    <div class="alert alert-info py-2 small">
                        <i class="bi bi-info-circle me-1"></i> <strong>Durasi Sewa Bebas:</strong> Penghuni dapat mengajukan durasi sewa berapa bulan saja sesuai kebutuhan. Setting di bawah hanya sebagai default/opsional dan tidak membatasi input penghuni.
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Default Durasi Minimal (Opsional)</label>
                            <input type="number" name="durasi_minimal" class="form-control" value="<?= esc($settings['durasi_minimal'] ?? '1') ?>" min="1">
                            <small class="text-muted">Hanya referensi, tidak membatasi input user</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Default Durasi Maksimal (Opsional)</label>
                            <input type="number" name="durasi_maksimal" class="form-control" value="<?= esc($settings['durasi_maksimal'] ?? '36') ?>" min="1">
                            <small class="text-muted">Hanya referensi, tidak membatasi input user</small>
                        </div>
                        <div class="col-md-12 mt-2">
                            <label class="form-label fw-semibold">Batas Tanggal Bayar</label>
                            <div class="input-group">
                                <span class="input-group-text">Tgl</span>
                                <input type="number" name="batas_tanggal_bayar" class="form-control" value="<?= esc($settings['batas_tanggal_bayar'] ?? '5') ?>" required min="1" max="31">
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info py-2 small">
                        <i class="bi bi-info-circle me-1"></i> <strong>Notifikasi Kontrak Otomatis:</strong> Sistem akan otomatis mengingatkan penghuni di H-7, H-3, H-2, H-1, dan H-Hari Kontrak Habis tanpa perlu disetting.
                    </div>

                    <h6 class="text-primary fw-bold border-bottom pb-2 mb-3 mt-4"><i class="bi bi-bank me-2"></i>Metode Pembayaran Kos (untuk User Transfer)</h6>
                    <div class="alert alert-info py-2 small mb-3">
                        <i class="bi bi-info-circle me-1"></i> <strong>Penting:</strong> Data ini ditampilkan ke user saat mereka akan bayar deposit / sewa bulanan. Tanpa info ini, user tidak tahu ke mana harus transfer dan harus chat admin manual.
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Bank #1 - Nama Bank</label>
                            <select name="bank_name_1" class="form-select">
                                <option value="">-- Pilih Bank --</option>
                                <?php
                                $banks = ['BCA', 'BNI', 'BRI', 'Mandiri', 'CIMB Niaga', 'BSI', 'Permata', 'Danamon', 'Maybank', 'Panin Bank', 'Bukopin', 'BTPN', 'Jenius'];
                                foreach ($banks as $b):
                                    $sel = (strtoupper($settings['bank_name_1'] ?? '') === strtoupper($b)) ? 'selected' : '';
                                ?>
                                    <option value="<?= esc($b) ?>" <?= $sel ?>><?= esc($b) ?></option>
                                <?php endforeach; ?>
                                <option value="Lainnya" <?= (strtolower($settings['bank_name_1'] ?? '') === 'lainnya') ? 'selected' : '' ?>>Lainnya</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Nomor Rekening #1</label>
                            <input type="text" name="bank_account_1" class="form-control" value="<?= esc($settings['bank_account_1'] ?? '') ?>" inputmode="numeric" pattern="[0-9\s\-]*">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Atas Nama #1</label>
                            <input type="text" name="bank_holder_1" class="form-control" value="<?= esc($settings['bank_holder_1'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Bank #2 - Nama Bank (Opsional)</label>
                            <select name="bank_name_2" class="form-select">
                                <option value="">-- Pilih Bank --</option>
                                <?php
                                foreach ($banks as $b):
                                    $sel = (strtoupper($settings['bank_name_2'] ?? '') === strtoupper($b)) ? 'selected' : '';
                                ?>
                                    <option value="<?= esc($b) ?>" <?= $sel ?>><?= esc($b) ?></option>
                                <?php endforeach; ?>
                                <option value="Lainnya" <?= (strtolower($settings['bank_name_2'] ?? '') === 'lainnya') ? 'selected' : '' ?>>Lainnya</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Nomor Rekening #2</label>
                            <input type="text" name="bank_account_2" class="form-control" value="<?= esc($settings['bank_account_2'] ?? '') ?>" inputmode="numeric" pattern="[0-9\s\-]*">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Atas Nama #2</label>
                            <input type="text" name="bank_holder_2" class="form-control" value="<?= esc($settings['bank_holder_2'] ?? '') ?>">
                        </div>
                    </div>

                    <h6 class="text-primary fw-bold border-bottom pb-2 mb-3 mt-4"><i class="bi bi-wallet2 me-2"></i>E-Wallet (Opsional)</h6>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">No. DANA</label>
                            <input type="text" name="ewallet_dana" class="form-control" value="<?= esc($settings['ewallet_dana'] ?? '') ?>" inputmode="numeric" pattern="[0-9\s\-]*">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">No. OVO</label>
                            <input type="text" name="ewallet_ovo" class="form-control" value="<?= esc($settings['ewallet_ovo'] ?? '') ?>" inputmode="numeric" pattern="[0-9\s\-]*">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">No. GoPay</label>
                            <input type="text" name="ewallet_gopay" class="form-control" value="<?= esc($settings['ewallet_gopay'] ?? '') ?>" inputmode="numeric" pattern="[0-9\s\-]*">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">No. ShopeePay</label>
                            <input type="text" name="ewallet_shopeepay" class="form-control" value="<?= esc($settings['ewallet_shopeepay'] ?? '') ?>" inputmode="numeric" pattern="[0-9\s\-]*">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Instruksi Pembayaran (Tampil ke User)</label>
                        <textarea name="payment_instructions" class="form-control" rows="3" placeholder="Contoh: Transfer tepat sesuai nominal tagihan. Setelah transfer, upload bukti di menu Pembayaran. Konfirmasi via WhatsApp admin jika perlu."><?= esc($settings['payment_instructions'] ?? '') ?></textarea>
                    </div>

                    <h6 class="text-primary fw-bold border-bottom pb-2 mb-3 mt-5"><i class="bi bi-building me-2"></i>Profil & Kontak Kos (Tampil di Landing Page)</h6>
                    <div class="alert alert-info py-2 small mb-3">
                        <i class="bi bi-info-circle me-1"></i> Data ini tampil di landing page publik. Ubah sesuai data kos Anda (nama, alamat, email, sosial media, dll).
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Kos</label>
                            <input type="text" name="nama_kos" class="form-control" value="<?= esc($settings['nama_kos'] ?? 'Rumah Kos') ?>" placeholder="Rumah Kos">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tagline / Slogan</label>
                            <input type="text" name="tagline" class="form-control" value="<?= esc($settings['tagline'] ?? '') ?>" placeholder="Sistem Informasi Manajemen Kos">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Alamat Lengkap Kos</label>
                        <textarea name="alamat" class="form-control" rows="2" placeholder="Jl. Contoh Alamat No. 123, Kota"><?= esc($settings['alamat'] ?? '') ?></textarea>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold"><i class="bi bi-envelope me-1"></i>Email Kontak</label>
                            <input type="email" name="email_kos" class="form-control" value="<?= esc($settings['email_kos'] ?? '') ?>" placeholder="info@rumahkos.id">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold"><i class="bi bi-telephone me-1"></i>Telepon</label>
                            <input type="text" name="telepon_kos" class="form-control" value="<?= esc($settings['telepon_kos'] ?? '') ?>" placeholder="+62 812-3456-7890">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold"><i class="bi bi-whatsapp me-1"></i>No. WhatsApp Admin</label>
                            <input type="text" name="wa_admin" class="form-control" value="<?= esc($settings['wa_admin'] ?? '') ?>" placeholder="081234567890">
                            <small class="text-muted">Dipakai untuk tombol chat WhatsApp di landing.</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold"><i class="bi bi-clock me-1"></i>Jam Operasional Office</label>
                        <input type="text" name="jam_operasional" class="form-control" value="<?= esc($settings['jam_operasional'] ?? '08:00 - 17:00 WIB') ?>" placeholder="08:00 - 17:00 WIB">
                    </div>

                    <h6 class="text-primary fw-bold border-bottom pb-2 mb-3 mt-4"><i class="bi bi-share me-2"></i>Sosial Media</h6>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold"><i class="bi bi-facebook me-1 text-primary"></i>URL Facebook</label>
                            <input type="url" name="facebook" class="form-control" value="<?= esc($settings['facebook'] ?? '') ?>" placeholder="https://facebook.com/rumahkos">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold"><i class="bi bi-instagram me-1 text-danger"></i>URL Instagram</label>
                            <input type="url" name="instagram" class="form-control" value="<?= esc($settings['instagram'] ?? '') ?>" placeholder="https://instagram.com/rumahkos">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold"><i class="bi bi-tiktok me-1"></i>URL TikTok</label>
                            <input type="url" name="tiktok" class="form-control" value="<?= esc($settings['tiktok'] ?? '') ?>" placeholder="https://tiktok.com/@rumahkos">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold"><i class="bi bi-youtube me-1 text-danger"></i>URL YouTube</label>
                            <input type="url" name="youtube" class="form-control" value="<?= esc($settings['youtube'] ?? '') ?>" placeholder="https://youtube.com/@rumahkos">
                        </div>
                    </div>

                    <h6 class="text-primary fw-bold border-bottom pb-2 mb-3 mt-4"><i class="bi bi-geo-alt me-2"></i>Google Maps</h6>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">URL Google Maps (untuk tombol Lihat di Maps)</label>
                        <input type="url" name="maps_link" class="form-control" value="<?= esc($settings['maps_link'] ?? '') ?>" placeholder="https://maps.google.com/?q=...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Embed Google Maps (URL src iframe)</label>
                        <input type="url" name="maps_embed" class="form-control" value="<?= esc($settings['maps_embed'] ?? '') ?>" placeholder="https://www.google.com/maps/embed?...">
                        <small class="text-muted">Buka Google Maps &rarr; Share &rarr; Embed a map &rarr; copy URL src. Kosongkan jika tidak pakai.</small>
                    </div>

                    <h6 class="text-primary fw-bold border-bottom pb-2 mb-3 mt-4"><i class="bi bi-textarea-t me-2"></i>Teks Footer</h6>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Teks Footer (Copyright)</label>
                        <input type="text" name="footer_text" class="form-control" value="<?= esc($settings['footer_text'] ?? '') ?>" placeholder="Rumah Kos. Sistem Informasi Manajemen Kos.">
                        <small class="text-muted">Teks ini tampil di footer landing page. Kosongkan untuk pakai default.</small>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>Simpan Pengaturan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-5">
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header fw-semibold bg-warning">
                <i class="bi bi-lightning me-2"></i>Maintenance Manual
            </div>
            <div class="card-body">
                <p class="text-muted small">Jalankan secara manual jika ada data yang belum terupdate:</p>

                <div class="d-grid gap-2">
                    <form action="/admin/pengaturan/recalculate-denda" method="post" class="d-grid">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-outline-warning"
                           onclick="return confirm('Hapus denda lama dan hitung ulang memakai tarif terbaru?')">
                            <i class="bi bi-arrow-repeat me-1"></i>Hitung Ulang Semua Denda
                        </button>
                    </form>
                    <small class="text-muted mb-2">Pakai ini kalau Anda baru saja mengubah tarif denda.</small>

                    <form action="/admin/pengaturan/notif-kontrak-habis" method="post" class="d-grid">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn btn-outline-warning"
                           onclick="return confirm('Kirim notifikasi kontrak hampir habis (H-7/H-3/H-2/H-1/H-0) sekarang?')">
                            <i class="bi bi-bell me-1"></i>Kirim Ulang Notifikasi Kontrak Habis
                        </button>
                    </form>
                    <small class="text-muted">Pakai ini kalau cron harian sempat tidak jalan (server mati / lupa setting Task Scheduler). Aman dipencet berkali-kali, tidak akan kirim notif dobel.</small>
                </div>
            </div>
        </div>

        <div class="card border-danger">
            <div class="card-header fw-semibold bg-danger text-white">
                <i class="bi bi-shield-lock me-2"></i>Backup & Keamanan
            </div>
            <div class="card-body">
                <p class="text-muted small">Download seluruh data database dalam format SQL.</p>
                <form action="/admin/pengaturan/backup" method="post" class="d-grid">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-danger w-100"
                       onclick="return confirm('Download backup database?')">
                        <i class="bi bi-download me-1"></i>Backup Database
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>