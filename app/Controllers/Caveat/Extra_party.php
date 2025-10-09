<?php
namespace App\Controllers\Caveat;
use App\Controllers\BaseController;
use App\Models\Caveat\CaveatCommonModel;
use App\Models\NewCase\DropdownListModel;
use App\Models\Caveat\SubordinateCourtModel;
use App\Models\Caveat\ExtraPartyModel;


class Extra_party extends BaseController {


    protected $db;
    protected $caveat_common_model;
    protected $Dropdown_list_model;
    protected $Subordinate_court_model;
    protected $Extra_party_model;
	protected $session;
	protected $validation;

    public function __construct() {
        parent::__construct();
        $this->request = \Config\Services::request();
        $this->validation = \Config\Services::validation();
		$this->session = \Config\Services::session();
		 
		$this->Extra_party_model = new ExtraPartyModel();
        $this->Dropdown_list_model = new DropdownListModel();
        $this->Subordinate_court_model = new SubordinateCourtModel();
        $this->caveat_common_model = new CaveatCommonModel();
        if (empty($this->session->get('login'))) {
            return response()->redirect(base_url('/'));
            exit(0);
        } else {
            is_user_status();
        }
    }

    public function _remap($param = NULL) {
            $segment = service('uri');
        if ($segment->getTotalSegments() == 4) {
            $this->add_extra_party($segment->getSegment(4));
        } elseif ($param == 'index') {
            $this->index(NULL);
        } elseif ($param == 'add_extra_party') {
            $this->add_extra_party();
        } elseif ($segment->getSegment(3) == 'delete') {
            $this->delete($segment->getSegment(4), $segment->getSegment(5));
        } else {
            $this->index($param);
        }
       
    }

