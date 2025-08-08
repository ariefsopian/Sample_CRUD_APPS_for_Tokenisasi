<?php
/**
 * hapus.php
 *
 * Menghapus data pegawai berdasarkan ID menggunakan prepared statement.
 * Akses file ini sangat dibatasi dan hanya untuk admin.
 */

include 'koneksi.php';

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

// Siapkan query untuk menghapus data dengan prepared statement
$query_delete = "DELETE FROM pegawai WHERE id = ?";
$stmt = mysqli_prepare($koneksi, $query_delete);

if ($stmt) {
    // Ikat parameter ke statement. 'i' berarti integer.
    mysqli_stmt_bind_param($stmt, "i", $id);

    // Eksekusi statement
    if (mysqli_stmt_execute($stmt)) {
        // Jika berhasil, arahkan kembali ke dashboard admin
        redirect('dashboard_admin.php');
    } else {
        // Jika gagal, catat error dan tampilkan pesan
        error_log("Database delete error: " . mysqli_stmt_error($stmt));
        die("Error: Gagal menghapus data. " . mysqli_stmt_error($stmt));
    }

    mysqli_stmt_close($stmt);
} else {
    error_log("Gagal menyiapkan statement delete: " . mysqli_error($koneksi));
    die("Error sistem: Gagal menyiapkan query database.");
}
?>