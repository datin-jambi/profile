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
        $id_faq = getParam('id_faq');
        $nama_faq = getParam('nama_faq');
        $isi_faq = getParam('isi_faq');
        
        if ($menu == "faq" AND $act == "dltfaq") {
            mysql_query("DELETE FROM tbl_faq WHERE id_faq = '$id_faq'");
            header('location:../../media.php?menu=' . $menu);
        } else if ($menu == "faq" AND $act == "updtfaq") {
            if ($id_faq == 0) {
                mysql_query("INSERT INTO tbl_faq(nama_faq, isi_faq)
                		 VALUES('$nama_faq', '$isi_faq')
                	    ");
            } else {
                mysql_query("UPDATE tbl_faq
    					 SET nama_faq = '$nama_faq', isi_faq='$isi_faq' WHERE id_faq = '$id_faq'
                        ");
            }
            header('location:../../media.php?menu=' . $menu);
        }
    }
}
?>
