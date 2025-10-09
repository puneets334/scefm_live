<?php

namespace App\Controllers\Report;

use App\Controllers\BaseController;

class DefaultController extends BaseController {

    public function __construct() {
        parent::__construct();
        if (empty(getSessionData('login'))) {
            return response()->redirect(base_url('/'));
        } else {
            is_user_status();
        }
    }

    public function index() {
        $allowed_users = array(USER_ADMIN, USER_ADMIN_READ_ONLY, USER_EFILING_ADMIN, USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);        
        if(getSessionData('login') != '' && !in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users )) {
            return redirect()->to(base_url('/'));
            exit(0);
        }        
        return redirect()->to(base_url('report/search'));
        exit(0);
    }

}