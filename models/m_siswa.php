<?php

require_once "m_koneksi.php";

class Siswa extends Koneksi
{

    // Menampilkan semua data siswa / mencari siswa
    public function tampil($search = "")
    {

        if ($search != "") {

            $search = mysqli_real_escape_string(
                $this->koneksi,
                $search
            );

            $query = mysqli_query(
                $this->koneksi,
                "SELECT * FROM siswa
                 WHERE nis LIKE '%$search%'
                 OR nama_siswa LIKE '%$search%'
                 OR kelas LIKE '%$search%'
                 OR username LIKE '%$search%'
                 ORDER BY id_siswa ASC"
            );

        } else {

            $query = mysqli_query(
                $this->koneksi,
                "SELECT * FROM siswa
                 ORDER BY id_siswa ASC"
            );

        }

        return $query;
    }


    // Mengambil satu data siswa berdasarkan ID
    public function ambilById($id_siswa)
    {
        $query = mysqli_query(
            $this->koneksi,
            "SELECT * FROM siswa
             WHERE id_siswa = '$id_siswa'"
        );

        return mysqli_fetch_assoc($query);
    }


    // Menambahkan data siswa
    public function tambah(
        $nis,
        $nama_siswa,
        $kelas,
        $username,
        $password
    ) {
        $query = mysqli_query(
            $this->koneksi,
            "INSERT INTO siswa
            (nis, nama_siswa, kelas, username, password)
            VALUES
            ('$nis', '$nama_siswa', '$kelas', '$username', '$password')"
        );

        return $query;
    }


    // Mengubah data siswa
    public function edit(
        $id_siswa,
        $nis,
        $nama_siswa,
        $kelas,
        $username,
        $password
    ) {
        if ($password != "") {

            $query = mysqli_query(
                $this->koneksi,
                "UPDATE siswa SET
                    nis = '$nis',
                    nama_siswa = '$nama_siswa',
                    kelas = '$kelas',
                    username = '$username',
                    password = '$password'
                 WHERE id_siswa = '$id_siswa'"
            );

        } else {

            $query = mysqli_query(
                $this->koneksi,
                "UPDATE siswa SET
                    nis = '$nis',
                    nama_siswa = '$nama_siswa',
                    kelas = '$kelas',
                    username = '$username'
                 WHERE id_siswa = '$id_siswa'"
            );
        }

        return $query;
    }


    // Menghapus data siswa
    public function hapus($id_siswa)
    {
        $query = mysqli_query(
            $this->koneksi,
            "DELETE FROM siswa
             WHERE id_siswa = '$id_siswa'"
        );

        return $query;
    }

}

?>