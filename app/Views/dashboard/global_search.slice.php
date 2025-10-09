<style>
    .caseTypePanel{
        max-height: 250px;
        overflow: auto;
    }
    #tabs{
        max-height:800px;
        overflow: auto;
    }
    #("#position").select2({
        allowClear:true,
        placeholder: 'Position'
    });
    .panel-info {
        border-color: #FFFFFF !important;
    }

    body {
        font-family: "Lato", sans-serif;
    }

    .sidebar {
        height: 100%;
        width: 0;
        position: fixed;
        z-index: 1;
        top: 0;
        left: 0;
        background-color:#cccccc ;
        overflow-x: hidden;
        transition: 0.5s;
        padding-top: 60px;
    }

    .sidebar a {
        padding: 8px 8px 8px 32px;
        text-decoration: none;
        font-size: 25px;
        color: #818181;
        display: block;
        transition: 0.3s;
    }

    .sidebar a:hover {
        color: #f1f1f1;
    }

    .sidebar .closebtn {
        position: absolute;
        top: 0;
        right: 25px;
        font-size: 36px;
        margin-left: 50px;
    }

    .openbtn {
        font-size: 20px;
        cursor: pointer;
        background-color: #111;
        color: white;
        padding: 10px 15px;
        border: none;
    }

    .openbtn:hover {
        background-color: #444;
    }

    #main {
        transition: margin-left .5s;
        padding: 16px;
    }
    .custom {
        width: 120px !important;
        cursor: pointer;
        color: white;
        background-color: #286090;
        border: none;
    }
    .custom_a{
        width: 800px !important;
        cursor: pointer;
        color: white !important;;
        background-color: #286090;
        border: none;
        padding: 10px 400px;
        text-align: center;


    }

    /* On smaller screens, where height is less than 450px, change the style of the sidenav (less padding and a smaller font size) */
    @media screen and (max-height: 450px) {
        .sidebar {padding-top: 15px;}
        .sidebar a {font-size: 18px;}
    }
</style>


<div id="tabs">
        <ul>
        <li><a href="#tabs-1">Efiling Search</a></li>
       <!-- --as per direction - the following code has been commented by K.B Pujari on 30-01-2023
       <li><a href="#tabs-2">Cases - Paper Book Document</a></li>-->
        <li><a href="#tabs-3">Law Search</a></li>
        <li><a href="#tabs-4">Order National Search</a></li>

    </ul>
    <div id="tabs-1">
        <?php
        $attribute = array('name' => 'search', 'id' => 'search', 'autocomplete' => 'off');
        echo form_open('search/search_result', $attribute);
        ?>
        <div class="panel panel-info col-sm-12">
            <div class="panel-heading">EFILING SEARCH</div>
            <div class="panel-body eFilingSearch">

                        <?php
                        $attribute = array('name' => 'search', 'id' => 'search', 'autocomplete' => 'off');
                        echo form_open('search/search_result', $attribute);
                        ?>
                        <div class="input-group col-sm-12" style="margin: 30px;">
                            <div class="col-sm-8">
                                <input class="form-control " name="search" id="search" maxlength="30" required placeholder="" type="text">
                            </div>

                            <div class="input-groupp-btn col-sm-4">
                                <button class="btn btn-primary custom" type="submit" value="1" size="20" name="submit"> Go!</button>
                            </div>
                        </div>
                        <?php echo form_close(); ?>


            </div>
        </div>


        <div id="#tab1SearchData">

        </div>

    </div>
    <!-- as per direction - Dspace Integration code has been commented by K.B Pujari on 30-01-2023 -->
    <!--<div id="tabs-2" style="min-height:1000px;" >
        <div id="main">
            <button class="openbtn" onclick="openNav()">☰ Search Criteria</button>   </div>


                        <div id="mySidebar" class="sidebar">
                            <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
                            <?php
/*                            //var_dump($advocate_pending_cases);
                            $attribute = array('name' => 'search', 'id' => 'search', 'autocomplete' => 'off');
                            echo form_open('', $attribute);
                            */?>
                            <div class="input-group col-sm-12">
                                <label class="form-check-label col-sm-12" for="searchDocument" style="text-align: center" >
                                    FREE TEXT SEARCH:
                                </label>
                                <span class="col-sm-12" >
                                    <input class="form-control" name="search_text" id="search_text" maxlength="50"  placeholder="Write the name of PaperBook Document OR Free text" type="text" onblur="myFunction()">

                                </span>
                            </div>
                            <div class="col-sm-12">
                                <span class="col-sm-6"><hr></span>
                                <span class="col-sm-1">OR</span>
                                <span class="col-sm-5"><hr></span>
                            </div>

                            <div class="input-group col-sm-12" >
                                <label class="form-check-label col-sm-12" for="searchInAllCases" style="text-align: center">ALL CASES:
                                </label>
                            </div>

                            <div class="input-group col-sm-12">
                                <span class="col-sm-12" >
                                    <select class="form-control select2" name="case_list" id="case_list" style="width:100% !important;">
                                            <option value="" selected>--Select Your Case--</option>
                                        <?php
