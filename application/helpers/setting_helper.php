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

// Settings
if (! function_exists('general')) {
	function general() {
		return [
			[
				'setting_group' => 'general',
				'setting_variable' => 'site_maintenance',
				'setting_default_value' => 'false',
				'setting_access_group' => 'public',
				'setting_description' => 'Pemeliharaan situs'
			],
			[
				'setting_group' => 'general',
				'setting_variable' => 'site_maintenance_end_date',
				'setting_default_value' => '2022-01-01',
				'setting_access_group' => 'public',
				'setting_description' => 'Tanggal Berakhir Pemeliharaan Situs'
			],
			[
				'setting_group' => 'general',
				'setting_variable' => 'site_cache',
				'setting_default_value' => 'false',
				'setting_access_group' => 'public',
				'setting_description' => 'Cache situs'
			],
			[
				'setting_group' => 'general',
				'setting_variable' => 'site_cache_time',
				'setting_default_value' => 10,
				'setting_access_group' => 'public',
				'setting_description' => 'Lama Cache Situs'
			],
			[
				'setting_group' => 'general',
				'setting_variable' => 'meta_description',
				'setting_default_value' => 'CMS Sekolahku adalah Content Management System dan PPDB Online gratis untuk SD SMP/Sederajat SMA/Sederajat',
				'setting_access_group' => 'public',
				'setting_description' => 'Deskripsi Meta'
			],
			[
				'setting_group' => 'general',
				'setting_variable' => 'meta_keywords',
				'setting_default_value' => 'CMS, Website Sekolah Gratis, Cara Membuat Website Sekolah, membuat web sekolah, contoh website sekolah, fitur website sekolah, Sekolah, Website, Internet,Situs, CMS Sekolah, Web Sekolah, Website Sekolah Gratis, Website Sekolah, Aplikasi Sekolah, PPDB Online, PSB Online, PSB Online Gratis, Penerimaan Siswa Baru Online, Raport Online, Kurikulum 2013, SD, SMP, SMA, Aliyah, MTs, SMK',
				'setting_access_group' => 'public',
				'setting_description' => 'Kata Kunci Meta'
			],
			[
				'setting_group' => 'general',
				'setting_variable' => 'map_location',
				'setting_default_value' => '',
				'setting_access_group' => 'public',
				'setting_description' => 'Lokasi di Google Maps'
			],
			[
				'setting_group' => 'general',
				'setting_variable' => 'favicon',
				'setting_default_value' => 'favicon.png',
				'setting_access_group' => 'public',
				'setting_description' => 'Favicon'
			],
			[
				'setting_group' => 'general',
				'setting_variable' => 'header',
				'setting_default_value' => 'header.png',
				'setting_access_group' => 'public',
				'setting_description' => 'Gambar Header'
			],
			[
				'setting_group' => 'general',
				'setting_variable' => 'recaptcha_status',
				'setting_default_value' => 'disable',
				'setting_access_group' => 'public',
				'setting_description' => 'reCAPTCHA Status'
			],
			[
				'setting_group' => 'general',
				'setting_variable' => 'recaptcha_site_key',
				'setting_default_value' => '6LeNCTAUAAAAAADTbL1rDw8GT1DF2DUjVtEXzdMu',
				'setting_access_group' => 'public',
				'setting_description' => 'Recaptcha Site Key'
			],
			[
				'setting_group' => 'general',
				'setting_variable' => 'recaptcha_secret_key',
				'setting_default_value' => '6LeNCTAUAAAAAGq8O0ItkzG8fsA9KeJ7mFMMFF1s',
				'setting_access_group' => 'public',
				'setting_description' => 'Recaptcha Secret Key'
			],
			[
				'setting_group' => 'general',
				'setting_variable' => 'timezone',
				'setting_default_value' => 'Asia/Jakarta',
				'setting_access_group' => 'public',
				'setting_description' => 'Time Zone'
			]
		];
	}
}

