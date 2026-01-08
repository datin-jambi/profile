<?php
/**
 * CEK-PKB.PHP
 * Aplikasi Cek Informasi Pajak Kendaraan Bermotor (PKB)
 * 
 * Fitur:
 * - Cek informasi kendaraan berdasarkan No. Polisi
 * - Hitung PKB, SWDKLLJ, STNK, dan TNKB
 * - Tampilan modern dan responsive
 * 
 * @author  Refactored Version
 * @date    2026-01-07
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('Asia/Jakarta');

// Include koneksi database
require_once "pgdbtool.php";
require_once "samlib.php";
require_once "Mobile-Detect/Mobile_Detect.php";

$detect = new Mobile_Detect;

// ========================================
// KONFIGURASI
// ========================================
define('APP_NAME', 'j-SAMSAT Provinsi Jambi');
define('APP_TITLE', 'Cek Informasi PKB');

// ========================================
// INISIALISASI VARIABEL
// ========================================
$form_submitted = false;
$data_kendaraan = null;
$hasil_perhitungan = null;
$error_message = null;
$info_message = null;
$debug_info = []; // Array untuk menyimpan informasi debug

// ========================================
// PROSES FORM JIKA ADA SUBMIT
// ========================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['no_polisi'])) {
    $form_submitted = true;
    
    try {
        // Validasi koneksi database
        if (!$dbonl->connected) {
            throw new Exception("Koneksi database gagal! Silakan coba lagi nanti.");
        }
        
        // Ambil dan sanitasi input
        $input_no_polisi = strtoupper(trim($_POST['no_polisi']));
        $input_nama_pemilik = isset($_POST['nm_pemilik']) ? strtoupper(trim($_POST['nm_pemilik'])) : '';
        $input_tgl_akhir_pkb = isset($_POST['tg_akhir_pkb']) ? trim($_POST['tg_akhir_pkb']) : '';
        $input_tgl_akhir_stnk = isset($_POST['tg_akhir_stnk']) ? trim($_POST['tg_akhir_stnk']) : '';
        $input_izin_angkutan = isset($_POST['izin_ang']) && $_POST['izin_ang'] === 'on' ? true : false;
        
        // Format nomor polisi
        $no_polisi = formatNomorPolisi($input_no_polisi);
        
        // Debug: simpan info pencarian
        $debug_info['input_original'] = $input_no_polisi;
        $debug_info['nopol_formatted'] = $no_polisi;
        
        // Cari data kendaraan
        $data_kendaraan = cariDataKendaraan($dbonl, $no_polisi, $input_nama_pemilik, $debug_info);
        
        if ($data_kendaraan) {
            // Override tanggal jika ada input dari user
            if ($input_tgl_akhir_pkb) {
                $data_kendaraan['tg_akhir_pkb'] = formatTanggal($input_tgl_akhir_pkb);
                $data_kendaraan['tg_akhir_jr'] = formatTanggal($input_tgl_akhir_pkb);
            }
            
            if ($input_tgl_akhir_stnk) {
                $data_kendaraan['tg_akhir_stnk'] = formatTanggal($input_tgl_akhir_stnk);
            }
            
            // Hitung biaya
            $hasil_perhitungan = hitungBiayaKendaraan($dbonl, $data_kendaraan, $input_izin_angkutan);
            
        } else {
            $error_message = "Data kendaraan dengan Nomor Polisi <strong>$input_no_polisi</strong> tidak ditemukan.";
            if ($input_nama_pemilik) {
                $error_message .= " dengan nama pemilik yang sesuai.";
            }
            
            // Tambahkan saran jika ada data mirip
            if (!empty($debug_info['similar_results'])) {
                $error_message .= "<br><br><strong>Data mirip yang ditemukan:</strong><ul style='margin-top: 10px;'>";
                foreach ($debug_info['similar_results'] as $similar) {
                    $error_message .= "<li>" . htmlspecialchars($similar['no_polisi']) . " - " . htmlspecialchars($similar['nm_pemilik']) . "</li>";
                }
                $error_message .= "</ul>";
            }
        }
        
    } catch (Exception $e) {
        $error_message = "Error: " . $e->getMessage();
    }
}

// ========================================
// FUNGSI HELPER
// ========================================

/**
 * Pembulatan ke atas per 100
 */
function pembulatan($n) {
    $m = $n;
    if($n > 0){
        $m = round($n / 100) * 100;
        if($m < $n) $m += 100;
    }
    return $m;
}

/**
 * Konversi tanggal dd/mm/yyyy ke timestamp
 */
function to_date($s) {
    list($d, $m, $y) = preg_split('/[-\/]/', $s);
    if(checkdate($m, $d, $y)) return mktime(0, 0, 0, $m, $d, $y);
    else return false;
}

/**
 * Hitung selisih tanggal
 */
function selisih_tgl($s1, $s2) {
    $tgl1 = to_date($s1);
    $tgl2 = to_date($s2);
    
    // not a date?
    if(!$tgl1 || !$tgl2) return array('d' => 0, 'm' => 0, 'y' => 0, 'n' => 0);
    if($tgl2 < $tgl1) return array('d' => 0, 'm' => 0, 'y' => 0, 'n' => 0);
    
    $s1 = toDbDate($s1);
    $s2 = toDbDate($s2);
    
    $datetime1 = new DateTime($s1);
    $datetime2 = new DateTime($s2);
    
    $diff = $datetime2->diff($datetime1);
    
    return array('d' => $diff->d, 'm' => $diff->m, 'y' => $diff->y, 'n' => $diff->days);
}

/**
 * Ambil tarif NJKB dari database (sama seperti infopkb.php)
 */
function gettrfnj($db, $kd_merek_kb, $thn) {
    $sql = "SELECT nilai_jual, bobot FROM t_trf_nj
            WHERE kd_merek_kb = '$kd_merek_kb'
              AND thn = $thn";
    $trfnj = $db->getrow($sql);
    
    if(!$trfnj) {
        // Jika tidak ada, return false untuk fallback ke estimasi
        return false;
    }
    
    return $trfnj;
}

/**
 * Hitung PKB (versi sederhana untuk estimasi)
 */
