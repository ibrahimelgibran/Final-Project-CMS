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

class M_tags extends CI_Model {

	/**
	 * Primary key
	 * @var String
	 */
	public static $pk = 'id';

	/**
	 * Table
	 * @var String
	 */
	public static $table = 'tags';

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
		$this->db->select('id, tag, slug, is_deleted');
		if ( ! empty($keyword) ) {
			$this->db->like('tag', $keyword);
			$this->db->or_like('slug', $keyword);
		}
		if ( $return_type == 'count' ) return $this->db->count_all_results(self::$table);
		if ( $limit > 0 ) $this->db->limit($limit, $offset);
		return $this->db->get(self::$table);
	}

	/**
	 * Get All Tags
	 * @param Integer $limit
	 * @param Boolean $random
	 * @return Resource
	 */
	public function get_tags($limit = 0, $random = FALSE) {
		$this->db->select('id, tag, slug');
		$this->db->where('is_deleted', 'false');
		if ( $limit > 0 ) $this->db->limit($limit);
		if ($random) $this->db->order_by('id','RANDOM');
		return $this->db->get(self::$table);
	}

	/**
	 * Create Tag from posts
	 * @param String $post_tags
	 * @return Void
	 */
	public function insert($post_tags) {
		$tags = explode(',', $post_tags);
		foreach ($tags as $tag) {
			$this->db->set('tag', trim($tag));
			$this->db->set('slug', slugify(trim($tag)));
			$this->db->set('created_at', date('Y-m-d H:i:s'));
			$this->db->set('created_by', __session('user_id'));
			$query = $this->db->get_compiled_insert(self::$table);
			$query = str_replace('INSERT INTO', 'INSERT IGNORE INTO', $query);
			$this->db->query($query);
		}
	}
}
