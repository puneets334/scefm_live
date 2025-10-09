@extends('layout.frontApp')
@section('content')
	<!-- Login Area Start  -->
	<div class="login-area">
		<div class="container">
			<div class="row">
				<div class="col-12 col-sm-12 col-md-6 col-lg-7 login-banner">
					<div class="login-banner-inner">
						<div class="banimg-sec">
							<!-- <img src="<?= base_url() . 'assets/newDesign/' ?>images/SCI-banner.png" alt="" class="img-fluid"> -->
                            <img src="<?= base_url() . 'assets/newDesign/' ?>images/logo-full.png" alt="" class="img-fluid  logo-at-banner">

						</div>
						<div class="banner-txts">
							<h5>SC-EFM </h5>
							<h6>E-Filing Module</h6>
							<h6>Supreme Court of India</h6>
						</div>
					</div>
				</div>
				<div class="col-12 col-sm-12 col-md-6 col-lg-5 login-section">
					<div class="login-s-inner">
						<div class="httxt">
							<h4>Enter the code</h4>
						</div>
						<div class="loin-form otp-form">
							<?php $session = session(); ?>
							@if($session->has('msg'))
							<div class="text-danger">
								<b>{{ esc($session->get('msg')) }}</b>
							</div>
							@endif
							<?php  //echo $_SESSION["captcha"];
							$attribute = array('class' => 'form_horizontal', 'name' => 'form_horizontal', 'id' => 'login-form', 'accept-charset' => 'utf-8', 'autocomplete' => 'off');
							echo form_open(base_url('otp'), $attribute);
							?>
							<input type="text" style="display: none;" name="_token" value="{{ csrf_token() }}">
							<div class="row">
								<div class="col-12 col-sm-12 col-md-12 col-lg-12">
									<div class="mb-3">
										<label for="" class="form-label">Enter the OTP code we sent to your mobile, be careful not to share the code to anyone</label>
									</div>
								</div>
							</div>
							<div class="otp-sec">
								<div class="row ">
									<div class="col-12 col-sm-12 col-md-12 col-lg-12">
										<div class="row otp-row">
											<div class="col mb-3">
												<input type="text" required class="form-control round-input" maxlength="1" placeholder="" name="otp_one" value="1">
											</div>
											<div class="col mb-3">
												<input type="text" required class="form-control round-input" maxlength="1" placeholder=" " name="otp_two" value="2">
											</div>
											<div class="col mb-3 ">
												<input type="text" required class="form-control round-input" maxlength="1" placeholder=" " name="otp_three" value="3">
											</div>
											<div class="col mb-3">
												<input type="text" required class="form-control round-input" maxlength="1" placeholder=" " name="otp_four" value="4">
											</div>
											<div class="col mb-3">
												<input type="text" required class="form-control round-input" maxlength="1" placeholder=" " name="otp_five" value="5">
											</div>
											<div class="col mb-3">
												<input type="text" required class="form-control round-input" maxlength="1" placeholder=" " name="otp_six" value="6">
											</div>
										</div>
									</div>
								</div>
								{{-- <div class="row">
										<div class="col-12 col-sm-12 col-md-12 col-lg-12">
											<div class="mb-3">
												<label for="" class="form-label">Didn’t receive the OTP? Tap <a href=""  class="yellow-txt"> Resend OTP </a> to get a new one.</label>
											</div>
										</div>
									</div> --}}
							</div>
							<div class="row">
								<div class="col-12 col-sm-12 col-md-12 col-lg-12">
									<div class="mb-3">
										<button type="submit" class="btn quick-btn ">Verify The Code</button>
									</div>
								</div>
							</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Login Area End  -->
	@endsection
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			const inputs = document.querySelectorAll('.round-input');

			inputs.forEach((input, index) => {
				input.addEventListener('input', function() {
					if (input.value.length === 1 && index < inputs.length - 1) {
						inputs[index + 1].focus();
					}
				});

				input.addEventListener('keydown', function(e) {
					if (e.key === 'Backspace' && input.value.length === 0 && index > 0) {
						inputs[index - 1].focus();
					}
				});
			});
		});
	</script>
