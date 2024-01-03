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

class Employee_directory extends Public_Controller {

	/**
	 * Limit per page
	 * @var Integer
	 */
	public static $per_page = 20;

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model('m_employees');
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		$this->vars['page_title'] = 'DIREKTORI ' . strtoupper(__session('__employee'));
		$total_rows = $this->m_employees->get_employees()->num_rows();
		$this->vars['total_page'] = ceil($total_rows / self::$per_page);
		$this->vars['query'] = $this->m_employees->get_employees(self::$per_page);
		$this->vars['content'] = 'themes/'.theme_folder().'/loop-employees';
		$this->load->view('themes/'.theme_folder().'/index', $this->vars);
	}

	/**
	 * Get Employees
	 * @return Object
	 */
	public function get_employees() {
		if ($this->input->is_ajax_request()) {
			$page_number = _toInteger($this->input->post('page_number', true));
			$offset = ($page_number - 1) * self::$per_page;
			$query = $this->m_employees->get_employees(self::$per_page, $offset);
			$rows = [];
			foreach($query->result() as $row) {
				$photo = 'no-image.png';
				if ($row->photo && file_exists(BASEPATH . '../media_library/employees/'.$row->photo)) {
					$photo = $row->photo;
				}
				$rows[] = [
					'nik' => $row->nik,
					'full_name' => $row->full_name,
					'gender' => $row->gender,
					'birth_place' => $row->birth_place,
					'birth_date' => indo_date($row->birth_date),
					'photo' => base_url('media_library/employees/'.$photo),
					'employment_type' => $row->employment_type,
					'guru_mapel' => $row->guru_mapel,
					'nbm' => $row->nbm,
					'motto' => $row->motto,
				];
			}
			$this->vars['rows'] = $rows;
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($this->vars, self::REQUIRED_FLAGS))
				->_display();
			exit;
		}
	}
}
