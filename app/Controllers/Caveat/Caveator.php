<?php

namespace App\Controllers\Caveat;

use App\Controllers\BaseController;
use App\Libraries\webservices\Efiling_webservices;
use App\Models\Common\CommonModel;
use App\Models\Caveat\CaveatCommonModel;
use App\Models\Caveat\CaveatorModel;
use App\Models\Clerk\ClerkModel;
class Caveator extends BaseController {

    protected $Common_model;
    protected $caveat_common_model;
    protected $Caveator_model;    
    protected $request;
    protected $validation;
    protected $session;
    protected $efiling_webservices;
    protected $Clerk_model;

    public function __construct() {       
        parent::__construct();
        $this->session = \Config\Services::session();
        if (empty(getSessionData('login'))) {
            return response()->redirect(base_url('/'));
        } else {
            is_user_status();
        }
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_DEPARTMENT, USER_ADMIN, USER_ADMIN_READ_ONLY, USER_EFILING_ADMIN, SR_ADVOCATE, ARGUING_COUNSEL);
        if (getSessionData('login') != '' && !in_array(getSessionData('login')['ref_m_usertype_id'], $allowed_users_array)) {
            return response()->redirect(base_url('/'));
           
        }
        $this->caveat_common_model = new CaveatCommonModel();
        $this->Caveator_model = new CaveatorModel();
        $this->Common_model = new CommonModel();
        $this->efiling_webservices = new Efiling_webservices();
        $this->validation = \Config\Services::validation();
        $this->request = \Config\Services::request();
        $this->Clerk_model = new ClerkModel();
    }

    public function index() {
        $allowedUsers = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);
        if (!in_array(getSessionData('login')['ref_m_usertype_id'], $allowedUsers)) {
            return redirect()->to(base_url('/'));
            exit(0);
        }        
        if (!empty(getSessionData('estab_details'))) {
            $estab_code = (getSessionData('estab_details')['est_code']);
            $efiling_for_type_id = getSessionData('estab_details')['efiling_for_type_id'];
            $state_code = getSessionData('estab_details')['state_code'];
        } else {
            $msg = getSessionData('MSG');
            $msg = message_show("fail", 'Invalid request !');
            return redirect()->to(base_url('whereToFile/caveat'));
            exit(0);
        }
        $allowed_stages = array('', Draft_Stage, Initial_Defected_Stage, E_REJECTED_STAGE);
        if (!in_array(getSessionData('efiling_details')['stage_id'], $allowed_stages)) { // echo 1; exit;
            return redirect()->to(base_url('caveat/view'));
            exit(0);
        }
        if (isset(getSessionData('efiling_details')['registration_id']) && !empty(getSessionData('efiling_details')['registration_id'])) {
            $registration_id = getSessionData('efiling_details')['registration_id'];
            $data['efiling_civil_data'] = $this->caveat_common_model->get_efiling_civil_details($registration_id);
            $data['efiling_civil_master_data'] = $this->caveat_common_model->get_efiling_civil_master_value($registration_id);
            $saved_state_id = $data['efiling_civil_data'][0]['state_id'];
            $saved_district_code = $data['efiling_civil_data'][0]['dist_code'];
            $saved_taluka_code = $data['efiling_civil_data'][0]['taluka_code'];
            $saved_town_code = $data['efiling_civil_data'][0]['town_code'];
            $data['org_list'] = $this->efiling_webservices->getOrgname($estab_code, $efiling_for_type_id, $state_code, $state_code);
            $data['relation_list'] = $this->efiling_webservices->getRelation($estab_code, $efiling_for_type_id, $state_code);
            $data['state_list'] = $this->efiling_webservices->getState($estab_code, $efiling_for_type_id, $state_code);
            if (isset($saved_state_id) && !empty($saved_state_id) && $saved_state_id != 0) {
                $data['distt_list'] = $this->efiling_webservices->getDistrict($estab_code, $efiling_for_type_id, $saved_state_id, $state_code);
                if (isset($saved_district_code) && !empty($saved_district_code) && $saved_district_code != 0) {
                    $data['pet_taluka_list'] = $this->efiling_webservices->getTaluka($estab_code, $saved_district_code, $efiling_for_type_id, $saved_state_id, $state_code);
                    if (isset($saved_taluka_code) && !empty($saved_taluka_code) && $saved_taluka_code != 0) {
                        $data['pet_vill_list'] = $this->efiling_webservices->getVillage($estab_code, $saved_district_code, $saved_taluka_code, $efiling_for_type_id, $saved_state_id, $state_code);
                    }
                    $data['pet_town_list'] = $this->efiling_webservices->getTown($estab_code, $saved_district_code, $efiling_for_type_id, $saved_state_id, $state_code);
                    if (isset($saved_town_code) && !empty($saved_town_code) && $saved_town_code != 0) {

                        $data['pet_ward_list'] = $this->efiling_webservices->getWard($estab_code, $saved_district_code, $saved_town_code, $efiling_for_type_id, $saved_state_id, $state_code);
                    }
                    $data['police_st_code'] = $this->efiling_webservices->get_police_st_by_district($estab_code, $efiling_for_type_id, $saved_district_code, $state_code);
                }
            }
            if($_SESSION['login']['ref_m_usertype_id'] == USER_CLERK){
                $data['selected_aor'] = $this->Clerk_model->selected_aor_for_case($registration_id);
            }
        } elseif (!empty(getSessionData('efiling_for_details'))) {
            $data['relation_list'] = $this->efiling_webservices->getRelation($estab_code, $efiling_for_type_id, $state_code);
            $data['org_list'] = $this->efiling_webservices->getOrgname($estab_code, $efiling_for_type_id, $state_code, $state_code);
            $data['state_list'] = $this->efiling_webservices->getState($estab_code, $efiling_for_type_id, $state_code);
        } else {
            return redirect()->to(base_url('dashboard'));
            exit(0);
        }
        if($_SESSION['login']['ref_m_usertype_id'] == USER_CLERK){
            $data['selected_aor'] = $this->Clerk_model->selected_aor_for_case($registration_id);
        }
        $this->render('caveat.caveat_view', $data, TRUE);
    }


    public function add_caveators() {
        $this->Common_model->get_establishment_details(); //add establishment in session
        $allowedUsers = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowedUsers)) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $allowed_stages = array('', Draft_Stage, Initial_Defected_Stage, E_REJECTED_STAGE);
        if (isset($_SESSION['efiling_details']) && !in_array($_SESSION['efiling_details']['stage_id'], $allowed_stages)) {
            echo '1@@@' . htmlentities("Update in Caveator details can be done only at 'Draft' and 'For Compliance' stages.", ENT_QUOTES);
            log_message('info', "Update in Caveator details can be done only at 'Draft' and 'For Compliance' stages.");
            exit(0);
            return redirect()->to(base_url('caveat/view'));
            exit(0);
        }
        $draft_data = $this->caveat_common_model->count_efilied_nums_on_draft();
        if($_SESSION['login']['ref_m_usertype_id'] == USER_ADVOCATE) {
            if ($draft_data[0]->total_drafts > ADVOCATE_DRAFT_NO) {
                $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center">Only' . ADVOCATE_DRAFT_NO . ' cases are maintain in Draft Stage. For filing this case please proceed available case in draft than file the case. </div>');
                log_message('info', "Only". ADVOCATE_DRAFT_NO . " cases are maintain in Draft Stage. For filing this case please proceed available case in draft than file the case.");
                return redirect()->to(base_url('dashboard'));
                exit(0);
            }
        } else if ($_SESSION['login']['ref_m_usertype_id'] == USER_IN_PERSON) {
            if ($draft_data[0]->total_drafts > PARTY_IN_PERSON_DRAFT_SIZE) {
                $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center">Only' . PARTY_IN_PERSON_DRAFT_SIZE . ' cases are maintain in Draft Stage. For filing this case please proceed available case in draft than file the case. </div>');
                log_message('info', "Only". PARTY_IN_PERSON_DRAFT_SIZE . "cases are maintain in Draft Stage. For filing this case please proceed available case in draft than file the case. ");
                return redirect()->to(base_url('dashboard'));
                exit(0);
            }
        }
        $this->validation->setRules([
            'case_type' => 'required|trim',
            'party_is' => 'required|trim',
        ]);
        if(!empty($_POST['party_is']) && escape_data($_POST['party_is']) == 'I') {
            $this->validation->setRules([
                'pet_complainant' => 'required|trim|min_length[3]|max_length[99]|validateAlphaNumericSingleDoubleQuotesBracketWithSpecialCharacters',
                'pet_rel_flag' => 'required|trim',
                'relative_name' => 'required|trim|min_length[3]|max_length[99]|validateAlphaNumericSingleDoubleQuotesBracketWithSpecialCharacters',
                'pet_age' => 'required|trim|exact_length[2]|is_natural',
                'pet_gender' => 'required|trim',
            ]);
        } else if (escape_data($_POST['party_is']) == 'D1' || escape_data($_POST['party_is']) == 'D2'){
            if(isset($_POST['org_state']) && !empty($_POST['org_state'])){
                $otherstatename_valid=url_decryption($_POST['org_state']);
                if($otherstatename_valid == 0)
                    $this->validation->setRules([
                        'org_state_name' => 'required|trim|validate_alpha_numeric_space_dot_hyphen_comma',
                    ]);
                else
                    $this->validation->setRules([
                        'org_state' => 'required|trim',
                    ]);
            }
            if(isset($_POST['org_dept']) && !empty($_POST['org_dept'])){
                $otherdeptname_valid=url_decryption($_POST['org_dept']);
                if($otherdeptname_valid == 0)
                    $this->validation->setRules([
                        'org_dept_name' => 'required|trim|validate_alpha_numeric_space_dot_hyphen_comma',
                    ]);
                else
                    $this->validation->setRules([
                        'org_dept' => 'required|trim',
                    ]);
            }
            if(isset($_POST['org_post']) && !empty($_POST['org_post'])){
                $otherpostname_valid=url_decryption($_POST['org_post']);
                if($otherpostname_valid == 0)
                    $this->validation->setRules([
                        'org_post_name' => 'required|trim|validate_alpha_numeric_space_dot_hyphen_comma',
                    ]);
                else
                    $this->validation->setRules([
                        'org_post' => 'required|trim',
                    ]);
            }
        } else if (escape_data($_POST['party_is']) == 'D3'){
            $this->validation->setRules([
                'org_dept' => 'required|trim',
            ]);
        }
        $this->validation->setRules([
            'pet_email' => 'required|trim|min_length[6]|max_length[49]|valid_email',
            "pet_mobile" => [
                "label" => "Mobile",
                "rules" => "trim|exact_length[10]|is_natural",
                "errors"=>[
                    "min_length"=>"The mobile field must be exactly 10 characters in length."
                ]
            ],
            'pet_address' => 'required|trim|min_length[3]|max_length[250]|validateAlphaNumericSingleDoubleQuotesBracketWithSpecialCharacters',
            'party_city' => 'required|trim|min_length[3]|max_length[49]|validateAlphaNumericSingleDoubleQuotesBracketWithSpecialCharacters',
            'party_state' => 'required|trim',
            'party_district' => 'required|trim',
            'party_pincode' => 'required|trim|exact_length[6]|is_natural',
        ]);
        if (in_array($_SESSION['login']['ref_m_usertype_id'], [USER_CLERK])) {
            $this->validation->setRules([
                'impersonated_aor' => 'trim|is_required',
            ]);
        }
        if (!$this->validation->withRequest($this->request)->run()) {
            echo '1@@@' . implode('<br>', $this->validation->getErrors());
            return;
        }
        if (in_array($_SESSION['login']['ref_m_usertype_id'], [USER_CLERK])) {
            $_SESSION['login']['aor_code'] = $this->request->getPost("impersonated_aor");
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
        } else {
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
        $id = !empty($_SESSION['login']['userid']) ? $_SESSION['login']['id'] : NULL;
        $case_type = !empty($_POST['case_type']) ? url_decryption(escape_data($_POST['case_type'])) : NULL;
        $pet_extracount = !empty($_POST['pet_extracount']) ? escape_data($_POST['pet_extracount']) : 1;
        $is_govt_filing=trim($this->request->getPost("is_govt_filing"));
        $is_govt_filing =  !empty($is_govt_filing) ? 1 : 0;
        if (in_array($_SESSION['login']['ref_m_usertype_id'], [USER_CLERK])) {
            if (isset($_POST['impersonated_aor']) && !empty($_POST['impersonated_aor'])){
                $impersonated_aor = explode('@@@', escape_data($this->request->getPost("impersonated_aor")));
                if (!empty($impersonated_aor)){
                    $_SESSION['login']['aor_code'] = $impersonated_aor[0];
                }
            } else {
                echo '1@@@AOR field is required.'; exit();
            }
            $aorData = getAordetailsByAORCODE($_SESSION['login']['aor_code']);
            $id=!empty($aorData) ? $aorData[0]->id:0;
            if (empty($id)){ echo '1@@@' . htmlentities("*Please AOR field is required $id", ENT_QUOTES);}
        }
        if (isset($_SESSION['login']['aor_code']) && !empty($_SESSION['login']['aor_code'])){
            if (is_AORGovernment($_SESSION['login']['aor_code'])){
                $is_govt_filing =$is_govt_filing;
            } else{
                $is_govt_filing =0;
            }
        }
        $data = array(
            'pet_name' => strtoupper($pet_complainant),
            'orgid' => $organisation_id,
            'pet_sex' => $pet_gender,
            'pet_gender' => $pet_gender,
            'pet_father_flag' => $pet_relation,
            'pet_father_name' => strtoupper($relative_name),
            'pet_age' => $pet_age,
            'pet_dob' => $pet_dob,
            'pet_email' => strtoupper($pet_email),
            'pet_mobile' => $pet_mobile,
            'petadd' => strtoupper($pet_address),
            'pet_pincode' => $pet_pincode,
            'state_id' => $state_id,
            'dist_code' => $district_id,
            'org_state' => $org_state,
            'org_state_name' => $org_state_name,
            'org_dept' => $org_dept,
            'org_dept_name' => $org_dept_name,
            'org_post' => $org_post,
            'org_post_name' => $org_post_name,
            'pet_city'=>$pet_city,
            'createdby'=>$id,
            'case_type_id'=>$case_type,
            'is_govt_filing' => $is_govt_filing,
        );
        $cis_masters_values = array(
        );
        $registration_id = isset($_SESSION['efiling_details']) && !empty($_SESSION['efiling_details']['registration_id']) ? $_SESSION['efiling_details']['registration_id'] : '';
        if ($registration_id != '' && !empty($registration_id)) {
            if (in_array($_SESSION['login']['ref_m_usertype_id'], [USER_CLERK])) {

                $aorData = getAordetailsByAORCODE($_SESSION['login']['aor_code']);
                $created_by=!empty($aorData) ? $aorData[0]->id  : 0;
                $case_details_update_data = array(
                    'created_by' => $created_by,
                    'updated_on' => date('Y-m-d H:i:s'),
                    'updated_by' => $this->session->userdata['login']['id'],
                    'updated_by_ip' => $_SERVER['REMOTE_ADDR']
                );
                $this->Common_model->update_efiling_nums($registration_id,$case_details_update_data);
                if (isset($registration_id) && isset($_SESSION['login']['aor_code'])){
                    $this->Clerk_model->clerk_filings_update($registration_id,$_SESSION['login']['aor_code']);
                }
                unset($data['createdby']);
                $data = array_merge($data, array('createdby'=>$created_by,'updated_by' => $this->session->userdata['login']['id'],'updated_by_ip' => $_SERVER['REMOTE_ADDR']));
            }
            $this->Caveator_model->update_caveators($registration_id, $data, $cis_masters_values);

            echo '2@@@' . htmlentities('Caveator updated successfully!', ENT_QUOTES) . '@@@' . base_url('caveat/defaultController/processing/' . url_encryption(trim($registration_id . '#' . E_FILING_TYPE_CAVEAT . '#' . Draft_Stage)));
        } else {
            $result = $this->Caveator_model->add_caveator($data, $pet_mobile, $pet_email, $cis_masters_values);
            if ($result) {
                if (in_array($_SESSION['login']['ref_m_usertype_id'], [USER_CLERK])) {
                    if (isset($result['registration_id']) && isset($_SESSION['login']['aor_code'])){
                        $this->Clerk_model->clerk_filings_insert($result['registration_id'],$_SESSION['login']['aor_code']);
                    }
                }
                echo '2@@@' . htmlentities('Caveator added successfully', ENT_QUOTES) . '@@@' . base_url('caveat/defaultController/processing/' . url_encryption(trim($result['registration_id'] . '#' . E_FILING_TYPE_CAVEAT . '#' . Draft_Stage)));
            } else {
                echo '1@@@' . htmlentities('eFiling Failed. Please Try again', ENT_QUOTES);
            }
        }
    }

}