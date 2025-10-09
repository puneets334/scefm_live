<?php
$attribute = array('class' => 'form-horizontal', 'id' => 'save_searched_case', 'name' => 'save_searched_case', 'autocomplete' => 'off');
//echo form_open('case/search/save_searched_case_result', $attribute);

unset($_SESSION['parties_list']);
$diary_no = $searched_case_details->diary_no;
$diary_year = $searched_case_details->diary_year;
$order_date = $searched_case_details->ord_dt;
$cause_title = strtoupper($searched_case_details->cause_title);
$reg_no_display = strtoupper($searched_case_details->reg_no_display);
$active_reg_year = $searched_case_details->active_reg_year;
$c_status = strtoupper($searched_case_details->c_status);
$case_nature=strtoupper($searched_case_details->case_grp);
$pno = $searched_case_details->pno;
$rno = $searched_case_details->rno;
$advocates=$searched_case_details->advocates;
$_SESSION['parties_list'] = $searched_case_details->parties;
$listing_date=$listing_details->next_dt;
$advocate_allowed=0;
$mentioned_for_date='';
$current_date=date('Y-m-d');
if(isset($diary_no) && !empty($diary_no)){
?>
<hr>
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
                <?php echo_data($c_status == 'D'?'DISPOSED':'PENDING'); ?>
            </div>
        </div>
    </div>
    <?php
}
else{
    echo '<p style="font-size:14px;" class="text-danger text-center"><strong>No Records Found !</strong></p>';
}
?>
<script type="text/javascript">
</script>