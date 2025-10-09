<?php $this->load->view('jailPetition/new_case_breadcrumb'); ?>
<div class="x_content">
    <div class="col-md-12 col-sm-12 col-xs-12">

        <div style="float: right">
            <div class="col-lg-5 col-md-5 col-sm-6 col-xs-5 text-right">
                <?php
                if ($_SESSION['login']['ref_m_usertype_id'] == JAIL_SUPERINTENDENT) {
                    if ($_SESSION['efiling_details']['stage_id'] != Draft_Stage) {
                        ?>
                        <a class="btn btn-success btn-sm" target="_blank" href="<?php echo base_url('acknowledgement/view'); ?>"> 
                            <i class="fa fa-download blink"></i> eFiling Acknowledgement
                        </a>
                        <?php
                    }
                }
                ?>
            </div>
            <a title="Click Here To View All Information" href="javascript:void(0);" class="btn btn-outline btn-primary btn-sm openall" style="float: right">
                <span class="fa fa-eye"></span>&nbsp;&nbsp; View All
            </a>
            <a title="Click Here To Close All Information" href="javascript:void(0);" class="btn btn-outline btn-info btn-sm closeall" style="float: right; ">
                <span class="fa fa-eye-slash"></span> Close All
            </a>
        </div>

        <div class="x_panel" style="background: #EDEDED;">
            <a href="#demo_1" class="list-group-item" style="background: #EDEDED;" data-toggle="collapse" data-parent="#MainMenu" aria-expanded="true"><i class="fa fa-minus" style="float: right;"></i> <b>Case Details</b></a>
            <div class="collapse in" id="demo_1" aria-expanded="true">

                <div class="x_panel">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-md-4 text-right" for="filing_no">Cause Title : </label>
                                <div class="col-md-8">
                                    <p> <?php echo_data($new_case_details[0]['cause_title']) ?> </p>
                                </div>
                            </div> 
                        </div>   
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-md-4 text-right" for="filing_no">Case Type : </label>
                                <div class="col-md-8">
                                    <p> <?php echo_data(strtoupper($sc_case_type[0]->casename)) ?> </p>
                                </div>
                            </div> 
                        </div> 
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-sm-11">
            <a href="#demo_2" class="list-group-item" style="background: #EDEDED;" data-toggle="collapse" data-parent="#MainMenu"><i class="fa fa-plus" style="float: right;"></i><?php if(!isset($petitioner_details) || empty($petitioner_details)){?><font style="color:red;"> <b>Petitioner</b></font><?php } else{?> <b>Petitioner</b><?php } ?></a>
            <div class="collapse" id="demo_2">
                <?php $this->load->view('jailPetition/petitioner_list_view'); ?>
            </div>
                </div>
                <div class="col-sm-1" class="list-group-item" style="background: #EDEDED;" >
                    <a href="<?php echo base_url('jailPetition/BasicDetails'); ?>"><i style="color:black; padding-top:10px;" class="fa fa-pencil"></i></a>
                </div>
            </div>


            <div class="row">
                <div class="col-sm-11">
            <a href="#demo_3" class="list-group-item" style="background: #EDEDED;" data-toggle="collapse" data-parent="#MainMenu"><i class="fa fa-plus" style="float: right;"></i><?php if(!isset($respondent_details) || empty($respondent_details)){?><font style="color:red;"> <b>Respondent</b></font><?php } else{?> <b>Respondent</b><?php } ?></a>
            <div class="collapse" id="demo_3">
                <?php $this->load->view('jailPetition/respondent_list_view'); ?>
            </div>
                </div>
                <div class="col-sm-1" class="list-group-item" style="background: #EDEDED;" >
                    <a href="<?php echo base_url('jailPetition/BasicDetails'); ?>"><i style="color:black; padding-top:10px;" class="fa fa-pencil"></i></a>
                </div>
            </div>


            <div class="row">
                <div class="col-sm-11">
            <a href="#demo_16" class="list-group-item" style="background: #EDEDED;" data-toggle="collapse" data-parent="#MainMenu"><i class="fa fa-plus" style="float: right;"></i> <?php if(!isset($extra_parties_list) || empty($extra_parties_list)){?><font style="color:orange;"> <b>Extra Petitioner</b></font><?php } else{?><b>Extra Petitioner</b><?php } ?></a>
            <div class="collapse" id="demo_16">
                <?php $this->load->view('jailPetition/extra_petitioner_list_view'); ?>
            </div>
                </div>
                <div class="col-sm-1" class="list-group-item" style="background: #EDEDED;" >
                    <a href="<?php echo base_url('jailPetition/Extra_petitioner'); ?>"><i style="color:black; padding-top:10px;" class="fa fa-pencil"></i></a>
                </div>
            </div>


            <div class="row">
                <div class="col-sm-11">
            <a href="#demo_7" class="list-group-item" style="background: #EDEDED;" data-toggle="collapse" data-parent="#MainMenu"><i class="fa fa-plus" style="float: right;"></i><?php if(!isset($subordinate_court_details) || empty($subordinate_court_details)){?><font style="color:orange;"> <b>Earlier Courts</b></font><?php } else{?> <b>Earlier Courts</b><?php } ?></a>
            <div class="collapse" id="demo_7">
                <?php $this->load->view('jailPetition/subordinate_court_list'); ?>
            </div>
                </div>
                <div class="col-sm-1" class="list-group-item" style="background: #EDEDED;" >
                    <a href="<?php echo base_url('jailPetition/Subordinate_court'); ?>"><i style="color:black; padding-top:10px;" class="fa fa-pencil"></i></a>
                </div>
            </div>