if (! function_exists('media')) {
	function media() {
		return [
			[
				'setting_group' => 'media',
				'setting_variable' => 'file_allowed_types',
				'setting_default_value' => 'jpg, jpeg, png, gif',
				'setting_access_group' => 'public',
				'setting_description' => 'Tipe file yang diizinkan'
			],
			[
				'setting_group' => 'media',
				'setting_variable' => 'upload_max_filesize',
				'setting_default_value' => 0,
				'setting_access_group' => 'public',
				'setting_description' => 'Maksimal Ukuran File yang Diupload'
			],
			[
				'setting_group' => 'media',
				'setting_variable' => 'thumbnail_size_height',
				'setting_default_value' => 100,
				'setting_access_group' => 'public',
				'setting_description' => 'Tinggi Gambar Thumbnail'
			],
			[
				'setting_group' => 'media',
				'setting_variable' => 'thumbnail_size_width',
				'setting_default_value' => 150,
				'setting_access_group' => 'public',
				'setting_description' => 'Lebar Gambar Thumbnail'
			],
			[
				'setting_group' => 'media',
				'setting_variable' => 'medium_size_height',
				'setting_default_value' => 308,
				'setting_access_group' => 'public',
				'setting_description' => 'Tinggi Gambar Sedang'
			],
			[
				'setting_group' => 'media',
				'setting_variable' => 'medium_size_width',
				'setting_default_value' => 460,
				'setting_access_group' => 'public',
				'setting_description' => 'Lebar Gambar Sedang'
			],
			[
				'setting_group' => 'media',
				'setting_variable' => 'large_size_height',
				'setting_default_value' => 600,
				'setting_access_group' => 'public',
				'setting_description' => 'Tinggi Gambar Besar'
			],
			[
				'setting_group' => 'media',
				'setting_variable' => 'large_size_width',
				'setting_default_value' => 800,
				'setting_access_group' => 'public',
				'setting_description' => 'Lebar Gambar Besar'
			],
			[
				'setting_group' => 'media',
				'setting_variable' => 'album_cover_height',
				'setting_default_value' => 250,
				'setting_access_group' => 'public',
				'setting_description' => 'Tinggi Cover Album Foto'
			],
			[
				'setting_group' => 'media',
				'setting_variable' => 'album_cover_width',
				'setting_default_value' => 400,
				'setting_access_group' => 'public',
				'setting_description' => 'Lebar Cover Album Foto'
			],
			[
				'setting_group' => 'media',
				'setting_variable' => 'banner_height',
				'setting_default_value' => 81,
				'setting_access_group' => 'public',
				'setting_description' => 'Tinggi Iklan'
			],
			[
				'setting_group' => 'media',
				'setting_variable' => 'banner_width',
				'setting_default_value' => 245,
				'setting_access_group' => 'public',
				'setting_description' => 'Lebar Iklan'
			],
			[
				'setting_group' => 'media',
				'setting_variable' => 'image_slider_height',
				'setting_default_value' => 400,
				'setting_access_group' => 'public',
				'setting_description' => 'Tinggi Gambar Slide'
			],
			[
				'setting_group' => 'media',
				'setting_variable' => 'image_slider_width',
				'setting_default_value' => 900,
				'setting_access_group' => 'public',
				'setting_description' => 'Lebar Gambar Slide'
			],
			[
				'setting_group' => 'media',
				'setting_variable' => 'student_photo_height',
				'setting_default_value' => 400,
				'setting_access_group' => 'public',
				'setting_description' => 'Tinggi Photo Peserta Didik'
			],
			[
				'setting_group' => 'media',
				'setting_variable' => 'student_photo_width',
				'setting_default_value' => 300,
				'setting_access_group' => 'public',
				'setting_description' => 'Lebar Photo Peserta Didik'
			],
			[
				'setting_group' => 'media',
				'setting_variable' => 'employee_photo_height',
				'setting_default_value' => 400,
				'setting_access_group' => 'public',
				'setting_description' => 'Tinggi Photo Guru dan Tenaga Kependidikan'
			],
			[
				'setting_group' => 'media',
				'setting_variable' => 'employee_photo_width',
				'setting_default_value' => 300,
				'setting_access_group' => 'public',
				'setting_description' => 'Lebar Photo Guru dan Tenaga Kependidikan'
			],
			[
				'setting_group' => 'media',
				'setting_variable' => 'headmaster_photo_height',
				'setting_default_value' => 400,
				'setting_access_group' => 'public',
				'setting_description' => 'Tinggi Photo Kepala Sekolah'
			],
			[
				'setting_group' => 'media',
				'setting_variable' => 'headmaster_photo_width',
				'setting_default_value' => 300,
				'setting_access_group' => 'public',
				'setting_description' => 'Lebar Photo Kepala Sekolah'
			],
			[
				'setting_group' => 'media',
				'setting_variable' => 'header_height',
				'setting_default_value' => 80,
				'setting_access_group' => 'public',
				'setting_description' => 'Tinggi Gambar Header'
			],
			[
				'setting_group' => 'media',
				'setting_variable' => 'header_width',
				'setting_default_value' => 200,
				'setting_access_group' => 'public',
				'setting_description' => 'Lebar Gambar Header'
			],
			[
				'setting_group' => 'media',
				'setting_variable' => 'logo_height',
				'setting_default_value' => 120,
				'setting_access_group' => 'public',
				'setting_description' => 'Tinggi Logo Sekolah'
			],
			[
				'setting_group' => 'media',
				'setting_variable' => 'logo_width',
				'setting_default_value' => 120,
				'setting_access_group' => 'public',
				'setting_description' => 'Lebar Logo Sekolah'
			]
		];
	}
}

