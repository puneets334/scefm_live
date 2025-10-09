<?php
$request =array(
    "DepartmentCode" => DEPARTMENT_CODE_LIVE,
    "OrderBatchTransactions" => "$loop_for_batch",
    "OrderBatchMerchantBatchCode" => "$OrderBatchMerchantBatchCode",
    "OrderBatchTotalAmount" => number_format($_POST['amount_to_pay'],2,".",""), //number_format($_SESSION['session_total_amount_to_pay'], 2),
    "InstallationId" => INSTALLATION_ID_LIVE,
    "OrderCode" => "$OrderBatchMerchantBatchCode",
    "CartDescription" => "$CartDescriptionForServiceCharges",
    "OrderContent" => $orderContentId,
    "PaymentTypeId" => $PaymentTypeIdForServiceCharges,
    "PAOCode" => PAO_CODE,
    "DDOCode" => DDO_CODE,
    "MultiHeadArray" => $child_request,
    "PaymentMethodMode" => "Online",
    "ShopperEmailAddress" =>$_POST["applicant_email"],
    "ShippingFirstName" => $_POST["first_name"],
    "ShippingLastName" => $_POST["second_name"],
    "ShippingAddress1" => $_POST["address"],
    "ShippingAddress2" => "",
    "ShippingPostalCode" => $_POST["pincode"],
    "ShippingCity" => $_POST["city"],
    "ShippingStateRegion" => $_POST["district"],
    "ShippingState" => $_POST["state"],
    "ShippingCountryCode" => $_POST["country"],
    "ShippingMobileNumber" => $_POST["applicant_mobile"],
    "BillingFirstName" => "",
    "BillingLastName" => "",
    "BillingAddress1" => "",
    "BillingAddress2" => "",
    "BillingPostalCode" => "",
    "BillingCity" => "",
    "BillingStateRegion" => "",
    "BillingState" => "",
    "BillingCountryCode" => "",
    "BillingMobileNumber" => "",
    "clientIP" => "$clientIP",
    "serviceUserID" => $_POST['service_key']
);
//echo "<br>";
//var_dump($request);
$signedXML = bharatKoshRequest($request);
?>
Redirecting to Payment Gateway ...
<form id="myform" name="myform" target="_self" action="https://bharatkosh.gov.in/bkepay" method="post">
<input type="hidden" name="bharrkkosh" value="<?= $signedXML; ?>">
<input type="submit" name="name" value="CLICK" style="visibility:hidden;"/>
</form>
<script>
window.onload = function () {
document.forms['myform'].submit();
}
</script>