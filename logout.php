<?php
include 'koneksi.php'; // Sertakan file koneksi.php untuk memulai sesi
session_destroy(); // Hancurkan semua data sesi
redirect('login.php'); // Redirect kembali ke halaman login
?>
