<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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

class Admission extends TCPDF {

   /**
    * Reference to CodeIgniter instance
    *
    * @var object
    */
   protected $CI;

	/**
	 * Class Constructor
	 * @return Void
	 */
	public function __construct() {
		parent::__construct('P', 'Cm', 'F4', true, 'UTF-8', false);
		$this->CI = &get_instance();
	}

	/**
	 * Overide Header
	 */
	public function Header() {

	}

	/**
	 * Overide Footer
	 */
	public function Footer() {
    	$str = '<table width="100%" border="0" cellpadding="3" cellspacing="0" style="border-top:1px solid #000000;">';
    	$str .= '<tbody>';
    	$str .= '<tr>';
    	$str .= '<td align="left" width="60%">Simpanlah lembar pendaftaran ini sebagai bukti pendaftaran Anda.</td>';
    	$str .= '<td align="right" width="40%">Dicetak tanggal '.indo_date(date('Y-m-d')).' pukul '.date('H:i:s').'</td>';
    	$str .= '</tr>';
    	$str .= '</tbody>';
    	$str .= '</table>';
    	$this->setY(-1);
    	$this->writeHTML($str, true, false, true, false, 'L');
	}

	/**
	 * Create Admission PDF Form
	 * @param Array $result
	 */
	public function create_pdf(array $result, $file_name) {
		$this->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$this->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		$this->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		$this->SetAutoPageBreak(TRUE, 1);
		$this->setImageScale(PDF_IMAGE_SCALE_RATIO);
		// Set Properties
		$this->SetTitle('FORMULIR PENERIMAAN '.ucwords(strtolower($this->CI->session->_student)).' BARU TAHUN '.$this->CI->session->admission_year);
		$this->SetAuthor('https://sekolahku.web.id');
		$this->SetSubject($this->CI->session->school_name);
		$this->SetKeywords($this->CI->session->school_name);
		$this->SetCreator('https://sekolahku.web.id');
		$this->SetMargins(2, 1, 2, true);
		$this->AddPage();
		$this->SetFont('freesans', '', 10);

		// Get HTML Template
		$str = file_get_contents(VIEWPATH.'admission/pdf_admission_template.html');
		// Header
		$str = str_replace('[LOGO]', base_url('media_library/images/'.$this->CI->session->logo), $str);
		$str = str_replace('[SCHOOL_NAME]', strtoupper($this->CI->session->school_name), $str);
		$str = str_replace('[SCHOOL_STREET_ADDRESS]', $this->CI->session->street_address, $str);
		$str = str_replace('[SCHOOL_PHONE]', $this->CI->session->phone, $str);
		$str = str_replace('[SCHOOL_FAX]', $this->CI->session->fax, $str);
		$str = str_replace('[SCHOOL_POSTAL_CODE]', $this->CI->session->postal_code, $str);
		$str = str_replace('[SCHOOL_EMAIL]', $this->CI->session->email, $str);
		$str = str_replace('[SCHOOL_WEBSITE]', str_replace(['http://', 'https://', 'www.'], '', $this->CI->session->website), $str);
		$str = str_replace('[TITLE]', 'Formulir Penerimaan ' . ucwords(strtolower($this->CI->session->_student)) .' Baru Tahun '.$this->CI->session->admission_year, $str);
		// Registrasi Peserta Didik
		$str = str_replace('[STUDENT_TYPE]', ($this->CI->session->school_level >= 5 ? 'Calon Mahasiswa' : 'Calon Peserta Didik'), $str);
		$str = str_replace('[IS_TRANSFER]', ($result['is_transfer'] == 'true' ? 'Pindahan':'Baru'), $str);
		$str = str_replace('[ADMISSION_TYPE]', $result['admission_type'], $str);
		$str = str_replace('[REGISTRATION_NUMBER]', $result['registration_number'], $str);
		$str = str_replace('[CREATED_AT]', $result['created_at'], $str);
		if ($this->CI->session->school_level >= 3) {
			$str = str_replace('[FIRST_CHOICE]', $result['first_choice'], $str);
			$str = str_replace('[SECOND_CHOICE]', $result['second_choice'], $str);
		} else {
			$replace = '<tr><td align="right">Pilihan I</td><td align="center">:</td><td align="left">[FIRST_CHOICE]</td></tr>';
			$str = str_replace($replace, '', $str);
			$replace = '<tr><td align="right">Pilihan II</td><td align="center">:</td><td align="left">[SECOND_CHOICE]</td></tr>';
			$str = str_replace($replace, '', $str);
		}

		$str = str_replace('[PREV_SCHOOL_NAME]', $result['prev_school_name'], $str);
		$str = str_replace('[PREV_SCHOOL_ADDRESS]', $result['prev_school_address'], $str);
		// Profile
		$str = str_replace('[FULL_NAME]', $result['full_name'], $str);
		$str = str_replace('[GENDER]', $result['gender'], $str);

		if ($this->CI->session->school_level == 2 || $this->CI->session->school_level == 3 || $this->CI->session->school_level == 4) {
			$str = str_replace('[NISN]', $result['nisn'], $str);
		} else {
			$replace = '<tr><td align="right">NISN</td><td align="center">:</td><td align="left">[NISN]</td></tr>';
			$str = str_replace($replace, '', $str);
		}
		if ($this->CI->session->school_level > 1) {
			$str = str_replace('[NIK]', $result['nik'], $str);
		} else {
			$replace = '<tr><td align="right">NIK</td><td align="center">:</td><td align="left">[NIK]</td></tr>';
			$str = str_replace($replace, '', $str);
		}
		$str = str_replace('[BIRTH_PLACE]', $result['birth_place'], $str);
		$str = str_replace('[BIRTH_DATE]', indo_date($result['birth_date']), $str);
		$str = str_replace('[RELIGION]', $result['religion'], $str);
		$str = str_replace('[SPECIAL_NEEDS]', $result['special_needs'], $str);
		// Alamat
		$str = str_replace('[STREET_ADDRESS]', $result['street_address'], $str);
		$str = str_replace('[RT]', $result['rt'], $str);
		$str = str_replace('[RW]', $result['rw'], $str);
		$str = str_replace('[SUB_VILLAGE]', $result['sub_village'], $str);
		$str = str_replace('[VILLAGE]', $result['village'], $str);
		$str = str_replace('[SUB_DISTRICT]', $result['sub_district'], $str);
		$str = str_replace('[DISTRICT]', $result['district'], $str);
		$str = str_replace('[POSTAL_CODE]', $result['postal_code'], $str);
		$str = str_replace('[EMAIL]', $result['email'], $str);
		$str = str_replace('[FOOTER_DATE]', $result['district'].', '. indo_date(substr($result['created_at'], 0, 10)), $str);
		$str = str_replace('[FOOTER_FULL_NAME]', $result['full_name'], $str);
		$this->writeHTML($str, true, false, true, false, 'C');
		$this->Output(FCPATH . 'media_library/students/'.$file_name, 'F');
	}

