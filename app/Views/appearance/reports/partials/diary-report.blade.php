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
            <?php foreach ($errors->all() as $error) { ?>
                <li><?php echo $error; ?></li>
            <?php } ?>
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
                            if ($this->request->getGet('report_type') && ($this->request->getGet('diary_no') || $this->request->getGet('list_dates') || $this->request->getGet('aor_code'))) { ?>
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
                            <?php echo form_open('', 'method="GET" class="row g-3" id="diaryForm"'); ?>
                                <!-- <input type="hidden" name="_token" value="{{ csrf_token() }}"> -->
                                <input type="hidden" name="report_type" value="diary">
                                <div class="col-md-3">
                                    <label for="inputMobile" class="form-label">Diary No</label>
                                    <input type="text" name="diary_no" id="diary_no" class="form-control cus-form-ctrl" value="<?= isset($_GET['diary_no']) ? $_GET['diary_no'] : ''; ?>" placeholder="Diary No" tabindex="1">
                                </div>
                                <div class="col-md-3">
                                    <label for="inputEmail" class="form-label">AOR Code</label>
                                    <input type="text" name="aor_code" id="aor_code" class="form-control cus-form-ctrl" value="<?= isset($_GET['aor_code']) ? $_GET['aor_code'] : ''; ?>" placeholder="AOR Code" tabindex="2">
                                </div>
                                <div class="col-3">
                                    <label for="inputLogin Date" class="form-label">List Date</label>
                                    <input type="text" name="list_dates" id="list_dates" class="form-control cus-form-ctrl list_dates" value="<?= isset($_GET['list_dates']) ? $_GET['list_dates'] : ''; ?>" placeholder="DD-MM-YYYY" tabindex="3">
                                </div>
                                <div class="col-3" style="margin-top: 3% !important;">
                                    <button type="submit" class="quick-btn btn btn-success" style="margin-top: -6% !important;" tabindex="4">Filter</button>
                                    <a href="<?= base_url('combined-reports'); ?>" class="quick-btn gray-btn" tabindex="5">Clear Filter</a>
                                </div>
                                <div></div>
                            <?php echo form_close(); ?>
                            <div id="form-error" class="alert alert-danger d-none"></div>
                            @if (!empty($diaryRecords))
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
                                    $labels[] = 'Report Type: <strong>Appearance</strong>';
                                }
                                if ($this->request->getGet('diary_no')) {
                                    $labels[] = 'Diary No: <strong>' . $this->request->getGet('diary_no') . '</strong>';
                                }
                                if ($this->request->getGet('aor_code')) {
                                    $labels[] = 'AOR Code: <strong>' . $this->request->getGet('aor_code') . '</strong>';
                                }
                                if ($this->request->getGet('list_dates')){
                                    try {
                                        $loginTime = \Carbon\Carbon::parse($this->request->getGet('list_dates'))->format('d-m-Y');
                                    } catch (\Exception $e) {
                                        $loginTime = $this->request->getGet('list_dates'); // fallback
                                    }
                                    $labels[] = 'List / Entry Dates: <strong>' . $loginTime . '</strong>';
                                }
                                ?>
                                <div id="printable-area" class="mt-2">
                                    @if (count($labels))
                                        <h4 class="text-center">{!! implode(' | ', $labels) !!}</h4>
                                    @endif
                                    <table class="table table-bordered table-striped w-100 text-center align-middle" id="datatable-responsive">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Diary No</th>
                                                <th>Court No</th>
                                                <th>Item No</th>
                                                <th>AOR Code</th>
                                                <th>Appearing For</th>
                                                <th>Advocate</th>
                                                <th>Submitted</th>
                                                <th>Active</th>
                                                <th class="list-date-col">List Date</th>
                                                <th class="list-date-col">Entry Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($diaryRecords as $key => $record)
                                                <tr>
                                                    <td>{{ $key+1 }}</td>
                                                    <td>{{ $record->diary_no }}</td>
                                                    <td>{{ $record->court_no }}</td>
                                                    <td>{{ $record->item_no }}</td>
                                                    <td>{{ $record->aor_code }}</td>
                                                    <td>{{ $record->appearing_for }}</td>
                                                    <td>{{ $record->advocate_title }} {{ $record->advocate_name }}</td>
                                                    <td>{{ $record->is_submitted ? 'Yes' : 'No' }}</td>
                                                    <td>{{ $record->is_active ? 'Yes' : 'No' }}</td>
                                                    <td class="list-date-col">
                                                        {{ $record->list_date ? \Carbon\Carbon::parse($record->list_date)->format('d-m-Y') : '' }}
                                                    </td>
                                                    <td class="list-date-col">
                                                        {{ $record->entry_date ? \Carbon\Carbon::parse($record->entry_date)->format('d-m-Y H:i:s') : '' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                @if (!empty($_GET['diary_no']) || !empty($_GET['aor_code']) || !empty($_GET['list_dates']))
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

    document.getElementById('diaryForm').addEventListener('submit', function(e) {

        const listDates = document.getElementById('list_dates').value.trim();
        const aorCode = document.getElementById('aor_code').value.trim();
        const diaryNo = document.getElementById('diary_no').value.trim();
        const errorBox = document.getElementById('form-error');

        errorBox.classList.add('d-none');
        errorBox.innerText = '';

        if (!listDates && !aorCode && !diaryNo) {
            e.preventDefault();
            errorBox.innerText = 'Please fill at least one of the fields: List Date, AOR Code, or Diary No.';
            errorBox.classList.remove('d-none');
            return false;
        }

        if (listDates) {
            const datesArray = listDates.split(',').map(d => d.trim()).filter(d => d !== '');

            if (datesArray.length > 1) {
                if (!aorCode && !diaryNo) {
                    e.preventDefault();
                    errorBox.innerText =
                        'Either AOR Code or Diary No is required when multiple list dates are selected.';
                    errorBox.classList.remove('d-none');
                }
            }
        }
    });
    $(document).ready(function() {
        var today = new Date();
        var startDate = new Date();
        startDate.setFullYear(today.getFullYear() - 40);
        $('.list_dates').datepicker({
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