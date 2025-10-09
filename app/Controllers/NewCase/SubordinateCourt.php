<?php
namespace App\Controllers\Newcase;

use App\Controllers\BaseController;
use App\Libraries\webservices\Efiling_webservices;
use App\Libraries\webservices\Highcourt_webservices;
use App\Models\Common\CommonModel;
use App\Models\DocumentIndex\DocumentIndexSelectModel;
use App\Models\NewCase\DeleteSubordinateCourtModel;
use App\Models\NewCase\DropdownListModel;
use App\Models\NewCase\GetDetailsModel;
use App\Models\NewCase\NewCaseModel;
use DateTime;
use CodeIgniter\CLI\CLI;

class SubordinateCourt extends BaseController {

    protected $Dropdown_list_model;
    protected $Get_details_model;
    protected $efiling_webservices;
    protected $DocumentIndex_Select_model;
    protected $common_model;
    protected $New_case_model;
    protected $highcourt_webservices;
    protected $DeleteSubordinateCourt_model;

    public function __construct() {
        parent::__construct();

        $this->Dropdown_list_model = new DropdownListModel();
        $this->common_model = new CommonModel();
        $this->New_case_model = new NewCaseModel();
        $this->Get_details_model = new GetDetailsModel();
        $this->DocumentIndex_Select_model = new DocumentIndexSelectModel();
        $this->efiling_webservices = new Efiling_webservices();
        $this->highcourt_webservices = new Highcourt_webservices();
        $this->DeleteSubordinateCourt_model = new DeleteSubordinateCourtModel();
    }

    public function index() {
        $sc_casetype = 0;
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_DEPARTMENT);

        if (getSessionData('login') != '' && !in_array(getSessionData('login')['ref_m_usertype_id'], $allowed_users_array)) {
            return redirect()->to(base_url('admindashboard'));
        }
        if (check_party() !=true) {  //func added on 15 JUN 2021
            log_message('info', "Please enter every party details before moving to further tabs.");
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center">Please enter every party details before moving to further tabs.</div>');
            return redirect()->to(base_url('newcase/extra_party'));
        }
        //$stages_array = array('', Draft_Stage, Initial_Defected_Stage, I_B_Defected_Stage);
        $stages_array = array('', Draft_Stage, Initial_Defected_Stage, E_REJECTED_STAGE);
        if (!empty(getSessionData('efiling_details')) && !in_array(getSessionData('efiling_details')['stage_id'], $stages_array)) {
            return redirect()->to(base_url('newcase/view'));
        }

