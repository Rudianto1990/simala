-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 10, 2026 at 05:42 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `simala_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(2, '2020-11-05-112140', 'App\\Database\\Migrations\\Users', 'default', 'App', 1788144848, 1),
(3, '2026-08-31-090000', 'App\\Database\\Migrations\\MonitoringAlat', 'default', 'App', 1788168836, 2);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_monitoring_alat`
--

CREATE TABLE `tbl_monitoring_alat` (
  `id` int(15) UNSIGNED NOT NULL,
  `nomor_asset` varchar(70) NOT NULL,
  `nama_alat` varchar(70) NOT NULL,
  `kode_alat` varchar(70) NOT NULL,
  `slug` varchar(70) NOT NULL,
  `merk` varchar(70) NOT NULL,
  `model` varchar(70) NOT NULL,
  `kap_swal_ton` varchar(70) NOT NULL,
  `span_m` varchar(70) NOT NULL,
  `outreach_m` varchar(70) NOT NULL,
  `foto_alat` varchar(255) NOT NULL,
  `status` enum('Milik','Sewa') NOT NULL,
  `tahun` varchar(4) NOT NULL,
  `negara` varchar(70) NOT NULL,
  `lokasi` varchar(70) NOT NULL,
  `keterangan` varchar(70) NOT NULL,
  `latitude` varchar(255) NOT NULL,
  `longitude` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_monitoring_alat`
--

INSERT INTO `tbl_monitoring_alat` (`id`, `nomor_asset`, `nama_alat`, `kode_alat`, `slug`, `merk`, `model`, `kap_swal_ton`, `span_m`, `outreach_m`, `foto_alat`, `status`, `tahun`, `negara`, `lokasi`, `keterangan`, `latitude`, `longitude`, `created_at`, `updated_at`) VALUES
(1, '120504003012', 'Gantry Luffing Crane (GLC)', 'GLC-01', 'gantry-luffing-crane-glc', 'WMMP', 'MQ4040A', '40', '16.00', '12 - 40', '', 'Milik', '2011', 'China', 'Dermaga 101', 'Elektrifikasi', '-6.0978519560374265', '106.88249945640564', '2026-09-08 15:01:00', '2026-09-08 08:52:00'),
(2, '120504003013', 'Gantry Luffing Crane (GLC)', 'GLC-02', 'gantry-luffing-crane-glc', 'WMMP', 'MQ4040A', '40', '16.00', '12 - 40', '', 'Milik', '2011', 'China', 'Dermaga 100', 'Elektrifikasi', '-6.095254259720866', '106.88258528709413', '2026-09-08 15:01:00', '2026-09-08 08:53:06'),
(3, '120504003014', 'Gantry Luffing Crane (GLC)', 'GLC-05', 'gantry-luffing-crane-glc', 'WMMP', 'MQ4040B', '40', '10.50', '12 - 40', '', 'Milik', '2011', 'China', 'Dermaga 100', 'Elektrifikasi', '-6.096787642172228', '106.88246726989748', '2026-09-08 15:01:00', '2026-09-08 08:54:23'),
(4, '120504003015', 'Gantry Luffing Crane (GLC)', 'GLC-06', 'gantry-luffing-crane-glc', 'WMMP', 'MQ4040B', '40', '10.50', '12 - 40', '', 'Milik', '2011', 'China', 'Dermaga 100', 'Elektrifikasi', '-6.096676418334364', '106.88255578279497', '2026-09-08 15:01:00', '2026-09-08 08:54:54'),
(5, '120504003016', 'Gantry Luffing Crane (GLC)', 'GLC-07', 'gantry-luffing-crane-glc', 'WMMP', 'MQ4040C', '40', '11.25', '12 - 40', '', 'Milik', '2011', 'China', 'Dermaga 114', 'Elektrifikasi', '-6.097943260233453', '106.88419729471208', '2026-09-08 15:01:00', '2026-09-08 08:56:11'),
(6, '120504003017', 'Gantry Luffing Crane (GLC)', 'GLC-08', 'gantry-luffing-crane-glc', 'WMMP', 'MQ4040C', '40', '11.25', '12 - 40', '', 'Milik', '2011', 'China', 'Dermaga 114', 'Elektrifikasi', '-6.097197115720513', '106.88422679901124', '2026-09-08 15:01:00', '2026-09-08 08:56:41'),
(7, '120504003018', 'Gantry Luffing Crane (GLC)', 'GLC-09', 'gantry-luffing-crane-glc', 'WMMP', 'MQ4040C', '40', '11.25', '12 - 40', '', 'Milik', '2011', 'China', 'Dermaga 114', 'Elektrifikasi', '-6.096868445088307', '106.88425898551941', '2026-09-08 15:01:00', '2026-09-08 08:57:12'),
(8, '120504003019', 'Gantry Luffing Crane (GLC)', 'GLC-10', 'gantry-luffing-crane-glc', 'WMMP', 'MQ4045', '40', '21.80', '12 - 45', '', 'Milik', '2011', 'China', 'Dermaga 114', 'Elektrifikasi', '-6.09619964327764', '106.88426971435547', '2026-09-08 15:01:00', '2026-09-08 08:57:52'),
(9, '120504003020', 'Gantry Luffing Crane (GLC)', 'GLC-11', 'gantry-luffing-crane-glc', 'WMMP', 'MQ4046', '40', '21.80', '12 - 45', '', 'Milik', '2011', 'China', 'Dermaga 114', 'Elektrifikasi', '-6.09622222978145', '106.8851923942566', '2026-09-08 15:01:00', '2026-09-08 08:59:20'),
(10, '120504003021', 'Rubber Tyred Gantry (RTG)', 'RTG-01', 'rubber-tyred-gantry-rtg', 'ZPMC', 'ZP13-2089', '41', '22.71', '', '', 'Milik', '2013', 'China', 'Lapangan 009', '', '-6.102868454730093', '106.87938809394836', '2026-09-08 15:01:00', '2026-09-08 08:59:42'),
(11, '120504003022', 'Rubber Tyred Gantry (RTG)', 'RTG-02', 'rubber-tyred-gantry-rtg', 'ZPMC', 'ZP13-2089', '41', '22.71', '', '', 'Milik', '2013', 'China', 'Lapangan 009', '', '-6.104415317093761', '106.87933444976808', '2026-09-08 15:01:00', '2026-09-08 09:00:03'),
(12, '120504003023', 'Rubber Tyred Gantry (RTG)', 'RTG-03', 'rubber-tyred-gantry-rtg', 'ZPMC', 'ZP13-2089', '41', '22.71', '', '', 'Milik', '2013', 'China', 'Lapangan 009', '', '-6.105311428419988', '106.87982797622682', '2026-09-08 15:01:00', '2026-09-08 09:00:25'),
(13, '120504003024', 'Rubber Tyred Gantry (RTG)', 'RTG-04', 'rubber-tyred-gantry-rtg', 'ZPMC', 'ZP13-2089', '41', '22.71', '', '', 'Milik', '2013', 'China', 'Lapangan 009', '', '-6.105204748578805', '106.87984943389894', '2026-09-08 15:01:00', '2026-09-08 09:00:46'),
(14, '120504003025', 'Overhead Crane (OHC)', 'OHC-01', 'overhead-crane-ohc', 'MHE-DEMAG', 'Double Girder', '25', '', '', '', 'Milik', '2012', 'Jerman', 'Dermaga 100', '', '-6.097267708828642', '106.88603997230531', '2026-09-08 15:01:00', '2026-09-08 09:02:05'),
(15, '120504003026', 'Overhead Crane (OHC)', 'OHC-02', 'overhead-crane-ohc', 'MHE-DEMAG', 'Double Girder', '25', '', '', '', 'Milik', '2012', 'Jerman', 'Dermaga 100', '', '-6.097534412340998', '106.885986328125', '2026-09-08 15:01:00', '2026-09-08 09:02:32'),
(16, '120504003027', 'Overhead Crane (OHC)', 'OHC-03', 'overhead-crane-ohc', 'MHE-DEMAG', 'Double Girder', '25', '', '', '', 'Milik', '2012', 'Jerman', 'Dermaga 100', '', '-6.098829339353025', '106.88157677650453', '2026-09-08 15:01:00', '2026-09-08 09:03:29'),
(17, '120504003028', 'Overhead Crane (OHC)', 'OHC-04', 'overhead-crane-ohc', 'MHE-DEMAG', 'Double Girder', '25', '', '', '', 'Milik', '2012', 'Jerman', 'Dermaga 100', '', '-6.098742410901434', '106.8859648704529', '2026-09-08 15:01:00', '2026-09-08 09:04:18'),
(18, '120504003029', 'Overhead Crane (OHC)', 'OHC-05', 'overhead-crane-ohc', 'MHE-DEMAG', 'Double Girder', '25', '', '', '', 'Milik', '2012', 'Jerman', 'Gudang Pombo', '', '-6.098729242448291', '106.88154458999635', '2026-09-08 15:01:00', '2026-09-08 09:04:47'),
(19, '120504003030', 'Overhead Crane (OHC)', 'OHC-06', 'overhead-crane-ohc', 'MHE-DEMAG', 'Double Girder', '25', '', '', '', 'Milik', '2012', 'Jerman', 'Gudang Pombo', '', '-6.098878596024528', '106.88159823417665', '2026-09-08 15:01:00', '2026-09-08 09:05:12'),
(20, '120504003031', 'Overhead Crane (OHC)', 'OHC-07', 'overhead-crane-ohc', 'MHE-DEMAG', 'Double Girder', '25', '', '', '', 'Milik', '2012', 'Jerman', 'Gudang Pombo', '', '-6.09902669939024', '106.88161969184877', '2026-09-08 15:01:00', '2026-09-08 09:06:08'),
(21, '120504003032', 'Overhead Crane (OHC)', 'OHC-08', 'overhead-crane-ohc', 'MHE-DEMAG', 'Double Girder', '25', '', '', '', 'Milik', '2012', 'Jerman', 'Gudang Ambon', '', '-6.0986545656445745', '106.88600778579712', '2026-09-08 15:01:00', '2026-09-08 09:06:56'),
(22, '120504003033', 'Overhead Crane (OHC)', 'OHC-09', 'overhead-crane-ohc', 'MHE-DEMAG', 'Double Girder', '25', '', '', '', 'Milik', '2012', 'Jerman', 'Gudang Ambon', '', '-6.098514629954252', '106.88599705696106', '2026-09-08 15:01:00', '2026-09-08 09:07:14'),
(23, '120504003034', 'Overhead Crane (OHC)', 'OHC-10', 'overhead-crane-ohc', 'MHE-DEMAG', 'Double Girder', '25', '', '', '', 'Milik', '2012', 'Jerman', 'Gudang Ambon', '', '-6.097824952328819', '106.8859648704529', '2026-09-08 15:01:00', '2026-09-08 09:07:31'),
(24, '120504003035', 'Overhead Crane (OHC)', 'OHC-11', 'overhead-crane-ohc', 'MHE-DEMAG', 'Double Girder', '25', '', '', '', 'Milik', '2012', 'Jerman', 'Gudang Pombo', '', '-6.098701321992759', '106.88131392002106', '2026-09-08 15:01:00', '2026-09-08 09:07:50'),
(25, '120504003036', 'Overhead Crane (OHC)', 'OHC-12', 'overhead-crane-ohc', 'MHE-DEMAG', 'Double Girder', '25', '', '', '', 'Milik', '2012', 'Jerman', 'Gudang Pombo', '', '-6.098889264135523', '106.88161969184877', '2026-09-08 15:01:00', '2026-09-08 09:08:07'),
(26, '120504003037', 'Mobile Crane (MBC)', 'MBC-01', 'mobile-crane-mbc', 'HCM', 'QLY 65', '65', '', '28', '', 'Milik', '2012', 'China', 'Dermaga 100', '', '-6.095244841715312', '106.88230097293855', '2026-09-08 15:01:00', '2026-09-08 09:08:57'),
(27, '120504003038', 'Mobile Crane (MBC)', 'MBC-02', 'mobile-crane-mbc', 'HCM', 'QLY 65', '65', '', '28', '', 'Milik', '2012', 'China', 'Galangan PSM', '', '-6.11458057530272', '106.8631500005722', '2026-09-08 15:01:00', '2026-09-08 09:12:08'),
(28, '120504003039', 'Mobile Crane (MBC)', 'MBC-03', 'mobile-crane-mbc', 'HCM', 'QLY 65', '65', '', '28', '', 'Milik', '2012', 'China', 'Galangan PSM', '', '-6.114820600710957', '106.86357378959657', '2026-09-08 15:01:00', '2026-09-08 09:12:40'),
(29, '120504003040', 'Mobile Crane (MBC)', 'MBC-04', 'mobile-crane-mbc', 'HCM', 'QLY 65', '65', '', '28', '', 'Milik', '2012', 'China', 'Dermaga Jl. Tembus DKB', '', '-6.113525712361856', '106.87326192855836', '2026-09-08 15:01:00', '2026-09-08 09:16:17'),
(30, '120504003041', 'Mobile Crane (MBC)', 'MBC-05', 'mobile-crane-mbc', 'HCM', 'QLY 65', '65', '', '28', '', 'Milik', '2012', 'China', 'Lapangan Inggom', '', '-6.111605501432052', '106.86959266662598', '2026-09-08 15:01:00', '2026-09-08 09:17:44'),
(31, '120504003042', 'Mobile Crane (MBC)', 'MBC-06', 'mobile-crane-mbc', 'HCM', 'QLY 65', '65', '', '28', '', 'Milik', '2012', 'China', 'Dermaga 100', '', '-6.095500878055192', '106.88221514225008', '2026-09-08 15:01:00', '2026-09-08 09:18:12'),
(32, '120504003043', 'Mobile Crane (MBC)', 'MBC-07', 'mobile-crane-mbc', 'HCM', 'QLY 65', '65', '', '28', '', 'Milik', '2012', 'China', 'Dermaga 100', '', '-6.0956994894937955', '106.88223123550416', '2026-09-08 15:01:00', '2026-09-08 09:18:44'),
(33, '120504003044', 'Mobile Crane (MBC)', 'MBC-08', 'mobile-crane-mbc', 'HCM', 'QLY 25', '25', '', '24', '', 'Milik', '2012', 'China', 'Dermaga 100', '', '-6.095842259651615', '106.8821668624878', '2026-09-08 15:01:00', '2026-09-08 09:19:11'),
(34, '120504003045', 'Mobile Crane (MBC)', 'MBC-09', 'mobile-crane-mbc', 'HCM', 'QLY 25', '25', '', '24', '', 'Milik', '2012', 'China', 'Dermaga 100', '', '-6.095922270931858', '106.88213467597963', '2026-09-08 15:01:00', '2026-09-08 09:19:38'),
(35, '120504003046', 'Mobile Crane (MBC)', 'MBC-10', 'mobile-crane-mbc', 'HCM', 'QLY 25', '25', '', '24', '', 'Milik', '2012', 'China', 'Dermaga 100', '', '-6.09561822800346', '106.88220441341402', '2026-09-08 15:01:00', '2026-09-08 09:20:03');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `id` int(15) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`id`, `nama`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, 'RUDIANTO EKA PRAYOGA', 'rudiantoekaprayoga@gmail.com', '$2y$10$lwWYlfAEhDwzAxjd0DbuYOF70D4ucl7Avu5T/5aofSq4OsJscGZTe', '2026-08-31 03:07:46', '2026-08-31 03:07:46'),
(2, 'ruudy', 'rudyzaa@gmail.com', '$2y$10$oNfoxmkm.kUJHrpnihOSI.ViVxxhAzYmyQBpX.ZXIwM2LPX8ik0la', '2026-08-31 11:03:14', '2026-08-31 11:03:14'),
(3, 'Fatur', 'fatur@simala.com', '$2y$10$GfkIwD9FxTLJl/5GYTV5a./2Xqo5WWEdcS85pddIbuaMRLSyFxJWS', '2026-09-03 02:36:02', '2026-09-03 02:36:02');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_monitoring_alat`
--
ALTER TABLE `tbl_monitoring_alat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_monitoring_alat`
--
ALTER TABLE `tbl_monitoring_alat`
  MODIFY `id` int(15) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
