<?php
$stages_array = array('', Draft_Stage, Initial_Defected_Stage,  E_REJECTED_STAGE);
if (!in_array(getSessionData('efiling_details')['stage_id'], $stages_array)) {
    $hidepencilbtn='true';
} else{
    $hidepencilbtn='false';
}
?>
<div class="panel panel-default">
    <div class="panel-body">        
        <div class="col-md-12 col-sm-12 col-xs-12">           
            <div class="table-responsive">
                <table id="datatable-responsive" class="table table-striped custom-table dataTable no-footer" cellspacing="0" width="100%">
                    <thead>
                        <tr class="success">
                            <th width="5%">#</th>
                            <th width="15%">Case Details</th>
                            <th width="15%">Cause Title</th>
                            <th width="15%">Decision Date</th>
                            <th width="15%">Order Challenged</th>
                            <th width="10%">Order Type</th>
                            <th width="10%">Caveat Details</th>
                            <th width="5%">Action</th> 
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        if(isset($subordinate_court_details) && !empty($subordinate_court_details)) {
                            foreach ((array)$subordinate_court_details as $exp) {
                                if($exp['court_type']==1) {
                                    $court_type='Court Type: High Court'.'<br>';
                                    $high_court_name='High Court Name: '.$exp['estab_name'].'<br>';
                                    $high_bench_name='Bench Name: '.$exp['bench_name'].'<br>';
                                    $court_type_details=$court_type.$high_court_name.$high_bench_name;
                                } elseif ($exp['court_type']==3) {
                                    $court_type='Court Type: District Court'.'<br>';
                                    $state_name='State Name: '.$exp['state_name'].'<br>';
                                    $district_name='Agency Name: '.$exp['estab_name'].'<br>';
                                    $court_type_details=$court_type.$state_name.$district_name;
                                } elseif ($exp['court_type']==4) {
                                    $court_type='Court Type: Supreme Court'.'<br>';
                                    $court_type_details=$court_type;
                                } elseif ($exp['court_type']==5) {
                                    $court_type='Court Type: Agency Court'.'<br>';
                                    $state_name='State Name: '.$exp['state_name'].'<br>';
                                    $agency_name='Agency Name: '.$exp['estab_name'].'<br>';
                                    $court_type_details=$court_type.$state_name.$agency_name;
                                }
                                $case_type = '<b>Case Type </b> : ' . $exp['casetype_name'] . '<br/> ';
                                $fil_no = '<b>Filing No.</b> : ' . (int) $exp['case_num'] . ' / ' . $exp['case_year'] . '<br/> ';
                                if (!empty($exp['cnr_num'])) {
                                    $case_details = '<b>CNR No.</b> : ' . cin_preview($exp['cnr_num']) . '<br/> ' . $case_type . $fil_no;
                                } else {
                                    $case_details = $case_type . $fil_no;
                                }
                                $causeTitle = '';
                                if(isset($exp['pet_name']) && !empty($exp['pet_name']) && isset($exp['res_name']) && !empty($exp['res_name'])) {
                                    $causeTitle = strtoupper($exp['pet_name'] . ' Vs. ' . $exp['res_name']);
                                } else  if(isset($exp['pet_name']) && !empty($exp['pet_name'])) {
                                    $causeTitle = strtoupper($exp['pet_name']);
                                } else {
                                    $causeTitle = '---';
                                }
                                ?>
                                <tr>
                                    <td data-key="#"><?php echo_data($i++); ?></td>
                                    <td data-key="Case Details">
                                        <?php
                                        echo '<b>'.$court_type_details.'</b>'; echo $case_details;
                                        $getData_earlierCourtArr=$exp['id'].'@@@'.$exp['registration_id'].'@@@'.$exp['court_type'].'@@@'.$exp['case_type_id'].'@@@'.$exp['casetype_name'];
                                        $earlierCourtArr=url_encryption($getData_earlierCourtArr);
                                        if($_SESSION['login']['userid']=='SC-ADMIN' && $exp['case_type_id']==0 || $exp['case_type_id'] ==null) {
                                            ?>
                                            <button class="efiling_search btn btn-success btn-sm" data-toggle="modal" href="#earlier_court_updateModal" data-court-id="<?php echo htmlspecialchars($earlierCourtArr, ENT_QUOTES, 'UTF-8'); ?>" onclick="getDataEarlierCourtUpdateModal(this.getAttribute('data-court-id'))">Update</button>
                                        <?php } ?>
                                    </td>
                                    <td data-key="Cause Title">
                                        <?php
                                        echo $causeTitle;
                                        if(!is_null($exp['fir_no']))
                                            echo '<br><b>FIR No. </b>'.$exp['fir_no'].'/'.$exp['fir_year'].' ('.$exp['complete_fir_no'].')';
                                        if(!is_null($exp['fir_no']))
                                            echo '<br><b>Police Station: </b>'.$exp['police_station_name'].' ('.$exp['district_name'].', '.$exp['state_name'].')';
                                        ?>
                                    </td>
                                    <!--<td><?php /*echo echo_data($exp['impugned_order_date']); */?></td>-->
                                    <td data-key="Decision Date"><?php echo echo_data(!empty($exp['impugned_order_date'])?date('d-m-Y',strtotime($exp['impugned_order_date'])):''); ?></td>
                                    <td data-key="Order Challenged"><?php if($exp['is_judgment_challenged']=='t'){ echo 'Yes';}else{ echo 'No';} ?></td>
                                    <td data-key="Order Type"><?php if($exp['judgment_type']=='F'){ echo 'Final';}else{ echo 'Interim';} ?></td>
                                    <td data-key="Caveat Details">
                                        <?php
                                        $ct_code=$exp['court_type'];
                                        $l_state=$exp['cmis_state_id'];
                                        $l_dist=$exp['ref_agency_code_id'];
                                        $lct_dec_dt=$exp['impugned_order_date'];
                                        $lct_caseno=$exp['case_num'];
                                        $lct_caseyear=$exp['case_year'];
                                        $json_return = file_get_contents(ICMIS_SERVICE_URL."/ConsumedData/search_caveat/?ct_code=$ct_code&l_state=$l_state&l_dist=$l_dist&lct_dec_dt='$lct_dec_dt'&lct_caseno=$lct_caseno&lct_caseyear=$lct_caseyear");
                                        $caveat_response = json_decode($json_return);
                                        if ($caveat_response) {
                                            $caveat_cases = $caveat_response->caveats;
                                            if (!empty($caveat_cases)) {
                                                $sr_no = 1;
                                                foreach ($caveat_cases as $cc) {
                                                    $caveatno = substr($cc->caveat_no, 0, strlen($cc->caveat_no) - 4) . '/' . substr($cc->caveat_no, -4);
                                                    echo $sr_no . ")Caveat No. " . $caveatno . '(' . $cc->caveat_status . ')<br>';
                                                    //echo $cc->agency_state;
                                                    echo $cc->agency_name . "<br>";
                                                    echo $cc->aor_details . "<br>";
                                                    $sr_no++;
                                                }
                                            } else{
                                                echo "Caveat not found";
                                            }
                                        } else{
                                            echo "Caveat not found.";
                                        }
                                        ?>
                                    </td>
                                    <?php if($hidepencilbtn!='true') { ?>
                                        <td data-key="Action" class="efiling_search">
                                            <a href="<?php echo base_url('newcase/Subordinate_court/DeleteSubordinateCourt/' . url_encryption($exp['id'])); ?>">Delete</a>
                                        </td>
                                    <?php } ?>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>  
        </div>
    </div>
</div>
<div class="modal fade" id="earlier_court_updateModal" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg" style="width: 90%;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                <h4 class="modal-title"><span class="fa fa-pencil"></span> Update Earlier Courts information</h4>
            </div>
            <div class="modal-body">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <input type="hidden" name="id_approval" id="id_approval" value="">
                    <input type="hidden" name="act_id" id="act_id" value="">
                    <div id="BindEarlierCourt"></div>
                </div>
                <div class="modal-footer"></div>
            </div>
        </div>
    </div>
</div>
<script>
        $(document).ready(function() {
            $("#datatable-responsive").DataTable({
                "ordering": false,
            });
        });
        function getDataEarlierCourtUpdateModal(earlierCourtArr) {
            var act_null='';
            $('#EarlierCourt').val(act_null);
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                type: 'POST',
                url: "<?php echo base_url('newcase/subordinate_court/update_subordinate_court_details'); ?>",
                data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, earlierCourtArr:earlierCourtArr},
                success: function (data) {
                        $('#earlier_court_updateModal').modal('show');
                        $('#BindEarlierCourt').html(data);
                    $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function (result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                },
                error: function () {
                    $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                }
            });
        }
    </script>