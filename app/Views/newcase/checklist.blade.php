<link href="<?= base_url() . 'assets/newAdmin/' ?>css/black-theme.css" rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/responsive.css" rel="stylesheet">
<div class="mainPanel ">
    <div class="panelInner">
        <div class="middleContent">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 sm-12 col-md-12 col-lg-12 middleContent-left">
                        <div class="center-content-inner comn-innercontent">
                            <?php
                            $segment = service('uri');
                            ?>
                            @if (
                                !empty(getSessionData('efiling_details')['ref_m_efiled_type_id']) &&
                                    getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_CAVEAT)
                                @include('caveat.caveat_breadcrumb')
                            @else
                                @include('newcase.new_case_breadcrumb')
                            @endif
                            @if ($segment->getSegment(2) == 'caseDetails')
                                @include('newcase.case_details_view')
                            @elseif ($segment->getSegment(2) == 'petitioner')
                                @include('newcase.petitioner_view')
                            @elseif ($segment->getSegment(2) == 'respondent')
                                @include('newcase.respondent_view')
                            @elseif ($segment->getSegment(2) == 'extra_party')
                                @include('newcase.extra_party_view')
                            @elseif ($segment->getSegment(2) == 'lr_party')
                                @include('newcase.lr_party_view')
                            @elseif ($segment->getSegment(2) == 'actSections')
                                @include('newcase.act_sections_view')
                            @elseif ($segment->getSegment(1) == 'uploadDocuments' || $segment->getSegment(1) == 'documentIndex')
                                @include('documentIndex.documentIndex_view')
                            @elseif ($segment->getSegment(1) == 'documentIndex')
                                @include('documentIndex.documentIndex_view')
                            @elseif ($segment->getSegment(2) == 'subordinate_court')
                                @include('newcase.subordinateCourt_view')
                            @elseif ($segment->getSegment(1) == 'affirmation')
                                //$this->load->view('affirmation/affirmation_view')
                            @elseif ($segment->getSegment(2) == 'checklist')
                                @if (!empty(getSessionData('efiling_details')['stage_id']) && (getSessionData('efiling_details')['stage_id'] == I_B_Defected_Stage || getSessionData('efiling_details')['stage_id'] == I_B_Defects_Cured_Stage))
                                <!-- shivam page -->  
                                 @include('newcase.efile_declaration_view')                               
                                @else
                                    @include('newcase.checklist_view')
                                @endif
                            @elseif ($segment->getSegment(2) == 'courtFee')
                                @if (getSessionData('efiling_details')['is_govt_filing'] == 1)
                                    @include('newcase.courtFee_govt_view')
                                @else
                                    @include('newcase.courtFee_view')
                                @endif
                            @elseif ($segment->getSegment(2) == 'view')
                                @include('newcase.new_case_preview')
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
