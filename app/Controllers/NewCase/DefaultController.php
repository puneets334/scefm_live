<?php
namespace App\Controllers\Newcase;

use App\Controllers\BaseController;
use App\Models\Common\CommonModel;

class DefaultController extends BaseController
{

    protected $session;
    protected $Common_model;

    public function __construct()
    {
        parent::__construct();
        $this->session = \Config\Services::session();
        if (empty($this->session->get('login'))) {
            return response()->redirect(base_url('/'));
        } else {
            is_user_status();
        }

        $this->Common_model = new CommonModel();

        unset($_SESSION['efiling_details']);
        unset($_SESSION['estab_details']);
        unset($_SESSION['case_table_ids']);
        unset($_SESSION['parties_list']);
        unset($_SESSION['efiling_type']);
        unset($_SESSION['pg_request_payment_details']);
        unset($_SESSION['eVerified_mobile_otp']['LITIGENT_MOB_OTP_VERIFY']);
    }

    // public function _remap($param = NULL)
    // {
    //     if ($param == 'index') {
    //         $this->index(NULL);
    //     } else {
    //         $this->index($param);
    //     }
    // }
 
    public function index($id = NULL)
    {
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_DEPARTMENT, USER_ADMIN, USER_ADMIN_READ_ONLY, USER_EFILING_ADMIN);

        log_message('debug', 'Checking allowed users array');
        if (getSessionData('login') != '' && !in_array(getSessionData('login')['ref_m_usertype_id'], $allowed_users_array)) {
            log_message('error', 'User type not allowed');
            return redirect()->to(base_url('/'));
        }

        log_message('debug', 'Validating ID');
        if ($id) {
            $id = url_decryption($id);

            $InputArrray = explode('#', $id);
            if (!is_numeric($InputArrray[0]) || $InputArrray[1] != E_FILING_TYPE_NEW_CASE || !is_numeric($InputArrray[2])) {
                log_message('error', 'Invalid ID format');
                return redirect()->to(base_url('/'));
            }

            $registration_id = $InputArrray[0];
            $_SESSION['regid'] = $registration_id;
            if (!empty($registration_id)) {
                casewithAIByregistration_id($registration_id);
            }
            log_message('debug', 'Fetching establishment details');
            $estab_details = $this->Common_model->get_establishment_details();
            if ($estab_details) {
                log_message('debug', 'Fetching e-filing details');
                $efiling_num_details = $this->Common_model->get_efiling_num_basic_Details($registration_id);
            } else {
                log_message('error', 'Establishment details not found');
                return redirect()->to(base_url('/'));
            }
        } else {
            log_message('debug', 'Fetching establishment details without ID');
            $estab_details = $this->Common_model->get_establishment_details();
            if ($estab_details) {
                log_message('info', 'Establishment details found, redirecting');
                //pr(base_url('newcase/caseDetails'));
                return response()->redirect(base_url('newcase/caseDetails'));
            }
        }

        log_message('debug', 'Checking efiling details');
        if (!empty(getSessionData('efiling_details'))) {
            $stages_array = array(Draft_Stage, Initial_Defected_Stage, I_B_Defected_Stage);
            $allowed_users = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);
            if ((isset($_SESSION['login']) && $_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN || $_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN_READ_ONLY || $_SESSION['login']['ref_m_usertype_id'] == USER_EFILING_ADMIN) || (isset($_SESSION['efiling_details']) && !in_array($_SESSION['efiling_details']['stage_id'], $stages_array))) {
            // if (in_array(getSessionData('login')['ref_m_usertype_id'], $allowed_users_array) && !empty(getSessionData('efiling_details')['stage_id']) && !in_array(getSessionData('efiling_details')['stage_id'], $stages_array)) {
                return redirect()->to(base_url('newcase/view'));
            } elseif (in_array(getSessionData('login')['ref_m_usertype_id'], $allowed_users) && !empty(getSessionData('efiling_details')['stage_id']) && in_array(getSessionData('efiling_details')['stage_id'], $stages_array)) {
                if (!empty(getSessionData('efiling_details')['stage_id']) && getSessionData('efiling_details')['stage_id'] == I_B_Defected_Stage) {
                    return redirect()->to(base_url('uploadDocuments'));
                }
                switch (max(explode(',', getSessionData('efiling_details')['breadcrumb_status']))) {
                    case NEW_CASE_CASE_DETAIL:
                        return redirect()->to(base_url('newcase/caseDetails'));
                    case NEW_CASE_PETITIONER:
                        return redirect()->to(base_url('newcase/petitioner'));
                    case NEW_CASE_RESPONDENT:
                        return redirect()->to(base_url('newcase/respondent'));
                    case NEW_CASE_EXTRA_INFO:
                        return redirect()->to(base_url('newcase/extra_info'));
                    case NEW_CASE_EXTRA_PARTY:
                        return redirect()->to(base_url('newcase/extra_party'));
                    case NEW_CASE_LRS:
                        return redirect()->to(base_url('newcase/lr_party'));
                    case NEW_CASE_ACT_SECTION:
                        return redirect()->to(base_url('newcase/actSections'));
                    case NEW_CASE_SUBORDINATE_COURT:
                        return redirect()->to(base_url('newcase/subordinate_court'));
                    case NEW_CASE_SIGN_METHOD:
                        return redirect()->to(base_url('newcase/sign_method'));
                    case NEW_CASE_UPLOAD_DOCUMENT:
                        return redirect()->to(base_url('uploadDocuments'));
                    case NEW_CASE_INDEX:
                        return redirect()->to(base_url('documentIndex'));
                    case NEW_CASE_COURT_FEE:
                        return redirect()->to(base_url('newcase/courtFee'));
                    case NEW_CASE_AFFIRMATION:
                        return redirect()->to(base_url('affirmation'));
                    case NEW_CASE_VIEW:
                        return redirect()->to(base_url('newcase/view'));
                    default:
                        return redirect()->to(base_url('newcase/caseDetails'));
                }
            } else {
                log_message('error', 'User not authorized for this stage');
                $this->session->setFlashdata('msg','<div class="alert alert-danger">User not authorized for this stage</div>');
                return redirect()->to(base_url('newcase/caseDetails'));
            }
        } else {
            log_message('error', 'E-filing details not found');
            return response()->redirect(base_url('/'));
        }


    }
    
}
