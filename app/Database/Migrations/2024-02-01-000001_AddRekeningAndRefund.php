<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Migration: Tambah kolom rekening user & refund di tabel sewa.
 *
 * Tujuan:
 * - User bisa set rekening bank + e-wallet di profil (untuk terima refund)
 * - Admin bisa upload bukti refund saat sewa ditolak (setelah user bayar deposit)
 * - Tabel pengaturan diisi default metode pembayaran kos (BCA/Mandiri/dll)
 *
 * Jalankan: php spark migrate
 */
class AddRekeningAndRefund extends Migration
{
    public function up()
    {
        // ============================
        // 1. Tambah kolom rekening di tabel USER
        // ============================
        // User perlu set rekening supaya admin tahu ke mana transfer refund
        // (checkout, pindah kamar, sewa ditolak)
        $this->forge->addColumn('user', [
            'nama_bank'         => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true, 'after' => 'no_hp'],
            'nomor_rekening'    => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true, 'after' => 'nama_bank'],
            'nama_pemilik_rek'  => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true, 'after' => 'nomor_rekening'],
            'ewallet_type'      => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true, 'after' => 'nama_pemilik_rek'],
            'ewallet_number'    => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true, 'after' => 'ewallet_type'],
        ]);

        // ============================
        // 2. Tambah kolom refund di tabel SEWA
        // ============================
        // Untuk tracking refund deposit saat sewa DITOLAK (setelah user bayar deposit)
        // Sebelumnya: refund hanya disebut di notifikasi, tidak ada tracking di DB.
        $this->forge->addColumn('sewa', [
            'bukti_refund'      => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true, 'after' => 'keterangan'],
            'tanggal_refund'    => ['type' => 'DATE', 'null' => true, 'after' => 'bukti_refund'],
            'total_refund'      => ['type' => 'DECIMAL', 'constraint' => '12,2', 'default' => 0, 'after' => 'tanggal_refund'],
            'refund_status'     => ['type' => 'ENUM', 'constraint' => ['tidak_ada', 'menunggu', 'selesai'], 'default' => 'tidak_ada', 'after' => 'total_refund'],
            'refund_metode'     => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true, 'after' => 'refund_status'],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('sewa', ['refund_metode', 'refund_status', 'total_refund', 'tanggal_refund', 'bukti_refund']);
        $this->forge->dropColumn('user', ['ewallet_number', 'ewallet_type', 'nama_pemilik_rek', 'nomor_rekening', 'nama_bank']);
    }
}
