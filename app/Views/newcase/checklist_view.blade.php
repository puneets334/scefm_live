<link href="<?= base_url() . 'assets/newDesign/images/logo.png' ?>" rel="shortcut icon" type="image/png" />
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/bootstrap.min.css" rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/font-awesome.min.css" rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/animate.css" rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/material.css" rel="stylesheet" />
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/style.css" rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">
<link href="<?= base_url() ?>assets/css/jquery-ui.css" rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/black-theme.css" rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/responsive.css" rel="stylesheet">
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
</style>
<?php
$ref_m_usertype_id = !empty(getSessionData('login')['ref_m_usertype_id']) ? getSessionData('login')['ref_m_usertype_id'] : null;
$stage_id = !empty(getSessionData('efiling_details')['stage_id']) ? getSessionData('efiling_details')['stage_id'] : null;
$collapse_class = 'collapse';
if (isset($ref_m_usertype_id) && !empty($ref_m_usertype_id) && $ref_m_usertype_id == USER_ADMIN && isset($stage_id) && !empty($stage_id) && $stage_id == Transfer_to_IB_Stage) {
    $collapse_class = 'collapse in';
}
?>
<div id="loader-wrapper" style="display: none;">
    <div id="loader"></div>
</div>
<div class="center-content-inner comn-innercontent">
    <div class="tab-content">
        <div class="tab-pane active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <div class="tab-form-inner">
                <div class="row">
                    <div class="col-12 sm-12 col-md-12 col-lg-12 middleContent-left">
                        <div class="center-content-inner comn-innercontent">
                            <div class="tab-content">
                                <div class="row">
                                    <h6 class="text-left mt-2"><a href="https://www.sci.gov.in/limitation-calculator/" target="_blank">Limitation Calculator</a></h6>
                                </div>
                                <div class="tab-pane active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                    <?php
                                    $attribute = array('class' => 'form-horizontal', 'name' => 'add_checklist', 'id' => 'add_checklist', 'autocomplete' => 'off');
                                    echo form_open('newcase/add_checklist', $attribute);
                                    ?>
                                    <div class="row">
                                        <h6 class="text-center fw-bold">Check List / Declaration</h6>
                                    </div>
                                    <?php if (session()->getFlashdata('msg')): ?>
                                        <div class="alert alert-success" role="alert">
                                            <?= session()->getFlashdata('msg') ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (session()->getFlashdata('error')): ?>
                                        <div class="alert alert-danger" role="alert">
                                            <?= session()->getFlashdata('error') ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="tab-form-inner">
                                        <div class="row">
                                            <div style="float: right">
                                                <button type="button" id="collapseAll" onclick="toggleAllAccordions()" class="btn btn-primary pull-right mb-2" style="margin-right: 5px;"> Collapse All </button>
                                            </div>
                                            <?php
                                            $this->ChecklistModel = new \App\Models\NewCase\ChecklistModel();
                                            $checklist_data = $this->ChecklistModel->get_checklist_data_by_efiling_for_type_id($case_details[0]['sc_case_type_id']);
                                            ?>
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <div class="accordion view-accordion acrdion-with-edit" id="accordionExample">
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="headingOne">
                                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne"><?php echo  ($checklist_data[0]['sc_case_type_id'] == 999999) ? 'Common Checklist' : $caseName->casename; ?></button>
                                                        </h2>
                                                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                            <div class="accordion-body">
                                                                <div class="x_panel">
                                                                    <div class="table-responsive">
                                                                        <table class="table align-middle table-striped custom-table custom_table_latest">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Sr. No.</th>
                                                                                    <th>Sub Question No.</th>
                                                                                    <th>
                                                                                        <?php if (isset(getSessionData('efiling_details')['efiling_for_type_id']) && !empty(getSessionData('efiling_details')['efiling_for_type_id']) && !empty($checklist_data) && $checklist_data[0]['sc_case_type_id'] == 999999) {
                                                                                            echo 'Checklist to be endorsed by the concerned Advocate-on-Record (Common Checklist)';
                                                                                        } elseif (isset(getSessionData('efiling_details')['efiling_for_type_id']) && !empty(getSessionData('efiling_details')['efiling_for_type_id']) && !empty($checklist_data) && $checklist_data[0]['sc_case_type_id'] == 1) {
                                                                                            echo 'Checklist to be endorsed by the concerned Advocate- on-Record Special Leave to Appeal (Civil)';
                                                                                        } elseif (isset(getSessionData('efiling_details')['efiling_for_type_id']) && !empty(getSessionData('efiling_details')['efiling_for_type_id']) && !empty($checklist_data) && $checklist_data[0]['sc_case_type_id'] == 2) {
                                                                                            echo 'Checklist to be endorsed by the concerned Advocate- on-Record Special Leave to Appeal (Criminal)';
                                                                                        } elseif (isset(getSessionData('efiling_details')['efiling_for_type_id']) && !empty(getSessionData('efiling_details')['efiling_for_type_id']) && !empty($checklist_data) && $checklist_data[0]['sc_case_type_id'] == 3) {
                                                                                            echo 'Checklist to be endorsed by the concerned Advocate- on-Record for Civil Appeal';
                                                                                        } elseif (isset(getSessionData('efiling_details')['efiling_for_type_id']) && !empty(getSessionData('efiling_details')['efiling_for_type_id']) && !empty($checklist_data) && $checklist_data[0]['sc_case_type_id'] == 4) {
                                                                                            echo 'Checklist to be endorsed by the concerned Advocate- on-Record in Criminal Appeal';
                                                                                        } elseif (isset(getSessionData('efiling_details')['efiling_for_type_id']) && !empty(getSessionData('efiling_details')['efiling_for_type_id']) && !empty($checklist_data) && $checklist_data[0]['sc_case_type_id'] == 5 || $checklist_data[0]['sc_case_type_id'] == 6) {
                                                                                            echo 'Checklist to be endorsed by the concerned Advocate-on-Record Writ Petition (Civil/Criminal) and Public Interest Litigation';
                                                                                        } elseif (isset(getSessionData('efiling_details')['efiling_for_type_id']) && !empty(getSessionData('efiling_details')['efiling_for_type_id']) && !empty($checklist_data) && $checklist_data[0]['sc_case_type_id'] == 7 || $checklist_data[0]['sc_case_type_id'] == 8) {
                                                                                            echo 'Checklist to be endorsed by the concerned Advocate- on-Record Transfer Petition (Civil/Criminal)';
                                                                                        } else {
                                                                                            echo "";
                                                                                        } ?>
                                                                                    </th>
                                                                                    <th>Select if complied (Blue denotes compliance)</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <?php
                                                                                $prev = 0;
                                                                                foreach ($checklist_data as $checklist) {
                                                                                    $checked_response = $this->ChecklistModel->get_checklist_data_by_registration_id_and_type(getSessionData('efiling_details')['registration_id'],'CA');
                                                                                    $answer = '';
                                                                                    if (!empty($checked_response) && isset($checked_response) && count($checked_response) > 0 && !empty($checked_response[0]['question_answer'])) {
                                                                                        $answer = json_decode($checked_response[0]['question_answer'], true);
                                                                                    }
                                                                                    ?>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <input type="hidden" name="checklist_id[]" value="<?php echo !empty($checklist['id']) ? $checklist['id'] : ''; ?>" />
                                                                                            <input type="hidden" name="question_no[]" value="<?php echo !empty($checklist['question_no']) ? $checklist['question_no'] : ''; ?>" />
                                                                                            <?php
                                                                                            if ($checklist['question_no'] != $prev) {
                                                                                                echo !empty($checklist['question_no']) ? $checklist['question_no'] : '';
                                                                                            }
                                                                                            $prev = $checklist['question_no'];
                                                                                            ?>
                                                                                        </td>
                                                                                        <td><?php echo !empty($checklist['sub_question_no']) ? $checklist['sub_question_no'] . '.' : ''; ?></td>
                                                                                        <td>
                                                                                            <input type="hidden" name="sub_question_no[]" value="<?php echo !empty($checklist['sub_question_no']) ? $checklist['sub_question_no'] : ''; ?>" />
                                                                                            &nbsp;<?php echo !empty($checklist['question']) ? $checklist['question'] : ''; ?>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div class="form_check_swith_custom form-check form-switch">
                                                                                                <input class="form-check-input" style="font-size: 18px !important;" type="checkbox" id="flexSwitchCheckDefault" name="answer[]" value="<?= $checklist['id'] ?>" <?php echo (!empty($answer) && is_array($answer) && isset($answer[$checklist['id']]) && $answer[$checklist['id']] == 1) ? 'checked' : '' ?> />
                                                                                            </div>
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
                                                    <?php if ($case_details[0]['subcode1'] == 8) { ?>
                                                        <div class="accordion-item">
                                                            <h2 class="accordion-header" id="headingTwo">
                                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">Public Interest Litigation</button>
                                                            </h2>
                                                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                                                <div class="accordion-body">
                                                                    <div class="x_panel">
                                                                        <?php
                                                                        $this->ChecklistModel = new \App\Models\NewCase\ChecklistModel();
                                                                        $checklist_data = $this->ChecklistModel->get_checklist_data_by_efiling_for_sub_cat_id($case_details[0]['subcode1']);
                                                                        ?>
                                                                        <div class="table-responsive">
                                                                            <table class="table align-middle table-striped custom-table custom_table_latest">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>Sr. No.</th>
                                                                                        <th>Sub Question No.</th>
                                                                                        <th>
                                                                                            <?php if (isset(getSessionData('efiling_details')['efiling_for_type_id']) && !empty(getSessionData('efiling_details')['efiling_for_type_id']) && !empty($checklist_data) && $checklist_data[0]['sub_cat_id'] == 8) {
                                                                                                echo 'Checklist to be endorsed by the concerned Advocate-on-Record Writ Petition (Civil/Criminal) and Public Interest Litigation';
                                                                                            } else {
                                                                                                echo "";
                                                                                            }
                                                                                            ?>
                                                                                        </th>
                                                                                        <th>Select if complied (Blue denotes compliance)</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <?php
                                                                                    $prev = 0;
                                                                                    foreach ($checklist_data as $checklist) {
                                                                                        $checked_response = $this->ChecklistModel->get_checklist_data_by_registration_id_and_type(getSessionData('efiling_details')['registration_id'],'IL');
                                                                                        $answer = '';
                                                                                        if (!empty($checked_response) && isset($checked_response) && count($checked_response) > 0 && !empty($checked_response[0]['question_answer'])) {
                                                                                            $answer = json_decode($checked_response[0]['question_answer'], true);
                                                                                        }
                                                                                        ?>
                                                                                        <tr>
                                                                                            <td>
                                                                                                <input type="hidden" name="checklist_id_pil[]" value="<?php echo !empty($checklist['id']) ? $checklist['id'] : ''; ?>" />
                                                                                                <input type="hidden" name="question_no_pil[]" value="<?php echo !empty($checklist['question_no']) ? $checklist['question_no'] : ''; ?>" />
                                                                                                <?php
                                                                                                if ($checklist['question_no'] != $prev) {
                                                                                                    echo !empty($checklist['question_no']) ? $checklist['question_no'] : '';
                                                                                                }
                                                                                                $prev = $checklist['question_no'];
                                                                                                ?>
                                                                                            </td>
                                                                                            <td><?php echo !empty($checklist['sub_question_no']) ? $checklist['sub_question_no'] . '.' : ''; ?></td>
                                                                                            <td>
                                                                                                <input type="hidden" name="sub_question_no_pil[]" value="<?php echo !empty($checklist['sub_question_no']) ? $checklist['sub_question_no'] : ''; ?>" />
                                                                                                &nbsp;<?php echo !empty($checklist['question']) ? $checklist['question'] : ''; ?>
                                                                                            </td>
                                                                                            <td>
                                                                                                <div class="form_check_swith_custom form-check form-switch">
                                                                                                    <input class="form-check-input" style="font-size: 18px !important;" type="checkbox" id="flexSwitchCheckDefault" name="answer_pil[]" value="<?= $checklist['id'] ?>" <?php echo (!empty($answer) && is_array($answer) && isset($answer[$checklist['id']]) && $answer[$checklist['id']] == 1) ? 'checked' : '' ?>/>
                                                                                                </div>
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
                                                    <?php } ?>
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="headingThree">
                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">Annexure D</button>
                                                        </h2>
                                                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                                            <div class="accordion-body">
                                                                <div class="x_panel">
                                                                    <?php
                                                                    $this->ChecklistModel = new \App\Models\NewCase\ChecklistModel();
                                                                    $annexure_data = $this->ChecklistModel->get_annexure_data();
                                                                    ?>
                                                                    <div class="table-responsive">
                                                                        <table class="table align-middle table-striped custom-table custom_table_latest">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Sr. No.</th>
                                                                                    <th>Sub Question No.</th>
                                                                                    <th>
                                                                                        <?php echo 'PROPOSED ADVOCATE\'S CHECK LIST (TO BE CERTIFIED BY ADVOCATE-ON-RECORD)'; ?>
                                                                                    </th>
                                                                                    <th>Select if complied (Blue denotes compliance)</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <?php
                                                                                $prev = 0;
                                                                                foreach ($checklist_data as $checklist) {
                                                                                    $checked_response = $this->ChecklistModel->get_checklist_data_by_registration_id_and_type(getSessionData('efiling_details')['registration_id'],'D');
                                                                                    $answer = '';
                                                                                    if (!empty($checked_response) && isset($checked_response) && count($checked_response) > 0 && !empty($checked_response[0]['question_answer'])) {
                                                                                        $answer = json_decode($checked_response[0]['question_answer'], true);
                                                                                    }
                                                                                    ?>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <input type="hidden" name="checklist_id_annexure[]" value="<?php echo !empty($checklist['id']) ? $checklist['id'] : ''; ?>" />
                                                                                            <input type="hidden" name="question_no_annexure[]" value="<?php echo !empty($checklist['question_no']) ? $checklist['question_no'] : ''; ?>" />
                                                                                            <?php
                                                                                            if ($checklist['question_no'] != $prev) {
                                                                                                echo !empty($checklist['question_no']) ? $checklist['question_no'] : '';
                                                                                            }
                                                                                            $prev = $checklist['question_no'];
                                                                                            ?>
                                                                                        </td>
                                                                                        <td><?php echo !empty($checklist['sub_question_no']) ? $checklist['sub_question_no'] . '.' : ''; ?></td>
                                                                                        <td>
                                                                                            <input type="hidden" name="sub_question_no_annexure[]" value="<?php echo !empty($checklist['sub_question_no']) ? $checklist['sub_question_no'] : ''; ?>" />
                                                                                            &nbsp;<?php echo !empty($checklist['question']) ? $checklist['question'] : ''; ?>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div class="form_check_swith_custom form-check form-switch">
                                                                                                <input class="form-check-input" style="font-size: 18px !important;" type="checkbox" id="flexSwitchCheckDefault" name="answer_annexure[]" value="<?= $checklist['id'] ?>" <?php echo (!empty($answer) && is_array($answer) && isset($answer[$checklist['id']]) && $answer[$checklist['id']] == 1) ? 'checked' : '' ?>/>
                                                                                            </div>
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
                                    </div>
                                    <div class="bottom_content">
                                        <label class="form-label"><input type="checkbox" name="consent" id="consent" value="1" <?php if (!empty($checked_response)) { echo 'checked'; } ?> required />&nbsp;I hereby declare that I have personally verified the petition/appeal and its contents, and that the same is in conformity with the practice and procedure of the Court of Supreme Court Rules, 2013. I further certify that all the requirements mentioned in the relevant Check List(s) have been duly complied with, and that all necessary documents and annexure(s) required for the purpose of hearing of the matter have been properly filed.</label>
                                        <div class="date_cust">
                                            <label class="form-label">Date:-</label>
                                            <span class="form-label"><?= $crnt_dt; ?></span>
                                        </div>
                                        <div class="name_sinc_custom">
                                            <div class="date_cust d-none">
                                                <label class="form-label">Signature</label>
                                                <input class="form-control cus-form-ctrl" type="text" readonly />
                                            </div>
                                            <div class="date_cust">
                                                <ul class="list-unstyled mb-0">
                                                    <li>
                                                        <label class="form-label">Name of Advocate-on-Record</label>
                                                        <span class="form-label"><?= getSessionData('login')['first_name']; ?></span>
                                                    </li>
                                                    <li>
                                                        <label class="form-label">Code of AOR</label>
                                                        <span class="form-label"><?= getSessionData('login')['userid']; ?></span>
                                                    </li>
                                                    <li>
                                                        <label class="form-label">Contact No. & e-mail id</label>
                                                        <span class="form-label"><?= getSessionData('login')['mobile_number']; ?></span> &
                                                        <span class="form-label"><?= getSessionData('login')['emailid']; ?></span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 my-3">
                                            <div class="save-btns text-center">
                                                <a href="<?= base_url('/newcase/courtFee') ?>" class="quick-btn gray-btn" type="button" tabindex='28'>PREVIOUS</a>
                                                <?php if (!empty($checked_response)) { ?>
                                                    <input type="submit" class="btn btn-success" name="check_update" id="check_update" tabindex='26' value="UPDATE">
                                                    <a href="<?= base_url('newcase/view') ?>" class="quick-btn gray-btn" type="button" tabindex='27'>NEXT</a>
                                                <?php } else { ?>
                                                    <input type="submit" class="btn btn-success" name="check_save" id="check_save" value="SAVE" tabindex='26'>
                                                <?php } ?>
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

<script src="<?= base_url() . 'assets/newAdmin/' ?>js/jquery351.min.js"></script>
<script src="<?= base_url() . 'assets/newAdmin/' ?>js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url() . 'assets/newAdmin/' ?>js/general.js"></script>
<script src="<?= base_url() . 'assets' ?>/vendors/jquery/dist/jquery.min.js"></script>
<script src="<?= base_url() ?>assets/js/sha256.js"></script>
<script src="<?= base_url() ?>assets/newAdmin/js/jquery.dataTables.min.js"></script>
@push('script')
<script>
    
    $(document).ready(function() {
        $('#check_update, #check_save').on('click', function(e) {
            e.preventDefault(); // Prevent default form submission
            showLoader();
            // Submit form asynchronously to avoid page reload
            $('#checklist_form').submit();
        });

        function showLoader() {
            $('#loader-wrapper').show();
            setTimeout(function() {
                $('#loader-wrapper').hide();
                hide();
            }, 3000); // Hides the loader after 3 seconds
        }

        function hide() {
            $('.alert-success').hide();
            $('.alert-danger').hide();
            <?php 
                unset($_SESSION['msg']); 
                unset($_SESSION['error']); 
            ?>
        };
    });

    
</script>
@endpush