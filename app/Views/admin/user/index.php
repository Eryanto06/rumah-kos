<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<!-- FORM KIRIM WA MANUAL + AUTO-DETECT NOMOR + AUTO-FILL PESAN -->
<div class="card mb-3 border-success">
    <div class="card-header fw-semibold bg-success text-white">
        <i class="bi bi-whatsapp me-2"></i>Kirim WhatsApp ke Nomor Manual
    </div>
    <div class="card-body">
        <form id="waManualForm" onsubmit="kirimWA(event)" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-semibold">Nomor HP (08xxx atau 628xxx)</label>
                <input type="text" id="nomorWA" class="form-control" placeholder="08123456789" required oninput="cekNomor()">
                <div id="infoNomor" class="form-text small mt-1"></div>
            </div>
            <div class="col-md-7">
                <label class="form-label small fw-semibold">Pesan (otomatis terisi kalau nomor dikenali)</label>
                <input type="text" id="pesanWA" class="form-control" placeholder="Halo, saya admin Rumah Kos...">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-success w-100">
                    <i class="bi bi-whatsapp me-1"></i>Kirim
                </button>
            </div>
        </form>
        <small class="text-muted mt-2 d-block">
            <i class="bi bi-info-circle"></i> Ketik nomor → pesan otomatis terisi sesuai status (penghuni/pendaftar). Atau ketik pesan sendiri. Akan dibuka di WhatsApp Web.
        </small>
    </div>
</div>

<!-- KOTAK PENCARIAN -->
<div class="card mb-3 border-primary">
    <div class="card-body py-3">
        <div class="row g-2 align-items-center">
            <div class="col-md-8">
                <div class="input-group">
                    <span class="input-group-text bg-primary text-white"><i class="bi bi-search"></i></span>
                    <input type="text" id="searchInput" class="form-control" placeholder="Cari nama, nomor HP, email, atau username..." oninput="cariUser()">
                </div>
            </div>
            <div class="col-md-4">
                <select id="searchFilter" class="form-select" onchange="cariUser()">
                    <option value="all">Cari di semua kolom</option>
                    <option value="nama">Cari berdasarkan Nama</option>
                    <option value="hp">Cari berdasarkan No. HP</option>
                    <option value="email">Cari berdasarkan Email</option>
                    <option value="username">Cari berdasarkan Username</option>
                    <option value="kamar">Cari berdasarkan No. Kamar</option>
                </select>
            </div>
        </div>
        <div class="mt-2 d-flex justify-content-between align-items-center">
            <small class="text-muted" id="searchInfo">Menampilkan semua data</small>
            <button class="btn btn-sm btn-outline-secondary" onclick="resetSearch()">
                <i class="bi bi-x-circle me-1"></i>Reset Pencarian
            </button>
        </div>
    </div>
</div>

<ul class="nav nav-tabs mb-3" id="userTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link <?= ($tab ?? 'penghuni') === 'penghuni' ? 'active' : '' ?>"
                data-bs-toggle="tab" data-bs-target="#penghuni" type="button">
            <i class="bi bi-house-door me-1"></i>Penghuni Aktif
            <span class="badge bg-success ms-1"><?= $total_penghuni ?></span>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link <?= ($tab ?? '') === 'pendaftar' ? 'active' : '' ?>"
                data-bs-toggle="tab" data-bs-target="#pendaftar" type="button">
            <i class="bi bi-person-plus me-1"></i>Pendaftar (Belum Sewa)
            <span class="badge bg-warning text-dark ms-1"><?= $total_pendaftar ?></span>
        </button>
    </li>
</ul>

