<?php
/**
 * proses_simpan.php
 *
 * Memproses data dari form tambah, melakukan tokenisasi,
 * dan menyimpan data ke database menggunakan PegawaiModel.
 */

include 'koneksi.php';
require_once 'PegawaiModel.php'; // Sertakan file model baru

// Pastikan pengguna sudah login
if (!is_logged_in()) {
    redirect('login.php');
}

// Hanya admin yang bisa menambahkan data
if (!is_admin()) {
    die("Akses ditolak. Anda tidak memiliki izin untuk melakukan aksi ini.");
}

// 1. Menangkap data dari form
$data_form = [
    'nama_lengkap'    => $_POST['nama_lengkap'],
    'tempat_lahir'    => $_POST['tempat_lahir'],
    'tanggal_lahir'   => $_POST['tanggal_lahir'],
    'jenis_kelamin'   => $_POST['jenis_kelamin'],
    'agama'           => $_POST['agama'],
    'alamat'          => $_POST['alamat'],
    'nomor_telepon'   => $_POST['nomor_telepon'],
];
$user_id = NULL;

// 2. Buat instance dari model dan panggil metode simpan
$model_pegawai = new PegawaiModel($koneksi);
if ($model_pegawai->simpanPegawai($data_form, $user_id)) {
    redirect('dashboard_admin.php');
} else {
    // Error sudah dicatat di model, tampilkan pesan umum ke pengguna
    die("Terjadi kesalahan teknis. Silakan hubungi administrator.");
}
?>