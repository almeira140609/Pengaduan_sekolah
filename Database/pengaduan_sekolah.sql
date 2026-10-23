-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 20, 2026 at 03:05 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pengaduan_sekolah`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_admin` int NOT NULL,
  `nama_admin` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_admin`, `nama_admin`, `username`, `password`) VALUES
(1, 'Admin Sekolah', 'admin', '12345'),
(2, 'Admin Sarana', 'admin.sarana', '12345'),
(3, 'Admin Kesiswaan', 'admin.kesiswaan', '12345'),
(4, 'Admin Tata Usaha', 'admin.tu', '12345'),
(5, 'Admin Sekolah 2', 'admin2', '12345');

-- --------------------------------------------------------

--
-- Table structure for table `aspirasi`
--

CREATE TABLE `aspirasi` (
  `id_aspirasi` int NOT NULL,
  `id_siswa` int NOT NULL,
  `id_kategori` int NOT NULL,
  `judul` varchar(150) NOT NULL,
  `isi_aspirasi` text NOT NULL,
  `lokasi` varchar(100) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `status` varchar(30) NOT NULL,
  `tanggal_aspirasi` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `aspirasi`
--

INSERT INTO `aspirasi` (`id_aspirasi`, `id_siswa`, `id_kategori`, `judul`, `isi_aspirasi`, `lokasi`, `foto`, `status`, `tanggal_aspirasi`) VALUES
(1, 1, 1, 'Lampu Ruang Kelas Mati', 'Lampu di ruang kelas XII RPL 1 mati sehingga ruangan menjadi kurang terang.', 'Ruang XII RPL 1', 'lampu_rusak.jpeg', 'Diajukan', '2026-08-25 08:00:00'),
(2, 2, 2, 'Keran Air Rusak', 'Keran air di toilet siswa mengalami kerusakan dan tidak dapat digunakan.', 'Toilet Siswa', 'keran_rusak.jpeg\r\n', 'Diproses', '2026-08-24 09:30:00'),
(3, 3, 3, 'Komputer Laboratorium Bermasalah', 'Beberapa komputer di laboratorium RPL tidak dapat digunakan.', 'Laboratorium RPL', 'komputer_rusak.jpeg', 'Diproses', '2026-08-23 10:00:00'),
(4, 4, 4, 'Kursi Perpustakaan Rusak', 'Terdapat beberapa kursi yang rusak di perpustakaan sekolah.', 'Perpustakaan', 'kursi_rusak.jpeg', 'Selesai', '2026-08-20 11:00:00'),
(5, 5, 5, 'Lapangan Sekolah Rusak', 'Beberapa bagian lapangan sekolah mengalami kerusakan dan perlu diperbaiki.', 'Lapangan Sekolah', 'lapang_rusak.jpeg', 'Diajukan', '2026-08-26 07:30:00'),
(6, 8, 1, 'Papan tulis rusak', 'papan tulis retak membuat pembelajaran sedikit terganggu', 'XI RPL 1', 'aspirasi_6ab47ad97e0e0.jpeg', 'Menunggu', '2026-09-24 08:20:25'),
(7, 8, 1, 'meja rusak', 'beberapa meja dikelas xi rpl 1 mengalami kerusakan', 'XI rpl 1', 'aspirasi_6ab5bb8dea6d6.jpeg', 'Menunggu', '2026-09-25 07:08:45'),
(8, 9, 3, 'iseng', 'usfuwefuw', 'lapangan', '', 'Menunggu', '2026-09-29 12:26:29'),
(9, 10, 1, 'kaca pecah ', 'ktjghfht', 'hihih', 'aspirasi_6abb50d26eee8.png', 'Menunggu', '2026-09-29 12:46:58'),
(10, 10, 1, 'Tembok Retak', 'Tembok di ruangan lab rpl mengalami keretakan di berbagai titik.', 'Diruangan Lab RPL', '', 'Menunggu', '2026-09-29 13:04:21');

-- --------------------------------------------------------

--
-- Table structure for table `histori`
--

CREATE TABLE `histori` (
  `id_histori` int NOT NULL,
  `id_aspirasi` int NOT NULL,
  `id_admin` int NOT NULL,
  `id_progres` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `histori`
--

INSERT INTO `histori` (`id_histori`, `id_aspirasi`, `id_admin`, `id_progres`) VALUES
(1, 1, 1, NULL),
(2, 2, 2, NULL),
(3, 3, 3, NULL),
(4, 4, 4, NULL),
(5, 5, 5, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int NOT NULL,
  `nama_kategori` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`) VALUES
