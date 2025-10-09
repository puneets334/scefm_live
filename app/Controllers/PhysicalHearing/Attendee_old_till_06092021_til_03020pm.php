<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attendee extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('common');
        $this->load->helper('encryptdecrypt');
        $this->load->helper('myarray');
        $this->load->helper('curl');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->database('icmis');
        $this->load->model('hearing_model');
        $this->load->model('consent_model');
    }

    public function index($case_info){

        if(!isset($this->session->loginData)){
            $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Session Expired. Please login again.</div>');
            unset($this->session->loginData);
            redirect('auth');
        }
        $data['page_title']='Add Party/Advocate';
        $data['case_info'] = $case_info;
        $case_info=json_decode(base64_decode($case_info), true);
        //var_dump($case_info);

        $aor_count=$this->hearing_model->aorCount($case_info['diary_no']);
        $data['aor_count']=json_encode($aor_count,JSON_NUMERIC_CHECK);

        $court_capacity=$this->hearing_model->courtCapacity();
        $data['court_capacity']=json_encode($court_capacity,JSON_NUMERIC_CHECK);

        $data['attendee_types'] = json_encode($this->hearing_model->get_master('ref_attendee_type', "display='Y'and is_internal='N' and id!=9"));
        $data['aud_nomination_status']=case_listed_in_daily_list_status($case_info['diary_no']);

        //echo "Return Value is: ".$data['aud_nomination_status'];

        $capacityForEachAor = @floor($court_capacity[0]["seating_capacity"]/$aor_count[0]['advocate_count']);
        if($capacityForEachAor>MAX_NOMINATION_LIMIT_PER_AOR)
            $capacityForEachAor=min(MAX_NOMINATION_LIMIT_PER_AOR,$capacityForEachAor);


        $_SESSION['eachAORCapacity']= ($case_info['source']=='C')?MAX_LIMIT_FOR_COURT_DIRECTED_PHYSICAL_CASE:(($case_info['source']=='A')?$capacityForEachAor:$capacityForEachAor);

        //$data['doc_types'] = json_encode($this->hearing_model->get_master('ref_doc_type', array('display'=>'Y')));
        $this->load->view('physical_hearing/menu');
        $this->load->view('physical_hearing/attendee', $data);
    }

    public function checkEntryWithinAllowDateAndTime($list_date)
    {
        $today = date("Y-m-d");
        //$today = '2020-02-03';
        $current_time = date("H:i:a");
        $allow_time = '08:00 am';
        $date_current_time = DateTime::createFromFormat('H:i a', $current_time);
        $date_allow_time = DateTime::createFromFormat('H:i a', $allow_time);

        $diff=date_diff(date_create($today),date_create($list_date));
        //$diff->format("%R%a days");

        if(intval($diff->format("%R%a days"))==0)
        {
            if ($date_allow_time >= $date_current_time)
            {
                return 1;
            }
            else
                return 2; //when causelist date:time is greater than 8:am in the morning
        }
        elseif($diff->format("%R%a days")<0) // not allowed to nominate for past date
            return 3;
        else
            return 1;
        // if listing date is > today then no need to restrict nomination after 8 am
    }

    private function checkSeatAvailability($diary_no,$list_number,$list_year, $court_no){

        $aor_count = $this->hearing_model->aorCount($diary_no);
        $court_details = $this->hearing_model->courtCapacity();
        $capacityForEachAor = 0;

        if($aor_count[0]['advocate_count'])
            $capacityForEachAor = floor( $court_details[0]['seating_capacity']/$aor_count[0]['advocate_count']);
        if($capacityForEachAor>MAX_NOMINATION_LIMIT_PER_AOR)
            $capacityForEachAor=min(MAX_NOMINATION_LIMIT_PER_AOR,$capacityForEachAor);

        if($_SESSION['eachAORCapacity']==MAX_LIMIT_FOR_COURT_DIRECTED_PHYSICAL_CASE)
            $capacityForEachAor= MAX_NOMINATION_LIMIT_PER_AOR;

        $seatsAllocatedToAor = $this->hearing_model->seatsAllocatedCount($list_number,$list_year,$diary_no, $this->session->loginData['bar_id'], $court_no);
        // echo 'seat_allocated :'.$seatsAllocatedToAor[0]['seat_allocated'].'capacity:'.$capacityForEachAor.' seat allocated to law clerk:'.$seatsAllocatedToAor[0]['law_clerk'];
        //exit();
        if($seatsAllocatedToAor[0]['seat_allocated']<$capacityForEachAor && $seatsAllocatedToAor[0]['law_clerk']>0)
            return 1;
        elseif($seatsAllocatedToAor[0]['seat_allocated']<$capacityForEachAor)
            return 2; //Only advocate entry allowed
        elseif($seatsAllocatedToAor[0]['law_clerk']==0)
            return 3; //only law clerk allowed
        else
            return 0; //No entry allowed
    }

    private function registerAttendeeForHybridPhysicalHearingViaOTRInSecureGate($params){
        //return 'AASDFGVC14526H4';
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://10.0.0.0:4443/api/secure_gate/public/register-attendee-for-hybrid-physical-hearing-via-otr",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS =>json_encode($params),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "User-Agent:boat"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $visit_id=json_decode($response)->visit_id;
        if(!empty($visit_id)){
            return $visit_id;
        }
        return false;
    }

    public function save(){
        $_SESSION['alert_msg'] = null;
        if (!isset($this->session->loginData)) {
            $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Session Expired. Please login again.</div>');
            unset($this->session->loginData);
            redirect('auth');
        }
        $data = json_decode(file_get_contents("php://input"), true);
        $_POST = $data['data'];

        try {

            $visit_id = $this->registerAttendeeForHybridPhysicalHearingViaOTRInSecureGate(array(
                'name' => $_POST['name'],
                'mobileNumber' => $_POST['mobile'],
                'refAttendeeTypeId' => $_POST['ref_attendee_type_id'],
                'aorCode' => $this->session->loginData['aor_code']
            ));

        }
        catch (Exception $e){
            echo $e->getMessage();
        }


        //exit(0);
        if($_POST['mobile']!=$_POST['mobile_confirm']){
            echo 99;
        }
        elseif(@$visit_id!==false && !empty(@$visit_id) ){
            $case_next_dt=case_listed_in_daily_list_next_date($_POST['case_info']['diary_no']);
            $checkCounselEntryForToday = $this->hearing_model->getCounselEntryForToday($_POST['case_info']['diary_no'], $_POST['case_info']['list_number'], $_POST['case_info']['list_year'], $_POST['mobile'], $_POST['case_info']['court_no']);

            if ($checkCounselEntryForToday[0]['counsel_entry'] < 1) {
                $checkDateAndTimeAllow=case_listed_in_daily_list_status($_POST['case_info']['diary_no']);
                if ($checkDateAndTimeAllow == 1) {
                    $seatCheck = $this->checkSeatAvailability($_POST['case_info']['diary_no'], $_POST['case_info']['list_number'], $_POST['case_info']['list_year'], $_POST['case_info']['court_no']);

                     //echo 'seat check:'.$seatCheck.' ref_attendee_type_id'. $_POST['ref_attendee_type_id'];


                    //if(($seatCheck==1 && $_POST['ref_attendee_type_id']!=5) || ($seatCheck==3 && $_POST['ref_attendee_type_id']==5) || ($seatCheck==2)){

                    if (($seatCheck == 1 && ($_POST['ref_attendee_type_id'] != 5 && $_POST['ref_attendee_type_id'] != 9)) || ($seatCheck==3 && $_POST['ref_attendee_type_id']==5) || ($seatCheck == 2 && $_POST['ref_attendee_type_id'] != 9)  ) {
                        //echo $seatCheck.'== 1 && '.$_POST['ref_attendee_type_id'].' != 9';

                        //($seatCheck == 2 && $_POST['ref_attendee_type_id'] != 5) -> add this condition if - not allow advocate clerk to add
                        $attendee_data = array('diary_no' => $_POST['case_info']['diary_no'], 'roster_id' => NULL, 'next_dt' => $case_next_dt, 'court_number' => NULL, 'case_number' => $_POST['case_info']['case_no'], 'item_number' => NULL, 'list_number' => $_POST['case_info']['list_number'], 'list_year' => $_POST['case_info']['list_year'], 'ref_attendee_type_id' => $_POST['ref_attendee_type_id'], 'name' => $_POST['name'], 'email_id' => $_POST['email'], 'mobile' => $_POST['mobile'], 'display' => 'Y', 'created_by_advocate_id' => $this->session->loginData['bar_id'], 'ip_address' => get_client_ip(), 'court_no'=>$_POST['case_info']['court_no']);
                        //$attendee_data = array('diary_no' => $_POST['case_info']['diary_no'], 'roster_id' => NULL, 'next_dt' => $case_next_dt, 'court_number' => NULL, 'case_number' => $_POST['case_info']['case_no'], 'item_number' => NULL, 'list_number' => $_POST['case_info']['list_number'], 'list_year' => $_POST['case_info']['list_year'], 'ref_attendee_type_id' => $_POST['ref_attendee_type_id'], 'name' => $_POST['name'], 'email_id' => $_POST['email'], 'mobile' => $_POST['mobile'], 'display' => 'Y', 'created_by_advocate_id' => $this->session->loginData['bar_id'], 'ip_address' => get_client_ip(), 'court_no'=>$_POST['case_info']['court_no'], 'secure_gate_visit_id' => @$visit_id);
                        $result = $this->hearing_model->save('attendee_details', $attendee_data);
                        if ($result) {
                            $mobile_no=str_repeat('X', strlen($_POST['mobile']) - 4) . substr($_POST['mobile'], -4);
                            $message="You have been nominated to attend physical hearing in Case No. ".$_POST['case_info']['case_no']." by the AOR, ".$_SESSION['loginData']['title']." ".$_SESSION['loginData']['name'].". Kindly fill the Self Declaration using your mobile number ".$mobile_no.' . Also, if not already registered, kindly perform one time registration for "Special Hearing Pass" on the link available under "Hearings in Physical mode(with Hybrid Option)" on SCI website.';
                            send_sms($_POST['mobile'], $message, null);
                            send_email($_POST['email'], "Physical hearing on Case No. ".$_POST['case_info']['case_no'], $message);
                            $logEntryStatus = $this->saveAttendeeLog($result, 'Y');
                            echo $logEntryStatus;
                        }
                    } else {
                        if ($seatCheck == 1  && $_POST['ref_attendee_type_id'] == 5 )
                            echo 1;
                        elseif ($seatCheck == 3 || $seatCheck == 0)
                            echo 2;
                        elseif (($seatCheck == 2 || $seatCheck == 1) && $_POST['ref_attendee_type_id'] == 9)
                            echo 9;
                        elseif ($seatCheck == 2 && $_POST['ref_attendee_type_id'] == 5)
                            echo 10;
                        else
                            echo 3;
                    }
                }
                elseif ($checkDateAndTimeAllow == 2)
                    echo 5;
                else
                    echo 6;

            } else
                echo 7;
        }
        else{
            echo 8;
        }

        /*if($seatCheck) {
            $attendee_data = array('diary_no' => $_POST['case_info']['diary_no'], 'roster_id' => $_POST['case_info']['roster_id'], 'next_dt' => $_POST['case_info']['listing_date'], 'court_number' => $_POST['case_info']['court_number'], 'case_number' => $_POST['case_info']['case_no'], 'item_number' => $_POST['case_info']['item_number'], 'ref_attendee_type_id' => $_POST['ref_attendee_type_id'], 'name' => $_POST['name'], 'email_id' => $_POST['email'], 'mobile' => $_POST['mobile'], 'display' => 'Y', 'created_by_advocate_id' => $this->session->loginData['bar_id'], 'ip_address' => get_client_ip(),);
            echo $result = $this->hearing_model->save('attendee_details', $attendee_data);
        }
        else
            echo 0;*/

    }

    public function attendee_list($caseInfo){

        $caseInfo = json_decode(base64_decode($caseInfo), true);
        $data['aor_count']= $this->hearing_model->aorCount($caseInfo['diary_no']);
        $case_next_dt=case_listed_in_daily_list_next_date($caseInfo['diary_no']);
        $current_timestamp = strtotime(date("Y-m-d H:i:s"));
        $allowed_upto_timestamp = (empty($case_next_dt))?$current_timestamp:strtotime(date($case_next_dt.ATTENDEE_VISIBLE_UPTO));

        $otherAttendee = array();
        $selfDetails = array();
        //If next date in attendee table exists and is less than current datetime then give option to copy previously added attendees for next hearing in the given case.
        if($allowed_upto_timestamp>=$current_timestamp){
            $otherAttendee=$this->hearing_model->getAttendeeList($caseInfo['diary_no'],$caseInfo['list_number'],$caseInfo['list_year'],$this->session->loginData['bar_id'],$caseInfo['court_no']);
            //Check for entry if not exists the make entry
            $this->doCaseAOREntry($caseInfo);
            $selfDetails=$this->hearing_model->getSelfEntry($caseInfo['diary_no'],$caseInfo['list_number'],$caseInfo['list_year'],$this->session->loginData['bar_id'],$caseInfo['court_no']);
        }

        $data['case_info']=array_merge($selfDetails,$otherAttendee);
        $data['aud_nomination_status']=case_listed_in_daily_list_status($caseInfo['diary_no']);

        echo json_encode($data);
    }


    private function doCaseAOREntry($caseInfo){
        $selfDetails=$this->hearing_model->getSelfEntry($caseInfo['diary_no'],$caseInfo['list_number'],$caseInfo['list_year'], $this->session->loginData['bar_id'], $caseInfo['court_no']);
        if(count($selfDetails)<=0){
            try {
                $visit_id = @$this->registerAttendeeForHybridPhysicalHearingViaOTRInSecureGate(array(
                    'name' => $this->session->loginData['name'],
                    'mobileNumber' => $this->session->loginData['mobile'],
                    'refAttendeeTypeId' => 9,
                    'aorCode' => $this->session->loginData['aor_code']
                ));
            }
            catch (Exception $e){
                //echo $e->getMessage();
            }
            $case_next_dt=case_listed_in_daily_list_next_date($caseInfo['diary_no']);
            //exit(0);
            if(@$visit_id!==false && !empty(@$visit_id)) {
                //Save Self Details in Attendee_detail table
                $aor_self_data = array('diary_no' => $caseInfo['diary_no'], 'roster_id' => null, 'next_dt' => $case_next_dt, 'court_number' => null, 'case_number' => $caseInfo['case_no'], 'item_number' => null, 'list_year' => $caseInfo['list_year'], 'list_number' => $caseInfo['list_number'], 'ref_attendee_type_id' => 9, 'name' => $this->session->loginData['title'] . ' ' . $this->session->loginData['name'], 'email_id' => $this->session->loginData['email'], 'mobile' => $this->session->loginData['mobile'], 'display' => 'Y', 'created_by_advocate_id' => $this->session->loginData['bar_id'], 'ip_address' => get_client_ip(), 'court_no' => $caseInfo['court_no']);
                //$aor_self_data = array('diary_no' => $caseInfo['diary_no'], 'roster_id' => null, 'next_dt' => $case_next_dt, 'court_number' => null, 'case_number' => $caseInfo['case_no'], 'item_number' => null, 'list_year' => $caseInfo['list_year'], 'list_number' => $caseInfo['list_number'], 'ref_attendee_type_id' => 9, 'name' => $this->session->loginData['title'] . ' ' . $this->session->loginData['name'], 'email_id' => $this->session->loginData['email'], 'mobile' => $this->session->loginData['mobile'], 'display' => 'Y', 'created_by_advocate_id' => $this->session->loginData['bar_id'], 'ip_address' => get_client_ip(), 'court_no' => $caseInfo['court_no'], 'secure_gate_visit_id' => @$visit_id);
                $result = $this->hearing_model->save('attendee_details', $aor_self_data);
                if ($result)
                    return 4;
            }
            else{
                return 8;
            }
        }
    }

    private function saveAttendeeLog($id,$flag)
    {
        $attendeeDetails=$this->hearing_model->checkActiveAttendeeEntry($id);
        if(count($attendeeDetails)>=0) {
            $attendee_log_data = array('attendee_details_id' => $id, 'display' => $flag, 'created_by_ip_address'  => get_client_ip());
            $result = $this->hearing_model->save('attendee_details_log', $attendee_log_data);
            if($result)
                return 4;
        }
    }

    public function delete_attendee($id, $caseInfo){
        $caseInfo = json_decode(base64_decode($caseInfo), true);
        $attendee_detail = $this->hearing_model->get_master('attendee_details', array('id'=>$id));
        $checkDateAndTimeAllow=case_listed_in_daily_list_status($caseInfo['diary_no']);

        if($checkDateAndTimeAllow==1)
        {
            if($this->saveAttendeeLog($id,'N'))
            {
                echo $this->hearing_model->update('attendee_details', array('display' => 'N', 'updated_on' => date('Y-m-d H:i:s'), 'updated_from_ip_address' => get_client_ip()), array('id' => $id, 'diary_no' => $caseInfo['diary_no']));
                $message = "Your gate pass to attend physical hearing in Case No. ".$attendee_detail[0]['case_number']." is not valid. --Supreme Court of India";
                send_sms($attendee_detail[0]['mobile'], $message, GATE_PASS_HEARING);
                send_email($attendee_detail[0]['email_id'], "Regarding physical hearing for ".$attendee_detail[0]['case_number'], $message);
            }
        }
        else
            echo '2';
    }

    public function restore_attendee($id, $caseInfo){

        $caseInfo = json_decode(base64_decode($caseInfo), true);
        $checkDateAndTimeAllow=case_listed_in_daily_list_status($caseInfo['diary_no']);

        if($checkDateAndTimeAllow==1)
        {
            $totalAttendee=$this->hearing_model->getActiveAttedeeCount($caseInfo['diary_no'],$caseInfo['list_number'],$caseInfo['list_year'],$this->session->loginData['bar_id'],$caseInfo['court_no']);

            if($_SESSION['eachAORCapacity']>$totalAttendee[0]['total_attendee'])
            {
                if($this->saveAttendeeLog($id,'Y')) {
                    echo $this->hearing_model->update('attendee_details', array('display' => 'Y', 'updated_on' => date('Y-m-d H:i:s'), 'updated_from_ip_address' => get_client_ip()), array('id' => $id, 'diary_no' => $caseInfo['diary_no']));
                }
            }
            else
                echo 0;
        }
    }

    public function saveEmpSelfData(){
        extract(json_decode(file_get_contents('php://input'), true));
        $type= '';
        $id ='';
        if(isset($update) && !empty($update)){
            $type = (int)$update;
        }
        if(isset($userId) && !empty($userId)){
            $id = (int)$userId;
        }
        if(empty($name)){
            echo json_encode(array('status'=>1,'id'=>'name'));
        }
        else if(empty($category)){
            echo json_encode(array('status'=>1,'id'=>'category'));
        }
        else if(empty($address)){
            echo json_encode(array('status'=>1,'id'=>'address'));
        }
        else if(empty($mobile)){
            echo json_encode(array('status'=>1,'id'=>'mobile'));
        }
        else if(!preg_match('/^[0-9]{10}+$/', $mobile)){
            echo json_encode(array('status'=>1,'id'=>'mobile'));
        }
        else{
            $inArr = array();
            switch ($type){
                case 1:
                    $inArr = array();
                    $inArr['travel'] =  !empty($travel) ?  (int)$travel : 0;
                    $inArr['travel_yes'] = !empty($travel_yes) ? htmlspecialchars(addslashes(trim($travel_yes))) : NULL;
                    $inArr['symptoms'] = !empty($symptoms) ? (int)$symptoms : 0;
                    $inArr['symptoms_met'] = !empty($symptoms_met) ? (int) $symptoms_met : 0;
                    $inArr['name'] = !empty($name) ? htmlspecialchars(addslashes(trim($name))) : NULL;
                    $inArr['other_category'] = !empty($other_category) ? htmlspecialchars(addslashes(trim($other_category))) : NULL;
                    $inArr['category'] = !empty($category) ? (int)$category : NULL;
                    $inArr['address'] = !empty($address) ? htmlspecialchars(addslashes(trim($address))) : NULL;
                    $inArr['mobile'] = !empty($mobile) ? trim($mobile) : NULL;
                    $inArr['wheel_chair'] = !empty($wheel_chair) ? (int)$wheel_chair : 0;
                    $inArr['for_date'] = date('Y-m-d H:i:s');
                    $this->load->model('consent_model');
                    $result=$this->consent_model->dbInsert($inArr,'emp_self_declarion');
                    $res =false;
                    if(isset($result) && !empty($result)){
                        if(!empty($this->session->userdata('loginData')['post_mobile'])){
                            $mobile= $this->session->userdata('loginData')['post_mobile'];
                            $params = array();
                            $params['current_date'] = date('Y-m-d');
                            $params['mobile'] = (int)$mobile;
                            $this->load->model('auth_model');
                            $result = $this->auth_model->getSelfDeclarationTodayData($params);
                            $res = !empty($result[0]) ? $result[0] : false;
                        }
                        else if(!empty($this->session->userdata('loginData')['mobile'])){
                            $mobile= $this->session->userdata('loginData')['mobile'];
                            $params = array();
                            $params['current_date'] = date('Y-m-d');
                            $params['mobile'] = (int)$mobile;
                            $this->load->model('auth_model');
                            $result = $this->auth_model->getSelfDeclarationTodayData($params);
                            $res = !empty($result[0]) ? $result[0] : false;
                        }
                        echo json_encode(array('status'=>2,'userdata'=>$res,'msg'=>'User details has been saved successfully!'));
                        exit;
                    }
                    else{
                        echo json_encode(array('status'=>3,'msg'=>'Error,Please try again later.'));
                        exit;
                    }
                    break;
                case 2:
                    $updateArr = array();
                    $updateArr['travel'] =  !empty($travel) ?  (int)$travel : 0;
                    if(isset($updateArr['travel']) && !empty($updateArr['travel'])){
                        $updateArr['travel_yes'] = !empty($travel_yes) ? htmlspecialchars(addslashes(trim($travel_yes))) : NULL;
                    }
                    else{
                        $updateArr['travel_yes'] = NULL;
                    }
                    $updateArr['symptoms'] = !empty($symptoms) ? (int)$symptoms : 0;
                    $updateArr['symptoms_met'] = !empty($symptoms_met) ? (int) $symptoms_met : 0;
                    $updateArr['name'] = !empty($name) ? htmlspecialchars(addslashes(trim($name))) : NULL;
                    $updateArr['other_category'] = !empty($other_category) ? htmlspecialchars(addslashes(trim($other_category))) : NULL;
                    $updateArr['category'] = !empty($category) ? (int)$category : NULL;
                    $updateArr['address'] = !empty($address) ? htmlspecialchars(addslashes(trim($address))) : NULL;
                    $updateArr['mobile'] = !empty($mobile) ? trim($mobile) : NULL;
                    $updateArr['wheel_chair'] = !empty($wheel_chair) ? (int)$wheel_chair : 0;
                    $updateArr['for_date'] = date('Y-m-d H:i:s');
                    $this->load->model('consent_model');
                    if(isset($id) && !empty($id)){
                        $condition_array = array('id'=>$id);
                        $result=$this->consent_model->update('emp_self_declarion',$updateArr,$condition_array);
                        $res =false;
                        if(isset($result) && !empty($result)){
                            if(!empty($this->session->userdata('loginData')['post_mobile'])){
                                $mobile= $this->session->userdata('loginData')['post_mobile'];
                                $params = array();
                                $params['current_date'] = date('Y-m-d');
                                $params['mobile'] = (int)$mobile;
                                $this->load->model('auth_model');
                                $result = $this->auth_model->getSelfDeclarationTodayData($params);
                                $res = !empty($result[0]) ? $result[0] : false;
                            }
                            else if(!empty($this->session->userdata('loginData')['mobile'])){
                                $mobile= $this->session->userdata('loginData')['mobile'];
                                $params = array();
                                $params['current_date'] = date('Y-m-d');
                                $params['mobile'] = (int)$mobile;
                                $this->load->model('auth_model');
                                $result = $this->auth_model->getSelfDeclarationTodayData($params);
                                $res = !empty($result[0]) ? $result[0] : false;
                            }
                            echo json_encode(array('status'=>2,'userdata'=>$res,'msg'=>'User details has been updated successfully!'));
                            exit;
                        }
                        else{
                            echo json_encode(array('status'=>3,'msg'=>'Error,Please try again later.'));
                            exit;
                        }
                    }
                    else{
                        echo json_encode(array('status'=>3,'msg'=>'Error,Please try again later.'));
                        exit;
                    }
                    break;
                default;
            }
        }
    }


    private function getTomorrowRegularDailyListCases($next_date)
    {
        $schedule_request_params = array('forDate' => $next_date, 'fromDate' => $next_date, 'toDate' => $next_date, 'hearingType' => array('F'), 'responseFormat' => 'CASE_WISE_FLATTENED_WITH_ALL_INFO', 'ifSkipDigitizedCasesStageComputation' => true);
        $scheduled_cases = (array)json_decode(file_get_contents('https://10.0.0.0:4443/api/schedule/cases?'.http_build_query($schedule_request_params), false, stream_context_create(array('http' => array('user_agent' => 'Mozilla'), "ssl"=>array("verify_peer"=>false, "verify_peer_name"=>false)))));

        $tomorrow_daily_list_cases=array();
        foreach ($scheduled_cases[0] as $key => $value) {
            //echo $value->diary_id;
            array_push($tomorrow_daily_list_cases,$value->diary_id);
        }
        return $tomorrow_daily_list_cases;

    }

    function send_tentative_list_email() {
        $tomorrow = date("Y-m-d", strtotime("+1 day"));
        $next_date_regular_hearing_listed_case=$this->getTomorrowRegularDailyListCases($tomorrow);
        $mail_response='';
        if(!empty($next_date_regular_hearing_listed_case)) { //check whether daily list for the next date is published or not
            $this->update_next_date_in_attendee_list_and_consent(); // update the next date if blank
            $weekly_list = $this->hearing_model->weekly_list_number();
            $list_number = $weekly_list[0]['weekly_no'];
            $list_year = $weekly_list[0]['weekly_year'];


            $tentative_attendee_list = $this->consent_model->getTentativeAttendeeListForMail($list_number, $list_year, $tomorrow, $next_date_regular_hearing_listed_case);

            if (!empty($tentative_attendee_list)) {
                $title = "Tentative List for " . date('d-m-Y', strtotime($tomorrow));
                $message = array(
                    'title' => $title,
                    'list_type'=>'tentative',
                    'attendee_list' => $tentative_attendee_list,
                );
                $mail_response=send_email(EMAIL_TO_CONCERN, $title, $message, 1).' - Attendee details';
            } else {
                $html_message = 'No Choice is received for '.APP_NAME_L1.' '.APP_NAME_L2.' for cases listed on ' . date('d-m-Y', strtotime($tomorrow)) . ' from any AOR till this time';
                $mail_response=send_email(EMAIL_TO_CONCERN, 'No Choice for the day', $html_message).' - No Choice for the day';
            }
        }
        else {
            $html_message = 'Daily List of Regular Hearing matters for ' . date('d-m-Y', strtotime($tomorrow)) . ' seems to have not been published at the time of sending this email';
            $mail_response=send_email(EMAIL_TO_CONCERN, 'Daily List not Published for '.date('d-m-Y', strtotime($tomorrow)), $html_message).' - No Daily List';

        }
        echo $mail_response;
        send_sms(SMS_TO_CONCERN, "Tentative List response: ".$mail_response);
    }

    function send_final_list_email() {

        //$today = date("Y-m-d");
        $today=date("Y-m-d", strtotime("+1 day"));
        $next_date_regular_hearing_listed_case=$this->getTomorrowRegularDailyListCases($today);

        $mail_response='';
        if(!empty($next_date_regular_hearing_listed_case)) {
            //$this->update_next_date_in_attendee_list_and_consent(); // update the next date if blank
            $weekly_list = $this->hearing_model->weekly_list_number();
            $list_number = $weekly_list[0]['weekly_no'];
            $list_year = $weekly_list[0]['weekly_year'];

            $tentative_attendee_list = $this->consent_model->getTentativeAttendeeListForMail($list_number, $list_year, $today,$next_date_regular_hearing_listed_case);
             //var_dump($tentative_attendee_list);exit();
            if (!empty($tentative_attendee_list)) {
                $title = "Final List of Attendee for ".date('d-m-Y', strtotime($today));
                $message = array(
                    'title' => $title,
                    'list_type'=>'final',
                    'attendee_list' => $tentative_attendee_list,
                );
                $mail_response=send_email(EMAIL_TO_CONCERN, $title, $message, 1).' - Attendee details';
            } else {
                $html_message = 'No Choice is received for '.APP_NAME_L1.' '.APP_NAME_L2.' for cases listed on ' . date('d-m-Y', strtotime($today)) . ' from any AOR till this time';
                $mail_response=send_email(EMAIL_TO_CONCERN, 'No Choice for the day', $html_message).' - No Choice for the day';
            }
        }
        else {
            $html_message = 'Daily List of Regular Hearing matters for ' . date('d-m-Y', strtotime($today)) . ' seems to have not been published at the time of sending this email';
            $mail_response=send_email(EMAIL_TO_CONCERN, 'Daily List not Published for '.date('d-m-Y', strtotime($today)), $html_message).' - No Daily List';

        }
        echo $mail_response;
        //send_sms(SMS_TO_CONCERN, "Final List response: ".$mail_response);
    }


    public function copy_attendee($caseInfo){
        $caseInfo = json_decode(base64_decode($caseInfo), true);
        $copy_status = $this->consent_model->copy_attendee($caseInfo['diary_no'], $this->session->loginData['bar_id'],$caseInfo['list_number'], $caseInfo['list_year'], $caseInfo['court_no']);
        echo $copy_status;
    }
    public function send_7am_reminder(){
        if(!$this->input->is_cli_request()){
            echo "This script can only be accessed via the command line" . PHP_EOL;
            return;
        }

        $today = date("Y-m-d");
        $next_date_regular_hearing_listed_case=$this->getTomorrowRegularDailyListCases($today);

        $weekly_list = $this->hearing_model->weekly_list_number();

        // we will send sms for all diary numbers i.e we did not use court_no
        $list_number = $weekly_list[0]['weekly_no'];
        $list_year = $weekly_list[0]['weekly_year'];
        if(!empty($next_date_regular_hearing_listed_case)) { // sms send only if daily list of today is already published
            $next_date_regular_hearing_listed_case=explode(",", $next_date_regular_hearing_listed_case);
            //$next_date_regular_hearing_listed_case=array();

            $advocate_consent_already_received_for_cases_list = $this->hearing_model->get_consent_received_from_advocate_all_cases($list_number,$list_year,$today);
            $advocate_consent_already_received_for_cases_list=$advocate_consent_already_received_for_cases_list[0]['cases_list'];
            $advocate_consent_already_received_for_cases_list=explode(",", $advocate_consent_already_received_for_cases_list);

            $cases_list_in_which_advocate_consent_pending_till_now=array_diff($next_date_regular_hearing_listed_case,$advocate_consent_already_received_for_cases_list);

            $advocate_details = $this->hearing_model->get_consent_not_received_cases_advocate_mobile_nos($cases_list_in_which_advocate_consent_pending_till_now);
            $advocate_mobile_nos=$advocate_details[0]['mobile_nos'];

            $message_at_7am = "Kindly register yourself by/before 8 AM today on the website to either obtain Special Hearing Passes or VC links for yourself (or nominee) for your cases listed today. After 8 AM special hearing passes can be generated only at the designated counters at new reception office, SCI";
            send_sms($advocate_mobile_nos, $message_at_7am, null);
        }
    }

    public function update_next_date_in_attendee_list_and_consent(){
        $today = date("Y-m-d");
        $weekly_list = $this->hearing_model->weekly_list_number();
        $list_number = $weekly_list[0]['weekly_no'];
        $list_year = $weekly_list[0]['weekly_year'];
        $all_advocate_consent_distinct_main_cases_of_current_daily_with_blank_next_dt=$this->hearing_model->get_consent_received_from_advocate_main_cases($list_number,$list_year,$today);
        //var_dump($all_advocate_consent_distinct_main_cases_of_current_daily_with_blank_next_dt);exit();
        foreach ($all_advocate_consent_distinct_main_cases_of_current_daily_with_blank_next_dt as $case)
        {

            $diary_no_which_next_need_to_updated=($case['diary_no']);
            $case_published_daily_listed_details=$this->hearing_model->get_case_listed_in_daily_list($diary_no_which_next_need_to_updated);
            //var_dump($case_published_daily_listed_details);
            $case_next_date=$case_published_daily_listed_details[0]['next_dt'];
            //$case_next_date='2021-09-02';//only for testing
            if(!empty($case_next_date))
            {
                $case_blank_next_date_attendees=$this->hearing_model->get_case_all_attendee_added_by_advocate_with_blank_next_dt($list_number,$list_year,$today);
               // var_dump($case_blank_next_date_attendees);exit();

                if(!empty($case_blank_next_date_attendees))
                {
                    $attendee_table_update_where_condition=array('diary_no' => $diary_no_which_next_need_to_updated,'list_number' =>$list_number, 'list_year' => $list_year,'created_by_advocate_id' => $case_blank_next_date_attendees[0]['created_by_advocate_id']);
                    $update_data=array('next_dt' => $case_next_date);
                    $update_case_attendee_next_date_result=$this->consent_model->update('attendee_details',$update_data,$attendee_table_update_where_condition);
                }

                $consent_table_update_where_condition=array('diary_no' => $diary_no_which_next_need_to_updated,'list_number' => $list_number, 'list_year' => $list_year,'court_no'=>$case['court_no'],'advocate_id'=>$case['advocate_id']);
                $update_data=array('next_dt' => $case_next_date);

                 $update_case_consent_next_date_result=$this->consent_model->update('physical_hearing_advocate_consent',$update_data,$consent_table_update_where_condition);
            }
            else
                continue;
        }

    }

}
