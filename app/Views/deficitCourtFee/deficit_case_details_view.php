
<div class="panel panel-default panel-body">
    <h4 style="text-align: center;color: #31B0D5">Case Details </h4>
    <div class="row">
        <div class="col-md-12">

            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label col-md-4 text-right" for="diary_no">Diary No. :</label>
                    <div class="col-md-8">
                        <p> <?php echo_data($case_details[0]['diary_no'] . ' / ' . $case_details[0]['diary_year']); ?></p>
                    </div>
                </div> 
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label col-md-4 text-right" for="reg_num">Registration No.:</label>
                    <div class="col-md-8">
                        <p> <?php echo_data($case_details[0]['reg_no_display']); ?></p>
                    </div>
                </div> 
            </div>

            <div class="clearfix"></div>

            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label col-md-4 text-right"  for="cause_title">Cause Title :</label>
                    <div class="col-md-8">
                        <p> <?php echo_data($case_details[0]['cause_title']); ?></p>
                    </div>
                </div> 
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label col-md-4 text-right" for="case_status">Case Status:</label>
                    <div class="col-md-8">
                        <p> <?php echo $case_details[0]['c_status'] == 'D' ? 'Disposed' : 'Pending'; ?></p>
                    </div>
                </div> 
            </div>

            <div class="clearfix"></div>

            </br>


        </div>
    </div>
</div>


