<style>
    .collon{
        width:10px;
    }

</style>
<?php

if ($_SESSION['efiling_details']['ref_m_efiled_type_id'] != E_FILING_TYPE_CDE) {
    $lbl_efiling_num = 'e-Filing No.';

    if ($current_status == I_B_Defects_Cured_Stage || $current_status == Initial_Defects_Cured_Stage) {
        $lbl_efiling_dt = 'Re e-Filed Date/Time';
    } else {
        $lbl_efiling_dt = 'e-Filed Date/Time';
    }
} else {
    $lbl_efiling_num = 'CDE No.';
    if ($current_status == I_B_Defects_Cured_Stage || $current_status == Initial_Defects_Cured_Stage) {
        $lbl_efiling_dt = 'Re CDE Date/Time';
    } else {
        $lbl_efiling_dt = 'CDE Date/Time';
    }
}
//  var_dump($view_data); die;
?>
<div style="border:1px solid #000;">
    <h3  style="text-align: center"> Supreme Court Of India<br>
        Acknowledgement
    </h3>
    <hr>
    <table width="90%" cellspacing="5" cellpadding="0" border="0" align="left">
        <tr>
            <!--<td rowspan="8" width="20%" valign="center"><img src="./assets/images/ecourts-logo.png" ></td>-->
            <td rowspan="8" width="20%" valign="center"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEQAAABkCAYAAAA/v5aEAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAACtYSURBVHja1J0HeFTV9vbPzKQnkAAJIfQmRQEB6USKFEFAQFCKAgIWukqxi15RQLBQLqh0KaKACFIkIHKpkoDU0EuAkEB6b1P/v3cMflzLvffTIHieZ57MzDlzzt5rv+td79otJpfLZfzRIzs727h69aphMpl+dU7f6bzu37BhQ33WRVV5tT137lzbr776quH3339f/ejRox6JiYnu33h4eBg1atQwGjVqlPHwww8fbdWqVVRISMhWTkVt2LAhY/HixcaiRYuMoKAg41Ydpj9jkIiICGPo0KGGp6fnvxlF90xJSTHuv/9+4+OPPzZVrFixZkJCQo8vvviiKxW7Oy4uroSvr6+ratWqNs45S5QoYZjNZiM3N9dITk42Lly44BkfH2+x2+225s2bxz/xxBN727Vr91VOTs733t7e6V5eXnemQdatW2f07NnzN89NnDjRGDt2bJjFYhn0j3/842lat2q5cuUMKpffrFmz3OLFixsYyXH27FnvgoICT4fDIcM6K1SoYCtbtqyTz47MzEwLSArctGmThe9sU6ZM2dapU6dZ3P57ztn4vduQNxCpA4MZXPubqP2fDhnkj75kkF8eDRo0MPbv36/z4du2bfuuTJkyriZNmrh27dqVdv78+QQQkz5o0CB7sWLFXLiFc+3atQUlS5Z0PfbYY/Yffvgh5a677nLdc889rvHjx1ufe+657I0bN6ZdvHgx/q233soJDAx00QBpGOJt0BQSHR1t4HLGiRMnDO7lNk54eLgBkv5wnYrUIPfee69a3YNzj48ePfpSQECAa/bs2VmxHG+//XY2CHD17dvXnp6efrlp06YuKpzHtZvgCse4ceNSeP9N9erVXVQ+kfeHatas6apdu7brpZdeygUl12JiYhK6detmBwGuH3/88SuuqXGjLI888sjPZfgzBvEoKt9TQUCEhdYeQmtNo8CBR44cSTp+/Li5V69e5V999dWc7t2720CJBy39Q5cuXWTQCvzUERYW5rBarZTHVSwrK8to3br1FSqfKcLevn37mUqVKhnVqlWrqd9/+umnV+bNm1caIz7y9ddfl+jQocNI7nFK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" ></td>
            <td><?php echo $lbl_efiling_num; ?></td>
            <td class="collon">:</td>
            <td style="width:150px;"><?php echo htmlentities($view_data['efiling_no'], ENT_QUOTES) ?></td>
            <td style="width:100px;"><?php echo $lbl_efiling_dt; ?></td>
            <td class="collon">:</td>
            <td style="width:120px;"><?php echo htmlentities(date('d-m-Y h:i:s A', strtotime($submitted_on)), ENT_QUOTES) ?></td>
        </tr>
<tr>
    <td> Efiled</td> <td class="collon">:</td>
    <td ><?php echo htmlentities($view_data['efiling_type'], ENT_QUOTES); ?></td>
    <?php if ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_NEW_CASE) { ?>
    <td>Case Type</td>
    <td class="collon">:</td>
    <td ><?php echo htmlentities($view_data['casename'], ENT_QUOTES); ?></td>
    <?php }
    else { ?>
    <td>Filed In</td>
    <td class="collon">:</td>
    <td ><?php echo htmlentities($view_data['sc_case'], ENT_QUOTES); ?></td>
    <?php }?>
