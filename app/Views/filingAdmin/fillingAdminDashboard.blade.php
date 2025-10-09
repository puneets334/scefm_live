@extends('layout.app')
@section('content')
<style>
    .add-new-area {
        display: none !important;
    }
    .yellow {
        color: #f0ad4e;
    }
    .orange {
        color: #FF7F50;
    }
    .dark_blue {
        color: #0040ff;
    }
    p:hover {
        background-color: #EDEDED !important;
    }
    .text_position {
        padding-left: 60%;
    }
    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }
    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }
    input:checked+.slider {
        background-color: #2196F3;
    }
    input:focus+.slider {
        box-shadow: 0 0 1px #2196F3;
    }
    input:checked+.slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }
    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }
    .slider.round:before {
        border-radius: 50%;
    }
    .first-th-left th:first-child, .first-th-left td:first-child {
        padding-left: 15px!important;
        text-align: left!important;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="dashboard-section dashboard-tiles-area">
                <?php if (!empty(getSessionData('MSG'))) {
						echo getSessionData('MSG');
					}
					if (!empty(getSessionData('msg'))) {
						echo getSessionData('msg');
					}
                    if (!empty(session()->setFlashdata('msg'))) {
						echo session()->setFlashdata('msg');
					}
                    ?>
            </div>
            <div class="dashboard-section">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="dash-card">
                            <div class="title-sec">
                                <h5 class="unerline-title">Pending With Registry </h5>
                            </div>
                            <div class="table-sec">
                                <div class="table-responsive">
                                    <table class="table table-striped custom-table first-th-left mob-simple-table">
                                        <thead>
                                            <tr>
                                                <th>Stages</th>
                                                <th>Count</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (count($stage_list) > 0)
                                                @foreach ($stage_list as $row)
                                                    @if ($row->meant_for == 'R')
                                                        @php
                                                        $total_count='0';
                                                        if($row->stage_id==New_Filing_Stage) {
                                                            $total_count=$count_efiling_data[0]->total_new_efiling;
                                                        } elseif($row->stage_id==DEFICIT_COURT_FEE) {
                                                            $total_count=$count_efiling_data[0]->total_efiled_deficit;
                                                        } elseif($row->stage_id==Initial_Defected_Stage) {
                                                            $total_count=$count_efiling_data[0]->total_not_accepted;
                                                        } elseif($row->stage_id==Transfer_to_CIS_Stage) {
                                                            $total_count=$count_efiling_data[0]->total_available_for_cis;
                                                        } elseif($row->stage_id==Get_From_CIS_Stage) {
                                                            $total_count=0;
                                                        } elseif($row->stage_id==Initial_Defects_Cured_Stage || $row->stage_id==DEFICIT_COURT_FEE_PAID) {
                                                            $total_count=$count_efiling_data[0]->total_refiled_cases;
                                                        } elseif($row->stage_id==Transfer_to_IB_Stage) {
                                                            $total_count=$count_efiling_data[0]->total_transfer_to_efiling_sec;
                                                        } elseif($row->stage_id==I_B_Approval_Pending_Admin_Stage) {
                                                            $total_count=$count_efiling_data[0]->total_pending_scrutiny;
                                                        } elseif($row->stage_id==I_B_Defected_Stage) {
                                                            $total_count=$count_efiling_data[0]->total_lodged_cases;
                                                        } elseif($row->stage_id==I_B_Rejected_Stage  || $row->stage_id==E_REJECTED_STAGE) {
                                                            $total_count=$count_efiling_data[0]->total_rejected;
                                                        } elseif($row->stage_id==I_B_Defects_Cured_Stage) {
                                                            $total_count=$count_efiling_data[0]->total_defect_cured;
                                                        } elseif($row->stage_id==LODGING_STAGE || $row->stage_id==DELETE_AND_LODGING_STAGE || $row->stage_id==MARK_AS_ERROR) {
                                                            $total_count=$count_efiling_data[0]->total_lodged_cases;
                                                        } elseif($row->stage_id==E_Filed_Stage || $row->stage_id==E_FILING_TYPE_NEW_CASE || $row->stage_id==CDE_ACCEPTED_STAGE || $row->stage_id==E_FILING_TYPE_CDE) {
                                                            $total_count=$count_efiling_data[0]->total_efiled_cases;
                                                        } elseif($row->stage_id==Document_E_Filed || $row->stage_id==E_FILING_TYPE_MISC_DOCS) {
                                                            $total_count=$count_efiling_data[0]->total_efiled_docs;
                                                        } elseif($row->stage_id==DEFICIT_COURT_FEE_E_FILED || $row->stage_id==E_FILING_TYPE_DEFICIT_COURT_FEE) {
                                                            $total_count=$count_efiling_data[0]->total_efiled_deficit ?? '';
                                                        } elseif($row->stage_id==IA_E_Filed || $row->stage_id==E_FILING_TYPE_IA) {
                                                            $total_count=$count_efiling_data[0]->total_efiled_ia;
                                                        } elseif($row->stage_id==HOLD) {
                                                            $total_count= $count_efiling_data[0]->total_hold_cases ?? '';
                                                        } elseif($row->stage_id==DISPOSED) {
                                                            $total_count=$count_efiling_data[0]->total_hold_disposed_cases;
                                                        }
                                                        @endphp
                                                        <!-- <tr>
                                                            <?php $href = ($total_count > 0) ? base_url("adminDashboard/stageList/" . htmlentities(url_encryption($row->stage_id , ENT_QUOTES))) : ''; ?>
                                                            <td data-key="Stages">
                                                                <a href="<?php echo $href; ?>">
                                                                    <i class="fa fa-square dark_blue"></i>
                                                                {{ isset($row->admin_stage_name) ? $row->admin_stage_name : '' }}</a>
                                                            </td>
                                                            <td data-key="Count"> <a href="<?php echo $href; ?>"></i>{{ $total_count }}</a> </td>
                                                        </tr> -->
                                                        <tr>
                                                            <?php $users_read_only_array = array(USER_ADMIN_READ_ONLY,USER_EFILING_ADMIN);
                                                            if (in_array($_SESSION['login']['ref_m_usertype_id'], $users_read_only_array)) { ?>
                                                            <?php $href = ($total_count ==0) ? 'javascript:void(0)' : base_url("report/search/list/" . htmlentities(url_encryption($row->stage_id, ENT_QUOTES))); ?>
                                                            <?php }else{ ?>
                                                                <?php $href = ($total_count ==0) ? 'javascript:void(0)' : base_url("adminDashboard/stageList/" . htmlentities(url_encryption($row->stage_id, ENT_QUOTES))); ?>
                                                            <?php } ?>
                                                            <td data-key="Stages">
                                                                <a href="<?php echo $href; ?>" >
                                                                    <i class="fa fa-square dark_blue"></i>
                                                                    {{ isset($row->admin_stage_name) ? $row->admin_stage_name : '' }}
                                                                </a>
                                                            </td>
                                                            <td data-key="Count">
                                                                <a href="<?php echo $href; ?>" >
                                                                    {{ $total_count }}
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>                                  
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                        <div class="dash-card">
                            <div class="title-sec">
                                <h5 class="unerline-title">Pending With Advocate </h5>
                            </div>
                            <div class="table-sec">
                                <div class="table-responsive">
                                    <table class="table table-striped custom-table  first-th-left mob-simple-table">
                                        <thead>
                                            <tr>
                                                <th style="text-align: left" class="ml-2">Stages</th>
                                                <th>Count</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (count($stage_list) > 0)
                                                @foreach ($stage_list as $row)
                                                    @if ($row->meant_for == 'A')
                                                        @php
                                                        $stage_id=$row->stage_id;
                                                        $total_count='0';
                                                        if($row->stage_id==New_Filing_Stage) {
                                                            $total_count=$count_efiling_data[0]->total_new_efiling;
                                                        } elseif($row->stage_id==DEFICIT_COURT_FEE) {
                                                            $total_count=$count_efiling_data[0]->total_efiled_deficit;
                                                        } elseif($row->stage_id==Initial_Defected_Stage) {
                                                            $total_count=$count_efiling_data[0]->total_not_accepted;
                                                        } elseif($row->stage_id==Transfer_to_CIS_Stage) {
                                                            $total_count=$count_efiling_data[0]->total_available_for_cis;
                                                        } elseif($row->stage_id==Get_From_CIS_Stage) {
                                                            $total_count=0;
                                                        } elseif($row->stage_id==Initial_Defects_Cured_Stage || $row->stage_id==DEFICIT_COURT_FEE_PAID) {
                                                            $total_count=$count_efiling_data[0]->total_refiled_cases;
                                                        } elseif($row->stage_id==Transfer_to_IB_Stage) {
                                                            $total_count=$count_efiling_data[0]->total_transfer_to_efiling_sec;
                                                        } elseif($row->stage_id==I_B_Approval_Pending_Admin_Stage) {
                                                            $total_count=$count_efiling_data[0]->total_pending_scrutiny;
                                                        } elseif($row->stage_id==I_B_Defected_Stage) {
                                                            $total_count=$count_efiling_data[0]->total_waiting_defect_cured;
                                                        } elseif($row->stage_id==I_B_Rejected_Stage  || $row->stage_id==E_REJECTED_STAGE) {
                                                            $total_count=$count_efiling_data[0]->total_rejected;
                                                        } elseif($row->stage_id==I_B_Defects_Cured_Stage) {
                                                            $total_count=$count_efiling_data[0]->total_defect_cured;
                                                        } elseif($row->stage_id==LODGING_STAGE || $row->stage_id==DELETE_AND_LODGING_STAGE || $row->stage_id==MARK_AS_ERROR) {
                                                            $total_count=$count_efiling_data[0]->total_lodged_cases;
                                                        } elseif($row->stage_id==E_Filed_Stage || $row->stage_id==E_FILING_TYPE_NEW_CASE || $row->stage_id==CDE_ACCEPTED_STAGE || $row->stage_id==E_FILING_TYPE_CDE) {
                                                            $total_count=$count_efiling_data[0]->total_efiled_cases;
                                                        } elseif($row->stage_id==Document_E_Filed || $row->stage_id==E_FILING_TYPE_MISC_DOCS) {
                                                            $total_count=$count_efiling_data[0]->total_efiled_docs;
                                                        } elseif($row->stage_id==DEFICIT_COURT_FEE_E_FILED || $row->stage_id==E_FILING_TYPE_DEFICIT_COURT_FEE) {
                                                            $total_count=$count_efiling_data[0]->total_efiled_deficit;
                                                        } elseif($row->stage_id==IA_E_Filed || $row->stage_id==E_FILING_TYPE_IA) {
                                                            $total_count=$count_efiling_data[0]->total_efiled_ia;
                                                        } elseif($row->stage_id==HOLD) {
                                                            $total_count=$count_efiling_data[0]->total_hold_cases;
                                                        } elseif($row->stage_id==DISPOSED) {
                                                            $total_count=$count_efiling_data[0]->total_hold_disposed_cases;
                                                        }
                                                        @endphp	                                                                       
                                                        <tr>
                                                            <?php $href = ($total_count > 0) ? base_url("adminDashboard/stageList/" . htmlentities(url_encryption($stage_id , ENT_QUOTES))) : ''; ?>	
                                                            <td data-key="Stages"><a href="<?php echo $href; ?>"><i class="fa fa-square dark_blue"></i>
                                                                {{ isset($row->admin_stage_name) ? $row->admin_stage_name : '' }}</a>
                                                            </td>
                                                            <td data-key="Count"> <a href="<?php echo $href; ?>"></i>{{ $total_count }}</a> </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            @endif
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
@endsection
<script>
    function ActionToAllUserCount() {
        var AllUserCount = document.querySelector('#AllUserCount').checked;
        /*if(!confirm("Do you really want to do this?")) {
            return false;
        }*/
        window.location.href = "<?= base_url('adminDashboard/DefaultController/ActionToAllUserCount?AllUserCount=') ?>" + AllUserCount;
    }
</script>