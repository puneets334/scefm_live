<?php

namespace App\Controllers\EfilingSearch;

use App\Controllers\BaseController;
use App\Models\UploadDocuments\UploadDocsModel;
use App\Models\Report\ReportModel;
use App\Libraries\webservices\Efiling_webservices;
use App\Models\NewCase\GetDetailsModel;
use App\Models\Affirmation\AffirmationModel;
use App\Models\MiscellaneousDocs\GetDetailsModel as MDGetDetailsModel;
use App\Models\MiscellaneousDocs\MiscellaneousDocsModel as MDMiscellaneousDocsModel;
use App\Models\MiscellaneousDocs\ViewModel as MDViewModel;
use App\Models\Caveat\ViewModel as CaveatViewModel;
use App\Models\Citation\CitationModel;
use App\Models\NewCase\ActSectionsModel as NewCaseActSectionsModel;
use App\Models\NewCase\DropdownListModel as NewCaseDropdownListModel;
use App\Models\NewCase\ViewModel as NewCaseViewModel;
use App\Models\IA\GetDetailsModel as IAGetDetailsModel;
use App\Models\IA\ViewModel as IAViewModel;
use App\Models\Common\CommonModel;
use App\Models\GetCISStatus\GetCISStatusModel;

class DefaultController extends BaseController
{

    protected $request;
    protected $UploadDocsModel;
    protected $ReportModel;
    protected $Efiling_webservices;
    protected $ViewModel;
    protected $GetDetailsModel;
    protected $MDGetDetailsModel;
    protected $MDViewModel;
    protected $AffirmationModel;
    protected $session;
    protected $MDMiscellaneousDocsModel;
    protected $CaveatViewModel;
    protected $NewCaseActSectionsModel;
    protected $NewCaseDropdownListModel;
    protected $NewCaseViewModel;
    protected $IAGetDetailsModel;
    protected $IAViewModel;
    protected $CommonModel;
    protected $Get_CIS_Status_model;
    protected $uri;
    protected $efiling_webservices;
    protected $Citation_model;

    public function __construct()
    {
        parent::__construct();
        $this->request = \Config\Services::request();
        $this->UploadDocsModel = new UploadDocsModel();
        $this->ReportModel = new ReportModel();
        $this->MDGetDetailsModel = new MDGetDetailsModel();
        $this->MDViewModel = new MDViewModel();
        $this->AffirmationModel = new AffirmationModel();
        $this->MDMiscellaneousDocsModel = new MDMiscellaneousDocsModel();
        $this->session = \Config\Services::session();
        $this->CaveatViewModel = new CaveatViewModel();
        $this->GetDetailsModel = new GetDetailsModel();
        $this->NewCaseActSectionsModel = new NewCaseActSectionsModel();
        $this->NewCaseDropdownListModel = new NewCaseDropdownListModel();
        $this->NewCaseViewModel = new NewCaseViewModel();
        $this->IAGetDetailsModel = new IAGetDetailsModel();
        $this->IAViewModel = new IAViewModel();
        $this->CommonModel = new CommonModel();
        $this->Get_CIS_Status_model = new GetCISStatusModel();
        $this->efiling_webservices = new Efiling_webservices();
        $this->Citation_model = new CitationModel();
        $this->uri = service('uri');
    }

    public function index()
    {
        extract($_REQUEST);
        if(isset($efiling_number)) {
            $efiling_number;
        } else {
            $efiling_number = '';
        }
        $efiling_no =$efiling_number;
        $data = $this->ReportModel->get_efilingByefiling_no($efiling_no);
        if (!empty($data) && $data != null && count($data) != 0 && $data[0]->efiling_no != null) {
            $report=$data[0];
            if($report->efiling_type !='' && $report->efiling_type=='new_case') {
                $rd='newcase.defaultController'; //. equal to / required
                $id='/'.$report->registration_id . '/'. $report->ref_m_efiled_type_id . '/' . $report->stage_id . '/' . $report->efiling_type;
            } else if($report->efiling_type !='' && $report->efiling_type=='misc_document') {
                $rd='miscellaneous_docs.DefaultController'; //. equal to / required
                $id='/'.$report->registration_id . '/' . $report->ref_m_efiled_type_id . '/' . $report->stage_id . '/' . $report->efiling_type;
            } else if($report->efiling_type !='' && $report->efiling_type=='IA') {
                $rd='IA.DefaultController'; //. equal to / required
                $id='/'.$report->registration_id . '/' . $report->ref_m_efiled_type_id . '/' . $report->stage_id . '/' . $report->efiling_type;
            } else if($report->efiling_type !='' && $report->efiling_type=='CAVEAT') {
                $rd='case.caveat.crud'; //. equal to / required
                $id='/'.$report->registration_id . '/' . $report->ref_m_efiled_type_id . '/' . $report->stage_id . '/' . $report->efiling_type;
            } else if($report->efiling_type !='' && $report->ref_m_efiled_type_id==OLD_CASES_REFILING) {
                $rd='oldCaseRefiling.DefaultController'; //. equal to / required
                $id='/'.$report->registration_id . '/' . $report->ref_m_efiled_type_id . '/' . $report->stage_id . '/' . $report->efiling_type;
            }
            $redirect_url = base_url('efiling_search/identify').$id;
            return redirect()->to($redirect_url);
        }
    }

