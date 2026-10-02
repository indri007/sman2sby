-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 19, 2026 at 06:59 AM
-- Server version: 11.8.8-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u401911348_absen1`
--

-- --------------------------------------------------------

--
-- Table structure for table `honor`
--

CREATE TABLE `honor` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tujuan` varchar(255) DEFAULT NULL,
  `alasan` text DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `transportasi` varchar(255) DEFAULT NULL,
  `biaya_perjalanan` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `honor_pegawai`
--

CREATE TABLE `honor_pegawai` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_honor` bigint(20) UNSIGNED NOT NULL,
  `id_pegawai` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jabatan`
--

CREATE TABLE `jabatan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `golongan` varchar(255) DEFAULT NULL,
  `gaji_pokok` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `jabatan`
--

INSERT INTO `jabatan` (`id`, `nama`, `golongan`, `gaji_pokok`) VALUES
(1, 'Siswa', '1', 0);

-- --------------------------------------------------------

--
-- Table structure for table `pegawai`
--

CREATE TABLE `pegawai` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_jabatan` bigint(20) UNSIGNED NOT NULL,
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `nip` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `nomor_telepon` varchar(255) NOT NULL,
  `tmt` date NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `tempat_lahir` varchar(255) NOT NULL,
  `gambar` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `pegawai`
--

