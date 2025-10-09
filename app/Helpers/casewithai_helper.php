<?php
/*Start cron code load balancer server (LBS)*/
use App\Models\AIAssisted\CommonCasewithAIModel;
use Config\Database;

    function is_available_for_triggered($api_type=null,$id = null)
    {
        $db = Database::connect('default');
        $builder = $db->table('efil.tbl_round_robin_api_iitm');
        $builder->SELECT("*");
        if (!empty($api_type)) {
            $builder->WHERE('api_type', $api_type);
        }
        if (!empty($id)) {
            $builder->WHERE('id', $id);
        }
        $builder->WHERE('is_deleted', FALSE);
        $builder->orderBy('no_of_times_already_triggered', 'ASC');
        $query = $builder->get();
        $resultArr=array();
        if ($query->getNumRows() >= 1) {
            $apis = $query->getResultArray();
            if(!empty($apis)) {
                // Filter the available APIs
                $availableApis = array_filter($apis, function($api) {
                    return $api['is_available_for_triggered'] == 1;
                });
                if (empty($availableApis)) {
                    //echo "No available APIs to call.<br>";
                    return;
                }
                // Sort available APIs by triggered_date (oldest first)
                usort($availableApis, function($a, $b) {
                    return strtotime($a['triggered_date']) - strtotime($b['triggered_date']);
                });
                // Select the first available API (oldest triggered_date)
                $selectedApi = &$availableApis[0];
                // Echo which API row is being triggered
                // echo "Triggering API with ID: " . $selectedApi['id'] . ", URL: " . $selectedApi['api_url'] . "<br>";
                // Save the start time
                $selectedApi['last_triggered_started_on'] = date('Y-m-d H:i:s');
                // Set it as busy
                $selectedApi['is_available_for_triggered'] = 0;
                $updateStartSql = array(
                    'last_triggered_started_on' => $selectedApi['last_triggered_started_on'],
                    'triggered_ip_address' => getClientIP(),
                    'is_available_for_triggered'=>0,
                );
                $builder = $db->table('efil.tbl_round_robin_api_iitm');
                $builder->WHERE('id', $selectedApi['id']);
                $builder->WHERE('is_deleted', FALSE);
                $builder->set($updateStartSql);
                $builder->UPDATE();
                if ($db->affectedRows() > 0) {
                    $resultArr=$selectedApi;
                }

            }

        }
        return $resultArr;
    }


    function curl_get_contents_casewithAICronLB($filePath, $selectedApi)
    {
        if (function_exists('curl_file_create')) {
            $file = curl_file_create($filePath, 'application/pdf', 'pdf_file.pdf');
        } else {
            $file = '@' . realpath($filePath);
        }
        if (!empty($selectedApi)){
            $url=$selectedApi['api_url'];
            $x_api_key=$selectedApi['api_key'];
            $pdf_file=$selectedApi['pdf_file'];

        }else{
            return false;
            $response_msg='Triggered is required*';
            echo $response_msg;
            exit();
        }
        if (!empty($pdf_file)){
            $data = array("$pdf_file" => $file);
        }else{
            $data = array('pdf_file' => $file);
        }

        //echo '<pre>';print_r($round_robinArr);exit();

        // $url = 'https://api.sci.iitmpravartak.org.in/data_extract';
        $x_api_key =isset($x_api_key) && !empty($x_api_key) ? $x_api_key : 'kFv7$_9rG';
        $headers = array(
            "Content-Type: multipart/form-data",
            "x-api-key: $x_api_key"
        );
        /* echo '<pre>';print_r($round_robinArr);
         echo '<pre>';print_r($data);
         echo '<pre>';print_r($headers);
         exit();*/
        //$data = array('pdf_file' => $file,);
        $curl = curl_init();
// Set the cURL options
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            echo 'URL error: ' . curl_error($curl);
            $response=null;
        } else {
        }
        curl_close($curl);

        //$response = "Simulated response for API ID " . $selectedApi['id'];
        /*$response= "API call completed for ID: " . $selectedApi['id'] . ", URL: " . $selectedApi['api_url'] . "<br>";
        $response= "Response: " . $response . "<br>";
        //$response='test';
        echo $response;*/

            // Simulate the API call
            //$response = "Simulated response for API ID " . $selectedApi['id'];
            // Save the completion time
            $selectedApi['last_triggered_completed_on'] = date('Y-m-d H:i:s');
            // Increment the trigger count
            $selectedApi['no_of_times_already_triggered'] += 1;
            // Set it as available again
            $selectedApi['is_available_for_triggered'] = 1;
            // Update the triggered_date for round-robin purposes
            $selectedApi['triggered_date'] = date('Y-m-d');

            $updateCompleteSql = array(
                'last_triggered_completed_on' => $selectedApi['last_triggered_completed_on'],
                'is_available_for_triggered'=>1,
                'no_of_times_already_triggered'=>$selectedApi['no_of_times_already_triggered'],
                'triggered_date'=>$selectedApi['triggered_date'],
            );
            $db = Database::connect('default');
            $builder = $db->table('efil.tbl_round_robin_api_iitm');
            $builder->WHERE('id', $selectedApi['id']);
            $builder->WHERE('is_deleted', FALSE);
            $builder->set($updateCompleteSql);
            $builder->UPDATE();
        return $response;
    }
