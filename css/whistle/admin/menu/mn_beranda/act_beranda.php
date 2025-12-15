<?php

if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) {
    ob_start("ob_gzhandler");
} else {
    ob_start();
}
include "../../timeout.php";
error_reporting(0);
session_start();
if ($_SESSION[login] == 1) {
    if (!cek_login()) {
        $_SESSION[login] = 0;
    }
}
if ($_SESSION[login] == 0) {
    header('location:../../logout.php');
} else {
    if (empty($_SESSION["usrname"]) && empty($_SESSION["passwd"]) && $_SESSION['login'] == 0) {
        echo '<script>alert("You Need To Login First !"); window.location = "index.php"</script>';
    } else {
        include_once("../../lib.php");
        $menu = getParam('menu');
        $act = getParam('act');
        $id_beranda = getParam('id_beranda');
        $nama_beranda = getParam('nama_beranda');
        $isi_beranda = getParam('isi_beranda');
        
        if ($menu == "beranda" AND $act == "dltberanda") {
            mysql_query("DELETE FROM tbl_beranda WHERE id_beranda = '$id_beranda'");
            header('location:../../media.php?menu=' . $menu);
        } else if ($menu == "beranda" AND $act == "updtberanda") {
            if ($id_beranda == 0) {
                mysql_query("INSERT INTO tbl_beranda(nama_beranda, isi_beranda)
                		 VALUES('$nama_beranda', '$isi_beranda')
                	    ");
            } else {
                mysql_query("UPDATE tbl_beranda
    					 SET nama_beranda = '$nama_beranda', isi_beranda='$isi_beranda' WHERE id_beranda = '$id_beranda'
                        ");
            }
            header('location:../../media.php?menu=' . $menu);
        }
    }
}
?>
