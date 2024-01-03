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
	 * Get Latest Posts
	 * @param Integer $limit
	 * @return Resource
	 */
	public function get_latest_posts($limit = 0) {
		$this->db->select('
			x1.id
			, x1.post_title
			, x1.created_at
			, x1.post_content
			, x1.post_image
			, x1.post_slug
			, x1.post_counter
			, x2.user_full_name AS post_author
		');
		$this->db->join('users x2', 'x1.post_author = x2.id', 'LEFT');
		$this->db->where('x1.post_type', 'post');
		$this->db->where('x1.post_status', 'publish');
		$this->db->where('x1.is_deleted', 'false');
		if ( ! $this->auth->hasLogin() ) {
			$this->db->where('x1.post_visibility', 'public');
		}
		$this->db->order_by('x1.created_at', 'DESC');
		if ( $limit > 0 ) $this->db->limit($limit);
		return $this->db->get(self::$table . ' x1');
	}

	/**
	 * Get Popular Posts
	 * @param Integer $limit
	 * @return Resource
	 */
	public function get_popular_posts($limit = 0) {
		$this->db->select('
			x1.id
			, x1.post_title
			, x1.created_at
			, x1.post_content
			, x1.post_image
			, x1.post_slug
			, x1.post_counter
			, x2.user_full_name AS post_author
		');
		$this->db->join('users x2', 'x1.post_author = x2.id', 'LEFT');
		$this->db->where('x1.post_type', 'post');
		$this->db->where('x1.post_status', 'publish');
		$this->db->where('x1.is_deleted', 'false');
		if ( ! $this->auth->hasLogin() ) {
			$this->db->where('x1.post_visibility', 'public');
		}
		$this->db->order_by('x1.post_counter', 'DESC');
		if ( $limit > 0 ) $this->db->limit($limit);
		return $this->db->get(self::$table . ' x1');
	}

	/**
	 * Get Most Commented
	 * @param Integer $limit
	 * @return Resource
	 */
	public function get_most_commented($limit = 0) {
		$this->db->select('
			x1.id
		  , x1.post_title
		  , x1.created_at
		  , x1.post_content
		  , x1.post_image
		  , x1.post_slug
		  , x1.post_counter
		  , x2.user_full_name AS post_author
			, COUNT(x3.id) AS total_comment
		');
		$this->db->join('users x2', 'x1.post_author = x2.id', 'LEFT');
		$this->db->join('comments x3', 'x1.id = x3.comment_post_id AND x3.comment_type = "post"', 'LEFT');
		$this->db->where('x1.post_type', 'post');
		$this->db->where('x1.post_status', 'publish');
		$this->db->where('x1.is_deleted', 'false');
		if ( ! $this->auth->hasLogin() ) {
			$this->db->where('x1.post_visibility', 'public');
		}
		$this->db->group_by(range(1, 8));
		$this->db->order_by('9', 'DESC');
		if ( $limit > 0 ) $this->db->limit($limit);
		$this->db->having('COUNT(x3.id) > 0');
		return $this->db->get(self::$table . ' x1');
	}

	/**
	 * Get Random Posts
	 * @param Integer $limit
	 * @return Resource
	 */
	public function get_random_posts($limit = 0) {
		$this->db->select('
			x1.id
			, x1.post_title
			, x1.created_at
			, x1.post_content
			, x1.post_image
			, x1.post_slug
			, x1.post_counter
			, x2.user_full_name AS post_author
		');
		$this->db->join('users x2', 'x1.post_author = x2.id', 'LEFT');
		$this->db->where('x1.post_type', 'post');
		$this->db->where('x1.post_status', 'publish');
		$this->db->where('x1.is_deleted', 'false');
		if ( ! $this->auth->hasLogin() ) {
			$this->db->where('x1.post_visibility', 'public');
		}
		$this->db->order_by('RAND()');
		if ( $limit > 0 ) $this->db->limit($limit);
		return $this->db->get(self::$table .' x1');
	}

	/**
	 * Get Related Posts
	 * @param String $post_categories
	 * @param Integer $id
	 * @return Resource
	 */
	public function get_related_posts($post_categories = '', $id = 0) {
		$post_categories = explode(',', $post_categories);
		$this->db->select('
			x1.id
			, x1.post_title
			, x1.created_at
			, x1.post_content
			, x1.post_image
			, x1.post_slug
			, x1.post_counter
			, x2.user_full_name AS post_author
		');
		$this->db->join('users x2', 'x1.post_author = x2.id', 'LEFT');
		$this->db->where('x1.post_type', 'post');
		$this->db->where('x1.post_status', 'publish');
		$this->db->where('x1.is_deleted', 'false');
		if ( ! $this->auth->hasLogin() ) {
			$this->db->where('x1.post_visibility', 'public');
		}
		if ($id > 0) $this->db->where('x1.id !=', $id);
		$i = 0;
		$this->db->group_start();
		foreach ($post_categories as $category) {
			if ($i == 0) {
				$this->db->like('x1.post_categories', $category);
			} else {
				$this->db->or_like('x1.post_categories', $category);
			}
			$i++;
		}
		$this->db->group_end();
		$this->db->order_by('x1.created_at', 'DESC');
		$this->db->limit((__session('post_related_count') > 0 ? __session('post_related_count') : 5));
		return $this->db->get(self::$table . ' x1');
	}

	/**
	 * Set Post Counter + 1
	 * @param Integer $id
	 * @return Boolean
	*/
	public function set_post_counter($id) {
		$this->db->set('post_counter', 'post_counter + 1', FALSE);
		$this->db->where(self::$pk, $id);
		return $this->db->update(self::$table);
	}

	/**
	* Search
	* @param String
	* @return Resource
	*/
	public function search($keyword) {
		$this->db->select('
			x1.id
			, x1.post_title
			, x1.created_at
			, x1.post_content
			, x1.post_image
			, x1.post_slug
			, x1.post_counter
			, x2.user_full_name AS post_author
		');
		$this->db->join('users x2', 'x1.post_author = x2.id', 'LEFT');
		$this->db->where('x1.post_status', 'publish');
		$this->db->where('x1.is_deleted', 'false');
		if ( ! $this->auth->hasLogin() ) {
			$this->db->where('x1.post_visibility', 'public');
		}
		$this->db->where_in('x1.post_type', ['post', 'page']);
		$this->db->group_start();
		$this->db->like('LOWER(x1.post_title)', strtolower($keyword));
		$this->db->group_end();
		$this->db->limit(20);
		return $this->db->get(self::$table .' x1');
	}

	/**
	 * Get Year From Posted Date
	 * @return Resource
	 */
	public function get_years() {
		$this->db->select('LEFT(created_at, 4) as year');
		$this->db->where('post_type', 'post');
		$this->db->where('is_deleted', 'false');
		$this->db->where('post_status', 'publish');
		if ( ! $this->auth->hasLogin() ) {
			$this->db->where('post_visibility', 'public');
		}
		$this->db->group_by('1');
		$this->db->order_by('1', 'DESC');
		return $this->db->get(self::$table);
	}

	/**
	 * Get Archives
	 * @param Integer $year
	 * @return Resource
	 */
	public function get_archives($year) {
		$this->db->select("
			SUBSTR(x1.created_at, 6, 2) AS `code`
			, MONTHNAME(x1.created_at) AS `month`
			, COUNT(*) AS `count`
		");
		$this->db->where('LEFT(x1.created_at, 4) = ', _isValidYear($year) ? $year : date('Y'));
		$this->db->where('x1.post_type', 'post');
		$this->db->where('x1.post_status', 'publish');
		if ( ! $this->auth->hasLogin() ) {
			$this->db->where('x1.post_visibility', 'public');
		}
		$this->db->where('x1.is_deleted', 'false');
		$this->db->group_by("1,2");
		$this->db->order_by('1', 'ASC');
		return $this->db->get(self::$table . ' x1');
	}

	/**
	 * Get Post by Year and Month
	 * @param String $year
	 * @param String $month
	 * @param Integer $limit
	 * @param Integer $offset
	 * @return Resource
	 */
	public function get_post_archives($year, $month, $limit = 0, $offset = 0) {
		$this->db->select('
			x1.id
			, x1.post_title
			, x1.created_at
			, x1.post_content
			, x1.post_image
			, x1.post_slug
			, x1.post_counter
			, x2.user_full_name AS post_author
		');
		$this->db->join('users x2', 'x1.post_author = x2.id', 'LEFT');
		$this->db->where('x1.post_type', 'post');
		$this->db->where('x1.post_status', 'publish');
		$this->db->where('x1.is_deleted', 'false');
		if ( ! $this->auth->hasLogin() ) {
			$this->db->where('x1.post_visibility', 'public');
		}
		$this->db->where('LEFT(x1.created_at, 4) = ', _isValidYear($year) ? $year : date('Y'))	;
		$this->db->where('SUBSTRING(x1.created_at, 6, 2) = ', _isValidMonth($month) ? $month : '01');
		$this->db->order_by('x1.created_at', 'DESC');
		if ( $limit > 0 ) $this->db->limit($limit, $offset);
		return $this->db->get(self::$table . ' x1');
	}

	/**
	 * Get Posts by Tags
	 * @param String $tag
	 * @param Integer $limit
	 * @param Integer $offset
	 * @return Resource
	 */
	public function get_post_tags($tag = '', $limit = 0, $offset = 0) {
		$this->db->select('
			x1.id
			, x1.post_title
			, x1.post_content
			, x1.created_at
			, x1.post_image
			, x1.post_slug
			, x2.user_full_name AS post_author
			, x1.post_counter
		');
		$this->db->join('users x2', 'x1.post_author = x2.id', 'LEFT');
		$this->db->where('x1.post_type', 'post');
		$this->db->where('x1.is_deleted', 'false');
		$this->db->where('x1.post_status', 'publish');
		if ( ! $this->auth->hasLogin() ) {
			$this->db->where('x1.post_visibility', 'public');
		}
		$this->db->group_start();
		$this->db->like("LOWER(REPLACE(x1.post_tags, ' ', '-'))", $tag);
		$this->db->group_end();
		$this->db->order_by('x1.created_at', 'DESC');
		if ( $limit > 0 ) $this->db->limit($limit, $offset);
		return $this->db->get(self::$table . ' x1');
	}

	/**
	 * Get Post by category
	 * @param String $category_slug
	 * @param Integer $limit
	 * @param Integer $offset
	 * @return Resource
	 */
	public function get_post_categories($category_slug = '', $limit = 0, $offset = 0) {
		$id = '+0+';
		$query = $this->db
			->select('id')
			->where('category_slug', $category_slug)
			->where('category_type', 'post')
			->where('is_deleted', 'false')
			->limit(1)
			->get('categories');
		if ($query->num_rows() == 1) {
			$res = $query->row();
			$id = '+' . $res->id . '+';
		}
		$this->db->select('
			x1.id
			, x1.post_title
			, x1.created_at
			, x1.post_content
			, x1.post_image
			, x1.post_slug
			, x1.post_counter
			, x2.user_full_name AS post_author
		');
		$this->db->join('users x2', 'x1.post_author = x2.id', 'LEFT');
		$this->db->where('x1.post_type', 'post');
		$this->db->where('x1.is_deleted', 'false');
		$this->db->where('x1.post_status', 'publish');
		if ( ! $this->auth->hasLogin() ) {
			$this->db->where('x1.post_visibility', 'public');
		}
		$this->db->group_start();
		$this->db->like('x1.post_categories', $id);
		$this->db->group_end();
		$this->db->order_by('x1.created_at', 'DESC');
		if ( $limit > 0 ) $this->db->limit($limit, $offset);
		return $this->db->get(self::$table . ' x1');
	}

	/**
	 * Get Another Pages
	 * @param Integer $id
	 * @param Integer $limit
	 * @return Resource
	 */
	public function get_another_pages( $id = 0, $limit = 0 ) {
		$this->db->select('
			x1.id
			, x1.post_title
			, x1.post_content
			, x1.created_at
			, x1.post_image
			, x1.post_slug
			, x2.user_full_name AS post_author
			, x1.post_counter
		');
		$this->db->join('users x2', 'x1.post_author = x2.id', 'LEFT');
		$this->db->where('x1.post_type', 'page');
		$this->db->where('x1.is_deleted', 'false');
		$this->db->where('x1.post_status', 'publish');
		if ( ! $this->auth->hasLogin() ) {
			$this->db->where('x1.post_visibility', 'public');
		}
		$this->db->where('x1.id !=', $id);
		$this->db->order_by('x1.created_at', 'DESC');
		if ( $limit > 0 ) $this->db->limit($limit);
		return $this->db->get(self::$table . ' x1');
	}

	/**
	 * Get Posts for RSS Feed
	 * @return Resource
	 */
	public function feed() {
		$this->db->select('id, post_title, created_at, post_content, post_slug');
		$this->db->where('post_type', 'post');
		$this->db->where('post_status', 'publish');
		$this->db->where('is_deleted', 'false');
		if ( ! $this->auth->hasLogin() ) {
			$this->db->where('post_visibility', 'public');
		}
		$this->db->order_by('created_at', 'DESC');
		$this->db->limit((__session('post_rss_count') > 0 ? __session('post_rss_count') : 10));
		return $this->db->get(self::$table);
	}

	/**
	 * Opening Speech | Kata Sambutan
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
}
