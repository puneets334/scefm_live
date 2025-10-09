<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class DefaultController extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {

        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);

        $allowed_admins_array = array(USER_ADMIN);

        if (in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            redirect('dashboard');
            exit(0);
        }elseif(in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_admins_array)){
            redirect('adminDashboard');
            exit(0);
        }else{
            redirect('login');
            exit(0);
        }
    }

}
