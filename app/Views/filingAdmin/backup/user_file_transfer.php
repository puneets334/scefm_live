<div class="right_col" role="main">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="row">
                                    <div class="col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">Select User</label>
                                            <div class="col-md-7 col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <select id="file_transfer" name="file_transfer" class="form-control input-sm" required>
                                                        <option value="">Select</option>
                                                        <?php
                                                        if(isset($users) && !empty($users)){
                                                            foreach ($users as $k=>$v){
                                                                echo '<option value="'.$v['user_id'].'">'.strtoupper($v['first_name']).'</option>';
                                                            }
                                                        }
                                                        ?>
                                                        <option value="0">File Not Allocated</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="row" style="display:none;" id="fileTransferUserDiv">
                                    <div class="col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label class="control-label col-md-5 col-sm-12 col-xs-12 input-sm">File Transfer To User </label>
                                            <div class="col-md-7 col-sm-12 col-xs-12">
                                                <div class="form-group">
                                                    <select id="file_transfer_to_user" name="file_transfer_to_user" class="form-control input-sm" required>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" id="buttonDiv" style="display: none;">
                                        <div class="form-group" style=" margin-left: 566px;">
                                            <button class="btn btn-primary" id="transferAll">File Transfer </button>
                                        </div>
                                </div>
                            </div>
                        </div>
                    <div class="row" style="display:none" id="fileTransferDiv" >
                        <table id="filetransfer" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Efiling No.</th>
                                <th>Cause Title</th>
                                <th>File Type</th>
                                <th>Diary No/Diary Year</th>
                                <th><label for="selectAll"><input type="checkbox" name="selectAll" id="selectAll"/><span style="margin-left: 7px;" id="selectSpan">Select All</span></label></th>
                            </tr>
                            </thead>
                        </table>
                </div>
            </div>
        </div>
    </div>

</div>
</div>
<script>
    $(document).ready(function(){
        $('#filetransfer').dataTable({
            "keys": false,
            "paging": false
        });
        $(document).on('change','#file_transfer',function(){
            var userId = $(this).val();
            if(userId == ''){
                alert("Please select user.");
                $("#file_transfer").focus();
                $("#file_transfer").css({'border-color':'red'});
                $("#filetransfer_wrapper").hide();
                $("#fileTransferDiv").hide();
                $("#fileTransferUserDiv").hide();
                $("#buttonDiv").hide();
                return false;
            }
            else {
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $.ajax({
                    type: "POST",
                    data: {CSRF_TOKEN: CSRF_TOKEN_VALUE,userId:userId },
                    url: "<?php echo base_url('filingAdmin/DefaultController/getEmpCaseData'); ?>",
                    async:false,
                    cache:false,
                    dataType:'json',
                    success: function(res)
                    {
                        if(res.caseData){
                            $("#filetransfer_wrapper").show();
                            $("#fileTransferDiv").show();
                            $("#fileTransferUserDiv").show();
                            $("#buttonDiv").show();
                            var result ='';
                            if(res.caseData && typeof res.caseData == 'string'){
                                result = JSON.parse(res.caseData);
                            }
                            else{
                                result = res.caseData;
                            }
                            $("#file_transfer_to_user").html('');
                            $("#file_transfer_to_user").html(res.trasferUser);
                            $("#filetransfer").DataTable().clear();
                            var length = Object.keys(result).length;
                            var ctn=1;
                                for(var i = 0; i < length; i++) {
                                    var cause_title= (result[i].cause_title) ? result[i].cause_title : '---';
                                    var diarydetails= (result[i].diarydetails) ? result[i].diarydetails : '---';
                                    var efiling_no= (result[i].efiling_no) ? result[i].efiling_no : '---';
                                    var registration_id= (result[i].registration_id) ? result[i].registration_id : '';
                                    var efiling_type = (result[i].efiling_type) ? result[i].efiling_type.replace(/_/g,' ').toUpperCase() : '---';
                                    var action = '<input class="selectAllcheckBox" type="checkbox" data-registration_id="'+registration_id+'" data-efiling_no="'+efiling_no+'" id="filecheckbox_'+ctn+'" name="filecheckbox" />';
                                    $('#filetransfer').dataTable().fnAddData( [
                                        ctn,
                                        efiling_no,
                                        cause_title,
                                        efiling_type,
                                        diarydetails,
                                        action
                                    ]);
                                    ctn++;
                                }
                        }
                        else
                            {   alert(res.message);
                                $("#buttonDiv").hide();
                                $("#fileTransferDiv").hide();
                                $("#fileTransferUserDiv").hide();
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
            }
        });
        $(document).on('click','#selectAll',function (){
            $('.selectAllcheckBox').each(function (index, obj) {
                if (this.checked === true) {
                    $(this).attr('checked',false);
                    $("#selectSpan").text("Select All");
                }
                else{
                    $(this).attr('checked',true);
                    $("#selectSpan").text("Deselect All");
                }
            });

        });

        $(document).on('click','#transferAll',function(){
            var file_transfer = $("#file_transfer option:selected").val();
            var file_transfer_to_user = $("#file_transfer_to_user option:selected").val();
            var validatinFlag = true;
            var fileArr = [];
            if(file_transfer == ''){
                alert("Please select user.");
                $("#file_transfer").focus();
                $("#file_transfer").css({'border-color':'red'});
                validatinFlag = false;
                return false;
            }
            else  if(file_transfer_to_user == ''){
                alert("Please select transfer user.");
                $("#file_transfer_to_user").focus();
                $("#file_transfer_to_user").css({'border-color':'red'});
                validatinFlag = false;
                return false;
            }
            $('.selectAllcheckBox').each(function (index, obj) {
                if(this.checked === true){
                    fileArr.push($(this).attr('data-registration_id'));
                }
            });
            if((validatinFlag) && (fileArr.length === 0)){
                alert("Please select file.");
                $("#filecheckbox_1").focus();
                $("#filecheckbox_1").css({'border-color':'red'});
                return false;
            }
            else{
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                var typeUser = {};
                typeUser.file_transfer_from = file_transfer;
                typeUser.file_transfer_to_user = file_transfer_to_user;
                var postData = {CSRF_TOKEN: CSRF_TOKEN_VALUE,userFile:fileArr,typeUser:typeUser };
                $.ajax({
                    type: "POST",
                    data: JSON.stringify(postData),
                    url: "<?php echo base_url('filingAdmin/DefaultController/fileTransferToAnOtherUser'); ?>",
                    async:false,
                    cache:false,
                    dataType:'json',
                    ContentType: 'application/json',
                    success: function(res)
                    {
                        //console.log(res);
                        // return false;
                        if(res && typeof res == 'string'){
                            res = JSON.parse(res);
                        }
                        if(res.success = 'success'){
                            var message = '';
                            message += res.message;
                            var efiling_no = res.efiling_no;
                            if(res.failedTotal){
                                message += ' and an unauthorized efiling No. '+efiling_no+' and total(s): '+res.failedTotal;
                            }
                            alert(message);
                            window.location.reload();
                        }
                        else  if(res.success = 'error'){
                            alert(res.message);
                            window.location.reload();
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
            }

        });


    });
</script>

