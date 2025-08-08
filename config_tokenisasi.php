<?php
/**
 * config_tokenisasi.php
 *
 * Berisi semua fungsi untuk berinteraksi dengan API tokenisasi.
 * Termasuk fungsi untuk caching dan operasi massal (bulk) untuk meningkatkan performa.
 */

// Memastikan file config.php sudah dimuat sebelum file ini dijalankan.
if (!defined('TOKEN_API_BASE_URL')) {
    die("File konfigurasi utama belum dimuat.");
}

// Inisialisasi 'api_cache' di dalam sesi jika belum ada.
// Ini digunakan untuk menyimpan sementara hasil dari API agar tidak perlu memintanya berulang kali.
if (!isset($_SESSION['api_cache'])) {
    $_SESSION['api_cache'] = [];
}

/**
 * Fungsi internal (_send_curl_request) untuk mengirim permintaan ke API menggunakan cURL.
 * Dibuat sebagai fungsi 'private' (diawali underscore) untuk digunakan oleh fungsi lain di file ini.
 *
 * @param string $url URL endpoint API.
 * @param string $username Username untuk otentikasi.
 * @param string $password Password untuk otentikasi.
 * @param string $payload Data yang dikirim dalam format JSON.
 * @return string|false Respons dari API dalam bentuk JSON string, atau 'false' jika gagal.
 */
function _send_curl_request($url, $username, $password, $payload) {
    $ch = curl_init($url); // Inisialisasi cURL.
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Mengatur agar cURL mengembalikan hasil sebagai string, bukan langsung menampilkannya.
    curl_setopt($ch, CURLOPT_POST, true); // Menggunakan metode HTTP POST.
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload); // Data yang dikirim.
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Content-Length: ' . strlen($payload)]); // Header permintaan.
    curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password); // Otentikasi Basic Auth.
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); // Timeout untuk koneksi (detik).
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);      // Timeout total untuk permintaan (detik).
    
    // --- KODE PERBAIKAN UNTUK MENGABAIAKAN SERTIFIKAT SSL ---
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    // --- AKHIR KODE PERBAIKAN ---

    $response = curl_exec($ch); // Eksekusi cURL.
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE); // Mendapatkan kode status HTTP.
    $error = curl_error($ch); // Mendapatkan pesan error jika ada.
    curl_close($ch); // Menutup koneksi cURL.

    // Jika ada error cURL atau status HTTP bukan 200 (OK), tampilkan detail error.
    if ($response === false || $http_code != 200) {
        echo "<h1>API Error:</h1>";
        echo "<p>URL: " . htmlspecialchars($url) . "</p>";
        echo "<p>HTTP Code: " . htmlspecialchars($http_code) . "</p>";
        echo "<p>cURL Error: " . htmlspecialchars($error) . "</p>";
        echo "<p>Response Body: " . htmlspecialchars($response) . "</p>";
        exit();
    }

    return $response;
}

/**
 * FUNGSI INTI (ENGINE) untuk semua operasi detokenisasi, dilengkapi dengan caching.
 *
 * @param array $tokens Array dari token-token yang akan diproses.
 * @param string $tokenGroup Grup token.
 * @param string $tokenTemplate Template token.
 * @param string $user Username API.
 * @param string $pass Password API.
 * @return array Peta (map) dari [token => data_asli].
 */
function _detokenize_engine(array $tokens, $tokenGroup, $tokenTemplate, $user, $pass) {
    if (empty($tokens)) {
        return [];
    }

    $data_map = []; // Peta untuk menyimpan hasil akhir [token => data].
    $tokens_to_fetch = []; // Array untuk menampung token yang tidak ada di cache.

    // Langkah 1: Cek setiap token di dalam cache sesi.
    foreach ($tokens as $token) {
        $cacheKey = md5($token . $user); // Membuat kunci cache yang unik.
        if (isset($_SESSION['api_cache'][$cacheKey])) {
            $data_map[$token] = $_SESSION['api_cache'][$cacheKey]; // Jika ada, ambil dari cache.
        } else {
            $tokens_to_fetch[] = $token; // Jika tidak, tambahkan ke daftar yang akan diminta ke API.
        }
    }

    // Langkah 2: Jika ada token yang perlu diminta ke API.
    if (!empty($tokens_to_fetch)) {
        $payload_items = [];
        foreach ($tokens_to_fetch as $token) {
            $payload_items[] = [ "tokengroup" => $tokenGroup, "token" => $token, "tokentemplate" => $tokenTemplate ];
        }

        // Kirim permintaan massal ke API.
        $url = TOKEN_API_BASE_URL . "/detokenize";
        $payload = json_encode($payload_items);
        $response_json = _send_curl_request($url, $user, $pass, $payload);

        if ($response_json !== false) {
            $results = json_decode($response_json, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($results)) {
                // Proses hasil dari API.
                foreach ($results as $index => $item) {
                    $original_token = $tokens_to_fetch[$index];
                    if ($item['status'] === 'Succeed') {
                        // Simpan hasil ke peta dan juga ke cache untuk permintaan berikutnya.
                        $data_map[$original_token] = $item['data'];
                        $cacheKey = md5($original_token . $user);
                        $_SESSION['api_cache'][$cacheKey] = $item['data'];
                    }
                }
            }
        }
    }

    return $data_map; // Kembalikan peta hasil akhir.
}

// Fungsi publik untuk detokenisasi massal (digunakan di dashboard_admin.php).
function detokenize_bulk(array $tokens, $tokenGroup, $tokenTemplate) {
    return _detokenize_engine($tokens, $tokenGroup, $tokenTemplate, TOKEN_API_USER_DETOKENIZE, TOKEN_API_PASS_DETOKENIZE);
}

// Fungsi publik untuk masking massal (digunakan untuk nomor telepon di dashboard_admin.php).
function mask_bulk(array $tokens, $tokenGroup, $tokenTemplate) {
    return _detokenize_engine($tokens, $tokenGroup, $tokenTemplate, TOKEN_API_USER_MASKING, TOKEN_API_PASS_MASKING);
}

// Fungsi untuk detokenisasi satu data (digunakan di ubah.php, dashboard_pegawai.php).
function detokenize_data($token, $tokenGroup, $tokenTemplate) {
    if (empty($token)) return false;
    $result_map = detokenize_bulk([$token], $tokenGroup, $tokenTemplate); // Memanfaatkan fungsi massal.
    return $result_map[$token] ?? false; // Mengembalikan hasil atau 'false' jika tidak ditemukan.
}

// Fungsi untuk masking satu data.
function mask_data($token, $tokenGroup, $tokenTemplate) {
    if (empty($token)) return false;
    $result_map = mask_bulk([$token], $tokenGroup, $tokenTemplate);
    return $result_map[$token] ?? false;
}

// Fungsi untuk tokenisasi data (mengubah data asli menjadi token).
function tokenize_data($dataToTokenize, $tokenGroup, $tokenTemplate) {
    $url = TOKEN_API_BASE_URL . "/tokenize";
    $payload = json_encode([["tokengroup" => $tokenGroup, "data" => $dataToTokenize, "tokentemplate" => $tokenTemplate]]);
    $response = _send_curl_request($url, TOKEN_API_USER_TOKENIZE, TOKEN_API_PASS_TOKENIZE, $payload);
    if ($response === false) return false;
    $result = json_decode($response, true);
    if (json_last_error() === JSON_ERROR_NONE && isset($result[0]['token']) && $result[0]['status'] === 'Succeed') {
        return $result[0]['token'];
    }
    error_log("Respons API Tokenisasi tidak valid: " . $response);
    return false;
}
?>