<?php

namespace App\Controllers\Vacation;

use App\Controllers\BaseController;
use App\Models\Vacation\VacationAdvanceModel;

class Advance extends BaseController
{

    protected $Vacation_advance_model;
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
        $this->Vacation_advance_model = new VacationAdvanceModel();
        $this->request = \Config\Services::request();
        $this->db = \Config\Database::connect();
        is_vacation_advance_list_duration();
        if (!is_vacation_advance_list_duration()) {
            echo 'Advance Summer Vacation List not authorized access';
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
        $data['mainhead'] = $this->request->getVar('mainhead') ?? 'F';
        $data['tab'] = 'vacation/advance/alllist?mainhead=' . $data['mainhead'];
        return $this->render('vacation.index', $data);
    }

    public function declinelist()
    {
        if (empty(getSessionData('login'))) {
            return response()->redirect(base_url('/'));
        } else {
            is_user_status();
        }
        $data['mainhead'] = $this->request->getVar('mainhead') ?? 'F';
        $data['tab'] = 'vacation/advance/declinelist?mainhead=' . $data['mainhead'];
        return $this->render('vacation.index', $data);
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
        $data['vacation_advance_list'] = $this->Vacation_advance_model->get_vacation_advance_list($aor_code, $mainhead);
        $data['matters_declined_by_counter'] = $this->Vacation_advance_model->get_matters_declined_by_counter_list($aor_code, $mainhead);
        $data['mainhead'] = $mainhead;
        return $this->render('vacation.getVacationAdvanceListAOR', $data);
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
        $data['vacation_advance_list'] = $this->Vacation_advance_model->get_vacation_advance_list($aor_code, $mainhead, 'D');
        $data['matters_declined_by_counter'] = $this->Vacation_advance_model->get_matters_declined_by_counter_list($aor_code, $mainhead);
        $data['mainhead'] = $mainhead;
        return $this->render('vacation.getVacationAdvanceListAOR_decline', $data);
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
            $dairyNos = isset($_POST['diary_no']) ? $_POST['diary_no'] : NULL;
            $userIP = getClientIP();
            $mainhead = $this->request->getVar('mainhead');
            if (empty($dairyNos)) {
                echo '1@@@' . htmlentities("Please select atleast one Case which need to be Decline", ENT_QUOTES);
                exit(0);
            }
            $vacation_advance_list_advocate = $this->Vacation_advance_model->get_vacation_advance_list_advocate($dairyNos, $aor_code, $mainhead);
            if (!empty($vacation_advance_list_advocate)) {
                $this->db->transStart();
                $is_insert_vacation_advance_list_advocate_log = $this->Vacation_advance_model->insert_vacation_advance_list_advocate_log('icmis.vacation_advance_list_advocate_log', $vacation_advance_list_advocate);
                if ($is_insert_vacation_advance_list_advocate_log) {
                    $curr_dt_time = date('Y-m-d H:i:s');
                    $data = array(
                        'updated_on' => $curr_dt_time,
                        'updated_by' => $userID,
                        'is_deleted' => 't',
                        'updated_from_ip' => getClientIP()
                    );
                    $builder = $this->db->table('icmis.vacation_advance_list_advocate');
                    $builder->whereIn('diary_no', $dairyNos);
                    $builder->where('is_deleted', 'f');
                    $builder->where('aor_code', $aor_code);
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
            $vacation_advance_list_advocate = $this->Vacation_advance_model->get_vacation_advance_list_advocate_restore($diary_no, $aor_code, $mainhead);
            if (!empty($vacation_advance_list_advocate)) {
                $sdata = array(
                    'id' => $vacation_advance_list_advocate[0]->id,
                    'diary_no' => $vacation_advance_list_advocate[0]->diary_no,
                    'conn_key' => $vacation_advance_list_advocate[0]->conn_key,
                    'is_fixed' => $vacation_advance_list_advocate[0]->is_fixed,
                    'aor_code' => $vacation_advance_list_advocate[0]->aor_code,
                    'is_deleted' => $vacation_advance_list_advocate[0]->is_deleted,
                    'updated_by' => $vacation_advance_list_advocate[0]->updated_by,
                    'updated_on' => $vacation_advance_list_advocate[0]->updated_on,
                    'updated_from_ip' => $vacation_advance_list_advocate[0]->updated_from_ip,
                    'vacation_list_year' => $vacation_advance_list_advocate[0]->vacation_list_year,
                );
                $this->db->transStart();
                $is_insert_vacation_advance_list_advocate_log = $this->Vacation_advance_model->insert_vacation_advance_list_advocate_log('icmis.vacation_advance_list_advocate_log', $sdata);
                if ($is_insert_vacation_advance_list_advocate_log) {
                    $curr_dt_time = date('Y-m-d H:i:s');
                    $data = array(
                        'updated_on' => $curr_dt_time,
                        'updated_by' => $userID,
                        'is_deleted' => 'f',
                        'updated_from_ip' => getClientIP()
                    );
                    $builder = $this->db->table('icmis.vacation_advance_list_advocate');
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
