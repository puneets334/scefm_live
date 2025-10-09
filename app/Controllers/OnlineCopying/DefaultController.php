<?php
namespace App\Controllers\OnlineCopying;
use App\Controllers\BaseController;
use App\Models\OnlineCopying\CommonModel;
use Config\Database;
use App\Libraries\webservices\Ecoping_webservices;
use TCPDF;

class DefaultController extends BaseController
{

    protected $session;
    protected $Common_model;
    protected $db2;
    protected $db3;
    protected $ecoping_webservices;

    public function __construct()
    {
        parent::__construct();
        $this->session = \Config\Services::session();
        if (!empty($this->session->get('login')) && ($this->session->get('login')['ref_m_usertype_id'] != AUTHENTICATED_BY_AOR || $this->session->get('login')['ref_m_usertype_id'] != ARGUING_COUNSEL)) {
            
            if (empty($this->session->get('login'))) {
                return response()->redirect(base_url('/'));
            } 
            // else {
            //     is_user_status();
            // }
        }
        $this->ecoping_webservices=new Ecoping_webservices();        
        $this->Common_model = new CommonModel();        
        
        $this->checkUserofECopying();

        unset($_SESSION['MSG']);
        unset($_SESSION['msg']);
    }

    public function copySearch()
    {           
        if (empty($this->session->get('login'))) {
            return response()->redirect(base_url('/'));
        } 
        // else {
        //     is_user_status();
        // }
        if (!empty($this->session->get('login')) && ($this->session->get('login')['ref_m_usertype_id'] != AUTHENTICATED_BY_AOR || $this->session->get('login')['ref_m_usertype_id'] != ARGUING_COUNSEL)) {
            if(empty(getSessionData('login'))) {
                return response()->redirect(base_url('/')); 
            } 
            // else {
            //     is_user_status();
            // }
        }
        $category=$this->ecoping_webservices->getCategory();
        return $this->render('onlineCopying.copy_search', compact('category'));
    }   
    
    public function getCopySearch()
    {
        $track_horizonal_timeline = array();
        $disposed_flag = array('F', 'R', 'D', 'C', 'W');
        $preparedArray = [];
        $flag = $this->request->getVar('flag');
        $results=$this->ecoping_webservices->geteCopySearch($this->request->getVar('flag'),$this->request->getVar('crn'),$this->request->getVar('application_type'),$this->request->getVar('application_no'),$this->request->getVar('application_year'));        
        return $this->render('onlineCopying.get_copy_search', compact('results'));
    }
    
    public function trackConsignment()
    {            
        if (empty($this->session->get('login'))) {
            return response()->redirect(base_url('/'));
        } 
        // else {
        //     is_user_status();
        // }
        return $this->render('onlineCopying.track');
    }

    public function getConsignmentStatus()
    {
        return $this->render('onlineCopying.get_consignment_status');
    }

    public function faq()
    {            
        if (empty($this->session->get('login'))) {
            return response()->redirect(base_url('/'));
        } 
        // else {
        //     is_user_status();
        // }
        if (!empty($this->session->get('login')) && ($this->session->get('login')['ref_m_usertype_id'] != AUTHENTICATED_BY_AOR || $this->session->get('login')['ref_m_usertype_id'] != ARGUING_COUNSEL)) {
            if(empty(getSessionData('login'))) {
                return response()->redirect(base_url('/')); 
            } 
            // else {
            //     is_user_status();
            // }
        }
        $faqs =$this->ecoping_webservices->copyFaq();        
        return $this->render('onlineCopying.faq', compact('faqs'));
    }

    public function screenReader()
    {            
        if (empty($this->session->get('login'))) {
            return response()->redirect(base_url('/'));
        } 
        // else {
        //     is_user_status();
        // }
        return $this->render('onlineCopying.screen_reader');
    }

    public function contactUs()
    {            
        if (empty($this->session->get('login'))) {
            return response()->redirect(base_url('/'));
        } 
        // else {
        //     is_user_status();
        // }
        return $this->render('onlineCopying.contact_us');
    }

    public function caseSearch()
    {            
        if (empty($this->session->get('login'))) {
            return response()->redirect(base_url('/'));
        } 
        // else {
        //     is_user_status();
        // }
        if (!empty($this->session->get('login')) && ($this->session->get('login')['ref_m_usertype_id'] != AUTHENTICATED_BY_AOR || $this->session->get('login')['ref_m_usertype_id'] != ARGUING_COUNSEL)) {
            if(empty(getSessionData('login'))) {
                return response()->redirect(base_url('/')); 
            } 
            // else {
            //     is_user_status();
            // }
        }
        $caseType = $this->ecoping_webservices->getCaseType();
        return $this->render('onlineCopying.case_search', compact('caseType'));
    }

    public function getCaseDetails()
    {       
        return $this->render('onlineCopying.get_case_details');
    }

