<?php

namespace App\Controllers\Cron;

use App\Controllers\BaseController;
use Config\Database;
use App\Models\Cron\DefaultModel;
use App\Libraries\webservices\Efiling_webservices;

class UpgradeUsers extends BaseController
{

    protected $db;
    protected $db2;
    protected $db3;
    protected $db4;
    protected $Default_model;
    protected $efiling_webservices;

    public function __construct()
    {
        parent::__construct();
        $this->db = Database::connect('default');
        $this->db2 = Database::connect('physical_hearing');
        $this->db3 = Database::connect('appearance');
        $this->db4 = Database::connect('e_services');
        $this->Default_model = new DefaultModel();
        $this->efiling_webservices = new Efiling_webservices();
    }

    public function get_efiling_count_report()
    {
        $sql = "SELECT 
        COUNT(distinct tu.id) total_users,        
        SUM(CASE WHEN (tu.ref_m_usertype_id= 1 and tu.aor_code is not null) THEN 1 ELSE 0 END) total_aor,
        SUM(CASE WHEN (tu.ref_m_usertype_id= 23 and tu.aor_code is not null) THEN 1 ELSE 0 END) total_aor_amicus_curiae,
        SUM(CASE WHEN (tu.ref_m_usertype_id= 2) THEN 1 ELSE 0 END) total_party_in_person,
        SUM(CASE WHEN (tu.ref_m_usertype_id= 3) THEN 1 ELSE 0 END) total_admin,
        SUM(CASE WHEN (tu.ref_m_usertype_id= 4) THEN 1 ELSE 0 END) total_super_admin,
        SUM(CASE WHEN (tu.ref_m_usertype_id= 10) THEN 1 ELSE 0 END) total_department,
        SUM(CASE WHEN (tu.ref_m_usertype_id= 12) THEN 1 ELSE 0 END) total_clerk,
        SUM(CASE WHEN (tu.ref_m_usertype_id= 14) THEN 1 ELSE 0 END) total_user_registrar_action,
        SUM(CASE WHEN (tu.ref_m_usertype_id= 15) THEN 1 ELSE 0 END) total_user_registrar_view,
        SUM(CASE WHEN (tu.ref_m_usertype_id= 16) THEN 1 ELSE 0 END) total_library,
        SUM(CASE WHEN (tu.ref_m_usertype_id= 17) THEN 1 ELSE 0 END) total_jail_superintendent,
        SUM(CASE WHEN (tu.ref_m_usertype_id= 19) THEN 1 ELSE 0 END) total_sr_advocate,
        SUM(CASE WHEN (tu.ref_m_usertype_id= 20) THEN 1 ELSE 0 END) total_user_efiling_admin,
        SUM(CASE WHEN (tu.ref_m_usertype_id= 21) THEN 1 ELSE 0 END) total_arguing_counsel,
        SUM(CASE WHEN (tu.ref_m_usertype_id= 22) THEN 1 ELSE 0 END) total_read_only_admin,
        SUM(CASE WHEN (tu.ref_m_usertype_id= 24 and tu.aor_code is not null) THEN 1 ELSE 0 END) total_authenticated_by_aor
        FROM efil.tbl_users tu 
        WHERE tu.is_active='1' and tu.is_deleted is false";
        $result = $this->db->query($sql)->getResultArray();

        echo 'E-filing User Details : <pre>';
        print_r($result[0]);

        $builder = $this->db2->table('physical_hearing_advocate_vc_consent');
        $builder->distinct()->select('advocate_id');
        $totalRecords = $builder->countAllResults();
        echo '<br /><br /> Physical Hearing User Details : total_users => ';
        print_r($totalRecords);

        $builder1 = $this->db3->table('aor_verify_otp');
        $builder1->select('mobile, email')->distinct();
        $totalRecords1 = $builder1->countAllResults(false);
        echo '<br /><br /> Appearance User Details : total_users => ';
        print_r($totalRecords1);

        $builder2 = $this->db4->table('verify_email');
        $builder2->select('mobile, email')->distinct()->where('c_status', 1)->where('filed_by', 1);
        $totalRecords2 = $builder2->countAllResults(false);
        echo '<br /><br /> Ecopying User Details : total_users => ';
        print_r($totalRecords2);
        die();
    }

