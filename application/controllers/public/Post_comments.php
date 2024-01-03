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

class Post_comments extends Public_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Save
	 * @return Object
	 */
	public function index() {
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
				$this->db->set('comment_url', strip_tags($this->input->post('comment_url', true)));
				$this->db->set('comment_content', strip_tags($this->input->post('comment_content', true)));
				$this->db->set('comment_type', 'post');
				$this->db->set('comment_post_id', _toInteger($this->input->post('comment_post_id', true)));
				$this->db->set('comment_status', filter_var((string) __session('comment_moderation'), FILTER_VALIDATE_BOOLEAN) ? 'unapproved' : 'approved');
				$this->db->set('comment_ip_address', get_ip_address());
				$this->db->set('comment_agent', $this->agent->agent_string());
				$this->db->set('created_at', date('Y-m-d H:i:s'));
				$this->db->set('created_by', __session('user_id'));
				$query = $this->db->insert('comments');
				$this->vars['status'] = $query ? 'success' : 'error';
				$this->vars['message'] = $query ? 'Komentar anda sudah tersimpan.' : 'Komentar anda tidak tersimpan.';
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

	/**
	 * Get Post Comments
	 * @return Object
	 */
	public function get_post_comments() {
		if ($this->input->is_ajax_request()) {
			$post_id = _toInteger($this->input->post('comment_post_id', true));
			$page_number = _toInteger($this->input->post('page_number', true));
			$offset = ($page_number - 1) * (int) __session('comment_per_page');
			$this->vars['comments'] = [];
			if ($post_id > 0) {
				$this->load->model('m_post_comments');
				$query = $this->m_post_comments->get_post_comments($post_id, (int) __session('comment_per_page'), $offset);
				$this->vars['comments'] = $query->result();
			}
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($this->vars, self::REQUIRED_FLAGS))
				->_display();
			exit;
		}
	}
}
