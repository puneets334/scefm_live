<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class FinalSubmit extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('common/Common_model');
        $this->load->model('shareDoc/Share_docs_model');
        //$this->load->library('encrypt');
        $this->load->library('encryption');



    }

    public function index() {

        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_DEPARTMENT);

        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            redirect('dashboard');
            exit(0);
        }

        if (empty($_SESSION['efiling_details']) || empty($_SESSION['estab_details'])) {
            redirect('dashboard');
            exit(0);
        }

        $registration_id = $_SESSION['efiling_details']['registration_id'];

        if ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_DEFICIT_COURT_FEE && (bool) $_SESSION['estab_details']['enable_payment_gateway']) {
            $next_stage = Transfer_to_IB_Stage;
        } elseif ($_SESSION['login']['ref_m_usertype_id'] == USER_DEPARTMENT || $_SESSION['login']['ref_m_usertype_id'] == USER_CLERK) {
            $next_stage = Draft_Stage;
        } elseif ($_SESSION['efiling_details']['stage_id'] == Draft_Stage) {
            $next_stage = Initial_Approaval_Pending_Stage;
        } elseif ($_SESSION['efiling_details']['stage_id'] == Initial_Defected_Stage) {
            $next_stage = Initial_Defects_Cured_Stage;
        } elseif ($_SESSION['efiling_details']['stage_id'] == DEFICIT_COURT_FEE) {
            $next_stage = DEFICIT_COURT_FEE_PAID;
        } elseif ($_SESSION['efiling_details']['stage_id'] == I_B_Defected_Stage) {
            $next_stage = I_B_Defects_Cured_Stage;
        } elseif ($_SESSION['efiling_details']['stage_id'] == I_B_Rejected_Stage || $_SESSION['efiling_details']['stage_id'] == E_REJECTED_STAGE) {
            $next_stage = Initial_Defects_Cured_Stage;
        } else {
            $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Invalid Action.</div>');
            redirect('dashboard');
        }

        $final_submit = TRUE;
        if ($final_submit) {
            $result = $this->Common_model->updateCaseStatus($registration_id, $next_stage);
            if ($result) {
                $next_stage=REFILED_OLD_EFILING_CASE;
                $result1 = $this->Common_model->updateCaseStatus($registration_id, $next_stage);
                if($result1)
                {
                   
                        $diary_id = $_SESSION['efiling_details']['diary_no'] . $_SESSION['efiling_details']['diary_year'];
                        $efiling_no = $_SESSION['efiling_details']['efiling_no'];
                        $this->autoTransferRefiledOldEfilingCaseDocumentsToICMIS($registration_id,$_SESSION['efiling_details']['diary_no'],$_SESSION['efiling_details']['diary_year'],$_SESSION['efiling_details']['efiling_no'],$_SESSION['login']['aor_code']);

                }
                $user_name = $_SESSION['login']['first_name'] . ' ' . $_SESSION['login']['last_name'];

                //code added on 27 jan 21 to get diary num for mesg
                $diary_no=$this->Common_model->get_Diary_no_forMsg($registration_id);
                $diaryno=$diary_no[0]["diaryno"];

                //Code added on 25 jan 21 to send proper message to users other than login user.

                //XXXXXXXXXXXXXXX START 23-03-2021 XXXXXXXXXXXX
                if (!empty($registration_id)) {
                    $efiled_docs_list_mail = $this->Common_model->get_index_items_list_mail($registration_id);
                } else {
                    $efiled_docs_list_mail = NULL;
                }

                if (!empty($efiled_docs_list_mail)) {

                    foreach ($efiled_docs_list_mail as $doc_list) {

                        /*$index_data .= '
                                        <a href="' . base_url('documentIndex/viewIndexItem/' . url_encryption($doc_list['doc_id'].'##'.'pdfmail')) . '" target="_blank">' . $doc_list['doc_title'] . '<br>( ' . $doc_list['docdesc'] . ')</a> ';*/

                        $key=$this->config->item( 'encryption_key' );
                        $msg = $doc_list['doc_id'];

                        //$encrypted_string = $this->encrypt->encode($msg , $key);
                        $encrypted_string = $this->encryption->encrypt($msg);

                        $index_data .= '
                                        <a href="' . base_url('documentIndex/WithoutLoginViewIndex/' . $encrypted_string) . '" target="_blank">' . $doc_list['doc_title'] . '<br>( ' . $doc_list['docdesc'] . ')</a> ';



                        /*$index_data .= '
                                        <a href="' . base_url('documentIndex/WithoutLoginViewIndex/' . $doc_list['doc_id']) . '" target="_blank">' . $doc_list['doc_title'] . '<br>( ' . $doc_list['docdesc'] . ')</a> ';*/

                    }
                } else {
                    /*$index_data .= '<tr><td colspan="4" class="text-center">No record found.</td></tr>';*/
                    $index_data .= 'No record found.';
                }

                //XXXXXXXXXXXXXXXX END XXXXXXXXXXXX


                $sentSMS .= "Document have been filed in Diary No. $diaryno as Efiling no. ". efile_preview($_SESSION['efiling_details']['efiling_no']) . " 
by Advocate $user_name  and is pending for initial approval with efiling admin. - Supreme Court of India";
                log_message('CUSTOM', "Document have been filed in Diary No. $diaryno as Efiling no. ". efile_preview($_SESSION['efiling_details']['efiling_no']) . " 
by Advocate $user_name  and is pending for initial approval with efiling admin. - Supreme Court of India");

                $sentSMS .= '<br>';

                $sentSMS .=  'Folowing Details Are '.' ' .  $index_data;

                  //till here

                $subject = "Submitted : Efiling no. " . efile_preview($_SESSION['efiling_details']['efiling_no']);


                $share_emails = $this->Common_model->get_share_email_details($registration_id);

                if (!empty($share_emails)) {
                    foreach ($share_emails as $emails) {

                        //send_mobile_sms($emails['email'], $sentSMS,SCISMS_Document_Filed_In_Diary);

                        $send_email=send_mail_msg($emails['email'], $subject, $sentSMS, ucwords($emails['name']));
                        //if($send_email=='success'){
                            $doc_email_updated = array(
                                'id' => $emails['id'],
                                'sent_on_mail' => date('Y-m-d H:i:s'),
                                'sent_on_mail_flag' => 'T'
                            );
                            $share_emails_updated = $this->Share_docs_model->add_doc_share_email_updated($emails['id'],$doc_email_updated);
                        //}
                    }
                }
                //Code to sent mail to login user
                $sentSMS = "Efiling no. " . efile_preview($_SESSION['efiling_details']['efiling_no']) . " has been submitted and is pending for initial approval with efiling admin. - Supreme Court of India";
                log_message('CUSTOM', "Efiling no. " . efile_preview($_SESSION['efiling_details']['efiling_no']) . " has been submitted and is pending for initial approval with efiling admin. - Supreme Court of India");
                send_mobile_sms($_SESSION['login']['mobile_number'], $sentSMS,SCISMS_Initial_Approval);
                send_mail_msg($_SESSION['login']['emailid'], $subject, $sentSMS, $user_name);

                $this->session->set_flashdata('msg', '<div class="alert alert-success text-center"> E-filing number ' . efile_preview($_SESSION['efiling_details']['efiling_no']) . ' submitted successfully for approval of E-filing Admin.!</div>');
                $_SESSION['efiling_details']['stage_id']=Initial_Approaval_Pending_Stage;
                redirect('miscellaneous_docs/view');
                //redirect('dashboard');
                exit(0);
            } else {
                $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Submition failed. Please try again!</div>');
                log_message('CUSTOM', "Miscellaneous Docs- Submition failed. Please try again!");
                redirect('miscellaneous_docs/view');
                //redirect('dashboard');
                exit(0);
            }
        }
    }
