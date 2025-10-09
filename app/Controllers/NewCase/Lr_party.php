<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Lr_party extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('common/Common_model');
        $this->load->model('common/Form_validation_model');
        $this->load->model('newcase/New_case_model');
        $this->load->model('newcase/Dropdown_list_model');
        $this->load->model('newcase/Get_details_model');
    }

    public function _remap($param = NULL) {
        
        if($this->uri->total_segments()== 4){             
             $this->add_lr_party($this->uri->segment(4));
        }elseif ($param == 'index') {
            $this->index(NULL);
        } elseif ($param == 'add_lr_party') {
            $this->add_lr_party();
        } else {
            $this->index($param);
        }
    }

    public function index($curr_party_id = NULL) {
        redirect('newcase/subordinate_court');exit(0);
        if (isset($curr_party_id) && !empty($curr_party_id)) {
            $curr_party_id = url_decryption($curr_party_id);
            if (!$curr_party_id) {
                $_SESSION['MSG'] = message_show("fail", "Invalid ID."); // this msg is not being displayed. checking required
                redirect('newcase/lr_party');
                exit(0);
            }
        }

        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_DEPARTMENT);

        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            redirect('admindashboard');
            exit(0);
        }

        //$stages_array = array('', Draft_Stage, Initial_Defected_Stage, I_B_Defected_Stage, E_REJECTED_STAGE);
        $stages_array = array('', Draft_Stage, Initial_Defected_Stage,  E_REJECTED_STAGE);
        if (!in_array($_SESSION['efiling_details']['stage_id'], $stages_array)) {
            redirect('newcase/view');
            exit(0);
        }

        //$data['state_list'] = $this->Dropdown_list_model->get_states_list();
        $data['state_list'] = $this->Dropdown_list_model->get_address_state_list();
        $countryList = $this->Dropdown_list_model->getCountryList();
        $data['countryList'] = !empty($countryList) ? $countryList : NULL;
        //XXXXXXXXXXXX start lrs Dropdown dataXXXXXX

        $data['lrs_details'] = $this->Get_details_model->get_lrs_remarks_details();
        //print_r($data['lrs_details']);exit();


        //XXXXXXXXXXXX End XXXXXX

        if (isset($_SESSION['efiling_details']['registration_id']) && !empty($_SESSION['efiling_details']['registration_id'])) {

            $registration_id = $_SESSION['efiling_details']['registration_id'];

            $data['lr_parties_list'] = $this->Get_details_model->get_case_parties_details($registration_id, array('p_r_type' => NULL, 'm_a_type' => NULL, 'party_id' => NULL, 'view_lr_list'=> TRUE));

            if (isset($curr_party_id) && !empty($curr_party_id)) {
                
                $data['curr_party_id'] = $curr_party_id;

                $data['party_details'] = $this->Get_details_model->get_case_parties_details($registration_id, array('p_r_type' => NULL, 'm_a_type' => NULL, 'party_id' => $curr_party_id , 'view_lr_list'=> FALSE));

               // print_r($data['party_details']);exit();

                $data['district_list'] = $this->Dropdown_list_model->get_districts_list($data['party_details'][0]['state_id']);
            }
        }

        $this->load->view('templates/header');
        $this->load->view('newcase/new_case_view', $data);
        $this->load->view('templates/footer');
    }

    public function add_lr_party($curr_party_id = NULL) {
        
        if (isset($curr_party_id) && !empty($curr_party_id)) {
            $curr_party_id = url_decryption($curr_party_id);
            if (!$curr_party_id) {
                $_SESSION['MSG'] = message_show("fail", "Invalid ID.");
                redirect('newcase/lr_party');
                exit(0);
            }
        }
        
        $stages_array = array('', Draft_Stage, Initial_Defected_Stage, I_B_Defected_Stage);

        if (!in_array($_SESSION['efiling_details']['stage_id'], $stages_array)) {
            echo '1@@@' . htmlentities("Update in Party details can be done only at 'Draft', 'For Compliance' stages' and 'Defective' stages.", ENT_QUOTES);
            exit(0);
        }
        $country_id = !empty($_POST['country_id']) ? url_decryption($_POST['country_id']) : NULL;
        $this->form_validation->set_rules('p_r_type', 'Party As', 'required|in_list[P,R]');
        $this->form_validation->set_rules('party_as', 'Party Is', 'required|in_list[I,D1,D2,D3]');        
        $this->form_validation->set_rules('lr_of_party', 'Legal Representative Of', array('required', 'trim', array('encypted_callable', array($this->Form_validation_model, 'validate_encrypted_value'))));
        if ($_POST['party_as'] == 'I') {
            $this->form_validation->set_rules('party_name', 'Petitioner name', 'required|trim|min_length[3]|max_length[99]|validate_alpha_numeric_single_double_quotes_bracket_with_special_characters');
            $this->form_validation->set_rules('relation', 'Relation', 'required|in_list[S,D,W]');
            $this->form_validation->set_rules('relative_name', 'Father/Husband name', 'required|trim|min_length[3]|max_length[99]|validate_alpha_numeric_single_double_quotes_bracket_with_special_characters');
            //$this->form_validation->set_rules('party_dob', 'D.O.B.', 'trim|exact_length[10]|validate_date_dd_mm_yyyy');
            $this->form_validation->set_rules('party_age', 'Age', 'trim|required|min_length[1]|max_length[2]|is_natural');
            $this->form_validation->set_rules('party_gender', 'Gender', 'trim|required|in_list[M,F,O]');
        } else {
            if ($_POST['party_as'] != 'D3') {

                $this->form_validation->set_rules('org_state', 'Organisation State Name', 'required|trim|validate_encrypted_value');
                if (url_decryption($_POST['org_state']) == 0) {
                    $this->form_validation->set_rules('org_state_name', 'Organisation Other State Name', 'required|trim|min_length[5]|max_length[200]|validate_alpha_numeric_space_dot_hyphen');
                }
            }
            $this->form_validation->set_rules('org_dept', 'Organisation Department name', 'required|trim|validate_encrypted_value');
            if (url_decryption($_POST['org_dept']) == 0) {
                $this->form_validation->set_rules('org_dept_name', 'Other Department name', 'required|trim|min_length[5]|max_length[200]|validate_alpha_numeric_space_dot_hyphen');
            }

            $this->form_validation->set_rules('org_post', 'Post', 'required|trim|validate_encrypted_value');
            if (url_decryption($_POST['org_post']) == 0) {
                $this->form_validation->set_rules('org_post_name', 'Other Post', 'required|trim|min_length[5]|max_length[200]|validate_alpha_numeric_space_dot_hyphen');
            }
        }
        $this->form_validation->set_rules('party_email', 'Email', 'trim|min_length[5]|max_length[49]|valid_email');
        $this->form_validation->set_rules('party_mobile', 'Mobile number', 'trim|exact_length[10]|is_natural');
        $this->form_validation->set_rules('party_pincode', 'Pincode', 'trim|exact_length[6]|is_natural');
        $this->form_validation->set_rules('party_address', 'Address', 'required|trim|min_length[3]|max_length[250]|validate_alpha_numeric_single_double_quotes_bracket_with_special_characters');

        if(isset($country_id) && !empty($country_id) && $country_id == 96){
            $this->form_validation->set_rules('party_city', 'City', 'required|trim|min_length[3]|max_length[49]|validate_alpha_numeric_single_double_quotes_bracket_with_special_characters');
            $this->form_validation->set_rules('party_state', 'State', 'required|trim|validate_encrypted_value');
            $this->form_validation->set_rules('party_district', 'District', 'required|trim|validate_encrypted_value');
        }
        else  if(isset($country_id) && !empty($country_id) && $country_id != 96 ){
            $this->form_validation->set_rules('party_city', 'City', 'trim|min_length[3]|max_length[49]|validate_alpha_numeric_single_double_quotes_bracket_with_special_characters');
            $this->form_validation->set_rules('party_state', 'State', 'trim|validate_encrypted_value');
            $this->form_validation->set_rules('party_district', 'District', 'trim|validate_encrypted_value');
        }
        $this->form_validation->set_error_delimiters('<br/>', '');
        if (!$this->form_validation->run()) {
            echo '3@@@';
            echo form_error('p_r_type').form_error('lr_of_party').form_error('party_as') . form_error('party_name') . form_error('relation') . form_error('relative_name') . form_error('party_age') .
            form_error('party_gender') . form_error('org_state') . form_error('org_state_name') . form_error('org_dept') . form_error('org_dept_name') .
            form_error('org_post') . form_error('org_post_name') . form_error('party_email') . form_error('party_mobile') .
            form_error('party_address') . form_error('party_city') . form_error('party_state') . form_error('party_district') . form_error('party_pincode');
            exit(0);
        }

        $registration_id = $_SESSION['efiling_details']['registration_id'];
        
        $party_as = strtoupper(escape_data($this->input->post("party_as")));

        $p_r_type = strtoupper(escape_data($this->input->post("p_r_type")));
        
        $lr_of_details = explode('##',url_decryption(escape_data($this->input->post("lr_of_party"))));
        $lr_of_party_id = $lr_of_details[0];
        $lr_of_p_r_type = $lr_of_details[1];

        if($lr_of_p_r_type != $p_r_type){
            //data tempered show this msg
            exit(0);
        }
        
        $party_max = $this->Get_details_model->get_max_party_num_n_id($registration_id, $p_r_type, $lr_of_party_id);
        if(isset($party_max[0]['max_party_id_of_lr']) && !empty($party_max[0]['max_party_id_of_lr'])){
            $max_party_id_array = explode('.', $party_max[0]['max_party_id_of_lr']);
            $party_id_num = $max_party_id_array[count($max_party_id_array)-1];
            $max_party_id_array[count($max_party_id_array)-1] = $party_id_num+1;
            $party_id = implode('.', $max_party_id_array);
        }else{
            $party_id = $lr_of_party_id.'.1';            
        }

        $parent_id = $lr_of_party_id;

        $lrsId=url_decryption(escape_data($this->input->post("lrs_id")));

        $party_name = strtoupper(escape_data($this->input->post("party_name")));
        $party_relation = escape_data($this->input->post("relation"));
        $relative_name = strtoupper(escape_data($this->input->post("relative_name")));
        $party_dob = escape_data($this->input->post("party_dob"));
        $party_dob_array = explode('/', $party_dob);
        $party_dob = $party_dob_array[2] . '-' . $party_dob_array[1] . '-' . $party_dob_array[0];
        $party_age = escape_data($this->input->post("party_age"));
        $party_gender = escape_data($this->input->post("party_gender"));

        if ($party_as != 'I') {

            if ($party_as != 'D3') {
                $org_state = url_decryption(escape_data($this->input->post("org_state")));
                if ($org_state == '0') {
                    $org_state_name = strtoupper(escape_data($this->input->post("org_state_name")));
                    $org_state_not_in_list = TRUE;
                } else {
                    $org_state_name = NULL;
                    $org_state_not_in_list = FALSE;
                }
            } else {
                $org_state = 0;
                $org_state_name = NULL;
                $org_state_not_in_list = FALSE;
            }

            $org_dept = url_decryption(escape_data($this->input->post("org_dept")));
            if ($org_dept == '0') {
                $org_dept_name = strtoupper(escape_data($this->input->post("org_dept_name")));
                $org_dept_not_in_list = TRUE;
            } else {
                $org_dept_name = NULL;
                $org_dept_not_in_list = FALSE;
            }

            $org_post = url_decryption(escape_data($this->input->post("org_post")));
            if ($org_post == '0') {
                $org_post_name = strtoupper(escape_data($this->input->post("org_post_name")));
                $org_post_not_in_list = TRUE;
            } else {
                $org_post_name = NULL;
                $org_post_not_in_list = FALSE;
            }

            $party_name = NULL;
            $party_relation = NULL;
            $relative_name = NULL;
            $party_age = NULL;
            $party_dob = NULL;
            $party_gender = NULL;
        } else {
            $org_state = 0;
            $org_state_name = NULL;
            $org_state_not_in_list = FALSE;
            $org_dept = 0;
            $org_dept_name = NULL;
            $org_dept_not_in_list = FALSE;
            $org_post = 0;
            $org_post_name = NULL;
            $org_post_not_in_list = FALSE;
        }

        $party_email = strtoupper(escape_data($this->input->post("party_email")));
        $party_mobile = escape_data($this->input->post("party_mobile"));
        $party_address = !empty($this->input->post("party_address")) ? strtoupper(escape_data($this->input->post("party_address"))) : NULL;
        $party_city = !empty($this->input->post("party_city")) ? strtoupper(escape_data($this->input->post("party_city"))) : NULL;
        $party_state_id = !empty($this->input->post("party_state")) ?  escape_data(url_decryption($this->input->post("party_state"))) : NULL;
        $party_district_id = !empty($this->input->post("party_district")) ? escape_data(url_decryption($this->input->post("party_district"))) : NULL;
        $party_pincode = !empty($this->input->post("party_pincode")) ? escape_data($this->input->post("party_pincode")) : NULL;

        $curr_dt_time = date('Y-m-d H:i:s');

        $party_address_details = array(
            'email_id' => $party_email,
            'mobile_num' => $party_mobile,
            'address' => $party_address,
            'city' => $party_city,
            'state_id' => $party_state_id,
            'district_id' => $party_district_id,
            'pincode' => $party_pincode,
            'country_id'=>$country_id
        );

        $party_organisation = array(
            'org_state_id' => $org_state,
            'org_state_name' => $org_state_name,
            'org_state_not_in_list' => $org_state_not_in_list,
            'org_dept_id' => $org_dept,
            'org_dept_name' => $org_dept_name,
            'org_dept_not_in_list' => $org_dept_not_in_list,
            'org_post_id' => $org_post,
            'org_post_name' => $org_post_name,
            'org_post_not_in_list' => $org_post_not_in_list
        );

        $party_individual = array(
            'party_name' => $party_name,
            'relation' => $party_relation,
            'relative_name' => $relative_name,
            'party_age' => $party_age,
            'party_dob' => $party_dob,
            'gender' => $party_gender,
            'lrs_remarks_id'=>$lrsId
        );        
        
        $party_type = array(
            'party_type' => $party_as,
            'p_r_type' => $p_r_type,
            'm_a_type' => 'A',
            'party_no' => $party_max[0]['max_party_no']+1,
            'have_legal_heir' => FALSE,
            'party_id' => $party_id,
            'parent_id'=>$parent_id
        );

        //echo '<pre>';print_r($_SESSION['efiling_details']);die;
        $party_details = array_merge($party_type, $party_individual, $party_organisation, $party_address_details);

        //echo '<pre>';print_r($party_details);die;

        if (isset($registration_id) && !empty($registration_id) && isset($curr_party_id) && !empty($curr_party_id)) {

            $party_update_data = array(
                'updated_on' => $curr_dt_time,
                'updated_by' => $this->session->userdata['login']['id'],
                'updated_by_ip' => getClientIP()
            );

            $party_details = array_merge($party_details, $party_update_data);

            //UPDATE MAIN PETITIONER DETAILS            

            $status = $this->New_case_model->add_update_case_parties($registration_id, $party_details, NEW_CASE_LRS, $curr_party_id);

            if ($status) {
                reset_affirmation($registration_id);
                echo '2@@@' . htmlentities('Party details updated successfully!', ENT_QUOTES) . '@@@' . base_url('newcase/defaultController/' . url_encryption(trim($registration_id . '#' . E_FILING_TYPE_NEW_CASE . '#' . $_SESSION['efiling_details']['stage_id'])));
            } else {
                echo '1@@@' . htmlentities('Some error occurred ! Please Try again.', ENT_QUOTES);
            }
        } else {

            $party_create_data = array(
                'registration_id' => $registration_id,
                'created_on' => $curr_dt_time,
                'created_by' => $this->session->userdata['login']['id'],
                'created_by_ip' => getClientIP()
            );
            $party_details = array_merge($party_details, $party_create_data);
            /*start extra party check*/
            $all_party_no = get_extra_party_P_or_R($p_r_type);
            $breadcrumb_statusGet = explode(',', $_SESSION['efiling_details']['breadcrumb_status']);
            $breadcrumb_status=count($breadcrumb_statusGet); $step=11;
            if ($step >= $breadcrumb_status) {
                if ($p_r_type == 'P') {

                    $extra_party_check_current_case_details = ($_SESSION['efiling_details']['no_of_petitioners']);

                    //if ($extra_party_check_current_case_details > $all_party_no) {
                    if ($extra_party_check_current_case_details > $all_party_no || $extra_party_check_current_case_details != $all_party_no) {
                        //echo 'added allow';exit();
                        $inserted_party_id = $this->New_case_model->add_update_case_parties($registration_id, $party_details, NEW_CASE_LRS);
                    } else {
                        echo '1@@@' . htmlentities('Your limit of number of ' . $extra_party_check_current_case_details . ' petitioner(s) already exhausted, if you want to add more petitioner(s) first update number of petitioners in case details tab.', ENT_QUOTES);
                    }

                } else if ($p_r_type == 'R') {
                    $extra_party_check_current_case_details = ($_SESSION['efiling_details']['no_of_respondents']);

                    //if ($extra_party_check_current_case_details > $all_party_no) {
                    if ($extra_party_check_current_case_details > $all_party_no || $extra_party_check_current_case_details != $all_party_no) {
                        //echo 'added allow';exit();
                        $inserted_party_id = $this->New_case_model->add_update_case_parties($registration_id, $party_details, NEW_CASE_LRS);
                    } else {
                        echo '1@@@' . htmlentities('Your limit of number of ' . $extra_party_check_current_case_details . ' respondents(s) already exhausted, if you want to add more respondent(s) first update number of respondent in case details tab.', ENT_QUOTES);
                    }
                }
            }
            /*end extra party check*/


            if ($inserted_party_id) {
                //get total is_dead_minor
                $params = array();
                $params['registration_id'] = $registration_id;
                $params['is_dead_minor'] = true;
                $params['is_deleted'] = 'false';
                $params['is_dead_file_status'] ='false';
                $is_dead_data = $this->Get_details_model->getTotalIsDeadMinor($params);
                if(isset($is_dead_data[0]->id) && !empty($is_dead_data[0]->id)){
                    $arr = array();
                    $arr['table_name'] = 'efil.tbl_case_parties';
                    $arr['whereFieldName'] = 'id';
                    $arr['whereFieldValue'] = (int)$is_dead_data[0]->id;
                    $updateArr = array();
                    $updateArr['is_dead_file_status']=true;
                    $arr['updateArr'] = $updateArr;
                    $this->Common_model->updateTableData($arr);
                }
                echo '2@@@' . htmlentities('Party details added successfully', ENT_QUOTES) . '@@@' . base_url('newcase/defaultController/' . url_encryption(trim($registration_id . '#' . E_FILING_TYPE_NEW_CASE . '#' . Draft_Stage)));
            } else {
                echo '1@@@' . htmlentities('Some error occurred ! Please Try again.', ENT_QUOTES);
            }
        }
    }

}
