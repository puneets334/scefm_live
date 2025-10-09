<?php
$this->load->view('new_case_breadcrumb');

if (isset($_SESSION['efiling_for_details']['case_type_pet_title']) && !empty($_SESSION['efiling_for_details']['case_type_pet_title'])) {
    $case_type_pet_title = htmlentities($_SESSION['efiling_for_details']['case_type_pet_title'], ENT_QUOTES);
} elseif (isset($efiling_civil_data[0]['case_type_pet_title']) && !empty($efiling_civil_data[0]['case_type_pet_title'])) {
    $case_type_pet_title = htmlentities($efiling_civil_data[0]['case_type_pet_title'], ENT_QUOTES);
} else {
    $case_type_pet_title = htmlentities('Complainant / Petitioner', ENT_QUOTES);
}


if (isset($_SESSION['efiling_for_details']['case_type_res_title']) && !empty($_SESSION['efiling_for_details']['case_type_res_title'])) {
    $case_type_res_title = htmlentities($_SESSION['efiling_for_details']['case_type_res_title'], ENT_QUOTES);
} elseif (isset($efiling_civil_data[0]['case_type_res_title']) && !empty($efiling_civil_data[0]['case_type_res_title'])) {
    $case_type_res_title = htmlentities($efiling_civil_data[0]['case_type_res_title'], ENT_QUOTES);
} else {
    $case_type_res_title = htmlentities('Accused / Respondent', ENT_QUOTES);
}


if ($_SESSION['breadcrumb_enable']['efiling_type'] == E_FILING_TYPE_CDE) {
    $lbl_efiling_for = 'CDE For : ';
    $lbl_efiling_details_head = 'Case Data Entry Details';
    $lbl_efiled_by = 'CDE By : ';
    $lbl_efiled_on = 'CDE On : ';
} else {
    $lbl_efiling_for = 'eFiling For : ';
    $lbl_efiling_details_head = 'eFiling Details';
    $lbl_efiled_by = 'eFiled By : ';
    $lbl_efiled_on = 'eFiled On : ';
}
$font_color = '#4721c4';

if (!empty($reg_id)) {
    echo " <script>
               $('#acknow').css('display', 'none');
             </script>";
} else {
    echo " <script>
           $('#acknow').css('display', 'block');
           </script>";
}
?>

<div class="col-md-12 col-sm-12 col-xs-12">    
    <div class="clearfix"></div>

    <div class="list-group-item" style="background: #EDEDED; padding: 8px 6px 28px 10px; color: #555;" data-toggle="collapse" data-parent="#MainMenu">
        <div class="col-lg-4 col-md-4 col-sm-5 col-xs-6"> 
            <b><?php echo $lbl_efiling_details_head; ?></b>
        </div>

        <div class="col-lg-5 col-md-5 col-sm-6 col-xs-5 text-right ">
            <div id="acknow">
                <?php
                if ($_SESSION['login']['ref_m_usertype_id'] == USER_ADVOCATE || $_SESSION['login']['ref_m_usertype_id'] == USER_IN_PERSON || $_SESSION['login']['ref_m_usertype_id'] == USER_PDE) {
                    if ($_SESSION['efiling_details']['stage_id'] != Draft_Stage && $_SESSION['breadcrumb_enable']['efiling_type'] != E_FILING_TYPE_CDE) {
                        ?>
                        <a class="btn btn-success btn-xs"  href="<?php echo base_url('acknowledgement/view'); ?>"  target="_blank"> 
                            <i class="fa fa-download blink"></i> Acknowledgement
                        </a>
                        <?php
                    }
                    ?>
                    <a class="btn btn-success btn-xs"  target="_blank" href="<?php echo base_url('new_case/case_details_doc'); ?>"> 
                        <i class="fa fa-download blink"></i> Download PDF
                    </a>
                <?php }
                ?>
