<?php
$segment = service('uri');        
if ($segment->getSegment(2) != 'view') {
    if (getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_MISC_DOCS) {
        render('miscellaneous_docs.misc_docs_breadcrumb');
    } elseif (getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_IA) {
        render('IA.ia_breadcrumb');        
    } elseif (getSessionData('efiling_details')['ref_m_efiled_type_id'] == E_FILING_TYPE_MENTIONING) {
        render('mentioning.mentioning_breadcrumb');
    }
}
?>
<div class="panel panel-default panel-body">
    <div id="result" class="text-center"></div>
    <h6 class="text-center fw-bold mb-2">Filing For</h6>
    <div class="col-12 mb-3">
        <div class="row">        
            <center><span class="text-danger">[ Select only those parties which are filing application / reply / document today. ]</span></center>
        </div>
    </div>
    <div class="panel panel-default">  
        <div class="panel-body">
            <?php
            $attribute = array('class' => 'form-horizontal', 'name' => 'add_filing_for_details', 'id' => 'add_filing_for_details', 'autocomplete' => 'off', 'enctype' => 'multipart/form-data');
            echo form_open('#', $attribute);
                if(!is_null(getSessionData('login')['department_id'])) {
                    ?>           
                    <div class="row">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Whether filed by Government? :</label>
                        <div class="col-lg-9 col-md-9 col-sm-12  col-xs-12">
                            <div class="form-group">
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <label class="switch">
                                        <input type="checkbox" name="is_govt_filing" id="is_govt_filing" <?php echo (!empty($appearing_for_details[0]['is_govt_filing']) && ($appearing_for_details[0]['is_govt_filing'] ==1)) ? 'checked' : ''  ?>>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>   
                <?php
                if($_SESSION['login']['ref_m_usertype_id'] == USER_CLERK){
                if(isset($clerk_aor_details) && !empty($clerk_aor_details) && $clerk_aor_details[0]['ref_department_id']==1){ ?>
                    <div class="row">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Whether filed by Government? :</label>
                        <div class="col-lg-9 col-md-9 col-sm-12  col-xs-12">
                            <div class="form-group">
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <label class="switch">
                                        <input type="checkbox" name="is_govt_filing" id="is_govt_filing" <?php echo (!empty($appearing_for_details[0]['is_govt_filing']) && ($appearing_for_details[0]['is_govt_filing'] ==1)) ? 'checked' : ''  ?>>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } } ?>     
                <div class="row mt-4 mb-4">
                    <label class="control-label col-md-1 col-sm-6 col-xs-1">Filing For<span style="color: red">*</span> :</label>
                    <div class="col-lg-9 col-md-9 col-sm-12  col-xs-12">
                        <div class="form-group">
                            <?php $lbl_appearing_for =isset($appearing_for_details[0]['partytype']) &&  $appearing_for_details[0]['partytype'] == 'P' ? 'Petitioner / Complainant' : 'Respondent / Accused'; ?>
                            <label class="radio-inline"><strong><?php echo $lbl_appearing_for; ?></strong></label>
                            <?php
                            if (isset($appearing_for_details[0]['partytype']) &&  $appearing_for_details[0]['partytype'] == 'P') {
                                $party_name_array = explode('##', $parties_details[0]['p_partyname']);
                                $party_sr_no_array = explode('##', $parties_details[0]['p_sr_no']);
                            } else{
                                $party_name_array = explode('##', $parties_details[0]['r_partyname']);
                                $party_sr_no_array = explode('##', $parties_details[0]['r_sr_no']);
                            }
                            // $parties_list = array_combine($party_sr_no_array, $party_name_array);
                            $keys = range(1, count($party_name_array));
                            $parties_list = array_combine($keys, $party_name_array);
                            if (($appearing_for_details[0]['partytype'] == 'P') || ($appearing_for_details[0]['partytype'] == 'R')) {
                                $saved_appearing_for = $appearing_for_details[0]['appearing_for'];
                                $saved_appearing_for = explode('$$', $saved_appearing_for);
                                $saved_filing_for = $appearing_for_details[0]['filing_for_parties'] ?? "";
                                $saved_filing_for = explode('$$', $saved_filing_for);
                            } else{
                                $saved_appearing_for = NULL;
                                $saved_filing_for = NULL;
                            }
                            ?>
                        </div>
                    </div>
                </div>       
                <div class="table-responsive">
                    <table id="onbehalf_table" class="table table-striped custom-table dataTable no-footer">
                        <thead>
                            <tr>
                                <th class="text-center">Party Name</th>
                                <th class="text-center">
                                    Select<span data-placement="bottom" class="on_hover" data-toggle="popover" data-content="All parties which are filing  motion / reply / document." data-original-title="" title="All parties which are filing  motion / reply / document.">
                                        <i class="fa fa-question-circle-o" ></i>
                                    </span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php                 
                            if ( is_array($saved_appearing_for) &&  is_array($parties_list) && count($parties_list) > 0) {
                                $i = 1;
                                foreach ($parties_list as $key => $value) {
                                    //$appearing = (in_array($key, $saved_appearing_for)) ? TRUE : FALSE;
                                    if (isset($saved_appearing_for) && !empty($saved_appearing_for)){
                                        $appearing = (in_array($key, $saved_appearing_for)) ? TRUE : FALSE;
                                    }else{
                                        $appearing=FALSE;
                                    }
                                    if (!$appearing) {
                                        continue;
                                    }
                                    $selected = (in_array($key, $saved_filing_for)) ? 'checked' : NULL;
                                    $saved_sr_no = array_search($key, $saved_filing_for);
                                    ?>
                                    <tr>
                                        <td data-key="Party Name">
                                            <input class="form-control cus-form-ctrl" name="party_name[]" type="text"  value="<?php echo_data($value); ?>" readonly="">
                                        </td>
                                        <td data-key="Select" class="text-center"><input class="checkBoxClass" type="checkbox" name="selected_party[]" value="<?php echo url_encryption($i . '$$$' . $key . '$$$' . $appearing_for_details[0]['partytype']); ?>" <?php   echo $selected; ?> ></td>
                                    </tr>
                                    <?php
                                    $i++;
                                }
                            }
                            ?>
                        </tbody>                 
                    </table>
                </div>   
                <div class="text-center mt-4">            
                    <a href="<?= base_url('appearing_for'); ?>" class="btn quick-btn gray-btn btnPrevious" type="button">PREVIOUS</a>
                    <?php if (isset($appearing_for_details[0]['filing_for_parties']) && !empty($appearing_for_details[0]['filing_for_parties'])) { ?>
                        <input type="submit" class="btn btn-success" id="save_efiling_for" name="submit" value="UPDATE">
                        <a href="<?= base_url('uploadDocuments'); ?>" class="btn quick-btn btnNext" type="button">NEXT</a>
                    <?php } else{ ?>
                        <input type="submit" class="quick-btn gray-btn" id="save_efiling_for" value="SAVE" name="submit"  >
                    <?php } ?>
                </div>        
            <?php echo form_close(); ?>
        </div>
    </div>
<script src="<?= base_url() . 'assets/newAdmin/' ?>js/jquery351.min.js"></script>
<script src="<?= base_url() . 'assets/newAdmin/' ?>js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url() . 'assets/newAdmin/' ?>js/general.js"></script>
<script src="<?= base_url() . 'assets/newAdmin/' ?>js/jquery-351.slim.min.js"></script>
<script src="<?= base_url() . 'assets' ?>/vendors/jquery/dist/jquery.min.js"></script>
<script src="<?= base_url() . 'assets' ?>/js/jquery.min.js"></script>
<script src="<?= base_url() . 'assets' ?>/js/jquery-ui.min.js"></script>
<script src="<?= base_url() ?>assets/js/bootstrap-datepicker.js"></script>
<script src="<?= base_url() ?>assets/js/bootstrap-datepicker.min.js"></script>
<script src="<?= base_url() ?>assets/js/sha256.js"></script>
<script src="<?= base_url() ?>assets/newAdmin/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url() . 'assets' ?>/js/select2.min.js"></script>
<script src="<?= base_url() . 'assets' ?>/js/select2-tab-fix.min.js"></script>
<script type="text/javascript" src="<?= base_url() . 'assets' ?>/js/jquery.validate.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#add_filing_for_details').on('submit', function () {            
            var selectedCases = [];
            $('#onbehalf_table input:checked').each(function() {
                selectedCases.push($(this).attr('value'));
            });
            if(selectedCases.length<=0) {
                alert("Please Select at least one party for whom you are filing.");
                return false;
            }         
            var form_data = $(this).serialize();
            $('#modal_loader').show();            
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('on_behalf_of/DefaultController/save_filing_for'); ?>",
                data: form_data,    
                success: function (data) {
                    $('#modal_loader').hide();
                    var resArr = data.split('@@@');
                    if (resArr[0] == 1) {
                        // $('#msg').show();
                        // $(".form-response").html("<p class='message success' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
						$('.msg').show();
						$("#result").html("<p class='message success' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "</p>");
                        location.reload();
                    } else if (resArr[0] == 3) {
                        // $('#msg').show();
                        // $(".form-response").html("<p class='message error' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
						$('.msg').show();
						$("#result").html("<p class='message error' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "</p>");
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
            return false;
        });
    });
</script>