-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3309
-- Generation Time: Jun 19, 2025 at 04:54 PM
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
-- Database: `klinik`
--

-- --------------------------------------------------------

--
-- Table structure for table `admintb`
--

CREATE TABLE `admintb` (
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admintb`
--

INSERT INTO `admintb` (`username`, `password`) VALUES
('admin', '$2y$10$ee4/1H6gTptWCJhfY8p5Mu6p1Pm3F66s34XcZGGp.Jvl26w8nAHkS');

-- --------------------------------------------------------

--
-- Table structure for table `appointmenttb`
--

CREATE TABLE `appointmenttb` (
  `appID` int NOT NULL,
  `pid` int DEFAULT NULL,
  `dokter_id` int DEFAULT NULL,
  `fname` varchar(100) DEFAULT NULL,
  `lname` varchar(100) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `docFees` int DEFAULT NULL,
  `appdate` date DEFAULT NULL,
  `apptime` time DEFAULT NULL,
  `userStatus` tinyint(1) DEFAULT NULL,
  `doctorStatus` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `appointmenttb`
--

INSERT INTO `appointmenttb` (`appID`, `pid`, `dokter_id`, `fname`, `lname`, `gender`, `email`, `contact`, `docFees`, `appdate`, `apptime`, `userStatus`, `doctorStatus`) VALUES
(1, 1, 1, 'pasien', 'tes', 'Laki-laki', 'pasien@gmail.com', '087890874567', NULL, '2025-05-27', '19:45:00', 1, 1),
(2, 2, 2, 'Reza', 'dika', 'Laki-laki', 'reza@gmail.com', '087890889567', NULL, '2025-05-29', '19:30:00', 1, 1),
(3, 2, 2, 'Reza', 'dika', 'Laki-laki', 'reza@gmail.com', '087890889567', NULL, '2025-05-27', '19:39:00', 1, NULL),
(5, 2, 1, 'Reza', 'dika', 'Laki-laki', 'reza@gmail.com', '087890889567', NULL, '2025-05-28', '19:42:00', 1, NULL),
(6, 2, 1, 'Reza', 'dika', 'Laki-laki', 'reza@gmail.com', '087890889567', NULL, '2025-05-31', '21:40:00', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `doctb`
--

CREATE TABLE `doctb` (
  `dokter_id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `doctorname` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `spec` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `doctb`
--

INSERT INTO `doctb` (`dokter_id`, `username`, `password`, `doctorname`, `email`, `spec`) VALUES
(1, 'pariti', '$2y$10$SMdpBoG.h4K9.f6UXhVWqOyss9nDEKmaeNe.hVHYsTB99LzbBizwO', 'pariti', 'parita@gmail.com', 'jantung'),
(2, 'sekar', '$2y$10$ZX2AgelMhDaMutP6V2uwg.YZsx.jQZU5J99xnazKp76mkKHgUGq/G', 'sekar', 'sekar@gmail.com', 'Kulit');

-- --------------------------------------------------------

--
-- Table structure for table `patreg`
--

CREATE TABLE `patreg` (
  `pid` int NOT NULL,
  `fname` varchar(100) DEFAULT NULL,
  `lname` varchar(100) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `cpassword` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `patreg`
--

INSERT INTO `patreg` (`pid`, `fname`, `lname`, `gender`, `email`, `contact`, `password`, `cpassword`) VALUES
(1, 'pasien', 'tes', 'Perempuan', 'pasien@gmail.com', '087890874566', 'pasien123', 'pasien123'),
(2, 'Reza', 'dika', 'Laki-laki', 'reza@gmail.com', '087890889567', 'reza123', 'reza123');

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `pembayaran_id` int NOT NULL,
  `pid` int NOT NULL,
  `dokter_id` int NOT NULL,
  `appdate` date NOT NULL,
  `apptime` time NOT NULL,
  `biaya` int NOT NULL,
  `status` enum('Lunas','Belum') NOT NULL,
  `tanggal_bayar` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`pembayaran_id`, `pid`, `dokter_id`, `appdate`, `apptime`, `biaya`, `status`, `tanggal_bayar`) VALUES
(1, 1, 1, '2025-05-27', '19:45:00', 87000, 'Lunas', '2025-05-26 11:09:18'),
(2, 2, 2, '2025-05-29', '19:30:00', 450000, 'Lunas', '2025-05-26 12:32:16'),
(3, 2, 2, '2025-05-27', '19:39:00', 450000, 'Lunas', '2025-05-26 00:00:00'),
(4, 2, 2, '2025-05-27', '19:39:00', 450000, 'Lunas', '2025-05-26 00:00:00'),
(6, 2, 1, '2025-05-31', '21:40:00', 450000, 'Lunas', '2025-05-31 00:00:00'),
(7, 2, 1, '2025-05-31', '21:40:00', 450000, 'Lunas', '2025-05-31 00:00:00'),
(8, 2, 1, '2025-05-31', '21:40:00', 450000, 'Lunas', '2025-05-31 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `prestb`
--

CREATE TABLE `prestb` (
  `presID` int NOT NULL,
  `pid` int DEFAULT NULL,
  `dokter_id` int DEFAULT NULL,
  `fname` varchar(100) DEFAULT NULL,
  `lname` varchar(100) DEFAULT NULL,
  `appdate` date DEFAULT NULL,
  `apptime` time DEFAULT NULL,
  `disease` varchar(255) DEFAULT NULL,
  `allergy` varchar(255) DEFAULT NULL,
  `prescription` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `prestb`
--

INSERT INTO `prestb` (`presID`, `pid`, `dokter_id`, `fname`, `lname`, `appdate`, `apptime`, `disease`, `allergy`, `prescription`) VALUES
(1, 1, 1, 'pasien', 'tes', '2025-05-27', '19:45:00', 'Diabetes', 'Kacang', 'bodrexin 2x sehari'),
(2, 1, 2, 'pasien', 'tes', '2025-05-31', '22:41:00', 'maag', '-', 'amboxil 3 x1'),
(3, 2, 1, 'Reza', 'dika', '2025-05-31', '21:40:00', 'asam lambung', 'wijen', 'oberus 3x sehari');

-- --------------------------------------------------------

--
-- Table structure for table `receptb`
--

CREATE TABLE `receptb` (
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `receptb`
--

INSERT INTO `receptb` (`username`, `password`) VALUES
('resepsionis', '$2y$10$klf3Oqre0Aodeq9BDTm1n.cRNREKDixWp2mD6XO2Lo/QudYnHm42C');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admintb`
--
ALTER TABLE `admintb`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `appointmenttb`
--
ALTER TABLE `appointmenttb`
  ADD PRIMARY KEY (`appID`),
  ADD KEY `fk_appointment_patient` (`pid`),
  ADD KEY `fk_appointment_doctor` (`dokter_id`);

--
-- Indexes for table `doctb`
--
ALTER TABLE `doctb`
  ADD PRIMARY KEY (`dokter_id`);

--
-- Indexes for table `patreg`
--
ALTER TABLE `patreg`
  ADD PRIMARY KEY (`pid`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`pembayaran_id`),
  ADD KEY `fk_bayar_pasien` (`pid`),
  ADD KEY `fk_bayar_dokter` (`dokter_id`);

--
-- Indexes for table `prestb`
--
ALTER TABLE `prestb`
  ADD PRIMARY KEY (`presID`),
  ADD KEY `fk_pres_patient` (`pid`),
  ADD KEY `fk_pres_doctor` (`dokter_id`);

--
-- Indexes for table `receptb`
--
ALTER TABLE `receptb`
  ADD PRIMARY KEY (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointmenttb`
--
ALTER TABLE `appointmenttb`
  MODIFY `appID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `doctb`
--
ALTER TABLE `doctb`
  MODIFY `dokter_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `patreg`
--
ALTER TABLE `patreg`
  MODIFY `pid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `pembayaran_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `prestb`
--
ALTER TABLE `prestb`
  MODIFY `presID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointmenttb`
--
ALTER TABLE `appointmenttb`
  ADD CONSTRAINT `fk_appointment_doctor` FOREIGN KEY (`dokter_id`) REFERENCES `doctb` (`dokter_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_appointment_patient` FOREIGN KEY (`pid`) REFERENCES `patreg` (`pid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `fk_bayar_dokter` FOREIGN KEY (`dokter_id`) REFERENCES `doctb` (`dokter_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_bayar_pasien` FOREIGN KEY (`pid`) REFERENCES `patreg` (`pid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `prestb`
--
ALTER TABLE `prestb`
  ADD CONSTRAINT `fk_pres_doctor` FOREIGN KEY (`dokter_id`) REFERENCES `doctb` (`dokter_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pres_patient` FOREIGN KEY (`pid`) REFERENCES `patreg` (`pid`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
