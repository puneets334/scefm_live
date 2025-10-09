<style>.pointer {cursor: pointer;}</style>
<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div id="msg">
                <?php
                if (isset($_SESSION['MSG']) && !empty($_SESSION['MSG'])) {
                    echo $_SESSION['MSG'];
                } unset($_SESSION['MSG']);
                ?></div>
        </div>
    </div>

    <div class="row">

        <div class="x_panel">

            <div class="x_title">
                    <h2><i class="fa fa-newspaper-o"></i>INDEX HOME</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">

                <?php

                //$attribute = array('class' => 'form-horizontal', 'name' => 'update_indx_edit', 'id' => 'update_indx_edit', 'autocomplete' => 'off');
               // echo form_open('supplements/Prefilled_index_controller/indexData_Edit/', $attribute);
                ?>

                <!--<div class="modal fade" id="modal-indx-edit">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">Enter INDEX No.</h4>
                            </div>
                            <div class="modal-body">
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control" placeholder="Enter Index NO." id="Indx_No_edit" name="Indx_No_edit">
                                    <input type="hidden" class="form-control"  id="doc_type" name="doc_type" value="EDITInsrt">
                                    <span class="input-group-btn">
                                        <input type="submit" class="btn btn-success" id="update_indx_edit" value="Submit">
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>modal-dialog-->
                <?php //echo form_close(); ?>

                <?php

                $attribute = array('class' => 'form-horizontal', 'name' => 'download_indx', 'id' => 'download_indx', 'target' =>'_blank','autocomplete' => 'off');
                //$attribute = { 'width' => '800', 'height' => '600', 'scrollbars' => 'yes', 'status' => 'yes', 'resizable' => 'yes' };
                echo form_open('supplements/Prefilled_index_controller/gen_IndexPrefilled_pdf/', $attribute);

                ?>

                <div class="modal fade" id="modal-indx-download">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">Enter INDEX No.</h4>
                            </div>
                            <div class="modal-body">
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control" placeholder="Enter Index NO." id="Indx_pdf_download" name="Indx_pdf_download">
                                    <!--<input type="hidden" class="form-control"  id="doc_type" name="doc_type" value="EDITInsrt">-->
                                    <!--<script>
                                        var pdf_download_indx= $('#Indx_pdf_download').val();
                                    </script>-->
                                    <span class="input-group-btn">
                                        <!--<button type="button"  class="btn btn-info btn-flat" onclick="put_indx_No()">Submit</button>-->
                                        <!--<button type="button"  class="btn btn-info btn-flat" >Submit</button>-->
                                        <input type="submit" class="btn btn-success" id="download_indx" value="Submit">

                                       <!-- <a href="<?php /*echo base_url('supplements/Prefilled_index_controller/indexData_Edit/'); */?>" target="_blank"> Submit</a>-->


                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- /.modal-dialog INDEX PDF DOWNLOAD-->
                <?php echo form_close(); ?>


                <!--start akg-->
                <div id="widgets-container" ng-init="widgets.recentDocuments.byOthers.ifVisible=true;" class="uk-child-width-1-1 uk-child-width-1-2@s uk-child-width-1-3@l ukchild-width-1-4@xl ukmargin-medium-top uk-grid-medium ukflex-between uk-grid" uk-grid="">
                    <div ng-show="widgets.recentDocuments.byOthers.ifVisible" class="uk-first-column">
                        <div class="uk-card-default uk-box-shadow-xlarge uk-border-rounded documents-widget" style="border-top:0.15rem dashed #ccc;">
                            <!--<div class="uk-flex-middle uk-grid-medium uk-grid uk-grid-stack pointer" style="text-decoration:none;padding:1.6rem 1.5rem 1.2rem 1rem;" uk-grid=""  class="tablink" onclick="openSearch('ShowCases', this, 'uk-label-primary')" id="defaultOpen">-->
                            <div class="uk-flex-middle uk-grid-medium uk-grid uk-grid-stack pointer" style="text-decoration:none;padding:1.6rem 1.5rem 1.2rem 1rem;" uk-grid=""  class="tablink" id="new_insrt">

                                <div>
                                    <span class="uk-label uk-label-primary sc-padding sc-padding-small-ends uk-text-bold uk-text-large"><span class="glyphicon glyphicon-hand-right"></span></span>
                                </div>
                                <div class="uk-first-column">
                                    <div>
                                        <!--<span class="uk-text-bold uk-text-primary uk-text-uppercase">Cases <span class="uk-text-small">(Soon to be listed)</span></span>-->
                                        <!--<span class="uk-text-bold uk-text-primary uk-text-uppercase">INDEX PDF Genrate</span>-->
                                        <?php $t='newInsrt'; ?>
                                        <a  href="<?= base_url('supplements/Prefilled_index_controller/indexDocHome?id='.$t); ?>"  class="btn btn-primary btn-block" type="button">INDEX PDF Genrate</a>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!--<div class="defects-widget-container">
                        <div class="uk-card-default uk-box-shadow-xlarge uk-border-rounded defects-widget" style="border-top:0.15rem dashed #ccc;">
                            <div class="uk-flex-middle uk-grid-medium uk-grid uk-grid-stack pointer" style="text-decoration:none;padding:1.6rem 1.5rem 1.2rem 1rem;" uk-grid="" >
                                <div>
                                    <span class="uk-label uk-label-danger sc-padding sc-padding-small-ends uk-text-bold uk-text-large"><span class="glyphicon glyphicon-hand-right"></span></span>
                                </div>
                                <div class="uk-first-column">
                                    <button class="btn btn-primary btn-block" onclick="pdf_indx_role()">INDEX DATA EDIT</button>
                                </div>
                            </div>
                        </div>
                    </div>-->
                    <div class="defects-widget-container">
                        <div class="uk-card-default uk-box-shadow-xlarge uk-border-rounded defects-widget" style="border-top:0.15rem dashed #ccc;">
                            <div class="uk-flex-middle uk-grid-medium uk-grid uk-grid-stack pointer" style="text-decoration:none;padding:1.6rem 1.5rem 1.2rem 1rem;" uk-grid="" >
                                <div>
                                    <span class="uk-label uk-label-danger sc-padding sc-padding-small-ends uk-text-bold uk-text-large"><span class="glyphicon glyphicon-hand-right"></span></span>
                                </div>
                                <div class="uk-first-column">
                                    <!--<span class="uk-text-bold uk-text-danger uk-text-uppercase">IA Index PDF</span>-->
                                    <?php $t='IaInsrt'; ?>
                                    <a  href="<?= base_url('supplements/Prefilled_index_controller/indexDocHome?id='.$t); ?>"  class="btn btn-primary btn-block" type="button">IA Index PDF</a>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="defects-widget-container">
                        <div class="uk-card-default uk-box-shadow-xlarge uk-border-rounded defects-widget" style="border-top:0.15rem dashed #ccc;">
                            <div class="uk-flex-middle uk-grid-medium uk-grid uk-grid-stack pointer" style="text-decoration:none;padding:1.6rem 1.5rem 1.2rem 1rem;" uk-grid="" >
                                <div>
                                    <span class="uk-label uk-label-danger sc-padding sc-padding-small-ends uk-text-bold uk-text-large"><span class="glyphicon glyphicon-hand-right"></span></span>
                                </div>
                                <div class="uk-first-column">
                                    <button class="btn btn-primary btn-block" onclick="pdf_indx_download()">Download INDEX PDF</button>
                                    <!--<a  href="<?/*= base_url('supplements/Prefilled_index_controller/indexDocHome?id='.$t); */?>"  class="btn btn-primary btn-block" type="button">INDEX DATA EDIT</a>-->
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
                <br/> <br/> <br/>
                <!--end akg-->
                <?php

                    $this->load->view('supplements/prefilled_index_list_view');

                ?>



            </div>
        </div>

        <!------------Table--------------------->


    </div>
