<?php

/**
 * The goal of this file is to allow developers a location
 * where they can overwrite core procedural functions and
 * replace them with their own. This file is loaded during
 * the bootstrap process and is called during the framework's
 * execution.
 *
 * This can be looked at as a `master helper` file that is
 * loaded early on, and may also contain additional functions
 * that you'd like to use throughout your entire application
 *
 * @see: https://codeigniter.com/user_guide/extending/common.html
 */

/**
 * Ambil info metode pembayaran kos dari tabel pengaturan.
 *
 * Dipakai di view user (pembayaran, sewa, invoice) supaya user tahu ke mana
 * harus transfer. Sebelumnya tidak ada info ini — user harus chat admin manual.
 *
 * @return array {
 *     @type array  $banks       List of ['nama'=>..., 'rekening'=>..., 'pemilik'=>...]
 *     @type array  $ewallets    List of ['type'=>..., 'nomor'=>...]
 *     @type string $instruksi   Teks instruksi pembayaran
 *     @type bool   $ada         True kalau ada minimal 1 metode
 * }
 */
function get_metode_pembayaran(): array
{
    $db = \Config\Database::connect();
    $rows = $db->table('pengaturan')
        ->whereIn('kunci', [
            'bank_name_1', 'bank_account_1', 'bank_holder_1',
            'bank_name_2', 'bank_account_2', 'bank_holder_2',
            'ewallet_dana', 'ewallet_ovo', 'ewallet_gopay', 'ewallet_shopeepay',
            'payment_instructions',
        ])
        ->get()
        ->getResultArray();

    $settings = [];
    foreach ($rows as $r) {
        $settings[$r['kunci']] = $r['nilai'];
    }

    $banks = [];
    for ($i = 1; $i <= 2; $i++) {
        $nama = $settings["bank_name_{$i}"] ?? '';
        $rek  = $settings["bank_account_{$i}"] ?? '';
        $pem  = $settings["bank_holder_{$i}"] ?? '';
        if (!empty($nama) && !empty($rek)) {
            $banks[] = ['nama' => $nama, 'rekening' => $rek, 'pemilik' => $pem];
        }
    }

    $ewallets = [];
    foreach (['DANA' => 'ewallet_dana', 'OVO' => 'ewallet_ovo', 'GoPay' => 'ewallet_gopay', 'ShopeePay' => 'ewallet_shopeepay'] as $type => $key) {
        $nomor = $settings[$key] ?? '';
        if (!empty($nomor)) {
            $ewallets[] = ['type' => $type, 'nomor' => $nomor];
        }
    }

    $instruksi = $settings['payment_instructions'] ?? '';
    $ada = !empty($banks) || !empty($ewallets);

    return [
        'banks'     => $banks,
        'ewallets'  => $ewallets,
        'instruksi' => $instruksi,
        'ada'       => $ada,
    ];
}

/**
 * Format info rekening user untuk ditampilkan di view admin.
 *
 * @param array $user Row user (dari DB, harus sudah include field rekening)
 * @return string Teks "Bank BCA 1234567890 a.n. Budi" / "DANA 08123..." / "-"
 */
function format_rekening_user(array $user): string
{
    $parts = [];
    if (!empty($user['nama_bank']) && !empty($user['nomor_rekening'])) {
        $parts[] = 'Bank ' . $user['nama_bank'] . ' ' . $user['nomor_rekening'];
        if (!empty($user['nama_pemilik_rek'])) {
            $parts[] = 'a.n. ' . $user['nama_pemilik_rek'];
        }
    }
    if (!empty($user['ewallet_type']) && !empty($user['ewallet_number'])) {
        $parts[] = $user['ewallet_type'] . ' ' . $user['ewallet_number'];
    }
    return $parts ? implode(' | ', $parts) : '-';
}


/**
 * Ambil info kontak kos dari tabel pengaturan.
 *
 * Dipakai di landing page & view lain supaya kontak (email, FB, IG, WA, alamat,
 * jam operasional, nama kos) bisa diubah admin tanpa edit kode. Sebelumnya
 * kontak di landing hardcoded di view — admin gak bisa ubah tanpa minta developer.
 *
 * @return array {
 *     @type string $nama_kos         Nama kos (default: "Rumah Kos")
 *     @type string $tagline          Slogan/tagline kos
 *     @type string $alamat           Alamat fisik kos
 *     @type string $email            Email kontak
 *     @type string $telepon          No telepon/kontak
 *     @type string $wa_admin         No WhatsApp admin (untuk chat via link_wa)
 *     @type string $facebook         URL Facebook
 *     @type string $instagram        URL Instagram
 *     @type string $tiktok           URL TikTok
 *     @type string $youtube          URL YouTube
 *     @type string $jam_operasional  Jam buka office (mis. "08:00 - 17:00 WIB")
 *     @type string $maps_embed       URL embed Google Maps
 *     @type string $maps_link        URL link Google Maps (untuk tombol "Lihat di Maps")
 *     @type string $footer_text      Teks footer (copyright)
 * }
 */
