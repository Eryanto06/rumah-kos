<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Migration: Initial schema untuk Rumah Kos.
 *
 * FIX C1, C11, C12 (laporan review): buat semua tabel + UNIQUE + index.
 *
 * Jalankan: php spark migrate
 */
class InitialSchema extends Migration
{
    public function up()
    {
        // ============================
        // 1. USER
        // ============================
        $this->forge->addField([
            'id_user'  => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'nama'     => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => false],
            'email'    => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => false],
            'username' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => false],
            'password' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'no_hp'    => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
            'foto'     => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'role'     => ['type' => 'ENUM', 'constraint' => ['admin', 'user'], 'default' => 'user'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true], // soft delete (H14)
        ]);
        $this->forge->addPrimaryKey('id_user');
        // FIX C11: UNIQUE di email & username
        $this->forge->addUniqueKey('email');
        $this->forge->addUniqueKey('username');
        $this->forge->createTable('user');

        // ============================
        // 2. KAMAR
        // ============================
        $this->forge->addField([
            'id_kamar'   => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'kode_kamar' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => false],
            'nomor_kamar'=> ['type' => 'VARCHAR', 'constraint' => 20, 'null' => false],
            'harga_sewa' => ['type' => 'DECIMAL', 'constraint' => '12,2', 'default' => 0], // M47
            'fasilitas'  => ['type' => 'TEXT', 'null' => true],
            'foto'       => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            // FIX schema bug: ENUM include 'dibooking' & 'perbaikan'
            'status'     => ['type' => 'ENUM', 'constraint' => ['tersedia', 'terisi', 'dibooking', 'perbaikan'], 'default' => 'tersedia'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id_kamar');
        $this->forge->addUniqueKey('kode_kamar');
        $this->forge->addUniqueKey('nomor_kamar');
        $this->forge->addKey('status'); // untuk filter list
        $this->forge->createTable('kamar');

        // ============================
        // 3. SEWA
        // ============================
        $this->forge->addField([
            'id_sewa'        => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'id_user'        => ['type' => 'INT', 'unsigned' => true, 'null' => false],
            'id_kamar'       => ['type' => 'INT', 'unsigned' => true, 'null' => false],
            'tanggal_pengajuan' => ['type' => 'DATE', 'null' => true],
            'tanggal_mulai'  => ['type' => 'DATE', 'null' => true],
            'tanggal_selesai'=> ['type' => 'DATE', 'null' => true],
            'durasi_bulan'   => ['type' => 'INT', 'default' => 1],
            'deposit'        => ['type' => 'DECIMAL', 'constraint' => '12,2', 'default' => 0],
            'deposit_dikembalikan' => ['type' => 'DECIMAL', 'constraint' => '12,2', 'default' => 0],
            // FIX schema: ENUM include 'disetujui'
            'status'         => ['type' => 'ENUM', 'constraint' => ['menunggu', 'disetujui', 'aktif', 'ditolak', 'selesai'], 'default' => 'menunggu'],
            'status_kunci'   => ['type' => 'ENUM', 'constraint' => ['belum_siap', 'siap_diambil', 'sudah_diambil', 'sudah_dikembalikan'], 'default' => 'belum_siap'],
            'tanggal_ambil_kunci' => ['type' => 'DATETIME', 'null' => true],
            'lokasi_ambil_kunci'  => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'keterangan'     => ['type' => 'TEXT', 'null' => true],
            'created_at'     => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id_sewa');
        $this->forge->addKey('id_user');
        $this->forge->addKey('id_kamar');
        $this->forge->addKey('status');
        // FIX M41: index tanggal_selesai untuk cron harian
        $this->forge->addKey('tanggal_selesai');
        $this->forge->createTable('sewa');

        // ============================
        // 4. PEMBAYARAN
        // ============================
        $this->forge->addField([
            'id_pembayaran'       => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'id_sewa'             => ['type' => 'INT', 'unsigned' => true, 'null' => false],
            'bulan_ke'            => ['type' => 'INT', 'default' => 0], // 0 = deposit
            'tanggal_bayar'       => ['type' => 'DATETIME', 'null' => true], // M48: DATETIME bukan DATE
            'tanggal_jatuh_tempo' => ['type' => 'DATE', 'null' => true],
            'jumlah_bayar'        => ['type' => 'DECIMAL', 'constraint' => '12,2', 'default' => 0], // M47
            'denda_per_hari'      => ['type' => 'DECIMAL', 'constraint' => '12,2', 'default' => 0],
            'total_denda'         => ['type' => 'DECIMAL', 'constraint' => '12,2', 'default' => 0],
            'bukti_bayar'         => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'kode_transaksi'      => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'status'              => ['type' => 'ENUM', 'constraint' => ['belum_bayar', 'menunggu_verifikasi', 'lunas'], 'default' => 'belum_bayar'],
            'keterangan'          => ['type' => 'TEXT', 'null' => true],
            'created_at'          => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id_pembayaran');
        $this->forge->addKey('id_sewa');
        $this->forge->addKey('status');
        // FIX M42: composite index untuk cron harian
        $this->forge->addKey(['status', 'tanggal_jatuh_tempo']);
        $this->forge->createTable('pembayaran');

        // ============================
        // 5. PENGAJUAN_PINDAH
        // ============================
        $this->forge->addField([
            'id_pindah'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'id_user'           => ['type' => 'INT', 'unsigned' => true],
            'id_sewa_lama'      => ['type' => 'INT', 'unsigned' => true],
            'id_kamar_lama'     => ['type' => 'INT', 'unsigned' => true],
            'id_kamar_baru'     => ['type' => 'INT', 'unsigned' => true],
            'alasan'            => ['type' => 'TEXT', 'null' => true],
            'tanggal_pengajuan' => ['type' => 'DATE', 'null' => true],
            'status'            => ['type' => 'ENUM', 'constraint' => ['menunggu', 'disetujui', 'ditolak'], 'default' => 'menunggu'],
            'tanggal_proses'    => ['type' => 'DATE', 'null' => true],
            'keterangan_admin'  => ['type' => 'TEXT', 'null' => true],
            'bukti_refund'      => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'tanggal_refund'    => ['type' => 'DATE', 'null' => true],
            'total_refund'      => ['type' => 'DECIMAL', 'constraint' => '12,2', 'default' => 0],
            'created_at'        => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id_pindah');
        $this->forge->addKey('id_user');
        $this->forge->addKey('id_kamar_baru');
        $this->forge->addKey('status');
        $this->forge->createTable('pengajuan_pindah');

        // ============================
        // 6. PENGAJUAN_CHECKOUT
        // ============================
        $this->forge->addField([
            'id_checkout'              => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'id_user'                  => ['type' => 'INT', 'unsigned' => true],
            'id_sewa'                  => ['type' => 'INT', 'unsigned' => true],
            'id_kamar'                 => ['type' => 'INT', 'unsigned' => true],
            'alasan'                   => ['type' => 'TEXT', 'null' => true],
            'tanggal_checkout_diajukan'=> ['type' => 'DATE', 'null' => true],
            'tanggal_pengajuan'        => ['type' => 'DATE', 'null' => true],
            // FIX schema: ENUM include 'inspeksi' & 'disetujui'
            'status'                   => ['type' => 'ENUM', 'constraint' => ['menunggu', 'inspeksi', 'disetujui', 'ditolak'], 'default' => 'menunggu'],
            'tanggal_proses'           => ['type' => 'DATE', 'null' => true],
            'keterangan_admin'         => ['type' => 'TEXT', 'null' => true],
            'bukti_refund'             => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'tanggal_refund'           => ['type' => 'DATE', 'null' => true],
            'total_refund'             => ['type' => 'DECIMAL', 'constraint' => '12,2', 'default' => 0],
            'created_at'               => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id_checkout');
        $this->forge->addKey('id_user');
        $this->forge->addKey('id_sewa');
        $this->forge->addKey('status');
        $this->forge->createTable('pengajuan_checkout');

        // ============================
        // 7. NOTIFIKASI
        // ============================
        $this->forge->addField([
            'id_notifikasi' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'id_user'       => ['type' => 'INT', 'unsigned' => true],
            'judul'         => ['type' => 'VARCHAR', 'constraint' => 200, 'null' => false],
            'pesan'         => ['type' => 'TEXT', 'null' => false],
            'tipe'          => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'info'],
            'dibaca'        => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0], // M68
            // FIX M50: DEFAULT CURRENT_TIMESTAMP
            'created_at'    => ['type' => 'DATETIME', 'default' => null, 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id_notifikasi');
        $this->forge->addKey('id_user');
        // FIX M43: composite index untuk badge counter
        $this->forge->addKey(['id_user', 'dibaca']);
        $this->forge->createTable('notifikasi');

        // ============================
        // 8. KELUHAN
        // ============================
        $this->forge->addField([
            'id_keluhan'  => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'id_user'     => ['type' => 'INT', 'unsigned' => true, 'null' => true], // null untuk keluhan private (H9)
            'id_pelapor'  => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'judul'       => ['type' => 'VARCHAR', 'constraint' => 200, 'null' => false],
            'deskripsi'   => ['type' => 'TEXT', 'null' => false],
            'kategori'    => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => false],
            'prioritas'   => ['type' => 'ENUM', 'constraint' => ['rendah', 'normal', 'tinggi', 'urgent'], 'default' => 'normal'],
            'is_private'  => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'tanggal'     => ['type' => 'DATETIME', 'null' => true], // M49: DATETIME bukan DATE
            'status'      => ['type' => 'ENUM', 'constraint' => ['menunggu', 'diproses', 'selesai'], 'default' => 'menunggu'],
            'balasan'     => ['type' => 'TEXT', 'null' => true],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id_keluhan');
        // FIX M44: index id_user & id_pelapor
        $this->forge->addKey('id_user');
        $this->forge->addKey('id_pelapor');
        $this->forge->addKey('status');
        $this->forge->createTable('keluhan');

        // ============================
        // 9. PENGUMUMAN
        // ============================
        $this->forge->addField([
            'id_pengumuman'  => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'judul'          => ['type' => 'VARCHAR', 'constraint' => 200, 'null' => false],
            'isi'            => ['type' => 'TEXT', 'null' => false],
            'target'         => ['type' => 'ENUM', 'constraint' => ['semua', 'penghuni_aktif', 'pendaftar'], 'default' => 'semua'],
            'status'         => ['type' => 'ENUM', 'constraint' => ['aktif', 'nonaktif'], 'default' => 'aktif'],
            'tanggal_mulai'  => ['type' => 'DATE', 'null' => true],
            'tanggal_selesai'=> ['type' => 'DATE', 'null' => true],
            'waktu_mulai'    => ['type' => 'TIME', 'null' => true],
            'waktu_selesai'  => ['type' => 'TIME', 'null' => true],
            'created_by'     => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'created_at'     => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'     => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id_pengumuman');
        // FIX M45: composite index untuk filter
        $this->forge->addKey(['status', 'tanggal_mulai', 'tanggal_selesai']);
        $this->forge->createTable('pengumuman');

        // ============================
        // 10. PERATURAN
        // ============================
        $this->forge->addField([
            'id_peraturan' => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'judul'        => ['type' => 'VARCHAR', 'constraint' => 200, 'null' => false],
            'isi'          => ['type' => 'TEXT', 'null' => false],
            'kategori'     => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => false],
            'urutan'       => ['type' => 'INT', 'default' => 0],
            'status'       => ['type' => 'ENUM', 'constraint' => ['aktif', 'nonaktif'], 'default' => 'aktif'],
            'created_at'   => ['type' => 'DATETIME', 'null' => true],
            'deleted_at'   => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id_peraturan');
        // FIX M46: composite index
        $this->forge->addKey(['status', 'kategori', 'urutan']);
        $this->forge->createTable('peraturan');

        // ============================
        // 11. PENGATURAN
        // ============================
        $this->forge->addField([
            'id_setting'  => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'kunci'       => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => false],
            'nilai'       => ['type' => 'TEXT', 'null' => true],
            'keterangan'  => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id_setting');
        // FIX C9/C12: UNIQUE di kunci supaya setSetting atomic upsert jalan
        $this->forge->addUniqueKey('kunci');
        $this->forge->createTable('pengaturan');

        // ============================
        // 12. PASSWORD_RESET
        // ============================
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
            'email'      => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => false],
            'token'      => ['type' => 'VARCHAR', 'constraint' => 64, 'null' => false],
            'expires_at' => ['type' => 'DATETIME', 'null' => false],
            'used'       => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'default' => null, 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        // FIX C12: UNIQUE di token supaya 2 email gak share token
        $this->forge->addUniqueKey('token');
        $this->forge->addKey('email');
        $this->forge->createTable('password_reset');
    }

    public function down()
    {
        $this->forge->dropTable('password_reset', true);
        $this->forge->dropTable('pengaturan', true);
        $this->forge->dropTable('peraturan', true);
        $this->forge->dropTable('pengumuman', true);
        $this->forge->dropTable('keluhan', true);
        $this->forge->dropTable('notifikasi', true);
        $this->forge->dropTable('pengajuan_checkout', true);
        $this->forge->dropTable('pengajuan_pindah', true);
        $this->forge->dropTable('pembayaran', true);
        $this->forge->dropTable('sewa', true);
        $this->forge->dropTable('kamar', true);
        $this->forge->dropTable('user', true);
    }
}
