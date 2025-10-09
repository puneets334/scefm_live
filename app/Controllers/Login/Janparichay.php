<?php

namespace App\Controllers\Login;

use App\Controllers\BaseController;
use App\Models\Login\JanparichayModel;
use stdClass;
use App\Libraries\Slice;

class Janparichay extends BaseController
{
    protected $session;
    protected $slice;
    protected $Janparichay_model;

    public function __construct()
    {
        parent::__construct();
        $this->session = \Config\Services::session();
        $this->slice = new Slice();
        $this->Janparichay_model = new JanparichayModel();
        helper(array('url', 'cookie', 'janparichay'));
    }

    public function index()
    {
        if (JANPARICHAY_CLIENT_ACTIVE) {
            if (isset($_GET['string']) && !empty($_GET['string'])) {
                $responseData = janparichay_handle_login_callback();
                $scefm_user_result = array();
                if ((isset($responseData) && !empty($responseData)) && (isset($responseData['data']['signature']) && !empty($responseData['data']['signature']))) {
                    $janparichay_data = $responseData['data']['signature'];
                    $JDSS = $responseData['data']['signature']['serviceData'];
                    $janparichay_data['aor_code'] = isset($JDSS['aor_code']) && !empty($JDSS['aor_code']) ? $JDSS['aor_code'] : NULL;
                    $janparichay_data['barRegdNo'] = isset($JDSS['barRegdNo']) && !empty($JDSS['barRegdNo']) ? $JDSS['barRegdNo'] : NULL;
                    $one_time_password = 'Test@4321' . $janparichay_data['mobileNo'];
                    if ((isset($janparichay_data['mobileNo']) && !empty($janparichay_data['mobileNo'])) && (isset($janparichay_data['aor_code']) && !empty($janparichay_data['aor_code']))) {
                        $one_time_password = 'vidhi' . substr($janparichay_data['aor_code'], -2) . substr($janparichay_data['mobileNo'], -5);
                    }
                    $userInSCeFM['userid'] = isset($janparichay_data['mobileNo']) && !empty($janparichay_data['mobileNo']) ? $janparichay_data['mobileNo'] : NULL;
                    $userInSCeFM['password'] = hash('sha256', $one_time_password);
                    $userInSCeFM['first_name'] = isset($janparichay_data['firstName']) && !empty($janparichay_data['firstName']) ? strtoupper($janparichay_data['firstName']) : NULL;
                    $userInSCeFM['last_name'] = isset($janparichay_data['lastName']) && !empty($janparichay_data['lastName']) ? strtoupper($janparichay_data['lastName']) : NULL;
                    $userInSCeFM['moblie_number'] = isset($janparichay_data['mobileNo']) && !empty($janparichay_data['mobileNo']) ? $janparichay_data['mobileNo'] : NULL;
                    $userInSCeFM['emailid'] = isset($janparichay_data['email']) && !empty($janparichay_data['email']) ? strtolower($janparichay_data['email']) : NULL;
                    $userInSCeFM['adv_sci_bar_id'] = null;
                    $userInSCeFM['aor_code'] = isset($janparichay_data['aor_code']) && !empty($janparichay_data['aor_code']) ? $janparichay_data['aor_code'] : NULL;
                    $userInSCeFM['bar_reg_no'] = isset($janparichay_data['barRegdNo']) && !empty($janparichay_data['barRegdNo']) ? $janparichay_data['barRegdNo'] : NULL;
                    $userInSCeFM['gender'] = isset($janparichay_data['gender']) && !empty($janparichay_data['gender']) ? $janparichay_data['gender'] : NULL;
                    $userInSCeFM['photo_path'] = null;
                    $userInSCeFM['admin_for_type_id'] = 1;
                    $userInSCeFM['admin_for_id'] = 1;
                    $userInSCeFM['account_status'] = 1;
                    $userInSCeFM['is_active'] = 1;
                    $userInSCeFM['refresh_token'] = isset($janparichay_data['clientToken']) && !empty($janparichay_data['clientToken']) ? $janparichay_data['clientToken'] : NULL;;
                    $userInSCeFM['dob'] = isset($janparichay_data['dob']) && !empty($janparichay_data['dob']) ? date("Y-m-d", strtotime($janparichay_data['dob'])) : NULL;
                    $userInSCeFM['m_address1'] = isset($janparichay_data['address']) && !empty($janparichay_data['address']) ? $janparichay_data['address'] : NULL;
                    $userInSCeFM['m_city'] = isset($janparichay_data['city']) && !empty($janparichay_data['city']) && strtolower($janparichay_data['city']) != 'none' ? $janparichay_data['address'] : NULL;
                    $userInSCeFM['m_state_id'] = null;
                    $userInSCeFM['m_district_id'] = null;
                    $userInSCeFM['m_pincode'] = null;
                    $userInSCeFM['create_ip'] = get_client_ip();
                    $userInSCeFM['is_first_pwd_reset'] = false;
                    if (isset($janparichay_data['aor_code']) && !empty($janparichay_data['aor_code'])) { //AOR (Advocate on record)
                        $userInSCeFM['ref_m_usertype_id'] = USER_ADVOCATE;
                        $userInSCeFM['aor_code'] = $janparichay_data['aor_code'];
                        $scefm_user_result = $this->Janparichay_model->get_user($janparichay_data['aor_code']);
                    } else {
                        //if not AOR (Advocate on record) only response data save
                        $currenttime = date("Y-m-d H:i:s");
                        $janparichay_log_data = $janparichay_signature = null;
                        if (!is_string($responseData)) {
                            $janparichay_log_data = json_encode($responseData);
                        }
                        if (!is_string($responseData['data']['signature'])) {
                            $janparichay_signature = json_encode($responseData['data']['signature']);
                        }
                        $janParichay_scefm_user_data_save = array(
                            'tbl_users_id' => isset($_SESSION['login']['id']) && !empty($_SESSION['login']['id']) ? $_SESSION['login']['id'] : 9999,
                            'sid' => JANPARICHAY_SERVICE_ID,
                            'status' => isset($responseData['status']) && !empty($responseData['status']) ? $responseData['status'] : null,
                            'message' => isset($responseData['message']) && !empty($responseData['message']) ? $responseData['message'] : null,
                            'signature' => isset($janparichay_signature) && !empty($janparichay_signature) ? $janparichay_signature : null,
                            'client_token' => isset($janparichay_data['clientToken']) && !empty($janparichay_data['clientToken']) ? $janparichay_data['clientToken'] : null,
                            'session_id' => isset($janparichay_data['sessionId']) && !empty($janparichay_data['sessionId']) ? $janparichay_data['sessionId'] : null,
                            'browser_id' => isset($janparichay_data['browserId']) && !empty($janparichay_data['browserId']) ? $janparichay_data['browserId'] : null,
                            'user_agent' => isset($janparichay_data['ua']) && !empty($janparichay_data['ua']) ? $janparichay_data['ua'] : null,
                            'response_all' => isset($janparichay_log_data) && !empty($janparichay_log_data) ? $janparichay_log_data : null,
                            'ci_session_efiling' => isset($_COOKIE['ci_session_efiling']) && !empty($_COOKIE['ci_session_efiling']) ? $_COOKIE['ci_session_efiling'] : null,
                            'created_by' => isset($_SESSION['login']['id']) && !empty($_SESSION['login']['id']) ? $_SESSION['login']['id'] : 9999,
                            'created_on' => $currenttime,
                            'created_by_ip' => get_client_ip(),
                            'user_key' => generate_user_key()
                        );
                        $this->Janparichay_model->save_janparichay($janParichay_scefm_user_data_save);
                    }
                    if (isset($scefm_user_result) && !empty($scefm_user_result)) {
                        $this->user_login($scefm_user_result, $responseData);
                    } else {
                        $sessiondata = array(
                            'sid' => JANPARICHAY_SERVICE_ID,
                            'clientToken' => isset($janparichay_data['clientToken']) && !empty($janparichay_data['clientToken']) ? $janparichay_data['clientToken'] : null,
                            'sessionId' => isset($janparichay_data['sessionId']) && !empty($janparichay_data['sessionId']) ? $janparichay_data['sessionId'] : null,
                            'browserId' => isset($janparichay_data['browserId']) && !empty($janparichay_data['browserId']) ? $janparichay_data['browserId'] : null,
                            'userAgent' => isset($janparichay_data['ua']) && !empty($janparichay_data['ua']) ? $janparichay_data['ua'] : null,
                        );
                        $_SESSION($sessiondata);
                        janparichay_logout_all_apps();
                        return redirect()->to(base_url('login/Janparichay/logout'));
                        exit(0);
                    }
                }
            } else {
                $this->session->setFlashdata('msg', 'You are not authorized!');
                return redirect()->to(base_url('/'));
                exit(0);
            }
        } else {
            $this->session->setFlashdata('msg', 'You are not authorized!!');
            return redirect()->to(base_url('/'));
            exit(0);
        }
    }

