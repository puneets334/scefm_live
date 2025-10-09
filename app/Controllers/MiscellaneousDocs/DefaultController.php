<?php

namespace App\Controllers\MiscellaneousDocs;

use App\Controllers\BaseController;
use App\Models\Common\CommonModel;

class DefaultController extends BaseController {

    protected $CommonModel;

    public function __construct() {
        parent::__construct();
        $this->CommonModel = new CommonModel();
        if (!isset($_SESSION['login']) && empty($_SESSION['login'])) {
            return redirect()->to(base_url('/'));
        } else {
            is_user_status();
        }
        unset($_SESSION['estab_details']);
        unset($_SESSION['efiling_details']);
        unset($_SESSION['estab_details']);
        unset($_SESSION['case_table_ids']);
        unset($_SESSION['parties_list']);
        unset($_SESSION['efiling_type']);
        unset($_SESSION['pg_request_payment_details']);
        unset($_SESSION['eVerified_mobile_otp']['LITIGENT_MOB_OTP_VERIFY']);
    }

    // public function _remap($param = NULL) {
    //     if ($param == 'index') {
    //         $this->index(NULL);
    //     } else {
    //         $this->index($param);
    //     }
    // }

    public function index($id = NULL) {
        if ($id) {
            $id = url_decryption($id);
            $InputArrray = explode('#', $id);   //0=>registration_id,1=>type,2=>stage
            if (!is_numeric($InputArrray[0]) || $InputArrray[1] != E_FILING_TYPE_MISC_DOCS || !is_numeric($InputArrray[2])) {
                return redirect()->to(base_url('/'));
                exit(0);
            }
            $registration_id = $InputArrray[0];
            // SETS $_SESSION['estab_details']
            $estab_details = $this->CommonModel->get_establishment_details();
            if ($estab_details) {
                //SET $_SESSION['efiling_details']
                $efiling_num_details = $this->CommonModel->get_efiling_num_basic_Details($registration_id);
            } else {
                return redirect()->to(base_url('/'));
                exit(0);
            }
        } else {
            // SETS $_SESSION['estab_details']
            $estab_details = $this->CommonModel->get_establishment_details();
            if ($estab_details) {
                $_SESSION['efiling_details']['ref_m_efiled_type_id'] = E_FILING_TYPE_MISC_DOCS;
                return redirect()->to(base_url('miscellaneous_docs/view'));
                exit(0);
            }
        }
        if (isset($_SESSION['efiling_details']) && !empty($_SESSION['efiling_details'])) {
            $stages_array = array(Draft_Stage, Initial_Defected_Stage, I_B_Defected_Stage);
            $allowed_users = array(USER_ADVOCATE, USER_IN_PERSON, USER_ADMIN,USER_ADMIN_READ_ONLY,USER_EFILING_ADMIN, USER_CLERK,AMICUS_CURIAE_USER);
            if ($_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN || $_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN_READ_ONLY || $_SESSION['login']['ref_m_usertype_id'] == USER_EFILING_ADMIN || !in_array($_SESSION['efiling_details']['stage_id'], $stages_array)) {
                return redirect()->to(base_url('miscellaneous_docs/view'));
                exit(0);
            } elseif ((in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users) && in_array($_SESSION['efiling_details']['stage_id'], $stages_array)) || ($_SESSION['login']['ref_m_usertype_id'] = USER_DEPARTMENT && $_SESSION['efiling_details']['stage_id'] == Draft_Stage )) {
                // echo '<pre>Ashu_'; pr($_SESSION['efiling_details']['breadcrumb_status']);
                switch (max(explode(',', $_SESSION['efiling_details']['breadcrumb_status']))) {
                    case MISC_BREAD_CASE_DETAILS:
                        return redirect()->to(base_url('appearing_for'));
                    case MISC_BREAD_APPEARING_FOR:
                        return redirect()->to(base_url('on_behalf_of'));
                    case MISC_BREAD_ON_BEHALF_OF:
                        return redirect()->to(base_url('uploadDocuments'));                    
                    case MISC_BREAD_UPLOAD_DOC:
                        return redirect()->to(base_url('documentIndex'));
                    case MISC_BREAD_DOC_INDEX:
                        return redirect()->to(base_url('miscellaneous_docs/courtFee'));
                    case MISC_BREAD_COURT_FEE:
                        return redirect()->to(base_url('miscellaneous_docs/courtFee'));
                        // return redirect()->to(base_url('IA/courtFee'));
                        // return redirect()->to(base_url('shareDoc'));
                    case MISC_BREAD_DOC_INDEX:
                        return redirect()->to(base_url('affirmation'));
                    case MISC_BREAD_AFFIRMATION:
                        return redirect()->to(base_url('miscellaneous_docs/view'));
                    case MISC_BREAD_VIEW:
                        return redirect()->to(base_url('miscellaneous_docs/view'));
                    default:
                        return redirect()->to(base_url('case_details'));
                }
            } else {
                return redirect()->to(base_url('/'));
                exit(0);
            }
        } else {
            return redirect()->to(base_url('/'));
            exit(0);
        }
    }

}