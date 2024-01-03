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

class M_admission_phases extends CI_Model {

	/**
	 * Primary key
	 * @var String
	 */
	public static $pk = 'id';

	/**
	 * Table
	 * @var String
	 */
	public static $table = 'admission_phases';

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
		$this->db->select('x1.id, x1.phase_name, x2.academic_year, x1.phase_start_date, x1.phase_end_date, x1.is_deleted');
		$this->db->join('academic_years x2', 'x1.academic_year_id = x2.id', 'LEFT');
		if ( ! empty($keyword) ) {
			$this->db->like('x1.phase_name', $keyword);
			$this->db->or_like('x2.academic_year', $keyword);
			$this->db->or_like('x1.phase_start_date', $keyword);
			$this->db->or_like('x1.phase_end_date', $keyword);
		}
		if ( $return_type == 'count' ) return $this->db->count_all_results(self::$table . ' x1');
		if ( $limit > 0 ) $this->db->limit($limit, $offset);
		return $this->db->get(self::$table . ' x1');
	}

	/**
	 * Get Current Phase / Gelombang Pendaftaran
	 * @return Array
	 */
	public function get_current_phase() {
		$query = $this->db
			->select('id, phase_name, phase_start_date, phase_end_date')
			->where('CURDATE() >= phase_start_date')
			->where('CURDATE() <= phase_end_date')
			->where('is_deleted', 'false')
			->order_by('phase_start_date', 'DESC')
			->limit(1)
			->get(self::$table);
		if ($query->num_rows() === 1) {
			$res = $query->row();
			return [
				'id' => $res->id,
				'phase_name' => $res->phase_name,
				'phase_start_date' => $res->phase_start_date,
				'phase_end_date' => $res->phase_end_date
			];
		}
		return [];
	}

	/**
	 * Dropdown
	 * @return Array
	 */
	public function dropdown() {
		$query = $this->db
			->select('id, phase_name')
			->where('is_deleted', 'false')
			->order_by('phase_name', 'DESC')
			->get(self::$table);
		$dataset = [];
		if ($query->num_rows() > 0) {
			foreach($query->result() as $row) {
				$dataset[$row->id] = $row->phase_name;
			}
		}
		return $dataset;
	}
}