    public function logout()
    {
        $this->session->setFlashdata('msg', 'You are not authorized.');
        return redirect()->to(base_url('/'));
        exit(0);
    }

    public function user_login($scefm_user_result, $responseData)
    {
        /*****end-for efiling_assistant*****/
        if ($scefm_user_result) {
            foreach ($scefm_user_result as $row) {
                if (SELECTED_RESTRICT_USERS_ACTIVE && !empty($row->aor_code)) {
                    if (!in_array($row->aor_code, SELECTED_RESTRICT_USERS_LIST)) {
                        echo "<script>alert('" . SELECTED_RESTRICT_USERS_ALERT_MESSAGE . "'); window.location.href='" . SELECTED_RESTRICT_USERS_URL . "';</script>";
                        exit();
                    }
                }
                $currenttime = date("Y-m-d H:i:s");
                //Check user role
                if (logged_in_check_user_type($row->ref_m_usertype_id)) {
                    $this->session->setFlashdata('msg', 'You are not authorized !!');
                    return redirect()->to(base_url('/'));
                    exit(0);
                }
                $user_name = ucwords($row->first_name . ' ' . $row->last_name);
                $log_data = $this->Janparichay_model->get_user_login_log_details($row->id);
                if (!empty($log_data)) {
                    foreach ($log_data as $resdata) {
                        $unauthorized_access = array_keys(array_column($log_data, 'block'), 't');
                        $new_login_agent = array_keys(array_column($log_data, 'user_agent'), $_SERVER['HTTP_USER_AGENT']);
                        if (isset($unauthorized_access[0]) && !empty($unauthorized_access[0])) {
                            $this->session->setFlashdata('msg', 'Unauthorized access.');
                            return redirect()->to(base_url('/'));
                            exit(0);
                        }
                    }
                } else {
                    if ($this->isMobileDevice()) {
                        $device = "Mobile";
                    } else {
                        $device = "Desktop";
                    }
                }
                $pg_request_function = $row->pg_request_function;
                $pg_response_function = $row->pg_response_function;
                $admin_estab_code = $row->estab_code;
                $impersonator_user = new stdClass();
                $impersonator_user = $row;
                $janparichay_data = $responseData['data']['signature'];
                $logindata = array(
                    'id' => $row->id,
                    'userid' => $row->userid,
                    'ref_m_usertype_id' => $row->ref_m_usertype_id,
                    'first_name' => $row->first_name,
                    'last_name' => $row->last_name,
                    'mobile_number' => $row->moblie_number,
                    'emailid' => $row->emailid,
                    'adv_sci_bar_id' => $row->adv_sci_bar_id,
                    'aor_code' => $row->aor_code,
                    'bar_reg_no' => $row->bar_reg_no,
                    'gender' => $row->gender,
                    'pg_request_fun' => $pg_request_function,
                    'pg_response_fun' => $pg_response_function,
                    'photo_path' => $row->photo_path,
                    'login_active_session' => substr(number_format(time() * rand(), 0, '', ''), 0, 6),
                    'admin_for_type_id' => $row->admin_for_type_id,
                    'admin_for_id' => $row->admin_for_id,
                    'account_status' => $row->account_status,
                    'refresh_token' => $row->refresh_token,
                    'impersonator_user' => $impersonator_user, //for efiling_assistant
                    'processid' => getmypid(),
                    'department_id' => $row->ref_department_id
                );
                $sessiondata = array(
                    'login' => $logindata,
                    'sid' => JANPARICHAY_SERVICE_ID,
                    'clientToken' => isset($janparichay_data['clientToken']) && !empty($janparichay_data['clientToken']) ? $janparichay_data['clientToken'] : null,
                    'sessionId' => isset($janparichay_data['sessionId']) && !empty($janparichay_data['sessionId']) ? $janparichay_data['sessionId'] : null,
                    'browserId' => isset($janparichay_data['browserId']) && !empty($janparichay_data['browserId']) ? $janparichay_data['browserId'] : null,
                    'user_id' => $row->id,
                    'user_agent' => isset($janparichay_data['ua']) && !empty($janparichay_data['ua']) ? $janparichay_data['ua'] : null,
                    'ip_address' => get_client_ip(),
                );
                unset($_SESSION['']);
                unset($_SESSION['login']);
                unset($_SESSION['sid']);
                unset($_SESSION['clientToken']);
                unset($_SESSION['sessionId']);
                unset($_SESSION['browserId']);
                unset($_SESSION['user_id']);
                unset($_SESSION['user_agent']);
                unset($_SESSION['ip_address']);
                $this->session->set($sessiondata);
                $janparichay_log_data = $janparichay_signature = null;
                if (!is_string($responseData)) {
                    $janparichay_log_data = json_encode($responseData);
                }
                if (!is_string($responseData['data']['signature'])) {
                    $janparichay_signature = json_encode($responseData['data']['signature']);
                }
                $janParichay_scefm_user_data_save = array(
                    'tbl_users_id' => isset($_SESSION['login']['id']) && !empty($_SESSION['login']['id']) ? $_SESSION['login']['id'] : null,
                    'sid' => JANPARICHAY_SERVICE_ID,
                    'status' => isset($responseData['status']) && !empty($responseData['status']) ? $responseData['status'] : null,
                    'message' => isset($responseData['message']) && !empty($responseData['message']) ? $responseData['message'] : null,
                    'signature' => isset($janparichay_signature) && !empty($janparichay_signature) ? $janparichay_signature : null,
                    'client_token' => isset($janparichay_data['clientToken']) && !empty($janparichay_data['clientToken']) ? $janparichay_data['clientToken'] : null,
                    'session_id' => isset($janparichay_data['sessionId']) && !empty($janparichay_data['sessionId']) ? $janparichay_data['sessionId'] : null,
                    'browser_id' => isset($janparichay_data['browserId']) && !empty($janparichay_data['browserId']) ? $janparichay_data['browserId'] : null,
                    'user_agent' => isset($janparichay_data['ua']) && !empty($janparichay_data['ua']) ? $janparichay_data['ua'] : null,
                    'response_all' => isset($janparichay_log_data) && !empty($janparichay_log_data) ? $janparichay_log_data : null,
                    'ci_session_efiling' => isset($_COOKIE['ci_session_efiling']) && !empty($_COOKIE['ci_session_efiling']) ? $_COOKIE['ci_session_efiling'] : null,
                    'created_by' => isset($_SESSION['login']['id']) && !empty($_SESSION['login']['id']) ? $_SESSION['login']['id'] : null,
                    'created_on' => $currenttime,
                    'created_by_ip' => get_client_ip(),
                    'user_key' => generate_user_key()
                );
                $this->Janparichay_model->save_janparichay($janParichay_scefm_user_data_save);
                $this->session->regenerate(TRUE); // Regenerate session ID
            }
            $this->logUser($action = 'login');
            $this->redirect_on_login();
        }
    }

