<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div id="msg"><?php
                echo $this->session->flashdata('msg');
                if (isset($_SESSION['MSG']) && !empty($_SESSION['MSG'])) {
                    echo $_SESSION['MSG'];
                } unset($_SESSION['MSG']);
                ?></div> 
            <div class="x_panel">
                <div class="x_title">
                    <h2><i class="fa fa-plus"></i> Add Admin Contact ( For Public View )</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="row">

                        <?php
                        $attribute = array('class' => 'form-horizontal form-label-left', 'id' => 'add_contact', 'name' => 'add_contact', 'autocomplete' => 'off');
                        echo form_open('admin/admin/add_contact', $attribute);
                        ?>
                        <div class="col-sm-6 col-xs-12">
                            <div class="col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label col-sm-5 input-sm"> </label>
                                    <div class="col-sm-7">
                                        <?php
                                        $setval = url_decryption(set_value("court_type"));

                                        if ($resData[0]->admin_for_type_id == ENTRY_TYPE_FOR_HIGHCOURT || $setval == ENTRY_TYPE_FOR_HIGHCOURT) {
                                            $hc_checked = 'checked';
                                            $show_dc = 'none';
                                            $show_hc = 'block';
                                        } elseif ($resData[0]->admin_for_type_id == ENTRY_TYPE_FOR_ESTABLISHMENT || $setval == ENTRY_TYPE_FOR_ESTABLISHMENT) {
                                            $lc_checked = 'checked';
                                            $show_dc = 'block';
                                            $show_hc = 'none';
                                            $hc_checked = '';
                                        } elseif (ENABLE_FOR_HC && ENABLE_FOR_ESTAB) {
                                            $show_dc = 'none';
                                            $hc_checked = '';
                                        }
                                        ?>
                                        <?php if (ENABLE_FOR_HC) { ?>
                                            <label class="radio-inline"><input type="radio" id="court" name="court_type" <?php echo htmlentities($hc_checked, ENT_QUOTES); ?> value="<?php echo htmlentities(url_encryption(1), ENT_QUOTES); ?>" onchange="displayCourt(1)"  maxlength="1" checked="">HIGH COURT</label>
                                            <?php
                                        } if (ENABLE_FOR_ESTAB) {
                                            if (!ENABLE_FOR_HC && ENABLE_FOR_ESTAB) {
                                                $lc_checked = 'checked';
                                            }
                                            ?>
                                            <label class="radio-inline"><input type="radio" id="lowwer_court" name="court_type" <?php echo htmlentities($lc_checked, ENT_QUOTES); ?> value="<?php echo htmlentities(url_encryption(2), ENT_QUOTES); ?>" onchange="displayCourt(2)"    maxlength="1">  LOWER COURT</label>
                                        <?php } ?>
                                        <?php echo form_error('court_type'); ?>
                                    </div>
                                </div>
                            </div>
                            <?php if (ENABLE_FOR_HC || $resData[0]->admin_for_type_id == ENTRY_TYPE_FOR_HIGHCOURT) { ?>
                                <div id="showHighCourt" style="display:<?php echo $show_hc; ?>">                                                                
                                    <div class="form-group">
                                        <label class="control-label col-sm-5 input-sm">High Court <span style="color: red">*</span> :</label>
                                        <div class="col-sm-7">
                                            <select name="high_court_id" id="high_court_id" class="form-control col-md-7 col-xs-12 input-sm" >
                                                <option value="" title="Select">Select</option>
                                                <?php
                                                if (count($high_court_list)) {
                                                    foreach ($high_court_list as $dataRes) {
                                                        if ($dataRes['id'] == $resData[0]->admin_for_id) {
                                                            $sel = 'selected=selected';
                                                        } else {
                                                            $sel = "";
                                                        }
                                                        $value = $dataRes['id'];
                                                        echo '<option ' . htmlentities($sel, ENT_QUOTES) . ' value="' . htmlentities(url_encryption($value), ENT_QUOTES) . '">' . htmlentities($dataRes['hc_name'], ENT_QUOTES) . '</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                            <?php echo form_error('high_court_id'); ?>
                                        </div>
                                    </div>

                                    <?php
                                    if ($resData[0]->bench_court != '') {
                                        $display_bench = "block";
                                    } else {
                                        $display_bench = "none";
                                    }
                                    ?>

                                    <div class="form-group" id="bench_court" style="display:<?php echo $display_bench; ?>">
                                        <label class="control-label col-sm-5 input-sm" >Bench Court: <span style="color: red">*</span> :</label>
                                        <div class="col-sm-7">
                                            <select name="bench_court_list" id="bench_court_list" class="form-control col-md-7 col-xs-12 input-sm">
                                                <option value="" title="Select">Select</option>
                                                <?php
                                                if (count($benchcourt_list)) {
                                                    foreach ($benchcourt_list as $dataRes) {
                                                        if ($dataRes['id'] == $resData[0]->admin_for_id) {
                                                            $sel = 'selected=selected';
                                                        } else {
                                                            $sel = "";
                                                        }
                                                        $value = $dataRes['id'];
                                                        echo '<option ' . htmlentities($sel, ENT_QUOTES) . ' value="' . htmlentities(url_encryption($value), ENT_QUOTES) . '">' . htmlentities($dataRes['hc_name'], ENT_QUOTES) . '</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                            <?php echo form_error('bench_court_list'); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } if (ENABLE_FOR_ESTAB || $resData[0]->admin_for_type_id == ENTRY_TYPE_FOR_ESTABLISHMENT) { ?>
                                <div id="showLowerCourt" style="display: <?php echo $show_dc; ?>">                                
                                    <div class="form-group">
                                        <label class="control-label col-sm-5 input-sm">State: <span style="color: red">*</span> :</label>
                                        <div class="col-sm-7">
                                            <select name="state_id" id="state_id" class="form-control col-md-7 col-xs-12 input-sm">
                                                <option value="" title="Select">Select</option>
                                                <?php
                                                if (count($state_list)) {
                                                    foreach ($state_list as $dataRes) {
                                                        if ($dataRes['state_id'] == $resData[0]->ref_m_states_id) {
                                                            $sel = 'selected=selected';
                                                        } else {
                                                            $sel = "";
                                                        }
                                                        echo '<option ' . htmlentities($sel, ENT_QUOTES) . ' value="' . htmlentities(url_encryption($dataRes['state_id']), ENT_QUOTES) . '">' . htmlentities($dataRes['state'], ENT_QUOTES) . '</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                            <?php echo form_error('state_id'); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-5 input-sm">District <span style="color: red">*</span> :</label>
                                        <div class="col-sm-7">
                                            <select name="distt_court_list" id="distt_court_list" class="form-control col-md-7 col-xs-12 input-sm required">
                                                <option value="" title="Select">Select</option>
                                                <?php
                                                if (count($districts_list)) {
                                                    foreach ($districts_list as $dataRes) {
                                                        if ($dataRes->id == $resData[0]->ref_m_distt_id) {
                                                            $sel = 'selected=selected';
                                                        } else {
                                                            $sel = "";
                                                        }
                                                        echo '<option ' . htmlentities($sel, ENT_QUOTES) . ' value="' . htmlentities(url_encryption(trim($dataRes->id), ENT_QUOTES)) . '">' . htmlentities($dataRes->dist_name, ENT_QUOTES) . '</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                            <?php echo form_error('distt_court_list'); ?>
                                        </div>
                                    </div>
                                    <?php
                                    $alloted_arr = array();
                                    foreach ($alloted_list as $v) {
                                        $alloted_arr[] = $v['admin_for_id'];
                                    }
                                    ?>
                                    <div class="form-group" id="nodal_chek">
                                        <label class="control-label col-sm-5 input-sm"> Court Establishment <span style="color: red">*</span> :</label>
                                        <div class="col-sm-7">
                                            <?php
                                            if ($resData[0]->ref_m_distt_id != '') {
                                                if (count($eshtablishment_list)) {
                                                    foreach ($eshtablishment_list as $dataActs) {
                                                        $checked = in_array($dataActs->id, $alloted_arr) ? 'checked' : '';
                                                        echo '<input class="" type="checkbox" id="reg_court_complex_id" name="reg_court_complex_id[]" value="' . url_encryption(trim($dataActs->id)) . '" ' . $checked . '/> ' . ucfirst(strtolower(htmlentities($dataActs->estname, ENT_QUOTES))) . '</br>';
                                                    }
                                                }
                                                ?>
                                                <?php
                                            } else {
                                                ?>
                                                <input type="checkbox" id="reg_court_complex_id" name="reg_court_complex_id" value=""/> Select Court Establishment<?php } ?>
                                        </div>
                                        <?php echo form_error('reg_court_complex_id'); ?>
                                    </div>
                                    <span id="show_court_name"></span>
                                </div>   
                            <?php } ?>
                            <div class="form-group">
                                <label class="control-label col-sm-5 input-sm">Email <span style="color: red">*</span> :</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input id="email_id" name="email_id" value="<?php echo htmlentities($resData[0]->email_id, ENT_QUOTES); ?>"  placeholder="Email address"    class="form-control input-sm"  tabindex="6">
                                        <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Enter Valid Email id(eg. test@gmail.com).">
                                            <i class="fa fa-question-circle-o" ></i>
                                        </span>
                                    </div> 
                                    <?php echo form_error('email_id'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ln_solid"></div>
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-4">
                            <button onclick="location.href = '<?php echo base_url(); ?>admin/admin/contact'" class="btn btn-primary" type="reset">Cancel</button>
                            <?php if ($resData[0] == '') { ?>
                                <input type="submit" name="add_user" value="Add Contact" class="btn btn-success">
                            <?php } else { ?>
                                <input type="submit" name="add_user" value="Update Contact" class="btn btn-success">
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <?php echo form_close(); ?>
            </div> </div>
        <!------------Table--------------------->
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_content">
                    <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                        <thead>
                            <tr class="success input-sm" role="row" >
                                <th>#</th>
                                <?php if (ENABLE_FOR_ESTAB) { ?>
                                    <th>District</th>
                                <?php } ?>
                                <th>High Court /Establishment Name</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            foreach ($contact_list as $dataRes) {
                                $estab_details = explode('||||', $dataRes->estab_name);
                                $id = explode('||||', $dataRes->id);
                                $dist_name = explode('@@@', $estab_details[0]);
                                ?>
                                <tr>
                                    <td width="4%"> <?php echo htmlentities($i++, ENT_QUOTES); ?></td>  
                                    <?php if (ENABLE_FOR_ESTAB) { ?>
                                        <td width="10%"><?php echo htmlentities($dist_name[1], ENT_QUOTES) ?></td> 
                                    <?php } ?>
                                    <td width="20%">

                                        <?php
                                        echo '<ul>';
                                        foreach ($estab_details as $v) {
                                            echo '<li>' . str_replace('@@@', ',', $v) . '</li>';
                                        }
                                        echo '</ul>';
                                        ?>
                                    </td> 

                                    <td width="10%"><?php echo htmlentities($dataRes->email_id, ENT_QUOTES); ?></td>  
                                    <td width="5%">
                                        <a href="<?php echo base_url(); ?>admin/admin/edit_contact/<?php echo htmlentities(url_encryption(trim($id[0]), ENT_QUOTES)) ?>"  class="btn btn-warning btn-xs" title="Edit" style="<?php echo $display; ?>"> Edit</a>
                                    </td>  
                                </tr>
                            <?php } ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!------------Table--------------------->
    </div>
</div>
</div>
<script>
    $('#state_id').change(function () {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $('#establishment_list').val('');
        $('#distt_court_list').val('');
        var get_state_id = $(this).val();
        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, state_id: get_state_id},
            url: "<?php echo base_url('Dropdown_list/get_district_list_y'); ?>",
            success: function (data)
            {
                $('#distt_court_list').html(data);
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
    });
</script>
<script>
    $('#distt_court_list').change(function () {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $('#establishment_list').val('');
        var get_distt_id = $(this).val();
        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, get_distt_id: get_distt_id},
            url: "<?php echo base_url(); ?>Dropdown_list/eshtablishment_list_checkbox",
            success: function (data)
            {
                $('#nodal_chek').html(data);
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
    });
</script>
<script>
    $('#establishment_list').change(function () {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var get_establishment_id = $(this).val();
        var est_code;
        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, establishment_id: get_establishment_id},
            url: "<?php echo base_url(); ?>Dropdown_list/get_estab_code",
            success: function (data)
            {
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
    });
</script>

<script>
    $('#high_court_id').change(function () {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var get_state_id = $(this).val();

        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, hc_id: get_state_id},
            url: "<?php echo base_url(); ?>Dropdown_list/get_benchcourt_list",
            success: function (data)
            {
                if (data != '0') {
                    $('#bench_court').css('display', 'block');
                } else {
                    $('#bench_court').css('display', 'none');
                    $('#bench_court_list').val('');
                }
                $('#bench_court_list').html(data);
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

    });

    function get_highcourt(hc_code) {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, high_court_code: hc_code},
            url: "<?php echo base_url(); ?>RemoteDb/connect_high_court_db",
            success: function (data)
            {

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

</script>
<script type="text/javascript">
    function displayCourt(element) {
        if (element == '1') {
            document.getElementById("showLowerCourt").style.display = "none";
            document.getElementById("showHighCourt").style.display = "block";
        } else if (element == '2') {
            document.getElementById("showLowerCourt").style.display = "block";
            document.getElementById("showHighCourt").style.display = "none";
        }
    }
    $(document).ready(function () {
        $('#reg_court_complex_id').change(function () {
            var get_reg_court_complex_id = $(this).val();
            var name = $("#reg_court_complex_id option:selected").text();
            $('#show_court_name').html(name);

        });
    });
</script>
