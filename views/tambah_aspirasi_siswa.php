<?php

session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'siswa') {
    header("Location: login.php");
    exit;
}

require_once "../models/m_koneksi.php";

$koneksi = new Koneksi();
$db = $koneksi->koneksi;

$nama_siswa = $_SESSION['nama_siswa'] ?? 'Siswa';
$nis = $_SESSION['nis'] ?? '-';
$kelas = $_SESSION['kelas'] ?? '-';

$kategori = mysqli_query(
    $db,
    "SELECT id_kategori, nama_kategori
     FROM kategori
     ORDER BY nama_kategori ASC"
);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Laporan - Pengaduan Sekolah</title>

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
           FORM CARD
        ========================= */

        .form-card {
            background: white;

            border-radius: 12px;

            border: 1px solid #e7eaf0;

            box-shadow: 0 5px 20px rgba(31,41,55,0.05);

            padding: 28px;
        }


        /* =========================
           FORM
        ========================= */

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;

            margin-bottom: 8px;

            font-size: 13px;

            font-weight: bold;

            color: #303b4e;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {

            width: 100%;

            padding: 11px 13px;

            border: 1px solid #dfe3ea;

            border-radius: 8px;

            background: white;

            color: #303b4e;

            font-family: Arial, Helvetica, sans-serif;

            font-size: 13px;

            outline: none;

            transition: 0.2s;
        }

        .form-group input,
        .form-group select {
            height: 42px;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {

            border-color: #7567d9;

            box-shadow: 0 0 0 3px rgba(117,103,217,0.10);
        }

        .form-group textarea {

            min-height: 140px;

            resize: vertical;
        }

        .form-group small {

            display: block;

            margin-top: 7px;

            font-size: 11px;

            color: #8992a2;
        }


        /* =========================
           BUTTON
        ========================= */

        .form-actions {

            display: flex;

            gap: 10px;

            margin-top: 5px;
        }

        .btn-simpan {

            display: inline-flex;

            align-items: center;

            justify-content: center;

            gap: 8px;

            background: #7567d9;

            color: white;

            border: none;

            text-decoration: none;

            padding: 12px 18px;

            height: 42px;

            border-radius: 8px;

            font-size: 13px;

            font-weight: bold;

            cursor: pointer;

            box-shadow: 0 5px 12px rgba(117,103,217,0.20);

            transition: 0.2s;
        }

        .btn-simpan:hover {

            background: #6658c9;
        }

        .btn-batal {

            display: inline-flex;

            align-items: center;

            justify-content: center;

            background: #f0f1f5;

            color: #555d6d;

            text-decoration: none;

            padding: 12px 18px;

            height: 42px;

            border-radius: 8px;

            font-size: 13px;

            font-weight: bold;

            transition: 0.2s;
        }

        .btn-batal:hover {

            background: #e5e6eb;
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

            .form-card {
                padding: 20px;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn-simpan,
            .btn-batal {
                width: 100%;
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


        <a href="dashboard_siswa.php">

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
                Tambah Laporan
            </h1>

            <p>
                Sampaikan laporan mengenai sarana dan prasarana sekolah.
            </p>

        </div>


        <!-- FORM -->

        <div class="form-card">


            <form
                action="../controllers/c_aspirasi.php?aksi=tambah"
                method="POST"
                enctype="multipart/form-data"
            >


                <!-- JUDUL -->

                <div class="form-group">

                    <label>
                        Judul Aspirasi
                    </label>

                    <input
                        type="text"
                        name="judul"
                        placeholder="Contoh: Lampu Ruang Kelas Mati"
                        required
                    >

                </div>


                <!-- KATEGORI -->

                <div class="form-group">

                    <label>
                        Kategori
                    </label>

                    <select
                        name="id_kategori"
                        required
                    >

                        <option value="">
                            -- Pilih Kategori --
                        </option>

                        <?php while ($row = mysqli_fetch_assoc($kategori)) : ?>

                            <option value="<?= $row['id_kategori']; ?>">

                                <?= htmlspecialchars($row['nama_kategori']); ?>

                            </option>

                        <?php endwhile; ?>

                    </select>

                </div>


                <!-- LOKASI -->

                <div class="form-group">

                    <label>
                        Lokasi
                    </label>

                    <input
                        type="text"
                        name="lokasi"
                        placeholder="Contoh: Ruang XII RPL 1"
                        required
                    >

                </div>


                <!-- ISI ASPIRASI -->

                <div class="form-group">

                    <label>
                        Isi Aspirasi
                    </label>

                    <textarea
                        name="isi_aspirasi"
                        placeholder="Jelaskan masalah yang ingin dilaporkan..."
                        required
                    ></textarea>

                </div>


                <!-- FOTO -->

                <div class="form-group">

                    <label>
                        Foto Pendukung
                    </label>

                    <input
                        type="file"
                        name="foto"
                        accept=".jpg,.jpeg,.png,.webp"
                    >

                    <small>
                        Foto bersifat opsional. Format JPG, JPEG, PNG, atau WEBP. Maksimal 5 MB.
                    </small>

                </div>


                <!-- BUTTON -->

                <div class="form-actions">

                    <button
                        type="submit"
                        name="tambah"
                        class="btn-simpan"
                    >
                        ＋ Kirim Laporan
                    </button>


                    <a
                        href="dashboard_siswa.php"
                        class="btn-batal"
                    >
                        Batal
                    </a>

                </div>


            </form>

        </div>


    </div>

</div>


</body>

</html>
