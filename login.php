<?php
/**
 * login.php
 * Halaman untuk login pengguna.
 * PERBAIKAN: Mengintegrasikan fungsi regenerate_session_after_login().
 */
include 'koneksi.php';

// Jika pengguna sudah login, alihkan ke dashboard yang sesuai
if (is_logged_in()) {
    redirect(is_admin() ? 'dashboard_admin.php' : 'dashboard_pegawai.php');
}

$pesan_error = '';

// Proses form saat metode request adalah POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT id, username, password, role FROM users WHERE username = ?";
    $stmt = mysqli_prepare($koneksi, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);

        if ($user && password_verify($password, $user['password'])) {
            // PENTING: Regenerasi ID Sesi setelah login berhasil
            regenerate_session_after_login();
            
            // Set variabel sesi
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            // Arahkan berdasarkan peran
            redirect(is_admin() ? 'dashboard_admin.php' : 'dashboard_pegawai.php');
        } else {
            $pesan_error = "Username atau password yang Anda masukkan salah.";
        }
        
        mysqli_stmt_close($stmt);
    } else {
        error_log("Gagal menyiapkan statement login: " . mysqli_error($koneksi));
        $pesan_error = "Terjadi kesalahan pada sistem. Silakan coba lagi.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Sistem Informasi Pegawai</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: "Inter", sans-serif; }
    </style>
</head>
<body class="bg-gradient-to-r from-blue-500 to-indigo-600 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-2xl w-full max-w-md">
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Login Pegawai</h2>
        
        <?php if ($pesan_error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?php echo htmlspecialchars($pesan_error); ?></span>
            </div>
        <?php endif; ?>

        <form action="login.php" method="post" class="space-y-5">
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                <input type="text" id="username" name="username" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                       placeholder="Masukkan username Anda">
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" id="password" name="password" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                       placeholder="Masukkan password Anda">
            </div>
            <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150 ease-in-out font-semibold">
                LOGIN
            </button>
        </form>
        <p class="mt-6 text-center text-gray-600">
            Belum punya akun? <a href="register.php" class="text-blue-600 hover:text-blue-800 font-semibold">Daftar di sini</a>
        </p>
    </div>
</body>
</html>