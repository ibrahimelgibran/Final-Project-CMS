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

class Lost_password extends CI_Controller {

	/**
	 * Flags that should be used when encoding to JSON.
	 *
	 * @var int
	 */
	public const REQUIRED_FLAGS = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES;
	
	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$timezone = NULL !== __session('timezone') ? __session('timezone') : 'Asia/Jakarta';
		date_default_timezone_set($timezone);
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		$this->load->view('users/lost-password');
	}

	/**
	 * Lost Password Process
	 * @return Object
	 */
	public function process() {
		if ($this->input->is_ajax_request()) {
			if ($this->validation()) {
				$email = $this->input->post('email', TRUE);
				$this->load->model('m_users');
				$query = $this->m_users->get_user_by_email($email);
				if (NULL == $query) {
					$this->vars['status'] = 'warning';
					$this->vars['message'] = 'Email anda tidak terdaftar pada database kami';
				} else {
					$forgot_password_key = sha1($email . uniqid(mt_rand(), true));
					$message = "Dear " . $query['user_full_name'];
					$message .= "<br><br>";
					$message .= "Silahkan klik tautan berikut untuk melakukan perubahan kata sandi Anda.";
					$message .= "<br>";
					$message .= "<a href=".base_url() . 'reset-password/' . $forgot_password_key.">".base_url() . 'reset-password/' . $forgot_password_key."</a>";
					$message .= "<br><br>";
					$message .= "Abaikan email ini jika Anda tidak mengajukan perubahan kata sandi ini.";
					$message .= "<br><br>";
					$message .= "Terima Kasih.";
					$message .= "<br><br>";
					$message .= "Admin";
					$message .= "<br>";
					$message .= __session('school_name');
					$config = array(
						'protocol' => 'smtp',
						'smtp_host' => __session('smtp_host'), 
						'smtp_port' => __session('smtp_port'),
						'smtp_user' => __session('smtp_user'),
						'smtp_pass' => __session('smtp_pass'),
						'smtp_crypto' => 'ssl', 
						'mailtype' => 'html',
						'smtp_timeout' => '4',
						'charset' => 'iso-8859-1',
						'wordwrap' => TRUE,
						"crlf" => "\r\n"
					);
					$this->load->library('email');
					$this->email->initialize($config);
					$this->email->set_newline("\r\n");
					$this->email->from(__session('email'), __session('school_name'));
					$this->email->to($query['user_email']);
					$this->email->subject('Lupa Kata Sandi');
					$this->email->message($message);
					if ($this->email->send()) {
						$update = $this->m_users->set_forgot_password_key($query['user_email'], $forgot_password_key);
						if ($update) {
							$this->vars['status'] = 'success';
							$this->vars['message'] = 'Tautan untuk mengubah kata sandi sudah kami kirimkan melalui email. Jika email tidak ditemukan, silahkan periksa pada folder spam.';
						} else {
							$this->vars['status'] = 'warning';
							$this->vars['message'] = 'Terjadi kesalahan dalam proses ubah kata sandi. Silahkan hubungi operator website untuk konfirmasi.';
						}
					} else {
						$this->vars['status'] = 'warning';
						$this->vars['message'] = 'Tautan untuk mengubah kata sandi tidak terkirim. Silahkan kirim email ke ' . __session('email');
					}
				}
			} else {
				$this->vars['status'] = 'error';
				$this->vars['message'] = validation_errors();
			}
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($this->vars, self::REQUIRED_FLAGS))
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
		$val->set_rules('email', 'Email', 'trim|required|valid_email');
		$val->set_error_delimiters('<div>&sdot; ', '</div>');
		return $val->run();
	}
}
