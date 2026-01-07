<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Koneksi Database</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            min-height: 100vh;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        h1 {
            color: white;
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.5em;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        .card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            transition: transform 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card h2 {
            color: #333;
            margin-bottom: 15px;
            border-bottom: 3px solid #667eea;
            padding-bottom: 10px;
        }
        .status {
            display: flex;
            align-items: center;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
            font-weight: 600;
        }
        .success {
            background: #d4edda;
            color: #155724;
            border-left: 5px solid #28a745;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            border-left: 5px solid #dc3545;
        }
        .info {
            background: #d1ecf1;
            color: #0c5460;
            border-left: 5px solid #17a2b8;
        }
        .icon {
            font-size: 24px;
            margin-right: 10px;
        }
        .details {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-top: 10px;
            font-size: 14px;
        }
        .details strong {
            color: #667eea;
        }
        .timestamp {
            text-align: center;
            color: white;
            margin-top: 20px;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table td {
            padding: 8px;
            border-bottom: 1px solid #dee2e6;
        }
        table td:first-child {
            font-weight: 600;
            width: 150px;
            color: #667eea;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Test Koneksi Database</h1>

<?php
// Test 1: PostgreSQL - Koneksi Utama (pgdbtool.php)
echo '<div class="card">';
echo '<h2>1. PostgreSQL - Koneksi Utama (pgdbtool.php)</h2>';

try {
    include_once("pgdbtool.php");
    $db = new pgDBTool();
    $db->connect();
    
    if ($db->connected && $db->connection_status()) {
        echo '<div class="status success">';
        echo '<span class="icon">✅</span>';
        echo '<span>Koneksi Berhasil!</span>';
        echo '</div>';
        
        echo '<div class="details">';
        echo '<table>';
        echo '<tr><td><strong>Host:</strong></td><td>' . $db->host . '</td></tr>';
        echo '<tr><td><strong>Database:</strong></td><td>' . $db->dbname . '</td></tr>';
        echo '<tr><td><strong>User:</strong></td><td>' . $db->user . '</td></tr>';
        echo '<tr><td><strong>Status:</strong></td><td>Connected</td></tr>';
        
        // Test query
        $result = pg_query($db->dbconn, "SELECT version()");
        if ($result) {
            $row = pg_fetch_assoc($result);
            echo '<tr><td><strong>PostgreSQL Version:</strong></td><td>' . substr($row['version'], 0, 50) . '...</td></tr>';
        }
        
        echo '</table>';
        echo '</div>';
    } else {
        echo '<div class="status error">';
        echo '<span class="icon">❌</span>';
        echo '<span>Koneksi Gagal!</span>';
        echo '</div>';
        
        echo '<div class="details">';
        echo '<table>';
        echo '<tr><td><strong>Host:</strong></td><td>' . $db->host . '</td></tr>';
        echo '<tr><td><strong>Database:</strong></td><td>' . $db->dbname . '</td></tr>';
        echo '<tr><td><strong>User:</strong></td><td>' . $db->user . '</td></tr>';
        $error_msg = $db->dbconn ? pg_last_error($db->dbconn) : 'Tidak dapat membuat koneksi';
        echo '<tr><td><strong>Error:</strong></td><td>' . $error_msg . '</td></tr>';
        echo '</table>';
        echo '</div>';
    }
} catch (Exception $e) {
    echo '<div class="status error">';
    echo '<span class="icon">❌</span>';
    echo '<span>Error: ' . $e->getMessage() . '</span>';
    echo '</div>';
}

echo '</div>';

// Test 2: PostgreSQL - Modul Transaksi
echo '<div class="card">';
echo '<h2>2. PostgreSQL - Modul Transaksi</h2>';

$host = "192.168.0.3";
$user = "samsat";
$password = "samsat";
$port = "5432";
$dbname = "pgsamsatoldb";

$conn_string = "host=$host port=$port dbname=$dbname user=$user password=$password";
$link = @pg_connect($conn_string);

if ($link) {
    echo '<div class="status success">';
    echo '<span class="icon">✅</span>';
    echo '<span>Koneksi Berhasil!</span>';
    echo '</div>';
    
    echo '<div class="details">';
    echo '<table>';
    echo '<tr><td><strong>Host:</strong></td><td>' . $host . '</td></tr>';
    echo '<tr><td><strong>Port:</strong></td><td>' . $port . '</td></tr>';
    echo '<tr><td><strong>Database:</strong></td><td>' . $dbname . '</td></tr>';
    echo '<tr><td><strong>User:</strong></td><td>' . $user . '</td></tr>';
    echo '<tr><td><strong>Status:</strong></td><td>Connected</td></tr>';
    
    // Test query
    $result = pg_query($link, "SELECT version()");
    if ($result) {
        $row = pg_fetch_assoc($result);
        echo '<tr><td><strong>PostgreSQL Version:</strong></td><td>' . substr($row['version'], 0, 50) . '...</td></tr>';
    }
    
    echo '</table>';
    echo '</div>';
    
    pg_close($link);
} else {
    echo '<div class="status error">';
    echo '<span class="icon">❌</span>';
    echo '<span>Koneksi Gagal!</span>';
    echo '</div>';
    
    echo '<div class="details">';
    echo '<table>';
    echo '<tr><td><strong>Host:</strong></td><td>' . $host . '</td></tr>';
    echo '<tr><td><strong>Port:</strong></td><td>' . $port . '</td></tr>';
    echo '<tr><td><strong>Database:</strong></td><td>' . $dbname . '</td></tr>';
    echo '<tr><td><strong>User:</strong></td><td>' . $user . '</td></tr>';
    $error_msg = 'Tidak dapat terhubung ke server (pastikan server PostgreSQL berjalan di ' . $host . ')';
    echo '<tr><td><strong>Error:</strong></td><td>' . $error_msg . '</td></tr>';
    echo '<tr><td><strong>Catatan:</strong></td><td>Server ini adalah server lokal/internal yang mungkin tidak aktif</td></tr>';
    echo '</table>';
    echo '</div>';
}

echo '</div>';

// Test 3: MySQL - Whistle Blower (OPTIONAL - Untuk modul terpisah)
echo '<div class="card">';
echo '<h2>3. MySQL - Whistle Blower (Opsional)</h2>';

echo '<div class="status info">';
echo '<span class="icon">ℹ️</span>';
echo '<span>Database utama aplikasi ini adalah PostgreSQL (Cloud). MySQL hanya untuk modul Whistle Blower yang terpisah.</span>';
echo '</div>';

$mysql_host = "localhost";
$mysql_user = "root";
$mysql_pass = "";
$mysql_db = "db_whistle_blower";

// Check if mysqli extension is available
if (function_exists('mysqli_connect')) {
    $mysql_conn = @mysqli_connect($mysql_host, $mysql_user, $mysql_pass, $mysql_db);
    
    if ($mysql_conn) {
        echo '<div class="status success">';
        echo '<span class="icon">✅</span>';
        echo '<span>Koneksi Berhasil!</span>';
        echo '</div>';
        
        echo '<div class="details">';
        echo '<table>';
        echo '<tr><td><strong>Host:</strong></td><td>' . $mysql_host . '</td></tr>';
        echo '<tr><td><strong>Database:</strong></td><td>' . $mysql_db . '</td></tr>';
        echo '<tr><td><strong>User:</strong></td><td>' . $mysql_user . '</td></tr>';
        echo '<tr><td><strong>Status:</strong></td><td>Connected</td></tr>';
        echo '<tr><td><strong>MySQL Version:</strong></td><td>' . mysqli_get_server_info($mysql_conn) . '</td></tr>';
        echo '</table>';
        echo '</div>';
        
        mysqli_close($mysql_conn);
    } else {
        echo '<div class="status error">';
        echo '<span class="icon">⚠️</span>';
        echo '<span>MySQL tidak tersedia (tidak masalah jika tidak digunakan)</span>';
        echo '</div>';
        
        echo '<div class="details">';
        echo '<table>';
        echo '<tr><td><strong>Host:</strong></td><td>' . $mysql_host . '</td></tr>';
        echo '<tr><td><strong>Database:</strong></td><td>' . $mysql_db . '</td></tr>';
        echo '<tr><td><strong>Catatan:</strong></td><td>MySQL hanya diperlukan untuk modul Whistle Blower. Jika tidak digunakan, abaikan error ini.</td></tr>';
        echo '</table>';
        echo '</div>';
    }
} else {
    echo '<div class="status info">';
    echo '<span class="icon">ℹ️</span>';
    echo '<span>Extension MySQLi tidak tersedia (tidak masalah untuk aplikasi utama)</span>';
    echo '</div>';
}

echo '</div>';

// PHP Info
echo '<div class="card">';
echo '<h2>📋 Informasi PHP</h2>';
echo '<div class="details">';
echo '<table>';
echo '<tr><td><strong>PHP Version:</strong></td><td>' . phpversion() . '</td></tr>';
echo '<tr><td><strong>PostgreSQL Support:</strong></td><td>' . (function_exists('pg_connect') ? '✅ Tersedia' : '❌ Tidak Tersedia') . '</td></tr>';
echo '<tr><td><strong>MySQLi Support:</strong></td><td>' . (function_exists('mysqli_connect') ? '✅ Tersedia' : '❌ Tidak Tersedia') . '</td></tr>';
echo '<tr><td><strong>PDO Support:</strong></td><td>' . (class_exists('PDO') ? '✅ Tersedia' : '❌ Tidak Tersedia') . '</td></tr>';
echo '<tr><td><strong>Timezone:</strong></td><td>' . date_default_timezone_get() . '</td></tr>';
echo '</table>';
echo '</div>';
echo '</div>';
?>

        <div class="timestamp">
            ⏰ Test dilakukan pada: <?php echo date('d-m-Y H:i:s'); ?>
        </div>
    </div>
</body>
</html>