    public function efiling_search()
    {
       return $this->render('responsive_variant.case.efiling_search.search');
    }

    function search()
    {
        $diary_no = trim($_GET['diary_no']);
        $efiling_no = trim($_GET['efiling_no']);
        $efiling_year = trim($_GET['efiling_year']);
        $ActionFiledOn = trim($_GET['ActionFiledOn']);
        $DateRange = trim($_GET['DateRange']);
        $filing_type_id = trim(url_decryption($_GET['filing_type_id']));
        $users_id = trim(url_decryption($_GET['users_id']));
        $stages = url_decryption($_GET['stage_id']);
        $data = $this->ReportModel->get_efiling_search_list($ActionFiledOn, $DateRange, array($stages), $filing_type_id, $users_id, $diary_no, $efiling_no,$efiling_year, $_SESSION['login']['admin_for_type_id'], $_SESSION['login']['admin_for_id']);
        if (!empty($data) && $data != null && count($data) != 0 && $data[0]->efiling_no != null) {
            $status = array('status' => 'true', 'msg' => 'Data is found',);
            $dataDBFinal11['customers'] = $data;
            $dataDBFinal11['status'] = $status;
            echo json_encode($dataDBFinal11);
        } else {
            $status = array('status' => 'false', 'msg' => 'Data is not found.',);
            $dataDBFinal11['customers'] = $data;
            $dataDBFinal11['status'] = $status;
            echo json_encode($dataDBFinal11);
        }
    }

    function identifyByicmis()
    {
        $uris = $this->uri->segment(1);
        $muri = str_replace('.', '/', $uris);
        $efilno = $this->uri->segment(3);
        // $efiling_type = $this->uri->segment(4);
        $getdata=$this->ReportModel->get_registration_id_efiling_type($efilno);
        $registration_id=$getdata->registration_id;
        $type=$getdata->ref_m_efiled_type_id;
        $stage=$getdata->stage_id;
        $efiling_type=$getdata->efiling_type;
        $ids = $registration_id . '#' . $type . '#' . $stage . '#' . $efiling_type;
        // registration_id # ref_m_usertype_id # stage_id #  efiling_type (efiling_type='CAVEAT' or 'IA' or 'misc_document' or 'new_case')
        $idss = url_encryption($ids);
        // echo '1@@@' . $idss; exit(0);
        // $this->slice->view('responsive_variant.case.efiling_search.view', @compact('idss'));
        if (!empty($registration_id) && !empty($type) && !empty($stage)) {
            return redirect()->to(base_url('efiling_search/get_view_data/'.$idss));
        } else {
            return redirect()->to(base_url('efiling_search'));
        }

    }

    public function identify()
    {
        $uris = service('uri');
        $sLast = $uris->getSegment(1);
        // $uris = $this->request->getUri(1);
        $muri = str_replace('.', '/', $sLast);    
        $registration_id = $uris->getSegment(4);
        $type = $uris->getSegment(5);
        $stage = ($uris->getTotalSegments() >= 6 && $uris->getTotalSegments()-1 == 6) ? $uris->getSegment(6) : NULL;
        $efiling_type = $uris->getTotalSegments() == 7 ? $uris->getSegment(7) : NULL;
        $this->CommonModel->get_efiling_num_basic_Details($registration_id);
        $ids = $registration_id . '#' . $type . '#' . $stage . '#' . $efiling_type;
        // registration_id # ref_m_usertype_id # stage_id #  efiling_type (efiling_type='CAVEAT' or 'IA' or 'misc_document' or 'new_case')
        $idss = url_encryption($ids);
        // echo '1@@@' . $idss; exit(0);
        // $this->slice->view('responsive_variant.case.efiling_search.view', @compact('idss'));
        if (!empty($registration_id) && !empty($type) && !empty($stage)) {
            return redirect()->to(base_url('efiling_search/DefaultController/get_view_data/'.$idss)); 
        } else {
            return redirect()->to(base_url('efiling_search'));
        }
    }

    function view($idss = null)
    {
        return  $this->render('responsive_variant.case.efiling_search.view', @compact('idss'));
    }

