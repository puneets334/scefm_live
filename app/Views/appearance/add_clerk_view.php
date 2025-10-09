<?php
if (!empty($clerkData[0]['city'])) {
    $depcity = $clerkData[0]['city'];
} else {
    $depcity = '';
}if ($clerkData[0]['pincode'] != 0) {
    $deppincode = $clerkData[0]['pincode'];
} else {
    $deppincode = '';
}
?>
<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div id="msg" style="text-align: center;">
                <?php
                if (isset($_SESSION['message']) && !empty($_SESSION['message'])) {
                    echo $_SESSION['message'];
                } unset($_SESSION['message']);
                ?>
            </div> 
            <div class="x_panel">
                <div class="table-wrapper-scroll-y my-custom-scrollbar ">
                    <div class="x_title">
                        <h2><i class="fa fa-plus"></i> Add Clerk</h2>
                        <div class="clearfix"></div>
                    </div>

                    <?php
                    if (empty($clerkData)) {
                        $action = 'clerk/add_Clerk';
                    } else {
                        $action = 'clerk/update_clerk';
                    }

                    $attribute = array('class' => 'form-horizontal form-label-left', 'id' => 'add_clerk_user', 'name' => 'add_clerk_user', 'autocomplete' => 'off');
                    echo form_open($action, $attribute);
                    ?>
                    <div class="x_content">
                        <div class="row">
                            <div class="col-sm-6 col-xs-12">

                                <div class="row">
                                    <div class="col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <input  name="clerk_id" value="<?php echo htmlentities(url_encryption($clerkData[0]['clerk_id']), ENT_QUOTES); ?>"  required class="form-control input-sm" type="hidden">
                                            <label class="control-label col-sm-5 input-sm">Advocate / Clerk Name <span style="color: red">*</span> :</label>
                                            <div class="col-sm-7">
                                                <div class="input-group">
                                                    <input id="clerk_name" name="clerk_name"  value="<?php echo htmlentities($clerkData[0]['first_name'], ENT_QUOTES); ?>"  placeholder="Advocate / Clerk Name"  maxlength="50" required class="form-control input-sm" type="text" tabindex="2">
                                                    <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Incharge Name only accept Charaters and Spaces(eg. ABC XYZ).">
                                                        <i class="fa fa-question-circle-o" ></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label col-sm-5 input-sm"> Mobile <span style="color: red">*</span> :</label>
                                            <div class="col-sm-7">
                                                <div class="input-group">
                                                    <input id="mobile_no" name="mobile_no" value="<?php echo htmlentities($clerkData[0]['moblie_number'], ENT_QUOTES); ?>"   placeholder="Mobile Number" maxlength="10" required class="form-control input-sm" type="text" tabindex="3">
                                                    <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Mobile should be Numeric and accept only 10 digits(eg. 9876543210).">
                                                        <i class="fa fa-question-circle-o" ></i>
                                                    </span>
                                                </div>   
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label col-sm-5 input-sm">Email <span style="color: red">*</span> :</label>
                                            <div class="col-sm-7">
                                                <span class="input-group">
                                                    <input id="email_id" name="email_id" value="<?php echo htmlentities($clerkData[0]['emailid'], ENT_QUOTES); ?>"  placeholder="Email address" required  class="form-control input-sm" type="email" tabindex="4">
                                                    <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Enter Valid Email id(eg. test@gmail.com).">
                                                        <i class="fa fa-question-circle-o" ></i>
                                                    </span>
                                                </span> 
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-sm-6 col-xs-12">
                                <div class="row">
                                    <div class="col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label col-sm-5 input-sm">Address <span style="color: red">*</span> :</label>
                                            <div class="col-sm-7">
                                                <div class="input-group">
                                                    <textarea name="address" id="address" placeholder="H.No.,  Street no,  City " class="form-control input-sm"  tabindex="6"><?php echo htmlentities($clerkData[0]['address1'], ENT_QUOTES); ?></textarea>
                                                    <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Please enter House No, Street No, Sector, Colony and Landmarks.Address can be alphanumeric (<?php echo VALIDATION_PREG_MATCH_MSG; ?>).">
                                                        <i class="fa fa-question-circle-o" ></i>
                                                    </span>
                                                </div>   
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label col-sm-5 input-sm">City <span style="color: red">*</span>:</label>
                                            <div class="col-sm-7">
                                                <span class="input-group">
                                                    <input id="city" name="city" value="<?php echo htmlentities($depcity, ENT_QUOTES); ?>"  placeholder="City" required  class="form-control input-sm" type="text" maxlength="50" tabindex="7">
                                                    <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Enter Valid City.">
                                                        <i class="fa fa-question-circle-o" ></i>
                                                    </span>
                                                </span> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label col-sm-5 input-sm">State <span style="color: red">*</span> :</label>
                                            <div class="col-md-7 col-sm-12 col-xs-12">
                                                <select name="states_id" id="states_id"  class="form-control input-sm filter_select_dropdown" tabindex="8">
                                                    <option value="" title="Select">Select</option>
                                                    <?php
                                                    foreach ($states as $dataRes) {
                                                        foreach ($dataRes->state as $state) {

                                                            if ($clerkData[0]['njdg_st_id'] == $state->state_code) {
                                                                $selected = 'selected';
                                                            } else {
                                                                $selected = '';
                                                            }
                                                            echo '<option ' . $selected . ' value="' . htmlentities(url_encryption($state->state_code . '#$' . $state->state_name), ENT_QUOTES) . '">' . htmlentities(strtoupper($state->state_name), ENT_QUOTES) . '</option>';
                                                        }
                                                    }
                                                    ?>                                         

                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label col-sm-5 input-sm">District <span style="color: red">*</span> :</label>
                                            <div class="col-md-7 col-sm-12 col-xs-12">
                                                <select name="district" id="district"  class="form-control input-sm filter_select_dropdown" tabindex="8">
                                                    <option value="" title="Select">Select</option>
                                                    <?php
                                                    foreach ($district as $dataRes) {
                                                        foreach ($dataRes->district as $district) {
                                                            if ($clerkData[0]['njdg_dist_id'] == $district->dist_code) {
                                                                $sel = 'selected=selected';
                                                            } else {
                                                                $sel = "";
                                                            }
                                                            echo '<option  ' . htmlentities($sel, ENT_QUOTES) . ' value="' . htmlentities(url_encryption($district->dist_code . '#$' . $district->dist_name), ENT_QUOTES) . '">' . htmlentities($district->dist_name, ENT_QUOTES) . '</option>';
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label col-sm-5 input-sm">Pincode :</label>
                                            <div class="col-sm-7">
                                                <span class="input-group">
                                                    <input id="pincode" name="pincode" value="<?php echo htmlentities($deppincode, ENT_QUOTES); ?>"  placeholder="Pincode" required  class="form-control input-sm" type="text" maxlength="6" tabindex="9">
                                                    <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Enter Valid Pincode.">
                                                        <i class="fa fa-question-circle-o" ></i>
                                                    </span>
                                                </span> 
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-xs-12">
                                <div class="row">
                                    <div class="col-sm-4 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label col-sm-6 input-sm">Allow to File Case <span style="color: red">*</span> :</label>
                                            <div class="col-sm-6">
                                                <?php
                                                if ($clerkData[0]['case_flag'] == 't') {
                                                    $casechecked = 'checked';
                                                } else {
                                                    $caseunchecked = 'checked';
                                                }
                                                ?>
                                                <label class="radio-inline"><input type="radio" name="case_file" id="case_file" value="TRUE" <?php echo htmlentities($casechecked, ENT_QUOTES); ?>>Yes</label>
                                                <label class="radio-inline"><input type="radio" name="case_file" id="case_file1" value="FALSE" <?php echo htmlentities($caseunchecked, ENT_QUOTES); ?>>No</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class = "col-sm-4 col-xs-12">
                                        <label class="control-label col-sm-6 input-sm">Allow to File Document <span style="color: red">*</span> :</label>
                                        <div class="col-sm-6">
                                            <?php
                                            if ($clerkData[0]['doc_flag'] == 't') {
                                                $docchecked = 'checked';
                                            } else {
                                                $docunchecked = 'checked';
                                            }
                                            ?>
                                            <label class="radio-inline"><input type="radio" name="doc_file" id="doc_file" value="TRUE" <?php echo htmlentities($docchecked, ENT_QUOTES); ?>>Yes</label>
                                            <label class="radio-inline"><input type="radio" name="doc_file" id="doc_file1" value="FALSE" <?php echo htmlentities($docunchecked, ENT_QUOTES); ?>>No</label>
                                        </div>
                                    </div>



                                    <!--                                <div class="col-sm-4 col-xs-12">
                                                                        <label class="control-label col-sm-6 input-sm">Allow to Submit Efiling <span style="color: red">*</span> :</label>
                                                                        <div class="col-sm-6">
                                    <?php
//                                        if ($clerkData[0]['efiling_flag'] == 't') {
//                                            $depchecked = 'checked';
//                                        } else {
//                                            $nochecked = 'checked';
//                                        }
                                    ?>
                                                                            <label class="radio-inline"><input type="radio" name="efile_add" id="dep_add" value="TRUE" <?php //echo htmlentities($depchecked, ENT_QUOTES);      ?>>Yes</label>
                                                                            <label class="radio-inline"><input type="radio" name="efile_add" id="dep_add1" value="FALSE" <?php //echo htmlentities($nochecked, ENT_QUOTES);      ?>>No</label>
                                                                        </div>
                                    
                                                                    </div>-->
                                </div>
                            </div>
                        </div>
                        <div class="ln_solid"></div>
                        <div class="form-group">
                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-4">
                                <button onclick="location.href = '<?php echo base_url('Clerk'); ?>'" class="btn btn-primary" type="reset">Cancel</button>
                                <?php if ($clerkData[0] == '') { ?>
                                    <input type="submit" name="add_dep_user" value="Add Clerk" class="btn btn-success">
                                <?php } else { ?>
                                    <input type="submit" name="add_dep_user" value="Update Clerk" class="btn btn-success">
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_content">
                                <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                    <thead>
                                        <tr class="success input-sm" role="row" >
                                            <th>#</th>
                                            <th>Full Name</th>
                                            <th>District / State</th>
                                            <th>User ID</th>
                                            <th>Contact Details</th>
                                            <th>Address</th>
                                            <th>Allow to   File Case / File Document</th>
                                            <th>Added ON</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        foreach ($clerk_user_list as $dataRes) {

                                            if ($dataRes['efiling_flag'] == 't') {
                                                $is_efiling = 'Yes';
                                            } else {
                                                $is_efiling = 'No';
                                            }
                                            if ($dataRes['case_flag'] == 't') {
                                                $is_case = 'Yes';
                                            } else {
                                                $is_case = 'No';
                                            }
                                            if ($dataRes['doc_flag'] == 't') {
                                                $is_doc = 'Yes';
                                            } else {
                                                $is_doc = 'No';
                                            }

                                            if (!empty($dataRes['city'])) {
                                                $city = $dataRes['city'];
                                            } else {
                                                $city = '';
                                            }if ($dataRes['njdg_st_name'] != '') {
                                                $state = $dataRes['njdg_st_name'];
                                            } else {
                                                $state = '';
                                            }if ($dataRes['pincode'] != 0) {
                                                $pincode = $dataRes['pincode'];
                                            } else {
                                                $pincode = '';
                                            }
                                            if ($dataRes['njdg_dist_name'] != '') {
                                                $district = $dataRes['njdg_dist_name'] . ', ';
                                            } else {
                                                $district = '';
                                            }

                                            if ($dataRes['is_active'] == 't') {
                                                $action = "<span class='btn btn-danger btn-xs'>Deactivate</span>";
                                                $status_val = 'Deactive';
                                                $display = 'pointer-events: block;';
                                            } else {
                                                $action = "<span class='btn btn-success btn-xs'>Activate</span>";
                                                $status_val = 'Active';
                                                $display = 'pointer-events: none;';
                                            }
                                            ?>

                                            <tr>
                                                <td width="4%"> <?php echo htmlentities($i++, ENT_QUOTES); ?></td> 
                                                <td width="8%"><?php echo htmlentities($dataRes['first_name'], ENT_QUOTES); ?></td> 
                                                <td width="8%"><?php echo htmlentities($district . $state, ENT_QUOTES); ?></td> 
                                                <td width="10%"><?php echo htmlentities($dataRes['userid'], ENT_QUOTES); ?></td>  
                                                <td width="8%"><?php echo htmlentities($dataRes['emailid'], ENT_QUOTES); ?><br><?php echo htmlentities($dataRes['moblie_number'], ENT_QUOTES); ?></td>
                                                <td width="10%"><?php echo htmlentities($dataRes['address1'] . ',' . $city, ENT_QUOTES) . '<br>' . htmlentities($district . $state, ENT_QUOTES) . '<br>' . htmlentities($pincode, ENT_QUOTES); ?></td>
                                                <td width="6%"><?php echo htmlentities($is_case . '/ ' . $is_doc, ENT_QUOTES); ?></td>
                                                <td width="8%"><?php echo date("d/m/Y h:i:s A", strtotime(htmlentities($dataRes['created_datetime'], ENT_QUOTES))); ?></td>  
                                                <td width="12%">
                                                    <a href="<?php echo base_url(); ?>clerk/edit/<?php echo htmlentities(url_encryption(trim($dataRes['clerk_id']), ENT_QUOTES)) ?>"  class="btn btn-warning btn-xs" title="Edit" style="<?php echo $display; ?>"> Edit</a>
                                                    <a href="<?php echo base_url(); ?>clerk/action/<?php echo htmlentities(url_encryption(trim($dataRes['clerk_id']), ENT_QUOTES)) ?>/<?php echo htmlentities(url_encryption($status_val), ENT_QUOTES); ?>" onclick="return confirm('Are you sure you want to do this action ?')"><?php echo $action; ?></a>
                                                </td>   
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
<script>
    $('#states_id').change(function () {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $('#distt_court_list').val('');
        var get_state_id = $(this).val();
        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, state_id: get_state_id},
            url: "<?php echo base_url(); ?>Clerk/get_district_list",
            success: function (data)
            {
                $('#district').html(data);
                $.getJSON("<?php echo base_url() . 'Login/get_csrf_new'; ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            },
            error: function () {
                $.getJSON("<?php echo base_url() . 'Login/get_csrf_new'; ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
    });

</script>