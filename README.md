<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>README - Sistem Informasi Pegawai (SIMPEG)</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; max-width: 800px; margin: 0 auto; padding: 20px; background-color: #f4f4f4; }
        h1, h2, h3 { color: #2c3e50; }
        h1 { border-bottom: 2px solid #3498db; padding-bottom: 10px; }
        code { background-color: #e9e9e9; padding: 2px 5px; border-radius: 4px; font-family: 'Courier New', Courier, monospace; }
        pre code { display: block; padding: 10px; background-color: #2c3e50; color: #ecf0f1; overflow-x: auto; }
        ul { list-style-type: none; padding-left: 20px; }
        li { margin-bottom: 10px; }
        .note { background-color: #f39c12; color: white; padding: 10px; border-radius: 5px; }
    </style>
</head>
<body>

    <h1>Sistem Informasi Pegawai (SIMPEG)</h1>
    <p>Aplikasi web sederhana berbasis PHP dan MySQL untuk mengelola data pegawai. Sistem ini dirancang dengan fokus pada keamanan data sensitif menggunakan teknik tokenisasi dan pemisahan peran pengguna (Admin dan Pegawai).</p>

    <h2>Fitur Utama</h2>
    <ul>
        <li><strong>Manajemen Pengguna:</strong> Mendukung peran Admin dan Pegawai dengan proses registrasi dan login yang aman.</li>
        <li><strong>Autentikasi Aman:</strong> Menggunakan *password hashing* (`password_hash()`) dan *prepared statements* untuk mencegah serangan SQL Injection.</li>
        <li><strong>Tokenisasi Data:</strong> Data sensitif seperti nama lengkap, alamat, dan nomor telepon tidak disimpan dalam bentuk teks biasa, melainkan dalam bentuk token.</li>
        <li><strong>Dashboard Berdasarkan Peran:</strong>
            <ul>
                <li><strong>Admin:</strong> Memiliki akses penuh untuk melihat, menambah, mengubah, dan menghapus seluruh data pegawai.</li>
                <li><strong>Pegawai:</strong> Hanya dapat melihat dan mengubah data pribadinya sendiri.</li>
            </ul>
        </li>
        <li><strong>Fungsionalitas CRUD:</strong> Mendukung operasi dasar (Create, Read, Update, Delete) untuk data pegawai.</li>
    </ul>

    <h2>Persyaratan Sistem</h2>
    <ul>
        <li>Web Server (seperti XAMPP, WAMP, atau LAMP)</li>
        <li>PHP 8.0 atau yang lebih baru</li>
        <li>MySQL atau MariaDB</li>
        <li>Git (opsional, untuk mengelola kode sumber)</li>
    </ul>

    <h2>Panduan Deployment</h2>
    <p>Ikuti langkah-langkah di bawah ini untuk menjalankan aplikasi secara lokal.</p>

    <h3>1. Kloning Repositori</h3>
    <p>Unduh proyek dari GitHub ke direktori web server Anda (misalnya, <code>C:\xampp\htdocs\</code>).</p>
    <pre><code>git clone [URL_REPOSITORI_ANDA] simpeg</code></pre>

    <h3>2. Konfigurasi Database</h3>
    <p>Buat database baru bernama <code>db_pegawai</code> di phpMyAdmin, lalu impor skema database dari file <code>db_pegawai.sql</code>.</p>

    <h3>3. Konfigurasi Aplikasi</h3>
    <p>Buat file konfigurasi dengan langkah-langkah berikut:</p>
    <ul>
        <li>Salin file <code>config.example.php</code> menjadi <code>config.php</code>.</li>
        <li>Buka file <code>config.php</code>.</li>
        <li>Isi kredensial database Anda (<code>DB_USER</code>, <code>DB_PASS</code>, dll.).</li>
        <li>Perbarui URL API tokenisasi serta kredensialnya sesuai dengan yang Anda dapatkan dari administrator API.</li>
        <li>Simpan file <code>config.php</code>.</li>
    </ul>

    <div class="note">
        <p><strong>PENTING:</strong> File <code>config.php</code> berisi informasi sensitif. Pastikan file ini tidak diunggah ke repositori publik dengan menambahkannya ke <code>.gitignore</code>.</p>
    </div>

    <h3>4. Akses Aplikasi</h3>
    <p>Buka peramban web Anda dan navigasikan ke URL berikut:</p>
    <pre><code>http://localhost/simpeg/</code></pre>
    <p>Anda akan diarahkan ke halaman login. Untuk memulai, Anda dapat membuat akun baru melalui halaman registrasi.</p>

</body>
</html>