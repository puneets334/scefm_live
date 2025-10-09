<?php

namespace App\Models\OldCaseRefiling;

use CodeIgniter\Model;

class OldEfilingDocsModel extends Model
{

    function __construct()
    {
        parent::__construct();
        $db      = \Config\Database::connect();
    }

    function generate_efil_num_n_add_case_details($diary_details, $curr_dt_time, $appearing_for = "")
    {

        $this->db->transStart();
        // GET NEXT EFILING NUMBER

        $generated_efil_num = $this->gen_efiling_number();
        $_SESSION['efiling_details'] = ['breadcrumb_status' => NULL];

        if ($generated_efil_num['efiling_num']) {

            // Saving data to efiling nums
            $registration_id = $this->add_efiling_nums($generated_efil_num, $curr_dt_time);
            if (isset($registration_id) && !empty($registration_id)) {

                $misc_docs_ia_details = array(
                    'registration_id' => $registration_id,
                    'ref_m_efiled_type_id' => OLD_CASES_REFILING,
                    'diary_no' => $diary_details['diary_no'],
                    'diary_year' => $diary_details['diary_year'],
                    'adv_bar_id' => $_SESSION['login']['adv_sci_bar_id'],
                    'adv_code' => $_SESSION['login']['aor_code'],
                    'created_on' => $curr_dt_time,
                    'created_by' => $_SESSION['login']['id'],
                    'created_by_ip' => getClientIP()
                );

                // INSERT MISC CASE DETAILS IN MISC DOCS
                $status = $this->add_misc_docs_ia_details($registration_id, $misc_docs_ia_details, MISC_BREAD_CASE_DETAILS, $appearing_for);
                //$status=1;
                if ($status) {
                    // UPDATE CASE STAGE IN EFILING NUM STATUS TABLE
                    $status = $this->update_efiling_num_status($registration_id, $curr_dt_time, Draft_Stage);

                    if ($status) {
                        // GENERATE EFILING NUM BASIC DETAILS SESSION
                        $this->get_efiling_num_basic_Details($registration_id);
                        $this->db->transComplete();
                    }
                }
            }
        }

        if ($this->db->transStatus() === FALSE) {
            return FALSE;
        } else {
            return $registration_id;
        }
    }


    public function gen_efiling_number()
    {
        $builder = $this->db->table('efil.m_tbl_efiling_no');
        // $session = session();

        $builder->SELECT('doc_efiling_no, doc_efiling_year');
        $builder->WHERE('entry_for_type', getSessionData('estab_details')['efiling_for_type_id']);
        $builder->WHERE('ref_m_establishment_id', getSessionData('estab_details')['efiling_for_id']);

        $query = $builder->get();
        $row = $query->getResultArray();

        // if (count($row) > 0) {
        $p_efiling_num = $row[0]['doc_efiling_no'];
        $year = $row[0]['doc_efiling_year'];

        if ($year < date('Y')) {
            $newYear = date('Y');
            $update_data = [
                'doc_efiling_no' => 1,
                'doc_efiling_year' => $newYear,
                'doc_updated_by' => getSessionData('login')['id'],
                'doc_updated_on' => date('Y-m-d H:i:s'),
                'doc_update_ip' => getClientIP()
            ];
            $builder->where('doc_efiling_no', $p_efiling_num);
            $builder->where('doc_efiling_year', $year);
            $builder->where('entry_for_type', getSessionData('estab_details')['efiling_for_type_id']);
            $builder->where('ref_m_establishment_id', getSessionData('estab_details')['efiling_for_id']);
            $builder->set($update_data);
            $builder->update();

            if ($this->db->affectedRows() > 0) {
                $data['efiling_num'] = 1;
                $data['efiling_year'] = $newYear;
                return $data;
            } else {
                return $this->gen_efiling_number();
            }
        } else {

            $gen_efiling_num = $p_efiling_num + 1;
            $efiling_num_info = [
                'doc_efiling_no' => $gen_efiling_num,
                'doc_updated_by' => getSessionData('login')['id'],
                'doc_updated_on' => date('Y-m-d H:i:s'),
                'doc_update_ip' => getClientIP()
            ];
            $builder->where('doc_efiling_no', $p_efiling_num);
            $builder->where('entry_for_type', getSessionData('estab_details')['efiling_for_type_id']);
            $builder->where('ref_m_establishment_id', getSessionData('estab_details')['efiling_for_id']);
            $builder->set($efiling_num_info);
            $builder->update();

            if ($this->db->affectedRows() > 0) {
                $data['efiling_num'] = $gen_efiling_num;
                $data['efiling_year'] = $year;
                return $data;
            } else {
                return $this->gen_efiling_number();
            }
        }

        // }
    }

