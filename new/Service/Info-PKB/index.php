<?php
include_once "../../../pgdbtool.php";
include_once "../../../samlib.php";

$error = '';
$result = []; // Inisialisasi sebagai array

$found = false;

if (isset($dbonl) && !$dbonl->connected) {
    $error = "Database not connected!";
}

// Inisialisasi nilai default untuk result
$result['nm_lokasi']  = "-";
$result['date_now'] = date("d/m/Y");

$no_polisi = isset($_POST['no_polisi']) ? setnopol($_POST['no_polisi']) : '';
$nm_pemilik = isset($_POST['nm_pemilik']) ? strtoupper(trim($_POST['nm_pemilik'])) : '';
$noka = isset($_POST['noka']) ? (int)$_POST['noka'] : 0;
$nosin = isset($_POST['nosin']) ? (int)$_POST['nosin'] : 0;
$row = null;
$table = '';

$where = $nm_pemilik ? "AND nm_pemilik LIKE '%$nm_pemilik%'" : '';
$tg_bayar = "1990-01-01";

if ($no_polisi) {
    // 1. Cek data transaksi tahun berjalan
    $query = "SELECT nm_merek_kb, nm_model_kb, nm_jenis_kb, th_rakitan, jumlah_cc, warna_kb, kd_plat, kd_bbm, kd_mohon, no_chasis, no_mesin, tg_akhir_pkb, tg_bayar, kd_lokasi, no_urut_trn, kd_merek_kb FROM t_trnkb WHERE no_polisi = '$no_polisi' AND tg_bayar > '$tg_bayar' $where ORDER BY tg_bayar DESC, no_urut_trn DESC";
    $row = $dbonl->getrow($query, "d/m/Y");
    if ($row) {
        $result = $row;
        $result['date_now'] = date("d/m/Y"); // Tambahkan date_now
        $tg_bayar = to_dbdate($row['tg_bayar']);
        $found = 1;
        $table = 't_trnkb';
        $result['nm_lokasi'] = cekLokasiBayar($result['kd_lokasi']);
        $njkb = nilaiJualKendaraan($result['kd_merek_kb'], $result['th_rakitan']);
        if($njkb) {
            $result['nilai_jual_kb'] = "Rp" . number_format($njkb['nilai_jual'], 0, ',', '.') . ",- x " .str_replace(".", ",", $njkb['bobot']);
        } else {
            $result['nilai_jual_kb'] = "-";
        }
    }
    // 2. Cek data master jika belum ditemukan
    if (!$found) {
        $query = "SELECT nm_merek_kb, nm_model_kb, nm_jenis_kb, th_rakitan, jumlah_cc, warna_kb, kd_plat, kd_bbm, no_chasis, no_mesin, tg_akhir_pkb, tg_bayar, kd_merek_kb, '-' FROM t_mstkb WHERE no_polisi = '$no_polisi' AND tg_bayar > '$tg_bayar' $where";
        $row = $dbonl->getrow($query, "d/m/Y");
        if ($row) {
            $result = p_mst2trn($row);
            $result['date_now'] = date("d/m/Y"); // Tambahkan date_now
            $tg_bayar = to_dbdate($row['tg_bayar']);
            $found = 2;
            $table = 't_mstkb';
            $result['nm_lokasi'] = cekLokasiBayar($result['kd_lokasi']);
        }
    }
    // 3. Cek data transaksi selesai jika belum ditemukan
    if (!$found) {
        $query = "SELECT nm_merek_kb, nm_model_kb, nm_jenis_kb, th_rakitan, jumlah_cc, warna_kb, kd_plat, kd_bbm, no_chasis, no_mesin, tg_akhir_pkb, tg_bayar, no_urut_trn, kd_mohon FROM tt_trnkb WHERE no_polisi = '$no_polisi' AND tg_bayar > '$tg_bayar' $where ORDER BY tg_bayar DESC, no_urut_trn DESC";
        $row = $dbonl->getrow($query, "d/m/Y");
        if ($row) {
            $result = $row;
            $result['date_now'] = date("d/m/Y"); // Tambahkan date_now
            $found = 3;
            $table = 'tt_trnkb';
            $result['nm_lokasi'] = cekLokasiBayar($result['kd_lokasi']);
        }
    }
    // 4. Validasi kd_mohon
    if ($found && isset($result['kd_mohon'])) {
        $kd_mohon = $result['kd_mohon'];
        if (preg_match('/[36X]X/', $kd_mohon) || preg_match('/3[34]/', $kd_mohon)) {
            $found = false;
        }
    }
    // 5. Update tg_akhir_pkb jika ada data swdkllj
    if ($found) {
        $s = to_dbdate($result['tg_akhir_pkb']);
        $query = "SELECT tg_akhir_jr FROM t_datapnrjr WHERE no_polisi = '$no_polisi' AND tg_akhir_jr > '$s' ORDER BY tg_akhir_jr DESC";
        $s = $dbonl->getvalue($query, "d/m/Y");
        if ($s) $result['tg_akhir_pkb'] = $s;
    }
}

