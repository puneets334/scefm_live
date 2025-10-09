<?php
if ($esigned_docs_details[0]->is_data_valid != 't') {
    // STARTS NEW CASEAFFIRMATION UPLOAD FORM  
    $attribute = array('class' => 'form-label-left', 'id' => 'adv_digital_sign', 'name' => 'adv_digital_sign', 'autocomplete' => 'off', 'enctype' => 'multipart/form-data');
    echo form_open('affirmation/digitalSign/upload_digitally_signed_doc', $attribute);
    ?>
    <div style="text-align: center">
        <div class="col-sm-12 row">
                <div class="form-group">
                    <label> Step 1 : <a href="<?= base_url('affirmation/viewUnsignedCertificate')?>"  target="_blank"><i class="fa fa-download blink"></i>  <i class="fa fa-file-pdf-o" style="font-size:20px;color:red"></i> Please Download Advocate Certificate.</a></label>
                </div>
        </div>
        <div class="col-sm-12 row">
                <label style="color: red;">Step 2 : Upload the same document as downloaded in Step 1</label>
        </div>
        <div class="col-sm-12 row" > <br>
                <div class="col-sm-4"> </div>
                <div class="form-group col-sm-2">
                    <input name="adv_oath_esign_pdf" id="adv_oath_esign_pdf" type="file" required>
                </div>
                <div class="form-group col-sm-2">
                        <input name="submit" value="Upload Certificate" class="btn btn-primary" type="submit">
                </div>
            <div class="col-sm-4"> </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-4 col-sm-4 col-xs-12"> </div>
        <div class="col-md-6 col-sm-6 col-xs-12">
        </div>
    </div><br><br>
    <div class="clearfix"></div>

    <?php echo form_close(); } ?>

   
<div class="clearfix"></div><br>
</div>
