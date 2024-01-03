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

class M_post_comments extends CI_Model {

	/**
	 * Primary key
	 *
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
		$this->db->select('
			x1.id
			, x1.comment_author
			, x1.comment_email
			, x1.comment_url
			, x1.created_at
			, x1.comment_content
			, x1.comment_reply
			, x1.comment_status
			, x2.post_title
			, x2.id AS comment_post_id
			, x2.post_slug
			, x1.is_deleted'
		);
		$this->db->join('posts x2', 'x1.comment_post_id = x2.id', 'LEFT');
		$this->db->where('x1.comment_type', 'post');
		if ( ! empty($keyword) ) {
			$this->db->group_start();
			$this->db->like('x1.comment_author', $keyword);
			$this->db->or_like('x1.comment_email', $keyword);
			$this->db->or_like('x1.comment_url', $keyword);
			$this->db->or_like('x2.post_title', $keyword);
			$this->db->or_like('x1.created_at', $keyword);
			$this->db->or_like('x1.comment_content', $keyword);
			$this->db->group_end();
		}
		if ( $return_type == 'count' ) return $this->db->count_all_results(self::$table . ' x1');
		if ( $limit > 0 ) $this->db->limit($limit, $offset);
		return $this->db->get(self::$table . ' x1');
	}

	/**
	 * Get Recent Comments
	 * @param Integer $limit
	 * @return Resource
	 */
	public function get_recent_comments($limit = 0) {
		$this->db->select('
			x2.id
			, x1.comment_author
			, x1.comment_url
			, x1.comment_content
			, x1.comment_reply
			, x2.id AS comment_post_id
			, x2.post_title
			, x2.post_slug
			, x1.created_at
		');
		$this->db->join('posts x2', 'x1.comment_post_id = x2.id', 'LEFT');
		$this->db->where('x1.comment_type', 'post');
		$this->db->where('x1.comment_status', 'approved');
		$this->db->where('x1.is_deleted', 'false');
		$this->db->order_by('x1.created_at', 'DESC');
		if ( $limit > 0 ) $this->db->limit($limit);
		return $this->db->get(self::$table. ' x1');
	}

	/**
	 * Get more comments
	 * @param Integer $comment_post_id
	 * @param Integer $limit
	 * @param Integer $offset
	 * @return Resource
	 */
	public function get_post_comments($comment_post_id = 0, $limit = 0, $offset = 0) {
		$this->db->select('
			x1.id
			, x1.comment_author
			, x1.comment_url
			, x1.created_at
			, x1.comment_content
			, x1.comment_reply
		');
		$this->db->join('posts x2', 'x1.comment_post_id = x2.id', 'LEFT');
		$this->db->where('x1.comment_type', 'post');
		$this->db->where('x1.comment_status', 'approved');
		$this->db->where('x1.is_deleted', 'false');
		$this->db->where('x1.comment_post_id', $comment_post_id);
		$this->db->order_by('x1.created_at', 'DESC');
		if ( $limit > 0 ) $this->db->limit($limit, $offset);
		return $this->db->get(self::$table . ' x1');
	}
}
