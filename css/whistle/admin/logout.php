<?php

include_once("lib.php");
session_start();
$LastOnline = gmdate("Y-m-d H:i:s", time() + 60 * 60 * 7);
$LastOnlineIP = $_SERVER["REMOTE_ADDR"];
$query = mysql_query("UPDATE tbl_user 
                                    SET LastOnline = '$LastOnline', LastOnlineIP = '$LastOnlineIP' 
				    WHERE id = '" . $_SESSION['id'] . "'
			            ") or die(mysql_error());
session_destroy();
header("location:index.php");
?>