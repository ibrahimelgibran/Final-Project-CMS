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

class M_academic_years extends CI_Model {

	/**
	 * Primary key
	 * @var String
	 */
	public static $pk = 'id';

	/**
	 * Table
	 * @var String
	 */
	public static $table = 'academic_years';

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
		$this->db->select('id, academic_year, semester, current_semester, admission_semester, is_deleted');
		if ( ! empty($keyword) ) $this->db->like('academic_year', $keyword);
		if ( $return_type == 'count' ) return $this->db->count_all_results(self::$table);
		if ( $limit > 0 ) $this->db->limit($limit, $offset);
		return $this->db->get(self::$table);
	}

	/**
	 * Get active admission semester ID
	 * @return Integer
	 */
	public function get_active_academic_year() {
		$dataset = [];
		$admission_semester = $this->db
			->select('id, academic_year')
			->where('admission_semester', 'true')
			->where('is_deleted', 'false')
			->order_by('academic_year', 'DESC')
			->limit(1)
			->get(self::$table);
		if ($admission_semester->num_rows() === 1) {
			$res = $admission_semester->row();
			$dataset['admission_semester_id'] = $res->id;
			$dataset['admission_semester'] = $res->academic_year;
			$dataset['admission_year'] = substr($res->academic_year, 0, 4);
		}

		$current_semester = $this->db
			->select('id, academic_year, semester')
			->where('current_semester', 'true')
			->where('is_deleted', 'false')
			->order_by('academic_year', 'DESC')
			->limit(1)
			->get(self::$table);
		if ($current_semester->num_rows() === 1) {
			$res = $current_semester->row();
			$dataset['current_academic_year_id'] = $res->id;
			$dataset['current_academic_year'] = $res->academic_year;
			$dataset['current_academic_semester'] = $res->semester;
		}
		return $dataset;
	}

	/**
	 * Dropdown
	 * @return Array
	 */
	public function dropdown($short = false) {
		$query = $this->db
			->select('id, academic_year')
			->where('is_deleted', 'false')
			->order_by('academic_year', 'ASC')
			->get(self::$table);
		$dataset = [];
		if ($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$dataset[$row->id] = ($short ? substr($row->academic_year, 0, 4) : $row->academic_year);
			}
		}
		return $dataset;
	}

	/**
	 * Deactivate Academic Years
	 * @param Integer
	 * @return Boolean
	 */
	public function deactivate_academic_years($id = 0, $field = 'current_semester') {
		if ($id > 0) $this->db->where(self::$pk . ' !=', $id);
		return $this->db->update(self::$table, [$field => 'false']);
	}
}