    public function migratePHAORs()
    {
        $type = 'P';
        $builder = $this->db2->table('physical_hearing_advocate_vc_consent');
        $builder->distinct()->select('advocate_id');
        $physical = $builder->get()->getResultArray();
        $missing_advocates = [];
        $res = [];
        if ($physical) {
            foreach ($physical as $key => $adv_id) {
                $uSql = "SELECT DISTINCT adv_sci_bar_id FROM efil.tbl_users WHERE adv_sci_bar_id = ?";
                $query = $this->db->query($uSql, [$adv_id['advocate_id']]);
                $row = $query->getRow();
                if ($row) {
                    $res[] = $row;
                } else {
                    $missing_advocates[] = $adv_id['advocate_id'];
                }
            }
            echo '<b>Total Users table records matched count is : ' . count($res) . ' </b> ' . '<br/><br/>';
            echo '<b>Total Users table records not matched count is : ' . count($missing_advocates) . ' </b> ' . '<br/><br/>';
            if (!empty($missing_advocates)) {
                $bar_adv = array_chunk($missing_advocates, 100);
                $aor_data = array();
                foreach ($bar_adv as $index => $chunk) {
                    $aor_data_main = $this->efiling_webservices->get_bar_details($chunk, $type);
                    if (!empty($aor_data_main)) {
                        $aor_data[] = $aor_data_main;
                    } else {
                        $missing_chunk_ids = array_column($chunk, 'advocate_id');
                        echo "No AOR data found for advocate IDs : " . implode(', ', $missing_chunk_ids) . "<br>";
                    }
                }
                $this->Default_model->upgradeUsers($aor_data);
            } else {
                echo "All advocate IDs found in Users Table.<br>";
            }
        } else {
            echo "No Records Found for Physical Hearing.<br>";
        }
    }

    public function migrateAPAORs()
    {
        $type = 'A';
        $builder = $this->db3->table('aor_verify_otp');
        $builder->distinct()->select('mobile, email');
        $appearance = $builder->get()->getResultArray();
        $missing_advocates = [];
        $res = [];
        if ($appearance) {
            foreach ($appearance as $key => $adv_details) {
                $uSql = "SELECT DISTINCT adv_sci_bar_id FROM efil.tbl_users WHERE moblie_number = ? AND LOWER(emailid) = ?";
                $query = $this->db->query($uSql, [$adv_details['mobile'], strtolower($adv_details['email'])]);
                $row = $query->getRow();
                if ($row) {
                    $res[] = $row;
                } else {
                    $missing_advocates[] = $adv_details;
                }
            }
            echo '<b>Total Users table records matched count is : ' . count($res) . ' </b> ' . '<br/><br/>';
            echo '<b>Total Users table records not matched count is : ' . count($missing_advocates) . ' </b> ' . '<br/><br/>';
            if (!empty($missing_advocates)) {
                $bar_adv = array_chunk($missing_advocates, 100);
                $aor_data = array();
                foreach ($bar_adv as $index => $chunk) {
                    $aor_data_main = $this->efiling_webservices->get_bar_details($chunk, $type);
                    if (!empty($aor_data_main)) {
                        $aor_data[] = $aor_data_main;
                    } else {
                        $missing_chunk_ids = array_column($chunk, 'advocate_id');
                        echo "No AOR data found for advocate IDs : " . implode(', ', $missing_chunk_ids) . "<br>";
                    }
                }
                $this->Default_model->upgradeUsers($aor_data);
            } else {
                echo "All advocate IDs found in Users Table.<br>";
            }
        } else {
            echo "No Records Found for Appearance.<br>";
        }
    }

