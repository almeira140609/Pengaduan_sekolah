<?php

session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

require_once "../models/m_kategori.php";

$kategori = new Kategori();

if (!isset($_GET['id'])) {
    header("Location: v_kategori.php");
    exit;
}

$id_kategori = $_GET['id'];

$data = $kategori->ambilById($id_kategori);

if (!$data) {
    header("Location: v_kategori.php");
    exit;
}

$nama_admin = $_SESSION['nama_admin'];

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Kategori - Pengaduan Sekolah</title>

    <link rel="stylesheet" href="../assets/edit_kategori.css">


</head>


<body>


<!-- =========================
     SIDEBAR
========================= -->

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

        </a>


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
     MAIN
========================= -->

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

            <h1>
                Edit Kategori
            </h1>

            <p>
                Perbarui nama kategori yang digunakan untuk mengelompokkan pengaduan siswa.
            </p>

        </div>


        <!-- FORM CARD -->

        <div class="form-card">


            <div class="form-title">

                <h2>
                    Form Edit Kategori
                </h2>

                <p>
                    Silakan ubah nama kategori kemudian simpan perubahan.
                </p>

            </div>


            <form
                action="../controllers/c_kategori.php"
                method="POST"
            >


                <input
                    type="hidden"
                    name="id_kategori"
                    value="<?= htmlspecialchars($data['id_kategori']); ?>"
                >


                <div class="form-group">

                    <label for="nama_kategori">
                        Nama Kategori
                    </label>

                    <input
                        type="text"
                        id="nama_kategori"
                        name="nama_kategori"
                        value="<?= htmlspecialchars($data['nama_kategori']); ?>"
                        placeholder="Masukkan nama kategori"
                        required
                    >

                </div>


                <div class="form-action">


                    <button
                        type="submit"
                        name="edit"
                        class="btn-simpan"
                    >
                        💾 Simpan Perubahan
                    </button>


                    <a
                        href="v_kategori.php"
                        class="btn-kembali"
                    >
                        ← Kembali
                    </a>


                </div>


            </form>

        </div>

    </div>

</div>


</body>

</html>
