
<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div id="msg">
                <?php
                if (isset($_SESSION['MSG']) && !empty($_SESSION['MSG'])) {
                    echo $_SESSION['MSG'];
                } unset($_SESSION['MSG']);
                ?></div>
        </div>
    </div>

    <div class="row">

            <div class="x_panel">

                    <div class="x_title">
                            <h2><i class="fa fa-newspaper-o"></i>Search Cases</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <?php
                         $today=date('Y-m-d');
                         $yesterday=date('Y-m-d', strtotime(' -1 day'));
                         $daterange=$yesterday.' to '.$today;
                         ?>

                        <div class="row">
                            <div class="col-sm-5 col-xs-12 hidden">
                                <div class="form-group">
                                    <label class="control-label col-sm-4">Search Diary Number:</label>
                                    <div class="col-sm-8">

                                            <input class="form-control"  id="diary_no"  name="diary_no"  placeholder="Search Diary Number..."  type="text">

                                    </div>

                                </div>

                            </div>

                            <div class="col-sm-7 col-xs-12">
                                <div class="col-sm-2 col-xs-12">
                                    <div class="form-group">

                                        <label class="control-label" style="margin-top:25px;">Search E-Filing:</label>

                                    </div>
                                </div>
                                <div class="form-group">

                                    <div class="col-sm-5"> <label class="control-label">E-F.Number:</label>
                                            <input class="form-control"  id="efiling_no"  name="efiling_no" minlength="2" placeholder="E-Filing Number..."  value="<?= $efiling_number ?>" type="text">

                                    </div>
                                    <div class="col-sm-2"><label class="control-label">Year:</label>
                                        <!--<input class="form-control"  id="efiling_year"  name="efiling_year" minlength="2" maxlength="4" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" placeholder="Year.."  type="text">-->
                                        <div class="input-group">
                                            <select tabindex = '25' class="form-control input-sm filter_select_dropdown" id="efiling_year" name="efiling_year" style="width: 100%">
                                                <option value="">Year</option>
                                                <?php
                                                $end_year = 48;
                                                for ($i = 0; $i <= $end_year; $i++) {
                                                    $year = (int) date("Y") - $i;

                                                    echo '<option ' . $sel . ' value=' .$year . '>' . $year . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                           <!-- -->

                        </div>
                        <br/>
                        <center><span class="error text-danger" id="efiling_error" style="display:none;">Please Enter E-Filing Number</span></center>
                        <hr/>

                            <br/><br/><hr/>
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-5">
                                    <div class="col-md-12 col-sm-12 col-xs-12" id="loader_div" style="display:none;">
                                        <img id="loader_img" style="position: fixed;left: 50%;margin-top: -50px;margin-left: -100px;" src="<?php echo base_url(); ?>/assets/images/loading-data.gif">
                                    </div>
                                    <div class="form-group" id="status_refresh">

                                        <input type="submit" id="Reportsubmit" name="add_notice" value="Search" class="btn btn-success loadDataReport_users_view">
                                        <button onclick="location.href = '<?php echo base_url('report'); ?>'" class="btn btn-primary" type="reset">Reset</button>
                                    </div>
                                </div>
                            </div>


            </div>
        </div>

    <!------------Table--------------------->

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">

            <div class="x_panel">
                <div class="x_title"> <h3 id="divTitle"></h3></div>
                <div class="x_content">
                    <div class="table-wrapper-scroll-y my-custom-scrollbar ">
                        <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                            <tr class="success input-sm" role="row" >
                                    <th width="6%">S.N.</th>
                                    <th width="15%">Filing No.</th>
                                    <th width="10%">Type</th>
                                    <th width="30%">Case Details</th>
                                    <th width="15%">Filed On</th>
                                    <th width="19%">Stages</th>
                            </tr>
                            </thead>
                        </table>

                    </div>
                </div>
            </div>
        </div>

    </div>
        <!------------Table--------------------->

</div>
</div>


<!-- Case Status modal-start-->
<link rel="stylesheet" href="<?= base_url() ?>assets/responsive_variant/templates/uikit_scutum_2/assets/css/main.min.css" />
<link type="text/css" rel="stylesheet" href="<?= base_url() ?>assets/responsive_variant/frameworks/uikit_3-4-1/css/uikit.min.css" />
<link rel="stylesheet" href="<?= base_url() ?>assets/responsive_variant/templates/uikit_scutum_2/assets/css/fonts/mdi_fonts.css" />
<link rel="stylesheet" href="<?= base_url() ?>assets/responsive_variant/templates/uikit_scutum_2/assets/css/materialdesignicons.min.css" />

<?php
$this->load->view('case_status/case_status_view');
?>
<script src="<?= base_url() ?>assets/responsive_variant/frameworks/uikit_3-4-1/js/uikit.min.js"></script>
<script src="<?= base_url() ?>assets/responsive_variant/frameworks/uikit_3-4-1/js/uikit-icons.min.js"></script>
<!-- Case Status modal-end-->


<script src="<?= base_url() . 'assets' ?>/vendors/jquery/dist/jquery.min.js"></script>
<script src="<?= base_url() . 'assets' ?>/js/jquery.min.js"></script>
<script src="<?= base_url() . 'assets' ?>/js/jquery-ui.min.js"></script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-datepicker.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/css/jquery-ui.css">
<script src="<?= base_url() ?>assets/js/bootstrap-datepicker.js"></script>
<script src="<?= base_url() ?>assets/js/bootstrap-datepicker.min.js"></script>
<script src="<?= base_url() ?>assets/js/sha256.js"></script>
<script src="<?= base_url() ?>assets/js/daterangepicker/daterangepicker.min.js"></script>
<script src="<?= base_url() ?>assets/js/daterangepicker/moment.min.js"></script>
<script src="<?= base_url() ?>assets/js/daterangepicker/daterangepicker.js"></script>
<script src="<?= base_url() ?>assets/js/bootstrap-datepicker.js"></script>
<script src="<?= base_url() ?>assets/js/bootstrap-datepicker.min.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>assets/css/daterangepicker/daterangepicker.css">


<script>
    window.onload=function(){
        var vx = '<?=$efiling_number?>';
        if(vx.length>10)
        {
            $("#Reportsubmit").click();
        }
    }

    $(document).ready(function() {

        $("#diary_no").autocomplete({
            source: "<?php echo base_url();?>report/search/search_diary_no",
            minLength: 2,
            select: function(event, ui) {
                $("#diary_no").val(ui.item.value);
                var v=ui.item.value;
                if(v){
                    $("#Reportsubmit").click();
                }

                }
        });
        $("#efiling_no_stop").autocomplete({
            source: "<?php echo base_url();?>report/search/search_efiling_no",
            minLength: 2,
            select: function(event, ui) {
                $("#efiling_no").val(ui.item.value);
                var v=ui.item.value;
                if(v){
                    $("#Reportsubmit").click();
                }
                }
        });
        $('.diary_date').daterangepicker ({
           /* autoclose: true,
            autoApply:true,
            showDropdowns: true,
            useCurrent: false,
            locale: {
                //format: 'YYYY-MM-DD HH:mm:ss',
                format: 'YYYY-MM-DD',
                separator: " to "
            }*/
            autoclose: true,
            autoApply:true,
            showDropdowns: true,
            //useCurrent: false,
            timePicker: true,
            startDate: moment().startOf('hour').add(24, 'hour'),
            //endDate: moment().startOf('hour').add(32, 'hour'),
            endDate: moment().startOf('hour').add(24, 'hour'),
            locale: {
               // format: 'YYYY-MM-DD hh:mm:ss',//
                 format: 'YYYY-MM-DD hh:mm:ss A',
                separator: " to "
            }
        });
        $('#listing_date_range').val('<?=$daterange;?>');
    });
</script>

<script>
    $(document).ready(function() {
        $('#datatable-responsive').DataTable({
            dom: 'Bfrtip',
            buttons: [{
                extend: 'pdf',
                title: 'Report List',
                filename: 'Report_pdf_file_name'
            }, {
                extend: 'excel',
                title: 'Report List',
                filename: 'Report_excel_file_name'
            }, {
                extend: 'csv',
                filename: 'Report_csv_file_name'
            }, {
                extend: 'print',
                title: 'Report List',
                filename: 'Report_print_file_name'
            }]

        });
    });
</script>
<script type="text/javascript">
    $(function() {
        //$('#status_refresh').hide();
        //$('#loader_div').show();
        var diary_no_Defult=$('#diary_no').val();
        var efiling_no_Defult=$('#efiling_no').val();
        var efiling_year_Defult=$('#efiling_year').val();
        var ActionFiledOn = $("input[name='ActionFiledOn']:checked").val();
        var listing_date=$('#listing_date_range').val();
        var stage_id_Defult = $("#stage_id option:selected").val();
        var filing_type_id_Defult = $("#filing_type_id option:selected").val();
        var users_id_Defult = $("#users_id option:selected").val();
        //loadData(ActionFiledOn,listing_date,stage_id_Defult,filing_type_id_Defult,users_id_Defult,diary_no_Defult,efiling_no_Defult,efiling_year_Defult);
        $("#datatable-responsive").DataTable();


        //loadDataReport_users_view
        $(".loadDataReport_users_view").click(function(e) {
            e.preventDefault();
            // $('#status_refresh').hide();
            // $('#loader_div').show();
            //alert('weldone');
            var diary_no=$('#diary_no').val();
            var efiling_no=$('#efiling_no').val();
            var efiling_year=$('#efiling_year').val();
            if(efiling_no == null || efiling_no=="") {
                if(diary_no == null || diary_no=="") {
                    //alert('weldone efiling_no');
                    $('#efiling_error').show();
                    $("#datatable-responsive").DataTable().clear();
                    var table = $('#datatable-responsive').DataTable();

                    table
                        .clear()
                        .draw();
                    return false;
                }
            }else if(diary_no == null || diary_no=="") {
                if(efiling_no == null || efiling_no=="") {
                    //alert('weldone diary_no');
                    $('#efiling_error').show();
                    $("#datatable-responsive").DataTable().clear();
                    var table = $('#datatable-responsive').DataTable();

                    table
                        .clear()
                        .draw();
                    return false;
                }
            }
            $('#efiling_error').hide();
            var ActionFiledOnGet ='All';
            var date='<?=$daterange;?>';
            var stage_id ='All';
            var filing_type_id = 'All';
            var users_id = 'All';
           //alert(efiling_no);
            loadData(ActionFiledOnGet,date,stage_id,filing_type_id,users_id,diary_no,efiling_no,efiling_year);
        });


        //end loadDataReport_users_view
        // Premade test data, you can also use your own

        $(".loadDataReport").click(function(e) {
            e.preventDefault();
            var diary_no=$('#diary_no').val();
            var efiling_no=$('#efiling_no').val();
            var efiling_year=$('#efiling_year').val();

            var ActionFiledOnGet = $("input[name='ActionFiledOn']:checked").val();
            var date=$('#listing_date_range').val();
            var stage_id = $("#stage_id option:selected").val();
            var filing_type_id = $("#filing_type_id option:selected").val();
            var users_id = $("#users_id option:selected").val();

            loadData(ActionFiledOnGet,date,stage_id,filing_type_id,users_id,diary_no,efiling_no,efiling_year);
        });
        function loadData(ActionFiledOn,date,stage_id,filing_type_id,users_id,diary_no,efiling_no,efiling_year) {
            if(date == null || date=="") {
                return false;
            }
            var datearray = date.split("to");
            var fromDateGet = datearray[0];
            var from_Date = fromDateGet.split("-");
            var fromDate = from_Date[2]+'-'+from_Date[1]+'-'+from_Date[0];

            var toDateGet = datearray[1];
            var to_Date = toDateGet.split("-");
            var toDate = to_Date[2]+'-'+to_Date[1]+'-'+to_Date[0];
            if(ActionFiledOn !='All') {
                $('#divTitle').html('Report for Date :' + fromDate + ' TO ' + toDate);
            }

            $.ajax({
                type: 'GET',
                url:  "<?php echo base_url('efiling_search/DefaultController/search'); ?>?DateRange="+date+'&ActionFiledOn=' + ActionFiledOn+'&stage_id=' + stage_id+'&filing_type_id=' + filing_type_id+'&users_id=' + users_id+'&diary_no=' + diary_no+'&efiling_no=' + efiling_no+'&efiling_year=' + efiling_year,
                contentType: "text/plain",
                dataType: 'json',
                success: function (data) {
                    myJsonData = data;
                    populateDataTable(myJsonData);
                },
                error: function (e) {
                    console.log("There was an error with your request...");
                    console.log("error: " + JSON.stringify(e));
                }
            });
        }

        // populate the data table with JSON data
        function populateDataTable(data) {

            $("#datatable-responsive").DataTable().clear();
            var table = $('#datatable-responsive').DataTable();

            table
                .clear()
                .draw();
            var diary_no_m=''; var diary_year_m=''; var open_case_status=''; var reg_no='';
            var length = Object.keys(data.customers).length;
            var redirect_url = ''; var efiling_no=''; var rd=''; var id=''; var res_name=''; var pet_name=''; var cause_title=''; var cause_details=''; var diary_no=''; var diary_year='';
            var open_case_view='href="#" onClick="open_case_view()"';
                for(var i = 0; i < length+1; i++) {
                    var sn=i+1;
                    var report = data.customers[i];
                    var stage_id=report.stage_id;

                    if(report.cause_title!=null){cause_title=report.cause_title; }
                    else if(report.ecase_cause_title!=null){cause_title=report.ecase_cause_title; }

                    if(report.pet_name!=null){pet_name=report.pet_name+'Vs.';}else{ pet_name=''; }

                    if(report.res_name!=null){res_name=report.res_name;} else{res_name='';}
                    if(report.diary_no!=null){diary_no='<b class="sci">Diary No: </b>'+report.diary_no+'/'; diary_no_m=report.diary_no;}else if(report.sc_diary_num!=null){diary_no='<b class="class="sci"">Diary No: </b>'+report.sc_diary_num+'/'; diary_no_m=report.sc_diary_num;}
                    if(report.diary_year!=null){diary_year=report.diary_year+'<br/>'; diary_year_m=report.diary_year;}else if(report.sc_diary_year!=null){diary_year=report.sc_diary_year+'<br/>'; diary_year_m=report.sc_diary_year;}
                    if (report.reg_no_display != '' && report.sc_display_num !=null) {
                        reg_no = '<b>Registration No.</b> : ' +report.sc_display_num+ '<br/> ';
                    } else {
                        reg_no = '';
                    }
                    if(diary_no_m !=null && diary_year_m !=null){
                         open_case_status='href="#" onClick="open_case_status()"';
                    }else{ open_case_status='';}
                    cause_details= '<a '+open_case_status+' data-diary_no="'+diary_no_m+'" data-diary_year="'+diary_year_m+'">'+'<span class="sci">'+diary_no + diary_year + reg_no + cause_title+'<br/>'+pet_name+res_name+'</span>'+'</a>';

                    if(report.efiling_type !='' && report.efiling_type=='new_case') {
                        rd='newcase.defaultController'; //. equal to / required
                        id='/'+report.registration_id + '/' + report.ref_m_efiled_type_id + '/' + report.stage_id + '/' + report.efiling_type;
                    }
                    else if(report.efiling_type !='' && report.efiling_type=='misc_document') {
                    rd='miscellaneous_docs.DefaultController'; //. equal to / required
                        id='/'+report.registration_id + '/' + report.ref_m_efiled_type_id + '/' + report.stage_id + '/' + report.efiling_type;
                    }
                    else if(report.efiling_type !='' && report.efiling_type=='IA') {
                    rd='IA.DefaultController'; //. equal to / required
                        id='/'+report.registration_id + '/' + report.ref_m_efiled_type_id + '/' + report.stage_id + '/' + report.efiling_type;
                    }
                    else if(report.efiling_type !='' && report.efiling_type=='CAVEAT') {
                    rd='case.caveat.crud'; //. equal to / required
                        id='/'+report.registration_id + '/' + report.ref_m_efiled_type_id + '/' + report.stage_id + '/' + report.efiling_type;
                    }
                    redirect_url = "<?=base_url('efiling_search/identify')?>"+id;
                    efiling_no= '<a  id="'+id+'" href="'+redirect_url+'">'+ report.efiling_no + '</a>';
                    /*efiling_no= '<a  id="'+id+'" onClick="open_case_view(this.id)">'+ report.efiling_no + '</a>';*/

                    var str_efiling_type=report.efiling_type;
                    var efiling_type=str_efiling_type.replace("_"," ");
                    //var date = report.create_on;
                    var date = report.activated_on;
                    var from_date = date.split(' '); var onlytime =from_date[1];

                    var now = onlytime.split(':'); var hours = now[0];
                    var ampm = hours >= 12 ? 'pm' : 'am';
                    date = $.datepicker.formatDate("dd/mm/yy", $.datepicker.parseDate('yy-mm-dd', date));
                    date=date+' '+onlytime+' '+ampm;
                    $('#datatable-responsive').dataTable().fnAddData( [
                        sn,
                        efiling_no,
                        efiling_type,
                        cause_details,
                        date,
                        report.admin_stage_name,
                    ]);

                }

        }
    });

</script>
<script>

   function open_case_view(search){
       var CSRF_TOKEN = 'CSRF_TOKEN';
       var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
       var search = search;
       //alert(search);
       $.ajax({
           type: "POST",
           data: {CSRF_TOKEN: CSRF_TOKEN_VALUE,search:search},
           beforeSend: function (xhr) {
               $("#view_case_status_data").html("<div style='margin:0 auto;margin-top:20px;width:100%;text-align: center;'><img src='<?=base_url()?>/assets/images/loading-data1.gif'></div>");
           },
           url: "<?php echo base_url('efiling_search/identify'); ?>"+search,
           success: function (data) {
               var resArr = data.split('@@@');

               if (resArr[0] == 1) {
                   alert(resArr[0]);
                   alert(resArr[1]);
                  // var win = window.open();
                   /*win.document.write('<iframe width="560" height="315" src="https://www.w3schools.com/" frameborder="0" allowfullscreen></iframe>')*/
                   /*window.location = '<//?=base_url('efiling_search/view/');?>' + resArr[1];*/
                   var src='<?=base_url("efiling_search/view/");?>' + resArr[1];
                   window.open(src);
               }
              // document.getElementById('view_case_status_data').innerHTML=data;
               $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function (result) {
                   $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
               });
           },
           error: function (result) {
               $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                   $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
               });
           }
       });
   }

   function open_case_status(){

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        var diary_no = $("a:focus").attr('data-diary_no');
        var diary_year = $("a:focus").attr('data-diary_year');
        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE,diary_no:diary_no, diary_year:diary_year},
            beforeSend: function (xhr) {
                $("#view_case_status_data").html("<div style='margin:0 auto;margin-top:20px;width:100%;text-align: center;'><img src='<?=base_url()?>/assets/images/loading-data1.gif'></div>");
            },
            url: "<?php echo base_url('report/search/showCaseStatusReport'); ?>",
            success: function (data) {
                document.getElementById('view_case_status_data').innerHTML=data;
                $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            },
            error: function (result) {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
        UIkit.modal('#case_status').toggle();
   }
</script>
<style>
    th{font-size: 13px;color: #000;}
    td{font-size: 13px;color: #000;}
    td .sci{font-size: 13px;color: #000;}
</style>

