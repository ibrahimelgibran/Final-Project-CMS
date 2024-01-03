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

// Options

if (! function_exists('student_status')) {
	function student_status() {
		return [
			'Aktif',
			'Lulus',
			'Mutasi',
			'Dikeluarkan',
			'Mengundurkan Diri',
			'Putus Sekolah',
			'Meninggal',
			'Hilang',
			'Lainnya'
		];
	}
}

if (! function_exists('employments')) {
	function employments() {
		return [
			'Tidak bekerja',
			'Nelayan',
			'Petani',
			'Peternak',
			'PNS/TNI/POLRI',
			'Karyawan Swasta',
			'Pedagang Kecil',
			'Pedagang Besar',
			'Wiraswasta',
			'Wirausaha',
			'Buruh',
			'Pensiunan',
			'Lain-lain'
		];
	}
}

if (! function_exists('special_needs')) {
	function special_needs() {
		return [
			'Tidak',
			'Tuna Netra',
			'Tuna Rungu',
			'Tuna Grahita ringan',
			'Tuna Grahita Sedang',
			'Tuna Daksa Ringan',
			'Tuna Daksa Sedang',
			'Tuna Laras',
			'Tuna Wicara',
			'Tuna ganda',
			'Hiper aktif',
			'Cerdas Istimewa',
			'Bakat Istimewa',
			'Kesulitan Belajar',
			'Narkoba',
			'Indigo',
			'Down Sindrome',
			'Autis',
			'Lainnya'
		];
	}
}

if (! function_exists('educations')) {
	function educations() {
		return [
			'Tidak sekolah',
			'Putus SD',
			'SD Sederajat',
			'SMP Sederajat',
			'SMA Sederajat',
			'D1',
			'D2',
			'D3',
			'D4/S1',
			'S2',
			'S3'
		];
	}
}

if (! function_exists('scholarships')) {
	function scholarships() {
		return [
			'Anak berprestasi',
			'Anak Miskin',
			'Pendidikan',
			'Unggulan',
			'Lain-lain'
		];
	}
}

if (! function_exists('achievement_types')) {
	function achievement_types( $key = NULL ) {
		$data = [
			'1' =>'Sains',
			'2' =>'Seni',
			'3' =>'Olahraga',
			'4' =>'Lain-lain'
		];
		return is_null( $key ) ? $data : $data[ $key ];
	}
}

if (! function_exists('achievement_levels')) {
	function achievement_levels( $key = NULL ) {
		$data = [
			'1' => 'Sekolah',
			'2' => 'Kecamatan',
			'3' => 'Kota/Kabupaten',
			'4' => 'Propinsi',
			'5' => 'Nasional',
			'6' => 'Internasional'
		];
		return is_null( $key ) ? $data : $data[ $key ];
	}
}

if (! function_exists('monthly_incomes')) {
	function monthly_incomes() {
		return [
			'Kurang dari 500,000',
			'500.000 - 999.9999',
			'1 Juta - 1.999.999',
			'2 Juta - 4.999.999',
			'5 Juta - 20 Juta',
			'Lebih dari 20 Juta'
		];
	}
}

if (! function_exists('residences')) {
	function residences() {
		return [
			'Bersama orang tua',
			'Wali',
			'Kos',
			'Asrama',
			'Panti Asuhan',
			'Lainnya'
		];
	}
}

if (! function_exists('transportations')) {
	function transportations() {
		return [
			'Jalan kaki',
			'Kendaraan pribadi',
			'Kendaraan Umum / angkot / Pete-pete',
			'Jemputan Sekolah',
			'Kereta Api',
			'Ojek',
			'Andong / Bendi / Sado / Dokar / Delman / Beca',
			'Perahu penyebrangan / Rakit / Getek',
			'Lainnya'
		];
	}
}

if (! function_exists('religions')) {
	function religions() {
		return [
			'Islam',
			'Kristen / protestan',
			'Katholik',
			'Hindu',
			'Budha',
			'Khong Hu Chu',
			'Lainnya'
		];
	}
}

/**
* Jenjang Sekolah
*/
if (! function_exists('school_levels')) {
	function school_levels() {
		return [
			'1 - Sekolah Dasar (SD) / Sederajat', // SD
			'2 - Sekolah Menengah Pertama (SMP) / Sederajat', // SMP
			'3 - Sekolah Menengah Atas (SMA) / Aliyah', // SMA
			'4 - Sekolah Menengah Kejuruan (SMK)', // SMK
			'5 - Universitas', // Universitas
			'6 - Sekolah Tinggi', // Sekolah Tinggi
			'7 - Politeknik' // Politeknik
		];
	}
}

/**
* Marital Status
*/
if (! function_exists('marriage_status')) {
	function marriage_status() {
		return [
			'Kawin',
			'Belum Kawin',
			'Berpisah'
		];
	}
}

/**
* Lembaga Pengangkat
*/
if (! function_exists('institution_lifters')) {
	function institution_lifters() {
		return [
			'Pemerintah Pusat',
			'Pemerintah Provinsi',
			'Pemerintah Kab/Kota',
			'Ketua yayasan',
			'Kepala Sekolah',
			'Komite Sekolah',
			'Lainnya'
		];
	}
}

