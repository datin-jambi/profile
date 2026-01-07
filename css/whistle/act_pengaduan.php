<?php

session_start();
include_once("lib.php");



if (isset($_POST["captcha"]) && $_POST["captcha"] != "" && $_SESSION["code"] == $_POST["captcha"]) {
    $id_pengaduan_pelanggaran = getParam('id_pengaduan_pelanggaran');
    $nomor_referensi = getParam('nomor_referensi');
    $nama_pelapor = getParam('nama_pelapor');
    $nomor_telepon = getParam('nomor_telepon');
    $email = getParam('email');
    $nama_pelaku = getParam('nama_pelaku');
    $id_pelanggaran = getParam('id_pelanggaran');
    $uraian_pelanggaran = getParam('uraian_pelanggaran');
    $waktu_kejadian = getParam('waktu_kejadian');
    //$tempat_terjadi = getParam('tempat_terjadi');
    $kronologis_permasalahan = getParam('kronologis_permasalahan');
    $captcha = getParam('captcha');
    $upt = getParam('uptd');
    $lok = getParam('lokasi');


//    $waktu_kejadian = getParam('waktu_kejadian');
//    if($_FILES['unggah_file']['type']!= "image/JPG" or $_FILES['unggah_file']['type']!= "image/GIF" or $_FILES['unggah_file']['type']!= "image/DOC" or $_FILES['unggah_file']['type']!= "image/PDF" or $_FILES['unggah_file']['type']!= "image/DOCX" or $_FILES['unggah_file']['type']!= "image/XLS" or  $_FILES['unggah_file']['type']!= "image/XLSX" or $_FILES['unggah_file']['type']!= "image/PPT" or $_FILES['unggah_file']['type']!= "image/PPTX" or $_FILES['unggah_file']['type']!= "image/JPEG" or $_FILES['unggah_file']['type']!= "image/PNG" or
//       $_FILES['unggah_file']['type']!= "image/jpg" or $_FILES['unggah_file']['type']!= "image/gif" or $_FILES['unggah_file']['type']!= "image/doc" or $_FILES['unggah_file']['type']!= "image/pdf" or $_FILES['unggah_file']['type']!= "image/docx" or $_FILES['unggah_file']['type']!= "image/xls" or  $_FILES['unggah_file']['type']!= "image/xlsx" or $_FILES['unggah_file']['type']!= "image/ppt" or $_FILES['unggah_file']['type']!= "image/pptx" or $_FILES['unggah_file']['type']!= "image/jpeg" or $_FILES['unggah_file']['type']!= "image/png"){
//        echo"<script>alert('Tipe FIle Tidak Sesuai');
//                javascript:history.go(-1);</script>";
//        exit;
//    }
    //type file yang bisa diupload
    $file_type = array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'doc', 'docx', 'xls', 'xlsx', 'pdf', 'ppt', 'pptx');
    $unggah_file = $_FILES['unggah_file']['name'];
    $file_size = $_FILES['unggah_file']['size'];
    //cari extensi file dengan menggunakan fungsi explode
    $explode = explode('.', $unggah_file);
    $extensi = $explode[count($explode) - 1];
    //tukuran maximum file yang dapat diupload
    $max_size = 2000000; // 1MB
    if (!in_array($extensi, $file_type)) {
        echo"<script>alert('Tipe File Tidak Sesuai'); javascript:history.go(-1);</script>";
    } else {
        if ($file_size > $max_size) {
            echo"<script>alert('Ukur File Tidak Sesuai'); javascript:history.go(-1);</script>";
        } else {

            if (strlen($unggah_file) > 0) {
                //upload
                if (is_uploaded_file($_FILES['unggah_file']['tmp_name'])) {
                    move_uploaded_file($_FILES['unggah_file']['tmp_name'], "admin/menu/mn_pengaduan/file/" . $unggah_file);
                }
            }
            $query = mysql_query("INSERT INTO tbl_pengaduan_pelanggaran(nomor_referensi, nama_pelapor, nomor_telepon, email, nama_pelaku, id_pelanggaran, uraian_pelanggaran, waktu_kejadian, tempat_terjadi, lokasi, kronologis_permasalahan, unggah_file, captcha)
                		 VALUES('$nomor_referensi','$nama_pelapor','$nomor_telepon','$email','$nama_pelaku','$id_pelanggaran','$uraian_pelanggaran','$waktu_kejadian','$upt','$lok','$kronologis_permasalahan','$unggah_file', '$captcha')
                	    ");
            if ($query) {
                echo "<script>alert('Data Berhasil Di input'); window.location = 'pelaporan.php'</script>";
            } else {
                echo"<script>alert('Data Gagal Di Input'); javascript:history.go(-1);</script>";
            }
        }
    }
} else {
    echo"<script>alert('Captcha Yang Anda Masukan Tidak Sesuai'); javascript:history.go(-1);</script>";
    exit;
}
?>
