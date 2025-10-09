<?php
$from_date = strtotime(date(ESIGN_SERVICES_DOWN_FROM));
$to_date = strtotime(date(ESIGN_SERVICES_DOWN_TO));
$current_date = strtotime(date('Y-m-d H:i:s'));
if ($current_date > $from_date && $current_date < $to_date) {
    $button_enbale = FALSE;
    $message = '<div class="text-danger text-center breadcrumb"><span class="blink">eSign By Aadhaar Sevices are down between <strong>' . htmlentities(date('d-m-Y H:i:s A', strtotime(ESIGN_SERVICES_DOWN_FROM)), ENT_QUOTES) . ' To ' . htmlentities(date('d-m-Y H:i:s A', strtotime(ESIGN_SERVICES_DOWN_TO)), ENT_QUOTES) . '</strong>.</span></div>';
} else {
    $button_enbale = TRUE;
}

?>
<?php echo $message; ?>
<?php if ($esigned_docs_details[0]->is_data_valid != 't') { ?>

    <div class="clearfix"></div>
    <div id="pet_aadhar_yes">
        <?php
        $attribute = array('class' => 'form-label-left', 'id' => 'adv_esign_cert', 'name' => 'adv_esign_cert', 'autocomplete' => 'off', 'enctype' => 'multipart/form-data', 'onkeypress' => "return event.keyCode != 13;");
        echo form_open('affirmation/esign_signature/esign_docs', $attribute);
        ?>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-4 col-xs-12 input-sm" for="document_signed"> Certificate  :</label>
            <div class="col-md-4 col-sm-4 col-xs-12">
                <a href="<?= base_url('affirmation/viewUnsignedCertificate')?>" target="_blank" onclick="return confirm('NOTE : eSign signature is properly visible in Adobe Acrobat Reader DC ( Version 11 or greater).');"> <i class="fa fa-file-pdf-o" style="font-size:20px;color:red"></i> View Certificate</a></div>
        </div>
        <div class="clearfix"></div>
        <div class="form-group">
            <label class="control-label col-md-3 col-sm-4 col-xs-12 input-sm" for="document_signed"> Signer :</label>
            <div class="col-md-4 col-sm-4 col-xs-12">
                <!--<label class="radio-inline"><input type="radio" name="signer_type" id="signer_type_P" value="P" onchange="signer_form()">Petitioner</label>
                <label class="radio-inline"><input type="radio" name="signer_type" id="signer_type_R" value="R" onchange="signer_form()">Respondent</label>-->
                <label class="radio-inline"><input type="radio" name="signer_type" id="signer_type_A" value="A" onchange="signer_form()">Advocate</label>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="form-group" id="form_advocate">
            <label class="control-label col-md-3 col-sm-3 col-xs-12 input-sm" > Advocate Name as on Aadhaar <span style="color: red">*</span> :</label>
            <div class="col-md-5 col-sm-6 col-xs-12">
                <div class="input-group">
                    <input name="adv_name_as_on_aadhar" id="adv_name_as_on_aadhar" onkeyup="this.value = this.value.replace(/[^a-zA-Z. ]/g, '')" class="form-control col-md-7 col-xs-12 input-sm button_id_get " maxlength="50"  placeholder="Advocate Name as on Aadhaar" type="text" value="<?=$_SESSION['login']['first_name'].' '.$_SESSION['login']['last_name']?>">
                    <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Name of Advocate as on Aadhaar." data-original-title="" title="">
                        <i class="fa fa-question-circle-o"></i>
                    </span>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12 ">
                <?php if ($button_enbale) { ?>
                    <button type="button" onclick="return onclick_eSign('advocate_id');" name="eSign" class="btn btn-primary">eSign</button>
                <?php } ?>
            </div>
        </div>


        <div class="form-group" id="form_petitioner">

            <table class="table table-bordered" id="dynamic_field">
                <tr>
                    <td>
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12" style="background-color:lavender;">Party/Litigant:</div>
                            <div class="col-md-6 col-sm-6 col-xs-12" style="background-color:lavenderblush;">Party mail ID:</div>
                        </div>
                    </td>
                    <td>
                        <div class="col-md-12 col-sm-12 col-xs-12" style="background-color:lavender;">Action</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select name="petitioners[]" id="petitioners" class="form-control input-sm" style="width: 100%">
                                    <option value="" title="Select">Select Petitioner</option>
                                </select>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="input-group">
                                    <input name="pet_email_id[]" id="pet_email_id"  class="form-control col-md-7 col-xs-12 input-sm button_id_get " maxlength="50" placeholder="Email Id of Petitioner" type="email">
                                    <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Email Address of Petitioner where signing request will be sent." data-original-title="" title="">
                        <i class="fa fa-question-circle-o"></i>
                    </span>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td><button type="button" name="add" id="add" class="btn btn-success">Add More</button></td>
                </tr>
            </table>

            <div class="col-md-3 col-sm-6 col-xs-12 ">
                <?php if ($button_enbale) { ?>
                <button type="submit"  name="eSign" class="btn btn-primary">Send eSign Request</button>
                <?php } ?>
            </div>
        </div>

        <div class="form-group" id="form_respondent">
            <!--<label class="control-label col-md-3 col-sm-3 col-xs-12 input-sm" > Party/Litigant: <span style="color: red">*</span> :</label>
            <div class="col-md-5 col-sm-6 col-xs-12">
                <div class="input-group">
                    <select name="respondents" id="respondents" class="form-control col-md-7 col-xs-12 input-sm filter_select_dropdown" style="width: 100%">
                        <option value="" title="Select">Select Respondent</option>
                    </select>
                </div>
            </div>
            <div class="clearfix"></div>
            <label class="control-label col-md-3 col-sm-3 col-xs-12 input-sm" > Party mail ID: <span style="color: red">*</span> :</label>
            <div class="col-md-5 col-sm-6 col-xs-12">
                <div class="input-group">
                    <input name="res_email_id" id="res_email_id"  class="form-control col-md-7 col-xs-12 input-sm button_id_get " maxlength="50" placeholder="Email Id of Respondent" type="email">
                    <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Email Address of Respondent where signing request will be sent." data-original-title="" title="">
                        <i class="fa fa-question-circle-o"></i>
                    </span>
                </div>
            </div>-->
            <table class="table table-bordered" id="dynamic_field_Res">
                <tr>
                    <td>
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12" style="background-color:lavender;">Party/Litigant:</div>
                            <div class="col-md-6 col-sm-6 col-xs-12" style="background-color:lavenderblush;">Party mail ID:</div>
                        </div>
                    </td>
                    <td>
                        <div class="col-md-12 col-sm-12 col-xs-12" style="background-color:lavender;">Action</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <select name="respondents[]" id="respondents" class="form-control input-sm" style="width: 100%">
                                    <option value="" title="Select">Select Respondent</option>
                                </select>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="input-group">
                                    <input name="res_email_id[]" id="res_email_id"  class="form-control col-md-7 col-xs-12 input-sm button_id_get " maxlength="50" placeholder="Email Id of Respondent" type="email">
                                    <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Email Address of Respondent where signing request will be sent." data-original-title="" title="">
                        <i class="fa fa-question-circle-o"></i>
                    </span>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td><button type="button" name="addRes" id="addRes" class="btn btn-success">Add More</button></td>
                </tr>
            </table>
            <div class="col-md-3 col-sm-6 col-xs-12 ">
                <?php if ($button_enbale) { ?>
                    <button type="submit" name="eSign" class="btn btn-primary">Send eSign Request</button>
                <?php } ?>
            </div>
        </div>
        
        
        <?php echo form_close(); ?>
        <div class="clearfix"></div> <br/><br/>
    </div>
<?php } ?>