public function test(){
    $registration_id = $_SESSION['efiling_details']['registration_id'];

    $diary_id = $_SESSION['efiling_details']['diary_no'] . $_SESSION['efiling_details']['diary_year'];
    $efiling_no = $_SESSION['efiling_details']['efiling_no'];
    $result=$this->autoTransferRefiledOldEfilingCaseDocumentsToICMIS($registration_id,$_SESSION['efiling_details']['diary_no'],$_SESSION['efiling_details']['diary_year'],$_SESSION['efiling_details']['efiling_no'],$_SESSION['login']['aor_code']);

}
    // Auto Transfer to scrutiny changes : start
    public function autoTransferRefiledOldEfilingCaseDocumentsToICMIS($registration_id=null,$diary_no=null,$diary_year=null,$efiling_no=null,$refiled_by_aor_code=null)
    {
        if(empty($registration_id))
        {
            return NULL;
        }
        else{
// Initialize a CURL session.
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => ADMIN_SERVER_URL.'newcase/AutoDiaryGeneration/updateOldEfilingRefiledCase',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array('registration_id' => $registration_id,'diary_no'=>$diary_no,'diary_year'=>$diary_year,'efiling_no'=>$efiling_no,'refiled_by_aor_code'=>$refiled_by_aor_code)
            ));



           /* $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => ADMIN_SERVER_URL.'newcase/AutoDiaryGeneration/updateOldEfilingRefiledCase',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array('registration_id' => $registration_id,'diary_no'=>$diary_no,'diary_year'=>$diary_year,'efiling_no'=>$efiling_no,'refiled_by_aor_code'=>$refiled_by_aor_code)
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            $response=(array)json_decode($response,true);
            var_dump($response);exit();*/



            $response = curl_exec($curl);
            curl_close($curl);
            $response=json_decode($response,true);
            if($response['status'] == 'success')
            {
                $this->session->set_flashdata('msg', '<div style="font-size=24px;font-weight: bolder" class="alert alert-success text-center font-weight-bold"> E-filing number ' . $efiling_no. ' has been re-filed successfully.</div>');
                $_SESSION['efiling_details']['stage_id'] = Initial_Approaval_Pending_Stage;
            }
            else
            {
                $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center text-bg">' . $response['message'] . '.</div>');

            }

            redirect('oldCaseRefiling/view');
        }
    }
    // Auto Transfer to scrutiny changes: end

}
