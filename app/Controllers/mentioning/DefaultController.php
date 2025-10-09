<?php
namespace App\Controllers;

class DefaultController extends BaseController {

    public function __construct() {

        parent::__construct();
        
        unset($_SESSION['estab_details']);
        unset($_SESSION['efiling_details']);
        unset($_SESSION['estab_details']);
        unset($_SESSION['case_table_ids']);
        unset($_SESSION['parties_list']);
        //unset($_SESSION['efiling_type']);
        unset($_SESSION['pg_request_payment_details']);
        unset($_SESSION['eVerified_mobile_otp']['LITIGENT_MOB_OTP_VERIFY']);
        
        $this->load->model('mentioning/Mentioning_model');
    }

    public function _remap($param = NULL) {

        if ($param == 'index') {
            $this->index(NULL);
        } else {
            $this->index($param);
        }
    }

    public function index($id = NULL) {

        if ($id) {

            $id = url_decryption($id);
            $InputArrray = explode('#', $id);   //0=>registration_id,1=>type,2=>stage

            if (!is_numeric($InputArrray[0]) || $InputArrray[1] != E_FILING_TYPE_MENTIONING || !is_numeric($InputArrray[2])) {
                redirect('login');
                exit(0);
            }

            $registration_id = $InputArrray[0];

            // SETS $_SESSION['estab_details']
            $estab_details = $this->Mentioning_model->get_establishment_details();
            if ($estab_details) {

                //SET $_SESSION['efiling_details']
                $efiling_num_details = $this->Mentioning_model->get_efiling_num_basic_Details($registration_id);
            } else {
                redirect('login');
                exit(0);
            }
        } else {

            // SETS $_SESSION['estab_details']
            $estab_details = $this->Mentioning_model->get_establishment_details();
            if ($estab_details) {
                $_SESSION['efiling_details']['ref_m_efiled_type_id'] = E_FILING_TYPE_MENTIONING;
                redirect('mentioning/view');
                exit(0);
            }
        }

        if (isset($_SESSION['efiling_details']) && !empty($_SESSION['efiling_details'])) {

            $stages_array = array(Draft_Stage);
            
            $allowed_users = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);
            if ($_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN || !in_array($_SESSION['efiling_details']['stage_id'], $stages_array)) {
           // if ($_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN) {
                redirect('mentioning/view');
                exit(0);
            } //elseif (in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users))  {

            elseif ((in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users) && in_array($_SESSION['efiling_details']['stage_id'], $stages_array)) ) {

                switch (max(explode(',', $_SESSION['efiling_details']['breadcrumb_status']))) {

                    case MEN_BREAD_LISTING:
                        redirect('mentioning/listing');
                    /*case MEN_BREAD_ON_BEHALF_OF:
                        redirect('uploadDocuments');                    
                    case MEN_BREAD_UPLOAD_DOC:                        
                        redirect('mentioning/courtFee');
                    case MEN_BREAD_COURT_FEE:                        
                        redirect('affirmation');
                    case MEN_BREAD_AFFIRMATION:
                        redirect('mentioning/view');
                    case MEN_BREAD_VIEW:
                        redirect('mentioning/view');*/
                    default:
                        redirect('case_details');
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
