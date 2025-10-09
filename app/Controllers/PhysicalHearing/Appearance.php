<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Appearance extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
		if (!isset($_SESSION['loginData']) && empty($_SESSION['loginData'])) {
			redirect('auth');
		}else{
			is_user_status();
		}
        $this->load->helper('common');
        $this->load->helper('encryptdecrypt');
        $this->load->helper('myarray');
        $this->load->helper('curl');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->database('icmis');
        $this->load->model('consent_VC_model');
        $this->load->model('consent_model');
        $this->load->model('hearing_model');
    }



    public function index($court=null){
       /* $nextTuesday = strtotime('next tuesday');  // next tuesday
        $nextMonday = strtotime('next monday');  // entries will be allowed till 1pm on this date
        $next_tuesday_date=date('Y-m-d',$nextTuesday);*/

        //TODO code for get the future first Misc day
        $next_misc_working_date=getNextMiscDayOfHearing();
        $listing_date=array("'$next_misc_working_date'");

        // $listing_date=array('2021-11-09'); //for testing only -comment this line for production

        if(!isset($this->session->loginData)){
            $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Session Expired. Please login again.</div>');
            unset($this->session->loginData);
            redirect('auth');
        }
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

        /*$data['advocate_cases_summary'] = $this->consent_VC_model->getListedAdvocateMatters($this->session->loginData['bar_id'],$listing_date,null,'AppearanceMenu');*/
        $data['advocate_cases_summary'] = $this->consent_VC_model->getListedAdvocateMatters($this->session->loginData['bar_id'],$listing_date,null,null);

        $this->load->view('physical_hearing/menu');

        if(!empty($court)) {
           /*$data['case_list'] = $this->consent_VC_model->getFutureListedMatters($this->session->loginData['bar_id'], $listing_date, $court);*/
            $data['case_list']=$this->consent_VC_model->getFutureListedMatters($this->session->loginData['bar_id'],$listing_date, $court);

            if(!empty($data['case_list']))
                $this->load->view('physical_hearing/v4/case_list', $data);
            else {
                $data['cases']=null;
                $this->load->view('physical_hearing/v4/case_list', $data);
            }
        }
        else
        {
            $data['cases']=null;
            $this->load->view('physical_hearing/v4/appearances_dashboard', $data);
        }

    }


    public  function case_listed_in_daily_status($diary_no)
    {
        $aud_nomination_status=case_listed_in_daily_list_status($diary_no);

        echo $aud_nomination_status;
    }

    public function save(){
        //var_dump($_POST); exit(0);
        extract($_POST);
        foreach($hearingModeConsent as $diary_no=>$aor_consent){
            $aud_consent_status=case_listed_in_daily_list_status($diary_no);
            if($aud_consent_status==1 || 1)
            {
                echo
                $already_updated_data=$this->consent_VC_model->getAORConsentDetails($diary_no,$next_date[$diary_no],$roster_id[$diary_no],$this->session->loginData['bar_id'], $court_no);

                $condition=array('diary_no' => $diary_no,'next_dt' => $next_date[$diary_no], 'roster_id' => $roster_id[$diary_no], 'court_no'=>$court_no, 'advocate_id'=>$this->session->loginData['bar_id']);
                if(count($already_updated_data)>0){
                    //move current log into log tbale
                    $this->db->trans_begin();
                    //var_dump($already_updated_data);
                    if(!empty($already_updated_data))
                    {
                        $insert_result=$this->consent_VC_model->InsertLog("physical_hearing_advocate_vc_consent","physical_hearing_advocate_vc_consent_log",$diary_no,$next_date[$diary_no],$roster_id[$diary_no], $court_no, $this->session->loginData['bar_id']);
                        //echo $insert_result;
                    }

                    /*if($aor_consent=='V' && $already_updated_data[0]['consent']=='P'){
                        //Send SMS & Email only if consent was changed from P to V. If consent was previously V and again updated as V in that case SMS/Email will not be sent.
                        $this->status_change_info_to_nominees($already_updated_data);
                    }*/

                    //Update
                    $data=array(
                        'updated_by' => $this->session->loginData['bar_id'],
                        'consent' => $aor_consent,
                        'updated_from_ip' => get_client_ip(),
                        'updated_on' => date('Y-m-d H:i:s'),
                        'advocate_id' => $this->session->loginData['bar_id'],
                        'consent_for_diary_nos' => $consent_for_diary_nos[$diary_no],
                        'case_count' => $case_count[$diary_no]
                    );
                    $update_result=$this->consent_VC_model->update('physical_hearing_advocate_vc_consent',$data,$condition);

                    if($this->db->trans_status() === FALSE || !isset($insert_result) || !isset($update_result)){
                        $this->db->trans_rollback();
                    }else{
                        $this->db->trans_commit();
                    }
                }
                else{
                    //Insert
                    $data=array(
                        'updated_by' => $this->session->loginData['bar_id'],
                        'diary_no' => $diary_no,
                        'next_dt' => $next_date[$diary_no],
                        'roster_id' => $roster_id[$diary_no],
                        'consent' => $aor_consent,
                        'updated_from_ip' => get_client_ip(),
                        'updated_on' => date('Y-m-d H:i:s'),
                        'advocate_id' => $this->session->loginData['bar_id'],
                        'court_no' => $court_no,
                        'consent_for_diary_nos' => $consent_for_diary_nos[$diary_no],
                        'case_count' => $case_count[$diary_no],
                    );


                    $this->consent_VC_model->save('physical_hearing_advocate_vc_consent',$data);
                    /* if($aor_consent=='V'){
                         //Send SMS & Email only if consent was changed from P to V.
                         $this->status_change_info_to_nominees(array('diary_no'=>$diary_no,'list_number'=>$list_number,'list_year'=>$list_year,'court_no'=>$court_no));
                     }*/
                }

            }
            else
            {
                $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center text-bold">You cannot add/modify Consent after 8 am of hearing date of Diary No.'.$diary_no.'</div>');
                redirect("Consent");
            }
        }
        $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center text-bold">Consent Updated Successfully</div>');
        redirect("Consent_VC");
    }

    function send_final_opt_vc_list_email() {
        $today = date("Y-m-d");
        $next_date_regular_hearing_listed_case=$this->getTomorrowRegularDailyListCases($today);

        $mail_response='';
        if(!empty($next_date_regular_hearing_listed_case)) {
            $this->update_next_date_in_attendee_list_and_consent(); // update the next date if blank
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

                $url=base_url().'index.php/attendee/printAttendeeFinalReport';
                $fileData[] = array( 'url'=>$url);
                $pdf_content=json_encode($fileData);

                $mail_response=send_email(EMAIL_TO_CONCERN, $title, $message, 1,rawurlencode($pdf_content)).' - Attendee details';
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
        send_sms(SMS_TO_CONCERN, "Final List response: ".$mail_response);
    }
}

