<?php
namespace App\Controllers;
class DefaultController extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->library('slice');
        $this->load->library('webservices/efiling_webservices');
        $this->load->model('common/Common_model');
        $this->load->model('deficitCourtFee/Deficit_court_fee_model');

    }

    function check_login() {

        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            echo '1@@@Invalid attempt.';
            exit(0);
        }
    }

    public function index() {
        //echo "jai sri ram";
        //print_r($_SESSION['login']);exit();


        $this->check_login();
        /*unset($_SESSION['efiling_details']);
        unset($_SESSION['estab_details']);*/

        $def_Data = $this->efiling_webservices->get_deficit_court_feeData($_SESSION['login']['adv_sci_bar_id']);
        $data['deficitData'] = $def_Data['deficit_case'];

        $AlreadyPaid_CourtFee = $this->Deficit_court_fee_model->get_already_paid_courtFee();

       // $this->slice->view('deficitCourtFee.deficit_court_fee',$data);
        $this->slice->view('deficitCourtFee.deficit_court_fee',compact('def_Data','AlreadyPaid_CourtFee'));


    }


    public function record_data_deficit_insrt() {
        /*echo "jai bholenath";
        exit();*/

        $this->check_login();
        //echo "rounakMishra";
        $id=$_POST['id'];
        $deficit_amt=$_POST['amt_pay'];
        $DiaryNo=$_POST['DiaryNo'];


        //$Diary_No_gen=substr($DiaryNo,0,-4);
        $Diary_No_gen1=explode(" / ",$DiaryNo);


        $Diary_No_gen=$Diary_No_gen1[0];

        /*echo $DiaryNo;
        echo "<br>";
        print_r($Diary_No_gen1);
        echo "<br>";
        echo $Diary_No_gen;
        exit();*/

        //$already_paid_confirm = $this->Deficit_court_fee_model->get_already_paid_confirmation($Diary_No_gen);
        $already_paid_confirm = $this->Deficit_court_fee_model->get_already_paid_confirmation($Diary_No_gen);

        /*print_r($already_paid_confirm);
        exit();*/

        if($already_paid_confirm){
            echo json_encode($already_paid_confirm);

        }else{
            echo 1;
        }
        //exit();

    }//End of function record_data_deficit_insrt()..


    public function record_data_deficit_insrt_paid() {

        $this->check_login();
        $id=$_POST['id'];
        $deficit_amt=$_POST['amt_pay'];
        $DiaryNo=$_POST['DiaryNo'];
        $curr_dt_time = date('Y-m-d H:i:s');
        //$cause_title=$_POST['PetName'] . ' VS. ' . $_POST['ResName'];
        $cause_title=$_POST['cause_tittle'] ;


        //xxxxxxxxxxxxxxxx tbl_sci Casses start xxxxxxxxxxxxxxxxxx
        $sci_cases_details = array(
            'reg_no_display' => '',
            'cause_title' => $cause_title,
            'c_status' => 'P',
            'p_no' => '',
            'r_no' => '',
            'p_sr_no' => '',
            'p_sr_no_show' => '',
            'p_partyname' => '',
            'r_sr_no' => '',
            'r_sr_no_show' => '',
            'r_partyname' => ''
        );
        $diary_saved = FALSE;
        /*$DiaryYr_Chk=substr($DiaryNo,-4);
        $DiaryNo_Chk=substr($DiaryNo,0,-4);*/


        $Diary_No_gen2=explode(" / ",$DiaryNo);

        $DiaryNo_Chk=$Diary_No_gen2[0];
        $DiaryYr_Chk=$Diary_No_gen2[1];


        $diary_already_exists = $this->Deficit_court_fee_model->get_diary_details($DiaryNo_Chk, $DiaryYr_Chk);
       /* print_r($diary_already_exists);
        exit();*/
        if (isset($diary_already_exists) && !empty($diary_already_exists)) {
            //echo "1"; exit();
            $_SESSION['tbl_sci_case_id']=$diary_already_exists[0]->id;
            if ($diary_already_exists[0]->reg_no_display != $reg_no_display || $diary_already_exists[0]->cause_title != $cause_title || $diary_already_exists[0]->c_status != $c_status || $diary_already_exists[0]->p_no != $pno || $diary_already_exists[0]->r_no != $rno || $diary_already_exists[0]->p_sr_no != $pet_sr_no || $diary_already_exists[0]->p_sr_no_show != $pet_sr_no_show || $diary_already_exists[0]->p_partyname != $pet_party_name || $diary_already_exists[0]->r_sr_no != $res_sr_no || $diary_already_exists[0]->r_sr_no_show != $res_sr_no_show || $diary_already_exists[0]->r_partyname != $res_party_name) {

                $sci_cases_details1 = array(
                    'updated_on' => $curr_dt_time,
                    'updated_by' => $this->session->userdata['login']['id'],
                    'updated_by_ip' => getClientIP()
                );
                $sci_cases_details = array_merge($sci_cases_details1, $sci_cases_details);
               // echo "<pre>";
                /*print_r($sci_cases_details);
                die;*/
                $diary_saved = $this->Deficit_court_fee_model->add_update_sci_cases_details($sci_cases_details, array('diary_no' => $DiaryNo_Chk, 'diary_year' => $DiaryYr_Chk));
            } else {
                $diary_saved = TRUE;
            }
        } else { //echo "2"; exit();
            $sci_cases_details1 = array(
                'diary_no' => $DiaryNo_Chk,
                'diary_year' => $DiaryYr_Chk,
                'created_on' => $curr_dt_time,
                'created_by' => $this->session->userdata['login']['id'],
                'created_by_ip' => getClientIP()
            );

            $sci_cases_details = array_merge($sci_cases_details1, $sci_cases_details);

            $diary_saved = $this->Deficit_court_fee_model->add_update_sci_cases_details($sci_cases_details, NULL);
        }



        //XXXXXXXXXXXXXXXXXX tbl_sci Casses end  XXXXXXXXXXXXXXXXXX

        $estab_details = $this->Deficit_court_fee_model->get_establishment_details();
        //print_r($_SESSION);die;
        if ($estab_details) {

            //SET $_SESSION['efiling_details']
            //$efiling_num_details = $this->Common_model->get_efiling_num_basic_Details($registration_id);
            $order_no = rand(1001, 9999) . date("yhmids");
            $order_date = date('Y-m-d H:i:s');
            $deficit_rslt=$this->Deficit_court_fee_model->get_deficit_num_insrt($id,$deficit_amt,$curr_dt_time,$order_no,$order_date,$DiaryNo);
        } else {
            redirect('login');
            exit(0);
        }
        //$deficit_rslt=$this->Deficit_court_fee_model->get_deficit_num_insrt($id,$deficit_amt);



    }//End of function record_data_deficit_insrt_paid()..


    public function get_view_paid_deficitCourt($id = NULL){
        //$this->check_login();
        if ($id) {

            $id = url_decryption($id);
            /*echo $id;
            exit();*/
            $InputArrray = explode('#', $id);   //0=>registration_id,1=>ref_m_efiled_type_id,2=>type,3=>efiling_no
            $registration_id = $InputArrray[0];

            if ($registration_id!='' || $InputArrray[2] == DEFICIT_COURT_FEE_PAID) {
               //SET $_SESSION['efiling_details']
                $efiling_num_details = $this->Common_model->get_efiling_num_basic_Details($registration_id);
                $data['case_details'] = $this->Deficit_court_fee_model->get_case_details_deficit_court($registration_id);
                $data['payment_details'] = $this->Deficit_court_fee_model->get_payment_details_deficitCourt($registration_id);
                //print_r($data['payment_details']);exit();
                //$result = array('registration_id' => $registration_id, 'efiling_no' => $efiling_num);
                $this->session->set_userdata('deficitPay_details', $InputArrray);

                $this->load->view('templates/header');
                //exit();
                $this->load->view('deficitCourtFee/deficit_court_preview', $data);
                $this->load->view('templates/footer');
            } else {
                redirect('login');
                exit(0);
            }

        } else {
            redirect('login');
            exit(0);
        }

    }//End of function get_view_paid_deficitCourt()..





    /*public function record_data_deficit_insrt() {

        $this->check_login();
        //echo "rounakMishra";
        $id=$_POST['id'];
        $deficit_amt=$_POST['amt_pay'];
        $DiaryNo=$_POST['DiaryNo'];
        $Diary_No_gen=substr($DiaryNo,0,-4);

        $already_paid_confirm = $this->Deficit_court_fee_model->get_already_paid_confirmation($Diary_No_gen);

        if($already_paid_confirm){

        }
        exit();


        $curr_dt_time = date('Y-m-d H:i:s');


        $estab_details = $this->Deficit_court_fee_model->get_establishment_details();
        //print_r($_SESSION);die;
        if ($estab_details) {

            //SET $_SESSION['efiling_details']
            //$efiling_num_details = $this->Common_model->get_efiling_num_basic_Details($registration_id);
            $order_no = rand(1001, 9999) . date("yhmids");
            $order_date = date('Y-m-d H:i:s');
            $deficit_rslt=$this->Deficit_court_fee_model->get_deficit_num_insrt($id,$deficit_amt,$curr_dt_time,$order_no,$order_date,$DiaryNo);
        } else {
            redirect('login');
            exit(0);
        }
        //$deficit_rslt=$this->Deficit_court_fee_model->get_deficit_num_insrt($id,$deficit_amt);



    }//End of function record_data_deficit_insrt()..*/



}//END OF CLASS DefaultController..



