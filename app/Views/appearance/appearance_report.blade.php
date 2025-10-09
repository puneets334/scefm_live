@extends('layout.advocateApp')
@section('content')
<link rel="shortcut icon" href="<?= base_url().'assets/newDesign/images/logo.png' ?>" type="image/png" />
<link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-datepicker.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-datepicker.min.css">
<style>
    .rounded-box {
        padding: 25px;
        background-color: lightblue;
    }
    .rounded-box-color {
        padding: 25px;
        background-color: lightgreen;
    }
</style>
<meta name="csrf-token" content="{{ csrf_token() }}">
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
                                <h5 class="unerline-title">&nbsp;{{ $heading }}</h5>
                                <a href="javascript:void(0)" onclick="window.history.back()" class="quick-btn pull-right"><span class="mdi mdi-chevron-double-left"></span>Back</a>
                            </div>
                            <section class="content">
                                <div class="container-fluid">        
                                    <div class='row'>
                                        @if (session('status'))
                                            <div class="col-12 alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>{{ session('status') }}</strong></div>
                                        @elseif(session('failed'))
                                            <div class="col-12 alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>{{ session('failed') }}</strong></div>
                                        @endif
                                        <div class="col-md-12">
                                            <div class="card card-primary">
                                                <div class="card-header m-1" style="background-color: #F8F9FA;color:#000;font-weight: 400;">
                                                    <h4 >Search</h4>
                                                </div>
                                                <form class="form-horizontal" id="appearance-filter-form">
                                                    @csrf
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-sm-3">
                                                                <div class="form-group">
                                                                    <label class="control-label">From Date (DD-MM-YYYY)</label>                                        
                                                                    <div class="input-group date" data-target-input="nearest">
                                                                        <input type="text" class="form-control cus-form-ctrl datepicker" name="cause_list_from_date" id="cause_list_from_date" value="<?php echo APPEARANCE_SUBMISSION; ?>" />
                                                                    </div>
                                                                    <span class="text-danger" id="cause_list_from_date_error"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <div class="form-group">
                                                                    <label class="control-label">To Date (DD-MM-YYYY)</label>
                                                                    <div class="input-group date" data-target-input="nearest">
                                                                        <input type="text" class="form-control cus-form-ctrl datepicker" name="cause_list_to_date" id="cause_list_to_date" value="{{ date('d-m-Y') }}" />
                                                                    </div>
                                                                    <span class="text-danger" id="cause_list_to_date_error"></span>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <div class="form-group">
                                                                    <label class="control-label">&nbsp;</label>
                                                                    <div class="input-group date">
                                                                        <button type="submit" name="search" class="btn quick-btn">Search</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="row">
                                                <!-- Total Submissions -->
                                                <div class="col-md-6">
                                                    <div class="card mb-3">
                                                        <div class="card-body d-flex p-1">
                                                            <div class="rounded-box">
                                                                <i class="fas fa-copy"></i>
                                                            </div>
                                                            <div class="text-left mr-auto p-2">
                                                                <h5 class="card-title">Total</h5>
                                                                <p class="card-text card-value" id="total_submissions">{{ $totalSubmissions }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Today's Submissions -->
                                                <div class="col-md-6">
                                                    <div class="card mb-3">
                                                        <div class="card-body d-flex p-1">
                                                            <div class="rounded-box rounded-box-color">
                                                                <i class="fas fa-flag"></i>
                                                            </div>
                                                            <div class="text-left mr-auto p-2">
                                                                <h5 class="card-title" id="today_selected_date">On Date ({{$today_selected_date}})</h5>
                                                                <p class="card-text card-value" id="today_submissions">{{ $todaySubmissions }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Weekly Submissions Chart -->
                                            <div class="card mt-4">
                                                <div class="card-body">
                                                    <h5 class="card-title">Weekly Submissions</h5>
                                                    <canvas id="weeklyChart" width="400" height="200"></canvas>
                                                </div>
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
@push('script')
<script src="<?= base_url() ?>assets/chart.js/Chart.min.js"></script>
<script src="<?= base_url() ?>assets/js/bootstrap-datepicker.js"></script>
<script src="<?= base_url() ?>assets/js/bootstrap-datepicker.min.js"></script>  
<script>
    $(document).ready(function() {        
        $('#cause_list_from_date').datepicker({
            format: 'dd-mm-yyyy',
            changeMonth: true,
            changeYear: true,
            dateFormat:'dd-mm-yy',
        });
        $('#cause_list_to_date').datepicker({
            format: 'dd-mm-yyyy',
            changeMonth: true,
            changeYear: true,
            dateFormat:'dd-mm-yy',
        });
        $('#appearance-filter-form').submit(function(e) {
            $(".btn-primary").prepend('<i class="fa fa-spinner fa-spin"></i>&nbsp;');
            $(".btn-primary").attr("disabled", 'disabled');
            $('#cause_list_from_date_error').text('');
            $('#cause_list_to_date_error').text('');
            e.preventDefault();
            const cause_list_from_date = $('#cause_list_from_date').val();
            const cause_list_to_date = $('#cause_list_to_date').val();
            $.ajax({
                url: "<?php echo base_url('appearance_report'); ?>",
                method: "POST",
                data: {
                    cause_list_from_date: cause_list_from_date,
                    cause_list_to_date: cause_list_to_date,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    $(".btn-primary").find(".fa-spinner").remove();
                    $(".btn-primary").removeAttr("disabled");
                    if (response.success) {
                        $('#today_submissions').html(response.today_submissions);
                        $('#total_submissions').html(response.total_submissions);
                        //$('#today_selected_date').html('On Date(' + response.today_selected_date+')');                        
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        let errorMessages = '<ul>';
                        for (let field in errors) {
                            errorMessages += `<li>${errors[field][0]}</li>`;
                            $('#' + field + '_error').text(errors[field][0]);
                        }
                        errorMessages += '</ul>';
                        $('#validation-errors').html(errorMessages).removeClass('d-none');
                        $(".btn-primary").find(".fa-spinner").remove();
                        $(".btn-primary").removeAttr("disabled");
                    }
                }
            });
        });
        const weeklyData   = <?php echo @json_encode($weeklySubmissions); ?>;
        const labels       = Object.keys(weeklyData);
        const thisWeekData = Object.values(weeklyData).map(data => data[0]);
        const lastWeekData = Object.values(weeklyData).map(data => data[1]);
        const ctx = document.getElementById('weeklyChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'This Week',
                    data: thisWeekData,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Last Week',
                    data: lastWeekData,
                    backgroundColor: 'rgba(201, 203, 207, 0.5)',
                    borderColor: 'rgba(201, 203, 207, 1)',
                    borderWidth: 1
                }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
@endpush