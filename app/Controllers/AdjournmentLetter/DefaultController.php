<?php
namespace App\Controllers\AdjournmentLetter;

use App\Controllers\BaseController;
use App\Models\AdjournmentLetter\AdjournmentModel;
use App\Libraries\webservices\Efiling_webservices;

class DefaultController extends BaseController {

    protected $adjournment_model;
    protected $efiling_webservices;

    public function __construct() {
        parent::__construct();
        if(empty(getSessionData('login'))){
            return response()->redirect(base_url('/')); 
        } else{
            is_user_status();
        }
        $this->adjournment_model = new AdjournmentModel();
        $this->efiling_webservices = new Efiling_webservices();
    }

    function check_login() {
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK,JAIL_SUPERINTENDENT,USER_REGISTRAR_ACTION,USER_REGISTRAR_VIEW);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            echo '1@@@Invalid attempt.';
            exit(0);
        }
    }

    public function index() {
        $this->check_login();
        $user_id=$this->session->userdata['login']['id'];
        $advocate_id = $this->session->userdata['login']['adv_sci_bar_id'];
        /*****start-causelist table*****/
        $fgc_context=array(
            'http' => array(
                'user_agent' => 'Mozilla',
            ),
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );
        $schedule_request_params = ['responseFormat' => 'CASE_WISE_FLATTENED_WITH_ALL_INFO', 'advocates'=> [$advocate_id], 'forDate' => 'all', 'ifSkipDigitizedCasesStageComputation' => true];
        list($scheduled_cases) = (array)@json_decode(@file_get_contents(API_CAUSELIST_URI . '?'.http_build_query($schedule_request_params), false, stream_context_create($fgc_context)));
        $existing_requests=$this->adjournment_model->getAdjournmentRequests($user_id);
        //Others Adjournment Request Started
        $recent_documents_str_advocate_others = file_get_contents(ICMIS_SERVICE_URL . '/ConsumedData/getAdvocateAllCases/?advocateId='.$advocate_id.'&onlyDiary=true');
        $recent_documents_advocate_others = json_decode($recent_documents_str_advocate_others);
        $adjournment_by_others_data=$recent_documents_advocate_others->data;
        $others_all_diaryId_data=array_column($adjournment_by_others_data,'diaryId');
        $existing_requests_others=array();
        if (count($others_all_diaryId_data) > 0 && $others_all_diaryId_data !=null && !empty($others_all_diaryId_data)){
            $existing_requests_others = $this->adjournment_model->getAdjournmentRequests($this->session->userdata['login']['id'],$others_all_diaryId_data,"",true);
        }
        $this->render('responsive_variant.adjournment_letter.index', compact('scheduled_cases','existing_requests','existing_requests_others'));
    }

    public function doAdjournmentRequest($data) {
        $this->check_login();
        $request_data=url_decryption($data);
        $parameters=explode('#',$request_data);
        $data_array=array(
            'diary_number'=>$parameters[0],
            'case_number'=>$parameters[1],
            'listed_date'=>$parameters[2],
            'court_number'=>$parameters[3],
            'item_number'=>$parameters[4],
            'already_moved'=>false
        );
        $letter_details=$this->adjournment_model->getAdjournmentRequests($this->session->userdata['login']['id'],$parameters[0],$parameters[2]);
        if(count($letter_details)>0){
            $data_array['already_moved']=true;
        }
        $_SESSION['inputdata']=$data_array;
        $this->render('responsive_variant.adjournment_letter.move_adjournment', compact('data_array'));
    }

    public function saveAdjournmentRequest() {
        $this->check_login();
        $data_array=$_SESSION['inputdata'];
        //TODO:: Check if already adjournment letter moved
        $letter_details=$this->adjournment_model->getAdjournmentRequests($this->session->userdata['login']['id'],$data_array['diary_number'],$data_array['listed_date']);
        if(count($letter_details)>0){
            echo '1@@@' . htmlentities('Transaction failed ! Adjournment request already initiated for this case.', ENT_QUOTES);
            exit(0);
        }
        if ($msg = isValidPDF('pdfDocFile')) {
            echo '1@@@' . htmlentities($msg, ENT_QUOTES);
            exit(0);
        }
        $data_array['doc_hashed_value'] = hash_file('sha256', $_FILES['pdfDocFile']['tmp_name']);
        $data_array['created_by'] = $_SESSION['login']['id'];
        $result = $this->adjournment_model->upload_pdfs($data_array, $_FILES['pdfDocFile']['tmp_name']);
        if ($result == 'trans_success') {
            echo '2@@@' . htmlentities('Document & Details saved successfully', ENT_QUOTES);
            exit(0);
        } elseif ($result == 'trans_failed') {
            echo '1@@@' . htmlentities('Transaction failed ! Please Try Again.', ENT_QUOTES);
            exit(0);
        } elseif ($result == 'upload_fail') {
            echo '1@@@' . htmlentities('Document uploading failed due to some technical reason.', ENT_QUOTES);
            exit(0);
        } else {
            echo '1@@@' . htmlentities('Document Uploading Failed ! Please Try again.', ENT_QUOTES);
            exit(0);
        }
    }

    public function showFile($id) {
        $this->check_login();
        $doc_id=url_decryption($id);
        $doc_details = $this->adjournment_model->get_uploaded_pdf_file($doc_id);
        $file_partial_path = $doc_details[0]['file_path'];
        $file_name = $doc_details[0]['file_name'];
        $doc_title = $file_name.'.pdf';
        if (file_exists($file_partial_path)) {
            header("Content-Type: application/pdf");
            header("Content-Disposition:inline;filename = $doc_title");
            header('Content-Transfer-Encoding: binary');
            header('Accept-Ranges: bytes');
            @readfile($file_partial_path);
            echo $file_name;
        } else {
            echo "File does not exists !";
            exit(0);
        }
    }

    public function showFile_Others($id) {
        $this->check_login();
        $doc_id=url_decryption($id);
        $doc_details = $this->adjournment_model->get_uploaded_pdf_file_Others($doc_id);
        $file_partial_path = $doc_details[0]['file_path'];
        $file_name = $doc_details[0]['file_name'];
        $doc_title = $file_name.'.pdf';
        if (file_exists($file_partial_path)) {
            header("Content-Type: application/pdf");
            header("Content-Disposition:inline;filename = $doc_title");
            header('Content-Transfer-Encoding: binary');
            header('Accept-Ranges: bytes');
            @readfile($file_partial_path);
            echo $file_name;
        } else {
            echo "File does not exists !";
            exit(0);
        }
    }

    public function saveAdjournmentRequestOthers() {
        $this->check_login();
        $case_number = $_POST['case_number'];
        $item_number = $_POST['item_number'];
        $court_number = $_POST['court_number'];
        $created_at = $_POST['created_at'];
        $diary_no = $_POST['diary_number'];
        $listed_date = $_POST['listed_date'];
        $data_array['created_by'] = $_SESSION['login']['id'];
        $data_array['diary_number'] = $diary_no;
        $data_array['listed_date'] = $listed_date;
        $data_array['tbl_adjournment_details_id'] = $_POST['adjournment_details_id'];
        $data_array['consent'] = $_POST['consent_type'];
        //TODO:: Check if already adjournment letter Others Saved
        $letter_details = $this->adjournment_model->get_uploaded_pdf_file_Others($data_array['tbl_adjournment_details_id'],$_SESSION['login']['id']);
        if ($letter_details) {
            echo '1@@@' . htmlentities('Transaction failed ! Adjournment request already initiated for this case.', ENT_QUOTES);
            exit(0);
        }
        if (!empty($data_array['consent']) && $data_array['consent']=='O' && !empty($_FILES['pdfDocFile']['name']) && $_FILES['pdfDocFile']['name'] != null){
            if ($msg = isValidPDF('pdfDocFile')) {
                echo '1@@@' . htmlentities($msg, ENT_QUOTES);
                exit(0);
            }
            $data_array['doc_hashed_value'] = hash_file('sha256', $_FILES['pdfDocFile']['tmp_name']);
        }
        $result = $this->adjournment_model->upload_pdfs_Others($data_array, $_FILES['pdfDocFile']['tmp_name']);
        $Getresult=explode('/',$result);
        $trans_success=$Getresult[0];
        $insert_id=$Getresult[1];
        $created_by_name=ucwords(strtolower($this->session->userdata['login']['first_name']));
        $to_email_Self= $created_by_emailid=trim(strtolower($this->session->userdata['login']['emailid']));
        $created_date=date("Y-m-d H:i:s");
        $created_by_details=$created_by_name.' ('.$created_date.')'.'<br>';
        if ($trans_success == 'trans_success') {
            if(!empty($data_array['consent']) && $data_array['consent']=='O'){
                $redirect_url = base_url('adjournment_letter/DefaultController/showFile_Others/' . url_encryption($insert_id));
                $data_html = $created_by_details.' Have Objection <a target="_blank" href="' . $redirect_url . '"><span data-uk-icon="icon: file-pdf"></span></a>';
            } else{
                $data_html=$created_by_details.'Concede';
            }
            $without_name='adjournment';
            if (!empty($diary_no) && $diary_no !=null) {
                $diary_number_year=substr($diary_no,0,-4).'/'.substr($diary_no,-4);
                //For Self:
                $subject_Self="Adjournment Letter in Diary No.".$diary_number_year." ";
                $message_Self="Dear ".$created_by_name." ,Your request for adjournment of Case No. ".$case_number.", listed on ".$listed_date." in Court Number : ".$court_number." as Item Number ".$item_number." submitted successfully.";
                send_mail_msg($to_email_Self, $subject_Self, $message_Self,$without_name);
                $get_advocate_list_data = $this->efiling_webservices->get_advocate_list($diary_no);
                $get_advocate_list=(object)$get_advocate_list_data;
                if (!empty($get_advocate_list) && $get_advocate_list !=null && count($get_advocate_list) > 0) {
                    foreach ($get_advocate_list->advocates_list as $key => $value) {
                        $name_others = ucwords(strtolower($value['name']));
                        $email = $value['email'];
                        $mobile = $value['mobile'];
                        $to_email=$email;
                        $subject="Adjournment Letter in Diary No.".$diary_number_year." ";
                        $message="Dear ".$name_others." ,Request for adjournment in Case No. ".$case_number.", listed on ".$listed_date." in Court Number : ".$court_number." as Item Number ".$item_number." submitted by ".$created_by_name." on ".$created_at.".You may give your consent by logging in SC-EFM portal.";
                        send_mail_msg($to_email, $subject, $message,$without_name);

                    }
                }
            }
            echo '2@@@' . htmlentities('Document & Details saved successfully', ENT_QUOTES).'@@@'.$data_html;
            exit(0);
        } elseif ($result == 'trans_failed') {
            echo '1@@@' . htmlentities('Transaction failed ! Please Try Again.', ENT_QUOTES);
            exit(0);
        } elseif ($result == 'upload_fail') {
            echo '1@@@' . htmlentities('Document uploading failed due to some technical reason.', ENT_QUOTES);
            exit(0);
        } else {
            echo '1@@@' . htmlentities('Document Uploading Failed ! Please Try again.', ENT_QUOTES);
            exit(0);
        }
    }

}