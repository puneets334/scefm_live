<link rel="shortcut icon" href="<?= base_url().'assets/newDesign/images/logo.png' ?>" type="image/png" />
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/bootstrap.min.css" rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/font-awesome.min.css" rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/animate.css" rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/material.css" rel="stylesheet" />
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/style.css" rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/responsive.css" rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/black-theme.css" rel="stylesheet">
<link href="<?= base_url() . 'assets/newAdmin/' ?>css/fullcalendar.css" rel="stylesheet">
<link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" type="text/css" href="<?= base_url() . 'assets/newAdmin/' ?>css/jquery.dataTables.min.css">
<?php if (!empty($errors)) { ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
<?php } ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="dashboard-section dashboard-tiles-area"></div>
            <div class="dashboard-section">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="dash-card">
                            <?php
                            $this->request = \Config\Services::request();
                            if ($this->request->getGet('report_type') && ($this->request->getGet('otp_mobile') || $this->request->getGet('otp_email') || $this->request->getGet('otp_login_time'))) { ?>
                                <section class="content">
                                    <div class="container-fluid">
                                        <div class='row'>
                                            <div class="col-md-12 mt-3 mb-3">
                                                <div class="card-primary">
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
                                                    <?php echo form_close(); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            <?php } ?>
                            {{-- Page Title Start --}}
                            <?php echo form_open('', 'method="GET" class="row g-3" id="otpForm"'); ?>
                                <!-- <input type="hidden" name="_token" value="{{ csrf_token() }}"> -->
                                <input type="hidden" name="report_type" value="otp">
                                <div class="col-md-3">
                                    <label for="inputMobile" class="form-label">Mobile</label>
                                    <input type="text" name="otp_mobile" id="otp_mobile" min="0" maxlength="10" class="form-control cus-form-ctrl" value="<?= isset($_GET['otp_mobile']) ? $_GET['otp_mobile'] : ''; ?>" placeholder="Mobile" tabindex="1">
                                </div>
                                <div class="col-md-3">
                                    <label for="inputEmail" class="form-label">Email</label>
                                    <input type="email" name="otp_email" id="otp_email" class="form-control cus-form-ctrl" value="<?= isset($_GET['otp_email']) ? $_GET['otp_email'] : ''; ?>" placeholder="Email" tabindex="2">
                                </div>
                                <div class="col-3">
                                    <label for="inputLogin Date" class="form-label">Login Date</label>
                                    <input type="text" name="otp_login_time" id="otp_login_time" class="form-control cus-form-ctrl otp_login_time" value="<?= isset($_GET['otp_login_time']) ? $_GET['otp_login_time'] : ''; ?>" placeholder="DD-MM-YYYY" tabindex="3">
                                </div>
                                <div class="col-3" style="margin-top: 3% !important;">
                                    <button type="submit" class="quick-btn btn btn-success" style="margin-top: -6% !important;" tabindex="4">Filter</button>
                                    <a href="<?= base_url('combined-reports'); ?>" class="quick-btn gray-btn" tabindex="5">Clear Filter</a>
                                </div>
                                <div></div>
                            <?php echo form_close(); ?>
                            <div id="form-error" class="alert alert-danger d-none"> Please fill at least one of the fields: Mobile, Email, or Login Date.</div>
                            @if (!empty($otpRecords))
                                <!-- <div class="row align-items-end">
                                    <div class="col-md-12 d-flex justify-content-end">
                                        <button type="button" onclick="window.print()" class="btn btn-secondary">
                                            Download as PDF
                                        </button>
                                    </div>
                                </div> -->
                                <?php
                                $labels = [];
                                $this->request = \Config\Services::request();
                                if ($this->request->getGet('report_type')) {
                                    $labels[] = 'Report Type: <strong>Login</strong>';
                                }
                                if ($this->request->getGet('otp_mobile')) {
                                    $labels[] = 'Mobile No: <strong>' . $this->request->getGet('otp_mobile') . '</strong>';
                                }
                                if ($this->request->getGet('otp_email')) {
                                    $labels[] = 'Email: <strong>' . $this->request->getGet('otp_email') . '</strong>';
                                }
                                if ($this->request->getGet('otp_login_time')){
                                    try {
                                        $loginTime = \Carbon\Carbon::parse($this->request->getGet('otp_login_time'))->format('d-m-Y');
                                    } catch (\Exception $e) {
                                        $loginTime = $this->request->getGet('otp_login_time'); // fallback
                                    }
                                    $labels[] = 'Login Time: <strong>' . $loginTime . '</strong>';
                                }
                                ?>
                                <div id="printable-area">
                                    @if (count($labels))
                                        <h4 class="text-center">{!! implode(' | ', $labels) !!}</h4>
                                    @endif
                                    <table class="table table-bordered table-striped w-100 text-center align-middle" id="datatable-responsive">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Mobile</th>
                                                <th>Email</th>
                                                <!-- <th>OTP</th> -->
                                                <th class="list-date-col">Entry Date</th>
                                                <th class="list-date-col">Login Time</th>
                                                <th>Status</th>
                                                <!-- <th>Count</th> -->
                                                <th>IP</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($otpRecords as $key => $record)
                                                <tr>
                                                    <td>{{ $key+1 }}</td>
                                                    <td>{{ $record->moblie_number }}</td>
                                                    <td>{{ $record->emailid }}</td>
                                                    <!-- <td>{{ $record->otp }}</td> -->
                                                    <td class="list-date-col">{{ \Carbon\Carbon::parse($record->login_time)->format('d-m-Y H:i:s') }}</td>
                                                    <td class="list-date-col">{{ \Carbon\Carbon::parse($record->login_time)->format('d-m-Y H:i:s')  }}</td>
                                                    <td>{{ $record->is_successful_login == 't' ? 1 : '' }}</td>
                                                    <!-- <td>{{ $record->otp_count }}</td> -->
                                                    <td>{{ $record->ip_address }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                @if (!empty($_GET['otp_mobile']) || !empty($_GET['otp_email']) || !empty($_GET['otp_login_time']))
                                    <div class="alert alert-info">No records found.</div>
                                @endif
                            @endif
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
<script src="<?= base_url() ?>assets/js/bootstrap-datepicker.min.js"></script>  
<script src="<?= base_url() ?>assets/js/sha256.js"></script>
<script src="<?= base_url() ?>assets/newAdmin/js/angular.min.js"></script>
<script src="<?= base_url() ?>assets/newAdmin/js/moment.min.js"></script>
<script src="<?= base_url() ?>assets/newAdmin/js/fullcalendar.min.js"></script>
<script src="<?= base_url() ?>assets/newAdmin/js/jquery.validate.min.js"></script>
<script src="<?= base_url() ?>assets/newAdmin/js/jquery.dataTables.min.js"></script>
@stack('script')
<script>
    $(document).ready(function() {
        $('#datatable-responsive').DataTable();
    });
    document.getElementById('otpForm').addEventListener('submit', function(e) {
        const mobile = document.getElementById('otp_mobile').value.trim();
        const email = document.getElementById('otp_email').value.trim();
        const loginTime = document.getElementById('otp_login_time').value.trim();
        const errorBox = document.getElementById('form-error');
        if (!mobile && !email && !loginTime) {
            e.preventDefault();
            errorBox.classList.remove('d-none');
        } else {
            errorBox.classList.add('d-none');
        }
    });
    $(document).ready(function() {
        var today = new Date();
        var startDate = new Date();
        startDate.setFullYear(today.getFullYear() - 40);
        $('.otp_login_time').datepicker({
            format: "dd-mm-yyyy",
            showOtherMonths: true,
            selectOtherMonths: true,
            changeMonth: true,
            changeYear: true,
            endDate: today,
            defaultDate: today,
            autoclose: true
        });
    });
</script>