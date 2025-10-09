<?php

use App\Controllers\BaseController;

class ResponsiveVariantRouteController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        if (!isset($_SESSION['login']) && empty($_SESSION['login'])) {
            redirect('login');
        }else{
            is_user_status();
        }

        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_DEPARTMENT, USER_ADMIN,SR_ADVOCATE,ARGUING_COUNSEL);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            redirect('login');
            exit(0);
        }

        $this->load->library('slice');
        $this->load->model('adjournment_letter/Adjournment_model');
        $this->load->model('dashboard/Stageslist_model');
    }
//Start Quick E-Filing

    public function showCaseCrud($registration_id = null){
        $registration_id=str_replace('_','#',@$registration_id);
        $tab = @$_REQUEST['tab'];

        $this->slice->view('newcaseQF/responsive_variant.case.crud', @compact('registration_id','tab'));
    }


//End Quick E-Filing








}


?>