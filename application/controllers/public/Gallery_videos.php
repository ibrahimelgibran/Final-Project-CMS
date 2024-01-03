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

class Gallery_videos extends Public_Controller {

	/**
	 * Limit per page
	 * @var Integer
	 */
	public static $per_page = 6;

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model('m_videos');
	}

	/**
	 * Index
	 */
	public function index() {
		$this->vars['page_title'] = 'Galeri Video';
		$total_rows = $this->m_videos->get_videos()->num_rows();
		$this->vars['total_page'] = ceil($total_rows / self::$per_page);
		$this->vars['query'] = $this->m_videos->get_videos(self::$per_page);
		$this->vars['content'] = 'themes/'.theme_folder().'/loop-videos';
		$this->load->view('themes/'.theme_folder().'/index', $this->vars);
	}

	/**
	 * Get Videos
	 * @return Object
	 */
	public function get_videos() {
		if ($this->input->is_ajax_request()) {
			$page_number = _toInteger($this->input->post('page_number', true));
			$offset = ($page_number - 1) * self::$per_page;
			$query = $this->m_videos->get_videos(self::$per_page, $offset);
			$this->vars['rows'] = $query->result();
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($this->vars, self::REQUIRED_FLAGS))
				->_display();
			exit;
		}
	}
}
