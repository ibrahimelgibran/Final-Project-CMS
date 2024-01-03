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

class M_helper extends CI_Model {

	/**
	 * Primary key
	 * @var String
	 */
	public static $pk = 'id';

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 *  Insert
	 * @param String $table
	 * @param Array $dataset
	 * @return Boolean
	 */
	public function insert($table, array $dataset) {
		$this->db->trans_start();
		$this->db->insert($table, $dataset);
		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	/**
	 * Update
	 * @param Integer $id
	 * @param String $table
	 * @param Array $dataset
	 * @return Boolean
	 */
	public function update($id, $table, array $dataset) {
		$this->db->trans_start();
		$this->db->where(self::$pk, $id)->update($table, $dataset);
		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	/**
	 *  Update Or Insert
	 * @param Integer $id
	 * @param String $table
	 * @param Array $dataset
	 * @return Boolean
	 */
	public function upsert($id, $table, array $dataset) {
		$this->db->trans_start();
		_isNaturalNumber( $id ) ?
		 	$this->db->where(self::$pk, $id)->update($table, $dataset) :
			$this->db->insert($table, $dataset);
		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	/**
	* Delete Permanently
	* @param String $key
   * @param String $value
   * @param String $table
	 * @return Boolean
	 */
	public function delete_permanently($key, $value, $table) {
		$this->db->trans_start();
		$this->db->where_in($key, $value)->delete($table);
		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	/**
	 * Delete
	 * @param Array $ids
	 * @param String $table
	 * @return Boolean
	 */
	public function delete(array $ids, $table) {
		$this->db->trans_start();
		$this->db->where_in(self::$pk, $ids)
			->update($table, [
				'is_deleted' => 'true',
				'deleted_by' => __session('user_id'),
				'deleted_at' => date('Y-m-d H:i:s')
			]
		);
		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	/**
	 * Truncate Table
	 * @param String $table
	 * @return Boolean
	 */
	public function truncate($table) {
		$this->db->trans_start();
		$this->db->truncate($table);
		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	/**
	* Restore
	 * @param Array $ids
	 * @param String $table
	 * @return Boolean
	 */
	public function restore(array $ids, $table) {
		$this->db->trans_start();
		$this->db->where_in(self::$pk, $ids)
			->update($table, [
				'is_deleted' => 'false',
				'restored_by' => __session('user_id'),
				'restored_at' => date('Y-m-d H:i:s')
			]
		);
		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	/**
	* Check value if exists
	* @param String $key
	* @param String $value
	* @param String $table
	 * @return Boolean
	 */
	public function is_exists($key, $value, $table) {
		$count = $this->db
			->where($key, $value)
			->count_all_results($table);
		return $count > 0;
	}

	/**
	 * Row Object
	 * @param String $key
	 * @param String $value
	 * @param String $table
	 * @return Object
	 */
	public function RowObject($key, $value, $table) {
		return $this->db
			->where($key, $value)
			->get($table)
			->row();
	}

	/**
	 * Results Object
	 * @param String $table
	 * @return Array of Object
	 */
	public function ResultsObject($table) {
		return $this->db->get($table)->result();
	}

	/**
	 * Row Array
	 * @param String $table
	 * @param String $key
	 * @param String $value
	 * @return Array
	 */
	public function RowArray($table, $key, $value) {
		return $this->db
			->where($key, $value)
			->get($table)
			->row_array();
	}

	/**
	 * Results Array
	 * @param String $table
	 * @return Array of Array
	 */
	public function ResultsArray($table) {
		return $this->db->get($table)->result_array();
	}

	/**
	 * Check if NIK exists
	 * @param String $nik
	 * @param String $table
	 * @param Integer $id
	 * @return Boolean
	 */
	public function nik_exists($nik, $table = '', $id = 0) {
		// Students
		$this->db->where('nik', $nik);
		if ( $table == 'students' && _isNaturalNumber($id) ) $this->db->where('id !=', _toInteger($id));
		$this->db->where('is_deleted', 'false');
		$student = $this->db->count_all_results('students');
		// Employees
		$this->db->where('nik', $nik);
		if ( $table == 'employees' && _isNaturalNumber($id) ) $this->db->where('id !=', _toInteger($id));
		$this->db->where('is_deleted', 'false');
		$employee = $this->db->count_all_results('employees');
		if ($student > 0 || $employee > 0) return true;
		return false;
	}

	/**
	 * Clear Expired Session and Login Attemps
	 * @return Void
	 */
	public function clear_expired_session() {
		$this->db->query("DELETE FROM `_sessions` WHERE DATE_FORMAT(FROM_UNIXTIME(timestamp), '%Y-%m-%d') < CURRENT_DATE");
		$this->db->query("DELETE FROM `login_attempts` WHERE DATE_FORMAT(created_at, '%Y-%m-%d') < CURRENT_DATE");
	}

	/**
	 * Get Admission Year
	 * @param Integer $admission_year_id
	 * @return Integer
	 */
	public function get_admission_year($admission_year_id = 0) {
		$admission_year = date('Y');
		$query = $this->db
			->select('academic_year')
			->where('id', $admission_year_id)
			->get('academic_years');
		if ($query->num_rows() === 1) {
			$res = $query->row();
			$admission_year = substr($res->academic_year, 0, 4);
		}
		return $admission_year;
	}
}
