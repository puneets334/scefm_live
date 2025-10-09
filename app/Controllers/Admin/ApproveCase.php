<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Common\CommonModel;
use App\Models\NewCase\NewCaseModel;
use App\Models\Admin\EfilingActionModel;

class ApproveCase extends BaseController {

    protected $New_case_model;
    protected $Common_model;
    protected $Admin_case_action_model;

    public function __construct() {
        parent::__construct();
        $this->New_case_model = new NewCaseModel();
        $this->Common_model = new CommonModel();
        $this->Admin_case_action_model = new EfilingActionModel();
    }

    public function index() {
        $Array = array(New_Filing_Stage, Initial_Defects_Cured_Stage);
        if (!in_array($_SESSION['efiling_details']['stage_id'], $Array)) {
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center"> ' . htmlentities('Invalid Action', ENT_QUOTES) . ' </div>');
            return redirect()->to(base_url('admin'));
            exit(0);
        }
        $allowed_users_array = array(USER_ADMIN);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            return redirect()->to(base_url('dashboard'));
            exit(0);
        }
        if (empty($_SESSION['efiling_details']) || empty($_SESSION['estab_details'])) {
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center"> ' . htmlentities('Invalid Action', ENT_QUOTES) . ' </div>');
            return redirect()->to(base_url('adminDashboard'));
            exit(0);
        }
        $regid = $this->session->userdata['efiling_details']['registration_id'];
        $filing_type = $this->session->userdata['efiling_details']['ref_m_efiled_type_id'];
        // In New Case Model Folder, there is no Admin_case_action_model. So using EfilingActionModel of Admin folder
        $data = $this->Admin_case_action_model->approve_case($regid, $filing_type);
        if ($data) {
            $userdata = $this->New_case_model->get_efiled_by_user($_SESSION['efiling_details']['created_by']);
            $sentSMS = "eFiling no. " . efile_preview($_SESSION['efiling_details']['efiling_no']) . " has been initially accepted for further processing.";
            $subject = "Initially accepted : eFiling no. " . efile_preview($_SESSION['efiling_details']['efiling_no']);
            log_message('CUSTOM', "eFiling no. " . efile_preview($_SESSION['efiling_details']['efiling_no']) . " has been initially accepted for further processing.");
            $responseObj = send_mobile_sms($userdata[0]->moblie_number, $sentSMS,SCISMS_Efiling_Acceptance);
            $user_name = $userdata[0]->first_name . ' ' . $userdata[0]->last_name;
            send_mail_msg($userdata[0]->emailid, $subject, $sentSMS, $user_name);
            if ($userdata[0]->ref_m_usertype_id == USER_ADVOCATE) {
                // In Common Model Folder, there is no get_petitioner_details function in Common_model. So using get_petitioner_data function to get petioner details of NewCaseModel of NewCase Folder  
                $petitioner_details = $this->New_case_model->get_petitioner_data($regid);
                $pet_user_name = $petitioner_details[0]['pet_name'];
                send_petitioner_mobile_sms($petitioner_details[0]['mobile'], $sentSMS);
                send_petitioner_mail_msg($petitioner_details[0]['email'], $subject, $sentSMS, $pet_user_name);
                foreach ($petitioner_details as $efiling) {
                    $pet_exp_name = $efiling['name'];
                    send_petitioner_mobile_sms($efiling['exp_mobile'], $sentSMS);
                    send_petitioner_mail_msg($efiling['exp_email'], $subject, $sentSMS, $pet_exp_name);
                }
            }
            $this->session->setFlashdata('msg', '<div class="alert alert-success text-center">E-filing Number ' . efile_preview($_SESSION['efiling_details']['efiling_no']) . ' Approved successfully !</div>');
            log_message('CUSTOM', "E-filing Number ". efile_preview($_SESSION['efiling_details']['efiling_no'])."Approved successfully !");
            return redirect()->to(base_url('admin'));
            exit(0);
        } else {
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center">Approval Failded. Please try again!</div>');
            log_message('CUSTOM', "Approval Failded. Please try again!");
            if ($filing_type == E_FILING_TYPE_NEW_CASE) {
                $redirectURL = 'New_case/view';
            } elseif ($filing_type == E_FILING_TYPE_MISC_DOCS) {
                $redirectURL = 'MiscellaneousFiling/view';
            } elseif ($filing_type == E_FILING_TYPE_DEFICIT_COURT_FEE) {
                $redirectURL = 'Deficit_court_fee/view';
            } elseif ($filing_type == E_FILING_TYPE_IA) {
                $redirectURL = 'IA/view';
            } else {
                $redirectURL = 'admin';
            }
            return redirect()->to(base_url($redirectURL));
            exit(0);
        }
    }

}