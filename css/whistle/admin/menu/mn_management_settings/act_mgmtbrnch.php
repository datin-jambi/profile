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
        $id = getParam('id');
        $kodecabang = getParam('kodecabang');
        $namacabang = getParam('namacabang');
        $kodecabanginduk = getParam('kodecabanginduk');
        if ($menu == "mgmtbrnch" AND $act == "dltbrnch") {
            mysql_query("DELETE FROM tbl_cabang WHERE id = '$id'");
            header('location:../../media.php?menu=' . $menu);
        } else if ($menu == "mgmtbrnch" AND $act == "updtbrnch") {
            if ($id == 0) {
                mysql_query("INSERT INTO tbl_cabang(kode_cabang, nama_cabang, kode_cabang_induk)
                		 VALUES('$kodecabang', '$namacabang', '$kodecabanginduk')
                	    ");
            } else {
                mysql_query("UPDATE tbl_cabang
    					 SET kode_cabang = '$kodecabang', nama_cabang = '$namacabang', kode_cabang_induk = '$kodecabanginduk'
    					 WHERE id = '$id'
                        ");
            }
            header('location:../../media.php?menu=' . $menu);
        }
    }
}
?>
