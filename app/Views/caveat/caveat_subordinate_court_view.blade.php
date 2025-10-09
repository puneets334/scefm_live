<link rel="shortcut icon" href="<?= base_url().'assets/newDesign/images/logo.png' ?>" type="image/png" />
<!-- <link rel="shortcut icon" href="<?= base_url() . 'assets/newAdmin/' ?>images/favicon.gif"> -->
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/bootstrap.min.css" rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/font-awesome.min.css" rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/animate.css" rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/material.css" rel="stylesheet" />
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/style.css" rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/fullcalendar.css" rel="stylesheet">
<!-- <link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-datepicker.css"> -->
<link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-datepicker.min.css"> 
<!-- <link rel="stylesheet" href="<?= base_url() ?>assets/css/jquery-ui.css"> -->
<link href="<?= base_url() . 'assets' ?>/css/select2.min.css" rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/black-theme.css" rel="stylesheet">
    <link href="<?= base_url() . 'assets/newAdmin/' ?>css/responsive.css" rel="stylesheet">
<style>
.overlay{
    display: none;
    position: fixed;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    z-index: 999;
    background: rgba(255,255,255,0.8) url("loader.gif") center no-repeat;
}
/* Turn off scrollbar when body element has the loading class */
body.loading {
    overflow: hidden;   
}
/* Make spinner image visible when body element has the loading class */
body.loading .overlay{
    display: block;
}
#loader-wrapper {
            /* display: flex; */
            justify-content: center;
            align-items: center;
            z-index: 1000; /* Ensure it's on top of other content */
        }
/* .loader {
      border: 8px solid #f3f3f3;  
      border-top: 8px solid #3498db;  
      border-radius: 50%;
      width: 50px;
      height: 50px;
      animation: spin 2s linear infinite;
      margin: 0 auto;  
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    } */
</style>
<div id="loader-wrapper" style="display: none;">
    <div id="loader"></div>
