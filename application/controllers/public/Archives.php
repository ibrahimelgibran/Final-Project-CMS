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

class Archives extends Public_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model('public/m_posts');
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		$year = substr($this->uri->segment(2), 0, 4);
		$month = substr($this->uri->segment(3), 0, 2);
		if ($year && $month) {
			$this->vars['page_title'] = 'Arsip Bulan ' . bulan($month).' '.$year;
			$total_rows = $this->m_posts->get_post_archives($year, $month)->num_rows();
			$this->vars['total_page'] = ceil($total_rows / __session('post_per_page'));
			$this->vars['query'] = $this->m_posts->get_post_archives($year, $month, __session('post_per_page'));
			$this->vars['content'] = 'themes/'.theme_folder().'/loop-posts';
			$this->load->view('themes/'.theme_folder().'/index', $this->vars);
		} else {
			show_404();
		}
	}

	/**
	 * Get Post Archives
	 * @return Object
	 */
	public function get_posts() {
		if ($this->input->is_ajax_request()) {
			$year = substr($this->input->post('year', true), 0, 4);
			$month = substr($this->input->post('month', true), 0, 2);
			$page_number = _toInteger($this->input->post('page_number', true));
			$offset = ($page_number - 1) * __session('post_per_page');
			$query = $this->m_posts->get_post_archives($year, $month, __session('post_per_page'), $offset);
			$this->vars['rows'] = $query->result();
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($this->vars, self::REQUIRED_FLAGS))
				->_display();
			exit;
		}
	}
}
