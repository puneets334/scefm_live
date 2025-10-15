<link rel="shortcut icon" href="<?= base_url().'assets/newDesign/images/logo.png' ?>" type="image/png" />
<!-- <link rel="shortcut icon"
    href="<?= base_url() . 'assets/newAdmin/' ?>images/favicon.gif"> -->
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/bootstrap.min.css"
    rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/font-awesome.min.css"
    rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/animate.css"
    rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/material.css"
    rel="stylesheet" />
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/style.css"
    rel="stylesheet">
<link rel="stylesheet"
    type="text/css"
    href="<?= base_url() . 'assets/newAdmin/' ?>css/jquery.dataTables.min.css">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/fullcalendar.css"
    rel="stylesheet">
<link rel="stylesheet"
    href="<?= base_url() ?>assets/css/bootstrap-datepicker.css">
<link rel="stylesheet"
    href="<?= base_url() ?>assets/css/bootstrap-datepicker.min.css">
<link rel="stylesheet"
    href="<?= base_url() ?>assets/css/jquery-ui.css">
<link href="<?= base_url() . 'assets' ?>/css/select2.min.css"
    rel="stylesheet">
    <link href="<?= base_url() . 'assets/newAdmin/' ?>css/black-theme.css" rel="stylesheet">
    <link href="<?= base_url() . 'assets/newAdmin/' ?>css/responsive.css" rel="stylesheet">

