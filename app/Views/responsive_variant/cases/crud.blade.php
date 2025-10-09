@extends('layout.advocateApp')
@section('content')
    <link href="<?= base_url() . 'assets' ?>/css/select2.min.css" rel="stylesheet">
    <style>
        th {
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
                                <div class="form-response m-2" id="msg" style="background-color: green; color: #fff; text-align: center;"></div>
                                <div uk-filter="target: .js-filter">
                                    <table id='myTable' class="uk-table uktable-justify uktable-striped uk-table-hover uk-table-divider">
                                        <thead>
                                            <tr class="uk-text-bold">
                                                <th class="uk-text-bold d-print-none">#</th>
                                                <th class="uk-text-bold">Diary No.</th>
                                                <th class="uk-text-bold">Cause Title</th>
                                                <th class="uk-text-bold">Registration No.</th>
                                                <th class="uk-text-bold">Status</th>
                                                <th class="uk-text-bold">No. of Petitioner</th>
                                                <th class="uk-text-bold">No. of Respondent</th>
                                            </tr>
                                        </thead>
                                        <tbody class="js-filter ">
                                            <?php
                                            if(isset($diaryDetails->diary_no) && !empty($diaryDetails->diary_no)) {
                                                $diaryNo = '';
                                                if(isset($diaryDetails->diary_no) && !empty($diaryDetails->diary_no) && isset($diaryDetails->diary_year) && !empty($diaryDetails->diary_year)){
                                                    $diaryNo = $diaryDetails->diary_no .'/' .$diaryDetails->diary_year;
                                                }
                                                $cause_title = !empty($diaryDetails->cause_title) ? $diaryDetails->cause_title : '';
                                                $reg_no_display = !empty($diaryDetails->reg_no_display) ? $diaryDetails->reg_no_display : '';
                                                $c_status = !empty($diaryDetails->c_status) ? 'Pending' : '';
                                                $pno = !empty($diaryDetails->pno) ? $diaryDetails->pno : '';
                                                $rno = !empty($diaryDetails->rno) ? $diaryDetails->rno : '';
                                                echo '<tr>
                                                    <td data-key="#">1</td>
                                                    <td data-key="Diary No.">'.$diaryNo.'</td>
                                                    <td data-key="Cause Title">'.$cause_title.'</td>
                                                    <td data-key="Registration No.">'.$reg_no_display.'</td>
                                                    <td data-key="Status">'.$c_status.'</td>
                                                    <td data-key="No.Of Petitioner">'.$pno.'</td>
                                                    <td data-key="No.Of Respondent">'.$rno.'</td>
                                                </tr>';
                                            } else {
                                                echo '<tr>
                                                    <td colspan="7" class="text-center">No Record Founds!</td>
                                                </tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-xs-12" id="loader_div"></div>
                                        <div class="col-md-12 col-sm-12 col-xs-12" style="margin-bottom: 26px;">
                                            <div class="row m-2">
                                                <div class="col-md-3">
                                                    <label class="control-label col-md-12 col-sm-12 col-xs-12">
                                                        <?php
                                                        if(isset($sr_advocate) && !empty($sr_advocate)){
                                                            echo '<input type="radio" name="assignedType" id="sr_advocate" class="typeUser" value="sr_advocate" checked/>';
                                                        } else {
                                                            echo '<input type="radio" name="assignedType" id="sr_advocate" class="typeUser" value="sr_advocate"/>';
                                                        }
                                                        ?>
                                                        Sr. Advocate
                                                    </label>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="control-label col-md-12 col-sm-12 col-xs-12">
                                                        <?php
                                                        if(isset($arguing_counsel) && !empty($arguing_counsel)) {
                                                            echo '<input type="radio" name="assignedType" id="arguing_counsel" class="typeUser" value="arguing_counsel" checked/>';
                                                        } else {
                                                            echo '<input type="radio" name="assignedType" id="arguing_counsel" class="typeUser" value="arguing_counsel"/>';
                                                        }
                                                        ?>
                                                        Advocate
                                                    </label>
                                                </div>

                                        <?php 
                                        if(isset($srAdvocate) && !empty($srAdvocate) && isset($diaryDetails->diary_no) && !empty($diaryDetails->diary_no)) {
                                            echo '<div class="col-md-3">
                                                <select class="form-control cus-form-ctrl col-sm-8 filter_select_dropdown"  id="srAdvocate" name="srAdvocate" >
                                                    <option value="">Select Sr. Advocate</option>';
                                                    $sradvocatelistIds = array_column($srAdvocate, 'id');
                                                    if(isset($srAdvocate) && !empty($srAdvocate)) {
                                                        foreach ($srAdvocate as $k=>$v) {
                                                            $id = !empty($v['id']) ? url_encryption($v['id']) : '';
                                                            $advocate_name = '';
                                                            $honorific_name ='';
                                                            $first_name ='';
                                                            $middle_name='';
                                                            $last_name ='';
                                                            $honorific_name = !empty($v['honorific_name']) ? $v['honorific_name'] : '';
                                                            $first_name = !empty($v['first_name']) ? $v['first_name'] : NULL;
                                                            $middle_name = !empty($v['middle_name']) ? $v['middle_name'] : NULL;
                                                            $last_name = !empty($v['last_name']) ? $v['last_name'] : NULL;
                                                            $advocate_name .= $honorific_name . ' ';
                                                            if (isset($first_name) && !empty($first_name) && isset($middle_name) && !empty($middle_name) && isset($last_name) && !empty($last_name)) {
                                                                $advocate_name .= $first_name . ' ' . $middle_name . ' ' . $last_name;
                                                            } else if (isset($first_name) && !empty($first_name) && isset($middle_name) && !empty($middle_name)) {
                                                                $advocate_name .= $first_name . ' ' . $middle_name;
                                                            } else if (isset($first_name) && !empty($first_name) && isset($last_name) && !empty($last_name)) {
                                                                $advocate_name .= $first_name . ' ' . $last_name;
                                                            } else {
                                                                $advocate_name .= $first_name;
                                                            }
                                                            echo '<option value="' . $id . '">' . $advocate_name . '</option>';
                                                        }
                                                    }
                                                echo '</select>                
                                            </div>
                                            <div class="col-md-3">
                                                <button type="button" id="addAdvocate" name="addAdvocate" class="btn btn-primary">Add Sr. Advocate</button>
                                            </div>
                                            <div class="row">
                                                <div class="panel panel-default" style="margin-top: 56px;">
                                                    <div class="panel-body">
                                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                                            <table id="sr_table" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
                                                                <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>Name</th>
                                                                        <th>Engaged Date</th>
                                                                        <th>Disengaged Date</th>
                                                                        <th>Status</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>';
                                                                    if (isset($srAssignedList) && !empty($srAssignedList)) {
                                                                        $i = 1;
                                                                        foreach ($srAssignedList as $k => $v) {
                                                                            $id = !empty($v['id']) ? url_encryption($v['id']) : '';
                                                                            $advocatename = '';
                                                                            $honorificname = !empty($v['honorific_name']) ? $v['honorific_name'] : '';
                                                                            $firstname = !empty($v['first_name']) ? $v['first_name'] : NULL;
                                                                            $middlename = !empty($v['middle_name']) ? $v['middle_name'] : NULL;
                                                                            $lastname = !empty($v['last_name']) ? $v['last_name'] : NULL;
                                                                            $advocatename .= $honorificname . ' ';
                                                                            if (isset($firstname) && !empty($firstname) && isset($middlename) && !empty($middlename) && isset($lastname) && !empty($lastname)) {
                                                                                $advocatename .= $firstname . ' ' . $middlename . ' ' . $lastname;
                                                                            } elseif (isset($firstname) && !empty($firstname) && isset($middlename) && !empty($middlename)) {
                                                                                $advocatename .= $firstname . ' ' . $middlename;
                                                                            } elseif (isset($firstname) && !empty($firstname) && isset($lastname) && !empty($lastname)) {
                                                                                $advocatename .= $firstname . ' ' . $lastname;
                                                                            } else {
                                                                                $advocatename .= $firstname;
                                                                            }
                                                                            $user_type = !empty($v['user_type']) ? $v['user_type'] : NULL;
                                                                            $createdAt = !empty($v['createdAt']) ? date('d-m-Y H:i:s', strtotime($v['createdAt'])) : '';
                                                                            $updatedAt = !empty($v['updatedAt']) ? date('d-m-Y H:i:s', strtotime($v['updatedAt'])) : '';
                                                                            $is_active = (!empty($v['is_active']) && $v['is_active'] == 't') ? 'Engage' : 'Disengage';
                                                                            $action = (!empty($v['is_active']) && $v['is_active'] == 't') ? 'Disengage' : 'Re-engage';
                                                                            $engageType = (!empty($v['is_active']) && $v['is_active'] == 't') ? '2' : '1';
                                                                            echo '<tr>
                                                                                <td data-key="#">' . $i . '</td>
                                                                                <td data-key="Name">' . $advocatename . '</td>
                                                                                <td data-key="Engaged Date">' . $createdAt . '</td>
                                                                                <td data-key="Disengaged Date">' . $updatedAt . '</td>
                                                                                <td data-key="Status">' . $is_active . '</td>
                                                                                <td data-key="Action"><a class="deleteSrAdvocateData" data-user_type="' . $user_type . '" data-engagetype="' . $engageType . '" data-sradvocateid="' . $id . '" href="javaScript:void(0);" title="' . $action . '">' . $action . '</a></td>
                                                                            </tr>';
                                                                            $i++;
                                                                        }
                                                                    } else{
                                                                        echo '<tr>
                                                                            <td colspan="6" class="text-center">No Record Founds!</td>
                                                                        </tr>';
                                                                    }
                                                                echo '</tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>            
                                            </div>';
                                        } else if(isset($diaryDetails->diary_no) && !empty($diaryDetails->diary_no)) {
                                            echo '<div class="col-md-3">
                                                <select class="form-control cus-form-ctrl col-sm-8 filter_select_dropdown"  id="srAdvocate" name="srAdvocate" >
                                                    <option value="">Select Advocate</option>';
                                                    $sradvocatelistIds = array_column($arguingCounsel, 'id');
                                                    if(isset($arguingCounsel) && !empty($arguingCounsel)){
                                                        foreach ($arguingCounsel as $k=>$v) {
                                                            $id = !empty($v['id']) ? url_encryption($v['id']) : '';
                                                            $first_name ='';
                                                            $first_name = !empty($v['first_name']) ? $v['first_name'] : NULL;
                                                            echo '<option value="' . $id . '">' . ucfirst($first_name) . '</option>';
                                                        }
                                                    }
                                                echo '</select>
                                            </div>
                                            <div class="col-md-3">
                                                <button type="button" id="addAdvocate" name="addAdvocate" class="btn btn-primary">Add Advocate</button>
                                            </div>
                                            <div class="row">
                                                <div class="panel panel-default" style="margin-top: 56px;">
                                                    <div class="panel-body">
                                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                                            <table id="sr_table" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
                                                                <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>Name</th>
                                                                        <th>Engaged Date</th>
                                                                        <th>Disengaged Date</th>
                                                                        <th>Status</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>';
                                                                    if(isset($arguingCounselList) && !empty($arguingCounselList)) {
                                                                        $i=1;
                                                                        foreach($arguingCounselList as $k=>$v) {
                                                                            $id =!empty($v['id']) ? url_encryption($v['id']) : '';
                                                                            $user_type = !empty($v['user_type']) ? $v['user_type'] : NULL;
                                                                            $firstname = !empty($v['first_name']) ? $v['first_name'] : NULL;
                                                                            $createdAt = !empty($v['createdAt']) ? date('d-m-Y H:i:s',strtotime($v['createdAt'])) : '';
                                                                            $updatedAt = !empty($v['updatedAt']) ? date('d-m-Y H:i:s',strtotime($v['updatedAt'])) : '';
                                                                            $is_active = (!empty($v['is_active']) && $v['is_active'] == 't') ? 'Engage' : 'Disengage';
                                                                            $action = (!empty($v['is_active']) && $v['is_active'] == 't') ? 'Disengage' : 'Re-engage';
                                                                            $engageType = (!empty($v['is_active']) && $v['is_active'] == 't') ? '2' : '1';
                                                                            echo '<tr>
                                                                                <td data-key="#">'.$i.'</td>
                                                                                <td data-key="Name">'.ucfirst($firstname).'</td>
                                                                                <td data-key="Engaged Date">'.$createdAt.'</td>
                                                                                <td data-key="Disengaged Date">'.$updatedAt.'</td>
                                                                                <td data-key="Status">'.$is_active.'</td>
                                                                                <td data-key="Action"><a class="deleteSrAdvocateData" data-user_type="' . $user_type . '" data-engagetype="' . $engageType . '" data-sradvocateid="' . $id . '" href="javaScript:void(0);" title="' . $action . '">' . $action . '</a></td>
                                                                            </tr>';
                                                                            $i++;
                                                                        }
                                                                    } else {
                                                                        echo '<tr>
                                                                            <td colspan="6" class="text-center">No Record Founds!</td>
                                                                        </tr>';
                                                                    }
                                                                echo '</tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>';
                                        }
                                        ?>
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
<script src="<?= base_url() . 'assets' ?>/js/select2.min.js"></script>
<script src="<?= base_url() . 'assets' ?>/js/select2-tab-fix.min.js"></script>
<script>
    $(document).ready(function() {
        $(".filter_select_dropdown").select2().on('select2-focus', function() {
            // debugger;
            $(this).data('select2-closed', true)
        });
        $("#msg").show();
        $(document).on('click','#addAdvocate',function(){
            var advocateId= $("#srAdvocate option:selected").val();
            var userType = $('input[name="assignedType"]:checked').val();
            if(advocateId) {
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                var postData = {advocateId:advocateId,userType:userType};
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('newcase/assignSrAdvocate'); ?>",
                    data: JSON.stringify(postData),
                    dataType:'json',
                    contentType:'application/json',
                    beforeSend: function () {
                        $("#loader_div").html('<img id="loader_img" style="position: fixed;left: 45%;margin-top: 2px;margin-left: -151px;"  src="<?php echo base_url('assets/images/loading-data.gif');?>">');
                    },
                    success: function (res) {
                        if(res.success == 'success') {
                            $("#loader_div").html('');
                            $(".form-response").html("<p class='message' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + res.message + "</p>");
                        } else {
                            $("#loader_div").html('');
                            $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + res.message + "</p>");
                        }
                        setTimeout(function () {
                            window.location.reload();
                        }, 2000);
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
            } else {
                alert("Please select " +userType.replace('_',' '));
                $("#srAdvocate").focus();
                $("#srAdvocate").css({"border":"red"});
                return false;
            }
        });
        $(document).on('click','.deleteSrAdvocateData',function() {
            var srAdvocateId = $(this).attr('data-sradvocateid');
            var engageType = $(this).attr('data-engagetype');
            var userType = $(this).attr('data-user_type');
            var diary_no='<?php echo isset($diaryDetails) ? $diaryDetails->diary_no.$diaryDetails->diary_year : ''; ?>';
            var engageMessage ='';
            if(engageType && engageType == '1') {
                engageMessage = "re-engage ";
            } else if(engageType && engageType == '2') {
                engageMessage = "disengage ";
            }
            var user_type ;
            if(userType) {
                user_type = userType.replace('_',' ');
            }
            if(confirm('Are you sure to '+engageMessage + user_type+' ?')) {
                if(srAdvocateId) {
                    var CSRF_TOKEN = 'CSRF_TOKEN';
                    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                    var postData = {srAdvocateId:srAdvocateId,engageType:engageType,userType:userType,diaryNo:diary_no};
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url('newcase/deleteSrAdvocate'); ?>",
                        data: JSON.stringify(postData),
                        dataType:'json',
                        contentType:'application/json',
                        beforeSend: function () {
                            $("#loader_div").html('<img id="loader_img" style="position: fixed;left: 45%;margin-top: 2px;margin-left: -151px;"  src="<?php echo base_url('assets/images/loading-data.gif');?>">');
                        },
                        success: function (res) {
                            if(res.success == 'success') {
                                $("#loader_div").html('');
                                $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + res.message + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                            } else {
                                $("#loader_div").html('');
                                $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + res.message + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                            }
                            setTimeout(function () {
                                window.location.reload();
                            }, 2000);
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
            }
        });
        $(document).on('click','.typeUser',function() {
            var typeUser = $(this).attr("id");
            var pathName = window.location.pathname;        
            if(typeUser == 'sr_advocate') {            
                if(pathName) {
                    pathNameArr = pathName.split('/');
                    if(pathNameArr[1] && pathNameArr[3]) {
                        window.location = window.location.origin+'/case/advocate/'+pathNameArr[3];
                    }
                }
            } else  if(typeUser == 'arguing_counsel') {
                var pathName = window.location.pathname;        
                if(pathName) {
                    pathNameArr = pathName.split('/');                
                    if(pathNameArr[1] && pathNameArr[3]) {
                        window.location = window.location.origin+'/case/arguingCounsel/'+pathNameArr[3];
                    }
                }
            }
        });
    });
    function hideMessageDiv() {
        document.getElementById('msg').style.display = "none";
    }
</script>
@endpush