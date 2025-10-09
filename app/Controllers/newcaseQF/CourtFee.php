<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class CourtFee extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('newcaseQF/common/Common_model');
        //   $this->load->model('newcase/New_case_model');
        //    $this->load->model('newcase/Dropdown_list_model');
        $this->load->model('newcaseQF/Get_details_model');
        
        $this->load->model('newcaseQF/Court_Fee_model');

        //line aaded on 6 nov
        $this->load->model('newcaseQF/documentIndex/DocumentIndex_Select_model');
        $this->load->model('shcilPayment/Payment_model');
    }

    public function index() {
        /*if (check_party() !=true) {  //func added on 15 JUN 2021
            $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Please enter every party details before moving to further tabs.</div>');
            redirect('newcase/extra_party');
        }*/
//func added on 6 nov 2020
        /*if (isset($_SESSION['efiling_details']['registration_id']) && !empty($_SESSION['efiling_details']['registration_id'])) {

            $registration_id = $_SESSION['efiling_details']['registration_id'];
            //$index_pdf_details = $this->DocumentIndex_Select_model->unfilled_pdf_pages_for_index($registration_id);
            $index_pdf_details = $this->DocumentIndex_Select_model->is_index_created($registration_id);
            if(empty($index_pdf_details)) {

                $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_DEPARTMENT);
                if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
                    redirect('login');
                    exit(0);
                }

        $stages_array = array('', Draft_Stage, Initial_Defected_Stage, I_B_Defected_Stage);
        if (!empty($_SESSION['efiling_details']['stage_id']) && !in_array($_SESSION['efiling_details']['stage_id'], $stages_array) && $_SESSION['efiling_details']['efiling_type'] == 'new_case'){
            redirect('newcase/view');
            exit(0);
        }
         else if (!empty($_SESSION['efiling_details']['stage_id']) && !in_array($_SESSION['efiling_details']['stage_id'], $stages_array) && $_SESSION['efiling_details']['efiling_type'] == 'CAVEAT'){
                redirect('caveat/view');
                exit(0);
            }*/




                //get total is_dead_minor
                $params = array();
                $params['registration_id'] = !empty($_SESSION['efiling_details']['registration_id']) ? $_SESSION['efiling_details']['registration_id'] : NULL;
                $params['is_dead_minor'] = true;
                $params['is_deleted'] = 'false';
                $params['is_dead_file_status'] ='false';
                $params['total'] =1;
                $isdeaddata = $this->Get_details_model->getTotalIsDeadMinor($params);
                /*if(isset($isdeaddata[0]->total) && !empty($isdeaddata[0]->total)){
                    $total = $isdeaddata[0]->total;
                    $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Please fill '.$total.' remaining dead/minor party details</div>');
                    redirect('newcase/lr_party');
                    exit(0);
                }*/


        if (isset($_SESSION['efiling_details']['registration_id']) && !empty($_SESSION['efiling_details']['registration_id'])) {
            $total_court_fee=0;

            $registration_id = $_SESSION['efiling_details']['registration_id'];
            //todo change code when user change case type from criminal to civil
            $pending_court_fee=getPendingCourtFee();
            if($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_CAVEAT) {
                $breadcrumb_to_update = CAVEAT_BREAD_COURT_FEE;
            } else {
                $breadcrumb_to_update = NEW_CASE_COURT_FEE;
            }
            if($pending_court_fee>0) {
                $update_courtfee_breadcrumb_status = $this->Payment_model->remove_breadcrumb($registration_id, $breadcrumb_to_update);
            }
            else
            {
                $update_courtfee_breadcrumb_status = $this->Payment_model->update_breadcrumbs($registration_id, $breadcrumb_to_update);
            }
            $data['uploaded_pages_count'] = $this->Court_Fee_model->get_uploaded_pages_count($registration_id);
            $data['payment_details'] = $this->Court_Fee_model->get_payment_details($registration_id);
            $data['court_fee_bifurcation']=$this->Common_model->get_court_fee_bifurcation($registration_id);
            $data['court_fee_list1']=$court_fee_calculation_param1=$this->Common_model->get_subject_category_casetype_court_fee($registration_id);
            $data['court_fee_list2']=$this->Common_model->get_ia_or_misc_doc_court_fee(null,12,0);
            $data['court_fee_list3']=$this->Common_model->get_ia_or_misc_doc_court_fee($registration_id,null,null);
            $data['court_fee_list4']=$this->Common_model->get_ia_or_misc_doc_court_fee(null,11,0);

            $court_fee_part1=calculate_court_fee(null,1,null,'O');
            $court_fee_part2=calculate_court_fee(null,2,null,null);

       //echo $payment_extra_fee;

            //echo $court_fee_part2;
            $total_court_fee=(int)$court_fee_part1+(int)$court_fee_part2;
            $data['court_fee']=$total_court_fee;
        }

                //echo '<pre>';print_r($_SESSION['estab_details']); echo '</pre>';exit();
                //echo '<pre>';print_r($data['court_fee']); echo '</pre>';//exit();
                $this->load->view('templates/header');
                $this->load->view('newcaseQF/new_case_view', $data);
                $this->load->view('templates/footer');
           /* } else {
                $updateData="";
                foreach ($index_pdf_details as $val)
                {
                    $updateData .= $val['doc_title'] . " , ";
                }

                $updateData=rtrim($updateData, " , ");
                //  echo htmlentities("Index of file " . $val['doc_title'] . ' not completed ', ENT_QUOTES);
                echo "<script>alert('$updateData Pdf file index is not complete');
window.location.href='" . base_url() . "documentIndex';</script>";

            }
        }*/
    }


    public function add_court_fee_details() {

        //echo "<pre>";       print_r($_SESSION);die;


        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_DEPARTMENT);

        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            $_SESSION['MSG'] = 'Unauthorised Access !';
            redirect('login');
            exit(0);
        }

        $stages_array = array('', Draft_Stage, Initial_Defected_Stage, I_B_Defected_Stage);
        if (!in_array($_SESSION['efiling_details']['stage_id'], $stages_array)) {
            $_SESSION['MSG'] = 'Invalid Stage.';
            redirect('newcase/view');
            exit(0);
        }

        $this->form_validation->set_rules('print_fee_details', 'Printing Details', 'required|trim|validate_encrypted_value');
        $this->form_validation->set_rules('usr_court_fee', 'Court Fee', 'required|trim|min_length[1]|max_length[5]|is_natural');
        $this->form_validation->set_rules('user_declared_extra_fee', 'want to more pay Court Fee', 'required|trim|min_length[1]|max_length[5]|is_natural');
        $this->form_validation->set_error_delimiters('<br/>', '');
        if (!$this->form_validation->run()) {

            $_SESSION['MSG'] = form_error('print_fee_details') . form_error('usr_court_fee');
            redirect('newcase/courtFee');
            exit(0);
        }

         $registration_id = $_SESSION['efiling_details']['registration_id'];


        $user_declared_extra_fee = escape_data($_POST['user_declared_extra_fee']);
        $print_fee_details = escape_data($_POST['print_fee_details']);
        $print_fee_details = explode('$$', url_decryption($print_fee_details));
        
        if (count($print_fee_details) != 5) {
            $_SESSION['MSG'] = 'Printing Fee details tempered';
            redirect('newcase/courtFee');
            exit(0);
        }
        
        $order_no = rand(1001, 9999) . date("yhmids");
        $order_date = date('Y-m-d H:i:s');

        $court_fee_part1=calculate_court_fee(null,1,null,'O');
        $court_fee_part2=calculate_court_fee(null,2,null,null);

        $total_court_fee=(int)$court_fee_part1+(int)$court_fee_part2;

        $already_paid_payment = $this->Common_model->get_already_paid_court_fee($registration_id);

        if(!empty($already_paid_payment[0]['court_fee_already_paid']))
            $total_court_fee=$total_court_fee-(int)$already_paid_payment[0]['court_fee_already_paid'];


        /*$data_to_save = array(
            'registration_id' => $registration_id,            
            'entry_for_type_id' => $_SESSION['efiling_details']['efiling_for_type_id'],
            'entry_for_id' => $_SESSION['efiling_details']['efiling_for_id'],
            'uploaded_pages' => $print_fee_details[0],
            'per_page_charges' => $_SESSION['estab_details']['printing_cost'],
            'no_of_copies' => 4,
            'user_declared_court_fee' => escape_data($_POST['usr_court_fee']),
            'printing_total' => escape_data($print_fee_details[1]),
            'user_declared_total_amt' => escape_data($_POST['usr_court_fee']) + $print_fee_details[1],
            'received_amt' => escape_data($_POST['usr_court_fee']) + $print_fee_details[1],
            'order_no' => $order_no,
            'order_date' => $order_date,
            'payment_stage_id' => $_SESSION['efiling_details']['stage_id'],
            'payment_mode' => 'online',
            'payment_mode_name' => 'SHCIL'
        );*/
        //comment 16 march 2021 by akg
        /*$data_to_save = array(
           'registration_id' => $registration_id,
           'entry_for_type_id' => !empty($_SESSION['efiling_details']['efiling_for_type_id']) ? (int)$_SESSION['efiling_details']['efiling_for_type_id'] : (int)$_SESSION['efiling_details']['ref_m_efiled_type_id'],
           'entry_for_id' => !empty($_SESSION['efiling_details']['efiling_for_id']) ? (int)$_SESSION['efiling_details']['efiling_for_id'] : 1,
           'uploaded_pages' => $print_fee_details[0],
           'per_page_charges' => $_SESSION['estab_details']['printing_cost'],
           'no_of_copies' => 4,
            'user_declared_extra_fee' => escape_data($user_declared_extra_fee),
           'user_declared_court_fee' => escape_data($total_court_fee),
           'printing_total' => escape_data($print_fee_details[1]),
           'user_declared_total_amt' => escape_data($total_court_fee) + $print_fee_details[1] + $user_declared_extra_fee,
           'received_amt' => escape_data($total_court_fee) + $print_fee_details[1] + $user_declared_extra_fee,
           'order_no' => $order_no,
           'order_date' => $order_date,
           'payment_stage_id' => $_SESSION['efiling_details']['stage_id'],
           'payment_mode' => 'online',
           'payment_mode_name' => 'SHCIL'
       );*/

        $data_to_save = array(
            'registration_id' => $registration_id,
            'entry_for_type_id' => !empty($_SESSION['efiling_details']['efiling_for_type_id']) ? (int)$_SESSION['efiling_details']['efiling_for_type_id'] : (int)$_SESSION['efiling_details']['ref_m_efiled_type_id'],
            'entry_for_id' => !empty($_SESSION['efiling_details']['efiling_for_id']) ? (int)$_SESSION['efiling_details']['efiling_for_id'] : 1,
            'uploaded_pages' => $print_fee_details[0],
            'per_page_charges' => $_SESSION['estab_details']['printing_cost'],
            'no_of_copies' => 4,
            'user_declared_extra_fee' => escape_data($user_declared_extra_fee),
            'user_declared_court_fee' => escape_data($print_fee_details[4]),
            'printing_total' => escape_data($print_fee_details[3]),
            'user_declared_total_amt' => escape_data($_POST['usr_court_fee']),
            'received_amt' => escape_data($_POST['usr_court_fee']),
            'order_no' => $order_no,
            'order_date' => $order_date,
            'payment_stage_id' => $_SESSION['efiling_details']['stage_id'],
            'payment_mode' => 'online',
            'payment_mode_name' => 'SHCIL'
        );
        $status = $this->Court_Fee_model->insert_pg_request($data_to_save);
        unset($_SESSION['pg_request_payment_details']);
        if ($status) {
            //comment 16 march 2021 by akg
           /* $_SESSION['pg_request_payment_details'] = array(
                'user_declared_court_fee' => escape_data($total_court_fee),
                'printing_total' => escape_data($print_fee_details[1]),
                'user_declared_total_amt' => escape_data($total_court_fee) + $print_fee_details[1] + $user_declared_extra_fee,
                'order_no' => $order_no,
                'order_date' => $order_date
            );*/
            $_SESSION['pg_request_payment_details'] = array(
                'user_declared_court_fee' => (escape_data($print_fee_details[4])) + (escape_data($user_declared_extra_fee)),
                'printing_total' => escape_data($print_fee_details[3]),
                'user_declared_total_amt' => escape_data($_POST['usr_court_fee']),
                'order_no' => $order_no,
                'order_date' => $order_date
            );        //echo '<pre>';print_r($_SESSION['pg_request_payment_details']); echo '</pre>'; exit();
            reset_affirmation($registration_id);
           redirect('shcilPayment/paymentRequest');
            exit(0);
        }
    }

}
