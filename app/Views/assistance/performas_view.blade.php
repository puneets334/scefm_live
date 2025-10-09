@if(getSessionData('login') != '' && getSessionData('login')['ref_m_usertype_id'] == USER_ADMIN)
@extends('layout.app')
@else
@extends('layout.advocateApp')
@endif
@section('content')
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
                                <h5 class="unerline-title"> Performas </h5>
                                <!-- <a href="javascript:void(0)" class="quick-btn pull-right" onclick="window.history.back()"><span class="mdi mdi-chevron-double-left"></span>Back</a> -->
                                <a href="javascript:void(0)" onclick="window.history.back()" class="quick-btn pull-right"><span class="mdi mdi-chevron-double-left"></span>Back</a>
                            </div>
                            {{-- Page Title End --}}
                            {{-- Main Start --}}
                            @if (getSessionData('login') != '' && getSessionData('login')['ref_m_usertype_id'] != USER_ADMIN)
                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    <a href="<?= base_url('assistance/notice_circulars'); ?>" aria-current="page" class="nav-link">Circulars</a>
                                </li>
                                <li class="nav-item">
                                    <a href="<?= base_url('assistance/performas'); ?>" aria-current="page" class="nav-link active">Performas</a>
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
                                                        <h2><i class="fa  fa-newspaper-o"></i> Performa(s) </h2>
                                                        <div class="clearfix"></div>
                                                    </div>
                                                    <div class="x_content">
                                                        <?php
                                                        // pr($get_data);
                                                        if (!$get_data) {
                                                            $action = 'assistance/performas/add_notice_circurlar';
                                                        } else {
                                                            $action = 'assistance/performas/add_notice_circurlar/' . url_encryption($get_data[0]['id']);
                                                        }
                                                        $attribute = array('class' => 'form-horizontal form-label-left', 'id' => 'add_notice_circurlar', 'name' => 'add_notice_circurlar', 'autocomplete' => 'off', 'enctype' => 'multipart/form-data');
                                                        echo form_open($action, $attribute);
                                                        // pr($action);
                                                        ?>
                                                        <div class="row">
                                                            <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                                                <div class="mb-3">
                                                                    <label for="" class="form-label">Title<span style="color: red">*</span></label>
                                                                    <input id="performa_title" name="performa_title" value="<?php if (!empty($get_data[0]['performa_title'])) echo htmlentities($get_data[0]['performa_title'], ENT_QUOTES); ?>" placeholder="News Title" maxlength="250" class="form-control cus-form-ctrl" type="text">
                                                                    <?php echo ($session->getFlashdata('performa_title') != NULL) ? $session->getFlashdata('performa_title') : NULL; ?>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                                                <div class="mb-3">
                                                                    <label for="" class="form-label">PDF<span style="color: red">*</span></label>
                                                                    <input id="news_doc" name="news_doc" value="" type="file" class="form-control cus-form-ctrl">
                                                                    <?php if (!empty($get_data[0]['file_name'])) { ?>
                                                                        <span class="help-block"><a href="<?php echo base_url('assistance/performas/view/' . url_encryption($get_data[0]['id'])); ?>" class="btn btn-link" target="_blank">View Old File</a></span>
                                                                    <?php } ?>
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
                                                                        <button onclick="location.href = '<?php echo base_url('assistance/performas'); ?>'" class="quick-btn gray-btn btn-primary" type="reset">Cancel</button>
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
                                                    <h2><i class="fa  fa-newspaper-o"></i> Performa(s)</h2>
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
                                                            <tr class="success input-sm" role="row">
                                                                <th>#</th>
                                                                <th> Performa(s) </th>
                                                                <?php if (getSessionData('login') != '' && getSessionData('login')['ref_m_usertype_id'] == USER_ADMIN) { ?>
                                                                    <th>Action</th>
                                                                <?php } ?>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $i = 1;
                                                            foreach ($notice_circualrs as $itm) {
                                                                // $lbl_is_notice_circular = $tim['is_circular'] ? 'Circular' : 'Notice';
                                                            ?>
                                                                <tr>
                                                                    <td width="5%" data-key="#"> <?php echo htmlentities($i++, ENT_QUOTES); ?></td>
                                                                    <td data-key="Performa(s)"><a href="<?php echo base_url('assistance/performas/view/' . url_encryption($itm['id'])); ?>" target="blank"><i class="fa fa-file-pdf-o danger" aria-hidden="true" style="color:red"></i>
                                                                            &nbsp;<?php echo htmlentities($itm['performa_title'], ENT_QUOTES); ?><br>
                                                                            (<?php echo htmlentities(date('d-m-Y', strtotime($itm['create_date'])), ENT_QUOTES); ?>)
                                                                        </a>
                                                                    </td>
                                                                    <?php if (getSessionData('login') != '' && getSessionData('login')['ref_m_usertype_id'] == USER_ADMIN) { ?>
                                                                        <td width="15%" data-key="Action">
                                                                            <a href="<?php echo base_url('assistance/performas/edit/' . url_encryption($itm['id'])); ?>" class="btn btn-secondary" role="button">Edit</a>
                                                                            <?php if ($itm['is_deleted'] == 'f') { ?>
                                                                                <a href="<?php echo base_url('assistance/performas/action/' . url_encryption($itm['id'] . '$$' . 'D')); ?>" class="btn btn-danger" role="button" onclick="return confirm('Are you sure, want to Deactivate this Notice/Circular ?')">Deactivate</a><?php } ?>
                                                                            <?php if ($itm['is_deleted'] == 't') { ?>
                                                                                <a href="<?php echo base_url('assistance/performas/action/' . url_encryption($itm['id'] . '$$' . 'A')); ?>" class="btn btn-success" role="button" onclick="return confirm('Are you sure, want to Activate this Notice/Circular ?')">Activate</a><?php } ?>

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
@push('script')
<?php if (getSessionData('login') != '' && $_SESSION['login']['ref_m_usertype_id'] == USER_ADMIN) { ?>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.news_datepicker').datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: "dd-mm-yy",
                minDate: new Date,
                yearRange: '2000:2099'
            });
            $.validator.addMethod('onecheck', function(value, ele) {
                return $("input:checked").length >= 1;
            }, 'Please Select Atleast One CheckBox')
            $('#add_notice_circurlar').validate({
                focusInvalid: true,
                ignore: ":hidden",
                rules: {
                    performa_title: {
                        required: true
                    },
                    deactivate_date: {
                        required: true
                    },
                    "pdf_is[]": {
                        required: true,
                        minlength: 1
                    }
                },
                messages: {
                    performa_title: {
                        required: 'Enter Title.'
                    },
                    deactivate_date: {
                        required: 'Enter deactivate date.'
                    },
                    "pdf_is[]": "Please select at least one Item."
                },
                highlight: function(element) {
                    $(element).closest('.form-group').addClass('has-error');
                },
                unhighlight: function(element) {
                    $(element).closest('.form-group').removeClass('has-error');
                },
                errorElement: 'span',
                errorClass: 'error-tip',
                errorPlacement: function(error, element) {
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
<script>
    $(document).ready(function() {
        $('#datatable-responsive').DataTable();
    });
</script>
@endpush