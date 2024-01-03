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

class Students extends Admin_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model([
			'm_students',
			'm_majors'
		]);
		$this->pk = M_students::$pk;
		$this->table = M_students::$table;
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		$this->vars['title'] = ucwords(strtolower(__session('_student')));
		$this->vars['academic'] = $this->vars['academic_references'] = $this->vars['students'] = TRUE;
		$majors = [0 => 'Unset'];
		if (__session('major_count') > 0) {
			$majors = [0 => 'Unset'] + $this->m_majors->dropdown();
		}
		$this->vars['major_dropdown'] = json_encode($majors, JSON_HEX_APOS | JSON_HEX_QUOT);
		$this->vars['content'] = 'academic/students';
		$this->load->view('backend/index', $this->vars);
	}

	/**
	 * Student Profile
	 * @return Void
	 */
	public function profile() {
		$id = _toInteger($this->uri->segment(4));
		if (_isNaturalNumber( $id )) {
			$this->load->model(['m_students', 'm_scholarships', 'm_achievements']);
			$this->vars['student'] = $this->m_students->profile($id);
			$this->vars['scholarships'] = $this->m_scholarships->get_by_student_id($id);
			$this->vars['achievements'] = $this->m_achievements->get_by_student_id($id);
			$this->vars['title'] = 'Profil ' . __session('_student');
			$this->vars['photo'] = base_url('media_library/images/no-image.png');
			$photo_name = $this->vars['student']->photo;
			$photo = 'media_library/students/' . $photo_name;
			if ($photo_name && file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $photo)) {
				$this->vars['photo'] = base_url($photo);
			}
			$this->vars['academic'] = $this->vars['academic_references'] = $this->vars['students'] = TRUE;
			$this->vars['content'] = 'academic/student_profile';
			$this->load->view('backend/index', $this->vars);
		} else {
			show_404();
		}
	}

	/**
	 * Pagination
	 * @return Object
	 */
	public function pagination() {
		if ($this->input->is_ajax_request()) {
			$keyword = trim($this->input->post('keyword', true));
			$page_number = _toInteger($this->input->post('page_number', true));
			$limit = _toInteger($this->input->post('per_page', true));
			$offset = ($page_number * $limit);
			$query = $this->m_students->get_where($keyword, 'rows', $limit, $offset);
			$total_rows = $this->m_students->get_where($keyword);
			$total_page = $limit > 0 ? ceil(_toInteger($total_rows) / _toInteger($limit)) : 1;
			$this->vars['total_page'] = _toInteger($total_page);
			$this->vars['total_rows'] = _toInteger($total_rows);
			$this->vars['rows'] = $query->result();
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($this->vars, self::REQUIRED_FLAGS))
				->_display();
			exit;
		}
	}

	/**
	 * Save | Update
	 * @return Object
	 */
	public function save() {
		if ($this->input->is_ajax_request()) {
			$id = _toInteger($this->input->post('id', true));
			if ($this->validation( $id )) {
				$dataset = $this->dataset();
				$dataset[(_isNaturalNumber( $id ) ? 'updated_by' : 'created_by')] = __session('user_id');
				if (!_isNaturalNumber( $id )) $dataset['created_at'] = date('Y-m-d H:i:s');
				$query = $this->model->upsert($id, $this->table, $dataset);
				$this->vars['status'] = $query ? 'success' : 'error';
				$this->vars['message'] = $query ? 'Data Anda berhasil disimpan.' : 'Terjadi kesalahan dalam menyimpan data';
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
	 * Dataset
	 * @return Array
	 */
	private function dataset() {
		return [
			'is_student' => 'true',
			'is_transfer' => $this->input->post('is_transfer', true),
			'start_date' => $this->input->post('start_date', true),
			'identity_number' => $this->input->post('identity_number') ? $this->input->post('identity_number', true) : NULL,
			'major_id' => _toInteger($this->input->post('major_id', true)) ? _toInteger($this->input->post('major_id', true)) : 0,
			'paud' => $this->input->post('paud', true),
			'tk' => $this->input->post('tk', true),
			'hobby' => $this->input->post('hobby', true),
			'ambition' => $this->input->post('ambition', true),
			'full_name' => $this->input->post('full_name', true),
			'gender' => $this->input->post('gender', true),
			'nisn' => $this->input->post('nisn') ? $this->input->post('nisn', true) : NULL,
			'nik' => $this->input->post('nik') ? $this->input->post('nik', true) : NULL,
			'skhun' => $this->input->post('skhun') ? $this->input->post('skhun', true) : NULL,
			'prev_exam_number' => $this->input->post('prev_exam_number') ? $this->input->post('prev_exam_number', true) : NULL,
			'prev_diploma_number' => $this->input->post('prev_diploma_number') ? $this->input->post('prev_diploma_number', true) : NULL,
			'prev_school_name' => $this->input->post('prev_school_name') ? $this->input->post('prev_school_name', true) : NULL,
			'prev_school_address' => $this->input->post('prev_school_address') ? $this->input->post('prev_school_address', true) : NULL,
			'birth_place' => $this->input->post('birth_place', true),
			'birth_date' => $this->input->post('birth_date', true),
			'religion_id' => _toInteger($this->input->post('religion_id', true)),
			'special_need_id' => _toInteger($this->input->post('special_need_id', true)) == 0 ? NULL : _toInteger($this->input->post('special_need_id', true)),
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
			'email' => $this->input->post('email', true) ? $this->input->post('email', true) : NULL,
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
			'sibling_number' => $this->input->post('sibling_number', true),
			'student_status_id' => _toInteger($this->input->post('student_status_id', true)),
			'end_date' => $this->input->post('end_date', true),
			'reason' => $this->input->post('reason', true)
		];
	}

	/**
	 * Validation Form
	 * @return Boolean
	 */
	private function validation( $id = 0) {
		$this->load->library('form_validation');
		$val = $this->form_validation;
		$val->set_rules('identity_number', __session('_identity_number'), 'trim|required|callback_identity_number_exists[' . $id . ']');
		$val->set_rules('full_name', 'Nama Lengkap', 'trim|required');
		$val->set_rules('student_status_id', 'Status ' . __session('_student'), 'trim|required');
		$val->set_rules('email', 'Email', 'trim|required|valid_email|callback_email_exists[' . $id . ']');
		$val->set_rules('nik', 'NIK', 'trim');
		$val->set_rules('nisn', 'NISN', 'trim');
		$val->set_rules('father_birth_year', 'Tahun Lahir Ayah', 'trim|numeric|min_length[4]|max_length[4]');
		$val->set_rules('mother_birth_year', 'Tahun Lahir Ibu', 'trim|numeric|min_length[4]|max_length[4]');
		$val->set_rules('guardian_birth_year', 'Tahun Lahir Wali', 'trim|numeric|min_length[4]|max_length[4]');
		$val->set_rules('sibling_number', 'Jumlah Saudara Kandung', 'trim|numeric|min_length[1]|max_length[2]');
		$val->set_rules('rt', 'RT', 'trim|numeric');
		$val->set_rules('rw', 'RW', 'trim|numeric');
		$val->set_rules('postal_code', 'Kode Pos', 'trim|numeric');
		$val->set_rules('mileage', 'Jarak Tempat Tinggal ke Sekolah', 'trim|numeric');
		$val->set_rules('traveling_time', 'Waktu Tempuh ke Sekolah', 'trim|numeric');
		$val->set_rules('height', 'Tinggi Badan', 'trim|numeric');
		$val->set_rules('weight', 'Berat Badan', 'trim|numeric');
		$val->set_message('required', '{field} harus diisi');
		$val->set_message('valid_email', '{field} harus diisi dengan format email yang benar');
		$val->set_error_delimiters('<div>&sdot; ', '</div>');
		return $val->run();
	}

	/**
	 * NIS Exists ?
	 * @param String $email
	 * @param Integer $id
	 * @return Boolean
	 */
	public function identity_number_exists( $identity_number, $id ) {
		$identity_number_exists = $this->m_students->identity_number_exists( $identity_number, $id );
		if ( $identity_number_exists ) {
			$this->form_validation->set_message('identity_number_exists', 'NIS sudah digunakan');
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
		$email_exists = $this->m_students->email_exists( $email, $id );
		if ( $email_exists ) {
			$this->form_validation->set_message('email_exists', 'Email sudah digunakan');
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * Upload
	 * @return Void
	 */
	public function upload() {
		if ($this->input->is_ajax_request()) {
			$id = _toInteger($this->input->post('id', true));
			if (_isNaturalNumber( $id )) {
				$query = $this->model->RowObject($this->pk, $id, $this->table);
				$file_name = $query->photo;
				$config['upload_path'] = './media_library/students/';
				$config['allowed_types'] = 'jpg|png|jpeg|gif';
				$config['max_size'] = 0;
				$config['encrypt_name'] = TRUE;
				$this->load->library('upload', $config);
				if ( ! $this->upload->do_upload('file') ) {
					$this->vars['status'] = 'error';
					$this->vars['message'] = $this->upload->display_errors();
				} else {
					$file = $this->upload->data();
					$update = $this->model->update($id, $this->table, ['photo' => $file['file_name']]);
					if ($update) {
						// chmood new file
						@chmod(FCPATH.'media_library/students/'.$file['file_name'], 0777);
						// chmood old file
						@chmod(FCPATH.'media_library/students/'.$file_name, 0777);
						// unlink old file
						@unlink(FCPATH.'media_library/students/'.$file_name);
						// resize new image
						$this->image_resize(FCPATH.'media_library/students', $file['file_name']);
					}
					$this->vars['status'] = 'success';
					$this->vars['message'] = 'uploaded';
				}
			}
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($this->vars, self::REQUIRED_FLAGS))
				->_display();
			exit;
		}
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
		$config['width'] = (int) __session('student_photo_width');
		// $config['height'] = (int) __session('student_photo_height');
		$this->image_lib->initialize($config);
		$this->image_lib->resize();
	}

	/**
	 * Student Reports
	 * @return Object
	 */
	public function student_reports() {
		if ($this->input->is_ajax_request()) {
			$query = $this->m_students->student_reports();
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($query->result(), JSON_HEX_APOS | JSON_HEX_QUOT))
				->_display();
			exit;
		}
	}

	/**
	  * Create Student Account - Single Activation
	  * Insert students record to users
	  * @return Object
	  */
	public function create_account() {
		if ($this->input->is_ajax_request()) {
			$id = _toInteger($this->input->post('id', true));
			if (_isNaturalNumber( $id )) {
				$user_id = $this->m_users->get_user_id('student', $id);
				if ( $user_id == 0 ) {
					$query = $this->model->RowObject($this->pk, $id, $this->table);
					$email_exists = $this->m_users->email_exists($query->email);
					if ( ! $email_exists ) {
						$this->db->set('user_name', $query->identity_number);
						$this->db->set('user_password', password_hash($query->identity_number, PASSWORD_BCRYPT));
						$this->db->set('user_full_name', $query->full_name);
						$this->db->set('user_email', $query->email);
						$this->db->set('user_group_id', 0);
						$this->db->set('user_type', 'student');
						$this->db->set('user_profile_id', $id);
						$this->db->set('created_at', date('Y-m-d H:i:s'));
						$this->db->set('created_by', __session('user_id'));
						$this->db->insert('users');
						$this->vars['status'] = 'success';
						$this->vars['message'] = 'Akun sudah diaktifkan';
					} else {
						$this->vars['status'] = 'warning';
						$this->vars['message'] = 'Email sudah digunakan. Silahkan gunakan email lain.';
					}
				} else {
					$this->vars['status'] = 'warning';
					$this->vars['message'] = 'Akun sudah diaktifkan sebelumnya';
				}
			} else {
				$this->vars['status'] = 'error';
				$this->vars['message'] = 'ID bukan merupakan tipe angka yang valid !';
			}
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($this->vars, self::REQUIRED_FLAGS))
				->_display();
			exit;
		}
	}

	/**
	  * Create All Student Accounts
	  * Insert students record to users
	  */
	public function create_accounts() {
		if ($this->input->is_ajax_request()) {
			$query = $this->m_students->get_inactive_accounts();
			$n = 0;
			foreach($query->result() as $row) {
				$user_id = $this->m_users->get_user_id('student', $row->id);
				if ( $user_id == 0 ) {
					$email_exists = $this->m_users->email_exists($row->email);
					if ( ! $email_exists ) {
						$this->db->set('user_name', $row->identity_number);
						$this->db->set('user_password', password_hash($row->identity_number, PASSWORD_BCRYPT));
						$this->db->set('user_full_name', $row->full_name);
						$this->db->set('user_email', $row->email);
						$this->db->set('user_group_id', 0);
						$this->db->set('user_type', 'student');
						$this->db->set('user_profile_id', $row->id);
						$this->db->set('created_at', date('Y-m-d H:i:s'));
						$this->db->set('created_by', __session('user_id'));
						$this->db->insert('users');
						$insert_id = $this->db->insert_id();
						if ( $insert_id ) $n++;
					}
				}
			}
			$this->vars['status'] = $n > 0 ? 'success' : 'warning';
			$this->vars['message'] = $n > 0 ? 'Akun sudah diaktifkan' : 'Akun sudah aktif sebelumnya';
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($this->vars, self::REQUIRED_FLAGS))
				->_display();
			exit;
		}
	}
}
