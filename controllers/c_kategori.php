<?php

session_start();

require_once "../models/m_kategori.php";

$kategori = new Kategori();

if (isset($_POST['tambah'])) {

    $nama_kategori = $_POST['nama_kategori'];


    $kategori->tambah($nama_kategori);

    header("Location: ../views/v_kategori.php");
    exit;
}


if (isset($_POST['edit'])) {

    $id_kategori = $_POST['id_kategori'];
    $nama_kategori = $_POST['nama_kategori'];


    $kategori->edit(
        $id_kategori,
        $nama_kategori,

    );

    header("Location: ../views/v_kategori.php");
    exit;
}

if (isset($_GET['hapus'])) {

    $id_kategori = $_GET['hapus'];

    $kategori->hapus($id_kategori);

    $_SESSION['pesan'] = "Kategori berhasil dihapus!";

    header("Location: ../views/v_kategori.php");
    exit;
}
?>