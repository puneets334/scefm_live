<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ajaxcalls_doc_share extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('shareDoc/Share_docs_model');
    }

    function add_share_email() {
        //  $input_count = count($_POST['name']);
        //    $doc_email = array();
        //   for ($i = 0; $i < $input_count; $i++) {
        //   print_r($_POST);die;

        $is_contact=$_POST['contact'];

        if ($is_contact==1){
            if (empty($_POST['name'])) {
                echo '1@@@' . htmlentities('Name is required', ENT_QUOTES);
                exit(0);
            }
            if (empty($_POST['email'])) {
                echo '1@@@' . htmlentities('Email is required', ENT_QUOTES);
                exit(0);
            }

        }else if ($is_contact==2){
            if (empty($_POST['name'])) {
                echo '1@@@' . htmlentities('Contact is required', ENT_QUOTES);
                exit(0);
            }
            if (empty($_POST['email'])) {
                echo '1@@@' . htmlentities('Contact is required', ENT_QUOTES);
                exit(0);
            }
        }else if ($is_contact==3){
            if (empty($_POST['name'])) {
                echo '1@@@' . htmlentities('Contact is required', ENT_QUOTES);
                exit(0);
            }
            if (empty($_POST['email'])) {
                echo '1@@@' . htmlentities('Contact is required', ENT_QUOTES);
                exit(0);
            }
        }else if ($is_contact==4){
            $case_aor_cotacts=$_POST['case_aor_cotacts'];
            if (sizeof($case_aor_cotacts)==0){
                echo '1@@@' . htmlentities('Name and email one row are required', ENT_QUOTES);
                exit(0);
            }
        }


        $checkname=true;
        if(isset($_POST['case_aor_cotacts'])){
            $checkname=false;
        }
        if($checkname){
            if (empty($_POST['name'])) {
                echo '1@@@' . htmlentities('Enter Name', ENT_QUOTES);
                exit(0);
            }
            ////Code added on 1 Feb 2021 only update columns added
            $doc_email = array(
                'registration_id' => $_SESSION['efiling_details']['registration_id'],
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'updated_by' => $_SESSION['login']['id'],
                   'updated_on' => date('Y-m-d H:i:s'),
                   'update_ip_address' => getClientIP(),
            );
            $result = $this->Share_docs_model->add_doc_share_email($doc_email);
        }
        else{
            $case_aor_cotacts=$_POST['case_aor_cotacts'];
            foreach($case_aor_cotacts as $contact){
                $data=explode('$$',$contact);
                $doc_email = array(
                    'registration_id' => $_SESSION['efiling_details']['registration_id'],
                    'name' => $data[0],
                    'email' => $data[1],

                    'updated_by' => $_SESSION['login']['id'],
                    'updated_on' => date('Y-m-d H:i:s'),
                    'update_ip_address' => getClientIP(),
                );
                $result = $this->Share_docs_model->add_doc_share_email($doc_email);
            }
        }
        // }

        if ($result) {
            $registration_id=$_SESSION['efiling_details']['registration_id'];
            $update_breadcrumb_status = $this->Share_docs_model->update_breadcrumbs($registration_id, MISC_BREAD_SHARE_DOC);
            echo '2@@@' . htmlentities('Email added successfully!', ENT_QUOTES);
            exit(0);
        }

        $this->load->view('templates/header');
        $this->load->view('shareDoc/share_document');
        $this->load->view('templates/footer');
    }

    //Function added on 29 jan 21 to delete user
    function deleteUserfromShareDoc() {

        $input_array = explode('$$',url_decryption(escape_data($this->input->post("form_submit_val"))));

        if (count($input_array) != 2) {
            echo '1@@@Invalid Action';
        }

        $user_id = $input_array[0];
        $registration_id = $input_array[1];

        if (empty($user_id)) {
            echo '1@@@Invalid attempt.';
            exit(0);
        }

        if ($registration_id != $_SESSION['efiling_details']['registration_id']) {
            echo '1@@@Invalid attempt.';
            exit(0);
        }

         $result = $this->Share_docs_model->deleteUserfromShareDoc($user_id, $registration_id);

        if ($result) {
            echo '2@@@User deleted successfully !';
            exit(0);
        } else {
            echo '1@@@Some Error ! Please try after some time.';
            exit(0);
        }
    }

}
