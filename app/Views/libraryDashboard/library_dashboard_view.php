<div class="right_col" role="main">
    <div id="page-wrapper">
        <!--<div id="page-wrapper" style="padding-left: 150px;">-->
        <?php echo $this->session->flashdata('msg'); ?>
        <div id="msg">
            <?php
            if (isset($_SESSION['MSG']) && !empty($_SESSION['MSG'])) { echo $_SESSION['MSG']; } unset($_SESSION['MSG']);
            ?>
        </div>
        <div class="row" >
            <!--<div class="row tile_count" >
                <?php
            //print_r($reference_count);
                foreach($reference_count as $index=>$countData){
                    if($countData['listing_date']==''){
                        $cnt_diary=$countData['total_reference'];
                    }

                    //echo $cnt_diary . "hulalalal";
                   // exit();*/
                    /*$textColor="";
                    if($index==0){
                        $textColor=" text-danger";
                    }
                    */?>
                    <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                        <?php
/*                        if ($countData['total_reference'] == 0) { */?>
                            <a href="#"> </a>
                            <?php
/*                        } else { */?>
                        <a  class="loadDataSci" data-date="<?/*=$countData['listing_date']*/?>">
                            <?php /*} */?>
                            <span class="count_top"><i class="glyphicon glyphicon-link"></i> <?php /*echo htmlentities(date('d-m-Y',strtotime($countData['listing_date'])), ENT_QUOTES); */?> </span>
                            <div class="count <?/*=$textColor*/?>"><?php /*echo htmlentities($countData['total_reference'], ENT_QUOTES); */?> </div>
                        </a>
                    </div>
                <?php  }
            //print_r($reference_count_today);
                foreach($reference_count_today as $index=>$countDataToday){
                    if($countDataToday['listing_date']==''){
                        $cnt_diaryToday=$countDataToday['total_reference'];
                    }
                }

                foreach($reference_count_future as $index=>$countDataFuture){
                    if($countDataFuture['listing_date']==''){
                        $cnt_diaryFuture=$countDataFuture['total_reference'];
                    }
                }



                ?>

            </div>-->
            <!--xxxxxxxxxxxxxxx-->
            <div class="col-sm-12" >
                <div class="col-sm-4" >
                    <div class="card">
                        <!--<img src="img_avatar.png" alt="Avatar" style="width:100%">-->
                        <!--<i class="fas fa-calendar-check"></i>-->
                        <a  class="loadDataSci" data-date="previous_dt">
                        <div class="container">
                            <h4><b>Previous Date</b><span class="badge" style="margin-left: 247px"><?= $cnt_diary ;?></span></h4>
                            <!--<p style="color: #7d9726"><b><?/*= $cnt_diary ;*/?></b></p>-->
                        </div>
                        </a>
                    </div>
                </div>
                <div class="col-sm-4" >
                    <div class="card">
                        <!--<img src="img_avatar.png" alt="Avatar" style="width:100%">-->
                        <a  class="loadDataSci" data-date="today_dt">
                        <div class="container">
                            <h4><b>Today Date</b><span class="badge" style="margin-left: 247px"><?= $cnt_diaryToday ;?></span></h4>
                            <!--<p style="color: #7d9726"><b>35</b></p>-->
                        </div>
                        </a>
                    </div>
                </div>
                <div class="col-sm-4" >
                    <div class="card">
                        <!--<img src="img_avatar.png" alt="Avatar" style="width:100%">-->
                        <a  class="loadDataSci" data-date="future_dt">
                        <div class="container">
                            <h4><b>Future Date</b><span class="badge" style="margin-left: 247px"><?= $cnt_diaryFuture ;?></span></h4>
                           <!-- <p style="color: #7d9726"><b>00</b></p>-->
                        </div>
                        </a>
                    </div>
                </div>
            </div>
            <!--/// table starts here -->
            <div class="col-md-12 col-sm-12 col-xs-12 " id="datatbl"   >
                <div class="x_panel">
                    <div class="x_title">
                        <h3 id="divTitle">
                            <!--<span style="float:right;"> <a class="btn btn-info" type="button" onclick="window.history.back()" /> Back</a></span>-->
                        </h3>
                    </div>
                    <div class="x_content">

                        <div class="table-wrapper-scroll-y my-custom-scrollbar ">
                            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <!-- <div class="dt-buttons">      <a class="dt-button buttons-pdf buttons-html5" tabindex="0" aria-controls="datatable-responsive" href="#"><span>PDF</span></a> <a class="dt-button buttons-excel buttons-html5" tabindex="0" aria-controls="datatable-responsive" href="#"><span>Excel</span></a> <a class="dt-button buttons-csv buttons-html5" tabindex="0" aria-controls="datatable-responsive" href="#"><span>CSV</span></a> <a class="dt-button buttons-print" tabindex="0" aria-controls="datatable-responsive" href="#"><span>Print</span></a> </div>-->
                                <thead>
                                <tr class="success input-sm" role="row" >
                                    <th style="width: 5%">#</th>
                                    <th style="width: 40%">Case Details</th>
                                    <th style="width: 30%">Citation</th>
                                    <th style="width: 15%">Refered By</th>
                                    <th style="width: 10%">Refered On</th>
                                    <!--<th style="width: 10%">Uploaded Order PDF (<i class="fa fa-file-pdf-o" style="font-size:16px"></i>)</th>-->
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div><!--END OF DIV id="datatbl"-->
        </div>

        <div class="clearfix"></div>
        <!--#################################-->
        <div class="col-md-12 col-sm-12 col-xs-12" id="divTableData">

            <?php
            //include("mentioning_details.php");
            ?>

        </div>


        <!--###################################################3-->

    </div>