    function get_view_data($id = null)
    {
        if ($id) {
            $id = url_decryption($id);
            $InputArrray = explode('#', $id);
            $data['registration_id'] = $registration_id = $InputArrray[0];
            $data['ref_m_usertype_id'] = $type = $InputArrray[1];
            $data['stage_id'] = $stage = $InputArrray[2];
            $data['efiling_type'] = $efiling_type = trim($InputArrray[3]);
            $data['efiling_search_header']= 'efiling_search';
            $data_efiling_search['efiling_search_header']= 'efiling_search';
            if (!empty($registration_id) && !empty($efiling_type)) {
                $data['uploaded_docs'] = $this->UploadDocsModel->get_uploaded_pdfs($registration_id);
                if ($efiling_type == 'CAVEAT') {
                    $data['efiling_civil_data'] = $this->CaveatViewModel->get_efiling_civil_details($registration_id);
                    $data['extra_party_details'] = $this->CaveatViewModel->get_extra_party_preview_details($registration_id);
                    $subordinate_data = $this->CaveatViewModel->get_sub_qj_hc_court_details($registration_id);
                    if(isset($subordinate_data[0]['sub_qj_high']) && !empty($subordinate_data[0]['sub_qj_high']) && $subordinate_data[0]['sub_qj_high'] == 3) {
                        $case_type_id = !empty($subordinate_data[0]['case_type']) ? (int)$subordinate_data[0]['case_type'] : NULL;
                        $est_code = !empty($subordinate_data[0]['bench_code']) ? $subordinate_data[0]['bench_code'] : NULL;
                        $hc_id= !empty($subordinate_data[0]['court_id']) ? (int)$subordinate_data[0]['court_id'] : NULL;
                        $params = array();
                        if(isset($hc_id) && !empty($hc_id)) {
                            $params['type'] = 1;
                            $params['hc_id'] = $hc_id;
                            $highCourtDetails = $this->CaveatViewModel->getCourtDetails($params);
                            if(isset($highCourtDetails[0]['name']) && !empty($highCourtDetails[0]['name'])) {
                                $highCourtName = $highCourtDetails[0]['name'];
                                $subordinate_data['0']['highCourtName'] = $highCourtName;
                            }
                        }
                        if(isset($hc_id) && !empty($hc_id) && isset($est_code) && !empty($est_code)) {
                            $params['type'] = 2;
                            $params['hc_id'] = $hc_id;
                            $params['est_code'] = $est_code;
                            $benchDetails = $this->CaveatViewModel->getCourtDetails($params);
                            if(isset($benchDetails[0]['name']) && !empty($benchDetails[0]['name'])) {
                                $benchName = $benchDetails[0]['name'];
                                $subordinate_data['0']['benchName'] = $benchName;
                            }
                        }
                        if(isset($case_type_id) && !empty($case_type_id) && isset($est_code) && !empty($est_code)) {
                            $params['type'] = 3;
                            $params['id'] = $case_type_id;
                            $params['est_code'] = $est_code;
                            $caseDetails = $this->CaveatViewModel->getCourtDetails($params);
                            if(isset($caseDetails[0]['type_name']) && !empty($caseDetails[0]['type_name'])) {
                                $caseName = $caseDetails[0]['type_name'];
                                $subordinate_data['0']['caseName'] = $caseName;
                            }
                        }
                    }
                    $data['subordinate_court_data'] =$subordinate_data;
                    $data['subordinate_court_details'] = $this->GetDetailsModel->get_subordinate_court_details($registration_id);
                    $data['efiled_docs_list'] = $this->CaveatViewModel->get_index_items_list($registration_id);
                    $data['payment_details'] = $this->CaveatViewModel->get_payment_details($registration_id);
                    return $this->render('caveat.caveat_preview',$data);
                } else if ($efiling_type == 'IA') {
                    $data['case_details'] = $this->IAGetDetailsModel->get_case_details($registration_id);                        
                    $data['filing_for_details'] = $this->IAViewModel->get_filing_for_parties($registration_id);
                    $data['efiled_docs_list'] = $this->IAViewModel->get_index_items_list($registration_id);
                    $data['payment_details'] = $this->IAViewModel->get_payment_details($registration_id);
                    $data['esigned_docs_details'] = $this->AffirmationModel->get_esign_doc_details($registration_id);
                    return $this->render('IA.ia_preview',$data);
                } else if ($efiling_type == 'misc_document') {
                    $data['case_details'] = $this->MDGetDetailsModel->get_case_details($registration_id);
                    $data['filing_for_details'] = $this->MDViewModel->get_filing_for_parties($registration_id);
                    $data['efiled_docs_list'] = $this->MDViewModel->get_index_items_list($registration_id);
                    $data['payment_details'] = $this->MDViewModel->get_payment_details($registration_id);
                    $data['esigned_docs_details'] = $this->AffirmationModel->get_esign_doc_details($registration_id);
                    return $this->render('miscellaneous_docs.misc_docs_preview',$data);
                } else if ($efiling_type == 'OLD-CASES-REFILING') {
                    $data['case_details'] = $this->GetDetailsModel->get_case_details($registration_id);
                    $data['filing_for_details'] = $this->ViewModel->get_filing_for_parties($registration_id);
                    $data['efiled_docs_list'] = $this->ViewModel->get_index_items_list($registration_id);
                    $data['payment_details'] = $this->ViewModel->get_payment_details($registration_id);
                    $data['esigned_docs_details'] = $this->AffirmationModel->get_esign_doc_details($registration_id);
                    return $this->render('oldCaseRefiling.old_efiling_preview',$data);
                } else if ($efiling_type == 'new_case') {                     
                    $data['new_case_details'] = $this->GetDetailsModel->get_new_case_details($registration_id);
                    $data['sc_case_type'] = $this->NewCaseDropdownListModel->get_sci_case_type_name($data['new_case_details'][0]['sc_case_type_id']);
                    $data['main_subject_cat'] = $this->NewCaseDropdownListModel->get_main_subject_category($data['new_case_details'][0]['subject_cat']);
                    $data['petitioner_details'] = $this->GetDetailsModel->get_case_parties_details($registration_id, array('p_r_type' => 'P', 'm_a_type' => 'M', 'party_id' => NULL, 'view_lr_list' => FALSE));
                    $data['respondent_details'] = $this->GetDetailsModel->get_case_parties_details($registration_id, array('p_r_type' => 'R', 'm_a_type' => 'M', 'party_id' => NULL, 'view_lr_list' => FALSE));
                    $data['extra_parties_list'] = $this->GetDetailsModel->get_case_parties_details($registration_id, array('p_r_type' => NULL, 'm_a_type' => 'A', 'party_id' => NULL, 'view_lr_list' => FALSE));
                    $data['lr_parties_list'] = $this->GetDetailsModel->get_case_parties_details($registration_id, array('p_r_type' => NULL, 'm_a_type' => NULL, 'party_id' => NULL, 'view_lr_list' => TRUE));
                    $data['act_sections_list'] = $this->NewCaseActSectionsModel->get_act_sections_list($registration_id);
                    $data['party_details'] = $this->GetDetailsModel->get_case_parties_details($registration_id, array('p_r_type' => 'P', 'm_a_type' => 'M', 'party_id' => NULL, 'view_lr_list' => FALSE));
                    $data['subordinate_court_details'] = $this->GetDetailsModel->get_subordinate_court_details($registration_id);
                    $data['efiled_docs_list'] = $this->NewCaseViewModel->get_index_items_list($registration_id);
                    $data['payment_details'] = $this->NewCaseViewModel->get_payment_details($registration_id);
                    $data['esigned_docs_details'] = $this->AffirmationModel->get_esign_doc_details($registration_id);
                    return $this->render('newcase.efile_details_view',$data);
                } else {
                    return redirect()->to(base_url('efiling_search'));
                }
            }
        } else {
            return redirect()->to(base_url('efiling_search'));
        }
    }

