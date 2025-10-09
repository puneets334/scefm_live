<?php
namespace App\Controllers;

class DefaultController extends BaseController {

    public function __construct() {
        parent::__construct();
        if (!isset($_SESSION['login']) && empty($_SESSION['login'])) {
            redirect('login');
        }else{
            is_user_status();
        }
        $this->load->model('dashboard/Dashboard_model');
        $this->load->library('slice');

        unset($_SESSION['efiling_details']);
        unset($_SESSION['case_table_ids']);
        unset($_SESSION['parties_list']);
        unset($_SESSION['efiling_type']);
        unset($_SESSION['pg_request_payment_details']);
        unset($_SESSION['eVerified_mobile_otp']['LITIGENT_MOB_OTP_VERIFY']);
    }

    public function index() {

        $users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_DEPARTMENT, USER_PDE);

        if (in_array($_SESSION['login']['ref_m_usertype_id'], $users_array)) {

            $data['count_efiling_data'] = $this->Dashboard_model->get_efilied_nums_stage_wise_count();
            $from_date = $next_date = date("Y-m-d", strtotime("today"));
            $data['listFor'] = 'today';

            $this->load->view('templates/header');
            $this->load->view('dashboard/dashboard_view', $data);
            $this->load->view('templates/footer');
        } else {
            redirect('login');
            exit(0);
        }
    }

    public function showGlobalSearch(){
        /*
        $data['advocate_list_cases_future_dates']=null;
        $data['scheduled_cases']=null;
        $data['advocate_pending_cases']=null;*/

        $advocate_id = $this->session->userdata['login']['adv_sci_bar_id'];


        /* getting all cases of logged in AOR - start*/
        //$advocate_cases_response_str = file_get_contents('http://40.186.14:91/index.php/DefaultController/out_service/index.php/ConsumedData/getAdvocateAllCases/?advocateId='.$advocate_id);
        //$data['advocate_pending_cases'] = json_decode($advocate_cases_response_str);
        /* getting all cases of logged in AOR - end*/

        /* getting logged in AOR future dates listed case dates-start*/

        $fgc_context=array(
            'http' => array(
                'user_agent' => 'Mozilla',
            ),
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );

        //$schedule_request_params = ['responseFormat' => 'CASE_WISE_FLATTENED_WITH_ALL_INFO', 'advocates'=> [$advocate_id], 'forDate' => 'all', 'ifSkipDigitizedCasesStageComputation' => true];
        //$data['scheduled_cases'] = json_decode(@file_get_contents(API_CAUSELIST_URI.'?'.http_build_query($schedule_request_params), false, stream_context_create($fgc_context)));
        $data['scheduled_cases'] = null;
        /* getting logged in AOR future dates listed case dates-end*/

       // $data['advocate_list_cases_future_dates'] = json_encode($this->Dashboard_model->advocate_list_cases_future_dates(), JSON_NUMERIC_CHECK);


        $this->load->view('templates/header');
        $this->slice->view('dashboard.global_search',$data);
        $this->load->view('templates/footer');
    }

}

?>