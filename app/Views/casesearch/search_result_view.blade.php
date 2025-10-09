<link rel="shortcut icon" href="<?= base_url().'assets/newDesign/images/logo.png' ?>" type="image/png" />
<!-- <link rel="shortcut icon" href="<?= base_url() . 'assets/newAdmin/' ?>images/favicon.gif"> -->
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/bootstrap.min.css" rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/font-awesome.min.css" rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/animate.css" rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/material.css" rel="stylesheet" />
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/style.css" rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/responsive.css" rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/black-theme.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?= base_url() . 'assets/newAdmin/' ?>css/jquery.dataTables.min.css">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/fullcalendar.css" rel="stylesheet">
<link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-datepicker.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/css/jquery-ui.css">
<link href="<?= base_url() . 'assets' ?>/css/select2.min.css" rel="stylesheet">
<?php if(isset($err)){ ?>
    <div class="mainPanel ">
    <div class="panelInner">
        <div class="middleContent">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 sm-12 col-md-12 col-lg-12 middleContent-left">
                        <div class="center-content-inner comn-innercontent">
                            <div class="dash-card dashboard-section">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="row">
                    <div class="col-sm-12 col-xs-12">
                        <div class="form-group">
                            <div class="ryt-dash-breadcrumb">
                                <div class="btns-sec">
                                    <!-- <a href="javascript:void(0)" class="quick-btn" onclick="window.history.back()"><span class="mdi mdi-chevron-double-left"></span>Back</a> -->
                                    <a href="javascript:void(0)" onclick="window.history.back()" class="quick-btn pull-right"><span class="mdi mdi-chevron-double-left"></span>Back</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
            <div class="col-sm-12 col-xs-12">
                        <div class="alert alert-danger text-center" role="alert" id="successAlert" ><?php echo $err; ?></div>
                    </div>
        </div>
        </div>
        </div>
        </div>
        </div>
        </div>
        </div>
        </div>
        </div>
    <?php } ?>
