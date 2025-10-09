@extends('layout.app')
@section('content')
<style>
    .orcls {
        display: flex;
        align-items: center;
        width: 40px;
        height: 40px;
        margin-top: 20px;
        font-weight: bold;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="dashboard-section dashboard-tiles-area"></div>
            <div class="dashboard-section">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="dash-card">
                            <div class="row">
                                <div class="col-lg-12 col-xs-12">
                                    <div class="" id="msg">
                                        <?php 
                                        $session = session();
                                        if ($session->getFlashdata('msg')) {
                                            echo '<div class="alert alert-success">'.$session->getFlashdata('msg').'</div>';
                                        }
                                        if ($session->getFlashdata('error')) {
                                            echo '<div class="alert alert-danger">'.$session->getFlashdata('error').'</div>';
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            {{-- Page Title Start --}}
                            <div class="title-sec">
                                <h5 class="unerline-title"> Metadata Comparison Report </h5>
                                <a href="javascript:void(0)" onclick="window.history.back()" class="quick-btn pull-right"><span class="mdi mdi-chevron-double-left"></span>Back</a>
                            </div>
                            {{-- Page Title End --}}
                            {{-- Main Start --}}
                            <div class="right_col" role="main">
                                <?php
                                $attribute = array('class' => 'form-horizontal', 'name' => 'search', 'id' => 'search', 'autocomplete' => 'off' , 'enctype' => 'multipart/form-data');
                                echo form_open(base_url('adminReport/UploadedPdfJsonComparison/search'), $attribute);
                                ?>
                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                        <div class="form-group">
                                            <label>E-file Uploaded On</label>
                                            <?php if(isset($from_date)) { ?>
                                                <input type="text" onfocus="document.getElementById('efileno').value = ''" value="<?php echo $from_date; ?>" readonly name="from_date" id="from_date" class="form-control cus-form-ctrl">
                                            <?php } else{ ?>
                                                <input type="text" onfocus="document.getElementById('efileno').value = ''" readonly name="from_date" id="from_date" class="form-control cus-form-ctrl">
                                            <?php } ?>
                                            <span id="error_from_date"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-sm-1 col-xs-1 orcls"> OR </div>
                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                        <div class="form-group">
                                            <label>E-file No</label>
                                            <?php if(isset($efileno)) { ?>
                                                <input type="text" onfocus="document.getElementById('from_date').value = ''" value="<?php echo $efileno; ?>" name="efileno" id="efileno" class="form-control cus-form-ctrl" onkeyup="this.value = this.value.replace(/[^a-zA-Z0-9_-]/g, '')">
                                            <?php } else{ ?>
                                                <input type="text" onfocus="document.getElementById('from_date').value = ''" name="efileno" id="efileno" class="form-control cus-form-ctrl" onkeyup="this.value = this.value.replace(/[^a-zA-Z0-9_-]/g, '')">
                                            <?php } ?>
                                            <input type="hidden" name="view_type" value="json">
                                            <span id="error_from_date"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-3">
                                        <label>&nbsp;</label>
                                        <div class="form-group">
                                            <button type="submit" class="quick-btn Download1">Search</button>
                                            <div id="loader_div" class="loader_div" style="display: none;"></div>
                                        </div>
                                    </div>
                                <?php echo form_close(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container-fluid">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="dashboard-section">
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="dash-card" id="tableData">
                                    <table id="datatable-responsive" class="table table-striped table-border custom-table" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th scope="col">E-file No</th>
                                                <th scope="col">Registration ID</th>
                                                <th scope="col">File Name</th>
                                                <th scope="col">Uploaded On</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if(isset($case_details) && !empty($case_details)) {
                                                foreach ($case_details as $case_detail) {
                                                    // pr($case_detail); 
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $case_detail->efiling_no; ?></td>
                                                        <td><?php echo $case_detail->registration_id; ?></td>
                                                        <td><?php echo $case_detail->file_name; ?></td>
                                                        <td><?php echo $case_detail->uploaded_on; ?></td>
                                                        <td>
                                                            <?php
                                                            if(isJSON($case_detail->iitm_api_json) && isJSON($case_detail->efiling_json) && isJSON($case_detail->icmis_json) && !empty($case_detail->iitm_api_json) && !empty($case_detail->efiling_json) && !empty($case_detail->icmis_json)) { ?>
                                                                <a class="quick-btn" href="<?php echo base_url('adminReport/UploadedPdfJsonComparison/compare?registration_id='.$case_detail->registration_id);?>&view_type=table" target="_blank">Compare as table view</a>
                                                            <?php } ?>
                                                            <?php
                                                            if(isJSON($case_detail->iitm_api_json) && isJSON($case_detail->efiling_json) && isJSON($case_detail->icmis_json) && !empty($case_detail->iitm_api_json) && !empty($case_detail->efiling_json) && !empty($case_detail->icmis_json)) { ?>
                                                                <a class="quick-btn gray-btn" href="<?php echo base_url('adminReport/UploadedPdfJsonComparison/compare?registration_id='.$case_detail->registration_id);?>&view_type=json" target="_blank">Compare as json</a>
                                                            <?php } ?>
                                                        </td>
                                                    </tr>
                                                    <?php 
                                                }
                                            } else{
                                                ?>
                                                <tr>
                                                    <td colspan="5">No records found.</td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
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
    $(document).ready(function() {
        $('#from_date').datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd",
            maxDate: new Date
        });
    });
</script>
@endpush