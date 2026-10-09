<?php

session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

require_once "../models/m_kategori.php";

$kategori = new Kategori();

$search = isset($_GET['search']) ? $_GET['search'] : '';

$data_kategori = $kategori->tampil($search);

$nama_admin = $_SESSION['nama_admin'];

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Data Kategori - Pengaduan Sekolah</title>

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

        .page-header {
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            justify-content: space-between;
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
           BUTTON TAMBAH
        ========================= */

        .btn-tambah {
            display: inline-flex;
            align-items: center;

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

            border-bottom: 1px solid #eeeef4;
        }

        .table-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .table-title h3 {
            font-size: 16px;
            color: #20283a;
        }

        .table-title p {
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

        .search-form input {
            width: 220px;
            height: 38px;

            padding: 0 12px;

            border: 1px solid #dddfea;
            border-radius: 8px;

            outline: none;

            font-size: 12px;
            color: #263238;
        }

        .search-form input:focus {
            border-color: #7567d9;
        }

        .search-form input::placeholder {
            color: #9aa3b2;
        }

        .search-form button {
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

        .search-form button:hover {
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

        .btn-reset:hover {
            background: #e3e2e9;
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


        /* =========================
           AKSI
        ========================= */

        .btn-edit,
        .btn-hapus {
            display: inline-flex;

            align-items: center;
            justify-content: center;

            width: 34px;
            height: 34px;

            border-radius: 8px;

            text-decoration: none;

            font-size: 15px;

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
           EMPTY DATA
        ========================= */

        .empty-data {
            text-align: center;

            padding: 50px 20px !important;

            color: #8992a2;
        }

        .empty-icon {
            font-size: 32px;
            margin-bottom: 10px;
        }

        .empty-data strong {
            display: block;

            font-size: 13px;

            color: #5f6877;

            margin-bottom: 5px;
        }

        .empty-data p {
            font-size: 12px;
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

            .table-title {
                flex-direction: column;
                align-items: flex-start;
            }

            .search-form {
                width: 100%;
            }

            .search-form input {
                flex: 1;
                width: auto;
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

            .search-form input,
            .search-form button,
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


        <!-- PAGE HEADER -->

        <div class="page-header">

            <div class="page-title">

                <h1>
                    Data Kategori
                </h1>

                <p>
                    Kelola kategori sarana dan prasarana sekolah.
                </p>

            </div>


            <a href="tambah_kategori.php" class="btn-tambah">
                ＋ Tambah Kategori
            </a>

        </div>


        <!-- TABLE CARD -->

        <div class="table-card">


            <div class="table-header">

                <div class="table-title">

                    <div>

                        <h3>
                            Daftar Kategori
                        </h3>

                        <p>
                            Kategori digunakan untuk mengelompokkan pengaduan siswa.
                        </p>

                    </div>


                    <form method="GET" class="search-form">

                        <input
                            type="text"
                            name="search"
                            placeholder="Cari kategori..."
                            value="<?= htmlspecialchars($search); ?>"
                        >


                        <button type="submit">
                            Cari
                        </button>


                        <?php if ($search != ''): ?>

                            <a href="v_kategori.php" class="btn-reset">
                                Reset
                            </a>

                        <?php endif; ?>

                    </form>

                </div>

            </div>


            <!-- TABLE -->

            <div class="table-wrapper">

                <table>

                    <thead>

                        <tr>

                            <th width="70">
                                No
                            </th>

                            <th>
                                Nama Kategori
                            </th>

                            <th width="150">
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    <?php

                    $no = 1;

                    if (mysqli_num_rows($data_kategori) > 0) {

                        while ($row = mysqli_fetch_assoc($data_kategori)) {

                    ?>

                        <tr>

                            <td>
                                <?= $no++; ?>
                            </td>


                            <td>
                                <?= htmlspecialchars($row['nama_kategori']); ?>
                            </td>


                            <td>

                                <a
                                    href="kategori_edit.php?id=<?= $row['id_kategori']; ?>"
                                    class="btn-edit"
                                    title="Edit Kategori"
                                >
                                    ✏️
                                </a>


                                <a
                                    href="../controllers/c_kategori.php?hapus=<?= $row['id_kategori']; ?>"
                                    class="btn-hapus"
                                    title="Hapus Kategori"
                                    onclick="return confirm('Yakin ingin menghapus kategori ini?')"
                                >
                                    🗑️
                                </a>

                            </td>

                        </tr>


                    <?php

                        }

                    } else {

                    ?>

                        <tr>

                            <td colspan="3" class="empty-data">

                                <div class="empty-icon">
                                    🔍
                                </div>

                                <strong>
                                    Data kategori tidak ditemukan
                                </strong>

                                <p>
                                    Coba gunakan kata kunci pencarian yang berbeda.
                                </p>

                            </td>

                        </tr>

                    <?php

                    }

                    ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>


</body>

</html>