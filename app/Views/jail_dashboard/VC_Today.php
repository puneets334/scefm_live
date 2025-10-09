
<div class="x_panel panel panel-default">
    <div class="panel-body">
        <!-- Content Wrapper. Contains page content -->

        <br/>
        <h4 style="text-align: center;color: #31B0D5">

            View

            <?php if($temp_val==1){?>Today
            <?php }?>
            <?php if($temp_val==2){?>Week
            <?php }?>
            VC Details
        </h4>



        <!-- ----------------------------modal for view task------------------------------>



        <div class="box box-default" style="height: 0%;" >
            <div class="box-header with-border">
                <div class="row" style="margin-bottom: 20px;" id="dV_particular_day_2">

                    <h4 style="margin-left: 15px;" align="center">Total Number of

                        <?php if($temp_val==1){?>Today
                        <?php }?>
                        <?php if($temp_val==2){?>Week
                        <?php }?>

                        VC Details </h4>

                    <div class="col-md-12">

                        <table class="table table-bordered" id="tbl_example_1">
                            <thead>
                            <tr>
                                <th>S.N.</th>
                                <th>Diary No </th>
                                <th>Prisoner Name </th>
                                <?php if($temp_val==2){?>

                                    <th>VC Date</th>
                                <?php }?>
                                <th>Start Time </th>
                                <th>End Time </th>


                                <?php if($temp_val==1){?>
                                    <th></th>
                                <?php }?>

                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if(isset($get_All_Petitions_Details) && !empty($get_All_Petitions_Details )){
                                $j = 1;
                                foreach ($get_All_Petitions_Details as $value) {?>
                                    <tr>
                                        <td><?php echo $j; ?></td>
                                        <td><?php echo $value['diary_no'].'/'.$value['diary_year']; ?></td>
                                        <td><?php  echo $value['prisoner_name']."(".$value['prisoner_id'].")";?>
                                        </td>
                                    <?php if($temp_val==2){?>
                                        <td><?php  echo date('d-m-Y',strtotime($value['vc_date']));?> <?php }?>
                                        </td>
                                        <td><?php  echo $value['start_time'];?>
                                              </td>
                                        <td><?php echo $value['end_time']; ?></td>

                                        <?php if($temp_val==1){?>
                                            <td><button id="conect" class="btn bg-olive btn-flat" >Connect</button></td>
                                        <?php }?>
                                    </tr>
                                    <?php $j++; } }?>
                            </tbody>
                        </table>
                    </div>

                    <!-- /.col -->
                </div>
            </div>
        </div>
        <!-- Main content -->

    </div>

</div>

<script   type="text/javascript">
    $(document).ready(function() {

        $('#tbl_example, #tbl_example_1').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": true,
        });

        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            autoclose:'true'
        });



    });



</script>









