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

class Administrator extends Admin_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model([
			'm_user_administrator',
			'm_user_groups'
		]);
		$this->pk = M_user_administrator::$pk;
		$this->table = M_user_administrator::$table;
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		$this->vars['title'] = 'Pengguna Administrator';
		$this->vars['users'] = $this->vars['administrator'] = TRUE;
		$this->vars['user_group_dropdown'] = json_encode($this->m_user_groups->dropdown(), JSON_HEX_APOS | JSON_HEX_QUOT);
		$this->vars['content'] = 'users/administrator';
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
			$query = $this->m_user_administrator->get_where($keyword, 'rows', $limit, $offset);
			$total_rows = $this->m_user_administrator->get_where($keyword);
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
			if ($this->validation( $id )) {
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
	 * dataset
	 * @param Integer
	 * @return Array
	 */
	private function dataset() {
		$dataset = [];
		$dataset['user_name'] = $this->input->post('user_name', true);
		$user_password = $this->input->post('user_password', true);
		if (!empty($user_password)) $dataset['user_password'] = password_hash($user_password, PASSWORD_BCRYPT);
		$dataset['user_email'] = $this->input->post('user_email') ? $this->input->post('user_email', true) : NULL;
		$dataset['user_url'] = $this->input->post('user_url') ? prep_url($this->input->post('user_url', true)) : NULL;
		$dataset['user_full_name'] = $this->input->post('user_full_name', true);
		$dataset['user_biography'] = $this->input->post('user_biography', true);
		$dataset['user_group_id'] = $this->input->post('user_group_id', true) ? $this->input->post('user_group_id', true) : 0;
		$dataset['user_type'] = 'administrator';
		return $dataset;
	}

	/**
	 * Validation Form
	 * @param Integer
	 * @return Boolean
	 */
	private function validation( $id = 0 ) {
		$this->load->library('form_validation');
		$val = $this->form_validation;
		$val->set_rules('user_name', 'User Name', 'trim|required');
		if (_isNaturalNumber( $id )) {
			$val->set_rules('user_password', 'Password', 'trim|min_length[6]');
		} else {
			$val->set_rules('user_password', 'Password', 'trim|required|min_length[6]');
		}
		$val->set_rules('user_email', 'Email', 'trim|required|valid_email|callback_email_exists[' . $id . ']');
		$val->set_rules('user_url', 'URL', 'trim|valid_url');
		$val->set_rules('user_full_name', 'Full Name', 'trim|required');
		$val->set_rules('user_group_id', 'Group', 'trim|required');
		$val->set_rules('user_biography', 'Biography', 'trim');
		$val->set_message('required', '{field} harus diisi');
		$val->set_message('valid_email', '{field} harus diisi dengan format email yang benar');
		$val->set_error_delimiters('<div>&sdot; ', '</div>');
		return $val->run();
	}

	/**
	 * Email Exists ?
	 * @param String $email
	 * @param Integer $id
	 * @return Boolean
	 */
	public function email_exists( $email, $id ) {
		$email_exists = $this->m_user_administrator->email_exists( $email, $id );
		if ( $email_exists ) {
			$this->form_validation->set_message('email_exists', 'Email sudah digunakan');
			return FALSE;
		}
		return TRUE;
	}
}