    function add_efiling_nums($generated_efil_num, $curr_dt_time)
    {

        $num_pre_fix = "EO";
        $filing_type = OLD_CASES_REFILING;
        $stage_id = Draft_Stage;

        if ($_SESSION['login']['ref_m_usertype_id'] == USER_DEPARTMENT || $_SESSION['login']['ref_m_usertype_id'] == USER_CLERK) {
            $created_by = 0;
            $sub_created_by = $_SESSION['login']['id'];
        } else {
            $created_by = $_SESSION['login']['id'];
            $sub_created_by = 0;
        }

        $efiling = sprintf("%'.05d\n", $generated_efil_num['efiling_num']);
        $string = $num_pre_fix . $_SESSION['estab_details']['estab_code'] . $efiling . $generated_efil_num['efiling_year'];
        $efiling_num = preg_replace('/\s+/', '', $string);

        $efiling_num_data = array(
            'efiling_no' => $efiling_num,
            'efiling_year' => $generated_efil_num['efiling_year'],
            'efiling_for_type_id' => $_SESSION['estab_details']['efiling_for_type_id'],
            'efiling_for_id' => $_SESSION['estab_details']['efiling_for_id'],
            'ref_m_efiled_type_id' => $filing_type,
            'created_by' => $created_by,
            'create_on' => $curr_dt_time,
            'create_by_ip' => getClientIP(),
            'sub_created_by' => $sub_created_by,
            'portal_version'=>PORTAL_VERSION
        );
        $builder = $this->db->table('efil.tbl_efiling_nums');
        $builder->INSERT($efiling_num_data);

        if ($this->db->insertID()) {

            $registration_id = $this->db->insertID();
            return $registration_id;
        } else {
            return FALSE;
        }
    }

    function add_misc_docs_ia_details($registration_id, $case_details, $breadcrumb_step, $appearing_for = "")
    {

        $builder = $this->db->table('efil.tbl_misc_docs_ia');
        $builder->INSERT($case_details);
        if ($this->db->insertID()) {
            $update_status = $this->update_breadcrumbs($registration_id, $breadcrumb_step);

            if ($update_status) {
                return $update_status;
            }
        } else {
            return FALSE;
        }
    }

    function check_existence_of_appearing_for($diary_no, $diary_year)
    {
        $builder = $this->db->table('efil.tbl_appearing_for as appearing');
        $builder->SELECT("diary_num, diary_year");
        $builder->WHERE('appearing.diary_num', $diary_no);
        $builder->WHERE('appearing.diary_year', $diary_year);
        $builder->WHERE('appearing.userid', $_SESSION['login']['id']);
        $builder->WHERE('appearing.is_deleted', 'FALSE');

        $query = $builder->get();
        //echo $this->db->last_query();
        if ($query->getNumRows() > 0) {
            return TRUE;
        } else {
            return false;
        }
    }


