<?php

namespace App\Models\Cron;

use CodeIgniter\Model;
use Config\Database;
class DefaultModel extends Model {

    function __construct() {
        parent::__construct();
    }

    public function get_efiled_nums_stage_wise_list_admin_cron($stage_ids, $admin_for_type_id, $admin_for_id,$diary_no='All') {
        $builder = $this->db->table('efil.tbl_efiling_nums as en');
        $builder->SELECT(array(
            'en.registration_id', 'en.ref_m_efiled_type_id', 'en.efiling_no',
        ));
        $builder->JOIN('efil.tbl_efiling_num_status as cs', 'en.registration_id = cs.registration_id');
        $builder->JOIN('public.tbl_efiling_caveat as ec', 'en.registration_id = ec.ref_m_efiling_nums_registration_id', 'left');
        $builder->JOIN('efil.m_tbl_efiling_type as et', 'en.ref_m_efiled_type_id=et.id');
        $builder->JOIN('efil.tbl_case_details as new_case_cd', 'en.registration_id = new_case_cd.registration_id', 'left');
        $builder->JOIN('efil.tbl_misc_docs_ia as mdia', 'en.registration_id = mdia.registration_id', 'left');
        $builder->JOIN('efil.tbl_sci_cases as sc_case', 'sc_case.diary_no=mdia.diary_no AND sc_case.diary_year = mdia.diary_year', 'left');
        $builder->JOIN('efil.tbl_users users', 'users.id=en.created_by', 'left');
        $builder->WHERE('cs.is_active', 'TRUE');
        $builder->WHERE('en.is_active', 'TRUE');
        $builder->WHERE('en.efiling_for_type_id', $admin_for_type_id);
        $builder->WHERE('en.efiling_for_id', $admin_for_id);
        $builder->where('cs.stage_id', $stage_ids);
        if(!empty($diary_no) && $diary_no!='All') {
            $sc_diary_num = substr($diary_no, 0, strlen($diary_no) - 4); $sc_diary_year = substr($diary_no, -4);
            if(!empty($sc_diary_num) && $sc_diary_num!=null) { $builder->WHERE('new_case_cd.sc_diary_num',$sc_diary_num); }
            if(!empty($sc_diary_year) && $sc_diary_year!=null) { $builder->WHERE('new_case_cd.sc_diary_year',$sc_diary_year); }
        }
        $builder->orderBy('cs.activated_on', 'DESC');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResult();
        } else{
            return false;
        }
    }

    function update_icmis_case_status($registration_id, $next_stage, $curr_dt, $case_details, $objections_insert, $objections_update, $efiling_type = null) {
        $builder = $this->db->table('efil.tbl_case_details');        
        foreach($case_details as $case_detail){ 
            $builder->where('registration_id', $registration_id);
            $builder->update($case_detail); 
        } 
        if (isset($objections_insert) && !empty($objections_insert)) {
            $builder2 = $this->db->table('efil.tbl_icmis_objections');
            foreach($objections_insert as $objection_insert){ 
                $builder2->insert($objection_insert); 
            } 
        }       
        if (isset($objections_update) && !empty($objections_update)) {
            $builder3 = $this->db->table('efil.tbl_icmis_objections');
            foreach($objections_update as $objections_updates){
                $builder3->where('registration_id', $registration_id);
                $builder3->update($objections_updates); 
            } 
        } 
        $current_stage = $this->get_current_stage($registration_id);
        if ($current_stage) {
            if ($current_stage[0]['stage_id'] == $next_stage) { 
                return FALSE;
            } else{ 
                if($next_stage) { 
                    $res = $this->update_next_stage($registration_id, $next_stage, $curr_dt);
                }
            }
        }
        return TRUE;
    }

    function get_current_stage($registration_id) {
        $builder = $this->db->table('efil.tbl_efiling_num_status');
        $builder->SELECT('stage_id');
        $builder->WHERE('is_active', 'TRUE');
        $builder->WHERE('registration_id', $registration_id);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else{
            return false;
        }
    }

    function update_next_stage($registration_id, $next_stage, $curr_dt) {
        $update_data = array(
            'deactivated_on' => $curr_dt,
            'is_active' => FALSE,
            'updated_by' => AUTO_UPDATE_CRON_USER,
            'updated_by_ip' => getClientIP()
        );
        $builder = $this->db->table('efil.tbl_efiling_num_status');
        $builder->WHERE('registration_id', $registration_id);
        $builder->WHERE('is_active', TRUE);
        $builder->UPDATE($update_data);
        $insert_data = array(
            'registration_id' => $registration_id,
            'stage_id' => $next_stage,
            'activated_on' => $curr_dt,
            'is_active' => TRUE,
            'activated_by' => AUTO_UPDATE_CRON_USER,
            'activated_by_ip' => getClientIP()
        );
        $builder = $this->db->table('efil.tbl_efiling_num_status');
        $builder->INSERT($insert_data);
        if ($this->db->insertID()) {
            return TRUE;
        } else{
            return FALSE;
        }
    }

    function insertInDBwithInsertedId($tablename, $data) {
        $builder = $this->db->table($tablename);
        $builder->insert($data);
        $insertId = $this->db->insertID();
        return  $insertId;
    }

    function updateCronDetails($id) {
        $builder = $this->db->table('efil.tbl_cron_details');
        $builder->where('id', $id);
        $builder->update(['completed_at' => date('Y-m-d H:i:s')]);
    }

    public function pending_court_fee() {
        $sql = "select pay.*, cd.sc_diary_num, cd.sc_diary_year 
        from efil.tbl_court_fee_payment pay
        join efil.tbl_efiling_nums ten on ten.registration_id = pay.registration_id 
        join efil.tbl_efiling_num_status ens on (ens.registration_id = ten.registration_id and ens.is_active = true) 
        join efil.tbl_case_details cd on cd.registration_id = ten.registration_id 
        where ens.stage_id in (9, 11) and payment_status ='Y' and cd.sc_diary_num is not null and cd.sc_diary_year is not null and is_locked ='f'
        union all 
        select pay.*, tmdi.diary_no, tmdi.diary_year
        from efil.tbl_court_fee_payment pay
        join efil.tbl_misc_docs_ia tmdi on tmdi.registration_id = pay.registration_id 
        join efil.tbl_efiling_num_status ens on (ens.registration_id = tmdi.registration_id and ens.is_active = true) 
        where ens.stage_id in (14,26) and payment_status ='Y' and tmdi.diary_no  is not null and tmdi.diary_year  is not null and is_locked ='f'";
        $query = $this->db->query($sql);
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else{
            return FALSE;
        }
    }

    function get_aor_code_check_after_insert($array_data) {
        $update_data = array();
        $insert_data = array();
        $output = 'Something went wrong please contact computer cell !!';
        $bar_i = 0;
        $bar_u = 0;
        $tbl_u = 0;
        $tbl_i = 0;
        $icmis_bar_insert = $icmis_bar_updated = $efil_tbl_users_updated = $efil_tbl_users_insert = '';
        foreach ($array_data as $row) {
            $bar_id = trim($row->bar_id);
            $aor_code = trim($row->aor_code);
            $mobile = trim($row->mobile);
            $email = trim($row->email);
            $enroll_date = (trim($row->enroll_date) == '0000-00-00' || trim($row->enroll_date) == '0000-00-00 00:00:00'  || trim($row->enroll_date) == '1976-00-00' || trim($row->enroll_date) == '1982-05-00' || trim($row->enroll_date) == '1981-00-00' || trim($row->enroll_date) == '1972-01-00' || trim($row->enroll_date) == '1975-00-00' || trim($row->enroll_date) == '1975-11-00' || trim($row->enroll_date) == '1961-11-00' || trim($row->enroll_date) == '1994-09-00' || trim($row->enroll_date) == '1994-09-00' || trim($row->enroll_date) == '1996-08-00' || trim($row->enroll_date) == '2002-08-00' || trim($row->enroll_date) == '2005-08-00') ? NULL : $row->enroll_date;
            $update_data = array(
                'name' => $row->name,
                'fname' => $row->fname,
                'mname' => $row->mname,
                'paddress' => $row->paddress,
                'pcity' => $row->pcity,
                'caddress' => $row->caddress,
                'ccity' => $row->ccity,
                'phno' => $row->phno,
                'mobile' => $row->mobile,
                'email' => $row->email,
                'enroll_no' => $row->enroll_no,
                'enroll_date' => $enroll_date,
                'isdead' => $row->isdead,
                'date_of_dead' => (trim($row->date_of_dead) == '0000-00-00' || trim($row->date_of_dead) == '0000-00-00 00:00:00') ? NULL : $row->date_of_dead,
            );
            $insert_data = array(
                'bar_id' => $bar_id,
                'title' => $row->title,
                'name' => $row->name,
                'rel' => $row->rel,
                'fname' => $row->fname,
                'mname' => $row->mname,
                'dob' => (trim($row->dob) == '0000-00-00' || trim($row->dob) == '0000-00-00 00:00:00') ? NULL : $row->dob,
                'paddress' => $row->paddress,
                'pcity' => $row->pcity,
                'caddress' => $row->caddress,
                'ccity' => $row->ccity,
                'pp' => $row->pp,
                'sex' => $row->sex,
                'cast' => $row->cast,
                'phno' => $row->phno,
                'mobile' => $row->mobile,
                'email' => $row->email,
                'enroll_no' => $row->enroll_no,
                'enroll_date' => (trim($row->enroll_date) == '0000-00-00' || trim($row->enroll_date) == '0000-00-00 00:00:00') ? NULL : $row->enroll_date,
                'isdead' => $row->isdead,
                'date_of_dead' => (trim($row->date_of_dead) == '0000-00-00' || trim($row->date_of_dead) == '0000-00-00 00:00:00') ? NULL : $row->date_of_dead,
                'passing_year' => $row->passing_year,
                'if_aor' => $row->if_aor,
                'state_id' => $row->state_id,
                'bentuser' => $row->bentuser,
                'bentdt' => (trim($row->bentdt) == '0000-00-00' || trim($row->bentdt) == '0000-00-00 00:00:00') ? NULL : $row->bentdt,
                'bupuser' => $row->bupuser,
                'bupdt' => (trim($row->bupdt) == '0000-00-00' || trim($row->bupdt) == '0000-00-00 00:00:00') ? NULL : $row->bupdt,
                'aor_code' => $aor_code,
                'if_sen' => $row->if_sen,
                'sc_from_dt' => (trim($row->sc_from_dt) == '0000-00-00' || trim($row->sc_from_dt) == '0000-00-00 00:00:00') ? NULL : $row->sc_from_dt,
                'sc_to_date' => (trim($row->sc_to_date) == '0000-00-00' || trim($row->sc_to_date) == '0000-00-00 00:00:00') ? NULL : $row->sc_to_date,
                'cmis_state_id' => $row->cmis_state_id,
                'agency_code' => $row->agency_code,
                'if_other' => $row->if_other,
            );
            $update_data_tbl_users = array(
                'adv_sci_bar_id' => $bar_id,
                'bar_reg_no' => $row->enroll_no,
                'first_name' => $row->name,
                'moblie_number' => $row->mobile,
                'emailid' => $row->email,
            );
            $ref_m_usertype_id = 1;
            $default_password = '827c18c3c3f5e716886523a83db27af43004cc8b4c232667c601ce831f7fd5d8';
            $insert_data_efil_tbl_users = array(
                'userid' => $aor_code,
                'password' => $default_password,
                'ref_m_usertype_id' => $ref_m_usertype_id,
                'admin_role' => null,
                'first_name' => $row->name,
                'last_name' => null,
                'moblie_number' => $mobile,
                'emailid' => $email,
                'adv_sci_bar_id' => $bar_id,
                'bar_reg_no' => $row->enroll_no,
                'account_status' => 0,
                'login_ip' => null,
                'refresh_token' => null,
                'gender' => $row->sex,
                'dob' => (trim($row->dob) == '0000-00-00' || trim($row->dob) == '0000-00-00 00:00:00') ? null : $row->dob,
                'm_address1' => $row->paddress,
                'm_address2' => null,
                'm_city' => $row->pcity,
                'm_district_id' => null,
                'm_state_id' => $row->cmis_state_id,
                'created_on' => date('Y-m-d H:i:s'),
                'create_ip' => getClientIP(),
                'is_first_pwd_reset' => true,
                'aor_code' => $aor_code,
                'is_active' => 1,
                'pp_a' => $row->pp
            );
            if (!empty($aor_code) && !empty($insert_data)) {          
                $builder = $this->db->table('icmis.bar');
                $builder->SELECT('*');
                $builder->where('aor_code', $aor_code);
                $query = $builder->get();
                if ($query->getNumRows() >= 1) {                
                    $result = $query->getResultArray();
                    $db_bar_id = trim($result[0]['bar_id']);
                    $db_aor_code = trim($result[0]['aor_code']);
                    if (!empty($update_data) && !empty($result) && $db_bar_id == $bar_id && $db_aor_code == $aor_code) { 
                        $builder = $this->db->table('icmis.bar');
                        $builder->WHERE('bar_id', $bar_id);
                        $builder->WHERE('aor_code', $aor_code);
                        $builder->UPDATE($update_data);
                        $bar_u++;
                        $icmis_bar_updated .= $aor_code . ',';
                    }
                } else{ 
                    if (!empty($insert_data) && !empty($aor_code)) { 
                        $builder = $this->db->table('icmis.bar');
                        $builder->INSERT($insert_data);
                        $bar_i++;
                        $icmis_bar_insert .= $aor_code . ',';
                    }
                }
                $builder = $this->db->table('efil.tbl_users');
                $builder->SELECT('aor_code,adv_sci_bar_id,bar_reg_no');
                $builder->where('aor_code', $aor_code);
                $query2 = $builder->get();
                if ($query2->getNumRows() >= 1) {               
                    $builder = $this->db->table('efil.tbl_users');
                    $builder->WHERE('ref_m_usertype_id', 1);
                    $builder->WHERE('aor_code', $aor_code);
                    $builder->UPDATE($update_data_tbl_users);
                    $tbl_u++;
                    $efil_tbl_users_updated .= $aor_code . ',';
                } else{ 
                    if (!empty(($mobile) && $mobile != null) && (!empty($email) && $email != null) && (!empty($aor_code) && $aor_code != null) && (isset($row->if_aor) && $row->if_aor=='Y')) { 
                        $is_efil_tbl_users = $this->efil_tbl_users($aor_code, $mobile, $email);
                        if (empty($is_efil_tbl_users)) {
                            $builder = $this->db->table('efil.tbl_users');
                            $builder->INSERT($insert_data_efil_tbl_users);
                            $tbl_i++;
                            $efil_tbl_users_insert .= $aor_code . ',';
                        }
                    }
                }
            }
            $output = '<span style="color:green">Successfully Executed</span>';
        }
        echo '<b>Total BAR table record updated count is : ' . $bar_u . ' </b> of AOR(s) code:' . $icmis_bar_updated . '<br/>';
        echo '<b>Total BAR table record inserted count is : ' . $bar_i . ' </b> of AOR(s) code:' . $icmis_bar_insert . '<br/>';
        echo '<b>Total Users table records updated count is : ' . $tbl_u . ' </b> of AOR(s) code:' . $efil_tbl_users_updated . '<br/>';
        echo '<b>Total Users table records inserted(User Accounts Created) count is : ' . $tbl_i . ' </b> of AOR(s) code:' . $efil_tbl_users_insert . '<br/>';
        return $output;
    }

    // public function efil_tbl_users($aor_code, $mobile, $email) {
    //     $builder = $this->db->table('efil.tbl_users');
    //     $builder->SELECT('aor_code,adv_sci_bar_id,bar_reg_no,moblie_number,emailid');
    //     $builder->where('userid', $aor_code);
    //     $builder->orwhere('userid', $mobile);
    //     $builder->orWhere('moblie_number', $mobile);
    //     $builder->orWhere('emailid', $email);
    //     $query3 = $builder->get();     
    //     if ($query3->getNumRows() >= 1) {
    //         $result = $query3->getResultArray();
    //         return $result;
    //     } else{
    //         return '';
    //     }
    // }

    public function efil_tbl_users($aor_code, $mobile, $email) {
        $email = strtolower($email);
        $builder = $this->db->table('efil.tbl_users');
        $builder->SELECT('aor_code, adv_sci_bar_id, bar_reg_no, moblie_number, emailid');
        $builder->where('aor_code', $aor_code);
        $builder->orWhere("(moblie_number='$mobile' or LOWER(userid)='$mobile' or LOWER(emailid)='$email')");
        $builder->WHERE('is_deleted', false);
        $query3 = $builder->get();
        if ($query3->getNumRows() >= 1) {
            $result = $query3->getResultArray();
            return $result;
        } else {
            return '';
        }
    }

    public function get_efiled_nums_stage_wise_list_admin_cronIsDisposed($stage_ids, $admin_for_type_id, $admin_for_id) {
        $builder = $this->db->table('efil.tbl_efiling_nums as en');
        $builder->DISTINCT();
        $builder->SELECT(array('en.registration_id', 'en.ref_m_efiled_type_id', 'en.efiling_no','cs.activated_on','en.efiling_for_id','efiling_type','new_case_cd.sc_diary_num','new_case_cd.sc_diary_year','concat(new_case_cd.sc_diary_num,new_case_cd.sc_diary_year)diary_no',
        ));
        $builder->JOIN('efil.tbl_efiling_num_status as cs', 'en.registration_id = cs.registration_id');
        $builder->JOIN('efil.m_tbl_efiling_type as et', 'en.ref_m_efiled_type_id=et.id');
        $builder->JOIN('efil.tbl_case_details as new_case_cd', 'en.registration_id = new_case_cd.registration_id');
        $builder->JOIN('efil.tbl_users users', 'users.id=en.created_by', 'left');
        $builder->WHERE('cs.is_active', 'TRUE');
        $builder->WHERE('en.is_active', 'TRUE');
        $builder->WHERE('en.ref_m_efiled_type_id', E_FILING_TYPE_NEW_CASE);
        $builder->WHERE('new_case_cd.sc_diary_num is not null');
        $builder->WHERE('new_case_cd.sc_diary_year is not null');
        $builder->WHERE('en.efiling_for_type_id', $admin_for_type_id);
        $builder->WHERE('en.efiling_for_id', $admin_for_id);
        $builder->whereIn('cs.stage_id', $stage_ids);
        $builder->orderBy('cs.activated_on','DESC');
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            return $query->getResult();
        } else {
            return false;
        }
    }

    function upgradeUsers($array_data)
    {
        $output = 'Something went wrong please contact computer cell !!';
        $tbl_u = 0;
        $tbl_i = 0;
        $tbl_api_count = 0;
        $efil_tbl_users_updated = $efil_tbl_users_insert = '';
        foreach ($array_data as $index => $row_main) {
            foreach ($row_main as $row) {
                $tbl_api_count++;
                $bar_id = trim($row->adv_sci_bar_id);
                $aor_code = trim($row->aor_code);
                $mobile = trim($row->mobile_number);
                $email = trim($row->emailid);
                $ref_m_usertype_id = 1;
                $default_password = '827c18c3c3f5e716886523a83db27af43004cc8b4c232667c601ce831f7fd5d8'; // Test@4321
                $insert_data_efil_tbl_users = array(
                    'userid' => $aor_code,
                    'password' => $default_password,
                    'ref_m_usertype_id' => $ref_m_usertype_id,
                    'admin_role' => null,
                    'first_name' => $row->first_name,
                    'last_name' => null,
                    'moblie_number' => $mobile,
                    'emailid' => $email,
                    'adv_sci_bar_id' => $bar_id,
                    'bar_reg_no' => $row->bar_reg_no,
                    'account_status' => 0,
                    'login_ip' => null,
                    'refresh_token' => null,
                    'gender' => $row->gender,
                    'dob' => trim($row->dob) == '0000-00-00' || trim($row->dob) == '0000-00-00 00:00:00' ? null : $row->dob,
                    'm_address1' => $row->m_address1,
                    'm_address2' => null,
                    'm_city' => $row->m_city,
                    'm_district_id' => null,
                    'm_state_id' => $row->m_state_id,
                    'created_on' => date('Y-m-d H:i:s'),
                    'create_ip' => getClientIP(),
                    'is_first_pwd_reset' => true,
                    'aor_code' => $aor_code,
                    'is_active' => 1,
                    'pp_a' => null,
                    'is_new_user' => 'Y'
                );
                if (!empty($aor_code) && !empty($insert_data_efil_tbl_users)) {
                    $builder = $this->db->table('efil.tbl_users');
                    $builder->SELECT('aor_code,adv_sci_bar_id,bar_reg_no');
                    $builder->where('aor_code', $aor_code);
                    $query2 = $builder->get();
                    if ($query2->getNumRows() >= 1) {
                        // $this->db->WHERE('ref_m_usertype_id', 1);
                        // $this->db->WHERE('aor_code', $aor_code);
                        // $this->db->UPDATE('efil.tbl_users', $update_data_tbl_users);
                        $tbl_u++;
                        $efil_tbl_users_updated .= $aor_code . ', ';
                    } else {
                        if (!empty(($mobile) && $mobile != null) && (!empty($email) && $email != null) && (!empty($aor_code) && $aor_code != null) && (isset($row->if_aor) && $row->if_aor == 'Y')) {
                            $is_efil_tbl_users = $this->efil_tbl_users($aor_code, $mobile, $email);
                            if (empty($is_efil_tbl_users)) {
                                $this->db->table('efil.tbl_users')->INSERT($insert_data_efil_tbl_users);
                                $tbl_i++;
                                $efil_tbl_users_insert .= $aor_code . ', ';
                                // echo '<b>Total Users table records inserted count is : '.$tbl_i.' </b> of AOR(s) code : '.$efil_tbl_users_insert.'<br/>';exit();
                            }
                        }
                    }
                }
                $output = '<span style="color:green">Successfully Executed</span><br /><br />';
            }
        }
        echo '<b>API Out-Services Total Users table records matched count is : ' . ($tbl_api_count) . ' </b> ' . '<br/><br/>';
        echo '<b>Total Users table records inserted (User Accounts Created) count is : ' . $tbl_i . ' </b> of AOR(s) code : ' . $efil_tbl_users_insert . '<br/><br />';
        echo '<b>Total Users table records Matched (User Accounts Created) count is : ' . $tbl_u . ' </b> of AOR(s) code : ' . $efil_tbl_users_updated . '<br/>';
        return $output;
    }

}