<!DOCTYPE html>
<html>
<head>
	<link rel="icon" href="<?=base_url('assets/sci_logo.png')?>" type="image/x-icon">
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?=$page_title?></title>
	<meta Http-Equiv="Cache-Control" Content="no-cache">
	<meta Http-Equiv="Pragma" Content="no-cache">
	<meta Http-Equiv="Expires" Content="0">


	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="<?=base_url()?>assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?=base_url()?>assets/css/AdminLTE.min.css">
	<?php
	$name='';
	$id ='';
	$mobile ='';
	$ref_attendee_type_id ='';
 if (!empty($this->session->userdata('loginData')['name'])){
		$name = $this->session->userdata('loginData')['name'];
	}
	 if(!empty($this->session->userdata('loginData')['bar_id'])){
		$id = $this->session->userdata('loginData')['bar_id'];
	}
	 if(!empty($this->session->userdata('loginData')['id'])){
		$id = $this->session->userdata('loginData')['id'];
	}
	 if(!empty($this->session->userdata('loginData')['mobile'])){
		$mobile = $this->session->userdata('loginData')['mobile'];
	}
	if(!empty($this->session->userdata('loginData')['ref_attendee_type_id'])){
		$ref_attendee_type_id= $this->session->userdata('loginData')['ref_attendee_type_id'];
	}
	//echo $name; exit;
    $paddress = !empty($this->session->userdata('loginData')['paddress']) ? $this->session->userdata('loginData')['paddress'] : $todayData[0]['address'];
	$dec_type = !empty($this->session->userdata('loginData')['dec_type']) ? $this->session->userdata('loginData')['dec_type'] : '';
	$todayUserId = !empty($todayData[0]['id']) ? $todayData[0]['id'] : '';
	$todayUserName = !empty($todayData[0]['name']) ? $todayData[0]['name'] : '';
	$todayUserMobile = !empty($todayData[0]['mobile']) ? $todayData[0]['mobile'] : '';
	$todayUserTravel = !empty($todayData[0]['travel']) ? $todayData[0]['travel'] : 0;
	$todayUserSymptoms_met = !empty($todayData[0]['symptoms_met']) ? $todayData[0]['symptoms_met'] : 0;
	$todayUserSymptoms = !empty($todayData[0]['symptoms']) ? $todayData[0]['symptoms'] : 0;
	$todayUserTravel_yes = !empty($todayData[0]['travel_yes']) ? $todayData[0]['travel_yes'] : '';
	$todayUserWheel_chair = !empty($todayData[0]['wheel_chair']) ? $todayData[0]['wheel_chair'] : 0;
	$todayUserOther_category= !empty($todayData[0]['other_category']) ? $todayData[0]['other_category'] : 0;
	$todayUserCategory= !empty($todayData[0]['category']) ? $todayData[0]['category'] : '';
	//echo '<pre>'; print_r($todayData); exit;
	?>


