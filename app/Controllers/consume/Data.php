<?php
namespace App\Controllers;

class Data extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->helper('functions');
        $this->load->model('consume/Consume_data_model');
        require_once APPPATH . 'third_party/SBI/Crypt/AES.php';
        require_once APPPATH . 'third_party/cg/AesCipher.php';

        $ref = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
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

    /*public function _remap($time_stamp = NULL) {
        $this->data($time_stamp);
    }*/

    public function index() {
        /*// For testing
        $time_stamp='2020-01-01';
        $ia_total_data = $this->Consume_data_model->get_efiled_misc_doc_ia_data(E_FILING_TYPE_IA, $time_stamp);
        print_r($ia_total_data);*/
    }

    public function data($time_stamp = NULL,$type=E_FILING_TYPE_NEW_CASE) {
        $this->validate_date_time($time_stamp);
        $cases_total_data=$cases_total_re_data=$misc_total_data=$misc_total_re_data=$ia_total_re_data=array();
        if($type==E_FILING_TYPE_NEW_CASE){
            $cases_total_data = $this->Consume_data_model->get_efiled_cases_data($time_stamp);
            $cases_total_re_data = $this->Consume_data_model->get_re_efiled_cases_data($time_stamp);
        }
        else if($type==E_FILING_TYPE_MISC_DOCS || $type==E_FILING_TYPE_IA){
            $misc_total_data = $this->Consume_data_model->get_efiled_misc_doc_ia_data(E_FILING_TYPE_MISC_DOCS, $time_stamp);
            $ia_total_data = $this->Consume_data_model->get_efiled_misc_doc_ia_data(E_FILING_TYPE_IA, $time_stamp);
            $misc_total_re_data = $this->Consume_data_model->get_re_efiled_misc_doc_ia_data(E_FILING_TYPE_MISC_DOCS, $time_stamp);
            $ia_total_re_data = $this->Consume_data_model->get_re_efiled_misc_doc_ia_data(E_FILING_TYPE_IA, $time_stamp);
        }
    /*    $cases_total_data = $this->Consume_data_model->get_efiled_cases_data($time_stamp);
        $misc_total_data = $this->Consume_data_model->get_efiled_misc_doc_ia_data(E_FILING_TYPE_MISC_DOCS, $time_stamp);
        $ia_total_data = $this->Consume_data_model->get_efiled_misc_doc_ia_data(E_FILING_TYPE_IA, $time_stamp);
        $cases_total_re_data = $this->Consume_data_model->get_re_efiled_cases_data($time_stamp);
        $misc_total_re_data = $this->Consume_data_model->get_re_efiled_misc_doc_ia_data(E_FILING_TYPE_MISC_DOCS, $time_stamp);
        $ia_total_re_data = $this->Consume_data_model->get_re_efiled_misc_doc_ia_data(E_FILING_TYPE_IA, $time_stamp);*/

        $cases_data = '';
        foreach ($cases_total_data as $cd) {

            $case_doc_details = json_decode($cd['row_to_json']);
            $i = 0;
            foreach ($case_doc_details->case_docs_details as $doc) {                

                $case_doc_details->case_docs_details[$i]->doc_id = $this->encrypt_doc_id($doc->doc_id);
                $i++;
            }
            $cases_data .= json_encode($case_doc_details) . ',';
        }
        
        foreach ($misc_total_data as $cd) {

            $case_doc_details = json_decode($cd['row_to_json']);
            $i = 0;
            foreach ($case_doc_details->case_docs_details as $doc) {                

                $case_doc_details->case_docs_details[$i]->doc_id = $this->encrypt_doc_id($doc->doc_id);
                $i++;
            }
            $misc_docs_data .= json_encode($case_doc_details) . ',';
        }
        
        foreach ($ia_total_data as $cd) {

            $case_doc_details = json_decode($cd['row_to_json']);
            $i = 0;
            foreach ($case_doc_details->case_docs_details as $doc) {                

                $case_doc_details->case_docs_details[$i]->doc_id = $this->encrypt_doc_id($doc->doc_id);
                $i++;
            }
            $ia_data .= json_encode($case_doc_details) . ',';
        }
        
        $re_cases_data = '';
        foreach ($cases_total_re_data as $cd) {

            $case_doc_details = json_decode($cd['row_to_json']);
            $i = 0;
            foreach ($case_doc_details->case_docs_details as $doc) {                

                $case_doc_details->case_docs_details[$i]->doc_id = $this->encrypt_doc_id($doc->doc_id);
                $i++;
            }
            $re_cases_data .= json_encode($case_doc_details) . ',';
        }
        
        foreach ($misc_total_re_data as $cd) {

            $case_doc_details = json_decode($cd['row_to_json']);
            $i = 0;
            foreach ($case_doc_details->case_docs_details as $doc) {                

                $case_doc_details->case_docs_details[$i]->doc_id = $this->encrypt_doc_id($doc->doc_id);
                $i++;
            }
            $re_misc_docs_data .= json_encode($case_doc_details) . ',';
        }
        
        foreach ($ia_total_re_data as $cd) {

            $case_doc_details = json_decode($cd['row_to_json']);
            $i = 0;
            foreach ($case_doc_details->case_docs_details as $doc) {                

                $case_doc_details->case_docs_details[$i]->doc_id = $this->encrypt_doc_id($doc->doc_id);
                $i++;
            }
            $re_ia_data .= json_encode($case_doc_details) . ',';
        }

        $cases_data = !empty($cases_data) ? rtrim($cases_data, ',') : '';
        $misc_docs_data = !empty($misc_docs_data) ? rtrim($misc_docs_data, ',') : '';
        $ia_data = !empty($ia_data) ? rtrim($ia_data, ',') : '';
        
        $re_cases_data = !empty($re_cases_data) ? rtrim($re_cases_data, ',') : '';
        $re_misc_docs_data = !empty($re_misc_docs_data) ? rtrim($re_misc_docs_data, ',') : '';
        $re_ia_data = !emtpy($re_ia_data) ? rtrim($re_ia_data, ',') : '';
        
        //$response = '{"efiled_cases_details":[' . $cases_data . ']}';
        $response = '{"efiled_cases_details":[' . $cases_data . '],"efiled_ia_details":[' . $ia_data . '], "efiled_loose_doc_details":[' . $misc_docs_data
                . '], "re_efiled_cases_details":[' . $re_cases_data. '], "re_efiled_ia_details":[' . $re_ia_data. '], "re_efiled_loose_doc_details":[' . $re_misc_docs_data. ']}';
        
        //$response = '{"efiled_cases_details":[' . $cases_data . '],"efiled_ia_details":[' . $ia_data . '], "efiled_loose_doc_details":[' . $misc_docs_data. ']}';
        header('Content-Type: application/json');
        echo $response;
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
        else if (!empty(getClientIP()))
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
    public function getMentioningDetails($time_stamp="") {
        $this->validate_date_time($time_stamp);
        $cases_total_data = $this->Consume_data_model->get_approved_mentioning_requests($time_stamp);
        echo json_encode($cases_total_data);
    }

    public function getCertificateDetails($time_stamp="") {
        $this->validate_date_time($time_stamp);
        $cases_total_data = $this->Consume_data_model->get_certificate_requests($time_stamp);
        echo json_encode($cases_total_data);
    }

    public function getnewpspdfkitdata_bydiaryno($diaryno){
        $pspdfkit_docids = $this->Consume_data_model->getNewPSPDFKIT_data($diaryno);
        echo json_encode($pspdfkit_docids);
    }

    public function getoldpspdfkitdata_bydiaryno($diaryno){
        $pspdfkit_docids = $this->Consume_data_model->getOldPSPDFKIT_data($diaryno);
        echo json_encode($pspdfkit_docids);
    }

    public function getNewPages($pspdfkit_id){
        $changed_pages = $this->Consume_data_model->getNewPages($pspdfkit_id);
        $pagesarr = array();
        foreach($changed_pages as $changed_page){
            array_push($pagesarr, $changed_page->page_no);
        }
        echo json_encode($pagesarr);
    }

    public function getRemovedPages($pspdfkit_id){
        $changed_pages = $this->Consume_data_model->getRemovedPages($pspdfkit_id);
        $pagesarr = array();
        foreach($changed_pages as $changed_page){
            array_push($pagesarr, $changed_page->page_no);
        }
        echo json_encode($pagesarr);
    }

}