    public function get_efiling_num_basic_Details($registration_id)
    {
        // defined in common model, ia_model also
        $builder = $this->db->table('efil.tbl_efiling_nums as en');
        $builder->SELECT("en.registration_id, en.efiling_no, en.efiling_year, en.ref_m_efiled_type_id, et.efiling_type,
                en.efiling_for_type_id, en.efiling_for_id, 
                en.breadcrumb_status, en.signed_method, en.allocated_to,
                en.created_by, en.sub_created_by,
                misc_ia.diary_no, misc_ia.diary_year,
                cs.stage_id, cs.activated_on,
                (select payment_status from efil.tbl_court_fee_payment where registration_id = en.registration_id order by id desc limit 1 )");

        $builder->JOIN('efil"."tbl_misc_docs_ia as misc_ia', 'en.registration_id = misc_ia.registration_id', 'left');
        $builder->JOIN('efil.tbl_efiling_num_status as cs', 'en.registration_id = cs.registration_id');
        $builder->JOIN('efil.m_tbl_efiling_type as et', 'en.ref_m_efiled_type_id = et.id');
        $builder->WHERE('cs.is_active', 'TRUE');
        $builder->WHERE('cs.is_deleted', 'FALSE');
        $builder->WHERE('en.is_active', 'TRUE');
        $builder->WHERE('en.is_deleted', 'FALSE');
        $builder->WHERE('en.registration_id', $registration_id);
        $query = $builder->get();
        //echo $this->db->last_query();exit();

        if ($query->getNumRows() >= 1) {

            $efiling_details = $query->getResultArray();
            // unset($_SESSION['efiling_details']);
            setSessionData('efiling_details', $efiling_details[0]);
            // setSessionData(array('efiling_details' => $efiling_details[0]));

            return TRUE;
        } else {
            return false;
        }
    }

    function update_efiling_num_status($registration_id, $curr_dt_time, $next_stage, $curr_stage = NULL)
    {

        $deactivate_curr_stage = array(
            'stage_id' => $curr_stage,
            'deactivated_on' => $curr_dt_time,
            'is_active' => FALSE,
            'updated_by' => $_SESSION['login']['id'],
            'updated_by_ip' => getClientIP(),
        );

        $activate_next_stage = array(
            'registration_id' => $registration_id,
            'stage_id' => $next_stage,
            'is_active' => TRUE,
            'activated_on' => $curr_dt_time,
            'activated_by' => $_SESSION['login']['id'],
            'activated_by_ip' => getClientIP(),
        );

        if ($curr_stage != NULL) {
            $builder = $this->db->table('efil.tbl_efiling_num_status');
            $builder->WHERE('registration_id', $registration_id);
            $builder->WHERE('is_active', TRUE);
            $builder->SET($deactivate_curr_stage);
            $builder->UPDATE();
            if ($this->db->affectedRows() > 0) {
                $builder->INSERT($activate_next_stage);
                if ($this->db->insertID()) {
                    return TRUE;
                } else {
                    return FALSE;
                }
            } else {
                return FALSE;
            }
        } else {
            
            $builder = $this->db->table('efil.tbl_efiling_num_status');
            $builder->INSERT($activate_next_stage);
            if ($this->db->insertID()) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

    function update_breadcrumbs($registration_id, $step_no)
    // {
        // if (isset($_SESSION['efiling_details']['breadcrumb_status']) && !empty($_SESSION['efiling_details']['breadcrumb_status'])) {
        //     $old_breadcrumbs = $_SESSION['efiling_details']['breadcrumb_status'] . ',' . $step_no;
        // } else {
        //     $old_breadcrumbs = $step_no;
        // }
    //     //$old_breadcrumbs_array = explode(',', $old_breadcrumbs);
    //     //$old_breadcrumbs = $_SESSION['efiling_details']['breadcrumb_status'] . ',' . $step_no;
    //     $old_breadcrumbs_array = explode(',', $old_breadcrumbs);

    //     $new_breadcrumbs_array = array_unique($old_breadcrumbs_array);
    //     $builder = $this->db->table('efil.tbl_efiling_nums');
        
    //     sort($new_breadcrumbs_array);
    //     $new_breadcrumbs = implode(',', $new_breadcrumbs_array);
    //     $_SESSION['efiling_details']['breadcrumb_status'] = $new_breadcrumbs;
    //     //$_SESSION['efiling_details'] = ['breadcrumb_status' => $new_breadcrumbs]; //Added for resetting breadcrumb_status
    //     // getSessionData('efiling_details')['breadcrumb_status'] = $new_breadcrumbs;

        
    //     $builder->WHERE('registration_id', $registration_id);
    //     $builder->SET(array('breadcrumb_status' => $new_breadcrumbs));
    //     $builder->UPDATE();
    //     if ($this->db->affectedRows() > 0) {
    //         return TRUE;
    //     } else {
    //         return FALSE;
    //     }
    // }

    {
        if (isset($_SESSION['efiling_details']['breadcrumb_status']) && !empty($_SESSION['efiling_details']['breadcrumb_status'])) {
            $old_breadcrumbs = $_SESSION['efiling_details']['breadcrumb_status'] . ',' . $step_no;
        } else {
            $old_breadcrumbs = $step_no;
        }
        $old_breadcrumbs_array = explode(',', $old_breadcrumbs);
        $new_breadcrumbs_array = array_unique($old_breadcrumbs_array);

        $builder = $this->db->table('efil.tbl_efiling_nums');

        sort($new_breadcrumbs_array);
        $new_breadcrumbs = implode(',', $new_breadcrumbs_array);
        getSessionData('efiling_details')['breadcrumb_status'] = $new_breadcrumbs;
        $builder->WHERE('registration_id', $registration_id);
        $builder->SET(array('breadcrumb_status' => $new_breadcrumbs));
        $builder->UPDATE();
        if ($this->db->affectedRows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}
