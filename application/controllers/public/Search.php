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

class Search extends Public_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->helper('text');
		$this->load->model('public/m_posts');
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		if ($_POST) {
			$this->load->library('form_validation');
			$this->form_validation->set_rules('keyword', 'Kata Kunci Pencarian', 'trim|required|alpha_numeric_spaces|max_length[100]');
			$this->form_validation->set_message('required', '{field} wajib diisi.');
			$this->form_validation->set_message('alpha_numeric_spaces', '{field} hanya bisa diisi huruf dan angka.');
			if (FALSE === $this->form_validation->run()) {
				$this->session->unset_userdata('keyword');
				$this->vars['query'] = FALSE;
				$this->vars['page_title'] = validation_errors();
			} else {
				$keyword = trim(strip_tags($this->input->post('keyword', true)));
				$this->session->set_userdata('keyword', $keyword);
				$this->vars['page_title'] = 'Hasil pencarian dengan kata kunci "' . $this->session->keyword . '"';
				$this->vars['query'] = $this->m_posts->search($keyword);
			}
			$this->vars['content'] = 'themes/'.theme_folder().'/search-results';
			$this->load->view('themes/'.theme_folder().'/index', $this->vars);
		} else {
			return redirect(base_url());
		}
	}
}
