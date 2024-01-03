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

class M_registrants extends CI_Model {

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
	 * Admission Year
	 * @var Integer
	 */
	public $admission_year;

	/**
	 * Class Constructor
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$year = __session('admission_year');
		$this->admission_year = (NULL !== $year && $year > 0) ? $year : date('Y');
	}

	/**
	 * Display a listing of the resource.
	 * @param String $keyword
	 * @param Integer $limit
	 * @param Integer $offset
	 * @return Resource
	 */
	public function get_where($keyword = '', $return_type = 'count', $limit = 0, $offset = 0) {
		$fields = [
			"x1.id"
			, "x1.registration_number"
			, "x1.re_registration"
			, "x1.created_at"
			, "x1.full_name"
			, "x1.birth_date"
			, "IF(x1.gender = 'M', 'L', 'P') AS gender"
			, "x1.photo"
			, "x1.is_deleted"
		];
		if (__session('major_count') > 0) {
			array_push($fields, 'x2.major_name AS first_choice');
			array_push($fields, 'x3.major_name AS second_choice');
		}
		$this->db->select(implode(', ', $fields));
		if (__session('major_count') > 0) {
			$this->db->join('majors x2', 'x1.first_choice_id = x2.id', 'LEFT');
			$this->db->join('majors x3', 'x1.second_choice_id = x3.id', 'LEFT');
		}
		$this->db->where('x1.is_prospective_student', 'true');
		$this->db->where('LEFT(x1.registration_number, 4) = ', $this->admission_year);
		if ( ! empty($keyword) ) {
			$this->db->group_start();
			$this->db->like('x1.registration_number', $keyword);
			$this->db->or_like('x1.re_registration', $keyword);
			$this->db->or_like('x1.created_at', $keyword);
			if (__session('major_count') > 0) {
				$this->db->or_like('x2.major_name', $keyword);
				$this->db->or_like('x3.major_name', $keyword);
			}
			$this->db->or_like('x1.full_name', $keyword);
			$this->db->or_like('x1.gender', $keyword);
			$this->db->group_end();
		}
		if ( $return_type == 'count' ) return $this->db->count_all_results(self::$table . ' x1');
		if ( $limit > 0 ) $this->db->limit($limit, $offset);
		return $this->db->get(self::$table . ' x1');
	}

	/**
	 * insert
	 * @param Int $selection_result
	 * @param Array $registration_number
	 * @return Boolean
	 */
	public function insert($selection_result, array $registration_number = []) {
		$approved = 0;
		$unapproved = 0;
		$error = 0;
		foreach($registration_number as $reg_number) {
			if ($selection_result != 'unapproved') {
				if ($this->check_quota($reg_number)) {
					$query = $this->db
						->where('registration_number', $reg_number)
						->update(self::$table, ['selection_result' => $selection_result]);
					$query ? $approved++ : $error++;
				} else {
					$unapproved++;
				}
			} else {
				$query = $this->db
					->where('registration_number', $reg_number)
					->update(self::$table, ['selection_result' => $selection_result]);
				$query ? $approved++ : $error++;
			}
		}
		$response = 'Sukses : '.$approved. ' SQL Error : '. $error.', Gagal : ' . $unapproved;
		return $response;
	}

	/**
	 * check_quota
	 * @access 	private
	 * @param String
	 * @return Boolean
	 */
	private function check_quota($registration_number) {
		// Get First Choice
		if (__session('major_count') > 0) {
			$student = $this->db
				->select('first_choice_id')
				->where('registration_number', $registration_number)
				->get(self::$table)
				->row();
		}

		// Set Default Quota
		$quota = 0;
		// Get Quota
		$this->db->select('quota');
		$this->db->where('year', $this->admission_year);
		// If SMK or PT
		if (__session('major_count') > 0) {
			$this->db->where('major_id', $student->first_choice_id);
		}
		$this->db->limit(1);
		$query = $this->db->get('admission_quotas');
		if ($query->num_rows() === 1) {
			$result = $query->row();
			$quota = $result->quota;
		}

		// Get Approved Students
		$approved = $this->db
			->where('is_prospective_student', 'true')
			->where('selection_result IS NOT NULL')
			->where('selection_result !=', 'unapproved')
			->where('LEFT(registration_number, 4) = ', $this->admission_year)
			->count_all_results(self::$table);
		return $quota > $approved;
	}

