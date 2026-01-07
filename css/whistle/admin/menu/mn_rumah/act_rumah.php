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
        $id_rumah = getParam('id_rumah');
        $nama_rumah = getParam('nama_rumah');
        $isi_rumah= getParam('isi_rumah');
        if ($menu == "rumah" AND $act == "updtrumah") {
            mysql_query("UPDATE tbl_rumah
    					 SET nama_rumah = '$nama_rumah', isi_rumah = '$isi_rumah' WHERE id_rumah = '$id_rumah'
                        ");
            header('location:../../media.php?menu=' . $menu);
        }
    }
}
?>