</head>
<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">
	<!-- Full Width Column -->
	<div class="content-wrapper">
		<div class="container">
			<!-- Main content -->
			<section class="content">
				<div class="container-fluid" ng-app="empSelfData" ng-controller="empSelfDataController" ng-init="fillCategory()" >

					<h2 class="page-header">Supreme Court Of India Self Declaration Form (For Entrants In the High Security Zone)</h2>
					<form id="empSelfdetails"  method="post" action = "" >
						<div class="row">
							<div class="form-group col-sm-9">
								<label for="travel" class="text-right">1. Have you travelled to a foreign country or to a notified area affected by COVID-19
									the last 15 days.</label>
								YES<input type="radio" id="travel_yes" ng-model="form.travel" ng-click="checkTravel()" value="1">
								NO<input type="radio" id="travel_no" ng-model="form.travel"  ng-click="checkTravel()"   value="0">
							</div>
							<?php
							if(isset($todayUserTravel_yes) && !empty($todayUserTravel_yes)){
								echo '<div class="form-group col-sm-3 " id="showCountryArea">
								<label for="travelyes" class="text-right">
									Name of the country/Area
								</label>
								<input type="text" id="travel_yes" ng-model="form.travel_yes"  maxlength="125" />
							</div>';
							}
							else{
								echo '<div class="form-group col-sm-3 hidden" id="showCountryArea">
								<label for="travelyes" class="text-right">
									Name of the country/Area
								</label>
								<input type="text" id="travel_yes" ng-model="form.travel_yes"  maxlength="125" />
							</div>';
							}
							?>


						</div>
						<div class="row">
							<div class="form-group col-sm-12">
								<label for="symptoms" class="text-right">2. Are you suffering from Fever,Cough and Cold or similar symptoms.?</label>
								YES<input type="radio" id="symptoms_yes" ng-model="form.symptoms"  value="1">
								NO<input type="radio" id="symptoms_no" ng-model="form.symptoms"  value="0">
							</div>
						</div>
						<div class="row">
							<div class="form-group col-sm-12">
								<label for="symptoms_met" class="text-right">3. Has any your Family Member or Persons(s) whom you have met
									recently displayed aforesaid symptoms recently.?</label>
								YES<input type="radio" id="symptoms_met_yes" ng-model="form.symptoms_met"  value="1">
								NO<input type="radio" id="symptoms_met_no"  ng-model="form.symptoms_met" value="0">
							</div>
						</div>
						<div class="row">
							<div class="form-group col-sm-12">
								<label for="wheel_chair" class="text-right">4. Wheel Chair Required?</label>
								YES<input type="radio" id="wheel_chair_yes" ng-model="form.wheel_chair"  value="1">
								NO<input type="radio" id="wheel_chair_no"  ng-model="form.wheel_chair" value="0">
							</div>
						</div>
						<div class="row">
							<div class="form-group col-sm-6">
								<label for="name" class="text-right">Name:</label>
								<input type="text" class="form-control" ng-model="form.name"  ng-readonly="true"  maxlength="50" ng-value="name"/>

							</div>
							<div class="form-group col-sm-6">
								<label for="category" class="text-right">Category:</label>
								<select class="form-control"  ng-model="form.category"  ng-change="checkOtherCategory()" ng-readonly="true" ng-disabled="true">
									<option value="">Select Category</option>
									<option ng-repeat="attendeelist in attendee" value="{{attendeelist.id}}">{{attendeelist.description}}</option>
								</select>
							</div>
						</div>
						<div class="row hidden" id="other_category">
							<div class="form-group col-sm-12">
								<label for="other_category" class="text-right">Other Category:</label>
								<input type="text" class="form-control" ng-model="form.other_category"  placeholder="Others" />
							</div>
						</div>

						<div class="row">
							<div class="form-group col-sm-6">
								<label for="address" class="text-right">Address:</label>
								<textarea class="form-control" ng-model="form.address" maxlength="220" placeholder="Address"></textarea>

							</div>
							<div class="form-group col-sm-6">
								<label for="mobile" class="text-right">Mobile:</label>
								<input type="text" class="form-control" id="mobile" ng-value="mobile"  ng-model="form.mobile" ng-readonly="true" placeholder="Mobile" maxlength="10"/>
							</div>
						</div>
						<input type="hidden" ng-model="form.userId" ng-value="userId"/>
						<?php
						if(isset($todayUserId) && !empty($todayUserId)){
							echo '<div class="row" id="updateuserDetails" ng-init="getTodayData()">
							<div class="form-group">
							
								<label for="mobile" class="text-right">Pass ID:</label>
								{{todayData.id}}
								<label for="mobile" class="text-right">Name:</label>
								{{todayData.name}}
								<label for="mobile" class="text-right">Mobile:</label>
								{{todayData.mobile}}
							</div>
							<input type="hidden" ng-model="form.update" ng-init="form.update=2" ng-value="update"/>
							</div>';
						}
						else{
							echo '<div class="row" id="userDetails" style="display:none;">
							<div class="form-group">
								<label for="mobile" class="text-right">Pass ID:</label>
								{{userdata.id}}
								<label for="mobile" class="text-right">Name:</label>
								{{userdata.name}}
								<label for="mobile" class="text-right">Mobile:</label>
								{{userdata.mobile}}
							</div>
							<input type="hidden" ng-model="form.update" ng-init="form.update=1" ng-value="update"/>
							</div>';
						}
						?>


						<div class="row">
							<div class="form-group col-sm-2">
								<button type="button"  id="update_form"  class="btn btn-block btn-primary"  ng-click="validateUserForm()"  style="display:none;">Update</button>
								<?php
								if(isset($todayUserId) && !empty($todayUserId)){
									echo '<button type="button"  id="update_form"  class="btn btn-block btn-primary"  ng-click="validateUserForm()"  >Update</button>';
								}
								else{
										echo '<button type="button"  id="sub_form" ng-model="sub_form" name="sub_form" ng-click="validateUserForm()" class="btn btn-block btn-primary">Submit</button>';
								}
								?>
							</div>
						</div>
					</form>

					<hr>
				</div>
			</section>
		</div>
	</div>
