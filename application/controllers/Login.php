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

class Login extends CI_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		if ($this->auth->hasLogin()) redirect('dashboard', 'refresh');
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		$this->vars['page_title'] = 'Login to Our Site';
		$this->vars['ip_banned'] = $this->auth->ip_banned(get_ip_address());
		$this->vars['login_info'] = $this->vars['ip_banned'] ? 'The login page has been blocked for 10 minutes' : 'Enter your username and password to log on';
		$this->load->view('users/login', $this->vars);
	}

	/**
	 * Verify Login
	 * @return Object
	 */
	public function verify() {
		if ($this->input->is_ajax_request()) {
			if ($this->validation()) {
				$user_name = $this->input->post('user_name', TRUE);
				$user_password = $this->input->post('user_password', TRUE);
				$ip_address = get_ip_address();
				$logged_in = $this->auth->logged_in($user_name, $user_password, $ip_address) ? 'success' : 'error';
				$this->vars['status'] = $logged_in;
				$this->vars['message'] = $logged_in == 'success' ? 'logged_in' : 'not_logged_in';
				$this->vars['ip_banned'] = $this->auth->ip_banned($ip_address);
			} else {
				$this->vars['status'] = 'error';
				$this->vars['message'] = validation_errors();
				$this->vars['ip_banned'] = FALSE;
			}
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($this->vars, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES))
				->_display();
			exit;
		}
	}

	/**
	 * Validation Form
	 * @return Boolean
	 */
	private function validation() {
		$this->load->library('form_validation');
		$val = $this->form_validation;
		$val->set_rules('user_name', 'Nama Akun', 'trim|required');
		$val->set_rules('user_password', 'Kata Sandi', 'trim|required');
		$val->set_error_delimiters('<div>&sdot; ', '</div>');
		return $val->run();
	}
}