/*                                        foreach ($advocate_pending_cases->data as $case) {
                                            echo '<option  value="'.escape_data(url_encryption($case->diaryId)).'">'.escape_data(strtoupper($case->registrationNumber).'@ Diary No.'.$case->diaryId.' ('.strtoupper($case->petitionerName).' Vs. '.strtoupper($case->respondentName).' <strong> - '.strtoupper(($case->status=='P'?'Pending':'Disposed'))).'</strong></option>';
                                        } */?>

                                        </select>
                                </span>
                            </div>

                            <div class="col-sm-12">
                                <span class="col-sm-6"><hr></span>
                                <span class="col-sm-1">OR</span>
                                <span class="col-sm-5"><hr></span>
                            </div>


                            <div class="input-group col-sm-12">
                                <label class="form-check-label col-sm-12" for="searchInAllCases" style="text-align: center" >
                                    CASES SOON TO BE LISTED:
                                </label>

                                <div class="imput-group col-sm-12">
                                <select class="form-control select2" name="soon_listed_case" id="soon_listed_case" style="width:100% !important;">
                                        <option value="" selected>--Select Case--</option>
                                    <?php
/*                                    foreach ($scheduled_cases[0] as $listed_case) {
                                        echo '<option  value="'.escape_data(url_encryption($listed_case->diary_id)).'">'.escape_data(strtoupper($listed_case->registration_number).'@ Diary No.'.$listed_case->diaryId.' ('.strtoupper($listed_case->petitioner_name).' Vs. '.strtoupper($listed_case->respondent_name)) . ')</option>';
                                    } */?>

                                    </select>
                            </div>
                            </div>
                            <div class="col-sm-12">
                                <span class="col-sm-6"><hr></span>
                                <span class="col-sm-1">OR</span>
                                <span class="col-sm-5"><hr></span>
                            </div>
                            <div class="input-group col-sm-12">
                                <label class="form-check-label col-sm-12" for="searchDocument" style="text-align: center" >
                                    TYPE OF DOCUMENT:
                                </label>

                                <div class="col-sm-12" >
                                    <div class="form-grsoup col-sm-12">
                                        <select class="form-constrol select21" name="document_type" id="document_type" style="width:100% !important;">
                                            <option value="" selected>-- Select Document --</option>
                                            <option value="Record Of Proceedings" selected>	Record Of Proceedings</option>
                                            <option value="Memo Of Appearance">Memo Of Appearance</option>
                                            <option value="Reply">Reply</option>
                                            <option value="Rejoinder Affidavit">Rejoinder Affidavit</option>
                                            <option value="Court Fee">Court Fee</option>
                                            <option value="Report">Report</option>
                                            <option value="Counter Affidavit">Counter Affidavit</option>
                                            <option value="Spare Copy / Process Application">Spare Copy / Process Application</option>
                                            <option value="Interlocutary Application">	Interlocutary Application</option>
                                            <option value="Additional Affidavit">Additional Affidavit</option>
                                            <option value="Misc. Document / Other">Misc. Document / Other</option>
                                            <option value="Affidavit">Affidavit</option>
                                            <option value="Vakalatnama">Vakalatnama</option>
                                            <option value="Vakalatnama And Memo Of Appearance">Vakalatnama And Memo Of Appearance</option>
                                            <option value="Copy Of Impugned Judgement / Order">Copy Of Impugned Judgement / Order</option>
                                            <option value="Letter">Letter</option>
                                            <option value="Annexure">Annexure</option>
                                            <option value="Affidavit Of Service">Affidavit Of Service</option>
                                            <option value="Proof Of Service">Proof Of Service</option>
                                            <option value="Additional Document">Additional Document</option>
                                            <option value="Written Submission / Statement">Written Submission / Statement</option>
                                            <option value="Proof of Surrender / Custody Certificate">Proof of Surrender / Custody Certificate</option>
                                            <option value="Affidavit of Undertaking">Affidavit of Undertaking</option>
                                            <option value="No Objection Certificate">No Objection Certificate</option>
                                            <option value="Certified Copy">Certified Copy</option>
                                            <option value="Statement of Case">Statement of Case</option>
                                            <option value="other">Not in the List</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                                <div class="form-check col-sm-12" >
                                    <span style="text-align: center">
                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckIndeterminate">
                                        <label class="form-check-label" for="flexCheckIndeterminate">
                                            Search for All Documents
                                        </label>
                                    </span>
                                </div>
                                <div class="col-sm-12" >
                                <span style="text-align: center">
                                    <input class="form-check-input" type="checkbox" value="" id="searchInAllCases">
                                    <label class="form-check-label" for="searchInAllCases" >
                                        Search in All My Cases
                                    </label>
                                </span>
                                </div>

                            <div class="col-sm-12">
                                <span class="col-sm-12"><hr></span>
                                <span class="input-group col-sm-12">
                              <span class="input-groupp-btn col-sm-12" >
                                <button class="btn btn-primary" type="button" value="1" name="getPaperbookDoc" id="getPaperbookDoc" style="width: 100%"> SEARCH DOCUMENT..</button>
                            </span>
                        </span>
                            </div>
                            </div>

                            <?php
