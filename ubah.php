<?php
/**
 * ubah.php
 * Menampilkan form untuk mengubah data pegawai.
 * PERBAIKAN: Menambahkan tombol Batal.
 */
include 'koneksi.php';

// Pastikan pengguna sudah login
if (!is_logged_in()) {
    redirect('login.php');
}

// Validasi ID pegawai
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    die("Aksi tidak valid: ID data tidak ditemukan atau tidak valid.");
}

$id_pegawai = $_GET['id'];
$user_id_current = get_user_id();
$query_select = "SELECT * FROM pegawai WHERE id = ?";
$stmt_select = mysqli_prepare($koneksi, $query_select);
mysqli_stmt_bind_param($stmt_select, "i", $id_pegawai);
mysqli_stmt_execute($stmt_select);
$result = mysqli_stmt_get_result($stmt_select);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    die("Data tidak ditemukan.");
}

// Jika pengguna adalah 'pegawai', pastikan mereka hanya bisa mengubah data miliknya
if (is_pegawai() && $data['user_id'] != $user_id_current) {
    die("Akses Ditolak: Anda tidak memiliki izin untuk mengubah data ini.");
}

// Gunakan konstanta global dari config.php untuk konsistensi
$display_nama = detokenize_data($data['nama_lengkap'], TOKEN_GROUP_DEFAULT, TOKEN_TEMPLATE_DEFAULT) ?: '[Gagal Memuat Data]';
$display_alamat = detokenize_data($data['alamat'], TOKEN_GROUP_DEFAULT, TOKEN_TEMPLATE_DEFAULT) ?: '[Gagal Memuat Data]';
$display_telp = detokenize_data($data['nomor_telepon'], TOKEN_GROUP_DEFAULT, TOKEN_TEMPLATE_DEFAULT) ?: '[Gagal Memuat Data]';

$page_title = 'Formulir Ubah Data Pegawai';
include 'templates/header.php';
?>

<div class="container mx-auto bg-white rounded-lg shadow-xl p-8 max-w-2xl">
    <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Formulir Ubah Data Pegawai</h2>
    <form action="proses_ubah.php" method="post" class="space-y-4">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($data['id']); ?>">
        
        <div>
            <label for="nama_lengkap" class="block text-gray-700 font-medium">Nama Lengkap</label>
            <input type="text" id="nama_lengkap" name="nama_lengkap" value="<?php echo htmlspecialchars($display_nama); ?>" required
                   class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div>
            <label for="tempat_lahir" class="block text-gray-700 font-medium">Tempat Lahir</label>
            <input type="text" id="tempat_lahir" name="tempat_lahir" value="<?php echo htmlspecialchars($data['tempat_lahir']); ?>" required
                   class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div>
            <label for="tanggal_lahir" class="block text-gray-700 font-medium">Tanggal Lahir</label>
            <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="<?php echo htmlspecialchars($data['tanggal_lahir']); ?>" required
                   class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div>
            <label class="block text-gray-700 font-medium">Jenis Kelamin</label>
            <div class="mt-2 space-x-6">
                <label class="inline-flex items-center">
                    <input type="radio" name="jenis_kelamin" value="Laki-laki" <?php if($data['jenis_kelamin']=='Laki-laki') echo 'checked'?> required class="form-radio text-blue-600 h-4 w-4">
                    <span class="ml-2 text-gray-700">Laki-laki</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="radio" name="jenis_kelamin" value="Perempuan" <?php if($data['jenis_kelamin']=='Perempuan') echo 'checked'?> class="form-radio text-pink-600 h-4 w-4">
                    <span class="ml-2 text-gray-700">Perempuan</span>
                </label>
            </div>
        </div>

        <div>
            <label for="agama" class="block text-gray-700 font-medium">Agama</label>
            <select id="agama" name="agama" required
                    class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                <option value="Islam" <?php if($data['agama']=='Islam') echo 'selected'?>>Islam</option>
                <option value="Kristen" <?php if($data['agama']=='Kristen') echo 'selected'?>>Kristen</option>
                <option value="Katolik" <?php if($data['agama']=='Katolik') echo 'selected'?>>Katolik</option>
                <option value="Hindu" <?php if($data['agama']=='Hindu') echo 'selected'?>>Hindu</option>
                <option value="Budha" <?php if($data['agama']=='Budha') echo 'selected'?>>Budha</option>
                <option value="Konghucu" <?php if($data['agama']=='Konghucu') echo 'selected'?>>Konghucu</option>
            </select>
        </div>

        <div>
            <label for="alamat" class="block text-gray-700 font-medium">Alamat</label>
            <textarea id="alamat" name="alamat" rows="4" required
                      class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"><?php echo htmlspecialchars($display_alamat); ?></textarea>
        </div>

        <div>
            <label for="nomor_telepon" class="block text-gray-700 font-medium">Nomor Telepon</label>
            <input type="text" id="nomor_telepon" name="nomor_telepon" value="<?php echo htmlspecialchars($display_telp); ?>" required
                   class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div class="flex justify-end items-center pt-4">
            
            <a href="<?php echo is_admin() ? 'dashboard_admin.php' : 'dashboard_pegawai.php'; ?>"
               class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-md shadow-md transition duration-300 mr-4">
                BATAL
            </a>

            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-md shadow-md transition duration-300">
                UBAH DATA
            </button>
            
        </div>
    </form>
</div>

<?php
include 'templates/footer.php';
?>