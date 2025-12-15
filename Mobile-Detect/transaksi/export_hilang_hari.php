<?php session_start();
if(!isset($_SESSION['username'])){header("location:./index.php");
}
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=batal_perhari.xls");

			
			
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
	
</body>
</html>
