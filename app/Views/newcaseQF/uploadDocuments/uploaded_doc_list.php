<div class="panel panel-default">
    <div class="panel-body">        
        <div class="col-md-12 col-sm-12 col-xs-12">
            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                <thead>
                    <tr class="success">
                        <th>#</th>
                        <th>Uploaded Documents(s)</th>
                        <th>Pages</th>
                        <?php if ($this->uri->segment(2)!= 'caseDetails' and ($_SESSION['login']['ref_m_usertype_id'] == USER_ADVOCATE and in_array($_SESSION['efiling_details']['stage_id'], array(Draft_Stage,Initial_Defected_Stage,I_B_Defected_Stage))) ) {?>
                        <th>Delete</th>
                        <?php }?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sr = 1;
                    $caveateDocNum = '';
                    foreach ($uploaded_docs as $doc) {
                        $file_name = !empty($doc['file_name']) ? $doc['file_name'] : NULL;
                        if(isset($file_name) && !empty($file_name)){
                            $fileNameArr = explode('_',$file_name);
                            if(isset($fileNameArr[0]) && !empty($fileNameArr[0])){
                                $caveaEfilingNo = $fileNameArr[0];
                                if (strpos($caveaEfilingNo, 'EKSCIN') !== false) {
                                    $caveateDocNum++;
                                }
                            }
                        }
                        ?>
                        <tr>
                            <td><?= $sr++; ?></td>                    
                            <td><a target="_blank" href="<?php echo base_url('uploadDocuments/viewDocument/' . url_encryption($doc['doc_id'])); ?>">
                                    <?php echo_data($doc['doc_title']); ?>
                                    <img src="<?= base_url('assets/images/pdf.png') ?>"> <br/>
                                    <?php echo_data($doc['doc_hashed_value']); ?></a>                                
                            </td>
                            <td><?= htmlentities($doc['page_no'], ENT_QUOTES); ?></td>
                            <?php if ($this->uri->segment(2)!= 'caseDetails' and ($_SESSION['login']['ref_m_usertype_id'] == USER_ADVOCATE and in_array($_SESSION['efiling_details']['stage_id'], array(Draft_Stage,Initial_Defected_Stage,I_B_Defected_Stage))) ) {?>
                            <td>         
                                <a href="javascript:void(0)" onclick="DocDeleteAction('<?php echo htmlentities(url_encryption(trim($doc['doc_id'] . '$$' . $_SESSION['efiling_details']['registration_id']), ENT_QUOTES)); ?>')"><i class="fa fa-trash"> Delete</i> </a>
                            </td>
                            <?php }?>
                        </tr>
                    <?php } ?>

                </tbody>
            </table>
            <input type="hidden" name="caveateDocNum" id="caveateDocNum" data-caveateDocNum="<?php echo $caveateDocNum;?>"/>
        </div>
    </div>
</div>
<script>
    function DocDeleteAction(value) {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        // var a = confirm("Are you sure that you really want to delete this record ?");
        var a = confirm("Once a document is deleted, index of that document will also be deleted. Do you really want to delete?");
        if (a == true)
        {
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('uploadDocuments/DefaultController/deletePDF'); ?>",
                data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, form_submit_val: value},
                success: function (result) {
                    var resArr = result.split('@@@');
                    if (resArr[0] == 1) {
                        $('#msg').show();
                        $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                    } else if (resArr[0] == 2) {
                        $(".form-response").html("<p class='message valid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                        $('#msg').show();
                        location.reload();
                    }
                    $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function (result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                },
                error: function (result) {
                    $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function (result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                }
            });
        }
    }
</script>