    public function migrateECAORs()
    {
        $type = 'E';
        $res = [];
        $builder = $this->db4->table('verify_email');
        $builder->distinct()->select('mobile, email')->where('c_status', 1)->where('filed_by', 1);
        $ecopying = $builder->get()->getResultArray();
        $missing_advocates = [];
        $res = [];
        if ($ecopying) {
            foreach ($ecopying as $key => $adv_details) {
                $uSql = "SELECT DISTINCT adv_sci_bar_id FROM efil.tbl_users WHERE moblie_number = ? AND LOWER(emailid) = ?";
                $query = $this->db->query($uSql, [$adv_details['mobile'], strtolower($adv_details['email'])]);
                $row = $query->getRow();
                if ($row) {
                    $res[] = $row;
                } else {
                    $missing_advocates[] = $adv_details;
                }
            }
            echo '<b>Total Users table records matched count is : ' . count($res) . ' </b> ' . '<br/><br/>';
            echo '<b>Total Users table records not matched count is : ' . count($missing_advocates) . ' </b> ' . '<br/><br/>';
            if (!empty($missing_advocates)) {
                $bar_adv = array_chunk($missing_advocates, 100);
                $aor_data = array();
                foreach ($bar_adv as $index => $chunk) {
                    $aor_data_main = $this->efiling_webservices->get_bar_details($chunk, $type);
                    if (!empty($aor_data_main)) {
                        $aor_data[] = $aor_data_main;
                    } else {
                        $missing_chunk_ids = array_column($chunk, 'advocate_id');
                        echo "No AOR data found for advocate IDs : " . implode(', ', $missing_chunk_ids) . "<br>";
                    }
                }
                $this->Default_model->upgradeUsers($aor_data);
            } else {
                echo "All advocate IDs found in Users Table.<br>";
            }
        } else {
            echo "No Records Found for Ecopying.<br>";
        }
    }

