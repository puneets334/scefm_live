@extends('layout.app')
@push('style')
<style>
    .yellow{
        color: #f0ad4e;
    }
    .orange{
        color:#FF7F50;
    }
    .dark_blue{
        color:#0040ff;
    }
    p:hover {
        background-color: #EDEDED !important;
    }.text_position{
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

    input:checked + .slider {
        background-color: #2196F3;
    }

    input:focus + .slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked + .slider:before {
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
</style>
@endpush
@section('content')
	<div class="container-fluid">		
        <div class="row" >
			<div class="col-lg-12">
				<?php
				$users_array = array(USER_ADMIN);
				if (in_array(getSessionData('login')['ref_m_usertype_id'], $users_array)) {
					?>
					<div class="dashboard-section dashboard-tiles-area">
						<div class="row">
							<div class="col-12 col-sm-12 col-md-4 col-lg-2">
							<?php if (isset($count_efiling_data) && $count_efiling_data[0]->total_new_efiling == 0) { ?>
								<a href="#"> </a>  
							<?php } else { ?>
								<a href="<?= base_url('adminDashboard/stageList/' . htmlentities(url_encryption(New_Filing_Stage, ENT_QUOTES))); ?>">
								<?php } ?>
								<div class="dashbord-tile pink-tile">
									<h6 class="tile-title">New Filing</h6>
									 
									<h4 class="main-count"><?php echo htmlentities($count_efiling_data[0]->total_new_efiling, ENT_QUOTES); ?></h4>
									 
								</div>
								</a>
							</div>
							<div class="col-12 col-sm-12 col-md-4 col-lg-2">
								 <?php if ($count_efiling_data[0]->total_refiled_cases == 0) { ?>
									<a href="#"> </a>  
								<?php } else { ?>
									<a href="<?= base_url('adminDashboard/stageList/' . htmlentities(url_encryption(Initial_Defects_Cured_Stage, ENT_QUOTES))); ?>">
									<?php } ?>
								<div class="dashbord-tile purple-tile">
									<h6 class="tile-title">Complied Objections</h6>									 
									<h4 class="main-count"><?php echo htmlentities($count_efiling_data[0]->total_refiled_cases, ENT_QUOTES); ?></h4>									 
								</div>
								</a>
							</div>
							<div class="col-12 col-sm-12 col-md-4 col-lg-2">
								 <?php if ($count_efiling_data[0]->total_transfer_to_efiling_sec == 0) { ?>
									<a href="#"> </a>  
								<?php } else { ?>
									<a href="<?= base_url('adminDashboard/stageList/' . htmlentities(url_encryption(Transfer_to_IB_Stage, ENT_QUOTES))); ?>" >
									<?php } ?>
								<div class="dashbord-tile blue-tile">
									<h6 class="tile-title">Transfer to ICMIS</h6>									 
									<h4 class="main-count"><?php echo htmlentities($count_efiling_data[0]->total_transfer_to_efiling_sec, ENT_QUOTES); ?></h4>								 
								</div>
								</a>
							</div>
							<div class="col-12 col-sm-12 col-md-4 col-lg-2">
								<?php if ($count_efiling_data[0]->total_pending_scrutiny == 0) { ?>
									<a href="#"> </a>  
								<?php } else { ?>
									<a href="<?= base_url('adminDashboard/stageList/' . htmlentities(url_encryption(I_B_Approval_Pending_Admin_Stage, ENT_QUOTES))); ?>" >
									<?php } ?>
								<div class="dashbord-tile blue-tile">
									<h6 class="tile-title">Pending Scrutiny</h6>									 
									<h4 class="main-count"><?php echo htmlentities($count_efiling_data[0]->total_pending_scrutiny, ENT_QUOTES); ?></h4>								 
								</div>
								</a>
							</div>
							<div class="col-12 col-sm-12 col-md-4 col-lg-2">
								 <?php if ($count_efiling_data[0]->total_defect_cured == 0) { ?>
									<a href="#"> </a>  
								<?php } else { ?>
									<a href="<?= base_url('adminDashboard/stageList/' . htmlentities(url_encryption(I_B_Defects_Cured_Stage, ENT_QUOTES))); ?>" >
									<?php } ?>
								<div class="dashbord-tile blue-tile">
									<h6 class="tile-title">Defects Cured</h6> 
									<h4 class="main-count"><?php echo htmlentities($count_efiling_data[0]->total_defect_cured, ENT_QUOTES); ?></h4>									 
								</div>
								</a>
							</div>
						</div>
					</div>
					<?php
					if (!empty(getSessionData('MSG'))) {
						echo getSessionData('MSG');
					}
					if (!empty(getSessionData('msg'))) {
						echo getSessionData('msg');
					}
					$session = session();
					$users_array = array(USER_ADMIN);
					if (in_array(getSessionData('login')['ref_m_usertype_id'], $users_array)) {
						?>
						<div class="row" style="display: none;">
							<div class="col-sm-12 col-xs-12">
								<div class="form-group">
									<label class="control-label col-md-1 col-sm-1 input-sm"><button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#FinalSubmitModal">View All users</button></label>
									<div class="col-md-1 col-sm-1">
										<label class="switch">
											<input type="checkbox" name="AllUserCount" id="AllUserCount" onclick="ActionToAllUserCount()" <?php echo (!empty($this->session->userdata['login']['AllUserCount']) && ($this->session->userdata['login']['AllUserCount'] ==1)) ? 'checked' : ''  ?> >
											<span class="slider round"></span>
										</label>
									</div>
								</div>
							</div>
						</div>
					<?php } ?>
					<div class="dashboard-section">
						<div class="row">
							<div class="col-12 col-sm-12 col-md-4 col-lg-4">
								<div class="dash-card">
									<div class="title-sec">
										<h5 class="unerline-title">Efile Status (Before Acceptance by Court)</h5>
									</div>
									<div class="table-sec">
										<div class="table-responsive">
											<table  class="tile_info"  style="width:100%">
												<tbody>
													<tr>
														<th>
															<div class="col-lg-7 col-md-7 col-sm-7 col-xs-7"><b>Stages</b></div>															 
														</th>
														<th>														 
															<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5"><b>Count</b></div>
														</th>
													</tr>
													<tr>
														<?php $href = ($count_efiling_data[0]->total_new_efiling == '0' ) ? 'javascript:void(0)' : base_url("adminDashboard/stageList/" . htmlentities(url_encryption(New_Filing_Stage, ENT_QUOTES))); ?>
														<td><a href=<?php echo $href; ?>><i class = "fa fa-square green"></i> New Filing </a></td>
														<td><a href=<?php echo $href; ?>> <?php echo htmlentities($count_efiling_data[0]->total_new_efiling, ENT_QUOTES); ?></a>   </td>
													</tr>
													<tr>
														<?php $href = ($count_efiling_data[0]->total_not_accepted == '0' ) ? 'javascript:void(0)' : base_url("adminDashboard/stageList/" . htmlentities(url_encryption(Initial_Defected_Stage, ENT_QUOTES))); ?>
														<td><a href=<?php echo $href; ?>><i class="fa fa-square red"></i> For Compliance </a> </td>
														<td><a href=<?php echo $href; ?>><?php echo htmlentities($count_efiling_data[0]->total_not_accepted, ENT_QUOTES); ?></a></td>
													</tr>
													<tr>
														<?php $href = ($count_efiling_data[0]->deficit_crt_fee == '0' ) ? 'javascript:void(0)' : base_url("adminDashboard/stageList/" . htmlentities(url_encryption(DEFICIT_COURT_FEE, ENT_QUOTES))); ?>
														<td><a href=<?php echo $href; ?>><i class="fa fa-square dark_blue"></i> Pay Deficit Fee</a>  </td>
														<td><a href=<?php echo $href; ?>><?php echo htmlentities($count_efiling_data[0]->deficit_crt_fee, ENT_QUOTES); ?></a></td>
													</tr>
													<tr>
														<?php $href = ($count_efiling_data[0]->total_refiled_cases == '0' ) ? 'javascript:void(0)' : base_url("adminDashboard/stageList/" . htmlentities(url_encryption(Initial_Defects_Cured_Stage, ENT_QUOTES))); ?>
														<td><a href=<?php echo $href; ?>><i class="fa fa-square purple"></i> Complied Objections</a></td>
														<td><a href=<?php echo $href; ?>><?php echo htmlentities($count_efiling_data[0]->total_refiled_cases, ENT_QUOTES); ?></a></td>
													</tr>
													<tr>
														<?php $href = ($count_efiling_data[0]->total_transfer_to_efiling_sec == '0' ) ? 'javascript:void(0)' : base_url("adminDashboard/stageList/" . htmlentities(url_encryption(Transfer_to_IB_Stage, ENT_QUOTES))); ?>
														<td><a href=<?php echo $href; ?>><i class="fa fa-square yellow"></i> Transfer to ICMIS</a></td>
														<td><a href=<?php echo $href; ?>> <?php echo htmlentities($count_efiling_data[0]->total_transfer_to_efiling_sec, ENT_QUOTES); ?></a></td>
													</tr>
													<tr>
														<?php $href = ($count_efiling_data[0]->total_hold_cases == '0' ) ? 'javascript:void(0)' : base_url("adminDashboard/stageList/" . htmlentities(url_encryption(HOLD, ENT_QUOTES))); ?>
														<td><a href=<?php echo $href; ?>><i class="fa fa-square yellow"></i> Hold</a></td>
														<td><a href=<?php echo $href; ?>> <?php echo htmlentities($count_efiling_data[0]->total_hold_cases, ENT_QUOTES); ?></a></td>
													</tr>
													<tr>
														<?php $href = ($count_efiling_data[0]->total_hold_disposed_cases == '0' ) ? 'javascript:void(0)' : base_url("adminDashboard/stageList/" . htmlentities(url_encryption(DISPOSED, ENT_QUOTES))); ?>
														<td><a href=<?php echo $href; ?>><i class="fa fa-square yellow"></i> Disposed</a></td>
														<td><a href=<?php echo $href; ?>> <?php echo htmlentities($count_efiling_data[0]->total_hold_disposed_cases, ENT_QUOTES); ?></a></td>
													</tr>
												</tbody>
											</table>
										</div>									 
									</div>
								</div>													
							</div>
							<div class="col-12 col-sm-12 col-md-4 col-lg-4">
								<div class="dash-card">
									<div class="title-sec">
										<h5 class="unerline-title">Filing Section Status</h5>
									</div>
									<div class="table-sec">
										<div class="table-responsive">
											<table  class="tile_info" style="width:100%">
												<tbody>
													<tr>
														<th>
															<div class="col-lg-7 col-md-7 col-sm-7 col-xs-7"><b>Stages</b></div> 
														</th>
														<th> 
															<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5"><b>Count</b></div>
														</th>
													</tr>
													<!--<tr>
														<?php /*$href = ($count_efiling_data[0]->total_available_for_cis == '0' ) ? 'javascript:void(0)' : base_url("adminDashboard/stageList/" . htmlentities(url_encryption(Transfer_to_CIS_Stage, ENT_QUOTES))); */?>
														<td><a href="<?php /*echo $href; */?>"><i class="fa fa-square orange "></i>Get ICMIS Status</a> </td>
														<td><a href="<?php /*echo $href; */?>"> <?php /*echo htmlentities($count_efiling_data[0]->total_available_for_cis, ENT_QUOTES); */?></a></td>
													</tr>-->
													<tr>
														<?php $href = ($count_efiling_data[0]->total_pending_scrutiny == '0' ) ? 'javascript:void(0)' : base_url("adminDashboard/stageList/" . htmlentities(url_encryption(I_B_Approval_Pending_Admin_Stage, ENT_QUOTES))); ?>
														<td><a href="<?php echo $href; ?>"><i class="fa fa-square purple"></i> Pending Scrutiny</a></td>
														<td><a href="<?php echo $href; ?>"><?php echo htmlentities($count_efiling_data[0]->total_pending_scrutiny, ENT_QUOTES); ?></a></td>
													</tr>
													<tr>
														<?php $href = ($count_efiling_data[0]->total_waiting_defect_cured == '0' ) ? 'javascript:void(0)' : base_url("adminDashboard/stageList/" . htmlentities(url_encryption(I_B_Defected_Stage, ENT_QUOTES))); ?>
														<td><a href="<?php echo $href; ?>"><i class="fa fa-square red"></i> Defective</a></td>
														<td><a href="<?php echo $href; ?>"><?php echo htmlentities($count_efiling_data[0]->total_waiting_defect_cured, ENT_QUOTES); ?></a></td>
													</tr>
													<tr> 
														<?php $href = ($count_efiling_data[0]->total_defect_cured == '0' ) ? 'javascript:void(0)' : base_url("adminDashboard/stageList/" . htmlentities(url_encryption(I_B_Defects_Cured_Stage, ENT_QUOTES))); ?>
														<td><a href="<?php echo $href; ?>"><i class="fa fa-square dark_blue"></i> Defects Cured</a></td>
														<td><a href="<?php echo $href; ?>"><?php echo htmlentities($count_efiling_data[0]->total_defect_cured, ENT_QUOTES); ?></a></td>
													</tr>
												</tbody>
											</table>
										</div>									 
									</div>
								</div>													
							</div>
							<div class="col-12 col-sm-12 col-md-4 col-lg-4">
								<div class="dash-card">
									<div class="title-sec">
										<h5 class="unerline-title">Efiling History(After Acceptance by Court)</h5>
									</div>
									<div class="table-sec">
										<div class="table-responsive">
											<table  class="tile_info" style="width:100%">
												<tbody>
													<tr>
														<th>
															<div class="col-lg-7 col-md-7 col-sm-7 col-xs-7"><b>Stages</b></div>
														</th>
														<th>
															<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5"><b>Count</b></div>
														</th>
													</tr>
													<tr>
														<?php $href = ($count_efiling_data[0]->total_efiled_cases == '0' ) ? 'javascript:void(0)' : base_url("adminDashboard/stageList/" . htmlentities(url_encryption(E_Filed_Stage, ENT_QUOTES))); ?>
														<td><a href="<?php echo $href; ?>"><i class="fa fa-square aero "></i> Cases</a></td>
														<td><a href="<?php echo $href; ?>"> <?php echo htmlentities($count_efiling_data[0]->total_efiled_cases, ENT_QUOTES); ?> </a> </td>
													</tr>
													<tr> 
														<?php $href = ($count_efiling_data[0]->total_efiled_docs == '0' ) ? 'javascript:void(0)' : base_url("adminDashboard/stageList/" . htmlentities(url_encryption(Document_E_Filed, ENT_QUOTES))); ?>
														<td><a href="<?php echo $href; ?>"><i class="fa fa-square purple"></i> Documents</a></td>
														<td><a href="<?php echo $href; ?>"><?php echo htmlentities($count_efiling_data[0]->total_efiled_docs, ENT_QUOTES); ?></a></td>
													</tr>
													<tr>
														<?php $href = ($count_efiling_data[0]->total_efiled_deficit == '0' ) ? 'javascript:void(0)' : base_url("adminDashboard/stageList/" . htmlentities(url_encryption(DEFICIT_COURT_FEE_E_FILED, ENT_QUOTES))); ?>
														<td><a href="<?php echo $href; ?>"><i class="fa fa-square dark_blue"></i> Paid Deficit Fee</a></td>
														<td><a href="<?php echo $href; ?>">  <?php echo htmlentities($count_efiling_data[0]->total_efiled_deficit, ENT_QUOTES); ?></a></td>
													</tr>
													<?php if (env('ENABLE_E_FILE_IA_FOR_HC') || env('ENABLE_E_FILE_IA_FOR_ESTAB')) { ?>
														<tr>
															<?php $href = ($count_efiling_data[0]->total_efiled_ia == '0' ) ? 'javascript:void(0)' : base_url("adminDashboard/stageList/" . htmlentities(url_encryption(IA_E_Filed, ENT_QUOTES))); ?>
															<td><a href="<?php echo $href; ?>"><i class="fa fa-square green"></i> IA</a> </td>
															<td><a href="<?php echo $href; ?>"><?php echo htmlentities($count_efiling_data[0]->total_efiled_ia, ENT_QUOTES); ?></a></td>
														</tr>
													<?php } ?>
													<tr>
														<?php $href = ($count_efiling_data[0]->total_rejected == '0' ) ? 'javascript:void(0)' : base_url("adminDashboard/stageList/" . htmlentities(url_encryption(I_B_Rejected_Stage, ENT_QUOTES))); ?>
														<td><a href="<?php echo $href; ?>"><i class="fa fa-square red"></i> Rejected</a></td>
														<td><a href="<?php echo $href; ?>"> <?php echo htmlentities($count_efiling_data[0]->total_rejected, ENT_QUOTES); ?></a></td>
													</tr>
													<tr>
														<?php $href = ($count_efiling_data[0]->total_lodged_cases == '0' ) ? 'javascript:void(0)' : base_url("adminDashboard/stageList/" . htmlentities(url_encryption(I_B_Defected_Stage.'@'.MARK_AS_ERROR, ENT_QUOTES))); ?>
														<td><a href="<?php echo $href; ?>"><i class="fa fa-square yellow"></i> Idle/Unprocessed e-Filed No.'s</a></td>
														<td><a href="<?php echo $href; ?>"><?php echo htmlentities($count_efiling_data[0]->total_lodged_cases, ENT_QUOTES); ?></a></td>
													</tr>
												</tbody>
											</table>
										</div>										 
									</div>
								</div>													
							</div>
						</div>
					</div>					
				<?php
			}
			$users_read_only_array = array(USER_ADMIN_READ_ONLY,USER_EFILING_ADMIN);
			if (in_array($_SESSION['login']['ref_m_usertype_id'], $users_read_only_array)) { ?> 
				<div class="dashboard-section">
					<div class="row">
						<div class="col-12 col-sm-12 col-md-4 col-lg-4">
							<div class="dash-card">
								<div class="title-sec">
									<h5 class="unerline-title">Pending With Registry</h5>
								</div>
								<div class="table-sec">
									<div class="table-responsive">
										<table  class="tile_info" style="width:100%">
											<tbody>
												<tr>
													<th>
														<div class="col-lg-7 col-md-7 col-sm-7 col-xs-7"><b>Stages</b></div>															 
													</th>
													<th>														 
														<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5"><b>Count</b></div>
													</th>
												</tr>											 
												<?php
												$total_count=0;												 
												foreach ($stage_list as $row) {
													if($row->meant_for =='R') {
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
															$total_count=$count_efiling_data[0]->total_efiled_deficit;
														} elseif($row->stage_id==IA_E_Filed || $row->stage_id==E_FILING_TYPE_IA) {
															$total_count=$count_efiling_data[0]->total_efiled_ia;
														} elseif($row->stage_id==HOLD) {
															$total_count=$count_efiling_data[0]->total_hold_cases;
														} elseif($row->stage_id==DISPOSED) {
															$total_count=$count_efiling_data[0]->total_hold_disposed_cases;
														}
														?>
														<tr>
															<?php $users_read_only_array = array(USER_ADMIN_READ_ONLY,USER_EFILING_ADMIN);
															if (in_array($_SESSION['login']['ref_m_usertype_id'], $users_read_only_array)) { ?>
															<?php $href = ($total_count ==0) ? 'javascript:void(0)' : base_url("report/search/list/" . htmlentities(url_encryption($row->stage_id, ENT_QUOTES))); ?>
															<?php } else{ ?>
																<?php $href = ($total_count ==0) ? 'javascript:void(0)' : base_url("adminDashboard/stageList/" . htmlentities(url_encryption($row->stage_id, ENT_QUOTES))); ?>
															<?php } ?>
															<td><a href="<?php echo $href; ?>" ><i class="fa fa-square purple"></i><?=$row->admin_stage_name?></a></td>
															<td><a href="<?php echo $href; ?>" ><?php echo htmlentities($total_count, ENT_QUOTES); ?></a></td>
														</tr>
														<?php
													}
												}
												?>
											</tbody>
										</table>
									</div>									 
								</div>
							</div>													
						</div>
						<div class="col-12 col-sm-12 col-md-4 col-lg-4">
							<div class="dash-card">
								<div class="title-sec">
									<h5 class="unerline-title">Pending With Advocate</h5>
								</div>
								<div class="table-sec">
									<div class="table-responsive">
										<table class="tile_info"  style="width:100%">
											<tbody>
												<tr>
													<th>
														<div class="col-lg-7 col-md-7 col-sm-7 col-xs-7"><b>Stages</b></div> 
													</th>
													<th> 
														<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5"><b>Count</b></div>
													</th>
												</tr>
												<?php
												$total_count=0;
												foreach ($stage_list as $row) {
													if($row->meant_for =='A') {
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
															// $total_count=$count_efiling_data[0]->total_lodged_cases;
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
														?>
														<tr>
															<?php $href = ($total_count ==0) ? 'javascript:void(0)' : base_url("adminDashboard/stageList/" . htmlentities(url_encryption($row->stage_id, ENT_QUOTES))); ?>
															<td><a href="<?php echo $href; ?>" target="_blank"><i class="fa fa-square purple"></i> <?=$row->admin_stage_name ?></a></td>
															<td><a href="<?php echo $href; ?>" target="_blank"><?php echo htmlentities($total_count, ENT_QUOTES); ?></a></td>
														</tr>
                                            			<?php
													}
												}
												?>
											</tbody>
										</table>
									</div>								 
								</div>
							</div>													
						</div>						 
					</div>
				</div>
			<?php } ?>			
		</div>		 
	</div>     
</div>
@endsection
@push('script')
<script>
    function ActionToAllUserCount() {
        var AllUserCount= document.querySelector('#AllUserCount').checked;
        /*if(!confirm("Do you really want to do this?")) {
            return false;
        }*/
        window.location.href = "<?=base_url('adminDashboard/DefaultController/ActionToAllUserCount?AllUserCount=')?>"+AllUserCount;
    }
</script>
@endpush