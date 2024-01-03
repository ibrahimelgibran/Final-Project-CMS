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

class Albums extends Admin_Controller {

	/**
	* Class Constructor
	*
	* @return Void
	*/
	public function __construct() {
		parent::__construct();
		$this->load->model('m_albums');
		$this->pk = M_albums::$pk;
		$this->table = M_albums::$table;
	}

	/**
	* Index
	* @return Void
	*/
	public function index() {
		$this->vars['title'] = 'Album Foto';
		$this->vars['media'] = $this->vars['albums'] = TRUE;
		$this->vars['content'] = 'media/albums';
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
			$query = $this->m_albums->get_where($keyword, 'rows', $limit, $offset);
			$total_rows = $this->m_albums->get_where($keyword);
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
	* Form Upload
	*
	* @return	void
	*/
	public function form_upload() {
		$id = _toInteger($this->uri->segment(4));
		if (_isNaturalNumber( $id )) {
			$query = $this->model->RowObject($this->pk, $id, $this->table);
			$this->vars['title'] = $query->album_title;
			$this->vars['action'] = site_url('media/albums/images_upload/').$id;
			$this->vars['media'] = $this->vars['albums'] = TRUE;
			$this->vars['content'] = 'media/upload_photos';
			$this->load->view('backend/index', $this->vars);
		} else {
			show_404();
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
			'album_title' => $this->input->post('album_title', true),
			'album_description' => $this->input->post('album_description', true),
			'album_slug' => slugify($this->input->post('album_title', true))
		];
	}

	/**
	* Validation Form
	* @return Boolean
	*/
	private function validation() {
		$this->load->library('form_validation');
		$val = $this->form_validation;
		$val->set_rules('album_title', 'Judul Album', 'trim|required');
		$val->set_rules('album_description', 'Keterangan', 'trim');
		$val->set_error_delimiters('<div>&sdot; ', '</div>');
		return $val->run();
	}

	/**
	* List Images
	* @return Object
	*/
	public function list_images() {
		if ($this->input->is_ajax_request()) {
			$id = _toInteger($this->input->post('id', true));
			if (_isNaturalNumber( $id )) {
				$this->load->model('m_photos');
				$query = $this->m_photos->get_photos($id);
				$items = [];
				foreach($query->result() as $row) {
					$items[] = [
						'src' => base_url('media_library/albums/large/'.$row->photo_name)
					];
				}
				$this->vars = [
					'count' => count($items),
					'items' => $items
				];
			}

			$this->output
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($this->vars, self::REQUIRED_FLAGS))
			->_display();
			exit;
		}
	}

	/**
	* Upload Cover albums
	* @return Void
	*/
	public function cover_upload() {
		if ($this->input->is_ajax_request()) {
			$id = _toInteger($this->input->post('id', true));
			if (_isNaturalNumber( $id )) {
				$query = $this->model->RowObject($this->pk, $id, $this->table);
				$file_name = $query->album_cover;
				$config['upload_path'] = './media_library/albums/';
				$config['allowed_types'] = 'jpg|png|jpeg|gif';
				$config['max_size'] = 0;
				$config['encrypt_name'] = TRUE;
				$this->load->library('upload', $config);
				if (!$this->upload->do_upload('file')) {
					$this->vars['status'] = 'error';
					$this->vars['message'] = $this->upload->display_errors();
				} else {
					$file = $this->upload->data();
					$update = $this->model->update($id, $this->table, ['album_cover' => $file['file_name']]);
					if ($update) {
						// resize new image
						$this->album_cover_resize(FCPATH.'media_library/albums/', $file['file_name']);
						// chmood new file
						@chmod(FCPATH.'media_library/albums/'.$file['file_name'], 0777);
						// chmood old file
						@chmod(FCPATH.'media_library/albums/'.$file_name, 0777);
						// unlink old file
						@unlink(FCPATH.'media_library/albums/'.$file_name);
					}
					$this->vars['status'] = 'success';
					$this->vars['message'] = 'uploaded';
				}
			}

			$this->output
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($this->vars, self::REQUIRED_FLAGS))
			->_display();
			exit;
		}
	}

	/**
	* Album Cover Resize
	* @param String $path
	* @param String $file_name
	* @return Void
	*/
	private function album_cover_resize($path, $file_name) {
		$this->load->library('image_lib');
		$config['image_library'] = 'gd2';
		$config['source_image'] = $path .'/'.$file_name;
		$config['maintain_ratio'] = false;
		$config['width'] = (int) __session('album_cover_width');
		$config['height'] = (int) __session('album_cover_height');
		$this->image_lib->initialize($config);
		$this->image_lib->resize();
	}

	/**
	* Upload Gallery Images
	* @return Void
	*/
	public function images_upload() {
		// if ($this->input->is_ajax_request()) {
		$id = _toInteger($this->uri->segment(4));
		if (_isNaturalNumber( $id )) {
			$config['upload_path'] = './media_library/albums/';
			$config['allowed_types'] = 'jpg|png|jpeg|gif';
			$config['max_size'] = 0;
			$config['encrypt_name'] = TRUE;
			$this->load->library('upload', $config);
			if (!$this->upload->do_upload('file')) {
				$this->vars['status'] = 'error';
				$this->vars['message'] = $this->upload->display_errors();
			} else {
				$file = $this->upload->data();
				$dataset = [
					'photo_album_id' => $id,
					'photo_name' => $file['file_name'],
					'created_at' => date('Y-m-d H:i:s'),
					'created_by' => __session('user_id')
				];
				$this->model->insert('photos', $dataset);
				// chmood new file
				@chmod(FCPATH.'media_library/albums/'.$file['file_name'], 0777);
				$this->image_resize(FCPATH.'media_library/albums/', $file['file_name'], 'large');
				$this->image_resize(FCPATH.'media_library/albums/', $file['file_name'], 'medium');
				$this->image_resize(FCPATH.'media_library/albums/', $file['file_name'], 'thumbnail');
				@unlink(FCPATH.'media_library/albums/'.$file['file_name']);
				$this->vars['status'] = 'success';
				$this->vars['message'] = 'uploaded';
			}
		}

		$this->output
		->set_content_type('application/json', 'utf-8')
		->set_output(json_encode($this->vars, self::REQUIRED_FLAGS))
		->_display();
		exit;
		// }
	}

	/**
	 * Resize Image
	 * @param String $path
	 * @param String $file_name
	 * @param String $size
	 * @return Void
	 */
	private function image_resize($path, $file_name, $size = 'large') {
		$settings = [
			'thumbnail_size_height' => __session('thumbnail_size_height'),
			'thumbnail_size_width' => __session('thumbnail_size_width'),
			'medium_size_height' => __session('medium_size_height'),
			'medium_size_width' => __session('medium_size_width'),
			'large_size_height' => __session('large_size_height'),
			'large_size_width' => __session('large_size_width')
		];
		$this->load->library('image_lib');
		$config['image_library'] = 'gd2';
		$config['source_image'] = $path .'/'.$file_name;
		$config['new_image'] = $path .'/'.$size;
		$config['maintain_ratio'] = false;
		$config['width'] = (int) $settings[$size.'_size_width'];
		$config['height'] = (int) $settings[$size.'_size_height'];
		$this->image_lib->initialize($config);
		$this->image_lib->resize();
		@chmod($path.'/'.$file_name, 0777);
	}
}
