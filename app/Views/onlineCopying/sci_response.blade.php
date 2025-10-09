
                        Redirecting to RTI Portal ...
                        <!--<form id="myform" name="myform" target="_self" action="http://10.40.186.15:90/applications/payment_response" method="post">-->
                           <!-- <form id="myform" name="myform" target="_self" action="http://10.40.186.15:94/rti/payment-response" method="post">-->
                            <form id="myform" name="myform" target="_self" action="<?php echo RTI_PAYMENT_RESPONSE; ?>" method="post">
                            <input type="hidden" name="application_ref_id" value="<?= $application_id ?>">
                            <input type="hidden" name="orderCode" value="<?= $orderCode ?>">
                            <input type="hidden" name="orderStatus" value="<?= $orderStatus ?>">
                            <input type="hidden" name="referenceID" value="<?= $referenceID ?>">
                            <input type="hidden" name="BankTransacstionDate" value="<?= $BankTransacstionDate ?>">
                            <input type="hidden" name="TotalAmount" value="<?= $response_amount ?>">
                            <input type="submit" name="name" value="CLICK" style="visibility: hidden;"/>
                        </form>
                        <script>
                            window.onload = function () {
                                document.forms['myform'].submit();
                            }
                        </script>
?>
