<?php
namespace App\Libraries\webservices;

class Efiling_webservices {

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



    public function getJailDetails($jailCode)
    {
        $data = curl_get_contents(ICMIS_SERVICE_URL."/ConsumedData/jailDetails/?jailCode=$jailCode");
        if ($data != false) {
            return json_decode($data,true);
        } else {
            return NULL;
        }
    }

    public function jailAuthorityDetails($jailCode)
    {
        $details=file_get_contents(API_PRISON . "/prison_contacts/".$jailCode);
        if ($details != false) {
            return json_decode($details,true);
        } else {
            return NULL;
        }
    }
    public function prisonerList($jailCode)
    {
        $details=file_get_contents(API_PRISON . "/prison_list_response/".$jailCode);
        if ($details != false) {
            return json_decode($details,true);
        } else {
            return NULL;
        }
    }

    public function prisonerDetail($jailCode,$prisonerId)
    {
        $details=file_get_contents(API_PRISON . "/prisoner_response/".$jailCode."/".$prisonerId."/1/1");
        if ($details != false) {
            return json_decode($details,true);
        } else {
            return NULL;
        }
    }
    /* Prisoner Module end */
    public function OpenAPIwebservice($est_code = '') {
        $url = OPENAPI_URL . $est_code . '&version=' . OPENAPI_VERSION;
        $web_response = curl_get_contents($url . $est_code);
        $json = json_encode($web_response);
        $configData = json_decode($json, true);
        $configData = json_decode($configData);

        $authentication_key = OPENAPI_KEY;
        // $iv = OPENAPI_IV;
        $cipher = 'aes-128-cbc'; // Replace with your cipher
        $iv_length = openssl_cipher_iv_length($cipher);
        $predefined_iv = OPENAPI_IV; // Replace with your IV

        $iv = $this->pad_iv($predefined_iv, $iv_length);
        $response_str = $configData->response_str;
        $status_str = $configData->status;
        //----Response  String-----
        $response_data = base64_decode($response_str);
        $response_decrypt = openssl_decrypt($response_data, 'AES-128-CBC', $authentication_key, OPENSSL_RAW_DATA, $iv);

        //----Status  String-----
        $status_data = base64_decode($status_str);
        $status_decrypt = openssl_decrypt($status_data, 'AES-128-CBC', $authentication_key, OPENSSL_RAW_DATA, $iv);
        return $response_decrypt . '@@@' . $status_decrypt;
    }

    public function OpenAPIwebservice_CINO($est_code = '') {
        $authentication_key = OPENAPI_KEY;
        // $iv = OPENAPI_IV;
        $cipher = 'aes-128-cbc'; // Replace with your cipher
        $iv_length = openssl_cipher_iv_length($cipher);
        $predefined_iv = OPENAPI_IV; // Replace with your IV

        $iv = $this->pad_iv($predefined_iv, $iv_length);
        $url = OPENAPI_URL . $est_code . '&version=' . OPENAPI_VERSION;
        $web_response = curl_get_contents($url . $est_code);

        //$json = json_encode($web_response);
        //$configData = json_decode($json, true);
        $response = json_decode($web_response);
        $response_str = !empty($response->response_str) ? $response->response_str : (isset($response->status) ? $response->status : '');
        $result_data = !empty($response_str) ? base64_decode($response_str) : '';
        $decrypt = openssl_decrypt($result_data, 'AES-128-CBC', $authentication_key, OPENSSL_RAW_DATA, $iv);
        return json_decode($decrypt);
    }

    public function OpenAPIwebservice_MultiCINO($est_code = '') {
        $url = OPENAPI_URL_MULTIPLE . $est_code;
        $web_response = curl_get_contents($url . $est_code);
        $decrypted = cino_decrypt($web_response, 'nic@punemaharashtraindia');
        $json_data = json_decode($decrypted, true);
        return $json_data;
    }

    public function webservice_get_form_cis($url = '') {
        $url = WEB_SERVICE_BASE_URL . $url;
        $xmlfile = curl_get_contents($url);
        $ob = simplexml_load_string($xmlfile);
        $json = json_encode($ob);
        $configData = json_decode($json, true);
        return $configData;
    }

