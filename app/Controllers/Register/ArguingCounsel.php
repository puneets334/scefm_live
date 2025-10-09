<?php

namespace App\Controllers\Register;
use App\Controllers\BaseController;
use App\Models\Register\RegisterModel;
use App\Models\Citation\CitationModel;
use App\Models\Common\CommonModel;
use App\Models\NewCase\DropdownListModel;
use App\Libraries\webservices\Efiling_webservices;
use App\Libraries\Slice;
use App\Libraries\Encrypt;
use CodeIgniter\Encryption\Encryption;
use App\Libraries\webservices\Ecoping_webservices;
// if (!defined('BASEPATH')) exit('No direct script access allowed');

class ArguingCounsel extends BaseController {
    protected $Register_model;
    protected $Citation_model;
    protected $Dropdown_list_model;
    protected $Common_model;
    protected $efiling_webservices;
    protected $slice;
    protected $form_validation;
    protected $session;
    protected $config;
    protected $request;
    protected $ecoping_webservices;
    public function __construct() {
        //after 1 min
        
        // session end
        parent::__construct();
        $this->Register_model = new RegisterModel();
        $this->Citation_model = new CitationModel();
        $this->Common_model = new CommonModel();
        $this->Dropdown_list_model = new DropdownListModel();
        $this->slice = new Slice();
        $this->efiling_webservices = new Efiling_webservices();
        $this->session = \Config\Services::session();
        $this->request = \Config\Services::request();
        $this->config = \Config\Services::config();
        $this->ecoping_webservices=new Ecoping_webservices();
        helper(['security']);
        
    }

