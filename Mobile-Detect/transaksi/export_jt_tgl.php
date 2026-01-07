<?php session_start();
if(!isset($_SESSION['username'])){header("location:./index.php");
}
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=jatuh_tempo_tgl.xls");

			
			
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
				<th>No Transaksi</th>   
				<th>Tgl Daftar</th>
				<th>No Polisi</th>
				
				<th>Jam Proses</th>
				<th>Term Id</th>
				<th>User Id</th>
				<th>No Notice</th>
				<th>Tgl Tetap</th>
				<th>Kode Lokasi Bayar Yang Lalu</th>
				<th>Tgl Akhir Pkb Yang Lalu</th>
				<th>Tgl Akhir Pkb</th>
			
			</tr>
			</thead>
			<tbody>
		<?php error_reporting(0);
			include 'koneksi.php';
			$tgl_awal = $_GET['tgl_awal'];
			$tgl_akhir = $_GET['tgl_akhir'];
			$tglskrng = date('Y-m-d');

			$query=pg_query("select * from t_reg_tg_akhir a, t_nm_lokasi b where a.kd_lokasi_byr = b.kd_lokasi AND a.tg_daftar between '$tgl_awal' and '$tgl_akhir'
			and LEFT(a.kd_lokasi,2) = '$lok' order by a.jam_proses DESC")or die(mysql_error());
			


			while($row=pg_fetch_array($query)){
				$no++;
			$no_polisi  			=$row['no_polisi'];
			$no_trn					=$row['no_trn'];
			$tg_daftar				=$row['tg_daftar'];
			$kd_lokasi				=$row['kd_lokasi'];
			$nm_lokasi				=$row['nm_lokasi'];
			$jam_proses				=$row['jam_proses'];
			$term_id				=$row['term_id'];
			$user_id				=$row['user_id'];
			$no_notice				=$row['no_notice'];
			$tg_tetap				=$row['tg_tetap'];
			$kd_lokasi_byr			=$row['kd_lokasi_byr'];
			$tg_akhir_pkb_yl		=$row['tg_akhir_pkb_yl'];
			$tg_akhir_pkb			=$row['tg_akhir_pkb'];
			
			
		?>
                              
			<tr>
			<td><?php echo $no ?></td>
			<td><?php echo $no_trn  ?></td>
			<td><?php echo $tg_daftar  ?></td>
			<td><?php echo $no_polisi ?></td>
			
			<td><?php echo $jam_proses ?></td>
			<td><?php echo $term_id ?></td>
			<td><?php echo $user_id?></td>
			<td><?php echo $no_notice ?></td>
			<td><?php echo $tg_tetap ?></td>
			<td><?php echo $nm_lokasi ?></td>
			<td><?php echo $tg_akhir_pkb_yl?></td>
			<td><?php echo $tg_akhir_pkb?></td>
			</tr>
                        				 
		<?php } ?>
        </form>
	
</body>
</html>