	/**
	 * Generating Blank Admission PDF Form
	 * @param Array $params
	 */
	public function blank_pdf(array $params) {
		$CI = &get_instance();
		$this->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$this->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		$this->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		$this->SetAutoPageBreak(TRUE, 1);
		$this->setImageScale(PDF_IMAGE_SCALE_RATIO);
		// Set Properties
		$this->SetTitle('FORMULIR PENERIMAAN '.ucfirst(strtolower($this->CI->session->_student)).' BARU TAHUN '.$this->CI->session->admission_year);
		$this->SetAuthor('https://sekolahku.web.id');
		$this->SetSubject($this->CI->session->school_name);
		$this->SetKeywords($this->CI->session->school_name);
		$this->SetCreator('https://sekolahku.web.id');
		$this->SetMargins(2, 1, 2, true);
		$this->AddPage();
		$this->SetFont('freesans', '', 10);

		// Get HTML Template
		$str = file_get_contents(VIEWPATH.'admission/pdf_admission_template.html');
		// Header
		$str = str_replace('[LOGO]', base_url('media_library/images/'.$this->CI->session->logo), $str);
		$str = str_replace('[SCHOOL_NAME]', strtoupper($this->CI->session->school_name), $str);
		$str = str_replace('[SCHOOL_STREET_ADDRESS]', $this->CI->session->street_address, $str);
		$str = str_replace('[SCHOOL_PHONE]', $this->CI->session->phone, $str);
		$str = str_replace('[SCHOOL_FAX]', $this->CI->session->fax, $str);
		$str = str_replace('[SCHOOL_POSTAL_CODE]', $this->CI->session->postal_code, $str);
		$str = str_replace('[SCHOOL_EMAIL]', $this->CI->session->email, $str);
		$str = str_replace('[SCHOOL_WEBSITE]', str_replace(['http://', 'https://', 'www.'], '', $this->CI->session->website), $str);
		$str = str_replace('[TITLE]', 'Formulir Penerimaan ' . ucwords(strtolower($this->CI->session->_student)).' Baru Tahun '.$this->CI->session->admission_year, $str);
		$dotted = '.................................................................................................................';
		$str = str_replace('[STUDENT_TYPE]', ucwords(strtolower($this->CI->session->_student)), $str);
		$str = str_replace('[IS_TRANSFER]', 'Baru / Pindahan', $str);
		$str = str_replace('[ADMISSION_TYPE]', (count($params['admission_types']) > 0 ? implode(' / ', $params['admission_types']) : $dotted), $str);
		$str = str_replace('[REGISTRATION_NUMBER]', '....................................................................................... ( <i>Diisi Panitia</i> )', $str);
		$str = str_replace('[CREATED_AT]', '....................................................................................... ( <i>Diisi Panitia</i> )', $str);
		// Registrasi Peserta Didik
		if ($this->CI->session->school_level >= 3) {
			$str = str_replace('[FIRST_CHOICE]', $dotted, $str);
			$str = str_replace('[SECOND_CHOICE]', $dotted, $str);

		} else {
			$replace = '<tr><td align="right">Pilihan I</td><td align="center">:</td><td align="left">[FIRST_CHOICE]</td></tr>';
			$str = str_replace($replace, '', $str);
			$replace = '<tr><td align="right">Pilihan II</td><td align="center">:</td><td align="left">[SECOND_CHOICE]</td></tr>';
			$str = str_replace($replace, '', $str);
		}
		// Sekolah Asal
		$str = str_replace('[PREV_SCHOOL_NAME]', $dotted, $str);
		// Alamat Sekolah Asal
		$str = str_replace('[PREV_SCHOOL_ADDRESS]', $dotted, $str);

		// Profile
		$str = str_replace('[FULL_NAME]', $dotted, $str);
		$str = str_replace('[GENDER]', 'Laki-laki / Perempuan', $str);
		if ($this->CI->session->school_level == 2 || $this->CI->session->school_level == 3 || $this->CI->session->school_level == 4) {
			$str = str_replace('[NISN]', $dotted, $str);
		} else {
			$replace = '<tr><td align="right">NISN</td><td align="center">:</td><td align="left">[NISN]</td></tr>';
			$str = str_replace($replace, '', $str);
		}
		if ($this->CI->session->school_level > 1) {
			$str = str_replace('[NIK]', $dotted, $str);
		} else {
			$replace = '<tr><td align="right">NIK</td><td align="center">:</td><td align="left">[NIK]</td></tr>';
			$str = str_replace($replace, '', $str);
		}
		$str = str_replace('[BIRTH_PLACE]', $dotted, $str);
		$str = str_replace('[BIRTH_DATE]', $dotted, $str);
		$str = str_replace('[RELIGION]', (count($params['religions']) > 0 ? implode(' / ', $params['religions']) : $dotted), $str);
		$str = str_replace('[SPECIAL_NEEDS]', (count($params['special_needs']) > 0 ? implode(' / ', $params['special_needs']) : $dotted), $str);
		// Alamat
		$str = str_replace('[STREET_ADDRESS]', $dotted, $str);
		$str = str_replace('[RT]', $dotted, $str);
		$str = str_replace('[RW]', $dotted, $str);
		$str = str_replace('[SUB_VILLAGE]', $dotted, $str);
		$str = str_replace('[VILLAGE]', $dotted, $str);
		$str = str_replace('[SUB_DISTRICT]', $dotted, $str);
		$str = str_replace('[DISTRICT]', $dotted, $str);
		$str = str_replace('[POSTAL_CODE]', $dotted, $str);
		$str = str_replace('[EMAIL]', $dotted, $str);
		$str = str_replace('[FOOTER_DATE]', '.............................................., ............. .................................... ' . $this->CI->session->admission_year, $str);
		$str = str_replace('[FOOTER_FULL_NAME]', '....................................................................', $str);
		$file_name = 'formulir-penerimaan-'. ($this->CI->session->school_level >= 5 ? 'mahasiswa' : 'peserta-didik').'-baru-tahun-'.$this->CI->session->admission_year;
		$file_name = strtoupper(str_replace(' ', '-', $file_name)).'.pdf';
		$this->writeHTML($str, true, false, true, false, 'C');
		$this->Output(__DIR__.'../../media_library/students/'.$file_name, 'I');
	}
}

/* End of file Admission.php */
/* Location: ./application/libraries/Admission.php */
