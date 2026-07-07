<?= $this->extend('layouts/user') ?>
<?= $this->section('content') ?>

<style>
    .konfirmasi-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-left: 5px solid #0d6efd;
    }
    .pertanyaan-item {
        background: white;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 12px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        transition: all 0.2s;
    }
    .pertanyaan-item:hover {
        box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    }
    .btn-check:checked + .btn-outline-success {
        background-color: #198754;
        border-color: #198754;
        color: white;
    }
    .btn-check:checked + .btn-outline-danger {
        background-color: #dc3545;
        border-color: #dc3545;
        color: white;
    }
    .deposit-panel {
        background: linear-gradient(135deg, #fff9e6 0%, #fff3cd 100%);
        border-left: 5px solid #ffc107;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 16px;
    }
    .alert-wajib-bayar {
        background: linear-gradient(135deg, #f8d7da 0%, #f5c2c7 100%);
        border-left: 5px solid #dc3545;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 12px;
        display: none;
    }
    .alert-kembalian {
        background: linear-gradient(135deg, #d1e7dd 0%, #a3cfbb 100%);
        border-left: 5px solid #198754;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 12px;
        display: none;
    }
    .alert-sama {
        background: linear-gradient(135deg, #cff4fc 0%, #b6effb 100%);
        border-left: 5px solid #0dcaf0;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 12px;
        display: none;
    }
</style>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent fw-semibold py-3">
                <i class="bi bi-arrow-left-right me-2 text-primary"></i>Form Pengajuan Pindah Kamar
            </div>
            <div class="card-body">
                <?php if ($sewaAktif): ?>
                    <!-- INFO KAMAR AKTIF -->
                    <div class="alert alert-info border-0 mb-3">
                        <div class="d-flex justify-content-between">
                            <div>
                                <small class="d-block text-muted">Kamar Aktif Saat Ini</small>
                                <strong class="fs-5">No. <?= esc($sewaAktif['nomor_kamar']) ?> (<?= esc($sewaAktif['kode_kamar']) ?>)</strong>
                            </div>
                            <div class="text-end">
                                <small class="d-block text-muted">Kontrak Berakhir</small>
                                <strong class="fs-5"><?= esc(date('d M Y', strtotime($sewaAktif['tanggal_selesai']))) ?></strong>
                            </div>
                        </div>
                    </div>

                    <?php if (empty($pengajuanMenunggu)): ?>
                    <!-- FORM AJUKAN PINDAH -->
                    <form action="/user/pindah-kamar/ajukan" method="post" id="formPindahKamar">
                        <?= csrf_field() ?>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Pilih Kamar Tujuan <span class="text-danger">*</span></label>
                            <select name="id_kamar_baru" id="kamarBaruSelect" class="form-select" required onchange="hitungDeposit()">
                                <option value="">-- Pilih Kamar Tersedia --</option>
                                <?php if (!empty($kamarTersedia)): ?>
                                    <?php foreach ($kamarTersedia as $k): 
                                        $depBaru = $k['harga_sewa'] * $kaliDeposit;
                                    ?>
                                        <option value="<?= $k['id_kamar'] ?>" 
                                                data-harga="<?= $k['harga_sewa'] ?>" 
                                                data-deposit="<?= $depBaru ?>">
                                            No. <?= esc($k['nomor_kamar']) ?> (<?= esc($k['kode_kamar']) ?>) - Rp <?= number_format($k['harga_sewa'], 0, ',', '.') ?>/bln - Deposit: Rp <?= number_format($depBaru,0,',','.') ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="" disabled>Maaf, belum ada kamar tersedia saat ini</option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <!-- PANEL INFO DEPOSIT (real-time) -->
                        <div class="deposit-panel" id="depositPanel" style="display:none;">
                            <h6 class="fw-bold mb-3 text-warning"><i class="bi bi-cash-coin me-1"></i>Informasi Deposit</h6>
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <td>Deposit Lama (kamar Anda sekarang)</td>
                                    <td class="text-end fw-bold text-primary">Rp <span id="depLama"><?= number_format($depositLama,0,',','.') ?></span></td>
                                </tr>
                                <tr>
                                    <td>Deposit Baru (kamar tujuan)</td>
                                    <td class="text-end fw-bold text-success">Rp <span id="depBaru">0</span></td>
                                </tr>
                                <tr style="border-top:2px solid #dee2e6;">
                                    <td><strong>Selisih Deposit</strong></td>
                                    <td class="text-end"><strong id="selisihDep">Rp 0</strong></td>
                                </tr>
                            </table>
                        </div>

                        <!-- ALERT KONDISI DEPOSIT -->
                        <div class="alert-wajib-bayar" id="alertWajibBayar">
                            <h6 class="fw-bold text-danger mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i>WAJIB BAYAR SELISIH DEPOSIT</h6>
                            <p class="mb-0 small">
                                Karena kamar baru lebih mahal, Anda <strong>WAJIB BAYAR SELISIH DEPOSIT</strong> sebesar 
                                <strong id="wajibBayarNominal">Rp 0</strong> setelah pindah disetujui admin.
                                <br>
                                <span class="text-muted">Selisih ini akan jadi tagihan tambahan di akun pembayaran Anda.</span>
                            </p>
                        </div>

                        <div class="alert-kembalian" id="alertKembalian">
                            <h6 class="fw-bold text-success mb-1"><i class="bi bi-cash-stack me-1"></i>DAPAT UANG KEMBALIAN</h6>
                            <p class="mb-0 small">
                                Karena kamar baru lebih murah, Anda akan <strong>DAPAT UANG KEMBALIAN</strong> sebesar 
                                <strong id="kembalianNominal">Rp 0</strong> setelah pindah disetujui admin.
                                <br>
                                <span class="text-muted">Admin akan transfer uang kembalian ke Anda & upload bukti transfer.</span>
                            </p>
                        </div>

                        <div class="alert-sama" id="alertSama">
                            <h6 class="fw-bold text-info mb-1"><i class="bi bi-check-circle me-1"></i>TIDAK ADA SELISIH DEPOSIT</h6>
                            <p class="mb-0 small">
                                Harga kamar baru sama dengan kamar lama. Deposit lama akan langsung dipindahkan ke kamar baru tanpa selisih.
                            </p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Alasan Pindah Kamar <span class="text-danger">*</span></label>
                            <textarea name="alasan" class="form-control" rows="3" required placeholder="Contoh: Pindah ke lantai atas, dekat dengan teman, dll."></textarea>
                        </div>

                        <!-- KOTAK KONFIRMASI ESTETIK -->
                        <div class="card konfirmasi-card border-0 mb-3 mt-4">
                            <div class="card-body p-4">
                                <h6 class="fw-bold mb-2 text-primary"><i class="bi bi-info-circle me-1"></i> Ketentuan Pindah Kamar:</h6>
                                <ul class="small text-muted mb-3">
                                    <li>Sisa durasi kontrak Anda akan dipindahkan ke kamar baru.</li>
                                    <li><strong>Tagihan bulan berjalan & bulan depan yang sudah Anda bayar di kamar lama DIPINDAHKAN ke sewa kamar baru (tidak hangus).</strong></li>
                                    <li>Tagihan bulan ke-3 dst yang belum dibayar akan dipindahkan ke kamar baru dengan <strong>harga kamar baru</strong>.</li>
                                    <li><strong class="text-danger">Jika kamar baru LEBIH MAHAL: Anda WAJIB BAYAR SELISIH HARGA untuk bulan-bulan yang sudah dibayar + selisih deposit.</strong></li>
                                    <li><strong class="text-success">Jika kamar baru LEBIH MURAH: selisih harga untuk bulan yang sudah dibayar + selisih deposit DIKEMBALIKAN ke Anda.</strong></li>
                                    <li><strong>Deposit lama Anda: Rp <?= number_format($depositLama,0,',','.') ?> (akan dipindahkan ke kamar baru).</strong></li>
                                    <li><strong>Deposit kamar baru = Harga sewa baru × <?= $kaliDeposit ?> (sesuai pengaturan sistem).</strong></li>
                                    <li>Jika kamar lama rusak/kotor, deposit akan dipotong sesuai kerusakan oleh admin.</li>
                                    <li><strong class="text-danger">Jika pindah di awal/pertengahan bulan, uang sewa untuk bulan tersebut di kamar lama TIDAK dikembalikan (hangus). Hanya deposit yang bisa dipindah/dikembalikan (dengan potongan kerusakan jika ada).</strong></li>
                                    <li>Admin akan inspeksi kamar lama sebelum menyetujui pindah.</li>
                                </ul>
                                
                                
                        <!-- KONFIRMASI: 9 PERTANYAAN -->
                        <h6 class="fw-bold mb-3 text-danger"><i class="bi bi-exclamation-triangle me-1"></i> Konfirmasi Kepastian Pindah Kamar:</h6>
                        <p class="small text-muted mb-4">Silakan jawab <strong>Iya</strong> pada <strong>SEMUA</strong> pertanyaan di bawah ini:</p>
                                <div class="pertanyaan-item">
                                    <p class="fw-semibold mb-2">1. Saya yakin ingin pindah kamar dan tidak akan membatalkan pengajuan ini.</p>
                                    <div class="btn-group w-100" role="group">
                                        <input type="radio" class="btn-check konfirmasi" name="konf1" id="konf1_ya" value="ya" onchange="cekSemuaKonfirmasi()">
                                        <label class="btn btn-outline-success" for="konf1_ya"><i class="bi bi-check-circle"></i> Iya</label>
                                        <input type="radio" class="btn-check konfirmasi" name="konf1" id="konf1_tidak" value="tidak" onchange="cekSemuaKonfirmasi()">
                                        <label class="btn btn-outline-danger" for="konf1_tidak"><i class="bi bi-x-circle"></i> Tidak</label>
                                    </div>
                                </div>

                                <div class="pertanyaan-item">
                                    <p class="fw-semibold mb-2">2. Saya mengerti bahwa tagihan bulan berjalan & bulan depan yang sudah saya bayar di kamar lama akan DIPINDAHKAN ke sewa kamar baru (tidak hangus), dan sisa tagihan bulan ke-3 dst yang belum dibayar juga akan dipindahkan dengan harga kamar baru.</p>
                                    <div class="btn-group w-100" role="group">
                                        <input type="radio" class="btn-check konfirmasi" name="konf2" id="konf2_ya" value="ya" onchange="cekSemuaKonfirmasi()">
                                        <label class="btn btn-outline-success" for="konf2_ya"><i class="bi bi-check-circle"></i> Iya</label>
                                        <input type="radio" class="btn-check konfirmasi" name="konf2" id="konf2_tidak" value="tidak" onchange="cekSemuaKonfirmasi()">
                                        <label class="btn btn-outline-danger" for="konf2_tidak"><i class="bi bi-x-circle"></i> Tidak</label>
                                    </div>
                                </div>

                                <div class="pertanyaan-item">
                                    <p class="fw-semibold mb-2">3. Saya mengerti bahwa jika kamar baru lebih mahal, saya WAJIB BAYAR SELISIH HARGA (untuk bulan yang sudah dibayar) + SELISIH DEPOSIT. Jika kamar baru lebih murah, selisihnya DIKEMBALIKAN ke saya.</p>
                                    <div class="btn-group w-100" role="group">
                                        <input type="radio" class="btn-check konfirmasi" name="konf3" id="konf3_ya" value="ya" onchange="cekSemuaKonfirmasi()">
                                        <label class="btn btn-outline-success" for="konf3_ya"><i class="bi bi-check-circle"></i> Iya</label>
                                        <input type="radio" class="btn-check konfirmasi" name="konf3" id="konf3_tidak" value="tidak" onchange="cekSemuaKonfirmasi()">
                                        <label class="btn btn-outline-danger" for="konf3_tidak"><i class="bi bi-x-circle"></i> Tidak</label>
                                    </div>
                                </div>

                                <div class="pertanyaan-item">
                                    <p class="fw-semibold mb-2">4. Saya mengerti bahwa deposit lama saya akan dipotong apabila kamar lama ditemukan rusak/kotor saat inspeksi.</p>
                                    <div class="btn-group w-100" role="group">
                                        <input type="radio" class="btn-check konfirmasi" name="konf4" id="konf4_ya" value="ya" onchange="cekSemuaKonfirmasi()">
                                        <label class="btn btn-outline-success" for="konf4_ya"><i class="bi bi-check-circle"></i> Iya</label>
                                        <input type="radio" class="btn-check konfirmasi" name="konf4" id="konf4_tidak" value="tidak" onchange="cekSemuaKonfirmasi()">
                                        <label class="btn btn-outline-danger" for="konf4_tidak"><i class="bi bi-x-circle"></i> Tidak</label>
                                    </div>
                                </div>

                                <div class="pertanyaan-item">
                                    <p class="fw-semibold mb-2">5. Saya bersedia menunggu proses inspeksi dari admin sebelum pengajuan pindah disetujui.</p>
                                    <div class="btn-group w-100" role="group">
                                        <input type="radio" class="btn-check konfirmasi" name="konf5" id="konf5_ya" value="ya" onchange="cekSemuaKonfirmasi()">
                                        <label class="btn btn-outline-success" for="konf5_ya"><i class="bi bi-check-circle"></i> Iya</label>
                                        <input type="radio" class="btn-check konfirmasi" name="konf5" id="konf5_tidak" value="tidak" onchange="cekSemuaKonfirmasi()">
                                        <label class="btn btn-outline-danger" for="konf5_tidak"><i class="bi bi-x-circle"></i> Tidak</label>
                                    </div>
                                </div>

                                <div class="pertanyaan-item">
                                    <p class="fw-semibold mb-2">6. Saya mengerti bahwa kunci kamar lama wajib dikembalikan ke admin setelah pindah disetujui, dan saya sudah memastikan tidak ada barang pribadi tertinggal di kamar lama.</p>
                                    <div class="btn-group w-100" role="group">
                                        <input type="radio" class="btn-check konfirmasi" name="konf6" id="konf6_ya" value="ya" onchange="cekSemuaKonfirmasi()">
                                        <label class="btn btn-outline-success" for="konf6_ya"><i class="bi bi-check-circle"></i> Iya</label>
                                        <input type="radio" class="btn-check konfirmasi" name="konf6" id="konf6_tidak" value="tidak" onchange="cekSemuaKonfirmasi()">
                                        <label class="btn btn-outline-danger" for="konf6_tidak"><i class="bi bi-x-circle"></i> Tidak</label>
                                    </div>
                                </div>

                                <div class="pertanyaan-item">
                                    <p class="fw-semibold mb-2">7. Saya mengerti bahwa keputusan admin terkait potongan kerusakan adalah final dan tidak dapat diganggu gugat.</p>
                                    <div class="btn-group w-100" role="group">
                                        <input type="radio" class="btn-check konfirmasi" name="konf7" id="konf7_ya" value="ya" onchange="cekSemuaKonfirmasi()">
                                        <label class="btn btn-outline-success" for="konf7_ya"><i class="bi bi-check-circle"></i> Iya</label>
                                        <input type="radio" class="btn-check konfirmasi" name="konf7" id="konf7_tidak" value="tidak" onchange="cekSemuaKonfirmasi()">
                                        <label class="btn btn-outline-danger" for="konf7_tidak"><i class="bi bi-x-circle"></i> Tidak</label>
                                    </div>
                                </div>

                                <div class="pertanyaan-item">
                                    <p class="fw-semibold mb-2">8. <strong class="text-danger">Saya mengerti bahwa jika saya pindah kamar di awal/pertengahan bulan, uang sewa untuk bulan tersebut di kamar lama TIDAK dikembalikan (hangus).</strong> Hanya deposit yang bisa dipindah/dikembalikan (dengan potongan kerusakan jika ada).</p>
                                    <div class="btn-group w-100" role="group">
                                        <input type="radio" class="btn-check konfirmasi" name="konf8" id="konf8_ya" value="ya" onchange="cekSemuaKonfirmasi()">
                                        <label class="btn btn-outline-success" for="konf8_ya"><i class="bi bi-check-circle"></i> Iya</label>
                                        <input type="radio" class="btn-check konfirmasi" name="konf8" id="konf8_tidak" value="tidak" onchange="cekSemuaKonfirmasi()">
                                        <label class="btn btn-outline-danger" for="konf8_tidak"><i class="bi bi-x-circle"></i> Tidak</label>
                                    </div>
                                </div>

                                <div class="pertanyaan-item">
                                    <p class="fw-semibold mb-2">9. Semua data yang saya isi pada form ini adalah benar dan dapat dipertanggungjawabkan.</p>
                                    <div class="btn-group w-100" role="group">
                                        <input type="radio" class="btn-check konfirmasi" name="konf9" id="konf9_ya" value="ya" onchange="cekSemuaKonfirmasi()">
                                        <label class="btn btn-outline-success" for="konf9_ya"><i class="bi bi-check-circle"></i> Iya</label>
                                        <input type="radio" class="btn-check konfirmasi" name="konf9" id="konf9_tidak" value="tidak" onchange="cekSemuaKonfirmasi()">
                                        <label class="btn btn-outline-danger" for="konf9_tidak"><i class="bi bi-x-circle"></i> Tidak</label>
                                    </div>
                                </div>

                        <button type="submit" class="btn btn-primary w-100 py-3 fw-bold fs-5" id="btnPindah">
                            <i class="bi bi-arrow-left-right me-2"></i>Ajukan Pindah Kamar
                        </button>
                    </form>
                    <?php else: ?>
                    <!-- SEDANG DIPROSES -->
                    <div class="alert alert-warning text-center py-4">
                        <i class="bi bi-clock-history fs-1 d-block mb-2"></i>
                        <h5 class="fw-bold">Pengajuan Sedang Diproses</h5>
                        <p class="mb-0">Anda sudah mengajukan pindah kamar. Mohon tunggu admin inspeksi & menyetujui.</p>
                    </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="alert alert-warning text-center py-4">
                        <i class="bi bi-exclamation-triangle fs-1 d-block mb-2"></i>
                        <h5 class="fw-bold">Anda Tidak Memiliki Sewa Aktif</h5>
                        <p class="mb-0">Tidak bisa ajukan pindah kamar karena tidak ada kamar aktif.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- RIWAYAT PENGAJUAN PINDAH -->
        <?php if (!empty($riwayat)): ?>
        <div class="card border-0 shadow-sm">
            <div class="card-header fw-semibold py-3 bg-transparent">
                <i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Pengajuan Pindah
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Dari Kamar</th>
                                <th>Pindah Ke</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Bukti Refund</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no=1; foreach ($riwayat as $r): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td>No. <?= esc($r['nomor_kamar_lama']) ?></td>
                                <td>No. <?= esc($r['nomor_kamar_baru']) ?></td>
                                <td><small><?= esc($r['tanggal_pengajuan']) ?></small></td>
                                <td>
                                    <?php if ($r['status'] == 'menunggu'): ?>
                                        <span class="badge bg-warning text-dark">Menunggu</span>
                                    <?php elseif ($r['status'] == 'disetujui'): ?>
                                        <span class="badge bg-success">Disetujui</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Ditolak</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($r['bukti_refund'])): ?>
                                        <button type="button" onclick="bukaBukti('<?= esc($r['bukti_refund'], 'js') ?>')" class="btn btn-sm btn-outline-success" title="Lihat Bukti Refund">
                                            <i class="bi bi-eye"></i> Refund
                                        </button>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
const depositLamaJs = <?= (int)$depositLama ?>;

function formatRupiah(angka) {
    return 'Rp ' + Number(angka).toLocaleString('id-ID');
}

function hitungDeposit() {
    const select = document.getElementById('kamarBaruSelect');
    const opt = select.options[select.selectedIndex];
    const panel = document.getElementById('depositPanel');
    const alertWajibBayar = document.getElementById('alertWajibBayar');
    const alertKembalian = document.getElementById('alertKembalian');
    const alertSama = document.getElementById('alertSama');

    // Reset semua alert
    alertWajibBayar.style.display = 'none';
    alertKembalian.style.display = 'none';
    alertSama.style.display = 'none';

    if (!select.value) {
        panel.style.display = 'none';
        return;
    }

    const depBaru = parseInt(opt.dataset.deposit) || 0;
    const selisih = depBaru - depositLamaJs;

    // Tampilkan panel
    panel.style.display = '';
    document.getElementById('depBaru').textContent = depBaru.toLocaleString('id-ID');
    document.getElementById('selisihDep').textContent = (selisih >= 0 ? '+' : '-') + Math.abs(selisih).toLocaleString('id-ID');
    document.getElementById('selisihDep').style.color = selisih > 0 ? '#dc3545' : (selisih < 0 ? '#198754' : '#0dcaf0');

    if (selisih > 0) {
        // WAJIB BAYAR SELISIH
        alertWajibBayar.style.display = '';
        document.getElementById('wajibBayarNominal').textContent = formatRupiah(selisih);
    } else if (selisih < 0) {
        // DAPAT KEMBALIAN
        alertKembalian.style.display = '';
        document.getElementById('kembalianNominal').textContent = formatRupiah(Math.abs(selisih));
    } else {
        // SAMA
        alertSama.style.display = '';
    }
}

function cekSemuaKonfirmasi() {
    const radios = document.querySelectorAll('.konfirmasi');
    const btn = document.getElementById('btnPindah');
    
    let yaCount = 0;
    radios.forEach(radio => {
        if (radio.checked && radio.value === 'ya') {
            yaCount++;
        }
    });
    
    if (yaCount === 9) {
        btn.disabled = false;
        btn.classList.remove('btn-secondary');
        btn.classList.add('btn-primary');
    } else {
        btn.disabled = true;
        btn.classList.remove('btn-primary');
        btn.classList.add('btn-secondary');
    }
}
</script>

<!-- MODAL BUKTI (image + PDF) dengan tombol X close -->
<style>
.bukti-modal-overlay {
    display: none; position: fixed; z-index: 9999; left: 0; top: 0;
    width: 100%; height: 100%; background: rgba(0,0,0,0.92);
    justify-content: center; align-items: center; padding: 20px;
    backdrop-filter: blur(2px);
}
.bukti-modal-overlay.show { display: flex; }
.bukti-modal-box {
    max-width: 92%; max-height: 90vh; background: white;
    border-radius: 12px; padding: 20px; position: relative;
    box-shadow: 0 20px 60px rgba(0,0,0,0.5);
}
.bukti-modal-box img { max-width: 100%; max-height: 75vh; display: block; margin: 0 auto; border-radius: 8px; }
.bukti-modal-box iframe { max-width: 100%; width: 80vw; max-height: 75vh; height: 75vh; display: block; margin: 0 auto; border: none; border-radius: 8px; }
.bukti-modal-close-inner {
    position: absolute; top: 10px; right: 10px; background: #dc3545;
    color: white; border: none; width: 44px; height: 44px;
    border-radius: 50%; font-size: 1.3rem; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.3); z-index: 2;
    transition: all 0.2s;
}
.bukti-modal-close-inner:hover { background: #bb2d3b; transform: scale(1.1) rotate(90deg); }
.bukti-modal-close-outer {
    position: fixed; top: 20px; right: 20px; background: rgba(255,255,255,0.15);
    color: white; border: 2px solid rgba(255,255,255,0.4); width: 50px; height: 50px;
    border-radius: 50%; font-size: 1.5rem; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    backdrop-filter: blur(4px); transition: all 0.2s; z-index: 10000;
}
.bukti-modal-close-outer:hover { background: rgba(220,53,69,0.8); border-color: #dc3545; transform: scale(1.1); }
.bukti-modal-caption {
    text-align: center; margin-top: 12px; padding: 8px;
    background: #f8f9fa; border-radius: 6px; font-size: 0.85rem; color: #495057;
    word-break: break-all;
}
.bukti-modal-hint {
    text-align: center; margin-top: 8px; font-size: 0.75rem; color: #6c757d;
}
</style>

<div id="buktiModalOverlay" class="bukti-modal-overlay" onclick="if(event.target.id==='buktiModalOverlay')tutupBukti()">
    <button type="button" class="bukti-modal-close-outer" onclick="tutupBukti()" title="Tutup (ESC)">
        <i class="bi bi-x-lg"></i>
    </button>
    <div class="bukti-modal-box">
        <button type="button" class="bukti-modal-close-inner" onclick="tutupBukti()" title="Tutup">
            <i class="bi bi-x-lg"></i>
        </button>
        <div id="buktiModalContent"></div>
        <div id="buktiModalCaption" class="bukti-modal-caption"></div>
        <div class="bukti-modal-hint"><i class="bi bi-info-circle me-1"></i>Klik tombol X, klik area gelap di luar, atau tekan ESC untuk tutup</div>
    </div>
</div>

<script>
function bukaBukti(file) {
    const overlay = document.getElementById('buktiModalOverlay');
    const content = document.getElementById('buktiModalContent');
    const caption = document.getElementById('buktiModalCaption');
    const url = '/uploads/' + file;

    // Cek extension — kalau PDF, pakai iframe; kalau image, pakai <img>
    const ext = file.split('.').pop().toLowerCase();
    if (ext === 'pdf') {
        content.innerHTML = '<iframe src="' + url + '" title="Bukti PDF"></iframe>';
    } else {
        content.innerHTML = '<img src="' + url + '" alt="Bukti">';
    }
    caption.innerHTML = '<i class="bi bi-file-earmark me-1"></i><strong>File:</strong> ' + file;
    overlay.classList.add('show');
    document.body.style.overflow = 'hidden';
}
function tutupBukti() {
    const overlay = document.getElementById('buktiModalOverlay');
    overlay.classList.remove('show');
    document.body.style.overflow = '';
    // Clear content supaya video/audio (kalau ada) berhenti
    setTimeout(() => {
        document.getElementById('buktiModalContent').innerHTML = '';
    }, 200);
}
// ESC untuk tutup
document.addEventListener('keydown', e => { if (e.key === 'Escape') tutupBukti(); });
</script>

<?= $this->endSection() ?>