<div class="tab-content">
    <!-- TAB: PENGHUNI AKTIF -->
    <div class="tab-pane fade <?= ($tab ?? 'penghuni') === 'penghuni' ? 'show active' : '' ?>" id="penghuni">
        <div class="card">
            <div class="card-header fw-semibold bg-success text-white">
                <i class="bi bi-house-door me-2"></i>Data Penghuni Aktif (Sudah Sewa Kamar)
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="tabelPenghuni">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Kamar</th>
                                <th>Mulai Huni</th>
                                <th>Selesai</th>
                                <th>No. HP</th>
                                <th>Email</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($penghuni)): $no=1; foreach ($penghuni as $u): ?>
                            <tr data-nama="<?= esc($u['nama']) ?>" data-hp="<?= esc($u['no_hp']) ?>" data-email="<?= esc($u['email']) ?>" data-kamar="<?= esc($u['nomor_kamar'] ?? '') ?>">
                                <td><?= $no++ ?></td>
                                <td>
                                    <strong><?= esc($u['nama']) ?></strong>
                                    <span class="badge bg-success ms-1">Aktif</span>
                                </td>
                                <td>
                                    <strong>No. <?= esc($u['nomor_kamar'] ?? '-') ?></strong>
                                    <small class="text-muted d-block">(<?= esc($u['kode_kamar'] ?? '-') ?>)</small>
                                </td>
                                <td><small><?= esc($u['tanggal_mulai'] ?? '-') ?></small></td>
                                <td><small><?= esc($u['tanggal_selesai'] ?? '-') ?></small></td>
                                <td data-nomor="<?= esc($u['no_hp']) ?>" data-nama="<?= esc($u['nama']) ?>" data-role="penghuni" data-kamar="<?= esc($u['nomor_kamar'] ?? '') ?>">
                                    <a href="<?= link_wa($u['no_hp']) ?>" target="_blank" class="text-decoration-none">
                                        <i class="bi bi-whatsapp text-success"></i> <?= esc($u['no_hp']) ?>
                                    </a>
                                </td>
                                <td><small><?= esc($u['email']) ?></small></td>
                                <td>
                                    <a href="<?= link_wa($u['no_hp'], 'Halo ' . $u['nama'] . ', saya admin Rumah Kos. Ada yang bisa saya bantu?') ?>" target="_blank"
                                       class="btn btn-success btn-sm" title="Chat WhatsApp Web">
                                        <i class="bi bi-whatsapp"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; else: ?>
                            <tr><td colspan="8" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Belum ada penghuni aktif
                            </td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- TAB: PENDAFTAR (BELUM SEWA) -->
    <div class="tab-pane fade <?= ($tab ?? '') === 'pendaftar' ? 'show active' : '' ?>" id="pendaftar">
        <div class="card">
            <div class="card-header fw-semibold bg-warning">
                <i class="bi bi-person-plus me-2"></i>Data Pendaftar (Sudah Daftar Akun, Belum Sewa Kamar)
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    Pendaftar ini sudah membuat akun tapi belum mengajukan sewa kamar. Anda bisa follow-up via WhatsApp untuk menawarkan kamar tersedia.
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle" id="tabelPendaftar">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Username</th>
                                <th>No. HP</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($pendaftar)): $no=1; foreach ($pendaftar as $u): ?>
                            <tr data-nama="<?= esc($u['nama']) ?>" data-hp="<?= esc($u['no_hp']) ?>" data-email="<?= esc($u['email']) ?>" data-username="<?= esc($u['username']) ?>">
                                <td><?= $no++ ?></td>
                                <td>
                                    <strong><?= esc($u['nama']) ?></strong>
                                    <span class="badge bg-warning text-dark ms-1">Pendaftar</span>
                                </td>
                                <td><small><?= esc($u['email']) ?></small></td>
                                <td><small><?= esc($u['username']) ?></small></td>
                                <td data-nomor="<?= esc($u['no_hp']) ?>" data-nama="<?= esc($u['nama']) ?>" data-role="pendaftar">
                                    <a href="<?= link_wa($u['no_hp']) ?>" target="_blank" class="text-decoration-none">
                                        <i class="bi bi-whatsapp text-success"></i> <?= esc($u['no_hp']) ?>
                                    </a>
                                </td>
                                <td>
                                    <a href="<?= link_wa($u['no_hp'], 'Halo ' . $u['nama'] . ', saya admin Rumah Kos. Anda sudah daftar akun. Apakah tertarik menyewa kamar? Ada kamar kosong yang bisa Anda lihat.') ?>" target="_blank"
                                       class="btn btn-success btn-sm" title="Follow-up via WhatsApp">
                                        <i class="bi bi-whatsapp"></i> Follow-up
                                    </a>
                                    <form action="/admin/user/hapus/<?= $u['id_user'] ?>" method="post" style="display:inline;">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Yakin hapus pendaftar ini?\n\nAkun akan dihapus permanen.\nRiwayat sewa & pembayaran tetap tersimpan.\n\nLanjutkan?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; else: ?>
                            <tr><td colspan="6" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Tidak ada pendaftar yang belum sewa
                            </td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
