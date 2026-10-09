<?php

session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'siswa') {
    header("Location: login.php");
    exit;
}

require_once "../models/m_koneksi.php";

$koneksi = new Koneksi();
$db = $koneksi->koneksi;

$id_siswa = $_SESSION['id_siswa'];
$nama_siswa = $_SESSION['nama_siswa'];

// ==========================================
// AMBIL DATA HISTORI LAPORAN SISWA
// ==========================================

$sql = "
    SELECT
        a.id_aspirasi,
        a.judul,
        a.lokasi,
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

$query = mysqli_query($db, $sql);

if (!$query) {
    die("Query error: " . mysqli_error($db));
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Histori Laporan - Pengaduan Sekolah</title>

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
            border-bottom: 1px solid #edf0f4;
        }

        .table-header h3 {
            font-size: 16px;
            color: #20283a;
        }

        .table-header p {
            margin-top: 5px;
            font-size: 12px;
            color: #8992a2;
        }

        .table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 850px;
        }

        th {
            padding: 14px 16px;
            text-align: left;
            background: #fafaff;
            color: #697386;
            font-size: 12px;
            font-weight: bold;
            border-bottom: 1px solid #edf0f4;
        }

        td {
            padding: 15px 16px;
            font-size: 13px;
            color: #4f5968;
            border-bottom: 1px solid #f0f2f5;
            vertical-align: middle;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover td {
            background: #fcfbff;
        }

        .judul {
            font-weight: bold;
            color: #263238;
        }

        .lokasi {
            color: #707b8c;
        }

        .tanggal {
            color: #707b8c;
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
            white-space: nowrap;
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

        /* =========================
           BUTTON DETAIL
        ========================= */

        .aksi {
            text-align: center;
        }

        .btn-detail {
            display: inline-flex;
            width: 34px;
            height: 34px;
            align-items: center;
            justify-content: center;
            background: #eeeafd;
            color: #6658c9;
            border-radius: 8px;
            text-decoration: none;
            font-size: 15px;
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
            padding: 55px 20px;
            color: #8992a2;
        }

        .empty-icon {
            font-size: 35px;
            margin-bottom: 12px;
        }

        .empty h3 {
            font-size: 15px;
            color: #596273;
            margin-bottom: 6px;
        }

        .empty p {
            font-size: 12px;
        }

        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width:900px) {

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

        @media (max-width:600px) {

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

        }

    </style>

</head>

<body>

<!-- =========================
     SIDEBAR
========================= -->

<div class="sidebar">

    <div class="logo">
        <h2>Pengaduan<br>Sekolah</h2>
        <p>Sistem Sarana & Prasarana</p>
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

        <a href="v_histori_siswa.php" class="active">
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

        <div class="page-title">

            <h1>Histori Laporan</h1>

            <p>
                Riwayat laporan pengaduan yang telah kamu kirimkan.
            </p>

        </div>


        <div class="table-card">

            <div class="table-header">

                <h3>Histori Pengaduan</h3>

                <p>
                    Daftar seluruh laporan yang pernah kamu buat.
                </p>

            </div>


            <div class="table-wrapper">

                <?php if (mysqli_num_rows($query) > 0) { ?>

                    <table>

                        <thead>

                            <tr>

                                <th width="50">No</th>

                                <th>Judul Laporan</th>

                                <th>Kategori</th>

                                <th>Lokasi</th>

                                <th>Status</th>

                                <th>Tanggal</th>

                                <th width="80">Aksi</th>

                            </tr>

                        </thead>

                        <tbody>

                            <?php

                            $no = 1;

                            while ($row = mysqli_fetch_assoc($query)) {

                                $status = $row['status_progres'];

                                if (empty($status)) {
                                    $status = 'menunggu';
                                }

                                $status_class = '';

                                if ($status == 'menunggu') {
                                    $status_class = 'menunggu';
                                } elseif ($status == 'dikerjakan') {
                                    $status_class = 'dikerjakan';
                                } elseif ($status == 'selesai') {
                                    $status_class = 'selesai';
                                }

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
                                        <?= htmlspecialchars($row['nama_kategori'] ?? '-'); ?>
                                    </td>

                                    <td>
                                        <div class="lokasi">
                                            <?= htmlspecialchars($row['lokasi']); ?>
                                        </div>
                                    </td>

                                    <td>

                                        <span class="status <?= $status_class; ?>">

                                            <?= ucfirst($status); ?>

                                        </span>

                                    </td>

                                    <td>

                                        <div class="tanggal">

                                            <?= date('d-m-Y', strtotime($row['tanggal_aspirasi'])); ?>

                                        </div>

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

                            <?php } ?>

                        </tbody>

                    </table>

                <?php } else { ?>

                    <div class="empty">

                        <div class="empty-icon">
                            ◷
                        </div>

                        <h3>Belum Ada Histori</h3>

                        <p>
                            Kamu belum memiliki laporan pengaduan.
                        </p>

                    </div>

                <?php } ?>

            </div>

        </div>

    </div>

</div>

</body>
</html>
