<?php

// Validasi Bahasa Indonesia untuk CI4.
// Override pesan default English dari system/Language/en/Validation.php.

return [
    // Core
    'required'              => 'Field {field} wajib diisi.',
    'isset'                 => 'Field {field} harus memiliki nilai.',
    'empty'                 => 'Field {field} tidak boleh kosong.',
    'not_empty'             => 'Field {field} tidak boleh kosong.',
    'regex_match'           => 'Format {field} tidak sesuai.',
    'matches'               => 'Field {field} tidak cocok dengan field {param}.',
    'differs'               => 'Field {field} harus berbeda dari field {param}.',
    'exact_length'          => 'Field {field} harus tepat {param} karakter panjangnya.',
    'min_length'            => 'Field {field} minimal harus {param} karakter panjangnya.',
    'max_length'            => 'Field {field} tidak boleh lebih dari {param} karakter panjangnya.',
    'greater_than'          => 'Field {field} harus berisi angka yang lebih besar dari {param}.',
    'greater_than_equal_to' => 'Field {field} harus berisi angka yang lebih besar atau sama dengan {param}.',
    'less_than'             => 'Field {field} harus berisi angka yang lebih kecil dari {param}.',
    'less_than_equal_to'    => 'Field {field} harus berisi angka yang lebih kecil atau sama dengan {param}.',
    'in_list'               => 'Field {field} harus salah satu dari: {param}.',
    'in_list'               => 'Field {field} harus salah satu dari: {param}.',
    'not_in_list'           => 'Field {field} tidak boleh salah satu dari: {param}.',
    'alpha'                 => 'Field {field} hanya boleh berisi karakter alfabet.',
    'alpha_space'           => 'Field {field} hanya boleh berisi karakter alfabet dan spasi.',
    'alpha_numeric'         => 'Field {field} hanya boleh berisi karakter alfanumerik.',
    'alpha_numeric_space'   => 'Field {field} hanya boleh berisi karakter alfanumerik dan spasi.',
    'alpha_dash'            => 'Field {field} hanya boleh berisi karakter alfanumerik, underscore, dan dash.',
    'numeric'               => 'Field {field} harus berisi angka.',
    'integer'               => 'Field {field} harus berisi integer.',
    'decimal'               => 'Field {field} harus berisi angka desimal.',
    'is_natural'            => 'Field {field} harus berisi angka positif atau nol.',
    'is_natural_no_zero'    => 'Field {field} harus berisi angka positif bukan nol.',
    'is_unique'             => 'Field {field} sudah digunakan. Silakan pilih nilai lain.',
    'is_not_unique'         => 'Field {field} harus sudah ada di database.',
    'valid_email'           => 'Field {field} harus berisi alamat email yang valid.',
    'valid_emails'          => 'Field {field} harus berisi semua alamat email yang valid.',
    'valid_url'             => 'Field {field} harus berisi URL yang valid.',
    'valid_date'            => 'Field {field} harus berisi tanggal yang valid.',
    'valid_ip'              => 'Field {field} harus berisi IP yang valid.',
    'valid_base64'          => 'Field {field} harus berisi string base64 yang valid.',
    'permit_empty'          => 'Field {field} boleh kosong.',
];