    function redirect_on_login()
    {
        if (getSessionData('login')['ref_m_usertype_id'] == USER_ADVOCATE) {
            return response()->redirect(base_url("dashboard_alt"));
            exit();
        } elseif (getSessionData('login')['ref_m_usertype_id'] == USER_DISTRICT_ADMIN) {
            return response()->redirect(base_url("adminDashboard/work_done"));
            exit();
        } elseif (getSessionData('login')['ref_m_usertype_id'] == USER_MASTER_ADMIN) {
            return response()->redirect(base_url("adminDashboard/work_done"));
            exit();
        } elseif (getSessionData('login')['ref_m_usertype_id'] == USER_ADMIN || getSessionData('login')['ref_m_usertype_id'] == USER_ACTION_ADMIN) {
            return response()->redirect(base_url("adminDashboard"));
            exit();
        } elseif (getSessionData('login')['ref_m_usertype_id'] == USER_BAR_COUNCIL) {
            return response()->redirect(base_url('Bar_council'));
            exit(0);
        } elseif (getSessionData('login')['ref_m_usertype_id'] == USER_REGISTRAR_ACTION || getSessionData('login')['ref_m_usertype_id'] == USER_REGISTRAR_VIEW) {
            return response()->redirect(base_url("registrarActionDashboard"));
            exit();
        } elseif (getSessionData('login')['ref_m_usertype_id'] == USER_LIBRARY) {
            return response()->redirect(base_url("citation/CitationController/libraryAdminDashBoard"));
            exit();
        } elseif (getSessionData('login')['ref_m_usertype_id'] == JAIL_SUPERINTENDENT) {
            return response()->redirect(base_url("jail_dashboard"));
            exit();
        } else if (getSessionData('login')['ref_m_usertype_id'] == USER_SUPER_ADMIN) {
            return response()->redirect(base_url("superAdmin"));
            exit();
        } else if (getSessionData('login')['ref_m_usertype_id'] == USER_EFILING_ADMIN) {
            return response()->redirect(base_url("filingAdmin"));
            exit();
        } elseif (getSessionData('login')['ref_m_usertype_id'] == USER_ADMIN_READ_ONLY) {
            return response()->redirect(base_url("report/search"));
            exit();
        } else {
            return response()->redirect(base_url("dashboard"));
            exit();
        }
    }

    public function logUser($action)
    {
        // logs the login and logout time
        if ($action == 'login') {
            $data['login_id'] = getSessionData('login')['id'];
            $data['is_successful_login'] = 'true';
            $data['ip_address'] = getClientIP();
            $data['login_time'] = date('Y-m-d H:i:s');
            // $data['session_detail'] = serialize($this->session->userdata());
            // $data['referrer'] = $_SERVER['HTTP_REFERER'];
            $data['referrer'] = getSessionData('login')['ref_m_usertype_id'];
            $data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
            // $data['url'] = $_SERVER['REQUEST_URI'];
            $data['processid'] = getSessionData('login')['processid'];
            $data['impersonator_user'] = json_encode(getSessionData('login')['impersonator_user']); //for efiling_assistant
        } elseif ($action == 'logout') {
            $data['log_id'] = getSessionData('login')['log_id'];
            $data['logout_time'] = date('Y-m-d H:i:s');
        }
        $this->Janparichay_model->logUser($action, $data);
    }

    public function isMobileDevice()
    {
        return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
    }

}