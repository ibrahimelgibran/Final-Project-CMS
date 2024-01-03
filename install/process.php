<?php
require_once('core_class.php');
require_once('database_class.php');
$core = new Core();
$database = new Database();
sleep(3);
$errors = [];
if ($core->validate_post($_POST) == true) {
	if ( ! $database->check_database($_POST)) {
		array_push($errors, 'Database tidak ditemukan. Silahkan buat database terlebih dahulu!');
	}
	if ( ! $database->create_tables($_POST)) {
		array_push($errors, 'Terjadi kesalahan dalam import file SQL. Silahkan periksa kembali konfigurasi database anda!');
	}
	if ( ! $core->write_config($_POST)) {
		array_push($errors, 'Terjadi kesalahan file permission. Silahkan ubah file permission application/config/database.php menjadi 0777');
	}
} else {
	array_push($errors, 'Semua form isian harus diisi!');
}
header("Content-Type: application/json;charset=utf-8");
echo json_encode($errors);
