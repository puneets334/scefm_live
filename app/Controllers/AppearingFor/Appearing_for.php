<?php

namespace App\Controllers\AppearingFor;

use App\Controllers\BaseController;
use App\Models\AppearingFor\AppearingForModel;
use App\Models\MiscellaneousDocs\GetDetailsModel;

class Appearing_for extends BaseController {

    protected $AppearingForModel;
    protected $GETDetailsModel;

    public function __construct() {
        parent::__construct();
        if(empty(getSessionData('login'))) {
            return response()->redirect(base_url('/')); 
        } else{
            is_user_status();
        }
        $this->AppearingForModel = new AppearingForModel();
        $this->GETDetailsModel= new GETDetailsModel();
    }

    public function save_appearing_details() {
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK,AMICUS_CURIAE_USER);
        if (!empty($_SESSION['login']) && !in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            echo '2@@@' . htmlentities('Unauthorised Access!', ENT_QUOTES);
            exit(0);
        }
        $stages_array = array(Draft_Stage, Initial_Defected_Stage, I_B_Defected_Stage);
        if (!empty($_SESSION['efiling_details']) && !in_array($_SESSION['efiling_details']['stage_id'], $stages_array)) {
            echo '2@@@' . htmlentities('Unauthorised Access!', ENT_QUOTES);
            exit(0);
        } 
        if(isset($_SESSION['efiling_details']) && !empty($_SESSION['efiling_details'])) {
            $registration_id = $_SESSION['efiling_details']['registration_id'];
            $party_type = isset($_POST['user_type'])?escape_data($_POST['user_type']):'';
            $party_name = isset($_POST['party_name'])?$_POST['party_name']:'';
            $parties_selected = isset($_POST['selected_party'])?$_POST['selected_party']:'';
            $party_email = isset($_POST['party_email'])?$_POST['party_email']:'';
            $party_mobile = isset($_POST['party_mob'])?$_POST['party_mob']:'';
            $appearing_for = $contact_p_name = $contact_p_email = $contact_p_mobile = $contact_partyid = '';
            if(isset($parties_selected)  && is_array($parties_selected)) {
                foreach ($parties_selected as $seleced_party) {
                    $selected_parties_details = url_decryption($seleced_party);    
                    $selected_parties_details = explode('$$$', $selected_parties_details);
                    $parties_sr_no = $selected_parties_details[1];
                    $party_id_sequence = $selected_parties_details[0] - 1;    
                    $appearing_for .= $parties_sr_no . '$$';    
                    $contact_p_name .= strtoupper($party_name[$party_id_sequence]) . '$$';
                    $contact_p_email .= strtoupper($party_email[$party_id_sequence]) . '$$';
                    $contact_p_mobile .= $party_mobile[$party_id_sequence] . '$$';
                    $contact_partyid .= $parties_sr_no . '$$';    
                    $appearing_for_tbl_id = $selected_parties_details[2];
                    $contact_tbl_id = $selected_parties_details[3];
                }
            }
            if (!(int) $appearing_for_tbl_id && !(int) $contact_tbl_id) {
                // INSERT APPEARING FOR AND CONTACT DETAILS OF PARTIES AND UPDATE BREADCRUMB
                $appearing_party_detail = array(
                    'userid' => $_SESSION['login']['id'],
                    'diary_num' => $_SESSION['efiling_details']['diary_no'],
                    'diary_year' => $_SESSION['efiling_details']['diary_year'],
                    'partytype' => $party_type,
                    'appearing_for' => !empty($appearing_for) ? rtrim($appearing_for, '$$') : '',
                    'created_by' => $_SESSION['login']['id'],
                    'created_by_ip' => $_SERVER['REMOTE_ADDR'],
                    'created_on' => date('Y-m-d H:i:s')
                );
                $contact_details = array(
                    'userid' => $_SESSION['login']['id'],
                    'diary_no' => $_SESSION['efiling_details']['diary_no'] . $_SESSION['efiling_details']['diary_year'],
                    'p_name' => !empty($contact_p_name) ? rtrim($contact_p_name, '$$') : '',
                    'p_email' => !empty($contact_p_email) ? rtrim($contact_p_email, '$$') : '',
                    'p_mobile' => !empty($contact_p_mobile) ? rtrim($contact_p_mobile, '$$') : '',
                    'partyid' => !empty($contact_partyid) ? rtrim($contact_partyid, '$$') : '',
                    'contact_type' => $party_type,
                    'created_by' => $_SESSION['login']['id'],
                    'created_on' => date('Y-m-d H:i:s'),
                    'create_ip' => $_SERVER['REMOTE_ADDR']
                );
                $details_saved_status = $this->AppearingForModel->add_appearing_for($appearing_party_detail, $contact_details, $registration_id);
                if ($details_saved_status) {
                    echo "1@@@Appearing for added successfully.";
                } else{
                    echo "2@@@Some Error ! Please try after some time.";
                }
            } elseif ((int) $appearing_for_tbl_id && (int) $contact_tbl_id) {
                // UPDATE APPEARING FOR AND CONTACT DETAILS OF PARTIES
                $update_appearing_party_detail = array(
                    'partytype' => $party_type,
                    'appearing_for' => !empty($appearing_for) ? rtrim($appearing_for, '$$') : '',
                    'updated_by' => $_SESSION['login']['id'],
                    'updated_by_ip' => $_SERVER['REMOTE_ADDR'],
                    'updated_on' => date('Y-m-d H:i:s')
                );
                $update_contact_details = array(
                    'p_name' => !empty($contact_p_name) ? rtrim($contact_p_name, '$$') : '',
                    'p_email' => !empty($contact_p_email) ? rtrim($contact_p_email, '$$') : '',
                    'p_mobile' => !empty($contact_p_mobile) ? rtrim($contact_p_mobile, '$$') : '',
                    'partyid' => !empty($contact_partyid) ? rtrim($contact_partyid, '$$') : '',
                    'contact_type' => $party_type,
                    'updated_by' => $_SESSION['login']['id'],
                    'update_ip' => $_SERVER['REMOTE_ADDR'],
                    'updated_on' => date('Y-m-d H:i:s')
                );
                $result = $this->AppearingForModel->update_appearing_for($update_appearing_party_detail, $appearing_for_tbl_id, $update_contact_details, $contact_tbl_id,$registration_id);
                if ($result) {
                    echo "1@@@Appearing for Updated Successfully.";
                } else{
                    echo "2@@@Some Error ! Please try after some time.";
                }
            }
        } else{
            return response()->redirect(base_url('dashboard_alt'));
        }
    }

}