<?php
$uris = service('uri');
if ($uris->getSegment(2) != 'view') {
    if ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_MISC_DOCS) {
        render('miscellaneous_docs.misc_docs_breadcrumb');
    } elseif ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_IA) {
        render('IA.ia_breadcrumb');
    } elseif ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_MENTIONING) {
        render('mentioning.mentioning_breadcrumb');
    } elseif ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_CITATION) {
        render('citation.citation_breadcrumb');
    } elseif ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_CERTIFICATE_REQUEST) {
        render('certificate.certificate_breadcrumb');
    } elseif ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == OLD_CASES_REFILING) {
        render('oldCaseRefiling.old_efiling_breadcrumb');
    }
}
?>
<div class="panel panel-default panel-body">
    <div class="col-md-12 text-center">
        <h4>Case Details </h4>
    </div>
    <div class="col-12">
        <div class="row">
            <div class="col-12 col-md-4 col-lg-4">
                <div class="row">
                    <div class="col-3"><label><strong>Diary No : </strong></label></div>
                    <div class="col-9">
                        <p> <?php echo_data($case_details[0]['diary_no'] . ' / ' . $case_details[0]['diary_year']); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4 col-lg-4">
                <div class="row">
                    <div class="col-3"><label><strong>Registration No. : </strong></label></div>
                    <div class="col-9">
                        <p> <?php echo_data($case_details[0]['reg_no_display']); ?></p> 
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4 col-lg-4">
                    <div class="row">
                        <div class="col-3"><label><strong>Cause Title : </strong></label></div>
                        <div class="col-9">
                            <p> <?php echo_data($case_details[0]['cause_title']); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4 col-lg-4">
                    <div class="row">
                        <div class="col-3"><label><strong>Status :</strong></label></div>
                        <div class="col-9">
                            <p><?php echo $case_details[0]['c_status'] == 'D' ? 'Disposed' : 'Pending'; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?php
                if (isset($filedByData) && !empty($filedByData)) {
                    $filedBy = '';
                    $name = !empty($filedByData['name']) ? $filedByData['name'] : '';
                    if (isset($filedByData['pp']) && !empty($filedByData['pp']) && empty($filedByData['aor_code']) && empty($filedByData['bar_id'])) {
                        $filedBy = $name . ' ( Party In Person )';
                    } else {
                        $aor_code = !empty($filedByData['aor_code']) ? $filedByData['aor_code'] : '';
                        $filedBy = $name . ' (AOR- ' . $aor_code . ')';
                    }
                    echo '<div class="col-12 col-md-4 col-lg-4">
                        <div class="col-3"><label for="filed_by"><strong>Filed By : </strong></label></div>
                        <div class="col-9">
                            <p>' . $filedBy . '</p>
                        </div>
                    </div>';
                }
                if (isset($case_listing_details->listed[0]) && !empty($case_listing_details->listed[0])) {
                    ?>
                    <div class="col-12 col-md-4 col-lg-4">
                        <div class="row">
                            <?php if (strtotime($case_listing_details->listed[0]->next_dt) >= strtotime(date("Y-m-d"))) { ?>
                                <div class="col-3"><label for="case_status"><strong>Listed For : </strong></label></div>
                                <div class="col-9">
                                    <p> <?= date('d-m-Y', strtotime($case_listing_details->listed[0]->next_dt)) ?>(<?= $case_listing_details->listed[0]->board_type ?>)</p>
                                </div>
                            <?php  } else { ?>
                                <div class="col-3"><label for="case_status"><strong>Listed On : </strong></label></div>
                                <div class="col-9">
                                    <p> <?= date('d-m-Y', strtotime($case_listing_details->listed[0]->next_dt)) ?>(<?= $case_listing_details->listed[0]->board_type ?>)</p>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php  } ?>
                <!-- <div class="col-md-6"></div> -->
                <div class="col-12 col-md-4 col-lg-4">
                    <div class="row">
                        <?php if(isset($case_listing_details->all_listed) && !empty($case_listing_details->all_listed)) { ?>
                            <div class="col-3"><label for="case_status"><strong>Listed : </strong></label></div>
                            <div class="col-9">
                                <p><?=sizeof($case_listing_details->all_listed);?> TIMES</p>
                            </div>
                        <?php  } else { ?>
                            <div class="col-3"><label for="case_status"><strong>Listed : </strong></label></div>
                            <div class="col-9">
                                <span> FRESH </span>
                            </div>
                        <?php  } ?>
                    </div>
                </div>
                <?php if (isset($details) && !empty($details)) { ?>
                    <div class="col-12 col-md-4 col-lg-4">
                        <label><strong>
                            <?php if ($details['details'][0]['judgment_reserved_date'] != null) {
                                echo '<font color=red>Judgment is reserved for :' . date('d-m-Y', strtotime($details['details'][0]['judgment_reserved_date'])) . '</font>';
                            }
                            ?>
                        </strong></label>
                    </div>
                    <?php
                }
                if ($case_details[0]['p_r_type'] == 'I') {
                    ?>
                    <div class="col-12 col-md-4 col-lg-4">
                        <div class="row">
                            <label for="case_status"><strong>Intervenor Name : </strong></label>
                            <div class="col-9">
                                <p> <?php if (isset($case_details[0]['intervenorname']) && !empty($case_details[0]['intervenorname'])) { echo strtoupper($case_details[0]['intervenorname']); } ?> </p>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                if ($uris->getSegment(2) != 'view') {
                    ?>
                    <div class="row">
                        <div class="col-md-offset-5 col-md-2 col-sm-offset-3 col-sm-6 col-xs-12 efiling_search">
                            <?php if ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_MENTIONING) { ?>
                                <a href="<?= base_url('mentioning/Listing'); ?>" class="btn quick-btn" type="button">NEXT</a>
                            <?php  } else if ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_CITATION) { ?>
                                <a href="<?= base_url('citation/CitationController'); ?>" class="btn quick-btn" type="button">NEXT</a>
                            <?php } else if ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_CERTIFICATE_REQUEST) { ?>
                                <a href="<?= base_url('certificate/AddDetails'); ?>" class="btn quick-btn" type="button">NEXT</a>
                            <?php  } else if ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == OLD_CASES_REFILING) { ?>
                                <a href="<?= base_url('uploadDocuments/DefaultController'); ?>" class="btn quick-btn" type="button">NEXT</a>
                            <?php  } else if ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_IA) { ?>
                                <a href="<?= base_url('appearing_for'); ?>" class="btn quick-btn" type="button">NEXT</a>
                            <?php  } else if ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_MISC_DOCS) { ?>
                                <a href="<?= base_url('appearing_for'); ?>" class="btn quick-btn" type="button">NEXT</a>
                            <?php } else { ?>
                                <a href="<?= base_url('case/interim_application/crud_registration/' . url_encryption(trim($_SESSION['efiling_details']['registration_id'] . '#' . $_SESSION['efiling_details']['ref_m_efiled_type_id'] . '#' . 1))); ?>" class="btn quick-btn" type="button">NEXT</a>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>