    // START CRON JOB FOR SCRUTINY STATUS
    public function cronJobForScrutinyStatus()
    {
        $totalCountData = $this->Get_CIS_Status_model->countPendingScrutiny(I_B_Approval_Pending_Admin_Stage,1,USER_ADMIN,USER_ADVOCATE);
        $totalData = $this->Get_CIS_Status_model->countPendingScrutiny(I_B_Approval_Pending_Admin_Stage,'',USER_ADMIN,USER_ADVOCATE);
        $totalRows = !empty($totalCountData[0]->total) ? $totalCountData[0]->total : NULL;
        $partionSize = 0; //partition size
        $totalPartion = 0; //total partition
        if(isset($totalRows) && !empty($totalRows)) {
            if($totalRows >= 10000) {
                $partionSize = 500;
            } else if($totalRows >= 8000) {
                $partionSize = 400;
            } else if($totalRows >= 6000) {
                $partionSize = 300;
            } else if($totalRows >= 4000) {
                $partionSize = 200;
            } else if($totalRows >= 2000) {
                $partionSize = 200;
            } else if($totalRows >= 1000) {
                $partionSize = 200;
            } else if($totalRows >= 800) {
                $partionSize = 200;
            } else if($totalRows >= 600) {
                $partionSize = 200;
            } else if($totalRows >= 400) {
                $partionSize = 200;
            } else if($totalRows >= 200) {
                $partionSize = 200;
            } else if($totalRows >= 100) {
                $partionSize = 100;
            } else if($totalRows >= 50) {
                $partionSize = 50;
            } else {
                $partionSize = $totalRows;
            }
        }
        if(isset($totalRows) && !empty($totalRows)) {
            $totalPartion = ceil($totalRows/$partionSize);
        }
        $efilingArrChunks = !empty($totalData[0]) ? array_chunk(array_column($totalData,'efiling_no'),$partionSize,false) : NULL;
        $efilingArrChunksData = !empty($totalData[0]) ? array_chunk($totalData,$partionSize,false) : NULL;
        $table_name = 'efil.tbl_cron_job_history';
        if(isset($totalPartion) && !empty($totalPartion)) {
            for ($i = 0; $i < $totalPartion; $i++) {
                $efilingStr = !empty($efilingArrChunks[$i]) ? implode(',',$efilingArrChunks[$i]) : NULL;
                $result = $this->efiling_webservices->get_new_case_efiling_nums_status_from_SCIS($efilingStr);
                $efiligNoFileTypeArr = array();
                if(isset($efilingArrChunksData[$i]) && !empty($efilingArrChunksData[$i])) {
                    foreach ($efilingArrChunksData[$i] as $k=>$v) {
                        $efiligNoFileTypeArr[$v->efiling_no] = $v->efiling_for_type_id.'#'.$v->registration_id.'#'.$v->stage_id;
                    }
                }
                if(isset($result->consumed_data) && !empty($result->consumed_data)) {
                    foreach ($result->consumed_data as $k=>$v) {
                        $fileType = NULL;
                        $registration_id = NULL;
                        $stageId = NULL;
                        if(array_key_exists($v->efiling_no,$efiligNoFileTypeArr)) {
                            if(isset($efiligNoFileTypeArr[$v->efiling_no]) && !empty($efiligNoFileTypeArr[$v->efiling_no])) {
                                $efilDataArr = explode('#',$efiligNoFileTypeArr[$v->efiling_no]);
                                $fileType = !empty($efilDataArr[0]) ? (int)trim($efilDataArr[0]) : NULL;
                                $registration_id = !empty($efilDataArr[1]) ? $efilDataArr[1] : NULL;
                                $stageId = !empty($efilDataArr[2]) ? $efilDataArr[2] : NULL;
                            }
                        }
                        $efiling_no = !empty($v->efiling_no) ? $v->efiling_no : NULL;
                        $cronHisArr = array();
                        $cronHisArr['registration_id'] = $registration_id;
                        $cronHisArr['created_ip'] = getClientIP();
                        $cronHisArr['file_type'] = $fileType;
                        $cronHisArr['created_at'] = date('Y-m-d H:i:s');
                        $cronHisArr['status'] = true;
                        $this->Citation_model->insertData($table_name,$cronHisArr);
                        if(isset($fileType) && !empty($fileType)) {
                            switch ($fileType) {
                                case E_FILING_TYPE_NEW_CASE :
                                    $this->get_cis_status_for_newcase($v,$registration_id, $efiling_no, $stageId );
                                    break;
                                case E_FILING_TYPE_MISC_DOCS:
                                    $this->get_cis_status_for_miscdoc($v,$registration_id, $efiling_no, $stageId );
                                    break;
                                case E_FILING_TYPE_IA:
                                    $this->get_cis_status_foria($v,$registration_id, $efiling_no, $stageId );
                                    break;
                                case E_FILING_TYPE_CAVEAT:
                                    $this->get_cis_status_forcaveat($v,$registration_id, $efiling_no, $stageId );
                                    break;
                                case E_FILING_TYPE_DEFICIT_COURT_FEE:
                                    $this->get_cis_status_deficit_court_fee($v,$registration_id, $efiling_no, $stageId );
                                    break;
                                default:
                            }
                        }
                    }
                }
            }
        }
    }

