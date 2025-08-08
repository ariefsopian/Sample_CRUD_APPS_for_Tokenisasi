<?php
/**
 * export_excel.php
 *
 * Mengambil data pegawai, mendetokenisasi, dan mengekspornya ke format CSV.
 */

include 'koneksi.php';

// Pastikan hanya admin yang bisa mengakses halaman ini
if (!is_logged_in() || !is_admin()) {
    die("Akses ditolak. Anda tidak memiliki izin untuk melakukan aksi ini.");
}

// Persiapan untuk ekspor CSV
$filename = "data_pegawai_" . date('Ymd_His') . ".csv";

// Set header HTTP agar browser mengunduh file
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '"');

// Buka output stream
$output = fopen('php://output', 'w');

// Tulis header kolom ke file CSV
fputcsv($output, array('No', 'Nama Lengkap', 'Tempat Lahir', 'Tanggal Lahir', 'Jenis Kelamin', 'Agama', 'Alamat', 'Nomor Telepon'));

// --- TAHAP 1: KUMPULKAN SEMUA DATA DAN TOKEN DARI DATABASE ---
$semua_pegawai = [];
$nama_tokens = [];
$alamat_tokens = [];
$telp_tokens = [];

$query_result = mysqli_query($koneksi, "SELECT * FROM pegawai ORDER BY id DESC");

if ($query_result && mysqli_num_rows($query_result) > 0) {
    while ($data = mysqli_fetch_assoc($query_result)) {
        $semua_pegawai[] = $data;
        if (!empty($data['nama_lengkap'])) $nama_tokens[] = $data['nama_lengkap'];
        if (!empty($data['alamat'])) $alamat_tokens[] = $data['alamat'];
        if (!empty($data['nomor_telepon'])) $telp_tokens[] = $data['nomor_telepon'];
    }
}

$nama_tokens = array_unique($nama_tokens);
$alamat_tokens = array_unique($alamat_tokens);
$telp_tokens = array_unique($telp_tokens);

// --- TAHAP 2: LAKUKAN PANGGILAN API SECARA MASSAL (BULK) ---
$nama_map = detokenize_bulk($nama_tokens, TOKEN_GROUP_DEFAULT, TOKEN_TEMPLATE_DEFAULT) ?: [];
$alamat_map = detokenize_bulk($alamat_tokens, TOKEN_GROUP_DEFAULT, TOKEN_TEMPLATE_DEFAULT) ?: [];
// Di sini kita gunakan detokenize biasa agar nomor telepon tidak di-masking saat diekspor
$telp_map = detokenize_bulk($telp_tokens, TOKEN_GROUP_DEFAULT, TOKEN_TEMPLATE_DEFAULT) ?: [];

// --- TAHAP 3: TULIS DATA KE FILE CSV ---
if (!empty($semua_pegawai)) {
    $no = 1;
    foreach($semua_pegawai as $data) {
        $display_nama = $nama_map[$data['nama_lengkap']] ?? '[Gagal Memuat]';
        $display_alamat = $alamat_map[$data['alamat']] ?? '[Gagal Memuat]';
        $display_telp = $telp_map[$data['nomor_telepon']] ?? '[Gagal Memuat]';
        
        $row = array(
            $no++,
            $display_nama,
            $data['tempat_lahir'],
            $data['tanggal_lahir'],
            $data['jenis_kelamin'],
            $data['agama'],
            $display_alamat,
            $display_telp
        );
        fputcsv($output, $row);
    }
}

// Tutup output stream
fclose($output);
exit();