
<?php

session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

$nama_admin = $_SESSION['nama_admin'];

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Admin - Pengaduan Sekolah</title>

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
           MAIN CONTENT
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

        .admin-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .admin-avatar {
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

        .admin-name {
            font-size: 13px;
            font-weight: bold;
            color: #263238;
        }

        .admin-role {
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
            background: linear-gradient(135deg, #7567d9, #6254c2);
            border-radius: 14px;
            padding: 28px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            box-shadow: 0 6px 18px rgba(117,103,217,0.20);
        }

        .welcome-text h2 {
            font-size: 22px;
            margin-bottom: 9px;
        }

        .welcome-text p {
            font-size: 13px;
            line-height: 1.6;
            color: #eeeafd;
        }

        .welcome-icon {
            width: 70px;
            height: 70px;
            border-radius: 18px;
            background: rgba(255,255,255,0.16);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 35px;
        }

        /* =========================
           QUICK MENU
        ========================= */

        .section-title {
            margin-top: 30px;
            margin-bottom: 15px;
        }

        .section-title h3 {
            font-size: 17px;
            color: #20283a;
        }

        .section-title p {
            margin-top: 5px;
            font-size: 12px;
            color: #8992a2;
        }

        .quick-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
        }

        .quick-card {
            background: white;
            border-radius: 12px;
            padding: 22px 18px;
            text-decoration: none;
            box-shadow: 0 3px 15px rgba(32,40,58,0.06);
            transition: 0.2s;
        }

        .quick-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 7px 20px rgba(32,40,58,0.10);
        }

        .quick-icon {
            width: 43px;
            height: 43px;
            border-radius: 10px;
            background: #eeeafd;
            color: #6658c9;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 21px;
            margin-bottom: 15px;
        }

        .quick-card h4 {
            font-size: 14px;
            color: #263238;
            margin-bottom: 6px;
        }

        .quick-card p {
            font-size: 11px;
            color: #8992a2;
            line-height: 1.5;
        }

        /* =========================
           INFORMATION CARD
        ========================= */

        .info-card {
            margin-top: 25px;
            background: white;
            border-radius: 12px;
            padding: 22px;
            box-shadow: 0 3px 15px rgba(32,40,58,0.06);
        }

        .info-card h3 {
            font-size: 16px;
            color: #20283a;
            margin-bottom: 18px;
        }

        .info-list {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }

        .info-item {
            padding: 16px;
            border-radius: 10px;
            background: #fafaff;
            border: 1px solid #f0effb;
        }

        .info-item .label {
            font-size: 11px;
            color: #8992a2;
            margin-bottom: 7px;
        }

        .info-item .value {
            font-size: 14px;
            font-weight: bold;
            color: #263238;
        }

        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 1100px) {

            .quick-grid {
                grid-template-columns: repeat(2, 1fr);
            }

        }

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

            .info-list {
                grid-template-columns: 1fr;
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
            .admin-name,
            .admin-role {
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

            .welcome-card {
                padding: 22px;
            }

            .welcome-text h2 {
                font-size: 18px;
            }

            .welcome-icon {
                width: 50px;
                height: 50px;
                font-size: 25px;
            }

            .quick-grid {
                grid-template-columns: 1fr;
            }

        }

    </style>

</head>

<body>

    <!-- SIDEBAR -->

    <div class="sidebar">

        <div class="logo">

            <h2>
                Pengaduan<br>Sekolah
            </h2>

            <p>
                Sistem Sarana & Prasarana
            </p>

        </div>


        <div class="menu">

            <div class="menu-title">
                Menu Admin
            </div>

            <a href="dashboard_admin.php" class="active">
                <span class="icon">⌂</span>
                <span>Dashboard</span>
            </a>

            <a href="v_siswa.php">
                <span class="icon">♙</span>
                <span>Data Siswa</span>
            </a>

            <a href="v_kategori.php">
                <span class="icon">▦</span>
                <span>Kategori</span>
            </a>

            <a href="v_aspirasi.php">
                <span class="icon">▤</span>
                <span>Aspirasi</span>
            </a>

            <a href="v_histori.php">
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


    <!-- MAIN CONTENT -->

    <div class="main-content">


        <!-- TOPBAR -->

        <div class="topbar">

            <div class="topbar-text">
                Sistem Pengaduan Sarana & Prasarana Sekolah
            </div>


            <div class="admin-info">

                <div class="admin-avatar">
                    <?= strtoupper(substr($nama_admin, 0, 1)); ?>
                </div>

                <div>

                    <div class="admin-name">
                        <?= htmlspecialchars($nama_admin); ?>
                    </div>

                    <div class="admin-role">
                        Admin
                    </div>

                </div>

            </div>

        </div>


        <!-- CONTENT -->

        <div class="content">


            <div class="page-title">

                <h1>Dashboard Admin</h1>

                <p>
                    Kelola data siswa dan pengaduan sarana prasarana sekolah.
                </p>

            </div>


            <!-- WELCOME CARD -->

            <div class="welcome-card">

                <div class="welcome-text">

                    <h2>
                        Selamat Datang, <?= htmlspecialchars($nama_admin); ?>! 👋
                    </h2>

                    <p>
                        Kelola laporan pengaduan siswa dengan mudah
                        melalui sistem pengaduan sekolah.
                    </p>

                </div>


                <div class="welcome-icon">
                    🏫
                </div>

            </div>


            <!-- QUICK MENU -->

            <div class="section-title">

                <h3>
                    Menu Utama
                </h3>

                <p>
                    Pilih menu untuk mengelola sistem.
                </p>

            </div>


            <div class="quick-grid">


                <a href="v_siswa.php" class="quick-card">

                    <div class="quick-icon">
                        ♙
                    </div>

                    <h4>
                        Data Siswa
                    </h4>

                    <p>
                        Kelola data siswa yang terdaftar.
                    </p>

                </a>


                <a href="v_kategori.php" class="quick-card">

                    <div class="quick-icon">
                        ▦
                    </div>

                    <h4>
                        Kategori
                    </h4>

                    <p>
                        Kelola kategori pengaduan sekolah.
                    </p>

                </a>


                <a href="v_aspirasi.php" class="quick-card">

                    <div class="quick-icon">
                        ▤
                    </div>

                    <h4>
                        Aspirasi
                    </h4>

                    <p>
                        Lihat dan kelola laporan dari siswa.
                    </p>

                </a>


                <a href="v_histori.php" class="quick-card">

                    <div class="quick-icon">
                        ◷
                    </div>

                    <h4>
                        Histori
                    </h4>

                    <p>
                        Lihat riwayat proses pengaduan.
                    </p>

                </a>


            </div>


            <!-- INFORMATION -->

            <div class="section-title">

                <h3>
                    Informasi Admin
                </h3>

                <p>
                    Informasi akun yang sedang digunakan.
                </p>

            </div>


            <div class="info-card">

                <h3>
                    Data Akun
                </h3>


                <div class="info-list">


                    <div class="info-item">

                        <div class="label">
                            Nama Admin
                        </div>

                        <div class="value">
                            <?= htmlspecialchars($nama_admin); ?>
                        </div>

                    </div>


                    <div class="info-item">

                        <div class="label">
                            Username
                        </div>

                        <div class="value">
                            <?= htmlspecialchars($_SESSION['username'] ?? '-'); ?>
                        </div>

                    </div>


                    <div class="info-item">

                        <div class="label">
                            Hak Akses
                        </div>

                        <div class="value">
                            Administrator
                        </div>

                    </div>


                </div>

            </div>


        </div>

    </div>

</body>

</html>
