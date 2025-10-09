<?php

namespace App\Controllers\jailPetition;

use App\Controllers\BaseController;
use App\Models\Common\CommonModel;
use App\Models\JailPetition\JailPetitionModel;
use App\Models\NewCase\DropdownListModel;
use App\Models\NewCase\GetDetailsModel;
use App\Libraries\webservices\Highcourt_webservices;

class Subordinate_court extends BaseController
{
    protected $Common_model;
    protected $Dropdown_list_model;
    protected $Get_details_model;
    protected $JailPetitionModel;
    protected $highcourt_webservices;

    public function __construct()
    {
        parent::__construct();
        $this->Common_model = new CommonModel();
        $this->Dropdown_list_model = new DropdownListModel();
        $this->Get_details_model = new GetDetailsModel();
        $this->JailPetitionModel = new JailPetitionModel();
        $this->highcourt_webservices = new Highcourt_webservices();
    }

    public function index()
    {
        $allowed_users_array = array(JAIL_SUPERINTENDENT);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $stages_array = array('', Draft_Stage, Initial_Defected_Stage, I_B_Defected_Stage);
        if (!in_array($_SESSION['efiling_details']['stage_id'], $stages_array)) {
            return redirect()->to(base_url('jailPetition/view'));
            exit(0);
        }
        if (isset($_SESSION['efiling_details']['registration_id']) && !empty($_SESSION['efiling_details']['registration_id'])) {
            $registration_id = $_SESSION['efiling_details']['registration_id'];
            $data['subordinate_court_details'] = $this->Get_details_model->get_subordinate_court_details($registration_id);
            $this->Get_details_model->get_case_table_ids($registration_id);
        }
        return $this->render('jailPetition.new_case_view', $data);
    }

