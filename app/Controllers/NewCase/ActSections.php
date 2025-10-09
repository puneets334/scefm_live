<?php
namespace App\Controllers;

class ActSections extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('common/Common_model');

        $this->load->model('newcase/Act_sections_model');
    }

    public function _remap($param = NULL) {
        //echo $this->uri->segment(4).'--'.$this->uri->segment(3).'---'.$this->uri->segment(2); die;
        if ($this->uri->total_segments() == 4) {
            $this->delete($this->uri->segment(4));
        } elseif ($param == 'index') {
            $this->index(NULL);
        } elseif ($param == 'add_act_section') {
            $this->add_act_section();
        } else {
            $this->index();
        }
    }

    public function index() {
        redirect('newcase/subordinate_court');exit(0);
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_DEPARTMENT);

        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            redirect('adminDashboard');
            exit(0);
        }
        if (check_party() !=true) {  //func added on 15 JUN 2021
            $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Please enter every party details before moving to further tabs.</div>');
            redirect('newcase/extra_party');
        }
        //$stages_array = array('', Draft_Stage, Initial_Defected_Stage, I_B_Defected_Stage, E_REJECTED_STAGE);
        $stages_array = array('', Draft_Stage, Initial_Defected_Stage,  E_REJECTED_STAGE);
        if (!in_array($_SESSION['efiling_details']['stage_id'], $stages_array)) {
            redirect('newcase/view');
            exit(0);
        }
        /*if (!in_array(NEW_CASE_RESPONDENT, explode(',', $_SESSION['efiling_details']['breadcrumb_status']))) {
            $_SESSION['MSG'] = message_show("fail", 'Please complete " Respondent " details.');
            redirect('newcase/respondent');
            exit(0);
        }*/
        $registration_id = $_SESSION['efiling_details']['registration_id'];
        //get total is_dead_minor
        $params = array();
        $params['registration_id'] = $registration_id;
        $params['is_dead_minor'] = true;
        $params['is_deleted'] = 'false';
        $params['is_dead_file_status'] ='false';
        $params['total'] =1;
        $this->load->model('newcase/Get_details_model');
        $isdeaddata = $this->Get_details_model->getTotalIsDeadMinor($params);
        if(isset($isdeaddata[0]->total) && !empty($isdeaddata[0]->total)){
            $total = $isdeaddata[0]->total;
            $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Please fill '.$total.' remaining dead/minor party details</div>');
            redirect('newcase/lr_party');
            exit(0);
        }

        $data['acts_list'] = $this->Act_sections_model->get_master_acts_list();


        if (isset($_SESSION['efiling_details']['registration_id']) && !empty($_SESSION['efiling_details']['registration_id'])) {
            $data['act_sections_list'] = $this->Act_sections_model->get_act_sections_list($registration_id);
        }/*else{
           $_SESSION['MSG'] = message_show("fail", 'Invalid Request.');
            redirect('dashboard');
            exit(0); 
        }*/
        $this->load->view('templates/header');
        $this->load->view('newcase/new_case_view', $data);
        $this->load->view('templates/footer');
    }

    public function add_act_section() {

        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);

        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            redirect('adminDashboard');
            exit(0);
        }

        $stages_array = array(Draft_Stage, Initial_Defected_Stage);

        if (!in_array($_SESSION['efiling_details']['stage_id'], $stages_array)) {
            echo '1@@@' . htmlentities("Update in Act - Sections details can be done only at 'Draft', 'For Compliance' stages' and 'Defective' stages.", ENT_QUOTES);
            exit(0);
        }
        if(empty($this->input->post('not_in_list'))){
            $this->form_validation->set_rules('case_acts', 'Act', 'required|trim|validate_encrypted_value');
            $act_id = escape_data(url_decryption($this->input->post("case_acts")));
        }



        /*Changes started on 07 September 2020*/


        if(!empty($this->input->post('not_in_list')))
        {
            $this->form_validation->set_rules('act_name','Act Name','required|trim|min_length[1]|max_length[75]|validate_alpha_numeric_space_dot_hyphen');
            $this->form_validation->set_rules('act_no','Act No','numeric|trim|min_length[1]|max_length[5]|greater_than[0.99]');
            $this->form_validation->set_rules('act_year','Act Year','numeric|trim|min_length[4]|max_length[4]|greater_than[1]');
        }

        /*end of changes*/