function get_kontak_kos(): array
{
    $db = \Config\Database::connect();
    $rows = $db->table('pengaturan')
        ->whereIn('kunci', [
            'nama_kos', 'tagline', 'alamat', 'email_kos', 'telepon_kos',
            'wa_admin', 'facebook', 'instagram', 'tiktok', 'youtube',
            'jam_operasional', 'maps_embed', 'maps_link', 'footer_text',
        ])
        ->get()
        ->getResultArray();

    $settings = [];
    foreach ($rows as $r) {
        $settings[$r['kunci']] = $r['nilai'];
    }

    return [
        'nama_kos'        => $settings['nama_kos'] ?? 'Rumah Kos',
        'tagline'         => $settings['tagline'] ?? 'Sistem Informasi Manajemen Kos',
        'alamat'          => $settings['alamat'] ?? '',
        'email'           => $settings['email_kos'] ?? '',
        'telepon'         => $settings['telepon_kos'] ?? '',
        'wa_admin'        => $settings['wa_admin'] ?? '',
        'facebook'        => $settings['facebook'] ?? '',
        'instagram'       => $settings['instagram'] ?? '',
        'tiktok'          => $settings['tiktok'] ?? '',
        'youtube'         => $settings['youtube'] ?? '',
        'jam_operasional' => $settings['jam_operasional'] ?? '08:00 - 17:00 WIB',
        'maps_embed'      => $settings['maps_embed'] ?? '',
        'maps_link'       => $settings['maps_link'] ?? '',
        'footer_text'     => $settings['footer_text'] ?? '',
    ];
}


/**
 * Cek apakah kolom ada di tabel tertentu.
 *
 * Dipakai untuk defensif — kalau migration belum dijalankan,
 * kolom baru belum ada, dan SELECT field yang tidak ada akan error.
 * Dengan helper ini, kita bisa cek dulu sebelum SELECT.
 *
 * @param string $table Nama tabel
 * @param string $column Nama kolom
 * @return bool True kalau kolom ada
 */
function kolom_ada(string $table, string $column): bool
{
    static $cache = [];
    $key = $table . '.' . $column;
    if (isset($cache[$key])) {
        return $cache[$key];
    }
    try {
        $db = \Config\Database::connect();
        $fields = $db->getFieldNames($table);
        $ada = in_array($column, $fields, true);
        $cache[$key] = $ada;
        return $ada;
    } catch (\Throwable $e) {
        $cache[$key] = false;
        return false;
    }
}

/**
 * Versi aman get_metode_pembayaran yang tidak crash kalau tabel pengaturan kosong.
 *
 * @return array Sama seperti get_metode_pembayaran, tapi selalu return array
 */
function get_metode_pembayaran_safe(): array
{
    try {
        return get_metode_pembayaran();
    } catch (\Throwable $e) {
        log_message('error', '[get_metode_pembayaran_safe] Error: ' . $e->getMessage());
        return ['banks' => [], 'ewallets' => [], 'instruksi' => '', 'ada' => false];
    }
}

/**
 * Versi aman get_kontak_kos yang tidak crash kalau tabel pengaturan kosong.
 *
 * @return array Sama seperti get_kontak_kos, tapi selalu return array dengan default
 */
function get_kontak_kos_safe(): array
{
    try {
        return get_kontak_kos();
    } catch (\Throwable $e) {
        log_message('error', '[get_kontak_kos_safe] Error: ' . $e->getMessage());
        return [
            'nama_kos' => 'Rumah Kos',
            'tagline' => 'Sistem Informasi Manajemen Kos',
            'alamat' => '', 'email' => '', 'telepon' => '', 'wa_admin' => '',
            'facebook' => '', 'instagram' => '', 'tiktok' => '', 'youtube' => '',
            'jam_operasional' => '08:00 - 17:00 WIB',
            'maps_embed' => '', 'maps_link' => '', 'footer_text' => '',
        ];
    }
}


/**
 * Generate SQL select clause untuk field rekening user (defensif).
 *
 * Pakai: ->select('u.id, u.nama' . rekening_select_clause('u') . ', u.email')
 * Kalau kolom rekening belum ada di DB (migration belum dijalankan),
 * return string kosong supaya SQL tidak error.
 *
 * @param string $alias Alias tabel user (mis. 'u' atau 'user')
 * @return string Contoh: ', u.nama_bank, u.nomor_rekening, ...' atau '' kalau kolom belum ada
 */
function rekening_select_clause(string $alias = 'u'): string
{
    $cols = ['nama_bank', 'nomor_rekening', 'nama_pemilik_rek', 'ewallet_type', 'ewallet_number'];
    $ada = [];
    foreach ($cols as $c) {
        if (kolom_ada('user', $c)) {
            $ada[] = $alias . '.' . $c;
        }
    }
    return empty($ada) ? '' : ', ' . implode(', ', $ada);
}


/**
 * Ambil data user + field rekening (defensif).
 * Kalau kolom rekening belum ada di DB (migration belum jalan),
 * return user tanpa field rekening.
 *
 * @param int $id_user ID user
 * @return array|null Data user atau null
 */
function get_user_with_rekening($id_user)
{
    $model = new \App\Models\UserModel();
    $colsRekening = ['nama_bank', 'nomor_rekening', 'nama_pemilik_rek', 'ewallet_type', 'ewallet_number'];
    $colsAda = [];
    foreach ($colsRekening as $c) {
        if (kolom_ada('user', $c)) {
            $colsAda[] = $c;
        }
    }
    $select = 'id_user, nama, no_hp, email' . (!empty($colsAda) ? ', ' . implode(', ', $colsAda) : '');
    return $model->select($select)->find($id_user);
}


/**
 * Label badge untuk bulan_ke tagihan.
 *
 * - bulan_ke = 0  -> Deposit
 * - bulan_ke = -1 -> Selisih Sewa (pindah kamar)
 * - bulan_ke > 0  -> Bulan ke-X
 *
 * @param int $bulan_ke
 * @return string HTML badge
 */
function label_bulan_ke($bulan_ke): string
{
    $bulan_ke = (int) $bulan_ke;
    if ($bulan_ke === 0) {
        return '<span class="badge bg-warning text-dark">Deposit</span>';
    }
    if ($bulan_ke === -1) {
        return '<span class="badge bg-info text-dark">Selisih Sewa</span>';
    }
    return 'Bulan ' . $bulan_ke;
}
