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

class Subscribe extends Public_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model('m_subscribers');
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		if ($this->input->is_ajax_request()) {
			if ($this->input->post('csrf_token') && $this->token->is_valid_token($this->input->post('csrf_token'))) {
				if ($this->validation()) {
					$this->db->set('email', $this->input->post('subscriber', true));
					$this->db->set('created_at', date('Y-m-d H:i:s'));
					$this->db->set('created_by', __session('user_id'));
					$query = $this->db->get_compiled_insert('subscribers');
					$query = str_replace('INSERT INTO', 'INSERT IGNORE INTO', $query);
	        		$this->db->query($query);
					$this->vars['status'] = 'success';
					$this->vars['message'] = 'Email anda sudah tersimpan';
				} else {
					$this->vars['status'] = 'error';
					$this->vars['message'] = validation_errors();
				}
				$this->vars['csrf_token'] = $this->token->get_token();
			} else {
				$this->vars['status'] = 'token_error';
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
		$val->set_rules('subscriber', 'Email', 'trim|required|valid_email');
		$val->set_message('valid_email', 'Masukan email dengan format yang benar');
		$val->set_error_delimiters('<div>&sdot; ', '</div>');
		return $val->run();
	}
}
