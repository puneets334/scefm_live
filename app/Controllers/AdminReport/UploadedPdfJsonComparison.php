<?php

namespace App\Controllers\AdminReport;

use App\Controllers\BaseController;
use App\Models\AIAssisted\IitmIcmisEfmApiJsonModel;

class UploadedPdfJsonComparison extends BaseController
{

    protected $Iitm_Icmis_Efm_Api_Json_Model;

    public function __construct()
    {
        parent::__construct();
        $this->Iitm_Icmis_Efm_Api_Json_Model = new IitmIcmisEfmApiJsonModel();
        if (empty(getSessionData('login'))) {
            return response()->redirect(base_url('/'));
        } else {
            is_user_status();
        }
        if (empty(getSessiondata('login')['ref_m_usertype_id'])) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $allowed_users_array = array(ICMIS_IITM_EFILE_COMPARISON_USERS);
        if (!in_array(getSessiondata('login')['userid'], $allowed_users_array)) {
            return redirect()->to(base_url('adminDashboard'));
            exit(0);
        }
    }

    public function index()
    {
        // if (empty(getSessionData('login')['ref_m_usertype_id'])) {
        //     return redirect()->to(base_url('/')); 
        // }
        // $allowed_users_array = array(USER_EFILING_ADMIN, USER_ADMIN);
        // if (getSessionData('login') != '' && !in_array(getSessionData('login')['ref_m_usertype_id'], $allowed_users_array)) {
        //     return redirect()->to(base_url('/'));
        //     exit(0);
        // }
        return $this->render('adminReport.uploaded_pdf_json_search');
    }

