<link rel="shortcut icon" href="<?= base_url().'assets/newDesign/images/logo.png' ?>" type="image/png" />
@if(getSessionData('login')['ref_m_usertype_id'] == USER_EFILING_ADMIN || getSessionData('login')['ref_m_usertype_id'] == USER_ADMIN)
   @extends('layout.app')
   @section('content')
@endif
<!DOCTYPE HTML>
<html>
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <title>SC</title>
      <link href="<?= base_url() . 'assets/newAdmin/' ?>css/bootstrap.min.css" rel="stylesheet">
      <link href="<?= base_url() . 'assets/newAdmin/' ?>css/font-awesome.min.css" rel="stylesheet">
      <link href="<?= base_url() . 'assets/newAdmin/' ?>css/animate.css" rel="stylesheet">
      <link href="<?= base_url() . 'assets/newAdmin/' ?>css/material.css" rel="stylesheet" />
      <link href="<?= base_url() . 'assets/newAdmin/' ?>css/style.css" rel="stylesheet">
      <link rel="stylesheet" type="text/css" href="<?= base_url() . 'assets/newAdmin/' ?>css/jquery.dataTables.min.css">
      <link href="<?= base_url() . 'assets/newAdmin/' ?>css/fullcalendar.css" rel="stylesheet">
      <link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-datepicker.min.css">
      <link href="<?= base_url() . 'assets' ?>/css/select2.min.css" rel="stylesheet">
      <link href="<?= base_url() . 'assets/newAdmin/' ?>css/black-theme.css" rel="stylesheet">
      <link href="<?= base_url() . 'assets/newAdmin/' ?>css/responsive.css" rel="stylesheet">
   </head>
   <?php
      $ref_m_usertype_id = !empty(getSessionData('login')['ref_m_usertype_id']) ? getSessionData('login')['ref_m_usertype_id'] : null;
      $stage_id = !empty(getSessionData('efiling_details')['stage_id']) ? getSessionData('efiling_details')['stage_id'] : null;
      $filing_type = !empty(getSessionData('efiling_details')['efiling_type']) ? getSessionData('efiling_details')['efiling_type'] : null;
      $collapse_class = 'collapse';
      $area_extended = false;
      $diary_generate_button = '';
      $diary = '';
      $hidepencilbtn='';
      
      if (isset($new_case_details[0]['sc_diary_num']) && !empty($new_case_details[0]['sc_diary_num'])) {
         $diary = $new_case_details[0]['sc_diary_num'];
      }
      if (isset($new_case_details[0]['sc_diary_year']) && !empty($new_case_details[0]['sc_diary_year'])) {
          $diary .= ' / ' . $new_case_details[0]['sc_diary_year'];
      }
      if (isset($ref_m_usertype_id) && !empty($ref_m_usertype_id) && $ref_m_usertype_id == USER_ADMIN && isset($stage_id) && !empty($stage_id) && $stage_id == Transfer_to_IB_Stage) {
          $collapse_class = 'collapse in';
          $area_extended = true;
          $diary_generate_button = '<div class="center-buttons"><a  href="javaScript:void(0)" class="quick-btn" data-efilingtype="new_case" id="generateDiaryNoPop" type="button">Generate Diary No.</a></div>';
      }
      $stages_array = ['', Draft_Stage, Initial_Defected_Stage, E_REJECTED_STAGE];
      if(isset(getSessionData('efiling_details')['stage_id'])){
          if (!in_array(getSessionData('efiling_details')['stage_id'], $stages_array)) {
              $hidepencilbtn = 'true';
          } else {
              $hidepencilbtn = 'false';
          }
      }
      
      ?>
   <body>
      <div id="loader-wrapper" style="display: none;">
         <div id="loader"></div>
      </div>
      <div class="mainPanel ">
         <div class="panelInner">
            <div class="middleContent">
               <div class="container-fluid">
                  <div class="row">
                     <div class="col-12 sm-12 col-md-12 col-lg-12 middleContent-left">
                        <?php 
                           if (!isset($efiling_search_header)) {
                               render('newcase.new_case_breadcrumb');
                           } ?>
                        <div class="center-content-inner comn-innercontent">
                           <div class="tab-content">
                              <div class="tab-pane active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                 <div class="row">
                                    <h6 class="text-center fw-bold">View</h6>
                                 </div>
                                 <div class="tab-form-inner">
                                    <div class="row">
                                       <div style="float: right">
                                          <div class="col-lg-5 col-md-5 col-sm-6 col-xs-5">
                                             <?php
                                             // if (getSessionData('login')['ref_m_usertype_id'] == USER_ADVOCATE || getSessionData('login')['ref_m_usertype_id'] == USER_IN_PERSON) {
                                                // $allowed_users_array = array(Initial_Approaval_Pending_Stage, I_B_Defects_Cured_Stage, Initial_Defects_Cured_Stage);
                                                // if (in_array(getSessionData('efiling_details')['stage_id'], $allowed_users_array)) {
                                                   ?>
                                                   <!-- <a class="btn btn-success btn-sm" target="_blank" href="<?php // echo base_url('acknowledgement/view'); ?>"><i class="fa fa-download blink"></i> eFiling Acknowledgement</a>  -->
                                                   <?php
                                                // }
                                             // }
                                             ?>
                                          </div>
                                          <?php
                                          // $diary = false;
                                          if (isset($diary_generate_button) && !empty($diary_generate_button) && empty($diary)) {
                                             echo $diary_generate_button;
                                          }
                                          if (isset($ref_m_usertype_id) && !empty($ref_m_usertype_id) && $ref_m_usertype_id == USER_ADMIN && isset($stage_id) && !empty($stage_id) && $stage_id == I_B_Defects_Cured_Stage) {
                                             if (!empty(getSessionData('efiling_details')['stage_id']) && getSessionData('efiling_details')['stage_id'] == I_B_Defects_Cured_Stage) {
                                                ?>
                                                <button class="btn btn-primary pull-right mb-3" data-efilingtype="new_case" data-registration_id="<?php echo getSessionData('efiling_details')['registration_id']; ?>" id="transferToScrutiny" type="button">Transfer To Scrutiny</button>
                                                <?php
                                             }
                                          }
                                          ?>
                                          <button id="collapseAll" onclick="toggleAllAccordions()" class="btn btn-primary pull-right mb-2" style="margin-right: 5px;"> Collapse All </button>
                                       </div>
                                       <div class="col-md-12 col-sm-12 col-xs-12">
                                          <div class="accordion view-accordion acrdion-with-edit"
                                             id="accordionExample">
                                             <div class="accordion-item">
                                                <h2 class="accordion-header"
                                                   id="headingOne">
                                                   <button class="accordion-button"
                                                      type="button"
                                                      data-bs-toggle="collapse"
                                                      data-bs-target="#collapseOne"
                                                      aria-expanded="true"
                                                      aria-controls="collapseOne">
                                                   Case Details
                                                   </button>
                                                </h2>
                                                <div id="collapseOne"
                                                   class="accordion-collapse collapse show"
                                                   aria-labelledby="headingOne"
                                                   data-bs-parent="#accordionExample">
                                                   <div class="accordion-body">
                                                      <div class="x_panel">
                                                         <div class="row">
                                                            <div class="col-md-4 col-sm-12">
                                                               <div class="form-group row">
                                                                  <label
                                                                     class="control-label col-4 text-right"
                                                                     for="filing_no"><b>Cause Title :</b>
                                                                  </label>
                                                                  <div class="col-8">
                                                                     <p>
                                                                        <?= isset($new_case_details[0]['cause_title']) ? echo_data($new_case_details[0]['cause_title']) : '';?>
                                                                     </p>
                                                                  </div>
                                                               </div>
                                                            </div>
                                                            <div class="col-md-4 col-sm-12">
                                                               <div class="form-group row">
                                                                  <label
                                                                     class="control-label col-4 text-right"
                                                                     for="filing_no"><b>Case Type :</b> 
                                                                  </label>
                                                                  <div class="col-8">
                                                                     <p> 
                                                                     <?= isset($sc_case_type[0]->casename) ?  echo_data(strtoupper($sc_case_type[0]->casename)) : '';?>   
                                                                     <?php // echo_data(strtoupper($sc_case_type[0]->casename)); ?> </p>
                                                                  </div>
                                                               </div>
                                                            </div>
                                                            <div class="col-md-4 col-sm-12">
                                                               <div class="form-group row">
                                                                  <label
                                                                     class="control-label col-4 text-right"
                                                                     for="filing_no"><b>Subject Category :</b> 
                                                                  </label>
                                                                  <div class="col-8">
                                                                     <p> 
                                                                     <?php
                                                                     isset($main_subject_cat[0]->sub_name1) ? echo_data(strtoupper($main_subject_cat[0]->sub_name1)) :'';
                                                                     isset($sub_subject_cat[0]->sub_name4) ? echo_data(!empty($sub_subject_cat[0]->sub_name4) ? '(' . $sub_subject_cat[0]->sub_name4 . ')' : '') : '';
                                                                     ?>
                                                                        <?php
                                                                        // echo_data(strtoupper($main_subject_cat[0]->sub_name1));
                                                                        // echo_data(!empty($sub_subject_cat[0]->sub_name4) ? '(' . $sub_subject_cat[0]->sub_name4 . ')' : '');
                                                                        ?> </p>
                                                                  </div>
                                                               </div>
                                                            </div>
                                                            </div>
                                                         <div class="row">

                                                            <?php
                                                               if (isset($diary) && !empty($diary)) {
                                                                   echo '<div class="col-md-4 col-sm-12">
                                                                       <div class="form-group row">
                                                                           <label class="control-label col-4 text-right" for="diary_no"><b> Diary No. : </b></label>
                                                                           <div class="col-8">
                                                                               <p>' . $diary . '</p>
                                                                           </div>
                                                                       </div>
                                                                   </div>';
                                                               }
                                                               if (isset($filedByData) && !empty($filedByData)) {
                                                                   $vakalat_advocate = '';
                                                                   if (isset($vakalat_filedByData) && !is_null($vakalat_filedByData)) {
                                                                       $vakalat_advocate = '<p><b>Transfered to : </b>' . $vakalat_filedByData . '</p><p>' . $vakalat_filedByData_contact_emailid . '</p>';
                                                                   }
                                                                   $filed = '<div class="col-md-4 col-sm-12">
                                                                       <div class="form-group row">
                                                                           <label class="control-label col-4 text-right" for="filed_by"><b> Filed By:</b> </label>
                                                                           <div class="col-8">
                                                                              <p> ' . $filedByData . '<br />'.$filedByData_contact_emailid.'<br />'.$vakalat_advocate.' </p>
                                                                           </div>
                                                                       </div>
                                                                   </div>';
                                                                   echo $filed;
                                                               }
                                                               ?>
                                                            <div class="col-md-4 col-sm-12">
                                                               <div class="form-group row">
                                                                  <label class="control-label col-4 pe-0 text-right" for="filing_no">
                                                                     <b>No. of Petitioner :</b>
                                                                  </label>
                                                                  <div class="col-8">
                                                                     <p>
                                                                        <?= isset($new_case_details[0]['no_of_petitioners']) ? echo_data($new_case_details[0]['no_of_petitioners']): '' ; ?>
                                                                     </p>
                                                                  </div>
                                                               </div>
                                                            </div>
                                                            <div class="col-md-4 col-sm-12">
                                                               <div class="form-group row">
                                                                  <label
                                                                     class="control-label col-4 pe-0 text-right"
                                                                     for="filing_no"><b> No. of
                                                                  Respondent :</b> 
                                                                  </label>
                                                                  <div class="col-8">
                                                                     <p>
                                                                        <?= isset($new_case_details[0]['no_of_respondents']) ? echo_data($new_case_details[0]['no_of_respondents']): '' ; ?>
                                                                     </p>
                                                                  </div>
                                                               </div>
                                                            </div>
                                                            <div class="col-md-4 col-sm-12">
                                                               <div class="form-group row">
                                                                  <label
                                                                     class="control-label col-4 text-right"
                                                                     for="filing_no"><b>IF SCLSC :</b> 
                                                                  </label>
                                                                  <div class="col-8">
                                                                     <p>
                                                                        <?= !empty($new_case_details[0]['if_sclsc']) && $new_case_details[0]['if_sclsc'] == 1 ? 'Yes' : 'No'; ?>
                                                                        <?php // echo !empty($new_case_details[0]->if_sclsc) && $new_case_details[0]->if_sclsc == 1 ? 'Yes' : 'No'; ?>
                                                                     </p>
                                                                  </div>
                                                               </div>
                                                            </div>
                                                            <div class="col-md-4 col-sm-12">
                                                               <div class="form-group row">
                                                                  <label
                                                                     class="control-label col-4 text-right"
                                                                     for="filing_no"><b>Special
                                                                  Category :</b> 
                                                                  </label>
                                                                  <div class="col-8">
                                                                     <?= !empty($new_case_details[0]['category_name']) ? $new_case_details[0]['category_name'] : 'None'; ?>
                                                                     <p><?php // echo !empty($new_case_details[0]->category_name) ? $new_case_details[0]->category_name : 'None'; ?></p>
                                                                  </div>
                                                               </div>
                                                            </div>
                                                         </div>
                                                      </div>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="accordion-item">
                                                <div class="row">
                                                   <h2 class="accordion-header 
                                                      <?php
                                                         if(isset($hidepencilbtn)){
                                                             if ($hidepencilbtn != 'true') { ?>
                                                      col-sm-12
                                                      <?php } else { ?>
                                                      col-sm-12
                                                      <?php } } ?>
                                                      "
                                                      id="headingTwo">
                                                      <button class="accordion-button collapsed"
                                                         type="button"
                                                         data-bs-toggle="collapse"
                                                         data-bs-target="#collapseTwo"
                                                         aria-expanded="false"
                                                         aria-controls="collapseTwo" <?php echo $area_extended; ?>>
                                                      <?php
                                                         if (!isset($petitioner_details) || empty($petitioner_details)) { ?>
                                                      <font style="color:red;"> <b>Petitioner</b>
                                                      </font>
                                                      <?php } else { ?><b>Petitioner</b><?php } ?>
                                                      <?php
                                                      if(isset($hidepencilbtn)){
                                                          if ($hidepencilbtn != 'true') { ?>
                                                         <a href="<?php echo base_url('newcase/petitioner'); ?>">
                                                            <i class="fa fa-pencil efiling_search"></i>
                                                         </a>
                                                         <?php }  }?>
                                                      </button>
                                                   </h2>
                                                   <?php
                                                      if(isset($hidepencilbtn)){
                                                          if ($hidepencilbtn != 'true') { ?>
                                                   <!-- <div class="col-sm-1">
                                                      
                                                   </div> -->
                                                   <?php }  }?>
                                                </div>
                                                <div id="collapseTwo"
                                                   class="accordion-collapse collapse <?php echo $collapse_class; ?>"
                                                   aria-labelledby="headingTwo"
                                                   data-bs-parent="#accordionExample">
                                                   <div class="accordion-body">
                                                      <?php render('newcase.petitioner_list_view', ['petitioner_details' => $petitioner_details]); ?>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="accordion-item">
                                                <div class="row">
                                                   <h2 class="accordion-header 
                                                      <?php
                                                         if(isset($hidepencilbtn)){
                                                             if ($hidepencilbtn != 'true') { ?>
                                                      col-sm-12
                                                      <?php } else { ?>
                                                      col-sm-12
                                                      <?php } } ?>
                                                      "
                                                      id="headingThree">
                                                      <button class="accordion-button collapsed"
                                                         type="button"
                                                         data-bs-toggle="collapse"
                                                         data-bs-target="#collapseThree"
                                                         aria-expanded="false"
                                                         aria-controls="collapseThree" <?php echo $area_extended; ?>>
                                                      <?php
                                                         if (!isset($respondent_details) || empty($respondent_details)) { ?><font style="color:red;">
                                                      <b>Respondent</b>
                                                      </font><?php } else { ?>
                                                      <b>Respondent</b><?php } ?>
                                                      <?php
                                                      if(isset($hidepencilbtn)){
                                                      if ($hidepencilbtn != 'true') { ?>
                                                      <a href="<?php echo base_url('newcase/respondent'); ?>"><i
                                                         class="fa fa-pencil efiling_search"></i></a>
                                                         <?php } } ?>
                                                      </button>
                                                   </h2>
                                                   <?php
                                                      if(isset($hidepencilbtn)){
                                                      if ($hidepencilbtn != 'true') { ?>
                                                   <!-- <div class="col-sm-1">
                                                      
                                                   </div> -->
                                                   <?php } } ?>
                                                </div>
                                                <div id="collapseThree"
                                                   class="accordion-collapse collapse <?php echo $collapse_class; ?>"
                                                   aria-labelledby="headingThree"
                                                   data-bs-parent="#accordionExample">
                                                   <div class="accordion-body">
                                                      <?php render('newcase.respondent_list_view', ['respondent_details' => $respondent_details]); ?>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="accordion-item">
                                                <div class="row">
                                                   <h2 class="accordion-header <?php
                                                      if(isset($hidepencilbtn)){
                                                          if ($hidepencilbtn != 'true') { ?>
                                                      col-sm-12
                                                      <?php } else { ?>
                                                      col-sm-12
                                                      <?php } } ?>"
                                                      id="headingFour">
                                                      <button class="accordion-button collapsed"
                                                         type="button"
                                                         data-bs-toggle="collapse"
                                                         data-bs-target="#collapseFour"
                                                         aria-expanded="false"
                                                         aria-controls="collapseFour" <?php echo $area_extended; ?>>
                                                      <?php
                                                         if (!isset($extra_parties_list) || empty($extra_parties_list)) { ?><font
                                                         style="color:orange;">
                                                      <b>Extra Party</b>
                                                      </font><?php } else { ?>
                                                      <b>Extra Party</b><?php } ?>
                                                      <?php
                                                      if(isset($hidepencilbtn)){
                                                      if ($hidepencilbtn != 'true') { ?>
                                                      <a href="<?php echo base_url('newcase/extra_party'); ?>"><i
                                                         class="fa fa-pencil efiling_search"></i></a>
                                                         <?php } } ?>
                                                      </button>
                                                   </h2>
                                                   <?php
                                                      if(isset($hidepencilbtn)){
                                                      if ($hidepencilbtn != 'true') { ?>
                                                   <!-- <div class="col-sm-1">
                                                      
                                                   </div> -->
                                                   <?php } } ?>
                                                </div>
                                                <div id="collapseFour"
                                                   class="accordion-collapse collapse <?php echo $collapse_class; ?>"
                                                   aria-labelledby="headingFour"
                                                   data-bs-parent="#accordionExample">
                                                   <div class="accordion-body">
                                                      <?php render('newcase.extra_party_list_view', ['extra_parties_list' => $extra_parties_list]); ?>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="accordion-item" style="display: none;">
                                                <div class="row">
                                                   <h2 class="accordion-header <?php
                                                      if(isset($hidepencilbtn)){
                                                          if ($hidepencilbtn != 'true') { ?>
                                                      col-sm-12
                                                      <?php } else { ?>
                                                      col-sm-12
                                                      <?php } } ?>"
                                                      id="headingFive">
                                                      <button class="accordion-button collapsed"
                                                         type="button"
                                                         data-bs-toggle="collapse"
                                                         data-bs-target="#collapseFive"
                                                         aria-expanded="false"
                                                         aria-controls="collapseFive" <?php echo $area_extended; ?>>
                                                      <?php
                                                         if (!isset($lr_parties_list) || empty($lr_parties_list)) { ?><font
                                                         style="color:orange;">
                                                      <b>Legal Representative</b>
                                                      </font>
                                                      <?php } else { ?> <b>Legal
                                                      Representative</b><?php } ?>
                                                      <?php
                                                      if(isset($hidepencilbtn)){
                                                      
                                                          if ($hidepencilbtn != 'true') { ?>
                                                      <a href="<?php echo base_url('newcase/lr_party'); ?>"><i
                                                         style="color:black; padding-top:20px;"
                                                         class="fa fa-pencil efiling_search"></i></a>
                                                         <?php }
                                                      } ?>
                                                      </button>
                                                   </h2>
                                                   <?php
                                                      if(isset($hidepencilbtn)){
                                                      
                                                          if ($hidepencilbtn != 'true') { ?>
                                                   <!-- <div class="col-sm-1">
                                                      
                                                   </div> -->
                                                   <?php }
                                                      } ?>
                                                </div>
                                                <div id="collapseFive"
                                                   class="accordion-collapse collapse <?php echo $collapse_class; ?>"
                                                   aria-labelledby="headingFive"
                                                   data-bs-parent="#accordionExample">
                                                   <div class="accordion-body">
                                                      <?php render('newcase.lr_party_list_view', ['lr_parties_list' => $lr_parties_list]); ?>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="accordion-item" style="display: none;">
                                                <div class="row">
                                                   <h2 class="accordion-header <?php
                                                      if(isset($hidepencilbtn)){
                                                          if ($hidepencilbtn != 'true') { ?>
                                                      col-sm-12
                                                      <?php } else { ?>
                                                      col-sm-12
                                                      <?php } } ?>"
                                                      id="headingSix">
                                                      <button class="accordion-button collapsed"
                                                         type="button"
                                                         data-bs-toggle="collapse"
                                                         data-bs-target="#collapseSix"
                                                         aria-expanded="false"
                                                         aria-controls="collapseSix" <?php echo $area_extended; ?>>
                                                      <?php
                                                         if (!isset($act_sections_list) || empty($act_sections_list)) { ?><font style="color:red;">
                                                      <b>Act-Section</b>
                                                      </font><?php } else { ?>
                                                      <b>Act-Section</b><?php } ?>
                                                      <?php
                                                      if ($hidepencilbtn != 'true') { ?>
                                                      <a href="<?php echo base_url('newcase/actSections'); ?>"><i
                                                       
                                                         class="fa fa-pencil efiling_search"></i></a>
                                                         <?php } ?>
                                                      </button>
                                                   </h2>
                                                   <?php
                                                      if ($hidepencilbtn != 'true') { ?>
                                                   <!-- <div class="col-sm-1">
                                                     
                                                   </div> -->
                                                   <?php } ?>
                                                </div>
                                                <div id="collapseSix"
                                                   class="accordion-collapse collapse <?php echo $collapse_class; ?>"
                                                   aria-labelledby="headingSix"
                                                   data-bs-parent="#accordionExample">
                                                   <div class="accordion-body">
                                                      <?php render('newcase.act_sections_list_view', ['act_sections_list' => $act_sections_list]); ?>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="accordion-item">
                                                <div class="row">
                                                   <h2 class="accordion-header <?php
                                                      if(isset($hidepencilbtn)){
                                                          if ($hidepencilbtn != 'true') { ?>
                                                      col-sm-12
                                                      <?php } else { ?>
                                                      col-sm-12
                                                      <?php } } ?>"
                                                      id="headingSeven">
                                                      <button class="accordion-button collapsed"
                                                         type="button"
                                                         data-bs-toggle="collapse"
                                                         data-bs-target="#collapseSeven"
                                                         aria-expanded="false"
                                                         aria-controls="collapseThreeSeven" <?php echo $area_extended; ?>>
                                                      <?php
                                                         if (!isset($subordinate_court_details) || empty($subordinate_court_details)) { ?><font
                                                         style="color:orange;">
                                                      <b>Earlier Courts</b>
                                                      </font><?php } else { ?>
                                                      <b>Earlier Courts</b><?php } ?>
                                                      <?php
                                                      if ($hidepencilbtn != 'true') { ?>
                                                      <a href="<?php echo base_url('newcase/subordinate_court'); ?>"><i
                                                         class="fa fa-pencil efiling_search"></i></a>
                                                         <?php } ?>
                                                      </button>
                                                   </h2>
                                                   <?php
                                                      if ($hidepencilbtn != 'true') { ?>
                                                   <!-- <div class="col-sm-1">
                                                     
                                                   </div> -->
                                                   <?php } ?>
                                                </div>
                                                <div id="collapseSeven"
                                                   class="accordion-collapse collapse <?php echo $collapse_class; ?>"
                                                   aria-labelledby="headingSeven"
                                                   data-bs-parent="#accordionExample">
                                                   <div class="accordion-body">
                                                      <?php
                                                         render('newcase.subordinate_court_list', ['subordinate_court_details' => $subordinate_court_details]); ?>
                                                   </div>
                                                </div>
                                             </div>
                                             
                                             <div class="accordion-item">
                                                <div class="row">
                                                   <h2 class="accordion-header <?php
                                                      if(isset($hidepencilbtn)){
                                                          if ($hidepencilbtn != 'true') { ?>
                                                      col-sm-12
                                                      <?php } else { ?>
                                                      col-sm-12
                                                      <?php } } ?>"
                                                      id="headingEight">
                                                      <button class="accordion-button collapsed"
                                                         type="button"
                                                         data-bs-toggle="collapse"
                                                         data-bs-target="#collapseEight"
                                                         aria-expanded="false"
                                                         aria-controls="collapseEight" <?php echo $area_extended; ?>>
                                                      <?php
                                                         if (!isset($efiled_docs_list) || empty($efiled_docs_list)) { ?><font style="color:red;">
                                                      <b>Index</b>
                                                      </font><?php } else { ?>
                                                      <b>Index</b><?php } ?>
                                                      <?php
                                                      if ($hidepencilbtn != 'true') { ?>
                                                      <a href="<?php echo base_url('documentIndex'); ?>"><i
                                                         class="fa fa-pencil efiling_search"></i></a>
                                                         <?php } ?>
                                                      </button>
                                                   </h2>
                                                   <?php
                                                      if ($hidepencilbtn != 'true') { ?>
                                                   <!-- <div class="col-sm-1">
                                                      
                                                   </div> -->
                                                   <?php } ?>
                                                </div>
                                                <div id="collapseEight"
                                                   class="accordion-collapse collapse <?php echo $collapse_class; ?>"
                                                   aria-labelledby="headingEight"
                                                   data-bs-parent="#accordionExample">
                                                   <div class="accordion-body">
                                                      <?php render('documentIndex.preview_newcase_index_doc_list', ['efiled_docs_list' => $efiled_docs_list, 'uploaded_docs' => $uploaded_docs]); ?>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="accordion-item">
                                                <div class="row">
                                                   <h2 class="accordion-header <?php
                                                      if(isset($hidepencilbtn)){
                                                          if ($hidepencilbtn != 'true') { ?>
                                                      col-sm-12
                                                      <?php } else { ?>
                                                      col-sm-12
                                                      <?php } } ?>"
                                                      id="headingNine">
                                                      <button class="accordion-button collapsed"
                                                         type="button"
                                                         data-bs-toggle="collapse"
                                                         data-bs-target="#collapseNine"
                                                         aria-expanded="false"
                                                         aria-controls="collapseNine"
                                                         <?php echo $area_extended; ?>>
                                                      <?php
                                                         $pending_court_fee = getPendingCourtFee();
                                                         if ((!isset($payment_details) || empty($payment_details)) && ($pending_court_fee > 0)) { ?>
                                                      <font style="color:red;">
                                                      <b>Court Fee</b>
                                                      </font><?php } else { ?>
                                                      <b>Court Fee</b><?php } ?>
                                                      <?php
                                                      if ($hidepencilbtn != 'true') { ?>
                                                      <a href="<?php echo base_url('newcase/courtFee'); ?>"><i
                                                         class="fa fa-pencil efiling_search"></i></a>
                                                         <?php } ?>
                                                      </button>
                                                   </h2>
                                                   <?php
                                                      if ($hidepencilbtn != 'true') { ?>
                                                   <!-- <div class="col-sm-1">
                                                     
                                                   </div> -->
                                                   <?php } ?>
                                                </div>
                                                <div id="collapseNine"
                                                   class="accordion-collapse collapse <?php echo $collapse_class; ?>"
                                                   aria-labelledby="headingNine"
                                                   data-bs-parent="#accordionExample">
                                                   <div class="accordion-body">
                                                      <?php render('shcilPayment.payment_list_view', ['payment_details' => $payment_details]); ?>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="accordion-item">
                                                <div class="row">
                                                   <h2 class="accordion-header <?php if(isset($hidepencilbtn)) { if ($hidepencilbtn != 'true') { ?>col-sm-12<?php } else { ?>col-sm-12<?php } } ?>" id="headingTen">
                                                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTen" aria-expanded="false" aria-controls="collapseTen" <?php echo $area_extended; ?>>
                                                         <b>Check List / Declaration</b>
                                                         <?php if ($hidepencilbtn != 'true') { ?>
                                                            <a href="<?php echo base_url('newcase/checklist'); ?>"><i class="fa fa-pencil efiling_search"></i></a>
                                                         <?php } ?>
                                                      </button>
                                                   </h2>
                                                </div>
                                                <div id="collapseTen"
                                                   class="accordion-collapse collapse <?php echo $collapse_class; ?>"
                                                   aria-labelledby="headingTen"
                                                   data-bs-parent="#accordionExample">
                                                   <div class="accordion-body">
                                                      <?php render('newcase.checklist_details_view'); ?>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 my-3 text-center">
                                       <div class="row">
                                          <div class="progress" style="display: none;">
                                             <div class="progress-bar progress-bar-success myprogress"
                                                role="progressbar"
                                                value="0"
                                                max="100"
                                                style="width:0%">0%</div>
                                          </div>
                                       </div>
                                       <div class="save-btns text-center">
                                          <?php
                                             if (isset(getSessionData('efiling_details')['ref_m_efiled_type_id']) && getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_NEW_CASE) {
                                                 $prev_url = base_url('documentIndex');
                                                 //$next_url = base_url('affirmation');
                                                 $next_url = base_url('newcase/view');
                                             } elseif (isset(getSessionData('efiling_details')['ref_m_efiled_type_id']) && getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_CAVEAT) {
                                                 $prev_url = base_url('documentIndex');
                                                 $next_url = base_url('caveat/view');
                                             } else {
                                                 $prev_url = '#';
                                                 $next_url = '#';
                                             }
                                             ?>
                                          <!--<a href="<?= $prev_url ?>"
                                             class="btn btn-primary btnPrevious"
                                             type="button">Previous</a>-->
                                          <input type="submit" class="btn btn-success pay_fee" id="pay_fee" name="submit" value="PAY" style="display: none;">
                                          <?php
                                             if ((isset($payment_details['0']->payment_status) && !empty($payment_details['0']->payment_status) && $payment_details['0']->payment_status == 'Y') || ($pending_court_fee == 0)) { ?>
                                          <!--<a href="<?= $next_url ?>"
                                             class="btn btn-primary btnNext pay_fee_next"
                                             type="button">Next</a>-->
                                          <?php } ?>
                                       </div>

                                       <?php
                                          $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_DEPARTMENT, JAIL_SUPERINTENDENT);
                                          $allowed_users_trash = array(USER_ADVOCATE, USER_IN_PERSON, USER_CLERK, USER_DEPARTMENT);
                                          if (!empty(getSessionData('login')) && in_array(getSessionData('login')['ref_m_usertype_id'], $allowed_users_array) && getPendingCourtFee() == 0 || getPendingCourtFee() < 0) {
                                             $stages_array = array(Draft_Stage);
                 
                                             if (!empty(getSessionData('efiling_details')) && in_array(getSessionData('efiling_details')['stage_id'], $stages_array)) {
                                                 if (!empty(getSessionData('efiling_details')) && getSessionData('efiling_details')['ref_m_efiled_type_id'] == 1) {
                                                     if (in_array(NEW_CASE_COURT_FEE, explode(',', getSessionData('efiling_details')['breadcrumb_status']))) {
                                          //  pr(explode(',', getSessionData('efiling_details')['breadcrumb_status']));
                 
                                                         // if(count(explode(',', getSessionData('efiling_details')['breadcrumb_status'])) > 6){
                                                         if (getSessionData('login')['ref_m_usertype_id'] == USER_ADVOCATE) {
                                         ?>
                                                             <!-- <button class="quick-btn gradient-btn btn btn-success btn-sm efilaor" id='efilaor'> SUBMIT FOR EFILING </button> -->
                                                         <?php } else if (getSessionData('login')['ref_m_usertype_id'] == USER_CLERK) { } else {
                                                         ?>
                                                             <!-- <button class="quick-btn gradient-btn btn btn-success btn-sm efilpip" id=''> SUBMIT FOR EFILING </button> -->
                                                         <?php
                                                         }
                                                     // }
                                                     }
                                                 } else {
                                                     if (!empty(getSessionData('efiling_details')) && in_array(JAIL_PETITION_SUBORDINATE_COURT, explode(',', getSessionData('efiling_details')['breadcrumb_status']))) {
                                                         ?>
                                                         <!-- <a href="<?php echo base_url('jailPetition/FinalSubmit') ?>" class="quick-btn gradient-btn btn btn-success btn-sm" id='jail'> -->
                                                             <!-- SUBMIT FOR EFILING</a> -->
                                         <?php }
                                                 }
                                             }
                                         }
                                          $segment = service('uri');
                                          $stages_array = array(Initial_Defected_Stage, I_B_Defected_Stage);
                                          $allowed_users_array = array(USER_ADVOCATE, USER_IN_PERSON, USER_DEPARTMENT);
                                          if (in_array($_SESSION['login']['ref_m_usertype_id'], $allowed_users_array)) {
                                          if (!empty(getSessionData('efiling_details')) && in_array(getSessionData('efiling_details')['stage_id'], $stages_array)) {
                                             // echo '<div class="col-md-8"><h5>Please ensure that you have cured the defects notified by admin. Then only proceed with final submit.</h5></div>';
                                             if (in_array(NEW_CASE_COURT_FEE, explode(',', getSessionData('efiling_details')['breadcrumb_status']))) {
                                                if ($segment->getSegment(2) == 'view') {
                                                      // echo '<a href="' . base_url('newcase/finalSubmit') . '" class="quick-btn gradient-btn btn btn-success btn-sm">SUBMIT FOR RE-FILING </a>';
                                                }
                                             }
                                          }
                                          }
                                          ?>
                                    </div>
                                    <!-- <div class="col-12 col-sm-12 col-md-12 col-lg-12 my-3">
                                       <?php
                                          // if (isset($payment_details) && !empty($payment_details)) {
                                          //     render('shcilPayment.payment_list_view', ['payment_details' => $payment_details]);
                                          // }
                                          ?>
                                       </div> -->
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <!-- form--end  -->
                  </div>
               </div>
               <!-- tabs-section -start  -->
            </div>
         </div>
      </div>
      </div>
      </div>
      @if(getSessionData('login')['ref_m_usertype_id'] == USER_EFILING_ADMIN || getSessionData('login')['ref_m_usertype_id'] == USER_ADMIN)
      @endsection
   @endif
      <script src="<?= base_url() . 'assets/newAdmin/' ?>js/jquery351.min.js"></script>
      <script src="<?= base_url() . 'assets/newAdmin/' ?>js/bootstrap.bundle.min.js"></script>
      <script src="<?= base_url() . 'assets/newAdmin/' ?>js/general.js"></script>
      <script src="<?= base_url() . 'assets' ?>/vendors/jquery/dist/jquery.min.js"></script>
      <script src="<?= base_url() ?>assets/js/bootstrap-datepicker.min.js"></script>
      <script src="<?= base_url() ?>assets/js/sha256.js"></script>
      <script src="<?= base_url() ?>assets/newAdmin/js/jquery.dataTables.min.js"></script>
      <script src="<?= base_url() . 'assets' ?>/js/select2.min.js"></script>
      @stack('script')
      <script src="<?= base_url(); ?>assets/js/sweetalert.min.js"></script>
      <link rel="stylesheet" href="<?= base_url(); ?>assets/css/sweetalert.css">
      <?php
         $pending_court_fee = empty(getPendingCourtFee()) ? 0 : getPendingCourtFee();
         ?>
      <div class="modal fade" id="approveModal" role="dialog">
         <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
               <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title"><span class="fa fa-pencil"></span> Confirmation</h4>
               </div>
               <div class="modal-body">
                  <p>Are you sure to Approve this E-filing number ? </p>
               </div>
               <div class="modal-footer">
                  <a href="" class="btn btn-default">No</a>
                  <a href="<?php echo base_url('admin/efilingAction'); ?>" class="btn btn-success">Yes</a>
               </div>
            </div>
         </div>
      </div>
      <div class="modal fade" id="transferModal" role="dialog">
         <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
               <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title"><span class="fa fa-pencil"></span> Confirmation</h4>
               </div>
               <div class="modal-body">
                  <p>Are you sure to transfer this E-filing number to Sec-X? </p>
               </div>
               <div class="modal-footer">
                  <a href="" class="btn btn-default">No</a>
                  <a href="<?php echo base_url('admin/EfilingAction/transferCase'); ?>" class="btn btn-success">Yes</a>
               </div>
            </div>
         </div>
      </div>
      <div class="modal fade" id="disapproveModal" role="dialog">
         <div class="modal-dialog modal-lg">
            <div class="modal-content">
               <div class="modal-header">
                  <h4 class="modal-title">
                     <span class="fa fa-pencil">&nbsp;&nbsp;</span><?php echo $lbl = (isset($_SESSION['efiling_details']['ref_m_efiled_type_id']) && $_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_MENTIONING) ? "Write Orders" : "Write Reason to Disapprove"; ?>
                  </h4>
                  <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
               </div>
               <?php
                  $attribute = array('name' => 'disapp_case', 'id' => 'disapp_case', 'autocomplete' => 'off');
                  if (isset($_SESSION['efiling_details']['ref_m_efiled_type_id']) && $_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_MENTIONING) {
                      echo form_open(base_url('admin/EfilingAction/submit_mentioning_order'), $attribute);
                  } else {
                      echo form_open(base_url('admin/EfilingAction/disapprove_case'), $attribute);
                  }
                  ?>
               <div class="modal-body">
                  <div id="disapprove_alerts"></div>
                  <div class="clearfix"><br></div>
                  <div class="btn-toolbar editor" data-role="editor-toolbar" data-target="#editor-one">
                     <div class="btn-group">
                        <a class="btn" data-edit="bold" title="Bold (Ctrl/Cmd+B)"><i class="fa fa-bold"></i></a>
                        <a class="btn" data-edit="italic" title="Italic (Ctrl/Cmd+I)"><i class="fa fa-italic"></i></a>
                     </div>
                     <div class="btn-group">
                        <a class="btn" data-edit="insertunorderedlist" title="Bullet list"><i class="fa fa-list-ul"></i></a>
                        <a class="btn" data-edit="insertorderedlist" title="Number list"><i class="fa fa-list-ol"></i></a>
                     </div>
                     <div class="btn-group">
                        <a class="btn" data-edit="justifyleft" title="Align Left (Ctrl/Cmd+L)"><i class="fa fa-align-left"></i></a>
                        <a class="btn" data-edit="justifycenter" title="Center (Ctrl/Cmd+E)"><i class="fa fa-align-center"></i></a>
                        <a class="btn" data-edit="justifyright" title="Align Right (Ctrl/Cmd+R)"><i class="fa fa-align-right"></i></a>
                        <a class="btn" data-edit="justifyfull" title="Justify (Ctrl/Cmd+J)"><i class="fa fa-align-justify"></i></a>
                     </div>
                     <div class="btn-group">
                        <a class="btn" data-edit="undo" title="Undo (Ctrl/Cmd+Z)"><i class="fa fa-undo"></i></a>
                        <a class="btn" data-edit="redo" title="Redo (Ctrl/Cmd+Y)"><i class="fa fa-repeat"></i></a>
                     </div>
                  </div>
                  <div id="editor-one" class="editor-wrapper placeholderText disapprovedText" contenteditable="true"></div>
                  <textarea name="remark" id="descr" style="display: none;"></textarea>
                  <span id="disapprove_count_word" style="float:right"></span>
                  <div class="clearfix"><br></div>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <a class="btn btn-success" id="disapprove_me">Submit</a>
               </div>
               <?php echo form_close(); ?>
            </div>
         </div>
      </div>
      <!-- mark as error start-->
      <div class="modal fade" id="markAsErrorModal" role="dialog">
         <div class="modal-dialog modal-lg">
            <div class="modal-content">
               <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                  <h4 class="modal-title">
                     <span class="fa fa-pencil"></span>Write reason to mark as error.
                  </h4>
               </div>
               <?php
                  $attribute = array('name' => 'mark_as_error', 'id' => 'mark_as_error', 'autocomplete' => 'off');
                  echo form_open(base_url('admin/EfilingAction/markAsErrorCase'), $attribute);
                  ?>
               <div class="modal-body">
                  <div id="disapprove_alerts"></div>
                  <div class="clearfix"><br></div>
                  <div class="btn-toolbar editor" data-role="editor-toolbar" data-target="#editor-one">
                     <div class="btn-group">
                        <a class="btn" data-edit="bold" title="Bold (Ctrl/Cmd+B)"><i class="fa fa-bold"></i></a>
                        <a class="btn" data-edit="italic" title="Italic (Ctrl/Cmd+I)"><i class="fa fa-italic"></i></a>
                     </div>
                     <div class="btn-group">
                        <a class="btn" data-edit="insertunorderedlist" title="Bullet list"><i class="fa fa-list-ul"></i></a>
                        <a class="btn" data-edit="insertorderedlist" title="Number list"><i class="fa fa-list-ol"></i></a>
                     </div>
                     <div class="btn-group">
                        <a class="btn" data-edit="justifyleft" title="Align Left (Ctrl/Cmd+L)"><i class="fa fa-align-left"></i></a>
                        <a class="btn" data-edit="justifycenter" title="Center (Ctrl/Cmd+E)"><i class="fa fa-align-center"></i></a>
                        <a class="btn" data-edit="justifyright" title="Align Right (Ctrl/Cmd+R)"><i class="fa fa-align-right"></i></a>
                        <a class="btn" data-edit="justifyfull" title="Justify (Ctrl/Cmd+J)"><i class="fa fa-align-justify"></i></a>
                     </div>
                     <div class="btn-group">
                        <a class="btn" data-edit="undo" title="Undo (Ctrl/Cmd+Z)"><i class="fa fa-undo"></i></a>
                        <a class="btn" data-edit="redo" title="Redo (Ctrl/Cmd+Y)"><i class="fa fa-repeat"></i></a>
                     </div>
                  </div>
                  <div id="editor-one" class="editor-wrapper placeholderText disapprovedText" contenteditable="true"></div>
                  <textarea name="remark" id="descr" style="display: none;"></textarea>
                  <span id="disapprove_count_word" style="float:right"></span>
                  <div class="clearfix"><br></div>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <a class="btn btn-success" id="markaserror">Submit</a>
               </div>
               <?php echo form_close(); ?>
            </div>
         </div>
      </div>
      <!-- mark as error end-->
      <div class="modal fade" id="DeficitCourtFeeModal" role="dialog">
         <div class="modal-dialog" role="document">
            <div class="modal-content">
               <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
                  <h4 class="modal-title">
                     <span class="fa fa-pencil"></span> Deficit Fee
                  </h4>
               </div>
               <div class="modal-body">
                  <?php
                     $attribute = array('name' => 'deficit_court_fees', 'id' => 'deficit_court_fees', 'autocomplete' => 'off');
                     echo form_open('admin/deficit_court_fees', $attribute);
                     ?>
                  <div class="form-group">
                     <label class="control-label col-sm-4 input-sm">Deficit Fees to be paid<span style="color: red">*</span>:</label>
                     <div class="col-md-6 col-sm-12 col-xs-12">
                        <input class="form-control" name="court_fees" id="court_fees" maxlength="6" required placeholder="Enter court fee" type="text">
                     </div>
                  </div>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <input type="submit" class="btn btn-info" value="Submit" name="submit">
               </div>
               <?php echo form_close(); ?>
            </div>
         </div>
      </div>
      <div class="modal fade" id="generateDiaryNoDiv" role="dialog">
         <div class="modal-dialog">
            <div class="modal-content">
               <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title"><span class="fa fa-pencil"></span> Check All Details </h4>
               </div>
               <div class="modal-body" id="checkAllSections">
               </div>
               <div class="col-md-12 col-sm-12 col-xs-12" id="loader_div">
               </div>
               <a data-efilingType="<?php echo strtolower($filing_type); ?>" class="btn btn-primary" id="createDiaryNo" type="button" style="margin-left: 224px;margin-bottom: 23px;">Generate Diary No.</a>
            </div>
         </div>
      </div>
      <!-- Modal -->
      <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="padding-right: 15px;margin-left:-257px;">
         <div class="modal-dialog" role="document">
            <div class="modal-content" style="width: 927px;height: 300px;">
               <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Diary Details</h5>
                  <button type="button" class="close closeButton" data-dismiss="modal" data-close="1" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="modal-body">
                  <span id="customErrorMessage"></span>
               </div>
               <div class="modal-footer">
                  <button type="button" data-close="1" class="btn btn-secondary closeButton" data-dismiss="modal">Close</button>
                  <!--                <button type="button" class="btn btn-primary">Save changes</button>-->
               </div>
            </div>
         </div>
      </div>
      <div class="modal fade" id="holdModal" role="dialog">
         <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
               <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title"><span class="fa fa-pencil"></span> Confirmation</h4>
               </div>
               <div class="modal-body">
                  <p>Are you sure to Hold this E-filing number ? </p>
               </div>
               <div class="modal-footer">
                  <a href="" class="btn btn-default">No</a>
                  <a href="<?php echo base_url('admin/efilingAction/hold'); ?>" class="btn btn-success">Yes</a>
               </div>
            </div>
         </div>
      </div>
      <div class="modal fade" id="disposedModal" role="dialog">
         <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
               <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title"><span class="fa fa-pencil"></span> Confirmation</h4>
               </div>
               <div class="modal-body">
                  <p>Are you sure to Disposed this E-filing number ? </p>
               </div>
               <div class="modal-footer">
                  <a href="" class="btn btn-default">No</a>
                  <a href="<?php echo base_url('admin/efilingAction/disposed'); ?>" class="btn btn-success">Yes</a>
               </div>
            </div>
         </div>
      </div>
      <script src="<?= base_url() . 'assets/newAdmin/' ?>js/jquery351.min.js"></script>
      <script>
         function get_objection(obj_id, obj_checked) {
             if (obj_checked.is(':checked')) {
                 var obj = $("#obj_" + obj_id).text();
             }
             if (obj_checked.is(':unchecked')) {
                 var uncheck = $("#obj_" + obj_id).text() + "<br><br>";
             }
             $('#objection_value').append(obj + "<br><br>");
             var objection = $('#objection_value').html();
             if (objection.indexOf(uncheck) != -1) {
                 objection = objection.replace(uncheck, '');
                 $('#objection_value').html(objection);
             } else {
                 $('#objection_value').html(objection);
             }
         }
         $(document).ready(function() {
             // $(document).on("click", "#generateDiaryNoPop", function () {
             //     var sectionDetails ='';
             //     var sectionData ='';
             //     $("#checkAllSections").html('');
             //     $('ul.nav-breadcrumb li').each(function(i){
             //         i= i+1;
             //         var sectionDetails = $(this).text().trim();
             //         var sectionArr = sectionDetails.split(i);
             //         var name = sectionArr[1].trim().replace(/[-' ']/g,'_').toLowerCase();
             //         if(name == 'extra_party' || name == 'legal_representative'){
             //             sectionData +='<div class="row"><div class="col-sm-4">'+sectionDetails+'</div>' +
             //                 '<div class="col-sm-4"><input class="checkSection" type="radio" id="'+name+'" name="'+name+'" value="1"> <label for="'+name+'"> YES</label><span id="error_'+name+'"></span></div> ' +
             //                 '<div class="col-sm-2"><input  class="checkSection" type="radio" id="'+name+'1" name="'+name+'" value="0"> <label for="'+name+'1"> NO</label></div>' +
             //                 '<div class="col-sm-2"><input class="checkSection" type="radio" id="'+name+'2" name="'+name+'" value="2"> <label for="'+name+'2"> N/A</label></div></div>';
             //         }
             //         else{
             //             sectionData +='<div class="row"><div class="col-sm-4">'+sectionDetails+'</div>' +
             //                 '<div class="col-sm-4"><input class="checkSection" type="radio" id="'+name+'" name="'+name+'" value="1"> <label for="'+name+'"> YES</label><span id="error_'+name+'"></span></div> ' +
             //                 '<div class="col-sm-4"><input class="checkSection" type="radio" id="'+name+'1" name="'+name+'" value="0"> <label for="'+name+'1"> NO</label></div></div>';
             //         }
             //
             //          if(name == 'view' || name =='affirmation'){
             //          }
             //          else{
             //              $("#checkAllSections").append(sectionData);
             //          }
             //         sectionData = '';
             //     });
             // });
             // $(document).on('click','#createDiaryNo',function(){
             $(document).on('click', '#generateDiaryNoPop', function() {
                 var pending_court_fee = '<?= $pending_court_fee ?>';
                 if (pending_court_fee > 0) {
                     var court_fee_payment_details = $('#is_verified').val();
                     var result = court_fee_payment_details.split('#');
         
                     if ((result[0] === 'f' && result[1] > 0 && pending_court_fee > 0)) {
                         alert('Please verify court fee before generating diary number.');
                         return false;
                     }
                 }
                 /*var court_fee_payment_details = $('#is_verified').val();
                 var result = court_fee_payment_details.split('#');
         
                 if ((result[0] ==='f' && result[1]>0 && pending_court_fee > 0)){
                     alert('Please verify court fee before generating diary number.');
                     return false;
                 }*/
                 var noError = true;
                 var arr = [];
                 var stepValue = [];
                 var filteredData = [];
                 var typeGeneration = '';
                 var file_type = $(this).attr('data-efilingtype');
                 // $('.checkSection').each(function(){
                 //     var name = $(this).attr('name');
                 //     var fieldValue =  $('input[name="'+name+'"]:checked').val();
                 //     if(typeof fieldValue == 'undefined'){
                 //         noError = false;
                 //         arr.push(name);
                 //     }
                 //     else{
                 //         if(fieldValue =='0'){
                 //             $("#error_"+name).text('Select this field');
                 //             $("#error_"+name).css( { "margin-left" : "6px", "color": "red"} );
                 //             noError = false;
                 //         }
                 //         else{
                 //             $("#error_"+name).text('');
                 //             var checkObject = {};
                 //             checkObject.field_name = name;
                 //             checkObject.field_value=fieldValue;
                 //             stepValue.push(checkObject);
                 //             checkObject = {};
                 //         }
                 //     }
                 // });
                 // var arr = arr.filter(uniqueArr);
                 // const  keys = ['field_name'];
                 //  const  filteredData = stepValue.filter((s => o =>(k => !s.has(k) && s.add(k))
                 //             (keys.map(k => o[k]).join('|')))
                 //          (new Set)
                 //      );
                 //  if(arr){
                 //      for(var i=0;i<arr.length;i++){
                 //          $("#error_"+arr[i]).text('Select this field');
                 //          $("#error_"+arr[i]).css( { "margin-left" : "6px", "color": "red"} );
                 //          noError = false;
                 //      }
                 //  }
         
                 if (file_type == 'new_case') {
                     filteredData.push({
                         field_name: "case_detail",
                         field_value: "1"
                     });
                     filteredData.push({
                         field_name: "petitioner",
                         field_value: "1"
                     });
                     filteredData.push({
                         field_name: "respondent",
                         field_value: "1"
                     });
                     filteredData.push({
                         field_name: "extra_party",
                         field_value: "1"
                     });
                     filteredData.push({
                         field_name: "legal_representative",
                         field_value: "1"
                     });
                     filteredData.push({
                         field_name: "act_section",
                         field_value: "1"
                     });
                     filteredData.push({
                         field_name: "earlier_courts",
                         field_value: "1"
                     });
                     filteredData.push({
                         field_name: "upload_document",
                         field_value: "1"
                     });
                     filteredData.push({
                         field_name: "index",
                         field_value: "1"
                     });
                     filteredData.push({
                         field_name: "pay_court_fee",
                         field_value: "1"
                     });
                     typeGeneration = 'diary no.';
                 } else if (file_type == 'caveat') {
                     filteredData.push({
                         field_name: "caveator",
                         field_value: "1"
                     });
                     filteredData.push({
                         field_name: "caveatee",
                         field_value: "1"
                     });
                     filteredData.push({
                         field_name: "extra_party",
                         field_value: "1"
                     });
                     filteredData.push({
                         field_name: "subordinate_court",
                         field_value: "1"
                     });
                     filteredData.push({
                         field_name: "upload_document",
                         field_value: "1"
                     });
                     filteredData.push({
                         field_name: "index",
                         field_value: "1"
                     });
                     filteredData.push({
                         field_name: "pay_court_fee",
                         field_value: "1"
                     });
                     typeGeneration = 'caveat no.';
                 }
                 var conformRes = confirm("Are you sure want to generate " + typeGeneration + " ?");
                 if (noError && file_type && conformRes) {
                     var CSRF_TOKEN = 'CSRF_TOKEN';
                     var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                     var postData = {
                         CSRF_TOKEN: CSRF_TOKEN_VALUE,
                         file_type: file_type
                     };
                     $.ajax({
                         type: "POST",
                         data: JSON.stringify(postData),
                         url: "<?php echo base_url('newcase/Ajaxcalls/getAllFilingDetailsByRegistrationId'); ?>",
                         dataType: 'json',
                         ContentType: 'application/json',
                         cache: false,
                         async: false,
                         beforeSend: function() {
                             $("#loader_div").html('<img id="loader_img" style="position: fixed; left: 63%;margin-top: -164px;  margin-left: -100px;" src="<?php echo base_url('assets/images/loading-data.gif'); ?>">');
                             $('#createDiaryNo').append('<i class="status_refresh fa fa-refresh fa-spin"></i>');
                         },
                         success: function(data) {
                             if (typeof data == 'string') {
                                 data = JSON.parse(data);
                             }
         
                             // return false;
                             if (data) {
                                 $("#exampleModal").modal('show');
                                 $('#generateDiaryNoDiv').modal('hide');
                                 var diaryStatus = '';
                                 var diaryNo = '';
                                 var insertData = {};
                                 var status = '';
                                 var diaryData = '';
                                 var alloted_to = '';
                                 var insertedDocNums = '';
                                 if (data.status == 'SUCCESS') {
                                     //alert('here');
                                     diaryStatus = 'new_diary';
                                     if (data.diary_no) {
                                         diaryNo = data.diary_no;
                                     }
                                     if (data.alloted_to) {
                                         alloted_to = data.alloted_to;
                                     }
                                     if (data.insertedDocNums) {
                                         insertedDocNums = data.insertedDocNums;
                                     }
                                     if (data.status) {
                                         status = data.status;
                                     }
                                     if (data.records_inserted) {
                                         insertData.records_inserted = data.records_inserted;
                                     }
                                     insertData.diaryNo = diaryNo;
                                     insertData.alloted_to = alloted_to;
                                     insertData.insertedDocNums = insertedDocNums;
                                     insertData.status = status;
                                     insertData.selectedcheckBox = filteredData;
                                     insertData.diaryStatus = diaryStatus;
                                 } else if (data.status == 'ERROR_ALREADY_IN_ICMIS') {
                                     var errorMessage = data.error.split(" ");
                                     diaryData = errorMessage.pop();
                                     diaryStatus = 'exist_diary';
                                     insertData.records_inserted = {};
                                     if (data.status) {
                                         status = data.status;
                                     }
                                     if (data.records_inserted) {
                                         insertData.records_inserted = data.records_inserted;
                                     }
                                     insertData.diaryNo = diaryData;
                                     insertData.status = status;
                                     insertData.selectedcheckBox = filteredData;
                                     insertData.diaryStatus = diaryStatus;
                                 } else if (data.status == 'ERROR_MAIN') {
                                     $("#customErrorMessage").html('');
                                     $("#customErrorMessage").html(data.error);
                                     $("#customErrorMessage").css('color', 'green');
                                     $("#customErrorMessage").css({
                                         'font-size': '30px'
                                     });
                                     return false;
                                 } else if (data.status == 'ERROR_CAVEAT') {
                                     $("#customErrorMessage").html('');
                                     $("#customErrorMessage").html(data.error);
                                     $("#customErrorMessage").css('color', 'green');
                                     $("#customErrorMessage").css({
                                         'font-size': '30px'
                                     });
                                     return false;
                                 }
                                 if (insertData) {
                                     if (file_type == 'new_case') {
                                         $('#createDiaryNo').html('Generate Diary No.');
                                         var errorMessage = 'Diary no. generation has been successfully!';
                            
                                     } else {
                                         $('#createDiaryNo').html('Generate Caveat No.');
                                         var errorMessage = 'Caveat no. generation has been successfully!';
                                     }
         
                                     $("#customErrorMessage").html('');
                                     $("#customErrorMessage").html(errorMessage);
                                     $("#customErrorMessage").css('color', 'green');
                                     $("#customErrorMessage").css({
                                         'font-size': '30px'
                                     });
                                     $.ajax({
                                         type: "POST",
                                         data: JSON.stringify(insertData),
                                         url: "<?php echo base_url('newcase/Ajaxcalls/updateDiaryDetails'); ?>",
                                         dataType: 'json',
                                         ContentType: 'application/json',
                                         cache: false,
                                         beforeSend: function() {
                                             $('#createDiaryNo').append('<i class="status_refresh fa fa-refresh fa-spin"></i>');
                                         },
                                         success: function(updateData) {
                                         
                                             // return false;
                                             $("#loader_div").html('');
         
                                             if (updateData.success == 'success') {
                                                 $("#customErrorMessage").html('');
                                                 $("#customErrorMessage").html(updateData.message);
                                             } else if (updateData.success == 'error') {
                                                 $("#customErrorMessage").html('');
                                                 $("#customErrorMessage").html(updateData.message);
                                             } else {
                                                 $("#customErrorMessage").html('');
                                                 $("#customErrorMessage").html(updateData.message);
                                             }
                                         }
                                     });
                                 }
         
                                 // else {
                                 //         var errorMessage = data.error;
                                 //         $("#customErrorMessage").html('');
                                 //         $("#customErrorMessage").html(errorMessage);
                                 //         $("#customErrorMessage").css('color','red');
                                 //         $("#customErrorMessage").css({'font-size': '15px'});
                                 //         $('#createDiaryNo').html('Generate Diary No.');
                                 // }
                             }
                             $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                                 $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                             });
                         },
                         error: function() {
                             $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                                 $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                             });
                         }
                     });
                 }
         
             });
         
             $(document).on('click', '.closeButton', function() {
                 var closeButtonAttr = $(this).attr('data-close');
                 if (closeButtonAttr == 1) {
                     window.location.reload();
                 }
             });
         
             $('#disapp_case input').on('change', function() {
                 var checkedValue = $('input[name=crt_fee_status]:checked', '#disapp_case').val();
                 $('#disapprove_alerts').hide();
                 $('.change_div_color').css('background-color', '');
                 if (checkedValue == '<?php echo url_encryption(3); ?>') {
                     $('#def_crt_fee').focus();
                     $('#def_crt_fee').attr('required', true);
                     $('#def_crt_fee').attr('disabled', false);
         
                 } else {
                     $('#def_crt_fee').attr('required', false);
                     $('#def_crt_fee').attr('disabled', true);
                     $('#def_crt_fee').val('');
                 }
         
             });
         
         
             $('#disapprove_me').click(function() {
         
                 var temp = $('.disapprovedText').text();
                 temp = $.trim(temp);
                 var efiling_type_id = '<?php 
            if(isset($_SESSION['efiling_details']['ref_m_efiled_type_id'])){
                echo $_SESSION['efiling_details']['ref_m_efiled_type_id']; 
            }else{
                echo '' ;
            }
            ?>';
         
         
                 if (efiling_type_id != "") {
                     $('#disapprove_alerts').show();
         
                     if (temp.length > 500) {
                         $('#disapprove_alerts').show();
                         $('#disapprove_alerts').html("<p class='message invalid' id='msgdiv'>Maximum length 500 allowed. <span class='close' onclick=hideMessageDiv()>X</span></p>");
                     } else {
                         if ($('#objection_value').html() != '') {
                             $('#obj_remarks').val($('#objection_value').html());
                         }
                         $('#descr').val($('#editor-one').html());
                         $('#disapp_case').submit();
                     }
         
         
                 }
             });
         
             $('#markaserror').click(function() {
                 var temp = $('.disapprovedText').text();
                 temp = $.trim(temp);
                 var efiling_type_id = '<?php 
            if(isset($_SESSION['efiling_details']['ref_m_efiled_type_id'])){
                echo $_SESSION['efiling_details']['ref_m_efiled_type_id']; 
            }else{
                echo '' ;
            }
            // echo $_SESSION['efiling_details']['ref_m_efiled_type_id']; 
            ?>';
                 if (efiling_type_id != "") {
                     if (temp.length == 0) {
                         alert("Please fill error note.");
                         $('.disapprovedText').focus();
                         return false;
                     } else {
                         $('#disapprove_alerts').show();
                         if (temp.length > 500) {
                             $('#disapprove_alerts').show();
                             $('#disapprove_alerts').html("<p class='message invalid' id='msgdiv'>Maximum length 500 allowed. <span class='close' onclick=hideMessageDiv()>X</span></p>");
                         } else {
                             $('textarea#descr').text($('.disapprovedText').text());
                             $('#mark_as_error').submit();
                         }
                     }
                 }
             });
             //update fee and send to icmis
             $(document).on('click', '#transferToScrutiny', function(e) {
               e.preventDefault();
               e.stopImmediatePropagation();
                 var registration_id = $(this).attr('data-registration_id');
                 if (registration_id) {
                     var CSRF_TOKEN = 'CSRF_TOKEN';
                     var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                     var postData = {
                         CSRF_TOKEN: CSRF_TOKEN_VALUE,
                         registration_id: registration_id
                     };
                     $.ajax({
                         type: "POST",
                         data: JSON.stringify(postData),
                         url: "<?php echo base_url('admin/EfilingAction/updateRefiledCase'); ?>",
                         dataType: 'json',
                         ContentType: 'application/json',
                         cache: false,
                         beforeSend: function() {
                             $("#loader_div").html('<img id="loader_img" style="position: fixed; left: 63%;margin-top: -164px;  margin-left: -100px;" src="<?php echo base_url('assets/images/loading-data.gif'); ?>">');
                             $('#createDiaryNo').append('<i class="status_refresh fa fa-refresh fa-spin"></i>');
                         },
                         success: function(res) {
                             if (typeof res == 'string') {
                                 res = JSON.parse(res);
                             }
                             if (res.status == 'success') {
                                 alert(res.message);
                                 window.location.reload();
                             } else {
                                 alert(res.message);
                                 window.location.reload();
                             }
                             $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                                 $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                             });
                         },
                         error: function() {
                             $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                                 $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                             });
                         }
                     });
                 }
             });
         
         });
         $('#disapproveModal').on('hidden.bs.modal', function(e) {
             $(this).find('form').trigger('reset');
             $('#disapprove_alerts').hide();
             $('.change_div_color').css('background-color', '');
             $('.error-tip').hide();
         });
         $('#markAsErrorModal').on('hidden.bs.modal', function(e) {
             $(this).find('form').trigger('reset');
             $('#disapprove_alerts').hide();
             $('.change_div_color').css('background-color', '');
             $('.error-tip').hide();
         });
         
         function uniqueArr(value, index, self) {
             return self.indexOf(value) === index;
         }
         
         function getAmtValue(amt) {
             //var regex = /^[0-9]*\.?[0-9]*$/;
             var regex = /^[0-9]*$/;
             if (regex.test(amt)) {
                 return true;
             } else {
                 $('#def_crt_fee').val('');
                 return false;
             }
         }
      </script>
      <style>
         .obj_wrapper {
         min-height: 140px;
         max-height: 180px;
         background-color: #fff;
         border-collapse: separate;
         border: 1px solid #ccc;
         padding: 4px;
         box-sizing: content-box;
         box-shadow: rgba(0, 0, 0, .07451) 0 1px 1px 0 inset;
         overflow-y: scroll;
         outline: 0;
         border-radius: 3px;
         }
         .editor-wrapper {
         min-height: 200px !important;
         max-height: 250px;
         }
      </style>
      <script>
         function toggleAllAccordions() {
             var button = document.getElementById("collapseAll");
             var accordionHeaders = document.querySelectorAll(".accordion-header button");
             var accordionCollapses = document.querySelectorAll(".accordion-collapse");
             var isCollapsed = Array.from(accordionHeaders).some(function (header) {
                 return !header.classList.contains("collapsed");
             });
             if (isCollapsed) {
                 button.innerHTML = "Expand all";
                 accordionHeaders.forEach(function (header) {
                     header.classList.add("collapsed");
                 });
                 accordionCollapses.forEach(function (collapse) {
                     collapse.classList.remove("show");
                 });
             } else {
                 button.innerHTML = "Collapse all";
                 accordionHeaders.forEach(function (header) {
                     header.classList.remove("collapsed");
                 });
                 accordionCollapses.forEach(function (collapse) {
                     collapse.classList.add("show");
                 });
             }
         }
      </script>
      <script type="text/javascript">
         $("#cde").click(function() {
             $.ajax({
                 url: "<?php echo base_url('newcase/finalSubmit/valid_cde'); ?>",
                 success: function(data) {
                     var dataas = data.split('?');
                     var ct = dataas[0];
                     var dataarr = dataas[1].slice(1).split(',');
                     if ((dataarr[0] != 2) && (dataarr[0] != 3) && (dataarr[0] != 6) && (dataarr[0] != 7)) {
                         window.location.href = "<?php echo base_url('newcase/finalSubmit/forcde') ?>";
                     }
                     if ((dataarr[0]) == 2) {
                         alert("Petitioner details are mandatory to fill. Redirecting to page !!");
                         window.location.href = "<?php echo base_url('newcase/petitioner') ?>";
                     }
         
                     if ((dataarr[0]) == 3) {
                         alert("Respondent details are mandatory to fill. Redirecting to page !!");
                         window.location.href = "<?php echo base_url('newcase/respondent') ?>";
                     }
         
                     if (((dataarr[0]) == 7) && (ct != 5) && (ct != 6) && (ct != 7) && (ct != 8)) {
                         alert("Subordinate_courts details are mandatory to fill. Redirecting to page !!");
                         window.location.href = "<?php echo base_url('newcase/subordinate_court') ?>";
                     }
         
                 },
                 error: function() {
                     $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                         $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                     });
                 }
         
         
             });
             return false;
         });
         // $(".efilaor").click(function() {
         //     $.ajax({
         //         url: "<?php echo base_url('newcase/AutoDiary/valid_efil'); ?>", // enabled this for auto diary generation
         //         success: function(data) {
         //             var dataas = data.split('?');
         //             var ct = dataas[0];
         //             var dataarr = dataas[1].slice(1).split(',');
         //             if ((dataarr[0] != 2) && (dataarr[0] != 3) && (dataarr[0] != 7) && (dataarr[0]) != 8 && (dataarr[0]) != 10) {
         //                 alert("All Completed");
         //                 // window.location.href = "<?php echo base_url('newcase/AutoDiary') ?>"; //ENABLED THIS FOR AUTO DIARY
         //                 showLoaderAndRedirect("<?php echo base_url('newcase/AutoDiary') ?>");
         //             }
         //             if ((dataarr[0]) == 2) {
         //                 alert("Petitioner details are mandatory to fill. Redirecting to page !!");
         //                 // window.location.href = "<?php echo base_url('newcase/petitioner') ?>";
         //                 showLoaderAndRedirect("<?php echo base_url('newcase/petitioner') ?>");
         //             }
         //             if ((dataarr[0]) == 3) {
         //                 alert("Respondent details are mandatory to fill. Redirecting to page !!");
         //                 // window.location.href = "<?php echo base_url('newcase/respondent') ?>";
         //                 showLoaderAndRedirect("<?php echo base_url('newcase/respondent') ?>");
         //             }
         //             if (((dataarr[0]) == 7) && (ct != 5) && (ct != 6) && (ct != 7) && (ct != 8)) {
         //                 alert("Subordinate_courts details are mandatory to fill. Redirecting to page !!");
         //                 // window.location.href = "<?php echo base_url('newcase/subordinate_court') ?>";
         //                 showLoaderAndRedirect("<?php echo base_url('newcase/subordinate_court') ?>");
         //             }
         //             if (((dataarr[0]) == 8)) {
         //                 alert("Documents are not uploaded. Redirecting to page !!");
         //                 // window.location.href = "<?php echo base_url('newcase/uploadDocuments') ?>";
         //                 showLoaderAndRedirect("<?php echo base_url('newcase/uploadDocuments') ?>");
         //             }
         //             if (((dataarr[0]) == 10)) {
         //                 alert("Court Fees not paid. Redirecting to page !!");
         //                 // window.location.href = "<?php echo base_url('newcase/courtFee') ?>";
         //                 showLoaderAndRedirect("<?php echo base_url('newcase/courtFee') ?>");
         //             }
         //         },
         //         error: function() {
         //             $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
         //                 $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
         //             });
         //         }
         //     });
         //     return false;
         // });
         
         function showLoaderAndRedirect(url) {
             // Show the loader
             document.getElementById('loader-wrapper').style.display = 'flex';
         
             // Delay to ensure the loader is visible for a moment
             setTimeout(function() {
                 window.location.href = url;
             }, 500); // Adjust delay as needed
         }
         // $("#efilpip").click(function() {
         //     $.ajax({
         //         url: "<?php echo base_url('newcase/finalSubmit/valid_efil'); ?>",
         //         success: function(data) {
         //             var dataas = data.split('?');
         //             var ct = dataas[0];
         //             var dataarr = dataas[1].slice(1).split(',');
         //             if ((dataarr[0] != 2) && (dataarr[0] != 3) && (dataarr[0] != 7) && (dataarr[0]) != 8 && (dataarr[0]) != 10) {
         //                 alert("all completed");
         //                //  window.location.href = "<?php echo base_url('newcase/finalSubmit') ?>";
         //                showLoaderAndRedirect("<?php echo base_url('newcase/finalSubmit') ?>");
         //             }
         //             if ((dataarr[0]) == 2) {
         //                 alert("Petitioner details are mandatory to fill. Redirecting to page !!");
         //                 window.location.href = "<?php echo base_url('newcase/petitioner') ?>";
         //             }
         //             if ((dataarr[0]) == 3) {
         //                 alert("Respondent details are mandatory to fill. Redirecting to page !!");
         //                 window.location.href = "<?php echo base_url('newcase/respondent') ?>";
         //             }
         //             if (((dataarr[0]) == 7) && (ct != 5) && (ct != 6) && (ct != 7) && (ct != 8)) {
         //                 alert("Subordinate_courts details are mandatory to fill. Redirecting to page !!");
         //                 window.location.href = "<?php echo base_url('newcase/subordinate_court') ?>";
         //             }
         //             if (((dataarr[0]) == 8)) {
         //                 alert("Documents are not uploaded. Redirecting to page !!");
         //                 window.location.href = "<?php echo base_url('newcase/uploadDocuments') ?>";
         //             }
         //             if (((dataarr[0]) == 10)) {
         //                 alert("Court Fees not paid. Redirecting to page !!");
         //                 window.location.href = "<?php echo base_url('newcase/courtFee') ?>";
         //             }
         //         },
         //         error: function() {
         //             $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
         //                 $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
         //             });
         //         }
         //     });
         //     return false;
         // });
         // $(".efilpip").click(function() {
         //     $.ajax({
         //         url: "<?php echo base_url('newcase/finalSubmit/valid_efil'); ?>",
         //         success: function(data) {
         //             var dataas = data.split('?');
         //             var ct = dataas[0];
         //             var dataarr = dataas[1].slice(1).split(',');
         //             if ((dataarr[0] != 2) && (dataarr[0] != 3) && (dataarr[0] != 7) && (dataarr[0]) != 8 && (dataarr[0]) != 10) {
         //                 alert("all completed");
         //                //  window.location.href = "<?php echo base_url('newcase/finalSubmit') ?>";
         //                showLoaderAndRedirect("<?php echo base_url('newcase/finalSubmit') ?>");
         //             }
         //             if ((dataarr[0]) == 2) {
         //                 alert("Petitioner details are mandatory to fill. Redirecting to page !!");
         //                 window.location.href = "<?php echo base_url('newcase/petitioner') ?>";
         //             }
         //             if ((dataarr[0]) == 3) {
         //                 alert("Respondent details are mandatory to fill. Redirecting to page !!");
         //                 window.location.href = "<?php echo base_url('newcase/respondent') ?>";
         //             }
         //             if (((dataarr[0]) == 7) && (ct != 5) && (ct != 6) && (ct != 7) && (ct != 8)) {
         //                 alert("Subordinate_courts details are mandatory to fill. Redirecting to page !!");
         //                 window.location.href = "<?php echo base_url('newcase/subordinate_court') ?>";
         //             }
         //             if (((dataarr[0]) == 8)) {
         //                 alert("Documents are not uploaded. Redirecting to page !!");
         //                 window.location.href = "<?php echo base_url('newcase/uploadDocuments') ?>";
         //             }
         //             if (((dataarr[0]) == 10)) {
         //                 alert("Court Fees not paid. Redirecting to page !!");
         //                 window.location.href = "<?php echo base_url('newcase/courtFee') ?>";
         //             }
         //         },
         //         error: function() {
         //             $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
         //                 $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
         //             });
         //         }
         //     });
         //     return false;
         // });
         $("#jail").click(function() {
             $.ajax({
                 url: "<?php echo base_url('jailPetition/FinalSubmit/validate'); ?>",
                 success: function(data) {
                     var dataarr = data.slice(1).split(',');
                     if ((dataarr[0] != 1) && (dataarr[0] != 3)) {
                         window.location.href = "<?php echo base_url('jailPetition/FinalSubmit') ?>";
                     }
                     if ((dataarr[0]) == 1) {
                         alert("Basic Case details are mandatory to fill. Redirecting to page !!");
                         window.location.href = "<?php echo base_url('jailPetition/BasicDetails') ?>";
                     }
                     if ((dataarr[0]) == 3) {
                         alert("Earlier Court Details are mandatory to fill. Redirecting to page !!");
                         window.location.href = "<?php echo base_url('jailPetition/Subordinate_court') ?>";
                     }
                 },
                 error: function() {
                     $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                         $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                     });
                 }
             });
             return false;
         });
         var base_url = "<?php echo base_url(); ?>";
         $(document).on('click', '.verifyFeeData', function() {
             var type = $.trim($(this).attr('data-actionType'));
             var receiptNumber = $.trim($(this).attr('data-transaction_num'));
             //alert(receiptNumber);return false;
             //var receiptNumber ='DLCT0801D2121P';
             var diaryNo = $.trim($(this).attr('data-diaryNo'));
             var diaryYear = $.trim($(this).attr('data-diaryYear'));
             //alert('type='+ type + '  receiptNumber=' + receiptNumber + '  diaryNo='+ diaryNo + '  diaryYear='+ diaryYear);
             if (type == 'lock') {
                 if (diaryNo === '' && diaryYear === '') {
                     alert('Please generate diary number first then try to lock court fee.');
                     return false;
                 }
             }
         
             if (type && receiptNumber) {
                 var CSRF_TOKEN = 'CSRF_TOKEN';
                 var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                 $('.form-responce').remove();
                 $('.status_refresh').html('');
                 $(this).append(' <i class="status_refresh fa fa-refresh fa-spin"></i>');
                 var postData = {
                     CSRF_TOKEN: CSRF_TOKEN_VALUE,
                     type: type,
                     receiptNumber: receiptNumber,
                     diaryNo: diaryNo,
                     diaryYear: diaryYear
                 };
                 $.ajax({
                     type: "POST",
                     url: base_url + "newcase/FeeVerifyLock_Controller/feeVeryLock",
                     data: JSON.stringify(postData),
                     cache: false,
                     async: false,
                     dataType: 'json',
                     contentType: 'application/json',
                     success: function(data) {
                         var status = (data.status);
                         if (type == 'verify') {
                             var RPSTATUS = (data.res.CERTRPDTL.RPSTATUS);
                             var RCPTNO = (data.res.CERTRPDTL.RCPTNO);
                             var DTISSUE = (data.res.CERTRPDTL.DTISSUE);
                             var CFAMT = (data.res.CERTRPDTL.CFAMT);
                             var STATUS = (data.res.CERTRPDTL.STATUS);
                             $('#Verify' + receiptNumber).hide();
                             $('#Verified' + receiptNumber).show();
                             $('#Verifiedlock' + receiptNumber).show();
                             $('#VerifiedLocked' + receiptNumber).hide();
                             //alert(data.res.CERTRPDTL.RPSTATUS);
                             if (RPSTATUS == 'FAIL') {
                                 $('#RPSTATUS').css('color', 'red');
                                 $('#STATUS').css('color', 'red');
                                 $('.STATUS').css('color', 'red');
                                 $('#fee_type').css('color', 'red');
                                 $('#STATUS_text').html('Reason');
                             } else {
                                 $('#RPSTATUS').css('color', 'green');
                                 $('#fee_type').css('color', 'green');
                                 $('.STATUS').css('color', 'green');
                                 $('#STATUS_text').html('');
                             }
                             $('.DTISSUE').show();
                             $('.CFAMT').show();
                             $('.STATUS').show();
                             $('#fee_type').html('Verify');
                             $('#RPSTATUS').html(RPSTATUS);
                             $('#receiptNumber').html(RCPTNO);
         
                             $('#DTISSUE').html(DTISSUE);
                             $('#CFAMT').html(CFAMT);
                             $('#STATUS').html(STATUS);
         
         
                             $('#diaryNumberYear').html('');
                             $('#CFLNAME').html('');
                             $('.diaryNumberYear').hide();
                             $('.CFLNAME').hide();
         
         
                             if (RPSTATUS == 'SUCCESS' && status == true && RCPTNO == receiptNumber) {
                                 var result = ('type ' + type + '  RPSTATUS=' + RPSTATUS + '  status=' + status + '  RCPTNO=' + RCPTNO + '  receiptNumber=' + receiptNumber);
                             } else {
                                 var result = ('type ' + type + ' verify Failed  ' + 'RPSTATUS=' + RPSTATUS + '  status=' + status + '  RCPTNO=' + RCPTNO + '  receiptNumber=' + receiptNumber);
                             }
         
                         } else {
                             var RPSTATUS = (data.res.LOCKTXN.LOCKRPDTL.RPSTATUS);
                             var RCPTNO = (data.res.LOCKTXN.LOCKRPDTL.RCPTNO);
                             var CFLNAME = (data.res.LOCKTXN.LOCKRPDTL.CFLNAME);
                             var diary_Year = (data.res.LOCKTXN.LOCKRPDTL.DIRYEAR);
         
                             var diary_No = (data.res.TXNHDR.DIRNO);
                             var slash = '/';
                             var diaryNumberYear = diary_No + slash + diary_Year;
         
                             //var RPSTATUS = (data.res.RPSTATUS);
                             // var RCPTNO = (data.res.RCPTNO);
                             $('#Verify' + receiptNumber).hide();
                             $('#Verified' + receiptNumber).show();
                             $('#Verifiedlock' + receiptNumber).hide();
                             $('#VerifiedLocked' + receiptNumber).show();
                             // alert(data.res.RPSTATUS);
                             if (RPSTATUS != 'SUCCESS') {
                                 $('#RPSTATUS').css('color', 'red');
                                 $('#fee_type').css('color', 'red');
                             } else {
                                 $('#RPSTATUS').css('color', 'green');
                                 $('#fee_type').css('color', 'green');
                             }
                             $('.diaryNumberYear').show();
                             $('.CFLNAME').show();
         
                             $('#fee_type').html('Locking');
                             $('#RPSTATUS').html(RPSTATUS);
                             $('#receiptNumber').html(RCPTNO);
                             $('#diaryNumberYear').html(diaryNumberYear);
                             $('#CFLNAME').html(CFLNAME);
         
                             $('#DTISSUE').html('');
                             $('#CFAMT').html('');
                             $('#STATUS').html('');
                             $('.DTISSUE').hide();
                             $('.CFAMT').hide();
                             $('.STATUS').hide();
         
         
         
         
         
                             if (status == true && RCPTNO == receiptNumber) {
                                 var result = ('type ' + type + '  RPSTATUS=' + RPSTATUS + '  status=' + status + '  RCPTNO=' + RCPTNO + '  receiptNumber=' + receiptNumber + '  Diary Number=' + diary_No + '/' + diary_Year + '  CFLNAME=' + CFLNAME);
                             } else {
                                 var result = ('type ' + type + ' verify Failed  ' + 'RPSTATUS=' + RPSTATUS + '  status=' + status + '  RCPTNO=' + RCPTNO + '  receiptNumber=' + receiptNumber);
                             }
                         }
         
                         // alert(result);
         
                         //$("#VerifyModalResult").html(result);
                         $("#VerifyModal").modal('show');
         
                         $('.status_refresh').remove();
                         $.getJSON(base_url + "csrftoken", function(result) {
                             $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                         });
                     }
                 });
             }
             //location.reload();
         });
         
         
         
         var base_url = '<?php echo base_url(); ?>';
         $(".check_stock_holding_payment_status").click(function () {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            var order_id = $(this).attr('data-order-id');
            $('.form-responce').remove();
            $(this).append(' <i class="status_refresh fa fa-refresh fa-spin"></i>');
            $.ajax({
                  type: "POST",
                  data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, order_id: order_id},
                  url: '<?php echo base_url('shcilPayment/paymentCheckStatus'); ?>',
                  // url: base_url + "shcilPayment/paymentCheckStatus",
                  success: function (data) {
                     $('.status_refresh').remove();
                     console.log(data);
                     if (data=='SUCCESS|Status Successfully Updated.'){
                        alert('Status Successfully Updated.');
                     }
                     window.location.reload();
                     $.getJSON(base_url + "csrftoken", function (result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                     });
                  }
            });
         });
         
         function ActionToTrash(trash_type) {
             event.preventDefault();
             var trash_type = trash_type;
             var url = "";
             if (trash_type == '') {
                 swal("Cancelled", "Your imaginary file is safe :)", "error");
                 return false;
             } else if (trash_type == 'UAT') {
                 url = "<?php echo base_url('userActions/trash'); ?>";
             } else if (trash_type == 'SLT') {
                 url = "<?php echo base_url('stage_list/trash'); ?>";
             } else if (trash_type == 'EAT') {
                 url = "<?php echo base_url('userActions/trash'); ?>";
             } else {
                 swal("Cancelled", "Your imaginary file is safe :)", "error");
                 return false;
             }
             //    alert('trash_type'+trash_type+'url='+url);//return false;
             swal({
                     title: "Do you really want to trash this E-Filing,",
                     text: "once it will be trashed you can't restore the same.",
                     type: "warning",
                     position: "top",
                     showCancelButton: true,
                     confirmButtonColor: "green",
                     confirmButtonText: "Yes",
                     cancelButtonText: "No",
                     buttons: ["Make Changes", "Yes!"],
                     closeOnConfirm: false,
                     closeOnCancel: true
                 },
                 function(isConfirm) {
                     if (isConfirm) { // submitting the form when user press yes
                         var link = document.createElement("a");
                         link.href = url;
                         link.target = "_self";
                         link.click();
                         swal({
                             title: "Deleted!",
                             text: "E-Filing has been deleted.",
                             type: "success",
                             timer: 2000
                         });
         
                     } else {
                         //swal({title: "Cancelled",text: "Your imaginary file is safe.",type: "error",timer: 1300});
                     }
         
                 });
         }
      </script>
      <script type="text/javascript">
         $(document).ready(function () {
             var oTable = $("#datatable-defects").dataTable();
         
             $("#checkAll").change(function () {
                 oTable.$("input[type=\'checkbox\']").prop("checked", $(this).prop("checked"));
             });
         });
         function setCuredDefect(id) {
             var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
             var value = $("#" + id).is(":checked");
             $.ajax({
                 type: 'POST',
                 url: '<?php echo base_url("documentIndex/Ajaxcalls/markCuredDefect"); ?>',
                 data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, objectionId: id, val:value},
                 success: function (data) {
                     $("#row"+id).toggleClass("curemarked");
                 },
                 error: function () {alert("failed");}
             });
         }
         
         function checkAll(){
             $(".checkOneByOne").prop("checked", $(this).prop("checked"));
             jQuery('.checkOneByOne').each(function(index, currentElement) {
                 var value = currentElement.id;
                 setCuredDefect(value);
             });
         }
      </script>
   </body>
</html>