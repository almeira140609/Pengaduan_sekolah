<?php

require_once "m_koneksi.php";

class Kategori extends Koneksi
{

    // ==============================
    // MENAMPILKAN DATA KATEGORI
    // ==============================

    public function tampil($search = "")
    {
        $search = mysqli_real_escape_string(
            $this->koneksi,
            $search
        );

        if ($search != "") {

            $query = mysqli_query(
                $this->koneksi,
                "SELECT * FROM kategori
                 WHERE nama_kategori LIKE '%$search%'
                 ORDER BY id_kategori ASC"
            );

        } else {

            $query = mysqli_query(
                $this->koneksi,
                "SELECT * FROM kategori
                 ORDER BY id_kategori ASC"
            );
        }

        return $query;
    }


    // ==============================
    // MENGAMBIL KATEGORI BERDASARKAN ID
    // ==============================

    public function ambilById($id_kategori)
    {
        $id_kategori = mysqli_real_escape_string(
            $this->koneksi,
            $id_kategori
        );

        $query = mysqli_query(
            $this->koneksi,
            "SELECT * FROM kategori
             WHERE id_kategori = '$id_kategori'"
        );

        return mysqli_fetch_assoc($query);
    }


    // ==============================
    // TAMBAH KATEGORI
    // ==============================

    public function tambah($nama_kategori)
    {
        $nama_kategori = mysqli_real_escape_string(
            $this->koneksi,
            trim($nama_kategori)
        );

        $query = mysqli_query(
            $this->koneksi,
            "INSERT INTO kategori (nama_kategori)
             VALUES ('$nama_kategori')"
        );

        return $query;
    }


    // ==============================
    // EDIT KATEGORI
    // ==============================

    public function edit($id_kategori, $nama_kategori)
    {
        $id_kategori = mysqli_real_escape_string(
            $this->koneksi,
            $id_kategori
        );

        $nama_kategori = mysqli_real_escape_string(
            $this->koneksi,
            trim($nama_kategori)
        );

        $query = mysqli_query(
            $this->koneksi,
            "UPDATE kategori
             SET nama_kategori = '$nama_kategori'
             WHERE id_kategori = '$id_kategori'"
        );

        return $query;
    }


    // ==============================
    // CEK APAKAH KATEGORI DIPAKAI OLEH DATA ASPIRASI
    // ==============================

    public function dipakaiAspirasi($id_kategori)
    {
        $id_kategori = mysqli_real_escape_string(
            $this->koneksi,
            $id_kategori
        );

        $query = mysqli_query(
            $this->koneksi,
            "SELECT COUNT(*) AS jumlah
             FROM aspirasi
             WHERE id_kategori = '$id_kategori'"
        );

        if (!$query) {
            return true;
        }

        $data = mysqli_fetch_assoc($query);

        return (int) $data['jumlah'] > 0;
    }


    // ==============================
    // HAPUS KATEGORI
    // ==============================

    public function hapus($id_kategori)
    {
        $id_kategori = mysqli_real_escape_string(
            $this->koneksi,
            $id_kategori
        );

        // Jangan hapus jika kategori masih digunakan.
        if ($this->dipakaiAspirasi($id_kategori)) {
            return false;
        }

        // Tangani kegagalan query agar tidak menampilkan Fatal Error MySQL.
        try {

            $query = mysqli_query(
                $this->koneksi,
                "DELETE FROM kategori
                 WHERE id_kategori = '$id_kategori'"
            );

            return $query && mysqli_affected_rows(
                $this->koneksi
            ) > 0;

        } catch (mysqli_sql_exception $e) {

            return false;
        }
    }

}

?>