    private function get_cis_status_for_newcase($data,$registration_id, $efiling_num, $curr_stage)
    {
        $curr_dt = date('Y-m-d H:i:s');
        $response = $data;
        if ($response->status == 'A') {
            $objections_status = NULL;
            $documents_status = NULL;
            $next_stage = 0;
            $d_no = explode('/', $response->diary_no);
            $diary_no = $d_no[0];
            $diary_year = $d_no[1];
            $diary_date = $response->diary_generated_on;
            $case_details[0] = array(
                'registration_id' => $registration_id,
                'sc_diary_num' => $diary_no,
                'sc_diary_year' => $diary_year,
                'sc_diary_date' => $diary_date,
                'updated_by' => $_SESSION['login']['id'],
                'updated_on' => $curr_dt,
                'updated_by_ip' => getClientIP(),
            );
            if ($response->verification_date != '') {
                $reg_no_display = $response->reg_no_display;
                $reg_date = $response->registration_date;
                if ($reg_date == '0000-00-00 00:00:00') {
                    $reg_date = null;
                }
                $case_details1[0] = array(
                    'sc_display_num' => $reg_no_display,
                    'sc_reg_date' => $reg_date,
                    'verification_date' => $response->verification_date
                );
                $case_details[0] = array_merge($case_details1[0], $case_details[0]);
                if ($response->objection_flag == 'Y') {
                    $cis_objections = $response->objections;
                    $objections_status = $this->get_icmis_objections_status($registration_id, $cis_objections, $curr_dt);
                }
                $cis_documents = $response->doc_details;
                $documents_status = $this->get_icmis_documents_status($registration_id, $cis_documents, TRUE, FALSE, $curr_dt);
                if ($documents_status[0]) {
                    $next_stage = I_B_Approval_Pending_Admin_Stage;
                } else {
                    $next_stage = E_Filed_Stage;
                }
            } else {
                $stage_update_timestamp = $this->Get_CIS_Status_model->get_stage_update_timestamp($registration_id, $curr_stage);
                if ($response->objection_flag == 'Y') {
                    if($stage_update_timestamp[0]['activated_on'] >= $response->last_defect_notified_date){
                        //echo "eFiling No. " . $efiling_num . " action is still pending for scrutiny.";
                        //  exit(0);
                    }
                    $cis_objections = $response->objections;
                    $objections_status = $this->get_icmis_objections_status($registration_id, $cis_objections, $curr_dt);
                    if ($objections_status[0] == TRUE && ($objections_status[1] || $objections_status[2])) {
                        $next_stage = I_B_Defected_Stage;
                    }
                } else {
                    $next_stage = I_B_Approval_Pending_Admin_Stage;
                }
                $cis_documents = $response->doc_details;
                $documents_status = $this->get_icmis_documents_status($registration_id, $cis_documents, TRUE, FALSE, $curr_dt);
            }
            $update_status = $this->Get_CIS_Status_model->update_icmis_case_status($registration_id, $next_stage, $curr_dt, $case_details, $objections_status[1], $objections_status[2], $documents_status[1]);
            // if ($update_status) {
            //     if ($next_stage) {
            //         if ($next_stage == I_B_Defected_Stage) {
            //             echo "eFiling No. " . $efiling_num . " updated to Defective Stage.";
            //             exit(0);
            //         } elseif ($next_stage == I_B_Approval_Pending_Admin_Stage) {
            //             echo "eFiling No. " . $efiling_num . " updated to Pending Scrutiny Stage.";
            //             exit(0);
            //         } elseif ($next_stage == E_Filed_Stage) {
            //             echo "eFiling No. " . $efiling_num . " updated to Efiled Stage.";
            //             exit(0);
            //         }
            //     }
            // }
        }
    }

