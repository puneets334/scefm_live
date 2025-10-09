<div class="panel panel-default">
    <h4 style="text-align: center;color: #31B0D5">Auxiliary Documents </h4>
    <h4 style="text-align: center;color: #31B0D5"><?php echo $_SESSION['efiling_details']['response_type'];?> </h4>
</div>
    <?php //echo '<pre>';print_r($_SESSION['efiling_details']);
        echo $this->session->flashdata('msg');
        echo $this->session->flashdata('msg_error');
    ?>


<div class="panel panel-default">
    <div class="panel-body">
        <div class="col-md-12 col-sm-12 col-xs-12">
                <table id="datatable-responsive" class="table table-striped dt-responsive nowrap" cellspacing="2" width="100%">

                    <tbody>
                    <?php
                    $i = 1;
                    //print_r($pdfs);exit();
                    foreach ($pdfs as $exp) {
                        
                        if($exp['radio_flag']==1){
                            $form_element = '
                                <input type="radio" id="add" name="facts" value="0" checked onchange="certificate(0)">
                                <label for="male">Additional Facts</label>
                                <input type="radio" id="no_add" name="facts" value="1" onchange="certificate(1)">
                                <label for="female">No Additional facts</label>';
                        }
                        else{
                            $form_element = '';
                        }
                     
                    ?>
                        <tr>

                            <td width="10%"><?php echo_data($i++); ?></td>
                            <td width="25%"><?php echo_data($exp['doc_name']); ?></td>
                            <?php
                                switch ($exp['type']){
                                    //case 'pdf':{ echo '<td><a target="_blank" href="'.$exp['action'].'"><span class="left badge bg-blue">Download</span></a></td>'; break;}
                                    case 'pdf':{ echo '<td><a target="_blank" href="'.$exp['action'].'"><span class="left badge bg-blue">Download</span></a>';

                                        if(in_array($exp['doc_name'], array('Advocate Checklist', 'Listing Proforma')))
                                            echo '<a href="'.$exp['doc_cancel'].'"><span class="left badge bg-red">Cancel</span></a></td>'; break;}
                                    case 'html':{echo '<td><a href="'.$exp['action'].'"><span class="left badge bg-green">Fill Form</span></a></td>'; break;}
                                    case 'sign':{echo '<td>';if(in_array($exp['doc_type'], array(DOC_TYPE_CHECKLIST, DOC_TYPE_PROFORMA))) echo '<a href="'.$exp['action'].'"><button class="left badge bg-orange">Edit</button></a>';  echo $form_element.'<a id="preview_'.$exp['radio_flag'].'" target="_blank" href="'.$exp['doc_preview'].'"><span class="left badge bg-maroon">Preview</span></a> <button class="left badge bg-red" onclick="pdf_sign(\''.$exp[doc_type].'\')">Sign</button></td></td>'; break;}
                                    case 'esign':{echo '<td>';if(in_array($exp['doc_type'], array(DOC_TYPE_AFFIDAVIT))) echo '<a href="'.$exp['action'].'"><button class="left badge bg-orange">Edit</button></a>';  echo '<a target="_blank" href="'.$exp['doc_preview'].'"><span class="left badge bg-maroon">Preview</span></a> <button class="left badge bg-green" onclick="get_email(\''.$exp[doc_type].'\')">eSign</button></td></td>'; break;}
                                    default:{echo '<td></td>'; break;}
                                }
                            ?>

                        </tr>

                    <?php  } ?>
                    </tbody>
                </table>
        </div>
    </div>
</div>



<div class="modal fade" id="modal-sign-otp">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Enter OTP</h4>
            </div>
            <div class="modal-body">
                <div class="input-group input-group-sm">
                    <input type="number" class="form-control" placeholder="Enter OTP" id="sign_otp">
                    <span class="input-group-btn">
                      <button type="button"  class="btn btn-info btn-flat" onclick="put_signature()">Submit</button>
                    </span>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<div class="modal fade" id="modal-esign-email">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Enter Email</h4>
            </div>
            <div class="modal-body">
                <div class="input-group input-group-sm">
                    <input type="email" class="form-control" placeholder="Enter Email" id="email_id">
                    <span class="input-group-btn">
                      <button type="button"  class="btn btn-info btn-flat" onclick="send_esign_request()">Submit</button>
                    </span>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
    var doc_type = '';
    function pdf_sign(doc){
        doc_type = doc;
        $('#modal-sign-otp').modal('show');
        //send sms for otp
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: 'POST',
            url: "<?php echo base_url('supplements/DefaultController/send_otp/'); ?>",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, doc_type:doc_type},
            success: function (data) {
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
    

    function put_signature() {
        $('#modal-sign-otp').modal('hide');
        var entered_otp = $('#sign_otp').val();
        $('#sign_otp').val('');
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: 'POST',
            url: "<?php echo base_url('supplements/DefaultController/verifyOtp/'); ?>",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, otp:entered_otp, doc_type:doc_type},
            success: function (data) {
                window.top.location.href = "<?=base_url('case/ancillary/documents')?>";
                $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            },
            error: function () {
                alert("Some error occurred! Please try again.");
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });

    }
    
    function get_email(doc) {
        doc_type = doc;
        $('#modal-esign-email').modal('show');
    }
    
    function send_esign_request() {
        $('#modal-esign-email').modal('hide');
        var entered_email = $('#email_id').val();
        $('#email_id').val('');
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $.ajax({
            type: 'POST',
            url: "<?php echo base_url('supplements/Esign_docs/esign_request/'); ?>",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, email_id:entered_email, doc_type:doc_type},
            success: function (data) {
                window.top.location.href = "<?=base_url('case/ancillary/documents')?>";
                $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            },
            error: function () {
                alert("Some error occurred! Please try again.");
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
    }
    
    function certificate(type) {
        document.getElementById("preview_1").href = "<?=base_url()?>"+"supplements/DefaultController/generate_slp_certificate_pdf/"+type;
    }

</script>