@extends('layout.frontApp')
@section('content')
<!-- <link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-datepicker.css"> -->
<link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-datepicker.min.css">
<style type="text/css">
    @media only screen and (min-width: 960px)    {
        body{
            overflow-x:hidden;
        }
    }
    .mt22 p{margin-top: -22px;}
</style>
<style>
    /* .datepicker-dropdown {
        margin-top: 300px !important;
    } */

    span.select2 {
        width: 100% !important;

    }

    .datepicker-dropdown {
        background-color: #fff;
    }

    .error {
        color: red;
    }

    .datepicker-days {
        background-color: #ffffff;
    }
</style>
<?php
// if(isset($_SESSION['kyc_configData'])) {
    $uid_data_name = isset($_SESSION['kyc_configData']) ? $_SESSION['kyc_configData']['UidData']['Poi']['@attributes']['name'] : '';
    $uid_data_dob = isset($_SESSION['kyc_configData']) ? $_SESSION['kyc_configData']['UidData']['Poi']['@attributes']['dob'] : '';
    $uid_data_dob = isset($_SESSION['kyc_configData']) ? str_replace('-', '/', $uid_data_dob) : '';
    $uid_data_email = isset($_SESSION['kyc_configData']) ? $_SESSION['kyc_configData']['UidData']['Poi']['@attributes']['e'] : '';
    $uid_data_gender = isset($_SESSION['kyc_configData']) ? $_SESSION['kyc_configData']['UidData']['Poi']['@attributes']['gender'] : '';
    $uid_data_mobile = isset($_SESSION['kyc_configData']) ? $_SESSION['kyc_configData']['UidData']['Poi']['@attributes']['m'] : '';

    $uid_data_photo = isset($_SESSION['kyc_configData']) ? $_SESSION['kyc_configData']['UidData']['Pht'] : '';
    $uid_data_son_of = isset($_SESSION['kyc_configData']) ? $_SESSION['kyc_configData']['UidData']['Poa']['@attributes']['careof'] : '';
    $uid_data_country = isset($_SESSION['kyc_configData']) ? $_SESSION['kyc_configData']['UidData']['Poa']['@attributes']['country'] : '';
    $uid_data_distt = isset($_SESSION['kyc_configData']) ? $_SESSION['kyc_configData']['UidData']['Poa']['@attributes']['dist'] : '';
    $uid_data_house_no = isset($_SESSION['kyc_configData']) ? $_SESSION['kyc_configData']['UidData']['Poa']['@attributes']['house'] : '';
    $uid_data_landmark = isset($_SESSION['kyc_configData']) ? $_SESSION['kyc_configData']['UidData']['Poa']['@attributes']['landmark'] : '';
    $uid_data_locality = isset($_SESSION['kyc_configData']) ? $_SESSION['kyc_configData']['UidData']['Poa']['@attributes']['loc'] : '';
    $uid_data_pincode = isset($_SESSION['kyc_configData']) ? $_SESSION['kyc_configData']['UidData']['Poa']['@attributes']['pc'] : '';
    $uid_data_post = isset($_SESSION['kyc_configData']) ? $_SESSION['kyc_configData']['UidData']['Poa']['@attributes']['po'] : '';
    $uid_data_state = isset($_SESSION['kyc_configData']) ? $_SESSION['kyc_configData']['UidData']['Poa']['@attributes']['state'] : '';
    $uid_data_street = isset($_SESSION['kyc_configData']) ? $_SESSION['kyc_configData']['UidData']['Poa']['@attributes']['street'] : '';
    $uid_data_village = isset($_SESSION['kyc_configData']) ? $_SESSION['kyc_configData']['UidData']['Poa']['@attributes']['vtc'] : '';
