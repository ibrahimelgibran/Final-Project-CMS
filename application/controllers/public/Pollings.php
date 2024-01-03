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

class Pollings extends Public_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model('m_pollings');
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		$this->vars['page_title'] = 'Hasil Jajak Pendapat';
		$query = get_active_question();
		$results = $this->m_pollings->get_pollings($query->id);
		$labels = [];
		$data = [];
		foreach($results->result() as $row) {
			array_push($labels, $row->labels);
			array_push($data, $row->data);
		}
		$this->vars['labels'] = json_encode($labels, JSON_HEX_APOS | JSON_HEX_QUOT);
		$this->vars['data'] = json_encode($data, JSON_HEX_APOS | JSON_HEX_QUOT);
		$this->vars['question'] = $query->question;
		$this->vars['content'] = 'themes/'.theme_folder().'/polling-results';
		$this->load->view('themes/'.theme_folder().'/index', $this->vars);
	}

	/**
	 * Save | Update
	 * @return Object
	 */
	public function save() {
		if ($this->input->is_ajax_request()) {
			if ($this->input->post('csrf_token') && $this->token->is_valid_token($this->input->post('csrf_token'))) {
				if ($this->validation()) {
					$answer_id = _toInteger($this->input->post('answer_id', true));
					$this->vars['status'] = $this->m_pollings->insert($answer_id) ? 'success' : 'info';
					$this->vars['message'] = $this->vars['status'] == 'success' ? 'Terima kasih sudah mengikuti polling kami.' : 'Anda sudah mengikuti polling kami hari ini.';
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
		$val->set_rules('answer_id', 'Jawaban', 'trim|required|is_natural_no_zero');
		$val->set_message('required', '{field} harus diisi');
		$val->set_error_delimiters('<div>&sdot; ', '</div>');
		return $val->run();
	}
}
