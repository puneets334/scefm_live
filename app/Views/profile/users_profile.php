<style>
    #photo_data{display:none;}
</style>
<!-- page content -->
<div class="right_col" role="main">
    <div id="page-wrapper">

        <?php echo $this->session->flashdata('msg'); ?>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>User Profile</h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <br />
                        <div class="col-md-12 col-sm-12 col-xs-12 profile_details">

                            <div class="well profile_view">
                                <div class="col-sm-12">

                                    <div class="col-xs-7">
                                        <h2><?php echo htmlentities($profile[0]->first_name, ENT_QUOTES); ?> <?php echo htmlentities($profile[0]->last_name, ENT_QUOTES); ?></h2>
                                        <ul class="list-unstyled">
                                            <li><strong>Email Id : </strong> <?php echo htmlentities($profile[0]->emailid, ENT_QUOTES); ?> </li>
                                            <li><i class="fa fa-phone"></i> Mobile #: <?php echo htmlentities($profile[0]->moblie_number, ENT_QUOTES); ?></li>
                                            <p><i class="fa fa-phone"></i> Other Contact #: <?php echo htmlentities($profile[0]->other_contact_number, ENT_QUOTES); ?></p>
                                        </ul>
                                        <ul class="list-unstyled">
                                            <li><i class="fa fa-building"></i> Address: <?php echo htmlentities($profile[0]->address1, ENT_QUOTES); ?>,
                                                <?php echo htmlentities($profile[0]->address2, ENT_QUOTES); ?>,<br>
                                                &nbsp;&nbsp;&nbsp;&nbsp;City - <?php echo htmlentities($profile[0]->city, ENT_QUOTES); ?>,
                                                Pincode - <?php echo htmlentities($profile[0]->pincode, ENT_QUOTES); ?> </li>

                                        </ul>
                                    </div>
                                    <div class="col-xs-5 text-center">
                                        <?php
                                        $file = 'user_images/photo/' . $profile[0]->photo_name_uploaded;
                                        if ($file != '') {
                                            ?>
                                            <img src="<?= base_url() . $file ?>" alt="..." class="img-circle img-responsive">
                                        <?php } else { ?>
                                            <img src="<?= base_url() ?>assets/images/user.png" alt="..." class="img-circle img-responsive">
                                        <?php } ?>
                                    </div>
                                    <!--                                     <div class="col-xs-12 bottom text-center emphasis">
                                    <?php
                                    if ($profile[0]->is_active == 't') {
                                        $status = "Deactive";
                                        $class = 'btn btn-danger';
                                    } else {
                                        $status = "Active";
                                        $class = 'btn btn-success';
                                    }
                                    ?>
                                                                    <input  name="deactive" type="button" data-target="#myModal" data-toggle="modal" style="width: 100px;" value="<?php echo $status ?>" class="<?php echo $class ?>">
                                                                 
                                                                         </div>-->

                                </div>

                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div><?php if ($remark != 0) { ?>
            <!--        <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="x_panel">
                                            <div class="x_content">
                                                <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr class="success input-sm" role="row" >
                                                            <th>#</th>
                                                            <th>Date</th>
                                                           <th>Remarks</th>
                                                            <th>Status</th>
                                                            
                                                        </tr>
                                                    </thead>
                                                    <tbody>
            <?php
            $i = 1;
            $j = 1;
            foreach ($remark as $re) {
                $msg = $re->remark;
                if ($re->activated_on != NULL) {
                    $st_date = $re->activated_on;
                    $status = "Active";
                } else {
                    $st_date = $re->deactived_on;
                    $status = "Deactive";
                }
                ?>
                                                                            <tr>
                                                                                <td width="4%" style="color:black;"> <?php echo htmlentities($i++, ENT_QUOTES); ?></td>  
                                                                                <td width="12%" style="color:black;"><?php echo htmlentities(ucwords($st_date), ENT_QUOTES); ?></td>  
                                                                                 <td width="10%" style="color:black;"><?php echo htmlentities($msg, ENT_QUOTES); ?></td>
                                                                                <td width="10%" style="color:black;"><?php echo htmlentities($status, ENT_QUOTES); ?></td>
                                                                            </tr>
                <?php
                $j++;
            }
            ?>
            
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                    </div>-->
        <?php } ?>
    </div>
