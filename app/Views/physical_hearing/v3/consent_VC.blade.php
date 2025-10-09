<?php
use App\Models\PhysicalHearing\ConsentVCModel;
use App\Models\PhysicalHearing\HearingModel;
$segment = service('uri');
$Consent_VC_model = new ConsentVCModel();
$hearing_model = new HearingModel();
$court_from_uri = $segment->getSegment(1);
$segments = $segment->getSegments();
$totalSegments = count($segments);
?>
<style>
    .switch-field {
        display: flex;
        margin-bottom: 36px;
        overflow: hidden;
    }
    .switch-field input {
        position: absolute !important;
        clip: rect(0, 0, 0, 0);
        height: 1px;
        width: 1px;
        border: 0;
        overflow: hidden;
    }
    .switch-field label {
        background-color: #e4e4e4;
        color: rgba(0, 0, 0, 0.6);
        font-size: 14px;
        line-height: 1;
        text-align: center;
        padding: 8px 16px;
        margin-right: -1px;
        border: 1px solid rgba(0, 0, 0, 0.2);
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.3), 0 1px rgba(255, 255, 255, 0.1);
        transition: all 0.1s ease-in-out;
    }
    .switch-field label:hover {
        cursor: pointer;
    }
    .switch-field input:checked + label {
        background-color: #a5dc86;
        box-shadow: none;
    }
    .switch-field label:first-of-type {
        border-radius: 4px 0 0 4px;
    }
    .switch-field label:last-of-type {
        border-radius: 0 4px 4px 0;
    }
    .form {
        max-width: 600px;
        font-family: "Lucida Grande", Tahoma, Verdana, sans-serif;
        font-weight: normal;
        line-height: 1.625;
        margin: 8px auto;
        padding: 16px;
    }
    h2 {
        font-size: 18px;
        margin-bottom: 8px;
    }
</style>
@extends('layout.advocateApp')
@section('content')
<div id="loader-wrapper" style="display: none;">
    <div id="loader"></div>