function hitpkb(&$datakb) {
    global $dbonl;
    
    // Dikecualikan dalam pengenaan PKB
    if($datakb['kd_jen_milik'] == '06' || $datakb['kd_fungsi'] == '10') {
        return 0;
    }
    
    $tahun_kendaraan = $datakb['th_rakitan'];
    $kd_merek_kb = $datakb['kd_merek_kb'];
    
    // Coba ambil dari tabel t_trf_nj (seperti di infopkb.php)
    $trfnj = gettrfnj($dbonl, $kd_merek_kb, $tahun_kendaraan);
    
    if($trfnj && isset($trfnj['nilai_jual']) && isset($trfnj['bobot'])) {
        // Data ditemukan di database
        $nilai_jual = $trfnj['nilai_jual'];
        $bobot = $trfnj['bobot'];
        $njkb = $nilai_jual * $bobot;
        
        // Format NJKB seperti di infopkb.php
        $datakb['njkb'] = "Rp" . number_format($nilai_jual, 0, ',', '.') . 
                          ",- x " . str_replace(".", ",", $bobot);
        $datakb['nilai_njkb'] = $njkb;
        $datakb['bobot_njkb'] = $bobot;
        $datakb['sumber_njkb'] = 'database'; // Tandai bahwa data dari database
    } else {
        // Fallback: Estimasi NJKB berdasarkan CC dan umur kendaraan
        $tahun_sekarang = date('Y');
        $umur = $tahun_sekarang - $tahun_kendaraan;
        $cc = $datakb['jumlah_cc'];
        $base_njkb = 0;
        
        if($datakb['kd_jenis_kb'] == 'R') { // Motor
            if($cc < 150) $base_njkb = 15000000;
            elseif($cc < 250) $base_njkb = 25000000;
            else $base_njkb = 35000000;
        } else { // Mobil
            if($cc < 1500) $base_njkb = 80000000;
            elseif($cc < 2500) $base_njkb = 150000000;
            else $base_njkb = 250000000;
        }
        
        // Depresiasi 10% per tahun
        $njkb = $base_njkb * pow(0.90, min($umur, 10));
        $bobot = 1.00;
        
        // Format NJKB
        $datakb['njkb'] = "Rp" . number_format($njkb, 0, ',', '.') . 
                          ",- x " . number_format($bobot, 2, ',', '.');
        $datakb['nilai_njkb'] = $njkb;
        $datakb['bobot_njkb'] = $bobot;
        $datakb['sumber_njkb'] = 'estimasi'; // Tandai bahwa data estimasi
    }
    
    // Cek apakah menggunakan OPSEN (berlaku untuk tanggal > 5 Januari 2026)
    $tgl_sekarang = date('d/m/Y');
    $tgl_opsen = '05/01/2026';
    $gunakan_opsen = (to_date($tgl_sekarang) > to_date($tgl_opsen));
    
    // Tarif PKB
    if ($gunakan_opsen) {
        // Aturan baru dengan OPSEN (berlaku > 5 Januari 2026)
        $pct_trf = 1.0; // Tarif 1% untuk semua
        $pct_pengenaan = 90.4; // Pengenaan 90.4%
    } else {
        // Aturan lama (sebelum 5 Januari 2026)
        $pct_trf = 1.5; // Default untuk kendaraan pribadi
        $pct_pengenaan = 100;
        
        if($datakb['izin_ang'] == "1") { // Kendaraan umum
            $pct_trf = 1;
            $pct_pengenaan = 30;
        }
    }
    
    // Simpan nilai tarif dan info opsen
    $datakb['pct_trf'] = $pct_trf;
    $datakb['pct_pkb'] = $pct_pengenaan;
    $datakb['gunakan_opsen'] = $gunakan_opsen;
    
    // Gunakan nilai_njkb yang sudah dihitung (dari database atau estimasi)
    $njkb = $datakb['nilai_njkb'];
    
    // Hitung tunggakan
    $tgl_sekarang = date('d/m/Y');
    $tgl_akhir_pkb = $datakb['tg_akhir_pkb'];
    $sel_tgl = selisih_tgl($tgl_akhir_pkb, $tgl_sekarang);
    
    // Tanggal pemberlakuan OPSEN
    $tgl_opsen = '05/01/2026';
    $d_tgl_opsen = to_date($tgl_opsen);
    
    $pkb_pok = [];
    $pkb_den = [];
    $opsen_pok = [];
    $opsen_den = [];
    $opsen_berlaku = []; // Array untuk menyimpan status OPSEN per tahun
    $tgl_periode = []; // Array untuk menyimpan tanggal periode per tahun
    
    for($i = 0; $i <= 5; $i++) {
        $pkb_pok[$i] = 0;
        $pkb_den[$i] = 0;
        $opsen_pok[$i] = 0;
        $opsen_den[$i] = 0;
        $opsen_berlaku[$i] = false;
        $tgl_periode[$i] = '';
    }
    
    // Hitung tanggal periode untuk setiap tahun dan tentukan tarif yang berlaku
    list($d, $m, $y) = preg_split('/[-\/]/', $tgl_akhir_pkb);
    
    if($sel_tgl['n'] > 0) { // Terlambat
        // Tahun berjalan (index 0)
        $tahun_periode_0 = $y + $sel_tgl['y'] + 1;
        $tgl_periode[0] = date('d/m/Y', mktime(0, 0, 0, $m, $d, $tahun_periode_0));
        $d_periode_0 = to_date($tgl_periode[0]);
        $opsen_berlaku[0] = ($d_periode_0 > $d_tgl_opsen);
        
        // Tentukan tarif untuk tahun berjalan
        if ($opsen_berlaku[0]) {
            // Gunakan tarif baru (1% x 90.4%)
            $trfpkb_0 = (1.0/100) * $njkb * (90.4/100);
        } else {
            // Gunakan tarif lama
            $pct_trf_lama = ($datakb['izin_ang'] == "1") ? 1 : 1.5;
            $pct_pengenaan_lama = ($datakb['izin_ang'] == "1") ? 30 : 100;
            $trfpkb_0 = ($pct_trf_lama/100) * $njkb * ($pct_pengenaan_lama/100);
        }
        
        $pkb_pok[0] = $trfpkb_0;
        
        // Denda tahun berjalan
        $d_periode_0_check = to_date($tgl_periode[0]);
        $d_sekarang = to_date($tgl_sekarang);
        
        if ($opsen_berlaku[0]) {
            // Sistem OPSEN: hanya ada denda jika periode sudah lewat
            if ($d_sekarang > $d_periode_0_check) {
                // Periode tahun berjalan sudah lewat, ada denda
                $sel_dari_periode_0 = selisih_tgl($tgl_periode[0], $tgl_sekarang);
                $m_denda = $sel_dari_periode_0['y'] * 12 + $sel_dari_periode_0['m'];
                if($sel_dari_periode_0['d'] > 15) $m_denda++;
                
                // Denda PKB dengan sistem OPSEN = 1% x bulan x PKB
                $pkb_den[0] = (1 / 100) * $m_denda * $trfpkb_0;
                
                // OPSEN
                $opsen_pok[0] = (66 / 100) * $pkb_pok[0];
                // Denda OPSEN = 1% x bulan x OPSEN
                $opsen_den[0] = (1 / 100) * $m_denda * $opsen_pok[0];
            } else {
                // Periode belum jatuh tempo, tidak ada denda
                $pkb_den[0] = 0;
                $opsen_pok[0] = (66 / 100) * $pkb_pok[0];
                $opsen_den[0] = 0;
            }
        } else {
            // Sistem lama: selalu hitung denda dari tgl_akhir_pkb asli
            $m_denda = $sel_tgl['m'];
            if($sel_tgl['d'] > 15) $m_denda++;
            
            // Denda lama = (2 + 2*bulan)% x PKB
            $pkb_den[0] = (2 + ($m_denda * 2))/100 * $trfpkb_0;
        }
        
        // Tunggakan tahun sebelumnya
        $y_tunggakan = $sel_tgl['y'];
        if($y_tunggakan > 5) $y_tunggakan = 5;
        
        for($i = 1; $i <= $y_tunggakan; $i++) {
            // Hitung tanggal periode untuk tahun ini
            $tahun_periode_i = $y + $sel_tgl['y'] - $i + 1;
            $tgl_periode[$i] = date('d/m/Y', mktime(0, 0, 0, $m, $d, $tahun_periode_i));
            $d_periode_i = to_date($tgl_periode[$i]);
            $opsen_berlaku[$i] = ($d_periode_i > $d_tgl_opsen);
            
            // Tentukan tarif untuk tahun tunggakan
            if ($opsen_berlaku[$i]) {
                // Gunakan tarif baru (1% x 90.4%)
                $trfpkb_i = (1.0/100) * $njkb * (90.4/100);
            } else {
                // Gunakan tarif lama
                $pct_trf_lama = ($datakb['izin_ang'] == "1") ? 1 : 1.5;
                $pct_pengenaan_lama = ($datakb['izin_ang'] == "1") ? 30 : 100;
                $trfpkb_i = ($pct_trf_lama/100) * $njkb * ($pct_pengenaan_lama/100);
            }
            
            $pkb_pok[$i] = $trfpkb_i;
            
            // Hitung denda tunggakan
            $d_periode_i_check = to_date($tgl_periode[$i]);
            
            if ($d_sekarang > $d_periode_i_check) {
                // Periode sudah lewat, hitung denda
                if ($opsen_berlaku[$i]) {
                    // Sistem OPSEN: hitung dari tgl_periode[$i] ke sekarang
                    $sel_dari_periode_i = selisih_tgl($tgl_periode[$i], $tgl_sekarang);
                    $m_tunggakan = $sel_dari_periode_i['y'] * 12 + $sel_dari_periode_i['m'];
                    if($sel_dari_periode_i['d'] > 15) $m_tunggakan++;
                    
                    // Denda PKB dengan sistem OPSEN = 1% x bulan x PKB
                    $pkb_den[$i] = (1 / 100) * $m_tunggakan * $trfpkb_i;
                    
                    // OPSEN
                    $opsen_pok[$i] = (66 / 100) * $pkb_pok[$i];
                    // Denda OPSEN = 1% x bulan x OPSEN
                    $opsen_den[$i] = (1 / 100) * $m_tunggakan * $opsen_pok[$i];
                } else {
                    // Sistem lama: hitung dari tgl_akhir_pkb asli, tambah tahun tunggakan
                    $m_tunggakan = $sel_tgl['m'] + ($i * 12);
                    if($sel_tgl['d'] > 15) $m_tunggakan++;
                    
                    // Denda lama = (2 + 2*bulan)% x PKB (max 48%)
                    if($m_tunggakan > 24) $m_tunggakan = 24;
                    $pkb_den[$i] = (2 + ($m_tunggakan * 2))/100 * $trfpkb_i;
                }
            } else {
                // Periode belum jatuh tempo, tidak ada denda
                $pkb_den[$i] = 0;
                
                // OPSEN tanpa denda
                if ($opsen_berlaku[$i]) {
                    $opsen_pok[$i] = (66 / 100) * $pkb_pok[$i];
                    $opsen_den[$i] = 0;
                }
            }
        }
    } else { // Tepat waktu
        $tahun_periode_0 = $y + 1;
        $tgl_periode[0] = date('d/m/Y', mktime(0, 0, 0, $m, $d, $tahun_periode_0));
        $d_periode_0 = to_date($tgl_periode[0]);
        $opsen_berlaku[0] = ($d_periode_0 > $d_tgl_opsen);
        
        // Tentukan tarif untuk pembayaran tepat waktu
        if ($opsen_berlaku[0]) {
            // Gunakan tarif baru (1% x 90.4%)
            $trfpkb_0 = (1.0/100) * $njkb * (90.4/100);
        } else {
            // Gunakan tarif lama
            $pct_trf_lama = ($datakb['izin_ang'] == "1") ? 1 : 1.5;
            $pct_pengenaan_lama = ($datakb['izin_ang'] == "1") ? 30 : 100;
            $trfpkb_0 = ($pct_trf_lama/100) * $njkb * ($pct_pengenaan_lama/100);
        }
        
        $pkb_pok[0] = $trfpkb_0;
        $pkb_den[0] = 0;
        
        // OPSEN untuk pembayaran tepat waktu (jika berlaku)
        if ($opsen_berlaku[0]) {
            $opsen_pok[0] = (66 / 100) * $pkb_pok[0];
            $opsen_den[0] = 0;
        }
    }
    
    // Kalo ada pemutihan (SEBELUM pembulatan)
    if ($datakb['pemutihan'] == "Y") {
        $set_pp = $datakb['set_pp'];
        
        // Simpan nilai sebelum pemutihan untuk informasi
        $pok_pkb_awal = 0;
        foreach($pkb_pok as $value) {
            $pok_pkb_awal += pembulatan($value);
        }
        
        $den_pkb_awal = 0;
        foreach($pkb_den as $value) {
            $den_pkb_awal += pembulatan($value);
        }
        
        $datakb['pok_pkb_awal'] = $pok_pkb_awal;
        $datakb['den_pkb_awal'] = $den_pkb_awal;
        $datakb['tot_pkb_awal'] = $pok_pkb_awal + $den_pkb_awal;
        
        // Pemutihan pokok PKB
        switch($set_pp['pokok_pkb']) {
            case "0":
                // Hapus semua pokok PKB
                for($i = 0; $i <= 5; $i++) {
                    $pkb_pok[$i] = 0;
                }
                break;
            
            case "1":
                // Hapus pokok PKB tahun ke-1 s/d ke-5
                for($i = 1; $i <= 5; $i++) {
                    $pkb_pok[$i] = 0;
                }
                break;
            
            case "2":
                // Tidak ada pemutihan pokok
                break;
            
            case "3":
                // Hapus pokok PKB tahun ke-2 s/d ke-5
                for($i = 2; $i <= 5; $i++) {
                    $pkb_pok[$i] = 0;
                }
                break;
            
            case "4":
                // Hapus pokok PKB tahun ke-3 s/d ke-5
                for($i = 3; $i <= 5; $i++) {
                    $pkb_pok[$i] = 0;
                }
                break;
            
            case "5":
                // Hapus pokok PKB tahun ke-5
                $pkb_pok[5] = 0;
                break;
        }
        
        // Pemutihan denda PKB
        switch($set_pp['denda_pkb']) {
            case "0":
                // Hapus semua denda PKB
                for($i = 0; $i <= 5; $i++) {
                    $pkb_den[$i] = 0;
                }
                break;
            
            case "1":
                // Hapus denda PKB tahun ke-1 s/d ke-5
                for($i = 1; $i <= 5; $i++) {
                    $pkb_den[$i] = 0;
                }
                break;
            
            case "2":
                // Hapus denda PKB tahun ke-2 s/d ke-5
                for($i = 2; $i <= 5; $i++) {
                    $pkb_den[$i] = 0;
                }
                break;
            
            case "3":
                // Hapus denda PKB tahun ke-3 s/d ke-5
                for($i = 3; $i <= 5; $i++) {
                    $pkb_den[$i] = 0;
                }
                break;
            
            case "4":
                // Hapus denda PKB tahun ke-4 s/d ke-5
                for($i = 4; $i <= 5; $i++) {
                    $pkb_den[$i] = 0;
                }
                break;
            
            case "5":
                // Hapus denda PKB tahun ke-5
                $pkb_den[5] = 0;
                break;
        }
    }
    
    // Pembulatan PKB
    $pok = 0;
    foreach($pkb_pok as &$value) {
        $value = pembulatan($value);
        $pok += $value;
    }
    
    $den = 0;
    foreach($pkb_den as &$value) {
        $value = pembulatan($value);
        $den += $value;
    }
    
    // Pembulatan OPSEN
    $pok_opsen = 0; 
    foreach($opsen_pok as &$value) {
        $value = pembulatan($value);
        $pok_opsen += $value;
    }
    
    $den_opsen = 0;
    foreach($opsen_den as &$value) {
        $value = pembulatan($value);
        $den_opsen += $value;
    }
    
    $datakb['pkb_pok'] = $pkb_pok;
    $datakb['pkb_den'] = $pkb_den;
    $datakb['pok_pkb_akhir'] = $pok;
    $datakb['den_pkb_akhir'] = $den;
    
    $datakb['opsen_pok'] = $opsen_pok;
    $datakb['opsen_den'] = $opsen_den;
    $datakb['pok_opsen_akhir'] = $pok_opsen;
    $datakb['den_opsen_akhir'] = $den_opsen;
    $datakb['opsen_berlaku'] = $opsen_berlaku; // Simpan status OPSEN per tahun
    $datakb['tgl_periode'] = $tgl_periode; // Simpan tanggal periode per tahun
    
    // Tanggal jatuh tempo baru
    $datakb['tg_akhir_pkb_yad'] = $tgl_periode[0]; // Gunakan tanggal periode tahun berjalan
    
    // Total termasuk OPSEN jika berlaku
    return $pok + $den + $pok_opsen + $den_opsen;
}

