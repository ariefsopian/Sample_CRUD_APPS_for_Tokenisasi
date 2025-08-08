-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 11, 2025 at 06:45 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_pegawai`
--

-- --------------------------------------------------------

--
-- Table structure for table `pegawai`
--

CREATE TABLE `pegawai` (
  `id` int(11) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `tempat_lahir` varchar(50) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `jenis_kelamin` varchar(20) NOT NULL,
  `agama` varchar(20) NOT NULL,
  `alamat` text NOT NULL,
  `nomor_telepon` varchar(15) NOT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pegawai`
--

INSERT INTO `pegawai` (`id`, `nama_lengkap`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `alamat`, `nomor_telepon`, `user_id`) VALUES
(5, 'oOlH< O{?tqb', 'Bandung', '2025-06-16', 'Laki-laki', 'Islam', 'y~qN)7%', 'C)i{.@/aQC)S', 2),
(7, ']5,) |S7s&l}', 'Surabaya', '1994-02-17', 'Laki-laki', 'Kristen', 'L@[F-jm', '/m(Al<tocW', 1),
(8, 'mmre $ir.W:J', 'Surabaya', '1992-04-17', 'Laki-laki', 'Katolik', '*G*5Apbv2', '<C}}Xb?:#@2I', 1),
(12, 'Wc,8XN? pZ{)fyDb', 'Bandung', '1992-06-03', 'Laki-laki', 'Budha', '_WT{dZ B%@', ';~U_BYoX*_CS', 1),
(13, 'R.ojW PYUMEpn', 'Bandung', '1994-09-14', 'Perempuan', 'Islam', 'uo}pX5A a1QuCAu', 'Bv+~],u;%Af4(', 1),
(14, '!,K; h*dRN?U h&y&5', 'Bandung', '2003-11-13', 'Laki-laki', 'Islam', '!5!aN(Y aA|ke', 'WnIvxF+jlCWf', 1),
(15, '39{Ua i;v1_ u<8E/i', 'Bandung', '2000-02-28', 'Perempuan', 'Islam', 'uo}pX5A a1QuCAu ', 'dA%Vv~:D94S6=', 1),
(16, 'Y0]<_ v^-', 'Banyumas', '1998-06-16', 'Laki-laki', 'Islam', 'lqv]?g!S /7e6R|x=', '?(XDu*2>;)4<', 1),
(17, 'c;T5$r QmXNL6QbJW', 'Depok', '1999-05-16', 'Laki-laki', 'Islam', 'xXRd@t_', 'ylvsb1MC%x/Lrh', 1),
(18, 'C2UsQ:2XK Qfcg(', 'Ambarawa', '1991-12-12', 'Laki-laki', 'Islam', 'ol%4!?9n %f<n7n[l', 'u?U@ZC?|:?[O@', 1),
(19, 'CUH%e]6p m]oU0(5B=', 'Surabaya', '1991-04-12', 'Laki-laki', 'Islam', 'h<;>9j1U ', 'h1K7w!f/|i1', 1),
(20, 'ldGO% xmSHW', 'Solo', '1992-06-28', 'Laki-laki', 'Islam', 'Z!t!', ']WE*X=K05&z#', 1),
(21, 'LC{0r [g_$5J', 'Surabaya', '2001-06-11', 'Laki-laki', 'Islam', 'h<;>9j1U', 'AQc72-IZwI!R7G', 1),
(23, 'fKA;i u9tYCgX', 'Boyolali', '1992-08-18', 'Laki-laki', 'Islam', 'r[Ui XMes7df', 'Ppm/sDMfV7Wh!', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','pegawai') NOT NULL DEFAULT 'pegawai'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(1, 'admin', '$2y$10$7G0tmRZexnemxAIo9BlyruToSGlipuFIMqINFTfVTH9335MEIECxm', 'admin'),
(2, 'Arief Sopian', '$2y$10$JnosJFa7cTFgsoMAzSQoD.AQNm0AzEvWOgf7.Oc/JeLN13UygE6Te', 'pegawai');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pegawai`
--
ALTER TABLE `pegawai`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pegawai`
--
ALTER TABLE `pegawai`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pegawai`
--
ALTER TABLE `pegawai`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
