<?php
namespace App\Controllers\NewCase;

use App\Controllers\BaseController;
use App\Libraries\webservices\Efiling_webservices;
use App\Models\Admin\EfilingActionModel;
use App\Models\Citation\CitationModel;
use App\Models\Common\CommonModel;
use App\Models\DocumentIndex\DocumentIndexSelectModel;
use App\Models\GetCISStatus\GetCISStatusModel;
use App\Models\NewCase\ActSectionsModel;
use App\Models\NewCase\DropdownListModel;
use App\Models\NewCase\GetDetailsModel;
use App\Models\NewCase\ViewModel;
use Config\Services;
use stdClass;

class Ajaxcalls extends BaseController {

    protected $Dropdown_list_model;
    protected $Get_details_model;
    protected $efiling_webservices;
    protected $DocumentIndex_Select_model;
    protected $Common_model;
    protected $View_model;
    protected $Act_sections_model;
    protected $Citation_model;
    protected $Get_CIS_Status_model;
    protected $Efiling_action_model;

    public function __construct() {
        parent::__construct();
        $this->Dropdown_list_model = new DropdownListModel();
        $this->Get_details_model = new GetDetailsModel();
        $this->DocumentIndex_Select_model = new DocumentIndexSelectModel();
        $this->efiling_webservices = new Efiling_webservices();
        $this->Common_model = new CommonModel();
        $this->View_model = new ViewModel();
        $this->Act_sections_model = new ActSectionsModel();
        $this->Citation_model = new CitationModel();
        $this->Get_CIS_Status_model = new GetCISStatusModel();
        $this->Efiling_action_model = new EfilingActionModel();
    }
    public function getAddressByPincode(){
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            return response()->redirect(base_url('/'));
            exit(0);
        }
        $enpincode = url_encryption($_POST['pincode']);
        $pincode = url_decryption($enpincode);
        //$pincode=226024;
        $address = $this->Dropdown_list_model->getPincodeDetails($pincode);
        echo json_encode($address);
    }

    public function get_districts() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return redirect()->to(base_url('/'));
            exit(0);
        }

        $state_id = url_decryption($_POST['state_id']);
        $districts = $this->Dropdown_list_model->get_districts_list($state_id);
        $dropDownOptions = '<option value="">Select District</option>';
        foreach ($districts as $district) {
            $dropDownOptions .= '<option value="' . escape_data(url_encryption($district->id_no)) . '">' . escape_data(strtoupper($district->name)) . '</option>';
        }

        echo $dropDownOptions;
    }


    public function getSelectedDistricts() {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            return response()->redirect(base_url('/'));
            exit(0);
        }
        $stateId = url_decryption($_POST['state_id']);
        $districts = $this->Dropdown_list_model->get_districts_list($stateId);
        $districtIdName = array();
        foreach ($districts as $district) {
            $tempArr = array();
            $tempArr['id'] = escape_data(url_encryption($district->id_no)) ;
            $tempArr['district_name'] = escape_data(strtoupper($district->name));
            $districtIdName[] = (object)$tempArr;
        }
        echo json_encode($districtIdName);
    }
    public function get_case_parties_list() {

        $p_r_type = $_POST['p_r_type'];

        $selected_parent_party = escape_data(url_decryption($_POST['selected_parent_party']));

        $this->form_validation->set_rules('p_r_type', 'Party as', 'required|in_list[P,R]');

        $this->form_validation->set_error_delimiters('', '');
        if (!$this->form_validation->run()) {
            echo '3@@@';
            echo form_error('p_r_type');
            exit(0);
        }

        $parties_list = $this->Get_details_model->get_case_parties_details($_SESSION['efiling_details']['registration_id'], array('p_r_type' => $p_r_type, 'm_a_type' => NULL, 'party_id' => NULL, 'view_lr_list' => FALSE));
        //echo "<pre>"; print_r($parties_list); die;
        $dropDownOptions = '<option value="">Select Party</option>';

        foreach ($parties_list as $party) {

            $sel = ($selected_parent_party == $party['party_id'] ) ? "selected=selected" : '';

            $m_a_type = $party['m_a_type'] == 'M' ? ' (M' . $party['p_r_type'] . ')' : ' (E' . $party['p_r_type'] . ')';

            $dropDownOptions .= '<option ' . $sel . ' value="' . escape_data(url_encryption($party['party_id'] . '##' . $party['p_r_type'])) . '">' . escape_data(strtoupper($party['party_name'] . $m_a_type)) . '</option>';
        }

        echo $dropDownOptions;
    }

    public function get_org_departments() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $party_is = $_POST['party_is'];
        $selected_org_st_id = $_POST['selected_org_st_id'];
        $selected_dept_id = $_POST['selected_dept_id'];
        $selState_not_in_list = ($selected_org_st_id == url_encryption(0)) ? "selected=selected" : '';
        $selDept_not_in_list = ($selected_dept_id == url_encryption(0)) ? "selected=selected" : '';
        $dept_list = $this->Dropdown_list_model->get_departments_list($party_is);
        $stateCentralDept = $this->Dropdown_list_model->getStateCentralDept();
        if ($party_is != 'D3') {
            $dropDownOptionsState = '<option  value="">Select Orgnisation State</option>';
            $selx = ($selected_org_st_id == url_encryption(0)) ? "selected=selected" : '';
            $dropDownOptionsState .= '<option '.$selx.' value="' . url_encryption(0) . '">NOT IN LIST</option>';
            foreach ($stateCentralDept as $v) {
                $selState = ($selected_org_st_id == url_encryption($v->deptcode)) ? "selected=selected" : '';
                $dropDownOptionsState .= '<option ' . $selState . ' value="' . escape_data(url_encryption($v->deptcode)) . '">' . escape_data(strtoupper($v->deptname)) . '</option>';
            }
        } else {
            $dropDownOptionsState = '';
        }
        $dropDownOptionsDept = '<option value="">Select Department</option>';
        $sely = ($selected_org_st_id == url_encryption(0)) ? "selected=selected" : '';
        $dropDownOptionsDept .= '<option '.$selDept_not_in_list.' value="' . url_encryption(0) . '">NOT IN LIST</option>';
        if(is_array($dept_list)){
            foreach ($dept_list as $dept) {
                $sel = ($selected_dept_id == url_encryption($dept->deptcode)) ? "selected=selected" : '';
                $dropDownOptionsDept .= '<option ' . $sel . ' value="' . escape_data(url_encryption($dept->deptcode)) . '">' . escape_data(strtoupper($dept->deptname)) . '</option>';
            }
        }
        echo $dropDownOptions = $dropDownOptionsState . '$$$$$' . $dropDownOptionsDept;
    }

    public function get_org_posts() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $post_list = $this->Dropdown_list_model->get_posts_list();
        $selected_post_id = $_POST['selected_post_id'];
        $selDept_not_in_list = ($selected_post_id == url_encryption(0)) ? "selected=selected" : '';
        $dropDownOptions = '<option value="">Select Post</option>';
        $sels = ($selected_post_id == url_encryption(0)) ? "selected=selected" : '';
        $dropDownOptions .= '<option '.$selDept_not_in_list.' value="' . url_encryption(0) . '">NOT IN LIST</option>';
        foreach ($post_list as $post) {
            $sel = ($selected_post_id == url_encryption($post->authcode)) ? "selected=selected" : '';
            
            $dropDownOptions .= '<option ' . $sel . ' value="' . escape_data(url_encryption($post->authcode)) . '">' . escape_data(strtoupper($post->authdesc)) . '</option>';
        }
        echo $dropDownOptions;
    }

    public function get_sub_category() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $main_cat_id = $main_cat_code = $sub_cat_code_1 = $sub_cat_code_2 = $sub_cat_code_3 = 0;

        $category_code = explode('##', url_decryption($_POST['category_code'])); //echo "<pre>"; print_r($category_code); die;

        $selected_sub_cat = url_decryption($_POST['selected_sub_cat']);

        if (count($category_code) == 2 && is_numeric($category_code[0]) && is_numeric($category_code[1])) {
            $main_cat_id = $category_code[0];
            $main_cat_code = $category_code[1];
        } elseif (count($category_code) == 3 && is_numeric($category_code[0]) && is_numeric($category_code[1]) && is_numeric($category_code[2])) {
            $main_cat_id = $category_code[0];
            $main_cat_code = $category_code[1];
            $sub_cat_code_1 = $category_code[2];
        } elseif (count($category_code) == 4 && is_numeric($category_code[0]) && is_numeric($category_code[1]) && is_numeric($category_code[2]) && is_numeric($category_code[3])) {
            $main_cat_id = $category_code[0];
            $main_cat_code = $category_code[1];
            $sub_cat_code_1 = $category_code[2];
            $sub_cat_code_2 = $category_code[3];
        } elseif (count($category_code) == 5 && is_numeric($category_code[0]) && is_numeric($category_code[1]) && is_numeric($category_code[2]) && is_numeric($category_code[3]) && is_numeric($category_code[4])) {
            $main_cat_id = $category_code[0];
            $main_cat_code = $category_code[1];
            $sub_cat_code_1 = $category_code[2];
            $sub_cat_code_2 = $category_code[3];
            $sub_cat_code_3 = $category_code[4];
        } else {
            // exit with message
        }

        $sub_category_list = $this->Dropdown_list_model->get_sub_category($main_cat_code, $sub_cat_code_1, $sub_cat_code_2, $sub_cat_code_3);

        $dropDownOptions='';
        $category_sc_old='';
        $dropDownOptions = '<option value="">Select Sub Category</option>';

        foreach ($sub_category_list as $sub_cat) {
            if (!empty($sub_cat->category_sc_old)){ $category_sc_old= ' ('.$sub_cat->category_sc_old.')';}
            $sel = ($selected_sub_cat == $sub_cat->id) ? "selected=selected" : '';
            if (count($category_code) == 2) {
                $dropDownOptions .= '<option ' . $sel . ' value="' . escape_data(url_encryption($sub_cat->id . '##' . $sub_cat->subcode1 . '##' . $sub_cat->subcode2)) . '">' . escape_data(strtoupper($sub_cat->sub_cat_name)) . $category_sc_old.'</option>';
            } elseif (count($category_code) == 3) {
                $dropDownOptions .= '<option ' . $sel . ' value="' . escape_data(url_encryption($sub_cat->id . '##' . $sub_cat->subcode1 . '##' . $sub_cat->subcode2 . '##' . $sub_cat->subcode3)) . '">' . escape_data(strtoupper($sub_cat->sub_cat_name)) . $category_sc_old.'</option>';
            } elseif (count($category_code) == 4) {
                $dropDownOptions .= '<option ' . $sel . ' value="' . escape_data(url_encryption($sub_cat->id . '##' . $sub_cat->subcode1 . '##' . $sub_cat->subcode2 . '##' . $sub_cat->subcode3 . '##' . $sub_cat->subcode4)) . '">' . escape_data(strtoupper($sub_cat->sub_cat_name)) . $category_sc_old.'</option>';
            }
        }

        echo $dropDownOptions;
    }
    public function get_sub_cat_check() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $checkedY='';$checkedN='';
        $selected_sub_cat_post = explode('##', url_decryption($_POST['selected_sub_cat']));
        $selected_sub_cat=$selected_sub_cat_post[0];
        $selected_value=$selected_sub_cat_post[1];
        if (!empty($selected_value) && $selected_value=='Y'){
            $checkedY = "checked";
        }elseif (!empty($selected_value) && $selected_value=='N'){
            $checkedN = "checked";
        }
        if (!empty($selected_sub_cat) && $selected_sub_cat==222){
            $dataResult= '
<label class="control-label col-sm-5 input-sm">Is Matrimonial? <span style="color: red">*</span></label>
<div class="col-sm-7">
<input type="hidden" name="matrimonialCheck" value="matrimonialCheck">
<input type="radio" name="matrimonial" value="Y" '.$checkedY.'>
  <label for="Matrimonial">Yes</label>
  <input type="radio"  name="matrimonial" value="N" '.$checkedN.'>
  <label for="Others">No</label>
    </div>
         ';
        }else {
            $dataResult='';
        }
        echo $dataResult;
    }

    public function get_casetype_check() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return redirect()->to(base_url('/'));
            exit(0);
        }

        $checkedY='';
        $checkedN='';
        $selected_casetype_post = explode('##', url_decryption($_POST['selected_casetype']));
        $selected_casetype=$selected_casetype_post[0];
        $selected_value=$selected_casetype_post[1];
        if (!empty($selected_value) && $selected_value=='Y'){
            $checkedY = "checked";
        }elseif (!empty($selected_value) && $selected_value=='N'){
            $checkedN = "checked";
        }
        if (!empty($selected_casetype) && $selected_casetype==7){
            $dataResult= '
<label class="control-label col-sm-5 input-sm">Is Matrimonial? <span style="color: red">*</span></label>
<div class="col-sm-7">
<input type="hidden" name="matrimonialCheck" value="matrimonialCheck">
<input type="radio" name="matrimonial" value="Y" '.$checkedY.'>
  <label for="Matrimonial">Yes</label>
  <input type="radio"  name="matrimonial" value="N" '.$checkedN.'>
  <label for="Others">No</label>
    </div>
         ';
        }else {
            $dataResult='';
        }
        echo $dataResult;
    }
    public function load_document_index() {

        $index_data = '';
        $registration_id = $_SESSION['efiling_details']['registration_id'];
        if (!empty($registration_id)) {
            $efiled_docs_list = $this->DocumentIndex_Select_model->get_index_items_list($registration_id);
        } else {
            $efiled_docs_list = NULL;
        }
        $i = 1;
        $index_data .= '<div class="col-md-12 col-sm-12 col-xs-12"> 
        <div class="table-sec">
                    <div class="table-responsive">
                        <table id="datatable-responsive" class="table table-striped custom-table">
          <thead>
            <tr class="success">
                <th width="3%">#</th>
                    <th>Title</th>
                    <th width="10%">Index</th>
                    <th width="10%">Action</th>
            </tr>
          </thead>
          <tbody>';
        if (!empty($efiled_docs_list)) {

            $indx = '';
            $st_indx = 1;
            $end_indx = '';
            foreach ($efiled_docs_list as $doc_list) {
                $end_indx = $end_indx + $doc_list['page_no'];
                $indx = $st_indx . ' - ' . $end_indx;
                $st_indx = $end_indx + 1;

                $index_data .= '<tr>
                        <td>' . $i++ . '</td>
                        <td><a href="' . base_url('documentIndex/viewIndexItem/' . url_encryption($doc_list['doc_id'])) . '" target="_blank">' . $doc_list['doc_title'] . '<br>( ' . $doc_list['docdesc'] . ')</a></td>
                        <td width="9%">' . $indx . '</td>
                        <td width="10%"> <a onclick = "delete_index(' . "'" . htmlentities(url_encryption(trim($doc_list['doc_id']), ENT_QUOTES)) . "'" . ')"class="btn btn-xs"><i class="fa fa-trash"></i></a></td>
                  </tr>';
            }
        } else {
            $index_data .= '<tr><td colspan="4" class="text-center">No record found.</td></tr>';
        }
        $index_data .= '</tbody></table></div></div></div>';

        echo $index_data;
    }


    public function getAllFilingDetailsByRegistrationId()
    {
        if(empty($_SESSION['login']['ref_m_usertype_id'])) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $postData = json_decode(file_get_contents('php://input'), true);
        $file_type = !empty($postData['file_type']) ? strtolower($postData['file_type']) : NULL;
        $registration_id = !empty($_SESSION['efiling_details']['registration_id']) ? $_SESSION['efiling_details']['registration_id'] : NULL;
        $finalArr = array();
        $finalArr['file_type'] = $file_type;
        
        if (isset($file_type) && !empty($file_type) && isset($registration_id) && !empty($registration_id)) {
            //get icmis usercode for diary_user_id
            $paramArray = array();
            $paramArray['registration_id'] = $registration_id;
            $icmisUserData = $this->Common_model->getIcmisUserCodeByRegistrationId($paramArray);
            $userIcmisCode = !empty($icmisUserData[0]->icmis_usercode) ? (int)$icmisUserData[0]->icmis_usercode : 0;
            switch ($file_type) {
                case 'new_case' :
                    $case_details = $this->Get_details_model->get_new_case_details($registration_id);
                    $tmpArr = array();
                    if (isset($case_details[0]) && !empty($case_details[0])) {
                        $tmpArr['casetype_id'] = !empty($case_details[0]['sc_case_type_id']) ? $case_details[0]['sc_case_type_id'] : NULL;
                        $tmpArr['diary_user_id'] = $userIcmisCode;
                        $tmpArr['pno'] = !empty($case_details[0]['no_of_petitioners']) ? $case_details[0]['no_of_petitioners'] : NULL;
                        $tmpArr['rno'] = !empty($case_details[0]['no_of_respondents']) ? $case_details[0]['no_of_respondents'] : NULL;

                        $mainParties=explode('Vs.',$case_details[0]['cause_title']);
                        $tmpArr['pet_name_causetitle']= is_array($mainParties) ? $mainParties[0] : '';
                        $tmpArr['res_name_causetitle']= is_array($mainParties) ? $mainParties[1] : '';
                        $tmpArr['subject_cat'] = !empty($case_details[0]['subject_cat']) ? $case_details[0]['subject_cat'] : NULL;
                        $tmpArr['subj_main_cat'] = !empty($case_details[0]['subj_main_cat']) ? $case_details[0]['subj_main_cat'] : NULL;
                        $tmpArr['sc_sp_case_type_id'] = !empty($case_details[0]['sc_sp_case_type_id']) ? $case_details[0]['sc_sp_case_type_id'] : NULL;
                        $tmpArr['jail_signature_date'] = !empty($case_details[0]['jail_signature_date']) ? $case_details[0]['jail_signature_date'] : NULL;
                        $tmpArr['efiling_no'] = !empty($case_details[0]['efiling_no']) ? $case_details[0]['efiling_no'] : NULL;
                        $tmpArr['if_sclsc']=!empty($case_details[0]['if_sclsc'])?$case_details[0]['if_sclsc']:0;
                        $tmpArr['sclsc_amr_no'] = !empty($case_details[0]['sclsc_amr_no']) ? $case_details[0]['sclsc_amr_no'] : NULL;
                        $tmpArr['sclsc_amr_year'] = !empty($case_details[0]['sclsc_amr_year']) ? $case_details[0]['sclsc_amr_year'] : NULL;
                        $tmpArr['special_category']=!empty($case_details[0]['special_category']) ? $case_details[0]['special_category'] : 0;
                        $created_by = !empty($case_details[0]['created_by']) ? $case_details[0]['created_by'] : NULL;
                        if (isset($created_by) && !empty($created_by)) {
                            $userData = $this->Common_model->getUserDetailsById($created_by);
                            if (isset($userData) && !empty($userData)) {
                                $adv_sci_bar_id = !empty($userData[0]['adv_sci_bar_id']) ? (int)$userData[0]['adv_sci_bar_id'] : NULL;
                                if (isset($adv_sci_bar_id) && !empty($adv_sci_bar_id)) {
                                    $getBarData = $this->Common_model->getBarDetailsById($adv_sci_bar_id);
                                    $bar_id = !empty($getBarData[0]['bar_id']) ? $getBarData[0]['bar_id'] : NULL;
                                    /* $pp = !empty($getBarData[0]['pp']) ? 'P' : 'A';
                                    $tmpArr['pet_adv_id'] = !empty($getBarData[0]['pp']) ? PETITIONER_IN_PERSON : $bar_id; */
                                    $pp = !empty($bar_id) ? 'A' : 'P';
                                    $tmpArr['pet_adv_id'] = !empty($bar_id) ? $bar_id : PETITIONER_IN_PERSON;
                                    $tmpArr['adv_pp'] = $pp;
                                }
                                else{
                                    $tmpArr['pet_adv_id'] = PETITIONER_IN_PERSON;
                                    $tmpArr['adv_pp'] = 'P';
                                }
                            }
                        }
                    }
                    $courtData = $this->Common_model->getEarlierCourtDetailByRegistrationId($registration_id);
                    $court_type = !empty($case_details[0]['court_type']) ? (int)$case_details[0]['court_type'] : NULL;
                    if(isset($court_type) && !empty($court_type)){
                        switch($court_type){
                            case 1:
                                $tmpArr ['court_type'] = !empty($courtData[0]['court_type']) ? $courtData[0]['court_type'] : NULL;
                                $tmpArr ['cmis_state_id'] = !empty($courtData[0]['cmis_state_id']) ? $courtData[0]['cmis_state_id'] : NULL;
                                $tmpArr ['ref_agency_code_id'] = !empty($courtData[0]['ref_agency_code_id']) ? $courtData[0]['ref_agency_code_id'] : NULL;
                                break;
                            case 3:
                                $tmpArr ['court_type'] = !empty($courtData[0]['court_type']) ? $courtData[0]['court_type'] : NULL;
                                $tmpArr ['cmis_state_id'] = !empty($courtData[0]['cmis_state_id']) ? $courtData[0]['cmis_state_id'] : NULL;
                                $tmpArr ['ref_agency_code_id'] = !empty($courtData[0]['ref_agency_code_id']) ? $courtData[0]['ref_agency_code_id'] : NULL;
                                break;
                            case 4:
                                $tmpArr ['court_type'] = !empty($courtData[0]['court_type']) ? $courtData[0]['court_type'] : NULL;
                                break;
                            case 5:
                                $tmpArr ['court_type'] = !empty($courtData[0]['court_type']) ? $courtData[0]['court_type'] : NULL;
                                $tmpArr ['cmis_state_id'] = !empty($courtData[0]['cmis_state_id']) ? $courtData[0]['cmis_state_id'] : NULL;
                                $tmpArr ['ref_agency_code_id'] = !empty($courtData[0]['ref_agency_code_id']) ? $courtData[0]['ref_agency_code_id'] : NULL;
                                break;
                            default:

                        }
                    }
                    $finalArr['case_details'] = !empty($tmpArr) ? array((object)$tmpArr) : array(new stdClass());

                    $main_petitioner_details = $this->Get_details_model->get_case_parties_details($registration_id, array('p_r_type' => 'P', 'm_a_type' => 'M', 'party_id' => NULL, 'view_lr_list' => FALSE));
                    $tempMainArr = array();
                    if (isset($main_petitioner_details) && !empty($main_petitioner_details)) {
                        foreach ($main_petitioner_details as $k => $v) {
                            $tempMainArr[] = (object)$v;
                        }
                    }
                    $finalArr['main_petitioner'] = !empty($main_petitioner_details[0]) ? $tempMainArr : array(new stdClass());
                    $respondent_details = $this->Get_details_model->get_case_parties_details($registration_id, array('p_r_type' => 'R', 'm_a_type' => 'M', 'party_id' => NULL, 'view_lr_list' => FALSE));
                    $tempResArr = array();
                    if (isset($respondent_details) && !empty($respondent_details)) {
                        foreach ($respondent_details as $k => $v) {
                            $tempResArr[] = (object)$v;
                        }
                    }

                    $finalArr['main_respondent'] = !empty($respondent_details[0]) ? $tempResArr : array(new stdClass());

                    //$extra_parties_list = $this->Get_details_model->get_case_parties_details($registration_id, array('p_r_type' => NULL, 'm_a_type' => 'A', 'party_id' => NULL, 'view_lr_list' => FALSE));
                    $extra_parties_list = $this->Get_details_model->get_case_parties_details($registration_id, array('p_r_type' => NULL, 'm_a_type' => 'A', 'party_id' => NULL, 'view_lr_list' => FALSE));
                    $tempExtArr = array();
                    if (isset($extra_parties_list) && !empty($extra_parties_list)) {
                        foreach ($extra_parties_list as $k => $v) {
                            $tempExtArr[] = (object)$v;
                        }
                    }
                    $finalArr['extra_party'] = !empty($extra_parties_list[0]) ? $tempExtArr : array(new stdClass());

                    $subordinate_court_details = $this->Get_details_model->getSubordinateCourtData($registration_id);
                    $court_type = ''; //for high court first
                    $subTempArr = array();
                    if (isset($subordinate_court_details) && !empty($subordinate_court_details)) {
                        foreach ($subordinate_court_details as $k => $v) {
                            $params = array();
                            if(isset($v['registration_id']) && !empty($v['registration_id'])){
                                $params['table_name'] = 'efil.tbl_fir_details';
                                $params['whereFieldName'] = 'registration_id';
                                $params['whereFieldValue'] = (int)$v['registration_id'];
                                $firData = $this->Common_model->getData($params);
                                if(!isset($v['case_type_id']) && empty($v['case_type_id'])){
                                    $v['case_type_id'] = 0;
                                }
                                if(isset($firData) && !empty($firData) && count($firData)>0){
                                    $v['fir_details']['is_fir_details_exists'] =  true;
                                    $v['fir_details']['firData'] =  array($firData[0]);
                                }
                                else{
                                    $v['fir_details']['is_fir_details_exists'] =  false;
                                    $v['fir_details']['firData']  =  array(new stdClass());
                                }
                                $subTempArr[$k] = (object)$v;
                            }
                        }
                    }
                    $finalArr['earlier_courts'] = !empty($subordinate_court_details[0]) ? $subTempArr : array(new stdClass());

                    
                    $efiled_docs_list = $this->View_model->get_index_items_list($registration_id);
                    $docTempArr = array();
                    if (isset($efiled_docs_list) && !empty($efiled_docs_list)) {
                        foreach ($efiled_docs_list as $k => $v) {
                            $docTempArr[] = (object)$v;
                        }
                    }
                    $finalArr['documents'] = !empty($efiled_docs_list[0]) ? $docTempArr : array(new stdClass());
                    $payment_details = $this->View_model->get_payment_details($registration_id);
                    $feeTempArr = array();
                    if (isset($payment_details) && !empty($payment_details)) {
                        foreach ($payment_details as $k => $v) {
                            $feeTempArr[] = (object)$v;
                        }
                    }
                    $finalArr['court_fee'] = !empty($payment_details[0]) ? $feeTempArr : array(new stdClass());

                    
                    $act_sections_list = $this->Act_sections_model->get_act_sections_list($registration_id);
                    $actTempArr = array();
                    if (isset($act_sections_list) && !empty($act_sections_list)) {
                        foreach ($act_sections_list as $k => $v) {
                            $actTempArr[] = (object)$v;
                        }
                    }
                    $finalArr['act_section'] = !empty($act_sections_list[0]) ? $actTempArr : array(new stdClass());

                    break;
                case 'caveat' :
                    $registration_id = !empty(getSessionData('efiling_details')['registration_id']) ? (int)getSessionData('efiling_details')['registration_id'] : NULL;
                    //caveator
                    $arr = array();
                    $arr['registration_id'] = $registration_id;
                    $arr['step'] = 3;
                    $caveator = $this->Common_model->getCaveatDataByRegistrationId($arr);
                    $is_already_approved_CaveatNoGenerated=$this->Efiling_action_model->is_already_approved_CaveatNoGenerated($registration_id);
                    if ($is_already_approved_CaveatNoGenerated){
                        $efiling_details=$is_already_approved_CaveatNoGenerated;
                        $result["status"] = "ERROR_CAVEAT";
                        $result["error"] = "This efiling detail already transfered to ICMIS as Caveat number ".$efiling_details->caveat_num.$efiling_details->caveat_year;
                        echo json_encode($result);exit();
                    }
                    $createdBy = !empty($caveator[0]->createdby) ? (int)$caveator[0]->createdby : NULL;
                    $case_type_id = !empty($caveator[0]->case_type_id) ? (int)$caveator[0]->case_type_id : NULL;
                    $tmpArr = array();
                    if (isset($caveator) && !empty($caveator)) {
                        if (isset($createdBy) && !empty($createdBy)) {
                            $userData = $this->Common_model->getUserDetailsById($createdBy);

                            if (isset($userData) && !empty($userData)) {
                                $adv_sci_bar_id = !empty($userData[0]['adv_sci_bar_id']) ? (int)$userData[0]['adv_sci_bar_id'] : NULL;
                                $usertype = !empty($userData[0]['ref_m_usertype_id']) ? (int)$userData[0]['ref_m_usertype_id'] : NULL;
                                if ((isset($adv_sci_bar_id) && !empty($adv_sci_bar_id)) || $usertype==USER_IN_PERSON) {
                                    if($usertype==USER_IN_PERSON){
                                        $adv_sci_bar_id=616;
                                    }
                                    $getBarData = $this->Common_model->getBarDetailsById($adv_sci_bar_id);
                                    $bar_id = !empty($getBarData[0]['bar_id']) ? $getBarData[0]['bar_id'] : NULL;
                                    $pp = !empty($getBarData[0]['pp']) ? 'P' : 'A';
                                    $tmpArr['pet_adv_id'] = !empty($getBarData[0]['pp']) ? CAVEATOR_IN_PERSON :   $bar_id;
                                    $tmpArr['adv_pp'] = $pp;
                                    // $tmpArr['diary_user_id'] = 1; // change in future
                                    $tmpArr['diary_user_id'] = $userIcmisCode;
                                    $tmpArr['casetype_id'] = $case_type_id;
                                }
                            }
                        }
                        $params = array();
                        $params['table_name'] = 'efil.tbl_efiling_nums';
                        $params['whereFieldName'] = 'registration_id';
                        $params['whereFieldValue'] = $registration_id;
                        $efilingData = $this->Common_model->getData($params);

                        if (isset($efilingData) && !empty($efilingData)) {
                            $tmpArr['efiling_no'] = !empty($efilingData[0]->efiling_no) ? $efilingData[0]->efiling_no : NULL;
                        }
                        $finalArr['caveat_details'] = !empty($tmpArr) ? array((object)$tmpArr) : array(new stdClass());
                        $finalArr['caveator'] = !empty($caveator[0]) ? array($caveator[0]) : array(new stdClass());
                    }

                    //caveatee
                    $arr = array();
                    $arr['registration_id'] = $registration_id;
                    $arr['step'] = 4;
                    $caveatee = $this->Common_model->getCaveatDataByRegistrationId($arr);
                    $finalArr['caveatee'] = !empty($caveatee[0]) ? array($caveatee[0]) : array(new stdClass());
                    //extra party
                    $arr = array();
                    $arr['registration_id'] = $registration_id;
                    $arr['step'] = 5;
                    $extra_party_details = $this->Common_model->getCaveatDataByRegistrationId($arr);
                    $finalArr['extra_party'] = !empty($extra_party_details[0]) ? $extra_party_details : array(new stdClass());
                    //subordinate
//                    $subordinate_data = $this->View_model->getSubCourtDetails($registration_id);
//                   // echo '<pre>'; print_r($subordinate_data); exit;
//                    if (isset($subordinate_data) && !empty($subordinate_data)) {
//                        $tempArr = array();
//                        $tmp = array();
//                        foreach ($subordinate_data as $k => $v) {
//                            $tmp['ct_code'] = !empty($v['court_type']) ? (int)$v['court_type'] : NULL;
//                            $tmp['l_state'] = !empty($v['cmis_state_id']) ? (int)$v['cmis_state_id'] : NULL;
//                            $tmp['l_dist'] = !empty($v['ref_agency_code_id']) ? (int)$v['ref_agency_code_id'] : NULL;
//                            $tmp['caveat_no'] = NULL;
//                            $tmp['lw_display'] = 'Y';
//                            $tmp['lct_casetype'] = !empty($v['case_type']) ? (int)$v['case_type'] : NULL;
//                            $tmp['lct_caseno'] = !empty($v['case_no']) ? (int)$v['case_no'] : NULL;
//                            $tmp['lct_caseyear'] = !empty($v['case_year']) ? (int)$v['case_year'] : NULL;
//                            $params = array();
//                            if(isset($v['registration_id']) && !empty($v['registration_id'])){
//                                $params['table_name'] = 'efil.tbl_fir_details';
//                                $params['whereFieldName'] = 'registration_id';
//                                $params['whereFieldValue'] = (int)$v['registration_id'];
//                                $firData = $this->Common_model->getData($params);
//                                if(isset($firData) && !empty($firData) && count($firData)>0){
//                                    $v['fir_details']['is_fir_details_exists'] =  true;
//                                    $v['fir_details']['firData'] =  array($firData[0]);
//                                }
//                                else{
//                                    $v['fir_details']['is_fir_details_exists'] =  false;
//                                    $v['fir_details']['firData']  =  array(new stdClass());
//                                }
//
//                            }
//                            $tempArr[$k] = (object)$v;
//                        }
//                    }
//                    $finalArr['earlier_courts'] = !empty($subordinate_data[0]) ? $tempArr : array(new stdClass());
                    // similar to new case 07/04/21


                    $subordinate_court_details = $this->Get_details_model->getSubordinateCourtData($registration_id);

                    $court_type = ''; //for high court first
                    $subTempArr = array();
                    if (isset($subordinate_court_details) && !empty($subordinate_court_details)) {
                        foreach ($subordinate_court_details as $k => $v) {
                            $params = array();
                            if(isset($v['registration_id']) && !empty($v['registration_id'])){
                                $params['table_name'] = 'efil.tbl_fir_details';
                                $params['whereFieldName'] = 'registration_id';
                                $params['whereFieldValue'] = (int)$v['registration_id'];
                                $firData = $this->Common_model->getData($params);
                                if(!isset($v['case_type_id']) && empty($v['case_type_id'])){
                                    $v['case_type_id'] = 0;
                                }
                                if(isset($firData) && !empty($firData) && count($firData)>0){
                                    $v['fir_details']['is_fir_details_exists'] =  true;
                                    $v['fir_details']['firData'] =  array($firData[0]);
                                }
                                else{
                                    $v['fir_details']['is_fir_details_exists'] =  false;
                                    $v['fir_details']['firData']  =  array(new stdClass());
                                }
                                $subTempArr[$k] = (object)$v;
                            }
                        }
                    }
                    $finalArr['earlier_courts'] = !empty($subordinate_court_details[0]) ? $subTempArr : array(new stdClass());

                    //upload doc
                    $efiled_docs_list = $this->View_model->get_index_items_list($registration_id);
                    if (isset($efiled_docs_list) && !empty($efiled_docs_list)) {
                        $tmpArr = array();
                        foreach ($efiled_docs_list as $k => $v) {
                            $tmpArr[] = (object)$v;
                        }
                    }
                    $finalArr['documents'] = !empty($efiled_docs_list[0]) ? $tmpArr : array(new stdClass());
                    //payment Details
                    $payment_details = $this->View_model->get_payment_details($registration_id);
                    if (isset($payment_details) && !empty($payment_details)) {
                        $tempArr = array();
                        foreach ($payment_details as $k => $v) {
                            $tempArr[] = (object)$v;
                        }
                    }
                    $finalArr['court_fee'] = !empty($payment_details[0]) ? $tempArr : array(new stdClass());
                    break;
                default:
                    $finalArr;
                    break;
            }
        }

        $finalArr= json_encode($finalArr);
        $response = array();
        $config = config('Encryption');
        $encrypter = Services::encrypter();
        if($file_type=='new_case'){
            $key=$config->key;
            $encrypted_string = $encrypter->encrypt($finalArr, $key);
            $response = $this->efiling_webservices->generateCaseDiary($encrypted_string);
        }
        else if($file_type == 'caveat'){
            $key=$config->key;
        // pr($key);

            $encrypted_string = $encrypter->encrypt($finalArr, $key);
            $response = $this->efiling_webservices->generateCaseDiary($encrypted_string);
        }
    //    pr($response);
//        $response = array();
//        $records_inserted = array();
//        $records_inserted['main'] = 1;
//        $records_inserted['docdetails'] = 1;
//        $records_inserted['lowerct'] = 1;
//        $records_inserted['party'] = 3;
//        $records_inserted['act_section'] = 1;
//        $records_inserted['diary_copy_set'] = 4;
//
//        $response['records_inserted'] = (object)$records_inserted;
//        $response['diary_no'] = 292021;
//        $response['status'] = 'SUCCESS';

        // echo '<pre>'; print_r($response); exit;
        echo json_encode($response);
        exit;
    }

    public function updateDiaryDetails()
    { 
        if(empty($_SESSION['login']['ref_m_usertype_id'])) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $output = array();

        $postData = json_decode(file_get_contents('php://input'), true);

        $registration_id = !empty($_SESSION['efiling_details']['registration_id']) ? $_SESSION['efiling_details']['registration_id'] : NULL;
        $efiling_type = !empty($_SESSION['efiling_details']['efiling_type']) ? strtolower($_SESSION['efiling_details']['efiling_type']) : NULL;
        $ref_m_efiled_type_id = !empty($_SESSION['efiling_details']['ref_m_efiled_type_id']) ? $_SESSION['efiling_details']['ref_m_efiled_type_id'] : NULL;
        $efiling_no = !empty($_SESSION['efiling_details']['efiling_no']) ? $_SESSION['efiling_details']['efiling_no'] : NULL;
        $diaryStatus = !empty($postData['diaryStatus']) ? $postData['diaryStatus'] : NULL;
        if (isset($registration_id) && !empty($registration_id)) {
            $diary_num = NULL;
            $diary_year = NULL;
            $arr = array();
            if(!empty($postData['diaryNo']) && isset($postData['diaryNo'])) {
                $diary_num = substr($postData['diaryNo'], 0, -4);
                $diary_year = substr($postData['diaryNo'], -4);
            }
            //alloted_to
            $alloted_to_name = NULL;
            if(!empty($postData['alloted_to']) && isset($postData['alloted_to'])) {
                $alloted = explode('~',$postData['alloted_to']);
                $alloted_to_emp_code = !empty($alloted[0]) ? $alloted[0] : NULL;
                $alloted_name = !empty($alloted[1]) ? $alloted[1] : NULL;
                if(isset($alloted_to_emp_code) && !empty($alloted_to_emp_code) && isset($alloted_name) && !empty($alloted_name)){
                    $alloted_to_name = strtoupper($alloted_name).' ('.$alloted_to_emp_code.' )';
                }
                else if(isset($alloted_name) && !empty($alloted_name)){
                    $alloted_to_name = strtoupper($alloted_name);
                }
            }
            $diaryNo = !empty($postData['diaryNo']) ? $postData['diaryNo'] : NULL;
            $status = !empty($postData['status']) ? $postData['status'] : NULL;
            $records_inserted = !empty($postData['records_inserted']) ? $postData['records_inserted'] : NULL;
            $insertedDocNums = !empty($postData['insertedDocNums']) ? $postData['insertedDocNums'] : NULL;
            if(isset($postData['selectedcheckBox']) && !empty($postData['selectedcheckBox'])){
                $checkBoxArr = array();
                $checkBoxArr['recordinserted'] = json_encode($postData['selectedcheckBox']);
                $checkBoxArr['diary_no'] = $diaryNo;
                $checkBoxArr['status'] = $status;
                $checkBoxArr['createdAt'] = date('Y-m-d H:i:s');
                $checkBoxArr['updatedAt'] = NULL;
                $checkBoxArr['createdby'] = !empty($_SESSION['login']['id']) ? (int)$_SESSION['login']['id'] : NULL;;
                $checkBoxArr['updatedby'] = NULL;
                $checkBoxArr['createdbyip'] = getClientIP();
                $checkBoxArr['registration_id'] = $registration_id;
                $table = "efil.tbl_diary_generation_checkbox";
                $this->Citation_model->insertData($table,$checkBoxArr);
            }
            if(isset($records_inserted) && !empty($records_inserted)){
                $insertDiaryData = array();
                $insertDiaryData['diaryNo'] = $diaryNo;
                $insertDiaryData['status'] = $status;
                $insertDiaryData['inserted_records'] = $records_inserted;
                $inArr = array();
                $inArr['recordinserted'] = json_encode($insertDiaryData);
                $inArr['diary_no'] = $diaryNo;
                $inArr['status'] = $status;
                $inArr['createdAt'] = date('Y-m-d H:i:s');
                $inArr['updatedAt'] = NULL;
                $inArr['createdby'] = !empty($_SESSION['login']['id']) ? (int)$_SESSION['login']['id'] : NULL;;
                $inArr['updatedby'] = NULL;
                $inArr['createdbyip'] = getClientIP();
                $inArr['registration_id'] = $registration_id;
                $table = 'efil.tbl_diary_generation_response';
                $this->Citation_model->insertData($table,$inArr);
            }
            switch ($efiling_type){
                case 'new_case' :
                    //update icmis_docnum in tbl_efiled_docs
                   // $insertedDocNums = array(0=>array('doc_id'=>3588,'docnum'=>2121));
                    if(isset($insertedDocNums) && !empty($insertedDocNums)){
                         foreach ($insertedDocNums as $k=>$v){
                             $doc_id = (int)$v['doc_id'];
                             $docnum = (int)$v['docnum'];
                             $upArray = [];
                             $upArray['table_name'] = 'efil.tbl_efiled_docs';
                             $upArray['whereFieldName'] = 'doc_id';
                             $upArray['whereFieldValue'] = $doc_id;
                             $docNumUpArr = array();
                             $docNumUpArr['icmis_docnum'] = $docnum;
                             $docNumUpArr['updated_on'] = date('Y-m-d H:i:s');
                             $upArray['updateArr'] = $docNumUpArr;
                             $this->Common_model->updateTableData($upArray);
                         }
                    }
                    $arr['table_name'] = 'efil.tbl_case_details';
                    $arr['whereFieldName'] = 'registration_id';
                    $arr['whereFieldValue'] = (int)$registration_id;
                    $upArr = array();
                    $upArr['sc_diary_year'] = $diary_year;
                    $upArr['sc_diary_date'] = date('Y-m-d H:i:s');
                    $upArr['sc_diary_num'] = $diary_num;
                    $arr['updateArr'] = $upArr;
                    $res = $this->Common_model->updateTableData($arr);
                    if (isset($res) && !empty($res)) {
                        $doc = array();
                        $doc['table_name'] = 'efil.tbl_efiled_docs';
                        $doc['whereFieldName'] = 'registration_id';
                        $doc['whereFieldValue'] = (int)$registration_id;
                        $docUpArr = array();
                        $docUpArr['icmis_diary_no'] = $diary_num.$diary_year;
                        $docUpArr['icmis_docyear'] = $diary_year;
                        $docUpArr['updated_on'] = date('Y-m-d H:i:s');
                        $doc['updateArr'] = $docUpArr;
                        $result = $this->Common_model->updateTableData($doc);
                        if (isset($result) && !empty($result)) {
                            if ($ref_m_efiled_type_id == E_FILING_TYPE_NEW_CASE) {
                                $output = $this->getCisStatusForNewCase($registration_id, $efiling_no, Transfer_to_IB_Stage,$diaryStatus,$alloted_to_name);
                            }
                            else {
                                $output['success'] = 'error';
                            }
                        }
                        else {
                            $output['success'] = 'error 1';
                        }
                    } else {
                        $output['success'] = 'error 2';
                    }
                    break;
                case 'caveat':
                    $arr['table_name'] = 'public.tbl_efiling_caveat';
                    $arr['whereFieldName'] = 'ref_m_efiling_nums_registration_id';
                    $arr['whereFieldValue'] = (int)$registration_id;
                    $upArr = array();
                    $upArr['caveat_year'] = $diary_year;
                    $upArr['caveat_num_date'] = date('Y-m-d H:i:s');
                    $upArr['caveat_num'] = $diary_num;
                    $arr['updateArr'] = $upArr;
                    $res = $this->Common_model->updateTableData($arr);
                    if (isset($res) && !empty($res)) {
                        $doc = array();
                        $doc['table_name'] = 'efil.tbl_efiled_docs';
                        $doc['whereFieldName'] = 'registration_id';
                        $doc['whereFieldValue'] = (int)$registration_id;
                        $docUpArr = array();
                        $docUpArr['icmis_diary_no'] = $diary_num.$diary_year;
                        $docUpArr['icmis_docyear'] = $diary_year;
                        $docUpArr['updated_on'] = date('Y-m-d H:i:s');
                        $doc['updateArr'] = $docUpArr;
                        $result = $this->Common_model->updateTableData($doc);

                        if (isset($result) && !empty($result)) {

                            if ($ref_m_efiled_type_id == E_FILING_TYPE_CAVEAT) {
                                $output = $this->getCisStatusForNewCase($registration_id, $efiling_no, Transfer_to_IB_Stage,$diaryStatus,'');
                    // pr($output);

                            }
                            else {
                                $output['success'] = 'error4';
                            }
                        }
                        else {
                            $output['success'] = 'error5';
                        }
                    } else {
                        $output['success'] = 'error6';
                    }
                    break;
                default :
                    $output;
            }
        }
        
        echo json_encode($output);
        exit;
    }

    function getCisStatusForNewCase($registration_id, $efiling_num, $curr_stage,$diaryStatus = NULL,$alloted_to_name = NULL)
    {
        if(empty($_SESSION['login']['ref_m_usertype_id'])) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        //echo 'Anshu 1';exit();
        $output = array();
        if (isset($registration_id) && !empty($registration_id) && isset($efiling_num)
            && !empty($efiling_num) && isset($curr_stage) && !empty($curr_stage)) {
            $efiling_type = !empty($_SESSION['efiling_details']['efiling_type']) ? strtolower($_SESSION['efiling_details']['efiling_type']) : NULL;
            $ref_m_efiled_type_id = !empty($_SESSION['efiling_details']['ref_m_efiled_type_id']) ? $_SESSION['efiling_details']['ref_m_efiled_type_id'] : NULL;
            $curr_dt = date('Y-m-d H:i:s');
            switch ($efiling_type){
                case 'new_case':
                    $params = array();
                    $params['table_name'] = 'efil.tbl_case_details';
                    $params['whereFieldName'] = 'registration_id';
                    $params['whereFieldValue'] = $registration_id;
                    $caseDetailsData = $this->Common_model->getData($params);
                    if (isset($caseDetailsData[0]->sc_diary_num) && !empty($caseDetailsData[0]->sc_diary_num)
                        && isset($caseDetailsData[0]->sc_diary_year) && !empty($caseDetailsData[0]->sc_diary_year)){
                        $objections_status = NULL;
                        $documents_status = NULL;
                        $diary_no = (int)$caseDetailsData[0]->sc_diary_num;
                        $diary_year = (int)$caseDetailsData[0]->sc_diary_year;
                        $sc_diary_date = !empty($caseDetailsData[0]->sc_diary_date) ? $caseDetailsData[0]->sc_diary_date : NULL;
                        $verification_date = !empty($caseDetailsData[0]->verification_date) ? $caseDetailsData[0]->verification_date : NULL;
                        $case_details = array();
                        $case_details['sc_diary_num'] = $diary_no;
                        $case_details['sc_diary_year'] = $diary_year;
                        $case_details['sc_diary_date'] = $sc_diary_date;
                        $case_details['updated_by'] = !empty($_SESSION['login']['id']) ? (int)$_SESSION['login']['id'] : NULL;
                        $case_details['updated_on'] = $curr_dt;
                        $case_details['updated_by_ip'] = getClientIP();
                        $stage_update_timestamp = $this->Get_CIS_Status_model->getStageUpdateTmestampCaseCaveat($registration_id, $curr_stage,$diaryStatus);
                        if(isset($stage_update_timestamp) && !empty($stage_update_timestamp)){
                            $next_stage = I_B_Approval_Pending_Admin_Stage;
                            $update_status = $this->Get_CIS_Status_model->update_icmis_case_status($registration_id, $next_stage, $curr_dt, $case_details, '', '', '',$efiling_type);
                            if(isset($update_status) && !empty($update_status)){
                                if($next_stage == I_B_Approval_Pending_Admin_Stage) {
                                    $cuase_title = '';
                                    if(!empty($caseDetailsData[0]->cause_title) &&  !empty($caseDetailsData[0]->cause_title)){
                                        $cuase_title = $caseDetailsData[0]->cause_title;
                                    }
                                    $createdby = !empty($caseDetailsData[0]->created_by) ? (int)$caseDetailsData[0]->created_by : NULL;
                                    if(isset($createdby) && !empty($createdby)){
                                        $params = array();
                                        $params['table_name'] ='efil.tbl_users';
                                        $params['whereFieldName'] ='id';
                                        $params['whereFieldValue'] = (int)$createdby;
                                        $params['is_active'] ='1';
                                        $userData = $this->Common_model->getData($params);
                                        $mobile = NULL;
                                        $email= NULL;
                                        $efiling_no = '';
                                        $first_name = '';
                                        $last_name ='';
                                        $userName = '';
                                        if(isset($userData) && !empty($userData)){
                                            $mobile = (!empty($userData[0]->moblie_number) && isset($userData[0]->moblie_number)) ? $userData[0]->moblie_number : NULL;
                                            $email= (!empty($userData[0]->emailid) && isset($userData[0]->emailid)) ? $userData[0]->emailid : NULL;
                                            $first_name= (!empty($userData[0]->first_name) && isset($userData[0]->first_name)) ? $userData[0]->first_name : NULL;
                                            $last_name= (!empty($userData[0]->last_name) && isset($userData[0]->last_name)) ? $userData[0]->last_name : NULL;
                                        }
                                    }
                                    if(isset($first_name) && !empty($first_name) && isset($last_name) && !empty($last_name)){
                                        $userName = $first_name.' '.$last_name;
                                    }
                                    else if(isset($first_name) && !empty($first_name)){
                                        $userName = $first_name;
                                    }
                                    $diary_No= $diary_no.' / '.$diary_year;
                                    $current_date = date('d-m-Y H:i:s');
                                    $arr = array();
                                    $arr['table_name'] ='efil.tbl_efiling_nums';
                                    $arr['whereFieldName'] ='registration_id';
                                    $arr['whereFieldValue'] = (int)$registration_id;
                                    $arr['is_active'] ='1';
                                    $efilingNoData = $this->Common_model->getData($arr);
                                    if(isset($efilingNoData[0]->efiling_no) && !empty($efilingNoData[0]->efiling_no)){
                                        $efiling_no = $efilingNoData[0]->efiling_no;
                                    }
                                    $smsRes = '';
                                    if(isset($mobile) && !empty($mobile)){
                                        //$message = 'Your case efiling no. '.$efiling_no.' , '.$cuase_title.' is filed with Diary No. '.$diary_No.' on '.$current_date.'';
                                        $message = 'Your case '.$efiling_no.' is filed with Diary No. '.$diary_No.' on '.$current_date.'. - Supreme Court of India';
                                        $smsRes = send_mobile_sms($mobile,$message,SCISMS_Case_Filed_Diary_No);
                                        send_whatsapp_message($registration_id,$efiling_no," - filed with Diary Number '.$diary_No ");
                                    }
                                    if(isset($email) && !empty($email)){
                                        $message = 'Dear '.$userName.', your case efiling no. '.$efiling_no.' , '.$cuase_title.' is filed with Diary No. '.$diary_No.' on '.$current_date.'';
                                        $to_email = $email;
                                        $subject = 'Diary No. Generation.';
                                        send_mail_msg($to_email, $subject, $message, $to_user_name="");
                                    }
                                    $output['success'] = 'success';
                                    // pr($output);
                                    if(isset($alloted_to_name) && !empty($alloted_to_name)){
                                        $output['message'] = 'Diary No.' .$diary_no.'/'.$diary_year.' has been created successfully! and allocated to  '.$alloted_to_name;
                                    }
                                    else{
                                        $output['message'] = 'Diary No.' .$diary_no.'/'.$diary_year.' has been created successfully!';
                                    }
                                } elseif ($next_stage == E_Filed_Stage) {
                                    $output['success'] = 'success';
                                    $output['message']='updated to Efiled Stage.';
                                }
                            }
                            else{
                                $output['success'] = 'error';
                                $output['message']='1';
                            }
                        }
                        else{
                            $output['success'] = 'error';
                            $output['message']='2';
                        }
                    }
                    else{
                        $output['success'] = 'error';
                        $output['message']='3';
                    }
                    break;
                case 'caveat':
                    $params = array();
                    $params['table_name'] = 'public.tbl_efiling_caveat';
                    $params['whereFieldName'] = 'ref_m_efiling_nums_registration_id';
                    $params['whereFieldValue'] = $registration_id;
                    $caveatDetailsData = $this->Common_model->getData($params);

                    if (isset($caveatDetailsData[0]->caveat_num) && !empty($caveatDetailsData[0]->caveat_num)
                        && isset($caveatDetailsData[0]->caveat_year) && !empty($caveatDetailsData[0]->caveat_year))
                    {
                        $diary_no = (int)$caveatDetailsData[0]->caveat_num;
                        $diary_year = (int)$caveatDetailsData[0]->caveat_year;
                        $sc_diary_date = !empty($caveatDetailsData[0]->caveat_num_date) ? $caveatDetailsData[0]->caveat_num_date : NULL;
                        $caveat_details = array();
                        $caveat_details['caveat_num'] = $diary_no;
                        $caveat_details['caveat_year'] = $diary_year;
                        $caveat_details['caveat_num_date'] = $sc_diary_date;
                        $caveat_details['updated_by'] = !empty($_SESSION['login']['id']) ? (int)$_SESSION['login']['id'] : NULL;
                        $caveat_details['updated_at'] = $curr_dt;
                        $caveat_details['updated_by_ip'] = getClientIP();
                        // $this->load->model('getCIS_status/Get_CIS_Status_model');
                        // pr($diaryStatus);

                        $stage_update_timestamp = $this->Get_CIS_Status_model->getStageUpdateTmestampCaseCaveat($registration_id, $curr_stage,$diaryStatus);
                        // pr($stage_update_timestamp);

                        if(isset($stage_update_timestamp) && !empty($stage_update_timestamp)){
                            //$next_stage = I_B_Approval_Pending_Admin_Stage;
                            //Changed on 02-06-2021 for marking caveat as e-filed once caveat number is generated as per current practice.
                            $next_stage = E_Filed_Stage;
                            $update_status = $this->Get_CIS_Status_model->update_icmis_case_status($registration_id, $next_stage, $curr_dt, $caveat_details, '', '', '',$efiling_type);

                            if(isset($update_status) && !empty($update_status)) {
                                // echo $next_stage; '<br>';
                                // echo E_Filed_Stage; '<br>';

                                if($next_stage == E_Filed_Stage) {
                                    $cuase_title = '';
                                    if(!empty($caveatDetailsData[0]->pet_name) &&  !empty($caveatDetailsData[0]->res_name)){
                                        $cuase_title = $caveatDetailsData[0]->pet_name.' Vs '.$caveatDetailsData[0]->res_name;
                                    }
                                    else  if(!empty($caveatDetailsData[0]->pet_name)){
                                        $cuase_title = $caveatDetailsData[0]->pet_name;
                                    }
                                // echo 'cause'; '<br>';
                                //     pr($cuase_title);
                                    $createdby = !empty($caveatDetailsData[0]->createdby) ? (int)$caveatDetailsData[0]->createdby : NULL;
                                    if(isset($createdby) && !empty($createdby)){
                                        $params = array();
                                        $params['table_name'] ='efil.tbl_users';
                                        $params['whereFieldName'] ='id';
                                        $params['whereFieldValue'] = (int)$createdby;
                                        $params['is_active'] ='1';
                                        $userData = $this->Common_model->getData($params);
                                        $mobile = NULL;
                                        $email= NULL;
                                        $efiling_no = '';
                                        $first_name = '';
                                        $last_name ='';
                                        $userName = '';
                                         
                                            if(isset($userData) && !empty($userData)){
                                                $mobile = (!empty($userData[0]->moblie_number) && isset($userData[0]->moblie_number)) ? $userData[0]->moblie_number : NULL;
                                                $email= (!empty($userData[0]->emailid) && isset($userData[0]->emailid)) ? $userData[0]->emailid : NULL;
                                                $first_name= (!empty($userData[0]->first_name) && isset($userData[0]->first_name)) ? $userData[0]->first_name : NULL;
                                                $last_name= (!empty($userData[0]->last_name) && isset($userData[0]->last_name)) ? $userData[0]->last_name : NULL;
                                            }
                                        
                                    }
                                    if(isset($first_name) && !empty($first_name) && isset($last_name) && !empty($last_name)){
                                        $userName = $first_name.' '.$last_name;
                                    }
                                    // else if(isset($first_name) && !empty($first_name)){
                                    else{
                                        $userName = $first_name;
                                    }
                                    $caveatNo= $diary_no.' / '.$diary_year;
                                    $current_date = date('d-m-Y H:i:s');
                                    $message ='';
                                    $arr = array();
                                    $arr['table_name'] ='efil.tbl_efiling_nums';
                                    $arr['whereFieldName'] ='registration_id';
                                    $arr['whereFieldValue'] = (int)$registration_id;
                                    $arr['is_active'] ='1';
                                    $efilingNoData = $this->Common_model->getData($arr);
                                    if(isset($efilingNoData[0]->efiling_no) && !empty($efilingNoData[0]->efiling_no)){
                                        $efiling_no = $efilingNoData[0]->efiling_no;
                                    }
                                    if(isset($mobile) && !empty($mobile)){
                                        // $message = 'Your caveat efiling no. '.$efiling_no.' , '.$cuase_title.' is filed with Caveat No. '.$caveatNo.' on '.$current_date.'';
                                        $message = 'Your caveat efiling no. '.$efiling_no.' , '.$cuase_title.' is filed with Caveat No. '.$caveatNo.' on '.$current_date.' - Supreme Court of India';
                                        $smsRes = send_mobile_sms($mobile,$message,SCISMS_EFILING_CAVEAT);
                                        send_whatsapp_message($registration_id,$efiling_no," - files with Caveat Number $caveatNo ");

                                    }
                                    if(isset($email) && !empty($email)){
                                        $message = 'Dear '.$userName.' ,your caveat efiling no. '.$efiling_no.' , '.$cuase_title.' is filed with Caveat No. '.$caveatNo.' on '.$current_date.'';
                                        $to_email = $email;
                                        $subject = 'Caveat No. Generation.';
                                        send_mail_msg($to_email, $subject, $message, $to_user_name="");
                                    }
                                    $output['success'] = 'success';
                                    $output['message']='Caveat No. '.$diary_no.'/'.$diary_year.' has been created successfully!.';
                                } /*elseif ($next_stage == E_Filed_Stage) {
                                        $output['success'] = 'success';
                                        $output['message']='updated to Efiled Stage.';
                                    }*/
                            }
                            else{
                                $output['success'] = 'error';
                                $output['message']='';
                            }
                        }
                        else{
                            $output['success'] = 'error';
                            $output['message']='';
                        }
                    }
                    else{
                        $output['success'] = 'error';
                        $output['message']='';
                    }
                    break;
                default :
                    $output['success'] = 'error';
                    $output['message']='';
                    break;
            }
        }
        else {
            $output['success'] = 'error';
            $output['message']='';
        }
        return $output;
    }

    public function assignSrAdvocate(){
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        if(empty($_SESSION['login']['ref_m_usertype_id'])) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $postData = json_decode(file_get_contents('php://input'),true);
        if(isset($postData['advocateId']) && !empty($postData['advocateId']) && isset($postData['userType']) && !empty($postData['userType'])){
            $sradvocateId = url_decryption($postData['advocateId']);
            $userType = !empty($postData['userType']) ? trim($postData['userType']) : NULL;
            $diaryNo ='';
            $diaryYear ='';
            $output = array();
            if(!empty(getSessionData('diaryDetails'))){
                $srAdvocate = getSessionData('diaryDetails');
                $diaryNo = !empty($srAdvocate->diary_no) ? $srAdvocate->diary_no : NULL;
                $diaryYear = !empty($srAdvocate->diary_year) ? $srAdvocate->diary_year : NULL;
                $adv_sci_bar_id = !empty(getSessionData('login')['adv_sci_bar_id']) ? getSessionData('login')['adv_sci_bar_id'] : NULL;
                $inArr = array();
                $inArr['diary_no'] = $diaryNo.$diaryYear;
                $inArr['sr_advocate_id'] = $sradvocateId;
                $inArr['createdAt'] = date('Y-m-d H:i:s');
                $inArr['createdbyip'] = getClientIP();
                $inArr['createdby'] = $adv_sci_bar_id;
                $inArr['user_type'] = $userType;
                // $this->load->model('citation/Citation_model');
                $table = "efil.tbl_sr_advocate_engage";
                $res = $this->Citation_model->insertData($table,$inArr);
                if(isset($res) && !empty($res)){
                    $adv_sci_bar_id = !empty(getSessionData('login')['adv_sci_bar_id']) ? getSessionData('login')['adv_sci_bar_id'] : NULL;
                    $inArr = array();
                    $inArr['diary_no'] = $diaryNo . $diaryYear;
                    $inArr['sr_advocate_id'] = $sradvocateId;
                    $inArr['createdAt'] = date('Y-m-d H:i:s');
                    $inArr['createdbyip'] = getClientIP();
                    $inArr['createdby'] = $adv_sci_bar_id;
                    $inArr['is_active'] = true;
                    $inArr['user_type'] = $userType;
                    // $this->load->model('citation/Citation_model');
                    $table = "efil.tbl_sr_advocate_engage_history";
                    $res1 =$this->Citation_model->insertData($table, $inArr);
                    if(isset($res1) && !empty($res1)){
                        $output['1success'] = 'success';
                        $output['message']= ucfirst(str_replace('_', ' ',$userType)). ' has been engaged successfully!';
                    }
                    else{
                        $output['success'] = 'error';
                        $output['message']='Something went wrong,Please try again';
                    }
                }
                else{
                    $output['success'] = 'error';
                    $output['message']='Something went wrong,Please try again';
                }
            }
            else{
                $output['success'] = 'error';
                $output['message']='Please select sr.advocate/advocate';
            }
        }
        else{
            $output['success'] = 'error';
            $output['message']='Please select sr.advocate/advocate';
        }
        echo json_encode($output);
        exit(0);
    }
    public function deleteSrAdvocate(){
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        if(empty($_SESSION['login']['ref_m_usertype_id'])) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $output = array();
        $postData = json_decode(file_get_contents('php://input'),true);
        if(isset($postData['srAdvocateId']) && !empty($postData['srAdvocateId']) && isset($postData['engageType']) && !empty($postData['engageType']) && !empty($postData['diaryNo'])){
            $srAdvocateId = url_decryption($postData['srAdvocateId']);
            $engageType = $postData['engageType'];
            $userType = !empty($postData['userType']) ? $postData['userType'] : NULL;
            $diaryNo=$postData['diaryNo'];
            //$engageType: 1-engage,2-disengage
            $engageValue ='';
            $engageMessage = '';
            if(isset($engageType) && !empty($engageType) && $engageType == 1){
                $engageValue = true;
                $engageMessage = 'Re-engaged';
            }
            else if(isset($engageType) && !empty($engageType) && $engageType == 2){
                $engageValue = false;
                $engageMessage = 'disengaged';
            }
            $adv_sci_bar_id = !empty(getSessionData('login')['adv_sci_bar_id']) ? getSessionData('login')['adv_sci_bar_id'] : NULL;
            $upArr = array();
            if(isset($engageValue) && !empty($engageValue) && $engageValue == true){
                $upArr['createdAt'] = date('Y-m-d H:i:s');
            }
            else{
                $upArr['updatedAt'] = date('Y-m-d H:i:s');
            }
            $upArr['createdbyip'] = getClientIP();
            $upArr['updatedby'] = $adv_sci_bar_id;
            $upArr['is_active'] = $engageValue;
            // $this->load->model('common/Common_model');
            $params = array();
            $params['table_name'] = "efil.tbl_sr_advocate_engage";
            $whereArr['sr_advocate_id']=(int)$srAdvocateId;
            $whereArr['diary_no']=$diaryNo;
            $params['whereArr'] = $whereArr;
            $params['updateArr'] = $upArr;
            $res = $this->Common_model->updateTableDataWithWhereArray($params);
            //var_dump($res);
            if(isset($res) && !empty($res)){
                $diaryNo ='';
                $diaryYear ='';
                $output = array();
                if(!empty(getSessionData('diaryDetails'))) {
                    $srAdvocate = getSessionData('diaryDetails');
                    $diaryNo = !empty($srAdvocate->diary_no) ? $srAdvocate->diary_no : NULL;
                    $diaryYear = !empty($srAdvocate->diary_year) ? $srAdvocate->diary_year : NULL;
                    $adv_sci_bar_id = !empty(getSessionData('login')['adv_sci_bar_id']) ? getSessionData('login')['adv_sci_bar_id'] : NULL;
                    $inArr = array();
                    $inArr['diary_no'] = $diaryNo . $diaryYear;
                    $inArr['sr_advocate_id'] = $srAdvocateId;
                    $inArr['createdAt'] = date('Y-m-d H:i:s');
                    $inArr['createdbyip'] = getClientIP();
                    $inArr['createdby'] = $adv_sci_bar_id;
                    $inArr['is_active'] = $engageValue;
                    $inArr['user_type'] = $userType;
                    // $this->load->model('citation/Citation_model');
                    $table = "efil.tbl_sr_advocate_engage_history";
                    $this->Citation_model->insertData($table, $inArr);
                }
                $output['success'] = 'success';
                $output['message']= ucfirst(str_replace('_',' ',$userType)).' has been '.$engageMessage.' successfully!';
            }
            else{
                $output['success'] = 'error';
                $output['message']='Something went wrong,Please try again';
            }

        }
        echo json_encode($output);
        exit(0);

    }


}