function cron_json_data_extract_empt_callAPILB($id=null)
{
    $db = Database::connect('default');
    $builder = $db->table('efil.tbl_uploaded_pdfs_jsonai');
    $builder->SELECT("*");
    $builder->WHERE("(iitm_api_json is null or iitm_api_json ='' or iitm_api_json ='Internal Server Error' or iitm_api_json ='internal server error' or iitm_api_json ILIKE '%Another request is already running%' or iitm_api_json ILIKE '%Please try again later%')");
    if (!empty($id)){ $builder->WHERE('id', $id); }
    $builder->WHERE('is_deleted', FALSE);
    $builder->WHERE('api_stage_id', 1);
    $builder->WHERE('registration_id is null');
    $builder->orderBy('uploaded_on','DESC');
    $query = $builder->get();
    if ($query->getNumRows() >= 1) {
        $resultArr= $query->getResultArray();
        if (!empty($resultArr)){
            foreach ($resultArr as $row){
                //if ((empty($row['iitm_api_json']) || strtolower($row['iitm_api_json'])=='internal server error') && !empty($row['doc_title'])
                if (!empty($row['doc_title']) && !empty($row['file_type']) && $row['file_path'] && $row['id']){
                    if (file_exists($row['file_path'])) {
                        $selectedApi=is_available_for_triggered('1');
                        $selectedApi_id=isset($selectedApi['id']) && !empty($selectedApi['id']) ? $selectedApi['id']: null;
                        if (!empty($selectedApi_id) && !empty($selectedApi)) {
                            $response = curl_get_contents_casewithAICronLB($row['file_path'], $selectedApi);
                            if (!empty($response)) {
                                $uploaded_on = date('Y-m-d H:i:s');
                                $data_update = array(
                                    'iitm_api_json' => $response,
                                    'is_active_iitm' => true,
                                    'iitm_api_json_updated_on' => $uploaded_on,
                                    'tbl_round_robin_api_iitm_id' => $selectedApi_id,
                                );
                                $builder = $db->table('efil.tbl_uploaded_pdfs_jsonai');
                                $builder->WHERE('id', $row['id']);
                                $builder->WHERE('is_deleted', FALSE);
                                $builder->set($data_update);
                                $response_updated = $builder->UPDATE();
                                echo $row['file_path'].'<pre>';
                                print_r($response);//exit();
                            }
                        }
                    }
                }
            }
        }

    }
}

/*End cron code load balancer server (LBS)*/


function cron_json_data_extract_empt_callAPILB_defect($id=null)
{
    $db = Database::connect('default');
    $builder = $db->table('efil.tbl_uploaded_pdfs_jsonai');
    $builder->SELECT("*");
    
    $builder->WHERE("(iitm_api_json_defect is null or iitm_api_json_defect='' or iitm_api_json_defect='Internal Server Error' or iitm_api_json_defect ILIKE '%Another request is already running%' or iitm_api_json_defect ILIKE '%Please try again later%')");
    if (!empty($id)){ $builder->WHERE('id', $id); }
    $builder->WHERE('is_deleted', FALSE);
    $builder->WHERE('api_stage_id', 1);
    $builder->WHERE('registration_id is null');
    //$ci->db->LIMIT(30);
    $builder->orderBy('uploaded_on','DESC');
    $query = $builder->get();
    //echo $ci->db->last_query();exit();
    if ($query->getNumRows() >= 1) {
        $resultArr= $query->getResultArray();
        //echo '<pre>';print_r($resultArr);exit();
        if (!empty($resultArr)){
            foreach ($resultArr as $row){
                //if ((empty($row['iitm_api_json_defect']) || strtolower($row['iitm_api_json_defect'])=='internal server error') && !empty($row['doc_title'])  && !empty($row['file_type']) && $row['file_path'] && $row['id']){
                if (!empty($row['doc_title'])  && !empty($row['file_type']) && $row['file_path'] && $row['id']){
                    if (file_exists($row['file_path'])) {
                        $selectedApi=is_available_for_triggered('2');
                        $selectedApi_id=isset($selectedApi['id']) && !empty($selectedApi['id']) ? $selectedApi['id']: null;
                        if (!empty($selectedApi_id) && !empty($selectedApi)) {
                            $response = curl_get_contents_casewithAICronLB($row['file_path'], $selectedApi);
                            if (!empty($response)) {
                                $uploaded_on = date('Y-m-d H:i:s');
                                $data_update = array(
                                    'iitm_api_json_defect' => $response,
                                    'is_active_iitm' => true,
                                    'iitm_api_json_defect_updated_on' => $uploaded_on,
                                    'tbl_round_robin_api_iitm_id_defect' => $selectedApi_id,
                                );
                                $builder = $db->table('efil.tbl_uploaded_pdfs_jsonai');
                                $builder->WHERE('id', $row['id']);
                                $builder->WHERE('is_deleted', FALSE);
                                $builder->set($data_update);
                                $response_updated = $builder->UPDATE();
                                echo $row['file_path'].'<pre>';
                                print_r($response);//exit();
                            }
                        }
                    }
                }
            }
        }

    }
}

