<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class FinalSubmit extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('userActions/UserActions_model');
    }

    public function index() {

        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON);

        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            redirect('login');
            exit(0);
        }

        if (empty($_SESSION['efiling_details']) || empty($_SESSION['estab_details'])) {
            $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Invalid Action.</div>');
            redirect('dashboard');
            exit(0);
        }

        $registration_id = $_SESSION['efiling_details']['registration_id'];

        if ($_SESSION['efiling_details']['stage_id'] == Draft_Stage) {
            $next_stage = Initial_Approaval_Pending_Stage;
        } elseif ($_SESSION['efiling_details']['stage_id'] == Initial_Defected_Stage) {
            $next_stage = Initial_Defects_Cured_Stage;
        } elseif ($_SESSION['efiling_details']['stage_id'] == I_B_Defected_Stage) {
            $next_stage = I_B_Defects_Cured_Stage;
        } else {
            $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Invalid Action.</div>');
            redirect('dashboard');
            exit(0);
        }

        $result = $this->UserActions_model->finalSubmit($registration_id, $next_stage);

        if ($result) {
            
            if($next_stage == Draft_Stage){
                $subject = "Submitted : Efiling no. " . efile_preview($_SESSION['efiling_details']['efiling_no']);
                $sentSMS = "Efiling no. " . efile_preview($_SESSION['efiling_details']['efiling_no']) . " has been submitted and is pending for initial approval with efiling admin. - Supreme Court of India";
                $smsTemplate=SCISMS_Initial_Approval;
                $this->session->set_flashdata('msg', '<div class="alert alert-success text-center"> E-filing number ' . efile_preview($_SESSION['efiling_details']['efiling_no']) . ' submitted successfully for approval of E-filing Admin.!</div>');
            }elseif($next_stage == Initial_Defected_Stage){
                $subject = "Re-Submitted : Efiling no. " . efile_preview($_SESSION['efiling_details']['efiling_no']);
                $sentSMS = "Efiling no. " . efile_preview($_SESSION['efiling_details']['efiling_no']) . " has been submitted again after curing initial defects and is pending for initial approval with efiling admin. - Supreme Court of India";
                $smsTemplate=SCISMS_Submission_after_defect_curing;
                $this->session->set_flashdata('msg', '<div class="alert alert-success text-center"> E-filing number ' . efile_preview($_SESSION['efiling_details']['efiling_no']) . ' submitted successfully for approval of E-filing Admin.!</div>');
            }elseif($next_stage == I_B_Defected_Stage){
                $subject = "Re-filed : Efiling no. " . efile_preview($_SESSION['efiling_details']['efiling_no']);
                $sentSMS = "Efiling no. " . efile_preview($_SESSION['efiling_details']['efiling_no']) . " refiled after curing defects. - Supreme Court of India";
                send_whatsapp_message($registration_id,$_SESSION['efiling_details']['efiling_no']," REFILED");
                $smsTemplate=SCISMS_Efiling_refiled;
                $this->session->set_flashdata('msg', '<div class="alert alert-success text-center"> E-filing number ' . efile_preview($_SESSION['efiling_details']['efiling_no']) . ' refiled successfully.!</div>');
            }

            $user_name = $_SESSION['login']['first_name'] . ' ' . $_SESSION['login']['last_name'];

            send_mobile_sms($_SESSION['login']['mobile_number'], $sentSMS,$smsTemplate);
            send_mail_msg($_SESSION['login']['emailid'], $subject, $sentSMS, $user_name);
            
            redirect('dashboard');
            exit(0);
            
        } else {
            $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Submition failed. Please try again!</div>');
            redirect('dashboard');
            exit(0);
        }
    }

}
