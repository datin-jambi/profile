<?php

include_once("lib.php");
error_reporting(0);

if ($_GET["menu"] == "home") {
    echo' <div class="container-fluid">
        <div class="quick-actions_homepage">
          <ul class="quick-actions">
            
            <li class="bg_ly"> 
                <a href="media.php?menu=pelanggaran"> <i class="icon-inbox"></i>';
                $query = mysql_query("select count(id_pelanggaran) as jumlah from tbl_pelanggaran ");
                $r=  mysql_fetch_array($query);
                    echo'<span class="label label-success">'.$r['jumlah'].' </span> Management Pelanggaran 
                </a> 
            </li>
            <li class="bg_lo"> 
                <a href="media.php?menu=pengaduan"> <i class="icon-th"></i>';
                    $query = mysql_query("select count(id_pengaduan_pelanggaran) as jumlah from tbl_pengaduan_pelanggaran");
                    $r=  mysql_fetch_array($query);
                   echo'<span class="label label-success">'.$r['jumlah'].' </span> Pengaduan Pelanggaran
                </a> 
            </li>
            <li class="bg_ls"> 
                <a href="media.php?menu=rumah"> <i class="icon-fullscreen"></i>';
                $query = mysql_query("select count(id_rumah) as jumlah from tbl_rumah");
                $r=  mysql_fetch_array($query);
                echo'<span class="label label-success">'.$r['jumlah'].' </span> Kelola Home
                </a> 
            </li>
            <li class="bg_lo"> <a href="media.php?menu=beranda"> <i class="icon-th-list"></i>';
                $query = mysql_query("select count(id_beranda) as jumlah from tbl_beranda");
                $r=  mysql_fetch_array($query);
                echo'<span class="label label-success">'.$r['jumlah'].' </span>Kelola Beranda
                </a> 
            </li>
            <li class="bg_ls"> <a href="media.php?menu=perlindungan"> <i class="icon-tint"></i>'; 
            $query = mysql_query("select count(id_perlindungan) as jumlah from tbl_perlindungan");
                $r=  mysql_fetch_array($query);
                echo'<span class="label label-success">'.$r['jumlah'].' </span>Kelola Perlindungan
                </a> 
            </li>
            <li class="bg_lb"> <a href="media.php?menu=faq"> <i class="icon-pencil"></i>';
            $query = mysql_query("select count(id_faq) as jumlah from tbl_faq");
                $r=  mysql_fetch_array($query);
                echo'<span class="label label-success">'.$r['jumlah'].' </span>Kelola Faq
                </a> 
        </li>
      </ul>
    </div>
  </div>
	   	   ';
} else if ($_GET["menu"] == "mgmtmn") {
    include "menu/mn_management_settings/mgmtmn.php";
} else if ($_GET["menu"] == "mgmtbrnch") {
    include "menu/mn_management_settings/mgmtbrnch.php";
} else if ($_GET["menu"] == "mgmtusr") {
    include "menu/mn_management_settings/mgmtusr.php";
} else if ($_GET["menu"] == "rumah") {
    include "menu/mn_rumah/rumah.php";
} else if ($_GET["menu"] == "beranda") {
    include "menu/mn_beranda/beranda.php";
} else if ($_GET["menu"] == "perlindungan") {
    include "menu/mn_perlindungan/perlindungan.php";
} else if ($_GET["menu"] == "faq") {
    include "menu/mn_faq/faq.php";
} else if ($_GET["menu"] == "pelanggaran") {
    include "menu/mn_pelanggaran/pelanggaran.php";
} else if ($_GET["menu"] == "pengaduan") {
    include "menu/mn_pengaduan/pengaduan.php";
} else if ($_GET["menu"] == "tindaklanjut") {
    include "menu/mn_tindaklanjut/tindaklanjut.php";
} else if ($_GET["menu"] == "report") {
    include "menu/mn_report/report.php";
} else if ($_GET["menu"] == "chgpasswd") {
    include "menu/mn_passwd/passwd.php";
} else {
    echo'<div class="widget-content">
		   		<div class="alert alert-error alert-block"> <a class="close" data-dismiss="alert" href="#">×</a>
	              	<h4 class="alert-heading">Error!</h4>
	              	Sorry, you are not yet complete menu. Please complete the menu. 
	            </div>
	        </div>
	       ';
}
?>