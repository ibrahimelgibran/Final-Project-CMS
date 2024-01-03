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

class M_class_groups extends CI_Model {

	/**
	 * Primary key
	 * @var String
	 */
	public static $pk = 'id';

	/**
	 * Table
	 * @var String
	 */
	public static $table = 'class_groups';

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
	 * @param String $return_type
	 * @param Integer $limit
	 * @param Integer $offset
	 * @return Resource
	 */
	public function get_where($keyword = '', $return_type = 'count', $limit = 0, $offset = 0) {
		$this->db->select("
			x1.id
			, CONCAT(x1.class_group, IF((x2.major_short_name <> ''), CONCAT(' ',x2.major_short_name),''),IF((x1.sub_class_group <> ''),CONCAT(' - ',x1.sub_class_group),'')) AS class_name
			, x1.is_deleted
		");
		$this->db->join('majors x2', 'x1.major_id = x2.id', 'LEFT');
		if ( ! empty($keyword) ) {
			$this->db->like("CONCAT(x1.class_group, IF((x2.major_short_name <> ''), CONCAT(' ',x2.major_short_name),''),IF((x1.sub_class_group <> ''),CONCAT(' - ',x1.sub_class_group),''))", $keyword);
		}
		$this->db->order_by('x1.class_group', 'ASC');
		$this->db->order_by('x1.major_id', 'ASC');
		$this->db->order_by('x1.sub_class_group', 'ASC');
		if ( $return_type == 'count' ) return $this->db->count_all_results(self::$table . ' x1');
		if ( $limit > 0 ) $this->db->limit($limit, $offset);
		return $this->db->get(self::$table . ' x1');
	}

	/**
	 * Dropdown
	 * @return Array
	 */
	public function dropdown() {
		$query = $this->db
			->select("x1.id, CONCAT(x1.class_group, IF((x2.major_short_name <> ''), CONCAT(' ',x2.major_short_name),''),IF((x1.sub_class_group <> ''),CONCAT(' - ',x1.sub_class_group),'')) AS class_name")
			->join('majors x2', 'x1.major_id = x2.id', 'LEFT')
			->where('x1.is_deleted', 'false')
			->order_by('x1.class_group', 'ASC')
			->order_by('x1.major_id', 'ASC')
			->order_by('x1.sub_class_group', 'ASC')
			->get(self::$table. ' x1');
		$dataset = [];
		if ($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$dataset[$row->id] = $row->class_name;
			}
		}
		return $dataset;
	}
}
