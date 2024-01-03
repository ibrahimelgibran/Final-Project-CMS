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

/**
 * Get Active Theme
 */
if ( ! function_exists('theme_folder')) {
	function theme_folder() {
		$CI = &get_instance();
		return $CI->session->userdata('theme');
	}
}

/**
 * Get Links
 * @param Integer $limit
 * @return Resource
 */
if ( ! function_exists('get_links')) {
	function get_links( $limit = 0 ) {
		$CI = &get_instance();
		$CI->load->model('m_links');
		return $CI->m_links->get_links( $limit );
	}
}

/**
 * Get Tags
 * @param Integer $limit
 * @return Resource
 */
if ( ! function_exists('get_tags')) {
	function get_tags( $limit = 0 ) {
		$CI = &get_instance();
		$CI->load->model('m_tags');
		return $CI->m_tags->get_tags( $limit, TRUE);
	}
}

/**
 * Get Banners
 * @param Integer $limit
 * @return Resource
 */
if ( ! function_exists('get_banners')) {
	function get_banners( $limit = 0 ) {
		$CI = &get_instance();
		$CI->load->model('m_banners');
		return $CI->m_banners->get_banners( $limit );
	}
}

/**
 * Get Archive Year
 */
if ( ! function_exists('get_years')) {
	function get_years() {
		$CI = &get_instance();
		$CI->load->model('public/m_posts');
		return $CI->m_posts->get_years();
	}
}

/**
 * Get Archives
 * @param Integer $year
 */
if ( ! function_exists('get_archives')) {
	function get_archives( $year ) {
		$CI = &get_instance();
		$CI->load->model('public/m_posts');
		return $CI->m_posts->get_archives( $year );
	}
}

/**
 * Get Quotes
 */
if ( ! function_exists('get_quotes')) {
	function get_quotes( $limit = 0 ) {
		$CI = &get_instance();
		$CI->load->model('m_quotes');
		return $CI->m_quotes->get_quotes( $limit );
	}
}

/**
 * Get Image Sliders
 * @param Integer $limit
 * @return Resource
 */
if ( ! function_exists('get_image_sliders')) {
	function get_image_sliders( $limit = 0 ) {
		$CI = &get_instance();
		$CI->load->model('m_image_sliders');
		return $CI->m_image_sliders->get_image_sliders( $limit );
	}
}

/**
 * Get Question
 */
if ( ! function_exists('get_active_question')) {
	function get_active_question() {
		$CI = &get_instance();
		$CI->load->model('m_questions');
		return $CI->m_questions->get_active_question();
	}
}

/**
 * Get Answears
 * @param Integer $question_id
 * @return Resource
 */
if ( ! function_exists('get_answers')) {
	function get_answers( $question_id ) {
		$CI = &get_instance();
		$CI->load->model('m_answers');
		return $CI->m_answers->get_answers( $question_id );
	}
}

/**
 * Get Recent Posts
 * @param Integer $limit
 * @return Resource
 */
if ( ! function_exists('get_latest_posts')) {
	function get_latest_posts( $limit = 0 ) {
		$CI = &get_instance();
		$CI->load->model('public/m_posts');
		return $CI->m_posts->get_latest_posts( $limit );
	}
}

/**
 * Get Popular Posts
 * @param Integer $limit
 * @return Resource
 */
if ( ! function_exists('get_popular_posts')) {
	function get_popular_posts( $limit = 0 ) {
		$CI = &get_instance();
		$CI->load->model('public/m_posts');
		return $CI->m_posts->get_popular_posts( $limit );
	}
}

/**
 * Get Most Commented
 * @param Integer $limit
 * @return Resource
 */
if ( ! function_exists('get_most_commented')) {
	function get_most_commented( $limit = 0 ) {
		$CI = &get_instance();
		$CI->load->model('public/m_posts');
		return $CI->m_posts->get_most_commented( $limit );
	}
}

/**
 * Get Random Posts
 * @param Integer $limit
 * @return Resource
 */
if ( ! function_exists('get_random_posts')) {
	function get_random_posts( $limit = 0 ) {
		$CI = &get_instance();
		$CI->load->model('public/m_posts');
		return $CI->m_posts->get_random_posts( $limit );
	}
}