	/**
	 * Generate Registration Number
	 * @return Boolean
	 */
	public function registration_number() {
		$admission_year = $this->admission_year;
		$query = $this->db->query("
			SELECT MAX(RIGHT(registration_number, 6)) AS max_number
			FROM students
			WHERE is_prospective_student='true'
			AND LEFT(registration_number, 4) = ?
		", [$admission_year]);
		$registration_number = "000001";
		if ($query->num_rows() === 1) {
			$data = $query->row();
			$number = ((int) $data->max_number) + 1;
			$registration_number = sprintf("%06s", $number);
		}
		return $admission_year . $registration_number;
	}

	/**
	 * Selection Result
	 * @param String $registration_number
	 * @param String $birth_date
	 * @return Array
	 */
	public function selection_result($registration_number, $birth_date) {
		$query = $this->db
			->where('registration_number', $registration_number)
			->where('birth_date', $birth_date)
			->get(self::$table);
		if ($query->num_rows() === 1) {
			$result = $query->row();
			if (is_null($result->selection_result)) {
				$response['status'] = 'info';
				$response['message'] = 'Proses seleksi belum dimulai';
			}
			if ($result->selection_result === 'unapproved') {
				$response['status'] = 'warning';
				$response['message'] = 'Anda Tidak Lolos Seleksi Penerimaan Peserta Didik Baru '.__session('school_name');
			} else {
				$response['status'] = 'success';
				if (__session('major_count') > 0) {
					$majors = $this->model->RowObject('id', $result->selection_result, 'majors');
					$response['message'] = 'Anda Lolos Seleksi Penerimaan Peserta Didik Baru dan diterima di ' . __session('_major') . ' ' . $majors->major_name . ' ' . __session('school_name');
				} else {
					$response['message'] = 'Anda Lolos Seleksi Penerimaan Peserta Didik Baru '.__session('school_name');
				}
			}
		} else {
			$response['status'] = 'warning';
			$response['message'] = 'Data Anda tidak terdaftar pada database kami';
		}
		return $response;
	}

	/**
	 * Find Registrant
	 * @param String $registration_number
	 * @param String $birth_date
	 * @return Array
	 */
	public function find_registrant($registration_number, $birth_date) {
		$this->db->select("
			x1.id
		  , IF(x1.is_transfer='true', 'Pindahan', 'Baru') AS is_transfer
		  , x1.registration_number
		  , x1.created_at
		  , x2.major_name AS first_choice
		  , x3.major_name AS second_choice
		  , x1.prev_school_name
		  , x1.prev_school_address
		  , x1.full_name
		  , IF(x1.gender = 'M', 'Laki-laki', 'Perempuan') AS gender
		  , x1.nisn
		  , x1.nik
		  , x1.birth_place
		  , x1.birth_date
		  , x4.option_name AS religion
		  , x5.option_name AS special_needs
		  , x6.option_name AS admission_type
		  , x1.street_address
		  , x1.rt
		  , x1.rw
		  , x1.sub_district
		  , x1.district
		  , x1.sub_village
		  , x1.village
		  , x1.postal_code
		  , x1.email
		");
		$this->db->join('majors x2', 'x1.first_choice_id = x2.id', 'LEFT');
		$this->db->join('majors x3', 'x1.second_choice_id = x3.id', 'LEFT');
		$this->db->join('options x4', 'x1.religion_id = x4.id', 'LEFT');
		$this->db->join('options x5', 'x1.special_need_id = x5.id', 'LEFT');
		$this->db->join('options x6', 'x1.admission_type_id = x6.id', 'LEFT');
		$this->db->where('x1.registration_number', $registration_number);
		$this->db->where('x1.birth_date', $birth_date);
		return $this->db->get(self::$table.' x1')->row_array();
	}

	/**
	 * Is Valid Registrant
	 * @param String $registration_number
	 * @param String $birth_date
	 * @return Boolean
	 */
	public function is_valid_registrant($registration_number, $birth_date) {
		$this->db->where('registration_number', $registration_number);
		$this->db->where('birth_date', $birth_date);
		$count = $this->db->count_all_results(self::$table);
		return $count > 0;
	}

	/**
	 * Admission Reports
	 * @return Resource
	 */
	public function admission_reports() {
		$this->load->model('m_students');
		$query = $this->m_students->student_query();
		$query .= "
		AND x1.is_prospective_student='true'
		ORDER BY x1.registration_number, x1.full_name ASC";
		return $this->db->query($query);
	}
}
