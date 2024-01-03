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

class Photos extends Admin_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model('m_photos');
		$this->pk = M_photos::$pk;
		$this->table = M_photos::$table;
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		$this->vars['title'] = 'Photo';
		$this->vars['media'] = $this->vars['albums'] = TRUE;
		$this->vars['content'] = 'media/photos';
		$this->load->view('backend/index', $this->vars);
	}

	/**
	 * Pagination
	 * @return Object
	 */
	public function pagination() {
		if ($this->input->is_ajax_request()) {
			$keyword = trim($this->input->post('keyword', true));
			$page_number = _toInteger($this->input->post('page_number', true));
			$limit = _toInteger($this->input->post('per_page', true));
			$offset = ($page_number * $limit);
			$query = $this->m_photos->get_where($keyword, 'rows', $limit, $offset);
			$total_rows = $this->m_photos->get_where($keyword);
			$total_page = $limit > 0 ? ceil(_toInteger($total_rows) / _toInteger($limit)) : 1;
			$this->vars['total_page'] = _toInteger($total_page);
			$this->vars['total_rows'] = _toInteger($total_rows);
			$this->vars['rows'] = $query->result();
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($this->vars, self::REQUIRED_FLAGS))
				->_display();
			exit;
		}
	}

	/**
	 * Deleted Permanently
	 */
	public function delete() {
		if ($this->input->is_ajax_request()) {
			$ids = explode(',', $this->input->post($this->pk));
			$success = 0;
			foreach($ids as $id) {
				$query = $this->model->RowObject($this->pk, $id, $this->table);
				if($this->m_photos->delete_permanently($id)) {
					@chmod(FCPATH.'media/albums/large/'.$query->photo_name, 0777);
					@chmod(FCPATH.'media/albums/medium/'.$query->photo_name, 0777);
					@chmod(FCPATH.'media/albums/thumbnail/'.$query->photo_name, 0777);
					@unlink(FCPATH.'media/albums/large/'.$query->photo_name);
					@unlink(FCPATH.'media/albums/medium/'.$query->photo_name);
					@unlink(FCPATH.'media/albums/thumbnail/'.$query->photo_name);
					$success++;
				}
			}
			$this->vars = [
	        	'action' => 'delete_permanently',
				'status' => 'info',
				'message' => $success.' record deleted successfully.'
			];

			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($this->vars, self::REQUIRED_FLAGS))
				->_display();
			exit;
		}
	}
}
