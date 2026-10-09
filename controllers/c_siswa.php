<?php

require_once "../models/m_siswa.php";

$siswa = new Siswa();


// ==============================
// TAMBAH SISWA
// ==============================

if (isset($_POST['tambah'])) {

    $nis        = $_POST['nis'];
    $nama_siswa = $_POST['nama_siswa'];
    $kelas      = $_POST['kelas'];
    $username   = $_POST['username'];
    $password   = $_POST['password'];

    $siswa->tambah(
        $nis,
        $nama_siswa,
        $kelas,
        $username,
        $password
    );

    header("Location: ../views/v_siswa.php?pesan=tambah");
    exit;
}


// ==============================
// EDIT SISWA
// ==============================

if (isset($_POST['edit'])) {

    $id_siswa   = $_POST['id_siswa'];
    $nis        = $_POST['nis'];
    $nama_siswa = $_POST['nama_siswa'];
    $kelas      = $_POST['kelas'];
    $username   = $_POST['username'];
    $password   = $_POST['password'];

    $siswa->edit(
        $id_siswa,
        $nis,
        $nama_siswa,
        $kelas,
        $username,
        $password
    );

    header("Location: ../views/v_siswa.php?pesan=edit");
    exit;
}


// ==============================
// HAPUS SISWA
// ==============================

if (isset($_GET['hapus'])) {

    $id_siswa = $_GET['hapus'];

    $hasil = $siswa->hapus($id_siswa);

    if ($hasil) {
        header("Location: ../views/v_siswa.php?pesan=hapus");
    } else {
        header("Location: ../views/v_siswa.php?pesan=gagal_hapus");
    }

    exit;
}
?>