</div>
<!-- /.content-wrapper -->
<footer class="main-footer">
	<div class="container">
		<strong>Copyright &copy;  <a href="#">SCI Computer Cell</a>.</strong> All rights
		reserved.
	</div>
	<!-- /.container -->
</footer>
</div>
<script src="<?=base_url()?>assets/plugins/jQuery/jQuery.min.js"></script>
<script src="<?=base_url()?>assets/js/bootstrap.min.js"></script>
<script src="<?= base_url() ?>assets/js/angular.min.js"></script>
<script>
	var mainApp = angular.module("empSelfData", []);
	mainApp.controller('empSelfDataController', function ($scope,$timeout, $http) {
		$scope.form={};
		$scope.userdata={};
		$scope.todayUserData={};
		$scope.attendee={};
		$scope.todayData = '';
		$timeout(function() {
			$scope.dec_type = "<?php echo $dec_type;?>";
			$scope.form.name = "<?php echo $name;?>";
			$scope.form.mobile = "<?php echo $mobile;?>";
			$scope.form.address = "<?php echo $paddress;?>";
			$scope.form.userId = "<?php echo (int)$todayUserId;?>";
			$scope.form.travel = "<?php echo $todayUserTravel;?>";
			$scope.form.symptoms = "<?php echo $todayUserSymptoms;?>";
			$scope.form.symptoms_met = "<?php echo $todayUserSymptoms_met;?>";
			$scope.form.wheel_chair = "<?php echo $todayUserWheel_chair;?>";
			$scope.form.travel_yes = "<?php echo $todayUserTravel_yes;?>";

		});
		$scope.checkOtherCategory = function() {
			if ($scope.form.category == "8") {
				$('#other_category').removeClass('hidden');
				$('#other_category').show();
				jQuery('[ng-model="form.other_category"]').focus();
				return false;
			}
			else  {
				$('#other_category').addClass('hidden');
				$('#other_category').hide();
			}
		};
		$scope.fillCategory = function(){
			$http.post("<?=base_url()?>index.php/Home/getAttendeeList").then(function successCallback(response) {
				$scope.attendee = response.data.attendee;
				if($scope.dec_type == 1) {
					$scope.form.category = $scope.attendee[1].id;
				}
				else if($scope.dec_type == 2){
					$scope.form.category  = $scope.attendee[<?php echo $ref_attendee_type_id -1;?>].id;
				}

			});
		};
		$scope.checkTravel = function() {
			if ($scope.form.travel == "1") {
				$('#showCountryArea').removeClass('hidden');
				$('#showCountryArea').show();
				jQuery('[ng-model="form.travel_yes"]').focus();
				return false;
			}
			else if ($scope.form.travel == "0") {
				$('#showCountryArea').addClass('hidden');
				$('#showCountryArea').hide();
				return false;
			}
		};
		$scope.getTodayData = function(){
			$http.get("<?=base_url()?>index.php/Home/getTodayData",$scope.form
			).then(function successCallback(response) {
				if(response.data.todayData)
				$scope.todayData = response.data.todayData;
			});
		};
		$scope.validateUserForm = function() {
			var mreg = /^\d+$/;
			var type='';
			if (!('travel' in $scope.form)) {
				alert("Please select Have you travelled to a foreign country or to a notified area affected by COVID-19 the last 15 days.(YES/NO)");
				jQuery('[ng-model="form.travel_yes"]').focus();
				return false;
			}
			else if (($scope.form.travel =="1" || $scope.form.travel =="0" ) && ('travel' in $scope.form)) {
				if($scope.form.travel =="1" && !('travel_yes' in $scope.form)){
					alert("Please select name of the Country/Area");
					jQuery('[ng-model="form.travel_yes"]').focus();
					return false;
				}
				else if (!('symptoms' in $scope.form)) {
					alert("Please select Are you suffering from Fever,Cough and Cold or similar symptoms.(YES/NO)");
					jQuery('[ng-model="form.symptoms"]').focus();
					return false;
				}
				else if (!('symptoms_met' in $scope.form)) {
					alert("Please select Has any your Family Member or Persons(s) whom you have met recently displayed aforesaid symptoms recently.(YES/NO)");
					jQuery('[ng-model="form.symptoms"]').focus();
					return false;
				}
				else if (!('wheel_chair' in $scope.form)) {
					alert("Please select Wheel Chair.(YES/NO)");
					jQuery('[ng-model="form.wheel_chair"]').focus();
					return false;
				}
				else if (!('name' in $scope.form)) {
					alert("Please fill name");
					jQuery('[ng-model="form.name"]').focus();
					jQuery('[ng-model="form.name"]').css('border-color','red');
					return false;
				}
				else if ($scope.form.name ==""){
					alert("Please fill name");
					jQuery('[ng-model="form.name"]').focus();
					jQuery('[ng-model="form.name"]').css('border-color','red');
					return false;
				}
				else if (!('category' in $scope.form)) {
					alert("Please select category");
					jQuery('[ng-model="form.category"]').focus();
					jQuery('[ng-model="form.category"]').css('border-color','red');
					return false;
				}
				else if ($scope.form.category == "") {
					alert("Please select category");
					jQuery('[ng-model="form.category"]').focus();
					jQuery('[ng-model="form.category"]').css('border-color','red');
					return false;
				}
				else if ($scope.form.category == "8" && (!('other_category' in $scope.form))) {
					alert("Please fill other category name");
					jQuery('[ng-model="form.other_category"]').focus();
					jQuery('[ng-model="form.other_category"]').css('border-color','red');
					return false;
				}
				else if (!('address' in $scope.form)) {
					alert("Please fill address");
					jQuery('[ng-model="form.address"]').focus();
					jQuery('[ng-model="form.address"]').css('border-color','red');
					return false;
				}
				else if ($scope.form.address =="") {
					alert("Please fill address");
					jQuery('[ng-model="form.address"]').focus();
					jQuery('[ng-model="form.address"]').css('border-color','red');
					return false;
				}
				else if (!('mobile' in $scope.form)) {
					alert("Please fill mobile number");
					jQuery('[ng-model="form.mobile"]').focus();
					jQuery('[ng-model="form.mobile"]').css('border-color','red');
					return false;
				}
				else if ($scope.form.mobile =="") {
					alert("Please fill mobile number");
					jQuery('[ng-model="form.mobile"]').focus();
					jQuery('[ng-model="form.mobile"]').css('border-color','red');
					return false;
				}
				else if (!mreg.test($scope.form.mobile)) {
					alert("Please fill valid mobile number");
					jQuery('[ng-model="form.mobile"]').focus();
					jQuery('[ng-model="form.mobile"]').val('');
					jQuery('[ng-model="form.mobile"]').css('border-color','red');
					return false;
				}
				else{
					$http.post("<?=base_url()?>index.php/Attendee/saveEmpSelfData",$scope.form
					).then(function successCallback(response) {
						if(response.data.status == '1' && response.data.id == 'name'){
							jQuery('[ng-model="form.name"]').focus();
							jQuery('[ng-model="form.name"]').css('border-color','red');
							return false;
						}
						else if(response.data.status == '1' && response.data.id == 'category'){
							jQuery('[ng-model="form.category"]').focus();
							jQuery('[ng-model="form.category"]').css('border-color','red');
							return false;
						}
						else if(response.data.status == '1' && response.data.id == 'address'){
							jQuery('[ng-model="form.address"]').focus();
							jQuery('[ng-model="form.address"]').css('border-color','red');
							return false;
						}
						else if(response.data.status == '1' && response.data.id == 'mobile'){
							jQuery('[ng-model="form.mobile"]').focus();
							jQuery('[ng-model="form.mobile"]').css('border-color','red');
							return false;
						}
						else if(response.data.status == '2' && response.data.userdata){
							alert(response.data.msg);
							$("#sub_form").hide();
							$("#update_form").show();
							$("#userDetails").show();
							$("#updateuserDetails").show();
							$scope.userdata= response.data.userdata;
							location.reload();
						}
						else if(response.data.status == '3'){
							alert(response.data.msg);
						}
					});

				}
			}
		};
	});

</script>
</body>
</html>
