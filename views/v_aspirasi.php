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

<link rel="stylesheet" href="../assets/v_aspirasi.css">


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
