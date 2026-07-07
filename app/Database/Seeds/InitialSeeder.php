<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Seeder: InitialAdmin + default pengaturan.
 *
 * Jalankan: php spark db:seed InitialSeeder
 */
class InitialSeeder extends Seeder
{
    public function run()
    {
        // ============================
        // 1. Default admin
        // ============================
        $existingAdmin = $this->db->table('user')->where('role', 'admin')->countAllResults();
        if ($existingAdmin === 0) {
            $this->db->table('user')->insert([
                'nama'     => 'Admin Rumah Kos',
                'email'    => 'admin@rumahkos.local',
                'username' => 'admin',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'no_hp'    => '081234567890',
                'role'     => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            echo "Admin default dibuat: username=admin, password=admin123\n";
            echo ">>> GANTI PASSWORD INI SETELAH LOGIN PERTAMA! <<<\n";
        }

        // ============================
        // 2. Default pengaturan
        // ============================
        $defaults = [
            ['kunci' => 'denda_per_hari', 'nilai' => '5000', 'keterangan' => 'Denda keterlambatan per hari (Rp)'],
            ['kunci' => 'default_deposit_kali', 'nilai' => '2', 'keterangan' => 'Default deposit = X kali harga sewa'],
            ['kunci' => 'durasi_minimal', 'nilai' => '1', 'keterangan' => 'Default durasi minimal sewa (bulan)'],
            ['kunci' => 'durasi_maksimal', 'nilai' => '36', 'keterangan' => 'Default durasi maksimal sewa (bulan)'],
            ['kunci' => 'batas_tanggal_bayar', 'nilai' => '5', 'keterangan' => 'Batas tanggal bayar tiap bulan'],
            ['kunci' => 'automation_last_run', 'nilai' => '', 'keterangan' => 'Tanggal automation terakhir jalan'],
            // FIX BUG: default metode pembayaran kos (kosong sampai admin isi di Pengaturan)
            ['kunci' => 'bank_name_1', 'nilai' => '', 'keterangan' => 'Nama bank utama untuk terima pembayaran'],
            ['kunci' => 'bank_account_1', 'nilai' => '', 'keterangan' => 'Nomor rekening bank utama'],
            ['kunci' => 'bank_holder_1', 'nilai' => '', 'keterangan' => 'Nama pemilik rekening bank utama'],
            ['kunci' => 'bank_name_2', 'nilai' => '', 'keterangan' => 'Nama bank alternatif (opsional)'],
            ['kunci' => 'bank_account_2', 'nilai' => '', 'keterangan' => 'Nomor rekening bank alternatif'],
            ['kunci' => 'bank_holder_2', 'nilai' => '', 'keterangan' => 'Nama pemilik rekening bank alternatif'],
            ['kunci' => 'ewallet_dana', 'nilai' => '', 'keterangan' => 'Nomor DANA'],
            ['kunci' => 'ewallet_ovo', 'nilai' => '', 'keterangan' => 'Nomor OVO'],
            ['kunci' => 'ewallet_gopay', 'nilai' => '', 'keterangan' => 'Nomor GoPay'],
            ['kunci' => 'ewallet_shopeepay', 'nilai' => '', 'keterangan' => 'Nomor ShopeePay'],
            ['kunci' => 'payment_instructions', 'nilai' => 'Transfer tepat sesuai nominal tagihan. Setelah transfer, upload bukti pembayaran di menu Pembayaran. Admin akan verifikasi dalam 1x24 jam.', 'keterangan' => 'Instruksi pembayaran untuk user'],
            // FIX: default kontak kos (admin bisa edit di Pengaturan)
            ['kunci' => 'nama_kos', 'nilai' => 'Rumah Kos', 'keterangan' => 'Nama kos (tampil di landing)'],
            ['kunci' => 'tagline', 'nilai' => 'Sistem Informasi Manajemen Kos', 'keterangan' => 'Tagline/slogan kos'],
            ['kunci' => 'alamat', 'nilai' => '', 'keterangan' => 'Alamat lengkap kos'],
            ['kunci' => 'email_kos', 'nilai' => '', 'keterangan' => 'Email kontak kos'],
            ['kunci' => 'telepon_kos', 'nilai' => '', 'keterangan' => 'No telepon kontak kos'],
            ['kunci' => 'wa_admin', 'nilai' => '', 'keterangan' => 'No WhatsApp admin untuk chat landing'],
            ['kunci' => 'facebook', 'nilai' => '', 'keterangan' => 'URL Facebook'],
            ['kunci' => 'instagram', 'nilai' => '', 'keterangan' => 'URL Instagram'],
            ['kunci' => 'tiktok', 'nilai' => '', 'keterangan' => 'URL TikTok'],
            ['kunci' => 'youtube', 'nilai' => '', 'keterangan' => 'URL YouTube'],
            ['kunci' => 'jam_operasional', 'nilai' => '08:00 - 17:00 WIB', 'keterangan' => 'Jam operasional office'],
            ['kunci' => 'maps_embed', 'nilai' => '', 'keterangan' => 'URL embed Google Maps'],
            ['kunci' => 'maps_link', 'nilai' => '', 'keterangan' => 'URL link Google Maps'],
            ['kunci' => 'footer_text', 'nilai' => '', 'keterangan' => 'Teks footer copyright'],
        ];

        foreach ($defaults as $setting) {
            $existing = $this->db->table('pengaturan')->where('kunci', $setting['kunci'])->countAllResults();
            if ($existing === 0) {
                $this->db->table('pengaturan')->insert($setting);
                echo "Pengaturan '{$setting['kunci']}' ditambahkan.\n";
            }
        }
    }
}