    public function index(){
        // if (getSessionData('login') != '' && getSessionData('login')['ref_m_usertype_id'] != USER_ADVOCATE){
        //     return redirect()->to(base_url('/'));
        //     exit(0);
        // }
        if (!isset($_SERVER['HTTP_REFERER'])){
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $data = array();
        $params = array();
        $params['login_id'] = !empty(getSessionData('login')['id']) ? (int)getSessionData('login')['id'] : NULL;
        $selfArguingCounselData = $this->Register_model->getArguingDataForApproval($params);
        $data = array(
            'selfArguingCounselData' => !empty($selfArguingCounselData) ? $selfArguingCounselData : NULL,
            'mobile_no' => NULL,
            'email_id' => NULL,
            'bar_reg_no' => NULL,
            'stateArr' => NULL,
        );
        $this->render('register.add_arguing_counsel', $data);

    }
    
    public function ifMobileEmailExists(){
        extract($_POST);
    }

    public function saveArguingCounselByAOR(){
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        if (getSessionData('login')['ref_m_usertype_id'] != USER_ADVOCATE){
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $validation =  \Config\Services::validation();
        $rules=[
            "name" => [
                "label" => "Name",
                "rules" => "required|max_length[100]|min_length[3]"
            ],
            "email" => [
                "label" => "Email",
                "rules" => "required|max_length[100]|valid_email"
            ],
            "mobile" => [
                "label" => "Mobile",
                "rules" => "required|exact_length[10]|is_natural"
            ],
            "bar_reg_no" => [
                "label" => "Bar Registration No.",
                "rules" => "max_length[30]"
            ],
        ];

       
        if ($this->validate($rules) === FALSE) {
            $data = [
                'validation' => $this->validator,
            ];

        } else{
            $name = !empty($this->request->getPost('name')) ? $this->request->getPost('name') : NULL;
            $mobile = !empty($this->request->getPost('mobile')) ? $this->request->getPost('mobile') : NULL;
            $email = !empty($this->request->getPost('email')) ? $this->request->getPost('email') : NULL;
            $bar_reg_no = !empty($this->request->getPost('bar_reg_no')) ? $this->request->getPost('bar_reg_no') : NULL;
            $mobileExistStatus = false;
            $emailExistStatus = false;
            //mobile uniqueness
            if(isset($mobile) && !empty($mobile)){
                //tbl_users
                $mobileExistUser = $this->Register_model->check_already_reg_mobile($mobile);
                if(boolval($mobileExistUser == false)){
                    //tbl_arguing_counsels
                    $mobileExistArguing = $this->Register_model->check_already_reg_mobile_arguing_counsel($mobile);
                    if(boolval($mobileExistArguing) == false){
                        $mobileExistStatus = false;
                    } else{
                        $emailExistStatus = true;
                    }
                } else{
                    $mobileExistStatus = true;
                }
            }
            //email uniqueness
            if(isset($email) && !empty($email)){
                //tbl_users
                $emailExistUser = $this->Register_model->check_already_reg_email($email);
                if(boolval($emailExistUser == false)){
                    //tbl_arguing_counsels
                    $emailExistarguing = $this->Register_model->check_already_reg_email_arguing_counsel($email);
                    if(boolval($emailExistarguing) == false){
                        $emailExistStatus = false;
                    } else{
                        $emailExistStatus = true;
                    }
                } else{
                    $emailExistStatus = true;
                }
            }
            // $emailExistStatus = false;
            // $mobileExistStatus = false;
            if(isset($emailExistStatus) && !empty($emailExistStatus) && isset($mobileExistStatus) && !empty($mobileExistStatus)){
                $this->session->setFlashdata('emailExistMessage','Email Already Exists! Please Try Another One.');
                $this->session->setFlashdata('mobileExistMessage','Mobile Already Exists! Please Try Another One.');
            } else if(isset($emailExistStatus) && !empty($emailExistStatus)){
                $this->session->setFlashdata('emailExistMessage','Email Already Exists! Please Try Another One.');
            } else if(isset($mobileExistStatus) && !empty($mobileExistStatus)){
                $this->session->setFlashdata('mobileExistMessage','Mobile Already Exists! Please Try Another One.');
            } else {
                $registration_code=generateRandomString();
                $dataToSave=array();
                $dataToSave['advocate_name'] = $name;
                $dataToSave['mobile_number']= $mobile;
                $dataToSave['emailid'] = $email;
                $dataToSave['bar_reg_no']= $bar_reg_no;
                $dataToSave['account_status']= 1;
                $dataToSave['created_on']= date('Y-m-d H:i:s');
                $dataToSave['created_by']= getSessionData('login')['id'];
                $dataToSave['created_ip'] = get_client_ip();
                $dataToSave['registration_code'] = $registration_code;
                $dataToSave['approving_user_id']= getSessionData('login')['id'];
                $dataToSave['approved_on'] = date('Y-m-d H:i:s');
                $dataToSave['approved_ip'] = get_client_ip();
                $dataToSave['is_pre_approved']= true;
                if(!empty($name) && !empty($email) && !empty($mobile)){
                    $this->Citation_model->insert($dataToSave);
                    $insetId = $this->Citation_model->getInsertID();
                    if(isset($insetId) && !empty($insetId)){
                        // sent weblink
                        $webUrl = $this->generateWebLink($insetId,$registration_code);
                        $subject ="Registration For Advocate";
                        $email_message = 'Dear '.$name.', Your registration code for advocate is "'.$registration_code.'" Please click below link and verify registration code. <br><a href="'.$webUrl->getHeaderLine('Location').'">Verify Registration Code</a>';
                        $sms_message  = 'Dear '.$name.', Your registration code for advocate is "'.$registration_code.'" Please go to mail and verify registration code.';
                        send_mail_msg($email, $subject, $email_message,$to_user_name="arguing_counsel");
                        send_mobile_sms($mobile, $sms_message,ARGUING_COUNSEL_VERIFY_CODE);
                        $this->session->setFlashdata('success','Registration code has been sent successfully on email! Please verify registration code.');
                    } else{
                        $this->session->setFlashdata('error','Something went wrong! Please try again later.');
                    }
                } else{
                    $this->index();
                }
            }
            $this->index();
            //TODO:: After saving above details in dscr.tbl_arguing_counsels send an email on given email id with weblink to directly open complete advocate page and send registration code on mobile through sms.
        }
    }

    private function generateWebLink($id,$registrationCode){
        $webLinkData=array('id'=>$id,'registration_code'=>$registrationCode);
        $webLinkDataDecoded=json_encode($webLinkData);
        $encrypter = service('encrypter');
        $encrypted_string = stringEncreption($webLinkDataDecoded);
        return redirect()->to(base_url('register/arguingCounsel/landArguingCounsel/'.base64_encode($encrypted_string)));
    }

    public function arguingCounselCompleteDetails(){
        $data = array();
        $data['state_list'] = $this->Dropdown_list_model->get_states_list();
        $data['stateArr'] = $this->Dropdown_list_model->get_states_list();
        $params = array();
        $params['ref_m_usertype_id'] = 1;
        if($_SERVER['HTTP_HOST'] == '127.0.0.1')
            $params['userid'] = 1777;
        else
            $params['userid'] = null;
        $aorList = $this->Register_model->getAorData($params);
        $data['aorList'] = !empty($aorList) ? $aorList : NULL;
        $param['login_id'] = !empty(getSessionData('login')['id']) ? (int)getSessionData('login')['id'] : NULL;
        $selfArguingCounselData = $this->Register_model->getArguingDataForApproval($param);
        $data['selfArguingCounselData'] = !empty($selfArguingCounselData) ? $selfArguingCounselData : NULL;
        return $this->render('register.add_arguing_counsel',$data);
    }
    public function saveArguingCounselCompleteDetails(){
        $data = array();
        if(empty(getSessionData('self_register_arguing_counsel'))  && empty(getSessionData('arguingCounselId'))){
            return redirect()->to(base_url('/'));
        }
        else if(!empty(getSessionData('self_register_arguing_counsel')) &&  (!empty(getSessionData('self_arguing_counsel')))){
            $success = 'Registration has been successfully completed.';
            $data['state_list'] = $this->Dropdown_list_model->get_states_list();
            $data['stateArr'] = $this->Dropdown_list_model->get_states_list();
            $this->session->setFlashdata('success', $success);
            $param['login_id'] = !empty(getSessionData('login')['id']) ? (int)getSessionData('login')['id'] : NULL;
            $selfArguingCounselData = $this->Register_model->getArguingDataForApproval($param);
            $data['selfArguingCounselData'] = !empty($selfArguingCounselData) ? $selfArguingCounselData : NULL;
            $this->render('register.add_arguing_counsel',$data);
        }
        else if(!empty(getSessionData('arguingCounselId')) && !empty(getSessionData('aor_register'))){
            $this->session->setFlashdata('success', 'Login credentials have been sent on email and mobile.');
            $data['state_list'] = $this->Dropdown_list_model->get_states_list();
            $data['stateArr'] = $this->Dropdown_list_model->get_states_list();
            $param['login_id'] = !empty(getSessionData('login')['id']) ? (int)getSessionData('login')['id'] : NULL;
            $selfArguingCounselData = $this->Register_model->getArguingDataForApproval($param);
            $data['selfArguingCounselData'] = !empty($selfArguingCounselData) ? $selfArguingCounselData : NULL;
            $this->render('register.add_arguing_counsel',$data);
        }
        else{
             // pr($_POST);
            $data['state_list'] = $this->Dropdown_list_model->get_states_list();
            $validation =  \Config\Services::validation();
            $rules=[
                'name' => 
                ["label" => 'Name',"rules" => 'trim|required|max_length[100]|min_length[3]|validate_alphacharacters'],
                'email' => 
                ["label" => 'Email',"rules" => 'trim|required|max_length[100]|valid_email'],
            'mobile' => 
                [ "label" => 'Mobile',"rules" => 'trim|required|exact_length[10]|is_natural'],
            'bar_reg_no' => 
                ["label" => 'Bar Registration No.', "rules" => 'trim'],
            'relation' => 
                ["label" => 'Relation', "rules" => 'trim|required'],
            'relation_name' => 
                ["label" => 'Relation Name',"rules" => 'trim|required|validate_alphacharacters']
            ];
            if(!empty($_POST['bar_reg_no'])){
                $rules=[ 'bar_reg_no' => 
                ["label" => 'Bar Registration No.',"rules" => 'trim|max_length[30]|min_length[3]|validate_alpha_numeric']];
            }
            if(!empty($_POST['c_address'])){
                $rules=[ 'c_address' => 
                ["label" => 'Bar Chamber address.',"rules" => 'trim|max_length[150]|min_length[3]|validate_alpha_numeric_single_double_quotes_bracket_with_special_characters']];
            }
            if(!empty($_POST['c_pincode'])){
                $rules=[ 'c_pincode' => 
                ["label" => 'Pincode',"rules" => 'trim|max_length[6]|is_natural']];
            }
            if(!empty($_POST['c_city'])){
                $rules=[ 'c_city' => 
                [ "label" => 'City', "rules" => 'trim|max_length[35]|validate_alpha_numeric_single_double_quotes_bracket_with_special_characters']];
            }
            if(!empty($_POST['c_state'])){
                $rules=[ 'c_state' => 
                [ "label" => 'State',"rules" => 'trim|required']];
            }
            if(!empty($_POST['c_district'])){
                $rules=[ 'c_district' => 
                ["label" => 'District',"rules" => 'trim|required']];
            }
            if(!empty($_POST['r_address'])){
                $rules=[ 'r_address' => 
                ["label" => 'Bar Chamber address.',"rules" => 'trim|max_length[150]|min_length[3]|validate_alpha_numeric_single_double_quotes_bracket_with_special_characters']];
            }
            if(!empty($_POST['r_pincode'])){
                $rules=[ 'r_pincode' => 
                [ "label" => 'Pincode',"rules" => 'trim|max_length[6]|is_natural']];
            }
            if(!empty($_POST['r_city'])){
                $rules=[ 'r_city' => 
                [ "label" => 'City',"rules" => 'trim|max_length[35]|validate_alpha_numeric_single_double_quotes_bracket_with_special_characters']];
            }
            if(!empty($_POST['r_state'])){
                $rules=[ 'r_state' => 
                [ "label" => 'State',"rules" => 'trim|required']];
            }
            if(!empty($_POST['r_district'])){
                $rules=[ 'r_district' => 
                [ "label" => 'District',"rules" => 'trim|required']];
            }
            if(!empty($_POST['aor'])){
                $rules=[ 'aor' => 
                [ "label" => 'AOR',"rules" => 'trim|required']];
            }
            if(empty($_FILES['bar_id_card']['name']))
            {
                $rules=[ 'bar_id_card' => 
                ["label" => 'Bar Id Card', "rules" => 'required']];
            }
            $arguingCounselId  = !empty(getSessionData('arguingCounselId')) ? trim(getSessionData('arguingCounselId')) : NULL;
            if(isset($arguingCounselId) && !empty($arguingCounselId)) {
                $arguingCounselDetails = $this->getArguingCounselDetails($arguingCounselId);
                $data['arguingCounselDetails'] = !empty($arguingCounselDetails) ? $arguingCounselDetails[0] : NULL;
            }
            if (!empty(getSessionData('self_register_arguing_counsel')) && getSessionData('self_register_arguing_counsel') == true) {
                $params = array();
                $params['ref_m_usertype_id'] = 1;
                if($_SERVER['HTTP_HOST'] == '127.0.0.1')
                    $params['userid'] = 1777;
                else
                    $params['userid'] = null;
                $aorList = $this->Register_model->getAorData($params);
                $data['stateArr'] = $this->Dropdown_list_model->get_states_list();
                $param['login_id'] = !empty(getSessionData('login')['id']) ? (int)getSessionData('login')['id'] : NULL;
                $selfArguingCounselData = $this->Register_model->getArguingDataForApproval($param);
                $data['selfArguingCounselData'] = !empty($selfArguingCounselData) ? $selfArguingCounselData : NULL;
                $data['aorList'] = !empty($aorList) ? $aorList : NULL;
            }
            if ($this->validate($rules) === FALSE && !empty($_POST)) {
                $data['validation'] = $this->validator; 
                $data['state_list'] = $this->Dropdown_list_model->get_states_list();
                $data['stateArr'] = $this->Dropdown_list_model->get_states_list();
                $params = array();
                $params['ref_m_usertype_id'] = 1;
                if($_SERVER['HTTP_HOST'] == '127.0.0.1')
                    $params['userid'] = 1777;
                else
                    $params['userid'] = null;
                $aorList = $this->Register_model->getAorData($params);
                $data['aorList'] = !empty($aorList) ? $aorList : NULL;
                $param['login_id'] = !empty(getSessionData('login')['id']) ? (int)getSessionData('login')['id'] : NULL;
                $selfArguingCounselData = $this->Register_model->getArguingDataForApproval($param);
                $data['selfArguingCounselData'] = !empty($selfArguingCounselData) ? $selfArguingCounselData : NULL;
                return $this->render('register.add_arguing_counsel',$data); 
            }
            if ($validation->withRequest($this->request)->run() === FALSE) {
            // if ($this->validate($rules) === FALSE) {
                // $data = [
                //     'validation' => $validation->getErrors(),
                // ];
                $data['state_list'] = $this->Dropdown_list_model->get_states_list();
                $data['stateArr'] = $this->Dropdown_list_model->get_states_list();
                $params = array();
                $params['ref_m_usertype_id'] = 1;
                if($_SERVER['HTTP_HOST'] == '127.0.0.1')
                    $params['userid'] = 1777;
                else
                    $params['userid'] = null;
                $aorList = $this->Register_model->getAorData($params);
                $data['aorList'] = !empty($aorList) ? $aorList : NULL;
                $param['login_id'] = !empty(getSessionData('login')['id']) ? (int)getSessionData('login')['id'] : NULL;
                $selfArguingCounselData = $this->Register_model->getArguingDataForApproval($param);
                $data['selfArguingCounselData'] = !empty($selfArguingCounselData) ? $selfArguingCounselData : NULL;
                return $this->render('register.add_arguing_counsel',$data);
            }
             else{
                if (!empty(getSessionData('self_register_arguing_counsel')) && getSessionData('self_register_arguing_counsel') == true)
                {
                    $name =  !empty($this->request->getPost('name')) ? $this->request->getPost('name') : NULL;
                    $mobile = !empty(getSessionData('adv_details')['mobile_no']) ? getSessionData('adv_details')['mobile_no'] : NULL;
                    $email = !empty(getSessionData('adv_details')['email_id']) ? getSessionData('adv_details')['email_id'] : NULL;
                    $bar_reg_no = !empty($this->request->getPost('bar_reg_no')) ? $this->request->getPost('bar_reg_no') : NULL;
                    $file_type = !empty($_FILES['bar_id_card']['type']) ? $_FILES['bar_id_card']['type'] : NULL;
                    $relation = !empty($this->request->getPost('relation')) ? $this->request->getPost('relation') : NULL;
                    $relation_name = !empty($this->request->getPost('relation_name')) ? $this->request->getPost('relation_name') : NULL;
                    $c_address = !empty($this->request->getPost('c_address')) ? $this->request->getPost('c_address') : NULL;
                    $c_city = !empty($this->request->getPost('c_city')) ? $this->request->getPost('c_city') : NULL;
                    $c_pincode = !empty($this->request->getPost('c_pincode')) ? $this->request->getPost('c_pincode') : NULL;
                    $c_state = !empty($this->request->getPost('c_state')) ? $this->request->getPost('c_state') : NULL;
                    $c_district = !empty($this->request->getPost('c_district')) ? $this->request->getPost('c_district') : NULL;
                    $r_address = !empty($this->request->getPost('r_address')) ? $this->request->getPost('r_address') : NULL;
                    $r_pincode = !empty($this->request->getPost('r_pincode')) ? $this->request->getPost('r_pincode') : NULL;
                    $r_city = !empty($this->request->getPost('r_city')) ? $this->request->getPost('r_city') : NULL;
                    $r_state = !empty($this->request->getPost('r_state')) ? $this->request->getPost('r_state') : NULL;
                    $r_district = !empty($this->request->getPost('r_district')) ? $this->request->getPost('r_district') : NULL;
                    $aor = !empty($this->request->getPost('aor')) ? $this->request->getPost('aor') : NULL;
                    $ext = '';
                    if (isset($file_type) && !empty($file_type)) {
                        $extarr = explode('/', $file_type);
                        $ext = !empty($extarr[1]) ? $extarr[1] : 'pdf';
                    }
                    $tmp_file_name = time() . rand() . '.' . $ext;
                    $file = $this->request->getFile('bar_id_card');
                    if ($file->isValid() && !$file->hasMoved()) {
                        $uploadPath = 'uploaded_docs/bar_id_card/';
                        if (!is_dir($uploadPath)) {
                            //chmod($uploadPath, 0777);
                            mkdir($uploadPath, 0777,true);
                        }
                        $fileName = $file->getName();
                        $filePath = $uploadPath . $fileName;
                        if ($file->move($uploadPath, $fileName)) {
                            $xfile = explode('.',$fileName);
                            if($xfile != 'pdf' || $xfile != 'jpeg' || $xfile != 'jpg') {
                                unlink($filePath);
                                $this->session->setFlashdata('error', 'Please Upload Valid Bar Id Card.');
                            }
                        }
                    }
                    $userIn = array();
                    $password = generateRandomString();
                    $userIn['userid'] = $password;
                    $userIn['password'] = NULL;
                    $userIn['ref_m_usertype_id'] = ARGUING_COUNSEL;
                    $userIn['first_name'] = $name;
                    $userIn['moblie_number'] = $mobile;
                    $userIn['emailid'] = $email;
                    $userIn['bar_reg_no'] = $bar_reg_no;
                    $userIn['account_status'] = 0;
                    $userIn['created_on'] = date('Y-m-d H:i:s');
                    $userIn['create_ip'] = getClientIP();
                    $userIn['is_deleted'] = true;
                    $userIn['is_active'] = 0;
                    $userIn['photo_path'] = $uploadPath . $fileName;
                    $userIn['adv_sci_bar_id'] = null;
                    $userIn['m_address1'] = $c_address;
                    $userIn['m_pincode'] = $c_pincode;
                    $userIn['m_city'] = $c_city;
                    $userIn['m_state_id'] = url_decryption($c_state);
                    $userIn['m_district_id'] = url_decryption($c_district);
                    $userIn['alt_address1'] = $r_address;
                    $userIn['alt_pincode'] = $r_pincode;
                    $userIn['alt_city'] = $r_city;
                    $userIn['alt_state_id'] = url_decryption($r_state);
                    $userIn['alt_district_id'] = url_decryption($r_district);
                    $userIn['is_first_pwd_reset'] = true;
                    // $this->load->model('citation/Citation_model');
                    $table = "efil.tbl_users";
                    $insetId = $this->Citation_model->insertData($table, $userIn);
                    if (isset($insetId) && !empty($insetId)) {
                        $dataToSave=array();
                        $dataToSave['advocate_name'] = $name;
                        $dataToSave['mobile_number']= $mobile;
                        $dataToSave['emailid'] = $email;
                        $dataToSave['bar_reg_no']= $bar_reg_no;
                        $dataToSave['relation_type'] = $relation;
                        $dataToSave['relative_name'] = $relation_name;
                        $dataToSave['tbl_users_id'] = $insetId;
                        $dataToSave['is_deleted'] = true;
                        $dataToSave['account_status']= 1;
                        $dataToSave['created_on']= date('Y-m-d H:i:s');
                        $dataToSave['registered_on'] = date('Y-m-d H:i:s');
                        $dataToSave['created_by']= NULL;
                        $dataToSave['created_ip'] = get_client_ip();
                        $dataToSave['registered_ip'] = get_client_ip();
                        $dataToSave['registration_code'] = NULL;
                        $dataToSave['approving_user_id']= $aor;
                        $dataToSave['approved_on'] = NULL;
                        $dataToSave['approved_ip'] = NULL;
                        $dataToSave['is_pre_approved']= FALSE;
                        $dataToSave['is_user_registered'] = true;
                        // $this->load->model('citation/Citation_model');
                        $table = "dscr.tbl_arguing_counsels";
                        if(!empty($name) && !empty($email) && !empty($mobile)){
                            $insetIdArguingCounsel = $this->Citation_model->insertData($table,$dataToSave);
                            if(isset($insetIdArguingCounsel) && !empty($insetIdArguingCounsel)){
                                $success = 'Registration has been successfully completed.';
                                $this->session->setFlashdata('success', $success); 
                                
                                setSessionData('self_arguing_counsel', true);
                            } else {
                                $this->session->setFlashdata('error', 'Something went wrong! Please try again later.');
                            }
                        }
                        else {
                            $this->session->setFlashdata('error', 'Something went wrong! Please try again later.');
                        }
                    } else {
                        $this->session->setFlashdata('error', 'Something went wrong! Please try again later.');
                    }
                    setSessionData('bar_id_card_error', '');
                    setSessionData('error', '');
                } else {
                    $name = !empty($arguingCounselDetails[0]['advocate_name']) ? $arguingCounselDetails[0]['advocate_name'] : NULL;
                    $mobile = !empty($arguingCounselDetails[0]['mobile_number']) ? $arguingCounselDetails[0]['mobile_number'] : NULL;
                    $email = !empty($arguingCounselDetails[0]['emailid']) ? $arguingCounselDetails[0]['emailid'] : NULL;
                    $tableId = !empty($arguingCounselDetails[0]['id']) ? $arguingCounselDetails[0]['id'] : NULL;
                    $bar_reg_no = !empty($this->request->getPost('bar_reg_no')) ? $this->request->getPost('bar_reg_no') : NULL;
                    $file_type = !empty($_FILES['bar_id_card']['type']) ? $_FILES['bar_id_card']['type'] : NULL;
                    $relation = !empty($this->request->getPost('relation')) ? $this->request->getPost('relation') : NULL;
                    $relation_name = !empty($this->request->getPost('relation_name')) ? $this->request->getPost('relation_name') : NULL;
                    $c_address = !empty($this->request->getPost('c_address')) ? $this->request->getPost('c_address') : NULL;
                    $c_city = !empty($this->request->getPost('c_city')) ? $this->request->getPost('c_city') : NULL;
                    $c_pincode = !empty($this->request->getPost('c_pincode')) ? $this->request->getPost('c_pincode') : NULL;
                    $c_state = !empty($this->request->getPost('c_state')) ? $this->request->getPost('c_state') : NULL;
                    $c_district = !empty($this->request->getPost('c_district')) ? $this->request->getPost('c_district') : NULL;
                    $r_address = !empty($this->request->getPost('r_address')) ? $this->request->getPost('r_address') : NULL;
                    $r_pincode = !empty($this->request->getPost('r_pincode')) ? $this->request->getPost('r_pincode') : NULL;
                    $r_city = !empty($this->request->getPost('r_city')) ? $this->request->getPost('r_city') : NULL;
                    $r_state = !empty($this->request->getPost('r_state')) ? $this->request->getPost('r_state') : NULL;
                    $r_district = !empty($this->request->getPost('r_district')) ? $this->request->getPost('r_district') : NULL;
                    $ext = '';
                    if (isset($file_type) && !empty($file_type)) {
                        $extarr = explode('/', $file_type);
                        $ext = !empty($extarr[1]) ? $extarr[1] : 'pdf';
                    }
                    $tmp_file_name = time() . rand() . '.' . $ext;
                    // $dir = "uploaded_docs/bar_id_card/";
                    // if (!file_exists($dir)) {
                    //     mkdir($dir, 0755, true);
                    // }
                    $data = array();
                    // if (!$this->upload->do_upload('bar_id_card')) {
                    //     setSessionData('bar_id_card_error', $this->upload->display_errors());
                    //     setSessionData('success', '');
                    //     $this->session->setFlashdata('error', 'Something went wrong! Please try again.');
                    // } else {
                    $uploadPath = 'uploaded_docs/bar_id_card/';
                    $fileName = '';
                    $file = $this->request->getFile('bar_id_card');
                    if ($file->isValid() && !$file->hasMoved()) {
                        
                        if (!is_dir($uploadPath)) {
                            mkdir($uploadPath, 0777, true);
                        }
                        $fileName = $file->getName();
                        $filePath = $uploadPath . $fileName;
                        if ($file->move($uploadPath, $fileName)) {
                            $xfile = explode('.',$fileName);
                            if($xfile != 'pdf' || $xfile != 'jpeg' || $xfile != 'jpg') {
                                unlink($filePath);
                                $this->session->setFlashdata('error', 'Please Upload Valid Bar Id Card.');
                            }
                        }
                    }
                    $userIn = array();
                    $password = generateRandomString();
                    $userIn['userid'] = $password;
                    $userIn['password'] = hash('sha256', $password);
                    $userIn['ref_m_usertype_id'] = ARGUING_COUNSEL;
                    $userIn['first_name'] = $name;
                    $userIn['moblie_number'] = $mobile;
                    $userIn['emailid'] = $email;
                    $userIn['bar_reg_no'] = $bar_reg_no;
                    $userIn['account_status'] = 0;
                    $userIn['created_on'] = date('Y-m-d H:i:s');
                    $userIn['create_ip'] = getClientIP();
                    $userIn['is_deleted'] = false;
                    $userIn['is_active'] = 1;
                    $userIn['photo_path'] = $uploadPath . $fileName;
                    $userIn['adv_sci_bar_id'] = null;
                    $userIn['m_address1'] = $c_address;
                    $userIn['m_pincode'] = $c_pincode;
                    $userIn['m_city'] = $c_city;
                    $userIn['m_state_id'] = url_decryption($c_state);
                    $userIn['m_district_id'] = url_decryption($c_district);
                    $userIn['alt_address1'] = $r_address;
                    $userIn['alt_pincode'] = $r_pincode;
                    $userIn['alt_city'] = $r_city;
                    $userIn['alt_state_id'] = url_decryption($r_state);
                    $userIn['alt_district_id'] = url_decryption($r_district);
                    $userIn['is_first_pwd_reset'] = true;
                    $table = "efil.tbl_users";
                    $insetId = $this->Citation_model->insertData($table, $userIn);
                    if (isset($insetId) && !empty($insetId)) {
                        $updateArr = array();
                        $updateArr['tbl_users_id'] = $insetId;
                        $updateArr['is_user_registered'] = true;
                        $updateArr['registered_on'] = date('Y-m-d H:i:s');
                        $updateArr['registration_code'] = NULL;
                        $updateArr['relation_type'] = $relation;
                        $updateArr['relative_name'] = $relation_name;
                        $updateArr['registered_ip'] = getClientIP();
                        $params = array();
                        $params['table_name'] = "dscr.tbl_arguing_counsels";
                        $params['whereFieldName'] = 'id';
                        $params['whereFieldValue'] = $tableId;
                        $params['updateArr'] = $updateArr;
                        // $this->load->model('common/Common_model');
                        $upRes = $this->Common_model->updateTableData($params);
                        if (isset($upRes) && !empty($upRes)) {
                            $subject = "Login credentials";
                            $email_message = 'Dear ' . $name . ', Your email is "' . $email . '" and password is "' . $password . '" <br><a href="' . base_url() . '">Click To Login</a>';
                            $sms_message = 'Dear ' . $name . ', Your email is "' . $email . '" and password is "' . $password . '" <br><a href="' . base_url() . '">Click To Login</a>';
                            setSessionData('aor_register',true);
                            send_mail_msg($email, $subject, $email_message, $to_user_name = "arguing_counsel");
                            // send_mobile_sms($mobile, $sms_message, SMS_EMAIL_API_USER);
                            setSessionData('success', 'Login credentials have been sent on email and mobile.');
                        } else {
                            setSessionData('error', 'Something went wrong! Please try again later.');
                        }
                    } else {
                        setSessionData('error', 'Something went wrong! Please try again later.');
                    }
                    setSessionData('bar_id_card_error', '');
                    setSessionData('error', '');
                    // }
                }
                $this->render('register.add_arguing_counsel', $data);
            }
        }
    }
    public function saveArguingCounselCompleteDetailsForOnlineCoping(){
        $data = array();
        if(empty(getSessionData('self_register_arguing_counsel'))  && empty(getSessionData('arguingCounselId'))){
            return redirect()->to(base_url('/'));
        }
        else if(!empty(getSessionData('self_register_arguing_counsel')) &&  (!empty(getSessionData('self_arguing_counsel')))){
            
            $success = 'Registration has been successfully completed.';
            $data['state_list'] = $this->Dropdown_list_model->get_states_list();
            $data['stateArr'] = $this->Dropdown_list_model->get_states_list();
            $this->session->setFlashdata('success', $success);
            $param['login_id'] = !empty(getSessionData('login')['id']) ? (int)getSessionData('login')['id'] : NULL;
            
            $selfArguingCounselData = $this->Register_model->getArguingDataForApproval($param);
            $data['selfArguingCounselData'] = !empty($selfArguingCounselData) ? $selfArguingCounselData : NULL;
            $this->render('register.add_arguing_counsel',$data);
        }
        else if(!empty(getSessionData('arguingCounselId')) && !empty(getSessionData('aor_register'))){
            $this->session->setFlashdata('success', 'Login credentials have been sent on email and mobile.');
            $data['state_list'] = $this->Dropdown_list_model->get_states_list();
            $data['stateArr'] = $this->Dropdown_list_model->get_states_list();
            $param['login_id'] = !empty(getSessionData('login')['id']) ? (int)getSessionData('login')['id'] : NULL;
            $selfArguingCounselData = $this->Register_model->getArguingDataForApproval($param);
            $data['selfArguingCounselData'] = !empty($selfArguingCounselData) ? $selfArguingCounselData : NULL;
            $this->render('register.add_arguing_counsel',$data);
        }
        else{
             // pr($_POST);
             
            $data['state_list'] = $this->Dropdown_list_model->get_states_list();
            $validation =  \Config\Services::validation();
            $rules=[
                'name' => 
                ["label" => 'Name',"rules" => 'trim|required|max_length[100]|min_length[3]|validate_alphacharacters'],
                'email' => 
                ["label" => 'Email',"rules" => 'trim|required|max_length[100]|valid_email'],
            'mobile' => 
                [ "label" => 'Mobile',"rules" => 'trim|required|exact_length[10]|is_natural'],
            'bar_reg_no' => 
                ["label" => 'Bar Registration No.', "rules" => 'trim'],
            'relation' => 
                ["label" => 'Relation', "rules" => 'trim|required'],
            'relation_name' => 
                ["label" => 'Relation Name',"rules" => 'trim|required|validate_alphacharacters']
            ];
            if(!empty($_POST['bar_reg_no'])){
                $rules=[ 'bar_reg_no' => 
                ["label" => 'Bar Registration No.',"rules" => 'trim|max_length[30]|min_length[3]|validate_alpha_numeric']];
            }
            if(!empty($_POST['c_address'])){
                $rules=[ 'c_address' => 
                ["label" => 'Bar Chamber address.',"rules" => 'trim|max_length[150]|min_length[3]|validate_alpha_numeric_single_double_quotes_bracket_with_special_characters']];
            }
            if(!empty($_POST['c_pincode'])){
                $rules=[ 'c_pincode' => 
                ["label" => 'Pincode',"rules" => 'trim|max_length[6]|is_natural']];
            }
            if(!empty($_POST['c_city'])){
                $rules=[ 'c_city' => 
                [ "label" => 'City', "rules" => 'trim|max_length[35]|validate_alpha_numeric_single_double_quotes_bracket_with_special_characters']];
            }
            if(!empty($_POST['c_state'])){
                $rules=[ 'c_state' => 
                [ "label" => 'State',"rules" => 'trim|required']];
            }
            if(!empty($_POST['c_district'])){
                $rules=[ 'c_district' => 
                ["label" => 'District',"rules" => 'trim|required']];
            }
            if(!empty($_POST['r_address'])){
                $rules=[ 'r_address' => 
                ["label" => 'Bar Chamber address.',"rules" => 'trim|max_length[150]|min_length[3]|validate_alpha_numeric_single_double_quotes_bracket_with_special_characters']];
            }
            if(!empty($_POST['r_pincode'])){
                $rules=[ 'r_pincode' => 
                [ "label" => 'Pincode',"rules" => 'trim|max_length[6]|is_natural']];
            }
            if(!empty($_POST['r_city'])){
                $rules=[ 'r_city' => 
                [ "label" => 'City',"rules" => 'trim|max_length[35]|validate_alpha_numeric_single_double_quotes_bracket_with_special_characters']];
            }
            if(!empty($_POST['r_state'])){
                $rules=[ 'r_state' => 
                [ "label" => 'State',"rules" => 'trim|required']];
            }
            if(!empty($_POST['r_district'])){
                $rules=[ 'r_district' => 
                [ "label" => 'District',"rules" => 'trim|required']];
            }
            if(!empty($_POST['aor'])){
                $rules=[ 'aor' => 
                [ "label" => 'AOR',"rules" => 'trim|required']];
            }
            if(empty($_FILES['bar_id_card']['name']))
            {
                $rules=[ 'bar_id_card' => 
                ["label" => 'Bar Id Card', "rules" => 'required']];
            }
            $arguingCounselId  = !empty(getSessionData('arguingCounselId')) ? trim(getSessionData('arguingCounselId')) : NULL;
            if(isset($arguingCounselId) && !empty($arguingCounselId)) {
                $arguingCounselDetails = $this->getArguingCounselDetails($arguingCounselId);
                $data['arguingCounselDetails'] = !empty($arguingCounselDetails) ? $arguingCounselDetails[0] : NULL;
            }
            if (!empty(getSessionData('self_register_arguing_counsel')) && getSessionData('self_register_arguing_counsel') == true) {
                $params = array();
                $params['ref_m_usertype_id'] = 1;
                if($_SERVER['HTTP_HOST'] == '127.0.0.1')
                    $params['userid'] = 1777;
                else
                    $params['userid'] = null;
                $aorList = $this->Register_model->getAorData($params);
                $data['stateArr'] = $this->Dropdown_list_model->get_states_list();
                $param['login_id'] = !empty(getSessionData('login')['id']) ? (int)getSessionData('login')['id'] : NULL;
                $selfArguingCounselData = $this->Register_model->getArguingDataForApproval($param);
                $data['selfArguingCounselData'] = !empty($selfArguingCounselData) ? $selfArguingCounselData : NULL;
                $data['aorList'] = !empty($aorList) ? $aorList : NULL;
            }
            if ($this->validate($rules) === FALSE && !empty($_POST)) {
                $data['validation'] = $this->validator; 
                $data['state_list'] = $this->Dropdown_list_model->get_states_list();
                $data['stateArr'] = $this->Dropdown_list_model->get_states_list();
                $params = array();
                $params['ref_m_usertype_id'] = 1;
                if($_SERVER['HTTP_HOST'] == '127.0.0.1')
                    $params['userid'] = 1777;
                else
                    $params['userid'] = null;
                $aorList = $this->Register_model->getAorData($params);
                $data['aorList'] = !empty($aorList) ? $aorList : NULL;
                $param['login_id'] = !empty(getSessionData('login')['id']) ? (int)getSessionData('login')['id'] : NULL;
                $selfArguingCounselData = $this->Register_model->getArguingDataForApproval($param);
                $data['selfArguingCounselData'] = !empty($selfArguingCounselData) ? $selfArguingCounselData : NULL;
                return $this->render('register.add_arguing_counsel',$data); 
            }
            if ($validation->withRequest($this->request)->run() === FALSE) {
                $data['state_list'] = $this->Dropdown_list_model->get_states_list();
                $data['stateArr'] = $this->Dropdown_list_model->get_states_list();
                $params = array();
                $params['ref_m_usertype_id'] = 1;
                if($_SERVER['HTTP_HOST'] == '127.0.0.1')
                    $params['userid'] = 1777;
                else
                    $params['userid'] = null;
                $aorList = $this->Register_model->getAorData($params);
                $data['aorList'] = !empty($aorList) ? $aorList : NULL;
                $param['login_id'] = !empty(getSessionData('login')['id']) ? (int)getSessionData('login')['id'] : NULL;
                $selfArguingCounselData = $this->Register_model->getArguingDataForApproval($param);
                $data['selfArguingCounselData'] = !empty($selfArguingCounselData) ? $selfArguingCounselData : NULL;
                return $this->render('register.add_arguing_counsel',$data);
            }
             else{
                if($this->request->getPost('isOnlineCopying')){
                    $clientIP = getClientIP();
                    $new_name = "";
                    $allowedExts = array("webm");
                    $temp = explode(".", $_FILES["video"]["name"]);
                    $extension = end($temp);
                    $array = [];
                    $mime = mime_content_type($_FILES["video"]["tmp_name"]);//file name
                    $allowed = array("video/webm","webm", "video/x-matroska");
                    $file_name = $_FILES['video']['name'];
                    $file_type = $_FILES['video']['type'];
        
                    $fileExtension = pathinfo($_FILES['video']['name'], PATHINFO_EXTENSION);
        
                    if (empty($_FILES["video"]["name"])) {
                        $array = array('status' => 'Empty file can not be uploaded');
                    } 
                    else if ($_FILES["video"]["error"] > 0) {
                        $error_code = "Issue at Server/Error Return Code: " . $_FILES["video"]["error"];
                        $array = array('status' => $error_code);
                    }
                     else if ($fileExtension != "webm") {
                        $array = array('status' => 'Only Valid Video file allowed');
                    } else if ($_FILES["video"]["size"] > 12000000) {
                        $array = array('status' => 'Not more then 12 mb allowed');
                    } 
                    else if (!(in_array($extension, $allowedExts) && $_FILES["video"]["type"] == "video/webm")) {
                        $array = array('status' => 'Only Valid file allowed');
                    } 
                    else {
                        //$master_to_path = $_SERVER['DOCUMENT_ROOT'] . "/copy_verify/attachments/video/";
                        $master_to_path = SAN_ROOT."SAN_eCopy/copy_verify/attachments/video";
        
                        //$master_to_path ="/var/www/html/public/SAN_eCopy/copy_verify/attachments/video/";
                        //$master_to_path='uploaded_docs';
                        if (!file_exists($master_to_path)) {
                            mkdir($master_to_path, 777, true);
                        }
                        chdir($master_to_path);
                        getcwd();
        
                        $new_name = md5(uniqid(rand(), TRUE)) . '.' . $extension;
                        $new_name = $master_to_path . "/" . $new_name;
                        if (file_exists($new_name)) {
                            $array = array('status' => 'Sorry File already exist.');
                        } else {
                            $results=$this->ecoping_webservices->getUserAssets($this->request->getPost('mobile'),$this->request->getPost('email'));
                            if (count($results) > 0) {
                                $array = array('status' => 'Records Already Available');
                            }
                            else{
                                //$uploadPath = 'uploaded_docs/';
                                $file = $this->request->getFile('video');
                                if (file_put_contents($new_name,file_get_contents($_FILES["video"]["tmp_name"]))) {
                                    
                                    /*if (!is_dir($uploadPath)) {
                                        mkdir($uploadPath, 0777, true);
                                    }*/
                                    $fileName = $file->getName();
                                    //$filePath = $uploadPath . time().$fileName;
                                    $asset_array = array("mobile" => $this->request->getPost('mobile'),
                                                "email" => $this->request->getPost('email'),
                                                "asset_type" => '3',
                                                "id_proof_type" => '0',
                                                "diary_no" => '0',
                                                "video_random_text" => $_SESSION['text_speak'],
                                                "file_path" => base_url().''.str_replace(SAN_ROOT,'',$new_name),
                                                "entry_time_ip" => $clientIP);
                                        
                                        $insert_user_asset = $this->ecoping_webservices->insert_user_assets($asset_array); //insert user assets
                                        $json_insert_user_asset =$insert_user_asset;
                                        
                                        if ($json_insert_user_asset->{'Status'} == "success") {
                                            $array = array('status' => 'success');
                                            $_SESSION['video_verify_status'] = 1;
                                        }
                                    else{
                                        $this->session->setFlashdata('error', 'Unable to add in user assets');
                                            
                                        }
                                }

                                $name =  !empty($this->request->getPost('name')) ? $this->request->getPost('name') : NULL;
                                $mobile = !empty(getSessionData('adv_details')['mobile_no']) ? getSessionData('adv_details')['mobile_no'] : NULL;
                                $email = !empty(getSessionData('adv_details')['email_id']) ? getSessionData('adv_details')['email_id'] : NULL;
                                $bar_reg_no = !empty($this->request->getPost('bar_reg_no')) ? $this->request->getPost('bar_reg_no') : NULL;
                                $file_type = !empty($_FILES['bar_id_card']['type']) ? $_FILES['bar_id_card']['type'] : NULL;
                                $relation = !empty($this->request->getPost('relation')) ? $this->request->getPost('relation') : NULL;
                                $relation_name = !empty($this->request->getPost('relation_name')) ? $this->request->getPost('relation_name') : NULL;
                                $c_address = !empty($this->request->getPost('c_address')) ? $this->request->getPost('c_address') : NULL;
                                $c_city = !empty($this->request->getPost('c_city')) ? $this->request->getPost('c_city') : NULL;
                                $c_pincode = !empty($this->request->getPost('c_pincode')) ? $this->request->getPost('c_pincode') : NULL;
                                $c_state = !empty($this->request->getPost('c_state')) ? $this->request->getPost('c_state') : NULL;
                                $c_district = !empty($this->request->getPost('c_district')) ? $this->request->getPost('c_district') : NULL;
                                $r_address = !empty($this->request->getPost('r_address')) ? $this->request->getPost('r_address') : NULL;
                                $r_pincode = !empty($this->request->getPost('r_pincode')) ? $this->request->getPost('r_pincode') : NULL;
                                $r_city = !empty($this->request->getPost('r_city')) ? $this->request->getPost('r_city') : NULL;
                                $r_state = !empty($this->request->getPost('r_state')) ? $this->request->getPost('r_state') : NULL;
                                $r_district = !empty($this->request->getPost('r_district')) ? $this->request->getPost('r_district') : NULL;
                                $aor = !empty($this->request->getPost('aor')) ? $this->request->getPost('aor') : NULL;
                                $ext = '';
                                if (isset($file_type) && !empty($file_type)) {
                                    $extarr = explode('/', $file_type);
                                    $ext = !empty($extarr[1]) ? $extarr[1] : 'pdf';
                                }
                                $tmp_file_name = time() . rand() . '.' . $ext;
                                $file = $this->request->getFile('bar_id_card');
                                //pr($file);
                                if ($file->isValid() && !$file->hasMoved()) {
                                    $uploadPath = 'uploaded_docs/bar_id_card/';
                                    if (!is_dir($uploadPath)) {
                                        //chmod($uploadPath, 0777);
                                        mkdir($uploadPath, 0777,true);
                                    }
                                    $fileName = $file->getName();
                                    $filePath = $uploadPath . $fileName;
                                    if ($file->move($uploadPath, $fileName)) {
                                        $xfile = explode('.',$fileName)[1];
                                        $filearr = array('pdf','jpeg','jpg');
                                        if(!in_array($xfile, $filearr)) {
                                            unlink($filePath);
                                            $this->session->setFlashdata('error', 'Please Upload Valid Bar Id Card.');
                                            exit;
                                        }
                                    }
                                }
                                //pr($file);
                                $userIn = array();
                                $userid = generateRandomString();
                                $one_time_password = generateRandomString();
                                $password = hash('sha256',$one_time_password);
                                $userIn['userid'] = $userid;
                                $userIn['password'] = $password;
                                $userIn['ref_m_usertype_id'] = ARGUING_COUNSEL;
                                $userIn['first_name'] = $name;
                                $userIn['moblie_number'] = $mobile;
                                $userIn['emailid'] = $email;
                                $userIn['bar_reg_no'] = $bar_reg_no;
                                $userIn['account_status'] = 0;
                                $userIn['created_on'] = date('Y-m-d H:i:s');
                                $userIn['create_ip'] = getClientIP();
                                $userIn['is_deleted'] = true;
                                $userIn['is_active'] = 0;
                                $userIn['photo_path'] = $uploadPath . $fileName;
                                $userIn['adv_sci_bar_id'] = null;
                                $userIn['m_address1'] = $c_address;
                                $userIn['m_pincode'] = $c_pincode;
                                $userIn['m_city'] = $c_city;
                                $userIn['m_state_id'] = url_decryption($c_state);
                                $userIn['m_district_id'] = url_decryption($c_district);
                                $userIn['alt_address1'] = $r_address;
                                $userIn['alt_pincode'] = $r_pincode;
                                $userIn['alt_city'] = $r_city;
                                $userIn['alt_state_id'] = url_decryption($r_state);
                                $userIn['alt_district_id'] = url_decryption($r_district);
                                $userIn['is_first_pwd_reset'] = true;
                                // $this->load->model('citation/Citation_model');
                                $table = "efil.tbl_users";
                                //pr($userIn);
                                $insetId = $this->Citation_model->insertData($table, $userIn);
                                
                                if (isset($insetId) && !empty($insetId)) {
                                    $dataToSave=array();
                                    $dataToSave['advocate_name'] = $name;
                                    $dataToSave['mobile_number']= $mobile;
                                    $dataToSave['emailid'] = $email;
                                    $dataToSave['bar_reg_no']= $bar_reg_no;
                                    $dataToSave['relation_type'] = $relation;
                                    $dataToSave['relative_name'] = $relation_name;
                                    $dataToSave['tbl_users_id'] = $insetId;
                                    $dataToSave['is_deleted'] = true;
                                    $dataToSave['account_status']= 1;
                                    $dataToSave['created_on']= date('Y-m-d H:i:s');
                                    $dataToSave['registered_on'] = date('Y-m-d H:i:s');
                                    $dataToSave['created_by']= NULL;
                                    $dataToSave['created_ip'] = get_client_ip();
                                    $dataToSave['registered_ip'] = get_client_ip();
                                    $dataToSave['registration_code'] = NULL;
                                    $dataToSave['approving_user_id']= $aor;
                                    $dataToSave['approved_on'] = NULL;
                                    $dataToSave['approved_ip'] = NULL;
                                    $dataToSave['is_pre_approved']= FALSE;
                                    $dataToSave['is_user_registered'] = true;
                                    // $this->load->model('citation/Citation_model');
                                    $tablex = "dscr.tbl_arguing_counsels";
                                    if(!empty($name) && !empty($email) && !empty($mobile)){
                                        $insetIdArguingCounsel = $this->Citation_model->insertData($tablex,$dataToSave);
                                        if(isset($insetIdArguingCounsel) && !empty($insetIdArguingCounsel)){
                                            $success = 'Registration has been successfully completed.';
                                            $this->session->setFlashdata('msg_success', $success); 
                                            
                                            setSessionData('self_arguing_counsel', true);
                                            $subject = "Login credentials";
                                            $email_message = 'Dear ' . $name . ', Your email is "' . $email . '" and password is "' . $one_time_password . '" <br><a href="' . base_url() . '">Click To Login</a>';
                                            $sms_message = 'Your Login Password of eFiling ' . $one_time_password . ' - SUPREME COURT OF INDIA';
                                            send_mail_msg($email, $subject, $email_message, $to_user_name = "arguing_counsel");
                                            return $this->render('register.add_arguing_counsel', $data);
                                        } else {
                                            $this->session->setFlashdata('error', 'Something went wrong! Please try again later.');
                                        }
                                    }
                                    else {
                                        $this->session->setFlashdata('error', 'Something went wrong! Please try again later.');
                                    }
                                } else {
                                    $this->session->setFlashdata('error', 'Something went wrong! Please try again later.');
                                }
                                setSessionData('bar_id_card_error', '');
                                setSessionData('error', '');
                                 
                            }
                        }
                    }
        
                }
                //recorded video video uoploaded end
                
                if (!empty(getSessionData('self_register_arguing_counsel')) && getSessionData('self_register_arguing_counsel') == true)
                {
                    $name =  !empty($this->request->getPost('name')) ? $this->request->getPost('name') : NULL;
                    $mobile = !empty(getSessionData('adv_details')['mobile_no']) ? getSessionData('adv_details')['mobile_no'] : NULL;
                    $email = !empty(getSessionData('adv_details')['email_id']) ? getSessionData('adv_details')['email_id'] : NULL;
                    $bar_reg_no = !empty($this->request->getPost('bar_reg_no')) ? $this->request->getPost('bar_reg_no') : NULL;
                    $file_type = !empty($_FILES['bar_id_card']['type']) ? $_FILES['bar_id_card']['type'] : NULL;
                    $relation = !empty($this->request->getPost('relation')) ? $this->request->getPost('relation') : NULL;
                    $relation_name = !empty($this->request->getPost('relation_name')) ? $this->request->getPost('relation_name') : NULL;
                    $c_address = !empty($this->request->getPost('c_address')) ? $this->request->getPost('c_address') : NULL;
                    $c_city = !empty($this->request->getPost('c_city')) ? $this->request->getPost('c_city') : NULL;
                    $c_pincode = !empty($this->request->getPost('c_pincode')) ? $this->request->getPost('c_pincode') : NULL;
                    $c_state = !empty($this->request->getPost('c_state')) ? $this->request->getPost('c_state') : NULL;
                    $c_district = !empty($this->request->getPost('c_district')) ? $this->request->getPost('c_district') : NULL;
                    $r_address = !empty($this->request->getPost('r_address')) ? $this->request->getPost('r_address') : NULL;
                    $r_pincode = !empty($this->request->getPost('r_pincode')) ? $this->request->getPost('r_pincode') : NULL;
                    $r_city = !empty($this->request->getPost('r_city')) ? $this->request->getPost('r_city') : NULL;
                    $r_state = !empty($this->request->getPost('r_state')) ? $this->request->getPost('r_state') : NULL;
                    $r_district = !empty($this->request->getPost('r_district')) ? $this->request->getPost('r_district') : NULL;
                    $aor = !empty($this->request->getPost('aor')) ? $this->request->getPost('aor') : NULL;
                    $ext = '';
                    if (isset($file_type) && !empty($file_type)) {
                        $extarr = explode('/', $file_type);
                        $ext = !empty($extarr[1]) ? $extarr[1] : 'pdf';
                    }
                    $tmp_file_name = time() . rand() . '.' . $ext;
                    $file = $this->request->getFile('bar_id_card');
                    if ($file->isValid() && !$file->hasMoved()) {
                        $uploadPath = 'uploaded_docs/bar_id_card/';
                        if (!is_dir($uploadPath)) {
                            //chmod($uploadPath, 0777);
                            mkdir($uploadPath, 0777,true);
                        }
                        $fileName = $file->getName();
                        $filePath = $uploadPath . $fileName;
                        if ($file->move($uploadPath, $fileName)) {
                            $xfile = explode('.',$fileName)[1];
                            $filearr = array('pdf','jpeg','jpg');
                            if(!in_array($xfile, $filearr)) {
                                unlink($filePath);
                                $this->session->setFlashdata('error', 'Please Upload Valid Bar Id Card.');
                            }
                        }
                    }
                    $userIn = array();
                    $userid = generateRandomString();
                    $one_time_password = generateRandomString();
                    $password = hash('sha256',$one_time_password);
                    $userIn['userid'] = $userid;
                    $userIn['password'] = $password;
                    $userIn['ref_m_usertype_id'] = ARGUING_COUNSEL;
                    $userIn['first_name'] = $name;
                    $userIn['moblie_number'] = $mobile;
                    $userIn['emailid'] = $email;
                    $userIn['bar_reg_no'] = $bar_reg_no;
                    $userIn['account_status'] = 0;
                    $userIn['created_on'] = date('Y-m-d H:i:s');
                    $userIn['create_ip'] = getClientIP();
                    $userIn['is_deleted'] = true;
                    $userIn['is_active'] = 0;
                    $userIn['photo_path'] = $uploadPath . $fileName;
                    $userIn['adv_sci_bar_id'] = null;
                    $userIn['m_address1'] = $c_address;
                    $userIn['m_pincode'] = $c_pincode;
                    $userIn['m_city'] = $c_city;
                    $userIn['m_state_id'] = url_decryption($c_state);
                    $userIn['m_district_id'] = url_decryption($c_district);
                    $userIn['alt_address1'] = $r_address;
                    $userIn['alt_pincode'] = $r_pincode;
                    $userIn['alt_city'] = $r_city;
                    $userIn['alt_state_id'] = url_decryption($r_state);
                    $userIn['alt_district_id'] = url_decryption($r_district);
                    $userIn['is_first_pwd_reset'] = true;
                    // $this->load->model('citation/Citation_model');
                    $table = "efil.tbl_users";
                    $insetId = $this->Citation_model->insertData($table, $userIn);
                    if (isset($insetId) && !empty($insetId)) {
                        $dataToSave=array();
                        $dataToSave['advocate_name'] = $name;
                        $dataToSave['mobile_number']= $mobile;
                        $dataToSave['emailid'] = $email;
                        $dataToSave['bar_reg_no']= $bar_reg_no;
                        $dataToSave['relation_type'] = $relation;
                        $dataToSave['relative_name'] = $relation_name;
                        $dataToSave['tbl_users_id'] = $insetId;
                        $dataToSave['is_deleted'] = true;
                        $dataToSave['account_status']= 1;
                        $dataToSave['created_on']= date('Y-m-d H:i:s');
                        $dataToSave['registered_on'] = date('Y-m-d H:i:s');
                        $dataToSave['created_by']= NULL;
                        $dataToSave['created_ip'] = get_client_ip();
                        $dataToSave['registered_ip'] = get_client_ip();
                        $dataToSave['registration_code'] = NULL;
                        $dataToSave['approving_user_id']= $aor;
                        $dataToSave['approved_on'] = NULL;
                        $dataToSave['approved_ip'] = NULL;
                        $dataToSave['is_pre_approved']= FALSE;
                        $dataToSave['is_user_registered'] = true;
                        // $this->load->model('citation/Citation_model');
                        $table = "dscr.tbl_arguing_counsels";
                        if(!empty($name) && !empty($email) && !empty($mobile)){
                            $insetIdArguingCounsel = $this->Citation_model->insertData($table,$dataToSave);
                            if(isset($insetIdArguingCounsel) && !empty($insetIdArguingCounsel)){
                                $success = 'Registration has been successfully completed.';
                                $this->session->setFlashdata('success', $success); 
                                $this->session->setFlashdata('msg_success', $success); 
                                
                                setSessionData('self_arguing_counsel', true);
                            } else {
                                $this->session->setFlashdata('error', 'Something went wrong! Please try again later.');
                            }
                        }
                        else {
                            $this->session->setFlashdata('error', 'Something went wrong! Please try again later.');
                        }
                    } else {
                        $this->session->setFlashdata('error', 'Something went wrong! Please try again later.');
                    }
                    setSessionData('bar_id_card_error', '');
                    setSessionData('error', '');
                } else {
                    $name = !empty($arguingCounselDetails[0]['advocate_name']) ? $arguingCounselDetails[0]['advocate_name'] : NULL;
                    $mobile = !empty($arguingCounselDetails[0]['mobile_number']) ? $arguingCounselDetails[0]['mobile_number'] : NULL;
                    $email = !empty($arguingCounselDetails[0]['emailid']) ? $arguingCounselDetails[0]['emailid'] : NULL;
                    $tableId = !empty($arguingCounselDetails[0]['id']) ? $arguingCounselDetails[0]['id'] : NULL;
                    $bar_reg_no = !empty($this->request->getPost('bar_reg_no')) ? $this->request->getPost('bar_reg_no') : NULL;
                    $file_type = !empty($_FILES['bar_id_card']['type']) ? $_FILES['bar_id_card']['type'] : NULL;
                    $relation = !empty($this->request->getPost('relation')) ? $this->request->getPost('relation') : NULL;
                    $relation_name = !empty($this->request->getPost('relation_name')) ? $this->request->getPost('relation_name') : NULL;
                    $c_address = !empty($this->request->getPost('c_address')) ? $this->request->getPost('c_address') : NULL;
                    $c_city = !empty($this->request->getPost('c_city')) ? $this->request->getPost('c_city') : NULL;
                    $c_pincode = !empty($this->request->getPost('c_pincode')) ? $this->request->getPost('c_pincode') : NULL;
                    $c_state = !empty($this->request->getPost('c_state')) ? $this->request->getPost('c_state') : NULL;
                    $c_district = !empty($this->request->getPost('c_district')) ? $this->request->getPost('c_district') : NULL;
                    $r_address = !empty($this->request->getPost('r_address')) ? $this->request->getPost('r_address') : NULL;
                    $r_pincode = !empty($this->request->getPost('r_pincode')) ? $this->request->getPost('r_pincode') : NULL;
                    $r_city = !empty($this->request->getPost('r_city')) ? $this->request->getPost('r_city') : NULL;
                    $r_state = !empty($this->request->getPost('r_state')) ? $this->request->getPost('r_state') : NULL;
                    $r_district = !empty($this->request->getPost('r_district')) ? $this->request->getPost('r_district') : NULL;
                    $ext = '';
                    if (isset($file_type) && !empty($file_type)) {
                        $extarr = explode('/', $file_type);
                        $ext = !empty($extarr[1]) ? $extarr[1] : 'pdf';
                    }
                    $tmp_file_name = time() . rand() . '.' . $ext;
                    // $dir = "uploaded_docs/bar_id_card/";
                    // if (!file_exists($dir)) {
                    //     mkdir($dir, 0755, true);
                    // }
                    $data = array();
                    // if (!$this->upload->do_upload('bar_id_card')) {
                    //     setSessionData('bar_id_card_error', $this->upload->display_errors());
                    //     setSessionData('success', '');
                    //     $this->session->setFlashdata('error', 'Something went wrong! Please try again.');
                    // } else {
                    $uploadPath = 'uploaded_docs/bar_id_card/';
                    $fileName = '';
                    $file = $this->request->getFile('bar_id_card');
                    if ($file->isValid() && !$file->hasMoved()) {
                        
                        if (!is_dir($uploadPath)) {
                            mkdir($uploadPath, 0777, true);
                        }
                        $fileName = $file->getName();
                        $filePath = $uploadPath . $fileName;
                        if ($file->move($uploadPath, $fileName)) {
                            $xfile = explode('.',$fileName)[1];
                            $filearr = array('pdf','jpeg','jpg');
                            if(!in_array($xfile, $filearr)) {
                                unlink($filePath);
                                $this->session->setFlashdata('error', 'Please Upload Valid Bar Id Card.');
                            }
                        }
                    }
                    $userIn = array();
                    $userid = generateRandomString();
                    $one_time_password = generateRandomString();
                    $password = hash('sha256',$one_time_password);
                    $userIn['userid'] = $userid;
                    $userIn['password'] = $password;
                    $userIn['ref_m_usertype_id'] = ARGUING_COUNSEL;
                    $userIn['first_name'] = $name;
                    $userIn['moblie_number'] = $mobile;
                    $userIn['emailid'] = $email;
                    $userIn['bar_reg_no'] = $bar_reg_no;
                    $userIn['account_status'] = 0;
                    $userIn['created_on'] = date('Y-m-d H:i:s');
                    $userIn['create_ip'] = getClientIP();
                    $userIn['is_deleted'] = false;
                    $userIn['is_active'] = 1;
                    $userIn['photo_path'] = $uploadPath . $fileName;
                    $userIn['adv_sci_bar_id'] = null;
                    $userIn['m_address1'] = $c_address;
                    $userIn['m_pincode'] = $c_pincode;
                    $userIn['m_city'] = $c_city;
                    $userIn['m_state_id'] = url_decryption($c_state);
                    $userIn['m_district_id'] = url_decryption($c_district);
                    $userIn['alt_address1'] = $r_address;
                    $userIn['alt_pincode'] = $r_pincode;
                    $userIn['alt_city'] = $r_city;
                    $userIn['alt_state_id'] = url_decryption($r_state);
                    $userIn['alt_district_id'] = url_decryption($r_district);
                    $table = "efil.tbl_users";
                    $insetId = $this->Citation_model->insertData($table, $userIn);
                    if (isset($insetId) && !empty($insetId)) {
                        $updateArr = array();
                        $updateArr['tbl_users_id'] = $insetId;
                        $updateArr['is_user_registered'] = true;
                        $updateArr['registered_on'] = date('Y-m-d H:i:s');
                        $updateArr['registration_code'] = NULL;
                        $updateArr['relation_type'] = $relation;
                        $updateArr['relative_name'] = $relation_name;
                        $updateArr['registered_ip'] = getClientIP();
                        $params = array();
                        $params['table_name'] = "dscr.tbl_arguing_counsels";
                        $params['whereFieldName'] = 'id';
                        $params['whereFieldValue'] = $tableId;
                        $params['updateArr'] = $updateArr;
                        // $this->load->model('common/Common_model');
                        $upRes = $this->Common_model->updateTableData($params);
                        if (isset($upRes) && !empty($upRes)) {
                            $subject = "Login credentials";
                            $email_message = 'Dear ' . $name . ', Your email is "' . $email . '" and password is "' . $one_time_password . '" <br><a href="' . base_url() . '">Click To Login</a>';
                            $sms_message = 'Dear ' . $name . ', Your email is "' . $email . '" and password is "' . $one_time_password . '" <br><a href="' . base_url() . '">Click To Login</a>';
                            setSessionData('aor_register',true);
                            send_mail_msg($email, $subject, $email_message, $to_user_name = "arguing_counsel");
                            // send_mobile_sms($mobile, $sms_message, SMS_EMAIL_API_USER);
                            setSessionData('success', 'Login credentials have been sent on email and mobile.');
                            setSessionData('msg_success', 'Login credentials have been sent on email and mobile.');
                        } else {
                            setSessionData('error', 'Something went wrong! Please try again later.');
                        }
                    } else {
                        setSessionData('error', 'Something went wrong! Please try again later.');
                    }
                    setSessionData('bar_id_card_error', '');
                    setSessionData('error', '');
                    // }
                }
                $this->render('register.add_arguing_counsel', $data);
            }
        }
    }
    private function getArguingCounselDetails($id,$registration_code=""){
        //get all details which are saved earlier by AOR by id which is sent in weblink after encryption
        $arr = array();
        $arr['id'] = !empty($id) ? $id : NULL;
        $arr['registration_code'] = !empty($registration_code) ? $registration_code : NULL;
        $result = $this->Register_model->getArguingCounselData($arr);
        return $result;
    }
    public function matchRegistrationCode(){
        $data= array();
        // $this->load->model('newcase/Dropdown_list_model');
        $data['state_list'] = $this->Dropdown_list_model->get_states_list();
        $arguingCounselId  = !empty(getSessionData('arguingCounselId')) ? trim(getSessionData('arguingCounselId')) : NULL;
        $arguingCounselDetails=$this->getArguingCounselDetails($arguingCounselId);
        $db_registration_code = !empty($arguingCounselDetails[0]['registration_code']) ? $arguingCounselDetails[0]['registration_code'] : NULL ;
        $post_registration_code = !empty($this->request->getPost('registration_code')) ? trim($this->request->getPost('registration_code')) : NULL;

        if(isset($db_registration_code) && !empty($db_registration_code) && isset($post_registration_code) && !empty($post_registration_code) &&
            $post_registration_code == $db_registration_code){
            $this->session->setFlashdata('success','Registration code matched.');
            $data['arguingCounselDetails'] = !empty($arguingCounselDetails) ? $arguingCounselDetails[0] : NULL;
            $params = array();
            $params['ref_m_usertype_id'] = 1;
            if($_SERVER['HTTP_HOST'] == '127.0.0.1')
                $params['userid'] = 1779;
            else
                $params['userid'] = null;
            $aorList = $this->Register_model->getAorData($params);
            $data['aorList'] = !empty($aorList) ? $aorList : NULL;
            $this->render('register.add_arguing_counsel',$data);
        }
        else{
            $this->session->setFlashdata('error','Registration code mismatched, Please try again.');
            return redirect()->back();
            // redirect($_SERVER['HTTP_REFERER'], 'refresh');
        }
    }
    public function resendRegistrationCode(){}

    public function landArguingCounsel($encryptedData){
        if(strlen($encryptedData) > 1){
            // $key = config('App')->encryptionKey;
            $encryptedData = base64_decode($encryptedData);
            $encrypter = service('encrypter');
            $decryptedData = stringDecreption($encryptedData);
            // $key = $this->config->item('encryption_key');
            // $data = json_decode($this->encrypt->decode($encryptedData, $key));
            $data = json_decode($decryptedData);
            $arr = array();
            if(!empty($data)){
                $arguingCounselDetails=$this->getArguingCounselDetails($data->id,$data->registration_code);
                if(isset($arguingCounselDetails) && !empty($arguingCounselDetails)){
                    // Open page for asking registration code which was sent earlier on sms to advocate
                    $arr['arguingCounselDetails'] = !empty($arguingCounselDetails) ? $arguingCounselDetails[0] : NULL;
                    setSessionData('arguingCounselId',$data->id);
                } else{
                    // Invalid attempt
                    $arr['arguingCounselDetails'] = array();
                }
                $this->render('register.verify_registration_code',$arr);
            }
        }else{
            return redirect()->to(base_url('/'));
        }
    }

    public function approveRejectedArguingCounsel(){
        if (getSessionData('login')['ref_m_usertype_id'] != USER_ADVOCATE){
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $output = array();
        $errorArr = array();
        $successArr = array();
        $postData = $_POST;
        $type = !empty($postData['type']) ? trim($postData['type']) : NULL;
        $userIdArr = !empty($postData['userIdArr']) ? $postData['userIdArr'] : NULL;
        // $this->load->model('common/Common_model');
        if(isset($type) && !empty($type) && isset($userIdArr) && !empty($userIdArr) && count($userIdArr)>0){
            $arr = array();
            $arr['userId'] = $userIdArr;
            $userDetails = $this->Register_model->getUserDetails($arr);
            $userData = array();
            if(isset($userDetails) && !empty($userDetails)){
                foreach ($userDetails as $k=>$v){
                    $userData[$v['id']] = $v['first_name'].'#'.$v['emailid'].'#'.$v['moblie_number'];
                }
            }
            switch ($type){
                case 'approved':
                    foreach ($userIdArr as $id){
                        $updateId = (int)trim($id);
                        $params = array();
                        $userTable = array();
                        // $password = generateRandomString();
                        // $userTable['password'] = hash('sha256', $password);
                        $userTable['is_active'] = '1';
                        $userTable['is_deleted'] = false;
                        $userTable['updated_on'] = date('Y-m-d H:i:s');
                        $userTable['update_ip'] = getClientIP();
                        $params['table_name'] = "efil.tbl_users";
                        $params['whereFieldName'] = 'id';
                        $params['whereFieldValue'] = $updateId;
                        $params['updateArr'] = $userTable;
                        $upUserTable = $this->Common_model->updateTableData($params);
                        if(isset($upUserTable) && !empty($upUserTable)){
                            $arguingCounselTable = array();
                            $arguingCounselTable['created_by'] = !empty(getSessionData('login')['id']) ? (int)getSessionData('login')['id'] : NULL;
                            $arguingCounselTable['is_user_registered'] = true;
                            $arguingCounselTable['approved_on'] = date('Y-m-d H:i:s');
                            $arguingCounselTable['approved_ip'] = getClientIP();
                            $arguingCounselTable['account_status'] = 1;
                            $arguingCounselTable['is_deleted'] = false;
                            $arguingArr = array();
                            $arguingArr['table_name'] = "dscr.tbl_arguing_counsels";
                            $arguingArr['whereFieldName'] = 'tbl_users_id';
                            $arguingArr['whereFieldValue'] =$updateId;
                            $arguingArr['updateArr'] = $arguingCounselTable;
                            $uparguingCounselTable = $this->Common_model->updateTableData($arguingArr);
                            if(isset($uparguingCounselTable) && !empty($uparguingCounselTable)){
                                $name = '';
                                $email ='';
                                $mobile ='';
                                if(isset($userData) && !empty($userData) && isset($updateId) && !empty($updateId)){
                                    if(array_key_exists($updateId,$userData)){
                                        $currentData = $userData[$updateId];
                                        if(isset($currentData) && !empty($currentData)){
                                            $currentDataArr = explode('#',$currentData);
                                            $name = !empty($currentDataArr[0]) ? $currentDataArr[0] : '';
                                            $email = !empty($currentDataArr[1]) ? $currentDataArr[1] : '';
                                            $mobile = !empty($currentDataArr[2]) ? $currentDataArr[2] : '';
                                        }
                                    }
                                
                                $subject = "Login credentials";
                                // $email_message = 'Dear ' . $name . ', Your email is "' . $email . '" and password is "' . $password . '" <br><a href="' . base_url() . '">Click To Login</a>';
                                // $sms_message = 'Your Login Password of eFiling ' . $password . ' - SUPREME COURT OF INDIA';
                                // send_mail_msg($email, $subject, $email_message, $to_user_name = "arguing_counsel");
                                // send_mobile_sms($mobile, $sms_message);
                                }
                                array_push($successArr,$id);
                            }
                            else{
                                array_push($errorArr,$id);
                            }
                        }
                        else{
                            array_push($errorArr,$id);
                        }
                    }
                    $output['message'] = 'Advocate has been '.$type.' successfully! success total(s): '.count($successArr).' and error total(s): '.count($errorArr);
                    $output['success'] = "success";
                 break;
                case 'rejected':
                    foreach ($userIdArr as $id){
                        $updateId = (int)trim($id);
                        $arguingCounselTable = array();
                        $arguingCounselTable['created_by'] = !empty(getSessionData('login')['id']) ? (int)getSessionData('login')['id'] : NULL;
                        $arguingCounselTable['account_status'] = 0;
                        $arguingArr = array();
                        $arguingArr['table_name'] = "dscr.tbl_arguing_counsels";
                        $arguingArr['whereFieldName'] = 'tbl_users_id';
                        $arguingArr['whereFieldValue'] =$updateId;
                        $arguingArr['updateArr'] = $arguingCounselTable;
                        $uparguingCounselTable = $this->Common_model->updateTableData($arguingArr);
                        if(isset($uparguingCounselTable) && !empty($uparguingCounselTable)){
                            $params = array();
                            $userTable = array();
                            $userTable['is_active'] = 0;
                            $userTable['is_deleted'] = true;
                            $userTable['updated_on'] = date('Y-m-d H:i:s');
                            $userTable['update_ip'] = getClientIP();
                            $userTable['deleted_by'] = $_SESSION['login']['id'];
                            $userTable['deleted_on'] = date('Y-m-d H:i:s');
                            $userTable['delete_ip'] = getClientIP();
                            $params['table_name'] = "efil.tbl_users";
                            $params['whereFieldName'] = 'id';
                            $params['whereFieldValue'] = $updateId;
                            $params['updateArr'] = $userTable;
                            $upUserTable = $this->Common_model->updateTableData($params);
                            if(isset($upUserTable) && !empty($upUserTable)){
                                $name = '';
                                $email ='';
                                $mobile ='';
                                if(isset($userData) && !empty($userData) && isset($updateId) && !empty($updateId)){
                                    if(array_key_exists($updateId,$userData)){
                                        $currentData = $userData[$updateId];
                                        if(isset($currentData) && !empty($currentData)){
                                            $currentDataArr = explode('@',$currentData);
                                            $name = !empty($currentDataArr[0]) ? $currentDataArr[0] : '';
                                            $email = !empty($currentDataArr[1]) ? $currentDataArr[1] : '';
                                            $mobile = !empty($currentDataArr[2]) ? $currentDataArr[2] : '';
                                        }
                                    }
                                    $subject = "Account deactivated.";
                                    $email_message = 'Dear ' . $name . ', your account has been deactivated by AOR  <br>';
                                    $sms_message ='Dear ' . $name . ', your account has been deactivated by AOR  <br>';
                                    send_mail_msg($email, $subject, $email_message, $to_user_name = "arguing_counsel");
                                    send_mobile_sms($mobile, $sms_message, ACCOUNT_DEACTIVE);
                                }
                            }
                            array_push($successArr,$id);
                        }
                        else{
                            array_push($errorArr,$id);
                        }
                    }
                    $output['message'] = 'Advocate has been '.$type.' successfully! success total(s): '.count($successArr).' and error total(s): '.count($errorArr);
                    $output['success'] = "success";
                 break;
                default:
            }
        }
        else{
            $output['message'] = "Please select user and action type";
            $output['error'] = "error";
        }
        echo json_encode($output);
        exit(0);
    }

    public function generateKey() {
        $encryption = service('encryption');
        $newKey = $encryption->generateKey();
        echo $newKey;
    }

}