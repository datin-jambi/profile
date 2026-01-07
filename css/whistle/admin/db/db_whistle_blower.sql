-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 06, 2019 at 04:49 AM
-- Server version: 10.1.8-MariaDB
-- PHP Version: 5.6.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_whistle_blower`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_beranda`
--

CREATE TABLE `tbl_beranda` (
  `id_beranda` int(13) NOT NULL,
  `nama_beranda` text CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `isi_beranda` text CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_beranda`
--

INSERT INTO `tbl_beranda` (`id_beranda`, `nama_beranda`, `isi_beranda`) VALUES
(1, 'Para Karyawan, Nasabah, dan Rekanan ', '&lt;span&gt;Dalam rangka mendukung penerapan Tata Kelola&lt;i&gt;&nbsp;&lt;/i&gt;Perusahaan &lt;i&gt;(Good Corporate Governance)&lt;/i&gt;&nbsp;yang baik&nbsp;dan&nbsp;meningkatkan efektivitas penerapan sistem pengendalian&nbsp;&lt;i&gt;f&lt;/i&gt;&lt;i&gt;raud&lt;/i&gt;&lt;i&gt;. &lt;/i&gt;Bank Jambi menyediakan sarana pelaporan pelanggaran yang disebut &lt;i&gt;Whistle&lt;/i&gt;&lt;i&gt;b&lt;/i&gt;&lt;i&gt;lowing System-Bank Jambi&lt;/i&gt;&nbsp;guna memberikan alternatif sarana pelaporan pelanggaran yang lebih efektif karena alasan kerahasian dan/atau tindak lanjut yang diharapkan tidak dapat dipenuhi.&lt;br&gt;&lt;/span&gt;&lt;span&gt;&lt;br&gt;Hal ini sejalan dengan komitmen Bank Jambi untuk menerapkan budaya &lt;i&gt;anti Fraud&lt;/i&gt;&nbsp;dengan yakni &lt;i&gt;"No Fraud Tolerance" &lt;/i&gt;sesuai dengan SE BI nomor 13/28/DPNP tanggal 09 Desember 2011 Perihal Penerapan Strategi Anti Fraud bagi Bank Umum.&lt;br&gt;		&lt;br&gt;&lt;/span&gt;&lt;span&gt;Apabila terdapat Insan Bank Jambi yang melakukan pelanggaran dalam hal benturan kepentingan,&lt;i&gt;&nbsp;fraud, &lt;/i&gt;kode etik dan penyuapan/gratifikasi maka diharapkan kerjasama dan peran aktif dari karyawan, nasabah, dan rekanan agar berkenan menyampaikan pengaduan pelanggaran pada &lt;i&gt;Whistle&lt;/i&gt;&lt;i&gt;b&lt;/i&gt;&lt;i&gt;lowing System-Bank Jambi&lt;/i&gt;&lt;i&gt;.&lt;/i&gt;&lt;/span&gt;&lt;br&gt;&lt;br&gt;&lt;br&gt;'),
(3, 'Tujuan Whistleblowing System ', '&lt;span&gt;Tujuan &lt;i&gt;Whistleblowing System&lt;/i&gt;&nbsp;-Bank Jambi adalah :&lt;/span&gt;&lt;br&gt;&lt;ol&gt;&lt;li&gt;Sebagai sarana pelaporan pelanggaran yang bisa dimanfaatkan secara efektif dan tetap menjaga kerahasian pelapor.&lt;/li&gt;&lt;li&gt;Sebagai sarana untuk memperoleh bukti awal yang bisa digunakan untuk menyelidiki pelanggaran terutama apabila kasus melibatkan insan Bank Jambi;&lt;/li&gt;&lt;li&gt;Sebagai sarana deteksi dini dan pencegahan apabila terjadinya pelanggaran yang melibatkan insan Bank Jambi; dan&lt;/li&gt;&lt;li&gt;&lt;span&gt;Sebagai&lt;i&gt;&nbsp;best practice&lt;/i&gt;&nbsp;implementasi &lt;i&gt;Good Corporate Governance&lt;/i&gt;&nbsp;(GCG) guna&nbsp;meningkatkan reputasi perusahaan.&lt;/span&gt;&lt;/li&gt;&lt;/ol&gt;&lt;br&gt;'),
(4, 'Sarana Penyampaian Pelaporan', '&lt;div&gt;&lt;ul&gt;&lt;li&gt;&lt;b&gt;bankjambi.co.id/whistleblower&lt;/b&gt;&lt;/li&gt;&lt;li&gt;&lt;b&gt;wbs@bankjambi.co.id&nbsp; &lt;/b&gt;(hanya untuk penyampaian penambahan bukti pendukung)&lt;/li&gt;&lt;/ul&gt;&lt;/div&gt;&lt;br&gt;'),
(6, 'Hal-Hal Yang Harus Dipenuhi Oleh Pelapor', 'Untuk mempermudah dan mempercepat proses tindak lanjut, berikut ini adalah hal-hal yang harus dipenuhi oleh pelapor dalam menyampaikan pengaduannya yakni sekurang-kurangnya :&lt;br&gt;&lt;ul&gt;&lt;li&gt;&nbsp;Nama pelapor (diperbolehkan menggunakan anonim);dan	&lt;/li&gt;&lt;li&gt;&lt;span&gt;&nbsp;Nomor telepon dan alamat&nbsp;&lt;i&gt;e-mail&lt;/i&gt;&nbsp;yang dapat dihubungi.&lt;/span&gt;&lt;/li&gt;&lt;/ul&gt;&lt;br&gt;Pengaduan Anda akan mudah ditindaklanjuti apabila telah memberikan indikasi awal yang dapat dipertanggung jawabkan (4W + 1 H) yang meliputi :&lt;br&gt;&lt;ol&gt;&lt;/ol&gt;&lt;ul&gt;&lt;li&gt;&lt;span&gt;&lt;i&gt;Who	&lt;/i&gt;: &nbsp;Siapa yang terlibat dalam pelanggaran tersebut.&lt;/span&gt;&lt;/li&gt;&lt;li&gt;&lt;span&gt;&lt;i&gt;What	&lt;/i&gt;: &nbsp;Indikasi pelanggaran yang diduga dilakukan.&lt;/span&gt;&lt;/li&gt;&lt;li&gt;&lt;span&gt;&lt;i&gt;Where	&lt;/i&gt;: &nbsp;Tempat terjadinya pelanggaran tersebut dilakukan&lt;/span&gt;&lt;/li&gt;&lt;li&gt;&lt;span&gt;&lt;i&gt;When	&lt;/i&gt;: &nbsp;Waktu terjadinya pelanggaran tersebut dilakukan.&lt;/span&gt;&lt;/li&gt;&lt;li&gt;&lt;span&gt;&lt;i&gt;How	&lt;/i&gt;: &nbsp;Bagaimana pelanggaran tersebut dilakukan (modus, cara, dsb.).&lt;/span&gt;&lt;/li&gt;&lt;li&gt;Dokumen pendukung dan/atau bukti lainnya (bila ada)&lt;/li&gt;&lt;/ul&gt;&lt;ol&gt;&lt;/ol&gt;&lt;br&gt;Laporan pelanggaran yang Anda sampaikan harus berhubungan dengan:&lt;br&gt;&lt;ul&gt;&lt;li&gt;&lt;p&gt;Benturan Kepentingan adalah suatu kondisi dimana insan Bank dalam \r\nmenjalankan tugas dan kewajibannya mempunyai kepentingan diluar \r\nkepentingan perusahaan, baik yang menyangkut kepentingan pribadi, \r\nkeluarga, maupun kepentingan pihak-pihak lain sehingga insan Bank \r\ntersebut dimungkinkan kehilangan obyektivitasnya dalam mengambil \r\nkeputusan dan kebijakan sesuai wewenang yang telah diberikan perusahaan \r\nkepadanya. &lt;/p&gt;&lt;br&gt;&lt;/li&gt;&lt;/ul&gt;&lt;blockquote&gt;Contoh :&lt;/blockquote&gt;&lt;blockquote&gt;&lt;ol&gt;&lt;li&gt;Pemberian fasilitas kredit kepada diri sendiri/keluarga yang terindikasi melanggar ketentuan dan berpotensi merugikan Bank Jambi secara ekonomis.&lt;/li&gt;&lt;li&gt;Pemberian keputusan penunjukan pihak tertentu sebagai penyedia barang/jasa, dimana Insan Bank Jambi tersebut mempunyai kepentingan ekonomis pada pihak yang ditunjuk tersebut.&lt;/li&gt;&lt;/ol&gt;&lt;br&gt;&lt;/blockquote&gt;&lt;ul&gt;&lt;li&gt;&lt;i&gt;&lt;/i&gt;&lt;span&gt;&lt;i&gt;&lt;p&gt;Fraud&lt;/i&gt;&nbsp;adalah tindakan penyimpangan yang \r\nsengaja dilakukan atau pembiaran yang dirancang untuk mengelabui, \r\nmenipu, atau memanipulasi Bank, nasabah, atau pihak lain, yang terjadi \r\ndi lingkungan Bank dan/atau menggunakan sarana Bank sehingga \r\nmengakibatkan Bank, nasabah, atau pihak lain menderita kerugian dan/atau\r\n pelaku&nbsp;&lt;i&gt;fraud&lt;/i&gt;&nbsp;memperoleh keuntungan keuangan baik secara langsung maupun tidak langsung.&lt;span&gt; Adapun Jenis-jenis perbuatan yang tergolong&lt;i&gt;&nbsp;fraud&lt;/i&gt;\r\n mencakup kecurangan, penipuan, penggelapan asset,&nbsp; pembocoran \r\ninformasi, tindak pidana perbankan (tipibank), dan tindakan-tindakan \r\nlainnya yang dapat dipersamakan dengan itu.&lt;/p&gt;&lt;/span&gt;&lt;/span&gt;&lt;/li&gt;&lt;/ul&gt;&lt;blockquote&gt;Contoh :&lt;br&gt;&lt;ol&gt;&lt;li&gt;Membuat dan/atau menggunakan dan/atau memberikan dan/atau mengubah dan/atau menyalin dan/atau menggandakan data dan/atau keterangan yang tidak sesuai dengan sebenarnya sehingga merugikan Perusahaan dan/atau nasabah.&lt;/li&gt;&lt;li&gt;Menyalahgunakan/mengambil tanpa alasan hak uang/barang/ data/dokumen milik perusahaan dan/atau nasabah di lingkungan kerja.&lt;/li&gt;&lt;/ol&gt;&lt;br&gt;&lt;/blockquote&gt;&lt;ul&gt;&lt;li&gt;&lt;p&gt;Kode Etik \r\nadalah penjabaran dari nilai budaya perusahaan yang telah dirumuskan \r\nberdasarkan nilai-nilai positif yang tumbuh dan berkembang pada segenap \r\ninsan Bank, untuk mencapai tujuan bersama dan digunakan sebagai \r\npedoman/petunjuk bagi insan perusahaan dalam mengambil keputusan dan \r\nbertindak.&lt;/p&gt;&lt;/li&gt;&lt;/ul&gt;&lt;blockquote&gt;Contoh:&lt;br&gt;&lt;ol&gt;&lt;li&gt;Tidak menjaga rahasia bank dan rahasia jabatan sebagaimana ditentukan oleh Bank Jambi.&lt;/li&gt;&lt;li&gt;Melakukan perbuatan asusila atau perjudian lingkungan kerja pada waktu kerja.&lt;/li&gt;&lt;li&gt;Mabuk, meminum minuman keras yang memabukkan, memakai dan/atau mengedarkan narkotika dan/atau zat adiktif lainnya di lingkungan perusahaan dan/atau pada waktu kerja.&lt;/li&gt;&lt;/ol&gt;&lt;br&gt;&lt;/blockquote&gt;&lt;ul&gt;&lt;li&gt;&lt;p&gt;Penyuapan/Gratifikasi adalah menerima sesuatu \r\ndalam bentuk apapun dan berapapun jumlah/nilainya dari pihak lain yang \r\nterkait dengan jabatan/wewenang/tanggung jawabnya di Bank.&lt;/p&gt;&lt;/li&gt;&lt;/ul&gt;&lt;blockquote&gt;Contoh :&lt;br&gt;&lt;/blockquote&gt;&lt;blockquote&gt;&lt;ol&gt;&lt;li&gt;Menerima imbalan secara langsung maupun tidak langsung dalam bentuk apapun dari pihak manapun yang terkait dengan tugas dan tanggung jawab.&lt;/li&gt;&lt;li&gt;Melakukan pungutan tidak sah dalam bentuk apapun juga dalam menjalankan tugasnya untuk kepentingan pribadi/golongan/pihak lain.&lt;br&gt;&lt;/li&gt;&lt;/ol&gt;&lt;/blockquote&gt;'),
(8, 'Perlindungan Pelapor', '&lt;ul&gt;&lt;li&gt;Perlindungan kepada Pelapor tindak pelanggaran pada prinsipnya adalah hak dari Pelapor guna memberikan rasa aman kepada Pelapor terkait dengan ancaman/tindakan yang didapat akibat laporan pelanggaran yang disampaikan. &lt;/li&gt;&lt;/ul&gt;&lt;ul&gt;&lt;li&gt;&lt;span&gt;Perlindungan hanya dapat diberikan kepada Pelapor dengan kategori (&lt;i&gt;disclosure).&lt;/i&gt;&lt;/span&gt;&lt;/li&gt;&lt;/ul&gt;&lt;ul&gt;&lt;li&gt;&lt;span&gt;Dalam hal Pelapor menerima ancaman terhadap pekerjaan, fisik, remunerasi maupun fasilitas pekerjaan yang diterima dari pihak lainnya, maka Pelapor menyampaikan permohonan perlindungan kepada Penanggung Jawab &lt;i&gt;Whistleblowing System-Bank Jambi&lt;/i&gt;&nbsp;yakni Direktur Utama.&lt;/span&gt;&lt;/li&gt;&lt;/ul&gt;&lt;ul&gt;&lt;li&gt;Permintaan perlindungan dapat disampaikan melalui surat atau email. &lt;/li&gt;&lt;/ul&gt;&lt;ul&gt;&lt;li&gt;&lt;span&gt;Bentuk perlindungan terhadap Pelapor disesuaikan dengan bentuk ancaman/ tindakan balasan yang diterima. &lt;br&gt;&lt;/span&gt;&lt;/li&gt;&lt;/ul&gt;&lt;ul&gt;&lt;li&gt;Pemberian perlindungan dilakukan dengan tetap memperhatikan azas kerahasian dan ketentuan terkait yang berlaku di Bank. &lt;/li&gt;&lt;/ul&gt;&lt;ul&gt;&lt;li&gt;Dengan pertimbangan tertentu, Bank Jambi juga dapat memberikan perlindungan kepada keluarga Pelapor. &lt;/li&gt;&lt;/ul&gt;&lt;ul&gt;&lt;li&gt;Pemberian perlindungan kepada Pelapor dapat pula ditolak atau dihentikan, apabila terdapat bukti bahwa Pelapor tidak melakukan kewajibannya dalam menjaga kerahasiaan identitas diri dan laporannya. &lt;/li&gt;&lt;/ul&gt;&lt;ul&gt;&lt;li&gt;&lt;span&gt;Perlindungan tidak akan diberikan atau akan dihentikan pemberiannya apabila dikemudian hari terbukti bahwa laporan pelanggaran yang disampaikan oleh Pelapor kepada Bank ternyata palsu/fitnah atau mempunyai tujuan yang menyimpang dari tujuan kebijakan &lt;i&gt;Whistleblowing System-Bank Jambi.&lt;/i&gt;&lt;/span&gt;&lt;/li&gt;&lt;/ul&gt;&lt;br&gt;'),
(10, 'Penanganan Pelaporan', '&lt;ul&gt;&lt;li&gt;&lt;span&gt;Untuk menjaga kerahasian, maka identitas Pelapor beserta laporan yang disampaikannya hanya diketahui oleh petugas &lt;i&gt;Whistleblowing System-Bank Jambi.&lt;/i&gt;&lt;/span&gt;&lt;/li&gt;&lt;/ul&gt;&lt;ul&gt;&lt;li&gt;&lt;span&gt;Untuk memperlancar proses tindaklanjut atas tindak pelanggaran yang dilaporkannya, petugas &lt;i&gt;Whistleblowing System-Bank Jambi&lt;/i&gt;&nbsp;dapat meminta tambahan informasi kepada pelapor.&lt;/span&gt;&lt;/li&gt;&lt;/ul&gt;&lt;ul&gt;&lt;li&gt;Kepada Pelapor diberikan hak untuk memantau perkembangan tindaklanjut tindakan pelanggaran yang dilaporkannnya melalui saluran telepon dan email yang disediakan dengan menyebutkan kode tanda terima laporan.&lt;/li&gt;&lt;/ul&gt;&lt;ul&gt;&lt;li&gt;Jangka waktu untuk mendapatkan tanggapan paling lambat 15 hari kerja setelah laporan terima.&lt;/li&gt;&lt;/ul&gt;&lt;br&gt;'),
(11, 'Tindak Lanjut Laporan', '&lt;ul&gt;&lt;li&gt;Tindakan Terhadap Pelapor Yang Terindikasi Memfitnah&lt;/li&gt;&lt;/ul&gt;&lt;blockquote&gt;&lt;span&gt;Sanksi dapat diberikan kepada Pelapor apabila terbukti bahwa laporan yang disampaikan ternyata fitnah dan terbukti bahwa laporan yang disampaikan mempunyai tujuan lain yang menyimpang dari maksud dan tujuan kebijakan&lt;i&gt;Whistleblowing System-Bank Jambi. &lt;/i&gt;Sanksi dimaksud mengacu kepada Peraturan Pemberian Sanksi/ Hukuman atas Pelanggaran Karyawan PT.Bank Pembangunan Daerah Jambi yang berlaku.&lt;/span&gt;&lt;br&gt;&lt;br&gt;&lt;/blockquote&gt;&lt;ul&gt;&lt;li&gt;Sanksi bagi Terlapor&lt;/li&gt;&lt;/ul&gt;&lt;blockquote&gt;Terlapor yang terlibat dalam pelanggaran akan diproses sesuai dengan Peraturan Pemberian Sanksi/ Hukuman atas Pelanggaran Karyawan PT.Bank Pembangunan Daerah Jambi yang berlaku.&lt;br&gt;&lt;/blockquote&gt;&lt;br&gt;'),
(12, '', 'Salam hangat,&nbsp;&lt;h3&gt;PT. Bank Pembangunan Daerah Jambi&lt;/h3&gt;'),
(13, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_cabang`
--

CREATE TABLE `tbl_cabang` (
  `id` int(11) NOT NULL,
  `kode_cabang` varchar(10) NOT NULL,
  `nama_cabang` varchar(30) NOT NULL,
  `kode_cabang_induk` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
-- Table structure for table `tbl_faq`
--

CREATE TABLE `tbl_faq` (
  `id_faq` int(13) NOT NULL,
  `nama_faq` text NOT NULL,
  `isi_faq` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_faq`
--

INSERT INTO `tbl_faq` (`id_faq`, `nama_faq`, `isi_faq`) VALUES
(1, 'Apakah aplikasi Whistleblowing System Bank Jambi ini?', 'Aplikasi Whistleblowing System Bank Jambi adalah sarana pengelolaan dan tindak lanjut pengaduan serta pelaporan hasil pengelolaan pengaduan yang disediakan oleh Bank Jambi sebagai salah satu sarana bagi pihak internal dan pihak eksternal untuk melaporkan dugaan adanya pelanggaran yang dilakukan oleh Insan Bank Jambi.'),
(2, 'Mengapa saya harus melaporkan pelanggaran ?', 'Guna mendukung Bank Jambi untuk meningkatkan efektivitas penerapan sistem pengendalian fraud dan mendukung penerapan Tata Kelola Perusahaan&lt;i&gt; (Good Corporate Governance)&lt;/i&gt; yang baik'),
(3, 'Siapa sajakah yang dapat menyampaikan pengaduan pelanggaran melalui Whistleblowing System-Bank Jambi ini ?', 'Pihak Internal maupun Eksternal yang memiliki kepentingan dengan pihak Bank.'),
(4, 'Dapatkah saya memilih untuk tidak memberitahukan identitas diri Saya?', 'Pelapor dapat memilih untuk mengungkapkan identitasnya atau tidak. Namun Pelaporan tanpa identitas Pelapor memiliki konsekuensi dan kemungkinan tidak dapat ditindaklanjuti terutama berkenaan dengan potensi tidak dapat dilengkapinya informasi dalam rangka proses penanganan laporan oleh Bank.'),
(5, 'Apa yang perlu saya laporkan dalam Whistleblowing System-Bank Jambi ini?', 'Jika Saudara melaporkan suatu pelanggaran, maka Saudara sebaiknya memberikan informasi selengkap mungkin sebagaimana yang diminta dalam formulir Laporan Pelanggaran yang terdapat pada website ini.'),
(6, 'Apakah bentuk respon yang diberikan kepada pelapor atas pengaduan yang disampaikan?', 'Respon yang diberikan kepada pelapor berupa respon awal yakni ucapan terima kasih telah melakukan pengaduan dan status/tindak lanjut pengaduan paling akhir sesuai dengan respon yang telah diberikan oleh pihak penerima pengaduan. Respon terkait dengan status/tindak lanjut pengaduan dapat dilihat dalam tindak lanjut pengaduan aplikasi Whistleblowing System-Bank Jambi ini'),
(7, 'Setelah saya membuat laporan, apakah saya akan dilindungi?', 'Bank memiliki kebijakan untuk memberikan perlindungan dan menjamin kerahasiaan laporan maupun identitas setiap pihak Pelapor.'),
(8, 'Apakah saya dapat memonitor laporan tersebut ?', 'Saudara dapat melihat perkembangan status laporan dengan melihat pada kolom tindaklanjut pada Whistleblowing System - Bank Jambi ini.'),
(9, 'Saya sudah mengirimkan laporan pelanggaran namun di kemudian hari saya ingin merubah/menambahkan data terkait laporan yang saya lakukan,  Apakah harus membuat pengaduan baru?', 'Data yang sudah dilaporkan sebelumnya tidak dapat dilakukan perubahan namun Saudara dapat menambahkan data lain terkait laporan pelanggaran dengan mengunggah data dalam bentuk seperti dokumen, foto, video, dan lainnya melalui email wbs@bankjambi.co.id');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_menu`
--

CREATE TABLE `tbl_menu` (
  `id` int(11) NOT NULL,
  `nama_menu` varchar(30) NOT NULL,
  `master_menu` varchar(30) NOT NULL,
  `tabel_asal` varchar(50) NOT NULL,
  `icon` varchar(50) NOT NULL,
  `link` varchar(100) NOT NULL,
  `lvl` text NOT NULL,
  `stts` varchar(10) NOT NULL DEFAULT 'Non Active',
  `urutan` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `tbl_menu`
--

INSERT INTO `tbl_menu` (`id`, `nama_menu`, `master_menu`, `tabel_asal`, `icon`, `link`, `lvl`, `stts`, `urutan`) VALUES
(1, 'Home ', 'Home', '', 'icon icon-dashboard', '?menu=home ', 'Superadmin, CS Credit, Checker, Credit Analyst, Approver', 'Active', 1),
(2, 'Management Menu ', 'Management Data', 'tbl_menu ', 'sitemap ', '?menu=mgmtmn', 'Superadmin ', 'Active', 2),
(3, 'Management Branch', 'Management Data', 'tbl_cabang', 'icon icon-th-list', '?menu=mgmtbrnch', 'Superadmin', 'Passive', 3),
(4, 'Management User ', 'Management Data', 'tbl_user ', 'icon icon-inbox', '?menu=mgmtusr ', 'Superadmin, User', 'Active', 4),
(27, 'Report', 'Report', '', 'icon icon-file', '?menu=report', 'Superadmin', 'Passive', 27),
(28, 'Kelola Home', 'Kelola Website', 'tbl_rumah', 'icon icon-tint', '?menu=rumah', 'Superadmin, User', 'Active', 5),
(29, 'Management Pelanggaran', 'Management Data', 'tbl_pelanggaran', 'icon icon-info-sign', '?menu=pelanggaran', 'Superadmin, User', 'Active', 4),
(30, 'Pengaduan ', 'Pengaduan', 'tbl_pengaduan_pelanggaran', 'icon icon-info-sign', '?menu=pengaduan', 'Superadmin, User', 'Active', 9),
(31, 'Tindak Lanjut', 'Tindak Lanjut', '', 'icon icon-inbox', '?menu=tindaklanjut', 'Superadmin, User', 'Active', 10),
(32, 'Kelola Beranda', 'Kelola Website', 'tbl_beranda', 'icon icon-pencil', '?menu=beranda', 'Superadmin, User', 'Active', 6),
(33, 'Kelola Perlindungan', 'Kelola Website', 'tbl_perlindungan', 'icon icon-star-half', '?menu=perlindungan', 'Superadmin, User', 'Passive', 7),
(34, 'Kelola Faq', 'Kelola Website', 'tbl_faq', 'icon icon-screenshot', '?menu=faq', 'Superadmin, User', 'Active', 8);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_pelanggaran`
--

CREATE TABLE `tbl_pelanggaran` (
  `id_pelanggaran` int(13) NOT NULL,
  `jenis_pelanggaran` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_pelanggaran`
--

INSERT INTO `tbl_pelanggaran` (`id_pelanggaran`, `jenis_pelanggaran`) VALUES
(1, 'Benturan Kepentingan'),
(2, 'Fraud'),
(3, 'Kode Etik'),
(5, 'Penyuapan / Gratifikasi');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_pengaduan_pelanggaran`
--

CREATE TABLE `tbl_pengaduan_pelanggaran` (
  `id_pengaduan_pelanggaran` int(13) NOT NULL,
  `nomor_referensi` varchar(10) NOT NULL,
  `nama_pelapor` varchar(50) NOT NULL,
  `nomor_telepon` varchar(15) NOT NULL,
  `email` varchar(30) NOT NULL,
  `nama_pelaku` varchar(50) NOT NULL,
  `id_pelanggaran` int(13) NOT NULL,
  `uraian_pelanggaran` text NOT NULL,
  `waktu_kejadian` date NOT NULL,
  `tempat_terjadi` varchar(50) NOT NULL,
  `kronologis_permasalahan` text NOT NULL,
  `unggah_file` text NOT NULL,
  `captcha` varchar(20) NOT NULL,
  `tindak_lanjut1` varchar(20) NOT NULL,
  `tindak_lanjut2` varchar(20) NOT NULL,
  `tindak_lanjut3` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_pengaduan_pelanggaran`
--

INSERT INTO `tbl_pengaduan_pelanggaran` (`id_pengaduan_pelanggaran`, `nomor_referensi`, `nama_pelapor`, `nomor_telepon`, `email`, `nama_pelaku`, `id_pelanggaran`, `uraian_pelanggaran`, `waktu_kejadian`, `tempat_terjadi`, `kronologis_permasalahan`, `unggah_file`, `captcha`, `tindak_lanjut1`, `tindak_lanjut2`, `tindak_lanjut3`) VALUES
(1, 'A001', 'Asmuni', '085383249956', 'muni4420@gmail.com', 'Bank 9 jambi', 1, 'Tidak melibatkan KSP, UMKM dll', '2018-12-11', 'Bank 9 jambi', 'Mohon kerja sama agar gaji setiap PNS yg bersangkutan tetap di ambil oleh bendahara setiap instansi agar tidak terjadi kemacetan cicilan pada KSP, UMKM, dll...', 'IMG-20181211-WA0001.jpg', 'FtFR', '', '', 'Tidak Diproses'),
(2, 'A001', 'Imam Ari Saputra', '081278352116', 'moncielari@gmail.com', 'Imam Ari Saputra', 1, 'Saya mempunyai aplikasi M-banking di android saya, namun saya lupa akan user name dan password nya. jadi bagaimana cara nya atau agar saya bisa terhubung untuk submit/login nya kembali?', '2019-02-25', 'Di rumah sendiri', 'Pas waktu mau login/submit ke aplikasi M-banking tersebut dengan memasukan User Name(Imam15021997) Password(18032018).\r\nNamun user name id tidak sesuai.\r\nNtah itu username nya salah atau password saya lupa', 'Screenshot_2019-02-25-16-00-14-91.png', 'bu0q', '', '', 'Tidak Diproses');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_perlindungan`
--

CREATE TABLE `tbl_perlindungan` (
  `id_perlindungan` int(11) NOT NULL,
  `nama_perlindungan` text NOT NULL,
  `isi_perlindungan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_perlindungan`
--

INSERT INTO `tbl_perlindungan` (`id_perlindungan`, `nama_perlindungan`, `isi_perlindungan`) VALUES
(1, 'Perlindungan Pelapor', 'Atas laporan yang terbukti kebenarannya, Bank Jambi akan memberikan perlindungan bagi pelapor. yang meliputi:\r\n1. kerahasiaan identitas pelapor dan isi laporan yang disampaikan\r\n2. perlindungan terhadap perlakuan yang merugikan pelapor\r\n3. perlindungan kemungkinan adanya tindakan ancaman, intimidasi, hukuman ataupun tindakan tidak menyenangkan dari pihak terlapor '),
(2, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_rumah`
--

CREATE TABLE `tbl_rumah` (
  `id_rumah` int(13) NOT NULL,
  `nama_rumah` varchar(250) NOT NULL,
  `isi_rumah` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_rumah`
--

INSERT INTO `tbl_rumah` (`id_rumah`, `nama_rumah`, `isi_rumah`) VALUES
(1, 'Welcome To Whistleblowing System Bank Jambi', 'WBS-Bank Jambi adalah sarana yang disediakan oleh Bank Jambi bagi Anda yang memiliki dan ingin melaporkan dugaan terjadinya pelanggaran benturan kepentingan, fraud, kode etik, penyuapan/gratifikasi yang dilakukan oleh pihak internal Bank Jambi.');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `id` int(11) NOT NULL,
  `uname` varchar(30) NOT NULL,
  `pwd` varchar(50) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `position` varchar(50) NOT NULL,
  `lvl` varchar(15) NOT NULL DEFAULT 'User',
  `stts` varchar(10) NOT NULL DEFAULT 'Passive',
  `LastOnline` varchar(20) NOT NULL,
  `LastOnlineIP` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`id`, `uname`, `pwd`, `fname`, `position`, `lvl`, `stts`, `LastOnline`, `LastOnlineIP`) VALUES
(1, 'superadmin', '7d5842c3f4983371c94b827a7e3bea7e6ecd77ea', 'Superadmin', 'Superadmin', 'Superadmin', 'Active', '2019-01-14 10:20:29', '172.9.1.59'),
(5, 'antifraud', 'de6afd23b076474b70f83a112c31a89440d7dff8', 'Anti Fraud', 'Pelaksana Anti Fraud', 'User', 'Active', '2019-05-02 17:39:32', '172.9.1.2');

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_pelanggaran`
--
CREATE TABLE `v_pelanggaran` (
`jenis_pelanggaran` varchar(50)
,`id_pengaduan_pelanggaran` int(13)
,`nomor_referensi` varchar(10)
,`nama_pelapor` varchar(50)
,`nomor_telepon` varchar(15)
,`email` varchar(30)
,`nama_pelaku` varchar(50)
,`id_pelanggaran` int(13)
,`uraian_pelanggaran` text
,`waktu_kejadian` date
,`tempat_terjadi` varchar(50)
,`kronologis_permasalahan` text
,`unggah_file` text
,`captcha` varchar(20)
,`tindak_lanjut1` varchar(20)
,`tindak_lanjut2` varchar(20)
,`tindak_lanjut3` varchar(20)
);

-- --------------------------------------------------------

--
-- Structure for view `v_pelanggaran`
--
DROP TABLE IF EXISTS `v_pelanggaran`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_pelanggaran`  AS  select `tbl_pelanggaran`.`jenis_pelanggaran` AS `jenis_pelanggaran`,`tbl_pengaduan_pelanggaran`.`id_pengaduan_pelanggaran` AS `id_pengaduan_pelanggaran`,`tbl_pengaduan_pelanggaran`.`nomor_referensi` AS `nomor_referensi`,`tbl_pengaduan_pelanggaran`.`nama_pelapor` AS `nama_pelapor`,`tbl_pengaduan_pelanggaran`.`nomor_telepon` AS `nomor_telepon`,`tbl_pengaduan_pelanggaran`.`email` AS `email`,`tbl_pengaduan_pelanggaran`.`nama_pelaku` AS `nama_pelaku`,`tbl_pengaduan_pelanggaran`.`id_pelanggaran` AS `id_pelanggaran`,`tbl_pengaduan_pelanggaran`.`uraian_pelanggaran` AS `uraian_pelanggaran`,`tbl_pengaduan_pelanggaran`.`waktu_kejadian` AS `waktu_kejadian`,`tbl_pengaduan_pelanggaran`.`tempat_terjadi` AS `tempat_terjadi`,`tbl_pengaduan_pelanggaran`.`kronologis_permasalahan` AS `kronologis_permasalahan`,`tbl_pengaduan_pelanggaran`.`unggah_file` AS `unggah_file`,`tbl_pengaduan_pelanggaran`.`captcha` AS `captcha`,`tbl_pengaduan_pelanggaran`.`tindak_lanjut1` AS `tindak_lanjut1`,`tbl_pengaduan_pelanggaran`.`tindak_lanjut2` AS `tindak_lanjut2`,`tbl_pengaduan_pelanggaran`.`tindak_lanjut3` AS `tindak_lanjut3` from (`tbl_pelanggaran` join `tbl_pengaduan_pelanggaran` on((`tbl_pelanggaran`.`id_pelanggaran` = `tbl_pengaduan_pelanggaran`.`id_pelanggaran`))) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_beranda`
--
ALTER TABLE `tbl_beranda`
  ADD PRIMARY KEY (`id_beranda`);

--
-- Indexes for table `tbl_cabang`
--
ALTER TABLE `tbl_cabang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_faq`
--
ALTER TABLE `tbl_faq`
  ADD PRIMARY KEY (`id_faq`);

--
-- Indexes for table `tbl_menu`
--
ALTER TABLE `tbl_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_pelanggaran`
--
ALTER TABLE `tbl_pelanggaran`
  ADD PRIMARY KEY (`id_pelanggaran`);

--
-- Indexes for table `tbl_pengaduan_pelanggaran`
--
ALTER TABLE `tbl_pengaduan_pelanggaran`
  ADD PRIMARY KEY (`id_pengaduan_pelanggaran`);

--
-- Indexes for table `tbl_perlindungan`
--
ALTER TABLE `tbl_perlindungan`
  ADD PRIMARY KEY (`id_perlindungan`);

--
-- Indexes for table `tbl_rumah`
--
ALTER TABLE `tbl_rumah`
  ADD PRIMARY KEY (`id_rumah`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_beranda`
--
ALTER TABLE `tbl_beranda`
  MODIFY `id_beranda` int(13) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `tbl_cabang`
--
ALTER TABLE `tbl_cabang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;
--
-- AUTO_INCREMENT for table `tbl_faq`
--
ALTER TABLE `tbl_faq`
  MODIFY `id_faq` int(13) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `tbl_menu`
--
ALTER TABLE `tbl_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
--
-- AUTO_INCREMENT for table `tbl_pelanggaran`
--
ALTER TABLE `tbl_pelanggaran`
  MODIFY `id_pelanggaran` int(13) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `tbl_pengaduan_pelanggaran`
--
ALTER TABLE `tbl_pengaduan_pelanggaran`
  MODIFY `id_pengaduan_pelanggaran` int(13) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `tbl_perlindungan`
--
ALTER TABLE `tbl_perlindungan`
  MODIFY `id_perlindungan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `tbl_rumah`
--
ALTER TABLE `tbl_rumah`
  MODIFY `id_rumah` int(13) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
