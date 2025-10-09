<?php
namespace App\Libraries\webservices;

class Highcourt_webservices {

    function encrypted_request_token($input_param){
        return hash_hmac('sha256', $input_param,OPENAPI_HASHHMAC_KEY);
    }

    function encrypted_request_string($input_param){
        $encrypt=@openssl_encrypt($input_param, 'AES-128-CBC', OPENAPI_KEY, OPENSSL_RAW_DATA, OPENAPI_IV);
        return base64_encode($encrypt);
    }

    function decrypt_response($response){
        $response = json_encode($response);
        $response = json_decode($response, true);
        $response = json_decode($response);
        
        if(isset($response->response_str)) {
            $response_str = $response->response_str;
            //----Response  String-----
            $response_data = base64_decode($response_str);
            $response_decrypt = @openssl_decrypt($response_data, 'AES-128-CBC', OPENAPI_KEY, OPENSSL_RAW_DATA, OPENAPI_IV);
        } else {
            $response_decrypt = null;
        }
        return $response_decrypt;
    }

    public function actMaster(){
        $input_param='';
        $request_token = $this->encrypted_request_token($input_param);
        $url="https://api.ecourts.gov.in/high_court/master/act?dept_id=".rawurlencode(OPENAPI_DEPT_NO)."&request_token=".rawurlencode($request_token)."&version=".rawurlencode(OPENAPI_VERSION);
        $response_str = @file_get_contents($url);
        $response = $this->decrypt_response($response_str);
        return $response;
    }

    public function caseTypeMaster($est_code){
        if(is_null($est_code) || trim($est_code)=='')
            return json_encode(array('error'=>'Establishment code is required'));
        $input_param="est_code=".$est_code;
        $request_token = $this->encrypted_request_token($input_param);
        $request_str = $this->encrypted_request_string($input_param);
        $url="https://api.ecourts.gov.in/high_court/master/caseType?dept_id=".rawurlencode(OPENAPI_DEPT_NO)."&request_str=".rawurlencode($request_str)."&request_token=".rawurlencode($request_token)."&version=".rawurlencode(OPENAPI_VERSION);
        $response_str = @file_get_contents($url);
        //var_dump($this->decrypt_response($response_str));
        return $this->decrypt_response($response_str);
    }


    /******************
    Establishment API
    1. State
    2. District
    3. Bench
     */
    public function states(){
        $input_param='';
        $request_token = $this->encrypted_request_token($input_param);
        $url="https://api.ecourts.gov.in/high_court/estlocation/state?dept_id=".rawurlencode(OPENAPI_DEPT_NO)."&request_token=".rawurlencode($request_token)."&version=".rawurlencode(OPENAPI_VERSION);
        $response_str = @file_get_contents($url);
        $response = $this->decrypt_response($response_str);
        //var_dump($response);
        return $response;
    }

    public function districts($state_code){
        if(is_null($state_code) || trim($state_code)=='')
            return json_encode(array('error'=>'State code is required'));
        $input_param='state_code='.$state_code;
        $request_token = $this->encrypted_request_token($input_param);
        $request_str = $this->encrypted_request_string($input_param);
        $url="https://api.ecourts.gov.in/high_court/estlocation/district?dept_id=".rawurlencode(OPENAPI_DEPT_NO)."&request_str=".rawurlencode($request_str)."&request_token=".rawurlencode($request_token)."&version=".rawurlencode(OPENAPI_VERSION);
        $response_str = @file_get_contents($url);
        $response = $this->decrypt_response($response_str);
        //var_dump($response);
        return $response;
    }

    public function bench($state_code){

        if(is_null($state_code) || trim($state_code)=='')
            return json_encode(array('error'=>'State code is required'));
        $input_param='state_code='.$state_code;
        $request_token = $this->encrypted_request_token($input_param);
        $request_str = $this->encrypted_request_string($input_param);
        $url="https://api.ecourts.gov.in/high_court/estlocation/bench?dept_id=".rawurlencode(OPENAPI_DEPT_NO)."&request_str=".rawurlencode($request_str)."&request_token=".rawurlencode($request_token)."&version=".rawurlencode(OPENAPI_VERSION);
        $response_str = @file_get_contents($url);
        $response = $this->decrypt_response($response_str);
        //var_dump($response);
        return $response;
    }


    /**********************
    CASE SEARCH APIs
    1. Search by CNR No.
    3. Show Order
    5. Search by Case Number
    7. Search by Party Name
    8. Search by FIR Number
     */

