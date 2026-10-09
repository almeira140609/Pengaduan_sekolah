<?php

session_start();


// =========================
// CEK LOGIN ADMIN
// =========================

if (
    !isset($_SESSION['role']) ||
    $_SESSION['role'] != 'admin'
) {
    header("Location: login.php");
    exit;
}


require_once "../models/m_koneksi.php";

$koneksi = new Koneksi();
$db = $koneksi->koneksi;


$nama_admin = $_SESSION['nama_admin'];


// =========================
// CEK ID ASPIRASI
// =========================

if (
    !isset($_GET['id']) ||
    empty($_GET['id'])
) {
    header("Location: v_aspirasi.php");
    exit;
}


$id_aspirasi = mysqli_real_escape_string(
    $db,
    $_GET['id']
);


// =========================
// AMBIL DATA ASPIRASI
// =========================

$sql = "
    SELECT
        a.id_aspirasi,
        a.judul,
        a.isi_aspirasi,
        a.lokasi,
        a.foto,
        a.tanggal_aspirasi,

        s.nama_siswa,
        s.nis,
        s.kelas,

        k.nama_kategori

    FROM aspirasi a

    LEFT JOIN siswa s
        ON a.id_siswa = s.id_siswa

    LEFT JOIN kategori k
        ON a.id_kategori = k.id_kategori

    WHERE a.id_aspirasi = '$id_aspirasi'
";


$query = mysqli_query(
    $db,
    $sql
);


$data = mysqli_fetch_assoc(
    $query
);


if (!$data) {

    header(
        "Location: v_aspirasi.php"
    );

    exit;
}


// =========================
// AMBIL PROGRES TERAKHIR
// =========================

$sql_progres = "
    SELECT
        p.id_progres,
        p.id_admin,
        p.status_progres,
        p.keterangan,
        p.foto,
        p.tanggal_progres

    FROM progres p

    WHERE p.id_aspirasi = '$id_aspirasi'

    ORDER BY p.id_progres DESC

    LIMIT 1
";


$query_progres = mysqli_query(
    $db,
    $sql_progres
);


$progres = mysqli_fetch_assoc(
    $query_progres
);


// =========================
// STATUS SEKARANG
// =========================

$status_sekarang =
    !empty($progres['status_progres'])
    ? $progres['status_progres']
    : 'menunggu';

?>

<!DOCTYPE html>

