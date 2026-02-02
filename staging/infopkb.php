<?php

/**
 * infopkb.php
 * Sistem Informasi PKB (Pajak Kendaraan Bermotor)
 * 
 * Fungsi: Menghitung dan menampilkan informasi PKB berdasarkan nomor polisi
 * 
 * @author Iwan Abu Bakar (Iw'87)
 * @version 2.0 - Cleaned & Optimized
 */

// Error reporting - set to E_ALL for development, 0 for production
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set timezone yang benar untuk Indonesia
date_default_timezone_set('Asia/Jakarta');

// ============================================================================
// INCLUDE LIBRARIES
// ============================================================================
include_once "../pgdbtool.php";
include_once "../samlib.php";
require_once "../Mobile-Detect/Mobile_Detect.php";

$detect = new Mobile_Detect;

// Check database connection
if (!$dbonl->connected) {
    ret_err("Database not connected!");
}

// ============================================================================
// HELPER FUNCTIONS
// ============================================================================

/**
 * Format tanggal dari input string ke format dd/mm/yyyy
 * @param string $date_string Input tanggal (format: ddmmyyyy atau ddmmyy)
 * @return string Tanggal dalam format dd/mm/yyyy
 */
function format_date_input($date_string)
{
    if (empty($date_string)) {
        return "";
    }

    $length = strlen($date_string);

    switch ($length) {
        case 8: // ddmmyyyy
            return substr($date_string, 0, 2) . "/" .
                substr($date_string, 2, 2) . "/" .
                substr($date_string, 4);

        case 6: // ddmmyy
            return substr($date_string, 0, 2) . "/" .
                substr($date_string, 2, 2) . "/20" .
                substr($date_string, 4);

        default:
            return $date_string;
    }
}

/**
 * Convert checkbox value to boolean string
 * @param string $value Input value ("on", "1", "0", etc)
 * @return string "1" or "0"
 */
function normalize_checkbox($value)
{
    return ($value == "on" || $value == "1") ? "1" : "0";
}

// ============================================================================
// GET INPUT PARAMETERS
// ============================================================================

// Nomor Polisi
$no_polisi = get_http_arg("no_polisi");
$no_polisi = setnopol($no_polisi);

// Tanggal Akhir STNK
$tg_akhir_stnk = get_http_arg("tg_akhir_stnk", "", false, false);
$tg_akhir_stnk = format_date_input($tg_akhir_stnk);

// Nama Pemilik (untuk nopol dobel)
$nm_pemilik = get_http_arg("nm_pemilik", "", false, false);
$where = "";
if ($nm_pemilik) {
    $nm_pemilik = strtoupper($nm_pemilik);
    $where = "AND nm_pemilik LIKE '%$nm_pemilik%'";
}

// Tanggal Akhir PKB
$tg_akhir_pkb = get_http_arg("tg_akhir_pkb", "", false, false);
$tg_akhir_pkb = format_date_input($tg_akhir_pkb);

// Checkbox parameters
$izin_ang = normalize_checkbox(get_http_arg("izin_ang", "0", false, false));
$siup_ang = normalize_checkbox(get_http_arg("siup_ang", "0", false, false));
$kir_ang = normalize_checkbox(get_http_arg("kir_ang", "0", false, false));

// Kode Merek Kendaraan
$kd_merek_kb = get_http_arg("kd_merek_kb", "", false, false);

// Tanggal daftar hari ini
$tg_daftar = date('d/m/Y');
$d_tg_daftar = to_date($tg_daftar);

// ============================================================================
// PEMUTIHAN PAJAK CONFIGURATION
// ============================================================================

// Query untuk mengecek apakah ada pemutihan pajak
$query = "SELECT nilai FROM t_param
          WHERE kd_param = 'SISTEM'
            AND kd_data = 'PEMUTIHAN-PAJAK'";

// Default: tidak ada pemutihan pajak
$set_pp = array(
    'pokok_pkb'     => 'X',
    'denda_pkb'     => 'X',
    'pokok_swdkllj' => 'X',
    'denda_swdkllj' => 'X'
);

$pemutihan = $dbonl->getvalue($query);

// Cek apakah ada pemutihan pajak aktif
if ($pemutihan == "Y") {
    // Ambil periode pemutihan pajak
    $tg_awal_pp  = p_param("TGL-AWAL-PP");
    $tg_akhir_pp = p_param("TGL-AKHIR-PP");

    // Baca setting pemutihan pajak dari database
    $query = "SELECT * FROM t_set_pp
              WHERE tg_awal  = '" . to_dbdate($tg_awal_pp) . "'
                AND tg_akhir = '" . to_dbdate($tg_akhir_pp) . "'";

    $set_pp = $dbonl->getrow($query, "d/m/Y");

    if ($set_pp) {
        $d_tg_awal  = to_date($set_pp['tg_awal']);
        $d_tg_akhir = to_date($set_pp['tg_akhir']);

        // Cek apakah tanggal daftar dalam periode pemutihan
        if ($d_tg_daftar < $d_tg_awal || $d_tg_daftar > $d_tg_akhir) {
            $pemutihan = "T"; // Di luar periode
        }
    } else {
        $pemutihan = "T"; // Setting tidak ditemukan
    }
} else {
    $pemutihan = "T"; // Tidak ada pemutihan
}

// ============================================================================
// CARI DATA KENDARAAN
// ============================================================================

$result   = false;
$tg_bayar = "1990-01-01";
$found    = 0; // 0=not found, 1=t_trnkb, 2=t_mstkb, 3=tt_trnkb

// Step 1: Cari di tabel transaksi tahun berjalan (t_trnkb)
$query = "SELECT no_polisi, nm_pemilik, al_pemilik, kd_jen_milik, kd_fungsi,
                 kd_kel_kb, kd_jenis_kb, kd_merek_kb, nm_merek_kb, nm_model_kb, 
                 nm_jenis_kb, th_rakitan, jumlah_cc, warna_kb, kd_plat, kd_bbm, 
                 tg_akhir_pkb, tg_akhir_jr, tg_akhir_stnk, 
                 tg_bayar, kd_mohon, kd_lokasi
          FROM t_trnkb
          WHERE no_polisi = '$no_polisi'
            AND tg_bayar > '$tg_bayar'
            $where
          ORDER BY tg_bayar DESC, no_urut_trn DESC";

$row = $dbonl->getrow($query, "d/m/Y");

if ($row) {
    $result   = $row;
    $tg_bayar = to_dbdate($row['tg_bayar']);
    $found    = 1;
}

// Step 2: Jika tidak ditemukan, cari di tabel master (t_mstkb)
$query = "SELECT no_polisi, nm_pemilik, al_pemilik, kd_jen_milik, kd_fungsi,
                 kd_kel_kb, kd_jenis_kb, kd_merek_kb, nm_merek_kb, nm_model_kb, 
                 nm_jenis_kb, th_rakitan, jumlah_cc, warna_kb, kd_plat, kd_bbm, 
                 tg_akhir_pkb, tg_akhir_jr, tg_akhir_stnk, tg_bayar, '-', '-'
          FROM t_mstkb
          WHERE no_polisi = '$no_polisi'
            AND tg_bayar > '$tg_bayar'
            $where";

$row = $dbonl->getrow($query, "d/m/Y");

if ($row) {
    $result   = p_mst2trn($row);
    $tg_bayar = to_dbdate($row['tg_bayar']);
    $found    = 2;
}

// Step 3: Jika masih belum ditemukan di t_trnkb, cari di tabel transaksi selesai (tt_trnkb)
if ($found != 1) {
    $query = "SELECT no_polisi, nm_pemilik, al_pemilik, kd_jen_milik, kd_fungsi,
                     kd_kel_kb, kd_jenis_kb, kd_merek_kb, nm_merek_kb, 
                     nm_model_kb, nm_jenis_kb, th_rakitan, jumlah_cc, 
                     warna_kb, kd_plat, kd_bbm, tg_akhir_pkb, tg_akhir_jr, 
                     tg_akhir_stnk, tg_bayar, kd_mohon, kd_lokasi
              FROM tt_trnkb
              WHERE no_polisi = '$no_polisi'
                AND tg_bayar > '$tg_bayar'
                $where
              ORDER BY tg_bayar DESC, no_urut_trn DESC";

    $row = $dbonl->getrow($query, "d/m/Y");

    if ($row) {
        $result = $row;
        $found  = 3;
    }
}

// ============================================================================
// INISIALISASI VARIABEL PERHITUNGAN
// ============================================================================

$datakb = false;
$pkb = 0;
$swd = 0;
$tot = 0;
$tg_akhir_yad = "";

$error  = false;
$errmsg = "";
$errno  = 0;

// ============================================================================
// VALIDASI DATA KENDARAAN
// ============================================================================

if ($found) {
    $kd_mohon = $result['kd_mohon'];

    // Cek apakah kendaraan sudah diremajakan (rejuvenation)
    // Pattern: .XX atau 3[34]
    if (
        preg_match('/.[36X]X./', $kd_mohon) == 1 ||
        preg_match('/.3[34]./', $kd_mohon) == 1
    ) {
        $found = false;
    }
}

// ============================================================================
// PROSES DATA KENDARAAN YANG DITEMUKAN
// ============================================================================

if ($found) {
    // Cek dan update tanggal akhir PKB/JR dari data penerimaan SWDKLLJ
    $s = to_dbdate($result['tg_akhir_pkb']);

    $query = "SELECT tg_akhir_jr FROM t_datapnrjr
              WHERE no_polisi = '$no_polisi'
                AND tg_akhir_jr > '$s'
              ORDER BY tg_akhir_jr DESC";

    $s = $dbonl->getvalue($query, "d/m/Y");
    if ($s) {
        $result['tg_akhir_pkb'] = $s;
        $result['tg_akhir_jr'] = $s;
    }

    // Inisialisasi data kendaraan untuk perhitungan
    $datakb = $result;

    $datakb['kd_mohon']   = ".2."; // Kode permohonan daftar ulang
    $datakb['tg_daftar']  = date('d/m/Y');
    $datakb['kd_proses']  = "pkb";
    $datakb['jt_berubah'] = false;

    // Override tanggal akhir PKB jika ada parameter input
    if ($tg_akhir_pkb) {
        $result['tg_akhir_pkb'] = $tg_akhir_pkb;
        $result['tg_akhir_jr']  = $tg_akhir_pkb;
        $datakb['tg_akhir_pkb'] = $tg_akhir_pkb;
        $datakb['tg_akhir_jr']  = $tg_akhir_pkb;
    }

    // Set data angkutan
    $datakb['izin_ang'] = $izin_ang;
    $datakb['siup_ang'] = $siup_ang;
    $datakb['kir_ang']  = $kir_ang;

    // Update merek kendaraan jika ada parameter input
    if ($kd_merek_kb) {
        $datakb['kd_merek_kb'] = $kd_merek_kb;

        $query = "SELECT nm_merek_kb, nm_model_kb, nm_jenis_kb
                  FROM t_merekkb
                  WHERE kd_merek_kb = '$kd_merek_kb'";

        $row = $dbonl->getrow($query);
        if ($row) {
            $datakb['nm_merek_kb'] = $row['nm_merek_kb'];
            $datakb['nm_model_kb'] = $row['nm_model_kb'];
            $datakb['nm_jenis_kb'] = $row['nm_jenis_kb'];
        }
    }

    // Update tanggal akhir STNK jika disebutkan
    if (to_date($tg_akhir_stnk)) {
        $datakb['tg_akhir_stnk'] = $tg_akhir_stnk;
    }

    $datakb['ctk_stnk'] = "0";

    // Inisialisasi data perhitungan
    $datakb['byr_dimuka']       = false;
    $datakb['progresif']        = "";
    $datakb['no_urut']          = 1;
    $datakb['njkb']             = 0;
    $datakb['pct_trf']          = 1.5;
    $datakb['pct_pkb']          = 90.4; // Berubah dari 100% menjadi 90.4%
    $datakb['tg_akhir_pkb_yad'] = "";
    $datakb['opsen_berlaku']    = false; // Flag untuk penerapan OPSEN

    // Get nama lokasi
    $result['nm_lokasi'] = "-";
    if ($datakb['kd_lokasi'] != '-') {
        $result['nm_lokasi'] = $dbonl->getvalue(
            "SELECT nm_lokasi FROM t_nm_lokasi WHERE kd_lokasi = '" .
                $datakb['kd_lokasi'] . "'"
        );
    }

    // Set data pemutihan pajak
    $datakb['pemutihan'] = $pemutihan;
    $datakb['set_pp'] = false;
    if ($pemutihan == "Y") {
        $datakb['set_pp'] = $set_pp;
    }

    // ========================================================================
    // PERHITUNGAN BIAYA
    // ========================================================================

    $pkb  = hitpkb($datakb);  // Hitung PKB
    $swd  = hitswd($datakb);  // Hitung SWDKLLJ
    $stnk = 0;
    $tnkb = 0;

    // Hitung biaya STNK dan TNKB jika ada tanggal akhir STNK
    if (to_date($datakb['tg_akhir_stnk'])) {
        $tg_akhir_stnk = $datakb['tg_akhir_stnk'];
        $y1 = year($datakb['tg_daftar']);
        $y2 = year($datakb['tg_akhir_pkb']);

        // Adjust tahun STNK jika melebihi tahun PKB
        if (year($tg_akhir_stnk) > $y2) {
            while (true) {
                $tg_akhir_stnk = addyear($tg_akhir_stnk, -5);
                $y = year($tg_akhir_stnk);

                // Keluar jika kurang dari tahun PKB atau tahun daftar
                if ($y < $y2 || $y < $y1) {
                    break;
                }

                $datakb['tg_akhir_stnk'] = $tg_akhir_stnk;
            }
        }

        $stnk = hitstnk($datakb);
        $tnkb = hittnkb($datakb);
    }
}

// ============================================================================
// FORMAT OUTPUT BIAYA
// ============================================================================

if ($found) {
    if (!$error) {
        // Hitung total biaya
        $tot = $pkb + $swd + $stnk + $tnkb;

        // Format ke currency Indonesia
        $pkb  = number_format($pkb, 0, ',', '.');
        $swd  = number_format($swd, 0, ',', '.');
        $stnk = number_format($stnk, 0, ',', '.');
        $tnkb = number_format($tnkb, 0, ',', '.');
        $tot  = number_format($tot, 0, ',', '.');

        $tg_akhir_yad = $datakb['tg_akhir_pkb_yad'];
    }
}

// ============================================================================
// HELPER FUNCTIONS - KONVERSI DATA
// ============================================================================

/**
 * Convert data master kendaraan ke format transaksi
 * @param array $vt_mstkb Data dari tabel t_mstkb
 * @return array Data dalam format t_trnkb
 */
