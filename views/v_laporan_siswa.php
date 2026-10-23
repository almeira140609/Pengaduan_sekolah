
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
        a.status AS status_aspirasi,
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

   <link rel="stylesheet" href="../assets/v_laporan_siswa.css">


</head>

<body>

<!-- SIDEBAR -->

<div class="sidebar">

    <div class="logo">
        <h2>Pengaduan<br>Sekolah</h2>
        <p>Sistem Sarana &amp; Prasarana</p>
    </div>

    <div class="menu">

        <div class="menu-title">Menu Siswa</div>

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

<!-- MAIN -->

<div class="main-content">

    <!-- TOPBAR -->

    <div class="topbar">

        <div class="topbar-text">
            Sistem Pengaduan Sarana &amp; Prasarana Sekolah
        </div>

        <div class="siswa-info">

            <div class="siswa-avatar">
                <?= htmlspecialchars(strtoupper(substr($nama_siswa, 0, 1))); ?>
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
            <h1>Laporan Saya</h1>

            <p>
                Daftar laporan yang sudah kamu kirimkan kepada pihak sekolah.
            </p>
        </div>

        <!-- TABLE CARD -->

        <div class="table-card">

            <?php if ($result && mysqli_num_rows($result) > 0): ?>

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

                    while ($row = mysqli_fetch_assoc($result)):

                        // Ambil status progres dan status aspirasi.
                        $status_progres = strtolower(
                            trim($row['status_progres'] ?? '')
                        );

                        $status_aspirasi = strtolower(
                            trim($row['status_aspirasi'] ?? '')
                        );

                        /*
                         * Jika salah satu status menyatakan ditolak,
                         * tampilkan Ditolak.
                         */
                        if (
                            $status_progres == 'ditolak' ||
                            $status_aspirasi == 'ditolak'
                        ) {
                            $status = 'ditolak';
                        } elseif ($status_progres != '') {
                            $status = $status_progres;
                        } elseif ($status_aspirasi != '') {
                            $status = $status_aspirasi;
                        } else {
                            $status = 'menunggu';
                        }

                    ?>

                        <tr>

                            <td>
                                <?= $no++; ?>
                            </td>

                            <td>
                                <div class="judul">
                                    <?= htmlspecialchars($row['judul'] ?? '-'); ?>
                                </div>
                            </td>

                            <td>
                                <span class="kategori">
                                    <?= htmlspecialchars($row['nama_kategori'] ?? '-'); ?>
                                </span>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['lokasi'] ?? '-'); ?>
                            </td>

                            <td>

                                <?php if ($status == 'ditolak'): ?>

                                    <span class="status status-ditolak">
                                        Ditolak
                                    </span>

                                <?php elseif ($status == 'menunggu'): ?>

                                    <span class="status status-menunggu">
                                        Menunggu
                                    </span>

                                <?php elseif ($status == 'dikerjakan'): ?>

                                    <span class="status status-dikerjakan">
                                        Dikerjakan
                                    </span>

                                <?php elseif ($status == 'selesai'): ?>

                                    <span class="status status-selesai">
                                        Selesai
                                    </span>

                                <?php else: ?>

                                    <span class="status status-menunggu">
                                        <?= htmlspecialchars(ucfirst($status)); ?>
                                    </span>

                                <?php endif; ?>

                            </td>

                            <td class="tanggal">

                                <?php
                                if (!empty($row['tanggal_aspirasi'])) {
                                    echo date(
                                        'd-m-Y',
                                        strtotime($row['tanggal_aspirasi'])
                                    );
                                } else {
                                    echo '-';
                                }
                                ?>

                            </td>

                            <td class="aksi">

                                <a
                                    href="aspirasi_detail_siswa.php?id=<?= (int) $row['id_aspirasi']; ?>"
                                    class="btn-detail"
                                    title="Lihat Detail"
                                >👁️</a>

                            </td>

                        </tr>

                    <?php endwhile; ?>

                    </tbody>

                </table>

            <?php else: ?>

                <div class="empty">

                    <div class="empty-icon">📝</div>

                    <h3>Belum Ada Laporan</h3>

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
