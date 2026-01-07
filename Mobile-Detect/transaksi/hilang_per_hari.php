<?php
session_start();
if(!isset($_SESSION['username'])){header("location:./index.php");
}
if($_SESSION['kd_wilayah'] == '001')
{
$wilayah = 'KOTA JAMBI';
$lok = '01';	
$host="192.168.1.2";
$user="samsat";
$password="samsat";
$port="5432";
$dbname = "pgsamsatdb";
date_default_timezone_set("Asia/Bangkok");
 
$link= pg_connect("host=".$host." port=".$port." dbname=".$dbname." user=".$user." password=".$password) or die("Koneksi gagal");
}
if($_SESSION['kd_wilayah'] == '002')
{
$wilayah = 'BATANGHARI';
$lok = '02';	
$host="192.168.2.2";
$user="samsat";
$password="samsat";
$port="5432";
$dbname = "pgsamsatdb";
date_default_timezone_set("Asia/Bangkok");
 
$link= pg_connect("host=".$host." port=".$port." dbname=".$dbname." user=".$user." password=".$password) or die("Koneksi gagal");
}
if($_SESSION['kd_wilayah'] == '003')
{
$wilayah = 'TANJAB BARAT';
$lok = '03';	
$host="192.168.3.2";
$user="samsat";
$password="samsat";
$port="5432";
$dbname = "pgsamsatdb";
date_default_timezone_set("Asia/Bangkok");
 
$link= pg_connect("host=".$host." port=".$port." dbname=".$dbname." user=".$user." password=".$password) or die("Koneksi gagal");
}
if($_SESSION['kd_wilayah'] == '004')
{
$wilayah = 'MERANGIN';
$lok = '04';	
$host="192.168.4.2";
$user="samsat";
$password="samsat";
$port="5432";
$dbname = "pgsamsatdb";
date_default_timezone_set("Asia/Bangkok");
 
$link= pg_connect("host=".$host." port=".$port." dbname=".$dbname." user=".$user." password=".$password) or die("Koneksi gagal");
}
if($_SESSION['kd_wilayah'] == '005')
{
$wilayah = 'BUNGO';
$lok = '05';
$host="192.168.5.2";
$user="samsat";
$password="samsat";
$port="5432";
$dbname = "pgsamsatdb";
date_default_timezone_set("Asia/Bangkok");
 
$link= pg_connect("host=".$host." port=".$port." dbname=".$dbname." user=".$user." password=".$password) or die("Koneksi gagal");	
}
if($_SESSION['kd_wilayah'] == '006')
{
$wilayah = 'KERINCI';
$lok = '06';
$host="192.168.14.2";
$user="samsat";
$password="samsat";
$port="5432";
$dbname = "pgsamsatdb";
date_default_timezone_set("Asia/Bangkok");
 
$link= pg_connect("host=".$host." port=".$port." dbname=".$dbname." user=".$user." password=".$password) or die("Koneksi gagal");	
}
if($_SESSION['kd_wilayah'] == '007')
{
$wilayah = 'TANJAB TIMUR';
$lok = '07';	
$host="192.168.18.2";
$user="samsat";
$password="samsat";
$port="5432";
$dbname = "pgsamsatdb";
date_default_timezone_set("Asia/Bangkok");
 
$link= pg_connect("host=".$host." port=".$port." dbname=".$dbname." user=".$user." password=".$password) or die("Koneksi gagal");
}
if($_SESSION['kd_wilayah'] == '008')
{
$wilayah = 'MUARO JAMBI';
$lok = '08';	
$host="192.168.16.2";
$user="samsat";
$password="samsat";
$port="5432";
$dbname = "pgsamsatdb";
date_default_timezone_set("Asia/Bangkok");
 
$link= pg_connect("host=".$host." port=".$port." dbname=".$dbname." user=".$user." password=".$password) or die("Koneksi gagal");
}
if($_SESSION['kd_wilayah'] == '009')
{
$wilayah = 'SAROLANGUN';
$lok = '09';	
$host="192.168.17.2";
$user="samsat";
$password="samsat";
$port="5432";
$dbname = "pgsamsatdb";
date_default_timezone_set("Asia/Bangkok");
 
$link= pg_connect("host=".$host." port=".$port." dbname=".$dbname." user=".$user." password=".$password) or die("Koneksi gagal");
}
if($_SESSION['kd_wilayah'] == '010')
{
$wilayah = 'TEBO';
$lok = '10';	
$host="192.168.10.2";
$user="samsat";
$password="samsat";
$port="5432";
$dbname = "pgsamsatdb";
date_default_timezone_set("Asia/Bangkok");
 
$link= pg_connect("host=".$host." port=".$port." dbname=".$dbname." user=".$user." password=".$password) or die("Koneksi gagal");
}
if($_SESSION['kd_wilayah'] == '011')
{
$wilayah = 'SUNGAI PENUH';
$lok = '11';	
$host="192.168.14.2";
$user="samsat";
$password="samsat";
$port="5432";
$dbname = "pgsamsatdb";
date_default_timezone_set("Asia/Bangkok");
 
$link= pg_connect("host=".$host." port=".$port." dbname=".$dbname." user=".$user." password=".$password) or die("Koneksi gagal");
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
		<div class="table-responsive"><?php error_reporting(0);
				
				$na = $_POST['na'];
				$nb = $_POST['nb'];
				$nc = $_POST['nc'];
			
				$no_polisi = "$na $nb $nc";
			
				$export		= "export_hilang_hari.php"; 

		 ?>
<a href="<?php echo $export	?>" target="__blank"><button type="button" class="btn btn-info">Cetak Excel</button></a>
		<form method="post" action="hapus_data.php" >
			<table cellpadding="0" cellspacing="0" border="1" class="table table-condensed" id="example">
			<thead>
			<tr>                        
				<th>No</th>    
				 
				<th>No Transaksi</th>   
				<th>No Polisi</th>
				<th>Tgl Daftar</th>
				<th>Tgl Transaksi</th>
				<th>No Transaksi</th>
				<th>Jumlah Bayar</th>
				<th>User Id</th>
				<th>Cap Transaksi</th>
			
				
			</tr>
			</thead>
			<tbody>
		<?php
			
			$tglskrng = date('Y-m-d');

			$query=pg_query("SELECT * FROM t_tera
		WHERE tg_tera = '$tglskrng'
		  
		  AND no_polisi NOT IN ( SELECT no_polisi FROM t_trnkb
					    WHERE tg_bayar = '$tglskrng' )")or die(mysql_error());
			


			while($row=pg_fetch_array($query)){
				$no++;
			$no_polisi  			=$row['no_polisi'];
			$no_trn					=$row['no_trn'];
			$tg_daftar				=$row['tg_daftar'];
			$kd_notice				=$row['kd_notice'];
			$user_id				=$row['user_id'];
			$kd_kasir				=$row['kd_kasir'];
			$jml_byr				=$row['jml_byr'];
			$tg_tera				=$row['tg_tera'];
			$no_tera				=$row['no_tera'];
			$cap_tera				=$row['cap_tera'];
			$flag_tera				=$row['flag_tera'];
			
			
			
			$jml = number_format($jml_byr, 0, ",", ".");
			
			
		?>
                              
			<tr>
			<td><?php echo $no ?></td>
			
			<td><?php echo $no_trn  ?></td>
			<td><?php echo $no_polisi ?></td>
			<td><?php echo $tg_daftar ?></td>
			<td><?php echo $tg_tera ?></td>
			<td><?php echo $no_tera?></td>
			<td>Rp.<?php echo $jml ?></td>
			<td><?php echo $user_id ?></td>
			<td><?php echo $cap_tera ?></td>
			
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
