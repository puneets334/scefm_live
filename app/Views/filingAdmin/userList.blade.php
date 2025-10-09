@extends('layout.app')
@section('content')
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/bootstrap.min.css" rel="stylesheet">  
<link href="<?= base_url() ?>assets/css/bootstrap-multiselect.css" rel="stylesheet">

<style>
    .add-new-area {
        display: none !important;
    }
    .form-switch {
        padding-left: 2.8em !important;
    }
</style>
<div class="container-fluid">
    <div id="loader-wrapper" style="display: none;">
        <div id="loader"></div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="dashboard-section dashboard-tiles-area"></div>
            <div class="dashboard-section">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="dash-card">
                            <div class="title-sec">
                                <h5 class="unerline-title">User List </h5>
                                <!-- <a href="javascript:void(0)" class="quick-btn pull-right mb-3" onclick="window.history.back()"><span class="mdi mdi-chevron-double-left"></span>Back</a> -->
                                <a href="javascript:void(0)" onclick="window.history.back()" class="quick-btn pull-right"><span class="mdi mdi-chevron-double-left"></span>Back</a>
                            </div>
                            <div class="table-sec">
                                <div class="table-responsive">
                                    <table class="table table-striped custom-table" id="datatable-responsive">
                                        <thead>
                                            <tr class="success input-sm" role="row">
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Emp ID</th>
                                                <th>Assigned File Type</th>
                                                <th>User Type</th>
                                                <th>Present/Absent</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (count($users) > 0)
                                                @foreach ($users as $k => $v)
                                                    @php
                                                    $roleText =$first_name=$emailid=$emp_id='';
                                                    $first_name = !empty($v->first_name) ? $v->first_name : '';
                                                    $emailid = !empty($v->emailid) ? $v->emailid : '';
                                                    $emp_id = !empty($v->emp_id) ? $v->emp_id : '';
                                                    $attend = (!empty($v->attend) && $v->attend == 'P') ? 'Present' : 'Absent';
                                                    $pp_a = (!empty($v->pp_a) && $v->pp_a == 'P') ? 'Party In Person' : 'Advocate';
                                                    $efiling_type = !empty($v->efiling_type) ? $v->efiling_type : '';
                                                    $user_id = !empty($v->id) ? $v->id : '';
                                                    if (isset($efiling_type) && $efiling_type != 'NULL') {
                                                        $fileType = explode(',', $efiling_type);
                                                        $roleText = '';
                                                        foreach ($fileType as $key => $value) {
                                                            $roleText .= str_replace('_', ' ', strtoupper($value)) . '</br>';
                                                        }
                                                    } elseif ($efiling_type == 'NULL') {
                                                        $roleText = '---';
                                                    }
                                                    @endphp
                                                    <tr>
                                                        <td data-key="#">{{ $loop->iteration }}</td>
                                                        <td data-key="Name">{{ $first_name }}</td>
                                                        <td data-key="Emp ID">{{ $emp_id }}</td>
                                                        <td data-key="Assigned File Type">{!! strtoupper($roleText) !!}</td>
                                                        <td data-key="User Type">{{ strtoupper($pp_a) }}</td>
                                                        <td data-key="Present/Absent">{{ strtoupper($attend) }}</td>
                                                        <td data-key="Action"><a title="Update Role" href="javaScript:void(0)" class="editRole" data-userid="{{ $user_id }}"><i class="fa fa-pencil" aria-hidden="true"></i></a></td>
                                                    </tr>
                                                @endforeach
                                            @endif
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
<!-- Edit MODAL OPEN     -->
<div class="modal common-modal fade" id="editRole" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h5 class="modal-title">Update User Role</h5>
                <div class="panel-body" id="editUserDiv">
                    <?php
                    $attribute = array('class' => 'form_horizontal', 'name' => 'editsciuser', 'id' => 'editsciuser', 'accept-charset' => 'utf-8', 'autocomplete' => 'off');
                    echo form_open(base_url('#'), $attribute);
                    ?>
                        <!-- <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12"> -->
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="" class="form-label">Name</label>
                                    <input type="text" autocomplete="off" name="emp_name" id="emp_name" placeholder="Name" maxlength="35" class="form-control cus-form-ctrl" />
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="" class="form-label">Emp.No. </label>
                                    <input type="text" autocomplete="off" name="empid" id="empid" placeholder="Emp.No." maxlength="15" class="form-control cus-form-ctrl" />
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="mb-3 lo">
                                    <label for="" class="form-label col-12 ps-0">Filing Type</label>
                                    <select id="filing_type" name="filing_type" class="input-sm form-control cus-form-ctrl" multiple="multiple" required>
                                        <!-- <option value="">Select Filing Type</option> -->
                                        <?php
                                        if (isset($filingType) && !empty($filingType)) {
                                            foreach ($filingType as $k => $v) {
                                                echo '<option value="' . $v->id . '">' . str_replace('_', ' ', strtoupper($v->efiling_type)) . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="" class="form-label">Party In Person / Advocate</label>
                                    <select id="pp_a" name="pp_a" class="form-select cus-form-ctrl" required>
                                        <option value="">Select Party In Person / Advocate</option>
                                        <option value="P">Party In Person</option>
                                        <option value="A">Advocate</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="mb-3">
                                    <label for="" class="form-label">User Absent/Present</label>
                                    <div class="form-check form-switch ">
                                        <input class="form-check-input" type="checkbox" id="attend" name="attend" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="center-buttons">
                            <input type="submit" name="editUserRole" value="Update User Role" id="editUserRole" class="btn quick-btn" />
                        </div>
                        <input type="hidden" name="userId" id="userId" />
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<style>
    .yellow {
        color: #f0ad4e;
    }
    .orange {
        color: #FF7F50;
    }
    .dark_blue {
        color: #0040ff;
    }
    p:hover {
        background-color: #EDEDED !important;
    }
    .text_position {
        padding-left: 60%;
    }
    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }
    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }
    input:checked+.slider {
        background-color: #2196F3;
    }
    input:focus+.slider {
        box-shadow: 0 0 1px #2196F3;
    }
    input:checked+.slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }
    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }
    .slider.round:before {
        border-radius: 50%;
    }
    .first-th-left th:first-child, .first-th-left td:first-child {
        padding-left: 15px !important;
        text-align: left !important
    }
    span.select2-container{
        width: 100% !important;
    }
