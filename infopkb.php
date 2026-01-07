<?php
/***
*  infopkb.php
*  informasi pkb
*
*  (p) Iwan abu bakar (Iw'87)
*/

error_reporting(0);
date_default_timezone_set('Asia/Krasnoyarsk');

include_once "pgdbtool.php";
include_once "samlib.php";
require_once "Mobile-Detect/Mobile_Detect.php";
$detect = new Mobile_Detect;

if(!$dbonl->connected){
    ret_err("not connected!");
}

$no_polisi = get_http_arg("no_polisi");
$no_polisi = setnopol($no_polisi);

$tg_akhir_stnk = get_http_arg("tg_akhir_stnk", "", false, false);
if($tg_akhir_stnk){
    $n = strlen($tg_akhir_stnk);
    switch($n){
        case 8:
	    $tg_akhir_stnk = substr($tg_akhir_stnk, 0, 2) . "/" .
                             substr($tg_akhir_stnk, 2, 2) . "/" .
                             substr($tg_akhir_stnk, 4);
	    break;

	case 6:
	    $tg_akhir_stnk = substr($tg_akhir_stnk, 0, 2) . "/" .
                             substr($tg_akhir_stnk, 2, 2) . "/20" .
                             substr($tg_akhir_stnk, 4);
	    break;
    }
}

// untuk yang nopol-nya dobel
$nm_pemilik = get_http_arg("nm_pemilik", "", false, false);

$where = "";
if($nm_pemilik){
    $nm_pemilik = strtoupper($nm_pemilik);
    $where = "AND nm_pemilik like '%$nm_pemilik%'";
}

$tg_akhir_pkb = get_http_arg("tg_akhir_pkb", "", false, false);
if($tg_akhir_pkb){
    $n = strlen($tg_akhir_pkb);
    switch($n){
        case 8:
	    $tg_akhir_pkb = substr($tg_akhir_pkb, 0, 2) . "/" .
                            substr($tg_akhir_pkb, 2, 2) . "/" .
                            substr($tg_akhir_pkb, 4);
	    break;

	case 6:
	    $tg_akhir_pkb = substr($tg_akhir_pkb, 0, 2) . "/" .
                            substr($tg_akhir_pkb, 2, 2) . "/20" .
                            substr($tg_akhir_pkb, 4);
	    break;
    }
}


$izin_ang  = get_http_arg("izin_ang", "0", false, false);
if($izin_ang == "on") $izin_ang = "1";

$siup_ang  = get_http_arg("siup_ang", "0", false, false);
if($siup_ang == "on") $siup_ang = "1";

$kir_ang  = get_http_arg("kir_ang", "0", false, false);
if($kir_ang == "on") $kir_ang = "1";

$kd_merek_kb = get_http_arg("kd_merek_kb", "", false, false);

$tg_daftar = date('d/m/Y');
$d_tg_daftar = to_date($tg_daftar);

$query = "SELECT nilai FROM t_param
	     WHERE kd_param = 'SISTEM'
               AND kd_data = 'PEMUTIHAN-PAJAK'";

// anggap tidak ada pemutihan pajak
$set_pp    = array( 'pokok_pkb'     => 'X', 
		    'denda_pkb'     => 'X',
		    'pokok_swdkllj' => 'X',
                    'denda_swdkllj' => 'X' );

$pemutihan = $dbonl->getvalue($query);

// ada pemutihan?
if($pemutihan == "Y"){
   // tgl. awal dan tgl. akhir pemutihan pajak
   $tg_awal_pp  = p_param("TGL-AWAL-PP");
   $tg_akhir_pp = p_param("TGL-AKHIR-PP");

   // baca setting pemutihan pajak
   $query = "SELECT * FROM t_set_pp
		WHERE tg_awal  = '" . to_dbdate($tg_awal_pp) . "'
                  AND tg_akhir = '" . to_dbdate($tg_akhir_pp) . "'";
   
   $set_pp = $dbonl->getrow($query, "d/m/Y");

   if($set_pp){
	$d_tg_awal =  to_date($set_pp['tg_awal'] );
        $d_tg_akhir = to_date($set_pp['tg_akhir']);

        // diluar tgl. pemutihan -> gak ada pemutihan
        if($d_tg_daftar < $d_tg_awal || $d_tg_daftar > $d_tg_akhir)
	    $pemutihan = "T";
   } else {
        // gak ada pemutihan
	$pemutihan = "T";
   }
} else {
    // gak ada pemutihan
    $pemutihan = "T";
}

$result   = false;
$tg_bayar = "1990-01-01";
$found    = 0;

// baca di data transaksi tahun berjalan
$query = "SELECT no_polisi, nm_pemilik, al_pemilik, kd_jen_milik, kd_fungsi,
                 kd_kel_kb, kd_jenis_kb, kd_merek_kb, nm_merek_kb, nm_model_kb, 
                 nm_jenis_kb, th_rakitan, jumlah_cc, warna_kb, kd_plat, kd_bbm, 
                 tg_akhir_pkb, tg_akhir_jr, tg_akhir_stnk, 
                 tg_bayar, kd_mohon, kd_lokasi
              FROM t_trnkb
	     WHERE no_polisi = '$no_polisi'
               AND tg_bayar  > '$tg_bayar'
	       $where
            ORDER BY tg_bayar DESC, no_urut_trn DESC";

$row = $dbonl->getrow($query, "d/m/Y");

// datanya ada di data transaksi tahun berjalan
if($row){
    $result   = $row;
    $tg_bayar = to_dbdate($row['tg_bayar']);
    $found    = 1;
}

// baca di data master
$query = "SELECT no_polisi, nm_pemilik, al_pemilik, kd_jen_milik, kd_fungsi,
                 kd_kel_kb, kd_jenis_kb, kd_merek_kb, nm_merek_kb, nm_model_kb, 
                 nm_jenis_kb, th_rakitan, jumlah_cc, warna_kb, kd_plat, kd_bbm, 
                 tg_akhir_pkb, tg_akhir_jr, tg_akhir_stnk, tg_bayar, '-', "-"
              FROM t_mstkb
	     WHERE no_polisi = '$no_polisi'
	       AND tg_bayar  > '$tg_bayar'
               $where";

$row = $dbonl->getrow($query, "d/m/Y");

// datanya ada di data master
if($row){
    $result   = p_mst2trn($row);
    $tg_bayar = to_dbdate($row['tg_bayar']);
    $found    = 2;
}

if($found != 1){
    // baca di data transaksi yang sudah selesai (tt_trnkb)
    $query = "SELECT no_polisi, nm_pemilik, al_pemilik, kd_jen_milik, kd_fungsi,
                     kd_kel_kb, kd_jenis_kb, kd_merek_kb, nm_merek_kb, 
		     nm_model_kb, nm_jenis_kb, th_rakitan, jumlah_cc, 
		     warna_kb, kd_plat, kd_bbm, tg_akhir_pkb, tg_akhir_jr, 
		     tg_akhir_stnk, tg_bayar, kd_mohon, kd_lokasi
                 FROM tt_trnkb
                WHERE no_polisi = '$no_polisi'
                  AND tg_bayar  > '$tg_bayar'
		  $where
              ORDER BY tg_bayar DESC, no_urut_trn DESC";

    $row = $dbonl->getrow($query, "d/m/Y");

    // datanya ada di data transaksi yang sudah selesai
    if($row){
        $result = $row;
        $found  = 3;
    }
}

$datakb = false;
$pkb = 0;
$swd = 0;
$tot = 0;
$tg_akhir_yad = "";

$error  = false;
$errmsg = "";
$errno  = 0;

if($found){
   $kd_mohon = $result['kd_mohon'];

   // sudah diremajakan
   if(preg_match('.[36X]X.', $kd_mohon) == 1 or
      preg_match('.3[34].', $kd_mohon) == 1) {

	$found = false;

   }
}

