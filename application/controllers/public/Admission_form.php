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

class Admission_form extends Public_Controller {

	/**
	 * Constructor
	 * @access  public
	 */
	public function __construct() {
		parent::__construct();
		// If close, redirect
		if (__session('admission_status') == 'close') return redirect(base_url());
		// If not in array, redirect
		$admission_start_date = __session('admission_start_date');
		$admission_end_date = __session('admission_end_date');
		if (NULL !== $admission_start_date && NULL !== $admission_end_date) {
			$date_range = array_date($admission_start_date, $admission_end_date);
			if ( ! in_array(date('Y-m-d'), $date_range)) {
				return redirect(base_url());
			}
		}
		$this->load->model('m_registrants');
		$this->pk = M_registrants::$pk;
		$this->table = M_registrants::$table;
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		$this->load->helper('form');
		$this->load->model(['m_majors', 'm_settings']);
		$this->vars['recaptcha_site_key'] = __session('recaptcha_site_key');
		$this->vars['page_title'] = 'Formulir Penerimaan ' . __session('_student') . ' Baru Tahun ' . __session('admission_year');
		$this->vars['religions'] = ['' => 'Pilih :'] + get_options('religions', FALSE);
		$this->vars['special_needs'] = get_options('special_needs', FALSE);
		$this->vars['residences'] = ['' => 'Pilih :'] + get_options('residences', FALSE);
		$this->vars['transportations'] = ['' => 'Pilih :'] + get_options('transportations', FALSE);
		$this->vars['educations'] = ['' => 'Pilih :'] + get_options('educations', FALSE);
		$this->vars['employments'] = ['' => 'Pilih :'] + get_options('employments', FALSE);
		$this->vars['monthly_incomes'] = ['' => 'Pilih :'] + get_options('monthly_incomes', FALSE);
		$this->vars['admission_types'] = ['' => 'Pilih :'] + get_options('admission_types', FALSE);
		$this->vars['majors'] = ['' => 'Pilih :'] + $this->m_majors->dropdown();
		$this->vars['content'] = 'themes/'.theme_folder().'/admission-form';
		$this->load->view('themes/'.theme_folder().'/index', $this->vars);
	}

