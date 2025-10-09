
    <!------------Dashboard vakalatnama--------------------->

        <div class="x_panel">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div id="msg"><?php echo $this->session->flashdata('msg');?></div>
                </div>
            </div>
            <div class="x_title">
                <h3><a href="<?php echo base_url('vakalatnama/dashboard/add'); ?>" ><button class="btn btn-warning pull-right">Add New Vakalatnama</button></a></h3><br/><br/>
                <h3><i class="fa fa-newspaper-o"></i>Search Vakalatnama Cases By <?php echo $_SESSION['response_type']; ?> <a href="<?php echo base_url('vakalatnama/DefaultController/search'); ?>"><button class="btn btn-success pull-right">Back</button></a></h3>
                <div class="clearfix"></div>
            </div>

        </div>
        <!------------Dashboard vakalatnama--------------------->



    <?php $this->load->view('vakalatnama/vakalatnama_list_view'); ?>

