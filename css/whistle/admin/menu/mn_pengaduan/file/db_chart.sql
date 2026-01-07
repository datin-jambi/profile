-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 23, 2015 at 02:54 PM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `db_chart`
--

-- --------------------------------------------------------

--
-- Table structure for table `datapenduduk`
--

CREATE TABLE IF NOT EXISTS `datapenduduk` (
  `provinsi` varchar(50) NOT NULL,
  `jumlah` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `datapenduduk`
--

INSERT INTO `datapenduduk` (`provinsi`, `jumlah`) VALUES
('Aceh', 3930905),
('Jawa', 11649655),
('Kalimantan', 4957627),
('Maluku', 2413846),
('Papua', 4248934);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `datapenduduk`
--
ALTER TABLE `datapenduduk`
 ADD PRIMARY KEY (`provinsi`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
