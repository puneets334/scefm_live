<?php

namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\FilingAdmin\FilingAdminModel;
use App\Models\SuperAdmin\SuperAdminModel;
use App\Models\Admin\SupadminModel;
use App\Models\Department\DepartmentModel;
use App\Models\NewRegister\AdvocateModel;
use App\Models\Common\CommonModel;
use App\Models\Common\DropdownListModel;
use App\Libraries\webservices\Efiling_webservices;

class SupadminController extends BaseController {

    protected $request;
    protected $session;
    protected $SuperAdminModel;
    protected $Supadmin_model;
    protected $Department_model;
    protected $Advocate_model;
    protected $Common_model;
    protected $Dropdown_list_model;
    protected $show_estab_profile;
    protected $efiling_webservices;
    
    public function __construct() {
        parent::__construct();
        if(empty(getSessionData('login'))){
            return response()->redirect(base_url('/')); 
        }else{
            is_user_status();
        }
        $this->request = \Config\Services::request();
        $this->session = \Config\Services::session();
        $this->SuperAdminModel = new SuperAdminModel();
        $this->Supadmin_model = new SupadminModel();
        $this->Department_model = new DepartmentModel();
        $this->Advocate_model = new AdvocateModel();
        $this->Common_model = new CommonModel();
        $this->Dropdown_list_model = new DropdownListModel();
        $this->efiling_webservices = new Efiling_webservices();
    }

    public function index() {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        unset($_SESSION['edit_id']);
        unset($_SESSION['frm_ref_id']);
        unset($_SESSION['change_filing_number']);
        unset($_SESSION['estab_details']);
        unset($_SESSION['efiling_details']);
        unset($_SESSION['filtered_state_id']);
        unset($_SESSION['filtered_distt_id']);
        unset($_SESSION['filtered_estab_id']);
        $this->supadmin();
    }

    public function supadmin() {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        if (!in_array($this->session->userdata['login']['ref_m_usertype_id'],array(USER_SUPER_ADMIN,USER_ADMIN))) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        unset($_SESSION['change_filing_number']);
        unset($_SESSION['last_stage_to_be_updated']);
        return $this->render('admin.supadmin_dashboard');
    }

