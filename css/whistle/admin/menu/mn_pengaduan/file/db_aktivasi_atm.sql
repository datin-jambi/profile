-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 30, 2016 at 03:17 PM
-- Server version: 5.6.21
-- PHP Version: 5.5.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `db_aktivasi_atm`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_aktivasi`
--

CREATE TABLE IF NOT EXISTS `tbl_aktivasi` (
`id_aktivasi` int(11) NOT NULL,
  `kode_cabang` varchar(20) NOT NULL,
  `tanggal_aktivasi` varchar(20) NOT NULL,
  `nama_nasabah` varchar(255) NOT NULL,
  `no_kartu` varchar(30) NOT NULL,
  `cif` varchar(20) NOT NULL,
  `alasan_penerbitan` varchar(255) NOT NULL,
  `tanggal_lahir` varchar(20) NOT NULL,
  `nama_ibu_kandung` varchar(255) NOT NULL,
  `flag` varchar(2) NOT NULL DEFAULT '0',
  `nama_cs` varchar(50) NOT NULL,
  `nama_atasan` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_atm`
--

CREATE TABLE IF NOT EXISTS `tbl_atm` (
`id_atm` int(11) NOT NULL,
  `kode_cabang` int(11) NOT NULL,
  `tanggal` varchar(12) NOT NULL,
  `kartu_atm` varchar(10) NOT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `tempat_lahir` varchar(50) NOT NULL,
  `tanggal_lahir` varchar(15) NOT NULL,
  `nama_ibu_kandung` varchar(255) NOT NULL,
  `alamat_rumah` varchar(255) NOT NULL,
  `kode_pos` varchar(20) NOT NULL,
  `telepon` varchar(20) NOT NULL,
  `handphone` varchar(15) NOT NULL,
  `alamat_kantor` varchar(255) NOT NULL,
  `kode_pos_kantor` varchar(20) NOT NULL,
  `telepon_kantor` varchar(20) NOT NULL,
  `kartu_identitas` varchar(10) NOT NULL,
  `no_kartu_identitas` varchar(50) NOT NULL,
  `nama_pada_kartu` varchar(20) NOT NULL,
  `flag` varchar(2) NOT NULL DEFAULT '0',
  `nama_petugas` varchar(50) NOT NULL,
  `nomor_nasabah` varchar(50) NOT NULL,
  `nomor_kartu` varchar(50) NOT NULL,
  `disetujui_tanggal` varchar(15) NOT NULL,
  `nama_atasan` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
(11, 'ID0010023', 'KCP. Kuamang Kuning', 'ID0010023'),
(12, 'ID0010030', 'Cabang Sei Penuh', 'ID0010030'),
(13, 'ID0010031', 'K. Kas Bedeng Delapan', 'ID0010030'),
(14, 'ID0010032', 'K. Kas Siulak Gedang', 'ID0010030'),
(15, 'ID0010040', 'Cabang Bangko', 'ID0010040'),
(16, 'ID0010041', 'K. Kas Pemenang', 'ID0010040'),
(17, 'ID0010050', 'Cabang Ma. Bulian', 'ID0010050'),
(18, 'ID0010051', 'K. Kas Muara Tembesi', 'ID0010050'),
(19, 'ID0010052', 'KCP Sungai Rengas', 'ID0010052'),
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
(31, 'ID0010081', 'KCP Singkut', 'ID0010081'),
(32, 'ID0010082', 'KCP. Mandiangin', 'ID0010082'),
(33, 'ID0010090', 'Cabang Ma. Tebo', 'ID0010090'),
(34, 'ID0010091', 'KCP Rimbo Bujang', 'ID0010091'),
(35, 'ID0010092', 'KCP Sungai Bengkal', 'ID0010092'),
(36, 'ID0010093', 'K. Kas Bangun Seranten', 'ID0010090'),
(37, 'ID0010099', 'Cabang Syariah', 'ID0010099'),
(38, 'ID0010100', 'KCP. Sungai Bahar', 'ID0010100'),
(39, 'ID0010701', 'KCP. Marene', 'ID0010701'),
(40, 'ID0010401', 'KCP. Muaro Delang', 'ID0010401'),
(41, 'ID0010601', 'KCP. Pematang Lumut', 'ID0010601');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_jenis_rekening`
--

CREATE TABLE IF NOT EXISTS `tbl_jenis_rekening` (
`id_jenis_rekening` int(11) NOT NULL,
  `nama_rekening` varchar(50) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_jenis_rekening`
--

INSERT INTO `tbl_jenis_rekening` (`id_jenis_rekening`, `nama_rekening`) VALUES
(2, 'Tabungan'),
(3, 'tes');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_jenis_transaksi`
--

CREATE TABLE IF NOT EXISTS `tbl_jenis_transaksi` (
`id_jenis_transaksi` int(11) NOT NULL,
  `nama_transaksi` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_kelebihan_opname`
--

CREATE TABLE IF NOT EXISTS `tbl_kelebihan_opname` (
`id_kelebihan_opname` int(11) NOT NULL,
  `kode_cabang` varchar(20) NOT NULL,
  `tanggal_pengaduan` varchar(20) NOT NULL,
  `penduduk` varchar(20) NOT NULL,
  `id_jenis_rekening` varchar(20) NOT NULL,
  `nama_pemilik_rekening` varchar(255) NOT NULL,
  `nomor_rekening` varchar(30) NOT NULL,
  `jenis_setoran` varchar(20) NOT NULL,
  `jumlah_setoran` varchar(50) NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `flag` varchar(2) NOT NULL DEFAULT '0',
  `nama_cs` varchar(50) NOT NULL,
  `nama_atasan` varchar(50) NOT NULL,
  `data_upload` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_menu`
--

CREATE TABLE IF NOT EXISTS `tbl_menu` (
`id` int(11) NOT NULL,
  `nama_menu` varchar(50) NOT NULL,
  `tabel_asal` varchar(50) NOT NULL,
  `icon` varchar(50) NOT NULL,
  `link` varchar(100) NOT NULL,
  `lvl` text NOT NULL,
  `stts` varchar(10) NOT NULL DEFAULT 'Non Active',
  `urutan` int(5) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_menu`
--

INSERT INTO `tbl_menu` (`id`, `nama_menu`, `tabel_asal`, `icon`, `link`, `lvl`, `stts`, `urutan`) VALUES
(1, 'Home ', '', 'home ', '?menu=home ', 'Superadmin, CS, Approval, Admin EB, Approval EB', 'Active', 1),
(2, 'Management Menu ', 'tbl_menu ', 'reorder', '?menu=mgmtmn ', 'Superadmin, Admin EB', 'Active', 10),
(3, 'Management Branch', 'tbl_cabang', 'sitemap', '?menu=mgmtbrnch', 'Superadmin, Admin EB', 'Active', 11),
(4, 'Management User ', 'tbl_user ', 'user', '?menu=mgmtusr ', 'Superadmin, Admin EB', 'Active', 12),
(5, 'Report', '', 'file-alt', '?menu=rprt', 'Superadmin, Admin EB, CS', 'Active', 13),
(6, 'Change Password ', '', 'unlock ', '?menu=chgpasswd ', 'Superadmin, CS, Approval, Admin EB, Approval EB', 'Non Active', 14),
(7, 'Logout ', '', 'signout', 'logout.php ', 'Superadmin, CS, Approval, Admin EB, Approval EB', 'Non Active', 15),
(18, 'Jenis Rekening', '', 'reorder', '?menu=jenis_rekening', 'Superadmin,  Admin EB', 'Active', 2),
(19, 'Jenis Transaksi', '', 'user', '?menu=jenis_transaksi', 'Superadmin, Admin EB', 'Active', 2);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_pengaduan_nasabah`
--

CREATE TABLE IF NOT EXISTS `tbl_pengaduan_nasabah` (
`id_pengaduan_nasabah` int(11) NOT NULL,
  `kode_cabang` varchar(20) NOT NULL,
  `tanggal_pengaduan` varchar(30) NOT NULL,
  `nama_lengkap` varchar(50) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `no_telp_hp` varchar(15) NOT NULL,
  `nama_pada_kartu` varchar(20) NOT NULL,
  `no_kartu` varchar(30) NOT NULL,
  `no_rekening` varchar(20) NOT NULL,
  `id_jenis_transaksi` int(11) NOT NULL,
  `tanggal` varchar(20) NOT NULL,
  `jam` varchar(20) NOT NULL,
  `lokasi` varchar(50) NOT NULL,
  `terminal_bank` varchar(20) NOT NULL,
  `keterangan_terminal` varchar(50) NOT NULL,
  `deskripsi_pengaduan` varchar(255) NOT NULL,
  `flag` varchar(2) NOT NULL DEFAULT '0',
  `nama_cs` varchar(50) NOT NULL,
  `nama_atasan` varchar(50) NOT NULL,
  `hari_pengaduan` varchar(20) NOT NULL,
  `tanggal_pengaduan_pusat` varchar(20) NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `tindak_lanjut` varchar(50) NOT NULL,
  `petugas_card_center` varchar(50) NOT NULL,
  `atasan_card_center` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `lvl` varchar(15) NOT NULL DEFAULT 'Credit Analyst',
  `stts` varchar(10) NOT NULL DEFAULT 'Non Active',
  `LastOnline` varchar(20) NOT NULL,
  `LastOnlineIP` varchar(20) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`id`, `kode_cabang`, `uname`, `pwd`, `fname`, `position`, `lvl`, `stts`, `LastOnline`, `LastOnlineIP`) VALUES
(1, 'ID0010001', 'superadmin', '889a3a791b3875cfae413574b53da4bb8a90d53e', 'SUPERADMIN', 'Superadmin', 'Superadmin', 'Active', '2016-10-28 08:50:19', '::1'),
(8, 'ID0010001', 'fajar', '4a5a3869c90fac4dbcc9d693184263de0894fd19', 'FAJAR FEBRIANTO DARMAWAN', '', 'Approval', 'Active', '2016-10-04 14:39:29', '::1'),
(9, 'ID0010001', 'acin', 'f0431fb16a0b51feee0bb9b1118b26b0e5eedead', 'ACHIEN ANTONY', '', 'Admin EB', 'Active', '2016-10-20 08:38:58', '::1'),
(10, 'ID0010001', 'rahmawati', '295e79be0d80c7fc5726c36ce8cbd90a424b4d9b', 'RAHMAWATI', '', 'Approval EB', 'Active', '', ''),
(11, 'ID0010001', 'nasifah', '03dc93bce620d82f5eef53337f5ec0a6932e7d7a', 'NASIFAH', '', 'CS', 'Active', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_aktivasi`
--
ALTER TABLE `tbl_aktivasi`
 ADD PRIMARY KEY (`id_aktivasi`);

--
-- Indexes for table `tbl_atm`
--
ALTER TABLE `tbl_atm`
 ADD PRIMARY KEY (`id_atm`);

--
-- Indexes for table `tbl_cabang`
--
ALTER TABLE `tbl_cabang`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_jenis_rekening`
--
ALTER TABLE `tbl_jenis_rekening`
 ADD PRIMARY KEY (`id_jenis_rekening`);

--
-- Indexes for table `tbl_jenis_transaksi`
--
ALTER TABLE `tbl_jenis_transaksi`
 ADD PRIMARY KEY (`id_jenis_transaksi`);

--
-- Indexes for table `tbl_kelebihan_opname`
--
ALTER TABLE `tbl_kelebihan_opname`
 ADD PRIMARY KEY (`id_kelebihan_opname`);

--
-- Indexes for table `tbl_menu`
--
ALTER TABLE `tbl_menu`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_pengaduan_nasabah`
--
ALTER TABLE `tbl_pengaduan_nasabah`
 ADD PRIMARY KEY (`id_pengaduan_nasabah`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_aktivasi`
--
ALTER TABLE `tbl_aktivasi`
MODIFY `id_aktivasi` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_atm`
--
ALTER TABLE `tbl_atm`
MODIFY `id_atm` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_cabang`
--
ALTER TABLE `tbl_cabang`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=42;
--
-- AUTO_INCREMENT for table `tbl_jenis_rekening`
--
ALTER TABLE `tbl_jenis_rekening`
MODIFY `id_jenis_rekening` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `tbl_jenis_transaksi`
--
ALTER TABLE `tbl_jenis_transaksi`
MODIFY `id_jenis_transaksi` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_kelebihan_opname`
--
ALTER TABLE `tbl_kelebihan_opname`
MODIFY `id_kelebihan_opname` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_menu`
--
ALTER TABLE `tbl_menu`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `tbl_pengaduan_nasabah`
--
ALTER TABLE `tbl_pengaduan_nasabah`
MODIFY `id_pengaduan_nasabah` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
