<style>
    #case_paper_book ul li ul {
        margin-left: 40px;
        margin-top: 5px;
        font-size: 16px;
    }
    a{color: black;}


</style>
<!--79922020-->
<!--9542017-->

<br><br><br>
<div class="panels panel-default" style="padding-left: 100px;">

            <h4 style="text-align: center;color: #31B0D5"> Upload Jail Petition Document - Testing </h4>
            <div class="panel-body">
                <?php
                $attribute = array('class' => 'form-horizontal', 'name' => 'upload_to_space', 'id' => 'upload_to_space', 'autocomplete' => 'off','enctype'=>'multipart/form-data');
                echo form_open('dspace/DefaultController/data_submission_eprison', $attribute);
                ?>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <?php echo $this->session->flashdata('msg'); ?>
                    <div class="form-group col-sm-3" >
                        <label for="parent community">Parent Community</label>

                        <input type="text" class="form-control" name="parent_community" id="parent_community" placeholder="Parent Community" value="075dc6a7-869e-42d2-88ee-eb55b69d46" readonly required1>

                    </div>
                    <div class="form-group col-sm-3" >
                        <label for="email">Registration ID</label>
                        <input type="text" class="form-control" name="diary_id" id="diary_id" placeholder="Diary No" value="JPSCIN01001562020" required1>
                    </div>

                    <div class="form-group col-sm-3" >
                        <label for="email">Filing Date</label>
                        <input type="text" class="datepick form-control" name="filing_date" id="filing_date" VALUE="09-01-2017" placeholder="Filing date" required1>
                    </div>
                    <div class="form-group col-sm-3" >
                        <label for="email">Reg No Display</label>
                        <input type="text" class="form-control" name="reg_no_display" id="reg_no_display" placeholder="Registration  No" required1 value="SLP(C) No.001684/2017">
                    </div>
                    <div class="form-group col-sm-3" >
                        <label for="email">Registration Date</label>
                        <input type="text" class="datepick form-control" name="registration_date" id="registration_date" placeholder="Registration date" required1 value="11-01-2017">
                    </div>
                    <div class="form-group col-sm-3">
                        <label for="email">Petitioner Name</label>
                        <input type="text" class="form-control" name="petitioner_name" id="petitioner_name" placeholder="Petitioner Name" required1 value="DHRUVDESH METASTEEL PVT. LTD. REP. BY ITS DIRECTOR MR. MANGVI LAL DAGA S/O LATE BRIDHICHAND DAGA">
                    </div>
                    <div class="form-group col-sm-3">
                        <label for="email">Respondent Name</label>
                        <input type="text" class="form-control" name="respondent_name" id="respondent_name" placeholder="Respondent Name" required1 value="KIOCL LTD">
                    </div>


                    <div class="form-group col-sm-4">
                        <label for="email">Document Type</label>
                        <select class="form-control" name="document_type" id="document_type">
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
                        </select>
                    </div>

                    <div class="form-group col-sm-8">
                        <label for="email">Document Title</label>
                        <input type="text" class="form-control" name="document_title" id="document_title" placeholder="Document Title" required value="Record of Proceedings dated 26 Sept 2015">
                    </div>
                    <div class="form-group col-sm-3" >
                        <label for="email">Order Date</label>
                        <input type="text" class="datepick form-control" name="order_date" id="order_date" VALUE="25-01-2021" placeholder="Date of Order" required1>
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="email">Parent Documents:</label>
                        <select class="form-control" name="parent" id="parent">
                            <option value="Main Paper Book" selected>Main Paper Book</option>
                            <option value="Additional Document" selected>Additional Document</option>
                            <option value="Interlocutory application" selected>Interlocutory application</option>

                            <option value="Main Paper Book >> Index of Record of Proceesings" >Main Paper Book >> Index of Record of Proceedings</option>
                            <option value="Main Paper Book >> Index of Contents" selected>Main Paper Book >> Index of Contents</option>
                            <option value="Additional Document >> Index of Contents">Additional Document >> Index of Contents</option>
                        </select>
                    </div>
                    <div class="form-group col-sm-3">
                        <label for="pwd">Parent Documents Name</label>
                        <input type="text" class="form-control" name="parent" id="parent" placeholder="parent" value="Main Paper Book >> Index of Contents" required1>
                    </div>

                    <div class="form-group col-sm-3">
                        <label for="pwd">Document Parent Id</label>
                        <input type="text" class="form-control" name="document_parent_id" id="document_parent_id" placeholder="Document Parent Id" value="624de725-3d73-481a-bd42-f2e7cb93bfa6" required1>
                    </div>
                    <div class="form-group col-sm-3">
                        <label for="pwd">Groups</label>
                        <input type="text" class="form-control" name="groups" id="groups" placeholder="Document Groups" value="" required1 readonly>
                    </div>
                    <div class="form-group col-sm-3">
                        <label for="pwd">Index Page No. From</label>
                        <input type="text" class="form-control" name="index_page_no_from" id="index_page_no_from" placeholder="Indexing page no from" required1 value="2">
                    </div>
                    <div class="form-group col-sm-3">
                        <label for="email">Index Page No. To</label>
                        <input type="text" class="form-control" name="index_page_no_to" id="index_page_no_to" placeholder="Indexing page no to" required1 value="3">
                    </div>

                    <div class="form-group col-sm-3">
                        <label for="pwd">Display sequence No.</label>
                        <input type="text" class="form-control" name="display_sequence_number" id="display_sequence_number" placeholder="display_sequence_number" required1  value ="1.00000">
                    </div>

                    <div class="form-group col-sm-6">
                        <label for="pwd">Browse file:</label>
                        <input type="file" class="form-control" name="file" id="file" >
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="button">&nbsp;</label>
                        <button name="upload" type="submit" value="Upload" class="btn btn-success">Submit</button>
                    </div>
                </div>

                <div class="clearfix"></div><br><br>
                <?php echo form_close(); ?>
                <hr color="blue" >

                <div>
                    <div class="form-group col-sm-12" >
                        <div class="col-sm-6">
                            <label class="col-sm-6" for="dairy_no">Jail Petition Registration Number:</label>
                            <input type="number" class=" form-control" name="dairy_no" id="dairy_no" placeholder="search Diary No"  value="79922020" required1>
                        </div>
                        <div class="form-group col-sm-4">
                            <label for="button">&nbsp;</label><br>
                            <button  name="upload" type="button" id="searchButton" value="search" class="btn btn-info">Get Case Files</button>
                        </div>
                    </div>
                    <div id="case_paper_book">

                    </div>
                    <?php
                    //var_dump($uploaded_files);
                    echo "<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>";

                    if(!empty($uploaded_files) or 1==1)
                    {
                        ?>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                <tr class="success">
                                    <th width="3%">#</th>
                                    <th>Document(s) Title</th>
                                    <th>Cause Title</th>
                                    <th width="30%">Paper Book file</th>
                                </tr>
                                </thead>
                                <tbody>

                                <tr>
                                    <td><?= $sr; ?></td>
                                    <td > Paper Book <?=$sr;?></td>
                                    <td>abc <strong>Vs.</strong>XYZ </td>
                                    <td> <a target="_blank" href="/dspace/DefaultController/display_bitstream_content/69fe374f-cfcf-409a-b4e8-ce29b60c455b"> <?=$sr;?> Paper Book</a></td>
                                </tr>
                                <?php
                                $sr = 1;
                                foreach ($uploaded_files as $file)
                                {
                                    ?>
                                    <tr>
                                        <td><?= $sr; ?></td>
                                        <td > Paper Book <?=$sr;?></td>
                                        <td>abc <strong>Vs.</strong>XYZ </td>
                                        <td> <a target="_blank" href="/dspace/DefaultController/display_bitstream_content/<?=$file['id']?>"> <?=$sr;?> Paper Book</a></td>
                                    </tr>
                                    <?php
                                    $sr++;
                                } ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } ?>
                </div>
                <div class="well">
                    <?php
                    $attribute = array('class' => 'form-horizontal', 'name' => 'create_user', 'id' => 'create_user', 'autocomplete' => 'off','enctype'=>'multipart/form-data');
                    echo form_open('', $attribute);
                    ?>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <?php echo $this->session->flashdata('msg'); ?>
                        <div class="form-group col-sm-3" >
                            <label for="parent community">First Name</label>
                            <input type="text" class="form-control" name="first_name" id="first_name" placeholder="User First Name" value="Superintendent"  required1>
                        </div>
                        <div class="form-group col-sm-3" >
                            <label for="email">Last Name</label>
                            <input type="text" class="form-control" name="last_name" id="last_name" placeholder="User Last Name" value="(CJ-1)" required1>
                        </div>

                        <div class="form-group col-sm-3" >
                            <label for="email">User Name</label>
                            <input type="text" class="form-control" name="user_name" id="user_name" VALUE="" placeholder="User Name" required1>
                        </div>
                        <div class="form-group col-sm-3" >
                            <label for="email">Email Id</label>
                            <input type="text" class="form-control" name="email_id" id="email_id" placeholder="Email Id" required1 value="">
                        </div>
                        <div class="form-group col-sm-3" >
                            <label for="email">Password</label>
                            <input type="password" class="form-control" name="password" id="password" placeholder="Password" value="Test@4321">
                        </div>
                        <div class="form-group col-sm-4">
                            <label for="button">&nbsp;</label><br>
                            <button  name="createUser" type="button" id="createUser" value="Create User" class="btn btn-info">Create User</button>
                        </div>

                        <?php form_close() ?>
                        <div id="user_created_status">

                        </div>

                    </div>
                </div>

