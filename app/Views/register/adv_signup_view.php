<?php
$uid_data_name = $_SESSION['kyc_configData']['UidData']['Poi']['@attributes']['name'];
$uid_data_dob = $_SESSION['kyc_configData']['UidData']['Poi']['@attributes']['dob'];
$uid_data_dob = str_replace('-', '/', $uid_data_dob);
$uid_data_email = $_SESSION['kyc_configData']['UidData']['Poi']['@attributes']['e'];
$uid_data_gender = $_SESSION['kyc_configData']['UidData']['Poi']['@attributes']['gender'];
$uid_data_mobile = $_SESSION['kyc_configData']['UidData']['Poi']['@attributes']['m'];

$uid_data_photo = $_SESSION['kyc_configData']['UidData']['Pht'];
$uid_data_son_of = $_SESSION['kyc_configData']['UidData']['Poa']['@attributes']['careof'];
$uid_data_country = $_SESSION['kyc_configData']['UidData']['Poa']['@attributes']['country'];
$uid_data_distt = $_SESSION['kyc_configData']['UidData']['Poa']['@attributes']['dist'];
$uid_data_house_no = $_SESSION['kyc_configData']['UidData']['Poa']['@attributes']['house'];
$uid_data_landmark = $_SESSION['kyc_configData']['UidData']['Poa']['@attributes']['landmark'];
$uid_data_locality = $_SESSION['kyc_configData']['UidData']['Poa']['@attributes']['loc'];
$uid_data_pincode = $_SESSION['kyc_configData']['UidData']['Poa']['@attributes']['pc'];
$uid_data_post = $_SESSION['kyc_configData']['UidData']['Poa']['@attributes']['po'];
$uid_data_state = $_SESSION['kyc_configData']['UidData']['Poa']['@attributes']['state'];
$uid_data_street = $_SESSION['kyc_configData']['UidData']['Poa']['@attributes']['street'];
$uid_data_village = $_SESSION['kyc_configData']['UidData']['Poa']['@attributes']['vtc'];

