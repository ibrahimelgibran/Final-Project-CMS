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

class Selection_process extends Admin_Controller {

	/**
	 * Class Constructor
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->helper('form');
		$this->load->model([
			'm_majors',
			'm_academic_years',
			'm_admission_selection_process'
		]);
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		$this->vars['title'] = 'Proses Seleksi';
		$this->vars['admission'] = $this->vars['selection_process'] = TRUE;
		$options = [];
		$options['unapproved'] = 'Tidak Diterima';
		if (__session('major_count') > 0) {
			$query = $this->m_majors->dropdown();
			foreach ($query as $key => $value) {
				$options[$key] = 'Diterima di '. $value;
			}
		} else {
			$options['approved'] = 'Diterima';
		}
		$this->vars['options'] = $options;
		$this->vars['admission_year_dropdown'] = $this->m_academic_years->dropdown(true);
		$this->vars['admission_type_dropdown'] = get_options('admission_types', FALSE);
		$majors = $this->m_majors->dropdown();
		if (__session('school_level') >= 3) {
			$majors  = [0 => 'Unset'] + $majors;
		}
		$this->vars['major_dropdown'] = $majors;
		$this->vars['content'] = 'admission/selection_process';
		$this->load->view('backend/index', $this->vars);
	}

	/**
	 * Get Prospective Students
	 */
	public function get_prospective_students() {
		if ($this->input->is_ajax_request()) {
			$admission_year_id = (int) $this->input->post('admission_year_id', true);
			$admission_type_id = (int) $this->input->post('admission_type_id', true);
			$major_id = (int) $this->input->post('major_id', true);
			$page_number = _toInteger($this->input->post('page_number', true));
			$limit = _toInteger($this->input->post('per_page', true));
			$offset = ($page_number * $limit);
			// Get Prosvective Students
			$query = $this->m_admission_selection_process->get_prospective_students($admission_year_id, $admission_type_id, $major_id, 'rows', $limit, $offset);
			$total_rows = $this->m_admission_selection_process->get_prospective_students($admission_year_id, $admission_type_id, $major_id);
			$total_page = $limit > 0 ? ceil(_toInteger($total_rows) / _toInteger($limit)) : 1;
			$this->vars['total_rows'] = _toInteger($total_rows);
			$this->vars['total_page'] = _toInteger($total_page);
			$this->vars['students'] = [];
			if (__session('major_count') > 0) {
				foreach($query->result() as $row) {
					$this->vars['students'][] = [
						'id' => $row->id,
						'first_choice' => $row->first_choice,
						'second_choice' => $row->second_choice,
						'registration_number' => $row->registration_number,
						'full_name' => $row->full_name
					];
				}
			} else {
				foreach($query->result() as $row) {
					$this->vars['students'][] = [
						'id' => $row->id,
						'registration_number' => $row->registration_number,
						'full_name' => $row->full_name
					];
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
	 * save
	 */
	public function save() {
		if ($this->input->is_ajax_request()) {
			$admission_year_id = (int) $this->input->post('admission_year_id', true);
			$admission_type_id = (int) $this->input->post('admission_type_id', true);
			$selection_result = $this->input->post('selection_result', true);
			$student_ids = explode(',', $this->input->post('student_ids'));
			$query = $this->m_admission_selection_process->selection_process($admission_year_id, $admission_type_id, $selection_result, $student_ids);
			$this->vars['status'] = $query['status'];
			$this->vars['message'] = $query['message'];
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($this->vars, self::REQUIRED_FLAGS))
				->_display();
			exit;
		}
	}
}