/*End cron code load balancer server (LBS)*/



if (!function_exists('casewithAI')) {

    function casewithAI($id)
    {
        $Common_casewithAI_model = new CommonCasewithAIModel();
         $response_result = $Common_casewithAI_model->get_casewithAI_data_extract($id,'API');
        return $response_result;
    }

}

if (!function_exists('casewithAIByregistration_id')) {
    function casewithAIByregistration_id($registration_id)
    {
        if (!empty($registration_id)){
            $db = Database::connect('default');
            $builder = $db->table('efil.tbl_uploaded_pdfs_jsonai');
            $builder->SELECT("*");
            
            $builder->WHERE('registration_id', $registration_id);
            $builder->WHERE('is_active_iitm',true);
            $builder->WHERE('is_deleted', FALSE);
            $builder->orderBy('uploaded_on','DESC');
            $builder->LIMIT(1);
            $query = $builder->get();
            //echo $this->db->last_query();exit();
            if ($query->getNumRows() >= 1) {
                $Common_casewithAI_model = new CommonCasewithAIModel();
                $response_result = $Common_casewithAI_model->get_casewithAI_data_extract($registration_id);
                return $response_result;
            }
        }

    }
}
if (!function_exists('casewithAIUpdate')) {
    function casewithAIUpdate($registration_id,$efiling_no)
    {
        $db = Database::connect('default');
        if (isset($_SESSION['casewithAI']) && !empty($_SESSION['casewithAI']) && !empty($registration_id) && !empty($efiling_no)) {
            if (isset($_SESSION['casewithAI'][0]['id']) && !empty($_SESSION['casewithAI'][0]['id'])) {
                $data_update = array('registration_id' => $registration_id,'efiling_no'=>$efiling_no);
                $builder = $db->table('efil.tbl_uploaded_pdfs_jsonai');
                $builder->WHERE('id', $_SESSION['casewithAI'][0]['id']);
                $builder->WHERE('is_deleted', FALSE);
                $builder->WHERE('is_active_iitm', true);
                $builder->set($data_update);
                $builder->UPDATE();
                if ($db->affectedRows() > 0) {
                    $res_result=casewithAIByregistration_id($registration_id);
                    casewithAI_UploadPdfIntomain($registration_id);
                    return TRUE;
                }else{
                    return TRUE;
                }
            }
        }
    }
}

if (!function_exists('casewithAI_UploadPdfIntomain')) {
    function casewithAI_UploadPdfIntomain($registration_id)
    {
        if (!empty($registration_id) && !empty($_SESSION['efiling_details'])){
            $db = Database::connect('default');
            $builder = $db->table('efil.tbl_uploaded_pdfs_jsonai');
            $builder->SELECT("*");
            
            $builder->WHERE('registration_id', $registration_id);
            $builder->WHERE('is_active_iitm',true);
            $builder->WHERE('is_deleted', FALSE);
            $builder->orderBy('uploaded_on','DESC');
            $builder->LIMIT(1);
            $query = $builder->get();
            //echo $this->db->last_query();exit();
            if ($query->getNumRows() >= 1) {
                $response_result= $query->getResultArray();
                if (!empty($response_result)){
                    $response_result=$response_result[0];
                }
                $Common_casewithAI_model = new CommonCasewithAIModel();
                $response_result = $Common_casewithAI_model->uploadPdfIntomain($response_result,$registration_id);

                return $response_result;
            }
        }

    }

}

