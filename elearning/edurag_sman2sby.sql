-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 12, 2026 at 04:25 PM
-- Server version: 11.8.9-MariaDB-log
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
  `jenis` varchar(255) DEFAULT NULL,
  `foto_path` varchar(255) DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `jarak_meter` decimal(8,2) DEFAULT NULL,
  `surat_dokter` varchar(255) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `device_info` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `presensi_pegawai`
--

INSERT INTO `presensi_pegawai` (`id`, `id_pegawai`, `tanggal_waktu`, `status`, `jenis`, `foto_path`, `latitude`, `longitude`, `jarak_meter`, `surat_dokter`, `keterangan`, `ip_address`, `device_info`) VALUES
(10, 1, '2026-08-19 15:44:12', 'Hadir', 'Pulang', 'uploads/presensi/foto_20260819154412_1.png', -6.5652335, 107.8861317, 18.22, NULL, '', NULL, NULL),
(11, 5, '2026-08-19 15:52:50', 'Izin', '-', NULL, -7.3300979, 112.7827820, 8017.06, 'uploads/presensi/surat_20260819155250_5.pdf', 'izn', NULL, NULL),
(12, 5, '2026-08-19 16:02:06', 'Izin', '-', NULL, -7.3300917, 112.7827814, 8016.41, 'uploads/presensi/surat_20260819160206_5.pdf', '', NULL, NULL),
(13, 16, '2026-08-20 13:45:47', 'Izin', '-', NULL, 0.0000000, 0.0000000, 999999.99, '', 'Sakit', NULL, NULL),
(15, 36, '2026-08-22 18:38:29', 'Izin', '-', NULL, -6.5470152, 107.7395935, 558866.68, '', 'sakit ', '187.40.232.119', 'Google Chrome 151 on macOS'),
(16, 4, '2026-08-23 15:46:44', 'Izin', '-', NULL, 0.0000000, 0.0000000, 999999.99, 'uploads/presensi/surat_20260823154644_4.png', '', '182.8.97.184', 'Google Chrome 151 on macOS'),
(17, 16, '2026-08-23 18:21:08', 'Izin', '-', NULL, -7.3363087, 112.7851613, 8752.54, '', 'Blbalbalaba', '2a04:4e41:76cd:7b42::9e4d:7b42', 'Safari on iPhone (iOS)'),
(18, 16, '2026-08-23 18:21:24', 'Sakit', '-', NULL, -7.3363087, 112.7851613, 8752.54, '', 'Jsjsjsjsja', '2a04:4e41:76cd:7b42::9e4d:7b42', 'Safari on iPhone (iOS)'),
(19, 10, '2026-08-24 07:46:20', 'Izin', '-', NULL, -7.2565395, 112.7497108, 1005.15, '', 'Sakit demam', '114.5.247.88', 'Google Chrome 150 on Android 10'),
(20, 11, '2026-08-24 07:49:49', 'Sakit', '-', NULL, -7.2565329, 112.7497020, 1005.96, '', 'sakit panas', '2400:9800:820:448e:72e:e762:7afa:244b', 'Google Chrome 151 on Android 10'),
(21, 4, '2026-08-24 07:51:43', 'Izin', '-', NULL, -7.2565393, 112.7497108, 1005.18, '', 'Saya udah di sekolah', '2400:9800:821:56f3:bd0b:865c:8651:a41d', 'Google Chrome 151 on Android 10'),
(22, 17, '2026-08-24 09:37:10', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260824093710_17.png', -7.2565459, 112.7497006, 1.34, NULL, '', '2404:c0:3568:7afb:1877:d2ff:fefe:9823', 'Google Chrome 151 on Android 10'),
(23, 21, '2026-08-24 09:58:02', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260824095802_21.png', -7.2565394, 112.7497107, 0.02, NULL, '', '2400:9800:702:e61f:e2f:717:955b:6d55', 'Google Chrome 151 on Android 10'),
(24, 26, '2026-08-24 09:59:05', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260824095905_26.png', -7.2565395, 112.7497109, 0.00, NULL, '', '114.79.21.39', 'Google Chrome 151 on Android 10'),
(25, 29, '2026-08-24 09:59:07', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260824095907_29.png', -7.2565219, 112.7496968, 2.50, NULL, '', '114.79.20.50', 'Google Chrome 151 on Android 10'),
(26, 29, '2026-08-24 10:00:14', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260824100014_29.png', -7.2565214, 112.7496938, 2.76, NULL, '', '114.79.20.50', 'Google Chrome 151 on Android 10'),
(27, 29, '2026-08-24 10:01:10', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260824100110_29.jpg', -7.2565118, 112.7496892, 3.90, NULL, '', '114.79.17.54', 'Google Chrome 151 on Android 10'),
(28, 29, '2026-08-24 10:01:44', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260824100144_29.png', -7.2565293, 112.7496985, 1.78, NULL, '', '114.79.17.54', 'Google Chrome 151 on Android 10'),
(29, 25, '2026-08-24 10:01:48', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260824100148_25.png', -7.2564275, 112.7501755, 52.74, NULL, '', '2404:c0:3178:239a:34a9:88e7:e98c:e311', 'Safari on iPhone (iOS)'),
(30, 17, '2026-08-24 10:09:21', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260824100921_17.png', -7.2565377, 112.7497112, 0.20, NULL, '', '2404:c0:3566:7e3e:2c4a:9dff:fe75:40e1', 'Google Chrome 151 on Android 10'),
(31, 14, '2026-08-24 10:15:07', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260824101507_14.jpg', -7.2565379, 112.7497108, 0.18, NULL, '', '114.5.103.201', 'Google Chrome 151 on Android 10'),
(32, 33, '2026-08-24 10:51:24', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260824105124_33.png', -7.2562521, 112.7495606, 36.00, NULL, '', '182.6.80.215', 'Google Chrome 151 on Android 10'),
(33, 33, '2026-08-24 10:51:38', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260824105138_33.png', -7.2566771, 112.7495101, 26.92, NULL, '', '182.6.80.215', 'Google Chrome 151 on Android 10'),
(34, 32, '2026-08-24 11:07:14', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260824110714_32.png', -7.2565313, 112.7497136, 0.96, NULL, '', '114.5.222.242', 'Google Chrome 150 on Android 10'),
(35, 14, '2026-08-24 13:54:15', 'Hadir', 'Pulang', 'uploads/presensi/foto_20260824135415_14.jpg', -7.2582448, 112.7501077, 194.61, NULL, '', '114.5.103.201', 'Google Chrome 151 on Android 10'),
(36, 26, '2026-08-24 13:54:44', 'Hadir', 'Pulang', 'uploads/presensi/foto_20260824135444_26.jpg', -7.2582480, 112.7502553, 199.24, NULL, '', '114.79.21.39', 'Google Chrome 151 on Android 10'),
(37, 17, '2026-08-24 15:11:53', 'Hadir', 'Pulang', 'uploads/presensi/foto_20260824151153_17.png', -7.3300234, 112.7827431, 8946.48, NULL, '', '182.8.97.184', 'Google Chrome 151 on Android 10'),
(38, 7, '2026-08-26 06:40:05', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260826064005_7.jpg', -7.2572219, 112.7493654, 84.91, NULL, '', '2404:c0:3561:e905:ece2:c2ff:fead:52a1', 'Google Chrome 149 on Android 10'),
(39, 14, '2026-08-26 06:43:58', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260826064358_14.jpg', -7.2563402, 112.7497306, 22.27, NULL, '', '114.5.110.151', 'Google Chrome 151 on Android 10'),
(40, 35, '2026-08-26 06:43:59', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260826064359_35.jpg', -7.2563378, 112.7497167, 22.44, NULL, '', '2400:9800:9b1:492d:9f5c:cb95:f685:7cd3', 'Samsung Browser on Android 10'),
(41, 13, '2026-08-26 06:44:28', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260826064428_13.jpg', -7.2564051, 112.7497201, 14.98, NULL, '', '2400:9800:9b1:7a7a:c19:83aa:4f3a:55e1', 'Google Chrome 150 on Android 10'),
(42, 35, '2026-08-26 06:44:30', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260826064430_35.jpg', -7.2563378, 112.7497167, 22.44, NULL, '', '2400:9800:9b1:492d:9f5c:cb95:f685:7cd3', 'Samsung Browser on Android 10'),
(43, 29, '2026-08-26 06:44:37', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260826064437_29.png', -7.2563488, 112.7497292, 21.30, NULL, '', '114.79.23.246', 'Google Chrome 151 on Android 10'),
(44, 10, '2026-08-26 06:44:41', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260826064441_10.png', -7.2562652, 112.7498154, 32.61, NULL, '', '114.5.241.141', 'Google Chrome 150 on Android 10'),
(45, 25, '2026-08-26 06:44:42', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260826064442_25.png', -7.2563426, 112.7498515, 26.84, NULL, '', '2404:c0:3178:239a:fcdf:25c1:3445:4cf6', 'Safari on iPhone (iOS)'),
(46, 10, '2026-08-26 06:44:43', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260826064443_10.png', -7.2562652, 112.7498154, 32.61, NULL, '', '114.5.241.141', 'Google Chrome 150 on Android 10'),
(47, 12, '2026-08-26 06:44:48', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260826064448_12.png', -7.2563401, 112.7497902, 23.83, NULL, '', '182.5.200.165', 'Safari on iPhone (iOS)'),
(48, 29, '2026-08-26 06:45:07', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260826064507_29.png', -7.2563373, 112.7497089, 22.48, NULL, '', '114.79.23.246', 'Google Chrome 151 on Android 10'),
(49, 18, '2026-08-26 06:45:12', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260826064512_18.jpg', -7.2563439, 112.7497243, 21.80, NULL, '', '2404:c0:3568:6c1b:18cf:2971:b98d:8ed4', 'Google Chrome 151 on Android 10'),
(50, 33, '2026-08-26 06:45:16', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260826064516_33.png', -7.2563419, 112.7497135, 21.97, NULL, '', '182.5.247.116', 'Google Chrome 151 on Android 10'),
(51, 11, '2026-08-26 06:45:21', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260826064521_11.png', -7.2563609, 112.7497165, 19.87, NULL, '', '2400:9800:761:ffaa:f71f:9d4a:ce7b:548f', 'Google Chrome 151 on Android 10'),
(52, 29, '2026-08-26 06:45:32', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260826064532_29.png', -7.2563593, 112.7497204, 20.06, NULL, '', '114.79.23.246', 'Google Chrome 151 on Android 10'),
(53, 29, '2026-08-26 06:45:37', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260826064537_29.png', -7.2563593, 112.7497204, 20.06, NULL, '', '114.79.23.246', 'Google Chrome 151 on Android 10'),
(54, 6, '2026-08-26 06:45:50', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260826064550_6.png', -7.2563888, 112.7497064, 16.76, NULL, '', '114.5.241.141', 'Google Chrome 150 on Android 10'),
(55, 36, '2026-08-26 06:45:56', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260826064556_36.png', -7.2563147, 112.7496959, 25.05, NULL, '', '140.213.59.108', 'Google Chrome 151 on Android 10'),
(56, 21, '2026-08-26 06:46:00', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260826064600_21.png', -7.2564683, 112.7497384, 8.48, NULL, '', '2400:9800:702:c1b7:74a6:171c:b1b2:b3cc', 'Google Chrome 151 on Android 10'),
(57, 6, '2026-08-26 06:46:10', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260826064610_6.png', -7.2564005, 112.7497063, 15.46, NULL, '', '114.5.241.141', 'Google Chrome 150 on Android 10'),
(58, 15, '2026-08-26 06:46:17', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260826064617_15.png', -7.2563469, 112.7497348, 21.58, NULL, '', '2404:c0:3079:725d:18cf:2a9a:9691:3e24', 'Google Chrome 151 on Android 10'),
(59, 20, '2026-08-26 06:46:39', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260826064639_20.jpg', -7.2563428, 112.7497351, 22.03, NULL, '', '2404:c0:356f:6e08:2c78:57ce:f7c5:8deb', 'Samsung Browser on Android 10'),
(60, 26, '2026-08-26 06:47:15', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260826064715_26.jpg', -7.2563454, 112.7496849, 21.77, NULL, '', '114.79.23.113', 'Google Chrome 151 on Android 10'),
(61, 5, '2026-08-26 06:48:42', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260826064842_5.png', -7.2563797, 112.7497346, 17.96, NULL, '', '114.79.23.246', 'Google Chrome 151 on Android 10'),
(62, 32, '2026-08-26 07:44:20', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260826074420_32.png', -7.2563812, 112.7496249, 20.00, NULL, '', '114.8.222.201', 'Google Chrome 150 on Android 10'),
(63, 34, '2026-08-26 08:18:26', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260826081826_34.png', -7.2563413, 112.7497183, 22.05, NULL, '', '114.79.23.53', 'Google Chrome 151 on Android 10'),
(64, 17, '2026-08-26 08:30:35', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260826083035_17.png', -7.2578795, 112.7500345, 153.22, NULL, '', '2404:c0:3178:1606:98ed:93ff:fe05:a09c', 'Google Chrome 151 on Android 10'),
(65, 21, '2026-08-26 15:57:49', 'Hadir', 'Pulang', 'uploads/presensi/foto_20260826155749_21.png', -7.2574097, 112.7492770, 107.95, NULL, '', '2400:9800:703:cee:a3a1:cd70:b6ac:2d43', 'Google Chrome 151 on Android 10'),
(66, 18, '2026-08-27 06:31:28', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260827063128_18.jpg', -7.2563476, 112.7497350, 21.50, NULL, '', '2404:c0:3562:f557:18cf:797b:c303:1a10', 'Google Chrome 151 on Android 10'),
(67, 26, '2026-08-27 06:33:22', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260827063322_26.jpg', -7.2563486, 112.7496652, 21.82, NULL, '', '114.79.23.231', 'Google Chrome 151 on Android 10'),
(68, 7, '2026-08-27 06:36:05', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260827063605_7.jpg', -7.2571329, 112.7494928, 70.23, NULL, '', '2404:c0:3562:1470:e875:13ff:fe05:9f10', 'Google Chrome 149 on Android 10'),
(69, 11, '2026-08-27 06:37:22', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260827063722_11.png', -7.2564802, 112.7493163, 44.02, NULL, '', '2400:9800:9b2:71f1:ce04:28a:32b3:5c81', 'Google Chrome 151 on Android 10'),
(70, 32, '2026-08-27 07:10:47', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260827071047_32.png', -7.2563677, 112.7497346, 19.28, NULL, '', '114.5.105.21', 'Google Chrome 150 on Android 10'),
(71, 12, '2026-08-27 07:11:07', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260827071107_12.png', -7.2563542, 112.7498173, 23.71, NULL, '', '182.5.240.22', 'Safari on iPhone (iOS)'),
(72, 29, '2026-08-27 07:11:16', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260827071116_29.png', -7.2563381, 112.7497169, 22.40, NULL, '', '114.79.19.150', 'Google Chrome 151 on Android 10'),
(73, 19, '2026-08-27 07:11:49', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260827071149_19.png', -7.2563297, 112.7498025, 25.42, NULL, '', '114.8.225.149', 'Google Chrome 152 on Android 10'),
(74, 23, '2026-08-27 07:11:59', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260827071159_23.png', -7.2563370, 112.7497236, 22.56, NULL, '', '2402:5680:88d3:6423::1', 'Samsung Browser on Android 10'),
(75, 5, '2026-08-27 07:12:07', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260827071207_5.png', -7.2563345, 112.7497346, 22.94, NULL, '', '2404:c0:3573:2a64:7c8e:5fff:feb2:10e1', 'Google Chrome 152 on Android 10'),
(76, 13, '2026-08-27 07:12:11', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260827071211_13.png', -7.2569164, 112.7504069, 87.47, NULL, '', '2400:9800:9b2:1349:6815:c776:2eec:be19', 'Google Chrome 150 on Android 10'),
(77, 3, '2026-08-27 07:12:48', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260827071248_3.png', -7.2563783, 112.7497576, 18.65, NULL, '', '2404:c0:3079:28c1:bd94:d35c:c41c:e370', 'Safari on iPhone (iOS)'),
(78, 9, '2026-08-27 07:12:49', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260827071249_9.png', -7.2563378, 112.7497132, 22.43, NULL, '', '114.8.220.153', 'Google Chrome 151 on Android 10'),
(79, 9, '2026-08-27 07:12:54', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260827071254_9.png', -7.2563378, 112.7497132, 22.43, NULL, '', '114.8.220.153', 'Google Chrome 151 on Android 10'),
(80, 9, '2026-08-27 07:13:09', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260827071309_9.png', -7.2563656, 112.7496653, 19.98, NULL, '', '114.8.220.153', 'Google Chrome 151 on Android 10'),
(81, 17, '2026-08-27 07:13:43', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260827071343_17.png', -7.2564317, 112.7497753, 13.93, NULL, '', '2404:c0:3563:a940:b4a6:b0ff:fe67:d78d', 'Google Chrome 151 on Android 10'),
(82, 21, '2026-08-27 07:14:07', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260827071407_21.png', -7.2563466, 112.7497211, 21.48, NULL, '', '2400:9800:700:c775:f39b:a7b6:5f7b:d310', 'Google Chrome 152 on Android 10'),
(83, 35, '2026-08-27 07:15:04', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260827071504_35.png', -7.2563830, 112.7497302, 17.53, NULL, '', '2400:9800:9b1:d85d:e8de:ebd3:514d:b0ce', 'Samsung Browser on Android 10'),
(84, 24, '2026-08-27 07:16:47', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260827071647_24.png', -7.2563416, 112.7497256, 22.07, NULL, '', '182.5.234.224', 'Google Chrome 151 on Android 10'),
(85, 24, '2026-08-27 07:17:01', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260827071701_24.png', -7.2563192, 112.7497218, 24.53, NULL, '', '182.5.234.224', 'Google Chrome 151 on Android 10'),
(86, 24, '2026-08-27 07:17:18', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260827071718_24.png', -7.2563363, 112.7497206, 22.62, NULL, '', '182.5.234.224', 'Google Chrome 151 on Android 10'),
(87, 20, '2026-08-27 07:18:03', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260827071803_20.png', -7.2563785, 112.7497642, 18.84, NULL, '', '2404:c0:3473:1717:c146:6ca1:8c13:26a9', 'Samsung Browser on Android 10'),
(88, 4, '2026-08-27 07:18:44', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260827071844_4.jpg', -7.2564035, 112.7496732, 15.68, NULL, '', '2400:9800:9b3:3be9:14f5:57ce:e71b:9612', 'Google Chrome 151 on Android 10'),
(89, 10, '2026-08-27 07:19:53', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260827071953_10.png', -7.2563336, 112.7497046, 22.91, NULL, '', '114.8.230.198', 'Google Chrome 150 on Android 10'),
(90, 6, '2026-08-27 07:21:10', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260827072110_6.png', -7.2563602, 112.7497173, 19.95, NULL, '', '2404:c0:3473:1717:c146:6ca1:8c13:26a9', 'Samsung Browser on Android 10'),
(91, 14, '2026-08-27 07:22:49', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260827072249_14.jpg', -7.2563352, 112.7496494, 23.71, NULL, '', '114.5.111.198', 'Google Chrome 151 on Android 10'),
(92, 36, '2026-08-27 11:16:39', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260827111639_36.png', -7.2564537, 112.7498115, 14.63, NULL, '', '112.215.237.17', 'Google Chrome 151 on Android 10'),
(93, 33, '2026-08-27 13:59:12', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260827135912_33.png', -7.2563718, 112.7496771, 19.02, NULL, '', '114.5.110.224', 'Google Chrome 151 on Android 10'),
(94, 4, '2026-08-27 15:37:52', 'Hadir', 'Pulang', 'uploads/presensi/foto_20260827153752_4.jpg', -7.2569946, 112.7489172, 101.12, NULL, '', '2400:9800:820:1e97:f15c:f580:1da:130d', 'Google Chrome 151 on Android 10'),
(95, 7, '2026-08-27 17:03:36', 'Hadir', 'Pulang', 'uploads/presensi/foto_20260827170336_7.jpg', -7.2569506, 112.7494073, 56.67, NULL, '', '2404:c0:3463:14e5:5409:edff:fe64:a749', 'Google Chrome 149 on Android 10'),
(96, 18, '2026-08-28 06:30:13', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260828063013_18.jpg', -7.2563458, 112.7497093, 21.54, NULL, '', '2404:c0:3577:1f4a:18cf:c546:4306:7fc', 'Google Chrome 151 on Android 10'),
(97, 7, '2026-08-28 07:09:22', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260828070922_7.jpg', -7.2565573, 112.7495316, 19.88, NULL, '', '2404:c0:3561:ed4a:9421:b2ff:fe69:4956', 'Google Chrome 149 on Android 10'),
(98, 12, '2026-08-28 08:13:23', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260828081323_12.jpg', -7.2566837, 112.7494547, 32.49, NULL, '', '182.5.238.187', 'Safari on iPhone (iOS)'),
(99, 14, '2026-08-28 08:13:52', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260828081352_14.jpg', -7.2567008, 112.7492320, 55.79, NULL, '', '114.5.110.224', 'Google Chrome 151 on Android 10'),
(100, 21, '2026-08-28 08:13:57', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260828081357_21.png', -7.2565918, 112.7493912, 35.74, NULL, '', '2400:9800:703:12bf:b242:80f5:1350:540d', 'Google Chrome 152 on Android 10'),
(101, 33, '2026-08-28 08:14:12', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260828081412_33.jpg', -7.2566311, 112.7494433, 31.23, NULL, '', '182.6.73.196', 'Google Chrome 151 on Android 10'),
(102, 25, '2026-08-28 08:14:19', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260828081419_25.png', -7.2566024, 112.7494637, 28.15, NULL, '', '2404:c0:307f:43:b47a:5ffb:ecd4:151b', 'Safari on iPhone (iOS)'),
(103, 29, '2026-08-28 08:14:29', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260828081429_29.png', -7.2565825, 112.7493287, 42.43, NULL, '', '114.79.22.31', 'Google Chrome 151 on Android 10'),
(104, 21, '2026-08-28 13:02:20', 'Hadir', 'Pulang', 'uploads/presensi/foto_20260828130220_21.png', -7.2564650, 112.7497241, 8.41, NULL, '', '2400:9800:701:731d:f3b3:2bf6:9eca:92d6', 'Google Chrome 152 on Android 10'),
(105, 32, '2026-08-28 13:02:50', 'Hadir', 'Pulang', 'uploads/presensi/foto_20260828130250_32.png', -7.2564145, 112.7497434, 14.35, NULL, '', '114.5.111.216', 'Google Chrome 151 on Android 10'),
(106, 7, '2026-08-28 13:27:12', 'Hadir', 'Pulang', 'uploads/presensi/foto_20260828132712_7.jpg', -7.2566946, 112.7495304, 26.34, NULL, '', '2404:c0:3561:ed4a:4bc:a8ff:fe52:6c6d', 'Google Chrome 152 on Android 10'),
(107, 7, '2026-08-31 06:29:28', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260831062928_7.jpg', -7.2570865, 112.7495536, 63.25, NULL, '', '2404:c0:319f:7ce8:84b3:e3ff:fe5f:4ed3', 'Google Chrome 151 on Android 10'),
(108, 11, '2026-08-31 06:43:23', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260831064323_11.png', -7.2568515, 112.7494688, 43.78, NULL, '', '2400:9800:760:326d:f0a3:c56c:31e8:c288', 'Google Chrome 151 on Android 10'),
(109, 25, '2026-08-31 07:38:26', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260831073826_25.png', -7.2563281, 112.7499640, 36.50, NULL, '', '2404:c0:3198:2541:986e:a741:3e7e:dd3d', 'Safari on iPhone (iOS)'),
(110, 18, '2026-08-31 07:38:37', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260831073837_18.jpg', -7.2563698, 112.7496694, 19.42, NULL, '', '2404:c0:3571:247b:18d0:985d:4755:a7d2', 'Google Chrome 151 on Android 10'),
(111, 26, '2026-08-31 09:07:09', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260831090709_26.jpg', -7.2563421, 112.7497249, 22.00, NULL, '', '114.79.20.175', 'Google Chrome 151 on Android 10'),
(112, 21, '2026-08-31 09:08:51', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260831090851_21.png', -7.2563363, 112.7497185, 22.61, NULL, '', '2400:9800:703:1297:b4d2:ac13:68fe:9dda', 'Google Chrome 152 on Android 10'),
(113, 7, '2026-08-31 09:39:27', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260831093927_7.jpg', -7.2570865, 112.7495536, 63.25, NULL, '', '2404:c0:319f:7ce8:84b3:e3ff:fe5f:4ed3', 'Google Chrome 151 on Android 10'),
(114, 23, '2026-08-31 10:02:01', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260831100201_23.jpg', -7.2563388, 112.7497228, 22.36, NULL, '', '2402:5680:88ef:a3c0::1', 'Samsung Browser on Android 10'),
(115, 19, '2026-08-31 10:19:02', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260831101902_19.png', -7.2563386, 112.7497142, 22.34, NULL, '', '114.8.226.160', 'Google Chrome 152 on Android 10'),
(116, 14, '2026-08-31 11:11:12', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260831111112_14.jpg', -7.2563373, 112.7496972, 22.53, NULL, '', '114.5.105.134', 'Google Chrome 151 on Android 10'),
(117, 17, '2026-08-31 11:11:22', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260831111122_17.png', -7.2563519, 112.7497282, 20.95, NULL, '', '2404:c0:31a9:6471:54e4:e6ff:fe39:c7e8', 'Google Chrome 151 on Android 10'),
(118, 29, '2026-08-31 11:12:23', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260831111223_29.png', -7.2563401, 112.7497032, 22.19, NULL, '', '114.79.19.113', 'Google Chrome 151 on Android 10'),
(119, 32, '2026-08-31 11:12:41', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260831111241_32.jpg', -7.2564030, 112.7498822, 24.24, NULL, '', '114.8.218.225', 'Google Chrome 151 on Android 10'),
(120, 36, '2026-08-31 11:13:02', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260831111302_36.png', -7.2565466, 112.7498686, 17.41, NULL, '', '140.213.219.208', 'Google Chrome 151 on Android 10'),
(121, 36, '2026-08-31 11:13:14', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260831111314_36.png', -7.2565466, 112.7498686, 17.41, NULL, '', '140.213.219.208', 'Google Chrome 151 on Android 10'),
(122, 36, '2026-08-31 11:13:19', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260831111319_36.png', -7.2565466, 112.7498686, 17.41, NULL, '', '140.213.219.208', 'Google Chrome 151 on Android 10'),
(123, 5, '2026-08-31 11:14:22', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260831111422_5.png', -7.2563374, 112.7497344, 22.62, NULL, '', '2404:c0:3078:720d:9055:e0ff:fe27:c560', 'Google Chrome 152 on Android 10'),
(124, 12, '2026-08-31 11:30:13', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260831113013_12.png', -7.2564286, 112.7496056, 16.94, NULL, '', '182.5.244.7', 'Safari on iPhone (iOS)'),
(125, 35, '2026-08-31 11:34:36', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260831113436_35.jpg', -7.2563319, 112.7496952, 23.15, NULL, '', '2400:9800:702:8e40:66f1:319b:4bd5:3849', 'Samsung Browser on Android 10'),
(126, 33, '2026-08-31 11:49:43', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260831114943_33.png', -7.2563967, 112.7497345, 16.09, NULL, '', '114.5.105.134', 'Google Chrome 152 on Android 10'),
(127, 22, '2026-08-31 14:29:26', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260831142926_22.png', -7.2563374, 112.7497152, 22.48, NULL, '', '182.5.242.49', 'Google Chrome 151 on Android 10'),
(128, 7, '2026-08-31 15:36:11', 'Hadir', 'Pulang', 'uploads/presensi/foto_20260831153611_7.jpg', -7.2569061, 112.7493728, 55.25, NULL, '', '2404:c0:319f:7ce8:84b3:e3ff:fe5f:4ed3', 'Google Chrome 151 on Android 10'),
(129, 18, '2026-08-31 15:36:25', 'Hadir', 'Pulang', 'uploads/presensi/foto_20260831153625_18.jpg', -7.2570781, 112.7494951, 64.45, NULL, '', '2404:c0:3571:247b:18d0:985d:4755:a7d2', 'Google Chrome 151 on Android 10'),
(130, 14, '2026-08-31 15:36:53', 'Hadir', 'Pulang', 'uploads/presensi/foto_20260831153653_14.jpg', -7.2571012, 112.7494159, 70.43, NULL, '', '114.5.105.232', 'Google Chrome 151 on Android 10'),
(131, 33, '2026-08-31 15:37:03', 'Hadir', 'Pulang', 'uploads/presensi/foto_20260831153703_33.jpg', -7.2570565, 112.7495573, 59.93, NULL, '', '182.5.241.228', 'Google Chrome 152 on Android 10'),
(132, 7, '2026-09-01 06:23:06', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260901062306_7.jpg', -7.2570880, 112.7495477, 63.59, NULL, '', '2404:c0:347e:d4cc:7cf0:b0ff:fe85:12b8', 'Google Chrome 151 on Android 10'),
(133, 29, '2026-09-01 06:37:07', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260901063707_29.png', -7.2563553, 112.7497289, 20.58, NULL, '', '114.79.22.197', 'Google Chrome 151 on Android 10'),
(134, 18, '2026-09-01 06:39:25', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260901063925_18.jpg', -7.2563018, 112.7496954, 26.49, NULL, '', '2404:c0:3190:8a92:18d1:1f7:f8:9124', 'Google Chrome 151 on Android 10'),
(135, 26, '2026-09-01 06:42:06', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260901064206_26.jpg', -7.2563724, 112.7497120, 18.58, NULL, '', '114.79.16.222', 'Google Chrome 151 on Android 10'),
(136, 19, '2026-09-01 06:44:01', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260901064401_19.png', -7.2563291, 112.7497220, 23.43, NULL, '', '114.8.226.160', 'Google Chrome 152 on Android 10'),
(137, 21, '2026-09-01 07:07:44', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260901070744_21.png', -7.2563400, 112.7497045, 22.19, NULL, '', '2400:9800:702:3154:9244:67f8:6fa8:2d5a', 'Google Chrome 152 on Android 10'),
(138, 17, '2026-09-01 07:08:43', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260901070843_17.png', -7.2563149, 112.7496843, 25.15, NULL, '', '2404:c0:3072:7169:2cc7:16ff:fe61:4c92', 'Google Chrome 151 on Android 10'),
(139, 33, '2026-09-01 13:40:32', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260901134032_33.png', -7.2562725, 112.7496805, 29.88, NULL, '', '182.5.243.148', 'Google Chrome 152 on Android 10'),
(140, 33, '2026-09-01 13:40:34', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260901134034_33.png', -7.2562725, 112.7496805, 29.88, NULL, '', '182.5.243.148', 'Google Chrome 152 on Android 10'),
(141, 14, '2026-09-01 13:40:48', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260901134048_14.jpg', -7.2563687, 112.7497578, 19.68, NULL, '', '114.5.105.41', 'Google Chrome 151 on Android 10'),
(142, 5, '2026-09-01 13:40:57', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260901134057_5.png', -7.2563779, 112.7499230, 29.50, NULL, '', '2404:c0:357e:1841:ec84:78ff:fe27:be91', 'Google Chrome 152 on Android 10'),
(143, 7, '2026-09-01 15:34:17', 'Hadir', 'Pulang', 'uploads/presensi/foto_20260901153417_7.jpg', -7.2568780, 112.7493712, 53.11, NULL, '', '2404:c0:347e:d4cc:7cf0:b0ff:fe85:12b8', 'Google Chrome 151 on Android 10'),
(144, 7, '2026-09-02 06:33:34', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260902063334_7.jpg', -7.2570995, 112.7495030, 66.36, NULL, '', '2404:c0:347e:d4cc:7cf0:b0ff:fe85:12b8', 'Google Chrome 151 on Android 10'),
(145, 5, '2026-09-02 06:51:06', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260902065106_5.png', -7.2565016, 112.7493624, 38.67, NULL, '', '2404:c0:307e:345f:8438:70ff:fe5f:1462', 'Google Chrome 152 on Android 10'),
(146, 32, '2026-09-02 06:55:33', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260902065533_32.png', -7.2563833, 112.7496377, 19.15, NULL, '', '2404:c0:307e:345f:8438:70ff:fe5f:1462', 'Google Chrome 152 on Android 10'),
(147, 18, '2026-09-02 06:56:00', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260902065600_18.jpg', -7.2565546, 112.7493048, 44.83, NULL, '', '2404:c0:3469:d36a:18d1:4ec4:c882:1160', 'Google Chrome 151 on Android 10'),
(148, 35, '2026-09-02 06:56:28', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260902065628_35.jpg', -7.2566827, 112.7494327, 34.57, NULL, '', '2400:9800:703:3bd4:ec15:99d7:cd08:48c0', 'Samsung Browser on Android 10'),
(149, 21, '2026-09-02 06:56:38', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260902065638_21.png', -7.2565134, 112.7496070, 11.82, NULL, '', '2400:9800:702:c9d:a0d9:b79c:48c3:e4c8', 'Google Chrome 152 on Android 10'),
(150, 21, '2026-09-02 06:56:42', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260902065642_21.png', -7.2565134, 112.7496070, 11.82, NULL, '', '2400:9800:702:c9d:a0d9:b79c:48c3:e4c8', 'Google Chrome 152 on Android 10'),
(151, 26, '2026-09-02 06:58:55', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260902065855_26.jpg', -7.2565581, 112.7493486, 40.02, NULL, '', '2400:9800:702:c9d:a0d9:b79c:48c3:e4c8', 'Google Chrome 152 on Android 10'),
(152, 22, '2026-09-02 14:35:51', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260902143551_22.png', -7.2563299, 112.7497245, 23.35, NULL, '', '182.5.245.137', 'Google Chrome 151 on Android 10'),
(153, 14, '2026-09-02 14:35:58', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260902143558_14.jpg', -7.2563698, 112.7497180, 18.89, NULL, '', '114.5.111.229', 'Google Chrome 152 on Android 10'),
(154, 7, '2026-09-02 15:42:58', 'Hadir', 'Pulang', 'uploads/presensi/foto_20260902154258_7.jpg', -7.2569145, 112.7493117, 60.64, NULL, '', '2404:c0:347e:d4cc:7cf0:b0ff:fe85:12b8', 'Google Chrome 151 on Android 10'),
(155, 7, '2026-09-02 17:51:19', 'Hadir', 'Pulang', 'uploads/presensi/foto_20260902175119_7.jpg', -7.2569145, 112.7493117, 60.64, NULL, '', '2404:c0:b601:3578:9c57:7140:1c62:a25b', 'Google Chrome 151 on Android 10'),
(156, 7, '2026-09-03 06:34:23', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260903063423_7.jpg', -7.2571754, 112.7494587, 75.98, NULL, '', '2404:c0:3575:2c93:b0c8:abff:fe4a:d87d', 'Google Chrome 152 on Android 10'),
(157, 21, '2026-09-03 10:07:01', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260903100701_21.png', -7.2563752, 112.7497333, 18.44, NULL, '', '2400:9800:702:65bf:53fb:3d05:1186:1542', 'Google Chrome 152 on Android 10'),
(158, 26, '2026-09-03 10:07:50', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260903100750_26.png', -7.2563444, 112.7497147, 21.70, NULL, '', '114.79.23.145', 'Google Chrome 151 on Android 10'),
(159, 21, '2026-09-03 15:27:12', 'Hadir', 'Pulang', 'uploads/presensi/foto_20260903152712_21.png', -7.2563537, 112.7497227, 20.70, NULL, '', '2400:9800:701:3082:cc20:6193:6010:dcc0', 'Google Chrome 152 on Android 10'),
(160, 26, '2026-09-03 15:27:40', 'Hadir', 'Pulang', 'uploads/presensi/foto_20260903152740_26.png', -7.2563909, 112.7492651, 51.88, NULL, '', '114.79.22.77', 'Google Chrome 151 on Android 10'),
(161, 26, '2026-09-03 15:28:00', 'Hadir', 'Pulang', 'uploads/presensi/foto_20260903152800_26.png', -7.2563909, 112.7492651, 51.88, NULL, '', '114.79.22.77', 'Google Chrome 151 on Android 10'),
(162, 7, '2026-09-03 16:54:42', 'Hadir', 'Pulang', 'uploads/presensi/foto_20260903165442_7.jpg', -7.2571889, 112.7494939, 76.07, NULL, '', '2404:c0:3575:2c93:b0c8:abff:fe4a:d87d', 'Google Chrome 152 on Android 10'),
(163, 10, '2026-09-04 06:36:05', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260904063605_10.png', -7.2563702, 112.7497277, 18.92, NULL, '', '114.8.229.25', 'Google Chrome 150 on Android 10'),
(164, 6, '2026-09-04 06:40:39', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260904064039_6.png', -7.2563627, 112.7497119, 19.66, NULL, '', '2404:c0:319f:688c:6979:fdfb:d03f:ca3b', 'Samsung Browser on Android 10'),
(165, 6, '2026-09-04 06:41:49', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260904064149_6.png', -7.2563503, 112.7497262, 21.11, NULL, '', '2404:c0:319f:688c:6979:fdfb:d03f:ca3b', 'Samsung Browser on Android 10'),
(166, 12, '2026-09-04 07:11:15', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260904071115_12.png', -7.2567151, 112.7495500, 26.39, NULL, '', '182.5.232.119', 'Safari on iPhone (iOS)'),
(167, 29, '2026-09-04 07:11:33', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260904071133_29.png', -7.2568522, 112.7495119, 41.12, NULL, '', '114.79.23.4', 'Google Chrome 152 on Android 10'),
(168, 21, '2026-09-04 07:11:41', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260904071141_21.png', -7.2568553, 112.7494617, 44.59, NULL, '', '2400:9800:702:25ac:1817:29f7:6859:108b', 'Google Chrome 152 on Android 10'),
(169, 26, '2026-09-04 07:12:01', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260904071201_26.jpg', -7.2568354, 112.7495168, 39.26, NULL, '', '114.79.21.118', 'Google Chrome 151 on Android 10'),
(170, 7, '2026-09-04 07:16:01', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260904071601_7.jpg', -7.2567865, 112.7495285, 34.05, NULL, '', '2404:c0:347d:9942:f4a3:ecff:fed3:a139', 'Google Chrome 152 on Android 10'),
(171, 19, '2026-09-04 08:02:42', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260904080242_19.png', -7.2568441, 112.7495120, 40.35, NULL, '', '114.8.225.162', 'Google Chrome 152 on Android 10'),
(172, 23, '2026-09-04 08:03:31', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260904080331_23.png', -7.2565864, 112.7494195, 32.56, NULL, '', '2402:5680:89ca:7cfb::1', 'Samsung Browser on Android 10'),
(173, 7, '2026-09-04 14:35:36', 'Hadir', 'Pulang', 'uploads/presensi/foto_20260904143536_7.jpg', -7.2569782, 112.7494103, 58.98, NULL, '', '2404:c0:347d:9942:f4a3:ecff:fed3:a139', 'Google Chrome 152 on Android 10'),
(175, 26, '2026-09-07 07:16:16', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260907071616_26.jpg', -7.2567080, 112.7494278, 36.42, NULL, '', '114.79.18.101', 'Google Chrome 151 on Android 10'),
(176, 12, '2026-09-07 10:50:30', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260907105030_12.png', -7.2563263, 112.7497846, 25.07, NULL, '', '182.5.198.160', 'Safari on iPhone (iOS)'),
(177, 7, '2026-09-07 14:07:25', 'Hadir', 'Pulang', 'uploads/presensi/foto_20260907140725_7.jpg', -7.2563737, 112.7497264, 18.52, NULL, '', '114.5.105.64', 'Google Chrome 152 on Android 10'),
(178, 7, '2026-09-07 14:08:06', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260907140806_7.jpg', -7.2563785, 112.7497193, 17.93, NULL, '', '114.5.105.64', 'Google Chrome 152 on Android 10'),
(179, 21, '2026-09-07 15:04:11', 'Hadir', 'Pulang', 'uploads/presensi/foto_20260907150411_21.png', -7.2563521, 112.7497232, 20.88, NULL, '', '2400:9800:703:19a0:d924:aeb2:f06:661b', 'Google Chrome 152 on Android 10'),
(180, 7, '2026-09-08 06:34:45', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260908063445_7.jpg', -7.2571030, 112.7495026, 66.74, NULL, '', '114.5.111.136', 'Google Chrome 152 on Android 10'),
(181, 26, '2026-09-08 06:37:37', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260908063737_26.jpg', -7.2563484, 112.7496888, 21.39, NULL, '', '114.79.19.39', 'Google Chrome 151 on Android 10'),
(182, 21, '2026-09-08 08:07:18', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260908080718_21.png', -7.2563506, 112.7497829, 22.46, NULL, '', '2400:9800:703:489d:8bb7:bd0f:82bf:34c3', 'Google Chrome 152 on Android 10'),
(183, 33, '2026-09-08 08:08:03', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260908080803_33.png', -7.2562629, 112.7496362, 31.84, NULL, '', '182.5.245.245', 'Google Chrome 152 on Android 10'),
(184, 23, '2026-09-08 08:09:11', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260908080911_23.png', -7.2564037, 112.7497413, 15.47, NULL, '', '2402:5680:88a3:832a::1', 'Samsung Browser on Android 10'),
(185, 7, '2026-09-08 15:35:57', 'Hadir', 'Pulang', 'uploads/presensi/foto_20260908153557_7.jpg', -7.2566791, 112.7492373, 54.50, NULL, '', '114.5.111.136', 'Google Chrome 152 on Android 10'),
(186, 26, '2026-09-09 06:07:45', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260909060745_26.png', -7.2563102, 112.7497109, 25.50, NULL, '', '114.79.23.69', 'Google Chrome 151 on Android 10'),
(187, 7, '2026-09-09 06:34:29', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260909063429_7.jpg', -7.2571946, 112.7494189, 79.65, NULL, '', '114.5.103.110', 'Google Chrome 152 on Android 10'),
(188, 21, '2026-09-09 07:10:10', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260909071010_21.png', -7.2563245, 112.7496869, 24.05, NULL, '', '2400:9800:821:2958:91f9:19:296a:164a', 'Google Chrome 152 on Android 10'),
(189, 23, '2026-09-09 07:27:48', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260909072748_23.png', -7.2563590, 112.7497889, 21.84, NULL, '', '2402:5680:88d4:d44d::1', 'Samsung Browser on Android 10'),
(190, 26, '2026-09-09 15:34:09', 'Hadir', 'Pulang', 'uploads/presensi/foto_20260909153409_26.jpg', -7.2563120, 112.7495334, 31.99, NULL, '', '114.79.18.102', 'Google Chrome 151 on Android 10'),
(191, 7, '2026-09-09 15:42:56', 'Hadir', 'Pulang', 'uploads/presensi/foto_20260909154256_7.jpg', -7.2569047, 112.7493702, 55.33, NULL, '', '114.5.103.110', 'Google Chrome 152 on Android 10'),
(192, 26, '2026-09-10 06:26:45', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260910062645_26.jpg', -7.2563377, 112.7497178, 22.45, NULL, '', '114.79.22.17', 'Google Chrome 151 on Android 10'),
(193, 12, '2026-09-10 06:27:32', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260910062732_12.png', -7.2563360, 112.7497774, 23.79, NULL, '', '182.5.233.132', 'Safari on iPhone (iOS)'),
(194, 7, '2026-09-10 06:39:48', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260910063948_7.png', -7.2576237, 112.7489172, 148.99, NULL, '', '114.5.103.225', 'Google Chrome 152 on Android 10'),
(195, 7, '2026-09-10 17:04:25', 'Hadir', 'Pulang', 'uploads/presensi/foto_20260910170425_7.png', -7.2572699, 112.7492615, 95.15, NULL, '', '114.5.103.225', 'Google Chrome 152 on Android 10'),
(196, 12, '2026-09-11 06:19:09', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260911061909_12.png', -7.2563399, 112.7498819, 29.12, NULL, '', '182.5.199.56', 'Safari on iPhone (iOS)'),
(197, 7, '2026-09-11 06:39:52', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260911063952_7.png', -7.2571784, 112.7493989, 78.94, NULL, '', '2404:c0:3475:ad56:bcca:f2ff:feca:c361', 'Google Chrome 152 on Android 10'),
(198, 26, '2026-09-11 09:03:41', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260911090341_26.jpg', -7.2564118, 112.7497321, 14.39, NULL, '', '114.79.21.94', 'Google Chrome 151 on Android 10'),
(199, 21, '2026-09-11 09:06:17', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260911090617_21.png', -7.2564385, 112.7497941, 14.50, NULL, '', '140.213.42.187', 'Google Chrome 152 on Android 10'),
(200, 17, '2026-09-11 09:27:32', 'Hadir', 'Masuk', 'uploads/presensi/foto_20260911092732_17.png', -7.2563535, 112.7497377, 20.89, NULL, '', '2404:c0:3563:4074:88b3:56ff:feab:27d5', 'Google Chrome 152 on Android 10'),
(201, 29, '2026-09-11 14:38:59', 'Hadir', 'Pulang', 'uploads/presensi/foto_20260911143859_29.png', -7.2564682, 112.7496263, 12.24, NULL, '', '114.79.21.54', 'Google Chrome 153 on Android 10'),
(202, 26, '2026-09-11 14:39:07', 'Hadir', 'Pulang', 'uploads/presensi/foto_20260911143907_26.jpg', -7.2567153, 112.7496161, 22.17, NULL, '', '114.79.23.101', 'Google Chrome 151 on Android 10'),
(203, 7, '2026-09-11 16:34:50', 'Hadir', 'Pulang', 'uploads/presensi/foto_20260911163450_7.png', -7.2571063, 112.7494569, 68.97, NULL, '', '114.5.102.125', 'Google Chrome 152 on Android 10');

-- --------------------------------------------------------

--
-- Table structure for table `school_location`
--

CREATE TABLE `school_location` (
  `id` int(11) NOT NULL,
  `nama_titik` varchar(100) NOT NULL DEFAULT 'SMAN 2 Surabaya',
  `latitude` decimal(10,7) NOT NULL DEFAULT -7.2655540,
  `longitude` decimal(10,7) NOT NULL DEFAULT 112.7503890,
  `radius_meter` int(11) NOT NULL DEFAULT 200,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `school_location`
--

INSERT INTO `school_location` (`id`, `nama_titik`, `latitude`, `longitude`, `radius_meter`, `created_at`, `updated_at`) VALUES
(1, 'SMAN 2 Surabaya', -7.2565395, 112.7497109, 200, '2026-08-19 07:08:37', '2026-08-24 08:12:10');

-- --------------------------------------------------------

--
-- Table structure for table `telegram_config`
--

CREATE TABLE `telegram_config` (
  `id` int(11) NOT NULL,
  `bot_token` varchar(255) NOT NULL DEFAULT '8982081168:AAGMRgzqFbsExn8QQsGxTVrI_piJmp_uT1M',
  `chat_id` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `telegram_config`
--

INSERT INTO `telegram_config` (`id`, `bot_token`, `chat_id`, `is_active`, `created_at`, `updated_at`) VALUES
(1, '8982081168:AAGMRgzqFbsExn8QQsGxTVrI_piJmp_uT1M', '6608181417', 1, '2026-08-22 11:10:42', '2026-08-22 11:33:36');

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
  `status` varchar(255) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `device_info` varchar(255) DEFAULT NULL,
  `browser_info` varchar(100) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `status`, `ip_address`, `device_info`, `browser_info`, `last_login`) VALUES
(1, 'admin', 'admin', 'ADMIN', '2a02:26f7:dfcc:4000:2000::1b', 'Safari on iPhone (iOS)', 'Safari', '2026-09-05 15:51:07'),
(2, '197707132000121001', '197707132000121001', 'PEGAWAI', '114.10.149.188', 'Google Chrome 152 on Android 10', 'Google Chrome 152', '2026-09-05 15:25:25'),
(3, '196801101997031003', '196801101997031003', 'PEGAWAI', NULL, NULL, NULL, NULL),
(4, '196605161988032001', '196605161988032001', 'PEGAWAI', NULL, NULL, NULL, NULL),
(5, '197004052005011006', '197004052005011006', 'PEGAWAI', NULL, NULL, NULL, NULL),
(6, '0103128173', '0103128173', 'PEGAWAI', '103.191.165.41', 'Google Chrome 152 on macOS', 'Google Chrome 152', '2026-09-05 15:29:38'),
(7, '0103364648', '0103364648', 'PEGAWAI', '114.5.242.89', 'Google Chrome 151 on Android 10', 'Google Chrome 151', '2026-08-27 07:16:06'),
(8, '0108020213', '0108020213', 'PEGAWAI', '2404:c0:3079:28c1:bd94:d35c:c41c:e370', 'Safari on iPhone (iOS)', 'Safari', '2026-08-27 07:12:21'),
(9, '0112322335', '0112322335', 'PEGAWAI', '2400:9800:820:1e97:f15c:f580:1da:130d', 'Google Chrome 151 on Android 10', 'Google Chrome 151', '2026-08-27 15:36:39'),
(10, '0108214198', '0108214198', 'PEGAWAI', '2404:c0:307e:345f:8438:70ff:fe5f:1462', 'Google Chrome 152 on Android 10', 'Google Chrome 152', '2026-09-02 06:50:56'),
(11, '0114738767', '0114738767', 'PEGAWAI', '2404:c0:319f:688c:6979:fdfb:d03f:ca3b', 'Samsung Browser on Android 10', 'Samsung Browser', '2026-09-04 06:40:11'),
(12, '0109091858', '0109091858', 'PEGAWAI', '114.5.102.125', 'Google Chrome 152 on Android 10', 'Google Chrome 152', '2026-09-11 16:34:15'),
(13, '0083178495', '0083178495', 'PEGAWAI', '140.213.42.164', 'Google Chrome 151 on Android 10', 'Google Chrome 151', '2026-08-24 07:23:07'),
(14, '0101018430', '0101018430', 'PEGAWAI', '114.8.220.153', 'Google Chrome 151 on Android 10', 'Google Chrome 151', '2026-08-27 07:12:22'),
(15, '0104434293', '0104434293', 'PEGAWAI', '114.8.229.25', 'Google Chrome 150 on Android 10', 'Google Chrome 150', '2026-09-04 06:35:53'),
(16, '0107108286', '0107108286', 'PEGAWAI', '2400:9800:760:326d:f0a3:c56c:31e8:c288', 'Google Chrome 151 on Android 10', 'Google Chrome 151', '2026-08-31 06:43:09'),
(17, '0126172624', '0126172624', 'PEGAWAI', '182.5.199.56', 'Safari on iPhone (iOS)', 'Safari', '2026-09-11 06:18:50'),
(18, '0112810604', '0112810604', 'PEGAWAI', '2a02:26f7:dfcd:4000:2000::e', 'Safari on iPhone (iOS)', 'Safari', '2026-08-27 13:51:53'),
(19, '0129671037', '0129671037', 'PEGAWAI', '114.5.111.229', 'Google Chrome 152 on Android 10', 'Google Chrome 152', '2026-09-02 14:35:33'),
(20, '0103264785', '0103264785', 'PEGAWAI', '2404:c0:3079:725d:18cf:2a9a:9691:3e24', 'Google Chrome 151 on Android 10', 'Google Chrome 151', '2026-08-26 06:45:27'),
(21, '0117815022', '0117815022', 'PEGAWAI', '114.8.218.146', 'Google Chrome 151 on Android 10', 'Google Chrome 151', '2026-08-24 07:14:59'),
(22, '0113867582', '0113867582', 'PEGAWAI', '2404:c0:3563:4074:88b3:56ff:feab:27d5', 'Google Chrome 152 on Android 10', 'Google Chrome 152', '2026-09-11 09:27:08'),
(23, '0107787521', '0107787521', 'PEGAWAI', '2404:c0:3198:99fe:6063:b735:cb7e:ab6a', 'Safari on macOS', 'Safari', '2026-09-08 06:27:19'),
(24, '0111595765', '0111595765', 'PEGAWAI', '114.8.225.162', 'Google Chrome 152 on Android 10', 'Google Chrome 152', '2026-09-04 08:02:23'),
(25, '0104790544', '0104790544', 'PEGAWAI', '2404:c0:319f:688c:6979:fdfb:d03f:ca3b', 'Samsung Browser on Android 10', 'Samsung Browser', '2026-09-04 06:38:46'),
(26, '0107233420', '0107233420', 'PEGAWAI', '140.213.42.187', 'Google Chrome 152 on Android 10', 'Google Chrome 152', '2026-09-11 09:05:15'),
(27, '1011885778', '1011885778', 'PEGAWAI', '182.5.245.137', 'Google Chrome 151 on Android 10', 'Google Chrome 151', '2026-09-02 14:35:10'),
(28, '0119527172', '0119527172', 'PEGAWAI', '2402:5680:88d4:d44d::1', 'Samsung Browser on Android 10', 'Samsung Browser', '2026-09-09 07:27:35'),
(29, '0107103088', '0107103088', 'PEGAWAI', '182.5.234.224', 'Google Chrome 151 on Android 10', 'Google Chrome 151', '2026-08-27 07:15:16'),
(30, '0111809897', '0111809897', 'PEGAWAI', '2404:c0:3198:2541:986e:a741:3e7e:dd3d', 'Safari on iPhone (iOS)', 'Safari', '2026-08-31 07:37:49'),
(31, '0119601956', '0119601956', 'PEGAWAI', '114.79.23.101', 'Google Chrome 151 on Android 10', 'Google Chrome 151', '2026-09-11 14:38:31'),
(32, '0117956929', '0117956929', 'PEGAWAI', NULL, NULL, NULL, NULL),
(33, '0105971823', '0105971823', 'PEGAWAI', '182.5.241.57', 'Safari on iPhone (iOS)', 'Safari', '2026-09-02 07:07:16'),
(34, '0117988711', '0117988711', 'PEGAWAI', '114.79.21.54', 'Google Chrome 153 on Android 10', 'Google Chrome 153', '2026-09-11 14:38:30'),
(35, '0108739943', '0108739943', 'PEGAWAI', NULL, NULL, NULL, NULL),
(36, '0103880660', '0103880660', 'PEGAWAI', '2a02:26f7:dfcc:da94:0:3000:0:3', 'Safari on iPhone (iOS)', 'Safari', '2026-08-27 07:11:54'),
(37, '0104370640', '0104370640', 'PEGAWAI', '2404:c0:307e:345f:8438:70ff:fe5f:1462', 'Google Chrome 152 on Android 10', 'Google Chrome 152', '2026-09-02 06:52:50'),
(38, '0103337613', '0103337613', 'PEGAWAI', '182.5.245.245', 'Google Chrome 152 on Android 10', 'Google Chrome 152', '2026-09-08 08:07:30'),
(39, '0103546403', '0103546403', 'PEGAWAI', '114.79.23.53', 'Google Chrome 151 on Android 10', 'Google Chrome 151', '2026-08-26 08:17:46'),
(40, '0108645866', '0108645866', 'PEGAWAI', '2400:9800:703:3bd4:ec15:99d7:cd08:48c0', 'Samsung Browser on Android 10', 'Samsung Browser', '2026-09-02 06:56:07'),
(41, '3109560686', '3109560686', 'PEGAWAI', '140.213.219.208', 'Google Chrome 151 on Android 10', 'Google Chrome 151', '2026-08-31 11:12:35');

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
  ADD KEY `id_pegawai` (`id_pegawai`),
  ADD KEY `idx_presensi_gamifikasi` (`id_pegawai`,`tanggal_waktu`,`status`),
  ADD KEY `idx_presensi_tanggal_status` (`tanggal_waktu`,`status`);

--
-- Indexes for table `school_location`
--
ALTER TABLE `school_location`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `telegram_config`
--
ALTER TABLE `telegram_config`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=204;

--
-- AUTO_INCREMENT for table `school_location`
--
ALTER TABLE `school_location`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `telegram_config`
--
ALTER TABLE `telegram_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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



-- ============================================================
-- EDU RAG MERDEKA - SMAN 2 SURABAYA LMS & AI EXTENSION
-- ============================================================

-- --------------------------------------------------------
-- Table: lms_classes
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `lms_classes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_kelas` varchar(50) NOT NULL UNIQUE,
  `nama_kelas` varchar(100) NOT NULL,
  `tingkat` varchar(20) DEFAULT '10',
  `jurusan` varchar(50) DEFAULT 'Umum',
  `wali_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tahun_ajaran` varchar(20) DEFAULT '2026/2027',
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `lms_classes` (`id`, `kode_kelas`, `nama_kelas`, `tingkat`, `jurusan`, `wali_user_id`, `tahun_ajaran`, `is_active`) VALUES
(1, 'X-1', 'Kelas X-1', '10', 'Kurikulum Merdeka', 2, '2026/2027', 1),
(2, 'X-2', 'Kelas X-2', '10', 'Kurikulum Merdeka', 3, '2026/2027', 1),
(3, 'X-3', 'Kelas X-3', '10', 'Kurikulum Merdeka', 4, '2026/2027', 1);

-- --------------------------------------------------------
-- Table: lms_class_students
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `lms_class_students` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `class_id` int(11) NOT NULL,
  `student_user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_student_class` (`class_id`, `student_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `lms_class_students` (`class_id`, `student_user_id`) VALUES
(1, 6),
(1, 7),
(1, 8),
(1, 9),
(1, 10),
(1, 11),
(1, 12),
(1, 13),
(1, 14),
(1, 15),
(1, 16),
(1, 17),
(1, 18),
(1, 19),
(1, 20),
(1, 21),
(1, 22),
(1, 23),
(1, 24),
(1, 25),
(1, 26),
(1, 27),
(1, 28),
(1, 29),
(1, 30),
(1, 31),
(1, 32),
(1, 33),
(1, 34),
(1, 35),
(1, 36),
(1, 37),
(1, 38),
(1, 39),
(1, 40),
(1, 41);

-- --------------------------------------------------------
-- Table: lms_subjects
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `lms_subjects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_mapel` varchar(50) NOT NULL UNIQUE,
  `nama_mapel` varchar(100) NOT NULL,
  `kelompok` varchar(50) DEFAULT 'Umum',
  `deskripsi` text DEFAULT NULL,
  `icon` varchar(50) DEFAULT 'book-open',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `lms_subjects` (`id`, `kode_mapel`, `nama_mapel`, `kelompok`, `deskripsi`, `icon`) VALUES
(1, 'MAT-X', 'Matematika', 'Wajib', 'Mata Pelajaran Matematika Kelas 10 Kurikulum Merdeka (SPLTV, Eksponen, Barisan)', 'calculator'),
(2, 'BIN-X', 'Bahasa Indonesia', 'Wajib', 'Bahasa Indonesia Kelas 10 (Laporan Hasil Observasi, Teks Anekdot, Hikayat)', 'book-open'),
(3, 'IPA-FIS-X', 'IPA - Fisika', 'Wajib', 'Fisika Kelas 10 (Pengukuran Ilmiah, Energi Terbarukan, Vektor)', 'zap'),
(4, 'IPA-KIM-X', 'IPA - Kimia', 'Wajib', 'Kimia Kelas 10 (Kimia Hijau, Struktur Atom, Nanoteknologi)', 'flask-conical'),
(5, 'IPA-BIO-X', 'IPA - Biologi', 'Wajib', 'Biologi Kelas 10 (Keanekaragaman Hayati, Virus dan Peranannya)', 'dna'),
(6, 'BIG-X', 'Bahasa Inggris', 'Wajib', 'English for High School (Descriptive Text, Recount Text)', 'globe');

-- --------------------------------------------------------
-- Table: lms_teacher_subjects
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `lms_teacher_subjects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `teacher_user_id` bigint(20) UNSIGNED NOT NULL,
  `subject_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `lms_teacher_subjects` (`teacher_user_id`, `subject_id`, `class_id`) VALUES
(2, 1, 1),
(2, 1, 2),
(3, 2, 1),
(4, 3, 1),
(5, 6, 1);

-- --------------------------------------------------------
-- Table: lms_materials
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `lms_materials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject_id` int(11) NOT NULL,
  `teacher_user_id` bigint(20) UNSIGNED NOT NULL,
  `judul` varchar(255) NOT NULL,
  `bab` varchar(100) NOT NULL,
  `konten` longtext NOT NULL,
  `file_lampiran` varchar(255) DEFAULT NULL,
  `youtube_url` varchar(255) DEFAULT NULL,
  `is_published` tinyint(1) DEFAULT 1,
  `views` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `lms_materials` (`id`, `subject_id`, `teacher_user_id`, `judul`, `bab`, `konten`, `is_published`) VALUES
(1, 1, 2, 'Pengantar Sistem Persamaan Linear Tiga Variabel (SPLTV)', 'Bab 3 - Sistem Persamaan Linear', '<h3>Sistem Persamaan Linear Tiga Variabel (SPLTV)</h3><p>Sistem Persamaan Linear Tiga Variabel adalah kumpulan tiga atau lebih persamaan linear yang memuat tiga variabel yang sama. Bentuk umum SPLTV dalam variabel x, y, dan z adalah:</p><pre>a1*x + b1*y + c1*z = d1\na2*x + b2*y + c2*z = d2\na3*x + b3*y + c3*z = d3</pre><p>Metode penyelesaian meliputi:</p><ul><li><b>Metode Substitusi</b>: Menyatakan salah satu variabel dalam dua variabel lainnya lalu mensubstitusikannya.</li><li><b>Metode Eliminasi</b>: Menghilangkan salah satu variabel dengan menyamakan koefisiennya.</li><li><b>Metode Campuran</b>: Kombinasi eliminasi untuk mereduksi ke 2 variabel, lalu substitusi.</li></ul>', 1),
(2, 2, 3, 'Menulis Teks Laporan Hasil Observasi (LHO)', 'Bab 1 - Mengungkap Fakta Alam Secara Objektif', '<h3>Teks Laporan Hasil Observasi</h3><p>Teks laporan hasil observasi adalah teks yang menyajikan informasi tentang suatu hal secara apa adanya berdasarkan hasil pengamatan faktual dan objektif.</p><h4>Struktur Teks LHO:</h4><ol><li><b>Pernyataan Umum / Klasifikasi:</b> pengenalan objek yang diamati.</li><li><b>Deskripsi Bagian:</b> perincian bagian-bagian objek secara detail.</li><li><b>Deskripsi Manfaat:</b> penjelasan fungsi dan faedah objek bagi kehidupan.</li></ol>', 1),
(3, 3, 4, 'Pengukuran dalam Kegiatan Kerja Ilmiah & Angka Penting', 'Bab 1 - Pengukuran Ilmiah', '<h3>Pengukuran & Ketelitian Alat Ukur</h3><p>Pengukuran adalah proses membandingkan suatu besaran yang diukur dengan besaran sejenis yang ditetapkan sebagai satuan.</p><p>Alat ukur panjang meliputi jangka sorong (ketelitian 0,05 mm atau 0,01 mm) dan mikrometer sekrup (ketelitian 0,01 mm).</p>', 1);

-- --------------------------------------------------------
-- Table: lms_material_classes
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `lms_material_classes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `material_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_mat_class` (`material_id`, `class_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `lms_material_classes` (`material_id`, `class_id`) VALUES
(1, 1),
(1, 2),
(2, 1),
(3, 1);

-- --------------------------------------------------------
-- Table: lms_discussions
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `lms_discussions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `material_id` int(11) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `komentar` text NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: lms_assignments
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `lms_assignments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `teacher_user_id` bigint(20) UNSIGNED NOT NULL,
  `judul` varchar(255) NOT NULL,
  `deskripsi` longtext NOT NULL,
  `tipe` enum('upload','online_text') DEFAULT 'upload',
  `deadline` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `lms_assignments` (`id`, `subject_id`, `class_id`, `teacher_user_id`, `judul`, `deskripsi`, `tipe`, `deadline`) VALUES
(1, 1, 1, 2, 'Latihan Mandiri: Soal SPLTV Metode Campuran', 'Selesaikan 3 soal sistem persamaan linear tiga variabel yang ada di Buku Siswa Matematika Kelas 10 halaman 85 nomor 1-3. Tulis tangan langkah penyelesaian dan foto/unggah lembar jawaban dalam format PDF/JPG.', 'upload', '2026-09-25 23:59:00');

-- --------------------------------------------------------
-- Table: lms_assignment_submissions
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `lms_assignment_submissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `assignment_id` int(11) NOT NULL,
  `student_user_id` bigint(20) UNSIGNED NOT NULL,
  `file_jawaban` varchar(255) DEFAULT NULL,
  `catatan_siswa` text DEFAULT NULL,
  `nilai` decimal(5,2) DEFAULT NULL,
  `catatan_guru` text DEFAULT NULL,
  `status` enum('submitted','graded') DEFAULT 'submitted',
  `submitted_at` timestamp NULL DEFAULT current_timestamp(),
  `graded_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_sub_assign` (`assignment_id`, `student_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: lms_question_banks
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `lms_question_banks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `teacher_user_id` bigint(20) UNSIGNED NOT NULL,
  `subject_id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `bab` varchar(100) NOT NULL,
  `tingkat_kelas` varchar(20) DEFAULT '10',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `lms_question_banks` (`id`, `teacher_user_id`, `subject_id`, `judul`, `bab`, `tingkat_kelas`) VALUES
(1, 2, 1, 'Bank Soal Matematika SPLTV & Eksponen', 'Bab 3 - SPLTV', '10');

-- --------------------------------------------------------
-- Table: lms_questions
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `lms_questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bank_id` int(11) DEFAULT NULL,
  `subject_id` int(11) NOT NULL,
  `bab` varchar(100) NOT NULL,
  `tipe` enum('pg','essay') DEFAULT 'pg',
  `pertanyaan` longtext NOT NULL,
  `kesulitan` enum('easy','medium','hard','hots') DEFAULT 'medium',
  `kunci_jawaban` varchar(255) NOT NULL,
  `pembahasan` longtext DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `lms_questions` (`id`, `bank_id`, `subject_id`, `bab`, `tipe`, `pertanyaan`, `kesulitan`, `kunci_jawaban`, `pembahasan`, `created_by`) VALUES
(1, 1, 1, 'Bab 3 - SPLTV', 'pg', 'Himpunan penyelesaian dari sistem persamaan linear x + y = 5 dan x - y = 1 adalah...', 'easy', 'A', 'Jumlahkan kedua persamaan: 2x = 6 => x = 3. Substitusi x = 3 ke persamaan pertama: 3 + y = 5 => y = 2. Jadi HP = (3, 2).', 2),
(2, 1, 1, 'Bab 3 - SPLTV', 'pg', 'Jika x, y, dan z memenuhi: x + y + z = 6, 2x - y + z = 3, dan x - 2y + 3z = 6, maka nilai dari x + 2y - z adalah...', 'medium', 'B', 'Dengan eliminasi-substitusi diperoleh x = 1, y = 2, z = 3. Maka x + 2y - z = 1 + 2(2) - 3 = 2.', 2),
(3, 1, 1, 'Bab 3 - SPLTV', 'pg', 'Sebuah toko alat tulis menjual paket A berisi 2 buku dan 1 pulpen seharga Rp12.000. Paket B berisi 1 buku dan 3 pulpen seharga Rp16.000. Berapakah harga 1 buku?', 'medium', 'C', 'Misal buku = b, pulpen = p.\n2b + p = 12.000 (x3) => 6b + 3p = 36.000\nb + 3p = 16.000 (x1) => b + 3p = 16.000\nKurangkan: 5b = 20.000 => b = Rp4.000.', 2);

-- --------------------------------------------------------
-- Table: lms_question_options
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `lms_question_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` int(11) NOT NULL,
  `label` varchar(5) NOT NULL,
  `teks_opsi` text NOT NULL,
  `is_benar` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `lms_question_options` (`question_id`, `label`, `teks_opsi`, `is_benar`) VALUES
(1, 'A', '(3, 2)', 1),
(1, 'B', '(2, 3)', 0),
(1, 'C', '(4, 1)', 0),
(1, 'D', '(1, 4)', 0),
(1, 'E', '(5, 0)', 0),
(2, 'A', '1', 0),
(2, 'B', '2', 1),
(2, 'C', '3', 0),
(2, 'D', '4', 0),
(2, 'E', '5', 0),
(3, 'A', 'Rp2.000', 0),
(3, 'B', 'Rp3.000', 0),
(3, 'C', 'Rp4.000', 1),
(3, 'D', 'Rp5.000', 0),
(3, 'E', 'Rp6.000', 0);

-- --------------------------------------------------------
-- Table: lms_exams
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `lms_exams` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `teacher_user_id` bigint(20) UNSIGNED NOT NULL,
  `judul` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `durasi_menit` int(11) DEFAULT 60,
  `tgl_mulai` datetime NOT NULL,
  `tgl_selesai` datetime NOT NULL,
  `kkm` decimal(5,2) DEFAULT 75.00,
  `acak_soal` tinyint(1) DEFAULT 1,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `lms_exams` (`id`, `subject_id`, `class_id`, `teacher_user_id`, `judul`, `deskripsi`, `durasi_menit`, `tgl_mulai`, `tgl_selesai`, `kkm`, `acak_soal`, `is_active`) VALUES
(1, 1, 1, 2, 'Kuis Formatif: SPLTV & Konsep Dasar', 'Kerjakan kuis pilihan ganda ini secara mandiri. Waktu pengerjaan adalah 30 menit.', 30, '2026-09-01 00:00:00', '2026-10-01 23:59:00', 75.00, 1, 1);

-- --------------------------------------------------------
-- Table: lms_exam_questions
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `lms_exam_questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `exam_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `bobot` decimal(5,2) DEFAULT 33.33,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `lms_exam_questions` (`exam_id`, `question_id`, `bobot`) VALUES
(1, 1, 33.33),
(1, 2, 33.33),
(1, 3, 33.34);

-- --------------------------------------------------------
-- Table: lms_exam_attempts
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `lms_exam_attempts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `exam_id` int(11) NOT NULL,
  `student_user_id` bigint(20) UNSIGNED NOT NULL,
  `waktu_mulai` datetime NOT NULL,
  `waktu_selesai` datetime DEFAULT NULL,
  `total_benar` int(11) DEFAULT 0,
  `total_salah` int(11) DEFAULT 0,
  `nilai_akhir` decimal(5,2) DEFAULT 0.00,
  `status` enum('in_progress','completed') DEFAULT 'in_progress',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: lms_exam_answers
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `lms_exam_answers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `attempt_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `jawaban_siswa` text DEFAULT NULL,
  `is_benar` tinyint(1) DEFAULT 0,
  `nilai` decimal(5,2) DEFAULT 0.00,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: ai_api_keys (Gemini Key Pool)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `ai_api_keys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `provider` varchar(50) DEFAULT 'gemini',
  `key_name` varchar(100) NOT NULL,
  `api_key` varchar(255) NOT NULL,
  `status` enum('active','rate_limited','inactive') DEFAULT 'active',
  `total_requests` int(11) DEFAULT 0,
  `error_count` int(11) DEFAULT 0,
  `last_used_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `ai_api_keys` (`id`, `provider`, `key_name`, `api_key`, `status`) VALUES
(1, 'gemini', 'Primary Key SMAN 2', 'YOUR_GEMINI_API_KEY_HERE', 'active');

-- --------------------------------------------------------
-- Table: ai_logs
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `ai_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `feature` varchar(50) NOT NULL,
  `prompt` text NOT NULL,
  `response` longtext NOT NULL,
  `model_used` varchar(50) DEFAULT 'gemini-2.5-flash',
  `status` varchar(20) DEFAULT 'success',
  `execution_time_ms` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: ai_curriculum_books (Grounding RAG Kurikulum Merdeka)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `ai_curriculum_books` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject_id` int(11) NOT NULL,
  `judul_buku` varchar(255) NOT NULL,
  `bab` varchar(100) NOT NULL,
  `halaman` varchar(50) NOT NULL,
  `konten_ringkasan` longtext NOT NULL,
  `kata_kunci` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `ai_curriculum_books` (`subject_id`, `judul_buku`, `bab`, `halaman`, `konten_ringkasan`, `kata_kunci`) VALUES
(1, 'Buku Siswa Matematika SMA/SMK Kelas X', 'Bab 1 - Eksponen dan Logaritma', 'Hal. 1-28', 'Definisi bilangan berpangkat bulat positif, sifat-sifat eksponen (a^m * a^n = a^(m+n), a^m / a^n = a^(m-n), (a^m)^n = a^(mn)), fungsi eksponensial dan pertumbuhan/peluruhan, definisi logaritma sebagai invers dari eksponen, sifat-sifat dasar logaritma.', 'eksponen, sifat pangkat, logaritma, pertumbuhan, peluruhan'),
(1, 'Buku Siswa Matematika SMA/SMK Kelas X', 'Bab 2 - Barisan dan Deret', 'Hal. 29-58', 'Pola barisan bilangan, barisan aritmetika dengan beda tetap b = Un - U(n-1), rumus suku ke-n Un = a + (n-1)b, deret aritmetika Sn = n/2(2a + (n-1)b), barisan geometri dengan rasio r = Un / U(n-1), rumus Un = a*r^(n-1), deret geometri hingga dan tak hingga.', 'barisan aritmetika, deret aritmetika, barisan geometri, deret geometri tak hingga'),
(1, 'Buku Siswa Matematika SMA/SMK Kelas X', 'Bab 3 - Sistem Persamaan dan Pertidaksamaan Linear', 'Hal. 59-92', 'Sistem Persamaan Linear Dua Variabel (SPLDV) dan Tiga Variabel (SPLTV). Metode penyelesaian: eliminasi, substitusi, gabungan eliminasi-substitusi. Sistem Pertidaksamaan Linear Dua Variabel (SPtLDV), menentukan daerah himpunan penyelesaian (DHP) pada bidang koordinat Cartesius dan pemodelan masalah nyata.', 'spltv, spldv, eliminasi, substitusi, daerah penyelesaian, pertidaksamaan linear'),
(2, 'Buku Siswa Cerdas Cergas Berbahasa dan Bersastra Indonesia Kelas X', 'Bab 1 - Mengungkap Fakta Alam Secara Objektif', 'Hal. 1-26', 'Teks Laporan Hasil Observasi (LHO). Ciri-ciri teks LHO: objektif, faktual, informatif. Struktur: Pernyataan umum/klasifikasi, Deskripsi bagian, Deskripsi manfaat/kesimpulan. Aspek kebahasaan: kalimat definisi, kalimat deskripsi, verba material, imbuhan di- dan kata depan di, penulisan kutipan dan sitasi ilmiah.', 'laporan hasil observasi, lho, objektif, deskripsi bagian, kalimat definisi'),
(2, 'Buku Siswa Cerdas Cergas Berbahasa dan Bersastra Indonesia Kelas X', 'Bab 2 - Mengungkapkan Kritik Lewat Senyuman', 'Hal. 27-52', 'Teks Anekdot. Ciri utama: cerita lucu/menghibur yang mengandung pesan kritik sosial atau sindiran terhadap fenomena publik/tokoh. Struktur teks anekdot: Abstraksi, Orientasi, Krisis, Reaksi, Koda. Kaidah kebahasaan: kalimat retoris, konjungsi temporal, verba aksi.', 'anekdot, kritik sosial, humor, krisis, reaksi, koda'),
(3, 'Buku Siswa IPA SMA Kelas X', 'Bab 1 - Pengukuran dalam Kerja Ilmiah', 'Hal. 1-30', 'Besaran pokok dan turunan, satuan internasional (SI), dimensi besaran, macam-macam alat ukur panjang (mistar, jangka sorong, mikrometer sekrup), massa (neraca Ohaus), dan waktu (stopwatch). Angka penting (aturan penentuan dan operasi hitung angka penting), ketidakpastian pengukuran berulang dan tunggal.', 'besaran pokok, dimensi, jangka sorong, mikrometer sekrup, angka penting, ketidakpastian');

-- --------------------------------------------------------
-- Table: ai_roadmaps
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `ai_roadmaps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_user_id` bigint(20) UNSIGNED NOT NULL,
  `subject_id` int(11) NOT NULL,
  `topik` varchar(255) NOT NULL,
  `target_hari` int(11) DEFAULT 7,
  `roadmap_json` longtext NOT NULL,
  `progress_percent` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

COMMIT;
