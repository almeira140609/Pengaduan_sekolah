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

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f5f3ff;
            color: #263238;
        }


        /* =========================
           SIDEBAR
        ========================= */

        .sidebar {
            position: fixed;
            left: 0;
            top: 0;

            width: 240px;
            height: 100vh;

            background: #20283a;
            color: white;

            display: flex;
            flex-direction: column;

            box-shadow: 3px 0 15px rgba(0,0,0,0.08);
        }


        .logo {
            padding: 27px 24px;

            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .logo h2 {
            font-size: 21px;
            line-height: 1.2;
        }

        .logo p {
            margin-top: 6px;

            font-size: 12px;

            color: #aeb8ca;
        }


        /* =========================
           MENU
        ========================= */

        .menu {
            padding: 22px 12px;
        }

        .menu-title {
            padding: 0 13px;

            margin-bottom: 10px;

            font-size: 11px;

            color: #8994a8;

            text-transform: uppercase;

            letter-spacing: 1px;
        }

        .menu a {
            display: flex;

            align-items: center;

            gap: 12px;

            padding: 12px 14px;

            margin-bottom: 5px;

            color: #dbe1eb;

            text-decoration: none;

            border-radius: 8px;

            font-size: 14px;

            transition: 0.2s;
        }

        .menu a:hover {
            background: #2b354b;
            color: white;
        }

        .menu a.active {
            background: #7567d9;
            color: white;

            box-shadow: 0 5px 12px rgba(117,103,217,0.25);
        }

        .icon {
            width: 22px;

            text-align: center;

            font-size: 16px;
        }


        /* =========================
           LOGOUT
        ========================= */

        .logout {
            margin-top: auto;

            padding: 15px 12px;

            border-top: 1px solid rgba(255,255,255,0.08);
        }

        .logout a {
            display: flex;

            align-items: center;

            gap: 12px;

            padding: 12px 14px;

            color: #dbe1eb;

            text-decoration: none;

            border-radius: 8px;

            font-size: 14px;
        }

        .logout a:hover {
            background: #2b354b;
        }


        /* =========================
           MAIN
        ========================= */

        .main-content {
            margin-left: 240px;

            min-height: 100vh;
        }


        /* =========================
           TOPBAR
        ========================= */

        .topbar {
            height: 72px;

            background: white;

            border-bottom: 1px solid #e5e8ee;

            display: flex;

            align-items: center;

            justify-content: space-between;

            padding: 0 32px;
        }

        .topbar-text {
            color: #7a8494;

            font-size: 13px;
        }

        .siswa-info {
            display: flex;

            align-items: center;

            gap: 10px;
        }

        .siswa-avatar {
            width: 38px;
            height: 38px;

            border-radius: 50%;

            background: #eeeafd;

            color: #6658c9;

            display: flex;

            align-items: center;

            justify-content: center;

            font-weight: bold;
        }

        .siswa-name {
            font-size: 13px;

            font-weight: bold;

            color: #263238;
        }

        .siswa-role {
            margin-top: 3px;

            font-size: 11px;

            color: #9aa3b2;
        }


        /* =========================
           CONTENT
        ========================= */

        .content {
            padding: 30px 32px;
        }

        .page-title {
            margin-bottom: 25px;
        }

        .page-title h1 {
            font-size: 25px;

            color: #20283a;
        }

        .page-title p {
            margin-top: 7px;

            font-size: 13px;

            color: #8992a2;
        }


        /* =========================
           WELCOME CARD
        ========================= */

        .welcome-card {
            background: white;

            border-radius: 12px;

            border: 1px solid #e7eaf0;

            box-shadow: 0 5px 20px rgba(31,41,55,0.05);

            padding: 28px;

            margin-bottom: 22px;

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 20px;
        }

        .welcome-text h2 {
            color: #20283a;

            font-size: 21px;

            margin-bottom: 8px;
        }

        .welcome-text p {
            color: #8992a2;

            font-size: 13px;

            line-height: 1.6;
        }


        /* =========================
           BUTTON TAMBAH LAPORAN
        ========================= */

        .btn-tambah {
            display: inline-flex;

            align-items: center;

            gap: 8px;

            background: #7567d9;

            color: white;

            text-decoration: none;

            padding: 12px 18px;

            border-radius: 8px;

            font-size: 13px;

            font-weight: bold;

            white-space: nowrap;

            box-shadow: 0 5px 12px rgba(117,103,217,0.20);

            transition: 0.2s;
        }

        .btn-tambah:hover {
            background: #6658c9;
        }


        /* =========================
           INFO SISWA
        ========================= */

        .info-grid {
            display: grid;

            grid-template-columns: repeat(3, 1fr);

            gap: 18px;

            margin-bottom: 22px;
        }

        .info-card {
            background: white;

            border-radius: 12px;

            border: 1px solid #e7eaf0;

            box-shadow: 0 5px 20px rgba(31,41,55,0.05);

            padding: 22px;
        }

        .info-icon {
            width: 40px;
            height: 40px;

            display: flex;

            align-items: center;

            justify-content: center;

            background: #eeeafd;

            color: #6658c9;

            border-radius: 8px;

            font-size: 18px;

            margin-bottom: 14px;
        }

        .info-label {
            font-size: 11px;

            color: #8992a2;

            text-transform: uppercase;

            letter-spacing: 0.5px;

            margin-bottom: 7px;
        }

        .info-value {
            font-size: 16px;

            font-weight: bold;

            color: #303b4e;
        }


        /* =========================
           MENU CEPAT
        ========================= */

        .section-title {
            margin-bottom: 14px;
        }

        .section-title h2 {
            font-size: 17px;

            color: #20283a;
        }

        .quick-grid {
            display: grid;

            grid-template-columns: repeat(2, 1fr);

            gap: 18px;
        }

        .quick-card {
            display: block;

            background: white;

            border-radius: 12px;

            border: 1px solid #e7eaf0;

            box-shadow: 0 5px 20px rgba(31,41,55,0.05);

            padding: 22px;

            text-decoration: none;

            transition: 0.2s;
        }

        .quick-card:hover {
            border-color: #d8d2ff;

            transform: translateY(-2px);
        }

        .quick-icon {
            font-size: 22px;

            margin-bottom: 12px;
        }

        .quick-card h3 {
            font-size: 15px;

            color: #303b4e;

            margin-bottom: 7px;
        }

        .quick-card p {
            font-size: 12px;

            color: #8992a2;

            line-height: 1.5;
        }


        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 900px) {

            .sidebar {
                width: 200px;
            }

            .main-content {
                margin-left: 200px;
            }

            .content {
                padding: 25px 20px;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .welcome-card {
                align-items: flex-start;

                flex-direction: column;
            }

        }


        @media (max-width: 600px) {

            .sidebar {
                width: 65px;
            }

            .logo h2,
            .logo p,
            .menu-title,
            .menu span:not(.icon),
            .logout span:not(.icon),
            .topbar-text,
            .siswa-name,
            .siswa-role {
                display: none;
            }

            .logo {
                text-align: center;

                padding: 22px 5px;
            }

            .menu a,
            .logout a {
                justify-content: center;
            }

            .main-content {
                margin-left: 65px;
            }

            .content {
                padding: 20px 12px;
            }

            .quick-grid {
                grid-template-columns: 1fr;
            }

        }

    </style>

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

        <a href="logout.php">

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
