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

    <link rel="stylesheet" href="../assets/v_histori_siswa.css">

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