if($found){

    // cek ke data penerimaan swdkllj
    $s = to_dbdate($result['tg_akhir_pkb']);

    $query = "SELECT tg_akhir_jr FROM t_datapnrjr
		 WHERE no_polisi   = '$no_polisi'
		   AND tg_akhir_jr > '$s'
	         ORDER BY tg_akhir_jr DESC";

    $s = $dbonl->getvalue($query, "d/m/Y");
    if($s){ 
	$result['tg_akhir_pkb'] = $s;
	$result['tg_akhir_jr'] = $s;
    }

    $datakb = $result;

    $datakb['kd_mohon']   = ".2.";
    $datakb['tg_daftar']  = date('d/m/Y');
    $datakb['kd_proses']  = "pkb";
    $datakb['jt_berubah'] = false;
    if($tg_akhir_pkb){
        $result['tg_akhir_pkb'] = $tg_akhir_pkb;
        $result['tg_akhir_jr']  = $tg_akhir_pkb;

        $datakb['tg_akhir_pkb'] = $tg_akhir_pkb;
        $datakb['tg_akhir_jr']  = $tg_akhir_pkb;
    }
    $datakb['izin_ang']   = $izin_ang;
    $datakb['siup_ang']   = $siup_ang;
    $datakb['kir_ang' ]   = $kir_ang;

    if($kd_merek_kb){
        $datakb['kd_merek_kb'] = $kd_merek_kb;
	$query = "SELECT nm_merek_kb, nm_model_kb, nm_jenis_kb
		     FROM t_merekkb
		   WHERE kd_merek_kb = '$kd_merek_kb'";
	$row = $dbonl->getrow($query);
	if($row){
	    $datakb['nm_merek_kb'] = $row['nm_merek_kb'];
	    $datakb['nm_model_kb'] = $row['nm_model_kb'];
	    $datakb['nm_jenis_kb'] = $row['nm_jenis_kb'];
        }
    }

    // kalo tgl. akhir stnk-nya disebutkan
    if(to_date($tg_akhir_stnk)){
       $datakb['tg_akhir_stnk'] = $tg_akhir_stnk;
    }
    $datakb['ctk_stnk']      = "0";

    $datakb['byr_dimuka'] = false;
    $datakb['progresif']  = "";
    $datakb['no_urut']    = 1;
    $datakb['njkb']       = 0;
    $datakb['pct_trf']    = 1.5;
    $datakb['pct_pkb']    = 100;
    $datakb['tg_akhir_pkb_yad'] = "";

    $result['nm_lokasi']  = "-";
    if($datakb['kd_lokasi'] != '-'){
	$result['nm_lokasi'] = $dbonl->getvalue("SELECT nm_lokasi 
					            FROM t_nm_lokasi
						  WHERE kd_lokasi = '" .
					        $datakb['kd_lokasi'] . "'");
    }

    $datakb['pemutihan'] = $pemutihan;
    $datakb['set_pp'   ] = false;

    // kalo ada pemutihan
    if($pemutihan == "Y") $datakb['set_pp'] = $set_pp;

    $pkb  = hitpkb($datakb);
    $swd  = hitswd($datakb);
    $stnk = 0;
    $tnkb = 0;

    // ada tgl akhir stnk-nya
    if(to_date($datakb['tg_akhir_stnk'])){
	$tg_akhir_stnk = $datakb['tg_akhir_stnk'];
        $y1 = year($datakb['tg_daftar']);
        $y2 = year($datakb['tg_akhir_pkb']);

	if(year($tg_akhir_stnk) > $y2){
	    while(true){
		$tg_akhir_stnk = addyear($tg_akhir_stnk, -5);
		$y = year($tg_akhir_stnk);
		
                // kurang dari tgl. akhir pkb exit
		if($y < $y2) break;
		else
		   if($y < $y1) break;
		
		$datakb['tg_akhir_stnk'] = $tg_akhir_stnk;
	    }
        }

        $stnk = hitstnk($datakb);
        $tnkb = hittnkb($datakb);
    }
}

if($found){
    if(!$error){
        $tot = $pkb + $swd + $stnk + $tnkb;

        $pkb  = number_format($pkb, 0, ',', '.');
        $swd  = number_format($swd, 0, ',', '.');
        $stnk = number_format($stnk, 0, ',', '.');
        $tnkb = number_format($tnkb, 0, ',', '.');
        $tot  = number_format($tot, 0, ',', '.');

        $tg_akhir_yad = $datakb['tg_akhir_pkb_yad'];
    }
}

function p_mst2trn($vt_mstkb){
    global $dbonl;

    // $row = $dbonl->initvars("t_trnkb");

    $row = array();

    $row['no_polisi']	     = $vt_mstkb['no_polisi'];
    $row['nm_pemilik']	     = $vt_mstkb['nm_pemilik'];
    $row['al_pemilik']	     = $vt_mstkb['al_pemilik'];
    $row['kd_jen_milik']     = $vt_mstkb['kd_jen_milik'];
    $row['kd_fungsi']	     = $vt_mstkb['kd_fungsi'];
    $row['kd_kel_kb']	     = $vt_mstkb['kd_kel_kb'];
    $row['kd_jenis_kb']	     = $vt_mstkb['kd_jenis_kb'];
    $row['kd_merek_kb']	     = $vt_mstkb['kd_merek_kb'];
    $row['nm_merek_kb']	     = $vt_mstkb['nm_merek_kb'];
    $row['nm_model_kb']	     = $vt_mstkb['nm_model_kb'];
    $row['nm_jenis_kb']	     = $vt_mstkb['nm_jenis_kb'];
    $row['th_rakitan']	     = $vt_mstkb['th_rakitan'];
    $row['jumlah_cc']	     = $vt_mstkb['jumlah_cc'];
    $row['warna_kb']	     = $vt_mstkb['warna_kb'];
    $row['kd_plat']	     = $vt_mstkb['kd_plat'];
    $row['kd_bbm']	     = $vt_mstkb['kd_bbm'];
    $row['tg_akhir_pkb']     = $vt_mstkb['tg_akhir_pkb'];
    $row['tg_akhir_jr']      = $vt_mstkb['tg_akhir_jr'];
    $row['tg_akhir_stnk']    = $vt_mstkb['tg_akhir_stnk'];
    $row['tg_bayar']         = $vt_mstkb['tg_bayar'];
    $row['kd_mohon']         = "-";
    $row['kd_lokasi']        = "-";
 
    return $row;

}

