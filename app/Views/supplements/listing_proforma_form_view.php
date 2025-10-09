<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 30/5/21
 * Time: 7:04 PM
 */
?>

<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div id="msg">
                <?php
                if (isset($_SESSION['MSG']) && !empty($_SESSION['MSG'])) {
                    echo $_SESSION['MSG'];
                } unset($_SESSION['MSG']);
                ?></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <?php //if ($_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN) { ?>
                <div class="x_title">
                    <h2><i class="fa  fa-newspaper-o"></i> LISTING PROFORMA FORM</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <?php

                    $action = 'supplements/listing_proforma_controller/add_pdf_data_insrt';

                    $attribute = array('class' => 'form-horizontal form-label-left', 'id' => 'add_profor_data', 'name' => 'add_profor_data', 'autocomplete' => 'off', 'enctype' => 'multipart/form-data');
                    echo form_open($action, $attribute);

                    $central_act_name = '';
                    $state_act_name = '';
                    $act_section_cntrl = '';
                    $act_section_state = '';
                    $central_rule_title = '';
                    $state_rule_title = '';
                    $central_rule_nos = '';
                    $state_rule_nos = '';

                    //var_dump($pdf_actsection_data);exit();
                    foreach ($pdf_actsection_data as $val_act) {
                        $act_state_id = $val_act['state_id'];
                        $act_actno = $val_act['actno'];

                        if ($act_state_id == 0 && $act_actno !=0) {
                            $central_act_name .= $val_act['act_name'] . ',';
                            $act_section_cntrl .= $val_act['act_section'] . ',';
                        } elseif($act_state_id != 0 && $act_actno !=0) {
                            $state_act_name .= $val_act['act_name'] . ',';
                            $act_section_state .= $val_act['act_section'] . ',';
                        }
                        if ($act_state_id == 0 && $act_actno == 0) {
                            $central_rule_title .= $val_act['act_name'] . ',';
                            $central_rule_nos .= $val_act['act_section'] . ',';

                        } elseif($act_state_id != 0 && $act_actno == 0) {
                            $state_rule_title .= $val_act['act_name'] . ',';
                            $state_rule_nos .= $val_act['act_section'] . ',';
                        }
                    }//end of foreachloop()..

                    if ($pdf_listing_proforma_data[0]['judgment_type'] == 'F') {
                        $impugned_fnl_odr = date("d-m-Y", strtotime($pdf_listing_proforma_data[0]['impugned_order_date']));
                        $impugned_interim_odr = '';

                    } elseif ($pdf_listing_proforma_data[0]['judgment_type'] == 'I') {
                        $impugned_interim_odr = date("d-m-Y", strtotime($pdf_listing_proforma_data[0]['impugned_order_date']));
                        $impugned_fnl_odr = '';
                    } else {
                        $impugned_fnl_odr = '';
                        $impugned_interim_odr = '';
                    }


                    //xxxxxxxxxxxxx
                    $Judges_passed_order = $pdf_listing_proforma_data[0]['judges_passed_order'];
                    $Tribunal_authority = $pdf_listing_proforma_data[0]['tribunal_state_name'];
                    // $Not_to_be_listed = $pdf_listing_proforma_data[0]['not_to_be_listed'];
                    $Disposed_matter_with_citation = $pdf_listing_proforma_data[0]['disposed_matter_with_citation'];
                    if ($Disposed_matter_with_citation == 'N') {
                        $Disposed_mttr = 'checked';
                        $Disposed_mttr_val='';
                    } else {
                        $Disposed_mttr_val = $Disposed_matter_with_citation;
                        $Disposed_mttr='';
                    }

                    $Pending_matter_with_case = $pdf_listing_proforma_data[0]['pending_matter_with_case'];
                    if ($Pending_matter_with_case == 'N') {
                        $Pending_mttr = 'checked';
                        $Pending_mttr_val ='';
                    } else {
                        $Pending_mttr_val = $Pending_matter_with_case;
                        $Pending_mttr = '';
                    }
                    $Sentence_awarded = $pdf_listing_proforma_data[0]['sentence_awarded'];
                    $Period_sentence_undergone = $pdf_listing_proforma_data[0]['period_sentence_undergone'];

                    $Date_section4_notification = $pdf_listing_proforma_data[0]['date_section4_notification'];
                    if ($Date_section4_notification == 'N') {
                        $Date_sec4_notification = 'checked';
                        $date_4='';
                    } elseif ($Date_section4_notification != 'N' && $pdf_listing_proforma_data[0]['sec4_notification_dt'] != '') {
                        $Date_sec4_notification = $pdf_listing_proforma_data[0]['sec4_notification_dt'];
                        $date_4= date("d-m-Y", strtotime($Date_sec4_notification));
                    } else {
                        $Date_sec4_notification = '';
                        $date_4='';
                    }
                    $Date_section6_notification = $pdf_listing_proforma_data[0]['date_section6_notification'];
                    if ($Date_section6_notification == 'N') {
                        $Date_sec6_notification = 'checked';
                        $date_6='';
                    } elseif($Date_section6_notification != 'N' && $pdf_listing_proforma_data[0]['sec6_notification_dt'] != '') {
                        $Date_sec6_notification = $pdf_listing_proforma_data[0]['sec6_notification_dt'];
                        $date_6= date("d-m-Y", strtotime($Date_sec6_notification));
                    }else {
                        $Date_sec6_notification = '';
                        $date_6='';
                    }
                    $Date_section17_notification = $pdf_listing_proforma_data[0]['date_section17_notification'];
                    if ($Date_section17_notification == 'N') {
                        $Date_sec17_notification = 'checked';
                        $date_17='';
                    } elseif($Date_section17_notification != 'N' && $pdf_listing_proforma_data[0]['sec17_notification_dt'] != '') {
                        $Date_sec17_notification = $pdf_listing_proforma_data[0]['sec17_notification_dt'];
                        $date_17= date("d-m-Y", strtotime($Date_sec17_notification));
                    }
                    $Vehicle_number = $pdf_listing_proforma_data[0]['vehicle_number'];

                    ?>
                    <div class="row">
                        <div class="col-sm-6 col-xs-12">
                            <input type="hidden" id="registrationID" name="registrationID" value="<?php echo $pdf_listing_proforma_data[0]['registration_id']?>" />
                            <div class="form-group">
                                <label class="control-label col-sm-5"> Central Act : (Title) <span style="color: red">*</span> :</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input id="cntrl_act_title" name="cntrl_act_title" value="<?php echo !empty($central_act_name) ? rtrim($central_act_name,',') : ''; ?>" placeholder="Central Act Title"   class="form-control" type="text" readonly>
                                        <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Title only accept Charaters and Spaces(eg. ABC XYZ).">
                                                <i class="fa fa-question-circle-o" ></i>
                                            </span>
                                    </div>
                                    <?php echo ($this->session->flashdata('cntrl_act_title') != NULL) ? $this->session->flashdata('cntrl_act_title') : NULL; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-5"> Central Rule : (Title) <span style="color: red">*</span> :</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input  id="cntrl_rule_title" name="cntrl_rule_title" value="<?php echo !empty($central_rule_title) ? rtrim($central_rule_title,',') : ''; ?>" placeholder="Central Rule Title"   class="form-control" type="text" readonly>
                                        <!-- <textarea class="form-control" rows="4" id="faq_question" name="faq_question"  placeholder="QUESTION" maxlength="500"><?php //echo htmlentities($get_data[0]['question'], ENT_QUOTES); ?></textarea> -->
                                        <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Title only accept Charaters and Spaces(eg. ABC XYZ).">
                                                <i class="fa fa-question-circle-o" ></i>
                                            </span>
                                    </div>
                                    <?php echo ($this->session->flashdata('cntrl_rule_title') != NULL) ? $this->session->flashdata('cntrl_rule_title') : NULL; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-5"> State Act : (Title) <span style="color: red">*</span> :</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input  id="state_act_title" name="state_act_title" value="<?php echo !empty($state_act_name) ? rtrim($state_act_name,',') : ''; ?>" placeholder="State Act Title"   class="form-control" type="text" readonly>
                                        <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Title only accept Charaters and Spaces(eg. ABC XYZ).">
                                                <i class="fa fa-question-circle-o" ></i>
                                            </span>
                                    </div>
                                    <?php echo ($this->session->flashdata('state_act_title') != NULL) ? $this->session->flashdata('state_act_title') : NULL; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-5"> State Rule : (Title) <span style="color: red">*</span> :</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input  id="state_rule_title" name="state_rule_title" value="<?php echo !empty($state_rule_title) ? rtrim($state_rule_title,',') : ''; ?>" placeholder="State Rule Title"   class="form-control" type="text" readonly>
                                        <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Title only accept Charaters and Spaces(eg. ABC XYZ).">
                                                <i class="fa fa-question-circle-o" ></i>
                                            </span>
                                    </div>
                                    <?php echo ($this->session->flashdata('state_rule_title') != NULL) ? $this->session->flashdata('state_rule_title') : NULL; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-5"> Impugned Interin Order (Date) <span style="color: red">*</span> :</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input  id="impugned_interin_order" name="impugned_interin_order" value="<?php echo htmlentities($impugned_interim_odr, ENT_QUOTES); ?>" placeholder="DD/MM/YYYY"   class="form-control" type="text" readonly>
                                        <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Select A date form Impugned Interin Order (Date) .">
                                                <i class="fa fa-question-circle-o" ></i>
                                            </span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-5"> High Court: (Name)  <span style="color: red">*</span> :</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input  id="high_court_name" name="high_court_name" value="<?php echo htmlentities($pdf_listing_proforma_data[0]['high_court_name'], ENT_QUOTES); ?>" placeholder="High Court (Name)"   class="form-control" type="text" readonly>
                                        <!-- <textarea class="form-control" rows="4" id="faq_question" name="faq_question"  placeholder="QUESTION" maxlength="500"><?php //echo htmlentities($get_data[0]['question'], ENT_QUOTES); ?></textarea> -->
                                        <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Title only accept Charaters and Spaces(eg. ABC XYZ).">
                                                <i class="fa fa-question-circle-o" ></i>
                                            </span>
                                    </div>
                                    <?php echo ($this->session->flashdata('high_court_name') != NULL) ? $this->session->flashdata('high_court_name') : NULL; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-5"> Tribunal/Authority (Name) <span style="color: red">*</span> :</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input tabindex = '2' id="tribunal_authority" name="tribunal_authority" value="<?php echo htmlentities($Tribunal_authority, ENT_QUOTES); ?>" placeholder="Tribunal/Authority (Name)"   class="form-control" type="text" readonly>
                                        <!-- <textarea class="form-control" rows="4" id="faq_question" name="faq_question"  placeholder="QUESTION" maxlength="500"><?php //echo htmlentities($get_data[0]['question'], ENT_QUOTES); ?></textarea> -->
                                        <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Title only accept Charaters and Spaces(eg. ABC XYZ).">
                                                <i class="fa fa-question-circle-o" ></i>
                                            </span>
                                    </div>
                                    <?php echo ($this->session->flashdata('tribunal_authority') != NULL) ? $this->session->flashdata('tribunal_authority') : NULL; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-5"> Petitioner(s) Name <span style="color: red">*</span> :</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input  id="petitioner_nm" name="petitioner_nm" value="<?php echo htmlentities($pdf_listing_proforma_data[0]['pet_party_name'], ENT_QUOTES); ?>" placeholder="Petitioner(s) Name"   class="form-control" type="text" readonly>
                                        <!-- <textarea class="form-control" rows="4" id="faq_question" name="faq_question"  placeholder="QUESTION" maxlength="500"><?php //echo htmlentities($get_data[0]['question'], ENT_QUOTES); ?></textarea> -->
                                        <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Title only accept Charaters and Spaces(eg. ABC XYZ).">
                                                <i class="fa fa-question-circle-o" ></i>
                                            </span>
                                    </div>
                                    <?php echo ($this->session->flashdata('petitioner_nm') != NULL) ? $this->session->flashdata('petitioner_nm') : NULL; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Petitioner(s) Mobile Phone Number
                                    <span style="color: red">*</span>:</label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <input  class="form-control " id="pet_mobile_no" name="pet_mobile_no" value="<?php echo htmlentities($pdf_listing_proforma_data[0]['pet_mobile'], ENT_QUOTES); ?>" placeholder="Mobile Phone Number
                                                "  type="text" readonly>
                                        <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                        <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Please Enter Mobile Phone Number.">
                                                <i class="fa fa-question-circle-o"></i>
                                            </span>
                                    </div>
                                    <?php echo ($this->session->flashdata('pet_mobile_no') != NULL) ? $this->session->flashdata('pet_mobile_no') : NULL; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Respondent(s) E-Mail Id  :</label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <input  class="form-control " id="res_email" name="res_email" value="<?php echo htmlentities($pdf_listing_proforma_data[0]['res_email'], ENT_QUOTES); ?>"  placeholder="EMAIL ID"  type="text" readonly>
                                        <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                        <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Please Enter Valid Email Id.">
                                                <i class="fa fa-question-circle-o"></i>
                                            </span>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="control-label col-sm-5"> Main Category Classification <span style="color: red">*</span> :</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input  id="main_category_classification" name="main_category_classification" value="<?php echo htmlentities($pdf_listing_proforma_data[0]['main_category'], ENT_QUOTES); ?>" placeholder="Main Category Classification"  class="form-control" type="text" readonly>
                                        <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Title only accept Charaters and Spaces(eg. ABC XYZ).">
                                                <i class="fa fa-question-circle-o" ></i>
                                            </span>
                                    </div>
                                    <?php echo ($this->session->flashdata('main_category_classification') != NULL) ? $this->session->flashdata('main_category_classification') : NULL; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-5 ">Not to be Listed Before :</label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <input tabindex = '3' class="form-control " id="not_to_be_listed" name="not_to_be_listed" value="<?php echo htmlentities($pdf_listing_proforma_data[0]['not_to_be_listed'], ENT_QUOTES); ?>"  placeholder="Not to be Listed Before"  type="text">
                                        <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                        <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Title only accept Charaters and Spaces(eg. ABC XYZ). .">
                                                <i class="fa fa-question-circle-o"></i>
                                            </span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-5"> Similar Pending Matter with Case details <span style="color: red">*</span> :</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input tabindex = '6' class="form-control " id="matter_with_case_dtl_pending" name="matter_with_case_dtl_pending" value="<?php echo htmlentities($Pending_mttr_val, ENT_QUOTES); ?>"  placeholder="Pending Matter"  type="text">
                                        <input tabindex = '7' type="checkbox" id="matter_with_case_dtl" name="matter_with_case_dtl" value="N" <?= $Pending_mttr;?> onclick="divhidepending()">&nbsp;&nbsp;
                                        <label for="citation1"> NA</label>

                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Whether Accused/Convict has Surrendered :</label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <input tabindex = '6' type="radio" id="surrendered_y" name="surrendered" value="Y" <?php if($pdf_listing_proforma_data[0]['surrendered']=='Y') echo 'checked';?>>&nbsp;
                                        <label for="surrender">Yes</label>&nbsp;
                                        <input tabindex = '7' type="radio" id="surrendered_n" name="surrendered" value="N" <?php if($pdf_listing_proforma_data[0]['surrendered']=='N') echo 'checked';?>>&nbsp;
                                        <label for="surrender">No</label>&nbsp;
                                        <input tabindex = '8' type="radio" id="surrendered_o" name="surrendered" value="O" <?php if($pdf_listing_proforma_data[0]['surrendered']=='O') echo 'checked';?>>&nbsp;
                                        <label for="surrender">NA</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Police Station :</label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <input  class="form-control " id="police_station" name="police_station" value="<?php echo htmlentities($pdf_listing_proforma_data[0]['police_station_name'], ENT_QUOTES); ?>"  placeholder="Police Station"  type="text" readonly>
                                        <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                        <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Title only accept Charaters and Spaces(eg. ABC XYZ). .">
                                                <i class="fa fa-question-circle-o"></i>
                                            </span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Period of Sentence Undergone including period of detention/custody undergone. :</label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <input tabindex = '10' class="form-control " id="period_sentence_undergone" name="period_sentence_undergone" value="<?php echo htmlentities($Period_sentence_undergone, ENT_QUOTES); ?>"  placeholder="Custody Undergone"  type="text">
                                        <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                        <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Title only accept Charaters and Spaces(eg. ABC XYZ). .">
                                                <i class="fa fa-question-circle-o"></i>
                                            </span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-5"> Date of Section 4 notification <span style="color: red">*</span> :</label>
                                <div class="col-sm-7">
                                    <div class="input-group">

                                        <input tabindex = '12' id="date_section4_notification_date" name="date_section4_notification_date" value="<?php echo ($Date_sec4_notification != 'checked') ? $date_4 : ''; ?>" maxlength="10" readonly="" placeholder="DD-MM-YYYY"   class="form-control" type="text">
                                        <input tabindex = '13' type="checkbox" id="date_section4_notification" name="date_section4_notification" value="N" <?= $Date_sec4_notification;?> onclick="divhidedt_4()">&nbsp;&nbsp;
                                        <label for="citation1"> NA</label>

                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-5"> Date of Section 17 notification <span style="color: red">*</span> :</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input tabindex = '16' id="date_section17_notification_date" name="date_section17_notification_date" value="<?php echo ($Date_sec17_notification != 'checked') ? $date_17 : ''; ?>" maxlength="10" readonly="" placeholder="DD-MM-YYYY"   class="form-control" type="text">
                                        <input tabindex = '17' type="checkbox" id="date_section17_notification" name="date_section17_notification" value="N" <?= $Date_sec17_notification;?> onclick="divhidedt_17()">&nbsp;&nbsp;
                                        <label for="citation1"> NA</label>

                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-5"> Special Category: (first petitioner/Appellant only) <span style="color: red">*</span> :</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input tabindex = '19' type="checkbox" id="first_petitioner_appellant" name="first_petitioner_appellant" value="N" <?php if($pdf_listing_proforma_data[0]['first_petitioner_appellant']=='N') echo 'checked';?>>&nbsp;&nbsp;
                                        <label for="citation1"> NA</label>

                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-5">  SC/ST <input tabindex = '21'type="checkbox" id="sc_st" name="sc_st" value="Y" <?php if($pdf_listing_proforma_data[0]['sc_st']=='Y') echo 'checked';?>></label>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-5">   Disable <input tabindex = '23' type="checkbox" id="disable" name="disable" value="Y" <?php if($pdf_listing_proforma_data[0]['disable']=='Y') echo 'checked';?>></label>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-5">   In custody <input tabindex = '25' type="checkbox" id="in_custody" name="in_custody" value="Y" <?php if($pdf_listing_proforma_data[0]['in_custody']=='Y') echo 'checked';?>></label>
                            </div>

                        </div><!-- END OF DIV class="col-sm-6 col-xs-12" -->

                        <div class="col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Section  <span style="color: red">*</span>:</label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <input  class="form-control " id="cntrl_act_section" name="cntrl_act_section" value="<?php echo !empty($act_section_cntrl) ? rtrim($act_section_cntrl,',') : ''; ?>" placeholder="SECTION"  type="text" readonly>
                                        <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                        <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Please Enter SECTION.">
                                                <i class="fa fa-question-circle-o"></i>
                                            </span>
                                    </div>
                                    <?php echo ($this->session->flashdata('cntrl_act_section') != NULL) ? $this->session->flashdata('cntrl_act_section') : NULL; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Rule No’s  <span style="color: red">*</span>:</label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <input class="form-control " id="cntrl_rule_no" name="cntrl_rule_no" value="<?php echo !empty($central_rule_nos) ? rtrim($central_rule_nos,',') : ''; ?>" placeholder="Rule No's"  type="text" readonly>
                                        <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                        <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Please Enter Rule No's.">
                                                <i class="fa fa-question-circle-o"></i>
                                            </span>
                                    </div>
                                    <?php echo ($this->session->flashdata('cntrl_rule_no') != NULL) ? $this->session->flashdata('cntrl_rule_no') : NULL; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Section  <span style="color: red">*</span>:</label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <input  class="form-control " id="state_act_section" name="state_act_section" value="<?php echo !empty($act_section_state) ? rtrim($act_section_state,',') : ''; ?>" placeholder="SECTION"  type="text" readonly>
                                        <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                        <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Please Enter SECTION.">
                                                <i class="fa fa-question-circle-o"></i>
                                            </span>
                                    </div>
                                    <?php echo ($this->session->flashdata('state_act_section') != NULL) ? $this->session->flashdata('state_act_section') : NULL; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Rule No’s  <span style="color: red">*</span>:</label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <input  class="form-control " id="state_rule_no" name="state_rule_no" value="<?php echo !empty($state_rule_nos) ? rtrim($state_rule_nos, ',') : ''; ?>" placeholder="Rule No's"  type="text" readonly>
                                        <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                        <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Please Enter Rule No's.">
                                                <i class="fa fa-question-circle-o"></i>
                                            </span>
                                    </div>
                                    <?php echo ($this->session->flashdata('state_rule_no') != NULL) ? $this->session->flashdata('state_rule_no') : NULL; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Impugned Final Order / Decree (Date)   <span style="color: red">*</span>:</label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <input  id="impugned_final_order" name="impugned_final_order" value="<?php echo htmlentities($impugned_fnl_odr, ENT_QUOTES); ?>" placeholder="DD/MM/YYYY"   class="form-control" type="text" readonly>
                                        <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Please Enter SECTION.">
                                                <i class="fa fa-question-circle-o"></i>
                                            </span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Name of Judges who passed the order   <span style="color: red">*</span>:</label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <input tabindex = '1' class="form-control " id="name_of_judges_passed" name="name_of_judges_passed" value="<?php echo htmlentities($Judges_passed_order, ENT_QUOTES); ?>" placeholder="Name of Judges who passed the order"  type="text">
                                        <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                        <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Title only accept Charaters and Spaces(eg. ABC XYZ).">
                                                <i class="fa fa-question-circle-o"></i>
                                            </span>
                                    </div>
                                    <?php echo ($this->session->flashdata('name_of_judges_passed') != NULL) ? $this->session->flashdata('name_of_judges_passed') : NULL; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Nature of Matter (Civil/Criminal)   <span style="color: red">*</span>:</label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <?php if($pdf_listing_proforma_data[0]['nature_of_mttr']=='C'){  ?>
                                            <input  type="radio" id="nature_mttr_c" name="Nature_mttr" value="C" checked>&nbsp;
                                            <label for="criminals">CIVIL</label>&nbsp;
                                            <input  type="radio" id="nature_mttr_R" name="Nature_mttr" value="R" disabled>&nbsp;
                                            <label for="criminals">CRIMINAL</label>&nbsp;
                                        <?php } elseif($pdf_listing_proforma_data[0]['nature_of_mttr']=='R'){  ?>
                                            <input type="radio" id="nature_mttr_c" name="Nature_mttr" value="C" disabled>&nbsp;
                                            <label for="criminals">CIVIL</label>&nbsp;
                                            <input  type="radio" id="nature_mttr_R" name="Nature_mttr" value="R" checked>&nbsp;
                                            <label for="criminals">CRIMINAL</label>&nbsp;
                                        <?php } else {  ?>
                                            <input type="radio" id="nature_mttr_c" name="Nature_mttr" value="C" >&nbsp;
                                            <label for="criminals">CIVIL</label>&nbsp;
                                            <input  type="radio" id="nature_mttr_R" name="Nature_mttr" value="R" >&nbsp;
                                            <label for="criminals">CRIMINAL</label>&nbsp;
                                        <?php } ?>

                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Petitioner(s) E-Mail Id  <span style="color: red">*</span>:</label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <input  class="form-control " id="pet_email" name="pet_email" value="<?php echo htmlentities($pdf_listing_proforma_data[0]['pet_email'], ENT_QUOTES); ?>" placeholder="E-Mail Id"  type="text" readonly>
                                        <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                        <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Please Enter E-Mail Id .">
                                                <i class="fa fa-question-circle-o"></i>
                                            </span>
                                    </div>
                                    <?php echo ($this->session->flashdata('pet_email') != NULL) ? $this->session->flashdata('pet_email') : NULL; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-5"> Respondent(s) Name <span style="color: red">*</span> :</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input  id="res_name" name="res_name" value="<?php echo htmlentities($pdf_listing_proforma_data[0]['res_party_name'], ENT_QUOTES); ?>" placeholder="Respondent(s) Name"  class="form-control" type="text" readonly>
                                        <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Title only accept Charaters and Spaces(eg. ABC XYZ).">
                                                <i class="fa fa-question-circle-o" ></i>
                                            </span>
                                    </div>
                                    <?php echo ($this->session->flashdata('res_name') != NULL) ? $this->session->flashdata('res_name') : NULL; ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Respondent(s) Mobile Phone Number :</label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <input  class="form-control " id="res_mobile" name="res_mobile" value="<?php echo htmlentities($pdf_listing_proforma_data[0]['res_mobile'], ENT_QUOTES); ?>"  placeholder="Mobile Phone Number"  type="text" readonly>
                                        <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                        <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Please Enter Mobile Phone Number .">
                                                <i class="fa fa-question-circle-o"></i>
                                            </span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Sub-Category Classification  :</label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <input  class="form-control " id="sub_category_classification" name="sub_category_classification" value="<?php echo htmlentities($pdf_listing_proforma_data[0]['sub_category'], ENT_QUOTES); ?>"  placeholder="Sub-Category Classification"  type="text" readonly>
                                        <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                        <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Title only accept Charaters and Spaces(eg. ABC XYZ)..">
                                                <i class="fa fa-question-circle-o"></i>
                                            </span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-5"> Similar Disposed of Matter with Citation, if any, & Case details. <span style="color: red">*</span> :</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input tabindex = '4' class="form-control " id="matter_with_citation_disposed" name="matter_with_citation_disposed" value="<?php echo htmlentities($Disposed_mttr_val, ENT_QUOTES); ?>"  placeholder="Disposed of Matter"  type="text">
                                        <input tabindex = '5' type="checkbox" id="matter_with_citation" name="matter_with_citation" value="N" <?= $Disposed_mttr ;?>  onclick="divhidedisposed()">&nbsp;&nbsp;
                                        <label for="citation"> NA</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Criminal Matters :</label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <?php if($pdf_listing_proforma_data[0]['nature_of_mttr']=='C'){  ?>
                                            <input  type="radio" id="criminals_mttr_y" name="Criminal_mttr" value="Y" disabled>&nbsp;
                                            <label for="criminals">Yes</label>&nbsp;
                                            <input  type="radio" id="criminals_mttr_n" name="Criminal_mttr" value="N" checked>&nbsp;
                                            <label for="criminals">No</label>&nbsp;
                                            <input  type="radio" id="criminals_mttr_o" name="Criminal_mttr" value="O" disabled>&nbsp;
                                            <label for="criminals">NA</label>
                                        <?php } elseif($pdf_listing_proforma_data[0]['nature_of_mttr']=='R') { ?>
                                            <input  type="radio" id="criminals_mttr_y" name="Criminal_mttr" value="Y" checked>&nbsp;
                                            <label for="criminals">Yes</label>&nbsp;
                                            <input  type="radio" id="criminals_mttr_n" name="Criminal_mttr" value="N" disabled>&nbsp;
                                            <label for="criminals">No</label>&nbsp;
                                            <input  type="radio" id="criminals_mttr_o" name="Criminal_mttr" value="O" disabled>&nbsp;
                                            <label for="criminals">NA</label>
                                        <?php } else { ?>
                                            <input  type="radio" id="criminals_mttr_y" name="Criminal_mttr" value="Y" >&nbsp;
                                            <label for="criminals">Yes</label>&nbsp;
                                            <input  type="radio" id="criminals_mttr_n" name="Criminal_mttr" value="N" >&nbsp;
                                            <label for="criminals">No</label>&nbsp;
                                            <input  type="radio" id="criminals_mttr_o" name="Criminal_mttr" value="O" >&nbsp;
                                            <label for="criminals">NA</label>
                                        <?php } ?>

                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">FIR No./Complaint No. And Date   :</label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <input  class="form-control " id="fir_complaint_no" name="fir_complaint_no" value="<?php echo htmlentities($pdf_listing_proforma_data[0]['fir_no'], ENT_QUOTES); ?>"  placeholder="FIR No./Complaint No. And Date "  type="text" readonly>
                                        <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                        <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Title only accept Charaters and Spaces(eg. ABC XYZ)..">
                                                <i class="fa fa-question-circle-o"></i>
                                            </span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Sentence Awarded :</label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <input tabindex = '9' class="form-control " id="sentence_awarded" name="sentence_awarded" value="<?php echo htmlentities($Sentence_awarded, ENT_QUOTES); ?>"  placeholder="Sentence Awarded"  type="text">
                                        <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                        <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Title only accept Charaters and Spaces(eg. ABC XYZ). .">
                                                <i class="fa fa-question-circle-o"></i>
                                            </span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-5"> Land acquisition Matters <span style="color: red">*</span> :</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input tabindex = '11' type="checkbox" id="land_acquisition_mattr" name="land_acquisition_mattr" value="N" <?php if($pdf_listing_proforma_data[0]['land_acquisition_mattr']=='N') echo 'checked';?>>&nbsp;&nbsp;
                                        <label for="citation"> NA</label>

                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-5"> Date of Section 6 notification <span style="color: red">*</span> :</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input tabindex = '14' id="date_section6_notification_date" name="date_section6_notification_date" value="<?php echo ($Date_sec6_notification != 'checked') ? $date_6 : ''; ?>" maxlength="10" readonly="" placeholder="DD-MM-YYYY"   class="form-control" type="text">
                                        <input tabindex = '15' type="checkbox" id="date_section6_notification" name="date_section6_notification" value="N" <?= $Date_sec6_notification;?> onclick="divhidedt_6()">&nbsp;&nbsp;
                                        <label for="citation"> NA</label>

                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-5"> Tax Matters : State the tax effect <span style="color: red">*</span> :</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input tabindex = '18' type="checkbox" id="tax_mttr_state" name="tax_mttr_state" value="N" <?php if($pdf_listing_proforma_data[0]['tax_mttr']=='N') echo 'checked';?>>&nbsp;&nbsp;
                                        <label for="citation1"> NA</label>

                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-5">  Senior Citizen > 65 Years <input tabindex = '20'type="checkbox" id="senior_citizen_65" name="senior_citizen_65" value="Y" <?php if($pdf_listing_proforma_data[0]['senior_citizen']=='Y') echo 'checked';?>></label>

                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-5">   Woman/Child <input tabindex = '22' type="checkbox" id="women_child" name="women_child" value="Y" <?php if($pdf_listing_proforma_data[0]['women_child']=='Y') echo 'checked';?>></label>

                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-5">   Legal Aid case <input tabindex = '24' type="checkbox" id="legal_aid_case" name="legal_aid_case" value="Y" <?php if($pdf_listing_proforma_data[0]['legal_aid_case']=='Y') echo 'checked';?>></label>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Vehicle number: (in case of motor accident claim matter only) :</label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <input tabindex = '26' class="form-control " id="vehicle_number" name="vehicle_number" value="<?php echo htmlentities($Vehicle_number, ENT_QUOTES); ?>"  placeholder="Vehicle number"  type="text">
                                        <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                                        <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Title only accept Charaters and Spaces(eg. ABC XYZ). .">
                                                <i class="fa fa-question-circle-o"></i>
                                            </span>
                                    </div>
                                </div>
                            </div>

                        </div><!-- END OF DIV class="col-sm-6 col-xs-12" -->

                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-4">
                                <!--edit_data-->
                                <?php if (isset($edit_data) == 'TRUE') { ?>
                                    <input tabindex = '50' type="submit" name="add_notice" value="Update" class="btn btn-success">
                                    <button tabindex = '51' onclick="location.href = '<?php echo base_url('supplements/listing_proforma_controller/gen_listingProforma_editPdf'); ?>'" class="btn btn-primary" type="reset">Cancel</button>
                                <?php  } else { ?>
                                    <input tabindex = '50' type="submit" name="add_notice" value="Add" class="btn btn-success">
                                    <button tabindex = '51' onclick="location.href = '<?php echo base_url('supplements/listing_proforma_controller'); ?>'" class="btn btn-primary" type="reset">Cancel</button>
                                    <!--<input type="submit" name="update_notice" value="Update" class="btn btn-success">-->
                                <?php } ?>

                            </div>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
                <?php //} else { ?>

                <!--<h2><i class="fa  fa-newspaper-o"></i> FAQ FORM</h2>
                <div class="clearfix"></div>-->

                <?php //} ?>
            </div>
        </div>
    </div>

