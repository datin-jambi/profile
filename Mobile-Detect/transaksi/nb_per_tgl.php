<?php
session_start();
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

?><!DOCTYPE html>
<html>
<head>
	<title>KUPT SAMSAT</title>
    
    <link rel="stylesheet" type="text/css" href="../file/style.css">
    <link rel="stylesheet" href="css/stylemenuutama.css">
	<link href="css/bootstrap.min.css" rel="stylesheet">    
    <link rel="stylesheet" href="bootstrap.min.css">
	<link rel="stylesheet" href="dataTables.bootstrap.css">
	<link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="screen">
	<link rel="stylesheet" type="text/css" href="css/DT_bootstrap.css">
	<link rel="stylesheet" type="text/css" href="css/font-awesome.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="font-awesome/css/font-awesome.min.css"/>

	<script src="js/jquery.js" type="text/javascript"></script>
	<script src="js/bootstrap.js" type="text/javascript"></script>

	<script src="datatables/jquery.dataTables.min.js"></script>
	<script src="datatables/dataTables.bootstrap.min.js"></script>

	<script type="text/javascript" charset="utf-8" language="javascript" src="js/jquery.dataTables.js"></script>
	<script type="text/javascript" charset="utf-8" language="javascript" src="js/DT_bootstrap.js"></script>
</head>
	<?php include'header.php'; ?>
<body>



			 
        <div class="col-md-18">
		<div class="container-fluid" style="margin-top:0px;">
		<div class = "row">
		<div class="panel panel-default">
		<div class="panel-body">
		<div class="table-responsive"><?php error_reporting (0);
				include 'koneksi.php';
				$tgl_awal = $_POST['tgl_awal'];
				$tgl_akhir = $_POST['tgl_akhir'];
			
				$export		= "export_nb_tgl.php?tgl_awal=$tgl_awal&tgl_akhir=$tgl_akhir"; 

		 ?>
<a href="<?php echo $export	?>" target="__blank"><button type="button" class="btn btn-info">Cetak Excel</button></a>
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
				<th>Aksi</th>
			</tr>
			</thead>
			<tbody>
		<?php
			
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
			$link_edit    = "form_ket.php?nopol=$no_polisi&flag=$flag_notice";
			
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
			<td width = "150px"><a href="<?php echo $link_edit?>" target="__blank">Edit Keterangan</a> </td>
			</tr>
                        				 
		<?php } ?>
        </form>
	
	<script>
		
	</script>
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="margin-left:400px; height:200px;">
              
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title" id="myModalLabel">Input Nopol</h4>
                  </div>
         
                  <div class="modal-body">
                   <form method="post" action="lihat_data.php">
                                    <label class="col-sm-2 col-sm-2 control-label">Nopol</label>
                                    <input type="text" value="BH" name="nx" disabled style="width:40px;"><input type="hidden" value="BH" name="na" style="width:60px;"> - <input type="text" placeholder="angka" name="nb" style="width:120px;"> - <input type="text" placeholder="seri" name="nc" style="width:40px;"><p><br />
                                 <div class="modal-footer">

                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" >Cari</button>
                  </div>
                </form>
                              </div>
                </div>
              </div>
          </div>
</body>
</html>
