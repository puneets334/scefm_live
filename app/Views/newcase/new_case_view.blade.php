<link href="<?= base_url() . 'assets/newAdmin/' ?>css/black-theme.css" rel="stylesheet">
    <link href="<?= base_url() . 'assets/newAdmin/' ?>css/responsive.css" rel="stylesheet">
<div class="mainPanel ">
    <div class="panelInner">
        <div class="middleContent">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 sm-12 col-md-12 col-lg-12 middleContent-left">
                        <div class="center-content-inner comn-innercontent">
                            <?php
                            $segment = service('uri');
                            ?>
                            @if (
                                !empty(getSessionData('efiling_details')['ref_m_efiled_type_id']) &&
                                    getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_CAVEAT)
                                @include('caveat.caveat_breadcrumb')
                            @else
                                @include('newcase.new_case_breadcrumb')
                            @endif
                            @if ($segment->getSegment(2) == 'caseDetails')
                                @include('newcase.case_details_view')
                            @elseif ($segment->getSegment(2) == 'petitioner')
                                @include('newcase.petitioner_view')
                            @elseif ($segment->getSegment(2) == 'respondent')
                                @include('newcase.respondent_view')
                            @elseif ($segment->getSegment(2) == 'extra_party')
                                @include('newcase.extra_party_view')
                            @elseif ($segment->getSegment(2) == 'lr_party')
                                @include('newcase.lr_party_view')
                            @elseif ($segment->getSegment(2) == 'actSections')
                                @include('newcase.act_sections_view')
                            @elseif ($segment->getSegment(1) == 'uploadDocuments' || $segment->getSegment(1) == 'documentIndex')
                                @include('documentIndex.documentIndex_view')
                            @elseif ($segment->getSegment(1) == 'documentIndex')
                                @include('documentIndex.documentIndex_view')
                            @elseif ($segment->getSegment(2) == 'subordinate_court')
                                @include('newcase.subordinateCourt_view')
                            @elseif ($segment->getSegment(1) == 'affirmation')
                                //$this->load->view('affirmation/affirmation_view')
                            @elseif ($segment->getSegment(2) == 'courtFee')
                                @if (getSessionData('efiling_details')['is_govt_filing'] == 1)
                                    @include('newcase.courtFee_govt_view')
                                @else
                                    @include('newcase.courtFee_view')
                                @endif
                            @elseif ($segment->getSegment(2) == 'view')
                                @include('newcase.new_case_preview')
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?=base_url();?>assets/js/sweetalert.min.js"></script>
    <link rel="stylesheet" href="<?=base_url();?>assets/css/sweetalert.css">
    <script src="<?= base_url() . 'assets/newAdmin/' ?>js/general.js"></script>