</tr>
        <tr>
            <td>Petitioner</td>
            <td class="collon">:</td>
            <td ><?php echo htmlentities($view_data['pet_name'], ENT_QUOTES); ?></td>
        </tr>

        <tr>
            <td>Respondent</td>
            <td class="collon">:</td>
            <td colspan="4"><?php echo htmlentities($view_data['res_name'], ENT_QUOTES) ?></td>
        </tr>

      <!--  <?php
/*        if ($view_data['ref_file_no'] != 'NA') {
            $ref_file_title = ($_SESSION['cnr_details']['efiling_case_reg_id']) ? 'efiling Ref No.' : 'CNR No.';
            */?>
            <tr>
                <td><?php /*echo $ref_file_title */?></td>
                <td class="collon">:</td>
                <td colspan="4"><?php /*echo htmlentities($view_data['ref_file_no'], ENT_QUOTES); */?></td>
            </tr>
        <?php /*} */?>
        <?php
/*        if ($_SESSION['efiling_details']['ref_m_efiled_type_id'] != E_FILING_TYPE_CDE) {
            if ($view_data['total_ia'] != 'NA') {
                */?>
                <tr>
                    <td>IA(s)</td>
                    <td class="collon">:</td>
                    <td colspan="4"><?php /*echo htmlentities($view_data['total_ia'], ENT_QUOTES); */?></td>
                </tr>
            --><?php /*}
        }
        */?>

        <tr>
<?php if ($_SESSION['login']['ref_m_usertype_id'] == USER_ADVOCATE) { ?>
                <td>Advocate</td>
                <td class="collon">:</td>
                <td><?php echo htmlentities($_SESSION['login']['first_name'] . ' ' . $_SESSION['login']['last_name'], ENT_QUOTES); ?>
                    (<?php echo htmlentities($_SESSION['login']['aor_code'], ENT_QUOTES); ?>) </td>
    <?php if ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_NEW_CASE) { ?>
                    <td>Matter Nature</td>
                    <td class="collon">:</td>
                    <td><?php echo ($view_data['urgent'] == 'Y') ? htmlentities('Urgent', ENT_QUOTES) : htmlentities('Ordinary', ENT_QUOTES); ?></td>
                    <?php
                }
            }
            ?>
<?php if ($_SESSION['login']['ref_m_usertype_id'] == USER_IN_PERSON) { ?>
                <td>Party In Person</td>
                <td class="collon">:</td>
                <td><?php echo htmlentities($_SESSION['login']['first_name'] . ' ' . $_SESSION['login']['last_name'], ENT_QUOTES); ?></td>
    <?php if ($_SESSION['efiling_details']['ref_m_efiled_type_id'] == E_FILING_TYPE_NEW_CASE) { ?>
                    <td>Matter Nature</td>
                    <td class="collon">:</td>
                    <td><?php echo ($view_data['urgent'] == 'Y') ? htmlentities('Urgent', ENT_QUOTES) : htmlentities('Ordinary', ENT_QUOTES); ?></td>
                    <?php
                }
            }
            ?>
        </tr>

<?php /*if ($allocated_to != ' ' && !empty($allocated_to)) { */?><!--
            <tr>
                <td>Efiling Admin</td>
                <td class="collon">:</td>
                <td><?php /*echo htmlentities($allocated_to, ENT_QUOTES) */?> </td>
    <?php /*if ($_SESSION['efiling_details']['efiling_for_type_id'] == E_FILING_FOR_HIGHCOURT) { */?>
                    <td>To Be Listed Before</td>
                    <td class="collon">:</td>
                    <td> <?php /*echo (!empty($view_data['bench_name'])) ? htmlentities($view_data['bench_name'] . ' Bench', ENT_QUOTES) : htmlentities('NA', ENT_QUOTES); */?></td>
            <?php /*} */?>
            </tr>
            --><?php
/*        }*/
        if ($_SESSION['efiling_details']['ref_m_efiled_type_id'] != E_FILING_TYPE_CDE) {
            ?>

            <tr>
                <td>Payment Details</td>
                <td class="collon">:</td>
                <td colspan="4"><?php echo $view_data['payment_details']; ?></td>
            </tr>
    <?php if ($view_data['count_number_of_fee_pay'] > 1) { ?>
                <tr>
                    <td>Total Fee Paid</td>
                    <td class="collon">:</td>
                    <td colspan="4"><strong><?php echo htmlentities('Rs. ' . $view_data['total_amount'], ENT_QUOTES); ?></strong></td>
                </tr>
            <?php }
        }
        ?>

    </table>  
    <br>
    <div style="text-align: right;"><strong>Generated Date: <?php echo htmlentities(date('d-m-Y h:i:s A'), ENT_QUOTES); ?></strong></div>

</div>
