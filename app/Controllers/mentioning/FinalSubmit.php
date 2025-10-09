<?php
namespace App\Controllers;

class FinalSubmit extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('mentioning/Mentioning_model');
    }

    public function index() {

        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);

        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            redirect('login');
            exit(0);
        }

        if (empty($_SESSION['efiling_details']) || empty($_SESSION['estab_details'])) {
            redirect('dashboard');
            exit(0);
        }

        $registration_id = $_SESSION['efiling_details']['registration_id'];

        if ($_SESSION['login']['ref_m_usertype_id'] == USER_CLERK) {
            $next_stage = Draft_Stage;
        } elseif ($_SESSION['efiling_details']['stage_id'] == Draft_Stage) {
            $next_stage = Initial_Approaval_Pending_Stage;
        } elseif ($_SESSION['efiling_details']['stage_id'] == Initial_Defected_Stage) {
            $next_stage = Initial_Defects_Cured_Stage;
        } else {
            $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Invalid Action.</div>');
            redirect('dashboard');
        }

        $final_submit = TRUE;
        if ($final_submit) {

            $result = $this->Mentioning_model->updateCaseStatus($registration_id, $next_stage);

            if ($result) {

                $sentSMS = "Efiling no. " . efile_preview($_SESSION['efiling_details']['efiling_no']) . " has been submitted and is pending for directions with Competent Authority. - Supreme Court of India";
                $subject = "Submitted : Efiling no. " . efile_preview($_SESSION['efiling_details']['efiling_no']);

                $user_name = $_SESSION['login']['first_name'] . ' ' . $_SESSION['login']['last_name'];

                send_mobile_sms($_SESSION['login']['mobile_number'], $sentSMS,SCISMS_Mentioning_Pending);
                send_mail_msg($_SESSION['login']['emailid'], $subject, $sentSMS, $user_name);

                $this->session->set_flashdata('msg', '<div class="alert alert-success text-center"> E-filing number ' . efile_preview($_SESSION['efiling_details']['efiling_no']) . ' submitted successfully for approval of Competent Authorty.!</div>');
                redirect('dashboard');
                exit(0);
            } else {
                $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Submition failed. Please try again!</div>');
                redirect('dashboard');
                exit(0);
            }
        }
    }

}
