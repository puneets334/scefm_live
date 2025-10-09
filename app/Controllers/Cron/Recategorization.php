<?php

namespace App\Controllers\Cron;

use App\Controllers\BaseController;
use App\Libraries\webservices\Efiling_webservices;

class Recategorization extends BaseController
{

    protected $efiling_webservices;
    protected $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
        $this->efiling_webservices = new Efiling_webservices();
    }

    public function get_cases_efiled($REQUEST = null)
    {
        if (empty($REQUEST) || !isset($REQUEST)) {
            echo 'Please provide at least one required parameter: Case Create Date (year and month), efiling_no, or diary_no.';
            exit();
        } else if (!empty($REQUEST) && empty($REQUEST['year']) && !empty($REQUEST['month'])) {
            echo 'Please provide both required parameter: Case Create Date year and month)';
            exit();
        }
        if (!empty($REQUEST) && !empty($REQUEST['diary_no'])) {
            $diary_no = $REQUEST['diary_no'];
            $diary_number = substr($diary_no, 0, strlen($diary_no) - 4);
            $diary_year = substr($diary_no, -4);
        } else {
            $diary_number = null;
            $diary_year = null;
        }
        $builder = $this->db->table('efil.tbl_case_details cd');
        $builder->DISTINCT();
        $builder->SELECT('cd.id,cd.sc_diary_num,cd.sc_diary_year,cd.sc_diary_date,cd.subject_cat_old,cd.subj_main_cat_old,cd.subj_sub_cat_1_old,cd.created_on,en.efiling_no,
        EXTRACT(YEAR FROM cd.created_on) as year,EXTRACT(MONTH FROM cd.created_on) as month,');
        $builder->JOIN('efil.tbl_efiling_nums as en', 'cd.registration_id = en.registration_id');
        $builder->JOIN('efil.tbl_efiling_num_status as cs', 'cd.registration_id = cs.registration_id');
        $builder->WHERE('cd.is_deleted', false);
        $builder->WHERE('en.is_deleted', false);
        $builder->WHERE('cs.is_deleted', false);
        $builder->WHERE('cs.is_active', true);
        $builder->WHERE('en.ref_m_efiled_type_id', E_FILING_TYPE_NEW_CASE);
        $builder->WHERE('cd.sc_diary_num is not null');
        $builder->WHERE('cd.sc_diary_year is not null');
        $builder->WHERE('cd.subject_cat_old is not null');
        // $builder->WHERE('cd.subj_sub_cat_1_old is not null');
        $builder->WHERE('cd.is_recategorization', 'N');
        if (!empty($REQUEST) && !empty($REQUEST['year'])) {
            $builder->WHERE("EXTRACT(YEAR FROM cd.created_on)=", $REQUEST['year']);
        }
        if (!empty($REQUEST) && !empty($REQUEST['month'])) {
            $builder->WHERE("EXTRACT(MONTH FROM cd.created_on)=", $REQUEST['month']);
        }
        if (!empty($REQUEST) && !empty($REQUEST['efiling_no'])) {
            $builder->WHERE("en.efiling_no", $REQUEST['efiling_no']);
        }
        if (!empty($REQUEST) && !empty($REQUEST['diary_no']) && !empty($diary_number) && !empty($diary_year)) {
            $builder->WHERE('cd.sc_diary_num', $diary_number);
            $builder->WHERE('cd.sc_diary_year', $diary_year);
        }
        $builder->orderBy('cd.id', 'ASC');
        // $builder->ORDER_BY('cd.id','DESC');
        // $builder->LIMIT(5000);
        $query = $builder->get();
        // echo $builder->last_query();die;
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    public function update_tbl_case_details($id, $sc_diary_num, $sc_diary_year, $case_details_update)
    {
        $builder = $this->db->table('efil.tbl_case_details');
        $builder->set($case_details_update);
        $builder->WHERE('id', $id);
        $builder->WHERE('sc_diary_num', $sc_diary_num);
        $builder->WHERE('sc_diary_year', $sc_diary_year);
        $builder->WHERE('is_recategorization', 'N');
        $builder->UPDATE();
    }

    public function index()
    {
        echo 'Not Authorized!'; exit;
        $year = null;
        $month = null;
        if (!empty($_REQUEST['year'])) {
            $year = $_REQUEST['year'];
        }
        if (!empty($_REQUEST['month'])) {
            $month = $_REQUEST['month'];
        }
        $cases_efiled = $this->get_cases_efiled($_REQUEST);
        if ($cases_efiled) {
            echo count($cases_efiled);
            $case_chunks = array_chunk($cases_efiled, 20);
            $tbl_c = 0;
            $tbl_c_not = 0;
            $efil_tbl_case_details_updated = $efil_tbl_case_details_not_updated = '';
            foreach ($case_chunks as $index => $chunk) {
                echo "<br/> Chunk No. " . ($index + 1) . " and Chunk size: " . sizeof($chunk);
                echo "<br/>";
                $data = $this->efiling_webservices->get_cases_efiled_new_submaster_id($chunk);
                if ($data) {
                    $curr_dt = date('Y-m-d H:i:s');
                    foreach ($data->consumed_data as $response) {
                        if ($response->status == 'Y' && !empty($response->submaster_id_new) && !empty($response->subj_main_cat_new)) {
                            $tbl_c++;
                            $efil_tbl_case_details_updated .= $response->sc_diary_num . $response->sc_diary_year . ',';
                            $case_details_update = array(
                                'subject_cat' => $response->submaster_id_new,
                                'subj_main_cat' => $response->subj_main_cat_new,
                                'subj_sub_cat_1' => $response->subj_sub_cat_1_new,
                                'is_recategorization' => "Y",
                                'recategorization_updated_on' => $curr_dt,
                            );
                            $this->update_tbl_case_details($response->id, $response->sc_diary_num, $response->sc_diary_year, $case_details_update);
                        } else {
                            $tbl_c_not++;
                            $efil_tbl_case_details_not_updated .= $response->sc_diary_num . $response->sc_diary_year . ',';
                        }
                    }
                }
            }
            echo '<b>Total tbl_case_details table records not updated count is : ' . $tbl_c_not . ' </b> of Diary Number(s):' . $efil_tbl_case_details_not_updated . '<br/>';
            echo '<b>Total tbl_case_details table records updated count is : ' . $tbl_c . ' </b> of Diary Number(s):' . $efil_tbl_case_details_updated . '<br/>';
        } else {
            echo "No Records Found for Recategorization";
        }
    }
}