/*                            form_close(); */?>
        <div class="col-sm-12">
            <div class="panel-group col-sm-3">
                <div class="panel panel-info col-sm-12">
                    <div class="panel-heading">Case Type wise - Paperbook</div>
                    <div class="panel-body caseTypePanel">
                        <table class="table table-striped">
                            <tbody>
                            <tr> <td><a href="#">SPECIAL LEAVE PETITION (CIVIL)<span class="badge">10</span></a></td></tr>
                            <tr> <td><a href="#">SPECIAL LEAVE PETITION (CRIMINAL) <span class="badge">8</span></a></td></tr>
                            <tr> <td><a href="#">CIVIL APPEAL <span class="badge">15</span></a></td></tr>
                            <tr> <td> <a href="#">CRIMINAL APPEAL <span class="badge">5</span></a></td></tr>
                            <tr> <td> <a href="#">WRIT PETITION (CIVIL)<span class="badge">7</span></a></td></tr>
                            <tr> <td> <a href="#">WRIT PETITION(CRIMINAL)<span class="badge">16</span></a></td></tr>
                            <tr> <td> <a href="#">TRANSFER PETITION (CIVIL)<span class="badge">11</span></a></td></tr>
                            <tr> <td> <a href="#">TRANSFER PETITION (CRIMINAL)<span class="badge">9</span></a></td></tr>
                            <tr> <td> <a href="#">REVIEW PETITION (CIVIL)<span class="badge">18</span></a></td></tr>
                            <tr> <td> <a href="#">REVIEW PETITION (CRIMINAL)<span class="badge">14</span></a></td></tr>
                            <tr> <td> <a href="#">TRANSFERRED CASE (CIVIL)<span class="badge">3</span></a></td></tr>
                            <tr> <td> <a href="#">TRANSFERRED CASE (CRIMINAL)<span class="badge">12</span></a></td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="panel panel-info col-sm-12">
                    <div class="panel-heading">Next listing Datewise - Paperbook</div>
                    <div class="panel-body caseTypePanel">
                        <table class="table table-striped">
                            <tbody>
                            <tr> <td><a href="#">02-02-2021&nbsp;&nbsp;<span class="badge">3</span></a></td></tr>
                            <tr> <td><a href="#">03-02-2021&nbsp;&nbsp;<span class="badge">2</span></a></td></tr>
                            <tr> <td><a href="#">04-02-2021&nbsp;&nbsp;<span class="badge">5</span></a></td></tr>
                            <tr> <td> <a href="#">09-02-2021&nbsp;&nbsp;<span class="badge">4</span></a></td></tr>
                            <tr> <td> <a href="#">10-02-2021&nbsp;&nbsp;<span class="badge">7</span></a></td></tr>
                            <tr> <td> <a href="#">11-02-2021&nbsp;&nbsp;<span class="badge">9</span></a></td></tr>
                            <tr> <td> <a href="#">15-02-2021&nbsp;&nbsp;<span class="badge">11</span></a></td></tr>
                            <tr> <td> <a href="#">18-02-2021&nbsp;&nbsp;<span class="badge">9</span></a></td></tr>
                            <tr> <td> <a href="#">24-02-2021&nbsp;&nbsp;<span class="badge">1</span></a></td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="panel-body col-sm-9" id="tab2SearchData" >
            </div>
        </div>

            </div>-->
    <div id="tabs-3">
        <?php
        $attribute = array('name' => 'search', 'id' => 'search', 'autocomplete' => 'off');
        echo form_open('search/search_result', $attribute);
        ?>
        <div class="panel panel-info col-sm-12">
            <div class="panel-heading">LAW SEARCH</div>
            <div class="panel-body eFilingSearch">


                <div class="input-grHoup col-sm-12" style="margin: 30px;">
                          <span class="col-sm-8">
                                <input class="form-control" name="search_case" id="search_case" maxlength="30" placeholder="" type="text">
                          </span>

                    <span class="input-groupp-btn  col-sm-4">
                       <button class="btn btn-primary custom" onclick="openlawsearch(event)"  type="" value="1" name="submit"> Go!</button>
                       </span>

                </div>
                <script>
                    function openlawsearch(event) {
                        var search_txt = $('#search_case').val();
                        var uri = "https://indiacode.nic.in/handle/123456789/1362//simple-search?query=" + search_txt + "&btngo=&searchradio=acts";
                        uri = encodeURI(uri);
                        window.open(uri);
                    }
                </script>
                <?php // echo form_close();    ?>


            </div>
        </div>

        <div id="#tab3SearchData">

        </div>

    </div>

    <div id="tabs-4">
        <div class="panel panel-info col-sm-12">
            <div class="panel-heading center" style="text-align:center"><a href="https://hcservices.ecourts.gov.in/hcservices/main.php" target="_blank">CASE & ORDER NATIONAL SEARCH</a> </div>
        </div>

       <!--as per direction - the following code has been commented by K.B Pujari on 30-01-2023
        <?php