function nilaiJualKendaraan($kdMerekKb, $thnRakitan) {
    global $dbonl;
    $sql = "SELECT * FROM t_trf_nj
                WHERE kd_merek_kb = '$kdMerekKb'
                  AND thn = $thnRakitan";
    $response = $dbonl->getrow($sql);
    if (!$response) {
        // $response = null;
        set_error("MOHON MAAF, TARIF NILAI JUAL KENDARAAN ANDA BELUM TERDATA!", -4);
        return false;
    }

    return $response;
}

function cekLokasiBayar($kdLokasi) {
    global $dbonl;
    $nmLokasi = $dbonl->getvalue("SELECT nm_lokasi 
                            FROM t_nm_lokasi
                        WHERE kd_lokasi = '$kdLokasi'");
    return $nmLokasi ? $nmLokasi : "-";
}

$njkb = nilaiJualKendaraan($result['kd_merek_kb'], $result['th_rakitan']);
if($njkb) {
    $result['nilai_jual_kb'] = "Rp" . number_format($njkb['nilai_jual'], 0, ',', '.') . ",- x " .str_replace(".", ",", $njkb['bobot']);
} else {
    $result['nilai_jual_kb'] = "-";
}

// Bentuk dasar tagihan
$tagihan = [
    'terakhir_bayar' => $result['tg_bayar'] ?? null,
    'njkb'           => $njkb ? $njkb['nilai_jual'] : 0,
    'bobot'          => $njkb ? $njkb['bobot'] : 0,
    'total_pajak'    => 0,
    'total_SWDKLJ'   => 0,
    'total_bulan'    => 0,
    'total_hari'     => 0,
    'row' => [
        'PKB'    => [],
        'SWDKLJ' => []
    ]
];

$jarak = [
    'hari'  => null,
    'bulan' => null
];

if (isset($result['tg_bayar']) && $result['tg_bayar']) {
    $jarak = jarakWaktu($result['tg_bayar']);
}

$tagihan['total_hari']  = $jarak['hari'];
$tagihan['total_bulan'] = $jarak['bulan'];

$periodeTagihan = [];

if ($tagihan['total_bulan'] > 0 && $tagihan['terakhir_bayar']) {
    $periodeTagihan = mappingPeriodeTahunan(
        $tagihan['terakhir_bayar'],
        $tagihan['total_bulan']
    );
}

$cutoffOpsen = new DateTime('2025-01-06');

// Buat struktur tagihan berdasarkan periode yang didapat
foreach ($periodeTagihan as $p) {

    // tentukan akhir periode
    $akhirPeriode = new DateTime($p['sampai_tahun'] . '-01-01');

    // opsen berlaku jika sebelum cutoff
    $isOpsen = $akhirPeriode > $cutoffOpsen;

    // PKB
    $tagihan['row']['PKB'][] = [
        'is_opsen'        => $isOpsen,
        'periode_ke'   => $p['periode_ke'],
        'periode'      => $p['label'],
        'dari_tahun'   => $p['dari_tahun'],
        'sampai_tahun' => $p['sampai_tahun'],
        'total_bulan'  => $p['total_bulan'],
        'pokok'        => 0,
        'denda'        => 0,
        'opsen'        => 0,
        'denda_opsen'  => 0,
        'total'        => 0
    ];

    // SWDKLJ
    $tagihan['row']['SWDKLJ'][] = [
        'periode_ke'   => $p['periode_ke'],
        'periode'      => $p['label'],
        'dari_tahun'   => $p['dari_tahun'],
        'sampai_tahun' => $p['sampai_tahun'],
        'total_bulan'  => $p['total_bulan'],
        'pokok'        => 0,
        'denda'        => 0,
        'total'        => 0
    ];
}

define('TARIF_NON_OPSEN', 0.015);
define('TARIF_OPSEN',     0.01);

define('PENGENAAN_NON_OPSEN', 1);
define('PENGENAAN_OPSEN',     0.904);

define('OPSEN_RATE', 0.66);
define('DENDA_RATE_PER_BULAN', 0.02);
define('DENDA_BASE_NON_OPSEN', 0.02);
define('DENDA_BASE_OPSEN',     0.01);
define('DENDA_MAX_BULAN',      24); // kalau mau dibatasi

foreach ($tagihan['row']['PKB'] as $i => $row) {

    // =======================
    // PKB & OPSEN (SUDAH ADA)
    // =======================
    if ($row['is_opsen']) {
        $tarif     = TARIF_OPSEN;
        $pengenaan = PENGENAAN_OPSEN;
    } else {
        $tarif     = TARIF_NON_OPSEN;
        $pengenaan = PENGENAAN_NON_OPSEN;
    }

    $pkb = $tarif
         * $tagihan['njkb']
         * $tagihan['bobot']
         * $pengenaan;

    $opsen = $row['is_opsen']
        ? $pkb * OPSEN_RATE
        : 0;

    // =======================
    // DENDA
    // =======================
    $bulanTelat = min($row['total_bulan'], DENDA_MAX_BULAN);

    if ($row['is_opsen']) {
        $baseDenda = DENDA_BASE_OPSEN;
    } else {
        $baseDenda = DENDA_BASE_NON_OPSEN;
    }

    $faktorDenda = $baseDenda + ($bulanTelat * DENDA_RATE_PER_BULAN);

    $dendaPKB   = $pkb * $faktorDenda;
    $dendaOpsen = $row['is_opsen']
        ? $opsen * $faktorDenda
        : 0;

    // =======================
    // ASSIGN
    // =======================
    $tagihan['row']['PKB'][$i]['pokok']       = round($pkb);
    $tagihan['row']['PKB'][$i]['opsen']        = round($opsen);
    $tagihan['row']['PKB'][$i]['denda']        = round($dendaPKB);
    $tagihan['row']['PKB'][$i]['denda_opsen']  = round($dendaOpsen);
    $tagihan['row']['PKB'][$i]['total']        = round(
        $pkb + $opsen + $dendaPKB + $dendaOpsen
    );

    // akumulasi total pajak
    $tagihan['total_pajak'] += $tagihan['row']['PKB'][$i]['total'];
}



// fugsi untuk mengecek total jarak kapan terakhir bayar PKB
function jarakWaktu($tgl_bayar) {
    if (!$tgl_bayar) {
        return [
            'hari' => null,
            'bulan' => null
        ];
    }

    $date1 = new DateTime($tgl_bayar);
    $date2 = new DateTime(date("Y-m-d"));
    $interval = $date1->diff($date2);

    return [
        'hari'  => $interval->days,
        'bulan' => ($interval->y * 12) + $interval->m
    ];
}

function mappingPeriodeTahunan($tgl_bayar, $total_bulan) {
    if (!$tgl_bayar || !$total_bulan || $total_bulan <= 0) {
        return [];
    }

    $periode = [];
    $dateAwal = new DateTime($tgl_bayar);
    $sisaBulan = $total_bulan;
    $periodeKe = 1;

    while ($sisaBulan > 0) {
        $bulanPeriode = min(12, $sisaBulan);

        $start = clone $dateAwal;
        $end   = clone $dateAwal;
        $end->modify("+{$bulanPeriode} months");

        $periode[] = [
            'periode_ke'  => $periodeKe,
            'dari_tahun'  => (int)$start->format('Y'),
            'sampai_tahun'=> (int)$end->format('Y'),
            'total_bulan' => $bulanPeriode,
            'label'       => $start->format('Y') . '/' . $end->format('Y')
        ];

        // geser ke periode berikutnya
        $dateAwal = clone $end;
        $sisaBulan -= $bulanPeriode;
        $periodeKe++;
    }

    return $periode;
}

function p_mst2trn($vt_mstkb) {
    return [
        'nm_merek_kb'  => $vt_mstkb['nm_merek_kb'],
        'nm_model_kb'  => $vt_mstkb['nm_model_kb'],
        'nm_jenis_kb'  => $vt_mstkb['nm_jenis_kb'],
        'th_rakitan'   => $vt_mstkb['th_rakitan'],
        'jumlah_cc'    => $vt_mstkb['jumlah_cc'],
        'warna_kb'     => $vt_mstkb['warna_kb'],
        'kd_plat'      => $vt_mstkb['kd_plat'],
        'kd_bbm'       => $vt_mstkb['kd_bbm'],
        'no_chasis'    => $vt_mstkb['no_chasis'],
        'no_mesin'     => $vt_mstkb['no_mesin'],
        'tg_akhir_pkb' => $vt_mstkb['tg_akhir_pkb'],
    ];
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Info Kendaraan Bermotor - j-SAMSAT</title>
    <script src="../../../staging/tailwind/tailwind.js"></script>
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in-up { animation: fadeInUp 0.6s ease-out forwards; }
        html, body {
            min-height: 100vh;
        }
        .content-wrapper {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .main-content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding-top: 1.5rem;
            padding-bottom: 1.5rem;
        }
        @media (max-width: 640px) {
            .main-content {
                padding-top: 0.5rem;
                padding-bottom: 0.5rem;
            }
        }
        @media (max-width: 768px) {
            .max-w-2xl {
                max-width: 98vw !important;
                padding-left: 0.5rem !important;
                padding-right: 0.5rem !important;
            }
        }
        @media (min-width: 1024px) {
            .main-content {
                padding-top: 2rem;
                padding-bottom: 2rem;
            }
        }
    </style>
</head>
<body class="min-h-screen relative overflow-x-hidden">
    <!-- Background Image with Overlay -->
    <!-- <div class="fixed inset-0 z-0">
        <div class="w-full h-full bg-cover bg-center bg-no-repeat" style="background-image: url('https://jambisamsat.net/assets/images/samsatjambi.jpg');"></div>
        <div class="absolute inset-0 bg-gradient-to-br from-blue-900/80 to-indigo-900/80"></div>
    </div> -->
    <!-- Content Wrapper -->
    <div class="relative z-10 content-wrapper" style="position: relative; z-index: 10;">
        <!-- Header -->
        <header class="bg-white/95 backdrop-blur-md shadow-lg sticky top-0 z-20">
            <div class="container mx-auto px-2 sm:px-4 py-3 flex items-center justify-between min-h-[64px] gap-2">
                <a href="../../coba.php" class="inline-flex items-center px-3 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 shadow text-sm sm:text-base flex-shrink-0">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    Menu Utama
                </a>
                <div class="flex-1 text-center">
                    <h1 class="text-xl sm:text-3xl lg:text-4xl font-bold text-blue-600 mb-1 leading-tight">J-SAMSAT</h1>
                    <p class="text-xs sm:text-base lg:text-lg text-gray-600 leading-tight">INFORMASI DATA KENDARAAN</p>
                </div>
                <div class="w-[110px] sm:w-[130px] flex-shrink-0"></div>
            </div>
        </header>
        <!-- Main Content -->
        <main class="main-content">
            <div class="container mx-auto px-2 sm:px-4 py-4 lg:py-0 w-full">
                <div class="max-w-2xl mx-auto w-full">
                    <div class="text-center mb-4 lg:mb-6 fade-in-up">
                        <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold text-orange-400 mb-2 drop-shadow-lg">PENCARIAN DATA KENDARAAN</h2>
                        <p class="text-white text-xs sm:text-base lg:text-lg drop-shadow">Masukkan data kendaraan yang ingin dicari</p>
                    </div>
                    <form method="post" class="mb-8 space-y-4 bg-white/95 backdrop-blur-md rounded-xl shadow-lg p-4 sm:p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
                            <label for="no_polisi" class="w-full sm:w-32 font-semibold text-sm sm:text-base">No. Polisi</label>
                            <input type="text" id="no_polisi" name="no_polisi" class="flex-1 border border-gray-300 rounded px-3 py-2 text-base sm:text-lg uppercase focus:outline-none focus:ring-2 focus:ring-blue-400" value="<?php echo htmlspecialchars($no_polisi); ?>" required />
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
                            <label for="nm_pemilik" class="w-full sm:w-32 font-semibold text-sm sm:text-base">Nama Pemilik</label>
                            <input type="text" id="nm_pemilik" name="nm_pemilik" class="flex-1 border border-gray-300 rounded px-3 py-2 text-base sm:text-lg uppercase focus:outline-none focus:ring-2 focus:ring-blue-400" value="<?php echo htmlspecialchars($nm_pemilik); ?>" />
                        </div>
                        <div class="flex flex-col sm:flex-row gap-2 sm:gap-4">
                            <label class="inline-flex items-center text-sm sm:text-base"><input type="checkbox" name="noka" value="1" <?php if($noka) echo 'checked'; ?>> <span class="ml-2">Tampilkan No. Rangka</span></label>
                            <label class="inline-flex items-center text-sm sm:text-base"><input type="checkbox" name="nosin" value="1" <?php if($nosin) echo 'checked'; ?>> <span class="ml-2">Tampilkan No. Mesin</span></label>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                            <button type="submit" class="w-full sm:w-auto py-2 px-4 bg-blue-600 text-white font-bold rounded hover:bg-blue-700">Cari Data</button>
                            <?php if ($found && $result): ?>
                            <button type="reset" class="w-full sm:w-auto py-2 px-4 bg-gray-200 text-gray-700 font-bold rounded hover:bg-gray-300 border border-gray-300">Reset</button>
                            <?php endif; ?>
                        </div>
                    </form>
                    <?php
                        if ($found && $result) {
                            echo '<pre>';
                            // var_dump($result);
                            // var_dump($result);
                            // var_dump($result["tg_bayar"]);
                            var_dump($tagihan);
                            echo '</pre>';
                            die();
                        }
                    ?>

                    <?php if ($error): ?>
                        <div class="bg-red-100 text-red-700 p-4 rounded mb-4 text-center font-semibold text-sm sm:text-base"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <?php if ($no_polisi && !$found && !$error): ?>
                        <div class="bg-yellow-100 text-yellow-800 p-4 rounded text-center font-semibold text-sm sm:text-base">DATA TIDAK ADA</div>
                    <?php endif; ?>
                    <?php if ($found && $result): ?>
                    <div class="mt-6 bg-white/95 backdrop-blur-md rounded-xl shadow-lg p-4 sm:p-6 overflow-x-auto">
                        <h2 class="text-lg sm:text-xl font-bold mb-4 text-blue-700">DATA KENDARAAN <?php echo htmlspecialchars($no_polisi); ?></h2>
                        <table class="w-full text-base sm:text-lg border-t border-gray-200">
                            <tbody>
                                <tr>
                                    <td class="py-2 font-semibold">MEREK</td>
                                    <td class="py-2">:</td>
                                    <td class="py-2"><?php echo htmlspecialchars($result['nm_merek_kb']); ?></td>
                                </tr>
                                <tr>
                                    <td class="py-2 font-semibold">MODEL/TIPE</td>
                                    <td class="py-2">:</td>
                                    <td class="py-2"><?php echo htmlspecialchars($result['nm_model_kb']); ?></td>
                                </tr>
                                <tr>
                                    <td class="py-2 font-semibold">JENIS</td>
                                    <td class="py-2">:</td>
                                    <td class="py-2"><?php echo htmlspecialchars($result['nm_jenis_kb']); ?></td>
                                </tr>
                                <tr>
                                    <td class="py-2 font-semibold">TAHUN</td>
                                    <td class="py-2">:</td>
                                    <td class="py-2"><?php echo htmlspecialchars($result['th_rakitan']); ?></td>
                                </tr>
                                <tr>
                                    <td class="py-2 font-semibold">CC</td>
                                    <td class="py-2">:</td>
                                    <td class="py-2"><?php echo htmlspecialchars($result['jumlah_cc']); ?> cc</td>
                                </tr>
                                <tr>
                                    <td class="py-2 font-semibold">WARNA</td>
                                    <td class="py-2">:</td>
                                    <td class="py-2"><?php echo htmlspecialchars($result['warna_kb']); ?></td>
                                </tr>
                                <?php if ($noka): ?>
                                <tr>
                                    <td class="py-2 font-semibold">NO. RANGKA</td>
                                    <td class="py-2">:</td>
                                    <td class="py-2"><?php echo htmlspecialchars($result['no_chasis']); ?></td>
                                </tr>
                                <?php endif; ?>
                                <?php if ($nosin): ?>
                                <tr>
                                    <td class="py-2 font-semibold">NO. MESIN</td>
                                    <td class="py-2">:</td>
                                    <td class="py-2"><?php echo htmlspecialchars($result['no_mesin']); ?></td>
                                </tr>
                                <?php endif; ?>
                                <tr>
                                    <td class="py-2 font-semibold">TGL. AKHIR PKB</td>
                                    <td class="py-2">:</td>
                                    <td class="py-2 text-lg sm:text-xl font-bold"><?php echo htmlspecialchars($result['tg_akhir_pkb']); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
        <!-- Footer -->
        <footer class="bg-white/95 backdrop-blur-md shadow-lg">
            <div class="container mx-auto px-4 py-3 lg:py-4 text-center text-gray-600">
                <p class="text-sm lg:text-base">&copy; 2024 Dipenda Prov. Jambi - SAMSAT Jambi</p>
            </div>
        </footer>
    </div>
<script>
    // Agar tombol reset benar-benar mengosongkan form (termasuk value dari PHP)
    document.addEventListener('DOMContentLoaded', function() {
        var form = document.querySelector('form[method="post"]');
        var resetBtn = form.querySelector('button[type="reset"]');
        resetBtn.addEventListener('click', function(e) {
            e.preventDefault();
            // Reload halaman tanpa POST agar hasil pencarian juga hilang
            window.location.href = window.location.pathname;
        });
    });
</script>

<script>
    // Console log untuk debugging semua data yang diambil
    <?php if ($no_polisi): ?>
    console.log('%c=== DATA QUERY DEBUG ===', 'color: #2563eb; font-weight: bold; font-size: 16px;');
    
    console.log('%c📋 Parameter Input:', 'color: #059669; font-weight: bold;');
    console.log('  No. Polisi:', <?php echo json_encode($no_polisi); ?>);
    console.log('  Nama Pemilik:', <?php echo json_encode($nm_pemilik); ?>);
    console.log('  Tampilkan No. Rangka:', <?php echo json_encode((bool)$noka); ?>);
    console.log('  Tampilkan No. Mesin:', <?php echo json_encode((bool)$nosin); ?>);
    
    <?php if ($found && $result): ?>
    console.log('%c\n✅ DATA BERHASIL DITEMUKAN', 'color: #16a34a; font-weight: bold; font-size: 14px;');
    console.log('%c📊 Source Table:', 'color: #7c3aed; font-weight: bold;');
    console.log('  Table:', <?php echo json_encode($table); ?>);
    console.log('  Keterangan:', <?php 
        if ($found == 1) echo '"Data Transaksi Tahun Berjalan (t_trnkb)"';
        elseif ($found == 2) echo '"Data Master Kendaraan (t_mstkb)"';
        elseif ($found == 3) echo '"Data Transaksi Selesai (tt_trnkb)"';
    ?>);
    
    console.log('%c\n🚗 Data Kendaraan Lengkap:', 'color: #dc2626; font-weight: bold;');
    console.table(<?php echo json_encode($result); ?>);
    
    console.log('%c\n📝 Detail Data:', 'color: #ea580c; font-weight: bold;');
    console.log('  Merek:', <?php echo json_encode($result['nm_merek_kb']); ?>);
    console.log('  Model/Tipe:', <?php echo json_encode($result['nm_model_kb']); ?>);
    console.log('  Jenis:', <?php echo json_encode($result['nm_jenis_kb']); ?>);
    console.log('  Tahun:', <?php echo json_encode($result['th_rakitan']); ?>);
    console.log('  CC:', <?php echo json_encode($result['jumlah_cc']); ?>);
    console.log('  Warna:', <?php echo json_encode($result['warna_kb']); ?>);
    <?php if ($noka): ?>
    console.log('  No. Rangka:', <?php echo json_encode($result['no_chasis']); ?>);
    <?php endif; ?>
    <?php if ($nosin): ?>
    console.log('  No. Mesin:', <?php echo json_encode($result['no_mesin']); ?>);
    <?php endif; ?>
    console.log('  Tgl. Sekarang:', <?php echo json_encode($result['date_now']); ?>);
    console.log('  Tgl. Akhir PKB:', <?php echo json_encode($result['tg_akhir_pkb']); ?>);
    console.log('  Lokasi Bayar:', <?php echo json_encode($result['nm_lokasi']); ?>);
    console.log('  Nilai Jual Kendaraan:', <?php echo json_encode($result['nilai_jual_kb']); ?>);
    
    <?php elseif ($no_polisi && !$found && !$error): ?>
    console.warn('%c⚠️ DATA TIDAK DITEMUKAN', 'color: #ca8a04; font-weight: bold; font-size: 14px;');
    console.log('Pencarian untuk No. Polisi:', <?php echo json_encode($no_polisi); ?>);
    console.log('Status: Tidak ada data di semua tabel (t_trnkb, t_mstkb, tt_trnkb)');
    <?php endif; ?>
    
    <?php if ($error): ?>
    console.error('%c❌ ERROR', 'color: #dc2626; font-weight: bold; font-size: 14px;');
    console.error('Error Message:', <?php echo json_encode($error); ?>);
    <?php endif; ?>
    
    console.log('%c========================', 'color: #2563eb; font-weight: bold;');
    <?php endif; ?>
</script>
</body>
</html>