// }
// else{
//     echo "Session data not found!";
//     exit; 
// }
$user_addar_img = 'data:image/png;base64,' . htmlentities($uid_data_photo, ENT_QUOTES);
?>
        <?php $star_requered = '<span style="color: red">*</span>'; ?>
        <?php $session = session(); ?>
 
        <div class="container-fluid">
        <div class="row card">
            <div class="col-lg-12">
                <div class="card-body">
                <div class="dashboard-section">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="dash-card">
                    <?php $session = session(); ?>
                    <div class="text-danger">
                        <b><?php echo $session->getFlashData('msg'); ?></b>
                    </div>
                    <div class="httxt">
                        <h4><?php echo $_SESSION['adv_details']['register_type']; ?></h4>
                    </div>
                    <div>
                        <?php if(isset($_SESSION['session_not_register_type_user']) && url_decryption($_SESSION['session_not_register_type_user']) == 'not_register_other'){ ?>
                        <div class="uk-card-body" >
                            <div class="uk-child-width-1-1@s uk-grid-small uk-text-center" style="background-color: #eeeeee;color:#29527C;padding-bottom: 13px;font-weight: 700;font-size: 13px;line-height: 1.471;font-family: 'Helvetica Neue',Roboto,Arial,'Droid Sans',sans-serif;" >

                            <div>
                                <div class="uk-tile uk-tile-mutedww uk-padding-remove" style="padding-bottom: 65px;">
                                    <p class="uk-h4">Aadhar Details </p>
                                    <hr/>
                                    <div class="uk-column-1-2@s uk-column-1-2@m uk-column-1-2@l uk-text-left">
                                        <div class="uk-column-1-1@s uk-column-1-1@m uk-column-1-1@l mt22">
                                            <p>S/O  : <?php echo htmlentities($uid_data_son_of, ENT_QUOTES); ?></p>
                                            <p>Country  : <?php echo htmlentities($uid_data_country, ENT_QUOTES); ?></p>
                                            <p>District  : <?php echo htmlentities($uid_data_distt, ENT_QUOTES); ?></p>
                                            <p>House No.  : <?php echo htmlentities($uid_data_house_no, ENT_QUOTES); ?></p>
                                            <p>Landmark  : <?php echo htmlentities($uid_data_landmark, ENT_QUOTES); ?></p>
                                            <p>Locality  : <?php echo htmlentities($uid_data_locality, ENT_QUOTES); ?></p>
                                        </div>
                                        <div class="uk-column-1-1@s uk-column-1-1@m uk-column-1-1@l mt22">
                                            <p>Pincode : <?php echo htmlentities($uid_data_pincode, ENT_QUOTES); ?></p>
                                            <p>Post  : <?php echo htmlentities($uid_data_post, ENT_QUOTES); ?></p>
                                            <p>State: <?php echo htmlentities($uid_data_state, ENT_QUOTES); ?></p>
                                            <p>Street  : <?php echo htmlentities($uid_data_street, ENT_QUOTES); ?></p>
                                            <p>Village  : <?php echo htmlentities($uid_data_village, ENT_QUOTES); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                        <?php } ?>
                    </div>
                    <div class="">
                        <?php
                        $attribute = array('id' => 'uploadForm', 'enctype' => "multipart/form-data");
                        echo form_open(base_url(), $attribute);
                        ?>
                            <input type="text" style="display: none;" name="_token" value="{{ csrf_token() }}">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-2 col-lg-1">
                                    <div class="mb-3" id="targetLayer">
                                        <?php if (!empty($_SESSION['profile_image']['profile_photo'])) { ?>
                                                <img  src="<?php echo $_SESSION['profile_image']['profile_photo']; ?>"  height="94" width="94" />
                                            <?php } else if (!empty($_SESSION['kyc_configData']['UidData']['Pht'])) {
                                                ?>
                                                <img  src="<?php echo $user_addar_img; ?>"  height="94" width="94" />
                                                <?php
                                            } else {
                                                echo "NO IMAGE";
                                            }
                                        ?>
                                       
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-10 col-lg-9">
                                    <div class="mb-3">
                                        <p class="image-note mb-2">
                                            <b>NOTE:</b> Please upload only JPG or JPEG.File name maximun length can be 40 characters <br> including digits characters, spaces, hypens and underscore. maximum file size 1MB.
                                        </p>
                                        <div class="row">
                                            <div class="text-danger">
                                                <b><?php echo $session->getFlashData('advocate_image'); ?></b>
                                            </div>
                                            <div class="col-12 col-sm-12 col-md-12 col-lg-4 mb-3">
                                                <input type="file" class="form-control cus-form-ctrl" value="<?php echo_data($user_addar_img) ?>" placeholder="" name="advocate_image"  accept=".jpg, .jpeg" id="advocate_image" required><span>Please Choose <b>Profile Picture</b> ( Upto 1MB )</span>
                                            </div>
                                            <div class="col-12 col-sm-12 col-md-12 col-lg-4">
                                                <input type="submit" value="UPLOAD" class="btnSubmit btn btn-sm quick-btn btn-primary" >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="loin-form">
                        <?php
                        if (!empty($uid_data_name)) {
                            $value = $uid_data_name;
                        } else {
                            $value = set_value('name');
                        }
                        ?>
                        <?php
                        $attribute = array('class' => 'uk-form-horizontal uk-margin-large', 'name' => 'advocate_reg_info', 'id' => 'advocate_reg_info', 'autocomplete' => 'off');
                        echo form_open('register/AdvSignUp/add_advocate', $attribute);
                        ?>
                            <input type="text" style="display: none;" name="_token" value="{{ csrf_token() }}">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Name <?php echo $star_requered; ?></label>
                                        <input class="form-control cus-form-ctrl" id="form-horizontal-text" type="text" oninput="validateInput(event)" id="name" name="name" placeholder="Name" maxlength="50" value="<?= session()->getFlashdata('old_name') ? session()->getFlashdata('old_name') : '' ?>">
                                        <?php if (isset($validation) && $validation->hasError('name')): ?>
                                            <div class="text-danger">
                                                <?= $validation->getError('name'); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php 
                                // Set the old date of birth value from session flashdata if it exists, else leave it blank
                                $old_dob = session()->getFlashdata('old_date_of_birth') ?? '';
                                ?>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Date of Birth <?php echo $star_requered; ?></label>
                                        <input class="form-control cus-form-ctrl"   name="date_of_birth" id="date_of_birth" maxlength="10" placeholder="DD/MM/YYYY" value="<?= $old_dob ?>" type="text">
                                                 <?php if (isset($validation) && $validation->hasError('date_of_birth')): ?>
                                                    <div class="text-danger">
                                                        <?= $validation->getError('date_of_birth'); ?>
                                                    </div>
                                                <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Mobile Number <?php echo $star_requered; ?></label>
                                        <input class="form-control cus-form-ctrl" id="form-horizontal-text" name='mobile' type="text" value="<?php echo $_SESSION['adv_details']['mobile_no']; ?>" placeholder="Mobile" minlength="10" maxlength="10" readonly>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Email ID <?php echo $star_requered; ?></label>
                                        <input class="form-control cus-form-ctrl" id="form-horizontal-text" value="<?php echo $_SESSION['adv_details']['email_id']; ?>" placeholder="Email ID" name='email_id' readonly>
                                    </div>
                                </div>
                                <?php
                                    $male = '';
                                    $female = '';
                                    if ($uid_data_gender == 'M') {
                                        $male = 'checked';
                                    } elseif ($uid_data_gender == 'F') {
                                        $female = 'checked';
                                    }
                                ?>
                                <?php 
                                    // Get old gender from session flashdata if available
                                    $old_gender = session()->getFlashdata('old_gender');

                                    ?>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Gender <?php echo $star_requered; ?></label><br>
                                        <label class="radio-inline">
                                            <input type="radio" <?php echo_data($male) ?> id="gender" name="gender" value="<?php echo htmlentities(url_encryption(1), ENT_QUOTES); ?>" maxlength="1" checked <?= ($old_gender == url_encryption(1)) ? 'checked' : ''; ?> > Male </label>
                                            <label class="radio-inline">
                                                <input type="radio" <?php echo_data($female) ?> id="gender" name="gender" value="<?php echo htmlentities(url_encryption(2), ENT_QUOTES); ?>" maxlength="1" <?= ($old_gender == url_encryption(2)) ? 'checked' : ''; ?> > Female </label>
                                            <label class="radio-inline">
                                                <input type="radio" id="gender" name="gender"  value="<?php echo htmlentities(url_encryption(3), ENT_QUOTES); ?>" maxlength="1" <?= ($old_gender == url_encryption(3)) ? 'checked' : ''; ?> > Other </label>
                                            <?php if (isset($validation) && $validation->hasError('gender')): ?>
                                            <div class="text-danger">
                                                <?= $validation->getError('gender'); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php $requerd = ''; $selected = '';?>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Address <?php echo $star_requered; ?></label>
                                        <textarea name="address" id="address" rows="1" placeholder="H.No.,  Street no, Colony,  Land Mark" class="form-control cus-form-ctrl" required><?php echo trim(htmlentities($uid_data_house_no, ENT_QUOTES)); ?><?= session()->getFlashdata('old_address') ? session()->getFlashdata('old_address') : '' ?>
                                            </textarea>
                                            <?php if (isset($validation) && $validation->hasError('address')): ?>
                                            <div class="text-danger">
                                                <?= $validation->getError('address'); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <?php 
                                    // Get old state from session flashdata if available
                                    $old_state_id = session()->getFlashdata('old_state_id');
                                    ?>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                    <div class="mb-3">
                                        <label for="" class="form-label">State <?php echo $star_requered; ?></label>
                                        <select name="state_id" id="state_id" class="form-control cus-form-ctrl" <?php echo $requerd; ?>>
                                            <option value="" title="Select">Select State</option>
                                            <?php
                                           
                                            foreach ($select_state as $state) {
                                                $state_value = htmlentities($state['state_code'] . '#$' . $state['state_name'], ENT_QUOTES);
                                                $selected = ($old_state_id == $state_value) ? 'selected' : '';
                                                echo '<option ' . $selected . ' value="' . htmlentities($state['state_code'] . '#$' . $state['state_name'], ENT_QUOTES) . '">' . htmlentities(strtoupper($state['state_name']), ENT_QUOTES) . '</option>';
                                            }
                                            ?>
                                        </select>
                                        <?php if (isset($validation) && $validation->hasError('state_id')): ?>
                                            <div class="text-danger">
                                                <?= $validation->getError('state_id'); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                    <div class="mb-3">
                                        <label for="" class="form-label">District <?php echo $star_requered; ?></label>
                                        <select name="district_list" id="district_list" class="form-control cus-form-ctrl" required <?php echo $requerd; ?> >
                                            <option value="" title="Select">Select District</option>
                                        </select>
                                        <?php if (isset($validation) && $validation->hasError('district_list')): ?>
                                            <div class="text-danger">
                                                <?= $validation->getError('district_list'); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Pin Code <?php echo $star_requered; ?></label>
                                        <input class="form-control cus-form-ctrl" id="pincode" name="pincode" <?php echo htmlentities($requerd, ENT_QUOTES); ?> value="<?= session()->getFlashdata('old_pincode') ? session()->getFlashdata('old_pincode') : '' ?>" placeholder="Pincode" maxlength="6" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');">
                                        <?php if (isset($validation) && $validation->hasError('pincode')): ?>
                                            <div class="text-danger">
                                                <?= $validation->getError('pincode'); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="center-buttons">
                                <a href="<?php echo base_url('register'); ?>" class="quick-btn gray-btn" >CANCEL</a>

                                                <button type="submit"  class="quick-btn" id="info_save" value="SUBMIT">SUBMIT</button>
                                                </div>
                                    </div>
                                </div>
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
            </div>   
            </div>   
            </div>   
