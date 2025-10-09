<?php
defined('BASEPATH') or exit('No direct script access allowed');
use Config\Database;
use App\Models\Login\JanparichayModel;
use CodeIgniter\HTTP\Request;
use CodeIgniter\HTTP\UserAgent;
//JANPARICHAY
define('JANPARICHAY_CLIENT_URL', 'http://localhost:3032');
define('JANPARICHAY_URL', 'https://janparichaystag.meripehchaan.gov.in');
define('JANPARICHAY_CLIENT_SERVICE_PORT', '');
define('JANPARICHAY_CLIENT_SERVICE_HOST', '');
define('JANPARICHAY_API_BASE_URL', 'https://apijanparichaystag.meripehchaan.gov.in/');
define('JANPARICHAY_SERVICE_ID', 'scefm3nym9s0ecvqoe91mma2bi83hk09');
define('JANPARICHAY_AUTH_KEY', '12pfqdp71ihyib511zr9ak3di5yizrtu');
define('JANPARICHAY_AES_IV', 'lxpmkmmpvg5pkr8h');
define('JANPARICHAY_LOG_LEVEL', 'DEBUG');
define('JANPARICHAY_LOGGING', 1);
define('JANPARICHAY_REST_AUTH_ID', '1489u8z6p2');

if (!function_exists('janparichay_hmac_client_signature')) {
    function janparichay_hmac_client_signature($hmacString)
    {
        $url     = JANPARICHAY_CLIENT_URL . '/hmac';
        $payload = json_encode(['HmacString' => $hmacString]);
        $corrId  = uniqid('hmac_', true);
        log_message('info', "[$corrId] HMAC REQUEST → URL: {$url} | Payload: {$payload}");
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_CONNECTTIMEOUT => 2,
            CURLOPT_TIMEOUT        => 5,
        ]);
        $response  = curl_exec($ch);
        $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);
        if ($response === false) {
            log_message('info', "[$corrId] HMAC RESPONSE ✖︎ cURL Error: {$curlError} | HTTP Code: {$httpCode}");
            return null;
        }
        log_message('info', "[$corrId] HMAC RESPONSE ← HTTP Code: {$httpCode} | Payload: {$response}");
        $responseData = json_decode($response, true);
        if (isset($responseData['status']) && $responseData['status'] === 'success' && isset($responseData['data']['signature'])) {
            return $responseData['data']['signature'];
        }
        return null;
    }
}

if (!function_exists('janparichay_encrypt')) {
    function janparichay_encrypt()
    {
        $url     = JANPARICHAY_CLIENT_URL . '/encryption';
        $payload = json_encode(['AESString' => JANPARICHAY_AES_IV]);
        $corrId  = uniqid('enc_', true);
        log_message('info', "[$corrId] ENCRYPT REQUEST → URL: {$url} | Payload: {$payload}");
        $ch      = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_CONNECTTIMEOUT => 2,
            CURLOPT_TIMEOUT        => 5,
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);
        if ($response === false) {
            log_message('info', "[$corrId] ENCRYPT RESPONSE ✖︎ cURL Error: {$curlError} | HTTP Code: {$httpCode}");
            return null;
        }
        log_message('info', "[$corrId] ENCRYPT RESPONSE ← HTTP Code: {$httpCode} | Payload: {$response}");
        $responseData = json_decode($response, true);
        if (isset($responseData['status']) && $responseData['status'] === 'success' && isset($responseData['data']['signature'])) {
            return $responseData['data']['signature'];
        }
        return null;
    }
}

if (! function_exists('janparichay_decrypt')) {
    function janparichay_decrypt($encryptedString)
    {
        $url = JANPARICHAY_CLIENT_URL . '/decryption';
        $payload = json_encode([
            'EncryptedString' => $encryptedString
        ]);
        $corrId = uniqid('dec_', true);
        log_message('info', "[$corrId] DECRYPTION REQUEST → URL: $url | Payload: $payload");
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_POST           => true,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($payload)
            ],
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT        => 15,
        ]);
        $response  = curl_exec($ch);
        $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);
        if ($response === false) {
            log_message('info', "[$corrId] DECRYPTION ERROR ← cURL: $curlError | HTTP Code: $httpCode");
            return false;
        }
        curl_close($ch);
        log_message('info', "[$corrId] DECRYPTION RESPONSE ← HTTP $httpCode | Payload: $response");
        $responseData = json_decode($response, true);
        if (isset($responseData['data']['signature'])) {
            return $responseData;
        }
    }
}

