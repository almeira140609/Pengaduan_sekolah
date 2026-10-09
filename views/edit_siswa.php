<?php

require_once "../models/m_siswa.php";

$siswa = new Siswa();

$id_siswa = $_GET['id'];

$data = $siswa->ambilById($id_siswa);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Siswa - Pengaduan Sekolah</title>
    <link rel="stylesheet" href="../assets/edit_siswa.css">
</head>


<body>


<!-- SIDEBAR -->

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


        <a href="v_siswa.php" class="active">

            <span class="icon">♙</span>

            <span>
                Data Siswa
            </span>

        </a>


        <a href="v_kategori.php">

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


<!-- MAIN -->

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
                    Edit Data Siswa
                </h1>

                <p>
                    Perbarui informasi data siswa yang tersimpan di sistem.
                </p>

            </div>

        </div>


        <!-- FORM CARD -->

        <div class="form-card">


            <div class="form-header">

                <h3>
                    Form Edit Siswa
                </h3>

                <p>
                    Ubah informasi siswa sesuai data terbaru.
                </p>

            </div>


            <form action="../controllers/c_siswa.php" method="POST">


                <input
                    type="hidden"
                    name="id_siswa"
                    value="<?= htmlspecialchars($data['id_siswa']); ?>"
                >


                <div class="form-group">

                    <label for="nis">
                        NIS
                    </label>

                    <input
                        type="text"
                        id="nis"
                        name="nis"
                        value="<?= htmlspecialchars($data['nis']); ?>"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="nama_siswa">
                        Nama Siswa
                    </label>

                    <input
                        type="text"
                        id="nama_siswa"
                        name="nama_siswa"
                        value="<?= htmlspecialchars($data['nama_siswa']); ?>"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="kelas">
                        Kelas
                    </label>

                    <input
                        type="text"
                        id="kelas"
                        name="kelas"
                        value="<?= htmlspecialchars($data['kelas']); ?>"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="username">
                        Username
                    </label>

                    <input
                        type="text"
                        id="username"
                        name="username"
                        value="<?= htmlspecialchars($data['username']); ?>"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="password">
                        Password
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Kosongkan jika tidak ingin mengganti password"
                    >

                    <div class="form-help">
                        Biarkan kosong jika password tidak ingin diubah.
                    </div>

                </div>


                <div class="form-action">

                    <a href="v_siswa.php" class="btn btn-kembali">
                        Kembali
                    </a>

                    <button
                        type="submit"
                        name="edit"
                        class="btn btn-simpan"
                    >
                        Simpan Perubahan
                    </button>

                </div>


            </form>


        </div>


    </div>


</div>


</body>

</html>