    public function add_subordinate_court_details()
    {
        $registration_id = $_SESSION['efiling_details']['registration_id'];
        if (!empty($_SESSION['search_case_data_save']['date_of_decision'])) {
            $decision_date = $_SESSION['search_case_data_save']['date_of_decision'];
            $status = TRUE;
        } else {
            $decision_date = NULL;
            $status = FALSE;
        }
        if (!empty($_POST['order_date'])) {
            if (!preg_match("/^(0[1-9]|[1-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/[0-9]{4}$/", $_POST['order_date'])) {
                echo '1@@@' . htmlentities('Invalid Date of birth date format !', ENT_QUOTES);
                exit(0);
            }
            $date = explode('/', $_POST['order_date']);
            $decision_date = $date[2] . '-' . $date[1] . '-' . $date[0];
        } else {
            $decision_date = $_SESSION['search_case_data_save']['date_of_decision'];
        }
        if ($_POST['radio_selected_court'] == '1') {
            $case_type_array = url_decryption($_POST['case_type_id']);
            $case_type_ids = explode('##', $case_type_array);
            $hc = explode('##', url_decryption($_POST['hc_court']));
            $bench = explode('##', url_decryption($_POST['hc_court_bench']));
            $estabid = $hc[0];
            $estab_code = $bench[2];
            $estab_name = $hc[1];
            $court_name = $estab_name . " High Court";
            $state_id = 0;
            $state_name = 0;
            $district_id = 0;
        } elseif ($_POST['radio_selected_court'] == '3') {
            $case_type_array = url_decryption($_POST['dc_case_type_id']);
            $case_type_ids = explode('#$', $case_type_array);
            $state = explode('#$', url_decryption($_POST['state']));
            $state_id = $state[0];
            $state_name = $state[1];
            $district = explode('#$', url_decryption($_POST['district']));
            $district_id = $district[0];
            $district_name = $district[1];
            $establishment = explode('#$', url_decryption($_POST['establishment']));
            $estab_code = $establishment[0];
            $estab_name = $establishment[1];
            $estabid = $establishment[2];
        }
        $case_details = array(
            'registration_id' => $registration_id,
            'court_type' => $_POST['radio_selected_court'],
            'state_id' => !empty($state_id) ? $state_id : 0,
            'district_id' => !empty($district_id) ? $district_id : 0,
            'agency_code' => 0,
            'pet_name' => $_SESSION['search_case_data_save']['pet_name'],
            'res_name' => $_SESSION['search_case_data_save']['res_name'],
            'case_type_id' => (isset($case_type_ids[0]) && !empty($case_type_ids[0])) ? $case_type_ids[0] : $_SESSION['search_case_data_save']['case_type_id'],
            'case_num' => $_SESSION['search_case_data_save']['case_num'],
            'case_year' => $_SESSION['search_case_data_save']['case_year'],
            'cnr_num' => $_SESSION['search_case_data_save']['cnr_num'],
            'impugned_order_date' => $decision_date,
            'is_judgment_challenged' => $_POST['judgement_challenged'],
            'judgment_type' => $_POST['judgement_type'],
            'status' => $status,
            // 'decision_date' => $decision_date,
            'casetype_name' => (isset($case_type_ids[1]) && !empty($case_type_ids[1])) ? $case_type_ids[1] : $_SESSION['search_case_data_save']['case_type_name'],
            'estab_id' => (isset($estabid) && !empty($estabid)) ? $estabid : NULL,
            'estab_name' => (isset($estab_name) && !empty($estab_name)) ? $estab_name : $_SESSION['search_case_data_save']['court_est_name'],
            'estab_code' => (isset($estab_code) && !empty($estab_code)) ? $estab_code : $_SESSION['search_case_data_save']['est_code'],
            'case_status' => $_SESSION['search_case_data_save']['case_status'],
            'state_name' => $state_name,
            'district_name' => $district_name
        );
        $fir_state = explode('#$', url_decryption($_POST['fir_state']));
        $fir_state_id = $fir_state[0];
        $fir_state_name = $fir_state[1];
        $fir_district = explode('#$', url_decryption($_POST['fir_district']));
        $fir_district_id = $fir_district[0];
        $fir_district_name = $fir_district[1];
        $fir_police_station = explode('#$', url_decryption($_POST['fir_policeStation']));
        $fir_police_station_id = $fir_police_station[0];
        $fir_police_station_name = $fir_police_station[1];
        $complete_fir_no = '';
        $fir_year = url_decryption($_POST['fir_year']);
        if (!empty($fir_police_station_id)) {
            $no = $_POST['fir_number'];
            $filled_int = sprintf("%04d", $no);
            $year = substr($fir_year, -2);
            $complete_fir_no = $fir_police_station_id . $filled_int . $year;
        }
        $fir_details = array(
            'registration_id' => $registration_id,
            'state_id' => !empty($fir_state_id) ? $fir_state_id : 0,
            'district_id' => !empty($fir_district_id) ? $fir_district_id : 0,
            'police_station_id' => !empty($fir_police_station_id) ? $fir_police_station_id : 0,
            'state_name' => $fir_state_name,
            'district_name' => !empty($fir_district_name) ? $fir_district_name : 'ALL',
            'police_station_name' => !empty($fir_police_station_name) ? $fir_police_station_name : $_POST['police_station_name'],
            'fir_no' => !empty($_POST['fir_number']) ? $_POST['fir_number'] : '',
            'fir_year' => !empty($fir_year) ? $fir_year : 0,
            'complete_fir_no' => !empty($complete_fir_no) ? $complete_fir_no : $_POST['complete_fir_number'],
            'is_deleted' => 'false',
            'status' => 'true',
            'updated_by' => $_SESSION['login']['id'],
            'updated_by_ip' => getClientIP(),
            'updated_on' => date('Y-m-d H:i:s')
        );
        if (isset($registration_id) && !empty($registration_id)) {
            $status = $this->JailPetitionModel->add_subordinate_court_info($registration_id, $case_details, JAIL_PETITION_SUBORDINATE_COURT, $fir_details);
            if ($status) {
                echo '2@@@' . htmlentities('Details added successfully!', ENT_QUOTES) . '@@@' . base_url('jailPetition/defaultController/' . url_encryption(trim($registration_id . '#' . E_FILING_TYPE_JAIL_PETITION . '#' . $_SESSION['efiling_details']['stage_id'])));
            } else {
                echo '1@@@' . htmlentities('Some error ! Please Try again.', ENT_QUOTES);
            }
        }
    }

