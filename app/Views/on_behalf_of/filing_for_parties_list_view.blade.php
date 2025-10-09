<?php
$lbl_filing_for = $filing_for_details[0]['p_r_type'] == 'P' ? 'Petitioner / Complainant' : 'Respondent / Accused';
if ($filing_for_details[0]['p_r_type'] == 'P') {
    $party_name_array = explode('##', $filing_for_details[0]['p_partyname']);
    $party_sr_no_array = explode('##', $filing_for_details[0]['p_sr_no']);
} else{
    $party_name_array = explode('##', $filing_for_details[0]['r_partyname']);
    $party_sr_no_array = explode('##', $filing_for_details[0]['r_sr_no']);
}
// $parties_list = array_combine($party_sr_no_array, $party_name_array);
$keys = range(1, count($party_name_array));
$parties_list = array_combine($keys, $party_name_array);
$saved_filing_for = $filing_for_details[0]['filing_for_parties'];
$saved_filing_for = explode('$$', $saved_filing_for);
?>
<div class="panel panel-default"> 
    <div class="panel-body">
        <table id="courtFeeTable" class="table custom-table table-striped table-bordered dt-responsive nowrap second_tbl" cellspacing="0" width="100%">
            <thead>
                <tr class="success">
                    <th>#</th>
                    <th><?php echo $lbl_filing_for; ?></th>                                                            
                </tr>
            </thead>
            <tbody>
                <?php
                if (count($parties_list) > 0) {
                    $i = 1;
                    foreach ($parties_list as $key => $value) {
                        if((in_array($key, $saved_filing_for))) {                            
                            ?>
                            <tr>
                                <td data-key="#" style="width: 5%"><?php echo $i;?></td>
                                <td data-key="<?php echo $lbl_filing_for; ?>"><?php echo_data($value); ?></td>                                                                
                            </tr>
                            <?php
                            $i++;
                        }
                    }
                }
                ?>
            </tbody>            
        </table>
    </div>
</div>