<?php
/**
 * PegawaiModel.php
 *
 * Kelas Model untuk mengelola data pegawai.
 * Bertanggung jawab untuk interaksi database dan logika bisnis seperti tokenisasi.
 */

class PegawaiModel {
    private $koneksi;

    public function __construct($koneksi_db) {
        $this->koneksi = $koneksi_db;
    }

    /**
     * Menyimpan data pegawai baru ke database.
     * @param array $data Data pegawai dari form.
     * @param int|null $user_id User ID yang terasosiasi, atau null jika tidak ada.
     * @return bool True jika berhasil, False jika gagal.
     */
    public function simpanPegawai($data, $user_id = null) {
        // Proses Tokenisasi Data Sensitif
        $tokenized_nama = tokenize_data($data['nama_lengkap'], TOKEN_GROUP_DEFAULT, TOKEN_TEMPLATE_DEFAULT);
        $tokenized_alamat = tokenize_data($data['alamat'], TOKEN_GROUP_DEFAULT, TOKEN_TEMPLATE_DEFAULT);
        $tokenized_telp = tokenize_data($data['nomor_telepon'], TOKEN_GROUP_DEFAULT, TOKEN_TEMPLATE_DEFAULT);

        // Validasi hasil tokenisasi
        if ($tokenized_nama === false || $tokenized_alamat === false || $tokenized_telp === false) {
            error_log("Kegagalan tokenisasi saat menambahkan pegawai baru.");
            return false;
        }

        // Menyimpan Data ke Database dengan Prepared Statements
        $query_insert = "INSERT INTO pegawai
                            (nama_lengkap, tempat_lahir, tanggal_lahir, jenis_kelamin, agama, alamat, nomor_telepon, user_id)
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->koneksi->prepare($query_insert);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sssssssi",
                $tokenized_nama,
                $data['tempat_lahir'],
                $data['tanggal_lahir'],
                $data['jenis_kelamin'],
                $data['agama'],
                $tokenized_alamat,
                $tokenized_telp,
                $user_id
            );

            $result = $stmt->execute();
            $stmt->close();
            return $result;
        } else {
            error_log("Gagal menyiapkan statement insert: " . $this->koneksi->error);
            return false;
        }
    }

    /**
     * Memperbarui data pegawai yang sudah ada.
     * @param int $id ID pegawai.
     * @param array $data Data pegawai yang diperbarui.
     * @return bool True jika berhasil, False jika gagal.
     */
    public function updatePegawai($id, $data) {
        // Proses Re-Tokenisasi Data Sensitif
        $tokenized_nama = tokenize_data($data['nama_lengkap'], TOKEN_GROUP_DEFAULT, TOKEN_TEMPLATE_DEFAULT);
        $tokenized_alamat = tokenize_data($data['alamat'], TOKEN_GROUP_DEFAULT, TOKEN_TEMPLATE_DEFAULT);
        $tokenized_telp = tokenize_data($data['nomor_telepon'], TOKEN_GROUP_DEFAULT, TOKEN_TEMPLATE_DEFAULT);

        // Validasi hasil tokenisasi
        if ($tokenized_nama === false || $tokenized_alamat === false || $tokenized_telp === false) {
            error_log("Kegagalan re-tokenisasi untuk ID pegawai: " . $id);
            return false;
        }

        // Memperbarui Data di Database dengan Prepared Statements
        $query_update = "UPDATE pegawai SET
                            nama_lengkap=?, tempat_lahir=?, tanggal_lahir=?,
                            jenis_kelamin=?, agama=?, alamat=?, nomor_telepon=?
                         WHERE id=?";

        $stmt = $this->koneksi->prepare($query_update);
        if ($stmt) {
            $stmt->bind_param("sssssssi",
                $tokenized_nama,
                $data['tempat_lahir'],
                $data['tanggal_lahir'],
                $data['jenis_kelamin'],
                $data['agama'],
                $tokenized_alamat,
                $tokenized_telp,
                $id
            );

            $result = $stmt->execute();
            $stmt->close();
            return $result;
        } else {
            error_log("Gagal menyiapkan statement update: " . $this->koneksi->error);
            return false;
        }
    }

    /**
     * Menghapus data pegawai dari database.
     * @param int $id ID pegawai yang akan dihapus.
     * @return bool True jika berhasil, False jika gagal.
     */
    public function hapusPegawai($id) {
        $query_delete = "DELETE FROM pegawai WHERE id = ?";
        $stmt = $this->koneksi->prepare($query_delete);

        if ($stmt) {
            $stmt->bind_param("i", $id);
            $result = $stmt->execute();
            $stmt->close();
            return $result;
        } else {
            error_log("Gagal menyiapkan statement delete: " . $this->koneksi->error);
            return false;
        }
    }

    // Metode lain untuk mendapatkan data bisa ditambahkan di sini,
    // misalnya: getPegawaiById, getAllPegawai, dsb.
}
?>