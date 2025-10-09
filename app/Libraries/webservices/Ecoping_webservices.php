<?php
namespace App\Libraries\webservices;

class Ecoping_webservices {

    public function webservice($est_code = '') {
        $web_response = curl_get_contents(WEB_SERVICE_BASE_URL . $est_code);
        $xml = simplexml_load_string($web_response);
        if ($xml === false) {
            return FALSE;
        } else {
            return $xml;
        }
    }

/* Prisoner Module */



   
    public function geteCopySearch($flag,$crn,$application_type,$application_no,$application_year)
    {
        //http://10.25.78.48:81/online_copying/get_copy_search?flag=ano&crn=&application_type=3&application_no=6544574&application_year=2021&_=1737095982794
        //echo ICMIS_SERVICE_URL."/online_copying/get_copy_search/?flag=$flag&crn=$crn&application_type=$application_type&application_no=$application_no&application_year=$application_year";
        /*$ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,ICMIS_SERVICE_URL."/online_copying/get_copy_search/?flag=$flag&crn=$crn&application_type=$application_type&application_no=$application_no&application_year=$application_year");
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
        $response=curl_exec($ch);
        echo $response;
        die;*/
        //return json_decode($response);
        //curl_setopt($ch,)
        $data = file_get_contents(ICMIS_SERVICE_URL."/online_copying/get_copy_search?flag=$flag&crn=$crn&application_type=$application_type&application_no=$application_no&application_year=$application_year");
        
        if ($data != false) {
            
            return json_decode($data,true);
        } else {
            return NULL;
        }
    }
    public function getCopySearchResult($postdata){
        
        $postdata = http_build_query(
            $postdata
        );
        
        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-Type: application/x-www-form-urlencoded',
                'content' => $postdata
            )
        );
        $context  = stream_context_create($opts);
        $url = ICMIS_SERVICE_URL;
        $result = file_get_contents($url.'/online_copying/getCopySearchResult', false, $context);
        return json_decode($result);
    }
  public function getCopyStatusResult($postdata,$asset_type_flag){
    $postdata['asset_type_flag']=$asset_type_flag;
    $postdata = http_build_query(
        $postdata
    );
    
    $opts = array('http' =>
        array(
            'method'  => 'POST',
            'header'  => 'Content-Type: application/x-www-form-urlencoded',
            'content' => $postdata
        )
    );
    $context  = stream_context_create($opts);
    $url = ICMIS_SERVICE_URL;
    $result = file_get_contents($url.'/online_copying/getCopyStatusResult',false,$context);
    return json_decode($result,true);
  }
  public function getCopyBarcode($id){
    
    $data = file_get_contents(ICMIS_SERVICE_URL."/online_copying/getCopyBarcode?id=$id");
        
        if ($data != false) {
            
            return json_decode($data,true);
        } else {
            return NULL;
        }
  }
  public function getCopyApplication($id){
    $data = file_get_contents(ICMIS_SERVICE_URL."/online_copying/getCopyApplication?id=$id");
        
    if ($data != false) {
        
        return json_decode($data,true);
    } else {
        return NULL;
    }
  }
  public function copyFormSentOn($id){
    
    $data = file_get_contents(ICMIS_SERVICE_URL."/online_copying/copyFormSentOn?id=$id");
        
    if ($data != false) {
        
        return json_decode($data);
    } else {
        return NULL;
    }
  } 
  public function getCopyRequest($id){
    $data = file_get_contents(ICMIS_SERVICE_URL."/online_copying/getCopyRequest?id=$id");
        
    if ($data != false) {
        
        return json_decode($data,true);
    } else {
        return NULL;
    }
  }
  public  function eCopyingGetDiaryNo($ct, $cn, $cy){
    
    $data = file_get_contents(ICMIS_SERVICE_URL."/online_copying/eCopyingGetDiaryNo?ct=$ct&cn=$cn&cy=$cy");
        
    if ($data != false) {
        
        return json_decode($data,true);
    } else {
        return NULL;
    }
  }   
  public function eCopyingCheckDiaryNo($ct, $cn, $cy){
    
    
    $data = file_get_contents(ICMIS_SERVICE_URL."/online_copying/eCopyingCheckDiaryNo?ct=$ct&cn=$cn&cy=$cy");
        
    if ($data != false) {
        
        return json_decode($data,true);
    } else {
        return NULL;
    }
  }
    public function eCopyingGetFileDetails($diary_no){
        $data = file_get_contents(ICMIS_SERVICE_URL."/online_copying/eCopyingGetFileDetails?diary_no=$diary_no");
        
        if ($data != false) {
            
            return json_decode($data);
        } else {
            return NULL;
        }   
    }
    public function getStatementVideo($mobile, $email){
        $email = urlencode($email);
        $mobile = urlencode($mobile);
        $data = file_get_contents(ICMIS_SERVICE_URL."/online_copying/getStatementVideo?email=$email&mobile=$mobile");
        
        if ($data != false) {
            
            return json_decode($data,true);
        } else {
            return NULL;
        }   
    }
    public function getStatementImage($mobile, $email){
        $email = urlencode($email);
        $mobile = urlencode($mobile);
        $data = file_get_contents(ICMIS_SERVICE_URL."/online_copying/getStatementImage?email=$email&mobile=$mobile");
        
        if ($data != false) {
            
            return json_decode($data,true);
        } else {
            return NULL;
        }   
    }
    public function getStatementIdProof($mobile, $email){
        $email = urlencode($email);
        $mobile = urlencode($mobile);
        $data = file_get_contents(ICMIS_SERVICE_URL."/online_copying/getStatementIdProof?email=$email&mobile=$mobile");
        
        if ($data != false) {
            
            return json_decode($data,true);
        } else {
            return NULL;
        }   
    }
    public function eCopyingStatementCheck($mobile, $email){
        $email = urlencode($email);
        $mobile = urlencode($mobile);
        $data = file_get_contents(ICMIS_SERVICE_URL."/online_copying/getStatementIdProof?email=$email&mobile=$mobile");
        
        if ($data != false) {
            
            return json_decode($data,true);
        } else {
            return NULL;
        }   
    }
    public function eCopyingCheckMaxDigitalRequest($mobile, $email){
        $email = urlencode($email);
        $mobile = urlencode($mobile);
        $data = file_get_contents(ICMIS_SERVICE_URL."/online_copying/getStatementIdProof?email=$email&mobile=$mobile");
        
        if ($data != false) {
            
            return json_decode($data,true);
        } else {
            return NULL;
        }   
    }
    public function eCopyingCopyStatus($diary_no, $check_asset_type, $mobile, $email){
        $email = urlencode($email);
        $mobile = urlencode($mobile);
        $data = file_get_contents(ICMIS_SERVICE_URL."/online_copying/getStatementIdProof?diary_no=$diary_no&check_asset_type=$check_asset_type&mobile=$mobile&email=$email");
        
        if ($data != false) {
            
            return json_decode($data,true);
        } else {
            return NULL;
        }   
    }
    public function eCopyingGetBar($diary_no,$mobile){
        //echo ICMIS_SERVICE_URL."/online_copying/eCopyingGetBar/?diary_no=$diary_no&mobile=$mobile";
        //die;
        $data = file_get_contents(ICMIS_SERVICE_URL."/online_copying/eCopyingGetBar?diary_no=$diary_no&mobile=$mobile");
        
        if ($data != false) {
            
            return json_decode($data,true);
        } else {
            return NULL;
        }   
    }
    public function getCopyBarcodeBymobileOrAorCOde($aor_code,$mobile){
        
        $data = file_get_contents(ICMIS_SERVICE_URL."/online_copying/getCopyBarcodeBymobileOrAorCOde?aor_code=$aor_code&aor_mobile=$mobile");
        
        if ($data != false) {
            
            return json_decode($data);
        } else {
            return NULL;
        } 
    }
    public function getBailApplied($diary_no, $mobile, $email){
        $email = urlencode($email);
        $mobile = urlencode($mobile);
        $data = file_get_contents(ICMIS_SERVICE_URL."/online_copying/getBailApplied?diary_no=$diary_no&mobile=$mobile&email=$email");
        
        if ($data != false) {
            
            return  $data;
        } else {
            return NULL;
        }   
    }
    public function eCopyingGetCopyDetails($condition, $third_party_sub_qry, $OLD_ROP){
        // $data = file_get_contents(ICMIS_SERVICE_URL."/online_copying/eCopyingGetCopyDetails/?condition=$condition&third_party_sub_qry=$third_party_sub_qry&OLD_ROP=$OLD_ROP", false);
        // $data = json_decode(curl_get_contents(ICMIS_SERVICE_URL . "/online_copying/eCopyingGetCopyDetails/?condition=$condition&third_party_sub_qry=$third_party_sub_qry&OLD_ROP=$OLD_ROP"));
        $curl = curl_init();
        $third_party_sub_qry = urlencode($third_party_sub_qry);
//echo ICMIS_SERVICE_URL."/online_copying/eCopyingGetCopyDetails?condition=$condition&third_party_sub_qry=$third_party_sub_qry&OLD_ROP=$OLD_ROP";
//die;
curl_setopt_array($curl, array(
  CURLOPT_URL => ICMIS_SERVICE_URL."/online_copying/eCopyingGetCopyDetails?condition=$condition&third_party_sub_qry=$third_party_sub_qry&OLD_ROP=$OLD_ROP",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
));

$data = curl_exec($curl);

curl_close($curl);
        if ($data != false) {
            
            return json_decode($data,true);
        } else {
            return NULL;
        }   
    }
    public function eCopyingGetGroupConcat($main_case){
        //echo ICMIS_SERVICE_URL."/online_copying/eCopyingGetGroupConcat?main_case=$main_case";
        //die;
        $data = file_get_contents(ICMIS_SERVICE_URL."/online_copying/eCopyingGetGroupConcat?main_case=$main_case");
        
        if ($data != false) {
            
            return json_decode($data,true);
        } else {
            return NULL;
        }   
    }
    public function getIsPreviuslyApplied($copy_category, $diary_no, $mobile, $email, $order_type, $order_date){
        $email = urlencode($email);
        $mobile = urlencode($mobile);
        $data = file_get_contents(ICMIS_SERVICE_URL."/online_copying/getIsPreviuslyApplied?copy_category=$copy_category&diary_no=$diary_no&mobile=$mobile&email=$email&order_type=$order_type&order_date=$order_date");
        
        if ($data != false) {
            
            return json_decode($data);
        } else {
            return NULL;
        }   
    }
    public function speedPostTariffCalcOffline($weight,$desitnation_pincode){
        $data = file_get_contents(ICMIS_SERVICE_URL."/online_copying/speedPostTariffCalcOffline?weight=$weight&desitnation_pincode=$desitnation_pincode");
        
        if ($data != false) {
            
            return json_decode($data,true);
        } else {
            return NULL;
        }   
    }
    public function eCopyingAvailableAllowedRequests($mobile,$email){
        $email = urlencode($email);
        $mobile = urlencode($mobile);
        $data = file_get_contents(ICMIS_SERVICE_URL."/online_copying/eCopyingAvailableAllowedRequests?mobile=$mobile&email=$email");
        
        if ($data != false) {
            
            return json_decode($data,true);
        } else {
            return NULL;
        }   
    }
    public function eCopyingGetDocumentType($third_party_sub_qry){
        // $data = file_get_contents(ICMIS_SERVICE_URL."/online_copying/eCopyingGetDocumentType?third_party_sub_qry=$third_party_sub_qry");

        $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => ICMIS_SERVICE_URL."/online_copying/eCopyingGetDocumentType?third_party_sub_qry=$third_party_sub_qry",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
));