</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">
                    <span class="fa fa-pencil"></span><?php
                    if ($profile[0]->is_active == 't') {
                        echo "Write Reason For Deactivation";
                    } else {
                        echo "Write Reason For Activation";
                    }
                    ?>
                </h4>
            </div>
            <?php
            $attribute = array('name' => 'disapp_case', 'id' => 'disapp_case', 'autocomplete' => 'off');
            if ($profile[0]->is_active == 't') {
                echo form_open('admin/deactive_user', $attribute);
            } else {
                echo form_open('admin/activated_user', $attribute);
            }
            ?>
            <div style="padding: 10px;">
                <div class="x_content">
                    <div id="alerts"></div>
                    <div class="btn-toolbar editor" data-role="editor-toolbar" data-target="#editor-one">
                        <div class="btn-group">
                            <a class="btn dropdown-toggle" data-toggle="dropdown" title="Font"><i class="fa fa-font"></i><b class="caret"></b></a>
                            <ul class="dropdown-menu">
                            </ul>
                        </div>

                        <div class="btn-group">
                            <a class="btn dropdown-toggle" data-toggle="dropdown" title="Font Size"><i class="fa fa-text-height"></i>&nbsp;<b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a data-edit="fontSize 5">
                                        <p style="font-size:17px">Huge</p>
                                    </a>
                                </li>
                                <li>
                                    <a data-edit="fontSize 3">
                                        <p style="font-size:14px">Normal</p>
                                    </a>
                                </li>
                                <li>
                                    <a data-edit="fontSize 1">
                                        <p style="font-size:11px">Small</p>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="btn-group">
                            <a class="btn" data-edit="bold" title="Bold (Ctrl/Cmd+B)"><i class="fa fa-bold"></i></a>
                            <a class="btn" data-edit="italic" title="Italic (Ctrl/Cmd+I)"><i class="fa fa-italic"></i></a>
                            <a class="btn" data-edit="strikethrough" title="Strikethrough"><i class="fa fa-strikethrough"></i></a>
                            <a class="btn" data-edit="underline" title="Underline (Ctrl/Cmd+U)"><i class="fa fa-underline"></i></a>
                        </div>

                        <div class="btn-group">
                            <a class="btn" data-edit="insertunorderedlist" title="Bullet list"><i class="fa fa-list-ul"></i></a>
                            <a class="btn" data-edit="insertorderedlist" title="Number list"><i class="fa fa-list-ol"></i></a>
                            <a class="btn" data-edit="outdent" title="Reduce indent (Shift+Tab)"><i class="fa fa-dedent"></i></a>
                            <a class="btn" data-edit="indent" title="Indent (Tab)"><i class="fa fa-indent"></i></a>
                        </div>

                        <div class="btn-group">
                            <a class="btn" data-edit="justifyleft" title="Align Left (Ctrl/Cmd+L)"><i class="fa fa-align-left"></i></a>
                            <a class="btn" data-edit="justifycenter" title="Center (Ctrl/Cmd+E)"><i class="fa fa-align-center"></i></a>
                            <a class="btn" data-edit="justifyright" title="Align Right (Ctrl/Cmd+R)"><i class="fa fa-align-right"></i></a>
                            <a class="btn" data-edit="justifyfull" title="Justify (Ctrl/Cmd+J)"><i class="fa fa-align-justify"></i></a>
                        </div>

                        <div class="btn-group">
                            <a class="btn dropdown-toggle" data-toggle="dropdown" title="Hyperlink"><i class="fa fa-link"></i></a>
                            <div class="dropdown-menu input-append">
                                <input class="span2" placeholder="URL" type="text" data-edit="createLink" />
                                <button class="btn" type="button">Add</button>
                            </div>
                            <a class="btn" data-edit="unlink" title="Remove Hyperlink"><i class="fa fa-cut"></i></a>
                        </div>
                        <div class="btn-group">
                            <a class="btn" data-edit="undo" title="Undo (Ctrl/Cmd+Z)"><i class="fa fa-undo"></i></a>
                            <a class="btn" data-edit="redo" title="Redo (Ctrl/Cmd+Y)"><i class="fa fa-repeat"></i></a>
                        </div>
                    </div>

                    <textarea  id="editor-one" class="editor-wrapper" name="remark" required="" style="width:98%"></textarea><br/>
                    <br/>

                    <div class="modal-footer">
                        <input type="submit" name="Save" value="Submit" class="btn btn-primary">
                    </div>
                    <?php echo form_close(); ?>  
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /page content -->

<script>

    function selectPhoto(id) {
        if (document.getElementById(id).checked) {
            document.getElementById('photo_data').click();
            document.getElementById('update_photo').disabled = false;
        } else {
            document.getElementById('update_photo').disabled = true;
        }
    }

</script>