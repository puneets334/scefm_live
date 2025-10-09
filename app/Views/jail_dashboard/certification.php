
<div class="x_panel panel panel-default">
    <div class="panel-body">
        <!-- Content Wrapper. Contains page content -->

        <br/>
        <h4 style="text-align: center;color: #31B0D5">

            View

            <?php if($type=="cc" && $status=='p')  {?>Pending Custody Cetificate
            <?php }?>
            <?php if($type=="cc" && $status=='d'){?>Draft  Custody Cetificate
            <?php }?>
            <?php if($type=="cc" && $status=='c'){?>Completed Custody Cetificate
            <?php }?>
            <?php if($type=="sc" && $status=='p')  {?>Pending Surrender Cetificate
            <?php }?>
            <?php if($type=="sc" && $status=='d'){?>Draft  Surrender Cetificate
            <?php }?>
            <?php if($type=="sc" && $status=='c'){?>Completed Surrender Cetificate
            <?php }?>
        </h4>



        <!-- ----------------------------modal for view task------------------------------>



        <div class="box box-default" style="height: 0%;" >
            <div class="box-header with-border">
                <div class="row" style="margin-bottom: 20px;" id="dV_particular_day_2">

                    <h4 style="margin-left: 15px;" align="center">Total Number of

                        <?php if($type=="cc" && $status=='p')  {?>Pending Custody Cetificate Details
                        <?php }?>
                        <?php if($type=="cc" && $status=='d'){?>Draft  Custody Cetificate Details
                        <?php }?>
                        <?php if($type=="cc" && $status=='c'){?>Completed Custody Cetificate Details
                        <?php }?>
                        <?php if($type=="sc" && $status=='p')  {?>Pending Surrender Cetificate Details
                        <?php }?>
                        <?php if($type=="sc" && $status=='d'){?>Draft  Surrender Cetificate Details
                        <?php }?>
                        <?php if($type=="sc" && $status=='c'){?>Completed Surrender Cetificate Details
                        <?php }?>
                         </h4>

                    <div class="col-md-12">

                        <table class="table table-bordered" id="tbl_example_1">
                            <thead>
                            <tr>
                                <th>S.N.</th>
                                <th>Diary_No </th>
                                <th>Prisoner Name </th>
                                <?php if($status=='c'){?>
                                    <th></th>
                                <?php }?>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if(isset($total_custody_details) && !empty($total_custody_details )){
                                $j = 1;
                                foreach ($total_custody_details as $value) {?>
                                    <tr>
                                        <td><?php echo $j; ?></td>
                                        <td><?php echo $value['diary_no'].'/'.$value['diary_year']; ?></td>
                                        <td><?php  echo $value['prisoner_name']." (".$value['prisoner_id'].")";?>
                                        </td>
                                        <?php if($status=='c'){?>
                                            <td><button id="upload" class="btn bg-olive btn-flat" >Upload</button></td>
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