</div>

<!--<script src="<?/*= base_url() . 'assets' */?>/vendors/jquery/dist/jquery.min.js"></script>--> <!--not required-->
<!--<link rel="stylesheet" href="<?/*= base_url() */?>assets/css/jquery-ui.css">-->
<script src="<?= base_url()?>assets/js/sha256.js"></script>
<!--<script src="<?/*= base_url() . 'assets' */?>/js/jquery.min.js"></script>--> <!--not required-->
<!--<script src="<?/*= base_url() . 'assets' */?>/js/jquery-ui.min.js"></script>-->
<script>
    $(document).ready(function() {
        $('#datatable-responsive').DataTable({
            dom: 'Bfrtip',
            buttons: [{
                extend: 'pdf',
                title: 'Library List',
                filename: 'Library_pdf_file_name'
            }, {
                extend: 'excel',
                title: 'Customized EXCEL Title',
                filename: 'Library_excel_file_name'
            }, {
                extend: 'csv',
                filename: 'Library_csv_file_name'
            }, {
                extend: 'print',
                filename: 'Library_print_file_name'
            }]

           /*buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]*/
        });
    });
</script>
<script type="text/javascript">
    $(".loadDataSci").click(function(e) {
        e.preventDefault();
        var form = $(this);
        var date = $(this).data("date");
        //alert(date);
        loadData(date);
    });
    function loadData(date) {
       // alert("jai bholenath" + date);
        //return;
        /*if(date == null || date=="") {
            return false;
        }*/
        /*var datearray = date.split("-");
        var newdate = datearray[2] + '-' + datearray[1] + '-' + datearray[0];*/
        //$('#divTitle').html('Citation for Date :'+newdate);
        var day=' Today ';
        if(date=='previous_dt'){
            var day=' Previous Dates ';
        }
        else if(date=='future_dt'){
            var day=' Future Dates ';
        }
        $('#divTitle').html('Citation for '+day);
        $.ajax({
            type: 'GET',
            url:  "<?php echo base_url('citation/CitationController/scitestapi'); ?>?chose_date=" + date,
            //url:  "<//?php echo base_url('citation/CitationController/scitestapi'); ?>?listing_date=" + date,
            contentType: "text/plain",
            dataType: 'json',
            success: function (data) {
                /*alert(data);
                console.log(data);
                return;*/
                myJsonData = data;
                //$("#datatable-responsive").DataTable().clear();

                populateDataTable(myJsonData);
            },
            error: function (e) {
                console.log("There was an error with your request...");
                console.log("error: " + JSON.stringify(e));
            }
        });
    }

    function populateDataTable(data) {

        console.log("populating data table...");
        // clear the table before populating it with more data
        var dateOfListing='';
        $("#datatable-responsive").DataTable().clear();
        var table = $('#datatable-responsive').DataTable();

        table
            .clear()
            .draw();
        var length = Object.keys(data.customers).length;
        // alert(length);
        for(var i = 0; i < length+1; i++) {
            var customer = data.customers[i];
            dateOfListing=customer.listing_date;
            if(customer.journal!=null){
                var journal_name='';
                if(customer.journal==1)
                    journal_name='AIR';
                else if(customer.journal==2)
                    journal_name='SCR';
                else if(customer.journal==3)
                    journal_name='SCC';
                else if(customer.journal==4)
                    journal_name='JT';
                else if(customer.journal==5)
                    journal_name='SC';

                var journal='<b>Journal: </b>'+ journal_name;}else{var journal='';}
            if(customer.journal_year!=null){var journal_year='<b> Journal Year: </b>'+ customer.journal_year;}else{var journal_year='';}
            if(customer.volume!=null){var volume='<b> Volume: </b>'+ customer.volume;}else{var volume='';}
            if(customer.suppl!=null){var suppl='<b> Suppl.: </b>'+ customer.suppl;}else{var suppl='';}
            if(customer.page_no!=null){var page_no='<b> Page No.: </b>'+ customer.page_no;}else{var page_no='';}
            if(customer.book_title!=null){var book_title='<b>Title: </b>'+ customer.book_title;}else{var book_title='';}
            if(customer.publisher_name!=null){var publisher_name='<b> Publisher Name: </b>'+ customer.publisher_name;}else{var publisher_name='';}
            if(customer.publication_year!=null){var publication_year='<b>Publisher Yr: </b>'+ customer.publication_year;}else{var publication_year='';}
            if(customer.subject!=null){var subject='<b> subject: </b>'+ customer.subject;}else{var subject='';}

            if(customer.journal!=null && customer.journal_year!=null){var j_jy_br='<br/>';}else{var j_jy_br='';}
            // You could also use an ajax property on the data table initialization suraj sr
            $('#datatable-responsive').dataTable().fnAddData( [
                i+1,

                '<b>Diary No: <b>'+customer.diary_no +' '+ customer.diary_year +'<br/>'+
                '<b>Registration No: <b>'+ customer.reg_no_display +'<br/>' +
                '<b>Causetitle: <b>'+ customer.cause_title +'<br/>'+' listing date: '+ customer.listing_date,

                journal + journal_year +j_jy_br+ volume + suppl + page_no +'<br/>'+ book_title +'<br/>'+
                publisher_name +'<br/>'+ publication_year + subject,

                customer.first_name +' '+ customer.last_name,
                customer.created_at,
            ]);
        }

    }

    //$(function() {
     //   alert("hualalalal");
      // var datatbl='<//?=json_encode($reference_details)?>';
      // var listing_date='<//?=$listing_date?>';
        //loadData(listing_date);
        //$("#datatable-responsive").DataTable();

        // Premade test data, you can also use your own

        /*$(".loadDataSci").click(function(e) {
            e.preventDefault();
            var form = $(this);
            var date = $(this).data("date");
            alert(date);
            loadData(date);
        });*/
        /*function loadData(date) {
            alert("jai bholenath" + date);
            return;
            /!*if(date == null || date=="") {
                return false;
            }*!/
            /!*var datearray = date.split("-");
            var newdate = datearray[2] + '-' + datearray[1] + '-' + datearray[0];*!/
            //$('#divTitle').html('Citation for Date :'+newdate);
            $('#divTitle').html('Citation for Date ');
            $.ajax({
                type: 'GET',
                url:  "<//?php //echo base_url('citation/CitationController/scitestapi'); ?>?chose_date=" + date,
                //url:  "<//?php //echo base_url('citation/CitationController/scitestapi'); ?>?listing_date=" + date,
                contentType: "text/plain",
                dataType: 'json',
                success: function (data) {
                    alert(data);
                    console.log(data);
                    return;
                    myJsonData = data;
                    populateDataTable(myJsonData);
                },
                error: function (e) {
                    console.log("There was an error with your request...");
                    console.log("error: " + JSON.stringify(e));
                }
            });
        }*/

        // populate the data table with JSON data
        /*function populateDataTable(data) {

            console.log("populating data table...");
            // clear the table before populating it with more data
            var dateOfListing='';
            $("#datatable-responsive").DataTable().clear();
            var length = Object.keys(data.customers).length;
           // alert(length);
            for(var i = 0; i < length+1; i++) {
                var customer = data.customers[i];
                dateOfListing=customer.listing_date;
                if(customer.journal!=null){var journal='<b>Journal: </b>'+ customer.journal;}else{var journal='';}
                if(customer.journal_year!=null){var journal_year='<b> Journal Year: </b>'+ customer.journal_year;}else{var journal_year='';}
                if(customer.volume!=null){var volume='<b> Volume: </b>'+ customer.volume;}else{var volume='';}
                if(customer.suppl!=null){var suppl='<b> Suppl.: </b>'+ customer.suppl;}else{var suppl='';}
                if(customer.page_no!=null){var page_no='<b> Page No.: </b>'+ customer.page_no;}else{var page_no='';}
                if(customer.book_title!=null){var book_title='<b>Title: </b>'+ customer.book_title;}else{var book_title='';}
                if(customer.publisher_name!=null){var publisher_name='<b> Publisher Name: </b>'+ customer.publisher_name;}else{var publisher_name='';}
                if(customer.publication_year!=null){var publication_year='<b>Publisher Yr: </b>'+ customer.publication_year;}else{var publication_year='';}
                if(customer.subject!=null){var subject='<b> subject: </b>'+ customer.subject;}else{var subject='';}

                if(customer.journal!=null && customer.journal_year!=null){var j_jy_br='<br/>';}else{var j_jy_br='';}
                // You could also use an ajax property on the data table initialization suraj sr
                $('#datatable-responsive').dataTable().fnAddData( [
                    i+1,

                    '<b>Diary No: <b>'+customer.diary_no +' '+ customer.diary_year +'<br/>'+
                    '<b>Registration No: <b>'+ customer.reg_no_display +'<br/>' +
                    '<b>Causetitle: <b>'+ customer.cause_title +'<br/>'+' listing date: '+ customer.listing_date,

                    '<td>' + journal + journal_year +j_jy_br+ volume + suppl + page_no +'<br/>'+ book_title +'<br/>'+
                    publisher_name +'<br/>'+ publication_year + subject,

                    customer.first_name +' '+ customer.last_name,
                    customer.created_at,
                ]);
            }

        }*/
   // })();

</script>

<style>

    #datatbl {
        /*margin-left: -145px;
        width: auto;*/
        margin-top: 30px;
    }

    /*.card {
        !* Add shadows to create the "card" effect *!
        box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
        transition: 0.3s;
    }*/

    /* On mouse-over, add a deeper shadow */
    .card:hover {
        box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
    }

    /* Add some padding inside the card container */
    .container {
        padding: 2px 16px;
    }


    .card {
        box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
        transition: 0.3s;
        border-radius: 5px; /* 5px rounded corners */
    }

    /* Add rounded corners to the top left and the top right corner of the image */
    img {
        border-radius: 5px 5px 0 0;
    }


    th{font-size: 13px;color: #000;}
    td{font-size: 13px;color: #000;}
    td .sci{font-size: 13px;color: #000;}

    
</style>
