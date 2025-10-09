<?php
namespace App\Controllers\Csrftoken;
use App\Controllers\BaseController;
use App\Models\Common\CommonModel;
use Config\Security;

class DefaultController extends BaseController {
    protected $Common_model;
    protected $security;
    public function index() {
        $security = \Config\Services::security();
        $response = array(
            'CSRF_TOKEN' => $security->getTokenName(),
            'CSRF_TOKEN_VALUE' => $security->getHash()
        );

        $_SESSION['csrf_to_be_checked'] = $security->getHash();

        echo json_encode($response);
    }
    public function updateIsDeadMinorData(){
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $output = array();
        $postData = json_decode(file_get_contents('php://input'),true);
        $current_party_id = !empty($postData['current_party_id']) ? (string)$postData['current_party_id'] : NULL;
        if(isset($current_party_id) && !empty($current_party_id)){
          $this->Common_model = new CommonModel();
            $params = array();
            $params['table_name'] = "efil.tbl_case_parties";
            $params['whereFieldName'] = "parent_id";
            $params['whereFieldValue'] = $current_party_id;
            $updateArr = array();
            $updateArr['is_dead_minor'] = false;
            $updateArr['is_dead_file_status'] = false;
            $updateArr['deleted_by_ip'] = getClientIP();
            $updateArr['is_deleted'] = true;
            $updateArr['updated_by'] = $_SESSION['login']['id'];
            $updateArr['updated_on'] = date('Y-m-d H:i:s');
            $updateArr['deleted_on'] = date('Y-m-d H:i:s');
            $updateArr['deleted_by'] = $_SESSION['login']['id'];
            $params['updateArr'] = $updateArr;
            $res = $this->Common_model->updateLrData($params);
            if(isset($res) && !empty($res)){
                $output['status'] = true;
                $output['message'] = "Successfully changed is dead/minor to live";
            }
            else{
                $output['status'] = false;
                $output['message'] = "Something went wrong,Please try again later";
            }
        }
        echo json_encode($output);
        exit(0);
    }



}

