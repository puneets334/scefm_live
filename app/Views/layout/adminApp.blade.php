<!DOCTYPE HTML>
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="csrf-token" content="<?php csrf_hash() ?>" />
	<title>Seventh Census of Minor Irrigation Schemes</title>
	<link rel="icon" href="<?= base_url() . 'assets/newDesign/' ?>images/logo.png" sizes="32x32">
	<link rel="icon" href="<?= base_url().'assets/newDesign/images/logo.png' ?>" type="image/png" />
	<!-- <link rel="shortcut icon" href="<?= base_url() . 'assets/newAdmin/' ?>images/favicon.gif"> -->
	<link href="<?= base_url() . 'assets/newAdmin/' ?>css/bootstrap.min.css" rel="stylesheet">
	<link href="<?= base_url() . 'assets/newAdmin/' ?>css/font-awesome.min.css" rel="stylesheet">
	<link href="<?= base_url() . 'assets/newAdmin/' ?>css/animate.css" rel="stylesheet">
	<link href="<?= base_url() . 'assets/newAdmin/' ?>css/material.css" rel="stylesheet" />
	<link href="<?= base_url() . 'assets/newAdmin/' ?>css/style.css" rel="stylesheet">
	<link href="<?= base_url() . 'assets/newAdmin/' ?>css/responsive.css" rel="stylesheet">
	<link href="<?= base_url() . 'assets/newAdmin/' ?>css/black-theme.css" rel="stylesheet">
	<style>
		#loader-wrapper {
			display: flex;
			justify-content: center;
			align-items: center;
			z-index: 1000; /* Ensure it's on top of other content */
		}
	</style>
</head>

