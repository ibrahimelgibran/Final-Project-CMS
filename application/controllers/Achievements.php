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

class Achievements extends Admin_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model('m_achievements');
		$this->pk = M_achievements::$pk;
		$this->table = M_achievements::$table;
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		$this->vars['title'] = 'Prestasi';
		$this->vars['achievements'] = TRUE;
		$this->vars['content'] = 'achievements';
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
			$query = $this->m_achievements->get_where($keyword, 'rows', $limit, $offset);
			$total_rows = $this->m_achievements->get_where($keyword);
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
	 * Save or Update
	 * @return Object
	 */
	public function save() {
		if ($this->input->is_ajax_request()) {
			$id = _toInteger($this->input->post('id', true));
			if ($this->validation()) {
				$dataset = $this->dataset();
				if (_isNaturalNumber( $id )) {
					$query = $this->model->RowObject($this->pk, $id, $this->table);
					if ($query->student_id == __session('user_profile_id')) {
						$dataset['updated_by'] = __session('user_id');
						$this->vars['status'] = $this->model->update($id, $this->table, $dataset) ? 'success' : 'error';
						$this->vars['message'] = $this->vars['status'] == 'success' ? 'updated' : 'not_updated';
					} else {
						$this->vars['status'] = 'error';
						$this->vars['message'] = 'forbidden';
					}
				} else {
					$dataset['created_at'] = date('Y-m-d H:i:s');
					$dataset['created_by'] = __session('user_id');
					$this->vars['status'] = $this->model->insert($this->table, $dataset) ? 'success' : 'error';
					$this->vars['message'] = $this->vars['status'] == 'success' ? 'created' : 'not_created';
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
			'student_id' => (int) __session('user_profile_id'),
			'achievement_description' => $this->input->post('achievement_description', true),
			'achievement_type' => $this->input->post('achievement_type', true),
			'achievement_level' => $this->input->post('achievement_level', true),
			'achievement_year' => $this->input->post('achievement_year', true),
			'achievement_organizer' => $this->input->post('achievement_organizer', true)
		];
	}

	/**
	 * Validation Form
	 * @return Boolean
	 */
	private function validation() {
		$this->load->library('form_validation');
		$val = $this->form_validation;
		$val->set_rules('achievement_description', 'Nama Prestasi', 'trim|required');
		$val->set_rules('achievement_type', 'Jenis Prestasi', 'trim|required|is_natural_no_zero');
		$val->set_rules('achievement_level', 'Tingkat Prestasi', 'trim|required|is_natural_no_zero');
		$val->set_rules('achievement_year', 'Tahun', 'trim|is_natural_no_zero|required|min_length[4]|max_length[4]');
		$val->set_rules('achievement_organizer', 'Penyelenggara', 'trim');
		$val->set_error_delimiters('<div>&sdot; ', '</div>');
		return $val->run();
	}
}
