@extends('layout.ecopyApp')
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
</style>
<div class="mainPanel ">
    <div class="panelInner">
        <div class="middleContent">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 sm-12 col-md-12 col-lg-12 middleContent-left">
                        <div class="dashboard-section dashboard-tiles-area">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                    <div class="dashbord-tile pink-tile" tabindex="0">
                                        <div >
                                            <h6 class="tile-title" tabindex="0">Online</h6>
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
                                        <!--End 2nd grid-->
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                    <div class="dashbord-tile purple-tile" tabindex="0">
                                        <h6 class="tile-title" tabindex="0">Offline</h6>
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
                                </div>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-4">
                                    <div class="dashbord-tile blue-tile" tabindex="0">
                                        <h6 class="tile-title" tabindex="0">Document Requested</h6>
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
            // "lengthMenu": [[5, 10, 20, -1], [5, 10, 20, 'Todos']]
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
