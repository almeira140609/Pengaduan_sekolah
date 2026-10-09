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
// FILTER
// =========================

$search = isset($_GET['search']) ? $_GET['search'] : '';
$tanggal = isset($_GET['tanggal']) ? $_GET['tanggal'] : '';
$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : '';
$id_siswa = isset($_GET['id_siswa']) ? $_GET['id_siswa'] : '';
$id_kategori = isset($_GET['id_kategori']) ? $_GET['id_kategori'] : '';


// =========================
// DATA SISWA UNTUK FILTER
// =========================

$data_siswa = mysqli_query(
    $db,
    "SELECT id_siswa, nama_siswa, nis
     FROM siswa
     ORDER BY nama_siswa ASC"
);


// =========================
// DATA KATEGORI UNTUK FILTER
// =========================

$data_kategori = mysqli_query(
    $db,
    "SELECT id_kategori, nama_kategori
     FROM kategori
     ORDER BY nama_kategori ASC"
);


// =========================
// QUERY ASPIRASI
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

        k.nama_kategori,

        COALESCE(
            (
                SELECT p.status_progres
                FROM progres p
                WHERE p.id_aspirasi = a.id_aspirasi
                ORDER BY p.id_progres DESC
                LIMIT 1
            ),
            'Menunggu'
        ) AS status_progres

    FROM aspirasi a

    LEFT JOIN siswa s
        ON a.id_siswa = s.id_siswa

    LEFT JOIN kategori k
        ON a.id_kategori = k.id_kategori

    WHERE 1=1
";


// =========================
// FILTER PENCARIAN
// =========================

if ($search != '') {

    $search_safe = mysqli_real_escape_string($db, $search);

    $sql .= "
        AND (
            a.judul LIKE '%$search_safe%'
            OR a.isi_aspirasi LIKE '%$search_safe%'
            OR a.lokasi LIKE '%$search_safe%'
            OR s.nama_siswa LIKE '%$search_safe%'
            OR s.nis LIKE '%$search_safe%'
            OR k.nama_kategori LIKE '%$search_safe%'
        )
    ";
}


// =========================
// FILTER TANGGAL
// =========================

if ($tanggal != '') {

    $tanggal_safe = mysqli_real_escape_string($db, $tanggal);

    $sql .= "
        AND DATE(a.tanggal_aspirasi) = '$tanggal_safe'
    ";
}


// =========================
// FILTER BULAN
// =========================

if ($bulan != '') {

    $bulan_safe = mysqli_real_escape_string($db, $bulan);

    $sql .= "
        AND DATE_FORMAT(a.tanggal_aspirasi, '%Y-%m') = '$bulan_safe'
    ";
}


// =========================
// FILTER SISWA
// =========================

if ($id_siswa != '') {

    $id_siswa_safe = mysqli_real_escape_string($db, $id_siswa);

    $sql .= "
        AND a.id_siswa = '$id_siswa_safe'
    ";
}


// =========================
// FILTER KATEGORI
// =========================

if ($id_kategori != '') {

    $id_kategori_safe = mysqli_real_escape_string($db, $id_kategori);

    $sql .= "
        AND a.id_kategori = '$id_kategori_safe'
    ";
}


// =========================
// URUTKAN
// =========================

$sql .= "
    ORDER BY a.tanggal_aspirasi DESC
";


// =========================
// JALANKAN QUERY
// =========================

$data_aspirasi = mysqli_query($db, $sql);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Data Aspirasi - Pengaduan Sekolah</title>


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


        /* =========================
           PAGE HEADER
        ========================= */

        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }


        .page-title h1 {
            font-size: 25px;
            color: #263238;
            margin-bottom: 7px;
        }


        .page-title p {
            font-size: 13px;
            color: #8994a8;
        }


        /* =========================
           TABLE CARD
        ========================= */

        .table-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.05);
            overflow: hidden;
        }


        .table-header {
            padding: 22px 24px;
            border-bottom: 1px solid #edf0f4;
        }


        .table-title {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
        }


        .title-info h3 {
            font-size: 16px;
            color: #263238;
            margin-bottom: 5px;
        }


        .title-info p {
            font-size: 12px;
            color: #9aa3b2;
        }


        /* =========================
           FILTER
        ========================= */

        .filter-form {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            flex-wrap: wrap;
            gap: 8px;
            max-width: 760px;
        }


        .filter-form input,
        .filter-form select {
            height: 38px;
            border: 1px solid #dfe3ea;
            border-radius: 7px;
            padding: 0 10px;
            font-size: 12px;
            color: #4d5868;
            background: white;
            outline: none;
        }


        .filter-form input:focus,
        .filter-form select:focus {
            border-color: #7567d9;
            box-shadow: 0 0 0 2px rgba(117,103,217,0.08);
        }


        .filter-form input[type="text"] {
            width: 230px;
        }


        .filter-form input[type="date"] {
            width: 145px;
        }


        .filter-form input[type="month"] {
            width: 145px;
        }


        .filter-form select {
            width: 145px;
        }


        .btn-filter {
            height: 38px;
            border: none;
            border-radius: 7px;
            padding: 0 15px;
            background: #7567d9;
            color: white;
            font-size: 12px;
            cursor: pointer;
        }


        .btn-filter:hover {
            background: #6658c9;
        }


        .btn-reset {
            height: 38px;
            display: flex;
            align-items: center;
            padding: 0 13px;
            border-radius: 7px;
            background: #f1f2f5;
            color: #626d7d;
            text-decoration: none;
            font-size: 12px;
        }


        .btn-reset:hover {
            background: #e5e7ec;
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
            min-width: 1050px;
        }


        thead {
            background: #fafbfc;
        }


        th {
            padding: 14px 16px;
            text-align: left;
            font-size: 11px;
            color: #8994a8;
            font-weight: bold;
            text-transform: uppercase;
            border-bottom: 1px solid #edf0f4;
            white-space: nowrap;
        }


        td {
            padding: 15px 16px;
            border-bottom: 1px solid #f0f1f4;
            font-size: 13px;
            color: #4d5868;
            vertical-align: middle;
        }


        tbody tr:hover {
            background: #faf9ff;
        }


        .nomor {
            color: #8994a8;
            text-align: center;
        }


        /* =========================
           SISWA
        ========================= */

        .siswa-name {
            font-size: 13px;
            font-weight: bold;
            color: #303b4a;
            margin-bottom: 4px;
        }


        .siswa-nis {
            font-size: 11px;
            color: #9aa3b2;
        }


        /* =========================
           ASPIRASI
        ========================= */

        .judul-aspirasi {
            font-size: 13px;
            font-weight: bold;
            color: #303b4a;
            margin-bottom: 5px;
        }


        .isi-aspirasi {
            font-size: 11px;
            color: #9aa3b2;
            max-width: 240px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }


        /* =========================
           KATEGORI
        ========================= */

        .kategori-badge {
            display: inline-block;
            padding: 5px 9px;
            border-radius: 6px;
            background: #eeeafd;
            color: #6658c9;
            font-size: 11px;
            font-weight: bold;
        }


        /* =========================
           STATUS
        ========================= */

        .status {
            display: inline-block;
            padding: 5px 9px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: bold;
            white-space: nowrap;
        }


        .status-menunggu {
            background: #fff4d6;
            color: #b98500;
        }


        .status-dikerjakan {
            background: #e8f0ff;
            color: #4b70c9;
        }


        .status-selesai {
            background: #e2f6ea;
            color: #2e8b57;
        }


        /* =========================
           TANGGAL
        ========================= */

        .tanggal {
            white-space: nowrap;
            color: #7a8494;
            font-size: 12px;
        }


        /* =========================
           AKSI
        ========================= */

        .aksi {
            display: flex;
            align-items: center;
            gap: 7px;
        }


        .btn-detail,
        .btn-histori {
            width: 34px;
            height: 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 7px;
            text-decoration: none;
            font-size: 15px;
            transition: 0.2s;
        }


        .btn-detail {
            background: #eeeafd;
        }


        .btn-detail:hover {
            background: #ddd8fb;
        }


        .btn-histori {
            background: #e8f0ff;
        }


        .btn-histori:hover {
            background: #d9e5fc;
        }


        /* =========================
           DATA KOSONG
        ========================= */

        .empty-data {
            text-align: center;
            padding: 60px 20px !important;
            color: #8994a8;
        }


        .empty-icon {
            font-size: 32px;
            margin-bottom: 12px;
        }


        .empty-data strong {
            display: block;
            font-size: 14px;
            color: #4d5868;
            margin-bottom: 6px;
        }


        .empty-data p {
            font-size: 12px;
            color: #9aa3b2;
        }


        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 1200px) {

            .table-title {
                flex-direction: column;
            }

            .filter-form {
                width: 100%;
                max-width: none;
                justify-content: flex-start;
            }

        }


        @media (max-width: 768px) {

            .sidebar {
                width: 200px;
            }

            .main-content {
                margin-left: 200px;
            }

            .content {
                padding: 20px;
            }

            .topbar {
                padding: 0 20px;
            }

            .topbar-text {
                display: none;
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


        <a href="v_kategori.php">

            <span class="icon">▦</span>

            <span>
                Kategori
            </span>

        </a>


        <a href="v_aspirasi.php" class="active">

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


        <!-- =========================
             PAGE HEADER
        ========================= -->

        <div class="page-header">

            <div class="page-title">

                <h1>
                    Data Aspirasi
                </h1>

                <p>
                    Kelola pengaduan sarana dan prasarana yang dikirim oleh siswa.
                </p>

            </div>

        </div>


        <!-- =========================
             TABLE CARD
        ========================= -->

        <div class="table-card">


            <div class="table-header">

                <div class="table-title">


                    <div class="title-info">

                        <h3>
                            Daftar Aspirasi
                        </h3>

                        <p>
                            Informasi pengaduan yang dikirim oleh siswa.
                        </p>

                    </div>


                    <!-- =========================
                         FILTER
                    ========================= -->

                    <form method="GET" class="filter-form">


                        <input
                            type="text"
                            name="search"
                            placeholder="Cari siswa, judul, atau kategori..."
                            value="<?= htmlspecialchars($search); ?>"
                        >


                        <input
                            type="date"
                            name="tanggal"
                            value="<?= htmlspecialchars($tanggal); ?>"
                            title="Filter berdasarkan tanggal"
                        >


                        <input
                            type="month"
                            name="bulan"
                            value="<?= htmlspecialchars($bulan); ?>"
                            title="Filter berdasarkan bulan"
                        >


                        <select name="id_siswa">

                            <option value="">
                                Semua Siswa
                            </option>

                            <?php

                            if ($data_siswa) {

                                while ($siswa = mysqli_fetch_assoc($data_siswa)) {

                            ?>

                                <option
                                    value="<?= $siswa['id_siswa']; ?>"
                                    <?= ($id_siswa == $siswa['id_siswa']) ? 'selected' : ''; ?>
                                >

                                    <?= htmlspecialchars($siswa['nama_siswa']); ?>

                                </option>

                            <?php

                                }

                            }

                            ?>

                        </select>


                        <select name="id_kategori">

                            <option value="">
                                Semua Kategori
                            </option>

                            <?php

                            if ($data_kategori) {

                                while ($kategori = mysqli_fetch_assoc($data_kategori)) {

                            ?>

                                <option
                                    value="<?= $kategori['id_kategori']; ?>"
                                    <?= ($id_kategori == $kategori['id_kategori']) ? 'selected' : ''; ?>
                                >

                                    <?= htmlspecialchars($kategori['nama_kategori']); ?>

                                </option>

                            <?php

                                }

                            }

                            ?>

                        </select>


                        <button
                            type="submit"
                            class="btn-filter"
                        >
                            Cari
                        </button>


                        <?php

                        if (
                            $search != '' ||
                            $tanggal != '' ||
                            $bulan != '' ||
                            $id_siswa != '' ||
                            $id_kategori != ''
                        ) {

                        ?>

                            <a
                                href="v_aspirasi.php"
                                class="btn-reset"
                            >
                                Reset
                            </a>

                        <?php } ?>


                    </form>

                </div>

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

                            <th width="180">
                                Siswa
                            </th>

                            <th width="250">
                                Aspirasi
                            </th>

                            <th width="140">
                                Kategori
                            </th>

                            <th width="150">
                                Lokasi
                            </th>

                            <th width="110">
                                Status
                            </th>

                            <th width="130">
                                Tanggal
                            </th>

                            <th width="120">
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    <?php

                    $no = 1;

                    if (
                        $data_aspirasi &&
                        mysqli_num_rows($data_aspirasi) > 0
                    ) {

                        while ($row = mysqli_fetch_assoc($data_aspirasi)) {

                    ?>

                        <tr>


                            <td class="nomor">
                                <?= $no++; ?>
                            </td>


                            <td>

                                <div class="siswa-name">

                                    <?= htmlspecialchars(
                                        $row['nama_siswa'] ?? '-'
                                    ); ?>

                                </div>


                                <div class="siswa-nis">

                                    NIS:
                                    <?= htmlspecialchars(
                                        $row['nis'] ?? '-'
                                    ); ?>

                                </div>

                            </td>


                            <td>

                                <div class="judul-aspirasi">

                                    <?= htmlspecialchars(
                                        $row['judul'] ?? '-'
                                    ); ?>

                                </div>


                                <div class="isi-aspirasi">

                                    <?= htmlspecialchars(
                                        $row['isi_aspirasi'] ?? '-'
                                    ); ?>

                                </div>

                            </td>


                            <td>

                                <span class="kategori-badge">

                                    <?= htmlspecialchars(
                                        $row['nama_kategori'] ?? '-'
                                    ); ?>

                                </span>

                            </td>


                            <td>

                                <?= htmlspecialchars(
                                    $row['lokasi'] ?? '-'
                                ); ?>

                            </td>


                            <td>

                                <?php

                                $status = $row['status_progres'] ?? 'Menunggu';

                                $status_class = 'status-menunggu';


                                if (
                                    strtolower($status) == 'dikerjakan'
                                ) {

                                    $status_class = 'status-dikerjakan';

                                }


                                if (
                                    strtolower($status) == 'selesai'
                                ) {

                                    $status_class = 'status-selesai';

                                }

                                ?>


                                <span class="status <?= $status_class; ?>">

                                    <?= htmlspecialchars($status); ?>

                                </span>

                            </td>


                            <td class="tanggal">

                                <?php

                                if (!empty($row['tanggal_aspirasi'])) {

                                    echo date(
                                        'd-m-Y',
                                        strtotime(
                                            $row['tanggal_aspirasi']
                                        )
                                    );

                                } else {

                                    echo '-';

                                }

                                ?>

                            </td>


                            <td>

                                <div class="aksi">


                                    <a
                                        href="aspirasi_detail.php?id=<?= $row['id_aspirasi']; ?>"
                                        class="btn-detail"
                                        title="Lihat Detail"
                                    >
                                        👁️
</a>
                                </div>

                            </td>


                        </tr>

                    <?php

                        }

                    } else {

                    ?>

                        <tr>

                            <td
                                colspan="8"
                                class="empty-data"
                            >

                                <div class="empty-icon">
                                    🔍
                                </div>


                                <strong>
                                    Data aspirasi tidak ditemukan
                                </strong>


                                <p>
                                    Belum ada pengaduan atau kata kunci pencarian tidak ditemukan.
                                </p>

                            </td>

                        </tr>

                    <?php } ?>


                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>


</body>

</html>
