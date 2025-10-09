@extends('layout.app')
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
                                <h5 class="unerline-title">Advocates Portal - Login & Appearance Report</h5>
                                <a href="javascript:void(0)" onclick="window.history.back()" class="quick-btn pull-right"><span class="mdi mdi-chevron-double-left"></span>Back</a>
                            </div>
                            <section class="content">
                                <div class="container-fluid">
                                    <div class='row'>
                                        <div class="col-md-12">
                                            <div class="card card-primary">
                                                <br />
                                                <?php
                                                echo form_open('', 'method="GET" class="form-horizontal"');
                                                $this->request = \Config\Services::request();
                                                ?>
                                                    <!-- <input type="hidden" name="_token" value="{{ csrf_token() }}"> -->
                                                    <label class="radio-inline" for="otp">
                                                        <input type="radio" name="report_type" id="otp" value="otp" <?php echo $this->request->getVar('report_type') == 'otp' ? 'checked' : ''; ?> onchange="this.form.submit()"> Advocates Login Report
                                                    </label>
                                                    <label class="radio-inline" for="diary">
                                                        <input type="radio" name="report_type" id="diary" value="diary" <?php echo $this->request->getVar('report_type') == 'diary' ? 'checked' : ''; ?> onchange="this.form.submit()"> Appearing In Diary Report
                                                    </label>
                                                    <label class="radio-inline" for="submissionlogs">
                                                        <input type="radio" name="report_type" id="submissionlogs" value="submissionlogs" <?php echo $this->request->getVar('report_type') == 'submissionlogs' ? 'checked' : ''; ?> onchange="this.form.submit()"> Appearing Logs Report
                                                    </label>
                                                    <?php
                                                echo form_close();
                                                if ($this->request->getVar('report_type') == 'otp' && $this->request->getVar('report_type') != 'diary' && $this->request->getVar('report_type') != 'submissionlogs') {
                                                    render('appearance.reports.partials.otp-report');
                                                } elseif ($this->request->getVar('report_type') == 'diary' && $this->request->getVar('report_type') != 'otp' && $this->request->getVar('report_type') != 'submissionlogs') {
                                                    render('appearance.reports.partials.diary-report');
                                                } elseif ($this->request->getVar('report_type') == 'submissionlogs' && $this->request->getVar('report_type') != 'otp' && $this->request->getVar('report_type') != 'diary') {
                                                    render('appearance.reports.partials.submission-logs-report');
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection