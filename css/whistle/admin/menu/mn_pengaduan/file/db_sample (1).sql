-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 12, 2017 at 05:40 AM
-- Server version: 5.6.21
-- PHP Version: 5.5.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `db_sample`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_cabang`
--

CREATE TABLE IF NOT EXISTS `tbl_cabang` (
`id` int(11) NOT NULL,
  `kode_cabang` varchar(10) NOT NULL,
  `nama_cabang` varchar(30) NOT NULL,
  `kode_cabang_induk` varchar(10) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_cabang`
--

INSERT INTO `tbl_cabang` (`id`, `kode_cabang`, `nama_cabang`, `kode_cabang_induk`) VALUES
(1, 'ID0010001', 'Kantor Pusat', 'ID0010001'),
(2, 'ID0010010', 'Cabang Utama', 'ID0010010'),
(3, 'ID0010011', 'K. Kas Gubernur', 'ID0010010'),
(4, 'ID0010012', 'K. Kas KPP Pratama', 'ID0010010'),
(5, 'ID0010013', 'K. Kas RSUD Raden Mattaher', 'ID0010010'),
(6, 'ID0010014', 'K. Kas Samsat', 'ID0010010'),
(7, 'ID0010015', 'Mobil Kas Keliling KCU', 'ID0010010'),
(8, 'ID0010020', 'Cabang Ma. Bungo', 'ID0010020'),
(9, 'ID0010021', 'K. Kas Rantau Ikil', 'ID0010020'),
(10, 'ID0010022', 'K. Kas Kel. Muara Bungo', 'ID0010020'),
(11, 'ID0010023', 'KCP. Kuamang Kuning', 'ID0010020'),
(12, 'ID0010030', 'Cabang Sei Penuh', 'ID0010030'),
(13, 'ID0010031', 'K. Kas Bedeng Delapan', 'ID0010030'),
(14, 'ID0010032', 'K. Kas Siulak Gedang', 'ID0010030'),
(15, 'ID0010040', 'Cabang Bangko', 'ID0010040'),
(16, 'ID0010041', 'K. Kas Pemenang', 'ID0010040'),
(17, 'ID0010050', 'Cabang Ma. Bulian', 'ID0010050'),
(18, 'ID0010051', 'K. Kas Muara Tembesi', 'ID0010050'),
(19, 'ID0010052', 'KCP Sungai Rengas', 'ID0010050'),
(20, 'ID0010053', 'K. Kas Durian Luncuk', 'ID0010050'),
(21, 'ID0010060', 'Cabang Kuala Tungkal', 'ID0010060'),
(22, 'ID0010061', 'Cabang Ma. Sabak', 'ID0010061'),
(23, 'ID0010062', 'K. Kas Rano', 'ID0010061'),
(24, 'ID0010063', 'K. Kas Geragai', 'ID0010061'),
(25, 'ID0010070', 'Cabang Sutomo', 'ID0010070'),
(26, 'ID0010071', 'Cabang Sengeti', 'ID0010071'),
(27, 'ID0010072', 'K. Kas UNBARI', 'ID0010070'),
(28, 'ID0010073', 'K. Kas Walikota', 'ID0010070'),
(29, 'ID0010074', 'K. Kas RSUD A. Manaf', 'ID0010070'),
(30, 'ID0010080', 'Cabang Sarolangun', 'ID0010080'),
(31, 'ID0010081', 'KCP Singkut', 'ID0010080'),
(32, 'ID0010082', 'KCP. Mandiangin', 'ID0010080'),
(33, 'ID0010090', 'Cabang Ma. Tebo', 'ID0010090'),
(34, 'ID0010091', 'KCP Rimbo Bujang', 'ID0010090'),
(35, 'ID0010092', 'KCP Sungai Bengkal', 'ID0010090'),
(36, 'ID0010093', 'K. Kas Bangun Seranten', 'ID0010090'),
(37, 'ID0010099', 'Cabang Syariah', 'ID0010099'),
(38, 'ID0010100', 'KCP. Sungai Bahar', 'ID0010071'),
(39, 'ID0010701', 'KCP. Marene', 'ID0010070'),
(40, 'ID0010401', 'KCP. Muaro Delang', 'ID0010040'),
(41, 'ID0010601', 'KCP. Pematang Lumut', 'ID0010060');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_menu`
--

CREATE TABLE IF NOT EXISTS `tbl_menu` (
`id` int(11) NOT NULL,
  `nama_menu` varchar(30) NOT NULL,
  `master_menu` varchar(30) NOT NULL,
  `tabel_asal` varchar(50) NOT NULL,
  `icon` varchar(50) NOT NULL,
  `link` varchar(100) NOT NULL,
  `lvl` text NOT NULL,
  `stts` varchar(10) NOT NULL DEFAULT 'Non Active',
  `urutan` int(5) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `tbl_menu`
--

INSERT INTO `tbl_menu` (`id`, `nama_menu`, `master_menu`, `tabel_asal`, `icon`, `link`, `lvl`, `stts`, `urutan`) VALUES
(1, 'Home ', 'Home', '', 'home ', '?menu=home ', 'Superadmin, CS Credit, Checker, Credit Analyst, Approver', 'Active', 1),
(2, 'Management Menu ', 'Management Data', 'tbl_menu ', 'sitemap ', '?menu=mgmtmn', 'Superadmin ', 'Active', 2),
(3, 'Management Branch', 'Management Data', 'tbl_cabang', 'icon icon-th-list', '?menu=mgmtbrnch', 'Superadmin', 'Active', 3),
(4, 'Management User ', 'Management Data', 'tbl_user ', 'icon icon-inbox', '?menu=mgmtusr ', 'Superadmin', 'Active', 4),
(27, 'Report', 'Report', '', 'icon icon-file', '?menu=report', 'Superadmin', 'Active', 27);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE IF NOT EXISTS `tbl_user` (
`id` int(11) NOT NULL,
  `kode_cabang` varchar(10) NOT NULL,
  `uname` varchar(30) NOT NULL,
  `pwd` varchar(50) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `position` varchar(50) NOT NULL,
  `lvl` varchar(15) NOT NULL DEFAULT 'User',
  `stts` varchar(10) NOT NULL DEFAULT 'Passive',
  `LastOnline` varchar(20) NOT NULL,
  `LastOnlineIP` varchar(20) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`id`, `kode_cabang`, `uname`, `pwd`, `fname`, `position`, `lvl`, `stts`, `LastOnline`, `LastOnlineIP`) VALUES
(1, 'ID0010001', 'superadmin', '889a3a791b3875cfae413574b53da4bb8a90d53e', 'Superadmin', 'Superadmin', 'Superadmin', 'Active', '2017-09-08 10:46:26', '::1'),
(3, 'ID0010001', 'reporting', '9fceada2874804cd75ff649fc94b02de4e664dc8', 'Reporting', 'Reporting', 'Reporting', 'Active', '2016-12-31 15:56:18', '::1'),
(4, 'ID0010010', 'haha', '8a2da05455775e8987cbfac5a0ca54f3f728e274', 'hahaha', 'hahaha', 'User', 'Active', '2017-01-06 15:30:23', '::1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_cabang`
--
ALTER TABLE `tbl_cabang`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_menu`
--
ALTER TABLE `tbl_menu`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_cabang`
--
ALTER TABLE `tbl_cabang`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=42;
--
-- AUTO_INCREMENT for table `tbl_menu`
--
ALTER TABLE `tbl_menu`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=28;
--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
