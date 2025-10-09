<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Common\CommonModel;
use App\Models\AdjournmentLetter\AdjournmentModel;
use App\Models\Dashboard\StageslistModel;
use App\Models\Report\ReportModel;
use App\Models\Certificate\CertificateModel;
use App\Models\Mentioning\MentioningModel;
use App\Models\Citation\CitationModel;
use App\Libraries\webservices\Efiling_webservices;
use App\Libraries\webservices\Ecoping_webservices;
use Config\Database;
use Exception;
use stdClass;

class GetEfiledCasesController extends BaseController
{
    protected $StageslistModel;

    public function __construct()
    {
        parent::__construct();
        $this->StageslistModel = new StageslistModel();
    }
    public function index()
    {
        return render('responsive_variant.dashboard.e_filed_cases');
    }

    public function getData()
    {
        if(empty(getSessionData('login'))){
            return response()->redirect(base_url('/')); 
        }
        $stageIds = array(1);
        $createdBy = getSessionData('login')['id'];
        $data=array();
        $notIn = 1;
        $request = service('request');
        $postData = $request->getPost();
        $dtpostData = $postData['data'];
        $draw = $dtpostData['draw'];
        $start = $dtpostData['start'];
        $rowperpage = $dtpostData['length']; // Rows display per page
        $columnIndex = $dtpostData['order'][0]['column']; // Column index
        $columnName = $dtpostData['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $dtpostData['order'][0]['dir']; // asc or desc
        $searchValue = $dtpostData['search']['value']; // Search value
        $db = Database::connect();
        $builder = $db->table('efil.tbl_efiling_nums as en');

        $builder->select([
            'mtds.user_stage_name',
            'en.efiling_for_type_id',
            'en.efiling_for_id',
            'en.ref_m_efiled_type_id',
            'en.efiling_no',
            'en.efiling_year',
            'en.registration_id',
            'en.allocated_on',
            'et.efiling_type',
            'cs.stage_id',
            'cs.activated_on',
            'en.sub_created_by',
            'new_case_cd.cause_title as ecase_cause_title',
            'new_case_cd.sc_diary_num',
            'new_case_cd.sc_diary_year',
            'new_case_cd.sc_diary_date',
            'new_case_cd.sc_display_num',
            'new_case_cd.sc_reg_date',
            'sc_case.diary_no',
            'sc_case.diary_year',
            'sc_case.reg_no_display',
            'sc_case.cause_title',
            'ec.pet_name',
            'ec.res_name',
            'ec.orgid',
            'ec.resorgid',
            'ec.ref_m_efiling_nums_registration_id as caveat_reg',
            'users.first_name',
            'users.last_name',
            'allocated_users.first_name as allocated_user_first_name',
            'allocated_users.last_name as allocated_user_last_name',
            'allocated_users.id as allocated_to_user_id',
            'tea.allocated_on as allocated_to_da_on',
            '(CASE WHEN en.ref_m_efiled_type_id IN (1,3,5) AND NOT (string_to_array(breadcrumb_status, \',\') && array[\'2\',\'3\']) THEN \'Basic Detail\' 
            WHEN en.ref_m_efiled_type_id IN (1,5) AND NOT (string_to_array(breadcrumb_status, \',\') && array[\'6\']) THEN \'Earlier Court\' 
            WHEN en.ref_m_efiled_type_id IN (1,5) AND NOT (string_to_array(breadcrumb_status, \',\') && array[\'7\']) THEN \'Earlier Court\' 
            WHEN en.ref_m_efiled_type_id IN (1,5) AND NOT (string_to_array(breadcrumb_status, \',\') && array[\'9\']) THEN \'Index\' 
            WHEN en.ref_m_efiled_type_id IN (1,5) AND NOT (string_to_array(breadcrumb_status, \',\') && array[\'10\']) THEN \'Payment\'
            WHEN en.ref_m_efiled_type_id IN (2,4) AND NOT (string_to_array(breadcrumb_status, \',\') && array[\'2\']) THEN \'Appearing For\' 
            WHEN en.ref_m_efiled_type_id IN (2,4) AND NOT (string_to_array(breadcrumb_status, \',\') && array[\'5\']) THEN \'Index\' 
            WHEN en.ref_m_efiled_type_id IN (2,4) AND NOT (string_to_array(breadcrumb_status, \',\') && array[\'' . NEW_CASE_COURT_FEE . '\']) THEN \'Court Fee\' 
            WHEN en.ref_m_efiled_type_id IN (2) AND NOT (string_to_array(breadcrumb_status, \',\') && array[\'7\']) THEN \'Share Document\' 
            WHEN ( (en.ref_m_efiled_type_id IN (1,5) AND NOT (string_to_array(breadcrumb_status, \',\') && array[\'13\'])) 
                    OR (en.ref_m_efiled_type_id IN (2,4) AND NOT (string_to_array(breadcrumb_status, \',\') && array[\'9\'])) 
            ) THEN \'Final Submit\' 
        END) as pendingStage',
            'ec.caveat_num',
            'ec.caveat_year',
            '(SELECT CONCAT(department_name, \' <br>(\', ministry_name, \')\') FROM efil.department_filings df 
            JOIN efil.m_departments md ON md.id = df.ref_department_id 
            WHERE registration_id=en.registration_id) as dept_file',
            '(SELECT \'Entered by Clerk\' FROM efil.clerk_filings WHERE registration_id=en.registration_id) as clerk_file',
            'jsonai.registration_id as registration_id_jsonai',
            'jsonai.id as id_jsonai'
        ]);
        $builder->join('efil.tbl_efiling_num_status as cs', 'en.registration_id = cs.registration_id');
        $builder->join('public.tbl_efiling_caveat as ec', 'en.registration_id = ec.ref_m_efiling_nums_registration_id', 'left');
        $builder->join('efil.m_tbl_efiling_type as et', 'en.ref_m_efiled_type_id=et.id');
        $builder->join('efil.tbl_case_details as new_case_cd', 'en.registration_id = new_case_cd.registration_id', 'left');
        $builder->join('efil.tbl_misc_docs_ia as mdia', 'en.registration_id = mdia.registration_id', 'left');
        $builder->join('efil.tbl_sci_cases as sc_case', 'sc_case.diary_no=mdia.diary_no AND sc_case.diary_year = mdia.diary_year', 'left');
        $builder->join('efil.tbl_users as users', 'en.created_by=users.id', 'left');
        $builder->join('efil.m_tbl_dashboard_stages as mtds', 'cs.stage_id = mtds.stage_id ', 'left');
        $builder->join('(SELECT * FROM efil.tbl_efiling_allocation tea ORDER BY tea.allocated_on DESC LIMIT 1) as tea', 'en.registration_id = tea.registration_id', 'left');
        $builder->join('efil.tbl_users as allocated_users', 'tea.admin_id=allocated_users.id', 'left');
        $builder->join('efil.tbl_uploaded_pdfs_jsonai as jsonai', "en.registration_id = jsonai.registration_id AND jsonai.iitm_api_json is not null AND jsonai.is_deleted ='false'", 'left');
        $builder->where('cs.is_active', 'TRUE');
        $builder->where('en.is_active', 'TRUE');
        $builder->where('en.is_deleted', 'FALSE');
        $builder->whereIn('en.ref_m_efiled_type_id', [1, 2, 4, 8, 9, 12, 13]);
        if ($notIn == 0) {
            $builder->whereIn('cs.stage_id', $stageIds);
        } else {
            $builder->whereNotIn('cs.stage_id', $stageIds);
        }
        if($searchValue !== null){
            $builder->groupStart();
            $searchValue = trim($searchValue);
            if(isDateString($searchValue)){
                $date = parseDate($searchValue);
                $builder->where('cs.activated_on >=', $date.' 00:00:00');
                $builder->where('cs.activated_on <=', $date.' 23:59:59');
            }else{
                $builder->where('lower(en.efiling_no)', strtolower($searchValue));
                $builder->orLike('lower(en.efiling_no)', strtolower($searchValue));
                $builder->orWhere('sc_case.diary_no', $searchValue);
                $builder->orLike('sc_case.diary_no', $searchValue);
                $builder->orWhere('sc_case.diary_year', $searchValue);
                $builder->orLike('sc_case.diary_year', $searchValue);
                $builder->orWhere('lower(mtds.user_stage_name)', strtolower($searchValue));
                $builder->orLike('lower(mtds.user_stage_name)', strtolower($searchValue));
                $builder->orWhere('lower(sc_case.reg_no_display)', strtolower($searchValue));
                $builder->orLike('lower(sc_case.reg_no_display)', strtolower($searchValue));
                $builder->orWhere('lower(sc_case.cause_title)', strtolower($searchValue));
                $builder->orLike('lower(sc_case.cause_title)', strtolower($searchValue));
                $builder->orLike('lower(et.efiling_type)', strtolower($searchValue));
            }

            $builder->groupEnd();
        }

        $builder->groupStart();
        if ($_SESSION['login']['ref_m_usertype_id'] == USER_DEPARTMENT || $_SESSION['login']['ref_m_usertype_id'] == USER_CLERK) {
            $builder->where('en.sub_created_by', $createdBy);
        } else {
            $builder->where('en.created_by', $createdBy);
        }

        if ($_SESSION['login']['ref_m_usertype_id'] == USER_ADVOCATE && !in_array(4, $stageIds)) {
            $builder->orWhere('en.registration_id IN (SELECT registration_id FROM efil.department_filings WHERE aor_code::varchar=(SELECT aor_code FROM efil.tbl_users WHERE id=' . $db->escape($createdBy) . '))');
            $builder->orWhere('en.registration_id IN (SELECT registration_id FROM efil.clerk_filings WHERE aor_code::varchar=(SELECT aor_code FROM efil.tbl_users WHERE id=' . $db->escape($createdBy) . '))');
        }

        if ($_SESSION['login']['ref_m_usertype_id'] == USER_DEPARTMENT && !in_array(4, $stageIds)) {
            $builder->orWhere('en.registration_id IN (SELECT registration_id FROM efil.department_filings WHERE ref_department_id=' . $this->db->escape($_SESSION['login']['department_id']) . ')');
        }

        if ($_SESSION['login']['ref_m_usertype_id'] == USER_CLERK && !in_array(4, $stageIds)) {
            // $builder->orWhereIn('en.created_by', [1597]);
        }
        $builder->groupEnd();
        $builder->orderBy('cs.activated_on', 'DESC');
        $query = $builder->get($rowperpage, $start);
        $qdataCount = $this->StageslistModel->get_efilied_nums_stage_wise_list(array(1), getSessionData('login')['id'], 1, null, null, $searchValue);
        $totalRecords = !empty($qdataCount)?count($qdataCount):0;
        $totalRecordwithFilter = !empty($qdataCount)?count($qdataCount):0;
        $final_submitted_applications = $query->getResult();
        $resData = array();
        if (isset($final_submitted_applications) && !empty($final_submitted_applications) && count($final_submitted_applications) > 0) {
            $i = $dtpostData['start']+1;
            $allocated = '';
            foreach ($final_submitted_applications as $key=>$re) {
                $stages = $re->stage_id;
                $exclude_stages_array = array(8, 9, 11, 13, 34, 35, 36, 37);
                $fil_no = $reg_no = $case_details = $cnr_number = $cino = '';
                $fil_ia_no = $reg_ia_no = $cause_title = $fil_case_no = $reg_case_no = $diary_no = $lbl_for_doc_no = $fil_misc_doc_ia_no = '';
                $efiling_types_array = array(E_FILING_TYPE_MISC_DOCS, E_FILING_TYPE_IA, E_FILING_TYPE_MENTIONING, OLD_CASES_REFILING);
                if (in_array($re->ref_m_efiled_type_id, $efiling_types_array)) {
                    if ($re->ref_m_efiled_type_id == E_FILING_TYPE_MISC_DOCS) {
                        $type = 'Misc. Docs';
                        $lbl_for_doc_no = '<b>Misc. Doc. No.</b> : ';
                        $redirect_url = base_url('miscellaneous_docs/DefaultController');
                        $redirect_url = base_url('case/document/crud_registration');
                        $recheck_url = 'case/document/crud_registration';
                    } elseif ($re->ref_m_efiled_type_id == E_FILING_TYPE_IA) {
                        $type = 'Interim Application';
                        $lbl_for_doc_no = '<b>IA Diary No.</b> : ';
                        $redirect_url = base_url('IA/DefaultController');
                        $redirect_url = base_url('case/interim_application/crud_registration');
                        $recheck_url = 'case/interim_application/crud_registration';
                    } elseif ($re->ref_m_efiled_type_id == E_FILING_TYPE_MENTIONING) {
                        $type = 'Mentioning';
                        $lbl_for_doc_no = '';
                        $redirect_url = base_url('case/mentioning');
                    } elseif ($re->ref_m_efiled_type_id == E_FILING_TYPE_CITATION) {
                        $type = 'Citation';
                        $lbl_for_doc_no = '';
                        $redirect_url = base_url('citation/DefaultController');
                        $redirect_url = base_url('case/citation');
                    } elseif ($re->ref_m_efiled_type_id == E_FILING_TYPE_CAVEAT) {
                        $type = 'Caveat';
                        $lbl_for_doc_no = '';
                        $redirect_url = base_url('caveat');
                    } elseif ($re->ref_m_efiled_type_id == OLD_CASES_REFILING) {
                        $type = 'Old Case Refiling';
                        $lbl_for_doc_no = '';
                        $redirect_url = base_url('case/refile_old_efiling_cases/crud_registration');
                    }
                    if (isset($re->loose_doc_no) && $re->loose_doc_no != '' && $re->loose_doc_year != '') {
                        $fil_misc_doc_ia_no = $lbl_for_doc_no . escape_data($re->loose_doc_no) . ' / ' . escape_data($re->loose_doc_year) . '<br/> ';
                    } else {
                        $fil_misc_doc_ia_no = '';
                    }
                    if ($re->diary_no != '' && $re->diary_year != '') {
                        $diary_no = '<b>Diary No.</b> : ' . escape_data($re->diary_no) . ' / ' . escape_data($re->diary_year) . '<br/> ';
                    } else {
                        $diary_no = '';
                    }
                    if ($re->reg_no_display != '') {
                        $reg_no = '<b>Registration No.</b> : ' . escape_data($re->reg_no_display) . '<br/> ';
                    } else {
                        $reg_no = '';
                    }
                    $case_details = $fil_ia_no . '<b>Filed In</b> <br>' . $diary_no . $reg_no . $re->cause_title;
                    if ($diary_no != '') {
                        $case_details = '<a onClick="open_case_statusStop()" title="show CaseStatus"  data-diary_no="' . $re->diary_no . '" data-diary_year="' . $re->diary_year . '">' . $case_details . '</a>';
                    }
                } elseif ($re->ref_m_efiled_type_id == E_FILING_TYPE_NEW_CASE) {
                    $type = 'New Case';
                    $cause_title = !empty($re->ecase_cause_title) ? escape_data(strtoupper($re->ecase_cause_title)) : '';
                    $cause_title = !empty($cause_title) ? str_replace('VS.', '<b>Vs.</b>', $cause_title) : '';
                    if ($re->sc_diary_num != '') {
                        $diary_no = '<b>Diary No.</b> : ' . escape_data($re->sc_diary_num) . '/' . escape_data($re->sc_diary_year) . '<br/> ';
                    } else {
                        $diary_no = '';
                    }
                    if ($re->reg_no_display != '') {
                        $reg_no = '<b>Registration No.</b> : ' . escape_data($re->sc_display_num) . '<br/> ';
                    } else {
                        $reg_no = '';
                    }
                    $case_details =  $diary_no . $reg_no . $cause_title;
                    if ($diary_no != '') {
                        $case_details = '<a onClick="open_case_statusStop()" title="show CaseStatus"  data-diary_no="' . $re->sc_diary_num . '" data-diary_year="' . $re->sc_diary_year . '">' . $case_details . '</a>';
                    }
                    $redirect_url = base_url('newcase/defaultController');
                    $recheck_url = 'case/crud';
                    $redirect_url = base_url('case/crud');
                } elseif ($re->ref_m_efiled_type_id == E_FILING_TYPE_CAVEAT) {
                    $efiling_civil_data = getEfilingCivilDetails($re->registration_id);
                    $type = 'Caveat';
                    $caveat_no = '';
                    $caveator_name = '';
                    $caveatee_name = '';
                    $caveator_name_vs_caveatee_name = '';
                    $caveator_details = '';
                    $caveatee_details = '';
                    if (isset($efiling_civil_data) && !empty($efiling_civil_data)) {
                        if (isset($efiling_civil_data[0]['orgid']) && !empty($efiling_civil_data[0]['orgid']) && $efiling_civil_data[0]['orgid'] != 'I') {
                            $org_dept_name = !empty($efiling_civil_data[0]['org_dept_name']) ? '<br/>Department Name : ' . $efiling_civil_data[0]['org_dept_name'] . '' : '';
                            $org_post_name = !empty($efiling_civil_data[0]['org_post_name']) ? 'Post Name : ' . $efiling_civil_data[0]['org_post_name'] . '' : '';
                            $org_state_name = !empty($efiling_civil_data[0]['org_state_name']) ? 'State Name : ' . $efiling_civil_data[0]['org_state_name'] : '';
                            $caveator_details = $org_dept_name . $org_post_name . $org_state_name;
                            if (!empty($caveator_details)) {
                                $caveator_details = ' <b>(</b>' . $caveator_details . '<b>)</b>';
                            }
                        }
                        if (isset($efiling_civil_data[0]['resorgid']) && !empty($efiling_civil_data[0]['resorgid']) && $efiling_civil_data[0]['resorgid'] != 'I') {
                            $res_org_dept_name = !empty($efiling_civil_data[0]['res_org_dept_name']) ? '<br/>Department Name : ' . $efiling_civil_data[0]['res_org_dept_name'] . '' : '';
                            $res_org_post_name = !empty($efiling_civil_data[0]['res_org_post_name']) ? 'Post Name : ' . $efiling_civil_data[0]['res_org_post_name'] . '' : '';
                            $res_org_state_name = !empty($efiling_civil_data[0]['res_org_state_name']) ? 'State Name : ' . $efiling_civil_data[0]['res_org_state_name'] : '';
                            $caveatee_details = $res_org_dept_name . $res_org_post_name . $res_org_state_name;
                            if (!empty($caveatee_details)) {
                                $caveatee_details = ' <b>(</b>' . $caveatee_details . '<b>)</b>';
                            }
                        }
                        if (isset($efiling_civil_data[0]['pet_name']) && !empty($efiling_civil_data[0]['pet_name'])) {
                            $caveator_name = $efiling_civil_data[0]['pet_name'];
                        } else if (isset($efiling_civil_data[0]['orgid']) && !empty($efiling_civil_data[0]['orgid']) && $efiling_civil_data[0]['orgid'] == 'D1') {
                            $caveator_name = 'State Department';
                        } else if (isset($efiling_civil_data[0]['orgid']) && !empty($efiling_civil_data[0]['orgid']) && $efiling_civil_data[0]['orgid'] == 'D2') {
                            $caveator_name = 'Central Department';
                        } else if (isset($efiling_civil_data[0]['orgid']) && !empty($efiling_civil_data[0]['orgid']) && $efiling_civil_data[0]['orgid'] == 'D3') {
                            $caveator_name = 'Other Organisation';
                        }
                        if (isset($efiling_civil_data[0]['res_name']) && !empty($efiling_civil_data[0]['res_name'])) {
                            $caveatee_name = $efiling_civil_data[0]['res_name'];
                        } else if (isset($efiling_civil_data[0]['resorgid']) && !empty($efiling_civil_data[0]['resorgid']) && $efiling_civil_data[0]['resorgid'] == 'D1') {
                            $caveatee_name = 'State Department';
                        } else if (isset($efiling_civil_data[0]['resorgid']) && !empty($efiling_civil_data[0]['resorgid']) && $efiling_civil_data[0]['resorgid'] == 'D2') {
                            $caveatee_name = 'Central Department';
                        } else if (isset($efiling_civil_data[0]['resorgid']) && !empty($efiling_civil_data[0]['resorgid']) && $efiling_civil_data[0]['resorgid'] == 'D3') {
                            $caveatee_name = 'Other Organisation';
                        }
                        $caveator_name_vs_caveatee_name = $caveator_name . $caveator_details . '<b> Vs. </b>' . $caveatee_name . $caveatee_details . '<br/> ';
                        if (
                            isset($re->caveat_num) && !empty($re->caveat_num)
                            && isset($re->caveat_num) && !empty($re->caveat_num)
                        ) {
                            $caveat_no = '<b>Filed In</b><br/><b>Caveat No.</b> : ' . $re->caveat_num . ' / ' . $re->caveat_year . '<br/> ';
                        }
                    }
                    $cause_title = !empty($re->pet_name) ? escape_data(strtoupper($re->pet_name)) : '';
                    $cause_title = !empty($caveator_name_vs_caveatee_name) ? escape_data(strtoupper($caveator_name_vs_caveatee_name)) : '';
                    if ($re->sc_diary_num != '') {
                        $diary_no = '<b>Diary No.</b> : ' . escape_data($re->sc_diary_num) . '/' . escape_data($re->sc_diary_year) . '<br/> ';
                    } else {
                        $diary_no = '';
                    }
                    if ($re->reg_no_display != '') {
                        $reg_no = '<b>Registration No.</b> : ' . escape_data($re->sc_display_num) . '<br/> ';
                    } else {
                        $reg_no = '';
                    }
                    $case_details =  $caveat_no . $diary_no . $reg_no . $cause_title;
                    if ($diary_no != '') {
                        $case_details = '<a>' . $case_details . '</a>';
                    }
                    $recheck_url = 'case/caveat/crud';
                    $redirect_url = base_url('case/caveat/crud');
                } elseif ($re->ref_m_efiled_type_id == E_FILING_TYPE_CERTIFICATE_REQUEST) {
                    $api_certificate_str = file_get_contents(env('API_PRISON') . '/certificate_status_efile/' . $re->efiling_no);
                    $api_certificate = json_decode($api_certificate_str);
                    $api_certificateData = $api_certificate->result;
                    $api_certificate_efiling_no = $api_certificateData->efiling_no;
                    $api_certificate_request_no = $api_certificateData->request_no;
                    $type = 'Certificate';
                    $lbl_for_doc_no = '';
                    if (!empty($api_certificate_efiling_no)) {
                        $redirect_url = '';
                        $redirect_url = base_url('case/certificate/' . $re->registration_id);
                    } else {
                        $redirect_url = '';
                    }
                    if ($re->diary_no != '' && $re->diary_year != '') {
                        $diary_no = '<b>Diary No.</b> : ' . escape_data($re->diary_no) . ' / ' . escape_data($re->diary_year) . '<br/> ';
                    } else {
                        $diary_no = '';
                    }
                    if ($re->reg_no_display != '') {
                        $reg_no = '<b>Registration No.</b> : ' . escape_data($re->reg_no_display) . '<br/> ';
                    } else {
                        $reg_no = '';
                    }
                    $case_details = $fil_ia_no . '<b>Filed In</b> <br>' . $diary_no . $reg_no . $re->cause_title;
                    if ($diary_no != '') {
                        $case_details = '<a onClick="open_case_statusStop()" title="show CaseStatus"  data-diary_no="' . $re->diary_no . '" data-diary_year="' . $re->diary_year . '">' . $case_details . '</a>';
                    }
                }

                if (!empty($api_certificate_efiling_no) && $re->ref_m_efiled_type_id == E_FILING_TYPE_CERTIFICATE_REQUEST) {
                    $user_stage_name = $re->user_stage_name;
                } else {
                    $user_stage_name = $re->user_stage_name;
                }
                //--------------------Pending Acceptence-------------------------->
                $arrayStage = array(Initial_Approaval_Pending_Stage, Initial_Defects_Cured_Stage, TRASH_STAGE);
                $case_details = '<a onClick="open_case_statusStop()" title="show CaseStatus"  data-diary_no="' . $re->diary_no . '" data-diary_year="' . $re->diary_year . '">' . $case_details . '</a>';
                if (in_array($stages, $arrayStage)) {
                    $efiling_no = '<a
                                href="' . $redirect_url . '/' . url_encryption(trim($re->registration_id . '#' . $re->ref_m_efiled_type_id . '#' . Initial_Approaval_Pending_Stage)) . '">
                                <b>' . htmlentities(efile_preview($re->efiling_no, ENT_QUOTES)) . '</b><br>';
                    if (isset($re->efiling_for_name) && !empty($re->efiling_for_name) && ($re->efiling_for_name) != NULL) {
                        $efiling_no .=  htmlentities($re->efiling_for_name, ENT_QUOTES);
                    }
                    $efiling_no .= '</a>';
                    $type = htmlentities($type, ENT_QUOTES);
                    $submitted_on = htmlentities(date("d/m/Y h:i:s A", strtotime($re->activated_on, ENT_QUOTES)));
                    $action = '&nbsp;';
                }
                //--------------------Draft------------------>
                if ($stages == Draft_Stage) {
                    $efiling_no = "<b>" . htmlentities(efile_preview($re->efiling_no, ENT_QUOTES)) . "</b>" . "<br>";
                    if (isset($re->efiling_for_name) && !empty($re->efiling_for_name) && ($re->efiling_for_name) != NULL) {
                        $efiling_no .= htmlentities($re->efiling_for_name, ENT_QUOTES) . $allocated;
                    }
                    $type = htmlentities($type, ENT_QUOTES);
                    $submitted_on = htmlentities(date("d/m/Y h:i:s A", strtotime(htmlentities($re->activated_on, ENT_QUOTES))));
                    $action = '<a class="form-control btn btn-success"
                                href="' . $redirect_url . '/' . url_encryption(trim($re->registration_id . '#' . $re->ref_m_efiled_type_id . '#' . Draft_Stage)) . '">' . htmlentities("View", ENT_QUOTES) . '</a>';
                }
                //<!--------------------For Compliance------------------>
                if ($stages == Initial_Defected_Stage) {
                    $efiling_no = "<b>" . htmlentities(efile_preview($re->efiling_no, ENT_QUOTES)) . "</b>" . "<br>";
                    if (isset($re->efiling_for_name) && !empty($re->efiling_for_name) && ($re->efiling_for_name) != NULL) {
                        $efiling_no .= htmlentities($re->efiling_for_name, ENT_QUOTES) . $allocated;
                    }
                    $type = htmlentities($type, ENT_QUOTES);
                    $submitted_on = htmlentities(date("d/m/Y h:i:s A", strtotime($re->activated_on, ENT_QUOTES)));
                    $action = '<a class="btn btn-primary"
                                href="' . base_url($recheck_url . '/' . url_encryption((trim($re->registration_id . '#' . $re->ref_m_efiled_type_id . '#' . Initial_Defected_Stage)))) . '">
                                <span
                                    class="uk-label md-bg-grey-900">' . htmlentities("Re-Submit", ENT_QUOTES) . '</span></a>';
                }
                //<!--------------------Make Payment------------------>
                if ($stages == Initial_Approved_Stage) {
                    $efiling_no = "<b>" . htmlentities(efile_preview($re->efiling_no, ENT_QUOTES)) . "</b>" . "<br>";
                    if (isset($re->efiling_for_name) && !empty($re->efiling_for_name) && ($re->efiling_for_name) != NULL) {
                        $efiling_no .= htmlentities($re->efiling_for_name, ENT_QUOTES) . $allocated;
                    }
                    $type = htmlentities($type, ENT_QUOTES);
                    $submitted_on = htmlentities(date("d/m/Y h:i:s A", strtotime(htmlentities($re->activated_on, ENT_QUOTES))));
                    $action = '<a class="form-control btn btn-success"
                                href="' . $redirect_url . '/' . url_encryption(trim($re->registration_id . '#' . $re->ref_m_efiled_type_id . '#' . Initial_Approved_Stage)) . '">' . htmlentities("Make Payment", ENT_QUOTES) . '</a>';
                }
                //<!--------------------Payment Receipts------------------>
                if ($stages == Pending_Payment_Acceptance) {
                    $efiling_no = '<a class="form-control btn btn-success"
                                href="' . $redirect_url . '/' . url_encryption(trim($re->registration_id . '#' . $re->ref_m_efiled_type_id . '#' . Pending_Payment_Acceptance)) . '"><b>' . htmlentities(efile_preview($re->efiling_no, ENT_QUOTES)) . '</b><br>';
                    if (isset($re->efiling_for_name) && !empty($re->efiling_for_name) && ($re->efiling_for_name) != NULL) {
                        $efiling_no .= htmlentities($re->efiling_for_name, ENT_QUOTES) . $allocated;
                    }
                    $efiling_no .= '</a>';
                    $type = htmlentities($type, ENT_QUOTES);
                    $action = "<b>" . htmlentities(efile_preview($re->efiling_no, ENT_QUOTES)) . "</b>" . "<br>";
                    if (isset($re->efiling_for_name) && !empty($re->efiling_for_name) && ($re->efiling_for_name) != NULL) {
                        $action .=  htmlentities($re->efiling_for_name, ENT_QUOTES) . $allocated;
                    }
                    $submitted_on = date("d/m/Y h:i:s A", strtotime(htmlentities($re->activated_on, ENT_QUOTES)));
                }
                //<!--------------------Pending Scrutiny------------------>
                if ($stages == I_B_Approval_Pending_Stage) {
                    $efiling_no = '<a
                                href="' . $redirect_url . '/' . url_encryption(trim($re->registration_id . '#' . $re->ref_m_efiled_type_id . '#' . I_B_Approval_Pending_Stage)) . '"><b>' . htmlentities(efile_preview($re->efiling_no, ENT_QUOTES)) . '</b><br>';
                    if (isset($re->efiling_for_name) && !empty($re->efiling_for_name) && ($re->efiling_for_name) != NULL) {
                        $efiling_no .= htmlentities($re->efiling_for_name, ENT_QUOTES) . $allocated;
                    }
                    $efiling_no .= '</a>';
                    $type = htmlentities($type, ENT_QUOTES);
                    $submitted_on = htmlentities(date("d/m/Y h:i:s A", strtotime($re->activated_on, ENT_QUOTES)));
                    $action = '&nbsp;';
                }
                //<!--------------------Defective------------------>
                if ($stages == I_B_Defected_Stage) {
                    if (isset($re->cnr_num) && !empty($re->cnr_num)) {
                        $cino = $re->cnr_num;
                    } elseif (isset($re->cino) && !empty($re->cino)) {
                        $cino = $re->cino;
                    }
                    $efiling_no = "<b>" . htmlentities(efile_preview($re->efiling_no, ENT_QUOTES)) . "</b>" . "<br>";
                    if (isset($re->efiling_for_name) && !empty($re->efiling_for_name) && ($re->efiling_for_name) != NULL) {
                        $efiling_no .= htmlentities($re->efiling_for_name, ENT_QUOTES) . $allocated;
                    }
                    $type = htmlentities($type, ENT_QUOTES);
                    $submitted_on = htmlentities(date("d/m/Y h:i:s A", strtotime($re->activated_on, ENT_QUOTES)));
                    $action = '<a class="btn btn-primary"
                                href="' . $redirect_url . '/' . url_encryption(trim($re->registration_id . '#' . $re->ref_m_efiled_type_id . '#' . I_B_Defected_Stage)) . '">' . htmlentities("Cure Defects", ENT_QUOTES) . '</a>';
                }
                //<!--------------------E-filed Cases------------------>
                if ($stages == E_Filed_Stage) {
                    $efiling_no = '<a
                                href="' . $redirect_url . '/' . url_encryption(trim($re->registration_id . '#' . $re->ref_m_efiled_type_id . '#' . E_Filed_Stage)) . '"><b>' . htmlentities(efile_preview($re->efiling_no, ENT_QUOTES)) . '</b><br>';
                    if (isset($re->efiling_for_name) && !empty($re->efiling_for_name) && ($re->efiling_for_name) != NULL) {
                        $efiling_no .= htmlentities($re->efiling_for_name, ENT_QUOTES);
                    }
                    $efiling_no .= '</a>';
                    $type = htmlentities($type, ENT_QUOTES);
                    $submitted_on = htmlentities(date("d/m/Y h:i:s A", strtotime($re->activated_on, ENT_QUOTES)));
                    $action = '&nbsp;';
                }
                //<!--------------------E-filed Misc. Documents------------------>
                if ($stages == Document_E_Filed) {
                    $efiling_no = '<a
                                href="' . $redirect_url . '/' . url_encryption(trim($re->registration_id . '#' . $re->ref_m_efiled_type_id . '#' . Document_E_Filed)) . '"><b>' . htmlentities(efile_preview($re->efiling_no, ENT_QUOTES)) . '</b><br>';
                    if (isset($re->efiling_for_name) && !empty($re->efiling_for_name) && ($re->efiling_for_name) != NULL) {
                        $efiling_no .= htmlentities($re->efiling_for_name, ENT_QUOTES);
                    }
                    $efiling_no .= '</a>';
                    $type = htmlentities($type, ENT_QUOTES);
                    $submitted_on = htmlentities(date("d/m/Y h:i:s A", strtotime(htmlentities($re->activated_on, ENT_QUOTES))));
                    $action = '&nbsp;';
                }
                //<!--------------------E-filed Misc. Documents------------------>
                if ($stages == DEFICIT_COURT_FEE_E_FILED) {
                    $efiling_no = '<a
                                href="' . $redirect_url . '/' . url_encryption(trim($re->registration_id . '#' . $re->ref_m_efiled_type_id . '#' . I_B_Rejected_Stage)) . '"><b>' . htmlentities(efile_preview($re->efiling_no, ENT_QUOTES)) . '</b><br>';
                    if (isset($re->efiling_for_name) && !empty($re->efiling_for_name) && ($re->efiling_for_name) != NULL) {
                        $efiling_no .= htmlentities($re->efiling_for_name, ENT_QUOTES);
                    }
                    $efiling_no .= '</a>';
                    $type = htmlentities($type, ENT_QUOTES);
                    $submitted_on = htmlentities(date("d/m/Y h:i:s A", strtotime(htmlentities($re->activated_on, ENT_QUOTES))));
                    $action = '&nbsp;';
                }
                if ($stages == I_B_Rejected_Stage) {
                    $efiling_no = '<a
                                href="' . $redirect_url . '/' . url_encryption(trim($re->registration_id . '#' . $re->ref_m_efiled_type_id . '#' . I_B_Rejected_Stage)) . '"><b>' . htmlentities(efile_preview($re->efiling_no, ENT_QUOTES)) . '</b><br>';
                    if (isset($re->efiling_for_name) && !empty($re->efiling_for_name) && ($re->efiling_for_name) != NULL) {
                        $efiling_no .= htmlentities($re->efiling_for_name, ENT_QUOTES);
                    }
                    $efiling_no .= '</a>';
                    $type = htmlentities($type, ENT_QUOTES);
                    $submitted_on = htmlentities(date("d/m/Y h:i:s A", strtotime(htmlentities($re->activated_on, ENT_QUOTES))));
                    if ($re->stage_id == I_B_Rejected_Stage) {
                        $action = htmlentities('Filing Section', ENT_QUOTES);
                    } elseif ($re->stage_id == E_REJECTED_STAGE) {
                        $action = htmlentities('eFiling Admin', ENT_QUOTES);
                    } else {
                        $action = '&nbsp;';
                    }
                }
                if ($stages == DEFICIT_COURT_FEE) {
                    $efiling_no = efile_preview(htmlentities($re->efiling_no, ENT_QUOTES));
                    $type = htmlentities($type, ENT_QUOTES);
                    $submitted_on = htmlentities(date("d/m/Y h:i:s A", strtotime(htmlentities($re->activated_on, ENT_QUOTES))));
                    $action = '<a class="form-control btn btn-success" href="' . $redirect_url . '/' . url_encryption(trim($re->registration_id . '#' . $re->ref_m_efiled_type_id . '#' . DEFICIT_COURT_FEE)) . '">' . htmlentities("View", ENT_QUOTES) . '</a>';
                }
                if ($stages == LODGING_STAGE) {
                    $efiling_no = '<a href="' . $redirect_url . '/' . url_encryption(trim($re->registration_id . '#' . $re->ref_m_efiled_type_id . '#' . LODGING_STAGE)) . '"><b>"' . htmlentities(efile_preview($re->efiling_no, ENT_QUOTES)) . "</b><br>";
                    if (isset($re->efiling_for_name) && !empty($re->efiling_for_name) && ($re->efiling_for_name) != NULL) {
                        $efiling_no .= htmlentities($re->efiling_for_name, ENT_QUOTES);
                    }
                    $efiling_no .= '</a>';
                    $type = htmlentities($type, ENT_QUOTES);
                    if ($re->stage_id == LODGING_STAGE) {
                        $stages_name = 'Trashed (Admin)';
                    } elseif ($re->stage_id == DELETE_AND_LODGING_STAGE) {
                        $stages_name = 'Trashed and Deleted (Admin)';
                    } elseif ($re->stage_id == TRASH_STAGE) {
                        $stages_name = 'Trashed (Self)';
                    }
                    $action = htmlentities($stages_name, ENT_QUOTES);
                    $submitted_on = htmlentities(date("d/m/Y h:i:s A", strtotime($re->activated_on, ENT_QUOTES)));
                }
                if ($stages == IA_E_Filed) {
                    $efiling_no = '<a href="' . $redirect_url . '/' . url_encryption(trim($re->registration_id . '#' . $re->ref_m_efiled_type_id . '#' . IA_E_Filed)) . '"><b>"' . htmlentities(efile_preview($re->efiling_no, ENT_QUOTES)) . "</b><br>";
                    if (isset($re->efiling_for_name) && !empty($re->efiling_for_name) && ($re->efiling_for_name) != NULL) {
                        $efiling_no .= htmlentities($re->efiling_for_name, ENT_QUOTES);
                    }
                    $efiling_no .= '</a>';
                    $type = htmlentities($type, ENT_QUOTES);
                    $submitted_on = htmlentities(date("d/m/Y h:i:s A", strtotime(htmlentities($re->activated_on, ENT_QUOTES))));
                    $action = '&nbsp;';
                }
                if ($stages == MENTIONING_E_FILED) {
                    $efiling_no = '<a
                        href="' . $redirect_url . '/' . trim($re->registration_id) . "><b>" . htmlentities(efile_preview($re->efiling_no, ENT_QUOTES)) . "</b><br>";
                    if (isset($re->efiling_for_name) && !empty($re->efiling_for_name) && ($re->efiling_for_name) != NULL) {
                        $efiling_no .= htmlentities($re->efiling_for_name, ENT_QUOTES);
                    }
                    $efiling_no .= '</a>';
                    $type = htmlentities($type, ENT_QUOTES);
                    $submitted_on = htmlentities(date("d/m/Y h:i:s A", strtotime(htmlentities($re->activated_on, ENT_QUOTES))));
                    $action = '&nbsp;';
                }
                if ($stages == CITATION_E_FILED) {
                    $efiling_no = '<a
                                href="' . $redirect_url . '/' . trim($re->registration_id) . '"><b>' . htmlentities(efile_preview($re->efiling_no, ENT_QUOTES)) . "</b>" . "<br>";
                    if (isset($re->efiling_for_name) && !empty($re->efiling_for_name) && ($re->efiling_for_name) != NULL) {
                        $efiling_no .= htmlentities($re->efiling_for_name, ENT_QUOTES);
                    }
                    $efiling_no .= '</a>';
                    $type = htmlentities($type, ENT_QUOTES);
                    $submitted_on = htmlentities(date("d/m/Y h:i:s A", strtotime(htmlentities($re->activated_on, ENT_QUOTES))));
                    $action = '&nbsp;';
                }
                if ($stages == CERTIFICATE_E_FILED) {
                    if (!empty($api_certificate_efiling_no) && $api_certificate_efiling_no == $re->efiling_no && !empty($api_certificate_request_no) && $api_certificate_request_no != null) {
                        $efiling_no = '<a class="CheckRequestCertificatewwww"
                                    onClick="CheckRequestCertificate(\'' . $api_certificate_request_no . '\')"
                                    data-scino="' . $api_certificate_efiling_no . '"
                                    data-request_no="' . $api_certificate_request_no . '">
                                    <b>' . htmlentities(efile_preview($re->efiling_no, ENT_QUOTES)) . "</b>" . "<br>";
                        if (isset($re->efiling_for_name) && !empty($re->efiling_for_name) && ($re->efiling_for_name) != NULL) {
                            $efiling_no .= htmlentities($re->efiling_for_name, ENT_QUOTES);
                        }
                        $efiling_no .= '</a>';
                    } else {
                        $efiling_no = '<span class="text-black"
                                    style="color:black!important;">
                                    <b>' . htmlentities(efile_preview($re->efiling_no, ENT_QUOTES)) . "</b>" . "<br>";
                        if (isset($re->efiling_for_name) && !empty($re->efiling_for_name) && ($re->efiling_for_name) != NULL) {
                            $efiling_no .= htmlentities($re->efiling_for_name, ENT_QUOTES);
                        }
                        $efiling_no .= '</span>';
                    }
                    $type = htmlentities($type, ENT_QUOTES);
                    $submitted_on = htmlentities(date("d/m/Y h:i:s A", strtotime(htmlentities($re->activated_on, ENT_QUOTES))));
                    $action = '&nbsp;';
                }
                if (in_array($stages, $exclude_stages_array)) {
                    $efiling_no = '<a href="' . $redirect_url . '/' . url_encryption(trim($re->registration_id . '#' . $re->ref_m_efiled_type_id . '#' . I_B_Approval_Pending_Stage)) . '"><b>' . htmlentities(efile_preview($re->efiling_no, ENT_QUOTES)) . '</b><br>';
                    if (isset($re->efiling_for_name) && !empty($re->efiling_for_name) && ($re->efiling_for_name) != NULL) {
                        $efiling_no .= htmlentities($re->efiling_for_name, ENT_QUOTES) . $allocated;
                    }
                    $efiling_no .= '</a>';
                    $type = htmlentities($type, ENT_QUOTES);
                    $submitted_on = htmlentities(date("d/m/Y h:i:s A", strtotime($re->activated_on, ENT_QUOTES)));
                    $action = '&nbsp;';
                    
                }
                $allocated_to = '';
                $allocated_to .= (!empty($re->allocated_user_first_name)) ? htmlentities($re->allocated_user_first_name, ENT_QUOTES) : '';
                $allocated_to .= (!empty($re->allocated_user_last_name)) ? htmlentities($re->allocated_user_last_name, ENT_QUOTES) : '';
                $allocated_to .=  (!empty($re->allocated_to_user_id)) ? ' (' . htmlentities($re->allocated_to_user_id, ENT_QUOTES) . ')' : '';
                $allocated_to .= '<br>';
                $allocated_to .= (!empty($re->allocated_to_da_on)) ? htmlentities(date("d/m/Y h.i.s A", strtotime($re->allocated_to_da_on, ENT_QUOTES))) : '';
                if ($stages != Draft_Stage || $stages != TRASH_STAGE) {
                    // $efiling_no = '';
                    // $type = '';
                    // $submitted_on = '';
                    
                } else {
                    $efiling_no = '';
                    $type = '';
                    $submitted_on = '';
                    $allocated_to = '';
                    $action = '';
                }
                if(isset($efiling_no) && !empty($efiling_no) && isset($type) && !empty($type) && isset($submitted_on) && !empty($submitted_on) && isset($allocated_to) && !empty($allocated_to)){
                    $data[] = array(
                        "id" => $i++,
                        "user_stage_name" => $user_stage_name,
                        "efiling_no" => $efiling_no,
                        "type" => $type,
                        "case_details" => $case_details,
                        "submitted_on" => $submitted_on,
                        "action" => $action,
                        "allocated_to" => $allocated_to,
                        "data_keys" => [
                            "id" => 'Sr. No.',
                            "user_stage_name" => 'Stage',
                            "efiling_no" => 'eFiling No.',
                            "type" => 'Type',
                            "case_details" => 'Case Detail',
                            "submitted_on" => 'Submitted On',
                            "action" => '...',
                            "allocated_to" => 'Allocated To DA'
                        ]
                    );
                }
            }
        }
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data,
            "token" => csrf_hash() // New token hash
        );

        return $this->response->setJSON($response);
    }
}
