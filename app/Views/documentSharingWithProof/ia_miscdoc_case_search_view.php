<div class="right_co" rol="main">
    <div id="page-wrapper">
        <div class="panel-default">
                <?php
                if(!empty($this->session->flashdata('msg'))){
                    echo '<div class="form-response" id="msg" style="display: block;"><p  id="">'.$this->session->flashdata('msg').' <span class="close" onclick=hideMessageDiv()>X</span></p> </div>';
                }
                ?>
            <h4 style="text-align: center;color: #31B0D5"> Case Search </h4>
            <div class="panel panel-default">
                <?php
                $attribute = array('class' => 'form-horizontal', 'id' => 'search_case_details', 'name' => 'search_case_details', 'autocomplete' => 'off','novalidate'=>'novalidate');
                echo form_open('documentSharingWithProof/DefaultController/searchCaseByDiaryRegistration', $attribute);
                $stylediary="display:none";
                $styleReg="display:none";
                $checkedDiary = false;
                $checkedReg = false;
                if(empty($search_type)){
                    $stylediary="display:block";
                    $checkedDiary = true;
                }
                else if(!empty($search_type) && $search_type == 'diary'){
                    $stylediary="display:block";
                    $checkedDiary = true;
                }
                else if(!empty($search_type) && $search_type == 'register'){
                    $styleReg="display:block";
                    $checkedReg = true;
                }
                ?>
                <center>  <br>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <label class="radio-inline input-lg"><input type="radio"  <?php echo (!empty($checkedDiary)) ? 'checked' :'' ?> name="search_filing_type" value="diary" > Diary Number</label>
                        <label class="radio-inline input-lg"><input type="radio" name="search_filing_type" <?php echo (!empty($checkedReg)) ? 'checked' :'' ?> value="register"> Registration No</label>
                    </div>
                </center>
                <br><hr>
                <div class="diary box" style="<?php echo $stylediary;?>">
                    <div class="col-md-6 col-sm-4 col-xs-12" >
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12 input-lg"> Diary No. <span style="color: red">*</span>:</label>
                            <div class="col-md-9 col-sm-12 col-xs-12">
                                <div class="input-group">
                                    <input id="diaryno" name="diaryno" value="<?php echo (!empty(set_value('diaryno'))) ? set_value('diaryno') : (!empty($this->session->userdata('searchDiaryNo')) ? $this->session->userdata('searchDiaryNo') : '') ;?>" maxlength="10" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');"  placeholder="Diary No."  class="form-control input-lg age_calculate" type="text" required>
                                    <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Diary number should be digits only.">
                                        <i class="fa fa-question-circle-o"  ></i>
                                    </span>
                                </div>
                                <?php
                                if(!empty(form_error('diaryno'))){
                                    echo '<span id="error_diaryno">'.form_error('diaryno').'</span>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class=" col-md-6 col-sm-6 col-xs-12 ">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12 input-lg">Diary Year <span style="color: red">*</span>:</label>
                            <div class="col-md-9 col-sm-12 col-xs-12">
                                <div class="input-group">
                                    <input id="diary_year" name="diary_year" value="<?php echo (!empty(set_value('diary_year'))) ? set_value('diary_year') : (!empty($this->session->userdata('searchDiaryYear')) ? $this->session->userdata('searchDiaryYear') : '')  ?>" maxlength=4" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');"    placeholder="Diary Year"  class="form-control input-lg age_calculate" type="text" required>
                                    <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Case year should be digits only.">
                                        <i class="fa fa-question-circle-o"  ></i>
                                    </span>
                                </div>
                                <?php
                                if(!empty(form_error('diary_year'))){
                                    echo '<span id="error_diary_year">'.form_error('diary_year').'</span>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="register box" style="<?php echo $styleReg;?>">
                    <div class="col-md-4 col-sm-6 col-xs-12" >
                        <div class="form-group">
                            <label class="control-label col-md-4 col-sm-12 col-xs-12 input-lg"> Case Type <span style="color: red">*</span>:</label>
                            <div class="col-md-8 col-sm-12 col-xs-12">
                                <select name="sc_case_type" id="sc_case_type" class="form-control input-lg filter_select_dropdown"  style="width:100%;" required>
                                    <option value="" title="Select">Select Case Type</option>
                                    <?php
                                    if(isset($sc_case_type) && !empty($sc_case_type) && count($sc_case_type)>0) {
                                        foreach ($sc_case_type as $dataRes) {
                                          echo '<option  value="'.url_encryption(trim($dataRes->casecode)).'">'.$dataRes->casename.'</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <?php
                        if(!empty(form_error('sc_case_type'))){
                            echo '<span id="error_sc_case_type">'.form_error('sc_case_type').'</span>';
                        }
                        ?>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12  "  >
                        <div class="form-group">
                            <label class="control-label col-md-4 col-sm-12 col-xs-12 input-lg"> Case No. <span style="color: red">*</span>:</label>
                            <div class="col-md-8 col-sm-12 col-xs-12">
                                <div class="input-group">
                                    <input id="case_number" value="<?php echo (!empty(set_value('case_number'))) ? set_value('case_number') : (!empty($this->session->userdata('searchCaseNumber')) ? $this->session->userdata('searchCaseNumber') : '') ;?>" name="case_number" maxlength="10" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');"   placeholder="Case No."  class="form-control input-lg age_calculate" type="text" required>
                                    <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Related case number should be digits only.">
                                        <i class="fa fa-question-circle-o"  ></i>
                                    </span>
                                </div>
                                <?php
                                if(!empty(form_error('case_number'))){
                                    echo '<span id="error_case_number">'.form_error('case_number').'</span>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12  " >
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-12 col-xs-12 input-lg"> Case Year <span style="color: red">*</span>:</label>
                            <div class="col-md-9 col-sm-12 col-xs-12">
                                <div class="input-group">
                                    <input id="case_year" value="<?php echo (!empty(set_value('case_year'))) ? set_value('case_year') : (!empty($this->session->userdata('searchCaseYear')) ? $this->session->userdata('searchCaseYear') : '') ;?>" name="case_year" maxlength="4" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');"    placeholder="Case Year"  class="form-control input-lg age_calculate" type="text" required>
                                    <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Case year should be digits only.">
                                        <i class="fa fa-question-circle-o"  ></i>
                                    </span>
                                </div>
                                <?php
                                if(!empty(form_error('case_year'))){
                                    echo '<span id="error_case_year">'.form_error('case_year').'</span>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-offset-5"  style="<?php echo  !empty($this->session->userdata('searchDiaryRegistrationId')) ? 'float: right;margin-right: 14px;' : ''  ?>">
                        <input type="submit" class="btn btn-primary" id="search_sc_case" value="SEARCH">
                    </div>
                </div>
                <?php echo form_close(); ?>
                <br>
                <div id="show_search_result_diary">
                    <?php
                    if(!empty($this->session->userdata('searched_case_details')->diary_no)){
                    $searched_case_details = $this->session->userdata('searched_case_details');
                    $diary_no = $searched_case_details->diary_no;
                    $diary_year = $searched_case_details->diary_year;
                    $order_date = $searched_case_details->ord_dt;
                    $cause_title = strtoupper($searched_case_details->cause_title);
                    $reg_no_display = strtoupper($searched_case_details->reg_no_display);
                    $active_reg_year = $searched_case_details->active_reg_year;
                    $c_status = strtoupper($searched_case_details->c_status);
                    ?>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label class="col-md-4 text-right"><strong>Diary No :</strong></label>
                                <div class="col-md-8">
                                    <?php echo_data($diary_no . ' / ' . $diary_year); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label class="col-md-4 text-right"><strong>Registration No. :</strong></label>
                                <div class="col-md-8">
                                    <?php echo_data($reg_no_display); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label class="col-md-4 text-right"><strong>Cause Title :</strong></label>
                                <div class="col-md-8">
                                    <?php echo_data($cause_title); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label class="col-md-4 text-right"><strong>Status :</strong></label>
                                <div class="col-md-8">
                                    <?php echo_data($c_status == 'D'?'DISPOSED':'PENDING'); ?>
                                </div>
                            </div>
                        </div>
                        <?php
                        }
//                        else{
//                            echo '<p style="font-size:14px;" class="text-danger text-center"><strong>No Records Found !</strong></p>';
//                        }
                        ?>
                </div>
            </div>
    </div>
            <div class="panel panel-default">
             <div class="panel-body"  style="<?php echo  !empty($this->session->userdata('searchDiarynoYear')) ? 'display:block' : 'display:none'  ?>">
                            <?php
                            $form_attribute = array('class' => 'form-horizontal', 'id' => 'uploadDocument', 'name' => 'uploadDocument', 'autocomplete' => 'off','novalidate'=>'novalidate');
                            echo form_open_multipart('documentSharingWithProof/DefaultController/uploadDocumentForSharing', $form_attribute);
                            ?>
                            <div class="row col-md-12">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12 input-lg">Document <span style="color: red">*</span>:</label>
                                        <div class="col-md-9 col-sm-12 col-xs-12">
                                                <select id="document_id" name="document_id"  class="form-control input-lg filter_select_dropdown" style="width: 100%">
                                                    <option value="">Select Document</option>
                                                    <?php
                                                    if(isset($doc_type) && !empty($doc_type)) {
                                                    foreach ($doc_type as $docs) {
                                                    echo '<option value="' . htmlentities(url_encryption($docs['doccode']), ENT_QUOTES) . '">' . htmlentities(strtoupper($docs['docdesc']), ENT_QUOTES) . '</option>';
                                                      }
                                                    }
                                                    ?>
                                                    </select>
                                            <?php
                                            if(!empty(form_error('document_id'))){
                                                echo '<span id="error_document_id">'.form_error('document_id').'</span>';
                                            }
                                            ?>
                                            <span id="error_document_id"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-12 col-xs-12 input-lg">Title <span style="color: red">*</span>:</label>
                                        <div class="col-md-9 col-sm-12 col-xs-12">
                                            <div class="input-group">
                                                <input id="doc_title" name="doc_title" maxlength="75" minlength="3"  class="form-control input-lg" placeholder="Title" type="text" required>
                                                <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content=" PDF title max. length can be 75 characters only.  Only numbers, letters, spaces, hyphens,dots and underscores are allowed.">
                                                        <i class="fa fa-question-circle-o"  ></i>
                                                    </span>
                                            </div>
                                            <?php
                                            if(!empty(form_error('doc_title'))){
                                                echo '<span id="error_doc_title">'.form_error('doc_title').'</span>';
                                            }
                                            ?>
                                            <span id="error_doc_title"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-4 col-sm-12 col-xs-12 input-lg">Sub Document <span id="required_span" style="color: red ;display:none";>*</span>:</label>
                                        <div class="col-md-8 col-sm-12 col-xs-12">
                                            <select id="sub_document_id" name="sub_document_id"  class="form-control input-lg filter_select_dropdown" style="width: 100%">
                                                <option value="">Select Sub Document</option>
                                            </select>
                                            <?php
                                            if(!empty(form_error('sub_document_id'))){
                                                echo '<span id="error_sub_document_id">'.form_error('sub_document_id').'</span>';
                                            }
                                            ?>
                                            <span id="error_sub_document_id"></span>
                                        </div>

                                    </div>
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label class="control-label col-md-4 col-sm-12 col-xs-12 input-lg">Upload Document <span style="color: red">*</span>:</label>
                                            <div class="col-md-8 col-sm-12 col-xs-12">
                                                <div class="input-group">
                                                    <input id="shareDocument" name="shareDocument"  accept=".pdf" class="form-control input-lg" type="file" required>
                                                    <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Only pdf file.">
                                                        <i class="fa fa-question-circle-o"  ></i>
                                                    </span>
                                                </div>
                                                <?php
                                                if(!empty(form_error('shareDocument'))){
                                                    echo '<span id="error_shareDocument">'.form_error('shareDocument').'</span>';
                                                }
                                                ?>
                                                <span id="error_shareDocument"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                             <div class="row col-md-12">
                                 <div class="form-group">
                                     <label class="control-label col-md-3 col-sm-12 col-xs-12 input-lg"></label>
                                     <div class="col-md-9 col-sm-12 col-xs-12">
                                         <div class="input-group" style="float: right;">
                                             <input id="uploadButton"  name="uploadButton" value="UPLOAD PDF" style="float: right;margin-right: -24px;" class="btn btn-primary" type="submit" required />
                                         </div>
                                     </div>
                                 </div>
                             </div>
             </div>
                            <?php
                            echo form_close();
                            ?>
                </div>
            </div>
<div class="panel panel-default"  style="<?php echo  !empty($this->session->userdata('searchDiarynoYear')) ? 'display:block' : 'display:none'  ?>">
<div class="panel-body" id="tableData">
    <div class="row" style="float: right;">
        <div class="col-md-12">
            <button class="btn btn-primary" id="shareDocButton">Share Document</button>
        </div>
    </div>
    <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
        <thead>
        <tr class="success">
            <th>#</th>
            <th>Document Details</th>
            <th>Document Uploded Date</th>
            <th>View Pdf</th>
            <th>
                <label class="control-label" for="selectAll"><input type="checkbox" name="selectAll" id="selectAll">
                    <span style="margin-left: 7px; font-size: 12px;" id="selectSpan">Select All</span>
                </label>
            </th>
        </tr>
        </thead>
        <tbody>
        <?php
        $i=1;
        if(isset($docDetails) && !empty($docDetails)){
            foreach ($docDetails as $k=>$v){
                $doc_id = !empty($v['doc_id']) ? $v['doc_id'] : NULL;
                $doc_title = !empty($v['doc_title']) ? $v['doc_title'] : '---';
                $file_path = !empty($v['file_path']) ? $v['file_path'] : '';
                $uploaded_on = !empty($v['uploaded_on']) ? date('d/m/Y H:i:s',strtotime($v['uploaded_on'])) : '';
                $pdf_url =base_url($file_path);
                $shareUSers = '';
                $userids = '';
//                if(isset($sharedocUsersList) && !empty($sharedocUsersList)){
//                    if(array_key_exists($doc_id,$sharedocUsersList)){
//                        $matchUsers = $sharedocUsersList[$doc_id];
//                        $matchUsersArr = explode('@',$matchUsers);
//                        $nameData = $matchUsersArr[0];
//                        $nameDataArr = explode(',',$nameData);
//                        $useridData = $matchUsersArr[1];
//                        $useridData = trim($useridData, '"');
//                        $useridDataArr = explode(',',$useridData);
//                        $docTitleData = $matchUsersArr[2];
//                        $docTitleData = trim($docTitleData, '"');
//                        $docTitleDataArr = explode(',',$docTitleData);
//                        $shareDocId = $matchUsersArr[3];
//                        for($k=0;$k<count($nameDataArr);$k++){
//                            $name = trim($nameDataArr[$k], '\'"');
//                            $docTitle = trim($docTitleDataArr[$k], '\'"');
//                            $userids .=$useridDataArr[$k].',';
//                            $url = 'documentSharingWithProof/DefaultController/downloadPdf/?shareDocId='.$shareDocId.'&userID='.$userids;
//                            $shareUSers .='<div>Name:'.$name.' | Doc title:'.$docTitle.'</div><br>';
//                        }
//                        if(isset($shareUSers) && !empty($shareUSers)){
//                            $shareUSers .='<a target="_blank" class="downloadPdf_1" href="'.base_url($url).'"  >Download PDF</a>';
//                        }
//                    }
//                }
                $shareDetails ='';
              //  echo '<pre>'; print_r($v['shareDocDetails']); exit;
                if (isset($v['shareDocDetails']) && !empty($v['shareDocDetails'])) {
                        $shareDetails .= "Document Title: ".$v['shareDocDetails']['doc_title'] . "<br>";
                        $shareDetails .= "Document: ".$v['shareDocDetails']['document'] . "<br>";
                        $shareDetails .= "Sub Document: ".$v['shareDocDetails']['sub_document'] . "<br>";
                }


                echo '<tr>
                        <td>'.$i.'</td>
                        <td>'.$shareDetails.'</td>
                        <td>'.$uploaded_on.'</td>
                        <td> <a target="_blank" href="'.$pdf_url.'">View Pdf </a> </td>
                        <td><input type="checkbox" data-docid="'.$doc_id.'" class="selectAllcheckBox"  name="checkbox_'.$i.'" id="checkbox_'.$i.'"/> </td>
                </tr>';
                $i++;
            }
         } ?>
        </tbody></table>
</div>
    <div class="panel-body" id="sharedUsertableData">
        <div class="row" style="float: left;">
            <div class="col-md-12">
                <h2 class="">Shared Document(s)</h2>
            </div>
        </div>
        <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
            <thead>
            <tr class="success">
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Share Doc Details</th>
                <th>Shared On</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $tr= '';
            $n=1;
          // echo '<pre>'; print_r($groupWiseArrData); //exit;
            if(isset($groupWiseArrData) && !empty($groupWiseArrData)){
                foreach ($groupWiseArrData as $key=>$value){
                    $groupArr = $diaryArr[$key];
                    if(isset($groupArr) && !empty($groupArr)){
                        foreach($groupArr as $k=>$v){
                            if(isset($value[$v]) && !empty($value[$v])){
                                $name ='';
                                $email ='';
                                $download ='';
                                $created_on ='';
                              foreach ($value[$v] as $k1=>$v1){
                                  $name .=$v1->name."<br>";
                                  $email .=$v1->email."<br>";
                                }
                                $shareDetails ='';
                                if (isset($v1->shareDocDetails) && !empty($v1->shareDocDetails)) {
                                    foreach ($v1->shareDocDetails as $k2 => $v2) {
                                        $shareDetails .= "Document Title: ".$v2['doc_title'] . "<br>";
                                        $shareDetails .= "Document: ".$v2['document'] . "<br>";
                                        $shareDetails .= "Sub Document: ".$v2['sub_document'] . "<br>";
                                    }
                                }
                                $created_on = !empty($v1->created_on) ?  date('d/m/Y H:i:s',strtotime($v1->created_on)) : '';
                            }
                            $url = 'documentSharingWithProof/DefaultController/downloadPdf/?shareDocUniqId='.$v;
                            $download .='<a target="_blank" class="downloadPdf_1" href="'.base_url($url).'"  >Download PDF</a>';
                            $tr .= '<tr>
                                        <td>'.$n.'</td>
                                        <td>'.$name.'</td>
                                        <td>'.$email.'</td>
                                        <td>'.$shareDetails.'</td>
                                        <td>'.$created_on.'</td>
                                        <td>'.$download.'</td>
                                      </tr>';
                            $n++;
                        }

                    }
                }
            }
          echo $tr;
            ?>
            </tbody>
        </table>
    </div>
</div>
</div>
</div>
    <div class="modal fade" id="shareDocumentPopup" role="dialog" style="">
        <div class="modal-dialog">
            <div class="modal-content" style="margin-left: -118px;margin-right: auto;width: 883px;height: 500px;">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><span class="fa fa-pencil"></span>Share Document</h4>
                </div>
                <div class="modal-body">
                    <div class="panel-body" id="documentShareDiv">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-3">
                                        <div class="radio">
                                            <label class="control-label" for="shareUsers1">
                                                <input type="radio" id="shareUsersType1" class="shareUsersType" name="shareUsers" value="a_or" checked/>AOR
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="radio">
                                        <label class="control-label" for="shareUsers2">
                                            <input type="radio" id="shareUsersType2"  class="shareUsersType" name="shareUsers" value="sr_a" />SR. Advocate
                                        </label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="radio">
                                        <label class="control-label" for="shareUsers3">
                                            <input type="radio" id="shareUsersType3" class="shareUsersType" name="shareUsers" value="a_c" />Advocate</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="radio">
                                            <label class="control-label" for="shareUsers4">
                                                <input type="radio" id="shareUsersType4" class="shareUsersType" name="shareUsers" value="other" />Others</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <div class="row" style="margin-bottom: 9px;">
                            <div class="col-md-12">
                                <div class="col-md-8">
                                    <select id="share_user_id"  name="share_user_id"  class="form-control input-lg" multiple="multiple" style="width: 100%">
                                        <?php
                                        $allTypeUsers = '';
                                        if(isset($sharingUsers) && !empty($sharingUsers)) {
                                            foreach ($sharingUsers as $users) {
                                                echo '<option value="' .$users['id']. '">' . htmlentities(strtoupper($users['first_name']), ENT_QUOTES) . '</option>';
                                            }
                                            $allTypeUsers = json_encode($sharingUsers);
                                        }
                                        ?>
                                    </select>
                                </div>
                            <div class="col-md-2">
                            </div>
                            <div class="col-md-2">
                                <input type="submit" class="btn btn-primary" id="shareToEmail" value="Share To Email">
                            </div>
                        </div>
                        </div>
                        <!-- start others -->
                        <div class="row" id="othereUsersDiv" style="display: none;">
                            <div  class="col-md-12">
                                <div  class="col-md-4">
                                    <label class="control-label col-md-4">Name <span style="color: red">*</span>:</label>
                                    <div class="input-group">
                                        <input id="other_name" name="other_name" maxlength="40" minlength="3" class="form-control" placeholder="Name" type="text"/>
                                    </div>
                                </div>
                                <div  class="col-md-4">
                                    <label class="control-label col-md-4">Email <span style="color: red">*</span>:</label>
                                    <div class="input-group">
                                        <input id="other_email" name="other_email" maxlength="40"  class="form-control" placeholder="email" type="email" />
                                    </div>
                                </div>
                                <div  class="col-md-4">
                                    <label class="control-label col-md-4">Mobile <span style="color: red">*</span>:</label>
                                    <div class="input-group">
                                        <input id="other_mobile" name="doc_title" maxlength="10" minlength="10" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');"   class="form-control" placeholder="Mobile" type="text"  />
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- end others -->

                    <div class="row">
                        <div  class="col-md-12" id="selectedUsersDiv">
                            <table style="width:100%" id="useerData">
                                <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody id="tableBody">

                                </tbody>
                                </table>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" style="display:none">
            <table id="tmpTable">
                <tbody id="tmp_id"></tbody>
            </table>
        </div>
    </div>
<style>
    .box{
        display: none;
        background-color: none;
    }
    div.box {
        height: 60px;
        padding: 0;
        overflow: unset;
        border:  #fff;
        background-color: #fff;
    }
</style>
<script type="text/javascript">
    var allUsers;
    var allTypeUsersObject=null;
    var aor ='<?php echo $allTypeUsers;?>';
    shareUserArr = [];
    tr='';
    var uniqueArr = [];
    shareDocIdArr = [];
    $(document).ready(function () {
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
        $(document).on('click','.delete',function(){
            $(this).closest('tr').remove();
            var userId = parseInt($(this).closest('tr').attr('data-id'));
            if(userId && shareUserArr){
                shareUserArr =  shareUserArr.filter((res) => res.id != userId);
                };
            // console.log(shareUserArr);
            // return false;
        });
        //$(document).on('click','.downloadPdf',function (){
        //    var userId = $(this).attr('data-userid');
        //    var shareDocId = $(this).attr('data-shareDocId');
        //    var CSRF_TOKEN = 'CSRF_TOKEN';
        //    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        //    var postData = {CSRF_TOKEN: CSRF_TOKEN_VALUE, userId: userId,shareDocId:shareDocId};
        //    if(userId && shareDocId){
        //        $.ajax({
        //            type: "POST",
        //            data:JSON.stringify(postData) ,
        //            dataType:'json',
        //            contentType:'application/pdf',
        //            url: "<?php //echo base_url('documentSharingWithProof/DefaultController/downloadPdf'); ?>//",
        //            success: function (res)
        //            {
        //                console.log(res);
        //                return false;
        //                $.getJSON("<?php //echo base_url('csrftoken'); ?>//", function (result) {
        //                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
        //                });
        //            },
        //            error: function () {
        //                $.getJSON("<?php //echo base_url('csrftoken'); ?>//", function (result) {
        //                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
        //                });
        //            }
        //        });
        //    }
        //});

        if($('#share_user_id').length > 0){
            $('#share_user_id').multiselect({
                enableCaseInsensitiveFiltering: true,
                maxHeight: 200,
                buttonWidth: '400px'
            });
        }
        $(document).on('click','#shareToEmail',function(){
            var selectedUserArr = [];
            $(".tableRow").each(function (index, obj){
                selectedUserArr.push($(this).attr('data-id'));
                });
       // alert(shareDocIdArr);
       // alert(selectedUserArr);
        //return false;
        var other_name ;
        var other_email;
        var other_mobile;
        var userTypeSelected  = $(".shareUsersType:checked").val();
        if((shareDocIdArr && selectedUserArr.length > 0) || ($("#other_name").val() !== '')){
            //other users
            if(userTypeSelected && userTypeSelected == 'other'){
                var other_name = $("#other_name").val();
                var other_email = $("#other_email").val();
                var other_mobile = $("#other_mobile").val();
                var emailPattern = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                var mobilePattern = /^\d{10}$/;
                if(other_name == ''){
                    alert("Please fill name.");
                    $("#other_name").focus();
                    $("#other_name").css({'border-color':'red'});
                    return false;
                }
                else  if(other_email == ''){
                    alert("Please fill email.");
                    $("#other_email").focus();
                    $("#other_email").css({'border-color':'red'});
                    return false;
                }
                else  if(other_email  && !emailPattern.test(other_email)){
                    alert("Please fill valid  email.");
                    $("#other_email").focus();
                    $("#other_email").css({'border-color':'red'});
                    return false;
                }
                else  if(other_mobile == ''){
                    alert("Please fill mobile no.");
                    $("#other_mobile").focus();
                    $("#other_mobile").css({'border-color':'red'});
                    return false;
                }
                else  if(other_mobile  && !mobilePattern.test(other_mobile)){
                    alert("Please fill valid  mobile no.");
                    $("#other_email").focus();
                    $("#other_email").css({'border-color':'red'});
                    return false;
                }

            }
            if(confirm("Are you sure to share document ?") == true){
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                var postData = {CSRF_TOKEN: CSRF_TOKEN_VALUE, selectedUserArr: selectedUserArr,shareDocIdArr:shareDocIdArr,other_name:other_name,other_email:other_email,other_mobile:other_mobile};
                $.ajax({
                    type: "POST",
                    data:JSON.stringify(postData) ,
                    dataType:'json',
                    contentType:'application/json',
                    url: "<?php echo base_url('documentSharingWithProof/DefaultController/docShareToEmail'); ?>",
                    success: function (response)
                    {
                        // console.log(response);
                        // return false;

                        if(response.status == true){
                            alert(response.message);
                            window.location.reload();
                        }
                        else {
                            alert(response.message);
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
        }
        else{
                if(userTypeSelected && userTypeSelected =='other'){
                    alert("Please fill name.");
                    $("#other_name").focus();
                    $("#other_name").css({'border-color':'red'});
                    return false;
                }
                else{
                    alert("Please select user");
                    $("#share_user_id").focus();
                    $("#share_user_id").css({'border-color':'red'});
                    return false;
                }
             }
        });
        var ctn=0;
        var flags = {};
        $('input[name="shareUsers"]').click(function (){
           var userType = $(this).val();
           var options= '';
           if(userType){
               var CSRF_TOKEN = 'CSRF_TOKEN';
               var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
               var postData = {CSRF_TOKEN: CSRF_TOKEN_VALUE, userType: userType};
               //check other users
               if(userType && userType == 'other'){
                   $("#othereUsersDiv").show();
                   $('#share_user_id').html('');
                   $('#share_user_id').multiselect('rebuild');
               }
               else{
                   $("#othereUsersDiv").hide();
                   $.ajax({
                       type: "POST",
                       data:JSON.stringify(postData) ,
                       dataType:'json',
                       async:false,
                       contentType:'application/json',
                       url: "<?php echo base_url('documentSharingWithProof/DefaultController/getUsersByType'); ?>",
                       success: function (response)
                       {
                           if(response){
                               response.forEach((res)=>{
                                   options +='<option value="'+res.id+'">'+(res.first_name).toUpperCase()+'</option>';
                           });
                           }
                           $('#share_user_id').html('');
                           $("#share_user_id").html(options);
                           allUsers ='';
                           allUsers = response;
                           aor = '';
                           var uniqueUsersData = shareUserArr.filter(function(res) {
                               if (flags[res.id]) {
                                   return false;
                               }
                               flags[res.id] = true;
                               return true;
                           });
                           uniqueUsersData.forEach((currentValue, index, res)=>{
                               tr +='<tr data-id="'+currentValue.id+'" class="tableRow"><td>'+(index+1)+'</td><td>'+currentValue.first_name+'</td><td>'+currentValue.emailid+'</td><td>'+currentValue.moblie_number+'</td><td><button class="delete btn btn-primary"><i class="fa fa-trash" aria-hidden="true"></i></button></td></tr>';
                       });
                           $('#tableBody').html('');
                           $('#tableBody').html(tr);
                           $('#share_user_id').multiselect('rebuild');
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
        });
        tr = '';
        tmp = [];
        $(document).on('change','#share_user_id',function(e){
            var userArr = $(this).val();
            var userType = $('input[name="shareUsers"]:checked').val();
            if(userArr.length >0){
                if(allUsers){
                    allTypeUsersObject = allUsers;
                }
                else if(aor){
                    allTypeUsersObject = JSON.parse(aor);
                }
                var selectedUsers = allTypeUsersObject.filter((res => userArr.includes(res.id)));
                var tr= '';
                var tmp ={};
                if(selectedUsers){
                    selectedUsers.forEach((currentValue, index, res)=>{
                        tmp.id= currentValue.id;
                        tmp.first_name= currentValue.first_name;
                        tmp.emailid= currentValue.emailid;
                        tmp.moblie_number= currentValue.moblie_number;
                        shareUserArr.push(tmp);
                    });
                }
                var flags = {};
                var uniqueUsersData = shareUserArr.filter(function(res) {
                    if (flags[res.id]) {
                        return false;
                    }
                    flags[res.id] = true;
                    return true;
                });
                uniqueUsersData.forEach((currentValue, index, res)=>{
                    tr +='<tr data-id="'+currentValue.id+'" class="tableRow"><td>'+(index+1)+'</td><td>'+currentValue.first_name+'</td><td>'+currentValue.emailid+'</td><td>'+currentValue.moblie_number+'</td><td><button class="delete btn btn-primary"><i class="fa fa-trash" aria-hidden="true"></i></button></td></tr>';
            });
                $('#tableBody').html('');
                $('#tableBody').html(tr);
            }
            else {
                var flags = {};
                var uniqueUsersData = shareUserArr.filter(function(res) {
                    if (flags[res.id]) {
                        return false;
                    }
                    flags[res.id] = true;
                    return true;
                });
                uniqueUsersData.forEach((currentValue, index, res)=>{
                    tr +='<tr data-id="'+currentValue.id+'" class="tableRow"><td>'+(index+1)+'</td><td>'+currentValue.first_name+'</td><td>'+currentValue.emailid+'</td><td>'+currentValue.moblie_number+'</td><td><button class="delete btn btn-primary"><i class="fa fa-trash" aria-hidden="true"></i></button></td></tr>';
            });
                $('#tableBody').html('');
                $('#tableBody').html(tr);
            }
        });
        $('input[name="search_filing_type"]').click(function () {
            var inputValue = $(this).attr("value");
            if (inputValue == 'register') {
                $('#diaryno').val('');
                $('#diary_year').val('');
                $("#show_search_result_diary").css("display", "none");
                $("#show_search_result").css("display", "none");
            } else if (inputValue == 'diary') {
                $('#sc_case_type').val('');
                $('#case_number').val('');
                $('#case_year').val('');
                $("#show_search_result").css("display", "none");
                $("#show_search_result_diary").css("display", "none");
            }
            var targetBox = $("." + inputValue);
            $(".box").not(targetBox).hide();
            $(targetBox).show();
        });
        var sc_case_type_id = "<?php echo set_value('sc_case_type');?>";
        if(sc_case_type_id){
            $("#sc_case_type").val(sc_case_type_id);
        }
        $(document).on("click","#uploadButton",function (e){
            var fileReg = /(.*?)\.(pdf)$/;
            var shareDocument =  $("#shareDocument").val();
            var document_id = $("#document_id option:selected").val();
            var sub_document_id = $("#sub_document_id option:selected").val();
            var dataExist = $("#sub_document_id").attr('dataexist');
            var uploadDocSize = "<?php echo UPLOADED_FILE_SIZE;?>";
            var doc_title = $("#doc_title").val();
            validation = true;
            var uploadedDoc_size ='';
            if(shareDocument){
                uploadedDoc_size =  document.getElementById('shareDocument').files[0]['size'];
            }
            if(document_id == ''){
                $("#document_id").focus();
                $("#error_document_id").text("Please select document.");
                $("#error_document_id").css({'color':'red'});
                alert("Please select document.");
                validation = false;
                return false;
            }
            else if(sub_document_id == '' && dataExist == 'true' ){
                $("#sub_document_id").focus();
                $("#error_sub_document_id").text("Please select sub document.");
                $("#error_sub_document_id").css({'color':'red'});
                alert("Please select sub document.");
                validation = false;
                return false;
            }
            else if(doc_title == ''){
                $("#doc_title").focus();
                $("#error_doc_title").text("Please fill title of document.");
                $("#error_doc_title").css({'color':'red'});
                alert("Please fill title of document.");
                validation = false;
                return false;
            }
           else if(shareDocument == ''){
                $("#shareDocument").focus();
                $("#error_shareDocument").text("Please select document file.");
                $("#error_shareDocument").css({'color':'red'});
                alert("Please select document file.");
                validation = false;
                return false;
            }
            else if(!fileReg.test(shareDocument)){
                $("#shareDocument").focus();
                $("#error_shareDocument").text("Please select document type(pdf)");
                $("#error_shareDocument").css({'color':'red'});
                alert("Please select document type(pdf)");
                validation = false;
                return false;
            }
            else if(uploadedDoc_size > uploadDocSize){
                var filesizeMb = ((uploadedDoc_size)/1024)/1024;
                $("#shareDocument").focus();
                $("#error_shareDocument").text("Please select document file less than "+filesizeMb +"MB");
                $("#error_shareDocument").css({'color':'red'});
                alert("Please select document file less than "+filesizeMb +"MB");
                validation = false;
                return false;
            }
             if(validation){
               return true;
            }
        });
        $(document).on("change","#document_id",function(){
            var doc_type_code = $(this).val();
            var CSRF_TOKEN = 'CSRF_TOKEN';
            var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
            if(doc_type_code){
                $.ajax({
                    type: "POST",
                    data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, doc_type_code: doc_type_code},
                    url: "<?php echo base_url('documentIndex/Ajaxcalls/get_doc_type'); ?>",
                    success: function (data)
                    {
                        if((data.trim()).length > 0){
                            var replaceData = data;
                            replaceData = data.replace(/Select Index Sub Item/gi,'Select Sub Document');
                            replaceData = replaceData.replace(/[(]Court Fee:\d+[)]/gi,' ');
                            $('#sub_document_id').html(replaceData);
                            $('#sub_document_id').attr('dataexist',true);
                            $('#required_span').show();
                        }
                        else{
                            $('#required_span').hide();
                            $('#sub_document_id').html(' <option value="">Select Sub Document</option>');
                            $('#sub_document_id').attr('dataexist',false);
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
        $(document).on('click','#shareDocButton',function(e){
            e.preventDefault();
            shareDocIdArr = [];
            $('.selectAllcheckBox').each(function (index, obj) {
                if(this.checked === true){
                    shareDocIdArr.push($(this).attr('data-docid'));
                }
            });
            if(shareDocIdArr && shareDocIdArr.length>0){
                $("#shareDocumentPopup").modal('show');
                $('#tableBody').html('');
            }
            else {
                    alert("Please select atleast one document.");
                    $("#checkbox_1").focus();
                    $("#checkbox_1").css({'border-color':'red'});
                    return false;
            }
         });
    });

</script>
<style>
    table {
        font-family: arial, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }
    td, th {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
    }
    tr:nth-child(even) {
        background-color: #dddddd;
    }
</style>