</div>

@endsection
@push('script')
<link href="<?= base_url() . 'assets' ?>/css/select2.min.css" rel="stylesheet">
<!-- form--end  -->
<script src="<?= base_url() . 'assets/newAdmin/' ?>js/jquery351.min.js"></script>
<script src="<?= base_url() . 'assets/newAdmin/' ?>js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url() . 'assets/newAdmin/' ?>js/general.js"></script>
<script src="<?= base_url() . 'assets' ?>/vendors/jquery/dist/jquery.min.js"></script>
<script src="<?= base_url() ?>assets/js/bootstrap-datepicker.min.js"></script> 
<script src="<?= base_url() ?>assets/js/sha256.js"></script>
<script src="<?= base_url() ?>assets/newAdmin/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url() . 'assets' ?>/js/select2.min.js"></script>
 
<!-- Select2 CSS -->
 
<script type="text/javascript">
    var base_url = '<?php echo base_url(); ?>';
</script>
<script>
    function validateInput(event) {
        const input = event.target.value;
        const regex = /^[a-zA-Z@_ ]*$/;
        if (!regex.test(input)) {
            event.target.value = input.replace(/[^a-zA-Z@_ ]+/g, '');
        }
    }
    // $(document).ready(function () {
    //     $('.filter_select_dropdown').select2();
    // });
    $(document).ready(function () {
        var today = new Date();

    $('#date_of_birth').datepicker({
        changeMonth: true,
        changeYear: true,
        yearRange: "-100:-1",
        format: "dd/mm/yyyy",
        // defaultDate: '-40y'
        endDate: today,

        maxDate: new Date
    }); 
});

    $('#state_id').change(function () {
        $('#district_list').val('');
        $('#establishment_list').val('');
        var get_state_id = $(this).val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, state_id: get_state_id},
            url: "<?php echo base_url('register/AdvSignUp/get_dist_list'); ?>",
            success: function (data)
            {
                if (data.indexOf('ERROR') != -1) {
                    $('#msg').show();
                    $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + data + " <span class='close' onclick=hideMessageDiv()>X</span></p>");
                    $('#district_list').html('<option value=""> Select District </option>');

                } else {
                    $('#msg').hide();
                    $('#district_list').html(data);
                }
                $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            },
            error: function () {
                $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
    }); 


     
