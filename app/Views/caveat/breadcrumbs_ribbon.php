<?php $breadCrumbsArray = explode(',', $_SESSION['efiling_details']['breadcrumb_status']); ?>
<ul class="nav-breadcrumb">
    <li> 
        <?php
        if ($this->uri->segment(1) == 'caveat' && $this->uri->segment(2) == NULL) {
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'first active';
        } elseif (in_array(CAVEAT_BREAD_CAVEATOR, $breadCrumbsArray)) {
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = '';
        } else {
            $ColorCode = 'background-color: #C11900;color:#ffffff;';
            $status_color = '';
        }
        ?>
        <a href="<?= $url_caveator ?>" class="<?php echo $status_color; ?>" style="z-index:8;"><span style="<?php echo $ColorCode; ?>">1</span> <?php echo ucfirst($case_type_pet_title); ?> </a>
    </li>

    <li>  

        <?php
        if ($this->uri->segment(2) == 'caveatee') {
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'first active';
        } elseif (in_array(CAVEAT_BREAD_CAVEATEE, $breadCrumbsArray)) {
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = '';
        } else {
            $ColorCode = 'background-color: #C11900;color:#ffffff;';
            $status_color = '';
        }
        ?>
        <a href="<?= $url_caveatee ?>" class="<?php echo $status_color; ?>" style="z-index:7;"><span style="<?php echo $ColorCode; ?>">2</span> <?php echo ucfirst($case_type_res_title); ?> </a>
    </li>

    <li> 
        <?php
        if ($this->uri->segment(2) == 'extra_party') {
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'first active';
        } elseif (in_array(CAVEAT_BREAD_EXTRA_PARTY, $breadCrumbsArray)) {
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = '';
        } else {
            $ColorCode = 'background-color: #f0ad4e;color:#ffffff;';
            $status_color = '';
        }
        ?>
        <!--<a href="<?/*= $url_extra_party */?>" class="<?php /*echo $status_color; */?>" style="z-index:6;"><span style="<?php /*echo $ColorCode; */?>">3</span> Extra Party </a>-->
    </li>                       

    <li> 
        <?php
        if ($this->uri->segment(2) == 'subordinate_court') {
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'first active';
        } elseif (in_array(CAVEAT_BREAD_SUBORDINATE_COURT, $breadCrumbsArray)) {
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = '';
        } else {
            $ColorCode = 'background-color: #f0ad4e;color:#ffffff;';
            $status_color = '';
        }
        ?>
        <a href="<?= $url_subordinate_court ?>" class="<?php echo $status_color; ?>" style="z-index:5;"><span style="<?php echo $ColorCode; ?>">3</span>  Earlier Courts  </a>
    </li>

    <li>  

        <?php
        if ($this->uri->segment(1) == 'uploadDocuments' || $this->uri->segment(1) == 'documentIndex') {
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'first active';
        } elseif (in_array(CAVEAT_BREAD_UPLOAD_DOC, $breadCrumbsArray)) {
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = '';
        } else {

            $ColorCode = 'background-color: #C11900;color:#ffffff;';
            $status_color = '';
        }
        ?>
        <a href="<?= $upload_doc_url ?>" class="<?php echo $status_color; ?>" style="z-index:4;"><span style="<?php echo $ColorCode; ?>">4</span> Upload Document / Index</a>

    </li>

   <!-- <li>
        <?php
/*        if ($this->uri->segment(1) == 'documentIndex') {
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'first active';
        } elseif (in_array(CAVEAT_BREAD_DOC_INDEX, $breadCrumbsArray)) {
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = '';
        } else {
            $ColorCode = 'background-color: #C11900;color:#ffffff;';
            $status_color = '';
        }
        */?>
        <a href="<?/*= $doc_index_url */?>" class="<?php /*echo $status_color; */?>" style="z-index:3;"><span style="<?php /*echo $ColorCode; */?>">6</span> Index  </a>
    </li>-->

    <li> 
        <?php
        if ($this->uri->segment(2) == 'courtFee') {
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'first active';
        } elseif (in_array(CAVEAT_BREAD_COURT_FEE, $breadCrumbsArray)) {
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = '';
        } else {
            $ColorCode = 'background-color: #f0ad4e;color:#ffffff;';
            $status_color = '';
        }
        ?>
        <a href="<?= $url_case_courtfee ?>"class="<?php echo $status_color; ?>" style="z-index:2;"><span style="<?php echo $ColorCode; ?>">5</span> Pay Court Fee </a>
    </li>                      


    <li> 
        <?php
        if ($this->uri->segment(2) == 'view') {
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'first active';
        } elseif (in_array(CAVEAT_BREAD_CAVEATOR, $breadCrumbsArray) && in_array(CAVEAT_BREAD_CAVEATEE, $breadCrumbsArray)  && in_array(CAVEAT_BREAD_DOC_INDEX, $breadCrumbsArray)) {
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = '';
        } else {
            $ColorCode = 'background-color: #f0ad4e;color:#ffffff;';
            $status_color = '';
        }
        ?>
        <a href="<?= base_url('caveat/view') ?>" class="<?php echo $status_color; ?>" style="z-index:1;"><span style="<?php echo $ColorCode; ?>">6</span>  View </a>
    </li>
</ul>

<div class="clearfix"></div><br>