<!--    <a class="btn btn-success" target="_blank" href="<?php //echo base_url('new_case/efiling_details_doc');                                                                                                                                                                                                    ?>"> 
<i class="fa fa-download blink"></i> Case Parties
</a>--> </div>
        </div>
        <div class="col-lg-2 col-md-2 visible-lg visible-md" style="width: 22%;">
            <button class="btn btn-primary btn-xs openall" style="float: right">Expand All <i class="fa fa-eye"></i></button>
            <button class="btn btn-info btn-xs closeall hidden" style="float: right">Collapse All <i class="fa fa-eye-slash"></i></button> 
        </div>

        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1" style="width: 3%;">
            <a href="#demo_14" class="list-group-item1" style="background: #EDEDED;" data-toggle="collapse" data-parent="#MainMenu">
                <i class="fa fa-minus" style="float: right;"></i> <b> </a>
        </div>
    </div>  
    <div class="collapse in" id="demo_14">
        <div class="panel panel-default panel-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-5 text-right" for="cnr"> <?php echo $lbl_efiling_for; ?></label>
                            <div class="col-md-7">
                                <p style="color :<?php echo $font_color; ?>"><?php echo htmlentities(ucwords(strtolower($efiling_civil_data[0]['efiling_for_name'])), ENT_QUOTES); ?></div></p>
                        </div>
                    </div> 
                    <div class="clearfix"></div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-5 text-right" for="cnr"> Nature :</label>
                            <div class="col-md-7">
                                <p style="color :<?php echo $font_color; ?>"> <?php echo ($efiling_civil_data[0]['ci_cri'] == '2') ? htmlentities('Civil', ENT_QUOTES) : htmlentities('Criminal', ENT_QUOTES); ?></p>
                            </div>
                        </div> 
                    </div>
                    <div class="col-md-6">
                        <?php if ($_SESSION['breadcrumb_enable']['efiling_type'] != E_FILING_TYPE_CDE) { ?>
                            <div class="form-group">
                                <label class="control-label col-md-5 text-right"  for="casetype"> Matter Type :</label>
                                <div class="col-md-7">
                                    <p style="color :<?php echo $font_color; ?>"> <?php
                                        if ($efiling_civil_data[0]['matter_type'] == '1') {
                                            $matter_type = 'Original';
                                        } elseif ($efiling_civil_data[0]['matter_type'] == '2') {
                                            $matter_type = 'Appeal';
                                        } elseif ($efiling_civil_data[0]['matter_type'] == '3') {
                                            $matter_type = 'Application';
                                        } else {
                                            $matter_type = 'NA';
                                        }
                                        ?>
                                        <?php echo htmlentities($matter_type, ENT_QUOTES); ?></p>
                                </div>
                            </div> 
                        <?php } ?>
                    </div>

                    <div class="clearfix"></div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-5 text-right" for="filing_date">Case Type :</label>
                            <div class="col-md-7">
                                <p style="color :<?php echo $font_color; ?>">  <?php echo htmlentities($efiling_civil_master_data[0]->filcase_type_name, ENT_QUOTES); ?></p>
                            </div>
                        </div> 
                    </div>
                    <div class="col-md-6">
                        <?php if ($_SESSION['breadcrumb_enable']['efiling_type'] != E_FILING_TYPE_CDE) { ?>
                            <div class="form-group">
                                <label class="control-label col-md-5 text-right"  for="reg_date">Is MVC Matter :</label>
                                <div class="col-md-7">
                                    <p style="color :<?php echo $font_color; ?>">  <?php echo ($efiling_civil_data[0]['macp'] == 'Y') ? htmlentities('Yes', ENT_QUOTES) : htmlentities('No', ENT_QUOTES); ?></p>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label col-md-5 text-right" for="reg_date">Priorty :</label>
                            <div class="col-md-7">                                   
                                <?php echo ($efiling_civil_data[0]['urgent'] == 'Y') ? '<p style=color:red;>' . htmlentities('Urgent', ENT_QUOTES) . "</p>" : '<p style="color :' . $font_color . '">' . htmlentities('Ordinary', ENT_QUOTES); ?></p>
                            </div>
                        </div> 
                    </div>
                    <?php if ($_SESSION['breadcrumb_enable']['efiling_for_type_id'] == E_FILING_FOR_HIGHCOURT) { ?>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-md-5 text-right"  for="reg_date">To Be Listed Before :</label>
                                <div class="col-md-7">
                                    <?php echo (!empty($efiling_civil_master_data[0]->bench_name)) ? '<p style=color:red;>' . htmlentities($efiling_civil_master_data[0]->bench_name . ' Bench', ENT_QUOTES) . "</p>" : '<p>' . htmlentities('NA', ENT_QUOTES); ?></p>
                                </div>
                            </div> 
                        </div>
                    <?php } ?>
                </div>
            </div>
            <?php if (!($_SESSION['efiling_details']['stage_id'] == Draft_Stage)) { ?>
                <div class="row"><hr>
                    <div class="col-md-12">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-md-5 text-right"  for="reg_date"><?php echo $lbl_efiled_by; ?></label>
                                <div class="col-md-7">
                                    <?php $usertype_id = ($efiled_by_user[0]->ref_m_usertype_id == USER_ADVOCATE) ? 'Advocate' : 'Party-in-person'; ?>
                                    <p style="color :<?php echo $font_color; ?>">  <?php echo htmlentities(strtoupper($efiled_by_user[0]->first_name . ' ' . $efiled_by_user[0]->last_name), ENT_QUOTES) . ' ( ' . htmlentities($usertype_id, ENT_QUOTES) . ')'; ?></p>
                                </div>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-md-5 text-right"  for="reg_date"><?php echo $lbl_efiled_on; ?></label>
                                <div class="col-md-7">
                                    <p style="color :<?php echo $font_color; ?>">  <?php echo $submitted_on ? htmlentities(date('d-m-Y h:i:s A', strtotime($submitted_on)), ENT_QUOTES) : ''; ?></p>
                                </div>
                            </div> 
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

    </div>
    <a href="#demo_1" class="list-group-item" style="background: #EDEDED;" data-toggle="collapse" data-parent="#MainMenu"><i class="fa fa-plus" style="float: right;"></i> <b><?php echo ucwords(strtolower($case_type_pet_title)); ?></b></a>
    <div class="collapse" id="demo_1">
        <div class="x_panel">
            <div class="table-responsive">
                <div class="text-right"><a href="<?php echo base_url('new_case/petitioner'); ?>"><i class="fa fa-pencil"></i></a></div>
                <table id="" class="table dt-responsive nowrap" cellspacing="0" width="100%">
                    <tbody>
                        <?php
                        if ($efiling_civil_data[0]['pet_sex'] == '1') {
                            $gender = 'Male';
                        } elseif ($efiling_civil_data[0]['pet_sex'] == '2') {
                            $gender = 'Female';
                        } elseif ($efiling_civil_data[0]['pet_sex'] == '3') {
                            $gender = 'Other';
                        } else {
                            $gender = '';
                        }


                        if ($efiling_civil_data[0]['res_sex'] == '1') {
                            $res_gender = 'Male';
                        } elseif ($efiling_civil_data[0]['res_sex'] == '2') {
                            $res_gender = 'Female';
                        } elseif ($efiling_civil_data[0]['res_sex'] == '3') {
                            $res_gender = 'Other';
                        } else {
                            $res_gender = '';
                        }


                        if ($efiling_civil_data[0]['pet_age'] == 0) {
                            $pet_age = '';
                        } else {
                            $pet_age = $efiling_civil_data[0]['pet_age'];
                        }

                        if ($efiling_civil_data[0]['res_age'] == 0) {
                            $res_age = '';
                        } else {
                            $res_age = $efiling_civil_data[0]['res_age'];
                        }


// STARTS PETITIONER ADDRESS

                        $pet_address = strtoupper($efiling_civil_data[0]['petadd']);
                        if (isset($efiling_civil_master_data[0]->pet_village_name) && !empty($efiling_civil_master_data[0]->pet_village_name)) {
                            $pet_address .= ', ' . strtoupper($efiling_civil_master_data[0]->pet_village_name);
                        }
                        if (isset($efiling_civil_master_data[0]->pet_ward_name) && !empty($efiling_civil_master_data[0]->pet_ward_name)) {
                            $pet_address .= ', ' . strtoupper($efiling_civil_master_data[0]->pet_ward_name);
                        }
                        if (isset($efiling_civil_master_data[0]->pet_town_name) && !empty($efiling_civil_master_data[0]->pet_town_name)) {
                            $pet_address .= ', ' . strtoupper($efiling_civil_master_data[0]->pet_town_name);
                        }
                        if (isset($efiling_civil_master_data[0]->pet_taluka_name) && !empty($efiling_civil_master_data[0]->pet_taluka_name)) {
                            $pet_address .= ', ' . strtoupper($efiling_civil_master_data[0]->pet_taluka_name);
                        }
                        if (isset($efiling_civil_master_data[0]->pet_distt_name) && !empty($efiling_civil_master_data[0]->pet_distt_name)) {
                            $pet_address .= ', ' . strtoupper($efiling_civil_master_data[0]->pet_distt_name);
                        }
                        if (isset($efiling_civil_master_data[0]->pet_state_name) && !empty($efiling_civil_master_data[0]->pet_state_name)) {
                            $pet_address .= ', ' . strtoupper($efiling_civil_master_data[0]->pet_state_name);
                        }
                        if (isset($efiling_civil_data[0]['pet_pincode']) && !empty($efiling_civil_data[0]['pet_pincode'])) {
                            $pet_address .= ' - ' . $efiling_civil_data[0]['pet_pincode'];
                        }

// ENDS PETITIONER ADDRESS
// STARTS RESPONDENT ADDRESS

                        $res_address = strtoupper($efiling_civil_data[0]['resadd']);
                        if (isset($efiling_civil_master_data[0]->res_village_name) && !empty($efiling_civil_master_data[0]->res_village_name)) {
                            $res_address .= ', ' . strtoupper($efiling_civil_master_data[0]->res_village_name);
                        }
                        if (isset($efiling_civil_master_data[0]->res_ward_name) && !empty($efiling_civil_master_data[0]->res_ward_name)) {
                            $res_address .= ', ' . strtoupper($efiling_civil_master_data[0]->res_ward_name);
                        }
                        if (isset($efiling_civil_master_data[0]->res_town_name) && !empty($efiling_civil_master_data[0]->res_town_name)) {
                            $res_address .= ', ' . strtoupper($efiling_civil_master_data[0]->res_town_name);
                        }
                        if (isset($efiling_civil_master_data[0]->res_taluka_name) && !empty($efiling_civil_master_data[0]->res_taluka_name)) {
                            $res_address .= ', ' . strtoupper($efiling_civil_master_data[0]->res_taluka_name);
                        }
                        if (isset($efiling_civil_master_data[0]->res_distt_name) && !empty($efiling_civil_master_data[0]->res_distt_name)) {
                            $res_address .= ', ' . strtoupper($efiling_civil_master_data[0]->res_distt_name);
                        }
                        if (isset($efiling_civil_master_data[0]->res_state_name) && !empty($efiling_civil_master_data[0]->res_state_name)) {
                            $res_address .= ', ' . strtoupper($efiling_civil_master_data[0]->res_state_name);
                        }
                        if (isset($efiling_civil_data[0]['res_pincode']) && !empty($efiling_civil_data[0]['res_pincode'])) {
                            $res_address .= ' - ' . $efiling_civil_data[0]['res_pincode'];
                        }

// ENDS RESPONDENT ADDRESS
// STARTS PETITIONER ALTERNATE ADDRESS

                        $pet_alt_address = strtoupper($efiling_civil_data[0]['pet_add2']);
                        if (isset($efiling_civil_master_data[0]->extra_info_pet_village_name) && !empty($efiling_civil_master_data[0]->extra_info_pet_village_name)) {
                            $pet_alt_address .= ', ' . strtoupper($efiling_civil_master_data[0]->extra_info_pet_village_name);
                        }
                        if (isset($efiling_civil_master_data[0]->extra_info_pet_ward_name) && !empty($efiling_civil_master_data[0]->extra_info_pet_ward_name)) {
                            $pet_alt_address .= ', ' . strtoupper($efiling_civil_master_data[0]->extra_info_pet_ward_name);
                        }
                        if (isset($efiling_civil_master_data[0]->extra_info_pet_town_name) && !empty($efiling_civil_master_data[0]->extra_info_pet_town_name)) {
                            $pet_alt_address .= ', ' . strtoupper($efiling_civil_master_data[0]->extra_info_pet_town_name);
                        }
                        if (isset($efiling_civil_master_data[0]->extra_info_pet_taluka_name) && !empty($efiling_civil_master_data[0]->extra_info_pet_taluka_name)) {
                            $pet_alt_address .= ', ' . strtoupper($efiling_civil_master_data[0]->extra_info_pet_taluka_name);
                        }
                        if (isset($efiling_civil_master_data[0]->extra_info_pet_distt_name) && !empty($efiling_civil_master_data[0]->extra_info_pet_distt_name)) {
                            $pet_alt_address .= ', ' . strtoupper($efiling_civil_master_data[0]->extra_info_pet_distt_name);
                        }
                        if (isset($efiling_civil_master_data[0]->extra_info_pet_state_name) && !empty($efiling_civil_master_data[0]->extra_info_pet_state_name)) {
                            $pet_alt_address .= ', ' . strtoupper($efiling_civil_master_data[0]->extra_info_pet_state_name);
                        }

// ENDS PETITIONER ALTERNATE ADDRESS
// STARTS RESPONDENT ALTERNATE ADDRESS

                        $res_alt_address = strtoupper($efiling_civil_data[0]['res_add2']);
                        if (isset($efiling_civil_master_data[0]->extra_info_res_village_name) && !empty($efiling_civil_master_data[0]->extra_info_res_village_name)) {
                            $res_alt_address .= ', ' . strtoupper($efiling_civil_master_data[0]->extra_info_res_village_name);
                        }
                        if (isset($efiling_civil_master_data[0]->extra_info_res_ward_name) && !empty($efiling_civil_master_data[0]->extra_info_res_ward_name)) {
                            $res_alt_address .= ', ' . strtoupper($efiling_civil_master_data[0]->extra_info_res_ward_name);
                        }
                        if (isset($efiling_civil_master_data[0]->extra_info_res_town_name) && !empty($efiling_civil_master_data[0]->extra_info_res_town_name)) {
                            $res_alt_address .= ', ' . strtoupper($efiling_civil_master_data[0]->extra_info_res_town_name);
                        }
                        if (isset($efiling_civil_master_data[0]->extra_info_res_taluka_name) && !empty($efiling_civil_master_data[0]->extra_info_res_taluka_name)) {
                            $res_alt_address .= ', ' . strtoupper($efiling_civil_master_data[0]->extra_info_res_taluka_name);
                        }
                        if (isset($efiling_civil_master_data[0]->extra_info_res_distt_name) && !empty($efiling_civil_master_data[0]->extra_info_res_distt_name)) {
                            $res_alt_address .= ', ' . strtoupper($efiling_civil_master_data[0]->extra_info_res_distt_name);
                        }
                        if (isset($efiling_civil_master_data[0]->extra_info_res_state_name) && !empty($efiling_civil_master_data[0]->extra_info_res_state_name)) {
                            $res_alt_address .= ', ' . strtoupper($efiling_civil_master_data[0]->extra_info_res_state_name);
                        }

// ENDS RESPONDENT ALTERNATE ADDRESS
                        ?>
                        <?php if ($efiling_civil_data[0]['orgid'] != '0') { ?>
                            <tr>
                                <td colspan="3">
                                    <span id="label_petname"><strong><?php echo ucwords(strtolower($case_type_pet_title)); ?> :</strong></span>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities(strtoupper($efiling_civil_data[0]['pet_name']), ENT_QUOTES); ?></font>
                                </td>
                                <td><strong>Organisation : </strong> <font color="<?php echo $font_color; ?>">&nbsp;<?php echo htmlentities($efiling_civil_master_data[0]->pet_org_name, ENT_QUOTES); ?></font></td>
                            </tr>
                            <?php
                        } elseif ($efiling_civil_data[0]['orgid'] == '0' && $efiling_civil_data[0]['not_in_list_org'] == 't') {
                            if ($_SESSION['login']['ref_m_usertype_id'] == USER_ACTION_ADMIN || $_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN) {
                                $colspan = 'colspan="2"';
                                $style1 = 'style="width: 50%"';
                                $style2 = 'style="width: 25%"';
                            } else {
                                $colspan = 'colspan="4"';
                                $style1 = '';
                                $style2 = '';
                            }
                            ?>
                            <tr>
                                <td <?php
                                echo htmlentities($colspan, ENT_QUOTES);
                                echo htmlentities($style1, ENT_QUOTES);
                                ?>><span id="label_petname"><strong><?php echo ucwords(strtolower($case_type_pet_title)); ?> :</strong></span>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities(strtoupper($efiling_civil_data[0]['pet_name']), ENT_QUOTES); ?></font>
                                    <?php
                                    if ($_SESSION['login']['ref_m_usertype_id'] == USER_ACTION_ADMIN || $_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN) {
                                        ?><p><font color="red">This Organisation is not in your CIS Masters. Please add it your masters and then select the same and save from the dropdown list here.</font></p><?php
                                    }
                                    ?>

                                </td>
                                <td><strong>Organisation : </strong> <font color="<?php echo $font_color; ?>">&nbsp;<?php echo htmlentities($efiling_civil_master_data[0]->pet_org_name, ENT_QUOTES); ?></font></td>
                                <?php
                                if ($_SESSION['login']['ref_m_usertype_id'] == USER_ACTION_ADMIN || $_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN) {
                                    $attribute = array('class' => 'form-horizontal', 'name' => 'add_org_not_listed', 'id' => 'add_org_not_listed', 'autocomplete' => 'off');
                                    echo form_open('#', $attribute);
                                    ?>
                                    <td <?php echo htmlentities($style2, ENT_QUOTES); ?>>
                                        <select name="pet_organisation_id" id="pet_organisation_id" class="form-control input-sm filter_select_dropdown" required="required">
                                            <option value="" title="Select">Select Organisation</option>
                                            <?php
                                            if (count($org_list)) {
                                                foreach ($org_list as $dataRes) {
                                                    $value = (string) $dataRes->ORGCODE;
                                                    if ($efiling_civil_data[0]['orgid'] == $value) {
                                                        $sel = 'selected=selected';
                                                    } else {
                                                        $sel = "";
                                                    }
                                                    echo '<option ' . htmlentities($sel, ENT_QUOTES) . '  value="' . htmlentities(url_encryption(trim($dataRes->ORGCODE . '#$' . (string) $dataRes->ORGNAME), ENT_QUOTES)) . '">' . htmlentities((string) $dataRes->ORGNAME, ENT_QUOTES) . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </td>
                                    <td <?php echo htmlentities($style2, ENT_QUOTES); ?>><input type="submit" class="btn btn-info" id="save_org_master" name="save" value="Save"></td>
                                    <?php
                                    echo form_close();
                                }
                                ?> 
                            </tr>                                
                        <?php } else { ?>
                            <tr>
                                <td><span id="label_petname"><strong><?php echo ucwords(strtolower($case_type_pet_title)); ?> :</strong></span>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities(strtoupper($efiling_civil_data[0]['pet_name']), ENT_QUOTES); ?></font></td>
                                <td colspan="2"><strong><?php echo ucwords($efiling_civil_master_data[0]->pet_relation); ?> :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities(strtoupper($efiling_civil_data[0]['pet_father_name']), ENT_QUOTES); ?></font></td>
                                <td><strong>Gender : </strong> <font color="<?php echo $font_color; ?>">&nbsp;<?php echo htmlentities($gender, ENT_QUOTES); ?></font></td>                                    
                            </tr>
                            <tr>
                                <td><strong>Have Legal Heir :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo $legal_heir = ($efiling_civil_data[0]['pet_legal_heir'] == 'Y') ? '<span class="blinking">Yes</span>' : 'No'; ?></font></td>
                                <td><strong>Religion :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities(strtoupper($efiling_civil_master_data[0]->pet_religion_name), ENT_QUOTES); ?></font></td>
                                <td><strong>Caste :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities(strtoupper($efiling_civil_master_data[0]->pet_caste_name), ENT_QUOTES); ?></font></td>
                                <td colspan="1"><strong>D.O.B. / Age :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_data[0]['pet_dob'], ENT_QUOTES) ? date('d/m/Y', strtotime($efiling_civil_data[0]['pet_dob'])) . ' / ' : ''; ?><?php echo htmlentities($pet_age, ENT_QUOTES); ?></font></td>
                            </tr>                                
                        <?php } ?>
                        <tr>
                            <td colspan="2"><strong>Extra <?php echo ucwords(strtolower($case_type_pet_title)); ?> Count :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_data[0]['pet_extracount'], ENT_QUOTES); ?></font></td>
                            <td><strong>Email :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_data[0]['pet_email'], ENT_QUOTES); ?></font></td>    
                            <td><strong>Mobile :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_data[0]['pet_mobile'], ENT_QUOTES); ?></font></td>                                    
                        </tr>
                        <tr>
                            <td  colspan="3"><strong>Address :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($pet_address, ENT_QUOTES); ?></font> </td>
                            <td><strong>Police Station :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities(strtoupper($efiling_civil_master_data[0]->pet_ps_name), ENT_QUOTES); ?></font></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <a href="#demo_2" class="list-group-item" style="background: #EDEDED;" data-toggle="collapse" data-parent="#MainMenu"><i class="fa fa-plus" style="float: right;"></i> <b><?php echo ucwords(strtolower($case_type_res_title)); ?></b></a>
    <div class="collapse" id="demo_2">
        <div class="x_panel">
            <div class="table-responsive">
                <div class="text-right"><a href="<?php echo base_url('new_case/respondent'); ?>"><i class="fa fa-pencil"></i></a></div>
                <table id="" class="table dt-responsive nowrap" cellspacing="0" width="100%">
                    <tbody>
                        <?php if ($efiling_civil_data[0]['resorgid'] != '0') { ?>
                            <tr>
                                <td colspan="3"><span id="label_petname"><strong><?php echo ucwords(strtolower($case_type_res_title)); ?> :</strong></span>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities(strtoupper($efiling_civil_data[0]['res_name']), ENT_QUOTES); ?></font> </td>
                                <td><strong>Organisation : </strong> <font color="<?php echo $font_color; ?>">&nbsp;<?php echo htmlentities($efiling_civil_master_data[0]->res_org_name, ENT_QUOTES); ?></font></td>
                            </tr>
                            <?php
                        } elseif ($efiling_civil_data[0]['resorgid'] == '0' && $efiling_civil_data[0]['res_not_in_list_org'] == 't') {
                            if ($_SESSION['login']['ref_m_usertype_id'] == USER_ACTION_ADMIN || $_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN) {
                                $colspan = 'colspan="2"';
                                $style1 = 'style="width: 50%"';
                                $style2 = 'style="width: 25%"';
                            } else {
                                $colspan = 'colspan="4"';
                                $style1 = '';
                                $style2 = '';
                            }
                            ?>
                            <tr>
                                <td <?php
                                echo htmlentities($colspan, ENT_QUOTES);
                                echo htmlentities($style1, ENT_QUOTES);
                                ?>><span id="label_petname"><strong><?php echo ucwords(strtolower($case_type_res_title)); ?> :</strong></span>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities(strtoupper($efiling_civil_data[0]['res_name']), ENT_QUOTES); ?></font>
                                    <?php
                                    if ($_SESSION['login']['ref_m_usertype_id'] == USER_ACTION_ADMIN || $_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN) {
                                        ?><p><font color="red">This Organisation is not in your CIS Masters. Please add it your masters and then select the same and save from the dropdown list here.</font></p><?php
                                    }
                                    ?>
                                </td> 
                                <td><strong>Organisation : </strong> <font color="<?php echo $font_color; ?>">&nbsp;<?php echo htmlentities($efiling_civil_master_data[0]->res_org_name, ENT_QUOTES); ?></font></td>
                                <?php
                                if ($_SESSION['login']['ref_m_usertype_id'] == USER_ACTION_ADMIN || $_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN) {
                                    $attribute = array('class' => 'form-horizontal', 'name' => 'add_org_not_listed', 'id' => 'add_org_not_listed', 'autocomplete' => 'off');
                                    echo form_open('#', $attribute);
                                    ?>
                                    <td <?php echo htmlentities($style2, ENT_QUOTES); ?>>
                                        <select name="res_organisation_id" id="res_organisation_id" class="form-control input-sm filter_select_dropdown" required="required"  style="width: 100%">
                                            <option value="" title="Select">Select Organisation</option>
                                            <?php
                                            if (count($org_list)) {
                                                foreach ($org_list as $dataRes) {
                                                    $value = (string) $dataRes->ORGCODE;
                                                    if ($efiling_civil_data[0]['resorgid'] == $value) {
                                                        $sel = 'selected=selected';
                                                    } else {
                                                        $sel = "";
                                                    }
                                                    echo '<option ' . htmlentities($sel, ENT_QUOTES) . '  value="' . htmlentities(url_encryption(trim($dataRes->ORGCODE . '#$' . (string) $dataRes->ORGNAME), ENT_QUOTES)) . '">' . htmlentities((string) $dataRes->ORGNAME, ENT_QUOTES) . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </td>
                                    <td <?php echo htmlentities($style2, ENT_QUOTES); ?>><input type="submit" class="btn btn-info" id="save_org_master1" name="save" value="Save"></td>
                                    <?php
                                    echo form_close();
                                }
                                ?> 
                            </tr>                                
                        <?php } else { ?>
                            <tr>
                                <td><span id="label_petname"><strong><?php echo ucwords(strtolower($case_type_res_title)); ?> :</strong></span>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities(strtoupper($efiling_civil_data[0]['res_name']), ENT_QUOTES); ?></font></td>
                                <td colspan="2"><strong><?php echo ucwords($efiling_civil_master_data[0]->res_relation); ?> :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities(strtoupper($efiling_civil_data[0]['res_father_name']), ENT_QUOTES); ?></font></td>
                                <td><strong>Gender: </strong> <font color="<?php echo $font_color; ?>">&nbsp;<?php echo htmlentities($res_gender, ENT_QUOTES); ?></font></td>                                    
                            </tr>
                            <tr>
                                <td><strong>Have Legal Heir :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo $legal_heir = ($efiling_civil_data[0]['pet_legal_heir'] == 'Y') ? '<span class="blinking">Yes</span>' : 'No'; ?></font></td>
                                <td><strong>Religion :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities(strtoupper($efiling_civil_master_data[0]->res_religion_name), ENT_QUOTES); ?></font></td>
                                <td><strong>Caste :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities(strtoupper($efiling_civil_master_data[0]->res_caste_name), ENT_QUOTES); ?></font></td>
                                <td colspan="1"><strong>D.O.B. / Age :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_data[0]['res_dob'], ENT_QUOTES) ? date('d/m/Y', strtotime($efiling_civil_data[0]['res_dob'])) . ' / ' : ''; ?><?php echo htmlentities($res_age, ENT_QUOTES); ?></font></td>
                            </tr>                                
                        <?php } ?>
                        <tr>
                            <td colspan="2"><strong>Extra <?php echo ucwords(strtolower($case_type_res_title)); ?> Count :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_data[0]['res_extracount'], ENT_QUOTES); ?></font></td>
                            <td><strong>Email :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_data[0]['res_email'], ENT_QUOTES); ?></font></td>    
                            <td><strong>Mobile :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_data[0]['res_mobile'], ENT_QUOTES); ?></font></td>                                    
                        </tr>
                        <tr>
                            <td colspan="3"><strong>Address :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($res_address, ENT_QUOTES); ?></font> </td>
                            <td><strong>Police Station :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities(strtoupper($efiling_civil_master_data[0]->res_ps_name), ENT_QUOTES); ?></font></td>
                        </tr> 
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <a href="#demo_3" class="list-group-item" style="background: #EDEDED;" data-toggle="collapse" data-parent="#MainMenu"><i class="fa fa-plus" style="float: right;"></i> <b>Extra Information</b></a>
    <div class="collapse" id="demo_3">
        <div class="x_panel">
            <div class="table-responsive">
                <div class="text-right"><a href="<?php echo base_url('new_case/extra_info'); ?>"><i class="fa fa-pencil"></i></a></div>
                <table id="" class="table dt-responsive nowrap" cellspacing="0" width="100%">
                    <tbody>
                        <tr>
                            <td colspan="4" style="text-align: center"><font color="#29579E"><strong><?php echo ucwords(strtolower($case_type_pet_title)); ?> Extra Information</font></td>
                        </tr>
                        <tr>
                            <td><strong>Passport No.:</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_data[0]['pet_passportno'], ENT_QUOTES); ?></font></td>
                            <td><strong>PAN No.:</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_data[0]['petpanno'], ENT_QUOTES); ?></font></td>
                            <td><strong>Country:</strong>&nbsp; <font color="<?php echo $font_color; ?>"><?php echo htmlentities(strtoupper($efiling_civil_data[0]['pet_country']), ENT_QUOTES); ?></font></td>
                            <td><strong>Nationality:</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities(strtoupper($efiling_civil_data[0]['pet_nationality']), ENT_QUOTES); ?></font></td>
                        </tr>
                        <tr>
                            <td><strong>Phone No.:</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_data[0]['pet_phone'], ENT_QUOTES); ?></font></td>
                            <td><strong>Fax No.:</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_data[0]['pet_fax'], ENT_QUOTES); ?></font></td>
                            <td colspan="2"><strong>Occupation:</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities(strtoupper($efiling_civil_data[0]['pet_occu']), ENT_QUOTES); ?></font></td>                                
                        </tr>
                        <tr>
                            <td colspan="4"><strong>Alternate Address:</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($pet_alt_address, ENT_QUOTES); ?></font></td>                                
                        </tr>                            
                        <tr><td colspan="4"  style="text-align: center"><font color="#29579E"><strong><?php echo ucwords(strtolower($case_type_res_title)); ?> Extra Information</strong></font></td></tr>
                        <tr>
                            <td><strong>Passport No.:</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_data[0]['res_passportno'], ENT_QUOTES); ?></font></td>
                            <td><strong>PAN No.:</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_data[0]['respanno'], ENT_QUOTES); ?></font></td>
                            <td><strong>Country:</strong>&nbsp; <font color="<?php echo $font_color; ?>"><?php echo htmlentities(strtoupper($efiling_civil_data[0]['res_country']), ENT_QUOTES); ?></font></td>
                            <td><strong>Nationality:</strong>&nbsp; <font color="<?php echo $font_color; ?>"><?php echo htmlentities(strtoupper($efiling_civil_data[0]['res_nationality']), ENT_QUOTES); ?></font></td>
                        </tr>
                        <tr>
                            <td><strong>Phone No.:</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_data[0]['res_phone'], ENT_QUOTES); ?></font></td>
                            <td><strong>Fax No.:</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_data[0]['res_fax'], ENT_QUOTES); ?></font></td>
                            <td colspan="2"><strong>Occupation:</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities(strtoupper($efiling_civil_data[0]['res_occu']), ENT_QUOTES); ?></font></td>                                
                        </tr>
                        <tr>
                            <td colspan="4"><strong>Alternate Address:</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($res_alt_address, ENT_QUOTES); ?></font></td>                                
                        </tr>                            
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <a href="#demo_8" class="list-group-item" style="background: #EDEDED;" data-toggle="collapse" data-parent="#MainMenu"><i class="fa fa-plus" style="float: right;"></i> <b>Extra Parties</b></a>             
    <div class="collapse" id="demo_8">
        <div class="x_panel">
            <div class="table-responsive">
                <div class="text-right"><a href="<?php echo base_url('new_case/extra_party'); ?>"><i class="fa fa-pencil"></i></a></div>
                <table id="" class="table dt-responsive nowrap" cellspacing="0" width="100%">
                    <tbody>
                        <?php
                        if (isset($extra_party_details) && count($extra_party_details) > 0) {
                            $i = 1;
                            foreach ($extra_party_details as $exp) {
                                if ($exp->pet_sex == '1') {
                                    $ext_gender = 'Male';
                                } elseif ($exp->pet_sex == '2') {
                                    $ext_gender = 'Female';
                                } elseif ($exp->pet_sex == '3') {
                                    $ext_gender = 'Other';
                                } else {
                                    $ext_gender = '';
                                }



                                if ($exp->type == '1') {
                                    $party_type = htmlentities(ucwords(strtolower($case_type_pet_title)), ENT_QUOTES);
                                    $form_name_n_id = 'extra_pet_orgid';
                                    $extra_party_id = 'extra_party_pet_id';
                                } elseif ($exp->type == '2') {
                                    $party_type = htmlentities(ucwords(strtolower($case_type_res_title)), ENT_QUOTES);
                                    $form_name_n_id = 'extra_res_orgid';
                                    $extra_party_id = 'extra_party_res_id';
                                }

                                if ($exp->pet_age == 0) {
                                    $extra_party_age = '';
                                } else {
                                    $extra_party_age = $exp->pet_age;
                                }

                                // STARTS EXTRA PARTY ADDRESS

                                $extra_party_address = strtoupper($exp->address);
                                if (isset($exp->extra_party_village_name) && !empty($exp->extra_party_village_name)) {
                                    $extra_party_address .= ', ' . strtoupper($exp->extra_party_village_name);
                                }
                                if (isset($exp->extra_party_ward_name) && !empty($exp->extra_party_ward_name)) {
                                    $extra_party_address .= ', ' . strtoupper($exp->extra_party_ward_name);
                                }
                                if (isset($exp->extra_party_town_name) && !empty($exp->extra_party_town_name)) {
                                    $extra_party_address .= ', ' . strtoupper($exp->extra_party_town_name);
                                }
                                if (isset($exp->extra_party_taluka_name) && !empty($exp->extra_party_taluka_name)) {
                                    $extra_party_address .= ', ' . strtoupper($exp->extra_party_taluka_name);
                                }
                                if (isset($exp->extra_party_distt_name) && !empty($exp->extra_party_distt_name)) {
                                    $extra_party_address .= ', ' . strtoupper($exp->extra_party_distt_name);
                                }
                                if (isset($exp->extra_party_state_name) && !empty($exp->extra_party_state_name)) {
                                    $extra_party_address .= ', ' . strtoupper($exp->extra_party_state_name);
                                }
                                if (isset($exp->pincode) && !empty($exp->pincode)) {
                                    $extra_party_address .= ' - ' . $exp->pincode;
                                }

                                // ENDS EXTRA PARTY ADDRESS
                                // STARTS EXTRA PARTY ALTERNATE ADDRESS

                                $extra_party_alt_address = strtoupper($exp->altaddress);
                                if (isset($exp->extra_party_o_village_name) && !empty($exp->extra_party_o_village_name)) {
                                    $extra_party_alt_address .= ', ' . strtoupper($exp->extra_party_o_village_name);
                                }
                                if (isset($exp->extra_party_o_ward_name) && !empty($exp->extra_party_o_ward_name)) {
                                    $extra_party_alt_address .= ', ' . strtoupper($exp->extra_party_o_ward_name);
                                }
                                if (isset($exp->extra_party_o_town_name) && !empty($exp->extra_party_o_town_name)) {
                                    $extra_party_alt_address .= ', ' . strtoupper($exp->extra_party_o_town_name);
                                }
                                if (isset($exp->extra_party_o_taluka_name) && !empty($exp->extra_party_o_taluka_name)) {
                                    $extra_party_alt_address .= ', ' . strtoupper($exp->extra_party_o_taluka_name);
                                }
                                if (isset($exp->extra_party_o_distt_name) && !empty($exp->extra_party_o_distt_name)) {
                                    $extra_party_alt_address .= ', ' . strtoupper($exp->extra_party_o_distt_name);
                                }
                                if (isset($exp->extra_party_o_state_name) && !empty($exp->extra_party_o_state_name)) {
                                    $extra_party_alt_address .= ', ' . strtoupper($exp->extra_party_o_state_name);
                                }

                                // ENDS EXTRA PARTY ALTERNATE ADDRESS
                                ?>
                                <tr>
                                    <td colspan="4" class="text-center"><font color="#ff0000"><b>S.No:<?php echo htmlentities($i++, ENT_QUOTES); ?> </b><b style="text-align: center"><?php echo htmlentities($party_type . ' Details', ENT_QUOTES); ?></b></font></td>
                                </tr>

                                <?php if ($exp->orgid != '0') { ?>
                                    <tr>
                                        <td colspan="3"><span id="label_petname"><strong><?php echo ucwords($party_type); ?> :</strong></span>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities(strtoupper($exp->name), ENT_QUOTES); ?></font> </td>
                                        <td><strong>Organisation : </strong> <font color="<?php echo $font_color; ?>">&nbsp;<?php echo htmlentities($exp->extra_party_org_name, ENT_QUOTES); ?></font></td>
                                    </tr>
                                    <?php
                                } elseif ($exp->orgid == '0' && $exp->extra_not_in_list_org == 't') {
                                    if ($_SESSION['login']['ref_m_usertype_id'] == USER_ACTION_ADMIN || $_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN) {
                                        $colspan = 'colspan="2"';
                                        $style1 = 'style="width: 50%"';
                                        $style2 = 'style="width: 25%"';
                                    } else {
                                        $colspan = 'colspan="4"';
                                        $style1 = '';
                                        $style2 = '';
                                    }
                                    ?>
                                    <tr>
                                        <td <?php
                                        echo htmlentities($colspan, ENT_QUOTES);
                                        echo htmlentities($style1, ENT_QUOTES);
                                        ?>><span id="label_petname"><strong><?php echo ucwords($party_type); ?> :</strong></span>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities(strtoupper($exp->name), ENT_QUOTES); ?></font>
                                            <?php
                                            if ($_SESSION['login']['ref_m_usertype_id'] == USER_ACTION_ADMIN || $_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN) {
                                                ?><p><font color="red">This Organisation is not in your CIS Masters. Please add it your masters and then select the same and save from the dropdown list here.</font></p><?php
                                            }
                                            ?>
                                        </td>
                                        <td><strong>Organisation : </strong> <font color="<?php echo $font_color; ?>">&nbsp;<?php echo htmlentities($exp->extra_party_org_name, ENT_QUOTES); ?></font></td>
                                        <?php
                                        if ($_SESSION['login']['ref_m_usertype_id'] == USER_ACTION_ADMIN || $_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN) {
                                            $attribute = array('class' => 'form-horizontal extra_party_org_not_listed', 'name' => 'extra_party_org_not_listed', 'id' => 'extra_party_org_not_listed', 'autocomplete' => 'off');
                                            echo form_open('#', $attribute);
                                            ?>
                                            <td <?php echo htmlentities($style2, ENT_QUOTES); ?>>
                                                <input type="hidden" name="<?php echo htmlentities($extra_party_id, ENT_QUOTES); ?>" value="<?php echo htmlentities(url_encryption($exp->id), ENT_QUOTES); ?>">
                                                <select name="<?php echo htmlentities($form_name_n_id, ENT_QUOTES); ?>" required="required" id="<?php echo htmlentities($form_name_n_id, ENT_QUOTES); ?>" class="form-control input-sm filter_select_dropdown" style="width: 100%">
                                                    <option value="" title="Select">Select Organisation</option>
                                                    <?php
                                                    if (count($org_list)) {
                                                        foreach ($org_list as $dataRes) {
                                                            $value = (string) $dataRes->ORGCODE;
                                                            if ($exp->orgid == $value) {
                                                                $sel = 'selected=selected';
                                                            } else {
                                                                $sel = "";
                                                            }
                                                            echo '<option ' . htmlentities($sel, ENT_QUOTES) . '  value="' . htmlentities(url_encryption(trim($dataRes->ORGCODE . '#$' . (string) $dataRes->ORGNAME), ENT_QUOTES)) . '">' . htmlentities((string) $dataRes->ORGNAME, ENT_QUOTES) . '</option>';
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                            <td <?php echo htmlentities($style2, ENT_QUOTES); ?>><input type="submit" class="btn btn-info" id="extra_party_org_not_listed" name="save" value="Save"></td>
                                            <?php
                                            echo form_close();
                                        }
                                        ?> 
                                    </tr>                                
                                <?php } else { ?>
                                    <tr>
                                        <td><span id="label_petname"><strong><?php echo ucwords($party_type); ?> :</strong></span>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities(strtoupper($exp->name), ENT_QUOTES); ?></font></td>
                                        <td colspan="2"><strong><?php echo ucwords($exp->extra_party_relation_name); ?> :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities(strtoupper($exp->father_name), ENT_QUOTES); ?></font></td>
                                        <td><strong>Gender: </strong> <font color="<?php echo $font_color; ?>">&nbsp;<?php echo htmlentities($ext_gender, ENT_QUOTES); ?></font></td>                                    
                                    </tr>
                                    <tr>
                                        <td><strong>Have Legal Heir :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo $legal_heir = ($exp->legal_heir == 'Y') ? '<span class="blinking">Yes</span>' : 'No'; ?></font></td>
                                        <td><strong>Religion :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities(strtoupper($exp->extra_party_religion_name), ENT_QUOTES); ?></font></td>
                                        <td><strong>Caste :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities(strtoupper($exp->extra_party_caste_name), ENT_QUOTES); ?></font></td>
                                        <td colspan="1"><strong>Age :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($extra_party_age, ENT_QUOTES); ?></font></td>
                                    </tr>                                
                                <?php } ?>
                                <tr>                                        
                                    <td><strong>Email :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($exp->pet_email, ENT_QUOTES); ?></font></td>    
                                    <td><strong>Mobile :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($exp->pet_mobile, ENT_QUOTES); ?></font></td>                                    
                                    <td colspan="2"><strong>Performa Flag:</strong><font color="<?php echo $font_color; ?>"><?php echo htmlentities($exp->performaresflag, ENT_QUOTES); ?></font></td>
                                </tr>
                                <tr>
                                    <td colspan="3"><strong>Address :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($extra_party_address, ENT_QUOTES); ?></font> </td>
                                    <td ><strong>Police Station :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities(strtoupper($exp->extra_party_ps_name), ENT_QUOTES); ?></font></td>
                                </tr> 

                                <?php if ($exp->other_info_flag == 'Y') { ?>
                                    <tr>
                                        <td colspan="4" style="text-align: center"><font color="#29579E"><i class="fa fa-plus" style="float: right;"></i> <b>Other Information </b></font></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Passport No:</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities(strtoupper($exp->passportno), ENT_QUOTES); ?></font></td>
                                        <td><strong>PAN No:</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities(strtoupper($exp->panno), ENT_QUOTES); ?></font></td>
                                        <td><strong>Fax No:</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($exp->pet_fax, ENT_QUOTES); ?></font></td>
                                        <td><strong>Phone No:</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($exp->pet_phone, ENT_QUOTES); ?></font></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Country:</strong>&nbsp; <font color="<?php echo $font_color; ?>"><?php echo htmlentities(strtoupper($exp->country), ENT_QUOTES); ?></font></td>
                                        <td><strong>Nationality:</strong>&nbsp; <font color="<?php echo $font_color; ?>"><?php echo htmlentities(strtoupper($exp->pet_nationality), ENT_QUOTES); ?></font></td>
                                        <td colspan="2"><strong>Occupation:</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities(strtoupper($exp->pet_occu), ENT_QUOTES); ?></font></td>                                                                            
                                    </tr>                                        
                                    <tr>
                                        <td colspan="4"><strong>Alternate Address:</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($extra_party_alt_address, ENT_QUOTES); ?></font></td>                                            
                                    </tr>
                                    <?php
                                }
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="4"><strong>Extra Parties are not added.</strong></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <a href="#demo_15" class="list-group-item" style="background: #EDEDED;" data-toggle="collapse" data-parent="#MainMenu"><i class="fa fa-plus" style="float: right;"></i> <b>Legal Representatives Details</b></a>             
    <div class="collapse" id="demo_15">
        <div class="x_panel">
            <div class="table-responsive">
                <div class="text-right"><a href="<?php echo base_url('new_case/add_LRs'); ?>"><i class="fa fa-pencil"></i></a></div>
                <table id="" class="table dt-responsive nowrap" cellspacing="0" width="100%">
                    <tbody>
                        <?php
                        if (isset($lrs_details) && count($lrs_details) > 0) {
                            $i = 1;
                            foreach ($lrs_details as $exp) {
                                if ($exp->pet_sex == '1') {
                                    $ext_gender = 'Male';
                                } elseif ($exp->pet_sex == '2') {
                                    $ext_gender = 'Female';
                                } elseif ($exp->pet_sex == '3') {
                                    $ext_gender = 'Other';
                                } else {
                                    $ext_gender = '';
                                }


                                if ($exp->type == '1') {
                                    $party_type = htmlentities(ucwords(strtolower($case_type_pet_title)), ENT_QUOTES);
                                    $form_name_n_id = 'extra_pet_orgid';
                                    $extra_party_id = 'extra_party_pet_id';
                                } elseif ($exp->type == '2') {
                                    $party_type = htmlentities(ucwords(strtolower($case_type_res_title)), ENT_QUOTES);
                                    $form_name_n_id = 'extra_res_orgid';
                                    $extra_party_id = 'extra_party_res_id';
                                }

                                if ($exp->pet_age == 0) {
                                    $extra_party_age = '';
                                } else {
                                    $extra_party_age = $exp->pet_age;
                                }

                                // STARTS EXTRA PARTY ADDRESS

                                $extra_party_address = strtoupper($exp->address);
                                if (isset($exp->extra_party_village_name) && !empty($exp->extra_party_village_name)) {
                                    $extra_party_address .= ', ' . strtoupper($exp->extra_party_village_name);
                                }
                                if (isset($exp->extra_party_ward_name) && !empty($exp->extra_party_ward_name)) {
                                    $extra_party_address .= ', ' . strtoupper($exp->extra_party_ward_name);
                                }
                                if (isset($exp->extra_party_town_name) && !empty($exp->extra_party_town_name)) {
                                    $extra_party_address .= ', ' . strtoupper($exp->extra_party_town_name);
                                }
                                if (isset($exp->extra_party_taluka_name) && !empty($exp->extra_party_taluka_name)) {
                                    $extra_party_address .= ', ' . strtoupper($exp->extra_party_taluka_name);
                                }
                                if (isset($exp->extra_party_distt_name) && !empty($exp->extra_party_distt_name)) {
                                    $extra_party_address .= ', ' . strtoupper($exp->extra_party_distt_name);
                                }
                                if (isset($exp->extra_party_state_name) && !empty($exp->extra_party_state_name)) {
                                    $extra_party_address .= ', ' . strtoupper($exp->extra_party_state_name);
                                }
                                if (isset($exp->pincode) && !empty($exp->pincode)) {
                                    $extra_party_address .= ' - ' . $exp->pincode;
                                }

                                // ENDS EXTRA PARTY ADDRESS
                                // STARTS EXTRA PARTY ALTERNATE ADDRESS

                                $extra_party_alt_address = strtoupper($exp->altaddress);
                                if (isset($exp->extra_party_o_village_name) && !empty($exp->extra_party_o_village_name)) {
                                    $extra_party_alt_address .= ', ' . strtoupper($exp->extra_party_o_village_name);
                                }
                                if (isset($exp->extra_party_o_ward_name) && !empty($exp->extra_party_o_ward_name)) {
                                    $extra_party_alt_address .= ', ' . strtoupper($exp->extra_party_o_ward_name);
                                }
                                if (isset($exp->extra_party_o_town_name) && !empty($exp->extra_party_o_town_name)) {
                                    $extra_party_alt_address .= ', ' . strtoupper($exp->extra_party_o_town_name);
                                }
                                if (isset($exp->extra_party_o_taluka_name) && !empty($exp->extra_party_o_taluka_name)) {
                                    $extra_party_alt_address .= ', ' . strtoupper($exp->extra_party_o_taluka_name);
                                }
                                if (isset($exp->extra_party_o_distt_name) && !empty($exp->extra_party_o_distt_name)) {
                                    $extra_party_alt_address .= ', ' . strtoupper($exp->extra_party_o_distt_name);
                                }
                                if (isset($exp->extra_party_o_state_name) && !empty($exp->extra_party_o_state_name)) {
                                    $extra_party_alt_address .= ', ' . strtoupper($exp->extra_party_o_state_name);
                                }

                                // ENDS EXTRA PARTY ALTERNATE ADDRESS
                                ?>
                                <tr>
                                    <td colspan="4" class="text-center"><font color="#ff0000"><b>S.No:<?php echo htmlentities($i++, ENT_QUOTES); ?> </b><b style="text-align: center"><?php echo htmlentities($party_type . ' LRs Details', ENT_QUOTES); ?></b></font></td>
                                </tr>

                                <?php if ($exp->orgid != '0') { ?>
                                    <tr>
                                        <td colspan="3"><span id="label_petname"><strong><?php echo ucwords($party_type); ?> :</strong></span>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities(strtoupper($exp->name), ENT_QUOTES); ?></font> </td>
                                        <td><strong>Organisation : </strong> <font color="<?php echo $font_color; ?>">&nbsp;<?php echo htmlentities($exp->extra_party_org_name, ENT_QUOTES); ?></font></td>
                                    </tr>
                                    <?php
                                } elseif ($exp->orgid == '0' && $exp->extra_not_in_list_org == 't') {
                                    if ($_SESSION['login']['ref_m_usertype_id'] == USER_ACTION_ADMIN || $_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN) {
                                        $colspan = 'colspan="2"';
                                        $style1 = 'style="width: 50%"';
                                        $style2 = 'style="width: 25%"';
                                    } else {
                                        $colspan = 'colspan="4"';
                                        $style1 = '';
                                        $style2 = '';
                                    }
                                    ?>
                                    <tr>
                                        <td <?php
                                        echo htmlentities($colspan, ENT_QUOTES);
                                        echo htmlentities($style1, ENT_QUOTES);
                                        ?>><span id="label_petname"><strong><?php echo ucwords($party_type); ?> :</strong></span>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities(strtoupper($exp->name), ENT_QUOTES); ?></font>
                                            <?php
                                            if ($_SESSION['login']['ref_m_usertype_id'] == USER_ACTION_ADMIN || $_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN) {
                                                ?><p><font color="red">This Organisation is not in your CIS Masters. Please add it your masters and then select the same and save from the dropdown list here.</font></p><?php
                                            }
                                            ?>
                                        </td>
                                        <td><strong>Organisation : </strong> <font color="<?php echo $font_color; ?>">&nbsp;<?php echo htmlentities($exp->extra_party_org_name, ENT_QUOTES); ?></font></td>
                                        <?php
                                        if ($_SESSION['login']['ref_m_usertype_id'] == USER_ACTION_ADMIN || $_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN) {
                                            $attribute = array('class' => 'form-horizontal extra_party_org_not_listed', 'name' => 'extra_party_org_not_listed', 'id' => 'extra_party_org_not_listed', 'autocomplete' => 'off');
                                            echo form_open('#', $attribute);
                                            ?>
                                            <td <?php echo htmlentities($style2, ENT_QUOTES); ?>>
                                                <input type="hidden" name="<?php echo htmlentities($extra_party_id, ENT_QUOTES); ?>" value="<?php echo htmlentities(url_encryption($exp->id), ENT_QUOTES); ?>">
                                                <select name="<?php echo htmlentities($form_name_n_id, ENT_QUOTES); ?>" required="required" id="<?php echo htmlentities($form_name_n_id, ENT_QUOTES); ?>" class="form-control input-sm filter_select_dropdown" style="width: 100%">
                                                    <option value="" title="Select">Select Organisation</option>
                                                    <?php
                                                    if (count($org_list)) {
                                                        foreach ($org_list as $dataRes) {
                                                            $value = (string) $dataRes->ORGCODE;
                                                            if ($exp->orgid == $value) {
                                                                $sel = 'selected=selected';
                                                            } else {
                                                                $sel = "";
                                                            }
                                                            echo '<option ' . htmlentities($sel, ENT_QUOTES) . '  value="' . htmlentities(url_encryption(trim($dataRes->ORGCODE . '#$' . (string) $dataRes->ORGNAME), ENT_QUOTES)) . '">' . htmlentities((string) $dataRes->ORGNAME, ENT_QUOTES) . '</option>';
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                            <td <?php echo htmlentities($style2, ENT_QUOTES); ?>><input type="submit" class="btn btn-info" id="extra_party_org_not_listed" name="save" value="Save"></td>
                                            <?php
                                            echo form_close();
                                        }
                                        ?> 
                                    </tr>                                
                                <?php } else { ?>
                                    <tr>
                                        <td><span id="label_petname"><strong><?php echo ucwords($party_type); ?> :</strong></span>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities(strtoupper($exp->name), ENT_QUOTES); ?></font></td>
                                        <td colspan="2"><strong><?php echo ucwords($exp->extra_party_relation_name); ?> :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities(strtoupper($exp->father_name), ENT_QUOTES); ?></font></td>
                                        <td><strong>Gender: </strong> <font color="<?php echo $font_color; ?>">&nbsp;<?php echo htmlentities($ext_gender, ENT_QUOTES); ?></font></td>                                    
                                    </tr>
                                    <tr>
                                        <td><strong>Legal Heir of :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities(strtoupper($exp->parent_name), ENT_QUOTES); ?></font></td>
                                        <td><strong>Religion :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities(strtoupper($exp->extra_party_religion_name), ENT_QUOTES); ?></font></td>
                                        <td><strong>Caste :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities(strtoupper($exp->extra_party_caste_name), ENT_QUOTES); ?></font></td>
                                        <td colspan="1"><strong>Age :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($extra_party_age, ENT_QUOTES); ?></font></td>
                                    </tr>                                
                                <?php } ?>
                                <tr>                                        
                                    <td><strong>Email :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($exp->pet_email, ENT_QUOTES); ?></font></td>    
                                    <td><strong>Mobile :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($exp->pet_mobile, ENT_QUOTES); ?></font></td>                                    
                                    <td colspan="2"><strong>Performa Flag:</strong><font color="<?php echo $font_color; ?>"><?php echo htmlentities($exp->performaresflag, ENT_QUOTES); ?></font></td>
                                </tr>
                                <tr>
                                    <td colspan="3"><strong>Address :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($extra_party_address, ENT_QUOTES); ?></font> </td>
                                    <td ><strong>Police Station :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities(strtoupper($exp->extra_party_ps_name), ENT_QUOTES); ?></font></td>
                                </tr> 

                                <?php if ($exp->other_info_flag == 'Y') { ?>
                                    <tr>
                                        <td colspan="4" style="text-align: center"><font color="#29579E"><i class="fa fa-plus" style="float: right;"></i> <b>Other Information </b></font></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Passport No:</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities(strtoupper($exp->passportno), ENT_QUOTES); ?></font></td>
                                        <td><strong>PAN No:</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities(strtoupper($exp->panno), ENT_QUOTES); ?></font></td>
                                        <td><strong>Fax No:</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($exp->pet_fax, ENT_QUOTES); ?></font></td>
                                        <td><strong>Phone No:</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($exp->pet_phone, ENT_QUOTES); ?></font></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Country:</strong>&nbsp; <font color="<?php echo $font_color; ?>"><?php echo htmlentities(strtoupper($exp->country), ENT_QUOTES); ?></font></td>
                                        <td><strong>Nationality:</strong>&nbsp; <font color="<?php echo $font_color; ?>"><?php echo htmlentities(strtoupper($exp->pet_nationality), ENT_QUOTES); ?></font></td>
                                        <td colspan="2"><strong>Occupation:</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities(strtoupper($exp->pet_occu), ENT_QUOTES); ?></font></td>                                                                            
                                    </tr>                                        
                                    <tr>
                                        <td colspan="4"><strong>Alternate Address:</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($extra_party_alt_address, ENT_QUOTES); ?></font></td>                                            
                                    </tr>
                                    <?php
                                }
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="4"><strong>Legal Representatives are not added.</strong></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <a href="#demo_18" class="list-group-item" style="background: #EDEDED;" data-toggle="collapse" data-parent="#MainMenu"><i class="fa fa-plus" style="float: right;"></i> <b>Case Details</b></a>             
    <div class="collapse" id="demo_18">
        <div class="x_panel">
            <?php
            if (!empty($efiling_civil_data[0]['offense_date'])) {
                $offense_date = date('d/m/Y', strtotime($efiling_civil_data[0]['offense_date']));
            } else {
                $offense_date = '';
            }
            ?>
            <div class="table-responsive">
                <div class="text-right"><a href="<?php echo base_url('new_case/case_detail'); ?>"><i class="fa fa-pencil"></i></a></div>
                <table id="" class="table dt-responsive nowrap" cellspacing="0" width="100%">
                    <tbody>
                        <tr>
                            <td><strong>Cause of Action :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_data[0]['causeofaction'], ENT_QUOTES); ?></font></td>
                            <td><strong>Date of Cause of Action  :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($offense_date, ENT_QUOTES); ?></font></td> 
                        </tr>
                        <tr>
                            <td><strong>Important Information or Subject or Reason :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_data[0]['subject1'], ENT_QUOTES); ?></font></td>
                            <td><strong>Valuation of Subject Matter :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities(htmlentities($efiling_civil_data[0]['amount'], ENT_QUOTES), ENT_QUOTES); ?></font></td>
                        </tr>
                        <tr>
                            <td><strong>Hide Parties:</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_data[0]['hide_partyname'], ENT_QUOTES); ?></font></td>
                            <td><strong>Relief Claimed :</strong>&nbsp; <font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_data[0]['relief_offense'], ENT_QUOTES); ?></font></td>

                        </tr>
                        <tr>
                            <td colspan="2" align="center"><strong>&nbsp; <font color="#31B0D5" style="font-size: 14px;">Place of Dispute for Jurisdiction </font></strong></td>
                        </tr>
                        <tr>
                            <td><strong>State :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_master_data[0]->case_state_name, ENT_QUOTES); ?></font></td>
                            <td><strong>Taluka :</strong>&nbsp; <font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_master_data[0]->case_taluka_name, ENT_QUOTES); ?></font></td>

                        </tr>
                        <tr>
                            <td><strong>District :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_master_data[0]->case_dist_name, ENT_QUOTES); ?></font></td>
                            <td><strong>Village :</strong>&nbsp; <font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_master_data[0]->case_village_name, ENT_QUOTES); ?></font></td>

                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <a href="#demo_5" class="list-group-item" style="background: #EDEDED;" data-toggle="collapse" data-parent="#MainMenu"><i class="fa fa-plus" style="float: right;"></i> <b>Acts-Sections</b></a>             
    <div class="collapse" id="demo_5">
        <div class="x_panel">
            <div class="table-responsive">
                <div class="text-right"><a href="<?php echo base_url('new_case/act_section'); ?>"><i class="fa fa-pencil"></i></a></div>
                <table id="" class="table dt-responsive nowrap" cellspacing="0" width="100%">
                    <tbody>
                        <?php if (!empty($efiling_civil_data[0]['under_sec1'])) { ?>
                            <tr>
                                <td><strong>Act 1:</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_master_data[0]->act_name_1, ENT_QUOTES); ?></font></td>
                                <td colspan="2"><strong>Under Section 1:</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_data[0]['under_sec1'], ENT_QUOTES); ?></font></td>
                            </tr>
                        <?php } if (!empty($efiling_civil_data[0]['under_sec2'])) { ?>
                            <tr>
                                <td><strong>Act 2:</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_master_data[0]->act_name_2, ENT_QUOTES); ?></font></td>
                                <td colspan="2"><strong>Under Section 2:</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_data[0]['under_sec2'], ENT_QUOTES); ?></font></td>
                            </tr>
                        <?php } if (!empty($efiling_civil_data[0]['under_sec3'])) { ?>
                            <tr>
                                <td><strong>Act 3:</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_master_data[0]->act_name_3, ENT_QUOTES); ?></font></td>
                                <td colspan="2"><strong>Under Section 3:</strong>&nbsp; <font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_data[0]['under_sec3'], ENT_QUOTES); ?></font></td>

                            </tr>
                        <?php } if (!empty($efiling_civil_data[0]['under_sec4'])) { ?>
                            <tr>
                                <td><strong>Act 4:</strong>&nbsp; <font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_master_data[0]->act_name_4, ENT_QUOTES); ?></font></td>
                                <td colspan="2"><strong>Under Section 4:</strong>&nbsp; <font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_data[0]['under_sec4'], ENT_QUOTES); ?></font></td>
                            </tr>
                        <?php } if (!empty($efiling_civil_data[0]['under_sec5'])) { ?>
                            <tr>
                                <td><strong>Act 5:</strong>&nbsp; <font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_master_data[0]->act_name_5, ENT_QUOTES); ?></font></td>
                                <td colspan="2"><strong>Under Section 5:</strong>&nbsp; <font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_data[0]['under_sec5'], ENT_QUOTES); ?></font></td>
                            </tr>
                        <?php }if (!empty($efiling_civil_data[0]['under_sec6'])) { ?>
                            <tr>
                                <td><strong>Act 6:</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_master_data[0]->act_name_6, ENT_QUOTES); ?></font></td>
                                <td colspan="2"><strong>Under Section 6:</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_data[0]['under_sec6'], ENT_QUOTES); ?></font></td>
                            </tr>
                        <?php }if (!empty($efiling_civil_data[0]['under_sec7'])) { ?>
                            <tr>
                                <td><strong>Act 7:</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_master_data[0]->act_name_7, ENT_QUOTES); ?></font></td>
                                <td colspan="2"><strong>Under Section 7:</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_data[0]['under_sec7'], ENT_QUOTES); ?></font></td>
                            </tr>
                        <?php }if (!empty($efiling_civil_data[0]['under_sec8'])) { ?>
                            <tr>
                                <td><strong>Act 8:</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_master_data[0]->act_name_8, ENT_QUOTES); ?></font></td>
                                <td colspan="2"><strong>Under Section 8:</strong>&nbsp; <font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_data[0]['under_sec8'], ENT_QUOTES); ?></font></td>

                            </tr>
                        <?php }if (!empty($efiling_civil_data[0]['under_sec9'])) { ?>
                            <tr>
                                <td><strong>Act 9:</strong>&nbsp; <font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_master_data[0]->act_name_9, ENT_QUOTES); ?></font></td>
                                <td colspan="2"><strong>Under Section 9:</strong>&nbsp; <font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_data[0]['under_sec9'], ENT_QUOTES); ?></font></td>
                            </tr>
                        <?php }if (!empty($efiling_civil_data[0]['under_sec10'])) { ?>
                            <tr>
                                <td><strong>Act 10:</strong>&nbsp; <font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_master_data[0]->act_name_10, ENT_QUOTES); ?></font></td>
                                <td colspan="2"><strong>Under Section 10:</strong>&nbsp; <font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_data[0]['under_sec10'], ENT_QUOTES); ?></font></td>
                            </tr><?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>        
    <?php if ($_SESSION['breadcrumb_enable']['efiling_for_type_id'] == E_FILING_FOR_HIGHCOURT || $_SESSION['breadcrumb_enable']['matter_type'] == '2' || $_SESSION['breadcrumb_enable']['efiling_type'] == E_FILING_TYPE_CDE) { ?>
        <a href="#demo_4" class="list-group-item" style="background: #EDEDED;" data-toggle="collapse" data-parent="#MainMenu"><i class="fa fa-plus" style="float: right;"></i> <b>Earlier Court Details</b></a>
        <div class="collapse" id="demo_4">
            <div class="x_panel">
                <div class="table-responsive">
                    <div class="text-right"><a href="<?php echo base_url('new_case/subordinate_court'); ?>"><i class="fa fa-pencil"></i></a></div>
                    <table id="" class="table dt-responsive nowrap" cellspacing="0" width="100%">
                        <?php if ($_SESSION['breadcrumb_enable']['efiling_for_type_id'] == E_FILING_FOR_ESTABLISHMENT) { ?>
                            <tbody><tr>
                                    <td><strong>Earlier Court Name:</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_master_data[0]->lower_court_name, ENT_QUOTES); ?></font></td>
                                    <td><strong>CNR Number:</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo $efiling_civil_data[0]['lower_cino'] == '' ? '' : htmlentities(cin_preview($efiling_civil_data[0]['lower_cino']), ENT_QUOTES); ?></font></td>
                                    <td><strong>Judge Name:</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_data[0]['lower_judge_name'], ENT_QUOTES); ?></font></td>
                                </tr>
                                <tr>
                                    <td><strong>Case Type:</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_master_data[0]->lower_court_case_type, ENT_QUOTES); ?></font></td>
                                    <td><strong><?php echo $efiling_civil_data[0]['filing_case'] == 1 ? 'Filing No.' : 'Case No.'; ?> :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities(ltrim(substr($efiling_civil_data[0]['lower_court'], 4, 7), '0'), ENT_QUOTES); ?></font></td>                                 
                                    <td><strong><?php echo $efiling_civil_data[0]['filing_case'] == 1 ? 'Filing Year' : 'Case Year'; ?> :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities(substr($efiling_civil_data[0]['lower_court'], 11, 4), ENT_QUOTES); ?></font></td>
                                </tr>
                                <tr>
                                    <td><strong>Date of Decision:</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_data[0]['lower_court_dec_dt'], ENT_QUOTES) ? date('d/m/Y', strtotime($efiling_civil_data[0]['lower_court_dec_dt'])) : ''; ?></font></td>
                                    <td><strong>CC Applied Date:</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_data[0]['lcc_applied_date'], ENT_QUOTES) ? date('d/m/Y', strtotime($efiling_civil_data[0]['lcc_applied_date'])) : ''; ?></font></td>
                                    <td><strong>CC Ready Date:</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_data[0]['lcc_received_date'], ENT_QUOTES) ? date('d/m/Y', strtotime($efiling_civil_data[0]['lcc_received_date'])) : ''; ?></font></td>
                                </tr>
                            </tbody>
                            <?php
                        } else {

                            if ($subordinate_court_data[0]['sub_qj_high'] == '1') {
                                ?>

                                <tbody>
                                    <tr>
                                        <td colspan="3"><font color="#ff0000"><b style="text-align: center"><?php echo htmlentities('First Appellete Court Details', ENT_QUOTES); ?></b></font></td>
                                    </tr>
                                    <tr>
                                        <td><strong>State :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_master_data[0]->app_court_state_name, ENT_QUOTES); ?></font></td>
                                        <td><strong>District :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_master_data[0]->app_court_distt_name, ENT_QUOTES); ?></font></td>
                                        <td><strong>Earlier Court Name :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_master_data[0]->app_court_sub_court_name, ENT_QUOTES); ?></font></td>
                                    </tr>
                                    <tr>
                                        <td><strong>CNR Number:</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo $subordinate_court_data[0]['lower_cino'] == '' ? '' : htmlentities(cin_preview($subordinate_court_data[0]['lower_cino']), ENT_QUOTES); ?></font></td>
                                        <td colspan="2"><strong>Judge Name:</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities(strtoupper($subordinate_court_data[0]['lower_judge_name']), ENT_QUOTES); ?></font></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Case Type :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_master_data[0]->app_court_sub_case_type, ENT_QUOTES); ?></font></td>
                                        <td><strong><?php echo $subordinate_court_data[0]['filing_case'] == 1 ? 'Filing No.' : 'Case No.'; ?> :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities(ltrim(substr($subordinate_court_data[0]['lower_court'], 4, 7), '0'), ENT_QUOTES); ?></font></td>                                 
                                        <td><strong><?php echo $subordinate_court_data[0]['filing_case'] == 1 ? 'Filing Year' : 'Case Year'; ?> :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities(substr($subordinate_court_data[0]['lower_court'], 11, 4), ENT_QUOTES); ?></font></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Date of Decision :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($subordinate_court_data[0]['lower_court_dec_dt'], ENT_QUOTES) ? date('d/m/Y', strtotime($subordinate_court_data[0]['lower_court_dec_dt'])) : ''; ?></font></td>
                                        <td><strong>CC Applied Date :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($subordinate_court_data[0]['lcc_applied_date'], ENT_QUOTES) ? date('d/m/Y', strtotime($subordinate_court_data[0]['lcc_applied_date'])) : ''; ?></font></td>
                                        <td><strong>CC Ready Date :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($subordinate_court_data[0]['lcc_received_date'], ENT_QUOTES) ? date('d/m/Y', strtotime($subordinate_court_data[0]['lcc_received_date'])) : ''; ?></font></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3"><font color="#ff0000"><b style="text-align: center"><?php echo htmlentities('Trial Court Details', ENT_QUOTES); ?></b></font></td>
                                    </tr>
                                    <tr>
                                        <td><strong>State :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_master_data[0]->trial_court_state_name, ENT_QUOTES); ?></font></td>
                                        <td><strong>District :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_master_data[0]->trial_court_distt_name, ENT_QUOTES); ?></font></td>
                                        <td><strong>Earlier Court Name :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_master_data[0]->trial_court_sub_court_name, ENT_QUOTES); ?></font></td>
                                    </tr>
                                    <tr>
                                        <td><strong>CNR Number :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo $subordinate_court_data[1]['lower_cino'] == '' ? '' : htmlentities(cin_preview($subordinate_court_data[1]['lower_cino']), ENT_QUOTES); ?></font></td>
                                        <td colspan="2"><strong>Judge Name :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities(strtoupper($subordinate_court_data[1]['lower_judge_name']), ENT_QUOTES); ?></font></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Case Type :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_master_data[0]->trial_court_case_type, ENT_QUOTES); ?></font></td>
                                        <td><strong><?php echo $subordinate_court_data[1]['filing_case'] == 1 ? 'Filing No.' : 'Case No.'; ?> :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities(ltrim(substr($subordinate_court_data[1]['lower_court'], 4, 7), '0'), ENT_QUOTES); ?></font></td>                                 
                                        <td><strong><?php echo $subordinate_court_data[1]['filing_case'] == 1 ? 'Filing Year' : 'Case Year'; ?> :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities(substr($subordinate_court_data[1]['lower_court'], 11, 4), ENT_QUOTES); ?></font></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Date of Decision :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($subordinate_court_data[1]['lower_court_dec_dt'], ENT_QUOTES) ? date('d/m/Y', strtotime($subordinate_court_data[1]['lower_court_dec_dt'])) : ''; ?></font></td>
                                        <td><strong>CC Applied Date :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($subordinate_court_data[1]['lcc_applied_date'], ENT_QUOTES) ? date('d/m/Y', strtotime($subordinate_court_data[1]['lcc_applied_date'])) : ''; ?></font></td>
                                        <td><strong>CC Ready Date :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($subordinate_court_data[1]['lcc_received_date'], ENT_QUOTES) ? date('d/m/Y', strtotime($subordinate_court_data[1]['lcc_received_date'])) : ''; ?></font></td>
                                    </tr>
                                </tbody>
                                <?php
                            }
                            if ($subordinate_court_data[0]['sub_qj_high'] == '2') {
                                ?>
                                <tbody>
                                    <tr>
                                        <td colspan="3"><font color="#ff0000"><b style="text-align: center"><?php echo htmlentities('First Appellete Court Details', ENT_QUOTES); ?></b></font></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Case / Reference No :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities(strtoupper($subordinate_court_data[0]['qjnumber']), ENT_QUOTES); ?></font></td>
                                        <td colspan="2"><strong>Date of Decision :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($subordinate_court_data[0]['date_of_order'], ENT_QUOTES) ? date('d/m/Y', strtotime($subordinate_court_data[0]['date_of_order'])) : ''; ?></font></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3"><font color="#ff0000"><b style="text-align: center"><?php echo htmlentities('Trial Court Details', ENT_QUOTES); ?></b></font></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Case / Reference No :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities(strtoupper($subordinate_court_data[1]['qjnumber']), ENT_QUOTES); ?></font></td>
                                        <td colspan="2"><strong>Date of Decision :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($subordinate_court_data[1]['date_of_order'], ENT_QUOTES) ? date('d/m/Y', strtotime($subordinate_court_data[1]['date_of_order'])) : ''; ?></font></td>
                                    </tr>

                                </tbody>
                                <?php
                            }
                            if ($subordinate_court_data[0]['sub_qj_high'] == '3') {
                                ?>
                                <tbody>
                                    <tr>
                                        <td colspan="3"><font color="#ff0000"><b style="text-align: center"><?php echo htmlentities('High Court Details', ENT_QUOTES); ?></b></font></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3"><strong>Case Type :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_master_data[0]->high_court_case_type, ENT_QUOTES); ?></font></td>
                                    </tr>
                                    <tr>
                                        <td><strong>CNR Number:</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo $subordinate_court_data[0]['lower_cino'] == '' ? '' : htmlentities(cin_preview($subordinate_court_data[0]['lower_cino']), ENT_QUOTES); ?></font></td>
                                        <td><strong><?php echo $subordinate_court_data[0]['filing_case'] == 1 ? 'Filing No.' : 'Case No.'; ?> :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities(ltrim(substr($subordinate_court_data[0]['lower_court'], 4, 7), '0'), ENT_QUOTES); ?></font></td>                                 
                                        <td><strong><?php echo $subordinate_court_data[0]['filing_case'] == 1 ? 'Filing Year' : 'Case Year'; ?> :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities(substr($subordinate_court_data[0]['lower_court'], 11, 4), ENT_QUOTES); ?></font></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Date of Decision :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($subordinate_court_data[0]['lower_court_dec_dt'], ENT_QUOTES) ? date('d/m/Y', strtotime($subordinate_court_data[0]['lower_court_dec_dt'])) : ''; ?></font></td>
                                        <td><strong>CC Applied Date :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($subordinate_court_data[0]['lcc_applied_date'], ENT_QUOTES) ? date('d/m/Y', strtotime($subordinate_court_data[0]['lcc_applied_date'])) : ''; ?></font></td>
                                        <td><strong>CC Ready Date :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($subordinate_court_data[0]['lcc_received_date'], ENT_QUOTES) ? date('d/m/Y', strtotime($subordinate_court_data[0]['lcc_received_date'])) : ''; ?></font></td>
                                    </tr>
                                </tbody>
                                <?php
                            }
                        }
                        ?>
                    </table> 
                </div>
            </div>
        </div>
    <?php } ?>
    <?php if ($_SESSION['breadcrumb_enable']['ci_cri'] == '3' || $_SESSION['breadcrumb_enable']['efiling_type'] == E_FILING_TYPE_CDE) { ?>
        <a href="#demo_7" class="list-group-item" style="background: #EDEDED;" data-toggle="collapse" data-parent="#MainMenu"><i class="fa fa-plus" style="float: right;"></i> <b>Police Station</b></a>             
        <div class="collapse" id="demo_7">
            <div class="x_panel">
                <div class="table-responsive">
                    <div class="text-right"><a href="<?php echo base_url('new_case/police_station'); ?>"><i class="fa fa-pencil"></i></a></div>
                    <table id="" class="table dt-responsive nowrap" cellspacing="0" width="100%">
                        <tbody><tr>
                                <td><strong>Police Challan or Private Complaint :</strong>&nbsp;<font color="<?php echo $font_color; ?>">
                                    <?php
                                    if ($police_station[0]->police_private == '1') {
                                        echo htmlentities('Police Chalan', ENT_QUOTES);
                                    } elseif ($police_station[0]->police_private == '2') {
                                        echo htmlentities('Private Complaint', ENT_QUOTES);
                                    }
                                    ?>
                                    </font></td>
                                <td><strong>State:</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_master_data[0]->police_st_state_name, ENT_QUOTES); ?></font></td>
                                <td><strong>District:</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_master_data[0]->police_st_dist_name, ENT_QUOTES); ?></font></td>
                            </tr>
                            <tr>
                                <td><strong>Police Station :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_master_data[0]->police_station_name, ENT_QUOTES); ?></font></td>
                                <td><strong>Date of Offence:</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($police_station[0]->offense_date, ENT_QUOTES) ? date('d/m/Y', strtotime($police_station[0]->offense_date)) : ''; ?></font></td>
                                <td><strong>Date of Filing Charge Sheet :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($police_station[0]->dt_chargesheet, ENT_QUOTES) ? date('d/m/Y', strtotime($police_station[0]->dt_chargesheet)) : ''; ?></font></td>
                            </tr>
                            <tr>
                                <td><strong>FIR Type:</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_master_data[0]->fir_type_name, ENT_QUOTES); ?></font></td>
                                <td><strong>FIR Number :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($police_station[0]->fir_no, ENT_QUOTES); ?></font></td>
                                <td><strong>Year :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($police_station[0]->fir_year, ENT_QUOTES); ?></font></td>
                            </tr>
                            <tr>
                                <td><strong>Investigation Agency:</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_master_data[0]->investigation_agency, ENT_QUOTES); ?></font></td>
                                <td><strong>FIR Filing Date:</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($police_station[0]->fir_date, ENT_QUOTES) ? date('d/m/Y', strtotime($police_station[0]->fir_date)) : ''; ?></font></td>
                                <td><strong>Investigating Officer :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($police_station[0]->investofficer, ENT_QUOTES); ?></font></td>
                            </tr>
                            <tr>
                                <td><strong>Investigating Officer 1:</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($police_station[0]->investofficer1, ENT_QUOTES); ?></font></td>
                                <td><strong>Belt No:</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($police_station[0]->beltno, ENT_QUOTES); ?></font></td>
                                <td><strong>Belt No 1 :</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($police_station[0]->beltno1, ENT_QUOTES); ?></font></td>
                            </tr>
                            <tr>
                                <td><strong>Trials:</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($efiling_civil_master_data[0]->trials_name, ENT_QUOTES); ?></font></td>
                                <td colspan="2"><strong>Offence Remark:</strong>&nbsp;<font color="<?php echo $font_color; ?>"><?php echo htmlentities($police_station[0]->causeofaction, ENT_QUOTES); ?></font></td>
                            </tr>
                        </tbody>
                    </table> 
                </div>
            </div>
        </div>
    <?php } ?>            
    <?php if ($_SESSION['breadcrumb_enable']['matter_type'] == '3' || $_SESSION['breadcrumb_enable']['efiling_type'] == E_FILING_TYPE_CDE) { ?>
        <a href="#demo_13" class="list-group-item" style="background: #EDEDED;" data-toggle="collapse" data-parent="#MainMenu"><i class="fa fa-plus" style="float: right;"></i> <b>Main Matter</b></a>
        <div class="collapse" id="demo_13">
            <div class="x_panel">
                <div class="text-right"><a href="<?php echo base_url('new_case/main_matter'); ?>"><i class="fa fa-pencil"></i></a></div>
                <?php include 'main_matter_data_list.php'; ?>
            </div>
        </div>
    <?php } ?>
    <?php if ($_SESSION['breadcrumb_enable']['macp'] == 'Y' || $_SESSION['breadcrumb_enable']['efiling_type'] == E_FILING_TYPE_CDE) { ?>
        <a href="#demo_6" class="list-group-item" style="background: #EDEDED;" data-toggle="collapse" data-parent="#MainMenu"><i class="fa fa-plus" style="float: right;"></i> <b>MVC (Motor Vehicle Claim )</b></a>
        <div class="collapse" id="demo_6">
            <div class="x_panel">
                <div class="text-right"><a href="<?php echo base_url('new_case/upload_mvc'); ?>"><i class="fa fa-pencil"></i></a></div>
                <div class="panel panel-body panel-default">
                    <?php
                    if (count($mvc_details) > 0) {
                        $i = 1;
                        foreach ($mvc_details as $total_application) {
                            echo '<div style="text-align:center;color:red;font-size:15px">S.No. ' . $i++ . '.</div>';
                            if ($total_application->other_po_stn != 'Y') {
                                ?>
                                <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-6">State :</label>
                                        <div class="col-md-6">
                                            <p><font color="<?php echo $font_color; ?>"><?php echo htmlentities($total_application->state_id_name, ENT_QUOTES); ?></font></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-6">District :</label>
                                        <div class="col-md-6">
                                            <p><font color="<?php echo $font_color; ?>"><?php echo htmlentities($total_application->dist_code_name, ENT_QUOTES); ?></font></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <div class="form-group">
                                        <label class="control-label col-md-6">Taluka :</label>
                                        <div class="col-md-6">
                                            <p><font color="<?php echo $font_color; ?>"><?php echo htmlentities($total_application->taluka_code_name, ENT_QUOTES); ?></font></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            <?php } ?>

                            <div class="col-sm-6 col-md-4">
                                <?php if ($total_application->other_po_stn == 'Y') { ?>
                                    <div class="form-group">
                                        <label class="control-label col-md-6">Other Police Station:</label>
                                        <div class="col-md-6">
                                            <p><font color="<?php echo $font_color; ?>"><?php echo htmlentities($total_application->other_police_stn, ENT_QUOTES); ?></font></p>
                                        </div>
                                    </div>
                                <?php } else { ?>
                                    <div class="form-group">
                                        <label class="control-label col-md-6">Police Station:</label>
                                        <div class="col-md-6">
                                            <p><font color="<?php echo $font_color; ?>"><?php echo htmlentities($total_application->police_stn_code_name, ENT_QUOTES); ?></font></p>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="col-sm-6 col-md-4">  
                                <div class="form-group">
                                    <label class="control-label col-md-6">FIR Type:</label>
                                    <div class="col-md-6">
                                        <p><font color="<?php echo $font_color; ?>">
                                            <?php
                                            if ($total_application->fir_type_code == 1) {
                                                $fir_type_code = 'I-1';
                                            } elseif ($total_application->fir_type_code == 2) {
                                                $fir_type_code = 'I-2';
                                            } elseif ($total_application->fir_type_code == 3) {
                                                $fir_type_code = 'I-3';
                                            } else {
                                                $fir_type_code = 'NA';
                                            }
                                            echo htmlentities($fir_type_code, ENT_QUOTES);
                                            ?>
                                            </font></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">      
                                <div class="form-group">
                                    <label class="control-label col-md-6">FIR No.:</label>
                                    <div class="col-md-6">
                                        <p><font color="<?php echo $font_color; ?>"><?php echo htmlentities($total_application->fir_no, ENT_QUOTES); ?></font></p>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-md-6">FIR Year :</label>
                                    <div class="col-md-6">
                                        <p><font color="<?php echo $font_color; ?>"><?php echo htmlentities($total_application->year, ENT_QUOTES); ?></font></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-md-6">Accident Place :</label>
                                    <div class="col-md-6">
                                        <p><font color="<?php echo $font_color; ?>"><?php echo htmlentities($total_application->accident_place, ENT_QUOTES); ?></font></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-md-6">Date of Accident :</label>
                                    <div class="col-md-6">
                                        <p><font color="<?php echo $font_color; ?>"><?php echo htmlentities(date('d-m-Y', strtotime($total_application->accident_date)), ENT_QUOTES); ?></font></p>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-md-6">Time of Accident :</label>
                                    <div class="col-md-6">
                                        <p><font color="<?php echo $font_color; ?>"><?php echo htmlentities($total_application->accident_time, ENT_QUOTES); ?></font></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-md-6">Compensation  :</label>
                                    <div class="col-md-6">
                                        <p><font color="<?php echo $font_color; ?>"><?php echo htmlentities($total_application->compensation, ENT_QUOTES); ?></font></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-md-6">Type of Injury :</label>
                                    <div class="col-md-6">
                                        <p><font color="<?php echo $font_color; ?>">
                                            <?php
                                            if ($total_application->injurytype == 1) {
                                                $injurytype = 'Simple';
                                            } elseif ($total_application->injurytype == 2) {
                                                $injurytype = 'Serious';
                                            } elseif ($total_application->injurytype == 3) {
                                                $injurytype = 'Death';
                                            } else {
                                                $injurytype = 'Other';
                                            }
                                            echo htmlentities($injurytype, ENT_QUOTES);
                                            ?>

                                            </font></p>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-md-6">Vehicle Type :</label>
                                    <div class="col-md-6">
                                        <p><font color="<?php echo $font_color; ?>"><?php echo htmlentities($total_application->vehicle_type, ENT_QUOTES); ?></font></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-md-6">Vehicle Regn. No :</label>
                                    <div class="col-md-6">
                                        <p><font color="<?php echo $font_color; ?>"><?php echo htmlentities($total_application->vehicle_regn_no, ENT_QUOTES); ?></font></p>
                                    </div>
                                </div>   
                            </div>    
                            <div class="col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-md-6">Driving License :</label>
                                    <div class="col-md-6">
                                        <p><font color="<?php echo $font_color; ?>"><?php echo htmlentities($total_application->driving_license, ENT_QUOTES); ?></font></p>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-md-6">Vehicle Owner Name :</label>
                                    <div class="col-md-6">
                                        <p><font color="<?php echo $font_color; ?>"><?php echo htmlentities($total_application->owner_name, ENT_QUOTES); ?></font></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-md-6">Insurance Company :</label>
                                    <div class="col-md-6">
                                        <p><font color="<?php echo $font_color; ?>"><?php echo htmlentities($total_application->insurance_company, ENT_QUOTES); ?></font></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label class="control-label col-md-6">Issuing Authority :</label>
                                    <div class="col-md-6">
                                        <p><font color="<?php echo $font_color; ?>"><?php echo htmlentities($total_application->issuing_authority, ENT_QUOTES); ?></font></p>
                                    </div>
                                </div>
                            </div>  
                            <div class="clearfix"></div>
                            <hr>
                            <?php
                        }
                    }
                    ?>

                </div>
            </div>
        </div>
    <?php } ?>

    <?php if ($_SESSION['breadcrumb_enable']['efiling_type'] != E_FILING_TYPE_CDE) { ?>
        <a href="#demo_12" class="list-group-item" style="background: #EDEDED;" data-toggle="collapse" data-parent="#MainMenu"><i class="fa fa-plus" style="float: right;"></i> <b>Sign Method</b></a>
        <div class="collapse" id="demo_12">
            <div class="x_panel">
                <div class="text-right"><a href="<?php echo base_url('new_case/sign_method'); ?>"><i class="fa fa-pencil"></i></a></div>
                <?php include 'sign_method_view.php'; ?>
            </div>
        </div>


        <a href="#demo_9" class="list-group-item" style="background: #EDEDED;" data-toggle="collapse" data-parent="#MainMenu"><i class="fa fa-plus" style="float: right;"></i> <b>Upload Documents </b></a>     
        <div class="collapse" id="demo_9">
            <div class="x_panel">
                <div class="text-right"><a href="<?php echo base_url('new_case/upload_docs'); ?>"><i class="fa fa-pencil"></i></a></div>
                <?php $this->load->view('uploaded_document_list.php'); ?>
            </div>
        </div>
        <a href="#demo_10" class="list-group-item" style="background: #EDEDED;" data-toggle="collapse" data-parent="#MainMenu"><i class="fa fa-plus" style="float: right;"></i> <b>Fees Paid</b></a>
        <div class="collapse" id="demo_10">
            <div class="x_panel">
                <div class="form-response" id="msg"></div>
                <div id="modal_loader">
                    <img id="loader_img" style="margin-top: 30px;margin-left: 30px;display:block;" src="<?php echo base_url(); ?>assets/images/loading-data.gif" />
                </div>
                <div class="text-right"><a href="<?php echo base_url('new_case/courtFee'); ?>"><i class="fa fa-pencil"></i></a></div>
                <?php
                include 'court_fee_lists_view.php';
                ?>
            </div>
        </div>
        <a href="#demo_11" class="list-group-item" style="background: #EDEDED;" data-toggle="collapse" data-parent="#MainMenu"><i class="fa fa-plus" style="float: right;"></i> <b>Affirmation</b></a>
        <div class="collapse" id="demo_11">
            <div class="x_panel">
                <div class="text-right"><a href="<?php echo base_url('new_case/affirmation'); ?>"><i class="fa fa-pencil"></i></a></div>
                <div class="panel panel-body panel-default">
                    <div class="table-responsive">
                        <?php if ($esigned_docs_details[0]->is_data_valid == 't') { ?>
                            <a href="<?= base_url() ?>stage_list/view_signed_oath/<?php echo htmlentities(url_encryption(trim($esigned_docs_details[0]->id)), ENT_QUOTES); ?>" target="_blank"><img src="<?= base_url() ?>assets/images/pdf.png"><strong>View Applicant/Complainant/Litigant Signed Affirmation </strong></a></font>
                            </br></br>
                            <?php
                        }
                        if ($verified_docs[0]->is_data_valid == 't') {
                            include 'mobile_otp_affirmation_view.php';
                        } else {
                            ?>
                            <div class="text-danger"> <strong>NOTE :</strong> eSign signature is properly visible in Adobe Acrobat Reader DC ( Version 11 or greater).</div>
                        <?php } ?>

                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

</div>

</div>
<?php $this->load->view('modals.php'); ?>

</div>
</div>
</div>
</div>
</div>
</div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $('#add_org_not_listed,.extra_party_org_not_listed').on('submit', function () {
            if ($('#add_org_not_listed,.extra_party_org_not_listed').valid()) {
                var form_data = $(this).serialize();
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>New_case/add_organisation",
                    data: form_data,
                    success: function (data) {
                        var resArr = data.split('@@@');
                        if (resArr[0] == 1) {
                            $('#msg').show();
                            $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                        } else if (resArr[0] == 2) {
                            $('#msg').show();
                            $(".form-response").html("<p class='message valid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                            setTimeout(function () {
                                $('#msg').hide();
                                window.location.href = '<?php echo base_url('new_case/view'); ?>';
                            }, 3000);
                        }
                        $.getJSON("<?php echo base_url() . 'Login/get_csrf_new'; ?>", function (result) {
                            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        });
                    },
                    error: function () {
                        $.getJSON("<?php echo base_url() . 'Login/get_csrf_new'; ?>", function (result) {
                            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        });
                    }
                });
                return false;
            } else {
                return false;
            }
        });
    });


</script>
<style>
    .blinking{
        animation:blinkingText 0.9s infinite;

    }
    @keyframes blinkingText{
        0%{     color: red;    }
        49%{    color: transparent; }
        50%{    color: transparent; }
        99%{    color:transparent;  }
        100%{   color: #000;    }
    }

</style>