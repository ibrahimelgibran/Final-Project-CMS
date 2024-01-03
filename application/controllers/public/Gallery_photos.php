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

class Gallery_photos extends Public_Controller {

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
		$this->load->model('m_albums');
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		$this->vars['page_title'] = 'Galeri Foto';
		$total_rows = $this->m_albums->get_albums()->num_rows();
		$this->vars['total_page'] = ceil($total_rows / self::$per_page);
		$this->vars['query'] = $this->m_albums->get_albums( self::$per_page );
		$this->vars['content'] = 'themes/'.theme_folder().'/loop-albums';
		$this->load->view('themes/'.theme_folder().'/index', $this->vars);
	}

	/**
	 * Get Albums
	 * @return Object
	 */
	public function get_albums() {
		if ($this->input->is_ajax_request()) {
			$page_number = _toInteger($this->input->post('page_number', true));
			$offset = ($page_number - 1) * self::$per_page;
			$query = $this->m_albums->get_albums(self::$per_page, $offset);
			$this->vars['rows'] = $query->result();
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($this->vars, self::REQUIRED_FLAGS))
				->_display();
			exit;
		}
	}

	/**
	 * List Images
	 * @return Object
	 */
	public function preview() {
		if ($this->input->is_ajax_request()) {
			$id = _toInteger($this->input->post('id', true));
			if (_isNaturalNumber( $id )) {
				$this->load->model('m_photos');
				$query = $this->m_photos->get_photos($id);
				$items = [];
				foreach($query->result() as $row) {
					$items[] = [
						'src' => base_url('media_library/albums/large/'.$row->photo_name)
					];
				}
				$this->vars = [
					'count' => count($items),
					'items' => $items
				];
			}

			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($this->vars, self::REQUIRED_FLAGS))
				->_display();
			exit;
		}
	}
}
