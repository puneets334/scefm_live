<!-- page content -->
<div class="right_col" role="main">
    <div id="page-wrapper">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="form-response" id="msg"></div>
                <div class="x_panel">
                    <div class="x_title"><h3>User Details</h3></div>  
                    <div class="x_content">
                        <div class="tab-content">
                            <div id="profile-tab" class="tab-pane active">
                                <a class="btn btn-info" type="button" onclick="window.history.back()" /> Back</a>
                                <h3><i class="fa fa-user"></i> ABOUT</h3>
                                <div class="row">
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-md-4 col-xs-6">Full Name:</label>
                                        <div class="col-md-8 col-xs-6"><?php echo ucwords(htmlentities($advocate_info[0]->first_name, ENT_QUOTES) . ' ' . htmlentities($advocate_info[0]->last_name, ENT_QUOTES)); ?></div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-md-4 col-xs-6">Bar Registration:</label>
                                        <div class="col-md-8 col-xs-6"><?php echo strtoupper(htmlentities($advocate_info[0]->bar_reg_no, ENT_QUOTES)); ?></div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-md-4 col-xs-6">Mobile:</label>
                                        <div class="col-md-8 col-xs-6"><?php echo htmlentities($advocate_info[0]->moblie_number, ENT_QUOTES); ?></div>

                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-md-4 col-xs-6">Email:</label>
                                        <div class="col-md-8 col-xs-6"><?php echo htmlentities($advocate_info[0]->emailid, ENT_QUOTES); ?></div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-md-4 col-xs-6">Gender:</label>
                                        <div class="col-md-8 col-xs-6"><?php echo ucwords(htmlentities($advocate_info[0]->gender, ENT_QUOTES)); ?></div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-md-4 col-xs-6">Date of Birth:</label>
                                        <div class="col-md-8 col-xs-6"><?php echo ucwords(htmlentities($advocate_info[0]->dob, ENT_QUOTES)); ?></div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-md-4 col-xs-6">Other Phone:</label>
                                        <div class="col-md-8 col-xs-6"><?php echo htmlentities($advocate_info[0]->other_contact_number, ENT_QUOTES); ?></div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-md-4 col-xs-6">User Type:</label>
                                        <?php
                                        if ($advocate_info[0]->ref_m_usertype_id == USER_ADVOCATE) {
                                            $user_type = 'Advocate';
                                        } elseif ($advocate_info[0]->ref_m_usertype_id == USER_IN_PERSON) {
                                            $user_type = 'Party-in-Person';
                                        }
                                        ?>
                                        <div class="col-md-8 col-xs-6"><?php echo ucwords(htmlentities($user_type, ENT_QUOTES)); ?></div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-md-4 col-xs-6">Address:</label>
                                        <?php
                                        if ($advocate_info[0]->address2 != '') {
                                            $address2 = ', ' . $advocate_info[0]->address2;
                                        }
                                        if ($advocate_info[0]->city != '') {
                                            $city = ', ' . $advocate_info[0]->city;
                                        }
                                        if ($advocate_info[0]->pincode != '') {
                                            $pincode = '- ' . $advocate_info[0]->pincode;
                                        }
                                        ?>
                                        <div class="col-md-8 col-xs-6"><?php echo ucwords($advocate_info[0]->address1 . $address2 . $city . $pincode); ?></div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <label class="col-md-4 col-xs-6">Registered In High Court / Lower Court (for efiling purpose):</label>
                                        <div class="col-md-8 col-xs-6"><?php echo htmlentities($advocate_info[0]->enrolled_for, ENT_QUOTES); ?></div>
                                    </div>
                                </div>
                                <hr>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <h3><i class="fa fa-image"></i> Photo</h3>
                                    <div class="panel-body">
                                        <div> <img class="img-thumbnail expand-img" alt="User Profile Photo" src="<?php echo htmlentities($advocate_info[0]->photo_name_uploaded, ENT_QUOTES); ?>" style="max-width: 300px;max-height: 300px;"> </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <h3><i class="fa fa-image"></i> ID Proof</h3>
                                    <div class="panel-body">
                                        <div> <img class="img-thumbnail expand-img" alt="User ID Proof" src="<?php echo htmlentities($advocate_info[0]->id_proof_name_uploaded, ENT_QUOTES); ?>" style="max-width: 300px;max-height: 300px;"> </div>
                                    </div>
                                </div>

                                <script>
                                    $('.expand-img').click(function () {
                                        var img_path = $(this).attr('src');
                                        $("#image_popup_model").modal();
                                        $("#image_popup_model .result").html('<img class="img-thumbnail" src="' + img_path + '">');
                                    });

                                </script>
                                <div class="modal fade" id="image_popup_model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="result text-center">  </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                if ($advocate_info[0]->is_active == 'f' && ($_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN || $_SESSION['login']['ref_m_usertype_id'] == USER_MASTER_ADMIN)) {
                                    if ($advocate_info[0]->moblie_number != 'NULL' . $advocate_info[0]->id && $advocate_info[0]->emailid != 'NULL' . $advocate_info[0]->id) {
                                        ?>
                                        <div class="clearfix"></div><br><br>
                                        <div class="row text-center">
                                            <button type="button"  class="btn btn-success" onclick="ActionToActivateUser('<?php echo htmlentities(url_encryption(trim($advocate_info[0]->id), ENT_QUOTES)); ?>')" >Activate</button>
                                            <button type="button" class="btn btn-danger  reject_user_by_admin" data-user-id="<?php echo htmlentities(url_encryption($advocate_info[0]->id), ENT_QUOTES); ?>">Reject</button>

                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                                <div class="row">
                                    <a class="btn btn-info" type="button" onclick="window.history.back()" /> Back</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).on('click', '#reject_frm_submit', function () {
        var temp = $('#editor-reason_to_reject').text();
        if (temp)
        {
            if (temp.length > 500) {
                $('#reject_user_action_alerts').html('<div class="alert alert-danger">Maximum length 500 allowed.</div>');
            } else {
                $('#reason_to_reject').val($('#editor-reason_to_reject').html());
                $('#reject_user_action').submit();
            }
        } else {
            $('#reject_user_action_alerts').html('<div class="alert alert-danger">Write reason to reject.</div>');
        }

    });

    $(document).on('keyup', '#editor-reason_to_reject', function () {
        var word_count = $(this).text();
        if (word_count == '<br>') {
            word_count = '0';
            $('#editor-reason_to_reject').html('');
            $('.help-block').html('0 of 500');
        } else {
            $('.help-block').html(word_count.length + ' of 500');
        }
    });
</script>