</script>


<script type="text/javascript">
$(document).ready(function (e) {

    $("#uploadForm").on('submit',(function(e) {
        e.preventDefault();

        var fileInput = document.getElementById('advocate_image');
            var file = fileInput.files[0];

            // Allowed file types
            var allowedTypes = ['image/jpeg', 'image/jpg'];
            var maxSize = 1024 * 1024; // 1MB

            if (file) {
                // Check file size
                if (file.size > maxSize) {
                    alert("File size must be maximum 1MB.");
                    fileInput.value = ''; // Clear the file input
                    return false;
                }

                // Check file type
                if (!allowedTypes.includes(file.type)) {
                    alert("Invalid file type. Please upload a JPG or JPEG file.");
                    fileInput.value = ''; // Clear the file input
                    return false;
                }
                $.ajax({
            url: "<?php echo base_url('register/AdvSignUp/upload_photo'); ?>",
            type: "POST",
            data:  new FormData(this),
            contentType: false,
            cache: false,
            processData:false,
            success: function(data)
            {
                var resArr = data.split('@@@');
                if (resArr[0] == 1) {
                    // $('#msg').show();
                    // $(".form-response").html("<p class='text-center alert alert-danger flashmessage message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' >X</span></p>");
                    alert(resArr[1]);
                }else{
                    $("#targetLayer").html(data);
                }
            },
            error: function() 
            {
            }           
       });
            } 
        
    }));


});
</script> 

