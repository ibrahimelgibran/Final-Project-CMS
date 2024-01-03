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

class Student_profile extends Admin_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model('m_students');
		$this->pk = M_students::$pk;
		$this->table = M_students::$table;
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		$this->load->helper('form');
		$id = (int) __session('user_profile_id');
		$this->vars['title'] = 'Biodata';
		$this->vars['student_profile'] = TRUE;
		$this->vars['religions'] = ['' => 'Pilih :'] + get_options('religions', FALSE);
		$this->vars['special_needs'] = ['' => 'Pilih :'] + get_options('special_needs', FALSE);
		$this->vars['residences'] = ['' => 'Pilih :'] + get_options('residences', FALSE);
		$this->vars['transportations'] = ['' => 'Pilih :'] + get_options('transportations', FALSE);
		$this->vars['educations'] = ['' => 'Pilih :'] + get_options('educations', FALSE);
		$this->vars['employments'] = ['' => 'Pilih :'] + get_options('employments', FALSE);
		$this->vars['monthly_incomes'] = ['' => 'Pilih :'] + get_options('monthly_incomes', FALSE);
		$this->vars['query'] = $this->model->RowObject($this->pk, $id, $this->table);
		$this->vars['content'] = 'students_profile';
		$this->load->view('backend/index', $this->vars);
	}

	/**
	 * Save or Update
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
			'paud' => $this->input->post('paud', true),
			'tk' => $this->input->post('tk', true),
			'hobby' => $this->input->post('hobby', true),
			'ambition' => $this->input->post('ambition', true),
			'birth_place' => $this->input->post('birth_place', true),
			'birth_date' => $this->input->post('birth_date', true),
			'religion_id' => _toInteger($this->input->post('religion_id', true)),
			'special_need_id' => _toInteger($this->input->post('special_need_id', true)),
			'street_address' => $this->input->post('street_address', true),
			'rt' => $this->input->post('rt', true),
			'rw' => $this->input->post('rw', true),
			'sub_village' => $this->input->post('sub_village', true),
			'village' => $this->input->post('village', true),
			'sub_district' => $this->input->post('sub_district', true),
			'district' => $this->input->post('district', true),
			'postal_code' => $this->input->post('postal_code', true),
			'residence_id' => _toInteger($this->input->post('residence_id', true)),
			'transportation_id' => _toInteger($this->input->post('transportation_id', true)),
			'phone' => $this->input->post('phone', true),
			'mobile_phone' => $this->input->post('mobile_phone', true),
			'email' => $this->input->post('email') ? $this->input->post('email', true) : NULL,
			'sktm' => $this->input->post('sktm', true),
			'kks' => $this->input->post('kks', true),
			'kps' => $this->input->post('kps', true),
			'kip' => $this->input->post('kip', true),
			'kis' => $this->input->post('kis', true),
			'citizenship' => $this->input->post('citizenship', true),
			'country' => $this->input->post('country', true),
			'father_name' => $this->input->post('father_name', true),
			'father_birth_year' => $this->input->post('father_birth_year', true),
			'father_education_id' => _toInteger($this->input->post('father_education_id', true)),
			'father_employment_id' => _toInteger($this->input->post('father_employment_id', true)),
			'father_monthly_income_id' => _toInteger($this->input->post('father_monthly_income_id', true)),
			'father_special_need_id' => _toInteger($this->input->post('father_special_need_id', true)),
			'mother_name' => $this->input->post('mother_name', true),
			'mother_birth_year' => $this->input->post('mother_birth_year', true),
			'mother_education_id' => _toInteger($this->input->post('mother_education_id', true)),
			'mother_employment_id' => _toInteger($this->input->post('mother_employment_id', true)),
			'mother_monthly_income_id' => _toInteger($this->input->post('mother_monthly_income_id', true)),
			'mother_special_need_id' => _toInteger($this->input->post('mother_special_need_id', true)),
			'guardian_name' => $this->input->post('guardian_name', true),
			'guardian_birth_year' => $this->input->post('guardian_birth_year', true),
			'guardian_education_id' => _toInteger($this->input->post('guardian_education_id', true)),
			'guardian_employment_id' => _toInteger($this->input->post('guardian_employment_id', true)),
			'guardian_monthly_income_id' => _toInteger($this->input->post('guardian_monthly_income_id', true)),
			'mileage' => $this->input->post('mileage', true),
			'traveling_time' => $this->input->post('traveling_time', true),
			'height' => $this->input->post('height', true),
			'weight' => $this->input->post('weight', true),
			'sibling_number' => $this->input->post('sibling_number', true)
		];
	}

	/**
	 * Validation Form
	 * @return Boolean
	 */
	private function validation( $id = 0 ) {
		$this->load->library('form_validation');
		$val = $this->form_validation;
		$val->set_rules('email', 'Email', 'trim|required|valid_email|callback_email_exists[' . $id . ']');
		$val->set_rules('father_birth_year', 'Tahun Lahir Ayah', 'trim|numeric|min_length[4]|max_length[4]');
		$val->set_rules('mother_birth_year', 'Tahun Lahir Ibu', 'trim|numeric|min_length[4]|max_length[4]');
		$val->set_rules('guardian_birth_year', 'Tahun Lahir Wali', 'trim|numeric|min_length[4]|max_length[4]');
		$val->set_rules('sibling_number', 'Jumlah Saudara Kandung', 'trim|numeric|min_length[1]|max_length[2]');
		$val->set_rules('rt', 'RT', 'trim|numeric');
		$val->set_rules('rw', 'RW', 'trim|numeric');
		$val->set_rules('postal_code', 'Kode Pos', 'trim|numeric');
		$val->set_rules('mileage', 'Jarak Tempat Tinggal ke Sekolah', 'trim|numeric');
		$val->set_rules('traveling_time', 'Waktu Tempuh ke Sekolah', 'trim|numeric');
		$val->set_rules('height', 'Tinggi Badan', 'trim|numeric|min_length[2]|max_length[3]');
		$val->set_rules('weight', 'Berat Badan', 'trim|numeric|min_length[2]|max_length[3]');
		$val->set_error_delimiters('<div>&sdot; ', '</div>');
		return $val->run();
	}

	/**
	 * Email Exists ?
	 * @param String $email
	 * @param Integer $id
	 * @return Boolean
	 */
	public function email_exists( $email, $id ) {
		$email_exists = $this->m_students->email_exists( $email, $id );
		if ( $email_exists ) {
			$this->form_validation->set_message('email_exists', 'Email sudah digunakan');
			return FALSE;
		}
		return TRUE;
	}
}