/**
* Lembaga Pengangkat
*/
if (! function_exists('employment_status')) {
	function employment_status() {
		return [
			'PNS ',
			'PNS Diperbantukan ',
			'PNS DEPAG ',
			'GTY/PTY ',
			'GTT/PTT Provinsi ',
			'GTT/PTT Kota/Kabupaten',
			'Guru Bantu Pusat ',
			'Guru Honor Sekolah ',
			'Tenaga Honor Sekolah ',
			'CPNS',
			'Lainnya'
		];
	}
}

/**
* Jenis Pendidik dan Tenaga Kependidikan (GTK)
*/
if (! function_exists('employment_types')) {
	function employment_types() {
		return [
			'Guru Kelas',
			'Guru Mata Pelajaran',
			'Guru BK',
			'Guru Inklusi',
			'Tenaga Administrasi Sekolah',
			'Guru Pendamping',
			'Guru Magang',
			'Guru TIK',
			'Laboran',
			'Pustakawan',
			'Lainnya'
		];
	}
}

/**
* Golongan
*/
if (! function_exists('ranks')) {
	function ranks() {
		return [
			'I/A',
			'I/B',
			'I/C',
			'I/D',
			'II/A',
			'II/B',
			'II/C',
			'II/D',
			'III/A',
			'III/B',
			'III/C',
			'III/D',
			'IV/A',
			'IV/B',
			'IV/C',
			'IV/D',
			'IV/E'
		];
	}
}

/**
* Sumber Gaji
*/
if (! function_exists('salary_sources')) {
	function salary_sources() {
		return [
			'APBN',
			'APBD Provinsi',
			'APBD Kab/Kota',
			'Yayasan',
			'Sekolah',
			'Lembaga Donor',
			'Lainnya'
		];
	}
}

/**
* Keahlian Laboratorium
*/
if (! function_exists('laboratory_skills')) {
	function laboratory_skills() {
		return [
			'Lab IPA',
			'Lab Fisika',
			'Lab Biologi',
			'Lab Kimia',
			'Lab Bahasa',
			'Lab Komputer',
			'Teknik Bangunan',
			'Teknik Survei & Pemetaan',
			'Teknik Ketenagakerjaan',
			'Teknik Pendinginan & Tata Udara',
			'Teknik Mesin'
		];
	}
}

if (! function_exists('scholarship_types')) {
	function scholarship_types($key = '') {
		$data = [
			'1' => 'Anak Berprestasi',
			'2' => 'Anak Miskin',
			'3' => 'Pendidikan',
			'4' => 'Unggulan',
			'5' => 'Lain-lain'
		];
		return $key !== '' ? $data[ $key ] : $data;
	}
}

if (! function_exists('modules')) {
	function modules($key = '') {
		$CI = &get_instance();
		$modules = [
			'hubungi-kami' => 'Hubungi Kami',
			'galeri-foto' => 'Galeri Foto',
			'galeri-video' => 'Galeri Video',
			'pendaftaran-alumni' => 'Pendaftaran Alumni',
			'direktori-alumni' => 'Direktori Alumni'
		];
		// '1 - Sekolah Dasar (SD)/ Sederajat', // SD
		// '2 - Sekolah Menengah Pertama (SMP)/ Sederajat', // SMP
		// '3 - Sekolah Menengah Atas (SMA) / Aliyah', // SMA
		// '4 - Sekolah Menengah Kejuruan (SMK)', // SMK
		// '5 - Universitas', // Universitas
		// '6 - Sekolah Tinggi', // Sekolah Tinggi
		// '7 - Politeknik' // Politeknik
		if ($CI->session->userdata('school_level') >= 5) {
			$modules['direktori-mahasiswa'] = 'Direktori Mahasiswa';
			$modules['direktori-dosen-dan-staf'] = 'Direktori Dosen dan Staff';
			$modules['formulir-penerimaan-mahasiswa-baru'] = 'Formulir PMB';
			$modules['hasil-seleksi-penerimaan-mahasiswa-baru'] = 'Hasil Seleksi PMB';
			$modules['cetak-formulir-penerimaan-mahasiswa-baru'] = 'Cetak Formulir PMB';
			$modules['download-formulir-penerimaan-mahasiswa-baru'] = 'Download Formulir PMB';
		} else {
			$modules['direktori-peserta-didik'] = 'Direktori Peserta Didik';
			$modules['direktori-guru-dan-tenaga-kependidikan'] = 'Direktori Guru dan Tenaga Kependidikan';
			$modules['formulir-penerimaan-peserta-didik-baru'] = 'Formulir PPDB';
			$modules['hasil-seleksi-penerimaan-peserta-didik-baru'] = 'Hasil Seleksi PPDB';
			$modules['cetak-formulir-penerimaan-peserta-didik-baru'] = 'Cetak Formulir PPDB';
			$modules['download-formulir-penerimaan-peserta-didik-baru'] = 'Download Formulir PPDB';
		}
		return $key == '' ? $modules : $modules[$key];
	}
}