</div>


<!-- Case Status modal-start-->
<link rel="stylesheet" href="<?= base_url() ?>assets/responsive_variant/templates/uikit_scutum_2/assets/css/main.min.css" />
<link type="text/css" rel="stylesheet" href="<?= base_url() ?>assets/responsive_variant/frameworks/uikit_3-4-1/css/uikit.min.css" />
<link rel="stylesheet" href="<?= base_url() ?>assets/responsive_variant/templates/uikit_scutum_2/assets/css/fonts/mdi_fonts.css" />
<link rel="stylesheet" href="<?= base_url() ?>assets/responsive_variant/templates/uikit_scutum_2/assets/css/materialdesignicons.min.css" />

<script src="<?= base_url() ?>assets/responsive_variant/frameworks/uikit_3-4-1/js/uikit.min.js"></script>
<script src="<?= base_url() ?>assets/responsive_variant/frameworks/uikit_3-4-1/js/uikit-icons.min.js"></script>
<!-- Case Status modal-end-->

<script src="<?= base_url() . 'assets' ?>/vendors/jquery/dist/jquery.min.js"></script>
<script src="<?= base_url() . 'assets' ?>/js/jquery.min.js"></script>
<script src="<?= base_url() . 'assets' ?>/js/jquery-ui.min.js"></script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-datepicker.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/css/jquery-ui.css">
<script src="<?= base_url() ?>assets/js/bootstrap-datepicker.js"></script>
<script src="<?= base_url() ?>assets/js/bootstrap-datepicker.min.js"></script>
<script src="<?= base_url() ?>assets/js/sha256.js"></script>
<script src="<?= base_url() ?>assets/js/daterangepicker/daterangepicker.min.js"></script>
<script src="<?= base_url() ?>assets/js/daterangepicker/moment.min.js"></script>
<script src="<?= base_url() ?>assets/js/daterangepicker/daterangepicker.js"></script>
<script src="<?= base_url() ?>assets/js/bootstrap-datepicker.js"></script>
<script src="<?= base_url() ?>assets/js/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>assets/css/daterangepicker/daterangepicker.css">

<script>
    /*function openSearch(cityName,elmnt,color) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablink");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].style.backgroundColor = "";
        }
        document.getElementById(cityName).style.display = "block";
        elmnt.style.backgroundColor = color;

    }
    // Get the element with id="defaultOpen" and click on it
    document.getElementById("defaultOpen").click();*/
</script>
<script>
    // $(document).ready(function () {
    function pdf_indx_role(){
       // alert("rounak mishra");

        $('#modal-indx-edit').modal('show');
        return;

    }//END of function pdf_indx_role()..

    function pdf_indx_download(){
        // alert("rounak mishra");

        $('#modal-indx-download').modal('show');
        return;

    }//END of function pdf_indx_role()..


</script>




<style>
    /*th{font-size: 13px;color: #000;}
    td{font-size: 13px;color: #000;}
    td .sci{font-size: 13px;color: #000;}
*/

</style>