    public function change_case_status()
    {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        $allowed_users = array(USER_ADMIN);
        if (getSessionData('login') != '' && !in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users)) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        unset($_SESSION['change_filing_number']);
        unset($_SESSION['last_stage_to_be_updated']);
        return $this->render('admin.supadmin_change_case_status_view');
    }

    public function search_case_status() {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        if (empty($this->request->getPost('efil_no'))) {
            $this->session->setFlashdata('MSG', "No Efiling number given !");
            return redirect()->to(base_url('Admin/Supadmin/change_case_status'));
            exit(0);
        }
        if (strlen(trim($this->request->getPost('efil_no'))) != 17) {
            $this->session->setFlashdata('MSG', "Invalid Efiling number !");
            return redirect()->to(base_url('Admin/Supadmin/change_case_status'));
            exit(0);
        }
        if (preg_match('/[^0-9a-zA-Z]/i', trim($this->request->getPost('efil_no')))) {
            $this->session->setFlashdata('MSG', "Efiling Number Can only contain characters, numbers and hyphens(-) !");
            return redirect()->to(base_url('Admin/Supadmin/change_case_status'));
            exit(0);
        }
        $dat = $this->Supadmin_model->search_status_that_can_be_changed($this->request->getPost('efil_no'));
        if (!$dat) {
            $this->session->setFlashdata('MSG', "efiling number is not on the stages viz. For Compliance, Pay Deficit Fee, Transfer to CIS, Idle/Unprocessed, Mark as error hence no action warranted!");
            return redirect()->to(base_url('Admin/Supadmin/change_case_status'));
            exit(0);
        } else {
            $Array = array(Initial_Defected_Stage, DEFICIT_COURT_FEE, Transfer_to_IB_Stage, LODGING_STAGE,MARK_AS_ERROR);
            if (!in_array($dat[0]['stage_id'], $Array)) {                
                $this->session->setFlashdata('MSG', "efiling number is not on the stages viz. For Compliance, Pay Deficit Fee, Transfer to CIS, Idle/Unprocessed, Mark as error hence no action warranted!");
                return redirect()->to(base_url('Admin/Supadmin/change_case_status'));
                exit(0);
            }
            $change_case_session = array('change_filing_number' => $dat);
            $this->session->set($change_case_session);
            return redirect()->to(base_url('admin/supadmin/change_case_status_main'));
            exit(0);
        }
    }

    public function change_case_status_main() {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        unset($_SESSION['last_stage_to_be_updated']);
        if (!in_array(getSessionData('login')['ref_m_usertype_id'],array(USER_SUPER_ADMIN,USER_ADMIN))) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        if (empty(getSessionData('change_filing_number'))) {
            $this->session->setFlashdata('MSG', "No Efiling Number was set!");
            return redirect()->to(base_url('Admin/Supadmin/change_case_status'));
            exit(0);
        }
        $data['status_that_can_be_changed'] = getSessionData('change_filing_number');
        return $this->render('admin.supadmin_change_case_status_main_view', $data);
    }

    public function final_case_status_change() {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        if (!in_array(getSessionData('login')['ref_m_usertype_id'],array(USER_SUPER_ADMIN,USER_ADMIN))) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        if (empty(getSessionData('change_filing_number'))) {
            $this->session->setFlashdata('MSG', "No Efiling Number was set!");
            return redirect()->to(base_url('Admin/Supadmin/change_case_status'));
            exit(0);
        }
        if (empty(getSessionData('last_stage_to_be_updated'))) {
            $this->session->setFlashdata('MSG', "No Last Stage to be updated is set !");
            return redirect()->to(base_url('admin/supadmin/change_case_status_main'));
            exit(0);
        }
        if (getSessionData('last_stage_to_be_updated') != $this->request->getPost('to_change_status_id')) {
            $this->session->setFlashdata('MSG', "Status to be changed is not set !");
            return redirect()->to(base_url('admin/supadmin/change_case_status_main'));
            exit(0);
        }
        if ($this->request->getPost('to_change_status_id') == NULL || $this->request->getPost('to_change_status_id') == '') {
           $this->session->setFlashdata('MSG', "Status to be changed cannot be blank !");
            return redirect()->to(base_url('admin/supadmin/final_case_status_change'));
            exit(0);
        }
        if ($this->request->getPost('remark') == NULL || $this->request->getPost('remark') == '') {
            $this->session->setFlashdata('MSG', "Remark Cannot be Blank !");
            return redirect()->to(base_url('admin/supadmin/final_case_status_change'));
            exit(0);
        }
        if (!is_numeric($this->request->getPost('to_change_status_id'))) {
            $this->session->setFlashdata('MSG', "Status to be changed should be numeric !");
            return redirect()->to(base_url('admin/supadmin/final_case_status_change'));
            exit(0);
        }
        if (preg_match('/[^0-9a-zA-Z\s.,]/i', $this->request->getPost('remark'))) {
            $this->session->setFlashdata('MSG', "Remark Can only contain numbers characters and spaces !");
            return redirect()->to(base_url('admin/supadmin/final_case_status_change'));
            exit(0);
        }
        if (strlen($this->request->getPost('remark')) >= 201) {
            $this->session->setFlashdata('MSG', "Remark length can only be of 200 characters !");
            return redirect()->to(base_url('admin/supadmin/final_case_status_change'));
            exit(0);
        }        
        $reg_id = getSessionData('change_filing_number')[0]['registration_id'];
        $status_to_change = $this->request->getPost('to_change_status_id');
        $remark = $this->request->getPost('remark');
        if (is_numeric($reg_id) && is_numeric($status_to_change)) {
            $res = $this->Supadmin_model->updateCaseStatus($reg_id, $status_to_change, $remark);
            if ($res) {
                $this->session->setFlashdata('success', "Status changed successfully !");
                return redirect()->to(base_url('Admin/Supadmin/change_case_status'));
                exit(0);
            } else {
                $this->session->setFlashdata('MSG', "Some Error Occoured. Contact Admin !");
                return redirect()->to(base_url('Admin/Supadmin/change_case_status'));
                exit(0);
            }
        } else {
            return redirect()->to(base_url('admin/supadmin/final_case_status_change'));
            exit(0);
        }
    }

    public function establishment() {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        if ($this->session->userdata['login']['ref_m_usertype_id'] != USER_SUPER_ADMIN) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $data['high_court_list'] = $this->Supadmin_model->get_superadmin_high_court_list();
        $data['state_list'] = $this->Supadmin_model->get_admin_states();
        $data['estab_details'] = $this->Supadmin_model->get_estab_details();
        return $this->render('admin.add_establishment_view', $data);
    }

    public function go_live() {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        if ($this->session->userdata['login']['ref_m_usertype_id'] != USER_SUPER_ADMIN) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $data['high_court_list'] = $this->Supadmin_model->get_live_high_court_list();
        $data['state_list'] = $this->Dropdown_list_model->get_super_admin_state_list();
        return $this->render('admin.go_live_view', $data);
    }

    public function check_master() {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        if ($this->session->userdata['login']['ref_m_usertype_id'] != USER_SUPER_ADMIN) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $data['state_list_y'] = $this->Dropdown_list_model->get_super_admin_state_list();
        $data['high_court_list'] = $this->Dropdown_list_model->get_super_admin_high_court_list();
        return $this->render('admin.check_master_data', $data);
    }

    public function get_master_all_data() {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        if ($this->session->userdata['login']['ref_m_usertype_id'] != USER_SUPER_ADMIN) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $court_type = $this->request->getPost("court_type");
        if (!isset($court_type) || empty($court_type) || !(env('ENABLE_FOR_HC') && env('ENABLE_FOR_ESTAB'))) {
            if (env('ENABLE_FOR_HC')) {
                $efiling_for_type_id = E_FILING_FOR_HIGHCOURT;
            } elseif (env('ENABLE_FOR_ESTAB')) {
                $efiling_for_type_id = E_FILING_FOR_ESTABLISHMENT;
            }
        } else {
            $efiling_for_type_id = url_decryption($court_type);
            $validate_nums = validate_number($efiling_for_type_id, TRUE, 1, 1, 'Court type');
            if ($validate_nums['response'] == FALSE) {
                echo '1@@@' . htmlentities($validate_nums['msg']['field_name'], ENT_QUOTES);
                exit(0);
            }
        }
        if ($efiling_for_type_id == E_FILING_FOR_HIGHCOURT) {
            $efiling_for_id = url_decryption($_POST['high_court_id']);
            $validate_nums = validate_number($efiling_for_id, TRUE, 1, 6, 'High Court');
            if ($validate_nums['response'] == FALSE) {
                echo '1@@@' . htmlentities($validate_nums['msg']['field_name'], ENT_QUOTES);
                exit(0);
            }
            $estab_details = $this->Common_model->get_establishment_details($efiling_for_type_id, $efiling_for_id);
            if (isset($estab_details) && empty($estab_details)) {
                echo '1@@@' . htmlentities('Invalid establishment', ENT_QUOTES);
                exit(0);
            }
            $estab_code = $_SESSION['estab_details']['est_code'];
            $state_code = $_SESSION['estab_details']['state_code'];
        } else {
            $state_data = url_decryption($_POST['filter_based_on_state']);
            $district_data = url_decryption($_POST['filter_based_on_district']);
            $establishment_data = explode('#$', url_decryption($_POST['filter_based_on_establishment']));
            $state_code = url_decryption($_POST['filter_based_on_state']);
            $validate_nums = validate_number($state_code, TRUE, 1, 6, 'State');
            if ($validate_nums['response'] == FALSE) {
                echo '1@@@' . htmlentities($validate_nums['msg']['field_name'], ENT_QUOTES);
                exit(0);
            }
            $dist_code_array = explode('#$', url_decryption($_POST['filter_based_on_district']));
            $dist_code = $dist_code_array[1];
            $validate_nums = validate_number($dist_code, TRUE, 1, 6, 'District');
            if ($validate_nums['response'] == FALSE) {
                echo '1@@@' . htmlentities($validate_nums['msg']['field_name'], ENT_QUOTES);
                exit(0);
            }
            $estab_id_array = explode('#$', url_decryption($_POST['filter_based_on_establishment']));
            $establishment_id = $estab_id_array[0];
            $validate_nums = validate_number($establishment_id, TRUE, 1, 6, 'Establishment');
            if ($validate_nums['response'] == FALSE) {
                echo '1@@@' . htmlentities($validate_nums['msg']['field_name'], ENT_QUOTES);
                exit(0);
            }
            $establishment_id = $establishment_data[0];
            $estab_code = $establishment_data[1];
            $state_code = $establishment_data[2];
            $_SESSION['estab_details']['est_code'] = $estab_code;
            $_SESSION['estab_details']['efiling_for_type_id'] = $efiling_for_type_id;
            $_SESSION['estab_details']['state_code'] = $state_code;
        }
        //--Address Master Web services--//
        $output = "";
        $states = "";
        $result = $this->efiling_webservices->getState($estab_code, $efiling_for_type_id, $state_code, $state_code);
        if (count($result)) {
            $states .= '<option value=""> Select</option>';
            foreach ($result as $dataActs) {
                $value = (string) $dataActs->STATECODE;
                $name = (string) $dataActs->STATENAME;
                $states .= '<option value="' . htmlentities(url_encryption(trim($value . '#$' . $name)), ENT_QUOTES) . '">' . '' . htmlentities(strtoupper((string) $dataActs->STATENAME), ENT_QUOTES) . '</option>';
            }
        } else {
            $states .= '<option value=""> Select</option>';
        }
        $output .= $states;
        $trials_result = $this->efiling_webservices->get_trials($estab_code, $efiling_for_type_id, $state_code);
        $trials_list = "";
        if (count($trials_result)) {
            $trials_list .= '<option value=""> Select </option>';
            foreach ($trials_result as $code => $name) {
                $trials_list .= '<option value="' . htmlentities(url_encryption(trim((string) $code . '#$' . (string) $name), ENT_QUOTES)) . '">' . htmlentities($name, ENT_QUOTES) . '</option>';
            }
        } else {
            $trials_list .= '<option value=""> Select</option>';
        }
        $output .= "@@@" . $trials_list;
        $relation_result = $this->efiling_webservices->getRelation($estab_code, $efiling_for_type_id, $state_code);
        $relation_list = "";
        if (count($relation_result)) {
            $relation_list .= '<option value=""> Select Relative  </option>';
            foreach ($relation_result as $dataRes) {
                $value = (string) $dataRes->RELATION_ID;
                $relation_list = '<option value="' . htmlentities(url_encryption(trim($dataRes->RELATION_ID . '#$' . (string) $dataRes->RELATION_NAME), ENT_QUOTES)) . '">' . htmlentities((string) $dataRes->RELATION_NAME, ENT_QUOTES) . '</option>';
            }
        } else {
            $relation_list .= '<option value=""> Select</option>';
        }
        $output .= "@@@" . $relation_list;
        $org_list = $this->efiling_webservices->getOrgname($estab_code, $efiling_for_type_id, $state_code, $state_code);
        $org_result = "";
        if (count($org_list)) {
            $org_result .= '<option value=""> Select</option>';
            foreach ($org_list as $dataRes) {
                $value = (string) $dataRes->ORGCODE;
                $org_result .= '<option value="' . htmlentities(url_encryption(trim($dataRes->ORGCODE . '#$' . (string) $dataRes->ORGNAME), ENT_QUOTES)) . '">' . htmlentities((string) $dataRes->ORGNAME, ENT_QUOTES) . '</option>';
            }
        } else {
            $org_result .= '<option value=""> Select</option>';
        }
        $output .= "@@@" . $org_result;
        $religion_list = $this->efiling_webservices->getReligion($estab_code, $efiling_for_type_id, $state_code);
        $religion_result = "";
        if (count($religion_list)) {
            $religion_result .= '<option value=""> Select</option>';
            foreach ($religion_list as $dataRes) {
                $religion_result .= '<option value="' . htmlentities(url_encryption(trim((string) $dataRes->RELIGIONCODE . '#$' . (string) $dataRes->RELIGIONNAME), ENT_QUOTES)) . '">' . htmlentities((string) $dataRes->RELIGIONNAME, ENT_QUOTES) . '</option>';
            }
        } else {
            $religion_result .= '<option value=""> Select</option>';
        }
        $output .= "@@@" . $religion_result;
        $police_challan_list = $this->efiling_webservices->get_policechalan($estab_code, $efiling_for_type_id, $state_code);
        $police_challan_result = "";
        if (count($police_challan_list)) {
            $police_challan_result .= '<option value=""> Select</option>';
            foreach ($police_challan_list as $code => $name) {
                $police_challan_result .= '<option value="' . htmlentities(trim($code), ENT_QUOTES) . '">' . htmlentities($name, ENT_QUOTES) . '</option>';
            }
        } else {
            $police_challan_result .= '<option value=""> Select</option>';
        }
        $output .= "@@@" . $police_challan_result;
        $investigation_list = $this->efiling_webservices->get_investigation($estab_code, $efiling_for_type_id, $state_code);
        $investigation_result = "";
        if (count($investigation_list)) {
            $investigation_result .= '<option value=""> Select</option>';
            foreach ($investigation_list as $dataRes) {
                $investigation_result .= '<option value="' . htmlentities(url_encryption(trim((string) $dataRes->INVESTIGATION_CODE . '#$' . (string) $dataRes->INVESTIGATION_AGENCY), ENT_QUOTES)) . '">' . htmlentities((string) $dataRes->INVESTIGATION_AGENCY, ENT_QUOTES) . '</option>';
            }
        } else {
            $investigation_result .= '<option value=""> Select </option>';
        }
        $output .= "@@@" . $investigation_result;
        $fir_type = $this->efiling_webservices->get_fir_type_list($estab_code, $efiling_for_type_id, $state_code);
        $fir_type_result = "";
        if (count($fir_type)) {
            $fir_type_result .= '<option value=""> Select</option>';
            foreach ($fir_type as $dataRes) {
                $fir_type_result .= '<option value="' . htmlentities(url_encryption(trim((string) $dataRes->FIRTYPECODE . '#$' . (string) $dataRes->FIRTYPENAME), ENT_QUOTES)) . '">' . htmlentities((string) $dataRes->FIRTYPENAME, ENT_QUOTES) . '</option>';
            }
        } else {
            $fir_type_result .= '<option value=""> Select</option>';
        }
        $output .= "@@@" . $fir_type_result;
        $act_list = $this->efiling_webservices->get_act_list($estab_code, $efiling_for_type_id, $state_code);
        $act_result = "";
        if (count($act_list)) {
            $act_result .= '<option value=""> Select </option>';
            foreach ($act_list as $dataRes) {
                $act_result .= '<option value="' . htmlentities(url_encryption(trim((string) $dataRes->ACTCODE . '#$' . (string) $dataRes->ACTNAME), ENT_QUOTES)) . '">' . htmlentities((string) $dataRes->ACTNAME, ENT_QUOTES) . '</option>';
            }
        } else {
            $act_result .= '<option value=""> Select </option>';
        }
        $output .= "@@@" . $act_result;
        $doc_type = $this->efiling_webservices->getDocType($estab_code, $efiling_for_type_id, $state_code);
        $doc_type_result = "";
        if (count($doc_type)) {
            $doc_type_result .= '<option value=""> Select </option>';
            foreach ($doc_type as $doc) {
                $doc_type_result .= '<option value="' . htmlentities(url_encryption(trim((string) $doc->DOCUMENTTYPE . '#$' . (string) $doc->DOCUMENTNAME)), ENT_QUOTES) . '">' . htmlentities((string) $doc->DOCUMENTNAME, ENT_QUOTES) . '</option>';
            }
        } else {
            $doc_type_result .= '<option value=""> Select </option>';
        }
        $output .= "@@@" . $doc_type_result;
        $court_fee_type = $this->efiling_webservices->getCourtFeeType($estab_code, $efiling_for_type_id, $state_code, $state_code);
        $court_fee_type_result = "";
        if (count($court_fee_type)) {
            $court_fee_type_result .= '<option value=""> Select</option>';
            foreach ($court_fee_type as $fee_type) {
                $court_fee_type_result .= '<option value = "' . url_encryption(htmlentities($fee_type->TYPE . '#$' . $fee_type->NAME, ENT_QUOTES)) . '">' . htmlentities($fee_type->NAME, ENT_QUOTES) . ' </option>';
            }
        } else {
            $court_fee_type_result .= '<option value=""> Select</option>';
        }
        $output .= "@@@" . $court_fee_type_result;
        $case_type = $this->efiling_webservices->getLowerCourtCaseType($estab_code, $efiling_for_type_id, $state_code, $state_code);
        $case_type_result = "";
        if (count($case_type)) {
            $case_type_result .= '<option value=""> Select </option>';
            foreach ($case_type as $case) {
                $case_type_result .= '<option value="' . htmlentities(url_encryption(trim((string) $case->CASETYPE . '#$' . (string) $case->CASENAME), ENT_QUOTES)) . '">' . htmlentities((string) $case->CASENAME, ENT_QUOTES) . '</option>';
            }
        } else {
            $case_type_result .= '<option value=""> Select</option>';
        }
        $output .= "@@@" . $case_type_result;
        $objection_list = $this->efiling_webservices->getObjection($estab_code, $efiling_for_type_id, $state_code, $state_code);
        $objection_result = "";
        if (count($objection_list)) {
            $objection_result .= '<option value=""> Select </option>';
            foreach ($objection_list as $obj) {
                $objection_result .= '<option value="' . htmlentities(url_encryption(trim((string) $obj->OBJECTION_CODE . '#$' . (string) $obj->OBJECTION_TYPE), ENT_QUOTES)) . '">' . htmlentities((string) $obj->OBJECTION_TYPE, ENT_QUOTES) . '</option>';
            }
        } else {
            $objection_result .= '<option value=""> Select </option>';
        }
        $output .= "@@@" . $objection_result;
        $ia_case_type = $this->efiling_webservices->getIACaseType($estab_code, $efiling_for_type_id, $state_code);
        $ia_case_type_result = "";
        if (count($ia_case_type)) {
            $ia_case_type_result .= '<option value=""> Select</option>';
            foreach ($ia_case_type as $dataRes) {
                if ($dataRes->FULLFORM != '') {
                    $full_form = ' (' . htmlentities(strtoupper((string) $dataRes->FULLFORM), ENT_QUOTES) . ')';
                } else {
                    $full_form = '';
                }
                $ia_case_type_result .= '<option value="' . htmlentities(url_encryption(trim((string) $dataRes->CASETYPE . '#$' . (string) $dataRes->TYPENAME . $full_form), ENT_QUOTES)) . '">' . htmlentities((string) $dataRes->TYPENAME, ENT_QUOTES) . $full_form . '</option>';
            }
        } else {
            $ia_case_type_result .= '<option value=""> Select</option>';
        }
        $output .= "@@@" . $ia_case_type_result;
        $ia_classification = $this->efiling_webservices->getIAClassification($estab_code, $efiling_for_type_id, $state_code);
        $ia_classification_result = "";
        if (count($ia_classification)) {
            $ia_classification_result .= '<option value=""> Select </option>';
            foreach ($ia_classification as $dataRes) {
                $ia_classification_result .= '<option value="' . htmlentities(url_encryption(trim((string) $dataRes->IACLASSCODE . '#$' . (string) $dataRes->IACLASSNAME), ENT_QUOTES)) . '">' . htmlentities((string) $dataRes->IACLASSNAME, ENT_QUOTES) . '</option>';
            }
        } else {
            $ia_classification_result .= '<option value=""> Select </option>';
        }
        $output .= "@@@" . $ia_classification_result;
        $ia_prayer = $this->efiling_webservices->getPrayer($estab_code, $efiling_for_type_id, $state_code);
        $ia_prayer_result = "";
        $sel = "";
        if (count($ia_prayer)) {
            $ia_prayer_result .= '<option value=""> Select </option>';
            foreach ($ia_prayer as $dataRes) {
                $ia_prayer_result .= '<option ' . $sel . ' value="' . htmlentities(url_encryption(trim((string) $dataRes->PRAYERCODE . '#$' . (string) $dataRes->PRAYERTYPE), ENT_QUOTES)) . '">' . htmlentities((string) $dataRes->PRAYERTYPE, ENT_QUOTES) . '</option>';
            }
        } else {
            $ia_prayer_result .= '<option value=""> Select </option>';
        }
        $output .= "@@@" . $ia_prayer_result;
        $purpose_list = $this->efiling_webservices->getPurpose($estab_code, $efiling_for_type_id, $state_code, $state_code);
        $ia_purpose_result = "";
        if (count($purpose_list)) {
            $ia_purpose_result .= '<option value=""> Select </option>';
            foreach ($purpose_list as $dataRes) {
                $ia_purpose_result .= '<option value="' . htmlentities(url_encryption(trim((string) $dataRes->PURPOSE_CODE . '#$' . (string) $dataRes->PURPOSE_NAME), ENT_QUOTES)) . '">' . htmlentities((string) $dataRes->PURPOSE_NAME, ENT_QUOTES) . '</option>';
            }
        } else {
            $ia_purpose_result .= '<option value=""> Select </option>';
        }
        $output .= "@@@" . $ia_purpose_result;
        echo $output;
    }

    public function check_payment() {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        if ($this->session->userdata['login']['ref_m_usertype_id'] != USER_SUPER_ADMIN) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $data['state_list_y'] = $this->Dropdown_list_model->get_state_list_Y();
        $data['high_court_list'] = $this->Dropdown_list_model->get_high_court_list();
        if (!empty($_SESSION['filtered_state_id']) && !empty($_SESSION['filtered_distt_id'])) {
            $data['district_list'] = $this->Dropdown_list_model->get_district_list($_SESSION['filtered_state_id']);
            $data['establishment_list'] = $this->Dropdown_list_model->get_eshtablishment_list($_SESSION['filtered_distt_id']);
        }
        $data['payment_details'] = $this->Supadmin_model->get_payment_detail($_SESSION['estab_details']['efiling_for_type_id'], $_SESSION['estab_details']['efiling_for_id']);
        return $this->render('admin.check_payment_getway', $data);
    }

    public function get_court_fee_type_list() {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        if ($this->session->userdata['login']['ref_m_usertype_id'] != USER_SUPER_ADMIN) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $estab_code = $_SESSION['estab_details']['est_code'];
        $state_code = $_SESSION['estab_details']['state_code'];
        $efiling_for_type_id = $_SESSION['estab_details']['efiling_for_type_id'];
        $court_fee_type = $this->efiling_webservices->getCourtFeeType($estab_code, $efiling_for_type_id, $state_code, $state_code);
        $court_fee_type_result = "";
        if (count($court_fee_type)) {
            $court_fee_type_result .= '<option value=""> Select</option>';
            foreach ($court_fee_type as $fee_type) {
                $court_fee_type_result .= '<option value = "' . url_encryption(htmlentities($fee_type->TYPE . '#$' . $fee_type->NAME, ENT_QUOTES)) . '">' . htmlentities($fee_type->NAME, ENT_QUOTES) . ' </option>';
            }
        } else {
            $court_fee_type_result .= '<option value=""> Select</option>';
        }
        echo $court_fee_type_result;
    }

    public function get_estab_details_value() {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        if (!isset($_POST['court_type']) || empty($_POST['court_type']) || !(env('ENABLE_FOR_HC') && env('ENABLE_FOR_ESTAB'))) {
            if (env('ENABLE_FOR_HC')) {
                $court_type = url_encryption(E_FILING_FOR_HIGHCOURT);
            } elseif (env('ENABLE_FOR_ESTAB')) {
                $court_type = url_encryption(E_FILING_FOR_ESTABLISHMENT);
            }
        } else {
            $court_type = $_POST['court_type'];
        }
        $estab_code_id = url_decryption(escape_data($_POST['est_code']));
        $estab_array = explode('#$', $estab_code_id);
        $efiling_for_id = $estab_array[0];
        $efiling_for_type_id = url_decryption(escape_data($court_type));
        if ($efiling_for_type_id == E_FILING_FOR_ESTABLISHMENT) {
            $_SESSION['filtered_state_id'] = url_decryption(escape_data($_POST['state_id']));
            $district_code = url_decryption(escape_data($_POST['district_id']));
            $district_code_array = explode('#$', $district_code);
            $_SESSION['filtered_distt_id'] = $district_code_array[0];
            $_SESSION['filtered_estab_id'] = $efiling_for_id;
        }
        if ($efiling_for_id && $efiling_for_type_id) {
            $this->Common_model->get_establishment_details($efiling_for_type_id, $efiling_for_id);
        }
    }

    public function add_establishment() {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        if ($this->session->userdata['login']['ref_m_usertype_id'] != USER_SUPER_ADMIN) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $hc = explode('#$', url_decryption($_POST['high_court_id']));
        $hcid = $hc[0];
        $hc_name = $hc[1];
        $hc_code = $hc[2];
        $est_id = url_decryption($_POST['est_id']);
        $ref_m_states_id = explode('#$', url_decryption($_POST['state_id']));
        $openapi_state_id = $ref_m_states_id[0];
        $state_id = $ref_m_states_id[1];
        $hc_id = $ref_m_states_id[2];
        $dist_code_array = explode('#$', url_decryption($_POST['district']));
        $dist_code = $dist_code_array[0];
        $dist_name = $dist_code_array[1];
        $estab_array = explode('#$', url_decryption($_POST['establishment_list']));
        $estab_id = $estab_array[0];
        $estab_name = $estab_array[1];
        $payment_method = trim(url_decryption($_POST['payment_method']));
        $appip = $_POST['appip'];
        if (empty($est_id) && empty($hcid)) {
            $validate_nums = validate_number($openapi_state_id, TRUE, 1, 6, 'State');
            if ($validate_nums['response'] == FALSE) {
                $this->session->setFlashData('message', "<div class='alert alert-danger fade in'> " . htmlentities($validate_nums['msg']['field_name'], ENT_QUOTES) . "</div>");
                return redirect()->to(base_url('admin/supadmin/establishment'));
                exit(0);
            }
            $validate_nums = validate_number($state_id, TRUE, 1, 6, 'State');
            if ($validate_nums['response'] == FALSE) {
                $this->session->setFlashData('message', "<div class='alert alert-danger fade in'>" . htmlentities($validate_nums['msg']['field_name'], ENT_QUOTES) . "</div>");
                return redirect()->to(base_url('admin/supadmin/establishment'));
                exit(0);
            }
            $validate_nums = validate_number($hc_id, TRUE, 1, 6, 'State');
            if ($validate_nums['response'] == FALSE) {
                $this->session->setFlashData('message', "<div class='alert alert-danger fade in'>" . htmlentities($validate_nums['msg']['field_name'], ENT_QUOTES) . "</div>");
                return redirect()->to(base_url('admin/supadmin/establishment'));
                exit(0);
            }
            $validate_nums = validate_number($dist_code, TRUE, 1, 6, 'District');
            if ($validate_nums['response'] == FALSE) {
                $this->session->setFlashData('message', "<div class='alert alert-danger fade in'> " . htmlentities($validate_nums['msg']['field_name'], ENT_QUOTES) . "</div>");
                return redirect()->to(base_url('admin/supadmin/establishment'));
                exit(0);
            }
            $validate_character = validate_names($dist_name, TRUE, 1, 50, 'District Name');
            if ($validate_character['response'] == FALSE) {
                $this->session->setFlashData('message', "<div class='alert alert-danger fade in'>" . htmlentities($validate_character['msg']['field_name'], ENT_QUOTES) . "</div>");
                return redirect()->to(base_url('admin/supadmin/establishment'));
                exit(0);
            }
            
            $validate_character = validate_names($estab_name, TRUE, 1, 100, 'Establishment Name');
            if ($validate_character['response'] == FALSE) {
                $this->session->setFlashData('message', "<div class='alert alert-danger fade in'>" . htmlentities($validate_character['msg']['field_name'], ENT_QUOTES) . "</div>");
                return redirect()->to(base_url('admin/supadmin/establishment'));
                exit(0);
            }
        } elseif (!empty($hcid)) {
            $validate_nums = validate_number($hcid, TRUE, 1, 6, 'High court');
            if ($validate_nums['response'] == FALSE) {
                $this->session->setFlashData('message', "<div class='alert alert-danger fade in'> " . htmlentities($validate_nums['msg']['field_name'], ENT_QUOTES) . "</div>");
                return redirect()->to(base_url('admin/supadmin/establishment'));
                exit(0);
            }
        }
        if (empty($payment_method)) {
            $this->session->setFlashData('message', "<div class='alert alert-danger fade in'>Choose any Payment Method.</div>");
            return redirect()->to(base_url('admin/supadmin/establishment'));
            exit(0);
        }if (empty($_SESSION['login']['state_payment_gateway'])) {
            $payment_method == 2;
        }
        if ($payment_method == 2) {
            $payment_mode = '0';
        } elseif ($payment_method == 1) {
            $payment_mode = 1;
        }
        if (empty($_POST['efil_add']) && empty($_POST['cde_add'])) {
            $efil_add = 'TRUE';
            $cde_add = 'FALSE';
        } else {
            if ($_POST['efil_add'] == 1 && empty($_POST['cde_add'])) {
                $efil_add = 'TRUE';
                $cde_add = 'FALSE';
            } elseif ($_POST['cde_add'] == 2 && empty($_POST['efil_add'])) {
                $cde_add = 'TRUE';
                $efil_add = 'FALSE';
            } elseif ($_POST['cde_add'] == 2 && $_POST['efil_add'] == 1) {
                $cde_add = 'TRUE';
                $efil_add = 'TRUE';
            }
        }
        if ($_POST['pet_state'] == 'on') {
            $pet_st = 'TRUE';
        } else {
            $pet_st = 'FALSE';
        }
        if ($_POST['pet_district'] == 'on') {
            $pet_dist = 'TRUE';
        } else {
            $pet_dist = 'FALSE';
        }
        if ($_POST['pet_email'] == 'on') {
            $pet_email = 'TRUE';
        } else {
            $pet_email = 'FALSE';
        }
        if ($_POST['pet_mobile'] == 'on') {
            $pet_mob = 'TRUE';
        } else {
            $pet_mob = 'FALSE';
        }
        if ($_POST['pet_exp_state'] == 'on') {
            $pet_exp_st = 'TRUE';
        } else {
            $pet_exp_st = 'FALSE';
        }
        if ($_POST['pet_exp_dist'] == 'on') {
            $pet_exp_dist = 'TRUE';
        } else {
            $pet_exp_dist = 'FALSE';
        }
        if ($_POST['pet_exp_email'] == 'on') {
            $pet_exp_email = 'TRUE';
        } else {
            $pet_exp_email = 'FALSE';
        }
        if ($_POST['pet_exp_mob'] == 'on') {
            $pet_exp_mob = 'TRUE';
        } else {
            $pet_exp_mob = 'FALSE';
        }
        if ($_POST['res_state'] == 'on') {
            $res_st = 'TRUE';
        } else {
            $res_st = 'FALSE';
        }
        if ($_POST['res_district'] == 'on') {
            $res_dist = 'TRUE';
        } else {
            $res_dist = 'FALSE';
        }
        if ($_POST['res_email'] == 'on') {
            $res_email = 'TRUE';
        } else {
            $res_email = 'FALSE';
        }
        if ($_POST['res_mobile'] == 'on') {
            $res_mob = 'TRUE';
        } else {
            $res_mob = 'FALSE';
        }
        if ($_POST['res_exp_state'] == 'on') {
            $res_exp_st = 'TRUE';
        } else {
            $res_exp_st = 'FALSE';
        }
        if ($_POST['res_exp_dist'] == 'on') {
            $res_exp_dist = 'TRUE';
        } else {
            $res_exp_dist = 'FALSE';
        }
        if ($_POST['res_exp_email'] == 'on') {
            $res_exp_email = 'TRUE';
        } else {
            $res_exp_email = 'FALSE';
        }
        if ($_POST['res_exp_mob'] == 'on') {
            $res_exp_mob = 'TRUE';
        } else {
            $res_exp_mob = 'FALSE';
        }if ($_POST['ia_purpose_list'] == 'on') {
            $ia_purpose_list = 'TRUE';
        } else {
            $ia_purpose_list = 'FALSE';
        }
        if ($payment_mode == 1 && $_SESSION['login']['state_payment_gateway'] == env('HR_GRAS_PAYMENT_GATEWAY_CODE')) {
            $dto = trim($_POST['dto']);
            $sto = trim($_POST['sto']);
            if ($sto == 0 || empty($sto)) {
                $sto = '00';
            } else {
                $sto = trim($_POST['sto']);
            }
            $ddo = trim($_POST['ddo']);
            $depcode = trim(strtoupper($_POST['depcode']));
            $city = trim($_POST['city']);
            $pincode = trim($_POST['pincode']);
            $officename = $dto . $sto . $ddo;
            $Securityemail = "";
            $Securityphone = "";
            $Remarks = "eFiling Payment";
            $SCHEMECOUNT = "1";
            $SCHEMENAME = env('HR_GRASS_PAYMENT_SCHEMENAME');
            if ($payment_mode == 1) {
                $payment_parameter = (object) array();
                $payment_parameter->DTO = $dto;
                $payment_parameter->STO = $sto;
                $payment_parameter->DDO = $ddo;
                $payment_parameter->Deptcode = $depcode;
                $payment_parameter->Cityname = $city;
                $payment_parameter->Officename = $officename;
                $payment_parameter->PINCODE = $pincode;
                $payment_parameter->Securityemail = "";
                $payment_parameter->Securityphone = "";
                $payment_parameter->Remarks = $Remarks;
                $payment_parameter->SCHEMECOUNT = $SCHEMECOUNT;
                $payment_parameter->SCHEMENAME = $SCHEMENAME;
                $payment_gateway_params = json_encode($payment_parameter);
            } else {
                $payment_gateway_params = '';
            }
            if (!empty($dto)) {
                $validate_nums = validate_number($dto, TRUE, 1, 2, 'DTO');
                if ($validate_nums['response'] == FALSE) {
                    $this->session->setFlashData('message', "<div class='alert alert-danger fade in'>" . htmlentities($validate_nums['msg']['field_name'], ENT_QUOTES) . "</div>");
                    return redirect()->to(base_url('admin/supadmin/establishment'));
                    exit(0);
                }
            }
            if (!empty($sto)) {
                if ($sto != '00') {
                    $validate_nums = validate_number($sto, TRUE, 1, 2, 'STO');
                    if ($validate_nums['response'] == FALSE) {
                        $this->session->setFlashData('message', "<div class='alert alert-danger fade in'> " . htmlentities($validate_nums['msg']['field_name'], ENT_QUOTES) . "</div>");
                        return redirect()->to(base_url('admin/supadmin/establishment'));
                        exit(0);
                    }
                }
            }
            if (!empty($ddo)) {
                $validate_nums = validate_number($ddo, TRUE, 1, 4, 'DDO');
                if ($validate_nums['response'] == FALSE) {
                    $this->session->setFlashData('message', "<div class='alert alert-danger fade in'>" . htmlentities($validate_nums['msg']['field_name'], ENT_QUOTES) . "</div>");
                    return redirect()->to(base_url('admin/supadmin/establishment'));
                    exit(0);
                }
            }
            if (!empty($depcode)) {
                if (preg_match('/[^A-Za-z\s]/i', $depcode) || $depcode == NULL) {
                    $this->session->setFlashData('message', "<div class='alert alert-danger fade in'>Enter Valid Department Code</div>");
                    return redirect()->to(base_url('admin/supadmin/establishment'));
                    exit(0);
                }
            }
            if (!empty($city)) {
                $validate_character = validate_names($city, FALSE, 1, 5, 'City');
                if ($validate_character['response'] == FALSE) {
                    $this->session->setFlashData('message', "<div class='alert alert-danger fade in'>" . htmlentities($validate_character['msg']['field_name'], ENT_QUOTES) . "</div>");
                    return redirect()->to(base_url('admin/supadmin/establishment'));
                    exit(0);
                }
            }
            if (!empty($pincode)) {
                $validate_nums = validate_number($pincode, FALSE, 1, 6, 'Pincode');
                if ($validate_nums['response'] == FALSE) {
                    $this->session->setFlashData('message', "<div class='alert alert-danger fade in'> " . htmlentities($validate_nums['msg']['field_name'], ENT_QUOTES) . "</div>");
                    return redirect()->to(base_url('admin/supadmin/establishment'));
                    exit(0);
                }
            }
        }
        if ($payment_mode == 1 && $_SESSION['login']['state_payment_gateway'] == env('MH_GRAS_PAYMENT_GATEWAY_CODE')) {
            $dept = trim(strtoupper($_POST['dept']));
            $tcode = trim(strtoupper($_POST['tcode']));
            $office_code = trim(strtoupper($_POST['office_code']));
            $major = trim(strtoupper($_POST['major']));
            $hoa1 = trim(strtoupper($_POST['hoa1']));
            $subsystem = env('MH_GRAS_PAYMENT_SUBSYSTEM'); //'Principaljudge';
            $payment_type = '03';
            if (!preg_match("/^[A-Za-z]*$/", $dept) || strlen($dept) < 3 || strlen($dept) > 3 || empty($dept)) {
                $this->session->setFlashData('message', "<div class='alert alert-danger fade in'>" . htmlentities("Enter Valid Department Code.Length Only 3 Characters.", ENT_QUOTES) . "</div>");
                return redirect()->to(base_url('admin/supadmin/establishment'));
                exit(0);
            }
            if (preg_match("/[^a-z_\-0-9]/i", $tcode) || strlen($tcode) > 4 || empty($tcode)) {
                $this->session->setFlashData('message', "<div class='alert alert-danger fade in'>" . htmlentities("Enter Valid Treasury Code.Length Only 4 Characters.", ENT_QUOTES) . "</div>");
                return redirect()->to(base_url('admin/supadmin/establishment'));
                exit(0);
            }
            if (preg_match("/[^a-z_\-0-9]/i", $office_code) || strlen($office_code) > 6 || strlen($office_code) < 6 || empty($office_code)) {
                $this->session->setFlashData('message', "<div class='alert alert-danger fade in'>" . htmlentities("Enter Valid Office Code.Length Only 6 Characters.", ENT_QUOTES) . "</div>");
                return redirect()->to(base_url('admin/supadmin/establishment'));
                exit(0);
            }
            if (preg_match('/[^0-9a-zA-Z\s.&_]/i', $major) || strlen($major) > 4 || strlen($major) < 4 || empty($major)) {
                $this->session->setFlashData('message', "<div class='alert alert-danger fade in'> Enter Valid Major Head. Max 4 Characters.</div>");
                return redirect()->to(base_url('admin/supadmin/establishment'));
                exit(0);
            }
            if (preg_match("/[^a-z_\-0-9-]/i", $hoa1) || strlen($hoa1) > 16 || empty($hoa1)) {
                $this->session->setFlashData('message', "<div class='alert alert-danger fade in'> " . htmlentities("Enter Valid HOA1.10 digit scheme code Hyphen 11th Position 12 th and 13th will be Object head. ", ENT_QUOTES) . "</div>");
                return redirect()->to(base_url('admin/supadmin/establishment'));
                exit(0);
            }
            $payment_gateway_params = "DEPT_CODE=" . $dept . "||PAYMENT_TYPE=" . $payment_type . "||TREASURY_CODE=$tcode||OFFICE_CODE=$office_code||PERIOD=O||MAJOR_HEAD=$major||HOA1=$hoa1||SUB_SYSTEM=$subsystem";
        }
        if ($payment_mode == 1 && $_SESSION['login']['state_payment_gateway'] == env('SHCIL_PAYMENT_GATEWAY_CODE')) {
            if (env('ENABLE_FOR_HC') == TRUE && !empty($hc_code)) {
                $mjhead = 'HC-Fee';
                $shead = trim(strtoupper($hc_name));
                $smjhead = "";
                $minhead = $hc_code;
            } else if (env('ENABLE_FOR_ESTAB') == TRUE) {
                $mjhead = 'DC-Fee';
                $shead = trim($estab_name);
                $smjhead = trim(strtoupper($dist_name));
                $minhead = trim($estab_id);
            }
            $payment_parameter->login = env('STOCK_HOLDING_LOGIN');
            $payment_parameter->password = env('STOCK_HOLDING_PASSWORD');
            $payment_parameter->Product = env('STOCK_HOLDING_PRODUCT');
            $payment_parameter->ReqHashKey = env('STOCK_HOLDING_REQHASHKEY');
            $payment_parameter->RespHashKey = env('STOCK_HOLDING_RESPHASHKEY');
            $payment_parameter->txnTyp = 'NB';
            $payment_parameter->scamt = "0";
            $payment_parameter->udf1 = env('STOCK_HOLDING_UDF1');
            $payment_parameter->udf2 = env('STOCK_HOLDING_UDF2');
            $payment_parameter->udf3 = env('STOCK_HOLDING_UDF3');
            $payment_parameter->udf4 = env('STOCK_HOLDING_UDF4');
            $payment_parameter->udf5 = env('STOCK_HOLDING_UDF5');
            $payment_parameter->udf6 = $mjhead;
            $payment_parameter->udf7 = $smjhead;
            $payment_parameter->udf8 = $minhead;
            $payment_parameter->udf9 = $shead;
            $payment_gateway_params = json_encode($payment_parameter);
        }
        if (empty($cde_add) && empty($efil_add)) {
            $this->session->setFlashData('message', "<div class='alert alert-danger fade in'> Choose any Check box (Efiling or CDE).</div>");
            return redirect()->to(base_url('admin/supadmin/establishment'));
            exit(0);
        }
        if (strlen($appip) > 15) {
            $this->session->setFlashData('message', "<div class='alert alert-danger fade in'>Enter valid ip address</div>");
            return redirect()->to(base_url('admin/supadmin/establishment'));
            exit(0);
        }
        if (!preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\z/', $appip)) {
            $this->session->setFlashData('message', "<div class='alert alert-danger fade in'>Enter valid ip address</div>");
            return redirect()->to(base_url('admin/supadmin/establishment'));
            exit(0);
        }
        if ($payment_mode == 1) {
            if ($_SESSION['login']['state_payment_gateway'] == env('SBI_PAYMENT_GATEWAY_CODE')) {
                $gateway_code = env('SBI_PAYMENT_GATEWAY_CODE');
            } elseif ($_SESSION['login']['state_payment_gateway'] == env('MH_GRAS_PAYMENT_GATEWAY_CODE')) {
                $gateway_code = env('MH_GRAS_PAYMENT_GATEWAY_CODE');
            } elseif ($_SESSION['login']['state_payment_gateway'] == env('HR_GRAS_PAYMENT_GATEWAY_CODE')) {
                $gateway_code = env('HR_GRAS_PAYMENT_GATEWAY_CODE');
            } elseif ($_SESSION['login']['state_payment_gateway'] == env('SHCIL_PAYMENT_GATEWAY_CODE')) {
                $gateway_code = env('SHCIL_PAYMENT_GATEWAY_CODE');
            } elseif ($_SESSION['login']['state_payment_gateway'] == env('CG_GRAS_PAYMENT_GATEWAY_CODE')) {
                $gateway_code = env('CG_GRAS_PAYMENT_GATEWAY_CODE');
            }
        } else {
            $gateway_code = '';
        }
        if (empty($hcid)) {
            if (empty($est_id)) {
                $distdata = array(
                    'ref_m_tbl_states_id' => $state_id,
                    'ref_m_hc_id' => $hc_id,
                    'dist_code' => $dist_code,
                    'dist_name' => $dist_name
                );
                $estabdata = array(
                    'state_code' => $state_id,
                    'estname' => $estab_name,
                    'est_code' => $estab_id,
                    'efiling_estab' => $efil_add,
                    'cde_estab' => $cde_add,
                    'payment_gateway_params' => $payment_gateway_params,
                    'is_pet_state_required' => $pet_st,
                    'is_pet_district_required' => $pet_dist,
                    'is_res_state_required' => $res_st,
                    'is_res_district_required' => $res_dist,
                    'is_pet_mobile_required' => $pet_mob,
                    'is_pet_email_required' => $pet_email,
                    'is_res_mobile_required' => $res_mob,
                    'is_res_email_required' => $res_email,
                    'is_extra_pet_state_required' => $pet_exp_st,
                    'is_extra_pet_district_required' => $pet_exp_dist,
                    'is_extra_pet_mobile_required' => $pet_exp_mob,
                    'is_extra_pet_email_required' => $pet_exp_email,
                    'is_extra_res_state_required' => $res_exp_st,
                    'is_extra_res_district_required' => $res_exp_dist,
                    'is_extra_res_mobile_required' => $res_exp_mob,
                    'is_extra_res_email_required' => $res_exp_mob,
                    'enable_payment_gateway' => $payment_mode,
                    'created_by' => $_SESSION['login']['id'],
                    'ip_details' => $appip,
                    'pg_request_function' => $gateway_code,
                    'pg_response_function' => $gateway_code,
                    'is_purpose_of_listing_required' => $ia_purpose_list,
                    'created_by_ip' => $_SERVER['REMOTE_ADDR'],
                );
                $get_estb = $this->Supadmin_model->get_establishment_code($estab_id);
                if (!$get_estb) {
                    $this->session->setFlashData('message', "<div class='alert alert-danger fade in'> Establishment Already Exist.</div>");
                    return redirect()->to(base_url('admin/supadmin/establishment'));
                } else {
                    $result = $this->Supadmin_model->add_establishment_detail($state_id, $dist_code, $distdata, $estabdata);
                    if ($result) {
                        $this->session->setFlashData('message', "<div class='alert alert-success fade in'> Establishment Inserted Successfully.</div>");
                        return redirect()->to(base_url('admin/supadmin/establishment'));
                    } else {
                        $this->session->setFlashData('message', "<div class='alert alert-danger fade in'> Some thing wrong! Please try again later.</div>");
                        return redirect()->to(base_url('admin/supadmin/establishment'));
                    }
                }
            } else {
                $updatestabdata = array(
                    'efiling_estab' => $efil_add,
                    'cde_estab' => $cde_add,
                    'payment_gateway_params' => $payment_gateway_params,
                    'is_pet_state_required' => $pet_st,
                    'is_pet_district_required' => $pet_dist,
                    'is_res_state_required' => $res_st,
                    'is_res_district_required' => $res_dist,
                    'is_pet_mobile_required' => $pet_mob,
                    'is_pet_email_required' => $pet_email,
                    'is_res_mobile_required' => $res_mob,
                    'is_res_email_required' => $res_email,
                    'is_extra_pet_state_required' => $pet_exp_st,
                    'is_extra_pet_district_required' => $pet_exp_dist,
                    'is_extra_pet_mobile_required' => $pet_exp_mob,
                    'is_extra_pet_email_required' => $pet_exp_email,
                    'is_extra_res_state_required' => $res_exp_st,
                    'is_extra_res_district_required' => $res_exp_dist,
                    'is_extra_res_mobile_required' => $res_exp_mob,
                    'is_extra_res_email_required' => $res_exp_mob,
                    'enable_payment_gateway' => $payment_mode,
                    'ip_details' => $appip,
                    'pg_request_function' => $gateway_code,
                    'pg_response_function' => $gateway_code,
                    'is_purpose_of_listing_required' => $ia_purpose_list
                );
            }
        } else {
            $updatestabdata = array(
                'efiling_estab' => $efil_add,
                'cde_estab' => $cde_add,
                'payment_gateway_params' => $payment_gateway_params,
                'is_pet_state_required' => $pet_st,
                'is_pet_district_required' => $pet_dist,
                'is_res_state_required' => $res_st,
                'is_res_district_required' => $res_dist,
                'is_pet_mobile_required' => $pet_mob,
                'is_pet_email_required' => $pet_email,
                'is_res_mobile_required' => $res_mob,
                'is_res_email_required' => $res_email,
                'is_extra_pet_state_required' => $pet_exp_st,
                'is_extra_pet_district_required' => $pet_exp_dist,
                'is_extra_pet_mobile_required' => $pet_exp_mob,
                'is_extra_pet_email_required' => $pet_exp_email,
                'is_extra_res_state_required' => $res_exp_st,
                'is_extra_res_district_required' => $res_exp_dist,
                'is_extra_res_mobile_required' => $res_exp_mob,
                'is_extra_res_email_required' => $res_exp_mob,
                'enable_payment_gateway' => $payment_mode,
                'ip_details' => $appip,
                'update_by' => $_SESSION['login']['id'],
                'pg_request_function' => $gateway_code,
                'pg_response_function' => $gateway_code,
                'is_purpose_of_listing_required' => $ia_purpose_list,
                'is_active' => TRUE
            );
        }
        if (!empty($est_id)) {
            $result = $this->Supadmin_model->update_establishment_detail($est_id, $updatestabdata);
            if ($result) {
                $this->session->setFlashData('message', "<div class='alert alert-success fade in'> Establishment Updated Successfully.</div>");
                return redirect()->to(base_url('admin/supadmin/establishment'));
            } else {
                $this->session->setFlashData('message', "<div class='text-danger'> Some thing wrong! Please try again later.</div>");
                return redirect()->to(base_url('admin/supadmin/establishment'));
            }
        }if (!empty($hcid)) {
            $result = $this->Supadmin_model->update_hc_detail($hcid, $updatestabdata);
            if ($result) {
                $this->session->setFlashData('message', "<div class='alert alert-success fade in'> High court Updated Successfully.</div>");
                return redirect()->to(base_url('admin/supadmin/establishment'));
            } else {
                $this->session->setFlashData('message', "<div class='text-danger'> Some thing wrong! Please try again later.</div>");
                return redirect()->to(base_url('admin/supadmin/establishment'));
            }
        }
    }

    function edit_establishment($id) {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        if ($this->session->userdata['login']['ref_m_usertype_id'] != USER_SUPER_ADMIN) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $est_id = explode('#$', url_decryption($id));
        $court = $est_id[0];
        $estid = $est_id[1];
        if (ENABLE_FOR_HC == TRUE && $court == 'HC') {
            $data['estid_details'] = $this->Supadmin_model->get_hc_payment_method_detail($estid);
            $data['high_court_list'] = $this->Supadmin_model->get_superadmin_high_court_list();
        } if (ENABLE_FOR_ESTAB == TRUE && $court == 'DC') {
            $data['state_list'] = $this->Supadmin_model->get_admin_states();
            $data['estab_details'] = $this->Supadmin_model->get_estab_details();
            $data['estid_details'] = $this->Supadmin_model->get_estabid_details($estid);
            $data['distt_list'] = $this->efiling_webservices->getOpenAPIDistrict($data['estid_details'][0]['openapi_state_code']);
            $data['est_list'] = $this->efiling_webservices->getOpenAPIEstablishment($data['estid_details'][0]['openapi_state_code'], $data['estid_details'][0]['dist_code']);
        }
        return $this->render('admin.add_establishment_view', $data);
    }

    public function action($action_id, $status) {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        if ($this->session->userdata['login']['ref_m_usertype_id'] != USER_SUPER_ADMIN) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        if (empty($action_id) || empty($status)) {
            $this->session->setFlashData('message', "<div class='alert alert-danger fade in'>Something Wrong! Please Try again letter.</div>");
            return redirect()->to(base_url('admin/supadmin/establishment'));
        }
        $action_id = url_decryption($action_id);
        $status = url_decryption(trim($status));
        if (preg_match('/[^0-9]/i', $action_id)) {
            $this->session->setFlashData('message', "<div class='alert alert-danger fade in'>Something Wrong! Please Try again letter1.</div>");
            return redirect()->to(base_url('admin/supadmin/establishment'));
        }
        $result = $this->Supadmin_model->action_taken($action_id, $status);
        if ($result) {
            $this->session->setFlashData('message', "<div class='alert alert-success fade in'> Status Updated Successfully.</div>");
            return redirect()->to(base_url('admin/supadmin/establishment'));
        } else {
            $this->session->setFlashData('message', "<div class='alert alert-danger fade in'>Something Wrong! Please Try again letter.</div>");
            return redirect()->to(base_url('admin/supadmin/establishment'));
        }
    }

    public function eshtablishment_list_by_dist_code_live() {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        $distt_id_array = explode('#$', url_decryption($_POST['get_distt_id']));
        $ref_m_distt_id = $distt_id_array[0];
        $distt_code = $distt_id_array[1];
        if (isset($ref_m_distt_id) && preg_match("/^[0-9]*$/", $ref_m_distt_id) && strlen($ref_m_distt_id) <= INTEGER_FIELD_LENGTH) {
            if (!empty($ref_m_distt_id)) {
                $result = $this->Supadmin_model->get_eshtablishment_list_live($ref_m_distt_id);
                if (count($result)) {
                    echo '<option value=""> Select Establishment </option>';
                    foreach ($result as $dataActs) {
                        $value = $dataActs->est_code;
                        echo '<option value="' . htmlentities(url_encryption($value), ENT_QUOTES) . '">' . '' . htmlentities(strtoupper($dataActs->estname), ENT_QUOTES) . '</option>';
                    }
                } else {
                    echo '<option value=""> Select Establishment </option>';
                }
            }
        } else {
            echo '<option value=""> Select  Establishment</option>';
        }
    }

    function update_live_status() {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        $hc_id = url_decryption($_POST['high_court_id']);
        $state_id = url_decryption($_POST['state_id']);
        $district = url_decryption($_POST['district']);
        $establishment_list = url_decryption($_POST['establishment_list']);
        if (!empty($hc_id)) {
            $result = $this->Supadmin_model->update_hc_live_status($hc_id);
            if ($result) {
                echo '2@@@' . htmlentities('High Court enabled Live successfully', ENT_QUOTES);
            } else {
                echo '1@@@' . htmlentities(' Failed. Please Try again', ENT_QUOTES);
            }
        } elseif (!empty($establishment_list)) {
            $result = $this->Supadmin_model->update_establishment_live_status($establishment_list);
            if ($result) {
                echo '2@@@' . htmlentities('Court Establishment enabled Live successfully', ENT_QUOTES);
            } else {
                echo '1@@@' . htmlentities(' Failed. Please Try again', ENT_QUOTES);
            }
        }
    }

    public function get_payment_method_eshtablishment_list() {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        $establishment_list = url_decryption($_POST['establishment_list']);
        $result = $this->Supadmin_model->get_establishment_payment_method_detail($establishment_list);
        if ($result) {
            echo $result[0]->enable_payment_gateway;
        }
    }

    public function get_payment_method_hc() {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        $high_court_list = url_decryption($_POST['high_court_list']);
        $result = $this->Supadmin_model->get_hc_payment_method_detail($high_court_list);
        if ($result) {
            echo $result[0]->enable_payment_gateway;
        }
    }

    function admin_list() {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        $data['admin_list'] = $this->Supadmin_model->get_admin_users($request_type, $account_status, $request_status);
        return $this->render('admin.adv_admin_list', $data);
    }

    function profile() {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        $uri = service('uri');
        $id = url_decryption($uri->getSegment(3));
        if ($_SESSION['login']) {
            unset($_SESSION['email_id_for_updation']);
            unset($_SESSION['mobile_no_for_updation']);
            $data['profile'] = $this->Advocate_model->get_full_profile($id);
            $data['account_status'] = $this->show_estab_profile->get_account_status($id);
            $data['show_estab'] = $this->show_estab_profile->show_estab_profile($id);
            if ($_SESSION['login']['ref_m_usertype_id'] == USER_IN_PERSON || $_SESSION['login']['ref_m_usertype_id'] == USER_ADVOCATE || $_SESSION['login']['ref_m_usertype_id'] == USER_DEPARTMENT || $_SESSION['login']['ref_m_usertype_id'] == USER_CLERK) {
            } else {
            }
            return $this->render('profile.profile_view', $data);
        } else {
            $_SESSION['MSG'] = message_show("fail", "Some things wrong !.Please try after some time!");
            return redirect()->to(base_url('/'));
            exit(0);
        }
    }

    function deactivate_adv() {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        $uri = service('uri');
        $user_id = url_decryption($uri->getSegment(3));
        $account_status = array(
            'account_status' => ACCOUNT_STATUS_DEACTIVE
        );
        $result = $this->Supadmin_model->deactivate_account($account_status, $user_id);
        if ($result) {
            $_SESSION['MSG'] = message_show("success", "Account Deactivated Successfully !");
            header('Location: ' . isset($_SERVER['HTTP_REFERER']));
            exit(0);
        } else {
            $_SESSION['MSG'] = message_show("fail", "Account Deactivated Fail !");
            header('Location: ' . isset($_SERVER['HTTP_REFERER']));
            exit(0);
        }
    }

    public function department() {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        if ($_SESSION['login']['ref_m_usertype_id'] != USER_SUPER_ADMIN) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $data['depart_user_list'] = $this->Department_model->get_superadmin_department($_SESSION['login']['id']);
        $data['states'] = $this->efiling_webservices->getOpenAPIState();
        return $this->render('department.add_department_view', $data);
    }

}