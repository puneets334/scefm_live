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
                <h3 id="divTitle"><i class="fa fa-newspaper-o"></i>Search Vakalatnama Cases By <?php echo $_SESSION['response_type']; ?> <a href="<?php echo base_url('vakalatnama/dashboard/vakalatnama_list'); ?>"><button class="btn btn-success pull-right">Back</button></a></h3>
                <div class="clearfix"></div>
            </div>

            <?php $p_checked=''; $r_checked=''; if(!empty($p_r_type)){
                if($p_r_type=='P'){ $p_checked='checked'; } else if($p_r_type=='R'){ $r_checked='checked'; }
                } ?>

            <center>  <br>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <?php
                        $attribute = array('class' => 'form-horizontal', 'id' => 'vakalatnama_party_form', 'name' => 'vakalatnama_party_form', 'autocomplete' => 'off');
                        echo form_open('vakalatnama/dashboard/action/'.$param.'', $attribute);
                        ?>
                        <label class="radio-inline input-lg" style="text-align: center;color: #31B0D5">Party Type:</label>
                        <label class="radio-inline input-lg"><input type="radio" name="party_type" class="party_type" id="party_type_petitioner" value="P" disabled   <?=$p_checked;?>>Petitioner</label>
                        <label class="radio-inline input-lg"><input type="radio" name="party_type" class="party_type" id="party_type_respondent" value="R" disabled   <?=$r_checked;?>>Respondent</label>
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
                echo form_open('vakalatnama/dashboard/action/'.$param.'', $attribute);
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
                                <?php $p=0; if(!empty($vakalatnama_parties) && count($vakalatnama_parties) > 0){ ?>
                                    <?php foreach ($vakalatnama_parties as $party){ ?>
                                <div class="row" id="refreshDiv-<?=$p;?>">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="Parties">Parties<span style="color: red">*</span>:</label>
                                        <div class="col-sm-8">
                                            <select name="party_update[]"  class="form-control input-sm filter_select_dropdown party akg" required>
                                                <option value="">Select a party</option>
                                                <?php
                                                foreach ($parties as $row) {
                                                    if($party['party_id']==$row->id){ $sel = 'selected';}else{$sel =''; }
                                                    if ($row->pet_res==$p_r_type) {
                                                        $is_party=$party['registration_id'].'/'.$p_r_type.'/'.$row->sr_no_show. '/' . $party['id'];
                                                        echo '<option class="akg" ' . $sel . ' value="' . $row->id . '/' . $row->email . '/' . 'update_party_email_id-' . $p . '/'. $row->partyname . '/'. $is_party.'/'.$sel.'">'. strtoupper($row->partyname).' ['.$p_r_type.'-'.$row->sr_no_show.']'.'</option>';
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
                                            <input type="text" class="form-control" name="party_email_id_update[]" id="update_party_email_id-<?=$p;?>" value="<?php echo $party['party_email_id'];?>" placeholder="Enter party email id.." required>
                                        </div>
                                        <?php if ($p==0){?>
                                        <div class="col-sm-1">
                                           <!-- <i id="add-field"  class='fa fa-plus-circle text-success pointer' style='font-size:30px;margin-left: 49px;'></i>-->
                                        </div>
                                        <?php  }?>
                                    </div>
                                </div>
                                        <?php if ($p!=0){?>
                                    <bottom style="display: none;" id="remove_field" class="delete_party_row-<?=$p;?>"><i class="fa fa-minus-circle text-danger pointer" style="font-size:30px;"></i></bottom>
                                    <bottom  onclick="party_delete('<?=url_encryption($party['party_id'].'/delete_party_row-'.$p)?>')"><i class="fa fa-minus-circle text-danger pointer" style="font-size:30px;"></i></bottom>
                                        <?php  }?>
                                            </div>
                              <?php $p++; }?>

                              <?php  }?>





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
                            <input type="hidden" class="form-control"  name="vakalatnama_id" id="vakalatnama_id" value="<?php echo url_encryption($vakalatnama[0]['id']);?>" readonly>
                            <input type="submit"  name="vakalatnama_update" id="vakalatnama_update" value="UPDATE" class="btn btn-warning">

                    </div>
                </div>
                <?php echo form_close(); ?>

            </div>
        </div>




        <!------------vakalatnama--------------------->



    </div>
</div>
<script src="<?=base_url();?>assets/js/sweetalert.min.js"></script>
<link rel="stylesheet" href="<?=base_url();?>assets/css/sweetalert.css">
<script>
    function party_delete(party) {
        event.preventDefault(); // prevent form submit
        var party =party; //$(this).val();
        //alert('party_id='+party_id);
        var form = event.target.form; // storing the form
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
                if (isConfirm) {
                    //form.submit();          // submitting the form when user press yes
                    var CSRF_TOKEN = 'CSRF_TOKEN';
                    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                    //alert('Change party='+party);
                    $.ajax({
                        type: "POST",
                        data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, party: party},
                        url: "<?php echo base_url('vakalatnama/DefaultController/party_delete'); ?>",
                        success: function (data)
                        {
                            var resArr = data.split('@@@');
                            if (resArr[0] == 1) {
                                var party_id = resArr[1];
                                var targate = resArr[2];
                                //alert(targate);
                                //$('#' + targate).val(party_email_id);
                                if (party_id) {
                                    $('.' + targate).trigger('click');
                                    swal("Deleted!", "Successfully is deleted", "error");
                                } else {
                                    swal("Cancelled", "Your imaginary file is safe :)", "error");
                                }

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
</script>

<script>
    $(document).on('change','.akg',function(){

            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            var party = $(this).val();
            var type='update';
            //alert('Change party='+party);
            $.ajax({
                type: "POST",
                data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, party: party,type:type},
                url: "<?php echo base_url('vakalatnama/DefaultController/is_party_party_email'); ?>",
                success: function (data)
                {
                    var resArr = data.split('@@@');
                    if (resArr[0] == 1) {
                        //var party_email_id = 'party_email_id-0';
                       // var party_id = resArr[1];
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

                    //$('#party_email').show();
                    //$('#party_email_id').html(data);
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
                '<div id="multi-field"><div class="row" id="refreshDiv-'+ akg +'"><div class="col-sm-6"><div class="form-group"><label class="control-label col-sm-4" for="Parties">Parties<span style="color: red">*</span>:</label><div class="col-sm-8"><select name="party[]" id="party" class="form-control input-sm filter_select_dropdown party akg" required><option value="">Select a party</option><?php foreach ($parties as $row) { if ($row->pet_res==$p_r_type) {?>  <option  value="<?= $row->id.'/'.$row->email.'/party_email_id-'?>'+ akg +'/<?=$row->partyname. '/'. $registration_id.'/'.$p_r_type.'/'.$row->sr_no_show;?>"><?=strtoupper($row->partyname).' ['.$p_r_type.'-'.$row->sr_no_show.']';?></option><?php } }?></select></div></div></div><div class="col-sm-5"><div class="form-group"><label class="control-label col-sm-4" for="party_email_id">Email</label><div class="col-sm-7"><input type="hidden" name="party_add_form" value="12'+ akg +'"><input type="text" class="form-control" name="party_email_id[]" id="party_email_id-'+ akg +'" placeholder="Enter party email id.." required></div></div></div><bottom id="remove_field"><i class="fa fa-minus-circle text-danger pointer" style="font-size:30px;"></i></bottom></div></div>');
            akg++;
        });

        $($wrapper).on("click", "#remove_field", function (e) { //user click on remove text
            e.preventDefault(); $(this).parent('div').remove(); x--;
        })
    });

</script>