@push('script')
    <script>
        $('#party_dob').datepicker({
            onSelect: function(value) {
                var parts = value.split("/");
                var day = parts[0] && parseInt(parts[0], 10);
                var month = parts[1] && parseInt(parts[1], 10);
                var year = parts[2] && parseInt(parts[2], 10);
                var str = month + '/' + day + '/' + year;
                var today = new Date(),
                    dob = new Date(str),
                    age = new Date(today - dob).getFullYear() - 1970;
                $('#party_age').val(age);
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
        $('#filing_date,#order_date').datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "dd/mm/yy",
            maxDate: new Date
        });
        $('#decision_date,#cc_applied_date,#cc_ready_date,#offence_date,#charge_sheet_date,#accident_date,#fir_file_date,\n\
           #trial_decision_date,#trial_cc_applied_date,#trial_cc_ready_date,#hc_decision_date,#hc_cc_applied_date,\n\
           #hc_cc_ready_date,#app_case_order_dt,#trial_case_order_dt').datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "dd/mm/yy",
            maxDate: new Date
        });
    </script>
    <script type="text/javascript">
        $("#cde").click(function () {
            $.ajax({
                url: "<?php echo base_url('newcase/finalSubmit/valid_cde'); ?>",
                success: function (data) {
                    var dataas = data.split('?');
                    var ct = dataas[0];
                    var dataarr = dataas[1].slice(1).split(',');
                    if ((dataarr[0] != 2) && (dataarr[0] != 3) && (dataarr[0] != 6) && (dataarr[0] != 7)) {
                        window.location.href = "<?php echo base_url('newcase/finalSubmit/forcde')?>";
                    }
                    if ((dataarr[0]) == 2) {
                        alert("Petitioner details are mandatory to fill. Redirecting to page !!");
                        window.location.href = "<?php echo base_url('newcase/petitioner')?>";
                    }
    
                    if ((dataarr[0]) == 3) {
                        alert("Respondent details are mandatory to fill. Redirecting to page !!");
                        window.location.href = "<?php echo base_url('newcase/respondent')?>";
                    }
    
                    if (((dataarr[0]) == 7) && (ct != 5) && (ct != 6) && (ct != 7) && (ct != 8)) {
                        alert("Subordinate_courts details are mandatory to fill. Redirecting to page !!");
                        window.location.href = "<?php echo base_url('newcase/subordinate_court')?>";
                    }
    
                },
                error: function () {
                    $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                }
    
    
            });
            return false;
        });
        $("#efilaor").click(function () {
            $.ajax({
                url: "<?php echo base_url('newcase/AutoDiary/valid_efil'); ?>", // enabled this for auto diary generation
                success: function (data) {
                    var dataas = data.split('?');
                    var ct = dataas[0];
                    var dataarr = dataas[1].slice(1).split(',');
                    if ((dataarr[0] != 2) && (dataarr[0] != 3) && (dataarr[0] != 7) && (dataarr[0]) != 8 && (dataarr[0]) != 10) {
                         alert("all completed");
                        window.location.href = "<?php echo base_url('newcase/AutoDiary')?>"; //ENABLED THIS FOR AUTO DIARY
                    }
                    if ((dataarr[0]) == 2) {
                        alert("Petitioner details are mandatory to fill. Redirecting to page !!");
                        window.location.href = "<?php echo base_url('newcase/petitioner')?>";
                    }
                    if ((dataarr[0]) == 3) {
                        alert("Respondent details are mandatory to fill. Redirecting to page !!");
                        window.location.href = "<?php echo base_url('newcase/respondent')?>";
                    }
                    if (((dataarr[0]) == 7) && (ct != 5) && (ct != 6) && (ct != 7) && (ct != 8)) {
                        alert("Subordinate_courts details are mandatory to fill. Redirecting to page !!");
                        window.location.href = "<?php echo base_url('newcase/subordinate_court')?>";
                    }
                    if (((dataarr[0]) == 8)) {
                        alert("Documents are not uploaded. Redirecting to page !!");
                        window.location.href = "<?php echo base_url('newcase/uploadDocuments')?>";
                    }
                    if (((dataarr[0]) == 10)) {
                        alert("Court Fees not paid. Redirecting to page !!");
                        window.location.href = "<?php echo base_url('newcase/courtFee')?>";
                    }
                },
                error: function () {
                    $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                }
            });
            return false;
        });
        $("#efilpip").click(function () {
            $.ajax({
                url: "<?php echo base_url('newcase/finalSubmit/valid_efil'); ?>",
                success: function (data) {
                    var dataas = data.split('?');
                    var ct = dataas[0];
                    var dataarr = dataas[1].slice(1).split(',');
                    if ((dataarr[0] != 2) && (dataarr[0] != 3) && (dataarr[0] != 7) && (dataarr[0]) != 8 && (dataarr[0]) != 10) {
                        alert("all completed");
                        window.location.href = "<?php echo base_url('newcase/finalSubmit')?>";
                    }
                    if ((dataarr[0]) == 2) {
                        alert("Petitioner details are mandatory to fill. Redirecting to page !!");
                        window.location.href = "<?php echo base_url('newcase/petitioner')?>";
                    }
                    if ((dataarr[0]) == 3) {
                        alert("Respondent details are mandatory to fill. Redirecting to page !!");
                        window.location.href = "<?php echo base_url('newcase/respondent')?>";
                    }
                    if (((dataarr[0]) == 7) && (ct != 5) && (ct != 6) && (ct != 7) && (ct != 8)) {
                        alert("Subordinate_courts details are mandatory to fill. Redirecting to page !!");
                        window.location.href = "<?php echo base_url('newcase/subordinate_court')?>";
                    }
                    if (((dataarr[0]) == 8)) {
                        alert("Documents are not uploaded. Redirecting to page !!");
                        window.location.href = "<?php echo base_url('newcase/uploadDocuments')?>";
                    }
                    if (((dataarr[0]) == 10)) {
                        alert("Court Fees not paid. Redirecting to page !!");
                        window.location.href = "<?php echo base_url('newcase/courtFee')?>";
                    }
                },
                error: function () {
                    $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                }
            });
            return false;
        });
        $("#jail").click(function () {
            $.ajax({
                url: "<?php echo base_url('jailPetition/FinalSubmit/validate'); ?>",
                success: function (data) {
                    var dataarr = data.slice(1).split(',');
                    if ((dataarr[0] != 1) && (dataarr[0] != 3)) {
                        window.location.href = "<?php echo base_url('jailPetition/FinalSubmit')?>";
                    }
                    if ((dataarr[0]) == 1) {
                        alert("Basic Case details are mandatory to fill. Redirecting to page !!");
                        window.location.href = "<?php echo base_url('jailPetition/BasicDetails')?>";
                    }
                    if ((dataarr[0]) == 3) {
                        alert("Earlier Court Details are mandatory to fill. Redirecting to page !!");
                        window.location.href = "<?php echo base_url('jailPetition/Subordinate_court')?>";
                    }
                },
                error: function () {
                    $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                }
            });
            return false;
        });
        function ActionToTrash(trash_type) {
        event.preventDefault();
        var trash_type =trash_type;
        var url="";
        if (trash_type==''){
            swal("Cancelled", "Your imaginary file is safe :)", "error");
            return false;
        }else if (trash_type=='UAT'){
            url="<?php echo base_url('userActions/trash'); ?>";
        }else if (trash_type=='SLT'){
            url="<?php echo base_url('stage_list/trash'); ?>";
        }else if (trash_type=='EAT'){
            url="<?php echo base_url('userActions/trash'); ?>";
        }else{
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
            function(isConfirm){
                if (isConfirm) {  // submitting the form when user press yes
                    var link = document.createElement("a");
                    link.href = url;
                    link.target = "_self";
                    link.click();
                    swal({ title: "Deleted!",text: "E-Filing has been deleted.",type: "success",timer: 2000 });

                } else {
                    //swal({title: "Cancelled",text: "Your imaginary file is safe.",type: "error",timer: 1300});
                }

            });
    }
    </script>
