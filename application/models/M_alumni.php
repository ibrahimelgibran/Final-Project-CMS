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

class M_alumni extends CI_Model {

	/**
	 * Primary key
	 * @var String
	 */
	public static $pk = 'id';

	/**
	 * Table
	 * @var String
	 */
	public static $table = 'students';

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
			id
			, identity_number
			, full_name
			, gender
			, street_address
			, photo
			, is_alumni
			, COALESCE(start_date, '') start_date
			, end_date
			, is_deleted
		");
		$this->db->where_in('is_alumni', ['true', 'false', 'unverified']);
		if ( ! empty($keyword) ) {
			$this->db->group_start();
			$this->db->like('identity_number', $keyword);
			$this->db->or_like('full_name', $keyword);
			$this->db->or_like('gender', $keyword);
			$this->db->or_like('street_address', $keyword);
			$this->db->or_like('start_date', $keyword);
			$this->db->or_like('end_date', $keyword);
			$this->db->group_end();
		}
		if ( $return_type == 'count' ) return $this->db->count_all_results(self::$table);
		if ( $limit > 0 ) $this->db->limit($limit, $offset);
		return $this->db->get(self::$table);
	}

	/**
	 * Get Alumni
	 * @param Integer $limit
	 * @param Integer $offset
	 * @return Resource
	 */
	public function get_alumni($limit = 0, $offset = 0) {
		$this->db->select("
			id
			, identity_number
			, full_name
			, IF(gender = 'M', 'Laki-laki', 'Perempuan') as gender
			, birth_place
			, LEFT(start_date, 4) AS start_date
			, LEFT(end_date, 4) AS end_date
			, birth_date
			, photo
		");
		$this->db->where('is_deleted', 'false');
		$this->db->where('is_alumni', 'true');
		$this->db->order_by('end_date', 'ASC');
		$this->db->order_by('full_name', 'ASC');
		if ( $limit > 0 ) $this->db->limit($limit, $offset);
		return $this->db->get(self::$table);
	}

	/**
	 * Alumni Reports
	 * @return Resource
	 */
	public function alumni_reports() {
		$this->load->model('m_students');
		$query = $this->m_students->student_query();
		$query .= "
		AND x1.is_alumni='true'
		ORDER BY x1.full_name ASC";
		return $this->db->query($query);
	}

	/**
	 * Get Inactive Accounts
	 * @return Resource
	 */
	public function get_inactive_accounts() {
		$this->db->select('x1.id, x1.identity_number, x1.full_name, x1.email');
		$this->db->join('users x2', 'x1.id = x2.user_profile_id AND x2.user_type = "student"', 'LEFT');
		$this->db->where('x2.user_profile_id', NULL);
		$this->db->where('x1.is_alumni', 'true');
		$this->db->where('x1.is_deleted', 'false');
		return $this->db->get('students x1');
	}
}
