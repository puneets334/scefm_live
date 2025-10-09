
<?php if ($esigned_docs_details[0]->is_data_valid == 't') { ?>
    <?php if ($esigned_docs_details[0]->signed_type == 1) { ?>

        <div class="row" style="text-align: center">
            <div class="col-xs-12">
                <h4>Advocate Certificate Submitted ! <span style="color:green" class="glyphicon glyphicon-ok"></span></h4> 
            </div>

            <div class="col-lg-11 col-md-11 col-sm-12 col-xs-12">
                <a href="<?= base_url('affirmation/docs/view_signed_certificate/' . htmlentities(url_encryption(trim($esigned_docs_details[0]->id)), ENT_QUOTES)) ?>" target="_blank" onclick="return confirm('NOTE : eSign signature is properly visible in Adobe Acrobat Reader DC ( Version 11 or greater).');"><i class="fa fa-file-pdf-o" style="font-size:20px;color:red"></i> View Digitally Signed Advocate Certificate</a>
                </br> 
            </div>
        </div>
        <div class="clearfix"></div>

    <?php }elseif ($esigned_docs_details[0]->signed_type == 2) { ?>
        <div>
            <div class="row" style="text-align: center" >
                <div class="col-xs-12">
                    <h4> Advocate Certificate submitted ! <span style="color:green" class="glyphicon glyphicon-ok"></span></h4> 
                </div>
                <div class="col-lg-11 col-md-11 col-sm-12 col-xs-12">
                    <a href="<?= base_url('affirmation/docs/view_signed_certificate/' . htmlentities(url_encryption(trim($esigned_docs_details[0]->id)), ENT_QUOTES)) ?>" target="_blank" onclick="return confirm('NOTE : eSign signature is properly visible in Adobe Acrobat Reader DC ( Version 11 or greater).');"><i class="fa fa-file-pdf-o" style="font-size:20px;color:red"></i> View eSigned Advocate Certificate</a>
                    <div class="clearfix"></div> <br/>   <button type="button" id="new_case_adv"  data-docid="<?php echo htmlentities(trim($esigned_docs_details[0]->id), ENT_QUOTES); ?>" class="btn btn-danger reset_affirmation">Reset</button>
                </div>
            </div>
            <div class="clearfix"></div> <br/><br/>
        </div>
    <?php }elseif ($esigned_docs_details[0]->signed_type == 3) { ?>
        <div class=" text-center">
            <h4> Document eVerified by Mobile OTP  <span class="glyphicon glyphicon-ok text-success"></span></h4> 
            <p> <b> On Date : </b> <?php echo date("d-m-Y H:i:s A", strtotime(htmlentities($esigned_docs_details[0]->register_updated_date_time, ENT_QUOTES))); ?></p>
            <p> <b> Using Mobile :</b> <?php echo str_repeat("*", strlen($esigned_docs_details[0]->otp_mobile_no) - 2) . substr($esigned_docs_details[0]->otp_mobile_no, -2); ?></p>
            <p> <b>By Advocate :</b> <?php echo htmlentities($esigned_docs_details[0]->name_as_on_aadhar, ENT_QUOTES); ?></p>
            <p> <b> <a class="efiling_search" href="<?= base_url('affirmation/docs/view_signed_certificate/' . htmlentities(url_encryption(trim($esigned_docs_details[0]->id)), ENT_QUOTES)) ?>" target="_blank">
                        <i class="fa fa-file-pdf-o danger"  style="color:red"></i> View  eVerification
                    </a> 
                </b>
            </p>
            <p> <button type="button" id="new_case_adv"  data-docid="<?php echo htmlentities(trim($esigned_docs_details[0]->id), ENT_QUOTES); ?>" class="btn btn-danger reset_affirmation">Reset</button>
            </p>
        </div>
    <?php } else{ ?>
        <div class="row" style="text-align: center" >
                <div class="col-xs-12">
                    <h4> Certificate not signed.</h4> 
                </div>                
            </div>
        <?php } ?>
<?php } ?>