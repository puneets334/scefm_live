<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('vakalatnama/vakalatnama_model');
        $this->load->model('newcase/Dropdown_list_model');
        $this->load->model('supplements/esign_docs_model');
        //$this->load->model('newcase/Get_details_model');
        $this->load->model('supplements/supplement_model');
        $this->load->model('appearing_for/Appearing_for_model');
        $this->load->model('supplements/Listing_proforma_model');
        $this->load->library('TCPDF');
        $this->load->helper('file');
        $this->load->library('slice');
        $this->load->helper('functions');

        if (!isset($_SESSION['login']) && empty($_SESSION['login'])) {
            redirect('login');
        }
    }
    public function index() {
        $this->slice->view('vakalatnama.dashboard');
    }

    public function vakalatnama_list() {

        $registration_id= $_SESSION['efiling_details']['registration_id'];
        if (empty($registration_id)){echo "<script>alert('$registration_id Registration no is required please try again!'); window.top.location.href='" . base_url() . "vakalatnama';</script>";}
        
        $data['vakalatnama_list'] = $this->vakalatnama_model->get_vakalatnama_details($registration_id);
        $data['is_client_signed'] = $this->is_vakaltnama_signed($data['vakalatnama_list'][0]['vakalatnama_id']);
        $this->load->view('templates/user_header');
        $this->load->view('vakalatnama/dashboard_view',$data);
        $this->load->view('templates/footer');
    }
    function add()
    {
        $registration_id = $_SESSION['efiling_details']['registration_id'];
        if (empty($registration_id)) {
            redirect('vakalatnama');
        }
        if (empty($registration_id)) {
            echo "<script>alert('$registration_id Registration no is required please try again!'); window.top.location.href='" . base_url() . "vakalatnama';</script>";
        }
        $vakalatnama = $this->vakalatnama_model->get_vakalatnama_details($registration_id);
        $data['vakalat_party_p_r_type']=$p_r_type=$vakalatnama[0]['p_r_type'];
        if (isset($_POST['vakalatnama_save']) && !empty($registration_id) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $myself_adv_id = url_decryption(trim($_POST['myself_adv_id']));
            $p_r_type = trim($_POST['p_r_type']);

            $created_on = date('Y-m-d H:i:s');
            $data = array(
                'registration_id' => $registration_id,
                'myself_adv_id' => $myself_adv_id,
                'p_r_type' => $p_r_type,
                'created_on' => $created_on,
                'created_on_ip' => getClientIP(),
                'database_type' => $_SESSION['efiling_details']['database_type'],
            );
            $vakalatnama_id = $this->vakalatnama_model->add_or_update($data);

            if ($vakalatnama_id) {
                //parties add
                $array1 = array();
                foreach ($_REQUEST['party'] as $selectedParty) {
                    $parties = explode('/', $selectedParty);
                    $party_id = $parties[0];
                    $party_email_id = $parties[1];
                    $array1[] = array(
                        'vakalatnama_id' => $vakalatnama_id,
                        'registration_id' => $registration_id,
                        'party_id' => $party_id,
                        'party_name' => $parties[3],
                        'p_r_type' => $p_r_type,
                        'party_no' => $parties[6],
                        'created_by' => $_SESSION['login']['id'],
                        'created_on' => $created_on,
                        'created_on_ip' => getClientIP(),
                        'database_type' => $_SESSION['efiling_details']['database_type'],
                    );
                }
                $array2 = array();
                foreach ($_REQUEST['party_email_id'] as $selectedPartyEmail) {
                    $array2[] = array('party_email_id' => $selectedPartyEmail,);
                }
                $parties_id_data = array_map(function ($array1, $array2) {
                    return array_merge(isset($array1) ? $array1 : array(), isset($array2) ? $array2 : array());
                }, $array1, $array2);
                $tempArr = array_unique(array_column($parties_id_data, 'party_id'));
                $parties_id_data_final = array_intersect_key($parties_id_data, $tempArr);
                if (!empty($parties_id_data_final) && count($parties_id_data_final) > 0 && !empty($vakalatnama_id) && !empty($vakalatnama_id) && !empty($p_r_type)) {
                    $is_add_party = $this->vakalatnama_model->add_party($registration_id, $vakalatnama_id, $p_r_type, $parties_id_data_final, 'insert');

                }

                //additional adv add
                $adv_users_id_data = array();
                foreach ($_REQUEST['adv_users_id'] as $selectedOption) {
                    $adv_users_id_data[] = array(
                        'vakalatnama_id' => $vakalatnama_id,
                        'registration_id' => $registration_id,
                        'aor_code' => $selectedOption,
                        'created_on' => $created_on,
                        'created_on_ip' => getClientIP(),
                        'database_type' => $_SESSION['efiling_details']['database_type'],
                    );
                }
                if (!empty($adv_users_id_data) && count($adv_users_id_data) > 0 && $adv_users_id_data != null) {
                    $result = $this->db->insert_batch('vakalat.tbl_vakalatnama_aor', $adv_users_id_data);
                }
                $this->session->set_flashdata('msg', '<div class="alert alert-success text-center">Successfully your request added.</div>');
                $param = url_encryption($registration_id . '#' . $vakalatnama_id . '#' . $p_r_type);
                $this->pdf($param);
                redirect('vakalatnama/dashboard/vakalatnama_list');
            } else {
                $this->session->set_flashdata('error', 'Something went wrong,Please try again later.');
            }


        } else if (isset($_POST['vakalatnama_party_check']) && !empty($registration_id) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $p_r_type = trim($_POST['party_type']);

        }



        if(!empty($p_r_type) && $p_r_type !=null){
            $data['p_r_type']=$p_r_type;
            $data['parties']=$this->vakalatnama_model->get_case_parties_details_vakalatnama($registration_id, array('p_r_type'=>$p_r_type,'view_lr_list' => FALSE));
            //echo '<pre>';print_r($data['parties']);exit();
        }else{
            $data['p_r_type']='';
            $data['parties']=array();
        }
        $data['registration_id']=$registration_id;
        $data['users_types_list'] = $this->vakalatnama_model->get_user_types();

        $this->load->view('templates/user_header');
        $this->load->view('vakalatnama/vakalatnama_add',$data);
        $this->load->view('templates/footer');



    }

    function action($param)
    {
        $data['param']=$param;
        $param_Data=url_decryption(trim($param));
        $paramData=explode('#',$param_Data);
         $registration_id=$paramData[0];
        $vakalatnama_id=$paramData[1];
        $p_r_type=$paramData[2];

        $vakalat_signed = $this->is_vakaltnama_signed($vakalatnama_id);
        if (empty($registration_id)){echo "<script>alert('$registration_id Registration no is required please try again!'); window.top.location.href='" . base_url() . "vakalatnama';</script>";}
        if($vakalat_signed){echo "<script>alert('Vakalatnama has been signed by atleast one party.'); window.top.location.href='" . base_url() . "vakalatnama';</script>";}
        
        if(isset($_POST['vakalatnama_update']) && !empty($registration_id) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $myself_adv_id = url_decryption(trim($_POST['myself_adv_id']));
            $myself_adv_name = trim($_POST['myself_adv_name']);
            $vakalatnama_id = url_decryption(trim($_POST['vakalatnama_id']));
            $party_email_id = trim($_POST['party_email_id']);
            $p_r_type = trim($_POST['p_r_type']);

            $created_on = date('Y-m-d H:i:s');

            if (!empty($vakalatnama_id) && $vakalatnama_id != null && isset($_POST['vakalatnama_update'])) {

                if (!empty($vakalatnama_id)) {
                    //parties add

                    $array1 = array();
                    foreach ($_REQUEST['party_update'] as $selectedParty) {
                        $parties = explode('/', $selectedParty);
                        $party_id = $parties[0];
                        $party_email_id = $parties[1];

                        $array1[] = array(
                            'id' => $parties[7],
                            'party_id' => $party_id,
                            'party_name' => $parties[3],
                            'p_r_type' => $p_r_type,
                            'party_no' => $parties[6],
                            'updated_on' => $created_on,
                            'updated_on_ip' => getClientIP(),
                        );
                    }
                    $array2 = array();
                    foreach ($_REQUEST['party_email_id_update'] as $selectedPartyEmail) {
                        $array2[] = array('party_email_id' => $selectedPartyEmail,);
                    }
                    $parties_id_data = array_map(function ($array1, $array2) {
                        return array_merge(isset($array1) ? $array1 : array(), isset($array2) ? $array2 : array());
                    }, $array1, $array2);
                    $tempArr = array_unique(array_column($parties_id_data, 'party_id'));
                    $parties_id_data_final= array_intersect_key($parties_id_data, $tempArr);
                    if (!empty($parties_id_data_final) && count($parties_id_data_final) > 0 && !empty($vakalatnama_id) && !empty($vakalatnama_id) && !empty($p_r_type)) {
                      $this->db->update_batch('vakalat.tbl_vakalatnama_parties',$parties_id_data_final,'id');
                   }

                    /*start updated after added party*/
                   if (isset($_POST['party_add_form'])) {
                       $add_array1 = array();
                       foreach ($_REQUEST['party'] as $selectedParty) {
                           $parties = explode('/', $selectedParty);
                           $party_id = $parties[0];
                           $party_email_id = $parties[1];
                           $add_array1[] = array(
                               'vakalatnama_id' => $vakalatnama_id,
                               'registration_id' => $registration_id,
                               'party_id' => $party_id,
                               'party_name' => $parties[3],
                               'p_r_type' => $p_r_type,
                               'party_no' => $parties[6],
                               'created_on' => $created_on,
                               'created_on_ip' => getClientIP(),
                               'database_type' => $_SESSION['efiling_details']['database_type'],
                           );
                       }
                       $add_array2 = array();
                       foreach ($_REQUEST['party_email_id'] as $selectedPartyEmail) {
                           $add_array2[] = array('party_email_id' => $selectedPartyEmail,);
                       }
                       $add_parties_id_data = array_map(function ($add_array1, $add_array2) {
                           return array_merge(isset($add_array1) ? $add_array1 : array(), isset($add_array2) ? $add_array2 : array());
                       }, $add_array1, $add_array2);
                       $add_tempArr = array_unique(array_column($add_parties_id_data, 'party_id'));
                       $add_parties_id_data_final = array_intersect_key($add_parties_id_data, $add_tempArr);

                       if (!empty($add_parties_id_data_final) && count($add_parties_id_data_final) > 0 && !empty($vakalatnama_id) && !empty($vakalatnama_id) && !empty($p_r_type)) {
                          $is_add_party=$this->vakalatnama_model->add_party($registration_id,$vakalatnama_id,$p_r_type,$add_parties_id_data_final,'insert');

                       }

                   }
                    /*end updated after added party*/


                    //additional adv add
                    $adv_users_id_data = array();
                    foreach ($_REQUEST['adv_users_id'] as $selectedOption) {
                        $adv_users_id_data[] = array(
                            'vakalatnama_id' => $vakalatnama_id,
                            'registration_id' => $registration_id,
                            'aor_code' => $selectedOption,
                            'updated_on' => $created_on,
                            'updated_on_ip' => getClientIP(),
                        );
                    }
                    if (!empty($adv_users_id_data) && count($adv_users_id_data) > 0 && $adv_users_id_data !=null){
                    $this->db->WHERE('vakalatnama_id', $vakalatnama_id);
                    $this->db->WHERE('registration_id', $registration_id);
                    $query = $this->db->DELETE('vakalat.tbl_vakalatnama_aor');
                    if ($query) {
                        $result = $this->db->insert_batch('vakalat.tbl_vakalatnama_aor', $adv_users_id_data);
                    }
                    }
                    $this->pdf($param);
                    $this->session->set_flashdata('msg', '<div class="alert alert-success text-center">Successfully your request updated.</div>');
                    redirect('vakalatnama/dashboard/vakalatnama_list');

                } else {
                    $this->session->set_flashdata('error', 'Something went wrong,Please try again later.');
                }


            }

        }else if(isset($_POST['vakalatnama_party_check']) && !empty($registration_id) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $p_r_type = trim($_POST['party_type']);

        }

        $data['vakalatnama_parties']=array();
        $data['parties_list']=array();


        $data['vakalatnama'] = $this->vakalatnama_model->get_vakalatnama_details($registration_id,$vakalatnama_id,$p_r_type);

        $data['vakalatnama_parties']=$this->vakalatnama_model->get_vakalatnama_parties_details($registration_id,$vakalatnama_id,$p_r_type);

        if(!empty($p_r_type) && $p_r_type !=null){
            $data['p_r_type']=$p_r_type;
            $data['parties']=$this->vakalatnama_model->get_case_parties_details_vakalatnama($registration_id, array('p_r_type'=>$p_r_type,'view_lr_list' => FALSE));
        }else{
            $data['p_r_type']='';
            $data['parties']=array();
        }

        $vakalatnama_aor = $this->vakalatnama_model->get_vakalatnama_aor_list($registration_id,$vakalatnama_id);
        $data['aor_code']='';$akg=1;
        foreach ($vakalatnama_aor as $row){ if ($akg==1){$data['aor_code'].=$row['aor_code'];}else{$data['aor_code'].=','.$row['aor_code'];}$akg++;}

        $data['aor_code_list']= explode(',',$data['aor_code']);
        $data['users_types_list'] = $this->vakalatnama_model->get_user_types();
        //echo '<pre>';print_r($data['parties']);exit();
        $this->load->view('templates/user_header');
        $this->load->view('vakalatnama/vakalatnama_update',$data);
        $this->load->view('templates/footer');



    }

    function pdf($param)
    {
        $data['param']=$param;
        $param_Data=url_decryption(trim($param));
        $paramData=explode('#',$param_Data);
        $registration_id=$paramData[0];
        $vakalatnama_id=$paramData[1];
        $p_r_type=$paramData[2];
        if (!empty($_SESSION['efiling_details']['database_type']) && $_SESSION['efiling_details']['database_type']=='I') {
            $parties = array([
                'petitioner_name' => ucwords(strtolower($_SESSION['efiling_details']['pet_name'])),
                'respondent_name' => ucwords(strtolower($_SESSION['efiling_details']['res_name'])),
            ]);
        }else{
            $case_detail = $this->supplement_model->table_data('efil.tbl_case_details', array('registration_id' => $registration_id));
            $sc_case_type_id = $case_detail[0]['sc_case_type_id'];
            if (!empty($sc_case_type_id) && count($sc_case_type_id) > 0 && $sc_case_type_id == 13 || $sc_case_type_id == 14) {
                $parties = get_affidavit_pdf_details($sc_case_type_id);
            } else {
                $parties = $this->supplement_model->get_signers_list($registration_id);
                $parties = array([
                    'petitioner_name' => $parties[0]['party_name'],
                    'respondent_name' => $parties[1]['party_name'],
                ]);
            }
        }
        $vakalatnama = $this->vakalatnama_model->get_vakalatnama_details($registration_id,$vakalatnama_id,$p_r_type);
        $vakalatnama_aor = $this->vakalatnama_model->get_vakalatnama_aor_list($registration_id,$vakalatnama_id);
        $vakalatnama_parties=$this->vakalatnama_model->get_vakalatnama_parties_details($registration_id,$vakalatnama_id,$p_r_type);
        $aor='';
        if (!empty($vakalatnama[0]['last_name']) && $vakalatnama[0]['last_name'] !=null && $vakalatnama[0]['last_name'] !='NULL'){ $myself_adv_name=ucwords(strtolower($vakalatnama[0]['first_name'].' '.$vakalatnama[0]['last_name']));}else{$myself_adv_name=ucwords(strtolower($vakalatnama[0]['first_name']));}

        if (!empty($vakalatnama_aor) && $vakalatnama_aor !=null && count($vakalatnama_aor) > 0){
            foreach ($vakalatnama_aor as $row){
                if (!empty($row['last_name']) && $row['last_name'] !=null && $row['last_name'] !='NULL'){ $adv_name=ucwords(strtolower($row['first_name'].' '.$row['last_name']));}else{$adv_name=ucwords(strtolower($row['first_name']));}
                $aor.=','.' '.$adv_name;
            }
        }
        $parties_name_list='';$akg=1;
        if (!empty($vakalatnama_parties) && $vakalatnama_parties !=null && count($vakalatnama_parties) > 0){
            foreach ($vakalatnama_parties as $row){  if ($akg==1) { $parties_name_list .= ucwords(strtolower($row['party_name'])).' ['.$row['p_r_type'].'-'.$row['sr_no_show'].']';}else{ $parties_name_list .= ',' . ' ' . ucwords(strtolower($row['party_name'])).' ['.$row['p_r_type'].'-'.$row['sr_no_show'].']';} $akg++;} }


       $aor_list= '<span style="text-decoration: underline;">'.$myself_adv_name.$aor.'</span>';
       $party_name= '<span style="text-decoration: underline;">'.$parties_name_list.'</span>';

        ob_start();
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetPrintHeader(FALSE);
        $pdf->SetPrintFooter(FALSE);

        $pdf->SetAuthor('Computer Cell, SCI');
        $pdf->SetTitle('Computer Cell, SCI');
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_HEADER, PDF_MARGIN_RIGHT);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once(dirname(__FILE__) . '/lang/eng.php');
            $pdf->setLanguageArray($l);
        }

        $pdf->SetFont('helvetica', '', 12, '', true);
        $pdf->AddPage();
        ob_end_clean();

        $html = '<h3 style="text-align:center">IN THE SUPREME COURT OF INDIA</h3>
                     <h4 style="text-align:center">CIVIL/CRIMINAL/ORIGINAL/APPELLATE/JURISDICTION</h4>
                     <h4 style="text-align:center;font-weight: normal">S.L.P.(C/Crl.)/Civil/Crl. Appeal/Writ Petition/T.P.No._______________20</h4>
                    <table><tr><td></td></tr><tr><td></td></tr>
                    <tr><td width="80%">' . $parties[0]['petitioner_name'] . '</td> <td width="20%">(PETITIONER)</td> </tr>
                    <tr><td width="100%"> <h4 style="text-align: center;">VERSUS</h4></td></tr>
                    <tr><td width="80%">' . $parties[0]['respondent_name'] . '</td> <td width="20%">(RESPONDENT)</td> </tr>
                    </table>
                    <h4 style="text-align:center;">VAKALATNAMA</h4>';
        $html .= '<p align="justify" style="line-height: 2.5;">
                   I/We '.$party_name.'
Appellants(s)/Petitioner(s)/Respondent(s)/Opposite party in the above Suit/ Appeal: Petition/
Reference do hereby appoint and retain  '.$aor_list. ' Advocate of the
Supreme Court to act and appear for me/us in the above Suit/ Appeal/ Petition/ Reference and or
my /our behalf to conduct and prosecute (or defend) the same and all proceedings that may be
taken in respect of my application connected with the same of any decree order passed therein,
including proceedings in taxation and application for Review, to file and obtain return of
documents, and to deposit and receive money on my/ or behalf in the said Suit Appeal/ Petition
Reference and in application of Review, and to represent me/us and to take all necessary steps
on my /our behalf in the above matter, I/We agree to ratify all acts done by the aforesaid
Advocate in pursuance of this authority.
                  </p>';
        $html .= '<p>Dated this the________________________day of__________________________20 </p>';
        $html .= '<tr><td></td></tr><tr><td>Accepted</td></tr>';
        $html .= '<tr><td width="60%"></td><td width="40%">APPELLANT(s)/PETITIONER(s)/<br>CAVEATOR(s)/RESPONDENT(s)</td></tr>';
        $html .= '<tr><td width="50%" style="text-decoration: underline;">ADVOCATE SUPREME COURT</td></tr><tr><td width="50%" style="text-decoration: underline;"></td></tr>';
        /* $html.='<h3 style="text-align:center;">MEMO OF APPEARANCE</h3>';
         $html .= '<p align="justify"  style="line-height: 1.2;">To,<br>The Registrar<br>Supreme Court of India<br>New Delhi<br/><br/>Sir,<br/></p>';
         $html .= '<tr><td></td></tr><tr><td>Please enter my appearance on behalf on the Petitioner(s) /Appellant(s)/ Respondent(s)
 /Intervenor in the matter above mentioned.</td></tr><tr><td></td></tr>';
         $html .= '<p>Dated this the__________________day of___________________20 </p>';
         $html .= '<tr><td width="70%"></td><td width="30%">Yours faithfully,</td></tr>';
         $html .= '<tr><td></td></tr><tr><td width="40%"></td><td width="60%">Advocate for Petitioner(s)/Appellant(s)/Respondent(s)</td></tr>';
        */ // output the HTML content
        $pdf->writeHTML($html, true, false, true, false, '');
        $unsigned_pdf_path = 'uploaded_docs/vakalatnama/' . $vakalatnama[0]['database_type'].$vakalatnama[0]['registration_id'] . '/unsigned_pdf/';
        if (!is_dir($unsigned_pdf_path)) {
            $uold = umask(0);
            if (mkdir($unsigned_pdf_path, 0777, true)) {
                $html = '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
                write_file($unsigned_pdf_path . '/index.html', $html);
            }
            umask($uold);
        }
        $pdf->Output($unsigned_pdf_path . $vakalatnama[0]['id'] . '_UnsignedVakalatnama.pdf', 'F');
    }
    
    
    private function is_vakaltnama_signed($vakalatnama_id){
        $number_of_signs = 0;
        $signers=$this->esign_docs_model->table_data('vakalat.tbl_vakalatnama_parties', array('vakalatnama_id'=>$vakalatnama_id, 'is_deleted'=>'false'));
        foreach ($signers as $signer){
            if($signer['signed']=='t')
                $number_of_signs++;
        }
        return $number_of_signs;
    }

}

?>