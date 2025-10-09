<?php

namespace App\Controllers\AIAssisted;

use App\Controllers\BaseController;
use App\Models\AIAssisted\CommonCasewithAIModel;

class Report extends BaseController {
    protected $Common_casewithAI_model;
    protected $db;
    public function __construct() {
        parent::__construct();
        $dbs = \Config\Database::connect();
        $this->db = $dbs->connect();
        $this->Common_casewithAI_model = new CommonCasewithAIModel();
    }
    public function index()
    {

    }

    public function list($id=null){
        $db = \Config\Database::connect();
        $builder = $db->table('efil.tbl_uploaded_pdfs_jsonai j');
        $builder->SELECT("j.*,ct.casename,trai.api_url,concat(u.first_name,u.last_name) user_uploaded_by ");
        
        $builder->JOIN('icmis.casetype ct', 'j.sc_case_type = ct.casecode', 'left');
        $builder->JOIN('efil.tbl_round_robin_api_iitm trai', 'j.tbl_round_robin_api_iitm_id = trai.id');
        $builder->JOIN('efil.tbl_users u', 'j.uploaded_by = u.id');
        $builder->WHERE('trai.is_deleted', FALSE);
        $builder->WHERE('j.is_deleted', FALSE);
        $builder->WHERE('j.is_active_iitm', true);
        $builder->WHERE('j.api_stage_id', 1);
        $builder->WHERE('trai.api_type', 1);
        if (!empty($id)){ $builder->WHERE('u.id', $id); }
        $builder->ORDERBY('j.iitm_api_json_updated_on', 'DESC');
        $query = $builder->get();
        $result=  $query->getResultArray();
        $data['report_list'] =$result;
        $this->render('AIAssisted.case_with_aiassisted_report_list_view', @compact('data'));
    }
    public function json_decode($id){
        $id = url_decryption($id);
        if (empty($id)){
            echo 'Document id is required';exit();
        }
        $builder = $this->db->table('efil.tbl_uploaded_pdfs_jsonai');
        $builder->SELECT("*");
        
        $builder->WHERE('id', $id);
        $builder->WHERE('is_deleted', FALSE);
        $builder->ORDER_BY('uploaded_on','DESC');
        $builder->LIMIT(1);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $response_result = $query->getResultArray();
            if (!empty($response_result)) {
                $decoded_data = json_decode($response_result[0]['iitm_api_json'], TRUE);
                if (!empty($decoded_data)) {
                    echo "<pre>";
                    echo json_encode($decoded_data, JSON_PRETTY_PRINT);
                    echo "</pre>";
                }else{
                    echo 'Data not found';
                }

            }

        }else{
            echo 'Data not found';
        }


        exit();


    }
    public function defect_list($id)
    {
        $id = url_decryption($id);
        if (empty($id)) {
            echo 'Document id is required';
            exit();
        }
        $builder = $this->db->table('efil.tbl_uploaded_pdfs_jsonai');
        $builder->SELECT("*");
        
        $builder->WHERE('iitm_api_json is not null');
        $builder->WHERE('id', $id);
        $builder->WHERE('is_deleted', FALSE);
        $builder->ORDER_BY('uploaded_on', 'DESC');
        $builder->LIMIT(1);
        $query = $this->db->get();
        $result = $query->getResultArray();
        $defect = array();
        if (!empty($result)) {
            if (isset($result) && isset($result[0]['iitm_api_json_defect']) && !empty($result[0]['iitm_api_json_defect']) && $result[0]['iitm_api_json_defect'] !=null) {
                if(strtolower($result[0]['iitm_api_json_defect']) !='internal server error') {
                    $iitm_api_json_defect_decode = json_decode($result[0]['iitm_api_json_defect'], TRUE);
                    if (!empty($iitm_api_json_defect_decode)) {
                        if (!empty($iitm_api_json_defect_decode)) {
                            $defect = $iitm_api_json_defect_decode;
                        }
                    }
                }
            }
            if (empty($defect)) {
                if (isset($result) && !empty($result[0]['iitm_api_json'])) {
                    $iitm_api_json_decode = json_decode($result[0]['iitm_api_json'], TRUE);
                    if (!empty($iitm_api_json_decode)) {
                        if (!empty($iitm_api_json_decode)) {
                            $defect = $iitm_api_json_decode['defect'];
                        }
                    }
                }
            }
        }

        $data['defect_list'] =$defect;
        $this->render('AIAssisted.defect_list_view', @compact('data'));

    }
}
?>
