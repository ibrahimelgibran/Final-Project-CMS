<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CMS Sekolahku | CMS (Content Management System) dan PPDB/PMB Online GRATIS
 * untuk sekolah SD/Sederajat, SMP/Sederajat, SMA/Sederajat, dan Perguruan Tinggi
 * @version    2.4.13
 * @author     Anton Sofyan | https://facebook.com/antonsofyan | 4ntonsofyan@gmail.com | 0857 5988 8922
 * @copyright  (c) 2014-2021
 * @link       https://sekolahku.web.id
 *
 * PERINGATAN :
 * 1. TIDAK DIPERKENANKAN MENGGUNAKAN CMS INI TANPA SEIZIN DARI PIHAK PENGEMBANG APLIKASI.
 * 2. TIDAK DIPERKENANKAN MEMPERJUALBELIKAN APLIKASI INI TANPA SEIZIN DARI PIHAK PENGEMBANG APLIKASI.
 * 3. TIDAK DIPERKENANKAN MENGHAPUS KODE SUMBER APLIKASI.
 */

class M_settings extends CI_Model {

	/**
	 * Primary key
	 * @var String
	 */
	public static $pk = 'id';

	/**
	 * Table
	 * @var String
	 */
	public static $table = 'settings';

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
	 * @param String $keyword
	 * @param Integer $limit
	 * @param Integer $offset
	 * @param String $setting_group
	 * @return Resource
	 */
	public function get_where($keyword = '', $setting_group = 'general', $return_type = 'count', $limit = 0, $offset = 0) {
		$this->db->select('id, setting_variable, COALESCE(`setting_value`, `setting_default_value`) AS setting_value, setting_description, is_deleted');
		$this->db->where('setting_group', $setting_group);
		if ( ! empty($keyword) ) {
			$this->db->group_start();
			$this->db->like('setting_description', $keyword);
			$this->db->or_like('setting_value', $keyword);
			$this->db->group_end();
		}
		if ( $return_type == 'count' ) return $this->db->count_all_results(self::$table);
		if ( $limit > 0 ) $this->db->limit($limit, $offset);
		return $this->db->get(self::$table);
	}

	/**
	 * Get Setting Values
	 * @param Array $access_group
	 * @return Array
	 */
	public function get_setting_values($access_group = 'public') {
		$this->db->select('setting_variable, COALESCE(setting_value, setting_default_value) AS setting_value');
		if ($access_group == 'public') $this->db->where('setting_access_group', 'public');
		$query = $this->db->get(self::$table);
		$settings = [];
		foreach($query->result() as $row) {
			$settings[$row->setting_variable] = $row->setting_value;
		}
		return $settings;
	}
}
