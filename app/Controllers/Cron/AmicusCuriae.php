<?php

namespace App\Controllers\Cron;

use App\Controllers\BaseController;
use App\Libraries\webservices\Efiling_webservices;
use App\Models\Cron\DefaultModel;
use Config\Database;

class AmicusCuriae extends BaseController
{

    protected $Default_model;
    protected $efiling_webservices;
    protected $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = Database::connect();
        $this->Default_model = new DefaultModel();
        $this->efiling_webservices = new Efiling_webservices();
    }

    public function index()
    {
        $mobile = $email = $aor_code = '';
        $amicus_curiaeDetails_data = file_get_contents(ICMIS_SERVICE_URL . '/ConsumedData/get_amicus_curiae_advocateDetails');
        if (!empty($amicus_curiaeDetails_data)) {
            $amicus_curiae_details_data = json_decode($amicus_curiaeDetails_data);
            if (isset($amicus_curiae_details_data) && isset($amicus_curiae_details_data->data)) {
                $amicus_curiaeData = $amicus_curiae_details_data->data;
                if (!empty($amicus_curiaeData)) {
                    $tbl_i = 0;
                    $efil_tbl_users_insert = '';
                    foreach ($amicus_curiaeData as $row) {
                        if (!empty($row->moblie_number) && !empty($row->emailid)) {
                            $is_mobile_number = $this->check_user_mobile_number($row->moblie_number);
                            if (empty($is_mobile_number)) {
                                $is_emailid = $this->check_user_emailid($row->emailid);
                                if (empty($is_emailid)) {
                                    $dob = NULL;
                                    if (isset($row->dob) && !empty($row->dob)) {
                                        $dob = trim($row->dob) == '0000-00-00' || trim($row->dob) == '0000-00-00 00:00:00' ? NULL : $row->dob;
                                        if (trim($dob) != '0000-00-00' || trim($dob) != '0000-00-00 00:00:00') {
                                            $dob = date('Y-m-d', strtotime($dob));
                                        }
                                    }
                                    $password = 'Test@4321';
                                    $insert_data_efil_tbl_users = array(
                                        'password' =>  hash('sha256', $password),
                                        'userid' => $row->userid,
                                        'ref_m_usertype_id' => $row->ref_m_usertype_id,
                                        'admin_role' => $row->admin_role,
                                        'first_name' => $row->first_name,
                                        'last_name' => $row->last_name,
                                        'moblie_number' => $row->moblie_number,
                                        'emailid' => $row->emailid,
                                        'adv_sci_bar_id' => $row->adv_sci_bar_id,
                                        'bar_reg_no' => $row->bar_reg_no,
                                        'account_status' => $row->account_status,
                                        'gender' => $row->gender,
                                        'dob' => $dob,
                                        'm_address1' => $row->m_address1,
                                        'm_address2' => $row->m_address2,
                                        'm_city' => $row->m_city,
                                        'm_state_id' => $row->m_state_id,
                                        'created_on' => $row->created_on,
                                        'create_ip' => $row->create_ip,
                                        'is_deleted' => $row->is_deleted,
                                        'is_first_pwd_reset' => $row->is_first_pwd_reset,
                                        'aor_code' => $row->aor_code,
                                        'is_active' => $row->is_active,
                                    );
                                    $builder = $this->db->table('efil.tbl_users');
                                    if ($builder->insert($insert_data_efil_tbl_users)) {
                                        $tbl_i++;
                                        $efil_tbl_users_insert .= $row->moblie_number . ',';
                                    }
                                }
                            }
                        }
                    }
                    echo '<b>Total Users table records inserted(User Accounts Created) count is : ' . $tbl_i . ' </b> of Moblie(s):' . $efil_tbl_users_insert . '<br/>';
                }
            }
        }
    }

    public  function check_user_mobile_number($mobile_number)
    {
        $builder = $this->db->table('efil.tbl_users');
        $builder->SELECT('userid, moblie_number, emailid');
        $builder->WHERE('is_deleted', false);
        $builder->WHERE('moblie_number', $mobile_number);
        $builder->orWhere('LOWER(userid)', $mobile_number);
        $query = $builder->get();
        return $query->getRowArray();
    }

    public  function check_user_emailid($emailid)
    {
        $builder = $this->db->table('efil.tbl_users');
        $builder->SELECT('userid, moblie_number, emailid');
        $builder->WHERE('is_deleted', false);
        $builder->WHERE('LOWER(emailid)', strtolower($emailid));
        $query = $builder->get();
        return $query->getRowArray();
    }
}