if (! function_exists('writing')) {
	function writing() {
		return [
			[
				'setting_group' => 'writing',
				'setting_variable' => 'default_post_category',
				'setting_default_value' => 1,
				'setting_access_group' => 'public',
				'setting_description' => 'Default Kategori Tulisan'
			],
			[
				'setting_group' => 'writing',
				'setting_variable' => 'default_post_status',
				'setting_default_value' => 'publish',
				'setting_access_group' => 'public',
				'setting_description' => 'Default Status Tulisan'
			],
			[
				'setting_group' => 'writing',
				'setting_variable' => 'default_post_visibility',
				'setting_default_value' => 'public',
				'setting_access_group' => 'public',
				'setting_description' => 'Default Akses Tulisan'
			],
			[
				'setting_group' => 'writing',
				'setting_variable' => 'default_post_discussion',
				'setting_default_value' => 'open',
				'setting_access_group' => 'public',
				'setting_description' => 'Default Komentar Tulisan'
			],
			[
				'setting_group' => 'writing',
				'setting_variable' => 'post_image_thumbnail_height',
				'setting_default_value' => 100,
				'setting_access_group' => 'public',
				'setting_description' => 'Tinggi Gambar Kecil'
			],
			[
				'setting_group' => 'writing',
				'setting_variable' => 'post_image_thumbnail_width',
				'setting_default_value' => 150,
				'setting_access_group' => 'public',
				'setting_description' => 'Lebar Gambar Kecil'
			],
			[
				'setting_group' => 'writing',
				'setting_variable' => 'post_image_medium_height',
				'setting_default_value' => 250,
				'setting_access_group' => 'public',
				'setting_description' => 'Tinggi Gambar Sedang'
			],
			[
				'setting_group' => 'writing',
				'setting_variable' => 'post_image_medium_width',
				'setting_default_value' => 400,
				'setting_access_group' => 'public',
				'setting_description' => 'Lebar Gambar Sedang'
			],
			[
				'setting_group' => 'writing',
				'setting_variable' => 'post_image_large_height',
				'setting_default_value' => 450,
				'setting_access_group' => 'public',
				'setting_description' => 'Tinggi Gambar Besar'
			],
			[
				'setting_group' => 'writing',
				'setting_variable' => 'post_image_large_width',
				'setting_default_value' => 840,
				'setting_access_group' => 'public',
				'setting_description' => 'Lebar Gambar Besar'
			],
		];
	}
}