function getCourtTypeAndState($metadata_extracted_court_type_string) {
    // $metadata_extracted_court_type_string='SUPREME Court of Jharkhand at Ranchi'
    // Sample string to check which is returing from IITM API in the court_type field
    if (is_array($metadata_extracted_court_type_string)) {
        $metadata_extracted_court_type_string = isset($metadata_extracted_court_type_string[0]) && $metadata_extracted_court_type_string[0] != 'NA' ? $metadata_extracted_court_type_string[0] : '';
    }
    $string = (!empty($metadata_extracted_court_type_string)?$metadata_extracted_court_type_string:null);
    // Array of high courts to check for
    $highCourtsNameAsPerEfilMasters = [
        'Allahabad', 'Allahabad Bench', 'Bombay', 'Calcutta', 'Chhattisgarh', 'Delhi',
        'Gauhati', 'Gujarat', 'Himachal Pradesh', 'Jammu & Kashmir', 'Jharkhand',
        'Karnataka', 'Kerala', 'Lucknow Bench', 'Madhya Pradesh', 'Madras',
        'Manipur', 'Meghalaya', 'Orissa', 'Patna', 'Punjab & Haryana', 'Rajasthan',
        'Sikkim', 'Supreme Court of India', 'Telangana & Andhra Pradesh',
        'Tripura', 'Uttarakhand'
    ];
    // 1. Check for the presence of phrases "high court", "supreme court", "district court", or "state agency" (case insensitive)
    $phrasePattern = '/\b(high court|supreme court|district court|state agency)\b/i';
    $stateToCheck = null;
    $state = null;
    $courtType = null;
    $highCourtsResult=array();
    if(!empty($string)) {
        // Check for phrases in the string
        if (preg_match($phrasePattern, $string, $matches)) {
            $courtType = strtolower($matches[0]);
            if ($courtType === 'supreme court') {
                // If 'Supreme Court' is matched, set the state name to 'Delhi'
                $state = 'Delhi';
            } else{
                // Otherwise, use the list of high courts names
                $stateToCheck = implode('|', $highCourtsNameAsPerEfilMasters);
            }
        }
        // 2. Check for the combination of "high court" and any of the specified high courts in any order
        if ($stateToCheck !== null && $courtType !== 'supreme court') {
            if ($courtType === 'high court') {
                $statePattern = '/\bhigh court\b.*?\b(' . $stateToCheck . ')\b/i';
                if (preg_match($statePattern, $string, $stateMatches)) {
                    // print_r($stateMatches);
                    $state=$stateMatches[1];
                }
            }
        }
    }
    $court_db_id=array();
    $court_type='';
    if (!empty($state)) {
        $court_db_id = get_high_courts_id($state);
    }
    if (isset($courtType) && ucwords($courtType) == 'High Court') {
        $court_type = 1;
    } else if (isset($courtType) && ucwords($courtType) == 'Supreme Court') {
        $court_type = 4;
    }
    $highCourtsResult = [
        'court_type_name_api' => ucwords($courtType),
        'state_name_api' => $state,
        'court_type' => $court_type
    ];
    if (!empty($court_db_id)) {
        $highCourtsResult=array_merge($highCourtsResult,$court_db_id);
    }
    // echo '<pre>'; print_r($highCourtsResult); exit();
    return $highCourtsResult;
}

if (!function_exists('get_high_courts_id')) {
    function get_high_courts_id($high_court_name)
    {

        if (!empty($high_court_name)){
            $db = Database::connect('default');
            $builder = $db->table('efil.m_tbl_high_courts_bench');
            $builder->DISTINCT();
            $builder->SELECT('hc_id, name');
            
            $builder->WHERE('bench_id is null and est_code is null');
            //$this->db->LIKE('name', $high_court_name);
            $builder->like('name',$high_court_name,'','!',true);
            $builder->LIMIT(1);
            $query= $builder->get();
            //echo $this->db->last_query();exit();
            $result= $query->getResultArray();
            if (!empty($result)){
                return $result[0];
            }
            return $result;
            $Common_casewithAI_model = new CommonCasewithAIModel();
            $response_result = $Common_casewithAI_model->get_high_courts_id($high_court_name);
            return $response_result;
        }

    }
}
if (!function_exists('AIAssisted')) {
    function AIAssisted($registration_id)
    {
        $response=null;
        if (!empty($registration_id)){
            $db = Database::connect('default');
            $builder = $db->table('efil.tbl_uploaded_pdfs_jsonai');
            $builder->SELECT("registration_id");
            
            $builder->WHERE('registration_id', $registration_id);
            $builder->WHERE('iitm_api_json is not null');
            $builder->WHERE('is_active_iitm',true);
            $builder->WHERE('is_deleted', FALSE);
            $builder->orderBy('uploaded_on','DESC');
            $builder->LIMIT(1);
            $query = $builder->get();
            if ($query->getNumRows() >= 1) {
                $response='AI Assisted';
            }
        }
        return $response;

    }
}
