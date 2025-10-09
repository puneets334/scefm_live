<?php
namespace App\Controllers;

class AdditionalAdv extends BaseController {

    public function __construct() {
        parent::__construct();

        $this->load->model('newcase/AdditionalAdv_model');
    }

    public function index() {

        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            redirect('login');
            exit(0);
        }

        $stages_array = array(Draft_Stage, Initial_Defected_Stage);
        if (!in_array($_SESSION['efiling_details']['stage_id'], $stages_array)) {
            redirect('newcase/view');
            exit(0);
        }
        
        if (!in_array(NEW_CASE_SUBORDINATE_COURT, explode(',', $_SESSION['efiling_details']['breadcrumb_status']))) {
            $_SESSION['MSG'] = message_show("fail", 'Please complete " Subordinate Court " details.');
            redirect('newcase/subordinate_courts');
            exit(0);
        }


        if (isset($_SESSION['efiling_details']['registration_id']) && !empty($_SESSION['efiling_details']['registration_id'])) {

            $registration_id = $_SESSION['efiling_details']['registration_id'];

            $data['aors_list'] = $this->AdditionalAdv_model->get_aors_list();   //echo "<pre>";print_r($data);         
            //$data['seniors_list'] = $this->AdditionalAdv_model->get_seniors_list();

            $data['saved_aors_list'] = $this->AdditionalAdv_model->get_saved_aors_list($registration_id);
            //$data['saved_seniors_list'] = $this->AdditionalAdv_model->get_saved_seniors_list($registration_id);
        }else{
           $_SESSION['MSG'] = message_show("fail", 'Invalid Request.');
            redirect('dashboard');
            exit(0); 
        }


        $this->load->view('templates/header');
        $this->load->view('newcase/new_case_view', $data);
        $this->load->view('templates/footer');
    }

    public function add_additional_aor() {

        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);

        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            $_SESSION['MSG'] = message_show("fail", 'Unauthorised Access !');
            redirect('login');
            exit(0);
        }

        $stages_array = array(Draft_Stage, Initial_Defected_Stage);
        if (!in_array($_SESSION['efiling_details']['stage_id'], $stages_array)) {
            $_SESSION['MSG'] = message_show("fail", 'Invalid Stage.');
            redirect('newcase/view');
            exit(0);
        }

        $this->form_validation->set_rules('additional_aors', 'AOR Name', 'required|trim|validate_encrypted_value');

        $this->form_validation->set_error_delimiters('<br/>', '');
        if (!$this->form_validation->run()) {

            echo '3@@@'.form_error('additional_aors');            
            exit(0);
        }

        $registration_id = $_SESSION['efiling_details']['registration_id'];

        $aor_details = escape_data($_POST['additional_aors']);
        $aor_details = explode('$$', url_decryption($aor_details));

        if (count($aor_details) != 2) {
            echo '3@@@'.'AOR Name details are tempered';           
            exit(0);
        }
        
        $data_to_save = array(
            'registration_id' => $registration_id,
            'adv_bar_id' => escape_data($aor_details[0]),
            'm_a_adv_type' => 'A',
            'for_p_r_a' => 'P',
            'is_active' => TRUE,
            'adv_code' => escape_data($aor_details[1]),
            'is_senior' => FALSE,
            'created_by' => $_SESSION['login']['id'],
            'created_on' => date('Y-m-d H:i:s'),
            'created_by_ip' => getClientIP()
        );

        $status = $this->AdditionalAdv_model->insert_additional_case_advocates($data_to_save);

        if ($status) {

            $data['saved_aors_list'] = $this->AdditionalAdv_model->get_saved_aors_list($registration_id);
            $index_data = $this->load->view('newcase/additional_adv_aors_list_view', $data, TRUE);

            echo '2@@@' . htmlentities('Added successfully.', ENT_QUOTES) . '@@@' . $index_data;
            exit(0);
        } else {
            echo '1@@@' . htmlentities('Failed. Please try again.', ENT_QUOTES);
            exit(0);
        }
    }

    public function delete() {
        
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);

        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            $_SESSION['MSG'] = message_show("fail", 'Unauthorised Access !');
            redirect('login');
            exit(0);
        }

        $stages_array = array(Draft_Stage, Initial_Defected_Stage);
        if (!in_array($_SESSION['efiling_details']['stage_id'], $stages_array)) {
            $_SESSION['MSG'] = message_show("fail", 'Invalid Stage.');
            redirect('newcase/view');
            exit(0);
        }
            
        $this->form_validation->set_rules('delete_id', 'Delete ID', 'required|trim|validate_encrypted_value');        

        $this->form_validation->set_error_delimiters('<br/>', '');

        if (!$this->form_validation->run()) {
            echo '3@@@' . form_error('delete_id');
            exit(0);
        }
        
        $delete_details = explode('$$', url_decryption(escape_data($_POST['delete_id'])));

        if ($delete_details[2] != 'aor') {
            echo '3@@@' . 'Posted data tempered';
            exit(0);
        }

        $registration_id = $_SESSION['efiling_details']['registration_id'];

        $delete_status = $this->AdditionalAdv_model->delete_additional_advocate(escape_data($delete_details[0]), escape_data($delete_details[1]));

        if ($delete_status) {
            if ($delete_details[2] == 'aor') {
                $data['saved_aors_list'] = $this->AdditionalAdv_model->get_saved_aors_list($registration_id);
                $index_data = $this->load->view('newcase/additional_adv_aors_list_view', $data, TRUE);
            }

            echo '2@@@ Deleted successfully. @@@' . $index_data . '@@@' . escape_data($delete_details[2]);
        } else {
            echo '1@@@ Some Error ! Please Try again.';
        }
    }

}
