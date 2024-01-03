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

class Posts extends Admin_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model([
			'm_posts',
			'm_categories'
		]);
		$this->pk = M_posts::$pk;
		$this->table = M_posts::$table;
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		$this->vars['title'] = 'Tulisan';
		$this->vars['posts'] = TRUE;
		$this->vars['content'] = 'posts_read';
		$this->load->view('backend/index', $this->vars);
	}

	/**
	 * Add new
	 * @return Void
	 */
	public function create() {
		$this->load->helper('form');
		$id = _toInteger($this->uri->segment(3));
		if (_isNaturalNumber( $id )) {
			$this->vars['query'] = $this->model->RowObject($this->pk, $id, $this->table);
			$post_image = 'media_library/posts/medium/'.$this->vars['query']->post_image;
			$this->vars['post_image'] = file_exists(FCPATH . $post_image) ? base_url($post_image) : '';
			// Check Authorization
			if (_toInteger($this->vars['query']->post_author) !== _toInteger(__session('user_id'))) {
				redirect('dashboard', 'refresh');
			}
			// If has published, forbidden to edit
			if ($this->vars['query']->post_status === 'publish') {
				redirect('dashboard', 'refresh');
			}
		} else {
			$this->vars['query'] = FALSE;
		}
		$this->vars['option_categories'] = $this->m_categories->get_categories('post');
		$this->vars['title'] = _isNaturalNumber($id) ? 'Edit Tulisan' : 'Tambah Tulisan';
		$this->vars['posts'] = TRUE;
		$this->vars['action'] = site_url('posts/save/'.$id);
		$this->vars['content'] = 'posts_create';
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
			$query = $this->m_posts->get_where($keyword, 'rows', $limit, $offset);
			$total_rows = $this->m_posts->get_where($keyword);
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
	 * Find by ID
	 * @return Void
	 */
	public function find_id() {
		if ($this->input->is_ajax_request()) {
			$id = _toInteger($this->input->post('id', true));
			$query = _isNaturalNumber( $id ) ? $this->model->RowObject($this->pk, $id, $this->table) : [];
			$this->output
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($query, self::REQUIRED_FLAGS))
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
			$id = _toInteger($this->uri->segment(3));
			if ($this->validation()) {
				$dataset = $this->dataset();
				$error_message = '';
				if ( ! empty($_FILES['post_image']) ) {
					$upload = $this->upload_image($id);
					if ($upload['status'] == 'success') {
						$dataset['post_image'] = $upload['file_name'];
					} else {
						$error_message = $upload['message'];
					}
				}
				if ( ! empty( $error_message ) ) {
					$this->vars['status'] = 'error';
					$this->vars['message'] = $error_message;
				} else {
					$dataset[(_isNaturalNumber( $id ) ? 'updated_by' : 'created_by')] = __session('user_id');
					if (!_isNaturalNumber( $id )) $dataset['created_at'] = date('Y-m-d H:i:s');
					if (_isNaturalNumber( $id )) unset($dataset['post_author']);
					$query = $this->model->upsert($id, $this->table, $dataset);
					$this->vars['action'] = _isNaturalNumber( $id ) ? 'update' : 'insert';
					$this->vars['status'] = $query ? 'success' : 'error';
					$this->vars['message'] = $query ? 'Data Anda berhasil disimpan.' : 'Terjadi kesalahan dalam menyimpan data';
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
			'post_title' => $this->input->post('post_title', true),
			'post_content' => $this->input->post('post_content'),
			'post_author' => __session('user_id'),
			'post_categories' => $this->input->post('post_categories', true),
			'post_type' => 'post',
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
		$val->set_rules('post_title', 'Title', 'trim|required');
		$val->set_rules('post_content', 'Content', 'trim|required');
		$val->set_error_delimiters('<div>&sdot; ', '</div>');
		return $val->run();
	}
}
