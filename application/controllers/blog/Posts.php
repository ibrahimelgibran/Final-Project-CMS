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
		$this->vars['blog'] = $this->vars['posts'] = $this->vars['all_posts'] = TRUE;
		$this->vars['content'] = 'blog/posts_read';
		$this->load->view('backend/index', $this->vars);
	}

	/**
	 * Add new
	 * @return Void
	 */
	public function create() {
		$this->load->helper('form');
		$this->vars['query'] = FALSE;
		$id = _toInteger($this->uri->segment(4));
		if (_isNaturalNumber( $id )) {
			$this->vars['query'] = $this->model->RowObject($this->pk, $id, $this->table);
			$post_image = 'media_library/posts/medium/'.$this->vars['query']->post_image;
			$this->vars['post_image'] = file_exists(FCPATH . $post_image) ? base_url($post_image) : '';
		}
		$this->vars['option_categories'] = $this->m_categories->get_categories('post');
		$this->vars['title'] = _isNaturalNumber( $id ) ? 'Edit Tulisan' : 'Tambah Tulisan';
		$this->vars['blog'] = $this->vars['posts'] = $this->vars['post_create'] = TRUE;
		$this->vars['action'] = site_url('blog/posts/save/'.$id);
		$this->vars['content'] = 'blog/posts_create';
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
	 * Save | Update
	 * @return Object
	 */
	public function save() {
		if ($this->input->is_ajax_request()) {
			$id = _toInteger($this->uri->segment(4));
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
					// Create tags from posts
					if ( ! empty( $dataset['post_tags'] )) {
						$this->load->model('m_tags');
						$this->m_tags->insert($dataset['post_tags']);
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
			'post_categories' => $this->input->post('post_categories', true),
			'post_type' => 'post',
			'post_status' => $this->input->post('post_status', true),
			'post_visibility' => $this->input->post('post_visibility', true),
			'post_comment_status' => $this->input->post('post_comment_status', true),
			'post_slug' => slugify($this->input->post('post_title', true)),
			'post_tags' => trim($this->input->post('post_tags', true))
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
		$val->set_rules('post_status', 'Status', 'trim|required|in_list[publish,draft]');
		$val->set_rules('post_visibility', 'Visibilitas', 'trim|required|in_list[public,private]');
		$val->set_rules('post_comment_status', 'Komentar', 'trim|required|in_list[open,close]');
		$val->set_error_delimiters('<div>&sdot; ', '</div>');
		return $val->run();
	}

	/**
	 * Post Image Upload Handler
	 * @param Integer $id
	 * @return 	Array
	 */
	private function upload_image($id) {
		$config['upload_path'] = './media_library/images/';
		$config['allowed_types'] = 'jpg|png|jpeg|gif';
		$config['max_size'] = 0;
		$config['encrypt_name'] = TRUE;
		$this->load->library('upload', $config);
		if (!$this->upload->do_upload('post_image')) {
			$this->vars['status'] = 'error';
			$this->vars['message'] = $this->upload->display_errors();
		} else {
			$file = $this->upload->data();
			// chmood new file
			@chmod(FCPATH.'media_library/images/'.$file['file_name'], 0777);
			// resize new image
			$this->image_resize(FCPATH.'media_library/images', $file['file_name']);
			$this->vars['status'] = 'success';
			$this->vars['file_name'] = $file['file_name'];
			if ( _isNaturalNumber($id) ) {
				$query = $this->model->RowObject($this->pk, $id, $this->table);
				// chmood old file
				@chmod(FCPATH.'media_library/posts/thumbnail/'.$query->post_image, 0777);
				@chmod(FCPATH.'media_library/posts/medium/'.$query->post_image, 0777);
				@chmod(FCPATH.'media_library/posts/large/'.$query->post_image, 0777);
				// unlink old file
				@unlink(FCPATH.'media_library/posts/thumbnail/'.$query->post_image);
				@unlink(FCPATH.'media_library/posts/medium/'.$query->post_image);
				@unlink(FCPATH.'media_library/posts/large/'.$query->post_image);
			}
		}
		return $this->vars;
	}

	/**
	 * Image Resize
	 * @param String $path
	 * @param String $file_name
	 * @return Void
	 */
	private function image_resize($path, $file_name) {
		$this->load->library('image_lib');
		// Thumbnail Image
		$thumb['image_library'] = 'gd2';
		$thumb['source_image'] = $path .'/'. $file_name;
		$thumb['new_image'] = './media_library/posts/thumbnail/'. $file_name;
		$thumb['maintain_ratio'] = false;
		$thumb['width'] = (int) __session('post_image_thumbnail_width');
		$thumb['height'] = (int) __session('post_image_thumbnail_height');
		$this->image_lib->initialize($thumb);
		$this->image_lib->resize();
		$this->image_lib->clear();
		// Medium Image
		$medium['image_library'] = 'gd2';
		$medium['source_image'] = $path .'/'. $file_name;
		$medium['new_image'] = './media_library/posts/medium/'. $file_name;
		$medium['maintain_ratio'] = false;
		$medium['width'] = (int) __session('post_image_medium_width');
		$medium['height'] = (int) __session('post_image_medium_height');
		$this->image_lib->initialize($medium);
		$this->image_lib->resize();
		$this->image_lib->clear();
		// Large Image
		$large['image_library'] = 'gd2';
		$large['source_image'] = $path .'/'. $file_name;
		$large['new_image'] = './media_library/posts/large/'. $file_name;
		$large['maintain_ratio'] = false;
		$large['width'] = (int) __session('post_image_large_width');
		$large['height'] = (int) __session('post_image_large_height');
		$this->image_lib->initialize($large);
		$this->image_lib->resize();
		$this->image_lib->clear();
		// Remove Original File
		@unlink($path .'/'. $file_name);
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
