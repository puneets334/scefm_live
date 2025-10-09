<?php
namespace App\Controllers\VacationSupp;

use App\Controllers\BaseController;

class Api extends BaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function report()
    {
        $response = null;
        if (empty($_REQUEST) || !isset($_REQUEST)) {
            $response = 'Please provide required parameter: mainhead.';
        } else if (empty($_REQUEST['token'])) {
            $response = 'Please provide required parameter: token..';
        }
        $token = !empty($_REQUEST['token']) ? $_REQUEST['token'] : null;
        $token_existed = '11873d07510c2c3348f58a04f63bc9a632961187';
        if ($token_existed == $token && empty($response)) {
            $vacation_advance_list_advocate = $this->get_vacation_advance_list_json_report($_REQUEST);
            echo json_encode(array('status' => true, 'vacation_advance_list_advocate' => $vacation_advance_list_advocate));
        } else {
            $response = !empty($response) ? $response : 'You are not Authorized';
            echo json_encode(array('status' => $response, 'vacation_advance_list_advocate' => array()));
        }
        exit();
    }

    public function get_vacation_advance_list_json_report($REQUEST)
    {
        $current_year = date('Y');
        $builder = $this->db->table('icmis.vacation_advance_list_advocate_supp');
        $builder->SELECT("*");
        $builder->WHERE('is_deleted', 't');
        $builder->WHERE('vacation_list_year', $current_year);
        if (isset($REQUEST['mainhead']) && !empty($REQUEST['mainhead'])) {
            $builder->WHERE('mainhead', $REQUEST['mainhead']);
        }
        if (isset($REQUEST['diary_no']) && !empty($REQUEST['diary_no'])) {
            $builder->WHERE('diary_no', $REQUEST['diary_no']);
        }
        if (isset($REQUEST['aor_code']) && !empty($REQUEST['aor_code'])) {
            $builder->WHERE('aor_code', $REQUEST['aor_code']);
        }
        $builder->orderBy("substr(diary_no::text, length(diary_no::text) - 3, 4)::int asc, substr(diary_no::text, 1, length(diary_no::text) - 4)::int asc", FALSE);
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function count_report()
    {
        if (!isset($_SESSION['login']) && empty($_SESSION['login'])) {
            return redirect()->to(base_url('/'));
        } else {
            is_user_status();
        }
        $allowed_users = array(USER_ADMIN, USER_ADMIN_READ_ONLY, USER_EFILING_ADMIN);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users)) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $current_year = date('Y');
        $builder = $this->db->table('icmis.vacation_advance_list_advocate_supp val');
        $builder->select("
            CONCAT(b.name, ' (', val.aor_code, ')') AS aor_name,
            SUM(CASE WHEN val.mainhead = 'F' THEN 1 ELSE 0 END) AS total_no_of_regular,
            SUM(CASE WHEN val.mainhead = 'F' AND val.is_deleted = 't' THEN 1 ELSE 0 END) AS total_no_of_regular_concert,
            SUM(CASE WHEN val.mainhead = 'M' THEN 1 ELSE 0 END) AS total_no_of_miscellaneous,
            SUM(CASE WHEN val.mainhead = 'M' AND val.is_deleted = 't' THEN 1 ELSE 0 END) AS total_no_of_miscellaneous_concert,
            TO_CHAR(MAX(val.updated_on), 'DD-MM-YYYY HH24:MI:SS') AS updated_on
        ");
        $builder->join('icmis.main_supp m', 'val.diary_no = m.diary_no', 'inner');
        $builder->join('icmis.bar b', 'val.aor_code = b.aor_code', 'left');
        $builder->where('val.vacation_list_year', $current_year);
        if (isset($_REQUEST['aor_code']) && !empty($_REQUEST['aor_code'])) {
            $builder->WHERE('val.aor_code', $_REQUEST['aor_code']);
        }
        $builder->group_by(array('b.name', 'val.aor_code'));
        $builder->order_by('aor_name', 'asc');
        $query = $builder->get();
        $data['vacation_advance_list_advocate'] = $query->getResultArray();
        if (isset($_REQUEST['json']) && !empty($_REQUEST['json']) && $_REQUEST['json'] == 'Y') {
            echo json_encode(array('status' => true, 'vacation_advance_list_advocate' => $data));
        } else {
            return $this->render('vacation_supp.vacation_advance_list_advocate_count_report', $data);
        }
    }
}
