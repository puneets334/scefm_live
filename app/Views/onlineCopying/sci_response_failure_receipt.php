<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SCI</title>
    <link rel="icon" href="<?= base_url() . 'assets/newDesign/' ?>images/logo.png" type="image/png" />
	<!-- <link rel="shortcut icon" href="<?= base_url() . 'assets/newAdmin/' ?>images/favicon.gif"> -->
	<link href="<?= base_url() . 'assets/newAdmin/' ?>css/bootstrap.min.css" rel="stylesheet">
	<link href="<?= base_url() . 'assets/newAdmin/' ?>css/font-awesome.min.css" rel="stylesheet">
	<link href="<?= base_url() . 'assets/newAdmin/' ?>css/animate.css" rel="stylesheet">
	<link href="<?= base_url() . 'assets/newAdmin/' ?>css/material.css" rel="stylesheet" />
	<link href="<?= base_url() . 'assets/newAdmin/' ?>css/style.css" rel="stylesheet">
	<link href="<?= base_url() . 'assets/newAdmin/' ?>css/responsive.css" rel="stylesheet">
	<link href="<?= base_url() . 'assets/newAdmin/' ?>css/black-theme.css" rel="stylesheet">
	<!-- <link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-datepicker.css"> -->
	<link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-datepicker.min.css">
	<link rel="stylesheet" href="<?= base_url() ?>assets/css/jquery-ui.css">
	<link href="<?= base_url() . 'assets' ?>/css/select2.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="<?= base_url() . 'assets/newAdmin/' ?>css/jquery.dataTables.min.css">
</head>

<body>
    <table cellspacing="0" cellpadding="0" style="width:100%;max-width: 800px; margin: 0 auto 20px auto;" id="idToPrint">
  <tr>
<td>
    <table cellspacing="0" cellpadding="0" style="width:100%;max-width: 800px; padding-bottom: 15px;margin: 0 auto 20px auto;">
        <tbody>
            <tr>
                <td style="text-align:left; width: 60%; padding-bottom: 40px;"><img style="width: 50px;" src="<?= base_url('assets/newDesign/images/logo.png'); ?>" style="width:100%;" />     
                </td>
                <td  style="text-align:right; padding-bottom: 40px;"><div style="font-family:'Arial'; text-align: right;font-size: 17px; padding: 2px;">Supreme Court of India</div>
                    <div style="font-family:'Arial'; text-align: right;font-size: 16px; padding: 2px;"><img src="<?= base_url() ?>/assets/images/mobile.png" height="14" width="14">011-23388922-24, 23388942</div>
                    <div style="font-family:'Arial'; text-align: right;font-size: 16px; padding: 2px; color: #0887c3;"><img style="margin-top: 6px;" src="<?= base_url() ?>/assets/images/msg.png" height="14" width="14">supremecourt@nic.in</div>
                    <div style="font-family:'Arial'; text-align: right;font-size: 16px; padding: 2px;"><img src="<?= base_url() ?>/assets/images/pin.png" height="14" width="14"> Tilak Marg, New Delhi-110001</div></td>
            </tr>
            <tr>
                <td style="border-left:6px solid #0087c3; padding-left: 10px;"><div style="font-family:'Arial'; text-align: left;font-size: 16px; padding: 2px;">To:</div>
                    <div style="font-family:'Arial'; text-align: left;font-size: 20px; padding: 2px;"><?php echo (isset($ShippingFirstName) ? $ShippingFirstName : '') . ' ' . (isset($ShippingLastName) ? $ShippingLastName : '') ?></div>
                    <div style="font-family:'Arial'; text-align: left;font-size: 16px; padding: 2px;"><img src="<?= base_url() ?>/assets/images/mobile.png" height="14" width="14"><?php echo isset($ShippingMobileNumber) ? $ShippingMobileNumber : ''; ?></div>
                    <div style="font-family:'Arial'; text-align: left;font-size: 16px; padding: 2px; color: #0887c3;"><img src="<?= base_url() ?>/assets/images/msg.png" height="14" width="14"><a href="mailto:<?php echo isset($ShopperEmailAddress) ? $ShopperEmailAddress : ''; ?>"><?php echo isset($ShopperEmailAddress) ? $ShopperEmailAddress : ''; ?></a></div>
                    <div style="font-family:'Arial'; text-align: left;font-size: 16px; padding: 2px;"><img src="<?= base_url() ?>/assets/images/pin.png" height="14" width="14"><?php echo (isset($ShippingAddress1) ? $ShippingAddress1 : '') . ' ' . (isset($ShippingAddress2) ? $ShippingAddress2 : '') . ' ' . (isset($ShippingCity) ? $ShippingCity : '') . ' ' . (isset($ShippingStateRegion) ? $ShippingStateRegion : '') . ' ' . (isset($ShippingState) ? $ShippingState : '') . ' ' . (isset($ShippingPostalCode) ? $ShippingPostalCode : '') . ' ' . (isset($ShippingCountryCode) ? $ShippingCountryCode : '') ?></div>
                    </td>
                <td>
                    <div style="font-family:'Arial'; text-align: right;font-size: 24px; color: #0887c3; padding: 2px;"> Receipt</div>
                        <!-- <div style="font-family:'Arial'; text-align: right;font-size: 14px; padding: 2px;">CRN: <?php //echo isset($orderCode) ? $orderCode : '' ?></div> -->
                            <!--<div style="font-family:'Arial'; text-align: right;font-size: 14px; padding: 2px;">Application No.:<?php echo (!empty($application_no) ? $application_no : 0); ?></div>-->
                                <div style="font-family:'Arial'; text-align: right;font-size: 14px; padding: 2px;">Date of Receipt: <?php echo (!empty($orderDate) ? date('d-m-Y', strtotime($orderDate)) : '') ?></div>
                                    <div style="font-family:'Arial'; text-align: right;font-size: 14px; padding: 2px;">Payment Status: <?php echo (!empty($orderStatus) ? $orderStatus : '') ?></div>
                </td>
            </tr>
        </tbody>
    </table>

