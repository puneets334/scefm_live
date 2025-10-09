<?php

$segment = service('uri');
if ($segment->getSegment(2) != 'view') {
    if ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_MISC_DOCS) {
        render('miscellaneous_docs.misc_docs_breadcrumb');
        // $this->load->view('miscellaneous_docs/misc_docs_breadcrumb');
    } elseif ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_IA) {
        render('IA.ia_breadcrumb');
    }
}

?>

<?php
if (empty($_SESSION['cnr_details']['is_pet_def'])) {
?>

    <div class="panel panel-default">
        <h4 style="text-align: center;color: #31B0D5">Share Document </h4>

        <div class="panel-body">
            <?php
            $attribute = array('class' => 'form-horizontal', 'name' => 'add_share_email', 'id' => 'add_share_email', 'autocomplete' => 'off', 'enctype' => 'multipart/form-data');
            echo form_open(base_url('#'), $attribute);
            ?>
            <div id="msg">
            </div>
            <p style="text-align: center;">Add Name and Email of the person for advance service of document. </p>
            <p class="text-danger" style="text-align: center;"><b>Disclaimer:</b>- Service and supply of copies/ documents shall be governed by Law/ Rules/Notification in force by the Supreme Court. </p>
            <div class="row">
                <div class="form-group">
                    <div class="col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label class="control-label col-sm-5 input-sm"> </label>
                            <div class="col-sm-7">
                                <label class="radio-inline"><input type="radio" name="contact" id="new1" value="4" onclick="show_contact(4)" checked="">Case AOR(s)</label>
                                <label class="radio-inline"><input type="radio" name="contact" id="new1" value="1" onclick="show_contact(1)">New</label>
                                <label class="radio-inline"><input type="radio" name="contact" id="contact" value="2" onclick="show_contact(2)">Contact</label>
                                <label class="radio-inline"><input type="radio" name="contact" id="aor" value="3" onclick="show_contact(3)">AOR</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" style="display: none;" id="new_contact">
                <div class="form-group">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dynamic_field">
                            <tr>
                                <td><input type="text" name="name" id="name" placeholder="Enter  Name" class="form-control name_list cus-form-ctrl" minlength="3" />
                                    <ul id="searchResult_1"></ul>
                                </td>
                                <td><input type="email" name="email" id="email" placeholder="Enter  Email" class="form-control email_list cus-form-ctrl" /></td>
                                <!-- <td><button type="button" name="add" id="add" class="btn btn-success">Add More</button></td>  -->
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!--Start Case Contacts-->
            <div class="row" id="case_aor_con">
                <div class="form-group">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dynamic_field">
                            <?php
                            if (isset($case_aor_contacts)) {
                                foreach ($case_aor_contacts['advocates_list'] as $contacts) {
                            ?>
                                    <tr>
                                        <td>
                                            <input readonly type="text" name="name" id="name" placeholder="Enter  Name" class="form-control name_list cus-form-ctrl" value="<?= $contacts['name'] ?>" />
                                            <ul id="searchResult_1"></ul>
                                        </td>
                                        <td><input readonly type="email" name="email" id="email" placeholder="Enter  Email" class="form-control email_list cus-form-ctrl" value="<?= $contacts['email'] ?>" /></td>
                                        <td><input type="checkbox" name="case_aor_contacts[]" value="<?= $contacts['name'] . '$$' . $contacts['email'] ?>"></td>
                                    </tr>
                            <?php    }
                            }
                            ?>
                        </table>
                    </div>
                </div>
            </div>
            <!--END-->
            <div class="row" style="display: none;" id="contact_id">
                <div class="form-group">
                    <div class="col-sm-12 col-xs-12">
                        <div class="form-group">

                            <div class="col-md-5 col-sm-12 col-xs-12">
                                <select name="contact_name" id="contact_name" class="form-control input-sm filter_select_dropdown cus-form-ctrl" style="width: 100%">
                                    <option value="">Select Contact</option>
                                    <?php
                                    foreach ($contact as $cont) {
                                        $con_name = explode('$$', $cont['p_name']);
                                        $con_email = explode('$$', $cont['p_email']);
                                        for ($j = 0; $j < count($con_name); $j++) {
                                            if (!empty($con_email[$j])) {
                                    ?>
                                                <option value="<?php echo $con_name[$j] . "#$" . $con_email[$j]; ?>"><?php echo $con_name[$j]; ?></option>
                                    <?php
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="display: none;" id="aor_con">
                <div class="form-group">
                    <div class="col-sm-12 col-xs-12">
                        <div class="form-group">
                            <div class="col-md-5 col-sm-12 col-xs-12">
                                <select name="aor_name" id="aor_name" class="form-control input-sm filter_select_dropdown cus-form-ctrl" style="width: 100%">
                                    <option value="">Select Contact</option>
                                    <?php foreach ($aor_contact as $aor) { ?>
                                        <option value="<?php echo $aor['name'] . "#$" . $aor['enroll_no'] . "#$" . $aor['email']; ?>"><?php echo $aor['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="text-center">
            <?php
            if ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_MISC_DOCS) {
                $prev_url = base_url('miscellaneous_docs/courtFee');
                //$next_url = base_url('affirmation');
                $next_url = base_url('miscellaneous_docs/view');
            } else if ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_IA) {
                $prev_url = base_url('IA/courtFee');
                //$next_url = base_url('affirmation');
                $next_url = base_url('miscellaneous_docs/view');
            } else {
                $prev_url = '#';
                $next_url = '#';
            }
            ?>
            <a href="<?= $prev_url ?>" class="btn btn-primary btnPrevious" type="button">PREVIOUS</a>
            <input type="submit" class="btn btn-success" value="SAVE">
            <?php if (!empty($email_details)) { ?>
                <a href="<?= $next_url ?>" class="btn btn-primary btnNext" type="button">NEXT</a>
            <?php } ?>
        </div>
        <?php
        echo form_close();
        ?>

        <?php
        if (!empty($email_details)) {
        ?>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <p style="text-align: center;">Name and Email of the person for advance service of document. </p>
                <p class="text-danger" style="text-align: center;"><b>Disclaimer:</b>- Service and supply of copies/ documents shall be governed by Law/ Rules/Notification in force by the Supreme Court. </p>

                <div class="x_panel">
                    <div class="x_content">
                        <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr class="success input-sm" role="row">
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 1;
                                foreach ($email_details as $value) {
                                ?>
                                    <tr>
                                        <td data-key="#" width="1%"> <?php echo htmlentities($i++, ENT_QUOTES); ?></td>
                                        <td data-key="Name" width="25%"> <?php echo htmlentities($value->name, ENT_QUOTES); ?>
                                        <td data-key="Email"> <?php echo htmlentities($value->email, ENT_QUOTES); ?></td>
                                        <td data-key="Delete">
                                            <a href="javascript:void(0)" onclick="DocDeleteAction('<?php echo htmlentities(url_encryption(trim($value->id . '$$' . $_SESSION['efiling_details']['registration_id']), ENT_QUOTES)); ?>')"><i class="fa fa-trash"> Delete</i> </a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

    <?php
        }
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
    </div>
    <script>
        $(document).ready(function() {
            var i = 1;
            $('#add').click(function() {
                i++;
                if (i <= 10) {
                    $('#dynamic_field').append('<tr id="row' + i + '"><td><input type="text" id="name_' + i + '" name="name[]" placeholder="Enter  Name" class="form-control name_list cus-form-ctrl" required/> <ul id="searchResult_' + i + '"></ul></td><td><input type="text" name="email[]" placeholder="Enter Email" class="form-control email_list cus-form-ctrl"  id="email_' + i + '"  required/></td><td><button type="button" name="remove" id="' + i + '" class="btn btn-danger btn_remove">X</button></td></tr>');
                }
            });

            $(document).on('click', '.btn_remove', function() {
                var button_id = $(this).attr("id");
                $('#row' + button_id + '').remove();
            });

            $('#add_share_email').on('submit', function() {
                var is_contact = $('input[name="contact"]:checked').val();

                // alert(is_contact); //return false;
                if ($('input[name="contact"]:checked').val() == 1) {
                    var name = $('#name').val();
                    var email = $('#email').val();
                    if (name.length == 0) {
                        $("#name").focus();
                        $('#msg').show();
                        $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp;Name is required <span class='close' onclick=hideMessageDiv()>X</span></p>");
                        return false;
                    } else if (email.length == 0) {
                        $("#email").focus();
                        $('#msg').show();
                        $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp;Email is required <span class='close' onclick=hideMessageDiv()>X</span></p>");
                        return false;
                    }
                    var form_data = {
                        name: name,
                        email: email,
                        contact: is_contact
                    };
                }
                if ($('input[name="contact"]:checked').val() == 2) {

                    var contact_name = $('#contact_name option:selected').val();

                    if (contact_name.length == 0) {
                        $("#contact_name").focus();
                        $('#msg').show();
                        $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp;Contact is required <span class='close' onclick=hideMessageDiv()>X</span></p>");
                        return false;
                    }
                    var cont = $('#contact_name').val();
                    var resArr = cont.split('#$');
                    var name = resArr[0];
                    var email = resArr[1];
                    var form_data = {
                        name: name,
                        email: email,
                        contact: is_contact
                    };
                }
                if ($('input[name="contact"]:checked').val() == 3) {
                    var aor_name = $('#aor_name option:selected').val();

                    if (aor_name.length == 0) {
                        $("#aor_name").focus();
                        $('#msg').show();
                        $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp;Contact is required <span class='close' onclick=hideMessageDiv()>X</span></p>");
                        return false;
                    }
                    var name = $('#aor_name').val();
                    var resArr = name.split('#$');
                    var ad_name = resArr[0] + '(' + resArr[1] + ')';
                    var email = resArr[2];
                    var form_data = {
                        name: ad_name,
                        email: email,
                        contact: is_contact
                    };
                }
                if ($('input[name="contact"]:checked').val() == 4) {
                    var val = [];
                    $('input[name="case_aor_contacts[]"]:checked').each(function(i) {
                        val[i] = $(this).val();
                    });

                    if (val.length == 0) {
                        $("#case_aor_contacts").focus();
                        $('#msg').show();
                        $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp;Name and Email id are required <span class='close' onclick=hideMessageDiv()>X</span></p>");
                        return false;
                    }
                    var form_data = {
                        case_aor_cotacts: val,
                        contact: is_contact
                    };
                    //return false;
                }
                if ($('#add_share_email').valid()) {

                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url('shareDoc/Ajaxcalls_doc_share/add_share_email'); ?>",
                        data: form_data,
                        success: function(data) {

                            var resArr = data.split('@@@');
                            if (resArr[0] == 1) {
                                $('#msg').show();
                                $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                            } else if (resArr[0] == 2) {
                                $('#msg').show();
                                $(".form-response").html("<p class='message valid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                                setTimeout(function() {
                                    $('#msg').hide();
                                }, 3000);
                                location.reload();
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
                    return false;
                } else {
                    return false;
                }
            });
        });


        function show_contact(element) {

            if (element == 1) {
                $('#new_contact').show();
                $('#contact_id').hide();
                $('#aor_con').hide();
                $('#case_aor_con').hide();
            }
            if (element == 2) {
                $('#new_contact').hide();
                $('#contact_id').show();
                $('#aor_con').hide();
                $('#case_aor_con').hide();
            }
            if (element == 3) {
                $('#new_contact').hide();
                $('#contact_id').hide();
                $('#aor_con').show();
                $('#case_aor_con').hide();
            }
            if (element == 4) {
                $('#new_contact').hide();
                $('#contact_id').hide();
                $('#aor_con').hide();
                $('#case_aor_con').show();
            }
        }


        function DocDeleteAction(value) {
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            var a = confirm("Are you sure that you really want to delete this record ?");
            if (a == true) {
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('shareDoc/Ajaxcalls_doc_share/deleteUserfromShareDoc'); ?>",
                    data: {
                        CSRF_TOKEN: CSRF_TOKEN_VALUE,
                        form_submit_val: value
                    },
                    success: function(result) {
                        var resArr = result.split('@@@');
                        if (resArr[0] == 1) {
                            $('#msg').show();
                            $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                        } else if (resArr[0] == 2) {
                            $(".form-response").html("<p class='message valid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                            $('#msg').show();
                            location.reload();
                        }
                        $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function(result) {
                            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        });
                    },
                    error: function(result) {
                        $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function(result) {
                            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        });
                    }
                });
            }
        }
    </script>

    <style>
        ul {
            list-style: none;
            max-height: 100px;
            margin: 0;
            /* overflow: auto; */
            padding: 0;
            text-indent: 10px;
        }

        li {
            line-height: 25px;
        }
    </style>