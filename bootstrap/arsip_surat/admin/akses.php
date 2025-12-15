<?php
session_start();
 
if(!isset($_SESSION['`1'])){
	echo '<script language="javascript">alert("Anda harus Login!"); document.location="../login.php";</script>';
}
?>