<div class="center-content-inner comn-innercontent">
    <div class="tab-content">
        <div class="tab-pane active"
            id="home"
            role="tabpanel"
            aria-labelledby="home-tab">
            <?php if (session()->getFlashdata('paymentError')) : ?>
                <div class="alert alert-danger" role="alert">
                    <b><?= session()->getFlashdata('paymentError') ?></b>
                </div>
            <?php endif; ?>
            <?php if(session()->getFlashdata('validation_error') != ''){ ?>
            <div class="row" id="errDiv">
                <div class="alert alert-danger" role="alert" id="alertMsg"><?php echo session()->getFlashdata('validation_error'); ?> </div>
            </div>
            <?php } ?>
            <?php
            $attribute = array('class' => 'form-horizontal', 'name' => 'court_fee_details', 'id' => 'court_fee_details', 'autocomplete' => 'off');
            echo form_open('newcase/courtFee/add_court_fee_details', $attribute);
            ?>
            <div class="tab-form-inner">
                <div class="row">
                    <h6 class="text-center fw-bold mb-2">Pay Court Fee</h6>
                    <div class="table_pay_heading">
                    <h5>No printing charges are required to be paid</h5>
                </div>
                </div>

                <?= ASTERISK_RED_MANDATORY ?>
               
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12" style="display: none;">
                        <!-- <table id="datatable-responsive" class="table table-striped custom-table" cellspacing="0" width="100%"> -->
                        <table id="datatable-responsive" class="table table-striped custom-table first-th-left" cellspacing="0" width="100%">

                            <thead>
                                <tr class="success">
                                    <th>Cost Per Page (₹)</th>
                                    <th>Uploaded Page(s)</th>
                                    <th>Total Cost (₹)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <?php
                                    $printing_cost = getSessionData('estab_details')['printing_cost'];
                                    $case_type_id = 0;
                                    $sc_case_type_id = 0;
                                    if (!empty($court_fee_list1)) {
                                        $case_type_id = $court_fee_list1[0]['sc_case_type_id'];
                                    }
                                    $printing_cost_total = (int) $uploaded_pages_count * (int) getSessionData('estab_details')['printing_cost'];
                                    $printing_cost = getSessionData('estab_details')['printing_cost'];
                                    ?>
                                    <td data-key="Cost Per Page (₹)"><?php echo_data($printing_cost); ?></td>
                                    <td data-key="Uploaded Page(s)"><?php echo_data($uploaded_pages_count); ?></td>
                                    <td data-key="Total Cost (₹)"><?php echo_data($printing_cost_total); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-xs-12 col-md-8 col-lg-8">
                        <!-- <table id="datatable-responsive1" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%"> -->
                        <table id="datatable-responsive1" class="table table-striped custom-table first-th-left" cellspacing="0" width="100%">
                        
                            <thead>
                                <tr class="success">
                                    <th>#</th>
                                    <th>Court Fee Details </th>
                                    <th style="text-align: center">Amount ( ₹ )</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $if_sclsc = (!empty(getSessionData('efiling_details')['if_sclsc']) && (getSessionData('efiling_details')['if_sclsc'] == 1)) ? getSessionData('efiling_details')['if_sclsc'] : 0;
                                //Code added for to make 0 court fee for SCLSC cases on 25072023 by Anshu and KBP
                                $sr_no = 1;
                                $display_vakalatnama = 'N';
                                $no_of_lower_court_order_challanged = 0;
                                $total_petitioners = 0;
                                if (!empty($court_fee_list3))
                                    $trial_court_order_challanged_for_caveat = $court_fee_list3[0]['trial_court_order_challanged_for_caveat'];
                                else
                                    $trial_court_order_challanged_for_caveat = 0;
                                if (!empty($court_fee_list1)) {
                                    $no_of_lower_court_order_challanged = $court_fee_list1[0]['order_challanged'];
                                    $total_petitioners = $court_fee_list1[0]['total_petitioners'];
                                } else {
                                    $no_of_lower_court_order_challanged = 0;
                                    $total_petitioners = 0;
                                }
                                $case_nature = !empty($court_fee_list1) ? $court_fee_list1[0]['nature'] : '';
                                $registration_id = getSessionData('efiling_details')['registration_id'];
                                $Common_model = new \App\Models\Common\CommonModel();
                                $Get_details_model = new \App\Models\NewCase\GetDetailsModel();
                                if (empty($case_nature)) {
                                    if (getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_CAVEAT) {
                                        $arr = array();
                                        $arr['registration_id'] = $registration_id;
                                        $arr['step'] = 6;
                                        $caveat_details = $Common_model->getCaveatDataByRegistrationId($arr);
                                        if (!empty($caveat_details[0]))
                                            $case_nature = $caveat_details[0]['nature'];
                                    }
                                }
                                $base_disposed_cases = $Get_details_model->get_subordinate_court_details($registration_id);
                                $lower_court_type = (is_array($base_disposed_cases) && !empty($base_disposed_cases)) ? $base_disposed_cases[0]['court_type'] : null;
                                $lower_court_case_type_id = (is_array($base_disposed_cases) && !empty($base_disposed_cases)) ? $base_disposed_cases[0]['case_type_id'] : null;
                                $establishment_code = (is_array($base_disposed_cases) && !empty($base_disposed_cases)) ? $base_disposed_cases[0]['estab_code'] : null;
                                if (!empty(getSessionData('efiling_details')['if_sclsc']) && (getSessionData('efiling_details')['if_sclsc'] == 1)) {
                                    $case_nature = 'R';
                                    //SET case nature as Criminal to mke court fee 0 if is_slsc selected by AOR/PIP
                                }
                                $allowed_case_type_id_CRL = array();
                                if (is_array($court_fee_list1)) {
                                    // foreach ($court_fee_list1 as $row) {
                                    $allowed_case_type_id_CRL = array(2, 4, 6, 8, 10, 12, 14, 16, 18, 20, 26, 28, 33, 35, 36, 41, 19); // ALl Criminal Casetype with Contempt Petition (Civil) 19
                                    foreach ($court_fee_list1 as $row)  {
                                        $base_case_subject_category_details = get_challanged_sc_base_case_details($registration_id, $row['sc_case_type_id']);
                                        if ($row['sc_case_type_id'] == '9' || $row['sc_case_type_id'] == '25' || $row['sc_case_type_id'] == '39')
                                        // for Review Petition , Curative Petition,Contempt petition(came from lower court) and MA : Court Fee is to be paid same as having paid in the Main/base Matter
                                        {
                                            $base_case_subject_category_details = get_challanged_sc_base_case_details($registration_id, $row['sc_case_type_id']);
                                            $subject_category = $base_case_subject_category_details['submaster_id'];
                                            $subcode1 = $base_case_subject_category_details['subcode1'];
                                            // $sc_case_type_id = $base_case_subject_category_details['case_type_id'];
                                            $case_type_id_CRL = $base_case_subject_category_details['case_type_id'];
                                            if (in_array($case_type_id_CRL, $allowed_case_type_id_CRL)) {
                                                $case_nature = 'R';
                                                $sc_case_type_id = $case_type_id_CRL;
                                            } else if ($row['sc_case_type_id'] == 39) {
                                                $sc_case_type_id = $row['sc_case_type_id'];
                                            } else {
                                                $sc_case_type_id = $case_type_id_CRL;
                                            }
                                        } else {
                                            $subject_category = $row['subject_cat']; //submaster id
                                            $subcode1 = $row['subcode1'];
                                            $sc_case_type_id = $row['sc_case_type_id'];
                                        }
                                        // $processing_charges = 0;
                                        // if($sc_case_type_id==23){
                                        //     $processing_charges = PROCESSING_CHARGES_FOR_23;
                                        // }
                                        // if($sc_case_type_id==24){
                                        //     $processing_charges = PROCESSING_CHARGES_FOR_24;
                                        // }
                                        // if($sc_case_type_id==19 || $sc_case_type_id==2 || $sc_case_type_id==4 || $sc_case_type_id==10)
                                        if (in_array($sc_case_type_id, $allowed_case_type_id_CRL))
                                        {
                                            $case_nature = 'R';
                                            //if the contempt petition casetype is selected then it can be treated as criminal matters thus no doc fees will be applicable
                                        }
                                        // echo $subject_category.'#'.$subcode1.'#'.$sc_case_type_id;
                                        $no_of_lower_court_order_challanged = $row['order_challanged'];
                                        $display_vakalatnama = 'Y';
                                        //  Vakalatnama need to add automatically once subject category selected
                                        ?>
                                        <tr style="color: #0d6aad;size: 20px;">
                                            <td data-key="#"><?= $sr_no ?></td>
                                            <td data-key="Court Fee Details ">
                                                Case Nature : <?php if ($row['nature'] == 'C') {
                                                                    echo 'Civil';
                                                                } else {
                                                                    echo 'Criminal';
                                                                }; ?>&nbsp;, Case Type : <?= $row['casename'] ?><br>
                                                Subject Category : <?= $row['sub_name1'] ?><br>
                                                <?php if (!empty($row['category_sc_old'])) { ?>
                                                    (<?= $row['category_sc_old'] ?>)
                                                <?php } ?>
                                                <?= $row['sub_name2'] ?>
                                                <?= $row['sub_name3'] ?>
                                                <?= $row['sub_name4'] ?>
                                                <?php if (($subject_category == '222' || $sc_case_type_id == '7') && $row['court_fee_calculation_helper_flag'] == 'Y') {
                                                ?>(Matrimonial Case)
                                            <?php }
                                                //subcode 1=8 belongs to Letter Petition & Pil Matters
                                                if ((int)$row['order_challanged'] > 0 &&  $sc_case_type_id != '5' && ($subcode1 != '8' || $sc_case_type_id == '7') &&  $sc_case_type_id != '19' && $case_type_id != 39) { ?>
                                                <br>
                                                (No. of order Challanged
                                                : <?= (int)$row['order_challanged']; ?> => Total Court Fee=Court fee * <?= (int)$row['order_challanged']; ?>)
                                                <?php }
                                                if ($subcode1 == '8' && $subject_category != '133' && $sc_case_type_id != '7'  && $sc_case_type_id != '23' && $sc_case_type_id != '24') {
                                                    if ($sc_case_type_id == 1) { ?>
                                                    <br>
                                                    (No. of order Challanged
                                                    : <?= (int)$row['order_challanged']; ?> => Total Court Fee=1500 * <?= (int)$row['order_challanged']; ?>)
                                                <?php
                                                    } elseif ($sc_case_type_id == 3) { ?>
                                                    <br>
                                                    (No. of order Challanged
                                                    : <?= (int)$row['order_challanged']; ?> => Total Court Fee=5000 * <?= (int)$row['order_challanged']; ?>)
                                                <?php
                                                    } else {
                                                        $court_fee1 = 500 * $row['total_petitioners'];
                                                ?> <br>
                                                    (No. of Petitioners
                                                    : <?= (int)$row['total_petitioners']; ?> => Total Court Fee=500 * <?= (int)$row['total_petitioners']; ?>)
                                                <?php }
                                                }
                                                if ($sc_case_type_id == '5') { ?>
                                                <br>
                                                (No. of Petitioners
                                                : <?= (int)$row['total_petitioners']; ?> => Total Court Fee=500 * <?= (int)$row['total_petitioners']; ?>)
                                            <?php } ?>
                                            </td>
                                            <td data-key="Amount ( ₹ )" align="center">
                                                <?php
                                                // if($sc_case_type_id==19 || $sc_case_type_id==2 || $sc_case_type_id==4 || $sc_case_type_id==10)
                                                if (in_array($sc_case_type_id, $allowed_case_type_id_CRL))
                                                {
                                                    $case_nature = 'R';
                                                    //if the contempt petition casetype is selected then it can be treated as criminal matters thus no doc fees will be applicable
                                                }
                                                if ($case_nature == 'C') {
                                                    $court_fee1 = 0;
                                                    if ($sc_case_type_id == '39' || $case_type_id == '39')
                                                    // 100 court fee will be applicable for the MA added by kbp on 17072023
                                                    {
                                                        $court_fee1 = (int)$court_fee1 + 100;
                                                    } else {
                                                        if ($subcode1 == '8' && $sc_case_type_id != '7' && $sc_case_type_id != '23' && $sc_case_type_id != '24' && $sc_case_type_id != '1' && $sc_case_type_id != '3')
                                                        // for Letter Petition & Pil Matters:130(except submaster id:133) court fee will fixed as 500 * no of petitioners filing the matters. changed on 26/04/2021
                                                        {
                                                            if ($subject_category != '133' && $sc_case_type_id != '7')
                                                                $court_fee1 = 500 * (int)$row['total_petitioners'];
                                                            else
                                                                $court_fee1 = (int)$court_fee1 + 1500;
                                                        } elseif ($sc_case_type_id == '5') {
                                                            $court_fee1 = 500 * (int)$row['total_petitioners'];
                                                        } elseif ($sc_case_type_id == '1' && $case_type_id != '39') {
                                                            if ($subcode1 == '8' || $lower_court_case_type_id == '5' || $lower_court_case_type_id == '7')
                                                                $court_fee1 = 1500 * (int)$no_of_lower_court_order_challanged;
                                                            else
                                                                $court_fee1 = $court_fee1 + $row['court_fee'];
                                                        } elseif ($sc_case_type_id == '3') //civil appeal
                                                        {
                                                            // if ($establishment_code == 'NGTD' || $establishment_code == 'AFT') //490506=Natioan green tribunal , 226817=Armed force tribunal
                                                            if ($establishment_code == 'NGTD' || $establishment_code == 'AFT' || $establishment_code == 'NGTWB') // 490506 = Natioan green tribunal, 226817 = Armed force tribunal, 21945 = NGTWB (NATIONAL GREEN TRIBUNAL, EASTERN ZONE BENCH, KOLKATA)
                                                            {
                                                                $court_fee1 = 1500;
                                                            } elseif ($establishment_code == 'NCDRC') {
                                                                $court_fee1 = 5000;
                                                            } else {
                                                                $court_fee1 = (int)$court_fee1 + 5000;
                                                            }
                                                        } elseif ($sc_case_type_id == '7') // for the casetype-Transfer Petition - if matrimonial then 500 else 2500 court fee will be charged . subject category court fee will not be applicable in the above case as well as for the subject category 1802. Changes done on 16/06/2021.
                                                        {
                                                            if ($row['court_fee_calculation_helper_flag'] == 'Y') {
                                                                $court_fee1 = $court_fee1 + 500; // 500 for matrimonial case
                                                            } else {
                                                                $court_fee1 = $court_fee1 + 2500;
                                                            }
                                                        } elseif ($sc_case_type_id == '19' && $lower_court_type == 4)  // 0 court fee will be applicable for the Contempt petition (Civil) nature cases, if lower court is self i.e. supreme court
                                                        {
                                                            $court_fee1 = 0;
                                                        } elseif ($sc_case_type_id == '23') // election petition
                                                        {
                                                            $court_fee1 = (int)$court_fee1 + 20000;
                                                        } elseif ($sc_case_type_id == '24') // arbitration petition
                                                        {
                                                            $court_fee1 = (int)$court_fee1 + 5000;
                                                        } else {
                                                            if ($subject_category == '222') { // Ordinary Civil Matters : T.P. Under Section 25 of the C.P.C.,subject category:1802
                                                                if ($row['court_fee_calculation_helper_flag'] == 'Y') {
                                                                    $court_fee1 = $court_fee1 + $row['court_fee']; // 500 for matrimonial case
                                                                } else {
                                                                    $court_fee1 = $court_fee1 + 2500;
                                                                }
                                                            } else if ($subject_category == '164')  //for the subject category "HABEAS CORPUS MATTERS" : Nature of the Matter will be Criminal Only so the case nuture condition will not not applicable for the same. changes done on 26/04/21
                                                            {
                                                                if ($subject_category == '130') {
                                                                    if ($sc_case_type_id == '5')
                                                                        $court_fee1 = (int)$court_fee1 + 500; // for Civil writ
                                                                    else
                                                                        $court_fee1 = (int)$court_fee1 + 1500; // for Civil matter(SLP)
                                                                }
                                                            } else {
                                                                $court_fee1 = $court_fee1 + $row['court_fee'];
                                                            }
                                                        }
                                                        if ($no_of_lower_court_order_challanged > 0 && $sc_case_type_id != '5' && ($subcode1 != '8' || $sc_case_type_id == '7')) { //only in writ petition(casetype id :5) and letter petition subject category(sub code1 :8) court fee will calculated multiply by no of petitioners and for the rest multiply by no of order challanged
                                                            if (!($sc_case_type_id == '39' || $case_type_id == '39')) {
                                                                $court_fee1 = (int)$court_fee1 * (int)$no_of_lower_court_order_challanged;
                                                            }
                                                        }
                                                    }
                                                } else {
                                                    //for criminal matter:decided based on case nature
                                                    $court_fee1 = 0;
                                                }
                                                ?>
                                                ₹ <?= $court_fee1; ?>
                                            </td>
                                        </tr>
                                        <!-- <?php /*if (empty(getSessionData('efiling_details')['if_sclsc']) && (getSessionData('efiling_details')['if_sclsc'] != 1)){
                                            if($case_nature == 'C'){
                                             if(COURT_FEE_PROCESSING_CHARGES){ 
                                            if(in_array($sc_case_type_id,PROCESSING_CHARGES_CASE_TYPE)){
                                                $sr_no++;
                                            ?>
                                            <tr style="color: #0d6aad;size: 20px;">
                                                <td data-key="#"><?= $sr_no ?></td>
                                                <td data-key="Court Fee Details ">Processing Charges</td>
                                                <td data-key="Amount ( ₹ )" align="center">₹ <?= (int)($processing_charges); ?></td>
                                            </tr>
                                            <?php } } }
                                            } */?> -->
                                        <?php $sr_no++;
                                    }
                                }
                                $lower_court_entry_count = 0;
                                if (getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_NEW_CASE) {
                                    $lower_court_entry_count = $no_of_lower_court_order_challanged;
                                }
                                if (getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_CAVEAT) {
                                    $lower_court_entry_count = $trial_court_order_challanged_for_caveat;
                                }
                                $all_added_docs_and_ias = array_column($court_fee_list3, 'doccode');
                                if (!in_array('12', $all_added_docs_and_ias) && !in_array('13', $all_added_docs_and_ias)) {
                                    if ($case_type_id != 39) // Vakalatnama will be not add automatically when MA if filed by AOR chnaged on 17072023 by KBP
                                    {
                                        $case_nature_from_icmis = getCaseDetailsWithNatureBasedOnSubjectCategory($registration_id);
                                        if ($case_nature_from_icmis == 'R') {
                                            $case_nature = 'R';
                                        }
                                        foreach ($court_fee_list2 as $row) {
                                            // if($sc_case_type_id==19 || $sc_case_type_id==2 || $sc_case_type_id==4 || $sc_case_type_id==10)
                                            if (in_array($sc_case_type_id, $allowed_case_type_id_CRL))
                                            {
                                                $case_nature = 'R'; //if the contempt petition casetype is selected then it can be treated as criminal matters thus no doc fees will be applicable
                                            }
                                            if ($display_vakalatnama == 'Y' || getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_CAVEAT) {
                                                ?>
                                                <tr style="color: #0055aa;size: 20px;">
                                                    <td data-key="#"><?= $sr_no ?></td>
                                                    <td data-key="Court Fee Details"> <?= $row['docdesc'] ?>
                                                        <?php
                                                        if ($case_type_id == '5') { ?>
                                                            (No. of Petitioners
                                                            : <?= $total_petitioners; ?>)
                                                            <?php } else {
                                                            if ($lower_court_entry_count > 0) { ?>
                                                                (No. of order Challanged
                                                                : <?= $lower_court_entry_count; ?>)
                                                        <?php }
                                                        } ?>
                                                    </td>
                                                    <td data-key='Amount ( ₹ )' align="center">₹
                                                        <?php
                                                        //print_r($case_nature);
                                                        if (($case_nature == 'C' && getSessionData('efiling_details')['ref_m_efiled_type_id'] != E_FILING_TYPE_CAVEAT) || ($case_nature == 'C' && getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_CAVEAT)) // CHNAGE BY KBPUJARI ON 28062023 TO MAKE 0 COURT FEE FOR THE CAVEAT FILING IF THE CASE TYPE IS SELECTED AS CRIMINAL
                                                            $doc_court_fee = $row['docfee'];
                                                        else
                                                            $doc_court_fee = 0;
                                                        if ($case_type_id == '5') {
                                                            echo (int)$doc_court_fee * (int)$total_petitioners;
                                                        } else {
                                                            if ($lower_court_entry_count > 0) {
                                                                echo (int)$doc_court_fee * (int)$lower_court_entry_count;
                                                            } else {
                                                                echo  (int)$doc_court_fee;
                                                            }
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                        <?php $sr_no++;
                                            }
                                        }
                                    }
                                }
                                $doc_list_with_letter_of_inspection_file = array(150);
                                $doc_list_no_of_non_party_appellant = array(895);
                                $doc_list_with_affidavit = array(20, 50, 190);
                                $doc_list_of_affidavit_attested_place = array(30, 60, 90, 110, 170, 220);
                                $doc_list_for_per_petition_calculation = array(84);
                                $doc_list_for_per_lower_court_order_challanged_number = array(85, 86, 87, 817, 120, 827, 828, 829, 830, 831, 835, 8328, 130);
                                $doc_list_of_zero_doc_fee_for_criminal_matter = array(842, 8120, 8137, 8144, 8152, 8153, 8160, 8252, 8271, 8302, 8324, 8346, 8368, 8385);
                                foreach ($court_fee_list3 as $row) {
                                    $no_of_affidavit_copies = $row['no_of_affidavit_copies'];
                                    // if($sc_case_type_id==19 || $sc_case_type_id==2 || $sc_case_type_id==4 || $sc_case_type_id==10)
                                    if (in_array($sc_case_type_id, $allowed_case_type_id_CRL))
                                    {
                                        $row['nature'] = 'R'; //if the contempt petition casetype is selected then it can be treated as criminal matters thus no doc fees will be applicable
                                    }
                                    if (!empty(getSessionData('efiling_details')['if_sclsc']) && (getSessionData('efiling_details')['if_sclsc'] == 1)) {
                                        $row['nature'] = 'R'; //SET case nature as Criminal to mke court fee 0 if is_slsc selected by AOR/PIP
                                    }
                                    $doc = (int)$row['doccode'] . (int)$row['doccode1'];
                                    $case_nature_from_icmis = getCaseDetailsWithNatureBasedOnSubjectCategory($registration_id);
                                    if ($case_nature_from_icmis == 'R') {
                                        $case_nature = 'R';
                                    }
                                    if (in_array((int)$doc, $doc_list_with_affidavit, TRUE)) {
                                        $doc_court_fee = 0;
                                        $doc_extra_details = '';
                                        if ($row['court_fee_calculation_helper_flag'] == 'Y') //DOC submit with Affidavit flag
                                        {
                                            if ($row['nature'] == 'C')
                                                $doc_court_fee = $row['docfee'];
                                            else
                                                $doc_court_fee = 0;
                                            $doc_extra_details = ' (Submitted in the form of Affidavit)';
                                        } else {
                                            $doc_court_fee = 0;
                                            $doc_extra_details = ' ( Not submitted in the form of Affidavit)';
                                        }
                                        ?>
                                        <tr style="color: #0055aa;size: 20px;">
                                            <td data-key="#"><?= $sr_no ?></td>
                                            <td data-key="Court Fee Details"> <?= $row['docdesc'] ?><?= $doc_extra_details ?> </td>
                                            <td data-key="Amount ( ₹ )" align="center">₹ <?= $doc_court_fee ?></td>
                                        </tr>
                                    <?php
                                        $sr_no++;
                                    } elseif (in_array((int)$doc, $doc_list_of_affidavit_attested_place, TRUE)) {
                                        $doc_court_fee = 0;
                                        $doc_extra_details = '';
                                        if ($row['nature'] == 'C')
                                            $doc_court_fee = $row['docfee'];
                                        else
                                            $doc_court_fee = 0;
                                        if ($sc_case_type_id == '19' && $doc == '11')  //For Affidavit 0 court fee will be applicable for the Affidavit id - Contempt petition (Civil) nature cases
                                        {
                                            $doc_court_fee = 0;
                                        }
                                        if ($no_of_affidavit_copies > 0) {
                                            $doc_extra_details = ' (No of Copies:' . $no_of_affidavit_copies . ')';
                                        }
                                    ?>
                                        <tr style="color: #0055aa;size: 20px;">
                                            <td data-key="#"><?= $sr_no ?></td>
                                            <td data-key="Court Fee Details"> <?= $row['docdesc'] ?><?= $doc_extra_details ?> </td>
                                            <td data-key="Amount ( ₹ )" align="center">₹
                                                <?php if ($no_of_affidavit_copies > 0)
                                                    echo (int)$doc_court_fee * (int)$no_of_affidavit_copies;
                                                else
                                                    echo (int)$doc_court_fee;
                                                ?>
                                            </td>
                                        </tr>
                                    <?php
                                        $sr_no++;
                                    } elseif (in_array((int)$doc, $doc_list_with_letter_of_inspection_file, TRUE)) {
                                        $doc_court_fee = 0;
                                        $doc_extra_details = '';
                                        if ($row['court_fee_calculation_helper_flag'] == 'Y') //Letter submitted as letter of inspection of file
                                        {
                                            if ($row['nature'] == 'C')
                                                $doc_court_fee = $row['docfee'];
                                            else
                                                $doc_court_fee = 0;
                                            $doc_extra_details = ' (Letter of inspection of file)';
                                        } else {
                                            $doc_court_fee = 0;
                                        }
                                    ?>
                                        <tr style="color: #0055aa;size: 20px;">
                                            <td data-key="#"><?= $sr_no;
                                                $doc ?></td>
                                            <td data-key="Court Fee Details"> <?= $row['docdesc'] ?><?= $doc_extra_details ?> </td>
                                            <td data-key="Amount ( ₹ )" align="center">₹ <?= $doc_court_fee ?></td>
                                        </tr>
                                    <?php
                                        $sr_no++;
                                    } elseif (in_array((int)$doc, $doc_list_for_per_petition_calculation, TRUE)) {
                                    ?>
                                        <tr style="color: #0055aa;size: 20px;">
                                            <td data-key="#"><?= $sr_no ?></td>
                                            <td data-key="Court Fee Details"> <?= $row['docdesc'] ?> <br> (No. of petitioners
                                                : <?= $row['total_petitioners']; ?>)
                                            </td>
                                            <td data-key="Amount ( ₹ )" align="center">₹
                                                <?php
                                                if ($row['nature'] == 'C')
                                                    $doc_court_fee = $row['docfee'];
                                                else
                                                    $doc_court_fee = 0;
                                                if ((int)$row['total_petitioners'] > 0)
                                                    echo (int)$doc_court_fee * (int)$row['total_petitioners'];
                                                else
                                                    echo (int)$doc_court_fee; ?>
                                            </td>
                                        </tr>
                                    <?php
                                        $sr_no++;
                                    } elseif (in_array((int)$doc, $doc_list_for_per_lower_court_order_challanged_number, TRUE)) {
                                    ?>
                                        <tr style="color: #0055aa;size: 20px;">
                                            <td data-key="#"><?= $sr_no ?></td>
                                            <td data-key="Court Fee Details"> <?= $row['docdesc'] ?> <br> (No. of order Challanged
                                                : <?= $row['order_challanged']; ?>)
                                            </td>
                                            <td data-key="Amount ( ₹ )" align="center">₹
                                                <?php
                                                if ($row['nature'] == 'C')
                                                    $doc_court_fee = $row['docfee'];
                                                else
                                                    $doc_court_fee = 0;
                                                if ((int)$row['order_challanged'] > 0)
                                                    echo (int)$doc_court_fee * (int)$row['order_challanged'];
                                                else
                                                    echo (int)$doc_court_fee;
                                                ?>
                                            </td>
                                        </tr>
                                    <?php
                                        $sr_no++;
                                    } elseif (in_array((int)$doc, $doc_list_of_zero_doc_fee_for_criminal_matter, TRUE)) {
                                        $doc_court_fee = 0;
                                        if ($row['nature'] == 'C')
                                            $doc_court_fee = (int)$row['docfee'];
                                        else
                                            $doc_court_fee = 0;
                                    ?>
                                        <tr style="color: #0055aa;size: 20px;">
                                            <td data-key="#"><?= $sr_no ?></td>
                                            <td data-key="Court Fee Details"> <?= $row['docdesc'] ?>
                                            </td>
                                            <td data-key="Amount ( ₹ )" align="center">₹ <?= (int)$doc_court_fee; ?></td>
                                        </tr>
                                    <?php
                                        $sr_no++;
                                    } elseif (in_array((int)$doc, $doc_list_no_of_non_party_appellant, TRUE)) {
                                    ?>
                                        <tr style="color: #0055aa;size: 20px;">
                                            <td data-key="#"><?= $sr_no ?></td>
                                            <td data-key="Court Fee Details"> <?= $row['docdesc'] ?> <br> No of Petitioners / appellant (Non-party)
                                                : <?= $row['no_of_petitioner_appellant']; ?>
                                            </td>
                                            <td data-key="Amount ( ₹ )" align="center">₹
                                                <?php
                                                if ($row['nature'] == 'C') {
                                                    if ((int)$row['no_of_petitioner_appellant'] > 0) {
                                                        echo (int)$row['docfee'] * (int)$row['no_of_petitioner_appellant'];
                                                    } else {
                                                        echo (int)$row['docfee'];
                                                    }
                                                } else
                                                    $doc_court_fee = 0;
                                                ?></td>
                                        </tr>
                                    <?php
                                    } else {
                                        //var_dump($row);
                                        echo $row['no_of_petitioner_appellant'];
                                    ?>
                                        <tr style="color: #0055aa;size: 20px;">
                                            <td data-key="#"><?= $sr_no; ?></td>
                                            <td data-key="Court Fee Details"> <?= $row['docdesc'] ?> </td>
                                            <td data-key="Amount ( ₹ )" align="center">₹
                                                <?php
                                                if (($case_nature == 'C' && getSessionData('efiling_details')['ref_m_efiled_type_id'] != E_FILING_TYPE_CAVEAT) || ($case_nature == 'C' && getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_CAVEAT)) // CHNAGE BY KBPUJARI ON 28062023 TO MAKE 0 COURT FEE FOR THE CAVEAT FILING IF THE CASE TYPE IS SELECTED AS CRIMINAL
                                                    $doc_court_fee = (int)$row['docfee'];
                                                else
                                                    $doc_court_fee = 0; ?>
                                                <?= (int)$doc_court_fee ?></td>
                                        </tr>
                                <?php
                                        $sr_no++;
                                    }
                                }
                                ?>
                                <?php $user_declared_extra_fee = 0;
                                if (is_array($payment_details) && count($payment_details) > 0) {
                                    // pr($payment_details);
                                    foreach ($payment_details as $Efee) {  
                                        $user_declared_extra_fee = $user_declared_extra_fee + (int)$Efee['user_declared_extra_fee'];
                                  } 

                                  if ($Efee['payment_status'] == 'Y' && $Efee['user_declared_extra_fee'] != 0) { ?>
                                    <tr style="color: #0055aa;size: 20px;">
                                        <td data-key="#"><?= $sr_no; ?></td>
                                        <td data-key="Court Fee Details"> Extra Court Fee </td>
                                        <td data-key="Amount ( ₹ )" align="center"><?= $user_declared_extra_fee ?></td>
                                    </tr>
                                <?php $sr_no++;
                                } ?>
                                
                            <?php   }  ?> 
                            <?php /*$court_fee = $court_fee;
                            if (empty(getSessionData('efiling_details')['if_sclsc']) && (getSessionData('efiling_details')['if_sclsc'] != 1)){
                                if($case_nature == 'C'){
                                if(COURT_FEE_PROCESSING_CHARGES){ 
                                    if(in_array($sc_case_type_id,PROCESSING_CHARGES_CASE_TYPE)){
                                        $court_fee = $court_fee + (int)($processing_charges);
                                    } } } }*/ ?>
                            <tr>
                                <td data-key="#"></td>
                                <td data-key="Court Fee Details"></td>
                                <td data-key="Amount ( ₹ )" colspan="3" style="text-align: center;padding-right: 71px;">
                                <?php if (@(int)$Efee['user_declared_extra_fee'] > 0) { ?>
                                    <label style="margin-top: 10px; font-weight: bold">Total : ₹ <?= $court_fee + $user_declared_extra_fee ?></label>
                                <?php } else { ?>
                                    <label style="margin-top: 10px; font-weight: bold">Total : ₹ <?= $court_fee ?></label>
                                <?php } ?>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-sm-12 col-xs-12 col-md-4 col-lg-4">
                        <div class="row justify-content-end align-items-end h-100 court-fee-sec">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-10 court-fee-box">
                            <div class="form-group">
                            <label class="form-label">Want to pay more Court Fee ( ₹ ) <span style="color: red" class="astriks">*</span> :</label>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-6">
                                <input type="text" onKeyPress="edValueKeyPress()" onKeyUp="edValueKeyPress(this)" id="user_declared_extra_fee" name="user_declared_extra_fee" minlength="1" maxlength="5" class="form-control cus-form-ctrl " placeholder="Court Fee Amount" value="0">
                            </div>
                        </div>
                        <?php
                        $court_fee_already_paid = 0;
                        $user_declared_extra_fee = 0;
                        $user_declared_court_fee = 0;
                        $uploaded_pages = 0;
                        $printing_cost_already_paid = 0;
                        if (is_array($payment_details) && count($payment_details) > 0) {
                            foreach ($payment_details as $payment) {
                                if ($payment['payment_status'] == 'Y') {
                                    $court_fee_already_paid = $court_fee_already_paid + (int)$payment['received_amt'];
                                    $user_declared_court_fee = $user_declared_court_fee + (int)$payment['user_declared_court_fee'];
                                    $user_declared_extra_fee = $user_declared_extra_fee + (int)$payment['user_declared_extra_fee'];
                                    $uploaded_pages = $uploaded_pages + (int)$payment['uploaded_pages'];
                                    $printing_cost_already_paid = $printing_cost_already_paid + (int)$payment['printing_total'];
                                }
                            }
                        }
                        $court_fee_already_paid_without_extra_fee = $court_fee_already_paid - $user_declared_extra_fee;
                        $uploaded_pages_count_pending = $uploaded_pages_count - $uploaded_pages;
                        $printing_cost_to_be_paid = (int)$uploaded_pages_count_pending * (float)$printing_cost;
                        $printing_cost_total = (int)$printing_cost_to_be_paid + (int)$printing_cost_already_paid;
                        $total_court_fee = (int)$court_fee + (int)$printing_cost_to_be_paid + (int)$printing_cost_already_paid;
                        $user_declared_court_fee = $court_fee - $user_declared_court_fee;
                        $pending_court_fee = $total_court_fee - $court_fee_already_paid_without_extra_fee;
                        ?>
                        <div class="mb-3">
                            <label for=""
                                class="form-label">Court Fee (To Pay) ( ₹ ) <span style="color: red" class="astriks">*</span></label>
                          <div class="col-md-12 col-12 col-lg-12 col-xl-12">
                          <input type="hidden" id="print_fee_details" name="print_fee_details" value="<?php echo_data(url_encryption($uploaded_pages_count_pending . '$$' . $printing_cost_total . '$$' . $printing_cost_already_paid . '$$' . $printing_cost_to_be_paid . '$$' . $user_declared_court_fee)); ?>" />
                            <input type="hidden" id="usr_court_fee_fixed" name="usr_court_fee_fixed" minlength="1" maxlength="5" class="form-control cus-form-ctrl " placeholder="Court Fee Amount" value="<?= $pending_court_fee; ?>" readonly />
                            <input type="text" id="usr_court_fee" name="usr_court_fee" minlength="1" maxlength="5" class="form-control cus-form-ctrl " placeholder="Court Fee Amount" value="<?= $pending_court_fee; ?>" readonly />
                          </div>
                            <label style="margin-top: 10px;font-weight: bold">Total Court Fee : <?= $court_fee ?> + <?= $printing_cost_total; ?> = ₹ <?= $total_court_fee ?></label>
                            <br>
                            <label style="margin-top: 2px;font-weight: bold;color: #2c4762">Court Fee Already paid: ₹ <?= $court_fee_already_paid ?> </label>
                        </div>
                            </div>
                        </div>                        
                    </div>                
                </div>
                <!-- --- Below is Previous Code  -->
                <!-- <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="form-group">
                            <label class="">Want to pay more Court Fee ( ₹ ) <span style="color: red" class="astriks">*</span> :</label>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-6">
                                <input type="text" onKeyPress="edValueKeyPress()" onKeyUp="edValueKeyPress(this)" id="user_declared_extra_fee" name="user_declared_extra_fee" minlength="1" maxlength="5" class="form-control cus-form-ctrl " placeholder="Court Fee Amount" value="0">
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-sm-6 col-md-6 col-lg-6">
                        <?php
                        $court_fee_already_paid = 0;
                        $user_declared_extra_fee = 0;
                        $user_declared_court_fee = 0;
                        $uploaded_pages = 0;
                        $printing_cost_already_paid = 0;
                        if (is_array($payment_details) && count($payment_details) > 0) {
                            foreach ($payment_details as $payment) {
                                if ($payment['payment_status'] == 'Y') {
                                    $court_fee_already_paid = $court_fee_already_paid + (int)$payment['received_amt'];
                                    $user_declared_court_fee = $user_declared_court_fee + (int)$payment['user_declared_court_fee'];
                                    $user_declared_extra_fee = $user_declared_extra_fee + (int)$payment['user_declared_extra_fee'];
                                    $uploaded_pages = $uploaded_pages + (int)$payment['uploaded_pages'];
                                    $printing_cost_already_paid = $printing_cost_already_paid + (int)$payment['printing_total'];
                                }
                            }
                        }
                        $court_fee_already_paid_without_extra_fee = $court_fee_already_paid - $user_declared_extra_fee;
                        $uploaded_pages_count_pending = $uploaded_pages_count - $uploaded_pages;
                        $printing_cost_to_be_paid = (int)$uploaded_pages_count_pending * (float)$printing_cost;
                        $printing_cost_total = (int)$printing_cost_to_be_paid + (int)$printing_cost_already_paid;
                        $total_court_fee = (int)$court_fee + (int)$printing_cost_to_be_paid + (int)$printing_cost_already_paid;
                        $user_declared_court_fee = $court_fee - $user_declared_court_fee;
                        $pending_court_fee = $total_court_fee - $court_fee_already_paid_without_extra_fee;
                        ?>
                        <div class="mb-3">
                            <label for=""
                                class="form-label">Court Fee (To Pay) ( ₹ ) <span style="color: red" class="astriks">*</span></label>
                          <div class="col-md-6 col-12 col-lg-6 col-xl-6">
                          <input type="hidden" id="print_fee_details" name="print_fee_details" value="<?php echo_data(url_encryption($uploaded_pages_count_pending . '$$' . $printing_cost_total . '$$' . $printing_cost_already_paid . '$$' . $printing_cost_to_be_paid . '$$' . $user_declared_court_fee)); ?>" />
                            <input type="hidden" id="usr_court_fee_fixed" name="usr_court_fee_fixed" minlength="1" maxlength="5" class="form-control cus-form-ctrl " placeholder="Court Fee Amount" value="<?= $pending_court_fee; ?>" readonly />
                            <input type="text" id="usr_court_fee" name="usr_court_fee" minlength="1" maxlength="5" class="form-control cus-form-ctrl " placeholder="Court Fee Amount" value="<?= $pending_court_fee; ?>" readonly />
                          </div>
                            <label style="margin-top: 10px;font-weight: bold">Total Court Fee : <?= $court_fee ?> + <?= $printing_cost_total; ?> = ₹ <?= $total_court_fee ?></label>
                            <br>
                            <label style="margin-top: 2px;font-weight: bold;color: #2c4762">Court Fee Already paid: ₹ <?= $court_fee_already_paid ?> </label>
                            {{-- <input type="text"
                                                            class="form-control cus-form-ctrl"
                                                            id="exampleInputEmail1"
                                                            placeholder=""> --}}
                        </div>
                    </div>
                </div> -->
                <label style="margin-top: 2px; font-weight: bold; color: red;"><i class="fa fa-disclaimer"></i>"THE COURT FEE CALCULATED AND SHOWN IN THIS PAGE IS AS PER THE CASE TYPE, EARLIER COURT AND CASE CATEGORY. ANY DEFICIT COURT FEES DEFECT MAY BE RAISED AT THE SCRUTINY STAGE AND DEFICIT PAYMENT TO BE PAID ACCORDINGLY."</label>
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 my-3">
                    <div class="row">
                        <div class="progress" style="display: none;">
                            <div class="progress-bar progress-bar-success myprogress" role="progressbar" value="0" max="100" style="width:0%">0%</div>
                        </div>
                    </div>
                    <div class="save-btns text-center">
                        <?php
                        if (getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_NEW_CASE) {
                            $prev_url = base_url('documentIndex');
                            //$next_url = base_url('affirmation');
                            $next_url = base_url('newcase/view');
                        } else if (getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_CAVEAT) {
                            $prev_url = base_url('documentIndex');
                            $next_url = base_url('caveat/view');
                        } else {
                            $prev_url = '#';
                            $next_url = '#';
                        }
                        ?>
                        <a href="<?= $prev_url ?>" class="btn quick-btn gray-btn" type="button">PREVIOUS</a>
                        <input type="submit" class="btn btn-success pay_fee" style="display: none;" id="pay_fee" name="submit" value="PAY">
                        <?php
                        if ((isset($payment_details['0']['payment_status']) && !empty($payment_details['0']['payment_status']) && $payment_details['0']['payment_status'] == 'Y') || ($pending_court_fee == 0)) { ?>
                            <a href="<?= $next_url ?>" class="btn quick-btn pay_fee_next" type="button">NEXT</a>
                        <?php } ?>
                    </div>
                </div>
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 my-3">
                    <?php
                    if (isset($payment_details) && !empty($payment_details)) {
                        render('shcilPayment.payment_list_view', ['payment_details' => $payment_details]);
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url() . 'assets/newAdmin/' ?>js/jquery351.min.js"></script>

<script src="<?= base_url() . 'assets/newAdmin/' ?>js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url() . 'assets/newAdmin/' ?>js/general.js"></script>
<script src="<?= base_url() . 'assets' ?>/vendors/jquery/dist/jquery.min.js"></script>
<script src="<?= base_url() ?>assets/js/bootstrap-datepicker.min.js"></script>
<script src="<?= base_url() ?>assets/js/sha256.js"></script>
<script src="<?= base_url() ?>assets/newAdmin/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url() . 'assets' ?>/js/select2.min.js"></script>
<script type="text/javascript" src="<?= base_url() . 'assets' ?>/js/jquery.validate.js"></script>.
<script src="<?= base_url(); ?>assets/js/sweetalert.min.js"></script>
<link rel="stylesheet" href="<?= base_url(); ?>assets/css/sweetalert.css">
<script>
    var pending_court_fee = "<?= $pending_court_fee; ?>";
    if (pending_court_fee > 0) {
        $('.pay_fee').show();
        $('.pay_fee_next').hide();
    } else {
        $('.pay_fee').hide();
        $('.pay_fee_next').show();
    }

    function edValueKeyPress(txb) {
        txb.value = txb.value.replace(/[^0-9]/g, '');
        var usr_court_fee = $('#usr_court_fee_fixed').val();
        var morefee = $('#user_declared_extra_fee').val();
        if (morefee == '') {
            morefee = parseInt(0);
            $('#usr_court_fee').val(usr_court_fee)
        }
        var usr_court_fee_final = parseInt(usr_court_fee) + parseInt(morefee);
        $('#usr_court_fee').val(usr_court_fee_final);
        if (usr_court_fee_final > 0) {
            $('.pay_fee').show();
            $('.pay_fee_next').hide();
        } else {
            $('.pay_fee').hide();
            $('.pay_fee_next').show();
        }
    }

    function ActionToTrash(trash_type) {
        event.preventDefault();
        var trash_type = trash_type;
        var url = "";
        if (trash_type == '') {
            swal("Cancelled", "Your imaginary file is safe :)", "error");
            return false;
        } else if (trash_type == 'UAT') {
            url = "<?php echo base_url('userActions/trash'); ?>";
        } else if (trash_type == 'SLT') {
            url = "<?php echo base_url('stage_list/trash'); ?>";
        } else if (trash_type == 'EAT') {
            url = "<?php echo base_url('userActions/trash'); ?>";
        } else {
            swal("Cancelled", "Your imaginary file is safe :)", "error");
            return false;
        }
        //    alert('trash_type'+trash_type+'url='+url);//return false;
        swal({
                title: "Do you really want to trash this E-Filing,",
                text: "once it will be trashed you can't restore the same.",
                type: "warning",
                position: "top",
                showCancelButton: true,
                confirmButtonColor: "green",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                buttons: ["Make Changes", "Yes!"],
                closeOnConfirm: false,
                closeOnCancel: true
            },
            function(isConfirm) {
                if (isConfirm) { // submitting the form when user press yes
                    var link = document.createElement("a");
                    link.href = url;
                    link.target = "_self";
                    link.click();
                    swal({
                        title: "Deleted!",
                        text: "E-Filing has been deleted.",
                        type: "success",
                        timer: 2000
                    });

                } else {
                    //swal({title: "Cancelled",text: "Your imaginary file is safe.",type: "error",timer: 1300});
                }

            });
    }
    var base_url = '<?php echo base_url(); ?>';
    $(".check_stock_holding_payment_status").click(function () {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var order_id = $(this).attr('data-order-id');
        $('.form-responce').remove();
        $(this).append(' <i class="status_refresh fa fa-refresh fa-spin"></i>');
        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, order_id: order_id},
            url: '<?php echo base_url('shcilPayment/paymentCheckStatus'); ?>',
            // url: base_url + "shcilPayment/paymentCheckStatus",
            success: function (data) {
                $('.status_refresh').remove();
                if (data=='SUCCESS|Status Successfully Updated.'){
                    alert(data);
                }else{
                    alert(data);
                }
                window.location.reload();
                $.getJSON(base_url + "csrftoken", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
    });
    $(document).on('click','.verifyFeeData',function(){
        var type = $.trim($(this).attr('data-actionType'));
        var receiptNumber = $.trim($(this).attr('data-transaction_num'));
        //alert(receiptNumber);return false;
        //var receiptNumber ='DLCT0801D2121P';
        var diaryNo = $.trim($(this).attr('data-diaryNo'));
        var diaryYear = $.trim($(this).attr('data-diaryYear'));
        //alert('type='+ type + '  receiptNumber=' + receiptNumber + '  diaryNo='+ diaryNo + '  diaryYear='+ diaryYear);
        if(type=='lock') {
            if (diaryNo ==='' && diaryYear ===''){
             alert('Please generate diary number first then try to lock court fee.');
                return false;
            }
        }
        if(type && receiptNumber){
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $('.form-responce').remove();
            $('.status_refresh').html('');
            $(this).append(' <i class="status_refresh fa fa-refresh fa-spin"></i>');
            var postData = {CSRF_TOKEN: CSRF_TOKEN_VALUE, type: type,receiptNumber:receiptNumber,diaryNo:diaryNo,diaryYear:diaryYear};
            $.ajax({
                type: "POST",
                url: base_url + "newcase/FeeVerifyLock_Controller/feeVeryLock",
                data: JSON.stringify(postData),
                cache:false,
                async:false,
                dataType: 'json' ,
                contentType: 'application/json',
                success: function (data) {
                    var status =(data.status);
                    if (type=='verify') {
                        var RPSTATUS = (data.res.CERTRPDTL.RPSTATUS);
                        var RCPTNO = (data.res.CERTRPDTL.RCPTNO);
                        var DTISSUE = (data.res.CERTRPDTL.DTISSUE);
                        var CFAMT = (data.res.CERTRPDTL.CFAMT);
                        var STATUS = (data.res.CERTRPDTL.STATUS);
                        $('#Verify'+receiptNumber).hide();
                        $('#Verified'+receiptNumber).show();
                        $('#Verifiedlock'+receiptNumber).show();
                        $('#VerifiedLocked'+receiptNumber).hide();
                        //alert(data.res.CERTRPDTL.RPSTATUS);
                        if (RPSTATUS=='FAIL') {
                            $('#RPSTATUS').css('color', 'red');
                            $('#STATUS').css('color', 'red');
                            $('.STATUS').css('color', 'red');
                            $('#fee_type').css('color', 'red');
                            $('#STATUS_text').html('Reason');
                        } else{
                            $('#RPSTATUS').css('color', 'green');
                            $('#fee_type').css('color', 'green');
                            $('.STATUS').css('color', 'green');
                            $('#STATUS_text').html('');
                        }
                        $('.DTISSUE').show();
                        $('.CFAMT').show();
                        $('.STATUS').show();
                        $('#fee_type').html('Verify');
                        $('#RPSTATUS').html(RPSTATUS);
                        $('#receiptNumber').html(RCPTNO);
                        $('#DTISSUE').html(DTISSUE);
                        $('#CFAMT').html(CFAMT);
                        $('#STATUS').html(STATUS);
                        $('#diaryNumberYear').html('');
                        $('#CFLNAME').html('');
                        $('.diaryNumberYear').hide();
                        $('.CFLNAME').hide();
                        if (RPSTATUS=='SUCCESS' && status==true  && RCPTNO==receiptNumber) {
                            var result=('type '+ type +'  RPSTATUS='+ RPSTATUS + '  status=' + status + '  RCPTNO='+ RCPTNO + '  receiptNumber='+ receiptNumber);
                        } else{
                            var result=('type '+ type +' verify Failed  '+'RPSTATUS='+ RPSTATUS + '  status=' + status + '  RCPTNO='+ RCPTNO + '  receiptNumber='+ receiptNumber);
                        }
                    } else{
                        var RPSTATUS = (data.res.LOCKTXN.LOCKRPDTL.RPSTATUS);
                        var RCPTNO = (data.res.LOCKTXN.LOCKRPDTL.RCPTNO);
                        var CFLNAME = (data.res.LOCKTXN.LOCKRPDTL.CFLNAME);
                        var diary_Year = (data.res.LOCKTXN.LOCKRPDTL.DIRYEAR);
                        var diary_No = (data.res.TXNHDR.DIRNO);
                        var slash='/';
                        var diaryNumberYear=diary_No+slash+diary_Year;
                        //var RPSTATUS = (data.res.RPSTATUS);
                        // var RCPTNO = (data.res.RCPTNO);
                        $('#Verify'+receiptNumber).hide();
                        $('#Verified'+receiptNumber).show();
                        $('#Verifiedlock'+receiptNumber).hide();
                        $('#VerifiedLocked'+receiptNumber).show();
                        // alert(data.res.RPSTATUS);
                        if (RPSTATUS !='SUCCESS') {
                            $('#RPSTATUS').css('color', 'red');
                            $('#fee_type').css('color', 'red');
                        } else{
                            $('#RPSTATUS').css('color', 'green');
                            $('#fee_type').css('color', 'green');
                        }
                        $('.diaryNumberYear').show();
                        $('.CFLNAME').show();
                        $('#fee_type').html('Locking');
                        $('#RPSTATUS').html(RPSTATUS);
                        $('#receiptNumber').html(RCPTNO);
                        $('#diaryNumberYear').html(diaryNumberYear);
                        $('#CFLNAME').html(CFLNAME);
                        $('#DTISSUE').html('');
                        $('#CFAMT').html('');
                        $('#STATUS').html('');
                        $('.DTISSUE').hide();
                        $('.CFAMT').hide();
                        $('.STATUS').hide();
                        if (status==true  && RCPTNO==receiptNumber) {
                            var result=('type '+ type +'  RPSTATUS='+ RPSTATUS + '  status=' + status + '  RCPTNO='+ RCPTNO + '  receiptNumber='+ receiptNumber+ '  Diary Number='+ diary_No+'/'+diary_Year+ '  CFLNAME='+ CFLNAME);
                        } else{
                            var result=('type '+ type +' verify Failed  '+'RPSTATUS='+ RPSTATUS + '  status=' + status + '  RCPTNO='+ RCPTNO + '  receiptNumber='+ receiptNumber);
                        }
                    }
                    // alert(result);
                    // $("#VerifyModalResult").html(result);
                    $("#VerifyModal").modal('show');
                    $('.status_refresh').remove();
                    $.getJSON(base_url + "csrftoken", function (result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                }
            });
        }
        //location.reload();
    });

    function ActionToTrash(trash_type) {
        event.preventDefault();
        var trash_type = trash_type;
        var url = "";
        if (trash_type == '') {
            swal("Cancelled", "Your imaginary file is safe :)", "error");
            return false;
        } else if (trash_type == 'UAT') {
            url = "<?php echo base_url('userActions/trash'); ?>";
        } else if (trash_type == 'SLT') {
            url = "<?php echo base_url('stage_list/trash'); ?>";
        } else if (trash_type == 'EAT') {
            url = "<?php echo base_url('userActions/trash'); ?>";
        } else {
            swal("Cancelled", "Your imaginary file is safe :)", "error");
            return false;
        }
        //    alert('trash_type'+trash_type+'url='+url);//return false;
        swal({
                title: "Do you really want to trash this E-Filing,",
                text: "once it will be trashed you can't restore the same.",
                type: "warning",
                position: "top",
                showCancelButton: true,
                confirmButtonColor: "green",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                buttons: ["Make Changes", "Yes!"],
                closeOnConfirm: false,
                closeOnCancel: true
            },
            function(isConfirm) {
                if (isConfirm) { // submitting the form when user press yes
                    var link = document.createElement("a");
                    link.href = url;
                    link.target = "_self";
                    link.click();
                    swal({
                        title: "Deleted!",
                        text: "E-Filing has been deleted.",
                        type: "success",
                        timer: 2000
                    });

                } else {
                    //swal({title: "Cancelled",text: "Your imaginary file is safe.",type: "error",timer: 1300});
                }

            });
    }
</script>