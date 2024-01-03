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

class M_class_group_settings extends CI_Model {

	/**
	 * Primary key
	 * @var String
	 */
	public static $pk = 'id';

	/**
	 * Table
	 * @var String
	 */
	public static $table = 'class_group_settings';

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
			, x2.academic_year
			, CONCAT(x3.class_group, IF((x4.major_short_name <> ''), CONCAT(' ',x4.major_short_name),''),IF((x3.sub_class_group <> ''),CONCAT(' - ',x3.sub_class_group),'')) AS class_name
			, CONCAT(x5.nik, ' - ', x5.full_name) AS employee_name
			, COUNT(x6.class_group_setting_id) AS total
			, x1.is_deleted
		");
		$this->db->join('academic_years x2', 'x1.academic_year_id = x2.id', 'LEFT');
		$this->db->join('class_groups x3', 'x1.class_group_id = x3.id', 'LEFT');
		$this->db->join('majors x4', 'x3.major_id = x4.id', 'LEFT');
		$this->db->join('employees x5', 'x1.employee_id = x5.id', 'LEFT');
		$this->db->join('class_group_students x6', 'x1.id = x6.class_group_setting_id', 'LEFT');
		if ( ! empty($keyword) ) {
			$this->db->like("x2.academic_year", $keyword);
			$this->db->or_like("CONCAT(x3.class_group, IF((x4.major_short_name <> ''), CONCAT(' ',x4.major_short_name),''),IF((x3.sub_class_group <> ''),CONCAT(' - ',x3.sub_class_group),''))", $keyword);
			$this->db->or_like("x5.nik", $keyword);
			$this->db->or_like("x5.full_name", $keyword);
		}
		$this->db->group_by('x1.id, x1.academic_year_id, x1.class_group_id');
		$this->db->order_by('x2.academic_year', 'DESC');
		$this->db->order_by('x3.class_group', 'ASC');
		$this->db->order_by('x3.major_id', 'ASC');
		$this->db->order_by('x3.sub_class_group', 'ASC');
		if ( $return_type == 'count' ) return $this->db->count_all_results(self::$table . ' x1');
		if ( $limit > 0 ) $this->db->limit($limit, $offset);
		return $this->db->get(self::$table . ' x1');
	}
}
