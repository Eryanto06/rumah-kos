-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 07 Jul 2026 pada 08.46
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_rumah_kos`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `kamar`
--

CREATE TABLE `kamar` (
  `id_kamar` int(11) NOT NULL,
  `kode_kamar` varchar(20) NOT NULL,
  `nomor_kamar` varchar(10) NOT NULL,
  `harga_sewa` decimal(10,0) NOT NULL,
  `fasilitas` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT 'kamar.jpg',
  `status` enum('tersedia','terisi','perbaikan','dibooking') DEFAULT 'tersedia',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kamar`
--

INSERT INTO `kamar` (`id_kamar`, `kode_kamar`, `nomor_kamar`, `harga_sewa`, `fasilitas`, `foto`, `status`, `created_at`) VALUES
(1, 'KOS-001', '101', 500000, 'Kasur, Lemari, Meja Belajar, Kipas Angin', '1782179910_22e570e27eef23c65d30.jpg', 'terisi', '2026-06-09 13:02:14'),
(2, 'KOS-002', '102', 500000, 'Kasur, Lemari, Meja Belajar, Kipas Angin', '1782179962_89f1910bda81c13bf7d8.jpg', 'terisi', '2026-06-09 13:02:14'),
(3, 'KOS-003', '103', 700000, 'Kasur, Lemari, Meja Belajar, AC, Kamar Mandi Dalam', '1782180090_84bdc2b5ef9e4d9cf208.jpg', 'tersedia', '2026-06-09 13:02:14'),
(4, 'KOS-004', '104', 700000, 'Kasur, Lemari, Meja Belajar, AC, Kamar Mandi Dalam', '1782180168_871ad0a26e6b61122649.jpg', 'terisi', '2026-06-09 13:02:14'),
(5, 'KOS-005', '201', 600000, 'Kasur, Lemari, Meja Belajar, Kipas Angin, Kamar Mandi Dalam', '1782180327_3fdea09647aef30de310.jpg', 'terisi', '2026-06-09 13:02:14'),
(6, 'KOS-006', '202', 600000, 'Kasur, Lemari, Meja Belajar, Kipas Angin, Kamar Mandi Dalam', '1782180391_7803a58854d6894a8f82.jpg', 'terisi', '2026-06-09 13:02:14'),
(7, 'KOS-007', '203', 900000, 'Kasur, Lemari, Meja Belajar, AC, Kamar Mandi Dalam, WiFi', '1783075654_c7a4a856679a418632bd.jpg', 'terisi', '2026-06-09 13:02:14'),
(8, 'KOS-008', '204', 900000, 'Kasur, Lemari, Meja Belajar, AC, Kamar Mandi Dalam, WiFi', '1782180433_c11469dc8940ddcf98a9.jpg', 'terisi', '2026-06-09 13:02:14'),
(9, 'KOS-009', '205', 2000000, 'Lemari Baju, Meja Belajar, Kursi, AC, Kamar Mandi Dalam, Stop Kontak, Gantungan Baju, Rak Sepatu', '1782533974_13064b02608035430be7.webp', 'tersedia', '2026-06-27 11:11:36'),
(10, 'KOS-010', '105', 500000, 'Kasur, Lemari (Kamar Testing)', '1783075708_dedbe2bc1eb90ed4528f.jpg', 'terisi', '2026-06-28 18:37:34'),
(11, 'KOS-011', '206', 750000, 'Kasur Single, Lemari Baju, Meja Belajar, Kursi, AC, Kamar Mandi Dalam, Stop Kontak', '1782699429_43ddd37a45c431e88d6d.jpg', 'tersedia', '2026-06-29 09:17:09'),
(12, 'KOS-012', '207', 800000, 'Kasur Single, Lemari Baju, Meja Belajar, Kursi, Kipas Angin, Kamar Mandi Dalam, Wi-Fi, Stop Kontak', '1782699681_56e34c8d23769e5e113c.jpg', 'terisi', '2026-06-29 09:21:21'),
(13, 'KOS-013', '208', 800000, 'Kasur Single, Lemari Baju, Meja Belajar, Kursi, Kipas Angin, Kamar Mandi Dalam, Wi-Fi, Stop Kontak', '1782699925_fec01a5ef599737b16d3.jpg', 'tersedia', '2026-06-29 09:22:30'),
(14, 'KOS-014', '106', 2500000, 'Kasur Queen, Lemari Baju, Meja Belajar, Kursi, AC, Kipas Angin, Jendela, Kamar Mandi Dalam, Water Heater, TV, Stop Kontak, Gantungan Baju, Rak Sepatu', '1782699876_aaee4aab9cf81310f184.jpg', 'tersedia', '2026-06-29 09:24:36'),
(15, 'KOS-015', '209', 2500000, 'Kasur Single, Lemari Baju, Kursi, AC, Kipas Angin, Kamar Mandi Dalam, Water Heater, Wi-Fi, TV, Stop Kontak, Cermin, Rak Sepatu', '1783075809_4b4230c53d70cbf1ebe5.jpg', 'tersedia', '2026-07-03 17:50:09'),
(17, 'KOS-016', '210', 1500000, 'Kasur Single, Lemari Baju, AC, Kamar Mandi Dalam, Water Heater, Wi-Fi, TV, Stop Kontak', '1783235236_a836c80fdadc6a883247.jpg', 'terisi', '2026-07-05 14:07:16');

-- --------------------------------------------------------

--
-- Struktur dari tabel `keluhan`
--

