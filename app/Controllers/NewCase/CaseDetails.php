<?php

namespace App\Controllers\Newcase;

use App\Controllers\BaseController;
use App\Models\Clerk\ClerkModel;
use App\Models\Common\CommonModel;
use App\Models\Department\DepartmentModel;
use App\Models\DocumentIndex\DocumentIndexModel;
use App\Models\NewCase\CourtFeeModel;
use App\Models\NewCase\DropdownListModel;
use App\Models\NewCase\GetDetailsModel;
use App\Models\NewCase\NewCaseModel;
use App\Models\ShcilPayment\PaymentModel;
use App\Models\UploadDocuments\UploadDocsModel;
use DateTime;

class CaseDetails extends BaseController
{

    protected $Common_model;
    protected $New_case_model;
    protected $Dropdown_list_model;
    protected $Get_details_model;
    protected $UploadDocs_model;
    protected $DocumentIndex_model;
    protected $DepartmentModel;
    protected $Clerk_model;
    protected $Court_Fee_model;
    protected $Payment_model;
    protected $request;
    protected $validation;

    public function __construct()
    {
        parent::__construct();
        if(empty(getSessionData('login'))){
            return response()->redirect(base_url('/')); 
        } else{
            is_user_status();
        }
        $this->Common_model = new CommonModel();
        $this->New_case_model = new NewCaseModel();
        $this->Dropdown_list_model = new DropdownListModel();
        $this->Get_details_model = new GetDetailsModel();
        $this->UploadDocs_model = new UploadDocsModel();
        $this->DocumentIndex_model = new DocumentIndexModel();
        $this->DepartmentModel = new DepartmentModel();
        $this->Clerk_model = new ClerkModel();
        $this->Court_Fee_model = new CourtFeeModel();
        $this->Payment_model = new PaymentModel();
        $this->request = \Config\Services::request();
        $this->validation = \Config\Services::validation();
    }

