<div class="row" id="moveToBottom">
    <div class="col-md-9 col-sm-9 col-xs-9"></div>
    <div class="col-md-2 col-sm-2 col-xs-2">
        <div class="form-group">
            <input type="submit" class="btn btn-success" id="move" value="Click to view list">
        </div>
    </div>
</div>
<div class="panel panel-default">
    <h4 style="text-align: center;color: #31B0D5"> Subordinate Courts </h4>
    <div class="panel-body">
        <?php
        $attribute = array('class' => 'form-horizontal', 'name' => 'subordinate_court_details', 'id' => 'subordinate_court_details', 'autocomplete' => 'off');
        echo form_open('#', $attribute);
        $cause_title = explode(' Vs. ', $new_case_details[0]['cause_title']);
        ?>

        <div class="row">
            <div class="col-sm-12 col-xs-12">
                <?php
               /* $scchecked = $party_details[0]['selected_court'] == '4' ? 'checked="checked"' : '';*/
                $hcchecked = $party_details[0]['selected_court'] == '1' ? 'checked="checked"' : '';
                $dcchecked = $party_details[0]['selected_court'] == '3' ? 'checked="checked"' : '';
                ?>
                <div class="form-group">
                    <label class="control-label col-sm-4 input-sm"> Select Court <span style="color: red">*</span> :</label>
                    <div class="col-md-7 col-sm-12 col-xs-12">
                        <!--<label class="radio-inline"><input tabindex = '1' type="radio" id="radio_selected_court1" name="radio_selected_court"onchange="get_court_as(this.value)" value="4" maxlength="2" <?php /*echo $scchecked; */?>> Supreme Court </label>-->
                        <label class="radio-inline"><input  tabindex = '2' type="radio" id="radio_selected_court2" name="radio_selected_court" onchange="get_court_as(this.value)" value="1" maxlength="2" <?php echo $hcchecked; ?>> High Court </label>
                        <label class="radio-inline"><input tabindex = '3' type="radio" id="radio_selected_court3" name="radio_selected_court" onchange="get_court_as(this.value)" value="3" maxlength="2" <?php echo $dcchecked; ?>> District Court </label>
<!--                        <label class="radio-inline"><input type="radio" id="radio_selected_court3" name="radio_selected_court" onchange="get_court_as(this.value)" value="2" maxlength="2" <?php echo $ochecked; ?>> Other Court </label>
                        <label class="radio-inline"><input type="radio" id="radio_selected_court3" name="radio_selected_court" onchange="get_court_as(this.value)" value="5" maxlength="2" <?php echo $sachecked; ?>> State Agency </label>-->
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <!--<div id="supreme_court_info" style="display: block;">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Case Type <span style="color: red">*</span>:</label>
                            <div class="col-md-7 col-sm-12 col-xs-12">
                                <select name="sci_case_type_id" tabindex = '4' id="sci_case_type_id" class="form-control input-sm filter_select_dropdown" style="width: 100%">
                                    <option value="" title="Select">Select Case Type</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Case No. <span style="color: red">*</span>:</label>
                            <div class="col-md-7 col-sm-12 col-xs-12">
                                <div class="input-group">
                                    <input id="sci_case_number" name="sci_case_number" tabindex = '5' maxlength="10" onkeyup="return isNumber(event)" placeholder="Case No."  class="form-control input-sm age_calculate" type="text">
                                    <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Related Case Number">
                                        <i class="fa fa-question-circle-o"  ></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 col-xs-12">
                        <div class="form-group">
                            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Year <span style="color: red">*</span>:</label>
                            <div class="col-md-3 col-sm-12 col-xs-12">
                                <div class="input-group">
                                    <select tabindex = '6' class="form-control input-sm filter_select_dropdown" id="sci_case_year" name="sci_case_year" style="width: 100%"> 
                                        <option value="">Year</option>
                                        <?php
