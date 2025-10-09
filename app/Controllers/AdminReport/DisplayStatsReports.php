<?php

namespace App\Controllers\AdminReport;

use App\Controllers\BaseController;
use App\Models\AdminReport\AdminReportsModel;

class DisplayStatsReports extends BaseController {

    protected $AdminReportsModel;

    public function __construct() {
        parent:: __construct();
        $this->AdminReportsModel = new AdminReportsModel();
    }

    public function index() {
        $this->efileStats();
    }

    // Changes done on 08022024 by KBP
    public function efileStats() {
        $data = array();
        $data['request_method'] = 'CRON';
        $file_type_id = unserialize(FILE_TYPE_ID);
        $params['file_type_id'] = $file_type_id;
        $launch_date= date('Y-m-d',strtotime('01-05-2023'));
        $postData['to_date'] = date('Y-m-d', strtotime("-1 days"));
        $postData['from_date'] =$launch_date; // date('Y-m-d H:i:s', strtotime("-1 days"));
        $postData['current_date'] = date('Y-m-d');
        $from_date = NULL;
        $to_date = NULL;
        $output = array();
        $validatinError = true;
        if(isset($postData['from_date']) && !empty($postData['from_date'])){
            $from_date = trim($postData['from_date']);
        } else{
            $data['status'] = 'error';
            $data['id'] = 'from_date';
            $data['msg'] = 'Please select from date.';
            $validatinError = false;
        }
        if(isset($postData['to_date']) && !empty($postData['to_date'])){
            $to_date = trim($postData['to_date']);
        } else{
            $data['status'] = 'error';
            $data['id'] = 'to_date';
            $data['msg'] = 'Please select to date.';
            $validatinError = false;
        }
        $data['heading_1st']='<b>Cases/Documents E-Filed between: </b>'.date("d-m-Y", strtotime($postData['from_date'])) . ' To '.date("d-m-Y", strtotime($postData['to_date']));
        $data['heading_2nd']='<b>Cases/Documents E-Filed today (as on </b>'.date("d-m-Y h:i:s A").')';
        if(isset($validatinError) && !empty($validatinError)){
            $ref_m_usertype_id = 20;
            if(isset($ref_m_usertype_id) && !empty($ref_m_usertype_id)) {
                $type = "file_allocated";
                $params['type'] = $type;
                $params['user_type'] = $ref_m_usertype_id;
                $params['stage_id'] = New_Filing_Stage;
                $params['from_date'] = $from_date;
                $params['to_date'] = $to_date;
                $file_allocated = $this->AdminReportsModel->getCountDataNew($params);
                $data['file_allocated'] = !empty($file_allocated[0]) ? $file_allocated[0] : NULL;
                if (!empty($postData['current_date'])) {
                    $params['to_date'] = ($postData['current_date'])?$postData['current_date']:'';
                    $params['from_date'] = ($postData['current_date'])?$postData['current_date']:'';
                    $file_allocated_7days = $this->AdminReportsModel->getCountDataNew($params);
                    $data['file_allocated_current_date'] = !empty($file_allocated_7days[0]) ? $file_allocated_7days[0] : NULL;
                }
            } else{
                $data['status'] = 'success';
                $data['id'] = 'result';
                $data['msg'] = 'Something went wrong,Please try again later!';
            }
        }
        $data['to_email']=array('sca.aktripathi@sci.nic.in','ppavan.sc@nic.in','reg.computercell@sci.nic.in','adreg.computercell@sci.nic.in','sca.kbpujari@sci.nic.in','sca.mohitjain@sci.nic.in','sg.office@sci.nic.in','office.regj2@sci.nic.in','reg.pavaneshd@sci.nic.in','ashish.js@nic.in','office.regj1@sci.nic.in','ca.shahnawaj@sci.nic.in','jca.shilpamalhotra@sci.nic.in');
        $data['subject']='SC-eFM Statistics';
        $data['message']='Statistical Information';
        $this->render('templates.email.reports_view_only', $data);
    }

}