function hitpkb(&$datakb){
    global $dbonl;

    // dikecualikan dalam pengenaan pkb
    if($datakb['kd_jen_milik'] == '06' or
       $datakb['kd_fungsi'] == '10')
    {
        return 0;
    }

    $no_polisi    = $datakb['no_polisi'];
    
    $tg_daftar    = date('d/m/Y');
    $tg_akhir_pkb = $datakb['tg_akhir_pkb'];

    // untuk perhitungan pkb, cek masa berlaku
    if($datakb['kd_proses'] == 'pkb'){    
        $d_tg_daftar    = to_date($tg_daftar);
        $d_tg_akhir_pkb = to_date($tg_akhir_pkb);
        
        if($d_tg_akhir_pkb > $d_tg_daftar){
            $sel_tgl = selisih_tgl($tg_daftar, $tg_akhir_pkb);
            if($sel_tgl['n'] > 90){
		set_error("MOHON MAAF, KENDARAAN ANDA BELUM SAATNYA DAFTAR ULANG!", -1);
                return -1;
            }
        }
    }
    
    // untuk kendaraan dinas    
    if($datakb['kd_kel_kb'] == 'D'){
		set_error("MOHON MAAF, KENDARAAN DINAS MASIH DILAKUKAN PENYESUAIAN PERHITUNGAN", -1);
                return -1;		
        // untuk permohonan daftar ulang
        if(strpos($datakb['kd_mohon'], '.2.') !== false){
            list($d, $m, $y) = split_date($datakb['tg_akhir_pkb']);
            if(mktime(0, 0, 0, $m, $d, $y) < mktime(0, 0, 0, 1, 1, 2012)){
                // di set ke tgl. 1/1/2012
                $tg_akhir_pkb = date('d/m/Y', mktime(0, 0, 0, 1, 1, 2012));
            }
        }    
    }

    // baca data merek kendaraan
    $found = getmrk($datakb['kd_merek_kb'], $datakb['nm_merek_kb'], $datakb['nm_model_kb'], 
                    $datakb['nm_jenis_kb']);
                        
    if(!$found) return -1;

    // baca tarif njkb
    $trfnj = gettrfnj($datakb['kd_merek_kb'], $datakb['th_rakitan']);
    if(!$trfnj) return -1;
    
    $datakb['njkb'] = "Rp" . number_format($trfnj['nilai_jual'], 0, ',', '.') .
                      ",- x " . str_replace(".", ",", $trfnj['bobot']);

    $datakb['byr_dimuka'] = false;
    
    // hitung selisih tgl akhir pkb dgn tgl. pendaftaran
    $sel_tgl = selisih_tgl($tg_akhir_pkb, $tg_daftar);

    switch($datakb['kd_plat']){
	case "2":
	    $datakb['kd_kel_kb'] = "U";
	    break;

	case "3":
	    $datakb['kd_kel_kb'] = "D";
	    break;

	default:
	    $datakb['kd_kel_kb'] = "P";
	    break;
    }

    // tarif dan pengenaan pkb        
    switch($datakb['kd_kel_kb']){
        default:
            $pct_trf = 1.5;
            $pct_pengenaan = 100;
            
            $trfpkb = ($pct_trf/100) * $trfnj['nilai_jual'] * $trfnj['bobot'] * ($pct_pengenaan/100);
            
	    // kendaraan pribadi non sepeda motor
            if($datakb['kd_jen_milik'] == '01' && 
               $datakb['kd_fungsi']    == '01' && 
               $datakb['kd_jenis_kb'] != 'R'){
               
                   if(substr($datakb['nm_pemilik'], 0, 2) != 'PT' && 
                      substr($datakb['nm_pemilik'], 0, 2) != 'CV'){
                   
                    $sql = "SELECT no_urut FROM t_progresif
                                WHERE no_polisi = '$no_polisi'";
		    $no_urut = $dbonl->getvalue($sql);

                    if(!$no_urut){
			set_error("MAAF, DATA KENDARAAN ANDA BELUM DISET PROGRESIF!", -2);
			return -1;
		    }
                    
                    switch($no_urut){
                        case 1:
                            $pct_trf = 1.5;
                            break;
                            
                        case 2:
                            $pct_trf = 2;
                            break;
                            
                        case 3:
                            $pct_trf = 2.5;
                            break;
                            
                        case 4:
                            $pct_trf = 3;
                            break;
                            
                        default:
                            $pct_trf = 3.5;
                    }
                    if($no_urut > 1){
			$datakb['no_urut'] = $no_urut;
                        $datakb['progresif'] = "PROGRESIF: PR$no_urut, TARIF: $pct_trf%, PKB Dasar: Rp." . number_format($trfpkb, 0, ',', '.') . ",-";
                    }
                }                 
            }

	    if($datakb['kd_fungsi'] == "04" ||
	       $datakb['kd_fungsi'] == "06" ||
	       $datakb['kd_fungsi'] == "07" ||
	       $datakb['kd_fungsi'] == "08"){

		$pct_trf = 0.5;
		$pct_pengenaan = 100;

		// kecuali sedan dan jeep
		if($datakb['kd_jenis_kb'] == "A" ||
		   $datakb['kd_jenis_kb'] == "B"){

		    $pct_trf = 1.5;
		    $pct_pengenaan = 100;

		}
	    }
            break;
            
        case 'U':
            $pct_trf = 1;
            $pct_pengenaan = 100;

	    // punya izin angkutan umum
            if($datakb['izin_ang'] == "1"){
		     $pct_pengenaan = 30;
                if(ereg('[FGH]', $datakb['kd_jenis_kb'])) 
		     $pct_pengenaan = 60;
            }
/*
	    // punya izin angkutan umum
            if($datakb['izin_ang'] == "1"){
                $pct_trf = 1;
                $pct_pengenaan = 100;

		// untuk perusahaan
                if($datakb['kd_jen_milik'] == '03' ||
                   $datakb['kd_jen_milik'] == '04' ||
                   $datakb['kd_jen_milik'] == '05' ||
                   $datakb['kd_jen_milik'] == '11' ||
                   $datakb['kd_jen_milik'] == '12' ||
                   substr($datakb['nm_pemilik'], 0, 3) == 'PT.'   ||
                   substr($datakb['nm_pemilik'], -4)   == '(PT)'  ||
                   substr($datakb['nm_pemilik'], -5)   == '(PT.)'
                  ){
		
		    // ada siup 
		    if($datakb['siup_ang'] == "1" && $datakb['kir_ang'] == "1"){
			$pct_pengenaan = 60;
                        if(ereg('[FG]', $datakb['kd_jenis_kb'])) 
			     $pct_pengenaan = 80;
		    }
                }
            }
*/
            break;
            
        case 'D':
		set_error("MOHON MAAF, KENDARAAN DINAS MASIH DILAKUKAN PENYESUAIAN PERHITUNGAN", -1);
                return -1;	
            $pct_trf = 0.5;
            $pct_pengenaan = 100;
            break;
    }
    
    $datakb['pct_trf'] = $pct_trf;
    $datakb['pct_pkb'] = $pct_pengenaan;

    // tarif pkb
    $trfpkb = ($pct_trf/100) * $trfnj['nilai_jual'] * $trfnj['bobot'] * ($pct_pengenaan/100);
    
    // sudah terlambat
    if($sel_tgl['n'] > 0){

        // pokok thn. berjalan
        $pkb_pok[0] = $trfpkb;

        // denda thn. berjalan        
        $m = $sel_tgl['m'];
        $y = $sel_tgl['y'];

        if($sel_tgl['d'] > 15) $m++;
        
        $pkb_den[0] = (2 + ($m * 2))/100 * $trfpkb; //====================================================================================================Denda PKB

	// init tunggakan
        for($i = 1; $i <= 5; $i++){
	    $pkb_pok[$i] = 0;
	    $pkb_den[$i] = 0;
	}

	$y = $sel_tgl['y'];
        if($y > 5) $y = 5;

	// tunggakan
	$m = $sel_tgl['m'];
        for($i = 1; $i <= $y; $i++){
	    $m += ($i * 12);
	    if($m > 24) $m = 24;
	    $pkb_pok[$i] = $trfpkb;
	    $pkb_den[$i] = (2 + ($m * 2))/100 * $trfpkb; //======================================================================================================Denda PKB
	}
        
        // jt berubah
        if($datakb['jt_berubah']){
            $m = $sel_tgl['m'];
            if($sel_tgl['d'] > 1) $m++;
            if($m > 0){
                $pkb_pok[1] += ($m / 12) * $trfpkb;
                $pkb_den[1] += (2 + ($m * 2))/100 * $trfpkb; //==================================================================================================Denda PKB
            }            
            $pkb_den[0] = 0;
        } else { 
	    // jt tidak berubah
            list($d, $m, $y) = split_date($tg_akhir_pkb);
            $tg_akhir_pkb_yad = date('d/m/Y', mktime(0, 0, 0, $m, $d, $y+$sel_tgl['y']+1));

	    // harus cetak stnk
	    if(year($tg_akhir_pkb_yad) == year($tg_akhir_stnk)){
		$datakb['ctk_stnk'] = "1";
	    }

            $sel_tgl2 = selisih_tgl($tg_daftar, $tg_akhir_pkb_yad);
            
	    // udah tinggal 90 hari lagi
            if($sel_tgl2['n'] <= 90){
	        // simpan data tahun berjalan
	        $pokok = $pkb_pok[0];
                $denda = $pkb_den[0];

                // pokok dan denda thn. berjalan
                $pkb_pok[0] = $trfpkb;
                $pkb_den[0] = 0;
        
		for($i = 5; $i > 1; $i--){
		    $pkb_pok[$i] = $pkb_pok[$i-1];
		    $pkb_den[$i] = $pkb_den[$i-1];
	        }

		$pkb_pok[1] = $pokok;
		$pkb_den[1] = $denda;

                $datakb['byr_dimuka'] = true; 
            }                                                            
        }
        
    } else {
        // belum terlambat
        
        // kalo jatuh temponya berubah
        if($datakb['jt_berubah']){        
            list($d, $m, $y) = split_date($tg_daftar);            
            $tg_akhir_pkb_yad = date('d/m/Y', mktime(0, 0, 0, $m, $d, $y+1));
            $sel_tgl = selisih_tgl($tg_akhir_pkb, $tg_akhir_pkb_yad);
            $m = $sel_tgl['m'];
            if($sel_tgl['d'] > 14) $m++;
            $pkb_pok[0] = ($m / 12) * $trfpkb;
            $pkb_den[0] = 0;
        } else {
            // jatuh tempo tidak berubah
            $pkb_pok[0] = $trfpkb;
            $pkb_den[0] = 0;
        }
    }

    // kalo ada pemutihan
    if($datakb['pemutihan'] == "Y"){

        // sebelum pemutihan
        $pok = 0;
        foreach($pkb_pok as &$value){
            $value = pembulatan($value);
            $pok += $value;
        }
    
        $den = 0;
        foreach($pkb_den as &$value){
            $value = pembulatan($value);
            $den += $value;
        }

        $tot = $pok + $den;

        $datakb['pok_pkb_awal'] = $pok;
        $datakb['den_pkb_awal'] = $den;
        $datakb['tot_pkb_awal'] = $tot;

	$set_pp = $datakb['set_pp'];

        // pemutihan pokok pkb
        switch($set_pp['pokok_pkb']){
	    case "0":
	        $pkb_pok[0] = 0;
	        $pkb_pok[1] = 0;
	        $pkb_pok[2] = 0;
	        $pkb_pok[3] = 0;
	        $pkb_pok[4] = 0;
	        $pkb_pok[5] = 0;
		break;

	    case "1":
	        $pkb_pok[1] = 0;
	        $pkb_pok[2] = 0;
	        $pkb_pok[3] = 0;
	        $pkb_pok[4] = 0;
	        $pkb_pok[5] = 0;
		break;

	    case "2":
                if($pkb_pok[1] > 0){
		   $pkb_pok[0] = $trfpkb;
		   $pkb_pok[1] = $trfpkb;
                }
	        $pkb_pok[2] = 0;
	        $pkb_pok[3] = 0;
	        $pkb_pok[4] = 0;
	        $pkb_pok[5] = 0;
		break;

	    case "3":
	        $pkb_pok[3] = 0;
	        $pkb_pok[4] = 0;
	        $pkb_pok[5] = 0;
		break;

	    case "4":
	        $pkb_pok[4] = 0;
	        $pkb_pok[5] = 0;
		break;

	    case "5":
	        $pkb_pok[5] = 0;
		break;
	}

        // pemutihan denda pkb
        switch($set_pp['denda_pkb']){
	    case "0":
	        $pkb_den[0] = 0;
	        $pkb_den[1] = 0;
	        $pkb_den[2] = 0;
	        $pkb_den[3] = 0;
	        $pkb_den[4] = 0;
	        $pkb_den[5] = 0;
		break;

	    case "1":
	        $pkb_den[1] = 0;
	        $pkb_den[2] = 0;
	        $pkb_den[3] = 0;
	        $pkb_den[4] = 0;
	        $pkb_den[5] = 0;
		break;

	    case "2":
	        $pkb_den[2] = 0;
	        $pkb_den[3] = 0;
	        $pkb_den[4] = 0;
	        $pkb_den[5] = 0;
		break;

	    case "3":
	        $pkb_den[3] = 0;
	        $pkb_den[4] = 0;
	        $pkb_den[5] = 0;
		break;

	    case "4":
	        $pkb_den[4] = 0;
	        $pkb_den[5] = 0;
		break;

	    case "5":
	        $pkb_den[5] = 0;
		break;
	}
    }

    $datakb['pkb_pok'] = $pkb_pok;
    $datakb['pkb_den'] = $pkb_den;

    $pok = 0;
    foreach($pkb_pok as &$value){
        $value = pembulatan($value);
        $pok += $value;
    }
    
    $den = 0;
    foreach($pkb_den as &$value){
        $value = pembulatan($value);
        $den += $value;
    }    
    $tot = $pok + $den;

    $datakb['pok_pkb_akhir'] = $pok;
    $datakb['den_pkb_akhir'] = $den;
    $datakb['tot_pkb_akhir'] = $tot;

    if($datakb['pemutihan'] == 'Y'){
        $datakb['jml_pp_pkb'] = $datakb['tot_pkb_awal'] - 
                                    $datakb['tot_pkb_akhir'];
    }

    $datakb['pokok_pkb'] = number_format($pok, 0, ",", ".");
    $datakb['denda_pkb'] = number_format($den, 0, ",", ".");
    $datakb['total_pkb'] = number_format($tot, 0, ",", ".");

    if($datakb['jt_berubah']){
        list($d, $m, $y) = split_date($tg_daftar);
        $datakb['tg_akhir_pkb_yad'] = date('d/m/Y', mktime(0, 0, 0, $d, $m, $y+1)); 
    } else {
        // jatuh tempo tidak berubah
        $n = ($datakb['byr_dimuka']) ? 2 : 1;
        
        list($d, $m, $y) = split_date($tg_akhir_pkb);
        $datakb['tg_akhir_pkb_yad'] = date('d/m/Y', mktime(0, 0, 0, $m, $d, $y+$sel_tgl['y']+$n));
    }
    
    if($datakb['kd_kel_kb'] == "D"){
           if(strpos($datakb['kd_mohon'], ".2.") !== false){
               list($d, $m, $y) = split_date($datakb['tg_akhir_pkb_yad']);
            
            // kalo tgl. akhir pkb yang akan datang sebelum 1/1/2013, 
            // tidak dipungut
            if(mktime(0, 0, 0, $m, $d, $y) < mktime(0, 0, 0, 1, 1, 2013)){            
                $tot = 0;
            }
        }
    }

    return $tot;
    
}