function p_mst2trn($vt_mstkb)
{
    // Mapping data dari master ke transaksi
    $row = array(
        'no_polisi'      => $vt_mstkb['no_polisi'],
        'nm_pemilik'     => $vt_mstkb['nm_pemilik'],
        'al_pemilik'     => $vt_mstkb['al_pemilik'],
        'kd_jen_milik'   => $vt_mstkb['kd_jen_milik'],
        'kd_fungsi'      => $vt_mstkb['kd_fungsi'],
        'kd_kel_kb'      => $vt_mstkb['kd_kel_kb'],
        'kd_jenis_kb'    => $vt_mstkb['kd_jenis_kb'],
        'kd_merek_kb'    => $vt_mstkb['kd_merek_kb'],
        'nm_merek_kb'    => $vt_mstkb['nm_merek_kb'],
        'nm_model_kb'    => $vt_mstkb['nm_model_kb'],
        'nm_jenis_kb'    => $vt_mstkb['nm_jenis_kb'],
        'th_rakitan'     => $vt_mstkb['th_rakitan'],
        'jumlah_cc'      => $vt_mstkb['jumlah_cc'],
        'warna_kb'       => $vt_mstkb['warna_kb'],
        'kd_plat'        => $vt_mstkb['kd_plat'],
        'kd_bbm'         => $vt_mstkb['kd_bbm'],
        'tg_akhir_pkb'   => $vt_mstkb['tg_akhir_pkb'],
        'tg_akhir_jr'    => $vt_mstkb['tg_akhir_jr'],
        'tg_akhir_stnk'  => $vt_mstkb['tg_akhir_stnk'],
        'tg_bayar'       => $vt_mstkb['tg_bayar'],
        'kd_mohon'       => '-',
        'kd_lokasi'      => '-'
    );

    return $row;
}

// ============================================================================
// FUNGSI PERHITUNGAN PKB (Pajak Kendaraan Bermotor)
// ============================================================================

/**
 * Hitung PKB (Pajak Kendaraan Bermotor)
 * @param array $datakb Data kendaraan
 * @return int Nilai PKB atau -1 jika error
 */