(1, 'Fasilitas'),
(2, 'Kebersihan'),
(3, 'Keamanan'),
(4, 'Pembelajaran'),
(5, 'Lingkungan');

-- --------------------------------------------------------

--
-- Table structure for table `progres`
--

CREATE TABLE `progres` (
  `id_progres` int NOT NULL,
  `id_aspirasi` int NOT NULL,
  `id_admin` int NOT NULL,
  `status_progres` enum('menunggu','dikerjakan','selesai','ditolak') NOT NULL,
  `keterangan` text NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `tanggal_progres` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `progres`
--

INSERT INTO `progres` (`id_progres`, `id_aspirasi`, `id_admin`, `status_progres`, `keterangan`, `foto`, `tanggal_progres`) VALUES
(1, 1, 1, 'menunggu', 'Admin sedang melakukan pemeriksaan terhadap lampu ruang kelas.', NULL, '2026-08-25 14:00:00'),
(2, 2, 2, 'menunggu', 'Keran air sedang dilakukan pengecekan oleh pihak sekolah.', NULL, '2026-08-24 14:00:00'),
(3, 3, 3, 'dikerjakan', 'Komputer laboratorium sedang dalam proses perbaikan.', NULL, '2026-08-23 15:00:00'),
(4, 4, 4, 'selesai', 'Kursi perpustakaan telah selesai diperbaiki.', NULL, '2026-08-22 10:00:00'),
(5, 5, 5, 'menunggu', 'Kondisi lapangan sedang diperiksa oleh Admin.', NULL, '2026-08-26 11:00:00'),
(6, 3, 3, 'menunggu', 'Komputer laboratorium sedang dalam proses perbaikan.', NULL, '2026-09-23 08:59:53'),
(7, 6, 2, 'dikerjakan', 'sudah diperbaiki', 'progres_1790218005_6ab48f159f62f.jpeg', '2026-09-24 09:46:45'),
(8, 3, 1, 'dikerjakan', 'sudah selesai', NULL, '2026-09-25 07:04:07'),
(9, 3, 1, 'selesai', 'sudah selesai', NULL, '2026-09-25 07:04:30'),
(10, 2, 1, 'dikerjakan', 'sedang diperbaiki', 'progres_1790294737_6ab5bad13ad13.jpeg', '2026-09-25 07:05:37'),
(11, 2, 1, 'selesai', 'sudah selesai.', NULL, '2026-09-25 07:07:12'),
(12, 7, 1, 'dikerjakan', 'sedang diperbaiki', NULL, '2026-09-25 07:09:38'),
(13, 7, 1, 'selesai', 'sudah selesai', NULL, '2026-09-25 07:13:03'),
(14, 8, 1, 'ditolak', 'laporan tidak sesuai.', NULL, '2026-09-29 12:27:01'),
(15, 9, 1, 'ditolak', 'bukan urusan saya.', NULL, '2026-09-29 12:49:40'),
(16, 10, 1, 'dikerjakan', 'akan saya kerjakan', NULL, '2026-09-29 13:05:59'),
(17, 10, 1, 'selesai', 'selesai', NULL, '2026-09-29 13:06:53'),
(18, 6, 2, 'selesai', 'sudah selesai', NULL, '2026-10-18 11:22:16');

--
-- Triggers `progres`
--
DELIMITER $$
CREATE TRIGGER `cek_status_progres` BEFORE UPDATE ON `progres` FOR EACH ROW BEGIN
    IF OLD.status_progres = 'Menunggu'
       AND NEW.status_progres NOT IN ('Menunggu', 'Dikerjakan') THEN

        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Status Menunggu hanya dapat berubah menjadi Dikerjakan';

    ELSEIF OLD.status_progres = 'Dikerjakan'
       AND NEW.status_progres NOT IN ('Dikerjakan', 'Selesai') THEN

        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Status Dikerjakan hanya dapat berubah menjadi Selesai';

    ELSEIF OLD.status_progres = 'Selesai'
       AND NEW.status_progres <> 'Selesai' THEN

        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Status Selesai tidak dapat diubah kembali';

    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tambah_histori_progres` AFTER UPDATE ON `progres` FOR EACH ROW BEGIN
    IF OLD.status_progres <> NEW.status_progres THEN

        INSERT INTO histori
        (
            id_aspirasi,
            id_admin,
            id_progres
        )
        VALUES
        (
            NEW.id_aspirasi,
            NEW.id_admin,
            NEW.id_progres
        );

    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `id_siswa` int NOT NULL,
  `nis` varchar(20) NOT NULL,
  `nama_siswa` varchar(50) NOT NULL,
  `kelas` varchar(25) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`id_siswa`, `nis`, `nama_siswa`, `kelas`, `username`, `password`) VALUES
(1, '24001', 'Andi Saputra', 'XII RPL 1', 'andi', '123'),
(2, '24002', 'Rangga Septian', 'XII RPL 1', 'angga', '12345'),
(3, '24003', 'Citra Lestari', 'XII RPL 2', 'citra', '1234'),
(4, '24004', 'Dina Maharani', 'XII RPL 2', 'dina', '1267'),
(5, '24005', 'Rina Cahya', 'XII RPL 3', 'rina', '12354'),
(6, '24006', 'Zahra Alia', 'XII AKL 2', 'aliaa', '12345'),
(8, '24008', 'Januar Tri. N', 'XI PPLG 1', 'januar', '12345'),
(9, '24009', 'Sadam Maulana  ', 'XII MPLB 4', 'sadam', '12345'),
(10, '24010', 'Mario Chiko', 'XII RPL 2', 'mario', '12345'),
(11, '240123', 'Fayola Abigail .S', 'XII MPLB 2', 'fayola', '12345'),
(12, '240132', 'Fitri Febriani', 'XII RPL 2', 'fitri', '12345');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `aspirasi`
--
ALTER TABLE `aspirasi`
  ADD PRIMARY KEY (`id_aspirasi`),
  ADD KEY `id_siswa` (`id_siswa`),
  ADD KEY `id_kategori` (`id_kategori`);

--
-- Indexes for table `histori`
--
ALTER TABLE `histori`
  ADD PRIMARY KEY (`id_histori`),
  ADD KEY `id_aspirasi` (`id_aspirasi`),
  ADD KEY `id_admin` (`id_admin`),
  ADD KEY `fk_histori_progres` (`id_progres`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `progres`
--
ALTER TABLE `progres`
  ADD PRIMARY KEY (`id_progres`),
  ADD KEY `id_aspirasi` (`id_aspirasi`),
  ADD KEY `id_admin` (`id_admin`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id_siswa`),
  ADD UNIQUE KEY `nis` (`nis`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_admin` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `aspirasi`
--
ALTER TABLE `aspirasi`
  MODIFY `id_aspirasi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `histori`
--
ALTER TABLE `histori`
  MODIFY `id_histori` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `progres`
--
ALTER TABLE `progres`
  MODIFY `id_progres` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id_siswa` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `aspirasi`
--
ALTER TABLE `aspirasi`
  ADD CONSTRAINT `aspirasi_ibfk_1` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`id_siswa`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `aspirasi_ibfk_2` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `histori`
--
ALTER TABLE `histori`
  ADD CONSTRAINT `fk_histori_progres` FOREIGN KEY (`id_progres`) REFERENCES `progres` (`id_progres`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `histori_ibfk_1` FOREIGN KEY (`id_aspirasi`) REFERENCES `aspirasi` (`id_aspirasi`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `histori_ibfk_2` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `progres`
--
ALTER TABLE `progres`
  ADD CONSTRAINT `progres_ibfk_1` FOREIGN KEY (`id_aspirasi`) REFERENCES `aspirasi` (`id_aspirasi`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `progres_ibfk_2` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_admin`) ON DELETE RESTRICT ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
