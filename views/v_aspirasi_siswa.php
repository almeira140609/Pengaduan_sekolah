<?php

require_once "../models/m_aspirasi.php";

$aspirasi = new Aspirasi();

$data_kategori = $aspirasi->kategori();

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Kirim Aspirasi</title>

    <style>

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f5f6fb;
            color: #2d3748;
        }

        .main-content {
            margin-left: 230px;
            min-height: 100vh;
        }

        /* =========================
           TOPBAR
        ========================= */

        .topbar {
            height: 75px;
            background-color: white;
            border-bottom: 1px solid #e5e7eb;

            display: flex;
            justify-content: space-between;
            align-items: center;

            padding: 0 35px;
        }

        .topbar-title {
            font-size: 15px;
            font-weight: 600;
            color: #374151;
        }

        .student-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .student-avatar {
            width: 38px;
            height: 38px;

            border-radius: 50%;

            background-color: #7567d9;
            color: white;

            display: flex;
            align-items: center;
            justify-content: center;

            font-weight: bold;
        }

        .student-text strong {
            display: block;
            font-size: 14px;
            color: #374151;
        }

        .student-text span {
            font-size: 12px;
            color: #9ca3af;
        }

        /* =========================
           CONTENT
        ========================= */

        .content {
            padding: 35px;
        }

        .page-header {
            margin-bottom: 25px;
        }

        .page-header h1 {
            font-size: 27px;
            color: #1f2937;
            margin-bottom: 7px;
        }

        .page-header p {
            color: #8a94a6;
            font-size: 14px;
        }

        /* =========================
           FORM CARD
        ========================= */

        .form-card {
            background-color: white;

            border-radius: 14px;

            padding: 30px;

            max-width: 800px;

            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.05);
        }

        .form-group {
            margin-bottom: 22px;
        }

        .form-group label {
            display: block;

            margin-bottom: 8px;

            font-size: 14px;
            font-weight: 600;

            color: #374151;
        }

        .required {
            color: #e74c3c;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {

            width: 100%;

            padding: 12px 14px;

            border: 1px solid #d9dce5;

            border-radius: 8px;

            font-size: 14px;

            outline: none;

            transition: 0.2s;

            font-family: Arial, sans-serif;

            background-color: white;
        }

        .form-group textarea {

            min-height: 150px;

            resize: vertical;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {

            border-color: #7567d9;

            box-shadow:
                0 0 0 3px rgba(117, 103, 217, 0.1);
        }

        .form-help {

            margin-top: 7px;

            font-size: 12px;

            color: #9ca3af;
        }

        /* =========================
           FOTO
        ========================= */

        .file-box {

            border: 2px dashed #d9dce5;

            border-radius: 10px;

            padding: 25px;

            text-align: center;

            background-color: #fafbff;

            transition: 0.2s;
        }

        .file-box:hover {

            border-color: #7567d9;

            background-color: #f8f7ff;
        }

        .file-icon {

            font-size: 35px;

            margin-bottom: 10px;
        }

        .file-box input {

            border: none;

            padding: 0;

            background: transparent;
        }

        /* =========================
           BUTTON
        ========================= */

        .form-actions {

            display: flex;

            gap: 10px;

            margin-top: 30px;

            padding-top: 20px;

            border-top: 1px solid #eee;
        }

        .btn {

            display: inline-block;

            padding: 11px 18px;

            border-radius: 8px;

            text-decoration: none;

            border: none;

            cursor: pointer;

            font-size: 14px;

            font-weight: 600;
        }

        .btn-primary {

            background-color: #7567d9;

            color: white;
        }

        .btn-primary:hover {

            background-color: #6557c7;
        }

        .btn-secondary {

            background-color: #eef0f4;

            color: #4b5563;
        }

        .btn-secondary:hover {

            background-color: #e2e5eb;
        }

        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 768px) {

            .main-content {

                margin-left: 0;
            }

            .content {

                padding: 20px;
            }

            .topbar {

                padding: 0 20px;
            }

            .form-card {

                padding: 22px;
            }

        }

    </style>

