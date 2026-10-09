<?php

session_start();


// =========================
// CEK LOGIN ADMIN
// =========================

if (
    !isset($_SESSION['role']) ||
    $_SESSION['role'] != 'admin'
) {
    header("Location: ../views/login.php");
    exit;
}


require_once "../models/m_progres.php";

$progres = new Progres();

$aksi = isset($_GET['aksi'])
    ? $_GET['aksi']
    : '';


// =========================
// TAMBAH PROGRES
// =========================

if ($aksi == "tambah") {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {


        // =========================
        // AMBIL DATA FORM
        // =========================

        $id_aspirasi = isset($_POST['id_aspirasi'])
            ? $_POST['id_aspirasi']
            : '';

        $keterangan = isset($_POST['keterangan'])
            ? trim($_POST['keterangan'])
            : '';

        $tindakan = isset($_POST['tindakan'])
            ? $_POST['tindakan']
            : '';


        // =========================
        // CEK ID ASPIRASI
        // =========================

        if (empty($id_aspirasi)) {

            header(
                "Location: ../views/v_aspirasi.php"
            );

            exit;
        }


        // =========================
        // CEK TINDAKAN
        // =========================

        $tindakan_valid = [
            'feedback',
            'mulai',
            'selesai',
            'tolak'
        ];


        if (!in_array($tindakan, $tindakan_valid)) {

            header(
                "Location: ../views/aspirasi_detail.php?id="
                . $id_aspirasi
            );

            exit;
        }


        // =========================
        // CEK STATUS TERAKHIR
        // =========================

        $data_terakhir =
            $progres->ambilTerakhir(
                $id_aspirasi
            );


        if ($data_terakhir) {

            $status_sekarang =
                strtolower(
                    trim(
                        $data_terakhir[
                            'status_progres'
                        ]
                    )
                );

        } else {

            $status_sekarang = 'menunggu';
        }


        // =========================
        // JIKA SUDAH SELESAI
        // ATAU DITOLAK
        // =========================

        if (
            $status_sekarang == 'selesai' ||
            $status_sekarang == 'ditolak'
        ) {

            header(
                "Location: ../views/aspirasi_detail.php?id="
                . $id_aspirasi
            );

            exit;
        }


        // =========================
        // CEK KETERANGAN
        // =========================

        if (empty($keterangan)) {

            header(
                "Location: ../views/aspirasi_detail.php?id="
                . $id_aspirasi
            );

            exit;
        }


        // =========================
        // TENTUKAN STATUS BARU
        // =========================

        if ($tindakan == 'feedback') {

            /*
             * Feedback tidak mengubah status.
             *
             * Menunggu  → Menunggu
             * Dikerjakan → Dikerjakan
             */

            if (
                $status_sekarang != 'menunggu' &&
                $status_sekarang != 'dikerjakan'
            ) {

                header(
                    "Location: ../views/aspirasi_detail.php?id="
                    . $id_aspirasi
                );

                exit;
            }

            $status_baru = $status_sekarang;
        }


        elseif ($tindakan == 'mulai') {

            // Menunggu → Dikerjakan

            if ($status_sekarang != 'menunggu') {

                header(
                    "Location: ../views/aspirasi_detail.php?id="
                    . $id_aspirasi
                );

                exit;
            }

            $status_baru = 'dikerjakan';
        }


        elseif ($tindakan == 'selesai') {

            // Dikerjakan → Selesai

            if ($status_sekarang != 'dikerjakan') {

                header(
                    "Location: ../views/aspirasi_detail.php?id="
                    . $id_aspirasi
                );

                exit;
            }

            $status_baru = 'selesai';
        }


        elseif ($tindakan == 'tolak') {

            // Menunggu → Ditolak

            if ($status_sekarang != 'menunggu') {

                header(
                    "Location: ../views/aspirasi_detail.php?id="
                    . $id_aspirasi
                );

                exit;
            }

            $status_baru = 'ditolak';
        }


        // =========================
        // ID ADMIN
        // =========================

        if (!isset($_SESSION['id_admin'])) {

            die(
                "ID admin tidak ditemukan di session login."
            );
        }


        $id_admin = $_SESSION['id_admin'];


        // =========================
        // UPLOAD FOTO
        // =========================

        $foto = null;


        if (
            isset($_FILES['foto']) &&
            $_FILES['foto']['error'] == 0
        ) {


            $nama_file =
                $_FILES['foto']['name'];


            $tmp_file =
                $_FILES['foto']['tmp_name'];


            // =========================
            // CEK EKSTENSI
            // =========================

            $ext = strtolower(
                pathinfo(
                    $nama_file,
                    PATHINFO_EXTENSION
                )
            );


            $allowed = [
                'jpg',
                'jpeg',
                'png',
                'gif',
                'webp'
            ];


            if (
                !in_array(
                    $ext,
                    $allowed
                )
            ) {

                die(
                    "Format foto tidak diperbolehkan."
                );
            }


            // =========================
            // NAMA FOTO BARU
            // =========================

            $nama_baru =
                time()
                . "_"
                . uniqid()
                . "."
                . $ext;


            // =========================
            // FOLDER UPLOAD
            // =========================

            $folder = "../uploads/";


            if (!is_dir($folder)) {

                mkdir(
                    $folder,
                    0777,
                    true
                );
            }


            // =========================
            // PINDAHKAN FOTO
            // =========================

            if (
                move_uploaded_file(
                    $tmp_file,
                    $folder . $nama_baru
                )
            ) {

                $foto = $nama_baru;
            }
        }


        // =========================
        // SIMPAN PROGRES
        // =========================

        $hasil = $progres->tambah(
            $id_aspirasi,
            $id_admin,
            $status_baru,
            $keterangan,
            $foto
        );


        // =========================
        // BERHASIL
        // =========================

        if ($hasil) {

            header(
                "Location: ../views/aspirasi_detail.php?id="
                . $id_aspirasi
            );

            exit;
        }


        // =========================
        // GAGAL
        // =========================

        echo "Gagal menyimpan progres.";
        echo "<br>";
        echo mysqli_error(
            $progres->koneksi
        );
    }
}

?>