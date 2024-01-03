<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CMS Sekolahku Premium Version
 * @version    2.5.2
 * @author     Anton Sofyan | https://facebook.com/antonsofyan | 4ntonsofyan@gmail.com | 0857 5988 8922
 * @copyright  (c) 2017-2023
 * @link       https://sekolahku.web.id
 *
 * PERINGATAN :
 * 1. TIDAK DIPERKENANKAN MENGGUNAKAN CMS INI TANPA SEIZIN DARI PIHAK PENGEMBANG APLIKASI.
 * 2. TIDAK DIPERKENANKAN MEMPERJUALBELIKAN APLIKASI INI TANPA SEIZIN DARI PIHAK PENGEMBANG APLIKASI.
 * 3. TIDAK DIPERKENANKAN MENGHAPUS KODE SUMBER APLIKASI.
 */

if ( ! function_exists('get_recapture_score')) {
    /**
     *
     * Returns a float from 0 - 1 indicating the likelihood that the request is a from a human
     * 0 is considered a robot
     * 1 is considered a human
     *
     * @param $secret
     * @return float
     */
    function get_recapture_score($secret) {
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $req = $url .'?secret=' . __session('recaptcha_secret_key') . '&response='.$secret.'';
        $res = file_get_contents($req);
        $res = json_decode($res);
        if ($res->success == false) {
            foreach($res->{'error-codes'} as $code){
                switch ($code) {
                    case 'missing-input-secret':
                        log_message('error', 'RECAPTCHA: The secret parameter is missing. ' . $req);
                        break;
                    case 'invalid-input-secret':
                        log_message('error', 'RECAPTCHA: The secret parameter is invalid or malformed. '. $req);
                        break;
                    case 'missing-input-response':
                        log_message('error', 'RECAPTCHA: The response parameter is missing. '. $req);
                        break;
                    case 'invalid-input-response':
                        log_message('error', 'RECAPTCHA: The response parameter is invalid or malformed. ' . $req);
                        break;
                    case 'bad-request':
                        log_message('error', 'RECAPTCHA: The request is invalid or malformed. ' . $req);
                        break;
                    case 'timeout-or-duplicate':
                        log_message('error', 'RECAPTCHA: The response is no longer valid: either is too old or has been used previously.');
                        break;
                    default:
                        log_message('error', 'RECAPTCHA: Unknown error');
                }
            }
            return 0;
        }
        return $res->score;
    }
}