<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 3/6/21
 * Time: 11:43 AM
 */

class Esign_docs extends CI_Controller
{
    public function __construct()
    {
        
        parent::__construct();
        $this->load->model('supplements/esign_docs_model');
        $this->load->helper('file');
        $this->load->helper('esign');
    }
    
    public function esign_request(){
        $registration_id= $_SESSION['efiling_details']['registration_id'];
        $efiling_num = $_SESSION['efiling_details']['efiling_no'];
        $bytes = random_bytes(20);
        $uuid = (bin2hex($bytes)).date('YmdHis');
        $doc_type = trim($_POST['doc_type']);
        $sign_array = array(
            'email'=>strtolower(trim($_POST['email_id'])),
            'registration_id' => $registration_id,
            'sign_url' =>'',
            'request_sent_on'=>NULL,
            'is_doc_signed'=>'false',
            'is_deleted'=>'false',
            'uuid'=>$uuid,
            'efiling_no' => $efiling_num,
            'doc_type'=>$doc_type,
        );
        $txn_status = $this->esign_docs_model->insert_data('efil.esign_docs', $sign_array);
        $this->send_email_for_signature($efiling_num, $registration_id, $doc_type);
    }
    
    private function send_email_for_signature($efiling_num, $registration_id, $doc_type){
        $sign_status = $this->esign_docs_model->table_data('efil.esign_docs', array('efiling_no'=>$efiling_num, 'registration_id'=>$registration_id, 'doc_type'=>$doc_type, 'is_deleted'=>'false', 'is_doc_signed'=>'false'));
        $uuid=$sign_status[0]['uuid'];
        $link = base_url("supplements/Esign_docs/sign_doc/".strtolower($efiling_num)."/".$registration_id."/$uuid");
        $set_sign_link = $this->esign_docs_model->update_data('efil.esign_docs', array('sign_url'=>$link,'request_sent_on'=>date('Y-m-d H:i:s')), array('id'=>$sign_status[0]['id'], 'is_deleted'=>'false','uuid'=>$uuid, 'registration_id'=>$sign_status[0]['registration_id']));
        $case_detail = $this->esign_docs_model->table_data('efil.tbl_case_details', array('registration_id'=>$registration_id));
        $sc_case_type_id = $case_detail[0]['sc_case_type_id'];
        
        $msg="Sir/Madam,
        
Please find attached affidavit for efiling application Registration Number -$efiling_num.
Click the given link to sign the certificate using your Aadhaar Number.
$link


###  This is automatically generated email, please do not reply. For any suggestion/feedback please contact Computer Cell through e-mail: itcell@sci.nic.in";
        $file_name = $efiling_num . '_Unsigned_Affidavit.pdf';
        $file_path = base_url("supplements/Esign_docs/get_doc/$sc_case_type_id/$efiling_num");
        $result = send_email($sign_status[0]['email'], "Document Sign request for Registration ID-$efiling_num", $msg, $file_path, $file_name);
        $_SESSION['MSG'] = message_show("success", 'Mail sent successfully.');
    }
    
    public function get_doc($sc_case_type_id, $efiling_num){
        $efiling_num = strtoupper($efiling_num);
        $file_name = $efiling_num . '_Unsigned_Affidavit.pdf';
        $unsigned_pdf_path = 'uploaded_docs/supplement/' . $efiling_num . '/'.$sc_case_type_id.'/unsigned_pdf/';
        $data['pdf']['file_path'] = $unsigned_pdf_path.$file_name;
        $data['pdf']['file_name'] = $file_name;
        $this->load->view('affirmation/view_certificate_pdf', $data);
    }
    
    /************ Called from Link sent on Email *********************/
    public function sign_doc($efiling_num, $registration_id, $uuid){
        $data['signer_list']=$this->esign_docs_model->table_data('efil.esign_docs', array('uuid'=>$uuid, 'efiling_no'=>strtoupper($efiling_num), 'registration_id'=>$registration_id, 'is_deleted'=>'false'));
        $case_detail = $this->esign_docs_model->table_data('efil.tbl_case_details', array('registration_id'=>$registration_id));
        $sc_case_type_id = $case_detail[0]['sc_case_type_id'];
        $data['file_path'] = base_url("supplements/Esign_docs/get_doc/$sc_case_type_id/$efiling_num");
        $data['response_uri'] = base_url("supplements/Esign_docs/mail_signed_response/$sc_case_type_id/$efiling_num/$uuid");
        $data['sign_count']=countStringInFile($data['file_path'], 'adbe.pkcs7.detached');
        if($data['signer_list'][0]['is_deleted']=='f')
            $this->load->view('supplements/esign//mail_affirmation_view', $data);
        else
            show_error('This page no longer exists.', '505', $heading = 'An Error Was Encountered');
    }
    
    
    public function mail_signed_response($sc_case_type_id, $efiling_num, $uuid){
        $efiling_num= strtoupper($efiling_num);
        $param = json_decode(base64_decode($_POST['response']), true);
        if(isset($_POST, $param['doc_id'], $param['code'], $param['signed_pdf_url']) && !empty($param['doc_id']) && !empty($param['code']) && $param['code']=='1'){
            $signed_file_dir = getcwd() . 'uploaded_docs/supplement/' . $efiling_num . '/'.$sc_case_type_id.'/signed_pdf/';
            $signed_file = $signed_file_dir.$efiling_num . '_Advocate_SignedCertificate.pdf';
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
                
                if(copy($param['signed_pdf_url'], $signed_file, stream_context_create($arrContextOptions))){
                    $this->esign_docs_model->update_data('efil.esign_docs',array('is_doc_signed'=>'true', 'event_description'=>'Successfully signed', 'signed_on'=>date('Y-m-d H:i:s')), array('uuid'=>$uuid, 'efiling_no'=>strtoupper($efiling_num), 'is_deleted'=>'false'));
                    show_error('Certificate has been signed successfully.', '200', $heading = 'Success');
                }
            }
            else{
                $this->esign_docs_model->update_data('efil.esign_docs',array('event_description'=>'Error @ '.date().': Mime Type of file is not PDF - '.$mime_type), "uuid='$uuid' and efiling_no='$efiling_num'");
                show_error('Mime Type of file is not PDF - '.$mime_type, '501', $heading = 'An Error Was Encountered');
            }
        }
        else{
            $this->esign_docs_model->update_data('efil.esign_docs',array('event_description'=>'Error @ '.date('Y-m-d H:i:s').': Incorrect parameters or Signing Failed'), "uuid='$uuid' and efiling_no='$efiling_num'");
            show_error('Incorrect parameters or Signing Failed. Please try again.', '502', $heading = 'An Error Was Encountered');
        }
    }
    
}