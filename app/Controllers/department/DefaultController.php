<?php
namespace App\Controllers;

class DefaultController extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('dashboard/Dashboard_model');
    }

    public function index() {

        $users_array = array(USER_DEPARTMENT);
        if (in_array($_SESSION['login']['ref_m_usertype_id'], $users_array)) {

            $data = [];
            $this->load->view('templates/department_header');
            $this->load->view('department/dashboard_view', $data);
            $this->load->view('templates/footer');
        }
        else {
            redirect('login');
            exit(0);
        }
    }

    public function engage_aor(){
        $data = [];
        $this->load->view('templates/department_header');
        $this->load->view('department/engage_aor', $data);
        $this->load->view('templates/footer');
    }


}

?>
