<?php
/**
 * hapus.php
 *
 * Menghapus data pegawai berdasarkan ID menggunakan PegawaiModel.
 * Akses file ini sangat dibatasi dan hanya untuk admin.
 */

include 'koneksi.php';
require_once 'PegawaiModel.php'; // Sertakan file model baru

// --- Validasi Keamanan ---
// Pastikan pengguna sudah login dan adalah admin.
if (!is_logged_in() || !is_admin()) {
    redirect('login.php');
}

// Pastikan parameter ID ada dan merupakan angka
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    die("Aksi tidak valid: ID data tidak ditemukan atau tidak valid.");
}

$id = $_GET['id'];

// 2. Buat instance dari model dan panggil metode hapus
$model_pegawai = new PegawaiModel($koneksi);
if ($model_pegawai->hapusPegawai($id)) {
    redirect('dashboard_admin.php');
} else {
    // Error sudah dicatat di model, tampilkan pesan umum ke pengguna
    die("Terjadi kesalahan teknis. Silakan hubungi administrator.");
}
?>