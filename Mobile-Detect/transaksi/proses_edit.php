<?php

	$ket	 = $_POST['ket'];
	$nopol	 = $_POST['nopol'];
	$flag	 = $_POST['flag'];
	

	
	include("koneksi.php");

	 $in = "Update t_notice SET catatan='$ket' where no_polisi='$nopol' and	flag_notice = '$flag'";
$hasil = pg_query($in);			
			if($hasil)
	{
				echo"<script>
				alert('SUCCESS: File Berhasil di Simpan!');
				window.location.href='form_nb.php';
			</script>";
	}
	
	
?>