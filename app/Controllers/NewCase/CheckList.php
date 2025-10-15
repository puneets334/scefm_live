<?php
namespace App\Controllers\Newcase;

use App\Controllers\BaseController;
use App\Models\NewCase\ChecklistModel;
use App\Models\Common\CommonModel;

class CheckList extends BaseController {

    protected $ChecklistModel;
    protected $Common_model;

    public function __construct() {
        parent::__construct();
        $this->session = \Config\Services::session();
        if (empty($this->session->get('login'))) {
            return response()->redirect(base_url('/'));
        } else {
            is_user_status();
        }
        $this->ChecklistModel = new ChecklistModel();
        $this->Common_model = new CommonModel();
    }

    public function index() {
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_DEPARTMENT);
        if (getSessionData('login') != '' && !in_array($this->session->get('login')['ref_m_usertype_id'], $allowed_users_array)) {
            return redirect()->to(base_url('admindashboard'));
            exit(0);
        }
        $stages_array = array('', Draft_Stage, Initial_Defected_Stage,  E_REJECTED_STAGE);
        if (!empty(getSessionData('efiling_details')['stage_id']) && !in_array(getSessionData('efiling_details')['stage_id'], $stages_array)) {
            return response()->redirect(base_url('newcase/view'));
            exit(0);
        }
        if (is_null($this->ChecklistModel)) {
            $this->ChecklistModel = new ChecklistModel();
        }
        $case_details = $this->Common_model->get_subject_category_casetype_court_fee($_SESSION['efiling_details']['registration_id']);
        $checked_response = $this->ChecklistModel->get_checklist_data_by_registration_id(getSessionData('efiling_details')['registration_id']);
        $checked_response_pil = $this->ChecklistModel->get_checklist_data_by_registration_id_pil(getSessionData('efiling_details')['registration_id']);
        $checked_response_annexure = $this->ChecklistModel->get_checklist_data_by_registration_id_annexure(getSessionData('efiling_details')['registration_id']);
        // if (empty($checked_response)) {
        //     $this->session->setFlashdata('msg', 'No checklist found for this case type, please contact admin.');
        //     return redirect()->to(base_url('newcase/checklist'));
        //     exit(0);
        // }
        $caseName = $this->ChecklistModel->get_sci_case_type_name_by_id($case_details[0]['sc_case_type_id']);
        $data['crnt_dt'] = date('d-m-Y');
        $data['checklist_response'] = ((is_array($checked_response) && !empty($checked_response) && count($checked_response) > 1) ? $checked_response : array());
        $data['checked_response_pil'] = ((is_array($checked_response_pil) && !empty($checked_response_pil) && count($checked_response_pil) > 1) ? $checked_response_pil : array());
        $data['checked_response_annexure'] = ((is_array($checked_response_annexure) && !empty($checked_response_annexure) && count($checked_response_annexure) > 1) ? $checked_response_annexure : array());
        //pr($data['checked_response_annexure']);
        $data['case_details'] = $case_details;
        $data['caseName'] = $caseName;
        return $this->render('newcase.checklist', $data);
    }


    public function add_checklist() {
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_DEPARTMENT);
        if (getSessionData('login') != '' && !in_array($this->session->get('login')['ref_m_usertype_id'], $allowed_users_array)) {
            return redirect()->to(base_url('admindashboard'));
            exit(0);
        }
        $stages_array = array('', Draft_Stage, Initial_Defected_Stage,  E_REJECTED_STAGE);
        if (!empty(getSessionData('efiling_details')['stage_id']) && !in_array(getSessionData('efiling_details')['stage_id'], $stages_array)) {
            return response()->redirect(base_url('newcase/view'));
            exit(0);
        }
        // pr($_POST);
        $checklist_id = $this->request->getPost('checklist_id');
        $question_no = $this->request->getPost('question_no');
        $sub_question_no = $this->request->getPost('sub_question_no');
        $answer = $this->request->getPost('answer');
        $checklist_id_pil = $this->request->getPost('checklist_id_pil');
        $question_no_pil = $this->request->getPost('question_no_pil');
        $sub_question_no_pil = $this->request->getPost('sub_question_no_pil');
        $answer_pil = $this->request->getPost('answer_pil');
        $checklist_id_annexure = $this->request->getPost('checklist_id_annexure');
        $question_no_annexure = $this->request->getPost('question_no_annexure');
        $sub_question_no_annexure = $this->request->getPost('sub_question_no_annexure');
        $answer_annexure = $this->request->getPost('answer_annexure');
        if($this->request->getPost('check_save')) {
            if (!empty($checklist_id) && is_array($checklist_id)) {
                $insert_data = array();
                foreach ($checklist_id as $key => $value) {
                    if (in_array($value, $answer)) {
                        $aws = 1;
                    } else {
                        $aws = 0;
                    }
                    // if(key_exists($key, $answer) ) {
                    //     if($answer[$key] == 'on') {
                            $insert_data[] = array(
                                'registration_id' => getSessionData('efiling_details')['registration_id'],
                                'question_no' => !empty($question_no[$key]) ? $question_no[$key] : '',
                                'sub_question_no' => !empty($sub_question_no[$key]) ? $sub_question_no[$key] : '',
                                'answer' => $aws,
                                'created_by' => getSessionData('login')['userid'],
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_by' => NULL,
                                'updated_at' => NULL,
                                'ref_m_check_list_new_id' => $value,
                                'cat_type' => 'CA'
                            );
                    //     }
                    // }
                }
                if (!empty($insert_data)) {
                    $insert_checklist = $this->ChecklistModel->insert_checks($insert_data);
                } else {
                    $this->session->setFlashdata('error', 'Something went wrong, please try again.');
                }
            }

            if (!empty($checklist_id_pil) && is_array($checklist_id_pil)) {
                $insert_data = array();
                foreach ($checklist_id_pil as $key => $value) {
                    if (in_array($value, $answer_pil)) {
                        $aws = 1;
                    } else {
                        $aws = 0;
                    }
                    // if(key_exists($key, $answer_pil) ) {
                    //     if($answer_pil[$key] == 'on') {
                            $insert_data[] = array(
                                'registration_id' => getSessionData('efiling_details')['registration_id'],
                                'question_no' => !empty($question_no_pil[$key]) ? $question_no_pil[$key] : '',
                                'sub_question_no' => !empty($sub_question_no_pil[$key]) ? $sub_question_no_pil[$key] : '',
                                'answer' => $aws,
                                'created_by' => getSessionData('login')['userid'],
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_by' => NULL,
                                'updated_at' => NULL,
                                'sub_cat_id' => 8, // PIL
                                'ref_m_check_list_new_id' => $value,
                                'cat_type' => 'IL'
                            );
                    //     }
                    // }
                }
                if (!empty($insert_data)) {
                    $insert_checklist = $this->ChecklistModel->insert_checks($insert_data);
                } else {
                    $this->session->setFlashdata('error', 'Something went wrong, please try again.');
                }
            }

            if (!empty($checklist_id_annexure) && is_array($checklist_id_annexure)) {
                $insert_data = array();
               foreach ($checklist_id_annexure as $key => $value) {
                    if (in_array($value, $answer_annexure)) {
                        $aws = 1;
                    } else {
                        $aws = 0;
                    }
                    // if(key_exists($key, $answer_annexure) ) {
                    //     if($answer_annexure[$key] == 'on') {
                            $insert_data[] = array(
                                'registration_id' => getSessionData('efiling_details')['registration_id'],
                                'question_no' => !empty($question_no_annexure[$key]) ? $question_no_annexure[$key] : '',
                                'sub_question_no' => !empty($sub_question_no_annexure[$key]) ? $sub_question_no_annexure[$key] : '',
                                'answer' => $aws,
                                'created_by' => getSessionData('login')['userid'],
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_by' => NULL,
                                'updated_at' => NULL,
                                'ref_m_check_list_new_id' => $value,
                                'cat_type' => 'D'
                            );
                    //     }
                    // }
                }
                if (!empty($insert_data)) {
                    $insert_checklist = $this->ChecklistModel->insert_checks($insert_data);
                    $this->session->setFlashdata('msg', 'Checklist saved successfully.');
                    return redirect()->to(base_url('newcase/checklist'));
                    exit(0);
                } else {
                    $this->session->setFlashdata('error', 'Something went wrong, please try again.');
                    return redirect()->to(base_url('newcase/checklist'));
                    exit(0);
                }
            }
        }
        if ($this->request->getPost('check_update')) {
            if (!empty($checklist_id) && is_array($checklist_id)) {
                $update_data = array();
                foreach ($checklist_id as $key => $value) {
                    if (in_array($value, $answer)) {
                        $aws = 1;
                    } else {
                        $aws = 0;
                    }
                    $update_data[] = array(
                        'registration_id' => getSessionData('efiling_details')['registration_id'],
                        'question_no' => !empty($question_no[$key]) ? $question_no[$key] : '',
                        'sub_question_no' => !empty($sub_question_no[$key]) ? $sub_question_no[$key] : '',
                        'answer' =>  $aws,
                        'updated_by' => getSessionData('login')['userid'],
                        'updated_at' => date('Y-m-d H:i:s'),
                        'ref_m_check_list_new_id' => $value
                    );
                }
                if (!empty($update_data)) {
                    $insert_checklist = $this->ChecklistModel->update_checks($update_data, getSessionData('efiling_details')['registration_id']);
                } else {
                    $this->session->setFlashdata('error', 'Something went wrong, please try again.');
                }
            }

            if (!empty($checklist_id_pil) && is_array($checklist_id_pil)) {
                $update_data = array();
                foreach ($checklist_id_pil as $key => $value) {
                    if (in_array($value, $answer_pil)) {
                        $aws = 1;
                    } else {
                        $aws = 0;
                    }
                    $update_data[] = array(
                        'registration_id' => getSessionData('efiling_details')['registration_id'],
                        'question_no' => !empty($question_no_pil[$key]) ? $question_no_pil[$key] : '',
                        'sub_question_no' => !empty($sub_question_no_pil[$key]) ? $sub_question_no_pil[$key] : '',
                        'answer' =>  $aws,
                        'updated_by' => getSessionData('login')['userid'],
                        'updated_at' => date('Y-m-d H:i:s'),
                        'sub_cat_id' => 8, // PIL
                        'ref_m_check_list_new_id' => $value
                    );
                }
                if (!empty($update_data)) {
                    $insert_checklist = $this->ChecklistModel->update_checks($update_data, getSessionData('efiling_details')['registration_id']);
                } else {
                    $this->session->setFlashdata('error', 'Something went wrong, please try again.');
                }
            }

            if (!empty($checklist_id_annexure) && is_array($checklist_id_annexure)) {
                $update_data = array();
                foreach ($checklist_id_annexure as $key => $value) {
                    if (in_array($value, $answer_annexure)) {
                        $aws = 1;
                    } else {
                        $aws = 0;
                    }
                    $update_data[] = array(
                        'registration_id' => getSessionData('efiling_details')['registration_id'],
                        'question_no' => !empty($question_no_annexure[$key]) ? $question_no_annexure[$key] : '',
                        'sub_question_no' => !empty($sub_question_no_annexure[$key]) ? $sub_question_no_annexure[$key] : '',
                        'answer' =>  $aws,
                        'updated_by' => getSessionData('login')['userid'],
                        'updated_at' => date('Y-m-d H:i:s'),
                        'ref_m_check_list_new_id' => $value
                    );
                }
                
                if (!empty($update_data)) {
                    $insert_checklist = $this->ChecklistModel->update_checks($update_data, getSessionData('efiling_details')['registration_id']);
                    $this->session->setFlashdata('msg', 'Checklist updated successfully.');
                    return redirect()->to(base_url('newcase/checklist'));
                    exit(0);
                } else {
                    $this->session->setFlashdata('error', 'Something went wrong, please try again.');
                    return redirect()->to(base_url('newcase/checklist'));
                    exit(0);
                }
            }
        }
    }

}