/**
 * PENCARIAN USER REAL-TIME
 * Cari di kedua tabel (penghuni & pendaftar) berdasarkan kolom yang dipilih
 */
function cariUser() {
    const keyword = document.getElementById('searchInput').value.toLowerCase().trim();
    const filter = document.getElementById('searchFilter').value;

    const tabelPenghuni = document.querySelector('#tabelPenghuni tbody');
    const tabelPendaftar = document.querySelector('#tabelPendaftar tbody');

    let totalTampil = 0;
    let totalSemua = 0;

    // Proses tabel penghuni
    if (tabelPenghuni) {
        const baris = tabelPenghuni.querySelectorAll('tr');
        baris.forEach(tr => {
            if (!tr.dataset.nama) return; // skip row "kosong"
            totalSemua++;

            let cocok = false;
            if (keyword === '') {
                cocok = true;
            } else {
                switch(filter) {
                    case 'nama':
                        cocok = tr.dataset.nama.toLowerCase().includes(keyword);
                        break;
                    case 'hp':
                        cocok = tr.dataset.hp.toLowerCase().includes(keyword);
                        break;
                    case 'email':
                        cocok = tr.dataset.email.toLowerCase().includes(keyword);
                        break;
                    case 'kamar':
                        cocok = tr.dataset.kamar.toLowerCase().includes(keyword);
                        break;
                    default: // 'all'
                        cocok = tr.dataset.nama.toLowerCase().includes(keyword) ||
                                tr.dataset.hp.toLowerCase().includes(keyword) ||
                                tr.dataset.email.toLowerCase().includes(keyword) ||
                                tr.dataset.kamar.toLowerCase().includes(keyword);
                }
            }

            tr.style.display = cocok ? '' : 'none';
            if (cocok) totalTampil++;
        });
    }

    // Proses tabel pendaftar
    if (tabelPendaftar) {
        const baris = tabelPendaftar.querySelectorAll('tr');
        baris.forEach(tr => {
            if (!tr.dataset.nama) return;
            totalSemua++;

            let cocok = false;
            if (keyword === '') {
                cocok = true;
            } else {
                switch(filter) {
                    case 'nama':
                        cocok = tr.dataset.nama.toLowerCase().includes(keyword);
                        break;
                    case 'hp':
                        cocok = tr.dataset.hp.toLowerCase().includes(keyword);
                        break;
                    case 'email':
                        cocok = tr.dataset.email.toLowerCase().includes(keyword);
                        break;
                    case 'username':
                        cocok = tr.dataset.username.toLowerCase().includes(keyword);
                        break;
                    default: // 'all'
                        cocok = tr.dataset.nama.toLowerCase().includes(keyword) ||
                                tr.dataset.hp.toLowerCase().includes(keyword) ||
                                tr.dataset.email.toLowerCase().includes(keyword) ||
                                tr.dataset.username.toLowerCase().includes(keyword);
                }
            }

            tr.style.display = cocok ? '' : 'none';
            if (cocok) totalTampil++;
        });
    }

    // Update info
    const infoEl = document.getElementById('searchInfo');
    if (keyword === '') {
        infoEl.innerHTML = 'Menampilkan semua data (' + totalSemua + ' user)';
        infoEl.className = 'text-muted small';
    } else {
        infoEl.innerHTML = 'Menampilkan <strong>' + totalTampil + '</strong> dari ' + totalSemua + ' user untuk pencarian "' + keyword + '"';
        infoEl.className = 'text-primary small';
    }
}

