<?php
$o_array=$_REQUEST['o_array'];
if(isset($_POST['bail_order']) && $_POST['bail_order'] == 'Y') {
    $add_class_hide = "d-none";
} else{
    $add_class_hide = "";
}
$o_array = !empty($o_array)?$o_array:[];

$_SESSION['loadOrders']=count($o_array);
if(count($o_array)>0) {
    ?>
    <div class="card col-md-12 mt-2 <?=$add_class_hide?> ">
        <div class="card-header bg-primary text-white font-weight-bolder"><sub>Calculated Fees</sub></div>
        <div class="card-body">
            <div class="table-sec">
                <table class="table table-bordered custom-table table-striped">
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>Document Details</th>
                            <th>Order/File Date</th>
                            <th>No. of Pages</th>
                            <th>No. of Copies</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        
                        $t_pages=0;$t_copies=0;$t_amt=0;   $service_charges = 0; $fee_in_stamp = 0; $total_red_wrappers = 0;
                        for($i=0;$i<count($o_array);$i++) {
                            $sporderdate = !empty($o_array[$i][0]) ? date('d-m-Y', strtotime($o_array[$i][0])) : '';
                            $sptotalpages = ($o_array[$i][1]) ? $o_array[$i][1] : 0;
                            $spjudgementorder = ($o_array[$i][2]) ? $o_array[$i][2] : '';
                            $ischargable = $o_array[$i][3];
                            $spjudgementordercode = $o_array[$i][4];
                            $sp_certi_number_of_docs=$o_array[$i][5];
                            $sp_certi_number_of_pages=$o_array[$i][6];
                            $sp_uncerti_number_of_docs=$o_array[$i][7];
                            $sp_uncerti_number_of_pages=isset($o_array[$i][8]) ? $o_array[$i][8] : null;
                            $s_no=$i+1;
                            ?>
                            <tr>
                                <td data-key="S.No."><?php echo $s_no; ?></td>
                                <td data-key="Document Details">
                                    <span class="cp_tspjudgementorder" id="tspjudgementorder_<?php echo $i; ?>"><?php echo $spjudgementorder; ?></span>
                                </td>
                                <td data-key="Order/File Date">
                                    <span class="cp_tsporderdate" id="tsporderdate_<?php echo $i; ?>"><?=$sporderdate?></span>
                                </td>
                                <td data-key="No. of Pages">
                                    <span id="tsptotalpages_<?php echo $i; ?>"><?php $t_pages=$t_pages+$sptotalpages; echo $sptotalpages; ?></span>
                                </td>
                                <td data-key="No. of Copies"><?php $t_copies=$t_copies+$_REQUEST['num_copy']; echo ($_REQUEST['num_copy'])?$_REQUEST['num_copy']:0; ?></td>
                                <td data-key="Amount">
                                    <?php
                                    $r_sql = eCopyingGetCasetoryById($_REQUEST['app_type']);
                                    $urgent_fee = $r_sql->urgent_fee;
                                    if(isset($_SESSION["session_filed"]) && $_SESSION["session_filed"] == 4) {
                                        $third_party_charges=5;
                                    } else{
                                        $third_party_charges=0;
                                    }
                                    $total = 0;                            
                                    if($ischargable == 'Free' && $_REQUEST['app_type'] == 5) {

                                    } else{
                                        if($sp_certi_number_of_docs > 0) {
                                            //for unavailable docs request fee calculation
                                            $total = (($r_sql->per_page * $sptotalpages) + ($r_sql->per_certification_fee * $sp_certi_number_of_docs)) * $_REQUEST['num_copy'];
                                            $service_charges += ($r_sql->per_page * $sptotalpages) * $_REQUEST['num_copy'];
                                            $fee_in_stamp += $r_sql->per_certification_fee * $_REQUEST['num_copy'] * $sp_certi_number_of_docs;
                                            if ($r_sql->per_certification_fee > 0) {
                                                $total_red_wrappers += $sp_certi_number_of_docs * $_REQUEST['num_copy'];
                                            }
                                        } else{
                                            $total = (($r_sql->per_page * $sptotalpages) + $r_sql->per_certification_fee) * $_REQUEST['num_copy'];
                                            $service_charges += ($r_sql->per_page * $sptotalpages) * $_REQUEST['num_copy'];
                                            $fee_in_stamp += $r_sql->per_certification_fee * $_REQUEST['num_copy'];
                                            if ($r_sql->per_certification_fee > 0) {
                                                $total_red_wrappers += $_REQUEST['num_copy'];
                                            }
                                        }
                                    }
                                    ?>
                                    <span id="ttotal_<?php echo $i; ?>"><?php $t_amt+=$total; echo $total; ?></span>
                                </td>
                            </tr>
                            <?php
                        }
                        $urgent_plus_third_party_charges = ($third_party_charges + $urgent_fee) * $_REQUEST['num_copy'];
                        $service_charges += $urgent_plus_third_party_charges;
                        $t_amt+=$urgent_plus_third_party_charges;
                        //if($_REQUEST[cop_mode] == 1 && strlen($_REQUEST[pincode]) == 6) {
                        if($_REQUEST['cop_mode'] == 1 && strlen($_REQUEST['pincode']) == 6) {
                            $total_pages = $t_pages * $_REQUEST['num_copy']; //1 A4 page = 80 gsm = 5 grams
                            $weight = copying_weight_calculator($total_pages, $total_red_wrappers);
                            
                            if ($weight > 0) {
                                $desitnation_pincode = $_REQUEST['pincode']; //destination pincode
                                //$postage_response = speed_post_tariff_calc_offline($total_pages,$desitnation_pincode);
                                $postage_response = speed_post_tariff_calc_online($weight, $desitnation_pincode);
                                $postage_data = json_decode($postage_response);
                                //  echo $postage_data['Validation Status'];
                                //var_dump($postage_data); <?= $postage_data->{'Base Tariff'}; + Tax Rs. <?= $postage_data->{'Service Tax'}; 
                                if ($postage_data->{'Validation Status'} == "Valid Input") {
                                    $postage_charges = ceil($postage_data->{'Base Tariff'} + $postage_data->{'Service Tax'});
                                    ?>
                                    <tr>
                                        <td colspan="5" data-speed_post_charges_mode="online">Speed Post Charges</td>
                                        <td><?= $postage_charges; ?></td>
                                    </tr>
                                    <?php
                                } else{
                                    ?>
                                    <tr>
                                        <td colspan="6"><?= $postage_data->{'Validation Status'}; ?></td>
                                    </tr>
                                    <?php
                                    exit();
                                }
                            } else{
                                ?>
                                <tr>
                                    <td colspan="6"><?= "Unable to Calculate Weight of pages"; ?></td>
                                </tr>
                                <?php
                                exit();
                            }
                        } else{
                            $postage_charges = 0;
                        }
                        if($urgent_plus_third_party_charges > 0) {
                            ?>
                            <tr>
                                <td colspan="5">Urgency fees</td>
                                <td><?= $urgent_plus_third_party_charges; ?></td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td colspan="3">Total</td>
                            <td data-key="No. of Pages"><?php echo $t_pages; ?></td>
                            <td data-key="No. of Copies"><?php echo $t_copies; ?></td>
                            <td data-key="Amount" class="amount_to_pay" data-postage ="<?= $postage_charges; ?>" data-service_charges="<?= $service_charges; ?>" data-fee_in_stamp="<?= $fee_in_stamp; ?>" data-amount-to-pay="<?= $t_amt + $postage_charges; ?>">
                                <?php 
                                if($postage_charges > 0) {
                                    echo $t_amt + $postage_charges;
                                    $_SESSION['session_total_amount_to_pay'] = $t_amt + $postage_charges;
                                } else{
                                    echo $t_amt;
                                    $_SESSION['session_total_amount_to_pay'] = $t_amt;
                                }
                                ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <input type="hidden" name="loadOrders" class="loadedOrders" id="loadedOrders" value="<?php echo $_SESSION['loadOrders'];?>">
<?php } ?>
