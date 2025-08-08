<?php
include 'koneksi.php';

// Pastikan hanya pendaftar yang bisa mengakses halaman ini
if (!is_logged_in() || !is_pendaftar()) {
    redirect('login.php');
}

$user_id = get_user_id();

// Cek apakah pendaftar ini sudah mendaftarkan siswa
$query_check = mysqli_query($koneksi, "SELECT id FROM calon_siswa WHERE user_id = '$user_id'");
if (mysqli_num_rows($query_check) > 0) {
    // Jika sudah ada, redirect ke dashboard pendaftar
    redirect('dashboard_pendaftar.php');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Pendaftaran Siswa Baru</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: "Inter", sans-serif; }
        label { margin-top: 1rem; display: block; font-weight: 500; }
        input[type=radio] { margin-right: 0.5rem; }
    </style>
</head>
<body class="bg-gray-100 p-6">
    <div class="container mx-auto bg-white rounded-lg shadow-xl p-8 max-w-2xl">
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Formulir Pendaftaran Siswa Baru</h2>
        <form action="proses_simpan.php" method="post" class="space-y-4">
            <!-- Hidden input for user_id -->
            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">

            <div>
                <label for="nama_lengkap" class="block text-gray-700">Nama Peserta Didik</label>
                <input type="text" id="nama_lengkap" name="nama_lengkap" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label for="tempat_lahir" class="block text-gray-700">Tempat Lahir</label>
                <input type="text" id="tempat_lahir" name="tempat_lahir" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label for="tanggal_lahir" class="block text-gray-700">Tanggal Lahir</label>
                <input type="date" id="tanggal_lahir" name="tanggal_lahir" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label class="block text-gray-700">Jenis Kelamin</label>
                <div class="mt-2">
                    <label class="inline-flex items-center">
                        <input type="radio" name="jenis_kelamin" value="Laki-laki" required class="form-radio text-blue-600">
                        <span class="ml-2 text-gray-700">Laki-laki</span>
                    </label>
                    <label class="inline-flex items-center ml-6">
                        <input type="radio" name="jenis_kelamin" value="Perempuan" class="form-radio text-pink-600">
                        <span class="ml-2 text-gray-700">Perempuan</span>
                    </label>
                </div>
            </div>

            <div>
                <label for="agama" class="block text-gray-700">Agama</label>
                <select id="agama" name="agama" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    <option value="">--Pilih Agama--</option>
                    <option value="Islam">Islam</option>
                    <option value="Kristen">Kristen</option>
                    <option value="Katolik">Katolik</option>
                    <option value="Hindu">Hindu</option>
                    <option value="Budha">Budha</option>
                    <option value="Konghucu">Konghucu</option>
                </select>
            </div>

            <div>
                <label for="alamat" class="block text-gray-700">Alamat Tinggal</label>
                <textarea id="alamat" name="alamat" rows="4" required
                          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"></textarea>
            </div>

            <div>
                <label for="nomor_telepon" class="block text-gray-700">Nomor Telepon</label>
                <input type="text" id="nomor_telepon" name="nomor_telepon" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-md shadow-md transition duration-300">
                    SIMPAN DATA
                </button>
            </div>
        </form>
    </div>
</body>
</html>
