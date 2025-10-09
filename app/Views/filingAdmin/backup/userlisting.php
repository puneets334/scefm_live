<div class="right_col" role="main">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <div class="panel-body">
                    <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                        <thead>
                        <tr>
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
                        <?php
                        $i = 1;
                        if(isset($users) && !empty($users)){
                            foreach ($users as $k=>$v) {
                                $first_name = !empty($v->first_name) ? $v->first_name : '';
                                $emailid = !empty($v->emailid) ? $v->emailid : '';
                                $emp_id = !empty($v->emp_id) ? $v->emp_id : '';
                                $attend = (!empty($v->attend) && $v->attend == 'P') ? 'Present' : 'Absent';
                                $pp_a = (!empty($v->pp_a) && $v->pp_a == 'P') ? 'Party In Person' : 'Advocate';
                                $efiling_type = !empty($v->efiling_type) ? $v->efiling_type : '';
                                $user_id = !empty($v->user_id) ? $v->user_id : '';
                                if(isset($efiling_type) && !empty($efiling_type) && $efiling_type != 'NULL'){
                                    $fileType = explode(',',$efiling_type);
                                    $roleText ='';
                                    foreach ($fileType as $k=>$v){
                                        $roleText .= str_replace('_',' ',strtoupper($v)).'</br>';
                                    }
                                }
                                else if($efiling_type == 'NULL'){
                                    $roleText ='---';
                                }
                                echo '<tr>
                                        <td>'.$i.'</td>
                                        <td>'.strtoupper($first_name).'</td>
                                        <td>'.$emp_id.'</td>
                                        <td>'.strtoupper($roleText).'</td>
                                        <td>'.strtoupper($pp_a).'</td>
                                        <td>'.strtoupper($attend).'</td>
                                        <td><a title="Update Role" href="javaScript:void(0)" class="editRole" data-userid="'.$user_id.'"><i class="fa fa-edit">Update Role</i></a></td>
                                    </tr>';
                                $i++;
                              }
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
</div>
<div class="modal fade" id="editRole" role="dialog" style="margin-left: -267px;">
    <div class="modal-dialog">
        <div class="modal-content" style="width: 885px; height: 300px;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="fa fa-pencil"></span> Update User Role</h4>
            </div>
            <div class="modal-body">
                <div class="panel-body" id="editUserDiv">
                    <!--<form action="#" class="form-horizontal" name="editsciuser" id="editsciuser" autocomplete="off" method="post" accept-charset="utf-8">-->
                        <?php
                        $attribute = array('class' => 'form_horizontal', 'name' => 'editsciuser', 'id' => 'editsciuser','accept-charset'=>'utf-8', 'autocomplete' => 'off');
                        echo form_open(base_url('#'), $attribute);
                        ?>
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="row">
                                    <div class="col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Name</label>
                                            <div class="col-md-7 col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <input type="text"  autocomplete="off" name="emp_name" id="emp_name" placeholder="Name" maxlength="35" class="form-control input-sm"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Filing Type</label>
                                            <div class="col-md-7 col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <select id="filing_type" name="filing_type" class="form-control input-sm" multiple="multiple" required>
                                                        <option value="">Select Filing Type</option>
                                                        <?php
                                                        if(isset($filingType) && !empty($filingType)){
                                                            foreach ($filingType as $k=>$v){
                                                                echo '<option value="'.$v->id.'">'.str_replace('_',' ',strtoupper($v->efiling_type)).'</option>';
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">User Absent/Present</label>
                                            <div class="col-md-7 col-sm-12 col-xs-12">
                                                <div class="form-group">
<!--                                                    <select id="attend" name="attend" class="form-control input-sm" required>-->
<!--                                                        <option value="">Select Present/Absent</option>-->
<!--                                                        <option value="P">Present</option>-->
<!--                                                        <option value="A">Absent</option>-->
<!--                                                    </select>-->

                                                    <label class="switch">
                                                        <input type="checkbox" id="attend" name="attend" required/>
                                                        <span class="slider round"></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="row">
                                    <div class="col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Emp.No. </label>
                                            <div class="col-md-7 col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <input type="text" autocomplete="off" name="empid" id="empid" placeholder="Emp.No." maxlength="15" class="form-control input-sm"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Party In Person / Advocate</label>
                                            <div class="col-md-7 col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <select id="pp_a" name="pp_a" class="form-control input-sm" required>
                                                        <option value="">Select Party In Person / Advocate</option>
                                                        <option value="P">Party In Person</option>
                                                        <option value="A">Advocate</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm"></label>
                                    <div class="col-md-7 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <input type="submit"  name="editUserRole" value="Update User Role" id="editUserRole" class="btn btn-primary"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="userId" id="userId"/>
                    </form>
                </div>
            </div>
<!--            <div class="modal-footer">-->
<!--                <a href="" class="btn btn-default">No</a>-->
<!--                <a href="" class="btn btn-success">Yes</a>-->
<!--            </div>-->
        </div>

    </div>
</div>
<style>
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

    input:checked + .slider {
        background-color: #2196F3;
    }

    input:focus + .slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked + .slider:before {
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
</style>

<script>
    $(document).ready(function () {
        $('#filing_type').multiselect();
        $(document).on('click','.editRole',function(){
            var userId = $(this).attr('data-userid');
            $("#userId").val(userId);
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            if(userId){
                $.ajax({
                    type: "POST",
                    data: {CSRF_TOKEN: CSRF_TOKEN_VALUE,userId:userId },
                    url: "<?php echo base_url('filingAdmin/DefaultController/getEmpDetailsByUserId'); ?>",
                    async:false,
                    cache:false,
                    dataType:'json',
                    success: function(res)
                    {
                        if(res && typeof res == 'string'){
                            res = JSON.parse(res);
                        }
                        if(res.status == 'success') {
                            $("#editRole").modal('show');
                            $("#emp_name").val(res.first_name);
                            $("#emp_name").prop('disabled',true);
                            $("#empid").val(res.emp_id);
                            $("#empid").prop('disabled',true);
                            $("#pp_a").val(res.pp_a);
                            $("#attend").val(res.attend);
                            if(res.attend && res.attend == 'P'){
                                $("#attend").prop('checked',true);
                            }
                            else{
                                $("#attend").prop('checked',false);
                            }
                            $ids = res.file_type_id.split(',');
                            $('#filing_type').val($ids);
                            $('#filing_type').multiselect('rebuild');

                        }
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
        });

        $(document).on('click','#editUserRole',function(e){
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
            if(emp_name == ''){
                alert("Please fill emp. name");
                $("#emp_name").css({'border-color':'red'});
                $("#emp_name").focus();
                validationError = false;
                return false;
            }
            else if(empid == ''){
                alert("Please fill emp.no.");
                $("#empid").css({'border-color':'red'});
                $("#empid").focus();
                validationError = false;
                return false;
            }
            else if(!empid.match(numRegExp)){
                alert("Please fill emp.no. as numeric.");
                $("#empid").css({'border-color':'red'});
                $("#empid").focus();
                $("#empid").val('');
                validationError = false;
                return false;
            }
            else if(pp_a == ''){
                alert("Please select party in person/advocate.");
                $("#pp_a").css({'border-color':'red'});
                $("#pp_a").focus();
                validationError = false;
                return false;
            }
            else if(attend == ''){
                alert("Please select present/absent.");
                $("#attend").css({'border-color':'red'});
                $("#attend").focus();
                validationError = false;
                return false;
            }
            if((empid) && (validationError) && (pp_a)){
                $.ajax({
                    type: "POST",
                    data: {CSRF_TOKEN: CSRF_TOKEN_VALUE,userId:userId,emp_name:emp_name,empid:empid,filing_type:filing_type,pp_a:pp_a,attend:attend },
                    url: "<?php echo base_url('filingAdmin/DefaultController/updateUserRole'); ?>",
                    async:false,
                    cache:false,
                    dataType:'json',
                    success: function(res)
                    {
                        if(res && typeof res == 'string'){
                            res = JSON.parse(res);
                        }
                        if(res.status == 'error'){
                            alert(res.message);
                            $("#"+res.fieldId).focus();
                            return false;
                        }
                        else  if(res.status == 'success'){
                            alert(res.message);
                            window.location.reload();
                        }
                        // console.log(res);
                        // return false;
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
        });

    });
</script>