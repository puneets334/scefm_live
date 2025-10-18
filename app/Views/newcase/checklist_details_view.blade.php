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
<div class="center-content-inner comn-innercontent">
    <div class="tab-content">
        <div class="tab-pane active" id="home" role="tabpanel" aria-labelledby="home-tab">
            <div class="tab-form-inner">
                <div class="row">
                    <div class="col-12 sm-12 col-md-12 col-lg-12 middleContent-left">
                        <div class="center-content-inner comn-innercontent">
                            <div class="tab-content">
                                <div class="tab-pane active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                    <div class="tab-form-inner">
                                        <div class="row">
                                            <?php
                                            $this->ChecklistModel = new \App\Models\NewCase\ChecklistModel();
                                            $this->Common_model = new \App\Models\Common\CommonModel();
                                            $case_details = $this->Common_model->get_subject_category_casetype_court_fee($_SESSION['efiling_details']['registration_id']);
                                            $checklist_data = $this->ChecklistModel->get_checklist_data_by_efiling_for_type_id($case_details[0]['sc_case_type_id']);
                                            $caseName = $this->ChecklistModel->get_sci_case_type_name_by_id($case_details[0]['sc_case_type_id']);
                                            ?>
                                            <div class="table-responsive">
                                                <h2 class="mt-5"><?php echo ($checklist_data[0]['sc_case_type_id'] == 999999) ? 'Common Checklist' : $caseName->casename; ?></h2>
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
                                                            <th>Complied</th>
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
                                                            if ($answer[$checklist['id']] == 1) {
                                                                ?>
                                                                <tr>
                                                                    <td>
                                                                        <?php
                                                                        if ($checklist['question_no'] != $prev) {
                                                                            echo !empty($checklist['question_no']) ? $checklist['question_no'] : '';
                                                                        }
                                                                        $prev = $checklist['question_no'];
                                                                        ?>
                                                                    </td>
                                                                    <td><?php echo !empty($checklist['sub_question_no']) ? $checklist['sub_question_no'] . '.' : ''; ?></td>
                                                                    <td>&nbsp;<?php echo !empty($checklist['question']) ? $checklist['question'] : ''; ?></td>
                                                                    <td class="pull-center"><?php echo (!empty($answer) && is_array($answer) && isset($answer[$checklist['id']]) && $answer[$checklist['id']] == 1) ? 'Yes' : 'No'; ?></td>
                                                                </tr>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <?php if ($case_details[0]['subcode1'] == 8) { ?>
                                                <h2>Public Interest Litigation</h2>
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
                                                                <th>Complied</th>
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
                                                                if ($answer[$checklist['id']] == 1) {
                                                                    ?>
                                                                    <tr>
                                                                        <td>
                                                                            <?php
                                                                            if ($checklist['question_no'] != $prev) {
                                                                                echo !empty($checklist['question_no']) ? $checklist['question_no'] : '';
                                                                            }
                                                                            $prev = $checklist['question_no'];
                                                                            ?>
                                                                        </td>
                                                                        <td><?php echo !empty($checklist['sub_question_no']) ? $checklist['sub_question_no'] . '.' : ''; ?></td>
                                                                        <td>&nbsp;<?php echo !empty($checklist['question']) ? $checklist['question'] : ''; ?></td>
                                                                        <td class="pull-center"><?php echo (!empty($answer) && is_array($answer) && isset($answer[$checklist['id']]) && $answer[$checklist['id']] == 1) ? 'Yes' : 'No'; ?></td>
                                                                    </tr>
                                                                    <?php
                                                                }
                                                            }    
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            <?php } ?>
                                            <h2>Annexure D</h2>
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
                                                            <th>Complied</th>
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
                                                            if ($answer[$checklist['id']] == 1) {
                                                                ?>
                                                                <tr>
                                                                    <td>
                                                                        <?php
                                                                        if ($checklist['question_no'] != $prev) {
                                                                            echo !empty($checklist['question_no']) ? $checklist['question_no'] : '';
                                                                        }
                                                                        $prev = $checklist['question_no'];
                                                                        ?>
                                                                    </td>
                                                                    <td><?php echo !empty($checklist['sub_question_no']) ? $checklist['sub_question_no'] . '.' : ''; ?></td>
                                                                    <td>&nbsp;<?php echo !empty($checklist['question']) ? $checklist['question'] : ''; ?></td>
                                                                    <td class="pull-center"><?php echo (!empty($answer) && is_array($answer) && isset($answer[$checklist['id']]) && $answer[$checklist['id']] == 1) ? 'Yes' : 'No' ?></td>
                                                                </tr>
                                                                <?php
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