    public function getAppCharge()
    {        
        $r_sql = $this->ecoping_webservices->getCatogoryForApplication($_REQUEST['idd']);        
        $app_rule='';
        if(!empty($r_sql)){
            if($r_sql->urgent_fee!=0) {
                $app_rule=$app_rule.$r_sql->urgent_fee.'/- urgency fees + ';
            }
            if($r_sql->per_certification_fee!=0) {
                $app_rule=$app_rule.$r_sql->per_certification_fee.'/- per certification + ';
            }
            if(isset($_SESSION["session_filed"]) && $_SESSION["session_filed"] == 4 && $_REQUEST['idd'] != 5) {
                $app_rule=$app_rule.'5/- (third party charges) + ';
            }
            $app_rule=$app_rule.$r_sql->per_page.'/- per page';
            $app='';
            if($r_sql->id==5) {
                $app= " <span class='font-weight-bold text-info'>First copy free of cost, thereafter - </span>";
            }
            return $app."Rs. ".$app_rule;
        }
        return "Please select application category. ";
    }

    public function getTotCopy()
    {
        return $this->render('onlineCopying.get_tot_copy');
    }

    public function unavailableRequest()
    {
        return $this->render('onlineCopying.unavailable_request');
    }

    public function requestedDocumentsSave()
    {
        $doc_array = array();
        if(!empty($_POST['document_type'])) {
            for($i = 0; $i < count($_POST['document_type']); $i++) {
                if(!empty($_POST['document_type'][$i])) {
                    if($_POST['mandate_date_of_order_type'][$i] == 'Y' && empty($_POST['order_date'][$i])) {
                        $array = array('status' => $_POST['document_type_text'][$i].' - Empty Order Date Not Allowed.');
                        echo json_encode($array);
                        exit();
                    } else if($_POST['mandate_date_of_order_type'][$i] == 'Y' && !empty($_POST['order_date'][$i]) && date('Y-m-d', strtotime($_POST['order_date'][$i])) > date('Y-m-d')) {
                        $array = array('status' => $_POST['document_type_text'][$i].' - Wrong Order Date Not Allowed.');
                        echo json_encode($array);
                        exit();
                    } else if($_POST['mandate_remark_of_order_type'][$i] == 'Y' && empty($_POST['doc_detail'][$i])) {
                        $array = array('status' => $_POST['document_type_text'][$i].' - Empty Document detail Not Allowed.');
                        echo json_encode($array);
                        exit();
                    } else {
                        $doc_array[] = array("document_type" => $_POST['document_type'][$i], "order_date" => $_POST['order_date'][$i], "doc_detail" => $_POST['doc_detail'][$i]);
                    }
                }
            }
        }
        if($_SESSION['max_unavailable_copy_request_per_day'] >=10){
            $array = array('status' => 'Max 10 unavailable document request reached per day.');
            exit();
        }
        if (!empty($_POST) && $_SESSION['is_token_matched'] == 'Yes' && isset($_SESSION["applicant_email"]) && isset($_SESSION["applicant_mobile"])) {
            $diary_no=$_SESSION['session_d_no'].$_SESSION['session_d_year'];
            // VERIFICATION OF CASE ALREADY APPLIED
            $result=$this->ecoping_webservices->getCopyingRequestVerify($diary_no,$_SESSION["applicant_mobile"]);
            if ((is_array($result) && count($result) > 0) || (isset($_SESSION['unavailable_copy_requested_diary_no']) && $_SESSION['unavailable_copy_requested_diary_no'] == $diary_no)) {
                $array = array('status' => 'Please wait till completion of your previous request.');
            } else {
                if (isset($_SESSION['user_address'])) {
                    foreach ($_SESSION['user_address'] as $data_address) {
                        $address_id = $data_address['id'];
                        $first_name = $data_address['first_name'];
                        $second_name = $data_address['second_name'];
                        $postal_add = $data_address['address'];
                        $city = $data_address['city'];
                        $district = $data_address['district'];
                        $state = $data_address['state'];
                        $country = $data_address['country'];
                        $pincode = $data_address['pincode'];
                        break;
                    }
                }
                $allowed_request = "request_to_available";
                $scipay = 10002;
                $create_crn =$this->ecoping_webservices->createCRN($scipay);//for unavailable document request
                $json_crn = $create_crn;
                if ($json_crn->{'Status'} == "success") {
                    $crn = $json_crn->{'CRN'};
                    $dataArray = array(
                        "diary" => $_SESSION['session_d_no'] . $_SESSION['session_d_year'],
                        "copy_category" => '0',
                        "application_reg_number" => '0',
                        "application_reg_year" => '0',
                        "application_receipt" => date('Y-m-d H:i:s'),
                        "advocate_or_party" => '0',
                        "court_fee" => '0',
                        "delivery_mode" => "1",
                        "postal_fee" => '0',
                        "ready_date" => '',
                        "dispatch_delivery_date" => '',
                        "adm_updated_by" => '1',
                        "updated_on" => date('Y-m-d H:i:s'),
                        "is_deleted" => "0",
                        "is_id_checked" => '',
                        "purpose" => '',
                        "application_status" => 'P',
                        "defect_code" => '',
                        "defect_description" => '',
                        "notification_date" => '',
                        "filed_by" => isset($_SESSION["session_filed"]) ? $_SESSION["session_filed"] : '',
                        "name" => $first_name . ' ' . $second_name,
                        "mobile" => $_SESSION["applicant_mobile"],
                        "address" => $postal_add . ', ' . $city . ', ' . $district . ', ' . $state . ', ' . $country . ' - ' . $pincode,
                        "application_number_display" => '',
                        "temp_id" => '',
                        "remarks" => '',
                        "source" => '6',
                        "send_to_section" => 'f',
                        "crn" => $crn,
                        "email" => $_SESSION["applicant_email"],
                        "authorized_by_aor" => (isset($_SESSION['session_authorized_bar_id']) && $_SESSION['session_authorized_bar_id'] > 0) ? $_SESSION['session_authorized_bar_id'] : '0',
                        "allowed_request" => $allowed_request,
                        "token_id" => '',
                        "address_id" => $address_id
                    );
                    $insert_application =$this->ecoping_webservices->insert_copying_application_online($dataArray); // insert application
                    $json_insert_application = $insert_application;
                    if ($json_insert_application->{'Status'} == "success") {
                        $last_application_id = $json_insert_application->{'last_application_id'};
                        for ($var = 0; $var < count($doc_array); $var++) {
                            $document_type = $doc_array[$var]['document_type'];
                            $ordate_date = $doc_array[$var]['order_date'];
                            $doc_details = $doc_array[$var]['doc_detail'];
                            $document_array = array();
                            $document_array = array(
                                'order_type' => $document_type,
                                'order_date' => date('Y-m-d', strtotime($ordate_date)),
                                'copying_order_issuing_application_id' => $last_application_id,
                                'number_of_copies' => '1',
                                'number_of_pages_in_pdf' => '0',
                                'path' => null,
                                'from_page' => null,
                                'to_page' => null,
                                'order_type_remark' => $doc_details,
                                'is_bail_order' => 'N'
                            );
                            $insert_application_documents = $this->ecoping_webservices->insert_copying_application_documents_online($document_array); // insert user assets
                            $json_insert_application_documents =$insert_application_documents;
                            if ($json_insert_application_documents->{'Status'} == "success") {
                                // 
                            } else {
                                $array = array('status' => 'Unable to insert records');
                            }
                        }
                        $sms = "Your request successfully submitted with CRN $crn for reference - Supreme Court Of India";
                        // Your request successfully submitted with CRN {#var#} for reference - Supreme Court Of India
                        $mobile = $_SESSION["applicant_mobile"];
                        // $sms_response = sci_send_sms($mobile, $sms, 'ecop', SCISMS_e_copying_crn_created);
                        // $json = json_decode($sms_response);
                        // if ($json->{'Status'} == "success") {
                        //     $_SESSION['max_unavailable_copy_request_per_day'] = $_SESSION['max_unavailable_copy_request_per_day'] + 1;
                        //     $_SESSION['unavailable_copy_requested_diary_no'] = $diary_no;
                        //     $array = array('status' => 'success');
                        // }
                        $array = array('status' => 'success');
                    } else {
                        $array = array('status' => 'Unable to insert records');
                    }
                } else {
                    $array = array('status' => 'Permission Denied');
                }
            }
            echo json_encode($array);
        }
    }

