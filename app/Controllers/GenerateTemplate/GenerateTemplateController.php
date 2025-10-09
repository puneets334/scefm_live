<?php

namespace App\Controllers\GenerateTemplate;

use App\Controllers\BaseController;
use App\Models\GenerateTemplate\GenerateTemplateModel;
use App\Libraries\webservices\Efiling_webservices;

class GenerateTemplateController extends BaseController {

    protected $efiling_webservices;
    protected $GenerateTemplate_Model;
    
    public function __construct() {
        parent:: __construct();
        $this->GenerateTemplate_Model = new GenerateTemplateModel();
        $this->efiling_webservices = new Efiling_webservices();
        if(empty(getSessionData('login'))){
            return response()->redirect(base_url('/')); 
        } else {
            is_user_status();
        }    
        if(empty(getSessionData('login')['ref_m_usertype_id'])){
            return redirect()->to(base_url('/'));
            exit(0);
        }
    }

    public function index() {
        $data = [];
        $data['sc_case_types'] = $this->GenerateTemplate_Model->get_templates();
        $data['case']          = $_GET['case'];        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $case_name        = $_POST['case_name'];
            $efileno          = isset($_POST['efileno']) ? $_POST['efileno'] : '';
            $diary_no         = $_POST['diary_no'];
            $diary_year       = $_POST['diary_year'];
            $sc_template_id   = $_POST['sc_template_id'];
            $error            = false;
            $this->session->setFlashdata('case_name', $case_name);
            $this->session->setFlashdata('efileno', $efileno);
            $this->session->setFlashdata('diary_no', $diary_no);
            $this->session->setFlashdata('diary_year', $diary_year);
            $this->session->setFlashdata('sc_template_id', $sc_template_id);
            $data['diary_no']         = $diary_no;
            $data['efileno']          = $efileno;
            $data['sc_template_id']   = $sc_template_id;
            $data['diary_year']       = $diary_year;
            $error_messages = [];
            if(!isset($case_name) || empty($case_name)) {
                $error = true;
                $error_messages[] = 'Case';
            }
            if(!isset($sc_template_id) || empty($sc_template_id)) {
                $error = true;
                $error_messages[] = 'Template Type';
            }
            if(empty($efileno) && empty($diary_no) && empty($diary_year) && $case_name=='P') {
                $error = true;
                $error_messages[] = ' E-file No or Diary No / Diary Year';
            }
            if(!empty($case_name) && $case_name=='IA' && empty($diary_no) && empty($diary_year)) {
                $error = true;
                $error_messages[] = ' Diary No and Diary Year';
            }
            if(!empty($case_name) && $case_name=='IA' && !empty($diary_no) && empty($diary_year)) {
                $error = true;
                $error_messages[] = ' Diary Year';
            }
            if(empty($diary_no) && !empty($diary_year)) {
                $error = true;
                $error_messages[] = ' Diary No';
            }
            if($error) {
                $err_message = 'Please Select '.implode(', ' ,$error_messages).'.';
                $this->session->setFlashdata('message', "<div class='alert alert-danger'>$err_message</div>");
                return redirect()->to(base_url('generate_template/GenerateTemplate_Controller/index?case='.$data['case']));
            }            
            if($case_name=='P') {
                if(isset($efileno) && !empty($efileno)) {
                    $results = $this->GenerateTemplate_Model->generateTemplateRecords($efileno);
                    if(isset($results) && !empty($results)){
                        $this->create_template($results,$sc_template_id,'efile');
                    } else{
                        $this->session->setFlashdata('message', "<div class='alert alert-danger'>No records found.</div>");
                        return redirect()->to(base_url('generate_template/GenerateTemplate_Controller/index?case='.$data['case']));
                    }
                } else{
                    $results = $this->efiling_webservices->getDiaryDetailForTemplate($diary_no.$diary_year);
                    if(isset($results) && !empty($results) && count(json_decode($results))>0) {
                        $this->create_template($results,$sc_template_id,'icmis');
                    } else{
                        $this->session->setFlashdata('message', "<div class='alert alert-danger'>No records found.</div>");
                        return redirect()->to(base_url('generate_template/GenerateTemplate_Controller/index?case='.$data['case']));
                    }
                }
            } else{
                $results = $this->efiling_webservices->getDiaryDetailForTemplate($diary_no.$diary_year);
                if(isset($results) && !empty($results) && count(json_decode($results))>0) {
                    $this->create_template($results,$sc_template_id,'icmis');
                } else{
                    $this->session->setFlashdata('message', "<div class='alert alert-danger'>No records found.</div>");
                    return redirect()->to(base_url('generate_template/GenerateTemplate_Controller/index?case='.$data['case']));
                }
            }
        }      
        return $this->render('generate_template.index', $data);
    }

    private function create_template($results,$template_id,$data_from) {
        if($data_from=='icmis') {
            foreach(json_decode($results) as $result) {
                if($result->p_r_type=='P') {
                    $petitioner_name_arr[] = $result->party_name;
                } elseif($result->p_r_type=='R') {
                    $responded_name_arr[] = $result->party_name;
                } 
            }
        } else{
            foreach($results as $result) {
                if(isset($result->party_name) && !empty($result->party_name)) {
                    $party_name = $result->party_name;
                    if($result->p_r_type=='P') {
                        $petitioner_name_arr[] = $party_name;
                    } elseif($result->p_r_type=='R') {
                        $responded_name_arr[] = $party_name;
                    }
                } else{
                    $party_name_arr = explode('Vs.', $result->cause_title);
                    if($result->p_r_type=='P') {
                        $petitioner_name_arr[] = $party_name_arr[0];
                    } elseif($result->p_r_type=='R') {
                        $responded_name_arr[] = $party_name_arr[1];
                    }
                }
            }
        }        
        $petitioner_name = implode(',',$petitioner_name_arr);
        $responded_name  = implode(',',$responded_name_arr);
        $template = $this->GenerateTemplate_Model->get_template($template_id);
        $template_name = $template->name.'.docx';
        $template_text = $template->template_text;
        $template_text = str_replace('PETITIONER_NAME','<span style="background-color:yellow;">'.$petitioner_name.'</span>',$template_text);
        $template_text = str_replace('RESPONDENT_NAME','<span style="background-color:yellow;">'.$responded_name.'</span>',$template_text); 
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header("Content-Disposition: attachment;Filename=".$template_name);
        echo $template_text;
        die;
    }

}