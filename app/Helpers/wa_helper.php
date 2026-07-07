<?php

if (!function_exists('format_wa')) {
    /**
     * Format nomor HP Indonesia ke format internasional WhatsApp
     * 08123456789  → 628123456789
     * 628123456789 → 628123456789 (sudah benar)
     * +628123456789 → 628123456789
     * 8123456789   → 628123456789
     */
    function format_wa($nomor)
    {
        if (empty($nomor)) return '';

        // Buang semua karakter non-digit
        $nomor = preg_replace('/[^0-9]/', '', $nomor);

        // Konversi format
        if (substr($nomor, 0, 3) === '620') {
            $nomor = '62' . substr($nomor, 3);
        } elseif (substr($nomor, 0, 2) === '62') {
            // sudah benar, tidak perlu diubah
        } elseif (substr($nomor, 0, 1) === '0') {
            $nomor = '62' . substr($nomor, 1);
        } elseif (substr($nomor, 0, 1) === '8') {
            $nomor = '62' . $nomor;
        }

        return $nomor;
    }
}

if (!function_exists('link_wa')) {
    /**
     * Buat link WhatsApp Web langsung ke nomor tujuan
     */
    function link_wa($nomor, $pesan = '')
    {
        $nomor = format_wa($nomor);
        if (empty($nomor)) return '#';

        $url = 'https://web.whatsapp.com/send?phone=' . $nomor;
        if (!empty($pesan)) {
            $url .= '&text=' . urlencode($pesan);
        }
        return $url;
    }
}