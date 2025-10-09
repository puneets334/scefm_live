<style>.pointer {cursor: pointer;}</style>
<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div id="msg" class="form-response"><?php echo $this->session->flashdata('msg');?></div>
        </div>
    </div>


    <!------------vakalatnama--------------------->
    <div class="row">

        <div class="x_panel">

            <div class="x_title">
                <h3 id="divTitle"><i class="fa fa-newspaper-o"></i>Search Vakalatnama Cases By <?php echo $_SESSION['response_type']; ?> <a href="<?php echo base_url('vakalatnama/dashboard/add'); ?>"><button class="btn btn-success pull-right">Reset</button></a> <a href="<?php echo base_url('vakalatnama/dashboard/vakalatnama_list'); ?>"><button class="btn btn-success pull-right">Back</button></a></h3>
                <div class="clearfix"></div>
            </div>
            <?php $p_checked=''; $r_checked='';$disabled='';
                  if(!empty($vakalat_party_p_r_type)){
                  $disabled='disabled';
                  if($vakalat_party_p_r_type=='P'){ $p_checked='checked'; } else if($vakalat_party_p_r_type=='R'){ $r_checked='checked'; }
              } ?>

            <center>  <br>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <?php
                        $attribute = array('class' => 'form-horizontal', 'id' => 'vakalatnama_party_form', 'name' => 'vakalatnama_party_form', 'autocomplete' => 'off');
                        echo form_open('vakalatnama/dashboard/add', $attribute);
                        ?>
                        <label class="radio-inline input-lg" style="text-align: center;color: #31B0D5">Party Type:</label>
                        <label class="radio-inline input-lg"><input type="radio" name="party_type" class="party_type" id="party_type_petitioner" <?=$disabled;?> value="P"   <?=$p_checked;?>>Petitioner</label>
                        <label class="radio-inline input-lg"><input type="radio" name="party_type" class="party_type" id="party_type_respondent" <?=$disabled;?> value="R"   <?=$r_checked;?>>Respondent</label>
                        <input type="submit"  name="vakalatnama_party_check" id="vakalatnama_party_check" value="SEARCH" class="btn btn-success hidden">
                        <?php echo form_close(); ?>
                    </div>
                </div>



            </center>
            <br><hr>


            <div class="x_content">
                <?php
                //echo '<pre>';print_r($_SESSION['login']);
                //echo '<pre>';print_r($_SESSION['efiling_details']);
                $readonly='';
                $active_advocate=$_SESSION['login']['first_name'].' '.$_SESSION['login']['last_name'];
                $active_advocate_emailid=$_SESSION['login']['emailid'];
                $active_advocate_id=$_SESSION['login']['id'];
                if (!empty($active_advocate) && $active_advocate !=null){
                    $readonly='readonly="readonly"';
                }
                ?>
                <?php
                $attribute = array('class' => 'form-horizontal', 'id' => 'search_case_vakalatnama', 'name' => 'search_case_vakalatnama', 'autocomplete' => 'off');
                echo form_open('vakalatnama/dashboard/add', $attribute);
                ?>
                <div class="row">

                    <div class="x_title text-center">
                        <h4>Cause [ <?php if (!empty($_SESSION['efiling_details']['ecase_cause_title'])){echo $_SESSION['efiling_details']['ecase_cause_title'];}else{ echo $_SESSION['efiling_details']['cause_title'];} ?> ]</h4>
                    </div>
                    <input type="hidden" class="form-control"  name="registration_id" id="registration_id" value="<?php echo url_encryption($vakalatnama[0]['registration_id']);?>" readonly required>

                    <input type="hidden" class="form-control"  name="p_r_type" id="p_r_type" value="<?php echo $p_r_type;?>" readonly>



                    <!-- start add more  Gellery Image -->
                    <div id="multi-field-wrapper">
                        <div id="multi-fields">

                            <div id="multi-field">

                                    <!--start first time added party-->

                                    <div class="row" id="refreshDiv-0">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="control-label col-sm-4" for="Parties">Parties<span style="color: red">*</span>:</label>
                                                <div class="col-sm-8">
                                                    <select name="party[]"  class="form-control input-sm filter_select_dropdown party akg" required>
                                                        <option value="">Select a party</option>
                                                        <?php
                                                        foreach ($parties as $row) {
                                                        if ($row->pet_res==$p_r_type){
                                                            echo '<option class="akg"  value="'.$row->id.'/'.$row->email.'/'.'party_email_id-0'.'/'.$row->partyname. '/'. $registration_id.'/'.$p_r_type.'/'.$row->sr_no_show.'">'. strtoupper($row->partyname).' ['.$p_r_type.'-'.$row->sr_no_show.']'.'</option>';
                                                        }
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-5">
                                            <div class="form-group">
                                                <label class="control-label col-sm-4" for="party_email_id">Email</label>
                                                <div class="col-sm-7">
                                                    <input type="hidden" name="party_add_form" value="120">
                                                    <input type="text" class="form-control" name="party_email_id[]" id="party_email_id-0" placeholder="Enter party email id.." required>
                                                </div>
                                                <div class="col-sm-1">
                                                    <i id="add-field"  class='fa fa-plus-circle text-success pointer' style='font-size:30px;margin-left: 49px;'></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <!--end first time added party-->




                            </div>
                            <br>
                        </div>
                    </div>

                    <!-- end Addmore Gallery Image -->





                </div>

                <br/>
                <div class="row">

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="pwd">Myself(Advocate) <span style="color: red">*</span>:</label>
                            <div class="col-sm-8">
                                <input type="hidden" class="form-control"  name="myself_adv_id" id="myself_adv_id" value="<?php echo url_encryption($active_advocate_id);?>" readonly required>
                                <input type="text" class="form-control"  name="myself_adv_name" id="myself_adv_name" value="<?php echo $active_advocate;?>" <?php echo $readonly;?> required>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-5" style="display: none;">
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="Parties">Additional.Advocate:</label>
                            <div class="col-sm-8">
                                <select name="adv_users_id[]" id="adv_users_id" class="form-control input-sm filter_select_dropdown" multiple="multiple">
                                    <option value="">Choose a advocate</option>
                                    <?php
                                    foreach ($users_types_list as $row) {

                                        if(in_array($row['id'],$aor_code_list)){ $sel = 'selected=selected';}else{$sel =''; }

                                        if ($row['id'] !=$_SESSION['login']['id']){
                                            if (!empty($row['last_name']) && $row['last_name'] !=null && $row['last_name'] !='NULL'){ $adv_name=$row['first_name'].' '.$row['last_name'];}else{$adv_name=$row['first_name'];}
                                            echo '<option '.$sel.'  value="'.$row['id'].'">' .strtoupper($adv_name).' ['. htmlentities(strtoupper($row['user_type']), ENT_QUOTES).']'.'</option>';
                                        }
                                    }

                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <br/><br/><br/>
                <div class="row">
                    <div class="col-sm-12 text-center">

                        <input type="submit"  name="vakalatnama_save" id="vakalatnama_add" value="SAVE" class="btn btn-success">

                    </div>
                </div>
                <?php echo form_close(); ?>

            </div>
        </div>


        <!------------vakalatnama--------------------->



    </div>
</div>

<script>
    $(document).ready(function () {
        <?php if(!empty($p_r_type)){ ?>
        <?php if($p_r_type=='P'){ ?>
        //alert('petitioner');
        $('#party_type_petitioner').trigger('click');
        <?php } else if($p_r_type=='R'){ ?>
        $('#party_type_respondent').trigger('click');
        <?php } ?>
        <?php } ?>
        $('.party_type').click(function () {

            var party_type = $(this).attr("value");

            if(party_type){
                $('#vakalatnama_party_check').trigger('click');
            }


        });
    });
</script>
<script>
    $(document).on('change','.akg',function(){

            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            var party = $(this).val();
            var type='insert';
            //alert('Change party='+party);
            $.ajax({
                type: "POST",
                data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, party: party,type:type},
                url: "<?php echo base_url('vakalatnama/DefaultController/is_party_party_email'); ?>",
                success: function (data)
                {
                    var resArr = data.split('@@@');
                    if (resArr[0] == 1) {
                        var party_email_id = resArr[2];
                        var targate = resArr[3];
                        //alert(targate);
                        $('#' + targate).val(party_email_id);
                        if (party_email_id) {
                            $('#' + targate).attr('readonly', true);
                        } else {
                            $('#' + targate).attr('readonly', false);
                        }
                    }else if (resArr[0] == 2) {
                        $('#msg').show();
                        $(".form-response").html("<p class='message invalid' id='msgdiv'>"+resArr[1]+"<span class='close' onclick=hideMessageDiv()>X</span></p>");

                        $("#refreshDiv-"+resArr[2]).load(location.href + " #refreshDiv-"+resArr[2]);
                        setTimeout(function () {

                            // location.reload();
                            //window.location.href = base_url + "vakalatnama/dashboard/action";
                        }, 3000);
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


    });
</script>


<script>
    /* ADD all_department */
    $('#multi-field-wrapper').each(function () {
        var $wrapper = $('#multi-fields', this);
        var x = 1;
        var akg = 1;

        $("#add-field", $(this)).click(function (e) {
            x++;
            $($wrapper).append(
               '<div id="multi-field"><div class="row" id="refreshDiv-'+ akg +'"><div class="col-sm-6"><div class="form-group"><label class="control-label col-sm-4" for="Parties">Parties<span style="color: red">*</span>:</label><div class="col-sm-8"><select name="party[]" id="party" class="form-control input-sm filter_select_dropdown party akg" required><option value="">Select a party</option><?php foreach ($parties as $row) { if ($row->pet_res==$p_r_type){ ?>  <option  value="<?= $row->id.'/'.$row->email.'/party_email_id-'?>'+ akg +'/<?=$row->partyname. '/'. $registration_id.'/'.$p_r_type.'/'.$row->sr_no_show;?>"><?=strtoupper($row->partyname).' ['.$p_r_type.'-'.$row->sr_no_show.']';?></option><?php } }?></select></div></div></div><div class="col-sm-5"><div class="form-group"><label class="control-label col-sm-4" for="party_email_id">Email</label><div class="col-sm-7"><input type="hidden" name="party_add_form" value="12'+ akg +'"><input type="text" class="form-control" name="party_email_id[]" id="party_email_id-'+ akg +'" placeholder="Enter party email id.." required></div></div></div><bottom id="remove_field"><i class="fa fa-minus-circle text-danger pointer" style="font-size:30px;"></i></bottom></div></div>');
            akg++;
        });

        $($wrapper).on("click", "#remove_field", function (e) { //user click on remove text
            e.preventDefault(); $(this).parent('div').remove(); x--;
        })
    });

</script>