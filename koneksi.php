<?php
/**
 * koneksi.php
 *
 * File ini adalah jantung dari aplikasi yang melakukan beberapa tugas penting:
 * 1. Memuat konfigurasi pusat dari config.php.
 * 2. Memulai dan mengamankan sesi PHP untuk manajemen login.
 * 3. Membuat koneksi ke database menggunakan gaya Object-Oriented.
 * 4. Menyediakan fungsi-fungsi helper yang digunakan di seluruh aplikasi (misal: is_admin, redirect).
 */

// 1. Muat file konfigurasi utama yang berisi semua kredensial.
// 'require_once' memastikan file hanya dimuat sekali dan akan menyebabkan error fatal jika file tidak ditemukan.
require_once 'config.php';

// 2. Fungsi untuk memulai dan mengamankan sesi PHP.
function secure_session_start() {
    $session_name = 'secure_session_id'; // Memberi nama sesi kustom agar tidak mudah ditebak.
    $secure = false; // Set ke 'true' jika Anda menggunakan HTTPS (SSL).
    $httponly = true; // Mencegah cookie sesi diakses melalui JavaScript (melindungi dari serangan XSS).

    // Mengatur agar sesi hanya menggunakan cookie, bukan parameter URL.
    if (ini_set('session.use_only_cookies', 1) === false) {
        error_log("Error: Gagal menginisialisasi sesi (use_only_cookies).");
        exit(); // Hentikan eksekusi jika gagal.
    }

    // Mengambil parameter cookie saat ini.
    $cookieParams = session_get_cookie_params();
    // Mengatur parameter cookie sesi dengan opsi keamanan yang sudah didefinisikan.
    session_set_cookie_params(
        $cookieParams["lifetime"],
        $cookieParams["path"],
        $cookieParams["domain"],
        $secure,
        $httponly
    );

    session_name($session_name); // Menetapkan nama sesi.
    session_start(); // Memulai sesi PHP.

    // Regenerasi ID sesi secara berkala untuk mencegah serangan 'session fixation'.
    if (!isset($_SESSION['created'])) {
        $_SESSION['created'] = time();
    } else if (time() - $_SESSION['created'] > 1800) { // Jika sesi sudah berjalan lebih dari 30 menit
        session_regenerate_id(true); // Buat ID sesi baru dan hapus yang lama.
        $_SESSION['created'] = time(); // Reset waktu pembuatan sesi.
    }
}

// Memanggil fungsi di atas untuk memulai sesi yang aman setiap kali 'koneksi.php' dimuat.
secure_session_start();

// 3. Membuat koneksi ke database menggunakan konstanta dari 'config.php' dengan gaya Object-Oriented.
$koneksi = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Memeriksa apakah koneksi berhasil.
if ($koneksi->connect_error) { // Memeriksa error dengan gaya Object-Oriented
    // Mencatat error ke log server (tidak ditampilkan ke pengguna) dan menampilkan pesan umum.
    error_log("Koneksi ke database gagal: " . $koneksi->connect_error);
    die("Koneksi ke server gagal. Silakan coba beberapa saat lagi.");
}

// 4. Memuat file yang berisi fungsi-fungsi untuk berinteraksi dengan API tokenisasi.
require_once 'config_tokenisasi.php';

// 5. Kumpulan fungsi helper untuk mempermudah pengembangan.

// Mengecek apakah pengguna sudah login berdasarkan keberadaan 'user_id' di sesi.
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// Mendapatkan peran (role) pengguna dari sesi. Mengembalikan null jika tidak ada.
function get_user_role() {
    return $_SESSION['role'] ?? null; // '??' adalah null coalescing operator.
}

// Mendapatkan ID pengguna dari sesi.
function get_user_id() {
    return $_SESSION['user_id'] ?? null;
}

// Mengalihkan pengguna ke URL lain dan menghentikan eksekusi skrip.
function redirect($url) {
    header("Location: " . $url);
    exit();
}

// Mengecek apakah pengguna adalah admin.
function is_admin() {
    return get_user_role() === 'admin';
}

// Mengecek apakah pengguna adalah pegawai.
function is_pegawai() {
    return get_user_role() === 'pegawai';
}

// Fungsi khusus untuk meregenerasi ID sesi setelah login berhasil, untuk keamanan ekstra.
function regenerate_session_after_login() {
    session_regenerate_id(true);
    $_SESSION['created'] = time();
}
?>