CREATE TABLE `keluhan` (
  `id_keluhan` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `judul` varchar(200) NOT NULL,
  `deskripsi` text NOT NULL,
  `kategori` enum('fasilitas_kamar','listrik_air','wifi','kebersihan','parkir','kebisingan','tetangga','keamanan','lainnya','kendala_akun','website_bug','status_sewa','info_kamar','tagihan_sewa') DEFAULT 'lainnya',
  `id_pelapor` int(11) DEFAULT NULL,
  `is_private` tinyint(1) DEFAULT 0,
  `prioritas` enum('rendah','normal','tinggi','urgent') DEFAULT 'normal',
  `foto` varchar(255) DEFAULT NULL,
  `tanggal` date NOT NULL,
  `status` enum('menunggu','diproses','selesai') DEFAULT 'menunggu',
  `balasan` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `keluhan`
--

INSERT INTO `keluhan` (`id_keluhan`, `id_user`, `judul`, `deskripsi`, `kategori`, `id_pelapor`, `is_private`, `prioritas`, `foto`, `tanggal`, `status`, `balasan`, `created_at`) VALUES
(1, 5, ' ac bocor', 'ac bocor banyk air dan ac gk dingin', 'lainnya', NULL, 0, 'normal', NULL, '2026-06-22', 'selesai', 'ok akan kami sampaikan ke teknniksi', '2026-06-22 20:58:22'),
(2, 5, 'jaringan jalan', 'kok jaringan nya gk jaln nya lagi mau gunakan loh', 'lainnya', 5, 0, 'normal', NULL, '2026-06-25', 'selesai', 'Sudah ditangani teknisi. Silakan cek kembali. Terima kasih.', '2026-06-25 17:31:56'),
(3, 5, 'kebisingan', 'malam malam buka lagu bising kali gk bisa tidur', 'kebisingan', 5, 0, 'urgent', NULL, '2026-06-25', 'diproses', 'Sudah ditangani teknisi. Silakan cek kembali. Terima kasih.', '2026-06-25 17:32:53'),
(4, 4, 'wifi gk jalan', 'jaringan kok gk jalan mohon di periksa ya\r\n', 'wifi', 4, 0, 'normal', NULL, '2026-06-25', 'selesai', 'Sudah ditangani teknisi. Silakan cek kembali. Terima kasih.', '2026-06-25 18:04:55'),
(5, 4, 'wifi gk jalan', 'wifi nya kok gk jalan segra diatasi yaa soalnya bentarlagi kuliah', 'wifi', 4, 0, 'normal', NULL, '2026-06-25', 'diproses', 'Sudah ditangani teknisi. Silakan cek kembali. Terima kasih.', '2026-06-25 20:06:44'),
(6, 5, 'keisingan ', 'kamar seblah 108 bising kaliii', 'kebisingan', 5, 0, 'urgent', NULL, '2026-06-25', 'selesai', 'Sudah ditangani teknisi. Silakan cek kembali. Terima kasih.', '2026-06-25 20:33:17'),
(7, 5, 'wifi gk jalan', 'knp hari ini wifi lemot banget\r\n', 'wifi', 5, 0, 'normal', NULL, '2026-06-25', 'menunggu', NULL, '2026-06-25 20:35:04'),
(8, 4, 'wifi ', 'wifi kok gk jalan segera di atas ya tolong ', 'wifi', 4, 0, 'urgent', NULL, '2026-06-25', 'menunggu', 'Sudah ditangani teknisi. Silakan cek kembali. Terima kasih.', '2026-06-25 20:37:45'),
(9, 5, 'berantem', 'tolong datang ke kos ada terjadi kecekcokan antar kamar mohon segra datang ya', 'tetangga', NULL, 1, 'urgent', NULL, '2026-06-25', 'selesai', 'Sudah ditangani teknisi. Silakan cek kembali. Terima kasih.', '2026-06-25 20:39:28'),
(10, 12, 'status pengajuan ', 'tolong udh lewat berapa detik msa blm di ada kamr nya\r\n', '', 12, 0, 'urgent', NULL, '2026-06-29', 'menunggu', 'Sedang dalam proses penanganan. Mohon ditunggu 1x24 jam.', '2026-06-29 13:33:27'),
(11, 4, 'Pengumuman Gangguan Wi-Fi', 'tolong di oerbaikan ', 'wifi', 4, 0, 'urgent', NULL, '2026-06-30', 'menunggu', NULL, '2026-06-30 17:55:39'),
(12, 12, 'Pengumuman Gangguan Wi-Fi', 'segera di atasi', 'wifi', 12, 0, 'normal', NULL, '2026-06-30', 'menunggu', NULL, '2026-06-30 18:02:11'),
(13, 12, 'Pengumuman Gangguan Wi-Fi', 'hskd', 'wifi', 12, 0, 'normal', NULL, '2026-06-30', 'menunggu', 'Sudah ditangani teknisi. Silakan cek kembali. Terima kasih.', '2026-06-30 18:08:34'),
(14, 12, 'Pengumuman Gangguan Wi-Fi', 'segera din pebaikan', 'wifi', 12, 0, '', NULL, '2026-07-01', 'menunggu', NULL, '2026-07-01 22:19:45'),
(15, 12, 'Pengumuman Gangguan Wi-Fi', 'tolong segra atasi\r\n', 'wifi', 12, 0, '', NULL, '2026-07-01', 'selesai', 'Sudah ditangani teknisi. Silakan cek kembali. Terima kasih.', '2026-07-01 22:25:46'),
(17, 12, 'Pengumuman Gangguan Wi-Fi', 'tolong segera di perbaikan', 'wifi', 12, 0, 'tinggi', NULL, '2026-07-03', 'selesai', 'Sudah ditangani teknisi. Silakan cek kembali. Terima kasih.', '2026-07-03 17:52:21'),
(18, 31, 'di kamar sebelag sangat bising', 'ssnasjdsadn', 'kebersihan', 31, 0, 'tinggi', NULL, '2026-07-06', 'menunggu', NULL, '2026-07-06 15:50:11');

-- --------------------------------------------------------

--
-- Struktur dari tabel `notifikasi`
--

CREATE TABLE `notifikasi` (
  `id_notifikasi` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `pesan` text NOT NULL,
  `tipe` varchar(50) NOT NULL,
  `dibaca` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `notifikasi`
--

INSERT INTO `notifikasi` (`id_notifikasi`, `id_user`, `judul`, `pesan`, `tipe`, `dibaca`, `created_at`) VALUES
(1, 4, 'Pengumuman Baru: Mati air', 'mati air dikarenakan pipa bocor...', 'pengumuman', 1, '2026-06-26 00:29:48'),
(2, 5, 'Pengumuman Baru: Mati air', 'mati air dikarenakan pipa bocor...', 'pengumuman', 1, '2026-06-26 00:29:48'),
(4, 1, 'Keluhan Baru', 'Penghuni baru mengirim keluhan: jaringan jalan', 'keluhan', 1, '2026-06-26 00:31:56'),
(5, 1, 'Keluhan Baru', 'Penghuni baru mengirim keluhan: kebisingan', 'keluhan', 1, '2026-06-26 00:32:53'),
(6, 1, 'Keluhan Baru', 'Penghuni baru mengirim keluhan: wifi gk jalan', 'keluhan', 1, '2026-06-26 01:04:55'),
(7, 5, 'Keluhan Diperbarui:  ac bocor', 'Status keluhan Anda: Selesai. Balasan admin: ok akan kami sampaikan ke teknniksi', 'keluhan', 1, '2026-06-26 01:10:23'),
(8, 1, 'Keluhan Baru', 'Penghuni baru mengirim keluhan: wifi gk jalan', 'keluhan', 1, '2026-06-26 03:06:44'),
(9, 1, 'Keluhan Baru', 'Penghuni baru mengirim keluhan: keisingan ', 'keluhan', 1, '2026-06-26 03:33:17'),
(10, 1, 'Keluhan Baru', 'Penghuni baru mengirim keluhan: wifi gk jalan', 'keluhan', 1, '2026-06-26 03:35:04'),
(11, 1, 'Keluhan Baru', 'Penghuni baru mengirim keluhan: wifi ', 'keluhan', 1, '2026-06-26 03:37:45'),
(12, 1, 'Keluhan Baru', 'Ada keluhan MASALAH PENGGHUNI LAIN yang membutuhkan tindakan Anda. Identitas pelapor dirahasiakan.', 'keluhan', 1, '2026-06-26 03:39:28'),
(13, 5, 'Keluhan Diperbarui: jaringan jalan', 'Status keluhan Anda: Menunggu. Balasan admin: Sudah ditangani teknisi. Silakan cek kembali. Terima kasih.', 'keluhan', 1, '2026-06-27 06:47:02'),
(14, 5, 'Keluhan Diperbarui: jaringan jalan', 'Status keluhan Anda: Menunggu. Balasan admin: Sudah ditangani teknisi. Silakan cek kembali. Terima kasih.', 'keluhan', 1, '2026-06-27 06:47:14'),
(15, 5, 'Keluhan Diperbarui: jaringan jalan', 'Status keluhan Anda: Selesai. Balasan admin: Sudah ditangani teknisi. Silakan cek kembali. Terima kasih.', 'keluhan', 1, '2026-06-27 06:48:38'),
(16, 5, 'Keluhan Diperbarui: jaringan jalan', 'Status keluhan Anda: Selesai. Balasan admin: Sudah ditangani teknisi. Silakan cek kembali. Terima kasih.', 'keluhan', 1, '2026-06-27 06:48:59'),
(21, 7, 'Tagihan Terlambat - Denda Rp 285.000', 'Tagihan sewa Bulan ke-1 sudah melewati jatuh tempo. Denda keterlambatan: Rp 285.000. Segera bayar sebelum denda bertambah!', 'tagihan', 0, '2026-06-28 02:34:02'),
(22, 8, 'Pengajuan Checkout Diterima', 'Pengajuan checkout Anda diterima. Admin akan proses & menjadwalkan inspeksi kamar. Deposit akan dikembalikan setelah kamar dinyatakan bersih & tidak rusak.', 'checkout', 1, '2026-06-28 02:52:58'),
(23, 1, 'Pengajuan Checkout Baru', 'User Checkout mengajukan checkout. Segera proses di menu Checkout untuk inspeksi kamar & pengembalian deposit.', 'checkout', 1, '2026-06-28 02:52:58'),
(24, 8, 'Check-Out Dalam Proses Inspeksi', 'Pengajuan check-out Anda sedang diproses. Tim admin akan mengunjungi kamar Anda untuk inspeksi kondisi kamar. Mohon kamar dalam keadaan bersih.', 'checkout', 1, '2026-06-28 03:09:44'),
(25, 4, 'Pengumuman: Pengumuman Pemadaman Air', 'Diberitahukan kepada seluruh penghuni kos bahwa akan ada pemadaman air sementara.\r\n\r\nWaktu: Hari ini pukul 18:00 - 20:30 WIB\r\nSebab: Perbaikan pompa a', 'pengumuman', 1, '2026-06-28 03:59:10'),
(26, 5, 'Pengumuman: Pengumuman Pemadaman Air', 'Diberitahukan kepada seluruh penghuni kos bahwa akan ada pemadaman air sementara.\r\n\r\nWaktu: Hari ini pukul 18:00 - 20:30 WIB\r\nSebab: Perbaikan pompa a', 'pengumuman', 1, '2026-06-28 03:59:10'),
(27, 7, 'Pengumuman: Pengumuman Pemadaman Air', 'Diberitahukan kepada seluruh penghuni kos bahwa akan ada pemadaman air sementara.\r\n\r\nWaktu: Hari ini pukul 18:00 - 20:30 WIB\r\nSebab: Perbaikan pompa a', 'pengumuman', 0, '2026-06-28 03:59:10'),
(28, 8, 'Pengumuman: Pengumuman Pemadaman Air', 'Diberitahukan kepada seluruh penghuni kos bahwa akan ada pemadaman air sementara.\r\n\r\nWaktu: Hari ini pukul 18:00 - 20:30 WIB\r\nSebab: Perbaikan pompa a', 'pengumuman', 1, '2026-06-28 03:59:10'),
(29, 7, 'Tagihan Terlambat - Denda Rp 570.000', 'Tagihan Anda telat. Denda: Rp 570.000. Segera bayar!', 'tagihan', 0, '2026-06-28 06:00:02'),
(30, 7, 'Tagihan Terlambat - Denda Rp 260.000', 'Tagihan Anda telat. Denda: Rp 260.000. Segera bayar!', 'tagihan', 0, '2026-06-28 06:00:02'),
(31, 7, 'Tagihan Terlambat - Denda Rp 580.000', 'Tagihan sewa Bulan ke-1 sudah melewati jatuh tempo. Denda keterlambatan: Rp 580.000. Segera bayar sebelum denda bertambah!', 'tagihan', 0, '2026-06-28 16:16:11'),
(32, 7, 'Tagihan Terlambat - Denda Rp 580.000', 'Tagihan sewa kamar No. 101 (Bulan ke-1) sudah melewati jatuh tempo. Denda: Rp 580.000. Segera bayar!', 'tagihan', 0, '2026-06-28 16:17:48'),
(33, 7, 'Tagihan Terlambat - Denda Rp 270.000', 'Tagihan sewa kamar No. 101 (Bulan ke-2) sudah melewati jatuh tempo. Denda: Rp 270.000. Segera bayar!', 'tagihan', 0, '2026-06-28 16:17:48'),
(34, 4, 'Pengumuman: Pengumuman Gangguan Wi-Fi', 'Diberitahukan kepada seluruh penghuni kos bahwa koneksi Wi-Fi sedang mengalami gangguan.\r\n\r\nTim teknisi sedang berusaha memperbaikinya. Estimasi norma', 'pengumuman', 1, '2026-06-28 17:21:41'),
(35, 5, 'Pengumuman: Pengumuman Gangguan Wi-Fi', 'Diberitahukan kepada seluruh penghuni kos bahwa koneksi Wi-Fi sedang mengalami gangguan.\r\n\r\nTim teknisi sedang berusaha memperbaikinya. Estimasi norma', 'pengumuman', 1, '2026-06-28 17:21:41'),
(36, 7, 'Pengumuman: Pengumuman Gangguan Wi-Fi', 'Diberitahukan kepada seluruh penghuni kos bahwa koneksi Wi-Fi sedang mengalami gangguan.\r\n\r\nTim teknisi sedang berusaha memperbaikinya. Estimasi norma', 'pengumuman', 0, '2026-06-28 17:21:41'),
(37, 8, 'Pengumuman: Pengumuman Gangguan Wi-Fi', 'Diberitahukan kepada seluruh penghuni kos bahwa koneksi Wi-Fi sedang mengalami gangguan.\r\n\r\nTim teknisi sedang berusaha memperbaikinya. Estimasi norma', 'pengumuman', 1, '2026-06-28 17:21:41'),
(38, 4, 'Pengumuman: Pengumuman', 'Diberitahukan kepada seluruh penghuni kos:\r\n\r\nuntuk penghuni noaktif cukup membuat account nya sekali aja ya\r\n\r\nTerima kasih atas perhatiannya.', 'pengumuman', 1, '2026-06-28 17:24:03'),
(39, 5, 'Pengumuman: Pengumuman', 'Diberitahukan kepada seluruh penghuni kos:\r\n\r\nuntuk penghuni noaktif cukup membuat account nya sekali aja ya\r\n\r\nTerima kasih atas perhatiannya.', 'pengumuman', 1, '2026-06-28 17:24:03'),
(40, 7, 'Pengumuman: Pengumuman', 'Diberitahukan kepada seluruh penghuni kos:\r\n\r\nuntuk penghuni noaktif cukup membuat account nya sekali aja ya\r\n\r\nTerima kasih atas perhatiannya.', 'pengumuman', 0, '2026-06-28 17:24:03'),
(41, 8, 'Pengumuman: Pengumuman', 'Diberitahukan kepada seluruh penghuni kos:\r\n\r\nuntuk penghuni noaktif cukup membuat account nya sekali aja ya\r\n\r\nTerima kasih atas perhatiannya.', 'pengumuman', 1, '2026-06-28 17:24:03'),
(42, 4, 'Pengumuman: Pengumuman Pemadaman Listrik', 'Diberitahukan kepada seluruh penghuni kos bahwa akan ada pemadaman listrik sementara.\r\n\r\nMohon siapkan senter/flashlight dan charge HP/powerbank sebel', 'pengumuman', 1, '2026-06-28 17:46:03'),
(43, 5, 'Pengumuman: Pengumuman Pemadaman Listrik', 'Diberitahukan kepada seluruh penghuni kos bahwa akan ada pemadaman listrik sementara.\r\n\r\nMohon siapkan senter/flashlight dan charge HP/powerbank sebel', 'pengumuman', 1, '2026-06-28 17:46:03'),
(44, 7, 'Pengumuman: Pengumuman Pemadaman Listrik', 'Diberitahukan kepada seluruh penghuni kos bahwa akan ada pemadaman listrik sementara.\r\n\r\nMohon siapkan senter/flashlight dan charge HP/powerbank sebel', 'pengumuman', 0, '2026-06-28 17:46:03'),
(45, 8, 'Pengumuman: Pengumuman Pemadaman Listrik', 'Diberitahukan kepada seluruh penghuni kos bahwa akan ada pemadaman listrik sementara.\r\n\r\nMohon siapkan senter/flashlight dan charge HP/powerbank sebel', 'pengumuman', 1, '2026-06-28 17:46:03'),
(46, 4, 'Pengumuman: Pengumuman Gangguan Wi-Fi', 'Diberitahukan kepada seluruh penghuni kos bahwa koneksi Wi-Fi sedang mengalami gangguan.\r\n\r\nTim teknisi sedang berusaha memperbaikinya. Estimasi norma', 'pengumuman', 1, '2026-06-28 18:04:04'),
(47, 5, 'Pengumuman: Pengumuman Gangguan Wi-Fi', 'Diberitahukan kepada seluruh penghuni kos bahwa koneksi Wi-Fi sedang mengalami gangguan.\r\n\r\nTim teknisi sedang berusaha memperbaikinya. Estimasi norma', 'pengumuman', 1, '2026-06-28 18:04:04'),
(48, 7, 'Pengumuman: Pengumuman Gangguan Wi-Fi', 'Diberitahukan kepada seluruh penghuni kos bahwa koneksi Wi-Fi sedang mengalami gangguan.\r\n\r\nTim teknisi sedang berusaha memperbaikinya. Estimasi norma', 'pengumuman', 0, '2026-06-28 18:04:04'),
(49, 8, 'Pengumuman: Pengumuman Gangguan Wi-Fi', 'Diberitahukan kepada seluruh penghuni kos bahwa koneksi Wi-Fi sedang mengalami gangguan.\r\n\r\nTim teknisi sedang berusaha memperbaikinya. Estimasi norma', 'pengumuman', 1, '2026-06-28 18:04:04'),
(50, 4, 'Pengumuman: Pengumuman Gangguan Wi-Fi', 'Diberitahukan kepada seluruh penghuni kos bahwa koneksi Wi-Fi sedang mengalami gangguan.\r\n\r\nTim teknisi sedang berusaha memperbaikinya. Estimasi norma', 'pengumuman', 0, '2026-06-28 18:24:58'),
(51, 5, 'Pengumuman: Pengumuman Gangguan Wi-Fi', 'Diberitahukan kepada seluruh penghuni kos bahwa koneksi Wi-Fi sedang mengalami gangguan.\r\n\r\nTim teknisi sedang berusaha memperbaikinya. Estimasi norma', 'pengumuman', 1, '2026-06-28 18:24:58'),
(52, 7, 'Pengumuman: Pengumuman Gangguan Wi-Fi', 'Diberitahukan kepada seluruh penghuni kos bahwa koneksi Wi-Fi sedang mengalami gangguan.\r\n\r\nTim teknisi sedang berusaha memperbaikinya. Estimasi norma', 'pengumuman', 0, '2026-06-28 18:24:58'),
(53, 8, 'Pengumuman: Pengumuman Gangguan Wi-Fi', 'Diberitahukan kepada seluruh penghuni kos bahwa koneksi Wi-Fi sedang mengalami gangguan.\r\n\r\nTim teknisi sedang berusaha memperbaikinya. Estimasi norma', 'pengumuman', 1, '2026-06-28 18:24:58'),
(55, 1, 'Pengajuan Pindah Kamar Baru', 'yandi mengajukan pindah kamar. Segera review di menu Pindah Kamar.', 'pindah', 1, '2026-06-28 18:46:45'),
(56, 5, 'Perpanjangan Kontrak Berhasil', 'Kontrak sewa kamar Anda (No. 104) berhasil diperpanjang selama 4 bulan. Tanggal selesai baru: 28 Jan 2027.', 'kontrak', 1, '2026-06-28 18:54:21'),
(57, 1, 'Perpanjangan Kontrak', 'yandi memperpanjang kontrak kamar No. 104 selama 4 bulan.', 'kontrak', 1, '2026-06-28 18:54:21'),
(58, 5, 'Perpanjangan Kontrak Berhasil', 'Kontrak sewa kamar Anda (No. 104) berhasil diperpanjang selama 1 bulan. Tanggal selesai baru: 28 Feb 2027.', 'kontrak', 1, '2026-06-28 18:54:24'),
(59, 1, 'Perpanjangan Kontrak', 'yandi memperpanjang kontrak kamar No. 104 selama 1 bulan.', 'kontrak', 1, '2026-06-28 18:54:24'),
(60, 5, 'Pengajuan Pindah Kamar Diterima', 'Pengajuan pindah kamar Anda diterima. Admin akan cek ketersediaan kamar tujuan & menyetujui dalam 1x24 jam. Mohon tunggu konfirmasi.', 'pindah', 1, '2026-06-28 19:01:50'),
(61, 1, 'Pengajuan Pindah Kamar Baru', 'yandi mengajukan pindah kamar. Segera review di menu Pindah Kamar.', 'pindah', 1, '2026-06-28 19:01:50'),
(62, 5, 'Pengajuan Pindah Kamar Diterima', 'Pengajuan pindah kamar Anda diterima. Admin akan cek ketersediaan kamar tujuan & menyetujui dalam 1x24 jam. Mohon tunggu konfirmasi.', 'pindah', 1, '2026-06-28 19:16:28'),
(63, 1, 'Pengajuan Pindah Kamar Baru', 'yandi mengajukan pindah kamar. Segera review di menu Pindah Kamar.', 'pindah', 1, '2026-06-28 19:16:28'),
(64, 5, 'Pengajuan Pindah Kamar Disetujui!', 'Selamat! Pengajuan pindah kamar Anda telah disetujui. Anda sekarang resmi menempati Kamar No. 201. Sisa durasi sewa Anda adalah 8 bulan. Kunci kamar lama harap dikembalikan ke admin.', 'info', 1, '2026-06-28 19:17:27'),
(65, 5, 'Perpanjangan Kontrak Berhasil', 'Kontrak sewa kamar Anda (No. 201) berhasil diperpanjang selama 2 bulan. Tanggal selesai baru: 28 Apr 2027.', 'kontrak', 1, '2026-06-28 19:18:08'),
(66, 1, 'Perpanjangan Kontrak', 'yandi memperpanjang kontrak kamar No. 201 selama 2 bulan.', 'kontrak', 1, '2026-06-28 19:18:08'),
(67, 5, 'Pengajuan Pindah Kamar Diterima', 'Pengajuan pindah kamar Anda diterima. Admin akan cek ketersediaan kamar tujuan & menyetujui dalam 1x24 jam. Mohon tunggu konfirmasi.', 'pindah', 1, '2026-06-28 19:29:50'),
(68, 1, 'Pengajuan Pindah Kamar Baru', 'yandi mengajukan pindah kamar. Segera review di menu Pindah Kamar.', 'pindah', 1, '2026-06-28 19:29:50'),
(69, 5, 'Pengajuan Pindah Kamar Disetujui!', 'Selamat! Pengajuan pindah kamar Anda telah disetujui. Anda sekarang resmi menempati Kamar No. 103. Sisa durasi sewa Anda adalah 10 bulan. Kunci kamar lama harap dikembalikan ke admin.', 'info', 1, '2026-06-28 19:30:48'),
(70, 5, 'Keluhan Diperbarui: keisingan ', 'Status keluhan Anda: Selesai. Balasan admin: Sudah ditangani teknisi. Silakan cek kembali. Terima kasih.', 'keluhan', 1, '2026-06-28 19:54:20'),
(71, 5, 'Keluhan Diperbarui: kebisingan', 'Status keluhan Anda: Menunggu. Balasan admin: Sedang dalam proses penanganan. Mohon ditunggu 1x24 jam.', 'keluhan', 1, '2026-06-28 19:54:31'),
(72, 5, 'Keluhan Diperbarui: berantem', 'Status keluhan Anda: Menunggu. Balasan admin: Sudah saya tegur pihak terkait. Jika berulang, hubungi saya lagi. Terima kasih.', 'keluhan', 1, '2026-06-28 19:54:53'),
(73, 4, 'Keluhan Diperbarui: wifi ', 'Status keluhan Anda: Menunggu. Balasan admin: Sudah ditangani teknisi. Silakan cek kembali. Terima kasih.', 'keluhan', 0, '2026-06-28 19:55:07'),
(74, 5, 'Keluhan Diperbarui: berantem', 'Status keluhan Anda: Menunggu. Balasan admin: Sudah ditangani teknisi. Silakan cek kembali. Terima kasih.', 'keluhan', 1, '2026-06-28 19:55:14'),
(75, 5, 'Keluhan Diperbarui: berantem', 'Status keluhan Anda: Menunggu. Balasan admin: Sudah ditangani teknisi. Silakan cek kembali. Terima kasih.', 'keluhan', 1, '2026-06-28 19:55:19'),
(76, 5, 'Keluhan Diperbarui: berantem', 'Status keluhan Anda: Menunggu. Balasan admin: Sudah ditangani teknisi. Silakan cek kembali. Terima kasih.', 'keluhan', 1, '2026-06-28 19:55:29'),
(77, 5, 'Keluhan Diperbarui: kebisingan', 'Status keluhan Anda: Diproses. Balasan admin: Sudah ditangani teknisi. Silakan cek kembali. Terima kasih.', 'keluhan', 1, '2026-06-28 19:55:44'),
(78, 4, 'Keluhan Diperbarui: wifi gk jalan', 'Status keluhan Anda: Selesai. Balasan admin: Sudah ditangani teknisi. Silakan cek kembali. Terima kasih.', 'keluhan', 0, '2026-06-28 19:56:02'),
(79, 5, 'Keluhan Diperbarui: berantem', 'Status keluhan Anda: Selesai. Balasan admin: Sudah ditangani teknisi. Silakan cek kembali. Terima kasih.', 'keluhan', 1, '2026-06-28 19:56:14'),
(80, 4, 'Keluhan Diperbarui: wifi gk jalan', 'Status keluhan Anda: Diproses. Balasan admin: Sudah ditangani teknisi. Silakan cek kembali. Terima kasih.', 'keluhan', 0, '2026-06-28 19:56:25'),
(81, 5, 'Pengajuan Pindah Kamar Diterima', 'Pengajuan pindah kamar Anda diterima. Admin akan cek ketersediaan kamar tujuan & menyetujui dalam 1x24 jam. Mohon tunggu konfirmasi.', 'pindah', 1, '2026-06-28 20:09:00'),
(82, 1, 'Pengajuan Pindah Kamar Baru', 'yandi mengajukan pindah kamar. Segera review di menu Pindah Kamar.', 'pindah', 1, '2026-06-28 20:09:00'),
(83, 5, 'Pengajuan Pindah Kamar Disetujui!', 'Selamat! Pengajuan pindah kamar Anda telah disetujui. Anda sekarang resmi menempati Kamar No. 201. Sisa durasi sewa Anda adalah 10 bulan. Kunci kamar lama harap dikembalikan ke admin. Tagihan deposit kamar baru telah dibuat di menu Pembayaran.', 'info', 1, '2026-06-28 20:10:27'),
(84, 5, 'Pembayaran Diterima', 'Bukti pembayaran Anda telah diupload untuk 1 tagihan. Admin akan verifikasi dalam 1x24 jam. Status tagihan akan berubah jadi \"Lunas\" setelah diverifikasi.', 'pembayaran', 1, '2026-06-28 20:11:36'),
(85, 1, 'Pembayaran Baru Perlu Verifikasi', 'Penghuni yandi upload bukti pembayaran untuk 1 tagihan. Segera verifikasi di menu Pembayaran.', 'pembayaran', 1, '2026-06-28 20:11:36'),
(87, 1, 'Pendaftar Baru', 'Pengguna baru mendaftar: percobaaan (HP: 082311111111). Belum mengajukan sewa.', 'user_baru', 1, '2026-06-28 21:16:09'),
(88, 9, 'Pengajuan Sewa Diterima', 'Pengajuan sewa kamar Anda diterima. SEBELUM admin menyetujui, Anda WAJIB membayar Deposit sebesar Rp 1.800.000 di menu Pembayaran. Setelah deposit lunas, admin akan memproses persetujuan.', 'sewa', 1, '2026-06-28 21:17:52'),
(89, 1, 'Pengajuan Sewa Baru', 'Pengajuan sewa baru masuk dari percobaaan. Tunggu user membayar deposit sebelum disetujui.', 'sewa', 1, '2026-06-28 21:17:52'),
(90, 9, 'Pembayaran Diterima', 'Bukti pembayaran Anda telah diupload untuk 1 tagihan. Admin akan verifikasi dalam 1x24 jam. Status tagihan akan berubah jadi \"Lunas\" setelah diverifikasi.', 'pembayaran', 1, '2026-06-28 21:18:56'),
(91, 1, 'Pembayaran Baru Perlu Verifikasi', 'Penghuni percobaaan upload bukti pembayaran untuk 1 tagihan. Segera verifikasi di menu Pembayaran.', 'pembayaran', 1, '2026-06-28 21:18:56'),
(92, 9, 'Pengajuan Sewa Disetujui!', 'Selamat! Pengajuan sewa Anda disetujui karena deposit sudah lunas. Kunci kamar No. 204 siap diambil di Office Rumah Kos.', 'sewa', 1, '2026-06-28 21:29:19'),
(94, 9, 'Pengajuan Pindah Kamar Diterima', 'Pengajuan pindah kamar Anda diterima. Admin akan cek ketersediaan kamar tujuan & menyetujui dalam 1x24 jam. Mohon tunggu konfirmasi.', 'pindah', 1, '2026-06-28 21:57:36'),
(95, 1, 'Pengajuan Pindah Kamar Baru', 'percobaaan mengajukan pindah kamar. Segera review di menu Pindah Kamar.', 'pindah', 1, '2026-06-28 21:57:36'),
(96, 9, 'Pengajuan Pindah Kamar Ditolak', 'Maaf, pengajuan pindah kamar Anda ditolak oleh admin. Hubungi admin untuk informasi lebih lanjut.', 'info', 1, '2026-06-28 22:02:39'),
(97, 9, 'Kunci Telah Diambil', 'Kunci kamar Anda telah diterima. Selamat menempati kamar Anda. Jangan lupa menjaga kebersihan dan ketertiban kos.', 'info', 1, '2026-06-28 23:34:33'),
(98, 5, 'Kunci Telah Diambil', 'Kunci kamar Anda telah diterima. Selamat menempati kamar Anda. Jangan lupa menjaga kebersihan dan ketertiban kos.', 'info', 1, '2026-06-29 02:21:19'),
(99, 8, 'Pengajuan Pindah Kamar Diterima', 'Pengajuan pindah kamar Anda diterima. Admin akan cek ketersediaan kamar tujuan & menyetujui dalam 1x24 jam. Mohon tunggu konfirmasi.', 'pindah', 1, '2026-06-29 02:25:12'),
(100, 1, 'Pengajuan Pindah Kamar Baru', 'User Checkout mengajukan pindah kamar. Segera review di menu Pindah Kamar.', 'pindah', 1, '2026-06-29 02:25:12'),
(101, 7, 'Tagihan Terlambat - Denda Rp 580.000', 'Tagihan Anda telat. Denda: Rp 580.000. Segera bayar!', 'tagihan', 0, '2026-06-29 02:25:24'),
(102, 7, 'Tagihan Terlambat - Denda Rp 270.000', 'Tagihan Anda telat. Denda: Rp 270.000. Segera bayar!', 'tagihan', 0, '2026-06-29 02:25:24'),
(103, 10, 'Kontrak Sewa Sisa 7 Hari', 'Kontrak sewa kamar Anda (No. 999) akan berakhir dalam 7 hari lagi (2026-07-05). Segera lakukan perpanjangan.', 'kontrak', 0, '2026-06-29 02:25:24'),
(104, 8, 'Pengajuan Pindah Kamar Disetujui!', 'Selamat! Pengajuan pindah kamar Anda telah disetujui. Anda sekarang resmi menempati Kamar No. 104. Sisa durasi sewa Anda adalah 8 bulan. Kunci kamar lama harap dikembalikan ke admin. Tagihan deposit kamar baru telah dibuat di menu Pembayaran.', 'info', 1, '2026-06-29 02:26:18'),
(105, 1, 'Pendaftar Baru', 'Pengguna baru mendaftar: devi (HP: 081212121212). Belum mengajukan sewa.', 'user_baru', 1, '2026-06-29 03:16:51'),
(106, 5, 'Perpanjangan Kontrak Berhasil', 'Kontrak sewa kamar Anda (No. 201) berhasil diperpanjang selama 2 bulan. Tanggal selesai baru: 28 Jun 2027. Tagihan untuk 2 bulan tambahan sudah dibuat, cek menu Pembayaran.', 'kontrak', 1, '2026-06-29 03:33:38'),
(107, 1, 'Perpanjangan Kontrak', 'yandi memperpanjang kontrak kamar No. 201 selama 2 bulan. Tagihan baru otomatis dibuat.', 'kontrak', 1, '2026-06-29 03:33:38'),
(108, 5, 'Perpanjangan Kontrak Berhasil', 'Kontrak sewa kamar Anda (No. 201) berhasil diperpanjang selama 2 bulan. Tanggal selesai baru: 28 Aug 2027. Tagihan untuk 2 bulan tambahan sudah dibuat, cek menu Pembayaran.', 'kontrak', 1, '2026-06-29 03:44:14'),
(109, 1, 'Perpanjangan Kontrak', 'yandi memperpanjang kontrak kamar No. 201 selama 2 bulan. Tagihan baru otomatis dibuat.', 'kontrak', 1, '2026-06-29 03:44:14'),
(110, 5, 'Perpanjangan Kontrak Berhasil', 'Kontrak sewa kamar Anda (No. 201) berhasil diperpanjang selama 1 bulan. Tanggal selesai baru: 28 Sep 2027. Tagihan untuk 1 bulan tambahan sudah dibuat, cek menu Pembayaran.', 'kontrak', 1, '2026-06-29 03:44:36'),
(111, 1, 'Perpanjangan Kontrak', 'yandi memperpanjang kontrak kamar No. 201 selama 1 bulan. Tagihan baru otomatis dibuat.', 'kontrak', 1, '2026-06-29 03:44:36'),
(112, 5, 'Perpanjangan Kontrak Berhasil', 'Kontrak sewa kamar Anda (No. 201) berhasil diperpanjang selama 1 bulan. Tanggal selesai baru: 28 Oct 2027. Tagihan untuk 1 bulan tambahan sudah dibuat, cek menu Pembayaran.', 'kontrak', 1, '2026-06-29 03:46:17'),
(113, 1, 'Perpanjangan Kontrak', 'yandi memperpanjang kontrak kamar No. 201 selama 1 bulan. Tagihan baru otomatis dibuat.', 'kontrak', 1, '2026-06-29 03:46:17'),
(114, 7, 'Tagihan Terlambat - Denda Rp 590.000', 'Tagihan Anda telat. Denda: Rp 590.000. Segera bayar!', 'tagihan', 0, '2026-06-29 14:10:02'),
(115, 7, 'Tagihan Terlambat - Denda Rp 280.000', 'Tagihan Anda telat. Denda: Rp 280.000. Segera bayar!', 'tagihan', 0, '2026-06-29 14:10:02'),
(116, 1, 'Pendaftar Baru', 'Pengguna baru mendaftar: diki (HP: 0822211212121). Belum mengajukan sewa.', 'user_baru', 1, '2026-06-29 16:27:36'),
(117, 12, 'Pengajuan Sewa Diterima', 'Pengajuan sewa kamar No. 206 diterima. WAJIB bayar Deposit Rp 1.500.000 dalam 3 hari (jatuh tempo: 02 Jul 2026) di menu Pembayaran. Setelah deposit lunas, admin akan memproses persetujuan.', 'sewa', 1, '2026-06-29 16:28:51'),
(118, 1, 'Pengajuan Sewa Baru', 'Pengajuan sewa baru dari diki (Kamar No. 206). Tunggu user membayar deposit sebelum disetujui.', 'sewa', 1, '2026-06-29 16:28:51'),
(119, 12, 'Pembayaran Diterima', 'Bukti pembayaran Anda telah diupload untuk 1 tagihan. Admin akan verifikasi dalam 1x24 jam. Status tagihan akan berubah jadi \"Lunas\" setelah diverifikasi.', 'pembayaran', 1, '2026-06-29 19:19:13'),
(120, 1, 'Pembayaran Baru Perlu Verifikasi', 'Penghuni diki upload bukti pembayaran untuk 1 tagihan. Segera verifikasi di menu Pembayaran.', 'pembayaran', 1, '2026-06-29 19:19:13'),
(121, 12, 'Pembayaran Diverifikasi (Lunas)', 'Pembayaran Deposit sebesar Rp 1.500.000 telah DIVERIFIKASI dan berstatus LUNAS. Catatan admin: Pembayaran deposit terverifikasi. Nominal Rp 1.500.000 sesuai.', 'pembayaran', 1, '2026-06-29 19:41:32'),
(122, 12, 'Pembayaran Dalam Proses Verifikasi', 'Pembayaran Deposit sebesar Rp 1.500.000 sedang menunggu verifikasi admin.', 'pembayaran', 1, '2026-06-29 19:41:52'),
(123, 12, 'Pembayaran Dalam Proses Verifikasi', 'Pembayaran Deposit sebesar Rp 1.500.000 sedang menunggu verifikasi admin.', 'pembayaran', 1, '2026-06-29 19:43:55'),
(124, 12, 'Pembayaran Dalam Proses Verifikasi', 'Pembayaran Deposit sebesar Rp 1.500.000 sedang menunggu verifikasi admin.', 'pembayaran', 1, '2026-06-29 19:50:47'),
(125, 12, 'Pembayaran Dalam Proses Verifikasi', 'Pembayaran Deposit sebesar Rp 1.500.000 sedang menunggu verifikasi admin.', 'pembayaran', 1, '2026-06-29 20:23:28'),
(126, 12, 'Pembayaran Diverifikasi (Lunas)', 'Pembayaran Deposit sebesar Rp 1.500.000 telah DIVERIFIKASI dan berstatus LUNAS. Catatan admin: Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal Rp 1.500.000 sesuai dengan tagihan, bukti transfer juga valid. Terima kasih telah membayar tepat waktu. 🙏', 'pembayaran', 1, '2026-06-29 20:29:36'),
(127, 12, 'Pembayaran Diverifikasi (Lunas)', 'Pembayaran Deposit sebesar Rp 1.500.000 telah DIVERIFIKASI dan berstatus LUNAS. Catatan admin: Selamat! Pembayaran deposit Anda sebesar Rp 1.500.000 telah berhasil diverifikasi. Deposit Anda siap digunakan. Admin akan segera memproses pengajuan sewa Anda. Terima kasih telah mempercayai Rumah Kos kami. 🙏', 'pembayaran', 1, '2026-06-29 20:29:51'),
(128, 12, 'Keluhan Diterima: status pengajuan ', 'Pertanyaan status sewa Anda diterima. Admin akan cek pengajuan Anda & balas segera.', 'keluhan', 1, '2026-06-29 20:33:27'),
(129, 1, 'Keluhan Baru (PENDAFTAR)', '[PENDAFTAR] status pengajuan ', 'keluhan', 1, '2026-06-29 20:33:27'),
(130, 12, 'Keluhan Diperbarui: status pengajuan ', 'Status keluhan Anda: Menunggu. Balasan admin: Sedang dalam proses penanganan. Mohon ditunggu 1x24 jam.', 'keluhan', 1, '2026-06-29 20:34:19'),
(131, 12, 'Pengajuan Sewa Disetujui!', 'Selamat! Pengajuan sewa Anda disetujui karena deposit sudah lunas. Kunci kamar No. 206 siap diambil di Office Rumah Kos.', 'sewa', 1, '2026-06-29 20:56:58'),
(133, 12, 'Kunci Telah Diambil', 'Kunci kamar Anda telah diterima. Selamat menempati kamar Anda. Jangan lupa menjaga kebersihan dan ketertiban kos.', 'info', 1, '2026-06-29 20:58:45'),
(134, 12, 'Pembayaran Diterima', 'Bukti pembayaran Anda telah diupload untuk 7 tagihan. Admin akan verifikasi dalam 1x24 jam. Status tagihan akan berubah jadi \"Lunas\" setelah diverifikasi.', 'pembayaran', 1, '2026-06-29 21:02:42'),
(135, 1, 'Pembayaran Baru Perlu Verifikasi', 'Penghuni diki upload bukti pembayaran untuk 7 tagihan. Segera verifikasi di menu Pembayaran.', 'pembayaran', 1, '2026-06-29 21:02:42'),
(136, 12, '✅ Pembayaran Diverifikasi (Lunas)', 'Pembayaran 7 tagihan (Bulan 1, Bulan 2, Bulan 3, Bulan 4, Bulan 5, Bulan 6, Bulan 7) sebesar Rp 5.250.000 telah DIVERIFIKASI dan berstatus LUNAS.\n\nCatatan admin: Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏\n\nTerima kasih telah membayar tepat waktu. - Admin Rumah Kos', 'pembayaran', 1, '2026-06-29 21:14:08'),
(137, 12, 'Pengajuan Pindah Kamar Diterima', 'Pengajuan pindah kamar Anda diterima. Admin akan cek ketersediaan kamar tujuan & menyetujui dalam 1x24 jam. Mohon tunggu konfirmasi.', 'pindah', 1, '2026-06-29 21:15:36'),
(138, 1, 'Pengajuan Pindah Kamar Baru', 'diki mengajukan pindah kamar. Segera review di menu Pindah Kamar.', 'pindah', 1, '2026-06-29 21:15:36'),
(139, 12, 'Pengajuan Pindah Kamar Disetujui!', 'Selamat! Pengajuan pindah kamar Anda telah disetujui. Anda sekarang resmi menempati Kamar No. 202. Sisa durasi sewa Anda adalah 12 bulan. Kunci kamar lama harap dikembalikan ke admin. Tagihan deposit kamar baru telah dibuat di menu Pembayaran.', 'info', 1, '2026-06-29 21:17:52'),
(140, 12, 'Pengajuan Pindah Kamar Diterima', 'Pengajuan pindah kamar Anda diterima. Admin akan cek ketersediaan kamar tujuan & menyetujui dalam 1x24 jam. Mohon tunggu konfirmasi.', 'pindah', 1, '2026-06-29 21:34:33'),
(141, 1, 'Pengajuan Pindah Kamar Baru', 'diki mengajukan pindah kamar. Segera review di menu Pindah Kamar.', 'pindah', 1, '2026-06-29 21:34:33'),
(142, 12, 'Inspeksi Kamar Lama - Pindah Kamar', 'Admin akan inspeksi kondisi kamar lama Anda sebelum pindah. Mohon kamar dalam kondisi bersih.', 'pindah', 1, '2026-06-29 21:35:29'),
(143, 12, 'Pengajuan Pindah Kamar Diterima', 'Pengajuan pindah kamar Anda diterima. Admin akan cek ketersediaan kamar tujuan & menyetujui dalam 1x24 jam. Mohon tunggu konfirmasi.', 'pindah', 1, '2026-06-29 21:48:15'),
(144, 1, 'Pengajuan Pindah Kamar Baru', 'diki mengajukan pindah kamar. Segera review di menu Pindah Kamar.', 'pindah', 1, '2026-06-29 21:48:15'),
(145, 12, 'Inspeksi Kamar Lama - Pindah Kamar', 'Admin akan inspeksi kondisi kamar lama Anda sebelum pindah. Mohon kamar dalam kondisi bersih.', 'pindah', 1, '2026-06-29 21:48:47'),
(146, 12, 'Pengajuan Pindah Kamar Diterima', 'Pengajuan pindah kamar Anda diterima. Admin akan cek ketersediaan kamar tujuan & menyetujui dalam 1x24 jam. Mohon tunggu konfirmasi.', 'pindah', 1, '2026-06-29 21:51:05'),
(147, 1, 'Pengajuan Pindah Kamar Baru', 'diki mengajukan pindah kamar. Segera review di menu Pindah Kamar.', 'pindah', 1, '2026-06-29 21:51:05'),
(148, 12, 'Inspeksi Kamar Lama - Pindah Kamar', 'Admin akan inspeksi kondisi kamar lama Anda sebelum pindah. Mohon kamar dalam kondisi bersih.', 'pindah', 1, '2026-06-29 21:51:32'),
(149, 12, 'Pengajuan Pindah Kamar Diterima', 'Pengajuan pindah kamar Anda diterima. Admin akan cek ketersediaan kamar tujuan & menyetujui dalam 1x24 jam. Mohon tunggu konfirmasi.', 'pindah', 1, '2026-06-29 21:59:07'),
(150, 1, 'Pengajuan Pindah Kamar Baru', 'diki mengajukan pindah kamar. Segera review di menu Pindah Kamar.', 'pindah', 1, '2026-06-29 21:59:07'),
(151, 12, '✅ Pindah Kamar Disetujui!', 'Pengajuan pindah kamar Anda disetujui! Anda sekarang di Kamar No. 206. Sisa durasi: 12 bulan.\n\nHasil inspeksi kamar lama: Kamar lama dalam kondisi bersih dan terawat. Tidak ada kerusakan. Kunci lengkap.\n\nRincian Deposit:\n• Deposit lama: Rp 1.200.000\n• Deposit dipindah ke kamar baru: Rp 1.200.000\n• Deposit kamar baru: Rp 1.500.000\n\n⚠️ Anda perlu membayar SELISIH deposit sebesar Rp 300.000 di menu Pembayaran.\n\nKunci kamar baru siap diambil di Office. Kunci kamar lama harap dikembalikan. Terima kasih. 🙏', 'pindah', 1, '2026-06-29 22:03:57'),
(152, 12, 'Pembayaran Diterima', 'Bukti pembayaran Anda telah diupload untuk 2 tagihan. Admin akan verifikasi dalam 1x24 jam. Status tagihan akan berubah jadi \"Lunas\" setelah diverifikasi.', 'pembayaran', 1, '2026-06-29 22:05:41'),
(153, 1, 'Pembayaran Baru Perlu Verifikasi', 'Penghuni diki upload bukti pembayaran untuk 2 tagihan. Segera verifikasi di menu Pembayaran.', 'pembayaran', 1, '2026-06-29 22:05:41'),
(154, 12, 'Kunci Telah Diambil', 'Kunci kamar Anda telah diterima. Selamat menempati kamar Anda. Jangan lupa menjaga kebersihan dan ketertiban kos.', 'info', 1, '2026-06-29 22:06:43'),
(155, 12, '✅ Pembayaran Diverifikasi (Lunas)', 'Pembayaran 2 tagihan (Deposit, Deposit) sebesar Rp 1.050.000 telah DIVERIFIKASI dan berstatus LUNAS.\n\nCatatan admin: Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏\n\nTerima kasih telah membayar tepat waktu. - Admin Rumah Kos', 'pembayaran', 1, '2026-06-29 22:07:10'),
(156, 8, 'Pembayaran Diterima', 'Bukti pembayaran Anda telah diupload untuk 1 tagihan. Admin akan verifikasi dalam 1x24 jam. Status tagihan akan berubah jadi \"Lunas\" setelah diverifikasi.', 'pembayaran', 0, '2026-06-29 22:55:09'),
(157, 1, 'Pembayaran Baru Perlu Verifikasi', 'Penghuni User Checkout upload bukti pembayaran untuk 1 tagihan. Segera verifikasi di menu Pembayaran.', 'pembayaran', 1, '2026-06-29 22:55:09'),
(158, 8, '✅ Pembayaran Diverifikasi (Lunas)', 'Pembayaran Deposit sebesar Rp 1.400.000 telah DIVERIFIKASI dan berstatus LUNAS.\n\nCatatan admin: Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏\n\nTerima kasih telah membayar tepat waktu. - Admin Rumah Kos', 'pembayaran', 0, '2026-06-29 22:58:59'),
(159, 8, 'Pengajuan Checkout Diterima', 'Pengajuan checkout Anda diterima. Admin akan proses & menjadwalkan inspeksi kamar. Deposit akan dikembalikan setelah kamar dinyatakan bersih & tidak rusak.', 'checkout', 0, '2026-06-29 22:59:35'),
(160, 1, 'Pengajuan Checkout Baru', 'User Checkout mengajukan checkout. Segera proses di menu Checkout untuk inspeksi kamar & pengembalian deposit.', 'checkout', 1, '2026-06-29 22:59:35'),
(161, 8, 'Check-Out Dalam Proses Inspeksi', 'Pengajuan check-out Anda sedang diproses. Tim admin akan mengunjungi kamar Anda untuk inspeksi kondisi kamar.', 'checkout', 0, '2026-06-29 22:59:58'),
(162, 8, '✅ Checkout Disetujui - Pengembalian Dana', 'Check-Out Disetujui! Anda telah resmi keluar dari Kamar No. 104.\n\n📊 Rincian Pengembalian Dana:\n• Lama Huni: 1 bulan (dari 8 bulan kontrak)\n• Sisa Bulan: 7 bulan\n• Refund Sisa Sewa: Rp 4.900.000\n• Deposit: Rp 1.400.000\n• Refund Deposit: Rp 1.400.000\n\n💰 TOTAL DIKEMBALIKAN: Rp 6.300.000\n\n Bukti transfer refund sudah diupload admin. Cek halaman Checkout Anda untuk lihat & download bukti.\n\nCatatan Inspeksi: Kamar dalam kondisi bersih dan terawat. Tidak ada kerusakan. Kunci lengkap.\n\nTerima kasih. 🙏', 'checkout', 1, '2026-06-29 23:07:44'),
(163, 1, 'Pendaftar Baru', 'Pengguna baru mendaftar: jovan (HP: 0812127878909). Belum mengajukan sewa.', 'user_baru', 1, '2026-06-29 18:14:28'),
(164, 13, 'Pengajuan Sewa Diterima', 'Pengajuan sewa kamar No. 202 diterima. WAJIB bayar Deposit Rp 1.200.000 dalam 3 hari (jatuh tempo: 02 Jul 2026) di menu Pembayaran. Setelah deposit lunas, admin akan memproses persetujuan.', 'sewa', 1, '2026-06-29 18:16:17'),
(165, 1, 'Pengajuan Sewa Baru', 'Pengajuan sewa baru dari jovan (Kamar No. 202). Tunggu user membayar deposit sebelum disetujui.', 'sewa', 1, '2026-06-29 18:16:17'),
(166, 13, 'Pembayaran Diterima', 'Bukti pembayaran Anda telah diupload untuk 1 tagihan. Admin akan verifikasi dalam 1x24 jam. Status tagihan akan berubah jadi \"Lunas\" setelah diverifikasi.', 'pembayaran', 1, '2026-06-29 18:17:39'),
(167, 1, 'Pembayaran Baru Perlu Verifikasi', 'Penghuni jovan upload bukti pembayaran untuk 1 tagihan. Segera verifikasi di menu Pembayaran.', 'pembayaran', 1, '2026-06-29 18:17:39'),
(168, 13, '❌ Pengajuan Sewa Ditolak', 'Maaf, pengajuan sewa Anda ditolak. Alasan: Pengajuan sewa ditolak oleh admin.. Silakan pilih kamar lain yang tersedia. Terima kasih. 🙏', 'sewa', 1, '2026-06-30 02:07:46'),
(169, 13, 'Pengajuan Sewa Diterima', 'Pengajuan sewa kamar No. 103 diterima. WAJIB bayar Deposit Rp 1.400.000 dalam 3 hari (jatuh tempo: 02 Jul 2026) di menu Pembayaran. Setelah deposit lunas, admin akan memproses persetujuan.', 'sewa', 1, '2026-06-29 19:10:17'),
(170, 1, 'Pengajuan Sewa Baru', 'Pengajuan sewa baru dari jovan (Kamar No. 103). Tunggu user membayar deposit sebelum disetujui.', 'sewa', 1, '2026-06-29 19:10:17'),
(171, 13, '❌ Pengajuan Sewa Ditolak', 'Maaf, pengajuan sewa Anda ditolak. Alasan: Pengajuan sewa ditolak oleh admin.. Silakan pilih kamar lain yang tersedia. Terima kasih. 🙏', 'sewa', 1, '2026-06-29 19:10:58'),
(172, 13, 'Pengajuan Sewa Diterima', 'Pengajuan sewa kamar No. 202 diterima. WAJIB bayar Deposit Rp 1.200.000 dalam 3 hari (jatuh tempo: 02 Jul 2026) di menu Pembayaran. Setelah deposit lunas, admin akan memproses persetujuan.', 'sewa', 1, '2026-06-29 19:12:55'),
(173, 1, 'Pengajuan Sewa Baru', 'Pengajuan sewa baru dari jovan (Kamar No. 202). Tunggu user membayar deposit sebelum disetujui.', 'sewa', 1, '2026-06-29 19:12:55'),
(174, 1, 'Pendaftar Baru', 'Pengguna baru mendaftar: jovan  (HP: 08121212121). Belum mengajukan sewa.', 'user_baru', 1, '2026-06-30 02:25:22'),
(175, 14, 'Pengajuan Sewa Diterima', 'Pengajuan sewa kamar No. 207 diterima. WAJIB bayar Deposit Rp 1.600.000 dalam 3 hari (jatuh tempo: 02 Jul 2026) di menu Pembayaran. Setelah deposit lunas, admin akan memproses persetujuan.', 'sewa', 0, '2026-06-30 02:26:00'),
(176, 1, 'Pengajuan Sewa Baru', 'Pengajuan sewa baru dari jovan  (Kamar No. 207). Tunggu user membayar deposit sebelum disetujui.', 'sewa', 1, '2026-06-30 02:26:00'),
(177, 1, 'Pendaftar Baru', 'Pengguna baru mendaftar: yuki (HP: 081212323241). Belum mengajukan sewa.', 'user_baru', 1, '2026-06-29 19:30:12'),
(178, 15, 'Pengajuan Sewa Diterima', 'Pengajuan sewa kamar No. 103 diterima. WAJIB bayar Deposit Rp 1.400.000 dalam 3 hari (jatuh tempo: 02 Jul 2026) di menu Pembayaran. Setelah deposit lunas, admin akan memproses persetujuan.', 'sewa', 0, '2026-06-29 19:30:47'),
(179, 1, 'Pengajuan Sewa Baru', 'Pengajuan sewa baru dari yuki (Kamar No. 103). Tunggu user membayar deposit sebelum disetujui.', 'sewa', 1, '2026-06-29 19:30:47'),
(180, 15, '❌ Pengajuan Sewa Ditolak', 'Maaf, pengajuan sewa Anda ditolak. Alasan: Pengajuan sewa ditolak oleh admin.. Silakan pilih kamar lain yang tersedia. Terima kasih. 🙏', 'sewa', 0, '2026-06-29 19:42:28'),
(181, 14, '❌ Pengajuan Sewa Ditolak', 'Maaf, pengajuan sewa Anda ditolak. Alasan: Pengajuan sewa ditolak oleh admin.. Silakan pilih kamar lain yang tersedia. Terima kasih. 🙏', 'sewa', 0, '2026-06-29 20:36:52'),
(182, 13, '❌ Pengajuan Sewa Ditolak', 'Maaf, pengajuan sewa Anda ditolak. Alasan: Pengajuan sewa ditolak oleh admin.. Silakan pilih kamar lain yang tersedia. Terima kasih. 🙏', 'sewa', 1, '2026-06-29 20:36:56'),
(183, 1, 'Pendaftar Baru', 'Pengguna baru mendaftar: jovan (HP: 081213141561). Belum mengajukan sewa.', 'user_baru', 1, '2026-06-29 20:37:45'),
(184, 16, 'Pengajuan Sewa Diterima', 'Pengajuan sewa kamar No. 103 diterima. WAJIB bayar Deposit Rp 1.400.000 dalam 3 hari (jatuh tempo: 02 Jul 2026) di menu Pembayaran. Setelah deposit lunas, admin akan memproses persetujuan.', 'sewa', 0, '2026-06-29 20:38:14'),
(185, 1, 'Pengajuan Sewa Baru', 'Pengajuan sewa baru dari jovan (Kamar No. 103). Tunggu user membayar deposit sebelum disetujui.', 'sewa', 1, '2026-06-29 20:38:14'),
(186, 16, '❌ Pengajuan Sewa Ditolak', 'Maaf, pengajuan sewa Anda ditolak. Alasan: Pengajuan sewa ditolak oleh admin.. Silakan pilih kamar lain yang tersedia. Terima kasih. 🙏', 'sewa', 0, '2026-06-30 03:43:32'),
(187, 1, 'Pendaftar Baru', 'Pengguna baru mendaftar: ery (HP: 081234351212). Belum mengajukan sewa.', 'user_baru', 1, '2026-06-30 03:47:50'),
(188, 17, 'Pengajuan Sewa Diterima', 'Pengajuan sewa kamar No. 103 diterima. WAJIB bayar Deposit Rp 1.400.000 dalam 3 hari (jatuh tempo: 02 Jul 2026) di menu Pembayaran. Setelah deposit lunas, admin akan memproses persetujuan.', 'sewa', 0, '2026-06-30 03:48:41'),
(189, 1, 'Pengajuan Sewa Baru', 'Pengajuan sewa baru dari ery (Kamar No. 103). Tunggu user membayar deposit sebelum disetujui.', 'sewa', 1, '2026-06-30 03:48:41'),
(190, 17, 'Pembayaran Diterima', 'Bukti pembayaran Anda telah diupload untuk 1 tagihan. Admin akan verifikasi dalam 1x24 jam. Status tagihan akan berubah jadi \"Lunas\" setelah diverifikasi.', 'pembayaran', 0, '2026-06-30 03:50:59'),
(191, 1, 'Pembayaran Baru Perlu Verifikasi', 'Penghuni ery upload bukti pembayaran untuk 1 tagihan. Segera verifikasi di menu Pembayaran.', 'pembayaran', 1, '2026-06-30 03:50:59'),
(192, 17, '✅ Pembayaran Diverifikasi (Lunas)', 'Pembayaran Deposit sebesar Rp 1.400.000 telah DIVERIFIKASI dan berstatus LUNAS.\n\nCatatan admin: Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏\n\nTerima kasih telah membayar tepat waktu. - Admin Rumah Kos', 'pembayaran', 0, '2026-06-30 03:51:39'),
(193, 17, 'Pengajuan Sewa Disetujui!', 'Selamat! Pengajuan sewa Anda disetujui karena deposit sudah lunas. Kunci kamar No. 103 siap diambil di Office Rumah Kos.', 'sewa', 1, '2026-06-30 03:51:54'),
(195, 17, 'Pembayaran Diterima', 'Bukti pembayaran Anda telah diupload untuk 12 tagihan. Admin akan verifikasi dalam 1x24 jam. Status tagihan akan berubah jadi \"Lunas\" setelah diverifikasi.', 'pembayaran', 0, '2026-06-30 03:56:43'),
(196, 1, 'Pembayaran Baru Perlu Verifikasi', 'Penghuni ery upload bukti pembayaran untuk 12 tagihan. Segera verifikasi di menu Pembayaran.', 'pembayaran', 1, '2026-06-30 03:56:43'),
(197, 17, 'Kunci Telah Diambil', 'Kunci kamar Anda telah diterima. Selamat menempati kamar Anda. Jangan lupa menjaga kebersihan dan ketertiban kos.', 'info', 0, '2026-06-30 03:57:14'),
(198, 17, '✅ Pembayaran Diverifikasi (Lunas)', 'Pembayaran 12 tagihan (Bulan 1, Bulan 2, Bulan 3, Bulan 4, Bulan 5, Bulan 6, Bulan 7, Bulan 8, Bulan 9, Bulan 10, Bulan 11, Bulan 12) sebesar Rp 8.400.000 telah DIVERIFIKASI dan berstatus LUNAS.\n\nCatatan admin: Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏\n\nTerima kasih telah membayar tepat waktu. - Admin Rumah Kos', 'pembayaran', 0, '2026-06-30 03:57:32'),
(199, 17, 'Pengajuan Pindah Kamar Diterima', 'Pengajuan pindah kamar Anda diterima. Admin akan cek ketersediaan kamar tujuan & menyetujui dalam 1x24 jam. Mohon tunggu konfirmasi.', 'pindah', 0, '2026-06-30 03:58:02'),
(200, 1, 'Pengajuan Pindah Kamar Baru', 'ery mengajukan pindah kamar. Segera review di menu Pindah Kamar.', 'pindah', 1, '2026-06-30 03:58:02'),
(201, 17, '❌ Pindah Kamar Ditolak', 'Maaf, pengajuan pindah kamar Anda ditolak. Alasan: Pengajuan pindah kamar ditolak oleh admin.. Hubungi admin untuk info lebih lanjut.', 'pindah', 0, '2026-06-30 03:59:58'),
(202, 17, 'Pengajuan Pindah Kamar Diterima', 'Pengajuan pindah kamar Anda diterima. Admin akan cek ketersediaan kamar tujuan & menyetujui dalam 1x24 jam. Mohon tunggu konfirmasi.', 'pindah', 0, '2026-06-30 04:00:31'),
(203, 1, 'Pengajuan Pindah Kamar Baru', 'ery mengajukan pindah kamar. Segera review di menu Pindah Kamar.', 'pindah', 1, '2026-06-30 04:00:31'),
(204, 17, '❌ Pindah Kamar Ditolak', 'Maaf, pengajuan pindah kamar Anda ditolak. Alasan: Pengajuan pindah kamar ditolak oleh admin.. Hubungi admin untuk info lebih lanjut.', 'pindah', 0, '2026-06-30 04:13:53'),
(205, 17, 'Pengajuan Pindah Kamar Diterima', 'Pengajuan pindah kamar Anda diterima. Admin akan cek ketersediaan kamar tujuan & menyetujui dalam 1x24 jam. Mohon tunggu konfirmasi.', 'pindah', 0, '2026-06-30 04:15:41'),
(206, 1, 'Pengajuan Pindah Kamar Baru', 'ery mengajukan pindah kamar. Segera review di menu Pindah Kamar.', 'pindah', 1, '2026-06-30 04:15:41'),
(207, 17, '❌ Pindah Kamar Ditolak', 'Maaf, pengajuan pindah kamar Anda ditolak. Alasan: Pengajuan pindah kamar ditolak oleh admin.. Hubungi admin untuk info lebih lanjut.', 'pindah', 0, '2026-06-30 04:16:20'),
(208, 17, 'Pengajuan Pindah Kamar Diterima', 'Pengajuan pindah kamar Anda diterima. Admin akan cek ketersediaan kamar tujuan & menyetujui dalam 1x24 jam. Mohon tunggu konfirmasi.', 'pindah', 0, '2026-06-30 04:17:23'),
(209, 1, 'Pengajuan Pindah Kamar Baru', 'ery mengajukan pindah kamar. Segera review di menu Pindah Kamar.', 'pindah', 1, '2026-06-30 04:17:23'),
(210, 17, '✅ Pindah Kamar Disetujui!', 'Pengajuan pindah kamar Anda disetujui! Anda sekarang di Kamar No. 102. Sisa durasi: 13 bulan.\n\nHasil inspeksi kamar lama: Kamar lama dalam kondisi bersih dan terawat. Tidak ada kerusakan. Kunci lengkap.\n\nRincian Deposit:\n• Deposit lama: Rp 1.400.000\n• Deposit dipindah ke kamar baru: Rp 1.400.000\n• Deposit kamar baru: Rp 1.000.000\n\n✓ Tidak ada pembayaran tambahan. Deposit lama sudah cukup.\n\nKunci kamar baru siap diambil di Office. Kunci kamar lama harap dikembalikan. Terima kasih. 🙏', 'pindah', 0, '2026-06-30 04:18:41'),
(211, 1, 'Pendaftar Baru', 'Pengguna baru mendaftar: yuki (HP: 081234561212). Belum mengajukan sewa.', 'user_baru', 1, '2026-06-30 15:54:40'),
(212, 18, 'Pengajuan Sewa Diterima', 'Pengajuan sewa kamar No. 205 diterima. WAJIB bayar Deposit Rp 4.000.000 dalam 3 hari (jatuh tempo: 03 Jul 2026) di menu Pembayaran. Setelah deposit lunas, admin akan memproses persetujuan.', 'sewa', 1, '2026-06-30 15:55:32'),
(213, 1, 'Pengajuan Sewa Baru', 'Pengajuan sewa baru dari yuki (Kamar No. 205). Tunggu user membayar deposit sebelum disetujui.', 'sewa', 1, '2026-06-30 15:55:32'),
(214, 7, 'Tagihan Terlambat - Denda Rp 600.000', 'Tagihan sewa kamar No. 101 (Bulan ke-1) sudah melewati jatuh tempo. Denda: Rp 600.000. Segera bayar!', 'tagihan', 0, '2026-06-30 15:55:51'),
(215, 7, 'Tagihan Terlambat - Denda Rp 290.000', 'Tagihan sewa kamar No. 101 (Bulan ke-2) sudah melewati jatuh tempo. Denda: Rp 290.000. Segera bayar!', 'tagihan', 0, '2026-06-30 15:55:51'),
(216, 18, 'Pembayaran Diterima', 'Bukti pembayaran Anda telah diupload untuk 1 tagihan. Admin akan verifikasi dalam 1x24 jam. Status tagihan akan berubah jadi \"Lunas\" setelah diverifikasi.', 'pembayaran', 1, '2026-06-30 15:57:43'),
(217, 1, 'Pembayaran Baru Perlu Verifikasi', 'Penghuni yuki upload bukti pembayaran untuk 1 tagihan. Segera verifikasi di menu Pembayaran.', 'pembayaran', 1, '2026-06-30 15:57:43'),
(218, 18, '✅ Pembayaran Diverifikasi (Lunas)', 'Pembayaran Deposit sebesar Rp 4.000.000 telah DIVERIFIKASI dan berstatus LUNAS.\n\nCatatan admin: Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏\n\nTerima kasih telah membayar tepat waktu. - Admin Rumah Kos', 'pembayaran', 1, '2026-06-30 15:58:50'),
(219, 18, 'Pengajuan Sewa Disetujui!', 'Selamat! Pengajuan sewa Anda disetujui karena deposit sudah lunas. Kunci kamar No. 205 siap diambil di Office Rumah Kos.', 'sewa', 1, '2026-06-30 16:00:04'),
(221, 18, 'Pembayaran Diterima', 'Bukti pembayaran Anda telah diupload untuk 1 tagihan. Admin akan verifikasi dalam 1x24 jam. Status tagihan akan berubah jadi \"Lunas\" setelah diverifikasi.', 'pembayaran', 1, '2026-06-30 17:17:06'),
(222, 1, 'Pembayaran Baru Perlu Verifikasi', 'Penghuni yuki upload bukti pembayaran untuk 1 tagihan. Segera verifikasi di menu Pembayaran.', 'pembayaran', 1, '2026-06-30 17:17:06'),
(223, 18, '✅ Pembayaran Diverifikasi (Lunas)', 'Pembayaran Sewa Bulan ke-1 sebesar Rp 2.000.000 telah DIVERIFIKASI dan berstatus LUNAS.\n\nCatatan admin: Sewa bulan ke-1\n\nTerima kasih telah membayar tepat waktu. - Admin Rumah Kos', 'pembayaran', 1, '2026-06-30 17:22:54'),
(224, 18, 'Pengajuan Pindah Kamar Diterima', 'Pengajuan pindah kamar Anda diterima. Admin akan cek ketersediaan kamar tujuan & menyetujui dalam 1x24 jam. Mohon tunggu konfirmasi.', 'pindah', 1, '2026-06-30 17:25:10'),
(225, 1, 'Pengajuan Pindah Kamar Baru', 'yuki mengajukan pindah kamar. Segera review di menu Pindah Kamar.', 'pindah', 1, '2026-06-30 17:25:10'),
(226, 18, '❌ Pindah Kamar Ditolak', 'Maaf, pengajuan pindah kamar Anda ditolak. Alasan: Pengajuan pindah kamar ditolak oleh admin.. Hubungi admin untuk info lebih lanjut.', 'pindah', 1, '2026-06-30 17:31:15'),
(227, 18, 'Kunci Telah Diambil', 'Kunci kamar Anda telah diterima. Selamat menempati kamar Anda. Jangan lupa menjaga kebersihan dan ketertiban kos.', 'info', 1, '2026-06-30 17:33:31'),
(228, 18, 'Pengajuan Pindah Kamar Diterima', 'Pengajuan pindah kamar Anda diterima. Admin akan cek ketersediaan kamar tujuan & menyetujui dalam 1x24 jam. Mohon tunggu konfirmasi.', 'pindah', 1, '2026-06-30 17:34:50'),
(229, 1, 'Pengajuan Pindah Kamar Baru', 'yuki mengajukan pindah kamar. Segera review di menu Pindah Kamar.', 'pindah', 1, '2026-06-30 17:34:50'),
(230, 18, '✅ Pindah Kamar Disetujui!', 'Pengajuan pindah kamar Anda disetujui! Anda sekarang di Kamar No. 207. Sisa durasi: 3 bulan.\n\nHasil inspeksi kamar lama: Kamar lama dalam kondisi bersih dan terawat. Tidak ada kerusakan. Kunci lengkap.\n\nRincian Deposit:\n• Deposit lama: Rp 4.000.000\n• Deposit dipindah ke kamar baru: Rp 4.000.000\n• Deposit kamar baru: Rp 1.600.000\n\n✓ Tidak ada pembayaran tambahan. Deposit lama sudah cukup.\n\nKunci kamar baru siap diambil di Office. Kunci kamar lama harap dikembalikan. Terima kasih. 🙏', 'pindah', 1, '2026-06-30 17:36:15'),
(231, 17, 'Kunci Telah Diambil', 'Kunci kamar Anda telah diterima. Selamat menempati kamar Anda. Jangan lupa menjaga kebersihan dan ketertiban kos.', 'info', 0, '2026-06-30 17:36:28'),
(232, 18, 'Pembayaran Diterima', 'Bukti pembayaran Anda telah diupload untuk 1 tagihan. Admin akan verifikasi dalam 1x24 jam. Status tagihan akan berubah jadi \"Lunas\" setelah diverifikasi.', 'pembayaran', 1, '2026-06-30 18:05:43'),
(233, 1, 'Pembayaran Baru Perlu Verifikasi', 'Penghuni yuki upload bukti pembayaran untuk 1 tagihan. Segera verifikasi di menu Pembayaran.', 'pembayaran', 1, '2026-06-30 18:05:43'),
(234, 18, '✅ Pembayaran Diverifikasi (Lunas)', 'Pembayaran Sewa Bulan ke-2 sebesar Rp 800.000 telah DIVERIFIKASI dan berstatus LUNAS.\n\nCatatan admin: Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏\n\nTerima kasih telah membayar tepat waktu. - Admin Rumah Kos', 'pembayaran', 1, '2026-06-30 18:06:20'),
(235, 18, 'Pengajuan Checkout Diterima', 'Pengajuan checkout Anda diterima. Admin akan proses & menjadwalkan inspeksi kamar.\n\nCatatan:\n- Anda TIDAK perlu melunasi semua tagihan untuk checkout.\n- Tagihan yang masih belum dibayar akan dibatalkan saat checkout disetujui.\n- Sisa sewa (bulan belum dihuni) + deposit (setelah potongan kerusakan) akan dikembalikan ke Anda.', 'checkout', 1, '2026-06-30 18:51:17'),
(236, 1, 'Pengajuan Checkout Baru', 'yuki mengajukan checkout. Segera proses di menu Checkout untuk inspeksi kamar & pengembalian dana.', 'checkout', 1, '2026-06-30 18:51:17'),
(237, 18, '✅ Checkout Disetujui - Pengembalian Dana', 'Check-Out Disetujui! Anda telah resmi keluar dari Kamar No. 207.\n\n📊 Rincian Pengembalian Dana:\n• Lama Huni: 1 bulan (dari 3 bulan kontrak)\n• Sisa Bulan: 2 bulan\n• Refund Sisa Sewa: Rp 1.600.000\n• Deposit: Rp 1.600.000\n• Refund Deposit: Rp 1.600.000\n\n📝 Catatan: 1 tagihan belum dibayar Anda otomatis DIBATALKAN karena Anda sudah checkout (tidak perlu dilunasi).\n\n💰 TOTAL DIKEMBALIKAN: Rp 3.200.000\n\nBukti transfer refund sudah diupload admin. Cek halaman Checkout Anda untuk lihat & download bukti.\n\nCatatan Inspeksi: Kamar dalam kondisi bersih dan terawat. Tidak ada kerusakan. Kunci lengkap.\n\nTerima kasih. 🙏', 'checkout', 1, '2026-06-30 19:24:52'),
(238, 18, 'Pengajuan Sewa Diterima - Rincian Pembayaran', 'Pengajuan sewa kamar No. 104 diterima.\\n\\n📊 RINCIAN PEMBAYARAN AWAL YANG WAJIB DIBAYAR:\\n• Deposit (sebesar 2x sewa): Rp 1.400.000\\n• Sewa Bulan Ke-1: Rp 700.000\\n────────────────────\\n💰 TOTAL WAJIB BAYAR AWAL: Rp 2.100.000\\n\\n⏰ BATAS WAKTU BAYAR DEPOSIT: 3 hari\\n   Jatuh tempo: 03 Jul 2026\\n\\n📝 CARA PEMBAYARAN:\\n1. Buka menu Pembayaran\\n2. Cari tagihan \"Deposit Awal Sewa\"\\n3. Upload bukti transfer\\n4. Tunggu verifikasi admin\\n\\nℹ️ INFORMASI PENTING:\\n• Deposit DIBAYARKAN SEKALI di awal & akan DIKEMBALIKAN saat checkout (setelah potong kerusakan jika ada)\\n• Setelah disetujui admin, tagihan bulan ke-2 dst dibayar bulanan sesuai tanggal mulai\\n• Kontrak berakhir: 30 Dec 2026 (6 bulan)\\n\\n⚠️ Jika deposit tidak dibayar dalam 3 hari, pengajuan sewa bisa ditolak admin.', 'sewa', 1, '2026-06-30 20:07:59'),
(239, 1, 'Pengajuan Sewa Baru', 'Pengajuan sewa baru dari yuki (Kamar No. 104). Tunggu user membayar deposit sebelum disetujui.', 'sewa', 1, '2026-06-30 20:07:59'),
(240, 18, 'Pembayaran Diterima', 'Bukti pembayaran Anda telah diupload untuk 1 tagihan. Admin akan verifikasi dalam 1x24 jam. Status tagihan akan berubah jadi \"Lunas\" setelah diverifikasi.', 'pembayaran', 1, '2026-06-30 20:08:49'),
(241, 1, 'Pembayaran Baru Perlu Verifikasi', 'Penghuni yuki upload bukti pembayaran untuk 1 tagihan. Segera verifikasi di menu Pembayaran.', 'pembayaran', 1, '2026-06-30 20:08:49');
INSERT INTO `notifikasi` (`id_notifikasi`, `id_user`, `judul`, `pesan`, `tipe`, `dibaca`, `created_at`) VALUES
(242, 18, '✅ Pembayaran Diverifikasi (Lunas)', 'Pembayaran Deposit sebesar Rp 1.400.000 telah DIVERIFIKASI dan berstatus LUNAS.\n\nCatatan admin: Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏\n\nTerima kasih telah membayar tepat waktu. - Admin Rumah Kos', 'pembayaran', 1, '2026-06-30 20:10:17'),
(243, 18, 'Pengajuan Sewa Disetujui!', 'Selamat! Pengajuan sewa Anda disetujui karena deposit sudah lunas. Kunci kamar No. 104 siap diambil di Office Rumah Kos.', 'sewa', 1, '2026-06-30 20:10:30'),
(245, 18, 'Kunci Telah Diambil', 'Kunci kamar Anda telah diterima. Selamat menempati kamar Anda. Jangan lupa menjaga kebersihan dan ketertiban kos.', 'info', 0, '2026-06-30 20:28:02'),
(246, 18, 'Pembayaran Diterima', 'Bukti pembayaran Anda telah diupload untuk 3 tagihan. Admin akan verifikasi dalam 1x24 jam. Status tagihan akan berubah jadi \"Lunas\" setelah diverifikasi.', 'pembayaran', 0, '2026-06-30 20:32:42'),
(247, 1, 'Pembayaran Baru Perlu Verifikasi', 'Penghuni yuki upload bukti pembayaran untuk 3 tagihan. Segera verifikasi di menu Pembayaran.', 'pembayaran', 1, '2026-06-30 20:32:42'),
(248, 18, '✅ Pembayaran Diverifikasi (Lunas)', 'Pembayaran 3 tagihan (Bulan 1, Bulan 2, Bulan 3) sebesar Rp 2.100.000 telah DIVERIFIKASI dan berstatus LUNAS.\n\nCatatan admin: Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏\n\nTerima kasih telah membayar tepat waktu. - Admin Rumah Kos', 'pembayaran', 0, '2026-06-30 20:33:15'),
(249, 18, 'Pengajuan Pindah Kamar Diterima', 'Pengajuan pindah kamar Anda diterima. Admin akan cek ketersediaan kamar tujuan & menyetujui dalam 1x24 jam. Mohon tunggu konfirmasi.\n\n📊 Info Deposit Kamar:\n• Deposit lama (kamar Anda sekarang): Rp 1.400.000\n• Deposit baru (kamar tujuan): Rp 1.200.000\n\n💰 ANDA DAPAT UANG KEMBALIAN: Rp 200.000\n   (karena kamar baru lebih murah dari kamar lama)\n   Uang kembalian akan ditransfer admin setelah pindah disetujui.\n\n⚠️ Catatan: Deposit lama bisa dipotong kalau kamar lama rusak/kotor saat inspeksi.', 'pindah', 0, '2026-06-30 20:36:31'),
(250, 1, 'Pengajuan Pindah Kamar Baru', 'yuki mengajukan pindah kamar. Segera review di menu Pindah Kamar.', 'pindah', 1, '2026-06-30 20:36:31'),
(251, 18, '✅ Pindah Kamar Disetujui!', 'Pengajuan pindah kamar Anda disetujui! Anda sekarang di Kamar No. 202.\n\nHasil inspeksi kamar lama: Kamar lama dalam kondisi bersih dan terawat. Tidak ada kerusakan. Kunci lengkap.\n\nRincian Keuangan:\n• Deposit lama: Rp 1.400.000\n• Deposit dipindah: Rp 1.400.000\n• Uang kembalian (selisih deposit): Rp 200.000\n\n✅ Bukti transfer refund sudah diupload admin. Cek halaman Pindah Kamar Anda untuk download bukti.\n\n⚠️ Kebijakan Pindah Pertengahan Bulan:\n• Sewa bulan berjalan di kamar lama dianggap HANGUS.\n• Anda wajib membayar sewa penuh untuk bulan berjalan di kamar baru: Rp 600.000\n\nKunci kamar baru siap diambil di Office. Kunci kamar lama harap dikembalikan. Terima kasih. 🙏', 'pindah', 0, '2026-06-30 20:39:36'),
(252, 1, 'Pendaftar Baru', 'Pengguna baru mendaftar: Jojo (HP: 08121819371929). Belum mengajukan sewa.', 'user_baru', 1, '2026-06-30 22:33:00'),
(253, 1, 'Pendaftar Baru', 'Pengguna baru mendaftar: Siti Baejudah (HP: 08222222222). Belum mengajukan sewa.', 'user_baru', 1, '2026-06-30 22:35:59'),
(254, 18, 'Tagihan Jatuh Tempo Hari Ini', 'Tagihan sewa Bulan ke-0 sebesar Rp 600.000 jatuh tempo HARI INI. Segera lakukan pembayaran di menu Pembayaran.', 'tagihan', 0, '2026-06-30 22:43:35'),
(255, 4, 'Keluhan Diterima: Pengumuman Gangguan Wi-Fi', 'Keluhan Wi-Fi Anda diterima. Admin akan restart router/cek koneksi. Estimasi normal: 1-2 jam. Sementara gunakan kuota data.', 'keluhan', 0, '2026-07-01 00:55:39'),
(256, 1, 'Keluhan Baru (PENGHUNI)', '[PENGHUNI] Pengumuman Gangguan Wi-Fi', 'keluhan', 1, '2026-07-01 00:55:39'),
(257, 12, 'Keluhan Diterima: Pengumuman Gangguan Wi-Fi', 'Keluhan Wi-Fi Anda diterima. Admin akan restart router/cek koneksi. Estimasi normal: 1-2 jam. Sementara gunakan kuota data.', 'keluhan', 1, '2026-07-01 01:02:11'),
(258, 1, 'Keluhan Baru (PENGHUNI)', '[PENGHUNI] Pengumuman Gangguan Wi-Fi', 'keluhan', 1, '2026-07-01 01:02:11'),
(259, 12, 'Keluhan Diterima: Pengumuman Gangguan Wi-Fi', 'Keluhan Wi-Fi Anda diterima. Admin akan restart router/cek koneksi. Estimasi normal: 1-2 jam. Sementara gunakan kuota data.', 'keluhan', 1, '2026-07-01 01:08:34'),
(260, 1, 'Keluhan Baru (PENGHUNI)', '[PENGHUNI] Pengumuman Gangguan Wi-Fi', 'keluhan', 1, '2026-07-01 01:08:34'),
(261, 7, 'Tagihan Terlambat - Denda Rp 610.000', 'Tagihan sewa kamar No. 101 (Bulan ke-1) sudah melewati jatuh tempo. Denda: Rp 610.000. Segera bayar!', 'tagihan', 0, '2026-07-01 11:33:23'),
(262, 7, 'Tagihan Terlambat - Denda Rp 300.000', 'Tagihan sewa kamar No. 101 (Bulan ke-2) sudah melewati jatuh tempo. Denda: Rp 300.000. Segera bayar!', 'tagihan', 0, '2026-07-01 11:33:24'),
(263, 18, 'Tagihan Terlambat - Denda Rp 10.000', 'Tagihan sewa kamar No. 202 (Bulan ke-0) sudah melewati jatuh tempo. Denda: Rp 10.000. Segera bayar!', 'tagihan', 0, '2026-07-01 11:33:24'),
(264, 12, 'Keluhan Diperbarui: Pengumuman Gangguan Wi-Fi', 'Status keluhan Anda: Menunggu. Balasan admin: Sudah ditangani teknisi. Silakan cek kembali. Terima kasih.', 'keluhan', 1, '2026-07-01 11:33:41'),
(265, 12, 'Pengajuan Pindah Kamar Diterima', 'Pengajuan pindah kamar Anda diterima. Admin akan cek ketersediaan kamar tujuan & menyetujui dalam 1x24 jam. Mohon tunggu konfirmasi.\n\n📊 Info Deposit Kamar:\n• Deposit lama (kamar Anda sekarang): Rp 1.500.000\n• Deposit baru (kamar tujuan): Rp 1.400.000\n\n💰 ANDA DAPAT UANG KEMBALIAN: Rp 100.000\n   (karena kamar baru lebih murah dari kamar lama)\n   Uang kembalian akan ditransfer admin setelah pindah disetujui.\n\n⚠️ Catatan: Deposit lama bisa dipotong kalau kamar lama rusak/kotor saat inspeksi.', 'pindah', 1, '2026-07-01 15:12:15'),
(266, 1, 'Pengajuan Pindah Kamar Baru', 'diki mengajukan pindah kamar. Segera review di menu Pindah Kamar.', 'pindah', 1, '2026-07-01 15:12:15'),
(267, 12, '❌ Pindah Kamar Ditolak', 'Maaf, pengajuan pindah kamar Anda ditolak. Alasan: Tidak memenuhi syarat. Hubungi admin untuk info lebih lanjut.', 'pindah', 1, '2026-07-01 15:19:03'),
(268, 12, 'Keluhan Diterima: Pengumuman Gangguan Wi-Fi', 'Keluhan Wi-Fi Anda diterima. Admin akan restart router/cek koneksi. Estimasi normal: 1-2 jam. Sementara gunakan kuota data.', 'keluhan', 1, '2026-07-01 15:19:45'),
(269, 1, 'Keluhan Baru (PENGHUNI)', '[PENGHUNI] Pengumuman Gangguan Wi-Fi', 'keluhan', 1, '2026-07-01 15:19:45'),
(270, 12, 'Keluhan Diterima: Pengumuman Gangguan Wi-Fi', 'Keluhan Wi-Fi Anda diterima. Admin akan restart router/cek koneksi. Estimasi normal: 1-2 jam. Sementara gunakan kuota data.', 'keluhan', 1, '2026-07-01 22:25:46'),
(271, 1, 'Keluhan Baru (PENGHUNI)', '[PENGHUNI] Pengumuman Gangguan Wi-Fi', 'keluhan', 1, '2026-07-01 22:25:46'),
(272, 1, 'Pendaftar Baru', 'Pengguna baru mendaftar: roger (HP: 081234345656). Belum mengajukan sewa.', 'user_baru', 1, '2026-07-01 22:47:37'),
(273, 21, 'Pengajuan Sewa Diterima', 'Pengajuan sewa kamar No. 104 diterima.\n\nWAJIB bayar Deposit Rp 1.400.000 dalam 3 hari (jatuh tempo: 04 Jul 2026) di menu Pembayaran. Setelah deposit lunas, admin akan memproses persetujuan.', 'sewa', 0, '2026-07-01 22:49:18'),
(274, 1, 'Pengajuan Sewa Baru', 'Pengajuan sewa baru dari roger (Kamar No. 104). Tunggu user membayar deposit sebelum disetujui.', 'sewa', 1, '2026-07-01 22:49:18'),
(275, 21, '❌ Pengajuan Sewa Ditolak', 'Maaf, pengajuan sewa Anda ditolak. Alasan: Pengajuan sewa ditolak oleh admin.. Silakan pilih kamar lain yang tersedia. Terima kasih. 🙏', 'sewa', 1, '2026-07-01 22:55:28'),
(276, 21, 'Pengajuan Sewa Diterima', 'Pengajuan sewa kamar No. 205 diterima.\n\nWAJIB bayar Deposit Rp 4.000.000 dalam 3 hari (jatuh tempo: 04 Jul 2026) di menu Pembayaran. Setelah deposit lunas, admin akan memproses persetujuan.', 'sewa', 0, '2026-07-01 22:56:27'),
(277, 1, 'Pengajuan Sewa Baru', 'Pengajuan sewa baru dari roger (Kamar No. 205). Tunggu user membayar deposit sebelum disetujui.', 'sewa', 1, '2026-07-01 22:56:27'),
(278, 10, 'Kontrak Sewa Sisa 3 Hari', 'Kontrak sewa kamar Anda (No. 105) akan berakhir dalam 3 hari lagi. Segera perpanjang.', 'kontrak', 0, '2026-07-02 17:34:00'),
(279, 7, 'Tagihan Terlambat - Denda Rp 620.000', 'Tagihan sewa kamar No. 101 (Bulan ke-1) sudah melewati jatuh tempo. Denda: Rp 620.000. Segera bayar!', 'tagihan', 0, '2026-07-02 17:34:00'),
(280, 7, 'Tagihan Terlambat - Denda Rp 310.000', 'Tagihan sewa kamar No. 101 (Bulan ke-2) sudah melewati jatuh tempo. Denda: Rp 310.000. Segera bayar!', 'tagihan', 0, '2026-07-02 17:34:00'),
(281, 18, 'Tagihan Terlambat - Denda Rp 20.000', 'Tagihan sewa kamar No. 202 (Bulan ke-0) sudah melewati jatuh tempo. Denda: Rp 20.000. Segera bayar!', 'tagihan', 0, '2026-07-02 17:34:00'),
(282, 21, '❌ Pengajuan Sewa Ditolak', 'Maaf, pengajuan sewa Anda ditolak. Alasan: Pengajuan sewa ditolak oleh admin.. Silakan pilih kamar lain yang tersedia. Terima kasih. 🙏', 'sewa', 0, '2026-07-02 17:51:30'),
(283, 1, 'Pendaftar Baru', 'Pengguna baru mendaftar: yelvi (HP: 08121234344). Belum mengajukan sewa.', 'user_baru', 1, '2026-07-02 19:14:16'),
(284, 22, 'Pengajuan Sewa Diterima', 'Pengajuan kamar No. 103 diterima. WAJIB bayar Deposit Rp 1.400.000 dalam 3 hari.', 'sewa', 1, '2026-07-02 19:16:12'),
(285, 1, 'Pengajuan Sewa Baru', 'yelvi mengajukan sewa kamar.', 'sewa', 1, '2026-07-02 19:16:12'),
(286, 22, 'Pembayaran Diterima', 'Bukti pembayaran Anda telah diupload untuk 1 tagihan. Admin akan verifikasi dalam 1x24 jam. Status tagihan akan berubah jadi \"Lunas\" setelah diverifikasi.', 'pembayaran', 1, '2026-07-02 19:16:44'),
(287, 1, 'Pembayaran Baru Perlu Verifikasi', 'Penghuni yelvi upload bukti pembayaran untuk 1 tagihan. Segera verifikasi di menu Pembayaran.', 'pembayaran', 1, '2026-07-02 19:16:44'),
(288, 22, '✅ Pembayaran Diverifikasi (Lunas)', 'Pembayaran Deposit sebesar Rp 1.400.000 telah DIVERIFIKASI dan berstatus LUNAS.\n\nCatatan admin: Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏\n\nTerima kasih telah membayar tepat waktu. - Admin Rumah Kos', 'pembayaran', 1, '2026-07-02 19:17:56'),
(289, 22, 'Pengajuan Sewa Disetujui!', 'Selamat! Pengajuan sewa Anda disetujui karena deposit sudah lunas. Kunci kamar No. 103 siap diambil di Office Rumah Kos.', 'sewa', 1, '2026-07-02 19:18:10'),
(291, 22, 'Kunci Telah Diambil', 'Kunci kamar Anda telah diterima. Selamat menempati kamar Anda. Jangan lupa menjaga kebersihan dan ketertiban kos.', 'info', 1, '2026-07-02 19:18:32'),
(292, 22, 'Pembayaran Diterima', 'Bukti pembayaran Anda telah diupload untuk 4 tagihan. Admin akan verifikasi dalam 1x24 jam. Status tagihan akan berubah jadi \"Lunas\" setelah diverifikasi.', 'pembayaran', 1, '2026-07-02 19:19:48'),
(293, 1, 'Pembayaran Baru Perlu Verifikasi', 'Penghuni yelvi upload bukti pembayaran untuk 4 tagihan. Segera verifikasi di menu Pembayaran.', 'pembayaran', 1, '2026-07-02 19:19:48'),
(294, 22, 'Keluhan Diterima: pebayaran ', 'Keluhan Anda diterima. Admin akan review & balas dalam 1x24 jam. Terima kasih.', 'keluhan', 1, '2026-07-02 19:21:24'),
(295, 1, 'Keluhan Baru (PENGHUNI)', '[PENGHUNI] pebayaran ', 'keluhan', 1, '2026-07-02 19:21:24'),
(296, 22, 'Keluhan Diperbarui: pebayaran ', 'Status keluhan Anda: Menunggu. Balasan admin: Sudah ditangani teknisi. Silakan cek kembali. Terima kasih.', 'keluhan', 1, '2026-07-02 19:22:21'),
(297, 22, '✅ Pembayaran Diverifikasi (Lunas)', 'Pembayaran 4 tagihan (Bulan 1, Bulan 2, Bulan 3, Bulan 4) sebesar Rp 2.800.000 telah DIVERIFIKASI dan berstatus LUNAS.\n\nCatatan admin: Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏\n\nTerima kasih telah membayar tepat waktu. - Admin Rumah Kos', 'pembayaran', 1, '2026-07-02 19:22:36'),
(298, 22, 'Perpanjangan Kontrak Berhasil', 'Kontrak sewa kamar Anda (No. 103) berhasil diperpanjang selama 3 bulan. Tanggal selesai baru: 03 Feb 2027. Tagihan untuk 3 bulan tambahan sudah dibuat, cek menu Pembayaran.', 'kontrak', 1, '2026-07-02 19:23:37'),
(299, 1, 'Perpanjangan Kontrak', 'yelvi memperpanjang kontrak kamar No. 103 selama 3 bulan. Tagihan baru otomatis dibuat.', 'kontrak', 1, '2026-07-02 19:23:37'),
(300, 22, 'Pengajuan Pindah Kamar Diterima', 'Pengajuan pindah kamar Anda diterima. Admin akan cek ketersediaan kamar tujuan & menyetujui dalam 1x24 jam. Mohon tunggu konfirmasi.\n\n📊 Info Deposit Kamar:\n• Deposit lama (kamar Anda sekarang): Rp 1.400.000\n• Deposit baru (kamar tujuan): Rp 1.600.000\n\n💰 ANDA WAJIB BAYAR SELISIH DEPOSIT: Rp 200.000\n   (karena kamar baru lebih mahal dari kamar lama)\n   Selisih ini akan jadi tagihan tambahan setelah pindah disetujui admin.\n\n⚠️ Catatan: Deposit lama bisa dipotong kalau kamar lama rusak/kotor saat inspeksi.', 'pindah', 1, '2026-07-02 19:25:06'),
(301, 1, 'Pengajuan Pindah Kamar Baru', 'yelvi mengajukan pindah kamar. Segera review di menu Pindah Kamar.', 'pindah', 1, '2026-07-02 19:25:06'),
(302, 22, '✅ Pindah Kamar Disetujui!', 'Pengajuan pindah kamar Anda disetujui! Anda sekarang di Kamar No. 208.\n\nHasil inspeksi kamar lama: Kamar lama dalam kondisi bersih dan terawat. Tidak ada kerusakan. Kunci lengkap.\n\nRincian Keuangan:\n• Deposit lama: Rp 1.400.000\n• Deposit dipindah: Rp 1.400.000\n• Wajib bayar selisih deposit: Rp 200.000\n\n⚠️ Kebijakan Pindah Pertengahan Bulan:\n• Sewa bulan berjalan di kamar lama dianggap HANGUS.\n• Anda wajib membayar sewa penuh untuk bulan berjalan di kamar baru: Rp 800.000\n\nKunci kamar baru siap diambil di Office. Kunci kamar lama harap dikembalikan. Terima kasih. 🙏', 'pindah', 1, '2026-07-02 19:26:32'),
(303, 22, 'Tagihan Jatuh Tempo Hari Ini', 'Anda memiliki 1 tagihan jatuh tempo HARI INI:\n• Deposit: Rp 800.000\n\nTotal: Rp 800.000\nSegera bayar di menu Pembayaran.', 'tagihan', 1, '2026-07-02 19:26:47'),
(304, 22, 'Pembayaran Diterima', 'Bukti pembayaran Anda telah diupload untuk 2 tagihan. Admin akan verifikasi dalam 1x24 jam. Status tagihan akan berubah jadi \"Lunas\" setelah diverifikasi.', 'pembayaran', 1, '2026-07-02 19:30:09'),
(305, 1, 'Pembayaran Baru Perlu Verifikasi', 'Penghuni yelvi upload bukti pembayaran untuk 2 tagihan. Segera verifikasi di menu Pembayaran.', 'pembayaran', 1, '2026-07-02 19:30:09'),
(306, 22, 'Kunci Telah Diambil', 'Kunci kamar Anda telah diterima. Selamat menempati kamar Anda. Jangan lupa menjaga kebersihan dan ketertiban kos.', 'info', 1, '2026-07-02 19:32:32'),
(307, 22, '✅ Pembayaran Diverifikasi (Lunas)', 'Pembayaran 2 tagihan (Deposit, Deposit) sebesar Rp 1.000.000 telah DIVERIFIKASI dan berstatus LUNAS.\n\nCatatan admin: Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏\n\nTerima kasih telah membayar tepat waktu. - Admin Rumah Kos', 'pembayaran', 1, '2026-07-02 19:32:55'),
(308, 22, 'Pengajuan Checkout Diterima', 'Pengajuan checkout Anda diterima. Admin akan proses & menjadwalkan inspeksi kamar.\n\nCatatan:\n- Anda TIDAK perlu melunasi semua tagihan untuk checkout.\n- Tagihan yang masih belum dibayar akan dibatalkan saat checkout disetujui.\n- Sisa sewa (bulan belum dihuni) + deposit (setelah potongan kerusakan) akan dikembalikan ke Anda.\n- ⚠️ Karena Anda checkout SEBELUM kontrak berakhir, deposit akan DIPOTONG 50% secara otomatis sesuai kebijakan early checkout.', 'checkout', 1, '2026-07-02 21:09:29'),
(309, 1, 'Pengajuan Checkout Baru', 'yelvi mengajukan checkout. Segera proses di menu Checkout untuk inspeksi kamar & pengembalian dana.', 'checkout', 1, '2026-07-02 21:09:29'),
(310, 22, '✅ Checkout Disetujui - Pengembalian Dana', 'Check-Out Disetujui! Anda telah resmi keluar dari Kamar No. 208.\n\n📊 Rincian Pengembalian Dana:\n• Lama Huni: 1 bulan (dari 8 bulan kontrak)\n• Refund Sisa Sewa (dari tagihan lunas): Rp 0\n• Deposit: Rp 1.600.000\n• Potongan Early Checkout (50% Deposit): -Rp 800.000\n• Refund Deposit: Rp 800.000\n\n💰 TOTAL DIKEMBALIKAN: Rp 800.000\n\nBukti transfer refund sudah diupload admin. Cek halaman Checkout Anda untuk download bukti.\n\nCatatan Inspeksi: Kamar dalam kondisi bersih dan terawat. Tidak ada kerusakan. Kunci lengkap.\n\nTerima kasih. 🙏', 'checkout', 1, '2026-07-02 21:11:36'),
(311, 22, 'Pengajuan Sewa Diterima', 'Pengajuan kamar No. 104 diterima. WAJIB bayar Deposit Rp 1.400.000 dalam 3 hari.', 'sewa', 1, '2026-07-02 21:14:28'),
(312, 1, 'Pengajuan Sewa Baru', 'yelvi mengajukan sewa kamar.', 'sewa', 1, '2026-07-02 21:14:28'),
(313, 22, 'Pembayaran Diterima', 'Bukti pembayaran Anda telah diupload untuk 1 tagihan. Admin akan verifikasi dalam 1x24 jam. Status tagihan akan berubah jadi \"Lunas\" setelah diverifikasi.', 'pembayaran', 1, '2026-07-02 21:14:45'),
(314, 1, 'Pembayaran Baru Perlu Verifikasi', 'Penghuni yelvi upload bukti pembayaran untuk 1 tagihan. Segera verifikasi di menu Pembayaran.', 'pembayaran', 1, '2026-07-02 21:14:45'),
(315, 22, '❌ Pengajuan Sewa Ditolak', 'Maaf, pengajuan sewa Anda ditolak. Alasan: Pengajuan sewa ditolak oleh admin.. Silakan pilih kamar lain yang tersedia. Terima kasih. 🙏', 'sewa', 1, '2026-07-02 21:18:04'),
(316, 22, '✅ Pembayaran Diverifikasi (Lunas)', 'Pembayaran Deposit sebesar Rp 1.400.000 telah DIVERIFIKASI dan berstatus LUNAS.\n\nCatatan admin: Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏\n\nTerima kasih telah membayar tepat waktu. - Admin Rumah Kos', 'pembayaran', 1, '2026-07-02 21:18:40'),
(317, 18, 'Pengajuan Checkout Diterima', 'Pengajuan checkout Anda diterima. Admin akan proses & menjadwalkan inspeksi kamar.\n\nCatatan:\n- Anda TIDAK perlu melunasi semua tagihan untuk checkout.\n- Tagihan yang masih belum dibayar akan dibatalkan saat checkout disetujui.\n- Sisa sewa (bulan belum dihuni) + deposit (setelah potongan kerusakan) akan dikembalikan ke Anda.\n- ⚠️ Karena Anda checkout SEBELUM kontrak berakhir, deposit akan DIPOTONG 50% secara otomatis sesuai kebijakan early checkout.', 'checkout', 0, '2026-07-02 21:36:04'),
(318, 1, 'Pengajuan Checkout Baru', 'yuki mengajukan checkout. Segera proses di menu Checkout untuk inspeksi kamar & pengembalian dana.', 'checkout', 1, '2026-07-02 21:36:04'),
(319, 18, '✅ Checkout Disetujui - Pengembalian Dana', 'Check-Out Disetujui! Anda telah resmi keluar dari Kamar No. 202.\n\n📊 Rincian Pengembalian Dana:\n• Lama Huni: 1 bulan (dari 6 bulan kontrak)\n• Refund Sisa Sewa (dari tagihan lunas): Rp 0\n• Deposit: Rp 1.200.000\n• Potongan Early Checkout (50% Deposit): -Rp 600.000\n• Refund Deposit: Rp 600.000\n\n💰 TOTAL DIKEMBALIKAN: Rp 600.000\n\nBukti transfer refund sudah diupload admin. Cek halaman Checkout Anda untuk download bukti.\n\nCatatan Inspeksi: Kamar dalam kondisi bersih dan terawat. Tidak ada kerusakan. Kunci lengkap.\n\nTerima kasih. 🙏', 'checkout', 0, '2026-07-02 21:36:55'),
(320, 1, 'Pendaftar Baru', 'Pengguna baru mendaftar: yuki (HP: 085261345612). Belum mengajukan sewa.', 'user_baru', 1, '2026-07-03 08:11:57'),
(321, 23, 'Pengajuan Sewa Diterima', 'Pengajuan kamar No. 202 diterima. WAJIB bayar Deposit Rp 1.200.000 dalam 3 hari.', 'sewa', 1, '2026-07-03 08:14:19'),
(322, 1, 'Pengajuan Sewa Baru', 'yuki mengajukan sewa kamar.', 'sewa', 1, '2026-07-03 08:14:19'),
(323, 10, 'Kontrak Sewa Sisa 2 Hari', 'Kontrak sewa kamar Anda (No. 105) akan berakhir dalam 2 hari lagi. Segera perpanjang.', 'kontrak', 0, '2026-07-03 08:15:02'),
(324, 7, 'Tagihan Terlambat - Denda Rp 630.000', 'Tagihan sewa kamar No. 101 (Bulan ke-1) sudah melewati jatuh tempo. Denda: Rp 630.000. Segera bayar!', 'tagihan', 0, '2026-07-03 08:15:02'),
(325, 7, 'Tagihan Terlambat - Denda Rp 320.000', 'Tagihan sewa kamar No. 101 (Bulan ke-2) sudah melewati jatuh tempo. Denda: Rp 320.000. Segera bayar!', 'tagihan', 0, '2026-07-03 08:15:02'),
(326, 23, 'Pembayaran Diterima', 'Bukti pembayaran Anda telah diupload untuk 1 tagihan. Admin akan verifikasi dalam 1x24 jam. Status tagihan akan berubah jadi \"Lunas\" setelah diverifikasi.', 'pembayaran', 1, '2026-07-03 08:19:13'),
(327, 1, 'Pembayaran Baru Perlu Verifikasi', 'Penghuni yuki upload bukti pembayaran untuk 1 tagihan. Segera verifikasi di menu Pembayaran.', 'pembayaran', 1, '2026-07-03 08:19:13'),
(328, 23, '✅ Pembayaran Diverifikasi (Lunas)', 'Pembayaran Deposit sebesar Rp 1.200.000 telah DIVERIFIKASI dan berstatus LUNAS.\n\nCatatan admin: Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏\n\nTerima kasih telah membayar tepat waktu. - Admin Rumah Kos', 'pembayaran', 1, '2026-07-03 08:24:53'),
(329, 23, '✅ Pembayaran Diverifikasi (Lunas)', 'Pembayaran Deposit sebesar Rp 1.200.000 telah DIVERIFIKASI dan berstatus LUNAS.\n\nCatatan admin: Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏\n\nTerima kasih telah membayar tepat waktu. - Admin Rumah Kos', 'pembayaran', 1, '2026-07-03 08:24:54'),
(330, 23, 'Pengajuan Sewa Disetujui!', 'Selamat! Pengajuan sewa Anda disetujui karena deposit sudah lunas. Kunci kamar No. 202 siap diambil di Office Rumah Kos.', 'sewa', 1, '2026-07-03 08:25:04'),
(332, 23, 'Kunci Telah Diambil', 'Kunci kamar Anda telah diterima. Selamat menempati kamar Anda. Jangan lupa menjaga kebersihan dan ketertiban kos.', 'info', 1, '2026-07-03 08:41:05'),
(333, 23, 'Pembayaran Diterima', 'Bukti pembayaran Anda telah diupload untuk 3 tagihan. Admin akan verifikasi dalam 1x24 jam. Status tagihan akan berubah jadi \"Lunas\" setelah diverifikasi.', 'pembayaran', 1, '2026-07-03 10:27:38'),
(334, 1, 'Pembayaran Baru Perlu Verifikasi', 'Penghuni yuki upload bukti pembayaran untuk 3 tagihan. Segera verifikasi di menu Pembayaran.', 'pembayaran', 1, '2026-07-03 10:27:38'),
(335, 23, '✅ Pembayaran Diverifikasi (Lunas)', 'Pembayaran 3 tagihan (Bulan 1, Bulan 2, Bulan 3) sebesar Rp 1.800.000 telah DIVERIFIKASI dan berstatus LUNAS.\n\nCatatan admin: Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏\n\nTerima kasih telah membayar tepat waktu. - Admin Rumah Kos', 'pembayaran', 1, '2026-07-03 10:28:43'),
(336, 23, '✅ Pembayaran Diverifikasi (Lunas)', 'Pembayaran 0 tagihan () sebesar Rp 0 telah DIVERIFIKASI dan berstatus LUNAS.\n\nCatatan admin: Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏\n\nTerima kasih telah membayar tepat waktu. - Admin Rumah Kos', 'pembayaran', 1, '2026-07-03 10:28:44'),
(337, 23, 'Perpanjangan Kontrak Berhasil', 'Kontrak sewa kamar Anda (No. 202) berhasil diperpanjang selama 3 bulan. Tanggal selesai baru: 04 Jan 2027. Tagihan untuk 3 bulan tambahan sudah dibuat, cek menu Pembayaran.', 'kontrak', 1, '2026-07-03 10:30:16'),
(338, 1, 'Perpanjangan Kontrak', 'yuki memperpanjang kontrak kamar No. 202 selama 3 bulan. Tagihan baru otomatis dibuat.', 'kontrak', 1, '2026-07-03 10:30:16'),
(339, 23, 'Pengajuan Pindah Kamar Diterima', 'Pengajuan pindah kamar Anda diterima. Admin akan cek ketersediaan kamar tujuan & menyetujui dalam 1x24 jam. Mohon tunggu konfirmasi.\n\n📊 Info Deposit Kamar:\n• Deposit lama (kamar Anda sekarang): Rp 1.200.000\n• Deposit baru (kamar tujuan): Rp 1.600.000\n\n💰 ANDA WAJIB BAYAR SELISIH DEPOSIT: Rp 400.000\n   (karena kamar baru lebih mahal dari kamar lama)\n   Selisih ini akan jadi tagihan tambahan setelah pindah disetujui admin.\n\n⚠️ Catatan: Deposit lama bisa dipotong kalau kamar lama rusak/kotor saat inspeksi.', 'pindah', 1, '2026-07-03 10:32:51'),
(340, 1, 'Pengajuan Pindah Kamar Baru', 'yuki mengajukan pindah kamar. Segera review di menu Pindah Kamar.', 'pindah', 1, '2026-07-03 10:32:51'),
(341, 23, '✅ Pindah Kamar Disetujui!', 'Pengajuan pindah kamar Anda disetujui! Anda sekarang di Kamar No. 207.\n\nHasil inspeksi kamar lama: Kamar lama dalam kondisi bersih dan terawat. Tidak ada kerusakan. Kunci lengkap.\n\nRincian Keuangan:\n• Deposit lama: Rp 1.200.000\n• Deposit dipindah: Rp 1.200.000\n• Wajib bayar selisih deposit: Rp 400.000\n\n✅ Kebijakan Pindah Kamar:\n• Tagihan bulan berjalan & bulan depan yang sudah Anda bayar di kamar lama DIPINDAHKAN ke sewa kamar baru (tidak hangus).\n• Anda TIDAK perlu membayar sewa penuh lagi untuk bulan berjalan di kamar baru.\n• Anda hanya wajib membayar SELISIH DEPOSIT (jika kamar baru lebih mahal).\n\nKunci kamar baru siap diambil di Office. Kunci kamar lama harap dikembalikan. Terima kasih. 🙏', 'pindah', 1, '2026-07-03 10:36:22'),
(342, 23, 'Pengajuan Checkout Diterima', 'Pengajuan checkout Anda diterima. Admin akan proses & menjadwalkan inspeksi kamar.\n\nCatatan:\n- Anda TIDAK perlu melunasi semua tagihan untuk checkout.\n- Tagihan yang masih belum dibayar akan dibatalkan saat checkout disetujui.\n- Sisa sewa (bulan belum dihuni) + deposit (setelah potongan kerusakan) akan dikembalikan ke Anda.\n- ⚠️ Karena Anda checkout SEBELUM kontrak berakhir, deposit akan DIPOTONG 50% secara otomatis sesuai kebijakan early checkout.', 'checkout', 0, '2026-07-03 15:00:19'),
(343, 1, 'Pengajuan Checkout Baru', 'yuki mengajukan checkout. Segera proses di menu Checkout untuk inspeksi kamar & pengembalian dana.', 'checkout', 1, '2026-07-03 15:00:19'),
(344, 23, '✅ Checkout Disetujui - Pengembalian Dana', 'Check-Out Disetujui! Anda telah resmi keluar dari Kamar No. 207.\n\n📊 Rincian Pengembalian Dana:\n• Lama Huni: 1 bulan (dari 7 bulan kontrak)\n• Refund Sisa Sewa (dari tagihan lunas): Rp 1.200.000\n• Deposit: Rp 1.600.000\n• Potongan Early Checkout (50% Deposit): -Rp 800.000\n• Refund Deposit: Rp 800.000\n\n💰 TOTAL DIKEMBALIKAN: Rp 2.000.000\n\nBukti transfer refund sudah diupload admin. Cek halaman Checkout Anda untuk download bukti.\n\nCatatan Inspeksi: Kamar dalam kondisi bersih dan terawat. Tidak ada kerusakan. Kunci lengkap.\n\nTerima kasih. 🙏', 'checkout', 0, '2026-07-03 15:01:02'),
(345, 1, 'Pendaftar Baru', 'Pengguna baru mendaftar: devi (HP: 085214147878). Belum mengajukan sewa.', 'user_baru', 1, '2026-07-03 15:01:52'),
(346, 24, 'Pengajuan Sewa Diterima', 'Pengajuan kamar No. 202 diterima. WAJIB bayar Deposit Rp 1.200.000 dalam 3 hari.', 'sewa', 1, '2026-07-03 15:05:49'),
(347, 1, 'Pengajuan Sewa Baru', 'devi mengajukan sewa kamar.', 'sewa', 1, '2026-07-03 15:05:49'),
(348, 24, '❌ Pengajuan Sewa Ditolak', 'Maaf, pengajuan sewa Anda ditolak. Alasan: Pengajuan sewa ditolak oleh admin.. Silakan pilih kamar lain yang tersedia. Terima kasih. 🙏', 'sewa', 1, '2026-07-03 15:09:08'),
(349, 24, 'Pengajuan Sewa Diterima', 'Pengajuan kamar No. 104 diterima. WAJIB bayar Deposit Rp 1.400.000 dalam 3 hari.', 'sewa', 1, '2026-07-03 15:09:46'),
(350, 1, 'Pengajuan Sewa Baru', 'devi mengajukan sewa kamar.', 'sewa', 1, '2026-07-03 15:09:46'),
(351, 24, '✅ Penolakan Sewa Dibatalkan', 'Permohonan maaf, penolakan pengajuan sewa Anda telah DIBATALKAN oleh admin (kemungkinan salah tekan). Status pengajuan Anda kembali ke MENUNGGU. Admin akan meninjau kembali dan menyetujui jika semua syarat terpenuhi. Mohon tunggu konfirmasi selanjutnya. Terima kasih. 🙏', 'sewa', 1, '2026-07-03 15:35:46'),
(352, 24, 'Pembayaran Diterima', 'Bukti pembayaran Anda telah diupload untuk 1 tagihan. Admin akan verifikasi dalam 1x24 jam. Status tagihan akan berubah jadi \"Lunas\" setelah diverifikasi.', 'pembayaran', 1, '2026-07-03 15:38:38'),
(353, 1, 'Pembayaran Baru Perlu Verifikasi', 'Penghuni devi upload bukti pembayaran untuk 1 tagihan. Segera verifikasi di menu Pembayaran.', 'pembayaran', 1, '2026-07-03 15:38:38'),
(354, 24, '✅ Pembayaran Diverifikasi (Lunas)', 'Pembayaran Deposit sebesar Rp 1.400.000 telah DIVERIFIKASI dan berstatus LUNAS.\n\nCatatan admin: Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏\n\nTerima kasih telah membayar tepat waktu. - Admin Rumah Kos', 'pembayaran', 1, '2026-07-03 15:39:03'),
(355, 24, '❌ Pengajuan Sewa Ditolak', 'Maaf, pengajuan sewa Anda ditolak. Alasan: Pengajuan sewa ditolak oleh admin.. Silakan pilih kamar lain yang tersedia. Terima kasih. 🙏', 'sewa', 1, '2026-07-03 15:41:28'),
(356, 24, '❌ Pengajuan Sewa Ditolak', 'Maaf, pengajuan sewa Anda ditolak. Alasan: Pengajuan sewa ditolak oleh admin.. Karena Anda sudah membayar Deposit sebesar Rp 1.400.000, admin akan menghubungi Anda untuk proses pengembalian dana. Terima kasih atas pengertiannya. 🙏', 'sewa', 0, '2026-07-03 15:43:36'),
(357, 1, '⚠️ Perlu Refund Deposit', 'Pengajuan sewa #37 ditolak, tapi user sudah bayar deposit Rp 1.400.000. Harap lakukan refund manual ke user.', 'info', 1, '2026-07-03 15:43:36'),
(358, 1, 'Pendaftar Baru', 'Pengguna baru mendaftar: devi (HP: 085246469795). Belum mengajukan sewa.', 'user_baru', 1, '2026-07-03 15:45:27'),
(359, 25, 'Pengajuan Sewa Diterima', 'Pengajuan kamar No. 104 diterima. WAJIB bayar Deposit Rp 1.400.000 dalam 3 hari.', 'sewa', 0, '2026-07-03 16:01:34'),
(360, 1, 'Pengajuan Sewa Baru', 'devi mengajukan sewa kamar.', 'sewa', 1, '2026-07-03 16:01:34'),
(361, 25, 'Pembayaran Diterima', 'Bukti pembayaran Anda telah diupload untuk 1 tagihan. Admin akan verifikasi dalam 1x24 jam. Status tagihan akan berubah jadi \"Lunas\" setelah diverifikasi.', 'pembayaran', 0, '2026-07-03 16:01:49'),
(362, 1, 'Pembayaran Baru Perlu Verifikasi', 'Penghuni devi upload bukti pembayaran untuk 1 tagihan. Segera verifikasi di menu Pembayaran.', 'pembayaran', 1, '2026-07-03 16:01:49'),
(363, 25, '✅ Pembayaran Diverifikasi (Lunas)', 'Pembayaran Deposit sebesar Rp 1.400.000 telah DIVERIFIKASI dan berstatus LUNAS.\n\nCatatan admin: Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏\n\nTerima kasih telah membayar tepat waktu. - Admin Rumah Kos', 'pembayaran', 0, '2026-07-03 16:02:17'),
(364, 25, '✅ Pembayaran Diverifikasi (Lunas)', 'Pembayaran Deposit sebesar Rp 1.400.000 telah DIVERIFIKASI dan berstatus LUNAS.\n\nCatatan admin: Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏\n\nTerima kasih telah membayar tepat waktu. - Admin Rumah Kos', 'pembayaran', 0, '2026-07-03 16:02:17'),
(365, 25, 'Pengajuan Sewa Disetujui!', 'Selamat! Pengajuan sewa Anda disetujui karena deposit sudah lunas. Kunci kamar No. 104 siap diambil di Office Rumah Kos.', 'sewa', 0, '2026-07-03 16:07:32'),
(367, 13, '✅ Penolakan Sewa Dibatalkan', 'Permohonan maaf, penolakan pengajuan sewa Anda telah DIBATALKAN oleh admin (kemungkinan salah tekan). Status pengajuan Anda kembali ke MENUNGGU. Admin akan meninjau kembali dan menyetujui jika semua syarat terpenuhi. Mohon tunggu konfirmasi selanjutnya. Terima kasih. 🙏', 'sewa', 1, '2026-07-03 16:47:29'),
(368, 13, '✅ Pembayaran Diverifikasi (Lunas)', 'Pembayaran Deposit sebesar Rp 1.200.000 telah DIVERIFIKASI dan berstatus LUNAS.\n\nCatatan admin: Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏\n\nTerima kasih telah membayar tepat waktu. - Admin Rumah Kos', 'pembayaran', 1, '2026-07-03 16:48:49'),
(369, 13, 'Pengajuan Sewa Disetujui!', 'Selamat! Pengajuan sewa Anda disetujui karena deposit sudah lunas. Kunci kamar No. 202 siap diambil di Office Rumah Kos.', 'sewa', 1, '2026-07-03 16:49:01'),
(371, 13, 'Pembayaran Diterima', 'Bukti pembayaran Anda telah diupload untuk 6 tagihan. Admin akan verifikasi dalam 1x24 jam. Status tagihan akan berubah jadi \"Lunas\" setelah diverifikasi.', 'pembayaran', 1, '2026-07-03 17:18:27'),
(372, 1, 'Pembayaran Baru Perlu Verifikasi', 'Penghuni jovan upload bukti pembayaran untuk 6 tagihan. Segera verifikasi di menu Pembayaran.', 'pembayaran', 1, '2026-07-03 17:18:27'),
(373, 13, '✅ Pembayaran Diverifikasi (Lunas)', 'Pembayaran 6 tagihan (Bulan 1, Bulan 2, Bulan 3, Bulan 4, Bulan 5, Bulan 6) sebesar Rp 3.600.000 telah DIVERIFIKASI dan berstatus LUNAS.\n\nCatatan admin: Sewa bulan ke-6\n\nTerima kasih telah membayar tepat waktu. - Admin Rumah Kos', 'pembayaran', 1, '2026-07-03 17:19:00'),
(374, 13, 'Perpanjangan Kontrak Berhasil', 'Kontrak sewa kamar Anda (No. 202) berhasil diperpanjang selama 2 bulan. Tanggal selesai baru: 03 Mar 2027. Tagihan untuk 2 bulan tambahan sudah dibuat, cek menu Pembayaran.', 'kontrak', 0, '2026-07-03 17:19:34'),
(375, 1, 'Perpanjangan Kontrak', 'jovan memperpanjang kontrak kamar No. 202 selama 2 bulan. Tagihan baru otomatis dibuat.', 'kontrak', 1, '2026-07-03 17:19:34'),
(376, 13, 'Pengajuan Pindah Kamar Diterima', 'Pengajuan pindah kamar Anda diterima. Admin akan cek ketersediaan kamar tujuan & menyetujui dalam 1x24 jam. Mohon tunggu konfirmasi.\n\n📊 Info Deposit Kamar:\n• Deposit lama (kamar Anda sekarang): Rp 1.200.000\n• Deposit baru (kamar tujuan): Rp 1.400.000\n\n💰 ANDA WAJIB BAYAR SELISIH DEPOSIT: Rp 200.000\n   (karena kamar baru lebih mahal dari kamar lama)\n   Selisih ini akan jadi tagihan tambahan setelah pindah disetujui admin.\n\n⚠️ Catatan: Deposit lama bisa dipotong kalau kamar lama rusak/kotor saat inspeksi.', 'pindah', 1, '2026-07-03 17:20:22'),
(377, 1, 'Pengajuan Pindah Kamar Baru', 'jovan mengajukan pindah kamar. Segera review di menu Pindah Kamar.', 'pindah', 1, '2026-07-03 17:20:22'),
(378, 25, 'Kunci Telah Diambil', 'Kunci kamar Anda telah diterima. Selamat menempati kamar Anda. Jangan lupa menjaga kebersihan dan ketertiban kos.', 'info', 0, '2026-07-03 17:21:00'),
(379, 13, 'Kunci Telah Diambil', 'Kunci kamar Anda telah diterima. Selamat menempati kamar Anda. Jangan lupa menjaga kebersihan dan ketertiban kos.', 'info', 0, '2026-07-03 17:21:24'),
(380, 13, '✅ Pindah Kamar Disetujui!', 'Pengajuan pindah kamar Anda disetujui! Anda sekarang di Kamar No. 103.\n\nHasil inspeksi kamar lama: Kamar dalam kondisi cukup bersih. Terdapat goresan pada dinding. Kunci lengkap.\n\nRincian Keuangan:\n• Deposit lama: Rp 1.200.000\n• Potongan kerusakan: -Rp 10.000\n• Deposit dipindah: Rp 1.190.000\n• Wajib bayar selisih deposit: Rp 210.000\n\n✅ Kebijakan Pindah Kamar:\n• Tagihan bulan berjalan & bulan depan yang sudah Anda bayar di kamar lama DIPINDAHKAN ke sewa kamar baru (tidak hangus).\n• Anda TIDAK perlu membayar sewa penuh lagi untuk bulan berjalan di kamar baru.\n• Anda hanya wajib membayar SELISIH DEPOSIT (jika kamar baru lebih mahal).\n\nKunci kamar baru siap diambil di Office. Kunci kamar lama harap dikembalikan. Terima kasih. 🙏', 'pindah', 1, '2026-07-03 17:22:34'),
(381, 13, 'Kunci Telah Diambil', 'Kunci kamar Anda telah diterima. Selamat menempati kamar Anda. Jangan lupa menjaga kebersihan dan ketertiban kos.', 'info', 1, '2026-07-03 17:23:38'),
(382, 13, 'Pengajuan Checkout Diterima', 'Pengajuan checkout Anda diterima. Admin akan proses & menjadwalkan inspeksi kamar.\n\nCatatan:\n- Anda TIDAK perlu melunasi semua tagihan untuk checkout.\n- Tagihan yang masih belum dibayar akan dibatalkan saat checkout disetujui.\n- Sisa sewa (bulan belum dihuni) + deposit (setelah potongan kerusakan) akan dikembalikan ke Anda.\n- ⚠️ Karena Anda checkout SEBELUM kontrak berakhir, deposit akan DIPOTONG 50% secara otomatis sesuai kebijakan early checkout.', 'checkout', 0, '2026-07-03 17:24:32'),
(383, 1, 'Pengajuan Checkout Baru', 'jovan mengajukan checkout. Segera proses di menu Checkout untuk inspeksi kamar & pengembalian dana.', 'checkout', 1, '2026-07-03 17:24:32'),
(384, 13, '❌ Check-Out Ditolak', 'Pengajuan check-out Anda ditolak. Alasan: Pengajuan check-out ditolak oleh admin.. Hubungi admin untuk informasi lebih lanjut.', 'checkout', 1, '2026-07-03 17:42:22'),
(385, 13, 'Pengajuan Checkout Diterima', 'Pengajuan checkout Anda diterima. Admin akan proses & menjadwalkan inspeksi kamar.\n\nCatatan:\n- Anda TIDAK perlu melunasi semua tagihan untuk checkout.\n- Tagihan yang masih belum dibayar akan dibatalkan saat checkout disetujui.\n- Sisa sewa (bulan belum dihuni) + deposit (setelah potongan kerusakan) akan dikembalikan ke Anda.\n- ⚠️ Karena Anda checkout SEBELUM kontrak berakhir, deposit akan DIPOTONG 50% secara otomatis sesuai kebijakan early checkout.', 'checkout', 0, '2026-07-03 17:43:27'),
(386, 1, 'Pengajuan Checkout Baru', 'jovan mengajukan checkout. Segera proses di menu Checkout untuk inspeksi kamar & pengembalian dana.', 'checkout', 1, '2026-07-03 17:43:27'),
(387, 13, '✅ Checkout Disetujui - Pengembalian Dana', 'Check-Out Disetujui! Anda telah resmi keluar dari Kamar No. 103.\n\n📊 Rincian Pengembalian Dana:\n• Lama Huni: 1 bulan (dari 8 bulan kontrak)\n• Refund Sisa Sewa (dari tagihan lunas): Rp 3.000.000\n• Deposit: Rp 1.400.000\n• Potongan Early Checkout (50% Deposit): -Rp 700.000\n• Refund Deposit: Rp 700.000\n\n💰 TOTAL DIKEMBALIKAN: Rp 3.700.000\n\nBukti transfer refund sudah diupload admin. Cek halaman Checkout Anda untuk download bukti.\n\nCatatan Inspeksi: Kamar dalam kondisi bersih dan terawat. Tidak ada kerusakan. Kunci lengkap.\n\nTerima kasih. 🙏', 'checkout', 0, '2026-07-03 17:44:36'),
(388, 12, 'Keluhan Diterima: Pengumuman Gangguan Wi-Fi', 'Keluhan Wi-Fi Anda diterima. Admin akan restart router/cek koneksi. Estimasi normal: 1-2 jam.', 'keluhan', 1, '2026-07-03 17:52:21'),
(389, 1, 'Keluhan Baru (PENGHUNI)', '[PENGHUNI] Pengumuman Gangguan Wi-Fi', 'keluhan', 1, '2026-07-03 17:52:21'),
(390, 12, 'Keluhan Diperbarui: Pengumuman Gangguan Wi-Fi', 'Status keluhan Anda: Menunggu. Balasan admin: Mohon maaf atas ketidaknyamanan. Akan segera kami perbaiki.', 'keluhan', 1, '2026-07-03 17:52:57'),
(391, 12, 'Keluhan Diperbarui: Pengumuman Gangguan Wi-Fi', 'Status keluhan Anda: Menunggu. Balasan admin: Sedang dalam proses penanganan. Mohon ditunggu 1x24 jam.', 'keluhan', 1, '2026-07-03 17:53:48'),
(392, 12, 'Keluhan Diperbarui: Pengumuman Gangguan Wi-Fi', 'Status keluhan Anda: Selesai. Balasan admin: Sudah ditangani teknisi. Silakan cek kembali. Terima kasih.', 'keluhan', 1, '2026-07-03 17:54:26'),
(393, 12, 'Keluhan Diperbarui: Pengumuman Gangguan Wi-Fi', 'Status keluhan Anda: Selesai. Balasan admin: Sudah ditangani teknisi. Silakan cek kembali. Terima kasih.', 'keluhan', 0, '2026-07-03 17:54:59'),
(394, 12, 'Pengajuan Pindah Kamar Diterima', 'Pengajuan pindah kamar Anda diterima. Admin akan cek ketersediaan kamar tujuan & menyetujui dalam 1x24 jam. Mohon tunggu konfirmasi.\n\n📊 Info Deposit Kamar:\n• Deposit lama (kamar Anda sekarang): Rp 1.500.000\n• Deposit baru (kamar tujuan): Rp 1.200.000\n\n💰 ANDA DAPAT UANG KEMBALIAN: Rp 300.000\n   (karena kamar baru lebih murah dari kamar lama)\n   Uang kembalian akan ditransfer admin setelah pindah disetujui.\n\n⚠️ Catatan: Deposit lama bisa dipotong kalau kamar lama rusak/kotor saat inspeksi.', 'pindah', 0, '2026-07-03 17:56:16'),
(395, 1, 'Pengajuan Pindah Kamar Baru', 'diki mengajukan pindah kamar. Segera review di menu Pindah Kamar.', 'pindah', 1, '2026-07-03 17:56:16'),
(396, 12, '✅ Pindah Kamar Disetujui!', 'Pengajuan pindah kamar Anda disetujui! Anda sekarang di Kamar No. 202.\n\nHasil inspeksi kamar lama: Kamar lama dalam kondisi bersih dan terawat. Tidak ada kerusakan. Kunci lengkap.\n\nRincian Keuangan:\n• Deposit lama: Rp 1.500.000\n• Deposit dipindah: Rp 1.500.000\n• Uang kembalian (selisih deposit): Rp 300.000\n\n✅ Bukti transfer refund sudah diupload admin. Cek halaman Pindah Kamar Anda untuk download bukti.\n\n🔑 Kunci kamar baru siap diambil di Office Rumah Kos (Jam 08:00-17:00 WIB).\n\n✅ Kebijakan Pindah Kamar:\n• Tagihan bulan berjalan & bulan depan yang sudah Anda bayar di kamar lama DIPINDAHKAN ke sewa kamar baru (tidak hangus).\n• Anda TIDAK perlu membayar sewa penuh lagi untuk bulan berjalan di kamar baru.\n\nKunci kamar lama harap dikembalikan. Terima kasih. 🙏', 'pindah', 0, '2026-07-03 17:57:05'),
(397, 12, 'Kunci Telah Diambil', 'Kunci kamar Anda telah diterima. Selamat menempati kamar Anda. Jangan lupa menjaga kebersihan dan ketertiban kos.', 'info', 0, '2026-07-03 17:59:35'),
(398, 4, 'Pengumuman: Pengumuman Pemadaman Air', 'Diberitahukan kepada seluruh penghuni kos bahwa akan ada pemadaman air sementara.\r\n\r\nMohon maaf atas ketidaknyamanannya. Pastikan Anda sudah menampung', 'pengumuman', 1, '2026-07-03 18:05:58'),
(399, 5, 'Pengumuman: Pengumuman Pemadaman Air', 'Diberitahukan kepada seluruh penghuni kos bahwa akan ada pemadaman air sementara.\r\n\r\nMohon maaf atas ketidaknyamanannya. Pastikan Anda sudah menampung', 'pengumuman', 0, '2026-07-03 18:05:58'),
(400, 7, 'Pengumuman: Pengumuman Pemadaman Air', 'Diberitahukan kepada seluruh penghuni kos bahwa akan ada pemadaman air sementara.\r\n\r\nMohon maaf atas ketidaknyamanannya. Pastikan Anda sudah menampung', 'pengumuman', 0, '2026-07-03 18:05:58'),
(401, 9, 'Pengumuman: Pengumuman Pemadaman Air', 'Diberitahukan kepada seluruh penghuni kos bahwa akan ada pemadaman air sementara.\r\n\r\nMohon maaf atas ketidaknyamanannya. Pastikan Anda sudah menampung', 'pengumuman', 0, '2026-07-03 18:05:58'),
(402, 10, 'Pengumuman: Pengumuman Pemadaman Air', 'Diberitahukan kepada seluruh penghuni kos bahwa akan ada pemadaman air sementara.\r\n\r\nMohon maaf atas ketidaknyamanannya. Pastikan Anda sudah menampung', 'pengumuman', 0, '2026-07-03 18:05:58'),
(403, 12, 'Pengumuman: Pengumuman Pemadaman Air', 'Diberitahukan kepada seluruh penghuni kos bahwa akan ada pemadaman air sementara.\r\n\r\nMohon maaf atas ketidaknyamanannya. Pastikan Anda sudah menampung', 'pengumuman', 0, '2026-07-03 18:05:58'),
(404, 17, 'Pengumuman: Pengumuman Pemadaman Air', 'Diberitahukan kepada seluruh penghuni kos bahwa akan ada pemadaman air sementara.\r\n\r\nMohon maaf atas ketidaknyamanannya. Pastikan Anda sudah menampung', 'pengumuman', 0, '2026-07-03 18:05:58'),
(405, 25, 'Pengumuman: Pengumuman Pemadaman Air', 'Diberitahukan kepada seluruh penghuni kos bahwa akan ada pemadaman air sementara.\r\n\r\nMohon maaf atas ketidaknyamanannya. Pastikan Anda sudah menampung', 'pengumuman', 0, '2026-07-03 18:05:58'),
(406, 1, 'Pendaftar Baru', 'Pengguna baru mendaftar: roger (HP: 08121314561). Belum mengajukan sewa.', 'user_baru', 1, '2026-07-05 13:55:48'),
(407, 26, 'Pengajuan Sewa Diterima', 'Pengajuan kamar No. 205 diterima. WAJIB bayar Deposit Rp 4.000.000 dalam 3 hari.', 'sewa', 1, '2026-07-05 13:57:44'),
(408, 1, 'Pengajuan Sewa Baru', 'roger mengajukan sewa kamar.', 'sewa', 1, '2026-07-05 13:57:44'),
(409, 10, 'Kontrak Sewa Sisa 0 Hari', 'Kontrak sewa kamar Anda (No. 105) berakhir HARI INI.', 'kontrak', 0, '2026-07-05 13:58:12'),
(410, 7, 'Tagihan Terlambat - Denda Rp 630.000', 'Tagihan sewa kamar No. 101 (Bulan ke-1) sudah melewati jatuh tempo. Denda: Rp 630.000. Segera bayar!', 'tagihan', 0, '2026-07-05 13:58:12'),
(411, 7, 'Tagihan Terlambat - Denda Rp 320.000', 'Tagihan sewa kamar No. 101 (Bulan ke-2) sudah melewati jatuh tempo. Denda: Rp 320.000. Segera bayar!', 'tagihan', 0, '2026-07-05 13:58:12'),
(412, 26, 'Pembayaran Diterima', 'Bukti pembayaran Anda telah diupload untuk 1 tagihan. Admin akan verifikasi dalam 1x24 jam. Status tagihan akan berubah jadi \"Lunas\" setelah diverifikasi.', 'pembayaran', 1, '2026-07-05 13:59:05'),
(413, 1, 'Pembayaran Baru Perlu Verifikasi', 'Penghuni roger upload bukti pembayaran untuk 1 tagihan. Segera verifikasi di menu Pembayaran.', 'pembayaran', 1, '2026-07-05 13:59:05'),
(414, 26, '✅ Pembayaran Diverifikasi (Lunas)', 'Pembayaran Deposit sebesar Rp 4.000.000 telah DIVERIFIKASI dan berstatus LUNAS.\n\nCatatan admin: Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏\n\nTerima kasih telah membayar tepat waktu. - Admin Rumah Kos', 'pembayaran', 1, '2026-07-05 13:59:29'),
(415, 26, 'Pengajuan Sewa Disetujui!', 'Selamat! Pengajuan sewa Anda disetujui karena deposit sudah lunas. Kunci kamar No. 205 siap diambil di Office Rumah Kos.', 'sewa', 1, '2026-07-05 13:59:58'),
(417, 26, 'Kunci Telah Diambil', 'Kunci kamar Anda telah diterima. Selamat menempati kamar Anda. Jangan lupa menjaga kebersihan dan ketertiban kos.', 'info', 1, '2026-07-05 14:01:06'),
(418, 26, 'Pembayaran Diterima', 'Bukti pembayaran Anda telah diupload untuk 3 tagihan. Admin akan verifikasi dalam 1x24 jam. Status tagihan akan berubah jadi \"Lunas\" setelah diverifikasi.', 'pembayaran', 1, '2026-07-05 14:01:49'),
(419, 1, 'Pembayaran Baru Perlu Verifikasi', 'Penghuni roger upload bukti pembayaran untuk 3 tagihan. Segera verifikasi di menu Pembayaran.', 'pembayaran', 1, '2026-07-05 14:01:49'),
(420, 26, '✅ Pembayaran Diverifikasi (Lunas)', 'Pembayaran 3 tagihan (Bulan 1, Bulan 2, Bulan 3) sebesar Rp 6.000.000 telah DIVERIFIKASI dan berstatus LUNAS.\n\nCatatan admin: Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏\n\nTerima kasih telah membayar tepat waktu. - Admin Rumah Kos', 'pembayaran', 1, '2026-07-05 14:02:23'),
(421, 26, 'Perpanjangan Kontrak Berhasil', 'Kontrak sewa kamar Anda (No. 205) berhasil diperpanjang selama 3 bulan. Tanggal selesai baru: 05 Jan 2027. Tagihan untuk 3 bulan tambahan sudah dibuat, cek menu Pembayaran.', 'kontrak', 1, '2026-07-05 14:03:05'),
(422, 1, 'Perpanjangan Kontrak', 'roger memperpanjang kontrak kamar No. 205 selama 3 bulan. Tagihan baru otomatis dibuat.', 'kontrak', 1, '2026-07-05 14:03:05'),
(423, 26, 'Pengajuan Pindah Kamar Diterima', 'Pengajuan pindah kamar Anda diterima. Admin akan cek ketersediaan kamar tujuan & menyetujui dalam 1x24 jam. Mohon tunggu konfirmasi.\n\n📊 Info Deposit Kamar:\n• Deposit lama (kamar Anda sekarang): Rp 4.000.000\n• Deposit baru (kamar tujuan): Rp 3.000.000\n\n💰 ANDA DAPAT UANG KEMBALIAN: Rp 1.000.000\n   (karena kamar baru lebih murah dari kamar lama)\n   Uang kembalian akan ditransfer admin setelah pindah disetujui.\n\n⚠️ Catatan: Deposit lama bisa dipotong kalau kamar lama rusak/kotor saat inspeksi.', 'pindah', 1, '2026-07-05 14:07:48'),
(424, 1, 'Pengajuan Pindah Kamar Baru', 'roger mengajukan pindah kamar. Segera review di menu Pindah Kamar.', 'pindah', 1, '2026-07-05 14:07:48'),
(425, 26, '✅ Pindah Kamar Disetujui!', 'Pengajuan pindah kamar Anda disetujui! Anda sekarang di Kamar No. 210.\n\nHasil inspeksi kamar lama: Kamar lama dalam kondisi bersih dan terawat. Tidak ada kerusakan. Kunci lengkap.\n\nRincian Keuangan:\n• Deposit lama: Rp 4.000.000\n• Deposit dipindah: Rp 4.000.000\n• Uang kembalian (selisih deposit): Rp 1.000.000\n\n✅ Bukti transfer refund sudah diupload admin. Cek halaman Pindah Kamar Anda untuk download bukti.\n\n🔑 Kunci kamar baru siap diambil di Office Rumah Kos (Jam 08:00-17:00 WIB).\n\n✅ Kebijakan Pindah Kamar:\n• Tagihan bulan berjalan & bulan depan yang sudah Anda bayar di kamar lama DIPINDAHKAN ke sewa kamar baru (tidak hangus).\n• Anda TIDAK perlu membayar sewa penuh lagi untuk bulan berjalan di kamar baru.\n\nKunci kamar lama harap dikembalikan. Terima kasih. 🙏', 'pindah', 1, '2026-07-05 14:08:40'),
(426, 26, 'Pengajuan Checkout Diterima', 'Pengajuan checkout Anda diterima. Admin akan proses & menjadwalkan inspeksi kamar.\n\nCatatan:\n- Anda TIDAK perlu melunasi semua tagihan untuk checkout.\n- Tagihan yang masih belum dibayar akan dibatalkan saat checkout disetujui.\n- Sisa sewa (bulan belum dihuni) + deposit (setelah potongan kerusakan) akan dikembalikan ke Anda.\n- ⚠️ Karena Anda checkout SEBELUM kontrak berakhir, deposit akan DIPOTONG 50% secara otomatis sesuai kebijakan early checkout.', 'checkout', 1, '2026-07-05 14:11:01'),
(427, 1, 'Pengajuan Checkout Baru', 'roger mengajukan checkout. Segera proses di menu Checkout untuk inspeksi kamar & pengembalian dana.', 'checkout', 1, '2026-07-05 14:11:01'),
(428, 26, '❌ Check-Out Ditolak', 'Pengajuan check-out Anda ditolak. Alasan: Pengajuan check-out ditolak oleh admin.. Hubungi admin untuk informasi lebih lanjut.', 'checkout', 1, '2026-07-05 14:12:50'),
(429, 26, 'Kunci Telah Diambil', 'Kunci kamar Anda telah diterima. Selamat menempati kamar Anda. Jangan lupa menjaga kebersihan dan ketertiban kos.', 'info', 1, '2026-07-05 14:13:36'),
(430, 26, 'Pengajuan Checkout Diterima', 'Pengajuan checkout Anda diterima. Admin akan proses & menjadwalkan inspeksi kamar.\n\nCatatan:\n- Anda TIDAK perlu melunasi semua tagihan untuk checkout.\n- Tagihan yang masih belum dibayar akan dibatalkan saat checkout disetujui.\n- Sisa sewa (bulan belum dihuni) + deposit (setelah potongan kerusakan) akan dikembalikan ke Anda.\n- ⚠️ Karena Anda checkout SEBELUM kontrak berakhir, deposit akan DIPOTONG 50% secara otomatis sesuai kebijakan early checkout.', 'checkout', 1, '2026-07-05 14:15:55'),
(431, 1, 'Pengajuan Checkout Baru', 'roger mengajukan checkout. Segera proses di menu Checkout untuk inspeksi kamar & pengembalian dana.', 'checkout', 1, '2026-07-05 14:15:55'),
(432, 26, '❌ Check-Out Ditolak', 'Pengajuan check-out Anda ditolak. Alasan: Pengajuan check-out ditolak oleh admin.. Hubungi admin untuk informasi lebih lanjut.', 'checkout', 1, '2026-07-05 14:16:53'),
(433, 26, 'Pengajuan Checkout Diterima', 'Pengajuan checkout Anda diterima. Admin akan proses & menjadwalkan inspeksi kamar.\n\nCatatan:\n- Anda TIDAK perlu melunasi semua tagihan untuk checkout.\n- Tagihan yang masih belum dibayar akan dibatalkan saat checkout disetujui.\n- Sisa sewa (bulan belum dihuni) + deposit (setelah potongan kerusakan) akan dikembalikan ke Anda.\n- ⚠️ Karena Anda checkout SEBELUM kontrak berakhir, deposit akan DIPOTONG 50% secara otomatis sesuai kebijakan early checkout.', 'checkout', 1, '2026-07-05 14:18:29'),
(434, 1, 'Pengajuan Checkout Baru', 'roger mengajukan checkout. Segera proses di menu Checkout untuk inspeksi kamar & pengembalian dana.', 'checkout', 1, '2026-07-05 14:18:29');
INSERT INTO `notifikasi` (`id_notifikasi`, `id_user`, `judul`, `pesan`, `tipe`, `dibaca`, `created_at`) VALUES
(435, 26, '✅ Checkout Disetujui - Pengembalian Dana', 'Check-Out Disetujui! Anda telah resmi keluar dari Kamar No. 210.\n\n📊 Rincian Pengembalian Dana:\n• Lama Huni: 1 bulan (dari 6 bulan kontrak)\n• Refund Sisa Sewa (dari tagihan lunas): Rp 4.000.000\n• Deposit: Rp 3.000.000\n• Potongan Early Checkout (50% Deposit): -Rp 1.500.000\n• Potongan Kerusakan: -Rp 100.000\n• Refund Deposit: Rp 1.400.000\n\n💰 TOTAL DIKEMBALIKAN: Rp 5.400.000\n\nBukti transfer refund sudah diupload admin. Cek halaman Checkout Anda untuk download bukti.\n\nCatatan Inspeksi: Kamar kotor dan tidak terawat. Terdapat kerusakan pada kaca jendela. Kunci lengkap.\n\nTerima kasih. 🙏', 'checkout', 1, '2026-07-05 14:19:21'),
(436, 26, 'Pengajuan Sewa Diterima', 'Pengajuan kamar No. 205 diterima. WAJIB bayar Deposit Rp 4.000.000 dalam 3 hari.', 'sewa', 0, '2026-07-05 21:14:30'),
(437, 1, 'Pengajuan Sewa Baru', 'roger mengajukan sewa kamar.', 'sewa', 1, '2026-07-05 21:14:30'),
(438, 7, 'Tagihan Terlambat - Denda Rp 630.000', 'Tagihan sewa kamar No. 101 (Bulan ke-1) sudah melewati jatuh tempo. Denda: Rp 630.000. Segera bayar!', 'tagihan', 0, '2026-07-06 10:08:12'),
(439, 7, 'Tagihan Terlambat - Denda Rp 320.000', 'Tagihan sewa kamar No. 101 (Bulan ke-2) sudah melewati jatuh tempo. Denda: Rp 320.000. Segera bayar!', 'tagihan', 0, '2026-07-06 10:08:12'),
(440, 26, '❌ Pengajuan Sewa Ditolak', 'Maaf, pengajuan sewa Anda ditolak. Alasan: Pengajuan sewa ditolak oleh admin.. Silakan pilih kamar lain yang tersedia. Terima kasih. 🙏', 'sewa', 0, '2026-07-06 10:08:50'),
(441, 1, 'Pendaftar Baru', 'Pengguna baru mendaftar: roger (HP: 085412361547). Belum mengajukan sewa.', 'user_baru', 1, '2026-07-06 10:10:16'),
(442, 27, 'Pengajuan Sewa Diterima', 'Pengajuan kamar No. 205 diterima. WAJIB bayar Deposit Rp 4.000.000 dalam 3 hari.', 'sewa', 1, '2026-07-06 10:11:44'),
(443, 1, 'Pengajuan Sewa Baru', 'roger mengajukan sewa kamar.', 'sewa', 1, '2026-07-06 10:11:44'),
(444, 27, 'Pembayaran Diterima', 'Bukti pembayaran Anda telah diupload untuk 1 tagihan. Admin akan verifikasi dalam 1x24 jam. Status tagihan akan berubah jadi \"Lunas\" setelah diverifikasi.', 'pembayaran', 1, '2026-07-06 10:13:00'),
(445, 1, 'Pembayaran Baru Perlu Verifikasi', 'Penghuni roger upload bukti pembayaran untuk 1 tagihan. Segera verifikasi di menu Pembayaran.', 'pembayaran', 1, '2026-07-06 10:13:00'),
(446, 27, '❌ Pengajuan Sewa Ditolak', 'Maaf, pengajuan sewa Anda ditolak. Alasan: Pengajuan sewa ditolak oleh admin.. Silakan pilih kamar lain yang tersedia. Terima kasih. 🙏', 'sewa', 1, '2026-07-06 10:13:58'),
(447, 27, '✅ Penolakan Sewa Dibatalkan', 'Permohonan maaf, penolakan pengajuan sewa Anda telah DIBATALKAN oleh admin (kemungkinan salah tekan). Status pengajuan Anda kembali ke MENUNGGU. Admin akan meninjau kembali dan menyetujui jika semua syarat terpenuhi. Mohon tunggu konfirmasi selanjutnya. Terima kasih. 🙏', 'sewa', 1, '2026-07-06 10:14:02'),
(448, 27, '❌ Pengajuan Sewa Ditolak', 'Maaf, pengajuan sewa Anda ditolak. Alasan: Pengajuan sewa ditolak oleh admin.. Silakan pilih kamar lain yang tersedia. Terima kasih. 🙏', 'sewa', 1, '2026-07-06 10:46:10'),
(449, 27, 'Pengajuan Sewa Diterima', 'Pengajuan kamar No. 205 diterima. WAJIB bayar Deposit Rp 4.000.000 dalam 3 hari.', 'sewa', 1, '2026-07-06 10:46:42'),
(450, 1, 'Pengajuan Sewa Baru', 'roger mengajukan sewa kamar.', 'sewa', 1, '2026-07-06 10:46:42'),
(451, 27, '❌ Pengajuan Sewa Ditolak', 'Maaf, pengajuan sewa Anda ditolak. Alasan: Pengajuan sewa ditolak oleh admin.. Silakan pilih kamar lain yang tersedia. Terima kasih. 🙏', 'sewa', 0, '2026-07-06 11:08:24'),
(452, 1, 'Pendaftar Baru', 'Pengguna baru mendaftar: roger (HP: 085412361547). Belum mengajukan sewa.', 'user_baru', 1, '2026-07-06 11:09:14'),
(453, 1, 'Pendaftar Baru', 'Pengguna baru mendaftar: roger (HP: 085412361547). Belum mengajukan sewa.', 'user_baru', 1, '2026-07-06 11:38:35'),
(454, 29, 'Pengajuan Sewa Diterima', 'Pengajuan kamar No. 207 diterima. WAJIB bayar Deposit Rp 1.600.000 dalam 3 hari.', 'sewa', 1, '2026-07-06 13:49:28'),
(455, 1, 'Pengajuan Sewa Baru', 'roger mengajukan sewa kamar.', 'sewa', 1, '2026-07-06 13:49:28'),
(456, 29, 'Pembayaran Diterima', 'Bukti pembayaran Anda telah diupload untuk 1 tagihan. Admin akan verifikasi dalam 1x24 jam. Status tagihan akan berubah jadi \"Lunas\" setelah diverifikasi.', 'pembayaran', 1, '2026-07-06 13:50:19'),
(457, 1, 'Pembayaran Baru Perlu Verifikasi', 'Penghuni roger upload bukti pembayaran untuk 1 tagihan. Segera verifikasi di menu Pembayaran.', 'pembayaran', 1, '2026-07-06 13:50:19'),
(458, 29, '✅ Pembayaran Diverifikasi (Lunas)', 'Pembayaran Deposit sebesar Rp 1.600.000 telah DIVERIFIKASI dan berstatus LUNAS.\n\nCatatan admin: Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏\n\nTerima kasih telah membayar tepat waktu. - Admin Rumah Kos', 'pembayaran', 1, '2026-07-06 13:50:55'),
(459, 29, 'Pengajuan Sewa Disetujui!', 'Selamat! Pengajuan sewa Anda disetujui karena deposit sudah lunas. Kunci kamar No. 207 siap diambil di Office Rumah Kos.', 'sewa', 1, '2026-07-06 13:51:02'),
(461, 29, 'Pembayaran Diterima', 'Bukti pembayaran Anda telah diupload untuk 3 tagihan. Admin akan verifikasi dalam 1x24 jam. Status tagihan akan berubah jadi \"Lunas\" setelah diverifikasi.', 'pembayaran', 1, '2026-07-06 13:54:50'),
(462, 1, 'Pembayaran Baru Perlu Verifikasi', 'Penghuni roger upload bukti pembayaran untuk 3 tagihan. Segera verifikasi di menu Pembayaran.', 'pembayaran', 1, '2026-07-06 13:54:50'),
(463, 29, 'Kunci Telah Diambil', 'Kunci kamar Anda telah diterima. Selamat menempati kamar Anda. Jangan lupa menjaga kebersihan dan ketertiban kos.', 'info', 1, '2026-07-06 13:55:28'),
(464, 29, '✅ Pembayaran Diverifikasi (Lunas)', 'Pembayaran 3 tagihan (Bulan 1, Bulan 2, Bulan 3) sebesar Rp 2.400.000 telah DIVERIFIKASI dan berstatus LUNAS.\n\nCatatan admin: Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏\n\nTerima kasih telah membayar tepat waktu. - Admin Rumah Kos', 'pembayaran', 1, '2026-07-06 13:55:41'),
(465, 29, 'Perpanjangan Kontrak Berhasil', 'Kontrak sewa kamar Anda (No. 207) berhasil diperpanjang selama 3 bulan. Tanggal selesai baru: 06 Jan 2027. Tagihan untuk 3 bulan tambahan sudah dibuat, cek menu Pembayaran.', 'kontrak', 1, '2026-07-06 13:55:58'),
(466, 1, 'Perpanjangan Kontrak', 'roger memperpanjang kontrak kamar No. 207 selama 3 bulan. Tagihan baru otomatis dibuat.', 'kontrak', 1, '2026-07-06 13:55:58'),
(467, 29, 'Pengajuan Pindah Kamar Diterima', 'Pengajuan pindah kamar Anda diterima. Admin akan cek ketersediaan kamar tujuan & menyetujui dalam 1x24 jam. Mohon tunggu konfirmasi.\n\n📊 Info Deposit Kamar:\n• Deposit lama (kamar Anda sekarang): Rp 1.600.000\n• Deposit baru (kamar tujuan): Rp 3.000.000\n\n💰 ANDA WAJIB BAYAR SELISIH DEPOSIT: Rp 1.400.000\n   (karena kamar baru lebih mahal dari kamar lama)\n   Selisih ini akan jadi tagihan tambahan setelah pindah disetujui admin.\n\n⚠️ Catatan: Deposit lama bisa dipotong kalau kamar lama rusak/kotor saat inspeksi.', 'pindah', 1, '2026-07-06 14:02:06'),
(468, 1, 'Pengajuan Pindah Kamar Baru', 'roger mengajukan pindah kamar. Segera review di menu Pindah Kamar.', 'pindah', 1, '2026-07-06 14:02:06'),
(469, 29, '✅ Pindah Kamar Disetujui!', 'Pengajuan pindah kamar Anda disetujui! Anda sekarang di Kamar No. 210.\n\nHasil inspeksi kamar lama: Kamar lama dalam kondisi bersih dan terawat. Tidak ada kerusakan. Kunci lengkap.\n\nRincian Keuangan:\n• Deposit lama: Rp 1.600.000\n• Deposit dipindah: Rp 1.600.000\n\n⚠️ WAJIB BAYAR SELISIH DEPOSIT: Rp 1.400.000\n\n🔒 KUNCI KAMAR BARU BELUM BISA DIAMBIL!\nAnda WAJIB melunasi selisih deposit di menu Pembayaran terlebih dahulu.\nSetelah admin verifikasi pembayaran, kunci akan disiapkan & Anda bisa ambil di Office.\n\n✅ Kebijakan Pindah Kamar:\n• Tagihan bulan berjalan & bulan depan yang sudah Anda bayar di kamar lama DIPINDAHKAN ke sewa kamar baru (tidak hangus).\n• Anda TIDAK perlu membayar sewa penuh lagi untuk bulan berjalan di kamar baru.\n• Anda hanya wajib membayar SELISIH DEPOSIT sebelum kunci disiapkan.\n\nKunci kamar lama harap dikembalikan. Terima kasih. 🙏', 'pindah', 1, '2026-07-06 14:03:23'),
(470, 1, '⚠️ Pindah Kamar - User Wajib Bayar Selisih Deposit', 'roger pindah kamar ke No. 210. Ada tagihan selisih deposit Rp 1.400.000 yang WAJIB dibayar user sebelum kunci disiapkan. Setelah user upload bukti & Anda verifikasi di menu Pembayaran, klik \"Set Siap Kunci\" di menu Sewa untuk mengaktifkan kunci.', 'pindah', 1, '2026-07-06 14:03:23'),
(471, 29, 'Pengajuan Checkout Diterima', 'Pengajuan checkout Anda diterima. Admin akan proses & menjadwalkan inspeksi kamar.\n\nCatatan:\n- Anda TIDAK perlu melunasi semua tagihan untuk checkout.\n- Tagihan yang masih belum dibayar akan dibatalkan saat checkout disetujui.\n- Sisa sewa (bulan belum dihuni) + deposit (setelah potongan kerusakan) akan dikembalikan ke Anda.\n- ⚠️ Karena Anda checkout SEBELUM kontrak berakhir, deposit akan DIPOTONG 50% secara otomatis sesuai kebijakan early checkout.', 'checkout', 1, '2026-07-06 14:31:03'),
(472, 1, 'Pengajuan Checkout Baru', 'roger mengajukan checkout. Segera proses di menu Checkout untuk inspeksi kamar & pengembalian dana.', 'checkout', 1, '2026-07-06 14:31:03'),
(473, 29, '✅ Checkout Disetujui - Pengembalian Dana', 'Check-Out Disetujui! Anda telah resmi keluar dari Kamar No. 210.\n\n📊 Rincian Pengembalian Dana:\n• Lama Huni: 1 bulan (dari 6 bulan kontrak)\n• Refund Sisa Sewa (dari tagihan lunas): Rp 1.600.000\n• Deposit: Rp 3.000.000\n• Potongan Early Checkout (50% Deposit): -Rp 1.500.000\n• Refund Deposit: Rp 1.500.000\n\n💰 TOTAL DIKEMBALIKAN: Rp 3.100.000\n\nBukti transfer refund sudah diupload admin. Cek halaman Checkout Anda untuk download bukti.\n\nTransfer dikirim ke: Bank BCA 8070121212 a.n. roger\n\nCatatan Inspeksi: Kamar dalam kondisi bersih dan terawat. Tidak ada kerusakan. Kunci lengkap.\n\nTerima kasih. 🙏', 'checkout', 1, '2026-07-06 14:33:09'),
(474, 1, 'Pendaftar Baru', 'Pengguna baru mendaftar: yuki (HP: 085264561414). Belum mengajukan sewa.', 'user_baru', 1, '2026-07-06 14:34:39'),
(475, 30, 'Pengajuan Sewa Diterima', 'Pengajuan kamar No. 206 diterima. WAJIB bayar Deposit Rp 1.500.000 dalam 3 hari.', 'sewa', 1, '2026-07-06 14:37:01'),
(476, 1, 'Pengajuan Sewa Baru', 'yuki mengajukan sewa kamar.', 'sewa', 1, '2026-07-06 14:37:01'),
(477, 30, 'Pembayaran Diterima', 'Bukti pembayaran Anda telah diupload untuk 1 tagihan. Admin akan verifikasi dalam 1x24 jam. Status tagihan akan berubah jadi \"Lunas\" setelah diverifikasi.', 'pembayaran', 1, '2026-07-06 14:37:35'),
(478, 1, 'Pembayaran Baru Perlu Verifikasi', 'Penghuni yuki upload bukti pembayaran untuk 1 tagihan. Segera verifikasi di menu Pembayaran.', 'pembayaran', 1, '2026-07-06 14:37:35'),
(479, 30, '✅ Pembayaran Diverifikasi (Lunas)', 'Pembayaran Deposit sebesar Rp 1.500.000 telah DIVERIFIKASI dan berstatus LUNAS.\n\nCatatan admin: Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏\n\nTerima kasih telah membayar tepat waktu. - Admin Rumah Kos', 'pembayaran', 1, '2026-07-06 14:38:18'),
(480, 30, 'Pengajuan Sewa Disetujui!', 'Selamat! Pengajuan sewa Anda disetujui karena deposit sudah lunas. Kunci kamar No. 206 siap diambil di Office Rumah Kos.', 'sewa', 1, '2026-07-06 14:40:14'),
(482, 30, 'Pembayaran Diterima', 'Bukti pembayaran Anda telah diupload untuk 2 tagihan. Admin akan verifikasi dalam 1x24 jam. Status tagihan akan berubah jadi \"Lunas\" setelah diverifikasi.', 'pembayaran', 1, '2026-07-06 14:42:26'),
(483, 1, 'Pembayaran Baru Perlu Verifikasi', 'Penghuni yuki upload bukti pembayaran untuk 2 tagihan. Segera verifikasi di menu Pembayaran.', 'pembayaran', 1, '2026-07-06 14:42:26'),
(484, 30, '✅ Pembayaran Diverifikasi (Lunas)', 'Pembayaran 2 tagihan (Bulan 1, Bulan 2) sebesar Rp 1.500.000 telah DIVERIFIKASI dan berstatus LUNAS.\n\nCatatan admin: Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏\n\nTerima kasih telah membayar tepat waktu. - Admin Rumah Kos', 'pembayaran', 1, '2026-07-06 14:42:49'),
(485, 30, 'Kunci Telah Diambil', 'Kunci kamar Anda telah diterima. Selamat menempati kamar Anda. Jangan lupa menjaga kebersihan dan ketertiban kos.', 'info', 1, '2026-07-06 14:43:53'),
(486, 30, 'Perpanjangan Kontrak Berhasil', 'Kontrak sewa kamar Anda (No. 206) berhasil diperpanjang selama 2 bulan. Tanggal selesai baru: 06 Nov 2026. Tagihan untuk 2 bulan tambahan sudah dibuat, cek menu Pembayaran.', 'kontrak', 0, '2026-07-06 14:44:17'),
(487, 1, 'Perpanjangan Kontrak', 'yuki memperpanjang kontrak kamar No. 206 selama 2 bulan. Tagihan baru otomatis dibuat.', 'kontrak', 1, '2026-07-06 14:44:17'),
(488, 30, 'Pengajuan Pindah Kamar Diterima', 'Pengajuan pindah kamar Anda diterima. Admin akan cek ketersediaan kamar tujuan & menyetujui dalam 1x24 jam. Mohon tunggu konfirmasi.\n\n📊 Info Deposit Kamar:\n• Deposit lama (kamar Anda sekarang): Rp 1.500.000\n• Deposit baru (kamar tujuan): Rp 1.600.000\n\n💰 ANDA WAJIB BAYAR SELISIH DEPOSIT: Rp 100.000\n   (karena kamar baru lebih mahal dari kamar lama)\n   Selisih ini akan jadi tagihan tambahan setelah pindah disetujui admin.\n\n⚠️ Catatan: Deposit lama bisa dipotong kalau kamar lama rusak/kotor saat inspeksi.', 'pindah', 0, '2026-07-06 14:45:26'),
(489, 1, 'Pengajuan Pindah Kamar Baru', 'yuki mengajukan pindah kamar. Segera review di menu Pindah Kamar.', 'pindah', 1, '2026-07-06 14:45:26'),
(490, 1, '⚠️ Pindah Kamar - User Wajib Bayar Selisih Deposit', 'yuki pindah kamar ke No. 207. Ada tagihan selisih deposit Rp 100.000 yang WAJIB dibayar user sebelum kunci disiapkan. Setelah user upload bukti & Anda verifikasi di menu Pembayaran, klik \"Set Siap Kunci\" di menu Sewa untuk mengaktifkan kunci.', 'pindah', 1, '2026-07-06 14:46:02'),
(491, 30, 'Pembayaran Diterima', 'Bukti pembayaran Anda telah diupload untuk 4 tagihan. Admin akan verifikasi dalam 1x24 jam. Status tagihan akan berubah jadi \"Lunas\" setelah diverifikasi.', 'pembayaran', 0, '2026-07-06 15:20:46'),
(492, 1, 'Pembayaran Baru Perlu Verifikasi', 'Penghuni yuki upload bukti pembayaran untuk 4 tagihan. Segera verifikasi di menu Pembayaran.', 'pembayaran', 1, '2026-07-06 15:20:46'),
(493, 30, '✅ Pembayaran Diverifikasi (Lunas)', 'Pembayaran 4 tagihan (Bulan 3, Bulan 4, Deposit, Deposit) sebesar Rp 1.800.000 telah DIVERIFIKASI dan berstatus LUNAS.\n\nCatatan admin: Selisih Deposit Kamar Baru\n\nTerima kasih telah membayar tepat waktu. - Admin Rumah Kos', 'pembayaran', 0, '2026-07-06 15:21:06'),
(494, 30, 'Kunci Telah Diambil', 'Kunci kamar Anda telah diterima. Selamat menempati kamar Anda. Jangan lupa menjaga kebersihan dan ketertiban kos.', 'info', 0, '2026-07-06 15:22:15'),
(495, 30, 'Pengajuan Checkout Diterima', 'Pengajuan checkout Anda diterima. Admin akan proses & menjadwalkan inspeksi kamar.\n\nCatatan:\n- Anda TIDAK perlu melunasi semua tagihan untuk checkout.\n- Tagihan yang masih belum dibayar akan dibatalkan saat checkout disetujui.\n- Sisa sewa (bulan belum dihuni) + deposit (setelah potongan kerusakan) akan dikembalikan ke Anda.\n- ⚠️ Karena Anda checkout SEBELUM kontrak berakhir, deposit akan DIPOTONG 50% secara otomatis sesuai kebijakan early checkout.', 'checkout', 0, '2026-07-06 15:24:42'),
(496, 1, 'Pengajuan Checkout Baru', 'yuki mengajukan checkout. Segera proses di menu Checkout untuk inspeksi kamar & pengembalian dana.', 'checkout', 1, '2026-07-06 15:24:42'),
(497, 30, '✅ Checkout Disetujui - Pengembalian Dana', 'Check-Out Disetujui! Anda telah resmi keluar dari Kamar No. 207.\n\n📊 Rincian Pengembalian Dana:\n• Lama Huni: 1 bulan (dari 4 bulan kontrak)\n• Refund Sisa Sewa (dari tagihan lunas): Rp 2.400.000\n• Deposit: Rp 1.600.000\n• Potongan Early Checkout (50% Deposit): -Rp 800.000\n• Refund Deposit: Rp 800.000\n\n💰 TOTAL DIKEMBALIKAN: Rp 3.200.000\n\nBukti transfer refund sudah diupload admin. Cek halaman Checkout Anda untuk download bukti.\n\nTransfer dikirim ke: Bank BNI 8070121212 a.n. yuki\n\nCatatan Inspeksi: Kamar dalam kondisi bersih dan terawat. Tidak ada kerusakan. Kunci lengkap.\n\nTerima kasih. 🙏', 'checkout', 0, '2026-07-06 15:25:48'),
(498, 1, 'Pendaftar Baru', 'Pengguna baru mendaftar: roger (HP: 081245451212). Belum mengajukan sewa.', 'user_baru', 1, '2026-07-06 15:29:04'),
(499, 31, 'Pengajuan Sewa Diterima', 'Pengajuan kamar No. 207 diterima. WAJIB bayar Deposit Rp 1.600.000 dalam 3 hari.', 'sewa', 1, '2026-07-06 15:32:55'),
(500, 1, 'Pengajuan Sewa Baru', 'roger mengajukan sewa kamar.', 'sewa', 1, '2026-07-06 15:32:55'),
(501, 31, 'Pembayaran Diterima', 'Bukti pembayaran Anda telah diupload untuk 1 tagihan. Admin akan verifikasi dalam 1x24 jam. Status tagihan akan berubah jadi \"Lunas\" setelah diverifikasi.', 'pembayaran', 1, '2026-07-06 15:33:37'),
(502, 1, 'Pembayaran Baru Perlu Verifikasi', 'Penghuni roger upload bukti pembayaran untuk 1 tagihan. Segera verifikasi di menu Pembayaran.', 'pembayaran', 1, '2026-07-06 15:33:37'),
(503, 31, '✅ Pembayaran Diverifikasi (Lunas)', 'Pembayaran Deposit sebesar Rp 1.600.000 telah DIVERIFIKASI dan berstatus LUNAS.\n\nCatatan admin: Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏\n\nTerima kasih telah membayar tepat waktu. - Admin Rumah Kos', 'pembayaran', 1, '2026-07-06 15:34:13'),
(504, 31, 'Pengajuan Sewa Disetujui!', 'Selamat! Pengajuan sewa Anda disetujui karena deposit sudah lunas. Kunci kamar No. 207 siap diambil di Office Rumah Kos.', 'sewa', 1, '2026-07-06 15:34:42'),
(506, 31, 'Kunci Telah Diambil', 'Kunci kamar Anda telah diterima. Selamat menempati kamar Anda. Jangan lupa menjaga kebersihan dan ketertiban kos.', 'info', 1, '2026-07-06 15:35:22'),
(507, 31, 'Pembayaran Diterima', 'Bukti pembayaran Anda telah diupload untuk 2 tagihan. Admin akan verifikasi dalam 1x24 jam. Status tagihan akan berubah jadi \"Lunas\" setelah diverifikasi.', 'pembayaran', 1, '2026-07-06 15:36:27'),
(508, 1, 'Pembayaran Baru Perlu Verifikasi', 'Penghuni roger upload bukti pembayaran untuk 2 tagihan. Segera verifikasi di menu Pembayaran.', 'pembayaran', 1, '2026-07-06 15:36:27'),
(509, 31, '✅ Pembayaran Diverifikasi (Lunas)', 'Pembayaran 2 tagihan (Bulan 1, Bulan 2) sebesar Rp 1.600.000 telah DIVERIFIKASI dan berstatus LUNAS.\n\nCatatan admin: Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏\n\nTerima kasih telah membayar tepat waktu. - Admin Rumah Kos', 'pembayaran', 1, '2026-07-06 15:36:56'),
(510, 31, 'Perpanjangan Kontrak Berhasil', 'Kontrak sewa kamar Anda (No. 207) berhasil diperpanjang selama 2 bulan. Tanggal selesai baru: 06 Nov 2026. Tagihan untuk 2 bulan tambahan sudah dibuat, cek menu Pembayaran.', 'kontrak', 0, '2026-07-06 15:37:26'),
(511, 1, 'Perpanjangan Kontrak', 'roger memperpanjang kontrak kamar No. 207 selama 2 bulan. Tagihan baru otomatis dibuat.', 'kontrak', 1, '2026-07-06 15:37:26'),
(512, 31, 'Pengajuan Pindah Kamar Diterima', 'Pengajuan pindah kamar Anda diterima. Admin akan cek ketersediaan kamar tujuan & menyetujui dalam 1x24 jam. Mohon tunggu konfirmasi.\n\n📊 Info Deposit Kamar:\n• Deposit lama (kamar Anda sekarang): Rp 1.600.000\n• Deposit baru (kamar tujuan): Rp 3.000.000\n\n💰 ANDA WAJIB BAYAR SELISIH DEPOSIT: Rp 1.400.000\n   (karena kamar baru lebih mahal dari kamar lama)\n   Selisih ini akan jadi tagihan tambahan setelah pindah disetujui admin.\n\n⚠️ Catatan: Deposit lama bisa dipotong kalau kamar lama rusak/kotor saat inspeksi.', 'pindah', 1, '2026-07-06 15:39:27'),
(513, 1, 'Pengajuan Pindah Kamar Baru', 'roger mengajukan pindah kamar. Segera review di menu Pindah Kamar.', 'pindah', 1, '2026-07-06 15:39:27'),
(514, 1, '⚠️ Pindah Kamar - User Wajib Bayar Selisih Deposit', 'roger pindah kamar ke No. 210. Ada tagihan selisih deposit Rp 1.400.000 yang WAJIB dibayar user sebelum kunci disiapkan. Setelah user upload bukti & Anda verifikasi di menu Pembayaran, klik \"Set Siap Kunci\" di menu Sewa untuk mengaktifkan kunci.', 'pindah', 1, '2026-07-06 15:40:21'),
(515, 31, 'Pembayaran Diterima', 'Bukti pembayaran Anda telah diupload untuk 2 tagihan. Admin akan verifikasi dalam 1x24 jam. Status tagihan akan berubah jadi \"Lunas\" setelah diverifikasi.', 'pembayaran', 0, '2026-07-06 15:41:34'),
(516, 1, 'Pembayaran Baru Perlu Verifikasi', 'Penghuni roger upload bukti pembayaran untuk 2 tagihan. Segera verifikasi di menu Pembayaran.', 'pembayaran', 1, '2026-07-06 15:41:34'),
(517, 31, '✅ Pembayaran Diverifikasi (Lunas)', 'Pembayaran 2 tagihan (Bulan -1, Deposit) sebesar Rp 2.800.000 telah DIVERIFIKASI dan berstatus LUNAS.\n\nCatatan admin: Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏\n\nTerima kasih telah membayar tepat waktu. - Admin Rumah Kos', 'pembayaran', 0, '2026-07-06 15:43:58'),
(518, 31, 'Keluhan Diterima: di kamar sebelag sangat bising', 'Keluhan kebersihan diterima. Petugas kebersihan akan ditugaskan dalam 1x24 jam.', 'keluhan', 1, '2026-07-06 15:45:13'),
(519, 1, 'Keluhan Baru (PENGHUNI)', '[PENGHUNI] di kamar sebelag sangat bising', 'keluhan', 1, '2026-07-06 15:45:13'),
(520, 31, 'Kunci Telah Diambil', 'Kunci kamar Anda telah diterima. Selamat menempati kamar Anda. Jangan lupa menjaga kebersihan dan ketertiban kos.', 'info', 0, '2026-07-06 15:46:16'),
(521, 4, 'Pengumuman: Pengumuman Pemadaman Air', 'Diberitahukan kepada seluruh penghuni kos bahwa akan ada pemadaman air sementara.\r\n\r\nMohon maaf atas ketidaknyamanannya. Pastikan Anda sudah menampung', 'pengumuman', 0, '2026-07-06 15:47:58'),
(522, 5, 'Pengumuman: Pengumuman Pemadaman Air', 'Diberitahukan kepada seluruh penghuni kos bahwa akan ada pemadaman air sementara.\r\n\r\nMohon maaf atas ketidaknyamanannya. Pastikan Anda sudah menampung', 'pengumuman', 0, '2026-07-06 15:47:58'),
(523, 7, 'Pengumuman: Pengumuman Pemadaman Air', 'Diberitahukan kepada seluruh penghuni kos bahwa akan ada pemadaman air sementara.\r\n\r\nMohon maaf atas ketidaknyamanannya. Pastikan Anda sudah menampung', 'pengumuman', 0, '2026-07-06 15:47:58'),
(524, 9, 'Pengumuman: Pengumuman Pemadaman Air', 'Diberitahukan kepada seluruh penghuni kos bahwa akan ada pemadaman air sementara.\r\n\r\nMohon maaf atas ketidaknyamanannya. Pastikan Anda sudah menampung', 'pengumuman', 0, '2026-07-06 15:47:58'),
(525, 10, 'Pengumuman: Pengumuman Pemadaman Air', 'Diberitahukan kepada seluruh penghuni kos bahwa akan ada pemadaman air sementara.\r\n\r\nMohon maaf atas ketidaknyamanannya. Pastikan Anda sudah menampung', 'pengumuman', 0, '2026-07-06 15:47:58'),
(526, 12, 'Pengumuman: Pengumuman Pemadaman Air', 'Diberitahukan kepada seluruh penghuni kos bahwa akan ada pemadaman air sementara.\r\n\r\nMohon maaf atas ketidaknyamanannya. Pastikan Anda sudah menampung', 'pengumuman', 0, '2026-07-06 15:47:58'),
(527, 17, 'Pengumuman: Pengumuman Pemadaman Air', 'Diberitahukan kepada seluruh penghuni kos bahwa akan ada pemadaman air sementara.\r\n\r\nMohon maaf atas ketidaknyamanannya. Pastikan Anda sudah menampung', 'pengumuman', 0, '2026-07-06 15:47:58'),
(528, 25, 'Pengumuman: Pengumuman Pemadaman Air', 'Diberitahukan kepada seluruh penghuni kos bahwa akan ada pemadaman air sementara.\r\n\r\nMohon maaf atas ketidaknyamanannya. Pastikan Anda sudah menampung', 'pengumuman', 0, '2026-07-06 15:47:58'),
(529, 31, 'Pengumuman: Pengumuman Pemadaman Air', 'Diberitahukan kepada seluruh penghuni kos bahwa akan ada pemadaman air sementara.\r\n\r\nMohon maaf atas ketidaknyamanannya. Pastikan Anda sudah menampung', 'pengumuman', 1, '2026-07-06 15:47:58'),
(530, 31, 'Keluhan Diterima: di kamar sebelag sangat bising', 'Keluhan kebersihan diterima. Petugas kebersihan akan ditugaskan dalam 1x24 jam.', 'keluhan', 0, '2026-07-06 15:50:12'),
(531, 1, 'Keluhan Baru (PENGHUNI)', '[PENGHUNI] di kamar sebelag sangat bising', 'keluhan', 1, '2026-07-06 15:50:12'),
(532, 31, 'Keluhan Diterima: di kamar sebelag sangat bising', 'Keluhan kebersihan diterima. Petugas kebersihan akan ditugaskan dalam 1x24 jam.', 'keluhan', 0, '2026-07-06 15:50:52'),
(533, 1, 'Keluhan Baru (PENGHUNI)', '[PENGHUNI] di kamar sebelag sangat bising', 'keluhan', 1, '2026-07-06 15:50:52'),
(534, 31, 'Keluhan Diterima: kematian air ', 'Keluhan listrik/air Anda diterima. Tim teknisi akan segera mengecek. Estimasi penanganan: 2-4 jam.', 'keluhan', 1, '2026-07-06 15:52:30'),
(535, 1, 'Keluhan Baru (PENGHUNI)', '[PENGHUNI] kematian air ', 'keluhan', 1, '2026-07-06 15:52:30'),
(536, 7, 'Tagihan Terlambat - Denda Rp 660.000', 'Tagihan sewa kamar No. 101 (Bulan ke-1) sudah melewati jatuh tempo. Denda: Rp 660.000. Segera bayar!', 'tagihan', 0, '2026-07-06 17:29:17'),
(537, 7, 'Tagihan Terlambat - Denda Rp 350.000', 'Tagihan sewa kamar No. 101 (Bulan ke-2) sudah melewati jatuh tempo. Denda: Rp 350.000. Segera bayar!', 'tagihan', 0, '2026-07-06 17:29:17'),
(538, 1, 'Pendaftar Baru', 'Pengguna baru mendaftar: yelvi (HP: 081234563456). Belum mengajukan sewa.', 'user_baru', 1, '2026-07-06 17:31:49'),
(539, 32, 'Pengajuan Sewa Diterima', 'Pengajuan kamar No. 206 diterima. WAJIB bayar Deposit Rp 1.500.000 dalam 3 hari.', 'sewa', 1, '2026-07-06 17:34:37'),
(540, 1, 'Pengajuan Sewa Baru', 'yelvi mengajukan sewa kamar.', 'sewa', 1, '2026-07-06 17:34:37'),
(541, 32, 'Pembayaran Diterima', 'Bukti pembayaran Anda telah diupload untuk 1 tagihan. Admin akan verifikasi dalam 1x24 jam. Status tagihan akan berubah jadi \"Lunas\" setelah diverifikasi.', 'pembayaran', 1, '2026-07-06 17:37:43'),
(542, 1, 'Pembayaran Baru Perlu Verifikasi', 'Penghuni yelvi upload bukti pembayaran untuk 1 tagihan. Segera verifikasi di menu Pembayaran.', 'pembayaran', 1, '2026-07-06 17:37:43'),
(543, 32, '✅ Pembayaran Diverifikasi (Lunas)', 'Pembayaran Deposit sebesar Rp 1.500.000 telah DIVERIFIKASI dan berstatus LUNAS.\n\nCatatan admin: Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏\n\nTerima kasih telah membayar tepat waktu. - Admin Rumah Kos', 'pembayaran', 1, '2026-07-06 17:38:01'),
(544, 32, 'Pengajuan Sewa Disetujui!', 'Selamat! Pengajuan sewa Anda disetujui karena deposit sudah lunas. Kunci kamar No. 206 siap diambil di Office Rumah Kos.', 'sewa', 1, '2026-07-06 17:38:55'),
(546, 32, 'Kunci Telah Diambil', 'Kunci kamar Anda telah diterima. Selamat menempati kamar Anda. Jangan lupa menjaga kebersihan dan ketertiban kos.', 'info', 1, '2026-07-06 17:39:10'),
(547, 32, 'Pembayaran Diterima', 'Bukti pembayaran Anda telah diupload untuk 2 tagihan. Admin akan verifikasi dalam 1x24 jam. Status tagihan akan berubah jadi \"Lunas\" setelah diverifikasi.', 'pembayaran', 1, '2026-07-06 17:39:48'),
(548, 1, 'Pembayaran Baru Perlu Verifikasi', 'Penghuni yelvi upload bukti pembayaran untuk 2 tagihan. Segera verifikasi di menu Pembayaran.', 'pembayaran', 1, '2026-07-06 17:39:48'),
(549, 32, '✅ Pembayaran Diverifikasi (Lunas)', 'Pembayaran 2 tagihan (Bulan 1, Bulan 2) sebesar Rp 1.500.000 telah DIVERIFIKASI dan berstatus LUNAS.\n\nCatatan admin: Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏\n\nTerima kasih telah membayar tepat waktu. - Admin Rumah Kos', 'pembayaran', 1, '2026-07-06 17:41:43'),
(550, 32, 'Perpanjangan Kontrak Berhasil', 'Kontrak sewa kamar Anda (No. 206) berhasil diperpanjang selama 2 bulan. Tanggal selesai baru: 06 Nov 2026. Tagihan untuk 2 bulan tambahan sudah dibuat, cek menu Pembayaran.', 'kontrak', 1, '2026-07-06 17:42:01'),
(551, 1, 'Perpanjangan Kontrak', 'yelvi memperpanjang kontrak kamar No. 206 selama 2 bulan. Tagihan baru otomatis dibuat.', 'kontrak', 1, '2026-07-06 17:42:01'),
(552, 32, 'Pengajuan Pindah Kamar Diterima', 'Pengajuan pindah kamar Anda diterima. Admin akan cek ketersediaan kamar tujuan & menyetujui dalam 1x24 jam. Mohon tunggu konfirmasi.\n\n📊 Info Deposit Kamar:\n• Deposit lama (kamar Anda sekarang): Rp 1.500.000\n• Deposit baru (kamar tujuan): Rp 1.600.000\n\n💰 ANDA WAJIB BAYAR SELISIH DEPOSIT: Rp 100.000\n   (karena kamar baru lebih mahal dari kamar lama)\n   Selisih ini akan jadi tagihan tambahan setelah pindah disetujui admin.\n\n⚠️ Catatan: Deposit lama bisa dipotong kalau kamar lama rusak/kotor saat inspeksi.', 'pindah', 1, '2026-07-06 17:43:46'),
(553, 1, 'Pengajuan Pindah Kamar Baru', 'yelvi mengajukan pindah kamar. Segera review di menu Pindah Kamar.', 'pindah', 1, '2026-07-06 17:43:46'),
(554, 1, '⚠️ Pindah Kamar - User Wajib Bayar Selisih Deposit', 'yelvi pindah kamar ke No. 207. Ada tagihan selisih deposit Rp 100.000 yang WAJIB dibayar user sebelum kunci disiapkan. Setelah user upload bukti & Anda verifikasi di menu Pembayaran, klik \"Set Siap Kunci\" di menu Sewa untuk mengaktifkan kunci.', 'pindah', 1, '2026-07-06 17:44:57'),
(555, 32, 'Pembayaran Diterima', 'Bukti pembayaran Anda telah diupload untuk 2 tagihan. Admin akan verifikasi dalam 1x24 jam. Status tagihan akan berubah jadi \"Lunas\" setelah diverifikasi.', 'pembayaran', 1, '2026-07-06 17:47:13'),
(556, 1, 'Pembayaran Baru Perlu Verifikasi', 'Penghuni yelvi upload bukti pembayaran untuk 2 tagihan. Segera verifikasi di menu Pembayaran.', 'pembayaran', 1, '2026-07-06 17:47:13'),
(557, 32, '✅ Pembayaran Diverifikasi (Lunas)', 'Pembayaran 2 tagihan (Bulan -1, Deposit) sebesar Rp 200.000 telah DIVERIFIKASI dan berstatus LUNAS.\n\nCatatan admin: Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏\n\nTerima kasih telah membayar tepat waktu. - Admin Rumah Kos', 'pembayaran', 1, '2026-07-06 17:47:41'),
(558, 32, 'Keluhan Diterima: di kamar sebelag sangat bising', 'Keluhan kebersihan diterima. Petugas kebersihan akan ditugaskan dalam 1x24 jam.', 'keluhan', 1, '2026-07-06 17:55:03'),
(559, 1, 'Keluhan Baru (PENGHUNI)', '[PENGHUNI] di kamar sebelag sangat bising', 'keluhan', 1, '2026-07-06 17:55:03'),
(560, 32, 'Keluhan Diterima: di kamar sebelag sangat bising', 'Keluhan kebersihan diterima. Petugas kebersihan akan ditugaskan dalam 1x24 jam.', 'keluhan', 1, '2026-07-06 17:55:21'),
(561, 1, 'Keluhan Baru (PENGHUNI)', '[PENGHUNI] di kamar sebelag sangat bising', 'keluhan', 1, '2026-07-06 17:55:21'),
(562, 32, 'Keluhan Diperbarui: di kamar sebelag sangat bising', 'Status keluhan Anda: Diproses. Balasan admin: Sudah ditangani teknisi. Silakan cek kembali. Terima kasih.', 'keluhan', 1, '2026-07-06 17:56:07'),
(563, 32, 'Keluhan Diperbarui: di kamar sebelag sangat bising', 'Status keluhan Anda: Selesai. ', 'keluhan', 1, '2026-07-06 17:56:15'),
(564, 32, 'Kunci Telah Diambil', 'Kunci kamar Anda telah diterima. Selamat menempati kamar Anda. Jangan lupa menjaga kebersihan dan ketertiban kos.', 'info', 0, '2026-07-06 18:58:42'),
(565, 32, 'Pengajuan Checkout Diterima', 'Pengajuan checkout Anda diterima. Admin akan proses & menjadwalkan inspeksi kamar.\n\nCatatan:\n- Anda TIDAK perlu melunasi semua tagihan untuk checkout.\n- Tagihan yang masih belum dibayar akan dibatalkan saat checkout disetujui.\n- Sisa sewa (bulan belum dihuni) + deposit (setelah potongan kerusakan) akan dikembalikan ke Anda.\n- ⚠️ Karena Anda checkout SEBELUM kontrak berakhir, deposit akan DIPOTONG 50% secara otomatis sesuai kebijakan early checkout.', 'checkout', 0, '2026-07-06 19:00:05'),
(566, 1, 'Pengajuan Checkout Baru', 'yelvi mengajukan checkout. Segera proses di menu Checkout untuk inspeksi kamar & pengembalian dana.', 'checkout', 1, '2026-07-06 19:00:05'),
(567, 32, '✅ Checkout Disetujui - Pengembalian Dana', 'Check-Out Disetujui! Anda telah resmi keluar dari Kamar No. 207.\n\n📊 Rincian Pengembalian Dana:\n• Lama Huni: 1 bulan (dari 4 bulan kontrak)\n• Refund Sisa Sewa (dari tagihan lunas): Rp 800.000\n• Deposit: Rp 1.600.000\n• Potongan Early Checkout (50% Deposit): -Rp 800.000\n• Refund Deposit: Rp 800.000\n\n💰 TOTAL DIKEMBALIKAN: Rp 1.600.000\n\nBukti transfer refund sudah diupload admin. Cek halaman Checkout Anda untuk download bukti.\n\nTransfer dikirim ke: Bank BCA 8070121212 a.n. yelvi\n\nCatatan Inspeksi: Kamar dalam kondisi bersih dan terawat. Tidak ada kerusakan. Kunci lengkap.\n\nTerima kasih. 🙏', 'checkout', 0, '2026-07-06 19:01:10'),
(568, 1, 'Pendaftar Baru', 'Pengguna baru mendaftar: yelvi (HP: 0823151589889). Belum mengajukan sewa.', 'user_baru', 1, '2026-07-06 19:04:18'),
(569, 33, 'Pengajuan Sewa Diterima', 'Pengajuan kamar No. 103 diterima. WAJIB bayar Deposit Rp 1.400.000 dalam 3 hari.', 'sewa', 1, '2026-07-06 19:06:38'),
(570, 1, 'Pengajuan Sewa Baru', 'yelvi mengajukan sewa kamar.', 'sewa', 1, '2026-07-06 19:06:38'),
(571, 33, '❌ Pengajuan Sewa Ditolak', 'Maaf, pengajuan sewa Anda ditolak. Alasan: Pengajuan sewa ditolak oleh admin.. Silakan pilih kamar lain yang tersedia. Terima kasih. 🙏', 'sewa', 1, '2026-07-06 19:07:14'),
(572, 33, '✅ Penolakan Sewa Dibatalkan', 'Permohonan maaf, penolakan pengajuan sewa Anda telah DIBATALKAN oleh admin (kemungkinan salah tekan). Status pengajuan Anda kembali ke MENUNGGU. Admin akan meninjau kembali dan menyetujui jika semua syarat terpenuhi. Mohon tunggu konfirmasi selanjutnya. Terima kasih. 🙏', 'sewa', 1, '2026-07-06 19:08:25'),
(573, 33, '❌ Pengajuan Sewa Ditolak', 'Maaf, pengajuan sewa Anda ditolak. Alasan: Pengajuan sewa ditolak oleh admin.. Silakan pilih kamar lain yang tersedia. Terima kasih. 🙏', 'sewa', 1, '2026-07-06 19:10:26'),
(574, 33, 'Pengajuan Sewa Diterima', 'Pengajuan kamar No. 103 diterima. WAJIB bayar Deposit Rp 1.400.000 dalam 3 hari.', 'sewa', 1, '2026-07-06 19:10:54'),
(575, 1, 'Pengajuan Sewa Baru', 'yelvi mengajukan sewa kamar.', 'sewa', 1, '2026-07-06 19:10:54'),
(576, 33, 'Pembayaran Diterima', 'Bukti pembayaran Anda telah diupload untuk 1 tagihan. Admin akan verifikasi dalam 1x24 jam. Status tagihan akan berubah jadi \"Lunas\" setelah diverifikasi.', 'pembayaran', 1, '2026-07-06 19:11:24'),
(577, 1, 'Pembayaran Baru Perlu Verifikasi', 'Penghuni yelvi upload bukti pembayaran untuk 1 tagihan. Segera verifikasi di menu Pembayaran.', 'pembayaran', 1, '2026-07-06 19:11:24'),
(578, 33, '✅ Pembayaran Diverifikasi (Lunas)', 'Pembayaran Deposit sebesar Rp 1.400.000 telah DIVERIFIKASI dan berstatus LUNAS.\n\nCatatan admin: Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏\n\nTerima kasih telah membayar tepat waktu. - Admin Rumah Kos', 'pembayaran', 1, '2026-07-06 19:11:58'),
(579, 33, 'Pengajuan Sewa Disetujui!', 'Selamat! Pengajuan sewa Anda disetujui karena deposit sudah lunas. Kunci kamar No. 103 siap diambil di Office Rumah Kos.', 'sewa', 1, '2026-07-06 19:12:25'),
(581, 33, 'Kunci Telah Diambil', 'Kunci kamar Anda telah diterima. Selamat menempati kamar Anda. Jangan lupa menjaga kebersihan dan ketertiban kos.', 'info', 1, '2026-07-06 19:12:38'),
(582, 33, 'Pembayaran Diterima', 'Bukti pembayaran Anda telah diupload untuk 2 tagihan. Admin akan verifikasi dalam 1x24 jam. Status tagihan akan berubah jadi \"Lunas\" setelah diverifikasi.', 'pembayaran', 1, '2026-07-06 19:14:44'),
(583, 1, 'Pembayaran Baru Perlu Verifikasi', 'Penghuni yelvi upload bukti pembayaran untuk 2 tagihan. Segera verifikasi di menu Pembayaran.', 'pembayaran', 1, '2026-07-06 19:14:44'),
(584, 33, '✅ Pembayaran Diverifikasi (Lunas)', 'Pembayaran 2 tagihan (Bulan 1, Bulan 2) sebesar Rp 1.400.000 telah DIVERIFIKASI dan berstatus LUNAS.\n\nCatatan admin: Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏\n\nTerima kasih telah membayar tepat waktu. - Admin Rumah Kos', 'pembayaran', 1, '2026-07-06 19:15:16'),
(585, 33, 'Perpanjangan Kontrak Berhasil', 'Kontrak sewa kamar Anda (No. 103) berhasil diperpanjang selama 2 bulan. Tanggal selesai baru: 06 Nov 2026. Tagihan untuk 2 bulan tambahan sudah dibuat, cek menu Pembayaran.', 'kontrak', 1, '2026-07-06 19:16:19'),
(586, 1, 'Perpanjangan Kontrak', 'yelvi memperpanjang kontrak kamar No. 103 selama 2 bulan. Tagihan baru otomatis dibuat.', 'kontrak', 1, '2026-07-06 19:16:19'),
(587, 33, 'Pengajuan Pindah Kamar Diterima', 'Pengajuan pindah kamar Anda diterima. Admin akan cek ketersediaan kamar tujuan & menyetujui dalam 1x24 jam. Mohon tunggu konfirmasi.\n\n📊 Info Deposit Kamar:\n• Deposit lama (kamar Anda sekarang): Rp 1.400.000\n• Deposit baru (kamar tujuan): Rp 1.600.000\n\n💰 ANDA WAJIB BAYAR SELISIH DEPOSIT: Rp 200.000\n   (karena kamar baru lebih mahal dari kamar lama)\n   Selisih ini akan jadi tagihan tambahan setelah pindah disetujui admin.\n\n⚠️ Catatan: Deposit lama bisa dipotong kalau kamar lama rusak/kotor saat inspeksi.', 'pindah', 1, '2026-07-06 19:16:54'),
(588, 1, 'Pengajuan Pindah Kamar Baru', 'yelvi mengajukan pindah kamar. Segera review di menu Pindah Kamar.', 'pindah', 1, '2026-07-06 19:16:54'),
(589, 1, '⚠️ Pindah Kamar - User Wajib Bayar Selisih Deposit', 'yelvi pindah kamar ke No. 207. Ada tagihan selisih deposit Rp 200.000 yang WAJIB dibayar user sebelum kunci disiapkan. Setelah user upload bukti & Anda verifikasi di menu Pembayaran, klik \"Set Siap Kunci\" di menu Sewa untuk mengaktifkan kunci.', 'pindah', 1, '2026-07-06 19:17:54'),
(590, 33, 'Pembayaran Diterima', 'Bukti pembayaran Anda telah diupload untuk 1 tagihan. Admin akan verifikasi dalam 1x24 jam. Status tagihan akan berubah jadi \"Lunas\" setelah diverifikasi.', 'pembayaran', 0, '2026-07-06 19:18:52'),
(591, 1, 'Pembayaran Baru Perlu Verifikasi', 'Penghuni yelvi upload bukti pembayaran untuk 1 tagihan. Segera verifikasi di menu Pembayaran.', 'pembayaran', 1, '2026-07-06 19:18:52'),
(592, 33, '✅ Pembayaran Diverifikasi (Lunas)', 'Pembayaran Deposit sebesar Rp 200.000 telah DIVERIFIKASI dan berstatus LUNAS.\n\nCatatan admin: Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏\n\nTerima kasih telah membayar tepat waktu. - Admin Rumah Kos', 'pembayaran', 0, '2026-07-06 19:19:48'),
(593, 7, 'Tagihan Terlambat - Denda Rp 670.000', 'Tagihan sewa kamar No. 101 (Bulan ke-1) sudah melewati jatuh tempo. Denda: Rp 670.000. Segera bayar!', 'tagihan', 0, '2026-07-07 12:57:18'),
(594, 7, 'Tagihan Terlambat - Denda Rp 360.000', 'Tagihan sewa kamar No. 101 (Bulan ke-2) sudah melewati jatuh tempo. Denda: Rp 360.000. Segera bayar!', 'tagihan', 0, '2026-07-07 12:57:18'),
(595, 7, 'Tagihan Terlambat - Denda Rp 670.000', 'Tagihan sewa kamar No. 101 (Bulan ke-1) sudah melewati jatuh tempo. Denda: Rp 670.000. Segera bayar!', 'tagihan', 0, '2026-07-07 13:02:33'),
(596, 7, 'Tagihan Terlambat - Denda Rp 360.000', 'Tagihan sewa kamar No. 101 (Bulan ke-2) sudah melewati jatuh tempo. Denda: Rp 360.000. Segera bayar!', 'tagihan', 0, '2026-07-07 13:02:33'),
(597, 7, 'Tagihan Terlambat - Denda Rp 670.000', 'Tagihan sewa kamar No. 101 (Bulan ke-1) sudah melewati jatuh tempo. Denda: Rp 670.000. Segera bayar!', 'tagihan', 0, '2026-07-07 13:03:41'),
(598, 7, 'Tagihan Terlambat - Denda Rp 360.000', 'Tagihan sewa kamar No. 101 (Bulan ke-2) sudah melewati jatuh tempo. Denda: Rp 360.000. Segera bayar!', 'tagihan', 0, '2026-07-07 13:03:41'),
(599, 7, 'Tagihan Terlambat - Denda Rp 670.000', 'Tagihan sewa kamar No. 101 (Bulan ke-1) sudah melewati jatuh tempo. Denda: Rp 670.000. Segera bayar!', 'tagihan', 0, '2026-07-07 13:04:05'),
(600, 7, 'Tagihan Terlambat - Denda Rp 360.000', 'Tagihan sewa kamar No. 101 (Bulan ke-2) sudah melewati jatuh tempo. Denda: Rp 360.000. Segera bayar!', 'tagihan', 0, '2026-07-07 13:04:05'),
(601, 7, 'Tagihan Terlambat - Denda Rp 670.000', 'Tagihan sewa kamar No. 101 (Bulan ke-1) sudah melewati jatuh tempo. Denda: Rp 670.000. Segera bayar!', 'tagihan', 0, '2026-07-07 13:18:18'),
(602, 7, 'Tagihan Terlambat - Denda Rp 360.000', 'Tagihan sewa kamar No. 101 (Bulan ke-2) sudah melewati jatuh tempo. Denda: Rp 360.000. Segera bayar!', 'tagihan', 0, '2026-07-07 13:18:18'),
(603, 7, 'Tagihan Terlambat - Denda Rp 670.000', 'Tagihan sewa kamar No. 101 (Bulan ke-1) sudah melewati jatuh tempo. Denda: Rp 670.000. Segera bayar!', 'tagihan', 0, '2026-07-07 13:18:32'),
(604, 7, 'Tagihan Terlambat - Denda Rp 360.000', 'Tagihan sewa kamar No. 101 (Bulan ke-2) sudah melewati jatuh tempo. Denda: Rp 360.000. Segera bayar!', 'tagihan', 0, '2026-07-07 13:18:32'),
(605, 7, 'Tagihan Terlambat - Denda Rp 670.000', 'Tagihan sewa kamar No. 101 (Bulan ke-1) sudah melewati jatuh tempo. Denda: Rp 670.000. Segera bayar!', 'tagihan', 0, '2026-07-07 13:29:59'),
(606, 7, 'Tagihan Terlambat - Denda Rp 360.000', 'Tagihan sewa kamar No. 101 (Bulan ke-2) sudah melewati jatuh tempo. Denda: Rp 360.000. Segera bayar!', 'tagihan', 0, '2026-07-07 13:29:59'),
(607, 7, 'Tagihan Terlambat - Denda Rp 670.000', 'Tagihan sewa kamar No. 101 (Bulan ke-1) sudah melewati jatuh tempo. Denda: Rp 670.000. Segera bayar!', 'tagihan', 0, '2026-07-07 13:30:11'),
(608, 7, 'Tagihan Terlambat - Denda Rp 360.000', 'Tagihan sewa kamar No. 101 (Bulan ke-2) sudah melewati jatuh tempo. Denda: Rp 360.000. Segera bayar!', 'tagihan', 0, '2026-07-07 13:30:11'),
(609, 1, 'Pendaftar Baru', 'Pengguna baru mendaftar: yuki (HP: 08121245456). Belum mengajukan sewa.', 'user_baru', 0, '2026-07-07 13:31:19'),
(610, 7, 'Tagihan Terlambat - Denda Rp 670.000', 'Tagihan sewa kamar No. 101 (Bulan ke-1) sudah melewati jatuh tempo. Denda: Rp 670.000. Segera bayar!', 'tagihan', 0, '2026-07-07 13:42:46'),
(611, 7, 'Tagihan Terlambat - Denda Rp 360.000', 'Tagihan sewa kamar No. 101 (Bulan ke-2) sudah melewati jatuh tempo. Denda: Rp 360.000. Segera bayar!', 'tagihan', 0, '2026-07-07 13:42:46'),
(612, 33, 'Pengajuan Checkout Diterima', 'Pengajuan checkout Anda diterima. Admin akan proses & menjadwalkan inspeksi kamar.\n\nCatatan:\n- Anda TIDAK perlu melunasi semua tagihan untuk checkout.\n- Tagihan yang masih belum dibayar akan dibatalkan saat checkout disetujui.\n- Sisa sewa (bulan belum dihuni) + deposit (setelah potongan kerusakan) akan dikembalikan ke Anda.\n- ⚠️ Karena Anda checkout SEBELUM kontrak berakhir, deposit akan DIPOTONG 50% secara otomatis sesuai kebijakan early checkout.', 'checkout', 0, '2026-07-07 13:43:59'),
(613, 1, 'Pengajuan Checkout Baru', 'yelvi mengajukan checkout. Segera proses di menu Checkout untuk inspeksi kamar & pengembalian dana.', 'checkout', 0, '2026-07-07 13:43:59');

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset`
--

CREATE TABLE `password_reset` (
  `id_reset` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expires_at` datetime NOT NULL,
  `used` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `password_reset`
--

INSERT INTO `password_reset` (`id_reset`, `email`, `token`, `expires_at`, `used`, `created_at`) VALUES
(1, 'budi@test.com', '5f267db48cb4f6a5288eae5062a5fb00691328d01779dd777a4690d9704b0747', '2026-06-27 05:57:27', 1, '2026-06-27 04:57:27'),
(2, 'checkout@test.com', '20e791e8546e09d7438ec163e6cf4b3416a1a00d5367b2c01c79f88bc1d5f59a', '2026-06-27 13:51:50', 1, '2026-06-27 12:51:50'),
(3, 'testh7@test.com', '7660c14847b4d5f7859c92bae9a8aa48127813ac430e136609b9759177821203', '2026-06-28 12:54:32', 1, '2026-06-28 11:54:32'),
(4, 'roger@gmail.com', '135d34670325fd5904d277b86f7d1e26082b7daf571ece23ffebf9339638a7f0', '2026-07-01 23:48:03', 1, '2026-07-01 22:48:03'),
(5, 'yana@gmail.com', 'e3ea3c7d642d4eb6e1303f9fdddd22572951f1830346df66b9c8cb5fa0e50f6e', '2026-07-07 13:58:35', 1, '2026-07-07 12:58:35'),
(6, 'yana@gmail.com', '24168645cdd9a6d2cdb39856f9d797c21f731203eaf38d02080d678445659d94', '2026-07-07 13:58:47', 1, '2026-07-07 12:58:47'),
(7, 'yana@gmail.com', '0b9038ce26cea7ffe6336c712c1d072ee5fc214dabdbb610c9f76fa7a30b3ad8', '2026-07-07 13:59:48', 1, '2026-07-07 12:59:48'),
(8, 'budi@test.com', 'dc1d5f096472bf4e56b0e3bace4eca0e91d163010764bc14fa64961f6b7d0260', '2026-07-07 14:00:08', 0, '2026-07-07 13:00:08'),
(9, 'budi@test.com', '9c6ee36f024db8749c8a52fcd0b10ccffbc5f22854edaff317b779f1763b224d', '2026-07-07 14:00:20', 0, '2026-07-07 13:00:20'),
(10, 'yelvi@gmail.com', 'd80df30d5b4cd8e854184f3af04f743b9e82fd417cff2449501618d69960d239', '2026-07-07 14:00:34', 0, '2026-07-07 13:00:34'),
(11, 'yelvi@gmail.com', '215afa0d9493b4c1d946159026914841c570c161c89c56be340f1ca194a07b55', '2026-07-07 14:00:46', 0, '2026-07-07 13:00:46'),
(15, 'yana@gmail.com', '38f1197369db360d22891c3ec343681868d4fdd8c61bf4101fb25358ecafd56e', '2026-07-07 14:23:13', 1, '2026-07-07 13:23:13'),
(16, 'yana@gmail.com', '5d57fa314fb1754194a9d804c27e1903b86daac0a8b388dc5621c15775e6f8cd', '2026-07-07 14:27:26', 1, '2026-07-07 13:27:26');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id_pembayaran` int(11) NOT NULL,
  `id_sewa` int(11) NOT NULL,
  `bulan_ke` int(11) NOT NULL,
  `tanggal_bayar` date DEFAULT NULL,
  `tanggal_jatuh_tempo` date DEFAULT NULL,
  `jumlah_bayar` decimal(10,0) NOT NULL,
  `denda_per_hari` int(11) DEFAULT 0,
  `total_denda` int(11) DEFAULT 0,
  `bukti_bayar` varchar(255) DEFAULT NULL,
  `kode_transaksi` varchar(50) DEFAULT NULL,
  `status` enum('belum_bayar','menunggu_verifikasi','lunas') DEFAULT 'belum_bayar',
  `keterangan` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pembayaran`
--

INSERT INTO `pembayaran` (`id_pembayaran`, `id_sewa`, `bulan_ke`, `tanggal_bayar`, `tanggal_jatuh_tempo`, `jumlah_bayar`, `denda_per_hari`, `total_denda`, `bukti_bayar`, `kode_transaksi`, `status`, `keterangan`, `created_at`) VALUES
(1, 1, 1, '2026-06-22', NULL, 900000, 0, 0, '1782123779_1247c2226457e60f7596.jpeg', NULL, 'lunas', 'oke\r\n', '2026-06-22 17:20:01'),
(2, 1, 2, '2026-06-22', NULL, 900000, 0, 0, '1782127830_87eca643d25b6b1487a9.jpeg', NULL, 'lunas', '', '2026-06-22 17:20:01'),
(3, 1, 3, '2026-06-22', NULL, 900000, 0, 0, '1782127830_87eca643d25b6b1487a9.jpeg', NULL, 'lunas', '', '2026-06-22 17:20:01'),
(4, 1, 4, NULL, NULL, 900000, 0, 0, NULL, NULL, 'belum_bayar', NULL, '2026-06-22 17:20:01'),
(5, 2, 1, '2026-06-22', NULL, 700000, 0, 0, '1782136575_30a123b1db89fca9f865.jpeg', NULL, 'lunas', '', '2026-06-22 20:55:34'),
(6, 10, 2, NULL, NULL, 600000, 0, 0, NULL, NULL, 'belum_bayar', NULL, '2026-06-22 20:55:34'),
(7, 9, 3, NULL, NULL, 700000, 0, 0, NULL, NULL, 'lunas', '', '2026-06-22 20:55:34'),
(8, 4, 1, NULL, '2026-05-01', 500000, 10000, 670000, NULL, NULL, 'belum_bayar', 'Sewa bulan ke-1 (TEST DENDA)', '2026-06-27 11:54:16'),
(9, 4, 0, '2026-04-28', NULL, 1000000, 0, 0, NULL, NULL, 'lunas', 'Deposit (TEST)', '2026-06-27 11:54:16'),
(10, 4, 2, NULL, '2026-06-01', 500000, 10000, 360000, NULL, NULL, 'belum_bayar', 'Sewa bulan ke-2 (TEST DENDA)', '2026-06-27 12:50:11'),
(16, 10, 0, '2026-06-28', NULL, 1200000, 0, 0, '1782627096_3f8043faf64eda903ad2.png', NULL, 'lunas', '', '2026-06-28 13:10:27'),
(17, 11, 0, '2026-06-28', NULL, 1800000, 0, 0, '1782631136_7dee3a5d1556ef5bda4d.png', NULL, 'lunas', '', '2026-06-28 14:17:52'),
(18, 11, 1, NULL, '2026-08-03', 900000, 0, 0, NULL, NULL, 'belum_bayar', 'Sewa bulan ke-1', '2026-06-28 14:29:19'),
(19, 11, 2, NULL, '2026-09-03', 900000, 0, 0, NULL, NULL, 'belum_bayar', 'Sewa bulan ke-2', '2026-06-28 14:29:19'),
(20, 11, 3, NULL, '2026-10-03', 900000, 0, 0, NULL, NULL, 'belum_bayar', 'Sewa bulan ke-3', '2026-06-28 14:29:19'),
(21, 11, 4, NULL, '2026-11-03', 900000, 0, 0, NULL, NULL, 'belum_bayar', 'Sewa bulan ke-4', '2026-06-28 14:29:19'),
(22, 11, 5, NULL, '2026-12-03', 900000, 0, 0, NULL, NULL, 'belum_bayar', 'Sewa bulan ke-5', '2026-06-28 14:29:19'),
(23, 11, 6, NULL, '2027-01-03', 900000, 0, 0, NULL, NULL, 'belum_bayar', 'Sewa bulan ke-6', '2026-06-28 14:29:19'),
(24, 11, 7, NULL, '2027-02-03', 900000, 0, 0, NULL, NULL, 'belum_bayar', 'Sewa bulan ke-7', '2026-06-28 14:29:19'),
(25, 11, 8, NULL, '2027-03-03', 900000, 0, 0, NULL, NULL, 'belum_bayar', 'Sewa bulan ke-8', '2026-06-28 14:29:19'),
(26, 11, 9, NULL, '2027-04-03', 900000, 0, 0, NULL, NULL, 'belum_bayar', 'Sewa bulan ke-9', '2026-06-28 14:29:19'),
(27, 11, 10, NULL, '2027-05-03', 900000, 0, 0, NULL, NULL, 'belum_bayar', 'Sewa bulan ke-10', '2026-06-28 14:29:19'),
(28, 11, 11, NULL, '2027-06-03', 900000, 0, 0, NULL, NULL, 'belum_bayar', 'Sewa bulan ke-11', '2026-06-28 14:29:19'),
(29, 11, 12, NULL, '2027-07-03', 900000, 0, 0, NULL, NULL, 'belum_bayar', 'Sewa bulan ke-12', '2026-06-28 14:29:19'),
(30, 11, 13, NULL, '2027-08-03', 900000, 0, 0, NULL, NULL, 'belum_bayar', 'Sewa bulan ke-13', '2026-06-28 14:29:19'),
(31, 11, 14, NULL, '2027-09-03', 900000, 0, 0, NULL, NULL, 'belum_bayar', 'Sewa bulan ke-14', '2026-06-28 14:29:19'),
(32, 11, 15, NULL, '2027-10-03', 900000, 0, 0, NULL, NULL, 'belum_bayar', 'Sewa bulan ke-15', '2026-06-28 14:29:19'),
(33, 11, 16, NULL, '2027-11-03', 900000, 0, 0, NULL, NULL, 'belum_bayar', 'Sewa bulan ke-16', '2026-06-28 14:29:19'),
(34, 11, 17, NULL, '2027-12-03', 900000, 0, 0, NULL, NULL, 'belum_bayar', 'Sewa bulan ke-17', '2026-06-28 14:29:19'),
(35, 11, 18, NULL, '2028-01-03', 900000, 0, 0, NULL, NULL, 'belum_bayar', 'Sewa bulan ke-18', '2026-06-28 14:29:19'),
(36, 11, 19, NULL, '2028-02-03', 900000, 0, 0, NULL, NULL, 'belum_bayar', 'Sewa bulan ke-19', '2026-06-28 14:29:19'),
(37, 11, 20, NULL, '2028-03-03', 900000, 0, 0, NULL, NULL, 'belum_bayar', 'Sewa bulan ke-20', '2026-06-28 14:29:19'),
(38, 11, 21, NULL, '2028-04-03', 900000, 0, 0, NULL, NULL, 'belum_bayar', 'Sewa bulan ke-21', '2026-06-28 14:29:19'),
(39, 11, 22, NULL, '2028-05-03', 900000, 0, 0, NULL, NULL, 'belum_bayar', 'Sewa bulan ke-22', '2026-06-28 14:29:19'),
(40, 11, 23, NULL, '2028-06-03', 900000, 0, 0, NULL, NULL, 'belum_bayar', 'Sewa bulan ke-23', '2026-06-28 14:29:19'),
(41, 11, 24, NULL, '2028-07-03', 900000, 0, 0, NULL, NULL, 'belum_bayar', 'Sewa bulan ke-24', '2026-06-28 14:29:19'),
(43, 10, 3, NULL, '2027-05-28', 600000, 0, 0, NULL, NULL, 'belum_bayar', 'Sewa bulan ke-3 (Perpanjangan)', '2026-06-28 20:33:38'),
(44, 10, 4, NULL, '2027-06-28', 600000, 0, 0, NULL, NULL, 'belum_bayar', 'Sewa bulan ke-4 (Perpanjangan)', '2026-06-28 20:33:38'),
(45, 10, 5, NULL, '2027-07-28', 600000, 0, 0, NULL, NULL, 'belum_bayar', 'Sewa bulan ke-5 (Perpanjangan)', '2026-06-28 20:44:14'),
(46, 10, 6, NULL, '2027-08-28', 600000, 0, 0, NULL, NULL, 'belum_bayar', 'Sewa bulan ke-6 (Perpanjangan)', '2026-06-28 20:44:14'),
(47, 10, 7, NULL, '2027-09-28', 600000, 0, 0, NULL, NULL, 'belum_bayar', 'Sewa bulan ke-7 (Perpanjangan)', '2026-06-28 20:44:36'),
(48, 10, 8, NULL, '2027-10-28', 600000, 0, 0, NULL, NULL, 'belum_bayar', 'Sewa bulan ke-8 (Perpanjangan)', '2026-06-28 20:46:17'),
(49, 14, 0, '2026-06-29', '2026-07-02', 1500000, 0, 0, '1782710353_65e0612b264e3988c716.png', NULL, 'lunas', 'Selamat! Pembayaran deposit Anda sebesar Rp 1.500.000 telah berhasil diverifikasi. Deposit Anda siap digunakan. Admin akan segera memproses pengajuan sewa Anda. Terima kasih telah mempercayai Rumah Kos kami. 🙏', '2026-06-29 09:28:51'),
(50, 14, 1, '2026-06-29', '2026-07-28', 750000, 0, 0, '1782716562_edc70438470f55d257b2.png', NULL, 'lunas', 'Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏', '2026-06-29 13:56:58'),
(51, 14, 2, '2026-06-29', '2026-08-28', 750000, 0, 0, '1782716562_edc70438470f55d257b2.png', NULL, 'lunas', 'Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏', '2026-06-29 13:56:58'),
(52, 14, 3, '2026-06-29', '2026-09-28', 750000, 0, 0, '1782716562_edc70438470f55d257b2.png', NULL, 'lunas', 'Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏', '2026-06-29 13:56:58'),
(53, 14, 4, '2026-06-29', '2026-10-28', 750000, 0, 0, '1782716562_edc70438470f55d257b2.png', NULL, 'lunas', 'Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏', '2026-06-29 13:56:58'),
(54, 14, 5, '2026-06-29', '2026-11-28', 750000, 0, 0, '1782716562_edc70438470f55d257b2.png', NULL, 'lunas', 'Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏', '2026-06-29 13:56:58'),
(55, 14, 6, '2026-06-29', '2026-12-28', 750000, 0, 0, '1782716562_edc70438470f55d257b2.png', NULL, 'lunas', 'Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏', '2026-06-29 13:56:58'),
(56, 14, 7, '2026-06-29', '2027-01-28', 750000, 0, 0, '1782716562_edc70438470f55d257b2.png', NULL, 'lunas', 'Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏', '2026-06-29 13:56:58'),
(57, 40, 8, NULL, '2027-02-28', 600000, 0, 0, NULL, NULL, 'belum_bayar', 'Sewa bulan ke-8', '2026-06-29 13:56:58'),
(58, 40, 9, NULL, '2027-03-28', 600000, 0, 0, NULL, NULL, 'belum_bayar', 'Sewa bulan ke-9', '2026-06-29 13:56:58'),
(59, 40, 10, NULL, '2027-04-28', 600000, 0, 0, NULL, NULL, 'belum_bayar', 'Sewa bulan ke-10', '2026-06-29 13:56:58'),
(60, 40, 11, NULL, '2027-05-28', 600000, 0, 0, NULL, NULL, 'belum_bayar', 'Sewa bulan ke-11', '2026-06-29 13:56:58'),
(61, 40, 12, NULL, '2027-06-28', 600000, 0, 0, NULL, NULL, 'belum_bayar', 'Sewa bulan ke-12', '2026-06-29 13:56:58'),
(62, 16, 0, '2026-06-29', NULL, 750000, 0, 0, '1782720341_70118e1b7e756279a662.png', NULL, 'lunas', 'Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏', '2026-06-29 14:17:52'),
(63, 16, 0, '2026-06-29', NULL, 300000, 0, 0, '1782720341_70118e1b7e756279a662.png', NULL, 'lunas', 'Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏', '2026-06-29 15:03:57'),
(70, 23, 0, '2026-06-29', '2026-07-02', 1400000, 0, 0, '1782741059_bf147a08120927338549.png', NULL, 'lunas', 'Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏', '2026-06-29 20:48:41'),
(71, 23, 1, '2026-06-29', '2026-07-30', 700000, 0, 0, '1782741403_7fd1f03e4684b67886aa.png', NULL, 'lunas', 'Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏', '2026-06-29 20:51:54'),
(72, 23, 2, '2026-06-29', '2026-08-30', 700000, 0, 0, '1782741403_7fd1f03e4684b67886aa.png', NULL, 'lunas', 'Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏', '2026-06-29 20:51:54'),
(73, 23, 3, '2026-06-29', '2026-09-30', 700000, 0, 0, '1782741403_7fd1f03e4684b67886aa.png', NULL, 'lunas', 'Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏', '2026-06-29 20:51:54'),
(74, 23, 4, '2026-06-29', '2026-10-30', 700000, 0, 0, '1782741403_7fd1f03e4684b67886aa.png', NULL, 'lunas', 'Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏', '2026-06-29 20:51:54'),
(75, 23, 5, '2026-06-29', '2026-11-30', 700000, 0, 0, '1782741403_7fd1f03e4684b67886aa.png', NULL, 'lunas', 'Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏', '2026-06-29 20:51:54'),
(76, 23, 6, '2026-06-29', '2026-12-30', 700000, 0, 0, '1782741403_7fd1f03e4684b67886aa.png', NULL, 'lunas', 'Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏', '2026-06-29 20:51:54'),
(77, 23, 7, '2026-06-29', '2027-01-30', 700000, 0, 0, '1782741403_7fd1f03e4684b67886aa.png', NULL, 'lunas', 'Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏', '2026-06-29 20:51:54'),
(78, 23, 8, '2026-06-29', '2027-03-02', 700000, 0, 0, '1782741403_7fd1f03e4684b67886aa.png', NULL, 'lunas', 'Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏', '2026-06-29 20:51:54'),
(79, 23, 9, '2026-06-29', '2027-03-30', 700000, 0, 0, '1782741403_7fd1f03e4684b67886aa.png', NULL, 'lunas', 'Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏', '2026-06-29 20:51:54'),
(80, 23, 10, '2026-06-29', '2027-04-30', 700000, 0, 0, '1782741403_7fd1f03e4684b67886aa.png', NULL, 'lunas', 'Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏', '2026-06-29 20:51:54'),
(81, 23, 11, '2026-06-29', '2027-05-30', 700000, 0, 0, '1782741403_7fd1f03e4684b67886aa.png', NULL, 'lunas', 'Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏', '2026-06-29 20:51:54'),
(82, 23, 12, '2026-06-29', '2027-06-30', 700000, 0, 0, '1782741403_7fd1f03e4684b67886aa.png', NULL, 'lunas', 'Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏', '2026-06-29 20:51:54'),
(118, 38, 0, '2026-07-03', '2026-07-06', 1400000, 0, 0, '1783069309_55a0047e3af875a22860.png', NULL, 'lunas', 'Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏', '2026-07-03 16:01:34'),
(119, 38, 1, NULL, '2026-08-03', 700000, 0, 0, NULL, NULL, 'belum_bayar', 'Sewa bulan ke-1', '2026-07-03 16:07:32'),
(120, 38, 2, NULL, '2026-09-03', 700000, 0, 0, NULL, NULL, 'belum_bayar', 'Sewa bulan ke-2', '2026-07-03 16:07:32'),
(121, 38, 3, NULL, '2026-10-03', 700000, 0, 0, NULL, NULL, 'belum_bayar', 'Sewa bulan ke-3', '2026-07-03 16:07:32'),
(156, 50, 0, '2026-07-06', '2026-07-09', 1600000, 0, 0, '1783326817_7f3b11f5c74e44f29641.png', NULL, 'lunas', 'Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏', '2026-07-06 15:32:55'),
(157, 51, 1, '2026-07-06', '2026-08-05', 1500000, 0, 0, '1783326987_efdf9442137adacb3b70.png', NULL, 'lunas', 'Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏', '2026-07-06 15:34:42'),
(158, 51, 2, '2026-07-06', '2026-09-05', 1500000, 0, 0, '1783326987_efdf9442137adacb3b70.png', NULL, 'lunas', 'Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏', '2026-07-06 15:34:42'),
(159, 51, 3, NULL, '2026-10-06', 1500000, 0, 0, NULL, NULL, 'belum_bayar', 'Sewa bulan ke-3 (Perpanjangan)', '2026-07-06 15:37:26'),
(160, 51, 4, NULL, '2026-11-06', 1500000, 0, 0, NULL, NULL, 'belum_bayar', 'Sewa bulan ke-4 (Perpanjangan)', '2026-07-06 15:37:26'),
(161, 51, -1, '2026-07-06', NULL, 1400000, 0, 0, '1783327294_841c62f7cb3443f16564.png', NULL, 'lunas', 'Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏', '2026-07-06 15:40:21'),
(162, 51, 0, '2026-07-06', NULL, 1400000, 0, 0, '1783327294_841c62f7cb3443f16564.png', NULL, 'lunas', 'Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏', '2026-07-06 15:40:21'),
(171, 55, 0, '2026-07-06', '2026-07-09', 1400000, 0, 0, '1783339883_ad5b563b642869206b4d.png', NULL, 'lunas', 'Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏', '2026-07-06 19:10:54'),
(172, 56, 1, '2026-07-06', '2026-08-05', 800000, 0, 0, '1783340084_522735ace742ce02d649.png', NULL, 'lunas', 'Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏', '2026-07-06 19:12:25'),
(173, 56, 2, '2026-07-06', '2026-09-05', 800000, 0, 0, '1783340084_522735ace742ce02d649.png', NULL, 'lunas', 'Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏', '2026-07-06 19:12:25'),
(174, 56, 3, NULL, '2026-10-06', 800000, 0, 0, NULL, NULL, 'belum_bayar', 'Sewa bulan ke-3 (Perpanjangan)', '2026-07-06 19:16:19'),
(175, 56, 4, NULL, '2026-11-06', 800000, 0, 0, NULL, NULL, 'belum_bayar', 'Sewa bulan ke-4 (Perpanjangan)', '2026-07-06 19:16:19'),
(176, 56, -1, NULL, NULL, 200000, 0, 0, NULL, NULL, 'belum_bayar', 'Selisih Sewa 2 bulan (kamar baru lebih mahal)', '2026-07-06 19:17:54'),
(177, 56, 0, '2026-07-06', NULL, 200000, 0, 0, '1783340332_f2e564ad2e78a665026f.png', NULL, 'lunas', 'Halo, pembayaran Anda telah kami verifikasi dan berstatus LUNAS. Nominal sesuai dengan tagihan, bukti transfer valid. Terima kasih telah membayar tepat waktu. 🙏', '2026-07-06 19:17:54');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengajuan_checkout`
--

CREATE TABLE `pengajuan_checkout` (
  `id_checkout` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_sewa` int(11) NOT NULL,
  `id_kamar` int(11) NOT NULL,
  `tanggal_checkout_diajukan` date NOT NULL,
  `alasan` text NOT NULL,
  `status` enum('menunggu','inspeksi','disetujui','ditolak') DEFAULT 'menunggu',
  `tanggal_proses` date DEFAULT NULL,
  `keterangan_admin` text DEFAULT NULL,
  `bukti_refund` varchar(255) DEFAULT NULL,
  `tanggal_refund` date DEFAULT NULL,
  `total_refund` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengajuan_checkout`
--

INSERT INTO `pengajuan_checkout` (`id_checkout`, `id_user`, `id_sewa`, `id_kamar`, `tanggal_checkout_diajukan`, `alasan`, `status`, `tanggal_proses`, `keterangan_admin`, `bukti_refund`, `tanggal_refund`, `total_refund`, `created_at`) VALUES
(2, 8, 13, 4, '2026-06-29', 'INGINI PINDAH KOTA', 'disetujui', '2026-06-29', 'Kamar dalam kondisi bersih dan terawat. Tidak ada kerusakan. Kunci lengkap. | Potongan: Rp 0', '1782724064_95262c679de0f225e23a.png', '2026-06-29', 6300000, '2026-06-29 15:59:35'),
(3, 18, 26, 12, '2026-06-30', 'PINDAH', 'disetujui', '2026-06-30', 'Kamar dalam kondisi bersih dan terawat. Tidak ada kerusakan. Kunci lengkap. | Potongan: Rp 0 | Tagihan dibatalkan: 1 tagihan', '1782797092_b8ef06199b1de6e74e72.png', '2026-06-30', 3200000, '2026-06-30 11:51:17'),
(4, 22, 32, 13, '2026-07-02', 'mau pindah kota\r\n', 'disetujui', '2026-07-02', 'Kamar dalam kondisi bersih dan terawat. Tidak ada kerusakan. Kunci lengkap. | Early Checkout -50% Deposit: Rp 800.000 | Potongan Kerusakan: Rp 0', '1783001496_b00ee41d0843841aeb85.png', '2026-07-02', 800000, '2026-07-02 21:09:29'),
(5, 18, 28, 6, '2026-07-02', 'mau pindah alam', 'disetujui', '2026-07-02', 'Kamar dalam kondisi bersih dan terawat. Tidak ada kerusakan. Kunci lengkap. | Early Checkout -50% Deposit: Rp 600.000 | Potongan Kerusakan: Rp 0', '1783003015_fcff2c90d2c37ac8b3bd.png', '2026-07-02', 600000, '2026-07-02 21:36:04'),
(6, 23, 35, 12, '2026-07-03', 'pindah\r\n', 'disetujui', '2026-07-03', 'Kamar dalam kondisi bersih dan terawat. Tidak ada kerusakan. Kunci lengkap. | Early Checkout -50% Deposit: Rp 800.000 | Potongan Kerusakan: Rp 0', '1783065662_c7d0111bbb2d1e53359f.png', '2026-07-03', 2000000, '2026-07-03 15:00:19'),
(7, 13, 39, 3, '2026-07-03', 'mau pindah kota', 'ditolak', '2026-07-03', 'DITOLAK: Pengajuan check-out ditolak oleh admin.', NULL, NULL, 0, '2026-07-03 17:24:32'),
(8, 13, 39, 3, '2026-07-03', 'mau pindah', 'disetujui', '2026-07-03', 'Kamar dalam kondisi bersih dan terawat. Tidak ada kerusakan. Kunci lengkap. | Early Checkout -50% Deposit: Rp 700.000 | Potongan Kerusakan: Rp 0', '1783075476_e1a731cf7c6d3ffa2065.png', '2026-07-03', 3700000, '2026-07-03 17:43:27'),
(9, 26, 42, 17, '2026-07-05', 'ingin pindah kota ', 'ditolak', '2026-07-05', 'DITOLAK: Pengajuan check-out ditolak oleh admin.', NULL, NULL, 0, '2026-07-05 14:11:01'),
(10, 26, 42, 17, '2026-07-31', 'sjchc', 'ditolak', '2026-07-05', 'DITOLAK: Pengajuan check-out ditolak oleh admin.', NULL, NULL, 0, '2026-07-05 14:15:55'),
(11, 26, 42, 17, '2026-07-30', 'pindah', 'disetujui', '2026-07-05', 'Kamar kotor dan tidak terawat. Terdapat kerusakan pada kaca jendela. Kunci lengkap. | Early Checkout -50% Deposit: Rp 1.500.000 | Potongan Kerusakan: Rp 100.000', '1783235961_f6e3a14c479b37fd9507.png', '2026-07-05', 5400000, '2026-07-05 14:18:29'),
(12, 29, 47, 17, '2026-07-06', 'di suruh pindh kota sama boss', 'disetujui', '2026-07-06', 'Kamar dalam kondisi bersih dan terawat. Tidak ada kerusakan. Kunci lengkap. | Early Checkout -50% Deposit: Rp 1.500.000 | Potongan Kerusakan: Rp 0', '1783323189_81222d9f290d7933ded0.png', '2026-07-06', 3100000, '2026-07-06 14:31:03'),
(13, 30, 49, 12, '2026-07-06', 'muau pindha', 'disetujui', '2026-07-06', 'Kamar dalam kondisi bersih dan terawat. Tidak ada kerusakan. Kunci lengkap. | Early Checkout -50% Deposit: Rp 800.000 | Potongan Kerusakan: Rp 0', '1783326348_222ff587f7f540952d47.png', '2026-07-06', 3200000, '2026-07-06 15:24:42'),
(14, 32, 53, 12, '2026-07-06', 'mzu oindah kota', 'disetujui', '2026-07-06', 'Kamar dalam kondisi bersih dan terawat. Tidak ada kerusakan. Kunci lengkap. | Early Checkout -50% Deposit: Rp 800.000 | Potongan Kerusakan: Rp 0', '1783339270_da1effbc4bd56a7919a2.png', '2026-07-06', 1600000, '2026-07-06 19:00:05'),
(15, 33, 56, 12, '2026-07-07', 'mau pindah', 'menunggu', NULL, NULL, NULL, NULL, 0, '2026-07-07 13:43:59');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengajuan_pindah`
--

CREATE TABLE `pengajuan_pindah` (
  `id_pindah` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_sewa_lama` int(11) NOT NULL,
  `id_kamar_lama` int(11) NOT NULL,
  `id_kamar_baru` int(11) NOT NULL,
  `alasan` text NOT NULL,
  `tanggal_pengajuan` date NOT NULL,
  `status` enum('menunggu','disetujui','ditolak') DEFAULT 'menunggu',
  `tanggal_proses` date DEFAULT NULL,
  `keterangan_admin` text DEFAULT NULL,
  `bukti_refund` varchar(255) DEFAULT NULL,
  `tanggal_refund` date DEFAULT NULL,
  `total_refund` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengajuan_pindah`
--

INSERT INTO `pengajuan_pindah` (`id_pindah`, `id_user`, `id_sewa_lama`, `id_kamar_lama`, `id_kamar_baru`, `alasan`, `tanggal_pengajuan`, `status`, `tanggal_proses`, `keterangan_admin`, `bukti_refund`, `tanggal_refund`, `total_refund`, `created_at`) VALUES
(5, 5, 8, 5, 3, 'pindah', '2026-06-28', 'disetujui', '2026-06-28', 'Pindah kamar disetujui. Sisa durasi 10 bulan dipindahkan ke kamar baru.', NULL, NULL, 0, '2026-06-28 12:29:50'),
(6, 5, 9, 3, 5, 'PINDAH\r\n\r\n', '2026-06-28', 'disetujui', '2026-06-28', 'Pindah kamar disetujui. Sisa durasi 10 bulan dipindahkan ke kamar baru.', NULL, NULL, 0, '2026-06-28 13:09:00'),
(7, 9, 11, 8, 4, 'ajiun pindah kamar', '2026-06-28', 'ditolak', '2026-06-28', 'DITOLAK: Pengajuan pindah kamar ditolak oleh admin.', NULL, NULL, 0, '2026-06-28 14:57:36'),
(9, 12, 14, 11, 6, 'SYA MAU PIDAH KMA YAAA\r\n', '2026-06-29', 'disetujui', '2026-06-29', 'Pindah kamar disetujui. Sisa durasi 12 bulan dipindahkan ke kamar baru.', NULL, NULL, 0, '2026-06-29 14:15:36'),
(10, 12, 15, 6, 11, 'say mau pindah kamar ya\r\n', '2026-06-29', '', '2026-06-29', 'Admin sedang inspeksi kondisi kamar lama.', NULL, NULL, 0, '2026-06-29 14:34:33'),
(11, 12, 15, 6, 11, 'pindh kamar ya\r\n', '2026-06-29', '', '2026-06-29', 'Admin sedang inspeksi kondisi kamar lama.', NULL, NULL, 0, '2026-06-29 14:48:15'),
(12, 12, 15, 6, 9, 'pindah\r\n', '2026-06-29', '', '2026-06-29', 'Admin sedang inspeksi kondisi kamar lama.', NULL, NULL, 0, '2026-06-29 14:51:05'),
(13, 12, 15, 6, 11, 'pindh\r\n', '2026-06-29', 'disetujui', '2026-06-29', 'Kamar lama dalam kondisi bersih dan terawat. Tidak ada kerusakan. Kunci lengkap. | Potongan: Rp 0 | Deposit dipindah: Rp 1.200.000 | User bayar selisih: Rp 300.000', NULL, NULL, 0, '2026-06-29 14:59:07'),
(14, 17, 23, 3, 6, 'pindah\r\n', '2026-06-29', 'ditolak', '2026-06-29', 'DITOLAK: Pengajuan pindah kamar ditolak oleh admin.', NULL, NULL, 0, '2026-06-29 20:58:02'),
(15, 17, 23, 3, 6, 'pindah kamar', '2026-06-29', 'ditolak', '2026-06-29', 'DITOLAK: Pengajuan pindah kamar ditolak oleh admin.', NULL, NULL, 0, '2026-06-29 21:00:31'),
(16, 17, 23, 3, 4, 'saya mau pindah kamar\r\n', '2026-06-29', 'ditolak', '2026-06-29', 'DITOLAK: Pengajuan pindah kamar ditolak oleh admin.', NULL, NULL, 0, '2026-06-29 21:15:41'),
(17, 17, 23, 3, 2, 'pindah lmaar', '2026-06-29', 'disetujui', '2026-06-29', 'Kamar lama dalam kondisi bersih dan terawat. Tidak ada kerusakan. Kunci lengkap. | Potongan: Rp 0 | Deposit dipindah: Rp 1.400.000', NULL, NULL, 0, '2026-06-29 21:17:23'),
(21, 12, 16, 11, 4, 'pindah', '2026-07-01', 'ditolak', '2026-07-01', 'DITOLAK: Tidak memenuhi syarat', NULL, NULL, 0, '2026-07-01 22:12:15'),
(25, 12, 16, 11, 6, 'ingin oindah', '2026-07-03', 'disetujui', '2026-07-03', 'Kamar lama dalam kondisi bersih dan terawat. Tidak ada kerusakan. Kunci lengkap. | Potongan: Rp 0 | Refund ke User: Rp 300.000', '1783076225_42db358a9d0f90cdb697.png', '2026-07-03', 300000, '2026-07-03 17:56:16'),
(29, 31, 50, 12, 17, 'mau pindah', '2026-07-06', 'disetujui', '2026-07-06', 'Kamar lama dalam kondisi bersih dan terawat. Tidak ada kerusakan. Kunci lengkap. | Potongan: Rp 0 | Refund ke User: Rp 0', NULL, '2026-07-06', 0, '2026-07-06 15:39:27'),
(31, 33, 55, 3, 12, 'mau pindah kamar', '2026-07-06', 'disetujui', '2026-07-06', 'Kamar lama dalam kondisi bersih dan terawat. Tidak ada kerusakan. Kunci lengkap. | Potongan: Rp 0 | Refund ke User: Rp 0', NULL, '2026-07-06', 0, '2026-07-06 19:16:54');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengaturan`
--

CREATE TABLE `pengaturan` (
  `id_setting` int(11) NOT NULL,
  `kunci` varchar(50) NOT NULL,
  `nilai` varchar(255) NOT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengaturan`
--

INSERT INTO `pengaturan` (`id_setting`, `kunci`, `nilai`, `keterangan`) VALUES
(1, 'denda_per_hari', '10000', 'Denda keterlambatan per hari (Rp)'),
(2, 'notif_hari_kontrak', '1', 'Notifikasi H-X sebelum kontrak habis'),
(6, 'automation_last_run', '2026-07-07', NULL),
(7, 'interval_denda_hari', '7', NULL),
(8, 'default_deposit_kali', '2', 'Default berapa kali harga sewa untuk deposit'),
(11, 'batas_tanggal_bayar', '5', 'Tanggal jatuh tempo default setiap bulan (1-31)'),
(12, 'durasi_minimal', '1', NULL),
(13, 'durasi_maksimal', '36', NULL),
(20, 'bank_name_1', 'BNI', NULL),
(21, 'bank_account_1', '123141523', NULL),
(22, 'bank_holder_1', 'admin kos', NULL),
(23, 'bank_name_2', 'BCA', NULL),
(24, 'bank_account_2', '8070123123', NULL),
(25, 'bank_holder_2', 'admin kos', NULL),
(26, 'ewallet_dana', '085262532197', NULL),
(27, 'ewallet_ovo', '085262532197', NULL),
(28, 'ewallet_gopay', '085262532197', NULL),
(29, 'ewallet_shopeepay', '', NULL),
(30, 'payment_instructions', '', NULL),
(31, 'nama_kos', 'Rumah Kos', NULL),
(32, 'tagline', '', NULL),
(33, 'alamat', '', NULL),
(34, 'email_kos', 'inforumahkos2@gmail.com', NULL),
(35, 'telepon_kos', '085264532197', NULL),
(36, 'wa_admin', '085264532197', NULL),
(37, 'facebook', '', NULL),
(38, 'instagram', '', NULL),
(39, 'tiktok', '', NULL),
(40, 'youtube', '', NULL),
(41, 'jam_operasional', '08:00 - 17:00 WIB', NULL),
(42, 'maps_embed', '', NULL),
(43, 'maps_link', '', NULL),
(44, 'footer_text', '', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengumuman`
--

CREATE TABLE `pengumuman` (
  `id_pengumuman` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `isi` text NOT NULL,
  `waktu_mulai` datetime DEFAULT NULL,
  `waktu_selesai` datetime DEFAULT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `target` varchar(20) DEFAULT 'semua',
  `created_by` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengumuman`
--

INSERT INTO `pengumuman` (`id_pengumuman`, `judul`, `isi`, `waktu_mulai`, `waktu_selesai`, `tanggal_mulai`, `tanggal_selesai`, `status`, `target`, `created_by`, `created_at`) VALUES
(8, 'Pengumuman Pemadaman Air', 'Diberitahukan kepada seluruh penghuni kos bahwa akan ada pemadaman air sementara.\r\n\r\nMohon maaf atas ketidaknyamanannya. Pastikan Anda sudah menampung air untuk kebutuhan sejenak.\r\n\r\nTerima kasih atas pengertiannya.', '2026-07-03 18:10:00', '2026-07-10 19:04:00', '2026-07-03', '2026-07-04', 'aktif', 'penghuni_aktif', 1, '2026-07-03 18:05:58'),
(9, 'Pengumuman Pemadaman Air', 'Diberitahukan kepada seluruh penghuni kos bahwa akan ada pemadaman air sementara.\r\n\r\nMohon maaf atas ketidaknyamanannya. Pastikan Anda sudah menampung air untuk kebutuhan sejenak.\r\n\r\nTerima kasih atas pengertiannya.', '2026-07-06 15:46:00', '2026-07-06 17:00:00', '2026-07-06', '2026-07-07', 'aktif', 'penghuni_aktif', 1, '2026-07-06 15:47:58');

-- --------------------------------------------------------

--
-- Struktur dari tabel `peraturan`
--

CREATE TABLE `peraturan` (
  `id_peraturan` int(11) NOT NULL,
  `judul` varchar(200) NOT NULL,
  `isi` text NOT NULL,
  `kategori` varchar(50) DEFAULT 'umum',
  `urutan` int(11) DEFAULT 0,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `peraturan`
--

INSERT INTO `peraturan` (`id_peraturan`, `judul`, `isi`, `kategori`, `urutan`, `status`, `created_at`) VALUES
(1, 'Jam Malam', 'Pintu gerbang kos ditutup pada pukul 22:00 WIB dan dibuka kembali pukul 05:00 WIB. Penghuni yang pulang melebihi jam malam harap hubungi penjaga kos.', 'jam_operasional', 1, 'aktif', '2026-06-26 09:52:25'),
(2, 'Dilarang Membawa Hewan', 'Penghuni dilarang membawa hewan peliharaan ke dalam area kos untuk menjaga kebersihan dan kenyamanan bersama.', 'larangan', 1, 'aktif', '2026-06-26 09:52:25'),
(3, 'Pembayaran Tepat Waktu', 'Tagihan sewa harus dibayar paling lambat tanggal 5 setiap bulan. Keterlambatan akan dikenakan denda sesuai pengaturan sistem.', 'pembayaran', 1, 'aktif', '2026-06-26 09:52:25'),
(4, 'Tamu Berkunjung', 'Tamu diperbolehkan berkunjung maksimal sampai pukul 21:00 WIB. Tamu tidak diperbolehkan menginap tanpa izin admin.', 'tamu', 1, 'aktif', '2026-06-26 09:52:25'),
(5, 'Kebersihan Kamar', 'Penghuni wajib menjaga kebersihan kamar masing-masing. Sampah dilarang dibuang di lorong atau area umum.', 'fasilitas', 1, 'aktif', '2026-06-26 09:52:25'),
(6, 'Keamanan Barang', 'Penghuni disarankan mengunci pintu kamar saat keluar. Admin tidak bertanggung jawab atas kehilangan barang di kamar.', 'keamanan', 1, 'aktif', '2026-06-26 09:52:25'),
(7, 'Dilarang Merokok', 'Dilarang merokok di dalam kamar dan area umum kos. Area merokok khusus tersedia di halaman belakang.', 'larangan', 2, 'aktif', '2026-06-26 09:52:25'),
(8, 'Hemat Listrik & Air', 'Penghuni wajib mematikan AC, lampu, dan kran air saat tidak digunakan untuk menghemat energi.', 'fasilitas', 2, 'aktif', '2026-06-26 09:52:25');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sewa`
--

CREATE TABLE `sewa` (
  `id_sewa` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_kamar` int(11) NOT NULL,
  `tanggal_pengajuan` date NOT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `durasi_bulan` int(11) DEFAULT 1,
  `deposit` int(11) DEFAULT 0,
  `status_kunci` enum('belum_siap','siap_diambil','sudah_diambil','sudah_dikembalikan') DEFAULT 'belum_siap',
  `tanggal_ambil_kunci` datetime DEFAULT NULL,
  `lokasi_ambil_kunci` varchar(200) DEFAULT NULL,
  `deposit_dikembalikan` int(11) DEFAULT 0,
  `status` enum('menunggu','disetujui','ditolak','aktif','selesai') DEFAULT 'menunggu',
  `keterangan` text DEFAULT NULL,
  `bukti_refund` varchar(255) DEFAULT NULL,
  `tanggal_refund` date DEFAULT NULL,
  `total_refund` decimal(12,2) DEFAULT 0.00,
  `refund_status` enum('tidak_ada','menunggu','selesai') DEFAULT 'tidak_ada',
  `refund_metode` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `sewa`
--

INSERT INTO `sewa` (`id_sewa`, `id_user`, `id_kamar`, `tanggal_pengajuan`, `tanggal_mulai`, `tanggal_selesai`, `durasi_bulan`, `deposit`, `status_kunci`, `tanggal_ambil_kunci`, `lokasi_ambil_kunci`, `deposit_dikembalikan`, `status`, `keterangan`, `bukti_refund`, `tanggal_refund`, `total_refund`, `refund_status`, `refund_metode`, `created_at`) VALUES
(1, 4, 7, '2026-06-22', '2026-06-22', '2026-10-22', 4, 0, 'sudah_diambil', '2026-06-22 00:00:00', NULL, 0, 'aktif', 'mau sewa kos ya\r\n', NULL, NULL, 0.00, 'tidak_ada', NULL, '2026-06-22 17:18:13'),
(2, 5, 4, '2026-06-22', '2026-06-22', '2026-06-23', 3, 0, 'belum_siap', NULL, NULL, 0, 'selesai', 'mau sewa', NULL, NULL, 0.00, 'tidak_ada', NULL, '2026-06-22 20:55:03'),
(3, 5, 8, '2026-06-23', '2026-06-23', '2026-06-28', 3, 0, 'belum_siap', NULL, NULL, 0, 'selesai', 'Pindah kamar dari sewa #2', NULL, NULL, 0.00, 'tidak_ada', NULL, '2026-06-23 11:15:17'),
(4, 7, 1, '2026-04-01', '2026-04-01', '2027-02-01', 10, 1000000, 'sudah_diambil', NULL, 'Office Rumah Kos', 0, 'aktif', NULL, NULL, NULL, 0.00, 'tidak_ada', NULL, '2026-06-27 11:54:16'),
(6, 5, 4, '2026-06-28', '2026-06-28', '2026-06-28', 8, 0, 'belum_siap', NULL, NULL, 0, 'selesai', 'Pindah kamar dari sewa #3', NULL, NULL, 0.00, 'tidak_ada', NULL, '2026-06-28 11:53:32'),
(7, 5, 8, '2026-06-28', '2026-06-28', '2026-06-28', 8, 0, 'belum_siap', NULL, NULL, 0, 'selesai', 'Pindah kamar dari sewa #6', NULL, NULL, 0.00, 'tidak_ada', NULL, '2026-06-28 12:03:20'),
(8, 5, 5, '2026-06-28', '2026-06-28', '2026-06-28', 10, 0, 'belum_siap', NULL, NULL, 0, 'selesai', 'Pindah kamar dari sewa #7', NULL, NULL, 0.00, 'tidak_ada', NULL, '2026-06-28 12:17:27'),
(9, 5, 3, '2026-06-28', '2026-06-28', '2026-06-28', 10, 0, 'belum_siap', NULL, NULL, 0, 'selesai', 'Pindah kamar dari sewa #8', NULL, NULL, 0.00, 'tidak_ada', NULL, '2026-06-28 12:30:48'),
(10, 5, 5, '2026-06-28', '2026-06-28', '2027-10-28', 16, 1200000, 'sudah_diambil', '2026-06-28 12:21:19', NULL, 0, 'aktif', 'Pindah kamar dari sewa #9', NULL, NULL, 0.00, 'tidak_ada', NULL, '2026-06-28 13:10:27'),
(11, 9, 8, '2026-06-28', '2026-07-03', '2028-07-03', 24, 1800000, 'sudah_diambil', '2026-06-28 09:34:33', 'Office Rumah Kos (Jam 08:00 - 17:00 WIB)', 0, 'aktif', 'antu segra di proses', NULL, NULL, 0.00, 'tidak_ada', NULL, '2026-06-28 14:17:52'),
(12, 10, 10, '2026-06-28', '2026-06-28', '2026-07-05', 1, 1000000, 'sudah_diambil', '2026-06-28 18:37:34', 'Office Rumah Kos', 0, 'aktif', 'DATA TESTING - kontrak sengaja diatur H-7 untuk uji notifikasi otomatis', NULL, NULL, 0.00, 'tidak_ada', NULL, '2026-06-28 18:37:34'),
(14, 12, 11, '2026-06-29', '2026-06-28', '2026-06-29', 12, 1500000, 'sudah_dikembalikan', '2026-06-29 06:58:45', 'Office Rumah Kos (Jam 08:00 - 17:00 WIB)', 0, 'selesai', 'mohon segera di proses\r\n', NULL, NULL, 0.00, 'tidak_ada', NULL, '2026-06-29 09:28:51'),
(15, 12, 6, '2026-06-29', '2026-06-29', '2026-06-29', 12, 1200000, 'sudah_dikembalikan', NULL, 'Office Rumah Kos (Jam 08:00 - 17:00 WIB)', 1200000, 'selesai', 'Pindah kamar dari sewa #14', NULL, NULL, 0.00, 'tidak_ada', NULL, '2026-06-29 14:17:52'),
(16, 12, 11, '2026-06-29', '2026-06-29', '2026-07-03', 12, 1500000, 'sudah_dikembalikan', '2026-06-29 08:06:43', 'Office Rumah Kos (Jam 08:00 - 17:00 WIB)', 1500000, 'selesai', 'Pindah kamar dari sewa #15', NULL, NULL, 0.00, 'tidak_ada', NULL, '2026-06-29 15:03:57'),
(23, 17, 3, '2026-06-29', '2026-06-30', '2026-06-29', 12, 1400000, 'sudah_dikembalikan', '2026-06-29 13:57:14', 'Office Rumah Kos (Jam 08:00 - 17:00 WIB)', 1400000, 'selesai', 'segera di proses', NULL, NULL, 0.00, 'tidak_ada', NULL, '2026-06-29 20:48:41'),
(24, 17, 2, '2026-06-29', '2026-06-29', '2027-07-29', 13, 1000000, 'sudah_diambil', '2026-06-30 03:36:28', 'Office Rumah Kos (Jam 08:00 - 17:00 WIB)', 0, 'aktif', 'Pindah kamar dari sewa #23', NULL, NULL, 0.00, 'tidak_ada', NULL, '2026-06-29 21:18:41'),
(38, 25, 4, '2026-07-03', '2026-07-03', '2026-10-03', 3, 1400000, 'sudah_diambil', '2026-07-03 17:21:00', 'Office Rumah Kos (Jam 08:00 - 17:00 WIB)', 0, 'aktif', 'mau sea', NULL, NULL, 0.00, 'tidak_ada', NULL, '2026-07-03 16:01:34'),
(40, 12, 6, '2026-07-03', '2026-07-03', '2027-07-03', 12, 1200000, 'sudah_diambil', '2026-07-03 17:59:35', 'Office Rumah Kos (Jam 08:00 - 17:00 WIB)', 0, 'aktif', 'Pindah kamar dari sewa #16', NULL, NULL, 0.00, 'tidak_ada', NULL, '2026-07-03 17:57:05'),
(50, 31, 12, '2026-07-06', '2026-07-06', '2026-07-06', 4, 1600000, 'sudah_dikembalikan', '2026-07-06 15:35:22', 'Office Rumah Kos (Jam 08:00 - 17:00 WIB)', 1600000, 'selesai', 'mau sewa', NULL, NULL, 0.00, 'tidak_ada', NULL, '2026-07-06 15:32:55'),
(51, 31, 17, '2026-07-06', '2026-07-06', '2026-11-06', 4, 3000000, 'sudah_diambil', '2026-07-06 15:46:16', 'Office Rumah Kos (Jam 08:00 - 17:00 WIB)', 0, 'aktif', 'Pindah kamar dari sewa #50', NULL, NULL, 0.00, 'tidak_ada', NULL, '2026-07-06 15:40:21'),
(54, 33, 3, '2026-07-06', '2026-07-06', '2026-09-06', 2, 0, 'belum_siap', NULL, NULL, 0, 'ditolak', 'DITOLAK: Pengajuan ditolak.', NULL, NULL, 0.00, 'tidak_ada', NULL, '2026-07-06 19:06:38'),
(55, 33, 3, '2026-07-06', '2026-07-06', '2026-07-06', 4, 1400000, 'sudah_dikembalikan', '2026-07-06 19:12:38', 'Office Rumah Kos (Jam 08:00 - 17:00 WIB)', 1400000, 'selesai', 'mau sewa', NULL, NULL, 0.00, 'tidak_ada', NULL, '2026-07-06 19:10:54'),
(56, 33, 12, '2026-07-06', '2026-07-06', '2026-11-06', 4, 1600000, 'siap_diambil', NULL, 'Office Rumah Kos (Jam 08:00 - 17:00 WIB)', 0, 'aktif', 'Pindah kamar dari sewa #55', NULL, NULL, 0.00, 'tidak_ada', NULL, '2026-07-06 19:17:54');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `nama_bank` varchar(50) DEFAULT NULL,
  `nomor_rekening` varchar(50) DEFAULT NULL,
  `nama_pemilik_rek` varchar(100) DEFAULT NULL,
  `ewallet_type` varchar(30) DEFAULT NULL,
  `ewallet_number` varchar(50) DEFAULT NULL,
  `foto` varchar(255) DEFAULT 'default.png',
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id_user`, `nama`, `email`, `username`, `password`, `no_hp`, `nama_bank`, `nomor_rekening`, `nama_pemilik_rek`, `ewallet_type`, `ewallet_number`, `foto`, `role`, `created_at`) VALUES
(1, 'Administrator', 'admin@rumahkos.com', 'admin', '$2y$10$rw5BRVUtDgc1IeM3QVkC8egfsjLVKuoRo5FXqLWTFyUmKZSgH2Y6C', '085264532197', NULL, NULL, NULL, NULL, NULL, 'default.png', 'admin', '2026-06-09 13:02:14'),
(4, 'eryanto', 'eryanto@gmail.com', 'andi', '$2y$10$DB/xaysf70Hn0j9pJMBD9uWx4K4dywSWTc8oOpngifW4IEsSNBjGW', '082312312345', NULL, NULL, NULL, NULL, NULL, 'default.png', 'user', '2026-06-09 23:07:32'),
(5, 'yandi', 'yanti@gmail.com', 'yandi', '$2y$10$OzHCy1aVgP0CwGYausdLreJeKWhnLu/iKg71SSOQslR1qd/f9WZ6G', '082316009999', NULL, NULL, NULL, NULL, NULL, 'default.png', 'user', '2026-06-22 19:34:53'),
(6, 'lala', 'yana@gmail.com', 'kiki', '321321', '085264532197', NULL, NULL, NULL, NULL, NULL, 'default.png', 'user', '2026-06-23 13:31:36'),
(7, 'Budi Test', 'budi@test.com', 'buditest', '$2y$10$z2nSTwVAcQxmZAxR3zWJ6.ArvcE.rF6o8xTD.rws5hDQbka2/UM9i', '081234567890', NULL, NULL, NULL, NULL, NULL, 'default.png', 'user', '2026-06-27 11:54:16'),
(9, 'percobaaan', 'momo@gmail.com', 'momo', '$2y$10$n0q5GMF2gLyPw8YQV8UEFu0b9vQkl7mUKZx7tqdsI.WKveOkToQMy', '082311111111', NULL, NULL, NULL, NULL, NULL, 'default.png', 'user', '2026-06-28 14:16:09'),
(10, 'Test Notif H7', 'testh7@test.com', 'testh7', '$2y$10$36CLcQjlCR7xuakCYa/Imeb.OBtdt4LVZwfcX4fjZDrvJDwnVshbC', '081200000007', NULL, NULL, NULL, NULL, NULL, 'default.png', 'user', '2026-06-28 18:37:34'),
(12, 'diki', 'diky@gmail.com', 'diky', '$2y$10$tZR2qFyRYD8AfTJr8jioo.qSgO4MG141aHNzV9i7e/WmPsRjmF0.u', '0822211212121', NULL, NULL, NULL, NULL, NULL, 'default.png', 'user', '2026-06-29 09:27:36'),
(17, 'ery', 'ery@gmail.com', 'eryan', '$2y$10$IFO5FI7VmbLtVCOTESMBgex0/pM0HcbWVD3jhKqQ/jMuoXwmOKyuC', '081234351212', NULL, NULL, NULL, NULL, NULL, 'default.png', 'user', '2026-06-29 20:47:50'),
(25, 'devi', 'devi@gmail.com', 'devi', '$2y$10$TkDNaBV/elE7VC7TFfmBdexdj1a0EA2.n6.zPz2CRb50BsRSQM86u', '085246469795', NULL, NULL, NULL, NULL, NULL, 'default.png', 'user', '2026-07-03 15:45:27'),
(31, 'roger', 'roger@gmail.com', 'roger', '$2y$10$HDmwDL0zE0TqWcnnXmK2QOlir4YBHz1n3Y5m4kak7u8brDu/C/e3e', '081245451212', 'BCA', '8070121212', 'roger', NULL, NULL, 'default.png', 'user', '2026-07-06 15:29:04'),
(33, 'yelvi', 'yelvi@gmail.com', 'yelvi', '$2y$10$yPa5Im1BNQUqqwbHj7U1/.ex87EX6lxPVzq9dSSkQlZJpZvpRIVFO', '0823151589889', 'BCA', '8070121212', 'yelvi', NULL, NULL, 'default.png', 'user', '2026-07-06 19:04:18'),
(34, 'yuki', 'yuki@gmail.com', 'yuki', '$2y$10$9wGbkJ//mowAI1/Z7T/D1.qNu8KoAcJmmZZaQuLs0hd.6EkeAqN9i', '08121245456', NULL, NULL, NULL, NULL, NULL, 'default.png', 'user', '2026-07-07 13:31:19');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `kamar`
--
ALTER TABLE `kamar`
  ADD PRIMARY KEY (`id_kamar`),
  ADD UNIQUE KEY `kode_kamar` (`kode_kamar`);

--
-- Indeks untuk tabel `keluhan`
--
ALTER TABLE `keluhan`
  ADD PRIMARY KEY (`id_keluhan`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`id_notifikasi`);

--
-- Indeks untuk tabel `password_reset`
--
ALTER TABLE `password_reset`
  ADD PRIMARY KEY (`id_reset`),
  ADD UNIQUE KEY `idx_token_unique` (`token`),
  ADD KEY `idx_token` (`token`);

--
-- Indeks untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD KEY `id_sewa` (`id_sewa`);

--
-- Indeks untuk tabel `pengajuan_checkout`
--
ALTER TABLE `pengajuan_checkout`
  ADD PRIMARY KEY (`id_checkout`);

--
-- Indeks untuk tabel `pengajuan_pindah`
--
ALTER TABLE `pengajuan_pindah`
  ADD PRIMARY KEY (`id_pindah`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_sewa_lama` (`id_sewa_lama`),
  ADD KEY `id_kamar_lama` (`id_kamar_lama`),
  ADD KEY `id_kamar_baru` (`id_kamar_baru`);

--
-- Indeks untuk tabel `pengaturan`
--
ALTER TABLE `pengaturan`
  ADD PRIMARY KEY (`id_setting`),
  ADD UNIQUE KEY `kunci` (`kunci`);

--
-- Indeks untuk tabel `pengumuman`
--
ALTER TABLE `pengumuman`
  ADD PRIMARY KEY (`id_pengumuman`);

--
-- Indeks untuk tabel `peraturan`
--
ALTER TABLE `peraturan`
  ADD PRIMARY KEY (`id_peraturan`);

--
-- Indeks untuk tabel `sewa`
--
ALTER TABLE `sewa`
  ADD PRIMARY KEY (`id_sewa`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_kamar` (`id_kamar`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `kamar`
--
ALTER TABLE `kamar`
  MODIFY `id_kamar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `keluhan`
--
ALTER TABLE `keluhan`
  MODIFY `id_keluhan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT untuk tabel `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id_notifikasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=614;

--
-- AUTO_INCREMENT untuk tabel `password_reset`
--
ALTER TABLE `password_reset`
  MODIFY `id_reset` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id_pembayaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=178;

--
-- AUTO_INCREMENT untuk tabel `pengajuan_checkout`
--
ALTER TABLE `pengajuan_checkout`
  MODIFY `id_checkout` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `pengajuan_pindah`
--
ALTER TABLE `pengajuan_pindah`
  MODIFY `id_pindah` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT untuk tabel `pengaturan`
--
ALTER TABLE `pengaturan`
  MODIFY `id_setting` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT untuk tabel `pengumuman`
--
ALTER TABLE `pengumuman`
  MODIFY `id_pengumuman` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `peraturan`
--
ALTER TABLE `peraturan`
  MODIFY `id_peraturan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `sewa`
--
ALTER TABLE `sewa`
  MODIFY `id_sewa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `keluhan`
--
ALTER TABLE `keluhan`
  ADD CONSTRAINT `keluhan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`id_sewa`) REFERENCES `sewa` (`id_sewa`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pengajuan_pindah`
--
ALTER TABLE `pengajuan_pindah`
  ADD CONSTRAINT `pengajuan_pindah_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `pengajuan_pindah_ibfk_2` FOREIGN KEY (`id_sewa_lama`) REFERENCES `sewa` (`id_sewa`) ON DELETE CASCADE,
  ADD CONSTRAINT `pengajuan_pindah_ibfk_3` FOREIGN KEY (`id_kamar_lama`) REFERENCES `kamar` (`id_kamar`) ON DELETE CASCADE,
  ADD CONSTRAINT `pengajuan_pindah_ibfk_4` FOREIGN KEY (`id_kamar_baru`) REFERENCES `kamar` (`id_kamar`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `sewa`
--
ALTER TABLE `sewa`
  ADD CONSTRAINT `sewa_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `sewa_ibfk_2` FOREIGN KEY (`id_kamar`) REFERENCES `kamar` (`id_kamar`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