//        $this->form_validation->set_rules('case_acts', 'Act', 'required|trim|validate_encrypted_value');
        $this->form_validation->set_rules('section_1', 'Section I', 'required|trim|min_length[1]|max_length[10]|validate_alpha_numeric_space_dot_hyphen');
        $this->form_validation->set_rules('section_2', 'Sub-Section I', 'trim|min_length[1]|max_length[10]|validate_alpha_numeric_space_dot_hyphen');
        $this->form_validation->set_rules('section_3', 'Sub-Section II', 'trim|min_length[1]|max_length[10]|validate_alpha_numeric_space_dot_hyphen');
        $this->form_validation->set_rules('section_4', 'Sub-Section III', 'trim|min_length[1]|max_length[10]|validate_alpha_numeric_space_dot_hyphen');

        $this->form_validation->set_error_delimiters('<br/>', '');
        /*  if (!$this->form_validation->run()) {
              echo '3@@@';
              echo form_error('case_acts') . form_error('section_1') .
              form_error('section_2') . form_error('section_2') . form_error('section_3') . form_error('section_4');
              exit(0);
          }*/


        if (!$this->form_validation->run()) {
            if(!empty($this->input->post('not_in_list')))
            {
                echo '3@@@';
                echo  form_error('act_name') . form_error('act_no') .form_error('act_year') .form_error('section_1').
                    form_error('section_2') . form_error('section_2') . form_error('section_3') . form_error('section_4');
                exit(0);
            }
            else
            {
                echo '3@@@';
                echo form_error('case_acts').form_error('section_1') .
                    form_error('section_2') . form_error('section_2') . form_error('section_3') . form_error('section_4');
                exit(0);

            }
        }


        $registration_id = $_SESSION['efiling_details']['registration_id'];
        if(empty($this->input->post('not_in_list'))){
            $act_id = escape_data(url_decryption($this->input->post("case_acts")));
        }


        $act_section = strtoupper(escape_data($this->input->post("section_1")));
        if (isset($_POST['section_2']) && !empty($_POST['section_2'])) {
            $act_section .= '(' . strtoupper(escape_data($this->input->post("section_2"))) . ')';
        }
        if (isset($_POST['section_3']) && !empty($_POST['section_3'])) {
            $act_section .= '(' . strtoupper(escape_data($this->input->post("section_3"))) . ')';
        }
        if (isset($_POST['section_4']) && !empty($_POST['section_4'])) {
            $act_section .= '(' . strtoupper(escape_data($this->input->post("section_4"))) . ')';
        }

        $curr_dt_time = date('Y-m-d H:i:s');

        /*
         * These changes were reverted on 10 September 2020 to accommodate other requirements.
         *         $act_sections_details = array(
                    'registration_id' => $registration_id,
                    'act_id' => $act_id,
                    'act_section' => $act_section,
                    'created_on' => $curr_dt_time,
                    'created_by' => $this->session->userdata['login']['id'],
                    'created_by_ip' => getClientIP()
                );*/


        /*start of change on 10 September 2020*/
        if(!empty($this->input->post('not_in_list')))
        {
            $act_name = strtoupper(escape_data($this->input->post('act_name')));
            $act_year = escape_data($this->input->post('act_year'));
            $act_no = escape_data($this->input->post('act_no'));
            $master_act_section = array(
                'act_name'=>$act_name,
                'act_name_h'=>'',
                'year'=> !empty($act_year) ? $act_year : 0,
                'actno'=>!empty($act_no) ? $act_no : 0,
                'state_id'=>'0',
                'display'=>'Y',
                'old_id'=>'0',
                'old_act_code'=>'0',
                'is_approved'=>'N'
            );
            $act_id_created = $this->Act_sections_model->add_master_acts_list($master_act_section);
        }
        /*End of changes on 10 September 2020*/

        $act_sections_details = array(
            'registration_id' => $registration_id,
            'act_id' => !empty($act_id_created) ? $act_id_created : $act_id,
            'act_section' => $act_section,
            'created_on' => $curr_dt_time,
            'created_by' => $this->session->userdata['login']['id'],
            'created_by_ip' => getClientIP()
        );
        $inserted_party_id = $this->Act_sections_model->add_case_act_sections($registration_id, $act_sections_details, NEW_CASE_ACT_SECTION);
        //$inserted_party_id = $this->Act_sections_model->add_case_act_sections($registration_id, $act_sections_details, NEW_CASE_ACT_SECTION, $act_id);

        if ($inserted_party_id) {
            reset_affirmation($registration_id);
            if(!empty($this->input->post('not_in_list')))
            {
                echo '2@@@' . htmlentities('Act - Section added successfully and waiting for approval', ENT_QUOTES) . '@@@' . base_url('newcase/actSections');

            }else
            {
                echo '2@@@' . htmlentities('Act - Section added successfully', ENT_QUOTES) . '@@@' . base_url('newcase/actSections');

            }
        } else {
            echo '1@@@' . htmlentities('Some error ! Please Try again.', ENT_QUOTES);
        }
        /*end of changes*/


        /*        if ($inserted_party_id) {

                    echo '2@@@' . htmlentities('Act - Section added successfully', ENT_QUOTES) . '@@@' . base_url('newcase/actSections');
                } else {
                    echo '1@@@' . htmlentities('Some error ! Please Try again.', ENT_QUOTES);
                }*/
    }


}
