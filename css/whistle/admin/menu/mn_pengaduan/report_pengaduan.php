<?php

function konversi_tanggal($format, $tanggal = "now", $bahasa = "id") {
    $en = array("Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Jan", "Feb",
        "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");

    $id = array("Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu",
        "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September",
        "Oktober", "November", "Desember");

// tambahan untuk bahasa prancis
// sumber http://w.blankon.in/6V
    $fr = array("dimanche", "lundi", "mardi", "mercredi", "jeudi", "vendredi", "samedi",
        "janvier", "février", "mars", "avril", "Mei", "mai", "juillet", "aoùt", "septembre",
        "octobre", "novembre", "décembre");

// mengganti kata yang berada pada array en dengan array id, fr (default id)
    return str_replace($en, $$bahasa, date($format, strtotime($tanggal)));
}

?>
<?php

include_once("../../lib.php");
require ('../../fpdf/fpdf.php');
session_start();

$id_pengaduan_pelanggaran = getParam('id_pengaduan_pelanggaran');
$pdf = new FPDF('P', 'cm', 'A4');
$pdf->AddPage();

$tampil = mysql_query("select tbl_pengaduan_pelanggaran.*, tbl_pelanggaran.jenis_pelanggaran from tbl_pengaduan_pelanggaran join tbl_pelanggaran on tbl_pengaduan_pelanggaran.id_pelanggaran=tbl_pelanggaran.id_pelanggaran where tbl_pengaduan_pelanggaran.id_pengaduan_pelanggaran='$id_pengaduan_pelanggaran'")or die(mysql_error());
while ($r = mysql_fetch_array($tampil)) {
    
    $pdf->SetFont('Arial', '', 12);
//$pdf->Image("http://localhost/ppap/images/gambar1.jpg", 0.8, 0.5, 4.8, 2);
    $pdf->Cell(19, 0.5, 'APLIKASI DATA PELAPOR BAKEUDA JAMBI', 0, 1, "R");
    $pdf->SetFont('Arial', 'i', 12);
    $pdf->Cell(19, 0.5, 'DATA REPORTING APPLICATION OF BAKEUDA JAMBI', 0, 1, "R");

    $pdf->Ln(1);
    $pdf->SetTextColor(255, 0, 10);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetFont('Arial', 'U', 10);
    $pdf->Cell(19, 0.5, 'FORMULIR PENGADUAN PELANGGARAN', 0, 0, "");
    $pdf->Ln(0.8);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(3, 0.5, 'Nomor Referensi', 0, 0, "L");
    $pdf->Cell(1, 0.5, ':', 0, 0, "C");
    $pdf->Cell(0, 0.5, $r['nomor_referensi'], 0, 0, "L");
    $pdf->Ln(0.8);
    $pdf->SetTextColor(255, 0, 10);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetFont('Arial', 'U', 10);
    $pdf->Cell(19, 0.5, 'IDENTITAS PELAPOR', 0, 0, "");
    $pdf->Ln(0.8);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(3, 0.5, 'Nama Pelapor', 0, 0, "L");
    $pdf->Cell(1, 0.5, ':', 0, 0, "C");
    $pdf->Cell(0, 0.5, $r['nama_pelapor'], 0, 0, "L");
    $pdf->Ln(0.5);
    $pdf->Cell(3, 0.5, 'Nomor Telepon', 0, 0, "L");
    $pdf->Cell(1, 0.5, ':', 0, 0, "C");
    $pdf->Cell(0, 0.5, $r['nomor_telepon'], 0, 0, "L");
    $pdf->Ln(0.5);
    $pdf->Cell(3, 0.5, 'Email', 0, 0, "L");
    $pdf->Cell(1, 0.5, ':', 0, 0, "C");
    $pdf->Cell(0, 0.5, $r['email'], 0, 0, "L");
    $pdf->Ln(0.8);
    $pdf->SetTextColor(255, 0, 10);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetFont('Arial', 'U', 10);
    $pdf->Cell(19, 0.5, 'TERLAPOR', 0, 0, "");
    $pdf->Ln(0.8);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(3, 0.5, 'Nama Pelaku', 0, 0, "L");
    $pdf->Cell(1, 0.5, ':', 0, 0, "C");
    $pdf->Cell(0, 0.5, $r['nama_pelaku'], 0, 0, "L");
    $pdf->Ln(0.5);
    $pdf->Cell(3, 0.5, 'Jenis Pelanggaran', 0, 0, "L");
    $pdf->Cell(1, 0.5, ':', 0, 0, "C");
    $pdf->Cell(0, 0.5, $r['jenis_pelanggaran'], 0, 0, "L");
    $pdf->Ln(0.5);
    $pdf->Cell(3, 0.5, 'Uraian Pelanggaran', 0, 0, "L");
    $pdf->Cell(1, 0.5, ':', 0, 0, "C");
    $pdf->Cell(0, 0.5, $r['uraian_pelanggaran'], 0, 0, "L");
    $pdf->Ln(0.5);
    $pdf->Cell(3, 0.5, 'Waktu Kejadian', 0, 0, "L");
    $pdf->Cell(1, 0.5, ':', 0, 0, "C");
    $pdf->Cell(0, 0.5, $r['waktu_kejadian'], 0, 0, "L");
    $pdf->Ln(0.5);
    $pdf->Cell(3, 0.5, 'Tempat Terjadi', 0, 0, "L");
    $pdf->Cell(1, 0.5, ':', 0, 0, "C");
    $pdf->Cell(0, 0.5, $r['tempat_terjadi'], 0, 0, "L");
    $pdf->Ln(0.5);
    $pdf->Cell(3, 0.5, 'Kronologis Permasalah', 0, 0, "L");
    $pdf->Cell(1, 0.5, ':', 0, 0, "C");
    $pdf->Cell(0, 0.5, $r['kronologis_permasalahan'], 0, 0, "L");
    $pdf->Ln(0.5);
    $pdf->Cell(3, 0.5, 'Unggah File', 0, 0, "L");
    $pdf->Cell(1, 0.5, ':', 0, 0, "C");
    $pdf->Cell(0, 0.5, $r['unggah_file'], 0, 0, "L");
    $pdf->Ln(0.5);
    $pdf->Cell(3, 0.5, 'Captcha', 0, 0, "L");
    $pdf->Cell(1, 0.5, ':', 0, 0, "C");
    $pdf->Cell(0, 0.5, $r['captcha'], 0, 0, "L");
    $pdf->Ln(0.5);
    $pdf->Cell(3, 0.5, 'Lokasi', 0, 0, "L");
    $pdf->Cell(1, 0.5, ':', 0, 0, "C");
    $pdf->Cell(0, 0.5, $r['lokasi'], 0, 0, "L");

//        $pdf->Ln(0.5);
//        if ($r['kode_cabang'] == $r['atm_dari_cabang']) {
//            
//        } else {
//            $pdf->Cell(3, 0.5, 'Atm Dari Cabang', 0, 0, "L");
//            $pdf->Cell(1, 0.5, ':', 0, 0, "C");
//            $pdf->Cell(0, 0.5, $r['atm_dari_cabang1'], 0, 0, "L");
//        }
//        $pdf->Ln(1);
//        $pdf->SetTextColor(255, 0, 10);
//        $pdf->SetFont('Arial', 'B', 10);
//        $pdf->SetFont('Arial', 'U', 10);
//        $pdf->Cell(19, 0.5, 'DATA PRIBADI/ Personal Data', 0, 0, "");
//        $pdf->Ln(1);
//        $pdf->SetFont('Arial', '', 9);
//        $pdf->SetTextColor(0, 0, 0);
//        $pdf->Cell(3, 0.5, 'Nama Lengkap', 0, 0, "L");
//        $pdf->Cell(1, 0.5, ':', 0, 0, "C");
//        $pdf->Cell(0, 0.5, $r['nama_lengkap'], 0, 0, "L");
//        $pdf->Ln(0.5);
//        $pdf->Cell(3, 0.5, 'Tempat & Tgl. Lahir', 0, 0, "L");
//        $pdf->Cell(1, 0.5, ':', 0, 0, "C");
//        $pdf->Cell(0, 0.5, $tempat_lahir . ', ' . $tanggal_lahir, 0, 0, "L");
//        $pdf->Ln(0.5);
//        $pdf->Cell(3, 0.5, 'Nama Ibu Kandung', 0, 0, "L");
//        $pdf->Cell(1, 0.5, ':', 0, 0, "C");
//        $pdf->Cell(0, 0.5, $r['nama_ibu_kandung'], 0, 0, "L");
//        $pdf->Ln(0.5);
//        $pdf->Cell(3, 0.5, 'Alamat Rumah', 0, 0, "L");
//        $pdf->Cell(1, 0.5, ':', 0, 0, "C");
//        $pdf->Cell(0, 0.5, $r['alamat_rumah'], 0, 0, "L");
//        $pdf->Ln(0.5);
//        $pdf->Cell(3, 0.5, 'Kode Pos', 0, 0, "L");
//        $pdf->Cell(1, 0.5, ':', 0, 0, "C");
//        $pdf->Cell(0, 0.5, $r['kode_pos'], 0, 0, "L");
//        $pdf->Ln(0.5);
//        $pdf->Cell(3, 0.5, 'Telepon', 0, 0, "L");
//        $pdf->Cell(1, 0.5, ':', 0, 0, "C");
//        $pdf->Cell(0, 0.5, $r['telepon'], 0, 0, "L");
//        $pdf->Ln(0.5);
//        $pdf->Cell(3, 0.5, 'Handphone', 0, 0, "L");
//        $pdf->Cell(1, 0.5, ':', 0, 0, "C");
//        $pdf->Cell(0, 0.5, $r['handphone'], 0, 0, "L");
//        $pdf->Ln(0.5);
//        $pdf->Cell(3, 0.5, 'Alamat Kantor', 0, 0, "L");
//        $pdf->Cell(1, 0.5, ':', 0, 0, "C");
//        $pdf->Cell(0, 0.5, $r['alamat_kantor'], 0, 0, "L");
//        $pdf->Ln(0.5);
//        $pdf->Cell(3, 0.5, 'Telepon', 0, 0, "L");
//        $pdf->Cell(1, 0.5, ':', 0, 0, "C");
//        $pdf->Cell(0, 0.5, $r['telepon_kantor'], 0, 0, "L");
//        $pdf->Ln(0.5);
//        $pdf->Cell(3, 0.5, 'Kode Pos', 0, 0, "L");
//        $pdf->Cell(1, 0.5, ':', 0, 0, "C");
//        $pdf->Cell(0, 0.5, $r['kode_pos_kantor'], 0, 0, "L");
//        $pdf->Ln(0.5);
//        $pdf->Cell(3, 0.5, 'Kartu Identitas', 0, 0, "L");
//        $pdf->Cell(1, 0.5, ':', 0, 0, "C");
//        $pdf->Cell(0, 0.5, $r['kartu_identitas'], 0, 0, "L");
//        $pdf->Ln(0.5);
//        $pdf->Cell(3, 0.5, 'No Kartu Identitas', 0, 0, "L");
//        $pdf->Cell(1, 0.5, ':', 0, 0, "C");
//        $pdf->Cell(0, 0.5, $r['no_kartu_identitas'], 0, 0, "L");
//        $pdf->Ln(0.5);
//        $pdf->Cell(3, 0.5, 'Nama Pada Kartu ATM', 0, 0, "L");
//        $pdf->Cell(1, 0.5, ':', 0, 0, "C");
//        $pdf->Cell(0, 0.5, $r['nama_pada_kartu'], 0, 0, "L");
//        $pdf->Ln(1.5);
//        $pdf->SetTextColor(255, 0, 10);
//        $pdf->SetFont('Arial', 'B', 10);
//        $pdf->SetFont('Arial', 'U', 10);
//        $pdf->Cell(19, 0.5, 'PERSETUJUAN', 0, 0, "");
//        $pdf->Ln(1);
//
//        if ($r['kode_cabang'] == $r['atm_dari_cabang']) {
//            $pdf->SetFont('Arial', '', 8);
//            $pdf->SetTextColor(0, 0, 0);
//            $pdf->Cell(19, 0.5, 'SAYA MENYATAKAN DATA DIATAS ADALAH BENAR DAN MENYETUJUI SERTA TUNDUK PADA KETENTUAN-KETENTUAN DALAM SYARAT-SYARAT', 0, 0, "J");
//            $pdf->Ln(0.5);
//            $pdf->Cell(19, 0.5, 'UMUM PEMBUKAAN REKENING DAN KETENTUAN MENGENAI PENGGUNAAN KARTU ATM BANK JAMBI TERLAMPIR YANG MERUPAKAN SATU', 0, 0, "J");
//            $pdf->Ln(0.5);
//            $pdf->Cell(19, 0.5, 'KESATUAN DENGAN FORMULIR PERMOHONAN INI MAUPUN KETENTUAN LAIN YANG BERLAKU DARI WAKTU KE WAKTU DI BANK JAMBI', 0, 0, "J");
//            $pdf->Ln(0.5);
//            $pdf->Cell(19, 0.5, '', 0, 0, "J");
//            $pdf->Ln(0.5);
//            $pdf->Cell(19, 0.5, '', 0, 0, "J");
//            $pdf->Ln(0.5);
//        } else {
//            $pdf->SetFont('Arial', '', 8);
//            $pdf->SetTextColor(0, 0, 0);
//            $pdf->Cell(19, 0.5, 'SAYA MENYATAKAN DATA DIATAS ADALAH BENAR DAN MENYETUJUI SERTA TUNDUK PADA KETENTUAN-KETENTUAN DALAM SYARAT-SYARAT', 0, 0, "J");
//            $pdf->Ln(0.5);
//            $pdf->Cell(19, 0.5, 'UMUM PEMBUKAAN REKENING DAN KETENTUAN MENGENAI PENGGUNAAN KARTU ATM BANK JAMBI TERLAMPIR YANG MERUPAKAN SATU', 0, 0, "J");
//            $pdf->Ln(0.5);
//            $pdf->Cell(19, 0.5, 'KESATUAN DENGAN FORMULIR PERMOHONAN INI MAUPUN KETENTUAN LAIN YANG BERLAKU DARI WAKTU KE WAKTU DI BANK JAMBI.', 0, 0, "J");
//            $pdf->Ln(0.5);
//            $pdf->Cell(19, 0.5, 'BERSAMA INI KAMI SAMPAIKAN BAHWA KAMI TELAH MENGHUBUNGI PETUGAS CUSTOMER SERVICE CABANG SAUDARA SECARA BERSAMA ', 0, 0, "J");
//            $pdf->Ln(0.5);
//            $pdf->Cell(19, 0.5, 'TELAH MEMASTIKAN KEBENARAN DATA NASABAH TERSEBUT DAN BERTANGGUNGJAWAB PENUH AKAN KEBENARAN DATA NASABAH', 0, 0, "J");
//            $pdf->Ln(0.5);
//            $pdf->Cell(19, 0.5, 'TERSEBUT. SEGALA DATA YANG KAMI SAMPAIKAN MENJADI TANGGUNG JAWAB KAMI', 0, 0, "J");
//        }
//        $pdf->Ln(1);
//        $pdf->SetFont('Arial', '', 10);
//        $pdf->Cell(0, 0.5, $r['nama_cabang'] . ', ' . $tanggal_awal, 0, 0, "L");
//        $pdf->Ln(0.5);
//        $pdf->Cell(3, 0.5, 'Nasabah / Customer', 0, 0, "L");
//        $pdf->Cell(1, 0.5, ':', 0, 0, "C");
//        $pdf->Cell(0, 0.5, $r['nama_lengkap'], 0, 0, "L");
//        $pdf->Ln(1);
//        $pdf->SetTextColor(255, 0, 10);
//        $pdf->SetFont('Arial', 'B', 10);
//        $pdf->SetFont('Arial', 'U', 10);
//        $pdf->Cell(19, 0.5, 'UNTUK KEPERLUAN BANK / For Bank Use Only', 0, 0, "");
//        $pdf->Ln(1);
//        $pdf->SetFont('Arial', '', 9);
//        $pdf->SetTextColor(0, 0, 0);
//        $pdf->Cell(3, 0.5, 'Nomor Nasabah', 0, 0, "L");
//        $pdf->Cell(1, 0.5, ':', 0, 0, "C");
//        $pdf->Cell(0, 0.5, $r['nomor_nasabah'], 0, 0, "L");
//        $pdf->Ln(0.5);
//        $pdf->Cell(3, 0.5, 'Nomor Kartu', 0, 0, "L");
//        $pdf->Cell(1, 0.5, ':', 0, 0, "C");
//        $pdf->Cell(0, 0.5, $r['nomor_kartu'], 0, 0, "L");
//        $pdf->Ln(0.5);
//        $pdf->Cell(3, 0.5, 'Disetujui Tanggal', 0, 0, "L");
//        $pdf->Cell(1, 0.5, ':', 0, 0, "C");
//        $pdf->Cell(0, 0.5, $disetujui_tanggal, 0, 0, "L");
//        $pdf->Ln(1);
//        $pdf->SetTextColor(255, 0, 10);
//        $pdf->SetFont('Arial', 'B', 10);
//        $pdf->SetFont('Arial', 'U', 10);
//        $pdf->Cell(19, 0.5, 'PERSETUJUAN DARI :', 0, 0, "");
//        $pdf->Ln(1);
//        $pdf->SetFont('Arial', '', 9);
//        $pdf->SetTextColor(0, 0, 0);
//        $pdf->Cell(3, 0.5, 'Nama Petugas', 0, 0, "L");
//        $pdf->Cell(1, 0.5, ':', 0, 0, "C");
//        $pdf->Cell(0, 0.5, $r['nama_petugas'], 0, 0, "L");
//        $pdf->Ln(0.5);
//        $pdf->Cell(3, 0.5, 'Nama Atasan', 0, 0, "L");
//        $pdf->Cell(1, 0.5, ':', 0, 0, "C");
//        $pdf->Cell(0, 0.5, $r['nama_atasan'], 0, 0, "L");
//
//        $pdf->Ln(0.5);
//        $pdf->Image("http://localhost/ppap/images/gambar1.jpg", 0.8, 0.5, 4.8, 2);
}
$pdf->Output();
?>
