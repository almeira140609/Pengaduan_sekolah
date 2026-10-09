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
           HEADER SISWA
        ========================= */

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .btn-tambah {
            display: inline-flex;
            align-items: center;
            gap: 6px;

            padding: 11px 16px;

            background: #7567d9;
            color: white;

            text-decoration: none;

            border-radius: 8px;

            font-size: 13px;
            font-weight: bold;

            box-shadow: 0 5px 12px rgba(117,103,217,0.20);

            transition: 0.2s;
        }

        .btn-tambah:hover {
            background: #6658c9;
            transform: translateY(-1px);
        }


        /* =========================
           ALERT
        ========================= */

        .alert {
            padding: 13px 16px;
            margin-bottom: 20px;

            border-radius: 8px;

            font-size: 13px;
        }

        .alert.success {
            background: #e9f8ef;
            color: #27814d;
            border: 1px solid #ccebd8;
        }


        /* =========================
           TABLE CARD
        ========================= */

        .table-card {
            background: white;
            border-radius: 12px;

            box-shadow: 0 3px 15px rgba(32,40,58,0.06);

            overflow: hidden;
        }

        .table-header {
            padding: 20px 22px;

            display: flex;
            justify-content: space-between;
            align-items: center;

            border-bottom: 1px solid #eeeef4;
        }

        .table-header h2 {
            font-size: 16px;
            color: #20283a;
        }

        .table-header p {
            margin-top: 5px;
            font-size: 12px;
            color: #8992a2;
        }


        /* =========================
           SEARCH
        ========================= */

        .search-form {
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .search-box {
            height: 38px;
            width: 220px;

            display: flex;
            align-items: center;

            padding: 0 11px;

            border: 1px solid #dddfea;
            border-radius: 8px;

            background: white;
        }

        .search-box span {
            font-size: 14px;
            margin-right: 7px;
        }

        .search-box input {
            width: 100%;

            border: none;
            outline: none;

            font-size: 12px;
            color: #263238;
        }

        .search-box input::placeholder {
            color: #9aa3b2;
        }

        .btn-search {
            height: 38px;

            padding: 0 15px;

            border: none;
            border-radius: 8px;

            background: #7567d9;
            color: white;

            font-size: 12px;
            font-weight: bold;

            cursor: pointer;
        }

        .btn-search:hover {
            background: #6658c9;
        }

        .btn-reset {
            height: 38px;

            display: flex;
            align-items: center;

            padding: 0 13px;

            border-radius: 8px;

            background: #eeeef4;
            color: #666b79;

            text-decoration: none;

            font-size: 12px;
        }


        /* =========================
           TABLE
        ========================= */

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #fafaff;
        }

        th {
            padding: 14px 18px;

            text-align: left;

            font-size: 11px;
            font-weight: bold;

            color: #8992a2;

            text-transform: uppercase;

            border-bottom: 1px solid #eeeef4;
        }

        td {
            padding: 15px 18px;

            font-size: 13px;

            color: #5f6877;

            border-bottom: 1px solid #f0f0f5;
        }

        tbody tr:hover {
            background: #fcfbff;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        .nis {
            color: #6658c9;
            font-weight: bold;
        }

        .nama {
            color: #263238;
            font-weight: bold;
        }


        /* =========================
           AKSI
        ========================= */

        .aksi {
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .btn-edit,
        .btn-hapus {
            width: 34px;
            height: 34px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 8px;

            text-decoration: none;

            font-size: 14px;

            transition: 0.2s;
        }

        .btn-edit {
            background: #eeeafd;
        }

        .btn-edit:hover {
            background: #ddd7fa;
        }

        .btn-hapus {
            background: #fff0f0;
        }

        .btn-hapus:hover {
            background: #ffdede;
        }


        /* =========================
           EMPTY
        ========================= */

        .empty {
            text-align: center;
            padding: 45px 20px !important;
            color: #8992a2;
        }

        .empty-icon {
            font-size: 32px;
            margin-bottom: 8px;
        }

        .empty p {
            font-size: 13px;
        }


        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 1100px) {

            .table-header {
                align-items: flex-start;
                gap: 15px;
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

            .table-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .search-form {
                width: 100%;
            }

            .search-box {
                flex: 1;
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

            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .btn-tambah {
                width: 100%;
                justify-content: center;
            }

            .search-form {
                flex-direction: column;
                align-items: stretch;
            }

            .search-box,
            .btn-search,
            .btn-reset {
                width: 100%;
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


        <!-- ALERT -->

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

            <?php endif; ?>

        <?php endif; ?>


        <!-- TABLE CARD -->

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

                        <a href="v_siswa.php" class="btn-reset">
                            Reset
                        </a>

                    <?php endif; ?>

                </form>

            </div>


            <!-- TABLE -->

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

                        <?php if (!empty($data_siswa)): ?>

                            <?php $no = 1; ?>

                            <?php foreach ($data_siswa as $row): ?>

                                <tr>

                                    <td>
                                        <?= $no++; ?>
                                    </td>

                                    <td>

                                        <span class="nis">
                                            <?= htmlspecialchars($row['nis']); ?>
                                        </span>

                                    </td>

                                    <td>

                                        <div class="nama">
                                            <?= htmlspecialchars($row['nama_siswa']); ?>
                                        </div>

                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['kelas']); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['username']); ?>
                                    </td>

                                    <td>

                                        <div class="aksi">

                                            <a
                                                href="edit_siswa.php?id=<?= $row['id_siswa']; ?>"
                                                class="btn-edit"
                                                title="Edit Siswa"
                                            >
                                                ✏️
                                            </a>
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

                            <?php endforeach; ?>


                        <?php else: ?>

                            <tr>

                                <td colspan="6" class="empty">

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