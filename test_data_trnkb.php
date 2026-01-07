<?php
/**
 * TEST DATA TRNKB
 * File untuk testing - melihat sample data dari tabel t_trnkb
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "pgdbtool.php";

// Buat koneksi database
$dbonl = new pgDBTool();
$dbonl->connect();

echo "<h1>Test Data dari Tabel t_trnkb</h1>";
echo "<p>Koneksi database: " . ($dbonl->connected ? "<strong style='color:green'>✓ Connected</strong>" : "<strong style='color:red'>✗ Failed</strong>") . "</p>";

if (!$dbonl->connected) {
    die("<p style='color:red'>Koneksi database gagal!</p>");
}

// Ambil 10 data sample
$query = "
    SELECT 
        no_polisi, 
        nm_pemilik, 
        nm_merek_kb, 
        nm_model_kb,
        th_rakitan,
        TO_CHAR(tg_akhir_pkb, 'DD/MM/YYYY') as tg_akhir_pkb,
        TO_CHAR(tg_bayar, 'DD/MM/YYYY') as tg_bayar,
        kd_mohon
    FROM t_trnkb
    WHERE tg_bayar > '2020-01-01'
    ORDER BY tg_bayar DESC
    LIMIT 10
";

echo "<h2>Query:</h2>";
echo "<pre style='background:#f5f5f5; padding:10px; border-left:3px solid #333;'>" . htmlspecialchars($query) . "</pre>";

$rs = $dbonl->query($query);

if (!$rs) {
    echo "<p style='color:red'>Error query: " . htmlspecialchars($dbonl->errmsg) . "</p>";
    die();
}

echo "<h2>Hasil (10 data terakhir):</h2>";
echo "<table border='1' cellpadding='10' style='border-collapse:collapse; width:100%; font-size:12px;'>";
echo "<thead style='background:#333; color:white;'>";
echo "<tr>";
echo "<th>No</th>";
echo "<th>No Polisi</th>";
echo "<th>Format (Length)</th>";
echo "<th>Pemilik</th>";
echo "<th>Merek/Model</th>";
echo "<th>Tahun</th>";
echo "<th>Tgl Akhir PKB</th>";
echo "<th>Tgl Bayar</th>";
echo "</tr>";
echo "</thead>";
echo "<tbody>";

$no = 1;
$found = false;

while ($row = $dbonl->fetch_assoc($rs)) {
    $found = true;
    $nopol = $row['no_polisi'];
    $length = strlen($nopol);
    
    // Analisis format
    $format_info = "Length: $length";
    $has_space = strpos($nopol, ' ') !== false;
    $space_count = substr_count($nopol, ' ');
    $format_info .= " | Spasi: " . ($has_space ? "✓ ($space_count)" : "✗");
    
    // Coba parse
    if (preg_match('/^([A-Z]{1,2})\s+(\d{1,4})\s+([A-Z]{1,3})$/', $nopol, $m)) {
        $format_info .= " | Format: '{$m[1]}' + '{$m[2]}' + '{$m[3]}'";
    } else {
        $format_info .= " | Format: TIDAK STANDAR";
    }
    
    echo "<tr>";
    echo "<td>$no</td>";
    echo "<td><strong>" . htmlspecialchars($nopol) . "</strong></td>";
    echo "<td><small>$format_info</small></td>";
    echo "<td>" . htmlspecialchars($row['nm_pemilik']) . "</td>";
    echo "<td>" . htmlspecialchars($row['nm_merek_kb'] . ' / ' . $row['nm_model_kb']) . "</td>";
    echo "<td>" . htmlspecialchars($row['th_rakitan']) . "</td>";
    echo "<td>" . htmlspecialchars($row['tg_akhir_pkb']) . "</td>";
    echo "<td>" . htmlspecialchars($row['tg_bayar']) . "</td>";
    echo "</tr>";
    $no++;
}

if (!$found) {
    echo "<tr><td colspan='8' style='text-align:center; color:red;'>Tidak ada data ditemukan</td></tr>";
}

echo "</tbody>";
echo "</table>";

// Test pencarian spesifik
echo "<hr style='margin: 30px 0;'>";
echo "<h2>Test Pencarian 'BH 2202 ZE'</h2>";

$test_nopol = 'BH 2202 ZE';

// Tampilkan analisis string yang dicari
echo "<h3>Analisis String Pencarian:</h3>";
echo "<table border='1' cellpadding='8' style='border-collapse:collapse; font-family:monospace;'>";
echo "<tr><th>String</th><td>" . htmlspecialchars($test_nopol) . "</td></tr>";
echo "<tr><th>Length</th><td>" . strlen($test_nopol) . " karakter</td></tr>";
echo "<tr><th>Hex</th><td>" . bin2hex($test_nopol) . "</td></tr>";
echo "<tr><th>Ord (bytes)</th><td>";
for ($i = 0; $i < strlen($test_nopol); $i++) {
    echo ord($test_nopol[$i]) . " ";
}
echo "</td></tr>";
echo "</table>";

// Query dengan kondisi berbeda untuk debugging
echo "<h3>Test Berbagai Metode Pencarian:</h3>";

// Method 1: Exact match
$query_test = "
    SELECT 
        no_polisi, 
        nm_pemilik,
        TO_CHAR(tg_bayar, 'DD/MM/YYYY') as tg_bayar,
        LENGTH(no_polisi) as len
    FROM t_trnkb
    WHERE no_polisi = '$test_nopol'
      AND tg_bayar > '1990-01-01'
    ORDER BY tg_bayar DESC
";

echo "<div style='margin:15px 0; padding:10px; background:#f0f0f0; border-left:4px solid blue;'>";
echo "<strong>Method 1: Exact Match</strong>";
echo "<pre style='background:#fff; padding:8px; margin:5px 0;'>" . htmlspecialchars($query_test) . "</pre>";

$result = $dbonl->getrow($query_test);

if ($result) {
    echo "<p style='color:green; font-weight:bold;'>✓ DITEMUKAN!</p>";
    echo "<table border='1' cellpadding='8' style='border-collapse:collapse;'>";
    echo "<tr><th>No Polisi</th><td>" . htmlspecialchars($result['no_polisi']) . "</td></tr>";
    echo "<tr><th>Pemilik</th><td>" . htmlspecialchars($result['nm_pemilik']) . "</td></tr>";
    echo "<tr><th>Length</th><td>" . $result['len'] . "</td></tr>";
    echo "</table>";
} else {
    echo "<p style='color:red; font-weight:bold;'>✗ TIDAK DITEMUKAN</p>";
}
echo "</div>";

// Method 2: LIKE dengan wildcard
$query_like = "
    SELECT 
        no_polisi, 
        nm_pemilik,
        TO_CHAR(tg_bayar, 'DD/MM/YYYY') as tg_bayar,
        LENGTH(no_polisi) as len
    FROM t_trnkb
    WHERE no_polisi LIKE 'BH%2202%ZE'
      AND tg_bayar > '1990-01-01'
    ORDER BY tg_bayar DESC
    LIMIT 5
";

echo "<div style='margin:15px 0; padding:10px; background:#f0f0f0; border-left:4px solid orange;'>";
echo "<strong>Method 2: LIKE Pattern 'BH%2202%ZE'</strong>";
echo "<pre style='background:#fff; padding:8px; margin:5px 0;'>" . htmlspecialchars($query_like) . "</pre>";

$rs_like = $dbonl->query($query_like);
if ($rs_like) {
    echo "<table border='1' cellpadding='8' style='border-collapse:collapse; font-family:monospace;'>";
    echo "<tr><th>No Polisi</th><th>Hex</th><th>Length</th><th>Pemilik</th><th>Tgl Bayar</th></tr>";
    
    $found_like = false;
    while ($row = $dbonl->fetch_assoc($rs_like)) {
        $found_like = true;
        echo "<tr>";
        echo "<td><strong>" . htmlspecialchars($row['no_polisi']) . "</strong></td>";
        echo "<td style='font-size:10px;'>" . bin2hex($row['no_polisi']) . "</td>";
        echo "<td>" . $row['len'] . "</td>";
        echo "<td>" . htmlspecialchars($row['nm_pemilik']) . "</td>";
        echo "<td>" . htmlspecialchars($row['tg_bayar']) . "</td>";
        echo "</tr>";
    }
    
    if (!$found_like) {
        echo "<tr><td colspan='5' style='text-align:center; color:red;'>Tidak ada data yang cocok</td></tr>";
    } else {
        echo "</table>";
        echo "<p style='color:green; font-weight:bold;'>✓ DITEMUKAN " . ($found_like ? "beberapa" : "0") . " data</p>";
    }
}
echo "</div>";

// Method 3: REPLACE spaces dan compare
$query_replace = "
    SELECT 
        no_polisi, 
        nm_pemilik,
        TO_CHAR(tg_bayar, 'DD/MM/YYYY') as tg_bayar,
        LENGTH(no_polisi) as len,
        REPLACE(no_polisi, ' ', '') as nopol_no_space
    FROM t_trnkb
    WHERE REPLACE(no_polisi, ' ', '') = 'BH2202ZE'
      AND tg_bayar > '1990-01-01'
    ORDER BY tg_bayar DESC
";

echo "<div style='margin:15px 0; padding:10px; background:#f0f0f0; border-left:4px solid green;'>";
echo "<strong>Method 3: REPLACE spaces (BH2202ZE)</strong>";
echo "<pre style='background:#fff; padding:8px; margin:5px 0;'>" . htmlspecialchars($query_replace) . "</pre>";

$result_replace = $dbonl->getrow($query_replace);

if ($result_replace) {
    echo "<p style='color:green; font-weight:bold;'>✓ DITEMUKAN!</p>";
    echo "<table border='1' cellpadding='8' style='border-collapse:collapse;'>";
    echo "<tr><th>No Polisi (Asli)</th><td>" . htmlspecialchars($result_replace['no_polisi']) . "</td></tr>";
    echo "<tr><th>No Polisi (No Space)</th><td>" . htmlspecialchars($result_replace['nopol_no_space']) . "</td></tr>";
    echo "<tr><th>Pemilik</th><td>" . htmlspecialchars($result_replace['nm_pemilik']) . "</td></tr>";
    echo "<tr><th>Length Asli</th><td>" . $result_replace['len'] . "</td></tr>";
    echo "</table>";
} else {
    echo "<p style='color:red; font-weight:bold;'>✗ TIDAK DITEMUKAN</p>";
}
echo "</div>";

// Info koneksi
echo "<hr style='margin: 30px 0;'>";
echo "<h2>Info Database:</h2>";
echo "<ul>";
echo "<li>Host: " . htmlspecialchars($dbonl->host) . "</li>";
echo "<li>Database: " . htmlspecialchars($dbonl->dbname) . "</li>";
echo "<li>User: " . htmlspecialchars($dbonl->user) . "</li>";
echo "</ul>";

?>
