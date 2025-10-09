<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class DefaultController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!isset($_SESSION['login']) && empty($_SESSION['login'])) {
            redirect('login');
        }else{
            is_user_status();
        }
        //$this->load->model('dashboard/Dashboard_model');
        $this->load->model('adjournment_letter/adjournment_model');
        $this->load->model('registrarActionDashboard/RegistrarActionDashboardModel');
        //$this->load->model('profile/Profile_model');
        unset($_SESSION['efiling_details']);
        
        unset($_SESSION['estab_details']);
        unset($_SESSION['case_table_ids']);
        unset($_SESSION['search_case_data']);
        unset($_SESSION['form_data']);
        unset($_SESSION['efiling_user_detail']);
        unset($_SESSION['pdf_signed_details']);
        unset($_SESSION['matter_type']);
        unset($_SESSION['crt_fee_and_esign_add']);
        unset($_SESSION['mobile_no_for_updation']);
        unset($_SESSION['email_id_for_updation']);
        unset($_SESSION['search_key']);
    }

    public function index() {
        $users_array = array(USER_REGISTRAR_ACTION,USER_REGISTRAR_VIEW);

        if (in_array($_SESSION['login']['ref_m_usertype_id'], $users_array)) {

            $listed_dateF=$this->adjournment_model->getAdjournmentRequests("","","","",'listed_dateF');
            $final_data = array();
            foreach ($listed_dateF as $row){
               $existing_requests=$this->adjournment_model->getAdjournmentRequests("","",$row->listed_date,"","");
                $results_data['listed_date_count']=array(
                   'listed_date'=>$row->listed_date,
                    'listed_count'=>count($existing_requests),
                   //'listed_date_list'=>$existing_requests,
                );
                $final_data[] =(object)$results_data;

            }
            $data['date_count']=$final_data;
            $data['count_efiling_data'] = $this->RegistrarActionDashboardModel->get_mentioning_count();
            //$data['tableData'] = $this->RegistrarActionDashboardModel->get_mentioning_details('P');
            $data['tabs_heading']="Pending Mentioning Requests";
            //var_dump($data['count_efiling_data'][0]);
            //$data['count_efiling_data'][0] = array('pending_requests'=>2,'approved_requests'=>3,'rejected_requests'=>4,'all'=>10);
            $this->load->view('templates/admin_header');
             $this->load->view('registrarActionDashboard/registrar_action_dashboard_view', $data);
            $this->load->view('templates/footer');
        } else {
            redirect('login');
            exit(0);
        }
    }
    public function saveAction(){
        extract($_POST);
        if($actionType==""){
            echo "Please select action type";
            return;
        }
        elseif($actionType=="a"){
            if($approvalDate==""){
                echo "Please select proposed listing date.";
                return;
            }
        }
        elseif($actionType=="w"){
            if($approvalAwaitedRemarks==""){
                echo "Please fill approval awaited remarks.";
                return;
            }
        }
        $approvalDate=date('Y-m-d', strtotime($approvalDate));
        echo $this->RegistrarActionDashboardModel->doSaveAction($id,$actionType,$approvalDate,$approvalAwaitedRemarks);
    }
    public function getMentioningDetails(){
        extract($_POST);
        $data['tabs_heading']="";
        if($requestStatus=='P' && $urgencyWith==''){
            $data['tabs_heading']="Total Pending Mentioning Requests";
        }
        else if($requestStatus=='P' && $urgencyWith=='M'){
            $data['tabs_heading']="Pending Mentioning with urgency letter requests";
        }
        else if($requestStatus=='P' && $urgencyWith=='O'){
            $data['tabs_heading']="Pending Oral Mentioning Requests";
        }
        elseif($requestStatus=='a' && $urgencyWith==''){
            $data['tabs_heading']="Total Approved Requests";
        }
        elseif($requestStatus=='a' && $urgencyWith=='M'){
            $data['tabs_heading']="Approved - Mentioning with urgency letter requests";
        }
        elseif($requestStatus=='a' && $urgencyWith=='O'){
            $data['tabs_heading']="Approved - Oral Mentioning Requests";
        }
        elseif($requestStatus=='w' && $urgencyWith==''){
            $data['tabs_heading']="Total Approval Awaited";
        }
        elseif($requestStatus=='w' && $urgencyWith=='M'){
            $data['tabs_heading']="Approval Awaited - Mentioning with urgency letter requests";
        }
        elseif($requestStatus=='w' && $urgencyWith=='O'){
            $data['tabs_heading']="Approval Awaited - Oral Mentioning Requests";
        }
        elseif($requestStatus=='r' && $urgencyWith==''){
            $data['tabs_heading']="Total Rejected Requests";
        }
        elseif($requestStatus=='r' && $urgencyWith=='M'){
            $data['tabs_heading']="Rejected - Mentioning with urgency letter requests";
        }
        elseif($requestStatus=='r' && $urgencyWith=='O'){
            $data['tabs_heading']="Rejected - Oral Mentioning Requests";
        }
        elseif($requestStatus=="0" && $urgencyWith==''){
            $data['tabs_heading']="All Requests";
        }
        elseif($requestStatus=='0' && $urgencyWith=='M'){
            $data['tabs_heading']="All - Mentioning with urgency letter requests";
        }
        elseif($requestStatus=='0' && $urgencyWith=='O'){
            $data['tabs_heading']="All - Oral Mentioning Requests";
        }
        /*elseif($requestStatus=="M"){
            $data['tabs_heading']="Registrar Action Mention With Uregency Letter.";
        }
        elseif($requestStatus=="O"){
            $data['tabs_heading']="Registrar Action Mention With Oral.";
        }*/
        else{
            $data['tabs_heading']="Registrar Action ";
        }
        $request_type = !empty($urgencyWith) ? $urgencyWith : null;
        $cases=$this->RegistrarActionDashboardModel->get_mentioning_details($requestStatus,$request_type);
        $diaryIds="";
        $diaryIds = implode(",",array_diff(array_column($cases, 'diaryid'),['']));
        $coramData= json_decode(file_get_contents(ICMIS_SERVICE_URL."/ConsumedData/getCoramJudges?diaryIds[]=$diaryIds"));
        //print_r($coramData['data']);
        $data['coramData']=$coramData->data;
        $data['tableData'] = $cases;
        $this->load->view('registrarActionDashboard/mentioning_details', $data);
    }
    public function getAdjournmentLetterMe(){
        $listed_date=trim($_POST['listed_date']);
        $data['tabs_heading']=$listed_date;
        $data['existing_requests']=$this->adjournment_model->getAdjournmentRequests("","",$listed_date,"","");
        $this->load->view('registrarActionDashboard/adjournmentLetterMeList', $data);
    }
}

?>