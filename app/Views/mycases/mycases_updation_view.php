<div class="right_col" role="main">
    <div id="page-wrapper">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="form-response" id="msg" >
                    <?php
                    if (isset($_SESSION['MSG']) && !empty($_SESSION['MSG'])) {
                        echo $_SESSION['MSG'];
                    } unset($_SESSION['MSG']);
                    ?>
                    <?php
                    if (isset($_POST) && !empty($_POST)) {
                        $display = 'display: none;';
                    } else {
                        $display = 'display: none;';
                    }
                    ?>
                </div>

                <div class="x_panel">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <table width="100%" border="1" >
                            <tr>
                                <td  width="15%" rowspan="3" style="background-color:#dff0d8; color:#000;"><a href="<?php echo base_url('mycases/lists/total'); ?>"><center>Total Matters <br><b> (<?php echo echo_data($total); ?>)</b></center></b> </a></td>
                                <td  width=70%" colspan="6" style="background-color:#FACD22; color:#000;"><a href="<?php echo base_url('mycases/lists/pending'); ?>"><center>Pending Matters <br><b>(<?php echo echo_data($pending_matters); ?>)</b></center></a></td>
                                <td  width="15%"  rowspan="3" style="background-color:#FBD8DB; color:#000;"><a href="<?php echo base_url('mycases/lists/disposed'); ?>"><center>Disposed Matters<br><b>(<?php echo echo_data($disposed_matter); ?>)</b></center></a> </td>
                            </tr>
                            <tr>
                                <td colspan="3"  style="background-color: #46eeee; color:#000;"><a href="<?php echo base_url('mycases/lists/pending_reg'); ?>"><center>Registered <br><b> (<?php echo echo_data($pending_reg_matters); ?>)</b></center></a>
                                <td colspan="3" style="background-color: #46eeee; color:#000;"><a href="<?php echo base_url('mycases/lists/pending_unreg'); ?>"><center>Unregistered <br><b>(<?php echo echo_data($pending_un_reg_matters); ?>)</b></center></a></td>
                            </tr>
                            <tr style="background-color: #46eeee; color:#000 !important;"> 
                                <td><a href="<?php echo base_url('mycases/lists/pending_reg_pet'); ?>"> <center>  Petitioner <br><b>(<?php echo echo_data($pending_reg_pet_matters); ?>)</b></center></a></td>
                                <td><a href="<?php echo base_url('mycases/lists/pending_reg_res'); ?>">  <center>  Respondent <br><b>(<?php echo echo_data($pending_reg_res_matters); ?>)</b></center></a></td>
                                <td><a href="<?php echo base_url('mycases/lists/pending_reg_other'); ?>">   <center>  Other <br><b>(<?php echo echo_data($pending_reg_pet_res_matters); ?>)</b></center></a></td>
                                <td><a href="<?php echo base_url('mycases/lists/pending_unreg_pet'); ?>"> <center>   Petitioner <br><b>(<?php echo echo_data($pending_un_reg_pet_matters); ?>)</b></center></a></td>
                                <td><a href="<?php echo base_url('mycases/lists/pending_unreg_res'); ?>"> <center>  Respondent <br><b>(<?php echo echo_data($pending_un_reg_res_matters); ?>)</b></center></a></td>
                                <td><a href="<?php echo base_url('mycases/lists/pending_unreg_other'); ?>"> <center> Other <br><b>(0<?php echo echo_data($pending_un_reg_pet_res_matters); ?>)</b><br></center></a></td>

                            </tr>
                        </table>
                    </div>
                </div>


                <div class="x_panel">
                    <?php include 'matters_detail.php'; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    a {
        color: #000;
        text-decoration: none;
    }
</style>