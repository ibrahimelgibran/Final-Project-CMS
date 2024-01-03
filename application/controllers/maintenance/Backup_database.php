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

class Backup_database extends Admin_Controller {

	/**
	 * Class Constructor
	 *
	 * @return Void
	 */
   public function __construct() {
      parent::__construct();
      if (__session('user_type') !== 'super_user') return redirect(base_url());
   }

   /**
	 * Backup Database
	 */
	public function index() {
		$this->load->dbutil();
		$prefs = [
			'format'   => 'zip',
			'filename' => 'backup-database-on-'.date("Y-m-d H-i-s").'.sql'
		];
		$backup =& $this->dbutil->backup($prefs);
		$file_name = 'backup-database-on-'. date("Y-m-d-H-i-s") .'.zip';
		$this->zip->download($file_name);
	}
}
