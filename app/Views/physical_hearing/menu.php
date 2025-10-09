

<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header" style="padding-top: 0px; !important">
            <a class="navbar-brand" href="<?=base_url()?>index.php/Consent_VC"><?=APP_NAME_IN_HEADER?><br><span style="font-size: smaller"><?=APP_NAME_L2?></span></a>
        </div>
        

        <strong>
        <ul class="nav navbar-nav">
            <!--<li class="menu"><a href="<?/*=base_url()*/?>index.php/Consent">Choose Mode of Hearing</a></li>-->
            <li class="active"><a href="<?=base_url()?>index.php/Consent_VC">Consent for VC</a></li>
            <!--<li class="menu"><a href="<?/*=base_url()*/?>index.php/Appearance">Nominate Court Appearances</a></li>-->
          <!--  <li><a href="<?/*=base_url()*/?>index.php/Home/selfdeclarationform">Self Declaration</a></li>-->
         <!--   <li><a href="<?/*=base_url()*/?>index.php/Advocate_listing/advocate_rpt">Reports</a></li>-->
            <li><a href="<?=base_url()?>index.php/Advocate_listing/advocate_rpt_srch">Reports</a></li>
        </ul>
            <?php if(!empty($_SESSION['loginData']['name'])) { ?>
            <ul class="nav navbar-nav navbar-custom-menu" style="margin-left: 400px">
                <li><a href="#">Welcome : <strong><?=$_SESSION['loginData']['title'].' '.$_SESSION['loginData']['name']?></strong> </a></li>
            </ul>
            <?php } ?>
        <ul class="nav navbar-nav navbar-right">
            <li><a href="<?=base_url()?>index.php/Auth/logout"><span class="glyphicon glyphicon-off"></span> Logout</a></li>
            <!--<li><a href="#"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>-->
        </ul>
            </strong>
    </div>

</nav>
<script src="<?=base_url()?>assets/plugins/jQuery/jQuery.min.js"></script>

<script type="text/javascript">
    $(function(){
        $('.nav a').filter(function(){
            return this.href==location.href}).parent().addClass('active').siblings().removeClass('active');

        $('.nav a').click(function(){
            $(this).parent().addClass('active').siblings().removeClass('active')
        });
    });


</script>