if (! function_exists('reading')) {
	function reading() {
		return [
			[
				'setting_group' => 'reading',
				'setting_variable' => 'post_per_page',
				'setting_default_value' => 10,
				'setting_access_group' => 'public',
				'setting_description' => 'Tulisan per halaman'
			],
			[
				'setting_group' => 'reading',
				'setting_variable' => 'post_rss_count',
				'setting_default_value' => 10,
				'setting_access_group' => 'public',
				'setting_description' => 'Jumlah RSS'
			],
			[
				'setting_group' => 'reading',
				'setting_variable' => 'post_related_count',
				'setting_default_value' => 10,
				'setting_access_group' => 'public',
				'setting_description' => 'Jumlah Tulisan Terkait'
			],
			[
				'setting_group' => 'reading',
				'setting_variable' => 'comment_per_page',
				'setting_default_value' => 10,
				'setting_access_group' => 'public',
				'setting_description' => 'Komentar per halaman'
			]
		];
	}
}

if (! function_exists('discussion')) {
	function discussion() {
		return [
			[
				'setting_group' => 'discussion',
				'setting_variable' => 'comment_moderation',
				'setting_default_value' => 'false',
				'setting_access_group' => 'public',
				'setting_description' => 'Komentar harus disetujui secara manual'
			],
			[
				'setting_group' => 'discussion',
				'setting_variable' => 'comment_registration',
				'setting_default_value' => 'false',
				'setting_access_group' => 'public',
				'setting_description' => 'Pengguna harus terdaftar dan login untuk komentar'
			],
			[
				'setting_group' => 'discussion',
				'setting_variable' => 'comment_blacklist',
				'setting_default_value' => 'kampret',
				'setting_access_group' => 'public',
				'setting_description' => 'Komentar disaring'
			],
			[
				'setting_group' => 'discussion',
				'setting_variable' => 'comment_order',
				'setting_default_value' => 'asc',
				'setting_access_group' => 'public',
				'setting_description' => 'Urutan Komentar'
			]
		];
	}
}

if (! function_exists('social_account')) {
	function social_account() {
		return [
			[
				'setting_group' => 'social_account',
				'setting_variable' => 'facebook',
				'setting_default_value' => '',
				'setting_access_group' => 'public',
				'setting_description' => 'Facebook'
			],
			[
				'setting_group' => 'social_account',
				'setting_variable' => 'twitter',
				'setting_default_value' => '',
				'setting_access_group' => 'public',
				'setting_description' => 'Twitter'
			],
			[
				'setting_group' => 'social_account',
				'setting_variable' => 'linked_in',
				'setting_default_value' => '',
				'setting_access_group' => 'public',
				'setting_description' => 'Linked In'
			],
			[
				'setting_group' => 'social_account',
				'setting_variable' => 'youtube',
				'setting_default_value' => '',
				'setting_access_group' => 'public',
				'setting_description' => 'Youtube'
			],
			[
				'setting_group' => 'social_account',
				'setting_variable' => 'instagram',
				'setting_default_value' => '',
				'setting_access_group' => 'public',
				'setting_description' => 'Instagram'
			]
		];
	}
}