function getmrk(&$kd_merek_kb, $nm_merek_kb, $nm_model_kb, $nm_jenis_kb){
    global $dbonl;

    $kode = substr($kd_merek_kb, 0, 3);
    switch($kode){
	case "101":
	   $nm_jenis_kb = "SEDAN";
	   break;

 	case "102":
	   $nm_jenis_kb = "JEEP";
	   break;

	case "103":
	   $nm_jenis_kb = "MINIBUS";
	   break;

	case "351":
	   if($nm_jenis_kb == "LIGH TRUK") $nm_jenis_kb = "LIGHT TRUCK";
	   break;

	case "401":
	   if($nm_jenis_kb == "TRUK") $nm_jenis_kb = "TRUCK";
	   break;

	case "701":
	   $nm_jenis_kb = "SPD. MOTOR R2";
	   break;

	case "702":
	   $nm_jenis_kb = "SPD. MOTOR R3";
	   break;
    }

    $s = substr($nm_jenis_kb, -3);
    if(substr($s, 0, 1) == "/") $nm_jenis_kb = substr($nm_jenis_kb, 0, -3);

    $mrk1  = setmodel($nm_merek_kb, $nm_model_kb, $nm_jenis_kb);
    $mrk1b = str_replace("0", "O", $mrk1);
                
    $sql = "SELECT nm_merek_kb, nm_model_kb, nm_jenis_kb
                FROM t_merekkb
               WHERE kd_merek_kb = '$kd_merek_kb'";
    $datamrk = $dbonl->getrow($sql);

    $found_mrk = false;
    
    if($datamrk and is_array($datamrk)){
        $mrk2  = setmodel($datamrk['nm_merek_kb'], $datamrk['nm_model_kb'], $datamrk['nm_jenis_kb']);
        $mrk2b = str_replace("0", "O", $mrk2);

        if($mrk1 == $mrk2 || $mrk1b == $mrk2b) {
            $found_mrk = true;        
        }
    }
    
    if(! $found_mrk){

        $sql = "SELECT kd_merek_kb, str_merekkb
                    FROM t_merekkb
                   WHERE nm_merek_kb = '$nm_merek_kb' 
                     AND nm_jenis_kb = '$nm_jenis_kb'";

	$rs = $dbonl->query($sql);
        while($row = $dbonl->fetch_assoc($rs)){
            $mrk2  = trim($row['str_merekkb']);
	    $mrk2b = str_replace("0", "O", $mrk2);

            if($mrk1 == $mrk2 || $mrk1b == $mrk2b){
                $kd_merek_kb = $row['kd_merek_kb'];
                $found_mrk = true;
                break;
            }
        }
        
        if(! $found_mrk){
            $sql = "SELECT kd_merek_kb, nm_merek_kb, nm_model_kb, nm_jenis_kb
                        FROM t_merekkb
                       WHERE str_merekkb = '$mrk1'";
	    $datamrk = $dbonl->getrow($sql);
            $mrk2  = setmodel($datamrk['nm_merek_kb'], $datamrk['nm_model_kb'], $datamrk['nm_jenis_kb']);
            $mrk2b = str_replace("0", "O", $mrk2);

            if($mrk1 == $mrk2 || $mrk1b == $mrk2b){
                $kd_merek_kb = $datamrk['kd_merek_kb'];            
                $found_mrk = true;
            }
        }            
    }

    if($found_mrk){
/*
        if($mrk1 != $mrk2){
            set_error("MOHON MAAF, DATA KODING KENDARAAN ANDA BELUM TERDATA!", -3);
            return false;
        }
*/
        return true;    
    } else {
        set_error("MOHON MAAF, DATA KODING KENDARAAN ANDA BELUM TERDATA!", -3);
        return false;
    }

    return true;    
}

function gettrfnj($kd_merek_kb, $thn){
    global $dbonl;

    $sql = "SELECT * FROM t_trf_nj
                WHERE kd_merek_kb = '$kd_merek_kb'
                  AND thn = $thn";
    $trfnj = $dbonl->getrow($sql);
    if(!$trfnj){
        set_error("MOHON MAAF, TARIF NILAI JUAL KENDARAAN ANDA BELUM TERDATA!", -4);
        return false;
    }
    
    return $trfnj;
}

