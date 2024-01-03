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

class Download extends Public_Controller {

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
		$this->load->model('m_files');
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		$slug = $this->uri->segment(2);
		if (alpha_dash($slug)) {
			$this->vars['page_title'] = str_replace('-', ' ', $slug);
			$total_rows = $this->m_files->get_files($this->uri->segment(2))->num_rows(); // @param slug
			$this->vars['total_page'] = ceil($total_rows / self::$per_page);
			$this->vars['query'] = $this->m_files->get_files($slug, self::$per_page, 0);
			$this->vars['content'] = 'themes/'.theme_folder().'/loop-files';
			$this->load->view('themes/'.theme_folder().'/index', $this->vars);
		} else {
			show_404();
		}
	}

	/**
	 * Get Files
	 */
	public function get_files() {
		$slug = $this->input->post('slug', true);
		$page_number = _toInteger($this->input->post('page_number', true));
		$offset = ($page_number - 1) * self::$per_page;
		if (alpha_dash($slug)) {
			$query = $this->m_files->get_files($slug, self::$per_page, $offset);
			$this->vars['rows'] = $query->result();
		}
		$this->output
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($this->vars, self::REQUIRED_FLAGS))
			->_display();
		exit;
	}

	/**
	 * Force Download
	 */
	public function force_download() {
		$id = _toInteger($this->uri->segment(4));
		if (_isNaturalNumber( $id )) {
			$query = $this->model->RowObject('id', $id, 'files');
			if ($query->file_visibility == 'private' && ! $this->auth->hasLogin()) {
				show_404();
			}
			$this->load->helper(['download', 'text']);
			$file = file_get_contents("./media_library/files/" . $query->file_name);
			$name = url_title(strtolower($query->file_title), '-'). $query->file_ext;
			$this->m_files->set_file_counter($id);
			force_download($name, $file);
		} else {
			show_404();
		}
	}
}
