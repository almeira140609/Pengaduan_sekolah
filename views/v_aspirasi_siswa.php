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

    <link rel="stylesheet" href="../assets/v_aspirasi_siswa.css">


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