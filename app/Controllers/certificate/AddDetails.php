<?php
namespace App\Controllers;

class AddDetails extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('certificate/Details_model');
        $this->load->library('webservices/efiling_webservices');
        $this->load->model('common/Common_model');
    }

    public function index() {

        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            redirect('login');
            exit(0);
        }
        $data['lowerCtDetails']=$this->efiling_webservices->getLowerCtDetails($_SESSION['efiling_details']['diary_no'].$_SESSION['efiling_details']['diary_year']);
        $data['parties']=$this->efiling_webservices->get_parties($_SESSION['efiling_details']['diary_no'].$_SESSION['efiling_details']['diary_year']);
        $data['app_name']="Certificate Request";
        $this->load->view('templates/header');
        $this->load->view('certificate/addDetails', $data);
        $this->load->view('templates/footer');
    }


    public function add_certificate_request() {   // to modify

        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_DEPARTMENT);

        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            echo '2@@@' . htmlentities('Unauthorised Access!', ENT_QUOTES);
            exit(0);
        }

        $this->form_validation->set_rules('party', 'party', 'required|trim');
        $this->form_validation->set_rules('jailCode', 'Jail', 'required|trim');
        if($_POST['hcCases']=='' or $_POST['hcCases']==null)
        $this->form_validation->set_rules('LC_Data', 'Lower Court Details', 'required');
       // $this->form_validation->set_error_delimiters('<br/>', '');
        if (!$this->form_validation->run()) {
            echo '3@@@';
            echo form_error('party'). form_error('jailCode').form_error('LC_Data');
            exit(0);
        }

        $jailCode=$_POST['jailCode'];
        $party_id = $_POST['party'];
        $party_name = $_POST['party_name'];
        $hcCases = $_POST['hcCases'];
        $certificate_type=$_POST['certificate_type'];
        $state=$_POST['state'];
        // INSERT APPEARING FOR AND CONTACT DETAILS OF PARTIES AND UPDATE BREADCRUMB
        $registration_id = $_SESSION['efiling_details']['registration_id'];
        $efiling_num=$this->Details_model->get_efiling_number($registration_id);
        $case_id=$this->Details_model->get_case_id($_SESSION['efiling_details']['diary_no'],$_SESSION['efiling_details']['diary_year']);
        $hcCases= !empty($hcCases) ? rtrim($hcCases,',') : '';
        $hcCase=explode(',',$hcCases);

        $noOfCases=count($hcCase);

        for($i=0;$i<$noOfCases;$i++) {
            $cases=explode('#',$hcCase[$i]);
            if($cases[4]=='d')
            {$establishment = explode('#$', url_decryption($cases[3]));
            $estab_code = $establishment[0];
            $estab_name = $establishment[1];}
            else if($cases[4]=='h')
            {
                $bench = explode('##', url_decryption($cases[3]));
                $estab_code = $bench[2];
                $estab_name = $bench[1];
                //$estab_name = $estab_name . " High Court";
            }
            $lowerct_case_year='';
            if (strpos($cases[2], '.') !== false)
                $lowerct_case_year=url_decryption(escape_data($cases[2]));
            else
                $lowerct_case_year=$cases[2];
            $certificate_detail = array(
                'tbl_sci_cases_id' => $case_id[0]['id'],
                'updated_by' => $_SESSION['login']['id'],
                'updated_by_ip' => getClientIP(),
                'updated_on' => date('Y-m-d H:i:s'),
                'registration_id' => $registration_id,
                'certificate_type' => $certificate_type,
                'party_name' => $party_name,
                'party_id' => $party_id,
                'jailcode' => url_decryption(escape_data($jailCode)),
                'state_code' => (empty($state)?null:$state),
                'request_status' => 'P',
                'efiling_no' => $efiling_num[0]['efiling_no'],
                'lowerct_case_no'=>$cases[1],
                'lowerct_case_year'=>$lowerct_case_year,
                'estab_code'=>$estab_code,
                'estab_name'=>$estab_name
            );
            $details_saved_status = $this->Details_model->add_details($certificate_detail,$registration_id);
        }

        if ($details_saved_status) {
            echo "1@@@Certificate Details added successfully.";
        } else {
            echo "2@@@Some Error ! Please try after some time.";
        }
    }
}
