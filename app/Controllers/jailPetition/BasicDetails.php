<?php

namespace App\Controllers\jailPetition;

use App\Controllers\BaseController;
use App\Models\NewCase\DropdownListModel;
use App\Models\NewCase\GetDetailsModel;
use App\Models\JailPetition\JailPetitionModel;
use App\Libraries\webservices\Efiling_webservices;

class BasicDetails extends BaseController
{

    protected $Dropdown_list_model;
    protected $Get_details_model;
    protected $JailPetitionModel;
    protected $efiling_webservices;
    protected $validation;

    public function __construct()
    {
        parent::__construct();
        $this->Dropdown_list_model = new DropdownListModel();
        $this->Get_details_model = new GetDetailsModel();
        $this->JailPetitionModel = new JailPetitionModel();
        $this->efiling_webservices = new Efiling_webservices();
        $this->validation = \Config\Services::validation();
    }

    public function prisonerList()
    {
        $jailCode = $_SESSION['login']['userid'];
        if (!empty($jailCode)) {
            $result = $this->efiling_webservices->prisonerList($jailCode);
            if (count($result)) {
                echo '<option value="" selected="true" disabled="disabled"> Select Prisoner </option>';
                foreach ($result['Inside Prisoner List'] as $dataRes) {
                    echo '<option  value="' . $dataRes['PID_No'] . '">' . strtoupper($dataRes['PID_No'] . '-----' . $dataRes['Prisoner_Name']) . '</option>';
                }
            } else {
                echo '<option value="" selected="true" disabled="disabled"> Select Prisoner </option>';
            }
        } else {
            echo '<option value="" selected="true" disabled="disabled"> Select Prisoner</option>';
        }
    }

    public function prisonerDetail()
    {
        $jailCode = $_SESSION['login']['userid'];
        $prisonerId = $_POST['prisonerId'];
        // $details = array();
        if (!empty($jailCode) && !empty($prisonerId)) {
            $result = $this->efiling_webservices->prisonerDetail($jailCode, $prisonerId);
        }
        if ($result) {
            $dob = strtok($result['PrisonerDetails'][0]['DOB'], 'T');
            $dob_array = explode('-', $dob);
            $dob = $dob_array[2] . "/" . $dob_array[1] . "/" . $dob_array[0];
            $details = array(
                'prisoner_name' => $result['PrisonerDetails'][0]['Prisoner_Name'],
                'sex_code' => $result['PrisonerDetails'][0]['Sex_Code'],
                'dob' => $dob,
                'father_name' => $result['PrisonerDetails'][0]['Father_Name'],
                'age' => !empty($result['PrisonerDetails'][0]['age']) ? $result['PrisonerDetails'][0]['age'] : '',
                'present_address' => !empty($result['PrisonerDetails'][0]['Present_Add']) ? $result['PrisonerDetails'][0]['Present_Add'] : ''
            );
        } else {
            $result = $this->efiling_webservices->prisonerList($jailCode);
            foreach ($result['Inside Prisoner List'] as $dataRes) {
                if ($dataRes['PID_No'] == $prisonerId) {
                    $details = array(
                        'prisoner_name' => $dataRes['Prisoner_Name'],
                        'sex_code' => $dataRes['gender'],
                        'dob' => $dataRes['dob'],
                        'father_name' => $dataRes['father_name'],
                        'age' => !empty($dataRes['age']) ? $dataRes['age'] : '',
                        'present_address' => !empty($dataRes['present_address']) ? $dataRes['present_address'] : ''
                    );
                }
            }
        }
        echo json_encode($details);
    }

    public function index()
    {
        $allowed_users_array = array(JAIL_SUPERINTENDENT);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $data['state_list'] = $this->Dropdown_list_model->get_states_list();
        $stages_array = array('', Draft_Stage, Initial_Defected_Stage, I_B_Defected_Stage);
        if (!in_array($_SESSION['efiling_details']['stage_id'], $stages_array)) {
            return redirect()->to(base_url('jailPetition/view'));
            exit(0);
        }
        if (isset($_SESSION['efiling_details']['registration_id']) && !empty($_SESSION['efiling_details']['registration_id'])) {
            $registration_id = $_SESSION['efiling_details']['registration_id'];
            $data['new_case_details'] = $this->Get_details_model->get_new_case_details($registration_id);
            $data['petitioner_details'] = $this->Get_details_model->get_case_parties_details($registration_id, array('p_r_type' => 'P', 'm_a_type' => 'M', 'party_id' => NULL, 'view_lr_list' => FALSE));
            $data['district_list'] = $this->Dropdown_list_model->get_districts_list($data['petitioner_details'][0]['state_id']);
            $data['respondent_details'] = $this->Get_details_model->get_case_parties_details($registration_id, array('p_r_type' => 'R', 'm_a_type' => 'M', 'party_id' => NULL, 'view_lr_list' => FALSE));
            $this->Get_details_model->get_case_table_ids($registration_id);
        }
        return $this->render('jailPetition.new_case_view', $data);
    }

