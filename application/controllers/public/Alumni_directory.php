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

class Alumni_directory extends Public_Controller {

	/**
	 * Limit per page
	 * @var Integer
	 */
	public static $per_page = 10;

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model('m_alumni');
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		$this->vars['page_title'] = 'DIREKTORI ALUMNI';
		$total_rows = $this->m_alumni->get_alumni()->num_rows();
		$this->vars['total_page'] = ceil($total_rows / self::$per_page);
		$this->vars['query'] = $this->m_alumni->get_alumni(self::$per_page);
		$this->vars['content'] = 'themes/'.theme_folder().'/loop-alumni';
		$this->load->view('themes/'.theme_folder().'/index', $this->vars);
	}

	/**
	 * Get Alumni
	 * @return Object
	 */
	public function get_alumni() {
		if ($this->input->is_ajax_request()) {
			$page_number = _toInteger($this->input->post('page_number', true));
			$offset = ($page_number - 1) * self::$per_page;
			$query = $this->m_alumni->get_alumni(self::$per_page, $offset);
			$rows = [];
			foreach($query->result() as $row) {
				$photo = 'no-image.png';
				if ($row->photo && file_exists($_SERVER['DOCUMENT_ROOT'] . '/media_library/students/'.$row->photo)) {
					$photo = $row->photo;
				}
				$rows[] = [
					'identity_number' => $row->identity_number,
					'full_name' => $row->full_name,
					'gender' => $row->gender,
					'birth_place' => $row->birth_place,
					'birth_date' => indo_date($row->birth_date),
					'start_date' => $row->start_date,
					'end_date' => $row->end_date,
					'photo' => base_url('media_library/students/'.$photo)
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
