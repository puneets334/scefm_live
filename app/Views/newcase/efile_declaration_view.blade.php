<link href="<?= base_url() . 'assets/newDesign/images/logo.png' ?>" rel="shortcut icon" type="image/png" />
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/bootstrap.min.css" rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/font-awesome.min.css" rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/animate.css" rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/material.css" rel="stylesheet" />
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/style.css" rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/fullcalendar.css" rel="stylesheet">
<link href="<?= base_url() ?>assets/css/bootstrap-datepicker.css" rel="stylesheet">
<link href="<?= base_url() ?>assets/css/bootstrap-datepicker.min.css" rel="stylesheet">
<link href="<?= base_url() ?>assets/css/jquery-ui.css" rel="stylesheet">
<link href="<?= base_url() . 'assets' ?>/css/select2.min.css" rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/black-theme.css" rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/responsive.css" rel="stylesheet">
<link href="<?= base_url(); ?>assets/css/sweetalert.css" rel="stylesheet">
<style>
    .form_check_swith_custom input {
        margin: 0 !important;
        font-size: 18px !important;
    }
    .form_check_swith_custom {
        min-height: auto !important;
        margin: 0 !important;
    }
    .custom_table_latest td {
        align-items: center;
        vertical-align: middle;
        line-height: normal !important;
    }
    .bottom_content {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        align-items: end;
    }
    .date_cust {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .date_cust label {
        padding: 0;
    }
    .cus-form-ctrl {
        border: none !important;
        margin-top: 15px !important;
        margin-left: -3% !important;
    }
    input[type=number]::-webkit-outer-spin-button,
    input[type=number]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    /* Remove arrows from number inputs (Firefox) */
    input[type=number] {
        -moz-appearance: textfield;
    }
    @media screen and (max-width: 767px) {
        .date_cust {
            flex-wrap: wrap;
        }
    }
    .name_sinc_custom .date_cust label {
        min-width: 200px;
        white-space: nowrap;
        padding: 0;
    }
    .name_sinc_custom {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .date_cust {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-left: 50px;
        font-size: 20px;
    }
    .name_sinc_custom {
        display: flex;
        flex-direction: column;
        gap: 0px;
    }
    .fade:not(.show) {
        opacity: 1;
    }
</style>
<div id="loader-wrapper" style="display: none;">
    <div id="loader"></div>
</div>
<?php
if (!isset($efiling_search_header)) {
    render('newcase.new_case_breadcrumb');
}
?>
<div class="center-content-inner comn-innercontent">
    <div class="card custom-card">
        <div class="tab-content">
            <?php if (session()->getFlashdata('message')): ?>
                <div class="alert" role="alert" style="margin-top: 21px;">
                    <?= session()->getFlashdata('message') ?>
                </div>
            <?php endif; ?>
            <div class="tab-pane active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <?php
                $attribute = array('class' => 'form-horizontal', 'name' => 'add_declaration', 'id' => 'add_declaration', 'autocomplete' => 'off');
                echo form_open('newcase/add_declaration', $attribute);
                ?>
                    <div class="tab-form-inner">
                        <div class="row">
                            <h2 class="text-center fw-bold" style="margin-top: 40px;">Diary No : <?= getSessionData('efiling_details')['diary_no']; ?> of 2025</h2>
                            <h3 style="text-align:center; margin-top: 22px;">DECLARATION</h3>
                        </div>
                        <?php
                        if (!empty($get_declaration_question)) {
                            $check_box = '';
                            if (!empty($check_declaration_answer)) {
                                if ($check_declaration_answer['answer'] == 1) {
                                    $check_box = "checked";
                                }
                            }
                            ?>
                            <div class="bottom_content">
                                <label class="form-label" style="text-align: center;display: block;font-size:18px;line-height: 1.9;font-weight: normal inherit;font-weight: unset !important;margin-left: 45px;margin-right: 45px;">
                                    <input type="hidden" name="question_id" value="<?= $get_declaration_question['id'] ?>">
                                    <input type="hidden" name="question_no" value="<?= $get_declaration_question['question_no'] ?>">
                                    <input type="checkbox" name="consent" id="consent" value="1" <?= $check_box ?> required />&nbsp;<?= $get_declaration_question['question'] ?>
                                </label>
                                <div class="date_cust">
                                    <label class="form-label">Date:-</label>
                                    <input type="text" class="form-control cus-form-ctrl datepick" placeholder="DD-MM-YYYY" maxlength="10" value="<?= $crnt_dt; ?>" readonly>
                                </div>
                                <div class="name_sinc_custom">
                                    <div class="date_cust d-none">
                                        <label class="form-label">Signature</label>
                                        <input class="form-control cus-form-ctrl" type="text" readonly />
                                    </div>
                                    <div class="date_cust">
                                        <label class="form-label">Name of Advocate-on-Record</label>
                                        <input class="form-control cus-form-ctrl" type="text" value="<?= getSessionData('login')['first_name']; ?>" readonly>
                                    </div>
                                    <div class="date_cust">
                                        <label class="form-label">Code of AOR</label>
                                        <input class="form-control cus-form-ctrl" type="text" value="<?= getSessionData('login')['userid']; ?>" readonly>
                                    </div>
                                    <div class="date_cust">
                                        <label class="form-label">Contact No. & e-mail id</label>
                                        <textarea class="form-control cus-form-ctrl" style="min-width: 0% !important;" readonly><?= getSessionData('login')['mobile_number']; ?>&#013;<?= getSessionData('login')['emailid']; ?></textarea>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="row" style="margin-bottom: 21px;">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 my-3">
                                <div class="save-btns text-center">
                                    <a href="<?= base_url('/uploadDocuments') ?>" class="quick-btn gray-btn" type="button" tabindex='28'>PREVIOUS</a>
                                    <?php if (!empty($check_declaration_answer)) { ?>
                                        <input type="submit" class="btn btn-success" id="pet_save" tabindex='26' value="UPDATE">
                                        <a href="<?= base_url('newcase/courtFee') ?>" class="quick-btn gray-btn" type="button" tabindex='27'>NEXT</a>
                                    <?php } else { ?>
                                        <input type="submit" class="btn btn-success" id="pet_save" value="SAVE" tabindex='26'>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
@push('script')
<script src="<?= base_url() . 'assets/newAdmin/' ?>js/jquery351.min.js"></script>
<script src="<?= base_url() . 'assets/newAdmin/' ?>js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url() . 'assets/newAdmin/' ?>js/general.js"></script>
<script src="<?= base_url() . 'assets' ?>/vendors/jquery/dist/jquery.min.js"></script>
<script src="<?= base_url() ?>assets/js/bootstrap-datepicker.js"></script>
<script src="<?= base_url() ?>assets/js/bootstrap-datepicker.min.js"></script>
<script src="<?= base_url() ?>assets/js/sha256.js"></script>
<script src="<?= base_url() ?>assets/newAdmin/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url() . 'assets' ?>/js/select2.min.js"></script>
<script src="<?= base_url(); ?>assets/js/sweetalert.min.js"></script>
<script>
    $(document).ready(function() {
        var today = new Date();
        var startYear = 1984;
        var startDate = new Date(startYear, 1, 1);
        $('#listing_dt').datepicker({
            format: "dd-mm-yyyy",
            showOtherMonths: true,
            selectOtherMonths: true,
            changeMonth: true,
            changeYear: true,
            // endDate: today,
            autoclose: true
        });
    });
    function showLoader() {
        $('#loader-wrapper').show();
        setTimeout(function() {
            $('#loader-wrapper').hide();
        }, 5000); // Hides the loader after 3 seconds
    }
</script>
@endpush