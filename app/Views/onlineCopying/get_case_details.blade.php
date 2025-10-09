<?php

if(isset($_SESSION['is_token_matched']) && $_SESSION['is_token_matched'] == 'Yes' && isset($_SESSION["applicant_email"]) && isset($_SESSION["applicant_mobile"]) ) {
if (isset($_SESSION['is_user_address_found']) && $_SESSION['is_user_address_found'] != 'YES') {
    //add your address *
    ?>
    <script type='text/javascript'>window.location.href = '<?php echo base_url('online_copying/applicant_address'); ?>'</script>
    <?php
}

$OLD_ROP = OLD_ROP_DB;
$diary_no = 0;
if(isset($_REQUEST['chk_status']) && $_REQUEST['chk_status']==1)
{
    $ct = $_REQUEST['ct']; 
    $cn = $_REQUEST['cn']; 
    $cy = $_REQUEST['cy'];
    $sql_dno=array();
    $sql_dno = eCopyingGetDiaryNo($ct, $cn, $cy);
    if(!empty($sql_dno) && count($sql_dno) > 0){
        if(isset($sql_dno['dn'])){
            $diary_no = $sql_dno['dn'].$sql_dno['dy'];
            $_SESSION['session_d_no'] = $sql_dno['dn'];
            $_SESSION['session_d_year'] =$sql_dno['dy'];
        }else{
            $diary_no = 0;
        }
    }
    else{
        
        $sql_dno = eCopyingCheckDiaryNo($ct, $cn, $cy);
        if(count($sql_dno) > 0){
            $diary_no = $sql_dno['diary_no'].substr($sql_dno['diary_no'], -4);
            $_SESSION['session_d_no'] = $sql_dno['diary_no'];
            $_SESSION['session_d_year'] = substr($sql_dno['diary_no'], -4);
        }
    }
}
else{
    $diary_no=$_REQUEST['d_no'].$_REQUEST['d_yr'];
    $_SESSION['session_d_no'] = $_REQUEST['d_no'];
    $_SESSION['session_d_year'] = $_REQUEST['d_yr'];
}

$res_fil_det = eCopyingGetFileDetails($diary_no);

$pno="";
$case_no='';
if(count($res_fil_det) > 0){
   if($res_fil_det[0]->pno!=0)
    {
        if($res_fil_det[0]->pno==2)
            $pno=" AND ANOTHER";
        else if($res_fil_det[0]->pno>2)
            $pno=" AND OTHERS";
    }
    
    $rno="";
    if($res_fil_det[0]->rno!=0)
    {
        if($res_fil_det[0]->rno==2)
            $rno=" AND ANOTHER";
        else if($res_fil_det[0]->rno>2)
            $rno=" AND OTHERS";
    }
   if($res_fil_det[0]->reg_no_display!='')
    {
        $case_no = $res_fil_det[0]->reg_no_display;
    }
    
    $case_no .= '  Diary No. '.substr($diary_no,0,-4).' - '.substr($diary_no,-4);

    $_SESSION['session_case_no'] = $case_no;
    $_SESSION['session_cause_title'] = $res_fil_det[0]->pet_name . $pno . " Vs " . $res_fil_det[0]->res_name . $rno;
    $_SESSION['session_c_status'] = $res_fil_det[0]->c_status ;
    // $_SESSION['unavailable_copy_requested_diary_no'] = $diary_no;
    
?>

<!--    <div id="show_error"></div>  This Segment Displays The Validation Rule -->
    <div class="card mt-2" >
<div class="card-header bg-primary text-white font-weight-bolder mt-0 mb-0">            
                                        Case Info    
                                    </div>        
        <div class="card-body">
            <div class="text-center diary_no_class" data-diary-no="<?=$diary_no;?>" data-case-no="<?=$case_no;?>" data-cause_title="<?=$res_fil_det[0]->pet_name . $pno . " Vs " . $res_fil_det[0]->res_name . $rno;?>" >
                <p><?= $res_fil_det[0]->pet_name . $pno . " Vs " . $res_fil_det[0]->res_name . $rno; ?></p>
                <strong><?= $case_no; ?> (<?= $res_fil_det[0]->c_status=='P' ? '<span style="color: #0554DB;">Pending</span>' : '<span class="text-danger">Disposed</span>'; ?>)</strong>
               
                <p class="text-left">
                    Applying as :<b> 
                    <?php if(getSessionData('login')['ref_m_usertype_id'] == AUTHENTICATED_BY_AOR){
                            echo "Authorized by AOR";
                        }else{  if(isset($_SESSION["session_filed"]) && $_SESSION["session_filed"] == 1){ echo "AOR";}
                            else if(isset($_SESSION["session_filed"]) && $_SESSION["session_filed"] == 2){ echo "Party of the case"; }
                            else if(isset($_SESSION["session_filed"]) && $_SESSION["session_filed"] == 3){ echo "Advocate/Appearing Counsel"; }
                            else if(isset($_SESSION["session_filed"]) && $_SESSION["session_filed"] == 4){ echo "Third Party"; }
                            else if(isset($_SESSION["session_filed"]) && $_SESSION["session_filed"] == 6){ echo "Authorized by AOR"; }
                    }
                            ?>
                    </b>
                    
                    <i class="fa fa-user text-info ml-5 " aria-hidden="true"></i> <?=(isset($_SESSION['user_address'][0]['second_name']) ? $_SESSION['user_address'][0]['second_name'] : '').' '.(isset($_SESSION['user_address'][0]['first_name']) ? $_SESSION['user_address'][0]['first_name'] : '');?> <i class="fa fa-phone-square text-info" aria-hidden="true"></i> <?=$_SESSION["applicant_mobile"];?> <i class="fa fa-envelope text-info" aria-hidden="true"></i> : <?=$_SESSION["applicant_email"];?></b>
                    
                </p>
                
            </div>
        </div>
    </div>        
    <?php 
    
    if(isset($_SESSION["session_filed"]) && $_SESSION["session_filed"] == 2){
        $check_asset_type = 5; //for party
    }
    if(isset($_SESSION["session_filed"]) && $_SESSION["session_filed"] == 3){
        $check_asset_type = 6; //for appearing counsel
    }
    if(isset($_SESSION["session_filed"]) && $_SESSION["session_filed"] == 4){
        $check_asset_type = 4; //for affidaivt filed by third party
    }
    
    if(isset($_SESSION["session_filed"]) && ($_SESSION["session_filed"] == 2 || $_SESSION["session_filed"] == 3 || $_SESSION["session_filed"] == 4)){
        $stmt_video = getStatementVideo($_SESSION["applicant_mobile"], $_SESSION["applicant_email"]);
        if (is_array($stmt_video) && count($stmt_video) == 0) {
            ?>
            <div class="alert alert-danger alert-dismissible">
                Please record your 10 seconds video. (It's mandatory)
            </div>
            <?php
            exit();
        }
        
                    
                    
        $stmt_image = getStatementImage($_SESSION["applicant_mobile"], $_SESSION["applicant_email"]);
        if (is_array($stmt_image) && count($stmt_image) == 0) {
            ?>
            <div class="alert alert-danger alert-dismissible">
                <!-- Please <a class='btn btn-danger' href='user_image.php'>Click here</a> to capture your photo. (It's mandatory) -->
                Please upload your photo. (It's mandatory)
            </div>
            <?php
            exit();
        }
        
        $stmt_id_proof = getStatementIdProof($_SESSION["applicant_mobile"], $_SESSION["applicant_email"]);
        if (is_array($stmt_id_proof) && count($stmt_id_proof) == 0) {
            ?>
            <div class="alert alert-danger alert-dismissible">
                <!-- Please <a class='btn btn-danger' href='user_id_proof.php'>Click here</a> to upload you ID Proof. (It's mandatory) -->
                Please upload your ID Proof. (It's mandatory)
            </div>
            <?php
            exit();
        }

        //VERIFICATION OF CASES VALIDATE, MAX 10 REQUEST per day ALLOWED
    $stmt_check = eCopyingStatementCheck($_SESSION["applicant_mobile"], $_SESSION["applicant_email"]);
    if (is_array($stmt_check) && count($stmt_check) > 0) {
        $_SESSION['max_cases_verify_per_day'] = count($stmt_check);
        if($_SESSION['max_cases_verify_per_day'] >=10){
            ?>
                <div class="alert alert-danger alert-dismissible">
                    Max 10 requests reached for verification per day. 
                </div>                  
            <?php                
            exit();
        }
    }    
    else{
        $_SESSION['max_cases_verify_per_day'] = 0;
    }

    
       //DIGITAL COPY, MAX 10 REQUEST ALLOWED
    $stmt_check2 = eCopyingCheckMaxDigitalRequest($_SESSION["applicant_mobile"], $_SESSION["applicant_email"]);
    if (is_array($stmt_check2) && count($stmt_check2) > 0) {
        $_SESSION['max_digital_copy_per_day'] = count($stmt_check2);
        if($_SESSION['max_digital_copy_per_day'] >=10){
            ?>
                <div class="alert alert-danger alert-dismissible">
                    Max 10 digital copy request reached per day. 
                </div>                  
            <?php
            exit();
        }                
    }
    else{
        $_SESSION['max_digital_copy_per_day'] = 0;
    }
    if(isset($_SESSION['login'])){
        $data_sqlv = eCopyingCopyStatus($diary_no, $check_asset_type, $_SESSION["login"]['mobile_number'], trim($_SESSION["login"]['emailid']));
    }else{
        $data_sqlv = eCopyingCopyStatus($diary_no, $check_asset_type, $_SESSION["applicant_mobile"], trim($_SESSION["applicant_email"]));
    }
    if (is_array($data_sqlv) && count($data_sqlv) > 0) {
            if($data_sqlv['verify_status'] == 1){
                //pending
                $_SESSION['diary_filed_user_verify_status'] = 'pending';
                ?>
                    <div class="alert alert-danger alert-dismissible">
                        Your Verification for case no. <?=$case_no?> is under process, wait till completion of verification process.
                    </div>                  
                <?php

                exit();
            }
            if($data_sqlv['verify_status'] == 2){
                //success verified
                $_SESSION['diary_filed_user_verify_status'] = 'success';
            }
            if($data_sqlv['verify_status'] == 3){
                //verification required
                $_SESSION['diary_filed_user_verify_status'] = 'apply_for_verification';
                //rejected
            ?>
                <?php                
            }
    }
    else{
        //verification required
        $_SESSION['diary_filed_user_verify_status'] = 'apply_for_verification';
    }
    
}

    if(isset($_SESSION["session_filed"]) && ($_SESSION["session_filed"] == 1 || $_SESSION["session_filed"] == 6)){
        
        if($_SESSION["session_filed"] == 1){
            $applicant_aor_mobile = $_SESSION["applicant_mobile"];
        }
        if($_SESSION["session_filed"] == 6){
            $applicant_aor_mobile = $_SESSION["aor_mobile"];
        }
        //print_r($_SESSION);
        //echo $diary_no.' '.$applicant_aor_mobile;
        $sql_verify = eCopyingGetBar($diary_no,$applicant_aor_mobile);
        if(!empty($sql_verify)){
            $_SESSION['diary_filed_user_verify_status'] = 'success';
        }
        else{
            $_SESSION['diary_filed_user_verify_status'] = 'unregistered';
            ?>
            <div class="alert alert-danger alert-dismissible">
                You are not registered in case no : <?= $case_no; ?>
            </div>&nbsp;&nbsp;
            <!-- <center><a href="<?= base_url('online_copying/verifyUser');?>" class="quick-btn"> Verify User </a></center>                   -->
            <?php                
            exit();
        }        
    }elseif(!isset($_SESSION["session_filed"])){?>
        <div class="alert alert-danger alert-dismissible">
                You are not registered in case no : <?= $case_no; ?>
            </div>
    <?php exit(); }  
        
    if(isset($_SESSION["diary_filed_user_verify_status"]) && $_SESSION["diary_filed_user_verify_status"] == 'apply_for_verification'){
        ?>
        <div class="ml-2"><div class="alert alert-info alert-dismissible p-1 m-1"><strong>Verification Required :</strong></div>
        </div>
        <!--<input type="button" name="btn_party_verification" id="btn_party_verification" value="Submit Request" class="btn btn-primary" />-->
        <?php
       // exit();
    }
    
    ?>
    
        <!-- REQUEST DETAILS -->
        <div class="form-row note_unavailable_doc_request <?= (isset($_SESSION["session_filed"]) && $_SESSION["session_filed"] == 2) && (isset($_SESSION["diary_filed_user_verify_status"]) && $_SESSION["diary_filed_user_verify_status"] == 'apply_for_verification') ? 'd-none' : ''; ?>">
            <div class="col-md-12 ">
                <p class="font-weight-bolder text-right">Note : Click to request for unavailable documents in software
                    <a class="unavailable_doc_request btn btn-warning" href="<?php echo base_url('online_copying/unavailable_request'); ?>">Request Add</a>
                </p>
            </div>    
        </div>

<?php
     
    //APPLICANT DETAILS
    if(isset($_SESSION['user_address'])){
        ?>
        <div class="card col-md-12 mt-2 applicant_details_toggle">
            <div class="card-header bg-primary text-white font-weight-bolder">Applicant Details
                    </div>
                    <div class="card-body">
                        <div class="row applicant_address">
        <?php
        $address_array = 1;
        foreach($_SESSION['user_address'] as $data_address) {


    ?>
    <div class="col-md-4 p-2 address_toggle_<?=$address_array?> <?=$address_array == 1 ? '' : 'd-none'?>" >
        <div class="col-md-12 shadow p-3 mb-3 bg-white rounded">

            <div class="card">
                <div class="card-header bg-white pb-1 pt-1">
                    <div class="row pl-1">
                        <div class=" col-6 text-left d-inline font-weight-bold text-black">
                            <?=$data_address['first_name']." ".$data_address['second_name'];?>
                        </div>
                        <div class="col-6 text-right d-inline font-weight-bold text-black">
                            <label class="radio-inline text-black ml-1 mt-0 mb-0">
                                <input type="radio" name="address_type_radio" data-address_id="<?=$data_address['id']?>" data-pincode="<?=$data_address['pincode']?>" id="radio_address_type_<?=$address_array?>" value="<?=$data_address['address_type']?>" <?=$address_array == 1 ? 'checked' : '' ?> > <?=$data_address['address_type']?>
                            </label>
                        </div>
                    </div>

                </div>
                <div class="card-body pb-1 pt-1">
                    <p>
                        <?=$data_address['address'].", ".$data_address['city'].", "?>
                        <br>
                        <?=$data_address['district'].", ".$data_address['state']?>
                        <br>
                        <?=$data_address['pincode'].", ".$data_address['country']?>
                    </p>
                </div>

                <?php 
                if(count($_SESSION['user_address']) > 1){
                if($address_array == 1){ ?>
                <div class="row text-right pl-2">
                    <button type="button" class="btn btn-primary btn_more_address">more...</button>
                </div>
                <?php } } ?>
            </div>

        </div>
    </div>
    <?php
            $address_array++;
}
?>
                        </div>
        </div>
        </div>
<?php
    }
    
    ?>

    <?php
        if(isset($_SESSION["session_filed"]) && $_SESSION["session_filed"] == 4){
    ?>
    <div class="card col-md-12 mt-2 attach_documents">


                    <div class="card-header bg-primary text-white font-weight-bolder">Upload attested affidavit on stamp of &#x20b9; 20/- <sub> (Max 100 kb & Only PDF File Allowed)</sub>
                    </div>
                    <div class="card-body">

        <form method='post'  action='' enctype="multipart/form-data">

        <div class="form-row">
        <div class="col-md-12">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="affidavit_addon">Upload Affidavit *</span>
                </div>
                <div class="custom-file">
                    <input type="file" class="custom-file-input affidavt_class" name="affidavit" id="affidavit" data-toggle="tooltip" data-placement="top" title="Upload an Original Affidavit on stamp paper of Rs. 20/- duly attested by the Notary specifying the good cause, ground or reason for which the copy is required and stating how he/she is interested in obtaining the same." accept="application/pdf" aria-labelledby="affidavit_addon">
                    <label class="custom-file-label" title="affidavit" for="affidavit"></label>
                </div>
                <div class="input-group-append">
                    <button class="btn btn-default d-none uploaded_affidavit_result" type="button" id="affidavit"></button>
                </div>
            </div>
        </div>
        </form>
            
        </div>

        <!--<input type="button" name="btn_third_party_affidavit_verification" id="btn_third_party_affidavit_verification" value="Submit Request" class="btn btn-primary" />-->

            

                    
    </div>
            <?php } ?>
    
    
    <?php
    
    if((isset($_SESSION["session_filed"]) && $_SESSION["session_filed"] == 2) && (isset($_SESSION["diary_filed_user_verify_status"]) && $_SESSION["diary_filed_user_verify_status"] == 'apply_for_verification')) {
    }
    else{
    ?>
    <div class="card col-md-12 mt-2 copying_dedtails_toggle_request">
                    <div class="card-header bg-primary text-white font-weight-bolder">Copying Details</div>
                    <div class="card-body">

<!--TODO:
 1. Display only if first time applying (Don't allow if already applied)
 2. First time no payment check
 -->

<?php


if(isset($_SESSION["session_filed"]) && ($_SESSION["session_filed"] == 1 || $_SESSION["session_filed"] == 6)){
    $is_bail_applied = getBailApplied($diary_no, $_SESSION['applicant_mobile'], $_SESSION['applicant_email']);
    
    if($is_bail_applied == 'NO'){
    ?>
        <style>
            #radioBtn .notActive{
                color: #3276b1;
                background-color: #fff;
            }
        </style>
    <div class="row mb-3">
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="row">
                <div class="row w-100 align-items-center">
                    <div class="col-5">
                        <label for="inputPassword6" id="bail_order" class="col-form-label">Are you applying first time for Bail Order ? </label>
                    </div>
                    <div class="col-7 pe-0">
                        <div id="radioBtn" class="btn-group">
                            <a class="btn btn-primary btn-sm notActive" data-toggle="bail_order" data-title="Y">YES</a>
                            <a class="btn btn-primary btn-sm active" data-toggle="bail_order" data-title="N">NO</a>
                        </div>  
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
    }
}
?>




    <div class="row mb-3">
        <div class="col-md-4 col-sm-4 col-xs-12">
            <div class="row">
                <div class="row w-100 align-items-center">
                    <div class="col-5">
                        <label for="inputPassword6" id="app_type_addon" class="col-form-label">Application Category *</label>
                    </div>
                    <div class="col-7 pe-0">
                        <?php
                            $app_category = eCopyingGetCopyCategory();
                            
                        ?>
                        <select class="form-control cus-form-ctrl" id="app_type" name="app_type" aria-labelledby="app_type_addon" required>
                            <option value="">-Select-</option>
                            <?php foreach ($app_category as $row) { ?>
                                <option value="<?php echo $row['id']; ?>"><?php echo $row['code'].'-'.$row['description']; ?></option>
                            <?php } ?>
                        </select>   
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 col-sm-4 col-xs-12">
            <div class="row">
                <div class="row w-100 align-items-center">
                    <div class="col-5">
                        <label for="inputPassword6" id="cop_mode_addon" class="col-form-label">Delivery Mode *</label>
                    </div>
                    <div class="col-7 pe-0">
                        <select class="form-control cus-form-ctrl" id="cop_mode" name="cop_mode" aria-labelledby="cop_mode_addon" required>
                            <option value="">Select</option>
                            <option value="1">By Speed Post</option>
                            <option value="2">Counter</option>
                            <option value="3">Email</option>                    
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-4 col-xs-12">
            <div class="row">
                <div class="row w-100 align-items-center">
                    <div class="col-5">
                        <label for="inputPassword6" class="col-form-label" id="num_copy_addon">No. of Copies *</label>
                    </div>
                    <div class="col-7 pe-0">
                    <select class="form-control cus-form-ctrl" id="num_copy" name="num_copy" aria-labelledby="num_copy_addon" required>
                        <?php
                        $currently_selected = date('Y');
                        
        
        
                        $earliest_year = 1;
                        if(isset($_SESSION["session_filed"]) && $_SESSION["session_filed"] == 4){
                            //third party
                            $latest_year = 1;//third party allow only one copy
                        }
                        else{
                            $latest_year = 5;    //other than third party allowed max 5 copy
                        }

                        foreach (range($earliest_year,$latest_year) as $i) {
                            print '<option value="' . $i . '"' . ($i === $currently_selected ? ' selected="selected"' : '') . '>' . $i . '</option>';
                        }
                        ?>
                </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
   
    <div class="form-row" style="color:#186329;">
        <div class="col-md-12">
            <span id="sp_app_charge"></span>
        </div>
    </div>
     
    <div class="form-row">

        <div class="row m-1 firstWarn">
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Warning :</strong> While applying for Record of Proceedings (ROP), Please remember a possibility that Record of Proceedings can be clubbed with Judgements/Orders passed that day. If page count and cost is shown more than your expectations, please click and apply through unavailable documents.
                <button type="button" class="btn btn-sm close firstWarnBtn" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>

        <div class="row m-1 secWarn">
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Warning :</strong> When you are applying for Judgements/Orders and it is found that Record of Proceedings (ROP) is clubbed with such Judgements/Orders, in such case certified copy of the requested document will be issued where as unathenticated copy will be issued for other clubbed documents.
                <button type="button" class="btn btn-sm close secWarnBtn" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>



        <div class="col-md-12">                                
            <div class="mb-3">
                <div class="">
                    <span class="input-group-text" id="applied_for_addon">Applied For *</span>
                </div>



                <div class="table-sec">
                <div class="table-responsive form-radio ml-2 applied_check_boxes" aria-labelledby="applied_for_addon" style="max-height:200px; overflow:auto;">
    <?php
        
        if(isset($res_fil_det[0]->main_case) && ($res_fil_det[0]->main_case !='' && $res_fil_det[0]->main_case != null && $res_fil_det[0]->main_case != '0')){
            $sql_conn_list = eCopyingGetGroupConcat($res_fil_det[0]->main_case);
            
            if(isset($sql_conn_list) && count($sql_conn_list) > 0){
                $res_diary_data = $sql_conn_list;
                $condition = $res_diary_data['conn_list'];
            }
            else{
                $condition = $diary_no;
            }
        }
        else{
            $condition = $diary_no;
        }

        //third party and appearing council allowed only judgemnet orders proceedings
        if($_SESSION["applicant_mobile"] != '9630100950' && (isset($_SESSION["session_filed"]) && ($_SESSION["session_filed"] == 3 || $_SESSION["session_filed"] == 4))){
            $third_party_sub_qry = " ot.id in (36,8,3,20,1,2,4) ";    
        }
        else{
            $third_party_sub_qry = "";    
        }
        
        $jud_order = eCopyingGetCopyDetails($condition,$third_party_sub_qry,$OLD_ROP);

        if(is_array($jud_order) && count($jud_order) > 0){

      ?>







<table class="table table-bordered custom-table table-striped" >
<thead>
    <tr>
       <th>Checkbox</th>
       <th>
           Document Details
       </th>
       <th>
           Order/File Date
       </th>
       <th>
           No. of Pages
       </th>
</tr>
</thead>
<tbody>
                       
                       
                  
                   <?php
                   $sno=1;$tot_pages=0;$tot_amt=0;

                               $isChargable = "NO";// echo $row1['judgement_order_code'].",".date('Y-m-d',strtotime($row1['orderdate']));
       //while ($row1 = mysqli_fetch_array($jud_order)) {

       foreach($jud_order as $row1){
           //print_r($row1);
           $t_pages_x='';
           $pdfname_exploded=explode("/",$row1['pdfname']);

           //Requested documents
           if($row1['s']==0){
               $path = "http://localhost/".$row1['pdfname'];
               $path = "http://".GET_SERVER_IP."/".$row1['pdfname'];
               $rop_public_path = API_SCI_GOV_IN."/".$row1['pdfname'];
               $rop_public_path_url = API_SCI_GOV_IN_URL."/".$row1['pdfname'];
               $path_to_save = $row1['pdfname'];
           }
           else{
               if( $pdfname_exploded[0]=='supremecourt') {
                   $path = "http://".GET_SERVER_IP."/supreme_court/".$row1['pdfname'];
                   $rop_public_path = API_SCI_GOV_IN."/".$row1['pdfname'];
                   $rop_public_path_url = API_SCI_GOV_IN_URL."/".$row1['pdfname'];
                   $path_to_save = "supreme_court/".$row1['pdfname'];
               }
               else{
                   $path = "http://".GET_SERVER_IP."/supreme_court/judgment/".$row1['pdfname'];
                   $rop_public_path = API_SCI_GOV_IN."/judgment/".$row1['pdfname'];
                   $path_to_save = "/supreme_court/judgment/".$row1['pdfname'];
                   $rop_public_path_url = API_SCI_GOV_IN_URL."/judgment/".$row1['pdfname'];
               }
           }

           
           if($row1['s']==0 && $row1['judgement_order_code'] == 37){ //Data in Digital Format
               $NumberOfPages = 1;
           }
           else{
               $path=base_url().'file-sample_150kB.pdf';
               //$pdf=new TCPDF();
               //$NumberOfPages=$pdf->SetSourceFile($path);
               //$nm_s=  exec ('pdftk '.$path. ' dump_data | grep NumberOfPages');
               //$NumberOfPages = str_replace('NumberOfPages: ','', $nm_s);
               //echo $NumberOfPages;
               $parser = new \Smalot\PdfParser\Parser();
               //print_r($row1);
               //echo $rop_public_path;
               //continue;
               $header = file_get_contents($rop_public_path, false, null);
               //print_r(json_decode($header)->error);
               //die;
               if (strpos($header, '%PDF-') !== 0) {
               //echo "File does not appear to be a valid PDF.";
               if(isset(json_decode($header)->error)){
                $NumberOfPages=$row1['fee_clc_for_certification_pages'];
               }else{
               $pdf = $parser->parseFile($rop_public_path);
               $pages = $pdf->getPages();
               $NumberOfPages=count($pages);
               }
               }else{
               $pdf = $parser->parseFile($rop_public_path);
               $pages = $pdf->getPages();
               $NumberOfPages=count($pages);
               }
               
           }
           //die;
       ?>
                       <tr>
                           <td class="pt-0" data-key="Checkbox">
                               <label class="d-none" title="Checkbox <?=$sno?>" for="chkdocjo_<?=$sno?>"><?=$sno?></label>
                               <input type="checkbox" name="chkdocjo_<?php echo $sno; ?>" id="chkdocjo_<?php echo $sno; ?>" data-order_type_id="<?=$row1['judgement_order_code']?>" class="cl_checks"/>

                               <?php
                               $isChargable = getIsPreviuslyApplied(5, $diary_no, $_SESSION["applicant_mobile"], $_SESSION["applicant_email"], $row1['judgement_order_code'], date('Y-m-d',strtotime($row1['orderdate'])));
                            //    pr($isChargable->path);
                                $lastElement = basename($isChargable->path);
                                $lastElementRow1 = basename($row1['pdfname']);

                               if($isChargable->status == 'YES' && $lastElement == $lastElementRow1){
                                   ?>
                                   <span class="text-danger d-none is_chargable" id="ischargable_<?php echo $sno; ?>">Chargeable</span>
                                   <?php
                               }
                               else{
                                   ?>
                                   <span class="text-success d-none is_chargable" id="ischargable_<?php echo $sno; ?>">Free</span>
                                   <?php
                               }
                               ?>


                           </td>
                           <td data-key="Document Details">
                                <span style="display: none;" id="spjudgementordercode_<?php echo $sno; ?>"><?=$row1['judgement_order_code'];?></span>                               
                                <span id="spjudgementorder_<?php echo $sno; ?>"><?php
                                    if($row1['judgement_order'] == "Record of Proceedings" || $row1['judgement_order'] == "Judgement"){
                                        ?>
                                        <a target="_blank" href="<?=$rop_public_path_url?>"><?=$row1['judgement_order']?></a> <i class="fa fa-info-circle text-secondary" aria-hidden="true" title="Please click & verify pdf documents."></i>
                                        <?php
                                    }
                                    else{
                                        echo $row1['judgement_order'];
                                    }

                                   if($row1['judgement_order_code'] == 37){echo ' -'.$row1['order_type_remark'];}
                                   if($row1['fee_clc_for_certification_no_doc'] > 0){ echo "<span class='text-primary'> (Number of Certified Documents ".($row1['fee_clc_for_certification_no_doc']).")</span>";}
                                   ?>
                                </span>
                           </td>
                           <td data-key="Order/File Date">
                               <?php

                               $hide_order_dt = "Order/File Date";
                               if($row1['orderdate'] == '1970-01-01 00:00:00' OR $row1['orderdate'] == '0000-00-00 00:00:00'){
                                   $hide_order_dt = "class='d-none'";
                               }
                               ?>
                               <span <?=$hide_order_dt?> id="sporderdate_<?php echo $sno; ?>"><?php echo !empty($row1['orderdate']) ? date('d-m-Y',strtotime($row1['orderdate'])) : ''; ?></span>
                            </td>
                            <td data-key="No. of Pages">
                                <?php
                                // vd.fee_clc_for_certification_no_doc, vd.fee_clc_for_certification_pages, vd.fee_clc_for_uncertification_no_doc, vd.fee_clc_for_uncertification_pages
                               ?>
                                <span id="sptotalpages_<?php echo $sno; ?>"><?=$NumberOfPages?></span>
                                <span id="spfilepath_<?php echo $sno; ?>" style="display: none;"><?php echo $path_to_save; ?></span>
                                <span id="sp_certi_number_of_docs_<?php echo $sno; ?>" style="display: none;"><?php echo $row1['fee_clc_for_certification_no_doc']; ?></span>
                                <span id="sp_certi_number_of_pages_<?php echo $sno; ?>" style="display: none;"><?php echo $row1['fee_clc_for_certification_pages']; ?></span>
                                <span id="sp_uncerti_number_of_docs_<?php echo $sno; ?>" style="display: none;"><?php echo $row1['fee_clc_for_uncertification_no_doc']; ?></span>
                                <span id="sp_uncerti_number_of_pages_<?php echo $sno; ?>" style="display: none;"><?php echo $row1['fee_clc_for_uncertification_pages']; ?></span>
                            </td>

                       </tr>   
                       <?php
                       $sno++;
       }
       ?>

</tbody>
</table>
<?php
   }
   else 
   {
       ?>
                   <div style="text-align: center"><b>No Record Found</b></div>       
       <?php
   }
                   ?>
                </div>

                </div>




                
            </div>
        </div>
    </div>

                  
                        
                        

        </div>
    </div>
        <div class="table-responsive" id="tb_added_doc"></div>
    <?php } ?>
        
    <?php
    
    if(((isset($_SESSION['diary_filed_user_verify_status']) && $_SESSION['diary_filed_user_verify_status'] == 'success') && (isset($_SESSION["session_filed"]) && $_SESSION["session_filed"] == 2 || $_SESSION["session_filed"] == 1 || $_SESSION["session_filed"] == 6))){
    ?>    
    <div class="row col-md-12 mt-2">
            <div class="col-md-6">

                <div class="form-row confirm_validate_toggle">
                    <div class="form-check mb-3">
                        <label class="form-check-label text-danger">
                            <input class="form-check-input" id="confirm_validate" name="confirm_validate" value="confirm" type="checkbox"> I agree to 
                            <a style="color: #0554DB;" href="#" data-toggle="modal" id="terms_conditions" data-target="#exampleModalLong"><u>terms and conditions</u></a>
                        </label>
                    </div>
                </div>

            </div>
            
            <div class="col-md-6 form-row tm-btn-right">
               <input type="button" name="btn_payment" id="btn_payment" value="Click To Confirm" class="btn btn-primary"/>
            </div>
    </div>
    <?php } 
    else{
?>
            <div class="row pt-2">            
            <div class="col-md-12 text-center">            
               <input type="button" name="btn_case_verify" id="btn_case_verify" value="Submit for Verification" class="btn btn-primary btn-sm col-2" />
            </div>
            </div>
<?php        
    }
    ?>
        
        
<div class="row col-md-12">
    <div class="above_error"></div>
</div>


        <!-- Modal -->
        <div class="modal fade" id="exampleModalLong" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Terms and Conditions</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        The eCopying S/w designed and developed by Supreme Court of India and Payment Gateway API shared by NTRP. In case of any issues during or after payment, user may contact to NTRP https://bharatkosh.gov.in
                        <br><br>Supreme Court of India will not be responsible in any way for non-payment, non-receipt, issue that may arise while paying the amount online for any kind of payments made through this portal. Though all efforts have been made to ensure the accuracy and correctness of the contents on this website, the same should not be construed as a statement of law or used for any legal purposes.
                        <br><br>
                        In case of any variance between what is stated and that contained in the relevant Acts, Rules, Regulations, Policy, Statements, etc, the latter shall prevail. Under no circumstances will Supreme Court of India for any expense, loss or damage including, without limitation, indirect or consequential loss or damage, or any expense, loss or damage whatsoever arising from use, or loss of use, of data, arising out of or in connection with the use of this website.
                        <br><br>These terms and conditions shall be governed by and construed in accordance with the Indian Laws. Any dispute arising under these terms and conditions shall be subject to the delhi jurisdiction.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">OK</button>
                    </div>
                </div>
            </div>
        </div>


<script>

$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
    $('[data-toggle="tooltip"]').tooltip();
});
$(document).on('click','.secWarnBtn', function(){
    $('.secWarn').hide();
});
$(document).on('click','.firstWarnBtn', function(){
    $('.firstWarn').hide();
});
</script>
<?php 
  }
  else{
      echo "Case Number Not Found.";
  }

}
else{
    echo "Session Expired";
    ?>
       <script type='text/javascript'>window.location.href='<?=base_url()?>'</script>
    <?php
}
?>