if (! function_exists('janparichay_login_url')) {
    function janparichay_login_url()
    {
        unset($_SESSION['msg']);
        $ttl = round(microtime(true) * 1000);
        $hmacString   = 'JanParichay' . $ttl . JANPARICHAY_URL . '/v1/api/login' . JANPARICHAY_SERVICE_ID;
        $signature    = janparichay_hmac_client_signature($hmacString);
        $encryptedSid = janparichay_encrypt();
        $params = [
            'sid'    => urldecode(JANPARICHAY_SERVICE_ID),
            'tid'    => urldecode($ttl),
            'cs'     => urldecode($signature),
            'string' => urldecode($encryptedSid),
        ];
        return $url = JANPARICHAY_URL . '/v1/api/login?' . http_build_query($params);
    }
}

if (!function_exists('janparichay_handshake_api')) {
    function janparichay_handshake_api($handshakingId)
    {
        $url     = JANPARICHAY_CLIENT_URL . '/handshake?handshakingId=' . urlencode($handshakingId) . "&sid=" . urlencode(JANPARICHAY_SERVICE_ID);
        $corrId = uniqid('hs_', true);
        log_message('info', "[$corrId] HANDSHAKE REQUEST → URL: $url");
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => 10,
        ]);
        $response  = curl_exec($curl);
        $httpCode  = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $curlError = curl_error($curl);
        curl_close($curl);

        if ($response === false) {
            log_message('info', "[$corrId] HANDSHAKE ERROR ← cURL: $curlError | HTTP Code: $httpCode");
            return false;
        }
        log_message('info', "[$corrId] HANDSHAKE RESPONSE ← HTTP $httpCode | Payload: $response");
        return $response;
    }
}

if (!function_exists('janparichay_handle_login_callback')) {
    function janparichay_handle_login_callback($handshakeId = null)
    {
        // $CI = get_instance();

        // if ($handshakeId === null) {
        //     $handshakeId = $CI->input->get('string', true);
        // }

        // $request = new RequestInterface;
        if ($handshakeId === null) {
            // $handshakeId = $_GET['string'] ?? null;
            $handshakeId = $_GET['string'] ?? null;
        }

        if (empty($handshakeId)) {
            throw new InvalidArgumentException('Missing handshake id (?string=) in callback.');
        }

        $encryptedData = janparichay_handshake_api($handshakeId, JANPARICHAY_SERVICE_ID);

        return janparichay_decrypt($encryptedData);
    }
}

if (! function_exists('janparichay_validate_token')) {
    function janparichay_validate_token($clientToken = null, $sessionId = null, $browserId = null)
    {
        // $CI = get_instance();

        $clientToken = $clientToken ?: getSessionData('clientToken');
        $sessionId   = $sessionId   ?: getSessionData('sessionId');
        $browserId   = $browserId   ?: getSessionData('browserId');

        if (!$clientToken || !JANPARICHAY_SERVICE_ID || !$sessionId || !$browserId) {
            log_message('error', 'JanParichay token validation failed due to missing parameters.');
            return false;
        }

        $query = http_build_query([
            'clientToken' => urldecode($clientToken),
            'sid'         => urldecode(JANPARICHAY_SERVICE_ID),
            'sessionId'   => urldecode($sessionId),
            'browserId'   => urldecode($browserId),
        ]);
        $url = rtrim(JANPARICHAY_CLIENT_URL, '/') . '/isTokenValid?' . $query;

        $corrId = uniqid('token_', true);
        log_message('info', "[$corrId] TOKEN VALIDATION REQUEST → URL: $url");

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT        => 10,
        ]);

        $response  = curl_exec($ch);
        $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($response === false) {
            log_message('info', "[$corrId] TOKEN VALIDATION ERROR ← cURL: $curlError | HTTP $httpCode");
            return false;
        }

        log_message('info', "[$corrId] TOKEN VALIDATION RESPONSE ← HTTP $httpCode | Payload: $response");

        return $response;
    }
}

