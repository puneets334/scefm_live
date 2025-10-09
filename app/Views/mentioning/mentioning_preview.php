<?php $this->load->view('mentioning/mentioning_breadcrumb'); ?>

<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="clearfix"></div>
    <div class="list-group-item1" style="background: #EDEDED; padding: 8px 6px 28px 10px; color: #555;" data-toggle="collapse" data-parent="#MainMenu">
        <div class="col-lg-4 col-md-4 col-sm-5 col-xs-6"> 
            <b>Case Details</b>
        </div>
        <div class="col-lg-5 col-md-5 col-sm-6 col-xs-5 text-right">
            <?php
            if ($_SESSION['login']['ref_m_usertype_id'] == USER_ADVOCATE || $_SESSION['login']['ref_m_usertype_id'] == USER_IN_PERSON) {
                //if ($_SESSION['efiling_details']['stage_id'] != Draft_Stage) {
                $allowed_users_array = array(Initial_Approaval_Pending_Stage,I_B_Defects_Cured_Stage,Initial_Defects_Cured_Stage);
                if (in_array($_SESSION['efiling_details']['stage_id'], $allowed_users_array)) {
                    ?>
                    <a class="btn btn-success btn-xs" target="_blank" href="<?php echo base_url('acknowledgement/view'); ?>"> 
                        <i class="fa fa-download blink"></i> eFiling Acknowledgement
                    </a>
                    <?php
                }
            }
            ?>
        </div>
        <div class="col-lg-2 col-md-2 visible-lg visible-md" style="width: 22%;">
            <button class="btn btn-primary btn-xs openall" style="float: right">Expand All <i class="fa fa-eye"></i></button>
            <button class="btn btn-info btn-xs closeall hidden" style="float: right">Collapse All <i class="fa fa-eye-slash"></i></button> 
        </div>
        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1" style="width: 3%;">        
            <a href="#demo_1" class="list-group-item1" style="background: #EDEDED;" data-toggle="collapse" data-parent="#MainMenu">
                <i class="fa fa-minus" style="float: right;"></i> <b> </a>
        </div>
    </div>
    <div class="collapse in" id="demo_1">
        <div class="x_panel">
            <?php $this->load->view('case_details/case_details_view', $data); ?>
        </div>
    </div>

    <a href="#demo_2" class="list-group-item" style="background: #EDEDED;" data-toggle="collapse" data-parent="#MainMenu"><i class="fa fa-plus" style="float: right;"></i> <b> Filing For</b></a>
    <div class="collapse" id="demo_2">
        <div class="x_panel">
            <div class="text-right"><a href="<?php echo base_url('on_behalf_of'); ?>"><i class="fa fa-pencil"></i></a></div>
            <?php $this->load->view('on_behalf_of/filing_for_parties_list_view'); ?>
        </div>
    </div>



    <a href="#demo_9" class="list-group-item" style="background: #EDEDED;" data-toggle="collapse" data-parent="#MainMenu"><i class="fa fa-plus" style="float: right;"></i> <b>Documents</b></a>
    <div class="collapse" id="demo_9"> 
        <div class="x_panel">
            <div class="text-right"><a href="<?php echo base_url('uploadDocuments'); ?>"><i class="fa fa-pencil"></i></a></div>
            <?php $this->load->view('uploadDocuments/uploaded_doc_list'); ?>
        </div>        
    </div>
    
    <a href="#demo_3" class="list-group-item" style="background: #EDEDED;" data-toggle="collapse" data-parent="#MainMenu"><i class="fa fa-plus" style="float: right;"></i> <b>Order on Request</b></a>
    <div class="collapse" id="demo_3"> 
        <div class="x_panel">            
            <?php echo $order_text[0]['order_text']; ?>
        </div>        
    </div>

    <a href="#demo_10" class="list-group-item" style="background: #EDEDED;" data-toggle="collapse" data-parent="#MainMenu"><i class="fa fa-plus" style="float: right;"></i> <b>Fees Paid</b></a>
    <div class="collapse" id="demo_10">
        <div class="x_panel">
            <div class="text-right"><a href="<?php echo base_url('mentioning/courtFee'); ?>"><i class="fa fa-pencil"></i></a></div>
            <?php $this->load->view('shcilPayment/payment_list_view'); ?>
        </div>
    </div>
    <a href="#demo_11" class="list-group-item" style="background: #EDEDED;" data-toggle="collapse" data-parent="#MainMenu">
        <i class="fa fa-plus" style="float: right;"></i> <b>Affirmation</b></a>
    <div class="collapse" id="demo_11">
        <div class="x_panel">
            <div class="text-right"><a href="<?php echo base_url('affirmation'); ?>"><i class="fa fa-pencil"></i></a></div>
                    <?php $this->load->view('affirmation/preview_certificate_view'); ?>
        </div>
    </div>
</div>
<?php $this->load->view('modals.php'); ?>
</div>
</div>
</div>
</div>
</div>
</div>
</div>