	/**
	  * Save
	  */
	public function save() {
		if ($this->input->is_ajax_request()) {
			if (__captchaActivated()) {
				$score = get_recapture_score($this->input->post('g-recaptcha-response'));
				if ($score < 0.9) {
					$this->vars['status'] = 'recaptcha_error';
	    			$this->vars['message'] = 'Recaptcha Error!';
					$this->output
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode($this->vars, self::REQUIRED_FLAGS))
						->_display();
					exit;
				}
			}

			if ($this->validation()) {
				$dataset = $this->dataset();
				// Upload File
				$has_uploaded = false;
				if ( ! empty($_FILES['photo']['name']) ) {
					$photo = $this->upload_file();
					if ($photo['status'] == 'success') {
						$has_uploaded = TRUE;
						$dataset['photo'] = $photo['file_name'];
					} else {
						$this->vars['status'] = $photo['status'];
						$this->vars['message'] = $photo['message'];
						$this->output
							->set_content_type('application/json', 'utf-8')
							->set_output(json_encode($this->vars, self::REQUIRED_FLAGS))
							->_display();
						exit;
					}
				}

				$query = $this->model->insert($this->table, $dataset);
				if ( $query ) {
					$registrant = $this->m_registrants->find_registrant($dataset['registration_number'], $dataset['birth_date']);
					if ( count($registrant) == 0 ) {
						$this->vars['status'] = 'warning';
						$this->vars['message'] = 'Data dengan tanggal lahir ' . indo_date($query->birth_date) . ' dan nomor pendaftaran ' . $query->registration_number . ' tidak ditemukan.';
					} else {
						$file_name = 'formulir-penerimaan-'. (__session('school_level') >= 5 ? 'mahasiswa' : 'peserta-didik').'-baru-tahun-'.__session('admission_year');
						$file_name .= '-'.$dataset['birth_date'].'-'.$dataset['registration_number'] . '-' . time() . '.pdf';
						$this->load->library('admission');
						$this->admission->create_pdf($registrant, $file_name);
						$this->vars['file_name'] = $file_name;
						$this->vars['status'] = 'success';
					}
				} else {
					$this->vars['status'] = 'error';
					$this->vars['message'] = 'Terjadi kesalahan dalam menyimpan data';
				}
				if ( ! $query && $has_uploaded ) @unlink(FCPATH.'media_library/students/'.$photo['file_name']);
			} else {
				$this->vars['status'] = 'error';
				$this->vars['message'] = validation_errors();
			}
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($this->vars, self::REQUIRED_FLAGS))
				->_display();
			exit;
		}
	}

	/**
	  * Upload File
	  * @return Array
	  */
	private function upload_file() {
		$config['upload_path'] = './media_library/students/';
		$config['allowed_types'] = 'jpg|jpeg';
		$config['max_size'] = 1024; // 1 Mb
		$config['encrypt_name'] = TRUE;
		$this->load->library('upload', $config);
		if ( ! $this->upload->do_upload('photo') ) {
			$this->vars['status'] = 'error';
			$this->vars['message'] = $this->upload->display_errors();
			$this->vars['file_name'] = '';
		} else {
			$file = $this->upload->data();
			// chmood file
			@chmod(FCPATH.'media_library/students/'.$file['file_name'], 0777);
			$this->image_resize(FCPATH.'media_library/students/', $file['file_name']);
			$this->vars['status'] = 'success';
			$this->vars['message'] = 'uploaded';
			$this->vars['file_name'] = $file['file_name'];
		}
		return $this->vars;
	}

	/**
	 * Image Resize
	 * @param String $path
	 * @param String $file_name
	 * @return Void
	 */
	private function image_resize($path, $file_name) {
		$this->load->library('image_lib');
		$config['image_library'] = 'gd2';
		$config['source_image'] = $path .'/'.$file_name;
		$config['maintain_ratio'] = TRUE;
		$config['width'] = NULL !== __session('student_photo_width') && __session('student_photo_width') > 0 ? __session('student_photo_width') : 250;
		// $config['height'] = (int) __session('student_photo_height');
		$this->image_lib->initialize($config);
		$this->image_lib->resize();
		@chmod($path.'/'.$file_name, 0644);
	}

	/**
	 * Dataset
	 * @return Array
	 */
	private function dataset() {
		$dataset = [];
		// Wajib diisi :
		$dataset['created_at'] = date('Y-m-d H:i:s');
		$dataset['registration_number'] = $this->m_registrants->registration_number();
		$dataset['is_prospective_student'] = 'true';
		$dataset['is_alumni'] = 'false';
		$dataset['is_student'] = 'false';
		$dataset['re_registration'] = 'false';

		$dataset['is_transfer'] = $this->input->post('is_transfer', true);
		$dataset['admission_type_id'] = (int) $this->input->post('admission_type_id', true);
		$dataset['admission_phase_id'] = NULL !== __session('admission_phase_id') ? __session('admission_phase_id') : 0;
		$dataset['full_name'] = strip_tags($this->input->post('full_name', true));
		$dataset['birth_date'] = $this->input->post('birth_date', true);
		$dataset['gender'] = $this->input->post('gender', true);
		$dataset['district'] = strip_tags($this->input->post('district', true));
		$dataset['first_choice_id'] = $this->input->post('first_choice_id', true) ? (int) $this->input->post('first_choice_id', true) : 0;
		$dataset['second_choice_id'] = $this->input->post('second_choice_id', true) ? (int) $this->input->post('second_choice_id', true) : 0;
		$dataset['nisn'] = $this->input->post('nisn', true) ? strip_tags($this->input->post('nisn', true)) : NULL;
		$dataset['nik'] = $this->input->post('nik', true) ? strip_tags($this->input->post('nik', true)) : NULL;
		$dataset['prev_exam_number'] = $this->input->post('prev_exam_number', true) ? strip_tags($this->input->post('prev_exam_number', true)) : NULL;
		$dataset['achievement'] = $this->input->post('achievement', true) ? strip_tags($this->input->post('achievement', true)) : NULL;
		$dataset['paud'] = $this->input->post('paud', true) ? $this->input->post('paud', true) : NULL;
		$dataset['tk'] = $this->input->post('tk', true) ? $this->input->post('tk', true) : NULL;
		$dataset['skhun'] = $this->input->post('skhun', true) ? strip_tags($this->input->post('skhun', true)) : NULL;
		$dataset['prev_school_name'] = $this->input->post('prev_school_name', true) ? strip_tags($this->input->post('prev_school_name', true)) : NULL;
		$dataset['prev_school_address'] = $this->input->post('prev_school_address', true) ? strip_tags($this->input->post('prev_school_address', true)) : NULL;
		$dataset['prev_diploma_number'] = $this->input->post('prev_diploma_number', true) ? strip_tags($this->input->post('prev_diploma_number', true)) : NULL;
		$dataset['hobby'] = $this->input->post('hobby', true) ? strip_tags($this->input->post('hobby', true)) : NULL;
		$dataset['ambition'] = $this->input->post('ambition', true) ? strip_tags($this->input->post('ambition', true)) : NULL;
		$dataset['birth_place'] = $this->input->post('birth_place', true) ? strip_tags($this->input->post('birth_place', true)) : NULL;
		$dataset['religion_id'] = $this->input->post('religion_id', true) ? (int) $this->input->post('religion_id', true) : 0;
		$dataset['special_need_id'] = $this->input->post('special_need_id', true) ? (int) $this->input->post('special_need_id', true) : 0;
		$dataset['street_address'] = $this->input->post('street_address', true) ? strip_tags($this->input->post('street_address', true)) : NULL;
		$dataset['rt'] = $this->input->post('rt', true) ? strip_tags($this->input->post('rt', true)) : NULL;
		$dataset['rw'] = $this->input->post('rw', true) ? strip_tags($this->input->post('rw', true)) : NULL;
		$dataset['sub_village'] = $this->input->post('sub_village', true) ? strip_tags($this->input->post('sub_village', true)) : NULL;
		$dataset['village'] = $this->input->post('village', true) ? strip_tags($this->input->post('village', true)) : NULL;
		$dataset['sub_district'] = $this->input->post('sub_district', true) ? strip_tags($this->input->post('sub_district', true)) : NULL;
		$dataset['postal_code'] = $this->input->post('postal_code', true) ? strip_tags($this->input->post('postal_code', true)) : NULL;
		$dataset['residence_id'] = $this->input->post('residence_id', true) ? $this->input->post('residence_id', true) : NULL;
		$dataset['transportation_id'] = $this->input->post('transportation_id', true) ? $this->input->post('transportation_id', true) : NULL;
		$dataset['phone'] = $this->input->post('phone', true) ? strip_tags($this->input->post('phone', true)) : NULL;
		$dataset['mobile_phone'] = $this->input->post('mobile_phone', true) ? strip_tags($this->input->post('mobile_phone', true)) : NULL;
		$dataset['email'] = $this->input->post('email', true) ? strip_tags($this->input->post('email', true)) : NULL;
		$dataset['sktm'] = $this->input->post('sktm', true) ? strip_tags($this->input->post('sktm', true)) : NULL;
		$dataset['kks'] = $this->input->post('kks', true) ? strip_tags($this->input->post('kks', true)) : NULL;
		$dataset['kps'] = $this->input->post('kps', true) ? strip_tags($this->input->post('kps', true)) : NULL;
		$dataset['kip'] = $this->input->post('kip', true) ? strip_tags($this->input->post('kip', true)) : NULL;
		$dataset['kis'] = $this->input->post('kis', true) ? strip_tags($this->input->post('kis', true)) : NULL;
		$dataset['citizenship'] = $this->input->post('citizenship', true) ? $this->input->post('citizenship', true) : 'WNI';
		$dataset['country'] = $this->input->post('country', true) ? strip_tags($this->input->post('country', true)) : NULL;

		$dataset['father_name'] = $this->input->post('father_name', true) ? strip_tags($this->input->post('father_name', true)) : NULL;
		$dataset['father_birth_year'] = $this->input->post('father_birth_year', true) ? (int) $this->input->post('father_birth_year', true) : NULL;
		$dataset['father_education_id'] = $this->input->post('father_education_id', true) ? (int) $this->input->post('father_education_id', true) : 0;
		$dataset['father_employment_id'] = $this->input->post('father_employment_id', true) ? (int) $this->input->post('father_employment_id', true) : 0;
		$dataset['father_monthly_income_id'] = $this->input->post('father_monthly_income_id', true) ? (int) $this->input->post('father_monthly_income_id', true) : 0;
		$dataset['father_special_need_id'] = $this->input->post('father_special_need_id', true) ? (int) $this->input->post('father_special_need_id', true) : 0;

		$dataset['mother_name'] = $this->input->post('mother_name', true) ? strip_tags($this->input->post('mother_name', true)) : NULL;
		$dataset['mother_birth_year'] = $this->input->post('mother_birth_year', true) ? (int) $this->input->post('mother_birth_year', true) : NULL;
		$dataset['mother_education_id'] = $this->input->post('mother_education_id', true) ? (int) $this->input->post('mother_education_id', true) : 0;
		$dataset['mother_employment_id'] = $this->input->post('mother_employment_id', true) ? (int) $this->input->post('mother_employment_id', true) : 0;
		$dataset['mother_monthly_income_id'] = $this->input->post('mother_monthly_income_id', true) ? (int) $this->input->post('mother_monthly_income_id', true) : 0;
		$dataset['mother_special_need_id'] = $this->input->post('mother_special_need_id', true) ? (int) $this->input->post('mother_special_need_id', true) : 0;

		$dataset['guardian_name'] = $this->input->post('guardian_name', true) ? strip_tags($this->input->post('guardian_name', true)) : NULL;
		$dataset['guardian_birth_year'] = $this->input->post('guardian_birth_year', true) ? (int) $this->input->post('guardian_birth_year', true) : NULL;
		$dataset['guardian_education_id'] = $this->input->post('guardian_education_id', true) ? (int) $this->input->post('guardian_education_id', true) : 0;
		$dataset['guardian_employment_id'] = $this->input->post('guardian_employment_id', true) ? (int) $this->input->post('guardian_employment_id', true) : 0;
		$dataset['guardian_monthly_income_id'] = $this->input->post('guardian_monthly_income_id', true) ? (int) $this->input->post('guardian_monthly_income_id', true) : 0;

		$dataset['mileage'] = $this->input->post('mileage', true) ? (int) $this->input->post('mileage', true) : NULL;
		$dataset['traveling_time'] = $this->input->post('traveling_time', true) ? (int) $this->input->post('traveling_time', true) : NULL;
		$dataset['height'] = $this->input->post('height', true) ? (int) $this->input->post('height', true) : NULL;
		$dataset['weight'] = $this->input->post('weight', true) ? (int) $this->input->post('weight', true) : NULL;
		$dataset['sibling_number'] = $this->input->post('sibling_number', true) ? (int) $this->input->post('sibling_number', true) : 0;
		return $dataset;
	}

	/**
	 * Validation Form
	 * @return Boolean
	 */
	private function validation() {
		$this->load->library('form_validation');
		$val = $this->form_validation;
		$val->set_rules('is_transfer', 'Jenis Pendaftaran', 'trim|required|in_list[true,false]');
		if (__session('major_count') > 0) {
			$val->set_rules('first_choice_id', 'Pilihan I (Satu)', 'trim|required|numeric');
			$val->set_rules('second_choice_id', 'Pilihan II (Dua)', 'trim|required|numeric');
		}
		$val->set_rules('admission_type_id', 'Jalur Pendaftaran', 'trim|required|numeric');
		$val->set_rules('prev_exam_number', 'Nomor Peserta Ujian', 'trim');
		$val->set_rules('paud', 'PAUD', 'trim|in_list[true,false]');
		$val->set_rules('tk', 'TK', 'trim|in_list[true,false]');
		$val->set_rules('hobby', 'Hobi', 'trim');
		$val->set_rules('ambition', 'Cita-Cita', 'trim');
		$val->set_rules('full_name', 'Nama Lengkap', 'trim|required');
		$val->set_rules('gender', 'Jenis Kelamin', 'trim|required|in_list[M,F]');
		$val->set_rules('skhun', 'Nomor Seri SKHUN Sebelumnya', 'trim');
		$val->set_rules('prev_diploma_number', 'Nomor Seri Ijazah Sebelumnya', 'trim');
		$val->set_rules('nisn', 'NISN', 'trim');
		if (__session('school_level') > 4) { // Perguruan Tinggi
			$val->set_rules('nik', 'NIK', 'trim|required|min_length[16]|max_length[16]|callback_nik_exists');
		}
		$val->set_rules('birth_place', 'Tempat Lahir', 'trim|required');
		$val->set_rules('birth_date', 'Tanggal Lahir', 'trim|required|min_length[10]|max_length[10]');
		$val->set_rules('religion_id', 'Agama', 'trim|required|numeric');
		$val->set_rules('special_need_id', 'Kebutuhan Khusus', 'trim|numeric');
		$val->set_rules('street_address', 'Alamat Jalan', 'trim|required');
		$val->set_rules('rt', 'RT', 'trim');
		$val->set_rules('rw', 'RW', 'trim');
		$val->set_rules('sub_village', 'Nama Dusun', 'trim');
		$val->set_rules('village', 'Nama Kelurahan/Desa', 'trim');
		$val->set_rules('sub_district', 'Kecamatan', 'trim');
		$val->set_rules('district', 'Kota/Kabupaten', 'trim|required');
		$val->set_rules('postal_code', 'Kode Pos', 'trim|numeric');
		$val->set_rules('residence_id', 'Tempat Tinggal', 'trim|numeric');
		$val->set_rules('transportation_id', 'Moda Transportasi', 'trim|numeric');
		$val->set_rules('phone', 'Nomor Telepon', 'trim');
		$val->set_rules('mobile_phone', 'Nomor HP', 'trim|required');
		$val->set_rules('email', 'E-mail Pribadi', 'trim|valid_email|callback_email_exists');
		$val->set_rules('sktm', 'No. Surat Keterangan Tidak Mampu (SKTM)', 'trim');
		$val->set_rules('kks', 'No. Kartu Keluarga Sejahtera (KKS)', 'trim');
		$val->set_rules('kps', 'No. Kartu Pra Sejahtera (KPS)', 'trim');
		$val->set_rules('kip', 'No. Kartu Indonesia Pintar (KIP)', 'trim');
		$val->set_rules('kis', 'No. Kartu Indonesia Sehat (KIS)', 'trim');
		$val->set_rules('citizenship', 'Kewarganegaraan', 'trim|required|in_list[WNI,WNA]');
		$val->set_rules('country', 'Nama Negara', 'trim');

		$val->set_rules('father_name', 'Nama Ayah Kandung', 'trim|required');
		$val->set_rules('father_birth_year', 'Tahun Lahir Ayah', 'trim|numeric|required|min_length[4]|max_length[4]');
		$val->set_rules('father_education_id', 'Pendidikan Ayah', 'trim|numeric');
		$val->set_rules('father_employment_id', 'Pekerjaan Ayah', 'trim|numeric');
		$val->set_rules('father_monthly_income_id', 'Penghasilan Bulanan Ayah', 'trim|numeric');
		$val->set_rules('father_special_need_id', 'Kebutuhan Khusus Ayah', 'trim|numeric');

		$val->set_rules('mother_name', 'Nama Ibu Kandung', 'trim|required');
		$val->set_rules('mother_birth_year', 'Tahun Lahir Ibu', 'trim|numeric|min_length[4]|max_length[4]');
		$val->set_rules('mother_education_id', 'Pendidikan Ibu', 'trim|numeric');
		$val->set_rules('mother_employment_id', 'Pekerjaan Ibu', 'trim|numeric');
		$val->set_rules('mother_monthly_income_id', 'Penghasilan Bulanan Ibu', 'trim|numeric');
		$val->set_rules('mother_special_need_id', 'Kebutuhan Khusus Ibu', 'trim|numeric');

		$val->set_rules('guardian_name', 'Nama Wali', 'trim');
		$val->set_rules('guardian_birth_year', 'Tahun Lahir Wali', 'trim|numeric|min_length[4]|max_length[4]');
		$val->set_rules('guardian_education_id', 'Pendidikan Wali', 'trim|numeric');
		$val->set_rules('guardian_employment_id', 'Pekerjaan Wali', 'trim|numeric');
		$val->set_rules('guardian_monthly_income_id', 'Penghasilan Bulanan Wali', 'trim|numeric');

		$val->set_rules('mileage', 'Jarak Tempat Tinggal ke Sekolah', 'trim|numeric|min_length[1]|max_length[5]');
		$val->set_rules('traveling_time', 'Waktu Tempuh ke Sekolah', 'trim|numeric|min_length[1]|max_length[5]');
		$val->set_rules('height', 'Tinggi Badan', 'trim|numeric|min_length[2]|max_length[5]');
		$val->set_rules('weight', 'Berat Badan', 'trim|numeric|min_length[2]|max_length[5]');
		$val->set_rules('sibling_number', 'Jumlah Saudara Kandung', 'trim|numeric|max_length[2]');

		$val->set_rules('declaration', 'Pernyataan', 'trim|required|in_list[true]|callback_declaration_check');

		$val->set_message('required', '{field} harus diisi');
		$val->set_message('min_length', '{field} harus diisi minimal {param} Karakter');
		$val->set_message('max_length', '{field} harus diisi maksimal {param} Karakter');
		$val->set_message('numeric', '{field} harus diisi dengan angka');
		$val->set_error_delimiters('<div>&sdot; ', '</div>');
		return $val->run();
	}

	/**
    * Declaration Check
	 * @param String $val
    * @return Boolean
    */
	public function declaration_check($val) {
		if ( ! filter_var((string) $val, FILTER_VALIDATE_BOOLEAN)) {
			$this->form_validation->set_message('declaration_check', 'Pernyataan harus diceklis');
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * NIK Exists ?
	 * @param String $nik
	 * @return Boolean
	 */
	public function nik_exists( $nik ) {
		$this->load->model('m_students');
		$nik_exists = $this->m_students->nik_exists( $nik );
		if ( $nik_exists ) {
			$this->form_validation->set_message('nik_exists', 'NIK sudah digunakan');
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * Email Exists ?
	 * @param String $email
	 * @return Boolean
	 */
	public function email_exists( $email ) {
		$this->load->model('m_students');
		$email_exists = $this->m_students->email_exists( $email );
		if ( $email_exists ) {
			$this->form_validation->set_message('email_exists', 'Email sudah digunakan');
			return FALSE;
		}
		return TRUE;
	}
}
