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
	<link rel="stylesheet" href="<?=base_url()?>assets/plugins/datepicker/datepicker3.css">
	<link rel="stylesheet" href="<?=base_url()?>assets/plugins/datatables/jquery.dataTables.min.css">
	<link rel="stylesheet" href="<?=base_url()?>assets/plugins/datatables/buttons.dataTables.min.css">
</head>
<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">
    <!-- Full Width Column -->
    <div class="content-wrapper">
        <div class="container" >
            <!-- Main content -->
            <section class="content">
                <ol class="breadcrumb">
                    <li><a href="<?php echo base_url() . 'index.php/Home'; ?>"><b>Home</b></a></li>
                    <li class="active" style="color:#F08080;"><b>Emp Self Declaration Data</b></li>
                </ol>
				<div class="row">
					<div class="form-group col-sm-4">
						<label for="from" class="text-right">From Date</label>
						<input type="text" id="fromDate"  autocomplete="off" class="form-control datepick" required placeholder="From Date" value="">
					</div>
					<div class="form-group col-sm-4">
						<label for="from" class="text-right">To Date</label>
						<input type="text" id="toDate"  autocomplete="off"  class="form-control datepick" required placeholder="To Date" value="">
					</div>
					<div class="form-group col-sm-4">
						<label for="from" class="text-right">&nbsp;</label>
						<button type="button"   id="loadData" name="view"  class="btn btn-block btn-primary">View</button>
					</div>
				</div>
				<div id="empSelefData"  class="table-responsive">
					<div class="row">
					</div>
					<table id="empSelefDataTable" class="table table-striped table-hover"  ui-options="dataTableOpt" ui-jq="dataTable">
						<thead>
						<tr>
							<th>S.No.</th>
							<th>Name </th>
							<th>Mobile</th>
							<th>Category</th>
							<th>Other Category</th>
							<th>Travel</th>
							<th>Travel Yes</th>
							<th>Address</th>
							<th>Symptoms Met</th>
							<th>Wheel Chair</th>
							<th>For Date</th>
							<th>Updated</th>
						</tr>
						</thead>
					</table>
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
<script src="<?=base_url()?>assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?=base_url()?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="<?=base_url()?>assets/plugins/datatables/buttons.print.min.js"></script>
<script src="<?=base_url()?>assets/plugins/datatables/buttons.colVis.min.js"></script>
<script src="<?=base_url()?>assets/plugins/datatables/buttons.html5.min.js"></script>
<script src="<?=base_url()?>assets/plugins/datatables/pdfmake.min.js"></script>
<script src="<?=base_url()?>assets/plugins/datatables/vfs_fonts.js"></script>

</body>
</html>
<script>
	$(document).ready(function(){
		$('#fromDate').datepicker({
			format: 'dd-mm-yyyy',
			todayHighlight: true,
			autoclose:true
		});
		$('#toDate').datepicker({
			format: 'dd-mm-yyyy',
			todayHighlight: true,
			autoclose:true
		});
	});

</script>
<script>
		$("#empSelefDataTable").DataTable();
		$("#loadData").click(function() {
		var fromDate ='';
		var toDate ='';
		fromDate =$("#fromDate").val();
		toDate =$("#toDate").val();
		var fieldTrue= true;
			if (fromDate == "") {
				alert("Please select from date.");
				jQuery('#fromDate').focus();
				fieldTrue = false;
				return false;
			}
	if(fieldTrue == true){
		loadData(fromDate,toDate);
	}
		});

		function loadData(fromDate,toDate) {
			var dataUrl = "<?=base_url()?>index.php/Home/getSearchEmpSelfDeclarationData";
			$.ajax({
				type: 'POST',
				url: dataUrl,
				contentType: "text/plain",
				data :JSON.stringify({fromDate:fromDate,toDate:toDate}),
				dataType: 'json',
				success: function (data) {
					myJsonData = data.empSelfData;
					populateDataTable(myJsonData);
				},
				error: function (e) {
					console.log("Error with your request...");
					console.log("error: " + JSON.stringify(e));
				}
			});
		}
		function customFormatDate(date) {
			var d = new Date(date),
					month = '' + (d.getMonth() + 1),
					day = '' + d.getDate(),
					year = d.getFullYear();
			if (month.length < 2)
				month = '0' + month;
			if (day.length < 2)
				day = '0' + day;
			return [year, month, day].join('-');
		}
		function populateDataTable(data) {
			$("#empSelefDataTable").DataTable().clear();
			var length = data.length;
			if (length >0) {
			for (var i = 0; i < length; i++) {
				var userData = data[i];
				var for_date = new Date(userData.for_date);
				var forDate = customFormatDate(for_date);

				var other_category ='';
				if(userData.other_category == null){
					other_category = "No";
				}
				else{
					other_category = userData.other_category ;
				}
				var travel = '';
				if(userData.travel == 1){
					travel = "Yes";
				}
				else{
					travel = 'No' ;
				}
				var symptoms_met =';'
				if(userData.symptoms_met == 1){
					symptoms_met = "Yes";
				}
				else{
					symptoms_met = 'No' ;
				}
				var wheel_chair = '';
				if(userData.wheel_chair == 1){
					wheel_chair = "Yes";
				}
				else{
					wheel_chair = 'No' ;
				}
				var travel_yes= '';
				if(userData.travel_yes !== "" && userData.travel_yes !== null )
				{
					travel_yes = userData.travel_yes;
				}
				else if(userData.travel_yes === null ){
					travel_yes = 'No';
				}
				else{
					travel_yes = 'No';
				}
				$('#empSelefDataTable').dataTable().fnAddData([
					i+1,
					userData.name,
					userData.mobile,
					userData.description.toUpperCase(),
					other_category,
					travel,
					travel_yes,
					userData.address,
					symptoms_met,
					wheel_chair,
					forDate,
					userData.created_at
				]);
			}
		}
			else{
				alert("No data available in table");
				$("#empSelefDataTable").DataTable().clear();
				$("#empSelefDataTable").DataTable().destroy();
			}
		}


</script>
