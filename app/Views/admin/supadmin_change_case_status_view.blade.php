@extends('layout.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12 sm-12 col-md-12 col-lg-12  ">
            <div class="center-content-inner comn-innercontent">
                <div class="dashboard-section">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="dash-card">
                                <div class="title-sec">
                                    <h5 class="unerline-title"><i class="fa fa-recycle"></i> Change Case Status </h5>
                                    <!-- <a href="javascript:void(0)" class="quick-btn pull-right mb-3" onclick="window.history.back()"><span class="mdi mdi-chevron-double-left"></span>Back</a>                 -->
                                    <a href="javascript:void(0)" onclick="window.history.back()" class="quick-btn pull-right"><span class="mdi mdi-chevron-double-left"></span>Back</a>
                                </div>
                                <?php 
                                $session = session();
                                    if ($session->getFlashdata('MSG')) {
                                        echo '<div class="alert alert-danger">'.$session->getFlashdata('MSG').'</div>';
                                    }
                                ?>
                 <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-sec">
                            <span style="color: red;"> NOTE : Stage of a eFiling Number can be reverted to one step back only when the said number is active on any of the following stages mentioned here in Admin login.</br>
                            Stages are </span><span>: <strong><u> For Compliance</u>, <u>Pay Deficit Fee</u>, <u>Transfer to CIS</u>, <u> Idle/Unprocessed</u>, <u>Mark as error</u> .</strong></span>

                            <br><br><br>
                            <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                               <div class="mb-3">
                                <?php
                                $attribute = array('class' => 'form-horizontal form-label-left', 'name' => 'search_case', 'id' => 'search_case', 'autocomplete' => 'off');
                                echo form_open('admin/supadmin/search_case_status', $attribute);
                                ?>
                                <label for="usr" class="form-label">Enter Efiling Number</label>
                                <div class="input-group cust-inline-back">
                                    <input type="text" class="form-control cus-form-ctrl" id="efil_no" name="efil_no" required placeholder="Enter Efiling Number" maxlength="20" style="width: auto !important;">                                
                                    <span class="input-group-text input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Enter an existing Efiling Number.">
                                        <i class="fa fa-question-circle-o" ></i>
                                    </span>
                                </div> 
                                <!-- <input type="submit" name="submit" value="Search" class="quick-btn"> -->
                                <div class="start-buttons">
                                    <button type="submit" name="submit" value="Search" class="quick-btn">Search</button>
                                </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>  
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection