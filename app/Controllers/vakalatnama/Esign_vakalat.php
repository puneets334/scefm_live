<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 3/6/21
 * Time: 11:43 AM
 */

class Esign_vakalat extends CI_Controller
{
    public function __construct()
    {
        
        parent::__construct();
        $this->load->model('supplements/esign_docs_model');
        $this->load->model('vakalatnama/vakalatnama_model');
        $this->load->helper('file');
        $this->load->helper('esign');
    }
    
    public function esign_request($param){
        $param_Data=url_decryption(trim($param));
        $paramData=explode('#',$param_Data);
        $vakalat_id=$paramData[1];
        $parties = $this->esign_docs_model->table_data('vakalat.tbl_vakalatnama_parties', array('vakalatnama_id'=>$vakalat_id, 'is_deleted'=>FALSE, 'signed'=>FALSE));
        if(empty($parties)){
            return 0;
        }
        $bytes = random_bytes(20);
        $uuid = (bin2hex($bytes)).date('YmdHis');
        $sign_array = array(
            'sign_url' =>'',
            'sign_request_sent_on'=>null,
            'signed'=>'false',
            'uuid'=>$uuid,
        );
        $txn_status = $this->esign_docs_model->update_data('vakalat.tbl_vakalatnama_parties', $sign_array, array('vakalatnama_id'=>$vakalat_id, 'id'=>$parties[0]['id']));
        $this->send_email_for_signature($parties[0]['id'], $vakalat_id, $uuid, $parties[0]['party_email_id']);
    }
    
    private function send_email_for_signature($party_id, $vakalat_id, $uuid, $email_id){
        $link = base_url("vakalatnama/Esign_vakalat/sign_doc/".$party_id."/".$vakalat_id."/$uuid");
        $set_sign_link = $this->esign_docs_model->update_data('vakalat.tbl_vakalatnama_parties', array('sign_url'=>$link,'sign_request_sent_on'=>date('Y-m-d H:i:s')), array('vakalatnama_id'=>$vakalat_id, 'id'=>$party_id,'uuid'=>$uuid));
        
        $msg="Sir/Madam,
        
Please find attached vakalatnama for e-filing application Registration Number - VKT$vakalat_id.
Click the given link to sign the certificate using your Aadhaar Number.
$link


###  This is automatically generated email, please do not reply. For any suggestion/feedback please contact Computer Cell through e-mail: itcell@sci.nic.in";
        $file_name = $vakalat_id . '_UnsignedVakalatnama.pdf';
        $efiling_num = $_SESSION['efiling_details']['efiling_no'];
        $file_path = base_url("vakalatnama/Esign_vakalat/get_doc/".url_encryption($party_id."#".$vakalat_id));
        $result = send_email($email_id, "Vakalatnama Sign request for Registration ID-$efiling_num", $msg, $file_path, $file_name);
        $_SESSION['MSG'] = message_show("success", 'Mail sent successfully.');
    }
    
    public function get_doc($param, $control=0){
        if($control==0) {
            $param_Data = url_decryption(trim($param));
            $paramData = explode('#', $param_Data);
            $vakalat_id = $paramData[1];
        }
        if($control==1){
            $vakalat_id=openssl_decrypt($param,"AES-128-ECB",'sc-efmAppWeb');
        }
        $vakalat_detail = $this->esign_docs_model->table_data('vakalat.tbl_vakalatnama', array('id'=>$vakalat_id));
        if($vakalat_detail[0]['advocate_sign']=='f'){
            $file_name = $vakalat_id . '_UnsignedVakalatnama.pdf';
            $pdf_path = 'uploaded_docs/vakalatnama/' . $vakalat_detail[0]['database_type'].$vakalat_detail[0]['registration_id'] . '/unsigned_pdf/';
        }
        elseif ($vakalat_detail[0]['advocate_sign']=='t'){
            $file_name = $vakalat_id . '_SignedVakalatnama.pdf';
            $pdf_path = 'uploaded_docs/vakalatnama/' . $vakalat_detail[0]['database_type'].$vakalat_detail[0]['registration_id'] . '/signed_pdf/';
        }
        $data['pdf']['file_path'] = $pdf_path.$file_name;
        $data['pdf']['file_name'] = $file_name;
        $this->load->view('affirmation/view_certificate_pdf', $data);
    }
    
    
    
