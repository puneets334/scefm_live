<?php

namespace App\Controllers\jailPetition;

use App\Controllers\BaseController;
use App\Models\NewCase\GetDetailsModel;
use App\Models\NewCase\DropdownListModel;
use App\Models\NewCase\ViewModel;
use App\Models\Affirmation\AffirmationModel;

class View extends BaseController
{
    protected $Get_details_model;
    protected $Dropdown_list_model;
    protected $View_model;
    protected $Affirmation_model;

    public function __construct()
    {
        parent::__construct();
        $this->Get_details_model = new GetDetailsModel();
        $this->Dropdown_list_model = new DropdownListModel();
        $this->View_model = new ViewModel();
        $this->Affirmation_model = new AffirmationModel;
    }

    function index()
    {
        $allowed_users_array = array(JAIL_SUPERINTENDENT, USER_ADMIN);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
        $registration_id = $_SESSION['efiling_details']['registration_id'];
        $data['new_case_details'] = $this->Get_details_model->get_new_case_details($registration_id);
        $data['sc_case_type'] = $this->Dropdown_list_model->get_sci_case_type_name($data['new_case_details'][0]['sc_case_type_id']);
        // $data['main_subject_cat'] = $this->Dropdown_list_model->get_main_subject_category($data['new_case_details'][0]['subject_cat']);
        $data['petitioner_details'] = $this->Get_details_model->get_case_parties_details($registration_id, array('p_r_type' => 'P', 'm_a_type' => 'M', 'party_id' => NULL, 'view_lr_list' => FALSE));
        $data['respondent_details'] = $this->Get_details_model->get_case_parties_details($registration_id, array('p_r_type' => 'R', 'm_a_type' => 'M', 'party_id' => NULL, 'view_lr_list' => FALSE));
        $data['extra_parties_list'] = $this->Get_details_model->get_case_parties_details($registration_id, array('p_r_type' => NULL, 'm_a_type' => 'A', 'party_id' => NULL, 'view_lr_list' => FALSE));
        $data['party_details'] = $this->Get_details_model->get_case_parties_details($registration_id, array('p_r_type' => 'P', 'm_a_type' => 'M', 'party_id' => NULL, 'view_lr_list' => FALSE));
        $data['subordinate_court_details'] = $this->Get_details_model->get_subordinate_court_details($registration_id);
        $data['efiled_docs_list'] = $this->View_model->get_index_items_list($registration_id);
        $data['esigned_docs_details'] = $this->Affirmation_model->get_esign_doc_details($registration_id);
        return $this->render('jailPetition.efile_details_view', $data);
    }
}
