-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 13, 2025 at 05:34 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_kab_bogor`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `CalculateDynamicTotalHarga` ()   BEGIN
  DECLARE i INT DEFAULT 0;
  DECLARE id_harga INT;
  DECLARE total DECIMAL(10, 2) DEFAULT 0;
  
  CREATE TEMPORARY TABLE IF NOT EXISTS DynamicTotalHarga (
    id_thn_pekerjaan_detail INT,
    total DECIMAL(10, 2)
  );
  
  -- Loop untuk setiap baris dalam tabel tb_thn_pekerjaan_detail
  WHILE i < (SELECT COUNT(*) FROM tb_thn_pekerjaan_detail) DO
    SET id_harga = JSON_UNQUOTE(JSON_EXTRACT((SELECT id_thn_harga FROM tb_thn_pekerjaan_detail LIMIT i, 1), CONCAT('$[', i, ']')));
    SET total = total + IFNULL((SELECT harga FROM tb_thn_harga WHERE id = id_harga), 0);
    
    INSERT INTO DynamicTotalHarga (id_thn_pekerjaan_detail, total) VALUES (i + 1, total);
    
    SET i = i + 1;
  END WHILE;
  
  -- Kembalikan hasil
  SELECT * FROM DynamicTotalHarga;
  
  -- Hapus tabel temporer
  DROP TEMPORARY TABLE DynamicTotalHarga;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tb_bidang_teknis`
--

