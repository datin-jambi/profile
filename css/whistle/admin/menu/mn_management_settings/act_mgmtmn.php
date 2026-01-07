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
        $menuname = getParam('menuname');
        $master_menu = getParam('master_menu');
        $tabelasal = getParam('tabelasal');
        $icon = getParam('icon');
        $link = getParam('link');
        $level = getParam('lvl');
        $status = getParam('stts');
        $urutan = getParam('urutan');
        if ($menu == "mgmtmn" AND $act == "dltmn") {
            mysql_query("DELETE FROM tbl_menu WHERE id = '$id'");
            header('location:../../media.php?menu=' . $menu);
        } else if ($menu == "mgmtmn" AND $act == "updtmn") {
            if ($id == 0) {
                mysql_query("INSERT INTO tbl_menu(nama_menu, master_menu, tabel_asal, icon, link, lvl, stts, urutan)
                		 VALUES('$menuname', '$master_menu','$tabelasal', '$icon', '$link', '$level', '$status', '$urutan')
                	    ");
            } else {
                mysql_query("UPDATE tbl_menu
    					 SET nama_menu = '$menuname', master_menu='$master_menu', icon='$icon', tabel_asal = '$tabelasal', link = '$link', lvl = '$level', stts = '$status', urutan = '$urutan'
    					 WHERE id = '$id'
                        ");
            }
            header('location:../../media.php?menu=' . $menu);
        }
    }
}
?>
