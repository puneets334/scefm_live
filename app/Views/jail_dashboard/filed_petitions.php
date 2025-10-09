
<style>

    fieldset
    {
        border: 1px solid #ddd !important;
        margin: 0;
        xmin-width: 0;
        padding: 10px;
        position: relative;
        border-radius:4px;
        background-color:#f5f5f5;
        padding-left:10px!important;

    }

    legend
    {
        font-size:14px;
        font-weight:bold;
        margin-bottom: 0px;
        width: 40%;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 5px 5px 5px 10px;
        background-color: #ffffff;
    }


</style>
<div class="x_panel panel panel-default">
    <div class="panel-body">
       <!-- Content Wrapper. Contains page content -->
        <?php
        $attribute = array('class' => 'form-horizontal', 'name' => 'get_petition_details', 'id' => 'get_petition_details',
            'autocomplete' => 'off', 'method'=>'post');

        echo form_open(  base_url().'jail_dashboard/DefaultController/get_filed_petitions/1', $attribute);

        ?>
        <br/>
        <h4 style="text-align: center;color: #31B0D5">

                View Total

                <?php if($temp_val==1){?>Filed
                <?php }?>
                <?php if($temp_val==2){?>Pending
                <?php }?>
                <?php if($temp_val==3){?>Disposed
                <?php }?>
                Petitions
        </h4>



        <!-- ----------------------------modal for view task------------------------------>

            <?php if($temp_val==1){?>
            <div  class="row">
                <br/>
                <fieldset class="fieldset">
                    <legend class="legend">Filtering Criteria</legend>

                    <label for="inputname" class="col-sm-2 control-label"> Month :</label>
                <div class="col-xs-3">

                    <select name="month_id" class="form-control" width="50%">
                        <option value="0">Select Month</option>
                        <option value="1">January</option>
                        <option value="2">February</option>
                        <option value="3">March</option>
                        <option value="4">April</option>
                        <option value="5">May</option>
                        <option value="6">June</option>
                        <option value="7">July</option>
                        <option value="8">August</option>
                        <option value="9">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                    </select>

                </div>
                <label for="inputname" class="col-sm-1 control-label"> Year :</label>
                <div class="col-sm-2">

                    <select name="year_id" class="form-control" width="50%">
                        <option value="0">Select Year</option>
                        <?php
                        for ($i = 2020; $i <= 2050; $i++)
                            echo "<option value='".$i."'>$i</option>";
                        ?>
                    </select>

                </div>
                <div class="col-lg-3" style="float: right;">
                    <input type="Submit" class="btn bg-olive btn-flat" value="Submit" id="pet_refresh">

                </div>
                </fieldset>
            </div>
<?php }?>
            <br />
        <div class="box box-default" style="height: 0%;" >
            <div class="box-header with-border">
                <div class="row" style="margin-bottom: 20px;" id="dV_particular_day_2">

                    <h4 style="margin-left: 15px;" align="center">Total Number of

                        <?php if($temp_val==1){?>Filed
                        <?php }?>
                        <?php if($temp_val==2){?>Pending
                        <?php }?>
                        <?php if($temp_val==3){?>Disposed
                        <?php }?>
                        Petitions </h4>

                    <div class="col-md-12">

                        <table class="table table-bordered" id="tbl_example_1">
                            <thead>
                            <tr>
                                <th>S.N.</th>
                                <th>Diary No </th>
                                <th>Case Status </th>
                                <th>Cause Title </th>
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
                                        <td><?php  if($value['c_status']=="P")
                                            echo "<i style=color:green;> Pending </i>";
                                        elseif($value['c_status']=="D") echo  "<i style=color:Red;> Disposed </i>";;
                                        ?></td>
                                        <td><?php echo $value['Cause_title']; ?></td>
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
        <?php echo form_close();
        ?>
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









