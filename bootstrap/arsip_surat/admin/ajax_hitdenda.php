<?php
	session_start();
	if(!isset($_SESSION['username'])){
		header('location: login.php'); // Mengarahkan ke Home Page
	}
	
	include"../lib/koneksi.php";
	include"../lib/all_function.php";
	
	if (!empty($_GET['id'])) {
		$sql = mysql_query("SELECT a.*, b.judul, c.nama FROM peminjaman a 
							LEFT JOIN buku b ON a.kodeBuku = b.kodeBuku 
							LEFT JOIN anggota c ON a.noAnggota = c.noAnggota 
							WHERE a.noPeminjaman = '$_GET[id]' AND stsPinjam = 1");
		$temukan = mysql_num_rows($sql);

		if ($temukan > 0) {
			$a = mysql_fetch_assoc($sql);

			$dt = date('Y-m-d');
			$lama = selisih_tanggal($dt, $a['tglPinjam']);
			$shr = selisih_tanggal($a['tglKembali'], $a['tglPinjam']);
			
			$terlambat = 0;
			$denda = 0;
			if ($lama <= $shr) {
				$ket = 'Tidak Terlambat';
			} else {
				$terlambat = $lama - $shr;
				$denda = $terlambat * 100;
				$ket = 'Terlambat ' . $terlambat . ' Hari';
			}
			
			
			echo $denda + 5000;
			
		}
				
	}

?>