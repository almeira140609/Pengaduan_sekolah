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

    <link rel="stylesheet" href="../assets/v_histori.css">

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