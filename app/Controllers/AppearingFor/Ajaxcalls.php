<?php

namespace App\Controllers\AppearingFor;

use App\Controllers\BaseController;
use App\Models\AppearingFor\AppearingForModel;
class Ajaxcalls extends BaseController {
    protected $Appearing_for_model;
    public function __construct() {
        parent::__construct();
        $this->Appearing_for_model = new AppearingForModel();
    }

    function get_parties_list() {        
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK,AMICUS_CURIAE_USER);
        if ($_SESSION['login'] != '' && !in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            echo '2@@@' . htmlentities('Unauthorised Access!', ENT_QUOTES);
            exit(0);
        }
        $stages_array = array(Draft_Stage, Initial_Defected_Stage, I_B_Defected_Stage);
        if (isset($_SESSION['efiling_details']) && $_SESSION['efiling_details'] != '' && !in_array($_SESSION['efiling_details']['stage_id'], $stages_array)) {
            echo '2@@@' . htmlentities('Unauthorised Access!', ENT_QUOTES);
            exit(0);
        }
        $registration_id = isset($_SESSION['efiling_details']) ? $_SESSION['efiling_details']['registration_id'] : '';
        $partytype = escape_data($_POST['partytype']);
        if (!empty($registration_id)) {
            $parties_details = $this->Appearing_for_model->get_case_parties_list($registration_id);
            $appearing_for_details = $this->Appearing_for_model->get_appearing_for_details($registration_id, $_SESSION['login']['id']);
        } else{
            exit(0);
        }
        if ($partytype == 'P') {            
            $party_name_array = explode('##', $parties_details[0]['p_partyname']);
            $party_sr_no_array = explode('##', $parties_details[0]['p_sr_no']);            
        } elseif ($partytype == 'R') {            
            $party_name_array = explode('##', $parties_details[0]['r_partyname']);
            $party_sr_no_array = explode('##', $parties_details[0]['r_sr_no']);            
        }
        $keys = range(1, count($party_name_array));
        $parties_list = array_combine($keys, $party_name_array);
        if ((isset($appearing_for_details) && !empty($appearing_for_details)) && $appearing_for_details[0]['partytype'] == $partytype) {
            $saved_appearing_for = $appearing_for_details[0]['appearing_for'];
            $saved_appearing_for = explode('$$', $saved_appearing_for);
            $saved_appearing_for_email = $appearing_for_details[0]['p_email'];
            $saved_appearing_for_email = explode('$$', $saved_appearing_for_email ?? '');
            $saved_appearing_for_mobile = $appearing_for_details[0]['p_mobile'];
            $saved_appearing_for_mobile = explode('$$', $saved_appearing_for_mobile ?? '');
        } else {
            $saved_appearing_for = array();
            $saved_appearing_for_email = NULL;
            $saved_appearing_for_mobile = NULL;
        }
        if (!empty($parties_list)) {
            $i = 1; $parties_data = '';
            foreach ($parties_list as $key => $value) {
                $selected =!empty($saved_appearing_for) && (in_array($key, $saved_appearing_for)) ? 'checked' : NULL;
                $saved_sr_no =!empty($saved_appearing_for) ? array_search($key, $saved_appearing_for) : '';
                $email = ($selected && isset($saved_appearing_for_email[$saved_sr_no])) ? $saved_appearing_for_email[$saved_sr_no] : NULL;
                $mobile = ($selected && isset($saved_appearing_for_mobile[$saved_sr_no])) ? $saved_appearing_for_mobile[$saved_sr_no] : NULL;
                $appearing_id = isset($appearing_for_details[0]['id']) ? $appearing_for_details[0]['id'] : '';
                $appearing_contact_id = isset($appearing_for_details[0]['contact_tbl_id']) ? $appearing_for_details[0]['contact_tbl_id'] : '';
                $parties_data .='<tr>
                    <td><input class="form-control  cus-form-ctrl" name="party_name[]" type="text"  value="'.escape_data($value).'" readonly=""></td>
                    <td><input class="form-control  cus-form-ctrl" name="party_email[]" type="email" placeholder="Email"  value="'.escape_data($email ?? '').'" ></td>
                    <td><input class="form-control  cus-form-ctrl mbl-ctrl" name="party_mob[]" placeholder="Mobile"  value="'. escape_data($mobile ?? '').'" type="text" maxlength="10" minlength="10" id="mobiles_id_'.$key.'" onkeyup="check_mobile_numb(this.value,'."'mobiles_id_".$key."'".');"></td>
                    <td class="text-center"><input class="checkBoxClass" type="checkbox" name="selected_party[]" value="'. url_encryption($i . '$$$' . $key . '$$$' . $appearing_id . '$$$' . $appearing_contact_id).'"'.$selected.'></td>
                </tr>';
                $i++;
            }
            echo '2@@@'.$parties_data;
        } else{
            echo '1@@@ No record found !';
        }        
    }

}