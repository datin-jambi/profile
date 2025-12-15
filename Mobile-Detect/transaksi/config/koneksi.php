<?php
	$host = 'localhost';
	$user = 'root';
	$pass = '';
	$db = 'db_arsip_samsat';

	$koneksi = mysql_connect($host, $user, $pass) or die("Tidak terkoneksi ke Server");

	if ($koneksi) {
		mysql_select_db($db) or die("Tidak terkoneksi ke datababse");
	}
	//fungsi untuk mengkonversi size file
function formatBytes($bytes, $precision = 2) { 
    $units = array('B', 'KB', 'MB', 'GB', 'TB'); 

    $bytes = max($bytes, 0); 
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
    $pow = min($pow, count($units) - 1); 

    $bytes /= pow(1024, $pow); 

    return round($bytes, $precision) . ' ' . $units[$pow]; 
} 


?>