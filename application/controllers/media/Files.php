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

class Files extends Admin_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model([
			'm_files',
			'm_file_categories'
		]);
		$this->pk = M_files::$pk;
		$this->table = M_files::$table;
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		$this->vars['title'] = 'File';
		$this->vars['media'] = $this->vars['files'] = TRUE;
		$this->vars['file_category_dropdown'] = json_encode($this->m_file_categories->dropdown(), JSON_HEX_APOS | JSON_HEX_QUOT);
		$this->vars['content'] = 'media/files';
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
			$query = $this->m_files->get_where($keyword, 'rows', $limit, $offset);
			$total_rows = $this->m_files->get_where($keyword);
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

	/**
	 * Save | Update
	 * @return Object
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
			'file_title' => $this->input->post('file_title', true),
			'file_category_id' => _toInteger($this->input->post('file_category_id', true)),
			'file_visibility' => $this->input->post('file_visibility', true)
		];
	}

	/**
	 * Validation Form
	 * @return Boolean
	 */
	private function validation() {
		$this->load->library('form_validation');
		$val = $this->form_validation;
		$val->set_rules('file_title', 'URL', 'trim|required');
		$val->set_rules('file_category_id', 'Keterangan', 'trim|required|is_natural_no_zero');
		$val->set_rules('file_visibility', 'Target', 'trim|required');
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
	    		$file_name = $query->file_name;
				$mimes = [];
				foreach(get_mimes() as $key => $value) {
					array_push($mimes, $key);
				}
				$file_allowed_types = explode(',', __session('file_allowed_types'));
				$allowed_types = [];
				foreach($file_allowed_types as $mime) {
					if(in_array(trim(strtolower($mime)), $mimes)) {
						array_push($allowed_types, trim(strtolower($mime)));
					}
				}
				$config['upload_path'] = './media_library/files/';
				$config['allowed_types'] = count($allowed_types) > 0 ? implode('|', $allowed_types) : '*';
				$config['max_size'] = (int) __session('upload_max_filesize');
				$config['encrypt_name'] = TRUE;
				$this->load->library('upload', $config);
				if ( ! $this->upload->do_upload('file')) {
					$this->vars['status'] = 'error';
					$this->vars['message'] = $this->upload->display_errors();
				} else {
					$file = $this->upload->data();
					$dataset = [
						'file_name' => $file['file_name'],
						'file_type' => $file['file_type'],
						'file_path' => $file['file_path'],
						'file_ext' => $file['file_ext'],
						'file_size' => $file['file_size']
					];
					$query = $this->model->update($id, $this->table, $dataset);
					if ( $query ) {
						@chmod(FCPATH.'media_library/files/'.$file_name, 0775);
						@unlink(FCPATH.'media_library/files/'.$file_name);
					}
					$this->vars['status'] = 'success';
					$this->vars['message'] = 'uploaded';
					$this->vars['full_path'] = $file['full_path'];
				}
			}
	    	$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($this->vars, self::REQUIRED_FLAGS))
				->_display();
			exit;
    	}
	}
}
