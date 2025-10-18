<?php declare(strict_types=1); ?>
<?php
if(!in_array($_SESSION['login']['ref_m_usertype_id'],array(ARGUING_COUNSEL,SR_ADVOCATE))){
    $x = 'layout.advocateApp';
}else{
    $x  ='layout.app';
}
?>
@extends($x)
@section('content')
<style>
.btn-info {
    margin: -13% 15%;
}

.fc-today-button {
    text-transform: capitalize !important;
}

#efiling-details {
    margin-top: 40px !important;
}

.blue-tile {
    height: 100% !important;
}

a .btn-primary {
    color: #fff;
}

li {
    list-style: none;
}

.fc .fc-toolbar-title {
    font-size: 1.6em !important;
}

.uk-modal {
    display: none;
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    z-index: 1010;
    overflow-y: auto;
    -webkit-overflow-scrolling: touch;
    padding: 15px 15px;
    background: rgba(0, 0, 0, .6);
    opacity: 0;
    transition: opacity .15s linear
}

@media (min-width:640px) {
    .uk-modal {
        padding: 50px 30px
    }
}

@media (min-width:960px) {
    .uk-modal {
        padding-left: 40px;
        padding-right: 40px
    }
}

.uk-modal.uk-open {
    opacity: 1
}

.uk-modal-page {
    overflow: hidden
}

.uk-modal-dialog {
    position: relative;
    box-sizing: border-box;
    margin: 0 auto;
    width: 600px;
    max-width: calc(100% - .01px) !important;
    background: #fff;
    opacity: 0;
    transform: translateY(-100px);
    transition: .3s linear;
    transition-property: opacity, transform
}

.uk-open>.uk-modal-dialog {
    opacity: 1;
    transform: translateY(0)
}

.uk-modal-container .uk-modal-dialog {
    width: 1200px
}

.uk-modal-full {
    padding: 0;
    background: 0 0
}

.uk-modal-full .uk-modal-dialog {
    margin: 0;
    width: 100%;
    max-width: 100%;
    transform: translateY(0)
}

.uk-modal-body {
    padding: 30px 30px
}

.uk-modal-header {
    padding: 15px 30px;
    background: #fff;
    border-bottom: 1px solid #e5e5e5
}

.uk-modal-footer {
    padding: 15px 30px;
    background: #fff;
    border-top: 1px solid #e5e5e5
}

.uk-modal-body::after,
.uk-modal-body::before,
.uk-modal-footer::after,
.uk-modal-footer::before,
.uk-modal-header::after,
.uk-modal-header::before {
    content: "";
    display: table
}

.uk-modal-body::after,
.uk-modal-footer::after,
.uk-modal-header::after {
    clear: both
}

.uk-modal-body>:last-child,
.uk-modal-footer>:last-child,
.uk-modal-header>:last-child {
    margin-bottom: 0
}

.uk-modal-title {
    font-size: 2rem;
    line-height: 1.3
}

[class*=uk-modal-close-] {
    position: absolute;
    z-index: 1010;
    top: 10px;
    right: 10px;
    padding: 5px
}

[class*=uk-modal-close-]:first-child+* {
    margin-top: 0
}

.uk-modal-close-outside {
    top: 0;
    right: -5px;
    transform: translate(0, -100%);
    color: #fff
}

.uk-modal-close-outside:hover {
    color: #fff
}

@media (min-width:960px) {
    .uk-modal-close-outside {
        right: 0;
        transform: translate(100%, -100%)
    }
}

.uk-modal-close-full {
    top: 0;
    right: 0;
    padding: 20px;
    background: #fff
}

.uk-text-uppercase {
    text-transform: uppercase !important;
}

.uk-text-small {
    font-size: 9px !important;
    line-height: 1.5;
}

.md-bg-grey-700 {
    background-color: #616161 !important;
}

.md-color-grey-50 {
    color: #fafafa !important;
}

.md-bg-red-700 {
    background-color: #d32f2f !important;
}

#calendar {
    cursor: pointer;
}

td {
    line-height: normal !important;
}

.fc-event-main {
    text-align: center;
}

#calendar-cases {
    text-align: center;
}

.bg-black-court-no{
    background-color: black;
    color: white;
    padding: 3px;
    border-radius: 50px;
    text-transform: uppercase !important;
}

