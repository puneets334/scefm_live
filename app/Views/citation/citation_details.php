<?php
if ($this->uri->segment(2) != 'view') {
    $this->load->view('citation/citation_breadcrumb');
}
//var_dump($_SESSION);
/*echo $_SESSION['login']['id']."||";
echo $_SESSION['login']['userid']."||";
echo $_SESSION['efiling_details']['efiling_no']."||";
echo $_SESSION['listing_details']->next_dt."||";
echo $_SESSION['tbl_sci_case_id']."||";*/
//print_r($auth_name);
//print_r($len);
//$auth_name = json_decode($auth_name,true);
//print_r($auth_name);
//$auth_name=json_decode($auth_name);
//print_r($auth_name);
//print_r($auth_name[1]);
//echo count($auth_name);
?>
<?php
$attribute = array('class' => 'form-horizontal', 'name' => 'frmCitationSearch', 'id' => 'frmCitationSearch', 'autocomplete' => 'off', 'enctype' => 'multipart/form-data');
echo form_open('#', $attribute);
?>
<!--<form id="frmCitationSearch">-->
<div class="panel panel-default">
    <h4 style="text-align: center;color: #31B0D5">Citation Details</h4>
    <div class="panel-body">

        <!-- Code change started on 21 September 2020  -->
        <div class="col-lg-8 col-md-8 coll-sm-8 col-xs-8">
            <div class="col-sm-2">
                <label class="control-label">Search by :</label>
            </div>
            <div class="col-sm-2">
                <div class="input-group">
                    <label class="radio-inline"><input type="radio" tabindex="1" onchange="changeSelect(this.value);"  name="search_option" id="search_option" value="1"><strong style="font-size: 13px;">Journal</strong></label>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="input-group">
                    <label class="radio-inline"><input type="radio" tabindex="1" onchange="changeSelect(this.value);" checked="checked" name="search_option" id="search_option" value="2"><strong style="font-size: 13px;">Book</strong></label>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="input-group">
                    <label class="radio-inline"><input type="radio" tabindex="1" onchange="changeSelect(this.value);"  name="search_option" id="search_option" value="3"><strong style="font-size: 13px;">Others</strong></label>
                </div>
            </div>
        </div>
        <br/>
        <br/>
        <br/>




        <!--End of code change-->




        <div class="col-lg-12 col-md-12 col-sm-12  col-xs-12" id="journal" style="display:none;">
            <div class="row">
            <div class="col-sm-1">
                <label class="control-label"> Journal<span style="color: red">*</span></label>
                <div class="input-group">
                    <select id="ddlJournal" name="ddlJournal" class="form-control">
                        <option value="1">AIR</option>
                        <option value="2">SCR</option>
                        <option value="3">SCC</option>
                        <option value="4">JT</option>
                        <option value="5">SC</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-3">
                <label class="control-label"> Year<span style="color: red">*</span></label>
                <div class="input-group">
                    <select id="ddlYear" name="ddlYear" class="form-control filter_select_dropdown">
                        <?php
                        $year=date('Y');
                        while($year>=1950){
                            echo "<option value='$year'>$year</option>";
                            $year--;
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="col-sm-3">
                <label class="control-label"> Volume<span style="color: red">*</span></label>
                <div class="input-group">
                    <select id="ddlVolume" name="ddlVolume" class="form-control filter_select_dropdown">
                        <?php
                        $i=0;
                        while($i<=20){
                            echo "<option value='$i'>$i</option>";
                            $i++;
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-sm-2">
                <label class="control-label"> Suppl.(Y/N)<span style="color: red">*</span></label>
                <div class="input-group">
                    <select id="ddlSuppl" name="ddlSuppl" class="form-control">
                        <option value="N">N</option>
                        <option value="Y">Y</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-1">
                <label class="control-label"> Page No.<span style="color: red">*</span></label>
                <div class="input-group col-sm-9" >
                    <input type="text" name="p_no" id="p_no" class="form-control input-sm">
                </div>
            </div>
            </div>
            <div class="row">
            <div class="col-sm-12">
                <div class="text-center">
                    <button class="btn btn-outline-secondary" type="button" id="btnSearchCitation" name="btnSearchCitation">
                        <i class="fa fa-search active" aria-hidden="true"></i>  Search
                    </button>
                </div>
            </div>
            </div>
     </div>


        <!--code change started on 21 September 2020-->
        <div class="col-lg-12 col-md-12 col-sm-12  col-xs-12" id="book">
            <div class="col-sm-2">
                <label class="control-label"> Search in Book By : <span style="color: red">*</span></label>
                <div class="input-group">
                    <select id="ddlBook" name="ddlBook" class="form-control" onchange="changeBookSearch(this.value)">
                        <option value="">-Select-</option>
                        <option value="1">Author Name</option>
                        <option  style="display:none" value="2">Author Code</option>
                        <option value="3">Title</option>
                        <option value="4">Subject</option>
                        <option value="5">Publisher</option>
                    </select>
                </div>
            </div>

            <div class="col-sm-3" id="auth_name" style="display:none;">
                <label class="control-label">Author Name<span style="color:red">*</span></label>
                <div class="input-group">
                    <select class="e1" id="auth_name" name="auth_name" style="width:120%">
                        <option value="">-----Select Author Name------</option>
                        <?php

                        foreach($auth_name as $name)
                        {?>
                            <option value="<?=$name['authcode']?>"><?=$name['firstname'].' '.$name['lastname'].'['.$name['authcode'].']'?></option>
                        <?php }
                        ?>
                    </select>
                </div>
            </div>

            <div class="col-sm-3" id="auth_code" style="display:none;">
                <label class="control-label">Author Code<span style="color:red">*</span></label>
                <div class="input-group">
                    <input type="number" id="auth_code_val" name="auth_code_val" class="form-control" placeholder="Enter Author Code">
                </div>
            </div>

            <div class="col-sm-3 col-lg-3 col-xs-3 col-md-3" id="book_title" style="display:none;">
                <label class="control-label">Title<span style="color:red">*</span></label>
                <div class="input-group">
                    <input type="text" id="book_title_val" name="book_title_val" class="form-control" placeholder="Enter Book Title">
                </div>
            </div>

            <div class="col-sm-3" id="book_subject" style="display:none;">
                <label class="control-label">Subject<span style="color:red">*</span></label>
                <div class="input-group">
                    <input type="text" id="book_subject_val" name="book_subject_val" class="form-control" placeholder="Enter Subject">
                </div>
            </div>

            <div class="col-sm-3" id="book_publisher" style="display:none;">
                <label class="control-label">Publisher<span style="color:red">*</span></label>
                <div class="input-group">
                    <input type="text" id="book_publisher_val" name="book_publisher_val" class="form-control" placeholder="Enter Publisher">
                </div>
            </div>



            <div class="col-sm-2" id="search_button" style="display:none;">
                <label class="control-label "> &nbsp;</label>
                <div class="input-group">
                    <button class="btn btn-outline-secondary" type="button" id="bookSearchCitation" name="bookSearchCitation">
                        <i class="fa fa-search active" aria-hidden="true"></i> Search
                    </button>
                </div>
            </div>

        </div>

        <div class="col-lg-12 col-md-12 col-sm-12  col-xs-12" id="others" style="display:none;">
            <div class="col-sm-2 col-xs-2 col-lg-2 col-md-2">
                <label>Title <span style="color: red">*</span> :</label>
            </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <div class="input-group">
                    <input type="text" class="form-control input-sm" name="doc_title" id="doc_title" required="" placeholder="PDF Title" minlength="3" maxlength="75">
                    <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content=" PDF title max. length can be 75 characters only.  Only numbers, letters, spaces, hyphens,dots and underscores are allowed." data-original-title="" title="">
                            <i class="fa fa-question-circle-o"></i></span>
                    </div>
                </div>

            <div class="col-sm-1 col-lg-1 col-md-1 col-xs-1"></div>

            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                <label>Office Order Date<span style="color:red">*:</span></label>
            </div>

            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                <div class="input-group">
                    <input class="form-control datepick" id="publishing_date" name="publishing_date" placeholder="DD/MM/YYYY"  type="text">
                    <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                    <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Please enter Publishing Date.">
                                <i class="fa fa-question-circle-o"></i>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-2 col-sm-2 col-xs-2 col-md-2">
                    <label>Place of Issuance<span style="color:red">*:</span></label>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <div class="input-group">
                        <input type="text" class="form-control input-sm" name="pub_place" id="pub_place" required="" placeholder="Publishing Place" minlength="3" maxlength="75">
                        <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content=" Place of Issuance place max. length can be 75 characters only.  Only numbers, letters, spaces, hyphens,dots and underscores are allowed." data-original-title="" title="">
                            <i class="fa fa-question-circle-o"></i></span>
                    </div>
                </div>

                <div class="col-sm-1 col-lg-1 col-md-1 col-xs-1"></div>

                <div class="form-group">
                    <label class="col-lg-2 col-md-2 col-sm-3 col-xs-12">Upload Order <span style="color: red">*</span> :</label>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                        <input name="pdfDocFile" id="pdfDocFile"  required="required" type="file">
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-sm-5 col-lg-5 col-md-5 col-xs-5"></div>
                <div class="col-sm-2 col-lg-2 col-md-2 col-xs-2">
                    <input type="submit" class="btn btn-success " style="width:90px;" id="save" value="save">
                </div>
                <div class="col-sm-5 col-lg-5 col-md-5 col-xs-5"></div>

            </div>

        </div>


        <!--End of code change-->





        <input type="hidden" id="webServiceResponseBook" name="webServiceResponseBook" value="[]">

        <input type="hidden" id="webServiceResponse" name="webServiceResponse" value="[]">
        <input type="hidden" id="isInSuplis" name="isInSuplis" value="0">
</div>
</div>
    <hr>
    <div id ="divSearchResult" class="col-md-12 col-sm-12 col-xs-12">
        <table id="datatable-responsive1" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%" hidden>
            <thead>
            <tr class="success">
                <th style="width: 15%">Case No.</th>
                <th style="width: 30%">Petitioner Name</th>
                <th style="width: 30%">Respondent Name</th>
                <th style="width: 10%">Judgment Date</th>
                <th style="width: 15%">Action</th>
            </tr>
            </thead>
            <tbody id="tbodyid">

            </tbody>
        </table>
    </div>

<?php /*change added on 24 September 2020*/ ?>
<div id ="divSearchResult2" class="col-md-12 col-sm-12 col-xs-12">
    <h4><b>Books List</b></h4>
    <table id="datatable-book" class="table table-striped table-bordered dt-responsive nowrap book_list" cellspacing="0" width="100%" hidden>
        <thead>
        <tr class="success">
            <th style="width: 5%">Sr. No.</th>
            <th style="width: 20%">Title [Edition]</th>
            <th style="width: 15%">Publication Name</th>
            <th style="width: 10%">Publication Place</th>
            <th style="width: 10%">Publication Year</th>
            <th style="width: 10%">Subject</th>
            <th style="width: 10%">ISBN</th>
            <th style="width: 10%">Class No.</th>
            <th style="width: 10%">Action</th>
        </tr>
        </thead>
        <tbody id="tbodybookid">

        </tbody>
    </table>
</div>

<?php /*change end*/ ?>
<!--</form>-->
    <?php echo form_close(); ?>

    <hr>
    <div class="panel panel-default">
        <div class="panel-body">

<?php /*Start of code change on 05 October 2020*/?>
            <button type="button" class="btn btn-info" id="share_window" onclick="load_share_window()" name="share_window" style="float: right;">
<!--            <button type="button" class="btn btn-info" id="share_window"  name="share_window" style="float: right;">-->
                <i class="fa fa-share-alt fa-lg" aria-hidden="true"></i> Share Citation</button>

            <button type="button" class="btn btn-danger" id="cancel" onclick="cancel_sharing()" name="cancel" style="float: right; display:none;">
                <i class="fa fa-times fa-lg" aria-hidden="true"></i> Cancel Sharing</button>

            

            <div id="share_list" name = "share_list" class="col-md-12 col-sm-12 col-xs-12">
                <h4>AORs List</h4>
                <table id="datatable-advocate" name="aor_details" class="table table-striped table-bordered dt-responsive nowrap aor_details" cellspacing="0" width="100%" >
                    <thead>
                    <tr class="success">
                        <th style="width: 5%">#</th>
                        <th style="width: 30%">Name [AOR Code]</th>
                        <th style="width: 15%">Contact Details</th>
                        <th style="width: 10%">Party Type</th>
                        <th style="width: 10%">Select / Unselect All
                            <input type="checkbox" id="check_all" name="check_all" value="X" onclick="check_uncheck_all()"></th>
                    </tr>
                    </thead>
                    <tbody id="sharelistbodyid">
                    <?php
                    $i = 0;
                    foreach($advocate_list as $adv_list)
                    { $i = $i + 1;
                        if($adv_list['pet_res']=='P')
                        {
                            $party_type = 'Petitioner';
                        }elseif ($adv_list['pet_res']=='R')
                        {
                            $party_type = 'Respondent';
                        }
                    ?>
                        <tr>
                            <td><?=$i?></td>
                            <td><?=$adv_list['name'].'['.$adv_list['aor_code'].']';?></td>
                            <td><b><?php echo 'Email: '?></b><?=$adv_list['email'];?>
                            </br><b><?php echo 'Mobile No. ';?></b><?=$adv_list['mobile'];?></td>
                            <td><?=$party_type;?></td>
                            <td><input type="checkbox" id="chk" name="chk[]" value="<?=$adv_list['advocate_id'].'/'.$adv_list['email']?>"></td>
                        </tr>
                    <?php }
                        ?>
                    </tbody>
                </table>

                <?php
                    if($i>0)
                    {?>
                        <button type="button" class="btn btn-info" id="share_button"  name="share_button" style="margin-left: 40%;" onclick="citation_email_send()">
                            <i class="fa fa-share-alt fa-lg" aria-hidden="true"></i> Click Here to Share</button>
                    <?php }
                ?>
            </div>






<?php /*End of code change*/?>

            <div id ="act_section_list_data" class="col-md-12 col-sm-12 col-xs-12">
                <h4>Existing Entries</h4>
                <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap exist_citation" cellspacing="0" width="100%" >
                    <thead>
                    <tr class="success">
                        <th style="width: 5%">#</th>
                        <th style="width: 70%">Citation</th>
                        <th style="width: 15%">For Listing Date</th>
                        <th style="width: 15%">Uploaded Order PDF( <i class="fa fa-file-pdf-o" style="font-size:16px"></i>)</th>
                        <th style="width: 10%">Action</th>
                    </tr>
                    </thead>
                    <tbody id="texistingbodyid">
                    <?php
                    $i=1;
                    //var_dump($existing_citation);
                    foreach($existing_citation as $data){
                        $file_path = !empty($data['file_path']) ? '<a title="'.strtoupper($data['publication_place']).'" target="_blank" href="'.base_url($data['file_path']).'"><i class="fa fa-file-pdf-o" style="font-size:25px"></i></a>' : '---';
//                        var_dump($data);
//                        echo $data['journal'];?>
                        <tr>
                            <td><?php echo htmlentities($i++, ENT_QUOTES);?></td>
                            <?php if($data['search_by'] == 'J') {?>
                            <td><b>Journal:</b> <?=htmlentities($data['journal'], ENT_QUOTES)?>
                                <b>Journal Year:</b> <?=htmlentities($data['journal_year'], ENT_QUOTES)?>
                                <b>Volume:</b> <?=htmlentities($data['volume'], ENT_QUOTES)?>
                                <b>Suppl.:</b> <?=htmlentities($data['suppl'], ENT_QUOTES)?>
                                <b>Page No.:</b> <?=htmlentities($data['page_no'], ENT_QUOTES)?>
                            </td>
                            <?php } elseif ($data['search_by']=='B') {?>
                                <td> <?php if($data['book_title']) {?>
                                    <b>Title:</b> <?=htmlentities($data['book_title'], ENT_QUOTES)?>
                                    <?php } if($data['author_name']){?>
                                        <b>Author Name:</b> <?=htmlentities($data['author_name'], ENT_QUOTES)?>
                                    <?php } if($data['subject']) {?>
                                        <b>Subject:</b> <?=htmlentities($data['subject'], ENT_QUOTES)?>
                                    <?php } if($data['publisher_name']){?>
                                    <b>Publisher Name:</b> <?=htmlentities($data['publisher_name'], ENT_QUOTES)?>
                                    <?php } if($data['publication_year']){?>
                                    <b>Publication Year:</b> <?=htmlentities($data['publication_year'], ENT_QUOTES)?>
                                    <?php } if($data['isbn_no']) {?>
                                    <b>ISBN No.:</b> <?=htmlentities($data['isbn_no'], ENT_QUOTES)?>
                                    <?php } if($data['publication_place']) {?>
                                    <b>Publication Place:</b> <?=htmlentities($data['publication_place'], ENT_QUOTES)?>
                                     <?php } ?>
                                </td>
                            <?php }
                            else { ?>
                                <td><?= strtoupper(htmlentities($data['publication_place'], ENT_QUOTES))?></td>
                            <?php
                            }
                            ?>
                            <td><?=htmlentities(date('d-m-Y',strtotime($data['listing_date'])), ENT_QUOTES)?></td>
                            <td><?php echo $file_path?></td>
                            <td><?php
                                if($data['listing_date']==date('d-m-Y',strtotime($_SESSION['listing_details']->next_dt))){
                                    echo "<button data-toggle=\"tooltip\" data-placement=\"top\" title=\"Delete Citation\" type=\"button\" name=\"btnAdd\" id=\"btnAdd\" onclick=\"deleteCitation(".$data['id'].")\"><i class=\"fa fa-trash text-danger\" aria-hidden=\"true\"></i></button>";
                                }
                                else{
                                    echo "<button data-toggle=\"tooltip\" data-placement=\"top\" title=\"Add Citation\" type=\"button\" name=\"btnAdd\" id=\"btnAdd\" onclick=\"updateForNextDate(".$data['id'].")\"><i class=\"fa fa-check text-success\" aria-hidden=\"true\"></i></button>";
                                }
                                ?></td>
                        </tr>
                   <?php }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<!--<div id="tablebody555"></div>-->


    <link rel="stylesheet" href="<?= base_url() ?>assets/css/jquery-ui.css">
    <link rel="stylesheet"  href="<?= base_url() . 'assets' ?>/css/select2.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-datepicker.min.css">


    <script src="<?= base_url() ?>assets/js/sha256.js"></script>
    <script src="<?= base_url() . 'assets' ?>/js/jquery.min.js"></script>
    <script src="<?= base_url() . 'assets' ?>/js/jquery-ui.min.js"></script>
    <script src="<?= base_url() . 'assets' ?>/js/select2.min.js"></script>
    <script src="<?= base_url() ?>assets/js/bootstrap-datepicker.js"></script>
    <script src="<?= base_url() ?>assets/js/bootstrap-datepicker.min.js"></script>
<style>
    .select2-container {
        min-width:200px;
    }
</style>
    <script type="text/javascript">

        $('#share_list').hide();
        $('#divSearchResult2').hide();
        $('#cancel').hide();


        /* Change started on 21 September 2020*/
        var bookData=[];

        function cancel_sharing()
        {
            $('#cancel').hide();
            $('#share_list').hide();
            $('#act_section_list_data').show();
            $('#share_window').show();
        }

        function load_share_window()
        {
            $('#share_list').show();
            $('#act_section_list_data').hide();
            $('#divSearchResult2').hide();
            $('#cancel').show();
            $('#share_window').hide();

        }

        function check_uncheck_all()
        {

            if($('#check_all').is(":checked"))
            {
                 $('#share_list input:checkbox').each(function () {

                     if($(this).attr('name')== 'chk[]'){
                            $(this).prop("checked",true);
                        }
                });
            }
            else
            {
                $('#share_list input:checkbox').each(function () {

                    if($(this).attr('name')== 'chk[]'){
                        $(this).prop("checked",false);
                    }
                });
            }
        }

        function addInCitationBook($option)
        {
            $("#webServiceResponseBook").val(JSON.stringify(bookData[$option]));
            var form_data = $('#frmCitationSearch').serialize();

            $.ajax({
                type: "POST",
                url: "<?php echo base_url('citation/CitationController/addRecordInCitationBook'); ?>",
                data: form_data,
                success: function (data) {
                    alert("Record added successfully!!");
                    location.reload();
                    //11-12-2020 commented reloadList(data);
                   // reloadList(data);
                    //$('#datatable-responsive1').hide();
                    //reloadList(data);
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
        }
        $('#frmCitationSearch').on('submit', function () {
            if ($('#frmCitationSearch').valid()) {
                var form_data = $('#frmCitationSearch').serialize();
                var title = $("#doc_title").val();
                var file_post = $('#frmCitationSearch')[0];
                var file_data = new FormData(file_post);
                file_data.append('', form_data);
                $.ajax({
                    enctype: 'multipart/form-data',
                    type: 'POST',
                    url: "<?php echo base_url('citation/CitationController/uploadOrder'); ?>",
                    data: file_data,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        alert("Record added successfully!!");
                        location.reload();
                        //11-12-2020 commented reloadList(data);
                       // reloadList(data);
                        $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        });
                    },
                    error: function () {
                        $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        });
                    }


                    //    var resArr = data.split('@@@');
                    //    if (resArr[0] == 1) {
                    //        $('#msg').show();
                    //        $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                    //    } else if (resArr[0] == 2) {
                    //        $(".form-response").html("<p class='message valid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                    //        $('#msg').show();
                    //       // location.reload();
                    //    } else if (resArr[0] == 3) {
                    //        $('#msg').show();
                    //        $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                    //    }
                    //    $.getJSON("<?php //echo base_url('csrftoken'); ?>//", function (result) {
                    //        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    //    });
                    //},
                    //error: function (e) {
                    //    $("#upload").prop("disabled", false);
                    //}
                });
                return false;
            } else {
                return false;
            }
        });



        $('.datepick').datepicker({
            format: 'dd/mm/yyyy',
            endDate: new Date(),
            todayHighlight: true,
            autoclose:true

        });



        $('#bookSearchCitation').on('click', function ()
        {

            var form_data = $('#frmCitationSearch').serialize();
            var ddlBook = $('#ddlBook').val();
            $('#datatable-book').hide();
            $("#tbodybookid").empty();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('citation/CitationController/searchByBookWebservice'); ?>",
                data: form_data,
                success: function (data) {
                    //console.log(data);

                    var data_count=0;
                    if(data!=null && data!='null'){
                         response = $.parseJSON(data);
                        data_count = response.length;
                        //console.log(response);

                        var trHTML = '';
                        var l = 0;
                        $.each(response, function (i, item) {

                            l = l+1;
                            bookData.push(item);

                            trHTML += '<tr>' + '<td>' + l + '</td>';
                            if(item.edno && item.edno != ' ') {
                                trHTML += '<td>' + item.title + '<b> [Edition :' + item.edno + ']</b>' + '</td>';
                            }
                            else {
                                trHTML += '<td>' + item.title +  '</td>';
                            }

                                trHTML += '<td>' + item.pubnm + '</td>' +
                                    '<td>' + item.pubpl + '</td>' +
                                    '<td>' + item.pubyr + '</td>' +
                                    '<td>' + item.sub1 + '</td>' +
                                    '<td>' + item.isbn + '</td>' +
                                    '<td>' + item.classno + '</td>' +
                                    '<td><button type="button" name="btnAdd" id="btnAdd" onclick="addInCitationBook('+i+')">' +
                                    '<i class="fa fa-plus active" aria-hidden="true"></i></button></td></tr>';

                        });
                        $('#datatable-book').append(trHTML);
                        $('#datatable-book').show();

                    }
                    if(data_count==0){//==0 removed for testing purpose
                       alert('No record found!!!');
                    }
                    else{
                        //addCitation(form_data);
                        $('#divSearchResult2').show();
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
            return false;
        });



        $(document).ready(function() {
            $(".e1").select2();
        });

        function changeBookSearch($option)
        {
            $('#search_button').show();
            if(!$option)
            {
                $('#auth_name').hide();
                $('#search_button').hide();
            }
            else if($option==1)
            {
                $('#auth_name').show();
                $('#auth_code').hide();
                $('#book_title').hide();
                $('#book_subject').hide();
                $('#book_publisher').hide();
            }
            else if($option ==2)
            {
                $('#auth_name').hide();
                $('#auth_code').show();
                $('#book_title').hide();
                $('#book_subject').hide();
                $('#book_publisher').hide();
            }
            else if($option ==3)
            {
                $('#auth_name').hide();
                $('#auth_code').hide();
                $('#book_title').show();
                $('#book_subject').hide();
                $('#book_publisher').hide();
            }
            else if($option == 4)
            {
                $('#auth_name').hide();
                $('#auth_code').hide();
                $('#book_title').hide();
                $('#book_subject').show();
                $('#book_publisher').hide();
            }
            else if($option == 5)
            {
                $('#auth_name').hide();
                $('#auth_code').hide();
                $('#book_title').hide();
                $('#book_subject').hide();
                $('#book_publisher').show();
            }
        }

       function changeSelect($option)
        {
            // alert($option);
            if($option == 1)
            {
                $('#journal').show();
                $('#book').hide();
                $('#others').hide();
                $('#datatable-book').hide();
                $('#divSearchResult2').hide();
            }
            else if($option ==2)
            {
             $('#journal').hide();
             $('#book').show();
             $('#others').hide();
             $('#datatable-responsive1').hide();
            }
            else if($option == 3)
            {
                $('#journal').hide();
                $('#book').hide();
                $('#others').show();
                $('#divSearchResult2').hide();
            }

        }


        /* End of Change */





        function addCitation(form_data){
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('citation/CitationController/addRecordInCitation'); ?>",
                data: form_data,
                success: function (data) {
                    alert("Record added successfully!!");
                    $('#datatable-responsive1').hide();
                    location.reload();
                    //11-12-2020 commented reloadList(data);
                   // reloadList(data);
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
        }
        function reloadList(data){
            var ddlBook = $('#ddlBook').val();
            var search_option = $('#search_option').val();

            $("#texistingbodyid").empty();
            response = $.parseJSON(data);
            var trHTML = '';
            var baseURL = "<?php echo base_url(); ?>";
            $.each(response, function (i, item) {
                /*if(item.suplis_response!="[]"){
                    case_response = $.parseJSON(item.suplis_response);

                }*/
                  var file_path = '---';
                  if(item['file_path']){
                      var imgUrl = baseURL+item['file_path'];
                         file_path = '<a target="_blank" title="'+item['publication_place']+'" href="'+imgUrl+'">View Order PDF</a>';
                    }
                    if(item.for_required_date==0){

                        if(item.search_by == 'J')
                        {
                        trHTML += '<tr><td>' + (i+1) + '</td><td><b>Journal: </b>' + item.journal + '<b> Journal Year: </b>' + item.journal_year + ' <b> Volume: </b>' + item.volume + '<b> Suppl.: </b>' + item.suppl + '<b> Page No.: </b>' + item.page_no + '</td>' +
                            '<td>' + item.listing_date + '</td><td>'+file_path+'</td>' +
                            '<td><button data-toggle="tooltip" data-placement="top" title="Delete Citation" type="button" name="btnDelete" id="btnDelete" ' +
                            'onclick="deleteCitation(' + item.id + ')">' +
                            '<i class="fa fa-trash text-danger" aria-hidden="true"></i></button>' +
                            '</td></tr>';
                        }else if(item.search_by == 'B')
                        {
                            trHTML += '<tr><td>' + (i+1) + '</td>' +
                                        '<td><b>Title: </b>'+ item.book_title;

                            if(item.edition_no){ trHTML += '<b> Edition No.: </b>' + item.edition_no; }
                            if(item.author_name){ trHTML += '<b> Author Name: </b>' + item.author_name;}
                            if(item.subject){ trHTML += '<b> Subject: </b>' + item.subject; }
                            if(item.publisher_name) { trHTML += '<b> Subject: </b>' + item.publisher_name; }

                            trHTML += '</td><td>' + item.listing_date + '<td>'+file_path+'</td><td><button data-toggle="tooltip" data-placement="top" title="Delete Citation" type="button" name="btnDelete" id="btnDelete" ' +
                                'onclick="deleteCitation(' + item.id + ')">' +
                                '<i class="fa fa-trash text-danger" aria-hidden="true"></i></button>' +
                                '</td></tr>';

                        }
                        else{
                                trHTML += '<tr><td>' + (i+1) + '</td>' +
                                    '<td><b>Publication Place: </b>' + item['publication_place'];

                                trHTML += '</td><td>' + item.listing_date + '</td><td>'+file_path+'</td><td><button data-toggle="tooltip" data-placement="top" title="Delete Citation" type="button" name="btnDelete" id="btnDelete" ' +
                                    'onclick="deleteCitation(' + item.id + ')">' +
                                    '<i class="fa fa-trash text-danger" aria-hidden="true"></i></button>' +
                                    '</td></tr>';
                        }
                    }
                    else{
                        trHTML += '<tr><td>' + (i+1) + '</td><td><b>Journal: </b>' + item.journal + '<b> Journal Year: </b>' + item.journal_year + ' <b> Volume: </b>' + item.volume + '<b> Suppl.: </b>' + item.suppl + '<b> Page No.: </b>' + item.page_no + '</td>' +
                            '<td>' + item.listing_date + '</td><td>'+file_path+'</td>' +
                            '<td><button data-toggle="tooltip" data-placement="top" title="Add Citation" type="button" name="btnAdd" id="btnAdd" ' +
                            'onclick="updateForNextDate(' + item.id + ')">' +
                            '<i class="fa fa-check text-success" aria-hidden="true"></i></button>' +
                            '</td></tr>';
                    }
            });
            $('#datatable-responsive').append(trHTML);
        }
        function addInCitation(){
            var form_data = $('#frmCitationSearch').serialize();
            addCitation(form_data);
        }
        function updateForNextDate(id){
            var y= confirm("Do You really want to Copy this Citation for next hearing?");
            if(y == true){
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('citation/CitationController/copyCitation'); ?>",
                    data: {'cid':id},
                    success: function (data) {
                        alert("Citation Copied Successfully!!");
                        location.reload();
                        //11-12-2020 commented reloadList(data);
                       // reloadList(data);
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
            }
        }
        function deleteCitation(id){
            var y= confirm("Do You really want to remove this Citation?");
            if(y == true){
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('citation/CitationController/removeCitation'); ?>",
                    data: {'cid':id},
                    success: function (data) {
                        alert("Citation removed Successfully!!");
                        location.reload();
                        //11-12-2020 commented reloadList(data);
                       // reloadList(data);
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
            }
        }



        $('#btnSearchCitation').on('click', function ()
        {
            var form_data = $('#frmCitationSearch').serialize();
            $("#tbodyid").empty();
            $.ajax({
                type: "POST",
                url: "<?php echo base_url('citation/CitationController/searchInWebservice'); ?>",
                data: form_data,
                success: function (data) {
                    var data_count=0;
                    if(data!=null && data!='null'){
                        response = $.parseJSON(data);
                        data_count = response.length;
                        var trHTML = '';
                        $.each(response, function (i, item) {
                            trHTML += '<tr><td>' + item.caseno + '</td><td>' + item.petname + '</td><td>' + item.resname + '</td>' +
                                '<td>' + item.doj + '</td><td>' +
                                '<button type="button" name="btnAdd" id="btnAdd" onclick="addInCitation()"><i class="fa fa-plus active" aria-hidden="true"></i></button></td></tr>';
                        });
                        $('#datatable-responsive1').append(trHTML);
                    }
                    if(data_count!=0){
                        $("#webServiceResponse").val(data);
                        $("#isInSuplis").val("1");
                        $('#datatable-responsive1').show();
                    }
                    else{
                        y=confirm("No Data found from suplis data. Do you still want to add this Citation");
                        if (y == true)
                        {
                            addCitation(form_data);
                        }
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
            return false;
        });

        //XXXXXXXXXXXXXXXXXXXXXXX work start XXXXXXXXXXXXXXXXXXXXXXX

        function share_content_aor () {

            var favorite = [];
            $.each($("input[name='chk[]']:checked"), function(){
                favorite.push($(this).val());
            });

            if(favorite!=''){

                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('citation/CitationController/share_citation_aor'); ?>",
                    data: {'c_id':favorite},
                    success: function (resultData) {
                        alert(resultData);
                        console.log(resultData);
                        return;

                        var rdata = JSON.parse(resultData);

                        var adata = rdata['existing_citation'];
                        var showdata ='';
                        var count_pno=0;
                        var cur_pno=0;
                        var tot_rec=0;

                        showdata = showdata + "<table style='border: dotted'><thead><tr><th style=\"width: 10%\">"+ "Listing Date "+ "</th>";
                        showdata = showdata + "<th style=\"width: 15%\">"+  "Title "+ "</th>";
                        showdata = showdata + "<th style=\"width: 10%\">"+  "Publisher Name "+ "</th>";
                        showdata = showdata + "<th style=\"width: 10%\">"+  "Publisher Year "+ "</th>";
                        showdata = showdata + "<th style=\"width: 10%\">"+  "Publisher Place "+ "</th>";
                        showdata = showdata + "<th style=\"width: 10%\">"+  "Subject "+ "</th></tr></thead>";

                        for (var key in adata) {
                            tot_rec= tot_rec +1;
                            var rowdata = adata[key];

                            if(rowdata['id']!=cur_pno){
                                // alert(rowdata['PNO']);
                                count_pno=count_pno+1;
                                cur_pno=rowdata['id'];
                            }
                            var X= rowdata['suplis_response'];
                            var Y = JSON.parse(X);
                            /*console.log(Y);
                            return;*/

                            showdata = showdata +"<tr><td>"+rowdata['listing_date']+"</td>";
                            showdata = showdata +"<td>"+Y['title']+"</td>";
                            showdata = showdata +"<td>"+Y['pubnm']+"</td>";
                            showdata = showdata +"<td>"+Y['pubyr']+"</td>";
                            showdata = showdata +"<td>"+Y['pubpl']+"</td>";
                            showdata = showdata +"<td>"+Y['sub1']+"</td></tr>";

                        }
                        showdata = showdata +"</table>";
                       // alert(showdata);
                       // $('#tablebody555').html(showdata);
                        //return;

                        $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        });
                        citation_email_send(showdata);
                    },
                    error: function () {
                        $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                            $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        });
                    }
                });

            }else{
                alert("Select Cheackbox in Aor List ");
            }

            return;
        }//End of function share_content_aor..

        function citation_email_send() {

            var favorite = [];
            $.each($("input[name='chk[]']:checked"), function(){
                favorite.push($(this).val());
            });
            if(favorite!='') {

                //alert(show_data);XXXXXXXXXXXXXXXXXXXX
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('citation/CitationController/share_email_citation_msg'); ?>",
                    //data: {'message': show_data},
                    data: {'c_id':favorite},
                    //dataType: "html",
                    success: function (resultData) {
                        alert(resultData);
                        /*console.log(resultData);

                        return;*/
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
            }else{
                alert("Select Cheackbox in Aor List ");
            }


            return;

        }//End of function citation_email_send..

        //XXXXXXXXXXXXXXXXXXXXXXX work End XXXXXXXXXXXXXXXXXXXXXXX






    </script>