</div>
<div class="center-content-inner comn-innercontent">
    <div class="tab-content">
        <div class="tab-pane Active" id="messages" role="tabpanel" aria-labelledby="messages-tab">                                 
            <div class="tab-form-inner">
                <?php
                $attribute = array('class' => 'form-horizontal', 'name' => 'subordinate_court_details', 'id' => 'subordinate_court_details', 'autocomplete' => 'off');
                echo form_open('#', $attribute);
                ?>

                <div class="row">
                    <h6 class="text-center fw-bold">Earlier Courts</h6>
                </div>
                    <div class="row" id="errDiv" style="display: none;">
                        <div class="alert alert-danger" role="alert" id="alertMsg"></div>
                    </div>
                <?= ASTERISK_RED_MANDATORY ?>
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 text-center">
                            <div class="mb-3">
                                <label for="" class="form-label me-2">Select Court <span style="color: red">*</span> : </label>
                                <?php
                                    $dcchecked=$hcchecked=$sachecked='';
                                    $court_type = !empty($caseData['court_type']) ? $caseData['court_type'] : NULL;
                                    $state_id = !empty($caseData['state_id']) ? $caseData['state_id'] : NULL;
                                    $district_id = !empty($caseData['district_id']) ? $caseData['district_id'] : NULL;
                                    $estab_code = !empty($caseData['estab_code']) ? $caseData['estab_code'] : NULL;
                                    $estab_id = !empty($caseData['estab_id']) ? $caseData['estab_id'] : NULL;

                                    //echo '<pre>'; print_r($caseData['court_type']); exit;
                                    ?>
                                <div class="form-check form-check-inline m-0">
                                    <input checked class="form-check-input cus-form-check" type="radio" id="radio_selected_court2" name="radio_selected_court" onchange="get_court_as(this.value)" value="1" maxlength="2" <?php echo $hcchecked; ?>>
                                    <label class="form-check-label" for="inlineRadio1">High Court</span></label>
                                </div>
                                <div class="form-check form-check-inline m-0">
                                    <input class="form-check-input cus-form-check" type="radio" id="radio_selected_court3" name="radio_selected_court" onchange="get_court_as(this.value)" value="3" maxlength="2" <?php echo $dcchecked; ?>>
                                    <label class="form-check-label" for="inlineRadio2">District Court</label>
                                </div>
                                <div class="form-check form-check-inline m-0">
                                    <input class="form-check-input cus-form-check" type="radio" id="radio_selected_court5" name="radio_selected_court" onchange="get_court_as(this.value)" value="5" maxlength="2" <?php echo $sachecked; ?>>
                                    <label class="form-check-label" for="inlineRadio2">State Agency/Tribunal</label>
                                </div>
                            </div>
                               
                            
                        </div>
                    </div>
                    <hr>
                    <div id="supreme_court_info" style="display: none;">                                               
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                                <div class="mb-3">
                                    <label for="" class="form-label">Case Type <span style="color: red">*</span></label>
                                    <select class="form-select cus-form-ctrl input-sm filter_select_dropdown"
                                        aria-label="Default select example" name="sci_case_type_id" id="sci_case_type_id">
                                        <option value="" title="Select">Select Case Type</option>
                                        
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                                
                                <div class="mb-3">
                                    <label for="" class="form-label">Case No. And Year <span style="color: red">*</span></label>
                                    <input id="sci_case_number" name="sci_case_number" placeholder="Case No"
                                    tabindex='17' value="" class="form-control cus-form-ctrl sci_validation" 
                                    type="text" minlength="1" maxlength="10" >
                                    <select class="form-select cus-form-ctrl"
                                        aria-label="Default select example" name="sci_case_year" id="sci_case_year">
                                        <option value="" title="Select">Select Year</option>
                                        
                                    </select>
                                </div>
                            </div>
                        </div>                        
                    </div>         

                    <div id="high_court_info" style="display: none;">
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="mb-3">
                                    <div class="row justify-content-center">
                                        <div class="col-12 col-sm-12 col-md-1 col-lg-1">
                                            <label for="" class="form-label text-ryt ">CNR <span style="color: red">*</span></label>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                                            <input id="cnr" name="cnr" placeholder="CNR" value="" class="form-control cus-form-ctrl sci_validation" maxlength="16" type="text"  maxlength="16" style="text-transform: uppercase;"> 
                                            <p class="pt-2">  <strong style="color: red !important; font-size: 14px;">Kindly search lower court details using CNR preferably for swift data retrieval</strong></p>
                                        </div>    
                                            <?php
                                            // pattern="^[A-Z]{4}[0-9]{12}$" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');"
                                            ?>
                                    
                                        
                                        <!-- <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                                            <div class="d-flex h-100 align-items-center">
                                            
                                            </div>
                                        </div> -->
                                    </div>
                                   

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <h2 class="text-center or-divider"><span>OR</span></h2>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                                <div class="mb-3">
                                    <label for="" class="form-label">High Court <span style="color: red">*</span></label>
                                    <select class="form-select cus-form-ctrl filter_select_dropdown"
                                            aria-label="Default select example" name="hc_court" id="hc_court">
                                    <option value="" title="Select">Select High Court</option>
                                    <?php
                                if(isset($high_courts) && !empty($high_courts)){
                                    foreach ($high_courts as $courts){
                                        //   echo '<option value="'. url_encryption($courts['hc_id'] . "##" . $courts['name'] ) . '">' . escape_data(strtoupper($courts['name'])) . '</option>';
                                    }
                                }
                                ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                                <div class="mb-3">
                                    <label for="" class="form-label">Bench <span style="color: red">*</span></label>
                                    <select class="form-select cus-form-ctrl input-sm filter_select_dropdown"
                                        aria-label="Default select example" name="hc_court_bench" id="hc_court_bench">
                                        <option value="" title="Select">Select High Court Bench</option>
                                        
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                                <div class="mb-3">
                                    <label for="" class="form-label">Case Type <span style="color: red">*</span></label>
                                    <select class="form-select cus-form-ctrl input-sm filter_select_dropdown"
                                        aria-label="Default select example" name="case_type_id" id="case_type_id">
                                        <option value="" title="Select">Select Case Type</option>
                                        
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-4 col-lg-4 hc_case_type_name" style="display: none;">
                                <div class="mb-3">
                                    <label for="" class="form-label">Case Type Name <span style="color: red">*</span></label>
                                    <input class="form-control cus-form-ctrl"  type="text" name="hc_case_type_name" id="hc_case_type_name" placeholder="Enter Case Type Name" tabindex='20' maxlength="100">
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                                <div class="mb-3">
                                    <label for="" class="form-label">Case No. And Year <span style="color: red">*</span></label>
                                    <div class="row">
                                    <div class="col-12 col-sm-12 col-md-8 col-lg-8">
                                        <input id="hc_case_number" name="hc_case_number" placeholder="Case No"
                                        tabindex='17' value="" class="form-control cus-form-ctrl sci_validation" type="text">
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                                        <select class="form-select cus-form-ctrl"
                                        aria-label="Default select example" name="hc_case_year" id="hc_case_year">
                                        <option value="" title="Select">Select Year</option>
                                        <?php
                                        $end_year = 47;
                                        for ($i = 0; $i <= $end_year; $i++) {
                                            $sel = '';
                                            $year = (int) date("Y") - $i;
                                            /* if (url_encryption($data_to_be_populated['year']) == url_encryption($year)) {
                                                $sel = 'selected=selected';
                                            } else {
                                                $sel = '';
                                            } */
                                            echo '<option ' . $sel . ' value=' . url_encryption($year) . '>' . $year . '</option>';
                                        }
                                        ?>
                                        
                                    </select>
                                    </div>

                                    </div>
                                    
                                    
                                
                                </div>
                            </div>


                        </div>
                        
                    </div>
                    
                    <div class="row" id="district_court_info" style="display: none;">
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="mb-3">
                                    <div class="row justify-content-center">
                                    <div class="col-12 col-sm-12 col-md-1 col-lg-1">
                                    <label for="" class="form-label text-ryt">CNR <span style="color: red">*</span></label>
                                    </div>
                                    <?php
                                    //  pattern="^[A-Z]{4}[0-9]{12}$" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');"
                                    ?>
                                     <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                                    <input id="dc_cnr" name="dc_cnr" placeholder="CNR" value="" class="form-control cus-form-ctrl sci_validation" maxlength="16" type="text"  maxlength="16" style="text-transform: uppercase;"> 
                                    <p class="pt-2"><strong style="color: red !important; font-size: 14px;">Kindly search lower court details using CNR preferably for swift data retrieval</strong></p>
                                     </div>
                                     <!-- <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                                     <div class="d-flex h-100 align-items-center">
                                    
                                     </div></div> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <h2 class="text-center or-divider"><span>OR</span></h2>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                                <div class="mb-3">
                                    <label for="" class="form-label">State <span style="color: red">*</span></label>
                                    <select class="form-select cus-form-ctrl"
                                            aria-label="Default select example" name="state" id="state">
                                    <option value="" title="Select">Select State</option>
                                    <?php
                                    
                                    ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                                    <div class="mb-3">
                                        <label for="" class="form-label">District <span style="color: red">*</span></label>
                                        <select class="form-select cus-form-ctrl"
                                            aria-label="Default select example" name="district" id="district">
                                            <option value="" title="Select">Select District</option>
                                            
                                        </select>
                                    </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                                <div class="mb-3">
                                    <label for="" class="form-label">Establishment <span style="color: red">*</span></label>
                                    <select class="form-select cus-form-ctrl"
                                        aria-label="Default select example" name="establishment" id="establishment">
                                        <option value="" title="Select">Select Establishment</option>
                                        
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                                <div class="mb-3">
                                    <label for="" class="form-label">Case Type <span style="color: red">*</span></label>
                                    <select class="form-control cus-form-ctrl"
                                        aria-label="Default select example" name="dc_case_type_id" id="dc_case_type_id">
                                        <option value="" title="Select">Select Case Type</option>
                                        
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-4 col-lg-4 dc_case_type_name" style="display: none;">
                                <div class="mb-3">
                                    <label for="" class="form-label">Case Type Name <span style="color: red">*</span></label>
                                    <input class="form-control cus-form-ctrl" type="text" aria-label="Default select example" name="dc_case_type_name" id="dc_case_type_name" placeholder="Enter Case Type Name" maxlength="100">
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                                <div class="mb-3">
                                    <label for="" class="form-label">Case No. And Year <span style="color: red">*</span></label>
                                    
                                
                                <div class="row">
                                    <div class="col-12 col-sm-12 col-md-8 col-lg-8">
                                        <input id="dc_case_number" name="dc_case_number" placeholder="Case No"
                                        tabindex='17' value="" class="form-control cus-form-ctrl sci_validation" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');"
                                        type="text" minlength="1" maxlength="10" >
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                                        <select class="form-select cus-form-ctrl"
                                        aria-label="Default select example" name="dc_case_year" id="dc_case_year">
                                        <option value="" title="Select">Select Year</option>
                                        <?php
                                        $end_year = 47;
                                        for ($i = 0; $i <= $end_year; $i++) {
                                            $sel = '';
                                            $year = (int) date("Y") - $i;
                                            /* if (url_encryption($data_to_be_populated['year']) == url_encryption($year)) {
                                                $sel = 'selected=selected';
                                            } else {
                                                $sel = '';
                                            } */
                                            echo '<option ' . $sel . ' value=' . url_encryption($year) . '>' . $year . '</option>';
                                        }
                                        ?>
                                        
                                        
                                    </select>
                                    </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>        
                    
                
                    <div class="row" id="state_agency_info" style="display: none;">
                        <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                            <div class="mb-3">
                                <label for="" class="form-label">State <span style="color: red">*</span></label>
                                <select class="form-select cus-form-ctrl"
                                        aria-label="Default select example" name="agency_state" id="agency_state">
                                <option value="" title="Select">Select State</option>
                                <?php
                                
                                ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                                <div class="mb-3">
                                    <label for="" class="form-label">Agency <span style="color: red">*</span></label>
                                    <select class="form-select cus-form-ctrl"
                                        aria-label="Default select example" name="agency" id="agency">
                                        <option value="" title="Select">Select Agency</option>
                                        
                                    </select>
                                </div>
                        </div>
                        <div class="col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Case Type <span style="color: red">*</span>:</label>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <select tabindex = '8' name="agency_case_type_id" id="agency_case_type_id" class="form-control cus-form-ctrl  filter_select_dropdown" style="width: 100%">
                                        <option value="" title="Select">Select Case Type</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 col-xs-12 agency_case_type_name" style="display: none;">
                            <div class="form-group">
                                <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Case Type Name <span style="color: red">*</span></label>
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <input type="text"  name="agency_case_type_name" id="agency_case_type_name" class="form-control input-sm" placeholder="Enter Case Type Name" maxlength="100">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                            <div class="mb-3">
                                <label for="" class="form-label">Case No. And Year <span style="color: red">*</span></label>
                                
                            
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-8 col-lg-8">
                                    <input id="case_number" name="case_number" placeholder="Case No"
                                    tabindex='17' value="" class="form-control cus-form-ctrl sci_validation"  type="text" minlength="1" maxlength="10" >
                                </div>
                                <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                                    <select class="form-select cus-form-ctrl"
                                    aria-label="Default select example" name="case_year" id="case_year">
                                    <option value="" title="Select">Select Year</option>
                                    <?php
                                    $end_year = 48;
                                    for ($i = 0; $i <= $end_year; $i++) {
                                        $year = (int) date('Y') - $i;
                                        $sel = $year == ((int) date('Y')) ? 'selected=selected' : '';
                                        echo '<option ' . $sel . ' value=' . $year . '>' . $year . '</option>';
                                    }
                                    ?>

                                    
                                </select>
                                </div>

                                </div>
                            </div>
                        </div>

                        
                            
                    </div> 
                    
                    <div class="col-md-12 col-sm-12 col-xs-12 text-center" id="search_button_div">
                        <div class="col-sm-12 col-xs-12 col-md-offset-5">
                            <div class="mb-3">
                                <div class="form-group">
                                    <div class="col-md-offset-3">
                                        <input tabindex = '17' type="button" id="search_case_hc" name="search_case_hc" value="Search" class="quick-btn gray-btn">
                                    </div>
                                </div>
                            </div>                                                        
                        </div>
                    </div>
                    <div id="case_result"></div>

                    <!----END : Show search Ressult---->
                    <div class="clearfix"></div><br><br>
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                            <div class="mb-3">
                                <label for="" class="form-label">Impugned Order Date <span style="color: red" class="astriks">*</span></label>
                                <div class="row">
                                    <div class="slect-date-two">
                                        <input tabindex='18' class="form-control cus-form-ctrl" id="order_date" name="order_date" maxlength="10" placeholder="DD/MM/YYYY" type="text">
                                        <select id="order_dates_list" style="width: 0% !important;" class="form-select cus-form-ctrl" aria-label="Default select example"></select>
                                    </div>
                                    <!-- <div class="col-md-8">
                                        <input tabindex = '18'class="form-control has-feedback-left cus-form-ctrl mb-3" id="order_date" name="order_date" maxlength="10" placeholder="DD/MM/YYYY" type="text"></div>
                                    <div class="col-md-4">
                                        <select id="order_dates_list" class="form-control cus-form-ctrl"></select>
                                    </div> -->
                                </div>                                
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                            <div class="mb-3">
                                <label for="" class="form-label">Impugned Order Challenged <span style="color: red">*</span></label>
                                <?php
                                $selectYes = (isset($new_case_details[0]['judgement_challenged']) && $new_case_details[0]['judgement_challenged'] == '1') ? 'selected=selected' : '';
                                $selectNo = (isset($new_case_details[0]['judgement_challenged']) && $new_case_details[0]['judgement_challenged'] == '2') ? 'selected=selected' : '';        
                                ?>                                
                                <select tabindex='4' name="judgement_challenged" id="judgement_challenged" class="form-control cus-form-ctrl filter_select_dropdown">
                                    <option <?php echo $selectYes; ?> value="1">Yes</option>
                                    <option <?php echo $selectNo; ?> value="0">No</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                            <div class="mb-3">
                                <label for="" class="form-label">Impugned Order Type <span style="color: red">*</span></label>
                                <?php
                                $selectInitial = (isset($new_case_details[0]['judgement_type']) && $new_case_details[0]['judgement_type'] == 'F') ? 'selected=selected' : '';
                                $selectInterim = (isset($new_case_details[0]['judgement_type']) && $new_case_details[0]['judgement_type'] == 'I') ? 'selected=selected' : '';
                                ?>
                                <select tabindex='4' name="judgement_type" id="judgement_type" class="form-control cus-form-ctrl filter_select_dropdown">
                                    <option <?php echo $selectInitial; ?> value="F">Final</option>
                                    <option <?php echo $selectInterim; ?> value="I">Interim</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                            <div class="mb-3">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input cus-form-check" type="checkbox" id="fircheckbox" name="fircheckbox"   maxlength="2" >
                                    <label class="form-check-label" for="inlineRadio1">FIR Details</span></label>
                                </div>                        
                            </div>
                        </div>
                    </div>
                    <div class="row" id="firDiv" style="display: none;">                        
                            <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                                <div class="mb-3">
                                    <label for="" class="form-label">State <span style="color: red">*</span></label>
                                    <select class="form-select cus-form-ctrl"
                                            aria-label="Default select example" name="fir_state" id="fir_state">
                                    <option value="" title="Select">Select State</option>
                                   
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                                    <div class="mb-3">
                                        <label for="" class="form-label">District <span style="color: red">*</span></label>
                                        <select class="form-select cus-form-ctrl"
                                            aria-label="Default select example" name="fir_district" id="fir_district">
                                            <option value="" title="Select">Select District</option>
                                        
                                        </select>
                                    </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                                <div class="mb-3">
                                    <label for="" class="form-label">Police Station <span style="color: red">*</span></label>
                                    <select class="form-select cus-form-ctrl"
                                        aria-label="Default select example" name="fir_policeStation" id="fir_policeStation">
                                        <option value="" title="Select">Select Police Station</option>
                                    
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                                <div class="mb-3">
                                   
                                    <label class="form-label">FIR No</label>
                                    <?php
                                    
                                    ?>
                                    <input id="fir_number" name="fir_number" placeholder="FIR Number"
                                    tabindex='17' value="" class="form-control cus-form-ctrl sci_validation" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');"
                                    type="text" minlength="10" maxlength="10">

                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                                <div class="mb-3">
                                    <label for="" class="form-label">Year</label>
                                    <?php
                                    
                                    ?>
                                    
                                    <select tabindex='4' name="fir_year" id="fir_year"
                                        class="form-control cus-form-ctrl filter_select_dropdown">
                                        <option value="">Year</option>
                                        <?php
                                        $end_year = 48;
                                        for ($i = 0; $i <= $end_year; $i++) {
                                            $year = (int) date("Y") - $i;
                                        
                                            echo '<option ' . $sel . ' value=' . url_encryption($year) . '>' . $year . '</option>';
                                        }
                                        ?>
                                    </select>

                                </div>
                            </div>
                            <div class="col-12">
                             <label for="" class="form-label">If Police Station not in list, please enter Police Station name and Complete FIR number below.</label>
                            </div>
                            <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                                <div class="mb-3">
                                    <label for="" class="form-label">Police Station Name <span style="color: red">*</span></label>
                                    <input id="police_station_name" name="police_station_name" placeholder="Police Station Name"
                                    tabindex='17' value="" class="form-control cus-form-ctrl sci_validation" type="text" maxlength="100">
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                                <div class="mb-3">
                                    <label for="" class="form-label">Complete FIR No. <span style="color: red">*</span></label>
                                    <input id="complete_fir_number" name="complete_fir_number" placeholder="FIR Number" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');"
                                    tabindex='17' value="" class="form-control cus-form-ctrl sci_validation" type="text"  maxlength="15" >

                                </div>
                            </div>
                        
                    </div>

                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 my-3 text-center">
                        <div class="col-sm-12 col-xs-12 col-md-offset-5">
                            
                                <div class="save-btns">
                                    
                                    <a tabindex = '30' href="<?= base_url('caveat/caveatee') ?>" class="quick-btn" type="button">Previous</a>
                                    <input tabindex = '28' type="submit" class="quick-btn gray-btn" id="subcourt_save" value="SAVE">
                                    <?php
                                    if(isset($subordinate_court_details) && !empty($subordinate_court_details)){
                                        echo ' <a  tabindex = "29" href="'.base_url('uploadDocuments').'" class="quick-btn" type="button">Next</a>';
                                    }
                                    ?>
                                
                                
                                
                                
                            </div>
                        </div>
                        </div>
                        <?php echo form_close();
                        ?>
                
                <div class="row">
                    @include('newcase.subordinate_court_list')
                </div>
            </div>
        </div>
        </div>
    </div>
                            <!-- form--end  -->
                       
    <script src="<?= base_url() . 'assets/newAdmin/' ?>js/jquery351.min.js"></script>
    <script src="<?= base_url() . 'assets/newAdmin/' ?>js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url() . 'assets/newAdmin/' ?>js/general.js"></script>
    <script src="<?= base_url() . 'assets' ?>/vendors/jquery/dist/jquery.min.js"></script>
    <script src="<?= base_url() ?>assets/js/bootstrap-datepicker.min.js"></script>
    <script src="<?= base_url() ?>assets/js/sha256.js"></script>
    <script src="<?= base_url() ?>assets/newAdmin/js/jquery.dataTables.min.js"></script>
    <script src="<?= base_url() . 'assets' ?>/js/select2.min.js"></script>
    <script src="<?= base_url() . 'assets' ?>/js/jquery.validate.js"></script>
        @push('script') 