if (! function_exists('mail_server')) {
	function mail_server() {
		return [
			[
				'setting_group' => 'mail_server',
				'setting_variable' => 'smtp_host',
				'setting_default_value' => '',
				'setting_access_group' => 'private',
				'setting_description' => 'SMTP Server Address'
			],
			[
				'setting_group' => 'mail_server',
				'setting_variable' => 'smtp_user',
				'setting_default_value' => '',
				'setting_access_group' => 'private',
				'setting_description' => 'SMTP Username'
			],
			[
				'setting_group' => 'mail_server',
				'setting_variable' => 'smtp_pass',
				'setting_default_value' => '',
				'setting_access_group' => 'private',
				'setting_description' => 'SMTP Password'
			],
			[
				'setting_group' => 'mail_server',
				'setting_variable' => 'smtp_port',
				'setting_default_value' => '',
				'setting_access_group' => 'public',
				'setting_description' => 'SMTP Port'
			]
		];
	}
}

if (! function_exists('admission')) {
	function admission() {
		return [
			[
				'setting_group' => 'admission',
				'setting_variable' => 'admission_status',
				'setting_default_value' => 'open',
				'setting_access_group' => 'public',
				'setting_description' => 'Status Penerimaan Peserta Didik Baru'
			],
			[
				'setting_group' => 'admission',
				'setting_variable' => 'admission_year',
				'setting_default_value' => date('Y'),
				'setting_access_group' => 'public',
				'setting_description' => 'Tahun Penerimaan Peserta Didik Baru'
			],
			[
				'setting_group' => 'admission',
				'setting_variable' => 'admission_start_date',
				'setting_default_value' => '2022-01-01',
				'setting_access_group' => 'public',
				'setting_description' => 'Tanggal Mulai PPDB'
			],
			[
				'setting_group' => 'admission',
				'setting_variable' => 'admission_end_date',
				'setting_default_value' => '2022-12-31',
				'setting_access_group' => 'public',
				'setting_description' => 'Tanggal Selesai PPDB'
			],
			[
				'setting_group' => 'admission',
				'setting_variable' => 'announcement_start_date',
				'setting_default_value' => '2022-01-01',
				'setting_access_group' => 'public',
				'setting_description' => 'Tanggal Mulai Pengumuman Hasil Seleksi PPDB'
			],
			[
				'setting_group' => 'admission',
				'setting_variable' => 'announcement_end_date',
				'setting_default_value' => '2022-12-31',
				'setting_access_group' => 'public',
				'setting_description' => 'Tanggal Selesai Pengumuman Hasil Seleksi PPDB'
			]
		];
	}
}

