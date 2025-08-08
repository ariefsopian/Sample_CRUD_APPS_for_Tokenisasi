<?php
/**
 * config.example.php
 *
 * File Konfigurasi Pusat - Contoh.
 * Salin file ini menjadi config.php dan sesuaikan nilainya.
 */

// --- KONFIGURASI KONEKSI DATABASE ---
// Konstanta ini digunakan oleh 'koneksi.php' untuk terhubung ke server database MySQL.
define('DB_HOST', 'localhost');      // Alamat server database
define('DB_USER', 'root');           // Username database
define('DB_PASS', 'your_db_password'); // Ganti dengan password database Anda
define('DB_NAME', 'db_pegawai');     // Nama database yang digunakan oleh aplikasi

// --- KONFIGURASI API TOKENISASI ---
// URL dasar (endpoint) dari layanan API tokenisasi.
// Ganti dengan URL API yang sesuai dengan lingkungan Anda.
define('TOKEN_API_BASE_URL', 'https://your-api-hostname/vts/rest/v2.0');

// Kredensial (username & password) untuk endpoint "Tokenize".
// Ganti dengan kredensial yang diberikan oleh administrator API.
define('TOKEN_API_USER_TOKENIZE', 'your_tokenize_user');
define('TOKEN_API_PASS_TOKENIZE', 'your_tokenize_password');

// Kredensial untuk endpoint "Detokenize".
// Ganti dengan kredensial yang diberikan oleh administrator API.
define('TOKEN_API_USER_DETOKENIZE', 'your_detokenize_user');
define('TOKEN_API_PASS_DETOKENIZE', 'your_detokenize_password');

// Kredensial untuk endpoint "Detokenize" dengan hasil yang disamarkan (masking).
// Ganti dengan kredensial yang diberikan oleh administrator API.
define('TOKEN_API_USER_MASKING', 'your_masking_user');
define('TOKEN_API_PASS_MASKING', 'your_masking_password');

// --- PENGATURAN TOKEN ---
// Pengaturan default yang digunakan saat berkomunikasi dengan API tokenisasi.
// Ganti dengan nilai yang sesuai dengan konfigurasi API Anda.
define('TOKEN_GROUP_DEFAULT', 'tkn.group.demo');
define('TOKEN_TEMPLATE_DEFAULT', 'tkn.tmp.demo');
define('TOKEN_TEMPLATE_MASKING', 'tkn.tmp.masking');