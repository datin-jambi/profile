<?php

session_start();
include_once("lib.php");

$usrname = antiinjection($_POST["usrname"]);
$passwd = antiinjection(sha1($_POST["passwd"]));

$pw = $_POST['passwd'];

$query = mysql_query("SELECT * FROM tbl_user WHERE uname = '$usrname' AND pwd = '$passwd' AND stts = 'Active'");
$cek = mysql_num_rows($query);
if ($cek > 0) {
    include "timeout.php";
    $rs = mysql_fetch_array($query);
    $_SESSION['id'] = $rs['id'];
    $_SESSION['usrname'] = $rs['uname'];
    $_SESSION['passwd'] = $rs['pwd'];
    $_SESSION['fullname'] = $rs['fname'];
    $_SESSION['level'] = $rs['lvl'];
    $_SESSION['uptd'] = $rs['uptd'];
    $_SESSION[login] = 1;
    timer();
    header("location:media.php?menu=home");
} else {
    header("location:index.php");
}
?>