/*                                        $end_year = 47;
                                        for ($i = 0; $i <= $end_year; $i++) {
                                            $year = (int) date("Y") - $i;
                                            if (url_encryption($data_to_be_populated['year']) == url_encryption($year)) {
                                                $sel = 'selected=selected';
                                            } else {
                                                $sel = '';
                                            }
                                            echo '<option ' . $sel . ' value=' . url_encryption($year) . '>' . $year . '</option>';
                                        }
                                        */?>
                                    </select>
                                </div>
                            </div>
                        </div>                   
                    </div>
                </div>
            </div>
        </div>-->

        <div id="high_court_info" style="display: block;">
            <div class="row">
            <div class="col-sm-4 col-xs-12">
                <div class="form-group">
                    <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">CNR <span style="color: red">*</span>:</label>
                    <div class="col-md-7 col-sm-12 col-xs-12">
                        <div class="input-group">
                            <input tabindex = '0'  id="cnr" name="cnr" maxlength="16" pattern="^[A-Z]{4}[0-9]{12}$" placeholder="CNR"  class="form-control input-sm age_calculate" type="text">
                            <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Please enter CNR ">
                                <i class="fa fa-question-circle-o"  ></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
                <div class="col-sm-4 col-xs-12">
                    You may choose to enter CNR or search case using form below
                </div>
            </div>
            <div class="col-sm-4 col-xs-12">
                <div class="form-group">
                    <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">High Court <span style="color: red">*</span>:</label>
                    <div class="col-md-7 col-sm-12 col-xs-12">
                        <select tabindex = '7' name="hc_court" id="hc_court" class="form-control input-sm filter_select_dropdown" style="width: 100%">
                            <option value="" title="Select">Select High Court</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-sm-4 col-xs-12">
                <div class="form-group">
                    <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Bench <span style="color: red">*</span>:</label>
                    <div class="col-md-7 col-sm-12 col-xs-12">
                        <select name="hc_court_bench" id="hc_court_bench" class="form-control input-sm filter_select_dropdown" style="width: 100%">
                            <option value="" title="Select">Select High Court Bench</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-sm-4 col-xs-12">
                <div class="form-group">
                    <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Case Type <span style="color: red">*</span>:</label>
                    <div class="col-md-7 col-sm-12 col-xs-12">
                        <select tabindex = '8' name="case_type_id" id="case_type_id" class="form-control input-sm filter_select_dropdown" style="width: 100%">
                            <option value="" title="Select">Select Case Type</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-xs-12">
                <div class="form-group">
                    <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Case No. <span style="color: red">*</span>:</label>
                    <div class="col-md-7 col-sm-12 col-xs-12">
                        <div class="input-group">
                            <input tabindex = '9'  id="case_number" name="case_number" maxlength="10" onkeyup="return isNumber(event)" placeholder="Case No."  class="form-control input-sm age_calculate" type="text">
                            <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Related Case Number">
                                <i class="fa fa-question-circle-o"  ></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-xs-12">
                <div class="form-group">
                    <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Year <span style="color: red">*</span>:</label>
                    <div class="col-md-3 col-sm-12 col-xs-12">
                        <div class="input-group">
                            <select tabindex = '10' class="form-control input-sm filter_select_dropdown" id="case_year" name="case_year" style="width: 100%"> 
                                <option value="">Year</option>
                                <?php
                                $end_year = 47;
                                for ($i = 0; $i <= $end_year; $i++) {
                                    $year = (int) date("Y") - $i;
                                    if (url_encryption($data_to_be_populated['year']) == url_encryption($year)) {
                                        $sel = 'selected=selected';
                                    } else {
                                        $sel = '';
                                    }
                                    echo '<option ' . $sel . ' value=' . url_encryption($year) . '>' . $year . '</option>';
                                }
                                ?> 
                            </select>
                        </div>
                    </div>
                </div>                   
            </div>
        </div>

        <div id="district_court_info" style="display: none;">
            <div class="row">
                <div class="col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">CNR <span style="color: red">*</span>:</label>
                        <div class="col-md-7 col-sm-12 col-xs-12">
                            <div class="input-group">
                                <input tabindex = '0'  id="dc_cnr" name="dc_cnr" maxlength="16" pattern="^[A-Z]{4}[0-9]{12}$" placeholder="CNR"  class="form-control input-sm age_calculate" type="text">
                                <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Please enter CNR ">
                                <i class="fa fa-question-circle-o"  ></i>
                            </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 col-xs-12">
                    You may choose to enter CNR or search case using form below
                </div>
            </div>
            <div class="col-sm-4 col-xs-12">
                <div class="form-group">
                    <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">State <span style="color: red">*</span>:</label>
                    <div class="col-md-7 col-sm-12 col-xs-12">
                        <select tabindex = '11' name="state" id="state" class="form-control input-sm filter_select_dropdown" style="width: 100%">
                            <option value="" title="Select">Select State</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-xs-12">
                <div class="form-group">
                    <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">District <span style="color: red">*</span>:</label>
                    <div class="col-md-7 col-sm-12 col-xs-12">
                        <select tabindex = '12' name="district" id="district" class="form-control input-sm filter_select_dropdown" style="width: 100%">
                            <option value="" title="Select">Select District</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-xs-12">
                <div class="form-group">
                    <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Establishment <span style="color: red">*</span>:</label>
                    <div class="col-md-7 col-sm-12 col-xs-12">
                        <select tabindex = '13' name="establishment" id="establishment" class="form-control input-sm filter_select_dropdown" style="width: 100%">
                            <option value="" title="Select">Select Establishment</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-sm-4 col-xs-12">
                <div class="form-group">
                    <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Case Type <span style="color: red">*</span>:</label>
                    <div class="col-md-7 col-sm-12 col-xs-12">
                        <select tabindex = '14' name="dc_case_type_id" id="dc_case_type_id" class="form-control input-sm filter_select_dropdown" style="width: 100%">
                            <option value="" title="Select">Select Case Type</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-xs-12">
                <div class="form-group">
                    <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Case No. <span style="color: red">*</span>:</label>
                    <div class="col-md-7 col-sm-12 col-xs-12">
                        <div class="input-group">
                            <input tabindex = '15' id="dc_case_number" name="dc_case_number" maxlength="10" onkeyup="return isNumber(event)" placeholder="Case No."  class="form-control input-sm age_calculate" type="text">
                            <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Related Case Number">
                                <i class="fa fa-question-circle-o"  ></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-xs-12">
                <div class="form-group">
                    <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Year <span style="color: red">*</span>:</label>
                    <div class="col-md-3 col-sm-12 col-xs-12">
                        <div class="input-group">
                            <select tabindex = '16' class="form-control input-sm filter_select_dropdown" id="dc_case_year" name="dc_case_year" style="width: 100%"> 
                                <option value="">Year</option>
                                <?php
                                $end_year = 47;
                                for ($i = 0; $i <= $end_year; $i++) {
                                    $year = (int) date("Y") - $i;
                                    if (url_encryption($data_to_be_populated['year']) == url_encryption($year)) {
                                        $sel = 'selected=selected';
                                    } else {
                                        $sel = '';
                                    }
                                    echo '<option ' . $sel . ' value=' . url_encryption($year) . '>' . $year . '</option>';
                                }
                                ?> 
                            </select>
                        </div>
                    </div>
                </div>                   
            </div>
        </div>

        <div class="col-md-12 col-sm-12 col-xs-12 ">
            <div class="col-sm-4 col-xs-12 col-md-offset-5">
                <div class="form-group">
                    <div class="col-md-offset-3">
                        <input tabindex = '17' type="button" id="search_case_hc" name="search_case_hc" value="Search" class="info btn-sm">
                    </div>
                </div> 
            </div>
        </div>
        <!----START : Show search Ressult---->
        <div id="case_result"></div>
        <!----END : Show search Ressult---->
        <div class="clearfix"></div><br><br>

        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="col-sm-4 col-xs-12">
                <div class="form-group">
                    <label class="control-label col-md-4 col-sm-12 col-xs-12 input-sm">Impugned Order Date :</label>
                    <div class="col-md-8 col-sm-12 col-xs-12">
                        <div class="input-group">
                            <input tabindex = '18' class="form-control has-feedback-left" id="order_date" name="order_date" maxlength="10" placeholder="DD/MM/YYYY"  type="text" style="width: 80%">
                            <select id="order_dates_list" class="form-control input-md" style="width: 10%">
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-xs-12">
                <div class="form-group">
                    <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Impugned Order Challenged <span style="color: red">*</span>:</label>
                    <div class="col-md-7 col-sm-12 col-xs-12">
                        <?php
                        $selectYes = $new_case_details[0]['judgement_challenged'] == '1' ? 'selected=selected' : '';
                        $selectNo = $new_case_details[0]['judgement_challenged'] == '2' ? 'selected=selected' : '';
                        ?>
                        <select tabindex = '19' name="judgement_challenged" id="judgement_challenged" class="form-control input-sm filter_select_dropdown" style="width: 100%" >
                            <option <?php echo $selectYes; ?> value="1">Yes</option>
                            <option <?php echo $selectNo; ?> value="0">No</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-xs-12">
                <div class="form-group">
                    <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Impugned Order Type <span style="color: red">*</span>:</label>
                    <div class="col-md-7 col-sm-12 col-xs-12">
                        <?php
                        $selectInitial = $new_case_details[0]['judgement_type'] == 'F' ? 'selected=selected' : '';
                        $selectInterim = $new_case_details[0]['judgement_type'] == 'I' ? 'selected=selected' : '';
                        ?>
                        <select tabindex = '20' name="judgement_type" id="judgement_type" class="form-control input-sm filter_select_dropdown" style="width: 100%" >
                            <option <?php echo $selectInitial; ?> value="F">Final</option>
                            <option <?php echo $selectInterim; ?> value="I">Interim</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <!-- FIR Details-->
        <br>

        <div class="row">
            <label class="control-label col-xs-12" style="font-size: large; text-align: center">FIR Details</label>
        </div>
        <br>
        <div id="fir_details">
            <div class="col-sm-5 col-xs-12">
                <div class="form-group">
                    <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">State <span style="color: red">*</span>:</label>
                    <div class="col-md-7 col-sm-12 col-xs-12">
                        <select tabindex = '21' name="fir_state" id="fir_state" class="form-control input-sm filter_select_dropdown" style="width: 100%">
                            <option value="" title="Select">Select State</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-sm-5 col-xs-12">
                <div class="form-group">
                    <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">District <span style="color: red">*</span>:</label>
                    <div class="col-md-7 col-sm-12 col-xs-12">
                        <select tabindex = '22' name="fir_district" id="fir_district" class="form-control input-sm filter_select_dropdown" style="width: 100%">
                            <option value="" title="Select">Select District</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
            <div class="col-sm-4 col-xs-12">
                <div class="form-group">
                    <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Police Station <span style="color: red">*</span>:</label>
                    <div class="col-md-7 col-sm-12 col-xs-12">
                        <select tabindex = '23' name="fir_policeStation" id="fir_policeStation" class="form-control input-sm filter_select_dropdown" style="width: 100%">
                            <option value="" title="Select">Select Police Station</option>
                        </select>
                    </div>
                </div>
                If Police Station not in list, please enter Police Station name and Complete FIR number below.
            </div>
            <div class="col-sm-4 col-xs-12">
                <div class="form-group">
                    <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> FIR No. <span style="color: red">*</span>:</label>
                    <div class="col-md-7 col-sm-12 col-xs-12">
                        <div class="input-group">
                            <input tabindex = '24' id="fir_number" name="fir_number" maxlength="10" onkeyup="return isNumber(event)" placeholder="FIR Number"  class="form-control input-sm age_calculate" type="text">
                            <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="FIR Number">
                                <i class="fa fa-question-circle-o"  ></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-xs-12">
                <div class="form-group">
                    <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Year <span style="color: red">*</span>:</label>
                    <div class="col-md-3 col-sm-12 col-xs-12">
                        <div class="input-group">
                            <select tabindex = '25' class="form-control input-sm filter_select_dropdown" id="fir_year" name="fir_year" style="width: 100%">
                                <option value="">Year</option>
                                <?php
                                $end_year = 48;
                                for ($i = 0; $i <= $end_year; $i++) {
                                    $year = (int) date("Y") - $i;
                                    if (url_encryption($data_to_be_populated['year']) == url_encryption($year)) {
                                        $sel = 'selected=selected';
                                    } else {
                                        $sel = '';
                                    }
                                    echo '<option ' . $sel . ' value=' . url_encryption($year) . '>' . $year . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            </div>
            <div class="row">
                <div class="col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Police Station Name <span style="color: red">*</span>:</label>
                        <div class="col-md-7 col-sm-12 col-xs-12">
                            <div class="input-group">
                                <input tabindex = '26' id="police_station_name" name="police_station_name" placeholder="Police Station Name"  class="form-control" type="text">
                                <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Police Station Name">
                                <i class="fa fa-question-circle-o"  ></i>
                            </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"> Complete FIR No. <span style="color: red">*</span>:</label>
                        <div class="col-md-7 col-sm-12 col-xs-12">
                            <div class="input-group">
                                <input tabindex = '27' id="complete_fir_number" name="complete_fir_number" maxlength="15" onkeyup="return isNumber(event)" placeholder="FIR Number"  class="form-control input-sm age_calculate" type="text">
                                <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="FIR Number">
                                <i class="fa fa-question-circle-o"  ></i>
                            </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- FIR Details end  -->
        <div class="form-group">
            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-5">
                <a tabindex = '30' href="<?= base_url('jailPetition/Extra_petitioner') ?>" class="btn btn-primary" type="button">Previous</a>
                <input tabindex = '28' type="submit" class="btn btn-success" id="subcourt_save" value="SAVE">
                <a  tabindex = '29' href="<?= base_url('uploadDocuments') ?>" class="btn btn-primary btnNext" type="button">Next</a>
            </div>
        </div>
        <?php echo form_close();
        ?>
    </div>

</div>
</div>
<?php $this->load->view('jailPetition/subordinate_court_list'); ?>
<script type="text/javascript">

    $(document).ready(function () {

        get_fir_state_list();
        $('#order_dates_list').on('change', function () {
            var selectedText = $("#order_dates_list option:selected").html();
            var dateText = selectedText.split(" ");
            $("#order_date").val(dateText[0]);
            var ordType = dateText[1].split("-");
            $("#judgement_type").val(ordType[0]).change();;
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
        $('#subordinate_court_details').on('submit', function () {
            if ($('#subordinate_court_details').valid()) {
                var form_data = $(this).serialize();
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('jailPetition/Subordinate_court/add_subordinate_court_details'); ?>",
                    data: form_data,
                    async: false,
                    beforeSend: function () {
                        $('#subcourt_save').val('Please wait...');
                        $('#subcourt_save').prop('disabled', true);
                    },
                    success: function (data) {
                        $('#subcourt_save').val('SAVE');
                        $('#subcourt_save').prop('disabled', false);
                        var resArr = data.split('@@@');
                        if (resArr[0] == 1) {
                            $('#msg').show();
                            $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                        } else if (resArr[0] == 2) {
                            $(".form-response").html("<p class='message valid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                            $('#msg').show();
                            location.reload();
                            //window.location.href = resArr[2];
                        } else if (resArr[0] == 3) {
                            $('#msg').show();
                            $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
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

            var court_type = $("input[name=radio_selected_court]:checked").val();

            if (court_type == 1) { //-High Court 

                var hc_court = $('#hc_court option:selected').val();
                var hc_court_bench = $('#hc_court_bench option:selected').val();
                var hc_court_name = $('#hc_court option:selected').text();
                var case_type = $('#case_type_id option:selected').val();
                var case_number = $('#case_number').val();
                var case_year = $('#case_year option:selected').val();
                var cnr = $('#cnr').val();

            } else if (court_type == 3) {//--District court

                var estab_id = $('#establishment option:selected').val();
                case_type = $('#dc_case_type_id option:selected').val();
                case_number = $('#dc_case_number').val();
                case_year = $('#dc_case_year option:selected').val();
                cnr = $('#dc_cnr').val();
            }

            $('#msg').hide();
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('newcase/Ajaxcalls_subordinate_court/search_case_details'); ?>",
                data: {cnr:cnr, selected_court: court_type, high_court_id: hc_court, hc_name: hc_court_name, hc_court_bench: hc_court_bench, estab_id: estab_id, case_type_id: case_type, case_number: case_number, case_year: case_year},
                async: false,
                success: function (data) {

                    var resArr = data.split('@@@');

                    if (resArr[0] == 1) {
                        $('#msg').show();
                        $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                        setTimeout(function () {
                            $(".form-response").hide();
                        }, 2000);
                        $("#case_result").html('');
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
                    $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                }
            });
        });
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

        $('#select2-case_type_id-container').text('Select Case Type');
        $('#case_type_id').val('');

         if (court_as == '1') {

           // $('#supreme_court_info').hide();
            $('#district_court_info').hide();
            $('#high_court_info').show();
            get_high_court_list(court_as);
        } else if (court_as == '3') {

            $('#district_court_info').show();
            $('#high_court_info').hide();
            //$('#supreme_court_info').hide();
            get_state_list();

        }
    }

    function get_high_court_list(court_as) {

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, selected_post_id: court_as},
            url: "<?php echo base_url('newcase/Ajaxcalls_subordinate_court/get_high_court'); ?>",
            success: function (data)
            {
                $('#hc_court').html(data);
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

    $('#move').click(function(){

        window.scrollTo(0,document.body.scrollHeight);

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
</script>