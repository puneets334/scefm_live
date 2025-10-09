<?php

namespace App\Controllers\IA;

use App\Controllers\BaseController;
use App\Models\NewCase\CourtFeeModel;

class Details extends BaseController {

    protected $Court_Fee_model;

    public function __construct() {
        parent::__construct();
        $this->Court_Fee_model = new CourtFeeModel();
    }

    public function index() {
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, AMICUS_CURIAE_USER);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            return redirect()->to(base_url('adminDashboard'));
            exit(0);
        }
        $stages_array = array(Draft_Stage, Initial_Defected_Stage, I_B_Defected_Stage);
        if (!in_array($_SESSION['efiling_details']['stage_id'], $stages_array)) {
            return redirect()->to(base_url('newcase/view'));
            exit(0);
        }
        if (isset($_SESSION['efiling_details']['registration_id']) && !empty($_SESSION['efiling_details']['registration_id'])) {
            $registration_id = $_SESSION['efiling_details']['registration_id'];
            $data['court_fee_details'] = $this->Court_Fee_model->get_court_fee_details($registration_id);
        }
        $no_of_orders_challenged = 4;
        $data['orders_challenged'] = $no_of_orders_challenged;
        if ($data['court_fee_details'][0]['court_fee'] != "") {
            $data['total_court_fee'] = $data['court_fee_details'][0]['court_fee'] * $no_of_orders_challenged;
        }
        return $this->render('IA.ia_view', $data);
    }

    public function add_court_fee_details() {
        
    }

}