<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Detail Aspirasi - Pengaduan Sekolah
    </title>


    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }


        body {
            font-family:
                Arial,
                Helvetica,
                sans-serif;

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

            box-shadow:
                3px 0 15px
                rgba(0,0,0,0.08);
        }


        .logo {
            padding: 27px 24px;

            border-bottom:
                1px solid
                rgba(255,255,255,0.08);
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

            box-shadow:
                0 5px 12px
                rgba(117,103,217,0.25);
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

            border-top:
                1px solid
                rgba(255,255,255,0.08);
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

            border-bottom:
                1px solid #e5e8ee;

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


        .btn-kembali {
            display: inline-block;

            margin-top: 15px;

            padding: 10px 15px;

            background: #eef0f5;

            color: #46515f;

            text-decoration: none;

            border-radius: 7px;

            font-size: 13px;
        }


        .btn-kembali:hover {
            background: #e1e4eb;
        }


        /* =========================
           GRID
        ========================= */

        .detail-grid {
            display: grid;

            grid-template-columns:
                1fr 1fr;

            gap: 20px;

            margin-bottom: 20px;
        }


        .card {
            background: white;

            border-radius: 12px;

            border:
                1px solid #e7eaf0;

            box-shadow:
                0 5px 20px
                rgba(31,41,55,0.05);

            padding: 22px;
        }


        .card h3 {
            font-size: 16px;

            color: #303b4e;

            margin-bottom: 20px;
        }


        /* =========================
           DETAIL
        ========================= */

        .detail-item {
            margin-bottom: 17px;
        }


        .detail-item:last-child {
            margin-bottom: 0;
        }


        .detail-label {
            font-size: 11px;

            color: #98a1af;

            margin-bottom: 6px;

            text-transform: uppercase;

            letter-spacing: 0.5px;
        }


        .detail-value {
            font-size: 14px;

            color: #303b4e;

            line-height: 1.6;
        }


        .isi {
            white-space: pre-line;
        }


        /* =========================
           FOTO ASPIRASI
        ========================= */

        .foto-aspirasi {
            margin-top: 18px;
        }


        .foto-aspirasi img {
            width: 100%;

            max-width: 450px;

            max-height: 300px;

            object-fit: contain;

            border-radius: 8px;

            border:
                1px solid #e5e8ee;

            background: #fafbfc;
        }


        .tidak-ada-foto {
            color: #98a1af;

            font-size: 13px;

            padding: 20px;

            background: #fafbfc;

            border-radius: 8px;
        }


        /* =========================
           STATUS
        ========================= */

        .status {
            display: inline-block;

            padding: 7px 13px;

            border-radius: 20px;

            font-size: 11px;

            font-weight: bold;
        }


        .menunggu {
            background: #fff3cd;

            color: #856404;
        }


        .dikerjakan {
            background: #dcecff;

            color: #245b9c;
        }


        .selesai {
            background: #d9f5e5;

            color: #18733c;
        }


        .ditolak {
            background: #fde8e8;

            color: #c0392b;
        }


        /* =========================
           FORM PROGRES
        ========================= */

        .form-group {
            margin-bottom: 18px;
        }


        .form-group label {
            display: block;

            margin-bottom: 7px;

            font-size: 13px;

            font-weight: bold;

            color: #46515f;
        }


        .form-group select,
        .form-group textarea,
        .form-group input[type="file"] {
            width: 100%;

            padding: 11px 12px;

            border:
                1px solid #dfe3ea;

            border-radius: 7px;

            font-family:
                Arial,
                Helvetica,
                sans-serif;

            font-size: 13px;

            color: #46515f;

            background: white;
        }


        .form-group textarea {
            min-height: 120px;

            resize: vertical;
        }


        .form-group input[type="file"] {
            padding: 9px;
        }


        .form-group textarea:focus,
        .form-group input[type="file"]:focus {
            outline: none;

            border-color: #7567d9;

            box-shadow:
                0 0 0 3px
                rgba(117,103,217,0.10);
        }


        .form-group small {
            display: block;

            margin-top: 6px;

            font-size: 11px;

            color: #98a1af;
        }


        /* =========================
           ADMIN OTOMATIS
        ========================= */

        .admin-processing {
            width: 100%;

            padding: 11px 12px;

            background: #f5f3ff;

            border:
                1px solid #d9d4f7;

            border-radius: 7px;

            display: flex;

            align-items: center;

            gap: 12px;
        }


        .admin-processing-icon {
            width: 35px;
            height: 35px;

            border-radius: 50%;

            background: #7567d9;

            color: white;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 15px;
        }


        .admin-processing-info {
            display: flex;

            flex-direction: column;

            gap: 3px;
        }


        .admin-processing-name {
            font-size: 13px;

            font-weight: bold;

            color: #303b4e;
        }


        .admin-processing-role {
            font-size: 11px;

            color: #8a91a0;
        }


        /* =========================
           STATUS CONTAINER
        ========================= */

        .status-container {
            width: 100%;

            padding: 11px 12px;

            background: #fafbfc;

            border:
                1px solid #dfe3ea;

            border-radius: 7px;
        }


        /* =========================
           TOMBOL
        ========================= */

        .tombol-proses {
            display: flex;

            gap: 10px;

            flex-wrap: wrap;
        }


        .btn-simpan {
            border: none;

            padding: 11px 18px;

            background: #7567d9;

            color: white;

            border-radius: 7px;

            font-size: 13px;

            cursor: pointer;

            transition: 0.2s;
        }


        .btn-simpan:hover {
            background: #6658c9;
        }


        .btn-tolak {
            border: 1px solid #f1cccc;

            padding: 11px 18px;

            background: #fde8e8;

            color: #c0392b;

            border-radius: 7px;

            font-size: 13px;

            cursor: pointer;
        }


        .btn-tolak:hover {
            background: #f8d7d7;
        }


        /* =========================
           FORM TOLAK
        ========================= */

        .form-tolak {
            display: none;

            margin-top: 20px;

            padding: 20px;

            background: #fff8f8;

            border:
                1px solid #f1cccc;

            border-radius: 10px;
        }


        .form-tolak-header {
            display: flex;

            align-items: center;

            justify-content: space-between;

            margin-bottom: 10px;
        }


        .form-tolak-header h4 {
            color: #c0392b;

            font-size: 15px;
        }


        .btn-close {
            border: none;

            background: transparent;

            color: #888;

            font-size: 22px;

            cursor: pointer;
        }


        .form-tolak-info {
            font-size: 12px;

            line-height: 1.6;

            color: #777;

            margin-bottom: 18px;
        }


        .btn-konfirmasi-tolak {
            border: none;

            padding: 11px 18px;

            background: #c0392b;

            color: white;

            border-radius: 7px;

            font-size: 13px;

            cursor: pointer;
        }


        .btn-konfirmasi-tolak:hover {
            background: #a93226;
        }


        /* =========================
           SELESAI / DITOLAK
        ========================= */

        .selesai-box {
            padding: 12px;

            background: #d9f5e5;

            color: #18733c;

            border-radius: 7px;

            font-size: 13px;
        }


        .ditolak-box {
            padding: 12px;

            background: #fde8e8;

            color: #c0392b;

            border:
                1px solid #f1cccc;

            border-radius: 7px;

            font-size: 13px;
        }


        /* =========================
           PROGRES TERAKHIR
        ========================= */

        .progres-box {
            margin-top: 20px;

            padding: 18px;

            background: #fafbfc;

            border:
                1px solid #e7eaf0;

            border-radius: 8px;
        }


        .progres-box h4 {
            font-size: 13px;

            color: #303b4e;

            margin-bottom: 12px;
        }


        .progres-keterangan {
            margin-top: 13px;

            font-size: 13px;

            color: #687282;

            line-height: 1.6;

            white-space: pre-line;
        }


        .progres-tanggal {
            margin-top: 10px;

            font-size: 11px;

            color: #98a1af;
        }


        /* =========================
           FOTO PROGRES
        ========================= */

        .foto-progres {
            margin-top: 15px;
        }


        .foto-progres p {
            font-size: 11px;

            color: #98a1af;

            margin-bottom: 7px;
        }


        .foto-progres img {
            width: 100%;

            max-width: 350px;

            max-height: 250px;

            object-fit: contain;

            border-radius: 8px;

            border:
                1px solid #e5e8ee;

            background: white;
        }


        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 900px) {

            .detail-grid {
                grid-template-columns: 1fr;
            }
        }


        @media (max-width: 800px) {

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


        <a
            href="v_aspirasi.php"
            class="active"
        >

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

                <?= strtoupper(
                    substr($nama_admin, 0, 1)
                ); ?>

            </div>


            <div>

                <div class="admin-name">

                    <?= htmlspecialchars(
                        $nama_admin
                    ); ?>

                </div>


                <div class="admin-role">
                    Admin
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
                    Detail Aspirasi
                </h1>


                <p>
                    Melihat detail laporan siswa dan
                    memberikan progres penanganan.
                </p>


                <a
                    href="v_aspirasi.php"
                    class="btn-kembali"
                >
                    ← Kembali ke Aspirasi
                </a>

            </div>

        </div>


        <!-- =========================
             DATA SISWA + ASPIRASI
        ========================= -->

        <div class="detail-grid">


            <!-- DATA SISWA -->

            <div class="card">

                <h3>
                    Data Siswa
                </h3>


                <div class="detail-item">

                    <div class="detail-label">
                        Nama Siswa
                    </div>

                    <div class="detail-value">

                        <?= htmlspecialchars(
                            $data['nama_siswa'] ?? '-'
                        ); ?>

                    </div>

                </div>


                <div class="detail-item">

                    <div class="detail-label">
                        NIS
                    </div>

                    <div class="detail-value">

                        <?= htmlspecialchars(
                            $data['nis'] ?? '-'
                        ); ?>

                    </div>

                </div>


                <div class="detail-item">

                    <div class="detail-label">
                        Kelas
                    </div>

                    <div class="detail-value">

                        <?= htmlspecialchars(
                            $data['kelas'] ?? '-'
                        ); ?>

                    </div>

                </div>


                <div class="detail-item">

                    <div class="detail-label">
                        Kategori
                    </div>

                    <div class="detail-value">

                        <?= htmlspecialchars(
                            $data['nama_kategori'] ?? '-'
                        ); ?>

                    </div>

                </div>

            </div>


            <!-- DETAIL ASPIRASI -->

            <div class="card">

                <h3>
                    Detail Aspirasi
                </h3>


                <div class="detail-item">

                    <div class="detail-label">
                        Judul
                    </div>

                    <div class="detail-value">

                        <?= htmlspecialchars(
                            $data['judul'] ?? '-'
                        ); ?>

                    </div>

                </div>


                <div class="detail-item">

                    <div class="detail-label">
                        Lokasi
                    </div>

                    <div class="detail-value">

                        <?= htmlspecialchars(
                            $data['lokasi'] ?? '-'
                        ); ?>

                    </div>

                </div>


                <div class="detail-item">

                    <div class="detail-label">
                        Isi Aspirasi
                    </div>

                    <div class="detail-value isi">

                        <?= htmlspecialchars(
                            $data['isi_aspirasi'] ?? '-'
                        ); ?>

                    </div>

                </div>


                <div class="detail-item">

                    <div class="detail-label">
                        Tanggal Aspirasi
                    </div>

                    <div class="detail-value">

                        <?= !empty(
                            $data['tanggal_aspirasi']
                        )
                            ? date(
                                'd-m-Y H:i',
                                strtotime(
                                    $data['tanggal_aspirasi']
                                )
                            )
                            : '-';
                        ?>

                    </div>

                </div>


                <!-- FOTO ASPIRASI -->

                <div class="detail-item foto-aspirasi">

                    <div class="detail-label">
                        Foto Aspirasi
                    </div>


                    <?php if (
                        !empty($data['foto'])
                    ): ?>

                        <img
                            src="../uploads/<?= htmlspecialchars(
                                $data['foto']
                            ); ?>"
                            alt="Foto Aspirasi"
                        >

                    <?php else: ?>

                        <div class="tidak-ada-foto">

                            Belum ada foto yang
                            dilampirkan siswa.

                        </div>

                    <?php endif; ?>

                </div>

            </div>

        </div>


        <!-- =========================
             PROGRES
        ========================= -->

        <div class="card">

            <h3>
                Progres / Feedback
            </h3>


            <?php
            /*
             * FORM HANYA DITAMPILKAN
             * JIKA BELUM SELESAI / DITOLAK
             */
            ?>

            <?php if (
                $status_sekarang != 'selesai' &&
                $status_sekarang != 'ditolak'
            ): ?>


                <!-- =========================
                     FORM FEEDBACK
                ========================= -->

                <form
                    action="../controllers/c_progres.php?aksi=tambah"
                    method="POST"
                    enctype="multipart/form-data"
                >

                    <input
                        type="hidden"
                        name="id_aspirasi"
                        value="<?= $data['id_aspirasi']; ?>"
                    >


                    <!-- ADMIN -->

                    <div class="form-group">

                        <label>
                            Admin yang Memproses
                        </label>


                        <div class="admin-processing">

                            <div class="admin-processing-icon">
                                👤
                            </div>


                            <div class="admin-processing-info">

                                <div class="admin-processing-name">

                                    <?= htmlspecialchars(
                                        $nama_admin
                                    ); ?>

                                </div>


                                <div class="admin-processing-role">
                                    Admin yang sedang login
                                </div>

                            </div>

                        </div>


                        <small>
                            Admin otomatis sesuai akun
                            yang sedang login.
                        </small>

                    </div>


                    <!-- STATUS -->

                    <div class="form-group">

                        <label>
                            Status Progres Saat Ini
                        </label>


                        <div class="status-container">

                            <span
                                class="status <?= strtolower(
                                    $status_sekarang
                                ); ?>"
                            >

                                <?= ucfirst(
                                    $status_sekarang
                                ); ?>

                            </span>

                        </div>


                        <small>
                            Status berubah melalui
                            tombol tindakan admin.
                        </small>

                    </div>


                    <!-- KETERANGAN -->

                    <div class="form-group">

                        <label for="keterangan">
                            Keterangan / Feedback
                        </label>


                        <textarea
                            name="keterangan"
                            id="keterangan"
                            placeholder="Tulis keterangan atau feedback untuk siswa..."
                            required
                        ></textarea>

                    </div>


                    <!-- FOTO -->

                    <div class="form-group">

                        <label for="foto">
                            Foto Bukti Perbaikan
                        </label>


                        <input
                            type="file"
                            name="foto"
                            id="foto"
                            accept="image/*"
                        >


                        <small>
                            Lampirkan foto sebagai bukti
                            perbaikan atau perkembangan aspirasi.
                        </small>

                    </div>


                    <!-- =========================
                         TOMBOL
                    ========================= -->

                    <?php if (
                        $status_sekarang == 'menunggu'
                    ): ?>


                        <div class="tombol-proses">

                            <button
                                type="submit"
                                name="tindakan"
                                value="mulai"
                                class="btn-simpan"
                            >
                                🔧 Mulai Kerjakan
                            </button>


                            <button
                                type="button"
                                class="btn-tolak"
                                onclick="bukaTolak()"
                            >
                                ❌ Tolak Laporan
                            </button>

                        </div>


                    <?php elseif (
                        $status_sekarang == 'dikerjakan'
                    ): ?>


                        <button
                            type="submit"
                            name="tindakan"
                            value="selesai"
                            class="btn-simpan"
                        >
                            ✅ Selesaikan Progres
                        </button>


                    <?php endif; ?>

                </form>


                <!-- =========================
                     FORM TOLAK
                ========================= -->

                <?php if (
                    $status_sekarang == 'menunggu'
                ): ?>

                    <div
                        id="form-tolak"
                        class="form-tolak"
                    >

                        <div class="form-tolak-header">

                            <h4>
                                ❌ Tolak Laporan
                            </h4>


                            <button
                                type="button"
                                class="btn-close"
                                onclick="tutupTolak()"
                            >
                                ×
                            </button>

                        </div>


                        <p class="form-tolak-info">

                            Pastikan laporan memang
                            tidak sesuai atau tidak dapat
                            diverifikasi sebelum menolaknya.

                        </p>


                        <form
                            action="../controllers/c_progres.php?aksi=tambah"
                            method="POST"
                        >

                            <input
                                type="hidden"
                                name="id_aspirasi"
                                value="<?= $data['id_aspirasi']; ?>"
                            >


                            <input
                                type="hidden"
                                name="tindakan"
                                value="tolak"
                            >


                            <div class="form-group">

                                <label
                                    for="alasan_penolakan"
                                >
                                    Alasan Penolakan
                                </label>


                                <textarea
                                    name="keterangan"
                                    id="alasan_penolakan"
                                    placeholder="Contoh: Laporan tidak ditemukan setelah dilakukan pengecekan..."
                                    required
                                ></textarea>

                            </div>


                            <button
                                type="submit"
                                class="btn-konfirmasi-tolak"
                            >
                                ❌ Konfirmasi Tolak Laporan
                            </button>

                        </form>

                    </div>

                <?php endif; ?>


            <?php elseif (
                $status_sekarang == 'selesai'
            ): ?>


                <!-- =========================
                     SUDAH SELESAI
                ========================= -->

                <div class="selesai-box">

                    ✅ Aspirasi ini sudah selesai ditangani.

                </div>


            <?php elseif (
                $status_sekarang == 'ditolak'
            ): ?>


                <!-- =========================
                     SUDAH DITOLAK
                ========================= -->

                <div class="ditolak-box">

                    ❌ Laporan ini sudah ditolak
                    dan tidak dapat diproses kembali.

                </div>

            <?php endif; ?>


            <!-- =========================
                 PROGRES TERAKHIR
            ========================= -->

            <?php if ($progres): ?>

                <div class="progres-box">

                    <h4>
                        Progres Terakhir
                    </h4>


                    <span
                        class="status <?= strtolower(
                            $progres['status_progres']
                        ); ?>"
                    >

                        <?= ucfirst(
                            $progres['status_progres']
                        ); ?>

                    </span>


                    <div class="progres-keterangan">

                        <?= htmlspecialchars(
                            $progres['keterangan'] ?? '-'
                        ); ?>

                    </div>


                    <!-- FOTO BUKTI -->

                    <?php if (
                        !empty($progres['foto'])
                    ): ?>

                        <div class="foto-progres">

                            <p>
                                Foto Bukti Perbaikan
                            </p>


                            <img
                                src="../uploads/<?= htmlspecialchars(
                                    $progres['foto']
                                ); ?>"
                                alt="Foto Bukti Perbaikan"
                            >

                        </div>

                    <?php endif; ?>


                    <div class="progres-tanggal">

                        <?= !empty(
                            $progres['tanggal_progres']
                        )
                            ? date(
                                'd-m-Y H:i',
                                strtotime(
                                    $progres['tanggal_progres']
                                )
                            )
                            : '-';
                        ?>

                    </div>

                </div>

            <?php endif; ?>

        </div>

    </div>

</div>


<!-- =========================
     JAVASCRIPT
========================= -->

<script>

function bukaTolak()
{
    document.getElementById(
        "form-tolak"
    ).style.display = "block";
}


function tutupTolak()
{
    document.getElementById(
        "form-tolak"
    ).style.display = "none";
}

</script>


</body>

</html>
