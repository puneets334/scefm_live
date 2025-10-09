<?php
namespace App\Controllers;

class View_icmis_file extends BaseController {

    public function __construct() {

        parent::__construct();
        require_once APPPATH . 'third_party/SBI/Crypt/AES.php';
        require_once APPPATH . 'third_party/cg/AesCipher.php';
    }

    public function _remap($param = NULL) {

        if ($param == 'index') {
            echo "Invalid Access !";
            exit(0);
        } else {
            $this->index($param);
        }
    }

    public function index($doc_id) {


        //$this->check_login();

        $doc_id = url_decryption($doc_id);
        if (empty($doc_id) || !preg_match("/^[0-9]*$/", $doc_id)) {
            echo "Invalid Access !";
            exit(0);
        }

        $doc_id = 'SXVnNlRrTjdXQzVOMWc3NkJ6YkNqQTo6';
        //$doc_id = base64_encode($this->encrypt_doc_id($doc_id));

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => PSPDFKIT_SERVER_URI."/api/documents/" . $doc_id . "/pdf",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array("Authorization: Token token=\"secret\""),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        if (!empty($response)) {

            header("Content-Type: application/pdf");
            header("Content-Disposition:inline;filename = $doc_title");
            header('Content-Transfer-Encoding: binary');
            header('Accept-Ranges: bytes');
            echo $response;
        } else {
            echo "File does not exists !";
            exit(0);
        }
    }

    function check_login() {

        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_ADMIN);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            redirect('dashboard');
            exit(0);
        }
    }

    function encrypt_doc_id($doc_id) {

        $doc_parameter = $doc_id;

        $aes = new Crypt_AES();
        $secret = base64_decode(SBI_PAYMENT_DOUBLE_VARIFICATION_SECRET_KEY);
        $aes->setKey($secret);
        $encrypted_doc_id = base64_encode($aes->encrypt($doc_parameter));
        $encrypted_doc_id = str_replace('/', '-', $encrypted_doc_id);
        $encrypted_doc_id = str_replace('=', ':', $encrypted_doc_id);
        $encrypted_doc_id = str_replace('+', '.', $encrypted_doc_id);

        return $encrypted_doc_id;
    }

}
