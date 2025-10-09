<?php
namespace App\Controllers;

class Listing extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('mentioning/Listing_model');
        $this->load->library('webservices/efiling_webservices');
        $this->load->model('common/Common_model');
    }

    public function index()
    {

        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            redirect('login');
            exit(0);
        }
        $data['app_name'] = "Mentioning";
        $holidays = '';
        $holiday = $this->efiling_webservices->get_holidays();
        $data['holidays'] = $holiday;
        //$data['listing'] = $this->Listing_model->get_mentioning_request_details($_SESSION['efiling_details']['diary_no'],$_SESSION['efiling_details']['diary_year']);
        if(!empty($_SESSION['efiling_details']['efiling_no']) && $_SESSION['efiling_details']['efiling_no']!=null){
            $data['listing'] = $this->Listing_model->check_get_mentioning_request_details($_SESSION['efiling_details']['efiling_no']);
        }else{$data['listing']=[];}
        //echo '<pre>';print_r($data);exit();
        $this->load->view('templates/header');
        $this->load->view('mentioning/listing', $data);
        $this->load->view('templates/footer');
    }

    public function send_otp()
    {
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_DEPARTMENT);

        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            echo '2@@@' . htmlentities('Unauthorised Access!', ENT_QUOTES);
            exit(0);
        }
        $text = "E-mentioning Diary No. " . $_SESSION['efiling_details']['diary_no'] . "/" . $_SESSION['efiling_details']['diary_year'];
        send_otp($text, 39);

    }

    public function resend_otp()
    {
        $text = "E-mentioning Diary No. " . $_SESSION['efiling_details']['diary_no'] . "/" . $_SESSION['efiling_details']['diary_year'];
        //var_dump($text);
        resend_otp(39, $text);
    }

    public function add_details()
    {
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_DEPARTMENT);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            echo '2@@@' . htmlentities('Unauthorised Access!', ENT_QUOTES);
            exit(0);
        }
        $this->form_validation->set_rules('synopsis', 'Synopsis of Urgency', 'required|trim|validate_alpha_numeric_space_dot');
        $this->form_validation->set_rules('listing_option', 'Listing', 'required|in_list[1,2]');
        $this->form_validation->set_rules('request_type', 'Request Type', 'required');
        if(!empty($this->input->post('engaged'))){
            $this->form_validation->set_rules('council_name', 'Party Name', 'required|trim|validate_alpha_numeric_space_dot');
            $this->form_validation->set_rules('council_email', 'Email', 'required|trim|min_length[5]|max_length[50]|valid_email');
            $this->form_validation->set_rules('council_mob', 'Mobile', 'required|trim|exact_length[10]|is_natural');
        }
        $this->form_validation->set_rules('otp', 'OTP', 'required|trim');
        $this->form_validation->set_error_delimiters('<br/>', '');
        if (!$this->form_validation->run()) {
            echo '3@@@';
            if(!empty($this->input->post('engaged'))){
                echo form_error('synopsis') . form_error('listing_option') . form_error('council_name') . form_error('council_email') . form_error('council_mob') . form_error('otp');
            }
            else{
                echo form_error('synopsis') . form_error('listing_option').form_error('otp');
            }
            exit(0);
        }
        $otp = $_POST['otp'];
        $otp_validate_response = validate_otp(39, $otp);
        if ($otp_validate_response == false) {
            echo '4@@@';
            echo "Invalid OTP";
            exit(0);
        }
        $fixed_date = false;
        $listing_option = escape_data($_POST['listing_option']);
        if ($listing_option == 1)
            $fixed_date = true;
        $synopsis = $_POST['synopsis'];
        $council_name = !empty($_POST['council_name']) ? $_POST['council_name'] : NULL;
        $council_email = !empty($_POST['council_email']) ? $_POST['council_email'] : NULL;
        $council_mobile = !empty($_POST['council_mob']) ? $_POST['council_mob'] : NULL;
        $listing_date = $_POST['listing_date'];
        $request_type = $_POST['request_type'];
        $date_range = $_POST['listing_date_range'];
        if (isset($_POST['listing_date']) && !empty($_POST['listing_date'])) {
            $listing_date_array = explode('/', $listing_date);
            $listing_date = $listing_date_array[2] . '-' . $listing_date_array[1] . '-' . $listing_date_array[0];
        } else
            $listing_date = NULL;
        if (isset($date_range) && !empty($date_range)) {
            $date_range_part = explode('-', $date_range);
            $first_dateP=new DateTime(str_replace('/','-',$date_range_part[0]));
            $first_dateF= $first_dateP->format('Y-m-d');

            $second_dateP = new DateTime(str_replace('/','-',$date_range_part[1]));
            $second_dateF= $second_dateP->format('Y-m-d');
            if (!empty($first_dateF) && $first_dateF!=null && !empty($second_dateF) && $second_dateF!=null){
                $date_range = '[' . trim($first_dateF) . ',' . trim($second_dateF) . ']';
            }else{ $date_range = NULL;}

        }else{$date_range = NULL;}

        // INSERT APPEARING FOR AND CONTACT DETAILS OF PARTIES AND UPDATE BREADCRUMB
        $registration_id = $_SESSION['efiling_details']['registration_id'];
        $efiling_num = $this->Listing_model->get_efiling_number($registration_id);
        $case_id = $this->Listing_model->get_case_id($_SESSION['efiling_details']['diary_no'], $_SESSION['efiling_details']['diary_year']);
        $last_listed_date = $this->efiling_webservices->get_last_listed_details(escape_data($_SESSION['efiling_details']['diary_no']), escape_data($_SESSION['efiling_details']['diary_year']));
        $judges_list = $last_listed_date->listed[0]->judges;
        $judges = $this->Listing_model->get_judges($judges_list);
        $judges_name_list = explode(',', $judges[0]['jname']);
        $judges_code_list = explode(',', $judges[0]['jcode']);
        $mentioning_detail = array(
            'tbl_sci_cases_id' => $case_id[0]['id'],
            'is_for_fixed_date' => $fixed_date,
            'synopsis_of_emergency' => $synopsis,
            'mentioned_by' => $_SESSION['login']['id'],
            'mentioned_by_ip' => getClientIP(),
            'mentioned_on' => date('Y-m-d H:i:s'),
            'created_on' => date('Y-m-d H:i:s'),
            'arguing_counsel_name' => $council_name,
            'arguing_consel_mobile_no' => $council_mobile,
            'arguing_counsel_email' => $council_email,
            'requested_listing_date' => $listing_date,
            'requested_listing_date_range' => $date_range,
            'request_type' => $request_type,
            'is_deleted' => 'false',
            'efiling_number' => $efiling_num[0]['efiling_no'],
            'mentioned_before_judge' => $judges_code_list[0]

        );

        $checklisting= $this->Listing_model->check_get_mentioning_request_details($efiling_num[0]['efiling_no']);
        if (!empty($checklisting) && $checklisting!=null){
            // echo "2@@@already added efiling no:".$efiling_num[0]['efiling_no'];
            redirect('case/mentioning/').$registration_id;
            exit();
        }
        $details_saved_status = $this->Listing_model->add_details($mentioning_detail,$registration_id);
        if ($details_saved_status) {
            echo "1@@@Mentioning Details added successfully.";
        } else {
            echo "2@@@Some Error ! Please try after some time.";
        }
    }


}
