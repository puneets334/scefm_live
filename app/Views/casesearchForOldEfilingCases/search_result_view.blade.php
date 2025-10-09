<?php
use App\Libraries\webservices\Efiling_webservices;
$this->efiling_webservices = new Efiling_webservices();
$session = session();
if(empty($searched_case_details)) {
    ?>
    @if(isset($validation) && !empty($validation->getError('search_filing_type')))
    <div class="text-danger">
        <b>{{ $validation->getError('search_filing_type')}}</b>
    </div>
    @endif
    @if(isset($validation) && !empty($validation->getError('diaryno')))
    <div class="text-danger">
        <b>{{ $validation->getError('diaryno')}}</b>
    </div>
    @endif
    @if(isset($validation) && !empty($validation->getError('diary_year')))
    <div class="text-danger">
        <b>{{ $validation->getError('diary_year')}}</b>
    </div>
    @endif
    @if(isset($validation) && !empty($validation->getError('sc_case_type')))
    <div class="text-danger">
        <b>{{ $validation->getError('sc_case_type')}}</b>
    </div>
    @endif
    @if(isset($validation) && !empty($validation->getError('case_number')))
    <div class="text-danger">
        <b>{{ $validation->getError('case_number')}}</b>
    </div>
    @endif
    @if(isset($validation) && !empty($validation->getError('case_year')))
    <div class="text-danger">
        <b>{{ $validation->getError('case_year')}}</b>
    </div>
    @endif
    <?php
} else{
    $attribute = array('class' => 'form-horizontal', 'id' => 'save_searched_case', 'name' => 'save_searched_case', 'autocomplete' => 'off');
    // echo form_open('case/search/save_searched_case_result', $attribute);
    // unset($_SESSION['parties_list']);
    $diary_no = $searched_case_details->diary_no;
    $diary_year = $searched_case_details->diary_year;
    $order_date = $searched_case_details->ord_dt;
    $cause_title = strtoupper($searched_case_details->cause_title);
    $reg_no_display = strtoupper($searched_case_details->reg_no_display);
    $active_reg_year = $searched_case_details->active_reg_year;
    $c_status = strtoupper($searched_case_details->c_status);
    $case_nature = strtoupper($searched_case_details->case_grp);
    $pno = $searched_case_details->pno;
    $rno = $searched_case_details->rno;
    $advocates = $searched_case_details->advocates;
    $_SESSION['parties_list'] = $searched_case_details->parties;
    $listing_date = $listing_details->next_dt;
    $advocate_allowed = 0;
    $mentioned_for_date = '';
    $current_date = date('Y-m-d');
    if (isset($diary_no) && !empty($diary_no)) { ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="dashboard-section dashboard-tiles-area"></div>
                    <div class="dashboard-section">
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                    <?php
                                    echo form_open('case/search/save_searched_case_result', $attribute);
                                    $mentioning_request_details = array();
                                    if ($mentioning_request_details != false) {
                                        if ($mentioning_request_details[0]['is_for_fixed_date'] == 't') {
                                            $mentioned_for_date = " for " . date('d-m-Y', strtotime($mentioning_request_details[0]['requested_listing_date']));
                                        } else {
                                            $dates = converDateRangeTodmY($mentioning_request_details[0]['requested_listing_date_range']);
                                            $mentioned_for_date = " for any date from " . $dates[0] . ' to ' . $dates[1];
                                        }
                                    }
                                    switch ($_SESSION['efiling_type']) {
                                        case 'misc':
                                            $lbl_confirm = "Miscellaneous Document(s)";
                                            break;
                                        case 'ia':
                                            $lbl_confirm = "Interlocutary Application";getSessionData('efiling_details')['gras_payment_status'];
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
                                        case 'refile_old_efiling_cases':
                                            $lbl_confirm = "Refile";
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
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <label class="col-md-4 text-right"><strong>Diary No :</strong></label>
                                                <div class="col-md-8">
                                                    <?php echo_data($diary_no . ' / ' . $diary_year); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <label class="col-md-4 text-right"><strong>Registration No. :</strong></label>
                                                <div class="col-md-8">
                                                    <?php echo_data($reg_no_display); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <label class="col-md-4 text-right"><strong>Cause Title :</strong></label>
                                                <div class="col-md-8">
                                                    <?php echo_data($cause_title); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                            <div class="form-group">
                                                <label class="col-md-4 text-right"><strong>Status :</strong></label>
                                                <div class="col-md-8">
                                                    <?php echo_data($c_status == 'D' ? 'DISPOSED' : 'PENDING'); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <?php
                                            $diary_number = $searched_case_details->diary_no . $searched_case_details->diary_year;
                                            // $ci = &get_instance();
                                            // $ci->load->library('webservices/efiling_webservices');
                                            $case_uncured_defects_details = $this->efiling_webservices->getCaseDefectDetails($diary_no, $diary_year);
                                            if (isset($case_uncured_defects_details) && !empty($case_uncured_defects_details)) {
                                                $msg = '<div class="alert table-responsive-sm""><div style="text-align:center;font-weight: bolder;text-decoration: underline">All DEFECTS </div>';
                                                $msg .= '<table id="datatable-defects" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%" >'
                                                    . '<thead class="success"><th>#</th><th>Defect Description</th><th>Prepare Dt.</th><th>Remove Dt.</th></thead>'
                                                    . '<tbody style="border-color: #ebccd1;background-color: #f2dede;color: #a94442;">';
                                                    // pr($case_uncured_defects_details->defects[0]->save_dt);
                                                foreach ($case_uncured_defects_details as $res) {
                                                    $i = 1;
                                                    foreach ($res as $re) {
                                                        $prep_dt = (isset($re->save_dt) && !empty($re->save_dt)) ? date('d-M-Y H:i:s', strtotime($re->save_dt)) : null;
                                                        $remove_dt = (isset($re->rm_dt) && !empty($re->rm_dt) && ($re->rm_dt != '0000-00-00 00:00:00')) ? date('d-M-Y H:i:s', strtotime($re->rm_dt)) : $re->rm_dt;
                                                        $defect_description = (isset($re->Description)) ? $re->Description : NULL;
                                                        $msg .= '<tr>';
                                                        $msg .= '<td data-key="#">' . $i++ . '</td>';
                                                        $msg .= '<td data-key="Defect Description">' . $defect_description . '</td>';
                                                        $msg .= '<td data-key="Prepare Dt.">' . $prep_dt . '</td>';
                                                        $msg .= '<td data-key="Remove Dt.">' . $remove_dt . '</td>';
                                                        $msg .= '</tr>';
                                                    }
                                                    $i+1;
                                                }

                                                $msg .= '</tbody></table>';
                                                $msg .= '</div></div>';
                                                echo $msg;
                                            }
                                            ?>
                                        </div>
                                        <?php
                                        if ($lbl_confirm == 'Mentioning_old_not_in_use') {
                                            if ($c_status == 'D') {
                                                echo  "<center><div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12\"><font style='font-size:16px;color:red;'>The searched case is disposed.Please follow the offline procedure of mentioning the case!</font></div></center>";
                                            } elseif ($listing_date >= $current_date) {
                                                echo  "<center><div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12\"><font style='font-size:16px;color:red;'>The searched case is listed on " . date('d-m-Y', strtotime($listing_date)) . ". Hence, you cannot E-mention the matter!</font></div></center>";
                                            } else if ($mentioning_request_details != false && ($mentioning_request_details[0]['approval_status'] == NULL || $mentioning_request_details[0]['approval_status'] == '' || empty($mentioning_request_details[0]['approval_status']))) {
                                                echo  "<center><div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12\"><font style='font-size:16px;color:red;'>The searched case is already mentioned $mentioned_for_date but PENDING FOR APPROVAL. Hence, you cannot E-mention the matter!</font></div></center>";
                                            } else if ($mentioning_request_details != false && $mentioning_request_details[0]['approval_status'] == 'a') {
                                                echo  "<center><div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12\"><font style='font-size:16px;color:red;'>The searched case is already mentioned $mentioned_for_date and is approved for " . date('d-m-Y', strtotime($mentioning_request_details[0]['approved_for_date'])) . "!</font></div></center>";
                                            } else if ($advocate_allowed == 1) {
                                        ?>
                                                <center>
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <p style="font-size:14px;" class="text-danger"><strong>Is this the case which you want to Mention for Early Hearing ? </strong></p>
                                                        <label class="radio-inline"><input type="radio" id="confirm_yes" name="confirm_response" required="required" value="yes">YES</label>
                                                        <label class="radio-inline"><input type="radio" id="confirm_no" name="confirm_response" value="no">NO</label>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><br>
                                                        <input type="submit" name="submit_confirm_response" value="Submit" class="btn btn-success" id="SubmitMentioning">
                                                    </div>
                                                </center>
                                            <?php
                                            } else if ($advocate_allowed == 0) {
                                                echo  "<center><div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12\"><font style='font-size:16px;color:red;'>You are not advocate in this case. Hence, you are not authorized to mention this case!</font></div></center>";
                                            }
                                        } else if ($lbl_confirm == 'Certificate') {
                                            if ($c_status == 'D') {
                                                echo  "<center><div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12\"><font style='font-size:16px;color:red;'>The searched case is disposed.You cannot request for Certificates in Disposed Case!</font></div></center>";
                                            } else if ($case_nature == 'C') {
                                                echo  "<center><div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12\"><font style='font-size:16px;color:red;'>The searched case is not a Criminal Case.You cannot request for Certificates in the Case!</font></div></center>";
                                            } else if ($advocate_allowed == 1) {
                                            ?>
                                                <center>
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <p style="font-size:14px;" class="text-danger"><strong>Is this the case in which you want to request Surrender and Custody Certificate ? </strong></p>
                                                        <label class="radio-inline"><input type="radio" id="confirm_yes" name="confirm_response" required="required" value="yes">YES</label>
                                                        <label class="radio-inline"><input type="radio" id="confirm_no" name="confirm_response" value="no">NO</label>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><br>
                                                        <input type="submit" name="submit_confirm_response" value="Submit" class="btn btn-success">
                                                    </div>
                                                </center>
                                            <?php
                                            } elseif ($advocate_allowed == 0) {
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
                                                    }*/ else if ($advocate_allowed == 1) {
                                            ?>
                                                <center>
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <p style="font-size:14px;" class="text-danger"><strong>Is this the case which you want to enter Citations ? </strong></p>
                                                        <label class="radio-inline"><input type="radio" id="confirm_yes" name="confirm_response" required="required" value="yes">YES</label>
                                                        <label class="radio-inline"><input type="radio" id="confirm_no" name="confirm_response" value="no">NO</label>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><br>
                                                        <input type="submit" name="submit_confirm_response" value="Submit" class="btn btn-success">
                                                    </div>
                                                </center>
                                            <?php
                                            } elseif ($advocate_allowed == 0) {
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
                                                    }*/ else if ($advocate_allowed == 1) {
                                            ?>
                                                <center>
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <p style="font-size:14px;" class="text-danger"><strong>Is this the case which you want to move adjournment letter ? </strong></p>
                                                        <label class="radio-inline"><input type="radio" id="confirm_yes" name="confirm_response" required="required" value="yes">YES</label>
                                                        <label class="radio-inline"><input type="radio" id="confirm_no" name="confirm_response" value="no">NO</label>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><br>
                                                        <input type="submit" name="submit_confirm_response" value="Submit" class="btn btn-success">
                                                    </div>
                                                </center>
                                            <?php
                                            } elseif ($advocate_allowed == 0) {
                                                echo  "<center><div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12\"><font style='font-size:16px;color:red;'>You are not advocate in this case. Hence, you are not authorized to move adjournment letter in this case!</font></div></center>";
                                            }
                                        }
                                        //elseif($lbl_confirm!='Mentioning' && $lbl_confirm!='Citation' && $lbl_confirm!='Certificate' && $lbl_confirm!='Adjournment Letter') {
                                        elseif ($lbl_confirm != 'Citation' && $lbl_confirm != 'Certificate' && $lbl_confirm != 'Adjournment Letter') {
                                            if ($c_status == 'D') {
                                                echo  "<center><div class=\"col-lg-12 col-md-12 col-sm-12 col-xs-12\"><font style='font-size:16px;color:red;'>The searched case is disposed!</font></div></center>";
                                            } else { ?>
                                                <div style="margin-left: 15%" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="form-response" id="msg"><?php
                                                                                        if (isset($_SESSION['MSG']) && !empty($_SESSION['MSG'])) {
                                                                                            echo $_SESSION['MSG'];
                                                                                        }
                                                                                        unset($_SESSION['MSG']);
                                                                                        ?>
                                                    </div>
                                                    <?php echo getSessionData('msg'); ?>
                                                </div>
                                                <center>
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <p style="font-size:14px;" class="text-danger"><strong>Is this the case of old e-filing which you want to <?php echo $lbl_confirm; ?>? </strong></p>
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
                                    <?php echo form_close(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
    } else {
        echo '<p style="font-size:14px;" class="text-danger text-center"><strong>No Records Found !</strong></p>';
    }
    ?>
    <?php if ($lbl_confirm == 'Mentioning' || $lbl_confirm == 'Refile') { ?>
        <script type="text/javascript">
            document.getElementById("confirm_yes").checked = true;
            // document.getElementById('save_searched_case').submit();
        </script>
    <?php } ?>
    <script type="text/javascript">
        $(function() {
            $('.clsRadio').click(function() {
                $('input:radio[value=' + ($(this).val()) + ']').prop("checked", true);
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
                $($wrapper).append('<div id="multi-field"><label class="col-md-4 text-right"><strong></strong></label><input type="text" class="col-md-5" id="txtIntervenorName" name="txtIntervenorName[]" required><span id="remove_field" style="font-size:20px;cursor: pointer;margin-left:3px;color:lightcoral" ><span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span></span>  </div>');
            });
            $($wrapper).on("click", "#remove_field", function(e) { //user click on remove text
                e.preventDefault();
                $(this).parent('div').remove();
                x--;
            })
        });
    </script>
<?php } ?>