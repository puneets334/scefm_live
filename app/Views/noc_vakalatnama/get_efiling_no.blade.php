@extends('layout.app')
@section('content')
    <style>
        .add-new-area {
            display: none !important;
        }
    </style>
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
                                    <h5 class="unerline-title"> NOC Vakalatnama </h5>
                                    <!-- <a href="javascript:void(0)" class="quick-btn pull-right" onclick="window.history.back()"><span class="mdi mdi-chevron-double-left"></span>Back</a> -->
                                    <a href="javascript:void(0)" onclick="window.history.back()" class="quick-btn pull-right"><span class="mdi mdi-chevron-double-left"></span>Back</a>
                                </div>
                                {{-- Page Title End --}}
                                {{-- Main Start --}}
                                <div class="row" id="printData">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="x_panel">
                                            <div class="x_content">
                                                <div class="panel-body">
                                                    <?= form_open() ?>
                                                    <?php
                                                    if (!empty(getSessionData('message'))) {
                                                        echo getSessionData('message');
                                                    }
                                                    ?>
                                                    <div class="row">
                                                        <!-- <div class="col-sm-12 col-md-4 col-lg-2">
                                                            <label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                                            <div class="form-group">
                                                                <label>Enter E-filing Number:</label>
                                                            </div>
                                                        </div> -->
                                                        <div class="col-sm-12 col-md-6  col-lg-4 ">
                                                            <label>Enter E-filing Number:</label>
                                                            <div class="form-group">
                                                                <input type="text" name="e_filing_num" id="e_filing_num"
                                                                    class="form-control cus-form-ctrl" value=" {{ isset($effilingNumber)?$effilingNumber:'' }}">
                                                                <span id="e_filing_num"></span>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3 col-sm-12 col-xs-12">
                                                            <label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                                            <div class="form-group">
                                                                <button type="submit" id="getResult"
                                                                    class="quick-btn"> Search</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?= form_close() ?>
                                                @if (isset($case_details) && !empty($case_details))
                                                    <?= form_open() ?>
                                                    <div class="row">
                                                        <div class="col-md-3 col-sm-3 col-xs-3">
                                                            <label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                                            <div class="form-group">
                                                                <label>CauseTitle:</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-sm-3 col-xs-3">
                                                            <label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                                            <div class="form-group">
                                                                {{ isset($case_details->cause_title) ? $case_details->cause_title : '' }}
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-3 col-sm-3 col-xs-3">
                                                            <label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                                            <div class="form-group">
                                                                <label>Case Filed by AOR:</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-sm-3 col-xs-3">
                                                            <label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                                            <div class="form-group">
                                                                {{ isset($case_details->first_name) ? $case_details->first_name : '' }}
                                                                {{ isset($case_details->aor_code) ? $case_details->aor_code : '' }}

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-3 col-sm-3 col-xs-3">
                                                            <label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                                            <div class="form-group">
                                                                <label>Select AOR:</label>
                                                            </div>
                                                        </div>
                                                        <input type="hidden" name="registration_id"
                                                            value="{{ isset($case_details->registration_id) ? $case_details->registration_id : '' }}">
                                                        <div class="col-md-4 col-sm-4 col-xs-3">
                                                            <label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                                            <div class="form-group">
                                                                <select name="new_aor" id="new_aor"
                                                                    class="form-select cus-form-ctrl filter_select_dropdown"
                                                                    required>
                                                                    <option value="">Select AOR</option>
                                                                    <?php foreach ($aor_list as $aor) { ?>
                                                                        <option value="<?= $aor->id ?>">
                                                                            <?= $aor->first_name . ' (' . $aor->aor_code . ' )' ?>
                                                                        </option>
                                                                    <?php } ?>
                                                                </select>
                                                                <span id="new_aor"></span>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-sm-3 col-xs-3">
                                                            <label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                                            <div class="form-group">
                                                                <button type="submit" class="btn btn-primary">Submit</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <?= form_close() ?>
                                                @endif

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
