<style>
    .buttons-print {
        margin-left: 75% !important;
        border-radius: 100px;
        margin-bottom: 10px !important;
        margin-top: 0 !important;
    }
</style>
<div class="row">
    <div class="col-lg-12">
        <div class="">
            <div class="row">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 mt-5">
                    <div class="">
                        <?php if(is_array($list)) { ?>
                            <div id="show_result" >
                                <?php if(!empty($list)) { ?>
                                    <p class="table_heading"><u>Consent for Dated : <?= $date_of_hearing; ?>,  Total Entries : <?= $case_count; ?></u></p>
                                <?php } ?>
                                <table id="datatable-res" class="table table-striped custom-table first-th-left dt-responsive nowrap" style="text-align: center;">
                                    <thead>
                                        <tr>
                                            <th>S. No.</th>
                                            <th>List Date</th>
                                            <th>Court No</th>
                                            <th>Item No</th>
                                            <th>Total Cases</th>
                                            <th>Consent given for Cases</th>
                                            <th>Mode of Hearing</th>
                                            <th>Updated On</th>
                                        </tr>
                                        <tr>
                                            <td>S. No.</td>
                                            <td>List Date</td>
                                            <td>Court No</td>
                                            <td>Item No</td>
                                            <td>Total Cases</td>
                                            <td>Consent given for Cases</td>
                                            <td>Mode of Hearing</td>
                                            <td>Updated On</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                        <?php
                                        if (isset($list) && !empty($list)) {
                                            foreach($list as $key => $value) {
                                                ?>
                                                <tr>
                                                    <td data-key="S. No."><?php echo $key+1; ?></td>
                                                    <td data-key="List Date"><?php echo !empty($value['next_dt']) ? date("d-m-Y", strtotime($value['next_dt'])) : ''; ?> </td>
                                                    <td data-key="Court No"><?php echo $value['court_no']; ?> </td>
                                                    <td data-key="Item No"><?php echo isset($value['item_number']) ? $value['item_number'] : '' ;?></td>
                                                    <td data-key="Total Cases"><?php echo $value['case_count'] ;?></td>
                                                    <td data-key="Consent given for Cases"><?php echo isset($value['consent_for_cases']) ? $value['consent_for_cases'] : '' ;?></td>
                                                    <td data-key="Mode of Hearing"><?php echo $value['consent'] ;?></td>
                                                    <td data-key="Updated On"><?php echo date("d-m-Y h:i", strtotime($value['updated_on'])); ?></td>
                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <td data-key="S. No."><?php echo '&nbsp;&nbsp&nbsp;&nbsp'; ?></td>
                                                <td data-key="List Date"><?php echo '&nbsp;&nbsp&nbsp;&nbsp'; ?></td>
                                                <td data-key="Court No"><?php echo '&nbsp;&nbsp&nbsp;&nbsp'; ?></td>
                                                <td data-key="Item No"><?php echo '&nbsp;&nbsp&nbsp;&nbsp'; ?></td>
                                                <td data-key="Total Cases" class="lead text-center"><?php echo '&nbsp;&nbsp&nbsp;&nbsp'; ?></td>
                                                <td data-key="Consent given for Cases"><?php echo '&nbsp;&nbsp&nbsp;&nbsp'; ?></td>
                                                <td data-key="Mode of Hearing"><?php echo '&nbsp;&nbsp&nbsp;&nbsp'; ?></td>
                                                <td data-key="Updated On"><?php echo '&nbsp;&nbsp&nbsp;&nbsp'; ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                                <br>
                            </div>
                            <?php
                        } else {
                            echo "Data Not Found! ";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        var table = $('#datatable-res').DataTable({
            dom: 'lBfrtip',
            'searching' : true,
            'paging' : true,
            ordering: false,
            buttons: ['print'],
            
            initComplete: function () {
                $('#datatable-res thead tr td').removeAttr('aria-sort');
            },
            drawCallback: function () {
                $('#datatable-res thead tr td').removeAttr('aria-sort');
            },
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
        });
        $('#datatable-res thead td').each( function () {
            var title = $('#datatable-res thead td').eq( $(this).index() ).text();
            $('#loader-wrapper').hide();
            $(this).html( '<input type="text" class="form-control cus-form-ctrl" placeholder="'+title+'" />' );
        } );
        $('#loader-wrapper').hide();
        $("#datatable-res thead input").on('keyup change', function () {
            table
                .column( $(this).parent().index()+':visible' )
                .search( this.value )
                .draw();
                $('#loader-wrapper').hide();
        } );   
    });
</script>