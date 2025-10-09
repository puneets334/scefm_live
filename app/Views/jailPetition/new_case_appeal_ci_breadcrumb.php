
<?php $StageArray = explode(',', $_SESSION['efiling_details']['breadcrumb_status']); ?>
<ul class="nav-breadcrumb">

    <li> 
        <?php
        if ($this->uri->segment(2) == 'caseDetails') {
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'first active';
        } elseif (in_array(JAIL_PETITION_CASE_DETAILS, $StageArray)) {
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = '';
        } else {
            $ColorCode = 'background-color: #C11900;color:#ffffff;';
            $status_color = '';
        }
        ?>
        <a href="<?= $url_case_detail ?>"class="<?php echo $status_color; ?>" style="z-index:12;"><span style="<?php echo $ColorCode; ?>">1</span> Case Detail </a>
    </li>


    <li> 
        <?php
        if ($this->uri->segment(2) == 'Extra_petitioner') {
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'first active';
        } elseif (in_array(JAIL_PETITION_EXTRA_PETITIONER, $StageArray)) {
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = '';
        } else {
            $ColorCode = 'background-color: #f0ad4e;color:#ffffff;';
            $status_color = '';
        }
        ?>
        <a href="<?= $url_extra_petitioner ?>" class="<?php echo $status_color; ?>" style="z-index:9;"><span style="<?php echo $ColorCode; ?>">2</span> Extra Petitioner </a>
    </li>


    <li> 
        <?php
        if ($this->uri->segment(2) == 'Subordinate_court') {
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'first active';
        } elseif (in_array(JAIL_PETITION_SUBORDINATE_COURT, $StageArray)) {
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = '';
        } else {
            $ColorCode = 'background-color: #C11900;color:#ffffff;';
            $status_color = '';
        }
        ?>
        <a href="<?= $url_subordinate_court ?>" class="<?php echo $status_color; ?>" style="z-index:6;"><span style="<?php echo $ColorCode; ?>">3</span>  Earlier Court  </a>
    </li>

    <li> 
        <?php
        if ($this->uri->segment(2) == 'upload_docs') {
            $ColorCode = 'background-color: #01ADEF';
            $status_color = 'first active';
        } elseif (in_array(JAIL_PETITION_UPLOAD_DOCUMENT, $StageArray)) {
            $ColorCode = 'background-color: #169F85;color:#ffffff;';
            $status_color = '';
        } else {
            $ColorCode = 'background-color: #C11900;color:#ffffff;';
            $status_color = '';
        }
        ?>
        <a href="<?= $url_case_upload_docs ?>" class="<?php echo $status_color; ?>" style="z-index:5;"><span style="<?php echo $ColorCode; ?>">4</span> Upload Document </a>
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
        <a href="<?= $url_case_affirmation ?>" class="<?php echo $status_color; ?>" style="z-index:2;"><span style="<?php echo $ColorCode; ?>">5</span> Affirmation </a>
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
        <a href="<?= base_url('jailPetition/view') ?>" class="<?php echo $status_color; ?>" style="z-index:1;"><span style="<?php echo $ColorCode; ?>">6</span>  View </a>
    </li>
</ul>

<div class="clearfix"></div><br>