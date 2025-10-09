<?php 
$segment = service('uri');

$StageArray = !empty(getSessionData('breadcrumb_enable')) ? explode(',', $_SESSION['efiling_details']['breadcrumb_status']) : array();
$disabled_status1='pointer-events: none; cursor: default;';?>
<ul class="nav-breadcrumb">

    <li> 
        
    </li> 

    <li> 
        
    </li>

    <li>  

        
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
        
    </li>

    <li> 
        
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
        
    </li>
</ul>

<div class="clearfix"></div><br>