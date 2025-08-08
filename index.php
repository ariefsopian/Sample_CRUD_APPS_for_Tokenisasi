<?php
/**
 * index.php
 * Halaman utama yang mengarahkan pengguna ke dashboard yang sesuai.
 * PERBAIKAN: Mengarahkan 'pegawai' ke dashboard_pegawai.php untuk konsistensi.
 */
include 'koneksi.php';

// Jika user belum login, redirect ke halaman login
if (!is_logged_in()) {
    redirect('login.php');
}

// Redirect ke dashboard yang sesuai dengan role
if (is_admin()) {
    redirect('dashboard_admin.php');
} elseif (is_pegawai()) {
    redirect('dashboard_pegawai.php');
} else {
    // Fallback jika ada role lain atau sesi tidak valid
    redirect('login.php');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting...</title>
    <style>
        body { font-family: "Inter", sans-serif; padding: 2rem; text-align: center; }
    </style>
</head>
<body>
    <p>Mengarahkan Anda ke dashboard...</p>
</body>
</html>