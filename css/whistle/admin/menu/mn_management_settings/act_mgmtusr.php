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
        $usrname = getParam('username');
        $passwd = sha1(getParam('password'));
        $fname = getParam('fullname');
        $position = getParam('position');
        $level = getParam('level');
        $status = getParam('stts');
        $uptd = getParam('uptd');

        
        if ($menu == "mgmtusr" AND $act == "dltusr") {
            mysql_query("DELETE FROM tbl_user WHERE id = '$id'");
            header('location:../../media.php?menu=' . $menu);
        } else if ($menu == "mgmtusr" AND $act == "updtusr") {
            if ($id == 0) {
                mysql_query("INSERT INTO tbl_user(uname, pwd, fname, position, lvl, stts, uptd)
                		 VALUES('$usrname', '$passwd', '$fname', '$position', '$level', '$status','$uptd')
                	    ");
            } else {
                if ($passwd == 0) {
                    mysql_query("UPDATE tbl_user
    					 	 SET uname = '$usrname', fname = '$fname', position = '$position', lvl = '$level', stts = '$status', uptd = '$uptd'  
    					 	 WHERE id = '$id'
                        	");
                } else {
                    mysql_query("UPDATE tbl_user 
    					 	 SET uname = '$usrname', pwd = '$passwd', fname = '$fname', position = '$position', lvl = '$level', stts = '$status', uptd = '$uptd' 
    					 	 WHERE id = '$id'
                        	");
                }
            }
            header('location:../../media.php?menu=' . $menu);
        }
    }
}
?>
