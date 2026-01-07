<?php
session_start();

$host="192.168.0.3";
$user="samsat";
$password="samsat";
$port="5432";
$dbname = "pgsamsatoldb";
date_default_timezone_set("Asia/Bangkok");
 
$link= pg_connect("host=".$host." port=".$port." dbname=".$dbname." user=".$user." password=".$password) or die("Koneksi gagal");



	$nama  = $_POST['username'];
	
	$password  = $_POST['password'];
	
	
		if(empty($nama) || empty($password)){
			echo '<script language="javascript">alert("Data masih kosong "); document.location="index.php";</script>';
		}		
	$query_login = pg_query("select * from t_admin where 
				   username='$nama' and password='$password'");
	if(pg_num_rows($query_login) == 0){
		echo '<script language="javascript">alert("Login Gagal "); document.location="index.php";</script>';
		
		exit;
	}else{
		//login berhasil
		$data = pg_fetch_assoc($query_login);
		$nama 				= $data['username'];
		$_SESSION['login'] 		= 1;
		$_SESSION['password'] 	= $data['password'];
		$_SESSION['kd_wilayah'] 	= $data['kd_wilayah'];
		$_SESSION['username'] 	= $nama;
		header("location:menu.php");
		exit;		
	}
?>