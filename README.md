# Sistem Informasi Pegawai (SIMPEG)

Aplikasi web sederhana berbasis PHP dan MySQL untuk mengelola data pegawai. Sistem ini dirancang dengan fokus pada keamanan data sensitif menggunakan teknik tokenisasi dan pemisahan peran pengguna (Admin dan Pegawai).

## Fitur Utama

-   **Manajemen Pengguna:** Mendukung peran Admin dan Pegawai dengan proses registrasi dan login yang aman.
-   **Autentikasi Aman:** Menggunakan *password hashing* (`password_hash()`) dan *prepared statements* untuk mencegah serangan SQL Injection.
-   **Tokenisasi Data:** Data sensitif seperti nama lengkap, alamat, dan nomor telepon tidak disimpan dalam bentuk teks biasa, melainkan dalam bentuk token.
-   **Dashboard Berdasarkan Peran:**
    -   **Admin:** Memiliki akses penuh untuk melihat, menambah, mengubah, dan menghapus seluruh data pegawai.
    -   **Pegawai:** Hanya dapat melihat dan mengubah data pribadinya sendiri.
-   **Fungsionalitas CRUD:** Mendukung operasi dasar (Create, Read, Update, Delete) untuk data pegawai.

## Persyaratan Sistem

-   Web Server (seperti XAMPP, WAMP, atau LAMP)
-   PHP 8.0 atau yang lebih baru
-   MySQL atau MariaDB
-   Git (opsional, untuk mengelola kode sumber)

## Panduan Deployment

Ikuti langkah-langkah di bawah ini untuk menjalankan aplikasi secara lokal.

### 1. Kloning Repositori

Unduh proyek dari GitHub ke direktori web server Anda (misalnya, `C:\xampp\htdocs\`).

### 2. Konfigurasi Database

Buat database baru bernama `db_pegawai` di phpMyAdmin, lalu impor skema database dari file `db_pegawai.sql`.

### 3. Konfigurasi Aplikasi

Buat file konfigurasi dengan langkah-langkah berikut:
-   Salin file `config.example.php` menjadi `config.php`.
-   Buka file `config.php` dengan editor teks.
-   **Sesuaikan konfigurasi database:**
    Ganti `your_db_password` dengan kata sandi database Anda. Pastikan `DB_HOST`, `DB_USER`, dan `DB_NAME` sudah benar.
    ```php
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', 'your_db_password'); // Ganti ini
    define('DB_NAME', 'db_pegawai');
    ```

-   **Sesuaikan konfigurasi API tokenisasi:**
    -   Ganti `https://your-api-hostname/vts/rest/v2.0` dengan URL API tokenisasi yang valid.
    -   Ganti semua placeholder `your_...` (contoh: `your_tokenize_user`, `your_tokenize_password`) dengan kredensial API yang Anda miliki.
    -   Pastikan `TOKEN_GROUP_DEFAULT`, `TOKEN_TEMPLATE_DEFAULT`, dan `TOKEN_TEMPLATE_MASKING` juga sesuai dengan konfigurasi API Anda.
    ```php
    define('TOKEN_API_BASE_URL', '[https://ptmkpdttkn01a.pertamina.com/vts/rest/v2.0](https://ptmkpdttkn01a.pertamina.com/vts/rest/v2.0)'); // Sesuaikan URL
    define('TOKEN_API_USER_TOKENIZE', 'your_tokenize_user');
    define('TOKEN_API_PASS_TOKENIZE', 'your_tokenize_password');
    // ... kredensial lainnya
    ```

-   Simpan file `config.php`.

> **PENTING:** File `config.php` berisi informasi sensitif. Pastikan file ini tidak diunggah ke repositori publik dengan menambahkannya ke `.gitignore`.

### 4. Akses Aplikasi

Buka peramban web Anda dan navigasikan ke URL berikut:
http://localhost/simpeg/
Anda akan diarahkan ke halaman login. Untuk memulai, Anda dapat membuat akun baru melalui halaman registrasi.