<?php
if (isset($_SESSION['efiling_for_details']['case_type_pet_title']) && !empty($_SESSION['efiling_for_details']['case_type_pet_title'])) {
    $case_type_pet_title = htmlentities($_SESSION['efiling_for_details']['case_type_pet_title'], ENT_QUOTES);
} elseif (isset($efiling_civil_data[0]['case_type_pet_title']) && !empty($efiling_civil_data[0]['case_type_pet_title'])) {
    $case_type_pet_title = htmlentities($efiling_civil_data[0]['case_type_pet_title'], ENT_QUOTES);
} else {
    $case_type_pet_title = htmlentities('Caveator', ENT_QUOTES);
}

if (isset($_SESSION['efiling_for_details']['case_type_res_title']) && !empty($_SESSION['efiling_for_details']['case_type_res_title'])) {
    $case_type_res_title = htmlentities($_SESSION['efiling_for_details']['case_type_res_title'], ENT_QUOTES);
} elseif (isset($efiling_civil_data[0]['case_type_res_title']) && !empty($efiling_civil_data[0]['case_type_res_title'])) {
    $case_type_res_title = htmlentities($efiling_civil_data[0]['case_type_res_title'], ENT_QUOTES);
} else {
    $case_type_res_title = htmlentities('Caveatee', ENT_QUOTES);
}

if ($efiling_civil_data[0]['matter_type'] == '1') {
    $matter_type = 'Appeal';
} elseif ($efiling_civil_data[0]['ci_cri'] == '2') {
    $matter_type = 'Application';
} elseif ($efiling_civil_data[0]['ci_cri'] == '3') {
    $matter_type = 'Original';
} else {
    $matter_type = 'NA';
}

if ($efiling_civil_data[0]['pet_sex'] == '1') {
    $gender = 'Male';
} elseif ($efiling_civil_data[0]['pet_sex'] == '2') {
    $gender = 'Female';
} elseif ($efiling_civil_data[0]['pet_sex'] == '3') {
    $gender = 'Other';
} else {
    $gender = 'Other';
}


if ($efiling_civil_data[0]['res_sex'] == '1') {
    $res_gender = 'Male';
} elseif ($efiling_civil_data[0]['res_sex'] == '2') {
    $res_gender = 'Female';
} elseif ($efiling_civil_data[0]['res_sex'] == '3') {
    $res_gender = 'Other';
} else {
    $res_gender = 'Other';
}



