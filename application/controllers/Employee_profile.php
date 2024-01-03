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

class Employee_profile extends Admin_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model('m_employees');
		$this->pk = M_employees::$pk;
		$this->table = M_employees::$table;
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		$this->load->helper('form');
		$id = NULL !== __session('user_profile_id') ? __session('user_profile_id') : 0;
		$this->vars['title'] = 'Biodata';
		$this->vars['employee_profile'] = TRUE;
		$this->vars['religions'] = ['' => 'Pilih :'] + get_options('religions', FALSE);
		$this->vars['marriage_status'] = ['' => 'Pilih :'] + get_options('marriage_status', FALSE);
		$this->vars['employment_status'] = ['' => 'Pilih :'] + get_options('employment_status', FALSE);
		$this->vars['employments'] = ['' => 'Pilih :'] + get_options('employments', FALSE);
		$this->vars['employment_types'] = ['' => 'Pilih :'] + get_options('employment_types', FALSE);
		$this->vars['institution_lifters'] = ['' => 'Pilih :'] + get_options('institution_lifters', FALSE);
		$this->vars['salary_sources'] = ['' => 'Pilih :'] + get_options('salary_sources', FALSE);
		$this->vars['laboratory_skills'] = ['' => 'Pilih :'] + get_options('laboratory_skills', FALSE);
		$this->vars['special_needs'] = ['' => 'Pilih :'] + get_options('special_needs', FALSE);
		$this->vars['ranks'] = ['' => 'Pilih :'] + get_options('ranks', FALSE);
		$this->vars['query'] = $this->model->RowObject('id', $id, $this->table);
		$this->vars['content'] = 'employee_profile';
		$this->load->view('backend/index', $this->vars);
	}

	/**
	 * save
	 * @return Object
	 */
	public function save() {
		if ($this->input->is_ajax_request()) {
			$id = NULL !== __session('user_profile_id') ? __session('user_profile_id') : 0;
			if (_isNaturalNumber( $id )) {
				if ($this->validation( $id )) {
					$dataset = $this->dataset();
					$dataset['updated_by'] = $id;
					$this->vars['status'] = $this->model->update($id, $this->table, $dataset) ? 'success' : 'error';
					$this->vars['message'] = $this->vars['status'] == 'success' ? 'updated' : 'not_updated';
					if ($this->vars['status'] == 'success') {
						$nik = $dataset['nik'];
						if ($nik !== __session('user_name')) {
							$this->load->model('m_users');
							$query = $this->m_users->reset_user_name($nik);
							if ($query) $this->session->set_userdata('user_name', $nik);
						}
						$user_email = $dataset['email'];
						if ($user_email !== __session('user_email')) {
							$this->load->model('m_users');
							$query = $this->m_users->reset_user_email($user_email);
							if ($query) $this->session->set_userdata('user_email', $user_email);
						}
					}
				} else {
					$this->vars['status'] = 'error';
					$this->vars['message'] = validation_errors();
				}
			} else {
				$this->vars['status'] = 'error';
				$this->vars['message'] = 'not_updated';
			}

			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($this->vars, self::REQUIRED_FLAGS))
				->_display();
			exit;
		}
	}

	/**
	 * Dataset
	 * @return Array
	 */
	private function dataset() {
		return [
			'assignment_letter_number' => $this->input->post('assignment_letter_number', true),
			'assignment_letter_date' => $this->input->post('assignment_letter_date', true),
			'assignment_start_date' => $this->input->post('assignment_start_date', true),
			'parent_School_status' => $this->input->post('parent_School_status', true),
			'full_name' => $this->input->post('full_name', true),
			'gender' => $this->input->post('gender', true),
			'nik' => $this->input->post('nik') ? $this->input->post('nik', true) : NULL,
			'birth_place' => $this->input->post('birth_place', true),
			'birth_date' => $this->input->post('birth_date', true),
			'mother_name' => $this->input->post('mother_name', true),
			'street_address' => $this->input->post('street_address', true),
			'rt' => $this->input->post('rt', true),
			'rw' => $this->input->post('rw', true),
			'sub_village' => $this->input->post('sub_village', true),
			'village' => $this->input->post('village', true),
			'sub_district' => $this->input->post('sub_district', true),
			'district' => $this->input->post('district', true),
			'postal_code' => $this->input->post('postal_code', true),
			'religion_id' => _toInteger($this->input->post('religion_id', true)),
			'marriage_status_id' => _toInteger($this->input->post('marriage_status_id', true)),
			'spouse_name' => $this->input->post('spouse_name', true),
			'spouse_employment_id' => _toInteger($this->input->post('spouse_employment_id', true)),
			'citizenship' => $this->input->post('citizenship', true),
			'country' => $this->input->post('country', true),
			'npwp' => $this->input->post('npwp') ? $this->input->post('npwp', true) : NULL,
			'employment_status_id' => _toInteger($this->input->post('employment_status_id', true)),
			'nip' => $this->input->post('nip') ? $this->input->post('nip', true) : NULL,
			'niy' => $this->input->post('niy') ? $this->input->post('niy', true) : NULL,
			'nuptk' => $this->input->post('nuptk') ? $this->input->post('nuptk', true) : NULL,
			'employment_type_id' => _toInteger($this->input->post('employment_type_id', true)),
			'decree_appointment' => $this->input->post('decree_appointment', true),
			'appointment_start_date' => $this->input->post('appointment_start_date', true),
			'institution_lifter_id' => _toInteger($this->input->post('institution_lifter_id', true)),
			'decree_cpns' => $this->input->post('decree_cpns', true),
			'pns_start_date' => $this->input->post('pns_start_date', true),
			'rank_id' => _toInteger($this->input->post('rank_id', true)),
			'salary_source_id' => _toInteger($this->input->post('salary_source_id', true)),
			'headmaster_license' => $this->input->post('headmaster_license', true),
			'laboratory_skill_id' => _toInteger($this->input->post('laboratory_skill_id')) ? _toInteger($this->input->post('laboratory_skill_id', true)) : NULL,
			'special_need_id' => _toInteger($this->input->post('special_need_id', true)),
			'braille_skills' => $this->input->post('braille_skills', true),
			'sign_language_skills' => $this->input->post('sign_language_skills', true),
			'phone' => $this->input->post('phone', true),
			'mobile_phone' => $this->input->post('mobile_phone', true),
			'email' => $this->input->post('email') ? $this->input->post('email', true) : NULL
		];
	}

	/**
	 * Validation Form
	 * @return Boolean
	 */
	private function validation( $id = 0) {
		$this->load->library('form_validation');
		$val = $this->form_validation;
		$val->set_rules('full_name', 'Nama Lengkap', 'trim|required');
		$val->set_rules('nik', 'NIK', 'trim|required|callback_nik_exists[' . $id . ']');
		$val->set_rules('email', 'Email', 'trim|required|valid_email|callback_email_exists[' . $id . ']');
		$val->set_rules('rt', 'RT', 'trim|numeric');
		$val->set_rules('rw', 'RW', 'trim|numeric');
		$val->set_rules('postal_code', 'Kode Pos', 'trim|numeric');
		$val->set_error_delimiters('<div>&sdot; ', '</div>');
		return $val->run();
	}

	/**
	 * NIK Exists ?
	 * @param String $nik
	 * @param Integer $id
	 * @return Boolean
	 */
	public function nik_exists( $nik, $id = 0 ) {
		$nik_exists = $this->m_employees->nik_exists($nik, $id);
		if ( $nik_exists ) {
			$this->form_validation->set_message('nik_exists', 'NIK sudah digunakan.');
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * Email Exists ?
	 * @param String $email
	 * @param Integer $id
	 * @return Boolean
	 */
	public function email_exists( $email, $id ) {
		$email_exists = $this->m_employees->email_exists( $email, $id );
		if ( $email_exists ) {
			$this->form_validation->set_message('email_exists', 'Email sudah digunakan');
			return FALSE;
		}
		return TRUE;
	}
}
