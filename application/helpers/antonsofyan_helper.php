<?php defined('BASEPATH') or exit('No direct script access allowed');

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


 /**
 * Is a Natural number, but not a zero  (1,2,3, etc.)
 * @param String $n
 * @return Boolean
 */
 if ( ! function_exists('_isNaturalNumber')) {
    function _isNaturalNumber( $n ) {
      return ($n != 0 && ctype_digit((string) $n));
   }
}

/**
* Is Integer
* @param String $n
* @return Boolean
*/
if ( ! function_exists('_toInteger')) {
   function _toInteger( $n ) {
      $n = abs(intval(strval($n)));
      return $n;
   }
}

 /**
  * Copyright
  * @param String $year
  * @param String $link
  * @param String $company
  * @return String
  */
 if ( ! function_exists('copyright')) {
 	function copyright($year = '', $link = '', $company_name = '') {
 		if ( ! _isValidYear( $year )) return;
 		$str = 'Copyright &copy; ';
 		$str .= date('Y') > $year ? $year . ' - ' . date('Y') : $year;
 		$str .= '<a href="';
 		$str .= $link == '' ? base_url() : $link;
 		$str .= '"> ';
 		$str .= $company_name == '' ? str_replace(array('http://', 'https://', 'www.'), '', rtrim(base_url(), '/')) : $company_name;
 		$str .= '</a>';
 		$str .= ' All rights reserved.';
 		return $str;
 	}
 }

/**
 * Filesize Formatted
 * @param Int $size
 * @return String
 */
if ( ! function_exists('filesize_formatted')) {
	function filesize_formatted($size) {
		$units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
		$power = $size > 0 ? floor(log($size, 1024)) : 0;
		return number_format($size / pow(1024, $power), 2, '.', ',') . ' ' . $units[$power];
	}
}

/**
 * Create Directory
 * @param String $dir
 * @return Void
 */
if ( ! function_exists('create_dir')) {
	function create_dir($dir) {
		if (!is_dir($dir)) {
			if (!mkdir($dir, 0777, true)) {
				die('Tidak dapat membuat folder : ' . $dir);
			}
		}
	}
}

/**
 * extract_themes
 * @return String
 */
if (! function_exists('extract_themes')) {
	function extract_themes() {
		$zip = new ZipArchive;
		$zip->open('./views/themes/default.zip');
		@chmod(FCPATH . 'views/themes', 0775);
		$zip->extractTo('./views/themes/');
		@chmod(FCPATH . 'views/themes/default/', 0775);
		$zip->close();
	}
}

/**
 * get_options
 * @param String $option_group
 * @return Object
 */
if ( ! function_exists('get_options')) {
	function get_options($option_group = '', $encode = TRUE) {
		$CI = &get_instance();
      $CI->load->model('m_options');
		$options = $CI->m_options->get_options( $option_group );
      if ( $encode ) return json_encode($options, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES);
      return $options;
	}
}

/**
 * get_option_id
 * @param String $option_group
 * @param String $option_name
 * @return Int
 */
if ( ! function_exists('get_option_id')) {
	function get_option_id($option_group = '', $option_name = '') {
		$CI = &get_instance();
      $CI->load->model('m_options');
		$option_id = $CI->m_options->get_option_id( $option_group, $option_name );
		return _toInteger($option_id);
	}
}

/**
 * get __session
 * @param String $session_key
 * @return Any
 */
if ( ! function_exists('__session')) {
   function __session( $session_key ) {
      $CI = &get_instance();
      return $CI->session->userdata( $session_key );
   }
}

/**
 * Encode String
 * @param String $str
 * @return String
 */
if ( ! function_exists('encode_str')) {
	function encode_str($str) {
		$CI = &get_instance();
		$CI->load->library('encrypt');
		$ret = $CI->encrypt->encode($str, $CI->config->item('encryption_key'));
		$ret = strtr($ret, array('+' => '.', '=' => '-', '/' => '~'));
		return $ret;
	}
}

/**
 * Decode String
 * @param String
 * @return String
 */
if ( ! function_exists('decode_str')) {
	function decode_str($str) {
		$CI = &get_instance();
		$CI->load->library('encrypt');
		$str = strtr($str, array('.' => '+', '-' => '=', '~' => '/'));
		return $CI->encrypt->decode($str, $CI->config->item('encryption_key'));
	}
}

/**
 * Indonesian Date Formated
 * @param String $date
 * @return String
 */
if ( ! function_exists('indo_date')) {
	function indo_date($date) {
		if (_isValidDate($date)) {
			$parts = explode("-", $date);
			$result = $parts[2] . ' ' . bulan($parts[1]) . ' ' . $parts[0];
			return $result;
		}
		return '';
	}
}

/**
 * English Date Formated
 * @param String $date
 * @return String
 */
if ( ! function_exists('english_date')) {
	function english_date($date) {
		if (_isValidDate($date)) {
			$parts = explode("-", $date);
			$result = $parts[2] . ', ' . month($parts[1]) . ' ' . $parts[0];
			return $result;
		}
		return '';
	}
}

/**
 * Day Name
 * @param String $idx
 * @return String
 */
if (! function_exists('day_name')) {
	function day_name($idx) {
		$arr = ['', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum\'at', 'Sabtu', 'Minggu'];
		return $arr[$idx];
	}
}

/**
 * Check Is Valid Date ?
 * @param String $date
 * @return Boolean
 */
if ( ! function_exists('_isValidDate')) {
	function _isValidDate($date) {
		if (preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", $date, $parts)) {
			return checkdate($parts[2], $parts[3], $parts[1]) ? true : false;
		}
		return false;
	}
}

