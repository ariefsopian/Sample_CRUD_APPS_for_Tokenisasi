<?php
/**
 * config.php
 *
 * File Konfigurasi Pusat.
 * Semua kredensial dan pengaturan penting aplikasi disimpan di sini.
 * Mengubah pengaturan di sini akan memengaruhi seluruh aplikasi tanpa perlu mengubah logika kode.
 */

// --- KONFIGURASI KONEKSI DATABASE ---
// Konstanta ini digunakan oleh 'koneksi.php' untuk terhubung ke server database MySQL.
define('DB_HOST', 'localhost');      // Alamat server database, biasanya 'localhost'.
define('DB_USER', 'root');           // Username untuk mengakses database.
define('DB_PASS', 'I8[&}xp3)~}Ze'); // Password untuk username database tersebut.
define('DB_NAME', 'db_pegawai');     // Nama database yang digunakan oleh aplikasi.


// --- KONFIGURASI API TOKENISASI ---
// URL dasar (endpoint) dari layanan API tokenisasi.
define('TOKEN_API_BASE_URL', 'https://tknz-api.pertamina.com/vts/rest/v2.0');

// Kredensial (username & password) untuk endpoint "Tokenize".
// Digunakan untuk mengubah data asli menjadi token.
define('TOKEN_API_USER_TOKENIZE', 'demo.tokenize');
define('TOKEN_API_PASS_TOKENIZE', 'demo.tkn@2025@!');

// Kredensial untuk endpoint "Detokenize".
// Digunakan untuk mengubah token kembali menjadi data asli.
define('TOKEN_API_USER_DETOKENIZE', 'demo.detokenize');
define('TOKEN_API_PASS_DETOKENIZE', 'demo.dtkn@2025@!');

// Kredensial untuk endpoint "Detokenize" dengan hasil yang disamarkan (masking).
// Digunakan untuk menampilkan data sensitif (seperti nomor telepon) secara aman.
define('TOKEN_API_USER_MASKING', 'demo.detokenize.masking');
define('TOKEN_API_PASS_MASKING', 'demo.dtknmsk@2025@!');


// --- PENGATURAN TOKEN ---
// Pengaturan default yang digunakan saat berkomunikasi dengan API tokenisasi.
define('TOKEN_GROUP_DEFAULT', 'tkn.group.demo');        // Grup token default.
define('TOKEN_TEMPLATE_DEFAULT', 'tkn.tmp.demo');       // Template token standar.
define('TOKEN_TEMPLATE_MASKING', 'tkn.tmp.masking');    // Template khusus untuk data yang akan di-masking.

?>