# 🚨 Panduan Troubleshooting - Rumah Kos Revisi v3

## "Banyak Error" — Solusi Step by Step

Pesan "banyak error" biasanya disebabkan **SATU hal saja**: migration belum dijalankan, sehingga kolom baru (`nama_bank`, `nomor_rekening`, `refund_status`, dll) belum ada di database. Akibatnya semua halaman yang akses kolom itu akan error.

**Selalu mulai dari langkah 1 dulu — biasanya langsung fix semua error.**

---

## ✅ LANGKAH 1: Jalankan Installer (PALING GAMPANG)

1. Extract zip ke folder project
2. Buka browser, akses: `http://localhost/rumah-kos-anda/installer`
   - (ganti `rumah-kos-anda` dengan nama folder project Anda)
3. Installer akan otomatis cek:
   - ✅/❌ Koneksi database
   - ✅/❌ Kolom rekening user ada?
   - ✅/❌ Kolom refund sewa ada?
   - ✅/❌ Setting kontak & metode pembayaran ada?
   - ✅/❌ Tabel utama ada?
   - ✅/❌ User admin ada?

4. Klik tombol **"Jalankan Keduanya"** (warna kuning)
5. Tunggu proses selesai, refresh halaman
6. Semua check harus hijau ✅

Kalau installer juga error (blank page / 500), lanjut ke **Langkah 2**.

---

## ✅ LANGKAH 2: Import SQL Manual via phpMyAdmin (PALING PASTI)

1. Buka phpMyAdmin: `http://localhost/phpmyadmin`
2. Klik database rumah kos Anda di sidebar kiri (mis. `db_rumah_kos`)
3. Klik tab **"SQL"** di atas
4. Buka file `database_revisi_v1_v2.sql` (ada di root folder project) dengan Notepad/VSCode
5. **Copy semua isi file** (Ctrl+A → Ctrl+C)
6. **Paste ke kotak SQL** di phpMyAdmin (Ctrl+V)
7. Klik tombol **"Go"** / **"Kirim"** di kanan bawah
8. Tunggu sampai muncul pesan sukses (biasanya "Your SQL query has been executed successfully")
9. Buka kembali aplikasi → error harus hilang

### Verifikasi sukses:
- Tabel `user` punya kolom baru: `nama_bank`, `nomor_rekening`, `nama_pemilik_rek`, `ewallet_type`, `ewallet_number`
- Tabel `sewa` punya kolom baru: `bukti_refund`, `tanggal_refund`, `total_refund`, `refund_status`, `refund_metode`
- Tabel `pengaturan` punya ~26 baris (kalau sebelumnya 12, sekarang 26)

---

## ✅ LANGKAH 3: Jalankan spark CLI (Kalau Pakai Terminal)

### Linux/Mac:
```bash
cd /path/to/rumah-kos
php spark migrate
php spark db:seed InitialSeeder
```

### Windows XAMPP:
```cmd
cd C:\xampp\htdocs\rumah-kos
C:\xampp\php\php.exe spark migrate
C:\xampp\php\php.exe spark db:seed InitialSeeder
```

Kalau muncul error "spark not found", pastikan Anda ada di folder project yang benar (ada file `spark` di sana).

---

## 🔧 Error Spesifik & Solusinya

### Error: "Call to undefined function get_kontak_kos()"
**Penyebab:** File `app/Common.php` tidak ter-load atau function belum didefinisikan.

**Solusi:**
1. Pastikan file `app/Common.php` ada dan berisi function `get_kontak_kos`, `get_metode_pembayaran`, `kolom_ada`, `rekening_select_clause`
2. Clear cache CI4: hapus isi folder `writable/cache/`
3. Restart Apache/Nginx

---

### Error: "Unknown column 'nama_bank' in 'field list'"
**Penyebab:** Migration belum dijalankan — kolom belum ada di tabel user.

**Solusi:** Jalankan Langkah 1, 2, atau 3 di atas.

---

### Error: "Call to undefined function kolom_ada()" / "rekening_select_clause()"
**Penyebab:** File `app/Common.php` versi lama (belum ada function defensif).

**Solusi:** Extract ulang zip v3 dengan overwrite semua file.

---

### Error 500 / Blank Page saat akses `/installer`
**Penyebab:** Kemungkinan error fatal di file PHP.

**Solusi:**
1. Cek error log di `writable/logs/log-*.log`
2. Aktifkan debug mode: edit `.env`, set `CI_ENVIRONMENT = development`
3. Buka `/installer` lagi — sekarang error harus tampil jelas

---

### Error: "Cannot redeclare function get_metode_pembayaran()"
**Penyebab:** Function didefinisikan 2x (mungkin dari copy-paste berulang).

**Solusi:** Cek file `app/Common.php` — pastikan setiap function hanya didefinisikan 1x.

---

### Halaman login/admin blank putih
**Penyebab:** PHP fatal error yang disembunyikan.

**Solusi:**
1. Edit `.env`, ubah `CI_ENVIRONMENT = production` → `CI_ENVIRONMENT = development`
2. Refresh halaman — error akan muncul dengan detail
3. Setelah fix, balik ke `production` untuk keamanan

---

## 📋 Checklist Post-Install

Setelah semua error hilang, pastikan:

- [ ] Buka `/installer` → semua check hijau ✅
- [ ] Login admin (username: `admin`, password: `admin123`)
- [ ] Buka **Pengaturan** → isi:
  - Rekening bank kos (BCA/Mandiri/dll)
  - E-wallet (DANA/OVO/GoPay/ShopeePay)
  - Kontak kos (nama, alamat, email, telepon, WA)
  - Sosial media (Facebook, Instagram, TikTok, YouTube)
  - Google Maps embed URL
- [ ] Buat user dummy → login → buka **Profil** → isi rekening
- [ ] Test flow pembayaran: user upload bukti → admin verifikasi
- [ ] Test flow checkout: user ajukan → admin inspeksi → upload bukti refund
- [ ] Cek landing page (`/`) → kontak & sosial media tampil di footer

---

## 🆘 Masih Error? Kirim Info Ini

Kalau setelah langkah 1-3 masih error, kirim info berikut supaya saya bisa bantu lebih cepat:

1. **Screenshot error** (atau copy paste teks error lengkap)
2. **URL** yang error (mis. `/admin/pembayaran/detail/5`)
3. **Apakah installer (`/installer`) bisa dibuka?**
4. **Output query SQL ini di phpMyAdmin:**
   ```sql
   SHOW COLUMNS FROM user LIKE 'nama_bank';
   SHOW COLUMNS FROM sewa LIKE 'refund_status';
   SELECT COUNT(*) FROM pengaturan;
   ```
5. **Isi file `writable/logs/log-*.log`** (10 baris terakhir)

---

## 📦 File Penting di Revisi v3

| File | Fungsi |
|------|--------|
| `database_revisi_v1_v2.sql` | Script SQL untuk import manual di phpMyAdmin |
| `app/Controllers/Installer.php` | Halaman cek & fix DB di `/installer` |
| `app/Common.php` | Helper function (`get_kontak_kos`, `kolom_ada`, dll) |
| `REVISI_LOG.md` | Log perubahan v1 |
| `REVISI_LOG_V2.md` | Log perubahan v2 |
| `REVISI_LOG_V3.md` | Log perubahan v3 (ini) |