</head>


<body>

    <?php include "navbar_siswa.php"; ?>


    <div class="main-content">


        <!-- TOPBAR -->

        <div class="topbar">

            <div class="topbar-title">

                Sistem Pengaduan Sarana & Prasarana Sekolah

            </div>


            <div class="student-info">

                <div class="student-avatar">

                    S

                </div>

                <div class="student-text">

                    <strong>Siswa</strong>

                    <span>Pengguna</span>

                </div>

            </div>

        </div>



        <!-- CONTENT -->

        <div class="content">


            <div class="page-header">

                <h1>Kirim Aspirasi</h1>

                <p>
                    Sampaikan pengaduan mengenai sarana dan prasarana sekolah.
                </p>

            </div>



            <div class="form-card">


                <form
                    method="POST"
                    action="../controllers/c_aspirasi.php"
                    enctype="multipart/form-data"
                >


                    <!-- ID SISWA SEMENTARA -->

                    <input
                        type="hidden"
                        name="id_siswa"
                        value="1"
                    >


                    <!-- JUDUL -->

                    <div class="form-group">

                        <label for="judul">

                            Judul Aspirasi
                            <span class="required">*</span>

                        </label>

                        <input
                            type="text"
                            id="judul"
                            name="judul"
                            placeholder="Contoh: Lampu kelas rusak"
                            maxlength="150"
                            required
                        >

                    </div>



                    <!-- KATEGORI -->

                    <div class="form-group">

                        <label for="id_kategori">

                            Kategori
                            <span class="required">*</span>

                        </label>

                        <select
                            id="id_kategori"
                            name="id_kategori"
                            required
                        >

                            <option value="">
                                -- Pilih Kategori --
                            </option>


                            <?php

                            while ($row = mysqli_fetch_assoc($data_kategori)) {

                            ?>

                                <option
                                    value="<?= $row['id_kategori']; ?>"
                                >

                                    <?= htmlspecialchars(
                                        $row['nama_kategori']
                                    ); ?>

                                </option>

                            <?php

                            }

                            ?>

                        </select>

                    </div>



                    <!-- ISI -->

                    <div class="form-group">

                        <label for="isi_aspirasi">

                            Isi Aspirasi
                            <span class="required">*</span>

                        </label>

                        <textarea
                            id="isi_aspirasi"
                            name="isi_aspirasi"
                            placeholder="Jelaskan masalah yang ingin kamu laporkan secara lengkap..."
                            required
                        ></textarea>

                        <div class="form-help">

                            Jelaskan kondisi atau masalah yang terjadi agar dapat
                            ditindaklanjuti dengan lebih mudah.

                        </div>

                    </div>



                    <!-- LOKASI -->

                    <div class="form-group">

                        <label for="lokasi">

                            Lokasi
                            <span class="required">*</span>

                        </label>

                        <input
                            type="text"
                            id="lokasi"
                            name="lokasi"
                            placeholder="Contoh: Ruang XI IPA 1"
                            maxlength="100"
                            required
                        >

                    </div>



                    <!-- FOTO -->

                    <div class="form-group">

                        <label for="foto">

                            Foto Bukti
                            <span class="required">*</span>

                        </label>


                        <div class="file-box">

                            <div class="file-icon">
                                📷
                            </div>

                            <input
                                type="file"
                                id="foto"
                                name="foto"
                                accept=".jpg,.jpeg,.png,.webp"
                                required
                            >

                            <div class="form-help">

                                Format JPG, JPEG, PNG, atau WEBP.
                                Maksimal 5 MB.

                            </div>

                        </div>

                    </div>



                    <!-- BUTTON -->

                    <div class="form-actions">

                        <a
                            href="dashboard_siswa.php"
                            class="btn btn-secondary"
                        >
                            Kembali
                        </a>


                        <button
                            type="submit"
                            name="tambah"
                            class="btn btn-primary"
                        >

                            Kirim Aspirasi

                        </button>

                    </div>


                </form>


            </div>

        </div>

    </div>


</body>

</html>