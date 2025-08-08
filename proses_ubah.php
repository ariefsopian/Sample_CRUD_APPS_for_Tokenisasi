<?php
/**
 * proses_ubah.php
 *
 * Memproses data dari form ubah, melakukan re-tokenisasi,
 * dan memperbarui data di database menggunakan prepared statements.
 */

include 'koneksi.php';

// Pastikan pengguna sudah login
if (!is_logged_in()) {
    redirect('login.php');
}

// Menangkap data dari form
$id = $_POST['id'];
$nama = $_POST['nama_lengkap'];
$tempat_lahir = $_POST['tempat_lahir'];
$tgl_lahir = $_POST['tanggal_lahir'];
$jk = $_POST['jenis_kelamin'];
$agama = $_POST['agama'];
$alamat = $_POST['alamat'];
$telp = $_POST['nomor_telepon'];
$user_id_current = get_user_id();

// --- Validasi Kepemilikan Data ---
if (is_pegawai()) {
    $query_check = "SELECT id FROM pegawai WHERE id = ? AND user_id = ?";
    $stmt_check = mysqli_prepare($koneksi, $query_check);
    mysqli_stmt_bind_param($stmt_check, "ii", $id, $user_id_current);
    mysqli_stmt_execute($stmt_check);
    $result_check = mysqli_stmt_get_result($stmt_check);
    if (mysqli_num_rows($result_check) == 0) {
        die("Akses Ditolak: Anda tidak memiliki izin untuk mengubah data ini.");
    }
    mysqli_stmt_close($stmt_check);
}

// --- Proses Re-Tokenisasi Data Sensitif ---
$token_group = "tkn.group.demo";
$token_template = "tkn.tmp.demo";

$tokenized_nama = tokenize_data($nama, $token_group, $token_template);
$tokenized_alamat = tokenize_data($alamat, $token_group, $token_template);
$tokenized_telp = tokenize_data($telp, $token_group, $token_template);

// Validasi hasil tokenisasi
if ($tokenized_nama === false || $tokenized_alamat === false || $tokenized_telp === false) {
    error_log("Kegagalan re-tokenisasi untuk ID pegawai: " . $id);
    die("Terjadi kesalahan teknis (Re-Tokenisasi Gagal). Hubungi administrator.");
}

// --- Memperbarui Data di Database dengan Prepared Statements ---
$query_update = "UPDATE pegawai SET
                    nama_lengkap=?, tempat_lahir=?, tanggal_lahir=?, 
                    jenis_kelamin=?, agama=?, alamat=?, nomor_telepon=?
                 WHERE id=?";

$stmt = mysqli_prepare($koneksi, $query_update);

if ($stmt) {
    // Ikat parameter ke statement
    // Tipe data: s=string, i=integer
    mysqli_stmt_bind_param($stmt, "sssssssi", 
        $tokenized_nama, 
        $tempat_lahir, 
        $tgl_lahir, 
        $jk, 
        $agama, 
        $tokenized_alamat, 
        $tokenized_telp, 
        $id
    );

    // Eksekusi query
    if (mysqli_stmt_execute($stmt)) {
        // Jika berhasil, arahkan kembali ke dashboard yang sesuai
        redirect(is_admin() ? 'dashboard_admin.php' : 'dashboard_pegawai.php');
    } else {
        // Jika gagal, catat error dan tampilkan pesan
        error_log("Database update error: " . mysqli_stmt_error($stmt));
        die("Error: Gagal memperbarui data. " . mysqli_stmt_error($stmt));
    }
    
    mysqli_stmt_close($stmt);
} else {
    error_log("Gagal menyiapkan statement update: " . mysqli_error($koneksi));
    die("Error sistem: Gagal menyiapkan query database.");
}
?>