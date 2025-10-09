<?php

namespace App\Controllers\VacationSupp;

use App\Controllers\BaseController;
use App\Models\VacationSupp\VacationAdvanceSuppModel;

class Advance extends BaseController
{

    protected $Vacation_advance_supp_model;
    protected $request;
    protected $db;

    public function __construct()
    {
        parent::__construct();
        if (empty(getSessionData('login'))) {
            return response()->redirect(base_url('/'));
        } else {
            is_user_status();
        }
        $this->Vacation_advance_supp_model = new VacationAdvanceSuppModel();
        $this->request = \Config\Services::request();
        $this->db = \Config\Database::connect();
        if (getSessionData('login')['ref_m_usertype_id'] != USER_ADVOCATE) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        is_vacation_supp_advance_list_duration();
        if (!is_vacation_supp_advance_list_duration()) {
            // echo '<p style="background-color: lightgray; padding: 10px; border: 1px solid black; border-radius: 10px; color: red;">Advance Summer Vacation List not authorized access</p>';
            exit();
        }
    }

    public function index()
    {
        if (empty(getSessionData('login'))) {
            return response()->redirect(base_url('/'));
        } else {
            is_user_status();
        }
        $mainhead = $this->request->getVar('mainhead') ?? 'F';
        $tab = 'vacation_supp/advance/alllist?mainhead=' . $mainhead;
        return $this->render('vacation_supp.index', compact('tab', 'mainhead'));
    }

    public function declinelist()
    {
        if (empty(getSessionData('login'))) {
            return response()->redirect(base_url('/'));
        } else {
            is_user_status();
        }
        $mainhead = $this->request->getVar('mainhead') ?? 'F';
        $tab = 'vacation_supp/advance/declinelist?mainhead=' . $mainhead;
        return $this->render('vacation_supp.index', compact('tab', 'mainhead'));
    }

    public function alllist()
    {
        if (empty(getSessionData('login'))) {
            return response()->redirect(base_url('/'));
        } else {
            is_user_status();
        }
        $aor_code = getSessionData('login')['aor_code'];
        $mainhead = $this->request->getVar('mainhead');
        $data = null;
        $data['vacation_advance_list'] = $this->Vacation_advance_supp_model->get_vacation_advance_list($aor_code, $mainhead);
        $data['matters_declined_by_counter'] = $this->Vacation_advance_supp_model->get_matters_declined_by_counter_list($aor_code, $mainhead);
        $data['mainhead'] = $mainhead;
        return $this->render('vacation_supp.getVacationAdvanceListAOR', $data);
    }

    public function get_declinelist()
    {
        if (empty(getSessionData('login'))) {
            return response()->redirect(base_url('/'));
        } else {
            is_user_status();
        }
        $aor_code = getSessionData('login')['aor_code'];
        $mainhead = $this->request->getVar('mainhead');
        $data = null;
        $data['vacation_advance_list'] = $this->Vacation_advance_supp_model->get_vacation_advance_list($aor_code, $mainhead, 'D');
        $data['matters_declined_by_counter'] = $this->Vacation_advance_supp_model->get_matters_declined_by_counter_list($aor_code, $mainhead);
        $data['mainhead'] = $mainhead;
        return $this->render('vacation_supp.getVacationAdvanceListAOR_decline', $data);
    }

    public function declineVacationListCasesAOR()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        if (empty(getSessionData('login'))) {
            return response()->redirect(base_url('/'));
        } else {
            is_user_status();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $aor_code = getSessionData('login')['aor_code'];
            $userID = getSessionData('login')['id'];
            $dairyNos = $_POST['diary_no'];
            $userIP = getClientIP();
            $mainhead = $this->request->getVar('mainhead');
            if (empty($dairyNos)) {
                echo '1@@@' . htmlentities("Please select atleast one Case which need to be Decline", ENT_QUOTES);
                exit(0);
            }
            $vacation_advance_list_advocate = $this->Vacation_advance_supp_model->get_vacation_advance_list_advocate($dairyNos, $aor_code, $mainhead);
            if (!empty($vacation_advance_list_advocate)) {
                $this->db->transStart();
                $is_insert_vacation_advance_list_advocate_log = $this->Vacation_advance_supp_model->insert_vacation_advance_list_advocate_log('icmis.vacation_advance_list_advocate_log', $vacation_advance_list_advocate);
                if ($is_insert_vacation_advance_list_advocate_log) {
                    $curr_dt_time = date('Y-m-d H:i:s');
                    $data = array(
                        'updated_on' => $curr_dt_time,
                        'updated_by' => $userID,
                        'is_deleted' => 't',
                        'updated_from_ip' => getClientIP()
                    );
                    $builder = $this->db->table('icmis.vacation_advance_list_advocate_supp');
                    $builder->whereIn('diary_no', $dairyNos);
                    $builder->WHERE('is_deleted', 'f');
                    $builder->WHERE('aor_code', $aor_code);
                    $builder->WHERE('mainhead', $mainhead);
                    $builder->SET($data);
                    if ($builder->UPDATE()) {
                        echo "2@@@Selected Case Successfully Listed";
                    } else {
                        echo "1@@@Selected Case Fail Please check try again";
                    }
                }
                $this->db->transComplete();
            }
        } else {
            echo '1@@@' . htmlentities("Accepted Only Request Method Post", ENT_QUOTES);
            exit(0);
        }
    }

    public function restoreVacationAdvanceListAOR()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        if (empty(getSessionData('login'))) {
            return response()->redirect(base_url('/'));
        } else {
            is_user_status();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $aor_code = getSessionData('login')['aor_code'];
            $userID = getSessionData('login')['id'];
            $diary_no = $_POST['diary_no'];
            $mainhead = $this->request->getVar('mainhead');
            if (empty($diary_no)) {
                echo '1@@@' . htmlentities("Please select atleast one Case which need to be Decline", ENT_QUOTES);
                exit(0);
            }
            $userIP = getClientIP();
            $vacation_advance_list_advocate = $this->Vacation_advance_supp_model->get_vacation_advance_list_advocate_restore($diary_no, $aor_code, $mainhead);
            if (!empty($vacation_advance_list_advocate)) {
                $this->db->transStart();
                $is_insert_vacation_advance_list_advocate_log = $this->Vacation_advance_supp_model->insert_vacation_advance_list_advocate_log('icmis.vacation_advance_list_advocate_log', $vacation_advance_list_advocate);
                if ($is_insert_vacation_advance_list_advocate_log) {
                    $curr_dt_time = date('Y-m-d H:i:s');
                    $data = array(
                        'updated_on' => $curr_dt_time,
                        'updated_by' => $userID,
                        'is_deleted' => 'f',
                        'updated_from_ip' => getClientIP()
                    );
                    $builder = $this->db->table('icmis.vacation_advance_list_advocate_supp');
                    $builder->WHERE('diary_no', $diary_no);
                    $builder->WHERE('is_deleted', 't');
                    $builder->WHERE('aor_code', $aor_code);
                    $builder->WHERE('mainhead', $mainhead);
                    $builder->SET($data);
                    if ($builder->UPDATE()) {
                        echo "2@@@Selected Case Successfully Listed";
                    } else {
                        echo "1@@@Selected Case Fail Please check try again";
                    }
                }
                $this->db->transComplete();
            }
        } else {
            echo '1@@@' . htmlentities("Accepted Only Request Method Post", ENT_QUOTES);
            exit(0);
        }
    }

}