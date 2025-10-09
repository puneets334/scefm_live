<?php
namespace App\Controllers;
class CitationController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        if (!isset($_SESSION['login']) && empty($_SESSION['login'])) {
            redirect('login');
        }else{
            is_user_status();
        }
        $this->load->model('citation/Citation_model');
        $this->load->library('webservices/efiling_webservices');
        $this->load->model('common/Common_model');
        $this->load->helper('functions_helper');
        $this->load->model('miscellaneous_docs/Get_details_model');
    }

    public function index()
    {
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            redirect('login');
            exit(0);
        }
        $data['app_name'] = "Citation";
        /*Change of Code started on 21 September 2020*/
        $type = 'list-authors';
        $arr = array('name' => '');
        $param = base64_encode(json_encode($arr));
        $data['auth_name'] = $this->efiling_webservices->getBookCatalogueDetails($type, rawurlencode($param));

        if (!empty($_SESSION['efiling_details']['diary_no']) && $_SESSION['efiling_details']['diary_no']!=null){
            $this->load->model('casesearch/Casesearch_model');
            $diary_already_exists = $this->Casesearch_model->get_diary_details($_SESSION['efiling_details']['diary_no'],$_SESSION['efiling_details']['diary_year']);
            if (isset($diary_already_exists) && !empty($diary_already_exists)) {$_SESSION['tbl_sci_case_id'] = $diary_already_exists[0]->id;}
             $diaryId=$_SESSION['efiling_details']['diary_no'].$_SESSION['efiling_details']['diary_year'];
        }else{$diaryId = 0;}

        $advocate_list = $this->efiling_webservices->get_advocate_list($diaryId);
        $data['advocate_list'] = $advocate_list['advocates_list'];
        //$data['existing_citation']=$this->Citation_model->get_existing_citations($_SESSION['tbl_sci_case_id']);
        $data['existing_citation'] = $this->getExistingCitation();
        /*End of change of Code*/
        $this->load->view('templates/header');
        $this->load->view('citation/citation_details', $data);
        $this->load->view('templates/footer');
    }

    /*Start of Code*/
    public function uploadOrder()
    {
        $this->form_validation->set_rules('doc_title', 'Document Title', 'required|trim|min_length[5]|max_length[75]|validate_alpha_numeric_space_dot_hyphen');
        $this->form_validation->set_rules('pub_place', 'Place of Issuance', 'required|trim|min_length[5]|max_length[75]|validate_alpha_numeric_space_dot_hyphen');
        if (!$this->form_validation->run()) {
            echo '3@@@';
            echo form_error('doc_title') . form_error('pub_place');
            exit(0);
        }
        if ($msg = isValidPDF('pdfDocFile')) {
            echo '1@@@' . htmlentities($msg, ENT_QUOTES);
            exit(0);
        }
        $registration_id = $_SESSION['efiling_details']['registration_id'];
        $ref_m_efiled_type_id = $_SESSION['efiling_details']['ref_m_efiled_type_id'];
        $doc_title = strtoupper(escape_data($this->input->post("doc_title")));
        $doc_hash_value = hash_file('sha256', $_FILES['pdfDocFile']['tmp_name']);
        $uploaded_on = date('Y-m-d H:i:s');
        $sub_created_by = 0;
        $uploaded_by = $_SESSION['login']['id'];
        /* Upload order pdf 10/12/2020 start*/
        $establishment_code = $_SESSION['estab_details']['estab_code'];
        $registration_id = $_SESSION['efiling_details']['registration_id'];
        $est_dir = 'uploaded_docs/' . $establishment_code;
        if (!is_dir($est_dir)) {
            $uold = umask(0);
            if (mkdir('uploaded_docs/' . $establishment_code, 0777, true)) {
                $html = '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
                write_file($est_dir . '/index.html', $html);
            }
            umask($uold);
        }
        $efil_num_dir = 'uploaded_docs/' . $establishment_code . '/' . $_SESSION['efiling_details']['efiling_no'];
        if (!is_dir($efil_num_dir)) {
            $uold = umask(0);
            if (mkdir($efil_num_dir, 0777, true)) {
                $html = '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
                write_file($efil_num_dir . '/index.html', $html);
            }
            umask($uold);
        }
        $file_uploaded_dir = 'uploaded_docs/' . $establishment_code . '/' . $_SESSION['efiling_details']['efiling_no'] . '/docs/';
        if (!is_dir($file_uploaded_dir)) {
            $uold = umask(0);
            if (mkdir('uploaded_docs/' . $establishment_code . '/' . $_SESSION['efiling_details']['efiling_no'] . '/docs/', 0777, true)) {
                $html = '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
                write_file($file_uploaded_dir . '/index.html', $html);
            }
            umask($uold);
        }
        //-----START :Modify File Name-----//
        $filename = $_POST['doc_title'];
        $filename = str_replace(' ', '_', $filename); // Replaces all spaces with underscore.
        $filename = preg_replace('/[^A-Za-z0-9\_]/', '', $filename); // Removes special chars.
        $filename = $_SESSION['efiling_details']['efiling_no'] . '_' . $filename . '.pdf';
        $uploaded = move_uploaded_file($_FILES['pdfDocFile']['tmp_name'], "$file_uploaded_dir/$filename");
        $file_path = getcwd() . '/' . $file_uploaded_dir . $filename;
        $page_no = (int)@exec('pdfinfo ' . $file_path . ' | awk \'/Pages/ {print $2}\'', $output);//poppler-utils variant
        /* Upload order pdf 10/12/2020 end*/
        $dataArr = array(
            'registration_id' => $registration_id,
            'efiled_type_id' => $ref_m_efiled_type_id,
            'file_size' => $_FILES['pdfDocFile']['size'],
            'file_type' => $_FILES['pdfDocFile']['type'],
            'doc_title' => $doc_title,
            'doc_hashed_value' => $doc_hash_value,
            'uploaded_by' => $uploaded_by,
            'uploaded_on' => $uploaded_on,
            'upload_ip_address' => getClientIP(),
            'sub_created_by' => $sub_created_by,
            'page_no' => $page_no,
            'file_name' => $filename,
            'file_path' => $file_uploaded_dir . $filename
        );
        $insertId = $this->Citation_model->insertData('efil.tbl_uploaded_pdfs',$dataArr);
        //$insertId = true;
        if(isset($insertId) && !empty($insertId)){
            $dataToOthers = array(
                'tbl_sci_cases_id' => $_SESSION['tbl_sci_case_id'],
                'efiling_no' => $_SESSION['efiling_details']['efiling_no'],
                'publication_place' => !empty($_POST['pub_place']) ? trim($_POST['pub_place']) : NULL,
                'orderdate'=> !empty($_POST['publishing_date']) ? date('Y-m-d H:i:s',strtotime(trim($_POST['publishing_date']))) : NULL,
                'listing_date'=> $_SESSION['listing_details']->next_dt,
                'subject' => NULL,
                'search_by' => 'O',
                'created_at'=>date('Y-m-d H:i:s'),
                'ip_address' => get_client_ip(),
                'doc_id'=>$insertId
            );
            $insertCitation = $this->Citation_model->insertData('efil.tbl_citation',$dataToOthers);
            $presentStage=$this->Citation_model->getStage($registration_id);
            if($presentStage[0]['stage_id']==1)
            $this->Citation_model->changeStage($registration_id,CITATION_E_FILED);
            $existing_citation = $this->getExistingCitation();
//            echo '<pre>' ; print_r($existing_citation);
//            exit;
            echo json_encode($existing_citation);
        }
    }


    public function searchByBookWebservice()
    {
        // $temp = $_SESSION['efiling_details']['diary_no'].' - '.$_SESSION['efiling_details']['diary_year'];
        // echo json_encode($temp);
        //echo json_encode($_SESSION);
        // echo $_SESSION['listing_details']['diary_no']; //diary_number with year
        // return;
        extract($_POST);
        if ($ddlBook == 1) {
            $type = 'author';
            $arr = array('author_code' => array($auth_name));

        } elseif ($ddlBook == 2) {
            $type = 'author';
            $arr = array('author_code' => array($auth_code_val));

        } elseif ($ddlBook == 3) {
            $type = 'title';
            $arr = array('title' => $book_title_val);

        } elseif ($ddlBook == 4) {
            $type = 'subject';
            $arr = array('subject' => $book_subject_val);

        } elseif ($ddlBook == 5) {
            $type = 'publisher';
            $arr = array('publisher' => $book_publisher_val);
        }

        if ($arr) {

            $param = base64_encode(json_encode($arr));
            $data = $this->efiling_webservices->getBookCatalogueDetails($type, $param);
            echo json_encode($data);
        }
    }


    public function addRecordInCitationBook()
    {

        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);

        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            redirect('login');
            exit(0);
        }
        // var_dump($_POST);
        extract($_POST);

        $data = json_decode($webServiceResponseBook);

        if ($ddlBook == 3 || $ddlBook == 4) {
            $author_name = trim($data->firstname) . ' ' . trim($data->lastname);
        } else {
            $author_name = '';
        }

        $dataToInsert = array('tbl_sci_cases_id' => $_SESSION['tbl_sci_case_id'],
            'efiling_no' => $_SESSION['efiling_details']['efiling_no'],
            'updated_by' => $_SESSION['login']['id'],
            'listing_date' => $_SESSION['listing_details']->next_dt,
            'book_title' => trim($data->title),
            'author_name' => trim($author_name),
            'publisher_name' => trim($data->pubnm),
            'publication_year' => trim($data->pubyr),
            'publication_place' => trim($data->pubpl),
            'edition_no' => trim($data->edno),
            'isbn_no' => trim($data->isbn),
            'subject' => trim($data->sub1),
            'search_by' => 'B',
            'suplis_response' => $webServiceResponseBook,
            'ip_address' => get_client_ip()
        );
        // var_dump($dataToInsert);
        $registration_id = $_SESSION['efiling_details']['registration_id'];
        $result = $this->Citation_model->insertRecordBook($dataToInsert, 'efil.tbl_citation');
        $presentStage=$this->Citation_model->getStage($registration_id);
        if($presentStage[0]['stage_id']==1)
        $this->Citation_model->changeStage($registration_id,CITATION_E_FILED);
        $existing_citation = $this->getExistingCitation();
        echo json_encode($existing_citation);
        //var_dump($result);
    }

    public function get_advocate_list()
    {
        $diary_no = $_SESSION['listing_details']->diary_no;
        $data['advocate_list'] = $this->efiling_webservices->get_advocate_list($diary_no);
        echo json_encode($data);

    }

    /*End of Code*/


    public function searchInWebservice()
    {
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);

        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            redirect('login');
            exit(0);
        }
        //echo "Value is: ".getJournalName(3);
        $ddlSuppl = 'N';
        extract($_POST);
        //echo json_encode($_POST);

        /*Start of Change of Code*/
        $arr = array('journal' => $ddlJournal, 'year' => $ddlYear, 'volume' => $ddlVolume, 'supply_flag' => $ddlSuppl, 'p_no' => $p_no);
        $param = base64_encode(json_encode($arr));
        $data = $this->efiling_webservices->getCitationDetailFromSuplis(3, rawurlencode($param));
        //$data='[{"caseno":"57002","petname":"STATE OF RAJASTHAN","resname":"SHERA RAM @ VISHNU DUTTA","doj":"2011\/12\/01","jd_type":"J"}]';
        //$data='[]';

        echo json_encode($data);
    }

    public function addRecordInCitation()
    {
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);

        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            redirect('login');
            exit(0);
        }
        extract($_POST);
        $dataToInsert = array('tbl_sci_cases_id' => $_SESSION['tbl_sci_case_id'],
            'efiling_no' => $_SESSION['efiling_details']['efiling_no'],
            'updated_by' => $_SESSION['login']['id'],
            'listing_date' => $_SESSION['listing_details']->next_dt,
            'journal' => $ddlJournal,
            'journal_year' => $ddlYear,
            'volume' => $ddlVolume,
            'suppl' => $ddlSuppl,
            'page_no' => $p_no,
            'suplis_response' => $webServiceResponse,
            'is_in_suplis' => $isInSuplis,
            'search_by' => 'J',
            'ip_address' => get_client_ip()
        );
        $registration_id = $_SESSION['efiling_details']['registration_id'];
        $this->Citation_model->insertRecord($dataToInsert, 'efil.tbl_citation');
        $presentStage=$this->Citation_model->getStage($registration_id);
        if($presentStage[0]['stage_id']==1)
        $this->Citation_model->changeStage($registration_id,CITATION_E_FILED);
        $existing_citation = $this->getExistingCitation();
        echo json_encode($existing_citation);
    }

    function getExistingCitation()
    {
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);

        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            redirect('login');
            exit(0);
        }
        $dataarray = $this->Citation_model->get_existing_citations($_SESSION['tbl_sci_case_id']);
        $uploadedDoc = $this->Citation_model->getUploadedDoc($_SESSION['tbl_sci_case_id']);
        $docArr = array();
        if(isset($uploadedDoc) && !empty($uploadedDoc)){
            foreach ($uploadedDoc as $k=>$v){
                $docArr[$v['doc_id']] = $v['file_path'];
            }
        }
        foreach ($dataarray as $index => $data) {
            if ($data['listing_date'] == $_SESSION['listing_details']->next_dt) {
                $data['for_required_date'] = 0;
            } else {
                $data['for_required_date'] = 1;
            }
            if(isset($docArr) && !empty($docArr)){
                if (array_key_exists($data['doc_id'],$docArr)) {
                    $data['file_path'] = $docArr[$data['doc_id']];
                }
                else
                    $data['file_path'] = NULL;
            }
            $data['listing_date'] = date('d-m-Y', strtotime($data['listing_date']));
            $data['journal'] = getJournalName($data['journal']);
            $dataarray[$index] = $data;
        }
        //var_dump($dataarray);
        return $dataarray;
    }


    function removeCitation()
    {
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);

        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            redirect('login');
            exit(0);
        }
        extract($_POST);
        $data_array = array(
            'is_deleted' => true,
            'deleted_on' => date('Y-m-d H:i:s')
        );
        $registration_id = $_SESSION['efiling_details']['registration_id'];
        $this->Citation_model->updateRecord($data_array, 'efil.tbl_citation', $cid);
        $existing_citation = $this->getExistingCitation();
        if($existing_citation ==null ||$existing_citation =='')
            $this->Citation_model->changeStage($registration_id,Draft_Stage);
        echo json_encode($existing_citation);
    }

    function copyCitation()
    {
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);

        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            redirect('login');
            exit(0);
        }
        extract($_POST);
        $this->Citation_model->doCopyCitation($cid, $_SESSION['listing_details']->next_dt);
        $existing_citation = $this->getExistingCitation();
        echo json_encode($existing_citation);
    }

    //Library Admin Code Starts
    public function libraryAdminDashBoard()
    {
        $users_array = array(USER_LIBRARY);

        if (in_array($_SESSION['login']['ref_m_usertype_id'], $users_array)) {
            $reference_count = $this->Citation_model->getCitationCountForLibrary();
            $data['reference_count'] = $reference_count;

            $reference_count_today = $this->Citation_model->getCitationCountForLibrarytoday();
            $data['reference_count_today'] = $reference_count_today;
            //print_r($data['reference_count_today']);

            $reference_count_future = $this->Citation_model->getCitationCountForLibraryfuture();
            $data['reference_count_future'] = $reference_count_future;

            $listing_date = 'today_dt';
            //$listing_date = $reference_count[0]['listing_date'];
            //$data['listing_date'] = $listing_date;
            $data['reference_details'] = $this->fetchReferenceDetails($listing_date);
            //$data['tabs_heading'] = "Citation for Date : " . date('d-m-Y', strtotime($listing_date));
            $this->load->view('templates/library_header');
            $this->load->view('libraryDashboard/library_dashboard_view', $data);
            $this->load->view('templates/footer');
        } else {
            redirect('login');
            exit(0);
        }
    }

    public function getReferenceDetails()
    {
        //$listing_date=trim($_GET['listing_date']);
        //extract($_GET['listing_date']);
        extract($_POST);
       // echo $listing_date;exit;
        $reference_details = $this->fetchReferenceDetails($listing_date);
        if(isset($reference_details) && !empty($reference_details)){
            $docIdArr = array_column($reference_details,'doc_id');
            if(isset($docIdArr) && !empty($docIdArr)){
                $listingDate = date('Y-m-d',strtotime($listing_date));
                $pdfData = $this->Citation_model->getPdfDateAndDocIdData($docIdArr,$listing_date);
                $docArr = array();
                if(isset($pdfData) && !empty($pdfData)){
                    foreach ($pdfData as $k=>$v){
                        $docArr[$v['doc_id']] = $v['file_path'];
                    }
                }
                if(isset($docArr) && !empty($docArr) && is_array($docArr)){
                    foreach ($reference_details as $in=>$arr){
                        if(array_key_exists($arr['doc_id'],$docArr)){
                            $arr['file_path'] = $docArr[$arr['doc_id']];
                        }
                        else{
                            $arr['file_path'] = NULL;
                        }
                        $reference_details[$in] = $arr;
                    }
                }
            }
        }
        echo json_encode($reference_details);
    }

    //function fetchReferenceDetails($date=null)
    function fetchReferenceDetails($date)
    {
        $data= $this->Citation_model->getCitationDetailsForLibrary($date);
        $dataDB=json_encode($data);
        $dataDBFinal=json_decode($dataDB);
        $dataDBFinal1 = (object)$dataDBFinal;
        $dataDBFinal11['customers'] = (object)$dataDBFinal1;
        $vvv = (object)$dataDBFinal11;
        return json_encode($vvv);

    }
    function scitestapi()
    {
        $date=trim($_GET['chose_date']);
        //$date=trim($_GET['listing_date']);
        $data= $this->Citation_model->getCitationDetailsForLibrary($date);
        $dataDB=json_encode($data);
        $dataDBFinal=json_decode($dataDB);
        $dataDBFinal1 = (object)$dataDBFinal;
        $dataDBFinal11['customers'] = (object)$dataDBFinal1;
        $vvv = (object)$dataDBFinal11;
        echo json_encode($vvv);
    }
    //ZXXXXXXXXXXXXXXXXXXXXXXX
    function share_citation_aor()
    {
        $citation_id = $this->input->post('c_id');
        $email_arr=array();
        foreach($citation_id as $val_c_id){
            $email=$val_c_id;
           // echo $email ;
            $email_id=explode("/",$email);
           // echo $email_id[1];
            if($email_id[1]!=''){
                $email_arr[]=$email_id[1];
               // array_push($email_arr,array("email_id"=>$email_id[1]));
            }
        }
        /*print_r($email_arr);


       // print_r($citation_id);
        exit();*/
        $data['existing_citation'] = $this->getExistingCitation();

        $citaion_data_arr=array();
        foreach($data['existing_citation'] as $val_existing){
            $listing_date=$val_existing['listing_date'];
            //$src_by=$val_existing['search_by'];

            if($val_existing['search_by']=='J'){
                $page_no=$val_existing['page_no'];
                $search_by=$val_existing['search_by'];
                $volume=$val_existing['volume'];
                $journal_year=$val_existing['journal_year'];
                $journal=$val_existing['journal'];
            }else{
                $page_no=$val_existing['page_no'];
                $search_by=$val_existing['search_by'];
                $volume=$val_existing['volume'];
                $journal_year=$val_existing['journal_year'];
                $journal=$val_existing['journal'];
            }
            $suplis_response=json_decode($val_existing['suplis_response']);
            $title=$suplis_response->title;
            $pubnm=$suplis_response->pubnm;
            $pubyr=$suplis_response->pubyr;
            $subject=$suplis_response->sub1;

            array_push($citaion_data_arr,array("listing_date"=>$listing_date,"title"=>$title,"pubnm"=>$pubnm,"pubyr"=>$pubyr,
                "sub"=>$subject,"page_no"=>$page_no,"search_by"=>$search_by,"volume"=>$volume,"journal_year"=>$journal_year,"journal"=>$journal));

        }
        $this->session->set_userdata('citation_data', $citaion_data_arr);
       /* print_r($citaion_data_arr);
        exit();*/

        echo json_encode($data);
    }//End of function share_citation_aor ..

    function share_email_citation_msg()
    {
        $citation_id = $this->input->post('c_id');
        $email_arr=array();
        foreach($citation_id as $val_c_id){
            $email=$val_c_id;
            // echo $email ;
            $email_id=explode("/",$email);
            // echo $email_id[1];
            if($email_id[1]!=''){
                $email_arr[]=$email_id[1];
                // array_push($email_arr,array("email_id"=>$email_id[1]));
            }
        }

        // print_r($citation_id);
        //exit();
        $data['existing_citation'] = $this->getExistingCitation();

        $citaion_data_arr=array();
        foreach($data['existing_citation'] as $val_existing){
            $listing_date=$val_existing['listing_date'];
            //$src_by=$val_existing['search_by'];

            if($val_existing['search_by']=='J'){
                $page_no=$val_existing['page_no'];
                $search_by=$val_existing['search_by'];
                $volume=$val_existing['volume'];
                $journal_year=$val_existing['journal_year'];
                $journal=$val_existing['journal'];
            }else{
                $page_no=$val_existing['page_no'];
                $search_by=$val_existing['search_by'];
                $volume=$val_existing['volume'];
                $journal_year=$val_existing['journal_year'];
                $journal=$val_existing['journal'];
            }
            $suplis_response=json_decode($val_existing['suplis_response']);
            $title=$suplis_response->title;
            $pubnm=$suplis_response->pubnm;
            $pubyr=$suplis_response->pubyr;
            $subject=$suplis_response->sub1;

            array_push($citaion_data_arr,array("listing_date"=>$listing_date,"title"=>$title,"pubnm"=>$pubnm,"pubyr"=>$pubyr,
                "sub"=>$subject,"page_no"=>$page_no,"search_by"=>$search_by,"volume"=>$volume,"journal_year"=>$journal_year,"journal"=>$journal));

        }
        $this->session->set_userdata('citation_data', $citaion_data_arr);


        //XXXXXXXXXXXXXXXXXXXXXXXXXXXXX
        $registration_id = $_SESSION['efiling_details']['registration_id'];
        $data['case_details'] = $this->Get_details_model->get_case_details($registration_id);

        //print_r($data['case_details']);
        foreach($data['case_details'] as $val){
           $diary_no=$val['diary_no'];
           $diary_yr=$val['diary_year'];
           $registration_no=$val['reg_no_display'];
           $cause_title=$val['cause_title'];

          //  echo 'diaryNo='. $diary_no . 'dairyYr='. $diary_yr . 'cause='.$cause_title . 'reg=' . $registration_no  ;
        }


        $this->session->set_userdata('case_data', $data['case_details']);


        $the_session='Citation';

        $this->session->set_userdata('citation_mail', $the_session);

        //$something = $this->input->post('message');
        $something = 'Citation_dtl';
       /* $email_array[0]='rounakmishra691@gmail.com';
        $email_array[1]='sca.aktripathi@sci.nic.in';*/
        //$email_id='rounakmishra691@gmail.com';
        $email_id=$email_arr;
        //$email_id=$email_array;
        /*print_r($email_id);
        exit();*/

        $subject='Citation in  '. $registration_no .' @ ' . $diary_no .'/'. $diary_yr .'  for Listing dated ' . $_SESSION['citation_data'][0]['listing_date'] ;
        $sent_mail = relay_mail_api($email_id,$subject,$something);

        if($sent_mail=='success'){
            //$this->session->set_flashdata('msg', 'Mail Sent Successfuly');
            $search_by=$_SESSION['citation_data'][1]['search_by'];
            if($search_by=='J'){
                $page_no=$_SESSION['citation_data'][1]['page_no'];
                $volume=$_SESSION['citation_data'][1]['volume'];
                $journal_year=$_SESSION['citation_data'][1]['journal_year'];
                $journal=$_SESSION['citation_data'][1]['journal'];

            }else{
                $page_no=$_SESSION['citation_data'][0]['page_no'];
                $volume=$_SESSION['citation_data'][0]['volume'];
                $journal_year=$_SESSION['citation_data'][0]['journal_year'];
                $journal=$_SESSION['citation_data'][0]['journal'];
            }

            $insert_data_info= array(
                'title'=>$_SESSION['citation_data'][0]['title'],'pubname'=>
                    $_SESSION['citation_data'][0]['pubnm'],'pubyear'=>$_SESSION['citation_data'][0]['pubyr'],'subject'=>$_SESSION['citation_data'][0]['sub'],
                'listing_date'=>$_SESSION['citation_data'][0]['listing_date'],'page_no'=>$page_no,'volume'=>$volume,'journal_year'=>$journal_year,'journal'=>$journal);

            //print_r($insert_data_info);

            $val_data_json=json_encode($insert_data_info);
            $email_log=json_encode($email_id);

            $dataToInsert = array(
                'dairy_no' => $diary_no,
                'efilling_no' => $_SESSION['efiling_details']['efiling_no'],
                'given_by' => $_SESSION['login']['id'],
                'given_to_email'=>$email_log,
                'citation_dtl'=>$val_data_json

            );

            $citation_log_data = $this->Citation_model->citation_email_log_data($dataToInsert, 'efil.tbl_citation_share_dtl');

            if($citation_log_data==1){
                $this->session->unset_userdata('citation_data');
                $this->session->unset_userdata('citation_mail');
                $this->session->unset_userdata('case_data');
                echo "Mail Sent Successfuly";

            }
        }else{
            //$this->session->set_flashdata('msg', '! Mail Not Sent');
            echo "! Mail Not Sent";
        }



    }//End of function share_email_citation_msg ..


}