/**
 * Ambil tarif SWDKLLJ dari database (sama seperti infopkb.php)
 */
function tarif_swd($db, $tgl, $kd_trf_swd, $bln) {
    $tgl = toDbDate($tgl);
    
    if(strlen($bln) == 1) $bln = "0$bln";
    $data = "prorata_" . $bln;
    
    $sql = "SELECT $data, krt_swd FROM t_trf_swd
            WHERE tg_dari <= '$tgl' AND tg_sampai >= '$tgl'
              AND kd_trf_swd = '$kd_trf_swd'";
    $row = $db->getrow($sql);
    
    if(!$row) {
        // Fallback ke tarif default jika tidak ditemukan
        return false;
    }
    
    if($row[$data] == $row['krt_swd']) $row['krt_swd'] = 0;
    return $row;
}

/**
 * Hitung denda SWDKLLJ (sama seperti infopkb.php)
 */
function hit_den_swd($tg_tetap, $tg_akhir, $trf_swd, $kd_jenis_kb = '') {
    $d_tg_tetap = to_date($tg_tetap);
    $d_tg_akhir = to_date($tg_akhir);
    
    $sel_tgl = selisih_tgl($tg_akhir, $tg_tetap);
    $n = $sel_tgl['n'];
    
    $pct = 0;
    
    if($n > 270) {
        $pct = 100;
    } elseif ($n > 180) {
        $pct = 75;
    } elseif ($n > 90) {
        $pct = 50;
    } else {
        if($n > 0) $pct = 25;
    }
    
    $denda = ($pct / 100) * $trf_swd;
    
    // Batas maksimal denda berdasarkan jenis kendaraan
    $max_denda = ($kd_jenis_kb == 'R') ? 35000 : 100000;
    if($denda > $max_denda) $denda = $max_denda;
    
    return $denda;
}

/**
 * Hitung SWDKLLJ (versi sederhana untuk estimasi)
 */
function hitswd(&$datakb) {
    global $dbonl;
    
    // Tentukan kode tarif SWDKLLJ berdasarkan jenis kendaraan
    $kd_trf_swd = '';
    switch($datakb['kd_jenis_kb']) {
        case 'A': // Sedan
        case 'B': // Jeep
            $kd_trf_swd = ($datakb['kd_kel_kb'] == "U") ? "EU" : "A";
            break;
        case 'C': // Minibus
            $kd_trf_swd = ($datakb['kd_kel_kb'] == "U") ? "EU" : "C";
            break;
        case 'D': // Bus
        case 'E': // Mikrobus
            $kd_trf_swd = ($datakb['kd_kel_kb'] == "U") ? "EU" : "D";
            break;
        case 'F': // Pikup
            $kd_trf_swd = "F";
            break;
        case 'G': // Truk
        case 'H': // Truk Besar
            $kd_trf_swd = "H";
            break;
        case 'R': // Sepeda Motor
            $kd_trf_swd = "R";
            break;
        default:
            $kd_trf_swd = "A";
    }
    
    // Untuk kendaraan khusus (ambulans, pemadam, dll)
    if($datakb['kd_jenis_kb'] != 'R') {
        if(preg_match('/0[678]/', $datakb['kd_fungsi'])) {
            $kd_trf_swd = 'A';
        }
    }
    
    $tg_daftar = date('d/m/Y');
    $tg_akhir_jr = $datakb['tg_akhir_jr'];
    
    $d_tg_daftar = to_date($tg_daftar);
    $d_tg_akhir_jr = to_date($tg_akhir_jr);
    
    // Coba ambil tarif dari database
    $trfswd_base = tarif_swd($dbonl, $tg_daftar, $kd_trf_swd, 12);
    
    // Jika tidak ada di database, gunakan estimasi
    if(!$trfswd_base) {
        $tarif_base = 0;
        if($datakb['kd_jenis_kb'] == 'R') { // Motor
            $tarif_base = 35000;
        } else { // Mobil
            if($datakb['kd_kel_kb'] == 'U') { // Umum
                $tarif_base = ($datakb['jumlah_cc'] <= 1600) ? 143000 : 163000;
            } else { // Pribadi
                $tarif_base = 143000;
            }
        }
        $trfswd_base = ['prorata_12' => $tarif_base, 'krt_swd' => 0];
    }
    
    $swd_pok = [];
    $swd_den = [];
    
    for($i = 0; $i <= 5; $i++) {
        $swd_pok[$i] = 0;
        $swd_den[$i] = 0;
    }
    
    $sel_tgl = selisih_tgl($tg_akhir_jr, $tg_daftar);
    
    // Sudah terlambat
    if($sel_tgl['n'] > 0) {
        $d_tg_daluarsa = mktime(0, 0, 0, 1, 1, date('Y')-4);
        
        // Cek apakah sudah daluarsa (lebih dari 4 tahun)
        if($d_tg_akhir_jr < $d_tg_daluarsa) {
            // Sudah daluarsa - hitung tunggakan dari tahun daluarsa
            list($d, $m, $y) = preg_split('/[-\/]/', $tg_akhir_jr);
            $y = date('Y', $d_tg_daluarsa);
            
            $k = -1;
            for($i = 4; $i > 0; $i--) {
                $k++;
                $tgl = date('d/m/Y', mktime(0, 0, 0, $m, $d, $y+$k));
                $trfswd = tarif_swd($dbonl, $tgl, $kd_trf_swd, 12);
                
                if($trfswd) {
                    $swd_pok[$i] = $trfswd['prorata_12'];
                    $swd_den[$i] = $swd_pok[$i] - $trfswd['krt_swd'];
                    $max_denda = ($datakb['kd_jenis_kb'] == 'R') ? 35000 : 100000;
                    if($swd_den[$i] > $max_denda) $swd_den[$i] = $max_denda;
                } else {
                    $swd_pok[$i] = $trfswd_base['prorata_12'];
                    $max_denda = ($datakb['kd_jenis_kb'] == 'R') ? 35000 : 100000;
                    $swd_den[$i] = $max_denda; // Denda maksimal
                }
            }
        } else {
            // Belum daluarsa - hitung tunggakan normal
            list($d, $m, $y) = preg_split('/[-\/]/', $tg_akhir_jr);
            
            $k = -1;
            for($i = $sel_tgl['y']; $i > 0; $i--) {
                $k++;
                if($i < 5) {
                    $tgl = date('d/m/Y', mktime(0, 0, 0, $m, $d, $y+$k));
                    $trfswd = tarif_swd($dbonl, $tgl, $kd_trf_swd, 12);
                    
                    if($trfswd) {
                        $swd_pok[$i] = $trfswd['prorata_12'];
                        $swd_den[$i] = $swd_pok[$i] - $trfswd['krt_swd'];
                        $max_denda = ($datakb['kd_jenis_kb'] == 'R') ? 35000 : 100000;
                        if($swd_den[$i] > $max_denda) $swd_den[$i] = $max_denda;
                    } else {
                        $swd_pok[$i] = $trfswd_base['prorata_12'];
                        $max_denda = ($datakb['kd_jenis_kb'] == 'R') ? 35000 : 100000;
                        $swd_den[$i] = $max_denda;
                    }
                }
            }
        }
        
        // Tahun berjalan
        list($d, $m, $y) = preg_split('/[-\/]/', $tg_akhir_jr);
        $tg_pre_jr = date('d/m/Y', mktime(0, 0, 0, $m, $d, $y + $sel_tgl['y']));
        
        $trfswd = tarif_swd($dbonl, $tg_pre_jr, $kd_trf_swd, 12);
        if(!$trfswd) $trfswd = $trfswd_base;
        
        $swd_pok[0] = $trfswd['prorata_12'];
        
        // Hitung denda tahun berjalan menggunakan fungsi hit_den_swd
        $trf_swd_denda = $trfswd['prorata_12'] - $trfswd['krt_swd'];
        $swd_den[0] = hit_den_swd($tg_daftar, $tg_pre_jr, $trf_swd_denda, $datakb['kd_jenis_kb']);
        
    } else {
        // Belum terlambat
        $swd_pok[0] = $trfswd_base['prorata_12'];
        $swd_den[0] = 0;
    }
    
    $pok = 0;
    foreach($swd_pok as $value) {
        $pok += $value;
    }
    
    $den = 0;
    $max_denda = ($datakb['kd_jenis_kb'] == 'R') ? 35000 : 100000;
    foreach($swd_den as $value) {
        if($value > $max_denda) $value = $max_denda;
        $den += $value;
    }
    
    // Kalo ada pemutihan
    if ($datakb['pemutihan'] == "Y") {
        $set_pp = $datakb['set_pp'];
        
        // Simpan nilai sebelum pemutihan
        $datakb['pok_swd_awal'] = $pok;
        $datakb['den_swd_awal'] = $den;
        $datakb['tot_swd_awal'] = $pok + $den;
        
        // Pemutihan pokok SWDKLLJ
        switch($set_pp['pokok_swdkllj']) {
            case "0":
                // Hapus semua pokok SWDKLLJ
                for($i = 0; $i <= 4; $i++) {
                    $swd_pok[$i] = 0;
                }
                break;
            
            case "1":
                // Hapus pokok SWDKLLJ tahun ke-1 s/d ke-4
                for($i = 1; $i <= 4; $i++) {
                    $swd_pok[$i] = 0;
                }
                break;
            
            case "2":
                // Hapus pokok SWDKLLJ tahun ke-2 s/d ke-4
                for($i = 2; $i <= 4; $i++) {
                    $swd_pok[$i] = 0;
                }
                break;
            
            case "3":
                // Hapus pokok SWDKLLJ tahun ke-3 s/d ke-4
                for($i = 3; $i <= 4; $i++) {
                    $swd_pok[$i] = 0;
                }
                break;
            
            case "4":
                // Hapus pokok SWDKLLJ tahun ke-4
                $swd_pok[4] = 0;
                break;
        }
        
        // Pemutihan denda SWDKLLJ
        switch($set_pp['denda_swdkllj']) {
            case "0":
                // Hapus semua denda SWDKLLJ
                for($i = 0; $i <= 4; $i++) {
                    $swd_den[$i] = 0;
                }
                break;
            
            case "1":
                // Hapus denda SWDKLLJ tahun ke-1 s/d ke-4
                for($i = 1; $i <= 4; $i++) {
                    $swd_den[$i] = 0;
                }
                break;
            
            case "2":
                // Hapus denda SWDKLLJ tahun ke-2 s/d ke-4
                for($i = 2; $i <= 4; $i++) {
                    $swd_den[$i] = 0;
                }
                break;
            
            case "3":
                // Hapus denda SWDKLLJ tahun ke-3 s/d ke-4
                for($i = 3; $i <= 4; $i++) {
                    $swd_den[$i] = 0;
                }
                break;
            
            case "4":
                // Hapus denda SWDKLLJ tahun ke-4
                $swd_den[4] = 0;
                break;
        }
        
        // Hitung ulang total setelah pemutihan
        $pok = 0;
        foreach($swd_pok as $value) {
            $pok += $value;
        }
        
        $den = 0;
        foreach($swd_den as $value) {
            if($value > $max_denda) $value = $max_denda;
            $den += $value;
        }
        
        // Simpan jumlah pemutihan
        $datakb['jml_pp_swd'] = $datakb['tot_swd_awal'] - ($pok + $den);
    }
    
    $datakb['swd_pok'] = $swd_pok;
    $datakb['swd_den'] = $swd_den;
    $datakb['pok_swd_akhir'] = $pok;
    $datakb['den_swd_akhir'] = $den;
    
    return $pok + $den;
}

