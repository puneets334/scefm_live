<link rel="shortcut icon" href="<?= base_url().'assets/newDesign/images/logo.png' ?>" type="image/png" />
@extends('layout.app')
@section('content')
<div class="mainPanel ">
    <div class="panelInner"> 
    @if(getSessionData('login')['ref_m_usertype_id'] == USER_ADMIN)
        @include('layout.header') 
    @endif 
        <div class="middleContent">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 sm-12 col-md-12 col-lg-12 middleContent-left">

                        <div class="center-content-inner comn-innercontent">
                            <?php
                            $segment = service('uri'); 
                         $lastSegment = $segment->getSegment($segment->getTotalSegments()); 
						   ?>
                            @if (
                                !empty(getSessionData('efiling_details')['ref_m_efiled_type_id']) &&
                                    getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_CAVEAT)
                                @include('caveat.caveat_breadcrumb')
                            @else
								@include('caveat.caveat_breadcrumb')
                            @endif

                            @if ($lastSegment == 'caveat')
                            @include('caveat.caveator_form')
                           @elseif ($lastSegment == 'caveatee')
                               @include('caveat.caveatee_form')
                           @elseif ($lastSegment == 'subordinate_court')
                              @include('caveat.caveat_subordinate_court_view')
                           @elseif ($lastSegment == 'uploadDocuments' || $lastSegment == 'documentIndex')
                               @include('documentIndex.documentIndex_view')
                           @elseif ($lastSegment == 'documentIndex')
                               @include('documentIndex.documentIndex_view')
                           @elseif ($lastSegment == 'courtFee')
                               @include('newcase.courtFee_view')
                           @elseif ($lastSegment == 'view')
                            @include('caveat.caveat_preview')
                           @endif						
                     
                        </div>
                    </div>
                </div>
            </div> 
        </div>
    </div>
