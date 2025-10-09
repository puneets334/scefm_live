
<?php $StageArray = explode(',', $_SESSION['efiling_details']['breadcrumb_status']); ?>
<ul class="nav-breadcrumb">

    <li> 
        <?php
        if ($this->uri->segment(2) == 'caseDetails') {
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
        <a href="<?= $url_case_detail ?>"class="<?php echo $status_color; ?>" style="z-index:12;"><span style="<?php echo $ColorCode; ?>">1</span> Case Detail </a>
    </li> 

    <li> 
        <?php
        if ($this->uri->segment(2) == 'petitioner') {
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'first active';
        } elseif (in_array(NEW_CASE_PETITIONER, $StageArray)) {
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = '';
        } else {
            $ColorCode = 'background-color: #C11900;color:#ffffff;';
            $status_color = '';
        }
        ?>
        <a href="<?= $url_petitioner ?>" class="<?php echo $status_color; ?>" style="z-index:11"><span style="<?php echo $ColorCode; ?>">2</span> Petitioner </a>
    </li>

    <li>  

        <?php
        if ($this->uri->segment(2) == 'respondent') {
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'first active';
        } elseif (in_array(NEW_CASE_RESPONDENT, $StageArray)) {
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = '';
        } else {
            $ColorCode = 'background-color: #C11900;color:#ffffff;';
            $status_color = '';
        }
        ?>
        <a href="<?= $url_respondent ?>" class="<?php echo $status_color; ?>" style="z-index:10"><span style="<?php echo $ColorCode; ?>">3</span> Respondent </a>
    </li>

    <li> 
        <?php
        if ($this->uri->segment(2) == 'extra_party') {
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'first active';
        } elseif (in_array(NEW_CASE_EXTRA_PARTY, $StageArray)) {
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = '';
        } else {
            $ColorCode = 'background-color: #f0ad4e;color:#ffffff;';
            $status_color = '';
        }
        ?>
        <a href="<?= $url_extra_party ?>" class="<?php echo $status_color; ?>" style="z-index:9;"><span style="<?php echo $ColorCode; ?>">4</span> Extra Party </a>
    </li>

    <li> 
        <?php
        if ($this->uri->segment(2) == 'lr_party') {
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'first active';
        } elseif (in_array(NEW_CASE_LRS, $StageArray)) {
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = '';
        } else {
            $ColorCode = 'background-color: #f0ad4e;color:#ffffff;';
            $status_color = '';
        }
        ?>
        <a href="<?= $url_add_lrs ?>"class="<?php echo $status_color; ?>" style="z-index:8;"><span style="<?php echo $ColorCode; ?>">5</span> Legal Representative </a>
    </li>



    <li> 
        <?php
        if ($this->uri->segment(2) == 'actSections') {
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'first active';
        } elseif (in_array(NEW_CASE_ACT_SECTION, $StageArray)) {
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = '';
        } else {
            $ColorCode = 'background-color: #C11900;color:#ffffff;';
            $status_color = '';
        }
        ?>
        <a href="<?= $url_act_section ?>" class="<?php echo $status_color; ?>" style="z-index:7;"><span style="<?php echo $ColorCode; ?>">6</span> Act-Section </a>
    </li>                                               


    <li> 
        <?php
        if ($this->uri->segment(2) == 'subordinate_court') {
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'first active';
        } elseif (in_array(NEW_CASE_SUBORDINATE_COURT, $StageArray)) {
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = '';
        } else {
            $ColorCode = 'background-color: #f0ad4e;color:#ffffff;';
            $status_color = '';
        }
        ?>
        <a href="<?= $url_subordinate_court ?>" class="<?php echo $status_color; ?>" style="z-index:6;"><span style="<?php echo $ColorCode; ?>">7</span>  Earlier Courts  </a>
    </li>
    
    <li> 
        <?php
        if ($this->uri->segment(2) == 'additionalAdv') {
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'first active';
        } elseif (in_array(NEW_CASE_ADDITIONAL_ADV, $StageArray)) {
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = '';
        } else {
            $ColorCode = 'background-color: #f0ad4e;color:#ffffff;';
            $status_color = '';
        }
        ?>
        <a href="<?= $url_case_additional_adv ?>" class="<?php echo $status_color; ?>" style="z-index:6;"><span style="<?php echo $ColorCode; ?>">7</span>  Additional Advocate  </a> 
    </li>
    
    <li> 
        <?php
        if ($this->uri->segment(2) == 'additionalCaseDetails') {
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'first active';
        } elseif (in_array(NEW_CASE_ADDITIONAL_INFO, $StageArray)) {
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = '';
        } else {
            $ColorCode = 'background-color: #f0ad4e;color:#ffffff;';
            $status_color = '';
        }
        ?>
        <a href="<?= $url_case_additional_caseDetails ?>" class="<?php echo $status_color; ?>" style="z-index:6;"><span style="<?php echo $ColorCode; ?>">7</span>  Additional Case Details  </a> 
    </li>

    <li> 
        <?php
        if ($this->uri->segment(2) == 'upload_docs') {
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'first active';
        } elseif (in_array(NEW_CASE_UPLOAD_DOCUMENT, $StageArray)) {
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = '';
        } else {
            $ColorCode = 'background-color: #C11900;color:#ffffff;';
            $status_color = '';
        }
        ?>
        <a href="<?= $url_case_upload_docs ?>" class="<?php echo $status_color; ?>" style="z-index:5;"><span style="<?php echo $ColorCode; ?>">8</span> Upload Document </a>
    </li> 

    <li> 
        <?php
        if ($this->uri->segment(1) == 'documentIndex') {
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'first active';
        } elseif (in_array(NEW_CASE_INDEX, $StageArray)) {
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = '';
        } else {
            $ColorCode = 'background-color: #C11900;color:#ffffff;';
            $status_color = '';
        }
        ?>
        <a href="<?= $url_case_create_index ?>"class="<?php echo $status_color; ?>" style="z-index:4;"><span style="<?php echo $ColorCode; ?>">9</span> Index </a>
    </li>
    
    <li> 
        <?php
        if ($this->uri->segment(1) == 'IA') {
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'first active';
        } elseif (in_array(NEW_CASE_IA, $StageArray)) {
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = '';
        } else {
            $ColorCode = 'background-color: #C11900;color:#ffffff;';
            $status_color = '';
        }
        ?>
        <a href="<?= $url_case_ia ?>"class="<?php echo $status_color; ?>" style="z-index:4;"><span style="<?php echo $ColorCode; ?>">9</span> IA </a>
    </li>



    <li> 
        <?php
        if ($this->uri->segment(2) == 'courtFee') {
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'first active';
        } elseif (in_array(NEW_CASE_COURT_FEE, $StageArray)) {
            $ColorCode = $bgcolor . ";color:#ffffff";
            $status_color = '';
        } else {
            $ColorCode = $bgcolor . ";color:#ffffff";
            $status_color = '';
        }
        ?>
        <a href="<?= $url_case_courtfee ?>" class="<?php echo $status_color; ?>" style="z-index:3;"><span style="<?php echo $ColorCode; ?>">10</span> Court Fee </a>
    </li>                        



    <li> 
        <?php
        if ($this->uri->segment(1) == 'affirmation') {
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'first active';
        } elseif (in_array(NEW_CASE_AFFIRMATION, $StageArray)) {
            $ColorCode = 'background-color: #169F85;color:#ffffff';
            $status_color = '';
        } elseif ($_SESSION['login']['ref_m_usertype_id'] == USER_CLERK || $_SESSION['login']['ref_m_usertype_id'] == USER_DEPARTMENT) {
            $ColorCode = 'background-color: #f0ad4e;color:#ffffff;';
            $status_color = '';
        } else {
            $ColorCode = 'background-color: #C11900;color:#ffffff';
            $status_color = '';
        }
        ?>
        <a href="<?= $url_case_affirmation ?>" class="<?php echo $status_color; ?>" style="z-index:2;"><span style="<?php echo $ColorCode; ?>">11</span> Affirmation </a>
    </li>



    <li> 
        <?php
        if ($this->uri->segment(2) == 'view') {
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'first active';
        } elseif (in_array(NEW_CASE_PETITIONER, $StageArray) && in_array(NEW_CASE_RESPONDENT, $StageArray) && in_array(NEW_CASE_UPLOAD_DOCUMENT, $StageArray)) {
            $ColorCode = 'background-color: #169F85;color:#ffffff';
            $status_color = '';
        } else {
            $ColorCode = 'background-color: #C11900;color:#ffffff';
            $status_color = '';
        }
        ?>
        <a href="<?= $url_case_view ?>" class="<?php echo $status_color; ?>" style="z-index:1;"><span style="<?php echo $ColorCode; ?>">12</span>  View </a> 
    </li>
</ul>

<div class="clearfix"></div><br>