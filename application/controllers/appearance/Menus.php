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

class Menus extends Admin_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model('m_menus');
		$this->pk = M_menus::$pk;
		$this->table = M_menus::$table;
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		$this->vars['title'] = 'Menu';
		$this->vars['appearance'] = $this->vars['menus'] = TRUE;
		$this->vars['content'] = 'appearance/menus';
		$this->load->view('backend/index', $this->vars);
	}

	/**
	 * Get All Menus
	 * @return Object
	 */
	public function get_menus() {
		if ($this->input->is_ajax_request()) {
			$query = $this->m_menus->get_menus();
			$this->vars['rows'] = $query->result();
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($this->vars, self::REQUIRED_FLAGS))
				->_display();
			exit;
		}
	}

	/**
    * Get All Pages
    * @return Object
    */
	public function get_pages() {
		if ($this->input->is_ajax_request()) {
			$this->load->model('m_pages');
			$query = $this->m_pages->get_pages();
			$this->vars['rows'] = $query->result();
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($this->vars, self::REQUIRED_FLAGS))
				->_display();
			exit;
		}
	}

	/**
    * Get All Post Categories
    * @return Object
    */
	public function get_post_categories() {
		if ($this->input->is_ajax_request()) {
			$this->load->model('m_categories');
			$query = $this->m_categories->get_categories('post');
			$this->vars['rows'] = $query->result();
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($this->vars, self::REQUIRED_FLAGS))
				->_display();
			exit;
		}
	}

	/**
    * Get All File Categories
    * @return Object
    */
	public function get_file_categories() {
		if ($this->input->is_ajax_request()) {
			$this->load->model('m_categories');
			$query = $this->m_categories->get_categories('file');
			$this->vars['rows'] = $query->result();
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($this->vars, self::REQUIRED_FLAGS))
				->_display();
			exit;
		}
	}

	/**
	 * Delete Menus Permanently
	 * @return Object
	 */
	public function delete_permanently() {
		if ($this->input->is_ajax_request()) {
			$id = _toInteger($this->input->post('id', true));
			if (_isNaturalNumber( $id )) {
				$is_exists = $this->model->is_exists('menu_parent_id', $id, $this->table);
				if ( ! $is_exists ) {
					$this->model->delete_permanently($this->pk, $id, $this->table);
					$this->vars['status'] = $this->model->delete_permanently($this->pk, $id, $this->table) ? 'success' : 'error';
					$this->vars['message'] = $this->vars['status'] == 'success' ? 'deleted' : 'not_deleted';
				} else {
					$this->vars['status'] = 'warning';
					$this->vars['message'] = '"Parent menu" tidak dapat dihapus. Silahkan hapus terlebih dahulu "child menu"!';
				}
			} else {
				$this->vars['status'] = 'error';
				$this->vars['message'] = 'Not initialize id OR id not a number';
			}
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($this->vars, self::REQUIRED_FLAGS))
				->_display();
			exit;
		}
	}

	/**
	 * Truncate table menus
	 * @return Object
	 */
	public function truncate_table() {
		if ($this->input->is_ajax_request()) {
			$this->vars['status'] = $this->model->truncate($this->table) ? 'success' : 'error';
			$this->vars['message'] = $this->vars['status'] == 'success' ? 'deleted' : 'not_deleted';
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($this->vars, self::REQUIRED_FLAGS))
				->_display();
			exit;
		}
	}

	/**
	 * Get Nested Menus
	 * @return Object
	 */
	public function nested_menus() {
		if ($this->input->is_ajax_request()) {
			$query = $this->m_menus->nested_menus();
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($query, self::REQUIRED_FLAGS))
				->_display();
			exit;
		}
	}

   /**
    * Save menu position
    * @return Object
    */
   public function save_menu_position() {
   	if ($this->input->is_ajax_request()) {
			if (NULL !== $this->input->post('menus')) {
				$menus = json_decode($this->input->post('menus'), true);
				$this->m_menus->save_menu_position(0, $menus);
			}
			$this->vars['status'] = 'success';
			$this->vars['message'] = 'Your data have been saved.';
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($this->vars, self::REQUIRED_FLAGS))
				->_display();
			exit;
		}

   }

	/**
    * Save Custom Links
    * @return Object
    */
	public function save_links() {
		if ($this->input->is_ajax_request()) {
			$this->db->set('menu_url', ($this->input->post('menu_url', true) && $this->input->post('menu_url', true) != '#') ? prep_url($this->input->post('menu_url', true)) : '#');
			$this->db->set('menu_title', $this->input->post('menu_title', true));
			$this->db->set('menu_target', $this->input->post('menu_target', true));
			$this->db->set('menu_type', 'links');
			$this->db->set('created_at', date('Y-m-d H:i:s'));
			$this->db->set('created_by', __session('user_id'));
			$this->vars['status'] = $this->db->insert($this->table) ? 'success' : 'error';
			$this->vars['message'] = $this->vars['status'] == 'success' ? 'created' : 'not_created';
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($this->vars, self::REQUIRED_FLAGS))
				->_display();
			exit;
		}
	}

	/**
    * Save Menus From Pages
    * @return Object
    */
	public function save_pages() {
		if ($this->input->is_ajax_request()) {
			$ids = explode(',', $this->input->post('ids'));
			foreach($ids as $id) {
				$query = $this->model->RowObject('id', $id, 'posts');
				$this->db->set('menu_title', $query->post_title);
				$this->db->set('menu_url', 'read/' . $id . '/'.$query->post_slug);
				$this->db->set('menu_type', 'pages');
				$this->db->set('menu_target', '_self');
				$this->db->set('created_at', date('Y-m-d H:i:s'));
				$this->db->set('created_by', __session('user_id'));
				$this->db->insert($this->table);
			}
			$this->vars['status'] = 'success';
			$this->vars['message'] = 'created';
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($this->vars, self::REQUIRED_FLAGS))
				->_display();
			exit;
		}
	}

	/**
    * Save Menus From Posts Categories
    * @return Object
    */
	public function save_post_categories() {
		if ($this->input->is_ajax_request()) {
			$ids = explode(',', $this->input->post('ids'));
			foreach($ids as $id) {
				$query = $this->model->RowObject('id', $id, 'categories');
				$this->db->set('menu_title', $query->category_name);
				$this->db->set('menu_url', 'kategori/'.$query->category_slug);
				$this->db->set('menu_type', 'post_categories');
				$this->db->set('menu_target', '_self');
				$this->db->set('created_at', date('Y-m-d H:i:s'));
				$this->db->set('created_by', __session('user_id'));
				$this->db->insert($this->table);
			}
			$this->vars['status'] = 'success';
			$this->vars['message'] = 'created';
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($this->vars, self::REQUIRED_FLAGS))
				->_display();
			exit;
		}
	}

	/**
    * Save Menus From File Categories
    * @return Object
    */
	public function save_file_categories() {
		if ($this->input->is_ajax_request()) {
			$ids = explode(',', $this->input->post('ids'));
			foreach($ids as $id) {
				$query = $this->model->RowObject('id', $id, 'categories');
				$this->db->set('menu_title', $query->category_name);
				$this->db->set('menu_url', 'download/'.$query->category_slug);
				$this->db->set('menu_type', 'file_categories');
				$this->db->set('menu_target', '_self');
				$this->db->set('created_at', date('Y-m-d H:i:s'));
				$this->db->set('created_by', __session('user_id'));
				$this->db->insert($this->table);
			}
			$this->vars['status'] = 'success';
			$this->vars['message'] = 'created';
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($this->vars, self::REQUIRED_FLAGS))
				->_display();
			exit;
		}
	}

	/**
    * Save Menus From List Modules
    * @return Object
    */
	public function save_modules() {
		if ($this->input->is_ajax_request()) {
			$modules = explode(',', $this->input->post('modules'));
			foreach($modules as $module) {
				$this->db->set('menu_title', modules($module));
				$this->db->set('menu_url', $module);
				$this->db->set('menu_type', 'modules');
				$this->db->set('menu_target', '_self');
				$this->db->set('created_at', date('Y-m-d H:i:s'));
				$this->db->set('created_by', __session('user_id'));
				$this->db->insert($this->table);
			}
			$this->vars['status'] = 'success';
			$this->vars['message'] = 'created';
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
				$dataset['updated_by'] = __session('user_id');
				$this->vars['status'] = $this->model->update($id, $this->table, $dataset) ? 'success' : 'error';
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
		$dataset = [];
		$dataset['menu_title'] = $this->input->post('menu_title', true);
		$dataset['menu_url'] = $this->input->post('menu_url', true);
		$dataset['menu_target'] = $this->input->post('menu_target', true);
		$is_deleted = $this->input->post('is_deleted');
		$dataset['is_deleted'] = $is_deleted;
		if (filter_var((string) $is_deleted, FILTER_VALIDATE_BOOLEAN)) {
			$dataset['deleted_by'] = __session('user_id');
			$dataset['deleted_at'] = date('Y-m-d H:i:s');
		} else {
			$dataset['restored_by'] = __session('user_id');
			$dataset['restored_at'] = date('Y-m-d H:i:s');
		}
		return $dataset;
	}

	/**
	 * Validation Form
	 * @return Boolean
	 */
	private function validation() {
		$this->load->library('form_validation');
		$val = $this->form_validation;
		$val->set_rules('menu_title', 'Judul Menu', 'trim|required');
		$val->set_rules('menu_url', 'URL', 'trim|required');
		$val->set_rules('menu_target', 'Target', 'trim|required');
		$val->set_rules('is_deleted', 'Aktif ?', 'trim|required|in_list[true,false]');
		$val->set_error_delimiters('<div>&sdot; ', '</div>');
		return $val->run();
	}
}
