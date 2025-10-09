
<?php 
$segment = service('uri');
$StageArray = !empty(getSessionData('breadcrumb_enable')) ? explode(',', $_SESSION['efiling_details']['breadcrumb_status']) : array();
$disabled_status1='pointer-events: none; cursor: default;';?>
<ul class="nav-breadcrumb">

    <li> 
        <?php
        if ($segment->getSegment(2) == 'caseDetails') {
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'first active';
        } elseif (in_array(NEW_CASE_CASE_DETAIL, $StageArray)) {
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = '';
        } else {
            $ColorCode = 'background-color: #f0ad4e;color:#ffffff;';
            $status_color = '';
        }
        ?>
        <!--<a href="<?/*= $url_case_detail */?>"class="<?php /*echo $status_color; */?>" style="z-index:12;"><span style="<?php /*echo $ColorCode; */?>">1</span> Case Detail </a>-->
    </li> 

    <li> 
        <?php
        if ($segment->getSegment(2) == 'petitioner') {
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'first active';
            $disabled_status='';
        } elseif (in_array(NEW_CASE_PETITIONER, $StageArray)) {
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = '';
            $disabled_status='';
        } else {
            $ColorCode = 'background-color: #C11900;color:#ffffff;';
            $status_color = '';
            $disabled_status='pointer-events: none; cursor: default;';
        }
        ?>
        <!--<a href="<?/*= $url_petitioner */?>" class="<?php /*echo $status_color; */?>" style="z-index:11; <?php /*if(!in_array(NEW_CASE_CASE_DETAIL, $StageArray)){ echo $disabled_status; }*/?>"><span style="<?php /*echo $ColorCode; */?>">2</span> Petitioner </a>-->
    </li>

    <li>  

        <?php
        if ($segment->getSegment(2) == 'respondent') {
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'first active';
            $disabled_status='';
        } elseif (in_array(NEW_CASE_RESPONDENT, $StageArray)) {
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = '';
            $disabled_status='';
        } else {
            $ColorCode = 'background-color: #C11900;color:#ffffff;';
            $status_color = '';
            $disabled_status='pointer-events: none; cursor: default;';
        }
        ?>
        <!--<a href="<?/*= $url_respondent */?>" class="<?php /*echo $status_color; */?>" style="z-index:10; <?php /*if(!in_array(NEW_CASE_PETITIONER, $StageArray)){ echo $disabled_status; }*/?>"><span style="<?php /*echo $ColorCode; */?>">3</span> Respondent </a>-->
    </li>

    <li> 
        <?php
        if ($segment->getSegment(2) == 'extra_party') {
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'first active';
           // $disabled_status='';
        } elseif (in_array(NEW_CASE_EXTRA_PARTY, $StageArray)) {
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = '';
           // $disabled_status='';
        } else {
            $ColorCode = 'background-color: #f0ad4e;color:#ffffff;';
            $status_color = '';
            //$disabled_status='pointer-events: none; cursor: default;';
        }
        ?>
        <!--<a href="<?/*= $url_extra_party */?>" class="<?php /*echo $status_color; */?>" style="z-index:9; <?php /*if(!in_array(NEW_CASE_RESPONDENT, $StageArray)){ echo $disabled_status1;} */?>"><span style="<?php /*echo $ColorCode; */?>">4</span> Extra Party </a>-->
    </li>

    <li> 
        <?php
        if ($segment->getSegment(2) == 'lr_party') {
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'first active';
           // $disabled_status='';
        } elseif (in_array(NEW_CASE_LRS, $StageArray)) {
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = '';
            //$disabled_status='';
        } else {
            $ColorCode = 'background-color: #f0ad4e;color:#ffffff;';
            $status_color = '';
           // $disabled_status='pointer-events: none; cursor: default;';
        }
        ?>
        <!--<a href="<?/*= $url_add_lrs */?>"class="<?php /*echo $status_color; */?>" style="z-index:8; <?php /*if(!in_array(NEW_CASE_RESPONDENT, $StageArray)){ echo $disabled_status1;}*/?>"><span style="<?php /*echo $ColorCode; */?>">5</span> Legal Representative </a>-->
    </li>



    <li> 
        <?php
        if ($segment->getSegment(2) == 'actSections') {
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'first active';
            //$disabled_status='';
        } elseif (in_array(NEW_CASE_ACT_SECTION, $StageArray)) {
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = '';
           // $disabled_status='';
        } else {
            $ColorCode = 'background-color: #C11900;color:#ffffff;';
            $status_color = 'abc';
           // $disabled_status='pointer-events: none; cursor: default;';
        }
        ?>
        <!--<a href="<?/*= $url_act_section */?>" class="<?php /*echo $status_color; */?>" style="z-index:7; <?php /*if(!in_array(NEW_CASE_RESPONDENT, $StageArray)){ echo $disabled_status1;}*/?>"><span style="<?php /*echo $ColorCode; */?>">6</span> Act-Section </a>-->
    </li>                                               


    <li> 
        <?php
        if ($segment->getSegment(2) == 'subordinate_court') {
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'first active';
           // $disabled_status='';
        } elseif (in_array(NEW_CASE_SUBORDINATE_COURT, $StageArray)) {
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = '';
          //  $disabled_status='';
        } else {
            $ColorCode = 'background-color: #f0ad4e;color:#ffffff;';
            $status_color = '';
            //$disabled_status='pointer-events: none; cursor: default;';
        }
        ?>
        <!--<a href="<?/*= $url_subordinate_court */?>" class="<?php /*echo $status_color; */?>" style="z-index:6;<?php /*if(!in_array(NEW_CASE_RESPONDENT, $StageArray)){ echo $disabled_status1;} */?>"><span style="<?php /*echo $ColorCode; */?>">4</span>  Earlier Courts  </a>-->
    </li>

    <li> 
        <?php
        if ($segment->getSegment(2) == 'upload_docs' || $segment->getSegment(1) == 'uploadDocuments' || $segment->getSegment(1) == 'documentIndex') {
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'first active';
            //$disabled_status='';
        } elseif (in_array(NEW_CASE_UPLOAD_DOCUMENT, $StageArray)) {
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = '';
           // $disabled_status='';
        } else {
            $ColorCode = 'background-color: #C11900;color:#ffffff;';
            $status_color = '';
           // $disabled_status='pointer-events: none; cursor: default;';
        }
        ?>
        <a href="<?= $url_case_upload_docs ?>" class="<?php echo $status_color; ?>" style="z-index:5;<?php if(!in_array(NEW_CASE_RESPONDENT, $StageArray)){ echo $disabled_status1;} ?>"><span style="<?php echo $ColorCode; ?>">1</span> Upload Document / Index </a>
    </li> 

    <li> 
        <?php
        if ($segment->getSegment(1) == 'documentIndex') {
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'first active';
            //$disabled_status='';
        } elseif (in_array(NEW_CASE_INDEX, $StageArray)) {
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = '';
           // $disabled_status='';
        } else {
            $ColorCode = 'background-color: #C11900;color:#ffffff;';
            $status_color = '';
           // $disabled_status='pointer-events: none; cursor: default;';
        }
        ?>
        <!--<a href="<?/*= $url_case_create_index */?>"class="<?php /*echo $status_color; */?>" style="z-index:4;<?php /*if(!in_array(NEW_CASE_RESPONDENT, $StageArray)){ echo $disabled_status1;} */?>"><span style="<?php /*echo $ColorCode; */?>">5</span> Index </a>-->
    </li>



    <li> 
        <?php
        if ($segment->getSegment(2) == 'courtFee') {
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'first active';
          //  $disabled_status='';
        } elseif (in_array(NEW_CASE_COURT_FEE, $StageArray)) {
            //$ColorCode = $bgcolor . ";color:#ffffff";
            $bgcolor='background-color: #169F85;';
            $ColorCode = $bgcolor . ";color:#ffffff";
            $status_color = '';
           // $disabled_status='';
        } else {

            $bgcolor='background-color: #C11900;';
            $ColorCode = $bgcolor . ";color:#ffffff";
            $status_color = '';
           // $disabled_status='pointer-events: none; cursor: default;';
        }

        ?>
        <a href="<?= $url_case_courtfee ?>" class="<?php echo $status_color; ?>" style="z-index:3;<?php if(!in_array(NEW_CASE_RESPONDENT, $StageArray)){ echo $disabled_status1;} ?>"><span style="<?php echo $ColorCode; ?>">2</span> Pay Court Fee </a>
    </li>                        



    <li>
        <?php
        if ($segment->getSegment(1) == 'affirmation') {
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'first active';
           // $disabled_status='';
        } elseif (in_array(NEW_CASE_AFFIRMATION, $StageArray)) {
            $ColorCode = 'background-color: #169F85;color:#ffffff';
            $status_color = '';
        } elseif ($_SESSION['login']['ref_m_usertype_id'] == USER_CLERK || $_SESSION['login']['ref_m_usertype_id'] == USER_DEPARTMENT) {
            $ColorCode = 'background-color: #f0ad4e;color:#ffffff;';
            $status_color = '';
           // $disabled_status='';
        } else {
            $ColorCode = 'background-color: #C11900;color:#ffffff';
            $status_color = '';
           // $disabled_status='pointer-events: none; cursor: default;';
        }
        ?>
        <!--<a href="<?/*= $url_case_affirmation */?>" class="<?php /*echo $status_color; */?>" style="z-index:2;<?php /*if(!in_array(NEW_CASE_RESPONDENT, $StageArray)){ echo $disabled_status1;} */?>"><span style="<?php /*echo $ColorCode; */?>">11</span> Affirmation </a>-->
    </li>



    <li> 
        <?php
        if ($segment->getSegment(2) == 'view') {
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'first active';
            //$disabled_status='';
        } elseif (in_array(NEW_CASE_PETITIONER, $StageArray) && in_array(NEW_CASE_RESPONDENT, $StageArray) && in_array(NEW_CASE_UPLOAD_DOCUMENT, $StageArray)) {
            $ColorCode = 'background-color: #169F85;color:#ffffff';
            $status_color = '';
            //$disabled_status='';
        } else {
            $ColorCode = 'background-color: #C11900;color:#ffffff';
            $status_color = '';
            //$disabled_status='pointer-events: none; cursor: default;';
        }
        ?>
        <a href="<?= base_url('newcase/view') ?>" class="<?php echo $status_color; ?>" style="z-index:1;<?php if(!in_array(NEW_CASE_RESPONDENT, $StageArray)){ echo $disabled_status1;}?>"><span style="<?php echo $ColorCode; ?>">3</span>  View </a>
    </li>
</ul>

<div class="clearfix"></div><br>