<!--            <a href="#demo_8" class="list-group-item" style="background: #EDEDED;" data-toggle="collapse" data-parent="#MainMenu"><i class="fa fa-plus" style="float: right;"></i> <b>Upload Documents</b></a>
            <div class="collapse" id="demo_8">
            <?php //$this->load->view('newcase/petitioner_list_view');  ?>
            </div>-->
            <!--<div class="row">
                <div class="col-sm-11">
            <a href="#demo_9" class="list-group-item" style="background: #EDEDED;" data-toggle="collapse" data-parent="#MainMenu"><i class="fa fa-plus" style="float: right;"></i> <b>Index</b></a>
            <div class="collapse" id="demo_9">
                <div class="x_panel">
                    <div class="text-right"><a href="<?php /*echo base_url('documentIndex'); */?>"><i class="fa fa-pencil"></i></a></div>
                    <?php /*$this->load->view('documentIndex/preview_newcase_index_doc_list'); */?>
                </div>
            </div>
                </div>
                <div class="col-sm-1" class="list-group-item" style="background: #EDEDED;" >
                    <a href="<?php /*echo base_url('documentIndex'); */?>"><i style="color:black; padding-top:10px;" class="fa fa-pencil"></i></a>
                </div>
            </div>-->

            <div class="row">
                <div class="col-sm-11">
            <a href="#demo_11" class="list-group-item" style="background: #EDEDED;" data-toggle="collapse" data-parent="#MainMenu"><i class="fa fa-plus" style="float: right;"></i><?php if(!isset($esigned_docs_details) || empty($esigned_docs_details)){?><font style="color:red;"> <b>Affirmation</b></font><?php } else{?> <b>Certificate</b><?php } ?></a>
            <div class="collapse" id="demo_11">
                <div class="x_panel">
                    <div class="text-right"><a href="<?php echo base_url('affirmation'); ?>"><i class="fa fa-pencil"></i></a></div>
                            <?php $this->load->view('affirmation/preview_certificate_view'); ?>
                </div>
            </div>
                </div>
                <div class="col-sm-1" class="list-group-item" style="background: #EDEDED;" >
                    <a href="<?php echo base_url('affirmation'); ?>"><i style="color:black; padding-top:10px;" class="fa fa-pencil"></i></a>
                </div>
            </div>
        </div>
    </div>
    <?php $this->load->view('modals.php'); ?>
</div> </div> </div> </div> </div> </div> 
