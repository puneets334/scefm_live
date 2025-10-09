
<?php
$segment = service('uri');
?>
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
<!-- 2-12-24 added start  -->
<!-- <div class="mainPanel ">
    <div class="panelInner">
        <div class="middleContent">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 sm-12 col-md-12 col-lg-12 middleContent-left">
                        <div class="center-content-inner comn-innercontent"> -->
<!-- 2-12-24 added start  -->


@include('IA.ia_breadcrumb')

@if($segment->getSegment(2) == 'caseDetails')

    @include('IA.case_details_view')
@elseif ($segment->getSegment(2) == 'appearing_for')
  
    @include('IA.act_sections_view')
@elseif ($segment->getSegment(1) == 'uploadDocuments' || $segment->getSegment(1) == 'documentIndex')

    @include('documentIndex.documentIndex_view')
@elseif ($segment->getSegment(1) == 'documentIndex') 

    @include('documentIndex.documentIndex_view')
@elseif ($segment->getSegment(1) == 'affirmation') 
  
    @include('miscellaneous_docs.courtFee_view')
@elseif ($segment->getSegment(2) == 'courtFee') 
    @if(getSessionData('efiling_details')['doc_govt_filing']==1)
        @include('newcase.courtFee_view')
    @else
        @include('miscellaneous_docs.courtFee_view')
    @endif
@elseif ($segment->getSegment(1) == 'view')

    @include('miscellaneous_docs.misc_docs_preview')
@endif

</div>
</div>
</div>
</div>             
</div>
</div>
</div>


<script>
            $('#disapprove_me').click(function() {

var temp = $('.disapprovedText').text();
temp = $.trim(temp);
var efiling_type_id = '<?php echo $_SESSION['efiling_details']['ref_m_efiled_type_id']; ?>';


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

</script>