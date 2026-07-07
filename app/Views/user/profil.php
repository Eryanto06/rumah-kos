<?= $this->extend('layouts/user') ?>
<?= $this->section('content') ?>

<div class="card border-0 shadow-sm" style="max-width:600px;margin:0 auto;">
    <div class="card-header bg-transparent fw-semibold py-3">
        <i class="bi bi-person-circle me-2 text-primary"></i>Profil Saya
    </div>
    <div class="card-body p-4">
        
        <!-- Preview Foto -->
        <div class="text-center mb-4">
            <?php if (!empty($user['foto'])): ?>
                <img src="/uploads/<?= esc($user['foto']) ?>" alt="Foto Profil" class="rounded-circle mb-2" style="width:120px;height:120px;object-fit:cover;border:3px solid #dee2e6;">
            <?php else: ?>
                <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-2" style="width:120px;height:120px;">
                    <i class="bi bi-person-fill text-muted" style="font-size:4rem;"></i>
                </div>
            <?php endif; ?>
            <p class="text-muted small mb-0">Klik "Choose File" untuk mengganti foto profil</p>
        </div>

        <form action="/user/profil/update" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <!-- Upload Foto -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Foto Profil</label>
                <input type="file" name="foto" class="form-control" accept="image/jpeg,image/png,image/jpg,image/webp">
                <small class="text-muted">Format: JPG, PNG, WEBP. Maks 2MB.</small>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" value="<?= esc($user['nama']) ?>" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label fw-semibold">Email</label>
                <input type="email" name="email" class="form-control" value="<?= esc($user['email']) ?>" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label fw-semibold">Username</label>
                <input type="text" class="form-control" value="<?= esc($user['username']) ?>" disabled>
                <small class="text-muted">Username tidak dapat diubah</small>
            </div>
            
            <div class="mb-4">
                <label class="form-label fw-semibold">No. HP</label>
                <input type="text" name="no_hp" class="form-control" value="<?= esc($user['no_hp']) ?>" required>
            </div>

            <!-- ====== FIX BUG: REKENING USER untuk terima refund ====== -->
            <div class="alert alert-info py-2 small mb-3">
                <i class="bi bi-info-circle me-1"></i>
                <strong>Info Rekening untuk Refund:</strong> Isi data rekening/e-wallet di bawah ini.
                Data ini dipakai admin saat transfer refund (checkout, pindah kamar, atau pengajuan sewa ditolak).
                Tanpa data ini, admin harus chat Anda manual untuk tanya no rekening — proses refund jadi lebih lama.
            </div>

            <h6 class="text-primary fw-bold border-bottom pb-2 mb-3 mt-4">
                <i class="bi bi-bank me-2"></i>Rekening Bank (untuk terima refund)
            </h6>

            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Bank</label>
                <select name="nama_bank" class="form-select">
                    <option value="">-- Pilih Bank (opsional) --</option>
                    <?php
                    $bankList = ['BCA', 'BNI', 'BRI', 'Mandiri', 'CIMB Niaga', 'BSI', 'Permata', 'Danamon', 'Maybank', 'Panin Bank', 'Bukopin', 'BTPN', 'Jenius'];
                    foreach ($bankList as $b):
                        $sel = (strtoupper($user['nama_bank'] ?? '') === strtoupper($b)) ? 'selected' : '';
                    ?>
                        <option value="<?= esc($b) ?>" <?= $sel ?>><?= esc($b) ?></option>
                    <?php endforeach; ?>
                    <option value="Lainnya" <?= (strtolower($user['nama_bank'] ?? '') === 'lainnya') ? 'selected' : '' ?>>Lainnya (sebutkan di Nama Pemilik)</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Nomor Rekening</label>
                <input type="text" name="nomor_rekening" class="form-control"
                       value="<?= esc($user['nomor_rekening'] ?? '') ?>"
                       placeholder="Contoh: 1234567890" inputmode="numeric" pattern="[0-9\s\-]*">
                <small class="text-muted">Hanya angka, spasi, atau tanda hubung.</small>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Nama Pemilik Rekening</label>
                <input type="text" name="nama_pemilik_rek" class="form-control"
                       value="<?= esc($user['nama_pemilik_rek'] ?? '') ?>"
                       placeholder="Sesuai buku tabungan">
            </div>

            <h6 class="text-primary fw-bold border-bottom pb-2 mb-3 mt-4">
                <i class="bi bi-wallet2 me-2"></i>E-Wallet (opsional, alternatif refund)
            </h6>

            <div class="row mb-3">
                <div class="col-md-5">
                    <label class="form-label fw-semibold">Jenis E-Wallet</label>
                    <select name="ewallet_type" class="form-select">
                        <option value="">-- Pilih (opsional) --</option>
                        <?php
                        $ewalletList = ['DANA', 'OVO', 'GoPay', 'ShopeePay', 'LinkAja', 'Jenius Pay'];
                        foreach ($ewalletList as $e):
                            $sel = (strtoupper($user['ewallet_type'] ?? '') === strtoupper($e)) ? 'selected' : '';
                        ?>
                            <option value="<?= esc($e) ?>" <?= $sel ?>><?= esc($e) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-7">
                    <label class="form-label fw-semibold">Nomor E-Wallet (No. HP terdaftar)</label>
                    <input type="text" name="ewallet_number" class="form-control"
                           value="<?= esc($user['ewallet_number'] ?? '') ?>"
                           placeholder="Contoh: 081234567890" inputmode="numeric" pattern="[0-9\s\-]*">
                </div>
            </div>

            <!-- ====== END FIX REKENING USER ====== -->

            <!-- FIX H18: Konfirmasi password lama untuk cegah akun takeover via session theft -->
            <div class="alert alert-warning py-2 small mb-3">
                <i class="bi bi-shield-lock me-1"></i>
                <strong>Konfirmasi Keamanan:</strong> Masukkan password Anda saat ini untuk menyimpan perubahan profil.
                Ini mencegah orang lain mengubah profil Anda walau session Anda bocor.
            </div>
            <div class="mb-4">
                <label class="form-label fw-semibold">Password Saat Ini <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="password" name="password_lama" class="form-control" required autocomplete="current-password">
                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword(this)">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>
            
            <button type="submit" class="btn btn-success w-100 py-2 fw-semibold">
                <i class="bi bi-save me-1"></i>Simpan Perubahan
            </button>
        </form>
    </div>
</div>

<script>
// Preview foto sebelum upload
document.querySelector('input[name="foto"]').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(ev) {
            const img = document.querySelector('.rounded-circle');
            if (img.tagName === 'IMG') {
                img.src = ev.target.result;
            }
        };
        reader.readAsDataURL(file);
    }
});

// Toggle show/hide password lama
function togglePassword(btn) {
    const input = btn.parentElement.querySelector('input');
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
}
</script>

<?= $this->endSection() ?>