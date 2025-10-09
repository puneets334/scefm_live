
<?php $StageArray = explode(',', $_SESSION['efiling_details']['breadcrumb_status']);
$disabled_status1='pointer-events: none; cursor: default;';?>
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
        if ($this->uri->segment(2) == 'upload_docs' || $this->uri->segment(2) == 'uploadDocuments') {
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
        <a href="<?= $url_case_upload_docs ?>" class="<?php echo $status_color; ?>" style="z-index:5;<?php if(!in_array(NEW_CASE_RESPONDENT, $StageArray)){ echo $disabled_status1;} ?>"><span style="<?php echo $ColorCode; ?>">2</span> Upload Document </a>
    </li> 




    <li> 
        <?php
        if ($this->uri->segment(2) == 'courtFee') {
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
        <a href="<?= $url_case_courtfee ?>" class="<?php echo $status_color; ?>" style="z-index:3;<?php if(!in_array(NEW_CASE_RESPONDENT, $StageArray)){ echo $disabled_status1;} ?>"><span style="<?php echo $ColorCode; ?>">3</span> Pay Court Fee </a>
    </li>



    <li> 
        <?php
        if ($this->uri->segment(2) == 'view') {
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
        <!--<a href="<?/*= base_url('newcase/view') */?>" class="<?php /*echo $status_color; */?>" style="z-index:1;<?php /*if(!in_array(NEW_CASE_RESPONDENT, $StageArray)){ echo $disabled_status1;}*/?>"><span style="<?php /*echo $ColorCode; */?>">4</span>  View </a>-->
    </li>
</ul>

<div class="clearfix"></div><br>