if (! function_exists('janparichay_logout_session')) {
    function janparichay_logout_session($clientToken = null, $sid = null, $sessionId = null, $browserId = null, $userAgent = null)
    {

        // $CI = get_instance();
        $clientToken = $clientToken ?: getSessionData('clientToken');
        $sessionId   = $sessionId   ?: getSessionData('sessionId');
        $browserId   = $browserId   ?: getSessionData('browserId');
        $userAgent   = $userAgent   ?: getSessionData('userAgent');

        unset($_SESSION);
        $session = \Config\Services::session();
        $session->destroy();
        // $CI->session->sess_destroy();

        if (!$clientToken || !JANPARICHAY_SERVICE_ID || !$sessionId || !$browserId || !$userAgent) {
            log_message('error', 'janparichay_logout_session(): missing required parameters.');
            return false;
        }

        $tid        = round(microtime(true) * 1000);
        $hmacString = "JanParichay" . $tid . JANPARICHAY_URL . "/v1/salt/api/client/logout" . $clientToken . JANPARICHAY_SERVICE_ID . $sessionId;

        $clientSignature = janparichay_hmac_client_signature($hmacString);

        $query = http_build_query([
            'clientToken' => urldecode($clientToken),
            'sid'         => urldecode(JANPARICHAY_SERVICE_ID),
            'sessionId'   => urldecode($sessionId),
            'browserId'   => urldecode($browserId),
            'ua'          => rawurlencode($userAgent),
            'tid'         => $tid,
            'cs'          => urldecode($clientSignature),
        ]);

        $baseUrl = rtrim(JANPARICHAY_URL, '/');
        $url     = $baseUrl . '/v1/salt/api/client/logout?' . $query;

        $corrId  = uniqid('logout_', true);
        log_message('info', "[$corrId] LOGOUT REQUEST → URL: $url");

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT        => 10,
        ]);

        $response  = curl_exec($ch);
        $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($response === false) {
            log_message('info', "[$corrId] LOGOUT ERROR ← cURL: $curlError | HTTP $httpCode");
            return false;
        }

        log_message('info', "[$corrId] LOGOUT RESPONSE ← HTTP $httpCode | Payload: $response");

        return $response;
    }
}

if (!function_exists('janparichay_logout_all_apps')) {

    function janparichay_logout_all_apps($clientToken = null, $sessionId = null, $browserId = null)
    {
        // $CI = get_instance();

        $clientToken = $clientToken ?: getSessionData('clientToken');
        $sessionId   = $sessionId   ?: getSessionData('sessionId');
        $browserId   = $browserId   ?: getSessionData('browserId');
        unset($_SESSION);
        $session = \Config\Services::session();
        $session->destroy();
        // $CI->session->sess_destroy();
        $query = http_build_query([
            'clientToken' => $clientToken,
            'sid'         => JANPARICHAY_SERVICE_ID,
            'sessionId'   => $sessionId,
            'browserId'   => $browserId,
        ]);

        $baseUrl = rtrim(JANPARICHAY_CLIENT_URL, '/');
        $url     = $baseUrl . '/logoutAll?' . $query;
        $corrId  = uniqid('logoutAll_', true);

        log_message('info', "[$corrId] LOGOUT ALL APPS REQUEST → POST URL: $url");

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT        => 10,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => '',
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/x-www-form-urlencoded'
            ]
        ]);

        $response  = curl_exec($ch);
        $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($response === false) {
            log_message('info', "[$corrId] LOGOUT ALL APPS ERROR ← cURL: $curlError | HTTP $httpCode");
            return false;
        }

        log_message('info', "[$corrId] LOGOUT ALL APPS RESPONSE ← HTTP $httpCode | Payload: $response");

        return $response;
    }
}

if (!function_exists('janparichay_timeout_session')) {
    function janparichay_timeout_session($clientToken = null, $sessionId = null, $browserId = null, $userAgent = null)
    {
        // $CI = get_instance();
        $clientToken = $clientToken ?: getSessionData('clientToken');
        $sessionId   = $sessionId   ?: getSessionData('sessionId');
        $browserId   = $browserId   ?: getSessionData('browserId');
        $userAgent   = $userAgent   ?: getSessionData('userAgent');

        $query = http_build_query([
            'clientToken' => urldecode($clientToken),
            'sid'         => urldecode(JANPARICHAY_SERVICE_ID),
            'sessionId'   => urldecode($sessionId),
            'browserId'   => urldecode($browserId),
            'ua'          => urldecode($userAgent),
        ]);

        $baseUrl = rtrim(JANPARICHAY_CLIENT_URL, '/');
        $url     = $baseUrl . '/timeout?' . $query;
        $corrId  = uniqid('timeout_', true);

        log_message('info', "[$corrId] TIMEOUT REQUEST → URL: $url");

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT        => 10,
        ]);

        $response  = curl_exec($ch);
        $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($response === false) {
            log_message('info', "[$corrId] TIMEOUT ERROR ← cURL: $curlError | HTTP $httpCode");
            return false;
        }

        log_message('info', "[$corrId] TIMEOUT RESPONSE ← HTTP $httpCode | Payload: $response");

        return $response;
    }
}

