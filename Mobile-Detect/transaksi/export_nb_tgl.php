<?php 
session_start();
if(!isset($_SESSION['username'])){header("location:./index.php");
}
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=batal_pertanggal.xls");

if(!isset($_SESSION['username'])){header("location:./index.php");
}
if($_SESSION['kd_wilayah'] == '001')
{
$wilayah = 'KOTA JAMBI';
$lok = '01';	

}
if($_SESSION['kd_wilayah'] == '002')
{
$wilayah = 'BATANGHARI';
$lok = '02';	

}
if($_SESSION['kd_wilayah'] == '003')
{
$wilayah = 'TANJAB BARAT';
$lok = '03';	

}
if($_SESSION['kd_wilayah'] == '004')
{
$wilayah = 'MERANGIN';
$lok = '04';	

}
if($_SESSION['kd_wilayah'] == '005')
{
$wilayah = 'BUNGO';
$lok = '05';

}
if($_SESSION['kd_wilayah'] == '006')
{
$wilayah = 'KERINCI';
$lok = '06';
	
}
if($_SESSION['kd_wilayah'] == '007')
{
$wilayah = 'TANJAB TIMUR';
$lok = '07';	

}
if($_SESSION['kd_wilayah'] == '008')
{
$wilayah = 'MUARO JAMBI';
$lok = '08';	

}
if($_SESSION['kd_wilayah'] == '009')
{
$wilayah = 'SAROLANGUN';
$lok = '09';	

}
if($_SESSION['kd_wilayah'] == '010')
{
$wilayah = 'TEBO';
$lok = '10';	

}
if($_SESSION['kd_wilayah'] == '011')
{
$wilayah = 'SUNGAI PENUH';
$lok = '11';	

}
if($_SESSION['kd_wilayah'] == '')
{
$wilayah = 'BAKEUDA';	
}
?>
<html>
<h3 align="center">Transaksi Samsat <?php echo $wilayah; ?></h3>

<body>


<form method="post" action="hapus_data.php" >
			<table cellpadding="0" cellspacing="0" border="1" class="table table-condensed" id="example">
			<thead>
			<tr>                        
				<th>No</th>    
				<th>Kode Lokasi</th> 
				<th>No Transaksi</th>   
				<th>No Polisi</th>
				<th>Tgl Daftar</th>
				<th>Tgl Cetak</th>
				<th>No Notice</th>
				<th>Jumlah Tetap</th>
				<th>User Id</th>
				<th>Status</th>
				<th>Catatan</th>
				
			</tr>
			</thead>
			<tbody>
		<?php error_reporting(0);
			include 'koneksi.php';
			$tgl_awal = $_GET['tgl_awal'];
			$tgl_akhir = $_GET ['tgl_akhir'];
			$tglskrng = date('Y-m-d');
			if($lok == '06' or $lok == '11'){

			$query=pg_query("select * from t_notice a, t_nm_lokasi b where a.kd_lokasi = b.kd_lokasi AND a.flag_notice = 'R'
			and LEFT(a.kd_lokasi,2) = '$lok' and a.tg_daftar between '$tgl_awal' and '$tgl_akhir' order by a.no_polisi ASC")or die(mysql_error());
			
			}
			else{
				$query=pg_query("select * from t_notice a, t_nm_lokasi b where a.kd_lokasi = b.kd_lokasi AND a.flag_notice = 'R'
			and LEFT(a.kd_lokasi,2) = '$lok' and a.tg_daftar between '$tgl_awal' and '$tgl_akhir' and catatan !='' order by a.no_polisi ASC")or die(mysql_error());
			}

			while($row=pg_fetch_array($query)){
				$no++;
			$no_polisi  			=$row['no_polisi'];
			$no_trn					=$row['no_trn'];
			$tg_daftar				=$row['tg_daftar'];
			$kd_lokasi				=$row['kd_lokasi'];
			$nm_lokasi				=$row['nm_lokasi'];
			$tg_cetak				=$row['tg_cetak'];
			$no_notice				=$row['no_notice'];
			$jml_tetap				=$row['jml_tetap'];
			$user_id				=$row['user_id'];
			
			$flag_notice			=$row['flag_notice'];
			if($flag_notice == 'R')
			{
				$flag_notice = 'Dibatalkan';
			}
			$catatan				=$row['catatan'];
			$jml = number_format($jml_tetap, 0, ",", ".");
			
			
		?>
                              
			<tr>
			<td><?php echo $no ?></td>
			<td><?php echo $nm_lokasi  ?></td>
			<td><?php echo $no_trn  ?></td>
			<td><?php echo $no_polisi ?></td>
			<td><?php echo $tg_daftar ?></td>
			<td><?php echo $tg_cetak ?></td>
			<td><?php echo $no_notice?></td>
			<td>Rp.<?php echo $jml ?></td>
			<td><?php echo $user_id ?></td>
			<td><?php echo $flag_notice ?></td>
			<td><?php echo $catatan?></td>
			
			</tr>
                        				 
		<?php } ?>
        </form>
	
</body>
</html>
