<div class="right_col" role="main">

    <div class="row"> 
        <script>
            // function openModal() {
            //     document.getElementById('modal_loader').style.display = 'block';
            //     document.getElementById('loader_img').style.display = 'block';
            //     document.getElementById('fade_loader').style.display = 'block';
            // }
            //
            // function closeModal() {
            //     document.getElementById('modal_loader').style.display = 'none';
            //     document.getElementById('fade_loader').style.display = 'none';
            // }

        </script>

        <div id="fade_loader"></div>
        <div id="modal_loader">
            <img id="loader_img" style="margin-top: 30px;margin-left: 30px;display:block;" src="<?php echo base_url(); ?>assets/images/loading-data.gif" />
        </div>

    </div>
    <div class="modal fade" id="bsModal3" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close close_cis" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="mySmallModalLabel"></h4>
                </div>
                <div class="modal-body formresponse">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default close_cis" >OK</button>

                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="form-response" id="msg" >
            <?php
            if (isset($_SESSION['MSG']) && !empty($_SESSION['MSG'])) {
                echo $_SESSION['MSG'];
            } unset($_SESSION['MSG']);
            ?></div>
        <div class="x_panel">
            <div class="x_title"> <h3><?= htmlentities($tabs_heading, ENT_QUOTES) ?> <span style="float:right;">  <a class="btn btn-info" type="button" onclick="window.history.back()" /> Back</a></span></h3> </div>
            <div class="x_content">
               <?php
             //  echo '<pre>'; print_r($basicDetails); exit;
               $petResName = !empty($basicDetails[0]['cause_title']) ? $basicDetails[0]['cause_title'] : NULL;
               if(isset($petResName) && !empty($petResName)){
                   $petResArr = explode('Vs.',$petResName);
                   $pet_name = !empty($petResArr[0]) ? $petResArr[0] : '';
                   $res_name = !empty($petResArr[1]) ? $petResArr[1] : '';
               }
               $casename = !empty($basicDetails[0]['casename']) ? $basicDetails[0]['casename'] : '';
               $sub_name1 =  !empty($basicDetails[0]['sub_name1']) ? $basicDetails[0]['sub_name1'] : '';
               ?>
                <div class="panel panel-default" data-select2-id="12">
                    <div class="panel-body">
                        <form>
                            <div class="row">
                                <h4 style="text-align: center;color: #31B0D5"> Case Details </h4>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="col-sm-4">
                                            <label class="control-label  col-sm-12 col-xs-12 input-sm">Cause Title Petitioner : <?php echo  $pet_name;?></label>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="control-label  col-sm-12 col-xs-12 input-sm">Cause Title Respondent  :<?php echo  $res_name;?></label>
                                        </div>
                                        <div class="col-sm-4">
                                            <label class="control-label col-sm-12 col-xs-12 input-sm">Case Type: <?php echo $casename;?> </label>
                                        </div>
                                      <div class="col-sm-4">
                                        <label class="control-label  col-sm-12 col-xs-12 input-sm">Main Category: <?php echo $sub_name1;?> </label>
                                      </div>

                                </div>
                            </div>
                            </form>
                    </div>
                </div>


            </div>
        </div>
    </div>

</div>
</div>

<!-- footer content -->
<script>
    function hideMessageDiv() {
        document.getElementById('msg').style.display = "none";
    }
    setTimeout("hideMessageDiv()", 5000);
</script>
<script src="<?= base_url() . 'assets' ?>/vendors/jquery/dist/jquery.min.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-datepicker.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/css/jquery-ui.css">
<script src="<?= base_url() ?>assets/js/bootstrap-datepicker.js"></script>
<script src="<?= base_url() ?>assets/js/bootstrap-datepicker.min.js"></script>
<script src="<?= base_url() ?>assets/js/sha256.js"></script>
<script src="<?= base_url() . 'assets' ?>/js/jquery.min.js"></script>
<script src="<?= base_url() . 'assets' ?>/js/jquery-ui.min.js"></script>