if (!function_exists('janparichay_timeout_client_session')) {
    function janparichay_timeout_client_session()
    {

        $tid             = round(microtime(true) * 1000);
        $hmacString      = "JanParichay" . $tid . JANPARICHAY_URL . "/v1/salt/api/client/timeout" . JANPARICHAY_SERVICE_ID;
        $clientSignature = janparichay_hmac_client_signature($hmacString);

        $query = http_build_query([
            'sid' => JANPARICHAY_SERVICE_ID,
            'tid' => $tid,
            'cs'  => urldecode($clientSignature),
        ]);

        $baseUrl = rtrim(JANPARICHAY_URL, '/');
        return $url     = $baseUrl . '/v1/salt/api/client/timeout?' . $query;
    }
}
function is_janparichay_user_status($loginid = null)
{
    // $ci = &get_instance();
    // $ci->load->model('login/Janparichay_model');
    // $ci->load->library('session');
    $Janparichay_model = new JanparichayModel();
    return $Janparichay_model->is_janparichay_user_status($loginid);
}

if (!function_exists('generate_user_key')) {
    function generate_user_key()
    {
        // $CI = &get_instance();
        // $CI->load->library('user_agent');
        // $ip_address = $CI->input->ip_address();
        // $browser_id = $CI->agent->agent_string();
        // $raw_string = $ip_address . '|' . $browser_id;
        // $key = hash('sha256', $raw_string);
        // return $key;
        $request = new Request;
        $userAgent = new UserAgent;
        $ip_address = $request->getIPAddress();
        $browser_id = $userAgent->getAgentString();
        $raw_string = $ip_address . '|' . $browser_id;
        $key = hash('sha256', $raw_string);
        return $key;
    }
}

if (!function_exists('check_and_redirect_janparichay_session')) {
    function check_and_redirect_janparichay_session()
    {
        // $CI = &get_instance();
        // $CI->load->library(['user_agent']);
        // $CI->load->helper(['url']);
        // $CI->load->database();
        $db = Database::connect();
        $user_key = generate_user_key();
        $builder = $db->table('efil.janparichay');
        $builder->select('client_token, session_id, browser_id');
        $builder->like('user_key', $user_key);
        $builder->orderBy('created_on', 'DESC');
        $builder->limit(1);
        $query = $builder->get();
        $row = $query->getRow();
        if ($row) {
            $response = janparichay_validate_token($row->client_token, $row->session_id, $row->browser_id);
            $response = json_decode($response, true);
            if (isset($response['tokenValid']) && $response['tokenValid'] === 'true') {
                $janparichay_url = janparichay_timeout_client_session();
                redirect($janparichay_url);
            }
        }
    }
}

if (!function_exists('janparichay_login_form')) {
    function janparichay_login_form()
    {
        if (JANPARICHAY_CLIENT_ACTIVE) {
            // $CI = &get_instance();
            // $CI->load->helper(['url']);
            return $html_janparichay_login_form = '<div class="uk-card-body uk-padding-remove ukborder-rounded"><a href="' . janparichay_login_url() . '" style="text-decoration: none;">
            <div style="display: inline-flex; align-items: center; background-color: #002a5c; color: white;padding: 10px 20px;border-radius: 6px;font-size: 16px;font-weight: 500;box-shadow: 0 2px 5px rgba(0,0,0,0.2);cursor: pointer;">
                            <img src="' . base_url() . 'assets/images/pehchaan-logo-blue.png" alt="Meri Pehchaan" style="height: 32px; margin-right: 10px;">Login AOR with Jan Parichay</div>
                    </a></div><h4><b>OR</b></h4>';
        }
    }
}
