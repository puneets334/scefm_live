@extends('layout.app')
@section('content')
<?php use App\Models\NewCase\DropdownListModel; ?>
<style>
    table {border: 0;border-collapse: separate;border-spacing: 1px 0;table-layout: fixed;word-wrap: break-word;}
    .innertable {border: 0;border-collapse: separate;border-spacing: 1px 0;table-layout: auto !important; word-wrap: normal !important; white-space: nowrap}
    .panel-heading {padding: 0;}
    .panel-title a {display: block;padding: 10px 15px;}
    b {font-weight: normal;}
    .accordion-option {width: 100%;float: left;clear: both;margin: 15px 0;}
    .accordion-option .title {font-size: 20px;font-weight: bold;float: left;padding: 0;margin: 0;}
    .accordion-option .toggle-accordion {float: right;font-size: 16px;color: #6a6c6f;}
    .accordion-option .toggle-accordion:before {content: "Expand All";}
    .accordion-option .toggle-accordion.active:before {content: "Collapse All";}
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
                                <h5 class="unerline-title"> Compare Uploaded PDF JSON Data </h5>
                                <a href="javascript:void(0)" onclick="window.history.back()" class="quick-btn pull-right"><span class="mdi mdi-chevron-double-left"></span>Back</a>
                            </div>
                            <button id="collapseAll" onclick="toggleAllAccordions()" class="btn btn-primary pull-right mb-3"> Collapse All </button>
                            {{-- Page Title End --}}
                            {{-- Main Start --}}
                            <div class="right_col" role="main">
                                <div class="clearfix"></div>
                                <div class="accordion view-accordion acrdion-with-edit" id="accordionExample">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingOne">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne"> Case Details </button>
                                        </h2>
                                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="x_panel">
                                                    <table class="table maintable">
                                                        <thead>
                                                            <tr class="success">
                                                                <th scope="col">#</th>
                                                                <th scope="col">IITM JSON</th>
                                                                <th scope="col">EFILING JSON</th>
                                                                <th scope="col">ICMIS JSON</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td colspan="4">
                                                                    <table class="table">
                                                                        <?php
                                                                        $srno = 1;
                                                                        // pr($case_details_array_column);
                                                                        foreach($case_details_array_column as $column){ 
                                                                            if(in_array($column,['subj_main_cat'])){
                                                                                continue;
                                                                            }
                                                                            ?>
                                                                        <tr>
                                                                            <td><?php echo $srno.'. '.$column; $srno++;?></td>
                                                                            <td>
                                                                                <?php 
                                                                                if(isset($iitm_api_array['case_details'][$column]) && is_array($iitm_api_array['case_details'][$column])){
                                                                                    echo implode(',',$iitm_api_array['case_details'][$column]);
                                                                                }else{
                                                                                    echo isset($iitm_api_array['case_details'][$column]) ? $iitm_api_array['case_details'][$column] : '';
                                                                                }
                                                                                ?>
                                                                            </td>
                                                                            <td>
                                                                                <?php 
                                                                                if($column=='court_type'){
                                                                                    if($efiling_array['case_details'][0][$column]==1){
                                                                                        $court_type = 'High Court';
                                                                                    }elseif($efiling_array['case_details'][0][$column]==3){
                                                                                        $court_type = 'District Court';
                                                                                    }elseif($efiling_array['case_details'][0][$column]==4){
                                                                                        $court_type = 'Supreme Court';
                                                                                    }elseif($efiling_array['case_details'][0][$column]==5){
                                                                                        $court_type = 'State Agency';
                                                                                    }
                                                                                    echo $court_type;
                                                                                }elseif($column=='casetype_id'){
                                                                                    $this->Dropdown_list_model = new DropdownListModel();
                                                                                    // $this->load->model('newcase/Dropdown_list_model');
                                                                                    $sc_case_type = $this->Dropdown_list_model->getSciCaseTypeOrderById();
                                                                                    foreach($sc_case_type as $value){
                                                                                        if($value->casecode==$efiling_array['case_details'][0][$column]){
                                                                                            echo $value->casename;
                                                                                        }
                                                                                    }
                                                                                }elseif($column=='sc_sp_case_type_id'){
                                                                                    if($efiling_array['case_details'][0][$column]==1){
                                                                                        $scspcasetype_id = 'NONE';
                                                                                    }elseif($efiling_array['case_details'][0][$column]==6){
                                                                                        $scspcasetype_id = 'JAIL PETITION';
                                                                                    }elseif($efiling_array['case_details'][0][$column]==7){
                                                                                        $scspcasetype_id = 'PUD';
                                                                                    }
                                                                                    echo $scspcasetype_id;
                                                                                }
                                                                                else{
                                                                                    echo isset($efiling_array['case_details'][0][$column]) ? $efiling_array['case_details'][0][$column] : '';
                                                                                }
                                                                            ?>
                                                                            </td>
                                                                            <td><?php echo isset($icmis_array['case_details'][$column]) ? $icmis_array['case_details'][$column] : '';?>
                                                                            </td>
                                                                        </tr>
                                                                        <?php } ?>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <h2 class="accordion-header" id="headingTwo">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo"> Main Petitioner </button>
                                        </h2>
                                        <div id="collapseTwo" class="accordion-collapse collapse show" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="x_panel">
                                                    <table class="table">
                                                        <thead>
                                                            <tr class="success">
                                                                <th scope="col">#</th>
                                                                <th scope="col">IITM JSON</th>
                                                                <th scope="col">EFILING JSON</th>
                                                                <th scope="col">ICMIS JSON</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td colspan="4">
                                                                    <table class="table">
                                                                        <?php
                                                                        $srno = 1;
                                                                        foreach($main_petitioner_array_column as $column){ 
                                                                            if(in_array($column,['id','registration_id','subj_main_cat','state_name'])){
                                                                                continue;
                                                                            }
                                                                            ?>
                                                                        <tr>
                                                                        <td><?php echo $srno.'. '.$column; $srno++;?></td>
                                                                            <td>
                                                                                <?php 
                                                                                    if(isset($iitm_api_array['main_petitioner'][$column]) && is_array($iitm_api_array['main_petitioner'][$column])){
                                                                                        echo implode(',',$iitm_api_array['main_petitioner'][$column]);
                                                                                    }else{
                                                                                        echo isset($iitm_api_array['main_petitioner'][$column]) ? $iitm_api_array['main_petitioner'][$column] : '';
                                                                                    }
                                                                                    ?>
                                                                            </td>
                                                                            <td>
                                                                                <?php 
                                                                                    if($column=='state_id'){
                                                                                        echo $efiling_array['main_petitioner'][0]['state_name'];
                                                                                    }elseif($column=='district_id'){
                                                                                        echo $efiling_array['main_petitioner'][0]['addr_dist_name'];
                                                                                    }elseif($column=='party_type'){
                                                                                        if($efiling_array['main_petitioner'][0][$column]=='I'){
                                                                                            echo 'Individual';
                                                                                        }elseif($efiling_array['main_petitioner'][0][$column]=='D1'){
                                                                                            echo 'State Department';
                                                                                        }elseif($efiling_array['main_petitioner'][0][$column]=='D2'){
                                                                                            echo 'Central Department';
                                                                                        }elseif($efiling_array['main_petitioner'][0][$column]=='D3'){
                                                                                            echo 'Other Organisation';
                                                                                        }
                                                                                        
                                                                                    }
                                                                                    else{
                                                                                        echo $efiling_array['main_petitioner'][0][$column];
                                                                                    }
                                                                                    ?>
                                                                            </td>
                                                                            <td><?php echo isset($icmis_array['main_petitioner'][$column]) ? $icmis_array['main_petitioner'][$column] : '';?>
                                                                            </td>
                                                                        </tr>
                                                                        <?php } ?>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <h2 class="accordion-header" id="headingThree">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree"> Main Reposndent </button>
                                        </h2>
                                        <div id="collapseThree" class="accordion-collapse collapse show" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="x_panel">
                                                    <table class="table">
                                                        <thead class="thead-dark">
                                                            <tr class="success">
                                                                <th scope="col">#</th>
                                                                <th scope="col">IITM JSON</th>
                                                                <th scope="col">EFILING JSON</th>
                                                                <th scope="col">ICMIS JSON</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td colspan="4">
                                                                    <table class="table">
                                                                        <?php
                                                                        $srno=1;
                                                                        foreach($main_reposndent_array_column as $column) { 
                                                                            if(in_array($column,['id','registration_id','state_name'])) {
                                                                                continue;
                                                                            }
                                                                            ?>
                                                                            <tr>
                                                                                <td><?php echo $srno.'. '.$column; $srno++;?></td>
                                                                                <td>
                                                                                    <?php 
                                                                                    if(isset($iitm_api_array['main_reposndent'][$column]) && is_array($iitm_api_array['main_reposndent'][$column])) {
                                                                                        echo implode(',',$iitm_api_array['main_reposndent'][$column]);
                                                                                    } else{
                                                                                        echo isset($iitm_api_array['main_reposndent'][$column]) ? $iitm_api_array['main_reposndent'][$column] : '';
                                                                                    }
                                                                                    ?>
                                                                                </td>
                                                                                <td>
                                                                                    <?php
                                                                                    if($column=='state_id') {
                                                                                        echo $efiling_array['main_petitioner'][0]['state_name'];
                                                                                    } elseif($column=='district_id') {
                                                                                        echo $efiling_array['main_petitioner'][0]['addr_dist_name'];
                                                                                    } elseif($column=='party_type') {
                                                                                        if($efiling_array['main_petitioner'][0][$column]=='I') {
                                                                                            echo 'Individual';
                                                                                        } elseif($efiling_array['main_petitioner'][0][$column]=='D1') {
                                                                                            echo 'State Department';
                                                                                        } elseif($efiling_array['main_petitioner'][0][$column]=='D2') {
                                                                                            echo 'Central Department';
                                                                                        } elseif($efiling_array['main_petitioner'][0][$column]=='D3') {
                                                                                            echo 'Other Organisation';
                                                                                        }
                                                                                    } else{
                                                                                        echo $efiling_array['main_petitioner'][0][$column];
                                                                                    }
                                                                                    ?>
                                                                                </td>
                                                                                <td><?php echo isset($icmis_array['main_respondent'][$column]) ? $icmis_array['main_respondent'][$column] : ''; ?></td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <h2 class="accordion-header" id="headingFour">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="true" aria-controls="collapseFour"> Extra Party </button>
                                        </h2>
                                        <div id="collapseFour" class="accordion-collapse collapse show" aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="x_panel">
                                                    <table class="table">
                                                        <thead class="thead-dark">
                                                            <tr class="success">
                                                                <th scope="col">IITM JSON</th>
                                                                <th scope="col">EFILING JSON</th>
                                                                <th scope="col">ICMIS JSON</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <div class="table-responsive">
                                                                        <table class="table innertable">
                                                                            <thead>
                                                                                <tr class="alert alert-info">
                                                                                    <?php foreach($iitm_extra_party_columns as $column) { ?>
                                                                                        <th><?php echo $column; ?></th>
                                                                                    <?php } ?>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <tr>
                                                                                    <?php foreach($iitm_api_array['extra_party'] as $iitm_api_data) { ?>
                                                                                        <tr>
                                                                                            <?php foreach($iitm_api_data as $key=>$value) { ?>
                                                                                                <td><?php echo $value;?></td>
                                                                                            <?php } ?>
                                                                                        </tr>
                                                                                    <?php } ?>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="table-responsive">
                                                                        <table class="table innertable">
                                                                            <thead>
                                                                                <tr class="alert alert-info">
                                                                                    <?php
                                                                                    foreach($efiling_extra_party_columns as $column) { 
                                                                                        if(in_array($column,['id','registration_id'])) {
                                                                                            continue;
                                                                                        }
                                                                                        ?>
                                                                                        <th><?php echo $column; ?></th>
                                                                                    <?php } ?>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <tr>
                                                                                    <?php foreach($efiling_array['extra_party'] as $efiling_data) { ?>
                                                                                        <tr>
                                                                                            <?php
                                                                                            foreach($efiling_data as $key=>$value) {
                                                                                                if(in_array($key,['id','registration_id','subj_main_cat','state_name'])) {
                                                                                                    continue;
                                                                                                }
                                                                                                ?>
                                                                                                <td><?php echo $value;?></td>
                                                                                            <?php } ?>
                                                                                        </tr>
                                                                                    <?php } ?>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="table-responsive">
                                                                        <table class="table innertable">
                                                                            <thead>
                                                                                <tr class="alert alert-info">
                                                                                    <?php foreach($icmis_extra_party_columns as $column) { ?>
                                                                                        <th><?php echo $column; ?></th>
                                                                                    <?php } ?>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <tr>
                                                                                    <?php foreach($icmis_array['extra_party'] as $icmis_data) { ?>
                                                                                        <tr>
                                                                                            <?php foreach($icmis_data as $key=>$value) { ?>
                                                                                                <td><?php echo $value;?></td>
                                                                                            <?php } ?>
                                                                                        </tr>
                                                                                    <?php } ?>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </td>
                                                            </tr>                                                            
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <h2 class="accordion-header" id="headingFive">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="true" aria-controls="collapseFive"> Earlier Courts </button>
                                        </h2>
                                        <div id="collapseFive" class="accordion-collapse collapse show" aria-labelledby="headingFive" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="x_panel">
                                                    <table class="table">
                                                        <thead class="thead-dark">
                                                            <tr class="success">
                                                                <th scope="col">IITM JSON</th>
                                                                <th scope="col">EFILING JSON</th>
                                                                <th scope="col">ICMIS JSON</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <div class="table-responsive">
                                                                        <table class="table innertable">
                                                                            <thead>
                                                                                <tr class="alert alert-info">
                                                                                    <?php foreach($iitm_earlier_courts_columns as $column) { ?>
                                                                                        <th><?php echo $column; ?></th>
                                                                                    <?php } ?>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <tr>
                                                                                    <?php
                                                                                    $iitm_api_earlier_courts[] = $iitm_api_array['earlier_courts'];
                                                                                    foreach($iitm_api_earlier_courts as $iitm_api_data) {
                                                                                        ?>
                                                                                        <tr>
                                                                                            <?php foreach($iitm_api_data as $key=>$value) { ?>
                                                                                                <td><?php echo $value;?></td>
                                                                                            <?php } ?>
                                                                                        </tr>
                                                                                    <?php } ?>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="table-responsive">
                                                                        <table class="table innertable">
                                                                            <thead>
                                                                                <tr class="alert alert-info">
                                                                                    <?php
                                                                                    foreach($efiling_earlier_courts_columns as $column) { 
                                                                                        if(in_array($column,['id','registration_id','fir_details'])) {
                                                                                            continue;
                                                                                        }
                                                                                        ?>
                                                                                        <th><?php echo $column; ?></th>
                                                                                    <?php } ?>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <tr>
                                                                                    <?php foreach($efiling_array['earlier_courts'] as $efiling_data) { ?>
                                                                                        <tr>
                                                                                            <?php
                                                                                            foreach($efiling_data as $key=>$value) {
                                                                                                if(in_array($key,['id','registration_id','fir_details'])) {
                                                                                                    continue;
                                                                                                }
                                                                                                ?>
                                                                                                <td><?php echo $value;?></td>
                                                                                            <?php } ?>
                                                                                        </tr>
                                                                                    <?php } ?>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="table-responsive">
                                                                        <table class="table innertable">
                                                                            <thead>
                                                                                <tr class="alert alert-info">
                                                                                    <?php foreach($icmis_earlier_courts_columns as $column) { ?>
                                                                                        <th><?php echo $column; ?></th>
                                                                                    <?php } ?>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <tr>
                                                                                    <?php foreach($icmis_array['earlier_courts'] as $icmis_data) { ?>
                                                                                        <tr>
                                                                                            <?php foreach($icmis_data as $key=>$value) { ?>
                                                                                                <td>
                                                                                                    <?php 
                                                                                                    if(is_array($value)) {
                                                                                                        echo implode(',',$value);
                                                                                                    } else{
                                                                                                        echo $value;
                                                                                                    }
                                                                                                    ?>
                                                                                                </td>
                                                                                            <?php } ?>
                                                                                        </tr>
                                                                                    <?php } ?>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
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
    </div>
</div>
@endsection
@push('script')
<!-- <script>
    $(document).ready(function() {
        $(".toggle-accordion").on("click", function() {
            var accordionId  = $(this).attr("accordion-id"),
            numPanelOpen = $(accordionId + ' .collapse.in').length;
            $(this).toggleClass("active");
            if (numPanelOpen == 0) {
                openAllPanels(accordionId);
                $('.toggle-accordion').innerHTML('Collapse All');
            } else{
                closeAllPanels(accordionId);
                $('.toggle-accordion').innerHTML('Expand All');
            }
        })
        openAllPanels = function(aId) {
            $(aId + ' .panel-collapse:not(".in")').collapse('show');
        }
        closeAllPanels = function(aId) {
            $(aId + ' .panel-collapse.in').collapse('hide');
        }
    });
</script> -->
<script>
    function toggleAllAccordions() {
        var button = document.getElementById("collapseAll");
        var accordionHeaders = document.querySelectorAll(".accordion-header button");
        var accordionCollapses = document.querySelectorAll(".accordion-collapse");
        var isCollapsed = Array.from(accordionHeaders).some(function (header) {
            return !header.classList.contains("collapsed");
        });
        if (isCollapsed) {
            button.innerHTML = "Expand all";
            accordionHeaders.forEach(function (header) {
                header.classList.add("collapsed");
            });
            accordionCollapses.forEach(function (collapse) {
                collapse.classList.remove("show");
            });
        } else{
            button.innerHTML = "Collapse all";
            accordionHeaders.forEach(function (header) {
                header.classList.remove("collapsed");
            });
            accordionCollapses.forEach(function (collapse) {
                collapse.classList.add("show");
            });
        }
    }
</script>
@endpush