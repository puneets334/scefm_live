<?php

namespace App\Controllers\ShcilPayment;

use App\Controllers\BaseController;
use App\Models\Common\CommonModel;
use App\Models\Affirmation\EsignSignatureModel;
use App\Models\AppearingFor\AppearingForModel;
use App\Models\NewCase\GetDetailsModel;

class PaymentRequest extends BaseController {
    protected $Get_details_model;
    protected $Esign_signature_model;
    protected $Appearing_for_model;
    protected $Common_model;

    public function __construct() {
        parent::__construct();
        $this->Get_details_model = new GetDetailsModel();
        $this->Esign_signature_model = new EsignSignatureModel();
        $this->Appearing_for_model = new AppearingForModel();
        $this->Common_model = new CommonModel();
    }



    public function index() {
        
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_DEPARTMENT,AMICUS_CURIAE_USER);
        if (getSessionData('login') != '' && !in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            return redirect()->to(base_url('/'));
            exit(0);
        }

        $stages_array = array('', Draft_Stage, Initial_Defected_Stage, I_B_Defected_Stage);
        if (getSessionData('efiling_details') != '' && !in_array($_SESSION['efiling_details']['stage_id'], $stages_array)) {
            return redirect()->to(base_url('dashboard'));
            exit(0);
        }
        if (isset($_SESSION['efiling_details']['registration_id']) && !empty($_SESSION['efiling_details']['registration_id'])
                && isset($_SESSION['pg_request_payment_details']) && !empty($_SESSION['pg_request_payment_details'])) {
            $party_name = '-';
            $pg_params = json_decode($_SESSION['estab_details']['payment_gateway_params'], true);
            if($_SESSION['efiling_details']['efiling_type'] == 'new_case'){
                $parties=$this->Esign_signature_model->get_signers_list($_SESSION['efiling_details']['registration_id'], 'P');
                foreach ($parties as $party){
                    if($party['p_r_type']=='P' && $party['m_a_type']=='M'){
                        $party_name = (!is_null($party['party_name']))?$party['party_name']:$party['deptname'].', '.$party['state_name'];
                    }
                }
            }
            else if($_SESSION['efiling_details']['efiling_type'] == 'IA' || $_SESSION['efiling_details']['efiling_type'] == 'misc_document'){
                $parties = $this->Appearing_for_model->get_case_parties_list($_SESSION['efiling_details']['registration_id']);
                foreach ($parties as $dataRes) {
                    if($dataRes['p_r_type']=='P'){
                        $party_names = explode("##",$dataRes['p_partyname']);
                        $party_nos = explode("$$", $dataRes['filing_for_parties']);
                        foreach ($party_nos as $x=>$party_no)
                            if($x==0)
                                $party_name = $party_names[($party_no-1)];
                            else
                                $party_name = $party_name.', '.$party_names[($party_no-1)];
                    }
                    else if($dataRes['p_r_type']=='R'){
                        $party_names = explode("##",$dataRes['r_partyname']);
                        $party_nos = explode("$$", $dataRes['filing_for_parties']);
                        foreach ($party_nos as $x=>$party_no)
                            if($x==0)
                                $party_name = $party_names[($party_no-1)];
                            else
                                $party_name = $party_name.', '.$party_names[($party_no-1)];
                    }
                    else if($dataRes['p_r_type']=='I')
                        $party_name = str_replace("##",", ",$dataRes['intervenor_name']);
                    else
                        $party_name = '-';
                }
            }
            else if($_SESSION['efiling_details']['efiling_type'] == 'DEFICIT_COURT_FEE'){
                $party_name='-';
            }
            $court_fee_list1 = $this->Common_model->get_subject_category_casetype_court_fee($_SESSION['efiling_details']['registration_id']);
            $processing_charges = 0;
            if($court_fee_list1[0]['sc_case_type_id']==23){
                $processing_charges = PROCESSING_CHARGES_FOR_23;
            }
            if($court_fee_list1[0]['sc_case_type_id']==24){
                $processing_charges = PROCESSING_CHARGES_FOR_24;
            }
            $params = array('login' => $pg_params['login'],
            'password' => $pg_params['password'],
            'prodid' => $pg_params['Product'],
            'ReqHashKey' => $pg_params['ReqHashKey'],
            'RespHashKey' => $pg_params['RespHashKey'],
            'txnType' => $pg_params['txnTyp'],
            'scamt' => $pg_params['scamt'],
            'txnid' => $_SESSION['pg_request_payment_details']['order_no'],
            'txndate' => date('d-M-Y H:i:s', strtotime($_SESSION['pg_request_payment_details']['order_date'])),
            'party_name' => $party_name, //$party_name
            'mobile' => $_SESSION['login']['mobile_number'],
            'email' => $_SESSION['login']['emailid'],
            'amt' => $_SESSION['pg_request_payment_details']['user_declared_total_amt'],
            'court_fees' => $_SESSION['pg_request_payment_details']['user_declared_court_fee'],
            'print_fees' => $_SESSION['pg_request_payment_details']['printing_total'],
            'return_url' => base_url() . "shcilPayment/paymentResponse",
            
        );
        $request = $params['login'] . $params['password'] . $params['txnType'] . $params['prodid'] . $params['txnid'] . $params['amt'] . $params['scamt'] . $params['txndate'];
        $sign = hash_hmac('sha512', $request, $params['ReqHashKey']);
        $paramsCookie = session_get_cookie_params();
        setcookie(session_name(), $_COOKIE[session_name()], time() + 60*60*24*30, $paramsCookie["path"], $paramsCookie["domain"], $paramsCookie["secure"], $paramsCookie["httponly"]);
        echo '<div align="center"><h3> Please Wait... You Will be Redirected Shortly<br/> Don\'t Refresh or Press Back </h3></div>';
        $attribute = array('target'=> '_top', 'name' => 'myform', 'id' => 'myform', 'autocomplete' => 'off');
        echo form_open(STOCK_HOLDING_PAYMENT_BASE_URL, $attribute);
        echo '<input type = "hidden" name = "login" value = "' . htmlentities($params['login'], ENT_QUOTES) . '" />';
        echo '<input type = "hidden" name = "pass" value = "' . htmlentities($params['password'], ENT_QUOTES) . '" />';
        echo '<input type = "hidden" name = "txnType" value = "' . htmlentities($params['txnType'], ENT_QUOTES) . '" />';
        echo '<input type = "hidden" name = "prodid" value = "' . htmlentities($params['prodid'], ENT_QUOTES) . '" />';
        echo '<input type = "hidden" name = "txnid" value = "' . htmlentities($params['txnid'], ENT_QUOTES) . '"  />';
        echo '<input type = "hidden" name = "amt" value = "' . htmlentities($params['amt'], ENT_QUOTES) . '"  />';
        echo '<input type = "hidden" name = "scamt" value = "' . htmlentities($params['scamt'], ENT_QUOTES) . '"  />';
        echo '<input type = "hidden" name = "txndate" value = "' . htmlentities($params['txndate'], ENT_QUOTES) . '"  />';
        echo '<input type = "hidden" name = "ru" value = "' . htmlentities($params['return_url'], ENT_QUOTES) . '" />';
        echo '<input type = "hidden" name = "signature" value = "' . htmlentities($sign, ENT_QUOTES) . '" />';
        echo '<input type = "hidden" name = "udf1" value = "' .htmlentities(substr($params['party_name'],0,98), ENT_QUOTES) . '" />';
        echo '<input type = "hidden" name = "udf2" value = "' . htmlentities($params['mobile'], ENT_QUOTES) . '" />';
        echo '<input type = "hidden" name = "udf3" value = "' . htmlentities($params['email'], ENT_QUOTES)  . '" />';
        echo '<input type = "hidden" name = "udf4" value = "' . htmlentities($params['court_fees'], ENT_QUOTES) . '" />';
        echo '<input type = "hidden" name = "udf5" value = "' . htmlentities($params['print_fees'], ENT_QUOTES) . '" />';
        echo '<input type = "hidden" name = "udf6" value = "Supreme Court of India" />';
        echo '<input type = "hidden" name = "udf7" value = "Delhi" />';
        if(COURT_FEE_PROCESSING_CHARGES){ 
            if(in_array($court_fee_list1[0]['sc_case_type_id'],PROCESSING_CHARGES_CASE_TYPE)){
                echo '<input type = "hidden" name = "udf8" value = "' . htmlentities($processing_charges, ENT_QUOTES) . '" />';
            } else {
                echo '<input type = "hidden" name = "udf8" value = "" />';
            }
        } else {
            echo '<input type = "hidden" name = "udf8" value = "" />';
        }
        echo '<input type = "hidden" name = "udf9" value = "" />';
        echo '<input type = "hidden" name = "addInfo" value = "" />';
        echo form_close();
        echo "<script>document.getElementById('myform').submit();</script>";
        }
    }
}

