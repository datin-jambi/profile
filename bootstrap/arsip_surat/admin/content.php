<?php
	include"../lib/koneksi.php";
	include"../lib/paging.class.php";
	include"../lib/all_function.php";
	include"../lib/fungsi_transaction.php";


	if ($_GET['mod'] == 'utama') {
		include"utama.php";
	} elseif ($_GET['mod'] == 'setting') {
		include"setting.php";
	} elseif ($_GET['mod'] == 'utama_user') {
		include"utama_user.php";
	} elseif ($_GET['mod'] == 'setting_user') {
		include"setting_user.php";
	} elseif ($_GET['mod'] == 'info') {
		include"modul/info/info.php";
	} elseif ($_GET['mod'] == 'notif') {
		include"modul/notif/notif.php";
	} elseif ($_GET['mod'] == 'user') {	
		include"modul/user/user.php";
	} elseif ($_GET['mod'] == 'tambah_surat') {	
		include"modul/surat/tambah_surat.php";
	} elseif ($_GET['mod'] == 'suratt') {	
		include"modul/surat/suratt.php";
	} elseif ($_GET['mod'] == 'surattt') {	
		include"modul/surat/surattt.php";
	
	} elseif ($_GET['mod'] == 'admin') {	
		include"modul/admin/admin.php";
	} elseif ($_GET['mod'] == 'pesan') {
		include"modul/pesan/pesan.php";
	} elseif ($_GET['mod'] == 'data_pegawai') {
		include"modul/pegawai/data_pegawai.php";
	} elseif ($_GET['mod'] == 'surat') {
		include"modul/surat/surat.php";
	} elseif ($_GET['mod'] == 'arsip_surat_user') {
		include"modul/surat/arsip_surat_user.php";
	} elseif ($_GET['mod'] == 'arsip_surat_anggota') {
		include"modul/surat/arsip_surat_anggota.php";
		include"modul/obat_user/obat_user.php";
	} elseif ($_GET['mod'] == 'obat_masuk') {
		include"modul/obat_masuk/obat_masuk.php";
	} elseif ($_GET['mod'] == 'tambah_obat_msk') {
		include"modul/obat_masuk/tambah_obat_msk.php";
	} elseif ($_GET['mod'] == 'obat_keluar') {
		include"modul/obat_keluar/obat_keluar.php";
	} elseif ($_GET['mod'] == 'tambah_obat_keluar') {
		include"modul/obat_keluar/tambah_obat_keluar.php";
	} elseif ($_GET['mod'] == 'laporan') {
		include"modul/laporan/laporan.php";
	} elseif ($_GET['mod'] == 'manajemenadmin') {
		include"modul/admin/manajemenadmin.php";			
		
	}





?>