    public function index($party_id = NULL) {
        return response()->redirect(base_url('caveat/caveatee'));
		exit(0);
        
        $data = array();
        if (isset($party_id) && !empty($party_id)) {
            $party_id = explode('$$', url_decryption(escape_data($party_id)));
            if ($party_id[1] != 'party_id') {
                $_SESSION['MSG'] = message_show("fail", "Invalid ID.");
                return redirect()->to(base_url('caveat/extra_party'));
                exit(0);
            } else {
                $party_id = $party_id[0];
            }
        }
        $allowedUsers = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowedUsers)) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        if (!(isset($_SESSION['efiling_details']['registration_id']) && !empty($_SESSION['efiling_details']['registration_id']))) {
            return redirect()->to(base_url('dashboard'));
            exit(0);
        }
        if (!in_array(CAVEAT_BREAD_CAVEATEE, explode(',', $_SESSION['efiling_details']['breadcrumb_status']))) {
            $_SESSION['MSG'] = message_show("fail", 'Please first save <b>Caveatee Information</b>.');
            log_message('CUSTOM', "While Filing Caveat - Please first save Caveatee Information.");
            return redirect()->to(base_url('caveat/caveatee'));
            exit(0);
        }
        if (isset($_SESSION['estab_details']) && !empty($_SESSION['estab_details'])) {
            $estab_code = $_SESSION['estab_details']['est_code'];
            $efiling_for_type_id = $_SESSION['estab_details']['efiling_for_type_id'];
            $state_code = $_SESSION['estab_details']['state_code'];
        } else {
            $_SESSION['MSG'] = message_show("fail", 'Invalid request !');
            return redirect()->to(base_url('whereToFile/caveat'));
            exit(0);
        }

        $allowed_stages = array(Draft_Stage, Initial_Defected_Stage, E_REJECTED_STAGE);
        if (!empty($_SESSION['efiling_details']['stage_id']) && !in_array($_SESSION['efiling_details']['stage_id'], $allowed_stages)) {
            return redirect()->to(base_url('caveat/view'));
            exit(0);
        }
        if (isset($party_id) && !empty($party_id)) {
            $party_details = $this->Extra_party_model->get_extra_party_details($party_id);
            $data['party_details'] = $party_details;
            if(isset($party_details) && !empty($party_details)){
                $state_id = !empty($party_details[0]['state_id']) ? (int)$party_details[0]['state_id'] : NULL;
                $data['district_list'] = $this->Dropdown_list_model->get_districts_list($state_id);
                $alt_state_id =!empty($party_details[0]['altstate_id']) ? (int)$party_details[0]['altstate_id'] : NULL;
                $data['alt_district_list'] = $this->Dropdown_list_model->get_districts_list($alt_state_id);
            if(!empty($party_details[0]['orgid']) && $party_details[0]['orgid'] !='I'){
                $party_is = trim($party_details[0]['resorgid']);
                $data['dept_list'] = $this->Dropdown_list_model->get_departments_list($party_is);
                $data['post_list'] = $this->Dropdown_list_model->get_posts_list();
            }
            }
        }
        $data['state_list'] = $this->Dropdown_list_model->get_states_list();
        $registration_id = $_SESSION['efiling_details']['registration_id'];
        $extra_party_data = $this->Extra_party_model->get_extra_parties_list($registration_id);
        $data['extra_party_data'] = $extra_party_data;
		 $this->render('caveat.caveat_view', $data);
    }

    public function add_extra_party($party_id_to_update = NULL) {
        if(isset($party_id_to_update) && !empty($party_id_to_update)) {
            $party_id_to_update = explode('$$', url_decryption(escape_data($party_id_to_update)));
            if ($party_id_to_update[1] != 'party_id') {
                $_SESSION['MSG'] = message_show("fail", "Invalid ID.");
                return redirect()->to(base_url('caveat/extra_party'));
                exit(0);
            } else {
                $party_id_to_update = $party_id_to_update[0];
            }
        }
        $allowedUsers = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowedUsers)) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        if (!(isset($_SESSION['efiling_details']['registration_id']) && !empty($_SESSION['efiling_details']['registration_id']))) {
            return redirect()->to(base_url('dashboard'));
            exit(0);
        }
        $allowed_stages = array(Draft_Stage, Initial_Defected_Stage, E_REJECTED_STAGE);
        if (!empty($_SESSION['efiling_details']['stage_id']) && !in_array($_SESSION['efiling_details']['stage_id'], $allowed_stages)) {
            echo '1@@@' . htmlentities("Update in Caveatee details can be done only at 'Draft' and 'For Compliance' stages.", ENT_QUOTES);
            exit(0);
            return redirect()->to(base_url('caveat/view'));
            exit(0);
        }
        $registration_id = $_SESSION['efiling_details']['registration_id'];
        $this->form_validation->set_rules('party_is', 'Caveator is', 'required|trim');
        $this->form_validation->set_rules('complainant_accused_type', 'Extra party type', 'required|trim');
        if(escape_data($_POST['party_is']) == 'I') {
            $this->form_validation->set_rules('pet_complainant', 'Caveator Name', 'required|trim|min_length[3]|max_length[99]|validate_alpha_numeric_single_double_quotes_bracket_with_special_characters');
            $this->form_validation->set_rules('pet_rel_flag', 'Relation', 'required|trim');
            $this->form_validation->set_rules('relative_name', 'Relative Name', 'trim|min_length[3]|max_length[99]|validate_alpha_numeric_single_double_quotes_bracket_with_special_characters');
            //$this->form_validation->set_rules('pet_dob', 'D.O.B', 'required|trim|validate_date_dd_mm_yyyy');
            $this->form_validation->set_rules('pet_age', 'Age', 'required|trim|exact_length[2]|is_natural');
            $this->form_validation->set_rules('pet_gender', 'Gender', 'required|trim');
        }
        else if (escape_data($_POST['party_is']) == 'D1' || escape_data($_POST['party_is']) == 'D2'){
            //36.8C81BFDC == 'NOT IN LIST'
            if(isset($_POST['org_state']) && !empty($_POST['org_state'])){
                $otherstatename_valid=url_decryption($_POST['org_state']);
                if($otherstatename_valid == 0)
                    $this->form_validation->set_rules('org_state_name', 'Other state name', 'required|trim|validate_alpha_numeric_space_dot_hyphen_comma');
                else
                    $this->form_validation->set_rules('org_state', 'State name', 'required|trim');
            }
            if(isset($_POST['org_dept']) && !empty($_POST['org_dept'])){
                $otherdeptname_valid=url_decryption($_POST['org_dept']);
                if($otherdeptname_valid == 0)
                    $this->form_validation->set_rules('org_dept_name', 'Other department name', 'required|trim|validate_alpha_numeric_space_dot_hyphen_comma');
                else
                    $this->form_validation->set_rules('org_dept', 'Department name', 'required|trim');
            }
            if(isset($_POST['org_post']) && !empty($_POST['org_post'])){
                $otherpostname_valid=url_decryption($_POST['org_post']);
                if($otherpostname_valid == 0)
                    $this->form_validation->set_rules('org_post_name', 'Organisation post name', 'required|trim|validate_alpha_numeric_space_dot_hyphen_comma');
                else
                    $this->form_validation->set_rules('org_post', 'Organisation post name', 'required|trim');
            }
        }
        else if (escape_data($_POST['party_is']) == 'D3'){
            //$this->form_validation->set_rules('org_state', 'Organisation state', 'required|trim');
            $this->form_validation->set_rules('org_dept', 'Organisation department', 'required|trim');
        }
        $email_required = $_SESSION['estab_details']['is_pet_email_required'] == 't' ? 'required|' : '';
        $mobile_required = $_SESSION['estab_details']['is_pet_mobile_required'] == 't' ? 'required|' : '';
        $state_required = $_SESSION['estab_details']['is_pet_state_required'] == 't' ? 'required' : '';
        $district_required = $_SESSION['estab_details']['is_pet_district_required'] == 't' ? 'required' : '';
        $this->form_validation->set_rules('pet_email', 'Email', $email_required . 'trim|min_length[6]|max_length[49]|valid_email');
        $this->form_validation->set_rules('pet_mobile', 'Mobile', $mobile_required . 'trim|exact_length[10]|is_natural');
        $this->form_validation->set_rules('pet_address', 'Address', 'required|trim|min_length[3]|max_length[250]|validate_alpha_numeric_single_double_quotes_bracket_with_special_characters');
        $this->form_validation->set_rules('party_city', 'City', 'required|trim|min_length[3]|max_length[49]|validate_alpha_numeric_single_double_quotes_bracket_with_special_characters');
        $this->form_validation->set_rules('party_state', 'State', 'trim|required');
        $this->form_validation->set_rules('party_district', 'District', 'trim|required');
        $this->form_validation->set_rules('party_pincode', 'Pincode', 'trim|exact_length[6]|is_natural');
        $this->form_validation->set_rules('other_information', 'Other Information', 'trim|in_list[on]');
        $this->form_validation->set_rules('proforma_repo', 'Performa Respondent', 'trim|in_list[on]');
        $this->form_validation->set_rules('o_passport_no', 'Alternate Passport No.', 'trim|min_length[5]|max_length[30]|validate_alpha_numeric_space_dot_hyphen_comma');
        $this->form_validation->set_rules('o_pancard_no', 'Alternate PAN Card', 'trim|min_length[5]|max_length[30]|validate_alpha_numeric_space_dot_hyphen_comma');
        $this->form_validation->set_rules('o_fax_no', 'Alternate FAX No.', 'trim|min_length[5]|max_length[30]|validate_alpha_numeric_space_dot_hyphen_comma');
        $this->form_validation->set_rules('o_phone_no', 'Alternate Phone No.', 'trim|min_length[5]|max_length[30]|validate_alpha_numeric_space_dot_hyphen_comma');
        $this->form_validation->set_rules('o_country', 'Alternate Country', 'trim|min_length[5]|max_length[30]|validate_alpha_numeric_space_dot_hyphen_comma');
        $this->form_validation->set_rules('o_nationality', 'Alternate Nationality', 'trim|min_length[5]|max_length[30]|validate_alpha_numeric_space_dot_hyphen_comma');
        $this->form_validation->set_rules('o_occupation', 'Alternate Occupation', 'trim|min_length[5]|max_length[100]|validate_alpha_numeric_space_dot_hyphen_comma');
        // $this->form_validation->set_rules('o_alt_address', 'Alternate Address', 'trim|min_length[5]|max_length[300]|validate_alpha_numeric_space_dot_hyphen_comma');
        $this->form_validation->set_rules('o_alt_address', 'Alternate Address', 'trim|min_length[3]|max_length[300]|validate_alpha_numeric_single_double_quotes_bracket_with_special_characters');
        $this->form_validation->set_rules('alt_party_pincode', 'Alternate pincode', 'trim');
        $this->form_validation->set_rules('alt_party_city', 'Alternate city', 'trim');
        $this->form_validation->set_rules('alt_party_state', 'Alternate state', 'trim');
        $this->form_validation->set_rules('alt_party_district', 'Alternate district', 'trim');
        $this->form_validation->set_error_delimiters('<br>', '');
        if ($this->form_validation->run() === FALSE) {
            echo '1@@@' . validation_errors();
            exit(0);
        }
        $pet_complainant = !empty($_POST["pet_complainant"]) ?  escape_data(strtoupper($_POST["pet_complainant"])) : NULL;
        $pet_relation = !empty($_POST['pet_rel_flag']) ? escape_data($_POST['pet_rel_flag']) : NULL;
        $relative_name = !empty($_POST["relative_name"]) ? escape_data(strtoupper($_POST["relative_name"])) : NULL;
        $pet_gender = !empty($_POST["pet_gender"]) ?  escape_data($_POST["pet_gender"]) : NULL;
        $pet_age = !empty($_POST["pet_age"]) ? escape_data($_POST["pet_age"]) : NULL;
        $pet_dob = !empty($_POST["pet_dob"]) ? escape_data($_POST["pet_dob"]) : NULL;
        if($pet_dob != ''){
            $date = explode('/', $pet_dob);
            $pet_dob = $date[2] . '-' . $date[1] . '-' . $date[0];
        }
        else {
            $pet_dob = NULL;
        }
        $organisation_id = !empty($_POST['party_is']) ?  escape_data($_POST['party_is']) : NULL;
        $org_state = !empty($_POST['org_state']) ?  url_decryption(escape_data($_POST['org_state'])) : NULL;
        $org_state_name = !empty($_POST['org_state_name']) ?  escape_data($_POST['org_state_name']) : NULL;
        $org_dept = !empty($_POST['org_dept']) ?  url_decryption(escape_data($_POST['org_dept'])) : NULL;
        $org_dept_name = !empty($_POST['org_dept_name']) ?  escape_data($_POST['org_dept_name']) : NULL;
        $org_post = !empty($_POST['org_post']) ?  url_decryption(escape_data($_POST['org_post'])) : NULL;
        $org_post_name = !empty($_POST['org_post_name']) ?  escape_data($_POST['org_post_name']) : NULL;
        $pet_email = !empty($_POST["pet_email"]) ? escape_data(strtoupper($_POST["pet_email"])) : NULL;
        $pet_mobile = !empty($_POST["pet_mobile"]) ? escape_data(strtoupper($_POST["pet_mobile"])) : NULL;
        $pet_address = !empty($_POST["pet_address"]) ? escape_data(strtoupper($_POST["pet_address"])) : NULL;
        $state_id = !empty($_POST['party_state']) ? url_decryption(escape_data($_POST['party_state'])) : NULL;
        $district_id = !empty($_POST['party_district']) ? url_decryption(escape_data($_POST['party_district'])) : NULL;
        $pet_pincode = !empty($_POST['party_pincode']) ? escape_data($_POST['party_pincode']) : NULL;
        $pet_city = !empty($_POST['party_city']) ? escape_data($_POST['party_city']) : NULL;
        $complainant_accused_type = !empty($_POST["complainant_accused_type"]) ?  (int)escape_data($_POST["complainant_accused_type"]) : NULL;
        $o_information = escape_data($_POST["other_information"]);
        $o_information = $o_information == 'on' ? 'Y' : 'N';
        $proforma_repo = !empty(escape_data($_POST['proforma_repo'])) ? escape_data($_POST['proforma_repo']) : NULL;
        $o_passport = !empty(escape_data($_POST['o_passport_no'])) ? escape_data($_POST['o_passport_no']) : NULL;
        $o_pancard = !empty(escape_data($_POST['o_pancard_no'])) ? escape_data($_POST['o_pancard_no']) : NULL;
        $o_fax_no = !empty(escape_data($_POST['o_fax_no'])) ? escape_data($_POST['o_fax_no']) : NULL;
        $o_occupation = !empty(escape_data($_POST['o_occupation'])) ? escape_data($_POST['o_occupation']) : NULL;
        $o_phone_no = !empty(escape_data($_POST['o_phone_no'])) ? escape_data($_POST['o_phone_no']) : NULL;
        $o_country = !empty(escape_data($_POST['o_country'])) ? escape_data($_POST['o_country']) : NULL;
        $o_nationality = !empty(escape_data($_POST['o_nationality'])) ? escape_data($_POST['o_nationality']) : NULL;
        $o_alt_add = !empty(escape_data($_POST['o_alt_address'])) ? escape_data($_POST['o_alt_address']) : NULL;
        $alt_pincode = !empty(escape_data($_POST['alt_party_pincode'])) ? escape_data($_POST['alt_party_pincode']) : NULL;
        $alt_city = !empty(escape_data($_POST['alt_party_city'])) ? escape_data($_POST['alt_party_city']) : NULL;
        $o_state_id = !empty(escape_data($_POST['alt_party_state'])) ? url_decryption(escape_data($_POST['alt_party_state'])) : NULL;
        $o_district_id = !empty(escape_data($_POST['alt_party_district'])) ? url_decryption(escape_data($_POST['alt_party_district'])) : NULL;

        $data = array(
            'ref_m_efiling_nums_registration_id' => $registration_id,
            'orgid' => $organisation_id,
            'name' => strtoupper($pet_complainant),
            'address' => strtoupper($pet_address),
            'pet_age' => $pet_age,
            'pet_dob'=>(!empty($pet_dob)) ? date('Y-m-d H:i:s',strtotime($pet_dob)): NULL,
            'father_name' => strtoupper($relative_name),
            'father_flag' => $pet_relation, 
            'pet_sex' => $pet_gender,
            'type' => $complainant_accused_type,
            'pet_occu' => strtoupper($o_occupation),
            'pet_email' => strtoupper($pet_email),
            'pet_mobile' => $pet_mobile,
            'state_id' => $state_id,
            'dist_code' => $district_id,
            'pincode' => $pet_pincode,
            'pet_city'=>$pet_city,
            'other_info_flag' => $o_information,
            'performaresflag' => $proforma_repo,
            'passportno' => strtoupper($o_passport),
            'panno' => strtoupper($o_pancard),
            'pet_phone' => $o_phone_no,
            'pet_fax' => $o_fax_no,
            'country' => strtoupper($o_country),
            'pet_nationality' => strtoupper($o_nationality),
            'altaddress' => strtoupper($o_alt_add),
            'altstate_id' => $o_state_id,
            'altdist_code' => $o_district_id,
            'alt_pincode' => $alt_pincode,
            'alt_city' => $alt_city,
            'extra_party_org_state'=>$org_state,
            'extra_party_org_state_name'=>$org_state_name,
            'extra_party_org_dept'=>$org_dept,
            'extra_party_org_dept_name'=>$org_dept_name,
            'extra_party_org_post'=>$org_post,
            'extra_party_org_post_name'=>$org_post_name,

//            'extra_party_org_name' => strtoupper($organisation_name),
//            'extra_party_caste_name' => strtoupper($cast_name),
//            'extra_party_state_name' => strtoupper($state_name),
//            'extra_party_distt_name' => strtoupper($district_name),
//            'extra_party_taluka_name' => strtoupper($taluka_name),
//            'extra_party_town_name' => strtoupper($town_name),
//            'extra_party_ward_name' => strtoupper($ward_name),
//            'extra_party_village_name' => strtoupper($village_name),
//            'extra_party_ps_name' => strtoupper($ps_code_name),
//            'extra_party_o_state_name' => strtoupper($o_state_name),
//            'extra_party_o_distt_name' => strtoupper($o_district_name),
//            'extra_party_o_taluka_name' => strtoupper($o_taluka_name),
//            'extra_party_o_town_name' => strtoupper($o_town_name),
//            'extra_party_o_ward_name' => strtoupper($o_ward_name),
//            'extra_party_o_village_name' => strtoupper($o_village_name),
//            'extra_not_in_list_org' => $not_in_list_org,
//            'extra_party_religion_name' => strtoupper($religion_name),
//            'extra_party_relation_name' => strtoupper($relation),
            'parentid' => NULL
        );
        //echo '<pre>'; print_r($data);echo '<pre>'; exit;
        if (!$party_id_to_update) {
            $get_party_id_for_extra_party = $this->Extra_party_model->get_max_party_id($registration_id, $complainant_accused_type, NULL);
            if ((int) $get_party_id_for_extra_party[0]['party_id'] == 0) {
                $curr_party_id = (int) $get_party_id_for_extra_party[0]['party_id'] + 2;
            } else {
                $curr_party_id = (int) $get_party_id_for_extra_party[0]['party_id'] + 1;
            }
            $party_no = $this->Extra_party_model->get_max_party_no($registration_id,$complainant_accused_type);
            $curr_party_no = '';
            if ((int) $party_no[0]['party_no'] == 0) {
                $curr_party_no = (int) $party_no[0]['party_no'] + 2;
            } else {
                $curr_party_no = (int) $party_no[0]['party_no'] + 1;
            }

            $data = array_merge($data, array('party_no' => $curr_party_no,'party_id' => $curr_party_id));
            $extra_party_count = $this->Extra_party_model->get_extra_party_count($registration_id, $complainant_accused_type);
          //  echo '<pre>'; print_r($extra_party_count); exit;
            if ($complainant_accused_type == '1') {
                if ($extra_party_count[0]['main_party_lrs'] > 0) {
                    $total_extra_party_count = $extra_party_count[0]['main_party_lrs'] + $extra_party_count[0]['extry_party_lrs'];
                    $data_extra_pet_n_res = array('pet_extracount' => ($total_extra_party_count + 1),'updated_at'=>date('Y-m-d H:i:s'));
                } else {
                    $data_extra_pet_n_res = array('pet_extracount' => ($extra_party_count[0]['extry_party_lrs'] + 1),'updated_at'=>date('Y-m-d H:i:s'));
                }
            } elseif ($complainant_accused_type == '2') {
                if ($extra_party_count[0]['main_party_lrs'] > 0) {
                    $total_extra_party_count = $extra_party_count[0]['main_party_lrs'] + $extra_party_count[0]['extry_party_lrs'];
                    $data_extra_pet_n_res = array('res_extracount' => ($total_extra_party_count + 1),'updated_at'=>date('Y-m-d H:i:s'));
                } else {
                    $data_extra_pet_n_res = array('res_extracount' => ($extra_party_count[0]['extry_party_lrs'] + 1),'updated_at'=>date('Y-m-d H:i:s'));
                }
            }
        }
        if ($party_id_to_update) {
            $result = $this->Extra_party_model->update_extra_party($party_id_to_update, $data, $registration_id);
            if ($result) {
                echo '2@@@' . htmlentities('Extra Party details updated successfully!', ENT_QUOTES);
            } else {
                echo '1@@@' . htmlentities('Some things went wrong. Please Try again', ENT_QUOTES);
            }
        } else {
            $result = $this->Extra_party_model->add_extra_party($data, $registration_id, $data_extra_pet_n_res);
            if ($result) {
                echo '2@@@' . htmlentities('Extra Party details added successfully!', ENT_QUOTES);
            } else {
                echo '1@@@' . htmlentities('Some things went wrong. Please Try again', ENT_QUOTES);
            }
        }
    }

    function delete($id, $type_with_request_form) {
        $id = url_decryption($id);
        $type_with_request_form = url_decryption($type_with_request_form);
        $explode_type = explode('#', $type_with_request_form);
        $type = $explode_type[0];
        $redirect_to = $explode_type[1];
        $extra_party_party_no = $explode_type[2];
        if ($redirect_to != 1 && $redirect_to != 2) {
            $_SESSION['MSG'] = message_show("fail", 'Invalid Data!');
            return redirect()->to(base_url(isset($_SERVER['HTTP_REFERER'])));
        }
        if ($redirect_to == 1) {
            $redirect_url = 'caveat/extra_party';
        } else {
            $redirect_url = 'caveat/add_LRs';
        }
        if (!preg_match("/^[0-9]*$/", $id) || !preg_match("/^[0-9]*$/", $type) || strlen($id) > INTEGER_FIELD_LENGTH || strlen($type) > 1) {
            $_SESSION['MSG'] = message_show("fail", 'Invalid Data!');
            return redirect()->to(base_url($redirect_url));
        }
        if (!($_SESSION['efiling_details']['stage_id'] == Draft_Stage || $_SESSION['efiling_details']['stage_id'] == Initial_Defected_Stage || empty($_SESSION['efiling_details']['stage_id']))) {
            $_SESSION['MSG'] = message_show("fail", "Deletion of Extra Party can be done only at 'Draft' and 'For Compliance' stages.!");
            log_message('CUSTOM', "Deletion of Extra Party can be done only at 'Draft' and 'For Compliance' stages.!");
            return redirect()->to(base_url($redirect_url));
        }
        if (!empty($id) && !empty($type)) {
            $this->db->trans_start();
            $result = $this->Extra_party_model->delete_extra_party($id, $type, $redirect_to, $extra_party_party_no);
            $this->Extra_party_model->assign_party_no($_SESSION['efiling_details']['registration_id']);
            $this->db->trans_complete();
            if ($result) {
                $_SESSION['MSG'] = message_show("success", 'Data Deleted successfully!');
                return redirect()->to(base_url($redirect_url));
            } else {
                $_SESSION['MSG'] = message_show("fail", 'Invalid Data!');
                return redirect()->to(base_url($redirect_url));
            }
        }
    }

}
