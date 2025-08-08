<?php
/**
 * proses_simpan.php
 *
 * Memproses data dari form tambah, melakukan tokenisasi,
 * dan menyimpan data ke database menggunakan prepared statements.
 * PERBAIKAN: Menambahkan logika INSERT dan redirect yang hilang.
 */

include 'koneksi.php';

// Pastikan pengguna sudah login
if (!is_logged_in()) {
    redirect('login.php');
}

// Hanya admin atau pegawai yang bisa menambahkan data (sesuaikan dengan aturan bisnis Anda)
// Untuk saat ini, kita asumsikan hanya admin dari alur tambah.php
if (!is_admin()) {
    die("Akses ditolak. Anda tidak memiliki izin untuk melakukan aksi ini.");
}

// 1. Menangkap data dari form
$nama_lengkap = $_POST['nama_lengkap'];
$tempat_lahir = $_POST['tempat_lahir'];
$tanggal_lahir = $_POST['tanggal_lahir'];
$jenis_kelamin = $_POST['jenis_kelamin'];
$agama = $_POST['agama'];
$alamat = $_POST['alamat'];
$nomor_telepon = $_POST['nomor_telepon'];

// ID User yang menambahkan data (dalam kasus ini admin)
// Untuk pegawai baru, user_id bisa diatur nanti saat mereka membuat akun
// atau bisa di-assign oleh admin. Untuk simple, kita set NULL.
$user_id = NULL; 

// 2. Proses Tokenisasi Data Sensitif
// Gunakan konstanta dari config.php
$tokenized_nama = tokenize_data($nama_lengkap, TOKEN_GROUP_DEFAULT, TOKEN_TEMPLATE_DEFAULT);
$tokenized_alamat = tokenize_data($alamat, TOKEN_GROUP_DEFAULT, TOKEN_TEMPLATE_DEFAULT);
$tokenized_telp = tokenize_data($nomor_telepon, TOKEN_GROUP_DEFAULT, TOKEN_TEMPLATE_DEFAULT);

// Validasi hasil tokenisasi
if ($tokenized_nama === false || $tokenized_alamat === false || $tokenized_telp === false) {
    error_log("Kegagalan tokenisasi saat menambahkan pegawai baru.");
    die("Terjadi kesalahan teknis (Tokenisasi Gagal). Hubungi administrator.");
}

// 3. Menyimpan Data ke Database dengan Prepared Statements
$query_insert = "INSERT INTO pegawai 
                    (nama_lengkap, tempat_lahir, tanggal_lahir, jenis_kelamin, agama, alamat, nomor_telepon, user_id) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($koneksi, $query_insert);

if ($stmt) {
    // Ikat parameter ke statement (s = string, i = integer)
    // Karena user_id bisa NULL, kita cek dulu. Jika ada nilainya, tipe 'i', jika NULL, tipe 's' (tapi dikirim sbg NULL)
    // Namun cara paling mudah adalah membiarkannya sebagai NULL langsung di query jika tidak ada,
    // atau di sini kita akan bind sebagai integer (karena kolomnya INT).
    // Jika tidak ada user id yang diasosiasikan, pastikan kolom user_id di DB memperbolehkan NULL.
    // Dari db_pegawai.sql, user_id mengizinkan NULL.
    mysqli_stmt_bind_param($stmt, "sssssssi", 
        $tokenized_nama, 
        $tempat_lahir, 
        $tanggal_lahir, 
        $jenis_kelamin, 
        $agama, 
        $tokenized_alamat, 
        $tokenized_telp,
        $user_id // Mengikat user_id yang bernilai NULL
    );

    // Eksekusi query
    if (mysqli_stmt_execute($stmt)) {
        // 4. Jika berhasil, arahkan kembali ke dashboard admin
        redirect('dashboard_admin.php');
    } else {
        // Jika gagal, catat error dan tampilkan pesan
        error_log("Database insert error: " . mysqli_stmt_error($stmt));
        die("Error: Gagal menyimpan data. " . mysqli_stmt_error($stmt));
    }
    
    mysqli_stmt_close($stmt);
} else {
    error_log("Gagal menyiapkan statement insert: " . mysqli_error($koneksi));
    die("Error sistem: Gagal menyiapkan query database.");
}
?>