<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CMS Sekolahku | CMS (Content Management System) dan PPDB/PMB Online GRATIS
 * untuk sekolah SD/Sederajat, SMP/Sederajat, SMA/Sederajat, dan Perguruan Tinggi
 * @version    2.4.13
 * @author     Anton Sofyan | https://facebook.com/antonsofyan | 4ntonsofyan@gmail.com | 0857 5988 8922
 * @copyright  (c) 2014-2021
 * @link       https://sekolahku.web.id
 *
 * PERINGATAN :
 * 1. TIDAK DIPERKENANKAN MENGGUNAKAN CMS INI TANPA SEIZIN DARI PIHAK PENGEMBANG APLIKASI.
 * 2. TIDAK DIPERKENANKAN MEMPERJUALBELIKAN APLIKASI INI TANPA SEIZIN DARI PIHAK PENGEMBANG APLIKASI.
 * 3. TIDAK DIPERKENANKAN MENGHAPUS KODE SUMBER APLIKASI.
 */

class M_users extends CI_Model {

	/**
	 * Primary key
	 * @var String
	 */
	public static $pk = 'id';

	/**
	 * Table
	 * @var String
	 */
	public static $table = 'users';

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
     * logged_in()
     * @param String $user_name
     * @return Boolean
     */
	public function logged_in($user_name) {
		return $this->db
			->select('id
				, user_name
				, user_email
				, user_password
				, user_type
				, user_group_id
				, user_profile_id
				, has_login
			')
         ->where('user_name', $user_name)
         ->where('is_deleted', 'false')
         ->limit(1)
         ->get(self::$table);
	}

	/**
     * last_logged_in()
     * @param Integer $id
     * @return void
     */
	public function last_logged_in($id) {
		$fields = [
			'last_logged_in' => date('Y-m-d H:i:s'),
			'ip_address' => get_ip_address(),
			'has_login' => 'true'
		];
		$this->db
			->where(self::$pk, $id)
			->update(self::$table, $fields);
	}

	/**
     * Reset Logged In
     * set "has_login" to false
     * @param Integer $id
     * @return Void
     */
	public function reset_logged_in($id) {
		$this->db
			->where(self::$pk, $id)
			->update(self::$table, ['has_login' => 'false']);
	}

	/**
     * get_attempts
     * @param String $ip_address
     * @return Object
     */
	public function get_attempts($ip_address) {
		$query = $this->db
			->where('ip_address', $ip_address)
			->get('login_attempts');
		if ($query->num_rows() === 1) {
			return $query->row();
		}
		return NULL;
	}

	/**
     * increase_login_attempts
     * @param String $ip_address
     * @return Void
     */
	public function increase_login_attempts($ip_address) {
		$query = $this->db
			->where('ip_address', $ip_address)
			->get('login_attempts');
		if ($query->num_rows() === 1) {
			$result = $query->row();
			$this->db
				->where('ip_address', $ip_address)
				->update('login_attempts', ['counter' => ($result->counter + 1)]);
		} else {
			$this->db
				->insert('login_attempts', [
					'created_at' => date('Y-m-d H:i:s'),
					'ip_address' => $ip_address,
					'counter' => 1
				]);
		}
	}

	/**
     * Reset Attempts
     * @param String $ip_address
     * @return Void
     */
	public function reset_attempts($ip_address) {
		$this->db
			->where('ip_address', $ip_address)
			->delete('login_attempts');
	}

	/**
     * get last logged in
     * @return Resource
     */
	public function get_last_login() {
		return $this->db
			->select("
				CASE WHEN x1.user_type = 'administrator' THEN x1.user_full_name
    			WHEN x1.user_type = 'student' THEN x2.full_name
    			WHEN x1.user_type = 'employee' THEN x3.full_name
      			END AS full_name
  				, x1.last_logged_in
			")
			->join('students x2', 'x1.user_profile_id = x2.id', 'LEFT')
			->join('employees x3', 'x1.user_profile_id = x3.id', 'LEFT')
			->where('x1.user_type !=', 'super_user')
			->where('x1.last_logged_in IS NOT NULL')
			->order_by('x1.last_logged_in', 'DESC')
			->limit(10)
			->get(self::$table.' x1');
	}

	/**
     * Reset User Name
     * @param String $user_name
     * @return Boolean
     */
	public function reset_user_name($user_name) {
		$user_id = __session('user_id');
		$count = $this->db
			->where('user_name', $user_name)
			->where('id <> ', $user_id)
			->count_all_results(self::$table);
		if ( $count == 0 ) {
			return $this->db
				->where('id', $user_id)
				->update(self::$table, ['user_name' => $user_name]);
		}
		return false;
	}

	/**
     * Reset User Email
     * @param String $user_email
     * @return Boolean
     */
	public function reset_user_email($user_email) {
		$user_id = __session('user_id');
		$count = $this->db
			->where('user_email', $user_email)
			->where('id <> ', $user_id)
			->count_all_results(self::$table);
		if ( $count == 0 ) {
			return $this->db
				->where('id', $user_id)
				->update(self::$table, ['user_email' => $user_email]);
		}
		return false;
	}

	/**
     * set_forgot_password_key
     * @param String $user_email
     * @param String $user_forgot_password_key
     * @return Boolean
     */
	public function set_forgot_password_key($user_email, $user_forgot_password_key) {
		$dataset = [
			'user_forgot_password_key' => $user_forgot_password_key,
			'user_forgot_password_request_date' => date('Y-m-d H:i:s')
		];
		return $this->db
			->where('user_email', $user_email)
			->update(self::$table, $dataset);
	}

	/**
     * Remove Forgot Password Key
     * @param Integer $id
     * @return Boolean
     */
	public function remove_forgot_password_key($id) {
		return $this->db
			->where(self::$pk, $id)
			->update(self::$table, [
				'user_forgot_password_key' => NULL,
				'user_forgot_password_request_date' => NULL
			]);
	}

	/**
     * Reset Password
     * @param String $id
     * @return Boolean
     */
	public function reset_password( $id ) {
		return $this->db
			->where(self::$pk, $id)
			->update(self::$table, [
				'user_forgot_password_key' => NULL,
				'user_forgot_password_request_date' => NULL,
				'user_password' => password_hash($this->input->post('password', true), PASSWORD_BCRYPT)
			]);
	}

	/**
     * Get user by email
     * @param String $user_email
     * @return Any
     */
	public function get_user_by_email($user_email) {
		$query = $this->db
			->where('user_email', $user_email)
			->get(self::$table);
		if ($query->num_rows() === 1) {
			$result = $query->row();
			return [
				'user_email' => $result->user_email,
				'user_full_name' => $result->user_full_name
			];
		}
		return NULL;
	}

	/**
	 * Get User ID
	 * @param String $user_type
	 * @param Integer $user_profile_id
	 * @return Integer
	 */
	public function get_user_id( $user_type = 'student', $user_profile_id = 0 ) {
		$this->db->select('id');
		$this->db->where('user_profile_id', $user_profile_id);
		$this->db->where('user_type', $user_type);
		$query = $this->db->get('users');
		if ($query->num_rows() == 1) {
			$res = $query->row();
			return $res->id;
		}
		return 0;
	}

	/**
	 * Check if email exists
	 * @param String $user_email
	 * @param Integer $id
	 * @return Boolean
	 */
	public function email_exists( $user_email, $id = 0 ) {
		$this->db->where('user_email', $user_email);
		if ( _isNaturalNumber($id) ) $this->db->where('id <>', _toInteger($id));
		$this->db->where('is_deleted', 'false');
		$count = $this->db->count_all_results(self::$table);
		return $count > 0;
	}
}