/*        $attribute = array('name' => 'search', 'id' => 'search', 'autocomplete' => 'off');
        echo form_open('search/search_result', $attribute);
        */?>
        <div class="panel panel-info col-sm-12">
            <div class="panel-heading">CASE & ORDER NATIONAL SEARCH</div>
            <div class="panel-body eFilingSearch col-sm-12">
                <div style="margin: 30px;text-align: center">
                    <a data-toggle="modal" href="#case_data_model" id="case_data" class="case_data_value custom_a"  title=""><span style='font-size: 14px;'> Case & Order National Search </span> </a>
                </div>

            </div>
        </div>
-->
        <div id="#tab4SearchData">
        </div>

    </div>

    </div>
</div>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    function myFunction() {
        $('#document_type').prop('disabled', 'disabled');
        $('#searchInAllCases').prop('disabled', 'disabled');

    }
    function openNav() {

        document.getElementById("mySidebar").style.width = "370px";
        document.getElementById("main").style.marginLeft = "370px";
    }

    function closeNav() {

        document.getElementById("mySidebar").style.width = "0";
        document.getElementById("main").style.marginLeft= "0";
    }


    $(document).ready(function() {
        $('.select2').select2({
            closeOnSelect: false
        });



        $('#case_list').on('select2:selecting', function(e) {
            $('#soon_listed_case').prop('disabled', 'disabled');
            $('#searchInAllCases').prop('disabled', 'disabled');
        });
        $('#soon_listed_case').on('select2:selecting', function(e) {
            $('#case_list').prop('disabled', 'disabled');
            $('#searchInAllCases').prop('disabled', 'disabled');
           // alert( this.value );

        });





    });
    $('#getPaperbookDoc').click(function () {
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        var search_text = $("#search_text").val();
        var soon_list_selected_case_diary_no =$("#soon_listed_case option:selected").val();

        var diary_no='';
        if(soon_list_selected_case_diary_no!='')
            diary_no=soon_list_selected_case_diary_no;


        if (search_text == '') {
            alert('Pls. Write the Name of Paperbook document');
            return false;
        }
        //alert(search_text);

        $.ajax({
            type: "POST",
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE,diary_no:diary_no,search_text: search_text},
            beforeSend: function (xhr) {
                $("#tab2SearchData").html("<div style='margin:0 auto;margin-top:20px;width:100%;text-align: center;'><img src='<?=base_url()?>/assets/images/loading-data1.gif'></div>");
            },
            url: "<?php echo base_url('search/defaultController/search_paperbook_data_in_dspace7'); ?>",
            success: function (data)
            {

                if((data.trim()).length > 0){
                    $('#tab2SearchData').html(data);
                }
                else{
                    $('#tab2SearchData').html('<h4>Data Not Found!</h4>');
                }

                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            },
            error: function () {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
         });

    });
</script>
