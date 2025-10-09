<?php

namespace App\Controllers\IA;

use App\Controllers\BaseController;
use App\Models\Common\CommonModel;

class DefaultController extends BaseController
{
    protected $CommonModel;

    public function __construct()
    {
        parent::__construct();
        $this->CommonModel = new CommonModel();
        unset($_SESSION['efiling_details']);
        unset($_SESSION['estab_details']);
        unset($_SESSION['case_table_ids']);
        unset($_SESSION['parties_list']);
        // unset($_SESSION['efiling_type']);
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
        if ($id) {
            $id = url_decryption($id);
            $InputArrray = explode('#', $id);
            // 0=>registration_id,1=>type,2=>stage
            if (!is_numeric($InputArrray[0]) || $InputArrray[1] != E_FILING_TYPE_IA || !is_numeric($InputArrray[2])) {
                return response()->redirect(base_url('/'));
            }
            $registration_id = $InputArrray[0];
            // SETS $_SESSION['estab_details']
            $estab_details = $this->CommonModel->get_establishment_details();
            if ($estab_details) {
                // SET $_SESSION['efiling_details']
                $efiling_num_details = $this->CommonModel->get_efiling_num_basic_Details($registration_id);
            } else {
                return response()->redirect(base_url('/'));
            }
        } else {
            // SETS $_SESSION['estab_details']
            $estab_details = $this->CommonModel->get_establishment_details();
            if ($estab_details) {
                getSessionData('efiling_details')['ref_m_efiled_type_id'] = E_FILING_TYPE_IA;
                return redirect()->to(base_url('IA/view'));
            }
        }
        if (!empty(getSessionData('efiling_details'))) {
            $stages_array = array(Draft_Stage, Initial_Defected_Stage, I_B_Defected_Stage);
            $allowed_users = array(USER_ADVOCATE, USER_ADMIN, USER_ADMIN_READ_ONLY, USER_EFILING_ADMIN, USER_IN_PERSON, USER_CLERK, AMICUS_CURIAE_USER);
            if (getSessionData('login')['ref_m_usertype_id'] == USER_ADMIN || getSessionData('login')['ref_m_usertype_id'] == USER_ADMIN_READ_ONLY || getSessionData('login')['ref_m_usertype_id'] == USER_EFILING_ADMIN || !in_array(getSessionData('efiling_details')['stage_id'], $stages_array)) {
                return redirect()->to(base_url('IA/view'));
            } elseif ((in_array(getSessionData('login')['ref_m_usertype_id'], $allowed_users) && in_array(getSessionData('efiling_details')['stage_id'], $stages_array)) || (getSessionData('login')['ref_m_usertype_id'] = USER_DEPARTMENT && getSessionData('efiling_details')['stage_id'] == Draft_Stage)) {
                echo getSessionData('radio_appearing_for');
                echo max(explode(',', getSessionData('efiling_details')['breadcrumb_status']));
                switch (max(explode(',', getSessionData('efiling_details')['breadcrumb_status']))) {
                    case IA_BREAD_CASE_DETAILS:
                        return redirect()->to(base_url('appearing_for'));
                    case IA_BREAD_APPEARING_FOR:
                        return redirect()->to(base_url('on_behalf_of'));
                    case IA_BREAD_ON_BEHALF_OF:
                        if (!empty(getSessionData('radio_appearing_for')) && getSessionData('radio_appearing_for') == 'I' && IA_BREAD_ON_BEHALF_OF == 3) {
                            return redirect()->to(base_url('uploadDocuments'));
                        } else {
                            return redirect()->to(base_url('appearing_for'));
                        }
                    case IA_BREAD_UPLOAD_DOC:
                        return redirect()->to(base_url('documentIndex'));
                    case IA_BREAD_DOC_INDEX:
                        return redirect()->to(base_url('IA/courtFee'));
                    case IA_BREAD_COURT_FEE:
                        return redirect()->to(base_url('IA/courtFee'));
                        // return redirect()->to(base_url('shareDoc'));
                    case IA_BREAD_SHARE_DOC:
                        return redirect()->to(base_url('affirmation'));
                    case IA_BREAD_AFFIRMATION:
                        return redirect()->to(base_url('IA/view'));
                    case IA_BREAD_VIEW:
                        return redirect()->to(base_url('IA/view'));
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
