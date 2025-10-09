<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?=$page_title?></title>
    <meta Http-Equiv="Cache-Control" Content="no-cache">
    <meta Http-Equiv="Pragma" Content="no-cache">
    <meta Http-Equiv="Expires" Content="0">


    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="<?=base_url()?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/css/AdminLTE.min.css">



</head>
<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">
    <!-- Full Width Column -->
    <div class="content-wrapper">
        <div class="container">
            <!-- Main content -->
            <section class="content">
                <ol class="breadcrumb">
                    <li><a href="<?php echo base_url() . 'index.php/Appearance'; ?>"><b>Home</b></a></li>
                    <li class="active" style="color:#F08080;"><b>Case List for Nominate Counsel</b></li>
                </ol>
                <div class="col-md-12">
                    <div class="row col-sm-12"><span  style="color:red;">To get special hearing passes, you can add/remove attendee upto 8 AM on the day of hearing.</span></div>

                </div><br><br>

                <?php

                if(sizeof($case_list)>0){
                $attributes = array("class" => "form-horizontal", "id" => "modeOfHearingForm", "name" => "modeOfHearingForm", "autocomplete"=>"off");
                echo form_open("Home", $attributes);?><!--
                <div class="col-md-4">
                <div>
                    <p class="margin">Select Listing date</p>
                    <div class="input-group input-group-sm">
                        <input type="date" name="list_date" id="list_date" class="form-control">
                        <span class="input-group-btn">
                      <button type="submit" class="btn btn-info btn-flat">Go!</button>
                    </span>
                    </div>
                </div><br/><br/>
                </div>-->
                <?php } ?>

                
                <?php
                    if(isset($case_list) && sizeof($case_list)>0){
                        echo '<div class="box-body no-padding">
                                <table class="table table-striped">
                                    <tbody><tr>
                                        <th style="width: 3%">#</th>
                                       <!-- <th>Diary No</th>
                                        <th>Court/Item Number</th>-->
                                        <th style="width:35%">Case Number</th>
                                        <th>No. of Cases</th>
                                        <th style="width:15%">Court # Item No</th>
                                        <th style="width:40%">Main case Details</th>
                                        <!--<th>Main/Connected</th>-->
                                        
                                        <th style="width:20%">Action</th>
                                        
                                    </tr>';
                        /*<td>'.$case['diary_no'].'</td>
                                                    <td>'.$case['court_number'].'/'.$case['item_number'].'</td>*/

                        $sno=0;

                        $CI =&get_instance();
                        $CI->load->model('Consent_model');
                        $CI1 =&get_instance();
                        $CI1->load->model('Hearing_model');


                        foreach ($case_list as $case){
                            $hearing_mode_court_direction=$case['consent'];
                            $last_consent_updated_advocate='';
                            $last_consent_updated_court_directed='';
                            $sno++; $rowHtml=null;

                            $arrContextOptions=array(
                                "ssl"=>array(
                                    "verify_peer"=>false,
                                    "verify_peer_name"=>false,
                                ),
                            );
                            $aud_nomination_status=(intval(file_get_contents(base_url().'index.php/consent/case_listed_in_daily_status/'.$case['diary_no'], false, stream_context_create($arrContextOptions))));

                            // echo 'case aud nomination status:'.$aud_nomination_status;
                            $consent_result=$CI->Consent_model->get_advocate_last_updated_consent($case['diary_no'],$case['list_number'],$case['list_year'],$this->session->loginData['bar_id'],$case['court_no']);


                            $consent_result1=$CI1->Hearing_model->get_court_directed_last_updated_consent($case['diary_no'],$case['list_number'],$case['list_year'],$case['court_no']);

                            $pChecked='checked';
                            $vChecked='';
                            $last_consent_updated_advocate=!empty($consent_result)?$consent_result[0]['consent']:'';
                            $last_consent_updated_court_directed=!empty($consent_result1)?$consent_result1[0]['consent']:'';
                            $source_advocate=!empty($consent_result)?$consent_result[0]['source']:'';
                            $source_court_directed=!empty($consent_result1)?$consent_result1[0]['source']:'';
                            //echo $last_consent_updated_advocate.'#'.$last_consent_updated_court_directed.'<br>';
                            $source = !empty($source_advocate)?$source_advocate:(!empty($source_court_directed)?$source_court_directed:'');
                            $case['source']=$source;
                            if($aud_nomination_status==1) {
                                $buttonHTML =anchor(base_url().'index.php/attendee/index/'.base64_encode(json_encode($case)), '<button type="button" class="btn btn-primary">Nominate Counsels</button>');
                            }
                            else
                            {
                                $buttonHTML='<button type="button" class="btn btn-danger"><strong>Case Listed for Today </strong></button>';
                            }
                            if($hearing_mode_court_direction=='P')
                            {
                                $label='Physical';
                                $buttonHTML.="<br><span style='color:red'>[Hon'ble court direction]</span>";
                            }

                                if(($last_consent_updated_advocate =='P' || $last_consent_updated_court_directed=='P') || 1==1)
                                {
                                    echo $rowHtml='<tr>
                                                    <td style="width: 20px">'.$sno.'</td>
                                                    <td>'.str_replace(' (M)','<span style="color:darkgreen"> (M)</span>',str_replace(' (C)','<span style="color:red"> (C)</span>',str_replace(",","<br/>",$case['case_no']))).'</td>
                                                    <td>'.$case['case_count'].'</td>
                                                    <td>'.$case['court_no'].' # '.$case['item_no'].'</td>
                                                    <td>'.$case['main_case_reg_no'].' @ '.$case['diary_no'].'<br>'.$case['cause_title'].'</td>
                                                    <td>'.$buttonHTML.'</td>
                                                  </tr>';
                                }
                                else{
                                    $rowHtml='';
                                }


                        }
                        echo '</tbody>
                                </table>
                                
                            </div>';
                        echo form_close();
                    }
                    else
                        echo "No case found.";
                ?>
            </section>
        </div>
    </div>
</div>
<!-- /.content-wrapper -->
<footer class="main-footer">
    <div class="container">
        <strong>Copyright &copy;  <a href="#">SCI Computer Cell</a>.</strong> All rights
        reserved.
    </div>
    <!-- /.container -->
</footer>
</div>
<script src="<?=base_url()?>assets/plugins/jQuery/jQuery.min.js"></script>
<script src="<?=base_url()?>assets/js/bootstrap.min.js"></script>
<script src="<?= base_url() ?>assets/js/angular.min.js"></script>

</body>

</html>
