<div class="right_col" role="main">
    <div id="page-wrapper">
        <div class="row">
            <div id="fade_loader"></div>
            <div id="modal_loader">
                <img id="loader_img" style="margin-top: 30px;margin-left: 30px;display:block;" src="<?php echo base_url(); ?>assets/images/loading-data.gif" />
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="form-response" id="msg"></div>
                <div class="x_panel">
                    <div style="text-align: center">
                        <img align="center" src="<?php echo base_url(); ?>assets/images/scilogo.png" /><br>
                        <span style="left: 387.812px; top: 178.029px; font-size: 20px; font-family: sans-serif; transform: scaleX(1.08129);">SUPREME COURT OF INDIA
                            <br>
                            <?php
                            if ($param[1] == 1)
                                echo 'ALL LISTED MATTERS';
                            elseif ($param[1] == 2)
                                echo 'ADVANCE LIST ';
                            else
                                echo 'ELIMINATION LIST ';
                            ?>
                            of Advocate ID : <?= $_SESSION['login']['adv_sci_bar_id'] ?>

                        </span>
                    </div>
                    <?php $this->load->view('mycause_list/cause_list_ribbon'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function openModal() {
        document.getElementById('modal_loader').style.display = 'block';
        document.getElementById('loader_img').style.display = 'block';
        document.getElementById('fade_loader').style.display = 'block';
    }
    function closeModal() {
        document.getElementById('modal_loader').style.display = 'none';
        document.getElementById('fade_loader').style.display = 'none';
    }

</script>