</div>
</div>

<script type="text/javascript">

    $('#date_section4_notification_date').datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "dd-mm-yy"
        //maxDate: new Date

    });

    $('#date_section6_notification_date').datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "dd-mm-yy"
        //maxDate: new Date

    });
    $('#date_section17_notification_date').datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "dd-mm-yy"
        //maxDate: new Date

    });

    function divhidedisposed(){
        if(document.getElementById('matter_with_citation').checked){
            $('#matter_with_citation_disposed').hide();
        }
        else{
            $('#matter_with_citation_disposed').show();
        }
    }

    function divhidepending(){
        if(document.getElementById('matter_with_case_dtl').checked){
            $('#matter_with_case_dtl_pending').hide();
        }
        else{
            $('#matter_with_case_dtl_pending').show();
        }
    }
    function divhidedt_4(){
        if(document.getElementById('date_section4_notification').checked){
            $('#date_section4_notification_date').hide();
        }
        else{
            $('#date_section4_notification_date').show();
        }
    }
    function divhidedt_6(){
        if(document.getElementById('date_section6_notification').checked){
            $('#date_section6_notification_date').hide();
        }
        else{
            $('#date_section6_notification_date').show();
        }
    }
    function divhidedt_17(){
        if(document.getElementById('date_section17_notification').checked){
            $('#date_section17_notification_date').hide();
        }
        else{
            $('#date_section17_notification_date').show();
        }
    }
    $( document ).ready(function() {
        var disposed_div_val= '<?php echo $Disposed_matter_with_citation ?> ';
        var Div_pending_val= '<?php echo $Pending_matter_with_case ?> ';
        var Date_div_4sec= '<?php echo $Date_sec4_notification ?> ';
        var Date_div_6sec= '<?php echo $Date_sec6_notification ?> ';
        var Date_div_17sec= '<?php echo $Date_sec17_notification ?> ';

        if(disposed_div_val.trim()=='N'){
            $('#matter_with_citation_disposed').hide();
        }else{
            $('#matter_with_citation_disposed').show();
        }
        if(Div_pending_val.trim()=='N'){
            $('#matter_with_case_dtl_pending').hide();
        }else{
            $('#matter_with_case_dtl_pending').show();
        }
        if(Date_div_4sec.trim()=='checked'){
            $('#date_section4_notification_date').hide();
        }else if(Date_div_4sec.trim()!='checked'){
            $('#date_section4_notification_date').show();
        }
        if(Date_div_6sec.trim()=='checked'){
            $('#date_section6_notification_date').hide();
        }else if(Date_div_6sec.trim()!='checked'){
            $('#date_section6_notification_date').show();
        }
        if(Date_div_17sec.trim()=='checked'){
            $('#date_section17_notification_date').hide();
        }else if(Date_div_17sec.trim()!='checked'){
            $('#date_section17_notification_date').show();
        }

        $('#add_profor_data').validate({
            focusInvalid: true,
            ignore: ":hidden",
            rules: {
                name_of_judges_passed: {
                    required: true
                }, not_to_be_listed: {
                    required: true
                },
                sentence_awarded: {
                    required: true
                },
                period_sentence_undergone: {
                    required: true
                }

            },
            messages: {
                name_of_judges_passed: {
                    required: 'Enter Name of Judges who passed the order.'
                }, not_to_be_listed: {
                    required: 'Enter Not to be Listed Before .'

                },
                sentence_awarded: {
                    required: 'Enter Sentence Awarded .'

                },
                period_sentence_undergone: "Pperiod_sentence_undergone."

            },
            highlight: function (element) {
                $(element).closest('.form-group').addClass('has-error');
            },
            unhighlight: function (element) {
                $(element).closest('.form-group').removeClass('has-error');
            },
            errorElement: 'span',
            errorClass: 'error-tip',
            errorPlacement: function (error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            }
        });

    });//END of function document.ready()..
</script>
