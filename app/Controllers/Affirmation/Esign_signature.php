<?php

namespace App\Controllers\Affirmation;

use App\Controllers\BaseController;
use App\Models\Affirmation\EsignSignatureModel;
use App\Models\AppearingFor\AppearingForModel;
use TCPDF;
use finfo;

class Esign_signature extends BaseController {

    protected $Esign_signature_model;
    protected $Appearing_for_model;
    protected $validation;
    
    public function __construct() {
        parent::__construct();
        $this->Esign_signature_model = new EsignSignatureModel();
        $this->Appearing_for_model = new AppearingForModel();
        $this->validation = \Config\Services::validation();
        require_once APPPATH . 'third_party/eSign/XMLSecEnc.php';
        require_once APPPATH . 'third_party/eSign/XMLSecurityDSig.php';
        require_once APPPATH . 'third_party/eSign/XMLSecurityKey.php';
    }
    
    function esign_docs() {
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK,JAIL_SUPERINTENDENT);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            $_SESSION['MSG'] = message_show("fail", 'Unauthorised Access !');
            return redirect()->to(base_url('adminDashboard'));
            exit(0);
        }        
        $stages_array = array(Draft_Stage, Initial_Defected_Stage, I_B_Defected_Stage);
        if (!in_array($_SESSION['efiling_details']['stage_id'], $stages_array)) {
            $_SESSION['MSG'] = message_show("fail", 'Invalid Stage.');
            return redirect()->to(base_url('dashboard'));
            exit(0);
        }        
        $registration_id = $_SESSION['efiling_details']['registration_id'];
        $efiling_num = $_SESSION['efiling_details']['efiling_no'];
        $est_code = $_SESSION['estab_details']['estab_code'];
        $validations = [
            "signer_type" => [
                "label" => "Signer Type",
                "rules" => "required|trim|min_length[1]|max_length[1]"
            ],
        ];
        $this->validation->setRules($validations);
        if ($this->validation->withRequest($this->request)->run() === FALSE) {
            $errors['MSG'] = $this->validation->getErrors();
            echo $errors;
            return redirect()->to(base_url('case/crud?tab=affirmation'));
            exit(0);
        }        
        switch($_POST['signer_type']){
            case 'P':{
                $petitioners   = $_POST['petitioners'];
                foreach ($petitioners as $ind=>$val){
                    $validations = [
                        "petitioners[".$ind."]" => [
                            "label" => "Petitioner",
                            "rules" => "required|trim|min_length[3]|max_length[99]|validate_alpha_numeric_space_dot_hyphen"
                        ],
                        "pet_email_id[".$ind."]" => [
                            "label" => "Petitioner Email ID",
                            "rules" => "required|trim|valid_email"
                        ],
                    ];
                    $this->validation->setRules($validations);
                    if ($this->validation->withRequest($this->request)->run() === FALSE) {
                        $errors = $this->validation->getErrors();
                        foreach ($errors as $field => $error) {
                            if(strpos($error, '{field}')){
                                echo str_replace('{field}', $field, $error) . "<br>";
                            } else{
                                echo $error . "<br>";
                            }
                        }
                        return redirect()->to(base_url('case/crud?tab=affirmation'));
                        exit(0);
                    }
                }                
                foreach ($petitioners as $ind=>$val) {
                    $bytes = random_bytes(20);
                    $uuid = (bin2hex($bytes)).date('YmdHis');
                    $petitioner_array = explode('#$', url_decryption(escape_data($_POST['petitioners'][$ind])));
                    $sign_array[$ind] = array(
                        'ref_tbl_case_parties_id'=>$petitioner_array[0],
                        'email'=>$_POST['pet_email_id'][$ind],
                        'registration_id' => $registration_id,
                        'sign_url' =>'',
                        'request_sent_on'=>NULL,
                        'is_certificate_signed'=>'false',
                        'is_locked'=>'true',
                        'uuid'=>$uuid,
                        'estab_code'=>$est_code,
                        'efiling_no' => $efiling_num,
                        'request_sent_by'=>$_SESSION['login']['id'],
                        'efiling_type'=>$_SESSION['efiling_details']['efiling_type'],
                        'pet_res_flag'=>'P',
                        'ordering'=>$ind
                    );
                }
                $txn_status = $this->Esign_signature_model->insert_batch_data('efil.esign_certificate', $sign_array);
                $this->send_email_for_signature($efiling_num,$est_code);
                break;
            }
            case 'R':{
                $respondents   = $_POST['respondents'];
                foreach ($respondents as $ind=>$val){
                    $validations = [
                        "respondents[".$ind."]" => [
                            "label" => "Respondent",
                            "rules" => "required|trim|min_length[3]|max_length[99]|validate_alpha_numeric_space_dot_hyphen"
                        ],
                        "res_email_id[".$ind."]" => [
                            "label" => "Respondent Email ID",
                            "rules" => "required|trim|valid_email"
                        ],
                    ];
                    $this->validation->setRules($validations);
                    if ($this->validation->withRequest($this->request)->run() === FALSE) {
                        $errors = $this->validation->getErrors();
                        foreach ($errors as $field => $error) {
                            if(strpos($error, '{field}')){
                                echo str_replace('{field}', $field, $error) . "<br>";
                            } else{
                                echo $error . "<br>";
                            }
                        }
                        return redirect()->to(base_url('case/crud?tab=affirmation'));
                        exit(0);
                    }
                }
                foreach ($respondents as $ind=>$val) {
                    $bytes = random_bytes(20);
                    $uuid = (bin2hex($bytes)) . date('YmdHis');
                    $link = base_url("affirmation/Esign_signature/sign_certificate/" . strtolower($efiling_num) . "/$uuid");
                    $respondent_array = explode('#$', url_decryption(escape_data($_POST['respondents'][$ind])));
                    $sign_array[$ind] = array('ref_tbl_case_parties_id' => $respondent_array[0], 'email' => $_POST['res_email_id'][$ind], 'registration_id' => $registration_id, 'sign_url' => '', 'request_sent_on' => NULL, 'is_certificate_signed' => 'false', 'is_locked' => 'true', 'uuid' => $uuid, 'estab_code' => $est_code, 'efiling_no' => $efiling_num, 'request_sent_by' => $_SESSION['login']['id'], 'efiling_type' => $_SESSION['efiling_details']['efiling_type'], 'pet_res_flag' => 'R', 'ordering' => $ind);
                }
                
                $txn_status = $this->Esign_signature_model->insert_batch_data('efil.esign_certificate', $sign_array);
                $this->send_email_for_signature($efiling_num,$est_code);
                break;
            }
            case 'A':{
                $validations = [
                    "adv_name_as_on_aadhar" => [
                        "label" => "Advocate Name as on Aadhaar",
                        "rules" => "required|trim|min_length[3]|max_length[99]|validate_alpha_numeric_space_dot_hyphen"
                    ],
                ];
                $this->validation->setRules($validations);
                if ($this->validation->withRequest($this->request)->run() === FALSE) {
                    $errors = $this->validation->getErrors();
                    foreach ($errors as $field => $error) {
                        if(strpos($error, '{field}')){
                            echo str_replace('{field}', $field, $error) . "<br>";
                        } else{
                            echo $error . "<br>";
                        }
                    }
                    return redirect()->to(base_url('case/crud?tab=affirmation'));
                    exit(0);
                }
                $name_as_on_aadhar = escape_data($_POST['adv_name_as_on_aadhar']);
                $this->advocate_esign($name_as_on_aadhar);
                break;
            }
        }
        return redirect()->to(base_url('affirmation'));
    }
    
    private function send_email_for_signature($efiling_num, $est_code){
        $sign_status = $this->Esign_signature_model->get_data('efil.esign_certificate', array('efiling_no'=>$efiling_num, 'is_locked'=>'true', 'is_certificate_signed'=>'false'), '*', 'ordering asc');
        $uuid=$sign_status[0]['uuid'];
        $link = base_url("affirmation/Esign_signature/sign_certificate/".strtolower($efiling_num)."/$uuid");
        $select_signer = $this->Esign_signature_model->update_data('efil.esign_certificate', array('sign_url'=>$link,'request_sent_on'=>date('Y-m-d H:i:s')), array('id'=>$sign_status[0]['id'], 'is_locked'=>'true','uuid'=>$uuid));
        $msg="Sir/Madam,
            Please find attached certificate of documents uploaded for efiling application Registration Number -$efiling_num.
            Click the given link to sign the certificate using your Aadhaar Number.
            $link
            ###  This is automatically generated email, please do not reply. For any suggestion/feedback please contact Computer Cell through e-mail: itcell@sci.nic.in";
        $file_name = $efiling_num.".pdf";
        $file_path = base_url("affirmation/viewUnsignedCertificate/get_certificate/$est_code/$efiling_num");
        $result = send_email($sign_status[0]['email'], "Document Sign request for Registration ID-$efiling_num", $msg, $file_path, $file_name);
        $_SESSION['MSG'] = message_show("success", 'Mail sent successfully.');
    }    
    
    private function advocate_esign($name_as_on_aadhar){
        $signed_by = ESIGNED_DOCS_BY_ADV;
        $registration_id = $_SESSION['efiling_details']['registration_id'];
        $efiling_num = $_SESSION['efiling_details']['efiling_no'];
        $est_code = $_SESSION['estab_details']['estab_code'];        
        //--UNSIGNED DOCUMENT DETAILS---------//
        $unsigned_pdf_file_name = $efiling_num . '_Advocate_UnsignedCertificate.pdf';
        $unsigned_pdf_full_path = getcwd() . '/uploaded_docs/' . $est_code . '/' . $efiling_num . '/unsigned_pdf/';
        $unsigned_pdf_full_path_with_file_name = $unsigned_pdf_full_path . $unsigned_pdf_file_name;
        $unsigned_pdf_partial_path = 'uploaded_docs/' . $est_code . '/' . $efiling_num . '/unsigned_pdf/';
        $unsigned_pdf_partial_path_with_file_name = $unsigned_pdf_partial_path . $unsigned_pdf_file_name;        
        $unsigned_pdf_sha_256_hash = hash_file('sha256', $unsigned_pdf_partial_path_with_file_name);        
        //--UNSIGNED DOCUMENT DETAILS END---------//        
        //--SIGNED DOCUMENT DETAILS---------//
        $signed_pdf_file_name = $efiling_num . '_Advocate_eSignedCertificate.pdf';
        $signed_pdf_full_path = getcwd() . '/uploaded_docs/' . $est_code . '/' . $efiling_num . '/signed_pdfs/';
        $signed_pdf_partial_path = 'uploaded_docs/' . $est_code . '/' . $efiling_num . '/signed_pdfs/';
        $signed_pdf_full_path_with_file_name = $signed_pdf_full_path . $signed_pdf_file_name;
        $signed_pdf_partial_path_with_file_name = $signed_pdf_partial_path . $signed_pdf_file_name;
        //--SIGNED DOCUMENT DETAILS END---------//       
        $file_details = array(
            'ref_registration_id' => $registration_id,
            'ref_efiling_no' => $efiling_num,
            'type' => $signed_by,
            'register_updated_date_time' => date('Y-m-d H:i:s'),
            'unsigned_pdf_full_path' => $unsigned_pdf_full_path,
            'unsigned_pdf_full_path_with_file_name' => $unsigned_pdf_full_path_with_file_name,
            'unsigned_pdf_partial_path' => $unsigned_pdf_partial_path,
            'unsigned_pdf_partial_path_with_file_name' => $unsigned_pdf_partial_path_with_file_name,
            'unsigned_pdf_file_name' => $unsigned_pdf_file_name,
            'unsigned_pdf_sha_256_hash' => $unsigned_pdf_sha_256_hash,
            'signed_pdf_full_path' => $signed_pdf_full_path,
            'signed_pdf_full_path_with_file_name' => $signed_pdf_full_path_with_file_name,
            'signed_pdf_partial_path' => $signed_pdf_partial_path,
            'signed_pdf_partial_path_with_file_name' => $signed_pdf_partial_path_with_file_name,
            'signed_pdf_file_name' => $signed_pdf_file_name,
            'name_as_on_aadhar' => $name_as_on_aadhar,
            'signed_type' => 2
        );        
        $signed_pdf_dir = 'uploaded_docs/' . $est_code . '/' . $efiling_num . '/signed_pdfs/';        
        if (!is_dir($signed_pdf_dir)) {
            $uold = umask(0);
            if (mkdir($signed_pdf_dir, 0777, true)) {
                $html = '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
                write_file($signed_pdf_dir . 'index.html', $html);
            }
            umask($uold);
        }
        $file_url = base_url("affirmation/viewUnsignedCertificate/get_certificate/$est_code/$efiling_num");
        $user_type = $this->Esign_signature_model->get_data('efil.tbl_user_types', array('id'=>$_SESSION['login']['ref_m_usertype_id']));        
        $app_id = $this->gen_application_number();
        $app_id = sprintf("%'.04d", $app_id);
        $txn = '016-ECOURTS-' . date("Ymd") . '-' . date("His") . '-' . ESIGN_REDIRECT_URL_CODE . $app_id;
        $ts = date("Y-m-d") . 'T' . date("H:i:s");
        $this->Esign_signature_model->insert_esign_request($file_details, $ts, $txn);
        $attribute = array('target' => '_top', 'name' => 'test', 'id' => 'test', 'autocomplete' => 'off');
        echo form_open(ESIGN_SERVICE_URL, $attribute); //Server
        echo '<input type = "hidden" name = "docId" value = "99999" />';
        echo '<input type = "hidden" name = "url" value = "'.$file_url.'" />';
        echo '<input type = "hidden" name = "respone_url" value = "'.base_url('affirmation/Esign_signature/advocate_esign_response').'" />';
        echo '<input type = "hidden" name = "docType" value = "'.$efiling_num.'" />';
        echo '<input type = "hidden" name = "esignIndex" value = "0" />';
        echo '<input type = "hidden" name = "app_no" value = "'.$txn.'" />';
        echo '<input type = "hidden" name = "employeeName" value = "'.$_SESSION['login']['first_name'].' '.$_SESSION['login']['last_name'].'" />';
        echo '<input type = "hidden" name = "employeeCode" value = "'.$_SESSION['login']['first_name'].' '.$_SESSION['login']['last_name'].'" />';
        echo '<input type = "hidden" name = "employeeDesignation" value = "'.$user_type[0]['user_type'].'" />';
        echo '<input type = "submit" />';
        echo form_close();
        echo "<script>document.getElementById('test').submit();</script>";
        exit(0);
    }
    
    public function advocate_esign_response(){
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK,JAIL_SUPERINTENDENT);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            $_SESSION['MSG'] = message_show("fail", 'Unauthorised Access !');
            return redirect()->to(base_url('adminDashboard'));
            exit(0);
        }        
        $stages_array = array(Draft_Stage, Initial_Defected_Stage, I_B_Defected_Stage);
        if (!in_array($_SESSION['efiling_details']['stage_id'], $stages_array)) {
            $_SESSION['MSG'] = message_show("fail", 'Invalid Stage.');
            return redirect()->to(base_url('dashboard'));
            exit(0);
        }
        $param = json_decode(base64_decode($_POST['response']), true);        
        if (isset($param['xml_reponse']) && !empty($param['xml_reponse'])) {            
            $response = (array) simplexml_load_string($param['xml_reponse']);
            $errcode = $response['@attributes']['errCode'];
            $errmsg = $response['@attributes']['errMsg'];
            $res_code = $response['@attributes']['resCode'];
            $status = $response['@attributes']['status'];
            $rests = $response['@attributes']['ts'];
            $txn = $response['@attributes']['txn'];
            $UserX509Certificate = $response['UserX509Certificate'];
            $diges_value = $response['Signature']->SignedInfo->Reference->DigestValue;
            $X509Certificate = $response['Signature']->KeyInfo->X509Data->X509Certificate;
            $signature_value = $response['Signature']->SignatureValue;            
            $txn_status = $this->Esign_signature_model->get_sign_txn_details($txn);
            if (!empty($txn_status[0]['digestvalue'])) {
                $_SESSION['MSG'] = message_show("fail", 'Invalid attempt !');
                //redirect('affirmation');
                return redirect()->to(base_url('case/crud?tab=affirmation'));
                exit(0);
            }            
            if (!(isset($_SESSION['efiling_details']['registration_id']) && !empty($_SESSION['efiling_details']['registration_id']))) {
                return redirect()->to(base_url('dashboard'));
                exit(0);
            }            
            $is_data_valid = FALSE;
            $signed_pdf_saved = 'No';
            $update_breadcrumb = NULL;            
            $registration_id = $_SESSION['efiling_details']['registration_id'];
            $efiling_no = $_SESSION['efiling_details']['efiling_no'];
            $est_code = $_SESSION['estab_details']['estab_code'];            
            if (!ENABLE_EVERIFICATION_BY_MOBILE_OTP && ENABLE_EVERIFICATION_ON_ESIGN_FAIL) {
                $errmsg1 = '<br/>If eSign using Aadhaar failed two times due to any technical reasons. e-Verification using Mobile will be automatically displayed at the very same page to proceed further.';
            } else {
                $errmsg1 = '';
            }            
            if ($errcode == 'NA' && $status == '1' && $param['code']=='1' && $param['signed_pdf_url']) {
                $arrContextOptions=array(
                    "ssl"=>array(
                        "verify_peer"=>false,
                        "verify_peer_name"=>false,
                    ),
                );
                $signed_file_contents = file_get_contents($param['signed_pdf_url'], false, stream_context_create($arrContextOptions));
                $file_info = new finfo(FILEINFO_MIME_TYPE);
                $mime_type = $file_info->buffer($signed_file_contents);                
                if ($signed_file_contents && $mime_type=='application/pdf') {
                    $adv_signed_certificate_path = getcwd() . '/uploaded_docs/' . $est_code . '/' . $efiling_no . '/signed_pdfs/' . $efiling_no . '_Advocate_eSignedCertificate.pdf';
                    //copy($param['signed_pdf_url'], $adv_signed_certificate_path);
                    file_put_contents($adv_signed_certificate_path, $signed_file_contents);
                    $filesize = filesize($adv_signed_certificate_path);                    
                    $is_data_valid = TRUE;
                    $signed_pdf_saved = 'Yes';                    
                    switch ($_SESSION['efiling_details']['ref_m_efiled_type_id']) {
                        case E_FILING_TYPE_NEW_CASE : $update_breadcrumb = NEW_CASE_AFFIRMATION;
                            break;
                        case E_FILING_TYPE_MISC_DOCS : $update_breadcrumb = MISC_BREAD_AFFIRMATION;
                            break;
                        case E_FILING_TYPE_IA : $update_breadcrumb = IA_BREAD_AFFIRMATION;
                            break;
                        case E_FILING_TYPE_MENTIONING : $update_breadcrumb = MEN_BREAD_AFFIRMATION;
                            break;
                        case E_FILING_TYPE_JAIL_PETITION : $update_breadcrumb = JAIL_PETITION_AFFIRMATION;
                            break;
                    }                    
                    $_SESSION['MSG'] = message_show("success", 'Document eSigned successfully !');
                    log_message('CUSTOM', "Document eSigned successfully !");
                } else {
                    $_SESSION['MSG'] = message_show("fail", 'Some Error, Please try again after some time !' . $errmsg1);
                    $efil_resp_remarks = 'Attempted, But File was not signed';
                    log_message('CUSTOM', "Attempted, But File was not signed !");
                }
            } elseif ($errmsg == '' && $errcode == '111') {
                $_SESSION['MSG'] = message_show("fail", 'Invalid Aadhaar Number !');
                log_message('CUSTOM', "Invalid Aadhaar Number !");
                $efil_resp_remarks = 'Invalid Aadhaar Number';
            } else {
                if ($errmsg == '') {
                    $_SESSION['MSG'] = message_show("fail", 'Some Error, Please try again after some time !' . $errmsg1);
                } else {
                    $_SESSION['MSG'] = message_show("fail", $errmsg . '<br>' . $errmsg1);
                }
            }            
            $data = array(
                'is_data_valid' => $is_data_valid,
                'rests' => $rests,
                'txn' => $res_code,
                'errmsg' => $errmsg,
                'errcode' => $errcode,
                'status' => $status,
                'userx509certificate' => $UserX509Certificate,
                'x509certificate' => $X509Certificate,
                'signaturevalue' => $signature_value,
                'digestvalue' => $diges_value,
                'signed_pdf_saved' => $signed_pdf_saved,
                'efil_resp_remarks' => $efil_resp_remarks
            );            
            $result = $this->Esign_signature_model->esign_document_xml_response($txn, $data, $registration_id, $update_breadcrumb);            
            if (!$result) {
                $_SESSION['MSG'] = message_show("fail", 'Some Error, Please try again after some time !');
            }
        } else {
            $_SESSION['MSG'] = message_show("fail", 'Some Error, Please try again after some time !');
        }
        return redirect()->to(base_url('case/crud?tab=affirmation'));
        exit(0);
    }
    
    
    function gen_application_number() {        
        $this->db->SELECT('esign_appli_num,esign_appli_date');
        $this->db->WHERE('entry_for_type', $_SESSION['estab_details']['efiling_for_type_id']);
        $this->db->WHERE('ref_m_establishment_id', $_SESSION['estab_details']['efiling_for_id']);
        $this->db->FROM('efil.m_tbl_efiling_no');
        $query = $this->db->get();
        $row = $query->result_array();
        $pre_application_num = $row[0]['esign_appli_num'];
        $date = $row[0]['esign_appli_date'];        
        if ($date < date('Y-m-d')) {
            $new_date = date('Y-m-d');
            $update_data = array('esign_appli_num' => 1,
                'esign_appli_date' => $new_date);
            $this->db->WHERE('esign_appli_num', $pre_application_num);
            $this->db->WHERE('esign_appli_date', $date);
            $this->db->WHERE('entry_for_type', $_SESSION['estab_details']['efiling_for_type_id']);
            $this->db->WHERE('ref_m_establishment_id', $_SESSION['estab_details']['efiling_for_id']);
            $this->db->UPDATE('efil.m_tbl_efiling_no', $update_data);
            if ($this->db->affected_rows() > 0) {
                return true;
            } else {
                $this->gen_application_number();
            }
        } else {
            $gen_application_num = $pre_application_num + 1;
            $application_info = array('esign_appli_num' => $gen_application_num);
            
            $this->db->WHERE('esign_appli_num', $pre_application_num);
            $this->db->WHERE('entry_for_type', $_SESSION['estab_details']['efiling_for_type_id']);
            $this->db->WHERE('ref_m_establishment_id', $_SESSION['estab_details']['efiling_for_id']);
            $result = $this->db->UPDATE('efil.m_tbl_efiling_no', $application_info);
            if ($this->db->affected_rows() > 0) {
                return $gen_application_num;
            } else {
                $this->gen_application_number();
            }
        }
    }
    
    public function get_signers() {
        $dropDownOptions = '<option value="" selected="true" disabled="disabled">Select Signer</option>';
        if($_SESSION['efiling_details']['efiling_type'] == 'new_case'){
            $signer_list=$this->Esign_signature_model->get_signers_list($_SESSION['efiling_details']['registration_id'], $_POST['type']);
            foreach ($signer_list as $dataRes) {
                $text = '['.$dataRes['p_r_type'].'-'.$dataRes['party_no'].'] ['.$dataRes['m_a_type'].'] '.$dataRes['party_name'].(($dataRes['state_name']!='')?', ':'').$dataRes['state_name'];
                $dropDownOptions .= '<option value="' . escape_data(url_encryption($dataRes['id'] . '#$' . $dataRes['m_a_type'])) . '">' . escape_data(strtoupper($text)) . '</option>';
            }
        } else if($_SESSION['efiling_details']['efiling_type'] == 'IA'){
            $signer_list = $this->Appearing_for_model->get_case_parties_list($_SESSION['efiling_details']['registration_id']);
            foreach ($signer_list as $dataRes) {
                $text = '['.$_POST['type'].'-'.(($_POST['type']=='P')?$dataRes['p_sr_no_show']:$dataRes['p_sr_no_show']).'] '.(($_POST['type']=='P')?$dataRes['p_partyname']:$dataRes['r_partyname']);
                $dropDownOptions .= '<option value="' . escape_data(url_encryption($dataRes['id'] . '#$M' )) . '">' . escape_data(strtoupper($text)) . '</option>';
            }
        }        
        echo $dropDownOptions;
    }
    
    /************ Called from Link sent on Email *********************/
    public function sign_certificate($efiling_num, $uuid) {
        $data['signer_list']=$this->Esign_signature_model->get_data('efil.esign_certificate', array('uuid'=>$uuid, 'efiling_no'=>strtoupper($efiling_num), 'is_locked'=>'true'));
        if(strtolower($data['signer_list'][0]['efiling_type'])=='new_case'){
            $data['party_details']=$this->Esign_signature_model->get_data('efil.tbl_case_parties', array('id'=>$data['signer_list'][0]['ref_tbl_case_parties_id'], 'registration_id'=>$data['signer_list'][0]['registration_id']));
        } else{
            $party_details=$this->Esign_signature_model->get_data('efil.tbl_sci_cases', array('id'=>$data['signer_list'][0]['ref_tbl_case_parties_id']));
            if($data['signer_list'][0]['pet_res_flag']=='P')
                $data['party_details'] = array(array('party_name'=>$party_details[0]['p_partyname'], 'p_r_type'=>$data['signer_list'][0]['pet_res_flag']));
            else if($data['signer_list'][0]['pet_res_flag']=='R')
                $data['party_details'] = array(array('party_name'=>$party_details[0]['r_partyname'], 'p_r_type'=>$data['signer_list'][0]['pet_res_flag']));
        }
        $file_path = base_url("affirmation/viewUnsignedCertificate/get_certificate/".$data['signer_list'][0]['estab_code']."/".$data['signer_list'][0]['efiling_no']);
        $data['sign_count']=countStringInFile($file_path, 'adbe.pkcs7.detached');
        if($data['signer_list'][0]['is_locked'])
            $this->load->view('affirmation/mail_affirmation_view', $data);
        else
            show_error('This page no longer exists.', '505', $heading = 'An Error Was Encountered');
    }
    
    public function mail_signed_response($efiling_num, $uuid) {
        $efiling_num= strtoupper($efiling_num);
        $param = json_decode(base64_decode($_POST['response']), true);
        if(isset($_POST, $param['doc_id'], $param['code'], $param['signed_pdf_url']) && !empty($param['doc_id']) && !empty($param['code']) && $param['code']=='1'){
            $signer_list=$this->Esign_signature_model->get_data('efil.esign_certificate', array('uuid'=>$uuid, 'efiling_no'=>strtoupper($efiling_num), 'is_locked'=>'true'));
            $last_signed_file_dir = getcwd() . '/uploaded_docs/' . $signer_list[0]['estab_code'] . '/' . $efiling_num . '/unsigned_pdf/';
            $last_signed_file = $last_signed_file_dir.$efiling_num . '_Advocate_UnsignedCertificate.pdf';
            $new_file_name = $last_signed_file;
            $arrContextOptions=array(
                "ssl"=>array(
                    "verify_peer"=>false,
                    "verify_peer_name"=>false,
                ),
            );            
            $signed_file_contents = file_get_contents($param['signed_pdf_url'], false, stream_context_create($arrContextOptions));
            $file_info = new finfo(FILEINFO_MIME_TYPE);
            $mime_type = $file_info->buffer($signed_file_contents);
            if($signed_file_contents && $mime_type=='application/pdf') {
                if(file_exists($last_signed_file)) {
                    $increment = 0;
                    list($name, $ext) = explode('.', $last_signed_file);
                    while(file_exists($last_signed_file)) {
                        $increment++;
                        $last_signed_file = $name. '__'.$increment . '.' . $ext;
                        $filename = $name. '__'.$increment . '.' . $ext;
                    }
                    $name_for_last_signed_file = str_replace($last_signed_file_dir,"",$filename);
                    rename($new_file_name, $filename);
                    chmod($filename,0777);
                }                
                if(copy($param['signed_pdf_url'], $new_file_name, stream_context_create($arrContextOptions))){
                    $sign_count = countStringInFile($new_file_name, 'adbe.pkcs7.detached');
                    $this->Esign_signature_model->update_data('efil.esign_certificate',array('is_locked'=>'false', 'file_name'=>trim($efiling_num . '_Advocate_UnsignedCertificate.pdf'), 'event_description'=>'Successfully signed', 'is_certificate_signed'=>'true', 'signed_on'=>date('Y-m-d H:i:s'), 'signature_count'=>$sign_count), array('uuid'=>$uuid, 'efiling_no'=>strtoupper($efiling_num), 'is_locked'=>'true'));
                    $this->Esign_signature_model->update_data('efil.esign_certificate',array('file_name'=>trim($name_for_last_signed_file), 'event_description'=>'Renamed file on '.date('Y-m-d H:i:s')), "uuid!='$uuid' and efiling_no='$efiling_num' and file_name='".trim($efiling_num . '_Advocate_UnsignedCertificate.pdf')."'");
                    $this->send_email_for_signature($efiling_num,'SCIN01');
                    show_error('Certificate has been signed successfully.', '200', $heading = 'Success');
                }
            } else{
                $this->Esign_signature_model->update_data('efil.esign_certificate',array('event_description'=>'Error @ '.now().': Mime Type of file is not PDF - '.$mime_type), "uuid='$uuid' and efiling_no='$efiling_num'");
                show_error('Mime Type of file is not PDF - '.$mime_type, '501', $heading = 'An Error Was Encountered');
                log_message('CUSTOM', "Error @ ".now().": Mime Type of file is not PDF - ".$mime_type.", uuid=".$uuid." and efiling_no=".$efiling_num);
            }
        } else{
            $this->Esign_signature_model->update_data('efil.esign_certificate',array('event_description'=>'Error @ '.date('Y-m-d H:i:s').': Incorrect parameters or Signing Failed'), "uuid='$uuid' and efiling_no='$efiling_num'");
            show_error('Incorrect parameters or Signing Failed. Please try again.', '502', $heading = 'An Error Was Encountered');
            log_message('CUSTOM', "Incorrect parameters or Signing Failed. Please try again.");
        }
    }

}