if (! function_exists('school_profile')) {
	function school_profile() {
		return [
			[
				'setting_group' => 'school_profile',
				'setting_variable' => 'npsn',
				'setting_default_value' => 123,
				'setting_access_group' => 'public',
				'setting_description' => 'NPSN'
			],
			[
				'setting_group' => 'school_profile',
				'setting_variable' => 'school_name',
				'setting_default_value' => 'SMA Negeri 9 Kuningan',
				'setting_access_group' => 'public',
				'setting_description' => 'Nama Sekolah'
			],
			[
				'setting_group' => 'school_profile',
				'setting_variable' => 'headmaster',
				'setting_default_value' => 'Anton Sofyan',
				'setting_access_group' => 'public',
				'setting_description' => 'Kepala Sekolah'
			],
			[
				'setting_group' => 'school_profile',
				'setting_variable' => 'headmaster_photo',
				'setting_default_value' => 'headmaster_photo.png',
				'setting_access_group' => 'public',
				'setting_description' => 'Photo Kepala Sekolah'
			],
			[
				'setting_group' => 'school_profile',
				'setting_variable' => 'school_level',
				'setting_default_value' => 3,
				'setting_access_group' => 'public',
				'setting_description' => 'Bentuk Pendidikan'
			],
			[
				'setting_group' => 'school_profile',
				'setting_variable' => 'school_status',
				'setting_default_value' => 1,
				'setting_access_group' => 'public',
				'setting_description' => 'Status Sekolah'
			],
			[
				'setting_group' => 'school_profile',
				'setting_variable' => 'ownership_status',
				'setting_default_value' => 1,
				'setting_access_group' => 'public',
				'setting_description' => 'Status Kepemilikan'
			],
			[
				'setting_group' => 'school_profile',
				'setting_variable' => 'decree_operating_permit',
				'setting_default_value' => '-',
				'setting_access_group' => 'public',
				'setting_description' => 'SK Izin Operasional'
			],
			[
				'setting_group' => 'school_profile',
				'setting_variable' => 'decree_operating_permit_date',
				'setting_default_value' => date('Y-m-d'),
				'setting_access_group' => 'public',
				'setting_description' => 'Tanggal SK Izin Operasional'
			],
			[
				'setting_group' => 'school_profile',
				'setting_variable' => 'tagline',
				'setting_default_value' => 'Where Tomorrow\'s Leaders Come Together',
				'setting_access_group' => 'public',
				'setting_description' => 'Slogan'
			],
			[
				'setting_group' => 'school_profile',
				'setting_variable' => 'rt',
				'setting_default_value' => 12,
				'setting_access_group' => 'public',
				'setting_description' => 'RT'
			],
			[
				'setting_group' => 'school_profile',
				'setting_variable' => 'rw',
				'setting_default_value' => '06',
				'setting_access_group' => 'public',
				'setting_description' => 'RW'
			],
			[
				'setting_group' => 'school_profile',
				'setting_variable' => 'sub_village',
				'setting_default_value' => 'Wage',
				'setting_access_group' => 'public',
				'setting_description' => 'Dusun'
			],
			[
				'setting_group' => 'school_profile',
				'setting_variable' => 'village',
				'setting_default_value' => 'Kadugede',
				'setting_access_group' => 'public',
				'setting_description' => 'Kelurahan / Desa'
			],
			[
				'setting_group' => 'school_profile',
				'setting_variable' => 'sub_district',
				'setting_default_value' => 'Kadugede',
				'setting_access_group' => 'public',
				'setting_description' => 'Kecamatan'
			],
			[
				'setting_group' => 'school_profile',
				'setting_variable' => 'district',
				'setting_default_value' => 'Kuningan',
				'setting_access_group' => 'public',
				'setting_description' => 'Kota/Kabupaten'
			],
			[
				'setting_group' => 'school_profile',
				'setting_variable' => 'postal_code',
				'setting_default_value' => 45561,
				'setting_access_group' => 'public',
				'setting_description' => 'Kode Pos'
			],
			[
				'setting_group' => 'school_profile',
				'setting_variable' => 'street_address',
				'setting_default_value' => 'Jalan Raya Kadugede No. 11',
				'setting_access_group' => 'public',
				'setting_description' => 'Alamat Jalan'
			],
			[
				'setting_group' => 'school_profile',
				'setting_variable' => 'phone',
				'setting_default_value' => '0232123456',
				'setting_access_group' => 'public',
				'setting_description' => 'Telepon'
			],
			[
				'setting_group' => 'school_profile',
				'setting_variable' => 'fax',
				'setting_default_value' => '0232123456',
				'setting_access_group' => 'public',
				'setting_description' => 'Fax'
			],
			[
				'setting_group' => 'school_profile',
				'setting_variable' => 'email',
				'setting_default_value' => 'info@sman9kuningan.sch.id',
				'setting_access_group' => 'public',
				'setting_description' => 'Email'
			],
			[
				'setting_group' => 'school_profile',
				'setting_variable' => 'website',
				'setting_default_value' => 'http://www.sman9kuningan.sch.id',
				'setting_access_group' => 'public',
				'setting_description' => 'Website'
			],
			[
				'setting_group' => 'school_profile',
				'setting_variable' => 'logo',
				'setting_default_value' => 'logo.png',
				'setting_access_group' => 'public',
				'setting_description' => 'Logo'
			]
		];
	}
}
