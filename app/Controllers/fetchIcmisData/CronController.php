<?php
namespace App\Controllers;

class CronController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        // $this->load->model('miscellaneous_docs/Get_details_model');
        $this->load->model('fetchIcmisData/synchDataModel');
        $this->load->library('webservices/efiling_webservices');
        ///$this->load->model('fetchIcmisData/fetchDataModel');
    }

    public function index()
    {
        /*extract($_POST);
        $array1=array(array('id'=>1,'name'=>'Test'),array('id'=>2,'name'=>'Test1'),array('id'=>3,'name'=>'Test2'));
        $array2=array(array('id'=>1,'name'=>'Test'),array('id'=>4,'name'=>'Test1'),array('id'=>3,'name'=>'Test5'),array('id'=>10,'name'=>'Test10'));
        $differences=compare_multi_Arrays($array1,$array2);
        print_r($differences);*/
        /*        $table_names=array('agency_master','act_master','act_master_temp','act_section',
        'bar','agency_master','authority','casetype','case_defect','country','court_complex',
        'court_fee_temp','defect_policy','deptt','district','docmaster','holidays','icmis_high_courts',
        'jail_master','judge','lc_casetype','lc_hc_casetype','m_court_fee','master_bench',
        'm_court_fee_valuation','m_from_court','m_limitation_period','m_to_r_casetype_mapping',
        'master_fixedfor','objection','pincode_district_mapping','police','post_t',
        'ref_agency_code','ref_agency_state','ref_defect_code','ref_keyword','ref_lower_court_case_type',
        'rto','state','submaster','m_tbl_valuation');*/
        $table_names = array('bar',  'authority', 'casetype', 'country',
            'deptt', 'district',  'holidays',
            'judge', 'lc_casetype', 'lc_hc_casetype', 'objection',  'Post_t',
            'ref_agency_code', 'ref_agency_state', 'ref_defect_code', 'ref_keyword', 'ref_lower_court_case_type',
            'rto', 'state', 'submaster');
        $table_not_synched=array('docmaster','police');//No primary key in these tables
        /*$table_names = array('bar',  'authority', 'casetype');*/
        foreach ($table_names as $table) {
            $tabledata = $this->get_master_data($table);
            if ($tabledata) {
                echo $table . ":" . count($tabledata['table_data']) . "<br/>";
                $primary_key="id";
                switch ($table){
                    case 'authority':
                        $primary_key="authcode";
                        break;
                    case 'bar':
                        $primary_key="bar_id";
                        break;
                    case 'casetype':
                        $primary_key="casecode";
                        break;
                    case 'deptt':
                        $primary_key="deptcode";
                        break;
                    case 'district':
                        $primary_key="dcode";
                        break;
                   /* case 'docmaster':
                        $primary_key="{{pending}}";
                        break;*/
                    case 'holidays':
                        $primary_key="hdate";
                        break;
                    case 'judge':
                        $primary_key="jcode";
                        break;
                    case 'lc_casetype':
                        $primary_key="lccasecode";
                        break;
                    case 'lc_hc_casetype':
                        $primary_key="lccasecode";
                        break;
                    case 'objection':
                        $primary_key="objcode";
                        break;
                    /*case 'police': //No primary key in this table
                        $primary_key="objcode";
                        break;*/
                    case 'Post_t':
                        $primary_key="post_code";
                        break;
                    case 'state':
                        $primary_key="id_no";
                        break;
                    case 'state':
                        $primary_key="id_no";
                        break;
                    default:
                        break;
                }
                echo "<br/> Table ".$table." started:".date('H:i:s');
                $this->synchDataModel->upsert_in_master_table($tabledata['table_data'],$table,$primary_key);
                echo "<br/> Table ".$table." End:".date('H:i:s');

            } else {
                echo $table . ": Nothing found!.<br/>";
            }
            //print_r($tabledata);

        }
    }


    private function get_master_data($tablename)
    {
        $masterData = file_get_contents(ICMIS_SERVICE_URL . "/CronData/fetchMasterData?table_name=" . $tablename);

        if ($masterData != false) {
            return json_decode($masterData, true);
        } else {
            return NULL;
        }

    }

}