    public function migrateECPIPs()
    {
        $output = 'Something went wrong please contact computer cell !!';
        $tbl_i = 0;
        $tbl_api_count = 0;
        $efil_tbl_users_insert = '';
        $sql = 'SELECT DISTINCT ON ("verify_email"."email", "verify_email"."mobile")
            "verify_email"."email",
            "verify_email"."mobile",
            "verify_email"."filed_by", 
            COALESCE("user_address"."first_name", \'PIP@\' || COALESCE("verify_email"."mobile"::text, \'unknown\')) AS first_name,
            "user_address"."second_name",
            "user_address"."address"
        FROM "verify_email"
        LEFT JOIN "user_address" 
            ON "user_address"."email" = "verify_email"."email" 
            AND "user_address"."mobile" = "verify_email"."mobile"
        WHERE "verify_email"."c_status" = 1
        AND "verify_email"."filed_by" = 2';
        $builder = $this->db4->query($sql);
        $ecopying = $builder->getResultArray();
        $missing_advocates = [];
        $res = [];
        if ($ecopying) {
            foreach ($ecopying as $key => $adv_details) {
                $uSql = "SELECT DISTINCT * FROM efil.tbl_users WHERE moblie_number = ? AND LOWER(emailid) = ?";
                $query = $this->db->query($uSql, [$adv_details['mobile'], strtolower($adv_details['email'])]);
                $row = $query->getRow();
                if ($row) {
                    $res[] = $row;
                } else {
                    $missing_advocates[] = $adv_details;
                }
            }
            echo '<b>Total Users table records matched count is : ' . count($res) . ' </b> ' . '<br/><br/>';
            echo '<b>Total Users table records not matched count is : ' . count($missing_advocates) . ' </b> ' . '<br/><br/>';
            if (!empty($missing_advocates)) {
                $bar_adv = array_chunk($missing_advocates, 100);
                foreach ($bar_adv as $index => $chunk) {
                    foreach ($chunk as $index => $row_main) {
                        $tbl_api_count++;
                        $mobile = trim($row_main['mobile']);
                        $email = trim($row_main['email']);
                        $ref_m_usertype_id = $row_main['filed_by'];
                        $default_password = '827c18c3c3f5e716886523a83db27af43004cc8b4c232667c601ce831f7fd5d8'; // Test@4321
                        $insert_data_efil_tbl_users = array(
                            'userid' => $mobile,
                            'password' => $default_password,
                            'ref_m_usertype_id' => $ref_m_usertype_id,
                            'admin_role' => null,
                            'first_name' => $row_main['first_name'],
                            'last_name' => $row_main['second_name'],
                            'moblie_number' => $mobile,
                            'emailid' => $email,
                            'adv_sci_bar_id' => null,
                            'bar_reg_no' => null,
                            'account_status' => 0,
                            'login_ip' => null,
                            'refresh_token' => null,
                            'gender' => null,
                            'dob' => null,
                            'm_address1' => $row_main['address'],
                            'm_address2' => null,
                            'm_city' => null,
                            'm_district_id' => null,
                            'm_state_id' => null,
                            'created_on' => date('Y-m-d H:i:s'),
                            'create_ip' => getClientIP(),
                            'is_first_pwd_reset' => true,
                            'aor_code' => null,
                            'is_active' => 1,
                            'pp_a' => null,
                            'is_new_user' => 'Y'
                        );
                        $this->db->table('efil.tbl_users')->INSERT($insert_data_efil_tbl_users);
                        $tbl_i++;
                        $efil_tbl_users_insert .= $mobile . ', ';
                        $output = '<span style="color:green">Successfully Executed</span><br /><br />';
                    }
                }
                echo '<b>Total Users table records inserted (User Accounts Created) count is : ' . $tbl_i . ' </b> of AOR(s) code : ' . $efil_tbl_users_insert . '<br/><br />';
                return $output;
            } else {
                echo "All advocate IDs found in Users Table.<br>";
            }
        } else {
            echo "No Records Found for Ecopying PIP or Arguing Counsel.<br>";
        }
    }

    public function migrateECArgCouns()
    {
        $output = 'Something went wrong please contact computer cell !!';
        $tbl_i = 0;
        $tbl_api_count = 0;
        $efil_tbl_users_insert = '';
        $sql = 'SELECT DISTINCT ON ("verify_email"."email", "verify_email"."mobile")
            "verify_email"."email",
            "verify_email"."mobile",
            "verify_email"."filed_by", 
            COALESCE("user_address"."first_name", \'Arguing Counsel@\' || COALESCE("verify_email"."mobile"::text, \'unknown\')) AS first_name,
            "user_address"."second_name",
            "user_address"."address"
        FROM "verify_email"
        LEFT JOIN "user_address" 
            ON "user_address"."email" = "verify_email"."email" 
            AND "user_address"."mobile" = "verify_email"."mobile"
        WHERE "verify_email"."c_status" = 1
        AND "verify_email"."filed_by" = 3';
        $builder = $this->db4->query($sql);
        $ecopying = $builder->getResultArray();
        $missing_advocates = [];
        $res = [];
        if ($ecopying) {
            foreach ($ecopying as $key => $adv_details) {
                $uSql = "SELECT DISTINCT * FROM efil.tbl_users WHERE moblie_number = ? AND LOWER(emailid) = ?";
                $query = $this->db->query($uSql, [$adv_details['mobile'], strtolower($adv_details['email'])]);
                $row = $query->getRow();
                if ($row) {
                    $res[] = $row;
                } else {
                    $missing_advocates[] = $adv_details;
                }
            }
            echo '<b>Total Users table records matched count is : ' . count($res) . ' </b> ' . '<br/><br/>';
            echo '<b>Total Users table records not matched count is : ' . count($missing_advocates) . ' </b> ' . '<br/><br/>';
            if (!empty($missing_advocates)) {
                $bar_adv = array_chunk($missing_advocates, 100);
                foreach ($bar_adv as $index => $chunk) {
                    foreach ($chunk as $index => $row_main) {
                        $tbl_api_count++;
                        $mobile = trim($row_main['mobile']);
                        $email = trim($row_main['email']);
                        $ref_m_usertype_id = 21;
                        $default_password = '827c18c3c3f5e716886523a83db27af43004cc8b4c232667c601ce831f7fd5d8'; // Test@4321
                        $insert_data_efil_tbl_users = array(
                            'userid' => $mobile,
                            'password' => $default_password,
                            'ref_m_usertype_id' => $ref_m_usertype_id,
                            'admin_role' => null,
                            'first_name' => $row_main['first_name'],
                            'last_name' => $row_main['second_name'],
                            'moblie_number' => $mobile,
                            'emailid' => $email,
                            'adv_sci_bar_id' => null,
                            'bar_reg_no' => null,
                            'account_status' => 0,
                            'login_ip' => null,
                            'refresh_token' => null,
                            'gender' => null,
                            'dob' => null,
                            'm_address1' => $row_main['address'],
                            'm_address2' => null,
                            'm_city' => null,
                            'm_district_id' => null,
                            'm_state_id' => null,
                            'created_on' => date('Y-m-d H:i:s'),
                            'create_ip' => getClientIP(),
                            'is_first_pwd_reset' => true,
                            'aor_code' => null,
                            'is_active' => 1,
                            'pp_a' => null,
                            'is_new_user' => 'Y'
                        );
                        $this->db->table('efil.tbl_users')->INSERT($insert_data_efil_tbl_users);
                        $tbl_i++;
                        $efil_tbl_users_insert .= $mobile . ', ';
                        $output = '<span style="color:green">Successfully Executed</span><br /><br />';
                    }
                }
                echo '<b>Total Users table records inserted (User Accounts Created) count is : ' . $tbl_i . ' </b> of AOR(s) code : ' . $efil_tbl_users_insert . '<br/><br />';
                return $output;
            } else {
                echo "All advocate IDs found in Users Table.<br>";
            }
        } else {
            echo "No Records Found for Ecopying PIP or Arguing Counsel.<br>";
        }
    }

    public function sendMailSMSNewUsers() {
        // comment before going live
        return 'Hello Team!'; exit(0);
        // 
        $uSql = "SELECT userid, first_name, moblie_number, emailid FROM efil.tbl_users WHERE is_new_user = 'Y'";
        $query = $this->db->query($uSql);
        $row = $query->getResultArray();
        if (!empty($row)) {
            $new_users = array_chunk($row, 100);
            foreach ($new_users as $index => $chunk) {
                foreach ($chunk as $index => $row_main) {
                    $mail_message = '<h1>Welcome to Our Service!</h1><p>Dear '.$row_main['first_name'].',</p><p>Your new Login Credentials are as below -- <br />Login ID - '.$row_main['userid'].'<br />Password - Test@4321<br /> Please click the link below to login.</p><p><a href="'.base_url().'" target="_blank" rel="nofollow noopener noreferrer">Login</a></p>
                    <p>Best Regards,<br />Supreme Court of India</p>';
                    send_mail_msg($row_main['emailid'], 'Login', $mail_message, $row_main['first_name']);
                    $sms_message = 'Dear '.$row_main['first_name'].',<br />Your new Login Credentials are as below -- <br />Login ID - '.$row_main['userid'].'<br />Password - Test@4321<br /> Please click the link below to login.<a href="'.base_url().'" target="_blank" rel="nofollow noopener noreferrer">Login</a>. - Supreme Court of India';
                    sendSMS(38, $row_main['moblie_number'], $sms_message, SCISMS_efiling_OTP);
                }
            }
            echo "Login Credentials for new users have been shared over mail and SMS!";
        } else {
            echo "No Users Found for sending mail and SMS!";
        }
    }

}