function resetSearch() {
    document.getElementById('searchInput').value = '';
    document.getElementById('searchFilter').value = 'all';
    cariUser();
    document.getElementById('searchInput').focus();
}

/**
 * Auto-detect nomor HP yang diketik + auto-fill pesan sesuai role
 */
function cekNomor() {
    const inputNomor = document.getElementById('nomorWA').value.trim();
    const infoEl = document.getElementById('infoNomor');
    const pesanEl = document.getElementById('pesanWA');

    if (inputNomor.length < 4) {
        infoEl.innerHTML = '';
        return;
    }

    let nomorBersih = inputNomor.replace(/[^0-9]/g, '');

    if (nomorBersih.startsWith('620')) {
        nomorBersih = '62' + nomorBersih.substring(3);
    } else if (nomorBersih.startsWith('62')) {
        // sudah benar
    } else if (nomorBersih.startsWith('0')) {
        nomorBersih = '62' + nomorBersih.substring(1);
    } else if (nomorBersih.startsWith('8')) {
        nomorBersih = '62' + nomorBersih;
    }

    const semuaTd = document.querySelectorAll('td[data-nomor]');
    let ditemukan = false;

    for (let td of semuaTd) {
        let nomorDb = td.getAttribute('data-nomor');
        nomorDb = nomorDb.replace(/[^0-9]/g, '');

        if (nomorDb.startsWith('620')) {
            nomorDb = '62' + nomorDb.substring(3);
        } else if (nomorDb.startsWith('62')) {
            // sudah benar
        } else if (nomorDb.startsWith('0')) {
            nomorDb = '62' + nomorDb.substring(1);
        } else if (nomorDb.startsWith('8')) {
            nomorDb = '62' + nomorDb;
        }

        if (nomorDb === nomorBersih) {
            const nama = td.getAttribute('data-nama');
            const role = td.getAttribute('data-role');
            const kamar = td.getAttribute('data-kamar');

            let infoTambahan = '';
            if (role === 'penghuni' && kamar) {
                infoTambahan = ' · Kamar No. ' + kamar;
                infoEl.innerHTML = '<span class="text-success fw-semibold"><i class="bi bi-check-circle-fill"></i> Ditemukan: ' + nama + ' (Penghuni Aktif' + infoTambahan + ')</span>';
                pesanEl.value = 'Halo ' + nama + ', saya admin Rumah Kos. Ada yang bisa saya bantu terkait kamar Anda?';
            } else {
                infoEl.innerHTML = '<span class="text-warning fw-semibold"><i class="bi bi-check-circle-fill"></i> Ditemukan: ' + nama + ' (Pendaftar - Belum Sewa)</span>';
                pesanEl.value = 'Halo ' + nama + ', saya admin Rumah Kos. Anda sudah daftar akun. Apakah tertarik menyewa kamar? Ada kamar kosong yang bisa Anda lihat.';
            }

            ditemukan = true;
            break;
        }
    }

    if (!ditemukan) {
        infoEl.innerHTML = '<span class="text-muted"><i class="bi bi-info-circle"></i> Nomor tidak terdaftar di sistem. Ketik pesan sendiri atau biarkan kosong.</span>';
    }
}

function kirimWA(e) {
    e.preventDefault();
    let nomor = document.getElementById('nomorWA').value.trim();
    let pesan = document.getElementById('pesanWA').value.trim();

    nomor = nomor.replace(/[^0-9]/g, '');

    if (nomor.startsWith('620')) {
        nomor = '62' + nomor.substring(3);
    } else if (nomor.startsWith('62')) {
        // sudah benar
    } else if (nomor.startsWith('0')) {
        nomor = '62' + nomor.substring(1);
    } else if (nomor.startsWith('8')) {
        nomor = '62' + nomor;
    }

    let url = 'https://web.whatsapp.com/send?phone=' + nomor;
    if (pesan) {
        url += '&text=' + encodeURIComponent(pesan);
    }

    window.open(url, '_blank');
}
</script>

<?= $this->endSection() ?>