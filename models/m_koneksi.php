<?php

class Koneksi
{
    public $koneksi;

    public function __construct()
    {
        $host = "localhost";
        $username = "root";
        $password = "";
        $database = "pengaduan_sekolah";

        $this->koneksi = mysqli_connect(
            $host,
            $username,
            $password,
            $database
        );

        if (!$this->koneksi) {
            die("Koneksi database gagal: " . mysqli_connect_error());
        }
    }
}

?>
