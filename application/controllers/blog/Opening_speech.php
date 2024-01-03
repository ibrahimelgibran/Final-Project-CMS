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

class Opening_speech extends Admin_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model('m_posts');
		$this->pk = M_posts::$pk;
		$this->table = M_posts::$table;
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		$this->vars['title'] = 'Sambutan ' . ucwords(strtolower(__session('_headmaster')));
		$this->vars['blog'] = $this->vars['opening_speech'] = TRUE;
		$this->vars['query'] = $this->m_posts->get_opening_speech();
		$this->vars['content'] = 'blog/opening_speech';
		$this->load->view('backend/index', $this->vars);
	}

	/**
	 * Save | Update
	 * @return Object
	 */
	public function save() {
		if ($this->input->is_ajax_request()) {
			if ($this->validation()) {
				$dataset = $this->dataset();
				$dataset['updated_by'] = __session('user_id');
				$this->vars['status'] = $this->m_posts->opening_speech_update($dataset) ? 'success' : 'error';
				$this->vars['message'] = $this->vars['status'] == 'success' ? 'updated' : 'not_updated';
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
	 * Dataset
	 * @return Array
	 */
	private function dataset() {
		return [
			'post_content' => $this->input->post('post_content'),
			'post_type' => 'opening_speech'
		];
	}

	/**
	 * Validation Form
	 * @return Boolean
	 */
	private function validation() {
		$this->load->library('form_validation');
		$val = $this->form_validation;
		$val->set_rules('post_content', 'Sambutan Kepala Sekolah', 'trim|required');
		$val->set_error_delimiters('<div>&sdot; ', '</div>');
		return $val->run();
	}

	/**
	 * Insert image in tinyMCE Editor
	 */
	public function images_upload_handler() {
		$config['upload_path'] = './media_library/posts/';
		$config['allowed_types'] = 'jpg|png|jpeg';
		$config['max_size'] = 0;
		$this->vars = [];
		$this->load->library('upload', $config);
		if ( ! $this->upload->do_upload('file')) {
			$this->vars['status'] = 'error';
			$this->vars['message'] = $this->upload->display_errors('', '');
		} else {
			$file = $this->upload->data();
			$this->vars['status'] = 'success';
			$this->vars['location'] = base_url('media_library/posts/'.$file['file_name']);
		}
		$this->output
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($this->vars, self::REQUIRED_FLAGS))
			->_display();
		exit;
	}
}
