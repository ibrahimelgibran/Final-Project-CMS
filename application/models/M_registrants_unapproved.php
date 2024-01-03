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

class M_registrants_unapproved extends CI_Model {

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
			'x1.id'
			, 'x1.registration_number'
			, 'x1.re_registration'
			, 'x1.created_at'
			, 'x1.full_name'
			, 'x1.birth_date'
			, 'x1.gender'
		];
		if (__session('major_count') > 0) {
			array_push($fields, 'x2.major_name AS selection_result');
		} else {
			array_push($fields, 'x1.selection_result');
		}
		$this->db->select(implode(', ', $fields));
		if (__session('major_count') > 0) {
			$this->db->join('majors x2', 'x1.selection_result = x2.id', 'LEFT');
		}
		$this->db->where('x1.is_alumni', 'false');
		$this->db->where('x1.is_prospective_student', 'true');
		$this->db->where('x1.selection_result', 'unapproved');
		$this->db->where('LEFT(x1.registration_number, 4) = ', $this->admission_year);
		if ( ! empty($keyword) ) {
			$this->db->group_start();
			$this->db->like('x1.registration_number', $keyword);
			if (__session('major_count') > 0) {
				$this->db->or_like('x2.major_name', $keyword);
			} else {
				$this->db->or_like('x1.selection_result', $keyword);
			}
			$this->db->or_like('x1.re_registration', $keyword);
			$this->db->or_like('x1.full_name', $keyword);
			$this->db->or_like('x1.gender', $keyword);
			$this->db->or_like('x1.birth_date', $keyword);
			$this->db->or_like('x1.created_at', $keyword);
			$this->db->group_end();
		}
		if ( $return_type == 'count' ) return $this->db->count_all_results(self::$table . ' x1');
		if ( $limit > 0 ) $this->db->limit($limit, $offset);
		return $this->db->get(self::$table . ' x1');
	}
}
