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

class M_admission_selection_process extends CI_Model {

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
	 * @param Integer $admission_year_id
	 * @param Integer $admission_type_id
	 * @param Integer $major_id
	 * @param String $return_type
	 * @param Integer $limit
	 * @param Integer $offset
	 * @return Resource
	 */
	public function get_prospective_students($admission_year_id = 0, $admission_type_id = 0, $major_id = 0, $return_type = 'count', $limit = 0, $offset = 0) {
		// Define Admission Year
		$admission_year = $this->model->get_admission_year($admission_year_id);
		$fields = ['x1.id', 'x1.registration_number', 'x1.full_name'];
		if (__session('major_count') > 0) {
			array_push($fields, "COALESCE(x2.major_name, '') AS first_choice", "COALESCE(x3.major_name, '') AS second_choice");
		}
		$this->db->select(implode(', ', $fields));
		// If SMK or PT
		if (__session('major_count') > 0) {
			$this->db->join('majors x2', 'x1.first_choice_id = x2.id', 'LEFT');
			$this->db->join('majors x3', 'x1.second_choice_id = x3.id', 'LEFT');
		}
		$this->db->where('x1.is_deleted', 'false');
		$this->db->where('x1.is_prospective_student', 'true');
		$this->db->where('x1.re_registration', 'true');
		$this->db->where('x1.selection_result IS NULL');
		$this->db->where('x1.admission_type_id', $admission_type_id);
		$this->db->where('LEFT(x1.registration_number, 4) = ', $admission_year);
		if (__session('major_count') > 0) {
			$this->db->where('x1.first_choice_id', $major_id);
		}
		// Chek if min birth Date and max birth date isset
		$min_birth_date = __session('min_birth_date');
		$max_birth_date = __session('max_birth_date');
		if (NULL !== $min_birth_date && _isValidDate($min_birth_date) && NULL !== $max_birth_date && _isValidDate($max_birth_date)) {
			$birth_dates = array_date($min_birth_date, $max_birth_date);
			if (count($birth_dates) > 0) $this->db->where_in('x1.birth_date', $birth_dates);
		}
		if ( $return_type == 'count' ) return $this->db->count_all_results('students x1');
		if ( $limit > 0 ) $this->db->limit($limit, $offset);
		return $this->db->get('students x1');
	}

	/**
	 * Selection Process
	 * @param Integer $admission_year_id
	 * @param Integer $admission_type_id
	 * @param Any $selection_result
	 * @param Array $student_ids
	 * @return Boolean
	 */
	public function selection_process($admission_year_id = 0, $admission_type_id = 0, $selection_result, array $student_ids) {
		// Define Admission Year
		$admission_year = $this->model->get_admission_year($admission_year_id);
		// Default Quota
		$admission_quota = 0;
		// Check Quota
		$query = $this->db
			->select('quota')
			->where('academic_year_id', _toInteger($admission_year_id))
			->where('admission_type_id', _toInteger($admission_type_id))
			->where('major_id', _toInteger($selection_result))
			->get('admission_quotas');
		if ($query->num_rows() === 1) {
			$res = $query->row();
			$admission_quota = _toInteger($res->quota);
		}

		if ($selection_result != 'unapproved') {
			// Check Selection Result
			$approved = $this->db
				->where('LEFT(registration_number, 4)=', $admission_year)
				->where('admission_type_id', $admission_type_id)
				->where('is_prospective_student', 'true')
				->group_start()
				->where('selection_result', $selection_result)
				->or_where('selection_result', 'approved')
				->group_end()
				->count_all_results('students');
			if (($admission_quota - $approved) < count($student_ids)) {
				return [
					'status' => 'warning',
					'message' => 'Kuota pendaftaran tidak mencukupi. Silahkan periksa kembali pengaturan kuota pendaftaran.'
				];
			}
		}

		$this->db->set('selection_result', $selection_result);
		$this->db->set('updated_by', __session('user_id'));
		// If Approved / Diterima PPDB/PMB nya
		if ($selection_result != 'unapproved') {
			$student_status_id = get_option_id('student_status', 'aktif');
			$this->db->set('is_student', 'true');
			$this->db->set('student_status_id', _toInteger($student_status_id));
		// Unapproved / Tidak Diterima
		} else {
			$this->db->set('is_student', 'false');
			$this->db->set('student_status_id', NULL);
		}

		// update major_id
		if (_toInteger($selection_result) > 0) {
			$this->db->set('major_id', _toInteger($selection_result));
		}
		$this->db->where_in('id', $student_ids);
		$query = $this->db->update('students');
		return [
			'status' => $query ? 'success' : 'error',
			'message' => $query ? 'Proses seleksi sudah tersimpan' : 'Proses seleksi tidak tersimpan'
		];
	}
}
