@if(getSessionData('login') != '' && getSessionData('login')['ref_m_usertype_id'] == USER_ADMIN)
    @extends('layout.app')
@else
    @extends('layout.advocateApp')
@endif
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="dashboard-section dashboard-tiles-area"></div>
                <div class="dashboard-section">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="dash-card">
                                {{-- Page Title Start --}}
                                <div class="title-sec">
                                    <h5 class="unerline-title"> Contact Us </h5>
                                    <!-- <a href="javascript:void(0)" class="quick-btn pull-right" onclick="window.history.back()"><span class="mdi mdi-chevron-double-left"></span>Back</a> -->
                                    <a href="javascript:void(0)" onclick="window.history.back()" class="quick-btn pull-right"><span class="mdi mdi-chevron-double-left"></span>Back</a>
                                </div>
                                {{-- Page Title End --}}
                                {{-- Main Start --}}
                                @if (getSessionData('login') != '' && getSessionData('login')['ref_m_usertype_id'] != USER_ADMIN)
                                    <ul class="nav nav-tabs">
                                        <li class="nav-item">
                                            <a href="<?= base_url('assistance/notice_circulars'); ?>" aria-current="page" class="nav-link">Circulars</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="<?= base_url('assistance/performas'); ?>" aria-current="page" class="nav-link">Performas</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="<?= base_url('contact_us'); ?>" aria-current="page" class="nav-link active">Contact Us</a>
                                        </li>
                                    </ul>
                                @endif
                                <div class="right_col" role="main">
                                    <div class="">
                                        <div class="clearfix"></div>
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <div class="x_panel">
                                                    <div class="x_title">
                                                        <h2> <i class="fa fa-envelope"></i> Contact Us</h2>
                                                        <div class="clearfix"></div>
                                                    </div>
                                                    <div class="x_content">
                                                        <div class="row">
                                                            <div class="col-lg-3">
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="center">
                                                                    <h4>The Registrar,</h4>
                                                                    <h5>Supreme Court of India,</h5>
                                                                    <h6>Tilak Marg, New Delhi - 110001</h6>
                                                                    <p>011-23388922-24,23388942</p>
                                                                    <p>FAX : 011-23381508,23381584</p>
                                                                    <p>e-mail : efiling[at]sci[dot]nic[dot]in</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-3">
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