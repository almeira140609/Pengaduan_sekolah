<?php

session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'siswa') {
    header("Location: login.php");
    exit;
}

require_once "../models/m_koneksi.php";

$koneksi = new Koneksi();
$db = $koneksi->koneksi;

$nama_siswa = $_SESSION['nama_siswa'] ?? 'Siswa';
$nis = $_SESSION['nis'] ?? '-';
$kelas = $_SESSION['kelas'] ?? '-';

$kategori = mysqli_query(
    $db,
    "SELECT id_kategori, nama_kategori
     FROM kategori
     ORDER BY nama_kategori ASC"
);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Laporan - Pengaduan Sekolah</title>


<link rel="stylesheet" href="../assets/tambah_aspirasi_siswa.css">

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


        <a href="v_laporan_siswa.php">

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
                Tambah Laporan
            </h1>

            <p>
                Sampaikan laporan mengenai sarana dan prasarana sekolah.
            </p>

        </div>


        <!-- FORM -->

        <div class="form-card">


            <form
                action="../controllers/c_aspirasi.php?aksi=tambah"
                method="POST"
                enctype="multipart/form-data"
            >


                <!-- JUDUL -->

                <div class="form-group">

                    <label>
                        Judul Aspirasi
                    </label>

                    <input
                        type="text"
                        name="judul"
                        placeholder="Contoh: Lampu Ruang Kelas Mati"
                        required
                    >

                </div>


                <!-- KATEGORI -->

                <div class="form-group">

                    <label>
                        Kategori
                    </label>

                    <select
                        name="id_kategori"
                        required
                    >

                        <option value="">
                            -- Pilih Kategori --
                        </option>

                        <?php while ($row = mysqli_fetch_assoc($kategori)) : ?>

                            <option value="<?= $row['id_kategori']; ?>">

                                <?= htmlspecialchars($row['nama_kategori']); ?>

                            </option>

                        <?php endwhile; ?>

                    </select>

                </div>


                <!-- LOKASI -->

                <div class="form-group">

                    <label>
                        Lokasi
                    </label>

                    <input
                        type="text"
                        name="lokasi"
                        placeholder="Contoh: Ruang XII RPL 1"
                        required
                    >

                </div>


                <!-- ISI ASPIRASI -->

                <div class="form-group">

                    <label>
                        Isi Aspirasi
                    </label>

                    <textarea
                        name="isi_aspirasi"
                        placeholder="Jelaskan masalah yang ingin dilaporkan..."
                        required
                    ></textarea>

                </div>


                <!-- FOTO -->

                <div class="form-group">

                    <label>
                        Foto Pendukung
                    </label>

                    <input
                        type="file"
                        name="foto"
                        accept=".jpg,.jpeg,.png,.webp"
                    >

                    <small>
                        Foto bersifat opsional. Format JPG, JPEG, PNG, atau WEBP. Maksimal 5 MB.
                    </small>

                </div>


                <!-- BUTTON -->

                <div class="form-actions">

                    <button
                        type="submit"
                        name="tambah"
                        class="btn-simpan"
                    >
                        ＋ Kirim Laporan
                    </button>


                    <a
                        href="dashboard_siswa.php"
                        class="btn-batal"
                    >
                        Batal
                    </a>

                </div>


            </form>

        </div>


    </div>

</div>


</body>

</html>
