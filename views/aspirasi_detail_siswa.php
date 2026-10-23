<?php

session_start();


// =========================
// CEK LOGIN SISWA
// =========================

if (
    !isset($_SESSION['role']) ||
    $_SESSION['role'] != 'siswa'
) {
    header("Location: login.php");
    exit;
}


require_once "../models/m_koneksi.php";
require_once "../models/m_progres.php";


$koneksi = new Koneksi();
$db = $koneksi->koneksi;


// =========================
// DATA SISWA
// =========================

$id_siswa = mysqli_real_escape_string(
    $db,
    $_SESSION['id_siswa']
);

$nama_siswa = $_SESSION['nama_siswa'] ?? 'Siswa';


// =========================
// AMBIL ID ASPIRASI
// =========================

if (
    !isset($_GET['id']) ||
    empty($_GET['id'])
) {
    header("Location: v_laporan_siswa.php");
    exit;
}

$id_aspirasi = mysqli_real_escape_string(
    $db,
    $_GET['id']
);


// =========================
// DATA ASPIRASI
// =========================

$sql = "
    SELECT
        a.id_aspirasi,
        a.judul,
        a.isi_aspirasi,
        a.lokasi,
        a.foto,
        a.tanggal_aspirasi,
        k.nama_kategori

    FROM aspirasi a

    LEFT JOIN kategori k
        ON a.id_kategori = k.id_kategori

    WHERE a.id_aspirasi = '$id_aspirasi'
    AND a.id_siswa = '$id_siswa'

    LIMIT 1
";

$result = mysqli_query($db, $sql);


if (
    !$result ||
    mysqli_num_rows($result) == 0
) {
    header("Location: v_laporan_siswa.php");
    exit;
}


$data = mysqli_fetch_assoc($result);


// =========================
// MODEL PROGRES
// =========================

$model_progres = new Progres();


// =========================
// PROGRES TERAKHIR
// =========================

$progres = $model_progres->ambilTerakhir(
    $id_aspirasi
);


// =========================
// STATUS
// =========================

$status = Progres::normalisasi(
    $progres['status_progres'] ?? 'menunggu'
);


// =========================
// RIWAYAT PROGRES
// =========================

$riwayat = $model_progres->ambilRiwayat(
    $id_aspirasi
);

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
        Detail Laporan - Pengaduan Sekolah
    </title>
<link rel="stylesheet" href="../assets/aspirasi_detail_siswa.css">
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

            <span class="icon">
                ⌂
            </span>

            <span>
                Dashboard
            </span>

        </a>


        <a
            href="v_laporan_siswa.php"
            class="active"
        >

            <span class="icon">
                ▤
            </span>

            <span>
                Laporan Saya
            </span>

        </a>


        <a href="v_histori_siswa.php">

            <span class="icon">
                ◷
            </span>

            <span>
                Histori
            </span>

        </a>

    </div>


    <div class="logout">

        <a href="logout.php">

            <span class="icon">
                ←
            </span>

            <span>
                Logout
            </span>

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

                <?= strtoupper(
                    substr($nama_siswa, 0, 1)
                ); ?>

            </div>


            <div>

                <div class="siswa-name">

                    <?= htmlspecialchars(
                        $nama_siswa
                    ); ?>

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

            <h1>
                Detail Laporan
            </h1>

            <p>
                Informasi lengkap mengenai laporan yang kamu kirimkan.
            </p>

        </div>



        <div class="detail-grid">


            <!-- =========================
                 DATA LAPORAN
            ========================= -->

            <div class="card">

                <div class="card-title">
                    Informasi Laporan
                </div>


                <div class="detail-item">

                    <div class="detail-label">
                        Judul Aspirasi
                    </div>

                    <div class="detail-title">

                        <?= htmlspecialchars(
                            $data['judul']
                        ); ?>

                    </div>

                </div>



                <div class="detail-item">

                    <div class="detail-label">
                        Kategori
                    </div>

                    <div>

                        <span class="kategori">

                            <?= htmlspecialchars(
                                $data['nama_kategori'] ?? '-'
                            ); ?>

                        </span>

                    </div>

                </div>



                <div class="detail-item">

                    <div class="detail-label">
                        Lokasi
                    </div>

                    <div class="detail-value">

                        <?= htmlspecialchars(
                            $data['lokasi']
                        ); ?>

                    </div>

                </div>



                <div class="detail-item">

                    <div class="detail-label">
                        Tanggal Laporan
                    </div>

                    <div class="detail-value">

                        <?= date(
                            'd-m-Y',
                            strtotime(
                                $data['tanggal_aspirasi']
                            )
                        ); ?>

                    </div>

                </div>



                <div class="detail-item">

                    <div class="detail-label">
                        Isi Aspirasi
                    </div>

                    <div class="detail-value">

                        <?= nl2br(
                            htmlspecialchars(
                                $data['isi_aspirasi']
                            )
                        ); ?>

                    </div>

                </div>



                <div class="detail-item">

                    <div class="detail-label">
                        Foto Pendukung
                    </div>


                    <div class="foto-container">


                        <?php if (!empty($data['foto'])) : ?>

                            <img
                                src="../uploads/<?= htmlspecialchars(
                                    $data['foto']
                                ); ?>"
                                alt="Foto laporan"
                            >

                        <?php else : ?>

                            <div class="no-foto">
                                Tidak ada foto pendukung.
                            </div>

                        <?php endif; ?>


                    </div>

                </div>

            </div>



            <!-- =========================
                 STATUS DAN RIWAYAT
            ========================= -->

            <div>


                <!-- STATUS -->

                <div class="card">

                    <div class="card-title">
                        Status Laporan
                    </div>


                    <?php if ($status == 'dikerjakan') : ?>

                        <span class="status status-dikerjakan">
                            Dikerjakan
                        </span>


                        <div class="status-info">

                            Laporan kamu sedang diproses
                            oleh admin.

                        </div>


                    <?php elseif ($status == 'selesai') : ?>

                        <span class="status status-selesai">
                            Selesai
                        </span>


                        <div class="status-info selesai">

                            Laporan kamu sudah selesai
                            ditangani oleh admin.

                        </div>


                    <?php elseif ($status == 'ditolak') : ?>

                        <span class="status status-ditolak">
                            Ditolak
                        </span>


                        <div class="status-info ditolak">

                            Laporan ini ditolak oleh admin.


                            <?php if (
                                !empty($progres['keterangan'])
                            ) : ?>

                                <br><br>

                                <strong>
                                    Keterangan:
                                </strong>

                                <br>

                                <?= nl2br(
                                    htmlspecialchars(
                                        $progres['keterangan']
                                    )
                                ); ?>

                            <?php endif; ?>


                        </div>


                    <?php else : ?>

                        <span class="status status-menunggu">
                            Menunggu
                        </span>


                        <div class="status-info">

                            Laporan kamu sedang menunggu
                            untuk diproses oleh admin.

                        </div>

                    <?php endif; ?>

                </div>



                <!-- =========================
                     RIWAYAT PROGRES
                ========================= -->

                <div
                    class="card"
                    style="margin-top: 18px;"
                >

                    <div class="card-title">
                        Riwayat Progres
                    </div>


                    <?php if (
                        $riwayat &&
                        mysqli_num_rows($riwayat) > 0
                    ) : ?>


                        <?php while (
                            $row = mysqli_fetch_assoc($riwayat)
                        ) : ?>


                            <?php

                            $status_riwayat =
                                Progres::normalisasi(
                                    $row['status_progres']
                                );


                            if (
                                $status_riwayat ==
                                'dikerjakan'
                            ) {

                                $nama_status =
                                    'Dikerjakan';

                            } elseif (
                                $status_riwayat ==
                                'selesai'
                            ) {

                                $nama_status =
                                    'Selesai';

                            } elseif (
                                $status_riwayat ==
                                'ditolak'
                            ) {

                                $nama_status =
                                    'Ditolak';

                            } else {

                                $nama_status =
                                    'Menunggu';

                            }

                            ?>


                            <div class="riwayat-item">


                                <div class="riwayat-dot"></div>


                                <div class="riwayat-status">

                                    <?= $nama_status; ?>

                                </div>


                                <div class="riwayat-tanggal">

                                    <?= date(
                                        'd-m-Y H:i',
                                        strtotime(
                                            $row[
                                                'tanggal_progres'
                                            ]
                                        )
                                    ); ?>

                                </div>


                                <?php if (
                                    !empty(
                                        $row['keterangan']
                                    )
                                ) : ?>

                                    <div class="riwayat-keterangan">

                                        <?= nl2br(
                                            htmlspecialchars(
                                                $row[
                                                    'keterangan'
                                                ]
                                            )
                                        ); ?>

                                    </div>

                                <?php endif; ?>


                                <?php if (
                                    !empty(
                                        $row['nama_admin']
                                    )
                                ) : ?>

                                    <div class="riwayat-admin">

                                        Diproses oleh:
                                        <strong>
                                            <?= htmlspecialchars(
                                                $row[
                                                    'nama_admin'
                                                ]
                                            ); ?>
                                        </strong>

                                    </div>

                                <?php endif; ?>


                            </div>


                        <?php endwhile; ?>


                    <?php else : ?>


                        <div class="tidak-ada-riwayat">

                            Belum ada progres dari admin.

                        </div>


                    <?php endif; ?>


                </div>


            </div>

        </div>



        <!-- =========================
             KEMBALI
        ========================= -->

        <div class="bottom-action">

            <a
                href="v_laporan_siswa.php"
                class="btn-kembali"
            >

                ← Kembali ke Laporan

            </a>

        </div>


    </div>

</div>


</body>

</html>