</div>
@endsection 
@push('script')
<script>
    $('#pet_dob').datepicker({
        onSelect: function (value) {
            var parts = value.split("/");
            var day = parts[0] && parseInt(parts[0], 10);
            var month = parts[1] && parseInt(parts[1], 10);
            var year = parts[2] && parseInt(parts[2], 10);
            var str = month + '/' + day + '/' + year;
            var today = new Date(),
                    dob = new Date(str),
                    age = new Date(today - dob).getFullYear() - 1970;
            $('#pet_age').val(age);
        },
        changeMonth: true,
        changeYear: true,
        yearRange: "-100:+0",  
        dateFormat: "dd/mm/yy",  
        defaultDate: "-40y", 
        maxDate: today  
    });

    $('#res_dob').datepicker({
        onSelect: function (value) {
            var parts = value.split("/");
            var day = parts[0] && parseInt(parts[0], 10);
            var month = parts[1] && parseInt(parts[1], 10);
            var year = parts[2] && parseInt(parts[2], 10);
            var str = month + '/' + day + '/' + year;
            var today = new Date(),
                    dob = new Date(str),
                    age = new Date(today - dob).getFullYear() - 1970;
            $('#res_age').val(age);
        },
        changeMonth: true,
        changeYear: true,
        yearRange: "-100:-1",
        dateFormat: "dd/mm/yy",
        defaultDate: '-40y'
    });
    $('#cause_date').datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "dd/mm/yy",
        maxDate: new Date

    });
    $('#filing_date').datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "dd/mm/yy",
        maxDate: new Date
    });
    $('#decision_date,#cc_applied_date,#cc_ready_date,#offence_date,#charge_sheet_date,#accident_date,#fir_file_date,\n\
       #trial_decision_date,#trial_cc_applied_date,#trial_cc_ready_date,#hc_decision_date,#hc_cc_applied_date,\n\
       #hc_cc_ready_date,#case_order_dt').datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "dd/mm/yy",
        maxDate: new Date
    });

    function compareDate(element) {

        if (element == 'cc_applied_date') {
            var decision_dt = $('#decision_date').val();
            var cc_applied_dt = $('#cc_applied_date').val();
            $('#cc_ready_date').val('');
            if (decision_dt == '' || decision_dt == 'NULL') {
                alert('First select decision date');
                $('#cc_applied_date').val('');

            } else {
                var decision_dt = $.datepicker.parseDate('dd/mm/yy', decision_dt);
                var cc_applied_dt = $.datepicker.parseDate('dd/mm/yy', cc_applied_dt);
                if (decision_dt > cc_applied_dt)
                {
                    alert('CC Applied Date is greater than OR Equal to Date of Decision');
                    $('#cc_applied_date').val('');
                }
            }
        } else if (element == 'cc_ready_date') {
            var decision_dt = $('#decision_date').val();
            var cc_applied_dt = $('#cc_applied_date').val();
            var cc_ready_dt = $('#cc_ready_date').val();
            if (decision_dt == '' || decision_dt == 'NULL') {
                alert('First select decision date');
                $('#cc_ready_date').val('');
            } else if (cc_applied_dt == '' || cc_applied_dt == 'NULL') {
                alert('Select CC Applied Date');
                $('#cc_ready_date').val('');
            } else {
                var cc_applied_dt = $.datepicker.parseDate('dd/mm/yy', cc_applied_dt);
                var cc_ready_dt = $.datepicker.parseDate('dd/mm/yy', cc_ready_dt);
                if (cc_applied_dt > cc_ready_dt) {
                    alert('CC Ready date is greater than OR Equal to CC Applied Date');
                    $('#cc_ready_date').val('');
                }
            }
        } else if (element == 'trial_cc_applied_date') {
            var trial_decision_dt = $('#trial_decision_date').val();
            var trial_cc_applied_dt = $('#trial_cc_applied_date').val();
            $('#trial_cc_ready_date').val('');
            if (trial_decision_dt == '' || trial_decision_dt == 'NULL') {
                alert('First select decision date');
                $('#trial_cc_applied_date').val('');
            } else {
                var trial_decision_dt = $.datepicker.parseDate('dd/mm/yy', trial_decision_dt);
                var trial_cc_applied_dt = $.datepicker.parseDate('dd/mm/yy', trial_cc_applied_dt);
                if (trial_decision_dt > trial_cc_applied_dt) {
                    alert('CC Applied Date is greater than OR Equal to Date of Decision');
                    $('#trial_cc_applied_date').val('');
                }
            }
        } else if (element == 'trial_cc_ready_date') {
            var trial_decision_dt = $('#trial_decision_date').val();
            var trial_cc_applied_dt = $('#trial_cc_applied_date').val();
            var trial_cc_ready_dt = $('#trial_cc_ready_date').val();
            if (trial_decision_dt == '' || trial_decision_dt == 'NULL') {
                alert('First select decision date');
                $('#trial_cc_ready_date').val('');
            } else if (trial_cc_applied_dt == '' || trial_cc_applied_dt == 'NULL') {
                alert('Select CC Applied Date');
                $('#trial_cc_ready_date').val('');
            } else {
                var trial_cc_applied_dt = $.datepicker.parseDate('dd/mm/yy', trial_cc_applied_dt);
                var trial_cc_ready_dt = $.datepicker.parseDate('dd/mm/yy', trial_cc_ready_dt);
                if (trial_cc_applied_dt > trial_cc_ready_dt) {
                    alert('CC Ready Date is greater than OR Equal to CC Applied date');
                    $('#trial_cc_ready_date').val('');
                }
            }
        } else if (element == 'hc_cc_applied_date') {
            var hc_decision_dt = $('#hc_decision_date').val();
            var hc_cc_applied_dt = $('#hc_cc_applied_date').val();
            $('#hc_cc_ready_date').val('');
            if (hc_decision_dt == '' || hc_decision_dt == 'NULL') {
                alert('First select decision date');
                $('#hc_cc_applied_date').val('');
            } else {
                var hc_decision_dt = $.datepicker.parseDate('dd/mm/yy', hc_decision_dt);
                var hc_cc_applied_dt = $.datepicker.parseDate('dd/mm/yy', hc_cc_applied_dt);
                if (hc_decision_dt > hc_cc_applied_dt) {
                    alert('CC Applied Date is greater than OR Equal to Date of Decision');
                    $('#hc_cc_applied_date').val('');
                }
            }
        } else if (element == 'hc_cc_ready_date') {
            var hc_decision_dt = $('#hc_decision_date').val();
            var hc_cc_applied_dt = $('#hc_cc_applied_date').val();
            var hc_cc_ready_dt = $('#hc_cc_ready_date').val();
            if (hc_decision_dt == '' || hc_decision_dt == 'NULL') {
                alert('First select decision date');
                $('#hc_cc_ready_date').val('');
            } else if (hc_cc_applied_dt == '' || hc_cc_applied_dt == 'NULL') {
                alert('Select CC Applied Date');
                $('#hc_cc_ready_date').val('');
            } else {
                var hc_cc_applied_dt = $.datepicker.parseDate('dd/mm/yy', hc_cc_applied_dt);
                var hc_cc_ready_dt = $.datepicker.parseDate('dd/mm/yy', hc_cc_ready_dt);
                if (hc_cc_applied_dt > hc_cc_ready_dt) {
                    alert('CC Ready Date is greater than OR Equal to CC Applied date');
                    $('#hc_cc_ready_date').val('');
                }
            }
        }
    }
    document.getElementById("copyButton").addEventListener("click", function() {
        copyToClipboardMsg(document.getElementById("copyTarget_EfilingNumber"), "copyButton");
    });
    // document.getElementById("copyButton2").addEventListener("click", function() {
    //     copyToClipboardMsg(document.getElementById("copyTarget2"), "copyButton");
    // });
    document.getElementById("pasteTarget").addEventListener("mousedown", function() {
        this.value = "";
    });
    function copyToClipboardMsg(elem, msgElem) {
        var EfilingNumber = document.getElementById('copyTarget_EfilingNumber').innerHTML;
        var succeed = copyToClipboard(elem);
        var msg;
        if (!succeed) {
            msg = "Copy not supported or blocked.  Press Ctrl+c to copy."
        } else {
            //msg = 'E-Filing Number: ' +EfilingNumber+' Copied.';
            msg = 'Copied';
        }
        if (typeof msgElem === "string") {
            msgElem = document.getElementById(msgElem);
        }
        msgElem.innerHTML = msg;
        /* setTimeout(function() {
            msgElem.innerHTML = "";
        }, 5000);*/
    }
<?php if (!empty($efiling_civil_data[0]['pet_legal_heir']) && $efiling_civil_data[0]['pet_legal_heir'] == 'Y') { ?>
        $(document).ready(function () {
            document.getElementById('address_label_name').innerHTML = 'Address :';
        });
<?php } elseif (!empty($efiling_civil_data[0]['res_legal_heir']) && $efiling_civil_data[0]['res_legal_heir'] == 'Y') { ?>
        $(document).ready(function () {
            document.getElementById('address_label_name_res').innerHTML = 'Address :';
        });
<?php } else { ?>
        $(document).ready(function () {
         //   document.getElementById('address_label_name').innerHTML = 'Address <span style="color: red">*</span> :';
          //  document.getElementById('address_label_name_res').innerHTML = 'Address <span style="color: red">*</span> :';
        });
<?php } ?>

</script>

@endpush
