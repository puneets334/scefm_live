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
<style>
    .flatpickr-calendar {
        display: none;
    }
</style>
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
                            if ($this->request->getGet('report_type') && ($this->request->getGet('diary_no') || $this->request->getGet('list_dates') || $this->request->getGet('entry_dates') || $this->request->getGet('aor_code') || $this->request->getGet('advocate_name'))) { ?>
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
                            <?php echo form_open('', 'method="GET" class="row g-3" id="submissionForm"'); ?>
                                <input type="hidden" name="report_type" value="submissionlogs">
                                <div class="col-md-6">
                                    <label for="inputDiary" class="form-label">Diary No</label>
                                    <input type="text" name="diary_no" id="diary_no" class="form-control cus-form-ctrl" value="<?= isset($_GET['diary_no']) ? $_GET['diary_no'] : ''; ?>" placeholder="Diary No" tabindex="1">
                                </div>
                                <div class="col-md-6">
                                    <label for="inputAOR" class="form-label">AOR Code</label>
                                    <input type="text" name="aor_code" id="aor_code" class="form-control cus-form-ctrl" value="<?= isset($_GET['aor_code']) ? $_GET['aor_code'] : ''; ?>" placeholder="AOR Code" tabindex="2">
                                </div>
                                <label for="inputList" class="form-label">List Date</label>
                                <div class="col-md-4"><input type="date" id="list_date" class="form-control cus-form-ctrl" /></div>
                                <div class=" col-md-2"><button type="button" class="btn btn-primary" id="addListDate">Add List Date</button></div>
                                <div class=" col-md-6"><textarea name="list_dates" id="list_dates" class="form-control cus-form-ctrl" tabindex="3" placeholder="List Date"><?= isset($_GET['list_dates']) ? $_GET['list_dates'] : ''; ?></textarea></div>

                                <label for="inputEntry" class="form-label">Entry Date</label>
                                <div class="col-md-4"><input type="date" id="entry_date" class="form-control cus-form-ctrl" /></div>
                                <div class=" col-md-2"><button type="button" class="btn btn-primary" id="addEntryDate">Add Entry Date</button></div>
                                <div class=" col-md-6"><textarea name="entry_dates" id="entry_dates" class="form-control cus-form-ctrl" tabindex="4" placeholder="Entry Date"><?= isset($_GET['entry_dates']) ? $_GET['entry_dates'] : ''; ?></textarea></div>
                                <div class="col-md-6">
                                    <label for="inputAdvocate" class="form-label">AOR/Advocate Name</label>
                                    <input type="text" name="advocate_name" id="advocate_name" class="form-control cus-form-ctrl" value="<?= isset($_GET['advocate_name']) ? $_GET['advocate_name'] : ''; ?>" placeholder="AOR/Advocate Name" tabindex="5">
                                </div>
                                <div class="col-6">
                                    <label for="inputAction" class="form-label">Action</label>
                                    <select name="action_type" class="form-control cus-form-ctrl" aria-label="Default select example">
                                        <option value="" <?php echo isset($_GET['action_type']) && $_GET['action_type'] === '' ? 'selected' : ''; ?>>Action All</option>
                                        <option value="FINAL SUBMIT" <?php echo isset($_GET['action_type']) && $_GET['action_type'] === 'FINAL SUBMIT' ? 'selected' : ''; ?>>FINAL SUBMIT</option>
                                        <option value="INSERT" <?php echo isset($_GET['action_type']) && $_GET['action_type'] === 'INSERT' ? 'selected' : ''; ?>>INSERT</option>
                                        <option value="DELETE" <?php echo isset($_GET['action_type']) && $_GET['action_type'] === 'DELETE' ? 'selected' : ''; ?>>DELETE</option>
                                    </select>
                                </div>
                                <div class="col-3" style="margin-top: 3% !important;">
                                    <button type="submit" class="quick-btn btn btn-success" style="margin-top: -6% !important;" tabindex="4">Filter</button>
                                    <a href="<?= base_url('combined-reports'); ?>" class="quick-btn gray-btn" tabindex="5">Clear Filter</a>
                                </div>
                                <div></div>
                            <?php echo form_close(); ?>
                            <div id="form-error" class="alert alert-danger d-none"></div>
                            @if (!empty($diaryRecords))
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
                                    $labels[] = 'List Dates: <strong>' . $loginTime . '</strong>';
                                }
                                if ($this->request->getGet('entry_dates')){
                                    try {
                                        $loginTime = \Carbon\Carbon::parse($this->request->getGet('entry_dates'))->format('d-m-Y');
                                    } catch (\Exception $e) {
                                        $loginTime = $this->request->getGet('entry_dates'); // fallback
                                    }
                                    $labels[] = 'Entry Dates: <strong>' . $loginTime . '</strong>';
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
                                                <th>List Date</th>
                                                <th>Entry Date</th>
                                                <th>Action Timestamp</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $isPaginated = $diaryRecords instanceof \Illuminate\Pagination\LengthAwarePaginator;
                                                $serialStart = $isPaginated ? ($diaryRecords->currentPage() - 1) * $diaryRecords->perPage() : 0;
                                            @endphp
                                            @foreach ($diaryRecords as $record)
                                                <tr>
                                                    <td>{{ $serialStart + $loop->iteration }}</td>
                                                    <td>{{ $record->diary_no }}</td>
                                                    <td>{{ $record->court_no }}</td>
                                                    <td>{{ $record->item_no }}</td>
                                                    <td>{{ $record->aor_code }}</td>
                                                    <td>{{ $record->appearing_for }}</td>
                                                    <td>{{ $record->advocate_title }} {{ $record->advocate_name }}</td>
                                                    <td>{{ $record->is_submitted ? 'Yes' : 'No' }}</td>
                                                    <td>{{ $record->is_active ? 'Yes' : 'No' }}</td>
                                                    <td>
                                                        {{ $record->list_date ? \Carbon\Carbon::parse($record->list_date)->format('d-m-Y') : '' }}
                                                    </td>
                                                    <td>
                                                        {{ $record->entry_date ? \Carbon\Carbon::parse($record->entry_date)->format('d-m-Y H:i:s') : '' }}
                                                    </td>
                                                    <td>
                                                        {{ $record->action_timestamp ? \Carbon\Carbon::parse($record->action_timestamp)->format('d-m-Y H:i:s') : '' }}
                                                    </td>
                                                    @if ($record->action_type == 'FINAL SUBMIT')
                                                        <td class="text-success">{{ $record->action_type }}</td>
                                                    @elseif($record->action_type == 'INSERT')
                                                        <td class="text-warning">{{ $record->action_type }}</td>
                                                    @else
                                                        <td class="text-danger">{{ $record->action_type }}</td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                @if (
                                    !empty($_GET['diary_no']) ||
                                        !empty($_GET['aor_code']) ||
                                        !empty($_GET['list_dates']) ||
                                        !empty($_GET['entry_dates']))
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
<script src="<?= base_url('assets/js/flatpickr.js'); ?>"></script>
@stack('script')
<script>
    $(document).ready(function() {
        $('#datatable-responsive').DataTable();
    });
    let listDatesArray = [];
    $("#addListDate").on("click", function() {
        const d = $("#list_date").val();    
        if (!d) {
            alert("Select list date first.");
        } else {
            listDatesArray.push(d);
            const dates = listDatesArray.join(", ");
            $("#list_dates").text(dates);
        }
    });
    // document.querySelector("#finish").addEventListener("click", function() {
    //     var dates = [];
    //     for (let o of document.querySelectorAll("#list_dates option"))
    //     {
    //         dates.push(o.value);
    //     }
    // });

    let entryDatesArray = [];
    $("#addEntryDate").on("click", function() {
        const d = $("#entry_date").val();    
        if (!d) {
            alert("Select entry date first.");
        } else {
            entryDatesArray.push(d);
            const dates = entryDatesArray.join(", ");
            $("#entry_dates").text(dates);
        }
    });
    // document.querySelector("#finish").addEventListener("click", function() {
    //     var dates = [];
    //     for (let o of document.querySelectorAll("#entry_dates option"))
    //     {
    //         dates.push(o.value);
    //     }
    // });

    document.getElementById('submissionForm').addEventListener('submit', function(e) {
        const listDates = document.getElementById('list_dates').value.trim();
        const entryDates = document.getElementById('entry_dates').value.trim();
        const aorCode = document.getElementById('aor_code').value.trim();
        const diaryNo = document.getElementById('diary_no').value.trim();
        const advocate_name = document.getElementById('advocate_name').value.trim();
        const errorBox = document.getElementById('form-error');
        errorBox.classList.add('d-none');
        errorBox.innerText = '';
        if (!listDates && !aorCode && !diaryNo && !advocate_name && !entryDates) {
            e.preventDefault();
            errorBox.innerText =
                'Please fill at least one of the fields: List Date, Entry Date, AOR Code, Diary No or AOR/Advocate Name.';
            errorBox.classList.remove('d-none');
            return false;
        }
    });
</script>