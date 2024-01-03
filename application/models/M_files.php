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

class M_files extends CI_Model {

	/**
	 * Primary key
	 * @var String
	 */
	public static $pk = 'id';

	/**
	 * Table
	 * @var String
	 */
	public static $table = 'files';

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
		$this->db->select('
			x1.id
			, x1.file_title
			, x1.file_name
			, x1.file_ext
			, x1.file_size
			, x2.category_name
			, x1.file_counter
			, x1.file_visibility
			, x1.is_deleted
		');
		$this->db->join('categories x2', 'x1.file_category_id = x2.id',  'LEFT');
		$this->db->where('x2.category_type', 'file');
		if ( ! empty($keyword) ) {
			$this->db->group_start();
			$this->db->like('x1.file_title', $keyword);
			$this->db->or_like('x1.file_name', $keyword);
			$this->db->or_like('x1.file_ext', $keyword);
			$this->db->or_like('x1.file_size', $keyword);
			$this->db->or_like('x2.category_name', $keyword);
			$this->db->or_like('x1.file_visibility', $keyword);
			$this->db->group_end();
		}
		if ( $return_type == 'count' ) return $this->db->count_all_results(self::$table . ' x1');
		if ( $limit > 0 ) $this->db->limit($limit, $offset);
		return $this->db->get(self::$table . ' x1');
	}

	/**
	 * Get Files
	 * @param String $slug
	 * @param Integer $limit
	 * @param Integer $offset
	 * @return Resource
	 */
	public function get_files($slug = '', $limit = 0, $offset = 0) {
		$this->db->select('
			x1.id
			, x1.file_title
			, x1.file_name
			, x1.file_ext
			, x1.file_size
			, x2.category_name
			, x1.file_counter
			, x1.file_visibility
		');
		$this->db->join("categories x2", "x1.file_category_id = x2.id AND x2.category_type = 'file'",  "LEFT");
		$this->db->where('x1.is_deleted', 'false');
		if (!empty($slug)) $this->db->where('x2.category_slug', $slug);
		if ( ! $this->auth->hasLogin() ) $this->db->where('x1.file_visibility', 'public');
		if ( $limit > 0 ) $this->db->limit($limit, $offset);
		return $this->db->get(self::$table . ' x1');
	}

	/**
	 * Set File Counter + 1
	 * @param Integer $id
	 * @return Void
	 */
	public function set_file_counter( $id ) {
		$this->db->set('file_counter', 'file_counter + 1', FALSE);
		$this->db->where('id', _toInteger($id));
		$this->db->update('files');
	}
}
