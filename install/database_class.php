<?php

class Database {

	/**
	 * Create Database
	 * @param Array
	 * @return Boolean
	 */
	public function check_database($params) {
		$db = new mysqli($params['database_hostname'],$params['database_username'],$params['database_password'],$params['database_name']);
		if ($db->connect_errno) {
			return false;
		}
		return true;
	}

	/**
	 * Create Account
	 * @param Array
	 * @return Boolean
	 */
	public function create_tables($params) {
		$db = mysqli_connect($params['database_hostname'],$params['database_username'],$params['database_password'],$params['database_name']);
		if (mysqli_connect_errno()) {
			return false;
		}
		$user_name = $params['user_name'];
		$user_password = password_hash($params['user_password'], PASSWORD_BCRYPT);
		$user_full_name = $params['user_full_name'];
		$user_email = $params['user_email'];
		$school_name = $params['school_name'];
		$street_address = $params['street_address'];
		$school_level = $params['school_level'];
		$tagline = $params['tagline'];
		$handle = fopen('db_cms_sekolahku.sql', "r+");
		$contents = fread($handle, filesize('db_cms_sekolahku.sql'));
		$sql = explode(";",$contents);
		array_push($sql, "TRUNCATE TABLE users");
		array_push($sql, "INSERT INTO users (user_name, user_password, user_full_name, user_email, user_type) VALUES ('{$user_name}', '{$user_password}', '{$user_full_name}', '{$user_email}', 'super_user')");
		array_push($sql, "UPDATE settings SET value='{$school_name}' WHERE variable='school_name'");
		array_push($sql, "UPDATE settings SET value='{$street_address}' WHERE variable='street_address'");
		array_push($sql, "UPDATE settings SET value='{$school_level}' WHERE variable='school_level'");
		array_push($sql, "UPDATE settings SET value='{$tagline}' WHERE variable='tagline'");
		foreach ($sql as $query) {
			mysqli_query($db, $query);
		}
		fclose($handle);
		return true;
	}
}