    private function get_cis_status_for_miscdoc($data,$registration_id, $efiling_num , $curr_stage)
    {
        $objections_status = array();
        $response = $data;
        $curr_dt = date('Y-m-d H:i:s');
        if ($response->status == 'A') {
            $cis_documents = $response->doc_details;
            $documents_status = $this->get_icmis_documents_status($registration_id, $cis_documents, FALSE, FALSE, $curr_dt);
            if ($documents_status[0]) {
                $next_stage = I_B_Approval_Pending_Admin_Stage;
            } else {
                $next_stage = Document_E_Filed;
            }
            $update_status = $this->Get_CIS_Status_model->update_misc_doc_ia_status($registration_id, $next_stage, $curr_dt, $objections_status[1], $objections_status[2], $documents_status[1]);
            // if ($update_status) {
            //     if ($next_stage) {
            //         if ($next_stage == I_B_Defected_Stage) {
            //             echo "eFiling No. " . $efiling_num . " updated to Defective Stage.";
            //             exit(0);
            //         } elseif ($next_stage == I_B_Approval_Pending_Admin_Stage) {
            //             echo "eFiling No. " . $efiling_num . " updated to Pending Scrutiny Stage.";
            //             exit(0);
            //         } elseif ($next_stage == Document_E_Filed) {
            //             echo "eFiling No. " . $efiling_num . " updated to Efiled Stage.";
            //             exit(0);
            //         }
            //     }
            // }
        }
    }

    private function get_cis_status_foria($data,$registration_id, $efiling_num, $curr_stage)
    {
        $objections_status = array();
        $response = $data;
        $curr_dt = date('Y-m-d H:i:s');
        if ($response->status == 'A') {
            $cis_documents = $response->doc_details;
            $documents_status = $this->get_icmis_documents_status($registration_id, $cis_documents, TRUE, FALSE, $curr_dt);
            if ($documents_status[0]) {
                $next_stage = I_B_Approval_Pending_Admin_Stage;
            } else {
                $next_stage = IA_E_Filed;
            }
            $update_status = $this->Get_CIS_Status_model->update_misc_doc_ia_status($registration_id, $next_stage, $curr_dt, $objections_status[1], $objections_status[2], $documents_status[1]);
            // if ($update_status) {
            //     if ($next_stage) {
            //         if ($next_stage == I_B_Defected_Stage) {
            //             echo "eFiling No. " . $efiling_num . " updated to Defective Stage.";
            //             exit(0);
            //         } elseif ($next_stage == I_B_Approval_Pending_Admin_Stage) {
            //             echo "eFiling No. " . $efiling_num . " updated to Pending Scrutiny Stage.";
            //             exit(0);
            //         } elseif ($next_stage == IA_E_Filed) {
            //             echo "eFiling No. " . $efiling_num . " updated to Efiled Stage.";
            //             exit(0);
            //         }
            //     }
            // }
        }
    }

