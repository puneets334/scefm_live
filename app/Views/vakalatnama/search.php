<style>.pointer {cursor: pointer;}</style>
<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div id="msg"><?php echo $this->session->flashdata('msg');?></div>
        </div>
    </div>
    <div class="row">

        <div class="x_panel">

            <div class="x_title">
                    <h2><i class="fa fa-newspaper-o"></i>Search Vakalatnama Cases</h2>
                <div class="clearfix"></div>
            </div>
            <?php
            $attribute = array('class' => 'form-horizontal', 'id' => 'search_case_vakalatnama', 'name' => 'search_case_vakalatnama', 'autocomplete' => 'off');
            echo form_open('vakalatnama/DefaultController/search', $attribute);
            ?>
            <div class="x_content">
                <!--start akg-->

                <div id="widgets-container" ng-init="widgets.recentDocuments.byOthers.ifVisible=true;" class="uk-child-width-1-1 uk-child-width-1-3@s uk-child-width-1-3@l ukchild-width-1-4@xl ukmargin-medium-top uk-grid-medium ukflex-between uk-grid" uk-grid="">

                    <div ng-show="widgets.recentDocuments.byOthers.ifVisible" class="uk-first-column">
                        <label class="radio-inline input-lg">
                        <div class="documents-widget">
                            <div class="uk-flex-middle uk-grid-medium uk-grid uk-grid-stack pointer" style="text-decoration:none;padding:1.6rem 1.5rem 1.2rem 1rem;" uk-grid=""  class="tablink" onclick="openSearch('ShowCases', this, 'uk-label-primary')" id="defaultOpen">

                                <div>
                                    <span class="sc-padding sc-padding-small-ends uk-text-bold uk-text-large"></span>
                                </div>
                                <div class="uk-first-column">
                                    <div>
                                        <input type="radio" name="search_filing_type" value="register">
                                        <span class="uk-text-bold uk-text-primary uk-text-uppercase">Search by Case number</span>

                                    </div>

                                </div>
                            </div>
                        </div>
                        </label>
                    </div>
                    <div class="defects-widget-container">
                        <label class="radio-inline input-lg">
                        <div class="defects-widget">
                            <div class="uk-flex-middle uk-grid-medium uk-grid uk-grid-stack pointer" style="text-decoration:none;padding:1.6rem 1.5rem 1.2rem 1rem;" uk-grid="" onclick="openSearch('ShowEfilingRequests', this, 'danger')">
                                <div>
                                    <span class="sc-padding sc-padding-small-ends uk-text-bold uk-text-large"></span>
                                </div>
                                <div class="uk-first-column">
                                    <input type="radio" checked name="search_filing_type" value="efilingNO">
                                    <span class="uk-text-bold uk-text-danger uk-text-uppercase">Search by E-filing Number</span>

                                </div>
                            </div>
                        </div>
                        </label>
                    </div>
                    <div>
                        <div class="defects-widget-container">
                            <label class="radio-inline input-lg">
                        <div class="applications-widget">

                            <div class="uk-flex-middle uk-grid-medium uk-grid uk-grid-stack pointer" style="text-decoration:none;padding:1.6rem 1.5rem 1.2rem 1rem;" uk-grid="" onclick="openSearch('ShowDiaryRequests', this, 'uk-label-warning')">
                                <div>
                                    <span class="sc-padding sc-padding-small-ends uk-text-bold uk-text-large"></span>
                                </div>
                                <div class="uk-first-column">
                                    <input type="radio"  name="search_filing_type" value="diary" >
                                    <span class="uk-text-bold uk-text-warning uk-text-uppercase">Search by Diary Number</span>
                                </div>
                            </div>
                            </label>
                        </div>
                        </div>
                    </div>

                </div>
                <br/> <br/> <br/> <br/> <br/> <br/>
                <!--end akg-->
                <div class="row">
                    <div class="col-sm-12 col-xs-12 tabcontent" id="ShowCases">
                        <div class="col-sm-5 col-xs-12 col-sm-offset-1" >
                            <div class="form-group">
                                <label class="control-label col-sm-4 col-xs-12 input-lg"> Case Type <span style="color: red">*</span>:</label>
                                <div class="col-md-4 col-sm-12 col-xs-12">
                                    <select name="sc_case_type" id="sc_case_type" class="form-control input-lg filter_select_dropdown"  style="width:100%;" required>
                                        <option value="" title="Select">Select Case Type</option>
                                        <?php
                                        if (count($sc_case_type)) {
                                            foreach ($sc_case_type as $dataRes) {
                                                $sel = ($new_case_details[0]['sc_case_type_id'] == (string) $dataRes->casecode ) ? "selected=selected" : '';
                                                ?>
                                                <option <?php echo $sel; ?> value="<?php echo url_encryption(trim($dataRes->casecode)); ?>"><?php echo_data($dataRes->casename); ?> </option>;
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-5 col-xs-12" >
                            <div class="form-group">
                            <label class="control-label col-sm-4">Case Number <span style="color: red">*</span>:</label>
                            <div class="col-sm-4">
                                <input class="form-control"  id="case_no"  name="case_no"  placeholder="Case Number..."  type="text" required>

                            </div>
                            <div class="col-sm-2">
                                <div class="input-group">
                                    <select tabindex = '25' class="form-control input-sm filter_select_dropdown" id="case_year" name="case_year" style="width: 100%" required>

                                        <?php
                                        $end_year = 48;
                                        for ($i = 0; $i <= $end_year; $i++) {
                                            $year = (int) date("Y") - $i;
                                            $sel = ($year == ((int) date("Y"))) ? 'selected=selected' : '';
                                            echo '<option ' . $sel . ' value=' .$year . '>' . $year . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>

                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xs-12 tabcontent" id="ShowEfilingRequests">
                        <div class="form-group">
                            <label class="control-label col-sm-4">E-Filing Number <span style="color: red">*</span>:</label>
                            <div class="col-sm-6">
                                <input class="form-control"  id="efiling_no"  name="efiling_no"  placeholder="E-Filing Number..."  type="text">

                            </div>

                        </div>
                    </div>

                    <div class="col-sm-6 col-xs-12 tabcontent" id="ShowDiaryRequests">
                        <div class="form-group">
                            <label class="control-label col-sm-4"> Diary Number <span style="color: red">*</span>:</label>
                            <div class="col-sm-4">
                                <input class="form-control"  id="diary_no"  name="diary_no"  placeholder="Diary Number..."  type="text">
                            </div>
                            <div class="col-sm-2">
                                <div class="input-group">
                                    <select tabindex = '25' class="form-control input-sm filter_select_dropdown" id="diary_year" name="diary_year" style="width: 100%">

                                        <?php
                                        $end_year = 48;
                                        for ($i = 0; $i <= $end_year; $i++) {
                                            $year = (int) date("Y") - $i;
                                            $sel = ($year == ((int) date("Y"))) ? 'selected=selected' : '';
                                            echo '<option ' . $sel . ' value=' .$year . '>' . $year . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="form-group">
                        <div class="col-md-offset-5">
                            <button type="submit" class="btn btn-primary" id="search_case_vakalatnama" name="vakalatnama" value="SEARCH">SEARCH</button>
                        </div>
                    </div>

                </div>
            <?php echo form_close(); ?>


            </div>
        </div>



        <!------------Table--------------------->


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
<link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-datepicker.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="<?= base_url() ?>assets/css/jquery-ui.css">
<script src="<?= base_url() ?>assets/js/sha256.js"></script>

<script>
    function openSearch(cityName,elmnt,color) {
        //alert(cityName);
        var search_filing_type = $("input[name='search_filing_type']:checked").val();
        if(search_filing_type=='register'){
            $("#sc_case_type").prop('required',true);
            $("#case_no").prop('required',true);
            $("#case_year").prop('required',true);

            $("#efiling_no").prop('required',false);
            $("#diary_no").prop('required',false);
            $("#diary_year").prop('required',false);
        }else if(search_filing_type=='efilingNO'){
            $("#efiling_no").prop('required',true);

            $("#sc_case_type").prop('required',false);
            $("#case_no").prop('required',false);
            $("#case_year").prop('required',false);
            $("#diary_no").prop('required',false);
            $("#diary_year").prop('required',false);

        }else if(search_filing_type=='diary'){
            $("#diary_no").prop('required',true);
            $("#diary_year").prop('required',true);


            $("#sc_case_type").prop('required',false);
            $("#case_no").prop('required',false);
            $("#case_year").prop('required',false);
            $("#efiling_no").prop('required',false);
        }

        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablink");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].style.backgroundColor = "";
        }
        document.getElementById(cityName).style.display = "block";
        elmnt.style.backgroundColor = color;

    }
    // Get the element with id="defaultOpen" and click on it
    document.getElementById("defaultOpen").click();
</script>

<script type="text/javascript">
    $(function() {

        $(".loadDataReport").click(function(e) {
            e.preventDefault();
            alert('welcome click =Search Case Number submit');
            var case_no=$('#case_no').val();
            if(case_no == null || case_no=="") {
                alert('Please enter the case number');
                return false;
            }
            var case_year=$('#case_year').val();
            var efiling_no='All';
            var diary_no='All';
            var diary_year='All';

            var search_type='case_no_year';

            loadData(search_type,diary_no,diary_year,efiling_no,case_no,case_year);
        });
        //SearchDiaryNumbersubmit
        $(".SearchDiaryNumbersubmit").click(function(e) {
            e.preventDefault();
            alert('welcome click =Search Diary Number submit');
            var diary_no=$('#diary_no').val();
            if(diary_no == null || diary_no=="") {
                alert('Please enter the diary number');
                return false;
            }
            var diary_year=$('#diary_year').val();
            var efiling_no='All';
            var case_no='All';
            var case_year='All';
            var search_type='Diary';

            loadData(search_type,diary_no,diary_year,efiling_no,case_no,case_year);
        });
        $(".SearchEfilingNumbersubmit").click(function(e) {
            e.preventDefault();
            alert('welcome click =Search Efiling Number submit');
            var efiling_no=$('#efiling_no').val();

            if(efiling_no == null || efiling_no=="") {
                alert('Please enter the e-filing number');
                return false;
            }
            var diary_no='All';
            var diary_year='All';
            var case_no='All';
            var case_year='All';
            var search_type='efiling';

            loadData(search_type,diary_no,diary_year,efiling_no,case_no,case_year);
        });


        //end loadDataReport_users_view
        // Premade test data, you can also use your own


        function loadData(search_type,diary_no,diary_year,efiling_no,case_no,case_year) {

           $('#divTitle').html('');
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            $.ajax({
                type: "POST",
                data: {CSRF_TOKEN: CSRF_TOKEN_VALUE,search_type:search_type,diary_no: diary_no,diary_year:diary_year,efiling_no:efiling_no,case_no:case_no,case_year:case_year},
                url:  "<?php echo base_url('vakalatnama/DefaultController/add'); ?>",
                success: function (data)
                {
                    $('#vakalatnama_bind').html(data);

                },
                error: function () {
                    $.getJSON("<?php echo base_url() . 'csrftoken'; ?>", function (result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    });
                }
            });

        }

        // populate the data table with JSON data

    })();

</script>
<script>

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

