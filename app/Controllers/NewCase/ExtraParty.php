<?php

// if (!defined('BASEPATH')) exit('No direct script access allowed');

namespace App\Controllers\Newcase;

use App\Controllers\BaseController;
use App\Models\Common\CommonModel;
use App\Models\Common\FormValidationModel;
use App\Models\NewCase\NewCaseModel;
use App\Models\NewCase\DropdownListModel;
use App\Models\NewCase\GetDetailsModel;

class ExtraParty extends BaseController {

    protected $session;
    protected $CommonModel;
    protected $FormValidationModel;
    protected $NewCaseModel;
    protected $DropdownListModel;
    protected $GetDetailsModel;
    protected $request;
    protected $validation;

    public function __construct() {
        parent::__construct();
        $this->session = \Config\Services::session();
        if (empty($this->session->get('login'))) {
            return response()->redirect(base_url('/'));
        } else{
            is_user_status();
        }
        $this->CommonModel = new CommonModel();
        $this->FormValidationModel = new FormValidationModel();
        $this->NewCaseModel = new NewCaseModel();
        $this->FormValidationModel = new FormValidationModel();
        $this->DropdownListModel = new DropdownListModel();
        $this->GetDetailsModel = new GetDetailsModel();
        $this->request = \Config\Services::request();
        $this->validation = \Config\Services::validation();
    }

    // public function _remap($param = NULL) {        
    //     if($this->uri->total_segments()== 4) {             
    //         $this->add_extra_party($this->uri->segment(4));
    //     } elseif ($param == 'index') {
    //         $this->index(NULL);
    //     } elseif ($param == 'add_extra_party') {
    //         $this->add_extra_party();
    //     } else{
    //         $this->index($param);
    //     }
    // }

