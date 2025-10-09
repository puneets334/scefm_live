<style>.select2-container {width: 100% !important;}</style>

<div class="panel panel-default">
    <div class="panel-body">
        <div id ="act_section_list_data" class="col-md-12 col-sm-12 col-xs-12">
            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                <thead>
                <tr class="success">
                    <th style="width: 5%">#</th>
                    <th style="width: 20%">INDEX ID</th>
                    <th style="width: 20%">Created On</th>
                    <th style="width: 20%">Action</th>

                </tr>
                </thead>
                <tbody>
                <?php

                $i = 1;
                foreach ($prefilledIndx_listView as $indx_list) {

                    ?>
                    <tr>
                        <td><?php echo_data($i++); ?></td>
                        <td><?php echo_data($indx_list['index_id']); ?></td>
                        <td><?php echo_data($indx_list['created_on']); ?></td>
                        <!--<td><a onclick="IndxEdit_section(<?php /*echo "'$Indx_Id_list'";*/?>)" class="btn btn-success btn-xs"><i class="fa fa-pencil"></i></a></td>-->
                        <td><a href="<?php echo base_url('supplements/Prefilled_index_controller/indexData_Edit/?Indx_No_edit=').$indx_list['index_id'].'&doc_type= '.'EDITInsrt' ?>" class="btn btn-success btn-xs"><i class="fa fa-pencil"></i></a></td>


                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>




