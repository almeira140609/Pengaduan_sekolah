<?php

require_once "m_koneksi.php";

class Siswa extends Koneksi
{

    // ==============================
    // MENAMPILKAN DATA SISWA
    // ==============================

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


    // ==============================
    // MENGAMBIL DATA SISWA BERDASARKAN ID
    // ==============================

    public function ambilById($id_siswa)
    {

        $id_siswa = mysqli_real_escape_string(
            $this->koneksi,
            $id_siswa
        );

        $query = mysqli_query(
            $this->koneksi,
            "SELECT * FROM siswa
             WHERE id_siswa = '$id_siswa'"
        );

        return mysqli_fetch_assoc($query);
    }


    // ==============================
    // TAMBAH SISWA
    // ==============================

    public function tambah(
        $nis,
        $nama_siswa,
        $kelas,
        $username,
        $password
    ) {

        $nis = mysqli_real_escape_string(
            $this->koneksi,
            $nis
        );

        $nama_siswa = mysqli_real_escape_string(
            $this->koneksi,
            $nama_siswa
        );

        $kelas = mysqli_real_escape_string(
            $this->koneksi,
            $kelas
        );

        $username = mysqli_real_escape_string(
            $this->koneksi,
            $username
        );

        $password = mysqli_real_escape_string(
            $this->koneksi,
            $password
        );


        $query = mysqli_query(
            $this->koneksi,
            "INSERT INTO siswa
            (nis, nama_siswa, kelas, username, password)
            VALUES
            ('$nis', '$nama_siswa', '$kelas', '$username', '$password')"
        );

        return $query;
    }


    // ==============================
    // EDIT SISWA
    // ==============================

    public function edit(
        $id_siswa,
        $nis,
        $nama_siswa,
        $kelas,
        $username,
        $password
    ) {

        $id_siswa = mysqli_real_escape_string(
            $this->koneksi,
            $id_siswa
        );

        $nis = mysqli_real_escape_string(
            $this->koneksi,
            $nis
        );

        $nama_siswa = mysqli_real_escape_string(
            $this->koneksi,
            $nama_siswa
        );

        $kelas = mysqli_real_escape_string(
            $this->koneksi,
            $kelas
        );

        $username = mysqli_real_escape_string(
            $this->koneksi,
            $username
        );

        $password = mysqli_real_escape_string(
            $this->koneksi,
            $password
        );


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


    // ==============================
    // CEK APAKAH SISWA PUNYA ASPIRASI
    // ==============================

    public function punyaAspirasi($id_siswa)
    {

        $id_siswa = mysqli_real_escape_string(
            $this->koneksi,
            $id_siswa
        );

        $query = mysqli_query(
            $this->koneksi,
            "SELECT COUNT(*) AS jumlah
             FROM aspirasi
             WHERE id_siswa = '$id_siswa'"
        );

        $data = mysqli_fetch_assoc($query);

        return $data['jumlah'] > 0;
    }


    // ==============================
    // MENGHAPUS DATA SISWA
    // ==============================

    public function hapus($id_siswa)
    {

        $id_siswa = mysqli_real_escape_string(
            $this->koneksi,
            $id_siswa
        );


        // Cek terlebih dahulu apakah siswa masih memiliki pengaduan

        if ($this->punyaAspirasi($id_siswa)) {

            return false;
        }


        // Kalau tidak punya pengaduan, siswa boleh dihapus

        $query = mysqli_query(
            $this->koneksi,
            "DELETE FROM siswa
             WHERE id_siswa = '$id_siswa'"
        );

        return $query;
    }

}

?>