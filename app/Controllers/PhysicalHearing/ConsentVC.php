<?php

namespace App\Controllers\PhysicalHearing;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\PhysicalHearing\ConsentVCModel;
use App\Models\PhysicalHearing\ConsentModel;
use App\Models\PhysicalHearing\HearingModel;
use Config\Database;
// use App\Models\PutModel;

class ConsentVC extends BaseController
{
    protected $consent_VC_model;
    protected $consent_model;
    protected $hearing_model;
    protected $physical_hearing;

    public function __construct()
    {
        helper(['common', 'encryptdecrypt', 'myarray', 'curl', 'url', 'form', 'session']); 
        $this->consent_VC_model = new ConsentVCModel(); 
        $this->consent_model = new ConsentModel(); 
        $this->hearing_model = new HearingModel();
        $this->physical_hearing = Database::connect('physical_hearing'); 
        $session = session();
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
    }

    public function index($court=null)
    {
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }
        $aor_code = getSessionData('login.aor_code');
        $data = array();
		//  $physical_hearing_data=array();
		// $physical_hearing_data = json_decode(file_get_contents('http://10.40.186.150:88/Physicalhearing/get_case_details?aor_code='.$aor_code.'&court='.$court));
        $physical_hearing_data = json_decode(file_get_contents(ICMIS_SERVICE_URL.'/Physicalhearing/get_case_details/?aor_code='.$aor_code.'&court='.$court));
		if (isset($physical_hearing_data) && !empty($physical_hearing_data)) {
            if (isset($physical_hearing_data) && !empty($physical_hearing_data) && isset($physical_hearing_data->physical_hearing) && !empty($physical_hearing_data->physical_hearing) && $physical_hearing_data->physical_hearing->status=='Y') {
                $data['display_message'] = (array)$physical_hearing_data->physical_hearing->display_message;
                $data['display_message1'] = $physical_hearing_data->physical_hearing->display_message1;
                $data['freezed_court'] = array_map('objectToArraySelective', (array)$physical_hearing_data->physical_hearing->freezed_court);
                $data['listing_date'] = (array)$physical_hearing_data->physical_hearing->listing_date;
                $data['advocate_cases_summary'] = array_map('objectToArraySelective', (array)$physical_hearing_data->physical_hearing->advocate_cases_summary);
                $data['cases'] = array_map('objectToArraySelective', (array)$physical_hearing_data->physical_hearing->cases);
            }
		} else {
            $next_misc_working_date=getNextMiscDayOfHearing();
            // $next_misc_working_date = '2024-01-03';
            $listing_date = (array)$next_misc_working_date;
            $isEntryAllowed=checkEntryWithinAllowDateAndTime($next_misc_working_date); 
            $data['page_title']='Choose mode of Hearing';
            $unfreezed_court = $this->consent_VC_model->available_court_list($listing_date);
            $freezed_court = $this->consent_VC_model->freezed_court_list($listing_date);
            $list = array();
            foreach ($unfreezed_court as $key => $value) {
                $list[] = $value['court_no'];
            }
            $list1 = array();
            foreach ($freezed_court as $key => $value) {
                $list1[] = $value['court_no'];
            }
            $unfreezed_court_list=array_diff($list,$list1);
            $data['display_message'] = 'List awaiting for Court Numbers : '.implode(', ',$unfreezed_court_list);
            $data['display_message1'] = 'List available for Court Numbers : '.implode(', ',$list1);
            $data['freezed_court']=isset($freezed_court)?$freezed_court:null;
            $data['listing_date']=$listing_date;
            $data['advocate_cases_summary'] = $this->consent_VC_model->getListedAdvocateMatters(getSessionData('login.adv_sci_bar_id'),$listing_date);
            if(!empty($court)) {
                $data['cases'] = $this->consent_VC_model->getFutureListedMatters(getSessionData('login.adv_sci_bar_id'), $listing_date, $court);
            } else {
                $data['cases']=null;
            }
        }
        return $this->render('physical_hearing.v3.consent_VC', $data);
    }

    public  function case_listed_in_daily_status($diary_no)
    {
       $aud_nomination_status=case_listed_in_daily_list_status($diary_no);
       echo $aud_nomination_status;
    }

    public function save() {
        extract($_POST);
        foreach($hearingModeConsent as $diary_no=>$aor_consent) {
            $aud_consent_status=case_listed_in_daily_list_status($diary_no);
            if($aud_consent_status==1 || 1) {
                $already_updated_data=$this->consent_VC_model->getAORConsentDetails($diary_no,$next_date[$diary_no],$roster_id[$diary_no],getSessionData('login.adv_sci_bar_id'), $court_no);
                $condition=array('diary_no' => $diary_no,'next_dt' => $next_date[$diary_no], 'roster_id' => $roster_id[$diary_no], 'court_no'=>$court_no, 'advocate_id'=>getSessionData('login.adv_sci_bar_id'));
                if(count($already_updated_data)>0) {
                    $this->physical_hearing->transBegin();
                    if(!empty($already_updated_data)) {
                        $insert_result=$this->consent_VC_model->InsertLog("physical_hearing_advocate_vc_consent","physical_hearing_advocate_vc_consent_log",$diary_no,$next_date[$diary_no],$roster_id[$diary_no], $court_no, getSessionData('login.adv_sci_bar_id'));
                    }
                    $data=array(
                        'updated_by' => getSessionData('login.adv_sci_bar_id'),
                        'consent' => $aor_consent,
                        'updated_from_ip' => get_client_ip(),
                        'updated_on' => date('Y-m-d H:i:s'),
                        'advocate_id' => getSessionData('login.adv_sci_bar_id'),
                        'consent_for_diary_nos' => $consent_for_diary_nos[$diary_no],
                        'case_count' => $case_count[$diary_no]
                    );
                    $update_result=$this->consent_VC_model->update('physical_hearing_advocate_vc_consent',$data,$condition);
                    if($this->physical_hearing->transStatus() === FALSE || !isset($insert_result) || !isset($update_result)){
                        $this->physical_hearing->transRollback();
                    } else {
                        $this->physical_hearing->transCommit();
                    }
                } else {
                    $data=array(
                        'updated_by' => getSessionData('login.adv_sci_bar_id'),
                        'diary_no' => $diary_no,
                        'next_dt' => $next_date[$diary_no],
                        'roster_id' => $roster_id[$diary_no],
                        'consent' => $aor_consent,
                        'updated_from_ip' => get_client_ip(),
                        'updated_on' => date('Y-m-d H:i:s'),
                        'advocate_id' => getSessionData('login.adv_sci_bar_id'),
                        'court_no' => $court_no,
                        'consent_for_diary_nos' => $consent_for_diary_nos[$diary_no],
                        'case_count' => $case_count[$diary_no],
                        'item_no'=>$item_no[$diary_no]
                    );
                    $this->consent_VC_model->save('physical_hearing_advocate_vc_consent',$data);
                    /* if($aor_consent=='V') {
                        // Send SMS & Email only if consent was changed from P to V.
                        $this->status_change_info_to_nominees(array('diary_no'=>$diary_no,'list_number'=>$list_number,'list_year'=>$list_year,'court_no'=>$court_no));
                    }*/
                }
            } else {
                $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center text-bold">You cannot add/modify Consent after 8 am of hearing date of Diary No.'.$diary_no.'</div>');
                return redirect()->to(base_url("Consent"));
            }
        }
        session()->setFlashdata('msg', '<div class="alert alert-success text-center text-bold">Consent Updated Successfully</div>');
        return redirect()->to(base_url('physical_hearing'));
    }

    function send_final_opt_vc_list_email() {
        $today = date("Y-m-d");
        $next_date_regular_hearing_listed_case=$this->getTomorrowRegularDailyListCases($today);
        $mail_response='';
        if(!empty($next_date_regular_hearing_listed_case)) {
            $this->update_next_date_in_attendee_list_and_consent();
            $weekly_list = $this->hearing_model->weekly_list_number();
            $list_number = $weekly_list[0]['weekly_no'];
            $list_year = $weekly_list[0]['weekly_year'];
            $tentative_attendee_list = $this->consent_model->getTentativeAttendeeListForMail($list_number, $list_year, $today,$next_date_regular_hearing_listed_case);
            if (!empty($tentative_attendee_list)) {
                $title = "Final List of Attendee for ".date('d-m-Y', strtotime($today));
                $message = array(
                    'title' => $title,
                    'list_type'=>'final',
                    'attendee_list' => $tentative_attendee_list,
                );
                $data = array(
                    'message' => $message
                );
                $url=base_url('index.php/attendee/printAttendeeFinalReport');
                $fileData[] = array( 'url'=>$url);
                $pdf_content=json_encode($fileData);
                $mail_response=send_email(EMAIL_TO_CONCERN, $title, $message, 1,rawurlencode($pdf_content)).' - Attendee details';
            } else {
                $html_message = 'No Choice is received for '.APP_NAME_L1.' '.APP_NAME_L2.' for cases listed on ' . date('d-m-Y', strtotime($today)) . ' from any AOR till this time';
                $mail_response=send_email(EMAIL_TO_CONCERN, 'No Choice for the day', $html_message).' - No Choice for the day';
            }
        } else {
            $html_message = 'Daily List of Regular Hearing matters for ' . date('d-m-Y', strtotime($today)) . ' seems to have not been published at the time of sending this email';
            $mail_response=send_email(EMAIL_TO_CONCERN, 'Daily List not Published for '.date('d-m-Y', strtotime($today)), $html_message).' - No Daily List';
        }
        echo $mail_response;
        send_sms(SMS_TO_CONCERN, "Final List response: ".$mail_response);
    }

    private function getTomorrowRegularDailyListCases($next_date) {
        $schedule_request_params = array(
            'forDate' => $next_date, 
            'fromDate' => $next_date, 
            'toDate' => $next_date, 
            'hearingType' => array('F'), 
            'responseFormat' => 'CASE_WISE_FLATTENED_WITH_ALL_INFO', 
            'ifSkipDigitizedCasesStageComputation' => true
        );
        $scheduled_cases = (array)json_decode(file_get_contents('https://10.0.0.0:4443/api/schedule/cases?'.http_build_query($schedule_request_params), false, stream_context_create(array('http' => array('user_agent' => 'Mozilla'), "ssl"=>array("verify_peer"=>false, "verify_peer_name"=>false)))));
        $tomorrow_daily_list_cases=array();
        foreach ($scheduled_cases[0] as $key => $value) {
            array_push($tomorrow_daily_list_cases,$value->diary_id);
        }
        return $tomorrow_daily_list_cases;
    }

    public function update_next_date_in_attendee_list_and_consent() {
        $today = date("Y-m-d");
        $weekly_list = $this->hearing_model->weekly_list_number();
        $list_number = $weekly_list[0]['weekly_no'];
        $list_year = $weekly_list[0]['weekly_year'];
        $all_advocate_consent_distinct_main_cases_of_current_daily_with_blank_next_dt=$this->hearing_model->get_consent_received_from_advocate_main_cases($list_number,$list_year,$today);
        foreach ($all_advocate_consent_distinct_main_cases_of_current_daily_with_blank_next_dt as $case) {
            $diary_no_which_next_need_to_updated=($case['diary_no']);
            $case_published_daily_listed_details=$this->hearing_model->get_case_listed_in_daily_list($diary_no_which_next_need_to_updated);
            $case_next_date=$case_published_daily_listed_details[0]['next_dt'];
            if(!empty($case_next_date)) {
                $case_blank_next_date_attendees=$this->hearing_model->get_case_all_attendee_added_by_advocate_with_blank_next_dt($list_number,$list_year,$today);
                if(!empty($case_blank_next_date_attendees)) {
                    $attendee_table_update_where_condition=array('diary_no' => $diary_no_which_next_need_to_updated,'list_number' =>$list_number, 'list_year' => $list_year,'created_by_advocate_id' => $case_blank_next_date_attendees[0]['created_by_advocate_id']);
                    $update_data=array('next_dt' => $case_next_date);
                    $update_case_attendee_next_date_result=$this->consent_model->update('attendee_details',$update_data,$attendee_table_update_where_condition);
                }
                $consent_table_update_where_condition=array('diary_no' => $diary_no_which_next_need_to_updated,'list_number' => $list_number, 'list_year' => $list_year,'court_no'=>$case['court_no'],'advocate_id'=>$case['advocate_id']);
                $update_data=array('next_dt' => $case_next_date);
                $update_case_consent_next_date_result=$this->consent_model->update('physical_hearing_advocate_consent',$update_data,$consent_table_update_where_condition);
            } else {
                continue;
            }
        }
    }

}