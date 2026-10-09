<?php

require_once "m_koneksi.php";

class Kategori extends Koneksi
{
public function tampil($search = "")
{
    $search = mysqli_real_escape_string($this->koneksi, $search);

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
            "SELECT * FROM kategori ORDER BY id_kategori ASC"
        );
    }

    return $query;
}

    public function ambilById($id_kategori)
    {
        $query = mysqli_query(
            $this->koneksi,
            "SELECT * FROM kategori
             WHERE id_kategori = '$id_kategori'"
        );

        return mysqli_fetch_assoc($query);
    }
public function tambah($nama_kategori)
{
    $query = mysqli_query(
        $this->koneksi,
        "INSERT INTO kategori
        (nama_kategori)
        VALUES
        ('$nama_kategori')"
    );

    return $query;
}

   public function edit($id_kategori, $nama_kategori)
{
    $query = mysqli_query(
        $this->koneksi,
        "UPDATE kategori SET
            nama_kategori = '$nama_kategori'
         WHERE id_kategori = '$id_kategori'"
    );

    return $query;
}
    public function hapus($id_kategori)
    {
        $query = mysqli_query(
            $this->koneksi,
            "DELETE FROM kategori
             WHERE id_kategori = '$id_kategori'"
        );

        return $query;
    }
}

?>