    private function get_cis_status_forcaveat($data,$registration_id, $efiling_num, $curr_stage)
    {
        $curr_dt = date('Y-m-d H:i:s');
        $response = $data;
        if ($response->status == 'A') {
            $objections_status = NULL;
            $documents_status = NULL;
            $next_stage = 0;
            $d_no = explode('/', $response->diary_no);
            $diary_no = $d_no[0];
            $diary_year = $d_no[1];
            $diary_date = $response->diary_generated_on;
            $case_details[0] = array(
                'registration_id' => $registration_id,
                'sc_diary_num' => $diary_no,
                'sc_diary_year' => $diary_year,
                'sc_diary_date' => $diary_date,
                'updated_by' => $_SESSION['login']['id'],
                'updated_on' => $curr_dt,
                'updated_by_ip' => getClientIP(),
            );
            if ($response->verification_date != '') {
                $reg_no_display = $response->reg_no_display;
                $reg_date = $response->registration_date;
                if ($reg_date == '0000-00-00 00:00:00') {
                    $reg_date = null;
                }
                $case_details1[0] = array(
                    'sc_display_num' => $reg_no_display,
                    'sc_reg_date' => $reg_date,
                    'verification_date' => $response->verification_date
                );
                $case_details[0] = array_merge($case_details1[0], $case_details[0]);
                if ($response->objection_flag == 'Y') {
                    $cis_objections = $response->objections;
                    $objections_status = $this->get_icmis_objections_status($registration_id, $cis_objections, $curr_dt);
                }
                $cis_documents = $response->doc_details;
                $documents_status = $this->get_icmis_documents_status($registration_id, $cis_documents, TRUE, FALSE, $curr_dt);
                if ($documents_status[0]) {
                    $next_stage = I_B_Approval_Pending_Admin_Stage;
                } else {
                    $next_stage = E_Filed_Stage;
                }
            } else {
                $stage_update_timestamp = $this->Get_CIS_Status_model->get_stage_update_timestamp($registration_id, $curr_stage);
                if ($response->objection_flag == 'Y') {
                    if($stage_update_timestamp[0]['activated_on'] >= $response->last_defect_notified_date){
                        //echo "eFiling No. " . $efiling_num . " action is still pending for scrutiny.";
                        //  exit(0);
                    }
                    $cis_objections = $response->objections;
                    $objections_status = $this->get_icmis_objections_status($registration_id, $cis_objections, $curr_dt);
                    if ($objections_status[0] == TRUE && ($objections_status[1] || $objections_status[2])) {
                        $next_stage = I_B_Defected_Stage;
                    }
                } else {
                    $next_stage = I_B_Approval_Pending_Admin_Stage;
                }
                $cis_documents = $response->doc_details;
                $documents_status = $this->get_icmis_documents_status($registration_id, $cis_documents, TRUE, FALSE, $curr_dt);
            }
            $update_status = $this->Get_CIS_Status_model->update_icmis_case_status($registration_id, $next_stage, $curr_dt, $case_details, $objections_status[1], $objections_status[2], $documents_status[1]);
            // if ($update_status) {
            //     if ($next_stage) {
            //         if ($next_stage == I_B_Defected_Stage) {
            //             echo "eFiling No. " . $efiling_num . " updated to Defective Stage.";
            //             exit(0);
            //         } elseif ($next_stage == I_B_Approval_Pending_Admin_Stage) {
            //             echo "eFiling No. " . $efiling_num . " updated to Pending Scrutiny Stage.";
            //             exit(0);
            //         } elseif ($next_stage == E_Filed_Stage) {
            //             echo "eFiling No. " . $efiling_num . " updated to Efiled Stage.";
            //             exit(0);
            //         }
            //     }
            // }
        }
    }

    private function get_cis_status_deficit_court_fee($data,$registration_id, $efiling_num, $curr_stage)
    {
        $curr_dt = date('Y-m-d H:i:s');
        $response = $data;
        if ($response->status == 'A') {
            $objections_status = NULL;
            $documents_status = NULL;
            $next_stage = 0;
            $d_no = explode('/', $response->diary_no);
            $diary_no = $d_no[0];
            $diary_year = $d_no[1];
            $diary_date = $response->diary_generated_on;
            $case_details[0] = array(
                'registration_id' => $registration_id,
                'sc_diary_num' => $diary_no,
                'sc_diary_year' => $diary_year,
                'sc_diary_date' => $diary_date,
                'updated_by' => $_SESSION['login']['id'],
                'updated_on' => $curr_dt,
                'updated_by_ip' => getClientIP(),
            );
            if ($response->verification_date != '') {
                $reg_no_display = $response->reg_no_display;
                $reg_date = $response->registration_date;
                if ($reg_date == '0000-00-00 00:00:00') {
                    $reg_date = null;
                }
                $case_details1[0] = array(
                    'sc_display_num' => $reg_no_display,
                    'sc_reg_date' => $reg_date,
                    'verification_date' => $response->verification_date
                );
                $case_details[0] = array_merge($case_details1[0], $case_details[0]);
                if ($response->objection_flag == 'Y') {
                    $cis_objections = $response->objections;
                    $objections_status = $this->get_icmis_objections_status($registration_id, $cis_objections, $curr_dt);
                }
                $cis_documents = $response->doc_details;
                $documents_status = $this->get_icmis_documents_status($registration_id, $cis_documents, TRUE, FALSE, $curr_dt);
                if ($documents_status[0]) {
                    $next_stage = I_B_Approval_Pending_Admin_Stage;
                } else {
                    $next_stage = E_Filed_Stage;
                }
            } else {
                $stage_update_timestamp = $this->Get_CIS_Status_model->get_stage_update_timestamp($registration_id, $curr_stage);
                if ($response->objection_flag == 'Y') {
                    if($stage_update_timestamp[0]['activated_on'] >= $response->last_defect_notified_date){
                        //echo "eFiling No. " . $efiling_num . " action is still pending for scrutiny.";
                        //  exit(0);
                    }
                    $cis_objections = $response->objections;
                    $objections_status = $this->get_icmis_objections_status($registration_id, $cis_objections, $curr_dt);
                    if ($objections_status[0] == TRUE && ($objections_status[1] || $objections_status[2])) {
                        $next_stage = I_B_Defected_Stage;
                    }
                } else {
                    $next_stage = I_B_Approval_Pending_Admin_Stage;
                }
                $cis_documents = $response->doc_details;
                $documents_status = $this->get_icmis_documents_status($registration_id, $cis_documents, TRUE, FALSE, $curr_dt);
            }
            $update_status = $this->Get_CIS_Status_model->update_icmis_case_status($registration_id, $next_stage, $curr_dt, $case_details, $objections_status[1], $objections_status[2], $documents_status[1]);
            // if ($update_status) {
            //     if ($next_stage) {
            //         if ($next_stage == I_B_Defected_Stage) {
            //             echo "eFiling No. " . $efiling_num . " updated to Defective Stage.";
            //             exit(0);
            //         } elseif ($next_stage == I_B_Approval_Pending_Admin_Stage) {
            //             echo "eFiling No. " . $efiling_num . " updated to Pending Scrutiny Stage.";
            //             exit(0);
            //         } elseif ($next_stage == E_Filed_Stage) {
            //             echo "eFiling No. " . $efiling_num . " updated to Efiled Stage.";
            //             exit(0);
            //         }
            //     }
            // }
        }
    }