function hitpkb(&$datakb)
{
    global $dbonl;

    // Kendaraan yang dikecualikan dari pengenaan PKB
    // kd_jen_milik 06: Kendaraan khusus
    // kd_fungsi 10: Fungsi khusus
    if ($datakb['kd_jen_milik'] == '06' || $datakb['kd_fungsi'] == '10') {
        return 0;
    }

    $no_polisi    = $datakb['no_polisi'];
    $tg_daftar    = date('d/m/Y');
    $tg_akhir_pkb = $datakb['tg_akhir_pkb'];

    // Validasi: Cek masa berlaku untuk perhitungan PKB
    // Tidak boleh daftar ulang lebih dari 90 hari sebelum masa berlaku habis
    if ($datakb['kd_proses'] == 'pkb') {
        $d_tg_daftar    = to_date($tg_daftar);
        $d_tg_akhir_pkb = to_date($tg_akhir_pkb);

        if ($d_tg_akhir_pkb > $d_tg_daftar) {
            $sel_tgl = selisih_tgl($tg_daftar, $tg_akhir_pkb);
            if ($sel_tgl['n'] > 90) {
                set_error("MOHON MAAF, KENDARAAN ANDA BELUM SAATNYA DAFTAR ULANG!", -1);
                return -1;
            }
        }
    }

    // Special handling untuk kendaraan dinas
    // Untuk kendaraan dinas yang masa berlakunya sebelum 1/1/2012, 
    // di-set ke tanggal 1/1/2012
    if ($datakb['kd_kel_kb'] == 'D') {
        if (strpos($datakb['kd_mohon'], '.2.') !== false) {
            list($d, $m, $y) = split_date($datakb['tg_akhir_pkb']);
            if (mktime(0, 0, 0, $m, $d, $y) < mktime(0, 0, 0, 1, 1, 2012)) {
                $tg_akhir_pkb = date('d/m/Y', mktime(0, 0, 0, 1, 1, 2012));
            }
        }
    }

    // Get data merek kendaraan dari database
    $found = getmrk(
        $datakb['kd_merek_kb'],
        $datakb['nm_merek_kb'],
        $datakb['nm_model_kb'],
        $datakb['nm_jenis_kb']
    );

    if (!$found) {
        return -1;
    }

    // Get tarif NJKB (Nilai Jual Kendaraan Bermotor)
    $trfnj = gettrfnj($datakb['kd_merek_kb'], $datakb['th_rakitan']);
    if (!$trfnj) {
        return -1;
    }

    // Format NJKB untuk ditampilkan
    $datakb['njkb'] = "Rp" . number_format($trfnj['nilai_jual'], 0, ',', '.') .
        ",- x " . str_replace(".", ",", $trfnj['bobot']);

    $datakb['byr_dimuka'] = false;

    // Hitung selisih tanggal akhir PKB dengan tanggal pendaftaran
    $sel_tgl = selisih_tgl($tg_akhir_pkb, $tg_daftar);

    // Determine kategori kendaraan berdasarkan kode plat
    switch ($datakb['kd_plat']) {
        case "2":
            $datakb['kd_kel_kb'] = "U"; // Umum
            break;

        case "3":
            $datakb['kd_kel_kb'] = "D"; // Dinas
            break;

        default:
            $datakb['kd_kel_kb'] = "P"; // Pribadi
            break;
    }

    // tarif dan pengenaan pkb        
    switch ($datakb['kd_kel_kb']) {
        default:
            $pct_trf = 1.5;
            $pct_pengenaan = 90.4; // Berubah dari 100% menjadi 90.4%

            $trfpkb = ($pct_trf / 100) * $trfnj['nilai_jual'] * $trfnj['bobot'] * ($pct_pengenaan / 100);

            // kendaraan pribadi non sepeda motor
            if (
                $datakb['kd_jen_milik'] == '01' &&
                $datakb['kd_fungsi']    == '01' &&
                $datakb['kd_jenis_kb'] != 'R'
            ) {

                if (
                    substr($datakb['nm_pemilik'], 0, 2) != 'PT' &&
                    substr($datakb['nm_pemilik'], 0, 2) != 'CV'
                ) {

                    $sql = "SELECT no_urut FROM t_progresif
                                WHERE no_polisi = '$no_polisi'";
                    $no_urut = $dbonl->getvalue($sql);

                    if (!$no_urut) {
                        set_error("MAAF, DATA KENDARAAN ANDA BELUM DISET PROGRESIF!", -2);
                        return -1;
                    }

                    switch ($no_urut) {
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
                    if ($no_urut > 1) {
                        $datakb['no_urut'] = $no_urut;
                        $datakb['progresif'] = "PROGRESIF: PR$no_urut, TARIF: $pct_trf%, PKB: Rp." . number_format($trfpkb, 0, ',', '.') . ",-";
                    }
                }
            }

            if (
                $datakb['kd_fungsi'] == "04" ||
                $datakb['kd_fungsi'] == "06" ||
                $datakb['kd_fungsi'] == "07" ||
                $datakb['kd_fungsi'] == "08"
            ) {

                $pct_trf = 0.5;
                $pct_pengenaan = 90.4; // Berubah dari 100% menjadi 90.4%

                // kecuali sedan dan jeep
                if (
                    $datakb['kd_jenis_kb'] == "A" ||
                    $datakb['kd_jenis_kb'] == "B"
                ) {

                    $pct_trf = 1.5;
                    $pct_pengenaan = 90.4; // Berubah dari 100% menjadi 90.4%
                }
            }
            break;

        case 'U':
            $pct_trf = 1;
            $pct_pengenaan = 90.4; // Berubah dari 100% menjadi 90.4%

            // punya izin angkutan umum
            if ($datakb['izin_ang'] == "1") {
                $pct_pengenaan = 30;
                if (ereg('[FGH]', $datakb['kd_jenis_kb']))
                    $pct_pengenaan = 60;
            }
            /*
                // punya izin angkutan umum
                    if($datakb['izin_ang'] == "1"){
                        $pct_trf = 1;
                        $pct_pengenaan = 90.4; // Berubah dari 100% menjadi 90.4%

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
            $pct_trf = 0.5;
            $pct_pengenaan = 90.4; // Berubah dari 100% menjadi 90.4%
            break;
    }

    $datakb['pct_trf'] = $pct_trf;
    $datakb['pct_pkb'] = $pct_pengenaan;

    // tarif pkb
    $trfpkb = ($pct_trf / 100) * $trfnj['nilai_jual'] * $trfnj['bobot'] * ($pct_pengenaan / 100);

    // sudah terlambat
    if ($sel_tgl['n'] > 0) {

        // pokok thn. berjalan
        $pkb_pok[0] = $trfpkb;

        // denda thn. berjalan        
        $m = $sel_tgl['m'];
        $y = $sel_tgl['y'];

        if ($sel_tgl['d'] > 15) $m++;

        $pkb_den[0] = (2 + ($m * 2)) / 100 * $trfpkb; //====================================================================================================Denda PKB

        // init tunggakan
        for ($i = 1; $i <= 5; $i++) {
            $pkb_pok[$i] = 0;
            $pkb_den[$i] = 0;
        }

        $y = $sel_tgl['y'];
        if ($y > 5) $y = 5;

        // tunggakan
        $m = $sel_tgl['m'];
        for ($i = 1; $i <= $y; $i++) {
            $m += ($i * 12);
            if ($m > 24) $m = 24;
            $pkb_pok[$i] = $trfpkb;
            $pkb_den[$i] = (2 + ($m * 2)) / 100 * $trfpkb; //======================================================================================================Denda PKB
        }

        // jt berubah
        if ($datakb['jt_berubah']) {
            $m = $sel_tgl['m'];
            if ($sel_tgl['d'] > 1) $m++;
            if ($m > 0) {
                $pkb_pok[1] += ($m / 12) * $trfpkb;
                $pkb_den[1] += (2 + ($m * 2)) / 100 * $trfpkb; //==================================================================================================Denda PKB
            }
            $pkb_den[0] = 0;
        } else {
            // jt tidak berubah
            list($d, $m, $y) = split_date($tg_akhir_pkb);
            $tg_akhir_pkb_yad = date('d/m/Y', mktime(0, 0, 0, $m, $d, $y + $sel_tgl['y'] + 1));

            // harus cetak stnk - bandingkan dengan tahun STNK dari data kendaraan
            if (isset($datakb['tg_akhir_stnk']) && !empty($datakb['tg_akhir_stnk'])) {
                if (year($tg_akhir_pkb_yad) == year($datakb['tg_akhir_stnk'])) {
                    $datakb['ctk_stnk'] = "1";
                }
            }

            $sel_tgl2 = selisih_tgl($tg_daftar, $tg_akhir_pkb_yad);

            // udah tinggal 90 hari lagi
            if ($sel_tgl2['n'] <= 90) {
                // simpan data tahun berjalan
                $pokok = $pkb_pok[0];
                $denda = $pkb_den[0];

                // pokok dan denda thn. berjalan
                $pkb_pok[0] = $trfpkb;
                $pkb_den[0] = 0;

                for ($i = 5; $i > 1; $i--) {
                    $pkb_pok[$i] = $pkb_pok[$i - 1];
                    $pkb_den[$i] = $pkb_den[$i - 1];
                }

                $pkb_pok[1] = $pokok;
                $pkb_den[1] = $denda;

                $datakb['byr_dimuka'] = true;
            }
        }
    } else {
        // belum terlambat

        // kalo jatuh temponya berubah
        if ($datakb['jt_berubah']) {
            list($d, $m, $y) = split_date($tg_daftar);
            $tg_akhir_pkb_yad = date('d/m/Y', mktime(0, 0, 0, $m, $d, $y + 1));
            $sel_tgl = selisih_tgl($tg_akhir_pkb, $tg_akhir_pkb_yad);
            $m = $sel_tgl['m'];
            if ($sel_tgl['d'] > 14) $m++;
            $pkb_pok[0] = ($m / 12) * $trfpkb;
            $pkb_den[0] = 0;
        } else {
            // jatuh tempo tidak berubah
            $pkb_pok[0] = $trfpkb;
            $pkb_den[0] = 0;
        }
    }

    // kalo ada pemutihan
    if ($datakb['pemutihan'] == "Y") {

        // sebelum pemutihan
        $pok = 0;
        foreach ($pkb_pok as &$value) {
            $value = pembulatan($value);
            $pok += $value;
        }

        $den = 0;
        foreach ($pkb_den as &$value) {
            $value = pembulatan($value);
            $den += $value;
        }

        $tot = $pok + $den;

        $datakb['pok_pkb_awal'] = $pok;
        $datakb['den_pkb_awal'] = $den;
        $datakb['tot_pkb_awal'] = $tot;

        $set_pp = $datakb['set_pp'];

        // pemutihan pokok pkb
        switch ($set_pp['pokok_pkb']) {
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
                if ($pkb_pok[1] > 0) {
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
        switch ($set_pp['denda_pkb']) {
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

    // ========================================================================
    // PERHITUNGAN OPSEN 66% dari PKB
    // Catatan: Pengecekan apakah OPSEN berlaku akan dilakukan setelah
    // tg_akhir_pkb_yad dihitung (di bawah)
    // ========================================================================
    $pkb_opsen = array();
    $pkb_den_opsen = array();

    // Inisialisasi OPSEN (akan diset ulang jika tidak berlaku)
    for ($i = 0; $i <= 5; $i++) {
        // OPSEN = 66% dari Pokok PKB
        $pkb_opsen[$i] = $pkb_pok[$i] * 0.66;

        // Denda OPSEN = 1% dari OPSEN (jika ada denda PKB)
        if ($pkb_den[$i] > 0) {
            $pkb_den_opsen[$i] = $pkb_opsen[$i] * 0.01;
        } else {
            $pkb_den_opsen[$i] = 0;
        }
    }

    $pok = 0;
    foreach ($pkb_pok as &$value) {
        $value = pembulatan($value);
        $pok += $value;
    }

    $den = 0;
    foreach ($pkb_den as &$value) {
        $value = pembulatan($value);
        $den += $value;
    }

    // Hitung total OPSEN
    $pok_opsen = 0;
    foreach ($pkb_opsen as &$value) {
        $value = pembulatan($value);
        $pok_opsen += $value;
    }

    $den_opsen = 0;
    foreach ($pkb_den_opsen as &$value) {
        $value = pembulatan($value);
        $den_opsen += $value;
    }

    $tot = $pok + $den;

    $datakb['pok_pkb_akhir'] = $pok;
    $datakb['den_pkb_akhir'] = $den;
    $datakb['tot_pkb_akhir'] = $tot;

    // Simpan data OPSEN
    $datakb['pkb_opsen'] = $pkb_opsen;
    $datakb['pkb_den_opsen'] = $pkb_den_opsen;
    $datakb['pok_opsen'] = $pok_opsen;
    $datakb['den_opsen'] = $den_opsen;
    $datakb['tot_opsen'] = $pok_opsen + $den_opsen;

    if ($datakb['pemutihan'] == 'Y') {
        $datakb['jml_pp_pkb'] = $datakb['tot_pkb_awal'] -
            $datakb['tot_pkb_akhir'];
    }

    $datakb['pokok_pkb'] = number_format($pok, 0, ",", ".");
    $datakb['denda_pkb'] = number_format($den, 0, ",", ".");
    $datakb['total_pkb'] = number_format($tot, 0, ",", ".");

    // Format OPSEN untuk ditampilkan
    $datakb['pokok_opsen'] = number_format($pok_opsen, 0, ",", ".");
    $datakb['denda_opsen'] = number_format($den_opsen, 0, ",", ".");
    $datakb['total_opsen'] = number_format($pok_opsen + $den_opsen, 0, ",", ".");

    // Total PKB + OPSEN
    $grand_total_pkb = $tot + $pok_opsen + $den_opsen;
    $datakb['grand_total_pkb'] = number_format($grand_total_pkb, 0, ",", ".");

    if ($datakb['jt_berubah']) {
        list($d, $m, $y) = split_date($tg_daftar);
        $datakb['tg_akhir_pkb_yad'] = date('d/m/Y', mktime(0, 0, 0, $d, $m, $y + 1));
    } else {
        // jatuh tempo tidak berubah
        $n = ($datakb['byr_dimuka']) ? 2 : 1;

        list($d, $m, $y) = split_date($tg_akhir_pkb);
        $datakb['tg_akhir_pkb_yad'] = date('d/m/Y', mktime(0, 0, 0, $m, $d, $y + $sel_tgl['y'] + $n));
    }

    // ========================================================================
    // CEK APAKAH OPSEN BERLAKU (Tanggal PKB Baru >= 06/01/2026)
    // ========================================================================
    $datakb['opsen_berlaku'] = false;

    // Cek apakah Tanggal PKB Baru (tg_akhir_pkb_yad) pada atau setelah 06/01/2026
    if (!empty($datakb['tg_akhir_pkb_yad']) && to_date($datakb['tg_akhir_pkb_yad'])) {
        list($d, $m, $y) = split_date($datakb['tg_akhir_pkb_yad']);
        $timestamp_pkb_baru = mktime(0, 0, 0, $m, $d, $y);
        $timestamp_batas_opsen = mktime(0, 0, 0, 1, 6, 2026); // 06 Januari 2026

        if ($timestamp_pkb_baru >= $timestamp_batas_opsen) {
            $datakb['opsen_berlaku'] = true;
        }
    }

    // Jika OPSEN tidak berlaku, set semua nilai OPSEN ke 0
    if (!$datakb['opsen_berlaku']) {
        // Reset array OPSEN
        for ($i = 0; $i <= 5; $i++) {
            $pkb_opsen[$i] = 0;
            $pkb_den_opsen[$i] = 0;
        }

        // Reset total OPSEN
        $pok_opsen = 0;
        $den_opsen = 0;

        // Update data OPSEN di datakb
        $datakb['pkb_opsen'] = $pkb_opsen;
        $datakb['pkb_den_opsen'] = $pkb_den_opsen;
        $datakb['pok_opsen'] = 0;
        $datakb['den_opsen'] = 0;
        $datakb['tot_opsen'] = 0;

        // Format OPSEN untuk ditampilkan
        $datakb['pokok_opsen'] = number_format(0, 0, ",", ".");
        $datakb['denda_opsen'] = number_format(0, 0, ",", ".");
        $datakb['total_opsen'] = number_format(0, 0, ",", ".");

        // Recalculate Total PKB (tanpa OPSEN)
        $grand_total_pkb = $tot;
        $datakb['grand_total_pkb'] = number_format($grand_total_pkb, 0, ",", ".");
    }

    if ($datakb['kd_kel_kb'] == "D") {
        if (strpos($datakb['kd_mohon'], ".2.") !== false) {
            list($d, $m, $y) = split_date($datakb['tg_akhir_pkb_yad']);

            // kalo tgl. akhir pkb yang akan datang sebelum 1/1/2013, 
            // tidak dipungut
            if (mktime(0, 0, 0, $m, $d, $y) < mktime(0, 0, 0, 1, 1, 2013)) {
                $tot = 0;
            }
        }
    }

    return $tot;
}

function getmrk(&$kd_merek_kb, $nm_merek_kb, $nm_model_kb, $nm_jenis_kb)
{
    global $dbonl;

    $kode = substr($kd_merek_kb, 0, 3);
    switch ($kode) {
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
            if ($nm_jenis_kb == "LIGH TRUK") $nm_jenis_kb = "LIGHT TRUCK";
            break;

        case "401":
            if ($nm_jenis_kb == "TRUK") $nm_jenis_kb = "TRUCK";
            break;

        case "701":
            $nm_jenis_kb = "SPD. MOTOR R2";
            break;

        case "702":
            $nm_jenis_kb = "SPD. MOTOR R3";
            break;
    }

    $s = substr($nm_jenis_kb, -3);
    if (substr($s, 0, 1) == "/") $nm_jenis_kb = substr($nm_jenis_kb, 0, -3);

    $mrk1  = setmodel($nm_merek_kb, $nm_model_kb, $nm_jenis_kb);
    $mrk1b = str_replace("0", "O", $mrk1);

    $sql = "SELECT nm_merek_kb, nm_model_kb, nm_jenis_kb
                FROM t_merekkb
               WHERE kd_merek_kb = '$kd_merek_kb'";
    $datamrk = $dbonl->getrow($sql);

    $found_mrk = false;

    if ($datamrk and is_array($datamrk)) {
        $mrk2  = setmodel($datamrk['nm_merek_kb'], $datamrk['nm_model_kb'], $datamrk['nm_jenis_kb']);
        $mrk2b = str_replace("0", "O", $mrk2);

        if ($mrk1 == $mrk2 || $mrk1b == $mrk2b) {
            $found_mrk = true;
        }
    }

    if (! $found_mrk) {

        $sql = "SELECT kd_merek_kb, str_merekkb
                    FROM t_merekkb
                   WHERE nm_merek_kb = '$nm_merek_kb' 
                     AND nm_jenis_kb = '$nm_jenis_kb'";

        $rs = $dbonl->query($sql);
        while ($row = $dbonl->fetch_assoc($rs)) {
            $mrk2  = trim($row['str_merekkb']);
            $mrk2b = str_replace("0", "O", $mrk2);

            if ($mrk1 == $mrk2 || $mrk1b == $mrk2b) {
                $kd_merek_kb = $row['kd_merek_kb'];
                $found_mrk = true;
                break;
            }
        }

        if (! $found_mrk) {
            $sql = "SELECT kd_merek_kb, nm_merek_kb, nm_model_kb, nm_jenis_kb
                        FROM t_merekkb
                       WHERE str_merekkb = '$mrk1'";
            $datamrk = $dbonl->getrow($sql);
            $mrk2  = setmodel($datamrk['nm_merek_kb'], $datamrk['nm_model_kb'], $datamrk['nm_jenis_kb']);
            $mrk2b = str_replace("0", "O", $mrk2);

            if ($mrk1 == $mrk2 || $mrk1b == $mrk2b) {
                $kd_merek_kb = $datamrk['kd_merek_kb'];
                $found_mrk = true;
            }
        }
    }

    if ($found_mrk) {
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

function gettrfnj($kd_merek_kb, $thn)
{
    global $dbonl;

    $sql = "SELECT * FROM t_trf_nj
                WHERE kd_merek_kb = '$kd_merek_kb'
                  AND thn = $thn";
    $trfnj = $dbonl->getrow($sql);
    if (!$trfnj) {
        set_error("MOHON MAAF, TARIF NILAI JUAL KENDARAAN ANDA BELUM TERDATA!", -4);
        return false;
    }

    return $trfnj;
}

/*
    hitung swdkllj
*/
function hitswd(&$datakb)
{
    switch ($datakb['kd_jenis_kb']) {
        case 'A':
            $kd_trf_swd = "DP";
            if ($datakb['kd_kel_kb'] == 'U') {
                $kd_trf_swd = ($datakb['jumlah_cc'] <= 1600) ? "DU" : "EU";
            }
            break;

        case 'B':
            $kd_trf_swd = "DP";
            break;

        case 'C':
            $kd_trf_swd = "DP";
            if ($datakb['kd_kel_kb'] == 'U') {
                $kd_trf_swd = ($datakb['jumlah_cc'] <= 1600) ? "DU" : "EU";
            }
            break;

        case 'D':
            if ($datakb['kd_kel_kb'] == "U") $kd_trf_swd = "EU";
            else
                $kd_trf_swd = "EP";
            break;

        case 'E':
            if ($datakb['kd_kel_kb'] == "U") $kd_trf_swd = "EU";
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
            if ($datakb['jumlah_cc'] < 50) $kd_trf_swd = 'A';
            elseif ($datakb['jumlah_cc'] > 250) $kd_trf_swd = 'C2';
            break;
    }

    // Check apakah fungsi kendaraan termasuk kategori angkutan (06, 07, 08)
    if ($datakb['kd_jenis_kb'] != 'R') {
        // Replace ereg() yang deprecated dengan preg_match()
        if (preg_match('/0[678]/', $datakb['kd_fungsi'])) {
            $kd_trf_swd = 'A';
        }
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
    if ($d_tg_akhir_jr < $d_tg_daftar) {
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
    if ($sel_tgl['n'] > 0) {

        // $d_tgl = mktime(0, 0, 0, 1, 1, $y+$sel_tgl['y']);
        $d_tg_daluarsa = mktime(0, 0, 0, 1, 1, date('Y') - 4);

        // sudah daluarsa
        if ($d_tg_akhir_jr < $d_tg_daluarsa) {

            $daluarsa = true;

            list($d, $m, $y) = split_date($tg_akhir_jr);
            if ($datakb['jt_berubah'])
                list($d, $m, $y) = split_date($tg_daftar);

            $y = date('Y', $d_tg_daluarsa);

            // hitung tunggakannya
            $k = -1;
            for ($i = 4; $i > 0; $i--) {
                $k++;
                $tgl = date('d/m/Y', mktime(0, 0, 0, $m, $d, $y + $k));
                $trfswd = tarif_swd($tgl, $kd_trf_swd, 12);
                $swd_pok[$i] = $trfswd['prorata_12'];
                $swd_den[$i] = $swd_pok[$i] - $trfswd['krt_swd'];
            }
        } else {
            // belum daluarsa

            list($d, $m, $y) = split_date($tg_akhir_jr);

            // jatuh tempo berubah
            if ($datakb['jt_berubah']) {
                // tunggakannya
                $k = -1;
                for ($i = $sel_tgl['y']; $i > 0; $i--) {
                    $k++;
                    if ($i < 5) {
                        $tgl = date('d/m/Y', mktime(0, 0, 0, $m, $d, $y + $k));
                        $trfswd = tarif_swd($tgl, $kd_trf_swd, 12);
                        $swd_pok[$i] = $trfswd['prorata_12'];
                        $swd_den[$i] = $swd_pok[$i] - $trfswd['krt_swd'];
                    }
                }

                // prorata s/d tgl. pendaftaran
                list($d, $m, $y) = split_date($tg_daftar);

                // tarif prorata
                $trfswd = tarif_swd($tg_daftar, $kd_trf_swd, $m);
                $s = (strlen($m) == 1) ? "0$m" : $m;

                // proratanya
                $swd_pok[0] = $trfswd["prorata_$s"];
                $swd_den[0] = 0;
            } else {
                // jatuh tempo tidak berubah

                // kalo bayar dimuka sekaligus, jumlah thn. tgk + 1
                $n = ($datakb['byr_dimuka']) ? 1 : 0;

                // tunggakannya
                $k = -1;
                for ($i = $sel_tgl['y'] + $n; $i > 0; $i--) {
                    $k++;
                    if ($i < 5) {
                        $tgl = date('d/m/Y', mktime(0, 0, 0, $m, $d, $y + $k));
                        $trfswd = tarif_swd($tgl, $kd_trf_swd, 12);
                        $swd_pok[$i] = $trfswd['prorata_12'];
                        $swd_den[$i] = $swd_pok[$i] - $trfswd['krt_swd'];
                    }
                }
            }
            // belum daluarsa
        }

        list($d, $m, $y) = split_date($tg_akhir_jr);
        $tg_pre_jr = date('d/m/Y', mktime(
            0,
            0,
            0,
            $m,
            $d,
            $y + $sel_tgl['y']
        ));
        if ($datakb['jt_berubah']) {
            // dihitung dari tgl. pendaftaran
            $trfswd = tarif_swd($tg_daftar, $kd_trf_swd, 12);
        } else {

            if ($datakb['byr_dimuka']) {
                $trfswd = tarif_swd($tg_daftar, $kd_trf_swd, 12);
                $tg_pre_jr = date('d/m/Y', mktime(
                    0,
                    0,
                    0,
                    $m,
                    $d,
                    $y + $sel_tgl['y'] + 1
                ));
            } else {
                $trfswd = tarif_swd($tg_pre_jr, $kd_trf_swd, 12);
            }
        }
        // pokok tahun berjalan (nilainya ditambah dengan proratanya)
        $swd_pok[0] += $trfswd['prorata_12'];

        // denda tahun berjalan
        // $swd_den[0] = $trfswd['prorata_12'] - $trfswd['krt_swd'];
        $swd_den[0] = hit_den_swd(
            $tg_daftar,
            $tg_pre_jr,
            $trfswd['prorata_12'] - $trfswd['krt_swd']
        );

        if ($datakb['byr_dimuka']) $swd_den[0] = 0;
    } else {
        // belum terlambat

        // kalo jatuh temponya berubah?
        if ($datakb['jt_berubah']) {
            // set tgl. jt jr yad.
            list($d, $m, $y) = split_date($tg_daftar);
            $tg_akhir_jr_yad = date('d/m/Y', mktime(0, 0, 0, $m, $d, $y + 1));

            // hitung selisihnya dgn. tgl. akhir jr yl.
            $sel_tgl = selisih_tgl($tg_akhir_jr, $tg_akhir_jr_yad);

            // jumlah bulan pengenaan prorata
            $m = $sel_tgl['m'];
            if ($sel_tgl['d'] > 0) $m++;

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
    if ($datakb['pemutihan'] == "Y") {

        // sebelum pemutihan
        $pok = 0;
        foreach ($swd_pok as &$value) {
            // $value = pembulatan($value);
            $pok += $value;
        }

        $den = 0;
        foreach ($swd_den as &$value) {
            // $value = pembulatan($value);
            if ($value > 100000) $value = 100000;
            $den += $value;
        }

        $tot = $pok + $den;

        $datakb['pok_swd_awal'] = $pok;
        $datakb['den_swd_awal'] = $den;
        $datakb['tot_swd_awal'] = $tot;

        $set_pp = $datakb['set_pp'];

        // pemutihan pokok swdkllj
        switch ($set_pp['pokok_swdkllj']) {
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
        switch ($set_pp['denda_swdkllj']) {
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
    foreach ($swd_pok as &$value) {
        // $value = pembulatan($value);
        $pok += $value;
    }

    $den = 0;
    foreach ($swd_den as &$value) {
        // $value = pembulatan($value);
        if ($value > 100000) $value = 100000;
        $den += $value;
    }
    $tot = $pok + $den;

    $datakb['pok_swd_akhir'] = $pok;
    $datakb['den_swd_akhir'] = $den;
    $datakb['tot_swd_akhir'] = $tot;

    if ($datakb['pemutihan'] == 'Y') {
        $datakb['jml_pp_swd'] = $datakb['tot_swd_awal'] -
            $datakb['tot_swd_akhir'];
    }

    $datakb['pokok_swd'] = number_format($pok, 0, ",", ".");
    $datakb['denda_swd'] = number_format($den, 0, ",", ".");
    $datakb['total_swd'] = number_format($tot, 0, ",", ".");

    if ($datakb['jt_berubah']) {
        list($d, $m, $y) = split_date($tg_daftar);
        $datakb['tg_akhir_jr_yad'] = date('d/m/Y', mktime(0, 0, 0, $d, $m, $y + 1));
    } else {
        $n = ($datakb['byr_dimuka']) ? 2 : 1;
        list($d, $m, $y) = split_date($tg_akhir_jr);
        $datakb['tg_akhir_jr_yad'] = date('d/m/Y', mktime(0, 0, 0, $m, $d, $y + $sel_tgl['y'] + $n));
    }

    return $tot;
}

function tarif_swd($tgl, $kd_trf_swd, $bln)
{
    global $dbonl;

    $tgl = to_dbdate($tgl);

    if (strlen($bln) == 1) $bln = "0$bln";
    $data = "prorata_" . $bln;
    $sql = "SELECT $data, krt_swd FROM t_trf_swd
                WHERE tg_dari <= '$tgl' AND tg_sampai >= '$tgl'
                  AND kd_trf_swd = '$kd_trf_swd'";

    $row = $dbonl->getrow($sql);
    if ($row[$data] == $row['krt_swd']) $row['krt_swd'] = 0;
    return $row;

    if (strlen($bln) == 1) $bln = "0$bln";
    $data = "prorata_" . $bln;

    $query = "SELECT $data, krt_swd FROM t_trf_swd
                 WHERE tg_dari <= '$tgl' AND tg_sampai >= '$tgl'
                   AND kd_trf_swd = '$kd_trf_swd'";
    $row = $dbonl->getrow($query);
    if ($row[$data] == $row['krt_swd']) $row['krt_swd'] = 0;
}

function hit_den_swd($tg_tetap, $tg_akhir, $trf_swd)
{

    $d_tg_tetap = to_date($tg_tetap);
    $d_tg_akhir = to_date($tg_akhir);

    $sel_tgl = selisih_tgl($tg_akhir, $tg_tetap);
    $n = $sel_tgl['n'];

    $pct = 0;

    if ($n > 270) {
        $pct = 100;
    } elseif ($n > 180) {
        $pct = 75;
    } elseif ($n >  90) {
        $pct = 50;
    } else {
        if ($n > 0) $pct = 25;
    }

    $denda = ($pct / 100) * $trf_swd;
    if ($denda > 100000) $denda = 100000;

    return $denda;
}

/*
  hitung pnbp tnkb
*/
function hittnkb($datakb)
{
    global $dbonl;

    $tg_daftar = date('d/m/Y');
    $tg_akhir_stnk = $datakb['tg_akhir_stnk'];

    $d_tg_daftar     = to_date($tg_daftar);
    $d_tg_akhir_stnk = to_date($tg_akhir_stnk);

    $pnbp_tnkb = 0;

    # stnk sudah habis
    if (
        $d_tg_akhir_stnk <= $d_tg_daftar ||
        // atau akan habis pada thn. ybs
        year($tg_akhir_stnk) == year($tg_daftar)
    ) {
        if ($datakb['kd_jenis_kb'] == "R") {
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
function hitstnk($datakb)
{
    global $dbonl;

    $tg_daftar = date('d/m/Y');
    $tg_akhir_pkb  = $datakb['tg_akhir_pkb'];
    $tg_akhir_stnk = $datakb['tg_akhir_stnk'];

    $d_tg_daftar     = to_date($tg_daftar);
    $d_tg_akhir_pkb  = to_date($tg_akhir_pkb);
    $d_tg_akhir_stnk = to_date($tg_akhir_stnk);

    $d_tg_kena_pnbp  = mktime(0, 0, 0, 1, 6, 2016);
    $tg_kena_pnbp    = date('d/m/Y', $d_tg_kena_pnbp);

    if ($datakb['kd_jenis_kb'] == "R") {
        $pnbp_sah_stnk = p_param("BEA-SAH-STNK-R2", 0);
        $pnbp_ctk_stnk = p_param("BEA-STNK-R2", 100000);
    } else {
        $pnbp_sah_stnk = p_param("BEA-SAH-STNK-R4", 0);
        $pnbp_ctk_stnk = p_param("BEA-STNK-R4", 200000);
    }

    // Case 1: STNK belum mati
    if ($d_tg_akhir_stnk > $d_tg_daftar) {

        // belum terlambat
        if ($d_tg_akhir_pkb >= $d_tg_daftar) {

            $pnbp_stnk = $pnbp_sah_stnk;

            # harus cetak stnk
            $sel_tgl = selisih_tgl($tg_daftar, $tg_akhir_stnk);
            if ($sel_tgl['n'] <= 90) {
                $pnbp_stnk = $pnbp_ctk_stnk;
            }
        } else {

            // sudah terlambat
            while (to_date($tg_akhir_pkb) < $d_tg_kena_pnbp) {
                $tg_akhir_pkb = addyear($tg_akhir_pkb, 1);
            }

            $sel_tgl = selisih_tgl($tg_akhir_pkb, $tg_daftar);

            $y = $sel_tgl['y'];
            if ($sel_tgl['m'] > 0 || $sel_tgl['d'] > 0) {
                $y++;
            }

            if ($datakb['byr_dimuka']) {
                if ($datakb['ctk_stnk'] != "1") {
                    $y++;
                }
            }

            $pnbp_stnk = $y * $pnbp_sah_stnk;

            // harus cetak stnk
            if ($datakb['ctk_stnk'] == '1') {
                $pnbp_stnk += $pnbp_ctk_stnk;
            }
        }
    } else {

        // Case 2: STNK sudah mati

        while (true) {
            $tg_akhir_yad = addyear($tg_akhir_stnk, 5);
            if (to_date($tg_akhir_yad) > $d_tg_daftar) break;
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
        if ($d > $n) {
            $d = 1;
            $m++;
        }

        $tg_akhir_pkb = date('d/m/Y', mktime(0, 0, 0, $m, $d, $y));

        while (to_date($tg_akhir_pkb) < $d_tg_kena_pnbp) {
            $tg_akhir_pkb = addyear($tg_akhir_pkb, 1);
        }

        $sel_tgl = selisih_tgl($tg_akhir_pkb, $tg_daftar);

        $y = $sel_tgl['y'];
        if ($sel_tgl['m'] > 0 || $sel_tgl['d'] > 0) {
            $y++;
        }

        if ($datakb['byr_dimuka']) $y++;

        $pnbp_stnk += $pnbp_sah_stnk * $y;
    }

    return $pnbp_stnk;
}


function set_error($msg, $no)
{
    global $error, $errmsg, $errno;

    $error  = true;
    $errmsg = $msg;
    $errno  = $no;
}

function pembulatan($n)
{
    $m = $n;
    if ($n > 0) {
        $m = round($n / 100) * 100;
        if ($m < $n) $m += 100;
    }
    return $m;
}

function split_date($tgl)
{
    list($d, $m, $y) = preg_split('/[-\/]/', $tgl);
    if (checkdate($m, $d, $y)) return array($d, $m, $y);
    else
        return array(0, 0, 0);
}


function selisih_tgl($s1, $s2)
{

    $tgl1 = to_date($s1);
    $tgl2 = to_date($s2);

    // not a date?
    if (!$tgl1 || !$tgl2) return array('d' => 0, 'm' => 0, 'y' => 0, 'n' => 0);
    if ($tgl2 < $tgl1) return array('d' => 0, 'm' => 0, 'y' => 0, 'n' => 0);

    $s1 = to_dbdate($s1);
    $s2 = to_dbdate($s2);

    $datetime1 = new DateTime($s1);
    $datetime2 = new DateTime($s2);

    $diff = $datetime2->diff($datetime1);

    return array('d' => $diff->d, 'm' => $diff->m, 'y' => $diff->y, 'n' => $diff->days);
}

/**
 * Convert string tanggal ke timestamp Unix
 * @param string $s Tanggal dalam format dd/mm/yyyy atau dd-mm-yyyy
 * @return int|false Unix timestamp atau false jika invalid
 */
function to_date($s)
{
    if (empty($s)) {
        return false;
    }

    $parts = preg_split('/[-\/]/', $s);

    // Pastikan hasil split ada 3 elemen (day, month, year)
    if (count($parts) !== 3) {
        return false;
    }

    list($d, $m, $y) = $parts;

    // Convert to integer untuk checkdate
    $d = (int)$d;
    $m = (int)$m;
    $y = (int)$y;

    if (checkdate($m, $d, $y)) {
        return mktime(0, 0, 0, $m, $d, $y);
    }

    return false;
}

/**
 * Tambah tahun ke tanggal
 * @param string $s Tanggal dalam format dd/mm/yyyy
 * @param int $n Jumlah tahun yang ditambahkan (bisa negatif)
 * @return string|false Tanggal baru atau false jika invalid
 */
function addyear($s, $n)
{
    if (empty($s)) {
        return false;
    }

    $parts = preg_split('/[-\/]/', $s);

    if (count($parts) !== 3) {
        return false;
    }

    list($d, $m, $y) = $parts;

    $d = (int)$d;
    $m = (int)$m;
    $y = (int)$y;

    if (checkdate($m, $d, $y)) {
        return date('d/m/Y', mktime(0, 0, 0, $m, $d, $y + $n));
    }

    return false;
}

/**
 * Tambah bulan ke tanggal
 * @param string $s Tanggal dalam format dd/mm/yyyy
 * @param int $n Jumlah bulan yang ditambahkan (bisa negatif)
 * @return string|false Tanggal baru atau false jika invalid
 */
function addmonth($s, $n)
{
    if (empty($s)) {
        return false;
    }

    $parts = preg_split('/[-\/]/', $s);

    if (count($parts) !== 3) {
        return false;
    }

    list($d, $m, $y) = $parts;

    $d = (int)$d;
    $m = (int)$m;
    $y = (int)$y;

    if (checkdate($m, $d, $y)) {
        return date('d/m/Y', mktime(0, 0, 0, $m + $n, $d, $y));
    }

    return false;
}

/**
 * Ambil tahun dari tanggal
 * @param string $s Tanggal dalam format dd/mm/yyyy
 * @return int|false Tahun atau false jika invalid
 */
function year($s)
{
    if (empty($s)) {
        return false;
    }

    $parts = preg_split('/[-\/]/', $s);

    if (count($parts) !== 3) {
        return false;
    }

    list($d, $m, $y) = $parts;

    $d = (int)$d;
    $m = (int)$m;
    $y = (int)$y;

    if (checkdate($m, $d, $y)) {
        return $y;
    }

    return false;
}

function month($s)
{
    list($d, $m, $y) = preg_split('/[-\/]/', $s);
    if (checkdate($m, $d, $y)) return $m;
    else
        return false;
}

function day($s)
{
    list($d, $m, $y) = preg_split('/[-\/]/', $s);
    if (checkdate($m, $d, $y)) return $d;
    else
        return false;
}

function max_hari($m, $y)
{
    if ($m == 12) return 31;
    else
        return date('d', mktime(0, 0, 0, $m + 1, 1, $y) - 1);
}

/**
 * Generate model string dari merek, model, dan jenis kendaraan
 * @param string $nm_merek_kb Nama merek kendaraan
 * @param string $nm_model_kb Nama model kendaraan
 * @param string $nm_jenis_kb Nama jenis kendaraan
 * @return string String model yang sudah di-strip
 */
function setmodel($nm_merek_kb, $nm_model_kb, $nm_jenis_kb)
{
    $s = strip_mrk($nm_merek_kb) . strip_mrk($nm_model_kb) . strip_mrk($nm_jenis_kb);
    return $s;
}

/**
 * Strip dan normalize string merek/model kendaraan
 * Menghapus spasi, tanda baca, dan normalize singkatan
 * @param string $s String yang akan di-strip
 * @return string String yang sudah di-normalize
 */
function strip_mrk($s)
{
    $s = strtoupper(trim($s));
    $s = str_replace(' ', '', $s);

    // Replace ereg_replace() yang deprecated dengan preg_replace()
    // [:punct:] = semua karakter tanda baca
    $s = preg_replace('/[[:punct:]]/', '', $s);

    // Normalize common abbreviations
    $s = str_replace('MANUAL', 'MT', $s);
    $s = str_replace('AUTOMATIC', 'AT', $s);
    $s = str_replace('CC', '', $s);

    return $s;
}
?>
<!DOCTYPE html>
<html lang="id" class="no-js">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi PKB - SAMSAT Online Jambi</title>
    <meta name="description" content="Sistem Informasi Pajak Kendaraan Bermotor (PKB) - SAMSAT Jambi" />
    <meta name="author" content="Dipenda Prov. Jambi" />

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        'fadeIn': 'fadeIn 0.6s ease-out',
                        'slideInLeft': 'slideInLeft 0.6s ease-out',
                        'pulse': 'pulse 2s infinite',
                        'shimmer': 'shimmer 3s infinite',
                        'bounce': 'bounce 2s infinite'
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': {
                                opacity: '0',
                                transform: 'translateY(20px)'
                            },
                            '100%': {
                                opacity: '1',
                                transform: 'translateY(0)'
                            }
                        },
                        slideInLeft: {
                            '0%': {
                                opacity: '0',
                                transform: 'translateX(-30px)'
                            },
                            '100%': {
                                opacity: '1',
                                transform: 'translateX(0)'
                            }
                        },
                        shimmer: {
                            '0%': {
                                backgroundPosition: '-1000px 0'
                            },
                            '100%': {
                                backgroundPosition: '1000px 0'
                            }
                        }
                    }
                }
            }
        }
    </script>

    <style>
        /* Hanya animasi yang tidak bisa digantikan Tailwind */
        @keyframes shimmer {
            0% {
                background-position: -1000px 0;
            }

            100% {
                background-position: 1000px 0;
            }
        }

        .shimmer-effect::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transform: rotate(45deg);
            animation: shimmer 3s infinite;
        }
    </style>

    <!-- jQuery & Bootstrap JS -->
    <script src="../assets/js/jquery.min.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>

    <script>
        function set_kd_merek_kb(v) {
            document.getElementById("kd_merek_kb").value = v;
            document.getElementById("kd_dipilih").innerHTML = "Kode Merek: " + v;
        }

        $(document).ready(function() {
            // Add smooth scroll behavior
            $('html').css('scroll-behavior', 'smooth');

            // Toggle Detail SWDKLLJ with enhanced animation
            $('#det_swd').hide();
            $('#show_det_swd').click(function() {
                $(this).addClass('opacity-70');

                $('#det_swd').slideToggle(500, 'swing', function() {
                    $('#show_det_swd').removeClass('opacity-70');
                });

                if ($(this).text().includes('TUTUP')) {
                    $(this).html('📊 LIHAT RINCIAN SWDKLLJ PER TAHUN');
                } else {
                    $(this).html('🔼 TUTUP RINCIAN SWDKLLJ');
                    // Scroll to the detail section
                    $('html, body').animate({
                        scrollTop: $('#det_swd').offset().top - 20
                    }, 600);
                }
            });

            // Toggle Detail PKB with enhanced animation
            $('#det_pkb').hide();
            $('#show_det_pkb').click(function() {
                $(this).addClass('opacity-70');

                $('#det_pkb').slideToggle(500, 'swing', function() {
                    $('#show_det_pkb').removeClass('opacity-70');
                });

                if ($(this).text().includes('TUTUP')) {
                    $(this).html('📊 LIHAT RINCIAN PKB PER TAHUN');
                } else {
                    $(this).html('🔼 TUTUP RINCIAN PKB');
                    // Scroll to the detail section
                    $('html, body').animate({
                        scrollTop: $('#det_pkb').offset().top - 20
                    }, 600);
                }
            });

            // Add hover effects to cards - tidak perlu lagi karena sudah di Tailwind

            // Add click animation to buttons - tidak perlu lagi karena sudah di Tailwind

            // Animate numbers on load
            $('.text-5xl, .text-lg').each(function() {
                var $this = $(this);
                $this.css('opacity', '0').animate({
                    opacity: 1
                }, 1000);
            });

            // Add ripple effect to detail rows - tidak perlu lagi karena sudah di Tailwind

            // Modal Reset
            $('#form-content').on('hidden.bs.modal', function() {
                $(this).find('form').trigger('reset');
                $("#koding").html('');
            });

            // Pilih Kode
            $("#pilih").click(function() {
                $("#form-content").modal('hide');
                $("#contact").attr('action', "/infopkb.php").submit();
            });

            // Cari Kode with loading indicator
            $("input#cari").click(function() {
                var $btn = $(this);
                var originalText = $btn.val();
                $btn.val('⏳ Mencari...').prop('disabled', true);

                $.ajax({
                    type: "POST",
                    url: "carikode.php",
                    data: $('form.contact').serialize(),
                    success: function(msg) {
                        $("#koding").html(msg).hide().fadeIn(400);
                        $btn.val(originalText).prop('disabled', false);
                    },
                    error: function() {
                        alert("❌ Gagal mengambil data kode merek. Silakan coba lagi.");
                        $btn.val(originalText).prop('disabled', false);
                    }
                });
            });

            // Add fade-in animation to all cards on scroll - tidak perlu lagi karena sudah di Tailwind

            // Initialize tooltips if present
            if (typeof $().tooltip === 'function') {
                $('[data-toggle="tooltip"]').tooltip();
            }

            // Add loading animation on page load
            $('.max-w-7xl').first().css('opacity', '0').animate({
                opacity: 1
            }, 800);
        });
    </script>

    <?php
    $f16 = "16px;";
    $f18 = "18px;";
    $f20 = "20px;";
    $f24 = "24px;";

    if ($detect->isMobile()) {
        $f16 = "12px;";
        $f18 = "14px;";
        $f20 = "16px;";
        $f24 = "20px;";
    }
    ?>
</head>

<body class="bg-gradient-to-br from-indigo-500 via-purple-500 to-purple-600 min-h-screen font-sans">
    <!-- Header Navigation -->
    <div class="bg-slate-600 py-4 mb-5 shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <a href="infopkb.html" class="inline-block bg-white text-slate-700 font-semibold py-3 px-6 rounded-lg shadow-md hover:bg-slate-50 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 no-underline">
                <strong>← Menu Utama</strong>
            </a>
            <span class="text-white ml-5 text-lg font-semibold align-middle">
                SAMSAT Online Jambi
            </span>
        </div>
    </div>

    <div class="max-w-7xl mx-auto my-5 bg-white p-8 rounded-2xl shadow-2xl animate-fadeIn">

        <?php
        if (!$found) {
            echo '<div class="bg-red-50 border-2 border-red-500 rounded-xl p-10 text-center" role="alert">';
            echo '<h2 class="text-red-600 text-3xl font-bold mb-4">❌ DATA TIDAK DITEMUKAN</h2>';
            echo '<p class="text-base text-gray-700">Nomor polisi yang Anda cari tidak ditemukan dalam database.</p>';
            echo '<a href="index.html" class="inline-block mt-5 bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors no-underline">Kembali ke Menu Utama</a>';
            echo '</div>';
            exit;
        }
        ?>

        <!-- ============================================
        INFORMASI KENDARAAN
        ============================================ -->

        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white p-8 rounded-xl mb-8 shadow-xl relative overflow-hidden animate-slideInLeft">
            <div class="shimmer-effect"></div>
            <h2 class="text-3xl font-semibold m-0 relative z-10">📋 Informasi Kendaraan</h2>
            <div class="text-5xl font-bold tracking-widest mt-2 relative z-10 drop-shadow-lg"><?php echo $no_polisi ?></div>
        </div>

        <!-- Container Responsif untuk Data Kendaraan dan Riwayat Pembayaran -->
        <div class="flex flex-col lg:flex-row gap-4 lg:gap-6 mb-6">
            <!-- Data Kendaraan Card -->
            <div class="flex-1 bg-white border border-gray-200 rounded-xl p-3 sm:p-4 md:p-6 shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group animate-fadeIn">
                <div class="absolute top-0 left-0 w-1 h-0 bg-gradient-to-b from-indigo-500 to-purple-600 group-hover:h-full transition-all duration-300"></div>
                <h3 class="text-slate-800 text-lg sm:text-xl md:text-2xl font-bold mb-4 md:mb-5 pb-2 md:pb-3 border-b-2 border-gray-200 relative inline-block after:content-[''] after:absolute after:bottom-[-2px] after:left-0 after:w-12 md:after:w-16 after:h-[2px] after:bg-gradient-to-r after:from-indigo-500 after:to-purple-600 after:rounded-full">🚗 Data Kendaraan</h3>
                <div class="overflow-x-auto -mx-3 sm:mx-0">
                    <table class="w-full min-w-[300px]">
                        <tbody>
                            <tr class="border-b border-gray-100 hover:bg-slate-50 transition-colors">
                                <td class="py-2.5 sm:py-3.5 px-2 font-semibold text-slate-600 w-32 sm:w-40 text-xs sm:text-sm md:text-base">Merek</td>
                                <td class="py-2.5 sm:py-3.5 px-2 w-5 text-center text-slate-400">:</td>
                                <td class="py-2.5 sm:py-3.5 px-2 text-slate-800 font-semibold text-xs sm:text-sm md:text-base"><?php echo $result['nm_merek_kb']; ?></td>
                            </tr>
                            <tr class="border-b border-gray-100 hover:bg-slate-50 transition-colors">
                                <td class="py-2.5 sm:py-3.5 px-2 font-semibold text-slate-600 text-xs sm:text-sm md:text-base">Model / Tipe</td>
                                <td class="py-2.5 sm:py-3.5 px-2 text-center text-slate-400">:</td>
                                <td class="py-2.5 sm:py-3.5 px-2 text-slate-800 font-semibold text-xs sm:text-sm md:text-base"><?php echo $result['nm_model_kb']; ?></td>
                            </tr>
                            <tr class="border-b border-gray-100 hover:bg-slate-50 transition-colors">
                                <td class="py-2.5 sm:py-3.5 px-2 font-semibold text-slate-600 text-xs sm:text-sm md:text-base">Jenis</td>
                                <td class="py-2.5 sm:py-3.5 px-2 text-center text-slate-400">:</td>
                                <td class="py-2.5 sm:py-3.5 px-2 text-slate-800 font-semibold text-xs sm:text-sm md:text-base"><?php echo $result['nm_jenis_kb']; ?></td>
                            </tr>
                            <tr class="border-b border-gray-100 hover:bg-slate-50 transition-colors">
                                <td class="py-2.5 sm:py-3.5 px-2 font-semibold text-slate-600 text-xs sm:text-sm md:text-base">Tahun Rakit</td>
                                <td class="py-2.5 sm:py-3.5 px-2 text-center text-slate-400">:</td>
                                <td class="py-2.5 sm:py-3.5 px-2 text-slate-800 font-semibold text-xs sm:text-sm md:text-base"><?php echo $result['th_rakitan']; ?></td>
                            </tr>
                            <tr class="border-b border-gray-100 hover:bg-slate-50 transition-colors">
                                <td class="py-2.5 sm:py-3.5 px-2 font-semibold text-slate-600 text-xs sm:text-sm md:text-base">Kapasitas Mesin</td>
                                <td class="py-2.5 sm:py-3.5 px-2 text-center text-slate-400">:</td>
                                <td class="py-2.5 sm:py-3.5 px-2 text-slate-800 font-bold text-xs sm:text-sm md:text-base"><?php echo $result['jumlah_cc'] . " cc"; ?></td>
                            </tr>
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="py-2.5 sm:py-3.5 px-2 font-semibold text-slate-600 text-xs sm:text-sm md:text-base">Warna</td>
                                <td class="py-2.5 sm:py-3.5 px-2 text-center text-slate-400">:</td>
                                <td class="py-2.5 sm:py-3.5 px-2 text-slate-800 font-semibold text-xs sm:text-sm md:text-base"><?php echo $result['warna_kb']; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Riwayat Pembayaran Card -->
            <div class="flex-1 bg-white border border-gray-200 rounded-xl p-3 sm:p-4 md:p-6 shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group animate-fadeIn">
                <div class="absolute top-0 left-0 w-1 h-0 bg-gradient-to-b from-indigo-500 to-purple-600 group-hover:h-full transition-all duration-300"></div>
                <h3 class="text-slate-800 text-lg sm:text-xl md:text-2xl font-bold mb-4 md:mb-5 pb-2 md:pb-3 border-b-2 border-gray-200 relative inline-block after:content-[''] after:absolute after:bottom-[-2px] after:left-0 after:w-12 md:after:w-16 after:h-[2px] after:bg-gradient-to-r after:from-indigo-500 after:to-purple-600 after:rounded-full">📅 Riwayat Pembayaran Terakhir</h3>
                <div class="overflow-x-auto -mx-3 sm:mx-0">
                    <table class="w-full min-w-[300px]">
                        <tbody>
                            <tr class="border-b border-gray-100 hover:bg-slate-50 transition-colors">
                                <td class="py-2.5 sm:py-3.5 px-2 font-semibold text-slate-600 w-32 sm:w-40 text-xs sm:text-sm md:text-base">Tanggal Bayar Terakhir</td>
                                <td class="py-2.5 sm:py-3.5 px-2 w-5 text-center text-slate-400">:</td>
                                <td class="py-2.5 sm:py-3.5 px-2"><span class="inline-block bg-gradient-to-r from-red-200 to-red-100 text-red-700 text-sm sm:text-base md:text-lg font-bold px-2 sm:px-3 py-1 rounded-md animate-pulse"><?php echo $result['tg_bayar']; ?></span></td>
                            </tr>
                            <tr class="border-b border-gray-100 hover:bg-slate-50 transition-colors">
                                <td class="py-2.5 sm:py-3.5 px-2 font-semibold text-slate-600 text-xs sm:text-sm md:text-base">Lokasi Bayar Terakhir</td>
                                <td class="py-2.5 sm:py-3.5 px-2 text-center text-slate-400">:</td>
                                <td class="py-2.5 sm:py-3.5 px-2 text-slate-800 font-bold text-xs sm:text-sm md:text-base"><?php echo $result['nm_lokasi']; ?></td>
                            </tr>
                            <tr class="border-b border-gray-100 hover:bg-slate-50 transition-colors">
                                <td class="py-2.5 sm:py-3.5 px-2 font-semibold text-slate-600 text-xs sm:text-sm md:text-base">Tgl. Akhir PKB Terakhir</td>
                                <td class="py-2.5 sm:py-3.5 px-2 text-center text-slate-400">:</td>
                                <td class="py-2.5 sm:py-3.5 px-2"><span class="inline-block bg-gradient-to-r from-red-200 to-red-100 text-red-700 text-sm sm:text-base md:text-lg font-bold px-2 sm:px-3 py-1 rounded-md animate-pulse"><?php echo $result['tg_akhir_pkb']; ?></span></td>
                            </tr>
                            <tr class="hover:bg-slate-50 transition-colors">
                                <?php
                                // Jika tanggal akhir STNK tidak ada, set ke dash
                                if (!to_date($datakb['tg_akhir_stnk'])) {
                                    $datakb['tg_akhir_stnk'] = "-";
                                }
                                ?>
                                <td class="py-2.5 sm:py-3.5 px-2 font-semibold text-slate-600 text-xs sm:text-sm md:text-base">Tgl. Akhir STNK</td>
                                <td class="py-2.5 sm:py-3.5 px-2 text-center text-slate-400">:</td>
                                <td class="py-2.5 sm:py-3.5 px-2"><span class="inline-block bg-gradient-to-r from-red-200 to-red-100 text-red-700 text-sm sm:text-base md:text-lg font-bold px-2 sm:px-3 py-1 rounded-md animate-pulse"><?php echo $datakb['tg_akhir_stnk']; ?></span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <?php
        if ($error) {
            echo '</div>'; // close container
            echo '<div class="max-w-7xl mx-auto my-5 bg-yellow-50 border-2 border-yellow-500 rounded-xl p-8 shadow-lg">';
            echo "<h3 class='text-yellow-800 text-2xl font-bold'>⚠️ {$errmsg}</h3>";
            if ($errno == -3) {
                echo '<button type="button" class="mt-4 bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors font-semibold text-lg" data-toggle="modal" data-target="#form-content">🔍 Cari Kode Merek</button>';
            }
            echo '</div></body></html>';
            exit;
        }
        ?>

        <!-- ============================================
             PERHITUNGAN BIAYA & TARIF
             ============================================ -->

        <!-- Tarif PKB Card -->
        <div class="bg-white border border-gray-200 rounded-xl p-3 sm:p-4 md:p-6 mb-6 shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group animate-fadeIn">
            <div class="absolute top-0 left-0 w-1 h-0 bg-gradient-to-b from-indigo-500 to-purple-600 group-hover:h-full transition-all duration-300"></div>
            <h3 class="text-slate-800 text-lg sm:text-xl md:text-2xl font-bold mb-4 md:mb-5 pb-2 md:pb-3 border-b-2 border-gray-200 relative inline-block after:content-[''] after:absolute after:bottom-[-2px] after:left-0 after:w-12 md:after:w-16 after:h-[2px] after:bg-gradient-to-r after:from-indigo-500 after:to-purple-600 after:rounded-full">💰 Tarif & Perhitungan Biaya</h3>
            <div class="overflow-x-auto -mx-3 sm:mx-0">
                <table class="w-full min-w-[400px]">
                    <tbody>
                        <tr class="border-b border-gray-100 hover:bg-slate-50 transition-colors">
                            <td class="py-2.5 sm:py-3.5 px-2 font-semibold text-slate-600 w-32 sm:w-52 text-xs sm:text-sm md:text-base">Tarif PKB</td>
                            <td class="py-2.5 sm:py-3.5 px-2 w-5 text-center text-slate-400">:</td>
                            <td class="py-2.5 sm:py-3.5 px-2">
                                <code class="bg-gray-100 px-2 sm:px-3 py-1 sm:py-1.5 rounded text-xs sm:text-sm font-mono block sm:inline-block overflow-x-auto">
                                    <?php
                                    echo str_replace(".", ",", $datakb['pct_trf']) . "% × " .
                                        $datakb['njkb'] . " × " .
                                        $datakb['pct_pkb'] . "%";
                                    ?>
                                </code>
                            </td>
                        </tr>

                        <tr class="bg-gradient-to-r from-yellow-50 to-yellow-100 hover:from-yellow-100 hover:to-yellow-50 transition-colors">
                            <td class="py-2.5 sm:py-3.5 px-2 font-semibold text-yellow-900 text-xs sm:text-sm md:text-base">Tarif OPSEN</td>
                            <td class="py-2.5 sm:py-3.5 px-2 text-center text-slate-400">:</td>
                            <td class="py-2.5 sm:py-3.5 px-2">
                                <?php if ($datakb['opsen_berlaku']) { ?>
                                    <code class="bg-yellow-200 text-yellow-900 px-2 sm:px-3 py-1 sm:py-1.5 rounded text-xs sm:text-sm font-mono font-bold">
                                        PKB × 66%
                                    </code>
                                <?php } else { ?>
                                    <div class="flex flex-col gap-1">
                                        <code class="bg-gray-200 text-gray-600 px-2 sm:px-3 py-1 sm:py-1.5 rounded text-xs sm:text-sm font-mono line-through">
                                            PKB × 66%
                                        </code>
                                        <span class="text-xs text-red-600 font-semibold">
                                            ⚠️ OPSEN berlaku untuk Tanggal PKB Baru ≥ 06/01/2026
                                        </span>
                                    </div>
                                <?php } ?>
                            </td>
                        </tr>

                        <?php if ($datakb['progresif']) { ?>
                            <tr class="border-t border-gray-100 hover:bg-slate-50 transition-colors">
                                <td class="py-2.5 sm:py-3.5 px-2 font-semibold text-slate-600 text-xs sm:text-sm md:text-base">Pajak Progresif</td>
                                <td class="py-2.5 sm:py-3.5 px-2 text-center text-slate-400">:</td>
                                <td class="py-2.5 sm:py-3.5 px-2">
                                    <span class="inline-block bg-yellow-100 text-yellow-900 px-2 sm:px-3 py-1 sm:py-1.5 rounded-lg font-bold text-xs sm:text-sm md:text-base">
                                        ⚠️ <?php echo $datakb['progresif']; ?>
                                    </span>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Rincian Biaya Card -->
        <div class="bg-white border border-gray-200 rounded-xl p-3 sm:p-4 md:p-6 mb-6 shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group animate-fadeIn">
            <div class="absolute top-0 left-0 w-1 h-0 bg-gradient-to-b from-indigo-500 to-purple-600 group-hover:h-full transition-all duration-300"></div>
            <h3 class="text-slate-800 text-lg sm:text-xl md:text-2xl font-bold mb-4 md:mb-5 pb-2 md:pb-3 border-b-2 border-gray-200 relative inline-block after:content-[''] after:absolute after:bottom-[-2px] after:left-0 after:w-12 md:after:w-16 after:h-[2px] after:bg-gradient-to-r after:from-indigo-500 after:to-purple-600 after:rounded-full">📊 Rincian Biaya yang Harus Dibayar</h3>

            <div class="space-y-3 md:space-y-4">
                <!-- Section PKB -->
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg p-3 md:p-4 border-l-4 border-blue-500 shadow-sm">
                    <div class="grid grid-cols-2 sm:grid-cols-[1fr_auto_140px] md:grid-cols-[1fr_auto_200px] gap-2 md:gap-3 mb-2">
                        <div class="font-bold text-blue-900 flex flex-col sm:flex-row sm:items-center text-sm md:text-base">
                            <span class="flex items-center">
                                🚗 PKB
                            </span>
                            <span class="mt-1 sm:mt-0 sm:ml-2 text-xs font-normal text-blue-700 bg-blue-200 px-2 py-0.5 rounded-full inline-block w-fit">(Pajak Kendaraan Bermotor)</span>
                        </div>
                        <div class="hidden sm:block text-blue-900">:</div>
                        <div class="text-left sm:text-right font-bold text-blue-900 text-sm md:text-base">Rp <?php echo $datakb['pokok_pkb']; ?>,-</div>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-[1fr_auto_140px] md:grid-cols-[1fr_auto_200px] gap-2 md:gap-3 pl-4 sm:pl-6 py-1.5 bg-white bg-opacity-50 rounded">
                        <div class="text-xs sm:text-sm font-semibold text-slate-700">• Denda PKB</div>
                        <div class="hidden sm:block text-xs sm:text-sm text-slate-600">:</div>
                        <div class="text-left sm:text-right text-xs sm:text-sm font-semibold text-slate-700">Rp <?php echo $datakb['denda_pkb']; ?>,-</div>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-[1fr_auto_140px] md:grid-cols-[1fr_auto_200px] gap-2 md:gap-3 mt-2 pt-2 border-t-2 border-blue-300">
                        <div class="font-bold text-blue-950 text-sm md:text-base">SUBTOTAL PKB</div>
                        <div class="hidden sm:block font-bold text-blue-950">:</div>
                        <div class="text-left sm:text-right font-bold text-blue-950 text-sm md:text-base">Rp <?php echo $datakb['total_pkb']; ?>,-</div>
                    </div>
                </div>

                <!-- Section OPSEN -->
                <?php if ($datakb['opsen_berlaku']) { ?>
                    <!-- OPSEN Berlaku -->
                    <div class="bg-gradient-to-r from-amber-50 to-amber-100 rounded-lg p-3 md:p-4 border-l-4 border-amber-500 shadow-sm">
                        <div class="grid grid-cols-2 sm:grid-cols-[1fr_auto_140px] md:grid-cols-[1fr_auto_200px] gap-2 md:gap-3 mb-2">
                            <div class="font-bold text-amber-900 flex flex-col sm:flex-row sm:items-center text-sm md:text-base">
                                <span>⚡ OPSEN PKB</span>
                                <span class="mt-1 sm:mt-0 sm:ml-2 text-xs font-normal text-amber-800 bg-amber-200 px-2 py-0.5 rounded-full inline-block w-fit">66% dari PKB</span>
                            </div>
                            <div class="hidden sm:block text-amber-900">:</div>
                            <div class="text-left sm:text-right font-bold text-amber-900 text-sm md:text-base">Rp <?php echo $datakb['pokok_opsen']; ?>,-</div>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-[1fr_auto_140px] md:grid-cols-[1fr_auto_200px] gap-2 md:gap-3 pl-4 sm:pl-6 py-1.5 bg-white bg-opacity-50 rounded">
                            <div class="text-xs sm:text-sm font-semibold text-slate-700">• Denda OPSEN</div>
                            <div class="hidden sm:block text-xs sm:text-sm text-slate-600">:</div>
                            <div class="text-left sm:text-right text-xs sm:text-sm font-semibold text-slate-700">Rp <?php echo $datakb['denda_opsen']; ?>,-</div>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-[1fr_auto_140px] md:grid-cols-[1fr_auto_200px] gap-2 md:gap-3 mt-2 pt-2 border-t-2 border-amber-300">
                            <div class="font-bold text-amber-950 text-sm md:text-base">SUBTOTAL OPSEN</div>
                            <div class="hidden sm:block font-bold text-amber-950">:</div>
                            <div class="text-left sm:text-right font-bold text-amber-950 text-sm md:text-base">Rp <?php echo $datakb['total_opsen']; ?>,-</div>
                        </div>
                    </div>
                <?php } else { ?>
                    <!-- OPSEN Tidak Berlaku -->
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg p-3 md:p-4 border-l-4 border-gray-400 shadow-sm opacity-60">
                        <div class="grid grid-cols-2 sm:grid-cols-[1fr_auto_140px] md:grid-cols-[1fr_auto_200px] gap-2 md:gap-3 mb-2">
                            <div class="font-bold text-gray-600 flex flex-col sm:flex-row sm:items-center text-sm md:text-base">
                                <span class="line-through">⚡ OPSEN PKB</span>
                                <span class="mt-1 sm:mt-0 sm:ml-2 text-xs font-normal text-red-700 bg-red-100 px-2 py-0.5 rounded-full inline-block w-fit">Tidak Berlaku</span>
                            </div>
                            <div class="hidden sm:block text-gray-600">:</div>
                            <div class="text-left sm:text-right font-bold text-gray-600 text-sm md:text-base line-through">Rp 0,-</div>
                        </div>
                        <div class="mt-2 p-2 bg-red-50 border border-red-200 rounded text-xs text-red-700">
                            <strong>ℹ️ Informasi:</strong> OPSEN hanya berlaku untuk kendaraan dengan <strong>Tanggal PKB Baru</strong> pada atau setelah <strong>06 Januari 2026</strong>.
                        </div>
                    </div>
                <?php } ?>

                <!-- Total PKB + OPSEN -->
                <div class="bg-gradient-to-r from-cyan-100 to-cyan-200 rounded-lg p-3 md:p-4 border-2 border-cyan-500 shadow-md">
                    <div class="flex flex-col sm:grid sm:grid-cols-[1fr_auto_140px] md:grid-cols-[1fr_auto_200px] gap-2 md:gap-3">
                        <div class="font-extrabold text-cyan-950 text-base md:text-lg flex items-center justify-center sm:justify-start">
                            <?php if ($datakb['opsen_berlaku']) { ?>
                                🎯 TOTAL PKB + OPSEN
                            <?php } else { ?>
                                🎯 TOTAL PKB
                            <?php } ?>
                        </div>
                        <div class="hidden sm:block font-bold text-cyan-950 text-base md:text-lg">:</div>
                        <div class="text-center sm:text-right font-extrabold text-cyan-950 text-lg md:text-xl">
                            Rp <?php echo $datakb['grand_total_pkb']; ?>,-
                        </div>
                    </div>
                </div>

                <!-- Section Biaya Lainnya -->
                <div class="bg-gradient-to-r from-slate-50 to-slate-100 rounded-lg p-3 md:p-4 border-l-4 border-slate-500 shadow-sm">
                    <div class="space-y-2">
                        <div class="grid grid-cols-2 sm:grid-cols-[1fr_auto_140px] md:grid-cols-[1fr_auto_200px] gap-2 md:gap-3 py-2 hover:bg-white hover:bg-opacity-70 rounded transition-colors">
                            <div class="font-semibold text-slate-700 flex flex-col sm:flex-row sm:items-center text-sm md:text-base">
                                <span>🛡️ SWDKLLJ</span>
                                <span class="mt-1 sm:mt-0 sm:ml-2 text-xs font-normal text-slate-600 inline-block">(Sumbangan Wajib)</span>
                            </div>
                            <div class="hidden sm:block text-slate-600">:</div>
                            <div class="text-left sm:text-right font-bold text-slate-800 text-sm md:text-base">Rp <?php echo $swd; ?>,-</div>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-[1fr_auto_140px] md:grid-cols-[1fr_auto_200px] gap-2 md:gap-3 py-2 hover:bg-white hover:bg-opacity-70 rounded transition-colors">
                            <div class="font-semibold text-slate-700 text-sm md:text-base">📄 PNBP STNK</div>
                            <div class="hidden sm:block text-slate-600">:</div>
                            <div class="text-left sm:text-right font-bold text-slate-800 text-sm md:text-base">Rp <?php echo $stnk; ?>,-</div>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-[1fr_auto_140px] md:grid-cols-[1fr_auto_200px] gap-2 md:gap-3 py-2 hover:bg-white hover:bg-opacity-70 rounded transition-colors">
                            <div class="font-semibold text-slate-700 text-sm md:text-base">
                                <span>🔢 PNBP TNKB</span> <span class="text-xs text-slate-600">(Plat Nomor)</span>
                            </div>
                            <div class="hidden sm:block text-slate-600">:</div>
                            <div class="text-left sm:text-right font-bold text-slate-800 text-sm md:text-base">Rp <?php echo $tnkb; ?>,-</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        // Jika ada pemutihan pajak
        if ($datakb['pemutihan'] == "Y") {
            $awal = $datakb['tot_pkb_awal'] + $datakb['tot_swd_awal'];
            $awal_formatted = number_format($awal, 0, ",", ".");

            $tot_pemutihan = $datakb['jml_pp_pkb'] + $datakb['jml_pp_swd'];
            $pkb_pemutihan = number_format($datakb['jml_pp_pkb'], 0, ",", ".");
            $swd_pemutihan = number_format($datakb['jml_pp_swd'], 0, ",", ".");
            $tot_pemutihan_formatted = number_format($tot_pemutihan, 0, ",", ".");
        ?>

            <!-- Alert Pemutihan -->
            <div class="bg-gradient-to-r from-yellow-100 to-yellow-200 border-2 border-yellow-500 rounded-xl p-6 my-5 text-yellow-900 shadow-lg relative overflow-hidden animate-fadeIn">
                <div class="absolute right-5 top-5 text-6xl opacity-30">🎉</div>
                <h4 class="text-xl font-bold mb-4 m-0">🎉 PROGRAM PEMUTIHAN PAJAK AKTIF!</h4>
                <table class="w-full m-0">
                    <tbody>
                        <tr class="border-b border-yellow-300">
                            <td class="py-2 px-1 w-64">Total Sebelum Pemutihan</td>
                            <td class="py-2 px-1 w-5 text-center">:</td>
                            <td class="py-2 px-1">
                                <span class="line-through text-gray-500">
                                    Rp <?php echo $awal_formatted; ?>,-
                                </span>
                            </td>
                        </tr>
                        <tr class="border-b border-yellow-300">
                            <td class="py-2 px-1 font-bold text-yellow-950">💸 Potongan Pemutihan</td>
                            <td class="py-2 px-1 text-center">:</td>
                            <td class="py-2 px-1 font-bold text-yellow-950">
                                Rp <?php echo $tot_pemutihan_formatted; ?>,-
                            </td>
                        </tr>
                        <tr class="text-sm opacity-90 border-b border-yellow-200">
                            <td class="py-2 px-1 pl-8">• PKB</td>
                            <td class="py-2 px-1 text-center">:</td>
                            <td class="py-2 px-1">Rp <?php echo $pkb_pemutihan; ?>,-</td>
                        </tr>
                        <tr class="text-sm opacity-90">
                            <td class="py-2 px-1 pl-8">• SWDKLLJ</td>
                            <td class="py-2 px-1 text-center">:</td>
                            <td class="py-2 px-1">Rp <?php echo $swd_pemutihan; ?>,-</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        <?php } ?>

        <!-- Total Pembayaran - BIG HIGHLIGHT -->
        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white p-8 rounded-2xl my-6 text-center shadow-2xl relative overflow-hidden animate-fadeIn">
            <div class="shimmer-effect"></div>
            <div class="relative z-10">
                <?php if ($datakb['pemutihan'] == "Y") { ?>
                    <div class="inline-block bg-green-500 text-white px-4 py-1.5 rounded-full text-sm font-bold tracking-wide uppercase shadow-md mb-2.5">
                        <span class="inline-block w-3 h-3 rounded-full bg-green-300 mr-2 animate-pulse"></span>PEMUTIHAN AKTIF
                    </div>
                    <h4 class="text-lg font-semibold mb-3 opacity-95 m-0">💰 TOTAL SETELAH PEMUTIHAN</h4>
                <?php } else { ?>
                    <h4 class="text-lg font-semibold mb-3 opacity-95 m-0">💰 TOTAL YANG HARUS DIBAYAR</h4>
                <?php } ?>
                <div class="text-5xl font-extrabold my-4 drop-shadow-lg tracking-wide">Rp <?php echo $tot; ?>,-</div>
                <div class="inline-block bg-white bg-opacity-20 px-5 py-2.5 rounded-lg mt-2.5 backdrop-blur-sm">
                    <p class="m-0 opacity-95 text-base">
                        📅 Tanggal Akhir PKB Baru: <strong class="text-lg"><?php echo $tg_akhir_yad; ?></strong>
                    </p>
                </div>
            </div>
        </div>

        <!-- Ringkasan Pokok dan Denda -->
        <div class="bg-white border border-gray-200 rounded-xl p-3 sm:p-4 md:p-6 mb-6 shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group animate-fadeIn">
            <div class="absolute top-0 left-0 w-1 h-0 bg-gradient-to-b from-indigo-500 to-purple-600 group-hover:h-full transition-all duration-300"></div>
            <h3 class="text-slate-800 text-lg sm:text-xl md:text-2xl font-bold mb-4 md:mb-5 pb-2 md:pb-3 border-b-2 border-gray-200 relative inline-block after:content-[''] after:absolute after:bottom-[-2px] after:left-0 after:w-12 md:after:w-16 after:h-[2px] after:bg-gradient-to-r after:from-indigo-500 after:to-purple-600 after:rounded-full">📝 <span class="bg-clip-text text-transparent bg-gradient-to-r from-indigo-500 to-purple-600 font-bold">Ringkasan Pokok & Denda</span></h3>

            <!-- Mobile Layout: Card-based -->
            <div class="block sm:hidden space-y-3">
                <!-- 1. PKB Card -->
                <div class="bg-gradient-to-r from-blue-50 to-cyan-100 rounded-lg p-4 border-l-4 border-blue-500 shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="font-bold text-blue-900 text-base flex items-center gap-2">🚗 PKB</h4>
                    </div>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between items-center">
                            <span class="text-slate-600">💵 Pokok</span>
                            <span class="font-semibold text-slate-800">Rp <?php echo $datakb['pokok_pkb']; ?>,-</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-600">⚠️ Denda</span>
                            <span class="font-semibold text-slate-800">Rp <?php echo $datakb['denda_pkb']; ?>,-</span>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t-2 border-blue-300">
                            <span class="font-bold text-blue-950">💰 Total</span>
                            <span class="font-bold text-blue-950">Rp <?php echo $datakb['total_pkb']; ?>,-</span>
                        </div>
                    </div>
                </div>

                <!-- 2. SWDKLLJ Card -->
                <div class="bg-gradient-to-r from-green-50 to-emerald-100 rounded-lg p-4 border-l-4 border-green-500 shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="font-bold text-green-900 text-base flex items-center gap-2">🛡️ SWDKLLJ</h4>
                    </div>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between items-center">
                            <span class="text-slate-600">💵 Pokok</span>
                            <span class="font-semibold text-slate-800">Rp <?php echo $datakb['pokok_swd']; ?>,-</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-600">⚠️ Denda</span>
                            <span class="font-semibold text-slate-800">Rp <?php echo $datakb['denda_swd']; ?>,-</span>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t-2 border-green-300">
                            <span class="font-bold text-green-950">💰 Total</span>
                            <span class="font-bold text-green-950">Rp <?php echo $datakb['total_swd']; ?>,-</span>
                        </div>
                    </div>
                </div>

                <!-- 3. OPSEN Card -->
                <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-lg p-4 border-l-4 border-amber-500 shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="font-bold text-amber-900 text-base flex items-center gap-2">⚡ OPSEN (66%)</h4>
                    </div>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between items-center">
                            <span class="text-slate-600">💵 Pokok</span>
                            <span class="font-semibold text-slate-800">Rp <?php echo $datakb['pokok_opsen']; ?>,-</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-600">⚠️ Denda</span>
                            <span class="font-semibold text-slate-800">Rp <?php echo $datakb['denda_opsen']; ?>,-</span>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t-2 border-amber-300">
                            <span class="font-bold text-amber-950">💰 Total</span>
                            <span class="font-bold text-amber-950">Rp <?php echo $datakb['total_opsen']; ?>,-</span>
                        </div>
                    </div>
                </div>

                <!-- 4. Total PKB+OPSEN Card -->
                <div class="bg-gradient-to-r from-cyan-100 to-cyan-200 rounded-lg p-4 border-2 border-cyan-500 shadow-md">
                    <div class="flex items-center justify-center mb-3">
                        <h4 class="font-bold text-cyan-950 text-base text-center">🎯 TOTAL PKB + OPSEN</h4>
                    </div>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between items-center">
                            <span class="text-slate-700">💵 Total Pokok</span>
                            <span class="font-semibold text-slate-800">Rp <?php echo number_format($datakb['pok_pkb_akhir'] + $datakb['pok_opsen'], 0, ",", "."); ?>,-</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-700">⚠️ Total Denda</span>
                            <span class="font-semibold text-slate-800">Rp <?php echo number_format($datakb['den_pkb_akhir'] + $datakb['den_opsen'], 0, ",", "."); ?>,-</span>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t-2 border-cyan-400">
                            <span class="font-extrabold text-cyan-950">💰 GRAND TOTAL</span>
                            <span class="font-extrabold text-cyan-950 text-base">Rp <?php echo $datakb['grand_total_pkb']; ?>,-</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tablet & Desktop Layout: Table-based -->
            <div class="hidden sm:block bg-gradient-to-br from-slate-50 to-slate-100 rounded-xl p-4 md:p-5 my-4 md:my-5 border-2 border-slate-200 shadow-md overflow-x-auto">
                <!-- Header -->
                <div class="flex bg-gradient-to-r from-indigo-500 to-purple-600 text-white p-3 md:p-4 rounded-lg font-bold mb-3 shadow-md uppercase tracking-wide text-xs md:text-sm">
                    <div class="flex-1 text-right px-2 md:px-3">💵 Pokok</div>
                    <div class="flex-1 text-right px-2 md:px-3">⚠️ Denda</div>
                    <div class="flex-1 text-right px-2 md:px-3">💰 Total</div>
                    <div class="flex-[1.5] text-left px-2 md:px-3 font-bold">📋 Jenis</div>
                </div>

                <!-- 1. PKB Row -->
                <div class="flex bg-gradient-to-r from-blue-50 to-cyan-100 p-3 md:p-4 border-b border-slate-200 items-center transition-all hover:bg-white hover:translate-x-1 hover:shadow-md relative before:content-[''] before:absolute before:left-0 before:top-0 before:w-1 before:h-full before:bg-gradient-to-b before:from-indigo-500 before:to-purple-600 before:scale-y-0 hover:before:scale-y-100 before:transition-transform border-l-4 border-blue-500">
                    <div class="flex-1 text-right px-2 md:px-3 font-semibold text-slate-700 text-sm md:text-base">Rp <?php echo $datakb['pokok_pkb']; ?>,-</div>
                    <div class="flex-1 text-right px-2 md:px-3 font-semibold text-slate-700 text-sm md:text-base">Rp <?php echo $datakb['denda_pkb']; ?>,-</div>
                    <div class="flex-1 text-right px-2 md:px-3 font-bold text-slate-800 text-sm md:text-base">Rp <?php echo $datakb['total_pkb']; ?>,-</div>
                    <div class="flex-[1.5] text-left px-2 md:px-3 font-bold text-slate-900 text-sm md:text-base">🚗 PKB</div>
                </div>

                <!-- 2. SWDKLLJ Row -->
                <div class="flex bg-gradient-to-r from-green-50 to-emerald-100 p-3 md:p-4 border-b border-slate-200 items-center transition-all hover:bg-white hover:translate-x-1 hover:shadow-md relative before:content-[''] before:absolute before:left-0 before:top-0 before:w-1 before:h-full before:bg-gradient-to-b before:from-indigo-500 before:to-purple-600 before:scale-y-0 hover:before:scale-y-100 before:transition-transform border-l-4 border-green-500">
                    <div class="flex-1 text-right px-2 md:px-3 font-semibold text-slate-700 text-sm md:text-base">Rp <?php echo $datakb['pokok_swd']; ?>,-</div>
                    <div class="flex-1 text-right px-2 md:px-3 font-semibold text-slate-700 text-sm md:text-base">Rp <?php echo $datakb['denda_swd']; ?>,-</div>
                    <div class="flex-1 text-right px-2 md:px-3 font-bold text-slate-800 text-sm md:text-base">Rp <?php echo $datakb['total_swd']; ?>,-</div>
                    <div class="flex-[1.5] text-left px-2 md:px-3 font-bold text-slate-900 text-sm md:text-base">🛡️ SWDKLLJ</div>
                </div>

                <!-- 3. OPSEN Row -->
                <div class="flex bg-gradient-to-r from-yellow-100 to-yellow-300 p-3 md:p-4 border-b border-slate-200 items-center transition-all hover:bg-white hover:translate-x-1 hover:shadow-md relative before:content-[''] before:absolute before:left-0 before:top-0 before:w-1 before:h-full before:bg-gradient-to-b before:from-indigo-500 before:to-purple-600 before:scale-y-0 hover:before:scale-y-100 before:transition-transform border-l-4 border-yellow-500">
                    <div class="flex-1 text-right px-2 md:px-3 font-semibold text-slate-700 text-sm md:text-base">Rp <?php echo $datakb['pokok_opsen']; ?>,-</div>
                    <div class="flex-1 text-right px-2 md:px-3 font-semibold text-slate-700 text-sm md:text-base">Rp <?php echo $datakb['denda_opsen']; ?>,-</div>
                    <div class="flex-1 text-right px-2 md:px-3 font-bold text-slate-800 text-sm md:text-base">Rp <?php echo $datakb['total_opsen']; ?>,-</div>
                    <div class="flex-[1.5] text-left px-2 md:px-3 font-bold text-slate-900 text-sm md:text-base">⚡ OPSEN (66%)</div>
                </div>

                <!-- 4. Total PKB+OPSEN Row -->
                <div class="flex bg-gradient-to-r from-blue-200 to-blue-300 p-3 md:p-4 border-b-0 border-t-2 border-blue-500 items-center transition-all hover:bg-white hover:translate-x-1 hover:shadow-md relative before:content-[''] before:absolute before:left-0 before:top-0 before:w-1 before:h-full before:bg-gradient-to-b before:from-indigo-500 before:to-purple-600 before:scale-y-0 hover:before:scale-y-100 before:transition-transform border-l-4 border-blue-800 font-bold text-sm md:text-base mt-2">
                    <div class="flex-1 text-right px-2 md:px-3 text-slate-800">Rp <?php echo number_format($datakb['pok_pkb_akhir'] + $datakb['pok_opsen'], 0, ",", "."); ?>,-</div>
                    <div class="flex-1 text-right px-2 md:px-3 text-slate-800">Rp <?php echo number_format($datakb['den_pkb_akhir'] + $datakb['den_opsen'], 0, ",", "."); ?>,-</div>
                    <div class="flex-1 text-right px-2 md:px-3 text-base md:text-lg font-bold text-slate-900">Rp <?php echo $datakb['grand_total_pkb']; ?>,-</div>
                    <div class="flex-[1.5] text-left px-2 md:px-3 bg-gradient-to-r from-blue-800 to-blue-900 text-white py-2 px-3 rounded-md shadow-md">🎯 TOTAL PKB+OPSEN</div>
                </div>
            </div>
        </div>

        <!-- Toggle Buttons Container (Responsive: Stack on mobile/tablet, Side-by-side on desktop) -->
        <div class="flex flex-col md:flex-row gap-4 md:gap-6 my-10">
            <!-- Button untuk Toggle Detail PKB -->
            <div class="flex-1 text-center relative">
                <div class="inline-block relative w-full md:w-auto">
                    <button id="show_det_pkb" class="w-full md:w-auto bg-gradient-to-r from-indigo-500 to-purple-600 text-white border-0 px-10 py-4 rounded-full font-bold text-base cursor-pointer transition-all duration-300 shadow-lg hover:shadow-2xl hover:-translate-y-1 hover:scale-105 active:translate-y-0 active:scale-95 uppercase tracking-widest relative overflow-hidden">
                        <span class="inline-block animate-bounce">📊</span> LIHAT RINCIAN PKB PER TAHUN
                    </button>
                    <div class="mt-2.5 text-slate-500 text-sm italic">
                        Klik untuk melihat detail pokok, denda, dan OPSEN per tahun
                    </div>
                </div>
            </div>

            <!-- Button untuk Toggle Detail SWDKLLJ -->
            <div class="flex-1 text-center relative">
                <div class="inline-block relative w-full md:w-auto">
                    <button id="show_det_swd" class="w-full md:w-auto bg-gradient-to-r from-indigo-500 to-purple-600 text-white border-0 px-10 py-4 rounded-full font-bold text-base cursor-pointer transition-all duration-300 shadow-lg hover:shadow-2xl hover:-translate-y-1 hover:scale-105 active:translate-y-0 active:scale-95 uppercase tracking-widest relative overflow-hidden">
                        <span class="inline-block animate-bounce">📊</span> LIHAT RINCIAN SWDKLLJ PER TAHUN
                    </button>
                    <div class="mt-2.5 text-slate-500 text-sm italic">
                        Klik untuk melihat detail SWDKLLJ per tahun
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail PKB Section (Hidden by default) -->
        <div id="det_pkb" class="hidden animate-fadeIn">
            <div class="bg-white border border-gray-200 rounded-xl p-6 mb-6 shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group">
                <div class="absolute top-0 left-0 w-1 h-0 bg-gradient-to-b from-indigo-500 to-purple-600 group-hover:h-full transition-all duration-300"></div>
                <h3 class="text-slate-800 text-2xl font-bold mb-5 pb-3 border-b-2 border-gray-200 relative inline-block after:content-[''] after:absolute after:bottom-[-2px] after:left-0 after:w-16 after:h-[2px] after:bg-gradient-to-r after:from-indigo-500 after:to-purple-600 after:rounded-full">📈 Rincian PKB Per Tahun</h3>
                <p class="text-gray-600 mb-5">
                    Berikut adalah rincian Pajak Kendaraan Bermotor (PKB) per tahun termasuk tahun berjalan dan tunggakan.<br>
                </p>

                <div class="bg-gradient-to-br from-slate-50 to-slate-100 rounded-xl p-5 my-5 border-2 border-slate-200 shadow-md overflow-x-auto">
                    <!-- Header Table -->
                    <div class="grid grid-cols-[repeat(6,1fr)_140px] gap-2 bg-gradient-to-r from-indigo-500 to-purple-600 p-3 rounded-lg shadow-md mb-3 min-w-[900px]">
                        <div class="text-center px-2 py-2 bg-blue-500 rounded text-white font-bold text-sm uppercase">Pokok PKB</div>
                        <div class="text-center px-2 py-2 bg-blue-500 rounded text-white font-bold text-sm uppercase">Denda PKB</div>
                        <div class="text-center px-2 py-2 bg-amber-500 rounded text-white font-bold text-sm uppercase">OPSEN</div>
                        <div class="text-center px-2 py-2 bg-amber-500 rounded text-white font-bold text-sm uppercase">Denda OPSEN</div>
                        <div class="text-center px-2 py-2 bg-green-500 rounded text-white font-bold text-sm uppercase">Total PKB</div>
                        <div class="text-center px-2 py-2 bg-green-500 rounded text-white font-bold text-sm uppercase">Total OPSEN</div>
                        <div class="text-center px-2 py-2 bg-slate-600 rounded text-white font-bold text-sm uppercase">Tahun</div>
                    </div>

                    <?php
                    $pkb_pok = $datakb['pkb_pok'];
                    $pkb_den = $datakb['pkb_den'];
                    $pkb_opsen = $datakb['pkb_opsen'];
                    $pkb_den_opsen = $datakb['pkb_den_opsen'];

                    $year_labels = ['Berjalan', 'Tunggakan 1', 'Tunggakan 2', 'Tunggakan 3', 'Tunggakan 4', 'Tunggakan 5'];

                    // Variabel untuk menghitung total
                    $total_pkb_pok = 0;
                    $total_pkb_den = 0;
                    $total_pkb_opsen = 0;
                    $total_pkb_den_opsen = 0;
                    $total_pkb_tot = 0;
                    $total_opsen_tot = 0;
                    $jumlah_baris = 0;

                    // Loop untuk menampilkan data PKB (max 6 tahun: tahun berjalan + 5 tahun tunggakan)
                    for ($i = 0; $i < 6; $i++) {
                        // Validasi: pastikan index ada dalam array sebelum diakses
                        if (!isset($pkb_pok[$i])) $pkb_pok[$i] = 0;
                        if (!isset($pkb_den[$i])) $pkb_den[$i] = 0;
                        if (!isset($pkb_opsen[$i])) $pkb_opsen[$i] = 0;
                        if (!isset($pkb_den_opsen[$i])) $pkb_den_opsen[$i] = 0;

                        // Skip jika semua nilai 0 (tidak menampilkan baris kosong)
                        if ($pkb_pok[$i] == 0 && $pkb_den[$i] == 0 && $pkb_opsen[$i] == 0 && $pkb_den_opsen[$i] == 0) {
                            continue;
                        }

                        // Hitung total per baris
                        $tot_pkb = $pkb_pok[$i] + $pkb_den[$i];
                        $tot_opsen = $pkb_opsen[$i] + $pkb_den_opsen[$i];

                        // Hitung total untuk footer
                        $total_pkb_pok += $pkb_pok[$i];
                        $total_pkb_den += $pkb_den[$i];
                        $total_pkb_opsen += $pkb_opsen[$i];
                        $total_pkb_den_opsen += $pkb_den_opsen[$i];
                        $total_pkb_tot += $tot_pkb;
                        $total_opsen_tot += $tot_opsen;
                        $jumlah_baris++;

                        // Format angka untuk ditampilkan
                        $pok = number_format($pkb_pok[$i], 0, ",", ".");
                        $den = number_format($pkb_den[$i], 0, ",", ".");
                        $ops = number_format($pkb_opsen[$i], 0, ",", ".");
                        $den_ops = number_format($pkb_den_opsen[$i], 0, ",", ".");
                        $tot_pkb_fmt = number_format($tot_pkb, 0, ",", ".");
                        $tot_opsen_fmt = number_format($tot_opsen, 0, ",", ".");

                        // Highlight untuk tahun berjalan
                        $row_class = $i == 0 ? 'bg-blue-50' : 'bg-white hover:bg-slate-50';
                    ?>
                        <div class="grid grid-cols-[repeat(6,1fr)_140px] gap-2 px-3 py-3 border-b border-slate-200 transition-all <?php echo $row_class; ?> min-w-[900px]">
                            <div class="text-right px-2 text-sm font-semibold text-slate-700">Rp <?php echo $pok; ?>,-</div>
                            <div class="text-right px-2 text-sm font-semibold text-slate-700">Rp <?php echo $den; ?>,-</div>
                            <div class="text-right px-2 text-sm font-semibold text-yellow-800 bg-yellow-50 rounded py-1">Rp <?php echo $ops; ?>,-</div>
                            <div class="text-right px-2 text-sm font-semibold text-yellow-800 bg-yellow-50 rounded py-1">Rp <?php echo $den_ops; ?>,-</div>
                            <div class="text-right px-2 text-sm font-bold text-slate-800">Rp <?php echo $tot_pkb_fmt; ?>,-</div>
                            <div class="text-right px-2 text-sm font-bold text-yellow-900 bg-yellow-100 rounded py-1">Rp <?php echo $tot_opsen_fmt; ?>,-</div>
                            <div class="text-center px-2 text-sm font-bold text-slate-700">
                                <?php echo $year_labels[$i]; ?>
                                <?php if ($i == 0) echo '<span class="text-blue-500 ml-1">●</span>'; ?>
                            </div>
                        </div>
                    <?php } ?>

                    <!-- Baris TOTAL -->
                    <?php if ($jumlah_baris > 0) {
                        $grand_total_all = $total_pkb_tot + $total_opsen_tot;
                    ?>
                        <div class="grid grid-cols-[repeat(6,1fr)_140px] gap-2 bg-slate-600 text-white px-3 py-3 font-bold text-base border-t-2 border-slate-700 rounded-b-lg mt-2 min-w-[900px]">
                            <div class="text-right px-2">Rp <?php echo number_format($total_pkb_pok, 0, ",", "."); ?>,-</div>
                            <div class="text-right px-2">Rp <?php echo number_format($total_pkb_den, 0, ",", "."); ?>,-</div>
                            <div class="text-right px-2 bg-amber-500 rounded py-1">Rp <?php echo number_format($total_pkb_opsen, 0, ",", "."); ?>,-</div>
                            <div class="text-right px-2 bg-amber-500 rounded py-1">Rp <?php echo number_format($total_pkb_den_opsen, 0, ",", "."); ?>,-</div>
                            <div class="text-right px-2">Rp <?php echo number_format($total_pkb_tot, 0, ",", "."); ?>,-</div>
                            <div class="text-right px-2 bg-amber-500 rounded py-1">Rp <?php echo number_format($total_opsen_tot, 0, ",", "."); ?>,-</div>
                            <div class="text-center px-2">TOTAL</div>
                        </div>

                        <!-- Baris GRAND TOTAL -->
                        <div class="grid grid-cols-[1fr_140px] gap-2 bg-gradient-to-r from-blue-900 to-blue-800 text-white px-4 py-4 font-bold text-lg border-t-2 border-blue-950 rounded-b-lg mt-1 shadow-lg min-w-[900px]">
                            <div class="text-right pr-5">GRAND TOTAL (PKB + OPSEN)</div>
                            <div class="text-center text-xl">Rp <?php echo number_format($grand_total_all, 0, ",", "."); ?>,-</div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>

        <!-- Detail SWDKLLJ Section (Hidden by default) -->
        <div id="det_swd" class="hidden animate-fadeIn">
            <div class="bg-white border border-gray-200 rounded-xl p-6 mb-6 shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group">
                <div class="absolute top-0 left-0 w-1 h-0 bg-gradient-to-b from-indigo-500 to-purple-600 group-hover:h-full transition-all duration-300"></div>
                <h3 class="text-slate-800 text-2xl font-bold mb-5 pb-3 border-b-2 border-gray-200 relative inline-block after:content-[''] after:absolute after:bottom-[-2px] after:left-0 after:w-16 after:h-[2px] after:bg-gradient-to-r after:from-indigo-500 after:to-purple-600 after:rounded-full">📈 Rincian SWDKLLJ Per Tahun</h3>
                <p class="text-gray-600 mb-5">
                    Berikut adalah rincian Sumbangan Wajib Dana Kecelakaan Lalu Lintas Jalan (SWDKLLJ) per tahun.
                </p>

                <div class="bg-gradient-to-br from-slate-50 to-slate-100 rounded-xl p-5 my-5 border-2 border-slate-200 shadow-md overflow-x-auto">
                    <!-- Header Table -->
                    <div class="grid grid-cols-[1fr_1fr_1fr_140px] gap-2 bg-gradient-to-r from-indigo-500 to-purple-600 p-3 rounded-lg shadow-md mb-3 min-w-[600px]">
                        <div class="text-center px-2 py-2 bg-green-500 rounded text-white font-bold text-sm uppercase">Pokok</div>
                        <div class="text-center px-2 py-2 bg-red-500 rounded text-white font-bold text-sm uppercase">Denda</div>
                        <div class="text-center px-2 py-2 bg-blue-500 rounded text-white font-bold text-sm uppercase">Total</div>
                        <div class="text-center px-2 py-2 bg-slate-600 rounded text-white font-bold text-sm uppercase">Tahun</div>
                    </div>

                    <?php
                    $swd_pok = $datakb['swd_pok'];
                    $swd_den = $datakb['swd_den'];
                    $tahun_labels = ['Berjalan', 'Tunggakan 1', 'Tunggakan 2', 'Tunggakan 3', 'Tunggakan 4', 'Tunggakan 5'];

                    // Variabel untuk menghitung total
                    $total_swd_pok = 0;
                    $total_swd_den = 0;
                    $total_swd_tot = 0;
                    $jumlah_baris_swd = 0;

                    // Loop untuk menampilkan data SWDKLLJ (max 6 tahun: berjalan + 5 tunggakan)
                    for ($i = 0; $i < 6; $i++) {
                        // Validasi: pastikan index ada dalam array sebelum diakses
                        if (!isset($swd_pok[$i])) {
                            $swd_pok[$i] = 0;
                        }
                        if (!isset($swd_den[$i])) {
                            $swd_den[$i] = 0;
                        }

                        // Hitung total
                        $tot_val = $swd_pok[$i] + $swd_den[$i];

                        // Skip jika semua nilai 0 (tidak menampilkan baris kosong)
                        if ($swd_pok[$i] == 0 && $swd_den[$i] == 0 && $tot_val == 0) {
                            continue;
                        }

                        // Hitung total untuk footer
                        $total_swd_pok += $swd_pok[$i];
                        $total_swd_den += $swd_den[$i];
                        $total_swd_tot += $tot_val;
                        $jumlah_baris_swd++;

                        // Format angka
                        $pok = number_format($swd_pok[$i], 0, ",", ".");
                        $den = number_format($swd_den[$i], 0, ",", ".");
                        $tot = number_format($tot_val, 0, ",", ".");

                        // Highlight untuk tahun berjalan
                        $row_class = ($i == 0) ? 'bg-green-50' : 'bg-white hover:bg-slate-50';
                    ?>
                        <div class="grid grid-cols-[1fr_1fr_1fr_140px] gap-2 px-3 py-3 border-b border-slate-200 transition-all <?php echo $row_class; ?> min-w-[600px]">
                            <div class="text-right px-2 text-sm font-semibold text-slate-700">Rp <?php echo $pok; ?>,-</div>
                            <div class="text-right px-2 text-sm font-semibold text-slate-700">Rp <?php echo $den; ?>,-</div>
                            <div class="text-right px-2 text-sm font-bold text-slate-800">Rp <?php echo $tot; ?>,-</div>
                            <div class="text-center px-2 text-sm font-bold text-slate-700">
                                <?php echo $tahun_labels[$i]; ?>
                                <?php if ($i == 0) echo '<span class="text-green-500 ml-1">●</span>'; ?>
                            </div>
                        </div>
                    <?php } ?>

                    <!-- Baris TOTAL -->
                    <?php if ($jumlah_baris_swd > 0) { ?>
                        <div class="grid grid-cols-[1fr_1fr_1fr_140px] gap-2 bg-slate-600 text-white px-3 py-3 font-bold text-base border-t-2 border-slate-700 rounded-b-lg mt-2 min-w-[600px]">
                            <div class="text-right px-2">Rp <?php echo number_format($total_swd_pok, 0, ",", "."); ?>,-</div>
                            <div class="text-right px-2">Rp <?php echo number_format($total_swd_den, 0, ",", "."); ?>,-</div>
                            <div class="text-right px-2">Rp <?php echo number_format($total_swd_tot, 0, ",", "."); ?>,-</div>
                            <div class="text-center px-2">TOTAL SWDKLLJ</div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <!-- Catatan/Footer -->
        <div class="bg-blue-50 border-l-4 border-slate-600 rounded-lg p-6 mt-8 shadow-md">
            <h5 class="text-slate-600 font-bold text-lg mb-3 mt-0 flex items-center gap-2">
                <span class="text-2xl">ℹ️</span> Catatan Penting
            </h5>
            <ul class="mb-0 pl-5 text-slate-600 space-y-2">
                <li class="leading-relaxed">Jika ada selisih/perbedaan perhitungan, maka yang digunakan adalah hasil perhitungan petugas <strong>SAMSAT</strong>.</li>
                <li class="leading-relaxed">Informasi ini bersifat informatif dan dapat berubah sewaktu-waktu.</li>
                <li class="leading-relaxed">Untuk informasi lebih lanjut, silakan hubungi kantor SAMSAT terdekat.</li>
            </ul>
        </div>
        <!-- </div> -->

        <footer class="bg-gradient-to-r from-slate-600 to-slate-800 text-white py-5 mt-10 text-center shadow-2xl">
            <div class="max-w-7xl mx-auto px-4">
                <p class="m-0 text-sm font-medium">
                    &copy; <?php echo date('Y'); ?> SAMSAT Online Provinsi Jambi. Semua hak cipta dilindungi.
                </p>
                <p class="mt-1.5 mb-0 text-xs opacity-85">
                    Untuk pelayanan terbaik, silakan kunjungi kantor SAMSAT terdekat.
                </p>
            </div>
        </footer>

        <script src="../assets/js/ie10-viewport-bug-workaround.js"></script>
</body>

</html>