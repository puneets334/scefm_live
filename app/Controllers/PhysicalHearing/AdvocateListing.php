<?php
// defined('BASEPATH') OR exit('No direct script access allowed');

namespace App\Controllers\PhysicalHearing;

use App\Controllers\BaseController;
use App\Models\PhysicalHearing\AdvListingModel;

class AdvocateListing extends BaseController
{
    protected $Adv_listing_model;

    public function __construct()
    {
        parent::__construct();
		// if (!isset($_SESSION['loginData']) && empty($_SESSION['loginData'])) {
		// 	redirect('auth');
		// }else{
		// 	is_user_status();
		// }
        // $this->load->helper('common');
        // $this->load->helper('encryptdecrypt');
        // $this->load->helper('myarray');
        // $this->load->helper('curl');
        // $this->load->helper('url');
        // $this->load->helper('form');
        // $this->load->database('physical_hearing');
        // $this->load->model('Adv_listing_model');
        $this->Adv_listing_model = new AdvListingModel();
        if(empty(getSessionData('login'))){
            return response()->redirect(base_url('/'));
            exit(0); 
        }else{
            is_user_status();
        }
    }

    /*public function index()
    {
        $this->load->view('cms/header');
        $this->load->view('welcome_message');
        $this->load->view('cms/footer');
    }*/

    public function advocate_rpt()
    {
        $daily_list= $this->Adv_listing_model->cases_listed_in_daily_list();
        $advocate_cases = $this->Adv_listing_model->adv_data();
        // pr($advocate_cases);
        foreach ($advocate_cases as &$advocate_case)
        {
            $key = array_search ($advocate_case['diary_no'], array_column($daily_list, 'diary_no'));
            if(is_numeric($key))
            {
                $advocate_case['court_number']=$advocate_case['court_no'];
                if(!empty($advocate_case['next_dt']))
                    $advocate_case['item_number'] = $daily_list[$key]['brd_slno'];
                else
                    $advocate_case['item_number'] = NULL;
                /* if(!empty($advocate_case['next_dt']))
                    $advocate_case['next_dt']=$advocate_case['next_dt'];*/
                $advocate_case['next_dt']=isset($advocate_case['court_number'])?(($advocate_case['next_dt'] == date('Y-m-d', strtotime($daily_list[$key]['next_dt'])))?$advocate_case['next_dt']:NULL):NULL;
                //$advocate_case['next_dt'] = date('d-m-Y', strtotime($daily_list[$key]['next_dt']));
            }
        }
        $data['list'] = $advocate_cases;
        // $this->load->view('physical_hearing/menu');
        $this->render('physical_hearing.advocate_rpt',$data);
    }

    public function advocate_rpt_vc($listing_date=null)
    {
        /*$nextTuesday = strtotime('next tuesday');  // next tuesday
        $listing_date=date('Y-m-d',$nextTuesday);*/
        if(empty($listing_date))
            $listing_date=getNextMiscDayOfHearing();
        $daily_list= $this->Adv_listing_model->cases_listed_in_daily_list($listing_date);
        $advocate_cases = $this->Adv_listing_model->adv_data_vc($listing_date);
        $data['date_of_hearing']=date('d-m-Y', strtotime($listing_date));
        foreach ($advocate_cases as &$advocate_case)
        {
            $cases_registration_nos=$this->Adv_listing_model->case_details($advocate_case['consent_for_diary_nos']);
            $key = array_search ($advocate_case['diary_no'], array_column($daily_list, 'diary_no'));
            if(is_numeric($key)){
                $advocate_case['court_number']=$advocate_case['court_no'];
                if(!empty($advocate_case['next_dt']))
                    $advocate_case['item_number'] = $daily_list[$key]['brd_slno'];
                else
                    $advocate_case['item_number'] = NULL;
                $advocate_case['consent_for_cases']=$cases_registration_nos[0]->consent_for_cases;
                $advocate_case['next_dt']=isset($advocate_case['court_number'])?(($advocate_case['next_dt'] == date('Y-m-d', strtotime($daily_list[$key]['next_dt'])))?$advocate_case['next_dt']:NULL):NULL;
            }
        }
        $data['list'] = $advocate_cases;
        if(!empty($data['list'])) {
            $data['case_count'] = count($data['list']);
        }
        $this->render('physical_hearing.advocate_rpt_vc_ajax',$data);
    }

    public function advocate_rpt_srch() {
        $listing_date=!empty($_POST['srch_date_data'])?date('Y-m-d', strtotime($_POST['srch_date_data'])):null;
        $data['list']='';
        if(!empty($listing_date)) {
            $this->advocate_rpt_vc($listing_date);
        } else {
            // $this->load->view('physical_hearing/menu');
            $this->render('physical_hearing.advocate_rpt_vc', $data);
        }
    }

}
// end of class Advocate_listing...