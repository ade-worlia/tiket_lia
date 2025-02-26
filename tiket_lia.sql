-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 26, 2025 at 02:35 AM
-- Server version: 10.4.8-MariaDB
-- PHP Version: 7.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tiket_lia`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `email` varchar(200) NOT NULL,
  `name` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `created_id` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `email`, `name`, `password`, `created_id`) VALUES
(1, 'liatugas84@gmail.com', 'lia', '$2y$10$n0W28XBZkGNjHPUoiWrvaut/KGKxD4VOYQdocDh/o7QmbO1iKLTXS', '2025-02-15 08:06:12'),
(2, 'veeltzee23@gmail.com', 'sumanto', '$2y$10$/WrIQ0ObCvyE5fFT/6R5OeaeUytHqld3Kh4mJALc0VmOFxIETicJS', '2025-02-19 07:38:45'),
(3, 'sucirah0510@gmail.com', 'uci', '$2y$10$PshZc1FLYVc3sGngX3KYNeXjOmrlh1o19pqza4NlNn5J7E7gvPHhy', '2025-02-21 06:50:59'),
(4, 'veeltzee23@gmail.com', 'bayu', '$2y$10$lQFDk5VvzeF1SQqxCC8rFO.kkmo6hA01CviQ0viq0RbjBtYjsvIxu', '2025-02-21 07:04:01'),
(5, 'veeltzee23@gmail.com', 'Manto', '$2y$10$EaWMMyMuY/D4FFoCqdbSXOOSQWNVanNv0Bhlc7bvNN9Eu5vWkSvRi', '2025-02-21 07:07:45');

-- --------------------------------------------------------

--
-- Table structure for table `akun_mall1`
--

CREATE TABLE `akun_mall1` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_mall` varchar(255) NOT NULL,
  `nik` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `akun_mall1`
--

INSERT INTO `akun_mall1` (`id`, `email`, `password`, `nama_mall`, `nik`) VALUES
(1, 'liatugas84@gmail.com', '$2y$10$RgO2jx0K.djO5vgf7bo7j.zUUe9J9/3uxObuN1PfSxQnkRl/7Rac.', 'APAAJA', '2312'),
(2, 'liatugas84@gmail.com', '$2y$10$bsbmkX8RTwR0.ecA7SCEWuftmGjF9boZF8XmtM7hP5uRaVybqc7Jq', 'CITY DEPOK\r\n', '34324'),
(3, 'liatugas84@gmail.com', '$2y$10$AybnwuLhNYfNnlFrDxKNEu5U4T3pu3t352cazvjY1zLNr39t7VS6O', 'xx1 depok', '888'),
(4, 'veeltzee23@gmail.com', '$2y$10$KOYGJ3cq5iGWA54WQBnNwOzKvvjq5UQUw1OPYkSpFz6CpRQs7dGQS', 'mall keluarga wii', '23432432');

-- --------------------------------------------------------

--
-- Table structure for table `film`
--

