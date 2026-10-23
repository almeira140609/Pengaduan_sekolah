<?php

session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'siswa') {
    header("Location: login.php");
    exit;
}

$nama_siswa = $_SESSION['nama_siswa'] ?? 'Siswa';
$nis = $_SESSION['nis'] ?? '-';
$kelas = $_SESSION['kelas'] ?? '-';

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Siswa - Pengaduan Sekolah</title>

    <link rel="stylesheet" href="../assets/dashboard_siswa.css">


</head>


<body>


<!-- =========================
     SIDEBAR
========================= -->

<div class="sidebar">


    <div class="logo">

        <h2>
            Pengaduan<br>
            Sekolah
        </h2>

        <p>
            Sistem Sarana & Prasarana
        </p>

    </div>


    <div class="menu">

        <div class="menu-title">
            Menu Siswa
        </div>


        <a href="dashboard_siswa.php" class="active">

            <span class="icon">⌂</span>

            <span>Dashboard</span>

        </a>


        <a href="v_laporan_siswa.php">

            <span class="icon">▤</span>

            <span>Laporan Saya</span>

        </a>


        <a href="v_histori_siswa.php">

            <span class="icon">◷</span>

            <span>Histori</span>

        </a>

    </div>


    <div class="logout">

       <a  href="logout.php"
            onclick="return confirm('Yakin ingin keluar?')"
>
    <span class="icon">←</span>

    <span>Logout</span>
</a>

    </div>

</div>


<!-- =========================
     MAIN
========================= -->

<div class="main-content">


    <!-- TOPBAR -->

    <div class="topbar">


        <div class="topbar-text">

            Sistem Pengaduan Sarana & Prasarana Sekolah

        </div>


        <div class="siswa-info">


            <div class="siswa-avatar">

                <?= strtoupper(substr($nama_siswa, 0, 1)); ?>

            </div>


            <div>

                <div class="siswa-name">

                    <?= htmlspecialchars($nama_siswa); ?>

                </div>

                <div class="siswa-role">

                    Siswa

                </div>

            </div>


        </div>

    </div>


    <!-- CONTENT -->

    <div class="content">


        <!-- TITLE -->

        <div class="page-title">

            <h1>
                Dashboard
            </h1>

            <p>
                Selamat datang di Sistem Pengaduan Sarana & Prasarana Sekolah.
            </p>

        </div>


        <!-- WELCOME -->

        <div class="welcome-card">


            <div class="welcome-text">

                <h2>
                    Halo, <?= htmlspecialchars($nama_siswa); ?> 👋
                </h2>

                <p>
                    Ada masalah atau fasilitas sekolah yang perlu diperbaiki?
                    Kamu bisa mengirimkan laporan kepada pihak sekolah.
                </p>

            </div>


            <a
                href="tambah_aspirasi_siswa.php"
                class="btn-tambah"
            >

                <span>＋</span>

                Tambah Laporan

            </a>


        </div>


        <!-- INFO SISWA -->

        <div class="info-grid">


            <div class="info-card">

                <div class="info-icon">
                    👤
                </div>

                <div class="info-label">
                    Nama Siswa
                </div>

                <div class="info-value">

                    <?= htmlspecialchars($nama_siswa); ?>

                </div>

            </div>


            <div class="info-card">

                <div class="info-icon">
                    ▣
                </div>

                <div class="info-label">
                    NIS
                </div>

                <div class="info-value">

                    <?= htmlspecialchars($nis); ?>

                </div>

            </div>


            <div class="info-card">

                <div class="info-icon">
                    🏫
                </div>

                <div class="info-label">
                    Kelas
                </div>

                <div class="info-value">

                    <?= htmlspecialchars($kelas); ?>

                </div>

            </div>


        </div>


        <!-- MENU CEPAT -->

        <div class="section-title">

            <h2>
                Menu Cepat
            </h2>

        </div>


        <div class="quick-grid">


            <a
                href="tambah_aspirasi_siswa.php"
                class="quick-card"
            >

                <div class="quick-icon">
                    📝
                </div>

                <h3>
                    Tambah Laporan
                </h3>

                <p>
                    Kirim aspirasi atau keluhan baru mengenai sarana
                    dan prasarana sekolah.
                </p>

            </a>


            <a
                href="v_laporan_siswa.php"
                class="quick-card"
            >

                <div class="quick-icon">
                    ▤
                </div>

                <h3>
                    Laporan Saya
                </h3>

                <p>
                    Lihat semua laporan yang sudah kamu kirimkan
                    beserta status penanganannya.
                </p>

            </a>


            <a
                href="v_histori_siswa.php"
                class="quick-card"
            >

                <div class="quick-icon">
                    ◷
                </div>

                <h3>
                    Histori
                </h3>

                <p>
                    Lihat riwayat perkembangan laporan yang pernah
                    kamu kirimkan.
                </p>

            </a>


        </div>


    </div>

</div>


</body>

</html>
