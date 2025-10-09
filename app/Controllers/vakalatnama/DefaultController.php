<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class DefaultController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('functions');
        $this->load->helper('file');
        $this->load->library('slice');
        $this->load->library('TCPDF');
        $this->load->library('webservices/efiling_webservices');
        $this->load->model('vakalatnama/vakalatnama_model');
        $this->load->model('newcase/Dropdown_list_model');

        if (!isset($_SESSION['login']) && empty($_SESSION['login'])) {
            redirect('login');
        }
    }
    public function index() {
        $this->slice->view('vakalatnama.search');
    }

    /********************** vakalatnama **********************/
    public function search() {
        $data['sc_case_type'] = $this->Dropdown_list_model->get_sci_case_type();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $search_type=trim($_POST['search_filing_type']);
            $diary_no=trim($_POST['diary_no']);
            $diary_year=trim($_POST['diary_year']);
            $efiling_no=trim($_POST['efiling_no']);

            $case_number=trim($_POST['case_no']);
            $case_year=trim($_POST['case_year']);
            $case_type_id=url_decryption(trim($_POST['sc_case_type']));

            if(!empty($case_number) && !empty($case_year) && !empty($case_type_id) && $search_type=='register') {
                $response_type='Case Number/Year :'.$case_number . '/'.$case_year;
                //CHECK EXISTING DATABASE
                $data['vakalatnama'] = $this->vakalatnama_model->get_vakalatnama_search($search_type,$efiling_no,$diary_no,$diary_year,$case_number,$case_year,$case_type_id);
                if (empty($data['vakalatnama']) && $data['vakalatnama']==false) {
                    $data['vakalatnama']=[];
                    //CHECK EXISTING API ICMIS BY GET diary_no AND diary_year
                    $web_service_result = $this->efiling_webservices->get_case_details_from_SCIS(escape_data($case_type_id), escape_data($case_number), escape_data($case_year));
                    if (!empty($web_service_result->case_details[0])) {
                        $case_data = $web_service_result->case_details[0];
                        $diary_no = $case_data->diary_no;
                        $diary_year = $case_data->diary_year;
                        //CHECK EXISTING API ICMIS
                        $data['vakalatnama'] = $this->vakalatnama_model->search_vakalatnama_or_parties_by_diaryNo($diary_no, $diary_year);
                    }
                }
            }elseif(!empty($search_type) && $search_type!=null && $search_type=='diary' && $search_type!='efilingNO') {
                $response_type='Diary Number :'.$diary_no . '/'.$diary_year;
                //CHECK EXISTING DATABASE
                $data['vakalatnama'] = $this->vakalatnama_model->get_vakalatnama_search($search_type,$efiling_no,$diary_no,$diary_year,$case_number,$case_year,$case_type_id);
                if (empty($data['vakalatnama']) && $data['vakalatnama']==false) {
                    $data['vakalatnama']=[];
                    //CHECK EXISTING API ICMIS
                    $data['vakalatnama'] = $this->vakalatnama_model->search_vakalatnama_or_parties_by_diaryNo($diary_no, $diary_year);
                }

            }elseif(!empty($search_type) && $search_type!=null && $search_type=='efilingNO' && $search_type!='diary') {
                $response_type='E-Filing Number :'.$efiling_no;
                //CHECK EXISTING DATABASE
                $data['vakalatnama'] = $this->vakalatnama_model->get_vakalatnama_search($search_type,$efiling_no,$diary_no,$diary_year,$case_number,$case_year,$case_type_id);
            }else{
                $response_type='Data not fount.';
            }
            $data['response_type']=$response_type;


            if (!empty($data['vakalatnama']) && $data['vakalatnama']!=null && !empty($_SESSION['efiling_details']['registration_id']) && $data['vakalatnama']!=false) {
                $this->session->set_userdata(array('response_type' => $response_type));

                 echo "<script>window.top.location.href='" . base_url() . "vakalatnama/dashboard';</script>";


            }else{
                $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Vakalatnama case is not available by '.$response_type.'</div>');
                //echo '<center class="alert-danger">Vakalatnama case is not available by '.$response_type.'</center>';exit();
                $data['sc_case_type'] = $this->Dropdown_list_model->get_sci_case_type();
                $this->load->view('templates/user_header');
                $this->load->view('vakalatnama/search',$data);
                $this->load->view('templates/footer');
            }

        }else{
            $this->load->view('templates/user_header');
            $this->load->view('vakalatnama/search',$data);
            $this->load->view('templates/footer');
        }
    }


    function party_email(){
        $party = trim($_POST['party']);
        $parties=explode('/',$party);
         $party_id=$parties[0];
         $party_email=$parties[1];
         $party_email_targate=$parties[2];
        echo '1@@@'.$party_id.'@@@'.$party_email.'@@@'.$party_email_targate;exit;

    }
    function is_party_party_email(){
        $party = trim($_POST['party']);
        $type = trim($_POST['type']);
        $parties=explode('/',$party);
        $party_id_form=$parties[0];
        $party_email=$parties[1];
        $party_email_targate=$parties[2];
        $party_name=ucwords(strtolower($parties[3]));

        $registration_id=$parties[4];
        $p_r_type=$parties[5];


        $refreshDiv_targate=explode('-',$parties[2]);
        $refreshDiv=$refreshDiv_targate[1];
        if ($type=='update') {
            $sel_selected=$parties[8];
            if ($sel_selected != 'selected') {
                $is_party = $this->vakalatnama_model->is_vakalatnama_parties($registration_id, $p_r_type, $party_id_form);
                if ($is_party != false) {
                    //$this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">This party "' . $party_name . '" is already exist check then try again!.</div>');
                    echo '2@@@' . 'This party ' . $party_name . ' is already exist check then try again!.' . '@@@' . $refreshDiv;
                } else {
                    echo '1@@@' . $party_id_form . '@@@' . $party_email . '@@@' . $party_email_targate;
                    exit;
                }
            } else {
                echo '1@@@' . $party_id_form . '@@@' . $party_email . '@@@' . $party_email_targate;
                exit;
            }

        }else if ($type=='insert') {
            $is_party = $this->vakalatnama_model->is_vakalatnama_parties($registration_id, $p_r_type, $party_id_form);
            if ($is_party != false) {
                //$this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">This party "' . $party_name . '" is already exist check then try again!.</div>');
                echo '2@@@' . 'This party ' . $party_name . ' is already exist check then try again!.' . '@@@' . $refreshDiv;
            } else {
                echo '1@@@' . $party_id_form . '@@@' . $party_email . '@@@' . $party_email_targate;
                exit;
            }
        } else {
            echo '1@@@' . $party_id_form . '@@@' . $party_email . '@@@' . $party_email_targate;
            exit;
        }

    }
    function vakalatnama_delete(){
        $vakalatnama_id = url_decryption(trim($_POST['vakalatnama']));


        if(!empty($vakalatnama_id) && !empty($vakalatnama_id) && $_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = array(
                    'is_deleted' =>TRUE ,
                    'updated_on_ip' => getClientIP(),
                    'updated_on' => date('Y-m-d H:i:s')
                );
            $this->db->WHERE('id',$vakalatnama_id);
            $this->db->UPDATE('vakalat.tbl_vakalatnama',$data);
            if ($this->db->affected_rows() > 0) {
                $this->db->WHERE('vakalatnama_id',$vakalatnama_id);
                $this->db->UPDATE('vakalat.tbl_vakalatnama_parties',$data);

                    echo '1@@@' . $vakalatnama_id . '@@@'.'Successfully vakalatnama is deleted';
                }else{ echo '2@@@'.'Some thing wrong! Please try again later.';  }

            exit;
        }else{
            echo '2@@@'.'Some thing wrong! Please try again later...';exit();
        }

    }
    function party_delete(){
        $party = url_decryption(trim($_POST['party']));
        $parties = explode('/', $party);
        $party_id = $parties[0];
        $delete_party_row_targate = $parties[1];

        if(!empty($party_id) && !empty($delete_party_row_targate) && $_SERVER['REQUEST_METHOD'] === 'POST') {

            if (!empty($party_id) && $party_id !=null){
               $result = $this->vakalatnama_model->vakalatnama_party_delete($party_id);
                if ($result){
                    echo '1@@@' . $party_id . '@@@' . $delete_party_row_targate;
                }else{ echo '2@@@'.'Some thing wrong! Please try again later.';  }
            }else{ echo '2@@@'.'Some thing wrong! Please try again later..'; }

            exit;
        }else{
            echo '2@@@'.'Some thing wrong! Please try again later...';exit();
        }

    }


}

?>