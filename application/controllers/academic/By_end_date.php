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

class By_end_date extends Admin_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model('m_students');
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		$this->vars['title'] = 'Grafik ' . ucwords(strtolower(__session('_student'))) .' Berdasarkan Tahun Lulus';
		$this->vars['academic'] = $this->vars['academic_chart'] = $this->vars['by_end_date'] = TRUE;
		$labels = [];
		$data = [];
		$query = $this->m_students->chart_by_end_date();
		foreach($query->result() as $row) {
			array_push($labels, $row->labels);
			array_push($data, $row->data);
		}
		$this->vars['labels'] = json_encode($labels, self::REQUIRED_FLAGS);
		$this->vars['data'] = json_encode($data, self::REQUIRED_FLAGS);
		$this->vars['content'] = 'academic/by_end_date';
		$this->load->view('backend/index', $this->vars);
	}
}
