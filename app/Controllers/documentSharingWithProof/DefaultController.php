<?php
namespace App\Controllers;
class DefaultController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('slice');
        $this->load->model('common/Common_model');
        $this->load->library('form_validation');
        $this->load->model('documentIndex/DocumentIndex_DropDown_model');
        $this->load->model('documentSharingWithProof/DocumentShare');
    }
    public function index($type=null){
        $allowedUsers = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);
        $data = array();
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowedUsers)) {
            redirect('login');
            exit(0);
        }
        $this->load->model('newcase/Dropdown_list_model');
        $arr = array();
        $diaryArr = array();
        $arr['user_id'] = !empty($_SESSION['login']['id']) ? $_SESSION['login']['id'] : null;
        $docsharesDiaries = $this->DocumentShare->docShareDetailsByDiaryNo($arr);
        if(isset($docsharesDiaries) && !empty($docsharesDiaries)){
            $groupWiseArrData = array();
            $groupDataWithShareDoc = array();
            foreach ($docsharesDiaries as $k=>$v){
                $diaryArr[$v['diary_no']] = explode(',',$v['shared_uniq_id']);
                $share_uniq_id = !empty($v['shared_uniq_id']) ?  $v['shared_uniq_id'] : NULL;
                $diary_no = !empty($v['diary_no']) ?  $v['diary_no'] : NULL;
                if(isset($share_uniq_id) && !empty($share_uniq_id)){
                    $share_uniq_id_arr = explode(',',$share_uniq_id);
                    if(isset($share_uniq_id_arr) && !empty($share_uniq_id_arr) && count($share_uniq_id_arr)>0){
                        foreach ($share_uniq_id_arr as $key=>$value){
                            $share_uniq_id_each = $share_uniq_id_arr[$key];
                            $tmp = array();
                            $tmp['shared_uniq_id'] = $share_uniq_id_each;
                            $groupData = $this->DocumentShare->geShareDetailsGroupWose($tmp);
                            $share_doc_id = !empty($groupData[0]->share_doc_id) ? explode(',',$groupData[0]->share_doc_id) : NULL;
                            $a = array();
                            $a['docIdArr'] = $share_doc_id;
                            $docData = $this->DocumentShare->getEfiledDocByDocId($a);
                            $shareDocDetails = array();
                            if(isset($docData) && !empty($docData)){
                                foreach ($docData as $key1=>$value1){
                                    $doc_id = !empty($value1['doc_type_id']) ? (int)$value1['doc_type_id'] : NULL;
                                    $sub_doc_id = !empty($value1['sub_doc_type_id']) ? (int)$value1['sub_doc_type_id'] : NULL;
                                    $doc_title = !empty($value1['doc_title']) ? $value1['doc_title'] : NULL;
                                    $doc= array();
                                    $shareMainDocDetails='';
                                    $shareSubDocDetails ='';
                                    if(isset($doc_id) && !empty($doc_id) && isset($sub_doc_id) && !empty($sub_doc_id)){
                                        $doc['doc_id'] = $doc_id;
                                        $doc['sub_doc_id'] = $sub_doc_id;
                                        $shareSubDocDetails = $this->DocumentShare->getShareDocumentDetails($doc);
                                    }
                                    if(isset($doc_id) && !empty($doc_id)){
                                        $doc= array();
                                        $doc['doc_id'] = $doc_id;
                                        $shareMainDocDetails = $this->DocumentShare->getShareDocumentDetails($doc);
                                    }
                                    $document = !empty($shareMainDocDetails[0]['docdesc']) ? $shareMainDocDetails[0]['docdesc'] : 'N/A';
                                    $sub_document = !empty($shareSubDocDetails[0]['docdesc']) ? $shareSubDocDetails[0]['docdesc'] : 'N/A';
                                    $darr = array();
                                    $darr['doc_title'] = $doc_title;
                                    $darr['document'] = $document;
                                    $darr['sub_document'] = $sub_document;
                                    $shareDocDetails[] = $darr;

                                }
                            }
                            if(isset($groupData) && !empty($groupData)){
                                foreach ($groupData as $k1=>$v1){
                                    $v1->shareDocDetails= $shareDocDetails;
                                }
                                $groupWiseArrData[$diary_no][$share_uniq_id_each] = $groupData;
                            }
                        }
                    }
                }
            }
        }
        $data['diaryArr'] = $diaryArr;
        $data['groupWiseArrData'] = $groupWiseArrData;
        $data['sc_case_type'] = $this->Dropdown_list_model->getSciCaseTypeOrderById();
        $data['search_type'] = !empty($type['search_type']) ? $type['search_type'] : '';
        $shareDocDetails = array();
        if(!empty($this->session->userdata('searchDiarynoYear'))){
            $searchDiarynoYear = $this->session->userdata('searchDiarynoYear');
            $docDetails = $this->DocumentShare->getEfiledDoc($searchDiarynoYear);
            if(isset($docDetails) && !empty($docDetails)){
                foreach ($docDetails as $k=>&$value1){
                    $doc_id = !empty($value1['doc_type_id']) ? (int)$value1['doc_type_id'] : NULL;
                    $sub_doc_id = !empty($value1['sub_doc_type_id']) ? (int)$value1['sub_doc_type_id'] : NULL;
                    $doc_title = !empty($value1['doc_title']) ? $value1['doc_title'] : NULL;
                    $doc= array();
                    $shareMainDocDetails='';
                    $shareSubDocDetails ='';
                    if(isset($doc_id) && !empty($doc_id) && isset($sub_doc_id) && !empty($sub_doc_id)){
                        $doc['doc_id'] = $doc_id;
                        $doc['sub_doc_id'] = $sub_doc_id;
                        $shareSubDocDetails = $this->DocumentShare->getShareDocumentDetails($doc);
                    }
                    if(isset($doc_id) && !empty($doc_id)){
                        $doc= array();
                        $doc['doc_id'] = $doc_id;
                        $shareMainDocDetails = $this->DocumentShare->getShareDocumentDetails($doc);
                    }
                    $document = !empty($shareMainDocDetails[0]['docdesc']) ? $shareMainDocDetails[0]['docdesc'] : 'N/A';
                    $sub_document = !empty($shareSubDocDetails[0]['docdesc']) ? $shareSubDocDetails[0]['docdesc'] : 'N/A';
                    $darr = array();
                    $darr['doc_title'] = $doc_title;
                    $darr['document'] = $document;
                    $darr['sub_document'] = $sub_document;
                    $value1['shareDocDetails'] = $darr;
                }
            }
            $data['docDetails'] = !empty($docDetails) ? $docDetails : NULL;
            $data['doc_type'] = $this->DocumentIndex_DropDown_model->get_document_type(NULL);
            $usrArr = array();
            $usrArr['shareUserType'] = USER_ADVOCATE;
            $sharingUsers = $this->Common_model->getAorSrAdvocateArguinCounsel($usrArr);
            $data['sharingUsers'] = !empty($sharingUsers) ? $sharingUsers : NULL;
        }
        else{
            $data['docDetails'] = !empty($type['docDetails']) ? $type['docDetails'] : '';
        }
       // echo '<pre>'; print_r($data['docDetails']); exit;
        $this->load->view('templates/header');
        $this->load->view('documentSharingWithProof/ia_miscdoc_case_search_view',$data);
        $this->load->view('templates/footer');
    }
    public function searchCaseByDiaryRegistration()
    {
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            redirect('login');
            exit(0);
        }
            $data = array();
            $diary_type = !empty($_POST['search_filing_type']) ? $_POST['search_filing_type'] : NULL;
            $this->load->library('webservices/efiling_webservices');
            $this->load->model('mentioning/Mentioning_model');
            $this->Mentioning_model->get_establishment_details();
            $data['search_type'] = $diary_type;
            if(isset($diary_type) && !empty($diary_type) && $diary_type == 'diary'){
                $this->form_validation->set_rules('diaryno', 'Diary Number', 'required|trim|numeric');
                $this->form_validation->set_rules('diary_year', 'Diary Year', 'required|trim|numeric');
            }
            if(isset($diary_type) && !empty($diary_type) && $diary_type == 'register'){
                $this->form_validation->set_rules('sc_case_type', 'Case Type', 'required|trim|is_required|encrypt_check');
                $this->form_validation->set_rules('case_number', 'Case Number', 'required|trim|numeric');
                $this->form_validation->set_rules('case_year', 'Case Year', 'required|trim|numeric');
            }
            if($this->form_validation->run() == false){
                $this->index($data);
            }
            else{
                if ($diary_type == 'diary'){
                    unset($_SESSION['searchScCaseType']);
                    unset($_SESSION['searchCaseNumber']);
                    unset($_SESSION['searchCaseYear']);
                    unset($_SESSION['searchDiarynoYear']);
                    $this->session->set_userdata('searchDiaryNo',$_POST['diaryno']);
                    $this->session->set_userdata('searchDiaryYear',$_POST['diary_year']);
                    $web_service_result = $this->efiling_webservices->get_case_diary_details_from_SCIS(escape_data($_POST['diaryno']), escape_data($_POST['diary_year']));
                } else if ($diary_type == 'register'){
                    unset($_SESSION['searchDiaryNo']);
                    unset($_SESSION['searchDiaryYear']);
                    unset($_SESSION['searchDiarynoYear']);
                    $this->session->set_userdata('searchScCaseType',$_POST['sc_case_type']);
                    $this->session->set_userdata('searchCaseNumber',$_POST['case_number']);
                    $this->session->set_userdata('searchCaseYear',$_POST['case_year']);
                    $web_service_result = $this->efiling_webservices->get_case_details_from_SCIS(url_decryption(escape_data($_POST['sc_case_type'])), escape_data($_POST['case_number']), escape_data($_POST['case_year']));
                }
              //  echo '<pre>'; print_r($web_service_result); exit;

                if (!empty($web_service_result->case_details[0]->diary_no)){
                    if(isset($web_service_result->case_details[0]->parties) && !empty($web_service_result->case_details[0]->parties)){
                        unset($web_service_result->case_details[0]->parties);
                    }
                   // $data['searched_case_details'] = $web_service_result->case_details[0];
                    $this->session->set_userdata('searched_case_details',$web_service_result->case_details[0]);
                    $diary_no = !empty($web_service_result->case_details[0]->diary_no) ? (string)$web_service_result->case_details[0]->diary_no : NULL;
                    $diary_year =  !empty($web_service_result->case_details[0]->diary_year) ? (string)$web_service_result->case_details[0]->diary_year : NULL;
                    $this->load->model('miscellaneous_docs/Miscellaneous_docs_model');
                    $appearance_exists=$this->Miscellaneous_docs_model->check_existence_of_appearing_for($diary_no,$diary_year);
                    if(isset($appearance_exists) && !empty($appearance_exists)){
//                    $params = array();
//                    $params['table_name'] ='efil.tbl_case_details';
//                    $params['whereFieldName'] ='sc_diary_num';
//                    $params['whereFieldValue'] = !empty($web_service_result->case_details[0]->diary_no) ? (string)$web_service_result->case_details[0]->diary_no : NULL;
//                    $params['is_deleted'] ='is_deleted';
//                    $params['sc_diary_year'] = !empty($web_service_result->case_details[0]->diary_year) ? (string)$web_service_result->case_details[0]->diary_year : NULL;
//                    $caseDetails = $this->Common_model->getData($params);
                   // echo '<pre>'; print_r($caseDetails); exit;
//                        if(isset($caseDetails[0]->registration_id) && !empty($caseDetails[0]->registration_id)){
//                            $registration_id = (int)$caseDetails[0]->registration_id;

                       // }
                        $this->session->set_userdata('searchDiarynoYear',$diary_no.$diary_year);
                    }
                    else{
                        $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center"><p>You are not appearing in this case.Please select an other case.</p></div>');
                        $this->session->set_userdata('searchDiarynoYear','');
                        unset($_SESSION['searched_case_details']);
                        redirect('documentSharingWithProof');
                    }
            }
            else{
                $this->session->set_userdata('searched_case_details','');
                unset($_SESSION['searched_case_details']);
                unset($_SESSION['searchDiarynoYear']);
                $this->session->set_flashdata('msg','<div class="alert alert-danger text-center"><p>No Record Founds!</p></div>');
                $data['searched_case_details'] = array();
            }

                $arr = array();
                $diaryArr = array();
                $arr['user_id'] = !empty($_SESSION['login']['id']) ? $_SESSION['login']['id'] : null;
                $docsharesDiaries = $this->DocumentShare->docShareDetailsByDiaryNo($arr);
                if(isset($docsharesDiaries) && !empty($docsharesDiaries)){
                    $groupWiseArrData = array();
                    foreach ($docsharesDiaries as $k=>$v){
                        $diaryArr[$v['diary_no']] = explode(',',$v['shared_uniq_id']);
                        $share_uniq_id = !empty($v['shared_uniq_id']) ?  $v['shared_uniq_id'] : NULL;
                        $diary_no = !empty($v['diary_no']) ?  $v['diary_no'] : NULL;
                        if(isset($share_uniq_id) && !empty($share_uniq_id)){
                            $share_uniq_id_arr = explode(',',$share_uniq_id);
                            if(isset($share_uniq_id_arr) && !empty($share_uniq_id_arr) && count($share_uniq_id_arr)>0){
                                foreach ($share_uniq_id_arr as $key=>$value){
                                    $share_uniq_id_each = $share_uniq_id_arr[$key];
                                    $tmp = array();
                                    $tmp['shared_uniq_id'] = $share_uniq_id_each;
                                    $groupData = $this->DocumentShare->geShareDetailsGroupWose($tmp);
                                    $share_doc_id = !empty($groupData[0]->share_doc_id) ? explode(',',$groupData[0]->share_doc_id) : NULL;
                                    $a = array();
                                    $a['docIdArr'] = $share_doc_id;
                                    $docData = $this->DocumentShare->getEfiledDocByDocId($a);
                                    $shareDocDetails = array();
                                    if(isset($docData) && !empty($docData)){
                                        foreach ($docData as $key1=>$value1){
                                            $doc_id = !empty($value1['doc_type_id']) ? (int)$value1['doc_type_id'] : NULL;
                                            $sub_doc_id = !empty($value1['sub_doc_type_id']) ? (int)$value1['sub_doc_type_id'] : NULL;
                                            $doc_title = !empty($value1['doc_title']) ? $value1['doc_title'] : NULL;
                                            $doc= array();
                                            $shareMainDocDetails='';
                                            $shareSubDocDetails ='';
                                            if(isset($doc_id) && !empty($doc_id) && isset($sub_doc_id) && !empty($sub_doc_id)){
                                                $doc['doc_id'] = $doc_id;
                                                $doc['sub_doc_id'] = $sub_doc_id;
                                                $shareSubDocDetails = $this->DocumentShare->getShareDocumentDetails($doc);
                                            }
                                            if(isset($doc_id) && !empty($doc_id)){
                                                $doc= array();
                                                $doc['doc_id'] = $doc_id;
                                                $shareMainDocDetails = $this->DocumentShare->getShareDocumentDetails($doc);
                                            }
                                            $document = !empty($shareMainDocDetails[0]['docdesc']) ? $shareMainDocDetails[0]['docdesc'] : 'N/A';
                                            $sub_document = !empty($shareSubDocDetails[0]['docdesc']) ? $shareSubDocDetails[0]['docdesc'] : 'N/A';
                                            $darr = array();
                                            $darr['doc_title'] = $doc_title;
                                            $darr['document'] = $document;
                                            $darr['sub_document'] = $sub_document;
                                            $shareDocDetails[] = $darr;

                                        }
                                    }
                                    if(isset($groupData) && !empty($groupData)){
                                        foreach ($groupData as $k1=>$v1){
                                            $v1->shareDocDetails= $shareDocDetails;
                                        }
                                        $groupWiseArrData[$diary_no][$share_uniq_id_each] = $groupData;
                                    }
                                }
                            }
                        }
                    }
                }
                $data['diaryArr'] = $diaryArr;
                $data['groupWiseArrData'] = $groupWiseArrData;
                $searchDiarynoYear = !empty($this->session->userdata('searchDiarynoYear')) ? $this->session->userdata('searchDiarynoYear') : NULL;
                $docDetails = $this->DocumentShare->getEfiledDoc($searchDiarynoYear);
                if(isset($docDetails) && !empty($docDetails)){
                    foreach ($docDetails as $k=>&$value1){
                        $doc_id = !empty($value1['doc_type_id']) ? (int)$value1['doc_type_id'] : NULL;
                        $sub_doc_id = !empty($value1['sub_doc_type_id']) ? (int)$value1['sub_doc_type_id'] : NULL;
                        $doc_title = !empty($value1['doc_title']) ? $value1['doc_title'] : NULL;
                        $doc= array();
                        $shareMainDocDetails='';
                        $shareSubDocDetails ='';
                        if(isset($doc_id) && !empty($doc_id) && isset($sub_doc_id) && !empty($sub_doc_id)){
                            $doc['doc_id'] = $doc_id;
                            $doc['sub_doc_id'] = $sub_doc_id;
                            $shareSubDocDetails = $this->DocumentShare->getShareDocumentDetails($doc);
                        }
                        if(isset($doc_id) && !empty($doc_id)){
                            $doc= array();
                            $doc['doc_id'] = $doc_id;
                            $shareMainDocDetails = $this->DocumentShare->getShareDocumentDetails($doc);
                        }
                        $document = !empty($shareMainDocDetails[0]['docdesc']) ? $shareMainDocDetails[0]['docdesc'] : 'N/A';
                        $sub_document = !empty($shareSubDocDetails[0]['docdesc']) ? $shareSubDocDetails[0]['docdesc'] : 'N/A';
                        $darr = array();
                        $darr['doc_title'] = $doc_title;
                        $darr['document'] = $document;
                        $darr['sub_document'] = $sub_document;
                        $value1['shareDocDetails'] = $darr;
                    }
                }
            $data['docDetails'] = !empty($docDetails) ? $docDetails : NULL;
            $this->load->model('newcase/Dropdown_list_model');
            $data['sc_case_type'] = $this->Dropdown_list_model->getSciCaseTypeOrderById();
            $data['doc_type'] = $this->DocumentIndex_DropDown_model->get_document_type(NULL);
            $this->load->view('templates/header');
            $this->load->view('documentSharingWithProof/ia_miscdoc_case_search_view', $data);
            $this->load->view('templates/footer');
        }
    }
    public function uploadDocumentForSharing(){
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            redirect('login');
            exit(0);
        }
        $data = array();
        if(!empty($_FILES['shareDocument']['name']) && !empty($_POST['doc_title']) && !empty($_POST['document_id'])  && !empty($this->session->userdata('searchDiarynoYear'))){
            $searchDiarynoYear = !empty($this->session->userdata('searchDiarynoYear')) ? (int)$this->session->userdata('searchDiarynoYear') : NULL;
            $doc_title= !empty($this->input->post('doc_title')) ? $this->input->post('doc_title') : NULL;
//            $params = array();
//            $params['table_name'] ='efil.tbl_efiling_nums';
//            $params['whereFieldName'] ='registration_id';
//            $params['whereFieldValue'] = !empty($this->session->userdata('searchDiaryRegistrationId')) ? (int)$this->session->userdata('searchDiaryRegistrationId'): NULL;
//            $params['is_deleted'] ='is_deleted';
//            $efilingDetails = $this->Common_model->getData($params);
            $docSharingEfiling = $this->DocumentShare->genDocumentSharingEfilingNumber();
            $efiling_no  = !empty($docSharingEfiling['doc_sharing_efiling_no']) ? $docSharingEfiling['doc_sharing_efiling_no'] : 1;
            $efilingYear  = !empty($docSharingEfiling['doc_sharing_efiling_year']) ? $docSharingEfiling['doc_sharing_efiling_year'] : 2021;
            $num_pre_fix = "ES";
            $est_code = !empty($_SESSION['estab_details']['estab_code']) ? $_SESSION['estab_details']['estab_code'] : 1;
            $efiling = sprintf("%'.05d\n", $efiling_no);
            $string = $num_pre_fix . $est_code . $efiling . $efilingYear;
            $efiling_no = preg_replace('/\s+/', '', $string);
//            $ref_m_efiled_type_id = !empty($efilingDetails[0]->ref_m_efiled_type_id) ? $efilingDetails[0]->ref_m_efiled_type_id : NULL;
            $ext = '';
            $file_type = !empty($_FILES['shareDocument']['type']) ? $_FILES['shareDocument']['type'] : NULL;
            if (isset($file_type) && !empty($file_type)) {
                $extarr = explode('/', $file_type);
                $ext = !empty($extarr[1]) ? $extarr[1] : 'pdf';
            }
            $tmp_file_name = $efiling_no.time() . rand() . '.' . $ext;
            $estab_code = !empty($this->session->userdata('estab_details')['estab_code']) ? $this->session->userdata('estab_details')['estab_code'] : 'SCIN01';
            $dir =  "uploaded_docs/" . $estab_code . "/" . $efiling_no . "/docs/";
            if (!file_exists($dir)) {
                mkdir($dir, 0755, true);
            }
            $config['upload_path'] = $dir;
            $config['allowed_types'] = 'pdf';
            $config['max_size'] = UPLOADED_FILE_SIZE / 1024;
            $config['overwrite'] = TRUE;
            $config['file_name'] = $tmp_file_name;
            $this->load->library('upload', $config);
            $file_name='';
            if (!$this->upload->do_upload('shareDocument')){
                $this->session->set_flashdata('msg', $this->upload->display_errors());
                $this->index($data);
            }
            else {
                $file_name = $this->upload->data('file_name');
                $doc_hash_value = hash_file('sha256', $_FILES['shareDocument']['tmp_name']);
                $uploaded_on = date('Y-m-d H:i:s');
                $sub_created_by = 0;
                $uploaded_by = $_SESSION['login']['id'];
                $insertData = array(
                    'registration_id' => NULL,
                    'efiled_type_id' => E_FILING_TYPE_NEW_CASE,
                    'file_size' => $_FILES['shareDocument']['size'],
                    'file_type' => $_FILES['shareDocument']['type'],
                    'doc_title' => strtoupper($doc_title),
                    'file_name'=>$file_name,
                    'file_path'=> $dir.$file_name,
                    'is_active'=>true,
                    'is_deleted'=>false,
                    'doc_hashed_value' => $doc_hash_value,
                    'uploaded_by' => $uploaded_by,
                    'uploaded_on' => $uploaded_on,
                    'upload_ip_address' => $_SERVER['REMOTE_ADDR'],
                    'sub_created_by' => $sub_created_by,
                    'pspdfkit_document_id'=>NULL
                );
                $efiled_docsArr = array();
                $efiled_docsArr['registration_id'] = NULL;
                $efiled_docsArr['efiled_type_id'] =E_FILING_TYPE_NEW_CASE;
                $efiled_docsArr['doc_type_id'] = !empty($_POST['document_id']) ? url_decryption($_POST['document_id']) : 0;
                $efiled_docsArr['sub_doc_type_id'] = !empty($_POST['sub_document_id']) ? url_decryption($_POST['sub_document_id']) : 0;
                $efiled_docsArr['file_name'] =$file_name;
                $efiled_docsArr['file_path'] =$dir.$file_name;
                $efiled_docsArr['icmis_diary_no'] = $searchDiarynoYear;
                $efiled_docsArr['doc_title'] =strtoupper($doc_title);
                $efiled_docsArr['is_active'] = true;
                $efiled_docsArr['file_type'] = $_FILES['shareDocument']['type'];
                $efiled_docsArr['file_size'] = $_FILES['shareDocument']['size'];
                $efiled_docsArr['doc_hashed_value'] = $doc_hash_value;
                $efiled_docsArr['uploaded_by'] = $uploaded_by;
                $efiled_docsArr['upload_ip_address'] = $_SERVER['REMOTE_ADDR'];
                $efiled_docsArr['is_deleted'] = false;
                $efiled_docsArr['pspdfkit_document_id'] = NULL;
                $this->load->model('citation/Citation_model');
                $table = "efil.tbl_uploaded_pdfs";
                $response = $this->Citation_model->insertData($table,$insertData);
                if(isset($response) && !empty($response)){
                    $tableName = "efil.tbl_efiled_docs";
                    $res = $this->Citation_model->insertData($tableName,$efiled_docsArr);
                    if(isset($res) && !empty($res)){
                        $this->session->set_flashdata('msg','<div class="alert alert-danger text-center"><p>Document file has been uploaded successfully</p></div>');
                    }
                    else{
                        $this->session->set_flashdata('msg','Something went wrong,Please try again.');
                    }
                    if(isset($searchDiarynoYear) && !empty($searchDiarynoYear)){
                        $arr = array();
                        $diaryArr = array();
                        $arr['user_id'] = !empty($_SESSION['login']['id']) ? $_SESSION['login']['id'] : null;
                        $docsharesDiaries = $this->DocumentShare->docShareDetailsByDiaryNo($arr);
                        if(isset($docsharesDiaries) && !empty($docsharesDiaries)){
                            $groupWiseArrData = array();
                            foreach ($docsharesDiaries as $k=>$v){
                                $diaryArr[$v['diary_no']] = explode(',',$v['shared_uniq_id']);
                                $share_uniq_id = !empty($v['shared_uniq_id']) ?  $v['shared_uniq_id'] : NULL;
                                $diary_no = !empty($v['diary_no']) ?  $v['diary_no'] : NULL;
                                if(isset($share_uniq_id) && !empty($share_uniq_id)){
                                    $share_uniq_id_arr = explode(',',$share_uniq_id);
                                    if(isset($share_uniq_id_arr) && !empty($share_uniq_id_arr) && count($share_uniq_id_arr)>0){
                                        foreach ($share_uniq_id_arr as $key=>$value){
                                            $share_uniq_id_each = $share_uniq_id_arr[$key];
                                            $tmp = array();
                                            $tmp['shared_uniq_id'] = $share_uniq_id_each;
                                            $groupData = $this->DocumentShare->geShareDetailsGroupWose($tmp);
                                            $share_doc_id = !empty($groupData[0]->share_doc_id) ? explode(',',$groupData[0]->share_doc_id) : NULL;
                                            $a = array();
                                            $a['docIdArr'] = $share_doc_id;
                                            $docData = $this->DocumentShare->getEfiledDocByDocId($a);
                                            $shareDocDetails = array();
                                            if(isset($docData) && !empty($docData)){
                                                foreach ($docData as $key1=>$value1){
                                                    $doc_id = !empty($value1['doc_type_id']) ? (int)$value1['doc_type_id'] : NULL;
                                                    $sub_doc_id = !empty($value1['sub_doc_type_id']) ? (int)$value1['sub_doc_type_id'] : NULL;
                                                    $doc_title = !empty($value1['doc_title']) ? $value1['doc_title'] : NULL;
                                                    $doc= array();
                                                    $shareMainDocDetails='';
                                                    $shareSubDocDetails ='';
                                                    if(isset($doc_id) && !empty($doc_id) && isset($sub_doc_id) && !empty($sub_doc_id)){
                                                        $doc['doc_id'] = $doc_id;
                                                        $doc['sub_doc_id'] = $sub_doc_id;
                                                        $shareSubDocDetails = $this->DocumentShare->getShareDocumentDetails($doc);
                                                    }
                                                    if(isset($doc_id) && !empty($doc_id)){
                                                        $doc= array();
                                                        $doc['doc_id'] = $doc_id;
                                                        $shareMainDocDetails = $this->DocumentShare->getShareDocumentDetails($doc);
                                                    }
                                                    $document = !empty($shareMainDocDetails[0]['docdesc']) ? $shareMainDocDetails[0]['docdesc'] : 'N/A';
                                                    $sub_document = !empty($shareSubDocDetails[0]['docdesc']) ? $shareSubDocDetails[0]['docdesc'] : 'N/A';
                                                    $darr = array();
                                                    $darr['doc_title'] = $doc_title;
                                                    $darr['document'] = $document;
                                                    $darr['sub_document'] = $sub_document;
                                                    $shareDocDetails[] = $darr;

                                                }
                                            }

                                            if(isset($groupData) && !empty($groupData)){
                                                foreach ($groupData as $k1=>$v1){
                                                    $v1->shareDocDetails= $shareDocDetails;
                                                }
                                                $groupWiseArrData[$diary_no][$share_uniq_id_each] = $groupData;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        // echo '<pre>'; print_r($diaryArr); exit;
                        $data['diaryArr'] = $diaryArr;
                        $data['groupWiseArrData'] = $groupWiseArrData;
                        $data['doc_type'] = $this->DocumentIndex_DropDown_model->get_document_type(NULL);
                    }
                    $this->index($data);
                }
                else{
                    $this->session->set_flashdata('msg','Something went wrong,Please try again.');
                    $this->index($data);
                }
            }
        }
        else{
                $this->session->set_flashdata('msg','Please select document,sub document,and fill title of document and select upload document.');
                $this->index($data);
        }
    }
    public function getDocDetails(){
        $allowedUsers = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowedUsers)){
            redirect('login');
            exit(0);
        }
        $output =false;
        $postData = json_decode(file_get_contents('php://input'), true);
        if(isset($postData['docid']) && !empty($postData['docid'])){
            $doc_id = (int)$postData['docid'];
            $docDetails = $this->Common_model->getDocDetailsById($doc_id);
            if(isset($docDetails) && !empty($docDetails)){
                $output = $docDetails;
            }
        }
        echo json_encode($output);
        exit(0);
    }
    public function getUsersByType(){
        $allowedUsers = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowedUsers)){
            redirect('login');
            exit(0);
        }
        $output = '';
        $usrArr = array();
        $postData = json_decode(file_get_contents('php://input'), true);
        if(isset($postData['userType']) && !empty($postData['userType'])){
            $userType = trim($postData['userType']);
            switch ($userType){
                case 'a_or':
                    $usrArr['shareUserType'] = USER_ADVOCATE;
                    $aorUsers = $this->Common_model->getAorSrAdvocateArguinCounsel($usrArr);
                    if(isset($aorUsers) && !empty($aorUsers)){
                        $output = !empty($aorUsers) ? $aorUsers : NULL;
                    }
                    break;
                case 'sr_a':
                    $usrArr['shareUserType'] = SR_ADVOCATE;
                    $srAdvUsers = $this->Common_model->getAorSrAdvocateArguinCounsel($usrArr);
                    if(isset($srAdvUsers) && !empty($srAdvUsers)) {
                        $output = !empty($srAdvUsers) ? $srAdvUsers : NULL;
                    }
                    break;
                case 'a_c':
                    $usrArr['shareUserType'] = ARGUING_COUNSEL;
                    $arguingUsers = $this->Common_model->getAorSrAdvocateArguinCounsel($usrArr);
                    if(isset($arguingUsers) && !empty($arguingUsers)) {
                        foreach ($arguingUsers as $k => $v) {
                            $output = !empty($arguingUsers) ? $arguingUsers : NULL;
                        }
                    }
                    break;
            }
        }
        echo json_encode($output);
        exit(0);
    }
    public function docShareToEmail(){
        $allowedUsers = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowedUsers)){
            redirect('login');
            exit(0);
        }
        $output = array();
        $selectedUserArr = array();
        $postData = json_decode(file_get_contents('php://input'), true);
        $shared_uniq_id = mt_rand(100000,999999);
        $other_name = !empty($postData['other_name']) ? $postData['other_name'] : NULL;
        $other_email = !empty($postData['other_email']) ? $postData['other_email'] : NULL;
        $other_mobile = !empty($postData['other_mobile']) ? $postData['other_mobile'] : NULL;
        if((!empty($other_name) &&  !empty($other_email) &&  !empty($other_mobile)) && (empty($postData['selectedUserArr'])
                && count($postData['selectedUserArr'])== 0 && !empty($postData['shareDocIdArr']))){
                     //other
                    $shareDocIdArr = implode(',',$postData['shareDocIdArr']);
                    $shareDocId = $shareDocIdArr;
                    $doc_type_id =    NULL;
                    $sub_doc_type_id = NULL;
                    $shareArrFrom = array();
                    $shareArrFrom['user_id'] = !empty($_SESSION['login']['id']) ? $_SESSION['login']['id'] : NULL;
                    $shareArrFrom['doc_id'] = $doc_type_id;
                    $shareArrFrom['sub_doc_id'] = $sub_doc_type_id;
                    $shareArrFrom['shared_uniq_id'] = $shared_uniq_id;
                    $shareArrFrom['share_doc_id'] = $shareDocId;
                    $shareArrFrom['email'] = !empty($_SESSION['login']['emailid']) ? $_SESSION['login']['emailid'] : NULL;
                    $shareArrFrom['mobile'] = !empty($_SESSION['login']['mobile_number']) ? $_SESSION['login']['mobile_number'] : NULL;
                    $shareArrFrom['name'] = !empty($_SESSION['login']['first_name']) ? $_SESSION['login']['first_name'] : NULL;
                    $shareArrFrom['created_on'] = date('Y-m-d H:i:s');
                    $shareArrFrom['created_ip'] = $_SERVER['REMOTE_ADDR'];
                    $shareArrFrom['is_active'] = true;
                    $shareArrFrom['diary_no'] = !empty($_SESSION['searchDiaryNo']) ? $_SESSION['searchDiaryNo'] : NULL;
                    $shareArrFrom['diary_year'] = !empty($_SESSION['searchDiaryYear']) ? $_SESSION['searchDiaryYear'] : NULL;
                    $shareArrFrom['case_type'] = !empty($_SESSION['searchScCaseType']) ? $_SESSION['searchScCaseType'] : NULL;
                    $shareArrFrom['case_no'] = !empty($_SESSION['searchCaseNumber']) ? $_SESSION['searchCaseNumber'] : NULL;
                    $shareArrFrom['case_year'] = !empty($_SESSION['searchCaseYear']) ? $_SESSION['searchCaseYear'] : NULL;
                    $cause_title = !empty($this->session->userdata('searched_case_details')->cause_title) ? $this->session->userdata('searched_case_details')->cause_title : '';
                    $shareArrFrom['cause_title'] = $cause_title;
                    $this->load->model('citation/Citation_model');
                    $table = "efil.tbl_doc_share_from";
                    $insetId = $this->Citation_model->insertData($table, $shareArrFrom);
                  //  $insetId = true;
                    if(isset($insetId) && !empty($insetId)){
                        $tmpArr = array();
                        $tmpArr['user_id'] = 0;
                        $tmpArr['doc_id'] = $doc_type_id;
                        $tmpArr['sub_doc_id'] = $sub_doc_type_id;
                        $tmpArr['doc_share_from_id'] = $insetId;
                        $tmpArr['email'] = $other_email;
                        $tmpArr['mobile'] = $other_mobile;
                        $tmpArr['name'] = $other_name;
                        $tmpArr['share_doc_id'] = $shareDocId;
                        $tmpArr['created_on'] = date('Y-m-d H:i:s');
                        $tmpArr['created_ip'] = $_SERVER['REMOTE_ADDR'];
                        $tmpArr['is_active'] = true;
                        $tmpArr['shared_uniq_id'] = $shared_uniq_id;
                        $table_name = "efil.tbl_doc_share_to";
                        $insertedId = $this->Citation_model->insertData($table_name, $tmpArr);
                        //$insertedId = true;
                    }
                    if(isset($insertedId) && !empty($insertedId)){
                        //sent email with doc
                        $a = array();
                        $a['docIdArr'] = $postData['shareDocIdArr'];
                        $docData = $this->DocumentShare->getEfiledDocByDocId($a);
                        if(isset($docData) && !empty($docData) && isset($other_email) && !empty($other_email)){
                            foreach ($docData as $k=>$v){
                                $file_url = !empty($v['file_path']) ? $v['file_path'] : NULL;
                                $doc_title = !empty($v['doc_title']) ? $v['doc_title'] : NULL;
                                if(isset($file_url) && !empty($file_url)){
                                $params = array();
                                $params['email'] = $other_email;
                                $params['file_url'] = $file_url;
                                $params['subject'] = "Document Shared";
                                $params['message']='Dear '.$other_name.' ,please find attachment of share document and click to download <br> <a title="'.$doc_title.'" href="'.base_url($file_url).'">Download</a>';
                                shareDocToEmail($params);
                                }
                            }
                        }
                        $output['success'] = true;
                        $output['message'] = "Document sharing has been successfully";
                    }
                    else{
                        $output['success'] = false;
                        $output['message'] = "Something went wrong,Please try again later!";
                    }

        }
        else if((!empty($other_name) &&  !empty($other_email) && !empty($other_mobile)) && (!empty($postData['selectedUserArr'])
                && count($postData['selectedUserArr'])>0 && !empty($postData['shareDocIdArr']))){
            //users and other
            $arr = array();
            $shareDocIdArr = implode(',',$postData['shareDocIdArr']);
            $shareDocId = $shareDocIdArr;
            $arr['idArr'] = $postData['selectedUserArr'];
            $getUserlist = $this->DocumentShare->UserListByIdArr($arr);
            if(isset($getUserlist) && !empty($getUserlist)){
                $doc_type_id = NULL;
                $sub_doc_type_id = NULL ;
                $shareArrFrom = array();
                $shareArrFrom['user_id'] = !empty($_SESSION['login']['id']) ? $_SESSION['login']['id'] : NULL;
                $shareArrFrom['doc_id'] = $doc_type_id;
                $shareArrFrom['sub_doc_id'] = $sub_doc_type_id;
                $shareArrFrom['share_doc_id'] = $shareDocId;
                $shareArrFrom['shared_uniq_id'] = $shared_uniq_id;
                $shareArrFrom['created_on'] = date('Y-m-d H:i:s');
                $shareArrFrom['created_ip'] = $_SERVER['REMOTE_ADDR'];
                $shareArrFrom['email'] = !empty($_SESSION['login']['emailid']) ? $_SESSION['login']['emailid'] : NULL;
                $shareArrFrom['mobile'] = !empty($_SESSION['login']['mobile_number']) ? $_SESSION['login']['mobile_number'] : NULL;
                $shareArrFrom['name'] = !empty($_SESSION['login']['first_name']) ? $_SESSION['login']['first_name'] : NULL;
                $shareArrFrom['is_active'] = true;
                $shareArrFrom['diary_no'] = !empty($_SESSION['searchDiaryNo']) ? $_SESSION['searchDiaryNo'] : NULL;
                $shareArrFrom['diary_year'] = !empty($_SESSION['searchDiaryYear']) ? $_SESSION['searchDiaryYear'] : NULL;
                $shareArrFrom['case_type'] = !empty($_SESSION['searchScCaseType']) ? $_SESSION['searchScCaseType'] : NULL;
                $shareArrFrom['case_no'] = !empty($_SESSION['searchCaseNumber']) ? $_SESSION['searchCaseNumber'] : NULL;
                $shareArrFrom['case_year'] = !empty($_SESSION['searchCaseYear']) ? $_SESSION['searchCaseYear'] : NULL;
                $cause_title = !empty($this->session->userdata('searched_case_details')->cause_title) ? $this->session->userdata('searched_case_details')->cause_title : '';
                $shareArrFrom['cause_title'] = $cause_title;
                $this->load->model('citation/Citation_model');
                $table = "efil.tbl_doc_share_from";
                $insetId = $this->Citation_model->insertData($table, $shareArrFrom);
                if(isset($insetId) && !empty($insetId)){
                    $shareArrTo = array();
                        foreach ($getUserlist as $k=>$v){
                            $tmpArr = array();
                            $tmpArr['user_id'] = !empty($v['id']) ? $v['id'] : NULL;
                            $tmpArr['doc_id'] = $doc_type_id;
                            $tmpArr['sub_doc_id'] = $sub_doc_type_id;
                            $tmpArr['doc_share_from_id'] = $insetId;
                            $tmpArr['shared_uniq_id'] = $shared_uniq_id;
                            $tmpArr['email'] = !empty($v['emailid']) ? $v['emailid'] : NULL;
                            $tmpArr['mobile'] = !empty($v['moblie_number']) ? $v['moblie_number'] : NULL;
                            $tmpArr['name'] = !empty($v['first_name']) ? $v['first_name'] : NULL;
                            $tmpArr['share_doc_id'] = $shareDocId;
                            $tmpArr['created_on'] = date('Y-m-d H:i:s');
                            $tmpArr['created_ip'] = $_SERVER['REMOTE_ADDR'];
                            $tmpArr['is_active'] = true;
                            array_push($shareArrTo,$tmpArr);
                        }
                        $table_name = "efil.tbl_doc_share_to";
                        if(count($shareArrTo)>1){
                            $this->DocumentShare->insertBatchData($table_name,$shareArrTo);
                            //others
                            $tmpArr = array();
                            $tmpArr['user_id'] = 0;
                            $tmpArr['doc_id'] = $doc_type_id;
                            $tmpArr['email'] = $other_email;
                            $tmpArr['mobile'] = $other_mobile;
                            $tmpArr['name'] = $other_name;
                            $tmpArr['sub_doc_id'] = $sub_doc_type_id;
                            $tmpArr['doc_share_from_id'] = $insetId;
                            $tmpArr['share_doc_id'] = $shareDocId;
                            $tmpArr['shared_uniq_id'] = $shared_uniq_id;
                            $tmpArr['created_on'] = date('Y-m-d H:i:s');
                            $tmpArr['created_ip'] = $_SERVER['REMOTE_ADDR'];
                            $tmpArr['is_active'] = true;
                            $insertedId = $this->Citation_model->insertData($table_name, $tmpArr);
                            if(isset($insertedId) && !empty($insertedId)){
                                //sent email with doc
                                $a = array();
                                $a['docIdArr'] = $postData['shareDocIdArr'];
                                $docData = $this->DocumentShare->getEfiledDocByDocId($a);
                                if(isset($docData) && !empty($docData)){
                                    foreach ($docData as $k=>$v){
                                        $file_url = !empty($v['file_path']) ? $v['file_path'] : NULL;
                                        $doc_title = !empty($v['doc_title']) ? $v['doc_title'] : NULL;
                                        if(isset($file_url) && !empty($file_url) && isset($getUserlist) && !empty($getUserlist)){
                                            foreach ($getUserlist as $k=>$v) {
                                                $email = !empty($v['emailid']) ? $v['emailid'] : NULL;
                                                $name = !empty($v['first_name']) ? $v['first_name'] : '';
                                                $params = array();
                                                $params['email'] = $email;
                                                $params['file_url'] = $file_url;
                                                $params['subject'] = "Document Shared";
                                                $params['message']='Dear '.$name.' ,please find attachment of share document and click to download <br> <a title="'.$doc_title.'" href="'.base_url($file_url).'">Download</a>';
                                                shareDocToEmail($params);
                                            }
                                        }
                                    }
                                }
                                $output['success'] = true;
                                $output['message'] = "Document sharing has been successfully";
                            }
                            else{
                                $output['success'] = false;
                                $output['message'] = "Something went wrong,Please try again later!";
                            }
                        }
                        else{
                            $this->Citation_model->insertData($table_name, $shareArrTo[0]);
                            //others
                            $tmpArr = array();
                            $tmpArr['user_id'] = 0;
                            $tmpArr['doc_id'] = $doc_type_id;
                            $tmpArr['email'] = $other_email;
                            $tmpArr['mobile'] = $other_mobile;
                            $tmpArr['name'] = $other_name;
                            $tmpArr['sub_doc_id'] = $sub_doc_type_id;
                            $tmpArr['doc_share_from_id'] = $insetId;
                            $tmpArr['share_doc_id'] = $shareDocId;
                            $tmpArr['created_on'] = date('Y-m-d H:i:s');
                            $tmpArr['created_ip'] = $_SERVER['REMOTE_ADDR'];
                            $tmpArr['is_active'] = true;
                            $table_name = "efil.tbl_doc_share_to";
                            $insertedId = $this->Citation_model->insertData($table_name, $tmpArr);
                            if(isset($insertedId) && !empty($insertedId)){
                                //sent email with doc
                                $a = array();
                                $a['docIdArr'] = $postData['shareDocIdArr'];
                                $docData = $this->DocumentShare->getEfiledDocByDocId($a);
                                if(isset($docData) && !empty($docData) && isset($other_email) && !empty($other_email)){
                                    foreach ($docData as $k=>$v){
                                        $file_url = !empty($v['file_path']) ? $v['file_path'] : NULL;
                                        $doc_title = !empty($v['doc_title']) ? $v['doc_title'] : NULL;
                                        if(isset($file_url) && !empty($file_url)){
                                            $params = array();
                                            $params['email'] = $other_email;
                                            $params['file_url'] = $file_url;
                                            $params['subject'] = "Document Shared";
                                            $params['message']='Dear '.$other_name.' ,please find attachment of share document and click to download <br> <a title="'.$doc_title.'" href="'.base_url($file_url).'">Download</a>';
                                            shareDocToEmail($params);
                                        }
                                    }
                                }
                                $output['success'] = true;
                                $output['message'] = "Document sharing has been successfully";
                            }
                            else{
                                $output['success'] = false;
                                $output['message'] = "Something went wrong,Please try again later!";
                            }
                        }
                }


            }

        }
        else if((empty($other_name) && empty($other_email) && empty($other_mobile)) && (!empty($postData['selectedUserArr'])
                && count($postData['selectedUserArr'])>0) && !empty($postData['shareDocIdArr'])){
            //users
            $arr = array();
            $shareDocIdArr = implode(',',$postData['shareDocIdArr']);
            $shareDocId = $shareDocIdArr;
            $arr['idArr'] = $postData['selectedUserArr'];
            $getUserlist = $this->DocumentShare->UserListByIdArr($arr);
            $doc_type_id = NULL;
            $sub_doc_type_id =  NULL;
            $shareArrFrom = array();
            $shareArrFrom['user_id'] = !empty($_SESSION['login']['id']) ? $_SESSION['login']['id'] : NULL;
            $shareArrFrom['email'] = !empty($_SESSION['login']['emailid']) ? $_SESSION['login']['emailid'] : NULL;
            $shareArrFrom['mobile'] = !empty($_SESSION['login']['mobile_number']) ? $_SESSION['login']['mobile_number'] : NULL;
            $shareArrFrom['name'] = !empty($_SESSION['login']['first_name']) ? $_SESSION['login']['first_name'] : NULL;
            $shareArrFrom['doc_id'] = $doc_type_id;
            $shareArrFrom['sub_doc_id'] = $sub_doc_type_id;
            $shareArrFrom['share_doc_id'] = $shareDocId;
            $shareArrFrom['shared_uniq_id'] = $shared_uniq_id;
            $shareArrFrom['created_on'] = date('Y-m-d H:i:s');
            $shareArrFrom['created_ip'] = $_SERVER['REMOTE_ADDR'];
            $shareArrFrom['is_active'] = true;
            $shareArrFrom['diary_no'] = !empty($_SESSION['searchDiaryNo']) ? $_SESSION['searchDiaryNo'] : NULL;
            $shareArrFrom['diary_year'] = !empty($_SESSION['searchDiaryYear']) ? $_SESSION['searchDiaryYear'] : NULL;
            $shareArrFrom['case_type'] = !empty($_SESSION['searchScCaseType']) ? $_SESSION['searchScCaseType'] : NULL;
            $shareArrFrom['case_no'] = !empty($_SESSION['searchCaseNumber']) ? $_SESSION['searchCaseNumber'] : NULL;
            $shareArrFrom['case_year'] = !empty($_SESSION['searchCaseYear']) ? $_SESSION['searchCaseYear'] : NULL;
            $cause_title = !empty($this->session->userdata('searched_case_details')->cause_title) ? $this->session->userdata('searched_case_details')->cause_title : '';
            $shareArrFrom['cause_title'] = $cause_title;
            $this->load->model('citation/Citation_model');
            $table = "efil.tbl_doc_share_from";
            $insetId = $this->Citation_model->insertData($table, $shareArrFrom);
            if(isset($insetId) && !empty($insetId)){
                $shareArrTo = array();
                if(isset($getUserlist) && !empty($getUserlist)){
                    foreach ($getUserlist as $k=>$v){
                        $tmpArr = array();
                        $tmpArr['user_id'] = !empty($v['id']) ? $v['id'] : NULL;
                        $tmpArr['doc_id'] = $doc_type_id;
                        $tmpArr['sub_doc_id'] = $sub_doc_type_id;
                        $tmpArr['doc_share_from_id'] = $insetId;
                        $tmpArr['shared_uniq_id'] = $shared_uniq_id;
                        $tmpArr['email'] = !empty($v['emailid']) ? $v['emailid'] : NULL;
                        $tmpArr['mobile'] = !empty($v['moblie_number']) ? $v['moblie_number'] : NULL;
                        $tmpArr['name'] = !empty($v['first_name']) ? $v['first_name'] : NULL;
                        $tmpArr['share_doc_id'] = $shareDocId;
                        $tmpArr['created_on'] = date('Y-m-d H:i:s');
                        $tmpArr['created_ip'] = $_SERVER['REMOTE_ADDR'];
                        $tmpArr['is_active'] = true;
                        array_push($shareArrTo,$tmpArr);
                    }
                    $table_name = "efil.tbl_doc_share_to";
                    if(count($shareArrTo)>1){
                        $insertedId = $this->DocumentShare->insertBatchData($table_name,$shareArrTo);
                    }
                    else{
                        $insertedId = $this->Citation_model->insertData($table_name, $shareArrTo[0]);
                    }
                    if(isset($insertedId) && !empty($insertedId)){
                        //sent email with doc
                        $a = array();
                        $a['docIdArr'] = $postData['shareDocIdArr'];
                        $docData = $this->DocumentShare->getEfiledDocByDocId($a);
                        if(isset($docData) && !empty($docData)){
                            foreach ($docData as $k=>$v){
                                $file_url = !empty($v['file_path']) ? $v['file_path'] : NULL;
                                $doc_title = !empty($v['doc_title']) ? $v['doc_title'] : NULL;
                                if(isset($file_url) && !empty($file_url) && isset($getUserlist) && !empty($getUserlist)){
                                        foreach ($getUserlist as $k=>$v) {
                                            $email = !empty($v['emailid']) ? $v['emailid'] : NULL;
                                            $name = !empty($v['first_name']) ? $v['first_name'] : '';
                                            $params = array();
                                            $params['email'] = $email;
                                            $params['file_url'] = $file_url;
                                            $params['subject'] = "Document Shared";
                                            $params['message']='Dear '.$name.' ,please find attachment of share document and click to download <br> <a title="'.$doc_title.'" href="'.base_url($file_url).'">Download</a>';
                                            shareDocToEmail($params);
                                    }
                                }
                            }
                        }
                        $output['success'] = true;
                        $output['message'] = "Document sharing has been successfully";
                    }
                    else{
                        $output['success'] = false;
                        $output['message'] = "Something went wrong,Please try again later!";
                    }
                }
            }
            else{
                $output['success'] = false;
                $output['message'] = "Something went wrong,Please try again later!";
            }

        }
        else{
            $output['success'] = false;
            $output['message'] = "Something went wrong,Please try again later!";
        }
        echo json_encode($output);
        exit(0);
    }
    public function downloadPdf(){
        parse_str($_SERVER['QUERY_STRING'], $_GET);
        $allowedUsers = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowedUsers)){
            redirect('login');
            exit(0);
        }
        if(!empty($_GET['shareDocUniqId']) && !empty($_GET['shareDocUniqId'])){
            $shareDocUniqId= !empty($_GET['shareDocUniqId']) ? rtrim($_GET['shareDocUniqId']) : '';
            $arr = array();
            $arr['shared_uniq_id'] = $shareDocUniqId;
            $recipientDetails = $this->DocumentShare->getUsersDetailsByShareUniqueId($arr);
            $shareDocIdArr = array();
            if(isset($recipientDetails) && !empty($recipientDetails)){
                $shareDocIdArr =array_unique(array_column($recipientDetails,'share_doc_id'));
                $shareDocIdArr =  explode(',',$shareDocIdArr[0]);
            }
            $a = array();
            $a['docIdArr'] = $shareDocIdArr;
            $senderDetails = $this->DocumentShare->getDocDetailsByDocIdArr($a);
            $fromUser = array();
            $fromUser['user_id'] = $_SESSION['login']['id'];
            $fromUser['shared_uniq_id'] = $shareDocUniqId ;
            $fromUserData = $this->DocumentShare->getFromUser($fromUser);

//             echo '<pre>'; print_r($fromUserData);
//              exit;
//            $b = array();
//            $b['table_name'] ='efil.tbl_case_details';
//            $b['whereFieldName'] ='registration_id';
//            $b['whereFieldValue'] = !empty($senderDetails[0]['registration_id']) ? (int)$senderDetails[0]['registration_id'] : NULL;
//            $b['is_deleted'] ='is_deleted';
//            $caseDetails = $this->Common_model->getData($b);
//            $ec = array();
//            $ec['table_name'] ='efil.tbl_lower_court_details';
//            $ec['whereFieldName'] ='registration_id';
//            $ec['whereFieldValue'] = !empty($senderDetails[0]['registration_id']) ? (int)$senderDetails[0]['registration_id'] : NULL;
//            $ec['is_deleted'] ='is_deleted';
//            $courtDetails = $this->Common_model->getData($ec);

         }
        $name = !empty($senderDetails[0]['first_name']) ? $senderDetails[0]['first_name'] : '';
        $aor_code = !empty($senderDetails[0]['aor_code']) ? $senderDetails[0]['aor_code'] : '';
        $file_path = !empty($senderDetails[0]['file_path']) ? $senderDetails[0]['file_path'] : '';
        $doc_title = !empty($senderDetails[0]['doc_title']) ? $senderDetails[0]['doc_title'] : '';
        $created_ip= !empty($recipientDetails[0]['created_ip']) ? $recipientDetails[0]['created_ip'] : '';
        $created_on = !empty($recipientDetails[0]['created_on']) ? date('d/m/Y H:i:s',strtotime($recipientDetails[0]['created_on'])) : '';
        $reciname = !empty($recipientDetails[0]->first_name) ? $recipientDetails[0]->first_name : '';
        $recimoblie_number = !empty($recipientDetails[0]->moblie_number) ? $recipientDetails[0]->moblie_number : '';
        $reciemailid = !empty($recipientDetails[0]->emailid) ? $recipientDetails[0]->emailid : '';
        $reci = !empty($recipientDetails[0]->first_name) ? $recipientDetails[0]->first_name : '';
        $caseData_cause_title =!empty($fromUserData[0]->cause_title) ? $fromUserData[0]->cause_title : '';
        $caseData_sc_diary_num =!empty($fromUserData[0]->diary_no) ? $fromUserData[0]->diary_no : '';
        $caseData_sc_diary_year =!empty($fromUserData[0]->diary_year) ? $fromUserData[0]->diary_year : '';
        $case_no = !empty($fromUserData[0]->case_no) ? $fromUserData[0]->case_no : '';
        $caseDiaryNo ='';
        if(isset($caseData_sc_diary_num) && !empty($caseData_sc_diary_num) && isset($caseData_sc_diary_year) && !empty($caseData_sc_diary_year)){
            $caseDiaryNo = $caseData_sc_diary_num.$caseData_sc_diary_year;
        }
        else if(isset($case_no) && !empty($case_no)){
            $caseDiaryNo = $case_no;
        }
        //create pdf
        $this->load->library('TCPDF');
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetPrintHeader(FALSE);
        $pdf->SetPrintFooter(TRUE);

        $pdf->SetAuthor('Computer Cell, SCI');
        $pdf->SetTitle('Computer Cell, SCI');
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once(dirname(__FILE__) . '/lang/eng.php');
            $pdf->setLanguageArray($l);
        }

        $pdf->SetFont('helvetica', '', 12, '', true);
        $pdf->AddPage();
        ob_end_clean();
        $html='';
        $html .= '<h3 style="text-align:center">Proof Of Service</h3>';
        $html .= '<table border="0">';
        $html .= ' <tr>
                        <td><span style="font-weight: bold">Name:</span></td>
                        <td>'.strtoupper($name) .' ('.$aor_code.')'.'</td>
                    </tr><br>';
        $html .= ' <tr>
                        <td><span style="font-weight: bold">Diary Number / Case Number:</span></td>
                        <td>'.$caseDiaryNo .'</td>
                   </tr><br>';
        $html .= ' <tr>
                        <td><span style="font-weight: bold">Cause Title:</span></td>
                        <td>'.strtoupper($caseData_cause_title) .'</td>
                   </tr><br>';
        $html .= '</table>';
        $html .= '<h3 style="text-align:left">Service by electronic mode of Documents with details given below</h3>';
        $html .= '<table border="1">';
        $html .= ' <tr>
                        <td>&nbsp;<span style="font-weight: bold">Document Title</span></td>
                        <td>&nbsp;<span style="font-weight: bold">Document</span></td>
                        <td>&nbsp;<span style="font-weight: bold">Sub Document</span></td>
                        <td>&nbsp;<span style="font-weight: bold">Filed On Date</span></td>
                    </tr>';
        if(isset($senderDetails) && !empty($senderDetails)){
            foreach ($senderDetails as $k=>$v){
                $doc_id = !empty($v['doc_type_id']) ? (int)$v['doc_type_id'] : NULL;
                $sub_doc_id = !empty($v['sub_doc_type_id']) ? (int)$v['sub_doc_type_id'] : NULL;
                $doc_title = !empty($v['doc_title']) ? $v['doc_title'] : NULL;
                $uploaded_on = !empty($v['uploaded_on']) ? date('d/m/Y H:i:s',strtotime($v['uploaded_on'])) : '';
                $doc= array();
                $shareMainDocDetails='';
                $shareSubDocDetails ='';
                if(isset($doc_id) && !empty($doc_id) && isset($sub_doc_id) && !empty($sub_doc_id)){
                    $doc['doc_id'] = $doc_id;
                    $doc['sub_doc_id'] = $sub_doc_id;
                    $shareSubDocDetails = $this->DocumentShare->getShareDocumentDetails($doc);
                }
                if(isset($doc_id) && !empty($doc_id)){
                    $doc= array();
                    $doc['doc_id'] = $doc_id;
                    $shareMainDocDetails = $this->DocumentShare->getShareDocumentDetails($doc);
                }
                $document = !empty($shareMainDocDetails[0]['docdesc']) ? $shareMainDocDetails[0]['docdesc'] : 'N/A';
                $sub_document = !empty($shareSubDocDetails[0]['docdesc']) ? $shareSubDocDetails[0]['docdesc'] : 'N/A';

                $html .= ' <tr>
                        <td>&nbsp;'.strtoupper($doc_title).'</td>
                         <td>&nbsp;'.$document .'</td>
                         <td>&nbsp;'.$sub_document .'</td>
                          <td>&nbsp;'.$uploaded_on .'</td>
                    </tr>';

            }
        }

        $html .= '</table>';
        $html .= '</table>';
        $html .= '<h3 style="text-align:left">Recipient(s) Details</h3>';
        $html .= '<table border="1">';
        $html .= ' <tr>
                        <td>&nbsp;<span style="font-weight: bold">Name</span></td>
                        <td width="47%">&nbsp;<span style="font-weight: bold">Email</span></td>
                        <td width="20%">&nbsp;<span style="font-weight: bold">Recipient Type</span></td>
                        </tr>';
        if(isset($recipientDetails) && !empty($recipientDetails)){
            foreach ($recipientDetails as $k=>$v){
                $postType = '';
                if(isset($v['ref_m_usertype_id']) && !empty($v['ref_m_usertype_id']) && $v['ref_m_usertype_id'] == USER_ADVOCATE){
                    $postType ='AOR';
                }
                else  if(isset($v['ref_m_usertype_id']) && !empty($v['ref_m_usertype_id']) && $v['ref_m_usertype_id'] == SR_ADVOCATE){
                    $postType ='SR.Advocate';
                }
                else  if(isset($v['ref_m_usertype_id']) && !empty($v['ref_m_usertype_id']) && $v['ref_m_usertype_id'] == ARGUING_COUNSEL){
                    $postType ='Advocate';
                }
                else{
                    $postType ='Other';
                }
                $html .= ' <tr>
                        <td>&nbsp;'.ucfirst($v['name']).'</td>
                        <td width="47%">&nbsp;'.$v['email'].'</td>
                       
                        <td width="20%">&nbsp;'.$postType.'</td>
                        </tr>';
                }
            }
        $html .= '</table><br><br><br>';
        $html .= '<table border="0">';
        $html .= ' <tr>
                        <td>&nbsp;<span style="font-weight: bold">Sent On Date:</span>&nbsp;'.$created_on.'</td>
                        <td>&nbsp;<span style="font-weight: bold">Sent From IP Address:</span>&nbsp;'.$created_ip.'</td>
                    </tr><br>';
        $html .= '</table><br><br>';
        $html .= '<h5 style="text-align:left;">Service of summons, notice, IA, MA or documents as per details mentioned above has been effected as per
                    above mentioned recipient details on Advocate-on-Record appearing for party(ies) in the case above or
                    party in-person, through Email/electronic mode at address in terms of Order IV Rule 12 SCR, 2013. (Proof
                    of service is auto generated)</h5>';
       $pdf->writeHTML($html, true, false, false, false, '');
        $pdf->Output('test.pdf', 'I');

    }



}