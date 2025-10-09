<?php
namespace App\Controllers\Add;

use App\Controllers\BaseController;

use App\Models\Department\DepartmentModel;
use App\Models\Common\CommonLoginModel;
use App\Models\Common\CommonModel;
use App\Models\Common\DropdownListModel;
use App\Libraries\webservices\Efiling_webservices;

class Department extends BaseController {

    protected $Department_model;
    protected $Common_login_model;
    protected $Common_model;
    protected $Dropdown_list_model;
    protected $efiling_webservices;

    public function __construct() {
        parent::__construct();
        if(empty(getSessionData('login'))){
            return response()->redirect(base_url('/')); 
        } else{
            is_user_status();
        }
        $this->Common_model = new CommonModel();
        $this->Department_model = new DepartmentModel();
        $this->Common_login_model = new CommonLoginModel();
        $this->Dropdown_list_model = new DropdownListModel();
        $this->efiling_webservices = new Efiling_webservices();
    }

    public function index() {
        $this->department();
    }

    public function department() {
        if ($_SESSION['login']['ref_m_usertype_id'] != USER_DEPARTMENT || $_SESSION['login']['dep_flag'] == 'f') {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $data['depart_user_list'] = $this->Department_model->get_dept_users($_SESSION['login']['id']);
        $data['states'] = $this->efiling_webservices->getOpenAPIState();
        $this->render('department.add_department_view', $data);
    }

    public function add_department() {
        if ($_SESSION['login']['ref_m_usertype_id'] != USER_DEPARTMENT && $_SESSION['login']['dep_flag'] == 'f' && $_SESSION['login']['ref_m_usertype_id'] != USER_SUPER_ADMIN) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        if ($_SESSION['login']['ref_m_usertype_id'] == USER_SUPER_ADMIN) {
            $dep_flag = TRUE;
            $case_flag = TRUE;
            $doc_flag = TRUE;
            $dep_adv_flag = TRUE;
            $redirection = 'admin/supadmin/department';
        }
        if ($_SESSION['login']['ref_m_usertype_id'] == USER_DEPARTMENT) {
            $dep_flag = $_POST['dep_add'];
            $case_flag = $_POST['case_file'];
            $doc_flag = $_POST['doc_file'];
            $dep_adv_flag = $_POST['dep_adv_add'];
            $redirection = 'add/Department';
        }
        $email_pattern = "/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,})$/";
        $department = trim(strtoupper($_POST['dep_name']));
        $moniter = trim(strtoupper($_POST['last_name']));
        $mobile = trim($_POST['mobile_no']);
        $email = trim(strtoupper($_POST['email_id']));
        $userid = strtoupper($_POST['login_id']);
        $ref_m_usertype_id = USER_DEPARTMENT;
        $district_id = explode("#$", url_decryption($_POST['district']));
        $random_password = 'Department@1234'; //time().rand('10,100');
        $login_pass = hash('sha256', $random_password);
        $address = trim($_POST['address']);
        $ref_m_states_id = explode("#$", url_decryption($_POST['states_id']));
        $city = trim(strtoupper($_POST['city']));
        $catag = $_POST['catagory'];
        if ($_SESSION['login']['ref_m_usertype_id'] == USER_SUPER_ADMIN) {
            if (empty($catag) || preg_match('/[^0-9a-zA-Z]/', $catag)) {
                $this->session->setFlashdata('message', "<div class='alert alert-danger fade in'>Please Select Valid Catagory.</div>");
                return redirect()->to(base_url($redirection));
            }
            $catagory = $catag;
        } else {
            $catagory = '';
        }
        if (preg_match('/[^0-9]/i', $mobile) || strlen($mobile) != 10) {
            $this->session->setFlashdata('message', "<div class='alert alert-danger fade in'>Please Enter Valid Mobile Number.</div>");
            return redirect()->to(base_url($redirection));
        }
        if (!preg_match($email_pattern, $email)) {
            $this->session->setFlashdata('message', "<div class='alert alert-danger fade in'>Please Enter Valid Email ID.</div>");
            return redirect()->to(base_url($redirection));
        }
        $check_email_already_present = $this->Common_login_model->check_email_already_present(strtoupper($email));
        $check_mobno_already_present = $this->Common_login_model->check_mobno_already_present(strtoupper($mobile));
        $check_usrid_already_present = $this->Common_login_model->check_usrid_already_present(strtoupper($userid));
        if ($check_email_already_present) {
            $this->session->setFlashdata('message', "<div class='alert alert-danger fade in' >Same Email Id already Registered.</div>");
            return redirect()->to(base_url($redirection));
        }
        if ($check_mobno_already_present) {
            $this->session->setFlashdata('message', "<div class='alert alert-danger fade in'>Same Mobile Number already Registered. </div>");
            return redirect()->to(base_url($redirection));
        }
        if ($check_usrid_already_present) {
            $this->session->setFlashdata('message', "<div class='alert alert-danger fade in'>Same User ID already Registered</div>");
            return redirect()->to(base_url($redirection));
        }
        if (preg_match('/[^0-9a-zA-Z. ]/', $department) || $department == NULL || strlen($department) < 5 || strlen($department) > 100) {
            $this->session->setFlashdata('message', "<div class='alert alert-danger fade in'>Department Name is required and can contain only characters and spaces. Minimum length 5 and max length 100 Characters.</div>");
            return redirect()->to(base_url($redirection));
        }
        if (preg_match('/[^0-9a-zA-Z. ]/', $moniter) || $moniter == NULL || strlen($moniter) < 5 || strlen($moniter) > 100) {
            $this->session->setFlashdata('message', "<div class='alert alert-danger fade in'>Moniter Name is required and can contain only characters and spaces.</div>");
            return redirect()->to(base_url($redirection));
        }
        if (preg_match('/[^0-9]/i', $ref_m_states_id[0])) {
            $this->session->setFlashdata('message', "<div class='alert alert-danger fade in'>State is required and can have only numbers!</div>");
            return redirect()->to(base_url($redirection));
        }
        if (preg_match('/[^0-9]/i', $district_id[0])) {
            $this->session->setFlashdata('message', "<div class='alert alert-danger fade in'>District is required and can have only numbers!</div>");
            return redirect()->to(base_url($redirection));
        }
        $validate_name = validate_names($address, TRUE, 4, 150, 'Address');
        if ($validate_name['response'] == FALSE) {
            $this->session->setFlashdata('message', "<div class='alert alert-danger fade in'>" . htmlentities($validate_name['msg']['field_name'], ENT_QUOTES) . "</div>");
            return redirect()->to(base_url($redirection));
        }
        if (preg_match("/[^0-9a-zA-Z\s.:,-_\/ ]/i", $city) || $city == NULL) {
            $this->session->setFlashdata('message', "<div class='alert alert-danger fade in'>City  is required and can contain only characters,numbers,spaces and :,.-_/</div>");
            return redirect()->to(base_url($redirection));
        }
        if (preg_match('/[^0-9a-zA-Z]/', $userid) || empty($userid) || strlen($userid) < 5 || strlen($userid) > 15) {
            $this->session->setFlashdata('message', "<div class='alert alert-danger fade in'>Login id is required and can be in alphnumeric format with min. length 5 and max. length 15.</div>");
            return redirect()->to(base_url($redirection));
        }
        if ($_POST['pincode'] != '') {
            $pincode = $_POST['pincode'];
            if (preg_match('/[^0-9]/i', $_POST['pincode']) || (strlen($_POST['pincode']) != 6)) {
                $this->session->setFlashdata('message', "<div class='alert alert-danger fade in'> Pincode is required and can have only numbers of 6 digit length </div>");
                return redirect()->to(base_url($redirection));
            }
        } else{
            $pincode = 0;
        }
        $data = array(
            'first_name' => $moniter,
            'moblie_number' => $mobile,
            'emailid' => $email,
            'userid' => $userid,
            'ref_m_usertype_id' => $ref_m_usertype_id,
            'ref_m_states_id' => $ref_m_states_id[0],
            'password' => $login_pass,
            'last_name' => 'NA',
            'gender' => 0,
            'address1' => $address,
            'njdg_st_id' => $ref_m_states_id[0],
            'njdg_st_name' => $ref_m_states_id[1],
            'njdg_dist_id' => $district_id[0],
            'njdg_dist_name' => $district_id[1],
            'pincode' => $pincode,
            'bar_reg_no' => 0,
            'ref_m_id_proof_type' => 0,
            'created_by_ip' => getClientIP(),
            'dob' => '0',
            'city' => $city,
            'updated_by_ip' => getClientIP(),
            'is_active' => TRUE,
            'dep_flag' => $dep_flag,
            'case_flag' => $case_flag,
            'doc_flag' => $doc_flag,
            'dep_adv_flag' => $dep_adv_flag
        );
        $result = $this->Department_model->add_department_user($data, $department, $catagory);
        if ($result) {
            $this->session->setFlashdata('message', "<div class='alert alert-success fade in'>Department Added Successfully.</div>");
            $user_name = $userid;
            send_mail_msg($email, 'Your eFiling Username', 'Your eFiling  
                        </br>URL<b>' . base_url() . '</b>
                        . </br>username : <b>' . strtoupper($userid) . '</b>  . </br>Password : <b>' . strtoupper($login_pass) . '</b> ', $moniter);
            $sentSMS = 'Your eFiling Password ' . $login_pass . 'The login username has been sent to your registered email account. - Supreme Court of India';
            send_mobile_sms($mobile, $sentSMS,SCISMS_Login_Password_To_Email);
            return redirect()->to(base_url($redirection));
            exit(0);
        } else {
            $this->session->setFlashdata('message', "<div class='alert alert-danger fade in'>Something Wrong, Please try again later.</div>");
            return redirect()->to(base_url($redirection));
        }
        $this->render('department.add_department_view', $data);
    }

    public function edit($id) {
        if ($_SESSION['login']['ref_m_usertype_id'] != USER_DEPARTMENT && $_SESSION['login']['dep_flag'] == 'f' && $_SESSION['login']['ref_m_usertype_id'] != USER_SUPER_ADMIN) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        if ($_SESSION['login']['ref_m_usertype_id'] == USER_SUPER_ADMIN) {
            $data['depart_user_list'] = $this->Department_model->get_superadmin_department($_SESSION['login']['id']);
            $redirection = 'admin/supadmin/department';
        }
        if ($_SESSION['login']['ref_m_usertype_id'] == USER_DEPARTMENT) {
            $data['depart_user_list'] = $this->Department_model->get_dept_users($_SESSION['login']['id']);
            $redirection = 'Department';
        }
        $id = url_decryption($id);
        $data['states'] = $this->efiling_webservices->getOpenAPIState();
        $data['district'] = $this->efiling_webservices->getOpenAPIDistrict($data['depart_user_list'][0]['njdg_st_id']);
        if ($id) {
            if (isset($_SESSION['login']) && $_SESSION['login']['ref_m_usertype_id'] == USER_DEPARTMENT || $_SESSION['login']['ref_m_usertype_id'] == USER_SUPER_ADMIN) {
                $data['depData'] = $this->Department_model->get_dept_details($id);
                $data['district'] = $this->efiling_webservices->getOpenAPIDistrict($data['depData'][0]['njdg_st_id']);
                $this->render('department.add_department_view', $data);
            }
        }
    }

    public function action($action_id, $status) {
        if ($_SESSION['login']['ref_m_usertype_id'] != USER_DEPARTMENT && $_SESSION['login']['dep_flag'] == 'f' && $_SESSION['login']['ref_m_usertype_id'] != USER_SUPER_ADMIN) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        if ($_SESSION['login']['ref_m_usertype_id'] == USER_SUPER_ADMIN) {
            $redirection = 'admin/supadmin/department';
        }
        if ($_SESSION['login']['ref_m_usertype_id'] == USER_DEPARTMENT) {
            $redirection = 'add/department';
        }
        if (empty($action_id) || empty($status)) {
            $this->session->setFlashdata('message', "<div class='alert alert-danger fade in'>Something Wrong! Please Try again letter.</div>");
            return redirect()->to(base_url($redirection));
        }
        $data['depart_user_list'] = $this->Department_model->get_dept_users($_SESSION['login']['id']);
        $action_id = url_decryption($action_id);
        $status = url_decryption(trim($status));
        if (preg_match('/[^0-9]/i', $action_id)) {
            $this->session->setFlashdata('message', "<div class='alert alert-danger fade in'>Something Wrong! Please Try again letter.</div>");
            return redirect()->to(base_url($redirection));
        }
        $result = $this->Common_model->action_taken($action_id, $status);
        if ($result) {
            $this->session->setFlashdata('message', "<div class='alert alert-success fade in'>Status Updated Successfully .</div>");
            return redirect()->to(base_url($redirection));
        } else {
            $this->session->setFlashdata('message', "<div class='alert alert-danger fade in'>Something Wrong! Please Try again letter.</div>");
            return redirect()->to(base_url($redirection));
        }
    }

    public function update_department() {
        if ($_SESSION['login']['ref_m_usertype_id'] != USER_DEPARTMENT && $_SESSION['login']['dep_flag'] == 'f' && $_SESSION['login']['ref_m_usertype_id'] != USER_SUPER_ADMIN) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        if ($_SESSION['login']['ref_m_usertype_id'] == USER_SUPER_ADMIN) {
            $dep_flag = TRUE;
            $case_flag = TRUE;
            $doc_flag = TRUE;
            $dep_adv_flag = TRUE;
            $redirection = 'admin/supadmin/department';
        }
        if ($_SESSION['login']['ref_m_usertype_id'] == USER_DEPARTMENT) {
            $dep_flag = $_POST['dep_add'];
            $case_flag = $_POST['case_file'];
            $doc_flag = $_POST['doc_file'];
            $dep_adv_flag = $_POST['dep_adv_add'];
            $redirection = 'add/Department';
        }
        $dep_id = url_decryption($_POST['dep_id']);
        $email_pattern = "/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,})$/";
        $department = trim(strtoupper($_POST['dep_name']));
        $moniter = trim(strtoupper($_POST['last_name']));
        $mobile = trim($_POST['mobile_no']);
        $email = trim(strtoupper($_POST['email_id']));
        $address = trim($_POST['address']);
        $city = trim(strtoupper($_POST['city']));
        $district_id = explode("#$", url_decryption($_POST['district']));
        $ref_m_states_id = explode("#$", url_decryption($_POST['states_id']));
        $catagory = $_POST['catagory'];
        if ($_SESSION['login']['ref_m_usertype_id'] == USER_SUPER_ADMIN) {
            if (empty($catagory) || preg_match('/[^0-9a-zA-Z]/', $catagory)) {
                $this->session->setFlashdata('message', "<div class='alert alert-danger fade in'>Please Select Valid Catagory.</div>");
                return redirect()->to(base_url($redirection));
            }
        }
        if (preg_match('/[^0-9]/i', $mobile) || strlen($mobile) != 10) {
            $this->session->setFlashdata('message', "<div class='alert alert-danger fade in'>Please Enter Valid Mobile Number.</div>");
            return redirect()->to(base_url($redirection));
        }
        if (!preg_match($email_pattern, $email)) {
            $this->session->setFlashdata('message', "<div class='alert alert-danger fade in'>Please Enter Valid Email ID.</div>");
            return redirect()->to(base_url($redirection));
        }
        $check_email_present = $this->Department_model->check_email_present(strtoupper($email), $dep_id);
        $check_mobno_present = $this->Department_model->check_mobno_present($mobile, $dep_id);
        if ($check_email_present) {
            $this->session->setFlashdata('message', "<div class='alert alert-danger fade in'>Same Email Id already Registered.</div>");
            return redirect()->to(base_url($redirection));
        }
        if ($check_mobno_present) {
            $this->session->setFlashdata('message', "<div class='alert alert-danger fade in'>Same Mobile Number already Registered. </div>");
            return redirect()->to(base_url($redirection));
        }
        if (preg_match('/[^0-9a-zA-Z. ]/', $department) || $department == NULL || strlen($department) < 5 || strlen($department) > 100) {
            $this->session->setFlashdata('message', "<div class='alert alert-danger fade in'>Department Name is required and can contain only characters and spaces.</div>");
            return redirect()->to(base_url($redirection));
        }
        if (preg_match('/[^0-9a-zA-Z. ]/', $moniter) || $moniter == NULL || strlen($moniter) < 5 || strlen($moniter) > 100) {
            $this->session->setFlashdata('message', "<div class='alert alert-danger fade in'>Moniter Name is required and can contain only characters and spaces.</div>");
            return redirect()->to(base_url($redirection));
        }
        if (preg_match('/[^0-9]/i', $ref_m_states_id[0])) {
            $this->session->setFlashdata('message', "<div class='alert alert-danger fade in'>State is required and can have only numbers!</div>");
            return redirect()->to(base_url($redirection));
        }
        if (preg_match('/[^0-9]/i', $district_id[0])) {
            $this->session->setFlashdata('message', "<div class='alert alert-danger fade in'>District is required and can have only numbers!</div>");
            return redirect()->to(base_url($redirection));
        }
        $validate_name = validate_names($address, TRUE, 4, 150, 'Address');
        if ($validate_name['response'] == FALSE) {
            $this->session->setFlashdata('message', "<div class='alert alert-danger fade in'>" . htmlentities($validate_name['msg']['field_name'], ENT_QUOTES) . "</div>");
            return redirect()->to(base_url('Department'));
        }
        if (preg_match("/[^0-9a-zA-Z\s.:,-_\/ ]/i", $city) || $city == NULL) {
            $this->session->setFlashdata('message', "<div class='alert alert-danger fade in'>City  is required and can contain only characters,numbers,spaces and :,.-_/</div>");
            return redirect()->to(base_url($redirection));
        }
        if ($_POST['pincode'] != '') {
            $pincode = $_POST['pincode'];
            if (preg_match('/[^0-9]/i', $_POST['pincode']) || (strlen($_POST['pincode']) != 6)) {
                $this->session->setFlashdata('message', "<div class='alert alert-danger fade in'>Pincode is required and can have only numbers of 6 digit length </div>");
                return redirect()->to(base_url($redirection));
            }
        } else{
            $pincode = 0;
        }
        $data = array(
            'first_name' => $moniter,
            'moblie_number' => $mobile,
            'emailid' => $email,
            'last_name' => 'NA',
            'address1' => $address,
            'pincode' => $pincode,
            'city' => $city,
            'updated_by_ip' => getClientIP(),
            'dep_flag' => $dep_flag,
            'case_flag' => $case_flag,
            'doc_flag' => $doc_flag,
            'dep_adv_flag' => $dep_adv_flag,
            'njdg_st_id' => $ref_m_states_id[0],
            'njdg_st_name' => $ref_m_states_id[1],
            'njdg_dist_id' => $district_id[0],
            'njdg_dist_name' => $district_id[1]
        );
        $result = $this->Department_model->update_department_user($dep_id, $data, $department, $catagory);
        if ($result) {
            $this->session->setFlashdata('message', "<div class='alert alert-success fade in'>Department Updated Successfully.</div>");
            return redirect()->to(base_url($redirection));
        } else {
            $this->session->setFlashdata('message', "<div class='alert alert-danger fade in'>Something Wrong, Please try again later.</div>");
            return redirect()->to(base_url($redirection));
        }
        $this->render('department.add_department_view', $data);
    }

    public function get_district_list() {
        $state_array = explode('#$', url_decryption(escape_data($_POST['state_id'])));
        $state_id = $state_array[0];
        if (isset($state_id) && preg_match("/^[0-9]*$/", $state_id) && strlen($state_id) <= INTEGER_FIELD_LENGTH) {
            if (!empty($state_id)) {
                $result = $this->efiling_webservices->getOpenAPIDistrict($state_id);
                if (!empty($status->status_code)) {
                    echo 'ERROR_' . $result->status;
                    exit(0);
                } else {
                    if (count($result)) {
                        echo '<option value=""> Select District </option>';
                        foreach ($result as $dataRes) {
                            foreach ($dataRes->district as $district) {
                                echo '<option  value="' . htmlentities(url_encryption($district->dist_code . '#$' . $district->dist_name), ENT_QUOTES) . '">' . htmlentities(strtoupper($district->dist_name), ENT_QUOTES) . '</option>';
                            }
                        }
                    } else {
                        echo '<option value=""> Select District </option>';
                    }
                }
            } else {
                echo '<option value=""> Select District</option>';
            }
        } else {
            echo '<option value=""> Select District</option>';
        }
    }

    public function department_advocate_panel() {
        if ($_SESSION['login']['ref_m_usertype_id'] != USER_DEPARTMENT && $_SESSION['login']['dep_flag'] == 'f') {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $data['get_dept_adv_panel_data'] = $this->Department_model->get_dept_adv_panel($_SESSION['login']['id']);
        $this->render('department.advocate_panel_view', $data);
    }

    public function search_advocate() {
        if ($_SESSION['login']['ref_m_usertype_id'] != USER_DEPARTMENT && $_SESSION['login']['dep_flag'] == 'f') {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $data['get_dept_adv_panel_data'] = $this->Department_model->get_dept_adv_panel($_SESSION['login']['id']);
        $adv_name = explode(' ', strtoupper($_POST['adv_name']));
        $first_name = $adv_name[0];
        $last_name = $adv_name[1];
        $adv_mobile = trim($_POST['adv_mobile']);
        if (!empty($adv_mobile)) {
            if (preg_match('/[^0-9]/i', $adv_mobile) || strlen($adv_mobile) != 10) {
                $this->session->setFlashdata('message', "<div class='alert alert-danger fade in'>Please Enter Valid Mobile Number.</div>");
                return redirect()->to(base_url('add/Department/department_advocate_panel'));
            }
        }
        $adv_barcode = trim($_POST['bar_code']);
        if (empty($first_name) && empty($adv_mobile) && empty($adv_barcode)) {
            $this->session->setFlashdata('message', "<div class='alert alert-danger fade in'>Please Enter Advocate Name or Mobile or Barcode.</div>");
            return redirect()->to(base_url('add/Department/department_advocate_panel'));
        }
        $data['advocate_data'] = $this->Department_model->get_advocate_data($first_name, $last_name, $adv_mobile, $adv_barcode);
        if (ENABLE_FOR_HC && ENABLE_FOR_ESTAB) {
            $data['high_court_list'] = $this->Dropdown_list_model->get_high_court_list();
            $data['state_list'] = $this->Dropdown_list_model->get_state_list_Y();
        } elseif (ENABLE_FOR_HC) {
            $data['high_court_list'] = $this->Dropdown_list_model->get_high_court_list();
        } elseif (ENABLE_FOR_ESTAB) {
            $data['state_list'] = $this->Dropdown_list_model->get_state_list_Y();
        }
        if (empty($data['advocate_data'])) {
            $this->session->setFlashdata('message', "<div class='alert alert-danger fade in'>No Record Found or Not register in eFiling.</div>");
            return redirect()->to(base_url('add/DepDartment/department_advocate_panel'));
        }
        $this->render('department.advocate_panel_view', $data);
    }

    public function add_dept_advocate() {
        if ($_SESSION['login']['ref_m_usertype_id'] != USER_DEPARTMENT && $_SESSION['login']['dep_flag'] == 'f') {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $get_department_data = $this->Department_model->get_department_details($_SESSION['login']['id']);
        $parent = $get_department_data[0]['parent'];
        $all_depts = $get_department_data[0]['all_dep'];
        $dep_id = $get_department_data[0]['id'];
        if (!empty($parent)) {
            $parents = $parent;
        } else {
            $parents = $dep_id;
        }
        $data['get_dept_adv_panel_data'] = $this->Department_model->get_dept_adv_panel($_SESSION['login']['id']);
        $input_count = count($_POST['adv_sel']);
        for ($i = 0; $i < $input_count; $i++) {
            $advocate_id = url_decryption($_POST['adv_sel'][$i]);
            $advoc_id[] = array($advocate_id);
            $get_data = $this->Department_model->get_dep_adv_data($advocate_id, $parents);
            if ($advocate_id == $get_data[$i]['advocate_id'] || !empty($get_data)) {
                $this->session->setFlashdata('message', "<div class='alert alert-danger fade in'>Already Exist.</div>");
                return redirect()->to(base_url('add/Department/department_advocate_panel'));
            } else {
                $data_adv[] = array('advocate_id' => $advocate_id, 'parent_dept_id' => $parents, 'all_dept_ids' => $all_depts, 'added_by' => $dep_id);
            }
        }
        $result = $this->Department_model->add_dept_advocate_data($data_adv);
        if ($result) {
            $this->session->setFlashdata('message', "<div class='alert alert-success fade in'>Added Successfully.</div>");
            return redirect()->to(base_url('add/department/department_advocate_panel'));
        } else {
            $this->session->setFlashdata('message', "<div class='alert alert-danger fade in'>Something went wrong! Please try again later.</div>");
        }
        header('location:' . base_url('Department/department_advocate_panel'));
        $this->render('department.advocate_panel_view', $data);
    }

    function delete_dept_advocate($advid, $depid) {
        $depid = url_decryption($depid);
        $advid = url_decryption($advid);
        $data['get_dept_adv_panel_data'] = $this->Department_model->get_dept_adv_panel($_SESSION['login']['id']);
        if ($_SESSION['login']['ref_m_usertype_id'] != USER_DEPARTMENT || $_SESSION['login']['dep_flag'] == 'f') {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        if (empty($depid) || empty($advid)) {
            $this->session->setFlashdata('message', "<div class='alert alert-danger fade in'>Something Wrong! Please Try again letter.</div>");
            return redirect()->to(base_url('add/department/department_advocate_panel'));
        }
        $result = $this->Department_model->delete_department_advocate($advid, $depid);
        if ($result) {
            $this->session->setFlashdata('message', "<div class='alert alert-success fade in'>Deleted Successfully.</div>");
            return redirect()->to(base_url('add/department/department_advocate_panel'));
        } else {
            $this->session->setFlashdata('message', "<div class='alert alert-danger fade in'>Something went wrong! Please try again later.</div>");
            return redirect()->to(base_url('add/department/department_advocate_panel'));
        }
    }

}