<?php

session_start();

require_once "../models/m_kategori.php";

$kategori = new Kategori();


// ==============================
// TAMBAH KATEGORI
// ==============================

if (isset($_POST['tambah'])) {

    $nama_kategori = $_POST['nama_kategori'];

    $hasil = $kategori->tambah($nama_kategori);

    if ($hasil) {

        header("Location: ../views/v_kategori.php?pesan=tambah");

    } else {

        header("Location: ../views/v_kategori.php?pesan=gagal_tambah");

    }

    exit;
}


// ==============================
// EDIT KATEGORI
// ==============================

if (isset($_POST['edit'])) {

    $id_kategori   = $_POST['id_kategori'];
    $nama_kategori = $_POST['nama_kategori'];

    $hasil = $kategori->edit(
        $id_kategori,
        $nama_kategori
    );

    if ($hasil) {

        header("Location: ../views/v_kategori.php?pesan=edit");

    } else {

        header("Location: ../views/v_kategori.php?pesan=gagal_edit");

    }

    exit;
}


// ==============================
// HAPUS KATEGORI
// ==============================

if (isset($_GET['hapus'])) {

    $id_kategori = $_GET['hapus'];

    $hasil = $kategori->hapus($id_kategori);


    // ==============================
    // BERHASIL DIHAPUS
    // ==============================

    if ($hasil) {

        header(
            "Location: ../views/v_kategori.php?pesan=hapus"
        );

    } else {

        // ==============================
        // GAGAL KARENA MASIH DIGUNAKAN
        // ==============================

        header(
            "Location: ../views/v_kategori.php?pesan=gagal_hapus"
        );

    }

    exit;
}

?>
