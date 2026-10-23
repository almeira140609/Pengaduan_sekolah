<?php

session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

require_once "../models/m_siswa.php";

$siswa = new Siswa();

$search = isset($_GET['search']) ? $_GET['search'] : '';

$data_siswa = $siswa->tampil($search);

$nama_admin = $_SESSION['nama_admin'];

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Data Siswa - Pengaduan Sekolah</title>

    <link rel="stylesheet" href="../assets/v_siswa.css">

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


<!-- =========================
     MAIN CONTENT
========================= -->

<div class="main-content">


    <!-- =========================
         TOPBAR
    ========================= -->

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


    <!-- =========================
         CONTENT
    ========================= -->

    <div class="content">


        <!-- PAGE TITLE -->

        <div class="page-title">

            <h1>
                Data Siswa
            </h1>

            <p>
                Kelola data siswa yang terdaftar di sistem pengaduan sekolah.
            </p>

        </div>


        <!-- HEADER -->

        <div class="page-header">

            <div></div>

            <a href="tambah_siswa.php" class="btn-tambah">
                ＋ Tambah Siswa
            </a>

        </div>


        <!-- =========================
             ALERT
        ========================= -->

        <?php if (isset($_GET['pesan'])): ?>


            <?php if ($_GET['pesan'] == 'tambah'): ?>

                <div class="alert success">
                    ✓ Data siswa berhasil ditambahkan.
                </div>


            <?php elseif ($_GET['pesan'] == 'edit'): ?>

                <div class="alert success">
                    ✓ Data siswa berhasil diperbarui.
                </div>


            <?php elseif ($_GET['pesan'] == 'hapus'): ?>

                <div class="alert success">
                    ✓ Data siswa berhasil dihapus.
                </div>


            <?php elseif ($_GET['pesan'] == 'gagal_hapus'): ?>

                <div class="alert gagal">
                    ⚠️ Siswa tidak dapat dihapus karena masih memiliki pengaduan.
                </div>

            <?php endif; ?>

        <?php endif; ?>


        <!-- =========================
             TABLE CARD
        ========================= -->

        <div class="table-card">


            <div class="table-header">

                <div>

                    <h2>
                        Daftar Siswa
                    </h2>

                    <p>
                        Data siswa yang tersimpan dalam sistem.
                    </p>

                </div>


                <!-- SEARCH -->

                <form method="GET" class="search-form">

                    <div class="search-box">

                        <span>🔍</span>

                        <input
                            type="text"
                            name="search"
                            placeholder="Cari siswa..."
                            value="<?= htmlspecialchars($search); ?>"
                        >

                    </div>


                    <button type="submit" class="btn-search">
                        Cari
                    </button>


                    <?php if ($search != ''): ?>

                        <a
                            href="v_siswa.php"
                            class="btn-reset"
                        >
                            Reset
                        </a>

                    <?php endif; ?>

                </form>

            </div>


            <!-- =========================
                 TABLE
            ========================= -->

            <div class="table-wrapper">

                <table>

                    <thead>

                        <tr>

                            <th width="60">
                                No
                            </th>

                            <th>
                                NIS
                            </th>

                            <th>
                                Nama Siswa
                            </th>

                            <th>
                                Kelas
                            </th>

                            <th>
                                Username
                            </th>

                            <th width="120">
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody>


                        <?php if (mysqli_num_rows($data_siswa) > 0): ?>

                            <?php $no = 1; ?>


                            <?php while ($row = mysqli_fetch_assoc($data_siswa)): ?>

                                <tr>

                                    <!-- NO -->

                                    <td>
                                        <?= $no++; ?>
                                    </td>


                                    <!-- NIS -->

                                    <td>

                                        <span class="nis">
                                            <?= htmlspecialchars($row['nis']); ?>
                                        </span>

                                    </td>


                                    <!-- NAMA -->

                                    <td>

                                        <div class="nama">
                                            <?= htmlspecialchars($row['nama_siswa']); ?>
                                        </div>

                                    </td>


                                    <!-- KELAS -->

                                    <td>
                                        <?= htmlspecialchars($row['kelas']); ?>
                                    </td>


                                    <!-- USERNAME -->

                                    <td>
                                        <?= htmlspecialchars($row['username']); ?>
                                    </td>


                                    <!-- AKSI -->

                                    <td>

                                        <div class="aksi">


                                            <!-- EDIT -->

                                            <a
                                                href="edit_siswa.php?id=<?= $row['id_siswa']; ?>"
                                                class="btn-edit"
                                                title="Edit Siswa"
                                            >
                                                ✏️
                                            </a>


                                            <!-- HAPUS -->

                                            <a
                                                href="../controllers/c_siswa.php?hapus=<?= $row['id_siswa']; ?>"
                                                class="btn-hapus"
                                                title="Hapus Siswa"
                                                onclick="return confirm('Yakin ingin menghapus data siswa ini?')"
                                            >
                                                🗑️
                                            </a>


                                        </div>

                                    </td>

                                </tr>


                            <?php endwhile; ?>


                        <?php else: ?>


                            <tr>

                                <td
                                    colspan="6"
                                    class="empty"
                                >

                                    <div class="empty-icon">
                                        📭
                                    </div>

                                    <p>
                                        Data siswa tidak ditemukan.
                                    </p>

                                </td>

                            </tr>


                        <?php endif; ?>


                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>


</body>

</html>