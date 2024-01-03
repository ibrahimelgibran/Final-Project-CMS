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

class Class_group_students extends Admin_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->helper('form');
		$this->load->model([
			'm_academic_years',
			'm_class_groups',
			'm_class_group_students',
			'm_students'
			]);
	}

	/**
	 * Create
	 */
	public function create() {
		$this->vars['title'] = 'Pengaturan Rombongan Belajar';
		$this->vars['academic'] = $this->vars['academic_settings'] = $this->vars['class_group_students'] = TRUE;
		$this->vars['academic_year_dropdown'] = $this->m_academic_years->dropdown();
		$this->vars['class_group_dropdown'] = $this->m_class_groups->dropdown();
		$this->vars['content'] = 'academic/set_class_group_students';
		$this->load->view('backend/index', $this->vars);
	}

	/**
	 * Get Students
	 */
	public function get_students() {
		if ($this->input->is_ajax_request()) {
			$academic_year_id = _toInteger($this->input->post('academic_year_id', true));
			$class_group_id = $this->input->post('class_group_id', true);
			$this->vars['rows'] = $this->m_class_group_students->get_students($academic_year_id, $class_group_id);
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($this->vars, self::REQUIRED_FLAGS))
				->_display();
			exit;
		}
	}

	/**
	 * Save to Destination Class
	 */
	public function save_to_destination_class() {
		if ($this->input->is_ajax_request()) {
			$student_ids = $this->input->post('student_ids', true);
			$ids = [];
			foreach (explode(',', $student_ids) as $student_id) {
				array_push($ids, trim($student_id));
			}
			$academic_year_id = _toInteger($this->input->post('academic_year_id', true));
			$class_group_id = _toInteger($this->input->post('class_group_id', true));
			$query = $this->m_class_group_students->save_to_destination_class($ids, $academic_year_id, $class_group_id);
			$this->vars['status'] = $query ? 'success' : 'error';
			$this->vars['message'] = $query ? 'Data sudah disipman' : 'Data tidak tersimpan. Kemungkinan terjadi duplikasi data, pengaturan wali kelas belum diatur, atau server bermasalah, silahkan periksa kembali data Anda.';
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($this->vars, self::REQUIRED_FLAGS))
				->_display();
			exit;
		}
	}

	/**
	 * Delete Permanently
	 */
	public function delete_permanently() {
		if ($this->input->is_ajax_request()) {
			$student_ids = $this->input->post('student_ids', true);
			$ids = [];
			foreach (explode(',', $student_ids) as $student_id) {
				array_push($ids, trim($student_id));
			}
			$academic_year_id = _toInteger($this->input->post('academic_year_id', true));
			$class_group_id = _toInteger($this->input->post('class_group_id', true));
			$query = $this->m_class_group_students->delete_permanently($ids, $academic_year_id, $class_group_id);
			$this->vars['status'] = $query ? 'success' : 'error';
			$this->vars['message'] = $query ? 'Data sudah terhapus' : 'Data tidak terhapus';
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($this->vars, self::REQUIRED_FLAGS))
				->_display();
			exit;
		}
	}

	/**
	 * Set as alumni
	 */
	public function set_as_alumni() {
		if ($this->input->is_ajax_request()) {
			$student_ids = $this->input->post('student_ids', true);
			$ids = [];
			foreach (explode(',', $student_ids) as $student_id) {
				array_push($ids, trim($student_id));
			}
			$end_date = (int) $this->input->post('end_date', true);
			$query = $this->m_students->set_as_alumni($ids, $end_date);
			$this->vars['status'] = $query ? 'success' : 'error';
			$this->vars['message'] = $query ? 'Data sudah tersimpan' : 'Data tidak tersimpan';
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($this->vars, self::REQUIRED_FLAGS))
				->_display();
			exit;
		}
	}
}