/**
 * Check Is Valid Year ?
 * @param String $year
 * @return Boolean
 */
if ( ! function_exists('_isValidYear')) {
   function _isValidYear( $year ) {
      if ( _toInteger($year) < 1000 || _toInteger($year) > 9999 ) return false;
      return true;
   }
}

/**
 * Check Is Valid Month ?
 * @param String $month
 * @return Boolean
 */
if ( ! function_exists('_isValidMonth')) {
   function _isValidMonth( $month ) {
      if ( _toInteger($month) < 1 || _toInteger($month) > 12 ) return false;
      return true;
   }
}

/**
 * Bulan
 * @param String $key
 * @return String
 */
if ( ! function_exists('bulan')) {
	function bulan($key = '') {
		$data = [
			'01' => 'Januari',
			'02' => 'Februari',
			'03' => 'Maret',
			'04' => 'April',
			'05' => 'Mei',
			'06' => 'Juni',
			'07' => 'Juli',
			'08' => 'Agustus',
			'09' => 'September',
			'10' => 'Oktober',
			'11' => 'November',
			'12' => 'Desember',
		];
		return $key === '' ? $data : $data[$key];
	}
}

/**
 * Month
 * @param String $key
 * @return String
 */
if ( ! function_exists('month')) {
	function month($key = '') {
		$data = [
			'01' => 'January',
			'02' => 'February',
			'03' => 'March',
			'04' => 'April',
			'05' => 'May',
			'06' => 'June',
			'07' => 'July',
			'08' => 'August',
			'09' => 'September',
			'10' => 'October',
			'11' => 'November',
			'12' => 'December',
		];
		return $key === '' ? $data : $data[$key];
	}
}

/**
 * Get IP Address
 * @return String
 */
if (! function_exists('get_ip_address')) {
	function get_ip_address() {
		return getenv('HTTP_X_FORWARDED_FOR') ? getenv('HTTP_X_FORWARDED_FOR') : getenv('REMOTE_ADDR');
	}
}

/**
 * check_internet_connection
 * @return Boolean
 */
if (! function_exists('check_internet_connection')) {
	function check_internet_connection() {
		return checkdnsrr('google.com');
	}
}

/**
 * Array Date
 * @param String $start_date
 * @param String $end_Date
 * @return Array
 */
if ( ! function_exists('array_date')) {
   function array_date($start_date, $end_date) {
      if (!_isValidDate($start_date)) return [];
      if (!_isValidDate($end_date)) return [];
      $start_date = strtotime($start_date);
      $end_date = strtotime($end_date);
      if ($start_date > $end_date) return array_date($end_date, $start_date);
      $dates = [];
      do {
         $dates[] = date('Y-m-d', $start_date);
         $start_date = strtotime("+ 1 day", $start_date);
      }
      while($start_date <= $end_date);
      return $dates;
   }
}

/**
 * Delete Cache
 * @return Void
 */
if (! function_exists('delete_cache')) {
	function delete_cache() {
		$CI = &get_instance();
		$CI->load->helper('directory');
		$path = APPPATH . 'cache';
		$files = directory_map($path, FALSE, TRUE);
		foreach ($files as $file) {
			if ($file !== 'index.html' && $file !== '.htaccess') {
				@chmod($path . '/' . $file, 0777);
				@unlink($path . '/' . $file);
			}
		}
	}
}

/**
 * Alpha Dash
 * Check if a-z or 0-9 or _-
 * @param String
 * @return Boolean
 */
if (! function_exists('alpha_dash')) {
	function alpha_dash($str) {
		return ( ! preg_match("/^([-a-z0-9_])+$/i", $str)) ? FALSE : TRUE;
	}
}

/**
 * Slugify
 * @param String
 * @return String
 */
 if (! function_exists('slugify')) {
    function slugify( $str ) {
      $lettersNumbersSpacesHyphens = '/[^\-\s\pN\pL]+/u';
      $spacesDuplicateHypens = '/[\-\s]+/';
      $str = preg_replace($lettersNumbersSpacesHyphens, '', $str);
      $str = preg_replace($spacesDuplicateHypens, '-', $str);
      $str = trim($str, '-');
      return strtolower($str);
   }
}

/**
 * Timezone List
 * @return String
 */
if (! function_exists('timezone_list')) {
	function timezone_list() {
		static $regions = array(DateTimeZone::ASIA);
	    $timezones = [];
	    foreach( $regions as $region ) {
	        $timezones = array_merge($timezones, DateTimeZone::listIdentifiers($region));
	    }
	    $timezone_offsets = [];
	    foreach($timezones as $timezone) {
	        $tz = new DateTimeZone($timezone);
	        $timezone_offsets[$timezone] = $tz->getOffset(new DateTime);
	    }
	    asort($timezone_offsets);
	    $timezone_list = [];
	    foreach( $timezone_offsets as $timezone => $offset ) {
	        $offset_prefix = $offset < 0 ? '-' : '+';
	        $offset_formatted = gmdate( 'H:i', abs($offset) );
	        $pretty_offset = "UTC${offset_prefix}${offset_formatted}";
	        $timezone_list[$timezone] = "(${pretty_offset}) $timezone";
	    }
	    return $timezone_list;
	}
}

/**
 * __captchaActivated
 * @return Boolean
 */
if ( ! function_exists('__captchaActivated')) {
	function __captchaActivated() {
		return NULL !== __session('recaptcha_status') && __session('recaptcha_status') == 'enable';
	}
}