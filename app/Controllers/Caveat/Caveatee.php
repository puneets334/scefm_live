<?php
namespace App\Controllers\Caveat;
use App\Controllers\BaseController;
use App\Models\Caveat\CaveatCommonModel;
use App\Models\Caveat\CaveateeModel;
use App\Models\Caveat\SubordinateCourtModel;
use App\Models\Common\CommonModel;
use App\Models\NewCase\DropdownListModel;

class Caveatee extends BaseController {

    protected $Common_model;
    protected $Caveat_common_model;
    protected $Caveatee_model;
    protected $db;
    protected $Dropdown_list_model;
    protected $request;
    protected $validation;

    public function __construct() {
        parent::__construct();
       
        $this->Caveat_common_model = new CaveatCommonModel();
        $this->Caveatee_model = new CaveateeModel();
        $this->Dropdown_list_model = new DropdownListModel();
        $this->Common_model = new CommonModel();

        $this->request = \Config\Services::request();
        $this->validation = \Config\Services::validation();
    }

    public function index() {

       
       
        $allowedUsers = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);
        $data = array();
        if (isset($_SESSION['login']) && !in_array(getSessionData('login')['ref_m_usertype_id'], $allowedUsers)) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        if (isset($_SESSION['efiling_details']) && !in_array(CAVEAT_BREAD_CAVEATOR, explode(',', getSessionData('efiling_details')['breadcrumb_status']))) {
            $_SESSION['MSG'] = message_show("fail", 'Please first save <b>Caveator Information</b>.');
            return redirect()->to(base_url('caveat'));
            exit(0);
        }
        if (!empty(getSessionData('estab_details'))) {
            $efiling_for_type_id = getSessionData('estab_details')['efiling_for_type_id'];
            $state_code = getSessionData('estab_details')['state_code'];
        } else {

            $_SESSION['MSG'] = message_show("fail", 'Invalid request !');
            return redirect()->to(base_url('whereToFile/caveat'));
            exit(0);
        }
        $allowed_stages = array(Draft_Stage, Initial_Defected_Stage, E_REJECTED_STAGE);
        if (!empty(getSessionData('efiling_details')['stage_id']) && !in_array(getSessionData('efiling_details')['stage_id'], $allowed_stages)) {
            return redirect()->to(base_url('caveat/view'));
            exit(0);
        }
        // $data['state_list'] = $this->Dropdown_list_model->get_states_list();
        $data['state_list'] = $this->Dropdown_list_model->get_address_state_list();
        $data['case_type_res_title'] = 'Caveatee Information';
        if(!empty(getSessionData('efiling_details')['registration_id'])){
            $registration_id = (int)getSessionData('efiling_details')['registration_id'];
            $arr = array();
            $arr['registration_id'] = $registration_id;
            $arr['step']= 2;
            $response = $this->Common_model->getCaveatDataByRegistrationId($arr);
            //pr($response);
            $state_id = NULL;
            if(isset($response) && !empty($response)){
                $state_id = !empty($response[0]['res_state_id']) ? (int)$response[0]['res_state_id'] : NULL;
                $data['district_list'] = $this->Dropdown_list_model->get_districts_list($state_id);
                if(!empty($response[0]['resorgid']) && $response[0]['resorgid'] !='I'){
                    $party_is = trim($response[0]['resorgid']);
                    $data['dept_list'] = $this->Dropdown_list_model->get_departments_list($party_is);
                    $data['post_list'] = $this->Dropdown_list_model->get_posts_list();
                }
            }
           
            $data['caveatee_details'] = $response;
            
        }
        $this->render('caveat.caveat_view', $data);
    }

    public function add_caveatee() {
      
       
        $allowedUsers = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);
        if (!in_array(getSessionData('login')['ref_m_usertype_id'], $allowedUsers)) {
           return redirect()->to(base_url('/'));
            exit(0);
        }
        $allowed_stages = array(Draft_Stage, Initial_Defected_Stage, E_REJECTED_STAGE);
        if (!empty(getSessionData('efiling_details')['stage_id']) && !in_array(getSessionData('efiling_details')['stage_id'], $allowed_stages)) {
            echo '1@@@' . htmlentities("Update in Caveatee details can be done only at 'Draft' and 'For Compliance' stages.", ENT_QUOTES);
            exit(0);
            return redirect()->to(base_url('caveat/view'));
            exit(0);
        }
      
        $this->validation->setRules(['party_is'=>["label"=>"Caveator is","rules"=> "required|trim"]]);
        if(escape_data($_POST['party_is']) == 'I') {

            $this->validation->setRules([
                "pet_complainant" => [
                    "label" => "Caveator Name",
                    "rules" => "required|trim|min_length[3]|max_length[99]|validate_alpha_numeric_single_double_quotes_bracket_with_special_characters"
                ],
                "pet_rel_flag" => [
                    "label" => "Relation",
                    "rules" => "required|trim"
                ],
                "relative_name" => [
                    "label" => "Relative Name",
                    "rules" => "trim|min_length[3]|max_length[99]|validate_alpha_numeric_single_double_quotes_bracket_with_special_characters"
                ],
                "pet_dob" => [
                    "label" => "D.O.B",
                    "rules" => "required|trim"
                ],
                "pet_age" => [
                    "label" => "Age",
                    "rules" => "required|trim|exact_length[2]|is_natural"
                ],
                "pet_gender" => [
                    "label" => "Gender",
                    "rules" => "required|trim"
                ],
            ]);

        }
        else if (escape_data($_POST['party_is']) == 'D1' || escape_data($_POST['party_is']) == 'D2'){
           
            if(isset($_POST['org_state']) && !empty($_POST['org_state'])){
                $otherstatename_valid=url_decryption($_POST['org_state']);
                
                if($otherstatename_valid == 0)
                $this->validation->setRules(['org_state_name'=>["label"=>"Other state name","rules"=> "required|trim|validate_alpha_numeric_space_dot_hyphen_comma"]]);
                else
                $this->validation->setRules(['org_state'=>["label"=>"State name","rules"=> "required|trim"]]);
            }
            if(isset($_POST['org_dept']) && !empty($_POST['org_dept'])){
               
                $otherdeptname_valid=url_decryption($_POST['org_dept']);
                if($otherdeptname_valid == 0)
                $this->validation->setRules(['org_dept_name'=>["label"=>"Other department name","rules"=> "required|trim|validate_alpha_numeric_space_dot_hyphen_comma"]]);
                else
                $this->validation->setRules(['org_dept'=>["label"=>"Department name","rules"=> "required|trim"]]);
            }
            if(isset($_POST['org_post']) && !empty($_POST['org_post'])){
               
                $otherpostname_valid=url_decryption($_POST['org_post']);
                if($otherpostname_valid == 0)
                $this->validation->setRules(['org_post_name'=>["label"=>"Organisation post name","rules"=> "required|trim|validate_alpha_numeric_space_dot_hyphen_comma"]]);
                else
                $this->validation->setRules(['org_post'=>["label"=>"Organisation post name","rules"=> "required|trim"]]);
               
            }
          
        }
        else if (escape_data($_POST['party_is']) == 'D3'){
            $this->validation->setRules(['org_dept'=>["label"=>"Organisation department","rules"=> "required|trim"]]);
           
        }
       
        $email_required = isset(getSessionData('estab_details')['is_pet_email_required']) && getSessionData('estab_details')['is_pet_email_required']  == 't' ? 'required|' : '';
        $mobile_required = isset(getSessionData('estab_details')['is_pet_mobile_required']) && getSessionData('estab_details')['is_pet_mobile_required'] == 't' ? 'required|' : '';
        $state_required = isset(getSessionData('estab_details')['is_pet_state_required']) && getSessionData('estab_details')['is_pet_state_required'] == 't' ? 'required' : '';
        $district_required = isset(getSessionData('estab_details')['is_pet_district_required']) && getSessionData('estab_details')['is_pet_district_required'] == 't' ? 'required' : '';
        
        $this->validation->setRules([
            "pet_email" => [
                "label" => "Email",
                "rules" => "trim|min_length[6]|max_length[49]|valid_email"
            ],
            "pet_mobile" => [
                "label" => "Mobile",
                "rules" => "trim|exact_length[10]|is_natural"
            ],
            "pet_address" => [
                "label" => "Address",
                "rules" => "required|trim|min_length[3]|max_length[250]|validate_alpha_numeric_single_double_quotes_bracket_with_special_characters"
            ],
            "party_city" => [
                "label" => "Relative Name",
                "rules" => "trim|min_length[3]|max_length[99]|validate_alpha_numeric_single_double_quotes_bracket_with_special_characters"
            ],
            "party_state" => [
                "label" => "City",
                "rules" => "required|trim|min_length[3]|max_length[49]|validate_alpha_numeric_single_double_quotes_bracket_with_special_characters"
            ],
            "party_district" => [
                "label" => "party_district",
                "rules" => "trim|required"
            ],
           'party_pincode' => 'required|trim|exact_length[6]|is_natural',
        ]);
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
       
        if($pet_gender=='undefined')
        {
        $pet_gender = NULL;
        }
        if ($organisation_id != 'I') {
            $is_org = TRUE;
            if ($organisation_id != 'D3') {
                $org_state = url_decryption($_POST["org_state"]);
                if ($org_state == '0') {
                    $org_state_name = $_POST["org_state_name"];
                    $org_state_not_in_list = TRUE;
                } else {
                    $org_state_name = NULL;
                    $org_state_not_in_list = FALSE;
                }
            } else {
                $org_state = 00;
                $org_state_name = NULL;
                $org_state_not_in_list = FALSE;
            }
    
            $org_dept = url_decryption($_POST["org_dept"]);
            if ($org_dept == '0') {
                $org_dept_name = $_POST["org_dept_name"];
                $org_dept_not_in_list = TRUE;
            } else {
                $org_dept_name = NULL;
                $org_dept_not_in_list = FALSE;
            }
    
            $org_post = url_decryption($_POST["org_post"]);
            if ($org_post == '0') {
                $org_post_name = $_POST["org_post_name"];
                $org_post_not_in_list = TRUE;
            } else {
                $org_post_name = NULL;
                $org_post_not_in_list = FALSE;
            }
        } else {
            $org_state = 00;
            $org_state_name = NULL;
            $org_state_not_in_list = FALSE;
            $org_dept = 0;
            $org_dept_name = NULL;
            $org_dept_not_in_list = FALSE;
            $org_post = 0;
            $org_post_name = NULL;
            $org_post_not_in_list = FALSE;
        }

        $pet_email = !empty($_POST["pet_email"]) ? escape_data(strtoupper($_POST["pet_email"])) : NULL;
        $pet_mobile = !empty($_POST["pet_mobile"]) ? escape_data(strtoupper($_POST["pet_mobile"])) : NULL;
        $pet_address = !empty($_POST["pet_address"]) ? escape_data(strtoupper($_POST["pet_address"])) : NULL;
        $state_id = !empty($_POST['party_state']) ? url_decryption(escape_data($_POST['party_state'])) : NULL;
        $district_id = !empty($_POST['party_district']) ? url_decryption(escape_data($_POST['party_district'])) : NULL;
        $pet_pincode = !empty($_POST['party_pincode']) ? escape_data($_POST['party_pincode']) : NULL;
        $pet_city = !empty($_POST['party_city']) ? escape_data($_POST['party_city']) : NULL;
        
        $data = array(
            'res_name' => isset($pet_complainant) ? strtoupper($pet_complainant) : NULL,
            'resorgid' => $organisation_id,
            'res_sex' => $pet_gender,
            'res_gender' => $pet_gender,
            'res_father_flag' => $pet_relation,
            'res_father_name' => isset($relative_name) ? strtoupper($relative_name) : NULL,
            'res_age' => $pet_age,
            'res_dob' => $pet_dob,
            'res_email' => isset($pet_email) ? strtoupper($pet_email) : NULL,
            'res_mobile' => $pet_mobile,
            'resadd' => isset($pet_address) ? strtoupper($pet_address) : NULL,
            'res_pincode' => $pet_pincode,
            'res_state_id' => $state_id,
            'res_dist' => $district_id,
            'res_city'=>$pet_city,
            'res_org_state' => $org_state,
            'res_org_state_name' => $org_state_name,
            'res_org_dept' => $org_dept,
            'res_org_dept_name' => $org_dept_name,
            'res_org_post' => $org_post,
            'res_org_post_name' => $org_post_name,
        );

        $cis_masters_values = array(
        );
        
        $registration_id = isset(getSessionData('efiling_details')['registration_id'])?getSessionData('efiling_details')['registration_id']:'';
        $result = $this->Caveatee_model->add_caveatee($registration_id, $data, $cis_masters_values);
        if ($result) {
            echo '2@@@' . htmlentities('Caveatee added successfully!', ENT_QUOTES) . '@@@' . base_url('caveat/defaultController/processing/' . url_encryption(isset($registration_id)? trim($registration_id . '#' . E_FILING_TYPE_CAVEAT . '#' . Draft_Stage):''));
        } else {
            echo '1@@@' . htmlentities('Some things went wrong. Please Try again', ENT_QUOTES) . '@@@' . base_url('caveat/defaultController/processing/' . url_encryption(isset($registration_id)? trim($registration_id . '#' . E_FILING_TYPE_CAVEAT . '#' . Draft_Stage):''));
        }
    }

}
