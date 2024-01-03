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

class M_student_groups extends CI_Model {

	/**
	 * Primary key
	 * @var String
	 */
	public static $pk = 'id';

	/**
	 * Table
	 * @var String
	 */
	public static $table = 'class_group_students';

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
			, x4.academic_year
			, CONCAT(x5.class_group, IF((x6.major_short_name <> ''), CONCAT(' ', x6.major_short_name),''), IF((x5.sub_class_group <> ''),CONCAT(' - ', x5.sub_class_group),'')) AS class_name
			, COALESCE(x2.identity_number, '-') AS identity_number
			, x2.full_name
			, x2.birth_place
			, x2.birth_date
			, IF(x2.gender = 'M', 'L', 'P') AS gender
			, x1.is_class_manager
			, x1.is_deleted
		");
		$this->db->join('students x2', 'x1.student_id = x2.id', 'LEFT');
		$this->db->join('class_group_settings x3', 'x1.class_group_setting_id = x3.id', 'LEFT');
		$this->db->join('academic_years x4', 'x3.academic_year_id = x4.id', 'LEFT');
		$this->db->join('class_groups x5', 'x3.class_group_id = x5.id', 'LEFT');
		$this->db->join('majors x6', 'x5.major_id = x6.id', 'LEFT');
		$this->db->where('x2.is_student', 'true');
		$this->db->where('x2.is_alumni', 'false');
		$this->db->where('x2.is_prospective_student', 'false');
		if ( ! empty($keyword) ) {
			$this->db->like('x4.academic_year', $keyword);
			$this->db->or_like('x2.identity_number', $keyword);
			$this->db->or_like('x2.full_name', $keyword);
			$this->db->or_like('x2.birth_place', $keyword);
			$this->db->or_like('x2.birth_date', $keyword);
			$this->db->or_like('x2.gender', $keyword);
			$this->db->or_like("CONCAT(x5.class_group, IF((x6.major_short_name <> ''), CONCAT(' ', x6.major_short_name),''), IF((x5.sub_class_group <> ''), CONCAT(' - ', x5.sub_class_group),''))", $keyword);
		}
		$this->db->order_by('x4.academic_year', 'ASC');
		$this->db->order_by('x5.class_group', 'ASC');
		$this->db->order_by('x5.major_id', 'ASC');
		$this->db->order_by('x5.sub_class_group', 'ASC');
		$this->db->order_by('x2.full_name', 'ASC');
		if ( $return_type == 'count' ) return $this->db->count_all_results(self::$table . ' x1');
		if ( $limit > 0 ) $this->db->limit($limit, $offset);
		return $this->db->get(self::$table . ' x1');
	}

	/**
	 * Set Class President / Ketua Kelas
	 * @param Integer $id
	 * @param Array $dataset
	 * @return Boolean
	 */
	public function set_class_president($id, $dataset) {
		$class_group_setting_id = 0;
		$query = $this->model->RowObject('id', $id, 'class_group_students');
		if (is_object($query)) {
			$class_group_setting_id = $query->class_group_setting_id;
		}
		if ($class_group_setting_id > 0) {
			$this->db->trans_start();
			if (filter_var((string) $dataset['is_class_manager'], FILTER_VALIDATE_BOOLEAN)) {
				$this->db
					->where('class_group_setting_id', $class_group_setting_id)
					->update(self::$table, ['is_class_manager' => 'false']);
			}
			$this->db
				->where(self::$pk, $id)
				->update(self::$table, $dataset);
			$this->db->trans_complete();
			return $this->db->trans_status();
		}
		return FALSE;
	}
}