    private function get_icmis_objections_status($registration_id, $cis_objections, $curr_dt)
    {
        $old_objections = $this->Get_CIS_Status_model->get_icmis_objections_list($registration_id);
        $insert_objections = array();
        $update_objections = array();
        $objections_pending = FALSE;
        foreach ($cis_objections as $cis_objection) {
            $key = array_keys(array_column($old_objections, 'obj_id'), $cis_objection->org_id);
            $obj_removed_date = ($cis_objection->rm_dt == '0000-00-00 00:00:00') ? NULL : $cis_objection->rm_dt;
            if ($cis_objection->rm_dt == '0000-00-00 00:00:00' && $objections_pending == FALSE) {
                $objections_pending = TRUE;
            }
            if ($key) {
                $efil_obj_id = $old_objections[$key[0]]['id'];
                $update_objections[] = array(
                    'id' => $efil_obj_id,
                    'obj_id' => $cis_objection->org_id,
                    'remarks' => $cis_objection->remark,
                    'obj_prepare_date' => $cis_objection->save_dt,
                    'obj_removed_date' => $obj_removed_date,
                    'updated_by' => $_SESSION['login']['id'],
                    'updated_on' => $curr_dt,
                    'update_ip' => getClientIP(),
                    'pspdfkit_document_id' => $cis_objection->pspdfkit_document_id,
                    'to_be_modified_pspdfkit_document_pages_raw' => $cis_objection->to_be_modified_pspdfkit_document_pages_raw,
                    'to_be_modified_pspdfkit_document_pages_parsed' => $cis_objection->to_be_modified_pspdfkit_document_pages_parsed,
                );
            } else {
                if ($obj_removed_date == NULL) {
                    $insert_objections[] = array(
                        'registration_id' => $registration_id,
                        'obj_id' => $cis_objection->org_id,
                        'remarks' => $cis_objection->remark,
                        'obj_prepare_date' => $cis_objection->save_dt,
                        'obj_removed_date' => $obj_removed_date,
                        'created_by' => $_SESSION['login']['id'],
                        'created_on' => $curr_dt,
                        'create_ip' => getClientIP(),
                        'pspdfkit_document_id' => $cis_objection->pspdfkit_document_id,
                        'to_be_modified_pspdfkit_document_pages_raw' => $cis_objection->to_be_modified_pspdfkit_document_pages_raw,
                        'to_be_modified_pspdfkit_document_pages_parsed' => $cis_objection->to_be_modified_pspdfkit_document_pages_parsed,
                    );
                }
            }
        }
        return array($objections_pending, $insert_objections, $update_objections);
    }

    private function get_icmis_documents_status($registration_id, $cis_documents, $ia_only, $check_verify, $curr_dt)
    {
        $efil_docs = $temp_efil_docs = $this->Get_CIS_Status_model->get_efiled_docs_list($registration_id, $ia_only);
        $i = 0;
        foreach ($temp_efil_docs as $e_doc) {
            $temp_efil_docs[$i]['doc_id'] = encrypt_doc_id($e_doc['doc_id']);
            $i++;
        }
        $update_documents = array();
        $documents_pending = FALSE;
        foreach ($cis_documents as $doc) {
            $key = array_keys(array_column($temp_efil_docs, 'doc_id'), $doc->doc_id);
            $verified_on_date = ($doc->verified_on == '0000-00-00 00:00:00') ? NULL : $doc->verified_on;
            $disposal_date = ($doc->dispose_date == '0000-00-00') ? NULL : $doc->dispose_date;
            if ($check_verify) {
                if ($doc->verified_on == '0000-00-00 00:00:00' && $documents_pending == FALSE) {
                    $documents_pending = TRUE;
                }
            } else {
                if ($doc->docnum == NULL) {
                    $documents_pending = TRUE;
                }
            }
            if ($key) {
                $efil_doc_id = $efil_docs[$key[0]]['doc_id'];
                $update_documents[] = array(
                    'doc_id' => $efil_doc_id,
                    'icmis_diary_no' => $doc->diary_no,
                    'icmis_doccode' => $doc->doccode,
                    'icmis_doccode1' => $doc->doccode1,
                    'icmis_docnum' => $doc->docnum,
                    'icmis_docyear' => $doc->docyear,
                    'icmis_other1' => $doc->other1,
                    'icmis_iastat' => $doc->iastat,
                    'icmis_verified' => $doc->verified,
                    'icmis_verified_on' => $verified_on_date,
                    'icmis_dispose_date' => $disposal_date,
                    'updated_by' => $_SESSION['login']['id'],
                    'updated_on' => $curr_dt,
                    'update_ip_address' => getClientIP(),
                );
            }
        }
        return array($documents_pending, $update_documents);
    }

}