    public function index()
    {
        $data = array();
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_DEPARTMENT);
        if (!in_array(getSessionData('login')['ref_m_usertype_id'], $allowed_users_array)) {
            return response()->redirect(base_url('adminDashboard'));
            exit(0);
        }
        // $stages_array = array('', Draft_Stage, Initial_Defected_Stage, I_B_Defected_Stage);
        $stages_array = array('', Draft_Stage, Initial_Defected_Stage, E_REJECTED_STAGE);
        if (getSessionData('efiling_details') != '' && !empty(getSessionData('efiling_details')['stage_id']) && !in_array(getSessionData('efiling_details')['stage_id'], $stages_array)) {
            return response()->redirect(base_url('newcase/view'));
            exit(0);
        }
        $data['sc_case_type'] = $this->Dropdown_list_model->get_sci_case_type();
        $data['main_subject_cat'] = $this->Dropdown_list_model->get_main_subject_category();
        $data['special_category'] = $this->Dropdown_list_model->get_special_category();
        if (getSessionData('login')['ref_m_usertype_id'] == USER_DEPARTMENT && !is_null(getSessionData('login')['department_id'])) {
            $data['department_aor'] = $this->DepartmentModel->get_department_aor(getSessionData('login')['department_id']);
        }
        if (getSessionData('login')['ref_m_usertype_id'] == USER_CLERK) {
            $data['clerk_aor'] = $this->Clerk_model->get_clerk_aor(getSessionData('login')['id']);
        }
        if (getSessionData('login')['ref_m_usertype_id'] == USER_ADVOCATE && !is_null(getSessionData('login')['aor_code'])) {
            $data['aor_department'] = $this->DepartmentModel->get_aor_department(getSessionData('login')['aor_code']);
        }
        $court_type = NULL;
        if (isset(getSessionData('efiling_details')['registration_id']) && !empty(getSessionData('efiling_details')['registration_id'])) {
            $registration_id = getSessionData('efiling_details')['registration_id'];
            $data['new_case_details'] = $this->Get_details_model->get_new_case_details($registration_id);
            if (getSessionData('login')['ref_m_usertype_id'] == USER_DEPARTMENT && !is_null(getSessionData('login')['department_id'])) {
                $data['selected_aor'] = $this->DepartmentModel->selected_aor_for_case($registration_id);
            }
            if (getSessionData('login')['ref_m_usertype_id'] == USER_ADVOCATE && !is_null(getSessionData('login')['aor_code'])) {
                $data['selected_department'] = $this->DepartmentModel->selected_aor_for_case($registration_id);
            }
            if (getSessionData('login')['ref_m_usertype_id'] == USER_CLERK) {
                $data['selected_aor'] = $this->Clerk_model->selected_aor_for_case($registration_id);
            }
            $court_type = (!empty($data['new_case_details'][0]['court_type']) && isset($data['new_case_details'][0]['court_type'])) ? (int)$data['new_case_details'][0]['court_type'] : NULL;
            $state_id = (!empty($data['new_case_details'][0]['state_id']) && isset($data['new_case_details'][0]['state_id'])) ? $data['new_case_details'][0]['state_id'] : NULL;
            $district_id = (!empty($data['new_case_details'][0]['district_id']) && isset($data['new_case_details'][0]['district_id'])) ? $data['new_case_details'][0]['district_id'] : NULL;
            $estab_code = (!empty($data['new_case_details'][0]['estab_code']) && isset($data['new_case_details'][0]['estab_code'])) ? $data['new_case_details'][0]['estab_code'] : NULL;
            $estab_id = (!empty($data['new_case_details'][0]['estab_id']) && isset($data['new_case_details'][0]['estab_id'])) ? $data['new_case_details'][0]['estab_id'] : NULL;
            $supreme_court_state = (!empty($data['new_case_details'][0]['supreme_court_state']) && isset($data['new_case_details'][0]['supreme_court_state'])) ? $data['new_case_details'][0]['supreme_court_state'] : NULL;
            $supreme_court_bench = (!empty($data['new_case_details'][0]['supreme_court_bench']) && isset($data['new_case_details'][0]['supreme_court_bench'])) ? $data['new_case_details'][0]['supreme_court_bench'] : NULL;
            $data['high_court_drop_down'] = [];
            if (isset($court_type) && !empty($court_type)) {
                switch ($court_type) {
                    case 1:
                        $high_courts = $this->Dropdown_list_model->high_courts();
                        $data['high_court_drop_down'] = $high_courts;
                        $params = array();
                        $params['type'] = 2;
                        $params['hc_id'] = (int)$estab_id;
                        $data['high_court_bench'] = $this->Common_model->getHighCourtData($params);
                        break;
                    case 3:
                        $state_id = !empty($data['new_case_details'][0]['state_id']) ? (int)$data['new_case_details'][0]['state_id'] : NULL;
                        $params = array('type' => 1);
                        $state_list = $this->Common_model->getSubordinateCourtData($params);
                        $data['state_list'] = $state_list;
                        $params = array('type' => 2, 'state_code' => $state_id);
                        $district_list = $this->Common_model->getSubordinateCourtData($params);
                        $data['district_list'] = $district_list;
                        break;
                    case 4:
                        $supreme_court_stateArr = array();
                        $supreme_court_stateArr[] = array('id' => 490506, 'name' => 'DELHI');
                        $data['supreme_court_state'] = $supreme_court_stateArr;
                        $supreme_court_benchArr = array();
                        $supreme_court_benchArr[] = array('id' => '10000', 'name' => 'DELHI');
                        $data['supreme_court_bench'] = $supreme_court_benchArr;
                        break;
                    case 5:
                        $state_agency_list = $this->Dropdown_list_model->get_states_list();
                        $data['state_agency_list'] = $state_agency_list;
                        $state_id = !empty($data['new_case_details'][0]['state_id']) ? (int)$data['new_case_details'][0]['state_id'] : NULL;
                        $court_type = 5;
                        $agencies = $this->Dropdown_list_model->icmis_state_agencies($state_id, $court_type);
                        $data['agencies'] = $agencies;
                        break;
                    default:
                }
            }
            $this->Get_details_model->get_case_table_ids($registration_id);
            $uploaded_docs = $this->UploadDocs_model->get_uploaded_pdfs($registration_id);
            if (!empty($uploaded_docs)) {
                $data['uploaded_docs'] = $uploaded_docs;
            } else{
                $data['uploaded_docs'] = '';
            }
            if($_SESSION['login']['ref_m_usertype_id'] == USER_CLERK) {
                $data['selected_aor'] = $this->Clerk_model->selected_aor_for_case($registration_id);
            }
        } else{
            $high_courts = $this->Dropdown_list_model->high_courts();
            $data['high_court_drop_down'] = $high_courts;
            // start case with AI
            // pr($_SESSION['casewithAI'][0]);
            // $_SESSION['casewithAI'] = getSessionData('casewithAI')['casewithAI'];
            if (isset($_SESSION['casewithAI']) && !empty($_SESSION['casewithAI'][0])) {
                $court_type_api=isset($_SESSION['casewithAI'][0]) ? $_SESSION['casewithAI'][0]['case_details']['court_type']:null;
                if (!empty($court_type_api)) {
                    $court_type_api = getCourtTypeAndState($court_type_api);
                    if (!empty($court_type_api)) {
                        $court_type = $court_type_api['court_type'];
                        $estab_id= $court_type_api['hc_id'];
                        if (isset($court_type) && !empty($court_type)) {
                            switch ($court_type) {
                                case 1:
                                    $high_courts = $this->Dropdown_list_model->high_courts();
                                    $data['high_court_drop_down'] = $high_courts;
                                    $params = array();
                                    $params['type'] = 2;
                                    $params['hc_id'] = (int)$estab_id;
                                    $data['high_court_bench'] = $this->Common_model->getHighCourtData($params);
                                    break;
                                case 4:
                                    $supreme_court_stateArr = array();
                                    $supreme_court_stateArr[] = array('id' => 490506, 'name' => 'DELHI');
                                    $data['supreme_court_state'] = $supreme_court_stateArr;
                                    $supreme_court_benchArr = array();
                                    $supreme_court_benchArr[] = array('id' => '10000', 'name' => 'DELHI');
                                    $data['supreme_court_bench'] = $supreme_court_benchArr;
                                    break;
                                default:
                            }
                        }
                    }
                }
                $pet_name_causetitle=isset($_SESSION['casewithAI'][0]) ? $_SESSION['casewithAI'][0]['case_details']['pet_name_causetitle'][0]:null;
                $res_name_causetitle=isset($_SESSION['casewithAI'][0]) ? $_SESSION['casewithAI'][0]['case_details']['res_name_causetitle'][0]:null;
                $response_court_type=isset($_SESSION['casewithAI'][0]) ? $_SESSION['casewithAI'][0]['case_details']['court_type']:null;
                $sc_sp_case_type_id=isset($_SESSION['casewithAI'][0]['case_details']['sc_sp_case_type_id']) ? $_SESSION['casewithAI'][0]['case_details']['sc_sp_case_type_id']:null;
                if (!empty($sc_sp_case_type_id)) {
                    if (strtoupper($sc_sp_case_type_id)=='NONE') {
                        $sc_sp_case_type_id=1;
                    } elseif (strtoupper($sc_sp_case_type_id)=='JAIL PETITION') {
                        $sc_sp_case_type_id=6;
                    } elseif (strtoupper($sc_sp_case_type_id)=='PUD') {
                        $sc_sp_case_type_id=7;
                    }
                }
                $jail_signature_date=isset($_SESSION['casewithAI'][0]['case_details']['jail_signature_date']) ? $_SESSION['casewithAI'][0]['case_details']['jail_signature_date']:'';
                if (!empty($jail_signature_date)) {
                    $jail_signature_date=date('Y-m-d',strtotime($jail_signature_date));
                }
                $cause_title='';
                if (!empty($pet_name_causetitle) && !empty($pet_name_causetitle)) {
                    $cause_title=$pet_name_causetitle.' Vs. '.$res_name_causetitle;
                }
                // $this->load->model('AIAssisted/Common_casewithAI_model');
                $sc_case_type_id=isset($_SESSION['casewithAI'][0]['sc_case_type']) && !empty($_SESSION['casewithAI'][0]['sc_case_type'])? $_SESSION['casewithAI'][0]['sc_case_type']:0;
                $data['new_case_details'][] = [
                    'cause_title' => $cause_title,
                    'no_of_petitioners' => isset($_SESSION['casewithAI'][0]['case_details']['pno']) ? $_SESSION['casewithAI'][0]['case_details']['pno']:0,
                    'no_of_respondents' => isset($_SESSION['casewithAI'][0]['case_details']['pno']) ? $_SESSION['casewithAI'][0]['case_details']['rno']:0,
                    'sc_sp_case_type_id' => $sc_sp_case_type_id,
                    'jail_signature_date' =>$jail_signature_date,
                    'estab_id' => $estab_id,
                    'court_type' => isset($court_type) && !empty($court_type) ? $court_type:null,
                    'sc_case_type_id' => $sc_case_type_id,
                ];
            }
            /*end code here json api*/
        }
        if($_SESSION['login']['ref_m_usertype_id'] == USER_CLERK ){
            $data['clerk_aor'] = $this->Clerk_model->get_clerk_aor($_SESSION['login']['id']);
        }
        $this->render('newcase.new_case_view', $data);
    }

    public function getSubSubjectCategory() {
        $subj_sub_cat_1 = explode('##', url_decryption(escape_data($this->request->getPost("main_category"))));
        var_dump($subj_sub_cat_1);
    }

    public function add_case_details() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_DEPARTMENT);
        if (!in_array(getSessionData('login')['ref_m_usertype_id'], $allowed_users_array)) {
            return redirect()->to(base_url('adminDashboard'));
            exit(0);
        }
        $validations = [];
        $stages_array = array('', Draft_Stage, Initial_Defected_Stage);
        if (!empty(getSessionData('efiling_details')['stage_id']) && !in_array(getSessionData('efiling_details')['stage_id'], $stages_array)) {
            echo '1@@@' . htmlentities("Update in Petitioner details can be done only at 'Draft', 'For Compliance' stages'", ENT_QUOTES);
            exit(0);
        }
        /*start upload duc*/
        $registration_id = (!empty(getSessionData('efiling_details')['registration_id']) && getSessionData('efiling_details')['registration_id']) != '' ? getSessionData('efiling_details')['registration_id'] : '';
        $court_fee_calculation_helper_flag = '';
        $matrimonialCheck = $this->request->getPost("matrimonialCheck");
        $matrimonial = $this->request->getPost("matrimonial");
        if (!empty($matrimonialCheck) && $matrimonialCheck == 'matrimonialCheck' && empty($matrimonial)) {
            $validations = [
                "matrimonial" => [
                    "label" => "Matrimonial Type",
                    "rules" => "required"
                ],
            ];
        } elseif (!empty($matrimonialCheck) && $matrimonialCheck == 'matrimonialCheck' && !empty($matrimonial)) {
            $court_fee_calculation_helper_flag = $matrimonial;
        }
        $no_of_petitioners = $this->request->getPost("no_of_petitioners");
        $no_of_respondents = $this->request->getPost("no_of_respondents");
        if ($no_of_petitioners == 0) {
            echo '1@@@' . htmlentities('Number of Petitioner(s) should be greater than 0.');
            exit();
        }
        if ($no_of_respondents == 0) {
            echo '1@@@' . htmlentities('Number of Respondent(s) should be greater than 0.');
            exit();
        }
        $validations += [
            "no_of_petitioners" => [
                "label" => "No of Petitioner",
                "rules" => "trim|min_length[1]|numeric"
            ],
            "no_of_respondents" => [
                "label" => "No of Respondents",
                "rules" => "trim|min_length[1]|numeric"
            ],
            "cause_pet" => [
                "label" => "Cause Title Petitioner",
                "rules" => "required|trim|min_length[3]|max_length[99]|validateAlphaNumericSingleDoubleQuotesBracketWithSpecialCharacters",
            ],
            "cause_res" => [
                "label" => "Cause Title Respondent",
                "rules" => "required|trim|min_length[3]|max_length[99]|validateAlphaNumericSingleDoubleQuotesBracketWithSpecialCharacters",
            ],
            "sc_case_type" => [
                "label" => "Case Type",
                "rules" => "required|trim|validate_encrypted_value"
            ],
            "sc_sp_case_type_id" => [
                "label" => "Special Case Type",
                "rules" => "required|trim|in_list[1,6,7]"
            ],
            "subj_cat_main" => [
                "label" => "Main Category",
                "rules" => "required|trim|validate_encrypted_value"
            ],
            "subj_sub_cat_1" => [
                "label" => "Sub Category 1",
                "rules" => "required|trim|validate_encrypted_value"
            ],
        ];      
        if (in_array(getSessionData('login')['ref_m_usertype_id'], [USER_DEPARTMENT, USER_CLERK])) {
            $validations += [
                "impersonated_aor" => [
                    "label" => "AOR",
                    "rules" => "trim"
                ],
            ];
        }        
        $court_type = !empty($_POST['court_name']) ? (int)$_POST['court_name'] : NULL;
        if (isset($court_type) && !empty($court_type)) {
            switch ($court_type) {
                case 1:
                    $validations += [
                        "high_courtname" => [
                            "label" => "High court name",
                            "rules" => "required"
                        ],
                        "high_court_bench_name" => [
                            "label" => "High court bench name",
                            "rules" => "required"
                        ],
                    ];
                    break;
                case 3:
                    $validations += [
                        "district_court_state_name" => [
                            "label" => "State name",
                            "rules" => "required|trim"
                        ],
                        "district_court_district_name" => [
                            "label" => "District name",
                            "rules" => "required"
                        ],
                    ];
                    break;
                case 4:
                    $validations += [
                        "supreme_state_name" => [
                            "label" => "State name",
                            "rules" => "required"
                        ],
                        "supreme_bench_name" => [
                            "label" => "Bench name",
                            "rules" => "required"
                        ],
                    ];
                    break;
                case 5:
                    $validations += [
                        "state_agency" => [
                            "label" => "State name",
                            "rules" => "required"
                        ],
                        "state_agency_name" => [
                            "label" => "Agency name",
                            "rules" => "required"
                        ],
                    ];
                    break;
                default:
            }
        }
        $this->validation->setRules($validations);      
        // $this->validation->setErrorDelimiter('<br/>', '');
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
            // echo $this->validation->getError('no_of_petitioner') . $this->validation->getError('no_of_respondent') . $this->validation->getError('cause_pet') . $this->validation->getError('cause_res') . $this->validation->getError('sc_case_type') . $this->validation->getError('sc_sp_case_type_id') .
            //     $this->validation->getError('subj_cat_main') . $this->validation->getError('subj_sub_cat_1') . $this->validation->getError('subj_sub_cat_2') . $this->validation->getError('subj_sub_cat_3') .
            //     $this->validation->getError('question_of_law') . $this->validation->getError('grounds') . $this->validation->getError('interim_grounds') . $this->validation->getError('main_prayer') . $this->validation->getError('interim_relief') . $this->validation->getError('matrimonial');
            // if (isset($court_type) && !empty($court_type)) {
            //     switch ($court_type) {
            //         case 1:
            //             echo $this->validation->getError('high_courtname') . $this->validation->getError('high_court_bench_name');
            //             break;
            //         case 3:
            //             echo $this->validation->getError('district_court_state_name') . $this->validation->getError('district_court_district_name');
            //             break;
            //         case 4:
            //             echo $this->validation->getError('supreme_state_name') . $this->validation->getError('supreme_bench_name');
            //             break;
            //         case 5:
            //             echo $this->validation->getError('state_agency') . $this->validation->getError('state_agency_name');
            //             break;
            //         default:
            //     }
            // }
            // exit(0);
        } else{
            $cause_title = (isset($_POST['cause_pet']) && !empty($_POST['cause_pet'])) ? strtoupper($_POST['cause_pet']) . ' Vs. ' : '';
            $cause_title .= (isset($_POST['cause_pet']) && !empty($_POST['cause_pet'])) ? strtoupper(escape_data($_POST["cause_res"])) : '';
            $subj_cat_main = (isset($_POST['subj_cat_main']) && !empty($_POST['subj_cat_main'])) ? explode('##', url_decryption(escape_data($this->request->getPost("subj_cat_main")))) : '';
            $subject_cat = is_array($subj_cat_main) ? $subj_cat_main[0] : '';
            $subj_sub_cat_1 = (isset($_POST['subj_sub_cat_1']) && !empty($_POST['subj_sub_cat_1'])) ? explode('##', url_decryption(escape_data($this->request->getPost("subj_sub_cat_1")))) : '';
            $subject_cat = isset($subj_sub_cat_1[0]) && !empty($subj_sub_cat_1[0]) ? $subj_sub_cat_1[0] : $subject_cat;
            $subj_sub_cat_1[0] = isset($subj_sub_cat_1[0]) && !empty($subj_sub_cat_1[0]) ? $subj_sub_cat_1[0] : 0;
            $subj_sub_cat_2 = (isset($_POST['subj_sub_cat_2']) && !empty($_POST['subj_sub_cat_2'])) ? explode('##', url_decryption(escape_data($this->request->getPost("subj_sub_cat_2")))) : '';
            $subject_cat = (isset($subj_sub_cat_2[0]) && !empty($subj_sub_cat_2[0])) ? $subj_sub_cat_2[0] : $subject_cat;
            $subj_sub_cat_2[0] = (isset($subj_sub_cat_2[0]) && !empty($subj_sub_cat_2[0])) ? $subj_sub_cat_2[0] : 0;
            $subj_sub_cat_3 = (isset($_POST['subj_sub_cat_3']) && !empty($_POST['subj_sub_cat_3'])) ? explode('##', url_decryption(escape_data($this->request->getPost("subj_sub_cat_3")))) : '';
            $subject_cat = (isset($subj_sub_cat_3[0]) && !empty($subj_sub_cat_3[0])) ? $subj_sub_cat_3[0] : $subject_cat;
            $subj_sub_cat_3[0] = (isset($subj_sub_cat_3[0]) && !empty($subj_sub_cat_3[0])) ? $subj_sub_cat_3[0] : 0;
            $sc_case_type = (isset($_POST['sc_case_type']) && !empty($_POST['sc_case_type'])) ? explode('##', url_decryption(escape_data($this->request->getPost("sc_case_type")))) : '';
            $sc_case_type[0] = (isset($sc_case_type[0]) && !empty($sc_case_type[0])) ? $sc_case_type[0] : 0;
            $question_of_law = !empty($this->request->getPost("question_of_law")) ? strtoupper(escape_data($this->request->getPost("question_of_law"))) : NULL;
            $grounds = !empty($this->request->getPost("grounds")) ? strtoupper(escape_data($this->request->getPost("grounds"))) : NULL;
            $interim_grounds = !empty($this->request->getPost("interim_grounds")) ? strtoupper(escape_data($this->request->getPost("interim_grounds"))) : NULL;
            $main_prayer = !empty($this->request->getPost("main_prayer")) ? strtoupper(escape_data($this->request->getPost("main_prayer"))) : NULL;
            $interim_relief = !empty($this->request->getPost("interim_relief")) ? strtoupper(escape_data($this->request->getPost("interim_relief"))) : NULL;
            $registration_id = (isset(getSessionData('efiling_details')['registration_id']) && !empty(getSessionData('efiling_details')['registration_id'])) ? getSessionData('efiling_details')['registration_id'] : '';
            $curr_dt_time = date('Y-m-d H:i:s');
            $state_id = NULL;
            $district_id = NULL;
            $agency_code = NULL;
            $estab_code = NULL;
            $estab_id = NULL;
            $supreme_court_state = NULL;
            $superem_court_bench = NULL;
            if (!empty($_POST["high_courtname"]) && $court_type == 1) {
                $high_courtname = explode('##', url_decryption(escape_data($_POST["high_courtname"])));
                $estab_id = !empty($high_courtname[0]) ? $high_courtname[0] : NULL;
            }
            if (!empty($_POST["high_court_bench_name"]) && $court_type == 1) {
                $high_court_bench_name = explode('##', url_decryption(escape_data($_POST["high_court_bench_name"])));
                $estab_code = !empty($high_court_bench_name[2]) ? $high_court_bench_name[2] : NULL;
                $_POST['state_agency_name'] = '';
            }
            if (!empty($_POST["district_court_state_name"]) && $court_type == 3) {
                $district_court_state_name = explode('#$', url_decryption(escape_data($_POST["district_court_state_name"])));
                $state_id = !empty($district_court_state_name[0]) ? $district_court_state_name[0] : NULL;
            }
            if (!empty($_POST["district_court_district_name"]) && $court_type == 3) {
                $district_court_district_name = explode('#$', url_decryption(escape_data($_POST["district_court_district_name"])));
                $district_id = !empty($district_court_district_name[0]) ? $district_court_district_name[0] : NULL;
            }
            if (!empty($_POST["supreme_state_name"]) && $court_type == 4) {
                $supreme_court_state = !empty($_POST["supreme_state_name"]) ? $_POST["supreme_state_name"] : NULL;
            }
            if (!empty($_POST["supreme_bench_name"]) && $court_type == 4) {
                $superem_court_bench = !empty($_POST["supreme_bench_name"]) ? $_POST["supreme_bench_name"] : NULL;
            }
            if (!empty($_POST["state_agency"]) && $court_type == 5) {
                $state_agency = explode('#$', url_decryption(escape_data($_POST["state_agency"])));
                $state_id = !empty($state_agency[0]) ? $state_agency[0] : NULL;
                $agency_code = 1;
            }
            if (!empty($_POST["state_agency_name"]) && $court_type == 5) {
                $state_agency_name = explode('##', url_decryption(escape_data($_POST["state_agency_name"])));
                $estab_id =  !empty($state_agency_name[0]) ? $state_agency_name[0] : NULL;
            }
            if ($_POST["datesignjail"] == '') {
                $jailsignDt = null;
            } else{
                $jailsignDt = escape_data($_POST["datesignjail"]);
            }
            $if_sclsc= isset($_POST["if_sclsc"]) ? trim($_POST["if_sclsc"]) : 0;
            $if_sclsc =  !empty($if_sclsc) ? 1 : 0;
            $sclsc_amr_no   = (isset($_POST['sclsc_amr_no']) && !empty($_POST['sclsc_amr_no']) && $if_sclsc==1) ? $_POST['sclsc_amr_no'] : NULL;
            $sclsc_amr_year = (isset($_POST['sclsc_amr_year']) && !empty($_POST['sclsc_amr_year']) && $if_sclsc==1) ? $_POST['sclsc_amr_year'] : NULL;
            if($if_sclsc==1){
                if (empty($sclsc_amr_no)){
                    echo '1@@@SCLSC ANR No  field is required.'; exit();
                }
                if (empty($sclsc_amr_year)){
                    echo '1@@@SCLSC ANR Year field is required.'; exit();
                }
            }
            $is_govt_filing = (isset($_POST["is_govt_filing"]) && trim($_POST["is_govt_filing"]) != '') ? 1 : 0;
            if (in_array($_SESSION['login']['ref_m_usertype_id'], [USER_CLERK])) {
                if (isset($_POST['impersonated_aor']) && !empty($_POST['impersonated_aor'])){
                    $impersonated_aor = explode('@@@', escape_data($this->request->getPost("impersonated_aor")));
                    if (!empty($impersonated_aor)){
                        $_SESSION['login']['aor_code'] = $impersonated_aor[0];
                    }
                } else{
                    echo '1@@@AOR field is required.'; exit();
                }
            }
            //check Whether filed by Government aor or not
            if (isset($_SESSION['login']['aor_code']) && !empty($_SESSION['login']['aor_code'])) {
                if (is_AORGovernment($_SESSION['login']['aor_code'])) {
                    $is_govt_filing = $is_govt_filing;
                } else{
                    $is_govt_filing =0;
                }
            }
            $special_category = !empty($_POST["special_category"]) ? url_decryption(escape_data($_POST["special_category"])) : 0;
            if(isset($jailsignDt) && !empty($jailsignDt)){ 
                $jailsignDt = DateTime::createFromFormat('d/m/Y', $jailsignDt);
                $jailsignDt = $jailsignDt->format('Y-m-d'); // Convert to Y-m-d format
            }
            // if($_POST["sc_sp_case_type_id"] != 6 || $_POST["sc_sp_case_type_id"] != '6'){
            // $jailsignDt = null;
            // }            
            $case_details = array(
                'cause_title' => $cause_title,
                'sc_case_type_id' => !empty($sc_case_type[0]) ? $sc_case_type[0] : null,
                'sc_sp_case_type_id' => escape_data($_POST["sc_sp_case_type_id"]),
                'subj_main_cat' => !empty($subj_cat_main[0]) ? $subj_cat_main[0] : null,
                'subj_sub_cat_1' => $subj_sub_cat_1[0] ? $subj_sub_cat_1[0] : null,
                'subj_sub_cat_2' => $subj_sub_cat_2[0] ? $subj_sub_cat_2[0] : null,
                'subj_sub_cat_3' => $subj_sub_cat_3[0] ? $subj_sub_cat_3[0] : null,
                'subject_cat' => $subject_cat ? $subject_cat : null,
                'question_of_law' => $question_of_law ? $question_of_law : null,
                'grounds' => $grounds ? $grounds : null,
                'grounds_interim' => $interim_grounds ? $interim_grounds : null,
                'main_prayer' => $main_prayer ? $main_prayer : null,
                'interim_relief' => $interim_relief ? $interim_relief : null,
                'court_fee_calculation_helper_flag' => $court_fee_calculation_helper_flag ? $court_fee_calculation_helper_flag : null,
                'if_sclsc' => $if_sclsc ? $if_sclsc : null,
                'is_govt_filing' => $is_govt_filing ? $is_govt_filing : null,
                'no_of_petitioners' => $no_of_petitioners ? $no_of_petitioners : null,
                'no_of_respondents' => $no_of_respondents ? $no_of_respondents : null,
                'keywords' => NULL,
                'state_id' => $state_id ? $state_id : null,
                'district_id' => $district_id ? $district_id : null,
                'agency_code' => $agency_code ? $agency_code : null,
                'estab_code' => $estab_code ? $estab_code : null,
                'estab_id' => $estab_id ? $estab_id : null,
                'supreme_court_state' => $supreme_court_state ? $supreme_court_state : null,
                'supreme_court_bench' => $superem_court_bench ? $superem_court_bench : null,
                'court_type' => $court_type ? $court_type : null,
                'jail_signature_date' => $jailsignDt ? (escape_data($_POST["sc_sp_case_type_id"]) == 6 ? $jailsignDt : null) : null,
                'special_category' => $special_category ? $special_category : null,
                'sclsc_amr_no'=>$sclsc_amr_no,
                'sclsc_amr_year'=>$sclsc_amr_year,
                
            );
            if (isset($_SESSION['impersonated_department']))
                unset($_SESSION['impersonated_department']);
            if (in_array(getSessionData('login')['ref_m_usertype_id'], [USER_ADVOCATE]) && isset($_POST['impersonated_department']) && !empty($_POST['impersonated_department'])) {
                $_SESSION['impersonated_department'] = $_POST['impersonated_department'];
            }
            if (isset($registration_id) && !empty($registration_id) && isset($_SESSION['case_table_ids']['case_details_id']) && !empty($_SESSION['case_table_ids']['case_details_id'])) {
                if (in_array($_SESSION['login']['ref_m_usertype_id'], [USER_CLERK])) {
                    $aorData = getAordetailsByAORCODE($_SESSION['login']['aor_code']);
                    $created_by=!empty($aorData) ? $aorData[0]->id  : null;
                    $adv_sci_bar_id=!empty($aorData) ? $aorData[0]->adv_sci_bar_id  : null;
                    $_SESSION['login']['adv_sci_bar_id'] = $adv_sci_bar_id;
                    $case_details_update_data = array(
                        'created_by' => $created_by,
                        'updated_on' => $curr_dt_time,
                        'updated_by' => getSessionData('login')['id'],
                        'updated_by_ip' => $_SERVER['REMOTE_ADDR']
                    );
                    $adv_details = array('adv_bar_id' => $adv_sci_bar_id,'adv_code' => $_SESSION['login']['aor_code']);
                    $this->db->table('efil.tbl_case_advocates');
                    $this->db->WHERE('registration_id', $registration_id);
                    $this->db->WHERE('is_active', FALSE);
                    $this->db->SET($adv_details);
                    if($this->db->UPDATE()){
                        $this->Common_model->update_efiling_nums($registration_id,$case_details_update_data);
                    }
                } else{
                    $case_details_update_data = array(
                        'updated_on' => $curr_dt_time,
                        'updated_by' => getSessionData('login')['id'],
                        'updated_by_ip' => $_SERVER['REMOTE_ADDR']
                    );
                }
                $case_details = array_merge($case_details, $case_details_update_data);
                //UPDATE CASE DETAILS
                $p_r_type_petitioners = 'P';
                $p_r_type_respondents = 'R';
                $step = 11;
                $breadcrumb_statusGet = (isset(getSessionData('efiling_details')['breadcrumb_status']) && !empty(getSessionData('efiling_details')['breadcrumb_status'])) ? explode(',', getSessionData('efiling_details')['breadcrumb_status']) : '';
                $breadcrumb_status = $breadcrumb_statusGet != '' ? count($breadcrumb_statusGet) : 0;
                $total_petitioners = get_extra_party_P_or_R($p_r_type_petitioners);
                $total_respondents = get_extra_party_P_or_R($p_r_type_respondents);
                if ($step >= $breadcrumb_status) {
                    if ($total_petitioners > $no_of_petitioners) {
                        echo '1@@@' . htmlentities('You have already entered ' . $total_petitioners . ' petitioners(s),please delete petitioners to change no of petitioners in case details', ENT_QUOTES);
                        exit();
                    }
                    if ($total_respondents > $no_of_respondents) {
                        echo '1@@@' . htmlentities('You have already entered ' . $total_respondents . ' respondents(s),please delete respondents to change no of respondents in case details', ENT_QUOTES);
                        exit();
                    }
                }
                $status = $this->New_case_model->add_update_case_details($registration_id, $case_details, NEW_CASE_CASE_DETAIL, getSessionData('case_table_ids')['case_details_id']);
                if ($status) {
                    //SESSION efiling_details
                    $this->Common_model->get_efiling_num_basic_Details($registration_id);
                    reset_affirmation($registration_id);
                    //$registration_id = getSessionData('efiling_details')['registration_id'];
                    //todo change code when user change case type from criminal to civil
                    $pending_court_fee = getPendingCourtFee();
                    if (getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_CAVEAT) {
                        $breadcrumb_to_update = CAVEAT_BREAD_COURT_FEE;
                    } else{
                        $breadcrumb_to_update = NEW_CASE_COURT_FEE;
                    }
                    if ($pending_court_fee > 0) {
                        $update_courtfee_breadcrumb_status = $this->Payment_model->remove_breadcrumb($registration_id, $breadcrumb_to_update);
                    } else{
                        // $update_courtfee_breadcrumb_status = $this->Payment_model->update_breadcrumbs($registration_id, $breadcrumb_to_update);
                        $update_courtfee_breadcrumb_status = $this->Payment_model->update_breadcrumbs($registration_id, 1);
                    }
                    if (in_array($_SESSION['login']['ref_m_usertype_id'], [USER_CLERK]) && !is_null($_SESSION['login']['aor_code'])) {
                        $this->Clerk_model->clerk_filings_update($registration_id,$_SESSION['login']['aor_code']);
                    }
                    echo '2@@@' . htmlentities('Case details updated successfully!', ENT_QUOTES) . '@@@' . base_url('newcase/defaultController/' . url_encryption(trim($registration_id . '#' . E_FILING_TYPE_NEW_CASE . '#' . $_SESSION['efiling_details']['stage_id'])));
                } else{
                    echo '1@@@' . htmlentities('Some error ! Please Try again.', ENT_QUOTES);
                }
            } else{
                //GENERATE NEW EFILING NUM AND ADD NEW CASE DETAILS, CREATE DRAFT STAGE
                $result = $this->New_case_model->generate_efil_num_n_add_case_details($case_details);
                if ($result['registration_id']) {
                    //SESSION efiling_details
                    $this->Common_model->get_efiling_num_basic_Details($result['registration_id']);
                    $user_name = getSessionData('login')['first_name'] . ' ' . getSessionData('login')['last_name'];
                    $efiling_num = getSessionData('efiling_details')['efiling_no'];
                    $sentSMS = "Efiling No. " . efile_preview($efiling_num) . " generated from your efiling account & still pending for final submit. - Supreme Court of India";
                    $subject = "Efiling No. " . efile_preview($efiling_num) . " generated from your efiling account";
                    // send_mobile_sms(getSessionData('login')['mobile_number'], $sentSMS, SCISMS_Efiling_No_Generated);
                    // send_mail_msg(getSessionData('login')['emailid'], $subject, $sentSMS, $user_name);
                    // send_whatsapp_message($result['registration_id'], $efiling_num, "generated from your efiling account & still pending for final submit.");
                    /*start upload duc data*/
                    $registration_id = $result['registration_id'];
                    casewithAIUpdate($result['registration_id'],$_SESSION['efiling_details']['efiling_no']);
                    echo '2@@@' . htmlentities('Case details added successfully', ENT_QUOTES) . '@@@' . base_url('newcase/defaultController/' . url_encryption(trim($result['registration_id'] . '#' . E_FILING_TYPE_NEW_CASE . '#' . Draft_Stage)));
                } else{
                    echo '1@@@' . htmlentities('Some error ! Please Try again.', ENT_QUOTES);
                }
            }
        }
    }

    public function is_upload_pdf() {
        if ($msg = isValidPDF('pdfDocFile')) {
            echo '1@@@' . htmlentities($msg, ENT_QUOTES);
            exit(0);
        }
        $breadcrumb_step_no = NEW_CASE_UPLOAD_DOCUMENT;
        $doc_title = 'Draft Petition';
        // (A) ERROR - NO FILE UPLOADED
        if (!isset($_FILES["pdfDocFile"])) {
            // exit("No file uploaded");
            echo '1@@@' . htmlentities('Upload Draft Petition file is required', ENT_QUOTES);
            exit(0);
        }
        // (B) FLAGS & "SETTINGS"
        // (B1) ACCEPTED & UPLOADED MIME-TYPES
        $accept = ["application/pdf", "image/png"];
        $upmime = strtolower($_FILES["pdfDocFile"]["type"]);
        // (B2) SOURCE & DESTINATION
        $source = $_FILES["pdfDocFile"]["tmp_name"];
        $destination = $_FILES["pdfDocFile"]["name"];
        // (C) SAVE UPLOAD ONLY IF ACCEPTED FILE TYPE
        if (in_array($upmime, $accept)) {
            echo '2@@@' . htmlentities('Document ready to upload', ENT_QUOTES);
            exit(0);
        } else {
            echo '1@@@' . htmlentities('Only PDF are allowed', ENT_QUOTES);
            exit(0);
            // echo "$upmime NOT ACCEPTED";
        }
        echo '2@@@' . htmlentities('Document uploaded successfully', ENT_QUOTES);
        exit(0);
    }


    public function efiling_declaration(){
        if(!empty(getSessionData('efiling_details'))){
                $data['crnt_dt'] = date('d/m/Y');
                $data['new_case_details'] = $this->Get_details_model->get_new_case_details(getSessionData('efiling_details')['registration_id']);
                $data['get_declaration_question'] = $this->New_case_model->get_efiling_declaration_question();
                $data['check_declaration_answer'] = $this->New_case_model->check_efiling_declaration_answer(getSessionData('efiling_details')['registration_id']);
                return $this->render('newcase.efile_declaration_view', $data);
        }else{
            return response()->redirect(base_url('adminDashboard'));
            exit(0);
        }
    }


    public function saved_efiling_declaration(){
       $registration_id = getSessionData('efiling_details')['registration_id'];
       $question_id =  $this->request->getPost('question_id');
       $question_no =  $this->request->getPost('question_no');
       $consent =  $this->request->getPost('consent');
       $status =  $this->New_case_model->saved_efiling_declaration_question_answer($registration_id, $question_id, $question_no, $consent);
       if($status){
            $this->session->setFlashdata('message', "<div class='alert alert-success fade in' style='text-align: center;'>Successfully Saved</div>");
            return redirect()->to(base_url('newcase/efiling_declaration'));
       }else{
             $this->session->setFlashdata('message', "<div class='alert alert-danger fade in' style='text-align: center;'>Something Wrong..!!</div>");
            return redirect()->to(base_url('newcase/efiling_declaration'));
       }

    }

}