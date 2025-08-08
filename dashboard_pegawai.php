<?php
/**
 * dashboard_pegawai.php
 * PERBAIKAN: Menggunakan konstanta global untuk konsistensi.
 */
include 'koneksi.php';

// Pastikan hanya pegawai yang bisa mengakses halaman ini
if (!is_logged_in() || !is_pegawai()) {
    redirect('login.php');
}

$user_id = get_user_id();
$data_pegawai = null;

// Ambil data pegawai
$query_pegawai = mysqli_query($koneksi, "SELECT * FROM pegawai WHERE user_id = '$user_id'");
if (mysqli_num_rows($query_pegawai) > 0) {
    $data_pegawai = mysqli_fetch_assoc($query_pegawai);

    if ($data_pegawai) {
        // Menggunakan konstanta dari config.php
        $data_pegawai['nama_lengkap_display'] = detokenize_data($data_pegawai['nama_lengkap'], TOKEN_GROUP_DEFAULT, TOKEN_TEMPLATE_DEFAULT) ?: '[Gagal Memuat Data]';
        $data_pegawai['alamat_display'] = detokenize_data($data_pegawai['alamat'], TOKEN_GROUP_DEFAULT, TOKEN_TEMPLATE_DEFAULT) ?: '[Gagal Memuat Data]';
        $data_pegawai['nomor_telepon_display'] = mask_data($data_pegawai['nomor_telepon'], TOKEN_GROUP_DEFAULT, TOKEN_TEMPLATE_MASKING) ?: '[Gagal Memuat Data]';
    }
}

$page_title = 'Dashboard Pegawai';
include 'templates/header.php';
?>

<div class="container mx-auto bg-white rounded-lg shadow-xl p-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold text-gray-800">Dashboard Pegawai</h2>
        <div class="flex items-center space-x-4">
            <span class="text-gray-700 font-semibold">Selamat datang, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
            <a href="logout.php" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-md shadow-md transition duration-300">
                Logout
            </a>
        </div>
    </div>

    <?php if ($data_pegawai): ?>
        <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-800 p-6 rounded-md" role="alert">
            <p class="font-bold text-xl mb-4">Informasi Data Diri Anda:</p>
            <div class="space-y-2">
                <p><strong>Nama Lengkap:</strong> <?php echo htmlspecialchars($data_pegawai['nama_lengkap_display']); ?></p>
                <p><strong>Tempat, Tgl Lahir:</strong> <?php echo htmlspecialchars($data_pegawai['tempat_lahir'] . ', ' . date('d F Y', strtotime($data_pegawai['tanggal_lahir']))); ?></p>
                <p><strong>Jenis Kelamin:</strong> <?php echo htmlspecialchars($data_pegawai['jenis_kelamin']); ?></p>
                <p><strong>Agama:</strong> <?php echo htmlspecialchars($data_pegawai['agama']); ?></p>
                <p><strong>Alamat:</strong> <?php echo htmlspecialchars($data_pegawai['alamat_display']); ?></p>
                <p><strong>Nomor Telepon:</strong> <?php echo htmlspecialchars($data_pegawai['nomor_telepon_display']); ?></p>
            </div>
            <div class="mt-6">
                <a href="ubah.php?id=<?php echo $data_pegawai['id']; ?>" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-md shadow-md transition duration-300">
                    Ubah Data Diri
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="bg-yellow-50 border-l-4 border-yellow-500 text-yellow-800 p-6 rounded-md" role="alert">
            <p class="font-bold text-xl">Data Diri Belum Lengkap</p>
            <p class="mt-2">Anda belum melengkapi data diri Anda. Silakan klik tombol di bawah untuk mengisi formulir.</p>
            <div class="mt-4">
                <a href="tambah.php" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md shadow-md transition duration-300">
                    Isi Formulir Data Diri
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php
include 'templates/footer.php';
?>