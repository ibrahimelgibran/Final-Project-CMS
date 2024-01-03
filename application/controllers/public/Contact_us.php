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

class Contact_us extends Public_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		$this->vars['recaptcha_site_key'] = __session('recaptcha_site_key');
		$this->vars['page_title'] = 'Hubungi Kami';
		$this->vars['content'] = 'themes/'.theme_folder().'/contact-us';
		$this->load->view('themes/'.theme_folder().'/index', $this->vars);
	}

	/**
	 * save
	 * @access public
	 */
	public function save() {
		if ($this->input->is_ajax_request()) {
			if (__captchaActivated()) {
				$score = get_recapture_score($this->input->post('g-recaptcha-response'));
				if ($score < 0.9) {
					$this->vars['status'] = 'recaptcha_error';
	    			$this->vars['message'] = 'Recaptcha Error!';
					$this->output
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode($this->vars, self::REQUIRED_FLAGS))
						->_display();
					exit;
				}
			}
			if ($this->validation()) {
				$this->load->library('user_agent');
				$this->db->set('comment_author', strip_tags($this->input->post('comment_author', true)));
				$this->db->set('comment_email', strip_tags($this->input->post('comment_email', true)));
				$this->db->set('comment_url', prep_url(strip_tags($this->input->post('comment_url', true))));
				$this->db->set('comment_content', strip_tags($this->input->post('comment_content', true)));
				$this->db->set('comment_type', 'message');
				$this->db->set('comment_ip_address', $_SERVER['REMOTE_ADDR']);
				$this->db->set('comment_agent', $this->agent->agent_string());
				$this->db->set('created_at', date('Y-m-d H:i:s'));
				$this->db->set('created_by', __session('user_id'));
				$query = $this->db->insert('comments');
				$this->vars['status'] = $query ? 'success' : 'error';
				$this->vars['message'] = $query ? 'Pesan anda sudah tersimpan.' : 'Pesan anda tidak tersimpan.';
			} else {
				$this->vars['status'] = 'validation_errors';
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
		$val->set_rules('comment_author', 'Nama Lengkap', 'trim|required|alpha_numeric_spaces');
		$val->set_rules('comment_email', 'Email', 'trim|required|valid_email');
		$val->set_rules('comment_url', 'URL', 'trim|valid_url');
		$val->set_rules('comment_content', 'Komentar', 'trim|required|alpha_numeric_spaces');
		$val->set_message('required', '{field} harus diisi');
		$val->set_message('valid_email', '{field} harus diisi dengan format email yang benar');
		$val->set_error_delimiters('<div>&sdot; ', '</div>');
		return $val->run();
	}
}