/**
 * Hitung biaya TNKB (Plat Nomor)
 */
function hittnkb($datakb) {
    global $dbonl;
    
    $tg_daftar = date('d/m/Y');
    $tg_akhir_stnk = $datakb['tg_akhir_stnk'];
    
    $d_tg_daftar = to_date($tg_daftar);
    $d_tg_akhir_stnk = to_date($tg_akhir_stnk);
    
    $pnbp_tnkb = 0;
    
    // STNK sudah habis atau akan habis tahun ini
    if($d_tg_akhir_stnk <= $d_tg_daftar) {
        if($datakb['kd_jenis_kb'] == "R") { // Motor
            $pnbp_tnkb = 60000;
        } else { // Mobil
            $pnbp_tnkb = 100000;
        }
    }
    
    return $pnbp_tnkb;
}

/**
 * Hitung biaya STNK
 */
function hitstnk($datakb) {
    global $dbonl;
    
    $tg_daftar = date('d/m/Y');
    $tg_akhir_stnk = $datakb['tg_akhir_stnk'];
    
    $d_tg_daftar = to_date($tg_daftar);
    $d_tg_akhir_stnk = to_date($tg_akhir_stnk);
    
    if($datakb['kd_jenis_kb'] == "R") { // Motor
        $pnbp_ctk_stnk = 100000;
    } else { // Mobil
        $pnbp_ctk_stnk = 200000;
    }
    
    $pnbp_stnk = 0;
    
    // Jika STNK akan habis dalam 90 hari
    if($d_tg_akhir_stnk > $d_tg_daftar) {
        $sel_tgl = selisih_tgl($tg_daftar, $tg_akhir_stnk);
        if($sel_tgl['n'] <= 90) {
            $pnbp_stnk = $pnbp_ctk_stnk;
        }
    } else {
        // STNK sudah habis
        $pnbp_stnk = $pnbp_ctk_stnk;
    }
    
    return $pnbp_stnk;
}

/**
 * Format nomor polisi ke format standar database: "BH 2202 ZE"
 * Menggunakan fungsi setnopol() dari samlib.php
 */
function formatNomorPolisi($nopol) {
    return setnopol($nopol);
}

/**
 * Format tanggal dari berbagai format input
 */
function formatTanggal($tgl) {
    $tgl = trim($tgl);
    $len = strlen($tgl);
    
    // Format: ddmmyyyy (8 karakter)
    if ($len == 8 && is_numeric($tgl)) {
        return substr($tgl, 0, 2) . '/' . substr($tgl, 2, 2) . '/' . substr($tgl, 4);
    }
    
    // Format: ddmmyy (6 karakter)
    if ($len == 6 && is_numeric($tgl)) {
        return substr($tgl, 0, 2) . '/' . substr($tgl, 2, 2) . '/20' . substr($tgl, 4);
    }
    
    // Sudah format dd/mm/yyyy atau dd-mm-yyyy
    return str_replace('-', '/', $tgl);
}

/**
 * Konversi tanggal dd/mm/yyyy ke yyyy-mm-dd
 */
function toDbDate($tgl) {
    if (empty($tgl)) return null;
    
    $parts = preg_split('/[-\/]/', $tgl);
    if (count($parts) == 3) {
        list($d, $m, $y) = $parts;
        if (checkdate($m, $d, $y)) {
            return sprintf("%04d-%02d-%02d", $y, $m, $d);
        }
    }
    return null;
}

/**
 * Cari data kendaraan dari database
 * Algoritma optimal: Input dinormalisasi ke format database standar,
 * lalu query langsung (bisa pakai index, lebih cepat!)
 */
function cariDataKendaraan($db, $no_polisi, $nama_pemilik = '', &$debug_info = []) {
    $where_nama = '';
    if ($nama_pemilik) {
        $where_nama = "AND UPPER(nm_pemilik) LIKE '%" . strtoupper($nama_pemilik) . "%'";
    }
    
    $tanggal_cutoff = '1990-01-01';
    
    // Input sudah dinormalisasi dari formatNomorPolisi()
    // Format standar: "BH 2202 ZE" (uppercase + 2 spasi)
    $nopol_std = $no_polisi;
    
    // Simpan untuk debug
    $debug_info['nopol_standard'] = $nopol_std;
    $debug_info['queries'] = [];
    
    // 1. Cari di tabel transaksi tahun berjalan (t_trnkb)
    // Query langsung tanpa REPLACE -> bisa pakai index, lebih cepat!
    $query = "
        SELECT 
            no_polisi, nm_pemilik, al_pemilik, kd_jen_milik, kd_fungsi,
            kd_kel_kb, kd_jenis_kb, kd_merek_kb, nm_merek_kb, nm_model_kb, 
            nm_jenis_kb, th_rakitan, jumlah_cc, warna_kb, kd_plat, kd_bbm,
            TO_CHAR(tg_akhir_pkb, 'DD/MM/YYYY') as tg_akhir_pkb,
            TO_CHAR(tg_akhir_jr, 'DD/MM/YYYY') as tg_akhir_jr,
            TO_CHAR(tg_akhir_stnk, 'DD/MM/YYYY') as tg_akhir_stnk,
            TO_CHAR(tg_bayar, 'DD/MM/YYYY') as tg_bayar,
            kd_mohon, kd_lokasi
        FROM t_trnkb
        WHERE no_polisi = '$nopol_std'
          AND tg_bayar > '$tanggal_cutoff'
          $where_nama
        ORDER BY tg_bayar DESC
    ";
    
    $debug_info['queries'][] = ['table' => 't_trnkb', 'query' => $query];
    $data_found = $db->getrow($query);
    
    if ($data_found) {
        $debug_info['found_in'] = 't_trnkb';
        $debug_info['total_queries'] = count($debug_info['queries']);
        return $data_found;
    }
    
    // 2. Jika tidak ada, cari di tabel master (t_mstkb)
    $query = "
        SELECT 
            no_polisi, nm_pemilik, al_pemilik, kd_jen_milik, kd_fungsi,
            kd_kel_kb, kd_jenis_kb, kd_merek_kb, nm_merek_kb, nm_model_kb, 
            nm_jenis_kb, th_rakitan, jumlah_cc, warna_kb, kd_plat, kd_bbm,
            TO_CHAR(tg_akhir_pkb, 'DD/MM/YYYY') as tg_akhir_pkb,
            TO_CHAR(tg_akhir_jr, 'DD/MM/YYYY') as tg_akhir_jr,
            TO_CHAR(tg_akhir_stnk, 'DD/MM/YYYY') as tg_akhir_stnk,
            TO_CHAR(tg_bayar, 'DD/MM/YYYY') as tg_bayar,
            '-' as kd_mohon,
            '-' as kd_lokasi
        FROM t_mstkb
        WHERE no_polisi = '$nopol_std'
          AND tg_bayar > '$tanggal_cutoff'
          $where_nama
        ORDER BY tg_bayar DESC
    ";
    
    $debug_info['queries'][] = ['table' => 't_mstkb', 'query' => $query];
    $data_found = $db->getrow($query);
    
    if ($data_found) {
        $debug_info['found_in'] = 't_mstkb';
        $debug_info['total_queries'] = count($debug_info['queries']);
        return $data_found;
    }
    
    // 3. Jika masih tidak ada, cari di transaksi lama (tt_trnkb)
    $query = "
        SELECT 
            no_polisi, nm_pemilik, al_pemilik, kd_jen_milik, kd_fungsi,
            kd_kel_kb, kd_jenis_kb, kd_merek_kb, nm_merek_kb, nm_model_kb, 
            nm_jenis_kb, th_rakitan, jumlah_cc, warna_kb, kd_plat, kd_bbm,
            TO_CHAR(tg_akhir_pkb, 'DD/MM/YYYY') as tg_akhir_pkb,
            TO_CHAR(tg_akhir_jr, 'DD/MM/YYYY') as tg_akhir_jr,
            TO_CHAR(tg_akhir_stnk, 'DD/MM/YYYY') as tg_akhir_stnk,
            TO_CHAR(tg_bayar, 'DD/MM/YYYY') as tg_bayar,
            kd_mohon, kd_lokasi
        FROM tt_trnkb
        WHERE no_polisi = '$nopol_std'
          $where_nama
        ORDER BY tg_bayar DESC
    ";
    
    $debug_info['queries'][] = ['table' => 'tt_trnkb', 'query' => $query];
    $data_found = $db->getrow($query);
    
    if ($data_found) {
        $debug_info['found_in'] = 'tt_trnkb';
        $debug_info['total_queries'] = count($debug_info['queries']);
        return $data_found;
    }
    
    // Jika tidak ditemukan, coba cari data yang mirip untuk saran
    // Ambil prefix untuk pencarian LIKE
    if (preg_match('/^([A-Z]{1,2})\s+(\d{1,4})/', $nopol_std, $m)) {
        $prefix_pattern = $m[1] . ' ' . substr($m[2], 0, 2) . '%';
    } else {
        $prefix_pattern = substr($nopol_std, 0, 5) . '%';
    }
    
    $query_similar = "
        SELECT no_polisi, nm_pemilik 
        FROM t_trnkb 
        WHERE no_polisi LIKE '$prefix_pattern'
        ORDER BY tg_bayar DESC
    ";
    $debug_info['similar_query'] = $query_similar;
    $debug_info['total_queries'] = count($debug_info['queries']) + 1;
    
    $similar_results = [];
    $rs = $db->query($query_similar);
    if ($rs) {
        while ($row = $db->fetch_assoc($rs)) {
            $similar_results[] = $row;
        }
    }
    $debug_info['similar_results'] = $similar_results;
    
    return null;
}

/**
 * Hitung biaya kendaraan (PKB, SWDKLLJ, STNK, TNKB)
 * Menggunakan fungsi dari infopkb.php/samlib.php
 */
