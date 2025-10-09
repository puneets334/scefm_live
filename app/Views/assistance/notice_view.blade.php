
<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div id="msg">
                <?php 
                $session = session();
                if ($session->getFlashdata('MSG')) {
                    echo $session->getFlashdata('MSG');
                }
                ?>
            </div> 
            <div class="x_panel">
                <div class="x_title">
                    <h2><i class="fa fa-file-text-o" aria-hidden="true"></i> Notice & Forms</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="row">
                        <?php
                        if (!$get_data) {
                            $action = 'assistance/notice/add_notice';
                        } else {
                            $action = 'assistance/notice/edit_notice/' . $get_data[0]['id'];
                        }
                        $attribute = array('class' => 'form-horizontal form-label-left', 'id' => 'add_notice', 'name' => 'add_notice', 'autocomplete' => 'off', 'enctype' => 'multipart/form-data');
                        echo form_open($action, $attribute);
                        ?>
                        <div class="col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label class="control-label col-sm-5"> Title <span style="color: red">*</span> :</label>
                                <div class="col-sm-7">
                                    <div class="input-group">
                                        <input id="notice_title" name="notice_title" value="<?php echo htmlentities($get_data[0]['news_title'], ENT_QUOTES); ?>" placeholder="Notice Title" maxlength="250"  class="form-control" type="text">
                                        <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Title only accept Charaters and Spaces(eg. ABC XYZ).">
                                            <i class="fa fa-question-circle-o" ></i>
                                        </span>
                                    </div> 
                                    <?php echo ($session->getFlashdata('news_title') != NULL) ? $session->getFlashdata('news_title') : NULL; ?>
                                </div>
                            </div>
                
                            <div class="form-group">
                                <label class="control-label col-sm-5">Document :</label>
                                <div class="col-sm-7">
                                    <input id="notice_doc"  name="notice_doc"  value=""  type="file">
                                    <?php if (!empty($get_data[0]['file_name'])) { ?>
                                    
                                        <span class="help-block"><a href="<?php echo base_url('assistance/news_event/news_pdf/' . url_encryption($get_data[0]['id'])); ?>" class="btn btn-link" target="_blank">View Old File</a></span>
                                    <?php } ?>
                                    <?php echo ($session->getFlashdata('news_doc') != NULL) ? $session->getFlashdata('news_doc') : NULL; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ln_solid"></div>
                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-4">
                            <?php if ($get_data[0] == '') { ?>
                                <input type="submit" name="add_notice" value="Add Notice" class="btn btn-success">
                            <?php } else { ?>
                                <input type="submit" name="add_user" value="Update Notice" class="btn btn-success">
                            <?php } ?>
                            <button onclick="location.href = '<?php echo base_url('assistance/notice/view'); ?>'" class="btn btn-primary" type="reset">Cancel</button>
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
        <!------------Table--------------------->
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_content">
                    <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                        <thead>
                            <tr class="success input-sm" role="row" >
                                <th>#</th>
                                <th>Notice & Form &nbsp;&nbsp;(Dated) </th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            foreach ($result as $value) {
                                ?>
                                <tr>
                                    <td data-key="#" width="1%"> <?php echo htmlentities($i++, ENT_QUOTES); ?></td> 
                                    <td data-key="Notice & Form &nbsp;&nbsp;(Dated) " width="19%"> <?php if ($value['file_name'] != '') { ?>
                                            <a href="<?php echo base_url('assistance/news_event/news_pdf/' . url_encryption($value['id'])); ?>" target="blank">    <i class="fa fa-file-pdf-o danger" aria-hidden="true"  style="color:red"></i>&nbsp; <?php echo htmlentities($value['news_title'], ENT_QUOTES); ?></a><br>
                                        <?php } else { ?><p style="color:#000"><?php echo htmlentities($value['news_title'], ENT_QUOTES); ?></p>
                                        <?php } ?>
                                        (<?php echo htmlentities(date('d-m-Y', strtotime($value['create_date'])), ENT_QUOTES); ?>)
                                    </td> 

                                    <td data-key="Action" width="5%">
                                        <a href="<?php echo base_url('assistance/notice/view/' . url_encryption($value['id'])); ?>" class="btn btn-warning btn-xs" ><i class="fa fa-edit"></i> Edit</a>
                                        <?php if ($value['is_active'] == 't') { ?>
                                            <a href="<?php echo base_url('assistance/notice/deactive_notice/' . url_encryption($value['id'])); ?>" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure, want to Deactivate this Notice?')">Deactivate</a><?php } ?>
                                        <?php if ($value['is_active'] == 'f') { ?>
                                            <a href="<?php echo base_url('assistance/notice/deactive_notice/' . url_encryption($value['id'])); ?>" class="btn btn-success btn-xs" onclick="return confirm('Are you sure, want to Activate this Notice?')">Activate</a><?php } ?>
                                    </td> 
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!------------Table--------------------->

    </div>
</div>
</div>
<script type="text/javascript">

    $(document).ready(function () {
      var data_check=<?php echo $get_data[0]['file_name']; ?>
        $('#add_notice').validate({
              
            focusInvalid: true,
            ignore: ":hidden",
            rules: {
                notice_title: {
                    required: true
                }, notice_doc: {
                    required: function () {
                    if (data_check=="") {
                        return false;
                    } else {
                        return true;
                    }
                }
                }

            },
            messages: {
                notice_title: {
                    required: 'Enter Title.'
                }, notice_doc: {
                     required:  'Please Upload Pdf.'
                }

            },
            highlight: function (element) {
                $(element).closest('.form-group').addClass('has-error');
            },
            unhighlight: function (element) {
                $(element).closest('.form-group').removeClass('has-error');
            },
            errorElement: 'span',
            errorClass: 'error-tip',
            errorPlacement: function (error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            }
        });
    });
</script>