</div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="dashboard-section dashboard-tiles-area"></div>
                <div class="dashboard-section">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="dash-card">
                                <div class="row">
                                    <?= session()->getFlashdata('msg'); ?>
                                </div>
                                <div class="title-sec">
                                    <h5 class="unerline-title"> Physical Hearing </h5>
                                    <?php if($totalSegments > 1) { ?>
                                        <a href="<?php echo base_url('physical_hearing'); ?>" class="quick-btn pull-right"><span class="mdi mdi-chevron-double-left"></span>Back</a>
                                    <?php } ?>
                                </div>
                                <div class="row g-3 align-items-center">
                                    <div class="col-auto">
                                        <label for="inputPassword6" class="col-form-label"> Select Court: </label>
                                    </div>
                                    <div class="col-auto">
                                        <select class="form-control cus-form-ctrl" id="selected_court" name="selected_court">
                                            <option value="">Select Court</option>
                                            <?php
                                            if($totalSegments >= 3) {
                                                $selected_court = $segment->getSegment(3);
                                            } else {
                                                $selected_court = '';
                                            }
                                            if(!empty($freezed_court)) {
                                                foreach($freezed_court as $court) {
                                                    echo '<option value="' . $court['court_no'] . '" '.($selected_court == $court['court_no'] ? 'selected' : '').'> Court No. '. $court['court_no'] .'</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-auto">
                                        <span id="passwordHelpInline" class="form-text">
                                            <button type="button" onclick="goto_selected_court()" class="btn btn-primary">Go!</button>
                                        </span>
                                    </div>
                                </div>
                                <?php
                                $attributes = array("method"=>"post","class" => "form-horizontal", "id" => "consentform", "name" => "consentform", "autocomplete"=>"off");
                                echo form_open(base_url("consent_VC/save"), $attributes);
                                ?>                                    
                                <div class="row col-sm-12">
                                    <div class="direct-chat-info clearfix">
                                        <span class="direct-chat-name pull-left"></span>
                                        <span class="direct-chat-timestamp pull-right"><?=date('d M, Y')?></span>
                                    </div>
                                </div>
                                <div class="row col-sm-12 mt-3">
                                    <img style="width: 5%;" class="direct-chat-img" src="{{ base_url('assets/images/physical.png') }}" alt="Message User Image">
                                    <div class="col-sm-12" style="background: #110458; border-color: #39ac73; color: #fff; width: 95%; padding: 20px; border-radius: 20px; font-size: 20px;">
                                        <b><?php echo (isset($display_message1)) ? $display_message1 : ''; ?></b>
                                    </div>
                                </div>
                                <div class="row col-sm-12 mb-3" style="text-align: center;">
                                    <div class="col-sm-12 mt-3">
                                        <span class="col-xs-12" style="color: red; font-weight: bold;">
                                            To get the VC Mode link, you can give your consent upto 8 AM on the day of listing</span>
                                    </div>
                                </div>
                                <?php
                                
                                if(isset($cases) && sizeof($cases)>0) {
                                    echo '<div class="table-responsive box-body no-padding">
                                        <table id="datatable-responsive" class="table table-striped custom-table">
                                            <thead>
                                                <tr>
                                                    <th>#</th>       
                                                    <th>List Date</th>                                  
                                                    <th>Registration Number</th>
                                                    <th>Court # Item no</th>
                                                    <th>Main case Details</th>        
                                                    <th>No. of Cases</th>                               
                                                    <th>Mode of Hearing </th>
                                                </tr>
                                            </thead>
                                            <tbody>';                            
                                    $sno=0;
                                    foreach ($cases as $case) {
                                        $is_valid_consent_date_and_time = checkEntryWithinAllowDateAndTime($case['next_dt']);
                                        if(!empty($is_valid_consent_date_and_time)) {
                                            $hearing_mode_court_direction=$case['consent_diaries'];
                                            $arrContextOptions=array(
                                                "ssl"=>array(
                                                    "verify_peer"=>false,
                                                    "verify_peer_name"=>false,
                                                ),
                                            );
                                            // $aud_nomination_status=(intval(file_get_contents(ICMIS_SERVICE_URL.'/consent/case_listed_in_daily_status/'.$case['diary_no'], false, stream_context_create($arrContextOptions))));
                                            $aud_nomination_status=case_listed_in_daily_list_status($case['diary_no']);
                                            $case_aor_details = $hearing_model->aorCount($case['diary_no']);
                                            $aor_count=$case_aor_details[0]['advocate_count'];
                                            $consent_result = $Consent_VC_model->get_advocate_last_updated_consent($case['diary_no'],$case['next_dt'],$case['roster_id'],getSessionData('login.adv_sci_bar_id'),$case['court_no']);
                                            $pChecked='';
                                            $label='';
                                            $vChecked='';
                                            if(!empty($consent_result)){
                                                $consent=$consent_result[0]['consent'];
                                                if($consent =='P') {
                                                    $pChecked='checked';
                                                    $vChecked='';
                                                    $label='Physical';
                                                } elseif($consent =='V') {
                                                    $pChecked='';
                                                    $vChecked='checked';
                                                    $label='Virtual';
                                                } else {
                                                    $pChecked='checked';
                                                    $vChecked='';
                                                    $label='Physical';
                                                }
                                            } else {
                                                $pChecked='checked';
                                                $vChecked='';
                                                $label='Physical';
                                            }
                                            if($aud_nomination_status==1 ) {
                                                if($aor_count>CASES_ALLOWD_MAX_LIMIT_OF_AOR) {
                                                    $label='Physical hearing is not allowed in this case';
                                                    $buttonHTML="<button type='button' class='btn btn-danger'><strong>".$label."</strong></button>";
                                                } else {
                                                    if($hearing_mode_court_direction=='P') {
                                                        $label='Physical';
                                                        $buttonHTML="<button type='button' class='btn btn-danger'><strong>".$label."</strong></button><br><span style='color:red'>[Hon'ble court direction]</span>";
                                                    } elseif($hearing_mode_court_direction=='V') {
                                                        $label='Virtual';
                                                        $buttonHTML='<button type="button" class="btn btn-warning"><strong>'.$label.'</strong></button><br><span style="color:red">[Honble court direction]</span>';
                                                    } else {
                                                        $buttonHTML='<span class="switch-field">
                                                            <input type="radio" id="radio-one'.$case['diary_no'].'" name="hearingModeConsent['.$case['diary_no'].']" value="P" '.$pChecked.' />
                                                            <label for="radio-one'.$case['diary_no'].'">Physical</label>
                                                            <input type="radio" id="radio-two'.$case['diary_no'].'" name="hearingModeConsent['.$case['diary_no'].']" value="V" '.$vChecked.' />
                                                            <label for="radio-two'.$case['diary_no'].'">Virtual</label>
                                                        </span>';
                                                    }
                                                }
                                            } else {
                                                $buttonHTML='<button type="button" class="btn btn-warning"><strong>'.$label.'</strong></button><br><span style="color:red">[Case listed for Today]</span>';
                                            }
                                            $sno++;
                                            if($hearing_mode_court_direction!='P' && $hearing_mode_court_direction!='V') {
                                                echo '<tr>
                                                    <td data-key="#">'.$sno.'</td>
                                                    <td data-key="List Date">'.date('d-m-Y', strtotime($case['next_dt'])).'</td>
                                                    <td data-key="Registration Number">'.str_replace(' (M)','<span style="color:darkgreen"> (M)</span>',str_replace(' (C)','<span style="color:red"> (C)</span>',str_replace(",","<br/>",$case['case_no']))).'</td>
                                                    <td data-key="Court # Item no">'.$case['court_no_display'].'#'.$case['item_no'].'</td>
                                                    <td data-key="Main case Details">'.$case['main_case_reg_no'].' @ '.$case['diary_no'].'<br>'.$case['cause_title'].'</td>
                                                    <td data-key="No. of Cases">'.$case['case_count'].'</td>
                                                    <td data-key="Mode of Hearing ">
                                                        <input type="hidden" name="consent_for_diary_nos['.$case['diary_no'].']" id="consent_for_diary_nos" value="'.$case['consent_diaries'].'">
                                                        <input type="hidden" name="case_count['.$case['diary_no'].']" id="case_count" value="'.$case['case_count'].'">
                                                        <input type="hidden" name="court_no" id="court_no" value="'.$case['court_no'].'">
                                                        <input type="hidden" name="next_date['.$case['diary_no'].']" id="next_date" value="'.$case['next_dt'].'">
                                                        <input type="hidden" name="roster_id['.$case['diary_no'].']" id="roster_id" value="'.$case['roster_id'].'">
                                                        <input type="hidden" name="item_no['.$case['diary_no'].']" id="item_no" value="'.$case['item_no'].'">'.$buttonHTML.'
                                                    </td>
                                                </tr>';
                                            }
                                        }
                                    }
                                    echo '</tbody></table>';
                                    if($aud_nomination_status==1) {
                                        echo '<center><button type="submit" class="btn quick-btn mt-2"><strong>UPDATE CHOICE</strong></button></center>';
                                    }
                                    echo ' </div>';
                                }
                                echo form_close();
                                if($court_from_uri=='physical_hearing') {
                                    ?>
                                    <br/>
                                    <div class="box">
                                        <div class="box-header">
                                            <h3 class="box-title">Summary of Chosen mode of hearing</h3>
                                        </div>
                                        <div class="box-body no-padding">
                                            <table id="datatable-responsive" class="table table-striped table-border custom-table" cellspacing="0" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th>List Date</th>
                                                        <th>Court No#</th>
                                                        <th>Total Cases</th>
                                                        <th>VC Mode of Hearing</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if(!empty($advocate_cases_summary)) {
                                                        foreach ($advocate_cases_summary as $court) {
                                                            $consent_result = $Consent_VC_model->getAdvocateVCConsentSummary(getSessionData('login.adv_sci_bar_id'),$listing_date[0], $court['courtno']);
                                                            ?>
                                                            <tr>
                                                                <td data-key="List Date"><?=date('d-m-Y', strtotime($court['next_dt']))?></td>
                                                                <td data-key="Court No#"><?=$court['court_no_display']?></td>
                                                                <td data-key="Total Cases"><?=$court['total_cases']?></td>
                                                                <td data-key="VC Mode of Hearing"><?=empty($consent_result[0]['vc_count'])?0:$consent_result[0]['vc_count'] ?> </td>
                                                            </tr>
                                                            <?php
                                                        }
                                                    } else{
                                                        echo "<div class='row mt-3' style='margin: 0% 45%;'> No case found! </div>";    
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<script src="<?= base_url() . 'assets/newAdmin/' ?>js/jquery351.min.js"></script>
<script src="<?=base_url()?>assets/physical_hearing/plugins/jQuery/jQuery.min.js"></script>
<script src="<?= base_url() ?>assets/physical_hearing/js/angular.min.js"></script>
<script src="<?= base_url() ?>assets/physical_hearing/js/angular-cookies.js"></script>
<script src="<?= base_url() ?>assets/physical_hearing/js/angular-route.js"></script>
<script>
    $(".alert").delay(2000).slideUp(200, function() {
        $(this).alert('close');
    });
    function goto_selected_court() {
        showLoader();
        sel_ct = $('#selected_court').val();
        if(!sel_ct) {
            alert('Please Select the Court List!');
            window.location.href = "<?= base_url('physical_hearing') ?>";
        } else {
            window.location.href = "<?= base_url('Consent_VC/index/') ?>"+sel_ct;
        }
    }
    function showLoader() {
        setTimeout(function() {
            $('#loader-wrapper').show();
        }, 1000);
    }
</script>