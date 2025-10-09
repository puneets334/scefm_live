<?php

namespace App\Controllers\AdminReport;

use App\Controllers\BaseController;
use App\Libraries\webservices\Efiling_webservices;
use App\Models\AdminDashboard\EfileStageModel;

class EfileStages extends BaseController
{

    protected $EfileStage_Model;
    protected $efiling_webservices;

    public function __construct()
    {
        parent::__construct();
        if (empty(getSessionData('login'))) {
            return response()->redirect(base_url('/'));
        } else {
            is_user_status();
        }
        $this->EfileStage_Model = new EfileStageModel();
        $this->efiling_webservices = new Efiling_webservices();
    }

    public function index()
    {
        if (empty(getSessionData('login'))) {
            return response()->redirect(base_url('/'));
        } else {
            is_user_status();
        }
        $allowed_users_array = array(USER_ADMIN);
        if (empty(getSessionData('login')) && !in_array(getSessiondata('login')['ref_m_usertype_id'], $allowed_users_array)) {
            return redirect()->to(base_url('adminDashboard'));
            exit(0);
        }
        return $this->render('adminReport.efile_stages_search');
    }

    public function search()
    {
        if (empty(getSessionData('login'))) {
            return redirect()->to(base_url('adminDashboard'));
            exit(0);
        }
        $efileno  = str_replace('-', '', $_GET['efileno']);
        if (empty($efileno)) {
            $this->session->setFlashdata('message', "<div class='text-danger'>Please enter E-file No.</div>");
            return redirect()->to(base_url('adminReport/EfileStages'));
            exit;
        }
        $case_details = $this->EfileStage_Model->getEfileListWithStage($efileno);
        $stage_list = $this->EfileStage_Model->getStageList();
        $data = [
            'efileno'      => $efileno,
            'case_details' => $case_details,
            'stage_list'   => $stage_list
        ];
        return $this->render('adminReport.efile_stages_search', $data);
    }

    public function updateStage()
    {
        $efile_no = $_POST['efileno'];
        $remarks  = $_POST['remarks'];
        $stage_id = $_POST['stage_id'];
        $errors = [];
        if (empty($stage_id) && empty($remarks)) {
            $errors = [
                'status' => false,
                'message' => 'Please select stage and add remarks.',
                'token'  => csrf_token()
            ];
        } elseif (is_null($stage_id) || !isset($stage_id) || empty($stage_id)) {
            $errors = [
                'status' => false,
                'message' => 'Please select stage.',
                'token' => csrf_token()
            ];
        } elseif (is_null($remarks) || !isset($remarks) || empty($remarks)) {
            $errors = [
                'status' => false,
                'message' => 'Please add remarks.',
                'token' => csrf_token()
            ];
        } elseif (!in_array($stage_id, [1, 12])) {
            $errors = [
                'status' => false,
                'message' => 'Only Draft or Case-E-Filed stages are allowed and you are choosing wrong stage.',
                'token' => csrf_token()
            ];
        }
        if (count($errors)) {
            $response = service('response');
            $response->setContentType('application/json');
            $response->setBody(json_encode($response));
            $response->send();
            $errors = [];
            exit;
        }
        $efile_num_status_data = $this->EfileStage_Model->getEfileStageData($efile_no);
        if ((in_array($efile_num_status_data->stage_id, [9, 10, 11]) && $stage_id == '12') || (in_array($efile_num_status_data->stage_id, [8]) && $stage_id == '1')) {
            $efile_num_status_data->remarks        = $remarks;
            $efile_num_status_data->datetime       = date('Y-m-d H:i:s');
            $efile_num_status_data->remote_addr    = $_SERVER['REMOTE_ADDR'];
            $efile_num_status_data->loginid        = $_SESSION['login']['id'];
            $efile_num_status_data->stage_id       = $stage_id;
            $diaryno = $this->efiling_webservices->getCaseDiaryNo($efile_no);
            if ($stage_id == '12' && $diaryno == 'null') {
                $errors = [
                    'status' => false,
                    'message' => "You can't change stage because diary no is not generated yet.",
                    'token'  => csrf_token()
                ];
            }
            if ($stage_id == '1' && $diaryno != 'null') {
                $errors = [
                    'status' => false,
                    'message' => "You can't change stage because diary no is generated.",
                    'token'  => csrf_token()
                ];
            }
            $efile_obj_cases = $this->EfileStage_Model->getObjections($efile_num_status_data->registration_id);
            if ($efile_obj_cases == false) {
                $errors = [
                    'status' => false,
                    'message' => "You can't change stage because no defects on SCeFM.",
                    'token'  => csrf_token()
                ];
            }
            $obj_cases = $this->efiling_webservices->getObjectionsByDiaryNo($diaryno);
            if (!empty($obj_cases)) {
                $errors = [
                    'status' => false,
                    'message' => "You can't change stage because defects found on icmis.",
                    'token'  => csrf_token()
                ];
            }
            if (count($errors)) {
                $response = service('response');
                $response->setContentType('application/json');
                $response->setBody(json_encode($errors));
                $response->send();
                exit;
            }
            $result = $this->EfileStage_Model->updateStageData($efile_num_status_data, $obj_cases, $efile_obj_cases);
            if ($result) {
                $response = [
                    'status' => true,
                    'message' => 'Stage updated successfully.',
                    'efileno' => $efile_no
                ];
            } else {
                $response = [
                    'status' => false,
                    'message' => 'Unable to update stage, please contact to Computer Cell.',
                    'token'  => csrf_token()
                ];
            }
            $response = service('response');
            $response->setContentType('application/json');
            $response->setBody(json_encode($response));
            $response->send();
            exit;
        } else {
            $errors = [
                'status' => false,
                'message' => 'You are doing something wrong, please contact to Computer Cell.',
                'token' => csrf_token()
            ];
        }
        if (count($errors)) {
            $response = service('response');
            $response->setContentType('application/json');
            $response->setBody(json_encode($errors));
            $response->send();
            exit;
        }
    }
}
