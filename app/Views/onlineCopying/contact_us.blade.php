<?php
if (isset(session()->get('login')['impersonator_user']) && isset(session()->get('login')['impersonator_user']->is_active) && !empty(session()->get('login')['impersonator_user']) && session()->get('login')['impersonator_user']->is_active == 1) {
    $extends = 'layout.advocateApp';
} else {
    $extends = 'layout.ecopyApp';
}
?>
@extends($extends)
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="dash-card dashboard-section">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <div class=" dashboard-bradcrumb">
                            <div class="left-dash-breadcrumb">
                                <div class="page-title">
                                    <h5><i class="fa fa-file"></i> Contact Us </h5>
                                </div>
                                <div class="form-response" id="msg" role="alert" data-auto-dismiss="5000"></div>
                            </div>
                            <div class="ryt-dash-breadcrumb">
                                <div class="btns-sec">
                                    <a href="javascript:void(0)" onclick="window.history.back()" class="quick-btn pull-right"><span class="mdi mdi-chevron-double-left"></span>Back</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="dash-card dashboard-section">
                <div class="row">
                    <div class="panel panel-default">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="right_col" role="main">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="x_panel">
                                            <div class="x_content">
                                                <div class="row">
                                                    <h4 class="center">Copying Section,</h4>
                                                    <div class="col-lg-5"></div>
                                                    <div class="col-lg-7">
                                                        <div class="align-left">
                                                            <!-- <h4>The Registrar,</h4>
                                                            <h5>Supreme Court of India,</h5>
                                                            <h6>Tilak Marg, New Delhi - 110001</h6>
                                                            <p>011-23388922-24,23388942</p>
                                                            <p>FAX : 011-23381508,23381584</p>
                                                            <p>e-mail : efiling[at]sci[dot]nic[dot]in</p> -->
                                                            <h5>Office : Supreme Court of India,</h5>
                                                            <h6>Address : Tilak Marg, New Delhi - 110001</h6>
                                                            <p>Phone : 011-23112081</p>
                                                            <p>E-Mail : supremecourt[at]nic[dot]in</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- Main End --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection