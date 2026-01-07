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
        $id_pengaduan_pelanggaran = getParam('id_pengaduan_pelanggaran');
        $nomor_referensi = getParam('nomor_referensi');
        $nama_pelapor    = getParam('nama_pelapor');
        $nomor_telepon   = getParam('nomor_telepon');
        $email           = getParam('email');
        $nama_pelaku     = getParam('nama_pelaku');
        $id_pelanggaran = getParam('id_pelanggaran');
        $uraian_pelanggaran = getParam('uraian_pelanggaran');
        $waktu_kejadian = getParam('waktu_kejadian');
        $tempat_terjadi = getParam('tempat_terjadi');
        $kronologis_permasalahan = getParam('kronologis_permasalahan');
        $unggah_file = getParam('unggah_file');
        $captcha = getParam('captcha');
        $check1 = getParam('check1');
        $check2 = getParam('check2');
        $check3 = getParam('check3');
         if ($menu == "pengaduan" AND $act == "downloadpengaduan") {
                $query = mysql_query("select * from tbl_pengaduan_pelanggaran WHERE id_pengaduan_pelanggaran = '$id_pengaduan_pelanggaran'");
                $data = mysql_fetch_array($query);
                // header yang menunjukkan nama file yang akan didownload
                header("Content-Disposition: attachment; filename=" . $data['unggah_file']);
                // proses membaca isi file yang akan didownload dari folder 'data'
                $fp = fopen("file/" . $data['unggah_file'], 'r');
                $content = fread($fp, filesize('file/' . $data['unggah_file']));
                echo $content;
                fclose($fp);
                //header('location:../../media.php?menu=' . $menu);
        }
         if ($menu == "pengaduan" AND $act == "dltpengaduan") {
                mysql_query("delete from tbl_pengaduan_pelanggaran WHERE id_pengaduan_pelanggaran = '$id_pengaduan_pelanggaran'");

                header('location:../../media.php?menu=' . $menu);
        }
         else if ($menu == "pengaduan" AND $act == "updtpengaduan") {
            if ($id_pengaduan_pelanggaran == 0) {
                mysql_query("INSERT INTO tbl_pengaduan_pelanggaran(nomor_referensi, nama_pelapor, nomor_telepon, email, nama_pelaku, id_pelanggaran, uraian_pelanggaran, waktu_kejadian, tempat_terjadi, kronologis_permasalahan, unggah_file, captcha)
                		 VALUES('$nomor_referensi','$nama_pelapor','$nomor_telepon','$email','$nama_pelaku','$id_pelanggaran','$uraian_pelanggaran','$waktu_kejadian','$tempat_terjadi','$kronologis_permasalahan','$unggah_file', '$captcha')
                	    ");
            } else {
                if($check1 == "DalamProses"){
                    $Check11='Dalam Proses';
                     mysql_query("UPDATE tbl_pengaduan_pelanggaran
    					 SET tindak_lanjut1 = '$Check11' WHERE id_pengaduan_pelanggaran = '$id_pengaduan_pelanggaran'
                        ");
             }
             else if($check2 == "Ditindaklanjuti"){
                 mysql_query("UPDATE tbl_pengaduan_pelanggaran
    					 SET tindak_lanjut2 = '$check2' WHERE id_pengaduan_pelanggaran = '$id_pengaduan_pelanggaran'
                        ");
             }else if($check3 =="TidakDiproses"){
                 $Check12='Tidak Diproses';
                mysql_query("UPDATE tbl_pengaduan_pelanggaran
    					 SET tindak_lanjut3 = '$Check12' WHERE id_pengaduan_pelanggaran = '$id_pengaduan_pelanggaran'
                        "); 
             }
            }
           header('location:../../media.php?menu=' . $menu);
        }
    }
}
?>
