<?php
namespace App\Controllers\AdminDashboard;

use App\Controllers\BaseController;

class TransferToIcmis extends BaseController {

    public function __construct() {
        parent::__construct();
        unset($_SESSION['efiling_details']);
        unset($_SESSION['estab_details']);
        unset($_SESSION['case_table_ids']);
        unset($_SESSION['search_case_data']);
        unset($_SESSION['form_data']);
        unset($_SESSION['efiling_user_detail']);
        unset($_SESSION['pdf_signed_details']);
        unset($_SESSION['matter_type']);
        unset($_SESSION['crt_fee_and_esign_add']);
        unset($_SESSION['mobile_no_for_updation']);
        unset($_SESSION['email_id_for_updation']);
        unset($_SESSION['search_key']);
    }
    
    public function _remap($param = NULL) {
        if ($param == 'index') {
            $this->index(NULL);
        } else{
            $this->index($param);
        }
    }

    public function index($filingDetails) {
        $users_array = array(USER_ADMIN);
        $data = array();
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $users_array)) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        if(isset($filingDetails) && !empty($filingDetails)) {
            $filingData =  explode('#',url_decryption($filingDetails));
            $registration_id = !empty($filingData['0']) ? (int)$filingData['0'] : NULL;
            $efiled_type_id = !empty($filingData['1']) ? $filingData['1'] : NULL;
            $stage = !empty($filingData['2']) ? $filingData['2'] : NULL;
            $efiling_no = !empty($filingData['3']) ? $filingData['3'] : NULL;
        } else{
            return redirect()->to(base_url('adminDashboard'));
            exit(0);
        }
        $data['tabs_heading'] = "New Filing";
        $this->render('adminDashboard.transfertoicmis_view', $data);
    }

}