INSERT INTO `pegawai` (`id`, `id_jabatan`, `id_user`, `nip`, `nama`, `nomor_telepon`, `tmt`, `tanggal_lahir`, `tempat_lahir`, `gambar`) VALUES
(1, 1, 6, '0103128173', 'ADINDA RAFEYLA ARIBOWO', '0', '2026-08-17', '2026-08-17', 'Surabaya', 'uploads/20260817222408.'),
(2, 1, 7, '0103364648', 'AIDAH SYAHLA HAFIZHAH', '0', '2026-08-17', '2026-08-17', 'Surabaya', 'uploads/20260817222726.'),
(3, 1, 8, '0108020213', 'AIRANISSA RAYSA RAHMAN', '0', '2026-08-17', '2026-08-17', 'Surabaya', 'uploads/20260817222830.'),
(4, 1, 9, '0112322335', 'AIRLANGGA POETRA ALFATTAH SYAH', '0', '2026-08-17', '2026-08-17', 'Surabaya', 'uploads/20260817222912.'),
(5, 1, 10, '0108214198', 'ALIYAH ERIN SUTRISNO', '0', '2026-08-17', '2026-08-17', 'Surabaya', 'uploads/20260817222941.'),
(6, 1, 11, '0114738767', 'ALVARO VALENTXIO ALY ANANTHA', '0', '2026-08-17', '2026-08-17', 'Surabaya', 'uploads/20260817223016.'),
(7, 1, 12, '0109091858', 'AQEELA NUR SILAVANY', '0', '2026-08-17', '2026-08-17', 'Surabaya', 'uploads/20260817223047.'),
(8, 1, 13, '0083178495', 'AQMAL WILDANMIR', '0', '2026-08-17', '2026-08-17', 'Surabaya', 'uploads/20260817223128.'),
(9, 1, 14, '0101018430', 'ARSA BUDI SATRIA', '0', '2026-08-17', '2026-08-17', 'Surabaya', 'uploads/20260817223155.'),
(10, 1, 15, '0104434293', 'AZZAM FADHIL SUSILO', '0', '2026-08-17', '2026-08-17', 'Surabaya', 'uploads/20260817223226.'),
(11, 1, 16, '0107108286', 'BAGASKARA FARROS LUDYANSYAH', '0', '2026-08-17', '2026-08-17', 'Surabaya', 'uploads/20260817223300.'),
(12, 1, 17, '0126172624', 'BENING KALINDA SATRIYO', '0', '2026-08-17', '2026-08-17', 'Surabaya', 'uploads/20260817223421.'),
(13, 1, 18, '0112810604', 'DAFFA HAIDAR RIFQI PUTRA', '0', '2026-08-17', '2026-08-17', 'Surabaya', 'uploads/20260817223450.'),
(14, 1, 19, '0129671037', 'FELICIA JIAN PARAMESTI', '0', '2026-08-17', '2026-08-17', 'Surabaya', 'uploads/20260817223515.'),
(15, 1, 20, '0103264785', 'GAVIN RAJATA HATAMI', '0', '2026-08-17', '2026-08-17', 'Surabaya', 'uploads/20260817223547.'),
(16, 1, 21, '0117815022', 'JIM JAFIN', '0', '2026-08-17', '2026-08-17', 'Surabaya', 'uploads/20260817223625.'),
(17, 1, 22, '0113867582', 'JOVAN ATHALLAH DIMITRI', '0', '2026-08-17', '2026-08-17', 'Surabaya', 'uploads/20260817223651.'),
(18, 1, 23, '0107787521', 'KAMILA FITRIA RAMADHANI', '0', '2026-08-17', '2026-08-17', 'Surabaya', 'uploads/20260817223724.'),
(19, 1, 24, '0111595765', 'KENZIE ADLI TSAQIB', '0', '2026-08-17', '2026-08-17', 'Surabaya', 'uploads/20260817223838.'),
(20, 1, 25, '0104790544', 'KENZIE JAVAS CLEMENTINO RIZAL', '0', '2026-08-17', '2026-08-17', 'Surabaya', 'uploads/20260817223906.'),
(21, 1, 26, '0107233420', 'MEHRUNNISA DHIA MALICA', '0', '2026-08-17', '2026-08-17', 'Surabaya', 'uploads/20260817223934.'),
(22, 1, 27, '1011885778', 'MOCHAMMAT IQBAL IBRAHIM SAPUTRA', '0', '2026-08-17', '2026-08-17', 'Surabaya', 'uploads/20260817224001.'),
(23, 1, 28, '0119527172', 'MUHAMMAD', '0', '2026-08-17', '2026-08-17', 'Surabaya', 'uploads/20260817224101.'),
(24, 1, 29, '0107103088', 'MUHAMMAD RIZIQ ARRAFAT', '0', '2026-08-17', '2026-08-17', 'Surabaya', 'uploads/20260817224226.'),
(25, 1, 30, '0111809897', 'MUTIARA CARISSA PUTRI', '0', '2026-08-17', '2026-08-17', 'Surabaya', 'uploads/20260817224254.'),
(26, 1, 31, '0119601956', 'NADHIFA AZKIYA HURIN', '0', '2026-08-17', '2026-08-17', 'Surabaya', 'uploads/20260817224320.'),
(27, 1, 32, '0117956929', 'NADIA MAHESWARI ANINDYA RACHMAWATI', '0', '2026-08-17', '2026-08-17', 'Surabaya', 'uploads/20260817224612.'),
(28, 1, 33, '0105971823', 'NADIA RAHMA PUTRI', '0', '2026-08-17', '2026-08-17', 'Surabaya', 'uploads/20260817224642.'),
(29, 1, 34, '0117988711', 'NADYA KHARISA RAMADHANI', '0', '2026-08-17', '2026-08-17', 'Surabaya', 'uploads/20260817224714.'),
(30, 1, 35, '0108739943', 'NARENDRA MAULANA AKBAR', '0', '2026-08-17', '2026-08-17', 'Surabaya', 'uploads/20260817224746.'),
(31, 1, 36, '0103880660', 'OKTAFIONA SAFIRA PUTRI GUNAWAN', '0', '2026-08-17', '2026-08-17', 'Surabaya', 'uploads/20260817224816.'),
(32, 1, 37, '0104370640', 'RANIA MAULIDIA FITRI', '0', '2026-08-17', '2026-08-17', 'Surabaya', 'uploads/20260817224954.'),
(33, 1, 38, '0103337613', 'ROBBYATUL FARICHAH', '0', '2026-08-17', '2026-08-17', 'Surabaya', 'uploads/20260817225021.'),
(34, 1, 39, '0103546403', 'SALMA HAYA INAS', '0', '2026-08-17', '2026-08-17', 'Surabaya', 'uploads/20260817225052.'),
(35, 1, 40, '0108645866', 'SYAFIRA TUSSONIA', '0', '2026-08-17', '2026-08-17', 'Surabaya', 'uploads/20260817225126.'),
(36, 1, 41, '3109560686', 'SYARIFAH NAURAH RIZKA AULIYA ALHINDUAN', '0', '2026-08-17', '2026-08-17', 'Surabaya', 'uploads/20260817225153.');

-- --------------------------------------------------------

--
-- Table structure for table `presensi_pegawai`
--

CREATE TABLE `presensi_pegawai` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_pegawai` bigint(20) UNSIGNED NOT NULL,
  `tanggal_waktu` datetime DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `jenis` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tunjangan`
--

CREATE TABLE `tunjangan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `tunjangan` bigint(20) UNSIGNED DEFAULT NULL,
  `jenis_pemberian` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tunjangan_pegawai`
--

