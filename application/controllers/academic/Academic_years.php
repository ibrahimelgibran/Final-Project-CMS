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

class Academic_years extends Admin_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model('m_academic_years');
		$this->pk = M_academic_years::$pk;
		$this->table = M_academic_years::$table;
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		$this->vars['title'] = __session('_academic_year');
		$this->vars['academic'] = $this->vars['academic_references'] = $this->vars['academic_years'] = TRUE;
		$this->vars['content'] = 'academic/academic_years';
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
			$query = $this->m_academic_years->get_where($keyword, 'rows', $limit, $offset);
			$total_rows = $this->m_academic_years->get_where($keyword);
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
				if (_isNaturalNumber( $id )) {
					$dataset['updated_by'] = __session('user_id');
					$this->vars['status'] = $this->model->update($id, $this->table, $dataset) ? 'success' : 'error';
					$this->vars['message'] = $this->vars['status'] == 'success' ? 'updated' : 'not_updated';
					if ($this->vars['status'] == 'success' && filter_var((string) $dataset['current_semester'], FILTER_VALIDATE_BOOLEAN)) {
						$this->m_academic_years->deactivate_academic_years($id, 'current_semester');
					}
					if ($this->vars['status'] == 'success' && filter_var((string) $dataset['admission_semester'], FILTER_VALIDATE_BOOLEAN)) {
						$this->m_academic_years->deactivate_academic_years($id, 'admission_semester');
					}
				} else {
					if (filter_var((string) $dataset['current_semester'], FILTER_VALIDATE_BOOLEAN)) {
						$this->m_academic_years->deactivate_academic_years(0, 'current_semester');
					}
					if (filter_var((string) $dataset['admission_semester'], FILTER_VALIDATE_BOOLEAN)) {
						$this->m_academic_years->deactivate_academic_years(0, 'admission_semester');
					}
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
			'academic_year' => $this->input->post('academic_year', true),
			'semester' => $this->input->post('semester', true),
			'current_semester' => $this->input->post('current_semester', true),
			'admission_semester' => $this->input->post('admission_semester', true)
		];
	}

	/**
	 * Validation Form
	 * @return Boolean
	 */
	private function validation() {
		$this->load->library('form_validation');
		$val = $this->form_validation;
		$val->set_rules('academic_year', 'Academic Years', 'trim|required|min_length[9]|max_length[9]|callback_format_check');
		$val->set_rules('semester', 'Semester', 'trim|required');
		$val->set_rules('current_semester', 'Semester Aktif', 'trim|required|in_list[true,false]');
		$val->set_rules('admission_semester', 'Semester PPDB/PMB', 'trim|required|in_list[true,false]');
		$val->set_error_delimiters('<div>&sdot; ', '</div>');
		return $val->run();
	}

	/**
	 * Format Check
	 * @param String $str
	 * @return Boolean
	 */
	public function format_check($str) {
		$year = explode('-', substr($str, 0, 9));
		if (FALSE === strpos($str, '-')) {
			$this->form_validation->set_message('format_check', 'Tahun awal dan tahun akhir harus dipisah dengan tanda strip (-)');
			return FALSE;
		} else if (strlen($str) !== 9) {
			$this->form_validation->set_message('format_check', 'Format tahun pelajaran harus 9 digit. Contoh : 2017-2018');
			return FALSE;
		} else if ((int) $year[ 0 ] === 0 || (int) $year[ 1 ] === 0) {
			$this->form_validation->set_message('format_check', 'Format tahun pelajaran harus diisi angka. Contoh : 2017-2018');
			return FALSE;
		} else if (($year[1] - $year[0]) != 1) {
			$this->form_validation->set_message('format_check', 'Tahun awal dan tahun akhir harus selisih satu !');
			return FALSE;
		} else if (strlen($year[0]) != 4 && strlen($ta[1] != 4)) {
			$this->form_validation->set_message('format_check', 'Tahun harus 4 karakter !');
			return FALSE;
		}
		return TRUE;
	}
}