<body>
	<div id="loader-wrapper" style="display: none;">
        <div id="loader"></div>
    </div>
	<div class="wrapper">

		<!--header section-->
		@include('layout.adminHeader')
		<!--header sec   end-->

		<div class="content">
			<!-- Side Panel Starts -->
			@include('layout.adminSidebar')
			<!-- Side Panel Ends -->
			<!-- Main Panel Starts -->
			<div class="mainPanel ">
				<div class="panelInner">
					<div class="middleContent">
						<div class="container-fluid">
							<div class="row">
								<div class="col-12 sm-12 col-md-8 col-lg-8 middleContent-left">
									<div class="left-content-inner comn-innercontent">
										<div class="dashboard-section dashboard-tiles-area">
											<div class="row">
												<div class="col-12 col-sm-12 col-md-4 col-lg-4">
													<div class="dashbord-tile pink-tile">
														<h6 class="tile-title">Recent Documents</h6>
														<p class="tile-subtitle">By other Parties</p>
														<h4 class="main-count">32</h4>
														<div class="tiles-comnts">
															<div class="tile-comnt">
																<h6 class="comts-no">00</h6>
																<p class="comnt-name">Rejoinder</p>
															</div>
															<div class="tile-comnt">
																<h6 class="comts-no">00</h6>
																<p class="comnt-name">Reply</p>
															</div>
															<div class="tile-comnt">
																<h6 class="comts-no">32</h6>
																<p class="comnt-name">IA</p>
															</div>
															<div class="tile-comnt">
																<h6 class="comts-no">00</h6>
																<p class="comnt-name">Other</p>
															</div>
														</div>
													</div>
												</div>
												<div class="col-12 col-sm-12 col-md-4 col-lg-4">
													<div class="dashbord-tile purple-tile">
														<h6 class="tile-title">Incomplete Filings</h6>
														<p class="tile-subtitle">Cases/appl. Filed by you</p>
														<h4 class="main-count">07</h4>
														<div class="tiles-comnts">
															<div class="tile-comnt">
																<h6 class="comts-no">00</h6>
																<p class="comnt-name">Draft</p>
															</div>
															<div class="tile-comnt">
																<h6 class="comts-no">00</h6>
																<p class="comnt-name">For Compliance</p>
															</div>
														</div>
													</div>
												</div>
												<div class="col-12 col-sm-12 col-md-4 col-lg-4">
													<div class="dashbord-tile blue-tile">
														<h6 class="tile-title">Scrutiny Status</h6>
														<p class="tile-subtitle">Cases/appl. Filed by you</p>
														<h4 class="main-count">02</h4>
														<div class="tiles-comnts">
															<div class="tile-comnt">
																<h6 class="comts-no">02</h6>
																<p class="comnt-name">Defect Notified</p>
															</div>
															<div class="tile-comnt">
																<h6 class="comts-no">02</h6>
																<p class="comnt-name">Pending Scrutiny</p>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="dashboard-section">
											<div class="row">
												<div class="col-12 col-sm-12 col-md-12 col-lg-12">
													<div class="dash-card">
														<div class="title-sec">
															<h5 class="unerline-title">e-Filled Cases</h5>
														</div>
														<div class="table-sec">
															<div class="table-responsive">
																<table class="table table-striped custom-table">
																	<thead>
																		<tr>
																			<th>Sr. No.</th>
																			<th>Stage</th>
																			<th>e-Filling No.</th>
																			<th>Case Detail</th>
																			<th>Type</th>
																			<th>Submitted On</th>
																			<th>Allocated To</th>
																		</tr>
																	</thead>
																	<tbody>
																		<tr>
																			<td>01</td>
																			<td>Stage 2</td>
																			<td>e-3249283</td>
																			<td>Property & Money Transfer</td>
																			<td>Civil</td>
																			<td>28 May 2024</td>
																			<td>Lawyer 1</td>
																		</tr>
																		<tr>
																			<td>02</td>
																			<td>Stage 2</td>
																			<td>e-3249283</td>
																			<td>Property & Money Transfer</td>
																			<td>Civil</td>
																			<td>28 May 2024</td>
																			<td>Lawyer 1</td>
																		</tr>
																		<tr>
																			<td>03</td>
																			<td>Stage 2</td>
																			<td>e-3249283</td>
																			<td>Property & Money Transfer</td>
																			<td>Civil</td>
																			<td>28 May 2024</td>
																			<td>Lawyer 1</td>
																		</tr>
																		<tr>
																			<td>04</td>
																			<td>Stage 2</td>
																			<td>e-3249283</td>
																			<td>Property & Money Transfer</td>
																			<td>Civil</td>
																			<td>28 May 2024</td>
																			<td>Lawyer 1</td>
																		</tr>
																		<tr>
																			<td>05</td>
																			<td>Stage 2</td>
																			<td>e-3249283</td>
																			<td>Property & Money Transfer</td>
																			<td>Civil</td>
																			<td>28 May 2024</td>
																			<td>Lawyer 1</td>
																		</tr>
																		<tr>
																			<td>06</td>
																			<td>Stage 2</td>
																			<td>e-3249283</td>
																			<td>Property & Money Transfer</td>
																			<td>Civil</td>
																			<td>28 May 2024</td>
																			<td>Lawyer 1</td>
																		</tr>
																		<tr>
																			<td>07</td>
																			<td>Stage 2</td>
																			<td>e-3249283</td>
																			<td>Property & Money Transfer</td>
																			<td>Civil</td>
																			<td>28 May 2024</td>
																			<td>Lawyer 1</td>
																		</tr>

																	</tbody>
																</table>
															</div>
															<div class="pagination-area">
																<div class="sowing-pg">
																	<p>01-06 in one page</p>
																</div>
																<div class="pagination-inner">
																	<nav aria-label="Page navigation example">
																		<ul class="pagination">
																			<li class="page-item">
																				<a class="page-link" href="#" aria-label="Previous">
																					<i class="fas fa-angle-double-left"></i>
																				</a>
																			</li>
																			<li class="page-item active" aria-current="page"><a class="page-link" href="#">1</a></li>
																			<li class="page-item"><a class="page-link" href="#">2</a></li>
																			<li class="page-item"><a class="page-link" href="#">3</a></li>
																			<li class="page-item">
																				<a class="page-link" href="#" aria-label="Next">
																					<i class="fas fa-angle-double-right"></i>
																				</a>
																			</li>
																		</ul>
																	</nav>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-12 sm-12 col-md-4 col-lg-4 middleContent-right">
									<div class="right-content-inner comn-innercontent">
										<div class="dashboard-section">
											<div class="row">
												<div class="col-12 col-sm-12 col-md-12 col-lg-12">
													<div class="dash-card">
														<div class="title-sec">
															<h5 class="unerline-title">e-Filled Cases</h5>
														</div>
														<div class="calender-sec">
															<img src="<?= base_url() . 'assets/newAdmin/' ?>images/calender-img.png" alt="" class="calender-img">
														</div>
														<div class="table-sec calender-table">
															<div class="table-responsive">
																<table class="table table-striped custom-table ">
																	<thead>
																		<tr>
																			<th>Case</th>
																			<th>Date & Bench</th>
																		</tr>
																	</thead>
																	<tbody>
																		<tr>
																			<td>Case #1</td>
																			<td>
																				<div class="dt-td">
																					<span class="mdi mdi-calendar-clock"></span>
																					<span>29-May-2024, 02:30 P.M. & Bench : ad & dd</span>
																				</div>
																			</td>
																		</tr>
																		<tr>
																			<td>Case #2</td>
																			<td>
																				<div class="dt-td">
																					<span class="mdi mdi-calendar-clock"></span>
																					<span>29-May-2024, 02:30 P.M. & Bench : ad & dd</span>
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
			<!-- Main Panel Ends -->
		</div>
		<footer class="footer-sec">Content Owned by Supreme Court of India</footer>
	</div>
	<div class="add-new-area">
		<a href="javascript:void(0)" class="add-btn"><span class="mdi mdi-plus-circle-outline"></span> NEW</a>
		<div class="add-new-options">
			<ul>
				<?php if (getSessionData('login')['ref_m_usertype_id'] != AMICUS_CURIAE_USER) { ?>
					<?php if(isset($_SESSION['login']['id']) && in_array($_SESSION['login']['id'], AIASSISTED_USER_IN_LIST)) { ?>
						<li>
							<a style="color: #fff; width: 100%; display: inline-block; padding: 9px 12px; font-size: 14px;" href="{{base_url('casewithAI')}}" class="add-link">AI Assisted Case Filing</a>
						</li>
					<?php } ?>
					<li>
						<a href="javascript:void(0)" class="add-lnk">Case</a>
					</li>
				<?php } ?>
				<li>
					<a href="javascript:void(0)" class="add-lnk">IA</a>
				</li>
				<li>
					<a href="javascript:void(0)" class="add-lnk">Misc. Docs</a>
				</li>
				<?php if (getSessionData('login')['ref_m_usertype_id'] != AMICUS_CURIAE_USER) { ?>
					<li>
						<a href="javascript:void(0)" class="add-lnk">Caveat</a>
					</li>
				<?php } ?>
			</ul>
		</div>
	</div>

	<script src="<?= base_url() . 'assets/newAdmin/' ?>js/jquery351.min.js"></script>
	<script src="<?= base_url() . 'assets/newAdmin/' ?>js/bootstrap.bundle.min.js"></script>
	<script src="<?= base_url() . 'assets/newAdmin/' ?>js/general.js"></script>

	<script>
        $(document).ready(function() {
            $('#loader-wrapper').show();
            var loaderTimeout = setTimeout(function() {
                $('#loader-wrapper').fadeOut('slow', function() {
                    $('#content').fadeIn('slow');
                });
            }, 3000);

            $(window).on('load', function() {
                clearTimeout(loaderTimeout);
                $('#loader-wrapper').fadeOut('slow', function() {
                    $('#content').fadeIn('slow');
                });
            });
        });

		$.ajaxSetup({
            headers: {
                'CSRF_TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
	</script>
	<script>
document.addEventListener('DOMContentLoaded', function () {
    let inactivityTime = function () {
        let time;
        // Reset timer on user activity
        window.onload = resetTimer;
        document.onmousemove = resetTimer;
        document.onkeypress = resetTimer;
        document.onclick = resetTimer;
        document.onscroll = resetTimer;

        function logout() {
            alert('You have been logged out due to inactivity.');
            window.location.href = '<?php echo base_url('logout'); ?>';
        }

        function resetTimer() {
            clearTimeout(time);
            // Set timeout to 30 minutes (1800000 ms)
            time = setTimeout(logout, 900000);
        }
    };

    inactivityTime();
});
</script>
<!-- <script>
document.addEventListener('DOMContentLoaded', function () {
    function checkSession() {
        fetch('<?php echo base_url("is_user_status"); ?>', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.status === 'expired') {
                alert('Another login detected. You will be redirected to the login page.');
           		window.location.href = '<?php echo base_url('logout'); ?>';
            }
        })
        .catch(error => {
            // console.error('Error checking session:', error);
        });
    }

    // Check session every 60 seconds
    setInterval(checkSession, 60000);
});
</script> -->
</body>

</html>