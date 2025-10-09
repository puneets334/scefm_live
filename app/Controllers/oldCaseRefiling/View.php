<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class View extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('miscellaneous_docs/Get_details_model');
        $this->load->model('oldCaseRefiling/View_model');
        $this->load->model('affirmation/Affirmation_model');
        $this->load->model('uploadDocuments/UploadDocs_model');
        $this->load->library('webservices/efiling_webservices');
    }

    function index() {

        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_ADMIN,USER_ADMIN_READ_ONLY,USER_EFILING_ADMIN, USER_CLERK, USER_DEPARTMENT);

        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            redirect('login');
            exit(0);
        }

        if (isset($_SESSION['efiling_details']['registration_id']) && !empty($_SESSION['efiling_details']['registration_id'])) {

            $registration_id = $_SESSION['efiling_details']['registration_id'];

            $data['case_details'] = $this->Get_details_model->get_case_details($registration_id);

            $diary_number='';$diary_year='';
            $data['case_listing_details']='';
            if(!empty($data['case_details']))
            {
                $diary_number=$data['case_details'][0]['diary_no'];
                $diary_year=$data['case_details'][0]['diary_year'];
                if(!empty($diary_number))
                {
                    $data['case_listing_details']=$this->efiling_webservices->getCaseListedDetail($diary_number,$diary_year);
                    $data['details']=$this->efiling_webservices->getCISData($diary_number.$diary_year);
                }
            }

            $data['filing_for_details'] = $this->View_model->get_filing_for_parties($registration_id);

            $data['efiled_docs_list'] = $this->View_model->get_index_items_list($registration_id);

            $data['payment_details'] = $this->View_model->get_payment_details($registration_id);

            $data['shareEmail_details'] = $this->View_model->get_share_email_details_user($registration_id);

            $data['esigned_docs_details'] = $this->Affirmation_model->get_esign_doc_details($registration_id);

            $data['uploaded_docs'] = $this->UploadDocs_model->get_uploaded_pdfs($registration_id);

            $creaedBy = !empty($data['case_details'][0]['created_by']) ? $data['case_details'][0]['created_by'] : NULL;
            if(isset($creaedBy) && !empty($creaedBy)){
                $this->load->model('common/Common_model');
                $params = array();
                $params['table_name'] ='efil.tbl_users';
                $params['whereFieldName'] ='id';
                $params['whereFieldValue'] = (int)$creaedBy;
                $params['is_active'] ='1';
                $userData = $this->Common_model->getData($params);
                if(isset($userData) && !empty($userData)){
                    $adv_sci_bar_id = !empty($userData[0]->adv_sci_bar_id) ? (int)$userData[0]->adv_sci_bar_id : NULL;
                    if(isset($adv_sci_bar_id) && !empty($adv_sci_bar_id)){
                        $getBarData = $this->Common_model->getBarDetailsById($adv_sci_bar_id);
                        $data['filedByData'] = !empty($getBarData) ?  $getBarData[0] : NULL;
                    }
                }
            }

            $this->load->view('templates/header');
            $this->load->view('oldCaseRefiling/old_efiling_preview', $data);
            $this->load->view('templates/footer');
        } else {
            redirect('login');
            exit(0);
        }




        /* $data['other_data_applying'] = $this->Get_details_model->ia_other_data($registration_id, 1, E_FILING_TYPE_MISC_DOCS);
          $data['uploaded_docs'] = $this->Get_details_model->get_uploaded_documents($registration_id);
          $data['payment_details'] = $this->Get_details_model->get_total_payment_detail($registration_id);
          $data['certificate_adv'] = $this->Get_details_model->get_esign_doc_adv($registration_id);
          $data['certificate_pet'] = $this->Get_details_model->get_esign_doc_pet($registration_id);
          $data['verified_docs'] = $this->Get_details_model->get_everified_doc($registration_id);
          $data['efiled_by_user'] = $this->Get_details_model->get_efiled_by_user($data['result'][0]['created_by']);
          $efiling_submitted_on = $this->Get_details_model->get_submitted_on($registration_id);
          $data['submitted_on'] = $efiling_submitted_on[0]['activated_on'];

          if (!empty($data['certificate_adv'])) {
          $data['certificate'] = $data['certificate_adv'];
          } else {
          $data['certificate'] = $data['certificate_pet'];
          } */
    }

}
