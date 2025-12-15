<?php

include_once("../../lib.php");
session_start();
$curpwd = sha1($_POST['currentpassword']);
$newpwd = sha1($_POST['newpassword']);
$confpwd = sha1($_POST['confirmpassword']);
$oldpwd = $_SESSION['passwd'];

if ($curpwd != $oldpwd) {
    echo'<script>alert("Your password was incorrect.");
                javascript:history.go(-1);
            </script>
           ';
    exit;
}
if (strlen($_POST['newpassword']) < 6) {
    echo'<script>alert("The password must be greater than or equal to 6 characters.");
                javascript:history.go(-1);
            </script>
           ';
    exit;
}
if ($newpwd != $confpwd) {
    echo'<script>alert("Passwords do not match.");
                javascript:history.go(-1);
            </script>
           ';
    exit;
}
$qry = "SELECT * FROM tbl_user WHERE uname = '" . $_SESSION['usrname'] . "' and pwd = '$curpwd'";
$result = mysql_query($qry) or mysql_error();
$num = mysql_num_rows($result);
if ($num == 1) {
    $qry2 = mysql_fetch_array($result);
    $result2 = "UPDATE tbl_user SET pwd = '$newpwd' WHERE uname = '" . $_SESSION['usrname'] . "'";
    $upd = mysql_query($result2);
    $LastOnline = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
    $LastOnlineIP = $_SERVER["REMOTE_ADDR"];
    $query = mysql_query("UPDATE tbl_user 
                                     SET LastOnline = '$LastOnline', LastOnlineIP = '$LastOnlineIP' 
                                     WHERE id = '" . $_SESSION['id'] . "'
                                    ") or die(mysql_error());
    if ($upd && $query) {
        session_destroy();
        echo'<script>alert("Password successfully changed."); window.location = "../../index.php"</script>';
    } else {
        echo'<script>alert("Wrong password."); window.location = "../../media.php?menu=chgpasswd"</script>';
    }
}
?>