</style>
<script src="<?=base_url()?>assets/js/bootstrap.min.js"></script>

<script src="<?= base_url() . 'assets/newAdmin/' ?>js/jquery351.min.js"></script> 
<script src="<?= base_url() . 'assets' ?>/js/select2.min.js"></script>
<script src="<?= base_url() . 'assets' ?>/js/bootstrap-multiselect.min.js"></script>
<script src="<?= base_url() . 'assets' ?>/js/jquery.validate.js"></script>
 

@push('script')
<script>
    $(document).ready(function() {
        $("#datatable-responsive").DataTable({
            "ordering": false,
            // dom: 'Bfrtip',
        });
        // $('#filing_type').multiselect();
        $(document).on('click', '.editRole', function() {
            var userId = $(this).attr('data-userid');
            $("#userId").val(userId);
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            if (userId) {
                $.ajax({
                    type: "POST",
                    data: {
                        CSRF_TOKEN: CSRF_TOKEN_VALUE,
                        userId: userId
                    },
                    url: "<?= base_url('filingAdmin/getEmpDetailsByUserId') ?>",
                    async: false,
                    cache: false,
                    dataType: 'json',
                    success: function(res) {
                        if (res && typeof res == 'string') {
                            res = JSON.parse(res);
                        }
                        if (res.status == 'success') {
                            $("#editRole").modal('show');
                            $("#emp_name").val(res.first_name);
                            $("#emp_name").prop('disabled', true);
                            $("#empid").val(res.emp_id);
                            $("#empid").prop('disabled', true);
                            $("#pp_a").val(res.pp_a);
                            $("#attend").val(res.attend);
                            if (res.attend && res.attend == 'P') {
                                $("#attend").prop('checked', true);
                            } else {
                                $("#attend").prop('checked', false);
                            }
                            var $ids = res.file_type_id.split(',');
                            // $('#filing_type').val($ids);
                            $('#filing_type').val($ids).trigger('change');
                            // $('#filing_type').select2();
                            var roleText = '';
                            ids.forEach(function(id) {
                                var text = $('#filing_type option[value="' + id + '"]').text();
                                roleText += text.replace(/_/g, ' ').toUpperCase() + '<br>';
                            });
                        }
                        $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        });
                    },
                    error: function() {
                        $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        });
                    }
                });
            }
        });
        $(document).on('click', '#editUserRole', function(e) {
            e.preventDefault();
            var userId = $("#userId").val();
            var emp_name = $.trim($("#emp_name").val());
            var empid = $("#empid").val();
            var filing_type = $("#filing_type").val();
            var pp_a = $("#pp_a").val();
            var attend = $("#attend").is(':checked');
            attend = (attend == true) ? 'P' : 'A';
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            var numRegExp = /^\d+$/;
            var validationError = true;
            if (emp_name == '') {
                alert("Please fill emp. name");
                $("#emp_name").css({
                    'border-color': 'red'
                });
                $("#emp_name").focus();
                validationError = false;
                return false;
            } else if (empid == '') {
                alert("Please fill emp.no.");
                $("#empid").css({
                    'border-color': 'red'
                });
                $("#empid").focus();
                validationError = false;
                return false;
            } else if (!empid.match(numRegExp)) {
                alert("Please fill emp.no. as numeric.");
                $("#empid").css({
                    'border-color': 'red'
                });
                $("#empid").focus();
                $("#empid").val('');
                validationError = false;
                return false;
            } else if (pp_a == '') {
                alert("Please select party in person/advocate.");
                $("#pp_a").css({
                    'border-color': 'red'
                });
                $("#pp_a").focus();
                validationError = false;
                return false;
            } else if (attend == '') {
                alert("Please select present/absent.");
                $("#attend").css({
                    'border-color': 'red'
                });
                $("#attend").focus();
                validationError = false;
                return false;
            }
            if ((empid) && (validationError) && (pp_a)) {
                $.ajax({
                    type: "POST",
                    data: {
                        CSRF_TOKEN: CSRF_TOKEN_VALUE,
                        userId: userId,
                        emp_name: emp_name,
                        empid: empid,
                        filing_type: filing_type,
                        pp_a: pp_a,
                        attend: attend
                    },
                    url: "<?= base_url('filingAdmin/updateUserRole') ?>",
                    async: false,
                    cache: false,
                    dataType: 'json',
                    success: function(res) {
                        if (res && typeof res == 'string') {
                            res = JSON.parse(res);
                        }
                        if (res.status == 'error') {
                            alert(res.message);
                            $("#" + res.fieldId).focus();
                            return false;
                        } else if (res.status == 'success') {
                            alert(res.message);
                            window.location.reload();
                        }
                        // return false;
                        $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        });
                    },
                    error: function() {
                        $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        });
                    }
                });
            }
        });
    });
    function ActionToAllUserCount() {
        var AllUserCount = document.querySelector('#AllUserCount').checked;
        window.location.href = "<?= base_url('adminDashboard/DefaultController/ActionToAllUserCount?AllUserCount=') ?>" + AllUserCount;
    }
</script>
@endpush