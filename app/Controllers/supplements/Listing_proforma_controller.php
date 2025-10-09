<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 30/5/21
 * Time: 6:57 PM
 */
?>

<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Listing_proforma_controller extends CI_Controller {


    public function __construct() {
        parent::__construct();
        $this->load->helper();
        //load the login model
        $this->load->model('supplements/Listing_proforma_model');
        $this->load->library('form_validation');
        $this->load->library('TCPDF');
        $this->load->helper('file');
    }

    public function index() {
        //echo "rounak";exit();

        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);

        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            redirect('adminDashboard');
            exit(0);
        }

        //$registrationID=183;
        $registrationID = $_SESSION['efiling_details']['registration_id'];

        $data['pdf_listing_proforma_data'] = $this->Listing_proforma_model->get_listing_proforma_list_pdf($registrationID);
        $data['pdf_actsection_data'] = $this->Listing_proforma_model->get_act_list_pdf($registrationID);

        $this->load->view('templates/header');
        $this->load->view('supplements/listing_proforma_form_view', $data);
        $this->load->view('templates/footer');
    }//End of function index() ..

    public function add_pdf_data_insrt() {
        //echo "rounak mishra";exit();

        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);

        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            redirect('adminDashboard');
            exit(0);
        }

        $InputArray = $this->input->post();
        //print_r($InputArray);exit();

        $this->form_validation->set_rules('name_of_judges_passed', 'Name of Judges who passed the order', 'required|trim|min_length[5]|validate_alpha_numeric_single_double_quotes_bracket_with_special_characters');
        $this->form_validation->set_rules('not_to_be_listed', 'Not to be Listed Before ', 'required|trim|min_length[5]|validate_alpha_numeric_single_double_quotes_bracket_with_special_characters');
        $this->form_validation->set_rules('sentence_awarded', 'Sentence Awarded', 'required|trim|validate_alpha_numeric_single_double_quotes_bracket_with_special_characters');
        $this->form_validation->set_rules('period_sentence_undergone', 'Period of Sentence Undergone including period of detention', 'required|trim|validate_alpha_numeric_single_double_quotes_bracket_with_special_characters');



        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->error_array();

            $this->session->set_flashdata('name_of_judges_passed', '<div class="text-danger">' . $error['name_of_judges_passed'] . '</div>');
            $this->session->set_flashdata('not_to_be_listed', '<div class="text-danger">' . $error['not_to_be_listed'] . '</div>');
            $this->session->set_flashdata('sentence_awarded', '<div class="text-danger">' . $error['sentence_awarded'] . '</div>');
            $this->session->set_flashdata('period_sentence_undergone', '<div class="text-danger">' . $error['Period Sentence Undergone'] . '</div>');

            redirect('supplements/listing_proforma_controller');
            exit(0);
        }

        $curr_dt_time = date('Y-m-d H:i:s');

        if ($InputArray != '') {
            $data_pdf_status = $this->Listing_proforma_model->get_pdf_data_check($InputArray['registrationID']);
            $name_judges_passed = (!empty($InputArray['name_of_judges_passed'] )) ? $InputArray['name_of_judges_passed'] : NULL;
            $tribunal_authority=(!empty($InputArray['tribunal_authority'] )) ? $InputArray['tribunal_authority'] : NULL;
            $not_to_be_listed = (!empty($InputArray['not_to_be_listed'] )) ? $InputArray['not_to_be_listed'] : NULL;
            $disposed_matter_with_citation = (!empty($InputArray['matter_with_citation'] )) ? $InputArray['matter_with_citation'] : NULL;
            if($disposed_matter_with_citation==NULL){
                $disposed_mttr_txt = $InputArray['matter_with_citation_disposed'];
            }else{
                $disposed_mttr_txt = $InputArray['matter_with_citation'];
            }
            $pending_matter_with_case_dtl = (!empty($InputArray['matter_with_case_dtl'] )) ? $InputArray['matter_with_case_dtl'] : NULL;
            if($pending_matter_with_case_dtl==NULL){
                $pending_mttr_txt = $InputArray['matter_with_case_dtl_pending'];
            }else{
                $pending_mttr_txt = $InputArray['matter_with_case_dtl'];
            }

            $surrendered = (!empty($InputArray['surrendered'] )) ? $InputArray['surrendered'] : NULL;

            $sentence_awarded=(($InputArray['sentence_awarded']!='' )) ? $InputArray['sentence_awarded'] : NULL;

            $period_sentence_undergone = (($InputArray['period_sentence_undergone']!='' )) ? $InputArray['period_sentence_undergone'] : NULL;

            //echo $period_sentence_undergone . ' // ' . $sentence_awarded ; exit();
            $land_acquisition_mattr = (!empty($InputArray['land_acquisition_mattr'] )) ? $InputArray['land_acquisition_mattr'] : NULL;
            $date_section4_notification = (!empty($InputArray['date_section4_notification'] )) ? $InputArray['date_section4_notification'] : NULL;
            //$section4_notification_Date=NULL;
            $section4_notification_Date=(!empty($InputArray['date_section4_notification_date'] )) ? $InputArray['date_section4_notification_date'] : NULL;
            if($date_section4_notification!=NULL && $date_section4_notification!='N'){
                $section4_notification_Date = date('Y/m/d', strtotime($InputArray['date_section4_notification_date']));
            }

            $date_section6_notification = (!empty($InputArray['date_section6_notification'] )) ? $InputArray['date_section6_notification'] : NULL;
            $section6_notification_Date=(!empty($InputArray['date_section6_notification_date'] )) ? $InputArray['date_section6_notification_date'] : NULL;
            if($date_section6_notification!=NULL && $date_section6_notification!='N'){
                $section6_notification_Date=date('Y-m-d', strtotime($InputArray['date_section6_notification_date']));
            }
            $date_section17_notification=(!empty($InputArray['date_section17_notification'] )) ? $InputArray['date_section17_notification'] : NULL;
            $section17_notification_Date=(!empty($InputArray['date_section17_notification_date'] )) ? $InputArray['date_section17_notification_date'] : NULL;
            if($date_section17_notification!=NULL && $date_section17_notification!='N'){
                $section17_notification_Date=date('Y-m-d', strtotime($InputArray['date_section17_notification_date']));
            }
            $tax_mttr_state = (!empty($InputArray['tax_mttr_state'] )) ? $InputArray['tax_mttr_state'] : NULL;
            $first_petitioner_appellant = (!empty($InputArray['first_petitioner_appellant'] )) ? $InputArray['first_petitioner_appellant'] : NULL;
            $senior_citizen_65 = (!empty($InputArray['senior_citizen_65'] )) ? $InputArray['senior_citizen_65'] : NULL;
            $sc_st = (!empty($InputArray['sc_st'] )) ? $InputArray['sc_st'] : NULL;
            $women_child = (!empty($InputArray['women_child'] )) ? $InputArray['women_child'] : NULL;
            $disable = (!empty($InputArray['disable'] )) ? $InputArray['disable'] : NULL;
            $legal_aid_case = (!empty($InputArray['legal_aid_case'] )) ? $InputArray['legal_aid_case'] : NULL;
            $in_custody = (!empty($InputArray['in_custody'] )) ? $InputArray['in_custody'] : NULL;
            $vehicle_number = (!empty($InputArray['vehicle_number'] )) ? $InputArray['vehicle_number'] : NULL;


            //if($data_pdf_status[0]['']){
            if(!empty($data_pdf_status) && $data_pdf_status[0]['is_signed']=='f'){

                $update_array = array(
                    'registration_id' => $InputArray['registrationID'],
                    'judges_passed_order' => $name_judges_passed,
                    'tribunal_authority' => $tribunal_authority,
                    'not_to_be_listed' => $not_to_be_listed,
                    'disposed_matter_with_citation' => $disposed_mttr_txt,
                    'pending_matter_with_case' => $pending_mttr_txt,
                    'surrendered' => $surrendered,
                    'sentence_awarded' => $sentence_awarded,
                    'period_sentence_undergone' => $period_sentence_undergone,
                    'land_acquisition_mattr' => $land_acquisition_mattr,
                    'date_section4_notification' => $date_section4_notification,
                    'date_section6_notification' => $date_section6_notification,
                    'date_section17_notification' => $date_section17_notification,
                    'tax_mttr' => $tax_mttr_state,
                    'first_petitioner_appellant' => $first_petitioner_appellant,
                    'senior_citizen' => $senior_citizen_65,
                    'sc_st' => $sc_st,
                    'women_child' => $women_child,
                    'disable' => $disable,
                    'legal_aid_case' => $legal_aid_case,
                    'in_custody' => $in_custody,
                    'vehicle_number' => $vehicle_number,
                    'created_by' => $_SESSION['login']['id'],
                    'created_on' => $curr_dt_time,
                    'is_active' => '1',
                    'sec4_notification_dt' =>$section4_notification_Date,
                    'sec6_notification_dt' =>$section6_notification_Date,
                    'sec17_notification_dt' =>$section17_notification_Date

                );

                // print_r($update_array);exit();

                $result = $this->Listing_proforma_model->update_pdf_data($InputArray['registrationID'],$update_array);

            } else if(empty($data_pdf_status)){

                $insert_array = array(
                    'registration_id' => $InputArray['registrationID'],
                    'judges_passed_order' => $name_judges_passed,
                    'tribunal_authority' => $tribunal_authority,
                    'not_to_be_listed' => $not_to_be_listed,
                    'disposed_matter_with_citation' => $disposed_mttr_txt,
                    'pending_matter_with_case' => $pending_mttr_txt,
                    'surrendered' => $surrendered,
                    'sentence_awarded' => $sentence_awarded,
                    'period_sentence_undergone' => $period_sentence_undergone,
                    'land_acquisition_mattr' => $land_acquisition_mattr,
                    'date_section4_notification' => $date_section4_notification,
                    'date_section6_notification' => $date_section6_notification,
                    'date_section17_notification' => $date_section17_notification,
                    'tax_mttr' => $tax_mttr_state,
                    'first_petitioner_appellant' => $first_petitioner_appellant,
                    'senior_citizen' => $senior_citizen_65,
                    'sc_st' => $sc_st,
                    'women_child' => $women_child,
                    'disable' => $disable,
                    'legal_aid_case' => $legal_aid_case,
                    'in_custody' => $in_custody,
                    'vehicle_number' => $vehicle_number,
                    'created_by' => $_SESSION['login']['id'],
                    'created_on' => $curr_dt_time,
                    'is_active' => '1',
                    'sec4_notification_dt' =>$section4_notification_Date,
                    'sec6_notification_dt' =>$section6_notification_Date,
                    'sec17_notification_dt' =>$section17_notification_Date

                );
                // print_r($insert_array);exit();

                $result = $this->Listing_proforma_model->insert_pdf_data($insert_array);

            } else if ($data_pdf_status[0]['is_signed']=='t'){
                $this->session->set_flashdata('msg_error','Cannot modify data as it has already been signed');

                redirect('supplements/listing_proforma_controller');
                exit(0);

            }else{
                $this->session->set_flashdata('msg_error','Data already exist');
                echo '<script type="text/javascript"> window.top.location.href = "'.base_url().'case/ancillary/documents" </script>';


            }

            //$this->gen_listingProforma_pdf($InputArray['registrationID']);

        }
        else {
            $this->session->set_flashdata('MSG', '<div class="alert alert-danger text-center">Invalid Request.</div>');

            redirect('supplements/listing_proforma_controller');
            exit(0);
        }

        if ($result) {
            $this->session->set_flashdata('msg','PDF Added Successfully');
            echo '<script type="text/javascript"> window.top.location.href = "'.base_url().'case/ancillary/documents" </script>';
            /*echo '<script type="text/javascript"> window.top.location.href = "'.base_url().'supplements/defaultController/pdfDocHome" </script>';*/


            exit(0);
        } else {
            $this->session->set_flashdata('MSG', '<div class="alert alert-danger text-center">Some error ! Please try after some time.</div>');
            redirect('supplements/listing_proforma_controller');
            exit(0);
        }
    }//End of function add_pdf_data_insrt()..


    function gen_listingProforma_pdf() {

        //$reg_store_pdf_id=185;
        $reg_store_pdf_id= $_SESSION['efiling_details']['registration_id'];
        $Pdf_Gen_Data = $this->Listing_proforma_model->get_pdf_store_data($reg_store_pdf_id);
        //$ID_registrationid=183;
        $Pdf_Gen_actData = $this->Listing_proforma_model->get_act_list_pdf($reg_store_pdf_id);

        $central_act_name='';
        $state_act_name='';
        $act_section_cntrl='';
        $act_section_state='';
        $central_rule_title='';
        $central_rule_nos='';
        $state_rule_title='';
        $state_rule_nos='';
        foreach($Pdf_Gen_actData as $val_act){
            $act_state_id=$val_act['state_id'];
            $act_actno=$val_act['actno'];

            if($act_state_id == 0 && $act_actno !=0){
                $central_act_name .=$val_act['act_name'].',';
                $act_section_cntrl .=$val_act['act_section'].',';
            }elseif($act_state_id != 0 && $act_actno !=0) {
                $state_act_name .=$val_act['act_name'].',';
                $act_section_state .=$val_act['act_section'].',';
            }
            if($act_state_id == 0 && $act_actno == 0){
                $central_rule_title .=$val_act['act_name'].',';
                $central_rule_nos .= $val_act['act_section'] . ',';
            }elseif($act_state_id != 0 && $act_actno == 0) {
                $state_rule_title .=$val_act['act_name'].',';
                $state_rule_nos .= $val_act['act_section'] . ',';
            }
        }//end of foreachloop()..

        $central_act_name= !empty($central_act_name) ? rtrim($central_act_name,',') : '';
        $act_section_cntrl=!empty($act_section_cntrl) ? rtrim($act_section_cntrl,',') : '';
        $state_act_name=!empty(($state_act_name)) ? rtrim($state_act_name,',') : '';
        $act_section_state=!empty($act_section_state) ? rtrim($act_section_state,',') : '';
        $central_rule_title=!empty($central_rule_title) ? rtrim($central_rule_title,',') : '';
        $state_rule_title=!empty($state_rule_title) ? rtrim($state_rule_title,',') : '';
        $Cntrl_rule_nos=!empty($central_rule_nos) ? rtrim($central_rule_nos,',') : '';
        $St_rule_nos=!empty($state_rule_nos) ? rtrim($state_rule_nos,',') : '';

        $Pet_party_name=$Pdf_Gen_Data[0]['pet_party_name'];
        $Pet_email=$Pdf_Gen_Data[0]['pet_email'];
        $Pet_mobile=$Pdf_Gen_Data[0]['pet_mobile'];
        $Res_party_name=$Pdf_Gen_Data[0]['res_party_name'];
        $Res_email=$Pdf_Gen_Data[0]['res_email'];
        $Res_mobile=$Pdf_Gen_Data[0]['res_mobile'];
        $Main_category=$Pdf_Gen_Data[0]['main_category'];
        $Sub_category=$Pdf_Gen_Data[0]['sub_category'];
        $High_court_name=$Pdf_Gen_Data[0]['high_court_name'];
        $Fir_no=$Pdf_Gen_Data[0]['fir_no'];
        $Police_station_name=$Pdf_Gen_Data[0]['police_station_name'];
        $Impugned_order_date=trim($Pdf_Gen_Data[0]['impugned_order_date']);
        $Judgment_type=trim($Pdf_Gen_Data[0]['judgment_type']);

        if ($Judgment_type == 'F' && $Impugned_order_date!='' ) {
            $impugned_fnl_odr = date("d-m-Y", strtotime($Pdf_Gen_Data[0]['impugned_order_date']));
            $impugned_interim_odr = '';

        } elseif ($Judgment_type == 'I' && $Impugned_order_date!='' ) {
            $impugned_interim_odr = date("d-m-Y", strtotime($Pdf_Gen_Data[0]['impugned_order_date']));
            $impugned_fnl_odr = '';
        } else {
            $impugned_fnl_odr = '';
            $impugned_interim_odr = '';
        }


        $Judges_passed_order=$Pdf_Gen_Data[0]['judges_passed_order'];
        //$Tribunal_authority=$Pdf_Gen_Data[0]['tribunal_authority'];
        $Tribunal_authority=$Pdf_Gen_Data[0]['tribunal_state_name'];
        $Not_to_be_listed=$Pdf_Gen_Data[0]['not_to_be_listed'];
        $Disposed_matter_with_citation=$Pdf_Gen_Data[0]['disposed_matter_with_citation'];
        if($Disposed_matter_with_citation=='N'){
            $Disposed_mttr='NA';
        }else{
            $Disposed_mttr= $Disposed_matter_with_citation;
        }

        $Pending_matter_with_case=$Pdf_Gen_Data[0]['pending_matter_with_case'];
        if($Pending_matter_with_case=='N'){
            $Pending_mttr='NA';
        }else{
            $Pending_mttr=$Pending_matter_with_case;
        }
        $Sentence_awarded=$Pdf_Gen_Data[0]['sentence_awarded'];
        $Period_sentence_undergone=$Pdf_Gen_Data[0]['period_sentence_undergone'];
        $Nature_mttr= $Pdf_Gen_Data[0]['nature_of_mttr'];
        if($Nature_mttr=='C'){
            $Criminal_mtrr ='CIVIL';
        }else{
            $Criminal_mtrr ='CRIMINAL';
        }
        $Surrendered_data =$Pdf_Gen_Data[0]['surrendered'];
        $Land_acquisition=$Pdf_Gen_Data[0]['land_acquisition_mattr'];
        if($Land_acquisition=='N'){
            $Land_acquisition_mttr='NA';
        }else{
            $Land_acquisition_mttr='';
        }
        $Date_section4_notification=$Pdf_Gen_Data[0]['date_section4_notification'];
        if($Date_section4_notification=='N'){
            $Date_sec4_notification='NA';
        }else{
            $Date_sec4_notification=$Pdf_Gen_Data[0]['sec4_notification_dt'];
        }
        $Date_section6_notification=$Pdf_Gen_Data[0]['date_section6_notification'];
        if($Date_section6_notification=='N'){
            $Date_sec6_notification='NA';
        }else{
            $Date_sec6_notification=$Pdf_Gen_Data[0]['sec6_notification_dt'];
        }
        $Date_section17_notification=$Pdf_Gen_Data[0]['date_section17_notification'];
        if($Date_section17_notification=='N'){
            $Date_sec17_notification='NA';
        }else{
            $Date_sec17_notification=$Pdf_Gen_Data[0]['sec17_notification_dt'];
        }
        $Tax_effect=$Pdf_Gen_Data[0]['tax_mttr'];
        if($Tax_effect=='N'){
            $Tax_Mttr='NA';
        }else{
            $Tax_Mttr='';
        }
        $First_Petitioner=$Pdf_Gen_Data[0]['first_petitioner_appellant'];
        if($First_Petitioner=='N'){
            $First_petitioner_appellant='NA';
        }else{
            $First_petitioner_appellant='';
        }
        $Senior_65=$Pdf_Gen_Data[0]['senior_citizen'];
        if($Senior_65=='Y'){
            $Senior_citizen='YES';
        }else{
            $Senior_citizen='';
        }
        $Sc_st=$Pdf_Gen_Data[0]['sc_st'];
        if($Sc_st=='Y'){
            $Sc_st_Data='YES';
        }else{
            $Sc_st_Data='';
        }
        $Women_child=$Pdf_Gen_Data[0]['women_child'];
        if($Women_child=='Y'){
            $Women_child_Data='YES';
        }else{
            $Women_child_Data='';
        }

        $Disable=$Pdf_Gen_Data[0]['disable'];
        if($Disable=='Y'){
            $Disable_Data='YES';
        }else{
            $Disable_Data='';
        }
        //$Act_section=$Pdf_Gen_Data[0]['act_section'];

        $Legal_aid_case=$Pdf_Gen_Data[0]['legal_aid_case'];
        if($Legal_aid_case=='Y'){
            $Legal_aid_case_Data='YES';
        }else{
            $Legal_aid_case_Data='';
        }

        $In_custody=$Pdf_Gen_Data[0]['in_custody'];
        if($In_custody=='Y'){
            $In_custody_Data='YES';
        }else{
            $In_custody_Data='';
        }
        $Vehicle_number=$Pdf_Gen_Data[0]['vehicle_number'];


        /*$efiling_num='ECSCIN01001562020';
        $est_code='aa11110';*/
        $efiling_num = $_SESSION['efiling_details']['efiling_no'];
        $est_code = $_SESSION['estab_details']['estab_code'];

        ob_start();
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

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
        $html_headr ='<h4 style="text-align: center">LISTING PROFORMA</h4> </br> <h5 style="text-align: right">SEC : XII</h5>';
        $html1 = '
        <table border="0.5" style="width:100% ; font-size: 12px "  ><tr>
            <td style="width: 7%">  </td>
            <td style="width: 45%"><b> The Case pertains to (please tick/check the correct box):-</b></td>
            <td style="width: 45%">  </td></tr>
        <tbody>';

        $html2 = '<tr style="padding: 5px "><td style="width: 7%"> (a) </td>
                       <td style="width: 45%"> Central Act: (Title)</td>
                       <td style="width: 45%">' . htmlentities($central_act_name, ENT_QUOTES) . '</td>
                  </tr>';

        $html3 = '<tr style="padding: 15px "><td style="width: 7%"> (b) </td>
                       <td style="width: 45% height: 100px"> Section </td>
                       <td style="width: 45%">' . htmlentities($act_section_cntrl, ENT_QUOTES) . '</td>
                  </tr>';

        $html4 = '<tr><td style="width: 7%"> (c) </td>
                       <td style="width: 45%"> Central Rule : (Title) </td>
                       <td style="width: 45%">' . htmlentities($central_rule_title, ENT_QUOTES) . '</td>
                  </tr>';

        $html5 = '<tr><td style="width: 7%"> (d) </td>
                       <td style="width: 45%"> Rule No’s </td>
                       <td style="width: 45%">' . htmlentities($Cntrl_rule_nos, ENT_QUOTES) . '</td>
                  </tr>';

        $html6 = '<tr><td style="width: 7%"> (e) </td>
                       <td style="width: 45%"> State Act : (Title) </td>
                       <td style="width: 45%">' . htmlentities($state_act_name, ENT_QUOTES) . '</td>
                  </tr>';

        $html7 = '<tr><td style="width: 7%"> (f) </td>
                       <td style="width: 45%"> Section </td>
                       <td style="width: 45%">' . htmlentities($act_section_state, ENT_QUOTES) . '</td>
                  </tr>';

        $html8 = '<tr><td style="width: 7%"> (g) </td>
                       <td style="width: 45%"> State Rule : (Title) </td>
                       <td style="width: 45%">' . htmlentities($state_rule_title, ENT_QUOTES) . '</td>
                  </tr>';

        $html9 = '<tr><td style="width: 7%"> (h) </td>
                       <td style="width: 45%"> Rule No’s  </td>
                       <td style="width: 45%">' . htmlentities($St_rule_nos, ENT_QUOTES) . '</td>
                  </tr>';

        $html10 = '<tr><td style="width: 7%"> (i) </td>
                       <td style="width: 45%"> Impugned Interin Order (Date) </td>
                       <td style="width: 45%">' . htmlentities($impugned_interim_odr, ENT_QUOTES) . '</td>
                  </tr>';


        $html11 = '<tr><td style="width: 7%"> (j) </td>
                       <td style="width: 45%"> Impugned Final Order / Decree (Date) </td>
                       <td style="width: 45%">' . htmlentities($impugned_fnl_odr, ENT_QUOTES) . '</td>
                  </tr>';

        $html12 = '<tr><td style="width: 7%"> (k) </td>
                       <td style="width: 45%"> High Court: (Name) </td>
                       <td style="width: 45%">' . htmlentities($High_court_name, ENT_QUOTES) . '</td>
                  </tr>';

        $html13 = '<tr><td style="width: 7%"> (l) </td>
                       <td style="width: 45%"> Name of Judges who passed the order </td>
                       <td style="width: 45%">' . htmlentities($Judges_passed_order, ENT_QUOTES) . '</td>
                  </tr>';

        $html14 = '<tr><td style="width: 7%"> (m) </td>
                       <td style="width: 45%"> Tribunal/Authority (Name) </td>
                       <td style="width: 45%">' . htmlentities($Tribunal_authority, ENT_QUOTES) . '</td>
                  </tr>';

        $html15 = '<tr><td style="width: 7%"> 1. </td>
                       <td style="width: 45%"> Nature of Matter (Civil/Criminal) </td>
                       <td style="width: 45%">' . htmlentities($Criminal_mtrr, ENT_QUOTES) . '</td>
                  </tr>';

        $html16 = '<tr>
                        <td style="width: 7%">2.(a)</td>
                       <td style="width: 45%"> Petitioner(s) Name  </td>
                       <td style="width: 45%">' . htmlentities($Pet_party_name, ENT_QUOTES) . '</td>
                  </tr>
                  <tr>
                       <td style="width: 7%">(b)</td>
                       <td style="width: 45%"> E-Mail Id:  </td>
                       <td style="width: 45%">' . htmlentities($Pet_email, ENT_QUOTES) . '</td>
                  </tr>
                  <tr>
                       <td style="width: 7%">(c)</td>
                       <td style="width: 45%">  Mobile Phone Number  </td>
                       <td style="width: 45%">' . htmlentities($Pet_mobile, ENT_QUOTES) . '</td>
                  </tr>';

        $html17 = '<tr>
                       <td style="width: 7%">3.(a)</td>
                       <td style="width: 45%"> Respondent(s) Name  </td>
                       <td style="width: 45%">' . htmlentities($Res_party_name, ENT_QUOTES) . '</td>
                   </tr>
                   <tr><td style="width: 7%">(b)</td>
                       <td style="width: 45%"> E-Mail Id:  </td>
                       <td style="width: 45%">' . htmlentities($Res_email, ENT_QUOTES) . '</td>
                   </tr>
                       <tr><td style="width: 7%">(c)</td>
                       <td style="width: 45%">  Mobile Phone Number  </td>
                       <td style="width: 45%">' . htmlentities($Res_mobile, ENT_QUOTES) . '</td>
                   </tr>';

        $html18 = '<tr><td style="width: 7%"> 4.(a) </td>
                       <td style="width: 45%"> Main Category Classification </td>
                       <td style="width: 45%">' . htmlentities($Main_category, ENT_QUOTES) . '</td>
                  </tr>';

        $html19 = '<tr><td style="width: 7%"> (b) </td>
                       <td style="width: 45%"> Sub-Category Classification </td>
                       <td style="width: 45%">' . htmlentities($Sub_category, ENT_QUOTES) . '</td>
                  </tr>';

        $html20 = '<tr><td style="width: 7%"> 5. </td>
                       <td style="width: 45%"> Not to be Listed Before </td>
                       <td style="width: 45%">' . htmlentities($Not_to_be_listed, ENT_QUOTES) . '</td>
                  </tr>';

        $html21 = '<tr><td style="width: 7%"> 6.(a) </td>
                       <td style="width: 45%"> Similar Disposed of Matter with Citation, if any, & Case details </td>
                       <td style="width: 45%"> ' . htmlentities($Disposed_mttr, ENT_QUOTES) . '</td>
                  </tr>';

        $html22 = '<tr><td style="width: 7%">(b) </td>
                       <td style="width: 45%"> Similar Pending Matter with Case details </td>
                       <td style="width: 45%"> ' . htmlentities($Pending_mttr, ENT_QUOTES) . '</td>
                  </tr>';

        $html23 = '<tr><td style="width: 7%"> 7. </td>
                       <td style="width: 45%"> Criminal Matters </td>
                       <td style="width: 45%"> ' . htmlentities($Criminal_mtrr, ENT_QUOTES) . '</td>
                  </tr>';

        $html24 = '<tr><td style="width: 7%"> (a) </td>
                       <td style="width: 45%"> Whether Accused/Convict has Surrendered </td>
                       <td style="width: 45%"> ' . htmlentities($Surrendered_data, ENT_QUOTES) . '</td>
                  </tr>';

        $html25 = '<tr><td style="width: 7%"> (b) </td>
                       <td style="width: 45%"> FIR No./Complaint No. And Date </td>
                       <td style="width: 45%">' . htmlentities($Fir_no, ENT_QUOTES) . '</td>
                  </tr>';

        $html26 = '<tr><td style="width: 7%"> (c) </td>
                       <td style="width: 45%"> Police Station </td>
                       <td style="width: 45%">' . htmlentities($Police_station_name, ENT_QUOTES) . '</td>
                  </tr>';

        $html27 = '<tr><td style="width: 7%"> (d) </td>
                       <td style="width: 45%"> Sentence Awarded  </td>
                       <td style="width: 45%">' . htmlentities($Sentence_awarded, ENT_QUOTES) . '</td>
                  </tr>';

        $html28 = '<tr><td style="width: 7%"> (e) </td>
                       <td style="width: 45%"> Period of Sentence Undergone including period of detention/custody undergone </td>
                       <td style="width: 45%">' . htmlentities($Period_sentence_undergone, ENT_QUOTES) . '</td>
                  </tr>';

        $html29 = '<tr><td style="width: 7%"> 8 </td>
                       <td style="width: 45%"> Land acquisition Matters </td>
                       <td style="width: 45%">' . htmlentities($Land_acquisition_mttr, ENT_QUOTES) . '</td>
                  </tr>';

        $html30 = '<tr><td style="width: 7%"> (a) </td>
                       <td style="width: 45%"> Date of Section 4 notification </td>
                       <td style="width: 45%">' . htmlentities($Date_sec4_notification, ENT_QUOTES) . '</td>
                  </tr>';
        $html31 = '<tr><td style="width: 7%"> (b) </td>
                       <td style="width: 45%"> Date of Section 6 notification </td>
                       <td style="width: 45%">' . htmlentities($Date_sec6_notification, ENT_QUOTES) . '</td>
                  </tr>';
        $html32 = '<tr><td style="width: 7%"> (c) </td>
                       <td style="width: 45%"> Date of Section 17 notification </td>
                       <td style="width: 45%">' . htmlentities($Date_sec17_notification, ENT_QUOTES) . '</td>
                  </tr>';
        $html33 = '<tr><td style="width: 7%"> 9 </td>
                       <td style="width: 45%"> Tax Matters : State the tax effect </td>
                       <td style="width: 45%">' . htmlentities($Tax_Mttr, ENT_QUOTES) . '</td>
                  </tr>';
        $html34 = '<tr><td style="width: 7%"> 10 </td>
                       <td style="width: 45%"> Special Category: (first petitioner/Appellant only) </td>
                       <td style="width: 45%">' . htmlentities($First_petitioner_appellant, ENT_QUOTES) . '</td>
                  </tr>';
        $html35 = '<tr><td style="width: 7%"> (a) </td>
                       <td style="width: 45%"> Senior Citizen > 65 Years </td>
                       <td style="width: 45%">' . htmlentities($Senior_citizen, ENT_QUOTES) . '</td>
                  </tr>';

        $html36 = '<tr><td style="width: 7%"> (b) </td>
                       <td style="width: 45%"> SC/ST </td>
                       <td style="width: 45%">' . htmlentities($Sc_st_Data, ENT_QUOTES) . '</td>
                  </tr>
                  <tr><td style="width: 7%"> (c) </td>
                       <td style="width: 45%"> Woman/Child </td>
                       <td style="width: 45%">' . htmlentities($Women_child_Data, ENT_QUOTES) . '</td>
                  </tr>
                  <tr><td style="width: 7%"> (d) </td>
                       <td style="width: 45%"> Disable </td>
                       <td style="width: 45%">' . htmlentities($Disable_Data, ENT_QUOTES) . '</td>
                  </tr>
                  <tr><td style="width: 7%"> (e) </td>
                       <td style="width: 45%"> Legal Aid case </td>
                       <td style="width: 45%">' . htmlentities($Legal_aid_case_Data, ENT_QUOTES) . '</td>
                  </tr>
                  <tr><td style="width: 7%"> (f) </td>
                       <td style="width: 45%"> In custody </td>
                       <td style="width: 45%">' . htmlentities($In_custody_Data, ENT_QUOTES) . '</td>
                  </tr>';
        $html37 = '<tr><td style="width: 7%"> 11 </td>
                       <td style="width: 45%"> Vehicle number: (in case of motor accident claim matter only) </td>
                       <td style="width: 45%">' . htmlentities($Vehicle_number, ENT_QUOTES) . '</td>
                  </tr>';


        $up_doc_hash4 .= '</tbody></table><br><br><br><br><br>';


        $html38 = '<table  style="width:100% ; font-size: 12px "  ><tr>
                    <td style="width: 7%"></td>
                    <td style="width: 40%"><b> Filed on :</b></td>
                    <td style="width: 10%"></td>
                    <td style="width: 45% ; text-align: justify-all ; "><b>' . htmlentities($Pet_party_name, ENT_QUOTES) . '
                    <br>Name of the Petitioner<br>( Petitioner -in- person)<br> Mobile : ' . htmlentities($Pet_mobile, ENT_QUOTES) . '
                    <br>  E-mail: ' . htmlentities($Pet_email, ENT_QUOTES) . '</b></td></tr>
                <tbody></tbody></table>';


        $html = $html_headr . $html1 . $html2 . $html3 . $html4 . $html5 . $html6 . $html7 .
            $html8 . $html9 . $html10 . $html11 . $html12 . $html13 . $html14 . $html15 . $html16 .
            $html17 . $html18 . $html19 . $html20 . $html21 . $html22 . $html23 . $html24 .
            $html25 . $html26 . $html27 . $html28 . $html29 . $html30 . $html31 . $html32 . $html33 .
            $html34 . $html35 . $html36 . $html37 . $up_doc_hash4 . $html38  ;

        // output the HTML content
        $pdf->writeHTML($html, true, false, true, false, '');


        if($Pdf_Gen_Data[0]['is_signed']=='t'){
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
        }

        $unsigned_pdf_path = 'uploaded_docs/' . $est_code . '/' . $efiling_num . '/unsigned_pdf/';
        if (!is_dir($unsigned_pdf_path)) {
            $uold = umask(0);
            if (mkdir('uploaded_docs/' . $est_code . '/' . $efiling_num . '/unsigned_pdf/', 0777, true)) {
                $html = '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>';
                write_file($unsigned_pdf_path . '/index.html', $html);
            }
            umask($uold);
        }

        $pdf->Output($unsigned_pdf_path . $efiling_num . '_ListingProforma_gen.pdf', 'FI');

    }

    function gen_listingProforma_editPdf()
    {

        $reg_store_pdf_id = $_SESSION['efiling_details']['registration_id'];

        /*$Pdf_Gen_Data = $this->Listing_proforma_model->get_pdf_store_data($reg_store_pdf_id);
        $Pdf_Gen_actData = $this->Listing_proforma_model->get_act_list_pdf($reg_store_pdf_id);*/

        $data['pdf_listing_proforma_data'] = $this->Listing_proforma_model->get_pdf_store_data($reg_store_pdf_id);

        $data['pdf_actsection_data'] = $this->Listing_proforma_model->get_act_list_pdf($reg_store_pdf_id);

        $data['edit_data']= 'TRUE';

        $this->load->view('templates/header');
        $this->load->view('supplements/listing_proforma_form_view', $data);
        $this->load->view('templates/footer');


    }//End of function gen_listingProforma_editPdf()..


    function doc_cancel_listingProforma(){

        //echo "Mishra";exit();
        $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK);

        if (!in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
            redirect('adminDashboard');
            exit(0);
        }


        $registrationID = $_SESSION['efiling_details']['registration_id'];
        $curr_dt_time = date('Y-m-d H:i:s');
        $Cancel_data_array = array(
            'created_by' => $_SESSION['login']['id'],
            'created_on' => $curr_dt_time,
            'is_active' => '0'

        );
        $pdf_listing_proforma_data_cancel = $this->Listing_proforma_model->pdf_data_cancel_listingproforma($registrationID,$Cancel_data_array);

        if($pdf_listing_proforma_data_cancel==TRUE){
            $this->session->set_flashdata('msg','PDF Cancel Successfully');
            echo '<script type="text/javascript"> window.top.location.href = "'.base_url().'case/ancillary/documents" </script>';

        }else{
            $this->session->set_flashdata('msg_error','PDF  Not Cancel Successfully!');
            echo '<script type="text/javascript"> window.top.location.href = "'.base_url().'case/ancillary/documents" </script>';

        }

    }//END of doc_cancel_checklist()..




}
