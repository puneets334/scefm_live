<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class DefaultController extends CI_Controller {

    public function __construct() {

        parent::__construct();
        if (!isset($_SESSION['login']) && empty($_SESSION['login'])) {
            redirect('login');
        }else{
            is_user_status();
        }

        $this->load->model('common/Common_model');
        $this->load->model('newcase/New_case_model');
        $this->load->model('newcase/Dropdown_list_model');
        $this->load->model('newcase/New_case_support_model');

        unset($_SESSION['efiling_details']);
        unset($_SESSION['estab_details']);
        unset($_SESSION['case_table_ids']);
        unset($_SESSION['parties_list']);
        unset($_SESSION['efiling_type']);
        unset($_SESSION['pg_request_payment_details']);
        unset($_SESSION['eVerified_mobile_otp']['LITIGENT_MOB_OTP_VERIFY']);
    }

    public function _remap($param = NULL) {

        if ($param == 'index') {
            $this->index(NULL);
        } else {
            $this->index($param);
        }
    }

    public function index($id = NULL) {
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_DEPARTMENT, USER_ADMIN);

        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            redirect('login');
            exit(0);
        }

        if ($id) {

            $id = url_decryption($id);
            $InputArrray = explode('#', $id);   //0=>registration_id,1=>type,2=>stage            
//print_r($InputArrray);die;
            if (!is_numeric($InputArrray[0]) || $InputArrray[1] != E_FILING_TYPE_NEW_CASE || !is_numeric($InputArrray[2])) {
                redirect('login');
                exit(0);
            }

            $registration_id = $InputArrray[0];

            $_SESSION['regid'] = $InputArrray[0];

            // SETS $_SESSION['estab_details']
            $estab_details = $this->Common_model->get_establishment_details();//print_r($_SESSION);die;
            if ($estab_details) {

                //SET $_SESSION['efiling_details']
                $efiling_num_details = $this->Common_model->get_efiling_num_basic_Details($registration_id);
            } else {
                redirect('login');
                exit(0);
            }
        } else {

            // SETS $_SESSION['estab_details']
            $estab_details = $this->Common_model->get_establishment_details();

            if ($estab_details) {
                redirect('newcaseQF/caseDetails');
                exit(0);
            }
        }

        if (isset($_SESSION['efiling_details']) && !empty($_SESSION['efiling_details'])) { 

            $stages_array = array(Draft_Stage, Initial_Defected_Stage, I_B_Defected_Stage);

            $allowed_users = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);

            if ($_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN || !in_array($_SESSION['efiling_details']['stage_id'], $stages_array)) {

                redirect('newcaseQF/view');
                exit(0);
            } elseif ((in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users) && in_array($_SESSION['efiling_details']['stage_id'], $stages_array)) || ($_SESSION['login']['ref_m_usertype_id'] = USER_DEPARTMENT && $_SESSION['efiling_details']['stage_id'] == Draft_Stage )) {
                /*echo "Start ";
                echo max(explode(',', $_SESSION['efiling_details']['breadcrumb_status']));
                exit;*/

                switch (max(explode(',', $_SESSION['efiling_details']['breadcrumb_status']))) {

                    case NEW_CASE_CASE_DETAIL: {
                            redirect('newcaseQF/caseDetails');
                            exit(0);
                        }
                    case NEW_CASE_PETITIONER: {
                            redirect('newcaseQF/petitioner');
                            exit(0);
                        }
                    case NEW_CASE_RESPONDENT: {
                            redirect('newcaseQF/respondent');
                            exit(0);
                        }
                    case NEW_CASE_EXTRA_INFO: {
                            redirect('newcaseQF/extra_info');
                            exit(0);
                        }
                    case NEW_CASE_EXTRA_PARTY: {
                            redirect('newcaseQF/extra_party');
                            exit(0);
                        }
                    case NEW_CASE_LRS: {
                            redirect('newcaseQF/lr_party');
                            exit(0);
                        }
                    case NEW_CASE_ACT_SECTION: {
                            redirect('newcaseQF/actSections');
                            exit(0);
                        }
                    case NEW_CASE_SUBORDINATE_COURT: {
                            redirect('newcaseQF/subordinate_court');
                            exit(0);
                        }
                    case NEW_CASE_SIGN_METHOD: {
                            redirect('newcaseQF/sign_method');
                            exit(0);
                        }
                    case NEW_CASE_UPLOAD_DOCUMENT: {
                            redirect('uploadDocuments');
                            exit(0);
                          }
                    case NEW_CASE_INDEX: {
                        redirect('documentIndex');
                        exit(0);
                    }
                    case NEW_CASE_COURT_FEE: {
                            redirect('newcaseQF/courtFee');
                            exit(0);
                        }
                    case NEW_CASE_AFFIRMATION; {
                            redirect('affirmation');
                            exit(0);
                        }
                    case NEW_CASE_VIEW: {
                            redirect('newcaseQF/view');
                            exit(0);
                        }
                    default: {
                            redirect('newcaseQF/caseDetails');
                            exit(0);
                        }
                }
            } else {
                redirect('login');
                exit(0);
            }
        } else {
            redirect('login');
            exit(0);
        }
    }

}