@endpush
<!-- <script type="text/javascript">
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
            success: function () {
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
</script> -->

<script type="text/javascript">
    $(document).ready(function () {
        var oTable = $("#datatable-defects").dataTable();
        $("#checkAll").click(function() {
            var isChecked = $(this).prop("checked");
            oTable.$("input[type=\'checkbox\']").prop("checked", isChecked);
            var setCuredDefectAllValues = [];
            if (isChecked) { 
                oTable.$("input.setCuredDefectAll:checked").each(function () { 
                    setCuredDefectAllValues.push($(this).val()); 
                }); 
            }
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                type: 'POST',
                url: '<?=base_url("documentIndex/Ajaxcalls/markCuredDefect")?>',
                data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, objectionId: setCuredDefectAllValues, val:true,type:'All'},
                success: function () {
                    if(setCuredDefectAllValues.length===0){
                        $(".setCuredDefectAllToggle").removeClass("curemarked");
                    }else{
                        $(".setCuredDefectAllToggle").addClass("curemarked");
                    }
                }
            });

        });
    });
    function setCuredDefect(id) {
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var value = $("#" + id).is(":checked"); 
        $.ajax({
            type: 'POST',
            url: '<?= base_url("documentIndex/Ajaxcalls/markCuredDefect")?>',
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, objectionId: id, val:value,type:'One'},
            success: function () {
            $("#row"+id).toggleClass("curemarked")
            
            },
            error: function () {alert("failed");}
        });
    }
</script>
