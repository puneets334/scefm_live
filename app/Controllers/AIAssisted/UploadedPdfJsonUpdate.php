<?php

namespace App\Controllers\AIAssisted;

use App\Controllers\BaseController;
use App\Libraries\webservices\Efiling_webservices;
use App\Models\AIAssisted\CommonCasewithAIModel;
use App\Models\AIAssisted\IitmIcmisEfmApiJsonModel;
use App\Models\Common\CommonModel;
use App\Models\IA\ViewModel;
use App\Models\NewCase\GetDetailsModel;
use stdClass;

class UploadedPdfJsonUpdate extends BaseController {
    protected $Common_model;
    protected $Get_details_model;
    protected $efiling_webservices;
    protected $View_model;
    protected $Iitm_Icmis_Efm_Api_Json_Model;
    protected $encrypter;
    public function __construct() {

        parent::__construct();
        $this->encrypter = \Config\Services::encrypter();
        $this->Common_model = new CommonModel();
        $this->Get_details_model = new GetDetailsModel();
        $this->efiling_webservices = new Efiling_webservices();
        $this->View_model = new ViewModel();
        $this->Iitm_Icmis_Efm_Api_Json_Model = new IitmIcmisEfmApiJsonModel();

    }

    public function update_uploaded_pdf_json_data($fromDate,$toDate,$efileno=''){

        $this->efile_json_update($fromDate,$toDate,$efileno);
        $this->icmis_json_update($fromDate,$toDate,$efileno);
        $this->iitm_json_update($fromDate,$toDate,$efileno);

    }

    private function iitm_json_update($fromDate,$toDate,$efileno){

        $file_path_base_url = 'http://10.192.105.105:91/';
        $case_details = $this->Iitm_Icmis_Efm_Api_Json_Model->get_uploaded_pdf_details($fromDate,$toDate,$efileno,'iitm');
        $result_array = [];
        foreach($case_details as $case_detail){
            $result_array[$case_detail->registration_id]['filepath'][]  = $file_path_base_url.$case_detail->file_path;
            $result_array[$case_detail->registration_id]['case_detail'] = $case_detail;
        }
        foreach($result_array as $registrtion_id_files){

            if(count($registrtion_id_files['filepath'])>1){
            }else{
                $result = $this->Iitm_Icmis_Efm_Api_Json_Model->get_uploaded_pdf_detail_data($registrtion_id_files['case_detail']->registration_id);
                if(isset($result)){
                    if(is_null($result->iitm_api_json) || empty($result->iitm_api_json) || $result->iitm_api_json='Internal Server Error' || str_contains($result->iitm_api_json,'Another request is already running') || str_contains($result->iitm_api_json,'Please try again later')){

                        $selectedApi    = is_available_for_triggered('1');
                        $selectedApi_id = isset($selectedApi['id']) && !empty($selectedApi['id']) ? $selectedApi['id']: null;

                        if (!empty($selectedApi_id) && !empty($selectedApi)) {
                            $response = curl_get_contents_casewithAICronLB($registrtion_id_files['filepath'][0], $selectedApi);
                            if(isset($response) && !empty($response)){
                                $update_res = $this->Iitm_Icmis_Efm_Api_Json_Model->update_iitm_api_json($registrtion_id_files['case_detail'],$response,'update');
                            }
                        }

                    }
                }else{
                    $selectedApi    = is_available_for_triggered('1');
                    $selectedApi_id = isset($selectedApi['id']) && !empty($selectedApi['id']) ? $selectedApi['id']: null;

                    if (!empty($selectedApi_id) && !empty($selectedApi)) {
                        $response = curl_get_contents_casewithAICronLB($registrtion_id_files['filepath'][0], $selectedApi);
                        if(isset($response) && !empty($response)){
                            $update_res = $this->Iitm_Icmis_Efm_Api_Json_Model->update_iitm_api_json($registrtion_id_files['case_detail'],$response,'insert');
                        }
                    }
                }
            }
        }
    }

    private function efile_json_update($fromDate,$toDate,$efileno){

        $case_details = $this->Iitm_Icmis_Efm_Api_Json_Model->get_uploaded_pdf_details($fromDate,$toDate,$efileno,'efile');
        foreach($case_details as $case_detail){
            $response = $this->getAllFilingDetailsByRegistrationId($case_detail->efiling_no,$case_detail->registration_id);
            if(isset($response) && !empty($response)){
                $update_res = $this->Iitm_Icmis_Efm_Api_Json_Model->update_efiiling_json($case_detail,$response);
            }
        }
    }

    private function icmis_json_update($fromDate,$toDate,$efileno){

        $case_details = $this->Iitm_Icmis_Efm_Api_Json_Model->get_uploaded_pdf_details($fromDate,$toDate,$efileno,'icmis');
        foreach($case_details as $case_detail){
            $response = $this->efiling_webservices->getGeneratedCaseDiary($case_detail->efiling_no,$case_detail->registration_id);
            if(isset($response) && !empty($response)){
                $update_res = $this->Iitm_Icmis_Efm_Api_Json_Model->update_icmis_api_json($case_detail,$response);
            }
        }
    }

