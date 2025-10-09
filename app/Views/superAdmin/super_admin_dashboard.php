<div class="right_col" role="main" style="min-height: 485px;">

    <div id="page-wrapper">
        <?php echo $this->session->flashdata('msg'); ?>

        <div id="msg">
            <?php
            // echo '<pre>'; print_r($_SESSION); exit(0);

            if (isset($_SESSION['MSG']) && !empty($_SESSION['MSG'])) { echo $_SESSION['MSG']; } unset($_SESSION['MSG']);
            ?>
        </div>
        <div class="row" >





        </div>
    </div>
</div>

<style>
    .yellow{
        color: #f0ad4e;
    }
    .orange{
        color:#FF7F50;
    }
    .dark_blue{
        color:#0040ff;
    }
    p:hover {
        background-color: #EDEDED !important;
    }.text_position{
        padding-left: 60%;
    }
</style>

