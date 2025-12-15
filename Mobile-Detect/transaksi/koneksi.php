<?php 

$host="192.168.0.3";
$user="samsat";
$password="samsat";
$port="5432";
$dbname = "pgsamsatoldb";
date_default_timezone_set("Asia/Bangkok");
 
$link= pg_connect("host=".$host." port=".$port." dbname=".$dbname." user=".$user." password=".$password) or die("Koneksi gagal");
?>