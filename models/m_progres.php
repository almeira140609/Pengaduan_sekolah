<?php

require_once "m_koneksi.php";

class Progres extends Koneksi
{
    // =========================
    // NORMALISASI STATUS
    // =========================

    public static function normalisasi($status)
    {
        $status = strtolower(trim($status));

        $status_valid = [
            'menunggu',
            'dikerjakan',
            'selesai',
            'ditolak'
        ];

        if (!in_array($status, $status_valid)) {
            return 'menunggu';
        }

        return $status;
    }


    // =========================
    // TAMPIL SEMUA DATA PROGRES
    // =========================

    public function tampil()
    {
        $query = mysqli_query(
            $this->koneksi,
            "SELECT
                p.id_progres,
                p.id_aspirasi,
                p.id_admin,
                p.status_progres,
                p.keterangan,
                p.foto,
                p.tanggal_progres,

                a.judul,
                a.isi_aspirasi,
                a.lokasi,

                s.nama_siswa,
                s.nis,

                k.nama_kategori,

                ad.nama_admin

            FROM progres p

            LEFT JOIN aspirasi a
                ON p.id_aspirasi = a.id_aspirasi

            LEFT JOIN siswa s
                ON a.id_siswa = s.id_siswa

            LEFT JOIN kategori k
                ON a.id_kategori = k.id_kategori

            LEFT JOIN admin ad
                ON p.id_admin = ad.id_admin

            ORDER BY p.id_progres DESC"
        );

        return $query;
    }


    // =========================
    // AMBIL DATA BERDASARKAN ID
    // =========================

    public function ambilById($id_progres)
    {
        $id_progres = mysqli_real_escape_string(
            $this->koneksi,
            $id_progres
        );

        $query = mysqli_query(
            $this->koneksi,
            "SELECT *
             FROM progres
             WHERE id_progres = '$id_progres'"
        );

        return mysqli_fetch_assoc($query);
    }


    // =========================
    // AMBIL PROGRES TERAKHIR
    // BERDASARKAN ID ASPIRASI
    // =========================

    public function ambilTerakhir($id_aspirasi)
    {
        $id_aspirasi = mysqli_real_escape_string(
            $this->koneksi,
            $id_aspirasi
        );

        $query = mysqli_query(
            $this->koneksi,
            "SELECT
                p.*,
                ad.nama_admin
             FROM progres p

             LEFT JOIN admin ad
                ON p.id_admin = ad.id_admin

             WHERE p.id_aspirasi = '$id_aspirasi'

             ORDER BY p.id_progres DESC

             LIMIT 1"
        );

        if ($query && mysqli_num_rows($query) > 0) {
            return mysqli_fetch_assoc($query);
        }

        return null;
    }


    // =========================
    // AMBIL RIWAYAT PROGRES
    // =========================

    public function ambilRiwayat($id_aspirasi)
    {
        $id_aspirasi = mysqli_real_escape_string(
            $this->koneksi,
            $id_aspirasi
        );

        $query = mysqli_query(
            $this->koneksi,
            "SELECT
                p.id_progres,
                p.id_aspirasi,
                p.id_admin,
                p.status_progres,
                p.keterangan,
                p.foto,
                p.tanggal_progres,
                ad.nama_admin

            FROM progres p

            LEFT JOIN admin ad
                ON p.id_admin = ad.id_admin

            WHERE p.id_aspirasi = '$id_aspirasi'

            ORDER BY p.id_progres ASC"
        );

        return $query;
    }


    // =========================
    // TAMBAH PROGRES
    // =========================

    public function tambah(
        $id_aspirasi,
        $id_admin,
        $status_progres,
        $keterangan,
        $foto = null
    ) {
        $id_aspirasi = mysqli_real_escape_string(
            $this->koneksi,
            $id_aspirasi
        );

        $id_admin = mysqli_real_escape_string(
            $this->koneksi,
            $id_admin
        );

        $status_progres = self::normalisasi(
            $status_progres
        );

        $status_progres = mysqli_real_escape_string(
            $this->koneksi,
            $status_progres
        );

        $keterangan = mysqli_real_escape_string(
            $this->koneksi,
            $keterangan
        );

        $foto = $foto !== null
            ? mysqli_real_escape_string(
                $this->koneksi,
                $foto
            )
            : null;


        // =========================
        // TANPA FOTO
        // =========================

        if ($foto === null || $foto === '') {

            return mysqli_query(
                $this->koneksi,
                "INSERT INTO progres
                (
                    id_aspirasi,
                    id_admin,
                    status_progres,
                    keterangan,
                    foto,
                    tanggal_progres
                )
                VALUES
                (
                    '$id_aspirasi',
                    '$id_admin',
                    '$status_progres',
                    '$keterangan',
                    NULL,
                    NOW()
                )"
            );
        }


        // =========================
        // DENGAN FOTO
        // =========================

        return mysqli_query(
            $this->koneksi,
            "INSERT INTO progres
            (
                id_aspirasi,
                id_admin,
                status_progres,
                keterangan,
                foto,
                tanggal_progres
            )
            VALUES
            (
                '$id_aspirasi',
                '$id_admin',
                '$status_progres',
                '$keterangan',
                '$foto',
                NOW()
            )"
        );
    }


    // =========================
    // EDIT PROGRES
    // =========================

    public function edit(
        $id_progres,
        $status_progres,
        $keterangan,
        $foto = null
    ) {
        $id_progres = mysqli_real_escape_string(
            $this->koneksi,
            $id_progres
        );

        $status_progres = self::normalisasi(
            $status_progres
        );

        $status_progres = mysqli_real_escape_string(
            $this->koneksi,
            $status_progres
        );

        $keterangan = mysqli_real_escape_string(
            $this->koneksi,
            $keterangan
        );


        // =========================
        // FOTO TIDAK DIUBAH
        // =========================

        if ($foto === null || $foto === '') {

            return mysqli_query(
                $this->koneksi,
                "UPDATE progres SET

                    status_progres = '$status_progres',
                    keterangan = '$keterangan',
                    tanggal_progres = NOW()

                 WHERE id_progres = '$id_progres'"
            );
        }


        // =========================
        // FOTO DIUBAH
        // =========================

        $foto = mysqli_real_escape_string(
            $this->koneksi,
            $foto
        );

        return mysqli_query(
            $this->koneksi,
            "UPDATE progres SET

                status_progres = '$status_progres',
                keterangan = '$keterangan',
                foto = '$foto',
                tanggal_progres = NOW()

             WHERE id_progres = '$id_progres'"
        );
    }
}
?>