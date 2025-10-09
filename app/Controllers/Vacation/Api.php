<?php
namespace App\Controllers\Vacation;

use App\Controllers\BaseController;
use App\Models\Vacation\VacationAdvanceModel;

class Api extends BaseController {

    protected $Vacation_advance_model;

    public function __construct() {
        parent::__construct();
        $this->Vacation_advance_model = new VacationAdvanceModel();
    }

    public function report()
    {
        $response=null;
        if (empty($_REQUEST) || !isset($_REQUEST)){
            $response= 'Please provide required parameter: mainhead.';
        }else if (empty($_REQUEST['token'])){
            $response= 'Please provide required parameter: token..';
        }
        $token=!empty($_REQUEST['token']) ? $_REQUEST['token'] :null;
        $token_existed='11873d07510c2c3348f58a04f63bc9a632961187';
        if ($token_existed==$token && empty($response)){
            $vacation_advance_list_advocate = $this->get_vacation_advance_list_json_report($_REQUEST);
            echo json_encode(array('status'=>true,'vacation_advance_list_advocate'=>$vacation_advance_list_advocate));
        }else{
            $response=!empty($response) ? $response :'You are not Authorized';
            echo json_encode(array('status'=>$response,'vacation_advance_list_advocate'=>array()));
        }
        exit();
    }
    public function get_vacation_advance_list_json_report($REQUEST) {
        $current_year=date('Y');
        $builder = $this->db->table('icmis.vacation_advance_list_advocate');
        $builder->SELECT("*");
        
        $builder->WHERE('is_deleted', 't');
        $builder->WHERE('vacation_list_year',$current_year);
        if (isset($REQUEST['mainhead']) && !empty($REQUEST['mainhead'])){ $builder->WHERE('mainhead', $REQUEST['mainhead']); }
        if (isset($REQUEST['diary_no']) && !empty($REQUEST['diary_no'])){ $builder->WHERE('diary_no', $REQUEST['diary_no']); }
        if (isset($REQUEST['aor_code']) && !empty($REQUEST['aor_code'])){ $builder->WHERE('aor_code', $REQUEST['aor_code']); }
        $builder->order_by("substr(diary_no::text, length(diary_no::text) - 3, 4)::int asc, substr(diary_no::text, 1, length(diary_no::text) - 4)::int asc", FALSE);
        $query = $builder->get();
        return $query->getResultArray();
    }
    public function count_report()
    {
        if (!isset($_SESSION['login']) && empty($_SESSION['login'])) {
            redirect('login'); }else{ is_user_status(); }
        $allowed_users = array(USER_ADMIN,USER_ADMIN_READ_ONLY,USER_EFILING_ADMIN);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users)) { redirect('login');exit(0); }
        $current_year=date('Y');
        $builder = $this->db->table('icmis.vacation_advance_list_advocate val');
        $builder->select("
                           CONCAT(b.name, ' (', val.aor_code, ')') AS aor_name,
                           SUM(CASE WHEN val.mainhead = 'F' THEN 1 ELSE 0 END) AS total_no_of_regular,
                           SUM(CASE WHEN val.mainhead = 'F' AND val.is_deleted = 't' THEN 1 ELSE 0 END) AS total_no_of_regular_concert,
                           SUM(CASE WHEN val.mainhead = 'M' THEN 1 ELSE 0 END) AS total_no_of_miscellaneous,
                           SUM(CASE WHEN val.mainhead = 'M' AND val.is_deleted = 't' THEN 1 ELSE 0 END) AS total_no_of_miscellaneous_concert,
                           TO_CHAR(MAX(val.updated_on), 'DD-MM-YYYY HH24:MI:SS') AS updated_on
                        ");
        
        $builder->join('icmis.main m', 'val.diary_no = m.diary_no', 'inner');
        $builder->join('icmis.bar b', 'val.aor_code = b.aor_code', 'left');
        $builder->where('val.vacation_list_year', $current_year);
        if (isset($_REQUEST['aor_code']) && !empty($_REQUEST['aor_code'])){ $builder->WHERE('val.aor_code', $_REQUEST['aor_code']); }
        $builder->group_by(array('b.name', 'val.aor_code'));
        $builder->order_by('aor_name', 'asc');
        $query = $builder->get();
        $data['vacation_advance_list_advocate'] =$query->result_array();
        if (isset($_REQUEST['json']) && !empty($_REQUEST['json']) && $_REQUEST['json']=='Y'){
            echo json_encode(array('status'=>true,'vacation_advance_list_advocate'=>$data));
        }else{
            $this->load->view('templates/header');
            $this->load->view('vacation/vacation_advance_list_advocate_count_report', $data);
            $this->load->view('templates/footer');
        }

    }

    public function get_sum_report()
    {
        $get_data_get_sum_report = array();
        $get_data_table_supplementary = array();
        $table_supplementary = '_supp';
        $get_data_get_sum_report = $this->Vacation_advance_model->get_sum_report_details();
        $get_data_table_supplementary = $this->Vacation_advance_model->get_sum_report_details($table_supplementary);
        $data = array_merge($get_data_get_sum_report, $get_data_table_supplementary);
        $notice = $this->Vacation_advance_model->get_notice();
        $data['notice_date_main'] = $data['notice_date_supp'] = null;
        if (isset($notice) && !empty($notice)) {
            $data['notice_date_main'] = (isset($notice[0]) && !empty($notice[0]['activated_from_date'])) ? date('d.m.Y', strtotime($notice[0]['activated_from_date'])) : '';
            $data['notice_date_supp'] = (isset($notice[1]) && !empty($notice[1]['activated_from_date'])) ? date('d.m.Y', strtotime($notice[1]['activated_from_date'])) : '';
        }
        if (isset($_REQUEST['json']) && !empty($_REQUEST['json']) && $_REQUEST['json'] == 'Y') {
            echo json_encode(array('status' => true, 'vacationData' => $data));
        } else {
            $data['supplementary'] = (isset($table_supplementary) && !empty($table_supplementary)) ? '(Supplementary) ' . date('Y') : '';
            $this->render('vacation.vacation_advance_list_advocate_sum_report', $data);
        }
    }

}

?>