</div>
<script>
    $(document).ready(function() {
        $(function () {
            $('.datepick').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true
            });
        });

        $(document).on("click", "#searchButton", function () {
            $.post("<?=base_url()?>index.php/dspace/DefaultController/get_collection_all_items",
                {diaryId: $('#dairy_no').val()}, function(result){
                    if(result!=0){
                        console.log(result);
                        $("#case_paper_book").html(result);
                        /*var data = $.parseJSON(result);
                        $("#tab_identifier").val(data[0].id);*!/*/

                    }
                    else
                        alert("Not a single files uploaded for the case");

                });

        });

        $(document).on("click", "#createUser", function () {
            var first_name=$('#first_name').val();
            var last_name=$('#last_name').val();
            var user_name=$('#user_name').val();
            var email_id=$('#email_id').val();
            var password=$('#password').val();
            $.post("<?=base_url()?>index.php/dspace/DefaultController/create_user_in_dspace7",
                {first_name:first_name,last_name:last_name,user_name:user_name,email_id:email_id,password:password }, function(result){
                    if(result!=0){
                        console.log(result);
                        $("#user_created_status").html(result);
                    }
                    else
                        alert("Error while creating new user in Dspace");
                });

        });

       


        $("#case_paper_book ul li ul").parent().css("background-color", "red" );
        //$("#case_paper_book ul li ").parent().css(" background-color: #0055aa;");

    });
</script>