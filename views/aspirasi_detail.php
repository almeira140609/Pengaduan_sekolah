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
<link rel="stylesheet" href="../assets/aspirasi_detail.css">
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
