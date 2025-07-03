-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 03, 2025 at 02:45 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `absensi`
--

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '2025_04_22_215053_create_absensis_table', 1),
(3, '2025_07_02_220127_create_pengajuan_izin_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `pengajuan_izin`
--

CREATE TABLE `pengajuan_izin` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `jenis` enum('izin','sakit','cuti') COLLATE utf8mb4_unicode_ci NOT NULL,
  `alasan` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','disetujui','ditolak') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `presensis`
--

CREATE TABLE `presensis` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `jam_masuk` time DEFAULT NULL,
  `jam_pulang` time DEFAULT NULL,
  `lokasi_masuk` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lokasi_pulang` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto_masuk` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto_pulang` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan` enum('hadir','izin','sakit','cuti') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'hadir',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `presensis`
--

INSERT INTO `presensis` (`id`, `user_id`, `tanggal`, `jam_masuk`, `jam_pulang`, `lokasi_masuk`, `lokasi_pulang`, `foto_masuk`, `foto_pulang`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 14, '2025-07-03', '21:09:54', '21:10:28', '-7.2574719, 112.7520883', '-7.2574719, 112.7520883', 'presensi/HebrmMN6hqehh3MXLap3zl4VcDeP4xT3lANFMnZ0.png', 'presensi/Bgx4qUoVbkphjitH0M87BYqgRtLJzwPe8Sap7LOU.png', 'hadir', '2025-07-03 14:09:55', '2025-07-03 14:10:28'),
(2, 1, '2025-07-03', '21:38:38', '21:39:06', '-7.2574719, 112.7520883', '-7.2574719, 112.7520883', 'presensi/CdoMSMA3RSAPGiTM0cDxTFyCnAjNqH5ORHTkUm17.jpg', 'presensi/bcBmu3GRZW1MYCPK9VCCUhvoJqZmlen2BJ5RwJrR.jpg', 'hadir', '2025-07-03 14:38:38', '2025-07-03 14:39:06');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile_picture` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('admin','karyawan','manager') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'karyawan',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `name`, `phone`, `email`, `profile_picture`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$12$Wk/SJQmgR1cgg4/R81sPN.Mvwp.yi/Y320uNS0KsABKAdXUONK1O2', 'Admin', '0811000000', 'admin@example.com', NULL, 'admin', NULL, '2025-07-03 12:56:59', '2025-07-03 12:56:59'),
(2, 'manager', '$2y$12$MPzH31FzSJEp5BXJ3w1e0.ADzMnWJOcFdzlsbGzmvcNB4pDbov0om', 'Manager', '0811000001', 'manager@example.com', NULL, 'manager', NULL, '2025-07-03 12:57:00', '2025-07-03 12:57:00'),
(3, 'karyawan01', '$2y$12$lDKzK3NGKTFgZ.HyoWnSpOkC42zzIE5xeW7.jF221Mi3H32y5nQUO', 'Karyawan 01', '0811000001', 'karyawan01@example.com', NULL, 'karyawan', NULL, '2025-07-03 12:57:00', '2025-07-03 12:57:00'),
(4, 'karyawan02', '$2y$12$P6Nw2RL6rRtb8Id6F/q6Duq6HhsN0E4qCrfwZXtJR2YTgNUdmnMYS', 'Karyawan 02', '0811000002', 'karyawan02@example.com', NULL, 'karyawan', NULL, '2025-07-03 12:57:01', '2025-07-03 12:57:01'),
(5, 'karyawan03', '$2y$12$g0FH/xaNEa8XTbrE53bY6uPrESFuh55ZUoLawgGiugGKNEbqN7cye', 'Karyawan 03', '0811000003', 'karyawan03@example.com', NULL, 'karyawan', NULL, '2025-07-03 12:57:01', '2025-07-03 12:57:01'),
(6, 'karyawan04', '$2y$12$V1wQxYzaxXsSa3gyTVVLDuq2TtcK2VwM8PG2FafEth5hsKFJjSXxK', 'Karyawan 04', '0811000004', 'karyawan04@example.com', NULL, 'karyawan', NULL, '2025-07-03 12:57:01', '2025-07-03 12:57:01'),
(7, 'karyawan05', '$2y$12$5zPjv.EYYjkUUfzVdofOseczYKUY0Eq2KoCVOI5jVOM1tXeCwO25y', 'Karyawan 05', '0811000005', 'karyawan05@example.com', NULL, 'karyawan', NULL, '2025-07-03 12:57:02', '2025-07-03 12:57:02'),
(8, 'karyawan06', '$2y$12$1VJKzctRZoSg/tlBb4p95enUKiSQxtywZBCACXdP.894VGJzvmVBy', 'Karyawan 06', '0811000006', 'karyawan06@example.com', NULL, 'karyawan', NULL, '2025-07-03 12:57:02', '2025-07-03 12:57:02'),
(9, 'karyawan07', '$2y$12$REMIAYjWErNckgrUixLt2uuXeJJog6RxRSHx0OhxXtedOKGWFuyAe', 'Karyawan 07', '0811000007', 'karyawan07@example.com', NULL, 'karyawan', NULL, '2025-07-03 12:57:03', '2025-07-03 12:57:03'),
(10, 'karyawan08', '$2y$12$NmQDsS6Xy6f/RoHLWxncGu61CPQJ5IcYGbukaZ8ywzrBLBuEK1AU2', 'Karyawan 08', '0811000008', 'karyawan08@example.com', NULL, 'karyawan', NULL, '2025-07-03 12:57:03', '2025-07-03 12:57:03'),
(11, 'karyawan09', '$2y$12$ooYK8yXvBR252pYN2EFrGe6wFktUjms6WV.CXIOMLZpaoYlK.U59u', 'Karyawan 09', '0811000009', 'karyawan09@example.com', NULL, 'karyawan', NULL, '2025-07-03 12:57:04', '2025-07-03 12:57:04'),
(12, 'karyawan10', '$2y$12$3Za71SnLBJcHf1wwLqLFee2s6koAuGdfieA3TCFMpRiA9Y/UT6TRy', 'Karyawan 10', '0811000010', 'karyawan10@example.com', NULL, 'karyawan', NULL, '2025-07-03 12:57:04', '2025-07-03 12:57:04'),
(13, 'karyawan11', '$2y$12$2eeEKIxA4xXIlMbKrPkAceabr5ETYsYUyfHr3a7s6EFkJok7Hj7/i', 'Karyawan 11', '0811000011', 'karyawan11@example.com', NULL, 'karyawan', NULL, '2025-07-03 12:57:04', '2025-07-03 12:57:04'),
(14, 'karyawan12', '$2y$12$uYYmTN9klY/AKXIIeAL.PeWvXwMlW8T8Lwv.vj9ndbSVsyiENCfvS', 'Karyawan 12', '0811000012', 'karyawan12@example.com', NULL, 'karyawan', NULL, '2025-07-03 12:57:05', '2025-07-03 12:57:05'),
(15, 'karyawan13', '$2y$12$9Ckylcv04nT.js.77KxExufBb/HGTfvwhbmtj6y0XNl5ovEcTBGtW', 'Karyawan 13', '0811000013', 'karyawan13@example.com', NULL, 'karyawan', NULL, '2025-07-03 12:57:05', '2025-07-03 12:57:05'),
(16, 'karyawan14', '$2y$12$hAcO4cmKQKK8CM5DTV6pNuIJ0PC/TB/GPh0fS1trp.Vh0/8wpTA1.', 'Karyawan 14', '0811000014', 'karyawan14@example.com', NULL, 'karyawan', NULL, '2025-07-03 12:57:05', '2025-07-03 12:57:05'),
(17, 'karyawan15', '$2y$12$KWcF4.juHyt0iNom08RnZedOaJ1x.3ueSNe9ZXiOGTFB.kdOVClS6', 'Karyawan 15', '0811000015', 'karyawan15@example.com', NULL, 'karyawan', NULL, '2025-07-03 12:57:06', '2025-07-03 12:57:06'),
(18, 'karyawan16', '$2y$12$c5/KjoWNd7vOhEdB89wuYehpV2Sv0dRt1HIZtsXGRtis0XyCwtdzi', 'Karyawan 16', '0811000016', 'karyawan16@example.com', NULL, 'karyawan', NULL, '2025-07-03 12:57:06', '2025-07-03 12:57:06'),
(19, 'karyawan17', '$2y$12$MljW9Y3E.QB58lSAIDiRHeJJtoTsgXvmwM00SK0lY1CE0Et.Jo15a', 'Karyawan 17', '0811000017', 'karyawan17@example.com', NULL, 'karyawan', NULL, '2025-07-03 12:57:07', '2025-07-03 12:57:07'),
(20, 'karyawan18', '$2y$12$dUtPEkXNVvJncW85UB9KY.5xxDAUg0AtAutYCLJuQyW7EzaU.Weke', 'Karyawan 18', '0811000018', 'karyawan18@example.com', NULL, 'karyawan', NULL, '2025-07-03 12:57:07', '2025-07-03 12:57:07');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pengajuan_izin`
--
ALTER TABLE `pengajuan_izin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pengajuan_izin_user_id_foreign` (`user_id`);

--
-- Indexes for table `presensis`
--
ALTER TABLE `presensis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `presensis_user_id_foreign` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pengajuan_izin`
--
ALTER TABLE `pengajuan_izin`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `presensis`
--
ALTER TABLE `presensis`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pengajuan_izin`
--
ALTER TABLE `pengajuan_izin`
  ADD CONSTRAINT `pengajuan_izin_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `presensis`
--
ALTER TABLE `presensis`
  ADD CONSTRAINT `presensis_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