<div class="clearfix"></div><br>

<script>
    var signer_list ='';
    $("#form_petitioner").hide();
    $("#form_respondent").hide();
    $("#form_advocate").hide();
    function signer_form() {
        var signerType = $("input[name='signer_type']:checked").val();
        if(signerType=='P'){
            $("#form_petitioner").show();
            $("#form_respondent").hide();
            $("#form_advocate").hide();
            $('#petitioners').val('');
            get_signers_list(signerType);
        }
        else if(signerType=='R'){
            $("#form_petitioner").hide();
            $("#form_respondent").show();
            $("#form_advocate").hide();
            $('#respondents').val('');
            get_signers_list(signerType);
        }
        else if(signerType=='A'){
            $("#form_petitioner").hide();
            $("#form_respondent").hide();
            $("#form_advocate").show();
        }
    }


    function get_signers_list(type)
    {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, type:type},
            url: "<?php echo base_url('affirmation/Esign_signature/get_signers'); ?>",
            success: function (data)
            {
                if(type=='P'){
                    signer_list = data;
                    $('#petitioners').html(data);
                }
                else if(type=='R'){
                    signer_list = data;
                    $('#respondents').html(data);
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
    }



    $(document).ready(function(){
        var i = 1;
        var j = 1;
        $('#add').click(function(){
            i++;
            $('#dynamic_field').append('<tr id="row'+i+'"><td><div class="row">\n' +
                '                            <div class="col-md-6 col-sm-6 col-xs-12">\n' +
                '                                <select name="petitioners[]" id="petitioners'+i+'" class="form-control input-sm" style="width: 100%">\n' +
                '                                    <option value="" title="Select">Select Petitioner</option>\n' +
                '                                </select>\n' +
                '                            </div>\n' +
                '                            <div class="col-md-6 col-sm-6 col-xs-12">\n' +
                '                                <div class="input-group">\n' +
                '                                    <input name="pet_email_id[]" id="pet_email_id"  class="form-control col-md-7 col-xs-12 input-sm button_id_get " maxlength="50" placeholder="Email Id of Petitioner" type="email">\n' +
                '                                    <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Email Address of Petitioner where signing request will be sent." data-original-title="" title="">\n' +
                '                        <i class="fa fa-question-circle-o"></i>\n' +
                '                    </span>\n' +
                '                                </div>\n' +
                '                            </div>\n' +
                '                        </div></td><td><button name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');
            console.log(signer_list);
            $('#petitioners'+i).html(signer_list);
        });

        $(document).on('click','.btn_remove', function(){
            var button_id = $(this).attr("id");
            $("#row"+button_id+"").remove();
        });

        $('#addRes').click(function(){
            j++;
            $('#dynamic_field_Res').append('<tr id="rowRes'+j+'"><td><div class="row">\n' +
                '                            <div class="col-md-6 col-sm-6 col-xs-12">\n' +
                '                                <select name="respondents[]" id="respondents'+j+'" class="form-control input-sm" style="width: 100%">\n' +
                '                                    <option value="" title="Select">Select Respondents</option>\n' +
                '                                </select>\n' +
                '                            </div>\n' +
                '                            <div class="col-md-6 col-sm-6 col-xs-12">\n' +
                '                                <div class="input-group">\n' +
                '                                    <input name="res_email_id[]" id="res_email_id"  class="form-control col-md-7 col-xs-12 input-sm button_id_get " maxlength="50" placeholder="Email Id of Respondent" type="email">\n' +
                '                                    <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Email Address of Respondent where signing request will be sent." data-original-title="" title="">\n' +
                '                        <i class="fa fa-question-circle-o"></i>\n' +
                '                    </span>\n' +
                '                                </div>\n' +
                '                            </div>\n' +
                '                        </div></td><td><button name="remove" id="'+j+'" class="btn btn-danger btn_remove">X</button></td></tr>');
            console.log(signer_list);
            $('#respondents'+j).html(signer_list);
        });

        $(document).on('click','.btn_remove', function(){
            var button_id = $(this).attr("id");
            $("#rowRes"+button_id+"").remove();
        });
        
    });
    

</script>
