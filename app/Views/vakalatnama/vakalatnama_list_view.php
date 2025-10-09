
    <!------------vakalatnama list--------------------->

        <div class="x_panel">

            <div class="x_content">


                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                <tr class="success">
                                    <th>#</th>
                                    <th>Vakalatnama no</th>
                                    <th><?php if ($_SESSION['efiling_details']['database_type']=='I'){ echo 'Diary-Number';}else{ echo 'Registration no';}?></th>
                                    <th>Party Type</th>
                                    <!--<th>Created on</th>-->
                                    <th>Parties</th>
                                    <th>View PDF</th>
                                    <th>Action</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $i = 1; $party_type='';
                                foreach ($vakalatnama_list as $row) {
                                    if ($row['p_r_type']=='P'){ $party_type='Petitioner'; }else if ($row['p_r_type']=='R'){ $party_type='Respondent'; }
                                    ?>
                                    <tr>
                                        <td><?php echo_data($i++); ?></td>
                                        <td>VKT<?php echo $row['id']; ?></td>
                                        <td><?php if ($row['database_type']=='I'){ echo $row['registration_id'];}else{ echo $row['registration_id']; } ?></td>
                                        <td><?php echo $party_type; ?></td>
                                        <!--<td><?php /*if(!empty($row['created_on'])){ echo date('d-m-Y H:i:s', strtotime($row['created_on'])); }*/?></td>-->
                                        <td>
                                            <?php
                                                $objects = json_decode($row['parties']);
                                                $icon = 'fa-stop';
                                                $color='red';
                                                $index=0;
                                                $sign_count=0;
                                                $sign_request=0;
                                                $status="";
                                                foreach ($objects as $obj){
                                                    if($index!=0)
                                                        echo '<br/>';
                                                    if(is_null($obj->uuid) && empty($obj->signed)){
                                                        $icon = 'fa-stop';$color='red';$hoverText='Sign Request not sent';
                                                    }
                                                    else if(!is_null($obj->uuid) && empty($obj->signed)){
                                                        $icon = 'fa-pause';$color='#FFD128';$hoverText='Signing Pending';
                                                        $sign_request++;
                                                    }
                                                    else if($obj->signed==true){
                                                        $icon = 'fa-check-square-o';$color='green';$hoverText='Signing Done';
                                                        $sign_count++;
                                                    }
                                                    
                                                    echo $obj->party_name.'&nbsp;&nbsp;&nbsp;<span title="'.$hoverText.'"><i class="fa '.$icon.'" style="font-size:18px;color:'.$color.'"></i></span>';
                                                    $index++;
                                                }
                                            ?>
                                        </td>
                                        <td class="efiling_search"><a href="<?php echo base_url('vakalatnama/Esign_vakalat/get_doc/').url_encryption($row['registration_id'].'#'.$row['id'].'#'.$row['p_r_type']); ?>" target="_blank"><i class="fa fa-file-pdf-o" style="font-size:18px;color:red"></i></a></td>
                                        <td class="efiling_search">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <?php if($sign_count==0 && $sign_request==0){ ?>
                                                <a title="Edit Vakalatnama" href="<?php echo base_url('vakalatnama/dashboard/action/').url_encryption($row['registration_id'].'#'.$row['id'].'#'.$row['p_r_type']); ?>"><i class="fa fa-edit" style="font-size:18px;color:blue"></i> </a>
                                                <button title="Send Sign Request" onclick="sign_request()"><i class="fa fa-clipboard" style="font-size:18px;color:green;"></i></button>
                                            <?php } if(($index==$sign_count) && $row['advocate_sign']=='f'){?>
                                            <a target="_blank" title="Advocate Signature" href="<?php echo base_url('vakalatnama/Esign_vakalat/advocate_esign_request/').url_encryption($row['registration_id'].'#'.$row['id'].'#'.$row['p_r_type']); ?>"><i class="fa fa-clipboard" style="font-size:18px;color:#0e5fff"></i> </a>
                                            <?php }
                                            else if(($index==$sign_count) && $row['advocate_sign']=='t'){
                                                $status="Complete";
                                            }?>
                                            <button title="Delete Vakalatnama" onclick="vakalatnama_delete('<?=url_encryption($row['id']);?>')"><i class="fa fa-trash" style="font-size:18px;color:red;"></i></button>
                                        </td>
                                        <td><?php
                                            if($sign_request==0)
                                                $status = "No sign rqeuest sent";
                                            if($sign_request>0)
                                                $status = "Sign Request sent to party";
                                            if(($index==$sign_count) && $row['advocate_sign']=='f')
                                                $status="Advocate Signature Pending";
                                            if(($index==$sign_count) && $row['advocate_sign']=='t')
                                                $status = "Complete";
                                            echo $status;
                                            ?></td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!------------vakalatnama list--------------------->
    <script src="<?=base_url();?>assets/js/sweetalert.min.js"></script>
    <link rel="stylesheet" href="<?=base_url();?>assets/css/sweetalert.css">

    <script>
        function vakalatnama_delete(vakalatnama) {
            event.preventDefault();
            var vakalatnama =vakalatnama;
            //alert(vakalatnama);
            swal({
                    title: "Are you sure delete this record?",
                    text: "But you will still be able to retrieve this file.",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "green",
                    confirmButtonText: "Yes, Delete!",
                    cancelButtonText: "No, cancel please!",
                    closeOnConfirm: false,
                    closeOnCancel: false
                },
                function(isConfirm){
                    if (isConfirm) {         // submitting the form when user press yes
                        var CSRF_TOKEN = 'CSRF_TOKEN';
                        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                        $.ajax({
                            type: "POST",
                            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, vakalatnama: vakalatnama},
                            url: "<?php echo base_url('vakalatnama/DefaultController/vakalatnama_delete'); ?>",
                            success: function (data)
                            {
                                var resArr = data.split('@@@');
                                if (resArr[0] == 1) {

                                    swal("Deleted!", "Successfully is deleted", "error");
                                    location.reload();

                                }else{
                                    swal("Cancelled", "Your imaginary file is safe :)", "error");
                                }

                                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                                });
                            },
                            error: function () {
                                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                                });
                            }
                        });

                    } else {
                        swal("Cancelled", "Your imaginary file is safe :)", "error");
                    }
                });
        }
        
        function sign_request(){
            $.ajax({
                type: "GET",
                url: "<?php echo base_url('vakalatnama/Esign_vakalat/esign_request/').url_encryption($row['registration_id'].'#'.$row['id'].'#'.$row['p_r_type']);  ?>",
                success: function (data)
                {
                    location.reload();
                },
                error: function () {
                }
            });
        }
    </script>