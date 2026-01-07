<?php

include_once("lib.php");
error_reporting(0);

$qry = mysql_query("SELECT master_menu, tabel_asal, icon, link FROM tbl_menu WHERE lvl like '%$_SESSION[level]%' AND stts = 'Active' GROUP BY master_menu ORDER BY urutan ASC");
while ($mn = mysql_fetch_array($qry)) {
    if ($mn['master_menu'] == "Home" || $mn['master_menu'] == "Report" || $mn['master_menu'] == "Tindak Lanjut") {
        $TabelAsal = $mn['tabel_asal'];
        $qry2 = mysql_query("SELECT DISTINCT COUNT(*) from $TabelAsal");
        $result = mysql_fetch_array($qry2);
        echo'<li class="">
		<a href="' . $mn['link'] . '">
                    <i  class="icon fa fa-' . $mn['icon'] . '"></i><span class="title">' . $mn['master_menu'] . ' <span class="badge">' . $result[0] . '</span></span>
		</a>
            </li>';
    }if ($mn['master_menu'] == "Pengaduan"){
        $TabelAsal = $mn['tabel_asal'];
        $qry2 = mysql_query("SELECT DISTINCT COUNT(*) from tbl_pengaduan_pelanggaran WHERE tempat_terjadi = '$_SESSION[uptd]'");
        $result = mysql_fetch_array($qry2);
        echo'<li class="">
        <a href="' . $mn['link'] . '">
                    <i  class="icon fa fa-' . $mn['icon'] . '"></i><span class="title">' . $mn['master_menu'] . ' <span class="badge">' . $result[0] . '</span></span>
        </a>
            </li>';
    } else {
        if ($mn['master_menu'] == "Management Data" || $mn['master_menu'] == "Kelola Website" ) {
            echo'<li class="submenu">
                    <a data-toggle="collapse" href="#management-data">
		        <i class="icon icon-' . $mn['icon'] . '"></i><span class="title">' . $mn['master_menu'] . ' </span><span class="icon icon-chevron-down" style="margin: 0px 0px 0px 45px;"></span>
                    </a>
		<ul>';
            $qry3 = mysql_query("SELECT * FROM tbl_menu WHERE master_menu='$mn[master_menu]' AND lvl like '%$_SESSION[level]%' AND stts = 'Active' ORDER BY urutan ASC");
            while ($mmn = mysql_fetch_array($qry3)) {
                $TabelAsal = $mmn['tabel_asal'];
                $qry4 = mysql_query("SELECT DISTINCT COUNT(*) from $TabelAsal");
                $result = mysql_fetch_array($qry4);
                echo'        		
                    <li>
                        <a href="' . $mmn['link'] . '">
                            <span class="icon icon-' . $mmn['icon'] . '"></span>   <span class="title">' . $mmn['nama_menu'] . ' <span class="badge">' . $result[0] . '</span></span>
			</a>
                    </li>';
            }
            echo'</ul>
		</li>';
        }
    }
}
?>