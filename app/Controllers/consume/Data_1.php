<?php
namespace App\Controllers;

class Data_1 extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->helper('functions');
        $this->load->model('consume/Consume_data_model_1');
        require_once APPPATH . 'third_party/SBI/Crypt/AES.php';
        require_once APPPATH . 'third_party/cg/AesCipher.php';

        $ref = $_SERVER['HTTP_REFERER'];
        $refData = parse_url($ref);
        if ($refData['host'] != $_SERVER['SERVER_NAME'] && $refData['host'] != NULL) {

            redirect("login");
            exit(0);
        }
        if (count($_POST) > 0) {
            if ($_POST['CSRF_TOKEN'] != $_SESSION['csrf_to_be_checked']) {
                echo '<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><title>Error</title><style type="text/css"> ::selection { background-color: #E13300; color: white; } ::-moz-selection { background-color: #E13300; color: white; } body {  background-color: #fff;  margin: 40px;  font: 13px/20px normal Helvetica, Arial, sans-serif;  color: #4F5155;}a {  color: #003399;  background-color: transparent;  font-weight: normal;}h1 {  color: #444;  background-color: transparent;  border-bottom: 1px solid #D0D0D0;  font-size: 19px;  font-weight: normal;  margin: 0 0 14px 0;  padding: 14px 15px 10px 15px;}code {  font-family: Consolas, Monaco, Courier New, Courier, monospace;  font-size: 12px;  background-color: #f9f9f9;  border: 1px solid #D0D0D0;  color: #002166;  display: block;  margin: 14px 0 14px 0;  padding: 12px 10px 12px 10px;}#container {  margin: 10px;  border: 1px solid #D0D0D0;  box-shadow: 0 0 8px #D0D0D0;}p {  margin: 12px 15px 12px 15px;}</style></head><body>  <div id="container">    <h1>An Error Was Encountered</h1>    <p>The action you have requested is not allowed.</p>  </div></body></html>';
                exit(0);
            }
        }
        $_SESSION['csrf_to_be_checked'] = $this->security->get_csrf_hash();
    }
    
    public function _remap($time_stamp = NULL)
{
        $this->data($time_stamp);
}

    public function index() {
        
    }

    public function data($time_stamp = NULL) {
 
        $this->validate_date_time($time_stamp);
        
        $case_details = $this->Consume_data_model_1->get_cases_details($time_stamp);
        
        $response = array('efiled_cases_details' => array(
                 'case_details' => $case_details ));
        header('Content-Type: application/json');
        echo json_encode($response);
        
        /*$case_parties = $this->Consume_data_model->get_cases_parties($time_stamp);
        $case_details = $this->Consume_data_model->get_cases_details($time_stamp);
        $case_act_sections = $this->Consume_data_model->get_cases_act_sections($time_stamp);
        $case_lower_case_details = $this->Consume_data_model->get_cases_lower_case_details($time_stamp);
        $case_advocate_details = $this->Consume_data_model->get_cases_advocates($time_stamp);

        $docs_details = $this->Consume_data_model->get_case_docs_details($time_stamp);

        $case_docs_details = array();
        if (!empty($docs_details)) {

            foreach ($docs_details as $docs_data) {

                $case_docs_details[] = array(
                    'efiling_no' => $docs_data['efiling_no'],
                    'efiling_year' => $docs_data['efiling_year'],
                    'created_on' => $docs_data['created_on'],
                    'index_no' => $docs_data['index_no'],
                    'doc_id' => $this->encrypt_doc_id($docs_data['doc_id']),
                    'doc_type_id' => $docs_data['doc_type_id'],
                    'sub_doc_type_id' => $docs_data['sub_doc_type_id'],
                    'file_name' => $docs_data['file_name'],
                    'doc_title' => $docs_data['doc_title'],                    
                    'file_size' => $docs_data['file_size'],
                    'page_no' => $docs_data['page_no'],
                    'no_of_copies' => $docs_data['no_of_copies'],
                    'doc_hashed_value' => $docs_data['doc_hashed_value'],
                    'refiled' => $docs_data['refiled'],
                    'remarks' => $docs_data['remarks'],
                    'uploaded_on' => $docs_data['uploaded_on'],
                    'is_deleted' => $docs_data['is_deleted']
                );
            }
        } else {
            $case_docs_details[] = NULL;
        }


        $response = array('efiled_cases_details' => array(
                 'case_details' => $case_details,
                  'case_parties' => $case_parties,
                  'case_act_sections' => $case_act_sections,
                  'case_lower_case_details' => $case_lower_case_details,
                  'case_advocate_details' => $case_advocate_details, 
                'case_docs_details' => $case_docs_details));
        header('Content-Type: application/json');
        echo json_encode($response);*/
    }

    function encrypt_doc_id($doc_id) {

        $doc_parameter = $doc_id . '|1';

        $aes = new Crypt_AES();
        $secret = base64_decode(SBI_PAYMENT_DOUBLE_VARIFICATION_SECRET_KEY);
        $aes->setKey($secret);
        $encrypted_doc_id = base64_encode($aes->encrypt($doc_parameter));
        $encrypted_doc_id = str_replace('/', '-', $encrypted_doc_id);
        $encrypted_doc_id = str_replace('=', ':', $encrypted_doc_id);
        $encrypted_doc_id = str_replace('+', '.', $encrypted_doc_id);

        return $encrypted_doc_id;
    }

   

    public function get_client_ip() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if (isset(getClientIP()))
            $ipaddress = getClientIP();
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    private function validate_date_time($time_stamp) {
        $time_stamp = urldecode($time_stamp);
        if (strlen($time_stamp) == 10) {
            $time_stamp .= ' 00:00:00';
        }
        if (!isset($time_stamp) || (isset($time_stamp) && $time_stamp != 'NULL' && !validateDate($time_stamp))) {
            echo "Wrong date or date not found or ip details not correct !";
            exit(0);
        }
        return $time_stamp;
    }  

}
