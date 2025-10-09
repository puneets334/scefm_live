<?php

namespace App\Controllers\jailPetition;

use App\Controllers\BaseController;
use App\Models\JailPetition\JailPetitionModel;
use App\Models\Common\CommonModel;
use App\Models\NewCase\DropdownListModel;

class DefaultController extends BaseController
{
    protected $JailPetitionModel;
    protected $Common_model;
    protected $Dropdown_list_model;

    public function __construct()
    {
        parent::__construct();
        $this->JailPetitionModel = new JailPetitionModel();
        $this->Common_model = new CommonModel();
        $this->Dropdown_list_model = new DropdownListModel();
        unset($_SESSION['efiling_details']);
        unset($_SESSION['estab_details']);
        unset($_SESSION['case_table_ids']);
        unset($_SESSION['parties_list']);
        unset($_SESSION['efiling_type']);
        unset($_SESSION['pg_request_payment_details']);
        unset($_SESSION['eVerified_mobile_otp']['LITIGENT_MOB_OTP_VERIFY']);
    }

    public function _remap($param = NULL)
    {
        if ($param == 'index') {
            $this->index(NULL);
        } else {
            $this->index($param);
        }
    }

    public function index($id = NULL)
    {
        $allowed_users_array = array(JAIL_SUPERINTENDENT, USER_ADMIN);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            return redirect()->to(base_url('/'));;
            exit(0);
        }
        if ($id) {
            $id = url_decryption($id);
            $InputArrray = explode('#', $id);
            if (!is_numeric($InputArrray[0]) || $InputArrray[1] != E_FILING_TYPE_JAIL_PETITION || !is_numeric($InputArrray[2])) {
                return redirect()->to(base_url('/'));;
                exit(0);
            }
            $registration_id = $InputArrray[0];
            $_SESSION['regid'] = $InputArrray[0];
            $estab_details = $this->Common_model->get_establishment_details();
            if ($estab_details) {
                $efiling_num_details = $this->Common_model->get_efiling_num_basic_Details($registration_id);
            } else {
                return redirect()->to(base_url('/'));;
                exit(0);
            }
        } else {
            $estab_details = $this->Common_model->get_establishment_details();
            if ($estab_details) {
                return redirect()->to(base_url('jail_dashboard'));
                exit(0);
            }
        }
        if (isset($_SESSION['efiling_details']) && !empty($_SESSION['efiling_details'])) {
            $stages_array = array(Draft_Stage, Initial_Defected_Stage, I_B_Defected_Stage);
            $allowed_users = array(JAIL_SUPERINTENDENT);
            if ($_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN || !in_array($_SESSION['efiling_details']['stage_id'], $stages_array)) {
                return redirect()->to(base_url('jailPetition/view'));
                exit(0);
            } elseif ((in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users) && in_array($_SESSION['efiling_details']['stage_id'], $stages_array))) {
                switch (max(explode(',', $_SESSION['efiling_details']['breadcrumb_status']))) {
                    case JAIL_PETITION_CASE_DETAILS: {
                        return redirect()->to(base_url('jailPetition/BasicDetails'));
                        exit(0);
                    }
                    case JAIL_PETITION_EXTRA_PETITIONER: {
                        return redirect()->to(base_url('jailPetition/Extra_petitioner'));
                        exit(0);
                    }
                    case JAIL_PETITION_SUBORDINATE_COURT: {
                        return redirect()->to(base_url('jailPetition/Subordinate_court'));
                        exit(0);
                    }
                    case JAIL_PETITION_SIGN_METHOD: {
                        return redirect()->to(base_url('jailPetition/sign_method'));
                        exit(0);
                    }
                    case JAIL_PETITION_UPLOAD_DOCUMENT: {
                        return redirect()->to(base_url('uploadDocuments'));
                        exit(0);
                    }
                    case JAIL_PETITION_AFFIRMATION; {
                        return redirect()->to(base_url('affirmation'));
                        exit(0);
                    }
                    case JAIL_PETITION_VIEW: {
                        return redirect()->to(base_url('jailPetition/view'));
                        exit(0);
                    }
                    default: {
                        return redirect()->to(base_url('jailPetition/BasicDetails'));
                        exit(0);
                    }
                }
            } else {
                return redirect()->to(base_url('/'));;
                exit(0);
            }
        } else {
            return redirect()->to(base_url('/'));;
            exit(0);
        }
    }
}
