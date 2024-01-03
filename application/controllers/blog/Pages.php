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

class Pages extends Admin_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model('m_pages');
		$this->pk = M_pages::$pk;
		$this->table = M_pages::$table;
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		$this->vars['title'] = 'Halaman';
		$this->vars['blog'] = $this->vars['pages'] = TRUE;
		$this->vars['content'] = 'blog/pages_read';
		$this->load->view('backend/index', $this->vars);
	}

	/**
	 * Add new
	 * @return Void
	 */
	public function create() {
		$this->load->helper('form');
		$id = _toInteger($this->uri->segment(4));
		$this->vars['query'] = _isNaturalNumber( $id ) ? $this->model->RowObject($this->pk, $id, $this->table) : FALSE;
		$this->vars['title'] = _isNaturalNumber( $id ) ? 'Edit Halaman' : 'Tambah Halaman';
		$this->vars['blog'] = $this->vars['pages'] = TRUE;
		$this->vars['action'] = site_url('blog/pages/save/'.$id);
		$this->vars['content'] = 'blog/pages_create';
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
			$query = $this->m_pages->get_where($keyword, 'rows', $limit, $offset);
			$total_rows = $this->m_pages->get_where($keyword);
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
			$id = _toInteger($this->uri->segment(4));
			if ($this->validation()) {
				$dataset = $this->dataset();
				$dataset[(_isNaturalNumber( $id ) ? 'updated_by' : 'created_by')] = __session('user_id');
				if (!_isNaturalNumber( $id )) $dataset['created_at'] = date('Y-m-d H:i:s');
				if (_isNaturalNumber( $id )) unset($dataset['post_author']);
				$query = $this->model->upsert($id, $this->table, $dataset);
				$this->vars['action'] = _isNaturalNumber( $id ) ? 'update' : 'insert';
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
	 * Set Created At
	 * @return Object
	 */
	public function set_created_at() {
		if ($this->input->is_ajax_request()) {
			$id = _toInteger($this->input->post('id', true));
			if (_isNaturalNumber( $id )) {
				$this->db->set('created_at', $this->input->post('created_at', true));
				$this->db->set('updated_by', __session('user_id'));
				$this->db->where($this->pk, $id);
				$query = $this->db->update($this->table);
				$this->vars['status'] = $query ? 'success' : 'error';
				$this->vars['message'] = $query ? 'updated' : 'not_updated';
				$this->output
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($this->vars, self::REQUIRED_FLAGS))
					->_display();
				exit;
			}
		}
	}

	/**
	 * Dataset
	 * @return Array
	 */
	private function dataset() {
		return [
			'post_title' => $this->input->post('post_title', true),
			'post_content' => $this->input->post('post_content'),
			'post_author' => __session('user_id'),
			'post_type' => 'page',
			'post_status' => $this->input->post('post_status', true),
			'post_visibility' => $this->input->post('post_visibility', true),
			'post_comment_status' => $this->input->post('post_comment_status', true),
			'post_slug' => slugify($this->input->post('post_title', true))
		];
	}

	/**
	 * Validation Form
	 * @return Boolean
	 */
	private function validation() {
		$this->load->library('form_validation');
		$val = $this->form_validation;
		$val->set_rules('post_title', 'Judul', 'trim|required');
		$val->set_rules('post_content', 'Konten', 'trim|required');
		$val->set_rules('post_status', 'Status', 'trim|required|in_list[publish,draft]');
		$val->set_rules('post_visibility', 'Visibilitas', 'trim|required|in_list[public,private]');
		$val->set_rules('post_comment_status', 'Komentar', 'trim|required|in_list[open,close]');
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
