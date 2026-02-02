<?php
$host     = "103.190.214.224";
// $host     = "192.168.0.3";
$port     = "5432";
$dbname   = "pgsamsatdb24";
// $dbname   = "pgsamsatoldb";
$user     = "samsat";
$password = "samsat";

$pdo = null;
try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password, array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ));
} catch (PDOException $e) {
    // Define BASE_URL dulu sebelum load layout
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    if (!defined('BASE_URL')) {
        define('BASE_URL', rtrim($scriptDir, '/'));
    }
    
    $dbErrorMessage = $e->getMessage();
    http_response_code(500);
    $title = "500 - Kesalahan Koneksi Database";
    $metaDesc = "Sistem mengalami gangguan koneksi database.";
    
    ob_start();
    include __DIR__ . "/../pages/error-db.php";
    $content = ob_get_clean();
    include __DIR__ . "/../layouts/main.php";
    exit;
}