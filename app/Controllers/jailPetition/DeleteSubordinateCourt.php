<?php

namespace App\Controllers\jailPetition;

use App\Controllers\BaseController;
use App\Models\JailPetition\JailPetitionModel;

class DeleteSubordinateCourt extends BaseController
{
    protected $JailPetitionModel;

    public function __construct()
    {
        parent::__construct();
        $this->JailPetitionModel = new JailPetitionModel();
    }

    public function _remap($param = NULL)
    {
        $this->index($param);
    }

    public function index($delete_id)
    {
        $delete_id = url_decryption($delete_id);
        if (empty($delete_id)) {
            $_SESSION['MSG'] = message_show("fail", "Invalid ID.");
            return redirect()->to(base_url('jail_dashboard'));
            exit(0);
        }
        $allowed_users_array = array(JAIL_SUPERINTENDENT);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            return redirect()->to(base_url('admindashboard'));
            exit(0);
        }
        $stages_array = array('', Draft_Stage, Initial_Defected_Stage, I_B_Defected_Stage);
        if (!in_array($_SESSION['efiling_details']['stage_id'], $stages_array)) {
            return redirect()->to(base_url('jail_dashboard'));
            exit(0);
        }
        if (isset($_SESSION['efiling_details']['registration_id']) && !empty($_SESSION['efiling_details']['registration_id'])) {
            $registration_id = $_SESSION['efiling_details']['registration_id'];
            $delete_status = $this->JailPetitionModel->delete_case_subordinate_court($registration_id, $delete_id);
            if ($delete_status) {
                $_SESSION['MSG'] = message_show("success", "Earlier Court Details deleted successfully.");
            } else {
                $_SESSION['MSG'] = message_show("fail", "Some error. Please try again.");
            }
            return redirect()->to(base_url('jailPetition/defaultController/' . url_encryption(trim($registration_id . '#' . E_FILING_TYPE_JAIL_PETITION . '#' . $_SESSION['efiling_details']['stage_id']))));
            exit(0);
        }
    }
}
