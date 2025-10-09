<style>
    body {
        background-color: #ffffff !important;
    }
    div.dt-buttons {
        float: right !important;
    }
    /* Center all cells by default */
    #datatable-responsive th,
    #datatable-responsive td {
        text-align: center;
    }
    /* Left-align only the AOR Name column (2nd column) */
    #datatable-responsive th:nth-child(2),
    #datatable-responsive td:nth-child(2) {
        text-align: left !important;
    }
</style>
<br /><br /><br />
<div class="container row">
    <div class="col-sm-1"></div>
    <div class="col-sm-10">
        <h4 style="text-align: center;"> <?= $title = "Report of Partial Court Working Days " . date('Y'); ?></h4>
        <table id="datatable-responsive" class="display" width="80%" cellspacing="0">
            <thead>
                <tr>
                    <th>S.N.</th>
                    <th>AOR Name</th>
                    <th>No of Regular cases listed</th>
                    <th>Consent received for no. of Regular cases </th>
                    <th>No of Misc. cases listed</th>
                    <th>Consent received for no. of Misc. cases </th>
                    <th>Updated on</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $srNo = 1;
                if (!empty($vacation_advance_list_advocate)) {
                    $total_no_of_regular = $total_no_of_regular_concert = $total_no_of_miscellaneous = $total_no_of_miscellaneous_concert = 0;
                    $no_of_regular = $no_of_regular_concert = $no_of_miscellaneous = $no_of_miscellaneous_concert = 0;
                    foreach ($vacation_advance_list_advocate as $row) {
                        $total_no_of_regular += $row['total_no_of_regular'];
                        $total_no_of_regular_concert += $row['total_no_of_regular_concert'];
                        $total_no_of_miscellaneous += $row['total_no_of_miscellaneous'];
                        $total_no_of_miscellaneous_concert += $row['total_no_of_miscellaneous_concert'];
                        ?>
                        <tr>
                            <td><?= $srNo++; ?></td>
                            <td><?= $row['aor_name']; ?></td>
                            <td><?= $row['total_no_of_regular']; ?></td>
                            <td><?= $row['total_no_of_regular_concert']; ?></td>
                            <td><?= $row['total_no_of_miscellaneous']; ?></td>
                            <td><?= $row['total_no_of_miscellaneous_concert']; ?></td>
                            <td style="width: 15%;"><?= $row['updated_on']; ?></td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th></th>
                    <th></th>
                    <th>Total : <?= $total_no_of_regular; ?></th>
                    <th>Total : <?= $total_no_of_regular_concert; ?></th>
                    <th>Total: <?= $total_no_of_miscellaneous; ?></th>
                    <th>Total : <?= $total_no_of_miscellaneous_concert; ?></th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<script>
    $(document).ready(function() {
        var title = "<?= $title; ?>";
        var table = $('#datatable-responsive').DataTable({
            dom: 'lBfrtip',
            pageLength: 20,
            buttons: [{
                    extend: 'pdf',
                    title: title,
                    filename: 'Report_pdf_file_name',
                    orientation: 'landscape',
                    pageSize: 'LEGAL',
                    exportOptions: {
                        columns: ':visible',
                        format: {
                            body: function(data, row, column, node) {
                                // If S.N. column (index 0), override with index+1
                                return column === 0 ? row + 1 : data;
                            }
                        }
                    }
                },
                {
                    extend: 'excel',
                    title: title,
                    filename: 'Report_excel_file_name',
                    exportOptions: {
                        columns: ':visible',
                        format: {
                            body: function(data, row, column, node) {
                                return column === 0 ? row + 1 : data;
                            }
                        }
                    }
                },
                {
                    extend: 'csv',
                    filename: 'Report_csv_file_name',
                    exportOptions: {
                        columns: ':visible',
                        format: {
                            body: function(data, row, column, node) {
                                return column === 0 ? row + 1 : data;
                            }
                        }
                    }
                },
                {
                    extend: 'print',
                    title: title,
                    filename: 'Report_print_file_name',
                    exportOptions: {
                        columns: ':visible',
                        format: {
                            body: function(data, row, column, node) {
                                return column === 0 ? row + 1 : data;
                            }
                        }
                    }
                }
            ],
            columnDefs: [{
                targets: 0, // S.N. column index
                searchable: false,
                orderable: false,
                className: 'dt-body-center'
            }]
        });
        // Re-draw S.N. after sort/search/paginate
        table.on('order.dt search.dt draw.dt', function() {
            table.column(0, {
                search: 'applied',
                order: 'applied'
            }).nodes().each(function(cell, i) {
                cell.innerHTML = i + 1;
            });
        }).draw();
    });
</script>