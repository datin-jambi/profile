<?php
// test-db.php
require __DIR__ . "/config/database.php";  // Ganti database.php jadi koneksi.php

echo "<!DOCTYPE html>
<html lang='id'>
<head>
    <meta charset='UTF-8'>
    <title>Test Koneksi Database</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .success { color: green; background: #d4edda; padding: 15px; border-radius: 5px; }
        .error { color: red; background: #f8d7da; padding: 15px; border-radius: 5px; }
        .info { background: #d1ecf1; padding: 10px; margin: 10px 0; border-radius: 5px; }
    </style>
</head>
<body>";

if ($pdo) {
    echo "<div class='success'>";
    echo "<h2>✅ Koneksi Database Berhasil!</h2>";
    
    // Test query sederhana
    try {
        $stmt = $pdo->query("SELECT version()");
        $version = $stmt->fetch();
        
        echo "<div class='info'>";
        echo "<strong>PostgreSQL Version:</strong><br>";
        echo htmlspecialchars($version['version']);
        echo "</div>";
        
        // Test count tables
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM information_schema.tables WHERE table_schema = 'public'");
        $tables = $stmt->fetch();
        
        echo "<div class='info'>";
        echo "<strong>Total Tables:</strong> " . $tables['total'];
        echo "</div>";
        
    } catch (PDOException $e) {
        echo "<div class='error'>";
        echo "<strong>Error saat query:</strong><br>";
        echo htmlspecialchars($e->getMessage());
        echo "</div>";
    }
    
    echo "</div>";
} else {
    echo "<div class='error'>";
    echo "<h2>❌ Koneksi Database Gagal!</h2>";
    echo "</div>";
}

echo "</body></html>";