/*
    hitung swdkllj
*/
function hitswd(&$datakb){
    
    switch($datakb['kd_jenis_kb']){
        case 'A':
            $kd_trf_swd = "DP";
            if($datakb['kd_kel_kb'] == 'U'){
                $kd_trf_swd = ($datakb['jumlah_cc'] <= 1600) ? "DU" : "EU";
            }
            break;
            
        case 'B':
            $kd_trf_swd = "DP";
            break;
            
        case 'C':
            $kd_trf_swd = "DP";
            if($datakb['kd_kel_kb'] == 'U'){
                $kd_trf_swd = ($datakb['jumlah_cc'] <= 1600) ? "DU" : "EU";
            }        
            break;
            
        case 'D':
	    if($datakb['kd_kel_kb'] == "U") $kd_trf_swd = "EU";
	    else
                $kd_trf_swd = "EP";
            break;
            
        case 'E':
	    if($datakb['kd_kel_kb'] == "U") $kd_trf_swd = "EU";
	    else
                $kd_trf_swd = "EP";
            break;
            
        case 'F':
            $kd_trf_swd = ($datakb['jumlah_cc'] <= 2400) ? "DP" : "F";
            break;
            
        case 'G':
        case 'H':
            $kd_trf_swd = "F";
            break;
            
        case 'R':
            $kd_trf_swd = 'C1';
            if($datakb['jumlah_cc'] < 50) $kd_trf_swd = 'A';
            elseif($datakb['jumlah_cc'] > 250) $kd_trf_swd = 'C2';
            break;
    }
    
    if($datakb['kd_jenis_kb'] != 'R'){
        if(ereg('0[678]', $datakb['kd_fungsi'])) $kd_trf_swd = 'A';
    }
    
    $tg_daftar = date('d/m/Y');
    tarif_swd($tg_daftar, $kd_trf_swd, 12);

    $tg_daftar   = date('d/m/Y');
    $tg_akhir_jr = $datakb['tg_akhir_jr'];
    
    $d_tg_daftar   = to_date($tg_daftar);
    $d_tg_akhir_jr = to_date($tg_akhir_jr);
    
/*
    if($datakb['kd_kel_kb'] == 'D'){
        if($d_tg_akhir_jr > $d_tg_daftar){
            $sel_tgl = selisih_tgl($tg_daftar, $tg_akhir_jr);
            if($sel_tgl['n'] > 90){
		set_error("MOHON MAAF, KENDARAAN ANDA BELUM SAATNYA DAFTAR ULANG!", -1);
		return 0;
            }
        } 
    }    
*/
    
    $swd_pok[0] = 0;
    $swd_den[0] = 0;
    
    $terlambat = false;
    if($d_tg_akhir_jr < $d_tg_daftar){
        $terlambat = true;                
    }
    
    $sel_tgl = selisih_tgl($tg_akhir_jr, $tg_daftar);

/*
    if($datakb['kd_kel_kb'] == 'D'){
        if(!$datakb['jt_berubah']){
            list($d, $m, $y) = split_date($tg_akhir_jr);
            $tg_akhir_jr_yad = date('d/m/Y', mktime(0, 0, 0, $m, $d, $y+$sel_tgl['y']+1));
            $sel_tgl2 = selisih_tgl($tg_daftar, $tg_akhir_jr_yad);            
            if($sel_tgl2['n'] <= 60) $datakb['byr_dimuka'] = true;
        }
    }
*/
        
    $daluarsa = false;

    // sudah terlambat
    if($sel_tgl['n'] > 0){
                
        // $d_tgl = mktime(0, 0, 0, 1, 1, $y+$sel_tgl['y']);
        $d_tg_daluarsa = mktime(0, 0, 0, 1, 1, date('Y')-4);
        
        // sudah daluarsa
        if($d_tg_akhir_jr < $d_tg_daluarsa){

	    $daluarsa = true;

	    list($d, $m, $y) = split_date($tg_akhir_jr);
	    if($datakb['jt_berubah'])
	        list($d, $m, $y) = split_date($tg_daftar);

	    $y = date('Y', $d_tg_daluarsa);

	    // hitung tunggakannya
	    $k = -1;
	    for($i = 4;$i>0; $i--){
		 $k++;
                 $tgl = date('d/m/Y', mktime(0, 0, 0, $m, $d, $y+$k));
                 $trfswd = tarif_swd($tgl, $kd_trf_swd, 12);
                 $swd_pok[$i] = $trfswd['prorata_12'];
                 $swd_den[$i] = $swd_pok[$i] - $trfswd['krt_swd'];
	    }

        } else {
            // belum daluarsa
            
            list($d, $m, $y) = split_date($tg_akhir_jr);
        
            // jatuh tempo berubah
            if($datakb['jt_berubah']){
                // tunggakannya
                $k = -1;
                for($i=$sel_tgl['y']; $i>0; $i--){            
                    $k++;
                    if($i < 5){
                        $tgl = date('d/m/Y', mktime(0, 0, 0, $m, $d, $y+$k));
                        $trfswd = tarif_swd($tgl, $kd_trf_swd, 12);
                        $swd_pok[$i] = $trfswd['prorata_12'];
                        $swd_den[$i] = $swd_pok[$i] - $trfswd['krt_swd'];
                    }
                }
            
                // prorata s/d tgl. pendaftaran
                list($d, $m, $y) = split_date($tg_daftar);
                            
                // tarif prorata
                $trfswd = tarif_swd($tg_daftar, $kd_trf_swd, $m);
                $s = (strlen(m) == 1) ? "0$m" : $m;
                
                // proratanya
                $swd_pok[0] = $trfswd["prorata_$s"];                
                $swd_den[0] = 0;
                
            } else {
                // jatuh tempo tidak berubah
                
                // kalo bayar dimuka sekaligus, jumlah thn. tgk + 1
                $n = ($datakb['byr_dimuka']) ? 1 : 0;
                
                // tunggakannya
                $k = -1;
                for($i=$sel_tgl['y']+$n; $i>0; $i--){            
                    $k++;
                    if($i < 5){
                        $tgl = date('d/m/Y', mktime(0, 0, 0, $m, $d, $y+$k));
                        $trfswd = tarif_swd($tgl, $kd_trf_swd, 12);
                        $swd_pok[$i] = $trfswd['prorata_12'];
                        $swd_den[$i] = $swd_pok[$i] - $trfswd['krt_swd'];
                    }
                }                
            }
            // belum daluarsa
        }
        
        list($d, $m, $y) = split_date($tg_akhir_jr);
        $tg_pre_jr = date('d/m/Y', mktime(0, 0, 0, $m, $d, 
                                                   $y+$sel_tgl['y']));
        if($datakb['jt_berubah']){
            // dihitung dari tgl. pendaftaran
            $trfswd = tarif_swd($tg_daftar, $kd_trf_swd, 12);
        } else {

            if($datakb['byr_dimuka']){
                $trfswd = tarif_swd($tg_daftar, $kd_trf_swd, 12);
                $tg_pre_jr = date('d/m/Y', mktime(0, 0, 0, $m, $d, 
                                                   $y+$sel_tgl['y']+1));
            } else {
                $trfswd = tarif_swd($tg_pre_jr, $kd_trf_swd, 12);
            }
        }
        // pokok tahun berjalan (nilainya ditambah dengan proratanya)
        $swd_pok[0] += $trfswd['prorata_12'];
        
        // denda tahun berjalan
        // $swd_den[0] = $trfswd['prorata_12'] - $trfswd['krt_swd'];
        $swd_den[0] = hit_den_swd($tg_daftar, $tg_pre_jr, 
				$trfswd['prorata_12'] - $trfswd['krt_swd']);
	
        if($datakb['byr_dimuka']) $swd_den[0] = 0;
        
    } else {
        // belum terlambat
        
        // kalo jatuh temponya berubah?
        if($datakb['jt_berubah']){
            // set tgl. jt jr yad.
            list($d, $m, $y) = split_date($tg_daftar);
            $tg_akhir_jr_yad = date('d/m/Y', mktime(0, 0, 0, $m, $d, $y+1));
            
            // hitung selisihnya dgn. tgl. akhir jr yl.
            $sel_tgl = selisih_tgl($tg_akhir_jr, $tg_akhir_jr_yad);
            
            // jumlah bulan pengenaan prorata
            $m = $sel_tgl['m'];
            if($sel_tgl['d'] > 0) $m++;
            
            // tarif swdkllj
            $trfswd = tarif_swd($tg_daftar, $kd_trf_swd, $m);
            $s = (strlen($m) == 1) ? "0$m" : $m;
            
            // swdkllj yang harus dibayar!
            $swd_pok[0] = $trfswd["prorata_$s"];
            $swd_den[0] = 0;
        } else {
            // jatuh tempo tidak berubah!
            
            // tarif swdkllj
            $trfswd = tarif_swd($tg_daftar, $kd_trf_swd, 12);            
            
            // swdkllj yang harus dibayar!
            $swd_pok[0] = $trfswd['prorata_12'];
            $swd_den[0] = 0;
        }
    }

    // kalo ada pemutihan
    if($datakb['pemutihan'] == "Y"){

	// sebelum pemutihan
        $pok = 0;
        foreach($swd_pok as &$value){
            // $value = pembulatan($value);
            $pok += $value;
        }
    
        $den = 0;
        foreach($swd_den as &$value){
            // $value = pembulatan($value);
            if($value > 100000) $value = 100000;
            $den += $value;
        }

        $tot = $pok + $den;

        $datakb['pok_swd_awal'] = $pok;
        $datakb['den_swd_awal'] = $den;
        $datakb['tot_swd_awal'] = $tot;

	$set_pp = $datakb['set_pp'];

        // pemutihan pokok swdkllj
        switch($set_pp['pokok_swdkllj']){
	    case "0":
	        $swd_pok[0] = 0;
	        $swd_pok[1] = 0;
	        $swd_pok[2] = 0;
	        $swd_pok[3] = 0;
	        $swd_pok[4] = 0;
		break;

	    case "1":
	        $swd_pok[1] = 0;
	        $swd_pok[2] = 0;
	        $swd_pok[3] = 0;
	        $swd_pok[4] = 0;
		break;

	    case "2":
	        $swd_pok[2] = 0;
	        $swd_pok[3] = 0;
	        $swd_pok[4] = 0;
		break;

	    case "3":
	        $swd_pok[3] = 0;
	        $swd_pok[4] = 0;
		break;

	    case "4":
	        $swd_pok[4] = 0;
		break;
	}

        // pemutihan denda swdkllj
        switch($set_pp['denda_swdkllj']){
	    case "0":
	        $swd_den[0] = 0;
	        $swd_den[1] = 0;
	        $swd_den[2] = 0;
	        $swd_den[3] = 0;
	        $swd_den[4] = 0;
	        $swd_den[5] = 0;
		break;

	    case "1":
	        $swd_den[1] = 0;
	        $swd_den[2] = 0;
	        $swd_den[3] = 0;
	        $swd_den[4] = 0;
		break;

	    case "2":
	        $swd_den[2] = 0;
	        $swd_den[3] = 0;
	        $swd_den[4] = 0;
		break;

	    case "3":
	        $swd_den[3] = 0;
	        $swd_den[4] = 0;
		break;

	    case "4":
	        $swd_den[4] = 0;
		break;
	}
    }

    $datakb['swd_pok'] = $swd_pok;
    $datakb['swd_den'] = $swd_den;

    $pok = 0;
    foreach($swd_pok as &$value){
        // $value = pembulatan($value);
        $pok += $value;
    }
    
    $den = 0;
    foreach($swd_den as &$value){
        // $value = pembulatan($value);
        if($value > 100000) $value = 100000;
        $den += $value;
    }
    $tot = $pok + $den;

    $datakb['pok_swd_akhir'] = $pok;
    $datakb['den_swd_akhir'] = $den;
    $datakb['tot_swd_akhir'] = $tot;

    if($datakb['pemutihan'] == 'Y'){
        $datakb['jml_pp_swd'] = $datakb['tot_swd_awal'] - 
                                    $datakb['tot_swd_akhir'];
    }

    $datakb['pokok_swd'] = number_format($pok, 0, ",", ".");
    $datakb['denda_swd'] = number_format($den, 0, ",", ".");
    $datakb['total_swd'] = number_format($tot, 0, ",", ".");

    if($datakb['jt_berubah']){
        list($d, $m, $y) = split_date($tg_daftar);
        $datakb['tg_akhir_jr_yad'] = date('d/m/Y', mktime(0, 0, 0, $d, $m, $y+1));     
    } else {
        $n = ($datakb['byr_dimuka']) ? 2 : 1;
        list($d, $m, $y) = split_date($tg_akhir_jr);
        $datakb['tg_akhir_jr_yad'] = date('d/m/Y', mktime(0, 0, 0, $m, $d, $y+$sel_tgl['y']+$n));
    }        

    return $tot;
}