    public function getState($national_code, $efiling_for_type_id, $est_state_code) {
        $court_type = get_court_type($efiling_for_type_id);
        if (!empty($national_code)) {
            if ($court_type == 'dc') {
                $est_state_code_param = '&est_state_code=' . $est_state_code;
            } else {
                $est_state_code_param = '';
            }
            $est_code = 'getState.php?national_code=' . $national_code . '&court_type=' . $court_type . $est_state_code_param;
            $data = $this->webservice($est_code);
            if (!empty($data)) {
                $state_list = $data;
                return $state_list;
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    }

    public function getDistrict($national_code, $efiling_for_type_id, $state_code, $est_state_code) {
        $court_type = get_court_type($efiling_for_type_id);
        if (!empty($national_code)) {
            if ($court_type == 'dc') {
                $est_state_code_param = '&est_state_code=' . $est_state_code;
            } else {
                $est_state_code_param = '';
            }
            $est_code = 'getDistrict.php?national_code=' . $national_code . '&court_type=' . $court_type . '&state_code=' . $state_code . $est_state_code_param;
            $data = $this->webservice($est_code);
            $district_list = array();
            if (!empty($data)) {
                foreach ($data as $org_name) {
                    $temp[] = $org_name;
                }
                $district_list = $temp;
                return $district_list;
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    }

    public function getTaluka($national_code, $dist_code, $efiling_for_type_id, $state_code, $est_state_code) {
        $court_type = get_court_type($efiling_for_type_id);
        if (!empty($national_code) && !empty($dist_code)) {
            if ($court_type == 'dc') {
                $est_state_code_param = '&est_state_code=' . $est_state_code;
            } else {
                $est_state_code_param = '';
            }
            $est_code = 'getTaluka.php?national_code=' . $national_code . '&dist_code=' . $dist_code . '&court_type=' . $court_type . '&state_code=' . $state_code . $est_state_code_param;
            $data = $this->webservice($est_code);
            $taluka_list = array();
            if (!empty($data)) {
                foreach ($data as $d) {
                    $temp[] = $d;
                }
                $taluka_list = $temp;
                return $taluka_list;
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    }

    public function getTown($national_code, $dist_code, $efiling_for_type_id, $state_code, $est_state_code) {
        $court_type = get_court_type($efiling_for_type_id);
        if (!empty($national_code) && !empty($dist_code)) {
            if ($court_type == 'dc') {
                $est_state_code_param = '&est_state_code=' . $est_state_code;
            } else {
                $est_state_code_param = '';
            }
            $est_code = 'getTown.php?national_code=' . $national_code . '&dist_code=' . $dist_code . '&court_type=' . $court_type . '&state_code=' . $state_code . $est_state_code_param;
            $data = $this->webservice($est_code);
            $town_list = array();
            if (!empty($data)) {
                foreach ($data as $d) {
                    $temp[] = $d;
                }
                $town_list = $temp;
                return $town_list;
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    }

    public function getVillage($national_code, $dist_code, $taluka_id, $efiling_for_type_id, $state_code, $est_state_code) {
        $court_type = get_court_type($efiling_for_type_id);
        if (!empty($national_code) && !empty($dist_code) && !empty($taluka_id)) {
            if ($court_type == 'dc') {
                $est_state_code_param = '&est_state_code=' . $est_state_code;
            } else {
                $est_state_code_param = '';
            }
            $est_code = 'getVillage.php?national_code=' . $national_code . '&dist_code=' . $dist_code . '&tal_code=' . $taluka_id . '&court_type=' . $court_type . '&state_code=' . $state_code . $est_state_code_param;
            $data = $this->webservice($est_code);
            $village_list = array();
            if (!empty($data)) {
                foreach ($data as $d) {
                    $temp[] = $d;
                }
                $village_list = $temp;
                return $village_list;
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    }

    public function getWard($national_code, $dist_code, $town_code, $efiling_for_type_id, $state_code, $est_state_code) {
        $court_type = get_court_type($efiling_for_type_id);
        if (!empty($national_code) && !empty($town_code)) {
            if ($court_type == 'dc') {
                $est_state_code_param = '&est_state_code=' . $est_state_code;
            } else {
                $est_state_code_param = '';
            }
            $est_code = 'getWard.php?national_code=' . $national_code . '&dist_code=' . $dist_code . '&town_code=' . $town_code . '&court_type=' . $court_type . '&state_code=' . $state_code . $est_state_code_param;
            $data = $this->webservice($est_code);
            $ward_list = array();
            if (!empty($data)) {
                foreach ($data as $d) {
                    $temp[] = $d;
                }
                $ward_list = $temp;
                return $ward_list;
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    }

    public function getOrgname($national_code, $efiling_for_type_id, $state_code, $est_state_code) {
        $court_type = get_court_type($efiling_for_type_id);
        if (!empty($national_code)) {
            if ($court_type == 'dc') {
                $est_state_code_param = '&est_state_code=' . $est_state_code;
            } else {
                $est_state_code_param = '';
            }
            $est_code = 'getOrgname.php?national_code=' . $national_code . '&court_type=' . $court_type . '&state_code=' . $state_code . $est_state_code_param;
            $data = $this->webservice($est_code);
            $org_list = array();
            if (!empty($data)) {
                foreach ($data as $org_name) {
                    $temp[] = $org_name;
                }
                $org_list = $temp;
                return $org_list;
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    }

    public function getOrgnameAddress($national_code, $org_code, $efiling_for_type_id, $state_code, $est_state_code) {
        $court_type = get_court_type($efiling_for_type_id);
        if (!empty($national_code)) {
            if ($court_type == 'dc') {
                $est_state_code_param = '&est_state_code=' . $est_state_code;
            } else {
                $est_state_code_param = '';
            }
            $est_code = 'getOrgname.php?national_code=' . $national_code . '&court_type=' . $court_type . '&state_code=' . $state_code . $est_state_code_param;
            $data = $this->webservice($est_code);
            $org_list = array();
            if (!empty($data)) {
                foreach ($data as $org_name) {
                    if ($org_code == $org_name->ORGCODE) {
                        $org_list = $org_name;
                    }
                }
                return $org_list;
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    }

    public function getCaseType($national_code, $efiling_for_type_id, $est_state_code) {

        $court_type = get_court_type($efiling_for_type_id);
        if (!empty($national_code)) {
            if ($court_type == 'dc') {
                $est_state_code_param = '&est_state_code=' . $est_state_code;
            } else {
                $est_state_code_param = '';
            }
            $est_code = 'getCaseType.php?national_code=' . $national_code . '&court_type=' . $court_type . $est_state_code_param;
            $data = $this->webservice($est_code);


            /*$input_str = 'national_code=' . $national_code . '&court_type=' . $court_type . $est_state_code_param;
            $encrypt = openssl_encrypt($input_str, 'AES-128-CBC', OPENAPI_KEY, OPENSSL_RAW_DATA, OPENAPI_IV);
            $request_str = base64_encode($encrypt);
            $request_token = hash_hmac('sha256', $input_str, OPENAPI_HASHHMAC_KEY);
            $est_code = 'getCaseType.php/?dept_id=' . OPENAPI_DEPT_NO . '&request_str=' . urlencode($request_str) . '&request_token=' . $request_token;
            $data = $this->OpenAPIwebservice($est_code);*/


            $case_type_list = array();
            if (!empty($data)) {
                foreach ($data as $d) {
                    $temp[] = $d;
                }
                $case_type_list = $temp;
                return $case_type_list;
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    }

    function getCaseTypeSelected($national_code, $civil_or_criminal, $efiling_for_type_id, $est_state_code) {

        $court_type = get_court_type($efiling_for_type_id);
        if ($civil_or_criminal == CASE_TYPE_CIVIL) {
            $sel_civil_or_criminal = 'Civil';
        } elseif ($civil_or_criminal == CASE_TYPE_CRIMINAL) {
            $sel_civil_or_criminal = 'Criminal';
        }
        if (!empty($national_code)) {
            if ($court_type == 'dc') {
                $est_state_code_param = '&est_state_code=' . $est_state_code;
            } else {
                $est_state_code_param = '';
            }
            $est_code = 'getCaseType.php?national_code=' . $national_code . '&court_type=' . $court_type . $est_state_code_param;
            $data = $this->webservice($est_code);
            $case_type_list = array();
            if (!empty($data)) {
                foreach ($data as $d) {
                    if ($sel_civil_or_criminal == $d->TYPEFLAG) {
                        $temp[] = $d;
                    }
                }
                $case_type_list = $temp;
                return $case_type_list;
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    }

    function getAdvocateInfo($national_code, $bar_reg, $efiling_for_type_id, $state_code, $est_state_code) {
        $court_type = get_court_type($efiling_for_type_id);
        if (!empty($national_code)) {

            if ($court_type == 'dc') {
                $est_state_code_param = '&est_state_code=' . $est_state_code;
            } else {
                $est_state_code_param = '';
            }
            $est_code = 'getAdvocate.php?national_code=' . $national_code . '&adv_barcode=' . $bar_reg . '&court_type=' . $court_type . '&state_code=' . $state_code . '&flag=1' . $est_state_code_param;
            $data = $this->webservice($est_code);
            $advocate_info = array();
            if (!empty($data)) {
                foreach ($data as $d) {
                    $temp['advocate_code'] = (string) $d->ADVOCATECODE[0];
                    $temp['advocate_name'] = (string) $d->ADVOCATENAME[0];
                }
                $advocate_info = $temp;
                return $advocate_info;
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    }

    public function getLowerCourt($national_code, $efiling_for_type_id, $district_code, $state_code, $est_state_code) {
        $court_type = get_court_type($efiling_for_type_id);
        if (!empty($national_code)) {
            if ($court_type == 'dc') {
                $est_state_code_param = '&est_state_code=' . $est_state_code;
            } else {
                $est_state_code_param = '';
            }
            $est_code = 'getLowerCourt.php?national_code=' . $national_code . '&court_type=' . $court_type . '&state_code=' . $state_code . '&dist_code=' . $district_code . $est_state_code_param;
            $data = $this->webservice($est_code);
            $lower_court_list = array();
            if (!empty($data)) {
                foreach ($data as $d) {
                    $temp[] = $d;
                }
                $lower_court_list = $temp;
                return $lower_court_list;
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    }

    public function getDocType($national_code, $efiling_for_type_id, $est_state_code) {
        $court_type = get_court_type($efiling_for_type_id);
        if (!empty($national_code)) {
            if ($court_type == 'dc') {
                $est_state_code_param = '&est_state_code=' . $est_state_code;
            } else {
                $est_state_code_param = '';
            }
            $est_code = 'getDocType.php?national_code=' . $national_code . '&court_type=' . $court_type . $est_state_code_param;
            $data = $this->webservice($est_code);
            $doc_type_list = array();
            if (!empty($data)) {
                foreach ($data as $d) {
                    $temp[] = $d;
                }
                $doc_type_list = $temp;
                return $doc_type_list;
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    }

    public function get_act_list($national_code, $efiling_for_type_id, $est_state_code) {
        $court_type = get_court_type($efiling_for_type_id);
        if (!empty($national_code)) {
            if ($court_type == 'dc') {
                $est_state_code_param = '&est_state_code=' . $est_state_code;
            } else {
                $est_state_code_param = '';
            }
            $est_code = 'getAct.php?national_code=' . $national_code . '&court_type=' . $court_type . $est_state_code_param;
            $data = $this->webservice($est_code);
            $act_list = array();
            if (!empty($data)) {
                foreach ($data as $d) {
                    $temp[] = $d;
                }
                $act_list = $temp;
                return $act_list;
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    }

    public function getReligion($national_code, $efiling_for_type_id, $est_state_code) {
        $court_type = get_court_type($efiling_for_type_id);
        if (!empty($national_code)) {
            if ($court_type == 'dc') {
                $est_state_code_param = '&est_state_code=' . $est_state_code;
            } else {
                $est_state_code_param = '';
            }
            $est_code = 'getReligion.php?national_code=' . $national_code . '&court_type=' . $court_type . $est_state_code_param;
            $data = $this->webservice($est_code);
            $religion_list = array();
            if (!empty($data)) {
                foreach ($data as $d2) {
                    $temp[] = $d2;
                }
                $religion_list = $temp;
                return $religion_list;
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    }

    public function getCaste($national_code, $rel_id, $efiling_for_type_id, $est_state_code) {
        $court_type = get_court_type($efiling_for_type_id);
        if (!empty($national_code)) {
            if ($court_type == 'dc') {
                $est_state_code_param = '&est_state_code=' . $est_state_code;
            } else {
                $est_state_code_param = '';
            }
            $est_code = 'getCaste.php?national_code=' . $national_code . '&rel_id=' . $rel_id . '&court_type=' . $court_type . $est_state_code_param;
            $data = $this->webservice($est_code);
            $caste_list = array();
            if (!empty($data)) {
                foreach ($data as $d2) {
                    $temp[] = $d2;
                }
                $caste_list = $temp;
                return $caste_list;
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    }

    public function get_state_name($national_code, $state_id) {
        if (!empty($state_id)) {
            $est_code = 'getState.php?national_code=' . $national_code;
            $data = $this->webservice($est_code);
            $state_name = array();
            if (!empty($data)) {
                $temp = array();
                foreach ($data as $state) {
                    if ($state[0]->STATECODE == $state_id) {
                        $temp[] = $state;
                    }
                }
                $state_name = $temp;
                return $state_name;
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    }

    public function getCaseHistory_cnr_num($cino, $court_type, $est_state_code) {
        if (!empty($cino)) {
            $court_type = get_court_type($court_type);
            if ($court_type == 'dc') {
                $est_state_code_param = '&est_state_code=' . $est_state_code;
            } else {
                $est_state_code_param = '';
            }
            $data = curl_get_contents(WEB_SERVICE_BASE_URL . "listOfCasesWebService.php?cino=" . $cino . '&court_type=' . $court_type . $est_state_code_param);

            if ($data != false) {
                $decrypted = cino_decrypt($data, 'nic@punemaharashtraindia');
                $json_data = json_decode($decrypted, true);

                if ($json_data != null && $json_data != false) {
                    return $json_data;
                } else {
                    return NULL;
                }
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    }

    public function get_trials($national_code, $efiling_for_type_id, $est_state_code) {
        $court_type = get_court_type($efiling_for_type_id);
        if (!empty($national_code)) {
            if ($court_type == 'dc') {
                $est_state_code_param = '?est_state_code=' . $est_state_code;
            } else {
                $est_state_code_param = '';
            }
            $url = 'get_trials.php' . $est_state_code_param;
            $data = $this->webservice($url);
            if (!empty($data)) {
                $trails_list = $data->PRIVATECOMPLAINT;
                $responce = array_combine((array) $trails_list->PRIVATECOMPLAINTCODE, (array) $trails_list->PRIVATECOMPLAINTNAME);
                return $responce;
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    }

    public function get_policechalan($national_code, $efiling_for_type_id, $est_state_code) {
        $court_type = get_court_type($efiling_for_type_id);
        if (!empty($national_code)) {
            if ($court_type == 'dc') {
                $est_state_code_param = '?est_state_code=' . $est_state_code;
            } else {
                $est_state_code_param = '';
            }
            $url = 'get_policechalan.php' . $est_state_code_param;
            $data = $this->webservice($url);
            if (!empty($data)) {
                $challan_list = $data->POLICECHALAN;
                $responce = array_combine((array) $challan_list->POLICECHALANCODE, (array) $challan_list->POLICECHALANNAME);
                return $responce;
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    }

    public function get_investigation($national_code, $efiling_for_type_id, $est_state_code) {
        $court_type = get_court_type($efiling_for_type_id);
        if (!empty($national_code)) {
            if ($court_type == 'dc') {
                $est_state_code_param = '?est_state_code=' . $est_state_code;
            } else {
                $est_state_code_param = '';
            }
            $url = 'get_investigation.php' . $est_state_code_param;
            $data = $this->webservice($url);
            if (!empty($data)) {
                $investigation_agency = $data;
                return $investigation_agency;
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    }

    public function get_fir_type_list($national_code, $efiling_for_type_id, $est_state_code) {
        $court_type = get_court_type($efiling_for_type_id);
        if (!empty($national_code)) {
            if ($court_type == 'dc') {
                $est_state_code_param = '&est_state_code=' . $est_state_code;
            } else {
                $est_state_code_param = '';
            }
            $url = 'get_firtype.php?national_code=' . $national_code . '&court_type=' . $court_type . $est_state_code_param;
            $data = $this->webservice($url);
            if (!empty($data)) {
                $fir_type_list = $data;
                return $fir_type_list;
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    }

    public function get_police_st_by_district($national_code, $efiling_for_type_id, $dist_code, $est_state_code) {
        $court_type = get_court_type($efiling_for_type_id);
        if (!empty($national_code)) {
            if ($court_type == 'dc') {
                $est_state_code_param = '&est_state_code=' . $est_state_code;
            } else {
                $est_state_code_param = '';
            }
            $url = 'getPolicestationByDistrict.php?national_code=' . $national_code . '&court_type=' . $court_type . '&dist_code=' . $dist_code . $est_state_code_param;
            $data = $this->webservice($url);
            $police_station_list = array();
            if (!empty($data)) {
                foreach ($data as $d) {
                    $temp[] = $d;
                }
                $police_station_list = $temp;
                return $police_station_list;
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    }

    public function getIACaseType($national_code, $efiling_for_type_id, $est_state_code) {
        $court_type = get_court_type($efiling_for_type_id);
        if (!empty($national_code)) {
            if ($court_type == 'dc') {
                $est_state_code_param = '&est_state_code=' . $est_state_code;
            } else {
                $est_state_code_param = '';
            }
            $est_code = 'getIACaseType.php?national_code=' . $national_code . '&court_type=' . $court_type . $est_state_code_param;
            $data = $this->webservice($est_code);
            $ia_case_type_list = array();
            if (!empty($data)) {
                foreach ($data as $d) {
                    $temp[] = $d;
                }
                $ia_case_type_list = $temp;
                return $ia_case_type_list;
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    }

    public function getIAClassification($national_code, $efiling_for_type_id, $est_state_code) {
        $court_type = get_court_type($efiling_for_type_id);
        if (!empty($national_code)) {
            if ($court_type == 'dc') {
                $est_state_code_param = '&est_state_code=' . $est_state_code;
            } else {
                $est_state_code_param = '';
            }
            $est_code = 'getIAClassification.php?national_code=' . $national_code . '&court_type=' . $court_type . $est_state_code_param;
            $data = $this->webservice($est_code);
            $classification_list = array();
            if (!empty($data)) {
                foreach ($data as $d) {
                    $temp[] = $d;
                }
                $classification_list = $temp;
                return $classification_list;
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    }

    public function getPrayer($national_code, $efiling_for_type_id, $est_state_code) {

        $court_type = get_court_type($efiling_for_type_id);
        if (!empty($national_code)) {
            if ($court_type == 'dc') {
                $est_state_code_param = '&est_state_code=' . $est_state_code;
            } else {
                $est_state_code_param = '';
            }
            $est_code = 'getPrayer.php?national_code=' . $national_code . '&court_type=' . $court_type . $est_state_code_param;
            $data = $this->webservice($est_code);
            $prayer_list = array();
            if (!empty($data)) {
                foreach ($data as $d) {
                    $temp[] = $d;
                }
                $prayer_list = $temp;
                return $prayer_list;
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    }

    public function get_prayer_desc($national_code, $efiling_for_type_id, $prayer_id, $est_state_code) {

        $court_type = get_court_type($efiling_for_type_id);
        if (!empty($prayer_id)) {
            if ($court_type == 'dc') {
                $est_state_code_param = '&est_state_code=' . $est_state_code;
            } else {
                $est_state_code_param = '';
            }
            $est_code = 'getPrayer.php?national_code=' . $national_code . '&court_type=' . $court_type . $est_state_code_param;
            $data = $this->webservice($est_code);
            $prayer_list = array();
            if (!empty($data)) {
                $temp = array();
                foreach ($data as $prayer_desc) {
                    if ($prayer_desc[0]->PRAYERCODE == $prayer_id) {
                        $temp[] = $prayer_desc;
                    }
                }
                $prayer_list = $temp;
                return $prayer_list;
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    }

    public function getLowerCourtCaseType($national_code, $efiling_for_type_id, $state_code, $est_state_code) {
        $court_type = get_court_type($efiling_for_type_id);
        if (!empty($national_code)) {
            if ($court_type == 'dc') {
                $est_state_code_param = '&est_state_code=' . $est_state_code;
            } else {
                $est_state_code_param = '';
            }
            $est_code = 'getLowerCourtCaseType.php?national_code=' . $national_code . '&court_type=' . $court_type . '&state_code=' . $state_code . $est_state_code_param;
            $data = $this->webservice($est_code);
            $lower_court_list = array();
            if (!empty($data)) {
                foreach ($data as $d) {
                    $temp[] = $d;
                }
                $lower_court_list = $temp;
                return $lower_court_list;
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    }

    public function caseHistoryWebService($state_code, $dist_code, $court_code, $case_type, $case_number, $year, $court_type, $est_state_code) {

        $court_type = get_court_type($court_type);
        if ($court_type == 'dc') {
            $est_state_code_param = '&est_state_code=' . $est_state_code;
        } else {
            $est_state_code_param = '';
        }

        $data = curl_get_contents(CASE_NO_WEB_SERVICE_BASE_URL . "caseNumberSearch.php?state_code=" . $state_code . '&dist_code=' . $dist_code . '&court_code=' . $court_code . '&case_type=' . $case_type . '&case_number=' . $case_number . '&year=' . $year . '&court_type=' . $court_type . $est_state_code_param);
        if ($data != false) {
            return $data;
        } else {
            return NULL;
        }
    }

    public function getCourtFeeType($national_code, $efiling_for_type_id, $state_code, $est_state_code) {
        $court_type = get_court_type($efiling_for_type_id);
        if (!empty($national_code)) {
            if ($court_type == 'dc') {
                $est_state_code_param = '&est_state_code=' . $est_state_code;
            } else {
                $est_state_code_param = '';
            }
            $est_code = 'getCourtFees.php?national_code=' . $national_code . '&court_type=' . $court_type . '&state_code=' . $state_code . $est_state_code_param;
            $data = $this->webservice($est_code);
            $courtfeetype_list = array();
            if (!empty($data)) {
                foreach ($data as $d) {
                    $temp[] = $d;
                }
                $courtfeetype_list = $temp;
                return $courtfeetype_list;
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    }

    public function get_new_case_efiling_nums_status_from_CIS($national_code, $efiling_for_type_id, $efiling_no, $est_state_code, $multi_record_fetch_flag) {
        $court_type = get_court_type($efiling_for_type_id);
        if (!empty($national_code) && !empty($efiling_no)) {
            if ($court_type == 'dc') {
                $est_state_code_param = '&est_state_code=' . $est_state_code;
            } else {
                $est_state_code_param = '';
            }
            if ($national_code == 'HRGR01') {
                $host = '10.249.36.247';
                $db = 'grgtest';
            }

            if ($national_code == 'TNTH01') {
                $host = '10.249.36.241';
                $db = 'thepdjdummy';
            }

            /* $url = 'get_efiling_status_dummy.php?national_court_code=' . $national_code . '&court_type=' . $court_type . '&efil_no=' . $efiling_no . '&flag='.$multi_record_fetch_flag. $est_state_code_param
              . '&host=' . $host . '&db=' . $db; */

            $url = 'get_efiling_status.php?national_court_code=' . $national_code . '&court_type=' . $court_type . '&efil_no=' . $efiling_no . '&flag=' . $multi_record_fetch_flag . $est_state_code_param;
            //$data = $this->webservice_get_form_cis($url);
            $data = $this->webservice_get_form_cis($url);
            if (!empty($data)) {
                $cis_data = $data;
                return $cis_data;
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    }

    public function get_misc_docs_efiling_nums_status_from_CIS($national_code, $efiling_for_type_id, $efiling_no, $est_state_code, $multi_record_fetch_flag) {
        $court_type = get_court_type($efiling_for_type_id);
        if (!empty($national_code) && !empty($efiling_no)) {
            if ($court_type == 'dc') {
                $est_state_code_param = '&est_state_code=' . $est_state_code;
            } else {
                $est_state_code_param = '';
            }
            if ($national_code == 'HPHC01') {
                $host = '10.249.36.247';
                $db = 'grgtest';
            }

            if ($national_code == 'TNTH01') {
                $host = '10.249.36.241';
                $db = 'thepdjdummy';
            }

            $url = 'get_edocument_status_dummy.php?national_court_code=' . $national_code . '&court_type=' . $court_type . '&efil_no=' . $efiling_no . '&flag=' . $multi_record_fetch_flag . $est_state_code_param
                    . '&host=' . $host . '&db=' . $db;

            //$url = 'get_efiling_status.php?national_court_code=' . $national_code . '&court_type=' . $court_type . '&efil_no=' . $efiling_no . '&flag=' . $multi_record_fetch_flag . $est_state_code_param;
            //$data = $this->webservice_get_form_cis($url);
            $data = $this->webservice_get_form_cis($url);
            if (!empty($data)) {
                $cis_data = $data;
                return $cis_data;
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    }

    public function get_deficit_fee_efiling_nums_status_from_CIS($national_code, $efiling_for_type_id, $efiling_no, $est_state_code, $multi_record_fetch_flag) {
        $court_type = get_court_type($efiling_for_type_id);
        if (!empty($national_code) && !empty($efiling_no)) {
            if ($court_type == 'dc') {
                $est_state_code_param = '&est_state_code=' . $est_state_code;
            } else {
                $est_state_code_param = '';
            }
            if ($national_code == 'HPHC01') {
                $host = '10.249.36.247';
                $db = 'grgtest';
            }

            if ($national_code == 'TNTH01') {
                $host = '10.249.36.241';
                $db = 'thepdjdummy';
            }

            $url = 'get_ecourt_fee_status_dummy.php?national_court_code=' . $national_code . '&court_type=' . $court_type . '&efil_no=' . $efiling_no . '&flag=' . $multi_record_fetch_flag . $est_state_code_param
                    . '&host=' . $host . '&db=' . $db;

            //$url = 'get_efiling_status.php?national_court_code=' . $national_code . '&court_type=' . $court_type . '&efil_no=' . $efiling_no . '&flag=' . $multi_record_fetch_flag . $est_state_code_param;
            //$data = $this->webservice_get_form_cis($url);
            $data = $this->webservice_get_form_cis($url);
            if (!empty($data)) {
                $cis_data = $data;
                return $cis_data;
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    }

    public function get_ia_efiling_nums_status_from_CIS($national_code, $efiling_for_type_id, $efil_no, $est_state_code, $multi_record_fetch_flag) {
        $court_type = get_court_type($efiling_for_type_id);
        if (!empty($national_code) && !empty($efil_no)) {
            if ($court_type == 'dc') {
                $est_state_code_param = '&est_state_code=' . $est_state_code;
            } else {
                $est_state_code_param = '';
            }

            if ($national_code == 'HRGR01') {
                $host = '10.249.36.247';
                $db = 'grgtest';
            }

            if ($national_code == 'TNTH01') {
                $host = '10.249.36.241';
                $db = 'thepdjdummy';
            }

            // $url = 'get_efilingIA_status_dummy.php?national_court_code=' . $national_code . '&court_type=' . $court_type . '&efilia_no=' . $efil_no . '&flag='.$multi_record_fetch_flag. $est_state_code_param
            // . '&host=' . $host . '&db=' . $db; 

            $url = 'get_efilingIA_status.php?national_court_code=' . $national_code . '&court_type=' . $court_type . '&efilia_no=' . $efil_no . '&flag=' . $multi_record_fetch_flag . $est_state_code_param;
            //$data = $this->webservice_get_form_cis($url);
            $data = $this->webservice_get_form_cis($url);
            if (!empty($data)) {
                $cis_data = $data;
                return $cis_data;
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    }

    public function getBankMasters($national_code, $efiling_for_type_id, $state_code, $est_state_code) {
        $court_type = get_court_type($efiling_for_type_id);
        if (!empty($national_code)) {
            if ($court_type == 'dc') {
                $est_state_code_param = '&est_state_code=' . $est_state_code;
            } else {
                $est_state_code_param = '';
            }
            $est_code = 'getBankMaster.php?national_code=' . $national_code . '&court_type=' . $court_type . '&state_code=' . $state_code . $est_state_code_param;
            $data = $this->webservice($est_code);

            $bank_list = array();
            if (!empty($data)) {
                foreach ($data as $d) {
                    $temp[] = $d;
                }
                $bank_list = $temp;
                return $bank_list;
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    }

    public function getPurpose($national_code, $efiling_for_type_id, $state_code, $est_state_code) {
        $court_type = get_court_type($efiling_for_type_id);
        if (!empty($national_code)) {
            if ($court_type == 'hc') {
                $est_state_code_param = '&state_code=' . $state_code;
            } else {
                $est_state_code_param = '&state_code=' . $state_code . '&est_state_code=' . $est_state_code;
            }
            $est_code = 'getPurpose.php?national_code=' . $national_code . '&court_type=' . $court_type . $est_state_code_param;
            $data = $this->webservice($est_code);

            $purpose_list = array();
            if (!empty($data)) {
                foreach ($data as $d) {
                    $temp[] = $d;
                }
                $purpose_list = $temp;
                return $purpose_list;
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    }

    public function get_sub_purpose($national_code, $efiling_for_type_id, $state_code, $est_state_code, $purpose_id) {
        $court_type = get_court_type($efiling_for_type_id);
        if (!empty($national_code)) {
            if ($court_type == 'hc') {
                $est_state_code_param = '&state_code=' . $state_code . '&purpose_code=' . $purpose_id;
            } else {
                $est_state_code_param = '&state_code=' . $state_code . '&est_state_code=' . $est_state_code . '&purpose_code=' . $purpose_id;
            }
            $est_code = 'getSubPurpose.php?national_code=' . $national_code . '&court_type=' . $court_type . $est_state_code_param;
            $data = $this->webservice($est_code);

            $sub_purpose_list = array();
            if (!empty($data)) {
                foreach ($data as $d) {
                    $temp[] = $d;
                }
                $sub_purpose_list = $temp;
                return $sub_purpose_list;
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    }

    public function getObjection($national_code, $efiling_for_type_id, $state_code, $est_state_code) {
        $court_type = get_court_type($efiling_for_type_id);
        if (!empty($national_code)) {
            if ($court_type == 'hc') {
                $est_state_code_param = '&state_code=' . $state_code;
            } else {
                $est_state_code_param = '&state_code=' . $state_code . '&est_state_code=' . $est_state_code;
            }
            $est_code = 'getObjection.php?national_code=' . $national_code . '&court_type=' . $court_type . $est_state_code_param;
            $data = $this->webservice($est_code);

            $objection_list = array();
            if (!empty($data)) {
                foreach ($data as $d) {
                    $temp[] = $d;
                }
                $objection_list = $temp;
                return $objection_list;
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    }

    public function getBenchlist($national_code, $efiling_for_type_id, $state_code, $est_state_code) {
        $court_type = get_court_type($efiling_for_type_id);
        if (!empty($national_code)) {
            if ($court_type == 'hc') {
                $est_state_code_param = '&state_code=' . $state_code;
            } else {
                $est_state_code_param = '&state_code=' . $state_code . '&est_state_code=' . $est_state_code;
            }
            $bench_list = 'getBenchType.php?national_code=' . $national_code . '&court_type=' . $court_type . $est_state_code_param;
            $data = $this->webservice_get_form_cis($bench_list);

            $bench_list = array();
            if (!empty($data)) {
                foreach ($data as $d) {
                    $temp[] = $d;
                }
                $bench_list = $temp;
                return $bench_list;
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    }

    public function getOpenAPIState() {
        $input_str = urlencode('');
        $request_token = hash_hmac('sha256', $input_str, OPENAPI_HASHHMAC_KEY);
        $est_code = 'estlocation/state/?dept_id=' . OPENAPI_DEPT_NO . '&request_token=' . $request_token;
        $data = $this->OpenAPIwebservice($est_code);
        $response = explode('@@@', $data);
        if (!empty($response[1])) {
            return $response[1];
        }
        $state_list = array();
        $result = array(json_decode($response[0]));
        if (!empty($result)) {
            foreach ($result as $d) {
                $temp[] = $d;
            }
            $state_list = $temp;
            return $state_list;
        } else {
            return NULL;
        }
    }

    public function getOpenAPIDistrict($state_id) {

        $input_str = "state_code=" . $state_id;

        $encrypt = openssl_encrypt($input_str, 'AES-128-CBC', OPENAPI_KEY, OPENSSL_RAW_DATA, OPENAPI_IV);
        $request_str = base64_encode($encrypt);

        $request_token = hash_hmac('sha256', $input_str, OPENAPI_HASHHMAC_KEY);
        $est_code = 'estlocation/district/?dept_id=' . OPENAPI_DEPT_NO . '&request_str=' . urlencode($request_str) . '&request_token=' . $request_token;

        $data = $this->OpenAPIwebservice($est_code);
        $response = explode('@@@', $data);
        if (!empty($response[1])) {
            return $response[1];
        }
        $district_list = array();
        $result = array(json_decode($response[0]));

        if (!empty($result)) {
            foreach ($result as $d) {
                $temp[] = $d;
            }
            $district_list = $temp;
            return $district_list;
        } else {
            return NULL;
        }
    }

    public function getOpenAPIEstablishment($state_id, $dist_code) {

        $input_str = "state_code=" . $state_id . "|dist_code=" . $dist_code;

        $encrypt = openssl_encrypt($input_str, 'AES-128-CBC', OPENAPI_KEY, OPENSSL_RAW_DATA, OPENAPI_IV);
        $request_str = base64_encode($encrypt);

        $request_token = hash_hmac('sha256', $input_str, OPENAPI_HASHHMAC_KEY);
        $est_code = 'estlocation/courtComplex/?dept_id=' . OPENAPI_DEPT_NO . '&request_str=' . urlencode($request_str) . '&request_token=' . $request_token;

        $data = $this->OpenAPIwebservice($est_code);
        $response = explode('@@@', $data);

        if (!empty($response[1])) {
            return $response[1];
        }
        $complex_list = array();
        $result = array(json_decode($response[0]));

        if (!empty($result)) {
            foreach ($result as $d) {
                $temp[] = $d;
            }
            $complex_list = $temp;
            return $complex_list;
        } else {
            return NULL;
        }
    }

    public function getOpenAPICourtNameList($establishment_code) {

        $input_str = "est_code=" . $establishment_code;

        $encrypt = openssl_encrypt($input_str, 'AES-128-CBC', OPENAPI_KEY, OPENSSL_RAW_DATA, OPENAPI_IV);
        $request_str = base64_encode($encrypt);

        $request_token = hash_hmac('sha256', $input_str, OPENAPI_HASHHMAC_KEY);
        $est_code = 'master/courtList/?dept_id=' . OPENAPI_DEPT_NO . '&request_str=' . urlencode($request_str) . '&request_token=' . $request_token;

        $data = $this->OpenAPIwebservice($est_code);
        $response = explode('@@@', $data);
        if (!empty($response[1])) {
            return $response[1];
        }
        $court_name_list = array();
        $result = array(json_decode($response[0]));

        if (!empty($result)) {
            foreach ($result as $d) {
                $temp[] = $d;
            }
            $court_name_list = $temp;
            return $court_name_list;
        } else {
            return NULL;
        }
    }

    public function getOpenAPICASE_TYPE($estcode) {
        $input_str = "est_code=" . $estcode;
        $encrypt = openssl_encrypt($input_str, 'AES-128-CBC', OPENAPI_KEY, OPENSSL_RAW_DATA, OPENAPI_IV);
        $request_str = base64_encode($encrypt);
        $request_token = hash_hmac('sha256', $input_str, OPENAPI_HASHHMAC_KEY);
        $est_code = 'master/caseType/?dept_id=' . OPENAPI_DEPT_NO . '&request_str=' . urlencode($request_str) . '&request_token=' . $request_token;
        $data = $this->OpenAPIwebservice_CINO($est_code);
        $result = array($data);

        $case_type = array();
        if (!empty($result)) {
            foreach ($result as $d) {
                $temp[] = $d;
            }
            $case_type = $temp;
            return $case_type;
        } else {
            return NULL;
        }
    }

    public function getOpenAPICNRSearch($cino) {
        $input_str = "cino=" . $cino;
        $cipher = 'aes-128-cbc'; // Replace with your cipher
        $iv_length = openssl_cipher_iv_length($cipher);
        $predefined_iv = OPENAPI_IV; // Replace with your IV

        $iv = $this->pad_iv($predefined_iv, $iv_length);
        $encrypt = openssl_encrypt($input_str, 'AES-128-CBC', OPENAPI_KEY, OPENSSL_RAW_DATA, $iv);
        $request_str = base64_encode($encrypt);
        $request_token = hash_hmac('sha256', $input_str, OPENAPI_HASHHMAC_KEY);
        $est_code = 'casesearch/cnrFullCaseDetails?dept_id=' . OPENAPI_DEPT_NO . '&request_str=' . urlencode($request_str) . '&request_token=' . $request_token;
        $data = $this->OpenAPIwebservice_CINO($est_code);
        $result = array($data);
        $cnr_list = array();
        if (!empty($result)) {
            foreach ($result as $d) {
                $temp[] = $d;
            }
            $cnr_list = $temp;
            return $cnr_list;
        } else {
            return NULL;
        }
    }
    function pad_iv($iv, $required_length) {
        return str_pad($iv, $required_length, "\0");
    }
    public function getOpenAPIcaseHistoryWebService($establishment_id, $case_type_id, $case_number, $case_year) {
        $establishment_id = trim($establishment_id);
        $case_type_id = trim($case_type_id);
        $case_number = trim($case_number);
        $case_year = trim($case_year);
        $input_str = "est_code=" . $establishment_id . "|case_type=" . $case_type_id . "|reg_no=" . $case_number . "|reg_year=" . $case_year;
        $cipher = 'aes-128-cbc'; // Replace with your cipher
        $iv_length = openssl_cipher_iv_length($cipher);
        $predefined_iv = OPENAPI_IV; // Replace with your IV

        $iv = $this->pad_iv($predefined_iv, $iv_length);
        $encrypt = openssl_encrypt($input_str, 'AES-128-CBC', OPENAPI_KEY, 0, $iv);
        // $encrypt = openssl_encrypt($input_str, 'AES-128-CBC', OPENAPI_KEY, 0, OPENAPI_IV);
        $request_str = base64_encode($encrypt);
        $request_token = hash_hmac('sha256', $input_str, OPENAPI_HASHHMAC_KEY);
        $est_code = 'casesearch/caseNumber?dept_id=' . OPENAPI_DEPT_NO . '&request_str=' . urlencode($request_str) . '&request_token=' . $request_token;
        $data = $this->OpenAPIwebservice_CINO($est_code);
        $result = array($data);
        $case_list = array();
        if (!empty($result)) {
            foreach ($result as $d) {
                $temp[] = $d;
            }
            $case_list = $temp;
            return $case_list;
        } else {
            return NULL;
        }
    }

    public function getOpenAPIFilingcaseHistoryWebService($establishment_id, $case_type_id, $filing_number, $case_year) {
        $establishment_id = trim($establishment_id);
        $case_type_id = trim($case_type_id);
        $filing_number = trim($filing_number);
        $case_year = trim($case_year);
        $input_str = "est_code=" . $establishment_id . "|case_type=" . $case_type_id . "|fil_no=" . $filing_number . "|fil_year=" . $case_year;
        $encrypt = openssl_encrypt($input_str, 'AES-128-CBC', OPENAPI_KEY, OPENSSL_RAW_DATA, OPENAPI_IV);
        $request_str = base64_encode($encrypt);
        $request_token = hash_hmac('sha256', $input_str, OPENAPI_HASHHMAC_KEY);
        $est_code = 'casesearch/filingNumber/?dept_id=' . OPENAPI_DEPT_NO . '&request_str=' . urlencode($request_str) . '&request_token=' . $request_token;
        $data = $this->OpenAPIwebservice_CINO($est_code);
        $result = array($data);

        $fil_list = array();
        if (!empty($result)) {
            foreach ($result as $d) {
                $temp[] = $d;
            }
            $fil_list = $temp;
            return $fil_list;
        } else {
            return NULL;
        }
    }

    public function get_OPENAPI_multiple_cnr_status_form_CIS($cino, $court_type) {
        $cino = trim($cino);
        $court_type = get_court_type($court_type);

        $input_str = "cino=" . $cino . "&court_type=" . $court_type;
        $est_code = 'casesearch/currentStatus/?' . $input_str;
        $data = $this->OpenAPIwebservice_MultiCINO($est_code);
        $result = array($data);

        $fil_list = array();
        if (!empty($result)) {
            foreach ($result as $d) {
                $temp[] = $d;
            }
            $fil_list = $temp;
            return $fil_list;
        } else {
            return NULL;
        }
    }

    public function get_business_details($cino, $business_date) {

        $input_str = "cino=" . $cino . "|business_date=" . $business_date;
        $encrypt = openssl_encrypt($input_str, 'AES-128-CBC', OPENAPI_KEY, OPENSSL_RAW_DATA, OPENAPI_IV);
        $request_str = base64_encode($encrypt);

        $request_token = hash_hmac('sha256', $input_str, OPENAPI_HASHHMAC_KEY);
        $est_code = 'casesearch/business/?dept_id=' . OPENAPI_DEPT_NO . '&request_str=' . urlencode($request_str) . '&request_token=' . $request_token;

        $data = $this->OpenAPIwebservice($est_code);
        $response = explode('@@@', $data);

        if (!empty($response[1])) {
            return $response[1];
        }
        $data_result = array();
        $result = array(json_decode($response[0]));

        if (!empty($result)) {
            foreach ($result as $d) {
                $temp[] = $d;
            }
            $data_result = $temp;
            return $data_result;
        } else {
            return NULL;
        }
    }

    public function show_order_detail_pdf($cino, $order_no, $order_date) {


        $input_str = "cino=" . $cino . "|order_no=" . $order_no . "|order_date=" . $order_date;
        $encrypt = openssl_encrypt($input_str, 'AES-128-CBC', OPENAPI_KEY, OPENSSL_RAW_DATA, OPENAPI_IV);
        $request_str = base64_encode($encrypt);
        $request_token = hash_hmac('sha256', $input_str, OPENAPI_HASHHMAC_KEY);

        $est_code = 'casesearch/order/?dept_id=' . OPENAPI_DEPT_NO . '&request_str=' . urlencode($request_str) . '&request_token=' . $request_token;
        $url = OPENAPI_URL . $est_code . '&version=' . OPENAPI_VERSION;

        $web_response = curl_get_contents($url . $est_code);
        if (!empty($web_response)) {
            return $web_response;
        } else {
            return NULL;
        }
    }

    public function get_cause_list_by_adv_bar($establishment_code, $adv_bar_code, $cause_list_date) {

        $input_str = "est_code=" . $establishment_code . "|advocate_bar_regn_no=" . $adv_bar_code . "|causelist_date=" . $cause_list_date;
        $encrypt = openssl_encrypt($input_str, 'AES-128-CBC', OPENAPI_KEY, OPENSSL_RAW_DATA, OPENAPI_IV);
        $request_str = base64_encode($encrypt);

        $request_token = hash_hmac('sha256', $input_str, OPENAPI_HASHHMAC_KEY);
        $est_code = 'causelist/advocate/?dept_id=' . OPENAPI_DEPT_NO . '&request_str=' . urlencode($request_str) . '&request_token=' . $request_token;

        $data = $this->OpenAPIwebservice($est_code);
        $response = explode('@@@', $data);

        if (!empty($response[1])) {
            return $response[1];
        }
        $data_result = array();
        $result = array(json_decode($response[0]));

        if (!empty($result)) {
            foreach ($result as $d) {
                $temp[] = $d;
            }
            $data_result = $temp;
            return $data_result;
        } else {
            return NULL;
        }
    }

    public function get_cause_list_by_court_no($establishment_code, $court_no, $cause_list_date, $ci_cri) {

        $input_str = "est_code=" . $establishment_code . "|court_no=" . $court_no . "|causelist_date=" . $cause_list_date . "|ci_cri=" . $ci_cri;
        $encrypt = openssl_encrypt($input_str, 'AES-128-CBC', OPENAPI_KEY, OPENSSL_RAW_DATA, OPENAPI_IV);
        $request_str = base64_encode($encrypt);

        $request_token = hash_hmac('sha256', $input_str, OPENAPI_HASHHMAC_KEY);
        $est_code = 'causelist/court/?dept_id=' . OPENAPI_DEPT_NO . '&request_str=' . urlencode($request_str) . '&request_token=' . $request_token;

        $data = $this->OpenAPIwebservice($est_code);
        $response = explode('@@@', $data);

        if (!empty($response[1])) {
            return $response[1];
        }
        $data_result = array();
        $result = array(json_decode($response[0]));

        if (!empty($result)) {
            foreach ($result as $d) {
                $temp[] = $d;
            }
            $data_result = $temp;
            return $data_result;
        } else {
            return NULL;
        }
    }

    public function getRelation($national_code, $efiling_for_type_id, $est_state_code) {
        $court_type = get_court_type($efiling_for_type_id);
        if (!empty($national_code)) {
            if ($court_type == 'dc') {
                $est_state_code_param = '&est_state_code=' . $est_state_code;
            } else {
                $est_state_code_param = '';
            }
            $est_code = 'getRelationship.php?national_code=' . $national_code . '&court_type=' . $court_type . $est_state_code_param;
            $data = $this->webservice($est_code);
            if (!empty($data)) {
                $relation_list = $data;
                return $relation_list;
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    }

    public function updateFilTrapAORtoFDR($efiling_no)
    {
        $efiling_no = urlencode($efiling_no);
        // $data = curl_get_contents(ICMIS_SERVICE_URL."/ConsumedData/updateFilTrapAORtoFDR/?efiling_no=$efiling_no");
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => ICMIS_SERVICE_URL."/ConsumedData/updateFilTrapAORtoFDR/?efiling_no=$efiling_no",
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
        /*if ($data != false) {
            return json_decode($data,true);
        } else {
            return NULL;
        }*/
    }


    public function get_new_case_efiling_scrutiny_cron_SCIS($efiling_nums_str) {
       //echo $data = curl_get_contents(ICMIS_SERVICE_URL."/ConsumedData/BulkGetScrutinyStatus/?input=$efiling_nums_str");
        $postdata = http_build_query(
            array(
                'pending_scrutiny' => $efiling_nums_str
            )
        );

        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-Type: application/x-www-form-urlencoded',
                'content' => $postdata
            )
        );

        $context  = stream_context_create($opts);

       $data = file_get_contents(ICMIS_SERVICE_URL."/ConsumedData/BulkGetScrutinyStatus", false, $context);

        if ($data != false) {
            return json_decode($data);
        } else {
            return NULL;
        }
    }

    public function get_new_case_efiling_nums_status_from_SCIS($efiling_nums_str) {
        $data = curl_get_contents(ICMIS_SERVICE_URL."/ConsumedData/?input=$efiling_nums_str");
        //$data = file_get_contents("/home/praveen/Desktop/sci-json/search_case_json.txt");

        if ($data != false) {
            return json_decode($data);
        } else {
            return NULL;
        }
    }
    
    public function get_misc_doc_ia_efiling_nums_status_from_SCIS($efiling_nums_str) { 
        $data = curl_get_contents(ICMIS_SERVICE_URL."/ConsumedData/docDetailWithEFilingNo/?input=$efiling_nums_str");
        //$data = file_get_contents("/home/praveen/Desktop/sci-json/search_case_json.txt");
        if ($data != false) {
            return json_decode($data);
        } else {
            return NULL;
        }
    }

    public function get_case_diary_details_from_SCIS($diary_no, $diary_year) {
        $data = file_get_contents(ICMIS_SERVICE_URL."/ConsumedData/caseDetails/?searchBy=D&diaryNo=$diary_no&diaryYear=$diary_year");

        //$data = file_get_contents("/home/praveen/Desktop/sci-json/diary_reg_search_json_data.txt");
        if ($data != false) {
            return json_decode($data);
        } else {
            return NULL;
        }
    }


    public function getCISData($diaryNo)
    {
        $data = file_get_contents(ICMIS_SERVICE_URL."/ConsumedData/getCaseDetails/?diaryNo=$diaryNo");
        if ($data != false) {
            return json_decode($data,true);
        } else {
            return NULL;
        }
    }

    public function get_advocate_list($diary_no_with_year){
        $data = curl_get_contents(ICMIS_SERVICE_URL."/ConsumedData/getAdvocateDetails/?diaryNo=$diary_no_with_year");
        if ($data != false) {
            return json_decode($data,true);
        } else {
            return NULL;
        }
    }

    public function get_case_details_from_SCIS($case_type_id, $case_no, $case_year) {
        $data = file_get_contents(ICMIS_SERVICE_URL."/ConsumedData/caseDetails/?searchBy=C&caseTypeId=$case_type_id&caseNo=$case_no&caseYear=$case_year");

        //$data = file_get_contents("/home/praveen/Desktop/sci-json/diary_reg_search_json_data.txt"
        if ($data != false) {
            return json_decode($data);
        } else {
            return NULL;
        }
    }

    public function get_last_listed_details($diary_no,$diary_year){
        $data = curl_get_contents(ICMIS_SERVICE_URL."/ConsumedData/getLastListedDetail/?diary_no=$diary_no&diary_year=$diary_year");
        //$data = file_get_contents("/home/praveen/Desktop/sci-json/diary_reg_search_json_data.txt"
        if ($data != false) {
            return json_decode($data);
        } else {
            return NULL;
        }
    }

    public function get_police_station_list($state_id,$district_id)
    {
        $data = file_get_contents(ICMIS_SERVICE_URL."/ConsumedData/getPoliceStation/?state_id=$state_id&district_id=$district_id");

        if ($data != false) {
            return json_decode($data,true);
        } else {
            return NULL;
        }
    }

    public function get_holidays()
    {
        $data = curl_get_contents(ICMIS_SERVICE_URL."/ConsumedData/get_holidays");
        if ($data != false) {
            return json_decode($data);
        } else {
            return NULL;
        }
    }

    public function getLowerCtDetails($diary_no)
    {
        $data = curl_get_contents(ICMIS_SERVICE_URL."/ConsumedData/getLowerCtDetails/?diary_no=$diary_no");
        if ($data != false) {
            return json_decode($data,true);
        } else {
            return NULL;
        }
    }


    public function get_parties($diary_no)
    {
        $data = curl_get_contents(ICMIS_SERVICE_URL."/ConsumedData/get_parties/?diary_no=$diary_no");
        if ($data != false) {
            return json_decode($data,true);
        } else {
            return NULL;
        }
    }
    //https://10.25.78.69:44432/api/icmis/statistics?keys[]=cases.disposed.584.2019-08-01
//    public function get_disposed_details_from_SCIS($type, $from_date, $to_date) {
//        $url1 = COURT_ASSIST_URI_ALT."/t?keys[]=cases.";
//        $url2 = $type . '.' . $from_date . '_to_' . $to_date . '.584';
//        $url = $url1 . $url2; 
//        
//        $data = curl_get_contents($url);
//         
//        if ($data != false) {
//            return json_decode($data);
//        } else {
//            return NULL;
//        }
//    }
    public function get_disposed_details_from_SCIS($type, $from_date)
    {
        $url1 = API_ICMIS_STATISTICS_URI."?keys[]=cases.";
        $url2 = $type . '.584.' . $from_date;
        $url = $url1 . $url2;

        $data = curl_get_contents($url);

        if ($data != false) {
            return json_decode($data);
        } else {
            return NULL;
        }
    }
    public function getCitationDetailFromSuplis($type, $param){
        $url=URL_FOR_SUPLIS."request/$type/$param";
        $data = curl_get_contents($url);
        if ($data != false) {
            return json_decode($data);
        } else {
            return NULL;
        }
    }


    /*Code Change started on 21 September 2020*/

    public function getBookCatalogueDetails($type,$param)
    {
        $url = URL_FOR_LIBRARY_DATA."/"."request/$type/$param";
        $data = curl_get_contents($url);

        if($data !=false)
        {
            return json_decode($data,true);
        }else
        {
            return NULL;
        }
    }
    /*End of code change*/



    public function getBarTable($mobile="",$email=""){
        $data = curl_get_contents(ICMIS_SERVICE_URL."/ConsumedData/allAdvocateDetails?mobile=$mobile&email=$email");
        if ($data != false) {
            return json_decode($data);
        } else {
            return NULL;
        }
    }
    public function getAdvPartyMapping($timestamp=""){
        /*echo ICMIS_SERVICE_URL."/ConsumedData/advPartyMappingDetails?timestamp=".$timestamp;
        exit;*/
        $data = curl_get_contents(ICMIS_SERVICE_URL."/ConsumedData/advPartyMappingDetails?timestamp=".urlencode($timestamp));
        //var_dump($data);
        //exit;
        if ($data != false) {
            return json_decode($data);
        } else {
            return NULL;
        }
    }

    /*---Jail module dashboard----*/
    public function totalPetitionsDetails($id)
    {
        $data = curl_get_contents(ICMIS_SERVICE_URL."/ConsumedData/totalPetitionDetails/?id=$id");

        if ($data != false) {
            return json_decode($data,true);
        } else {
            return NULL;
        }
    }
    public function pendingPetitionsDetails($id)
    {
        $data = curl_get_contents(ICMIS_SERVICE_URL."/ConsumedData/pendingPetitionDetails/?id=$id");

        if ($data != false) {
            return json_decode($data,true);
        } else {
            return NULL;
        }
    }
    public function disposedPetitionsDetails($id)
    {
        $data = curl_get_contents(ICMIS_SERVICE_URL."/ConsumedData/disposedPetitionDetails/?id=$id");
        if ($data != false) {
            return json_decode($data,true);
        } else {
            return NULL;
        }
    }
    public function getAllPetitionsDetails($id,$val,$month,$year)
    {
        $data = curl_get_contents(ICMIS_SERVICE_URL."/ConsumedData/getAllPetitionsDetails/?id=$id&status=$val&month=$month&year=$year");

        if ($data != false) {
            return json_decode($data,true);
        } else {
            return NULL;
        }
    }

    public function getCertificateCount($jailcode,$status,$type)
    {
        $data = curl_get_contents(ICMIS_SERVICE_URL."/ConsumedData/getCertificateCount/?jailcode=$jailcode&status=$status&type=$type");

        if ($data != false) {
            return json_decode($data,true);
        } else {
            return NULL;
        }
    }

    public function getCertificateDetails($jailcode,$status,$type)
    {
        $data = curl_get_contents(ICMIS_SERVICE_URL."/ConsumedData/getCertificateDetails/?jailcode=$jailcode&status=$status&type=$type");

        if ($data != false) {
            return json_decode($data,true);
        } else {
            return NULL;
        }
    }

    public function getvcDetails($jailcode,$fromdate,$todate)
    {
        $data = curl_get_contents(ICMIS_SERVICE_URL."/ConsumedData/getvcDetails/?jailcode=$jailcode&fromdate=$fromdate&todate=$todate");

        if ($data != false) {
            return json_decode($data,true);
        } else {
            return NULL;
        }
    }

    public function registerDoc($encrypted_string)
    {
        /*
         $data = curl_get_contents(ICMIS_SERVICE_URL."/index.php/PutInICMIS/saveIADoc/?table=$table_name&details=$data");

        if ($data != false) {
            return json_decode($data,true);
        } else {
            return NULL;
        }*/

        $postdata = http_build_query(
            array(
                'doc_details' => $encrypted_string
            )
        );

        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-Type: application/x-www-form-urlencoded',
                'content' => $postdata
            )
        );

        $context  = stream_context_create($opts);

        return $result = file_get_contents(ICMIS_SERVICE_URL.'/PutInICMIS/saveIADoc', false, $context);
    }
    public function generateCaseDiary($encrypted_string){

        $postdata = http_build_query(
            array(
                'diary_details' => $encrypted_string
            )
        );
        // $opts = array('http' =>
        //     array(
        //         'method'  => 'POST',
        //         'header'=> "Content-type: application/x-www-form-urlencoded\r\n"
        //         . "Content-Length: " . strlen($postdata) . "\r\n",
        //         'content' => $postdata
        //     )
        // );
        // $context  = stream_context_create($opts);
        $curl = curl_init();
        $url = ICMIS_SERVICE_URL;
        curl_setopt_array($curl, array(
          CURLOPT_URL => $url.'/filing/do_generate_case_diary',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => $postdata,
          CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded',
                'Content-Length: ' . strlen($postdata)
            )
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        return $decodedResponse = json_decode(json_encode($response), true);
        // return $result = file_get_contents($response);
        /*print_r($result);
        exit();*/
    }

    function file_post_contents($url, $data, $username = null, $password = null)
    {
        $postdata = http_build_query($data);

        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => $postdata
            )
        );

        if($username && $password)
        {
            $opts['http']['header'] = ("Authorization: Basic " . base64_encode("$username:$password"));
        }

        $context = stream_context_create($opts);

        echo file_get_contents($url, false, $context);
    }
    /*--end--*/

    public function setReturnedByAdvocate($encrypted_string){
        $postdata = http_build_query(
            array(
                'diary_details' => $encrypted_string
            )
        );

        // $opts = array('http' =>
        //     array(
        //         'method'  => 'POST',
        //         'header'  => 'Content-Type: application/x-www-form-urlencoded',
        //         'content' => $postdata
        //     )
        // );

        // $context  = stream_context_create($opts);
        // $url = ICMIS_SERVICE_URL;

        // return $result = file_get_contents($url.'/filing/changeScruitinyStage', false, $context);

        $curl = curl_init();
        $url = ICMIS_SERVICE_URL;
        curl_setopt_array($curl, array(
          CURLOPT_URL => $url.'/filing/changeScruitinyStage',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => $postdata,
          CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded',
                'Content-Length: ' . strlen($postdata)
            )
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        return $decodedResponse = json_decode(json_encode($response), true);
    }
    public function get_deficit_court_feeData($advocateId)
    {
        $deficitDetails=file_get_contents(ICMIS_SERVICE_URL."/ConsumedData/getDeficitDefectCases/?advocateId=$advocateId");

        /* print_r($deficitDetails);
         exit();*/
        if ($deficitDetails != false) {
            return json_decode($deficitDetails,true);
        } else {
            return NULL;
        }

    }//END of function get_deficit_court_feeData()..

    public function getEmpDetailsByempId($empId){
        $output= false;
        if(isset($empId) && !empty($empId)){
            $res =file_get_contents(ICMIS_SERVICE_URL.'/ConsumedData/sci_user_details?emp_id='.$empId);
            if(isset($res) && !empty($res)){
                $output =  json_decode($res,true);
            }
        }
        return $output;
    }
    public function updateFeeByRegistrationId($params){
        $output= false;
        if(isset($params) && !empty($params)){
            $diaryNo = !empty($params['diaryNo']) ? (int)$params['diaryNo'] : NULL;
            $totalFee = !empty($params['totalFee']) ? (int)$params['totalFee'] : NULL;

            $url = ICMIS_SERVICE_URL;
            $res =file_get_contents($url.'/ConsumedData/updateFeeByRegistrationIdInMain?diaryNo='.$diaryNo.'&totalFee='.$totalFee);
            if(isset($res) && !empty($res)){
                $output =  json_decode($res,true);
            }
        }
        return $output;
    }

    public function saveRefiledIAData($encriptedData) {

        $url = ICMIS_SERVICE_URL."/PutInICMIS/saveRefiledIA";
        $post_data = array('details' => $encriptedData);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
        $server_output = curl_exec($ch);
        return $decodedResponse = json_decode($server_output, TRUE);
    }
    
    public function getScrutinyUserByDiaryNo($diaryNo){
        $postdata = http_build_query(
            array(
                'diaryNo' => $diaryNo)
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
        $result = @file_get_contents($url.'/PutInICMIS/getScruitinyOfficialDetails?diaryNo='.$diaryNo);
        return json_decode($result,true);
    }
//
//    public function getDaCodeAndSectionIdByCaseNo($searchBy,$caseTypeId,$caseNo,$caseYear){
//        $url = ICMIS_SERVICE_URL;
//        $res = curl_get_contents($url."/ConsumedData/caseDetails/?searchBy=$searchBy&caseTypeId=$caseTypeId&caseNo=$caseNo&caseYear=$caseYear");
//        return json_decode($res,true);
//
//    }
//    public function getDaCodeAndSectionIdByDiaryNo($searchBy,$diaryNo,$diaryYear){
//        $url = ICMIS_SERVICE_URL;
//        $res = curl_get_contents($url."/ConsumedData/caseDetails/?searchBy=$searchBy&diaryNo=$diaryNo&diaryYear=$diaryYear");
//        return json_decode($res,true);
//
//    }


    public function getAdvPartyMappingBydiaryNo($diary_no=""){
        /*echo ICMIS_SERVICE_URL."/ConsumedData/advPartyMappingDetailsBydiaryNo?diary_no=".$diary_no;
        exit;*/
        
        $data = file_get_contents(ICMIS_SERVICE_URL."/ConsumedData/advPartyMappingDetailsBydiaryNo?diary_no=".urlencode($diary_no));
    //     var_dump($data);
    //    exit;
        if ($data != false) {
            return json_decode($data);
        } else {
            return NULL;
        }
    }

    public function getCaseListedDetail($diary_no='',$diary_year='')
    {
        $data = curl_get_contents(ICMIS_SERVICE_URL."/ConsumedData/getLastListedDetail?diary_no=".urlencode($diary_no)."&diary_year=".urlencode($diary_year));
        if ($data != false) {
            return json_decode($data);
        } else {
            return NULL;
        }
    }

    public function getCaseDefectDetails($diary_no='',$diary_year='')
    {
        $data = file_get_contents(ICMIS_SERVICE_URL."/ConsumedData/getCaseDefectsList?diary_no=".urlencode($diary_no)."&diary_year=".urlencode($diary_year));

        if ($data != false) {
            return json_decode($data);
        } else {
            return NULL;
        }
    }

    public function get_display_status_with_date_differnces($tentative_cl_dt)
    {
        $tentative_cl_date_greater_than_today_flag="F";
        $curDate=date('d-m-Y');
        $tentativeCLDate = date('d-m-Y', strtotime($tentative_cl_dt));
        $datediff=strtotime($tentativeCLDate) - strtotime($curDate);
        $noofdays= round($datediff / (60 * 60 * 24));
        if(strtotime($tentativeCLDate) > strtotime($curDate) )
        {
            if($noofdays<=60 && $noofdays>0){
                $tentative_cl_date_greater_than_today_flag='T';
            }
        }
        else
        {
            $tentative_cl_date_greater_than_today_flag='F';
        }
        return $tentative_cl_date_greater_than_today_flag;
    }

    public function getDiaryUserCodeFromICMIS()
    {
        $data = file_get_contents(ICMIS_SERVICE_URL."/ConsumedData/getDiaryUserCodeFromICMIS");
        if ($data != false) {
            return json_decode($data,true);
        } else {
            return NULL;
        }
    }
    public function getCaseDetailsWithCaseTypeFromICMIS($diary_no='',$diary_year='')
    {
        $data = curl_get_contents(ICMIS_SERVICE_URL."/ConsumedData/getCaseDetailsWithCaseType?diary_no=".urlencode($diary_no)."&diary_year=".urlencode($diary_year));
        if ($data != false) {
            return json_decode($data,true);
        } else {
            return NULL;
        }
    }

     public function updateOldEfilingData($params){

         $postdata = http_build_query(
             array(
                 'details' => $params)
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
         $result = file_get_contents($url.'/PutInICMIS/saveRefiledOldefilingCaseData', false, $context);

         return json_decode($result,true);
    }

    public function checkInTheOldEfilingCasesList($diary_no='',$diary_year='')
    {
        $data = file_get_contents(ICMIS_SERVICE_URL."/ConsumedData/checkCaseIsEligibleForRefiling?diary_no=".urlencode($diary_no)."&diary_year=".urlencode($diary_year));

        if ($data != false) {
            return json_decode($data);
        } else {
            return NULL;
        }
    }

    public function checkRefiledCaseDataAlreadySyncedOrNot($diary_no='',$diary_year='',$refiled_on='',$refiled_by='')
    {
        //echo ICMIS_SERVICE_URL."/ConsumedData/checkCaseIsEligibleForInsertViaCRON?diary_no=".urlencode($diary_no)."&diary_year=".urlencode($diary_year)."&refiled_on=".urlencode($refiled_on)."&refiled_by=".urlencode($refiled_by).'<BR>';
        $url=ICMIS_SERVICE_URL."/ConsumedData/checkCaseIsEligibleForInsertViaCRON?diary_no=".urlencode($diary_no)."&diary_year=".urlencode($diary_year)."&refiled_on=".urlencode($refiled_on)."&refiled_by=".urlencode($refiled_by);
        $data = file_get_contents($url);
        //var_dump($data);;exit();
        if ($data != false) {
            return json_decode($data);
        } else {
            return NULL;
        }
    }

    /*Start retrieval data for json comparison 19-sep-2024 Manoj*/
    public function getGeneratedCaseDiary($efile_no,$registrtion_id){
        $postdata = http_build_query(
            array(
                'efile_no' => $efile_no
            )
        );
        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-Type: application/x-www-form-urlencoded',
                'content' => $postdata
            )
        );
        $context  = stream_context_create($opts);
        $url      = ICMIS_SERVICE_URL;

        return $result = file_get_contents($url.'/ConsumedData/getDiaryDetails', false, $context);
    }

    public function getCaseDiaryNo($efile_no){

        // $postdata = http_build_query(
        //     array(
        //         'efile_no' => $efile_no
        //     )
        // );
        // $opts = array('http' =>
        //     array(
        //         'method'  => 'POST',
        //         'header'  => 'Content-Type: application/x-www-form-urlencoded',
        //         'content' => $postdata
        //     )
        // );
        // $context  = stream_context_create($opts);
        // $url      = ICMIS_SERVICE_URL;

        // return $result = file_get_contents($url.'/ConsumedData/getDiaryNo', false, $context);

        $postdata = http_build_query(
            array(
                'efile_no' => $efile_no
            )
        );
        $curl = curl_init();
        $url = ICMIS_SERVICE_URL;
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url.'/ConsumedData/getDiaryNo',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $postdata,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded',
                'Content-Length: ' . strlen($postdata)
            )
        ));        
        $response = curl_exec($curl);
        curl_close($curl);
        return $decodedResponse = json_decode($response);
    }

    public function getDiaryDetailForTemplate($diary_no){
        $postdata = http_build_query(
            array(
                'diary_no' => $diary_no
            )
        );
        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-Type: application/x-www-form-urlencoded',
                'content' => $postdata
            )
        );
        $context  = stream_context_create($opts);
        $url      = ICMIS_SERVICE_URL;
        return $result = file_get_contents($url.'/ConsumedData/getDiaryDetailForTemplate', false, $context);
    }
    /*end retrieval data for json comparison 19-sep-2024 Manoj*/

    public function getObjectionsByDiaryNo($diaryno) {        
        $postdata = http_build_query(
            array(
                'diary_no' => $diaryno
            )
        );
        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-Type: application/x-www-form-urlencoded',
                'content' => $postdata
            )
        );
        $context = stream_context_create($opts);
        $url     = ICMIS_SERVICE_URL;
        // return $result = file_get_contents($url.'/ConsumedData/getObjectionsByDiaryNo', false, $context);
        return $result = curl_get_contents($url.'/ConsumedData/getObjectionsByDiaryNo', false, $context);
    }
/*start Refiling IA and MiscDocs.*/
    public function BulkGetIaMiscScrutinyDefects($efiling_nums_str) {

        $postdata = http_build_query(
            [
                'pending_scrutiny' => $efiling_nums_str
            ]
        );
        $opts = ['http' =>
            [
                'method'  => 'POST',
                'header'  => 'Content-Type: application/x-www-form-urlencoded',
                'content' => $postdata
            ]
        ];
        $context  = stream_context_create($opts);
        $url     = ICMIS_SERVICE_URL;
        $data = file_get_contents($url.'/ConsumedData/BulkGetIaMiscScrutinyDefects', false, $context);
        if ($data != false) {
            return json_decode($data);
        } else {
            return NULL;
        }
    }
    public function updateDefectRefiledIAData($encriptedData){
        $postdata = http_build_query(
            array(
                'details' => $encriptedData)
        );
        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-Type: application/x-www-form-urlencoded',
                'content' => $postdata
            )
        );
        $context  = stream_context_create($opts);
        $url     = ICMIS_SERVICE_URL; 
        $result = file_get_contents($url.'/PutInICMIS/updateDefectRefiledIAData', false, $context);
        var_dump($result);
        return json_decode($result,true);
    }
    /*end Refiling IA and MiscDocs.*/
    
    /*start function Is_disposed */
    public function get_new_case_efilingIsDisposed($efiling_nums_str) {
        // echo $data = curl_get_contents(env('ICMIS_SERVICE_URL')."/ConsumedData/get_new_case_efilingIsDisposed/?input=$efiling_nums_str");
        $postdata = http_build_query(
            array(
                'pending_scrutiny' => $efiling_nums_str
            )
        );
        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-Type: application/x-www-form-urlencoded',
                'content' => $postdata
            )
        );
        $context  = stream_context_create($opts);
        $data = file_get_contents(ICMIS_SERVICE_URL."/ConsumedData/get_new_case_efilingIsDisposed", false, $context);
        if ($data != false) {
            return json_decode($data);
        } else {
            return NULL;
        }
    }
    /*end function Is_disposed */
    
    public function get_ia_misc_efiling_scrutiny_cron_SCIS($efiling_nums_str) {

        $postdata = http_build_query(
            [
                'pending_scrutiny' => $efiling_nums_str
            ]
        );
        $opts = ['http' =>
            [
                'method'  => 'POST',
                'header'  => 'Content-Type: application/x-www-form-urlencoded',
                'content' => $postdata
            ]
        ];
        $context  = stream_context_create($opts);
        $data = file_get_contents(ICMIS_SERVICE_URL."/ConsumedData/BulkGetIaMiscScrutinyStatus", false, $context);
         if ($data != false) {
             return json_decode($data);
         } else {
             return NULL;
         }
    }

    public function getListedCases($aor_code) {
        $url = ICMIS_SERVICE_URL."/ConsumedData/getListedCases?aor_code=".urlencode($aor_code);
        $data = file_get_contents($url);
        if ($data != false) {
            return json_decode($data);
        } else {
            return NULL;
        }
    }

    public function getAddedAdvocatesInDiary($aor_code, $diary_no, $next_dt) {
        $url = ICMIS_SERVICE_URL."/ConsumedData/getAddedAdvocatesInDiary?aor_code=".urlencode($aor_code)."&diary_no=".urlencode($diary_no)."&next_dt=".urlencode($next_dt);
        $data = file_get_contents($url);
        if ($data != false) {
            return json_decode($data);
        } else {
            return NULL;
        }
    }

    public function getSubmittedAdvocatesInDiary($aor_code, $diary_no, $next_dt) {
        $url = ICMIS_SERVICE_URL."/ConsumedData/getSubmittedAdvocatesInDiary?aor_code=".urlencode($aor_code)."&diary_no=".urlencode($diary_no)."&next_dt=".urlencode($next_dt);
        $data = file_get_contents($url);
        if ($data != false) {
            return json_decode($data);
        } else {
            return NULL;
        }
    }

    public function getAppearingAdvocates($aor_code, $diary_no, $list_date) {
        $url = ICMIS_SERVICE_URL."/ConsumedData/getAppearingAdvocates?aor_code=".urlencode($aor_code)."&diary_no=".urlencode($diary_no)."&list_date=".urlencode($list_date);
        $data = file_get_contents($url);
        if ($data != false) {
            return json_decode($data);
        } else {
            return NULL;
        }
    }

    public function getPreviousListingDate($aor_code, $diary_no, $next_dt) {
        $url = ICMIS_SERVICE_URL."/ConsumedData/getPreviousListingDate?aor_code=".urlencode($aor_code)."&diary_no=".urlencode($diary_no)."&next_dt=".urlencode($next_dt);
        $data = file_get_contents($url);
        if ($data != false) {
            return json_decode($data);
        } else {
            return NULL;
        }
    }

    public function getPreviousListAdvocates($aor_code, $diary_no, $list_date) {
        $url = ICMIS_SERVICE_URL."/ConsumedData/getPreviousListAdvocates?aor_code=".urlencode($aor_code)."&diary_no=".urlencode($diary_no)."&list_date=".urlencode($list_date);
        $data = file_get_contents($url);
        if ($data != false) {
            return json_decode($data);
        } else {
            return NULL;
        }
    }

    public function getAdvocateMasterList($data) {
        $url = ICMIS_SERVICE_URL."/ConsumedData/getAdvocateMasterList";
        $data = file_get_contents($url);
        if ($data != false) {
            return json_decode($data);
        } else {
            return NULL;
        }
    }

    public function saveAppearanceConfirmationData($efiling_nums_str) {
        $postdata = http_build_query(
            [
                'data' => $efiling_nums_str
            ]
        );
        $opts = ['http' =>
            [
                'method'  => 'POST',
                'header'  => 'Content-Type: application/x-www-form-urlencoded',
                'content' => $postdata
            ]
        ];
        $context  = stream_context_create($opts);
        // $url = ICMIS_SERVICE_URL."/ConsumedData/saveAppearanceConfirmationData?data=".$efiling_nums_str;
        $data = file_get_contents(ICMIS_SERVICE_URL."/ConsumedData/saveAppearanceConfirmationData", false, $context);
        // $data = file_get_contents($url);
         if ($data != false) {
             return $data;
         } else {
             return NULL;
         }
    }
    public function updateAppearanceConfirmationData($value, $efiling_nums_str) {

        $postdata = http_build_query(
            [
                'value' => $value,
                'update' => $efiling_nums_str
            ]
        );
        $opts = ['http' =>
            [
                'method'  => 'POST',
                'header'  => 'Content-Type: application/x-www-form-urlencoded',
                'content' => $postdata
            ]
        ];
        $context  = stream_context_create($opts);
        $data = file_get_contents(ICMIS_SERVICE_URL."/ConsumedData/updateAppearanceConfirmationData", false, $context);
         if ($data != false) {
             return $data;
         } else {
             return NULL;
         }
    }
    public function removeAppearanceConfirmationData($value, $efiling_nums_str) {

        $postdata = http_build_query(
            [
                'value' => $value,
                'is_active' => $efiling_nums_str
            ]
        );
        $opts = ['http' =>
            [
                'method'  => 'POST',
                'header'  => 'Content-Type: application/x-www-form-urlencoded',
                'content' => $postdata
            ]
        ];
        $context  = stream_context_create($opts);
        $data = file_get_contents(ICMIS_SERVICE_URL."/ConsumedData/removeAppearanceConfirmationData", false, $context);
         if ($data != false) {
             return $data;
         } else {
             return NULL;
         }
    }

    public function getAdvocateDiaryDetails($list_date_ymd, $court_no) {
        $url = ICMIS_SERVICE_URL."/ConsumedData/getAdvocateDiaryDetails?list_date_ymd=$list_date_ymd&court_no=$court_no";
        $data = file_get_contents($url);
        return $data;
        // if ($data != false) {
        //     return json_decode($data);
        // } else {
        //     return NULL;
        // }
    }

    public function getAdvocateAppearanceDetails($diary_no, $adv_for, $list_date_ymd, $aor_code) {
        $url = ICMIS_SERVICE_URL."/ConsumedData/getAdvocateAppearanceDetails?diary_no=$diary_no&adv_for=$adv_for&list_date_ymd=$list_date_ymd&aor_code=$aor_code";
        $data = file_get_contents($url);
        if ($data != false) {
            return json_decode($data);
        } else {
            return NULL;
        }
    }

    public function getAdvocateAppearanceAORIncludeORExclude($diary_no, $adv_for, $list_date_ymd, $aor_code) {
        $url = ICMIS_SERVICE_URL."/ConsumedData/getAdvocateAppearanceAORIncludeORExclude?diary_no=$diary_no&adv_for=$adv_for&list_date_ymd=$list_date_ymd&aor_code=$aor_code";
        $data = file_get_contents($url);
        if ($data != false) {
            return json_decode($data);
        } else {
            return NULL;
        }
    }

    /*start function Recategrization */
    public function get_cases_efiled_new_submaster_id($efiling_nums_str){
        $postdata = http_build_query(
            array(
                'pending_recategorization' => $efiling_nums_str
            )
        );
        // $opts = array('http' =>
        //     array(
        //         'method'  => 'POST',
        //         'header'  => 'Content-Type: application/x-www-form-urlencoded',
        //         'content' => $postdata
        //     )
        // );
        // $context  = stream_context_create($opts);
        // $data = file_get_contents(ICMIS_SERVICE_URL."/ConsumedData/get_cases_efiled_new_submaster_id", false, $context);
        // if ($data != false) {
        //     return json_decode($data);
        // } else {
        //     return NULL;
        // }
        $curl = curl_init();
        $url = ICMIS_SERVICE_URL;
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url.'/ConsumedData/get_cases_efiled_new_submaster_id',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $postdata,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded',
                'Content-Length: ' . strlen($postdata)
            )
        ));        
        $response = curl_exec($curl);        
        curl_close($curl);
        return $decodedResponse = json_decode($response);
    }
    /*end function Recategrization */

    public function get_bar_details($type, $aor_ids) {
        $postdata = http_build_query(
            array(
                'type' => $type,
                'aors' => $aor_ids
            )
        );
        $curl = curl_init();
        $url = ICMIS_SERVICE_URL;
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url.'/ConsumedData/getBarAORDetails',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $postdata,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded',
                'Content-Length: ' . strlen($postdata)
            )
        ));        
        $response = curl_exec($curl);
        curl_close($curl);
        return $decodedResponse = json_decode($response);
    }

}