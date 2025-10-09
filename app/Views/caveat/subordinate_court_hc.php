<div class="panel panel-default">
    <h4 style="text-align: center;color: #31B0D5">Subordinate Court Information </h4>
    <div class="panel-body">
        <div class="row">
            <div class="col-sm-12 col-xs-12 col-md-12"> 

                <div class="form-group">
                    <label class="control-label col-md-1 col-sm-12 col-xs-12 input-sm col-md-offset-4" style="text-align: right;">Select Court <span style="color: red">*</span> :</label>
                    <div class="col-md-3 col-sm-12 col-xs-12">
                        <select name="court_type" id="court_type" onchange="show_form_hc(this.value)" class="form-control input-sm filter_select_dropdown" style="width: 100%">
                            <option value="<?php echo_data(url_encryption('1$$subCourtType')) ?>" title="Select">Subordinate Court</option>
                            <option value="<?php echo_data(url_encryption('2$$subCourtType')) ?>" title="Select">Quasi Judicial</option>
                            <option value="<?php echo_data(url_encryption('3$$subCourtType')) ?>" title="Select">High Court</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="clearfix"></div>
        <div id="subordinate_info_frm">
            <?php
            include 'subordinate_info_form.php' ;
            ?>
            </div>
        <div id="quasi_judicial_info_frm" style="display: none;">
            <?php include 'subordinate_quasi_jug_info_form.php'; ?> 
        </div>
        <div id="hc_info_frm" style="display: none;">
            <?php include 'subordinate_hc_info_form.php'; ?> 
        </div>
        <?php
        if (isset($subordinate_court_details) && !empty($subordinate_court_details)) {
            include 'subordinate_listing_view.php';
        }
        ?>

    </div>

</div>
<script type="text/javascript">


</script>