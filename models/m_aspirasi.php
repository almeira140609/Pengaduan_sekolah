<?php

require_once "m_koneksi.php";

class Aspirasi extends Koneksi
{
    // Menampilkan semua aspirasi
    public function tampil()
    {
        $query = mysqli_query(
            $this->koneksi,
            "SELECT * FROM aspirasi
             ORDER BY id_aspirasi ASC"
        );

        return $query;
    }

    // Mengambil data kategori
    public function kategori()
    {
        $query = mysqli_query(
            $this->koneksi,
            "SELECT * FROM kategori
             ORDER BY nama_kategori ASC"
        );

        return $query;
    }

    // Menambahkan aspirasi
    public function tambah(
        $id_siswa,
        $id_kategori,
        $judul,
        $isi_aspirasi,
        $lokasi,
        $foto
    ) {

        // Mengamankan data dari karakter khusus
        $id_siswa = mysqli_real_escape_string(
            $this->koneksi,
            $id_siswa
        );

        $id_kategori = mysqli_real_escape_string(
            $this->koneksi,
            $id_kategori
        );

        $judul = mysqli_real_escape_string(
            $this->koneksi,
            $judul
        );

        $isi_aspirasi = mysqli_real_escape_string(
            $this->koneksi,
            $isi_aspirasi
        );

        $lokasi = mysqli_real_escape_string(
            $this->koneksi,
            $lokasi
        );

        $foto = mysqli_real_escape_string(
            $this->koneksi,
            $foto
        );

        $query = mysqli_query(
            $this->koneksi,
            "INSERT INTO aspirasi
            (
                id_siswa,
                id_kategori,
                judul,
                isi_aspirasi,
                lokasi,
                foto,
                status,
                tanggal_aspirasi
            )
            VALUES
            (
                '$id_siswa',
                '$id_kategori',
                '$judul',
                '$isi_aspirasi',
                '$lokasi',
                '$foto',
                'Menunggu',
                NOW()
            )"
        );

        return $query;
    }
}

?>