CREATE TABLE `tb_bidang_teknis` (
  `id` int(11) NOT NULL,
  `idOpd` char(6) DEFAULT NULL,
  `idBidangTeknis` char(6) DEFAULT NULL,
  `namaBidangTeknis` varchar(255) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_bidang_teknis`
--

INSERT INTO `tb_bidang_teknis` (`id`, `idOpd`, `idBidangTeknis`, `namaBidangTeknis`, `updated_by`, `updated_at`) VALUES
(1, 'A0001', 'BT0001', 'Cipta Karya & Permukiman', 1, '2025-06-28 14:33:28'),
(4, 'A0001', 'BT0002', 'Binamarga', 1, '2025-06-28 14:34:40'),
(5, 'A0001', 'BT0003', 'SDA', 1, '2025-06-28 14:34:45');

-- --------------------------------------------------------

--
-- Table structure for table `tb_jenis_item`
--

CREATE TABLE `tb_jenis_item` (
  `id` int(11) NOT NULL,
  `idKelompokItem` int(11) DEFAULT NULL,
  `idJenisBarang` char(6) DEFAULT NULL,
  `kodeKelompok` char(15) DEFAULT NULL,
  `NamaJenis` varchar(50) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_jenis_item`
--

INSERT INTO `tb_jenis_item` (`id`, `idKelompokItem`, `idJenisBarang`, `kodeKelompok`, `NamaJenis`, `updated_by`, `updated_at`) VALUES
(1, 1, '01', 'A001.01', 'Mandor', 1, '2023-10-12 08:41:00'),
(2, 1, '02', 'A001.02', 'Tukang', 1, '2023-10-12 08:13:40'),
(4, 2, '02', 'A002.02', 'Batu Kali', 1, '2023-10-12 08:14:19'),
(6, 2, '01', 'A002.01', 'Batu Split', 1, '2023-10-13 15:50:44'),
(7, 3, '01', 'A003.01', 'Besi Hollow', 1, '2023-10-13 16:13:37'),
(8, 2, '03', 'A002.03', 'Batu Krikil', 1, '2023-11-12 07:54:24');

--
-- Triggers `tb_jenis_item`
--
DELIMITER $$
CREATE TRIGGER `delete_tb_jenis_item` AFTER DELETE ON `tb_jenis_item` FOR EACH ROW BEGIN
    DELETE FROM tb_spesifikasi_item
    WHERE idJenisItem = OLD.id;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_tb_jenis_item` AFTER UPDATE ON `tb_jenis_item` FOR EACH ROW BEGIN
    UPDATE tb_spesifikasi_item
    SET kodeKelompok = CONCAT(NEW.kodeKelompok, '.', tb_spesifikasi_item.idSpesifikasi)
    WHERE tb_spesifikasi_item.idJenisItem = NEW.id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tb_kegiatan`
--

CREATE TABLE `tb_kegiatan` (
  `id` int(11) NOT NULL,
  `idKegiatan` char(6) DEFAULT NULL,
  `idBidangTeknis` char(6) DEFAULT NULL,
  `UraianKegiatan` varchar(255) DEFAULT NULL,
  `satuan` char(8) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_kegiatan`
--

INSERT INTO `tb_kegiatan` (`id`, `idKegiatan`, `idBidangTeknis`, `UraianKegiatan`, `satuan`, `updated_by`, `updated_at`) VALUES
(6, 'A0001', 'BT0002', 'Pembuatan Beton', 'm2', 1, '2025-06-28 16:59:00'),
(7, 'A0002', 'BT0001', 'Pembuatan Rangka Baja', 'm2', 1, '2025-06-28 16:22:19'),
(17, 'A0003', 'BT0003', 'Galian saluran tanah ', 'm3', 1, '2025-06-28 16:25:43');

--
-- Triggers `tb_kegiatan`
--
DELIMITER $$
CREATE TRIGGER `delete_tb_kegiatan` AFTER DELETE ON `tb_kegiatan` FOR EACH ROW BEGIN
    DELETE FROM tb_thn_kegiatan
    WHERE idKegiatan = OLD.id;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_tb_kegiatan` AFTER UPDATE ON `tb_kegiatan` FOR EACH ROW BEGIN
    UPDATE tb_thn_kegiatan
    SET kodeKelompok = NEW.idKegiatan
    WHERE idKegiatan = NEW.id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tb_kelompok_item`
--

CREATE TABLE `tb_kelompok_item` (
  `id` int(11) NOT NULL,
  `IdKelItem` char(6) DEFAULT NULL,
  `UraianKelompok` varchar(100) DEFAULT NULL,
  `tipe` char(4) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_kelompok_item`
--

INSERT INTO `tb_kelompok_item` (`id`, `IdKelItem`, `UraianKelompok`, `tipe`, `updated_by`, `updated_at`) VALUES
(1, 'A001', 'Pekerja', 'SSH', 1, '2025-06-25 15:57:51'),
(2, 'A002', 'Batu', 'SSH', 1, '2023-10-13 14:29:31'),
(3, 'A003', 'Besi', 'SSH', 1, '2023-10-12 06:10:26'),
(4, 'A004', 'Aspal', 'SSH', 1, '2023-10-13 15:53:24'),
(7, 'A005', 'Pasir', 'SSH', 1, '2023-10-29 13:11:25');

--
-- Triggers `tb_kelompok_item`
--
DELIMITER $$
CREATE TRIGGER `delete_tb_kelompok_item` AFTER DELETE ON `tb_kelompok_item` FOR EACH ROW BEGIN
    DELETE FROM tb_jenis_item
    WHERE idKelompokItem = OLD.id;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_tb_kelompok_item` AFTER UPDATE ON `tb_kelompok_item` FOR EACH ROW BEGIN
    UPDATE tb_jenis_item
    SET kodeKelompok = CONCAT(NEW.IdKelItem, '.', tb_jenis_item.idJenisBarang)
    WHERE tb_jenis_item.idKelompokItem = NEW.id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tb_lokasi`
--

CREATE TABLE `tb_lokasi` (
  `id` int(11) NOT NULL,
  `nama_toko` varchar(225) DEFAULT NULL,
  `tautan` text DEFAULT NULL,
  `koordinat` text NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_lokasi`
--

INSERT INTO `tb_lokasi` (`id`, `nama_toko`, `tautan`, `koordinat`, `updated_by`, `updated_at`) VALUES
(1, 'TB. Sinar Abadi Home Care', 'https://maps.app.goo.gl/uWzd197k7D9VJZwd7', '', 1, '2023-11-30 03:26:04'),
(3, 'Toko Bangunan Pasirkoja', 'https://maps.app.goo.gl/krnL79YPHFrS1bFY7', '-6.925777184282123, 107.59131677304711', 1, '2025-07-25 20:42:08');

-- --------------------------------------------------------

--
-- Table structure for table `tb_manajemen_dashboard`
--

CREATE TABLE `tb_manajemen_dashboard` (
  `id` int(11) NOT NULL,
  `idItem` text DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_manajemen_dashboard`
--

INSERT INTO `tb_manajemen_dashboard` (`id`, `idItem`, `updated_by`, `updated_at`) VALUES
(1, '[\"1\",\"2\",\"3\",\"5\",\"7\",\"10\",\"8\",\"9\"]', 1, '2023-11-20 10:05:10');

-- --------------------------------------------------------

--
-- Table structure for table `tb_opd`
--

CREATE TABLE `tb_opd` (
  `id` int(11) NOT NULL,
  `idOpd` char(6) DEFAULT NULL,
  `namaOpd` varchar(255) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_opd`
--

INSERT INTO `tb_opd` (`id`, `idOpd`, `namaOpd`, `updated_by`, `updated_at`) VALUES
(7, 'A0004', 'DISHUB', 1, '2025-06-27 21:10:37'),
(8, 'A0005', 'DINKES', 1, '2025-06-27 21:10:46'),
(9, 'A0006', 'DISDIK', 1, '2025-06-27 21:10:54'),
(10, 'A0007', 'LH', 1, '2025-06-27 21:11:01'),
(12, 'A0009', 'Lainnya', 1, '2025-06-27 21:11:27'),
(13, 'A0001', 'PUPR', 1, '2025-06-28 13:34:07');

-- --------------------------------------------------------

--
-- Table structure for table `tb_spesifikasi_item`
--

CREATE TABLE `tb_spesifikasi_item` (
  `id` int(11) NOT NULL,
  `idJenisItem` int(11) DEFAULT NULL,
  `idSpesifikasi` char(6) DEFAULT NULL,
  `kodeKelompok` char(15) DEFAULT NULL,
  `UraianSpesifikasi` varchar(50) DEFAULT NULL,
  `satuan` char(15) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_spesifikasi_item`
--

INSERT INTO `tb_spesifikasi_item` (`id`, `idJenisItem`, `idSpesifikasi`, `kodeKelompok`, `UraianSpesifikasi`, `satuan`, `updated_by`, `updated_at`) VALUES
(3, 2, '01', 'A001.02.01', 'Jasa Tukang', 'Borongan', 1, '2023-10-13 14:28:13'),
(5, 2, '02', 'A001.02.02', 'Jasa Tukang', 'Harian', 1, '2023-10-13 14:28:13'),
(7, 6, '01', 'A002.01.01', 'Batu split ½ berat 100 gram', '100 gram', 1, '2023-10-13 15:51:03'),
(8, 7, '01', 'A003.01.01', '15 x 30 x 0,60 mm', '6 m', 1, '2023-10-13 16:14:04'),
(9, 7, '02', 'A003.01.02', '15 x 30 x 0,80 mm', '6 m', 1, '2023-10-13 16:24:09'),
(10, 4, '02', 'A002.02.02', 'Batu Kali 57', 'Dum', 1, '2023-11-12 07:57:16'),
(11, 6, '1', 'A002.01.1', 'Batu Split 1/2 ukuran 10–20 mm', 'm³', 1, '2025-07-02 13:00:49'),
(12, 6, '2', 'A002.01.2', 'Batu Split 2/3 ukuran 20–30 mm', 'm³', 1, '2025-07-02 13:00:51'),
(13, 8, '1', 'A002.03.1', 'Batu Krikil Halus (Pasir Batu)', 'm³', 1, '2025-07-04 06:06:10');

--
-- Triggers `tb_spesifikasi_item`
--
DELIMITER $$
CREATE TRIGGER `delete_tb_spesifikasi_item` AFTER DELETE ON `tb_spesifikasi_item` FOR EACH ROW BEGIN
    DELETE FROM tb_thn_harga
    WHERE idSpesifikasi = OLD.id;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_tb_spesifikasi_item` AFTER UPDATE ON `tb_spesifikasi_item` FOR EACH ROW BEGIN
    UPDATE tb_thn_harga
    SET kodeKelompok = NEW.kodeKelompok
    WHERE tb_thn_harga.idSpesifikasi = NEW.id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tb_standar_biaya`
--

CREATE TABLE `tb_standar_biaya` (
  `id` int(11) NOT NULL,
  `idASB` char(6) DEFAULT NULL,
  `idOpd` char(6) DEFAULT NULL,
  `UraianKegiatan` varchar(255) DEFAULT NULL,
  `satuan` char(8) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_standar_biaya`
--

INSERT INTO `tb_standar_biaya` (`id`, `idASB`, `idOpd`, `UraianKegiatan`, `satuan`, `updated_by`, `updated_at`) VALUES
(2, 'ASB002', 'A0001', 'Pembuatan Flyover', 'm2', 1, '2025-06-29 20:00:12'),
(3, 'ASB001', 'A0001', 'Pembuatan Jembatan', 'm2', 1, '2025-07-05 10:03:57'),
(4, 'ASB003', 'A0005', 'Pembuatan Puskesmas', 'm³', 1, '2025-07-07 05:07:08');

-- --------------------------------------------------------

--
-- Table structure for table `tb_standar_biaya_thn`
--

CREATE TABLE `tb_standar_biaya_thn` (
  `id` int(11) NOT NULL,
  `idASB` char(6) DEFAULT NULL,
  `tahunASB` char(4) DEFAULT NULL,
  `kodeKelompok` char(6) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_standar_biaya_thn`
--

INSERT INTO `tb_standar_biaya_thn` (`id`, `idASB`, `tahunASB`, `kodeKelompok`, `updated_by`, `updated_at`) VALUES
(1, '3', '2023', 'ASB001', 1, '2023-11-12 08:03:48'),
(2, '3', '2022', 'ASB001', 1, '2023-11-12 08:04:04'),
(3, '2', '2023', 'ASB002', 1, '2023-11-12 08:04:12'),
(4, '2', '2022', 'ASB002', 1, '2023-11-12 08:06:29'),
(6, '3', '2025', 'ASB001', 1, '2025-06-29 19:35:54'),
(10, '4', '2027', 'ASB003', 1, '2025-07-07 05:07:08');

-- --------------------------------------------------------

--
-- Table structure for table `tb_standar_biaya_thn_detail`
--

CREATE TABLE `tb_standar_biaya_thn_detail` (
  `id` int(11) NOT NULL,
  `id_standar_biaya_thn` int(11) DEFAULT NULL,
  `id_thn_pekerjaan_detail` text DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_standar_biaya_thn_detail`
--

INSERT INTO `tb_standar_biaya_thn_detail` (`id`, `id_standar_biaya_thn`, `id_thn_pekerjaan_detail`, `updated_by`, `updated_at`) VALUES
(4, 1, '[]', 1, '2023-11-13 09:00:03'),
(13, 12, '[\"11\",\"10\",\"14\"]', 1, '2025-07-06 20:57:13'),
(16, 3, '[\"11\",\"10\",\"12\"]', 1, '2025-07-07 05:06:30');

-- --------------------------------------------------------

--
-- Table structure for table `tb_thn_harga`
--

CREATE TABLE `tb_thn_harga` (
  `id` int(11) NOT NULL,
  `idSpesifikasi` int(11) DEFAULT NULL,
  `kodeKelompok` char(15) DEFAULT NULL,
  `TahunHarga` char(4) DEFAULT NULL,
  `harga` double DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_thn_harga`
--

INSERT INTO `tb_thn_harga` (`id`, `idSpesifikasi`, `kodeKelompok`, `TahunHarga`, `harga`, `updated_by`, `updated_at`) VALUES
(5, 3, 'A001.02.01', '2023', 4500000, 1, '2023-10-13 02:20:15'),
(6, 3, 'A001.02.01', '2022', 4300000, 1, '2023-10-13 02:20:15'),
(9, 7, 'A002.01.01', '2023', 1500000, 1, '2023-10-13 15:51:27'),
(10, 7, 'A002.01.01', '2022', 1250000, 1, '2023-11-20 09:43:58'),
(12, 8, 'A003.01.01', '2022', 36000, 1, '2023-10-13 16:17:34'),
(13, 9, 'A003.01.02', '2023', 49000, 1, '2023-10-13 16:25:14'),
(14, 9, 'A003.01.02', '2022', 47000, 1, '2023-10-13 16:25:34'),
(15, 10, 'A002.02.02', '2023', 1000000, 1, '2023-11-12 07:57:16'),
(16, 10, 'A002.02.02', '2022', 900000, 1, '2023-11-12 07:57:16'),
(18, 5, 'A001.02.02', '2023', 160000, 1, '2023-11-05 12:20:43'),
(19, 5, 'A001.02.02', '2022', 150000, 1, '2023-11-11 08:07:12'),
(20, 11, 'A002.01.1', '2027', 500000, 1, '2025-07-02 13:00:49'),
(21, 12, 'A002.01.2', '2027', 500000, 1, '2025-07-02 13:00:51'),
(22, 13, 'A002.03.1', '2027', 700000, 1, '2025-07-04 06:06:10');

-- --------------------------------------------------------

--
-- Table structure for table `tb_thn_kegiatan`
--

CREATE TABLE `tb_thn_kegiatan` (
  `id` int(11) NOT NULL,
  `idKegiatan` char(6) DEFAULT NULL,
  `tahunPekerjaan` char(4) DEFAULT NULL,
  `kodeKelompok` char(6) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_thn_kegiatan`
--

INSERT INTO `tb_thn_kegiatan` (`id`, `idKegiatan`, `tahunPekerjaan`, `kodeKelompok`, `updated_by`, `updated_at`) VALUES
(16, '17', '2023', 'A0003', 1, '2025-06-28 16:54:12'),
(17, '6', '2022', 'A0001', 1, '2023-11-11 07:59:55'),
(18, '6', '2021', 'A0001', 1, '2023-11-11 08:00:01'),
(19, '7', '2023', 'A0002', 1, '2023-11-11 08:00:06'),
(20, '7', '2022', 'A0002', 1, '2023-11-11 08:00:16'),
(21, '7', '2021', 'A0002', 1, '2023-11-11 08:00:22'),
(22, '7', '2025', 'A0002', 1, '2025-06-28 16:54:04'),
(23, '6', '2025', 'A0001', 1, '2025-06-28 16:58:10');

--
-- Triggers `tb_thn_kegiatan`
--
DELIMITER $$
CREATE TRIGGER `delete_tb_thn_kegiatan` AFTER DELETE ON `tb_thn_kegiatan` FOR EACH ROW BEGIN
    DELETE FROM tb_thn_pekerjaan_detail
    WHERE id_thn_kegiatan = OLD.id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tb_thn_pekerjaan_detail`
--

CREATE TABLE `tb_thn_pekerjaan_detail` (
  `id` int(11) NOT NULL,
  `id_thn_kegiatan` char(4) DEFAULT NULL,
  `id_thn_harga` text DEFAULT NULL,
  `total_item` text DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_thn_pekerjaan_detail`
--

INSERT INTO `tb_thn_pekerjaan_detail` (`id`, `id_thn_kegiatan`, `id_thn_harga`, `total_item`, `updated_by`, `updated_at`) VALUES
(10, '19', '[\"4\",\"18\",\"13\"]', '[\"30\",\"30\",\"100\"]', 1, '2023-11-11 12:32:21'),
(11, '17', '[\"6\",\"10\",\"16\"]', '[\"1\",\"50\",\"70\"]', 1, '2025-07-01 12:51:04'),
(12, '20', '[\"3\",\"19\",\"14\"]', '[\"30\",\"30\",\"100\"]', 1, '2023-11-11 12:32:04'),
(14, '16', '[\"5\",\"9\",\"15\"]', '[\"1\",\"50\",\"70\"]', 1, '2025-07-01 12:49:08'),
(20, '17', '[\"6\",\"18\",\"19\"]', '[\"1\",\"1\",\"1\"]', 1, '2025-07-25 20:59:24');

-- --------------------------------------------------------

--
-- Table structure for table `tb_usulan_kegiatan`
--

CREATE TABLE `tb_usulan_kegiatan` (
  `id` int(11) NOT NULL,
  `idKegiatan` char(6) DEFAULT NULL,
  `idBidangTeknis` char(6) DEFAULT NULL,
  `UraianKegiatan` varchar(255) DEFAULT NULL,
  `satuan` char(8) DEFAULT NULL,
  `tahunPekerjaan` char(4) DEFAULT NULL,
  `idOpd` char(6) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'usulan',
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_usulan_kegiatan`
--

INSERT INTO `tb_usulan_kegiatan` (`id`, `idKegiatan`, `idBidangTeknis`, `UraianKegiatan`, `satuan`, `tahunPekerjaan`, `idOpd`, `status`, `updated_by`, `updated_at`) VALUES
(1, 'A0004', 'BT0001', 'Pembuatan Jembatan', 'm2', '2027', 'A0001', 'usulan', 1, '2025-07-04 08:44:01'),
(2, 'A0005', 'BT0002', 'Pembuatan Jalan', 'm2', '2027', 'A0004', 'usulan', 1, '2025-07-04 08:44:57'),
(3, 'A0006', 'BT0002', 'Pembuatan Fondasi', 'm2', '2027', 'A0005', 'usulan', 1, '2025-07-04 08:01:20');

-- --------------------------------------------------------

--
-- Table structure for table `tb_usulan_spesifikasi_item`
--

CREATE TABLE `tb_usulan_spesifikasi_item` (
  `id` int(11) NOT NULL,
  `idJenisItem` int(11) DEFAULT NULL,
  `idSpesifikasi` char(6) DEFAULT NULL,
  `kodeKelompok` char(15) DEFAULT NULL,
  `UraianSpesifikasi` varchar(255) DEFAULT NULL,
  `satuan` char(15) DEFAULT NULL,
  `TahunHarga` char(4) DEFAULT NULL,
  `harga` double DEFAULT NULL,
  `idOpd` char(6) DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'usulan',
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_usulan_spesifikasi_item`
--

INSERT INTO `tb_usulan_spesifikasi_item` (`id`, `idJenisItem`, `idSpesifikasi`, `kodeKelompok`, `UraianSpesifikasi`, `satuan`, `TahunHarga`, `harga`, `idOpd`, `status`, `updated_by`, `updated_at`) VALUES
(28, 6, '1', 'A002.01.1', 'Batu Split 1/2 ukuran 10–20 mm', 'm³', '2027', 500000, 'A0004', 'disetujui', 1, '2025-07-02 13:00:49'),
(29, 6, '2', 'A002.01.2', 'Batu Split 2/3 ukuran 20–30 mm', 'm³', '2027', 500000, 'A0001', 'disetujui', 1, '2025-07-02 13:00:51'),
(30, 8, '1', 'A002.03.1', 'Batu Krikil Halus (Pasir Batu)', 'm³', '2027', 700000, 'A0001', 'disetujui', 1, '2025-07-04 06:06:10'),
(31, 8, '2', 'A002.03.2', 'Batu Kerikil untuk Beton Mutu K-225', 'm³', '2027', 700000, 'A0001', 'usulan', 1, '2025-07-09 07:06:19');

-- --------------------------------------------------------

--
-- Table structure for table `tb_usulan_standar_biaya`
--

CREATE TABLE `tb_usulan_standar_biaya` (
  `id` int(11) NOT NULL,
  `idASB` char(6) NOT NULL,
  `idOpd` char(6) NOT NULL,
  `UraianKegiatan` varchar(255) NOT NULL,
  `satuan` char(8) NOT NULL,
  `tahunASB` char(4) DEFAULT NULL,
  `idOpdPengusul` char(6) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'usulan',
  `updated_by` int(11) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_usulan_standar_biaya`
--

INSERT INTO `tb_usulan_standar_biaya` (`id`, `idASB`, `idOpd`, `UraianKegiatan`, `satuan`, `tahunASB`, `idOpdPengusul`, `status`, `updated_by`, `updated_at`) VALUES
(7, 'ASB003', 'A0005', 'Pembuatan Puskesmas', 'm³', '2027', 'A0001', 'disetujui', 1, '2025-07-07 05:07:08'),
(9, 'ASB005', 'A0004', 'Membuat Jalan Tol', 'm³', '2027', 'A0001', 'usulan', 1, '2025-07-07 05:06:59');

-- --------------------------------------------------------

--
-- Table structure for table `tb_usulan_standar_biaya_thn_detail`
--

CREATE TABLE `tb_usulan_standar_biaya_thn_detail` (
  `id` int(11) NOT NULL,
  `id_standar_biaya_thn` int(11) DEFAULT NULL,
  `id_thn_pekerjaan_detail` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'usulan',
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_usulan_standar_biaya_thn_detail`
--

INSERT INTO `tb_usulan_standar_biaya_thn_detail` (`id`, `id_standar_biaya_thn`, `id_thn_pekerjaan_detail`, `status`, `updated_by`, `updated_at`) VALUES
(9, 7, '[\"11\",\"10\",\"14\"]', 'usulan', 1, '2025-07-07 05:06:16'),
(10, 9, '[\"11\",\"10\",\"14\",\"12\"]', 'usulan', 1, '2025-07-07 05:06:18');

-- --------------------------------------------------------

--
-- Table structure for table `tb_usulan_thn_pekerjaan_detail`
--

CREATE TABLE `tb_usulan_thn_pekerjaan_detail` (
  `id` int(11) NOT NULL,
  `id_thn_kegiatan` char(4) DEFAULT NULL,
  `id_thn_harga` text DEFAULT NULL,
  `total_item` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'usulan',
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_usulan_thn_pekerjaan_detail`
--

INSERT INTO `tb_usulan_thn_pekerjaan_detail` (`id`, `id_thn_kegiatan`, `id_thn_harga`, `total_item`, `status`, `updated_by`, `updated_at`) VALUES
(3, '2', '[\"5\",\"10\"]', '[\"2\",\"1\"]', 'usulan', 1, '2025-07-09 07:09:37'),
(6, '1', '[\"5\",\"6\",\"18\"]', '[\"1\",\"2\",\"1\"]', 'usulan', 1, '2025-07-04 08:44:01');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nick_name` varchar(10) DEFAULT NULL,
  `full_name` varchar(30) DEFAULT NULL,
  `email` varchar(30) NOT NULL,
  `phone` bigint(15) DEFAULT NULL,
  `photo` text DEFAULT NULL,
  `password` text DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `email_kode` varchar(10) DEFAULT NULL,
  `email_active` int(11) DEFAULT 0,
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nick_name`, `full_name`, `email`, `phone`, `photo`, `password`, `role_id`, `status`, `email_kode`, `email_active`, `last_login`, `created_at`) VALUES
(1, 'Admin', 'Administrator', 'trisnatya@gmail.com', 62812121, '', '$2y$10$EDKqYtBy9f40fVv4c0MOVuGyoNAJ0JrkPeJGXsRVs8Vvs7UFvcdxW', 1, 1, NULL, 1, '2025-08-13 10:33:24', '2023-09-09 21:57:09'),
(7, 'Irwin', 'Irwin', 'asd@asd.hd', 62812122, '', '$2a$08$YmZmOThlNjgwZDBkZDBjM.WZO6HG44xIkbd3Sdha.zLNdzFr/TYo2', 8, 1, NULL, 1, '2023-11-27 19:11:51', NULL),
(8, 'Admin', 'Administrator', 'admin@admin.com', 62812123, '', '$2a$08$ZWEyNTk3YWFlM2I4N2FmYOCxqyHeR/hvCSmK2GWzjIOuokdKIxcla', 2, 1, NULL, 1, '2023-11-29 21:18:17', NULL),
(9, 'trisnatya', 'Trisnatya Mahardhika', 'trisnatya.mahardhika@gmail.com', 62812124, '', '$2a$08$MzBlZTdmY2IzMjU5NzIwYu9hmlEvo.ikB.FbdAeFDQZd5GaD331e.', 3, 1, NULL, 1, '2023-10-30 21:21:23', '2023-10-23 04:18:47'),
(32, 'PUPR', 'PUPR', 'pupr@gmail.com', 628516511, '', '$2a$08$ZmVlMTcyOGY5YzliMWI3NeUrAoVpXBPseDGZMPlQiGu6Me3ktBOny', 1, 1, NULL, 1, '2025-07-26 20:11:31', '2025-07-26 20:11:09'),
(33, 'DISHUB', 'DISHUB', 'dishub@gmail.com', 6284684864, '', '$2a$08$Yjc3ZGE3NWFjOGMzMmYzMerInjIHL7FKbNntOuVOEi1ZAn1n2tMRi', 9, 1, NULL, 1, NULL, '2025-07-26 20:12:26'),
(34, 'DINKES', 'DINKES', 'dinkes@gmail.com', 62845161, '', '$2a$08$MTkzNjdjZWU5YmY5NTNkN.p7Qbg07jUUHxvk9eq9HhPnkwNrBQKMe', 9, 1, NULL, 1, '2025-07-26 20:23:03', '2025-07-26 20:12:48'),
(35, 'DISDIK', 'DISDIK', 'disdik@gmail.com', 628464866, '', '$2a$08$ZWE0NTIxYWE5ZDhjMTYzN.szaqceDKqtYcdYJ5uNNasyAvoJa1LLe', 9, 1, NULL, 1, '2025-07-26 20:30:27', '2025-07-26 20:13:19'),
(36, 'LH', 'LH', 'lh@gmail.com', 6281654654, '', '$2a$08$ZDY4MmRlMzU3NTM2NWNhNe4Ku.vxMfLHNayl6RogAXPZsWBVdqUYy', 9, 1, NULL, 1, NULL, '2025-07-26 20:13:41');

-- --------------------------------------------------------

--
-- Table structure for table `users_log`
--

CREATE TABLE `users_log` (
  `id` int(11) NOT NULL,
  `users_id` int(11) DEFAULT NULL,
  `name` varchar(30) DEFAULT NULL,
  `email` varchar(30) DEFAULT NULL,
  `ip` varchar(30) DEFAULT NULL,
  `browser` text DEFAULT NULL,
  `folder_access` varchar(100) DEFAULT NULL,
  `controller_name` varchar(100) DEFAULT NULL,
  `methode` varchar(100) DEFAULT NULL,
  `access_time` datetime DEFAULT NULL,
  `post_data` text DEFAULT NULL,
  `get_data` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_log`
--

INSERT INTO `users_log` (`id`, `users_id`, `name`, `email`, `ip`, `browser`, `folder_access`, `controller_name`, `methode`, `access_time`, `post_data`, `get_data`) VALUES
(10423, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'getDataLokasi', '2025-06-23 15:12:49', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10424, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'data', '2025-06-23 15:12:50', '[]', '[]'),
(10425, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'data', '2025-06-23 15:13:54', '[]', '[]'),
(10426, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'getDataLokasi', '2025-06-23 15:13:54', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10427, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-06-23 15:18:22', '[]', '[]'),
(10428, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-06-23 15:18:44', '[]', '[]'),
(10429, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'data', '2025-06-23 23:41:41', '[]', '[]'),
(10430, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'getDataLokasi', '2025-06-23 23:41:42', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10431, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'data', '2025-06-23 23:41:55', '[]', '[]'),
(10432, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'getDataLokasi', '2025-06-23 23:41:55', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10433, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'getDataLokasi', '2025-06-23 23:42:02', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10434, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'data', '2025-06-23 23:42:02', '[]', '[]'),
(10435, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-06-23 23:42:19', '[]', '[]'),
(10436, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-06-23 23:42:36', '[]', '[]'),
(10437, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-06-23 23:46:47', '[]', '[]'),
(10438, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'data', '2025-06-24 00:32:36', '[]', '[]'),
(10439, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'getDataLokasi', '2025-06-24 00:32:36', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10440, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-06-24 00:32:54', '[]', '[]'),
(10441, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'kegiatan_hspk', 'kegiatan_hspk', 'form', '2025-06-24 09:44:18', '[]', '[]'),
(10442, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'kegiatan_hspk', 'kegiatan_hspk', 'form', '2025-06-24 09:44:18', '[]', '[]'),
(10443, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'kegiatan_hspk', 'kegiatan_hspk', 'form', '2025-06-24 09:45:06', '[]', '[]'),
(10444, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'getDataLokasi', '2025-06-24 09:45:20', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10445, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'data', '2025-06-24 09:45:20', '[]', '[]'),
(10446, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'getDataLokasi', '2025-06-24 09:45:22', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10447, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'data', '2025-06-24 09:45:22', '[]', '[]'),
(10448, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-06-24 10:01:14', '[]', '[]'),
(10449, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'data', '2025-06-24 12:14:40', '[]', '[]'),
(10450, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'getDataLokasi', '2025-06-24 12:14:41', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10451, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-06-24 12:15:18', '[]', '[]'),
(10452, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'data', '2025-06-25 14:38:00', '[]', '[]'),
(10453, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'getDataLokasi', '2025-06-25 14:38:00', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10454, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-06-25 14:38:19', '[]', '[]'),
(10455, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'getDataLokasi', '2025-06-25 22:38:22', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10456, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'data', '2025-06-25 22:38:22', '[]', '[]'),
(10457, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-06-25 22:38:36', '[]', '[]'),
(10458, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'data', '2025-06-26 00:09:02', '[]', '[]'),
(10459, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'getDataLokasi', '2025-06-26 00:09:02', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10460, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-06-26 00:09:17', '[]', '[]'),
(10461, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'tahun_kegiatan_hspk', 'tahun_kegiatan_hspk', 'form', '2025-06-26 18:12:58', '[]', '{\"id\":\"Z1VrWTV4QjJQNmRxbS8ydEhCWHpLdz09\"}'),
(10462, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'tahun_kegiatan_hspk', 'tahun_kegiatan_hspk', 'getById', '2025-06-26 18:13:04', '[]', '{\"id\":\"Z1VrWTV4QjJQNmRxbS8ydEhCWHpLdz09\"}'),
(10463, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'tahun_kegiatan_hspk', 'tahun_kegiatan_hspk', 'kegiatan', '2025-06-26 18:13:05', '[]', '[]'),
(10464, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'data', '2025-06-26 18:13:12', '[]', '[]'),
(10465, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'getDataLokasi', '2025-06-26 18:13:13', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10466, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-06-26 18:13:26', '[]', '[]'),
(10467, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'getDataLokasi', '2025-06-27 21:09:01', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10468, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'data', '2025-06-27 21:09:01', '[]', '[]'),
(10469, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-06-27 21:09:30', '[]', '[]'),
(10470, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'data', '2025-06-28 01:08:23', '[]', '[]'),
(10471, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'getDataLokasi', '2025-06-28 01:08:24', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10472, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-06-28 01:10:30', '[]', '[]'),
(10473, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'data', '2025-06-28 02:04:45', '[]', '[]'),
(10474, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'getDataLokasi', '2025-06-28 02:04:45', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10475, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-06-28 02:04:53', '[]', '[]'),
(10476, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-06-28 03:08:48', '[]', '[]'),
(10477, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-06-28 03:32:47', '[]', '[]'),
(10478, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-06-28 03:56:04', '[]', '[]'),
(10479, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-06-28 03:56:12', '[]', '[]'),
(10480, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'getDataLokasi', '2025-06-28 20:09:06', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10481, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'data', '2025-06-28 20:09:06', '[]', '[]'),
(10482, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-06-28 20:17:28', '[]', '[]'),
(10483, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-06-28 21:04:25', '[]', '[]'),
(10484, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-06-28 23:03:46', '[]', '[]'),
(10485, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'getDataLokasi', '2025-06-30 01:34:34', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10486, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'data', '2025-06-30 01:34:34', '[]', '[]'),
(10487, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-06-30 01:34:43', '[]', '[]'),
(10488, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-06-30 01:39:08', '[]', '[]'),
(10489, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'getDataLokasi', '2025-07-01 01:08:19', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10490, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'data', '2025-07-01 01:08:19', '[]', '[]'),
(10491, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-01 01:08:43', '[]', '[]'),
(10492, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-01 01:16:38', '[]', '[]'),
(10493, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-01 01:19:42', '[]', '[]'),
(10494, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-01 01:20:46', '[]', '[]'),
(10495, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-01 01:31:52', '[]', '[]'),
(10496, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-01 01:58:46', '[]', '[]'),
(10497, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-01 02:02:28', '[]', '[]'),
(10498, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-01 02:10:43', '[]', '[]'),
(10499, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-01 02:12:43', '[]', '[]'),
(10500, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-01 02:15:49', '[]', '[]'),
(10501, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-01 02:49:50', '[]', '[]'),
(10502, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-01 02:51:08', '[]', '[]'),
(10503, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-01 02:59:19', '[]', '[]'),
(10504, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-01 03:14:49', '[]', '[]'),
(10505, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-01 03:24:22', '[]', '[]'),
(10506, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-01 03:42:44', '[]', '[]'),
(10507, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-01 03:43:58', '[]', '[]'),
(10508, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-01 03:45:51', '[]', '[]'),
(10509, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-01 03:47:49', '[]', '[]'),
(10510, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-01 04:01:08', '[]', '[]'),
(10511, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'getDataLokasi', '2025-07-01 16:21:37', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10512, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'data', '2025-07-01 16:21:38', '[]', '[]'),
(10513, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-01 16:21:47', '[]', '[]'),
(10514, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-01 17:04:40', '[]', '[]'),
(10515, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-01 19:14:18', '[]', '[]'),
(10516, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-01 20:02:00', '[]', '[]'),
(10517, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-01 20:30:00', '[]', '[]'),
(10518, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-01 20:47:46', '[]', '[]'),
(10519, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'data', '2025-07-01 20:52:32', '[]', '[]'),
(10520, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'getDataLokasi', '2025-07-01 20:52:32', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10521, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-01 20:52:38', '[]', '[]'),
(10522, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-01 20:53:34', '[]', '[]'),
(10523, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-01 20:56:46', '[]', '[]'),
(10524, 14, 'rizalrandyy', 'rizalrandy3@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'data', '2025-07-01 20:56:47', '[]', '[]'),
(10525, 14, 'rizalrandyy', 'rizalrandy3@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'getDataLokasi', '2025-07-01 20:56:47', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10526, 14, 'rizalrandyy', 'rizalrandy3@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'jenis_item', 'jenis_item', 'getData', '2025-07-01 20:56:49', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10527, 14, 'rizalrandyy', 'rizalrandy3@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'lokasi_toko', 'lokasi_toko', 'getData', '2025-07-01 20:56:55', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10528, 14, 'rizalrandyy', 'rizalrandy3@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'lokasi_toko', 'lokasi_toko', 'getData', '2025-07-01 20:57:20', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10529, 14, 'rizalrandyy', 'rizalrandy3@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'lokasi_toko', 'lokasi_toko', 'getData', '2025-07-01 20:57:24', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10530, 14, 'rizalrandyy', 'rizalrandy3@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'logout', 'login', 'logout', '2025-07-01 20:57:27', '[]', '[]'),
(10531, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-01 20:57:32', '[]', '[]'),
(10532, 14, 'rizalrandyy', 'rizalrandy3@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'getDataLokasi', '2025-07-01 20:57:32', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10533, 14, 'rizalrandyy', 'rizalrandy3@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'data', '2025-07-01 20:57:32', '[]', '[]'),
(10534, 14, 'rizalrandyy', 'rizalrandy3@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'jenis_item', 'jenis_item', 'getData', '2025-07-01 20:57:34', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10535, 14, 'rizalrandyy', 'rizalrandy3@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_spesifikasi_harga', 'usulan_spesifikasi_harga', 'getData', '2025-07-01 20:57:37', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10536, 14, 'rizalrandyy', 'rizalrandy3@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_spesifikasi_item', 'usulan_spesifikasi_item', 'getData', '2025-07-01 20:57:39', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10537, 14, 'rizalrandyy', 'rizalrandy3@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_spesifikasi_harga', 'usulan_spesifikasi_harga', 'getData', '2025-07-01 20:57:41', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10538, 14, 'rizalrandyy', 'rizalrandy3@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_spesifikasi_item', 'usulan_spesifikasi_item', 'getData', '2025-07-01 20:57:46', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10539, 14, 'rizalrandyy', 'rizalrandy3@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_spesifikasi_harga', 'usulan_spesifikasi_harga', 'getData', '2025-07-01 20:57:49', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10540, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-01 20:58:19', '[]', '[]'),
(10541, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-01 21:34:30', '[]', '[]'),
(10542, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-01 23:00:26', '[]', '[]'),
(10543, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-01 23:08:31', '[]', '[]'),
(10544, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-01 23:35:10', '[]', '[]'),
(10545, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-02 00:35:15', '[]', '[]'),
(10546, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-02 02:15:09', '[]', '[]'),
(10547, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'getDataLokasi', '2025-07-02 14:21:27', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10548, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'data', '2025-07-02 14:21:27', '[]', '[]'),
(10549, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-02 14:21:35', '[]', '[]'),
(10550, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'data', '2025-07-02 18:30:16', '[]', '[]'),
(10551, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'getDataLokasi', '2025-07-02 18:30:16', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10552, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-02 18:30:25', '[]', '[]'),
(10553, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'kegiatan_hspk', 'kegiatan_hspk', 'getData', '2025-07-02 20:08:07', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10554, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'data', '2025-07-02 20:08:17', '[]', '[]'),
(10555, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'getDataLokasi', '2025-07-02 20:08:17', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10556, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-02 20:08:22', '[]', '[]'),
(10557, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-02 20:31:54', '[]', '[]'),
(10558, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-02 21:32:40', '[]', '[]'),
(10559, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-02 21:59:40', '[]', '[]'),
(10560, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-02 22:01:34', '[]', '[]'),
(10561, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'getDataLokasi', '2025-07-02 22:11:11', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10562, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'data', '2025-07-02 22:11:11', '[]', '[]'),
(10563, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-02 22:11:17', '[]', '[]'),
(10564, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'getDataLokasi', '2025-07-02 22:27:25', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10565, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'data', '2025-07-02 22:27:25', '[]', '[]'),
(10566, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-02 22:27:31', '[]', '[]'),
(10567, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_hspk', 'usulan_kegiatan_hspk', 'getData', '2025-07-03 21:52:18', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10568, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-03 21:52:33', '[]', '[]'),
(10569, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'getDataLokasi', '2025-07-03 22:26:18', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10570, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'data', '2025-07-03 22:26:18', '[]', '[]'),
(10571, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-03 22:26:31', '[]', '[]'),
(10572, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_hspk_detail', 'usulan_kegiatan_hspk_detail', 'getData', '2025-07-04 12:33:16', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10573, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-04 12:33:30', '[]', '[]'),
(10574, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_hspk_detail', 'usulan_kegiatan_hspk_detail', 'getData', '2025-07-04 14:51:52', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10575, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-04 14:52:07', '[]', '[]'),
(10576, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_hspk_detail', 'usulan_kegiatan_hspk_detail', 'getData', '2025-07-04 16:52:07', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10577, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_hspk_detail', 'usulan_kegiatan_hspk_detail', 'getData', '2025-07-04 16:52:07', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10578, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_hspk_detail', 'usulan_kegiatan_hspk_detail', 'getData', '2025-07-04 16:52:08', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10579, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_hspk_detail', 'usulan_kegiatan_hspk_detail', 'getData', '2025-07-04 19:50:39', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10580, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-04 19:50:51', '[]', '[]'),
(10581, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-04 20:04:29', '[]', '[]'),
(10582, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'data', '2025-07-04 21:23:55', '[]', '[]'),
(10583, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'getDataLokasi', '2025-07-04 21:23:55', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10584, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-04 21:24:06', '[]', '[]'),
(10585, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_asb', 'usulan_kegiatan_asb', 'form', '2025-07-05 16:00:07', '[]', '[]'),
(10586, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_asb', 'usulan_kegiatan_asb', 'Opd', '2025-07-05 16:00:09', '[]', '[]'),
(10587, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_asb', 'usulan_kegiatan_asb', 'Opd_pengusul', '2025-07-05 16:00:09', '[]', '[]'),
(10588, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-05 16:00:20', '[]', '[]'),
(10589, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'getDataLokasi', '2025-07-05 16:22:21', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10590, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'data', '2025-07-05 16:22:21', '[]', '[]'),
(10591, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-05 16:22:26', '[]', '[]'),
(10592, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_asb', 'usulan_kegiatan_asb', 'getData', '2025-07-05 23:18:20', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10593, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-05 23:18:47', '[]', '[]'),
(10594, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_asb', 'usulan_kegiatan_asb', 'getData', '2025-07-06 00:32:48', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10595, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-06 00:33:00', '[]', '[]'),
(10596, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-06 01:12:43', '[]', '[]'),
(10597, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-06 01:50:51', '[]', '[]'),
(10598, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_asb', 'usulan_kegiatan_asb', 'getData', '2025-07-07 01:33:13', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10599, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-07 01:34:16', '[]', '[]'),
(10600, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-07 01:58:28', '[]', '[]'),
(10601, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-07 02:43:53', '[]', '[]'),
(10602, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_asb_detail', 'usulan_kegiatan_asb_detail', 'getData', '2025-07-07 11:40:16', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10603, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-07 11:41:12', '[]', '[]'),
(10604, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-09 14:02:34', '[]', '[]'),
(10605, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_asb_detail', 'usulan_kegiatan_asb_detail', 'form', '2025-07-12 13:37:30', '[]', '{\"id\":\"dS9va29KY1B4YkI5OGYyNDg5c1RNQT09\"}'),
(10606, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_asb_detail', 'usulan_kegiatan_asb_detail', 'kegiatan', '2025-07-12 13:37:32', '[]', '[]'),
(10607, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_asb_detail', 'usulan_kegiatan_asb_detail', 'getById', '2025-07-12 13:37:34', '[]', '{\"id\":\"dS9va29KY1B4YkI5OGYyNDg5c1RNQT09\"}'),
(10608, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_asb_detail', 'usulan_kegiatan_asb_detail', 'kegiatan', '2025-07-12 13:37:34', '[]', '[]'),
(10609, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'data', '2025-07-18 15:06:49', '[]', '[]'),
(10610, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'getDataLokasi', '2025-07-18 15:06:50', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10611, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-18 15:07:16', '[]', '[]'),
(10612, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'data', '2025-07-22 23:48:28', '[]', '[]'),
(10613, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'getDataLokasi', '2025-07-22 23:48:29', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10614, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-22 23:59:12', '[]', '[]'),
(10615, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'getDataLokasi', '2025-07-26 03:37:00', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10616, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'data', '2025-07-26 03:37:01', '[]', '[]'),
(10617, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-26 03:37:44', '[]', '[]'),
(10618, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'data', '2025-07-26 04:25:25', '[]', '[]'),
(10619, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'getDataLokasi', '2025-07-26 04:25:25', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10620, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'data', '2025-07-26 04:39:02', '[]', '[]'),
(10621, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'getDataLokasi', '2025-07-26 04:39:02', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10622, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'getDataLokasi', '2025-07-26 04:42:44', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10623, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'data', '2025-07-26 04:42:44', '[]', '[]'),
(10624, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-26 04:42:52', '[]', '[]'),
(10625, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-26 04:46:57', '[]', '[]'),
(10626, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-26 04:55:32', '[]', '[]'),
(10627, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-26 04:58:03', '[]', '[]'),
(10628, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-26 05:33:36', '[]', '[]'),
(10629, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-26 05:33:43', '[]', '[]'),
(10630, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-26 05:34:33', '[]', '[]'),
(10631, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-26 05:42:43', '[]', '[]'),
(10632, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-26 05:42:49', '[]', '[]'),
(10633, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'data', '2025-07-26 17:50:10', '[]', '[]');
INSERT INTO `users_log` (`id`, `users_id`, `name`, `email`, `ip`, `browser`, `folder_access`, `controller_name`, `methode`, `access_time`, `post_data`, `get_data`) VALUES
(10634, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'getDataLokasi', '2025-07-26 17:50:10', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10635, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-26 17:54:27', '[]', '[]'),
(10636, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-26 18:32:36', '[]', '[]'),
(10637, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-26 18:39:19', '[]', '[]'),
(10638, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-26 18:41:47', '[]', '[]'),
(10639, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-26 18:41:58', '[]', '[]'),
(10640, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-26 18:55:59', '[]', '[]'),
(10641, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'data', '2025-07-26 18:56:01', '[]', '[]'),
(10642, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'getDataLokasi', '2025-07-26 18:56:01', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10643, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'manajemen_dashboard', 'manajemen_dashboard', 'kegiatan', '2025-07-26 18:56:13', '[]', '[]'),
(10644, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'manajemen_dashboard', 'manajemen_dashboard', 'getById', '2025-07-26 18:56:14', '[]', '[]'),
(10645, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'manajemen_dashboard', 'manajemen_dashboard', 'kegiatan', '2025-07-26 18:56:14', '[]', '[]'),
(10646, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'data', '2025-07-26 18:57:29', '[]', '[]'),
(10647, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'getDataLokasi', '2025-07-26 18:57:29', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10648, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-26 18:57:35', '[]', '[]'),
(10649, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', 'dashboard', 'dashboard', 'getDataLokasi', '2025-07-26 18:58:36', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10650, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', 'dashboard', 'dashboard', 'data', '2025-07-26 18:58:36', '[]', '[]'),
(10651, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', 'login', 'login', 'masuk', '2025-07-26 18:58:46', '[]', '[]'),
(10652, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', 'login', 'login', 'masuk', '2025-07-26 18:59:55', '[]', '[]'),
(10653, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-26 20:11:31', '[]', '[]'),
(10654, 32, 'PUPR', 'pupr@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'data', '2025-07-26 20:11:33', '[]', '[]'),
(10655, 32, 'PUPR', 'pupr@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'getDataLokasi', '2025-07-26 20:11:33', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10656, 32, 'PUPR', 'pupr@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'bidang_teknis', 'bidang_teknis', 'getData', '2025-07-26 20:11:35', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10657, 32, 'PUPR', 'pupr@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'opd', 'opd', 'getData', '2025-07-26 20:11:47', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10658, 32, 'PUPR', 'pupr@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'logout', 'login', 'logout', '2025-07-26 20:14:03', '[]', '[]'),
(10659, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-26 20:14:15', '[]', '[]'),
(10660, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'getDataLokasi', '2025-07-26 20:14:16', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10661, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'data', '2025-07-26 20:14:16', '[]', '[]'),
(10662, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'getDataLokasi', '2025-07-26 20:15:54', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10663, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'data', '2025-07-26 20:15:54', '[]', '[]'),
(10664, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'data', '2025-07-26 20:16:28', '[]', '[]'),
(10665, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'getDataLokasi', '2025-07-26 20:16:28', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10666, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'getDataLokasi', '2025-07-26 20:16:36', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10667, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'data', '2025-07-26 20:16:36', '[]', '[]'),
(10668, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'logout', 'login', 'logout', '2025-07-26 20:17:16', '[]', '[]'),
(10669, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-26 20:17:35', '[]', '[]'),
(10670, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'data', '2025-07-26 20:17:35', '[]', '[]'),
(10671, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'getDataLokasi', '2025-07-26 20:17:35', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10672, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'bidang_teknis', 'bidang_teknis', 'getData', '2025-07-26 20:17:38', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10673, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'opd', 'opd', 'getData', '2025-07-26 20:17:42', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10674, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'jenis_item', 'jenis_item', 'getData', '2025-07-26 20:17:43', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10675, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'kelompok_item', 'kelompok_item', 'getData', '2025-07-26 20:17:44', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10676, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'spesifikasi_item', 'spesifikasi_item', 'getData', '2025-07-26 20:17:47', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10677, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_spesifikasi_item', 'usulan_spesifikasi_item', 'getData', '2025-07-26 20:17:49', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10678, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_hspk', 'usulan_kegiatan_hspk', 'getData', '2025-07-26 20:17:55', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10679, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_asb', 'usulan_kegiatan_asb', 'getData', '2025-07-26 20:17:57', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10680, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_asb_detail', 'usulan_kegiatan_asb_detail', 'getData', '2025-07-26 20:17:59', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10681, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_asb', 'usulan_kegiatan_asb', 'getData', '2025-07-26 20:18:12', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10682, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_spesifikasi_item', 'usulan_spesifikasi_item', 'getData', '2025-07-26 20:18:42', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10683, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_hspk', 'usulan_kegiatan_hspk', 'getData', '2025-07-26 20:18:47', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10684, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_spesifikasi_item', 'usulan_spesifikasi_item', 'getData', '2025-07-26 20:18:49', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10685, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'logout', 'login', 'logout', '2025-07-26 20:22:55', '[]', '[]'),
(10686, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-26 20:23:03', '[]', '[]'),
(10687, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'data', '2025-07-26 20:23:04', '[]', '[]'),
(10688, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'getDataLokasi', '2025-07-26 20:23:04', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10689, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'bidang_teknis', 'bidang_teknis', 'getData', '2025-07-26 20:23:06', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10690, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_spesifikasi_item', 'usulan_spesifikasi_item', 'getData', '2025-07-26 20:23:12', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10691, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_spesifikasi_item', 'usulan_spesifikasi_item', 'getData', '2025-07-26 20:24:01', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10692, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_spesifikasi_item', 'usulan_spesifikasi_item', 'form', '2025-07-26 20:24:02', '[]', '[]'),
(10693, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_spesifikasi_item', 'usulan_spesifikasi_item', 'Opd', '2025-07-26 20:24:03', '[]', '[]'),
(10694, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_spesifikasi_item', 'usulan_spesifikasi_item', 'kel_item', '2025-07-26 20:24:03', '[]', '[]'),
(10695, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_spesifikasi_item', 'usulan_spesifikasi_item', 'saveData', '2025-07-26 20:24:15', '{\"idJenisItem\":\"7\",\"idSpesifikasi\":\"1\",\"UraianSpesifikasi\":\"Batu krikil 4x4\",\"satuan\":\"m2\",\"TahunHarga\":\"2027\",\"harga\":\"500000\",\"idOpd\":\"A0001\"}', '[]'),
(10696, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_spesifikasi_item', 'usulan_spesifikasi_item', 'form', '2025-07-26 20:24:16', '[]', '[]'),
(10697, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_spesifikasi_item', 'usulan_spesifikasi_item', 'Opd', '2025-07-26 20:24:16', '[]', '[]'),
(10698, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_spesifikasi_item', 'usulan_spesifikasi_item', 'kel_item', '2025-07-26 20:24:16', '[]', '[]'),
(10699, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_spesifikasi_item', 'usulan_spesifikasi_item', 'getData', '2025-07-26 20:24:18', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10700, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_hspk', 'usulan_kegiatan_hspk', 'getData', '2025-07-26 20:24:56', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10701, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_hspk', 'usulan_kegiatan_hspk', 'getData', '2025-07-26 20:25:21', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10702, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_hspk', 'usulan_kegiatan_hspk', 'form', '2025-07-26 20:25:22', '[]', '[]'),
(10703, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'kegiatan_hspk', 'kegiatan_hspk', 'bidang_teknis', '2025-07-26 20:25:22', '[]', '[]'),
(10704, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_spesifikasi_item', 'usulan_spesifikasi_item', 'Opd', '2025-07-26 20:25:22', '[]', '[]'),
(10705, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_hspk', 'usulan_kegiatan_hspk', 'saveData', '2025-07-26 20:25:31', '{\"idKegiatan\":\"A0001\",\"UraianKegiatan\":\"sadawd\",\"idBidangTeknis\":\"BT0001\",\"satuan\":\"wad\",\"tahunPekerjaan\":\"2022\",\"idOpd\":\"A0004\"}', '[]'),
(10706, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_hspk', 'usulan_kegiatan_hspk', 'saveData', '2025-07-26 20:25:35', '{\"idKegiatan\":\"A0006\",\"UraianKegiatan\":\"sadawd\",\"idBidangTeknis\":\"BT0001\",\"satuan\":\"wad\",\"tahunPekerjaan\":\"2022\",\"idOpd\":\"A0004\"}', '[]'),
(10707, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_hspk', 'usulan_kegiatan_hspk', 'saveData', '2025-07-26 20:25:39', '{\"idKegiatan\":\"A00010\",\"UraianKegiatan\":\"sadawd\",\"idBidangTeknis\":\"BT0001\",\"satuan\":\"wad\",\"tahunPekerjaan\":\"2022\",\"idOpd\":\"A0004\"}', '[]'),
(10708, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_hspk', 'usulan_kegiatan_hspk', 'form', '2025-07-26 20:25:39', '[]', '[]'),
(10709, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'kegiatan_hspk', 'kegiatan_hspk', 'bidang_teknis', '2025-07-26 20:25:40', '[]', '[]'),
(10710, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_spesifikasi_item', 'usulan_spesifikasi_item', 'Opd', '2025-07-26 20:25:40', '[]', '[]'),
(10711, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_hspk', 'usulan_kegiatan_hspk', 'getData', '2025-07-26 20:25:41', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10712, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_hspk_detail', 'usulan_kegiatan_hspk_detail', 'getData', '2025-07-26 20:25:54', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10713, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_hspk', 'usulan_kegiatan_hspk', 'getData', '2025-07-26 20:25:56', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10714, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_hspk_detail', 'usulan_kegiatan_hspk_detail', 'getData', '2025-07-26 20:25:58', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10715, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_hspk_detail', 'usulan_kegiatan_hspk_detail', 'getData', '2025-07-26 20:26:32', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10716, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_hspk_detail', 'usulan_kegiatan_hspk_detail', 'form', '2025-07-26 20:26:34', '[]', '[]'),
(10717, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_hspk_detail', 'usulan_kegiatan_hspk_detail', 'kegiatan', '2025-07-26 20:26:35', '[]', '[]'),
(10718, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_hspk_detail', 'usulan_kegiatan_hspk_detail', 'saveData', '2025-07-26 20:26:40', '{\"id_thn_kegiatan\":\"4\",\"id_thn_harga\":\"5\",\"total_item\":\"1\"}', '[]'),
(10719, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_hspk_detail', 'usulan_kegiatan_hspk_detail', 'form', '2025-07-26 20:26:41', '[]', '[]'),
(10720, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_hspk_detail', 'usulan_kegiatan_hspk_detail', 'kegiatan', '2025-07-26 20:26:41', '[]', '[]'),
(10721, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_hspk_detail', 'usulan_kegiatan_hspk_detail', 'getData', '2025-07-26 20:26:42', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10722, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_asb', 'usulan_kegiatan_asb', 'getData', '2025-07-26 20:26:47', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10723, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_hspk', 'usulan_kegiatan_hspk', 'getData', '2025-07-26 20:26:49', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10724, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_hspk_detail', 'usulan_kegiatan_hspk_detail', 'getData', '2025-07-26 20:26:51', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10725, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_asb', 'usulan_kegiatan_asb', 'getData', '2025-07-26 20:26:53', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10726, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_asb', 'usulan_kegiatan_asb', 'getData', '2025-07-26 20:27:17', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10727, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_asb', 'usulan_kegiatan_asb', 'form', '2025-07-26 20:27:19', '[]', '[]'),
(10728, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_asb', 'usulan_kegiatan_asb', 'Opd_pengusul', '2025-07-26 20:27:19', '[]', '[]'),
(10729, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_asb', 'usulan_kegiatan_asb', 'Opd', '2025-07-26 20:27:19', '[]', '[]'),
(10730, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_asb', 'usulan_kegiatan_asb', 'saveData', '2025-07-26 20:27:34', '{\"idASB\":\"ASB010\",\"idOpdPengusul\":\"A0001\",\"idOpd\":\"A0001\",\"UraianKegiatan\":\"Testo\",\"satuan\":\"m\\u00b3\",\"tahunASB\":\"2027\"}', '[]'),
(10731, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_asb', 'usulan_kegiatan_asb', 'form', '2025-07-26 20:27:35', '[]', '[]'),
(10732, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_asb', 'usulan_kegiatan_asb', 'Opd_pengusul', '2025-07-26 20:27:35', '[]', '[]'),
(10733, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_asb', 'usulan_kegiatan_asb', 'Opd', '2025-07-26 20:27:35', '[]', '[]'),
(10734, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_asb', 'usulan_kegiatan_asb', 'getData', '2025-07-26 20:27:37', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10735, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_asb_detail', 'usulan_kegiatan_asb_detail', 'getData', '2025-07-26 20:27:38', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10736, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_asb', 'usulan_kegiatan_asb', 'getData', '2025-07-26 20:27:40', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10737, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_asb_detail', 'usulan_kegiatan_asb_detail', 'getData', '2025-07-26 20:27:41', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10738, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_asb_detail', 'usulan_kegiatan_asb_detail', 'getData', '2025-07-26 20:28:06', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10739, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_asb_detail', 'usulan_kegiatan_asb_detail', 'form', '2025-07-26 20:28:07', '[]', '[]'),
(10740, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_asb_detail', 'usulan_kegiatan_asb_detail', 'kegiatan', '2025-07-26 20:28:07', '[]', '[]'),
(10741, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_asb_detail', 'usulan_kegiatan_asb_detail', 'saveData', '2025-07-26 20:28:13', '{\"id_standar_biaya_thn\":\"13\",\"id_thn_pekerjaan_detail\":\"20,10\"}', '[]'),
(10742, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_asb_detail', 'usulan_kegiatan_asb_detail', 'form', '2025-07-26 20:28:13', '[]', '[]'),
(10743, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_asb_detail', 'usulan_kegiatan_asb_detail', 'kegiatan', '2025-07-26 20:28:14', '[]', '[]'),
(10744, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_asb_detail', 'usulan_kegiatan_asb_detail', 'getData', '2025-07-26 20:28:15', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10745, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_asb_detail', 'usulan_kegiatan_asb_detail', 'getData', '2025-07-26 20:29:04', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10746, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'lokasi_toko', 'lokasi_toko', 'getData', '2025-07-26 20:29:07', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10747, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'lokasi_toko', 'lokasi_toko', 'getAll', '2025-07-26 20:29:07', '[]', '[]'),
(10748, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'lokasi_toko', 'lokasi_toko', 'getData', '2025-07-26 20:29:40', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10749, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'lokasi_toko', 'lokasi_toko', 'getAll', '2025-07-26 20:29:40', '[]', '[]'),
(10750, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'lokasi_toko', 'lokasi_toko', 'getData', '2025-07-26 20:29:47', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10751, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'lokasi_toko', 'lokasi_toko', 'getAll', '2025-07-26 20:29:47', '[]', '[]'),
(10752, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'usulan_kegiatan_asb_detail', 'usulan_kegiatan_asb_detail', 'getData', '2025-07-26 20:29:51', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10753, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'lokasi_toko', 'lokasi_toko', 'getData', '2025-07-26 20:29:52', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10754, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'lokasi_toko', 'lokasi_toko', 'getAll', '2025-07-26 20:29:52', '[]', '[]'),
(10755, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'lokasi_toko', 'lokasi_toko', 'getData', '2025-07-26 20:30:15', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10756, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'lokasi_toko', 'lokasi_toko', 'getAll', '2025-07-26 20:30:15', '[]', '[]'),
(10757, 34, 'DINKES', 'dinkes@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'logout', 'login', 'logout', '2025-07-26 20:30:18', '[]', '[]'),
(10758, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-07-26 20:30:27', '[]', '[]'),
(10759, 35, 'DISDIK', 'disdik@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'getDataLokasi', '2025-07-26 20:30:27', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10760, 35, 'DISDIK', 'disdik@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'data', '2025-07-26 20:30:27', '[]', '[]'),
(10761, 35, 'DISDIK', 'disdik@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'bidang_teknis', 'bidang_teknis', 'getData', '2025-07-26 20:30:29', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10762, 35, 'DISDIK', 'disdik@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'lokasi_toko', 'lokasi_toko', 'getData', '2025-07-26 20:30:31', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10763, 35, 'DISDIK', 'disdik@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'lokasi_toko', 'lokasi_toko', 'getAll', '2025-07-26 20:30:31', '[]', '[]'),
(10764, 35, 'DISDIK', 'disdik@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'lokasi_toko', 'lokasi_toko', 'form', '2025-07-26 20:30:34', '[]', '[]'),
(10765, 35, 'DISDIK', 'disdik@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'bidang_teknis', 'bidang_teknis', 'getData', '2025-07-26 20:30:38', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10766, 35, 'DISDIK', 'disdik@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'lokasi_toko', 'lokasi_toko', 'getData', '2025-07-26 20:30:40', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10767, 35, 'DISDIK', 'disdik@gmail.com', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'lokasi_toko', 'lokasi_toko', 'getAll', '2025-07-26 20:30:40', '[]', '[]'),
(10768, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'getDataLokasi', '2025-08-04 10:11:43', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10769, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'data', '2025-08-04 10:11:44', '[]', '[]'),
(10770, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-08-04 10:12:12', '[]', '[]'),
(10771, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'data', '2025-08-12 19:57:56', '[]', '[]'),
(10772, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'getDataLokasi', '2025-08-12 19:57:56', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10773, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-08-12 19:58:06', '[]', '[]'),
(10774, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'kegiatan_hspk_detail', 'kegiatan_hspk_detail', 'getById', '2025-08-12 21:28:41', '[]', '{\"id\":\"dS9va29KY1B4YkI5OGYyNDg5c1RNQT09\"}'),
(10775, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'kegiatan_hspk_detail', 'kegiatan_hspk_detail', 'kegiatan', '2025-08-12 21:28:42', '[]', '[]'),
(10776, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'kegiatan_hspk_detail', 'kegiatan_hspk_detail', 'getData', '2025-08-12 21:29:59', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10777, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'kegiatan_hspk_detail', 'kegiatan_hspk_detail', 'getData', '2025-08-12 21:30:21', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10778, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'kegiatan_hspk_detail', 'kegiatan_hspk_detail', 'getData', '2025-08-12 21:30:23', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10779, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'data', '2025-08-12 21:30:29', '[]', '[]'),
(10780, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'getDataLokasi', '2025-08-12 21:30:29', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10781, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-08-12 21:30:42', '[]', '[]'),
(10782, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'data', '2025-08-13 10:31:48', '[]', '[]'),
(10783, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'getDataLokasi', '2025-08-13 10:31:48', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10784, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'data', '2025-08-13 10:32:06', '[]', '[]'),
(10785, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'dashboard', 'dashboard', 'getDataLokasi', '2025-08-13 10:32:06', '[]', '{\"keyword\":\"{}\",\"limit\":\"10\",\"offset\":\"1\"}'),
(10786, NULL, NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', 'login', 'login', 'masuk', '2025-08-13 10:33:24', '[]', '[]');

-- --------------------------------------------------------

--
-- Table structure for table `users_role`
--

CREATE TABLE `users_role` (
  `id` int(11) NOT NULL,
  `name` varchar(30) DEFAULT NULL,
  `role_access` text DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_role`
--

INSERT INTO `users_role` (`id`, `name`, `role_access`, `updated_at`, `created_at`) VALUES
(1, 'admin', '{\"bidang_teknis\":{\"bidang_teknis\":\"on\",\"accessadd_bidang_teknis\":\"on\",\"accessedit_bidang_teknis\":\"on\",\"accessdelete_bidang_teknis\":\"on\"},\"jenis_item\":{\"jenis_item\":\"on\",\"accessadd_jenis_item\":\"on\",\"accessedit_jenis_item\":\"on\",\"accessdelete_jenis_item\":\"on\"},\"kegiatan_asb\":{\"kegiatan_asb\":\"on\",\"accessadd_kegiatan_asb\":\"on\",\"accessedit_kegiatan_asb\":\"on\",\"accessdelete_kegiatan_asb\":\"on\"},\"kegiatan_asb_detail\":{\"kegiatan_asb_detail\":\"on\",\"accessadd_kegiatan_asb_detail\":\"on\",\"accessedit_kegiatan_asb_detail\":\"on\",\"accessdelete_kegiatan_asb_detail\":\"on\"},\"kegiatan_hspk\":{\"kegiatan_hspk\":\"on\",\"accessadd_kegiatan_hspk\":\"on\",\"accessedit_kegiatan_hspk\":\"on\",\"accessdelete_kegiatan_hspk\":\"on\"},\"kegiatan_hspk_detail\":{\"kegiatan_hspk_detail\":\"on\",\"accessadd_kegiatan_hspk_detail\":\"on\",\"accessedit_kegiatan_hspk_detail\":\"on\",\"accessdelete_kegiatan_hspk_detail\":\"on\"},\"kelompok_item\":{\"kelompok_item\":\"on\",\"accessadd_kelompok_item\":\"on\",\"accessedit_kelompok_item\":\"on\",\"accessdelete_kelompok_item\":\"on\"},\"lokasi_toko\":{\"lokasi_toko\":\"on\",\"accessadd_lokasi_toko\":\"on\",\"accessedit_lokasi_toko\":\"on\",\"accessdelete_lokasi_toko\":\"on\"},\"manajemen_dashboard\":{\"manajemen_dashboard\":\"on\",\"accessadd_manajemen_dashboard\":\"on\",\"accessedit_manajemen_dashboard\":\"on\",\"accessdelete_manajemen_dashboard\":\"on\"},\"opd\":{\"opd\":\"on\",\"accessadd_opd\":\"on\",\"accessedit_opd\":\"on\",\"accessdelete_opd\":\"on\"},\"perkiraan_hps\":{\"perkiraan_hps\":\"off\",\"accessadd_perkiraan_hps\":\"off\",\"accessedit_perkiraan_hps\":\"off\",\"accessdelete_perkiraan_hps\":\"off\"},\"spesifikasi_harga\":{\"spesifikasi_harga\":\"on\",\"accessadd_spesifikasi_harga\":\"on\",\"accessedit_spesifikasi_harga\":\"on\",\"accessdelete_spesifikasi_harga\":\"on\"},\"spesifikasi_item\":{\"spesifikasi_item\":\"on\",\"accessadd_spesifikasi_item\":\"on\",\"accessedit_spesifikasi_item\":\"on\",\"accessdelete_spesifikasi_item\":\"on\"},\"tahun_kegiatan_asb\":{\"tahun_kegiatan_asb\":\"on\",\"accessadd_tahun_kegiatan_asb\":\"on\",\"accessedit_tahun_kegiatan_asb\":\"on\",\"accessdelete_tahun_kegiatan_asb\":\"on\"},\"tahun_kegiatan_hspk\":{\"tahun_kegiatan_hspk\":\"on\",\"accessadd_tahun_kegiatan_hspk\":\"on\",\"accessedit_tahun_kegiatan_hspk\":\"on\",\"accessdelete_tahun_kegiatan_hspk\":\"on\"},\"user_log\":{\"user_log\":\"on\"},\"user_manage\":{\"user_manage\":\"on\",\"accessadd_user_manage\":\"on\",\"accessedit_user_manage\":\"on\",\"accessdelete_user_manage\":\"on\"},\"user_role\":{\"user_role\":\"on\",\"accessadd_user_role\":\"on\",\"accessedit_user_role\":\"on\",\"accessdelete_user_role\":\"on\"},\"usulan_kegiatan_asb\":{\"usulan_kegiatan_asb\":\"on\",\"accessadd_usulan_kegiatan_asb\":\"on\",\"accessedit_usulan_kegiatan_asb\":\"on\",\"accessdelete_usulan_kegiatan_asb\":\"on\"},\"usulan_kegiatan_asb_detail\":{\"usulan_kegiatan_asb_detail\":\"on\",\"accessadd_usulan_kegiatan_asb_detail\":\"on\",\"accessedit_usulan_kegiatan_asb_detail\":\"on\",\"accessdelete_usulan_kegiatan_asb_detail\":\"on\"},\"usulan_kegiatan_hspk\":{\"usulan_kegiatan_hspk\":\"on\",\"accessadd_usulan_kegiatan_hspk\":\"on\",\"accessedit_usulan_kegiatan_hspk\":\"on\",\"accessdelete_usulan_kegiatan_hspk\":\"on\"},\"usulan_kegiatan_hspk_detail\":{\"usulan_kegiatan_hspk_detail\":\"on\",\"accessadd_usulan_kegiatan_hspk_detail\":\"on\",\"accessedit_usulan_kegiatan_hspk_detail\":\"on\",\"accessdelete_usulan_kegiatan_hspk_detail\":\"on\"},\"usulan_spesifikasi_item\":{\"usulan_spesifikasi_item\":\"on\",\"accessadd_usulan_spesifikasi_item\":\"on\",\"accessedit_usulan_spesifikasi_item\":\"on\",\"accessdelete_usulan_spesifikasi_item\":\"on\"},\"kelola_usulan\":{\"akses_ubah_status\":\"on\"}}', '2025-07-06 01:50:43', '2023-10-30 13:04:44'),
(2, 'Administrator', '{\"bidang_teknis\":{\"bidang_teknis\":\"off\",\"accessadd_bidang_teknis\":\"off\",\"accessedit_bidang_teknis\":\"off\",\"accessdelete_bidang_teknis\":\"off\"},\"jenis_item\":{\"jenis_item\":\"on\",\"accessadd_jenis_item\":\"on\",\"accessedit_jenis_item\":\"on\",\"accessdelete_jenis_item\":\"on\"},\"kegiatan_asb\":{\"kegiatan_asb\":\"on\",\"accessadd_kegiatan_asb\":\"on\",\"accessedit_kegiatan_asb\":\"on\",\"accessdelete_kegiatan_asb\":\"on\"},\"kegiatan_asb_detail\":{\"kegiatan_asb_detail\":\"on\",\"accessadd_kegiatan_asb_detail\":\"on\",\"accessedit_kegiatan_asb_detail\":\"on\",\"accessdelete_kegiatan_asb_detail\":\"on\"},\"kegiatan_hspk\":{\"kegiatan_hspk\":\"on\",\"accessadd_kegiatan_hspk\":\"on\",\"accessedit_kegiatan_hspk\":\"on\",\"accessdelete_kegiatan_hspk\":\"on\"},\"kegiatan_hspk_detail\":{\"kegiatan_hspk_detail\":\"on\",\"accessadd_kegiatan_hspk_detail\":\"on\",\"accessedit_kegiatan_hspk_detail\":\"on\",\"accessdelete_kegiatan_hspk_detail\":\"on\"},\"kelompok_item\":{\"kelompok_item\":\"on\",\"accessadd_kelompok_item\":\"on\",\"accessedit_kelompok_item\":\"on\",\"accessdelete_kelompok_item\":\"on\"},\"lokasi_toko\":{\"lokasi_toko\":\"on\",\"accessadd_lokasi_toko\":\"on\",\"accessedit_lokasi_toko\":\"on\",\"accessdelete_lokasi_toko\":\"on\"},\"manajemen_dashboard\":{\"manajemen_dashboard\":\"off\",\"accessadd_manajemen_dashboard\":\"off\",\"accessedit_manajemen_dashboard\":\"off\",\"accessdelete_manajemen_dashboard\":\"off\"},\"opd\":{\"opd\":\"off\",\"accessadd_opd\":\"off\",\"accessedit_opd\":\"off\",\"accessdelete_opd\":\"off\"},\"perkiraan_hps\":{\"perkiraan_hps\":\"off\",\"accessadd_perkiraan_hps\":\"off\",\"accessedit_perkiraan_hps\":\"off\",\"accessdelete_perkiraan_hps\":\"off\"},\"spesifikasi_harga\":{\"spesifikasi_harga\":\"on\",\"accessadd_spesifikasi_harga\":\"on\",\"accessedit_spesifikasi_harga\":\"on\",\"accessdelete_spesifikasi_harga\":\"on\"},\"spesifikasi_item\":{\"spesifikasi_item\":\"on\",\"accessadd_spesifikasi_item\":\"on\",\"accessedit_spesifikasi_item\":\"on\",\"accessdelete_spesifikasi_item\":\"on\"},\"tahun_kegiatan_asb\":{\"tahun_kegiatan_asb\":\"on\",\"accessadd_tahun_kegiatan_asb\":\"on\",\"accessedit_tahun_kegiatan_asb\":\"on\",\"accessdelete_tahun_kegiatan_asb\":\"on\"},\"tahun_kegiatan_hspk\":{\"tahun_kegiatan_hspk\":\"on\",\"accessadd_tahun_kegiatan_hspk\":\"on\",\"accessedit_tahun_kegiatan_hspk\":\"on\",\"accessdelete_tahun_kegiatan_hspk\":\"on\"},\"user_log\":{\"user_log\":\"on\"},\"user_manage\":{\"user_manage\":\"on\",\"accessadd_user_manage\":\"on\",\"accessedit_user_manage\":\"on\",\"accessdelete_user_manage\":\"on\"},\"user_role\":{\"user_role\":\"on\",\"accessadd_user_role\":\"on\",\"accessedit_user_role\":\"on\",\"accessdelete_user_role\":\"on\"},\"usulan_spesifikasi_item\":{\"usulan_spesifikasi_item\":\"on\",\"accessadd_usulan_spesifikasi_item\":\"on\",\"accessedit_usulan_spesifikasi_item\":\"on\",\"accessdelete_usulan_spesifikasi_item\":\"on\"},\"kelola_usulan\":{\"akses_ubah_status\":\"off\"}}', '2025-07-01 20:57:16', '2023-10-30 14:16:08'),
(4, 'Vendor', '{\"jenis_item\":{\"jenis_item\":\"on\",\"accessadd_jenis_item\":\"off\",\"accessedit_jenis_item\":\"off\",\"accessdelete_jenis_item\":\"off\"},\"kegiatan_asb\":{\"kegiatan_asb\":\"on\",\"accessadd_kegiatan_asb\":\"off\",\"accessedit_kegiatan_asb\":\"off\",\"accessdelete_kegiatan_asb\":\"off\"},\"kegiatan_asb_detail\":{\"kegiatan_asb_detail\":\"on\",\"accessadd_kegiatan_asb_detail\":\"off\",\"accessedit_kegiatan_asb_detail\":\"off\",\"accessdelete_kegiatan_asb_detail\":\"off\"},\"kegiatan_hspk\":{\"kegiatan_hspk\":\"on\",\"accessadd_kegiatan_hspk\":\"off\",\"accessedit_kegiatan_hspk\":\"off\",\"accessdelete_kegiatan_hspk\":\"off\"},\"kegiatan_hspk_detail\":{\"kegiatan_hspk_detail\":\"on\",\"accessadd_kegiatan_hspk_detail\":\"off\",\"accessedit_kegiatan_hspk_detail\":\"off\",\"accessdelete_kegiatan_hspk_detail\":\"off\"},\"kelompok_item\":{\"kelompok_item\":\"on\",\"accessadd_kelompok_item\":\"off\",\"accessedit_kelompok_item\":\"off\",\"accessdelete_kelompok_item\":\"off\"},\"manajemen_dashboard\":{\"manajemen_dashboard\":\"off\",\"accessadd_manajemen_dashboard\":\"off\",\"accessedit_manajemen_dashboard\":\"off\",\"accessdelete_manajemen_dashboard\":\"off\"},\"perkiraan_hps\":{\"perkiraan_hps\":\"off\",\"accessadd_perkiraan_hps\":\"off\",\"accessedit_perkiraan_hps\":\"off\",\"accessdelete_perkiraan_hps\":\"off\"},\"spesifikasi_harga\":{\"spesifikasi_harga\":\"on\",\"accessadd_spesifikasi_harga\":\"off\",\"accessedit_spesifikasi_harga\":\"off\",\"accessdelete_spesifikasi_harga\":\"off\"},\"spesifikasi_item\":{\"spesifikasi_item\":\"on\",\"accessadd_spesifikasi_item\":\"off\",\"accessedit_spesifikasi_item\":\"off\",\"accessdelete_spesifikasi_item\":\"off\"},\"tahun_kegiatan_asb\":{\"tahun_kegiatan_asb\":\"on\",\"accessadd_tahun_kegiatan_asb\":\"off\",\"accessedit_tahun_kegiatan_asb\":\"off\",\"accessdelete_tahun_kegiatan_asb\":\"off\"},\"tahun_kegiatan_hspk\":{\"tahun_kegiatan_hspk\":\"on\",\"accessadd_tahun_kegiatan_hspk\":\"off\",\"accessedit_tahun_kegiatan_hspk\":\"off\",\"accessdelete_tahun_kegiatan_hspk\":\"off\"},\"user_log\":{\"user_log\":\"off\"},\"user_manage\":{\"user_manage\":\"off\",\"accessadd_user_manage\":\"off\",\"accessedit_user_manage\":\"off\",\"accessdelete_user_manage\":\"off\"},\"user_role\":{\"user_role\":\"off\",\"accessadd_user_role\":\"off\",\"accessedit_user_role\":\"off\",\"accessdelete_user_role\":\"off\"}}', '2023-11-20 14:01:05', '2023-10-30 09:47:59'),
(8, 'Bag Irigasi', '{\"jenis_item\":{\"jenis_item\":\"off\",\"accessadd_jenis_item\":\"off\",\"accessedit_jenis_item\":\"off\",\"accessdelete_jenis_item\":\"off\"},\"kegiatan_asb\":{\"kegiatan_asb\":\"off\",\"accessadd_kegiatan_asb\":\"off\",\"accessedit_kegiatan_asb\":\"off\",\"accessdelete_kegiatan_asb\":\"off\"},\"kegiatan_asb_detail\":{\"kegiatan_asb_detail\":\"off\",\"accessadd_kegiatan_asb_detail\":\"off\",\"accessedit_kegiatan_asb_detail\":\"off\",\"accessdelete_kegiatan_asb_detail\":\"off\"},\"kegiatan_hspk\":{\"kegiatan_hspk\":\"off\",\"accessadd_kegiatan_hspk\":\"off\",\"accessedit_kegiatan_hspk\":\"off\",\"accessdelete_kegiatan_hspk\":\"off\"},\"kegiatan_hspk_detail\":{\"kegiatan_hspk_detail\":\"off\",\"accessadd_kegiatan_hspk_detail\":\"off\",\"accessedit_kegiatan_hspk_detail\":\"off\",\"accessdelete_kegiatan_hspk_detail\":\"off\"},\"kelompok_item\":{\"kelompok_item\":\"off\",\"accessadd_kelompok_item\":\"off\",\"accessedit_kelompok_item\":\"off\",\"accessdelete_kelompok_item\":\"off\"},\"manajemen_dashboard\":{\"manajemen_dashboard\":\"off\",\"accessadd_manajemen_dashboard\":\"off\",\"accessedit_manajemen_dashboard\":\"off\",\"accessdelete_manajemen_dashboard\":\"off\"},\"perkiraan_hps\":{\"perkiraan_hps\":\"off\",\"accessadd_perkiraan_hps\":\"off\",\"accessedit_perkiraan_hps\":\"off\",\"accessdelete_perkiraan_hps\":\"off\"},\"spesifikasi_harga\":{\"spesifikasi_harga\":\"off\",\"accessadd_spesifikasi_harga\":\"off\",\"accessedit_spesifikasi_harga\":\"off\",\"accessdelete_spesifikasi_harga\":\"off\"},\"spesifikasi_item\":{\"spesifikasi_item\":\"off\",\"accessadd_spesifikasi_item\":\"off\",\"accessedit_spesifikasi_item\":\"off\",\"accessdelete_spesifikasi_item\":\"off\"},\"tahun_kegiatan_asb\":{\"tahun_kegiatan_asb\":\"off\",\"accessadd_tahun_kegiatan_asb\":\"off\",\"accessedit_tahun_kegiatan_asb\":\"off\",\"accessdelete_tahun_kegiatan_asb\":\"off\"},\"tahun_kegiatan_hspk\":{\"tahun_kegiatan_hspk\":\"off\",\"accessadd_tahun_kegiatan_hspk\":\"off\",\"accessedit_tahun_kegiatan_hspk\":\"off\",\"accessdelete_tahun_kegiatan_hspk\":\"off\"},\"user_log\":{\"user_log\":\"off\"},\"user_manage\":{\"user_manage\":\"off\",\"accessadd_user_manage\":\"off\",\"accessedit_user_manage\":\"off\",\"accessdelete_user_manage\":\"off\"},\"user_role\":{\"user_role\":\"off\",\"accessadd_user_role\":\"off\",\"accessedit_user_role\":\"off\",\"accessdelete_user_role\":\"off\"}}', '2023-11-20 14:00:59', '2023-11-13 19:13:24'),
(9, 'OPD', '{\"bidang_teknis\":{\"bidang_teknis\":\"on\",\"accessadd_bidang_teknis\":\"off\",\"accessedit_bidang_teknis\":\"off\",\"accessdelete_bidang_teknis\":\"off\"},\"jenis_item\":{\"jenis_item\":\"on\",\"accessadd_jenis_item\":\"off\",\"accessedit_jenis_item\":\"off\",\"accessdelete_jenis_item\":\"off\"},\"kegiatan_asb\":{\"kegiatan_asb\":\"on\",\"accessadd_kegiatan_asb\":\"off\",\"accessedit_kegiatan_asb\":\"off\",\"accessdelete_kegiatan_asb\":\"off\"},\"kegiatan_asb_detail\":{\"kegiatan_asb_detail\":\"on\",\"accessadd_kegiatan_asb_detail\":\"off\",\"accessedit_kegiatan_asb_detail\":\"off\",\"accessdelete_kegiatan_asb_detail\":\"off\"},\"kegiatan_hspk\":{\"kegiatan_hspk\":\"on\",\"accessadd_kegiatan_hspk\":\"off\",\"accessedit_kegiatan_hspk\":\"off\",\"accessdelete_kegiatan_hspk\":\"off\"},\"kegiatan_hspk_detail\":{\"kegiatan_hspk_detail\":\"on\",\"accessadd_kegiatan_hspk_detail\":\"off\",\"accessedit_kegiatan_hspk_detail\":\"off\",\"accessdelete_kegiatan_hspk_detail\":\"off\"},\"kelompok_item\":{\"kelompok_item\":\"on\",\"accessadd_kelompok_item\":\"off\",\"accessedit_kelompok_item\":\"off\",\"accessdelete_kelompok_item\":\"off\"},\"lokasi_toko\":{\"lokasi_toko\":\"on\",\"accessadd_lokasi_toko\":\"on\",\"accessedit_lokasi_toko\":\"on\",\"accessdelete_lokasi_toko\":\"on\"},\"manajemen_dashboard\":{\"manajemen_dashboard\":\"off\",\"accessadd_manajemen_dashboard\":\"off\",\"accessedit_manajemen_dashboard\":\"off\",\"accessdelete_manajemen_dashboard\":\"off\"},\"opd\":{\"opd\":\"on\",\"accessadd_opd\":\"off\",\"accessedit_opd\":\"off\",\"accessdelete_opd\":\"off\"},\"perkiraan_hps\":{\"perkiraan_hps\":\"on\",\"accessadd_perkiraan_hps\":\"off\",\"accessedit_perkiraan_hps\":\"off\",\"accessdelete_perkiraan_hps\":\"off\"},\"spesifikasi_harga\":{\"spesifikasi_harga\":\"on\",\"accessadd_spesifikasi_harga\":\"off\",\"accessedit_spesifikasi_harga\":\"off\",\"accessdelete_spesifikasi_harga\":\"off\"},\"spesifikasi_item\":{\"spesifikasi_item\":\"on\",\"accessadd_spesifikasi_item\":\"off\",\"accessedit_spesifikasi_item\":\"off\",\"accessdelete_spesifikasi_item\":\"off\"},\"tahun_kegiatan_asb\":{\"tahun_kegiatan_asb\":\"on\",\"accessadd_tahun_kegiatan_asb\":\"off\",\"accessedit_tahun_kegiatan_asb\":\"off\",\"accessdelete_tahun_kegiatan_asb\":\"off\"},\"tahun_kegiatan_hspk\":{\"tahun_kegiatan_hspk\":\"on\",\"accessadd_tahun_kegiatan_hspk\":\"off\",\"accessedit_tahun_kegiatan_hspk\":\"off\",\"accessdelete_tahun_kegiatan_hspk\":\"off\"},\"user_log\":{\"user_log\":\"off\"},\"user_manage\":{\"user_manage\":\"off\",\"accessadd_user_manage\":\"off\",\"accessedit_user_manage\":\"off\",\"accessdelete_user_manage\":\"off\"},\"user_role\":{\"user_role\":\"off\",\"accessadd_user_role\":\"off\",\"accessedit_user_role\":\"off\",\"accessdelete_user_role\":\"off\"},\"usulan_kegiatan_asb\":{\"usulan_kegiatan_asb\":\"on\",\"accessadd_usulan_kegiatan_asb\":\"on\",\"accessedit_usulan_kegiatan_asb\":\"off\",\"accessdelete_usulan_kegiatan_asb\":\"off\"},\"usulan_kegiatan_asb_detail\":{\"usulan_kegiatan_asb_detail\":\"on\",\"accessadd_usulan_kegiatan_asb_detail\":\"on\",\"accessedit_usulan_kegiatan_asb_detail\":\"off\",\"accessdelete_usulan_kegiatan_asb_detail\":\"off\"},\"usulan_kegiatan_hspk\":{\"usulan_kegiatan_hspk\":\"on\",\"accessadd_usulan_kegiatan_hspk\":\"on\",\"accessedit_usulan_kegiatan_hspk\":\"off\",\"accessdelete_usulan_kegiatan_hspk\":\"off\"},\"usulan_kegiatan_hspk_detail\":{\"usulan_kegiatan_hspk_detail\":\"on\",\"accessadd_usulan_kegiatan_hspk_detail\":\"on\",\"accessedit_usulan_kegiatan_hspk_detail\":\"off\",\"accessdelete_usulan_kegiatan_hspk_detail\":\"off\"},\"usulan_spesifikasi_item\":{\"usulan_spesifikasi_item\":\"on\",\"accessadd_usulan_spesifikasi_item\":\"on\",\"accessedit_usulan_spesifikasi_item\":\"off\",\"accessdelete_usulan_spesifikasi_item\":\"off\"},\"kelola_usulan\":{\"akses_ubah_status\":\"off\"}}', '2025-07-26 20:29:37', '2025-07-26 05:45:11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_bidang_teknis`
--
ALTER TABLE `tb_bidang_teknis`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idBidangTeknis` (`idBidangTeknis`),
  ADD KEY `idOpd` (`idOpd`);

--
-- Indexes for table `tb_jenis_item`
--
ALTER TABLE `tb_jenis_item`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kodeKelompok` (`idKelompokItem`,`idJenisBarang`);

--
-- Indexes for table `tb_kegiatan`
--
ALTER TABLE `tb_kegiatan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tb_kegiatan_ibfk_1` (`idBidangTeknis`);

--
-- Indexes for table `tb_kelompok_item`
--
ALTER TABLE `tb_kelompok_item`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kodeKelompok` (`IdKelItem`);

--
-- Indexes for table `tb_lokasi`
--
ALTER TABLE `tb_lokasi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_manajemen_dashboard`
--
ALTER TABLE `tb_manajemen_dashboard`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_opd`
--
ALTER TABLE `tb_opd`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idOpd` (`idOpd`);

--
-- Indexes for table `tb_spesifikasi_item`
--
ALTER TABLE `tb_spesifikasi_item`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kodeKelompok` (`idJenisItem`,`idSpesifikasi`);

--
-- Indexes for table `tb_standar_biaya`
--
ALTER TABLE `tb_standar_biaya`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idOpd` (`idOpd`);

--
-- Indexes for table `tb_standar_biaya_thn`
--
ALTER TABLE `tb_standar_biaya_thn`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_standar_biaya_thn_detail`
--
ALTER TABLE `tb_standar_biaya_thn_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_thn_harga`
--
ALTER TABLE `tb_thn_harga`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kodeKelompok` (`idSpesifikasi`,`TahunHarga`);

--
-- Indexes for table `tb_thn_kegiatan`
--
ALTER TABLE `tb_thn_kegiatan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_thn_pekerjaan_detail`
--
ALTER TABLE `tb_thn_pekerjaan_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_usulan_kegiatan`
--
ALTER TABLE `tb_usulan_kegiatan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idBidangTeknis` (`idBidangTeknis`),
  ADD KEY `tb_usulan_kegiatan_ibfk_2` (`idOpd`);

--
-- Indexes for table `tb_usulan_spesifikasi_item`
--
ALTER TABLE `tb_usulan_spesifikasi_item`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idJenisItem` (`idJenisItem`,`idSpesifikasi`),
  ADD KEY `idOpd` (`idOpd`),
  ADD KEY `idSpesifikasi` (`idSpesifikasi`);

--
-- Indexes for table `tb_usulan_standar_biaya`
--
ALTER TABLE `tb_usulan_standar_biaya`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tb_usulan_standar_biaya_ibfk_1` (`idOpd`),
  ADD KEY `idOpdPengusul` (`idOpdPengusul`);

--
-- Indexes for table `tb_usulan_standar_biaya_thn_detail`
--
ALTER TABLE `tb_usulan_standar_biaya_thn_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_usulan_thn_pekerjaan_detail`
--
ALTER TABLE `tb_usulan_thn_pekerjaan_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `duplicate` (`email`,`phone`);

--
-- Indexes for table `users_log`
--
ALTER TABLE `users_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_role`
--
ALTER TABLE `users_role`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_bidang_teknis`
--
ALTER TABLE `tb_bidang_teknis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tb_jenis_item`
--
ALTER TABLE `tb_jenis_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5027;

--
-- AUTO_INCREMENT for table `tb_kegiatan`
--
ALTER TABLE `tb_kegiatan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `tb_kelompok_item`
--
ALTER TABLE `tb_kelompok_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5029;

--
-- AUTO_INCREMENT for table `tb_lokasi`
--
ALTER TABLE `tb_lokasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tb_manajemen_dashboard`
--
ALTER TABLE `tb_manajemen_dashboard`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tb_opd`
--
ALTER TABLE `tb_opd`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tb_spesifikasi_item`
--
ALTER TABLE `tb_spesifikasi_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5058;

--
-- AUTO_INCREMENT for table `tb_standar_biaya`
--
ALTER TABLE `tb_standar_biaya`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tb_standar_biaya_thn`
--
ALTER TABLE `tb_standar_biaya_thn`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tb_standar_biaya_thn_detail`
--
ALTER TABLE `tb_standar_biaya_thn_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tb_thn_harga`
--
ALTER TABLE `tb_thn_harga`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5070;

--
-- AUTO_INCREMENT for table `tb_thn_kegiatan`
--
ALTER TABLE `tb_thn_kegiatan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `tb_thn_pekerjaan_detail`
--
ALTER TABLE `tb_thn_pekerjaan_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `tb_usulan_kegiatan`
--
ALTER TABLE `tb_usulan_kegiatan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tb_usulan_spesifikasi_item`
--
ALTER TABLE `tb_usulan_spesifikasi_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `tb_usulan_standar_biaya`
--
ALTER TABLE `tb_usulan_standar_biaya`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tb_usulan_standar_biaya_thn_detail`
--
ALTER TABLE `tb_usulan_standar_biaya_thn_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tb_usulan_thn_pekerjaan_detail`
--
ALTER TABLE `tb_usulan_thn_pekerjaan_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `users_log`
--
ALTER TABLE `users_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10787;

--
-- AUTO_INCREMENT for table `users_role`
--
ALTER TABLE `users_role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_bidang_teknis`
--
ALTER TABLE `tb_bidang_teknis`
  ADD CONSTRAINT `tb_bidang_teknis_ibfk_1` FOREIGN KEY (`idOpd`) REFERENCES `tb_opd` (`idOpd`);

--
-- Constraints for table `tb_kegiatan`
--
ALTER TABLE `tb_kegiatan`
  ADD CONSTRAINT `tb_kegiatan_ibfk_1` FOREIGN KEY (`idBidangTeknis`) REFERENCES `tb_bidang_teknis` (`idBidangTeknis`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tb_standar_biaya`
--
ALTER TABLE `tb_standar_biaya`
  ADD CONSTRAINT `tb_standar_biaya_ibfk_1` FOREIGN KEY (`idOpd`) REFERENCES `tb_opd` (`idOpd`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tb_usulan_kegiatan`
--
ALTER TABLE `tb_usulan_kegiatan`
  ADD CONSTRAINT `tb_usulan_kegiatan_ibfk_1` FOREIGN KEY (`idBidangTeknis`) REFERENCES `tb_bidang_teknis` (`idBidangTeknis`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_usulan_kegiatan_ibfk_2` FOREIGN KEY (`idOpd`) REFERENCES `tb_opd` (`idOpd`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tb_usulan_spesifikasi_item`
--
ALTER TABLE `tb_usulan_spesifikasi_item`
  ADD CONSTRAINT `tb_usulan_spesifikasi_item_ibfk_1` FOREIGN KEY (`idOpd`) REFERENCES `tb_opd` (`idOpd`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tb_usulan_standar_biaya`
--
ALTER TABLE `tb_usulan_standar_biaya`
  ADD CONSTRAINT `tb_usulan_standar_biaya_ibfk_1` FOREIGN KEY (`idOpd`) REFERENCES `tb_opd` (`idOpd`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_usulan_standar_biaya_ibfk_2` FOREIGN KEY (`idOpdPengusul`) REFERENCES `tb_opd` (`idOpd`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