.court-no-div{
    padding-top: 8px;
    padding-bottom: 8px;
}
</style>
<div class="mainPanel ">
    <div class="panelInner">
        <div class="middleContent">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 sm-12 col-md-12 col-lg-12 middleContent-left">
                        @if(!in_array($_SESSION['login']['ref_m_usertype_id'],array(ARGUING_COUNSEL,SR_ADVOCATE)))
                        <div class="dashboard-section dashboard-tiles-area">
                            <div class="row">
                                <input type="hidden" name="CSRF_TOKEN" value="<?php echo csrf_hash(); ?>">
                                <div class="col-12 col-sm-12 col-md-6 col-lg-3">
                                    <div class="dashbord-tile pink-tile" tabindex="0">
                                        <!-- Start 1st Grid -->
                                        <div id="showByMe" style="display: block;">
                                            <h6 class="tile-title" tabindex="0">Recent Documents</h6>
                                            <p class="tile-subtitle" tabindex="0">By other Parties</p>
                                            <button id="byMe" class="btn btn-info pull-right">#By me</button>
                                            <h4 class="main-count" tabindex="0">
                                                <?php
                                                        if (isset($recent_documents_by_others) && !empty($recent_documents_by_others)) {
                                                            echo count($recent_documents_by_others);
                                                        } else {
                                                            echo '00';
                                                        }
                                                        ?>
                                            </h4>
                                            @include('responsive_variant.dashboard.layouts.documents', ['documents' =>
                                            $recent_documents_by_others])
                                            @include('responsive_variant.dashboard.layouts.documents', ['documents' =>
                                            $recent_documents_by_others_grouped_by_document_type->adjournment_requests,'type'
                                            => 'adjournment_requests'])
                                            <div class="tiles-comnts">
                                                <div class="tile-comnt" tabindex="0">
                                                    <h6 class="comts-no">
                                                        <?php
                                                                if (isset($recent_documents_by_others_grouped_by_document_type) && !empty($recent_documents_by_others_grouped_by_document_type)) {
                                                                    echo count($recent_documents_by_others_grouped_by_document_type->rejoinder);
                                                                } else {
                                                                    echo '00';
                                                                }
                                                                ?>
                                                    </h6>
                                                    <p class="comnt-name">Rejoinder</p>
                                                    @include('responsive_variant.dashboard.layouts.documents',
                                                    ['documents' =>
                                                    $recent_documents_by_others_grouped_by_document_type->rejoinder])
                                                </div>
                                                <div class="tile-comnt" tabindex="0">
                                                    <h6 class="comts-no">
                                                        <?php
                                                                if (isset($recent_documents_by_others_grouped_by_document_type) && !empty($recent_documents_by_others_grouped_by_document_type)) {
                                                                    echo count($recent_documents_by_others_grouped_by_document_type->reply);
                                                                } else {
                                                                    echo '00';
                                                                }
                                                                ?>
                                                    </h6>
                                                    <p class="comnt-name">Reply</p>
                                                    @include('responsive_variant.dashboard.layouts.documents',
                                                    ['documents' =>
                                                    $recent_documents_by_others_grouped_by_document_type->reply])
                                                </div>
                                                <div class="tile-comnt" tabindex="0">
                                                    <h6 class="comts-no">
                                                        <?php
                                                                if (isset($recent_documents_by_others_grouped_by_document_type) && !empty($recent_documents_by_others_grouped_by_document_type)) {
                                                                    echo count($recent_documents_by_others_grouped_by_document_type->ia);
                                                                } else {
                                                                    echo '00';
                                                                }
                                                                ?>
                                                    </h6>
                                                    <p class="comnt-name">IA</p>
                                                    @include('responsive_variant.dashboard.layouts.documents',
                                                    ['documents' =>
                                                    $recent_documents_by_others_grouped_by_document_type->ia, 'type' =>
                                                    ''])
                                                </div>
                                                <div class="tile-comnt" tabindex="0">
                                                    <h6 class="comts-no">
                                                        <?php
                                                                if (isset($recent_documents_by_others_grouped_by_document_type) && !empty($recent_documents_by_others_grouped_by_document_type)) {
                                                                    echo count($recent_documents_by_others_grouped_by_document_type->other);
                                                                } else {
                                                                    echo '00';
                                                                }
                                                                ?>
                                                    </h6>
                                                    <p class="comnt-name">Other</p>
                                                    @include('responsive_variant.dashboard.layouts.documents',
                                                    ['documents' =>
                                                    $recent_documents_by_others_grouped_by_document_type->other])
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End 1st Grid -->

                                        <!--Start 2nd grid-->
                                        <div style="display: none;"id="showByOthers">
                                            <h6 class="tile-title" tabindex="0">Recent Documents</h6>
                                            <p class="tile-subtitle" tabindex="0">By Me</p>
                                            <button id="byOthers" class="btn btn-info pull-right" tabindex="0">#By
                                                others</button>
                                            <h4 class="main-count" tabindex="0">
                                                <?php
                                                        if (isset($recent_documents_by_me) && !empty($recent_documents_by_me)) {
                                                            echo count($recent_documents_by_me);
                                                        } else {
                                                            echo '00';
                                                        }
                                                        ?>
                                            </h4>
                                            @include('responsive_variant.dashboard.layouts.documents',
                                            ['uk_drop_boundary' => '.my-documents-widget', 'documents' =>
                                            $recent_documents_by_me])
                                            @include('responsive_variant.dashboard.layouts.documents',
                                            ['uk_drop_boundary' => '.my-documents-widget', 'documents' =>
                                            $recent_documents_by_me_grouped_by_document_type->other])
                                            <div class="tiles-comnts">
                                                <div class="tile-comnt" tabindex="0">
                                                    <h6 class="comts-no">
                                                        <?php
                                                                if (isset($recent_documents_by_me_grouped_by_document_type) && !empty($recent_documents_by_me_grouped_by_document_type)) {
                                                                    echo count($recent_documents_by_me_grouped_by_document_type->rejoinder);
                                                                } else {
                                                                    echo '00';
                                                                }
                                                                ?>
                                                    </h6>
                                                    <p class="comnt-name">Rejoinder</p>
                                                    @include('responsive_variant.dashboard.layouts.documents',
                                                    ['uk_drop_boundary' => '.my-documents-widget', 'documents' =>
                                                    $recent_documents_by_me_grouped_by_document_type->rejoinder])
                                                </div>
                                                <div class="tile-comnt" tabindex="0">
                                                    <h6 class="comts-no">
                                                        <?php
                                                                if (isset($recent_documents_by_me_grouped_by_document_type) && !empty($recent_documents_by_me_grouped_by_document_type)) {
                                                                    echo count($recent_documents_by_me_grouped_by_document_type->reply);
                                                                } else {
                                                                    echo '00';
                                                                }
                                                                ?>
                                                    </h6>
                                                    <p class="comnt-name">Reply</p>
                                                    @include('responsive_variant.dashboard.layouts.documents',
                                                    ['uk_drop_boundary' => '.my-documents-widget', 'documents' =>
                                                    $recent_documents_by_me_grouped_by_document_type->reply])
                                                </div>
                                                <div class="tile-comnt" tabindex="0">
                                                    <h6 class="comts-no">
                                                        <?php
                                                                if (isset($recent_documents_by_me_grouped_by_document_type) && !empty($recent_documents_by_me_grouped_by_document_type)) {
                                                                    echo count($recent_documents_by_me_grouped_by_document_type->ia);
                                                                } else {
                                                                    echo '00';
                                                                }
                                                                ?>
                                                    </h6>
                                                    <p class="comnt-name">IA</p>
                                                    @include('responsive_variant.dashboard.layouts.documents',
                                                    ['uk_drop_boundary' => '.my-documents-widget', 'documents' =>
                                                    $recent_documents_by_me_grouped_by_document_type->ia])
                                                </div>
                                                <div class="tile-comnt" tabindex="0">
                                                    <h6 class="comts-no">
                                                        <?php
                                                                if (isset($recent_documents_by_me_grouped_by_document_type) && !empty($recent_documents_by_me_grouped_by_document_type)) {
                                                                    echo count($recent_documents_by_me_grouped_by_document_type->other);
                                                                } else {
                                                                    echo '00';
                                                                }
                                                                ?>
                                                    </h6>
                                                    <p class="comnt-name">Other</p>
                                                    @include('responsive_variant.dashboard.layouts.documents',
                                                    ['uk_drop_boundary' => '.my-documents-widget', 'documents' =>
                                                    $recent_documents_by_me_grouped_by_document_type->other])
                                                </div>
                                            </div>
                                        </div>
                                        <!--End 2nd grid-->
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-3">
                                    <div class="dashbord-tile purple-tile" tabindex="0">
                                        <h6 class="tile-title" tabindex="0">Incomplete Filings</h6>
                                        <p class="tile-subtitle" tabindex="0">Cases/appl. Filed by you</p>
                                        <h4 class="main-count" tabindex="0">
                                            <?php
                                                    if (isset($incomplete_applications) && !empty($incomplete_applications)) {
                                                        echo count($incomplete_applications);
                                                    } else {
                                                        echo '00';
                                                    }
                                                    ?>
                                        </h4>
                                        @include('responsive_variant.dashboard.layouts.applications', ['applications' =>
                                        $incomplete_applications])
                                        <div class="tiles-comnts">
                                            <div class="tile-comnt" tabindex="0">
                                                <h6 class="comts-no">
                                                    <?php
                                                            if (isset($draft_applications) && !empty($draft_applications)) {
                                                                echo count($draft_applications);
                                                            } else {
                                                                echo '00';
                                                            }
                                                            ?>
                                                </h6>
                                                <p class="comnt-name">Draft</p>
                                                @include('responsive_variant.dashboard.layouts.applications',
                                                ['applications' => $draft_applications])
                                            </div>
                                            <div class="tile-comnt" tabindex="0">
                                                <h6 class="comts-no">
                                                    <?php
                                                            if (isset($initially_defective_applications) && !empty($initially_defective_applications)) {
                                                                echo count($initially_defective_applications);
                                                            } else {
                                                                echo '00';
                                                            }
                                                            ?>
                                                </h6>
                                                <p class="comnt-name">For Compliance</p>
                                                @include('responsive_variant.dashboard.layouts.applications',
                                                ['applications' => $initially_defective_applications])
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-3">
                                    <div class="dashbord-tile blue-tile" tabindex="0">
                                        <h6 class="tile-title" tabindex="0">Scrutiny Status</h6>
                                        <p class="tile-subtitle" tabindex="0">Cases/appl. Filed by you</p>
                                        <h4 class="main-count">&nbsp;&nbsp;</h4>
                                        <div class="tiles-comnts">
                                            <div class="tile-comnt" tabindex="0">
                                                <h6 class="comts-no">
                                                    <?php
                                                            if (isset($defect_notified) && !empty($defect_notified)) {
                                                                echo count($defect_notified);
                                                            } else {
                                                                echo '00';
                                                            }
                                                            ?>
                                                </h6>
                                                <p class="comnt-name">Defect Notified</p>
                                                @include('responsive_variant.dashboard.layouts.defects', ['applications'
                                                => $defect_notified])
                                            </div>
                                            <div class="tile-comnt" tabindex="0">
                                                <h6 class="comts-no">
                                                    <?php
                                                            if (isset($pending_scrutiny) && !empty($pending_scrutiny)) {
                                                                echo count($pending_scrutiny);
                                                            } else {
                                                                echo '00';
                                                            }
                                                            ?>
                                                </h6>
                                                <p class="comnt-name">Pending Scrutiny</p>
                                                @include('responsive_variant.dashboard.layouts.defects', ['applications'
                                                => $pending_scrutiny])
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- -----  -->
                                <div class="col-12 col-sm-12 col-md-6 col-lg-3">
                                    <div class="dashbord-tile purple-tile application-tile" tabindex="0">
                                        <h6 class="tile-title" tabindex="0">Certified Copies</h6>
                                        <p class="tile-subtitle"></p>
                                        <h4 class="main-count">&nbsp;&nbsp;</h4>
                                        <div class="application-tile-sections">
                                            <div class="applictin-tile-sec">
                                                <div class="appliction-til-nam" tabindex="0">
                                                    <h6>Online</h6>
                                                </div>
                                                <div class="tiles-comnts">
                                                    <div class="tile-comnt" tabindex="0">
                                                        <h6 class="comts-no">
                                                            <?php
                                                            
                                                                    if (isset($online->disposed_appl) && !empty($online->disposed_appl)) {
                                                                        echo $online->disposed_appl;
                                                                    } else {
                                                                        echo '00';
                                                                    }
                                                                    ?>
                                                        </h6>
                                                        <p class="comnt-name">Disposed</p>
                                                        @include('responsive_variant.dashboard.layouts.certified_copy',
                                                        ['uk_drop_boundary' => '.my-documents-widget', 'dashboard_flag' =>
                                                        'online_disposed'])
                                                    </div>
                                                    <div class="tile-comnt" tabindex="0">
                                                        <h6 class="comts-no">
                                                            <?php
                                                                    if (isset($online->pending_appl) && !empty($online->pending_appl)) {
                                                                        echo $online->pending_appl;
                                                                    } else {
                                                                        echo '00';
                                                                    }
                                                                    ?>
                                                        </h6>
                                                        <p class="comnt-name">Pending</p>
                                                        @include('responsive_variant.dashboard.layouts.certified_copy',
                                                        ['uk_drop_boundary' => '.my-documents-widget', 'dashboard_flag' =>
                                                        'online_pending'])
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="applictin-tile-sec">
                                                <div class="appliction-til-nam" tabindex="0">
                                                    <h6>Offline</h6>
                                                </div>
                                                <div class="tiles-comnts">
                                                    <div class="tile-comnt" tabindex="0">
                                                        <h6 class="comts-no">
                                                            <?php
                                                                    if (isset($offline->disposed_appl) && !empty($offline->disposed_appl)) {
                                                                        echo $offline->disposed_appl;
                                                                    } else {
                                                                        echo '00';
                                                                    }
                                                                    ?>
                                                        </h6>
                                                        <p class="comnt-name">Disposed</p>
                                                        @include('responsive_variant.dashboard.layouts.certified_copy',
                                                        ['uk_drop_boundary' => '.my-documents-widget', 'dashboard_flag' =>
                                                        'offline_disposed'])
                                                    </div>
                                                    <div class="tile-comnt" tabindex="0">
                                                        <h6 class="comts-no">
                                                            <?php
                                                                    if (isset($offline->pending_appl) && !empty($offline->pending_appl)) {
                                                                        echo $offline->pending_appl;
                                                                    } else {
                                                                        echo '00';
                                                                    }
                                                                    ?>
                                                        </h6>
                                                        <p class="comnt-name">Pending</p>
                                                        @include('responsive_variant.dashboard.layouts.certified_copy',
                                                        ['uk_drop_boundary' => '.my-documents-widget', 'dashboard_flag' =>
                                                        'offline_pending'])
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="applictin-tile-sec">
                                                <div class="appliction-til-nam" tabindex="0">
                                                    <h6>Document Requested</h6>
                                                </div>
                                                <div class="tiles-comnts">
                                                    <div class="tile-comnt" tabindex="0">
                                                        <h6 class="comts-no">
                                                            <?php
                                                                    if (isset($request->disposed_request) && !empty($request->disposed_request)) {
                                                                        echo $request->disposed_request;
                                                                    } else {
                                                                        echo '00';
                                                                    }
                                                                    ?>
                                                        </h6>
                                                        <p class="comnt-name">Disposed</p>
                                                        @include('responsive_variant.dashboard.layouts.certified_copy',
                                                        ['uk_drop_boundary' => '.my-documents-widget', 'dashboard_flag' =>
                                                        'document_disposed'])
                                                    </div>
                                                    <div class="tile-comnt" tabindex="0">
                                                        <h6 class="comts-no">
                                                            <?php
                                                                    if (isset($request->pending_request) && !empty($request->pending_request)) {
                                                                        echo $request->pending_request;
                                                                    } else {
                                                                        echo '00';
                                                                    }
                                                                    ?>
                                                        </h6>
                                                        <p class="comnt-name">Pending</p>
                                                        @include('responsive_variant.dashboard.layouts.certified_copy',
                                                        ['uk_drop_boundary' => '.my-documents-widget', 'dashboard_flag' =>
                                                        'document_pending'])
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                <?php  if (session()->getFlashdata('paymentError')) : ?>
                    <div class="alert-danger">
                        <b><?= session()->getFlashdata('paymentError') ?></b>
                    </div>
                <?php endif; ?>
                <div class="row">
                    @if(!in_array($_SESSION['login']['ref_m_usertype_id'],array(ARGUING_COUNSEL,SR_ADVOCATE)))
                    <div class="col-12 sm-12 col-md-12 col-lg-9 middleContent-left">
                        @else
                        <div class="col-12 sm-12 col-md-12 col-lg-12 middleContent-left">
                            @endif
                            <div class="left-content-inner comn-innercontent">
                                <div class="dashboard-section">
                                    <div class="row">
                                        @if(!in_array($_SESSION['login']['ref_m_usertype_id'],array(ARGUING_COUNSEL,SR_ADVOCATE,AMICUS_CURIAE_USER)))
                                        
                                        @if(!empty($scheduled_cases) && (is_array($scheduled_cases) || is_object($scheduled_cases)) && (isset($scheduled_cases[0]) || !empty($scheduled_cases[0])) && (count($scheduled_cases[0])>0))
                                            <div class="col-12 col-sm-12 col-md-12 col-lg-4">
                                                <div class="dash-card" >
                                                    <div class="title-sec">
                                                        <h5 class="unerline-title" tabindex="0">My Cases <small class="uk-text-muted">soon to be listed</small></h5>
                                                    </div>
                                                    <div class="table-sec ">
                                                        <div class="table-responsive ">
                                                            <table id="datatable-responsive-sc_cases"
                                                                class="table table-striped custom-table  soon-listing-table">
                                                                <thead>
                                                                    <tr class="uk-text-bold">
                                                                        <th class="uk-text-bold" tabindex="0">Case</th>
                                                                        <th class="uk-text-bold" tabindex="0">Date & Bench</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="accordionExample">
                                                                    <?php $IS_AMICUS_CURIAE = null; ?>
                                                                    @if(!empty($scheduled_cases))
                                                                        <?php $i = 1; ?>
                                                                        @foreach($scheduled_cases as $scheduled_case)
                                                                            @if(is_array($scheduled_case) || is_object($scheduled_case))
                                                                                @foreach($scheduled_case as $key => $scheduled)
                                                                                    <tr>
                                                                                        <td class="uk-width-small@m" data-key="Case" tabindex="0" style="width: 40%; text-align: left; align-items: left;">
                                                                                            <div><span class="uk-text-muted">{{$scheduled['registration_number'] ?: ('D. No.' . $scheduled['diary_number'] . '/' . $scheduled['diary_year'])}}</span></div>
                                                                                            <?php $IS_AMICUS_CURIAE_P = $IS_AMICUS_CURIAE_R = ''; ?>
                                                                                            @if (isset($scheduled_case[$key]['advocates']) && !empty($scheduled_case[$key]['advocates']))
                                                                                                @foreach ($scheduled_case[$key]['advocates'] as $advocates)
                                                                                                    @if (isset($scheduled_case[$key]['advocates']) && !empty($scheduled_case[$key]['advocates']))
                                                                                                        @if ($_SESSION['login']['adv_sci_bar_id'] == $advocates['id'] && $_SESSION['login']['aor_code'] == $advocates['aor_code'] && $advocates['is_amicus_curiae'] == 1)
                                                                                                            @if (strtoupper($advocates['represents_petitioner_or_respondent']) == 'P')
                                                                                                                <?php $IS_AMICUS_CURIAE_P = '[<span class="text-success"><b>P: A</b>micus <b>C</b>uriae</span>]';?>
                                                                                                            @endif
                                                                                                            @if (strtoupper($advocates['represents_petitioner_or_respondent']) == 'R')
                                                                                                                <?php $IS_AMICUS_CURIAE_R = '[<span class="text-success"><b>R: A</b>micus <b>C</b>uriae</span>]';?>
                                                                                                            @endif
                                                                                                        @endif
                                                                                                    @endif
                                                                                                @endforeach
                                                                                            @endif
                                                                                            <div><b style="font-size: 17px;">P: </b>{{ucwords(strtolower($scheduled['petitioner_name']))}} <?=$IS_AMICUS_CURIAE_P;?></div>
                                                                                            <div><b style="font-size: 17px;">R: </b>{{ucwords(strtolower($scheduled['respondent_name']))}} <?=$IS_AMICUS_CURIAE_R;?></div>
                                                                                        </td>
                                                                                        <td class="uk-table-expand" uk-margin
                                                                                            data-key="Date & Bench" tabindex="0" width=60%>
                                                                                            <div>
                                                                                                <li class="mycases-li">
                                                                                                    <button type="button"
                                                                                                        class="btn btn-primary accordion-button collapsed"
                                                                                                        data-bs-toggle="collapse"
                                                                                                        data-bs-target="#collapse<?php echo $i; ?>"
                                                                                                        aria-expanded="false"
                                                                                                        aria-controls="collapse<?php echo $i; ?>">{{date_format(date_create($scheduled['meta']['listing']['listed_on']), 'D, jS M')}}</button>
                                                                                                    <ul id="collapse<?php echo $i; ?>"
                                                                                                        class="submenu accordion-collapse collapse"
                                                                                                        aria-labelledby="heading<?php echo $i; ?>"
                                                                                                        data-bs-parent="#accordionExample">
                                                                                                        @if(!empty($scheduled['office_reports']['current']['uri']))
                                                                                                        <li><a class="btn-link"
                                                                                                                href="{{$scheduled['office_reports']['current']['uri']}}"
                                                                                                                target="_blank"><span
                                                                                                                    uk-icon="icon: file-pdf"></span>
                                                                                                                Office Report</a></li>
                                                                                                        @endif
                                                                                                        @if(!empty($scheduled['rop_judgments']['current']['uri']))
                                                                                                        <li><a class="btn-link"
                                                                                                                href="{{$scheduled['rop_judgments']['current']['uri']}}"
                                                                                                                target="_blank"><span
                                                                                                                    uk-icon="icon: file-pdf"></span>
                                                                                                                Previous Order
                                                                                                                ({{$scheduled['rop_judgments']['current']['dated']}})</a>
                                                                                                        </li>
                                                                                                        @endif
                                                                                                        <li><a style="cursor: pointer;"
                                                                                                                title="Send SMS"
                                                                                                                onClick="get_message_data(this.id)"
                                                                                                                id="<?php echo $scheduled['diary_number'] .'#'.$scheduled['diary_year'].'#'.$scheduled['registration_number'].'#'.$scheduled['petitioner_name'].'#'.$scheduled['respondent_name'].'#'.$scheduled['item_number'].'#'.$scheduled['meta']['listing']['court']['name'],'#'.date_format(date_create($scheduled['meta']['listing']['listed_on']), 'D, jS M Y'); ?>"><span
                                                                                                                    uk-icon="icon: comment"></span>
                                                                                                                Send SMS</a></li>
                                                                                                        <?php if($scheduled['vc_url']!=null) { ?>
                                                                                                        <li><a class="btn-link"
                                                                                                                href="{{$scheduled['vc_url']}}"
                                                                                                                target="_blank"><span
                                                                                                                    uk-icon="icon: file-text"></span>
                                                                                                                Join Virtual Court</a></li>
                                                                                                        <?php } ?>
                                                                                                        <li>
                                                                                                            @if($scheduled['meta']['listing']['court']['number']!=1)

                                                                                                            <a href="<?php echo base_url("case/paper_book_viewer/".(string)$scheduled['diary_id'].""); ?>"
                                                                                                                target="_blank"
                                                                                                                rel="noopener">
                                                                                                                <span
                                                                                                                    uk-icon="icon: bookmark"></span>
                                                                                                                Paper Book (with Indexing)
                                                                                                            </a>
                                                                                                            @endif
                                                                                                            @if($scheduled['meta']['listing']['court']['number']==1)
                                                                                                            <a href="<?php echo base_url("case/3pdf_paper_book_viewer/".(string)$scheduled['diary_id'].""); ?>"
                                                                                                                target="_blank"
                                                                                                                rel="noopener">
                                                                                                                <span
                                                                                                                    uk-icon="icon: bookmark"></span>
                                                                                                                Paper Book (3 PDF)
                                                                                                            </a>
                                                                                                            @endif
                                                                                                        </li>
                                                                                                    </ul>
                                                                                                </li>
                                                                                            </div>
                                                                                            <div class="court-no-div">
                                                                                                <span class="uk-label md-bg-grey-900 bg-black-court-no"
                                                                                                    uk-tooltip="{{$scheduled['meta']['listing']['court']['listing_cum_board_type'] . str_replace(':','.',$scheduled['meta']['listing']['court']['scheduled_time'])}}">{{'Item '.($scheduled['item_number_alt'] ?: $scheduled['item_number'])}}
                                                                                                    @
                                                                                                    {{$scheduled['meta']['listing']['court']['name']}}</span>
                                                                                            </div>
                                                                                            <div>
                                                                                                <small>
                                                                                                    <b>Bench:</b><br>
                                                                                                    <?php echo ucwords(strtolower(implode(',<br> ', $scheduled['meta']['listing']['court']['judges']))); ?>
                                                                                                </small>
                                                                                            </div>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <?php $i++; ?>
                                                                                @endforeach
                                                                            @endif
                                                                        @endforeach
                                                                    @endif
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr class="uk-divider-vertical uk-margin-small-left uk-margin-small-right uk-visible@m">
                                            </div>
                                        @endif
                                        @endif

                                        <!-- start amicus_curiae_user soon cases -->
                                            @if(!empty($amicus_curiae_user_soon_cases))
                                                <div class="uk-width uk-width-large@l uk-margin-medium-top uk-overflow-auto uktable-responsive">
                                                    <h4 class="uk-heading-bullet uk-text-bold">My cases <small class="uk-text-muted">soon to be listed</small></h4>
                                                    <table class="uk-table uktable-justify uktable-striped uk-table-hover uk-table-divider" id="soon-to-be-listed-cases-table">
                                                        <thead>
                                                        <tr class="uk-text-bold">
                                                            <th class="uk-text-bold">Case</th>
                                                            <th class="uk-text-bold">Date & Bench</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach($amicus_curiae_user_soon_cases as $amicus_curiae_user_soon_case)
                                                            <tr>
                                                                <td class="uk-width-small@m">
                                                                    <div>
                                                                        <span class="uk-text-muted">{{$amicus_curiae_user_soon_case->registration_number ?: ('D. No.' . $amicus_curiae_user_soon_case->diary_number . '/' . $amicus_curiae_user_soon_case->diary_year)}}</span>
                                                                    </div>

                                                                    <?php $IS_AMICUS_CURIAE_P=$IS_AMICUS_CURIAE_R='';?>
                                                                    @if (isset($amicus_curiae_user_soon_case->advocates) && !empty($amicus_curiae_user_soon_case->advocates))
                                                                        @foreach ($amicus_curiae_user_soon_case->advocates as $advocates)
                                                                            @if (isset($advocates) && !empty($advocates))
                                                                                @if ($advocates->is_amicus_curiae)
                                                                                    @if (strtoupper($advocates->represents_petitioner_or_respondent) == 'P')
                                                                                        <?php $IS_AMICUS_CURIAE_P = '<br/>[<span class="text-success"><b>P:A</b>micus <b>C</b>uriae</span>]';?>
                                                                                    @endif
                                                                                    @if (strtoupper($advocates->represents_petitioner_or_respondent) == 'R')
                                                                                        <?php $IS_AMICUS_CURIAE_R = '<br/>[<span class="text-success"><b>R:A</b>micus <b>C</b>uriae</span>]';?>
                                                                                    @endif
                                                                                @endif
                                                                            @endif
                                                                        @endforeach
                                                                    @endif
                                                                    <div><b>P: </b>{{ucwords(strtolower($amicus_curiae_user_soon_case->petitioner_name))}} <?=$IS_AMICUS_CURIAE_P;?></div>
                                                                    <div><b>R: </b>{{ucwords(strtolower($amicus_curiae_user_soon_case->respondent_name))}} <?=$IS_AMICUS_CURIAE_R;?></div>
                                                                <!--<div>
                        <span class="uk-label uk-background-muted uk-text-primary" style="text-transform: none;font-size:11px;">{{ucwords(strtolower(str_replace(']','',str_replace('[','',$scheduled_case->meta->listing->court->listing_sub_type))))}}</span>
                    </div>-->
                                                                </td>
                                                                <td class="uk-table-expand" uk-margin>
                                                                    <div>
                                                                        <button type="button" class="sc-button uk-button-text">{{date_format(date_create($amicus_curiae_user_soon_case->meta->listing->listed_on), 'D, jS M')}}&nbsp;<i uk-icon="triangle-down"></i></button>
                                                                        <div class="uk-padding-remove" uk-dropdown="pos:bottom-left;mode:click;">
                                                                            <ul class="uknav-parent-icon uk-dropdown-nav" uk-nav>
                                                                                <!--<li><a href="#"><span uk-icon="icon: file-text"></span> View in Causelist</a></li>-->

                                                                                @if(!empty($amicus_curiae_user_soon_case->office_reports->current->uri))
                                                                                    <li class="uk-nav-divider uk-margin-remove"></li>
                                                                                    <li><a href="{{$amicus_curiae_user_soon_case->office_reports->current->uri}}" target="_blank"><span uk-icon="icon: file-pdf"></span> Office Report</a></li>
                                                                                @endif
                                                                                @if(!empty($amicus_curiae_user_soon_case->rop_judgments->current->uri))
                                                                                    <li class="uk-nav-divider uk-margin-remove"></li>
                                                                                    <li><a href="{{$amicus_curiae_user_soon_case->rop_judgments->current->uri}}" target="_blank"><span uk-icon="icon: file-pdf"></span> Previous Order ({{$amicus_curiae_user_soon_case->rop_judgments->current->dated}})</a></li>
                                                                                @endif
                                                                                <li class="uk-nav-divider uk-margin-remove"></li>
                                                                                <li><a href="#" title="Send SMS" onClick="get_message_data(this.id)" id="<?php   echo $amicus_curiae_user_soon_case->diary_number .'#'.$amicus_curiae_user_soon_case->diary_year.'#'.$amicus_curiae_user_soon_case->registration_number.'#'.$amicus_curiae_user_soon_case->petitioner_name.'#'.$amicus_curiae_user_soon_case->respondent_name.'#'.$amicus_curiae_user_soon_case->item_number.'#'.$amicus_curiae_user_soon_case->meta->listing->court->name,'#'.date_format(date_create($amicus_curiae_user_soon_case->meta->listing->listed_on), 'D, jS M Y'); ?>"><span uk-icon="icon: comment"></span> Send SMS</a></li>
                                                                                <?php if($amicus_curiae_user_soon_case->vc_url!=null){?><li><a href="{{$amicus_curiae_user_soon_case->vc_url}}" target="_blank"><span uk-icon="icon: file-text"></span> Join Virtual Court</a></li>
                                                                                <?php } ?>
                                                                                <li>
                                                                                    <a href="#" onclick="javascript:loadPaperBookViewer(this);" data-paper-book-viewer-url="{{base_url("case/paper_book_viewer")}}/{{$amicus_curiae_user_soon_case->diary_id}}" targe="_blank" data-diary_no="@{{$amicus_curiae_user_soon_case->diary_id}}" data-diary_year="">
                                                                                        <span uk-icon="icon: bookmark"></span> Paper Book (with Indexing)
                                                                                    </a>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                    <div class="court-no-div">
                                                                        <span class="uk-label md-bg-grey-900  bg-black-court-no" uk-tooltip="{{$amicus_curiae_user_soon_case->meta->listing->court->listing_cum_board_type . str_replace(':','.',$amicus_curiae_user_soon_case->meta->listing->court->scheduled_time)}}">{{'Item '.($amicus_curiae_user_soon_case->item_number_alt ?: $amicus_curiae_user_soon_case->item_number)}} @ {{$amicus_curiae_user_soon_case->meta->listing->court->name}}</span>
                                                                    </div>
                                                                    <div>
                                                                        <small>
                                                                            <b>Bench:</b><br>
                                                                            {{ucwords(strtolower(implode(',<br> ', $amicus_curiae_user_soon_case->meta->listing->court->judges)))}}
                                                                        </small>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <!--<hr class="uk-divider-vertical uk-margin-small-left uk-margin-small-right uk-visible@m">-->
                                            @endif
                                        <!-- end amicus_curiae_user soon cases -->

                                        <!-- start sr advocate soon -->
                                        @if(!empty($sr_advocate_soon_cases))
                                        <!-- <div class="uk-width uk-width-large@l uk-margin-medium-top uk-overflow-auto uktable-responsive">
                                            <h4 class="uk-heading-bullet uk-text-bold">My cases <small class="uk-text-muted">soon to be listed</small></h4>
                                            <table class="uk-table uktable-justify uktable-striped uk-table-hover uk-table-divider" id="soon-to-be-listed-cases-table"> -->
                                        @if(!empty($sr_advocate_data))
                                        <div class="col-12 col-sm-12 col-md-3 col-lg-3">
                                            @else
                                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                                @endif
                                                <div class="dash-card">
                                                    <div class="title-sec">
                                                        <h5 class="unerline-title">My cases <small class="uk-text-muted">soon to be listed</small></h5>
                                                    </div>
                                                    <div class="table-sec">
                                                        <div class="table-responsive">
                                                            <table id="datatable-responsive-srAdv"
                                                                class="table table-striped custom-table">
                                                                <thead>
                                                                    <tr class="uk-text-bold">
                                                                        <th class="uk-text-bold">Case</th>
                                                                        <th class="uk-text-bold">Date & Bench</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php $i=1; ?>
                                                                    @foreach($sr_advocate_soon_cases as
                                                                    $sr_advocate_soon_case)
                                                                    <tr>
                                                                        <td data-key="Case" class="uk-width-small@m" style="width: 40%; text-align: left; align-items: left;">
                                                                            <div>
                                                                                <span class="uk-text-muted">{{$sr_advocate_soon_case->registration_number ?: ('D. No.' . $sr_advocate_soon_case->diary_number . '/' . $sr_advocate_soon_case->diary_year)}}</span>
                                                                            </div>
                                                                            <div><b style="font-size: 17px;">P: </b>{{ucwords(strtolower($sr_advocate_soon_case->petitioner_name))}}</div>
                                                                            <div><b style="font-size: 17px;">R: </b>{{ucwords(strtolower($sr_advocate_soon_case->respondent_name))}}</div>
                                                                            {{--<div>
                                                                                <span class="uk-label uk-background-muted uk-text-primary" style="text-transform: none;font-size:11px;">{{ucwords(strtolower(str_replace(']','',str_replace('[','',$scheduled_case->meta->listing->court->listing_sub_type))))}}</span>
                                                                            </div>--}}
                                                                        </td>
                                                                        <td data-key="Date & Bench" class="uk-table-expand" uk-margin width=60%>
                                                                            <div>
                                                                                <li>
                                                                                    <button type="button"
                                                                                        class="btn btn-primary accordion-button collapsed"
                                                                                        data-bs-toggle="collapse"
                                                                                        data-bs-target="#collapse<?php echo $i; ?>"
                                                                                        aria-expanded="false"
                                                                                        aria-controls="collapse<?php echo $i; ?>">{{date_format(date_create($sr_advocate_soon_case->meta->listing->listed_on), 'D, jS M')}}</button>
                                                                                    <ul id="collapse<?php echo $i; ?>"
                                                                                        class="submenu accordion-collapse collapse"
                                                                                        aria-labelledby="heading<?php echo $i; ?>"
                                                                                        data-bs-parent="#accordionExample">
                                                                                        @if(!empty($sr_advocate_soon_case->office_reports->current->uri))
                                                                                        <li><a class="btn-link"
                                                                                                href="{{$sr_advocate_soon_case->office_reports->current->uri}}"
                                                                                                target="_blank"><span
                                                                                                    uk-icon="icon: file-pdf"></span>
                                                                                                Office Report</a></li>
                                                                                        @endif
                                                                                        @if(!empty($sr_advocate_soon_case->rop_judgments->current->uri))
                                                                                        <li><a class="btn-link"
                                                                                                href="{{$sr_advocate_soon_case->rop_judgments->current->uri}}"
                                                                                                target="_blank"><span
                                                                                                    uk-icon="icon: file-pdf"></span>
                                                                                                Previous Order
                                                                                                ({{$sr_advocate_soon_case->rop_judgments->current->dated}})</a>
                                                                                        </li>
                                                                                        @endif
                                                                                        <li><a style="cursor: pointer;" title="Send SMS"
                                                                                                onClick="get_message_data(this.id)"
                                                                                                id="<?php echo $sr_advocate_soon_case->diary_number .'#'.$sr_advocate_soon_case->diary_year.'#'.$sr_advocate_soon_case->registration_number.'#'.$sr_advocate_soon_case->petitioner_name.'#'.$sr_advocate_soon_case->respondent_name.'#'.$sr_advocate_soon_case->item_number.'#'.$sr_advocate_soon_case->meta->listing->court->name,'#'.date_format(date_create($sr_advocate_soon_case->meta->listing->listed_on), 'D, jS M Y'); ?>"><span
                                                                                                    uk-icon="icon: comment"></span> Send
                                                                                                SMS</a></li>
                                                                                        <?php if($sr_advocate_soon_case->vc_url != NULL) { ?>
                                                                                        <li><a class="btn-link"
                                                                                                href="{{$sr_advocate_soon_case->vc_url}}"
                                                                                                target="_blank"><span
                                                                                                    uk-icon="icon: file-text"></span>
                                                                                                Join Virtual Court</a></li>
                                                                                        <?php } ?>
                                                                                        <li>
                                                                                            <a href="#"
                                                                                                onclick="javascript:loadPaperBookViewer(this);"
                                                                                                data-paper-book-viewer-url="<?php echo base_url("case/paper_book_viewer/".(string)$sr_advocate_soon_case->diary_id.""); ?>"
                                                                                                targe="_blank"
                                                                                                data-diary_no="@{{$sr_advocate_soon_case->diary_id}}"
                                                                                                data-diary_year="">
                                                                                                <span uk-icon="icon: bookmark"></span>
                                                                                                Paper Book (with Indexing)
                                                                                            </a>
                                                                                        </li>
                                                                                    </ul>
                                                                                </li>
                                                                            </div>
                                                                            <div class="court-no-div">
                                                                                <span class="uk-label md-bg-grey-900  bg-black-court-no"
                                                                                    uk-tooltip="{{$sr_advocate_soon_case->meta->listing->court->listing_cum_board_type . str_replace(':','.',$sr_advocate_soon_case->meta->listing->court->scheduled_time)}}">{{'Item '.($sr_advocate_soon_case->item_number_alt ?: $sr_advocate_soon_case->item_number)}}
                                                                                    @
                                                                                    {{$sr_advocate_soon_case->meta->listing->court->name}}</span>
                                                                            </div>
                                                                            <div>
                                                                                <small>
                                                                                    <b>Bench:</b><br>
                                                                                    {{ucwords(strtolower(implode(',<br> ', $sr_advocate_soon_case->meta->listing->court->judges)))}}
                                                                                </small>
                                                                            </div>
                                                                        </td>
                                                        </tr>
                                                        <?php $i++; ?>
                                                        @endforeach
                                                        </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- <hr class="uk-divider-vertical uk-margin-small-left uk-margin-small-right uk-visible@m"> -->
                                        @endif
                                        <!-- end sr advocate soon -->
                                        <!-- start sr advocate data -->
                                        @if(!empty($sr_advocate_data))
                                        <!-- <div class="col-12 col-sm-12 col-md-12 col-lg-9"> -->
                                            @if(!empty($sr_advocate_data))
                                            <div class="col-12 col-sm-12 col-md-3 col-lg-12">
                                                @else
                                                <div class="col-12 col-sm-12 col-md-12 col-lg-9">
                                                    @endif
                                            <div class="dash-card">
                                                <div class="title-sec">
                                                    <h5 class="unerline-title">My Cases <small class="uk-text-muted">assigned by AOR</small></h5>
                                                    <!-- <a href="javascript:void(0)" class="quick-btn pull-right" onclick="window.history.back()"><span class="mdi mdi-chevron-double-left"></span>Back</a> -->
                                                    <a href="javascript:void(0)" onclick="window.history.back()" class="quick-btn pull-right"><span class="mdi mdi-chevron-double-left"></span>Back</a>
                                                </div>
                                                <div class="table-sec">
                                                    <div class="table-responsive">
                                                        <table class="table table-striped custom-table" id="efiled-cases-table">
                                                            <thead>
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th>Case Number</th>
                                                                    <th>Cause Title</th>
                                                                    <th style="width: 90px;">Status</th>
                                                                    <th>Engaged By/Date</th>
                                                                    <th>Paper Book</th>
                                                                    <!-- <th>...</th>-->
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $n=1;
                                                                if(isset($sr_advocate_data['details']) && !empty($sr_advocate_data['details']))
                                                                {
                                                                    foreach ($sr_advocate_data['details'] as $k=>$v)
                                                                    {
                                                                        $diary_no = !empty($v->diary_no) ? $v->diary_no : '';
                                                                        $reg_no_display = !empty($v->reg_no_display) ? $v->reg_no_display : '';
                                                                        $cause_title = (!empty($v->pet_name) && !empty($v->res_name))  ? ($v->pet_name.' Vs '.$v->res_name) : '';
                                                                        $c_status = (!empty($v->c_status) && $v->c_status == 'P') ? 'Pending' : '';
                                                                        $createdAt = !empty($v->createdAt) ? date('d/m/Y H:i:s',strtotime($v->createdAt)) : '';
                                                                        $assignedby = !empty($v->assignedby) ? $v->assignedby : '';
                                                                        echo '<tr>
                                                                            <td data-key="#">'.$n.'</td>
                                                                            <td data-key="Case Number">'.$diary_no.'<br/>'.$reg_no_display.'</td>
                                                                            <td data-key="Cause Title">'.$cause_title.'</td>
                                                                            <td data-key="Status">'.$c_status.'</td>
                                                                            <td data-key="Engaged By/Date">'.$assignedby.'<br/>'.$createdAt.'</td>
                                                                            <td data-key="Paper Book">
                                                                                <a href="#" onclick="javascript:loadPaperBookViewer(this);" data-paper-book-viewer-url="'.base_url("case/paper_book_viewer").'/'.$diary_no.'" targe="_blank" data-diary_no="'.$diary_no.'" data-diary_year="">
                                 View
                                </a>
                                                                            </td>
                                                                        </tr>';
                                                                        $n++;
                                                                    }
                                                                }
                                                                ?>
                                                                <!-- <a href="javaScript:void(0)" onClick="open_paper_book()" class="" data-diary_no="'.$diary_no.'" data-diary_year="">View</a>-->
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        <!-- end sr advocate data -->
                                        @if(!in_array($_SESSION['login']['ref_m_usertype_id'],array(ARGUING_COUNSEL,SR_ADVOCATE)))
                                        @if(!empty($scheduled_cases) && (is_array($scheduled_cases) || is_object($scheduled_cases)) && (isset($scheduled_cases[0]) || !empty($scheduled_cases[0])) && (count($scheduled_cases[0])>0))
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-8">
                                            @else
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                            @endif
                                            <div class="dash-card">
                                                <div class="title-sec">
                                                    <h5 class="unerline-title">e-Filed Cases</h5>
                                                </div>
                                                <div class="table-sec efiled-cases-table">
                                                    <div class="table-responsive" style="height: 800px; overflow-x: overlay;">
                                                        <table id="datatable-responsive"
                                                            class="table table-striped custom-table ">
                                                            <thead>
                                                                <tr>
                                                                    <th>Sr. No.</th>
                                                                    <th>Stage</th>
                                                                    <th>eFiling No.</th>
                                                                    <th>Type</th>
                                                                    <th>Case Detail</th>
                                                                    <th>Submitted On</th>
                                                                    <th>...</th>
                                                                    <th>Allocated To DA</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                   
                                                </div>
                                            </div>
                                        </div>

                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if(!in_array($_SESSION['login']['ref_m_usertype_id'],array(ARGUING_COUNSEL,SR_ADVOCATE)))
                        <div class="col-12 sm-12 col-md-12 col-lg-3 middleContent-right">
                            <div class="right-content-inner comn-innercontent">
                                <div class="dashboard-section">
                                    <div class="row">
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="dash-card">
                                                <!-- <div class="title-sec"> -->
                                                    <!-- <h5 class="unerline-title">My e-Filed Cases</h5> -->
                                                    <div class="end-buttons mt-0">
                                                       <button class="btn btn-secondary" onclick="showAllCases();" id="showAllCases">Show All e-Filed Cases</button>
                                                       <!-- <button class="btn btn-secondary" onclick="showAllCases();" id="showAllCases">Show All e-Filed Cases</button> -->
                                                    </div>
                                                <!-- </div> -->
                                                <div class="calender-sec">
                                                    <div id="calendar"></div>
                                                </div>
                                                <!-- <div id='efiling-details'>
                                                    <div class="title-sec">
                                                        <h5 class="unerline-title">Allocated Cases</h5>
                                                    </div>
                                                    <div class="table-responsive">
                                                        <table id="calendar-cases" class="table table-striped custom-table">
                                                            <thead>
                                                                <tr>
                                                                    <th>eFiling No.</th>
                                                                    <th>Date & Time</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="efiling"></tbody>
                                                        </table>
                                                    </div>
                                                </div> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="mail" uk-modal class="common-modal">
        <div class="uk-modal-dialog" id="view_contacts_text" align="center">
            <h4> SMS CASE DETAILS <div id="mail_d"></div>
            </h4>
            <!-- <input type="text" name="<?php // echo $this->security->get_csrf_token_name();?>" value="<?php // echo $this->security->get_csrf_hash();?>" placeholder="csrf token"> -->
            <button class="uk-modal-close-default quick-btn"  type="button" uk-close></button>
            <div class="uk-modal-body">
                To: <input type="text" class="form-control cus-form-ctrl" id="recipient_mobile_no" name="recipient_mobile_no" minlength="10" maxlength="10" placeholder="Recipient's Mobile Number">
                <br>
                Message Text: <div id='caseinfosms'></div>
            </div>
            <div class="uk-modal-footer uk-text-right modal-footer" id="con_footer">
            <div class="center-buttons">
                <button class="quick-btn gray-btn uk-button-default uk-modal-close" type="button">Cancel</button>
                <!-- <input type="button" id="send_sms" value="Send SMS " class="quick-btn"
                    onclick="send_sms()"> -->
                    <button type="button" id="send_sms"  class="quick-btn"
                    onclick="send_sms()">Send SMS</button>
            </div>
            </div>
        </div>
    </div>
    <div id="paper-book-viewer-modal" class="uk-modal-full" uk-modal="bg-close:false;esc-close:false;">
        <div class="uk-modal-dialog" uk-overflow-auto>
            <button class="uk-modal-close-full uk-close-large" type="button" uk-close></button>
            <iframe src="" height="100%" width="100%" scrolling frameborder="no" uk-height-viewport></iframe>
        </div>
    </div>
    @endsection
    <script src="<?= base_url() . 'assets/newAdmin/' ?>js/jquery351.min.js"></script>
    <link href="<?= base_url() ?>calender/main.min.css" rel='stylesheet' />
    <script src="<?= base_url() ?>calender/main.min.js"></script>
    <script src="<?= base_url() ?>calender/locales-all.min.js"></script>
 
    <script src="<?= base_url() ?>assets/newAdmin/js/angular.min.js"></script>
    <script src="{{base_url('assets/responsive_variant/frameworks/uikit_3-4-1/js/uikit.min.js')}}"></script>
    <script src="{{base_url('assets/responsive_variant/frameworks/uikit_3-4-1/js/uikit-icons.min.js')}}"></script>
 
    <script>
    function loadPaperBookViewer(obj){
        // alert(obj);
        $('#paper-book-viewer-modal iframe').attr('src', $(obj).data('paper-book-viewer-url'));
        UIkit.modal('#paper-book-viewer-modal').show();
    }
    $(document).ready(function() {
        $('#datatable-responsive-srAdv').DataTable();
        $('#efiled-cases-table').DataTable();
    });
    $(document).ready(function() {
        $('#datatable-responsive-sc_cases').DataTable({
            // "bPaginate": false,
            "bLengthChange": false,
            "bFilter": false,
            "bInfo": false,
            "bAutoWidth": false,
            "pageLength": 5,
            "bSort": false,
            // "lengthMenu": [[5, 10, 20, -1], [5, 10, 20, 'Todos']]
        });
        $('#datatable-responsive').DataTable({
            "bPaginate": false,
            "bLengthChange": false,
            "bFilter": false,
            "bInfo": false,
        });

        
    });
    </script>
    <script>
    $(document).ready(function() {
        $("#byMe").click(function() {
            $("#showByMe").hide();
            $("#showByOthers").show();
        });
        $("#byOthers").click(function() {
            $("#showByOthers").hide();
            $("#showByMe").show();
        });
    });
    </script>
    <script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: function(fetchInfo, successCallback, failureCallback) {
                $.ajax({
                    url: '<?php echo base_url(); ?>dashboard_alt/getDailyCaseCounts',
                    dataType: 'json',
                    success: function(data) {
                        var events = [];
                        data.forEach(event => {
                            var existingEvent = events.find(e => e.start ===
                                event.start);
                            if (!existingEvent) {
                                events.push({
                                    title: event.count,
                                    start: event.start
                                });
                            }
                        });
                        successCallback(events);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching events:', error);
                        failureCallback(error);
                    }
                });
            },
            eventContent: function(arg) {
                let titleEl = document.createElement('div');
                titleEl.innerText = arg.event.title;
                let arrayOfDomNodes = [titleEl];
                return {
                    domNodes: arrayOfDomNodes
                };
            },
            eventClick: function(info) {
                var start = moment(info.event.start).format('YYYY/MM/DD');
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $.ajaxSetup({
                    headers: {
                        'CSRF_TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '<?php echo base_url(); ?>dashboard_alt/getDayCaseDetails',
                    method: "POST",
                    data: {
                        start: start,
                        CSRF_TOKEN: "<?php echo csrf_hash(); ?>",
                    },
                    // dataType: 'json',
                    beforeSend: function() {
                        $('#loader-wrapper').show();
                        var loaderTimeout = setTimeout(function() {
                            $('#loader-wrapper').fadeOut('slow', function() {
                                $('#content').fadeIn('slow');
                            });
                        }, 1000);
                    },
                    success: function(res) {
                        $('#datatable-responsive').html('');
                        $('#datatable-responsive').html(res);
                        $('#datatable-responsive').DataTable().destroy();
                        $('#datatable-responsive').DataTable({
                            "bPaginate": true,
                            "bLengthChange": false,
                            "bFilter": false,
                            "bInfo": false,
                            "bAutoWidth": false,
                            "pageLength": 10,
                            "searching": true
                            // "lengthMenu": [[5, 10, 20, -1], [5, 10, 20, 'Todos']]
                        });
                        $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        });
                    },
                    error: function(xhr, status, error) {
                        var Table = document.getElementById("datatable-responsive");
                        Table.innerHTML = "";
                        $('#datatable-responsive').append('<tr><td colspan="8">No Records Found!</td></tr>');
                        $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        });
                    }
                });
            }
        });
        calendar.render();
    });

    function showAllCases(){
        // window.location.reload();
        $.ajax({
            url: '<?php echo base_url(); ?>e_filed_cases',
            method: "GET",
            // dataType: 'json',
            beforeSend: function() {
                $('#loader-wrapper').show();
                var loaderTimeout = setTimeout(function() {
                    $('#loader-wrapper').fadeOut('slow', function() {
                        $('#content').fadeIn('slow');
                    });
                }, 3000);
            },
            success: function(res) {
                $('#datatable-responsive').DataTable().destroy();
                $('#datatable-responsive').html('');
                $('#datatable-responsive').html(res);

            },
            error: function(xhr, status, error) {
                
            }
        });
    }
    // function showAllCases(){
    //     $.ajax({
    //         url: '<?php echo base_url(); ?>dashboard_alt/getDayCaseDetails',
    //         method: "POST",
    //         data: {
    //             start: ''
    //         },
    //         // dataType: 'json',
    //         beforeSend: function() {
    //             $('#loader-wrapper').show();
    //             var loaderTimeout = setTimeout(function() {
    //                 $('#loader-wrapper').fadeOut('slow', function() {
    //                     $('#content').fadeIn('slow');
    //                 });
    //             }, 1000);
    //         },
    //         success: function(res) {
    //             // $('#datatable-responsive').DataTable();
    //             // var table = $('#datatable-responsive').DataTable().destroy();
    //             $('#datatable-responsive').html('');
    //             $('#datatable-responsive').html(res);
    //             // $('#datatable-responsive').DataTable().reload();

    //         },
    //         error: function(xhr, status, error) {
    //             var Table = document.getElementById("datatable-responsive");
    //             Table.innerHTML = "";
    //             $('#datatable-responsive').append('<tr><td colspan="8">No Records Found!</td></tr>');
    //         }
    //     });
    // }

    function get_message_data(id) {
        UIkit.modal('#mail').toggle();
        var x = id.split("#");
        if (x[2] == '')
            document.getElementById('caseinfosms').innerHTML = "Diary No. " + x[0] + "/" + x[1] + " - " + x[3] +
            " VS " + x[4] + " is to be listed on " + x[7] + " in " + x[6] + " as item " + x[5] +
            " subject to order for the day";
        else
            document.getElementById('caseinfosms').innerHTML = "Case No." + x[2] + " - " + x[3] + " VS " + x[4] +
            " is to be listed on " + x[7] + " in " + x[6] + " as item " + x[5] + " subject to order for the day";

    }

    function send_sms() {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var message = document.getElementById("caseinfosms").innerHTML;
        var mobile_no = $("#recipient_mobile_no").val();
        // Regular expression to allow only numbers and limit to 10 digits
        const regex = /^[0-9]{0,10}$/;
        // Check if the input matches the regex
        if (!regex.test(mobile_no)) {
            // If the input doesn't match, prevent the change
            alert('Mobile Number should be numeric and of maximum 10 digits only.');
            event.preventDefault();
            return false;
        }
        // Attach the validation function to the input field
        const inputField = document.getElementById('recipient_mobile_no');
        // inputField.addEventListener('input', validateInput);
        $.ajax({
            type: "POST",
            data: {
                message: message,
                mobile_no: mobile_no,
                CSRF_TOKEN: CSRF_TOKEN_VALUE,
            },
            url: "<?php echo base_url('mycases/citation_notes/send_sms'); ?>",
            success: function(data) {
                var resArr = data.split('@@@');
                if (resArr[0] == 1)
                    alert(resArr[1]);
                else if (resArr[0] == 2) {
                    alert(resArr[1]);
                    $('#recipient_mobile_no').val('');
                    document.getElementById("caseinfosms").innerHTML = '';
                    UIkit.modal('#mail').toggle();
                }
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            },
            error: function(result) {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
    }
    $('body').attr('ng-app', 'dashboardApp').attr('ng-controller', 'dashboardController');
    var mainApp = angular.module("dashboardApp", []);
    mainApp.directive('onFinishRender', function($timeout) {
        return {
            restrict: 'A',
            link: function(scope, element, attr) {
                if (scope.$last === true) {
                    $timeout(function() {
                        scope.$emit(attr.onFinishRender);
                    });
                }
            }
        }
    });
    mainApp.directive('ngFocusModel', function($timeout) {
        return {
            link: function(scope, element, attrs) {
                element.bind('click', function() {
                    $timeout(function() {
                        jQuery('[ng-model="' + attrs.ngFocusModel + '"]').focus();
                    });
                })
            }
        }
    });
    mainApp.directive('ngFocus', function($timeout) {
        return {
            link: function(scope, element, attrs) {
                element.bind('click', function() {
                    $timeout(function() {
                        jQuery(attrs.setFocus).focus();
                    });
                })
            }
        }
    });
    mainApp.controller('dashboardController', function($scope, $http, $filter, $interval, $compile) {});
    $(function() {
        ngScope = angular.element($('[ng-app="dashboardApp"]')).scope();
        $('.documents-widget,.my-documents-widget,.applications-widget,.defects-widget [uk-drop]').on('shown',
            function() {
                $('#content > *:not(#widgets-container)').addClass('sci-blur-medium');
            }).on('hidden', function() {
            $('#content > *:not(#widgets-container)').removeClass('sci-blur-medium');
        });

        scutum.require(['datatables', 'datatables-buttons'], function() {
            //    $('#myTable').DataTable();
            /*$('#soon-to-be-listed-cases-table').DataTable({
                "pageLength": 100
            });*/
            $('#efiled-cases-table').DataTable({
                "bSort": false,
                initComplete: function() {
                    this.api().columns().every(function(indexCol, thisCol) {
                        var columnIndex = [];
                        if (userTypeId && userTypeId == 19) {
                            columnIndex[0] = 3;
                        } else {
                            columnIndex[0] = 1;
                            columnIndex[1] = 3;
                        }
                        if ($.inArray(this.selector.cols, columnIndex) !== -1) {
                            var column = this;
                            var select = $(
                                '<select class="uk-select"><option value="">' +
                                this.context[0].aoColumns[this.selector.cols]
                                .sTitle + '</option></select>').appendTo($(
                                column.header()).empty()).on('change',
                            function() {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );
                                column.search(val ? '^' + val + '$' : '',
                                    true, false).draw();
                            });
                            column.data().unique().sort().each(function(d, j) {
                                select.append('<option value="' + d + '">' +
                                    d + '</option>')
                            });
                        }
                    });
                }
            });
        });
    });
$(document).ready(function(){
    var CSRF_TOKEN = 'CSRF_TOKEN';
    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
    $.ajax({
        url: '<?php echo base_url(); ?>e_filed_cases',
        method: "GET",
        data:{
            CSRF_TOKEN: CSRF_TOKEN_VALUE,
        },
        // dataType: 'json',
        beforeSend: function() {
            $('#loader-wrapper').show();
            var loaderTimeout = setTimeout(function() {
                $('#loader-wrapper').fadeOut('slow', function() {
                    $('#content').fadeIn('slow');
                });
            }, 3000);
        },
        success: function(res) {
            $('#datatable-responsive').html('');
            $('#datatable-responsive').html(res);
            $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
            });
        },
        error: function(xhr, status, error) {
            $.getJSON("<?php echo base_url('csrftoken'); ?>", function(result) {
                $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
            });
        }
    });
    });

    // $("#userTables_filter").addClass("col-6");
    </script>
       