CREATE TABLE `film` (
  `id` int(231) NOT NULL,
  `poster` varchar(231) NOT NULL,
  `trailer` varchar(231) NOT NULL,
  `banner` varchar(231) NOT NULL,
  `nama_film` varchar(231) NOT NULL,
  `judul` longtext NOT NULL,
  `total_menit` varchar(231) NOT NULL,
  `usia` varchar(231) NOT NULL,
  `genre` varchar(231) NOT NULL,
  `dimensi` varchar(231) NOT NULL,
  `Producer` varchar(231) NOT NULL,
  `Director` varchar(231) NOT NULL,
  `Writer` varchar(231) NOT NULL,
  `Cast` varchar(231) NOT NULL,
  `Distributor` varchar(231) NOT NULL,
  `harga` varchar(231) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `film`
--

INSERT INTO `film` (`id`, `poster`, `trailer`, `banner`, `nama_film`, `judul`, `total_menit`, `usia`, `genre`, `dimensi`, `Producer`, `Director`, `Writer`, `Cast`, `Distributor`, `harga`) VALUES
(1, 'uploads/poster/zona1.jpg', 'uploads/trailer/Official Trailer Zona Merah.mp4', 'uploads/banner/zno.jpg', 'zona merah', 'bgus', '12', '13', 'Action', '2D', 'ee', 'ee', 'ee', 'rr', 'rr', '120000'),
(3, 'uploads/poster/miracle_baru.jpg', 'uploads/trailer/Official Trailer Zona Merah.mp4', 'uploads/banner/cinema.png', 'betul', 'bgus', '90', '17', '', '3D', 'ee', 'ee', 'ee', 'rr', 'rr', '120000'),
(12, 'uploads/poster/posterlia - Copy.jpg', 'uploads/trailer/Official Trailer Zona Merah.mp4', 'uploads/banner/pelangi.jpg', 'miracle', 'menceritakan petemanan dan pendidikan disebuah pelosok desa', '12', 'SU', '', '2D', 'lia', 'lia', 'lia', 'iqbaal ramadhan', 'lia', '30'),
(13, 'uploads/poster/posterlia - Copy.jpg', 'uploads/trailer/Official Trailer Zona Merah.mp4', 'uploads/banner/pelangi.jpg', 'laskar pelangi', 'menceritakan sebuah persahabatan ', '12', 'SU', 'Action', '3D', 'lia', 'shella', 'vili', 'vili', 'shella', '20'),
(14, 'uploads/poster/zona1.jpg', 'uploads/trailer/Official Trailer Zona Merah.mp4', 'uploads/banner/merah.jpg', 'zona merah', 'keren', '12', '17', '', '2D', 'lia', 'lia', 'lia', 'devano', 'lia', '30'),
(15, 'uploads/poster/poster2.jpg', 'uploads/trailer/Official Trailer Zona Merah.mp4', 'uploads/banner/matirasa3.jpg', 'mati rasa', 'menceritakan petemanan dan pendidikan disebuah pelosok desa', '12', 'SU', 'Family', '2D', 'lia', 'lia', 'lia', 'iqbal', 'lia', '30'),
(16, 'uploads/poster/moana.jpg', 'uploads/trailer/Official Trailer Zona Merah.mp4', 'uploads/banner/banner6.jpg', 'moana', 'lucu', '12', 'SU', '', '3D', 'lia', 'lia', 'lia', 'vili', 'lia', '30');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_film`
--

CREATE TABLE `jadwal_film` (
  `id` int(11) NOT NULL,
  `mall_id` int(11) NOT NULL,
  `film_id` int(11) NOT NULL,
  `studio` varchar(231) NOT NULL,
  `jam_tayang_1` time NOT NULL,
  `jam_tayang_2` time NOT NULL,
  `jam_tayang_3` time NOT NULL,
  `tanggal_tayang` date NOT NULL,
  `tanggal_akhir_tayang` date NOT NULL,
  `total_menit` varchar(231) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `jadwal_film`
--

INSERT INTO `jadwal_film` (`id`, `mall_id`, `film_id`, `studio`, `jam_tayang_1`, `jam_tayang_2`, `jam_tayang_3`, `tanggal_tayang`, `tanggal_akhir_tayang`, `total_menit`) VALUES
(6, 2, 6, 'Studio 3', '00:00:14', '14:13:00', '18:10:00', '2025-02-22', '2025-02-26', '12'),
(7, 4, 11, 'Studio 3', '00:00:18', '19:24:00', '02:24:00', '2025-02-22', '2025-02-27', '12'),
(8, 2, 5, 'Studio 1', '00:00:16', '16:32:00', '17:31:00', '2025-02-22', '2025-03-23', '12'),
(9, 2, 2, 'Studio 2', '00:00:16', '20:37:00', '21:40:00', '2025-02-22', '2025-03-24', '12'),
(10, 2, 1, 'Studio 1', '00:00:16', '17:40:00', '18:40:00', '2025-02-22', '2025-03-23', '12'),
(11, 2, 1, 'Studio 2', '21:52:00', '23:52:00', '23:52:00', '2025-02-08', '2025-02-28', '12'),
(12, 2, 1, 'Studio 3', '19:55:00', '00:55:00', '01:55:00', '2025-02-01', '2025-02-28', '12'),
(13, 4, 12, 'Studio 3', '10:10:00', '12:12:00', '14:14:00', '2025-03-01', '2025-03-24', '12'),
(14, 2, 13, 'Studio 2', '13:58:00', '14:58:00', '17:58:00', '2025-02-25', '2025-03-01', '12'),
(15, 3, 1, 'Studio 1', '11:47:00', '14:52:00', '15:48:00', '2025-02-25', '2025-03-08', '12'),
(16, 3, 15, 'Studio 3', '11:30:00', '12:30:00', '14:30:00', '2025-02-27', '2025-03-02', '12'),
(17, 1, 16, 'Studio 1', '08:48:00', '16:48:00', '20:48:00', '2025-02-25', '2025-03-07', '12');

-- --------------------------------------------------------

--
-- Table structure for table `seats`
--

CREATE TABLE `seats` (
  `id` int(11) NOT NULL,
  `mall_name` varchar(222) NOT NULL,
  `seat_number` varchar(20) NOT NULL,
  `status` enum('avallable','occupied') NOT NULL,
  `film_name` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `seats`
--

INSERT INTO `seats` (`id`, `mall_name`, `seat_number`, `status`, `film_name`) VALUES
(10, 'CITY DEPOK', 'A4', 'occupied', 'zona merah'),
(11, 'CITY DEPOK', 'A3', 'occupied', 'zona merah'),
(12, 'CITY DEPOK', 'A1', 'occupied', 'zona merah'),
(13, 'CITY DEPOK', 'A2', 'occupied', 'zona merah'),
(14, 'CITY DEPOK', 'A6', 'occupied', 'zona merah'),
(15, 'CITY DEPOK', 'A7', 'occupied', 'zona merah'),
(16, 'CITY DEPOK', 'A8', 'occupied', 'zona merah'),
(17, 'CITY DEPOK', 'E1', 'occupied', 'zona merah'),
(18, 'CITY DEPOK', 'H10', 'occupied', 'zona merah'),
(19, 'APAAJA', 'A4', 'occupied', 'moana'),
(20, 'APAAJA', 'G4', 'occupied', 'moana'),
(21, 'APAAJA', 'G5', 'occupied', 'moana'),
(22, 'APAAJA', 'G6', 'occupied', 'moana'),
(23, 'APAAJA', 'G7', 'occupied', 'moana');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `order_id` varchar(60) NOT NULL,
  `status` varchar(222) NOT NULL,
  `payment_type` varchar(20) NOT NULL,
  `amount` int(11) NOT NULL,
  `transaction_time` datetime NOT NULL,
  `username` varchar(250) NOT NULL,
  `seat_number` varchar(222) NOT NULL,
  `nama_film` varchar(232) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `order_id`, `status`, `payment_type`, `amount`, `transaction_time`, `username`, `seat_number`, `nama_film`) VALUES
(1, 'TIX-1740229487', 'settlement', 'qris', 240000, '2025-02-22 20:04:50', 'liatugas84@gmail.com', 'H8,H9', 'zona merah'),
(2, 'TIX-1740370614', 'pending', 'qris', 120000, '2025-02-24 11:17:03', 'liatugas84@gmail.com', 'A4', 'zona merah'),
(3, 'TIX-1740446862', 'settlement', 'qris', 120000, '2025-02-25 08:27:48', 'liatugas84@gmail.com', 'A4', 'zona merah'),
(4, 'TIX-1740456887', 'settlement', 'qris', 120000, '2025-02-25 11:14:57', 'liatugas84@gmail.com', 'A3', 'zona merah'),
(5, 'TIX-1740462562', 'settlement', 'qris', 120000, '2025-02-25 12:49:27', 'shellasantika33@gmail.com', 'A7', 'zona merah'),
(6, 'TIX-1740462646', 'settlement', 'qris', 120000, '2025-02-25 12:50:53', 'shellasantika33@gmail.com', 'A8', 'zona merah'),
(7, 'TIX-1740462915', 'settlement', 'qris', 120000, '2025-02-25 12:55:21', 'shellasantika33@gmail.com', 'H10', 'zona merah'),
(8, 'TIX-1740463169', 'settlement', 'qris', 30, '2025-02-25 12:59:31', 'liatugas84@gmail.com', 'A4', 'moana'),
(9, 'TIX-1740463540', 'settlement', 'qris', 120, '2025-02-25 13:05:41', 'liatugas84@gmail.com', 'G4,G5,G6,G7', 'moana');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(225) NOT NULL,
  `name` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `name`, `password`, `created_at`) VALUES
(1, 'sucirah0510@gmail.com', 'uci', '$2y$10$PqhFYjuQShidi7pTKkCe8.tsmFv.p7fMduJonNuKv2D3JnxLRyo92', '2025-02-13 08:07:53'),
(4, 'liatugas84@gmail.com', 'lia', '$2y$10$lBaFRTUpkxTw5NVN95Kp/O6ebkPcp/WSFplOrlBA2xwz.yXpCwHKS', '2025-02-14 03:36:42'),
(5, 'liatugas84@gmail.com', 'lia', '$2y$10$0eJcMjRfdbhMcc5pnb5Qeee7S.hMOFfzlpa9Ab6tyeywImjXMSoz.', '2025-02-14 05:52:06'),
(6, 'veeltzee23@gmail.com', 'bayu', '$2y$10$1NzX2daBnE2plZj//Ir3/uEOReCo80VNj4RTD5FOIEZNaEBC0D8i2', '2025-02-14 05:59:36');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `akun_mall1`
--
ALTER TABLE `akun_mall1`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `film`
--
ALTER TABLE `film`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jadwal_film`
--
ALTER TABLE `jadwal_film`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `seats`
--
ALTER TABLE `seats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `akun_mall1`
--
ALTER TABLE `akun_mall1`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `film`
--
ALTER TABLE `film`
  MODIFY `id` int(231) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `jadwal_film`
--
ALTER TABLE `jadwal_film`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `seats`
--
ALTER TABLE `seats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