/**
 * Get post category
 * @param String $category_slug
 * @param Integer $limit
 * @return Resource
 */
if ( ! function_exists('get_post_categories')) {
	function get_post_categories( $category_slug, $limit = 0 ) {
		$CI = &get_instance();
		$CI->load->model('public/m_posts');
		return $CI->m_posts->get_post_categories( $category_slug, $limit );
	}
}

/**
 * Get Categories
 * @param String $category_type
  * @param Integer $limit
 * @return Resource
 */
if ( ! function_exists('get_categories')) {
	function get_categories( $category_type, $limit = 0 ) {
		$CI = &get_instance();
		$CI->load->model('m_categories');
		return $CI->m_categories->get_categories( $category_type, $limit );
	}
}

/**
 * Get Related Posts
 * @param String $categories
 * @param Integer $id
 * @return Resource
 */
if ( ! function_exists('get_related_posts')) {
	function get_related_posts( $categories, $id ) {
		$CI = &get_instance();
		$CI->load->model('public/m_posts');
		return $CI->m_posts->get_related_posts( $categories, $id );
	}
}

/**
 * Get Another Pages
 * @param Integer $id
 * @param Integer $limit
 * @return Resource
 */
if ( ! function_exists('get_another_pages')) {
	function get_another_pages( $id, $limit = 0 ) {
		$CI = &get_instance();
		$CI->load->model('public/m_posts');
		return $CI->m_posts->get_another_pages( $id, $limit );
	}
}

/**
 * Get Recent Comments
 * @param Integer $limit
 * @return Resource
 */
if ( ! function_exists('get_recent_comments')) {
	function get_recent_comments( $limit = 0 ) {
		$CI = &get_instance();
		$CI->load->model('m_post_comments');
		return $CI->m_post_comments->get_recent_comments( $limit );
	}
}

/**
 * opening_speech | Sambutan Kepala Sekolah
 * @return String
 */
if ( ! function_exists('get_opening_speech')) {
	function get_opening_speech() {
		$CI = &get_instance();
		$CI->load->model('public/m_posts');
		return $CI->m_posts->get_opening_speech();
	}
}

/**
 * Get Recent Videos
 * @param Integer $limit
 * @return Resource
 */
if ( ! function_exists('get_videos')) {
	function get_videos( $limit = 0 ) {
		$CI = &get_instance();
		$CI->load->model('m_videos');
		return $CI->m_videos->get_videos( $limit );
	}
}

/**
 * Get Albums
 * @param Integer $limit
 * @return Resource
 */
if ( ! function_exists('get_albums')) {
	function get_albums( $limit = 0 ) {
		$CI = &get_instance();
		$CI->load->model('m_albums');
		return $CI->m_albums->get_albums( $limit );
	}
}

/**
 * Get Menus
 * @return Array
 */
if ( ! function_exists('get_menus')) {
	function get_menus() {
		$CI = &get_instance();
		$CI->load->model('m_menus');
		return $CI->m_menus->nested_menus();
	}
}

/**
 * Recursive Lists
 * @param Array $menus
 * @return String
 */
if (!function_exists('recursive_list')) {
	function recursive_list($menus) {
		$nav = '';
		foreach ($menus as $menu) {
			$url = $menu['menu_url'] == '#' ? $menu['menu_url'] : base_url() . $menu['menu_url'];
			if ($menu['menu_type'] == 'links') $url = $menu['menu_url'];
			$nav .= '<li>';
			$nav .= '<a href="'. $url .'" target="'. $menu['menu_target'] .'">' . strtoupper($menu['menu_title']) . '</a>';
			$sub_nav = recursive_list($menu['children']);
			if ($sub_nav) $nav .= "<ul>" . $sub_nav . "</ul>";
			$nav .= "</li>";
		}
		return $nav;
	}
}

/**
 * Routes | Opening Speech Route
 * @return String
 */
if ( ! function_exists('opening_speech_route')) {
	function opening_speech_route() {
		$CI = &get_instance();
		$level = (int) $CI->session->userdata('school_level');
		if ( $level == 5 ) return 'sambutan-rektor';
		if ( $level == 6 ) return 'sambutan-ketua';
		if ( $level == 7 ) return 'sambutan-direktur';
		return 'sambutan-kepala-sekolah';
	}
}
