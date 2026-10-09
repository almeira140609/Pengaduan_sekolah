<?php

session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit;
}

require_once "../models/m_koneksi.php";

$koneksi = new Koneksi();
$db = $koneksi->koneksi;

$nama_admin = $_SESSION['nama_admin'];


// =========================
// AMBIL DATA HISTORI
// =========================

$sql = "
    SELECT
        a.id_aspirasi,
        a.judul,
        a.tanggal_aspirasi,

        s.nama_siswa,
        s.nis,

        k.nama_kategori,

        COALESCE(
            (
                SELECT p.status_progres
                FROM progres p
                WHERE p.id_aspirasi = a.id_aspirasi
                ORDER BY p.id_progres DESC
                LIMIT 1
            ),
            'menunggu'
        ) AS status_progres,

        (
            SELECT p.keterangan
            FROM progres p
            WHERE p.id_aspirasi = a.id_aspirasi
            ORDER BY p.id_progres DESC
            LIMIT 1
        ) AS keterangan,

        (
            SELECT p.tanggal_progres
            FROM progres p
            WHERE p.id_aspirasi = a.id_aspirasi
            ORDER BY p.id_progres DESC
            LIMIT 1
        ) AS tanggal_progres

    FROM aspirasi a

    LEFT JOIN siswa s
        ON a.id_siswa = s.id_siswa

    LEFT JOIN kategori k
        ON a.id_kategori = k.id_kategori

    ORDER BY a.id_aspirasi DESC
";

$query = mysqli_query($db, $sql);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Histori Aspirasi - Pengaduan Sekolah</title>

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


        /* =========================
           CARD
        ========================= */

        .table-card {
            background: white;
            border-radius: 12px;
            border: 1px solid #e7eaf0;
            box-shadow: 0 5px 20px rgba(31,41,55,0.05);
            overflow: hidden;
        }

        .table-header {
            padding: 20px 22px;
            border-bottom: 1px solid #eceff3;
        }

        .table-header h3 {
            font-size: 16px;
            color: #303b4e;
        }

        .table-header p {
            margin-top: 5px;
            font-size: 12px;
            color: #98a1af;
        }


        /* =========================
           TABLE
        ========================= */

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            min-width: 1000px;
            border-collapse: collapse;
        }

        table th {
            padding: 14px 18px;
            background: #fafbfc;
            color: #7b8493;
            font-size: 12px;
            text-align: left;
            border-bottom: 1px solid #e7eaf0;
        }

        table td {
            padding: 16px 18px;
            font-size: 13px;
            color: #46515f;
            border-bottom: 1px solid #eef0f3;
            vertical-align: middle;
        }

        table tbody tr:hover {
            background: #fafbfe;
        }

        table tbody tr:last-child td {
            border-bottom: none;
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


        /* =========================
           KETERANGAN
        ========================= */

        .keterangan {
            max-width: 250px;
            line-height: 1.5;
            color: #687282;
        }


        /* =========================
           DATA KOSONG
        ========================= */

        .data-kosong {
            text-align: center;
            padding: 45px !important;
            color: #98a1af;
        }

        .data-kosong-icon {
            font-size: 30px;
            margin-bottom: 10px;
        }

        .data-kosong strong {
            display: block;
            color: #303b4e;
            margin-bottom: 5px;
        }


        /* =========================
           RESPONSIVE
        ========================= */

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


        <a href="v_aspirasi.php">

            <span class="icon">▤</span>

            <span>Aspirasi</span>

        </a>


        <a href="v_histori.php" class="active">

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
     MAIN CONTENT
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


        <!-- HEADER -->

        <div class="page-header">

            <div class="page-title">

                <h1>
                    Histori Aspirasi
                </h1>

                <p>
                    Melihat riwayat perkembangan aspirasi yang telah dikirim oleh siswa.
                </p>

            </div>

        </div>


        <!-- TABLE CARD -->

        <div class="table-card">


            <div class="table-header">

                <h3>
                    Riwayat Aspirasi
                </h3>

                <p>
                    Data aspirasi dan progres terbaru yang telah diberikan oleh admin.
                </p>

            </div>


            <div class="table-wrapper">

                <table>

                    <thead>

                        <tr>

                            <th>No</th>

                            <th>Siswa</th>

                            <th>NIS</th>

                            <th>Kategori</th>

                            <th>Judul Aspirasi</th>

                            <th>Status</th>

                            <th>Keterangan</th>

                            <th>Tanggal</th>

                        </tr>

                    </thead>


                    <tbody>

<?php

$no = 1;

if (mysqli_num_rows($query) > 0) {

    while ($row = mysqli_fetch_assoc($query)) {

        $status = strtolower($row['status_progres'] ?? 'menunggu');

        $status_class = $status;

        $status_text = ucfirst($status);

?>

                        <tr>

                            <td>
                                <?= $no++; ?>
                            </td>


                            <td>
                                <?= htmlspecialchars($row['nama_siswa'] ?? '-'); ?>
                            </td>


                            <td>
                                <?= htmlspecialchars($row['nis'] ?? '-'); ?>
                            </td>


                            <td>
                                <?= htmlspecialchars($row['nama_kategori'] ?? '-'); ?>
                            </td>


                            <td>
                                <?= htmlspecialchars($row['judul'] ?? '-'); ?>
                            </td>


                            <td>

                                <span class="status <?= $status_class; ?>">

                                    <?= htmlspecialchars($status_text); ?>

                                </span>

                            </td>


                            <td>

                                <div class="keterangan">

                                    <?= htmlspecialchars(
                                        !empty($row['keterangan'])
                                            ? $row['keterangan']
                                            : '-'
                                    ); ?>

                                </div>

                            </td>


                            <td>

<?php

if (!empty($row['tanggal_progres'])) {

    echo date(
        'd-m-Y H:i',
        strtotime($row['tanggal_progres'])
    );

} else {

    echo date(
        'd-m-Y H:i',
        strtotime($row['tanggal_aspirasi'])
    );

}

?>

                            </td>

                        </tr>

<?php

    }

} else {

?>

                        <tr>

                            <td colspan="8" class="data-kosong">

                                <div class="data-kosong-icon">
                                    ◷
                                </div>

                                <strong>
                                    Belum ada histori aspirasi
                                </strong>

                                <span>
                                    Data histori akan muncul setelah aspirasi memiliki progres.
                                </span>

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