$user_addar_img = 'data:image/png;base64,' . htmlentities($uid_data_photo, ENT_QUOTES);
?>
<div class="panel-body panel-body-in" style="background: white;"> 
    <?php $star_requered = '<span style="color: red">*</span>'; ?>
    <center><h3><?php echo $_SESSION['adv_details']['register_type']; ?></h3> </center>
    <div class="form-response">

    </div> 
    <?php echo $this->session->flashdata('msg'); ?>
    <div class="panel panel-default">  
        <div class="panel-body" style="padding:60px;padding-top: 0;padding-bottom: 0;"> 

            <div class="col-md-12 col-sm-12 col-xs-12" style="margin-top: 10px;"> 

                <?php if (url_decryption($_SESSION['register_type_select']) == '1') { ?>
                    <div class="col-md-5 col-sm-12 col-xs-12 col-md-offset-1" style="height:150px;margin-top: 10px; background-color: #eeeeee;">
                        <div id="aadhar_details" > 
                            <center> <b>Aadhar Details </b></center> <hr style="margin-top: 0; margin-bottom: 5px;">
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <p style=" margin: 0 !important; margin-top: 0 !important;"><b>S/O : </b><?php echo htmlentities($uid_data_son_of, ENT_QUOTES); ?></p>
                                <p style=" margin: 0 !important; margin-top: 0 !important;"><b>Country : </b><?php echo htmlentities($uid_data_country, ENT_QUOTES); ?></p>
                                <p style=" margin: 0 !important; margin-top: 0 !important;"><b>District : </b><?php echo htmlentities($uid_data_distt, ENT_QUOTES); ?></p>
                                <p style=" margin: 0 !important; margin-top: 0 !important;"><b>House No. : </b><?php echo htmlentities($uid_data_house_no, ENT_QUOTES); ?></p>
                                <p style=" margin: 0 !important; margin-top: 0 !important;"><b>Landmark : </b><?php echo htmlentities($uid_data_landmark, ENT_QUOTES); ?></p>
                                <p style=" margin: 0 !important; margin-top: 0 !important;"><b>Locality : </b><?php echo htmlentities($uid_data_locality, ENT_QUOTES); ?></p>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <p style=" margin: 0 !important; margin-top: 0 !important;"><b>Pine Code : </b><?php echo htmlentities($uid_data_pincode, ENT_QUOTES); ?></p>
                                <p style=" margin: 0 !important; margin-top: 0 !important;"><b>Post : </b><?php echo htmlentities($uid_data_post, ENT_QUOTES); ?></p>
                                <p style=" margin: 0 !important; margin-top: 0 !important;"><b>State : </b><?php echo htmlentities($uid_data_state, ENT_QUOTES); ?></p>
                                <p style=" margin: 0 !important; margin-top: 0 !important;"><b>Street : </b><?php echo htmlentities($uid_data_street, ENT_QUOTES); ?></p>
                                <p style=" margin: 0 !important; margin-top: 0 !important;"><b>Village : </b><?php echo htmlentities($uid_data_village, ENT_QUOTES); ?></p> 
                            </div>  
                        </div>
                    </div>
                <?php } ?>

                <?php
                if (url_decryption($_SESSION['register_type_select']) == '1') {
                    $div = 'col-md-6 col-sm-12 col-xs-12';
                } else {
                    $div = 'col-md-9 col-sm-12 col-xs-12 col-md-offset-2';
                }
                ?>

                <div class="<?php echo $div; ?>">
                    <!--<form id="uploadForm" action="upload.php" method="post">-->
                        <?php
                        $attribute = array('id' => 'uploadForm');
                        echo form_open(base_url('uploadForm'), $attribute);
                        ?>
                        <div class="bgColor">

                            <div id="targetLayer">
                                <?php if (!empty($_SESSION['profile_image']['profile_photo'])) { ?> 
                                    <img class="image-preview" src="<?php echo $_SESSION['profile_image']['profile_photo']; ?>" class="upload-preview" height="40" width="50" />
                                <?php } else if (!empty($_SESSION['kyc_configData']['UidData']['Pht'])) {
                                    ?>
                                    <img class="image-preview" src="<?php echo $user_addar_img; ?>" class="upload-preview" height="40" width="50" />
                                    <?php
                                } else {
                                    echo "NO IMAGE";
                                }
                                ?>
                            </div>
                            <div id="uploadFormLayer">
                                <center><p style="color: red;">NOTE : Please upload only JPG or JPEG. File name maximun length can be 40 characters including digits characters, spaces, hypens and underscore. maximum file size 1MB.</p></center>
                                <center>
                                    <input name="advocate_image" required type="file" value="<?php echo_data($user_addar_img) ?>" class="inputFile btn btn-info" style="width: 50%;"/> 
                                    <input type="submit" value="UPLOAD" class="btnSubmit btn btn-info" style="width: 50%;" />
                                </center>
                            </div>
                        </div> 
                    </form>
                </div> 
            </div> 

            <?php
            $attribute = array('class' => 'form-horizontal form-label-left', 'name' => 'advocate_reg_info', 'id' => 'advocate_reg_info', 'autocomplete' => 'off');
            echo form_open('register/AdvSignUp/add_advocate', $attribute);
            ?>
            <div class="col-md-12 col-sm-12 col-xs-12" style="margin-top: 20px;"> 
                <div class="col-md-6 col-sm-12 col-xs-12"> 
                    <div class="row">
                        <div class="col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12 input-sm"> Name <span style="color: red">*</span> :</label>
                                <div class="col-md-9 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <?php
                                        if (!empty($uid_data_name)) {
                                            $value = $uid_data_name;
                                        } else {
                                            $value = set_value('name');
                                        }
                                        ?> 
                                        <input id="name" name="name" placeholder="Name" maxlength="50" class="form-control input-sm" style="text-transform: uppercase;" type="text" value="<?php echo $value; ?>">

                                        <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Please enter valid Advocate Full Name.">
                                            <i class="fa fa-question-circle-o" ></i>
                                        </span>
                                    </div>
                                    <?php echo form_error('name'); ?>
                                </div>
                            </div>
                        </div>
                    </div> 
                    <div class="row">
                        <div class="col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12 input-sm">Date of Birth <span style="color: red">*</span> :</label>
                                <div class="col-md-9 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <input class="form-control has-feedback-left datepicker" value="<?php
                                        if (!empty($post_datas[2])) {
                                            echo date("d/m/Y", strtotime(htmlentities($post_datas[2], ENT_QUOTES)));
                                        } elseif (!empty($uid_data_dob)) {
                                            echo $uid_data_dob;
                                        } else {
                                            
                                        }
                                        ?>" name="date_of_birth" id="date_of_birth" maxlength="10" readonly="" placeholder="DD/MM/YYYY" type="text">
                                        <span class="fa fa-calendar-o form-control-feedback left" style="" aria-hidden="true"></span>
                                        <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Please Enter Date of Birth (DD/MM/YYYY)." data-original-title="" title="">
                                            <i class="fa fa-question-circle-o"></i>
                                        </span>
                                    </div>
                                    <?php echo form_error('date_of_birth'); ?>
                                </div>
                            </div>
                        </div>
                    </div> 

                    <div class="row">
                        <div class="col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12 input-sm">Mobile :</label>
                                <div class="col-md-9 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <input value="<?php echo $_SESSION['adv_details']['mobile_no']; ?>" placeholder="Mobile" class="form-control input-sm" readonly name='mobile' type="text">
                                        <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Pin Code should be in number">
                                            <i class="fa fa-question-circle-o"></i>
                                        </span>
                                    </div>
                                    <?php echo form_error('mobile'); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12 input-sm">Email ID :</label>
                                <div class="col-md-9 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <input value="<?php echo $_SESSION['adv_details']['email_id']; ?>" placeholder="Email ID" name='email_id' class="form-control input-sm" readonly type="text">
                                        <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Pin Code should be in number">
                                            <i class="fa fa-question-circle-o"></i>
                                        </span>
                                    </div>
                                    <?php echo form_error('email_id'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-sm-3 col-sm-12 col-xs-12 input-sm" > Gender <span style="color: red">*</span> :</label>
                                <div class="col-md-9 col-sm-12 col-xs-12"> 
                                    <div class="input-group col-md-12">
                                        <?php
                                        if ($uid_data_gender == 'M') {
                                            $male = 'checked';
                                        } elseif ($uid_data_gender == 'F') {
                                            $female = 'checked';
                                        }
                                        ?> 
                                        <label class="radio-inline"><input type="radio" <?php echo_data($male) ?> id="gender" name="gender" value="<?php echo htmlentities(url_encryption(1), ENT_QUOTES); ?>" maxlength="1"> Male </label>
                                        <label class="radio-inline"><input type="radio" <?php echo_data($female) ?> id="gender" name="gender" value="<?php echo htmlentities(url_encryption(2), ENT_QUOTES); ?>" maxlength="1"> Female </label>
                                        <label class="radio-inline"><input type="radio" id="gender" name="gender"  value="<?php echo htmlentities(url_encryption(3), ENT_QUOTES); ?>" maxlength="1"> Other </label>
                                    </div>
                                    <?php echo form_error('gender'); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-sm-6 col-sm-12 col-xs-12">
                    <div class="row">

                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12 input-sm"> Address <?php echo $star_requered; ?> :</label>
                            <div class="col-md-9 col-sm-12 col-xs-12">
                                <div class="input-group">
                                    <textarea name="address" id="address" rows="3" placeholder="H.No.,  Street no, Colony,  Land Mark" class="form-control input-sm"></textarea> 
                                    <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Please enter House No, Street No, Sector, Colony and Landmarks. Please Select District from the below mentioned field. Do not repeat District in Address fields and District Fields. Alternate Address can be alphanumeric (<?php echo VALIDATION_PREG_MATCH_MSG; ?> ).">
                                        <i class="fa fa-question-circle-o" ></i>
                                    </span>
                                </div>
                                <?php echo form_error('address'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="row"> 
                        <div class="col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12 input-sm"> State <?php echo $star_requered; ?> :</label>
                                <div class="col-md-9 col-sm-12 col-xs-12">
                                    <select name="state_id" id="state_id" <?php echo $requerd; ?> style="width: 100%;" class="form-control input-sm filter_select_dropdown">
                                        <option value="" title="Select">Select State</option> 
                                        <?php
                                        foreach ($select_state as $state) {
                                            echo '<option ' . $selected . ' value="' . htmlentities($state['state_code'] . '#$' . $state['state_name'], ENT_QUOTES) . '">' . htmlentities(strtoupper($state['state_name']), ENT_QUOTES) . '</option>';
                                        }
                                        ?>
                                    </select>
                                    <?php echo form_error('state_id'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12 input-sm"> District <?php echo $star_requered; ?> :</label>
                                <div class="col-md-9 col-sm-12 col-xs-12">
                                    <select name="district_list" id="district_list" <?php echo $requerd; ?> style="width: 100%;" class="form-control input-sm filter_select_dropdown">
                                        <option value="" title="Select">Select District</option>  

                                    </select>
                                    <?php echo form_error('district_list'); ?>
                                </div>
                            </div>
                        </div>
                    </div>  
                    <div class="row">
                        <div class="col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-12 col-xs-12 input-sm">Pin Code  <?php echo $star_requered; ?> :</label>
                                <div class="col-md-9 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <input id="pincode" name="pincode" <?php echo htmlentities($requerd, ENT_QUOTES); ?> value="" placeholder="Pin Code" maxlength="6" class="form-control input-sm"  type="text">
                                        <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Pin Code should be in number">
                                            <i class="fa fa-question-circle-o"></i>
                                            <a href="https://www.indiapost.gov.in/vas/pages/findpincode.aspx" target="_blank">Pin Code Locator</a>
                                        </span>
                                    </div>
                                    <?php echo form_error('pincode'); ?>
                                </div>
                            </div>
                        </div>
                    </div> 
                </div>  

                <div class="form-group "> 
                    <div class="col-md-2 col-sm-6 col-xs-12 col-md-offset-6" style="padding: 10px;">
                        <input type="submit" class="btn btn-success btn-sm" id="info_save" value="SUBMIT">
                        <a href="<?php echo base_url('register'); ?>" class="btn btn-danger btn-sm" >CANCEL</a>
                    </div>
                </div> 

            </div> 


            <?php echo form_close(); ?>  
        </div>


    </div>
</div> <br><br>
</div>

<?php $this->load->view('register/adv_signup_nav'); ?>

