<?php

function mySQLConnect($servername, $username, $password, $database) {
    mysql_connect($servername, $username, $password) or die("Error Connection!!!" . mysql_error());
    mysql_select_db($database) or die(mysql_error());
}

mySQLConnect("localhost", "root", "", "db_whistle_blower");

function antiinjection($data) {
    $filter_sql = mysql_real_escape_string(stripslashes(strip_tags(htmlspecialchars($data, ENT_QUOTES))));
    return $filter_sql;
}

function replace($str, $find, $repl) {
    return str_replace($find, $repl, $str);
}

function stripTags($str) {
    $tags = $str;
    $tags = replace($tags, "'", "`");
    $tags = replace($tags, "<", "&lt;");
    $tags = replace($tags, ">", "&gt;");
    return $tags;
}

function getPost($param) {
    $default = '';
    if (!isset($_POST[$param]) || empty($_POST[$param])) {
        return $default;
    }
    return $_POST[$param];
}

function getParam($param) {
    $default = '';
    if (!isset($_GET[$param]) || empty($_GET[$param])) {
        return stripTags(getPost($param, $default));
    }
    return stripTags($_GET[$param]);
}

function tgl_indo($tgl) {
    $tanggal = substr($tgl, 8, 2);
    $bulan = getBulan(substr($tgl, 5, 2));
    $tahun = substr($tgl, 0, 4);
    return $tanggal . ' ' . $bulan . ' ' . $tahun;
}

function getBulan($bln) {
    switch ($bln) {
        case 1:
            return "Januari";
            break;
        case 2:
            return "Februari";
            break;
        case 3:
            return "Maret";
            break;
        case 4:
            return "April";
            break;
        case 5:
            return "Mei";
            break;
        case 6:
            return "Juni";
            break;
        case 7:
            return "Juli";
            break;
        case 8:
            return "Agustus";
            break;
        case 9:
            return "September";
            break;
        case 10:
            return "Oktober";
            break;
        case 11:
            return "November";
            break;
        case 12:
            return "Desember";
            break;
    }
}

?>