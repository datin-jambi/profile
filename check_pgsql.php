<?php
// Quick check untuk PostgreSQL extension
echo "<!DOCTYPE html>";
echo "<html><head><title>Check PostgreSQL</title></head><body>";
echo "<h2>PostgreSQL Extension Check</h2>";

if (function_exists('pg_connect')) {
    echo "<p style='color: green; font-size: 20px;'>✅ Extension PostgreSQL SUDAH AKTIF!</p>";
    echo "<p>Anda bisa menggunakan pg_connect() sekarang.</p>";
} else {
    echo "<p style='color: red; font-size: 20px;'>❌ Extension PostgreSQL BELUM AKTIF!</p>";
    echo "<p>Silakan aktifkan extension=pgsql di php.ini dan restart Apache.</p>";
}

echo "<hr>";
echo "<h3>Loaded Extensions:</h3>";
echo "<pre>";
$extensions = get_loaded_extensions();
foreach ($extensions as $ext) {
    if (stripos($ext, 'pgsql') !== false || stripos($ext, 'pdo') !== false) {
        echo "✅ $ext\n";
    }
}
echo "</pre>";

echo "<p><a href='test_koneksi.php'>← Kembali ke Test Koneksi</a></p>";
echo "</body></html>";
?>