    public function search()
    {
        // if (empty(getSessionData('login')['ref_m_usertype_id'])) {
        //     return redirect()->to(base_url('/')); 
        // }
        // $allowed_users_array = array(USER_EFILING_ADMIN, USER_ADMIN);
        // if (getSessionData('login') != '' && !in_array(getSessionData('login')['ref_m_usertype_id'], $allowed_users_array)) {
        //     return redirect()->to(base_url('/'));
        //     exit(0);
        // }
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            return redirect()->to(base_url('adminReport/UploadedPdfJsonComparison'));
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fromdate = $todate = isset($_POST['from_date']) ? $_POST['from_date'] : '';
            $efileno  = isset($_POST['efileno']) ? $_POST['efileno'] : '';
            if (empty($fromdate) && empty($efileno)) {
                $this->session->setFlashdata('error', '<div class="text-danger">Please enter E-file No or E-file Uploaded On.</div>');
                return redirect()->to(base_url('adminReport/UploadedPdfJsonComparison'));
                exit(0);
            }
            $case_details = $this->Iitm_Icmis_Efm_Api_Json_Model->get_uploaded_pdf_detail_list($fromdate, $todate, $efileno);
            $data = [
                'from_date'    => $_POST['from_date'],
                'efileno'      => $efileno,
                'case_details' => $case_details
            ];
            return $this->render('adminReport.uploaded_pdf_json_search', $data);
        }
    }

    public function compare()
    {
        // if (empty(getSessionData('login')['ref_m_usertype_id'])) {
        //     return response()->redirect(base_url('/')); 
        // }
        // $allowed_users_array = array(USER_EFILING_ADMIN, USER_ADMIN);
        // if (getSessionData('login') != '' && !in_array(getSessionData('login')['ref_m_usertype_id'], $allowed_users_array)) {
        //     return redirect()->to(base_url('filingAdmin'));
        //     exit(0);
        // }
        $registration_id     = $_GET['registration_id'];
        $view_type           = $_GET['view_type'];
        $json_detail         = $this->Iitm_Icmis_Efm_Api_Json_Model->get_uploaded_pdf_json_data($registration_id);
        $data['json_detail'] = $json_detail;
        if ($view_type == 'table') {
            $iitm_api_array                  = json_decode($json_detail->iitm_api_json, true);
            $iitm_case_details_columns       = array_keys(isset($iitm_api_array['case_details']) ? $iitm_api_array['case_details'] : []);
            $iitm_main_petitioner_columns    = array_keys(isset($iitm_api_array['main_petitioner']) ? $iitm_api_array['main_petitioner'] : []);
            $iitm_main_reposndent_columns    = array_keys(isset($iitm_api_array['main_reposndent']) ? $iitm_api_array['main_reposndent'] : []);
            $iitm_extra_party_columns        = array_keys(isset($iitm_api_array['extra_party']) ? $iitm_api_array['extra_party'] : []);
            $iitm_earlier_courts_columns     = array_keys(isset($iitm_api_array['earlier_courts']) ? $iitm_api_array['earlier_courts'] : []);
            $efiling_array                   = json_decode($json_detail->efiling_json, true);
            $efiling_case_details_columns    = array_keys(isset($efiling_array['case_details'][0]) ? $efiling_array['case_details'][0] : []);
            $efiling_main_petitioner_columns = array_keys(isset($efiling_array['main_petitioner'][0]) ? $efiling_array['main_petitioner'][0] : []);
            $efiling_main_reposndent_columns = array_keys(isset($efiling_array['main_respondent'][0]) ? $efiling_array['main_respondent'][0] : []);
            $efiling_extra_party_columns     = array_keys(isset($efiling_array['extra_party'][0]) ? $efiling_array['extra_party'][0] : []);
            $efiling_earlier_courts_columns  = array_keys(isset($efiling_array['earlier_courts'][0]) ? $efiling_array['earlier_courts'][0] : []);
            $icmis_array                     = json_decode($json_detail->icmis_json, true);
            $icmis_case_details_columns      = array_keys(isset($icmis_array['case_details']) ? $icmis_array['case_details'] : []);
            $icmis_main_petitioner_columns   = array_keys(isset($icmis_array['main_petitioner']) ? $icmis_array['main_petitioner'] : []);
            $icmis_main_reposndent_columns   = array_keys(isset($icmis_array['main_respondent']) ? $icmis_array['main_respondent'] : []);
            $icmis_extra_party_columns       = array_keys(isset($icmis_array['extra_party'][0]) ? $icmis_array['extra_party'][0] : []);
            $icmis_earlier_courts_columns    = array_keys(isset($icmis_array['earlier_courts'][0]) ? $icmis_array['earlier_courts'][0] : []);
            $case_details_array_column       = array_unique(array_merge($iitm_case_details_columns, $efiling_case_details_columns, $icmis_case_details_columns));
            $main_petitioner_array_column    = array_unique(array_merge($iitm_main_petitioner_columns, $efiling_main_petitioner_columns, $icmis_main_petitioner_columns));
            $main_reposndent_array_column    = array_unique(array_merge($iitm_main_reposndent_columns, $efiling_main_reposndent_columns, $icmis_main_reposndent_columns));
            $extra_party_array_column        = array_unique(array_merge($iitm_extra_party_columns, $efiling_extra_party_columns, $icmis_extra_party_columns));
            $earlier_courts_array_column     = array_unique(array_merge($iitm_earlier_courts_columns, $efiling_earlier_courts_columns, $icmis_earlier_courts_columns));
            $data['case_details_array_column']      = $case_details_array_column;
            $data['main_petitioner_array_column']   = $main_petitioner_array_column;
            $data['main_reposndent_array_column']   = $main_reposndent_array_column;
            $data['extra_party_array_column']       = $extra_party_array_column;
            $data['earlier_courts_array_column']    = $earlier_courts_array_column;
            $data['iitm_extra_party_columns']       = $iitm_extra_party_columns;
            $data['efiling_extra_party_columns']    = $efiling_extra_party_columns;
            $data['icmis_extra_party_columns']      = $icmis_extra_party_columns;
            $data['iitm_earlier_courts_columns']    = $iitm_earlier_courts_columns;
            $data['efiling_earlier_courts_columns'] = $efiling_earlier_courts_columns;
            $data['icmis_earlier_courts_columns']   = $icmis_earlier_courts_columns;
            $data['iitm_api_array']                 = $iitm_api_array;
            $data['efiling_array']                  = $efiling_array;
            $data['icmis_array']                    = $icmis_array;
            return $this->render('adminReport.uploaded_pdf_json_compare_view_type_table', $data);
        } else {
            return $this->render('adminReport.uploaded_pdf_json_compare_view_type_json', $data);
        }
    }
}
