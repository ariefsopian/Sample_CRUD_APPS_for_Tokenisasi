<?php
/**
 * dashboard_admin.php
 * Halaman utama untuk admin, menampilkan seluruh data pegawai.
 * Halaman ini dioptimalkan menggunakan panggilan API massal untuk performa yang cepat.
 */
include 'koneksi.php';

// Keamanan: Pastikan hanya pengguna yang sudah login dan berperan sebagai 'admin' yang bisa mengakses.
if (!is_logged_in() || !is_admin()) {
    redirect('login.php');
}

// Mengatur judul halaman yang akan ditampilkan di tag <title> HTML.
$page_title = 'Dashboard Admin - Manajemen Data Pegawai';
// Memuat bagian header HTML (tag <!DOCTYPE>, <head>, dan awal <body>).
include 'templates/header.php';

// --- TAHAP 1: KUMPULKAN SEMUA DATA DAN TOKEN DARI DATABASE ---
$semua_pegawai = [];  // Array untuk menyimpan semua baris data pegawai dari database.
$nama_tokens = [];    // Array untuk mengumpulkan semua token nama lengkap.
$alamat_tokens = [];  // Array untuk mengumpulkan semua token alamat.
$telp_tokens = [];    // Array untuk mengumpulkan semua token nomor telepon.

// Mengambil semua data dari tabel 'pegawai', diurutkan dari ID terbaru.
$query_result = mysqli_query($koneksi, "SELECT * FROM pegawai ORDER BY id DESC");

// Jika query berhasil dan ada data yang ditemukan.
if ($query_result && mysqli_num_rows($query_result) > 0) {
    // Loop melalui setiap baris data yang ditemukan.
    while ($data = mysqli_fetch_assoc($query_result)) {
        $semua_pegawai[] = $data; // Simpan seluruh baris data.
        
        // Kumpulkan token dari setiap kolom yang dienkripsi.
        if (!empty($data['nama_lengkap'])) $nama_tokens[] = $data['nama_lengkap'];
        if (!empty($data['alamat'])) $alamat_tokens[] = $data['alamat'];
        if (!empty($data['nomor_telepon'])) $telp_tokens[] = $data['nomor_telepon'];
    }
}

// Menghapus duplikat token untuk efisiensi. API tidak perlu memproses token yang sama berulang kali.
$nama_tokens = array_unique($nama_tokens);
$alamat_tokens = array_unique($alamat_tokens);
$telp_tokens = array_unique($telp_tokens);


// --- TAHAP 2: LAKUKAN PANGGILAN API SECARA MASSAL (BULK) ---
// Hanya ada 3 panggilan API yang terjadi di sini, tidak peduli berapa banyak data pegawai.
// Ini adalah kunci dari performa halaman yang cepat.

// Panggil fungsi 'detokenize_bulk' untuk mendapatkan semua data nama asli.
$nama_map = detokenize_bulk($nama_tokens, TOKEN_GROUP_DEFAULT, TOKEN_TEMPLATE_DEFAULT) ?: [];
// Panggil fungsi 'detokenize_bulk' untuk mendapatkan semua data alamat asli.
$alamat_map = detokenize_bulk($alamat_tokens, TOKEN_GROUP_DEFAULT, TOKEN_TEMPLATE_DEFAULT) ?: [];
// Panggil fungsi 'mask_bulk' untuk mendapatkan semua nomor telepon yang sudah di-masking.
// Menggunakan template standar karena API sudah dikonfigurasi untuk auto-masking.
$telp_map = mask_bulk($telp_tokens, TOKEN_GROUP_DEFAULT, TOKEN_TEMPLATE_DEFAULT) ?: [];

?>

<div class="container mx-auto bg-white rounded-lg shadow-xl p-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-gray-800">Manajemen Data Pegawai</h2>
        <a href="logout.php" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-md shadow-md">Logout</a>
    </div>

    <div class="mb-6 flex justify-start space-x-4">
        <a href="tambah.php" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md shadow-md">+ Tambah Data Pegawai</a>
        <a href="export_excel.php" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-md shadow-md">Export ke Excel</a>
    </div>

    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full bg-white">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="py-3 px-4 text-left uppercase font-semibold text-sm">No</th>
                    <th class="py-3 px-4 text-left uppercase font-semibold text-sm">Nama Lengkap</th>
                    <th class="py-3 px-4 text-left uppercase font-semibold text-sm">Tempat & Tgl Lahir</th>
                    <th class="py-3 px-4 text-left uppercase font-semibold text-sm">Jenis Kelamin</th>
                    <th class="py-3 px-4 text-left uppercase font-semibold text-sm">Agama</th>
                    <th class="py-3 px-4 text-left uppercase font-semibold text-sm">Alamat</th>
                    <th class="py-3 px-4 text-left uppercase font-semibold text-sm">No. Telepon</th>
                    <th class="py-3 px-4 text-center uppercase font-semibold text-sm">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 text-sm font-light">
                <?php if (!empty($semua_pegawai)) : ?>
                    <?php $no = 1; foreach($semua_pegawai as $data) : ?>
                        <?php
                            // --- TAHAP 3: TAMPILKAN DATA DARI HASIL PANGGILAN MASSAL ---
                            // Tidak ada lagi panggilan API di dalam loop ini. Data diambil dari 'map' yang sudah dibuat di Tahap 2.
                            
                            $display_nama = $nama_map[$data['nama_lengkap']] ?? '[Gagal Memuat]';
                            $display_alamat = $alamat_map[$data['alamat']] ?? '[Gagal Memuat]';
                            $display_telp = $telp_map[$data['nomor_telepon']] ?? '[Gagal Memuat]';
                            
                            // Masking tanggal lahir secara manual di PHP dengan hanya menampilkan tahun.
                            $tanggal_lahir_formatted = '**, **** ' . date('Y', strtotime($data['tanggal_lahir']));
                        ?>
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="py-3 px-4 text-left"><?php echo $no++; ?></td>
                            <td class="py-3 px-4 text-left"><?php echo htmlspecialchars($display_nama); ?></td>
                            <td class="py-3 px-4 text-left"><?php echo htmlspecialchars($data['tempat_lahir'] . ', ' . $tanggal_lahir_formatted); ?></td>
                            <td class="py-3 px-4 text-left"><?php echo htmlspecialchars($data['jenis_kelamin']); ?></td>
                            <td class="py-3 px-4 text-left"><?php echo htmlspecialchars($data['agama']); ?></td>
                            <td class="py-3 px-4 text-left"><?php echo htmlspecialchars($display_alamat); ?></td>
                            <td class="py-3 px-4 text-left"><?php echo htmlspecialchars($display_telp); ?></td>
                            
                            <td class="py-3 px-4 text-center">
                                <div class="flex item-center justify-center space-x-4">
                                    <a href="ubah.php?id=<?php echo $data['id']; ?>" title="Ubah Data" class="p-2 rounded-full hover:bg-yellow-100 transition duration-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-600" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                            <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                    <a href="hapus.php?id=<?php echo $data['id']; ?>" title="Hapus Data" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')" class="p-2 rounded-full hover:bg-red-100 transition duration-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm4 0a1 1 0 012 0v6a1 1 0 11-2 0V8z" clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="8" class="text-center py-4 text-gray-500">
                            Belum ada data pegawai yang ditambahkan.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
// Memuat bagian footer HTML (penutup tag <body> dan <html>).
include 'templates/footer.php';
?>