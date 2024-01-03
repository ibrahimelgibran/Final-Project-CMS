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

class Alumni extends Admin_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->model(['m_alumni', 'm_users']);
		$this->pk = M_alumni::$pk;
		$this->table = M_alumni::$table;
	}

	/**
	 * Index
	 * @return Void
	 */
	public function index() {
		$this->vars['title'] = 'Alumni';
		$this->vars['academic'] = $this->vars['academic_references'] = $this->vars['alumni'] = TRUE;
		$this->vars['content'] = 'academic/alumni';
		$this->load->view('backend/index', $this->vars);
	}

	/**
	 * Profile
	 * @return Void
	 */
	public function profile() {
		$id = _toInteger($this->uri->segment(4));
		if (_isNaturalNumber( $id )) {
			$this->load->model(['m_students', 'm_scholarships', 'm_achievements']);
			$this->vars['student'] = $this->m_students->profile($id);
			$this->vars['scholarships'] = $this->m_scholarships->get_by_student_id($id);
			$this->vars['achievements'] = $this->m_achievements->get_by_student_id($id);
			$this->vars['title'] = 'Profil Alumni';
			$this->vars['photo'] = base_url('media_library/images/no-image.png');
			$photo_name = $this->vars['student']->photo;
			$photo = 'media_library/students/' . $photo_name;
			if ($photo_name && file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $photo)) {
				$this->vars['photo'] = base_url($photo);
			}
			$this->vars['academic'] = $this->vars['academic_references'] = $this->vars['alumni'] = TRUE;
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
			$query = $this->m_alumni->get_where($keyword, 'rows', $limit, $offset);
			$total_rows = $this->m_alumni->get_where($keyword);
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
				if (_isNaturalNumber( $id )) {
					$dataset['updated_by'] = __session('user_id');
					if ($dataset['is_alumni'] == 'false') {
						$student_status_id = get_option_id('student_status', 'aktif');
						if (_isNaturalNumber( $student_status_id )) {
							$dataset['student_status_id'] = $student_status_id;
						}
					} else if ($dataset['is_alumni'] == 'unverified') {
						$dataset['is_student'] = 'false';
						$dataset['is_prospective_student'] = 'false';
					}
					$this->vars['status'] = $this->model->update($id, $this->table, $dataset) ? 'success' : 'error';
					$this->vars['message'] = $this->vars['status'] == 'success' ? 'updated' : 'not_updated';
				} else {
					$this->vars['status'] = 'error';
					$this->vars['message'] = 'not_updated';
				}
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
			'is_alumni' => $this->input->post('is_alumni', true),
			'identity_number' => strip_tags($this->input->post('identity_number', true)),
			'full_name' => $this->input->post('full_name', true),
			'street_address' => $this->input->post('street_address', true),
			'rt' => $this->input->post('rt', true),
			'rw' => $this->input->post('rw', true),
			'sub_village' => $this->input->post('sub_village', true),
			'village' => $this->input->post('village', true),
			'sub_district' => $this->input->post('sub_district', true),
			'district' => $this->input->post('district', true),
			'postal_code' => $this->input->post('postal_code', true),
			'phone' => $this->input->post('phone', true),
			'mobile_phone' => $this->input->post('mobile_phone', true),
			'email' => $this->input->post('email', true),
			'end_date' => $this->input->post('end_date', true),
			'reason' => $this->input->post('reason', true)
		];
	}

	/**
	 * Validation Form
	 * @return Boolean
	 */
	private function validation( $id = 0 ) {
		$this->load->library('form_validation');
		$val = $this->form_validation;
		$val->set_rules('full_name', 'Nama Lengkap', 'trim|required');
		$val->set_rules('identity_number', __session('_identity_number'), 'trim|alpha_numeric_spaces|callback_identity_number_exists[' . $id . ']');
		$val->set_rules('email', 'Email', 'trim|required|valid_email|callback_email_exists[' . $id . ']');
		$val->set_rules('father_birth_year', 'Tahun Lahir Ayah', 'trim|numeric|min_length[4]|max_length[4]');
		$val->set_rules('mother_birth_year', 'Tahun Lahir Ibu', 'trim|numeric|min_length[4]|max_length[4]');
		$val->set_rules('guardian_birth_year', 'Tahun Lahir Wali', 'trim|numeric|min_length[4]|max_length[4]');
		$val->set_error_delimiters('<div>&sdot; ', '</div>');
		return $val->run();
	}

	/**
	 * Email Exists ?
	 * @param String $identity_number
	 * @return Boolean
	 */
	public function identity_number_exists( $identity_number, $id ) {
		$this->load->model('m_students');
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
		$this->load->model('m_students');
		$email_exists = $this->m_students->email_exists( $email, $id );
		if ( $email_exists ) {
			$this->form_validation->set_message('email_exists', 'Email sudah digunakan');
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * Upload
	 * @return Object
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
					$data = $this->upload->data();
					$query = $this->model->update($id, $this->table, ['photo' => $data['file_name']]);
					if ( $query ) {
						@chmod(FCPATH.'media_library/students/'.$file_name, 0777);
						$resize = $this->image_resize(FCPATH.'media_library/students/', $data['file_name']);
						if ( $resize ) {
							@unlink(FCPATH.'media_library/students/'.$file_name);
						}
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
	 * Alumni Reports
	 * @return Object
	 */
	public function alumni_reports() {
		if ($this->input->is_ajax_request()) {
			$query = $this->m_alumni->alumni_reports();
			$this->output
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($query->result(), JSON_HEX_APOS | JSON_HEX_QUOT))
				->_display();
			exit;
		}
	}

	/**
	 * Create Alumni Account - Single Activation
	 * Insert Alumni record to users
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
					$this->vars['message'] = 'Akun sudah diaktifkan sebelumnya.';
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
	 * Create All Alumni Accounts
	 * Insert Alumni record to users
	 */
	public function create_accounts() {
		if ($this->input->is_ajax_request()) {
			$query = $this->m_alumni->get_inactive_accounts();
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