</td>
  </tr>
  <tr>
    <td>
        <table cellspacing="10px" cellpadding="10px"
        style="border-collapse: collapse;width: 100%;max-width: 800px;margin: 0 auto;">
            <tr>
                <th style="font-family:'Arial'; text-align: center; font-size: 18px; color: #fff; padding: 20px 10px; background: #ff4646; border-bottom:1px solid #fff">#</th>
                <th style="font-family:'Arial'; text-align: left; font-size: 18px; color: #000; padding: 20px 10px; background: #eeeeee; border-bottom:1px solid #fff">Description</th>
                <th style="font-family:'Arial'; text-align: center; font-size: 18px; color: #fff; padding: 20px 10px; background: #ff4646; border-bottom:1px solid #fff">Total</th>
            </tr>
            <tr>
                <td style="font-family:'Arial'; text-align: center; font-size: 18px; color: #fff; padding: 20px 10px; background: #ff4646; border-bottom:1px solid #fff;">1</td>
                <td style="font-family:'Arial'; text-align: left; font-size: 18px; color: #000; padding: 20px 10px; background: #eeeeee; border-bottom:1px solid #fff;">
                   <div style="font-family:'Arial'; text-align: left; font-size: 18px; color: #ff4646; padding: 0px 10px 8px 10px;">For Copy of Diary No. <?php echo (!empty($diary_no) ? $diary_no : '') ?></div>
                   <div style="font-family:'Arial'; text-align: left; font-size: 16px; color: #000; padding: 0px 10px;"><?php echo (!empty($required_document) ? $required_document : "") ?></div>
                </td>
                <td style="font-family:'Arial'; text-align: center; font-size: 18px; color: #00000; padding: 20px 10px; background: #ff4646; border-bottom:1px solid #fff;">Rs. <?php echo (!empty($OrderBatchTotalAmounts) ? $OrderBatchTotalAmounts : '') ?></td>
            </tr>
        </table>
      
    </td>
      </tr>
      <tr>
        <td style="padding-top:30px; padding-bottom: 30px;"> <div style="font-family:'Arial'; color: #555555; text-align: left;font-size:32px; padding: 2px;">Thank you! </div>
        </td>
          </tr>
          <tr>
            <td style="border-left:6px solid #0087c3; padding-left: 10px;"> <div style="font-family:'Arial'; color: #555555; text-align: left;font-size:20px; padding: 2px;"> NOTICE: </div>
               
                <div style="font-family:'Arial'; text-align: left;font-size: 14px; padding: 2px;">Fee once paid is not refundable or adjustable under any circumstances in future.</div>
            </td>
              </tr>


	</table>
	<link href="<?= base_url() . 'assets/newAdmin/' ?>css/bootstrap.min.css" rel="stylesheet">
	<center>
	<div class="col-12">
	<div class="row">
        <div class="col-4">&nbsp;</div>
	<div class="col-2">
		<a href="<?php echo base_url('redirect_on_login'); ?>" class="btn btn-secondary"><span class="mdi mdi-chevron-double-left"></span>Back</a>
	</div>
	<div class="col-2">
		<input type="button" onclick="print()" value="Print" class="btn btn-sm btn-primary">
	</div>
	</div>
	</div>
	</center>

    <!-- Page -1 End  -->
<script>
	function print(){
		// $("#idToPrint").print();
		var DocumentContainer = document.getElementById('idToPrint');
		var WindowObject = window.open();
		WindowObject.document.writeln(DocumentContainer.innerHTML);
		WindowObject.document.close();
		WindowObject.focus();
		WindowObject.print();
		WindowObject.close();
	}
	// var DocumentContainer = document.getElementById('idToPrint');
	// 	var WindowObject = window.open();
	// 	WindowObject.document.writeln(DocumentContainer.innerHTML);
	// 	WindowObject.document.close();
	// 	WindowObject.focus();
	// 	WindowObject.print();
	// 	WindowObject.close();
</script>

</body>

</html>