<script>

 $(document).ready(function() {
    $('.filter_select_dropdown').select2();
        var today = new Date();
        $('#order_date').datepicker({ 
        format: "dd/mm/yyyy",
        showOtherMonths: true,
        selectOtherMonths: true,
        changeMonth: true,
        changeYear: true,
        endDate: today,
        autoclose: true
    });

    });  
 
    $(document).ready(function () {
        get_fir_state_list();
        toggle_entry_div();
        $('#case_type_id').on('change', function () {
            var case_type_id = $(this).val();
            var case_type_selectedText = $("#case_type_id option:selected").html();
            if(case_type_selectedText==='NOT IN LIST'){
                alert('Are you sure that it is not in list');
                $('.hc_case_type_name').show();
            }else {  $('.hc_case_type_name').hide();}
        });
        $('#dc_case_type_id').on('change', function () {
            var case_type_id = $(this).val();
            var case_type_selectedText = $("#dc_case_type_id option:selected").html();
            if(case_type_selectedText==='NOT IN LIST'){
                alert('Are you sure that it is not in list');
                $('.dc_case_type_name').show();
            }else {  $('.dc_case_type_name').hide();}
        });
        $('#agency_case_type_id').on('change', function () {
            var case_type_id = $(this).val();
            var case_type_selectedText = $("#agency_case_type_id option:selected").html();
            if(case_type_selectedText==='NOT IN LIST'){
                alert('Are you sure that it is not in list');
                $('.agency_case_type_name').show();
            }else {  $('.agency_case_type_name').hide();}
        });

        $('#order_dates_list').on('change', function() {
                var selectedText = $("#order_dates_list option:selected").html();
                var dateText = selectedText.split(" ");
                $("#order_date").val(dateText[0]);
                var ordType = dateText[1].split("-");
                $("#judgement_type").val(ordType[0]).change();
            });

        $('#cnr').on('input', function () {
            if( $.trim($('#cnr').val())=='')
                $("#hc_court, #hc_court_bench, #case_type_id, #case_number, #case_year").prop("disabled", false);
            else
                $("#hc_court, #hc_court_bench, #case_type_id, #case_number, #case_year").prop("disabled", true);
        });

        $('#dc_cnr').on('input', function () {
            if( $.trim($('#dc_cnr').val())=='')
                $("#state, #district, #establishment, #dc_case_type_id, #dc_case_number, #dc_case_year").prop("disabled", false);
            else
                $("#state, #district, #establishment, #dc_case_type_id, #dc_case_number, #dc_case_year").prop("disabled", true);
        });
        $('#police_station_name').on('input', function () {
            if( $.trim($('#police_station_name').val())=='')
                $("#fir_policeStation,#fir_number, #fir_year").prop("disabled", false);
            else
                $("#fir_policeStation,#fir_number, #fir_year").prop("disabled", true);
        });
        $('#fir_policeStation').on('change', function() {
            if( $.trim(this.value)!=''){
                $("#police_station_name,#complete_fir_number").prop("disabled", true);
                $("#police_station_name,#complete_fir_number").attr("required", false);
            }
            else{
                $("#police_station_name,#complete_fir_number").prop("disabled", false);
                $("#police_station_name,#complete_fir_number").attr("required", true);
            }
        });
        $('#subordinate_court_details').on('submit', function () {
            var hc_case_number = $("#hc_case_number").val();
            var cnr = $("#cnr").val();
        //    if(hc_case_number=='' && cnr==''){
        //     alert('Please fill Case No. And Year OR CNR Number.');
        //     return false;
        //    }
            var judgement_type = $("#judgement_type").val();
            var order_date = $("#order_date").val();
            if(judgement_type){
                if(order_date == ''){
                    alert("Please fill impugned order date.");
                    $("#order_date").css('border-color','red');
                    return false;
                }
            }
            if ($('#subordinate_court_details').valid()) {
               // var form_data = $(this).serialize();
              // alert('hello');
              var court_type = $("input[name=radio_selected_court]:checked").val() ?? 1;
             
                 var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

                var formdata = new FormData();
                formdata.append("CSRF_TOKEN", CSRF_TOKEN_VALUE);
                formdata.append("radio_selected_court", $('[name="radio_selected_court"]:checked').val());
                formdata.append("agency_case_type_name", $('[name="agency_case_type_name"]').val());
                formdata.append("case_number", $('[name="case_number"]').val());
                formdata.append("fir_year", $('[name="fir_year"]').val());
                formdata.append("fir_number", $('[name="fir_number"]').val());
                formdata.append("complete_fir_number", $('[name="complete_fir_number"]').val());
                formdata.append("order_date", $('[name="order_date"]').val());
                formdata.append("judgement_challenged", $('[name="judgement_challenged"]').val());
                formdata.append("judgement_type", $('[name="judgement_type"]').val());
                
                formdata.append("case_type_id", $('[name="case_type_id"]').val());
                
                if (court_type == 1) { //-High Court
                    formdata.append("hc_case_type_name", $('[name="hc_case_type_name"]').val());               
                    formdata.append("hc_case_number", $('[name="hc_case_number"]').val());               
                    formdata.append("hc_case_year", $('[name="hc_case_year"]').val());
                } else if (court_type == 4) {//--Supreme court
                    formdata.append("sci_case_number", $('[name="sci_case_number"]').val());               
                    formdata.append("sci_case_year", $('[name="sci_case_year"]').val());
                } else if (court_type == 3) {//--District court
                    formdata.append("dc_case_type_name", $('[name="dc_case_type_name"]').val());               
                    formdata.append("dc_case_number", $('[name="dc_case_number"]').val());               
                    formdata.append("dc_case_year", $('[name="dc_case_year"]').val());
                } else if (court_type == 5) {//--Agency court
                    // var case_type_name = $('#agency_case_type_name').val();
                    formdata.append("agency_case_type_name", $('[name="agency_case_type_name"]').val());               
                    formdata.append("case_number", $('[name="case_number"]').val());               
                    formdata.append("case_year", $('[name="case_year"]').val());
                }
                
                
                
                formdata.append("cnr", $('[name="cnr"]').val());
                formdata.append("hc_court_bench", $('[name="hc_court_bench"]').val());               
                formdata.append("hc_court", $('[name="hc_court"]').val());             
                
                formdata.append("dc_case_type_id", $('[name="dc_case_type_id"]').val());
                formdata.append("state", $('[name="state"]').val());               
                formdata.append("dc_case_year", $('[name="dc_case_year"]').val());               
                formdata.append("district", $('[name="district"]').val());

                formdata.append("establishment", $('[name="establishment"]').val());
                formdata.append("sci_case_type_id", $('[name="sci_case_type_id"]').val());               
                formdata.append("judgement_challenged", $('[name="judgement_challenged"]').val());               
                formdata.append("judgement_type", $('[name="judgement_type"]').val());             
                formdata.append("sci_case_number", $('[name="sci_case_number"]').val());          
                formdata.append("sci_case_year", $('[name="sci_case_year"]').val());
                formdata.append("agency_state", $('[name="agency_state"]').val());
                formdata.append("agency", $('[name="agency"]').val());


                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url();?>newcase/subordinate_court/add_subordinate_court_details",
                    data: formdata,
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        $('#subcourt_save').val('Please wait...');
                        $('#subcourt_save').prop('disabled', true);
                    },
                    success: function (data) {
                        $('#subcourt_save').val('SAVE');
                        $('#subcourt_save').prop('disabled', false);
                        var resArr = data.split('@@@');

                        if (resArr[0] == 1) {
                            // $('#msg').show();
                            // $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                            $('#dangerAlert').show();
                            $('#dangerAlert').html(resArr[1]);
                        } else if (resArr[0] == 2) {
                            // $(".form-response").html("<p class='message valid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                            // $('#msg').show();
                            $('#successAlert').show();
                            $('#successAlert').html(resArr[1]);
                            location.reload();
                            //window.location.href = resArr[2];
                        } else if (resArr[0] == 3) {
                            // $('#msg').show();
                            // $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                            $('#dangerAlert').show();
                            $('#dangerAlert').html(resArr[1]);
                        }
                        $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        });
                    },
                    error: function () {
                        $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        });
                    }

                });
                return false;
            } else {
                return false;
            }
        });

        //----------Get High Court Bench List----------------------//
        $('#hc_court').change(function () {

            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $('#case_type_id').val('');

            var high_court_id = $(this).val();
            $.ajax({
                type: "POST",
                data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, high_court_id: high_court_id, court_type: '1'},
                url: "<?php echo base_url('newcase/Ajaxcalls_subordinate_court/get_hc_bench_list'); ?>",
                success: function (data)
                {
                    $('#hc_court_bench').html(data);
                    if(hc_bench_value && court_type == 1){
                        $('#hc_court_bench').val(hc_bench_value).select2().trigger("change");
                    }
                    $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                },
                error: function () {
                    $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                }
            });

        });

        //----------Get Case Type List----------------------//
        $('#hc_court_bench').change(function () {
//alert('hiiii');
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $('#case_type_id').val('');

            var hc_bench_id = $(this).val();
            $.ajax({
                type: "POST",
                data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, hc_bench_id: hc_bench_id, court_type: '1'},
                url: "<?php echo base_url('newcase/Ajaxcalls_subordinate_court/get_hc_case_type_list'); ?>",
                success: function (data)
                {
                    //alert(data);
                   // $('#sci_case_type_id').val(data);
                    $('#case_type_id').html(data);
                    $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                },
                error: function () {
                    $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                }
            });

        });


        $("#search_case_hc").click(function () {
            $('#search_button_div').hide();
            $('#loader-wrapper').show();
            var court_type = $("input[name=radio_selected_court]:checked").val() ?? 1;
            // alert(court_type);
            if (court_type == 1) { //-High Court

                var hc_court = $('#hc_court option:selected').val();
                var hc_court_bench = $('#hc_court_bench option:selected').val();
                var hc_court_name = $('#hc_court option:selected').text();
                var case_type = $('#case_type_id option:selected').val();
                var case_type_name = $('#hc_case_type_name').val();
                var case_number = $('#hc_case_number').val();
                var case_year = $('#hc_case_year option:selected').val();
                var cnr = $('#cnr').val();

            } else if (court_type == 4) {//--Supreme court

                case_type = $('#sci_case_type_id option:selected').val();
                var case_type_name = '';
                case_number = $('#sci_case_number').val();
                case_year = $('#sci_case_year option:selected').val();

            } else if (court_type == 3) {//--District court

                var estab_id = $('#establishment option:selected').val();
                case_type = $('#dc_case_type_id option:selected').val();
                var case_type_name = $('#dc_case_type_name').val();
                case_number = $('#dc_case_number').val();
                case_year = $('#dc_case_year option:selected').val();
                cnr = $('#dc_cnr').val();
            } else if (court_type == 5) {//--Agency court
                var case_type_name = $('#agency_case_type_name').val();
            }
            //alert(case_number);alert(case_year);
            $('#msg').hide();
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('newcase/Ajaxcalls_subordinate_court/search_case_details'); ?>",
                data: {cnr:cnr, selected_court: court_type, high_court_id: hc_court, hc_name: hc_court_name, hc_court_bench: hc_court_bench, estab_id: estab_id, case_type_id: case_type,case_type_name:case_type_name, case_number: case_number, case_year: case_year},
                async: true,
                success: function (data) {
                    $('#loader-wrapper').hide();
                    $('#search_button_div').show();
                    var resArr = data.split('@@@');
                    if(resArr[0] == 3){
                        $('#errDiv').show();
                        $('#alertMsg').html(resArr[1]);
                        // alert(resArr[1]); 
                        const errDiv = document.getElementById('errDiv');
                        errDiv.style.display = 'block';
                        setTimeout(() => {
                            errDiv.style.display = 'none';
                        }, 2000);
                    }
                    if (resArr[0] == 1) {
                        $('#msg').show();
                        $("#case_result").html("<p class='message center invalid' style='color: red; font-weight: bold; ' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()></span></p>")
                        // setTimeout(function () {
                        //     $("#case_result_not_found").hide();
                        // }, 5000);
                    } else if (resArr[0] == 2) {
                        $("#case_result").html(resArr[1]);
                        $("#order_date").val(resArr[2]);
                        $("#order_dates_list").html(resArr[3]);
                        $('#show_button').hide();

                    } else if (resArr[0] == 3) {
                        $('#msg').show();
                        $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                    }
                    $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                },
                error: function () {
                    $('#loader_div').hide();
                    $('#loader-wrapper').hide();

                    $('#search_button_div').show();
                    $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                }
            });
        });
        //earlier courts selection
        court_type = "<?php echo isset($court_type)?$court_type:1;?>";
        hc_bench_value = "<?php echo isset($hc_bench_value)?$hc_bench_value:'';?>";
        hc_value = "<?php echo isset($hc_value)?$hc_value:'';?>";
        state_value = "<?php echo isset($state_value)?$state_value:'';?>";
        district_value = "<?php echo isset($district_value)?$district_value:'';?>";
        agency_state_value = "<?php echo isset($agency_state_value)?$agency_state_value:'';?>";
        agency_name_value = "<?php echo isset($agency_name_value)?$agency_name_value:'';?>";
        court_type = parseInt(court_type);
        if(court_type){
            switch (court_type){
                case 1:
                    //high court
                    $("#radio_selected_court2").prop('checked',true).trigger('click');
                    $('#supreme_court_info').hide();
                    $('#district_court_info').hide();
                    $('#state_agency_info').hide();
                    $('#high_court_info').show();
                    $('#search_button_div').show();
                    get_high_court_list(court_type,hc_value);
                    break;
                case 3:
                    //district court
                    $("#radio_selected_court3").prop('checked',true).trigger('click');
                    $('#district_court_info').show();
                    $('#high_court_info').hide();
                    $('#state_agency_info').hide();
                    $('#supreme_court_info').hide();
                    $('#search_button_div').show();
                    get_state_list();
                    break;
                case 4:
                    //supreme court
                    $("#radio_selected_court1").prop('checked',true).trigger('click');
                    $('#high_court_info').hide();
                    $('#state_agency_info').hide();
                    $('#district_court_info').hide();
                    $('#supreme_court_info').show();
                    $('#search_button_div').show();
                    get_sci_case_type()
                    break;
                case 5:
                    //state agency
                    $("#radio_selected_court5").prop('checked',true).trigger('click');
                    $('#state_agency_info').show();
                    $('#high_court_info').hide();
                    $('#district_court_info').hide();
                    $('#supreme_court_info').hide();
                    $('#search_button_div').hide();
                    get_agency_state_list();
                    break;
                default:
            }
        }
    });

    
    /* Functions for fir */
    function get_fir_state_list()
    {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $('#fir_state').val('');

        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE},
            url: "<?php echo base_url('newcase/Ajaxcalls_subordinate_court/get_icmis_state_list'); ?>",
            success: function (data)
            {
                $('#fir_state').html(data);
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            },
            error: function () {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
    }

    $('#fir_state').change(function () {

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $('#fir_district').val('');

        var get_state_id = $(this).val();
        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, state_id: get_state_id},
            url: "<?php echo base_url('newcase/Ajaxcalls_subordinate_court/get_icmis_district_list'); ?>",
            success: function (data)
            {
                $('#fir_district').html(data);
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            },
            error: function () {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });

    });

    $('#fir_district').change(function () {
        $('#fir_policeStation').val('');

        var get_distt_id = $(this).val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var state_id = $("#fir_state option:selected").val();
        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, state_id: state_id, get_distt_id: get_distt_id},
            url: "<?php echo base_url('newcase/Ajaxcalls_subordinate_court/get_police_station_list'); ?>",
            success: function (data) {
                $('#fir_policeStation').html(data);
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            },
            error: function () {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
    });


    function get_court_as(court_as) {
        //alert('hiii');

        $('#select2-case_type_id-container').text('Select Case Type');
        $('#case_type_id').val('');

        if (court_as == '4') {
            $('#high_court_info').hide();
            $('#state_agency_info').hide();
            $('#district_court_info').hide();
            $('#supreme_court_info').show();
            $('#search_button_div').show();
            get_sci_case_type()
        } else if (court_as == '1') {

            $('#supreme_court_info').hide();
            $('#district_court_info').hide();
            $('#state_agency_info').hide();
            $('#high_court_info').show();
            $('#search_button_div').show();
            get_high_court_list(court_as);
        } else if (court_as == '3') {

            $('#district_court_info').show();
            $('#high_court_info').hide();
            $('#state_agency_info').hide();
            $('#supreme_court_info').hide();
            $('#search_button_div').show();
            get_state_list();

        }else if (court_as == '5') {
            $('#state_agency_info').show();
            $('#high_court_info').hide();
            $('#district_court_info').hide();
            $('#supreme_court_info').hide();
            $('#search_button_div').hide();
            get_agency_state_list();
        }
    }

    function get_high_court_list(court_as,hc_value=null) {

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, selected_post_id: court_as},
            url: "<?php echo base_url('newcase/Ajaxcalls_subordinate_court/get_high_court'); ?>",
            success: function (data)
            {
                $('#hc_court').html(data);
                if(hc_value && court_as == 1){
                    $('#hc_court').val(hc_value).select2().trigger("change");
                }
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            },
            error: function () {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });

    }

    function get_sci_case_type() {

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $('#sci_case_type_id').val('');

        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE},
            url: "<?php echo base_url();?>newcase/Ajaxcalls_subordinate_court/get_sci_case_type",
            success: function (data)
            {
                $('#sci_case_type_id').html(data);
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            },
            error: function () {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
    }

    function get_state_list() {

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $('#state').val('');

        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE},
            url: "<?php echo base_url('newcase/Ajaxcalls_subordinate_court/get_state_list'); ?>",
            success: function (data)
            {
                $('#state').html(data);
                if(state_value && court_type == 3){
                    $('#state').val(state_value).select2().trigger("change");
                }
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            },
            error: function () {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
    }


    //----------Get District List----------------------//
    $('#state').change(function () {

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $('#district').val('');

        var get_state_id = $(this).val();
        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, state_id: get_state_id},
            url: "<?php echo base_url('newcase/Ajaxcalls_subordinate_court/get_district_list'); ?>",
            success: function (data)
            {
                $('#district').html(data);
                if(district_value && court_type == 3){
                    $('#district').val(district_value).select2().trigger("change");
                }
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            },
            error: function () {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });

    });

    $('#district').change(function () {
        $('#establishment').val('');

        var get_distt_id = $(this).val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var state_id = $("#state option:selected").val();
        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, state_id: state_id, get_distt_id: get_distt_id},
            url: "<?php echo base_url('newcase/Ajaxcalls_subordinate_court/get_establishment_list'); ?>",
            success: function (data)
            {
                if (data.indexOf('ERROR') != -1) {
                    $('#msg').show();
                    $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + data + " <span class='close' onclick=hideMessageDiv()>X</span></p>");
                    $('#establishment').html('<option value=""> Select Establishment </option>');
                } else {
                    $('#msg').hide();
                    $('#establishment').html(data);
                }
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            },
            error: function () {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
    });

    $('#establishment').change(function () {

        var estab_id = $('#establishment').val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, est_code: estab_id},
            url: "<?php echo base_url('newcase/Ajaxcalls_subordinate_court/OpenAPIcase_type_list'); ?>",
            success: function (data)
            {
                $('#dc_case_type_id').html(data);
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });

            },
            error: function () {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });

    });

    $('#nohc_save').click(function () {

        var hcval= $('#chk_nohc').val();
        //  alert(hcval);
        if (document.getElementById('chk_nohc').checked==false)
        {
            alert("Please Select the checkbox to proceed");
            return;
        }
        // alert("dfdsf");
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE},
            url: "<?php echo base_url('newcase/Ajaxcalls_subordinate_court/bypass_hc'); ?>",
            success: function (data)
            {
                var ans=data.split('@');
                //alert(ans[1]);
                window.location="<?php echo base_url('uploadDocuments')?>";
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });

            },
            error: function () {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });

    });


    function get_agency_state_list()
    {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $('#fir_state').val('');

        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE},
            url: "<?php echo base_url('newcase/Ajaxcalls_subordinate_court/get_icmis_state_list'); ?>",
            success: function (data)
            {
                $('#agency_state').html(data);
                if(agency_state_value && court_type == 5){
                    $('#agency_state').val(agency_state_value).select2().trigger("change");
                }
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            },
            error: function () {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
    }


    $('#agency_state').change(function () {

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $('#case_type_id').val('');

        var agency_state_id = $(this).val();
        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, agency_state_id: agency_state_id, court_type: '5'},
            url: "<?php echo base_url('newcase/Ajaxcalls_subordinate_court/get_state_agency_list'); ?>",
            success: function (data)
            {
                $('#agency').html(data);
                if(agency_name_value && court_type == 5){
                    $('#agency').val(agency_name_value).select2().trigger("change");
                }
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            },
            error: function () {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });

    });


    $('#agency').change(function () {

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $('#case_type_id').val('');

        var agency_id = $(this).val();
        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, agency_id: agency_id, court_type: '5'},
            url: "<?php echo base_url('newcase/Ajaxcalls_subordinate_court/get_state_agency_case_types'); ?>",
            success: function (data)
            {
                $('#agency_case_type_id').html(data);
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            },
            error: function () {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });

    });

    function toggle_entry_div()
    {
        if($("#chk_nohc").is(":checked"))
        {
            $("#hc_entry_div").hide();
        }
        else {
            $("#hc_entry_div").show();
        }

    }
    
    

    $(document).on('click','#fircheckbox',function(){
        var checkvalue = $(this).val();
        if(checkvalue == ''){
            $('#fircheckbox').prop('checked',true);
            $('#fircheckbox').val('1');
            $("#firDiv").show();
        }
        else{
            $('#fircheckbox').prop('checked',false);
            $('#fircheckbox').val('');
            $("#firDiv").hide();
        }
    });
    function hideMessageDiv() {
      document.getElementById('msg').style.display = "none";
  }

</script>
<script>
<?php  pr("Step ffffff"); ?>   
</script>
@endpush