    public function index($party_id = NULL) {
        return redirect()->to(base_url('newcase/subordinate_court')); exit(0);
        if (isset($party_id) && !empty($party_id)) {
            $party_id = url_decryption($party_id);
            if (!$party_id) {
                $_SESSION['MSG'] = message_show("fail", "Invalid ID.");
                return redirect()->to(base_url('newcase/extra_party'));
                exit(0);
            }
        }
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_DEPARTMENT);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            return redirect()->to(base_url('admindashboard'));
            exit(0);
        }
        // $stages_array = array('', Draft_Stage, Initial_Defected_Stage, I_B_Defected_Stage, E_REJECTED_STAGE);
        $stages_array = array('', Draft_Stage, Initial_Defected_Stage,  E_REJECTED_STAGE);
        if (!in_array($_SESSION['efiling_details']['stage_id'], $stages_array)) {
            return redirect()->to(base_url('newcase/view'));
            exit(0);
        }
        // $data['state_list'] = $this->DropdownListModel->get_states_list();
        $data['state_list'] = $this->DropdownListModel->get_address_state_list();
        $countryList = $this->DropdownListModel->getCountryList();
        $data['countryList'] = !empty($countryList) ? $countryList : NULL;
        if (isset($_SESSION['efiling_details']['registration_id']) && !empty($_SESSION['efiling_details']['registration_id'])) {
            $registration_id = $_SESSION['efiling_details']['registration_id'];
            $data['extra_parties_list'] = $this->GetDetailsModel->get_case_parties_details($registration_id, array('p_r_type' => NULL, 'm_a_type' => 'A', 'party_id' => NULL, 'view_lr_list'=> FALSE)); 
            if (isset($party_id) && !empty($party_id)) {                
                $data['party_id'] = $party_id;
                $data['party_details'] = $this->GetDetailsModel->get_case_parties_details($registration_id, array('p_r_type' => NULL, 'm_a_type' => NULL, 'party_id' => $party_id, 'view_lr_list'=> FALSE));
                $data['district_list'] = $this->DropdownListModel->get_districts_list($data['party_details'][0]['state_id']);
            }
        }
        if(!empty($this->session->userdata['efiling_details']['breadcrumb_status'])) {
            $breadCrumArr = explode(',',$this->session->userdata['efiling_details']['breadcrumb_status']);
            if(in_array(NEW_CASE_LRS,$breadCrumArr)) {
                $data['is_dead_data'] = true;
            } else{
                $data['is_dead_data'] = false;
            }
        }
        // $this->load->view('templates/header');
        // $this->load->view('newcase/new_case_view', $data);
        // $this->load->view('templates/footer');
        return $this->render('newcase.new_case_view', $data);
    }

    public function add_extra_party($party_id = NULL) {        
        if (isset($party_id) && !empty($party_id)) {
            $party_id = url_decryption($party_id);
            if (!$party_id) {
                $_SESSION['MSG'] = message_show("fail", "Invalid ID.");
                return redirect()->to(base_url('newcase/extra_party'));
                exit(0);
            }
        }
        $validations = [];
        $stages_array = array('', Draft_Stage, Initial_Defected_Stage, I_B_Defected_Stage);
        if (!in_array($_SESSION['efiling_details']['stage_id'], $stages_array)) {
            echo '1@@@' . htmlentities("Update in Party details can be done only at 'Draft', 'For Compliance' stages' and 'Defective' stages.", ENT_QUOTES);
            exit(0);
        }
        $country_id = !empty($_POST['country_id']) ? url_decryption($_POST['country_id']) : NULL;
        $validations = [
            "party_as" => [
                "label" => "Party As",
                "rules" => "required|in_list[I,D1,D2,D3]"
            ],
        ];
        $validations += [
            "p_r_type" => [
                "label" => "Extra Party Side",
                "rules" => "required|in_list[P,R]"
            ],
        ];
        if ($_POST['party_as'] == 'I') {
            $validations += [
                "party_name" => [
                    "label" => "Petitioner name",
                    "rules" => "required|trim|min_length[3]|max_length[99]|validateAlphaNumericSingleDoubleQuotesBracketWithSpecialCharacters"
                ],
                "relation" => [
                    "label" => "Relation",
                    "rules" => "required|in_list[S,D,W,N]"
                ],
            ];
            $rel = escape_data($_POST["relation"]);
            if($rel=='N') {
                $validations += [
                    "relative_name" => [
                        "label" => "Father/Husband name",
                        "rules" => "trim|min_length[3]|max_length[99]|validateAlphaNumericSingleDoubleQuotesBracketWithSpecialCharacters"
                    ],
                ];
            } else{
                $validations += [
                    "relative_name" => [
                        "label" => "Father/Husband name",
                        "rules" => "required|trim|min_length[3]|max_length[99]|validateAlphaNumericSingleDoubleQuotesBracketWithSpecialCharacters"
                    ],
                ];
            }
            if($_POST['is_dead_minor'] == '0') {
                $is_dead_minor = false;
                $is_dead_file_status = false;
                $validations += [
                    "party_age" => [
                        "label" => "Age",
                        "rules" => "trim|required|min_length[1]|max_length[2]|is_natural"
                    ],
                    "party_address" => [
                        "label" => "Address",
                        "rules" => "required|trim|min_length[3]|max_length[250]|validateAlphaNumericSingleDoubleQuotesBracketWithSpecialCharacters"
                    ],
                ];
                if(isset($country_id) && !empty($country_id) && $country_id == 96) {
                    $validations += [
                        "party_city" => [
                            "label" => "City",
                            "rules" => "required|trim|min_length[3]|max_length[49]|validateAlphaNumericSingleDoubleQuotesBracketWithSpecialCharacters"
                        ],
                        "party_state" => [
                            "label" => "State",
                            "rules" => "required|trim|validate_encrypted_value"
                        ],
                        "party_district" => [
                            "label" => "District",
                            "rules" => "required|trim|validate_encrypted_value"
                        ],
                    ];
                } elseif(isset($country_id) && !empty($country_id) && $country_id != 96 && $_POST['is_dead_minor'] == '1') {
                    $validations += [
                        "party_city" => [
                            "label" => "City",
                            "rules" => "trim|min_length[3]|max_length[49]|validateAlphaNumericSingleDoubleQuotesBracketWithSpecialCharacters"
                        ],
                        "party_state" => [
                            "label" => "State",
                            "rules" => "trim|validate_encrypted_value"
                        ],
                        "party_district" => [
                            "label" => "District",
                            "rules" => "trim|validate_encrypted_value"
                        ],
                    ];
                }
            } else{
                $is_dead_minor = true;
                $is_dead_file_status = false;
            }
            /*END OF CHANGES ON on 03/09/20.*/
            $validations += [
                "party_dob" => [
                    "label" => "D.O.B.",
                    "rules" => "trim"
                ],
                "party_gender" => [
                    "label" => "Gender",
                    "rules" => "trim|required|in_list[M,F,O]"
                ],
            ];
        } else{
            if ($_POST['party_as'] != 'D3') {
                $validations += [
                    "org_state" => [
                        "label" => "Organisation State Name",
                        "rules" => "required|trim|validate_encrypted_value"
                    ],
                ];
                if (isset($_POST['org_state']) && url_decryption($_POST['org_state']) == 0) {
                    $validations += [
                        "org_state_name" => [
                            "label" => "Organisation Other State Name",
                            "rules" => "required|trim|min_length[5]|max_length[200]|validate_alpha_numeric_space_dot_hyphen"
                        ],
                    ];
                }
            }
            $validations += [
                "org_dept" => [
                    "label" => "Organisation Department name",
                    "rules" => "required|trim|validate_encrypted_value"
                ],
            ];
            if (isset($_POST['org_dept']) && url_decryption($_POST['org_dept']) == 0) {
                $validations += [
                    "org_dept_name" => [
                        "label" => "Other Department name",
                        "rules" => "required|trim|min_length[5]|max_length[200]|validate_alpha_numeric_space_dot_hyphen"
                    ],
                ];
            }
            $validations += [
                "org_post" => [
                    "label" => "Post",
                    "rules" => "required|trim|validate_encrypted_value"
                ],
            ];
            if (isset($_POST['org_post']) && url_decryption($_POST['org_post']) == 0) {
                $validations += [
                    "org_post_name" => [
                        "label" => "Other Post",
                        "rules" => "required|trim|min_length[5]|max_length[200]|validate_alpha_numeric_space_dot_hyphen"
                    ],
                ];
            }
        }
        $validations += [
            "party_email" => [
                "label" => "Email",
                "rules" => "required|trim|min_length[6]|max_length[49]|valid_email"
            ],
            "party_mobile" => [
                "label" => "Mobile number",
                "rules" => "required|trim|exact_length[10]|is_natural"
            ],
            "party_pincode" => [
                "label" => "Pincode",
                "rules" => "required|trim|exact_length[6]|is_natural"
            ],
        ];
        $this->validation->setRules($validations);
        if ($this->validation->withRequest($this->request)->run() === FALSE) {
            echo '3@@@';
            $errors = $this->validation->getErrors();
            foreach ($errors as $field => $error) {
                if(strpos($error, '{field}')){
                    echo str_replace('{field}', $field, $error) . "<br>";
                } else{
                    echo $error . "<br>";
                }
            }
            exit(0);
        }
        $registration_id = $_SESSION['efiling_details']['registration_id'];        
        $p_r_type = strtoupper(escape_data($_POST["p_r_type"]));        
        $party_max = $this->GetDetailsModel->get_max_party_num_n_id($registration_id, $p_r_type);
        $is_org = FALSE;        
        $party_as = strtoupper(escape_data($_POST["party_as"]));
        $party_name = strtoupper(escape_data($_POST["party_name"]));
        $party_relation = escape_data($_POST["relation"]);
        $relative_name = strtoupper(escape_data($_POST["relative_name"]));
        $party_dob = escape_data($_POST["party_dob"]);
        if (isset($_POST['party_dob']) && !empty($_POST['party_dob'])) {
            $party_dob_array = explode('/', $party_dob);
            $party_dob = $party_dob_array[2] . '-' . $party_dob_array[1] . '-' . $party_dob_array[0];
        } else{
            $party_dob = NULL;
        }
        $party_age = !empty($_POST["party_age"]) ? escape_data($_POST["party_age"]) : NULL;
        $party_gender = escape_data($_POST["party_gender"]);
        if ($party_as != 'I') {
            $is_org = TRUE;
            if ($party_as != 'D3') {
                $org_state = url_decryption(escape_data($_POST["org_state"]));
                if ($org_state == '0') {
                    $org_state_name = strtoupper(escape_data($_POST["org_state_name"]));
                    $org_state_not_in_list = TRUE;
                } else{
                    $org_state_name = NULL;
                    $org_state_not_in_list = FALSE;
                }
            } else{
                $org_state = 0;
                $org_state_name = NULL;
                $org_state_not_in_list = FALSE;
            }
            $org_dept = url_decryption(escape_data($_POST["org_dept"]));
            if ($org_dept == '0') {
                $org_dept_name = strtoupper(escape_data($_POST["org_dept_name"]));
                $org_dept_not_in_list = TRUE;
            } else{
                $org_dept_name = NULL;
                $org_dept_not_in_list = FALSE;
            }
            $org_post = url_decryption(escape_data($_POST["org_post"]));
            if ($org_post == '0') {
                $org_post_name = strtoupper(escape_data($_POST["org_post_name"]));
                $org_post_not_in_list = TRUE;
            } else{
                $org_post_name = NULL;
                $org_post_not_in_list = FALSE;
            }
            $party_name = NULL;
            $party_relation = NULL;
            $relative_name = NULL;
            $party_age = NULL;
            $party_dob = NULL;
            $party_gender = NULL;
        } else{
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
        $party_email = strtoupper(escape_data($_POST["party_email"]));
        $party_mobile = escape_data($_POST["party_mobile"]);
        $party_address = strtoupper(escape_data($_POST["party_address"]));
        $party_city = !empty($_POST["party_city"]) ? strtoupper(escape_data($_POST["party_city"])) : NULL;
        $party_state_id = !empty($_POST["party_state"]) ? escape_data(url_decryption($_POST["party_state"])) : NULL;
        $party_district_id = !empty($_POST["party_district"]) ?  escape_data(url_decryption($_POST["party_district"])) : NULL;
        $party_pincode = escape_data($_POST["party_pincode"]);
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
            'is_dead_minor'=>$is_dead_minor,
            'is_dead_file_status'=>$is_dead_file_status
        );
        $party_type = array(
            'is_org' => $is_org,
            'party_type' => $party_as,
            'p_r_type' => $p_r_type,
            'm_a_type' => 'A',
            'party_no' => $party_max[0]['max_party_no']+1,            
            'party_id' => $party_max[0]['max_party_id']+1
        );
        $party_details = array_merge($party_type, $party_individual, $party_organisation, $party_address_details);
        if (isset($registration_id) && !empty($registration_id) && isset($party_id) && !empty($party_id)) {
            $party_update_data = array(
                'updated_on' => $curr_dt_time,
                'updated_by' => $this->session->userdata['login']['id'],
                'updated_by_ip' => $_SERVER['REMOTE_ADDR']
            );
            $party_details = array_merge($party_details, $party_update_data);
            //UPDATE EXTRA PARTY DETAILS
            $status = $this->NewCaseModel->add_update_case_parties($registration_id, $party_details, NEW_CASE_EXTRA_PARTY, $party_id);
            if ($status) {
                reset_affirmation($registration_id);
                echo '2@@@' . htmlentities('Party details updated successfully !', ENT_QUOTES) . '@@@' . base_url('newcase/defaultController/' . url_encryption(trim($registration_id . '#' . E_FILING_TYPE_NEW_CASE . '#' . $_SESSION['efiling_details']['stage_id'])));
            } else{
                echo '1@@@' . htmlentities('Some error occurred ! Please Try again.', ENT_QUOTES);
            }
        } else{
            $party_create_data = array(
                'registration_id' => $registration_id,
                'created_on' => $curr_dt_time,
                'created_by' => $this->session->userdata['login']['id'],
                'created_by_ip' => $_SERVER['REMOTE_ADDR']
            );
            $party_details = array_merge($party_details, $party_create_data);
            $all_party_no = get_extra_party_P_or_R($p_r_type);
            $breadcrumb_statusGet = explode(',', $_SESSION['efiling_details']['breadcrumb_status']);
            $breadcrumb_status=count($breadcrumb_statusGet); $step=11;
            if ($step >= $breadcrumb_status) {
                if ($p_r_type == 'P') {
                    $extra_party_check_current_case_details = ($_SESSION['efiling_details']['no_of_petitioners']);
                    //if ($extra_party_check_current_case_details > $all_party_no) {
                    if ($extra_party_check_current_case_details > $all_party_no || $extra_party_check_current_case_details != $all_party_no) {
                        $inserted_party_id = $this->NewCaseModel->add_update_case_parties($registration_id, $party_details, NEW_CASE_EXTRA_PARTY);
                    } else{
                        echo '1@@@' . htmlentities('Your limit of number of ' . $extra_party_check_current_case_details . ' petitioner(s) already exhausted, if you want to add more petitioner(s) first update number of petitioners in case details tab.', ENT_QUOTES);
                    }
                } elseif ($p_r_type == 'R') {
                    $extra_party_check_current_case_details = ($_SESSION['efiling_details']['no_of_respondents']);
                    //if ($extra_party_check_current_case_details > $all_party_no) {
                    if ($extra_party_check_current_case_details > $all_party_no || $extra_party_check_current_case_details != $all_party_no) {
                        $inserted_party_id = $this->NewCaseModel->add_update_case_parties($registration_id, $party_details, NEW_CASE_EXTRA_PARTY);
                    } else{
                        echo '1@@@' . htmlentities('Your limit of number of ' . $extra_party_check_current_case_details . ' respondents(s) already exhausted, if you want to add more respondent(s) first update number of respondent in case details tab.', ENT_QUOTES);
                    }
                }
            }
            if ($inserted_party_id) {
                echo '2@@@' . htmlentities('Party details added successfully !', ENT_QUOTES) . '@@@' . base_url('newcase/defaultController/' . url_encryption(trim($registration_id . '#' . E_FILING_TYPE_NEW_CASE . '#' . Draft_Stage)));
            } else{
                echo '1@@@' . htmlentities('Some error occurred ! Please Try again.', ENT_QUOTES);
            }
        }
    }

}