<?php 
// Get old state from session flashdata if available
$old_state_id = session()->getFlashdata('old_state_id');
?>

<script type="text/javascript">
    // Get old state ID from PHP
    var oldStateId = '<?= isset($old_state_id) ? $old_state_id : ''; ?>';

    // If oldStateId exists, call the getdist function and trigger AJAX call
    if (oldStateId) { 

        // Trigger the AJAX request to get the district list
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, state_id: oldStateId},
            url: "<?= base_url('register/AdvSignUp/get_dist_list'); ?>", // Correct PHP echo
            success: function (data) {
                if (data.indexOf('ERROR') != -1) {
                    $('#msg').show();
                    $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + data + " <span class='close' onclick=hideMessageDiv()>X</span></p>");
                    $('#district_list').html('<option value=""> Select District </option>');
                } else {
                    $('#msg').hide();
                    $('#district_list').html(data);
                }

                // Refresh the CSRF token
                $.getJSON("<?= base_url() . 'csrftoken'; ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            },
            error: function () {
                // Handle AJAX error
                $.getJSON("<?= base_url() . 'csrftoken'; ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });

        
        <?php 
            // Get old state from session flashdata if available
            $old_district_list = session()->getFlashdata('old_district_list');
            ?>
        var oldDistId = '<?= isset($old_district_list) ? $old_district_list : ''; ?>';
    
        setTimeout(function() {
        if (oldDistId) {
            document.getElementById('district_list').value = oldDistId;
        }
    }, 500);  

    }
    
</script>

@endpush

