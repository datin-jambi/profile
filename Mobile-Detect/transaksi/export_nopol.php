<?php session_start();
if(!isset($_SESSION['username'])){header("location:./index.php");
}
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=transaksi_nopol.xls");

			$no_polisi 	= $_GET['no_polisi'];
			
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
				<th>Kode Lokasi</th>
				<th>Kode Proses</th>
				<th>Kode Status</th>
				<th>Jam Proses</th>		
				<th>Term Id</th>
				<th>User Id</th>	
			
			</tr>
			</thead>
			<tbody>
		<?php error_reporting (0);
			include 'koneksi.php';

			$query=pg_query("select * from t_log_trn a, t_nm_lokasi b where a.no_polisi = '$no_polisi' and a.kd_lokasi = b.kd_lokasi order by jam_proses DESC")or die(mysql_error());
			


			while($row=pg_fetch_array($query)){
				$no++;
			$no_polisi  			=$row['no_polisi'];
			$no_trn					=$row['no_trn'];
			$tg_daftar				=$row['tg_daftar'];
			$kd_lokasi				=$row['kd_lokasi'];
			$nm_lokasi				=$row['nm_lokasi'];
			$kd_proses				=$row['kd_proses'];
			$kd_status				=$row['kd_status'];
			$jam_proses				=$row['jam_proses'];
			$term_id				=$row['term_id'];
			$user_id				=$row['user_id'];
			
			
		?>
                              
			<tr>
			<td><?php echo $no ?></td>
			<td><?php echo $no_trn  ?></td>
			<td><?php echo $tg_daftar  ?></td>
			<td><?php echo $no_polisi ?></td>
			<td><?php echo $nm_lokasi	 ?></td>
			<td><?php echo $kd_proses ?></td>
			<td><?php echo $kd_status ?></td>
			<td><?php echo $jam_proses?></td>
			<td><?php echo $term_id ?></td>
			<td><?php echo $user_id ?></td>
			
			</tr>
                        				 
		<?php } ?>
        </form>
	
</body>
</html>
