<?php
require_once "../models/m_kategori.php";
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Kategori - Pengaduan Sekolah</title>

    <link rel="stylesheet" href="../assets/tambah_kategori.css">


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
            Menu Utama
        </div>


        <a href="dashboard_admin.php">

            <span class="icon">⌂</span>

            <span>
                Dashboard
            </span>

        </a>


        <a href="v_siswa.php">

            <span class="icon">♙</span>

            <span>
                Data Siswa
            </span>

        </a>


        <a href="v_kategori.php" class="active">

            <span class="icon">▦</span>

            <span>
                Kategori
            </span>

        </a>


        <a href="v_aspirasi.php">

            <span class="icon">▤</span>

            <span>
                Aspirasi
            </span>


         <a href="v_histori.php">

            <span class="icon">◷</span>

            <span>
                Histori
            </span>

        </a>

    </div>


    <div class="logout">

        <a href="logout.php">

            <span class="icon">←</span>

            <span>
                Logout
            </span>

        </a>

    </div>


</div>


<!-- =========================
     MAIN CONTENT
========================= -->

<div class="main-content">


    <!-- TOPBAR -->

    <div class="topbar">

        <div class="topbar-text">
            Sistem Pengaduan Sarana & Prasarana Sekolah
        </div>


        <div class="admin">

            <div class="admin-avatar">
                A
            </div>


            <div>

                <div class="admin-name">
                    Administrator
                </div>

                <div class="admin-role">
                    Admin Sekolah
                </div>

            </div>

        </div>

    </div>


    <!-- CONTENT -->

    <div class="content">


        <!-- HEADER -->

        <div class="page-header">

            <div class="page-title">

                <h1>
                    Tambah Kategori
                </h1>

                <p>
                    Tambahkan kategori baru untuk pengaduan sekolah.
                </p>

            </div>

        </div>


        <!-- FORM CARD -->

        <div class="form-card">


            <div class="form-header">

                <h3>
                    Form Kategori
                </h3>

                <p>
                    Masukkan informasi kategori yang ingin ditambahkan.
                </p>

            </div>


            <form
                method="POST"
                action="../controllers/c_kategori.php"
            >


                <div class="form-group">

                    <label for="nama_kategori">
                        Nama Kategori
                    </label>

                    <input
                        type="text"
                        id="nama_kategori"
                        name="nama_kategori"
                        placeholder="Contoh: Kerusakan Fasilitas"
                        required
                    >

                </div>

                <div class="form-action">

                    <a
                        href="v_kategori.php"
                        class="btn btn-kembali"
                    >
                        Kembali
                    </a>


                    <button
                        type="submit"
                        name="tambah"
                        class="btn btn-simpan"
                    >
                        + Simpan Kategori
                    </button>

                </div>


            </form>


        </div>


    </div>


</div>


</body>

</html>
