<?php

session_start();

require_once "../models/m_aspirasi.php";

$aspirasi = new Aspirasi();


// ================================
// CEK LOGIN SISWA
// ================================

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'siswa') {
    header("Location: ../views/login.php");
    exit;
}


// ================================
// PROSES TAMBAH ASPIRASI
// ================================

if (isset($_POST['tambah'])) {

    // ID SISWA DIAMBIL DARI SESSION
    $id_siswa = $_SESSION['id_siswa'];

    $id_kategori = $_POST['id_kategori'];
    $judul = $_POST['judul'];
    $isi_aspirasi = $_POST['isi_aspirasi'];
    $lokasi = $_POST['lokasi'];


    // ================================
    // PROSES UPLOAD FOTO
    // ================================

    $foto = "";

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {

        $nama_file = $_FILES['foto']['name'];
        $tmp_file = $_FILES['foto']['tmp_name'];
        $ukuran_file = $_FILES['foto']['size'];

        // Ekstensi file
        $ekstensi = strtolower(
            pathinfo($nama_file, PATHINFO_EXTENSION)
        );

        // Ekstensi yang diperbolehkan
        $ekstensi_allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($ekstensi, $ekstensi_allowed)) {
            die("Format foto tidak diperbolehkan.");
        }

        // Maksimal 5 MB
        if ($ukuran_file > 5 * 1024 * 1024) {
            die("Ukuran foto maksimal 5 MB.");
        }

        // Nama file dibuat unik
        $nama_baru = uniqid('aspirasi_') . '.' . $ekstensi;

        // Folder upload
        $folder = "../uploads/";

        // Buat folder jika belum ada
        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        // Pindahkan file
        if (move_uploaded_file($tmp_file, $folder . $nama_baru)) {

            $foto = $nama_baru;

        } else {

            die("Foto gagal diupload.");

        }
    }


    // ================================
    // SIMPAN KE DATABASE
    // ================================

    $simpan = $aspirasi->tambah(
        $id_siswa,
        $id_kategori,
        $judul,
        $isi_aspirasi,
        $lokasi,
        $foto
    );


    // ================================
    // HASIL
    // ================================

    if ($simpan) {

        header("Location: ../views/v_laporan_siswa.php?pesan=tambah");
        exit;

    } else {

        echo "Aspirasi gagal disimpan.";

    }

}

?>