$data = curl_exec($curl);

curl_close($curl);
        if ($data != false) {
            
            return json_decode($data,true);
        } else {
            return NULL;
        }   
    }
    public function eCopyingGetCasetoryById($id){
        $data = file_get_contents(ICMIS_SERVICE_URL."/online_copying/eCopyingGetCasetoryById?id=$id");
        
        if ($data != false) {
            
            return json_decode($data);
        } else {
            return NULL;
        }   
    }
    public function insert_user_assets($data){
        
        $postdata = http_build_query(
            $data
        );
        
        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-Type: application/x-www-form-urlencoded',
                'content' => $postdata
            )
        );
        $context  = stream_context_create($opts);
        
        $result = file_get_contents(ICMIS_SERVICE_URL.'/online_copying/add_user_assets', false, $context);
        
        return json_decode($result);
    }
    public function is_certified_copy_details($ref_tbl_lower_court_details_id,$registration_id){
        
        $data = file_get_contents(ICMIS_SERVICE_URL."/online_copying/is_certified_copy_details?ref_tbl_lower_court_details_id=$ref_tbl_lower_court_details_id&registration_id=$registration_id");
        
        if ($data != false) {
            
            return json_decode($data,true);
        } else {
            return NULL;
        }    
    }
    public function getAordetailsByAORCODE($aor_code){
        $data = file_get_contents(ICMIS_SERVICE_URL."/online_copying/getAordetailsByAORCODE?aor_code=$aor_code");
        
        if ($data != false) {
            
            return json_decode($data,true);
        } else {
            return NULL;
        }   
    }
    /*public function is_AORGovernment(){
        $data = file_get_contents(ICMIS_SERVICE_URL."/online_copying/is_AORGovernment/?ct=$ct&cn=$cn&cy=$cy");
        
        if ($data != false) {
            
            return json_decode($data,true);
        } else {
            return NULL;
        }   
    }
    public function is_clerk_aor(){
        $data = file_get_contents(ICMIS_SERVICE_URL."/online_copying/is_clerk_aor/?ct=$ct&cn=$cn&cy=$cy");
        
        if ($data != false) {
            
            return json_decode($data,true);
        } else {
            return NULL;
        }   
    }
    public function getAordetails_ifFiledByClerk(){
        $data = file_get_contents(ICMIS_SERVICE_URL."/online_copying/getAordetails_ifFiledByClerk/?ct=$ct&cn=$cn&cy=$cy");
        
        if ($data != false) {
            
            return json_decode($data,true);
        } else {
            return NULL;
        }   
    }*/
    public function articleTrackingOffline($articlenumber){
        $data = file_get_contents(ICMIS_SERVICE_URL."/online_copying/articleTrackingOffline?articlenumber=$articlenumber");
        
        if ($data != false) {
            
            return json_decode($data,true);
        } else {
            return NULL;
        }      
    }
    public function getCaseType()
    {
        //http://10.25.78.48:81/online_copying/get_copy_search?flag=ano&crn=&application_type=3&application_no=6544574&application_year=2021&_=1737095982794

        $data = curl_get_contents(ICMIS_SERVICE_URL."/online_copying/get_case_type");
        if ($data != false) {
            return json_decode($data);
        } else {
            return NULL;
        }
    }
    public function getCategory(){
        
        $data = curl_get_contents(ICMIS_SERVICE_URL."/online_copying/get_category");
        
        
        if ($data != false) {
           
            return json_decode($data,true);
        } else {
            return NULL;
        }
    }
    public function insert_copying_application_online($postdata){
        
        $postdata = http_build_query(
            $postdata
        );
        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-Type: application/x-www-form-urlencoded',
                'content' => $postdata
            )
        );
        $context  = stream_context_create($opts);
        $url = ICMIS_SERVICE_URL;
        //echo $url.'/online_copying/save_coping_online';
        //die;
        $result = file_get_contents($url.'/online_copying/save_coping_online', false, $context);
        //echo $result;
        //die;
        
        return json_decode($result);     
        
    }
    
    public function copyFaq(){
        
        $data = file_get_contents(ICMIS_SERVICE_URL."/online_copying/copy_faq");
        if ($data != false) {
            return json_decode($data);
        } else {
            return NULL;
        }
    }
    public function getCatogoryForApplication($idd){
        
        $data = file_get_contents(ICMIS_SERVICE_URL."/online_copying/get_catogory_for_application?idd=$idd");
        
        if ($data != false) {
            return json_decode($data);
        } else {
            return NULL;
        }   
    }
    public function insert_copying_application_documents_online($postdata){
        $postdata = http_build_query(
            $postdata
        );
        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-Type: application/x-www-form-urlencoded',
                'content' => $postdata
            )
        );
        $context  = stream_context_create($opts);
        $url = ICMIS_SERVICE_URL;
        $result = file_get_contents($url.'/online_copying/save_copying_application_documents_online', false, $context);
        
        return json_decode($result);
    }
    public function getCopyingRequestVerify($diary_no,$applicant_mobile){
        $data = curl_get_contents(ICMIS_SERVICE_URL."/online_copying/get_catogory_for_application?diary_no=$diary_no&applicant_mobile=$applicant_mobile");
        if ($data != false) {
            return json_decode($data);
        } else {
            return NULL;
        }   
    }
    public function createCRN($service_user_id){
        //echo ICMIS_SERVICE_URL."/online_copying/createCRN?service_user_id=$service_user_id";
        //die;
        $data = file_get_contents(ICMIS_SERVICE_URL."/online_copying/createCRN?service_user_id=$service_user_id");
        
        if ($data != false) {
            return json_decode($data);
        } else {
            return NULL;
        }   
    }
    public function saveSMSData($dataArr){
        $postdata = http_build_query(
            $dataArr
        );
        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-Type: application/x-www-form-urlencoded',
                'content' => $postdata
            )
        );
        $context  = stream_context_create($opts);
        $url = ICMIS_SERVICE_URL;
        $result = file_get_contents($url.'/online_copying/saveSMSData', false, $context);
        //$data = curl_get_contents(ICMIS_SERVICE_URL."/online_copying/saveSMSData/");
        if ($result != false) {
            return json_decode($result);
        } else {
            return NULL;
        }   
    }
    public function getUserAddress($mobile_no,$email){
        //echo ICMIS_SERVICE_URL."/online_copying/getUserAddress/?mobile=$mobile_no&emailid=$email";
        //die;
        //exit;gid
        // $data = file_get_contents(ICMIS_SERVICE_URL."/online_copying/getUserAddress?mobile=$mobile_no&emailid=$email");
        $email = urlencode($email);
        $mobile_no = urlencode($mobile_no);
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => ICMIS_SERVICE_URL."/online_copying/getUserAddress?mobile=$mobile_no&emailid=$email",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $data = curl_exec($curl);

        curl_close($curl);
        if ($data != false) {
            return json_decode($data,true);
        } else {
            return NULL;
        }  
    }
    public function getUserAssets($mobile_no,$email){
        //$data[]=
        $email = urlencode($email);
        $mobile_no = urlencode($mobile_no);
        $data = file_get_contents(ICMIS_SERVICE_URL."/online_copying/getUserAssets?mobile=$mobile_no&emailid=$email");
        if ($data != false) {
            return json_decode($data,true);
        } else {
            return NULL;
        }  
    }
    public function getUserAssetsKycVideo($mobile_no,$email,$asset_type){
        $email = urlencode($email);
        $mobile_no = urlencode($mobile_no);
        $data = file_get_contents(ICMIS_SERVICE_URL."/online_copying/getUserAssets?mobile=$mobile_no&emailid=$email&asset_type=$asset_type&videoKyc=yes");
        if ($data != false) {
            return json_decode($data,true);
        } else {
            return NULL;
        }  
    }
    public function verifyAadhar($mobile_no,$email){
        $mobile_no = urlencode($mobile_no);
        $email = urlencode($email);
        $data = file_get_contents(ICMIS_SERVICE_URL."/online_copying/verifyAadhar?mobile=$mobile_no&emailid=$email");
        if ($data != false) {
            return json_decode($data);
        } else {
            return NULL;
        }  
    }
    public function getListedCases($mobile_no,$email){
        $mobile_no = urlencode($mobile_no);
        $email = urlencode($email);
        $data = file_get_contents(ICMIS_SERVICE_URL."/online_copying/getListedCases?mobile=$mobile_no&emailid=$email");
        if ($data != false) {
            return json_decode($data);
        } else {
            return NULL;
        }  
    }

    public function saveUserAddress($dataArr) {
        // $postdata = http_build_query(
        //     $dataArr
        // );
        // $opts = array(
        //     'http' =>
        //     array(
        //         'method'  => 'POST',
        //         'header'  => 'Content-Type: application/x-www-form-urlencoded',
        //         'content' => $postdata
        //     )
        // );
        // $context  = stream_context_create($opts);
        // $url = ICMIS_SERVICE_URL;
        // $result = file_get_contents($url . '/online_copying/saveUserAddress', true, $context);
        // if ($result != false) {
        //     return json_decode($result, true);
        // } else {
        //     return [];
        // }
        $postdata = http_build_query(
            $dataArr
        );
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => ICMIS_SERVICE_URL . '/online_copying/saveUserAddress',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $postdata,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));
        $result = curl_exec($curl);
        curl_close($curl);
        if ($result != false) {
            return json_decode($result, true);
        } else {
            return NULL;
        }             
    }

    public function BharatKoshBatchRequest($dataArr){
        
        $postdata = http_build_query(
            $dataArr
        );
        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-Type: application/x-www-form-urlencoded',
                'content' => $postdata
            )
        );
        $context  = stream_context_create($opts);
        $url = ICMIS_SERVICE_URL;
        $result = file_get_contents($url.'/online_copying/BharatKoshBatchRequest',true, $context);
        
        //$data = curl_get_contents(ICMIS_SERVICE_URL."/online_copying/saveSMSData/");
        
        
        if ($result != false) {
        return json_decode($result,true);
            
        } else {
            return NULL;
        }
        
    }
    public function bharatKoshRequest($dataArr){
        
        $postdata = http_build_query(
            $dataArr
        );
        
        $curl = curl_init();

          curl_setopt_array($curl, array(
             CURLOPT_URL => ICMIS_SERVICE_URL.'/online_copying/BharatKoshRequest',
             CURLOPT_RETURNTRANSFER => true,
             CURLOPT_ENCODING => '',
             CURLOPT_MAXREDIRS => 10,
             CURLOPT_TIMEOUT => 0,
             CURLOPT_FOLLOWLOCATION => true,
             CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
             CURLOPT_CUSTOMREQUEST => 'POST',
             CURLOPT_POSTFIELDS =>$postdata,
             CURLOPT_HTTPHEADER => array(
             'Content-Type: application/x-www-form-urlencoded'
             ),
           ));
            
           $result = curl_exec($curl);
           
           curl_close($curl);
          
        /*$opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-Type: application/x-www-form-urlencoded',
                'content' =>$postdata
            )
        );
        $context  = stream_context_create($opts);
        $url = ICMIS_SERVICE_URL;
        $result = file_get_contents($url.'/online_copying/BharatKoshRequest',true, $context);*/
        
        //$data = curl_get_contents(ICMIS_SERVICE_URL."/online_copying/saveSMSData/");
        if ($result != false) {
        return json_decode($result,true);
            
        } else {
            return NULL;
        }
        
    }
    public function getPincode($pincode){
        $data = file_get_contents(ICMIS_SERVICE_URL."/online_copying/getPincode?pincode=$pincode");
        
        if ($data != false) {
            return json_decode($data);
        } else {
            return NULL;
        }     
    }
    public function RemoveApplicantAddress($addressID, $clientIP){
        $data = file_get_contents(ICMIS_SERVICE_URL."/online_copying/RemoveApplicantAddress?id=$addressID&deletedIP=$clientIP");
        
        if ($data != false) {
            return json_decode($data);
        } else {
            return NULL;
        }  
    }
    public function eCopyingOtpVerification($email){
         //echo ICMIS_SERVICE_URL."/online_copying/eCopyingOtpVerification?emailid=$email";
         $email = urlencode($email);
         //echo ICMIS_SERVICE_URL."/online_copying/eCopyingOtpVerification?emailid=$email";
         //die;
        $data = file_get_contents(ICMIS_SERVICE_URL."/online_copying/eCopyingOtpVerification?emailid=$email");
        if ($data != false) {
            return json_decode($data);
        } else {
            return NULL;
        }  
    }
    public function saveauthenticatedByAorDteail($data){
        
        $postdata = http_build_query(
            $data
        );
        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-Type: application/x-www-form-urlencoded',
                'content' => $postdata
            )
        );
        $context  = stream_context_create($opts);
        $url = ICMIS_SERVICE_URL;
        //echo $url.'/online_copying/saveauthenticatedByAorDteail';
        $result = file_get_contents($url.'/online_copying/saveauthenticatedByAorDteail',true, $context);
        //print_r($result);
        //die;
        //$data = curl_get_contents(ICMIS_SERVICE_URL."/online_copying/saveSMSData/");
        
        
        if ($result != false) {
        return json_decode($result,true);
            
        } else {
            return NULL;
        }
    }
    
    public function ApproveAuthenticatedByAor($data){
        // $postdata = http_build_query(
        //     $data
        // );
        // $opts = array('http' =>
        //     array(
        //         'method'  => 'POST',
        //         'header'  => 'Content-Type: application/x-www-form-urlencoded',
        //         'content' => $postdata
        //     )
        // );
        // $context  = stream_context_create($opts);
        // $url = ICMIS_SERVICE_URL;
        $result = curl_get_contents(ICMIS_SERVICE_URL."/online_copying/ApproveAuthenticatedByAor?id=".urlencode($data['id'])."");
        
        //$data = curl_get_contents(ICMIS_SERVICE_URL."/online_copying/saveSMSData/");
        
        
        if ($result != false) {
            return json_decode($result,true);

        } else {
            return NULL;
        }
    }
    public function eCopyingGetBarDetails($bar_id){
        
        $data = curl_get_contents(ICMIS_SERVICE_URL."/online_copying/eCopyingGetBarDetails?bar_id=$bar_id");
        if ($data != false) {
            return json_decode($data);
        } else {
            return NULL;
        }  
    }
    public function online($email,$mobile){
        $email = urlencode($email);
        $mobile = urlencode($mobile);
        $data = curl_get_contents(ICMIS_SERVICE_URL."/online_copying/online?email=$email&mobile=$mobile");
        if ($data != false) {
            return json_decode($data);
        } else {
            return NULL;
        }  
    }
    public function offline($email,$mobile){
        $email = urlencode($email);
        $mobile = urlencode($mobile);
        $data = curl_get_contents(ICMIS_SERVICE_URL."/online_copying/offline?email=$email&mobile=$mobile");
        if ($data != false) {
            return json_decode($data);
        } else {
            return NULL;
        }  
    }
    public function requests($email,$mobile){
        $email = urlencode($email);
        $mobile = urlencode($mobile);
        $data = curl_get_contents(ICMIS_SERVICE_URL."/online_copying/requests?email=$email&mobile=$mobile");
        if ($data != false) {
            return json_decode($data);
        } else {
            return NULL;
        }  
    }
    public function bharatkoshSaveStatus($data){
        //print_r($data);
        //die;
        $postdata = http_build_query(
            $data
        );
        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-Type: application/x-www-form-urlencoded',
                'content' => $postdata
            )
        );
        $context  = stream_context_create($opts);
        $url = ICMIS_SERVICE_URL;
        $result = file_get_contents($url.'/online_copying/bharatkoshSaveStatus',true, $context);
        
        //$data = curl_get_contents(ICMIS_SERVICE_URL."/online_copying/saveSMSData/");
        
        if ($result != false) {
        return json_decode($result,true);
            
        } else {
            return NULL;
        }
        
    }
    public function getBharakoshPaymentStatus($orderCode,$orderStatus){
        
        $data = curl_get_contents(ICMIS_SERVICE_URL."/online_copying/getBharakoshPaymentStatus?orderCode=$orderCode&orderStatus=$orderStatus");
        if ($data != false) {
            return json_decode($data);
        } else {
            return NULL;
        }  
    }
    public function getBharatKoshRequest($orderCode){
        
        $data = curl_get_contents(ICMIS_SERVICE_URL."/online_copying/getBharatKoshRequest?orderCode=$orderCode");
        if ($data != false) {
            return json_decode($data);
        } else {
            return NULL;
        }  
    }
    public function getCopyingDetails($crn){
        
        $data = curl_get_contents(ICMIS_SERVICE_URL."/online_copying/getCopyingDetails?crn=$crn");
        if ($data != false) {
            return json_decode($data);
        } else{
            return NULL;
        }  
    }
    public function get_application_type($app_type){
        
        $data = curl_get_contents(ICMIS_SERVICE_URL."/online_copying/get_application_type?app_type=$app_type");
        if ($data != false) {
            return json_decode($data);
        } else{
            return NULL;
        }  
    }
    public function getAppearingDiaryNosOnly($cause_list_date, $aor_code){
        
        $data = curl_get_contents(ICMIS_SERVICE_URL."/online_copying/getAppearingDiaryNosOnly?list_date=$cause_list_date&aor_code=$aor_code");
        if ($data != false) {
            return json_decode($data);
        } else{
            return NULL;
        }  
    }
    public function eCopyingVerifiedUser($email, $mobile, $status){
        $email = urlencode($email);
        $mobile = urlencode($mobile);
        //echo ICMIS_SERVICE_URL."/online_copying/eCopyingVerifiedUser?email=$email&mobile=$mobile&status=$status";
        //die;
        $data = curl_get_contents(ICMIS_SERVICE_URL."/online_copying/eCopyingVerifiedUser?email=$email&mobile=$mobile&status=$status");
        if ($data != false) {
            return json_decode($data);
        } else{
            return NULL;
        }  
    }
    public function eCopyingVerifiedUserAOR($email, $mobile, $status, $bar_id){
        $email = urlencode($email);
        $mobile = urlencode($mobile);
        $data = curl_get_contents(ICMIS_SERVICE_URL."/online_copying/eCopyingVerifiedUserAOR?email=$email&mobile=$mobile&status=$status&bar_id=$bar_id");
        if ($data != false) {
            return json_decode($data);
        } else{
            return NULL;
        }  
    }

    public function getEcopyingDashbordData($dashboard_flag, $mobile, $email){
        $email = urlencode($email);
        $mobile = urlencode($mobile);
        // $data = file_get_contents(ICMIS_SERVICE_URL."/online_copying/getEcopyingDashbordData?dashboard_flag=$dashboard_flag&email=$email&mobile=$mobile");
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => ICMIS_SERVICE_URL."/online_copying/getEcopyingDashbordData?dashboard_flag=$dashboard_flag&email=$email&mobile=$mobile",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        $recent_documents_str_advocate_others = curl_exec($curl);
        curl_close($curl);
        $data = $recent_documents_str_advocate_others;
        if ($data != false) {
            
            return json_decode($data);
        } else {
            return NULL;
        }      
    }
    public function getserviceIDAccordingIP($service_key,$allowedIpMethod3){
        $service_key = urlencode($service_key);
        $allowedIpMethod3 = urlencode($allowedIpMethod3);
        // $data = file_get_contents(ICMIS_SERVICE_URL."/online_copying/getEcopyingDashbordData?dashboard_flag=$dashboard_flag&email=$email&mobile=$mobile");
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => ICMIS_SERVICE_URL."/online_copying/getserviceIDAccordingIP?service_key=$service_key&allowedIpMethod3=$allowedIpMethod3",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        $recent_documents_str_advocate_others = curl_exec($curl);
        curl_close($curl);
        $data = $recent_documents_str_advocate_others;
        if ($data != false) {
            
            return json_decode($data);
        } else{
            return NULL;
        }      
    }
}