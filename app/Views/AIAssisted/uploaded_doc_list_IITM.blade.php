<div class="panel panel-default">
    <div class="panel-body">        
        <div class="col-md-12 col-sm-12 col-xs-12">
            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                <thead>
                    <tr class="success">
                        <th>S.N.</th>
                        <th>Documents(s) ID </th>
                        <th>Uploaded Documents(s) </th>
                        <th>Uploaded On </th>
                        <th>Updated On </th>
                        <th>Pages</th>
                        <th>AI Assisted Case filing</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sr = 1;
                    // $filing_num_label='E-Filing no :DRAFT-';
                    $filing_num_label='DRAFT-'; $defect='';
                    foreach ($data['uploaded_docs_IITM'] as $doc) {
                        if ($doc['stage_id']==1 || empty($doc['stage_id'])){
                            $file_name = !empty($doc['file_name']) ? $doc['file_name'] : NULL;
                            $iitm_api_json = !empty($doc['iitm_api_json']) ? $doc['iitm_api_json'] : NULL;
                            if (!empty($doc['efiling_no'])){
                                $efiling_no = $doc['efiling_no'];
                                $efiling_no='<span class="btn btn-danger btn-sm"  style="color: #ffffff;">' . $filing_num_label . htmlentities(efile_preview($efiling_no), ENT_QUOTES) . '</strong></span> ';
                            } else{
                                $efiling_no = '<i class="fa fa-hand-o-right"></i> AI Assisted Case filing';
                            }
                            ?>
                            <tr>
                                <td><?= $sr++; ?></td>                    
                                <td><?= $doc['id']; ?></td>
                                <td>
                                    <a target="_blank" href="<?php echo base_url('AIAssisted/viewDocument/' . url_encryption($doc['id'])); ?>">
                                        <?php echo_data($doc['doc_title']); ?>
                                        <img src="<?= base_url('assets/images/pdf.png') ?>"> <br/>
                                        <?php echo_data($doc['doc_hashed_value']); ?>
                                    </a>
                                </td>
                                <td><?= date('d-m-Y H:i:s', strtotime($doc['uploaded_on'])) ?></td>
                                <td><?= !empty($doc['iitm_api_json_updated_on']) ? date('d-m-Y H:i:s', strtotime($doc['iitm_api_json_updated_on'])): ''; ?></td>
                                <td><?= htmlentities($doc['page_no'], ENT_QUOTES); ?></td>
                                <td>
                                    <?php
                                    if (!empty($doc['iitm_api_json'])) {
                                        if(str_contains($doc['iitm_api_json'],'Internal Server Error') || (str_contains($doc['iitm_api_json'],'Another request is already running') || str_contains($doc['iitm_api_json'],'Please try again later'))) {
                                            echo '<span class="text-danger">Please wait..meta-data extraction may take some time.</span>';
                                        } else{ ?>
                                            <a style="color:blue;font-size: initial; " href="javascript:void(0)" onclick="AIAssist_case_efling('<?php echo htmlentities(url_encryption(trim($doc['id']), ENT_QUOTES)); ?>')"> <?=$efiling_no;?> </a>
                                            <br/>
                                            <a style="color:green;font-size: initial; " target="_blank" href="<?php echo base_url('AIAssisted/CaseWithAI/json_decode/' . url_encryption($doc['id'])); ?>"><i class="fa fa-eye-slash"></i> Extracted Metadata JSON</a>
                                            <?php
                                        }
                                    } else{
                                        if(str_contains($doc['iitm_api_json'],'Internal Server Error') || (str_contains($doc['iitm_api_json'],'Another request is already running') || str_contains($doc['iitm_api_json'],'Please try again later'))) {
                                            echo '<span class="text-danger">Please wait..meta-data extraction may take some time</span>';
                                        } else{
                                            echo '<span class="text-success">Please wait..meta-data extraction may take some time</span>';
                                        }
                                    }
                                    if(!empty($doc['iitm_api_json_defect'])) {
                                        if(str_contains($doc['iitm_api_json_defect'],'Internal Server Error') || (str_contains($doc['iitm_api_json_defect'],'Another request is already running') || str_contains($doc['iitm_api_json_defect'],'Please try again later'))) {
                                            $defect = '';
                                        } else{
                                            $iitm_api_json_defect_decode = json_decode($doc['iitm_api_json_defect'], TRUE);
                                            if (!empty($iitm_api_json_defect_decode)) {
                                                $defect = count($iitm_api_json_defect_decode);
                                            }
                                        }
                                    }
                                    if (!empty($defect)) { ?>
                                        <br/><a style="color:red;font-size: initial; " target="_blank" href="<?php echo base_url('AIAssisted/CaseWithAI/defect_list/' . url_encryption($doc['id'])); ?>"><i class="fa fa-eye-slash"></i> AI Assisted defects(<?=$defect;?>)</a>
                                    <?php } ?>
                                </td>
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
<script>
    function AIAssist_case_efling(value) {
        // alert('AIAssist_case_efling ok='+value);
        // return false;
        if(value) {
            // alert('AIAssist_case_efling ok22='+value);
            $(".form-response").html("");
            $.ajax({
                type: "GET",
                url: "<?php echo base_url('AIAssisted/CaseWithAI/get_AIAssist_case_efling'); ?>",
                data: {form_submit_val: value},
                success: function (result) {
                    var resArr = result.split('@@@');
                    if (resArr[0] == 1) {
                        $('#msg').show();
                        $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                    } else if (resArr[0] == 2) {
                        $(".form-response").html("");
                        $('#msg').show();
                        var targetUrl =resArr[1]; "<?php echo base_url('case/crud'); ?>";
                        window.parent.location.href=targetUrl;
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