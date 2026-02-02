<?php
require __DIR__ . "/config/database.php";

// Define BASE_URL untuk handle subfolder
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
define('BASE_URL', rtrim($scriptDir, '/'));

$route = isset($_GET['route']) ? $_GET['route'] : '';
$title = "Samsat Jambi"; // default title
$metaDesc = "Website resmi Samsat Jambi.";

// tentukan file halaman
switch ($route) {
    case '':
        $title = "Beranda - Samsat Jambi";
        ob_start();
        include "pages/home.php";
        $content = ob_get_clean();
        break;

    case 'layanan/cek-data':
        $title = "Cek Data Kendaraan - Samsat Jambi";
        ob_start();
        include "pages/layanan/cek-data.php";
        $content = ob_get_clean();
        break;

    case 'layanan/cek-pkb':
        $title = "Cek Informasi PKB - Samsat Jambi";
        ob_start();
        include "pages/layanan/cek-pkb.php";
        $content = ob_get_clean();
        break;

    case 'layanan/cek-nilai-jual':
        $title = "Cek Nilai Jual Kendaraan - Samsat Jambi";
        ob_start();
        include "pages/layanan/cek-nilai-jual.php";
        $content = ob_get_clean();
        break;

    case 'layanan/cek-progresif':
        $title = "Cek Pajak Progresif - Samsat Jambi";
        ob_start();
        include "pages/layanan/cek-progresif.php";
        $content = ob_get_clean();
        break;


    default:
        http_response_code(404);
        $title = "404 - Halaman Tidak Ditemukan";
        ob_start();
        echo "<div class='container mx-auto p-10 text-center'>
                <h1 class='text-3xl font-bold text-red-600'>404</h1>
                <p>Halaman tidak ditemukan.</p>
              </div>";
        $content = ob_get_clean();
        break;
}

// render layout utama
include "layouts/main.php";
