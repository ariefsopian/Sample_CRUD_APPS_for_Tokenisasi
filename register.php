<?php
/**
 * register.php
 * Halaman untuk pengguna baru mendaftarkan akun.
 * PERBAIKAN: Menggunakan prepared statements untuk mencegah SQL Injection.
 */
include 'koneksi.php';

// Jika pengguna sudah login, alihkan ke dashboard yang sesuai
if (is_logged_in()) {
    redirect(is_admin() ? 'dashboard_admin.php' : 'dashboard_pegawai.php');
}

$pesan_sukses = '';
$pesan_error = '';

// Proses form saat metode request adalah POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = 'pegawai'; // Role diatur secara default

    // Validasi dasar
    if (empty($username) || empty($password) || empty($confirm_password)) {
        $pesan_error = "Semua kolom wajib diisi.";
    } elseif ($password !== $confirm_password) {
        $pesan_error = "Konfirmasi password tidak cocok.";
    } elseif (strlen($password) < 6) {
        $pesan_error = "Password minimal harus 6 karakter.";
    } else {
        // Cek apakah username sudah ada menggunakan prepared statement
        $query_check = "SELECT id FROM users WHERE username = ?";
        $stmt_check = mysqli_prepare($koneksi, $query_check);
        mysqli_stmt_bind_param($stmt_check, "s", $username);
        mysqli_stmt_execute($stmt_check);
        $result_check = mysqli_stmt_get_result($stmt_check);

        if (mysqli_num_rows($result_check) > 0) {
            $pesan_error = "Username sudah digunakan. Silakan pilih username lain.";
        } else {
            // Hash password untuk keamanan
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Simpan pengguna baru dengan prepared statement
            $query_insert = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
            $stmt_insert = mysqli_prepare($koneksi, $query_insert);
            mysqli_stmt_bind_param($stmt_insert, "sss", $username, $hashed_password, $role);
            
            if (mysqli_stmt_execute($stmt_insert)) {
                $pesan_sukses = "Pendaftaran berhasil! Silakan <a href='login.php' class='font-bold text-teal-700'>login di sini</a>.";
            } else {
                $pesan_error = "Terjadi kesalahan pada server. Silakan coba lagi.";
                error_log("Gagal registrasi: " . mysqli_stmt_error($stmt_insert));
            }
            mysqli_stmt_close($stmt_insert);
        }
        mysqli_stmt_close($stmt_check);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Pegawai</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: "Inter", sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-r from-green-500 to-teal-600 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-2xl w-full max-w-md">
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Daftar Akun Pegawai</h2>
        
        <?php if ($pesan_sukses): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?php echo $pesan_sukses; ?></span>
            </div>
        <?php endif; ?>
        <?php if ($pesan_error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?php echo htmlspecialchars($pesan_error); ?></span>
            </div>
        <?php endif; ?>

        <form action="register.php" method="post" class="space-y-5">
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                <input type="text" id="username" name="username" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500 transition duration-150 ease-in-out"
                       placeholder="Pilih username Anda">
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" id="password" name="password" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500 transition duration-150 ease-in-out"
                       placeholder="Buat password (min. 6 karakter)">
            </div>
            <div>
                <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500 transition duration-150 ease-in-out"
                       placeholder="Ulangi password Anda">
            </div>
            <button type="submit"
                    class="w-full bg-teal-600 text-white py-2 px-4 rounded-md hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition duration-150 ease-in-out font-semibold">
                DAFTAR
            </button>
        </form>
        <p class="mt-6 text-center text-gray-600">
            Sudah punya akun? <a href="login.php" class="text-teal-600 hover:text-teal-800 font-semibold">Login di sini</a>
        </p>
    </div>
</body>
</html>