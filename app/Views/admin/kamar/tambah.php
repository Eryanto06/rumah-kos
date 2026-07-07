<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="card border-0 shadow-sm" style="max-width:750px;margin:0 auto;">
    <div class="card-header bg-transparent fw-semibold py-3">
        <i class="bi bi-plus-circle me-2 text-primary"></i>Tambah Kamar Baru
    </div>
    <div class="card-body">
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle me-2"></i><strong>Terjadi kesalahan:</strong>
                <ul class="mb-0 mt-1">
                    <?php foreach (session()->getFlashdata('errors') as $e): ?>
                        <li><?= esc($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <form action="/admin/kamar/simpan" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <!-- KODE KAMAR -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Kode Kamar</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-upc-scan"></i></span>
                    <input type="text" name="kode_kamar" id="kodeKamar" class="form-control" value="<?= old('kode_kamar', $kode_otomatis ?? 'KOS-001') ?>" required>
                    <div class="input-group-text">
                        <div class="form-check form-switch m-0">
                            <input class="form-check-input" type="checkbox" id="autoKode" checked onchange="toggleAuto('kode', this.checked)">
                            <label class="form-check-label small" for="autoKode">Auto</label>
                        </div>
                    </div>
                </div>
                <small class="text-muted">Otomatis generate KOS-XXX. Hapus centang "Auto" untuk ketik manual.</small>
            </div>

            <!-- NOMOR KAMAR -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Nomor Kamar</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-hash"></i></span>
                    <input type="text" name="nomor_kamar" id="nomorKamar" class="form-control" value="<?= old('nomor_kamar', $nomor_otomatis ?? 101) ?>" required>
                    <div class="input-group-text">
                        <div class="form-check form-switch m-0">
                            <input class="form-check-input" type="checkbox" id="autoNomor" checked onchange="toggleAuto('nomor', this.checked)">
                            <label class="form-check-label small" for="autoNomor">Auto</label>
                        </div>
                    </div>
                </div>
                <small class="text-muted">Otomatis generate nomor berurutan. Hapus centang "Auto" untuk ketik manual.</small>
            </div>

            <!-- HARGA SEWA -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Harga Sewa (per bulan)</label>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="number" name="harga_sewa" class="form-control" placeholder="500000" value="<?= old('harga_sewa') ?>" required>
                </div>
            </div>

            <!-- FASILITAS -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Fasilitas Kamar</label>
                <div class="card bg-light border-0 mb-2">
                    <div class="card-body">
                        <small class="text-muted d-block mb-2"><i class="bi bi-check2-square me-1"></i>Pilih fasilitas standar:</small>
                        <div class="row g-2">
                            <?php
                            $fasilitasStandar = [
                                'Kasur Single', 'Kasur Queen', 'Lemari Baju', 'Meja Belajar', 'Kursi',
                                'AC', 'Kipas Angin', 'Jendela', 'Kamar Mandi Dalam', 'Water Heater',
                                'Wi-Fi', 'TV', 'Stop Kontak', 'Gantungan Baju', 'Cermin', 'Rak Sepatu'
                            ];
                            // FIX ERROR: pakai (array) untuk suppress warning VS Code
                             $oldChecklist = old('fasilitas_checklist');
                            $oldChecklist = is_array($oldChecklist) ? $oldChecklist : [];
                            foreach ($fasilitasStandar as $f):
                            ?>
                            <div class="col-md-4 col-sm-6">
                                <div class="form-check">
                                    <input class="form-check-input fasilitas-check" type="checkbox" name="fasilitas_checklist[]" value="<?= esc($f) ?>" id="fas_<?= md5($f) ?>" <?= in_array($f, $oldChecklist) ? 'checked' : '' ?>>
                                    <label class="form-check-label small" for="fas_<?= md5($f) ?>"><?= esc($f) ?></label>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="card border-0 mb-2">
                    <div class="card-body p-2">
                        <small class="text-muted d-block mb-1"><i class="bi bi-pencil me-1"></i>Fasilitas tambahan (pisahkan dengan koma):</small>
                        <input type="text" name="fasilitas_manual" class="form-control" placeholder="Contoh: Brankas kecil, Papan setrika" value="<?= old('fasilitas_manual') ?>">
                    </div>
                </div>
                <div class="mt-2 p-2 bg-white border rounded">
                    <small class="text-muted">Preview: <span id="fasilitasPreview" class="text-dark fw-semibold">-</span></small>
                </div>
            </div>

            <!-- FOTO -->
            <div class="mb-4">
                <label class="form-label fw-semibold">Foto Kamar (Opsional)</label>
                <input type="file" name="foto" class="form-control" accept="image/*">
                <small class="text-muted">Format: JPG, PNG. Maks 2MB.</small>
                <div class="mt-2">
                    <img id="previewFoto" src="" alt="Preview" class="img-thumbnail" style="max-height:200px; display:none;">
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan Kamar</button>
                <a href="/admin/kamar" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<script>
function toggleAuto(type, isAuto) {
    const input = document.getElementById(type === 'kode' ? 'kodeKamar' : 'nomorKamar');
    input.readOnly = isAuto;
    if (!isAuto) { input.focus(); input.select(); }
}
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('kodeKamar').readOnly = document.getElementById('autoKode').checked;
    document.getElementById('nomorKamar').readOnly = document.getElementById('autoNomor').checked;
    updateFasilitasPreview();
});
document.querySelector('input[name="foto"]').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('previewFoto');
    if (file) {
        const reader = new FileReader();
        reader.onload = function(ev) { preview.src = ev.target.result; preview.style.display = 'block'; };
        reader.readAsDataURL(file);
    } else { preview.style.display = 'none'; }
});
function updateFasilitasPreview() {
    const checkboxes = document.querySelectorAll('.fasilitas-check:checked');
    const manual = document.querySelector('input[name="fasilitas_manual"]').value;
    let fasilitas = Array.from(checkboxes).map(c => c.value);
    if (manual.trim()) {
        fasilitas = fasilitas.concat(manual.split(',').map(s => s.trim()).filter(s => s));
    }
    fasilitas = [...new Set(fasilitas)];
    document.getElementById('fasilitasPreview').textContent = fasilitas.length > 0 ? fasilitas.join(', ') : '-';
}
document.querySelectorAll('.fasilitas-check').forEach(c => c.addEventListener('change', updateFasilitasPreview));
document.querySelector('input[name="fasilitas_manual"]').addEventListener('input', updateFasilitasPreview);
</script>

<?= $this->endSection() ?>