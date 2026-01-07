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
        $id_pelanggaran = getParam('id_pelanggaran');
        $jenis_pelanggaran = getParam('jenis_pelanggaran');
        
        if ($menu == "pelanggaran" AND $act == "dltpelanggaran") {
            mysql_query("DELETE FROM tbl_pelanggaran WHERE id_pelanggaran = '$id_pelanggaran'");
            header('location:../../media.php?menu=' . $menu);
        } else if ($menu == "pelanggaran" AND $act == "updtpelanggaran") {
            if ($id_pelanggaran == 0) {
                mysql_query("INSERT INTO tbl_pelanggaran(jenis_pelanggaran)
                		 VALUES('$jenis_pelanggaran')
                	    ");
            } else {
                mysql_query("UPDATE tbl_pelanggaran
    					 SET jenis_pelanggaran = '$jenis_pelanggaran' WHERE id_pelanggaran = '$id_pelanggaran'
                        ");
            }
            header('location:../../media.php?menu=' . $menu);
        }
    }
}
?>
