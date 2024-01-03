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

class M_posts extends CI_Model {

	/**
	 * Primary key
	 * @var String
	 */
	public static $pk = 'id';

	/**
	 * Table
	 * @var String
	 */
	public static $table = 'posts';

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
		$this->db->select("
			x1.id
			, x1.post_title
			, x2.user_full_name AS post_author
			, x1.post_status
			, x1.created_at
			, x1.is_deleted
		");
		$this->db->join('users x2', 'x1.post_author = x2.id', 'LEFT');
		$this->db->where('x1.post_type', 'post');
		if (in_array(__session('user_type'), ['student', 'employee'])) {
			$this->db->where('x1.post_author', __session('user_id'));
		}
		if ( ! empty($keyword) ) {
			$this->db->group_start();
			$this->db->like('x1.post_title', $keyword);
			$this->db->or_like('x2.user_full_name', $keyword);
			$this->db->or_like('x1.post_status', $keyword);
			$this->db->or_like('x1.created_at', $keyword);
			$this->db->group_end();
		}
		if ( $return_type == 'count' ) return $this->db->count_all_results(self::$table . ' x1');
		if ( $limit > 0 ) $this->db->limit($limit, $offset);
		return $this->db->get(self::$table . ' x1');
	}

	/**
	 * opening_speech | Sambutan Kepala Sekolah
	 * @return String
	 */
	public function get_opening_speech() {
		$query = $this->db
			->select('post_content')
			->where('post_type', 'opening_speech')
			->limit(1)
			->get(self::$table);
		if ($query->num_rows() === 1) {
			$result = $query->row();
			return $result->post_content;
		}
		return '';
	}

	/**
	 * Update Sambutan Kepala Sekolah
	 * @param Array $dataset
	 * @return Boolean
	 */
	public function opening_speech_update($dataset = []) {
		$count = $this->db->where('post_type', 'opening_speech')->count_all_results(self::$table);
		if ($count === 0) {
			return $this->db->insert(self::$table, $dataset);
		}
		return $this->db->where('post_type', 'opening_speech')->update(self::$table, $dataset);
	}
}
