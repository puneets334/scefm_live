<div class="x_panel">
    <div class="x_title"> <h3>Adjournment Letter <?= htmlentities(date('d-M-Y', strtotime($tabs_heading)), ENT_QUOTES) ?>
            <!--<span style="float:right;"> <a class="btn btn-info" type="button" onclick="window.history.back()" /> Back</a></span>-->
        </h3> </div>
    <div class="x_content">
        <div class="table-wrapper-scroll-y my-custom-scrollbar ">
            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%" style="color: black">
                <thead>
                <tr class="success input-sm" role="row" >
                    <th width="5%">Sl.No.</th>
                    <th width="15%">Case Number</th>
                    <th width="20%">Listed On</th>
                    <th width="10%">Court No./Item No.</th>
                    <th width="15">File</th>
                    <th width="25%">Reply Details</th>
                </tr>
                </thead>
                <tbody>
                <?php

                $i=1;
                //print_r($tableData);
                foreach($existing_requests as $index=>$requests){ ?>
                    <tr>
                        <td><?=$i?></td>
                        <td><?php if(!empty($requests->case_number)){ echo $requests->case_number.'<br/>';}
                           echo 'D. No.'.$requests->diary_number;?></td>

                        <td><?=date_format(date_create($requests->listed_date), 'D, jS M');?></td>

                        <td><?php if (!empty($requests->item_number)){ echo 'Item '.($requests->item_number).' '.$requests->court_number ;} ?></td>

                        <td><?php  $redirect_url=base_url('adjournment_letter/DefaultController/showFile/'.url_encryption($requests->id)); ?>
                            <div class="">
                                <a target="_blank" href="<?=$redirect_url?>"><i class="far fa-file-pdf">PDF</i></a>
                            </div>
                        </td>
                        <td>
                            <?php
                            if(!empty($requests->tbl_adjournment_details_id) && $requests->id==$requests->tbl_adjournment_details_id && !empty($requests->doc_id)){?>
                               <?php foreach ($requests->reply_list as $index=>$reply){
                                    if(!empty($reply->consent) && $reply->consent=='C'){
                                        $consent_type ='Concede';
                                    } else if(!empty($reply->consent) && $reply->consent=='O'){
                                        $consent_type ='Have Objection';
                                    }else{
                                        $consent_type ='';
                                    }
                                    $redirect_url=base_url('adjournment_letter/DefaultController/showFile_Others/'.url_encryption($reply->doc_id));

                                    ?>
                                    <?php
                                    echo ucwords(strtolower($reply->reply_first_name)).' '.$reply->created_at.'<br>'.$consent_type;
                                     if(!empty($reply->consent) && $reply->consent=='O') {
                                        echo '<a target="_blank" href="' . $redirect_url . '">PDF<i class="far fa-file-pdf"></i></a>';
                                         }

                                    ?>
                                    <br/>
                                    <?php }?>

                             <?php }?>
                        </td>

                    </tr>
                    <?php
                    $i++;
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $('.datepicker').datepicker({
        dateFormat: 'dd-mm-yy',
        todayHighlight: true,
        autoclose:true,
        minDate: 0
    });
</script>