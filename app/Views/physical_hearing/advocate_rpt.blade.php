<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 27/8/20
 * Time: 12:20 PM
 */
//print_r ($list);
$crnt_dt=date("d-m-Y");

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<link rel="icon" href="<?=base_url('assets/sci_logo.png')?>" type="image/x-icon">
    <meta charset="UTF-8">
    <title>counsel Data List</title>
    <script src="<?=base_url()?>assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="<?=base_url()?>assets/js/bootstrap.min.js"></script>
    <script src="<?=base_url()?>assets/js/app.min.js"></script>
    <!--<script src="<?/*=base_url()*/?>assets/js/Reports.js"></script>-->
    <script src="<?=base_url()?>assets/jsAlert/dist/sweetalert.min.js"></script>
    <script src="<?=base_url()?>assets/plugins/datepicker/bootstrap-datepicker.js"></script>
    <script src="<?=base_url()?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="<?=base_url()?>assets/plugins/datatables/dataTables.buttons.min.js"></script>
    <script src="<?=base_url()?>assets/plugins/datatables/buttons.print.min.js"></script>
    <script src="<?=base_url()?>assets/plugins/datatables/buttons.colVis.min.js"></script>
    <script src="<?=base_url()?>assets/plugins/datatables/buttons.html5.min.js"></script>
    <script src="<?=base_url()?>assets/plugins/datatables/pdfmake.min.js"></script>
    <script src="<?=base_url()?>assets/plugins/datatables/vfs_fonts.js"></script>
    <link rel="stylesheet" href="<?=base_url()?>assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/plugins/datatables/jquery.dataTables.min.css">
    <link rel="stylesheet" href="<?=base_url()?>assets/plugins/datatables/buttons.dataTables.min.css">
    <!--<script src="<?/*=base_url()*/?>assets/js/fontawesome.js"></script>-->
    <script>

        $(function () {
            $('.datepick').datepicker({
                format: 'dd-mm-yyyy',
                todayHighlight: true,
                autoclose:true
            });
        });

        function Get_resultfunction(){
            var date_chk=document.getElementById("listing_dt").value;
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();


                $.ajax({
                    type: 'POST',
                    url: "<?=base_url()?>index.php/Advocate_listing/advocate_rpt_srch",
                    data:{CSRF_TOKEN: CSRF_TOKEN_VALUE , srch_date_data: date_chk },
                    //dataType: "json",
                    success: function(resultData) {
                         //return;
                        $('#show_result').html(resultData);

                        /*var rdata = JSON.parse(resultData);

                        var adata = rdata['data'];
                       /!* var data_length=rdata['length'];*!/
                        var i;
                        var showdata ='';
                        var count_court_no=0;
                        var cur_court_number=0;
                        var tot_rec=0;*/

                       /* showdata = showdata + "<thead>";
                        showdata = showdata +"<tr>";
                        showdata = showdata +"<th style=\"width:2%;\"></th>";
                        showdata = showdata +"<th style=\'width:5%;\' class=\'lead text-center\'>"+"Court No"+"</th>";
                        showdata = showdata +"<th style=\"width:5%;\" class=\"lead text-center\">"+"Item No"+"</th>";
                        showdata = showdata +"<th style=\"width:20%;\" class=\"lead text-center\">"+"Case No"+"</th>";
                        showdata = showdata +"<th style=\"width:10%;\" class=\"lead text-center\">"+"Attendee Name"+"</th>";
                        showdata = showdata +"<th style=\"width:5%;\" class=\"lead text-center\">"+"Mobile"+"</th></tr>";
                        showdata = showdata +"<th style=\"width:5%;\" class=\"lead text-center\">"+"Email"+"</th></tr>";
                        showdata = showdata + "</thead>";
                        showdata = showdata + "<tbody>";*/


                        // for(i=0 ; i< data_length ; i++){
                            /*for (var key in adata) {
                                tot_rec= tot_rec +1;
                                var rowdata = adata[key];
                                if(rowdata['id']!=cur_court_number){
                                    // alert(rowdata['PNO']);
                                    count_court_no=count_court_no+1;
                                    cur_court_number=rowdata['id'];
                                }
                                if(rowdata['id']==1){
                                    showdata = showdata +"<tr class='selected'";
                                } else{
                                    showdata = showdata +"<tr class=''";
                                }
                                var mid = "s"+rowdata['id'];
                                showdata = showdata +" id='"+mid+"'>";

                                showdata = showdata + "<td style=\"width:5%;\"></td>";
                                showdata = showdata +"<td>"+rowdata['court_number']+"</td>";
                                showdata = showdata +"<td>"+rowdata['item_number']+"</td>";
                                showdata = showdata +"<td>"+rowdata['case_number']+"</td>";
                                showdata = showdata +"<td>"+rowdata['name']+"</td>";
                                showdata = showdata +"<td>"+rowdata['description']+"</td>";
                                showdata = showdata +"<td>"+rowdata['mobile']+"</td>";
                                showdata = showdata +"<td>"+rowdata['email_id']+"</td></tr>";

                            }*/
                            /*showdata = showdata + "</tbody>";*/
                        //}
                    }//End of function success ..
                });
        }
    </script>
