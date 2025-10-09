@if(getSessionData('login') != '' && getSessionData('login')['ref_m_usertype_id'] == USER_ADMIN)
    @extends('layout.app')
@else
    @extends('layout.advocateApp')
@endif
@section('content')
    <link href="<?= base_url() ?>assets/css/bootstrap-datepicker.css" rel="stylesheet">
    <link href="<?= base_url() ?>assets/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="dashboard-section dashboard-tiles-area"></div>
                <div class="dashboard-section">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="dash-card">
                                {{-- Page Title Start --}}
                                <div class="title-sec">
                                    <h5 class="unerline-title"> Notice and Circulars </h5>
                                    <!-- <a href="javascript:void(0)" class="quick-btn pull-right" onclick="window.history.back()"><span class="mdi mdi-chevron-double-left"></span>Back</a> -->
                                    <a href="javascript:void(0)" onclick="window.history.back()" class="quick-btn pull-right"><span class="mdi mdi-chevron-double-left"></span>Back</a>
                                </div>
                                {{-- Page Title End --}}
                                {{-- Main Start --}}
                                @if (getSessionData('login') != '' && getSessionData('login')['ref_m_usertype_id'] != USER_ADMIN)
                                    <ul class="nav nav-tabs">
                                        <li class="nav-item">
                                            <a href="<?= base_url('assistance/notice_circulars'); ?>" aria-current="page" class="nav-link active">Circulars</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="<?= base_url('assistance/performas'); ?>" aria-current="page" class="nav-link">Performas</a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="<?= base_url('contact_us'); ?>" aria-current="page" class="nav-link">Contact Us</a>
                                        </li>
                                    </ul>
                                @endif
                                <div class="tab-pane active" id="home" role="tabpanel" aria-labelledby="home-tab">
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
                                            </div>
                                        </div>    
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-xs-12">            
                                                <div class="x_panel">                
                                                    <?php if (getSessionData('login') != '' && getSessionData('login')['ref_m_usertype_id'] == USER_ADMIN) { ?>
                                                        <div class="x_title">
                                                            <h2><i class="fa  fa-newspaper-o"></i> Notice and Circulars</h2>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                        <div class="x_content">
                                                            <?php
                                                            if (!($get_data)) {
                                                                $action = 'assistance/notice_circulars/add_notice_circurlar';
                                                            } else {
                                                                $action = 'assistance/notice_circulars/add_notice_circurlar/' . url_encryption($get_data[0]['id']);
                                                            }
                                                            $attribute = array('class' => 'form-horizontal form-label-left', 'id' => 'add_notice_circurlar', 'name' => 'add_notice_circurlar', 'autocomplete' => 'off', 'enctype' => 'multipart/form-data');
                                                            echo form_open($action, $attribute);
                                                            ?>
                                                                <div class="row">
                                                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6" >
                                                                        <div class="mb-3">
                                                                            <label class="form-label"> Title <span style="color: red">*</span> :</label>
                                                                            <input id="news_title" name="news_title" value="<?php if(!empty($get_data[0]['news_title'])) echo htmlentities($get_data[0]['news_title'], ENT_QUOTES); ?>" placeholder="News Title" maxlength="250"  class="form-control cus-form-ctrl" type="text" required>
                                                                            <!-- <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Title only accept Charaters and Spaces(eg. ABC XYZ)."><i class="fa fa-question-circle-o" ></i></span> -->
                                                                            <?php echo ($session->getFlashdata('news_title') != NULL) ? $session->getFlashdata('news_title') : NULL; ?>
                                                                        </div> 
                                                                    </div>
                                                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6" >
                                                                        <div class="mb-3">
                                                                            <label class="form-label">PDF <span style="color: red">*</span> :</label>
                                                                            <input id="news_doc"  name="news_doc" accept="application/pdf" value="" required type="file">
                                                                            <?php if (!empty($get_data[0]['file_name'])) { ?>
                                                                                <span class="help-block"><a href="<?php echo base_url('assistance/notice_circulars/view/' . url_encryption($get_data[0]['id'])); ?>" class="btn btn-link" target="_blank">View Old File</a></span>
                                                                            <?php } ?>
                                                                            <?php echo ($session->getFlashdata('news_doc') != NULL) ? $session->getFlashdata('news_doc') : NULL; ?>
                                                                        </div> 
                                                                    </div>
                                                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6" >
                                                                        <div class="mb-3">
                                                                            <label class="form-label"> Auto Deactivate On <span style="color: red">*</span> :</label>
                                                                            <input type="text" class="form-control cus-form-ctrl news_datepicker" id="deactivate_date" name="deactivate_date" placeholder="Deactivate Date" value="<?php if (!empty($get_data[0]['deactive_date']) && $get_data[0]['deactive_date'] != '') echo htmlentities(date('d-m-Y', strtotime($get_data[0]['deactive_date'])), ENT_QUOTES); ?>" required/>
                                                                            <!-- <span class="input-group-addon" data-placement="bottom" data-toggle="popover" data-content="Enter News Deactive Date"><i class="fa fa-question-circle-o" ></i></span> -->
                                                                            <?php echo ($session->getFlashdata('deactivate_date') != NULL) ? $session->getFlashdata('deactivate_date') : NULL; ?>
                                                                        </div> 
                                                                    </div>
                                                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 mt-4">
                                                                        <div class="mb-3">
                                                                            <label class="form-label">PDF is  <span style="color: red">*</span> :</label>
                                                                            <span>Notice</span>&nbsp;&nbsp;&nbsp;<label><input type="radio" class="radio-inline" value="Notice" name="pdf_is" <?php if(!empty($get_data[0]['is_notice']) && $get_data[0]['is_notice'] == 't') echo 'checked'; ?> ></label>
                                                                            <span>Circular</span>&nbsp;&nbsp;&nbsp;<label><input type="radio" class="radio-inline" value="Circular" name="pdf_is" <?php if(!empty($get_data[0]['is_circular']) && $get_data[0]['is_circular'] == 't') echo 'checked'; ?>></label>
                                                                            <?php echo ($session->getFlashdata('news_doc') != NULL) ? $session->getFlashdata('news_doc') : NULL; ?>
                                                                        </div> 
                                                                    </div>
                                                                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 my-3">
                                                                        <div class="save-form-details">
                                                                            <div class="save-btns">
                                                                                <?php if (empty($get_data[0])) { ?>
                                                                                    <input type="submit" name="add_notice" value="Add" class="quick-btn btn btn-primary">
                                                                                <?php } else { ?>
                                                                                    <input type="submit" name="update_notice" value="Update" class="quick-btn btn btn-primary">
                                                                                <?php } ?>
                                                                                <button onclick="location.href = '<?php echo base_url('assistance/notice_circulars'); ?>'" class="quick-btn gray-btn btn-primary" type="reset">Cancel</button>
                                                                            </div>
                                                                            <div class="form-fields-des">
                                                                                <p>Note: Fields Marked in <span class="red-txt">*</span> are mandatory</p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php echo form_close(); ?>
                                                        </div>
                                                    <?php } else { ?> 
                                                        <h2><i class="fa  fa-newspaper-o"></i> Notice and Circulars</h2>
                                                        <div class="clearfix"></div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>        
                                        <!------------Table--------------------->
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <!-- <div class="x_panel">
                                                    <div class="x_content">
                                                        <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%"> -->
                                                <div class="table-sec">
                                                    <div class="table-responsive">
                                                        <table id="datatable-responsive" class="table table-striped custom-table first-th-left">
                                                            <thead>
                                                                <tr class="success input-sm" role="row" >
                                                                    <th>#</th>
                                                                    <th>Item</th>
                                                                    <th>Notice / Circular (Dated)</th>
                                                                    <?php if (getSessionData('login') != '' && getSessionData('login')['ref_m_usertype_id'] == USER_ADMIN) { ?>
                                                                        <th>Will Deactivate On</th>
                                                                        <th>Action</th>
                                                                    <?php } ?>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $i = 1;
                                                                // pr($notice_circualrs);
                                                                foreach ($notice_circualrs as $itm) {
                                                                    $lbl_is_notice_circular = (!empty($itm['is_circular']) && $itm['is_circular'] == 't') ? 'Circular' : 'Notice';
                                                                    ?>
                                                                    <tr>
                                                                        <td width="5%" data-key="#"> <?php echo htmlentities($i++, ENT_QUOTES); ?></td>
                                                                        <td width="15%" data-key="Item"><?php echo $lbl_is_notice_circular; ?></td>
                                                                        <td data-key="Notice / Circular (Dated)"><a href="<?php echo base_url('assistance/notice_circulars/view/' . url_encryption($itm['id'])); ?>" target="blank"><i class="fa fa-file-pdf-o danger" aria-hidden="true"  style="color:red"></i>
                                                                                &nbsp;<?php echo htmlentities($itm['news_title'], ENT_QUOTES); ?><br>
                                                                                (<?php echo htmlentities(date('d-m-Y', strtotime($itm['create_date'])), ENT_QUOTES); ?>)
                                                                            </a>
                                                                        </td> 
                                                                        <?php if (getSessionData('login') != '' && getSessionData('login')['ref_m_usertype_id'] == USER_ADMIN) { ?>
                                                                            <td width="10%" data-key="Will Deactivate On"> <?php echo htmlentities(date('d-m-Y', strtotime($itm['deactive_date'])), ENT_QUOTES); ?></td>                                    
                                                                            <td width="15%" data-key="Action">
                                                                                <a href="<?php echo base_url('assistance/notice_circulars/edit/' . url_encryption($itm['id'])); ?>" class="btn btn-primary" role="button"><i class="fa fa-pencil"></i></a>
                                                                                <?php if ($itm['is_deleted'] == 'f') { ?>
                                                                                    <a href="<?php echo base_url('assistance/notice_circulars/action/' . url_encryption($itm['id'] . '$$' . 'D')); ?>" class="btn btn-danger" role="button" onclick="return confirm('Are you sure, want to Deactivate this Notice/Circular ?')">Deactivate</a><?php } ?>
                                                                                <?php if ($itm['is_deleted'] == 't') { ?>
                                                                                    <a href="<?php echo base_url('assistance/notice_circulars/action/' . url_encryption($itm['id'] . '$$' . 'A')); ?>" class="btn btn-success" role="button" onclick="return confirm('Are you sure, want to Activate this Notice/Circular ?')">Activate</a><?php } ?>
                                                                            </td>
                                                                        <?php } ?>
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
                                {{-- Main End --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<script src="<?= base_url() . 'assets/newAdmin/' ?>js/jquery351.min.js"></script>
<script src="<?= base_url() ?>assets/js/bootstrap-datepicker.js"></script>
<script src="<?= base_url() ?>assets/js/bootstrap-datepicker.min.js"></script>
<?php if (getSessionData('login') != '' && getSessionData('login')['ref_m_usertype_id'] == USER_ADMIN) { ?>
    <script type="text/javascript">
        $(document).ready(function () {
            $('.news_datepicker').datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: "dd-mm-yyyy",
                startDate: new Date(),
                // startDate: today,
                // yearRange: '2000:2099'
            });
            $.validator.addMethod('onecheck', function (value, ele) {
                return $("input:checked").length >= 1;
            }, 'Please Select Atleast One CheckBox')
            $('#add_notice_circurlar').validate({
                focusInvalid: true,
                ignore: ":hidden",
                rules: {
                    news_title: {
                        required: true
                    }, deactivate_date: {
                        required: true

                    },
                    "pdf_is[]": {
                        required: true,
                        minlength: 1
                    }
                },
                messages: {
                    news_title: {
                        required: 'Enter Title.'
                    }, deactivate_date: {
                        required: 'Enter deactivate date.'
                    },
                    "pdf_is[]": "Please select at least one Item."
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
<?php } ?>