function hitungBiayaKendaraan($db, $data, $izin_angkutan = false) {
    $hasil = [
        'pokok_pkb' => 0,
        'denda_pkb' => 0,
        'total_pkb' => 0,
        'pokok_swdkllj' => 0,
        'denda_swdkllj' => 0,
        'total_swdkllj' => 0,
        'biaya_stnk' => 0,
        'biaya_tnkb' => 0,
        'grand_total' => 0,
        'tgl_jatuh_tempo_baru' => '',
        'status_terlambat' => false,
        'hari_terlambat' => 0,
        'detail_pkb' => '',
        'peringatan' => [],
        'detail_per_tahun' => [] // Tambahan untuk detail per tahun
    ];
    
    try {
        // Cek apakah kendaraan sudah diremajakan (tidak perlu bayar)
        if (isKendaraanDiremajakan($data['kd_mohon'])) {
            $hasil['peringatan'][] = "Kendaraan ini sudah diremajakan. Silakan hubungi SAMSAT.";
            return $hasil;
        }
        
        // Cek pemutihan pajak
        $pemutihan_setup = getPemutihanSetup();
        
        // Setup data untuk perhitungan (format sesuai infopkb.php)
        $datakb = $data;
        $datakb['kd_mohon'] = ".2.GTW."; // Default untuk perpanjangan STNK
        $datakb['tg_daftar'] = date('d/m/Y');
        $datakb['kd_proses'] = "pkb";
        $datakb['jt_berubah'] = false;
        $datakb['izin_ang'] = $izin_angkutan ? "1" : "0";
        $datakb['siup_ang'] = "0";
        $datakb['kir_ang'] = "0";
        $datakb['ctk_stnk'] = "0";
        $datakb['byr_dimuka'] = false;
        $datakb['progresif'] = "";
        $datakb['no_urut'] = 1;
        $datakb['pct_trf'] = 1.5;
        $datakb['pct_pkb'] = 100;
        $datakb['tg_akhir_pkb_yad'] = "";
        $datakb['pemutihan'] = $pemutihan_setup['pemutihan'];
        $datakb['set_pp'] = ($pemutihan_setup['pemutihan'] == 'Y') ? $pemutihan_setup['set_pp'] : false;
        
        // Hitung selisih hari
        $tgl_sekarang = date('d/m/Y');
        $tgl_akhir_pkb = $data['tg_akhir_pkb'];
        
        $selisih = selisih_tgl($tgl_akhir_pkb, $tgl_sekarang);
        $hasil['hari_terlambat'] = $selisih['n'];
        $hasil['status_terlambat'] = $selisih['n'] > 0;
        
        if ($selisih['n'] > 0) {
            $bulan = $selisih['m'];
            if ($selisih['d'] > 15) $bulan++;
            $hasil['detail_pkb'] = "Terlambat {$selisih['n']} hari ($bulan bulan, {$selisih['y']} tahun)";
        } else {
            $hasil['detail_pkb'] = "Tepat waktu";
        }
        
        // Hitung PKB menggunakan fungsi estimasi
        $total_pkb = hitpkb($datakb);
        
        if ($total_pkb > 0) {
            // Ambil hasil dari $datakb yang sudah dihitung
            $hasil['pokok_pkb'] = $datakb['pok_pkb_akhir'];
            $hasil['denda_pkb'] = $datakb['den_pkb_akhir'];
            
            // Hitung total PKB (PKB pokok + denda PKB saja, tanpa OPSEN)
            $total_pkb_only = $hasil['pokok_pkb'] + $hasil['denda_pkb'];
            $hasil['total_pkb'] = $total_pkb_only;
            
            // OPSEN (jika berlaku)
            $hasil['pokok_opsen'] = isset($datakb['pok_opsen_akhir']) ? $datakb['pok_opsen_akhir'] : 0;
            $hasil['denda_opsen'] = isset($datakb['den_opsen_akhir']) ? $datakb['den_opsen_akhir'] : 0;
            $hasil['total_opsen'] = $hasil['pokok_opsen'] + $hasil['denda_opsen'];
            $hasil['gunakan_opsen'] = isset($datakb['gunakan_opsen']) ? $datakb['gunakan_opsen'] : false;
            $hasil['tgl_jatuh_tempo_baru'] = $datakb['tg_akhir_pkb_yad'];
            
            // Simpan info tarif untuk ditampilkan
            $hasil['tarif_info'] = [
                'njkb' => isset($datakb['njkb']) ? $datakb['njkb'] : '-',
                'sumber_njkb' => isset($datakb['sumber_njkb']) ? $datakb['sumber_njkb'] : 'estimasi',
                'pct_trf' => $datakb['pct_trf'],
                'pct_pkb' => $datakb['pct_pkb'],
                'pct_opsen' => isset($datakb['pct_opsen']) ? $datakb['pct_opsen'] : 0,
                'progresif' => $datakb['progresif'],
                'no_urut' => $datakb['no_urut'],
                'gunakan_opsen' => isset($datakb['gunakan_opsen']) ? $datakb['gunakan_opsen'] : false
            ];
            
            // Informasi pemutihan PKB (jika ada)
            if ($datakb['pemutihan'] == 'Y') {
                $hasil['pemutihan_pkb'] = [
                    'aktif' => true,
                    'total_awal' => isset($datakb['tot_pkb_awal']) ? $datakb['tot_pkb_awal'] : 0,
                    'total_akhir' => $hasil['total_pkb'] + $hasil['total_opsen'],
                    'jumlah_dibebaskan' => isset($datakb['jml_pp_pkb']) ? $datakb['jml_pp_pkb'] : 
                        ((isset($datakb['tot_pkb_awal']) ? $datakb['tot_pkb_awal'] : 0) - $hasil['total_pkb'])
                ];
            } else {
                $hasil['pemutihan_pkb'] = ['aktif' => false];
            }
            
            // Ambil detail per tahun PKB dengan tanggal periode
            if (isset($datakb['pkb_pok']) && isset($datakb['pkb_den'])) {
                $hasil['pkb_per_tahun'] = [];
                
                for ($i = 0; $i <= 5; $i++) {
                    if ($datakb['pkb_pok'][$i] > 0 || $datakb['pkb_den'][$i] > 0 || 
                        (isset($datakb['opsen_pok'][$i]) && $datakb['opsen_pok'][$i] > 0)) {
                        
                        // Ambil tanggal periode dari perhitungan
                        $tgl_periode = isset($datakb['tgl_periode'][$i]) ? $datakb['tgl_periode'][$i] : '-';
                        
                        // Extract tahun dari tanggal periode
                        if ($tgl_periode != '-') {
                            list($d_p, $m_p, $y_p) = preg_split('/[-\/]/', $tgl_periode);
                            $tahun_periode = $y_p;
                        } else {
                            $tahun_periode = '-';
                        }
                        
                        // Ambil data OPSEN jika ada
                        $opsen_pok_val = isset($datakb['opsen_pok'][$i]) ? $datakb['opsen_pok'][$i] : 0;
                        $opsen_den_val = isset($datakb['opsen_den'][$i]) ? $datakb['opsen_den'][$i] : 0;
                        $opsen_berlaku_val = isset($datakb['opsen_berlaku'][$i]) ? $datakb['opsen_berlaku'][$i] : false;
                        
                        $hasil['pkb_per_tahun'][$i] = [
                            'pokok' => $datakb['pkb_pok'][$i],
                            'denda' => $datakb['pkb_den'][$i],
                            'opsen_pokok' => $opsen_pok_val,
                            'opsen_denda' => $opsen_den_val,
                            'opsen_berlaku' => $opsen_berlaku_val,
                            'total' => $datakb['pkb_pok'][$i] + $datakb['pkb_den'][$i] + $opsen_pok_val + $opsen_den_val,
                            'tgl_periode' => $tgl_periode,
                            'tahun' => $tahun_periode
                        ];
                    }
                }
            }
        } else {
            $hasil['peringatan'][] = "Terjadi kesalahan dalam perhitungan PKB.";
        }
        
        // Hitung SWDKLLJ menggunakan fungsi dari infopkb.php
        $total_swd = hitswd($datakb);
        
        if ($total_swd > 0) {
            $hasil['pokok_swdkllj'] = $datakb['pok_swd_akhir'];
            $hasil['denda_swdkllj'] = $datakb['den_swd_akhir'];
            $hasil['total_swdkllj'] = $total_swd;
            
            // Informasi pemutihan SWDKLLJ (jika ada)
            if ($datakb['pemutihan'] == 'Y') {
                $hasil['pemutihan_swd'] = [
                    'aktif' => true,
                    'total_awal' => isset($datakb['tot_swd_awal']) ? $datakb['tot_swd_awal'] : 0,
                    'total_akhir' => $total_swd,
                    'jumlah_dibebaskan' => isset($datakb['jml_pp_swd']) ? $datakb['jml_pp_swd'] :
                        ((isset($datakb['tot_swd_awal']) ? $datakb['tot_swd_awal'] : 0) - $total_swd)
                ];
            } else {
                $hasil['pemutihan_swd'] = ['aktif' => false];
            }
            
            // Ambil detail per tahun SWDKLLJ dengan tanggal periode
            if (isset($datakb['swd_pok']) && isset($datakb['swd_den'])) {
                $hasil['swd_per_tahun'] = [];
                
                // Hitung tanggal periode untuk setiap tahun
                list($d, $m, $y) = preg_split('/[-\/]/', $data['tg_akhir_jr']);
                $sel_tgl = selisih_tgl($data['tg_akhir_jr'], date('d/m/Y'));
                
                for ($i = 0; $i <= 5; $i++) {
                    if ($datakb['swd_pok'][$i] > 0 || $datakb['swd_den'][$i] > 0) {
                        // Hitung tanggal akhir periode untuk tahun ini
                        if ($i == 0) {
                            // Tahun berjalan - periode s/d tanggal jatuh tempo berikutnya
                            $tahun_periode = $y + $sel_tgl['y'] + 1;
                            $tgl_periode = date('d/m/Y', mktime(0, 0, 0, $m, $d, $tahun_periode));
                        } else {
                            // Tunggakan - periode tahun yang lalu
                            $tahun_periode = $y + $sel_tgl['y'] - $i + 1;
                            $tgl_periode = date('d/m/Y', mktime(0, 0, 0, $m, $d, $tahun_periode));
                        }
                        
                        $hasil['swd_per_tahun'][$i] = [
                            'pokok' => $datakb['swd_pok'][$i],
                            'denda' => $datakb['swd_den'][$i],
                            'total' => $datakb['swd_pok'][$i] + $datakb['swd_den'][$i],
                            'tgl_periode' => $tgl_periode,
                            'tahun' => $tahun_periode
                        ];
                    }
                }
            }
        }
        
        // Hitung biaya STNK jika ada tanggal akhir STNK
        if (to_date($data['tg_akhir_stnk'])) {
            $hasil['biaya_stnk'] = hitstnk($datakb);
        }
        
        // Hitung biaya TNKB (plat nomor) jika perlu
        if (to_date($data['tg_akhir_stnk'])) {
            $hasil['biaya_tnkb'] = hittnkb($datakb);
        }
        
        // Total keseluruhan
        $hasil['grand_total'] = $hasil['total_pkb'] + $hasil['total_opsen'] + $hasil['total_swdkllj'] + 
                                $hasil['biaya_stnk'] + $hasil['biaya_tnkb'];
        
    } catch (Exception $e) {
        $hasil['peringatan'][] = "Error perhitungan: " . $e->getMessage();
    }
    
    return $hasil;
}

/**
 * Cek apakah kendaraan sudah diremajakan
 */
function isKendaraanDiremajakan($kd_mohon) {
    if (empty($kd_mohon)) return false;
    
    // Pattern untuk kendaraan yang sudah diremajakan
    return (preg_match('/\.[36X]X\./', $kd_mohon) == 1 || 
            preg_match('/\.3[34]\./', $kd_mohon) == 1);
}

/**
 * Cek dan setup pemutihan pajak
 */
