<?php

class DefaultController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!isset($_SESSION['login']) && empty($_SESSION['login'])) {
            redirect('login');
        }else{
            is_user_status();
        }
    }

    public function index() {
        

        //$allowed_users = array(USER_ADMIN, USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);
        $allowed_users = array(USER_ADMIN);
        if(!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users )){
            redirect('login'); exit(0);
        }        
        
        redirect('adminDashboard');
        exit(0);
        
    }

}
