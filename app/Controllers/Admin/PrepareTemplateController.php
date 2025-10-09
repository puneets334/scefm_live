<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\webservices\Efiling_webservices;
use App\Models\PrepareTemplate\PrepareTemplateModel;

class PrepareTemplateController extends BaseController {
    
    public $header;
    protected $PrepareTemplate_Model;
    protected $efiling_webservices;
    protected $request;
    protected $validation;

    public function __construct() {
        parent:: __construct();
        $this->request = \Config\Services::request();
        $this->validation = \Config\Services::validation();
        $this->PrepareTemplate_Model = new PrepareTemplateModel();
        $this->efiling_webservices = new Efiling_webservices();
        if(empty(getSessionData('login'))){
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }    
        if(empty(getSessionData('login')['ref_m_usertype_id'])) {
            return redirect()->to(base_url('/'));
            exit(0);
        }
    }

    public function index() {
        $data = [];
        $validations = [];
        $data['templates'] = $this->PrepareTemplate_Model->get_templates();
        $template_type     = $_GET['template_type'];
        $quey_string       = '';        
        if(isset($template_type ) && !empty($template_type)) {
            $created_template = $this->PrepareTemplate_Model->get_template($template_type);
            $data['created_template'] = $created_template;
            $data['template_type']    =  $template_type;
            $quey_string              = '?template_type='.$template_type;
        }        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $template_text    = str_replace('&nbsp;',' ',$_POST['template_text']);
            $template_id      = $_POST['template_id'];
            $validations = [
                "template_id" => [
                    "label" => "Template Type",
                    "rules" => "required"
                ],
                "template_text" => [
                    "label" => "Template Text",
                    "rules" => "required"
                ],
            ];
            $this->validation->setRules($validations);
            if ($this->validation->withRequest($this->request)->run() === FALSE) {
                return $this->render('prepare_template.index',$data);
            } else{
                $quey_string = '?template_type='.$template_id;
                $template_data = [
                    'template_id'    => $template_id,
                    'template_text'  => $template_text,
                    'created_by'     => $_SESSION['login']['id'],
                    'created_by_ip'  => getClientIP(),
                ];
                $response = $this->PrepareTemplate_Model->save_template($template_data);
                if($response) {
                    $this->session->setFlashdata('message', "<div class='alert alert-success'>Template prepared successfully.</div>");
                } else{
                    $this->session->setFlashdata('message', "<div class='alert alert-danger'>Something went wrong.</div>");
                }
                return redirect()->to(base_url('admin/PrepareTemplate_Controller'.$quey_string));
            }
        } else{
            return $this->render('prepare_template.index',$data);
        }
    }

    public function prepared_templates_download() {
        $data = [];
        $data['sc_case_types'] = $this->PrepareTemplate_Model->get_templates();
        $data['case']          = $_GET['case'];        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data['case_name']        = $_POST['case_name'];
            $data['efileno']          = isset($_POST['efileno']) ? $_POST['efileno'] : '';
            $data['diary_no']         = $_POST['diary_no'];
            $data['diary_year']       = $_POST['diary_year'];
            $data['template_id']      = $_POST['sc_template_type'];
            $data['download']         = $_POST['download'];
            $template_text            = isset($_POST['template_text']) ? str_replace('&nbsp;',' ',$_POST['template_text']) : '';
            $error                    = false;            
            $this->session->setFlashdata('case_name', $data['case_name']);
            $this->session->setFlashdata('efileno', $data['efileno']);
            $this->session->setFlashdata('diary_no', $data['diary_no']);
            $this->session->setFlashdata('diary_year', $data['diary_year']);
            $this->session->setFlashdata('template_id', $data['template_id']);
            $error_messages = [];            
            if(!isset($data['case_name']) || empty($data['case_name'])) {
                $error = true;
                $error_messages[] = 'Case';
            }
            if(!isset($data['template_id']) || empty($data['template_id'])) {
                $error = true;
                $error_messages[] = 'Template Type';
            }
            if(empty($data['efileno']) && empty($data['diary_no']) && empty($data['diary_year']) && !empty($data['template_id'])) {
                if($data['download']) {
                    if($data['download']) {
                        $this->download_template($template_text,$data['template_id']);
                    }
                } else{
                    $prepared_template_text = $this->PrepareTemplate_Model->get_template($data['template_id']);
                    $data['created_template'] = $prepared_template_text->template_text;
                    return $this->render('prepare_template.index_user',$data);
                }
            } else{
                if($data['download']) {
                    $this->download_template($template_text,$data['template_id']);
                }
                if(!empty($data['case_name']) && $data['case_name']=='IA' && !empty($data['diary_no']) && empty($data['diary_year'])) {
                    $error = true;
                    $error_messages[] = ' Diary Year';
                }
                if(empty($data['diary_no']) && !empty($data['diary_year'])) {
                    $error = true;
                    $error_messages[] = ' Diary No';
                }
                if($error) {
                    $err_message = 'Please Select '.implode(', ' ,$error_messages).'.';
                    $this->session->setFlashdata('message', "<div class='alert alert-danger'>$err_message</div>");
                    return redirect()->to(base_url('admin/PrepareTemplate_Controller/prepared_templates_download?case='.$data['case']));
                    exit(0);
                }
                if($data['case_name']=='P') {
                    if(isset($data['efileno']) && !empty($data['efileno'])) {
                        $results = $this->PrepareTemplate_Model->preparedTemplateRecords($data['efileno']);
                        if(isset($results) && !empty($results)) {
                            $data['created_template'] = $this->prepare_template($results,$data['template_id'],'efile');
                        } else{
                            $this->session->setFlashdata('message', "<div class='alert alert-danger'>No records found.</div>");
                            return redirect()->to(base_url('admin/PrepareTemplate_Controller/prepared_templates_download?case='.$data['case']));
                        }
                    } else{
                        $results = $this->efiling_webservices->getDiaryDetailForTemplate($data['diary_no'].$data['diary_year']);
                        if(isset($results) && !empty($results) && count(json_decode($results))>0) {
                            $data['created_template'] = $this->prepare_template($results,$data['template_id'],'icmis');
                        } else{
                            $this->session->setFlashdata('message', "<div class='alert alert-danger'>No records found.</div>");
                            return redirect()->to(base_url('admin/PrepareTemplate_Controller/prepared_templates_download?case='.$data['case']));
                        }
                    }
                } else{
                    $results = $this->efiling_webservices->getDiaryDetailForTemplate($data['diary_no'].$data['diary_year']);
                    if(isset($results) && !empty($results) && count(json_decode($results))>0) {
                        $data['created_template'] = $this->prepare_template($results,$data['template_id'],'icmis');
                    } else{
                        $this->session->setFlashdata('message', "<div class='alert alert-danger'>No records found.</div>");
                        return redirect()->to(base_url('admin/PrepareTemplate_Controller/prepared_templates_download?case='.$data['case']));
                    }
                }               
                return $this->render('prepare_template.index_user',$data);
            }
        } else{
            return $this->render('prepare_template.index_user',$data);
        }
    }

    private function prepare_template($results,$template_id,$data_from) {
        if($data_from=='icmis') {
            foreach(json_decode($results) as $result) {
                if($result->p_r_type=='P') {
                    $petitioner_name = $result->party_name;
                } elseif($result->p_r_type=='R') {
                    $responded_name = $result->party_name;
                } 
            }
        } else{
            foreach($results as $result) {
                if(isset($result->party_name) && !empty($result->party_name)) {
                    $party_name = $result->party_name;
                    if($result->p_r_type=='P') {
                        $petitioner_name = $party_name;
                    } elseif($result->p_r_type=='R') {
                        $responded_name = $party_name;
                    }
                } else{
                    $party_name_arr = explode('Vs.', $result->cause_title);
                    if($result->p_r_type=='P') {
                        $petitioner_name = $party_name_arr[0];
                    } elseif($result->p_r_type=='R') {
                        $responded_name = $party_name_arr[1];
                    }
                }
            }
        }
        $prepared_template_text = $this->PrepareTemplate_Model->get_template($template_id);
        $str = str_replace('PETITIONER_NAME','<span style="background-color:yellow;">'.$petitioner_name.'</span>',$prepared_template_text->template_text);
        $str = str_replace('RESPONDENT_NAME','<span style="background-color:yellow;">'.$responded_name.'</span>',$str);
        return $str;
    }

    private function get_filename_by_template_id($template_id) {
        $template      = $this->PrepareTemplate_Model->get_template_by_id($template_id);
        $template_name = $template->name.'.docx';
        return $template_name;
    }

    private function download_template($template_text,$template_id) {
        $template_name = $this->get_filename_by_template_id($template_id);
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header("Content-Disposition: attachment;Filename=".$template_name);
        echo $template_text;
        die;
    }

}