function tarif_swd($tgl, $kd_trf_swd, $bln){
    global $dbonl;

    $tgl = to_dbdate($tgl);
    
    if(strlen($bln) == 1) $bln = "0$bln";
    $data = "prorata_" . $bln;
    $sql = "SELECT $data, krt_swd FROM t_trf_swd
                WHERE tg_dari <= '$tgl' AND tg_sampai >= '$tgl'
                  AND kd_trf_swd = '$kd_trf_swd'";

    $row = $dbonl->getrow($sql);
    if($row[$data] == $row['krt_swd']) $row['krt_swd'] = 0;
    return $row;

    if(strlen($bln) == 1) $bln = "0$bln";
    $data = "prorata_" . $bln;
    
    $query = "SELECT $data, krt_swd FROM t_trf_swd
                 WHERE tg_dari <= '$tgl' AND tg_sampai >= '$tgl'
                   AND kd_trf_swd = '$kd_trf_swd'";
    $row = $dbonl->getrow($query);
    if($row[$data] == $row['krt_swd']) $row['krt_swd'] = 0;    
}

function hit_den_swd($tg_tetap, $tg_akhir, $trf_swd){
   
    $d_tg_tetap = to_date($tg_tetap);
    $d_tg_akhir = to_date($tg_akhir);

    $sel_tgl = selisih_tgl($tg_akhir, $tg_tetap);
    $n = $sel_tgl['n'];

    $pct = 0;

    if($n > 270){
	$pct = 100;

    } elseif ($n > 180){
	$pct = 75;

    } elseif ($n >  90){
	$pct = 50;

    } else {
	if($n > 0) $pct = 25;
    }

    $denda = ($pct / 100) * $trf_swd;
    if($denda > 100000) $denda = 100000;

    return $denda;
}

/*
  hitung pnbp tnkb
*/
function hittnkb($datakb){
    global $dbonl;

    $tg_daftar = date('d/m/Y');
    $tg_akhir_stnk = $datakb['tg_akhir_stnk'];
 
    $d_tg_daftar     = to_date($tg_daftar);
    $d_tg_akhir_stnk = to_date($tg_akhir_stnk);

    $pnbp_tnkb = 0;

    # stnk sudah habis
    if($d_tg_akhir_stnk <= $d_tg_daftar || 
       // atau akan habis pada thn. ybs
       year($tg_akhir_stnk) == year($tg_daftar)){
	if($datakb['kd_jenis_kb'] == "R"){
	    $pnbp_tnkb = p_param("BEA-PLAT-R2", 60000);
	} else {
	    $pnbp_tnkb = p_param("BEA-PLAT-R4", 100000);
	}
    }

    return $pnbp_tnkb;
}

/*
   hitung pnbp stnk
*/
function hitstnk($datakb){
    global $dbonl;

    $tg_daftar = date('d/m/Y');
    $tg_akhir_pkb  = $datakb['tg_akhir_pkb'];
    $tg_akhir_stnk = $datakb['tg_akhir_stnk'];

    $d_tg_daftar     = to_date($tg_daftar);
    $d_tg_akhir_pkb  = to_date($tg_akhir_pkb );
    $d_tg_akhir_stnk = to_date($tg_akhir_stnk);

    $d_tg_kena_pnbp  = mktime(0, 0, 0, 1, 6, 2016);
    $tg_kena_pnbp    = date('d/m/Y', $d_tg_kena_pnbp);

    if($datakb['kd_jenis_kb'] == "R"){
	$pnbp_sah_stnk = p_param("BEA-SAH-STNK-R2", 0);
	$pnbp_ctk_stnk = p_param("BEA-STNK-R2", 100000);
    } else {
	$pnbp_sah_stnk = p_param("BEA-SAH-STNK-R4", 0);
	$pnbp_ctk_stnk = p_param("BEA-STNK-R4", 200000);
    }

    // Case 1: STNK belum mati
    if($d_tg_akhir_stnk > $d_tg_daftar){

	// belum terlambat
        if($d_tg_akhir_pkb >= $d_tg_daftar){

	    $pnbp_stnk = $pnbp_sah_stnk;

	    # harus cetak stnk
            $sel_tgl = selisih_tgl($tg_daftar, $tg_akhir_stnk);
	    if($sel_tgl['n'] <= 90){
		$pnbp_stnk = $pnbp_ctk_stnk;
	    }

	} else {

	    // sudah terlambat
	    while(to_date($tg_akhir_pkb) < $d_tg_kena_pnbp){
		$tg_akhir_pkb = addyear($tg_akhir_pkb, 1);
	    }

	    $sel_tgl = selisih_tgl($tg_akhir_pkb, $tg_daftar);

	    $y = $sel_tgl['y'];
	    if($sel_tgl['m'] > 0 || $sel_tgl['d'] > 0){
		$y++;
	    }

	    if($datakb['byr_dimuka']){
		if($datakb['ctk_stnk'] != "1"){
		    $y++;
	        }
	    }
	    
	    $pnbp_stnk = $y * $pnbp_sah_stnk;

	    // harus cetak stnk
            if($datakb['ctk_stnk'] == '1'){
		$pnbp_stnk += $pnbp_ctk_stnk;
	    }
	}
    } else {

	// Case 2: STNK sudah mati

	while(true){
	    $tg_akhir_yad = addyear($tg_akhir_stnk, 5);
	    if(to_date($tg_akhir_yad) > $d_tg_daftar) break;
	    else
		$tg_akhir_stnk = $tg_akhir_yad;
	}

	// kena perpanjangan
	$pnbp_stnk = $pnbp_ctk_stnk;

	$tg_akhir_stnk = addyear($tg_akhir_stnk, 1);

	$d = day($tg_akhir_pkb);
	$m = month($tg_akhir_pkb);
	$y = year($tg_akhir_stnk);

	$n = max_hari($m, $y);
	if($d > $n){
	    $d = 1;
	    $m++;
	}

	$tg_akhir_pkb = date('d/m/Y', mktime(0, 0, 0, $m, $d, $y));

	while(to_date($tg_akhir_pkb) < $d_tg_kena_pnbp){
	    $tg_akhir_pkb = addyear($tg_akhir_pkb, 1);
	}

	$sel_tgl = selisih_tgl($tg_akhir_pkb, $tg_daftar);

	$y = $sel_tgl['y'];
	if($sel_tgl['m'] > 0 || $sel_tgl['d'] > 0){
	    $y++;
	}
	
        if($datakb['byr_dimuka']) $y++;

	$pnbp_stnk += $pnbp_sah_stnk * $y;
    }
 
    return $pnbp_stnk;
}


function set_error($msg, $no){
    global $error, $errmsg, $errno;

    $error  = true;
    $errmsg = $msg;
    $errno  = $no;
}

function pembulatan($n){
    $m = $n;
    if($n > 0){
        $m = round($n / 100) * 100;
        if($m < $n) $m += 100;
    }
    return $m;
}

function split_date($tgl){
    list($d, $m, $y) = preg_split('/[-\/]/',$tgl);
    if(checkdate($m, $d, $y)) return array($d, $m, $y);
    else
	return array(0, 0, 0);
}

/*
function to_dbdate($tgl){
    list($d, $m, $y) = preg_split('/[-\/]/',$tgl);
    if(checkdate($m, $d, $y)) return "$y-$m-$d";
    else
	return "0000-00-00";
}
*/

/***
   selisih_tgl(<tgl1>, <tgl2>)
   menghitung selisih tgl. <tgl2> - <tgl1>
*/
function selisih_tgl($s1, $s2){

    $tgl1 = to_date($s1);
    $tgl2 = to_date($s2);

    // not a date?
    if(!$tgl1 || !$tgl2) return array('d' => 0, 'm' => 0, 'y' => 0, 'n' => 0);
    if($tgl2 < $tgl1) return array('d' => 0, 'm' => 0, 'y' => 0, 'n' => 0);

    $s1 = to_dbdate($s1);
    $s2 = to_dbdate($s2);

    $datetime1 = new DateTime($s1);
    $datetime2 = new DateTime($s2);
    
    $diff = $datetime2->diff($datetime1);
    
    return array('d' => $diff->d, 'm' => $diff->m, 'y' => $diff->y, 'n' => $diff->days);

}

function to_date($s){
    list($d, $m, $y) = preg_split('/[-\/]/',$s);
    if(checkdate($m, $d, $y)) return mktime(0, 0, 0, $m, $d, $y);
    else
	return false;
}

function addyear($s, $n){
    list($d, $m, $y) = preg_split('/[-\/]/',$s);
    if(checkdate($m, $d, $y)) 
	return date('d/m/Y', mktime(0, 0, 0, $m, $d, $y+$n));
    else
	return false;
}

function addmonth($s, $n){
    list($d, $m, $y) = preg_split('/[-\/]/',$s);
    if(checkdate($m, $d, $y)) 
	return date('d/m/Y', mktime(0, 0, 0, $m+$n, $d, $y));
    else
        return false;
}

function year($s){
    list($d, $m, $y) = preg_split('/[-\/]/',$s);
    if(checkdate($m, $d, $y)) return $y;
    else
	return false;
}

function month($s){
    list($d, $m, $y) = preg_split('/[-\/]/',$s);
    if(checkdate($m, $d, $y)) return $m;
    else
        return false;
}

function day($s){
    list($d, $m, $y) = preg_split('/[-\/]/',$s);
    if(checkdate($m, $d, $y)) return $d;
    else
        return false;
}

function max_hari($m, $y){
    if($m == 12) return 31;
    else
	return date('d', mktime(0, 0, 0, $m+1, 1, $y)-1);
}

function setmodel($nm_merek_kb, $nm_model_kb, $nm_jenis_kb){
    $s = strip_mrk($nm_merek_kb) . strip_mrk($nm_model_kb) . strip_mrk($nm_jenis_kb);
    return $s;
}

