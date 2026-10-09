<?php

session_start();

require_once "../models/m_koneksi.php";

$koneksi = new Koneksi();
$db = $koneksi->koneksi;


// =========================
// CEK METHOD
// =========================

if ($_SERVER['REQUEST_METHOD'] != 'POST') {

    header("Location: ../views/login.php");

    exit;
}


// =========================
// AMBIL INPUT
// =========================

$username = isset($_POST['username'])
    ? trim($_POST['username'])
    : '';

$password = isset($_POST['password'])
    ? trim($_POST['password'])
    : '';


// =========================
// CEK KOSONG
// =========================

if ($username == '' || $password == '') {

    header("Location: ../views/login.php?error=1");

    exit;
}


$username = mysqli_real_escape_string(
    $db,
    $username
);


// =========================
// CEK ADMIN
// =========================

$query_admin = mysqli_query(
    $db,
    "SELECT *
     FROM admin
     WHERE username = '$username'
     LIMIT 1"
);


$admin = mysqli_fetch_assoc($query_admin);


// Jika username ditemukan di admin
if ($admin) {

    $password_benar = false;


    // Untuk password yang sudah di-hash
    if (password_verify($password, $admin['password'])) {

        $password_benar = true;

    }


    // Untuk password biasa/plain text
    elseif ($password === $admin['password']) {

        $password_benar = true;

    }


    if ($password_benar) {

        $_SESSION['role'] = 'admin';

        $_SESSION['id_admin'] = $admin['id_admin'];

        $_SESSION['nama_admin'] = $admin['nama_admin'];

        $_SESSION['username'] = $admin['username'];


        header("Location: ../views/dashboard_admin.php");

        exit;
    }

}


// =========================
// CEK SISWA
// =========================

$query_siswa = mysqli_query(
    $db,
    "SELECT *
     FROM siswa
     WHERE username = '$username'
     LIMIT 1"
);


$siswa = mysqli_fetch_assoc($query_siswa);


// Jika username ditemukan di siswa
if ($siswa) {

    $password_benar = false;


    // Untuk password yang sudah di-hash
    if (password_verify($password, $siswa['password'])) {

        $password_benar = true;

    }


    // Untuk password biasa/plain text
    elseif ($password === $siswa['password']) {

        $password_benar = true;

    }


    if ($password_benar) {

        $_SESSION['role'] = 'siswa';

        $_SESSION['id_siswa'] = $siswa['id_siswa'];

        $_SESSION['nama_siswa'] = $siswa['nama_siswa'];

        $_SESSION['nis'] = $siswa['nis'];

        $_SESSION['kelas'] = $siswa['kelas'];

        $_SESSION['username'] = $siswa['username'];


        header("Location: ../views/dashboard_siswa.php");

        exit;
    }

}


// =========================
// LOGIN GAGAL
// =========================

header("Location: ../views/login.php?error=1");

exit;

?>