    /************ Called from Link sent on Email *********************/
    public function sign_doc($party_id, $vakalat_id, $uuid){
        $signer=$this->esign_docs_model->table_data('vakalat.tbl_vakalatnama_parties', array('uuid'=>$uuid, 'id'=>$party_id, 'vakalatnama_id'=>$vakalat_id, 'is_deleted'=>'false'));
        $data['signer_list'] = array(array('id'=>$signer[0]['id'], 'doc_type'=>DOC_TYPE_VAKALATNAMA, 'email'=>$signer[0]['party_name']));
        $data['file_path'] = base_url("vakalatnama/Esign_vakalat/get_doc/".url_encryption($party_id."#".$vakalat_id));
        $data['response_uri'] = base_url("vakalatnama/Esign_vakalat/mail_signed_response/$party_id/$vakalat_id/$uuid");
        $data['sign_count']=countStringInFile($data['file_path'], 'adbe.pkcs7.detached');
        if($signer[0]['is_deleted']=='f')
            $this->load->view('supplements/esign//mail_affirmation_view', $data);
        else
            show_error('This page no longer exists.', '505', $heading = 'An Error Was Encountered');
    }
    
    
    public function mail_signed_response($party_id, $vakalat_id, $uuid){
        $vakalat_detail = $this->esign_docs_model->table_data('vakalat.tbl_vakalatnama', array('id'=>$vakalat_id));
        $param = json_decode(base64_decode($_POST['response']), true);
        if(isset($_POST, $param['doc_id'], $param['code'], $param['signed_pdf_url']) && !empty($param['doc_id']) && !empty($param['code']) && $param['code']=='1'){
        //$param['signed_pdf_url']="http://10.40.186.15/esigner_dir/90/temp_file_$party_id.pdf";
        //if(isset($_POST, $param['doc_id'], $param['code'], $param['signed_pdf_url']) && !empty($param['doc_id'])  ){
            $last_signed_file_dir = 'uploaded_docs/vakalatnama/' . $vakalat_detail[0]['database_type'].$vakalat_detail[0]['registration_id'] . '/unsigned_pdf/';
            $last_signed_file = $last_signed_file_dir.$vakalat_id . '_UnsignedVakalatnama.pdf';
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
                if(file_exists($last_signed_file)){
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
                    $this->esign_docs_model->update_data('vakalat.tbl_vakalatnama_parties',array('signed'=>'true', 'signed_on'=>date('Y-m-d H:i:s'),'event_description'=>'Successfully signed',), array('uuid'=>$uuid, 'id'=>$party_id, 'is_deleted'=>'false'));
                    $parameters = url_encryption('0#'.$vakalat_id);
                    $this->esign_request($parameters);
                    show_error('Vakalatnama has been signed successfully.', '200', $heading = 'Success');
                }
            }
            
            else{
                $this->esign_docs_model->update_data('vakalat.tbl_vakalatnama_parties',array('event_description'=>'Error @ '.date().': Mime Type of file is not PDF - '.$mime_type), "uuid='$uuid' and id='$party_id'");
                show_error('Mime Type of file is not PDF - '.$mime_type, '501', $heading = 'An Error Was Encountered');
            }
        }
        else{
            $this->esign_docs_model->update_data('vakalat.tbl_vakalatnama_parties',array('event_description'=>'Error @ '.date('Y-m-d H:i:s').': Incorrect parameters or Signing Failed'), "uuid='$uuid' and id='$party_id'");
            show_error('Incorrect parameters or Signing Failed. Please try again.', '502', $heading = 'An Error Was Encountered');
        }
    }
    
    
    /*************** Advocate Vakalatnama Esign ****************/
    public function advocate_esign_request($param){
        $param_Data=url_decryption(trim($param));
        $paramData=explode('#',$param_Data);
        $vakalat_id=$paramData[1];
        $signers=$this->esign_docs_model->table_data('vakalat.tbl_vakalatnama_parties', array('vakalatnama_id'=>$vakalat_id, 'is_deleted'=>'false', 'signed'=>'false'));
        if(empty($signers[0]) && isset($_SESSION['login'])){
            $data['signer_list'] = array(array('id'=>$_SESSION['login']['id'], 'doc_type'=>DOC_TYPE_VAKALATNAMA, 'email'=>$_SESSION['login']['first_name'].' '.$_SESSION['login']['last_name']));
            $encrypted_string=openssl_encrypt($vakalat_id,"AES-128-ECB",'sc-efmAppWeb');
            $data['file_path'] = base_url("vakalatnama/Esign_vakalat/get_doc/".$encrypted_string."/1");$data['response_uri'] = base_url("vakalatnama/Esign_vakalat/advocate_signature_response/$param");
            $data['sign_count']=countStringInFile($data['file_path'], 'adbe.pkcs7.detached');
            $this->load->view('supplements/esign//mail_affirmation_view', $data);
        }
        else{
            // 1 of the parties has not signed.
            exit(0);
        }
    }
    
    public function advocate_signature_response($param){
        $param_Data=url_decryption(trim($param));
        $paramData=explode('#',$param_Data);
        $vakalat_id=$paramData[1];
        $vakalat_detail = $this->esign_docs_model->table_data('vakalat.tbl_vakalatnama', array('id'=>$vakalat_id));
        $response = json_decode(base64_decode($_POST['response']), true);
        //$response['signed_pdf_url']="http://10.40.186.15/esigner_dir/90/temp_file_1.pdf";
        //if(isset($_POST, $response['doc_id'], $response['code'], $response['signed_pdf_url']) && !empty($response['doc_id']) ){
        if(isset($_POST, $response['doc_id'], $response['code'], $response['signed_pdf_url']) && !empty($response['doc_id']) && !empty($response['code']) && $response['code']=='1'){
            $signed_file_dir = 'uploaded_docs/vakalatnama/' . $vakalat_detail[0]['database_type'].$vakalat_detail[0]['registration_id'] . '/signed_pdf/';
            $signed_file = $signed_file_dir.$vakalat_id . '_SignedVakalatnama.pdf';
            if (!is_dir($signed_file_dir)) {
                $uold = umask(0);
                if (mkdir($signed_file_dir, 0777, true)) {
                    $html = '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
                    write_file($signed_file_dir . '/index.html', $html);
                }
                umask($uold);
            }
            $arrContextOptions=array(
                "ssl"=>array(
                    "verify_peer"=>false,
                    "verify_peer_name"=>false,
                ),
            );
        
            $signed_file_contents = file_get_contents($response['signed_pdf_url'], false, stream_context_create($arrContextOptions));
            $file_info = new finfo(FILEINFO_MIME_TYPE);
            $mime_type = $file_info->buffer($signed_file_contents);
            if($signed_file_contents && $mime_type=='application/pdf') {
                if(copy($response['signed_pdf_url'], $signed_file, stream_context_create($arrContextOptions))){
                    $sign_count = countStringInFile($signed_file, 'adbe.pkcs7.detached');
                    $this->esign_docs_model->update_data('vakalat.tbl_vakalatnama',array('advocate_sign'=>'true', 'signed_on'=>date('Y-m-d H:i:s'),'event_description'=>'Successfully signed'), array('id'=>$vakalat_id, 'is_deleted'=>'false'));
                    show_error('Vakalatnama has been signed successfully by AOR.', '200', $heading = 'Success');
                }
            }
        
            else{
                $this->esign_docs_model->update_data('vakalat.tbl_vakalatnama',array('event_description'=>'Error @ '.date().': Mime Type of file is not PDF - '.$mime_type), array('id'=>$vakalat_id, 'is_deleted'=>'false'));
                show_error('Mime Type of file is not PDF - '.$mime_type, '501', $heading = 'An Error Was Encountered');
            }
        }
        else{
            $this->esign_docs_model->update_data('vakalat.tbl_vakalatnama',array('event_description'=>'Error @ '.date('Y-m-d H:i:s').': Incorrect parameters or Signing Failed'), array('id'=>$vakalat_id, 'is_deleted'=>'false'));
            show_error('Incorrect parameters or Signing Failed. Please try again.', '502', $heading = 'An Error Was Encountered');
        }
    }
    
}