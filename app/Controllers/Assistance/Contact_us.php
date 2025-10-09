<?php
namespace App\Controllers\Assistance;
use App\Controllers\BaseController;

class Contact_us extends BaseController {

    public function __construct() {
        parent::__construct();
        if(empty(getSessionData('login'))){
            return response()->redirect(base_url('/')); 
        } else{
            is_user_status();
        }
        $allowed_users = array(USER_ADMIN, USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, SR_ADVOCATE, ARGUING_COUNSEL);
        if (getSessionData('login') != '' && !in_array(getSessionData('login')['ref_m_usertype_id'], $allowed_users)) {
            return response()->redirect(base_url('/'));
            exit(0);
        }        
    }

    public function index() {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        $allowed_users = array(USER_ADMIN, USER_ADVOCATE, USER_IN_PERSON, USER_CLERK,SR_ADVOCATE, ARGUING_COUNSEL);
        if (getSessionData('login') != '' && !in_array(getSessionData('login')['ref_m_usertype_id'], $allowed_users)) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $this->render('assistance.contactus_view');
    }

}