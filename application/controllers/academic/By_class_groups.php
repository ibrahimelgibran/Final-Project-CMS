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

class By_class_groups extends Admin_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model([
			'm_students',
			'm_academic_years'
		]);
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		$this->vars['title'] = 'Grafik ' . ucwords(strtolower(__session('_student'))) . ' Berdasarkan Kelas';
		$this->vars['academic'] = $this->vars['academic_chart'] = $this->vars['by_class_groups'] = TRUE;
		$this->vars['academic_year_dropdown'] = $this->m_academic_years->dropdown();
		$this->vars['content'] = 'academic/by_class_groups';
		$this->load->view('backend/index', $this->vars);
	}

	/**
	 * Generate Chart
	 */
	public function generate_chart() {
		if ($this->input->is_ajax_request()) {
			$academic_year_id = _toInteger($this->input->post('academic_year_id', true));
			$query = $this->m_students->chart_by_class_groups($academic_year_id);
			$this->vars['title'] = 'Grafik ' . ucwords(strtolower(__session('_student'))) . ' Berdasarkan Kelas';
			$this->vars['labels'] = [];
			$this->vars['data'] = [];
			foreach($query->result() as $row) {
				array_push($this->vars['labels'], $row->labels);
				array_push($this->vars['data'], $row->data);
			}
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($this->vars, self::REQUIRED_FLAGS))
				->_display();
			exit;
		}
	}
}
