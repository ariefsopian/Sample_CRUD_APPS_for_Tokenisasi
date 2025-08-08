<?php
include 'koneksi.php';

// Pastikan hanya admin yang bisa mengakses halaman ini
if (!is_logged_in() || !is_admin()) {
    redirect('login.php');
}

$page_title = 'Formulir Tambah Data Pegawai';
include 'templates/header.php';
?>

<div class="container mx-auto bg-white rounded-lg shadow-xl p-8 max-w-2xl">
    <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Formulir Tambah Data Pegawai</h2>
    <form action="proses_simpan.php" method="post" class="space-y-4">
        
        <div>
            <label for="nama_lengkap" class="block text-gray-700">Nama Pegawai</label>
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

        <div class="flex justify-end items-center pt-4">
            
            <a href="dashboard_admin.php"
               class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-md shadow-md transition duration-300 mr-4">
                BATAL
            </a>

            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-md shadow-md transition duration-300">
                SIMPAN DATA
            </button>
        </div>
    </form>
</div>

<?php
include 'templates/footer.php';
?>