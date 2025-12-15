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
        $id_perlindungan = getParam('id_perlindungan');
        $nama_perlindungan = getParam('nama_perlindungan');
        $isi_perlindungan = getParam('isi_perlindungan');
        
        if ($menu == "perlindungan" AND $act == "dltperlindungan") {
            mysql_query("DELETE FROM tbl_perlindungan WHERE id_perlindungan = '$id_perlindungan'");
            header('location:../../media.php?menu=' . $menu);
        } else if ($menu == "perlindungan" AND $act == "updtperlindungan") {
            if ($id_perlindungan == 0) {
                mysql_query("INSERT INTO tbl_perlindungan(nama_perlindungan, isi_perlindungan)
                		 VALUES('$nama_perlindungan', '$isi_perlindungan')
                	    ");
            } else {
                mysql_query("UPDATE tbl_perlindungan
    					 SET nama_perlindungan = '$nama_perlindungan', isi_perlindungan='$isi_perlindungan' WHERE id_perlindungan = '$id_perlindungan'
                        ");
            }
            header('location:../../media.php?menu=' . $menu);
        }
    }
}
?>