function getPemutihanSetup() {
    global $dbonl;
    
    $tg_daftar = date('d/m/Y');
    $d_tg_daftar = to_date($tg_daftar);
    
    // Default: tidak ada pemutihan
    $result = [
        'pemutihan' => 'T',
        'set_pp' => [
            'pokok_pkb' => 'X',
            'denda_pkb' => 'X',
            'pokok_swdkllj' => 'X',
            'denda_swdkllj' => 'X'
        ]
    ];
    
    // Cek apakah ada pemutihan
    $query = "SELECT nilai FROM t_param
                WHERE kd_param = 'SISTEM'
                  AND kd_data = 'PEMUTIHAN-PAJAK'";
    
    $pemutihan = $dbonl->getvalue($query);
    
    // Jika ada pemutihan
    if ($pemutihan == "Y") {
        // Ambil tanggal awal dan akhir pemutihan
        $tg_awal_pp = p_param("TGL-AWAL-PP");
        $tg_akhir_pp = p_param("TGL-AKHIR-PP");
        
        // Baca setting pemutihan pajak
        $query = "SELECT * FROM t_set_pp
                    WHERE tg_awal = '" . toDbDate($tg_awal_pp) . "'
                      AND tg_akhir = '" . toDbDate($tg_akhir_pp) . "'";
        
        $set_pp = $dbonl->getrow($query, "d/m/Y");
        
        if ($set_pp) {
            $d_tg_awal = to_date($set_pp['tg_awal']);
            $d_tg_akhir = to_date($set_pp['tg_akhir']);
            
            // Cek apakah tanggal daftar dalam periode pemutihan
            if ($d_tg_daftar >= $d_tg_awal && $d_tg_daftar <= $d_tg_akhir) {
                $result['pemutihan'] = 'Y';
                $result['set_pp'] = $set_pp;
            }
        }
    }
    
    return $result;
}

/**
 * Format angka ke format Rupiah
 */
function formatRupiah($nilai) {
    return 'Rp ' . number_format($nilai, 0, ',', '.');
}

/**
 * Nama jenis kepemilikan
 */