CREATE TABLE `tunjangan_pegawai` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_pegawai` bigint(20) UNSIGNED NOT NULL,
  `id_tunjangan` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `status`) VALUES
(1, 'admin', 'admin', 'ADMIN'),
(2, '197707132000121001', '197707132000121001', 'PEGAWAI'),
(3, '196801101997031003', '196801101997031003', 'PEGAWAI'),
(4, '196605161988032001', '196605161988032001', 'PEGAWAI'),
(5, '197004052005011006', '197004052005011006', 'PEGAWAI'),
(6, '0103128173', '0103128173', 'PEGAWAI'),
(7, '0103364648', '0103364648', 'PEGAWAI'),
(8, '0108020213', '0108020213', 'PEGAWAI'),
(9, '0112322335', '0112322335', 'PEGAWAI'),
(10, '0108214198', '0108214198', 'PEGAWAI'),
(11, '0114738767', '0114738767', 'PEGAWAI'),
(12, '0109091858', '0109091858', 'PEGAWAI'),
(13, '0083178495', '0083178495', 'PEGAWAI'),
(14, '0101018430', '0101018430', 'PEGAWAI'),
(15, '0104434293', '0104434293', 'PEGAWAI'),
(16, '0107108286', '0107108286', 'PEGAWAI'),
(17, '0126172624', '0126172624', 'PEGAWAI'),
(18, '0112810604', '0112810604', 'PEGAWAI'),
(19, '0129671037', '0129671037', 'PEGAWAI'),
(20, '0103264785', '0103264785', 'PEGAWAI'),
(21, '0117815022', '0117815022', 'PEGAWAI'),
(22, '0113867582', '0113867582', 'PEGAWAI'),
(23, '0107787521', '0107787521', 'PEGAWAI'),
(24, '0111595765', '0111595765', 'PEGAWAI'),
(25, '0104790544', '0104790544', 'PEGAWAI'),
(26, '0107233420', '0107233420', 'PEGAWAI'),
(27, '1011885778', '1011885778', 'PEGAWAI'),
(28, '0119527172', '0119527172', 'PEGAWAI'),
(29, '0107103088', '0107103088', 'PEGAWAI'),
(30, '0111809897', '0111809897', 'PEGAWAI'),
(31, '0119601956', '0119601956', 'PEGAWAI'),
(32, '0117956929', '0117956929', 'PEGAWAI'),
(33, '0105971823', '0105971823', 'PEGAWAI'),
(34, '0117988711', '0117988711', 'PEGAWAI'),
(35, '0108739943', '0108739943', 'PEGAWAI'),
(36, '0103880660', '0103880660', 'PEGAWAI'),
(37, '0104370640', '0104370640', 'PEGAWAI'),
(38, '0103337613', '0103337613', 'PEGAWAI'),
(39, '0103546403', '0103546403', 'PEGAWAI'),
(40, '0108645866', '0108645866', 'PEGAWAI'),
(41, '3109560686', '3109560686', 'PEGAWAI');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `honor`
--
ALTER TABLE `honor`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `honor_pegawai`
--
ALTER TABLE `honor_pegawai`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_honor` (`id_honor`),
  ADD KEY `id_pegawai` (`id_pegawai`);

--
-- Indexes for table `jabatan`
--
ALTER TABLE `jabatan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pegawai`
--
ALTER TABLE `pegawai`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_jabatan` (`id_jabatan`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `presensi_pegawai`
--
ALTER TABLE `presensi_pegawai`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pegawai` (`id_pegawai`);

--
-- Indexes for table `tunjangan`
--
ALTER TABLE `tunjangan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nama` (`nama`);

--
-- Indexes for table `tunjangan_pegawai`
--
ALTER TABLE `tunjangan_pegawai`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pegawai` (`id_pegawai`),
  ADD KEY `id_tunjangan` (`id_tunjangan`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `honor`
--
ALTER TABLE `honor`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `honor_pegawai`
--
ALTER TABLE `honor_pegawai`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jabatan`
--
ALTER TABLE `jabatan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pegawai`
--
ALTER TABLE `pegawai`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `presensi_pegawai`
--
ALTER TABLE `presensi_pegawai`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tunjangan`
--
ALTER TABLE `tunjangan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tunjangan_pegawai`
--
ALTER TABLE `tunjangan_pegawai`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `honor_pegawai`
--
ALTER TABLE `honor_pegawai`
  ADD CONSTRAINT `honor_pegawai_ibfk_1` FOREIGN KEY (`id_honor`) REFERENCES `honor` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `honor_pegawai_ibfk_2` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pegawai`
--
ALTER TABLE `pegawai`
  ADD CONSTRAINT `pegawai_ibfk_1` FOREIGN KEY (`id_jabatan`) REFERENCES `jabatan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pegawai_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `presensi_pegawai`
--
ALTER TABLE `presensi_pegawai`
  ADD CONSTRAINT `presensi_pegawai_ibfk_1` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tunjangan_pegawai`
--
ALTER TABLE `tunjangan_pegawai`
  ADD CONSTRAINT `tunjangan_pegawai_ibfk_1` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tunjangan_pegawai_ibfk_2` FOREIGN KEY (`id_tunjangan`) REFERENCES `tunjangan` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
