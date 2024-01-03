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

class Themes extends Admin_Controller {

	/**
	 * Class Constructor
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model('m_themes');
		$this->pk = M_themes::$pk;
		$this->table = M_themes::$table;
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		$this->vars['title'] = 'Tema';
		$this->vars['appearance'] = $this->vars['themes'] = TRUE;
		$this->vars['content'] = 'appearance/themes';
		$this->load->view('backend/index', $this->vars);
	}

	/**
	 * Pagination
	 * @return Object
	 */
	public function pagination() {
		if ($this->input->is_ajax_request()) {
			$keyword = trim($this->input->post('keyword', true));
			$page_number = _toInteger($this->input->post('page_number', true));
			$limit = _toInteger($this->input->post('per_page', true));
			$offset = ($page_number * $limit);
			$query = $this->m_themes->get_where($keyword, 'rows', $limit, $offset);
			$total_rows = $this->m_themes->get_where($keyword);
			$total_page = $limit > 0 ? ceil(_toInteger($total_rows) / _toInteger($limit)) : 1;
			$this->vars['total_page'] = _toInteger($total_page);
			$this->vars['total_rows'] = _toInteger($total_rows);
			$this->vars['rows'] = $query->result();
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($this->vars, self::REQUIRED_FLAGS))
				->_display();
			exit;
		}
	}
//
	/**
	 * Save | Update
	 * @return 	Object
	 */
	public function save() {
		if ($this->input->is_ajax_request()) {
			$id = _toInteger($this->input->post('id', true));
			if ($this->validation()) {
				$dataset = $this->dataset();
				$dataset[(_isNaturalNumber( $id ) ? 'updated_by' : 'created_by')] = __session('user_id');
				if (!_isNaturalNumber( $id )) $dataset['created_at'] = date('Y-m-d H:i:s');
				$query = $this->model->upsert($id, $this->table, $dataset);
				$this->vars['status'] = $query ? 'success' : 'error';
				$this->vars['message'] = $query ? 'Data Anda berhasil disimpan.' : 'Terjadi kesalahan dalam menyimpan data';
				if ($this->vars['status'] == 'success' && $dataset['is_active'] == 'true') {
					$this->m_themes->deactivate_themes($id);
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
	 * Dataset
	 * @return Array
	 */
	private function dataset() {
		return [
			'theme_name' => $this->input->post('theme_name', true),
			'theme_folder' => trim(strtolower($this->input->post('theme_folder', true))),
			'theme_author' => $this->input->post('theme_author', true),
			'is_active' => $this->input->post('is_active', true)
		];
	}

	/**
	 * Validation Form
	 * @return Boolean
	 */
	private function validation() {
		$this->load->library('form_validation');
		$val = $this->form_validation;
		$val->set_rules('theme_name', 'Theme Name', 'trim|required');
		$val->set_rules('theme_folder', 'Theme Folder', 'trim|required');
		$val->set_rules('theme_author', 'Author', 'trim|required');
		$val->set_error_delimiters('<div>&sdot; ', '</div>');
		return $val->run();
	}

	/**
	 * Upload
	 * @return Void
	 */
    public function upload() {
    	if ($this->input->is_ajax_request()) {
			$id = _toInteger($this->input->post('id', true));
	    	if (_isNaturalNumber( $id )) {
	    		$query = $this->model->RowObject($this->pk, $id, $this->table);
				$config['upload_path'] = './views/themes/';
				$config['allowed_types'] = 'zip';
				$config['max_size'] = 0;
				$config['encrypt_name'] = false;
				$this->load->library('upload', $config);
				if ( ! $this->upload->do_upload('file')) {

					$this->vars['status'] = 'error';
					$this->vars['message'] = $this->upload->display_errors();
				} else {
					$file = $this->upload->data();
					if ($query->theme_folder == $file['raw_name']) {
						$zip = new ZipArchive;
						if ($zip->open(VIEWPATH . 'themes/' . $file['file_name'])) {
							$zip->extractTo(VIEWPATH . 'themes/');
							$zip->close();
							// chmod Theme Folder
							$this->chmod_themes('./views/themes/'.$file['raw_name']);

							$this->vars['status'] = 'success';
							$this->vars['message'] = 'extracted';
						} else {

							$this->vars['status'] = 'error';
							$this->vars['message'] = 'not_extracted';
						}
					} else {

						$this->vars['status'] = 'warning';
						$this->vars['message'] = 'Nama file yang diupload tidak sama dengan "'. $query->theme_folder.'"';
					}
					// Delete ZIP File
					@chmod('./views/themes/' . $file['file_name'], 0777);
					@unlink('./views/themes/' . $file['file_name']);
				}
	    	} else {
				$this->vars['status'] = 'error';
				$this->vars['message'] = 'ID bukan merupakan tipe angka yang valid !';
	    	}

	    	$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($this->vars, self::REQUIRED_FLAGS))
				->_display();
			exit;
		}
	}

	/**
	 * chmod Theme Folder
	 * @param String
	 * @return Void
	 */
	private function chmod_themes($path) {
		@chmod($path, 0777);
		$dir = new DirectoryIterator($path);
		foreach ($dir as $item) {
			@chmod($item->getPathname(), 0777);
			if ($item->isDir() && !$item->isDot()) {
				$this->chmod_themes($item->getPathname());
			}
		}
	}
}