function getNamaKepemilikan($kode) {
    $daftar = [
        '01' => 'Perorangan',
        '02' => 'Badan Hukum',
        '03' => 'Perusahaan',
        '04' => 'Pemerintah',
        '06' => 'Bebas PKB'
    ];
    return $daftar[$kode] ?? 'Lainnya';
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_TITLE; ?> - <?php echo APP_NAME; ?></title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .gradient-price {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        details summary::-webkit-details-marker {
            display: none;
        }
    </style>
</head>
<body class="min-h-screen p-5">
    <div class="max-w-6xl mx-auto">
        <a href="index.html" class="inline-block mb-5 text-white no-underline font-semibold px-5 py-2.5 bg-white/20 rounded-lg hover:bg-white/30 transition-all">
            ← Kembali ke Menu Utama
        </a>
        
        <div class="bg-white p-6 rounded-2xl shadow-2xl mb-7 text-center">
            <h1 class="text-blue-600 text-4xl mb-2.5 font-bold">🚗 <?php echo APP_TITLE; ?></h1>
            <p class="text-slate-500 text-lg"><?php echo APP_NAME; ?></p>
        </div>
        
        <?php if ($error_message): ?>
            <div class="p-4 mb-5 rounded-lg border-l-4 bg-red-50 text-red-900 border-red-500">
                <strong>⚠️ Error!</strong> <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($info_message): ?>
            <div class="p-4 mb-5 rounded-lg border-l-4 bg-blue-50 text-blue-900 border-blue-500">
                <strong>ℹ️ Informasi:</strong> <?php echo $info_message; ?>
            </div>
        <?php endif; ?>
        
        <!-- DEBUG INFO (untuk development) -->
        <?php if (!empty($debug_info) && $form_submitted): ?>
            <div class="bg-slate-100 rounded-2xl p-7 shadow-2xl mb-5 border-l-4 border-slate-500">
                <h3 class="text-slate-700 mb-4 text-xl font-semibold">🔍 Informasi Debug</h3>
                
                <div class="text-sm">
                    <p class="mb-2"><strong>Input Original:</strong> <?php echo htmlspecialchars($debug_info['input_original'] ?? '-'); ?></p>
                    <p class="mb-2"><strong>Format Standar DB:</strong> <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded font-mono"><?php echo htmlspecialchars($debug_info['nopol_standard'] ?? $debug_info['nopol_formatted'] ?? '-'); ?></span></p>
                    <p class="mb-2 text-xs text-slate-500">💡 Input dinormalisasi ke format database standar (uppercase + 2 spasi), query langsung pakai index = super cepat!</p>
                    
                    <?php if (isset($debug_info['found_in'])): ?>
                        <p class="text-green-600 mb-2"><strong>✅ Data Ditemukan di:</strong> <?php echo $debug_info['found_in']; ?></p>
                        <p class="mb-2"><strong>Total Query:</strong> <?php echo $debug_info['total_queries'] ?? count($debug_info['queries'] ?? []); ?> query (optimal! 🚀)</p>
                    <?php else: ?>
                        <p class="text-red-600 mb-2"><strong>❌ Data Tidak Ditemukan</strong></p>
                        <p class="text-xs mb-2">Total query: <?php echo $debug_info['total_queries'] ?? count($debug_info['queries'] ?? []); ?> query</p>
                    <?php endif; ?>
                    
                    <details class="mt-4">
                        <summary class="cursor-pointer font-semibold text-slate-600 hover:text-slate-800">
                            ▶ Lihat Query SQL (<?php echo count($debug_info['queries'] ?? []); ?> queries)
                        </summary>
                        <div class="mt-2.5 max-h-96 overflow-y-auto">
                            <?php if (!empty($debug_info['queries'])): ?>
                                <?php foreach ($debug_info['queries'] as $idx => $q): ?>
                                    <div class="bg-white p-2.5 my-1 rounded border-l-2 border-blue-500">
                                        <strong>Query #<?php echo $idx + 1; ?> - Tabel: <?php echo $q['table']; ?></strong>
                                        <pre class="text-xs bg-slate-800 text-slate-200 p-2.5 rounded mt-1 overflow-x-auto"><?php echo htmlspecialchars($q['query']); ?></pre>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </details>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- FORM INPUT -->
        <div class="bg-white rounded-xl p-8 shadow-lg border border-slate-100 mb-6">
            <div class="flex items-center gap-3 mb-6 pb-5 border-b-2 border-blue-500">
                <div class="bg-blue-100 p-3 rounded-lg">
                    <span class="text-2xl">📋</span>
                </div>
                <h2 class="text-2xl font-bold text-slate-800 m-0">Form Pencarian Data Kendaraan</h2>
            </div>
            
            <form method="POST" action="">
                <div class="mb-6">
                    <label class="block font-semibold text-slate-700 mb-2.5 text-sm uppercase tracking-wide">
                        Nomor Polisi <span class="text-red-600">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="no_polisi" 
                        class="w-full px-5 py-3.5 border-2 border-slate-200 rounded-xl text-base uppercase transition-all focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 hover:border-slate-300 bg-white" 
                        placeholder="Contoh: BH1234AB"
                        value="<?php echo isset($_POST['no_polisi']) ? htmlspecialchars($_POST['no_polisi']) : ''; ?>"
                        required
                        autofocus
                    >
                    <small class="block text-xs text-slate-500 mt-2">💡 Masukkan nomor polisi tanpa spasi atau tanda baca</small>
                </div>
                
                <div class="mb-6">
                    <label class="block font-semibold text-slate-700 mb-2.5 text-sm uppercase tracking-wide">
                        Nama Pemilik <span class="text-slate-400 text-xs normal-case">(Opsional)</span>
                    </label>
                    <input 
                        type="text" 
                        name="nm_pemilik" 
                        class="w-full px-5 py-3.5 border-2 border-slate-200 rounded-xl text-base uppercase transition-all focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 hover:border-slate-300 bg-white" 
                        placeholder="Isi jika ada kendaraan dobel dengan nomor polisi sama"
                        value="<?php echo isset($_POST['nm_pemilik']) ? htmlspecialchars($_POST['nm_pemilik']) : ''; ?>"
                    >
                    <small class="block text-xs text-slate-500 mt-2">💡 Isi hanya jika nama pemilik yang ditampilkan berbeda</small>
                </div>
                
                <div class="mb-6">
                    <label class="block font-semibold text-slate-700 mb-2.5 text-sm uppercase tracking-wide">
                        Tanggal Pencarian PKB <span class="text-slate-400 text-xs normal-case">(Otomatis)</span>
                    </label>
                    <input 
                        type="text" 
                        name="tg_cek" 
                        id="tg_cek" 
                        class="w-full px-5 py-3.5 border-2 border-slate-200 rounded-xl text-base transition-all focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 hover:border-slate-300 bg-slate-50" 
                        value="<?php echo isset($_POST['tg_cek']) ? htmlspecialchars($_POST['tg_cek']) : date('d/m/Y'); ?>"
                    >
                    <small class="block text-xs text-slate-500 mt-2">💡 Default adalah tanggal hari ini, bisa diubah sesuai kebutuhan</small>
                </div>
                
                <div class="mb-6">
                    <label class="block font-semibold text-slate-700 mb-2.5 text-sm uppercase tracking-wide">
                        Tanggal Akhir PKB <span class="text-slate-400 text-xs normal-case">(Opsional)</span>
                    </label>
                    <input 
                        type="text" 
                        name="tg_akhir_pkb" 
                        class="w-full px-5 py-3.5 border-2 border-slate-200 rounded-xl text-base transition-all focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 hover:border-slate-300 bg-white" 
                        placeholder="Format: dd/mm/yyyy atau ddmmyyyy"
                        value="<?php echo isset($_POST['tg_akhir_pkb']) ? htmlspecialchars($_POST['tg_akhir_pkb']) : ''; ?>"
                    >
                    <small class="block text-xs text-slate-500 mt-2">💡 Isi jika tanggal akhir PKB yang ditampilkan berbeda</small>
                </div>
                
                <div class="mb-6">
                    <label class="block font-semibold text-slate-700 mb-2.5 text-sm uppercase tracking-wide">
                        Tanggal Akhir STNK <span class="text-slate-400 text-xs normal-case">(Opsional)</span>
                    </label>
                    <input 
                        type="text" 
                        name="tg_akhir_stnk" 
                        class="w-full px-5 py-3.5 border-2 border-slate-200 rounded-xl text-base transition-all focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100 hover:border-slate-300 bg-white" 
                        placeholder="Format: dd/mm/yyyy atau ddmmyyyy"
                        value="<?php echo isset($_POST['tg_akhir_stnk']) ? htmlspecialchars($_POST['tg_akhir_stnk']) : ''; ?>"
                    >
                    <small class="block text-xs text-slate-500 mt-2">💡 Isi jika tanggal akhir STNK yang ditampilkan berbeda</small>
                </div>
                
                <div class="mb-7">
                    <label class="block font-semibold text-slate-700 mb-2.5 text-sm uppercase tracking-wide">Kendaraan Umum</label>
                    <div class="flex items-center p-4 bg-gradient-to-r from-slate-50 to-slate-100 rounded-xl border-2 border-slate-200 hover:border-slate-300 transition-all">
                        <input 
                            type="checkbox" 
                            name="izin_ang" 
                            id="izin_ang"
                            class="w-5 h-5 mr-3 cursor-pointer accent-blue-600"
                            <?php echo (isset($_POST['izin_ang']) && $_POST['izin_ang'] === 'on') ? 'checked' : ''; ?>
                        >
                        <label for="izin_ang" class="m-0 cursor-pointer text-slate-700 font-medium">
                            🚌 Memiliki izin angkutan umum yang masih berlaku
                        </label>
                    </div>
                </div>
                
                <button type="submit" class="w-full px-8 py-4 border-0 rounded-xl text-lg font-bold cursor-pointer transition-all bg-gradient-to-r from-blue-600 to-blue-700 text-white hover:from-blue-700 hover:to-blue-800 hover:shadow-xl transform hover:-translate-y-1 active:translate-y-0 shadow-lg">
                    <span class="text-xl mr-2">🔍</span> Cek Informasi PKB Sekarang
                </button>
            </form>
        </div>
        
        <!-- HASIL PENCARIAN -->
        <?php if ($form_submitted && $data_kendaraan && !$error_message): ?>
            
        <!-- Data Kendaraan -->
        <div class="bg-white rounded-xl p-8 shadow-lg border border-slate-100 mb-6">
            <div class="flex items-center gap-3 mb-6 pb-5 border-b-2 border-emerald-500">
                <div class="bg-emerald-100 p-3 rounded-lg">
                    <span class="text-2xl">🚙</span>
                </div>
                <h2 class="text-2xl font-bold text-slate-800 m-0">Data Kendaraan</h2>
            </div>
            
            <div class="bg-gradient-to-br from-slate-50 to-white rounded-lg border border-slate-200 overflow-hidden">
            <table class="w-full">
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="p-4 font-semibold text-slate-600 w-48 text-sm uppercase tracking-wide">Nomor Polisi</td>
                    <td class="p-4 text-slate-800">
                        <div class="inline-flex items-center gap-2 bg-blue-100 px-4 py-2 rounded-lg">
                            <span class="text-xl">🚗</span>
                            <strong class="text-xl text-blue-700"><?php echo $data_kendaraan['no_polisi']; ?></strong>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="p-3 border-b border-slate-200 font-semibold text-slate-600">Merek</td>
                    <td class="p-3 border-b border-slate-200 text-slate-800"><?php echo $data_kendaraan['nm_merek_kb']; ?></td>
                </tr>
                <tr>
                    <td class="p-3 border-b border-slate-200 font-semibold text-slate-600">Model/Tipe</td>
                    <td class="p-3 border-b border-slate-200 text-slate-800"><?php echo $data_kendaraan['nm_model_kb']; ?></td>
                </tr>
                <tr>
                    <td class="p-3 border-b border-slate-200 font-semibold text-slate-600">Jenis Kendaraan</td>
                    <td class="p-3 border-b border-slate-200 text-slate-800"><?php echo $data_kendaraan['nm_jenis_kb']; ?></td>
                </tr>
                <tr>
                    <td class="p-3 border-b border-slate-200 font-semibold text-slate-600">Tahun Rakitan</td>
                    <td class="p-3 border-b border-slate-200 text-slate-800"><?php echo $data_kendaraan['th_rakitan']; ?></td>
                </tr>
                <tr>
                    <td class="p-3 border-b border-slate-200 font-semibold text-slate-600">Kapasitas Mesin</td>
                    <td class="p-3 border-b border-slate-200 text-slate-800"><?php echo number_format($data_kendaraan['jumlah_cc'], 0, ',', '.'); ?> CC</td>
                </tr>
                <tr>
                    <td class="p-3 border-b border-slate-200 font-semibold text-slate-600">Warna</td>
                    <td class="p-3 border-b border-slate-200 text-slate-800"><?php echo $data_kendaraan['warna_kb']; ?></td>
                </tr>
                <?php if (!empty($data_kendaraan['tg_bayar'])): ?>
                <tr>
                    <td class="p-3 border-b border-slate-200 font-semibold text-slate-600">Tgl. Bayar Terakhir</td>
                    <td class="p-3 border-b border-slate-200 text-slate-800">
                        <strong><?php echo $data_kendaraan['tg_bayar']; ?></strong>
                    </td>
                </tr>
                <?php endif; ?>
                <tr>
                    <td class="p-3 border-b border-slate-200 font-semibold text-slate-600">Tgl. Akhir PKB</td>
                    <td class="p-3 border-b border-slate-200 text-slate-800">
                        <strong class="text-lg"><?php echo $data_kendaraan['tg_akhir_pkb']; ?></strong>
                        <?php if ($hasil_perhitungan['status_terlambat']): ?>
                            <span class="inline-block px-4 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-900 ml-2">
                                Terlambat <?php echo $hasil_perhitungan['hari_terlambat']; ?> hari
                            </span>
                        <?php else: ?>
                            <span class="inline-block px-4 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-900 ml-2">Aktif</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td class="p-3 border-b border-slate-200 font-semibold text-slate-600">Tgl. Akhir STNK</td>
                    <td class="p-3 border-b border-slate-200 text-slate-800">
                        <strong><?php echo $data_kendaraan['tg_akhir_stnk'] ?: '-'; ?></strong>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- Peringatan -->
        <?php if (!empty($hasil_perhitungan['peringatan'])): ?>
            <div class="p-4 mb-5 rounded-lg border-l-4 bg-yellow-50 text-yellow-900 border-yellow-500">
                <strong>⚠️ Peringatan:</strong>
                <ul class="mt-2 ml-5 list-disc">
                    <?php foreach ($hasil_perhitungan['peringatan'] as $peringatan): ?>
                        <li><?php echo $peringatan; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <!-- Rincian Biaya -->
        <?php if ($hasil_perhitungan['grand_total'] > 0): ?>
            <div class="bg-white rounded-xl p-8 shadow-lg border border-slate-100 mb-6">
                <div class="flex items-center gap-3 mb-6 pb-5 border-b-2 border-amber-500">
                    <div class="bg-amber-100 p-3 rounded-lg">
                        <span class="text-2xl">💰</span>
                    </div>
                    <h2 class="text-2xl font-bold text-slate-800 m-0">Rincian Biaya</h2>
                </div>
                
                <!-- Info Tarif Dasar -->
                <?php if (isset($hasil_perhitungan['tarif_info'])): ?>
                <div class="bg-blue-50 p-5 rounded-xl mb-5 border-l-4 border-blue-500">
                    <h3 class="text-base font-bold text-blue-900 mb-3 flex items-center gap-2">
                        <span>📊</span> Informasi Tarif
                    </h3>
                    <div class="text-sm text-blue-800">
                        <p class="mb-2">
                            <strong>NJKB:</strong> 
                            <?php echo $hasil_perhitungan['tarif_info']['njkb']; ?>
                        </p>
                        <p class="mb-1">
                            <strong>Tarif Dasar PKB:</strong> 
                            <?php echo str_replace('.', ',', number_format($hasil_perhitungan['tarif_info']['pct_trf'], 1)); ?>% 
                            x <?php echo $hasil_perhitungan['tarif_info']['njkb']; ?>
                            x <?php echo number_format($hasil_perhitungan['tarif_info']['pct_pkb'], 1); ?>%
                        </p>
                        <?php if (!empty($hasil_perhitungan['tarif_info']['gunakan_opsen'])): ?>
                        <p class="mb-1 mt-2 text-blue-900 font-semibold">
                            <strong>OPSEN:</strong> 
                            <?php echo $hasil_perhitungan['tarif_info']['pct_opsen']; ?>% x PKB
                            <span class="text-xs font-normal">(Berlaku sejak 5 Januari 2025)</span>
                        </p>
                        <?php endif; ?>
                        <p class="text-xs text-blue-600 italic">
                            <?php if ($hasil_perhitungan['tarif_info']['sumber_njkb'] == 'database'): ?>
                                ✓ Data NJKB dari database resmi
                            <?php else: ?>
                                ⚠️ Estimasi NJKB (data tidak ditemukan di database)
                            <?php endif; ?>
                        </p>
                        <?php if (!empty($hasil_perhitungan['tarif_info']['progresif'])): ?>
                        <p class="mt-2 text-xs bg-blue-100 p-2 rounded">
                            ⚠️ <?php echo $hasil_perhitungan['tarif_info']['progresif']; ?>
                        </p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Notifikasi Pemutihan Pajak -->
                <?php if (isset($hasil_perhitungan['pemutihan_pkb']) && $hasil_perhitungan['pemutihan_pkb']['aktif']): ?>
                <div class="bg-green-50 p-5 rounded-xl mb-5 border-l-4 border-green-500 shadow-sm">
                    <h3 class="text-base font-bold text-green-900 mb-3 flex items-center gap-2">
                        <span>🎉</span> Pemutihan Pajak Aktif!
                    </h3>
                    <div class="text-sm text-green-800">
                        <p class="mb-1">
                            Anda mendapatkan keringanan dari program pemutihan pajak.
                        </p>
                        <p class="mb-1">
                            <strong>PKB Sebelum Pemutihan:</strong> 
                            <?php echo formatRupiah($hasil_perhitungan['pemutihan_pkb']['total_awal']); ?>
                        </p>
                        <p class="mb-1">
                            <strong>PKB Setelah Pemutihan:</strong> 
                            <?php echo formatRupiah($hasil_perhitungan['pemutihan_pkb']['total_akhir']); ?>
                        </p>
                        <p class="font-semibold text-green-900">
                            <strong>Dibebaskan:</strong> 
                            <?php echo formatRupiah($hasil_perhitungan['pemutihan_pkb']['jumlah_dibebaskan']); ?>
                        </p>
                        <?php if (isset($hasil_perhitungan['pemutihan_swd']) && $hasil_perhitungan['pemutihan_swd']['aktif']): ?>
                        <hr class="my-2 border-green-200">
                        <p class="mb-1">
                            <strong>SWDKLLJ Sebelum Pemutihan:</strong> 
                            <?php echo formatRupiah($hasil_perhitungan['pemutihan_swd']['total_awal']); ?>
                        </p>
                        <p class="mb-1">
                            <strong>SWDKLLJ Setelah Pemutihan:</strong> 
                            <?php echo formatRupiah($hasil_perhitungan['pemutihan_swd']['total_akhir']); ?>
                        </p>
                        <p class="font-semibold text-green-900">
                            <strong>Dibebaskan:</strong> 
                            <?php echo formatRupiah($hasil_perhitungan['pemutihan_swd']['jumlah_dibebaskan']); ?>
                        </p>
                        <?php endif; ?>
                        <p class="mt-2 text-xs text-green-600 italic">
                            ✓ Manfaatkan program pemutihan ini sebelum masa berlaku berakhir
                        </p>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- PKB -->
                <div class="bg-slate-50 p-4 rounded-lg mb-2.5 flex justify-between items-center">
                    <div>
                        <div class="font-semibold text-slate-600">PKB (Pajak Kendaraan Bermotor)</div>
                        <small class="text-slate-500"><?php echo $hasil_perhitungan['detail_pkb']; ?></small>
                    </div>
                    <div class="text-xl font-bold text-slate-800"><?php echo formatRupiah($hasil_perhitungan['total_pkb']); ?></div>
                </div>
                
                <!-- Detail PKB Per Tahun -->
                <?php if (isset($hasil_perhitungan['pkb_per_tahun']) && count($hasil_perhitungan['pkb_per_tahun']) > 0): ?>
                    <div class="pl-5 mb-4">
                        <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
                            <table class="w-full text-sm">
                                <thead class="bg-gradient-to-r from-slate-100 to-slate-50">
                                    <tr class="border-b-2 border-slate-300">
                                        <th class="px-4 py-3.5 text-left text-slate-700 font-bold text-xs uppercase tracking-wider">Periode</th>
                                        <th class="px-4 py-3.5 text-center text-slate-700 font-bold text-xs uppercase tracking-wider">Tanggal Akhir</th>
                                        <!-- <th class="px-4 py-3.5 text-center text-slate-700 font-bold text-xs uppercase tracking-wider">Tahun</th> -->
                                        <th class="px-4 py-3.5 text-right text-slate-700 font-bold text-xs uppercase tracking-wider">Pokok</th>
                                        <th class="px-4 py-3.5 text-right text-slate-700 font-bold text-xs uppercase tracking-wider">Denda</th>
                                        <th class="px-3 py-3.5 text-right text-slate-700 font-bold text-xs uppercase tracking-wider">Opsen</th>
                                        <th class="px-3 py-3.5 text-right text-slate-700 font-bold text-xs uppercase tracking-wider">Denda Opsen</th>
                                        <th class="px-4 py-3.5 text-right text-slate-700 font-bold text-xs uppercase tracking-wider">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200">
                                    <?php foreach ($hasil_perhitungan['pkb_per_tahun'] as $tahun => $detail): ?>
                                    <tr class="hover:bg-slate-50 transition-colors <?php echo !empty($detail['opsen_berlaku']) ? 'bg-blue-50' : ''; ?>">
                                        <td class="px-4 py-3">
                                            <?php if ($tahun == 0): ?>
                                                <span class="font-semibold text-slate-800">Tahun Berjalan</span>
                                            <?php else: ?>
                                                <span class="text-slate-600">Tunggakan Thn -<?php echo $tahun; ?></span>
                                            <?php endif; ?>
                                            <?php if (!empty($detail['opsen_berlaku'])): ?>
                                                <br><span class="inline-block mt-1 px-2 py-0.5 bg-blue-200 text-blue-800 rounded-full text-xs font-medium">✓ Opsen berlaku</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-4 py-3 text-center text-slate-600 text-xs font-medium"><?php echo $detail['tgl_periode'] ?? '-'; ?></td>
                                        <!-- <td class="px-4 py-3 text-center text-slate-600 font-semibold"><?php echo $detail['tahun'] ?? '-'; ?></td> -->
                                        <td class="px-4 py-3 text-right text-slate-700"><?php echo formatRupiah($detail['pokok']); ?></td>
                                        <td class="px-4 py-3 text-right text-red-600 font-medium"><?php echo formatRupiah($detail['denda']); ?></td>
                                        <td class="px-3 py-3 text-right <?php echo !empty($detail['opsen_berlaku']) ? 'text-blue-700 font-semibold' : 'text-slate-400'; ?>">
                                            <?php echo !empty($detail['opsen_berlaku']) && $detail['opsen_pokok'] > 0 ? formatRupiah($detail['opsen_pokok']) : '-'; ?>
                                        </td>
                                        <td class="px-3 py-3 text-right <?php echo !empty($detail['opsen_berlaku']) ? 'text-blue-600 font-medium' : 'text-slate-400'; ?>">
                                            <?php echo !empty($detail['opsen_berlaku']) && $detail['opsen_denda'] > 0 ? formatRupiah($detail['opsen_denda']) : '-'; ?>
                                        </td>
                                        <td class="px-4 py-3 text-right font-bold text-slate-800"><?php echo formatRupiah($detail['total']); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    </div>
                <?php elseif ($hasil_perhitungan['pokok_pkb'] > 0 || $hasil_perhitungan['denda_pkb'] > 0): ?>
                    <div class="pl-5 mb-2.5">
                        <small class="text-sm text-slate-600">
                            Pokok: <?php echo formatRupiah($hasil_perhitungan['pokok_pkb']); ?> | 
                            Denda: <?php echo formatRupiah($hasil_perhitungan['denda_pkb']); ?>
                        </small>
                    </div>
                <?php endif; ?>
                
                <!-- OPSEN PKB (jika berlaku) -->
                <?php if (!empty($hasil_perhitungan['gunakan_opsen']) && $hasil_perhitungan['total_opsen'] > 0): ?>
                <div class="bg-blue-50 p-4 rounded-lg mb-2.5 border border-blue-200">
                    <div class="flex justify-between items-center mb-2">
                        <div>
                            <div class="font-semibold text-blue-800">OPSEN PKB</div>
                            <small class="text-blue-600">Opsen Pajak Kendaraan Bermotor (66% x PKB) - Berlaku sejak 5 Jan 2025</small>
                        </div>
                        <div class="text-xl font-bold text-blue-900"><?php echo formatRupiah($hasil_perhitungan['total_opsen']); ?></div>
                    </div>
                    <div class="pl-0">
                        <small class="text-sm text-blue-700">
                            Pokok: <?php echo formatRupiah($hasil_perhitungan['pokok_opsen']); ?> | 
                            Denda: <?php echo formatRupiah($hasil_perhitungan['denda_opsen']); ?>
                        </small>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- SWDKLLJ -->
                <div class="bg-slate-50 p-4 rounded-lg mb-2.5 flex justify-between items-center">
                    <div>
                        <div class="font-semibold text-slate-600">SWDKLLJ (Dana Kecelakaan)</div>
                        <small class="text-slate-500">Sumbangan Wajib Dana Kecelakaan Lalu Lintas</small>
                    </div>
                    <div class="text-xl font-bold text-slate-800"><?php echo formatRupiah($hasil_perhitungan['total_swdkllj']); ?></div>
                </div>
                
                <!-- Detail SWDKLLJ Per Tahun -->
                <?php if (isset($hasil_perhitungan['swd_per_tahun']) && count($hasil_perhitungan['swd_per_tahun']) > 0): ?>
                    <div class="pl-5 mb-4">
                        <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
                            <table class="w-full text-sm">
                                <thead class="bg-slate-100">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-slate-700">Periode</th>
                                        <th class="px-3 py-2 text-center text-slate-700">Tanggal Akhir</th>
                                        <!-- <th class="px-3 py-2 text-center text-slate-700">Tahun</th> -->
                                        <th class="px-3 py-2 text-right text-slate-700">Pokok</th>
                                        <th class="px-3 py-2 text-right text-slate-700">Denda</th>
                                        <th class="px-3 py-2 text-right text-slate-700">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200">
                                    <?php foreach ($hasil_perhitungan['swd_per_tahun'] as $tahun => $detail): ?>
                                    <tr>
                                        <td class="px-3 py-2">
                                            <?php if ($tahun == 0): ?>
                                                <span class="font-medium text-slate-700">Tahun Berjalan</span>
                                            <?php else: ?>
                                                <span class="text-slate-600">Tunggakan Thn -<?php echo $tahun; ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-3 py-2 text-center text-slate-600 text-xs"><?php echo $detail['tgl_periode'] ?? '-'; ?></td>
                                        <!-- <td class="px-3 py-2 text-center text-slate-600 font-medium"><?php echo $detail['tahun'] ?? '-'; ?></td> -->
                                        <td class="px-3 py-2 text-right"><?php echo formatRupiah($detail['pokok']); ?></td>
                                        <td class="px-3 py-2 text-right text-red-600"><?php echo formatRupiah($detail['denda']); ?></td>
                                        <td class="px-3 py-2 text-right font-semibold"><?php echo formatRupiah($detail['total']); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php elseif ($hasil_perhitungan['pokok_swdkllj'] > 0 || $hasil_perhitungan['denda_swdkllj'] > 0): ?>
                    <div class="pl-5 mb-2.5">
                        <small class="text-sm text-slate-600">
                            Pokok: <?php echo formatRupiah($hasil_perhitungan['pokok_swdkllj']); ?> | 
                            Denda: <?php echo formatRupiah($hasil_perhitungan['denda_swdkllj']); ?>
                        </small>
                    </div>
                <?php endif; ?>
                
                <!-- STNK -->
                <?php if ($hasil_perhitungan['biaya_stnk'] > 0): ?>
                    <div class="bg-slate-50 p-4 rounded-lg mb-2.5 flex justify-between items-center">
                        <div>
                            <div class="font-semibold text-slate-600">Biaya STNK</div>
                            <small class="text-slate-500">Penerbitan/Perpanjangan STNK</small>
                        </div>
                        <div class="text-xl font-bold text-slate-800"><?php echo formatRupiah($hasil_perhitungan['biaya_stnk']); ?></div>
                    </div>
                <?php endif; ?>
                
                <!-- TNKB -->
                <?php if ($hasil_perhitungan['biaya_tnkb'] > 0): ?>
                    <div class="bg-slate-50 p-4 rounded-lg mb-2.5 flex justify-between items-center">
                        <div>
                            <div class="font-semibold text-slate-600">Biaya TNKB (Plat Nomor)</div>
                            <small class="text-slate-500">Penggantian Plat Nomor</small>
                        </div>
                        <div class="text-xl font-bold text-slate-800"><?php echo formatRupiah($hasil_perhitungan['biaya_tnkb']); ?></div>
                    </div>
                <?php endif; ?>
                
                <!-- Total -->
                <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 text-white p-8 rounded-2xl my-6 text-center shadow-xl border-2 border-emerald-500">
                    <h3 class="text-sm font-bold mb-3 opacity-90 uppercase tracking-widest">Total Yang Harus Dibayar</h3>
                    <div class="text-5xl font-extrabold my-4 drop-shadow-lg"><?php echo formatRupiah($hasil_perhitungan['grand_total']); ?></div>
                    <?php if ($hasil_perhitungan['tgl_jatuh_tempo_baru']): ?>
                        <p class="mt-2.5 opacity-90">
                            Berlaku s/d: <?php echo $hasil_perhitungan['tgl_jatuh_tempo_baru']; ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Informasi Tambahan -->
            <div class="p-4 mb-5 rounded-lg border-l-4 bg-blue-50 text-blue-900 border-blue-500">
                <strong>ℹ️ Informasi Penting:</strong>
                <ul class="mt-2 ml-5 list-disc">
                    <li><strong>Perhitungan bersifat ESTIMASI.</strong> Sistem ini menggunakan rumus perkiraan berdasarkan tahun kendaraan dan CC untuk menghitung NJKB.</li>
                    <li><strong>Jika ada selisih/perbedaan perhitungan, maka yang digunakan adalah hasil perhitungan petugas SAMSAT.</strong></li>
                    <li>Untuk perhitungan akurat dengan NJKB resmi, gunakan layanan <a href="infopkb.php" class="text-blue-700 underline font-semibold">Info PKB Official</a> atau kunjungi SAMSAT terdekat.</li>
                    <li>Pembayaran dapat dilakukan di SAMSAT, Bank, atau melalui aplikasi resmi.</li>
                    <li>Pastikan membawa dokumen asli saat melakukan pembayaran.</li>
                </ul>
            </div>
            
        <?php else: ?>
            <div class="p-4 mb-5 rounded-lg border-l-4 bg-green-50 text-green-900 border-green-500">
                <strong>✅ Informasi:</strong> Data kendaraan ditemukan, namun tidak ada biaya yang perlu dibayarkan saat ini.
            </div>
        <?php endif; ?>
            
        <?php elseif ($form_submitted && !$data_kendaraan && !$error_message): ?>
            <div class="p-4 mb-5 rounded-lg border-l-4 bg-yellow-50 text-yellow-900 border-yellow-500">
                <strong>⚠️ Data Tidak Ditemukan!</strong><br>
                Silakan periksa kembali nomor polisi yang Anda masukkan.
            </div>
        <?php endif; ?>
        
        <!-- Footer Info -->
        <div class="text-center text-white mt-7 p-5">
            <p>&copy; <?php echo date('Y'); ?> <?php echo APP_NAME; ?></p>
            <p class="text-sm opacity-80echo APP_NAME; ?></p>
            <p style="font-size: 0.9em; opacity: 0.8;">Aplikasi ini untuk kemudahan informasi. Untuk transaksi resmi, hubungi SAMSAT terdekat.</p>
        </div>
    </div>
</body>
</html>
