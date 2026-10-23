<?php

session_start();

// ==========================================
// CEK STATUS LOGIN
// ==========================================

if (isset($_SESSION['role'])) {

    // Jika login sebagai admin
    if ($_SESSION['role'] == 'admin') {
        header("Location: views/dashboard_admin.php");
        exit;
    }

    // Jika login sebagai siswa
    if ($_SESSION['role'] == 'siswa') {
        header("Location: views/dashboard_siswa.php");
        exit;
    }
}

// ==========================================
// BELUM LOGIN
// ==========================================

header("Location: views/login.php");
exit;

?>