    private function getAllFilingDetailsByRegistrationId($efiling_no=NULL,$registration_id=NULL)
    {

        $registration_id = !empty($registration_id) ?$registration_id:NULL;
        $finalArr = array();
        if (isset($registration_id) && !empty($registration_id)) {
            $paramArray = array();
            $paramArray['registration_id'] = $registration_id;
            $userIcmisCode=$this->efiling_webservices->getDiaryUserCodeFromICMIS();
            $userIcmisCode=$userIcmisCode['icmis_diary_user_usercode'];
            if(empty($userIcmisCode))
            {
                $userIcmisCode=env('ADMIN_AUTO_DIARY_ICMIS_USER_CODE');
            }

            $case_details = $this->Get_details_model->get_new_case_details($registration_id);

            $tmpArr = array();
            if (isset($case_details[0]) && !empty($case_details[0])) {

                $tmpArr['casetype_id'] = !empty($case_details[0]['sc_case_type_id']) ? $case_details[0]['sc_case_type_id'] : NULL;
                $tmpArr['diary_user_id'] = $userIcmisCode;
                $tmpArr['pno'] = !empty($case_details[0]['no_of_petitioners']) ? $case_details[0]['no_of_petitioners'] : NULL;
                $tmpArr['rno'] = !empty($case_details[0]['no_of_respondents']) ? $case_details[0]['no_of_respondents'] : NULL;

                $mainParties=explode('Vs.',$case_details[0]['cause_title']);
                $tmpArr['pet_name_causetitle']=$mainParties[0];
                $tmpArr['res_name_causetitle']=$mainParties[1];
                $tmpArr['subject_cat'] = !empty($case_details[0]['category_name']) ? $case_details[0]['category_name'] : NULL;
                $tmpArr['subj_main_cat'] = !empty($case_details[0]['subj_main_cat']) ? $case_details[0]['subj_main_cat'] : NULL;
                $tmpArr['sc_sp_case_type_id'] = !empty($case_details[0]['sc_sp_case_type_id']) ? $case_details[0]['sc_sp_case_type_id'] : NULL;
                $tmpArr['jail_signature_date'] = !empty($case_details[0]['jail_signature_date']) ? $case_details[0]['jail_signature_date'] : NULL;
                $tmpArr['efiling_no'] = !empty($case_details[0]['efiling_no']) ? $case_details[0]['efiling_no'] : NULL;
                $tmpArr['if_sclsc']=!empty($case_details[0]['if_sclsc'])?$case_details[0]['if_sclsc']:0;
                $tmpArr['special_category']=!empty($case_details[0]['special_category'])?$case_details[0]['special_category']:0;
                $created_by = !empty($case_details[0]['created_by']) ? $case_details[0]['created_by'] : NULL;

                if (isset($created_by) && !empty($created_by)) {
                    $userData = $this->Common_model->getUserDetailsById($created_by);
                    if (isset($userData) && !empty($userData)) {
                        $adv_sci_bar_id = !empty($userData[0]['adv_sci_bar_id']) ? (int)$userData[0]['adv_sci_bar_id'] : NULL;
                        if (isset($adv_sci_bar_id) && !empty($adv_sci_bar_id)) {
                            $getBarData = $this->Common_model->getBarDetailsById($adv_sci_bar_id);
                            $bar_id = !empty($getBarData[0]['bar_id']) ? $getBarData[0]['bar_id'] : NULL;
                            /* $pp = !empty($getBarData[0]['pp']) ? 'P' : 'A';
                            $tmpArr['pet_adv_id'] = !empty($getBarData[0]['pp']) ? PETITIONER_IN_PERSON : $bar_id; */
                            // This portion of code is commented on 13052025, as PP field has no significant for AOR or PIP in the BAR table as per ICMIS team
                            $pp = !empty($bar_id) ? 'A' : 'P';
                            $tmpArr['pet_adv_id'] = !empty($bar_id) ? $bar_id : PETITIONER_IN_PERSON;
                            $tmpArr['adv_pp'] = $pp;
                        }
                        else{
                            $tmpArr['pet_adv_id'] = PETITIONER_IN_PERSON;
                            $tmpArr['adv_pp'] = 'P';
                        }
                    }
                }
            }
            $courtData = $this->Iitm_Icmis_Efm_Api_Json_Model->getEarlierCourtDetailByRegistrationId($registration_id);
            $court_type = !empty($case_details[0]['court_type']) ? (int)$case_details[0]['court_type'] : NULL;
            if(isset($court_type) && !empty($court_type)){
                switch($court_type){
                    case 1:
                        $tmpArr ['court_type'] = !empty($courtData[0]['court_type']) ? $courtData[0]['court_type'] : NULL;
                        $tmpArr ['cmis_state_id'] = !empty($courtData[0]['cmis_state_name']) ? $courtData[0]['cmis_state_name'] : NULL;
                        $tmpArr ['ref_agency_code_id'] = !empty($courtData[0]['ref_agency_code_name']) ? $courtData[0]['ref_agency_code_name'] : NULL;
                        break;
                    case 3:
                        $tmpArr ['court_type'] = !empty($courtData[0]['court_type']) ? $courtData[0]['court_type'] : NULL;
                        $tmpArr ['cmis_state_id'] = !empty($courtData[0]['cmis_state_name']) ? $courtData[0]['cmis_state_name'] : NULL;
                        $tmpArr ['ref_agency_code_id'] = !empty($courtData[0]['ref_agency_code_name']) ? $courtData[0]['ref_agency_code_name'] : NULL;
                        break;
                    case 4:
                        $tmpArr ['court_type'] = !empty($courtData[0]['court_type']) ? $courtData[0]['court_type'] : NULL;
                        break;
                    case 5:
                        $tmpArr ['court_type'] = !empty($courtData[0]['court_type']) ? $courtData[0]['court_type'] : NULL;
                        $tmpArr ['cmis_state_id'] = !empty($courtData[0]['cmis_state_name']) ? $courtData[0]['cmis_state_name'] : NULL;
                        $tmpArr ['ref_agency_code_id'] = !empty($courtData[0]['ref_agency_code_name']) ? $courtData[0]['ref_agency_code_name'] : NULL;
                        break;
                    default:

                }
            }
            $finalArr['case_details'] = !empty($tmpArr) ? array((object)$tmpArr) : array(new stdClass());
            $main_petitioner_details = $this->Get_details_model->get_case_parties_details($registration_id, array('p_r_type' => 'P', 'm_a_type' => 'M', 'party_id' => NULL, 'view_lr_list' => FALSE));
            $tempMainArr = array();
            if (isset($main_petitioner_details) && !empty($main_petitioner_details)) {
                foreach ($main_petitioner_details as $k => $v) {
                    $tempMainArr[] = (object)$v;
                }
            }
            $finalArr['main_petitioner'] = !empty($main_petitioner_details[0]) ? $tempMainArr : array(new stdClass());
            $respondent_details = $this->Get_details_model->get_case_parties_details($registration_id, array('p_r_type' => 'R', 'm_a_type' => 'M', 'party_id' => NULL, 'view_lr_list' => FALSE));
            $tempResArr = array();
            if (isset($respondent_details) && !empty($respondent_details)) {
                foreach ($respondent_details as $k => $v) {
                    $tempResArr[] = (object)$v;
                }
            }

            $finalArr['main_respondent'] = !empty($respondent_details[0]) ? $tempResArr : array(new stdClass());

            $extra_parties_list = $this->Get_details_model->get_case_parties_details($registration_id, array('p_r_type' => NULL, 'm_a_type' => 'A', 'party_id' => NULL, 'view_lr_list' => FALSE));
            $tempExtArr = array();
            if (isset($extra_parties_list) && !empty($extra_parties_list)) {
                foreach ($extra_parties_list as $k => $v) {
                    $tempExtArr[] = (object)$v;
                }
            }
            $finalArr['extra_party'] = !empty($extra_parties_list[0]) ? $tempExtArr : array(new stdClass());

            $subordinate_court_details = $this->Get_details_model->getSubordinateCourtData($registration_id);
            $court_type = '';
            $subTempArr = array();
            if (isset($subordinate_court_details) && !empty($subordinate_court_details)) {
                foreach ($subordinate_court_details as $k => $v) {
                    $params = array();
                    if(isset($v['registration_id']) && !empty($v['registration_id'])){
                        $params['table_name'] = 'efil.tbl_fir_details';
                        $params['whereFieldName'] = 'registration_id';
                        $params['whereFieldValue'] = (int)$v['registration_id'];
                        $firData = $this->Common_model->getData($params);
                        if(!isset($v['case_type_id']) && empty($v['case_type_id'])){
                            $v['case_type_id'] = 0;
                        }
                        if(isset($firData) && !empty($firData) && count($firData)>0){
                            $v['fir_details']['is_fir_details_exists'] =  true;
                            $v['fir_details']['firData'] =  array($firData[0]);
                        }
                        else{
                            $v['fir_details']['is_fir_details_exists'] =  false;
                            $v['fir_details']['firData']  =  array(new stdClass());
                        }
                        $subTempArr[$k] = (object)$v;
                    }
                }
            }
            $finalArr['earlier_courts'] = !empty($subordinate_court_details[0]) ? $subTempArr : array(new stdClass());
        }

        return json_encode($finalArr);
    }

    private function mergePDFs($files, $outputPath) {

        $quotedFiles = array_map('escapeshellarg', $files);
        $fileString  = implode(' ', $quotedFiles);
        $quotedOutputPath = escapeshellarg($outputPath);

        $command = "gs -dBATCH -dNOPAUSE -q -sDEVICE=pdfwrite -sOutputFile=$quotedOutputPath $fileString";

        exec($command . " 2>&1", $output, $returnVar); // Redirect stderr to stdout

        if ($returnVar === 0) {
            echo "PDFs merged successfully into $outputPath";
        } else {
            echo "Error merging PDFs. Command output: " . implode("\n", $output);
        }
    }

}
