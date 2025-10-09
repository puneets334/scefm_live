<?php
// defined('BASEPATH') OR exit('No direct script access allowed');

namespace App\Controllers\PhysicalHearing;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\PhysicalHearing\ConsentVCModel;
use App\Models\PhysicalHearing\ConsentModel;
use App\Models\PhysicalHearing\HearingModel;
// use App\Models\PutModel;

class Consent extends BaseController {

    protected $consent_VC_model;
    protected $consent_model;
    protected $hearing_model;

    public function __construct()
    {
        parent::__construct();
		// if (!isset($_SESSION['loginData']) && empty($_SESSION['loginData'])) {
		// 	redirect('auth');
		// }else{
		// 	is_user_status();
		// }
        // $this->load->helper('common');
        // $this->load->helper('encryptdecrypt');
        // $this->load->helper('myarray');
        // $this->load->helper('curl');
        // $this->load->helper('url');
        // $this->load->helper('form');
        // $this->load->database('icmis');
        // $this->load->model('consent_model');
        // $this->load->model('hearing_model');
        helper(['common', 'encryptdecrypt', 'myarray', 'curl', 'url', 'form', 'session']);
        $this->consent_VC_model = new ConsentVCModel();
        $this->consent_model = new ConsentModel();
        $this->hearing_model = new HearingModel();
        $session = session();
    }

    public function index($court=null)
    {
        // echo $court;
        // if(!isset($this->session->loginData)){
        //     $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Session Expired. Please login again.</div>');
        //     unset($this->session->loginData);
        //     redirect('auth');
        // }
        $data['page_title']='Choose mode of Hearing';
        //$unfreezed_court = $this->hearing_model->unfreezed_court_list();
        $unfreezed_court = $this->hearing_model->available_court_list_in_weekly();
        $freezed_court = $this->hearing_model->freezed_court_list();
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
        $data['weekly_list'] = $this->hearing_model->weekly_list_number();
        $data['advocate_cases_summary'] = $this->hearing_model->getListedAdvocateMatters($this->session->loginData['bar_id']);
        if(!empty($court))
            $data['cases']=$this->hearing_model->getFutureListedMatters($this->session->loginData['bar_id'], $court);
        else
            //$data['cases']=$this->hearing_model->getFutureListedMatters($this->session->loginData['bar_id'], null);//for all court option
            $data['cases']=null;
        // $data['cases']=null;
        // $this->load->view('physical_hearing/menu');
        $this->render('physical_hearing.consent', $data);
    }

    public  function case_listed_in_daily_status($diary_no)
    {
       $aud_nomination_status=case_listed_in_daily_list_status($diary_no);
       echo $aud_nomination_status;
    }

    public function save()
    {
        extract($_POST);
        // echo $_POST['row_20'];
        // var_dump($hearingModeConsent);
        // echo $selected_court;
        $cases=$this->hearing_model->getFutureListedMatters($this->session->loginData['bar_id'],$court_no);
        $list_number=$cases[0]['list_number'];
        $list_year=$cases[0]['list_year'];
        $court_no=$cases[0]['court_no'];
        //echo $list_number.'#'.$list_year;
        foreach($hearingModeConsent as $diary_no=>$aor_consent){
            //$data=explode('_',$aor_consent);
            //echo 'Diary No:'.$diary_no.' value : '.$aor_consent;
            $aud_consent_status=case_listed_in_daily_list_status($diary_no);
            $case_next_dt=case_listed_in_daily_list_next_date($diary_no);
            if($aud_consent_status==1) {
                $already_updated_data=$this->consent_model->getAORConsentDetails($diary_no,$list_number,$list_year,$this->session->loginData['bar_id'], $court_no);
                $condition=array('diary_no' => $diary_no,'list_number' => $list_number, 'list_year' => $list_year, 'court_no'=>$court_no, 'advocate_id'=>$this->session->loginData['bar_id']);
                if(count($already_updated_data)>0){
                    //move current log into log tbale
                    $this->db->trans_begin();
                    //var_dump($already_updated_data);
                    if(!empty($already_updated_data))
                    {
                        $insert_result=$this->consent_model->InsertLog("physical_hearing_advocate_consent","physical_hearing_advocate_consent_log",$diary_no,$list_number,$list_year, $court_no);
                        //echo $insert_result;
                    }
                    if($aor_consent=='V' && $already_updated_data[0]['consent']=='P'){
                        //Send SMS & Email only if consent was changed from P to V. If consent was previously V and again updated as V in that case SMS/Email will not be sent.
                        $this->status_change_info_to_nominees($already_updated_data);
                    }
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
                    $update_result=$this->consent_model->update('physical_hearing_advocate_consent',$data,$condition);
                    if($this->db->transStatus() === FALSE || !isset($insert_result) || !isset($update_result)){
                        $this->db->transRollback();
                    } else {
                        $this->db->transCommit();
                    }
                } else {
                    //Insert
                    $data=array(
                        'updated_by' => $this->session->loginData['bar_id'],
                        'diary_no' => $diary_no,
                        'list_number' => $list_number,
                        'list_year' => $list_year,
                        'consent' => $aor_consent,
                        'updated_from_ip' => get_client_ip(),
                        'updated_on' => date('Y-m-d H:i:s'),
                        'advocate_id' => $this->session->loginData['bar_id'],
                        'court_no' => $court_no,
                        'consent_for_diary_nos' => $consent_for_diary_nos[$diary_no],
                        'case_count' => $case_count[$diary_no],
                        'next_dt' =>$case_next_dt
                    );
                    $this->consent_model->save('physical_hearing_advocate_consent',$data);
                    /* if($aor_consent=='V'){
                        //Send SMS & Email only if consent was changed from P to V.
                        $this->status_change_info_to_nominees(array('diary_no'=>$diary_no,'list_number'=>$list_number,'list_year'=>$list_year,'court_no'=>$court_no));
                    }*/
                }
            } else {
                $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center text-bold">You cannot add/modify Consent after 8 am of hearing date of Diary No.'.$diary_no.'</div>');
                return redirect()->to(base_url("Consent"));
            }
        }
        $this->session->setFlashdata('msg', '<div class="alert alert-danger text-center text-bold">Consent Updated Successfully</div>');
        return redirect()->to(base_url("Consent"));
    }

    private function status_change_info_to_nominees($already_updated_data)
    {
        $diary_no = $already_updated_data[0]['diary_no'];
        $list_number = $already_updated_data[0]['list_number'];
        $list_year = $already_updated_data[0]['list_year'];
        $court_no = $already_updated_data[0]['court_no'];
        $attendees = $this->hearing_model->get_master('attendee_details', "diary_no='$diary_no' and
            list_number=$list_number and
            list_year=$list_year and
            court_no=$court_no and
            display='Y' and ref_attendee_type_id in (1,2,3,4,9)");
        $message = "Your physical presence in Case No. ".$attendees[0]['case_number']." is no more valid. -Supreme Court of India";
        foreach ($attendees as $attendee){
            send_sms($attendee['mobile'], $message, INVALID_PYSICAL_APPEARANCE);
            send_email($attendee['email_id'], "Regarding physical hearing for ".$attendee['case_number'], $message);
        }
    }

}