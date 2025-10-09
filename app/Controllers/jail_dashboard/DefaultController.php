<?php
namespace App\Controllers;

class DefaultController extends BaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('dashboard/Dashboard_model');

        unset($_SESSION['efiling_details']);
        //unset($_SESSION['estab_details']);
        unset($_SESSION['case_table_ids']);
        unset($_SESSION['parties_list']);
        unset($_SESSION['efiling_type']);
        unset($_SESSION['pg_request_payment_details']);
        unset($_SESSION['eVerified_mobile_otp']['LITIGENT_MOB_OTP_VERIFY']);
        $this->load->library('webservices/efiling_webservices');
    }

    public function index() {

        $users_array = array(JAIL_SUPERINTENDENT);
        $jail_admin_ids=array(2656,2657,2658,2659);

        if (in_array($_SESSION['login']['ref_m_usertype_id'], $users_array)) {

            $data['count_efiling_data'] = $this->Dashboard_model->get_efilied_nums_stage_wise_count();

            $id=$_SESSION['login']['id'];

            //Get count
            $total_pet=$this->efiling_webservices->totalPetitionsDetails($id);
            $pending_matter=$this->efiling_webservices->pendingPetitionsDetails($id);
            $disp_matter=$this->efiling_webservices->disposedPetitionsDetails($id);

            $data['total_pending_petitions']=$pending_matter['total_pending_petitions'][0]['total'];
            $data['total_disposed_petitions']=$disp_matter['total_disposed_petitions'][0]['total'];
            $data['total_filed_petitions']=$total_pet['total_petitions'][0]['total'];

            //VC Details
            //today
            $jailcode=$_SESSION['login']['userid'];
            $fromdate=date('Y-m-d');

            $todate=date('Y-m-d');

            $tomorrow = strtotime('+7 day');
            $toweekdate=date('Y-m-d', $tomorrow);

            $tem1=$this->efiling_webservices->getvcDetails($jailcode,$fromdate,$todate);

            $data['total_today_vc']=count($tem1['get_vc_details']);

            //week

            $tem2=$this->efiling_webservices->getvcDetails($jailcode,$fromdate,$toweekdate);

            $data['total_week_vc']=count($tem2['get_vc_details']);

            //Certification
            //Custody Pending
            $custody_details_p=$this->efiling_webservices->getCertificateCount($jailcode,'p','cc');
            $data['total_custody_Pen']=count($custody_details_p['get_certificate_details']);

            //Draft
            $custody_details_d=$this->efiling_webservices->getCertificateCount($jailcode,'d','cc');
            $data['total_custody_Draft']=count($custody_details_d['get_certificate_details']);

            //Completed
            $custody_details_c=$this->efiling_webservices->getCertificateCount($jailcode,'c','cc');
            $data['total_custody_com']=count($custody_details_c['get_certificate_details']);

            //Surrender
            //Pending
            $sur_details_p=$this->efiling_webservices->getCertificateCount($jailcode,'p','sc');
            $data['total_sur_Pen']=count($sur_details_p['get_certificate_details']);

            //Draft
            $sur_details_d=$this->efiling_webservices->getCertificateCount($jailcode,'d','sc');
            $data['total_sur_Draft']=count($sur_details_d['get_certificate_details']);

            //Completed
            $sur_details_c=$this->efiling_webservices->getCertificateCount($jailcode,'c','sc');
            $data['total_sur_com']=count($sur_details_c['get_certificate_details']);


            $this->load->view('templates/jail_header');
            $this->load->view('jail_dashboard/dashboard_view', $data);
            $this->load->view('templates/footer');
        }
        else if(in_array($_SESSION['login']['id'],$jail_admin_ids))
        {

        }
            else {
            redirect('login');
            exit(0);
        }
    }

    public function get_filed_petitions($val=0)
    {
        $id = $_SESSION['login']['id'];
        $data['temp_val'] = $val;

        //get specific details
        $month = 0;
        $year = 0;

        if ($this->input->post('month_id') != 0 && $this->input->post('year_id') != 0) {
            $month = $this->input->post('month_id');
            $year = $this->input->post('year_id');
        }

        //get details
        $get_AllPetitionsDetails = $this->efiling_webservices->getAllPetitionsDetails($id, $val, $month, $year);

        $data['get_All_Petitions_Details'] = $get_AllPetitionsDetails['get_all_petitions_details'];

        //var_dump($data['get_All_Petitions_Details']);exit(0);

        $this->load->view('templates/jail_header');
        $this->load->view('jail_dashboard/filed_petitions', $data);
        $this->load->view('templates/footer');
    }

    public function get_vcDetails($val=0)
    {
        //VC Details
        //today
        $jailcode = $_SESSION['login']['userid'];
        $fromdate = date('Y-m-d');
        $data['temp_val'] = $val;


        $todate = date('Y-m-d');

        $tomorrow = strtotime('+7 day');
        $toweekdate = date('Y-m-d', $tomorrow);


        if ($val == 1) {
            $vc_details = $this->efiling_webservices->getvcDetails($jailcode, $fromdate, $todate);

        } else {
            //week
            $vc_details = $this->efiling_webservices->getvcDetails($jailcode, $fromdate, $toweekdate);
        }

        $data['get_All_Petitions_Details']=$vc_details['get_vc_details'];

        $this->load->view('templates/jail_header');
        $this->load->view('jail_dashboard/VC_Today',$data);
        $this->load->view('templates/footer');
    }

    public function get_certificationDetails($type,$status)
    {
        $jailcode = $_SESSION['login']['userid'];

        $data['type'] = $type;
        $data['status'] = $status;

        //Certification
        //Custody
        if($type=="cc" && $status=='p')
        {
            $custody_details_p=$this->efiling_webservices->getCertificateDetails($jailcode,'p','cc');
            $data['total_custody_details']=$custody_details_p['get_certificate_details'];
        }
        elseif ($type=="cc" && $status=='d')
        {
            $custody_details_d=$this->efiling_webservices->getCertificateDetails($jailcode,'d','cc');
            $data['total_custody_details']=$custody_details_d['get_certificate_details'];

        }
        elseif ($type=="cc" && $status=='c')
        {
            $custody_details_c=$this->efiling_webservices->getCertificateDetails($jailcode,'c','cc');
            $data['total_custody_details']=$custody_details_c['get_certificate_details'];
        }
        elseif ($type=="sc" && $status=='p')
        {
            $sur_details_p=$this->efiling_webservices->getCertificateDetails($jailcode,'p','sc');
            $data['total_custody_details']=$sur_details_p['get_certificate_details'];
        }
        elseif ($type=="sc" && $status=='d')
        {
            $sur_details_d=$this->efiling_webservices->getCertificateDetails($jailcode,'d','sc');
            $data['total_custody_details']=$sur_details_d['get_certificate_details'];
        }
        elseif ($type=="sc" && $status=='c')
        {
            $sur_details_c=$this->efiling_webservices->getCertificateDetails($jailcode,'c','sc');
            $data['total_custody_details']=$sur_details_c['get_certificate_details'];
        }

        $this->load->view('templates/jail_header');
        $this->load->view('jail_dashboard/certification',$data);
        $this->load->view('templates/footer');
    }
}

?>