if ($efiling_civil_data[0]['orgid'] == '0' || $efiling_civil_data[0]['orgid'] == '') {
    $lbl_complainant = $case_type_pet_title;
} else {
    $lbl_complainant = $case_type_pet_title . ' (Organisation)';
}
if ($efiling_civil_data[0]['resorgid'] == '0' || $efiling_civil_data[0]['resorgid'] == '') {
    $lbl_respondent = $case_type_res_title;
} else {
    $lbl_respondent = $case_type_res_title . ' (Organisation)';
}
?>
<div class="col-md-12 col-sm-12 col-xs-12">
    <?php if ($_SESSION['efiling_details']['ref_m_efiled_type_id'] != E_FILING_TYPE_CDE) { ?>
        <h2 style="text-align: center" >Case Information</h2>
        <hr>
    <?php } ?>
    <div class="panel panel-default"> 
        <div class="panel-body">
            <?php if ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_CDE) { ?>
                <div class="x_panel">
                    <div class="table-responsive">
                        <table id="" class="table dt-responsive nowrap" border="1" cellspacing="0"  style="background-color:#ffffff;font-size: 11px; " >

                            <tr> <td colspan="3"><strong style="text-align: center;font-size: 15px;">Acknowledgement</strong></td>  

                            </tr>
                            <tr><td rowspan="6" width="20%" valign="center" style="text-align: center; margin-top: 50%;"><img src="<?php echo base_url('assets/images/ecourts-logo.png'); ?>" ></td>
                                <td  width="40%" ><strong>CDE No. : </strong> <?php echo htmlentities(efile_preview($efiling_civil_data[0]['efiling_no']), ENT_QUOTES); ?> </td>  
                                <td  width="40%" ><strong>CDE Date/Time : </strong><?php echo htmlentities(date('d-m-Y H:i:s A', strtotime($efiling_civil_data[0]['create_on']))); ?> </td>  
                            </tr>
                            <tr>
                                <td colspan="3"><strong>Caveator : </strong> <?php echo htmlentities($efiling_civil_data[0]['pet_name'], ENT_QUOTES); ?> </td>  
                            </tr>
                            <tr>
                                <td colspan="3"><strong>Caveatee : </strong> <?php echo htmlentities($efiling_civil_data[0]['res_name'], ENT_QUOTES); ?> </td>  
                            </tr>
                            <tr>
                                <td colspan="3"><strong>Advocate : </strong> <?php echo htmlentities($_SESSION['login']['first_name'] . ' ' . $_SESSION['login']['last_name'], ENT_QUOTES); ?>
                                    (<?php echo htmlentities($_SESSION['login']['bar_reg_no'], ENT_QUOTES); ?>)  </td>  
                            </tr>
                            <tr>
                                <td colspan="3"><strong>CNR No : </strong>  <?php echo htmlentities($_SESSION['cnr_details']['efiling_case_reg_id'], ENT_QUOTES); ?> </td>  
                            </tr>
                            <tr>
                                <td colspan="2" ><strong style="text-align: right;">Generated Date :</strong>  <?php echo htmlentities(date('d-m-Y H:i:s'), ENT_QUOTES); ?> </td>  
                            </tr>

                        </table>  
                        <?php if (ENABLE_DISCLAIMER != '') { ?>
                            <p class="left-align" style="color: red;"><?php echo ENABLE_DISCLAIMER; ?></p>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
            <table  cellspacing="0" width="100%">
                <tr>
                    <td colspan="2"><b>eFiling For - </b><?php echo htmlentities($efiling_civil_data[0]['efiling_for_name'], ENT_QUOTES); ?></td>
                </tr>
                <tr>
                    <td colspan="2"><b>e-Filing No.-</b><?php echo htmlentities(efile_preview($efiling_civil_data[0]['efiling_no']), ENT_QUOTES); ?></td>
                </tr>
                <tr>
                    <td><b>Caveator - </b><?php echo htmlentities($efiling_civil_data[0]['pet_name'], ENT_QUOTES); ?></td>
                    <td><b>Caveatee - </b><?php echo htmlentities($efiling_civil_data[0]['res_name'], ENT_QUOTES); ?></td>
                </tr>
                <tr>
                    <?php $usertype_id = ($efiled_efiling_details[0]->ref_m_usertype_id == USER_ADVOCATE) ? 'Advocate' : 'Party-in-person'; ?>
                    <td><b>eFiled By - </b><?php echo htmlentities(strtoupper($efiled_efiling_details[0]->first_name . ' ' . $efiled_efiling_details[0]->last_name), ENT_QUOTES) . ' ( ' . htmlentities($usertype_id, ENT_QUOTES) . ')'; ?></td>
                    <td><b>eFiled On - </b><?php echo $efiled_efiling_details[0]->activated_on ? htmlentities(date('d-m-Y h:i:s A', strtotime($efiled_efiling_details[0]->activated_on)), ENT_QUOTES) : ''; ?></td>
                </tr>

            </table>

            <div class="clearfix"></div>
            <hr>
            <h2 style="text-align: center" ><?php echo $case_type_pet_title; ?></h2>
            <table id="" class="table dt-responsive nowrap" border="1" cellspacing="0" width="100%"  style="background-color:#ffffff;font-size: 11px; " >
                <tbody>
                    <tr>
                        <?php if ($efiling_civil_data[0]['orgid'] == '0' || $efiling_civil_data[0]['orgid'] == '') { ?>
                            <td colspan="3"><span id="label_petname"><strong><?php echo htmlentities($lbl_complainant, ENT_QUOTES); ?>:</strong></span>&nbsp;<font color="#36366c"><?php echo htmlentities($efiling_civil_data[0]['pet_name'], ENT_QUOTES) . htmlentities($efiling_civil_master_data[0]->pet_org_name, ENT_QUOTES); ?></font></td>
                        <?php } else { ?>
                            <td colspan="3"><span id="label_petname"><strong><?php echo htmlentities($lbl_complainant, ENT_QUOTES); ?>:</strong></span>&nbsp;<font color="#36366c"><?php echo htmlentities($efiling_civil_data[0]['pet_name'], ENT_QUOTES); ?></font></td>
                        <?php } ?>
                        <td><strong>Extra <?php echo $case_type_pet_title; ?> Count:</strong>&nbsp;<font color="#36366c"><?php echo htmlentities($efiling_civil_data[0]['pet_extracount'], ENT_QUOTES); ?></font></td>
                    </tr>
                    <?php if ($efiling_civil_data[0]['orgid'] == '0' || $efiling_civil_data[0]['orgid'] == '') { ?>
                        <tr>
                            <td><strong>Gender: </strong> <font color="#36366c">&nbsp;<?php echo htmlentities($gender, ENT_QUOTES); ?></font></td>
                            <td><strong>Relation:</strong> <font color="#36366c">&nbsp;<?php echo htmlentities($efiling_civil_master_data[0]->pet_relation, ENT_QUOTES); ?></font></td>
                            <td colspan="2"><strong>Relative Name:</strong>&nbsp;<font color="#36366c"><?php echo htmlentities($efiling_civil_data[0]['pet_father_name'], ENT_QUOTES); ?></font></td>
                        </tr>
                        <tr class="tr_petorg">
                            <td><strong>Age:</strong> <font color="#36366c">&nbsp;<?php echo $efiling_civil_data[0]['pet_age'] != 0 ? htmlentities($efiling_civil_data[0]['pet_age'], ENT_QUOTES) : ''; ?></font></td>
                            <td colspan="3"><strong>Date of Birth:</strong>&nbsp;<font color="#36366c"><?php echo $efiling_civil_data[0]['pet_dob'] ? date('d/m/Y', strtotime($efiling_civil_data[0]['pet_dob'])) : ''; ?></font></td>

                        </tr>
                    <?php } ?>
                    <tr class="tr_petorg">
                        <td><strong>Email:</strong>&nbsp;<font color="#36366c"><?php echo htmlentities($efiling_civil_data[0]['pet_email'], ENT_QUOTES); ?></font></td>
                        <td><strong>Mobile No.:</strong>&nbsp;<font color="#36366c"><?php echo htmlentities($efiling_civil_data[0]['pet_mobile'], ENT_QUOTES); ?></font></td>
                        <td colspan="2"><strong>Address:</strong>&nbsp;<font color="#36366c"><?php echo htmlentities($efiling_civil_data[0]['petadd'], ENT_QUOTES); ?></font> </td>

                    </tr>
                    <tr>
                        <td><strong>State:</strong>&nbsp; <font color="#36366c"><?php echo htmlentities($efiling_civil_master_data[0]->pet_state_name, ENT_QUOTES); ?></font></td>
                        <td><strong>District:</strong>&nbsp; <font color="#36366c"><?php echo htmlentities($efiling_civil_master_data[0]->pet_distt_name, ENT_QUOTES); ?></font></td>
                        <td><strong>Town:</strong>&nbsp;<font color="#36366c"><?php echo htmlentities($efiling_civil_master_data[0]->pet_town_name, ENT_QUOTES); ?></font></td>
                        <td><strong>Ward:</strong>&nbsp;<font color="#36366c"><?php echo htmlentities($efiling_civil_master_data[0]->pet_ward_name, ENT_QUOTES); ?></font></td>
                    </tr>
                    <tr>
                        <td><strong>Taluka:</strong>&nbsp;<font color="#36366c"><?php echo htmlentities($efiling_civil_master_data[0]->pet_taluka_name, ENT_QUOTES); ?></font></td>
                        <td><strong>Village:</strong>&nbsp;<font color="#36366c"><?php echo htmlentities($efiling_civil_master_data[0]->pet_village_name, ENT_QUOTES); ?></font></td>
                        <td><strong>Police Station:</strong>&nbsp;<font color="#36366c"><?php echo htmlentities($efiling_civil_master_data[0]->pet_ps_name, ENT_QUOTES); ?></font></td>
                        <td><strong>Pincode:</strong>&nbsp;<font color="#36366c"><?php echo $efiling_civil_data[0]['pet_pincode'] != 0 ? htmlentities($efiling_civil_data[0]['pet_pincode'], ENT_QUOTES) : ''; ?></font></td>
                    </tr>
                </tbody>
            </table>

            <h2 style="text-align: center" ><?php echo $case_type_res_title; ?></h2>
            <table id="" class="table dt-responsive nowrap" border="1" cellspacing="0" width="100%"  style="background-color:#ffffff;font-size: 11px; " >
                <tbody>

                    <tr>
                        <?php if ($efiling_civil_data[0]['resorgid'] == '0' || $efiling_civil_data[0]['resorgid'] == '') { ?>
                            <td colspan="3"><span id="label_petname"><strong><?php echo htmlentities($lbl_respondent, ENT_QUOTES); ?>:</strong></span>&nbsp;<font color="#36366c"><?php echo htmlentities($efiling_civil_data[0]['res_name'], ENT_QUOTES) . htmlentities($efiling_civil_master_data[0]->res_org_name, ENT_QUOTES); ?></font></td>
                        <?php } else { ?>
                            <td colspan="3"><span id="label_petname"><strong><?php echo htmlentities($lbl_respondent, ENT_QUOTES); ?>:</strong></span>&nbsp;<font color="#36366c"><?php echo htmlentities($efiling_civil_data[0]['res_name'], ENT_QUOTES); ?></font></td>
                        <?php } ?>
                        <td><strong>Extra <?php echo $case_type_res_title; ?> Count:</strong>&nbsp;<font color="#36366c"><?php echo htmlentities($efiling_civil_data[0]['res_extracount'], ENT_QUOTES); ?></font></td>

                    </tr>
                    <?php if ($efiling_civil_data[0]['resorgid'] == '0' || $efiling_civil_data[0]['resorgid'] == '') { ?>
                        <tr>
                            <td><strong>Gender: </strong> <font color="#36366c">&nbsp;<?php echo htmlentities($res_gender, ENT_QUOTES); ?></font></td>
                            <td><strong>Relation:</strong> <font color="#36366c">&nbsp;<?php echo htmlentities($efiling_civil_master_data[0]->res_relation, ENT_QUOTES) ?></font></td>
                            <td colspan="2"><strong>Relative Name:</strong>&nbsp;<font color="#36366c"><?php echo htmlentities($efiling_civil_data[0]['res_father_name'], ENT_QUOTES); ?></font></td>

                        </tr>

                        <tr class="tr_petorg">
                            <td><strong>Age:</strong> <font color="#36366c">&nbsp;<?php echo $efiling_civil_data[0]['res_age'] != 0 ? htmlentities($efiling_civil_data[0]['res_age'], ENT_QUOTES) : ''; ?></font></td>
                            <td colspan="3"><strong>Date of Birth:</strong>&nbsp;<font color="#36366c"><?php echo $efiling_civil_data[0]['res_dob'] ? date('d/m/Y', strtotime($efiling_civil_data[0]['res_dob'])) : ''; ?></font></td>

                        </tr>
                    <?php } ?>
                    <tr class="tr_petorg">
                        <td><strong>Email:</strong>&nbsp;<font color="#36366c"><?php echo htmlentities($efiling_civil_data[0]['res_email'], ENT_QUOTES); ?></font></td>
                        <td><strong>Mobile No.:</strong>&nbsp;<font color="#36366c"><?php echo htmlentities($efiling_civil_data[0]['res_mobile'], ENT_QUOTES); ?></font></td>
                        <td colspan="2"><strong>Address:</strong>&nbsp;<font color="#36366c"><?php echo htmlentities($efiling_civil_data[0]['resadd'], ENT_QUOTES); ?></font> </td>

                    </tr>
                    <tr>
                        <td><strong>State:</strong>&nbsp; <font color="#36366c"><?php echo htmlentities($efiling_civil_master_data[0]->res_state_name, ENT_QUOTES); ?></font></td>
                        <td><strong>District:</strong>&nbsp; <font color="#36366c"><?php echo htmlentities($efiling_civil_master_data[0]->res_distt_name, ENT_QUOTES); ?></font></td>
                        <td><strong>Town:</strong>&nbsp;<font color="#36366c"><?php echo htmlentities($efiling_civil_master_data[0]->res_town_name, ENT_QUOTES); ?></font></td>
                        <td><strong>Ward:</strong>&nbsp;<font color="#36366c"><?php echo htmlentities($efiling_civil_master_data[0]->res_ward_name, ENT_QUOTES); ?></font></td>
                    </tr>
                    <tr>
                        <td><strong>Taluka:</strong>&nbsp;<font color="#36366c"><?php echo htmlentities($efiling_civil_master_data[0]->res_taluka_name, ENT_QUOTES); ?></font></td>
                        <td><strong>Village:</strong>&nbsp;<font color="#36366c"><?php echo htmlentities($efiling_civil_master_data[0]->res_village_name, ENT_QUOTES); ?></font></td>
                        <td><strong>Police Station:</strong>&nbsp;<font color="#36366c"><?php echo htmlentities($efiling_civil_master_data[0]->res_ps_name, ENT_QUOTES); ?></font></td>
                        <td><strong>Pincode:</strong>&nbsp;<font color="#36366c"><?php echo $efiling_civil_data[0]['res_pincode'] != 0 ? htmlentities($efiling_civil_data[0]['res_pincode'], ENT_QUOTES) : ''; ?></font></td>                                
                    </tr>
                </tbody>
            </table>


            <h2 style="text-align: center">Extra Party</h2>
            <table id="" class="table dt-responsive nowrap" border="1" cellspacing="0" width="100%"  style="background-color:#ffffff;font-size: 11px; " >
                <tbody>
                    <?php
                    if (isset($extra_party_details) && count($extra_party_details) > 0) {
                        $i = 1;
                        $k = 1;
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

                            if (isset($exp->father_name) && !empty($exp->father_name)) {
                                if ($exp->father_flag == '1') {
                                    //    $ext_relation = 'Father Name';
                                    $ext_relation = 'Father';
                                } elseif ($exp->father_flag == '2') {
                                    $ext_relation = 'Mother';
                                    //  $ext_relation = 'Mother Name';
                                } elseif ($exp->father_flag == '3') {
                                    $ext_relation = 'Husband';
                                    //  $ext_relation = 'Husband Name';
                                } elseif ($exp->father_flag == '4') {
                                    $ext_relation = 'Other/None';
                                } else {
                                    $ext_relation = 'Other/None';
                                }
                            } else {
                                $ext_relation = 'Father/Mother/Husband Name';
                            }

                            if ($exp->type == '1') {
                                $j = $i++;
                                $party_type = htmlentities(ucwords(strtolower($case_type_pet_title)), ENT_QUOTES);
                                $form_name_n_id = 'extra_pet_orgid';
                                $extra_party_id = 'extra_party_pet_id';
                            } elseif ($exp->type == '2') {
                                $j = $k++;
                                $party_type = htmlentities(ucwords(strtolower($case_type_res_title)), ENT_QUOTES);
                                $form_name_n_id = 'extra_res_orgid';
                                $extra_party_id = 'extra_party_res_id';
                            }

                            if ($exp->pet_age == 0) {
                                $extra_party_age = '';
                            } else {
                                $extra_party_age = $exp->pet_age;
                            }

                            $extra_party_address = strtoupper($exp->address);

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
                            if ($exp->orgid != '0') {
                                $exp_org = 'Organisation';
                            } else {
                                $exp_org = $party_type;
                            }
                            // ENDS EXTRA PARTY ALTERNATE ADDRESS
                            ?>
                            <tr>
                                <td colspan="4"  bgcolor="#f5f5f5"><font color="#c9302c"><b>S.No:<?php echo htmlentities($j, ENT_QUOTES); ?> </b><b style="text-align: center"><?php echo htmlentities($party_type . ' Details', ENT_QUOTES); ?></b></font></td>
                            </tr>

                            <?php if ($exp->orgid != '0') { ?>
                                <tr>
                                    <td colspan="4"><span id="label_petname"><strong><?php echo ucwords($exp_org); ?> :</strong></span>&nbsp;<font color="#36366c"><?php echo htmlentities(strtoupper($exp->name), ENT_QUOTES); ?></font>
                                    </td>
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
                                    ?>><span id="label_petname"><strong><?php echo ucwords($exp_org); ?> :</strong></span>&nbsp;<font color="#36366c"><?php echo htmlentities(strtoupper($exp->name), ENT_QUOTES); ?></font>
                                        <?php
                                        if ($_SESSION['login']['ref_m_usertype_id'] == USER_ACTION_ADMIN || $_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN) {
                                            ?><p><font color="red">This Organisation is not in your CIS Masters. Please add it your masters and then select the same and save from the dropdown list here.</font></p><?php
                                        }
                                        ?>
                                    </td>
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
                                    <td colspan="3"><span id="label_petname"><strong><?php echo ucwords($exp_org); ?> :</strong></span>&nbsp;<font color="#36366c"><?php echo htmlentities(strtoupper($exp->name), ENT_QUOTES); ?></font></td>
                                    <td><strong>Performa Flag:</strong><font color="#36366c"><?php echo htmlentities($exp->performaresflag, ENT_QUOTES); ?></font></td>                             
                                </tr>
                                <tr>
                                    <td><strong>Gender: </strong> <font color="#36366c">&nbsp;<?php echo htmlentities($ext_gender, ENT_QUOTES); ?></font></td>   
                                    <td colspan="3"><strong>Age :</strong>&nbsp;<font color="#36366c"><?php echo htmlentities($extra_party_age, ENT_QUOTES); ?></font></td>
                                </tr>
                                <tr>
                                    <td ><strong>Relation :</strong>&nbsp;<font color="#36366c"><?php echo htmlentities(strtoupper($exp->extra_party_relation_name), ENT_QUOTES); ?></font></td>
                                    <td colspan="3"><strong>Relative Name :</strong>&nbsp;<font color="#36366c"><?php echo htmlentities(strtoupper($exp->father_name), ENT_QUOTES); ?></font></td>


                                </tr>                                
                            <?php } ?>
                            <tr>                                        
                                <td><strong>Email :</strong>&nbsp;<font color="#36366c"><?php echo htmlentities(preview_email_id($exp->pet_email), ENT_QUOTES); ?></font></td>    
                                <td><strong>Mobile :</strong>&nbsp;<font color="#36366c"><?php echo htmlentities($exp->pet_mobile, ENT_QUOTES); ?></font></td>                                    
                                <td colspan="2"><strong>Address :</strong>&nbsp;<font color="#36366c"><?php echo htmlentities($extra_party_address, ENT_QUOTES); ?></font> </td>
                            </tr>
                            <tr>
                                <td><strong>State:</strong>&nbsp; <font color="#36366c"><?php echo htmlentities($exp->extra_party_state_name, ENT_QUOTES); ?></font></td>
                                <td><strong>District:</strong>&nbsp; <font color="#36366c"><?php echo htmlentities($exp->extra_party_distt_name, ENT_QUOTES); ?></font></td>
                                <td><strong>Town:</strong>&nbsp;<font color="#36366c"><?php echo htmlentities($exp->extra_party_town_name, ENT_QUOTES); ?></font></td>
                                <td><strong>Ward:</strong>&nbsp;<font color="#36366c"><?php echo htmlentities($exp->extra_party_ward_name, ENT_QUOTES); ?></font></td>
                            </tr>
                            <tr>
                                <td><strong>Taluka:</strong>&nbsp;<font color="#36366c"><?php echo htmlentities($exp->extra_party_taluka_name, ENT_QUOTES); ?></font></td>
                                <td><strong>Village:</strong>&nbsp;<font color="#36366c"><?php echo htmlentities($exp->extra_party_village_name, ENT_QUOTES); ?></font></td>
                                <td><strong>Police Station:</strong>&nbsp;<font color="#36366c"><?php echo htmlentities($exp->extra_party_ps_name, ENT_QUOTES); ?></font></td>
                                <td><strong>Pincode:</strong>&nbsp;<font color="#36366c"><?php echo htmlentities($exp->pincode, ENT_QUOTES); ?></font></td>
                            </tr>

                            <?php if ($exp->other_info_flag == 'Y') { ?>
                                <tr>
                                    <td colspan="4" style="text-align: center"  bgcolor="#f5f5f5"><font color="#29579E"><i class="fa fa-plus" style="float: right;"></i> <b>Other Information </b></font></td>
                                </tr>
                                <tr>
                                    <td><strong>Passport No:</strong>&nbsp;<font color="#36366c"><?php echo htmlentities(strtoupper($exp->passportno), ENT_QUOTES); ?></font></td>
                                    <td><strong>PAN No:</strong>&nbsp;<font color="#36366c"><?php echo htmlentities(strtoupper($exp->panno), ENT_QUOTES); ?></font></td>
                                    <td><strong>Fax No:</strong>&nbsp;<font color="#36366c"><?php echo htmlentities($exp->pet_fax, ENT_QUOTES); ?></font></td>
                                    <td><strong>Phone No:</strong>&nbsp;<font color="#36366c"><?php echo htmlentities($exp->pet_phone, ENT_QUOTES); ?></font></td>
                                </tr>
                                <tr>
                                    <td><strong>Country:</strong>&nbsp; <font color="#36366c"><?php echo htmlentities(strtoupper($exp->country), ENT_QUOTES); ?></font></td>
                                    <td><strong>Nationality:</strong>&nbsp; <font color="#36366c"><?php echo htmlentities(strtoupper($exp->pet_nationality), ENT_QUOTES); ?></font></td>
                                    <td colspan="2"><strong>Occupation:</strong>&nbsp;<font color="#36366c"><?php echo htmlentities(strtoupper($exp->pet_occu), ENT_QUOTES); ?></font></td>                                                                            
                                </tr>                                        
                                <tr>
                                    <td colspan="4"><strong>Alternate Address:</strong>&nbsp;<font color="#36366c"><?php echo htmlentities($extra_party_alt_address, ENT_QUOTES); ?></font></td>                                            
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


            <h2 style="text-align: center">Subordinate Court</h2>

            <table id="" class="table dt-responsive nowrap" border="1" cellspacing="0" width="100%"  style="background-color:#ffffff;font-size: 11px; " >
                <?php if ($_SESSION['breadcrumb_enable']['efiling_for_type_id'] == E_FILING_FOR_ESTABLISHMENT && $efiling_civil_master_data[0]->lower_court_name != '') {
                    ?>
                    <tbody>

                        <tr>
                            <td><strong>Subordinate Court Name:</strong>&nbsp;<font color="#36366c"><?php echo htmlentities($efiling_civil_master_data[0]->lower_court_name, ENT_QUOTES); ?></font></td>
                            <td><strong>CNR Number:</strong>&nbsp;<font color="#36366c"><?php echo $efiling_civil_data[0]['lower_cino'] == '' ? '' : htmlentities(cin_preview($efiling_civil_data[0]['lower_cino']), ENT_QUOTES); ?></font></td>
                            <td><strong>Judge Name:</strong>&nbsp;<font color="#36366c"><?php echo htmlentities($efiling_civil_data[0]['lower_judge_name'], ENT_QUOTES); ?></font></td>
                        </tr>
                        <tr>
                            <td><strong>Case Type:</strong>&nbsp;<font color="#36366c"><?php echo htmlentities($efiling_civil_master_data[0]->lower_court_case_type, ENT_QUOTES); ?></font></td>
                            <td><strong><?php echo $efiling_civil_data[0]['filing_case'] == 1 ? 'Filing No.' : 'Case No.'; ?> :</strong>&nbsp;<font color="#36366c"><?php echo htmlentities(ltrim(substr($efiling_civil_data[0]['lower_court'], 4, 7), '0'), ENT_QUOTES); ?></font></td>                                 
                            <td><strong><?php echo $efiling_civil_data[0]['filing_case'] == 1 ? 'Filing Year' : 'Case Year'; ?> :</strong>&nbsp;<font color="#36366c"><?php echo htmlentities(substr($efiling_civil_data[0]['lower_court'], 11, 4), ENT_QUOTES); ?></font></td>
                        </tr>
                        <tr>
                            <td><strong>Date of Decision:</strong>&nbsp;<font color="#36366c"><?php echo htmlentities($efiling_civil_data[0]['lower_court_dec_dt'], ENT_QUOTES) ? date('d/m/Y', strtotime($efiling_civil_data[0]['lower_court_dec_dt'])) : ''; ?></font></td>
                            <td><strong>CC Applied Date:</strong>&nbsp;<font color="#36366c"><?php echo htmlentities($efiling_civil_data[0]['lcc_applied_date'], ENT_QUOTES) ? date('d/m/Y', strtotime($efiling_civil_data[0]['lcc_applied_date'])) : ''; ?></font></td>
                            <td><strong>CC Ready Date:</strong>&nbsp;<font color="#36366c"><?php echo htmlentities($efiling_civil_data[0]['lcc_received_date'], ENT_QUOTES) ? date('d/m/Y', strtotime($efiling_civil_data[0]['lcc_received_date'])) : ''; ?></font></td>
                        </tr>
                    </tbody>
                    <?php
                } else {

                    if ($subordinate_court_data[0]['sub_qj_high'] == '1') {
                        ?>

                        <tbody>
                            <tr>
                                <td colspan="3"><font color="#c9302c" ><b style="text-align: center"><?php echo htmlentities('First Appellete Court Details', ENT_QUOTES); ?></b></font></td>
                            </tr>
                            <tr>
                                <td><strong>State :</strong>&nbsp;<font color="#36366c"><?php echo htmlentities($efiling_civil_master_data[0]->app_court_state_name, ENT_QUOTES); ?></font></td>
                                <td><strong>District :</strong>&nbsp;<font color="#36366c"><?php echo htmlentities($efiling_civil_master_data[0]->app_court_distt_name, ENT_QUOTES); ?></font></td>
                                <td><strong>Subordinate Court Name :</strong>&nbsp;<font color="#36366c"><?php echo htmlentities($efiling_civil_master_data[0]->app_court_sub_court_name, ENT_QUOTES); ?></font></td>
                            </tr>
                            <tr>
                                <td><strong>CNR Number:</strong>&nbsp;<font color="#36366c"><?php echo $subordinate_court_data[0]['lower_cino'] == '' ? '' : htmlentities(cin_preview($subordinate_court_data[0]['lower_cino']), ENT_QUOTES); ?></font></td>
                                <td colspan="2"><strong>Judge Name:</strong>&nbsp;<font color="#36366c"><?php echo htmlentities(strtoupper($subordinate_court_data[0]['lower_judge_name']), ENT_QUOTES); ?></font></td>
                            </tr>
                            <tr>
                                <td><strong>Date of Decision :</strong>&nbsp;<font color="#36366c"><?php echo htmlentities($subordinate_court_data[0]['lower_court_dec_dt'], ENT_QUOTES) ? date('d/m/Y', strtotime($subordinate_court_data[0]['lower_court_dec_dt'])) : ''; ?></font></td>
                                <td><strong>CC Applied Date :</strong>&nbsp;<font color="#36366c"><?php echo htmlentities($subordinate_court_data[0]['lcc_applied_date'], ENT_QUOTES) ? date('d/m/Y', strtotime($subordinate_court_data[0]['lcc_applied_date'])) : ''; ?></font></td>
                                <td><strong>CC Ready Date :</strong>&nbsp;<font color="#36366c"><?php echo htmlentities($subordinate_court_data[0]['lcc_received_date'], ENT_QUOTES) ? date('d/m/Y', strtotime($subordinate_court_data[0]['lcc_received_date'])) : ''; ?></font></td>
                            </tr>
                            <tr>
                                <td colspan="3"><font color="#c9302c" ><b style="text-align: center"><?php echo htmlentities('Trial Court Details', ENT_QUOTES); ?></b></font></td>
                            </tr>
                            <tr>
                                <td><strong>State :</strong>&nbsp;<font color="#36366c"><?php echo htmlentities($efiling_civil_master_data[0]->trial_court_state_name, ENT_QUOTES); ?></font></td>
                                <td><strong>District :</strong>&nbsp;<font color="#36366c"><?php echo htmlentities($efiling_civil_master_data[0]->trial_court_distt_name, ENT_QUOTES); ?></font></td>
                                <td><strong>Subordinate Court Name :</strong>&nbsp;<font color="#36366c"><?php echo htmlentities($efiling_civil_master_data[0]->trial_court_sub_court_name, ENT_QUOTES); ?></font></td>
                            </tr>
                            <tr>
                                <td><strong>CNR Number :</strong>&nbsp;<font color="#36366c"><?php echo $subordinate_court_data[1]['lower_cino'] == '' ? '' : htmlentities(cin_preview($subordinate_court_data[1]['lower_cino']), ENT_QUOTES); ?></font></td>
                                <td colspan="2"><strong>Judge Name :</strong>&nbsp;<font color="#36366c"><?php echo htmlentities(strtoupper($subordinate_court_data[1]['lower_judge_name']), ENT_QUOTES); ?></font></td>
                            </tr>
                            <tr>
                                <td><strong>Date of Decision :</strong>&nbsp;<font color="#36366c"><?php echo htmlentities($subordinate_court_data[1]['lower_court_dec_dt'], ENT_QUOTES) ? date('d/m/Y', strtotime($subordinate_court_data[1]['lower_court_dec_dt'])) : ''; ?></font></td>
                                <td><strong>CC Applied Date :</strong>&nbsp;<font color="#36366c"><?php echo htmlentities($subordinate_court_data[1]['lcc_applied_date'], ENT_QUOTES) ? date('d/m/Y', strtotime($subordinate_court_data[1]['lcc_applied_date'])) : ''; ?></font></td>
                                <td><strong>CC Ready Date :</strong>&nbsp;<font color="#36366c"><?php echo htmlentities($subordinate_court_data[1]['lcc_received_date'], ENT_QUOTES) ? date('d/m/Y', strtotime($subordinate_court_data[1]['lcc_received_date'])) : ''; ?></font></td>
                            </tr>
                        </tbody>
                        <?php
                    } elseif ($subordinate_court_data[0]['sub_qj_high'] == '2') {
                        ?>
                        <tbody>
                            <tr>
                                <td colspan="3"><font color="#c9302c" ><b style="text-align: center"><?php echo htmlentities('First Appellete Court Details', ENT_QUOTES); ?></b></font></td>
                            </tr>
                            <tr>
                                <td><strong>Case / Reference No :</strong>&nbsp;<font color="#36366c"><?php echo htmlentities(strtoupper($subordinate_court_data[0]['qjnumber']), ENT_QUOTES); ?></font></td>
                                <td colspan="2"><strong>Date of Decision :</strong>&nbsp;<font color="#36366c"><?php echo htmlentities($subordinate_court_data[0]['date_of_order'], ENT_QUOTES) ? date('d/m/Y', strtotime($subordinate_court_data[0]['date_of_order'])) : ''; ?></font></td>
                            </tr>
                            <tr>
                                <td colspan="3"><font color="#c9302c" ><b style="text-align: center"><?php echo htmlentities('Trial Court Details', ENT_QUOTES); ?></b></font></td>
                            </tr>
                            <tr>
                                <td><strong>Case / Reference No :</strong>&nbsp;<font color="#36366c"><?php echo htmlentities(strtoupper($subordinate_court_data[1]['qjnumber']), ENT_QUOTES); ?></font></td>
                                <td colspan="2"><strong>Date of Decision :</strong>&nbsp;<font color="#36366c"><?php echo htmlentities($subordinate_court_data[1]['date_of_order'], ENT_QUOTES) ? date('d/m/Y', strtotime($subordinate_court_data[1]['date_of_order'])) : ''; ?></font></td>
                            </tr>

                        </tbody>
                        <?php
                    } elseif ($subordinate_court_data[0]['sub_qj_high'] == '3') {
                        ?>
                        <tbody>
                            <tr>
                                <td colspan="3"><font color="#c9302c"><b style="text-align: center"><?php echo htmlentities('High Court Details', ENT_QUOTES); ?></b></font></td>
                            </tr>
                            <tr>
                                <td colspan="3"><strong>Case Type :</strong>&nbsp;<font color="#36366c"><?php echo htmlentities($efiling_civil_master_data[0]->high_court_case_type, ENT_QUOTES); ?></font></td>
                            </tr>
                            <tr>
                                <td><strong>CNR Number:</strong>&nbsp;<font color="#36366c"><?php echo $subordinate_court_data[0]['lower_cino'] == '' ? '' : htmlentities(cin_preview($subordinate_court_data[0]['lower_cino']), ENT_QUOTES); ?></font></td>
                                <td><strong><?php echo $subordinate_court_data[0]['filing_case'] == 1 ? 'Filing No.' : 'Case No.'; ?> :</strong>&nbsp;<font color="#36366c"><?php echo htmlentities(ltrim(substr($subordinate_court_data[0]['lower_court'], 4, 7), '0'), ENT_QUOTES); ?></font></td>                                 
                                <td><strong><?php echo $subordinate_court_data[0]['filing_case'] == 1 ? 'Filing Year' : 'Case Year'; ?> :</strong>&nbsp;<font color="#36366c"><?php echo htmlentities(substr($subordinate_court_data[0]['lower_court'], 11, 4), ENT_QUOTES); ?></font></td>
                            </tr>
                            <tr>
                                <td><strong>Date of Decision :</strong>&nbsp;<font color="#36366c"><?php echo htmlentities($subordinate_court_data[0]['lower_court_dec_dt'], ENT_QUOTES) ? date('d/m/Y', strtotime($subordinate_court_data[0]['lower_court_dec_dt'])) : ''; ?></font></td>
                                <td><strong>CC Applied Date :</strong>&nbsp;<font color="#36366c"><?php echo htmlentities($subordinate_court_data[0]['lcc_applied_date'], ENT_QUOTES) ? date('d/m/Y', strtotime($subordinate_court_data[0]['lcc_applied_date'])) : ''; ?></font></td>
                                <td><strong>CC Ready Date :</strong>&nbsp;<font color="#36366c"><?php echo htmlentities($subordinate_court_data[0]['lcc_received_date'], ENT_QUOTES) ? date('d/m/Y', strtotime($subordinate_court_data[0]['lcc_received_date'])) : ''; ?></font></td>
                            </tr>
                        </tbody>
                        <?php
                    } else {
                        ?>
                        <tr>
                            <td colspan="4"><strong>No Records added.</strong></td>
                        </tr>
                    <?php } ?>
                </table>  

                <?php
            }
            ?>
            <?php if ($_SESSION['breadcrumb_enable']['efiling_type'] != E_FILING_TYPE_CDE) { ?>
                <h2 style="text-align: center">Index</h2>
                <table id="" class="table dt-responsive nowrap" border="1" cellspacing="0" width="100%"  style="background-color:#ffffff;font-size: 11px; " >
                    <thead>
                        <tr bgcolor="#dff0d8"> 
                        <tr bgcolor="#dff0d8">
                            <th>#</th>
                            <th>Document(s) Title</th>                        
                            <th>Index</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sr = 1;
                        $indx = '';
                        $st_indx = 1;
                        $end_indx = '';

                        foreach ($efiled_docs_list as $doc_list) {
                            $end_indx = $end_indx + $doc_list['page_no'];
                            $indx = $st_indx . ' - ' . $end_indx;
                            $st_indx = $end_indx + 1;
                            ?>
                            <tr>
                                <td><?= $sr++; ?></td>
                                <td>                                                             
                                    <a target="_blank" href="<?php echo base_url('documentIndex/viewIndexItem/' . url_encryption($doc_list['doc_id'])); ?>">
                                        <?php echo_data($doc_list['doc_title']); ?>
                                        <img src="<?= base_url('assets/images/pdf.png') ?>"> <br/>
                                        <?php echo_data($doc_list['doc_hashed_value']); ?></a>                                     
                                </td>
                                <td><?= $indx; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

                <h2 style="text-align: center">Court Fee</h2>
                <table id="" class="table dt-responsive nowrap" border="1" cellspacing="0" width="100%"  style="background-color:#ffffff;font-size: 11px; " >
                    <thead>
                        <tr bgcolor="#dff0d8">
                            <th>#</th>
                            <th>Fee Type </th>                          
                            <th width="20%"><?php echo !empty($mode_label) ?  rtrim($mode_label, '/ ') : ''; ?> No. <br> Dated</th>
                            <th>Amount( <i class="fa fa-rupee"></i> )</th>   
                            <?php if ((bool) $_SESSION['estab_details']['is_charging_printing_cost']) { ?>
                                <th>Printing Cost ( <i class="fa fa-rupee"></i> )</th>
                                <th>Total Amount  ( <i class="fa fa-rupee"></i> )</th>
                                <?php } ?>
                            <th>Receipt <br> Uploaded On</th>                        

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sno = 1;
                        foreach ($payment_details as $resData) {

                            $transaction_date = $resData['txn_complete_date'] ? date('d-m-Y', strtotime($resData['txn_complete_date'])) : '';
                            ?>
                            <tr>
                                <td><?= htmlentities($sno++, ENT_QUOTES); ?></td>
                                <td><?= htmlentities($resData['fee_type_name'], ENT_QUOTES); ?></td>                             
                                <td><?= htmlentities($resData['bank_name'], ENT_QUOTES) . '<br>' . htmlentities($resData['transaction_num'], ENT_QUOTES) . '<br>' . htmlentities($transaction_date, ENT_QUOTES); ?></td>
                                <td><?= htmlentities($resData['user_declared_court_fee'], ENT_QUOTES); ?></td>
                                <?php if ((bool) $_SESSION['estab_details']['is_charging_printing_cost']) { ?>
                                    <td><?= htmlentities($resData['printing_total'], ENT_QUOTES); ?></td>
                                    <td><?= htmlentities($resData['user_declared_total_amt'], ENT_QUOTES); ?></td>
                                <?php } ?>
                                <td> <?= htmlentities($resData['payment_mode_name'], ENT_QUOTES) ?> </a><br> <?= htmlentities(date('d-m-Y h:i:s A', strtotime($resData['receipt_upload_date'])), ENT_QUOTES); ?></td>                            

                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
<?php } ?>

</div>
</div>