    public function by_cino($cino){
        $input_param="cino=".$cino;
        $request_token = $this->encrypted_request_token($input_param);
        $request_str = $this->encrypted_request_string($input_param);
        $url="https://api.ecourts.gov.in/high_court/casesearch/cnrFullCaseDetails?dept_id=".rawurlencode(OPENAPI_DEPT_NO)."&request_str=".rawurlencode($request_str)."&request_token=".rawurlencode($request_token)."&version=".rawurlencode(OPENAPI_VERSION);
        $response_str = @file_get_contents($url);
        return $this->decrypt_response($response_str);
    }

    public function case_status_by_cino($cino){
        $input_param="cino=".$cino;
        $request_token = $this->encrypted_request_token($input_param);
        $request_str = $this->encrypted_request_string($input_param);
        $url="https://api.ecourts.gov.in/high_court/casesearch/currentStatus?dept_id=".rawurlencode(OPENAPI_DEPT_NO)."&request_str=".rawurlencode($request_str)."&request_token=".rawurlencode($request_token)."&version=".rawurlencode(OPENAPI_VERSION);
        $response_str = @file_get_contents($url);
        return $this->decrypt_response($response_str);
    }

    public function show_orders($cino, $order_no, $order_date){
        $order_date = date('Y-m-d', strtotime($order_date));
        $input_param="cino=$cino|order_no=$order_no|order_date=$order_date";
        $request_token = $this->encrypted_request_token($input_param);
        $request_str = $this->encrypted_request_string($input_param);
        $url="https://api.ecourts.gov.in/high_court/casesearch/order?dept_id=".rawurlencode(OPENAPI_DEPT_NO)."&request_str=".rawurlencode($request_str)."&request_token=".rawurlencode($request_token)."&version=".rawurlencode(OPENAPI_VERSION);
        $response_str = @file_get_contents($url);
        return $this->decrypt_response($response_str);
    }

    public function by_case_no($est_code, $case_type, $reg_no, $reg_year){
        // pr($est_code .'---'. $case_type .'---'. $reg_no .'---'. $reg_year);
        $input_param="est_code=$est_code|case_type=$case_type|reg_no=$reg_no|reg_year=$reg_year";
        $request_token = $this->encrypted_request_token($input_param);
        $request_str = $this->encrypted_request_string($input_param);
        $url="https://api.ecourts.gov.in/high_court/casesearch/caseNumber?dept_id=".rawurlencode(OPENAPI_DEPT_NO)."&request_str=".rawurlencode($request_str)."&request_token=".rawurlencode($request_token)."&version=".rawurlencode(OPENAPI_VERSION);
        $response_str = @file_get_contents($url);
        return $this->decrypt_response($response_str);
    }


    public function by_party($est_code, $pend_disp, $litigant_name, $reg_year){
        if(strlen($litigant_name)<3 || strlen($litigant_name)>99)
            return json_encode(array('error'=>'Litigant Name must have more than '));
        $input_param="est_code=$est_code|pend_disp=$pend_disp|litigant_name=$litigant_name|reg_year=$reg_year";
        $request_token = $this->encrypted_request_token($input_param);
        $request_str = $this->encrypted_request_string($input_param);
        $url="https://api.ecourts.gov.in/high_court/casesearch/partyName?dept_id=".rawurlencode(OPENAPI_DEPT_NO)."&request_str=".rawurlencode($request_str)."&request_token=".rawurlencode($request_token)."&version=".rawurlencode(OPENAPI_VERSION);
        $response_str = @file_get_contents($url);
        return $this->decrypt_response($response_str);
    }

    public function by_fir($est_code, $police_station_code, $fir_no='', $fir_year=''){
        $input_param="est_code=$est_code|police_station_code=$police_station_code";
        if(trim($fir_no)!='')
            $input_param.="|fir_no=$fir_no";
        if(trim($fir_year)!='')
            $input_param.="|fir_year=$fir_year";
        $request_token = $this->encrypted_request_token($input_param);
        $request_str = $this->encrypted_request_string($input_param);
        $url="https://api.ecourts.gov.in/high_court/casesearch/firNumber?dept_id=".rawurlencode(OPENAPI_DEPT_NO)."&request_str=".rawurlencode($request_str)."&request_token=".rawurlencode($request_token)."&version=".rawurlencode(OPENAPI_VERSION);
        $response_str = @file_get_contents($url);
        return $this->decrypt_response($response_str);
    }


}
