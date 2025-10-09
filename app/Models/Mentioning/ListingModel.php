<?php

namespace App\Models\Mentioning;

use CodeIgniter\Model;

class ListingModel extends Model
{

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $db      = \Config\Database::connect();
    }


    function get_efiling_number($registration_id)
    {
        $builder = $this->db->table('efil.tbl_efiling_nums')
            ->select('efiling_no')
            ->where('registration_id', $registration_id)
            ->get();

        if ($builder->getNumRows() >= 1) {
            $result = $builder->getResultArray();
            return $result;
        } else {
            return false;
        }
    }

    /*Change in code on 16 September 2020*/

    function upload_pdfs($data, $file_temp_name)
    {
        $establishment_code = getSessionData('estab_details')['estab_code'];
        $registration_id = getSessionData('estab_details')['registration_id'];

        $this->db->transStart();

        $est_dir = 'uploaded_docs/' . $establishment_code;
        if (!is_dir($est_dir)) {
            $uold = umask(0);
            if (mkdir($est_dir, 0777, true)) {
                $html = '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
                write_file($est_dir . '/index.html', $html);
            }
            umask($uold);
        }

        $efil_num_dir = 'uploaded_docs/' . $establishment_code . '/' . getSessionData('estab_details')['efiling_no'];
        if (!is_dir($efil_num_dir)) {
            $uold = umask(0);
            if (mkdir($efil_num_dir, 0777, true)) {
                $html = '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
                write_file($efil_num_dir . '/index.html', $html);
            }
            umask($uold);
        }

        $file_uploaded_dir = 'uploaded_docs/' . $establishment_code . '/' . getSessionData('estab_details')['efiling_no'] . '/docs/';
        if (!is_dir($file_uploaded_dir)) {
            $uold = umask(0);
            if (mkdir($file_uploaded_dir, 0777, true)) {
                $html = '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
                write_file($file_uploaded_dir . '/index.html', $html);
            }
            umask($uold);
        }

        // Modify File Name
        $filename = $data['doc_title'];
        $filename = str_replace(' ', '_', $filename); // Replaces all spaces with underscore.
        $filename = preg_replace('/[^A-Za-z0-9\_]/', '', $filename); // Removes special chars.
        $filename = getSessionData('efiling_details')['efiling_no'] . '_' . $filename . '.pdf';

        // Upload File
        $result = $this->upload_file($file_uploaded_dir, $filename, $file_temp_name);

        // Get Page Count
        $file_path = $file_uploaded_dir . $filename;
        $page_no = (int) @exec('pdfinfo ' . $file_path . ' | awk \'/Pages/ {print $2}\'', $output);

        $data2 = array('page_no' => $page_no, 'file_name' => $filename, 'file_path' => $file_path);
        $merge_array_data = array_merge($data, $data2);

        if ($result) {
            // Insert into Database
            $builder = $this->db->table('efil.tbl_uploaded_pdfs');
            $builder->insert($merge_array_data);

            if ($this->db->InsertID()) {
                $this->db->transComplete();
                return 'trans_success';
            } else {
                unlink($file_path); // Remove uploaded file on failure
                return 'trans_failed';
            }
        } else {
            return 'upload_fail';
        }
    }


    function upload_file($file_uploaded_dir, $filename, $file)
    {
        $targetPath = $file_uploaded_dir . '/' . $filename;

        // Check if the directory exists, create if it doesn't
        if (!is_dir($file_uploaded_dir)) {
            mkdir($file_uploaded_dir, 0777, true);
        }

        $uploaded = move_uploaded_file($file->getTempName(), $targetPath);

        if ($uploaded) {
            return true;
        } else {
            return false;
        }
    }

    /*End of Change in code on 16 September 2020*/

    function get_judges($judges)
    {
        $builder = $this->db->table('icmis.judge');
        $builder->select('jcode, jname');

        if ($judges == null || $judges == '') {
            $builder->where('jname ilike', '%CHIEF%')
                ->where('is_retired', 'N')
                ->where('display', 'Y')
                ->where('jtype', 'J');
        } else {
            $builder->select("string_agg(jcode::character varying, ',' order by judge_seniority) as jcode, array_agg(jname order by judge_seniority) as jname")
                ->whereIn('jcode', explode(',', $judges))
                ->where('is_retired', 'N')
                ->where('display', 'Y')
                ->where('jtype', 'J');
        }

        $query = $builder->get();

        if ($query->getNumRows() == 1) {
            return $query->getResultArray();
        } else {
            // If no result found or multiple rows found, fall back to chief judge query
            $chiefBuilder = $this->db->table('icmis.judge')
                ->select('jcode, jname')
                ->where('jname ilike', '%CHIEF%')
                ->where('is_retired', 'N')
                ->where('display', 'Y')
                ->where('jtype', 'J');

            $chiefQuery = $chiefBuilder->get();

            if ($chiefQuery->getNumRows() == 1) {
                return $chiefQuery->getResultArray();
            }
        }

        return null; // Default return if no valid results
    }


    function get_mentioning_request_details($diary_no, $diary_year)
    {
        $builder = $this->db->table('efil.tbl_mentioning_requests as tmr');
        $builder->select('tmr.*');
        $builder->join('efil.tbl_sci_cases as tsc', 'tmr.tbl_sci_cases_id = tsc.id');
        $builder->like('tsc.diary_no', $diary_no);
        $builder->like('tsc.diary_year', $diary_year);

        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    function check_get_mentioning_request_details($efiling_no, $diary_no = null, $diary_year = null)
    {
        $builder = $this->db->table('efil.tbl_mentioning_requests as tmr');
        $builder->select('tmr.*');

        if (!empty($diary_no) && !empty($diary_year)) {
            $builder->join('efil.tbl_sci_cases as tsc', 'tmr.tbl_sci_cases_id = tsc.id');
            $builder->like('tsc.diary_no', $diary_no);
            $builder->like('tsc.diary_year', $diary_year);
        }

        $builder->where('tmr.efiling_number', $efiling_no);
        $builder->limit(1);

        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            return $query->getRow();
        } else {
            return false;
        }
    }

    function get_holidays()
    {
        $currentYear = date('Y');
        $nextYear = $currentYear + 1;

        $builder = $this->db->table('icmis.holidays');
        $builder->select('hdate');
        $builder->where('emp_hol', 1);
        $builder->where("(extract(year from hdate) = $currentYear OR extract(year from hdate) = $nextYear)");

        $query = $builder->get();
        return $query->getResultArray();
    }

    function get_case_id($diary_no, $diary_year)
    {
        $builder = $this->db->table('efil.tbl_sci_cases');
        $builder->select('id');
        $builder->like('diary_no', $diary_no);
        $builder->like('diary_year', $diary_year);

        $query = $builder->get();

        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    function insert_otp($otp_details)
    {
        $builder = $this->db->table('efil.tbl_sms_log');
        $builder->insert($otp_details);

        if ($this->db->insertID()) {
            return true;
        } else {
            return false;
        }
    }

    function add_details($details, $registration_id)
    {
        $builder = $this->db->table('efil.tbl_mentioning_requests');
        $builder->insert($details);

        $update_data = [
            'deactivated_on' => date('Y-m-d H:i:s'),
            'is_active' => FALSE,
            'updated_by' => session()->get('login.id'),
            'updated_by_ip' => $_SERVER['REMOTE_ADDR']
        ];

        $builder = $this->db->table('efil.tbl_efiling_num_status');
        $builder->where('registration_id', $registration_id);
        $builder->where('is_active', TRUE);
        $builder->update($update_data);

        $insert_data = [
            'registration_id' => $registration_id,
            'stage_id' => MENTIONING_E_FILED,
            'activated_on' => date('Y-m-d H:i:s'),
            'is_active' => TRUE,
            'activated_by' => session()->get('login.id'),
            'activated_by_ip' => $_SERVER['REMOTE_ADDR']
        ];

        $builder->insert($insert_data);

        if ($this->db->InsertID()) {
            $this->db->transComplete();
        }

        if ($this->db->transStatus() === TRUE) {
            return true;
        } else {
            return false;
        }
    }
}