</head>
<body>
    <div id="show_result">
        <div class="col-sm-3" ></div>
        <!--<div class="col-sm-3" >
            <label for="tdate">Listing Date:</label>&nbsp
            <input type="text" class="datepick" id="listing_dt" placeholder="DD-MM-YY" name="listing_dt" maxlength="10" value="<?/*= $crnt_dt ; */?>">
        </div>
        <div class="col-sm-3" >
            <button class="btn btn-primary" onclick="Get_resultfunction()">Get Data</button>
        </div>-->

        <table id="head" style="display: none;">
            <thead>
            <tr>
                <th>#</th>
                <th >Search Court No</th>
                <th>Search by Item No</th>
                <th>Search by Case No</th>
            </tr>
            </thead>
        </table>

        <table id="reportTable1" class="table table-striped table-hover">
            <thead>
            <tr>
                <th style="width:2%;">#</th>
                <th style="width:5%;" class="lead text-center">List Date</th>
                <th style="width:5%;" class="lead text-center">Court No</th>
                <th style="width:5%;" class="lead text-center">Item No</th>
                <th style="width:20%;" class="lead text-center">Case No</th>
                <th style="width:10%;" class="lead text-center">Attendee Name</th>
                <th style="width:5%;" class="lead text-center">Attendee Type</th>
                <th style="width:5%;" class="lead text-center">Mobile</th>
                <th style="width:5%;" class="lead text-center">Email</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach($list as $value)
            {
                ?>
                <tr>
                    <th data-key="#" style="width:5%;">#</th>
                    <td data-key="List Date" class="lead text-center"><?= $value['next_dt']; ?> </td>
                    <td data-key="Court No" class="lead text-center"><?= $value['court_number']; ?> </td>
                    <td data-key="Item No" class="lead text-center"><?= $value['item_number'] ;?></td>
                    <td data-key="Case No" class="lead text-center"><?= $value['case_number'] ;?></td>
                    <td data-key="Attendee Name" class="lead text-center"><?= $value['name'] ;?></td>
                    <td data-key="Attendee Type" class="lead text-center"><?= $value['description'] ;?></td>
                    <td data-key="Mobile" class="lead text-center"><?= $value['mobile'] ;?></td>
                    <td data-key="Email" class="lead text-center"><?= $value['email_id'] ;?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <br>
    </div><!--END OF DIV id="show_result"-->
</body>
</html>
<script>

    $(document).ready(function () {
        $('#head thead tr').clone(true).prependTo('#reportTable1 thead');
        $('#reportTable1 thead tr:eq(0) th').each(function (i) {
            if (i != 0 && i != 5) {
                var title = $(this).text();
                var width = $(this).width();
                if (width > 200) {
                    width = width - 100;
                }
                else if (width < 100) {
                    width = width + 20;
                }
                $(this).html('<input type="text" style="width: ' + width + 'px" placeholder="' + title + '" />');

                $('input', this).on('keyup change', function () {
                    if (t.column(i).search() !== this.value) {
                        t
                            .column(i)
                            .search(this.value)
                            .draw();
                            $('#loader-wrapper').hide();
                    }
                });
            }
        });


        var t = $('#reportTable1').DataTable({
            "order": [[1, 'asc']],
            "ordering": false,
            "lengthMenu": [5],
            fixedHeader: true,
            scrollX: true,
            autoFill: true,
            dom: 'Brtip',
            buttons: [
                {
                    extend: 'print',
                    exportOptions: {
                        // columns: ':visible',
                        columns: [0, 1, 2, 3, 4],
                        stripHtml: false
                    }
                }
            ]



        });

        t.on('order.dt search.dt', function () {
            t.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                cell.innerHTML = i + 1;
                t.cell(cell).invalidate('dom');
            });
            $('#loader-wrapper').hide();
        }).draw();
    });
</script>

</body>
</html>
