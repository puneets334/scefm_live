<?php

namespace App\Models\Mentioning;

use CodeIgniter\Model;

class MentioningModel extends Model
{

    function __construct()
    {
        parent::__construct();
        $db = \Config\Database::connect();
    }


    function generate_efil_num_n_add_case_details($diary_details, $curr_dt_time)
    {
        $builder = $this->db->table('efil.tbl_efiling_nums');

        $this->db->transStart();

        // GET NEXT EFILING NUMBER
        $generated_efil_num = $this->genEfilingNumber();

        if (!empty($generated_efil_num['efiling_num'])) {
            // Saving data to efiling nums
            $registration_id = $this->addEfilingNums($generated_efil_num, $curr_dt_time);

            if (!empty($registration_id)) {
                $misc_docs_ia_details = [
                    'registration_id' => $registration_id,
                    'ref_m_efiled_type_id' => E_FILING_TYPE_MENTIONING,
                    'diary_no' => $diary_details['diary_no'],
                    'diary_year' => $diary_details['diary_year'],
                    'adv_bar_id' => session()->get('login.adv_sci_bar_id'),
                    'adv_code' => session()->get('login.aor_code'),
                    'created_on' => $curr_dt_time,
                    'created_by' => session()->get('login.id'),
                    'created_by_ip' => $_SERVER['REMOTE_ADDR']
                ];

                // INSERT MISC CASE DETAILS IN MISC DOCS
                $status = $this->addMiscDocsIaDetails($registration_id, $misc_docs_ia_details, MEN_BREAD_CASE_DETAILS);

                if ($status) {
                    // UPDATE CASE STAGE IN EFILING NUM STATUS TABLE
                    $status = $this->updateEfilingNumStatus($registration_id, $curr_dt_time, 'Draft_Stage');

                    if ($status) {
                        // GENERATE EFILING NUM BASIC DETAILS SESSION
                        $this->getEfilingNumBasicDetails($registration_id);
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


    function gen_efiling_number()
    {
        $builder = $this->db->table('efil.m_tbl_efiling_no');

        $builder->select('men_efiling_no, men_efiling_year');
        $builder->where('entry_for_type', $_SESSION['estab_details']['efiling_for_type_id']);
        $builder->where('ref_m_establishment_id', $_SESSION['estab_details']['efiling_for_id']);
        $query = $builder->get();

        $row = $query->getRowArray();

        $p_efiling_num = $row['men_efiling_no'];
        $year = $row['men_efiling_year'];

        if ($year < date('Y')) {
            $newYear = date('Y');

            $update_data = [
                'men_efiling_no' => 1,
                'men_efiling_year' => $newYear,
                'men_updated_by' => $_SESSION['login']['id'],
                'men_updated_on' => date('Y-m-d H:i:s'),
                'men_update_ip' => $_SERVER['REMOTE_ADDR']
            ];

            $builder->where('men_efiling_no', $p_efiling_num);
            $builder->where('men_efiling_year', $year);
            $builder->where('entry_for_type', $_SESSION['estab_details']['efiling_for_type_id']);
            $builder->where('ref_m_establishment_id', $_SESSION['estab_details']['efiling_for_id']);
            $builder->update($update_data);

            if ($this->db->affectedRows() > 0) {
                $data['efiling_num'] = 1;
                $data['efiling_year'] = $newYear;
                return $data;
            } else {
                return $this->genEfilingNumber();
            }
        } else {
            $gen_efiling_num = $p_efiling_num + 1;

            $efiling_num_info = [
                'men_efiling_no' => $gen_efiling_num,
                'men_updated_by' => $_SESSION['login']['id'],
                'men_updated_on' => date('Y-m-d H:i:s'),
                'men_update_ip' => $_SERVER['REMOTE_ADDR']
            ];

            $builder->where('men_efiling_no', $p_efiling_num);
            $builder->where('entry_for_type', $_SESSION['estab_details']['efiling_for_type_id']);
            $builder->where('ref_m_establishment_id', $_SESSION['estab_details']['efiling_for_id']);
            $builder->update($efiling_num_info);

            if ($this->db->affectedRows() > 0) {
                $data['efiling_num'] = $gen_efiling_num;
                $data['efiling_year'] = $year;
                return $data;
            } else {
                return $this->genEfilingNumber();
            }
        }
    }


    public function add_efiling_nums($generated_efil_num, $curr_dt_time)
    {
        $num_pre_fix = "EM";
        $filing_type = E_FILING_TYPE_MENTIONING;
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

        $efiling_num_data = [
            'efiling_no' => $efiling_num,
            'efiling_year' => $generated_efil_num['efiling_year'],
            'efiling_for_type_id' => $_SESSION['estab_details']['efiling_for_type_id'],
            'efiling_for_id' => $_SESSION['estab_details']['efiling_for_id'],
            'ref_m_efiled_type_id' => $filing_type,
            'created_by' => $created_by,
            'create_on' => $curr_dt_time,
            'create_by_ip' => $_SERVER['REMOTE_ADDR'],
            'sub_created_by' => $sub_created_by,
            'portal_version'=>PORTAL_VERSION
        ];

        $builder = $this->db->table('efil.tbl_efiling_nums');
        $builder->insert($efiling_num_data);

        if ($this->db->affectedRows() > 0) {
            $registration_id = $this->db->insertID();
            return $registration_id;
        } else {
            return false;
        }
    }

    public function add_misc_docs_ia_details($registration_id, $case_details, $breadcrumb_step)
    {
        $this->db->table('tbl_misc_docs_ia')->insert($case_details); // Insert data into the table

        if ($this->db->affectedRows() > 0) {
            $update_status = $this->update_breadcrumbs($registration_id, $breadcrumb_step);

            if ($update_status) {
                $exists_appearing = $this->check_existence_of_appearing_for($case_details['diary_no'], $case_details['diary_year']);

                if ($exists_appearing) {
                    $update_status = $this->update_breadcrumbs($registration_id, MISC_BREAD_APPEARING_FOR);
                    return $update_status;
                } else {
                    return $update_status;
                }
            } else {
                return $update_status; // Assuming $update_status is set elsewhere
            }
        } else {
            return false;
        }
    }

    public function check_existence_of_appearing_for($diary_no, $diary_year)
    {
        $builder = $this->db->table('tbl_appearing_for as appearing'); // Table name with alias

        $builder->select('diary_num, diary_year')
            ->where('appearing.diary_num', $diary_no)
            ->where('appearing.diary_year', $diary_year)
            ->where('appearing.userid', session()->get('login')['id']) // Access session data
            ->where('appearing.is_deleted', false); // Assuming 'is_deleted' is a boolean column

        $query = $builder->get();

        if ($query->getNumRows() == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function get_efiling_num_basic_Details($registration_id)
    {
        $builder = $this->db->table('tbl_efiling_nums as en'); // Table name with alias

        $builder->select("en.registration_id, en.efiling_no, en.efiling_year, en.ref_m_efiled_type_id, et.efiling_type,
                en.efiling_for_type_id, en.efiling_for_id, 
                en.breadcrumb_status, en.signed_method, en.allocated_to,
                en.created_by, en.sub_created_by,
                misc_ia.diary_no, misc_ia.diary_year,
                cs.stage_id, cs.activated_on,
                (select payment_status from efil.tbl_court_fee_payment where registration_id = en.registration_id order by id desc limit 1 )")
            ->join('tbl_misc_docs_ia as misc_ia', 'en.registration_id = misc_ia.registration_id', 'left')
            ->join('tbl_efiling_num_status as cs', 'en.registration_id = cs.registration_id')
            ->join('m_tbl_efiling_type as et', 'en.ref_m_efiled_type_id = et.id')
            ->where('cs.is_active', true)
            ->where('cs.is_deleted', false)
            ->where('en.is_active', true)
            ->where('en.is_deleted', false)
            ->where('en.registration_id', $registration_id);

        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            $efiling_details = $query->getRowArray();

            // Set session data (replace with CodeIgniter 4 session handling)
            session()->set('efiling_details', $efiling_details);

            return true;
        } else {
            return false;
        }
    }


    public function get_establishment_details()
    {
        $builder = $this->db->table('m_tbl_establishments'); // Table name with alias

        $builder->select("id, estab_name, estab_code, access_ip,
                    enable_payment_gateway, is_charging_printing_cost, printing_cost, payment_gateway_params, pg_request_function, pg_response_function,
                    is_pet_state_required, is_pet_district_required, is_res_state_required, is_res_district_required, 
                    is_pet_mobile_required, is_pet_email_required, is_res_mobile_required, is_res_email_required,
                    is_extra_pet_state_required, is_extra_pet_district_required, is_extra_res_state_required, is_extra_res_district_required,
                    is_extra_pet_mobile_required, is_extra_pet_email_required, is_extra_res_mobile_required, is_extra_res_email_required")
            ->where('display', 'Y')
            ->orderBy('estab_name', 'asc');

        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            $estab_details = $query->getRowArray();

            // Set session data (replace with CodeIgniter 4 session handling)
            session()->set('estab_details', $estab_details);

            $_SESSION['estab_details']['efiling_for_type_id'] = E_FILING_FOR_SUPREMECOURT; // Adjust as necessary
            $_SESSION['estab_details']['efiling_for_id'] = escape_data($estab_details['id']); // Assuming escape_data() function is defined
            return true;
        } else {
            return false;
        }
    }


    public function update_efiling_num_status($registration_id, $curr_dt_time, $next_stage, $curr_stage = null)
    {
        $activate_next_stage = [
            'registration_id' => $registration_id,
            'stage_id' => $next_stage,
            'is_active' => true,
            'activated_on' => $curr_dt_time,
            'activated_by' => session()->get('login')['id'], // Access session data
            'activated_by_ip' => $_SERVER['REMOTE_ADDR'],
        ];

        if ($curr_stage !== null) {
            $deactivate_curr_stage = [
                'stage_id' => $curr_stage,
                'deactivated_on' => $curr_dt_time,
                'is_active' => false,
                'updated_by' => session()->get('login')['id'], // Access session data
                'updated_by_ip' => $_SERVER['REMOTE_ADDR'],
            ];

            $this->db->table('tbl_efiling_num_status')
                ->where('registration_id', $registration_id)
                ->where('is_active', true)
                ->update($deactivate_curr_stage);

            if ($this->db->affectedRows() > 0) {
                $this->db->table('tbl_efiling_num_status')->insert($activate_next_stage);
                return ($this->db->insertID() > 0);
            } else {
                return false;
            }
        } else {
            $this->db->table('tbl_efiling_num_status')->insert($activate_next_stage);
            return ($this->db->insertID() > 0);
        }
    }


    public function update_breadcrumbs($registration_id, $step_no)
    {
        // Update session variable
        $old_breadcrumbs = session()->get('efiling_details')['breadcrumb_status'] . ',' . $step_no;
        $old_breadcrumbs_array = explode(',', $old_breadcrumbs);
        $new_breadcrumbs_array = array_unique($old_breadcrumbs_array);

        sort($new_breadcrumbs_array);
        $new_breadcrumbs = implode(',', $new_breadcrumbs_array);
        session()->set('efiling_details.breadcrumb_status', $new_breadcrumbs); // Set session data

        // Update database record
        $this->db->table('tbl_efiling_nums')
            ->where('registration_id', $registration_id)
            ->update(['breadcrumb_status' => $new_breadcrumbs]);

        return ($this->db->affectedRows() > 0);
    }

    public function updateCaseStatus($registration_id, $next_stage)
    {
        $this->db->transStart(); // Start transaction

        // Update current active stage to inactive
        $update_data = [
            'deactivated_on' => date('Y-m-d H:i:s'),
            'is_active' => false,
            'updated_by' => session()->get('login')['id'], // Access session data
            'updated_by_ip' => $_SERVER['REMOTE_ADDR']
        ];

        $this->db->table('tbl_efiling_num_status')
            ->where('registration_id', $registration_id)
            ->where('is_active', true)
            ->update($update_data);

        if ($this->db->affectedRows() > 0) {
            // If update was successful

            $action_res = true; // Initialize action result

            if ($next_stage == Initial_Approaval_Pending_Stage) {
                // Perform additional operations for specific next stage
                $assign_to = $this->assign_efiling_number(session()->get('estab_details')['efiling_for_type_id'], session()->get('estab_details')['efiling_for_id']);

                $nums_data_update = [
                    'allocated_to' => $assign_to[0]['id'],
                    'allocated_on' => date('Y-m-d H:i:s')
                ];

                $this->db->table('tbl_efiling_nums')
                    ->where('registration_id', $registration_id)
                    ->where('is_active', true)
                    ->update($nums_data_update);

                if ($this->db->affectedRows() > 0) {
                    // If update to tbl_efiling_nums was successful
                    $data = [
                        'registration_id' => $registration_id,
                        'admin_id' => $assign_to[0]['id'],
                        'allocated_on' => date('Y-m-d H:i:s'),
                        'updated_by' => session()->get('login')['id'], // Access session data
                        'updated_on' => date('Y-m-d H:i:s'),
                        'update_ip' => $_SERVER['REMOTE_ADDR'],
                        'reason_to_allocate' => null
                    ];

                    $this->db->table('tbl_efiling_allocation')->insert($data);

                    if (!$this->db->insertID()) {
                        $action_res = false;
                    }
                } else {
                    $action_res = false;
                }
            }

            if ($action_res) {
                // Proceed to insert new status entry
                $proceed = false;

                if ($next_stage == Initial_Defects_Cured_Stage || $next_stage == DEFICIT_COURT_FEE_PAID) {
                    // Handle specific stages
                    $update_def = [
                        'is_active' => false,
                        'is_defect_cured' => true,
                        'defect_cured_date' => date('Y-m-d H:i:s'),
                        'updated_by' => session()->get('login')['id'], // Access session data
                        'ip_address' => $_SERVER['REMOTE_ADDR']
                    ];

                    $this->db->table('tbl_initial_defects')
                        ->where('registration_id', $registration_id)
                        ->where('is_active', true)
                        ->update($update_def);

                    if ($this->db->affectedRows() > 0) {
                        $proceed = true;
                    }
                } else {
                    $proceed = true;
                }

                if ($proceed) {
                    // Insert new status entry
                    $insert_data = [
                        'registration_id' => $registration_id,
                        'stage_id' => $next_stage,
                        'activated_on' => date('Y-m-d H:i:s'),
                        'is_active' => true,
                        'activated_by' => session()->get('login')['id'], // Access session data
                        'activated_by_ip' => $_SERVER['REMOTE_ADDR']
                    ];

                    $this->db->table('tbl_efiling_num_status')->insert($insert_data);

                    if ($this->db->insertID()) {
                        $this->db->transComplete(); // Complete transaction
                    }
                }
            }
        }

        if ($this->db->transStatus() === false) {
            // Check transaction status
            return false;
        } else {
            return true;
        }
    }


    public function assign_efiling_number($efiling_for_type_id, $efiling_for_id)
    {
        $todayDate = date('Y-m-d');

        $query = "SELECT users.id, users.ref_m_usertype_id, users.admin_for_type_id, users.admin_for_id, COUNT(en.allocated_on) AS efiling_num_count
            FROM efil.tbl_users AS users
            LEFT JOIN efil.tbl_efiling_nums AS en ON en.allocated_to = users.id AND DATE(en.allocated_on) = '$todayDate'
            WHERE users.admin_for_type_id = ? AND users.admin_for_id = ? AND
            users.is_deleted IS FALSE AND
            users.ref_m_usertype_id IN (?, ?)
            GROUP BY users.id, users.ref_m_usertype_id, users.admin_for_type_id, users.admin_for_id
            ORDER BY efiling_num_count";

        $query = $this->db->query($query, [$efiling_for_type_id, $efiling_for_id, USER_ADMIN, USER_ACTION_ADMIN]);

        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }
}