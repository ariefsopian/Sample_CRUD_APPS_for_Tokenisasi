<?php
/**
 * proses_ubah.php
 *
 * Memproses data dari form ubah dan memperbarui data di database menggunakan PegawaiModel.
 */

include 'koneksi.php';
require_once 'PegawaiModel.php'; // Sertakan file model baru

// Pastikan pengguna sudah login
if (!is_logged_in()) {
    redirect('login.php');
}

// Menangkap data dari form
$id = $_POST['id'];
$data_form = [
    'nama_lengkap'    => $_POST['nama_lengkap'],
    'tempat_lahir'    => $_POST['tempat_lahir'],
    'tanggal_lahir'   => $_POST['tanggal_lahir'],
    'jenis_kelamin'   => $_POST['jenis_kelamin'],
    'agama'           => $_POST['agama'],
    'alamat'          => $_POST['alamat'],
    'nomor_telepon'   => $_POST['nomor_telepon'],
];
$user_id_current = get_user_id();

// --- Validasi Kepemilikan Data (tetap di layer controller) ---
// Logika ini tetap di sini karena ini adalah validasi otorisasi, bukan logika database.
if (is_pegawai()) {
    $query_check = "SELECT id FROM pegawai WHERE id = ? AND user_id = ?";
    $stmt_check = $koneksi->prepare($query_check);
    $stmt_check->bind_param("ii", $id, $user_id_current);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    if ($result_check->num_rows == 0) {
        die("Akses Ditolak: Anda tidak memiliki izin untuk mengubah data ini.");
    }
    $stmt_check->close();
}

// 2. Buat instance dari model dan panggil metode update
$model_pegawai = new PegawaiModel($koneksi);
if ($model_pegawai->updatePegawai($id, $data_form)) {
    redirect(is_admin() ? 'dashboard_admin.php' : 'dashboard_pegawai.php');
} else {
    // Error sudah dicatat di model, tampilkan pesan umum ke pengguna
    die("Terjadi kesalahan teknis. Silakan hubungi administrator.");
}
?>