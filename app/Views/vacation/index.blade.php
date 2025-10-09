@extends('layout.advocateApp')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="dashboard-section dashboard-tiles-area"></div>
            <div class="dashboard-section">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="dash-card">
                            <div class="title-sec">
                                <h5 class="unerline-title"> Partial Court Working Days </h5>
                                <a href="javascript:void(0)" onclick="window.history.back()" class="quick-btn pull-right"><span class="mdi mdi-chevron-double-left"></span>Back</a>
                            </div>
                            <div class="row g-3 align-items-center">
                                <h3 class="text-center"> Partial Court Working Days @php echo date("Y") @endphp</h3>
                                <div class="col-auto" style="margin-left: 32%;">
                                    <label for="inputPassword6" class="col-form-label"><b>Select List Type: </b></label>
                                </div>
                                <div class="col-auto" style="width: 25%;">
                                    <select name="mainhead" id="mainhead" class="form-control cus-form-ctrl" onchange="this.form.submit()">
                                        <option value="F" <?= ($mainhead == 'F') ? 'selected' : ''; ?>>Regular</option>
                                        <option value="M" <?= ($mainhead == 'M') ? 'selected' : ''; ?>>Miscellaneous</option>
                                    </select>
                                </div>
                            </div>
                            <hr>
                            @php
                                $iframe_src_route_uri = 'vacation/advance/alllist?mainhead='.$mainhead;
                                if(uri_string() == 'vacation/advance/declinelist'){
                                    $iframe_src_route_uri = 'vacation/advance/get_declinelist?mainhead='.$mainhead;
                                }
                                $current_tab = uri_string(); // e.g., 'vacation/advance' or 'vacation/advance/declinelist'
                            @endphp
                            <div class="dash-card dashboard-section tabs-section">
                                <div class="tabs-sec-inner">
                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <a href="{{ base_url('vacation/advance?mainhead=' . $mainhead) }}" class="{{ ($current_tab == 'vacation/advance') ? 'nav-link active' : 'nav-link' }}"> Partial Court Working Days (All Matters) - For Consent </a>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <a href="{{ base_url('vacation/advance/declinelist?mainhead=' . $mainhead) }}" class="{{ ($current_tab == 'vacation/advance/declinelist') ? 'nav-link active' : 'nav-link' }}"> Partial Court Working Days (Declined Matters) </a>
                                        </li>
                                    </ul>
                                    <?php
                                    if (!is_vacation_advance_list_duration()) {
                                        echo 'Advance Summer Vacation List not authorized access';
                                        exit();
                                    }
                                    ?>
                                    <iframe name="content-iframe" class="col-12 iframe-scroll-bar" src="{{base_url($iframe_src_route_uri)}}"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
<script>
    document.getElementById('mainhead').addEventListener('change', function () {
        const selected = this.value;
        const baseUrl = "<?= base_url('vacation/advance'); ?>";
        window.location.href = baseUrl + "?mainhead=" + selected;
    });
</script>
@endpush