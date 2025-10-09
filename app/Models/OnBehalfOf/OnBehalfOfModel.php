<?php

namespace App\Models\OnBehalfOf;

use CodeIgniter\Model;

class OnBehalfOfModel extends Model
{

    function __construct()
    {
        parent::__construct();
    }
    
    function get_case_parties_list($registration_id)
    {

        $builder = $this->db->table('efil.tbl_misc_docs_ia misc_ia');
        $builder->SELECT("sc.*");
        $builder->JOIN('efil.tbl_sci_cases sc', 'misc_ia.diary_no = sc.diary_no AND misc_ia.diary_year = sc.diary_year');
        $builder->WHERE('misc_ia.registration_id', $registration_id);
        $builder->WHERE('misc_ia.is_deleted', FALSE);
        $builder->WHERE('sc.is_deleted', FALSE);
        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }

    function get_appearing_for_details($registration_id, $advocate_id)
    {
        $builder = $this->db->table('efil.tbl_appearing_for appearing');
        $builder->SELECT("appearing.*, misc_ia.filing_for_parties, misc_ia.is_govt_filing");
        $builder->JOIN('efil.tbl_misc_docs_ia misc_ia', 'misc_ia.diary_no = appearing.diary_num AND misc_ia.diary_year = appearing.diary_year');
        $builder->WHERE('misc_ia.registration_id', $registration_id);
        $builder->WHERE('appearing.userid', $advocate_id);
        $builder->WHERE('appearing.is_deleted', FALSE);
        $builder->WHERE('misc_ia.is_deleted', FALSE);

        $query = $builder->get();
        if ($query->getNumRows() >= 1) {
            $result = $query->getResultArray();
            return $result;
        } else {
            return FALSE;
        }
    }

    public function update_filing_for($updateFilingForDetail, $registrationId)
    {
        $builder = $this->db->table('efil.tbl_misc_docs_ia');
        $builder->where('registration_id', $registrationId);
        $builder->set($updateFilingForDetail);
        $builder->update();
        $this->update_breadcrumbs($registrationId, MISC_BREAD_ON_BEHALF_OF);
        $this->db->transComplete();
        if ($this->db->transStatus() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    // Assuming this is a method in the same class to handle breadcrumbs
    private function updateBreadcrumbs($registrationId, $breadcrumbType)
    {
        // Your logic to update breadcrumbs
    }

    public function update_breadcrumbs($registrationId, $stepNo)
    {
        $session = session();
        $efilingDetails = getSessionData('efiling_details');
        
        $oldBreadcrumbs = $efilingDetails['breadcrumb_status'] . ',' . $stepNo;
        $oldBreadcrumbsArray = explode(',', $oldBreadcrumbs);
        $newBreadcrumbsArray = array_unique($oldBreadcrumbsArray);
        sort($newBreadcrumbsArray);
        $newBreadcrumbs = implode(',', $newBreadcrumbsArray);
        $efilingDetails['breadcrumb_status'] = $newBreadcrumbs;

        setSessionData('efiling_details', $efilingDetails);

        $builder = $this->db->table('efil.tbl_efiling_nums');
        $builder->where('registration_id', $registrationId);
        $builder->set(['breadcrumb_status' => $newBreadcrumbs]);
        $builder->update();
        // pr($registrationId);
        if ($this->db->affectedRows() > 0)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
}
