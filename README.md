# Sistem Informasi Manajemen Rumah Kos

Sistem informasi Manajemen rumah kos berbasis web, dibangun dengan CodeIgniter 4 dan MySQL.

## Fitur

- Manajemen Kamar (tambah, edit, hapus)
- Pengajuan Sewa dengan deposit
- Pembayaran tagihan bulanan + upload bukti transfer
- Denda otomatis (cron job harian)
- Perpanjangan kontrak
- Pindah kamar dengan perhitungan selisih harga
- Checkout dengan refund deposit
- Keluhan (bisa anonim)
- Pengumuman
- Notifikasi otomatis

## Teknologi

- Backend: PHP 8 + CodeIgniter 4
- Database: MySQL 8
- Frontend: Bootstrap 5

## Cara Install

1. Copy folder ke `C:\xampp\htdocs\`
2. Buat database `db_rumah_kos` di phpMyAdmin
3. Import `database_revisi_v1_v2.sql`
4. Edit `.env` sesuai database Anda
5. Akses: `http://localhost/kos rumah/`

## Login Default

- Admin: `admin` 
- password: 123123

## Alur Logika Sistem

logika misalnya, di pindah-kamar penghuni aktif yng ingin pindah kamar harus mengisi from pengajuan pindah kamar pertama penghuni akan tentukan kamar mana yng ingin dia pindah terus tanggal berpa akan dia pindah setelah itu penghuni harus isi keteranagan mengapa ingin pindah kamar, setelah itu penghuni wajib menjawab pernyataan sesuai dengan yang ada di ketentuan membuat penghuni bahwa bnr" mau pindah kamar. setelah udh isi semua pertanyaan penghuni akan ajukan pindah kamarnya. dan akan muncul riwayat pindah kamar dengan status menungu dan di sisi admin akan menerima penajuaan nya dari notifikasi dan seblm admin setujuin kan melakukan nama nya inpeksi seperti turun ke kamar memeriksa keadaan kamar apakah ada kerusakan atau tidak jika mengalami kerusakan maka penghuni akan menganti rugi dan admin akan isi di bagian total kerusakan dan nanti akan di potong dalam deposit setelah itu admin hrs melihat apakah kamar yang dia ajukan nya itu lebih murah atau lebih mahal kalau lebih mahal penghuni harus bayar uang tambahan dan jika lebih murah maka admin akan refund ke pada penghuni sesuai dan total kamar yang di huni dan  sistem akan kirim notifikasi setuju dan jika ada refund akan ada bukti pembayaran ke pda penghuni semua perhitungan / totalan akan di hitung oleh sistem dan tidak akan salah ada hitung baru admin klik setuju pindah kamar, 
setelah di setujui, penghuni wajib membayar uang tambahan dan mengembalikan kunci lama baru bisa mengambil kunci baru jika penghuni blm bayar deposit maka sistem otomatis tidak bisa menyerahkan kunci.

untuk bagian pengajuan perpanjang waktu kamar user wajib membayar semua tagihan, baru bisa mengajukan perpanjang jika tidak memlunasi semua nya maka sistem otomatis akan menolaknya.  seblm mengajukan perpanjang kamar  akan muncul sebuah pernyataan apaka penghuni benaran mau perpanjang kamar terus penghuni harus tekan ya otomatis sistem akan memperpanjang waktu kamarnya nya dan di pembayaran akan muncul tagihan baru.

untuk bagian check out 
penghuni yng ingin melakukan check out akan mengisi tanggal yng akan di check out  dan akan mengisi keterangan/alasan penghuni ingin check out lalu di sana ada berbagai ketentuan dan pernyaataan yng hrus di baca dan di jawab penghuni supaya tidak terjdi salah paham di karenakan penghuni yang check out di luar perjanjian habis kontrak akan di potong depositnya 50 % dan semua jawabn harus di isi iya baru penghuni bisa mengajukan check out dan setelh itu akan muncul riwayat check out penghuni dngan status menunggu, dan di bgain admin akan mendapatakn notifikasi penghuni yang mau check out sma seperti pindah kamar admin wajib melakuakn inpeksi apakah ada terjdi kerusakan atau tidak, jika ada admin akan memotong biaya kerusakan  di depositnya dngan mengisi di from total kerusakan dan admin akan mengecek juga berapa sisa uang yng hrus di kembalikan kepada penghuni semua juga udh di hitung oleh sistem jadi tidak akan ada terjdi kesalahan dan sebelm penghuni malakukan check out juga memberika info pemotongan hrga yng akan di kenak kan kepada penghuni lalu admin refund sisa biaya deposit maupun sisa tagihan sewa bulan depan yng udh di bayar penghuni lalu admin klijk setuju nanti di bagian user akan mendapat notifikasi bahwa check out sudah di setujui admin dan juga mendapt kan bukti pembayran refund dari admin.

teruntuk user / calon penghuni/ pendaftar yng mau sewa kos  di wajibkan mengisi nomor rekening maupun nama lengkap dari rekening  jika tidak mengisi di bagian dasboard akan muncul notifikasi tersebut terus, supaya mempermudah admin bisa mentansfer pembayaran refund kepda penghuni. 

bagian sewa kamar 
calon penghuni yang ingin mengajukan sewa, wajib menentukan tanggal dan kamar yang ingin dia sewa, mengisi keterangan sperti mmemberitahu kepada admin bahwa dia mau sewa kos dan wajib juga mengisi durasi sewanya. terus calon penghuni wajib membaca dan mengisi semua ketentuan dan pernyataan nya udh di cantumkan setalah itu di pembayaran akan muncul tagihan deposit yang wajib di bayar seblm 3 hari lewat dari itu akan di tolak admin 
dan di bgian admin gk bisa langsung di setujukan di karenakan calon penghuni wajib membayar deposit baru admin bisa setuju dan dari status status pendaftar menjdi penghuni 


## Struktur Database

12 tabel: user, kamar, sewa, pembayaran, pengajuan_pindah, pengajuan_checkout, notifikasi, keluhan, pengumuman, peraturan, pengaturan, password_reset.

## Cron Job

```bash
php spark rumahkos:daily
```

Hitung denda, kirim notif tagihan jatuh tempo, notif kontrak hampir habis.