function strip_mrk($s){
    $s = strtoupper(trim($s));
    $s = str_replace(' ', '', $s);
    $s = ereg_replace('[[:punct:]]', '', $s);
    $s = str_replace('MANUAL', 'MT', $s);
    $s = str_replace('AUTOMATIC', 'AT', $s);
    $s = str_replace('CC', '', $s);
    return $s;
}
?>
<!DOCTYPE html>
<html lang="en" class="no-js">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <title>INFORMASI j-SAMSAT</title>
        <meta name="description" content="INFORMASI j-SAMSAT" />
        <meta name="author" content="Dipenda Prov. Jambi" />
        <link href="bootstrap/css/bootstrap.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="css/default.css" />
        <link rel="stylesheet" type="text/css" href="css/component.css" />
        <link rel="stylesheet" type="text/css" href="css/btn.css" />
        <script src="assets/js/jquery.min.js"></script>
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <script>
                function set_kd_merek_kb(v){
                    document.getElementById("kd_merek_kb").value = v;
                    document.getElementById("kd_dipilih").innerHTML = "Kode Merek: " + v;
                }

                $(document).ready(function () {
		        $('#det_swd').hide();
                        $('#show_det_swd').click(function(){
                            $('#det_swd').slideToggle('slow');
                            if($(this).text() == 'TUTUP RINCIAN SWDKLLJ')
                            {
                                $(this).text('LIHAT RINCIAN SWDKLLJ');
                            }
                            else
                            {
                                $(this).text('TUTUP RINCIAN SWDKLLJ');
                            }
                        });

                        $('#det_pkb').hide();
                        $('#show_det_pkb').click(function(){
                            $('#det_pkb').slideToggle('slow');
                            if($(this).text() == 'TUTUP RINCIAN PKB')
                            {
                                $(this).text('LIHAT RINCIAN PKB');
                            }
                            else
                            {
                                $(this).text('TUTUP RINCIAN PKB');
                            }
                        });

                        $('#form-content').on('hidden.bs.modal', function () {
                                $(this).find('form').trigger('reset');
                                $("#koding").html('');
                        });
                        $("#pilih").click(function(){
                                $("#form-content").modal('hide');
                                $("#contact").attr('action', "/infopkb.php").submit();
                        });
                        $("input#cari").click(function(){
                                $.ajax({
                                        type: "POST",
                                        url: "carikode.php", //
                                        data: $('form.contact').serialize(),
                                        success: function(msg){
                                                $("#koding").html(msg)
                                        },
                                        error: function(){
						alert("failure");
                                        }
                                });
                        });
                });
        </script>

	<?php
	    $f16 = "16px;";
            $f18 = "18px;";
            $f20 = "20px;";
            $f24 = "24px;";

            if ( $detect->isMobile() ) {
		$f16 = "12px;";
		$f18 = "14px;";
		$f20 = "16px;";
		$f24 = "20px;";
            }
	?>

	<style>
        .label {
           color: #47a3da;
           font-size: <?php echo $f16 ?>;
        }

        a.kodemerek {
           color: #47a3da;
           font-size: 16px;
        }

        input[type=text] {
	   text-transform: uppercase;
        }

	td {
           vertical-align: text-top;
	   font-size: <?php echo $f18 ?>;
	   padding-left: 8px;
	}

	.enh18 {
	   font-size: <?php echo $f18 ?>;
        }

	.enh20 {
	   font-size: <?php echo $f20 ?>;
	} 

	.enh24 {
	   font-size: <?php echo $f24 ?>;
	}

	</style>
    </head>
    <body class="cbp-spmenu-push">
        <div class="container">
            <a href="index.html" class="btn btn-default">Menu Utama</a>
            <header class="clearfix">
                <span>j-SAMSAT</span>
                <h1>INFORMASI SAMSAT JAMBI</h1>
            </header>
            <?php
		if(!$found){
		    echo "<h2>DATA TIDAK ADA</h2>";
		    exit;
		}
	    ?>

            <!-- Modal -->
            <div id="form-content" class="modal fade" role="dialog">
              <div class="modal-dialog">
            
                <!-- Modal content-->
                <div class="modal-content">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h3>Cari Kode Merek Kendaraan</h3>
	    <h4><?php echo $result['nm_merek_kb'] . " " . 
                           $result['nm_model_kb'] . " " . 
                           $result['nm_jenis_kb'] ?></h4>
        </div>

        <div class="modal-body">
	    <div style="height: 180px;">
            <form class="contact" name="contact" id="contact" method="post">
                <label class="label" for="nm_merek_kb">Merek</label><br>
                <input type="text" name="nm_merek_kb" class="input-xlarge"><br>
                <label class="label" for="nm_model_kb">Model/Tipe</label><br>
                <input type="text" name="nm_model_kb" class="input-xlarge"><br>
                <label class="label" for="nm_jenis_kb">Jenis</label><br>
                <input type="text" name="nm_jenis_kb" class="input-xlarge"><br>
	        <div id="kd_dipilih"></div>
                <input type="hidden" name="no_polisi" <?php echo "value=\"" . $no_polisi . "\""; ?> ><br>
                <input type="hidden" name="nm_pemilik" <?php echo "value=\"" . $nm_pemilik . "\""; ?> ><br>
                <input type="hidden" name="tg_akhir_pkb" <?php echo "value=\"" . $tg_akhir_pkb . "\""; ?> ><br>
                <input type="hidden" name="izin_ang" <?php echo "value=\"" . $izin_ang . "\""; ?> ><br>
                <input type="hidden" name="siup_ang" <?php echo "value=\"" . $siup_ang . "\""; ?> ><br>
                <input type="hidden" name="kir_ang" <?php echo "value=\"" . $kir_ang . "\""; ?> ><br>
                <input type="hidden" name="kd_merek_kb" id="kd_merek_kb" class="input-xlarge" <?php echo "value=" . $result['kd_merek_kb'] ?> ><br>
            </form>
	    </div>
            <input class="btn btn-success" type="submit" value="Cari" id="cari">
            <input class="btn btn-success" type="button" Value="Batal" data-dismiss="modal">
            <div id="koding"></div>                         
        </div>
        <div class="modal-footer">
            <input class="btn btn-success" type="button" value="Ok" id="pilih">
            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
        </div>

                </div>
              </div>
            </div>

            <div class="main" style="width: 98%; padding: 5px;">

	        <h2>DATA KENDARAAN <?php echo $no_polisi ?></h2>
		<table>
		  <!--  <tr>
			<td>NAMA PEMILIK</td>
			<td>:</td>
			<td class="enh24"><strong><?php echo $result['nm_pemilik']; ?></strong></td>
		    </tr>
		    <tr>
			<td>ALAMAT</td>
			<td>:</td>
			<td><?php echo $result['al_pemilik']; ?></td>
		    </tr>-->
		    <tr>
			<td>MEREK</td>
			<td>:</td>
			<td><?php echo $result['nm_merek_kb']; ?></td>
		    </tr>
		    <tr>
			<td>MODEL/TIPE</td>
			<td>:</td>
			<td><?php echo $result['nm_model_kb']; ?></td>
		    </tr>
		    <tr>
			<td>JENIS</td>
			<td>:</td>
			<td><?php echo $result['nm_jenis_kb']; ?></td>
		    </tr>
		    <tr>
			<td>TAHUN</td>
			<td>:</td>
			<td><?php echo $result['th_rakitan']; ?></td>
		    </tr>
		    <tr>
			<td>CC</td>
			<td>:</td>
			<td><?php echo $result['jumlah_cc'] . "cc"; ?></td>
		    </tr>
		    <tr>
			<td>WARNA</td>
			<td>:</td>
			<td><?php echo $result['warna_kb']; ?></td>
		    </tr>
		    <tr>
			<td>TGL. BAYAR yl.</td>
			<td>:</td>
			<td class="enh18"><strong><?php echo $result['tg_bayar']; ?></strong></td>
		    </tr>
		    <tr>
			<td>LOKASI BAYAR yl.</td>
			<td>:</td>
			<td class="enh18"><strong><?php echo $result['nm_lokasi']; ?></strong></td>
		    </tr>
		    <tr>
			<td>TGL. AKHIR PKB yl.</td>
			<td>:</td>
			<td class="enh24"><strong><?php echo $result['tg_akhir_pkb']; ?></strong></td>
		    </tr>
                    <tr>
		        <?php 
		            // kalo tgl. akhir stnk-nya gak ada
			    if(! to_date($datakb['tg_akhir_stnk']))
				$datakb['tg_akhir_stnk'] = "";
			?>
                        <td>TGL. AKHIR STNK</td>
                        <td>:</td>
                        <td class="enh24"><strong><?php echo $datakb['tg_akhir_stnk']; ?></strong></td>
                    </tr>
		    <?php
			if($error){
			    echo "</table>";
			    echo "<h3>$errmsg</h3>";
			    if($errno == -3){
                                echo "<button type=\"button\" class=\"btn btn-info btn-lg\" data-toggle=\"modal\" data-target=\"#form-content\">Cari Kode</button>";
			    }
			    exit;
		        }
		    ?>

		    <?php
			echo "<tr>
			          <td>TARIF DASAR</td>
			          <td>:</td>
			          <td>" . str_replace(".", ",", 
						$datakb['pct_trf']) . "% x " .
                                          $datakb['njkb'] . " x " .
                                          $datakb['pct_pkb'] . "%</td>
			      </tr>";

			if($datakb['progresif']){
			    echo "<tr>
			              <td>PROGRESIF</td>
			              <td>:</td>
			              <td>" . $datakb['progresif'] . "</td>
			          </tr>";
			}
		    ?>
		    <tr>
			<td>PKB</td>
			<td>:</td>
			<td class="enh20"><strong><?php echo "Rp$pkb,-"; ?></strong></td>
		    </tr>
		    <tr>
			<td>SWDKLJ</td>
			<td>:</td>
			<td class="enh20"><strong><?php echo "Rp$swd,-"; ?></strong></td>
		    </tr>
                    <tr>
                        <td>PNBP STNK</td>
                        <td>:</td>
                        <td class="enh20"><strong><?php echo "Rp$stnk,-"; ?></strong></td>
                    </tr>
                    <tr>
                        <td>PNBP TNKB</td>
                        <td>:</td>
                        <td class="enh20"><strong><?php echo "Rp$tnkb,-"; ?></strong></td>
                    </tr>

		    <?php
			if($datakb['pemutihan'] == "Y"){
			    $awal = $datakb['tot_pkb_awal'] +
				           $datakb['tot_swd_awal'];
			    $awal = number_format($awal, 0, ",", ".");
		    ?>

                    <tr>
                        <td>TOTAL</td>
                        <td>:</td>
                        <td class="enh18"><strong>
                            <?php
                                echo "Rp$awal,- (SEBELUM PEMUTIHAN)";
                            ?>
                        </strong></td>
                    </tr>


		    <?php
			}
		    ?>
		    <tr>
			<td>TOTAL</td>
			<td>:</td>
			<td class="enh24"><strong>
                            <?php 
                                echo "Rp$tot,-"; 
				if($datakb['pemutihan'] == "Y"){
				    echo " <span class=\"enh18\">(SESUDAH PEMUTIHAN)</span>";
				}
                            ?>
                        </strong></td>
		    </tr>

		    <?php
			if($datakb['pemutihan'] == "Y"){
			    $tot = $datakb['jml_pp_pkb'] + 
					$datakb['jml_pp_swd'];
			    $pkb = number_format($datakb['jml_pp_pkb'], 0, ",", ".");
			    $swd = number_format($datakb['jml_pp_swd'], 0, ",", ".");
			    $tot = number_format($tot, 0, ",", ".");
		    ?>

                    <tr>
                        <td>PEMUTIHAN</td>
                        <td>:</td>
                        <td class="enh20"><strong><?php echo "Rp$tot,-"; ?></strong></td>
                    </tr>

                    <tr>
                        <td>PKB</td>
                        <td>:</td>
                        <td class="enh18"><strong><?php echo "Rp$pkb,-"; ?></strong></td>
                    </tr>

                    <tr>
                        <td>SWDKLLJ</td>
                        <td>:</td>
                        <td class="enh18"><strong><?php echo "Rp$swd,-"; ?></strong></td>
                    </tr>

		    <?php
		        }
		    ?>
		    <tr>
			<td>TGL. AKHIR PKB yad.</td>
			<td>:</td>
			<td class="enh24"><strong><?php echo $tg_akhir_yad; ?></strong></td>
		    </tr>
		</table>


	    <h4>RINCIAN:</h4>
            <div class="row">
                <div class="col-md-2 col-sm-2 col-xs-3">
                    <p class="text-center" style="font-weight: bold; font-size: 12px;">POKOK</p> 
                </div>
                <div class="col-md-2 col-sm-2 col-xs-3">
                    <p class="text-center" style="font-weight: bold; font-size: 12px;">DENDA</p> 
                </div>
                <div class="col-md-2 col-sm-2 col-xs-3">
                    <p class="text-center" style="font-weight: bold; font-size: 12px;">TOTAL</p> 
                </div>
                <div class="col-md-2 col-sm-2 col-xs-3">
                    <p class="text-center"></p> 
                </div>
            </div>
		<?php
		    $style = "font-size: 14px;";
                    if ( $detect->isMobile() ) {
		        $style = "font-size: 12px;";
                    }
		    $style = "style=\"$style\"";
		?>
            <div class="row">
                <div class="col-md-2 col-sm-2 col-xs-3">
                    <p class="text-right" <?php echo $style ?>><?php echo $datakb['pokok_pkb'] ?></p> 
                </div>
                <div class="col-md-2 col-sm-2 col-xs-3">
                    <p class="text-right" <?php echo $style ?>><?php echo $datakb['denda_pkb'] ?></p> 
                </div>
                <div class="col-md-2 col-sm-2 col-xs-3">
                    <p class="text-right" <?php echo $style ?>><?php echo $datakb['total_pkb'] ?></p> 
                </div>
                <div class="col-md-2 col-sm-2 col-xs-3">
                    <p style="font-weight: bold; font-size: 14px; padding-left: 14px;" class="text-left">PKB</p> 
                </div>
            </div>
            <div class="row">
                <div class="col-md-2 col-sm-2 col-xs-3">
                    <p class="text-right" <?php echo $style ?>><?php echo $datakb['pokok_swd'] ?></p> 
                </div>
                <div class="col-md-2 col-sm-2 col-xs-3">
                    <p class="text-right" <?php echo $style ?>><?php echo $datakb['denda_swd'] ?></p> 
                </div>
                <div class="col-md-2 col-sm-2 col-xs-3">
                    <p class="text-right" <?php echo $style ?>><?php echo $datakb['total_swd'] ?></p> 
                </div>
                <div class="col-md-2 col-sm-2 col-xs-3">
                    <p style="font-weight: bold; font-size: 14px; padding-left: 14px;" class="text-left">SWDKLLJ</p> 
                </div>
            </div>

            <div class="row">
		<button id="show_det_pkb" class="cssbtn" style="width:170px">LIHAT RINCIAN PKB</button>
	    </div>
	    <div id="det_pkb">
	        <h4>RINCIAN PKB:</h4>
                <div class="row">
                    <div class="col-md-2 col-sm-2 col-xs-3">
                        <p class="text-center" style="font-weight: bold; font-size: 12px;">POKOK</p> 
                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-3">
                        <p class="text-center" style="font-weight: bold; font-size: 12px;">DENDA</p> 
                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-3">
                        <p class="text-center" style="font-weight: bold; font-size: 12px;">TOTAL</p> 
                    </div>
                </div>
		    <?php
		        $style = "font-size: 14px;";
                        if ( $detect->isMobile() ) {
		            $style = "font-size: 12px;";
                        }
		        $style = "style=\"$style\"";
		    ?>
	        <?php
		    $pkb_pok = $datakb['pkb_pok'];
		    $pkb_den = $datakb['pkb_den'];
    
		    for($i=0;$i<6;$i++){
                        $pok = number_format($pkb_pok[$i], 0, ",", ".");
                        $den = number_format($pkb_den[$i], 0, ",", ".");
		        $tot = $pkb_pok[$i] + $pkb_den[$i];
                        $tot = number_format($tot, 0, ",", ".");
	        ?>
                <div class="row">
                    <div class="col-md-2 col-sm-2 col-xs-3">
                        <p class="text-right" <?php echo $style ?>><?php echo $pok ?></p> 
                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-3">
                        <p class="text-right" <?php echo $style ?>><?php echo $den ?></p> 
                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-3">
                        <p class="text-right" <?php echo $style ?>><?php echo $tot ?></p> 
                    </div>
                </div>
	        <?php } ?>
	    </div>

            <div class="row">
		<button id="show_det_swd" class="cssbtn" style="width:170px">LIHAT RINCIAN SWDKLLJ</button>
	    </div>
	    <div id="det_swd">
	        <h4>RINCIAN SWDKLLJ:</h4>
                <div class="row">
                    <div class="col-md-2 col-sm-2 col-xs-3">
                        <p class="text-center" style="font-weight: bold; font-size: 12px;">POKOK</p> 
                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-3">
                        <p class="text-center" style="font-weight: bold; font-size: 12px;">DENDA</p> 
                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-3">
                        <p class="text-center" style="font-weight: bold; font-size: 12px;">TOTAL</p> 
                    </div>
                </div>
		    <?php
		        $style = "font-size: 14px;";
                        if ( $detect->isMobile() ) {
		            $style = "font-size: 12px;";
                        }
		        $style = "style=\"$style\"";
		    ?>
	        <?php
		    $swd_pok = $datakb['swd_pok'];
		    $swd_den = $datakb['swd_den'];
    
		    for($i=0;$i<5;$i++){
                        $pok = number_format($swd_pok[$i], 0, ",", ".");
                        $den = number_format($swd_den[$i], 0, ",", ".");
		        $tot = $swd_pok[$i] + $swd_den[$i];
                        $tot = number_format($tot, 0, ",", ".");
	        ?>
                <div class="row">
                    <div class="col-md-2 col-sm-2 col-xs-3">
                        <p class="text-right" <?php echo $style ?>><?php echo $pok ?></p> 
                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-3">
                        <p class="text-right" <?php echo $style ?>><?php echo $den ?></p> 
                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-3">
                        <p class="text-right" <?php echo $style ?>><?php echo $tot ?></p> 
                    </div>
                </div>
	        <?php } ?>
	    </div>

		<p><strong>Catatan:</strong></p>
		<ul>
		   <li> Jika ada selisih/perbedaan perhitungan, maka yang digunakan adalah hasil perhitungan petugas <b>SAMSAT</b>.</li>
		</ul>
            </div>
        </div>
        <!-- Bootstrap core JavaScript
    ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <script src="assets/js/ie10-viewport-bug-workaround.js"></script>
    </body>
</html>
