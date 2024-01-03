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

class M_employees extends CI_Model {

	/**
	 * Primary key
	 * @var String
	 */
	public static $pk = 'id';

	/**
	 * Table
	 * @var String
	 */
	public static $table = 'employees';

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
			, x1.nik
			, x1.full_name
			, x2.option_name AS employment_type
			, IF(x1.gender = 'M', 'L', 'P') AS gender
			, COALESCE(x1.birth_place, '') birth_place
			, x1.birth_date
			, x1.photo, x1.is_deleted
		");
		$this->db->join('options x2', 'x1.employment_type_id = x2.id', 'LEFT');
		if ( ! empty($keyword) ) {
			$this->db->like('x1.nik', $keyword);
			$this->db->or_like('x1.full_name', $keyword);
			$this->db->or_like('x1.gender', $keyword);
			$this->db->or_like('x1.birth_place', $keyword);
			$this->db->or_like('x1.birth_date', $keyword);
			$this->db->or_like('x2.option_name', $keyword);
		}
		if ( $return_type == 'count' ) return $this->db->count_all_results(self::$table . ' x1');
		if ( $limit > 0 ) $this->db->limit($limit, $offset);
		return $this->db->get(self::$table . ' x1');
	}

	/**
	 * Dropdown
	 * @return Array
	 */
	public function dropdown() {
		$query = $this->db
			->select('id, nik, full_name')
			->where('is_deleted', 'false')
			->order_by('full_name', 'ASC')
			->get(self::$table);
		$dataset = [];
		if ($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$dataset[$row->id] = $row->nik .' - '. $row->full_name;
			}
		}
		return $dataset;
	}

	/**
	 * Get Employment Type
	 * @param Integer $id
	 * @return String
	 */
	public function get_employment_type($id) {
		$query = $this->model->RowObject(self::$pk, $id, self::$table);
		if (is_object($query)) {
			$employment_type = $this->model->RowObject('id', $query->employment_type_id, 'options');
			if (is_object($employment_type)) {
				return $employment_type->option_name;
			}
			return NULL;
		}
		return NULL;
	}

	/**
	 * Get Inactive Accounts
	 * @return Resource
	 */
	public function get_inactive_accounts() {
		$this->db->select('x1.id, x1.nik, x1.full_name, x1.email');
		$this->db->join('users x2', 'x1.id = x2.user_profile_id AND x2.user_type = "employee"', 'LEFT');
		$this->db->where('x2.user_profile_id', NULL);
		$this->db->where('x1.is_deleted', 'false');
		return $this->db->get('employees x1');
	}

	/**
	 * Employee Query
	 * @return String
	 */
	public function employee_query() {
		return "
		SELECT x1.id
			, x1.assignment_letter_number
			, x1.assignment_letter_date
			, x1.assignment_start_date
			, x1.parent_school_status
			, x1.full_name
			, IF(x1.gender = 'M', 'L', 'P') AS gender
			, x1.nik
			, x1.guru_mapel
			, x1.nbm
			, x1.birth_place
			, x1.birth_date
			, x1.mother_name
			, x1.street_address
			, x1.rt
			, x1.rw
			, x1.sub_village
			, x1.village
			, x1.sub_district
			, x1.district
			, x1.postal_code
			, x2.option_name AS religion
			, x3.option_name AS marriage_status
			, x1.spouse_name
			, x4.option_name AS spouse_employment
			, x1.citizenship
			, x1.country
			, x1.npwp
			, x5.option_name AS employment_status
			, x1.nip
			, x1.niy
			, x1.nuptk
			, x6.option_name AS employment_type
			, x1.decree_appointment
			, x1.appointment_start_date
			, x7.option_name AS institution_lifter
			, x1.decree_cpns
			, x1.pns_start_date
			, x8.option_name AS 'rank'
			, x9.option_name AS salary_source
			, x1.headmaster_license
			, x10.option_name AS laboratory_skill
			, x11.option_name AS special_need
			, x1.braille_skills
			, x1.sign_language_skills
			, x1.phone
			, x1.mobile_phone
			, x1.email
			, x1.photo
		FROM employees x1
		LEFT JOIN options x2 ON x1.religion_id = x2.id
		LEFT JOIN options x3 ON x1.marriage_status_id = x3.id
		LEFT JOIN options x4 ON x1.spouse_employment_id = x4.id
		LEFT JOIN options x5 ON x1.employment_status_id = x5.id
		LEFT JOIN options x6 ON x1.employment_type_id = x6.id
		LEFT JOIN options x7 ON x1.institution_lifter_id = x7.id
		LEFT JOIN options x8 ON x1.rank_id = x8.id
		LEFT JOIN options x9 ON x1.salary_source_id = x9.id
		LEFT JOIN options x10 ON x1.laboratory_skill_id = x10.id
		LEFT JOIN options x11 ON x1.special_need_id = x11.id
		WHERE 1 = 1";
	}

	/**
	 * Employee Reports
	 * @return Resource
	 */
	public function employee_reports() {
		$query = $this->employee_query();
		$query .= "
		ORDER BY x1.full_name ASC";
		return $this->db->query($query);
	}

	/**
	 * Profile
	 * @param Integer $id
	 * @return Resource
	 */
	public function profile($id) {
		$query_string = $this->employee_query();
		$query_string .= '
			AND x1.id = ?
		';
		return $this->db->query($query_string, [_toInteger( $id )])->row();
	}

	/**
	 * Get Employees
	 * @param Integer $limit
	 * @param Integer $offset
	 * @return Resource
	 */
	public function get_employees($limit = 0, $offset = 0) {
		$this->db->select("
			x1.id
		  , x1.nik
		  , x1.full_name
		  , x1.guru_mapel
		  , x1.nbm
		  , x1.motto
		  , IF(x1.gender = 'M', 'Laki-laki', 'Perempuan') as gender
		  , x1.birth_place
		  , x1.birth_date
		  , x1.photo
		  , x2.option_name AS employment_type
		");
		$this->db->join('options x2', 'x1.employment_type_id = x2.id', 'LEFT');
		$this->db->where('x1.is_deleted', 'false');
		$this->db->order_by('x1.full_name', 'ASC');
		if ( $limit > 0 ) $this->db->limit($limit, $offset);
		return $this->db->get(self::$table . ' x1');
	}

	/**
	 * Check if nik exists
	 * @param String $nik
	 * @param Integer $id
	 * @return Boolean
	 */
	public function nik_exists( $nik, $id = 0 ) {
		$this->db->where('nik', $nik);
		if ( _isNaturalNumber($id) ) $this->db->where('id <>', _toInteger($id));
		$this->db->where('is_deleted', 'false');
		$count = $this->db->count_all_results(self::$table);
		return $count > 0;
	}

	/**
	 * Check if email exists
	 * @param String $email
	 * @param Integer $id
	 * @return Boolean
	 */
	public function email_exists( $email, $id = 0 ) {
		$this->db->where('email', $email);
		if ( _isNaturalNumber($id) ) $this->db->where('id <>', _toInteger($id));
		$this->db->where('is_deleted', 'false');
		$count = $this->db->count_all_results(self::$table);
		return $count > 0;
	}
}
