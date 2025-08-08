<?php
/**
 * templates/header.php
 *
 * Template untuk bagian header HTML yang digunakan di banyak halaman.
 * Mengatur judul halaman secara dinamis melalui variabel $page_title.
 */
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) : 'Sistem Informasi Pegawai'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: "Inter", sans-serif; }
    </style>
</head>
<body class="bg-gray-100 p-6">