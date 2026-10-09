<?php

session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'siswa') {
    header("Location: login.php");
    exit;
}

require_once "../models/m_koneksi.php";

$koneksi = new Koneksi();
$db = $koneksi->koneksi;

$id_siswa = mysqli_real_escape_string(
    $db,
    $_SESSION['id_siswa']
);

$nama_siswa = $_SESSION['nama_siswa'] ?? 'Siswa';


// =========================
// AMBIL DATA LAPORAN
// =========================

$sql = "
    SELECT
        a.id_aspirasi,
        a.judul,
        a.isi_aspirasi,
        a.lokasi,
        a.foto,
        a.tanggal_aspirasi,
        k.nama_kategori,

        (
            SELECT p.status_progres
            FROM progres p
            WHERE p.id_aspirasi = a.id_aspirasi
            ORDER BY p.id_progres DESC
            LIMIT 1
        ) AS status_progres

    FROM aspirasi a

    LEFT JOIN kategori k
        ON a.id_kategori = k.id_kategori

    WHERE a.id_siswa = '$id_siswa'

    ORDER BY a.id_aspirasi DESC
";

$result = mysqli_query($db, $sql);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Laporan Saya - Pengaduan Sekolah</title>

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
           LAPORAN CARD
        ========================= */

        .table-card {

            background: white;

            border-radius: 12px;

            border: 1px solid #e7eaf0;

            box-shadow: 0 5px 20px rgba(31,41,55,0.05);

            padding: 22px;

            overflow-x: auto;
        }


        /* =========================
           TABLE
        ========================= */

        table {
            width: 100%;

            border-collapse: collapse;

            min-width: 850px;
        }

        th {
            padding: 13px 12px;

            text-align: left;

            font-size: 11px;

            color: #8992a2;

            text-transform: uppercase;

            letter-spacing: 0.5px;

            border-bottom: 1px solid #e7eaf0;

            white-space: nowrap;
        }

        td {
            padding: 15px 12px;

            font-size: 13px;

            color: #303b4e;

            border-bottom: 1px solid #f0f1f4;

            vertical-align: middle;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        tbody tr:hover {
            background: #faf9ff;
        }


        /* =========================
           JUDUL
        ========================= */

        .judul {
            font-weight: bold;

            color: #303b4e;

            max-width: 220px;
        }


        /* =========================
           KATEGORI
        ========================= */

        .kategori {
            display: inline-block;

            background: #eeeafd;

            color: #6658c9;

            padding: 6px 10px;

            border-radius: 6px;

            font-size: 11px;

            font-weight: bold;

            white-space: nowrap;
        }


        /* =========================
           STATUS
        ========================= */

        .status {

            display: inline-block;

            padding: 6px 10px;

            border-radius: 20px;

            font-size: 11px;

            font-weight: bold;

            text-transform: capitalize;

            white-space: nowrap;
        }

        .status-menunggu {
            background: #fff3cd;

            color: #856404;
        }

        .status-dikerjakan {
            background: #dcecff;

            color: #245b9c;
        }

        .status-selesai {
            background: #d9f5e5;

            color: #18733c;
        }


        /* =========================
           TANGGAL
        ========================= */

        .tanggal {
            white-space: nowrap;

            color: #7a8494;
        }


        /* =========================
           AKSI
        ========================= */

        .aksi {
            text-align: center;

            white-space: nowrap;
        }

        .btn-detail {

            display: inline-flex;

            align-items: center;

            justify-content: center;

            width: 34px;

            height: 34px;

            background: #eeeafd;

            color: #6658c9;

            border-radius: 8px;

            text-decoration: none;

            font-size: 16px;

            transition: 0.2s;
        }

        .btn-detail:hover {

            background: #7567d9;

            color: white;
        }


        /* =========================
           EMPTY DATA
        ========================= */

        .empty {

            text-align: center;

            padding: 45px 20px;

            color: #8992a2;
        }

        .empty-icon {

            font-size: 35px;

            margin-bottom: 12px;
        }

        .empty h3 {

            font-size: 15px;

            color: #303b4e;

            margin-bottom: 6px;
        }

        .empty p {

            font-size: 12px;

            margin-bottom: 18px;
        }

        .btn-tambah {

            display: inline-flex;

            align-items: center;

            gap: 8px;

            background: #7567d9;

            color: white;

            text-decoration: none;

            padding: 11px 17px;

            border-radius: 8px;

            font-size: 13px;

            font-weight: bold;
        }

        .btn-tambah:hover {

            background: #6658c9;
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

            .table-card {
                padding: 15px;
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


        <a href="v_laporan_siswa.php" class="active">

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
                Laporan Saya
            </h1>

            <p>
                Daftar laporan yang sudah kamu kirimkan kepada pihak sekolah.
            </p>

        </div>


        <!-- TABLE CARD -->

        <div class="table-card">


            <?php if ($result && mysqli_num_rows($result) > 0) : ?>


                <table>

                    <thead>

                        <tr>

                            <th>No</th>

                            <th>Judul Aspirasi</th>

                            <th>Kategori</th>

                            <th>Lokasi</th>

                            <th>Status</th>

                            <th>Tanggal</th>

                            <th>Aksi</th>

                        </tr>

                    </thead>


                    <tbody>


                    <?php

                    $no = 1;

                    while ($row = mysqli_fetch_assoc($result)) :

                        $status = $row['status_progres'] ?? 'menunggu';

                    ?>


                        <tr>


                            <td>
                                <?= $no++; ?>
                            </td>


                            <td>

                                <div class="judul">

                                    <?= htmlspecialchars($row['judul']); ?>

                                </div>

                            </td>


                            <td>

                                <span class="kategori">

                                    <?= htmlspecialchars(
                                        $row['nama_kategori'] ?? '-'
                                    ); ?>

                                </span>

                            </td>


                            <td>

                                <?= htmlspecialchars(
                                    $row['lokasi']
                                ); ?>

                            </td>


                            <td>


                                <?php if ($status == 'menunggu') : ?>

                                    <span class="status status-menunggu">
                                        Menunggu
                                    </span>


                                <?php elseif ($status == 'dikerjakan') : ?>

                                    <span class="status status-dikerjakan">
                                        Dikerjakan
                                    </span>


                                <?php elseif ($status == 'selesai') : ?>

                                    <span class="status status-selesai">
                                        Selesai
                                    </span>


                                <?php else : ?>

                                    <span class="status status-menunggu">
                                        Menunggu
                                    </span>

                                <?php endif; ?>


                            </td>


                            <td class="tanggal">

                                <?= date(
                                    'd-m-Y',
                                    strtotime($row['tanggal_aspirasi'])
                                ); ?>

                            </td>


                            <td class="aksi">

                                <a
                                    href="aspirasi_detail_siswa.php?id=<?= $row['id_aspirasi']; ?>"
                                    class="btn-detail"
                                    title="Lihat Detail"
                                >
                                    👁️
                                </a>

                            </td>


                        </tr>


                    <?php endwhile; ?>


                    </tbody>

                </table>


            <?php else : ?>


                <div class="empty">

                    <div class="empty-icon">
                        📝
                    </div>

                    <h3>
                        Belum Ada Laporan
                    </h3>

                    <p>
                        Kamu belum mengirimkan laporan atau aspirasi.
                    </p>

                    <a
                        href="tambah_aspirasi_siswa.php"
                        class="btn-tambah"
                    >
                        ＋ Tambah Laporan
                    </a>

                </div>


            <?php endif; ?>


        </div>


    </div>

</div>


</body>

</html>
