@extends('layout.app')
@section('content')
<style>
    .rounded_success {
        border-radius: 25px;
        background: #73AD21;
        padding: 5px; 
        color:white!important;
    }
    .rounded_primary {
        border-radius: 25px;
        background: #286090;
        padding: 5px;  
        color:white;
    }
</style>
<?php $session = session(); ?>
<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12">
            <div class="dashboard-section dashboard-tiles-area"></div>
			<div class="row">
				<div class="col-12 col-sm-12 col-md-12 col-lg-12">
					<div class="dash-card">
						<div class="title-sec">
							<h5 class="unerline-title"> Manage E-file Stages </h5>
							<a href="javascript:void(0)" onclick="window.history.back()" class="quick-btn pull-right"><span class="mdi mdi-chevron-double-left"></span>Back</a>
						</div>
						<div class="panel-body">
                            <div class="row">
                                <form action="{{base_url('adminReport/EfileStages/search')}}" method="GET">
                                    <div class="col-md-4 col-sm-4 col-xs-4">
                                        <div class="form-group">
                                            <label>E-file No</label>
                                            <input type="text" value="{{isset($efileno) ? $efileno:'' }}" name="efileno" id="efileno" class="form-control cus-form-ctrl" onkeyup="this.value = this.value.replace(/[^a-zA-Z0-9_-]/g, '')">
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-3 col-xs-3">
                                        <label>&nbsp;</label>
                                        <div class="form-group">
                                            <button type="submit" class="quick-btn Download1">Search</button>
                                            <div id="loader_div" class="loader_div" style="display: none;"></div>
                                        </div>
                                    </div>
                                </form>
                                <?php echo session()->getFlashdata('message'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            @if(isset($case_details))
            <div class="dash-card">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_content">
                            <div class="panel-body">
                                <table id="datatable-responsive" class="table table-striped table-border custom-table" cellspacing="0" width="100%">
                                    <thead>
                                        <tr class="success">
                                            <th>E-file No</th>
                                            <th>E-file Year</th>
                                            <th>Created On</th>
                                            <th>Created By</th>
                                            <th>Activated On</th>
                                            <th>Activated By</th>
                                            <th>Active</th>
                                            <th>Stage</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                        $show_stage_change_button=true;
                                        $stage_status = '<span class="rounded_success">Current Stage</span>';
                                        @endphp
                                        @forelse($case_details as $key=>$case_detail)
                                            @if($key==0 && $case_detail->stage_id==12)
                                                @php
                                                    $show_stage_change_button=false;
                                                    $stage_status = '<span class="rounded_success">Current Stage</span>';
                                                @endphp
                                            @endif
                                            <tr>
                                                <td>{{$case_detail->efiling_no}}</td>
                                                <td>{{$case_detail->efiling_year}}</td>
                                                <td>{{$case_detail->create_on}}</td>
                                                <td>{{$case_detail->full_name}}</td>
                                                <td>{{$case_detail->activated_on}}</td>
                                                <td>{{$case_detail->activated_full_name}}</td>
                                                <td>
                                                    @if($case_detail->is_active=='t')
                                                        Yes
                                                    @else
                                                        No
                                                    @endif
                                                </td>
                                                <td>
                                                    {{$case_detail->user_stage_name}} ({{$case_detail->stage_id}} )
                                                    <div class="pull-right"><?= $stage_status; ?></div>
                                                </td>
                                                <td>
                                                    @if(in_array($case_detail->stage_id,CHANGE_EFILE_STAGE) && $show_stage_change_button && $case_detail->is_active=='t')
                                                    @php 
                                                        $show_stage_change_button=false;
                                                    @endphp
                                                    <button type="button" class="btn btn-success" onclick="showModal('{{$key}}')">
                                                        Change Stage
                                                    </button>
                                                    @else &nbsp; @endif
                                                </td>
                                            </tr>
                                            @if($case_detail->is_active=='t')
                                                <div class="modal fade" id="{{$key}}efileStageModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <form id="{{$key}}efileStageForm">
                                                                <input type="hidden" id="{{$key}}csrf_token_input" name="{{$csrf['name']}}" value="{{$csrf['hash']}}" />
                                                                <input type="hidden" name="efileno" value="{{$case_detail->efiling_no}}" />
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title" id="myModalLabel">Update E-file Stage(#{{$case_detail->efiling_no}})</h4>
                                                                    <button type="button" class="close btn-sm" data-bs-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="form-group">
                                                                        <label for="remarks">Change Stage To<span style="color:red">*</span></label>
                                                                        <select name="stage_id" class="form-control">
                                                                            <option value="">Select Stage</option>
                                                                            @foreach($stage_list as $stage)
                                                                                @if(($stage->stage_id=='1' && $case_detail->stage_id=='8') || ($stage->stage_id=='12' && $case_detail->stage_id=='9') || ($stage->stage_id=='12' && $case_detail->stage_id=='11') || ($stage->stage_id=='12' && $case_detail->stage_id=='10'))
                                                                                    <option value="{{$stage->stage_id}}">
                                                                                        {{$stage->user_stage_name}}
                                                                                    </option>
                                                                                @endif
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="remarks">Remarks<span style="color:red">*</span></label>
                                                                        <textarea name="remarks" class="form-control" rows="4"></textarea>
                                                                    </div>
                                                                    <div id="{{$key}}messagediv" class="mt-2 alert "></div>
                                                                </div>
                                                            </form>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                                                                <button type="button" class="btn btn-primary" onclick="submitForm('{{$key}}')">Update Stage</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            @php
                                                $stage_status = '<span class="rounded_primary">Previous Stage</span>';
                                            @endphp
                                        @empty
                                            <tr>
                                                <td colspan="3">No records found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>                                    
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
<script src="<?= base_url() . 'assets/newAdmin/' ?>js/jquery351.min.js"></script>
<script>
    $(document).ready(function() {
        $('#datatable-responsive').DataTable({
            
        });
    });
    function submitForm(key) {
        $.ajax({
            type: "POST",
            url: "{{base_url('adminReport/EfileStages/updateStage')}}",
            cache: false,
            data: $('#' + key + 'efileStageForm').serialize(),
            success: function(response) {
                var responsedata = JSON.parse(JSON.stringify(response));
                if (responsedata.status) {
                    $('#' + key + 'messagediv').addClass('alert-success');
                    $('#' + key + 'messagediv').removeClass('alert-danger');
                    $('#' + key + 'messagediv').html(responsedata.message);
                    setTimeout(function() {
                        $('#' + key + 'efileStageModal').modal('hide');
                        window.location.href =
                            "{{base_url('adminReport/EfileStages/search?efileno=')}}" +
                            responsedata.efileno;
                    }, 2000);
                } else {
                    $('#' + key + 'csrf_token_input').val(responsedata.token);
                    $('#' + key + 'messagediv').addClass('alert-danger');
                    $('#' + key + 'messagediv').removeClass('alert-success');
                    $('#' + key + 'messagediv').html(responsedata.message);
                }
            },
            error: function() {
                alert("Error");
            }
        });
    }
    function showModal(key){
        $('#' + key + 'efileStageModal').modal('show');
    }
</script>