    public function deleteSubordinateCourt($delete_id)
    {
        $delete_id = url_decryption($delete_id);
        if (empty($delete_id)) {
            $_SESSION['MSG'] = message_show("fail", "Invalid ID."); // this msg is not being displayed. checking required
            return redirect()->to(base_url('jail_dashboard'));
            exit(0);
        }
        $allowed_users_array = array(JAIL_SUPERINTENDENT);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            return redirect()->to(base_url('jail_dashboard'));
            exit(0);
        }
        $stages_array = array('', Draft_Stage, Initial_Defected_Stage, I_B_Defected_Stage);
        if (!in_array($_SESSION['efiling_details']['stage_id'], $stages_array)) {
            return redirect()->to(base_url('jail_dashboard'));
            exit(0);
        }
        if (isset($_SESSION['efiling_details']['registration_id']) && !empty($_SESSION['efiling_details']['registration_id'])) {
            $registration_id = $_SESSION['efiling_details']['registration_id'];
            $delete_status = $this->JailPetitionModel->delete_case_subordinate_court($registration_id, $delete_id);
            if ($delete_status) {
                $_SESSION['MSG'] = message_show("success", "Detail deleted successfully."); // this msg is not being displayed. checking required
            } else {
                $_SESSION['MSG'] = message_show("fail", "Some error. Please try again."); // this msg is not being displayed. checking required
            }
            return redirect()->to(base_url('jailPetition/defaultController/' . url_encryption(trim($registration_id . '#' . E_FILING_TYPE_JAIL_PETITION . '#' . $_SESSION['efiling_details']['stage_id']))));
            exit(0);
        }
    }

    // This call is once in a while functionality to update HC & Bench master table i.e: m_tbl_high_courts_bench
    function hc_bench_from_api()
    {
        if (!$this->request->isCLI()) {
            echo "This script can only be accessed via the command line" . PHP_EOL;
            return;
        }
        $high_courts = $this->highcourt_webservices->states();
        $high_courts = json_decode($high_courts, true);
        if (isset($high_courts['state'])) {
            foreach ($high_courts['state'] as $courts) {
                $this->Dropdown_list_model->insert_to_table('efil.m_tbl_high_courts_bench', array('hc_id' => $courts['state_code'], 'name' => $courts['state_name']));
            }
        }
        $hc_list = $this->Dropdown_list_model->high_courts();
        foreach ($hc_list as $hc) {
            $hc_benches = $this->highcourt_webservices->bench($hc['hc_id']);
            $hc_benches = json_decode($hc_benches, true);
            if (isset($hc_benches['benches'])) {
                foreach ($hc_benches['benches'] as $bench) {
                    $this->Dropdown_list_model->insert_to_table('efil.m_tbl_high_courts_bench', array('hc_id' => $hc['hc_id'], 'name' => $bench['bench_name'], 'bench_id' => $bench['bench_id'], 'est_code' => $bench['est_code']));
                }
            }
        }
    }

    // This call is once in a while functionality to update HC & Bench master table i.e: m_tbl_high_courts_bench
    function hc_case_types_from_api()
    {
        if (!$this->request->isCLI()) {
            echo "This script can only be accessed via the command line" . PHP_EOL;
            return;
        }
        $bench_list = $this->Dropdown_list_model->hc_bench(0, 0);
        foreach ($bench_list as $bench) {
            $hc_case_types = $this->highcourt_webservices->caseTypeMaster($bench['est_code']);
            $hc_case_types = json_decode($hc_case_types, true);
            if (isset($hc_case_types)) {
                foreach ($hc_case_types as $hc_case_type) {
                    $this->Dropdown_list_model->insert_to_table('efil.m_tbl_high_courts_case_types', array('est_code' => $bench['est_code'], 'case_type' => $hc_case_type['case_type'], 'type_name' => $hc_case_type['type_name']));
                    // $dropDownOptions .= '<option value="' . escape_data(url_encryption($hc_case_type['case_type'] . "##" . $hc_case_type['type_name'] )) . '">' . escape_data(strtoupper($hc_case_type['type_name'])) . '</option>';
                }
            }
        }
    }
}
