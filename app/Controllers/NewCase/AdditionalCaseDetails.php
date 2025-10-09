<?php
namespace App\Controllers;

class AdditionalCaseDetails extends BaseController {

    public function __construct() {
        parent::__construct();

        $this->load->model('newcase/AdditionalCaseDetails_model');
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

            $data['keywords_list'] = $this->AdditionalCaseDetails_model->get_keywords_list();

            $data['saved_keywords_list'] = $this->AdditionalCaseDetails_model->get_saved_keywords_list($registration_id);

            $data['saved_additionalCaseDetails'] = $this->AdditionalCaseDetails_model->get_saved_additional_info($registration_id);
        } else {
            $_SESSION['MSG'] = message_show("fail", 'Invalid Request.');
            redirect('dashboard');
            exit(0); 
        }


        $this->load->view('templates/header');
        $this->load->view('newcase/new_case_view', $data);
        $this->load->view('templates/footer');
    }

    public function add_additionalCaseDetails() {

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

        $this->form_validation->set_rules('question_of_law', 'Question of Law', 'required|trim|min_length[10]|max_length[450]|validate_alpha_numeric_with_special_characters');
        $this->form_validation->set_rules('grounds', 'Grounds in brief', 'required|trim|min_length[10]|max_length[450]|validate_alpha_numeric_with_special_characters');
        $this->form_validation->set_rules('interim_grounds', 'Interim Grounds', 'required|trim|min_length[10]|max_length[450]|validate_alpha_numeric_with_special_characters');
        $this->form_validation->set_rules('main_prayer', 'Main Prayer', 'required|trim|min_length[10]|max_length[450]|validate_alpha_numeric_with_special_characters');
        $this->form_validation->set_rules('interim_relief', 'Interim Relief', 'required|trim|min_length[10]|max_length[450]|validate_alpha_numeric_with_special_characters');

        $this->form_validation->set_error_delimiters('<br/>', '');
        if (!$this->form_validation->run()) {
            echo '3@@@' . form_error('question_of_law') . form_error('grounds') . form_error('interim_grounds') . form_error('main_prayer') . form_error('interim_relief');
            exit(0);
        }

        $registration_id = $_SESSION['efiling_details']['registration_id'];

        $question_of_law = !empty(strtoupper(escape_data($this->input->post("question_of_law")))) ? strtoupper(escape_data($this->input->post("question_of_law"))) : NULL;
        $grounds = !empty(strtoupper(escape_data($this->input->post("grounds")))) ? strtoupper(escape_data($this->input->post("grounds"))) : NULL;
        $interim_grounds = !empty(strtoupper(escape_data($this->input->post("interim_grounds")))) ? strtoupper(escape_data($this->input->post("interim_grounds"))) : NULL;
        $main_prayer = !empty(strtoupper(escape_data($this->input->post("main_prayer")))) ? strtoupper(escape_data($this->input->post("main_prayer"))) : NULL;
        $interim_relief = !empty(strtoupper(escape_data($this->input->post("interim_relief")))) ? strtoupper(escape_data($this->input->post("interim_relief"))) : NULL;

        $case_details = array(
            'question_of_law' => $question_of_law,
            'grounds' => $grounds,
            'grounds_interim' => $interim_grounds,
            'main_prayer' => $main_prayer,
            'interim_relief' => $interim_relief,
            'updated_on' => date('Y-m-d H:i:s'),
            'updated_by' => $this->session->userdata['login']['id'],
            'updated_by_ip' => getClientIP()
        );

        $status = $this->AdditionalCaseDetails_model->update_additional_CaseDetails($case_details, $registration_id);

        if ($status) {
            echo '2@@@' . htmlentities('Saved successfully.', ENT_QUOTES);
            exit(0);
        } else {
            echo '1@@@' . htmlentities('Failed. Please try again.', ENT_QUOTES);
            exit(0);
        }
    }

    public function add_keywords() {

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

        $this->form_validation->set_rules('action', 'Action', 'required|trim|in_list[add,delete]');
        $this->form_validation->set_rules('keywords', 'Keywords', 'required|trim|validate_encrypted_value');
        $this->form_validation->set_rules('input1', 'Posted Data', 'required|trim|validate_encrypted_value');

        $this->form_validation->set_error_delimiters('<br/>', '');

        if (!$this->form_validation->run()) {
            echo '3@@@' . form_error('action') . form_error('keywords') . form_error('input1');
            exit(0);
        }

        $registration_id = $_SESSION['efiling_details']['registration_id'];

        $keywords = explode('$$', url_decryption(escape_data($_POST['keywords'])));

        $saved_keywords = explode('$$', url_decryption(escape_data($_POST['input1'])));

        if ($keywords[1] != 'keyword_id' && $saved_keywords[1] != 'keyword_data') {
            echo '3@@@' . 'Posted data tempered';
            exit(0);
        }

        $saved_keywords = explode(',', $saved_keywords[0]);

        if (escape_data($_POST['action']) == 'add') {
            if (!in_array($keywords[0], $saved_keywords)) {
                array_push($saved_keywords, $keywords[0]);
            }
            $msg = 'Keyword Saved successfully.';
        } elseif (escape_data($_POST['action']) == 'delete') {
            if (($key = array_search($keywords[0], $saved_keywords)) !== false) {
                unset($saved_keywords[$key]);
            }
            $msg = 'Keyword deleted successfully.';
        }

        $keywords = trim(implode(',', $saved_keywords), ',');

        $case_details = array(
            'keywords' => $keywords,
            'updated_on' => date('Y-m-d H:i:s'),
            'updated_by' => $this->session->userdata['login']['id'],
            'updated_by_ip' => getClientIP()
        );

        $input1 = url_encryption($keywords . '$$keyword_data');

        $status = $this->AdditionalCaseDetails_model->update_additional_CaseDetails($case_details, $registration_id);

        if ($status) {

            $data['saved_keywords_list'] = $this->AdditionalCaseDetails_model->get_saved_keywords_list($registration_id);
            $index_data = $this->load->view('newcase/keywords_list_view', $data, TRUE);

            echo '2@@@' . $msg . '@@@' . $index_data . '@@@' . $input1;
            exit(0);
        } else {
            echo '1@@@' . $msg;
            exit(0);
        }
    }

}
