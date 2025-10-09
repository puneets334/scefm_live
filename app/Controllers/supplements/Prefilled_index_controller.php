<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Prefilled_index_controller extends CI_Controller
{


    public function __construct()
    {

        parent::__construct();

        $this->load->model('supplements/Prefilled_index_model');
        $this->load->library('TCPDF');
        $this->load->helper('file');
        $this->load->library('slice');
        $this->load->helper('functions');
    }
    public function home_page_docs(){
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_DEPARTMENT);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            redirect('adminDashboard');
            exit(0);
        }

        $data['prefilledIndx_listView'] = $this->Prefilled_index_model->get_genrateindx_list();
        //print_r($data['prefilledIndx_listView']);exit();

        $this->load->view('templates/header');
        $this->load->view('supplements/prefilled_index_home_view' , $data);
        $this->load->view('templates/footer');

    }

    public function indexDocHome()
    {
       // $r=$_GET['id'];
        unset($_SESSION['INDX_ID_MSG']);
        $data['divshow']=$_GET['id'];
        //unset($_SESSION['efiling_details']);

        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_DEPARTMENT);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            redirect('adminDashboard');
            exit(0);
        }



        $this->load->view('templates/header');
        $this->load->view('supplements/prefilled_index_view',$data);
        $this->load->view('templates/footer');

    }//end of function indexDocHome()..

    public function indexData_Edit()
    {//echo "jai sri ram";exit();

        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_DEPARTMENT);
        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            redirect('adminDashboard');
            exit(0);
        }

        //$indx_Id_edit=strtoupper($_POST['Indx_No_edit']);
        $indx_Id_edit=strtoupper($_GET['Indx_No_edit']);


        /*echo "jai sri ram"  . '/ ' . $indx_Id_edit;
        print_r($data['divshow']);
        exit();*/

        $data['docs_indx_data'] = $this->Prefilled_index_model->get_pdf_store_data_indx_edit($indx_Id_edit);
        //$data['divshow']=$_POST['doc_type'];
        $data['divshow']=trim($_GET['doc_type']);

       /* echo $indx_Id_edit ;

        print_r($data['divshow']);
        exit();*/


        //print_r($data['docs_indx_data']) ; exit();
        if($data['docs_indx_data']==''){

            $this->session->set_flashdata('MSG','Data Not Found!' .$indx_Id_edit );
            //redirect('case/ancillary/Indexdocuments', 'refresh');
            redirect('case/ancillary/Indexdocuments' , 'refresh');
            exit(0);

        }else{

            $this->load->view('templates/header');
            $this->load->view('supplements/prefilled_index_view',$data);
            $this->load->view('templates/footer');
        }



    }//end of function indexData_Edit()..

    function add_index_data(){

        //unset($_SESSION['INDX_ID_MSG']);
        $deficit_rslt=$this->Prefilled_index_model->gen_indexcounter_number();

        $index_countr=$deficit_rslt['index_counter_no'];
        $index_countr_Yr=$deficit_rslt['index_counter_yr'];
        $indexID=trim("EI".$index_countr.$index_countr_Yr);

        $InputArray = $this->input->post();
        $Insert_array = array();
        $tem_array=array();
        $curr_dt_time = date('Y-m-d H:i:s');
       // $remove_lst_row=array_pop($InputArray['docs']);
        $count=count($InputArray['docs']);

        for($i=0; $i< $count ; $i++){
            //$abc=$val['docs'];
            if(!empty($InputArray['docs'][$i])){
                $tem_array['index_id']=$indexID;
                $tem_array['documents']=$InputArray['docs'][$i];
                $tem_array['from_page_no']=!empty($InputArray['Frompage'][$i]) ? $InputArray['Frompage'][$i] : NULL;
                $tem_array['remarks']=!empty($InputArray['remrks'][$i]) ? $InputArray['remrks'][$i] : NULL;
                $tem_array['contents']=!empty($InputArray['contents'][$i]) ? $InputArray['contents'][$i] : NULL;
                $tem_array['volume']=!empty($InputArray['vol'][$i]) ? $InputArray['vol'][$i] : NULL;
                $tem_array['created_by']=$_SESSION['login']['id'];
                $tem_array['created_on']=$curr_dt_time;
                $tem_array['docs_readonly_flag']=$InputArray['docs_read_only'][$i];
                $tem_array['to_page_no']=!empty($InputArray['Topage'][$i]) ? $InputArray['Topage'][$i] : NULL;
                array_push ($Insert_array,$tem_array);

            }
        }//End of for loop()..

        $Indx_Id_Msg=$indexID;

        $result = $this->Prefilled_index_model->Index_pdf_data($Insert_array);


        if ($result==1) {

            //$this->gen_IndexPrefilled_pdf($indexID);
           // $this->session->set_flashdata('indxmsg',$Indx_Id_Msg);
            //echo '<script type="text/javascript"> window.top.location.href = "'.base_url().'case/ancillary/documents" </script>';


            echo $Indx_Id_Msg; exit();



        } else {
            echo "FALSE";
            /*$this->session->set_flashdata('MSG', '<div class="alert alert-danger text-center">Some error ! Please try after some time.</div>');
            redirect('supplements/listing_proforma_controller');
            exit(0);*/
        }

    }//END of function add_index_data()..

    /*function gen_IndexPrefilled_pdf($indexID) {*/
        function gen_IndexPrefilled_pdf() {

        $indexID_download=strtoupper($_POST['Indx_pdf_download']);

        //$indexID_download=strtoupper($_GET['Indx_pdf_download']);


            // echo $indx_Id_download ; exit();

        $Pdf_Gen_Data = $this->Prefilled_index_model->get_pdf_store_data_indx($indexID_download);

        $Pdf_Gen_Data_mstr_indx = $this->Prefilled_index_model->get_pdf_store_data_mstrindx($indexID_download);
       // print_r($Pdf_Gen_Data_mstr_indx);exit();

        ob_start();
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, array(  288,  210), PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetPrintHeader(FALSE);
        $pdf->SetPrintFooter(TRUE);

        $pdf->SetAuthor('Computer Cell, SCI');
        $pdf->SetTitle('Computer Cell, SCI');
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->setCellHeightRatio(2);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once(dirname(__FILE__) . '/lang/eng.php');
            $pdf->setLanguageArray($l);
        }

        $pdf->SetFont('helvetica', '', 10, '', true);
        $pdf->AddPage();
        ob_end_clean();

        // create some HTML content

        if(array_search(2, array_column($Pdf_Gen_Data, 'volume')) !== False || array_search(3, array_column($Pdf_Gen_Data, 'volume')) !== False) {

            $html_mstr_headr ='<h4 style="text-align: center"> MASTER INDEX</h4>';

            $html_mstr_indx ='
                <table border="0.5" style="width:100% ; font-size: 12px "  ><tr>
                    <td style="width: 5%" ><b>SL.NO.</b></td>
                    <td style="width: 50% ; text-align: center ;" ><b> Volume</b></td>
                    <td style="width: 28% ; text-align: center ;" ><b>Index Page No</b></td>
                    
                    </tr>
                <tbody> ';


            $html_mstr_indx1 ='';
            $i=1;
            /*$vol1_frompgNO='';
            $vol1_topgNO='';
            $vol2_frompgNO='';
            $vol2_topgNO='';
            $vol3_frompgNO='';
            $vol3_topgNO='';
            $indx_vol='';
            $vol1_count='Y';
            $vol2_count='Y';
            $vol3_count='Y';*/

            foreach ($Pdf_Gen_Data_mstr_indx as $s){

                //$frompg_val=$s['minvalue'];
                //$topg_val=$s['maxvalue'];
                $vol=$s['volume'];
                if($vol==1){
                    // echo "hulalalala"; exit();
                    $indx_vol='VOL I';
                    $frompg_val=$s['minvalue'];
                    $topg_val=$s['maxvalue'];

                }
                elseif($vol==2 ){
                    $indx_vol='VOL II';
                    $frompg_val=$s['minvalue'];
                    $topg_val=$s['maxvalue'];
                }
                elseif($vol==3 ){
                    $indx_vol='VOL III';
                    $frompg_val=$s['minvalue'];
                    $topg_val=$s['maxvalue'];
                }


                $html_mstr_indx1 .='<tr>
                            <td style="width: 5% ; text-align: center ;">' . htmlentities($i++, ENT_QUOTES) . '</td>
                            <td style="width: 50%; text-align: center ;">' . htmlentities($indx_vol, ENT_QUOTES) . '</td>
                            <td style="width: 28%; text-align: center ;">' . htmlentities($frompg_val . ' - ' . $topg_val , ENT_QUOTES) . '</td>
                            
                        </tr>';

                $html_mstr_indx2 = '</tbody></table><br pagebreak="true"/>';




            }//END of foreach loop()..
            //exit();

        }//End of if condition array_search()..









        $html4='';
       $html1 = '<p style="text-align: justify-all ;">Part I of the case file. The applications to be listed before the Court and Judge in Chamber/Court 
                    of Registrar shall be placed in Part I and Part II respectively.   
                    No applications included in Part II shall form part of the paper book.</p><br/>';

        $html_headr ='<h4 style="text-align: center">INDEX OF CONTENTS</h4>';
        $html2 = '
        <table border="0.5" style="width:100% ; font-size: 12px "  ><tr>
            <td style="width: 5%" rowspan="2"><b>SL.NO.</b></td>
            <td style="width: 50% ; text-align: center ;" rowspan="2"><b> Particulars of Document</b></td>
            <td style="width: 28% ; text-align: center ;" colspan="2"><b>Page No. of part to which it belongs  </b></td>
            <td style="width: 15% ; text-align: center ;" rowspan="2"><b>Remarks  </b></td>
            
            </tr>
            <tr>
                <td>Part I (Contents of Paper Book)</td>
                <td>Part II (Contents of file alone)</td>
            </tr>
        <tbody>';
        $html3 ='<tr>
                        <td>' . htmlentities('(i)', ENT_QUOTES) . '</td>
                        <td>' . htmlentities('(ii)', ENT_QUOTES) . '</td>
                        <td>' . htmlentities('(iii)', ENT_QUOTES) . '</td>
                        <td>' . htmlentities('(iv)', ENT_QUOTES) . '</td>
                        <td>' . htmlentities('(v)', ENT_QUOTES) . '</td>

                </tr>';
        $i=1;
        foreach ($Pdf_Gen_Data as $r){
            $partdocs=$r['documents'];
            $rmks=$r['remarks'];
            $part1_PgNo='';
            $part2_PgNo='';
            if($r['contents']==1){
                $part1_PgNo= $r['from_page_no'] . ' - ' . $r['to_page_no'] ;
            }elseif ($r['contents']==2){
                $part2_PgNo= $r['from_page_no'] . ' - ' . $r['to_page_no'] ;
            }elseif ($r['contents']==3){
                $part1_PgNo= $r['from_page_no'] . ' - ' . $r['to_page_no'] ;
                $part2_PgNo= $r['from_page_no'] . ' - ' . $r['to_page_no'] ;
            }
            if(!empty($partdocs)){
                $html4 .='<tr>
                        <td style="width: 5%;text-align:center;">' . htmlentities($i++, ENT_QUOTES) . '</td>
                        <td style="width: 50%;text-align:justify-all;">' . $partdocs . '</td>
                        <td style="width: 14%;text-align:center;">' . htmlentities($part1_PgNo, ENT_QUOTES) . '</td>
                        <td style="width: 14%;text-align:center;">' . htmlentities($part2_PgNo, ENT_QUOTES) . '</td>
                        <td style="width: 15%;text-align:center;">' . htmlentities($rmks, ENT_QUOTES) . '</td>
                    </tr>';

            }



        }//END of foreach loop()..

            $html5 = '</tbody></table>';

        $htmlMSTR = $html_mstr_headr . $html_mstr_indx . $html_mstr_indx1 . $html_mstr_indx2  ;


        $html =  $html1 . $html_headr . $html2 . $html3 . $html4 .$html5 ;

        // output the HTML content
        $pdf->writeHTML($htmlMSTR, true, false, true, false, '');

        $pdf->writeHTML($html, true, false, true, false, '');


        /*if($Pdf_Gen_Data[0]['is_signed']=='t'){
            $pdf->SetY(-30);
            $pdf->Write(0, "This contents of this PDF is signed by using OTP", '', 0, 'C', true, 0, false, false, 0);
        }
        else{
            // Get the page width/height
            $myPageWidth = $pdf->getPageWidth();
            $myPageHeight = $pdf->getPageHeight();

            // Find the middle of the page and adjust.
            $myX = ( $myPageWidth / 2 ) - 75;
            $myY = ( $myPageHeight / 2 ) + 25;

            // Set the transparency of the text to really light
            $pdf->SetAlpha(0.09);

            // Rotate 45 degrees and write the watermarking text
            $pdf->StartTransform();
            $pdf->Rotate(45, $myX, $myY);
            $pdf->SetFont("helvetica", "", 80);
            $pdf->Text($myX, $myY,"PREVIEW");
            $pdf->StopTransform();

            // Reset the transparency to default
            $pdf->SetAlpha(1);
        }*/
        $est_code='SCI';
        $efiling_num=$indexID_download;
        $unsigned_pdf_path = 'uploaded_docs/' . $est_code . '/' . $efiling_num . '/unsigned_pdf/';
        if (!is_dir($unsigned_pdf_path)) {
            $uold = umask(0);
            if (mkdir('uploaded_docs/' . $est_code . '/' . $efiling_num . '/unsigned_pdf/', 0777, true)) {
                $html = '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
                write_file($unsigned_pdf_path . '/index.html', $html);
            }
            umask($uold);
        }

        $pdf->Output($unsigned_pdf_path . $efiling_num . '_INDEX.pdf', 'I'); //F SAVE FILE

            /*<script>

                var myWindow = window.open("", "", "width=200,height=100");

            </script>*/

           // echo '<script> window.open("", "", "width=200,height=100"); </script>';
            //echo anchor(prep_url('www.google.com'), 'Google', 'target="_blank"');

    }//END of function gen_IndexPrefilled_pdf()..



    function update_index_data(){



        /*$index_countr=$deficit_rslt['index_counter_no'];
        $index_countr_Yr=$deficit_rslt['index_counter_yr'];
        $indexID="EI".$index_countr.$index_countr_Yr;*/


        $InputArray = $this->input->post();
        $curr_dt_time = date('Y-m-d H:i:s');
        $insert_array_dlt = array(
            'created_by' => $_SESSION['login']['id'],
            'created_on' => $curr_dt_time,
            'is_active' => '1'

        );
        //$exist_index_rcd_dlt=$this->Prefilled_index_model->indx_rcd_dlt();

        $Update_array = array();
        $tem_array=array();

        // $remove_lst_row=array_pop($InputArray['docs']);
        $count=count($InputArray['docs']);

        for($i=0; $i< $count ; $i++){
            //$abc=$val['docs'];
            if(!empty($InputArray['docs'][$i])) {
                $tem_array['index_id'] = trim($InputArray['indx_Id_RCD'][$i]);
                $tem_array['documents'] = $InputArray['docs'][$i];
                $tem_array['from_page_no'] = !empty($InputArray['Frompage'][$i]) ? $InputArray['Frompage'][$i] : NULL;
                $tem_array['remarks'] = !empty($InputArray['remrks'][$i]) ? $InputArray['remrks'][$i] : NULL;
                $tem_array['contents'] = !empty($InputArray['contents'][$i]) ? $InputArray['contents'][$i] : NULL;
                $tem_array['volume'] = !empty($InputArray['vol'][$i]) ? $InputArray['vol'][$i] : NULL;
                $tem_array['created_by'] = $_SESSION['login']['id'];
                $tem_array['created_on'] = $curr_dt_time;
                $tem_array['docs_readonly_flag'] = $InputArray['docs_read_only'][$i];
                $tem_array['to_page_no'] = !empty($InputArray['Topage'][$i]) ? $InputArray['Topage'][$i] : NULL;
                array_push($Update_array, $tem_array);
            }
        }//End of for loop ()..


         /*echo "<pre>";
         print_r($Update_array);exit();*/

         $indxID_rcddlt=trim($Update_array[0]['index_id']);

        // echo $indxID_rcddlt; exit();

        $result = $this->Prefilled_index_model->indx_rcd_dlt_update($indxID_rcddlt,$Update_array,$insert_array_dlt);
        // print_r($result); exit();


        if ($result==1) {

            //$this->gen_IndexPrefilled_pdf($indxID_rcddlt);
            /*$this->session->set_flashdata('msg','PDF Added Successfully');
            //echo '<script type="text/javascript"> window.top.location.href = "'.base_url().'case/ancillary/documents" </script>';

            redirect('supplements/listing_proforma_controller');*/


            echo "TRUE";

        } else {
            echo "FALSE";
            /*$this->session->set_flashdata('MSG', '<div class="alert alert-danger text-center">Some error ! Please try after some time.</div>');
            redirect('supplements/listing_proforma_controller');
            exit(0);*/
        }

    }//END of function update_index_data()..

    /*public function pdfDocHome(){
        $registration_id = $_SESSION['efiling_details']['registration_id'];
        $checklist_action = '';
        $checklist_action_type = '';
        $checklist_pdf_preview = '';
        $filled_data = $this->supplement_model->get_data($registration_id);
        if (is_null($filled_data)) {
            $checklist_action = base_url() . 'case/ancillary/checklist';
            $checklist_action_type = 'html';
        } elseif ($filled_data['is_signed'] == 'f') {
            $checklist_action = base_url() . 'case/ancillary/checklist';
            $checklist_action_type = 'sign';
            $checklist_pdf_preview = base_url() . 'supplements/DefaultController/generate_checklist_pdf';
        } else {
            $checklist_action = base_url() . 'supplements/DefaultController/generate_checklist_pdf';
            $checklist_action_type = 'pdf';
        }

        //CERTIFICATE IN SLP
        $slp_cert_sign_status = $this->get_sign_status(DOC_TYPE_SLP_CERT);
        $slp_cert_pdf_preview = '';
        if ($slp_cert_sign_status == 'A') {
            $slp_cert_action_type = 'sign';
            $slp_cert_action = base_url() . 'case/ancillary/checklist';
            $slp_cert_pdf_preview = base_url() . 'supplements/DefaultController/generate_slp_certificate_pdf';
        } elseif ($slp_cert_sign_status == 'V') {
            $slp_cert_action_type = 'pdf';
            $slp_cert_action = base_url() . 'supplements/DefaultController/generate_slp_certificate_pdf';
        }

        //XXXXXXXXXXXXXXXXXXXX Listing Proforma XXXXXXXXXXXXXX
        $proforma_action = '';
        $proforma_action_type = '';
        $proforma_pdf_preview = '';
        $filled_data_proforma = $this->Listing_proforma_model->get_pdf_data_check($registration_id);
        //print_r($filled_data_proforma); exit();

        if (empty($filled_data_proforma[0])) {
            //echo "1"; exit();
            $proforma_action = base_url() . 'supplements/listing_proforma_controller';
            $proforma_action_type = 'html';

        } elseif ($filled_data_proforma[0]['is_signed'] == 'f') {
            //echo "2"; exit();
            $proforma_action = base_url() . 'supplements/listing_proforma_controller/gen_listingProforma_editPdf';
            $proforma_action_type = 'sign';
            $proforma_pdf_preview = base_url() . 'supplements/listing_proforma_controller/gen_listingProforma_pdf';
        } else {
            //echo "3"; exit();
            $proforma_action = base_url() . 'supplements/listing_proforma_controller/gen_listingProforma_pdf';
            $proforma_action_type = 'pdf';
        }
        //XXXXXXXXXXXXXXXXXXXX END  XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

        $data['pdfs'] = array(
            array('doc_name' => 'Advocate Checklist', 'action' => $checklist_action, 'type' => $checklist_action_type, 'doc_type' => DOC_TYPE_CHECKLIST, 'doc_preview' => $checklist_pdf_preview),
            array('doc_name' => 'Affidavit', 'action' => base_url() . 'supplements/DefaultController/', 'type' => 'pdf'),
            array('doc_name' => 'Certificate in SLP', 'action' => $slp_cert_action, 'type' => $slp_cert_action_type, 'doc_type' => DOC_TYPE_SLP_CERT, 'doc_preview' => $slp_cert_pdf_preview),
            array('doc_name' => 'Filing Index/Memo', 'action' => base_url() . 'supplements/DefaultController/generate_filingIndex_pdf', 'type' => 'pdf'),
            array('doc_name' => 'Memo of Appearance', 'action' => base_url() . 'supplements/DefaultController/generate_memo_of_appearance_pdf', 'type' => 'pdf'),
            array('doc_name' => 'Index of ROP', 'action' => base_url() . 'assets/downloads/record_of_proceedings.pdf', 'type' => 'pdf'),
            array('doc_name' => 'Refiling Declaration', 'action' => base_url() . 'supplements/DefaultController/generate_refiling_declaration_pdf', 'type' => 'pdf'),

            array('doc_name' => 'Listing Proforma', 'action' => $proforma_action, 'type' => $proforma_action_type, 'doc_type' => DOC_TYPE_PROFORMA, 'doc_preview' => $proforma_pdf_preview),
        );
        $this->load->view('templates/header');
        $this->load->view('supplements/pdf_home/pdf_list', $data);
        //$this->slice->view('supplements.pdf_home.pdf_list',$data);
        $this->load->view('templates/footer');

    }*/

    /*public function send_otp(){
        send_otp('signing document', $_POST['doc_type'], 1);
        echo 1;
    }

    public function verifyOtp(){
        if (validate_otp($_POST['doc_type'], $_POST['otp'])) {

            if ($_POST['doc_type'] == DOC_TYPE_CHECKLIST) {
                echo $this->supplement_model->update_data('efil.tbl_check_list', array('is_signed' => 't'), array('registration_id' => $_SESSION['efiling_details']['registration_id']));
            } elseif ($_POST['doc_type'] == DOC_TYPE_PROFORMA) {
                echo $this->Listing_proforma_model->update_data_signaftr('efil.tbl_listing_proforma_pdf_gen', array('is_signed' => '1'), array('registration_id' => $_SESSION['efiling_details']['registration_id']));
            }
        }
    }*/

    /*function get_sign_status($doc_type){
        $sign_otp = $this->supplement_model->table_data('efil.tbl_sms_log', array('type_id' => $doc_type, 'tbl_efiling_num_id' => $_SESSION['efiling_details']['registration_id']));
        $validate_status = 'A';
        foreach ($sign_otp as $x) {
            if ($x['validate_status'] == 'A') {
                $validate_status = 'A';
                break;
            }
            elseif ($x['validate_status'] == 'V')
                $validate_status = 'V';
        }
        return $validate_status;
    }*/




}

?>