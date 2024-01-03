<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CMS Sekolahku | CMS (Content Management System) dan PPDB/PMB Online GRATIS
 * untuk sekolah SD/Sederajat, SMP/Sederajat, SMA/Sederajat, dan Perguruan Tinggi
 * @version    2.4.13
 * @author     Anton Sofyan | https://facebook.com/antonsofyan | 4ntonsofyan@gmail.com | 0857 5988 8922
 * @copyright  (c) 2014-2023
 * @link       https://sekolahku.web.id
 *
 * PERINGATAN :
 * 1. TIDAK DIPERKENANKAN MENGGUNAKAN CMS INI TANPA SEIZIN DARI PIHAK PENGEMBANG APLIKASI.
 * 2. TIDAK DIPERKENANKAN MEMPERJUALBELIKAN APLIKASI INI TANPA SEIZIN DARI PIHAK PENGEMBANG APLIKASI.
 * 3. TIDAK DIPERKENANKAN MENGHAPUS KODE SUMBER APLIKASI.
 */

class Auth {

	/**
	 * Reference to CodeIgniter instance
	 *
	 * @var object
	 */
	protected $CI;

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		$this->CI = &get_instance();
		$this->CI->load->model([
			'm_users',
			'm_user_privileges'
		]);
	}

	/**
	 * Logged In()
	 * @param String $user_name
	 * @param String $user_password
	 * @param String $ip_address
	 * @return Boolean
	 */
	public function logged_in($user_name, $user_password, $ip_address) {
		$ip_banned = $this->ip_banned($ip_address);
		if ( ! $ip_banned ) {
			$query = $this->CI->m_users->logged_in($user_name);
			if ($query->num_rows() === 1) {
				$data = $query->row();
				if (password_verify($user_password, $data->user_password)) {
					$session_data = [
						'user_id' => $data->id,
						'user_name' => $data->user_name,
						'user_email' => $data->user_email,
						'user_type' => $data->user_type,
						'user_profile_id' => $data->user_profile_id,
						'has_login' => TRUE,
						'user_privileges' => $this->CI->m_user_privileges->get_user_privileges($data->user_group_id, $data->user_type)
					];

					// If Student
					if ($data->user_type == 'student') {
						$student = $this->CI->model->RowObject('id', $data->user_profile_id, 'students');
						$session_data['is_student'] = filter_var((string) $student->is_student, FILTER_VALIDATE_BOOLEAN);
						$session_data['is_prospective_student'] = filter_var((string) $student->is_prospective_student, FILTER_VALIDATE_BOOLEAN);
						$session_data['is_alumni'] = filter_var((string) $student->is_alumni, FILTER_VALIDATE_BOOLEAN);
					}
					$this->CI->session->set_userdata($session_data);
					$this->last_logged_in($data->id);
					$this->reset_attempts($ip_address);
					return TRUE;
				}
				return FALSE;
			}
			$this->increase_login_attempts($ip_address);
			return FALSE;
		}
		return FALSE;
	}

	/**
	 * Get User ID
	 * @return Integer | NULL
	 **/
	public function get_user_id() {
		$id = (int) __session('user_id');
		return !empty($id) ? $id : NULL;
	}

	/**
	 * Last Logged In
	 * Fungsi untuk mengupdate data login terakhir
	 * @param Integer $id
	 * @return Void
	 */
	private function last_logged_in($id) {
		$this->CI->m_users->last_logged_in($id);
	}

	/**
	 * Has Login
	 * Fungsi untuk mengecek apakah data session user id kosong / tidak
	 * @return Boolean
	 */
	public function hasLogin() {
		return (bool) __session('has_login');
	}

	/**
	 * Restrict
	 * Fungsi untuk validasi status login
	 * @return Boolean
	 */
	public function restrict() {
		if (!$this->hasLogin()) {
			redirect('login', 'refresh');
		}
	}

	/**
	 * check if user has ban by ip address
	 * @param String $ip_address
	 * @return Boolean
	 */
	public function ip_banned($ip_address) {
		$max_attempts = 10;
		$banned_time = 600; // 600 || Banned at 10 minutes
		$query = $this->CI->m_users->get_attempts($ip_address);
		if (is_object($query) && $query->counter >= $max_attempts) {
			$datetime = strtotime($query->updated_at);
			$time_diff = time() - $datetime;
			if ($time_diff >= $banned_time) {
				$this->reset_attempts($ip_address);
				return FALSE;
			}
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Increase Login Attempts
	 * @param String $ip_address
	 * @return Void
	 */
	private function increase_login_attempts($ip_address) {
		$this->CI->m_users->increase_login_attempts($ip_address);
	}

	/**
	 * Reset Login Attempts
	 * Fungsi untuk menghapus data login attempts
	 * @param String $ip_address
	 * @return Void
	 */
	private function reset_attempts($ip_address) {
		$this->CI->m_users->reset_attempts($ip_address);
	}
}
