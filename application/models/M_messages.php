<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CMS Sekolahku | CMS (Content Management System) dan PPDB/PMB Online GRATIS
 * untuk sekolah SD/Sederajat, SMP/Sederajat, SMA/Sederajat, dan Perguruan Tinggi
 * @version    2.4.13
 * @author     Anton Sofyan | https://facebook.com/antonsofyan | 4ntonsofyan@gmail.com | 0857 5988 8922
 * @copyright  (c) 2014-2021
 * @link       https://sekolahku.web.id
 *
 * PERINGATAN :
 * 1. TIDAK DIPERKENANKAN MENGGUNAKAN CMS INI TANPA SEIZIN DARI PIHAK PENGEMBANG APLIKASI.
 * 2. TIDAK DIPERKENANKAN MEMPERJUALBELIKAN APLIKASI INI TANPA SEIZIN DARI PIHAK PENGEMBANG APLIKASI.
 * 3. TIDAK DIPERKENANKAN MENGHAPUS KODE SUMBER APLIKASI.
 */

class M_messages extends CI_Model {

	/**
	 * Primary key
	 * @var String
	 */
	public static $pk = 'id';

	/**
	 * Table
	 * @var String
	 */
	public static $table = 'comments';

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Get Data
	 * @param String $keyword
	 * @param String $return_type
	 * @param Integer $limit
	 * @param Integer $offset
	 * @return Resource
	 */
	public function get_where($keyword = '', $return_type = 'count', $limit = 0, $offset = 0) {
		$this->db->select('id, comment_author, comment_email, comment_url, created_at, comment_content, is_deleted');
		$this->db->where('comment_type', 'message');
		if ( ! empty($keyword) ) {
			$this->db->group_start();
			$this->db->like('comment_author', $keyword);
			$this->db->or_like('comment_email', $keyword);
			$this->db->or_like('created_at', $keyword);
			$this->db->or_like('comment_content', $keyword);
			$this->db->group_end();
		}
		if ( $return_type == 'count' ) return $this->db->count_all_results(self::$table);
		if ( $limit > 0 ) $this->db->limit($limit, $offset);
		return $this->db->get(self::$table);
	}

	/**
	 * Reply Inbox
	 * @param Integer $id
	 * @param Array $dataset
	 * @return Integer
	 */
	public function reply($id, $dataset) {
		$this->model->update($id, self::$table, $dataset);
		$query = $this->model->RowObject(self::$pk, $id, self::$table);
		$config = array(
			'protocol' => 'smtp',
			'smtp_host' => __session('smtp_host'), 
			'smtp_port' => __session('smtp_port'),
			'smtp_user' => __session('smtp_user'),
			'smtp_pass' => __session('smtp_pass'),
			'smtp_crypto' => 'ssl', 
			'mailtype' => 'html',
			'smtp_timeout' => '4',
			'charset' => 'iso-8859-1',
			'wordwrap' => TRUE,
			"crlf" => "\r\n"
		);
		$this->load->library('email');
		$this->email->initialize($config);
		$this->email->set_newline("\r\n");
		$this->email->from(__session('email'), __session('school_name'));
		$this->email->to($query->comment_email);
		$this->email->subject($dataset['comment_subject']);
		$this->email->message($dataset['comment_reply']);
		if ($this->email->send()) {
			return true;
		} else {
			show_error($this->email->print_debugger());
		}
	}
}
