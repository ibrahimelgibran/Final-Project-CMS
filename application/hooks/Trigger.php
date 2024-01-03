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

class Trigger {

   /**
    * Reference to CodeIgniter instance
    *
    * @var object
    */
    private $CI;

	/**
     * Class constructor
     */
    public function __construct() {
		$this->CI = &get_instance();
      $this->CI->load->model([
         'm_settings'
         , 'm_themes'
         , 'm_admission_phases'
         , 'm_academic_years'
         , 'm_majors'
      ]);
	}

	/**
     * Set Session Here
     */
	public function index() {
      $session_data = [];
		$setting_access_group = ! $this->CI->auth->hasLogin() ? 'public' : 'private';
		$settings = $this->CI->m_settings->get_setting_values($setting_access_group);
		foreach($settings as $setting_variable => $setting_value) {
			$session_value = $setting_value;
			if ($setting_variable == 'school_level') {
        		$options = $this->CI->model->RowObject('id', $setting_value, 'options');
        		$session_value = substr($options->option_name, 0, 1); // ex : 1 - SD / Sekolah Dasar <-- ambil digit pertama sebagai kode jenjang sekolah
			}
			$session_data[ $setting_variable ] = $session_value;
		}

		$school_level = isset($session_data['school_level']) ? (int) $session_data['school_level'] : 0;
		// Academic Year Name
		$session_data['_academic_year'] = $school_level >= 5 ? 'Tahun Akademik' : 'Tahun Pelajaran';
		// Student Name
		$session_data['_student'] = $school_level >= 5 ? 'Mahasiswa' : 'Peserta Didik';
		// Identity Number Name
		$session_data['_identity_number'] = $school_level >= 5 ? 'NIM' : 'NIS';
      // Short Employee Name
		$session_data['_employee'] = $school_level >= 5 ? 'Staff dan Dosen' : 'GTK';
      // Long Employee Name
		$session_data['__employee'] = $school_level >= 5 ? 'Staf dan Dosen' : 'Guru dan Tenaga Kependidikan';
		// Subject Name
		$session_data['_subject'] = $school_level >= 5 ? 'Mata Kuliah' : 'Mata Pelajaran';
		// Admission NameMata Pelajara
		$session_data['_admission'] = $school_level >= 5 ? 'PMB' : 'PPDB';
		// Major Name
      switch ($school_level) {
			case 4:
				$session_data['_major'] = 'Program Keahlian';
				break;
			case 5:
			case 6:
			case 7:
				$session_data['_major'] = 'Program Studi';
				break;
			default:
				$session_data['_major'] = 'Jurusan';
				break;
		}
		// Headmaster Name
		switch ($school_level) {
			case 5:
				$session_data['_headmaster'] = 'Rektor';
				break;
			case 6:
				$session_data['_headmaster'] = 'Ketua';
				break;
			case 7:
				$session_data['_headmaster'] = 'Direktur';
				break;
			default:
				$session_data['_headmaster'] = 'Kepala Sekolah';
				break;
		}

		// Get Active Theme
		$session_data['theme'] = $this->CI->m_themes->get_active_themes();
		// Set Academic Year
		$academic_year = $this->CI->m_academic_years->get_active_academic_year();
		// Current Admission Year
		if (isset($academic_year['admission_semester_id'])) {
			$session_data['admission_semester_id'] = $academic_year['admission_semester_id'];
		}
		if (isset($academic_year['admission_semester'])) {
			$session_data['admission_semester'] = $academic_year['admission_semester'];
		}
		if (isset($academic_year['admission_year'])) {
			$session_data['admission_year'] = $academic_year['admission_year'];
		}
		// Current Academic Year
		if (isset($academic_year['current_academic_year_id'])) {
			$session_data['current_academic_year_id'] = $academic_year['current_academic_year_id'];
		}
		if (isset($academic_year['current_academic_year'])) {
			$session_data['current_academic_year'] = $academic_year['current_academic_year'];
		}
		if (isset($academic_year['current_academic_semester'])) {
			$session_data['current_academic_semester'] = $academic_year['current_academic_semester'];
		}
      // Gelombang Pendaftaran
		$admission_phase = $this->CI->m_admission_phases->get_current_phase();
		if (count($admission_phase) > 0) {
			$session_data['admission_phase_id'] = $admission_phase['id'];
			$session_data['admission_phase'] = $admission_phase['phase_name'];
			$session_data['admission_start_date'] = $admission_phase['phase_start_date'];
			$session_data['admission_end_date'] = $admission_phase['phase_end_date'];
		}

      // Get Active Majors
      $major_count = $this->CI->m_majors->major_count();
      $session_data['major_count'] = $major_count;

		// Set Session Data
		$this->CI->session->set_userdata($session_data);
	}
}
