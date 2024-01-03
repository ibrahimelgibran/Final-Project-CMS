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

class Employees extends Admin_Controller {

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
		$this->vars['title'] = __session('__employee');
		$this->vars['employees'] = $this->vars['all_employees'] = TRUE;
		$this->vars['content'] = 'employees/employees';
		$this->load->view('backend/index', $this->vars);
	}

	/**
	 * Profile
	 * @param Integer
	 * @return Void
	 */
	public function profile() {
		$id = _toInteger($this->uri->segment(4));
		if (_isNaturalNumber( $id )) {
			$this->vars['query'] = $this->m_employees->profile($id);
			$this->vars['title'] = 'Profil ' . __session('__employee');
			$this->vars['photo'] = base_url('media_library/images/no-image.png');
			$photo_name = $this->vars['query']->photo;
			$photo = 'media_library/employees/' . $photo_name;
			if ($photo_name && file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $photo)) {
				$this->vars['photo'] = base_url($photo);
			}
			$this->vars['employees'] = $this->vars['all_employees'] = TRUE;
			$this->vars['content'] = 'employees/employee_profile';
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
			$query = $this->m_employees->get_where($keyword, 'rows', $limit, $offset);
			$total_rows = $this->m_employees->get_where($keyword);
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
			'assignment_letter_number' => $this->input->post('assignment_letter_number', true),
			'assignment_letter_date' => $this->input->post('assignment_letter_date', true),
			'assignment_start_date' => $this->input->post('assignment_start_date', true),
			'parent_school_status' => $this->input->post('parent_school_status', true),
			'full_name' => $this->input->post('full_name', true),
			'gender' => $this->input->post('gender', true),
			'nik' => $this->input->post('nik', true),
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
			'npwp' => $this->input->post('npwp', true) ? $this->input->post('npwp', true) : NULL,
			'employment_status_id' => _toInteger($this->input->post('employment_status_id', true)),
			'nip' => $this->input->post('nip', true) ? $this->input->post('nip', true) : NULL,
			'niy' => $this->input->post('niy', true) ? $this->input->post('niy', true) : NULL,
			'nuptk' => $this->input->post('nuptk', true),
			'employment_type_id' => _toInteger($this->input->post('employment_type_id', true)),
			'decree_appointment' => $this->input->post('decree_appointment', true),
			'appointment_start_date' => $this->input->post('appointment_start_date', true),
			'institution_lifter_id' => _toInteger($this->input->post('institution_lifter_id', true)),
			'decree_cpns' => $this->input->post('decree_cpns', true),
			'pns_start_date' => $this->input->post('pns_start_date', true),
			'rank_id' => _toInteger($this->input->post('rank_id', true)),
			'salary_source_id' => _toInteger($this->input->post('salary_source_id', true)),
			'headmaster_license' => $this->input->post('headmaster_license', true),
			'laboratory_skill_id' => _toInteger(_toInteger($this->input->post('laboratory_skill_id', true))),
			'special_need_id' => _toInteger($this->input->post('special_need_id', true)),
			'braille_skills' => $this->input->post('braille_skills', true),
			'sign_language_skills' => $this->input->post('sign_language_skills', true),
			'phone' => $this->input->post('phone', true),
			'mobile_phone' => $this->input->post('mobile_phone', true),
			'email' => $this->input->post('email', true),
			'guru_mapel' => $this->input->post('guru_mapel', true),
			'nbm' => $this->input->post('nbm', true),
			'motto' => $this->input->post('motto', true),
		];
	}

	/**
	 * Validation Form
	 * @return Boolean
	 */
	private function validation( $id = 0 ) {
		$this->load->library('form_validation');
		$val = $this->form_validation;
		$val->set_rules('full_name', 'Full Name', 'trim|required');
		$val->set_rules('nik', 'NIK', 'trim|required|callback_nik_exists[' . $id . ']');
		$val->set_rules('email', 'Email', 'trim|required|valid_email|callback_email_exists[' . $id . ']');
		$val->set_error_delimiters('<div>&sdot; ', '</div>');
		return $val->run();
	}

	/**
	 * NIK Exists ?
	 * @param String $nik
	 * @param Integer $id
	 * @return Boolean
	 */
	public function nik_exists( $nik, $id ) {
		$nik_exists = $this->m_employees->nik_exists( $nik, $id );
		if ( $nik_exists ) {
			$this->form_validation->set_message('nik_exists', 'NIK sudah digunakan');
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
				$config['upload_path'] = './media_library/employees/';
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
						@chmod(FCPATH.'media_library/employees/'.$file['file_name'], 0777);
						// chmood old file
						@chmod(FCPATH.'media_library/employees/'.$file_name, 0777);
						// unlink old file
						@unlink(FCPATH.'media_library/employees/'.$file_name);
						// resize new image
						$this->image_resize(FCPATH.'media_library/employees', $file['file_name']);
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
		$config['width'] = (int) __session('employee_photo_width');
		// $config['height'] = (int) __session('employee_photo_height');
		$this->image_lib->initialize($config);
		$this->image_lib->resize();
	}

	/**
	 * Employee Reports
	 * @return Object
	 */
	public function employee_reports() {
		if ($this->input->is_ajax_request()) {
			$query = $this->m_employees->employee_reports();
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($query->result(), JSON_HEX_APOS | JSON_HEX_QUOT))
				->_display();
			exit;
		}
	}

	/**
	  * Create Employees Account
	  */
	public function create_account() {
		if ($this->input->is_ajax_request()) {
			$id = _toInteger($this->input->post('id', true));
			if (_isNaturalNumber( $id )) {
				$user_id = $this->m_users->get_user_id('employee', $id);
				if ($user_id == 0) {
					$query = $this->model->RowObject($this->pk, $id, $this->table);
					$email_exists = $this->m_users->email_exists($query->email);
					if ( ! $email_exists ) {
						$query = $this->model->RowObject($this->pk, $id, $this->table);
						$this->db->set('user_name', $query->nik);
						$this->db->set('user_password', password_hash($query->nik, PASSWORD_BCRYPT));
						$this->db->set('user_full_name', $query->full_name);
						$this->db->set('user_email', $query->email);
						$this->db->set('user_group_id', 0);
						$this->db->set('user_type', 'employee');
						$this->db->set('user_profile_id', $id);
						$this->db->set('created_at', date('Y-m-d H:i:s'));
						$this->db->set('created_by', __session('user_id'));
						$this->db->insert('users');
						$this->vars['status'] = 'success';
						$this->vars['message'] ='Akun sudah diaktifkan';
					} else {
						$this->vars['status'] = 'warning';
						$this->vars['message'] = 'Email sudah digunakan. Silahkan gunakan email lain.';
					}
				} else {
					$this->vars['status'] = 'warning';
					$this->vars['message'] = 'Akun sudah aktif sebelumnya';
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
	  * Create All Employees Accounts
	  */
	public function create_accounts() {
		if ($this->input->is_ajax_request()) {
			$query = $this->m_employees->get_inactive_accounts();
			$n = 0;
			foreach($query->result() as $row) {
				$user_id = $this->m_users->get_user_id('employee', $row->id);
				if ( $user_id == 0 ) {
					$email_exists = $this->m_users->email_exists($row->email);
					if ( ! $email_exists ) {
						$this->db->set('user_name', $row->nik);
						$this->db->set('user_password', password_hash($row->nik, PASSWORD_BCRYPT));
						$this->db->set('user_full_name', $row->full_name);
						$this->db->set('user_email', $row->email);
						$this->db->set('user_group_id', 0);
						$this->db->set('user_type', 'employee');
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