        $data['sc_case_type'] = $this->Dropdown_list_model->get_sci_case_type();
        $data['main_subject_cat'] = $this->Dropdown_list_model->get_main_subject_category();
        $criminal_case=0;
        if (isset(getSessionData('efiling_details')['registration_id']) && !empty(getSessionData('efiling_details')['registration_id'])) {

            $registration_id = getSessionData('efiling_details')['registration_id'];
            $data['subordinate_court_details'] = $this->Get_details_model->get_subordinate_court_details($registration_id);
            $this->Get_details_model->get_case_table_ids($registration_id);
            $casedetails=$this->Get_details_model->get_new_case_details($registration_id);
            // pr($casedetails);
            $sc_casetype=$casedetails[0]['sc_case_type_id'];
            $data['sc_case_typeId_div_hide']=$sc_casetype;
            $criminal_case_type_id=array(10,12,14,26,2,28,6,4,8,18,20,29,16,33,35,36,41);
            if(in_array($sc_casetype,$criminal_case_type_id))
                $criminal_case=1;
        }
        $caseData = array();
        $caseData['court_type'] = !empty($casedetails[0]['court_type']) ? $casedetails[0]['court_type'] : NULL;
        $data['hc_value'] ='';
        $data['hc_bench_value'] ='';
        $data['state_value'] ='';
        $data['district_value'] ='';
        $data['agency_state_value'] ='';
        $data['agency_name_value'] ='';
        $data['data_to_be_populated'] ='';
        if(isset($caseData['court_type']) && !empty( $caseData['court_type'])){
            $court_type =  (int)$caseData['court_type'];
            $params = array();
            switch($court_type){
                case 1:
                    $estab_id = !empty($casedetails[0]['estab_id']) ? $casedetails[0]['estab_id'] : NULL; // high court estab_id
                    $estab_code = !empty($casedetails[0]['estab_code']) ? $casedetails[0]['estab_code'] : NULL; //bench estab_code
                    if(isset($estab_id) && !empty($estab_id) && isset($estab_code) && !empty($estab_code)){
                        $params['hc_id'] = $estab_id;
                        $params['est_code'] = $estab_code;
                        $highcourtData = $this->common_model->getHighCourtDetailsByHcIdAndEstabCode($params);
                        if(isset($highcourtData) && !empty($highcourtData)){
                            $data['hc_value'] = escape_data(url_encryption($highcourtData[0]->hc_id . "##" . $highcourtData[0]->hname ));
                            $data['hc_bench_value'] = escape_data(url_encryption($highcourtData[0]->bench_id . "##" . $highcourtData[0]->bname. "##" . $highcourtData[0]->est_code)) ;
                        }
                    }
                    break;
                case 3:
                    $state_id = !empty($casedetails[0]['state_id']) ? $casedetails[0]['state_id'] : NULL;
                    $district_id = !empty($casedetails[0]['district_id']) ? $casedetails[0]['district_id'] : NULL;
                    $arr = array();
                    $arr['state_id'] = $state_id;
                    $arr['district_id'] = $district_id;
                    $arr['court_type'] = $court_type;
                    $districtData = $this->common_model->getDistrictDataByCourtTypeAndStateIdDistrictId($arr);
                    if(isset($districtData) && !empty($districtData)){
                        $data['state_value'] = escape_data(url_encryption($districtData[0]->state_code . '#$' . $districtData[0]->state_name . '#$' . $districtData[0]->state_name));
                        $data['district_value'] = escape_data(url_encryption($districtData[0]->district_code . '#$' . $districtData[0]->district_name));
                    }
                    break;
                case 4:
                    break;
                case 5:
                    $state_id = !empty($casedetails[0]['state_id']) ? $casedetails[0]['state_id'] : NULL;
                    $estab_id = !empty($casedetails[0]['estab_id']) ? $casedetails[0]['estab_id'] : NULL;
                    $agencyArr = array();
                    $agencyArr['state_id'] = $state_id;
                    $agencyArr['estab_id'] = $estab_id;
                    $agencyData = $this->common_model->getStateAgencyDetailsByStateIdAndEstabId($agencyArr);
                    if(isset($agencyData) && !empty($agencyData)){
                        $data['agency_state_value'] = url_encryption($agencyData[0]->cmis_state_id . '#$' . $agencyData[0]->agency_state);
                        $data['agency_name_value']  = url_encryption($agencyData[0]->id . "##" . $agencyData[0]->agency_name. "##" . $agencyData[0]->short_agency_name );
                    }
                    break;
                default:
            }
        }
        //get total is_dead_minor
        $params = array();
        $params['registration_id'] = !empty(getSessionData('efiling_details')['registration_id']) ? getSessionData('efiling_details')['registration_id'] : NULL;
        $params['is_dead_minor'] = true;
        $params['is_deleted'] = 'false';
        $params['is_dead_file_status'] ='false';
        $params['total'] =1;
        $isdeaddata = $this->Get_details_model->getTotalIsDeadMinor($params);
        if(isset($isdeaddata[0]->total) && !empty($isdeaddata[0]->total)){
            $total = $isdeaddata[0]->total;
            log_message('info', "Please fill '.$total.' remaining dead/minor party details");
            $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center">Please fill '.$total.' remaining dead/minor party details</div>');
            return redirect()->to(base_url('newcase/lr_party'));
        }
        $data['caseData'] = $caseData;
        $data['criminal_case']=$criminal_case;
        $data['sc_casetypearr']=$sc_casetype;
        /*start code here json api IITM*/
        $data_to_be_populated_year = null; $impugned_order_date = null; $case_num=null; $judgement_type=null; $is_judgment_challenged=null; $decision_date='';
        if (isset($_SESSION['casewithAI'][0]['earlier_courts']['impugned_order_date'])) {
            if (is_array($_SESSION['casewithAI'][0]['earlier_courts']['impugned_order_date'])) {
                $impugned_order_date = $_SESSION['casewithAI'][0]['earlier_courts']['impugned_order_date'][0];
            }
            if (empty($impugned_order_date)) {
                $impugned_order_date = $_SESSION['casewithAI'][0]['earlier_courts']['impugned_order_date'];
            }
            if (!empty($impugned_order_date)) {
                $impugned_order_date = date('d/m/Y', strtotime($impugned_order_date));
            }
        }
        if (isset($_SESSION['casewithAI'][0]['earlier_courts']['decision_date'])) {
            if (is_array($_SESSION['casewithAI'][0]['earlier_courts']['decision_date'])) {
                $decision_date = $_SESSION['casewithAI'][0]['earlier_courts']['decision_date'][0];
            }
            if (empty($decision_date)) {
                $decision_date = $_SESSION['casewithAI'][0]['earlier_courts']['decision_date'];
            }
            if (!empty($decision_date)) {
                $decision_date = date('d/m/Y', strtotime($decision_date));
            }
        }
        if (isset($_SESSION['casewithAI'][0]['earlier_courts']['case_year'])) {
            if (is_array($_SESSION['casewithAI'][0]['earlier_courts']['case_year'])) {
                $data_to_be_populated_year = isset($_SESSION['casewithAI'][0]['earlier_courts'][0]['case_year']) && $_SESSION['casewithAI'][0]['earlier_courts'][0]['case_year'] != 'NA' ? $_SESSION['casewithAI'][0]['earlier_courts'][0]['case_year'] : '';
            }
            if (empty($data_to_be_populated_year)) {
                $data_to_be_populated_year = isset($_SESSION['casewithAI'][0]['earlier_courts']['case_year']) && $_SESSION['casewithAI'][0]['earlier_courts']['case_year'] != 'NA' ? $_SESSION['casewithAI'][0]['earlier_courts']['case_year'] : '';
            }
        }
        if (isset($_SESSION['casewithAI'][0]['earlier_courts']['case_num'])) {
            if (is_array($_SESSION['casewithAI'][0]['earlier_courts']['case_num'])) {
                $case_num = isset($_SESSION['casewithAI'][0]['earlier_courts'][0]['case_num']) && $_SESSION['casewithAI'][0]['earlier_courts'][0]['case_num'] != 'NA' ? $_SESSION['casewithAI'][0]['earlier_courts'][0]['case_num'] : '';
            }
            if (empty($case_num)) {
                $case_num = isset($_SESSION['casewithAI'][0]['earlier_courts']['case_num']) && $_SESSION['casewithAI'][0]['earlier_courts']['case_num'] != 'NA' ? $_SESSION['casewithAI'][0]['earlier_courts']['case_num'] : '';
            }
        }
        if (isset($_SESSION['casewithAI'][0]['earlier_courts']['judgement_type'])) {
            if (is_array($_SESSION['casewithAI'][0]['earlier_courts']['judgement_type'])) {
                $judgement_type = isset($_SESSION['casewithAI'][0]['earlier_courts'][0]['judgement_type']) && $_SESSION['casewithAI'][0]['earlier_courts'][0]['judgement_type'] != 'NA' ? $_SESSION['casewithAI'][0]['earlier_courts'][0]['judgement_type'] : '';
            }
            if (empty($judgement_type)) {
                $judgement_type = isset($_SESSION['casewithAI'][0]['earlier_courts']['judgement_type']) && $_SESSION['casewithAI'][0]['earlier_courts']['judgement_type'] != 'NA' ? $_SESSION['casewithAI'][0]['earlier_courts']['judgement_type'] : '';
            }
            if (!empty($judgement_type) && $judgement_type=='F') {
                $impugned_order_date=!empty($decision_date) ? $decision_date :$impugned_order_date;
            }

        }
        if (isset($_SESSION['casewithAI'][0]['earlier_courts']['is_judgment_challenged'])) {
            if (is_array($_SESSION['casewithAI'][0]['earlier_courts']['is_judgment_challenged'])) {
                $is_judgment_challenged = isset($_SESSION['casewithAI'][0]['earlier_courts'][0]['is_judgment_challenged']) && $_SESSION['casewithAI'][0]['earlier_courts'][0]['is_judgment_challenged'] != 'NA' ? $_SESSION['casewithAI'][0]['earlier_courts'][0]['is_judgment_challenged'] : '';
            }
            if (empty($is_judgment_challenged)) {
                $is_judgment_challenged = isset($_SESSION['casewithAI'][0]['earlier_courts']['is_judgment_challenged']) && $_SESSION['casewithAI'][0]['earlier_courts']['is_judgment_challenged'] != 'NA' ? $_SESSION['casewithAI'][0]['earlier_courts']['is_judgment_challenged'] : '';
            }
            if (!empty($is_judgment_challenged) && $is_judgment_challenged==true){
                $is_judgment_challenged=1;
            }else{
                $is_judgment_challenged=2;
            }
        }
        $data['new_case_details'][] = [
            'judgement_type' => $judgement_type,
            'judgement_challenged' => $is_judgment_challenged,
        ];
        // $data['data_to_be_populated']['year'] = $data_to_be_populated_year;
        $data['case_num']=$case_num;
        $data['impugned_order_date'] = $impugned_order_date;
        /*end code here json api IITM*/
        // $this->load->view('templates/header');
        // $this->load->view('newcase/new_case_view', $data);
        // $this->load->view('templates/footer');
        return $this->render('newcase.new_case_view', $data);
    }

    public function update_subordinate_court_details() {

        $earlierCourtArr=url_decryption($_POST['earlierCourtArr']);
        $earlierCourtData=explode('@@@',$earlierCourtArr);
        $id=$earlierCourtData[0];
        $registration_id=$earlierCourtData[1];
        $court_type=$earlierCourtData[2];
        $case_type_id=$earlierCourtData[3];
        $casetype_name=$earlierCourtData[4];

        if (!empty($registration_id) && $registration_id !=null && !empty($id) && $id !=null) {

            $subordinate_court_details = $this->Get_details_model->get_subordinate_court_details($registration_id,$id);

            $caseData = array();
            $caseData['court_type'] =$court_type;
            if(!empty($court_type)){
                $court_type =  (int)$court_type;
                $params = array();
                switch($court_type){
                    case 1: //High Court
                        $est_code = $subordinate_court_details[0]['estab_code']; //bench estab_code
                        if(isset($est_code) && !empty($est_code)){
                            $data['case_type_list'] = $this->Dropdown_list_model->hc_case_types($est_code);
                        }
                        break;
                    case 3: //District Court or Lower Court
                        $est_code = $subordinate_court_details[0]['estab_code'];
                        if (!empty($est_code)) {
                            $params=array('type'=>4,'est_code'=>$est_code,);
                            $data['case_type_list'] = $this->common_model->getSubordinateCourtData($params);
                        }
                        break;
                    case 4: //Supreme Court
                        $data['case_type_list'] = $this->Dropdown_list_model->get_sci_case_type();
                        break;
                    case 5: //State Agency
                        $agency_id = $subordinate_court_details[0]['estab_id'];
                        if (!empty($agency_id) && !empty($court_type)) {
                            $data['case_type_list'] = $this->Dropdown_list_model->icmis_state_agencies_case_types($agency_id, $court_type);
                        }
                        break;
                    default:
                }
            }
            $data['caseData'] = $caseData;
            $data['subordinate_court_details']=$subordinate_court_details;
            return $this->render('newcase.earlier_court_updateModal', $data);
        }else{
            echo 'Some error ! Please Try again';
        }
    }

    public function update_subordinate_court() {
        //echo 'under the process';
        $id=url_decryption($_POST['earlier_court_id']);
        $registration_id=url_decryption($_POST['registration_id']);
        $radio_selected_court=$_POST['radio_selected_court'];
        if ($_POST['radio_selected_court'] == '1') {

            $case_type_ids = explode('##', url_decryption($_POST['case_type_id']));
            $case_type_name=$_POST['hc_case_type_name'];

        } elseif ($_POST['radio_selected_court'] == '3') {

            $case_type_ids = explode('##', url_decryption($_POST['dc_case_type_id']));
            $case_type_name=$_POST['dc_case_type_name'];

        } elseif ($_POST['radio_selected_court'] == '4') {

            $case_type_ids = explode('#$', url_decryption($_POST['sci_case_type_id']));
            $case_type_name=$_POST['sci_case_type_name'];

        } else if($_POST['radio_selected_court'] == '5'){

            $case_type_ids = explode('##', url_decryption($_POST['agency_case_type_id']));
            $case_type_name=$_POST['agency_case_type_name'];

        }

        if ($case_type_ids[0]=='0'){
            $case_type_id=$case_type_ids[0];
            $case_type_name=$case_type_name;
        }else{
            $case_type_id=$case_type_ids[0];
            $case_type_name=$case_type_ids[1];
        }

        $update_case_details = array(
            'case_type_id' =>$case_type_id,
            'casetype_name' =>$case_type_name,

        );

        //echo '<pre>';print_r($update_case_details);echo '</pre>';exit();

        if (isset($registration_id) && !empty($registration_id) && !empty($id) && $id !=null) {
            $status = $this->New_case_model->update_subordinate_court_info_newcase($id,$registration_id, $update_case_details);
            if ($status) {
                reset_affirmation($registration_id);
                echo '2@@@' . htmlentities('Earlier Court Update successfully!', ENT_QUOTES);
            } else {
                echo '1@@@' . htmlentities('Some error ! Please Try again.', ENT_QUOTES);
            }
        }

    }
    public function add_subordinate_court_details() {


        $registration_id = getSessionData('efiling_details')['registration_id'];

        if (!empty($_SESSION['search_case_data_save']['date_of_decision'])) {
            $decision_date = $_SESSION['search_case_data_save']['date_of_decision'];
            $status = TRUE;
        } else {
            $decision_date = NULL;
            $status = FALSE;
        }
        $Sc_Case_TypeId=isset($_POST['Sc_Case_TypeId'])?$_POST['Sc_Case_TypeId']:'';


        if($Sc_Case_TypeId==7 || $Sc_Case_TypeId==8){
            $Is_Judgment_Challenged = '1' ;
            $Judgment_Type = '';

        }else{
            if (!empty($_POST['order_date'])) {
                $date = date('d/m/Y', strtotime($_POST['order_date']));
                if (!preg_match("/^(0[1-9]|[1-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/[0-9]{4}$/", $date)) {
                    echo '1@@@' . htmlentities('Invalid Date of birth date format !', ENT_QUOTES);
                    exit(0);
                }
                // $date = explode('/', $_POST['order_date']);
                // $decision_date = $date[2] . '-' . $date[1] . '-' . $date[0];
                // $decision_date = date('Y-m-d', strtotime($_POST['order_date']));
                $decision_date = $_POST['order_date'];
                
            } else {
                $decision_date = $_SESSION['search_case_data_save']['date_of_decision'];
            }
            if(isset($decision_date) && !empty($decision_date)){ 
                $decision_date = DateTime::createFromFormat('d/m/Y', $decision_date);
                $decision_date = $decision_date->format('Y-m-d'); // Convert to Y-m-d format
            }
            $Is_Judgment_Challenged = isset($_POST['judgement_challenged']) ? $_POST['judgement_challenged'] : '';
            $Judgment_Type = !empty($_POST['judgement_type']) ? $_POST['judgement_type'] : '';
        }
        $cnr_number = ''; 
        $case_number = '';
        $case_year = '';
        $state_name = '';
        $district_name = '';
        if ($_POST['radio_selected_court'] == '1') {

            $case_type_array = !empty($_POST['case_type_id']) ? url_decryption($_POST['case_type_id']) : '';
            $case_type_ids = !empty($case_type_array) ? explode('##', $case_type_array) : '';
            $case_type_name= !empty($_POST['hc_case_type_name']) ? $_POST['hc_case_type_name'] : '';
            $case_number= !empty($_POST['hc_case_number']) ? $_POST['hc_case_number'] : '';
            $case_year= !empty($_POST['hc_case_year']) ? url_decryption($_POST['hc_case_year']) : '';
            $cnr_number= !empty($_POST['cnr']) ? strtoupper($_POST['cnr']) : '';
            $hc = !empty($_POST['hc_court']) ? explode('##', url_decryption($_POST['hc_court'])) : '';
            $bench = !empty($_POST['hc_court_bench']) ? explode('##', url_decryption($_POST['hc_court_bench'])) : '';
            $estabid = !empty($hc) ? $hc[0] : '';
            $estab_code =  !empty($bench) ? $bench[2] : ''; 
            $estab_name = !empty($hc) ? $hc[1] : '';
            $court_name = !empty($estab_name) ? $estab_name . " High Court" : '';
            $state_id = 0;
            $state_name = 0;
            $district_id = 0;
            $district_name = null;
        } elseif ($_POST['radio_selected_court'] == '3') {

            $case_type_array = !empty($_POST['dc_case_type_id']) ? url_decryption($_POST['dc_case_type_id']) : '';
            $case_type_ids = !empty($case_type_array) ? explode('##', $case_type_array) : '';
            $case_type_name= !empty($_POST['hc_case_type_name']) ? $_POST['hc_case_type_name'] : '';
            $cnr_number= !empty($_POST['dc_cnr']) ? strtoupper($_POST['dc_cnr']) : '';
            $state = !empty($_POST['state']) ? explode('#$', url_decryption($_POST['state'])) : '';
            $case_number= !empty($_POST['hc_case_number']) ? $_POST['hc_case_number'] : '';
            $case_year= !empty($_POST['hc_case_year']) ? url_decryption($_POST['hc_case_year']) : '';
            $state_id = !empty($state) ? $state[0] : '';
            $state_name = !empty($state) ? $state[1] : '';

            $district = !empty($_POST['district']) ? explode('#$', url_decryption($_POST['district'])) : '';
            $district_id = !empty($district) ? $district[0] : '';
            $district_name = !empty($district) ? $district[1] : '';

            $establishment = !empty($_POST['establishment']) ? explode('#$', url_decryption($_POST['establishment'])) : '';
            $estab_code = !empty($establishment) ? $establishment[0] : '';
            $estab_name = !empty($establishment) ? $establishment[1] : '';
            $estabid = !empty($establishment[2]) ? $establishment[2] : '';
        } elseif ($_POST['radio_selected_court'] == '4') {
            $case_type_array = !empty($_POST['sci_case_type_id']) ? url_decryption($_POST['sci_case_type_id']) : '';
            $case_type_ids = !empty($case_type_array) ? explode('#$', $case_type_array) : '';
            $district_id = 0;
            $case_number= !empty($_POST['hc_case_number']) ? $_POST['hc_case_number'] : '';
            $case_year= !empty($_POST['hc_case_year']) ? url_decryption($_POST['hc_case_year']) : '';
            $state_name = 0;
            $district_name = null;
        }
        // elseif ($_POST['radio_selected_court'] == '5') {
        //     $case_number = !empty($_POST['case_number']) ? $_POST['case_number'] : '';
        //     $case_year = !empty($_POST['case_year']) ? url_decryption($_POST['case_year']) : '';
        //     $state_name = !empty($state) ? $state[1] : '';
        //     $district_name = !empty($district) ? $district[1] : '';
        // }

        if(!empty($case_type_ids)){
            if ($case_type_ids[0]=='0'){
                $case_type_id=$case_type_ids[0];
                $case_type_name=$case_type_name;
            }else{
                $case_type_id=$case_type_ids[0];
                $case_type_name=$case_type_ids[1];
            }
        }else{
            $case_type_id= '';
            $case_type_name='';
        }
        // pr($_SESSION);
        $pet_name = isset($_SESSION['search_case_data_save']['pet_name']) ? $_SESSION['search_case_data_save']['pet_name'] : NULL;
        $res_name = isset($_SESSION['search_case_data_save']['res_name']) ? $_SESSION['search_case_data_save']['res_name'] : NULL;
        $session_case_type_id = isset($_SESSION['search_case_data_save']['case_type_id']) ? $_SESSION['search_case_data_save']['case_type_id'] : NULL;
        $case_num = isset($_SESSION['search_case_data_save']['case_num']) ? $_SESSION['search_case_data_save']['case_num'] : $case_number;
        $case_year = isset($_SESSION['search_case_data_save']['case_year']) ? $_SESSION['search_case_data_save']['case_year']:$case_year;
        $cnr_number = isset($_SESSION['search_case_data_save']['cnr_num']) && !empty($_SESSION['search_case_data_save']['cnr_num'])?$_SESSION['search_case_data_save']['cnr_num']:$cnr_number;
        $session_case_type_name = isset($_SESSION['search_case_data_save']['case_type_name']) ? $_SESSION['search_case_data_save']['case_type_name'] : NULL;


        $case_details = array(
            'registration_id' => $registration_id,
            'court_type' => $_POST['radio_selected_court'],
            'state_id' => !empty($state_id)?$state_id:0,
            'district_id' => !empty($district_id)?$district_id:0,
            'agency_code' => 0,
            'pet_name' => $pet_name,
            'res_name' => $res_name,
            /*'case_type_id' => (isset($case_type_ids[0]) && !empty($case_type_ids[0]))?$case_type_ids[0]:$_SESSION['search_case_data_save']['case_type_id'],
            'case_num' => $_SESSION['search_case_data_save']['case_num'],
            'case_year' => $_SESSION['search_case_data_save']['case_year'],
            'cnr_num' => $_SESSION['search_case_data_save']['cnr_num'],*/
            //'case_type_id' => (isset($case_type_ids[0]) && !empty($case_type_ids[0]))?$case_type_ids[0]:$_SESSION['search_case_data_save']['case_type_id'],
            'case_type_id' => (isset($case_type_id) && !empty($case_type_id))?$case_type_id : $session_case_type_id,
            'case_num' =>  $case_num,
            'case_year' => $case_year,
            'cnr_num' =>  $cnr_number,
            'impugned_order_date' => $decision_date,
            'is_judgment_challenged' => $Is_Judgment_Challenged,
            'judgment_type' => $Judgment_Type,
            'status' => $status,
            // 'decision_date' => $decision_date,
            //'casetype_name' => (isset($case_type_ids[1]) && !empty($case_type_ids[1]))?$case_type_ids[1]:$_SESSION['search_case_data_save']['case_type_name'],
            'casetype_name' => isset($case_type_name) && !empty($case_type_name)?$case_type_name : $session_case_type_name,
            'estab_id' => (isset($estabid) && !empty($estabid))?$estabid:NULL,
            'estab_name' => (isset($estab_name) && !empty($estab_name)) ? $estab_name: (isset($_SESSION['search_case_data_save']['court_est_name']) ? $_SESSION['search_case_data_save']['court_est_name'] : ''),
            'estab_code' => (isset($estab_code) && !empty($estab_code)) ? $estab_code : (isset($_SESSION['search_case_data_save']['est_code']) ? $_SESSION['search_case_data_save']['est_code'] : ''),
            'case_status' => isset($_SESSION['search_case_data_save']['case_status']) ? $_SESSION['search_case_data_save']['case_status'] : '',
            'state_name' => $state_name,
            'district_name' => $district_name
        );
        // State Agency

        if ($_POST['radio_selected_court'] == '5') {
            $agency_state = !empty($_POST['agency_state']) ? explode('#$', url_decryption($_POST['agency_state'])) : '';
            $case_details['state_id'] = !empty($agency_state) ? $agency_state[0] : 0;
            $case_details['state_name'] = !empty($agency_state) ? $agency_state[1] : '';
            $agency = !empty($_POST['agency']) ? explode('##', url_decryption($_POST['agency'])) : '';
            $case_details['agency_code'] = '1';
            $case_details['estab_id'] = !empty($agency) ? $agency[0] : 0;
            $case_details['estab_name'] = !empty($agency) ? $agency[1] : '';
            $case_details['estab_code'] = !empty($agency) ? $agency[2] : '';
            $agency_case_type = !empty($_POST['agency_case_type_id']) ? explode('##', url_decryption($_POST['agency_case_type_id'])) : '';
            $case_details['case_type_id'] = !empty($agency_case_type) ? $agency_case_type[0] : 0;
            if (isset($agency_case_type[0]) && $agency_case_type[0] == '0') {
                $case_details['casetype_name'] = $_POST['agency_case_type_name'];
            } else {
                $case_details['casetype_name'] = isset($agency_case_type[1]) ? $agency_case_type[1] : '';
            }
            $case_details['case_num'] = !empty($_POST['case_number']) ? $_POST['case_number'] : '';
            $case_details['case_year'] = !empty($_POST['case_year']) ? url_decryption($_POST['case_year']) : '';
        }
        /*Start server side validation*/
        $response_validation = '';
        if ($_POST['radio_selected_court'] == '1') {
            //-High Court
            if (empty($case_details['cnr_num'])) {
                if (empty($case_details['estab_id'])) {
                    $response_validation .= "The High Court field is required.";
                }
                if (empty($case_details['estab_code'])) {
                    $response_validation .= "The High Court Bench field is required.";
                }
            }
        } elseif ($_POST['radio_selected_court'] == '3') {
            //--District court
            if (empty($_POST['dc_cnr'])) {
                if (empty($case_details['state_id'])) {
                    $response_validation .= "The State field is required.";
                }
                if (empty($case_details['district_id'])) {
                    $response_validation .= "The District field is required.";
                }
                if (empty($case_details['estab_code'])) {
                    $response_validation .= $case_details['estab_code'] . "The Establishment field is required.";
                }
            }
        } elseif ($_POST['radio_selected_court'] == '5') {
            //--State Agency/Tribunal
            if (empty($case_details['state_id'])) {
                $response_validation .= "The State field is required.";
            }
            if (empty($case_details['estab_id'])) {
                $response_validation .= "The Agency field is required.";
            }
        } elseif ($_POST['radio_selected_court'] == '4') {
            //--Supreme court
            if (empty($_POST['sci_case_type_id'])) {
                $response_validation .= "The Case Type field is required.$Sc_Case_TypeId";
            }
            if (empty($_POST['sci_case_number'])) {
                $response_validation .= "The Case No field is required.";
            }
            if (empty($_POST['sci_case_year'])) {
                $response_validation .= "The Year field is required.";
            }
        }
        // if ($_POST['radio_selected_court'] != '4') {
        //     if (empty($case_details['case_type_id'])) {
        //         if (empty($case_details['casetype_name'])) {
        //             $response_validation .= "The Case Type Name field is required.</br>";
        //         }
        //     } else {
        //         if (empty($case_details['casetype_name'])) {
        //             $response_validation .= "The Case Type field is required.</br>";
        //         }
        //     }
        //     if (empty($case_details['case_num'])) {
        //         $response_validation .= "The Case No field is required.</br>";
        //     }
        //     if (empty($case_details['case_year'])) {
        //         $response_validation .= "The Year field is required.</br>";
        //     }
        // }
        if ($Sc_Case_TypeId == 7 || $Sc_Case_TypeId == 8) {

        } else {
            if (empty($case_details['impugned_order_date'])) {
                $response_validation .= "The impugned order date field is required.";
            }
        }
        if (!empty($response_validation)) {
            echo '1@@@' . nl2br($response_validation);
            exit();
        }
        /*End server side validation*/

        $casedetails=$this->Get_details_model->get_new_case_details($registration_id);
        $sc_casetype=isset($casedetails[0]['sc_case_type_id'])?$casedetails[0]['sc_case_type_id']:'';
        $criminal_case_type_id=array(10,12,14,26,2,28,6,4,8,18,20,29,16,33,35,36,41);
        if(in_array($sc_casetype,$criminal_case_type_id) || getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_CAVEAT) {
            if(isset($_POST['fir_state']) && !empty($_POST['fir_state'])){
                $fir_state = explode('#$', url_decryption($_POST['fir_state']));

                $fir_state_id = $fir_state[0];
                $fir_state_name = $fir_state[1];

                $fir_district = !empty($_POST['fir_district']) ? explode('#$', url_decryption($_POST['fir_district'])) : '';
                $fir_district_id = !empty($fir_district) ? $fir_district[0] : '';
                $fir_district_name = !empty($fir_district) ? $fir_district[1] : '';
                $fir_police_station = !empty($_POST['fir_policeStation']) ? explode('#$', url_decryption($_POST['fir_policeStation'])) : '';
                $fir_police_station_id = !empty($fir_police_station) ? $fir_police_station[0] : '';
                $fir_police_station_name = !empty($fir_police_station) ? $fir_police_station[1] : '';
                $complete_fir_no = '';
                
                $fir_year = isset($_POST['fir_year']) ? url_decryption($_POST['fir_year']) : '' ;
                if (!empty($fir_police_station_id)) {
                    $no = $_POST['fir_number'];
                    $filled_int = sprintf("%04d", $no);
                    $year = substr($fir_year, -2);
                    $complete_fir_no = $fir_police_station_id . $filled_int . $year;
                }
                $fir_details = array(
                    'registration_id' => $registration_id,
                    'state_id' => !empty($fir_state_id) ? $fir_state_id : 0,
                    'district_id' => !empty($fir_district_id) ? $fir_district_id : 0,
                    'police_station_id' => !empty($fir_police_station_id) ? $fir_police_station_id : 0,
                    'state_name' => $fir_state_name,
                    'district_name' => !empty($fir_district_name) ? $fir_district_name : 'ALL',
                    'police_station_name' => !empty($fir_police_station_name) ? $fir_police_station_name : $_POST['police_station_name'],
                    'fir_no' => !empty($_POST['fir_number']) ? $_POST['fir_number'] : '',
                    'fir_year' => !empty($fir_year) ? $fir_year : null,
                    'complete_fir_no' => !empty($complete_fir_no) ? $complete_fir_no : $_POST['complete_fir_number'],
                    'is_deleted' => 'false',
                    'status' => 'true',
                    'updated_by' => $_SESSION['login']['id'],
                    'updated_by_ip' => getClientIP(),
                    'updated_on' => date('Y-m-d H:i:s')
                );
            }
            else{
                $fir_details = null;
            }
        }
        else
            $fir_details = null;
        $certified_copy_details = array(
            'registration_id' => $registration_id,
            'application_date' => (isset($_POST['application_date']) && !empty($_POST['application_date']))? $_POST['application_date']:NULL,
            'application_no' => (isset($_POST['application_no']) && !empty($_POST['application_no']))? $_POST['application_no']:NULL,
            'exemption_filed' => (isset($_POST['exemption_filed']) && !empty($_POST['exemption_filed']))? $_POST['exemption_filed']:NULL,
            'tentative_date' => (isset($_POST['tentative_date']) && !empty($_POST['tentative_date']))? $_POST['tentative_date']:NULL,
            'issuance_date' => (isset($_POST['issuance_date']) && !empty($_POST['issuance_date']))? $_POST['issuance_date']:NULL,
            'created_by' => $_SESSION['login']['id'],
            'created_by_ip' => getClientIP(),
            'created_on' => date('Y-m-d H:i:s')
        );
        if (isset($registration_id) && !empty($registration_id)) {
            $subordinate_court_details = $this->Get_details_model->get_subordinate_court_details($registration_id);
            if(!empty(getSessionData('efiling_details')['ref_m_efiled_type_id']) && getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_CAVEAT){
                $status = $this->New_case_model->add_subordinate_court_info($registration_id, $case_details, CAVEAT_BREAD_SUBORDINATE_COURT,$fir_details,$subordinate_court_details,null);
                if ($status) {
                    reset_affirmation($registration_id);
                    echo '2@@@' . htmlentities('Details added successfully!', ENT_QUOTES) . '@@@' . base_url('caveat/defaultController/processing' . url_encryption(trim($registration_id . '#' . E_FILING_TYPE_CAVEAT . '#' . getSessionData('efiling_details')['stage_id'])));
                } else {
                    echo '1@@@' . htmlentities('Some error ! Please Try again.', ENT_QUOTES);
                }
            }
            else
            {
                $status = $this->New_case_model->add_subordinate_court_info($registration_id, $case_details, NEW_CASE_SUBORDINATE_COURT,$fir_details,$subordinate_court_details,$certified_copy_details);
                if ($status) {
                    reset_affirmation($registration_id);
                    echo '2@@@' . htmlentities('Details added successfully!', ENT_QUOTES) . '@@@' . base_url('newcase/defaultController/' . url_encryption(trim($registration_id . '#' . E_FILING_TYPE_NEW_CASE . '#' . getSessionData('efiling_details')['stage_id'])));
                } else {
                    echo '1@@@' . htmlentities('Some error ! Please Try again.', ENT_QUOTES);
                }
            }

        }
        unset($_SESSION['search_case_data_save']);
    }

    //This call is once in a while functionality to update HC & Bench master table i.e: m_tbl_high_courts_bench
    function hc_bench_from_api(){
        if(!$this->request->isCLI()){
            echo "This script can only be accessed via the command line" . PHP_EOL;
            return;
        }
        $high_courts = $this->highcourt_webservices->states();
        $high_courts = json_decode($high_courts, true);
        if(isset($high_courts['state'])){
            foreach ($high_courts['state'] as $courts) {
                $this->Dropdown_list_model->insert_to_table('efil.m_tbl_high_courts_bench', array('hc_id'=>$courts['state_code'], 'name'=>$courts['state_name']));
            }
        }
        $hc_list = $this->Dropdown_list_model->high_courts();
        foreach ($hc_list as $hc){
            $hc_benches = $this->highcourt_webservices->bench($hc['hc_id']);
            $hc_benches = json_decode($hc_benches, true);
            if(isset($hc_benches['benches'])){
                foreach ($hc_benches['benches'] as $bench) {
                    $this->Dropdown_list_model->insert_to_table('efil.m_tbl_high_courts_bench', array('hc_id'=>$hc['hc_id'], 'name'=>$bench['bench_name'], 'bench_id'=>$bench['bench_id'], 'est_code'=>$bench['est_code']));
                }
            }
        }
    }

    //This call is once in a while functionality to update HC & Bench master table i.e: m_tbl_high_courts_bench
    function hc_case_types_from_api(){
        if(!$this->request->isCLI()){
            echo "This script can only be accessed via the command line" . PHP_EOL;
            return;
        }
        $bench_list = $this->Dropdown_list_model->hc_bench(0,0);
        foreach ($bench_list as $bench){
            $hc_case_types = $this->highcourt_webservices->caseTypeMaster($bench['est_code']);
            $hc_case_types = json_decode($hc_case_types, true);
            if(isset($hc_case_types)){
                foreach ($hc_case_types as $hc_case_type) {
                    $this->Dropdown_list_model->insert_to_table('efil.m_tbl_high_courts_case_types', array('est_code'=>$bench['est_code'], 'case_type'=>$hc_case_type['case_type'], 'type_name'=>$hc_case_type['type_name'] ));
                    // $dropDownOptions .= '<option value="' . escape_data(url_encryption($hc_case_type['case_type'] . "##" . $hc_case_type['type_name'] )) . '">' . escape_data(strtoupper($hc_case_type['type_name'])) . '</option>';
                }
            }
        }
    }

    //This call is once in a while functionality to update HC & Bench master table i.e: m_tbl_high_courts_bench
    function dc_estab_from_api(){
        // if(!$this->input->is_cli_request()){
        //     echo "This script can only be accessed via the command line" . PHP_EOL;
        //     return;
        // }
        if(!$this->request->isCLI()){
            echo "This script can only be accessed via the command line" . PHP_EOL;
            return;
        }
        
        $states = file_get_contents(DISTRICT_COURT_URL.'states');
        $states = json_decode($states, true);
        if(isset($states['state'])){
            foreach ($states['state'] as $state) {
                $state_code = $state['state_code'];
                $state_name = $state['state_name'];
                $districts = file_get_contents(DISTRICT_COURT_URL.'districts/'.$state_code);
                $districts = json_decode($districts, true);
                if(isset($districts['district'])){
                    foreach ($districts['district'] as $district){
                        $district_code = $district['dist_code'];
                        $district_name = $district['dist_name'];
                        $complexes = file_get_contents(DISTRICT_COURT_URL.'courtComplex/'.$state_code.'/'.$district_code);
                        $complexes = json_decode($complexes, true);
                        if(isset($complexes['complex1'])){
                            foreach ($complexes['complex1'] as $complex){
                                $estab_code = $complex['est_code'];
                                $estab_name = $complex['court_est_name'];
                                $x = array('state_code'=>$state_code,
                                    'state_name'=>$state_name,
                                    'district_code'=>$district_code,
                                    'district_name'=>$district_name,
                                    'estab_code'=>$estab_code,
                                    'estab_name'=>$estab_name);
                                if(!is_null($estab_code))
                                    $this->Dropdown_list_model->insert_to_table('efil.m_tbl_district_courts_establishments',$x);
                                else
                                    continue;
                            }
                        }
                    }
                }

            }
        }
    }

    function dc_case_types_from_api(){
        /*if(!$this->input->is_cli_request()){
            echo "This script can only be accessed via the command line" . PHP_EOL;
            return;
        }*/
        $estab_list = $this->Dropdown_list_model->get_district_court_establishments();
        //var_dump($estab_list);
        foreach ($estab_list as $estab){
            /*echo '<pre>';
            print_r($estab);*/
            $dc_case_types = file_get_contents(DISTRICT_COURT_URL.'webServiceCaseTypeMaster/'.$estab['estab_code']);
            $dc_case_types = json_decode($dc_case_types, true);
            if(isset($dc_case_types)){
                foreach ($dc_case_types as $dc_case_type) {
                    $this->Dropdown_list_model->insert_to_table('efil.m_tbl_district_courts_case_types', array('est_code'=>$estab['estab_code'], 'case_type'=>$dc_case_type['case_type'], 'type_name'=>$dc_case_type['type_name'] ));
                }
            }
        }
    }



    /*changes started on 04 September 2020*/
    public function DeleteSubordinateCourt($delete_id) {
        $delete_id = url_decryption($delete_id);
        if (empty($delete_id)) {
            if(!empty(getSessionData('efiling_details')['ref_m_efiled_type_id']) && getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_CAVEAT){
                $_SESSION['MSG'] = message_show("fail", "Invalid ID."); // this msg is not being displayed. checking required
                return redirect()->to(base_url('caveat/subordinate_court'));
                exit(0);
            }
            else{
                $_SESSION['MSG'] = message_show("fail", "Invalid ID."); // this msg is not being displayed. checking required
                return redirect()->to(base_url('newcase/subordinate_court'));
                exit(0);
            }
        }
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_DEPARTMENT);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            if(!empty(getSessionData('efiling_details')['ref_m_efiled_type_id']) && getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_CAVEAT){
                return redirect()->to(base_url('caveat/subordinate_court'));
                exit(0);
            }
            else{
                return redirect()->to(base_url('newcase/subordinate_court'));
                exit(0);
            }
        }
        $stages_array = array('', Draft_Stage, Initial_Defected_Stage, I_B_Defected_Stage);

        if (!in_array(getSessionData('efiling_details')['stage_id'], $stages_array)) {
            if(!empty(getSessionData('efiling_details')['ref_m_efiled_type_id']) && getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_CAVEAT){
                return redirect()->to(base_url('caveat/subordinate_court'));
                exit(0);
            }
            else{
                return redirect()->to(base_url('newcase/subordinate_court'));
                exit(0);
            }
        }
        if (isset(getSessionData('efiling_details')['registration_id']) && !empty(getSessionData('efiling_details')['registration_id'])) {
            $registration_id = getSessionData('efiling_details')['registration_id'];
            $params =array();
            $params['table_name'] = "efil.tbl_fir_details";
            $params['whereFieldName'] = "ref_tbl_lower_court_details_id";
            $params['whereFieldValue'] = $delete_id;
            $params['is_deleted'] = "false";
            $result= $this->common_model->getData($params);
            $firData = false;
            if(isset($result) && !empty($result)){
                $firData = true;
            }
            $params =array();
            $params['table_name'] = "efil.tbl_certified_copy_details";
            $params['whereFieldName'] = "ref_tbl_lower_court_details_id";
            $params['whereFieldValue'] = $delete_id;
            $params['is_deleted'] =false;
            $certified_copy_result= $this->common_model->getData($params);
            $certified_copyData = false;
            if(isset($certified_copy_result) && !empty($certified_copy_result)){
                $certified_copyData = true;
            }
            $delete_status = $this->DeleteSubordinateCourt_model->delete_case_subordinate_court($registration_id, $delete_id,$firData,$certified_copyData);
            if ($delete_status) {
                reset_affirmation($registration_id);
                    $this->session->setFlashdata('msg', '<div class="alert alert-success text-center" role="alert" data-auto-dismiss="5000">Earlier Court Details deleted successfully.</div>');

                //$_SESSION['MSG'] = message_show("success", "Earlier Court Details deleted successfully."); // this msg is not being displayed. checking required
            } else {
                $this->session->setFlashdata('fail', 'Some error. Please try again.');

                //$_SESSION['MSG'] = message_show("fail", "Some error. Please try again."); // this msg is not being displayed. checking required
            }
            if(!empty(getSessionData('efiling_details')['ref_m_efiled_type_id']) && getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_CAVEAT){
                return redirect()->to(base_url('caveat/defaultController/processing/' . url_encryption(trim($registration_id . '#' . E_FILING_TYPE_CAVEAT . '#' . getSessionData('efiling_details')['stage_id']))));
                exit(0);
            }
            else{
                return redirect()->to(base_url('newcase/subordinate_court'));
                //redirect('newcase/defaultController/' . url_encryption(trim($registration_id . '#' . E_FILING_TYPE_NEW_CASE . '#' . getSessionData('efiling_details')['stage_id'])));
                exit(0);
            }

        }
    }

    /*End of changes*/


}
