<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'opening_speech';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "opening_speech" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['hubungi-kami'] = 'public/contact_us';
$route['hubungi-kami/save'] = 'public/contact_us/save';
$route['read/(:num)/(:any)'] = 'public/readmore';
$route['feed'] = 'public/feed';
$route['sambutan-rektor'] = 'public/opening_speech';
$route['sambutan-ketua'] = 'public/opening_speech';
$route['sambutan-direktur'] = 'public/opening_speech';
$route['sambutan-kepala-sekolah'] = 'public/opening_speech';
$route['galeri-foto'] = 'public/gallery_photos';
$route['galeri-video'] = 'public/gallery_videos';
$route['kategori/(:any)'] = 'public/post_categories';
$route['arsip/([0-9]{4})/([0-9]{2})'] = 'public/archives';
$route['tag/(:any)'] = 'public/post_tags';
$route['download/(:any)'] = 'public/download';
$route['download/force_download/(:num)'] = 'public/download/force_download';
$route['hasil-jajak-pendapat'] = 'public/pollings';
$route['hasil-pencarian'] = 'public/search';
$route['under-construction'] = 'public/under_construction';
$route['login'] = 'login';
$route['login/(:any)'] = 'login/verify';
$route['logout'] = 'logout';
$route['lost-password'] = 'lost_password';
$route['lost-password/process'] = 'lost_password/process';
$route['reset-password/(:any)'] = 'reset_password';
$route['reset-password-process/(:any)'] = 'reset_password/process';

$route['pendaftaran-alumni'] = 'public/alumni';
$route['direktori-mahasiswa'] = 'public/student_directory';
$route['direktori-peserta-didik'] = 'public/student_directory';
$route['direktori-dosen-dan-staf'] = 'public/employee_directory';
$route['direktori-guru-dan-tenaga-kependidikan'] = 'public/employee_directory';
$route['direktori-alumni'] = 'public/alumni_directory';
$route['subscribe'] = 'public/subscribe';
$route['vote'] = 'public/pollings/save';

$route['student-registration'] = 'public/admission_form/save';
$route['admission-selection-results'] = 'public/admission_selection_results/get_results';
// PPDB
$route['formulir-penerimaan-peserta-didik-baru'] = 'public/admission_form';
$route['hasil-seleksi-penerimaan-peserta-didik-baru'] = 'public/admission_selection_results';
$route['cetak-formulir-penerimaan-peserta-didik-baru'] = 'public/print_admission_form';
$route['download-formulir-penerimaan-peserta-didik-baru'] = 'public/blank_admission_form';
// PMB
$route['formulir-penerimaan-mahasiswa-baru'] = 'public/admission_form';
$route['hasil-seleksi-penerimaan-mahasiswa-baru'] = 'public/admission_selection_results';
$route['cetak-formulir-penerimaan-mahasiswa-baru'] = 'public/print_admission_form';
$route['download-formulir-penerimaan-mahasiswa-baru'] = 'public/blank_admission_form';

$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