    public function add_case_details()
    {
        $allowed_users_array = array(JAIL_SUPERINTENDENT);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $stages_array = array('', Draft_Stage, Initial_Defected_Stage);
        if (!in_array($_SESSION['efiling_details']['stage_id'], $stages_array)) {
            echo '1@@@' . htmlentities("Update in Petitioner details can be done only at 'Draft', 'For Compliance' stages'", ENT_QUOTES);
            exit(0);
        }
        // $this->form_validation->set_rules('petitioner_name', 'Petitioner Name', 'required|trim|min_length[3]|max_length[99]|validate_alpha_numeric_space_dot_hyphen_apostrophe_atrateof');
        // $this->form_validation->set_rules('relation', 'Relation', 'required|in_list[S,D,W]');
        // $this->form_validation->set_rules('relative_name', 'Father/Husband name', 'required|trim|min_length[3]|max_length[99]|validate_alpha_numeric_space_dot_hyphen_apostrophe_atrateof');
        // $this->form_validation->set_rules('petitioner_dob', 'D.O.B.', 'trim|exact_length[10]|validate_date_dd_mm_yyyy');
        // $this->form_validation->set_rules('petitioner_age', 'Age', 'trim|required|min_length[1]|max_length[2]|is_natural');
        // $this->form_validation->set_rules('petitioner_gender', 'Gender', 'trim|required|in_list[M,F,O]');
        // $this->form_validation->set_rules('petitioner_address', 'Address', 'required|trim|min_length[10]|max_length[250]|validate_alpha_numeric_space_dot_hyphen_comma_slash');
        // $this->form_validation->set_rules('petitioner_state', 'Petitioner State', 'required|trim|validate_encrypted_value');
        // $this->form_validation->set_rules('petitioner_district', 'District', 'required|trim|validate_encrypted_value');
        // $this->form_validation->set_rules('petitioner_pincode', 'Pincode', 'trim|exact_length[6]|is_natural');
        // $this->form_validation->set_rules('respondent_name', 'Respondent Name', 'required|trim|min_length[3]|max_length[99]|validate_alpha_numeric_space_dot_hyphen_apostrophe');
        // $this->form_validation->set_rules('respondent_state', 'Respondent State', 'required|trim|validate_encrypted_value');
        // $this->form_validation->set_rules('earlier_applies', 'Earlier Applies', 'trim|required|in_list[N,Y]');
        // $this->form_validation->set_error_delimiters('<br/>', '');
        // if (!$this->form_validation->run()) {
        //     echo '3@@@';
        //     echo form_error('petitioner_name') . form_error('relation') . form_error('relative_name') . form_error('petitioner_dob') .
        //         form_error('petitioner_age') . form_error('petitioner_gender') . form_error('petitioner_address') . form_error('petitioner_state') .
        //         form_error('petitioner_district') . form_error('petitioner_pincode') . form_error('respondent_name') . form_error('respondent_state') . form_error('earlier_applies');
        //     exit(0);
        // }
        $validationRules = [
            'petitioner_name' => [
                'label' => 'Petitioner Name',
                'rules' => 'required|trim|min_length[3]|max_length[99]|regex_match[/^[a-zA-Z0-9\s.\-\'@]+$/]'
            ],
            'relation' => [
                'label' => 'Relation',
                'rules' => 'required|in_list[S,D,W]'
            ],
            'relative_name' => [
                'label' => 'Father/Husband name',
                'rules' => 'required|trim|min_length[3]|max_length[99]|regex_match[/^[a-zA-Z0-9\s.\-\'@]+$/]'
            ],
            'petitioner_dob' => [
                'label' => 'D.O.B.',
                'rules' => 'trim|exact_length[10]|regex_match[/^\d{2}-\d{2}-\d{4}$/]'
            ],
            'petitioner_age' => [
                'label' => 'Age',
                'rules' => 'required|trim|min_length[1]|max_length[2]|is_natural'
            ],
            'petitioner_gender' => [
                'label' => 'Gender',
                'rules' => 'required|trim|in_list[M,F,O]'
            ],
            'petitioner_address' => [
                'label' => 'Address',
                'rules' => 'required|trim|min_length[10]|max_length[250]|regex_match[/^[a-zA-Z0-9\s.\-,\/]+$/]'
            ],
            'petitioner_state' => [
                'label' => 'Petitioner State',
                'rules' => 'required|trim'
            ],
            'petitioner_district' => [
                'label' => 'District',
                'rules' => 'required|trim'
            ],
            'petitioner_pincode' => [
                'label' => 'Pincode',
                'rules' => 'trim|exact_length[6]|is_natural'
            ],
            'respondent_name' => [
                'label' => 'Respondent Name',
                'rules' => 'required|trim|min_length[3]|max_length[99]|regex_match[/^[a-zA-Z0-9\s.\-\']+$/]'
            ],
            'respondent_state' => [
                'label' => 'Respondent State',
                'rules' => 'required|trim'
            ],
            'earlier_applies' => [
                'label' => 'Earlier Applies',
                'rules' => 'required|trim|in_list[N,Y]'
            ]
        ];
        $this->validation->setRules($validationRules);
        $this->validation->withRequest($this->request)->run();
        if (!$this->validation->run()) {
            echo '3@@@';
            echo implode('', $this->validation->getErrors());
            exit(0);
        } else {
            $cause_title = strtoupper(escape_data($this->request->getPost("petitioner_name"))) . ' Vs. ';
            $cause_title .= strtoupper(escape_data($this->request->getPost("respondent_name")));
            $registration_id = $_SESSION['efiling_details']['registration_id'];
            $earlier_applies = escape_data($this->request->getPost("earlier_applies"));
            $curr_dt_time = date('Y-m-d H:i:s');
            $case_details = array(
                'cause_title' => $cause_title,
                'sc_case_type_id' => 2,
                'sc_sp_case_type_id' => 6,
                'subj_main_cat' => 0,
                'subj_sub_cat_1' => 0,
                'subj_sub_cat_2' => 0,
                'subj_sub_cat_3' => 0,
                'subject_cat' => NULL,
                'question_of_law' => NULL,
                'grounds' => NULL,
                'grounds_interim' => NULL,
                'main_prayer' => NULL,
                'interim_relief' => NULL,
                'keywords' => NULL,
                'jail_petition_date' => $curr_dt_time,
                'earlier_applies' => $earlier_applies
            );
            $petitioner_name = strtoupper(escape_data($this->request->getPost("petitioner_name")));
            $petitioner_relation = escape_data($this->request->getPost("relation"));
            $relative_name = strtoupper(escape_data($this->request->getPost("relative_name")));
            if (isset($_POST['petitioner_dob']) && !empty($_POST['petitioner_dob'])) {
                $petitioner_dob = escape_data($this->request->getPost("petitioner_dob"));
                $petitioner_dob_array = explode('/', $petitioner_dob);
                $petitioner_dob = $petitioner_dob_array[2] . '-' . $petitioner_dob_array[1] . '-' . $petitioner_dob_array[0];
            } else {
                $petitioner_dob = NULL;
            }
            $petitioner_age = escape_data($this->request->getPost("petitioner_age"));
            $petitioner_gender = escape_data($this->request->getPost("petitioner_gender"));
            $petitioner_email = strtoupper(escape_data($this->request->getPost("petitioner_email")));
            $petitioner_mobile = escape_data($this->request->getPost("petitioner_mobile"));
            $petitioner_address = strtoupper(escape_data($this->request->getPost("petitioner_address")));
            $petitioner_city = strtoupper(escape_data($this->request->getPost("petitioner_city")));
            $petitioner_state_id = escape_data(url_decryption($this->request->getPost("petitioner_state")));
            $petitioner_district_id = escape_data(url_decryption($this->request->getPost("petitioner_district")));
            $petitioner_pincode = escape_data($this->request->getPost("petitioner_pincode"));
            $petitioner_details = array(
                'party_name' => $petitioner_name,
                'relation' => $petitioner_relation,
                'relative_name' => $relative_name,
                'party_age' => $petitioner_age,
                'party_dob' => $petitioner_dob,
                'gender' => $petitioner_gender,
                'email_id' => $petitioner_email,
                'mobile_num' => $petitioner_mobile,
                'address' => $petitioner_address,
                'city' => $petitioner_city,
                'state_id' => $petitioner_state_id,
                'district_id' => $petitioner_district_id,
                'pincode' => $petitioner_pincode,
                'is_org' => false,
                'party_type' => 'I',
                'p_r_type' => 'P',
                'm_a_type' => 'M',
                'party_no' => 1,
                'party_id' => 1
            );
            $respondent_name = strtoupper(escape_data($this->request->getPost("respondent_name")));
            $respondent_state_id = escape_data(url_decryption($this->request->getPost("respondent_state")));
            $respondent_details = array(
                'party_name' => $respondent_name,
                'state_id' => $respondent_state_id,
                'org_state_id' => $respondent_state_id,
                'org_state_name' => $respondent_name,
                'is_org' => true,
                'party_type' => 'D1',
                'p_r_type' => 'R',
                'm_a_type' => 'M',
                'party_no' => 1,
                'party_id' => 1
            );
            if (isset($registration_id) && !empty($registration_id) && isset($_SESSION['case_table_ids']['case_details_id']) && !empty($_SESSION['case_table_ids']['case_details_id']) && isset($_SESSION['case_table_ids']['m_respondent_id']) && !empty($_SESSION['case_table_ids']['m_respondent_id']) && isset($_SESSION['case_table_ids']['m_petitioner_id']) && !empty($_SESSION['case_table_ids']['m_petitioner_id'])) {
                $case_details_update_data = array(
                    'updated_on' => $curr_dt_time,
                    'updated_by' => $this->session->userdata['login']['id'],
                    'updated_by_ip' => getClientIP()
                );
                $case_details = array_merge($case_details, $case_details_update_data);
                $petitioner_details = array_merge($petitioner_details, $case_details_update_data);
                $respondent_details = array_merge($respondent_details, $case_details_update_data);
                $status = $this->JailPetitionModel->add_update_case_details($registration_id, $case_details, $petitioner_details, $respondent_details, JAIL_PETITION_CASE_DETAILS, $_SESSION['case_table_ids']['case_details_id'], $_SESSION['case_table_ids']['m_petitioner_id'], $_SESSION['case_table_ids']['m_respondent_id']);
                if ($status) {
                    echo '2@@@' . htmlentities('Case details updated successfully!', ENT_QUOTES) . '@@@' . base_url('jailPetition/defaultController/' . url_encryption(trim($registration_id . '#' . E_FILING_TYPE_JAIL_PETITION . '#' . $_SESSION['efiling_details']['stage_id'])));
                } else {
                    echo '1@@@' . htmlentities('Some error ! Please Try again.', ENT_QUOTES);
                }
            } else {
                $result = $this->JailPetitionModel->generate_efil_num_n_add_case_details($case_details, $petitioner_details, $respondent_details);
                // $_SESSION['case_table_ids']['m_petitioner_id'] = $inserted_party_id;
                // $_SESSION['case_table_ids']['m_respondent_id'] = $inserted_party_id;
                if ($result['registration_id']) {
                    $user_name = $_SESSION['login']['first_name'] . ' ' . $_SESSION['login']['last_name'];
                    $efiling_num = $_SESSION['efiling_details']['efiling_no'];
                    $sentSMS = "Efiling No. " . efile_preview($efiling_num) . " generated from your efiling account & still pending for final submit. - Supreme Court of India";
                    $subject = "Efiling No. " . efile_preview($efiling_num) . " generated from your efiling account";
                    send_mobile_sms($_SESSION['login']['mobile_number'], $sentSMS, SCISMS_Efiling_No_Generated);
                    send_mail_msg($_SESSION['login']['emailid'], $subject, $sentSMS, $user_name);
                    echo '2@@@' . htmlentities('Case details added successfully', ENT_QUOTES) . '@@@' . base_url('jailPetition/defaultController/' . url_encryption(trim($result['registration_id'] . '#' . E_FILING_TYPE_JAIL_PETITION . '#' . Draft_Stage)));
                } else {
                    echo '1@@@' . htmlentities('Some error ! Please Try again.', ENT_QUOTES);
                }
            }
        }
    }
}