<?php
$attribute = array('class' => 'form-horizontal', 'id' => 'save_searched_case', 'name' => 'save_searched_case', 'autocomplete' => 'off');
// echo form_open('case/search/save_searched_case_result', $attribute);
unset($_SESSION['parties_list']);
$diary_no = @$searched_case_details->diary_no;
$diary_year = @$searched_case_details->diary_year;
$order_date = @$searched_case_details->ord_dt;
$cause_title = strtoupper(@$searched_case_details->cause_title);
$reg_no_display = strtoupper(@$searched_case_details->reg_no_display);
$active_reg_year = @$searched_case_details->active_reg_year;
$c_status = strtoupper(@$searched_case_details->c_status);
$case_nature = strtoupper(@$searched_case_details->case_grp);
$pno = @$searched_case_details->pno;
$rno = @$searched_case_details->rno;
$advocates = @$searched_case_details->advocates;
$_SESSION['parties_list'] = @$searched_case_details->parties;
$listing_date = @$listing_details->next_dt ?? '';
$advocate_allowed = 0;
$mentioned_for_date = '';
$lbl_confirm = '';
$current_date = date('Y-m-d');
if (isset($diary_no) && !empty($diary_no)) {
    echo form_open('case/search/save_searched_case_result', $attribute);
    if ($mentioning_request_details != false) {
        if ($mentioning_request_details[0]['is_for_fixed_date'] == 't')
            $mentioned_for_date = " for " . date('d-m-Y', strtotime($mentioning_request_details[0]['requested_listing_date']));
        else {
            $dates = converDateRangeTodmY($mentioning_request_details[0]['requested_listing_date_range']);
            $mentioned_for_date = " for any date from " . $dates[0] . ' to ' . $dates[1];
        }
    }
    switch (getSessionData('efiling_type')) {
        case 'misc':
            @$lbl_confirm = "Miscellaneous Document(s)";
            break;
        case 'ia':
            $lbl_confirm = "Interlocutary Application";
            break;
        case 'mentioning':
            $lbl_confirm = "Mentioning";
            break;
        case 'citation':
            $lbl_confirm = "Citation";
            break;
        case 'certificate':
            $lbl_confirm = "Certificate";
            break;
        case 'adjournment_letter':
            $lbl_confirm = "Adjournment Letter";
            break;
        default:
            $lbl_confirm = '';
    }
    if (in_array($_SESSION['login']['aor_code'], explode(',', $advocates))) {
        $advocate_allowed = 1;
    }
    $searched_result_details = $diary_no . '$$$' . $diary_year . '$$$' . $order_date . '$$$' . $cause_title . '$$$' . $reg_no_display . '$$$' . $c_status . '$$$' . $pno . '$$$' . $rno;
    ?>
    <hr>
    <input type="hidden" name="searched_details" value="<?php echo_data(url_encryption($searched_result_details)) ?>">
    <?php if(in_array($_SESSION['login']['ref_m_usertype_id'], [USER_CLERK])){ ?>
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="row">
                    <div class="col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Select AOR <span style="color: red">*</span> :</label>
                            <div class="col-md-7 col-sm-12 col-xs-12">
                                <select name="impersonated_aor" id="impersonated_aor" class="form-control input-sm" required>
                                    <option value="" title="Select">Select AOR</option>
                                    <?php
                                    if (!empty($clerk_aor)) {
                                        foreach ($clerk_aor as $dataRes) {
                                            $ref_department_id = !empty($dataRes->ref_department_id) && $dataRes->ref_department_id !=0 ? $dataRes->ref_department_id : 0;
                                            $sel = ($selected_aor[0]->aor_code == (string) $dataRes->aor_code ) ? "selected=selected" : '';
                                            ?>
                                            <option <?php echo $sel; ?> value="<?=$dataRes->aor_code; ?>"><?php echo_data($dataRes->name.' ('.$dataRes->aor_code.')'); ?> </option>;
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br/>
    <?php } ?>
    
    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="col-md-6 col-lg-6">
                    <div class="row">
                        <div class="col-md-4">
                            <label><strong>Diary No :</strong></label>
                        </div>
                        <div class="col-md-8">
                            <?php echo_data($diary_no . ' / ' . $diary_year); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6">
                    <div class="row">
                        <div class="col-md-4">
                            <label><strong>Registration No. :</strong></label>
                        </div>
                        <div class="col-md-8">
                            <?php echo_data($reg_no_display); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="row mt-2 mb-4">
        <div class="col-12">
            <div class="row">
                <div class="col-md-6 col-lg-6">
                    <div class="row">
                        <div class="col-md-4">
                            <label><strong>Cause Title :</strong></label>
                        </div>
                        <div class="col-md-8">
                            <?php echo_data($cause_title); ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6">
                    <div class="row">
                        <div class="col-md-4">
                            <label><strong>Status :</strong></label>
                        </div>
                        <div class="col-md-8">
                            <?php echo_data($c_status == 'D' ? 'DISPOSED' : 'PENDING'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <?php if ($lbl_confirm == 'Mentioning') {
            if ($c_status == 'D') {
                echo  "<center><div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12\"><font style='font-size:16px;color:red;'>The searched case is disposed.Please follow the offline procedure of mentioning the case!</font></div></center>";
            } elseif ($listing_date >= $current_date) {
                echo  "<center><div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12\"><font style='font-size:16px;color:red;'>The searched case is listed on " . date('d-m-Y', strtotime($listing_date)) . ". Hence, you cannot E-mention the matter!</font></div></center>";
            } else if ($mentioning_request_details != false && ($mentioning_request_details[0]['approval_status'] == NULL || $mentioning_request_details[0]['approval_status'] == '' || empty($mentioning_request_details[0]['approval_status']))) {
                echo  "<center><div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12\"><font style='font-size:16px;color:red;'>The searched case is already mentioned $mentioned_for_date but PENDING FOR APPROVAL. Hence, you cannot E-mention the matter!</font></div></center>";
            } else if ($mentioning_request_details != false && $mentioning_request_details[0]['approval_status'] == 'a') {
                echo  "<center><div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12\"><font style='font-size:16px;color:red;'>The searched case is already mentioned $mentioned_for_date and is approved for " . date('d-m-Y', strtotime($mentioning_request_details[0]['approved_for_date'])) . "!</font></div></center>";
            } else if ($advocate_allowed == 1) { ?>
                <center>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <p style="font-size:14px;" class="text-danger"><strong>Is this the case which you want to Mention for
                                Early Hearing ? </strong></p>
                        <label class="radio-inline"><input type="radio" id="confirm_yes" name="confirm_response" required="required" value="yes">YES</label>
                        <label class="radio-inline"><input type="radio" id="confirm_no" name="confirm_response" value="no">NO</label>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><br>
                        <input type="submit" name="submit_confirm_response" value="Submit" class="btn btn-success" id="SubmitMentioning">
                    </div>
                </center>
            <?php } else if ($advocate_allowed == 0) {
                echo  "<center><div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12\"><font style='font-size:16px;color:red;'>You are not advocate in this case. Hence, you are not authorized to mention this case!</font></div></center>";
            }
        } else if ($lbl_confirm == 'Certificate') {
            if ($c_status == 'D') {
                echo  "<center><div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12\"><font style='font-size:16px;color:red;'>The searched case is disposed.You cannot request for Certificates in Disposed Case!</font></div></center>";
            } else if ($case_nature == 'C') {
                echo  "<center><div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12\"><font style='font-size:16px;color:red;'>The searched case is not a Criminal Case.You cannot request for Certificates in the Case!</font></div></center>";
            } else if ($advocate_allowed == 1) { ?>
                <center>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <p style="font-size:14px;" class="text-danger"><strong>Is this the case in which you want to request
                                Surrender and Custody Certificate ? </strong></p>
                        <label class="radio-inline"><input type="radio" id="confirm_yes" name="confirm_response" required="required" value="yes">YES</label>
                        <label class="radio-inline"><input type="radio" id="confirm_no" name="confirm_response" value="no">NO</label>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><br>
                        <input type="submit" name="submit_confirm_response" value="Submit" class="btn btn-success">
                    </div>
                </center>
            <?php } elseif ($advocate_allowed == 0) {
                echo  "<center><div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12\"><font style='font-size:16px;color:red;'>You are not advocate in this case. Hence, you are not authorized to request for Certificates!</font></div></center>";
            }
        } else if ($lbl_confirm == 'Citation') {
            if ($c_status == 'D') {
                echo  "<center><div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12\"><font style='font-size:16px;color:red;'>The searched case is disposed!</font></div></center>";
            } elseif (empty($listing_date) || $listing_date == "" || $listing_date < $current_date) {
                echo  "<center><div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12\"><font style='font-size:16px;color:red;'>The searched case is not listed. Hence, you cannot enter Citations!</font></div></center>";
            }
            /*elseif($listing_date>=$current_date){
                echo  "<center><div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12\"><font style='font-size:16px;color:red;'>The searched case is listed on ".date('d-m-Y',strtotime($listing_date)).". Hence, you cannot E-mention the matter!</font></div></center>";
            }*/
            else if ($advocate_allowed == 1) { ?>
                <center>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <p style="font-size:14px;" class="text-danger"><strong>Is this the case which you want to enter
                                Citations ? </strong></p>
                        <label class="radio-inline"><input type="radio" id="confirm_yes" name="confirm_response" required="required" value="yes">YES</label>
                        <label class="radio-inline"><input type="radio" id="confirm_no" name="confirm_response" value="no">NO</label>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><br>
                        <input type="submit" name="submit_confirm_response" value="Submit" class="btn btn-success">
                    </div>
                </center>
            <?php } elseif ($advocate_allowed == 0) {
                echo  "<center><div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12\"><font style='font-size:16px;color:red;'>You are not advocate in this case. Hence, you are not authorized to enter citations in this case!</font></div></center>";
            }
        } else if ($lbl_confirm == 'Adjournment Letter') {
            if ($c_status == 'D') {
                echo  "<center><div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12\"><font style='font-size:16px;color:red;'>The searched case is disposed!</font></div></center>";
            } elseif (empty($listing_date) || $listing_date == "" || $listing_date < $current_date) {
                echo  "<center><div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12\"><font style='font-size:16px;color:red;'>The searched case is not listed. Hence, you cannot move adjournment letter!</font></div></center>";
            }
            /*elseif($listing_date>=$current_date){
                echo  "<center><div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12\"><font style='font-size:16px;color:red;'>The searched case is listed on ".date('d-m-Y',strtotime($listing_date)).". Hence, you cannot E-mention the matter!</font></div></center>";
            }*/
            else if ($advocate_allowed == 1) { ?>
                <center>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <p style="font-size:14px;" class="text-danger"><strong>Is this the case which you want to move
                                adjournment letter ? </strong></p>
                        <label class="radio-inline"><input type="radio" id="confirm_yes" name="confirm_response" required="required" value="yes">YES</label>
                        <label class="radio-inline"><input type="radio" id="confirm_no" name="confirm_response" value="no">NO</label>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><br>
                        <input type="submit" name="submit_confirm_response" value="Submit" class="btn btn-success">
                    </div>
                </center>
            <?php } elseif ($advocate_allowed == 0) {
                echo  "<center><div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12\"><font style='font-size:16px;color:red;'>You are not advocate in this case. Hence, you are not authorized to move adjournment letter in this case!</font></div></center>";
            }
        } elseif ($lbl_confirm != 'Mentioning' && $lbl_confirm != 'Citation' && $lbl_confirm != 'Certificate' && $lbl_confirm != 'Adjournment Letter') {
            if ($c_status == 'D') {
                echo  "<center><div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12\"><font style='font-size:16px;color:red;'>The searched case is disposed!</font></div></center>";
            } else { ?>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="form-response" id="msg">
                        <?php
                        if (isset($_SESSION['MSG']) && !empty($_SESSION['MSG'])) {
                            echo $_SESSION['MSG'];
                        }
                        unset($_SESSION['MSG']);
                        ?>
                    </div>
                    <?php echo getSessionData('msg'); ?>
                    <label class="radio-inlin"><input type="radio" class="clsRadio" id="radion_existing" name="radio_appearing_for" value="E" checked="checked"> Request on behalf of litigant for whom my
                        appearance as AOR is already recorded. </label><br />
                    <!--                <label class="radio-inlin"><input type="radio" class="clsRadio" id="radio_new" name="radio_appearing_for" value="N"> Want to represent new litigant.</label><br/>-->
                    <label class="radio-inlin"><input type="radio" class="clsRadio" id="radio_new" name="radio_appearing_for" value="N"> Want to represent existing litigant(s) (Un-represented/New appearance)</label><br />

                    <?php if ($lbl_confirm == 'Interlocutary Application') { ?>
                        <label class="radio-inlin"><input type="radio" class="clsRadio" id="radio_intervenor" name="radio_appearing_for" value="I"> Intervenor/Other.</label>
                        <hr />
                    <?php } ?>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12" id="divAppearingFor" style="display:none;">
                    <div class="form-group">
                        <label class="text-left"><strong>Appearing For :</strong></label>
                        <!--<input type="text" class="col-md-8" id="txtIntervenorName" name="txtIntervenorName">-->
                        <!-- start add more  txtIntervenorName-->
                        <div id="multi-field-wrapper">
                            <div id="multi-fields">
                                <div id="multi-field">
                                    <div class="row">

                                        <div class="col-md-10">
                                            <input type="text" class="form-control cus-form-ctrl" id="txtIntervenorName" name="txtIntervenorName[]">
                                        </div>
                                        <div class="col-md-2">
                                            <span id="add-field" style='font-size:20px;cursor: pointer;margin-left:3px;'><i class="fa fa-plus-circle" aria-hidden="true"></i>
                                                <span class="glyphicon glyphicon-plus-sign" aria-hidden="true" required></span></span>
                                        </div>
                                    </div>
                                </div>
                                <br>
                            </div>
                        </div>
                        <!-- end txtIntervenorName -->
                    </div>
                </div>
                <center>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <p style="font-size:14px;" class="text-danger"><strong>Is this the case in which you want to file <?php echo $lbl_confirm; ?>? </strong></p>
                        <label class="radio-inline"><input type="radio" id="confirm_yes" name="confirm_response" required="required" value="yes">YES</label>
                        <label class="radio-inline"><input type="radio" id="confirm_no" name="confirm_response" value="no">NO</label>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><br>
                        <input type="submit" name="submit_confirm_response" value="Submit" class="btn btn-success">
                    </div>
                </center>
                <?php
            }
        }
        ?>
    </div>
    <hr>
<?php echo form_close();
} else {
    if(!isset($err)){
        echo '<p style="font-size:14px;" class="text-danger text-center"><strong>No Records Found !</strong></p>';
    }
}
?>
<script src="<?= base_url() . 'assets' ?>/js/jquery.min.js"></script>
<?php if ($lbl_confirm == 'Mentioning') { ?>
    <script type="text/javascript">
        document.getElementById("confirm_yes").checked = true;
        //document.getElementById('save_searched_case').submit();
    </script>
<?php } ?>
<script type="text/javascript">
    $(function() {
        $('.clsRadio').click(function() {
            $('input:radio[value=' + ($(this).val()) + ']').prop("checked", true);
            // alert($(this).val());
            if ($(this).val() == 'I') {
                $("#divAppearingFor").show();
                $("#txtIntervenorName").show().prop('required', true);
            } else {
                $("#divAppearingFor").hide();
                $("#txtIntervenorName").hide().prop('required', false);
            }
        });
    });
    $('#multi-field-wrapper').each(function() {
        var $wrapper = $('#multi-fields', this);
        var x = 1;
        $("#add-field", $(this)).click(function(e) {
            x++;
            $($wrapper).append(
                '<div id="multi-field"><label class="text-right"><strong></strong></label><input type="text" class="orm-control cus-form-ctrl col-md-10" id="txtIntervenorName" name="txtIntervenorName[]" required style="width: 83% !important;margin-right:2% !important;"><span class="col-md-2" id="remove_field" style="font-size:20px;cursor: pointer;margin-left:3px;color:lightcoral" ><i class="fa fa-minus-circle" aria-hidden="true"></i></span>  </div>'
            );
        });
        $($wrapper).on("click", "#remove_field", function(e) { //user click on remove text
            e.preventDefault();
            $(this).parent('div').remove();
            x--;
        })
    });
</script>
