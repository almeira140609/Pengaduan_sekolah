
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

   <link rel="stylesheet" href="../assets/dashboard_admin.css">

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
