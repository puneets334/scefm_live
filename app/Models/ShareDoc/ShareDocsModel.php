<?php
namespace App\Models\ShareDoc;

use CodeIgniter\Model;
class ShareDocsModel extends Model {

    function __construct() {
        parent::__construct();
    }

    function update_breadcrumbs($registration_id, $step_no) {

        $sessionBreadcrumbs = getSessionData('login')['efiling_details']['breadcrumb_status'] ?? '';
        $oldBreadcrumbs = $sessionBreadcrumbs . ',' . $step_no;
        $oldBreadcrumbsArray = explode(',', $oldBreadcrumbs);
        $newBreadcrumbsArray = array_unique($oldBreadcrumbsArray);
    
        sort($newBreadcrumbsArray);
        $newBreadcrumbs = implode(',', $newBreadcrumbsArray);
    
        // Update session variable
        $_SESSION['efiling_details']['breadcrumb_status'] = $newBreadcrumbs;
    
        // Update database
        $builder = $this->db->table('efil.tbl_efiling_nums');
        $builder->where('registration_id', $registration_id);
        $builder->set(['breadcrumb_status' => $newBreadcrumbs]);
        $builder->update();
        $query = $builder->get(); 
   
        if ($this->db->affectedRows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    function remove_breadcrumb($registration_id, $breadcrumb_to_remove) {

        $sessionBreadcrumbs = getSessionData('login')['efiling_details']['breadcrumb_status'] ?? '';
        $breadcrumbsArray = explode(',', $sessionBreadcrumbs);
    
        if (in_array($breadcrumb_to_remove, $breadcrumbsArray)) {
            $index = array_search($breadcrumb_to_remove, $breadcrumbsArray);
            if ($index !== false) {
                unset($breadcrumbsArray[$index]);
            }
    
            $newBreadcrumbs = implode(',', $breadcrumbsArray);
    
            // Update session variable
            $_SESSION['efiling_details']['breadcrumb_status'] = $newBreadcrumbs;
    
            // Update database
            
            $builder = $this->db->table('efil.tbl_efiling_nums');
            $builder->where('registration_id', $registration_id);
            $builder->set(['breadcrumb_status' => $newBreadcrumbs]);
            $builder->update();
    
            if ($this->db->affectedRows() > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    function add_update_adv_details($registration_id, $data) {

    $builder = $this->db->table('efil.tbl_case_advocates');
    $builder->insert($data);

    if ($this->db->insertID() > 0) {
        return true;
    } else {
        return false;
    }
    }

    function add_doc_share_email($data) {

        $builder = $this->db->table('efil.tbl_doc_share_email');   
        $builder->insert($data);    
        if ($this->db->insertID() > 0) {
            return true;
        } else {
            return false;
        }
    }

    //XXXXXXXXXXXXXXXXXXX START XXXXXXXXXXXXXXXXXXXX
    function add_doc_share_email_updated($userId,$data) {

        $builder = $this->db->table('efil.tbl_doc_share_email');    
        $builder->where('id', $userId);
        $builder->set($data);
        $builder->update();    
        if ($this->db->affectedRows() == 1) {
            return true;
        } else {
            return false;
        }
    }
    //XXXXXXXXXXXXXXXXXXXX END XXXXXXXXXXXXXXX
    function get_aor_contact() {

        $builder = $this->db->table('icmis.bar');   
        $builder->select('*');
        $builder->where('email IS NOT NULL', null, false); // This replaces WHERE "email !=", ''
        $query = $builder->get();        
        if ($query->getResult()) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    public function get_email_details($regId) {

    $builder = $this->db->table('efil.tbl_doc_share_email');
    $builder->select('*');
    $builder->where('is_active', true); // Assuming 't' indicates true in your database
    $builder->where('registration_id', $regId);   
    $query = $builder->get();   
    if ($query->getNumRows() > 0) {
        return $query->getResult();
    } else {
        return false;
    }
    }

    public function get_contact() {

        $builder = $this->db->table('efil.tbl_case_contacts');
    
        $builder->select('*');
        $builder->where('p_email IS NOT NULL');
        $builder->where('created_by', $_SESSION['login']['id']);
        
        $query = $builder->get();
        
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }
    function get_case_aor_contacts($registration_id){

        $builder = $this->db->table('efil.tbl_misc_docs_ia tmdi');
    
        $builder->select('b.name, b.email');
        $builder->join('efil.tbl_appearing_for taf', 'tmdi.diary_no = taf.diary_num AND tmdi.diary_year = taf.diary_year');
        $builder->join('efil.tbl_users tu', 'taf.userid = tu.id');
        $builder->join('icmis.bar b', 'tu.adv_sci_bar_id = b.bar_id');
        $builder->where('tmdi.registration_id', $registration_id);
        //$builder->where('b.aor_code !=', $_SESSION['login']['aor_code']); // Uncomment to add the additional condition
    
        $query = $builder->get();
        
        if ($query->getNumRows() >= 1) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }


    //Function added on 29 jan 21 to delete user
    function deleteUserfromShareDoc($user_id, $reg_id) {

        if (!isset($_SESSION['efiling_details']['registration_id']) || empty($_SESSION['efiling_details']['registration_id'])) {
            return FALSE;
        }

        $this->db->transStart();
		$builder = $this->db->table('efil.tbl_doc_share_email');
        $data = array('is_active' => 'f', 'deleted_by' =>$_SESSION['login']['id'], 'deleted_on' => date('Y-m-d H:i:s'), 'deleted_ip' => getClientIP());
        $builder->WHERE('registration_id', $reg_id);
        $builder->WHERE('id', $user_id);
        $builder->SET($data);
        $builder->UPDATE();
        if($this->db->affectedRows() == 1) {
            $email_details=$this->get_email_details($reg_id);
            if (empty($email_details)){
                $status = $this->remove_breadcrumb($reg_id, MISC_BREAD_SHARE_DOC);
            }
            $this->db->transComplete();
        }

        if ($this->db->transStatus() === FALSE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }
}