    /*public function testpdf()
    {
        $parser = new \Smalot\PdfParser\Parser();
        $pdf = $parser->parseFile('http://10.40.186.239:84/filesample_150kB.pdf');        
        $pages = $pdf->getPages();
        echo count($pages);
    }*/

    public function caseRelationVerification()
    {
        //getUserAssets
        $data['ecoping_webservices']=$this->ecoping_webservices;
        return $this->render('onlineCopying.case_relation_verification',$data);
    }

    public function sciRequest()
    {
        $params = session_get_cookie_params();
        setcookie(session_name(), $_COOKIE[session_name()], time() + 60*60*24*30, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
        return $this->render('onlineCopying.sci_request');
    }
    public function sci_response(){
        if(empty($_POST['BharatkoshResponse'])){
           $_POST['BharatkoshResponse'] = 'PD94bWwgdmVyc2lvbj0iMS4wIj8+PHBheW1lbnRTZXJ2aWNlIHZlcnNpb249IjEuMCIgbWVyY2hhbnRDb2RlPSJNRVJDSEFOVCI+PHJlcGx5PjxvcmRlclN0YXR1cyBvcmRlckNvZGU9IkNQMjAyMDA5MjEwMDAwMiIgc3RhdHVzPSJTVUNDRVNTIj48cmVmZXJlbmNlIGlkPSIyMTA5MjAwMDAxMjk0IiBCYW5rVHJhbnNhY3N0aW9uRGF0ZT0iMDkvMjEvMjAyMCAxMToyNjowMSIgVG90YWxBbW91bnQ9IjEiPjwvcmVmZXJlbmNlPjwvb3JkZXJTdGF0dXM+PC9yZXBseT48U2lnbmF0dXJlIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwLzA5L3htbGRzaWcjIj48U2lnbmVkSW5mbz48Q2Fub25pY2FsaXphdGlvbk1ldGhvZCBBbGdvcml0aG09Imh0dHA6Ly93d3cudzMub3JnL1RSLzIwMDEvUkVDLXhtbC1jMTRuLTIwMDEwMzE1IiAvPjxTaWduYXR1cmVNZXRob2QgQWxnb3JpdGhtPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwLzA5L3htbGRzaWcjcnNhLXNoYTEiIC8+PFJlZmVyZW5jZSBVUkk9IiI+PFRyYW5zZm9ybXM+PFRyYW5zZm9ybSBBbGdvcml0aG09Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvMDkveG1sZHNpZyNlbnZlbG9wZWQtc2lnbmF0dXJlIiAvPjwvVHJhbnNmb3Jtcz48RGlnZXN0TWV0aG9kIEFsZ29yaXRobT0iaHR0cDovL3d3dy53My5vcmcvMjAwMC8wOS94bWxkc2lnI3NoYTEiIC8+PERpZ2VzdFZhbHVlPmJmeGpZOVA2eTYzL3lOaDRMSTdYTlJaNHZNQT08L0RpZ2VzdFZhbHVlPjwvUmVmZXJlbmNlPjwvU2lnbmVkSW5mbz48U2lnbmF0dXJlVmFsdWU+cnJaaTBBLzVTb0g2WWM2SGw5d3dUUm9HMTZadEd3ZEtaY3lzbFNFTzZPNVhRWlFDN2laenU3YjM1enJ0OEZIUjdVOWhNYW90SCtSZnlURlBaRytZd2VTWW55VnM4dkxyOUFDM3VxbGxoRmRRQ1MrTUVMMXFFK3ZyRjFkZm1EY2pYWnhNcGw2bXFEWWRSU3hzY1dVSWZ5MnBxUWRGdmdJVlN0M1VkQmtEYUJvbEp5VGFvTnJua1NCNldWRWR6MUovSGFVUVI2aHlPSE9XYmhkdkNyUGx2Wks3cFhwOWhLejUzQUZIZUpETzhHT1dPeStqZXdVRS9QdzN5ZGF3OGprYTFEVE8zS01tQk9ReWdYNGtzN0I0Ry9ITEw2eERlYWIxWDgxZFc2LzdMTHcvci9RY3RnSVd5NmZES3dIM0NzemdyU0FleWVRdUF3MGhtQTF4N0duYUF3PT08L1NpZ25hdHVyZVZhbHVlPjxLZXlJbmZvPjxYNTA5RGF0YT48WDUwOUlzc3VlclNlcmlhbD48WDUwOUlzc3Vlck5hbWU+Q049R2VvVHJ1c3QgUlNBIENBIDIwMTgsIE9VPXd3dy5kaWdpY2VydC5jb20sIE89RGlnaUNlcnQgSW5jLCBDPVVTPC9YNTA5SXNzdWVyTmFtZT48WDUwOVNlcmlhbE51bWJlcj4xNTkyMDE2NDUzMjQ3OTU5NDMwMDcyODgwNTAxMTE5MjQ0NTcyMDwvWDUwOVNlcmlhbE51bWJlcj48L1g1MDlJc3N1ZXJTZXJpYWw+PFg1MDlDZXJ0aWZpY2F0ZT5NSUlHcERDQ0JZeWdBd0lCQWdJUUMvb2N0bmhCZE0zYjJtTVY4aFo3R0RBTkJna3Foa2lHOXcwQkFRc0ZBREJlTVFzd0NRWURWUVFHRXdKVlV6RVZNQk1HQTFVRUNoTU1SR2xuYVVObGNuUWdTVzVqTVJrd0Z3WURWUVFMRXhCM2QzY3VaR2xuYVdObGNuUXVZMjl0TVIwd0d3WURWUVFERXhSSFpXOVVjblZ6ZENCU1UwRWdRMEVnTWpBeE9EQWVGdzB4T1RBeU1EY3dNREF3TURCYUZ3MHlNVEF5TURZeE1qQXdNREJhTUlHQU1Rc3dDUVlEVlFRR0V3SkpUakVPTUF3R0ExVUVDQk1GUkdWc2FHa3hFakFRQmdOVkJBY1RDVTVGZHlCRVpXeG9hVEV0TUNzR0ExVUVDaE1rUTI5dWRISnZiR3hsY2lCSFpXNWxjbUZzSUc5bUlFRmpZMjkxYm5SeklDaERSMEVwTVI0d0hBWURWUVFERXhWM2QzY3VZbWhoY21GMGEyOXphQzVuYjNZdWFXNHdnZ0VpTUEwR0NTcUdTSWIzRFFFQkFRVUFBNElCRHdBd2dnRUtBb0lCQVFETFRqdlRadDkzV3QyYnI5S2hHTjFUcmcxdHpqTm5CQ2MvVzZGbWhuT081OUJSTHRPNkdtdm9wOW9WNGJscmcwNm8zbjFsVVdtTXdZQ1BTSFUrb21XRVdvV2thZFZtV1lPNmlyYzBBWHN4ZEhXMHdkVzNPdnFRZzZjYWRQVzVVeGFaM1djQmtPWGNqMkdvR2w1SktlNlBER0FKSWtkQm8xclhJdjlrMXd5SE5waFFjbGlsNVNsUjNEZFdFaWg1MUJBL1NhN3hyRTNGbzhydnhyajR6WGhFM1U5UFdSYTlGYW12ZGVMQUtFU2JPemNaUjE0TmNQV1FlVXkrZ0E4bWVKc2s4SXVoMjRDREJQNHQwYUs3dWFMb3JiWS93S3lzbnMwYmVJcU93Unc5TnQwbDN1amlNZmRnbm1qMXUrTDlPLzMwQmNmMzBIVEtpcWVQTloyQ00zc3pBZ01CQUFHamdnTTVNSUlETlRBZkJnTlZIU01FR0RBV2dCU1FXUCt3bkhXb1VWUjNzZTN5bzBNV09KNXN4VEFkQmdOVkhRNEVGZ1FVNUxLdGh1cWo5NWxNcXZvK2NSckVXVUlMZWdFd013WURWUjBSQkN3d0tvSVZkM2QzTG1Kb1lYSmhkR3R2YzJndVoyOTJMbWx1Z2hGaWFHRnlZWFJyYjNOb0xtZHZkaTVwYmpBT0JnTlZIUThCQWY4RUJBTUNCYUF3SFFZRFZSMGxCQll3RkFZSUt3WUJCUVVIQXdFR0NDc0dBUVVGQndNQ01ENEdBMVVkSHdRM01EVXdNNkF4b0MrR0xXaDBkSEE2THk5alpIQXVaMlZ2ZEhKMWMzUXVZMjl0TDBkbGIxUnlkWE4wVWxOQlEwRXlNREU0TG1OeWJEQk1CZ05WSFNBRVJUQkRNRGNHQ1dDR1NBR0cvV3dCQVRBcU1DZ0dDQ3NHQVFVRkJ3SUJGaHhvZEhSd2N6b3ZMM2QzZHk1a2FXZHBZMlZ5ZEM1amIyMHZRMUJUTUFnR0JtZUJEQUVDQWpCMUJnZ3JCZ0VGQlFjQkFRUnBNR2N3SmdZSUt3WUJCUVVITUFHR0dtaDBkSEE2THk5emRHRjBkWE11WjJWdmRISjFjM1F1WTI5dE1EMEdDQ3NHQVFVRkJ6QUNoakZvZEhSd09pOHZZMkZqWlhKMGN5NW5aVzkwY25WemRDNWpiMjB2UjJWdlZISjFjM1JTVTBGRFFUSXdNVGd1WTNKME1Ba0dBMVVkRXdRQ01BQXdnZ0Y5QmdvckJnRUVBZFo1QWdRQ0JJSUJiUVNDQVdrQlp3QjJBS1M1Q1pDMEdGZ1VoN3NUb3N4bmNBbzhOWmdFK1J2ZnVPTjN6UTdJRGR3UUFBQUJhTWMwa29ZQUFBUURBRWN3UlFJaEFOcnlwdWRCUmtKZFhJN0l1L2Zad3hIZ2ZPZFl6Q2lGMytVUExiMWd3R0gvQWlCRisrUHJuc2RJYXBuMENtdk5IUmZqTzFIQ0xCckFUTlYxSFhuMGZlUDRlZ0IyQUlkMXYrZFpmUGlNUTVsZnZmTnUvMWFOUjFZMi8wcTFZTUcwNnY5ZW9JTVBBQUFCYU1jMGsxb0FBQVFEQUVjd1JRSWhBSnRyZ0JzNURLOUplaE1uSThBaytDUW1KRlNwYmZFUGRKTzVScGlhOWwwTUFpQmtyaytOanIvcXN4OUw2VVZKRlpxUzRrcTJaYUdvc1QyL0RWZjd0TkJDWFFCMUFHOVRkcXd4OERFWjJKa0FwRkVWLzNjVkhCSFpBc0VBS1FhTnNnaWFOOWtUQUFBQmFNYzBrOXNBQUFRREFFWXdSQUlnUFo1TmxaR0hseHFqdU5jNFNxL25GaDYrMElnejJHNktDbGFWYUxBK25rRUNJQXFYOHNVT1pjM1dsZGpoRE9pbFpxNGRDTGRUNFdqT0xkRDRUTGl5L2lrRk1BMEdDU3FHU0liM0RRRUJDd1VBQTRJQkFRQU9heVBXL3g3RkFVdWE0ckU3bE0wMCt0SVV2aURjVGhaQVdpMkJTeVNDRmszU0QzQmhsVVVFZFFaQzZ4b0NrMDdtbkpOaFZuWW9yd1k0bXpRa0luUm5TUndIa1ZVUytxMnRuM1ZTZC9VakNNbmZTTk1NOU0yc2ZiTExXYWtKU09GSk5DdkQwUzBmMWhjb1NqbzlkUExDc3pmQ1Z0N2U5TjJEcERwRXY1dld2Ynd5L0JEUC9UaUZLWTFiUVNGK0xsOXVGQ3JQT09OUG1Pc0xxRkNhVWltZEhrNkk0bUtxRElEN2VQbzdYL0E4dldnalFRNDNQOEdRWVl5b2hmeG9pYyt0OVU0TlRpRHJjVzRHek4vd1U0Q2hETVB6OGxiKysydlZ4cGlTNzh4alhJdG9RTlFObTM3STRuS2dqWXNsV1dRQXh1czhITVZlQlZrWGFpMnEwT0p0PC9YNTA5Q2VydGlmaWNhdGU+PC9YNTA5RGF0YT48L0tleUluZm8+PC9TaWduYXR1cmU+PC9wYXltZW50U2VydmljZT4=';
        }
        $responseString = $_POST['BharatkoshResponse'];
        //echo "111<br><br>";
        $responseStringBase64_decode = base64_decode($responseString);
        if(!empty($_POST['BharatkoshResponse'])){
        $signature_match = bharatKoshResponse($responseStringBase64_decode);
         //$signature_match='Signature validated';
        if($signature_match == 'Signature validated') {
            $xml = simplexml_load_string($responseStringBase64_decode);
            $reply = $xml->reply;
            //var_dump($reply);
            //print_r($reply);
            //print_r($_SESSION['CRN']);
            //die;
            $orderStatus = $reply[0]->orderStatus;
            $orderCode = $orderStatus->attributes()->orderCode;
            // (string)$orderCode[0] = 'CP2025082200026';
            $orderStatus = $orderStatus->attributes()->status;
            $reference = $reply[0]->orderStatus->reference;
            $referenceID = $reference->attributes()->id;
            $BankTransacstionDate = $reference->attributes()->BankTransacstionDate;
            $TotalAmount = $reference->attributes()->TotalAmount[0];
            //echo $reference->attributes()->TotalAmount[0];
            //die;
            $key_master = substr((string)$orderCode[0], 0, 2);
            // $data=[
            //     "orderCode" => $orderCode,
            //     "orderStatus" => "$orderStatus",
            //     "referenceID" => "$referenceID",
            //     "bankTransactionDate" => "$BankTransacstionDate",
            //     "response_amount" => $TotalAmount == 0 ? '0.00' : $TotalAmount
            // ];
            $data=[
                "orderCode" => (string)$orderCode[0],
                "orderStatus" => "$orderStatus",
                "referenceID" => "$referenceID",
                "bankTransactionDate" => "$BankTransacstionDate",
                "response_amount" => $TotalAmount == 0 ? '0.00' : (string)$TotalAmount[0]
            ];

            // $data=[
            //     "orderCode" => 'CP2025082200026',
            //     "orderStatus" => "SUCCESS",
            //     "referenceID" => "2208250141636",
            //     "bankTransactionDate" => "08/22/2025 16:25:56",
            //     "response_amount" => '33.00'
            // ];
        $service_row_count=getBharakoshPaymentStatus($data);
        if (count($service_row_count) ==0) {
        if (bharatkoshSaveStatus($data)) {
            $data=getBharatKoshRequest((string)$orderCode[0]);

            $select_statement_row_count =count($data);
            $data=(is_array($data) && !empty($data)) ? $data[0] : '';
            $orderStatus = $orderStatus;
            $referenceID = $referenceID;
            $orderDate = !empty($data) ? $data->entry_time : '';
            $OrderBatchTotalAmounts = !empty($data) ? $data->order_batch_total_amount : '';
            $ShippingFirstName = !empty($data) ? $data->shipping_first_name : '';
            $ShippingLastName = !empty($data) ? $data->shipping_last_name : '';
            $ShippingAddress1 = !empty($data) ? $data->shipping_address1 : '';
            $ShippingAddress2 = !empty($data) ? $data->shipping_address2 : '';
            $ShippingPostalCode = !empty($data) ? $data->shipping_postal_code : '';
            $ShippingCity = !empty($data) ? $data->shipping_city : '';
            $ShippingStateRegion = !empty($data) ? $data->shipping_state_region : '';
            $ShippingState = !empty($data) ? $data->shipping_state : '';
            $ShippingCountryCode = !empty($data) ? $data->shipping_country_code : '';
            $ShippingMobileNumber = !empty($data) ? $data->shipping_mobile_number : '';
            $ShopperEmailAddress = !empty($data) ? $data->shopper_email_address : '';
            $application_id = !empty($data) ? $data->application_id : '';
            //echo $data->application_id;
            //die;
        if($select_statement_row_count > 0) {
            if($key_master =='RT') {
            return $this->render('onlineCopying.sci_response',$data);
            }
            if ($orderStatus == 'SUCCESS'){
                // $orderCode=$_SESSION['CRN'];
                $orderCode=$data->order_batch_merchant_batch_code;
                $copyingDetails = getCopyingDetails($orderCode);
                $diary_no = $copyingDetails[0];
                $required_document = $copyingDetails[1];
                $sms_text = "eCopying charges Rs. $TotalAmount received successfully. CRN $orderCode allotted for your reference. - Supreme Court Of India";
                $retData['orderStatus'] = (string)$orderStatus;
                $retData['OrderBatchTotalAmounts'] = $OrderBatchTotalAmounts;
                $retData['orderCode'] = $orderCode;
                $retData['ShippingMobileNumber'] = $ShippingMobileNumber;
                $retData['ShopperEmailAddress'] = $ShopperEmailAddress;
                $retData['ShippingFirstName'] = $ShippingFirstName;
                $retData['ShippingLastName'] = $ShippingLastName;
                $retData['ShippingAddress1'] = $ShippingAddress1;
                $retData['ShippingAddress2'] = $ShippingAddress2;
                $retData['ShippingCity'] = $ShippingCity;
                $retData['ShippingStateRegion'] = $ShippingStateRegion;
                $retData['ShippingState'] = $ShippingState;
                $retData['ShippingPostalCode'] = $ShippingPostalCode;
                $retData['ShippingCountryCode'] = $ShippingCountryCode;
                $retData['orderDate'] = $orderDate;
                $retData['diary_no'] = $diary_no;
                $retData['required_document'] = $required_document;
                $retData['ShippingState'] = $ShippingState;
                $retData['application_id'] = $application_id;
                //print_r($_SESSION);
                //die;
                //$_SESSION['orderStatus'] = (string)$orderStatus;
                /*$_SESSION['OrderBatchTotalAmounts'] = $OrderBatchTotalAmounts;
                $_SESSION['orderCode'] = (string)$orderCode;
                $_SESSION['ShippingMobileNumber'] = $ShippingMobileNumber;

                $_SESSION['ShopperEmailAddress'] = $ShopperEmailAddress;
                $_SESSION['ShippingFirstName'] = $ShippingFirstName;
                $_SESSION['ShippingLastName'] = $ShippingLastName;
                $_SESSION['ShippingAddress1'] = $ShippingAddress1;
                $_SESSION['ShippingAddress2'] = $ShippingAddress2;
                $_SESSION['ShippingCity'] = $ShippingCity;
                $_SESSION['ShippingStateRegion'] = $ShippingStateRegion;
                $_SESSION['ShippingState'] = $ShippingState;

                $_SESSION['ShippingPostalCode'] = $ShippingPostalCode;
                $_SESSION['ShippingCountryCode'] = $ShippingCountryCode;
                $_SESSION['orderDate'] = $orderDate;
                $_SESSION['diary_no'] = $diary_no;
                $_SESSION['required_document'] = $required_document;
                $_SESSION['ShippingState'] = $ShippingState;*/
                //eCopying charges Rs. {#var#} received successfully. CRN {#var#} allotted for your reference. - Supreme Court Of India
                //$sms_response = sci_send_sms($ShippingMobileNumber,$sms_text,'ecop',SCISMS_eCopying_charges);
                //$json_for_aor = json_decode($sms_response);
                $data=array('OrderBatchTotalAmounts'=>$OrderBatchTotalAmounts,'orderDate'=>$orderDate,'orderCode'=>$orderCode);
                // $htmlContent=$this->render('onlineCopying.sci_email_response',$data);
                $htmlContent=view('onlineCopying/sci_email_response',$data);
                $subject='Payment Confirmation';
                $to_email=$ShopperEmailAddress;
                send_mail_msg($to_email, $subject,$htmlContent, $to_user_name="");
                // return response()->redirect(base_url('online_copying/sci_response_reciept'));   
                return view('onlineCopying/sci_response_success_receipt',$retData); 
            }else{
                $orderCode=$data->order_batch_merchant_batch_code;
                $copyingDetails = getCopyingDetails($orderCode);
                $diary_no = $copyingDetails[0];
                $required_document = $copyingDetails[1];
                $sms_text = "eCopying charges Rs. $TotalAmount is Failed. - Supreme Court Of India";
                $retData['orderStatus'] = (string)$orderStatus;
                $retData['OrderBatchTotalAmounts'] = $OrderBatchTotalAmounts;
                $retData['orderCode'] = $orderCode;
                $retData['ShippingMobileNumber'] = $ShippingMobileNumber;
                $retData['ShopperEmailAddress'] = $ShopperEmailAddress;
                $retData['ShippingFirstName'] = $ShippingFirstName;
                $retData['ShippingLastName'] = $ShippingLastName;
                $retData['ShippingAddress1'] = $ShippingAddress1;
                $retData['ShippingAddress2'] = $ShippingAddress2;
                $retData['ShippingCity'] = $ShippingCity;
                $retData['ShippingStateRegion'] = $ShippingStateRegion;
                $retData['ShippingState'] = $ShippingState;
                $retData['ShippingPostalCode'] = $ShippingPostalCode;
                $retData['ShippingCountryCode'] = $ShippingCountryCode;
                $retData['orderDate'] = $orderDate;
                $retData['diary_no'] = $diary_no;
                $retData['required_document'] = $required_document;
                $retData['ShippingState'] = $ShippingState;
                $retData['application_id'] = $application_id;
                $data=array('OrderBatchTotalAmounts'=>$OrderBatchTotalAmounts,'orderDate'=>$orderDate,'orderCode'=>$orderCode);
                $htmlContent=view('onlineCopying/sci_email_failed_response',$data);
                $subject='Payment Confirmation';
                $to_email=$ShopperEmailAddress;
                send_mail_msg($to_email, $subject,$htmlContent, $to_user_name="");
                return view('onlineCopying/sci_response_failure_receipt',$retData); 
            }
            
        }
       }
        }else{
            //logout
            return response()->redirect(base_url('logout'));
            //<!--<script type='text/javascript'>window.location.href = 'online_copying/logout.php'</script>-->
        }
        }
        }
    }
    public function sci_response_reciept(){
        //print_r($_SESSION);
        //die;
        ob_start();
        //$this->render('onlineCopying.sci_response_success_receipt',$_SESSION);
        //die;
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        // pr($pdf);
        $pdf->SetPrintHeader(TRUE);
        $pdf->SetPrintFooter(TRUE);
        $pdf->SetAuthor('Supreme Court of India');
        $pdf->SetTitle('Supreme Court of India');
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        
        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once(dirname(__FILE__) . '/lang/eng.php');
            $pdf->setLanguageArray($l);
        }

        $pdf->SetFont('helvetica', '', 8, '', true);
        $pdf->AddPage();
        $output_file_name ="ResponseReciept.pdf";
        $content = view('onlineCopying/sci_response_success_receipt',$_SESSION);
        $pdf->writeHTML($content. '', true, false, false, false, '');
        $pdf->lastPage();
        
        ob_end_clean();
        
        
        
        // $pdf->Output($output_file_name, 'I');
        // exit(0);

        return view('onlineCopying/sci_response_success_receipt',$_SESSION);   
    }
    public function sciRequestPayment()
    {
        $params = session_get_cookie_params();
        setcookie(session_name(), $_COOKIE[session_name()], time() + 60*60*24*30, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
        return $this->render('onlineCopying.sci_request_payment');
    }
    
    public function verifyUser()
    {
        return $this->render('onlineCopying.verify_user');
    }

    public function checkUserofECopying(){
        $_SESSION['is_token_matched'] = 'Yes';
        $_SESSION['applicant_email'] = !empty(getSessionData('login')) ? getSessionData('login')['emailid'] : '';
        $_SESSION['applicant_mobile'] = !empty(getSessionData('login')) ? getSessionData('login')['mobile_number'] : '';
        $checkUserAddress=array();
        if(!empty(getSessionData('login'))){
        $checkUserAddress = $this->ecoping_webservices->getUserAddress(getSessionData('login')['mobile_number'], getSessionData('login')['emailid']);
        //print_r($checkUserAddress);
        //die;        
        if (!empty($checkUserAddress)) {
            $address_array = array();
            $_SESSION['is_user_address_found'] = 'YES';
            $address_data = $checkUserAddress;
            foreach($address_data as $r) {
                $address_array[] = $r;   
            }
            $_SESSION['user_address'] = $address_array;            
        } else {
            $_SESSION['is_user_address_found'] = 'NO';
        }
        if(isset(getSessionData('login')['your_email']) && getSessionData('login')['your_email'] != '' && isset(getSessionData('login')['your_mobile']) && getSessionData('login')['your_mobile']!= ''){
            $verify = eCopyingVerifiedUser(getSessionData('login')['your_email'], getSessionData('login')['your_mobile'], 0);
        }else{
            $verify = eCopyingVerifiedUser(getSessionData('login')['emailid'], getSessionData('login')['mobile_number'], 0);
        }
        if(!$verify && (getSessionData('login')['ref_m_usertype_id'] != USER_ADVOCATE)){
            
            return $this->render('onlineCopying.verify_user');
        }elseif(getSessionData('login')['ref_m_usertype_id'] == USER_ADVOCATE){ 
        }elseif(isset($verify) && !empty($verify) && $verify->c_status == 0){
            if(getSessionData('login')['ref_m_usertype_id'] == AUTHENTICATED_BY_AOR){
                
                $barid = getSessionData('login')['icmis_usercode'];
                $verifyAOR = eCopyingVerifiedUserAOR(getSessionData('login')['your_email'], getSessionData('login')['your_mobile'], 1,$barid);
                if(!$verifyAOR){
                    $this->session->setFlashdata('msg', 'This email address is requested to be registered for eCopying Services of Supreme Court of India for (User Name, Applicant Type)');
                    return response()->redirect(base_url('online_copying/user_verification'));
                }
            }else{
                $this->session->setFlashdata('msg', 'This email address is requested to be registered for eCopying Services of Supreme Court of India for (User Name, Applicant Type)');
                return response()->redirect(base_url('online_copying/user_verification'));
            }
            
        }
        $dOtp = $this->ecoping_webservices->eCopyingOtpVerification($_SESSION['applicant_email']);
        if($dOtp) {
            $_SESSION['session_verify_otp'] = '000000';
            $_SESSION['session_otp_id'] = '999999';
            $_SESSION['applicant_mobile'] = $dOtp->mobile;
            $_SESSION['applicant_email'] = $dOtp->email;
            $_SESSION['is_email_send'] = 'Yes';
            $_SESSION['email_token'] = $dOtp->otp;
            $_SESSION['is_token_matched'] = 'Yes';            
            $_SESSION["session_filed"] = $dOtp->filed_by;
            $_SESSION['session_authorized_bar_id'] = $dOtp->authorized_bar_id;            
            if($dOtp->filed_by == 6) {
                // $_SESSION['session_authorized_bar_id'] = $dOtp->authorized_bar_id;            
                $aor_data = $this->ecoping_webservices->eCopyingGetBarDetails($dOtp->authorized_bar_id);
                if (!empty($aor_data)) {
                    $aor_mobile = $aor_data->mobile;
                    $_SESSION["aor_mobile"] = $aor_data->mobile; 
                }
            }
        } 
        } 
    }

}