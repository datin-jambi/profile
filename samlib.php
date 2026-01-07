<?php
include_once("pgdbtool.php");
include_once("cipher.php");

date_default_timezone_set('Asia/Jakarta');

$dbonl = new pgDBTool();
$dbonl->connect();

function get_http_arg(
$arg, 
$default=NULL, 
$exit_if_notset=true, 
$exit_if_empty=true
){
    $val = "";
    if(isset($_POST[$arg])) $val = $_POST[$arg];
    elseif(isset($_GET[$arg])) $val = $_GET[$arg];
    else {
	if($exit_if_notset) ret_err($arg . " not set!");
    }
	
    $val = trim($val);
    if($val == ""){
	if(!is_null($default)) $val = $default;
        $val = trim($val);
    }
    if($val == ""){
	if($exit_if_empty) ret_err($arg . " is empty!");
    }
    return trim($val);
}

function p_param($kd_data, $default=NULL){
    return get_param("SISTEM", $kd_data, "nilai", $default);
}

function p_param2($kd_param, $kd_data, $default=NULL){
    return get_param($kd_param, $kd_data, "nilai", $default);
}

function p_param_data($kd_param, $kd_data, $default=NULL){
    return get_param($kd_param, $kd_data, "param_data", $default);
}

function get_param($kd_param, $kd_data, $field, $default=NULL){
    global $dbonl;

    $query = "SELECT $field FROM t_param
		WHERE kd_param = '$kd_param'
		  AND kd_data  = '$kd_data'";

    $value = $dbonl->getvalue($query);

    if((is_null($value) || $dbonl->status = NOTFOUND) && !is_null($default)) 
        $value = $default;
    
    return $value;
}

function nama_wilayah($kd_wilayah){
   return nama_kode($kd_wilayah, "t_wilayah", "kd_wilayah", "nm_wilayah");
}

function nama_upt($kd_upt){
   return nama_kode($kd_upt, "t_nm_upt", "kd_upt", "nm_upt");
}

function nama_lokasi($kd_lokasi){
   return nama_kode($kd_lokasi, "t_nm_lokasi", "kd_lokasi", "nm_lokasi");
}

function nama_bbm($kd_bbm){
   return nama_kode($kd_bbm, "t_bbm", "kd_bbm", "nm_bbm"); 
}

function nama_kode($kode, $tabel, $field_kode, $field_nama){
    global $dbonl;

    $nama = "";
    $query = "SELECT $field_nama FROM $tabel
		WHERE $field_kode = '$kode'";

    $nama = $dbonl->getvalue($query);
    if(is_null($nama)) $nama = "";
    return $nama; 
}

function to_dbdate($tgl){
    if($tgl){
        list($d, $m, $y) = explode("/", $tgl);
        $tgl = "$y-$m-$d";
    }
    return $tgl;
}

function dttime($tgl, $dbfmt=true){
    if($tgl){
	if($dbfmt) list($y, $m, $d) = explode("-", $tgl);
	else
	    list($d, $m, $y) = explode("/", $tgl);
	$tgl = mktime(0, 0, 0, $m, $d, $y);
    }
    return $tgl;
}

function ret_err($str=""){
    echo ":err>$str";
    exit;
}

function ret_true($str=""){
    echo ":200>$str";
    exit;
}

function ret_false($str=""){
    echo ":201>$str";
    exit;
}

function setnopol($s){
    $s = str_replace(' ', '', $s);
    $s = preg_replace('/[[:punct:]]/', '', $s);
    $s = trim(strtoupper($s));
    $str = '';
    $n = strlen($s);
    $t = '';
    for($i = 0; $i < $n; $i++){
        $c = substr($s, $i, 1);
        if(preg_match('/[A-Z]/', $c)){
            if($t == 'N') $str .= ' ';
            $t = 'C';			
        } else {
            if($t == 'C') $str .= ' ';
            $t = 'N';
        }
        $str .= $c;
    }
    if(substr($str, 0, 2) != 'BH') $str = 'BH ' . trim($str);
    return $str;
}

function nama_bulan($m, $fmt="upper"){
   $bln = (int) $m;

   $nama = array( 1 => "Januari", "Februari", "Maret", "April", 
                       "Mei", "Juni", "Juli", "Agustus", 
                       "September", "Oktober", "November", "Desember" );

   $nm_bln = "";

   if($m > 0 && $m < 13){
       $nm_bln = $nama[$bln];
       switch($fmt){
           case "upper":
	      $nm_bln = strtoupper($nm_bln);
	      break;

	   case "lower":
	      $nm_bln = strtolower($nm_bln);
	      break;

	   case "ucfirst":
	      $nm_bln = ucfirst(strtolower($nm_bln));
	      break;
       }
   }

   return $nm_bln;
}

/*
   set date from a variable with format d, dd, ddmm, ddmmyy, ddmmyyyy, and
   dd/mm/yyyy or dd-mm-yyyy
*/
function set_tgl($tgl){

    $n = strlen($tgl);
    
    $d = date('d');
    $m = date('m');
    $y = date('Y');

    switch($n){
        case 0:
	    break;

	case 1:
	case 2:
	    $d = $tgl;
	    break;

        case 4:
	    $d = substr($tgl, 0, 2);
	    $m = substr($tgl, 2);
	    break;

	case 6:
	    $d = substr($tgl, 0, 2);
	    $m = substr($tgl, 2, 2);
	    $y = substr($y, 0, 2) . substr($tgl, 4);
	    break;
	     
	case 8:
	    $d = substr($tgl, 0, 2);
	    $m = substr($tgl, 2, 2);
	    $y = substr($tgl, 4);
            break;

	default:
            list($d, $m, $y) = preg_split('/[-\/]/',$tgl);
    }

    if(checkdate($m, $d, $y)) $tgl = date('d/m/Y', mktime(0, 0, 0, $m, $d, $y));
    else
	$tgl = "";

    return $tgl;
}

function numfmt($v, $dec=0, $acctfmt=false){
    $s = number_format($v, $dec, ",", ".");
    if($v < 0 && $acctfmt){
	$s = "(" . number_format(abs($v), $dec, ",", ".") . ")";
    }
    return $s;
}

?>
