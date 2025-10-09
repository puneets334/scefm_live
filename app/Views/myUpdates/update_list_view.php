<div class="right_col" role="main">
    <div id="page-wrapper">
        <div class="panel-default">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <?php
                    $this->load->view('myUpdates/myUpdates_ribbon_view');
                    ?>
                </div>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="col-md-7 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="form-group">
                            <label class="control-label col-md-8 col-sm-12 col-xs-12 input-sm" style="font-size: 18px;"><?php echo echo_data($list_head); ?></label>
                        </div>
                    </div>
                </div>
            </div>  
            <div class="clearfix"></div>
            <div class="table-wrapper-scroll-y my-custom-scrollbar ">
                <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr class="success input-sm" role="row" >
                            <th width="5%"> # </th>
                            <th width="45%"> Diary / Registration No.</th>
                            <th width="15%"> Status </th>
                            <th width="12%"> Action </th>
                        </tr>
                    </thead>
                    <tbody>	

                        <?php
                        $i = 1;
                        foreach ($myUpdatesList as $dataRes) {

                            $pet_name = htmlentities($dataRes['pet_name'], ENT_QUOTES);
                            $res_name = htmlentities($dataRes['res_name'], ENT_QUOTES);

                            $reg_no_display = $diary_date = $next_date = $defects_notified_date = $disposal_date = $order_date = $last_order_date = $refiled_date = '';
                            $applied_date = $refiled_date = $applied_date = $ready_date = $delivery_date = $doc_filing_date = $caveat_date = '';


                            if (!empty($dataRes['reg_no_display'])) {
                                $reg_no_display = htmlentities($dataRes['reg_no_display'], ENT_QUOTES);
                                $reg_no_whats = '*REG. NO* : ' . htmlentities($dataRes['reg_no_display'], ENT_QUOTES) . '%0A';
                            }
                            $cause_titles = $reg_no_display . '<br>' . $pet_name . '<b> Vs. </b> ' . $res_name;

                            if (!empty($dataRes['diary_date'])) {

                                $diary_date = '<b>Diary Date :</b>' . htmlentities($dataRes['diary_date'], ENT_QUOTES);
                                $diary_date_mail = '<b>DIARY DATE : </b>' . date("d-m-Y", strtotime(htmlentities($dataRes['diary_date'], ENT_QUOTES))) . '<br>';
                                $diary_date_whats = '*DIARY DATE* : ' . date("d-m-Y", strtotime(htmlentities($dataRes['diary_date'], ENT_QUOTES))) . '%0A';
                            }

                            if (!empty($dataRes['next_date'])) {
                                $next_date = '<br><b>Next Date :</b>' . htmlentities($dataRes['next_date'], ENT_QUOTES);
                                $next_date_mail = '<b>NEXT DATE : </b>' . date("d-m-Y", strtotime(htmlentities($dataRes['next_date'], ENT_QUOTES))) . '<br>';
                                $next_date_whats = '*NEXT DATE* : ' . date("d-m-Y", strtotime(htmlentities($dataRes['next_date'], ENT_QUOTES))) . '%0A';
                            }

                            if (!empty($dataRes['defects_notified_date'])) {
                                $defects_notified_date = '<br><b>Defects Notified :</b>' . htmlentities($dataRes['defects_notified_date'], ENT_QUOTES);
                                $defects_notified_date_mail = '<b>DEFECTS NOTIFIED DATE : </b>' . date("d-m-Y", strtotime(htmlentities($dataRes['defects_notified_date'], ENT_QUOTES))) . '<br>';
                                $defects_notified_date_whats = '*DEFECTS NOTIFIED DATE* : ' . date("d-m-Y", strtotime(htmlentities($dataRes['defects_notified_date'], ENT_QUOTES))) . '%0A';
                            }


                            if (!empty($dataRes['disposal_date'])) {
                                $disposal_date = '<br><b>Disposal Date :</b>' . htmlentities($dataRes['disposal_date'], ENT_QUOTES);
                                $disposal_date_mail = '<b>DISPOSAL DATE : </b>' . date("d-m-Y", strtotime(htmlentities($dataRes['disposal_date'], ENT_QUOTES))) . '<br>';
                                $disposal_date_whats = '*DISPOSAL DATE* : ' . date("d-m-Y", strtotime(htmlentities($dataRes['disposal_date'], ENT_QUOTES))) . '%0A';
                            }


                            if (!empty($dataRes['order_date'])) {
                                $order_date = '<br><b>Order Date :</b>' . htmlentities($dataRes['order_date'], ENT_QUOTES);
                                $order_date_mail = '<b>ORDER DATE : </b>' . date("d-m-Y", strtotime(htmlentities($dataRes['order_date'], ENT_QUOTES))) . '<br>';
                                $order_date_whats = '*ORDER DATE* : ' . date("d-m-Y", strtotime(htmlentities($dataRes['order_date'], ENT_QUOTES))) . '%0A';
                            }

                            if (!empty($dataRes['last_order_date'])) {
                                $last_order_date = '<br><b>Last Order Date :</b>' . htmlentities($dataRes['last_order_date'], ENT_QUOTES);
                                $last_date_mail = '<b>LAST ORDER DATE : </b>' . date("d-m-Y", strtotime(htmlentities($dataRes['last_order_date'], ENT_QUOTES))) . '<br>';
                                $last_date_whats = '*LAST ORDER DATE* : ' . date("d-m-Y", strtotime(htmlentities($dataRes['last_order_date'], ENT_QUOTES))) . '%0A';
                            }

                            if (!empty($dataRes['refiled_date'])) {
                                $refiled_date = '<br><b>Refiled :</b>' . htmlentities($dataRes['refiled_date'], ENT_QUOTES);
                                $refiled_date_mail = '<b>REFILED DATE : </b>' . date("d-m-Y", strtotime(htmlentities($dataRes['refiled_date'], ENT_QUOTES))) . '<br>';
                                $refiled_date_whats = '*REFILED DATE* : ' . date("d-m-Y", strtotime(htmlentities($dataRes['refiled_date'], ENT_QUOTES))) . '%0A';
                            }

                            if (!empty($dataRes['applied_date'])) {
                                $applied_date = '<br><b>Applied Date :</b>' . htmlentities($dataRes['applied_date'], ENT_QUOTES);
                                $applied_date_mail = '<b>APPLIED DATE : </b>' . date("d-m-Y", strtotime(htmlentities($dataRes['applied_date'], ENT_QUOTES))) . '<br>';
                                $applied_date_whats = '*APPLIED DATE* : ' . date("d-m-Y", strtotime(htmlentities($dataRes['applied_date'], ENT_QUOTES))) . '%0A';
                            }

                            if (!empty($dataRes['ready_date'])) {
                                $ready_date = '<br><b>Ready Date :</b>' . htmlentities($dataRes['ready_date'], ENT_QUOTES);
                                $ready_date_mail = '<b>READY DATE : </b>' . date("d-m-Y", strtotime(htmlentities($dataRes['ready_date'], ENT_QUOTES))) . '<br>';
                                $ready_date_whats = '*READY DATE* : ' . date("d-m-Y", strtotime(htmlentities($dataRes['ready_date'], ENT_QUOTES))) . '%0A';
                            }

                            if (!empty($dataRes['delivery_date'])) {
                                $delivery_date = '<br><b>Delivery Date :</b>' . htmlentities($dataRes['delivery_date'], ENT_QUOTES);
                                $delivery_date_mail = '<b>DELIVERY DATE : </b>' . date("d-m-Y", strtotime(htmlentities($dataRes['delivery_date'], ENT_QUOTES))) . '<br>';
                                $delivery_date_whats = '*DELIVERY DATE* : ' . date("d-m-Y", strtotime(htmlentities($dataRes['delivery_date'], ENT_QUOTES))) . '%0A';
                            }

                            if (!empty($dataRes['doc_filing_date'])) {
                                $doc_filing_date = '<br><b>Filing Date :</b>' . htmlentities($dataRes['doc_filing_date'], ENT_QUOTES);
                                $doc_filing_date_mail = '<b>FILING DATE : </b>' . date("d-m-Y", strtotime(htmlentities($dataRes['doc_filing_date'], ENT_QUOTES))) . '<br>';
                                $doc_filing_date_whats = '*FILING DATE* : ' . date("d-m-Y", strtotime(htmlentities($dataRes['doc_filing_date'], ENT_QUOTES))) . '%0A';
                            }

                            if (!empty($dataRes['caveat_date'])) {
                                $caveat_date = '<br><b>Caveat Date :</b>' . htmlentities($dataRes['caveat_date'], ENT_QUOTES);
                                $caveat_date_date_mail = '<b>CAVEAT DATE : </b>' . date("d-m-Y", strtotime(htmlentities($dataRes['caveat_date'], ENT_QUOTES))) . '<br>';
                                $caveat_date_date_whats = '*CAVEAT DATE* : ' . date("d-m-Y", strtotime(htmlentities($dataRes['caveat_date'], ENT_QUOTES))) . '%0A';
                            }

                            //---MAIL MESSAGE--------//
                            $diary_no_mail = '<b>DIARY NUMBER : </b>' . htmlentities($dataRes['diary_no'], ENT_QUOTES) . '<br>';
                            $case_title_mail = '<br><b>CAUSE TITLE : </b>' . htmlentities($dataRes['pet_name'], ENT_QUOTES) . '<b> Vs. </b>' . htmlentities($dataRes['res_name'], ENT_QUOTES) . '<br>';

                            $mail_message = $diary_no_mail . $reg_no_display . '' . $case_title_mail . '' . $diary_date_mail . '' . $next_date_mail . '' .
                                    $defects_notified_date_mail . '' . $disposal_date_mail . '' . $order_date_mail . '' . $last_date_mail . '' . $refiled_date_mail . '' .
                                    $applied_date_mail . '' . $ready_date_mail . '' . $delivery_date_mail . '' . $doc_filing_date_mail . '' . $caveat_date_date_mail;

                            //---WHATSAPP MESSAGE--------//
                            $diary_no_whats = '*DIARY NO* : ' . htmlentities($dataRes['diary_no'], ENT_QUOTES) . '%0A';
                            $case_title_whats = '*CAUSE TITLE* : ' . htmlentities($dataRes['pet_name'], ENT_QUOTES) . ' *Vs*. ' . htmlentities($dataRes['res_name'], ENT_QUOTES) . '%0A';

                            $whatsapp_message = $diary_no_whats . '' . $reg_no_whats . '' . $case_title_whats . '' . $diary_date_whats . '' . $next_date_whats . '' .
                                    $defects_notified_date_whats . '' . $disposal_date_whats . '' . $order_date_whats . '' . $last_date_whats . '' . $refiled_date_whats . '' .
                                    $applied_date_whats . '' . $ready_date_whats . '' . $delivery_date_whats . '' . $doc_filing_date_whats . '' . $caveat_date_date_whats;
                            ?>
                            <tr>
                                <td><?php echo_data($i); ?></td>
                                <td><a href="<?=CASE_STATUS_API?>?d_no=<?php echo echo_data(substr_replace($dataRes['diary_no'], "", -4)); ?>&d_yr=<?php echo echo_data(substr($dataRes['diary_no'], -4)); ?>" target="_blank"><?php echo htmlentities($dataRes['diary_no'], ENT_QUOTES) . $cause_titles; ?></a></td>
                                <td><?php echo $diary_date . $next_date . $defects_notified_date . $disposal_date . $order_date . $last_order_date . $refiled_date . $applied_date . $ready_date . $delivery_date . $doc_filing_date . $caveat_date; ?></td>
                                <td>
                                    <a href="<?=CASE_STATUS_API?>?d_no=<?php echo echo_data(substr_replace($dataRes['diary_no'], "", -4)); ?>&d_yr=<?php echo echo_data(substr($dataRes['diary_no'], -4)); ?>" target="_blank">&nbsp;&nbsp;
                                        <a data-toggle="modal" href="#add_citation_modal1" id="add_citation" class="add_citation_value" data-id="<?php echo echo_data(substr_replace($dataRes['diary_no'], "", -4)); ?> / <?php echo echo_data(substr($dataRes['diary_no'], -4)); ?> " title="Add Citation"><i class="fa fa-book" style="font-weight: bold; font-size: 20px;"></i></a>&nbsp;&nbsp;
                                        <a data-toggle="modal" href="#add_notes_modal" id="add_notes" class="add_notes_value" data-id="<?php echo echo_data(substr_replace($dataRes['diary_no'], "", -4)); ?> / <?php echo echo_data(substr($dataRes['diary_no'], -4)); ?>" title="Add Notes"><i class="fa fa-file-text-o " style="font-weight: bold; font-size: 20px;"></i></a> &nbsp;&nbsp;
                                        <a data-toggle="modal" onclick="view_contact_details('<?php echo url_encryption($dataRes['diary_no']); ?>')" id="add_contact" class="add_contact_value" data-id="<?php echo $dataRes['diary_no']; ?>" title="Add Contact"><i class="fa fa-phone" style="font-weight: bold; font-size: 20px;"></i></a>&nbsp;&nbsp;

                                        <?php
                                        echo '<input class="col-md-1" type="hidden"  id="whats_msgid_' . $i . '" value="' . $whatsapp_message . '" >
                                              <a href="javascript:void(0);" onclick="whatsapp_openWin(' . $i . ')" charset="utf-8" class="fa fa-whatsapp" style="color:green;font-weight: bold; font-size: 20px;" title="WhatsApp Case Details"> </a>';
                                        ?> 

                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <!--                            <a data-toggle="modal" href="#article_mail_modal" id="send_mail_modal" class="send_mail_modal" data-id="<?php echo $re->cnr_no; ?>" style="font-weight: bold; font-size: 20px;"><i class="fa fa-envelope"></i></a>  -->
                                        <?php
                                        echo '<input class="col-md-1" type="hidden"  id="case_details_' . $i . '" value="' . $mail_message . '" >
                                              <input class="col-md-1" type="hidden"  id="subject_case_details_' . $i . '" value="' . $dataRes['diary_no'] . '" >&nbsp;&nbsp;
                                              <a data-toggle="modal" onclick="get_message_data(' . $i . ',\'' . 'mail' . '\')" href="#article_mail_modal" id="send_mail_modal" class="send_mail_modal" charset="utf-8" style="font-weight: bold; font-size: 20px;" title="Send Mail"><i class="fa fa-envelope"></i> </a>&nbsp;&nbsp;';
                                        ?></a>
                                </td>
                            </tr>
                            <?php
                            $i++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="add_citation_modal1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"><span class="fa fa-pencil"></span> Write Citation Details for Diary : <b><span id="citation_for_cnr"></span></b> </h4>
            </div>
            <?php
            $attribute = array('name' => 'add_citation_text1', 'id' => 'add_citation_text1', 'autocomplete' => 'off');
            echo form_open("#", $attribute);
            ?>
            <div class="modal-body">
                <div id="add_citation_alerts"></div>
                <div class="btn-toolbar editor" data-role="editor-toolbar" data-target="#editor-one">
                    <div class="btn-group">
                        <a class="btn" data-edit="bold" title="Bold (Ctrl/Cmd+B)"><i class="fa fa-bold"></i></a>
                        <a class="btn" data-edit="italic" title="Italic (Ctrl/Cmd+I)"><i class="fa fa-italic"></i></a>
                    </div>
                    <div class="btn-group">
                        <a class="btn" data-edit="insertunorderedlist" title="Bullet list"><i class="fa fa-list-ul"></i></a>
                        <a class="btn" data-edit="insertorderedlist" title="Number list"><i class="fa fa-list-ol"></i></a>
                    </div>
                    <div class="btn-group">
                        <a class="btn" data-edit="justifyleft" title="Align Left (Ctrl/Cmd+L)"><i class="fa fa-align-left"></i></a>
                        <a class="btn" data-edit="justifycenter" title="Center (Ctrl/Cmd+E)"><i class="fa fa-align-center"></i></a>
                        <a class="btn" data-edit="justifyright" title="Align Right (Ctrl/Cmd+R)"><i class="fa fa-align-right"></i></a>
                        <a class="btn" data-edit="justifyfull" title="Justify (Ctrl/Cmd+J)"><i class="fa fa-align-justify"></i></a>
                    </div>
                    <div class="btn-group">
                        <a class="btn" data-edit="undo" title="Undo (Ctrl/Cmd+Z)"><i class="fa fa-undo"></i></a>
                        <a class="btn" data-edit="redo" title="Redo (Ctrl/Cmd+Y)"><i class="fa fa-repeat"></i></a>
                    </div>
                </div>
                <div id="editor-one" class="editor-wrapper editor-citation" contenteditable="true"></div>
                <input type="hidden" name="citation_cnr_no" id="citation_cnr_no" value=""/>
                <input type="hidden" name="current_url" id="current_url" value="<?php echo current_url(); ?>"/>
                <textarea name="citation_description" id="citation_description"  style="display:none;"></textarea>
            </div>
            <div class="modal-footer">
                <div class="left"><a class="btn btn-warning" data-dismiss="modal">Cancel</a></div>
                <div class="right"><button type="submit" class="btn btn-success" id="save_citation">Save Citation</button></div>
            </div>
            <div class="view_citation_data"></div>
            <?php echo form_close(); ?>  
        </div>
    </div>
</div>
<div class="modal fade" id="add_notes_modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"><span class="fa fa-pencil"></span> Write Notes Details for Case : <b><span id="notes_for_cnr"></span></b> </h4>
            </div>
            <?php
            $attribute = array('name' => 'add_notes_text', 'id' => 'add_notes_text', 'autocomplete' => 'off');
            echo form_open("#", $attribute);
            ?>
            <div class="modal-body">
                <div id="add_notes_alerts"></div>
                <div class="btn-toolbar editor" data-role="editor-toolbar" data-target="#editor-one">
                    <div class="btn-group">
                        <a class="btn" data-edit="bold" title="Bold (Ctrl/Cmd+B)"><i class="fa fa-bold"></i></a>
                        <a class="btn" data-edit="italic" title="Italic (Ctrl/Cmd+I)"><i class="fa fa-italic"></i></a>
                    </div>
                    <div class="btn-group">
                        <a class="btn" data-edit="insertunorderedlist" title="Bullet list"><i class="fa fa-list-ul"></i></a>
                        <a class="btn" data-edit="insertorderedlist" title="Number list"><i class="fa fa-list-ol"></i></a>
                    </div>
                    <div class="btn-group">
                        <a class="btn" data-edit="justifyleft" title="Align Left (Ctrl/Cmd+L)"><i class="fa fa-align-left"></i></a>
                        <a class="btn" data-edit="justifycenter" title="Center (Ctrl/Cmd+E)"><i class="fa fa-align-center"></i></a>
                        <a class="btn" data-edit="justifyright" title="Align Right (Ctrl/Cmd+R)"><i class="fa fa-align-right"></i></a>
                        <a class="btn" data-edit="justifyfull" title="Justify (Ctrl/Cmd+J)"><i class="fa fa-align-justify"></i></a>
                    </div>
                    <div class="btn-group">
                        <a class="btn" data-edit="undo" title="Undo (Ctrl/Cmd+Z)"><i class="fa fa-undo"></i></a>
                        <a class="btn" data-edit="redo" title="Redo (Ctrl/Cmd+Y)"><i class="fa fa-repeat"></i></a>
                    </div>
                </div>
                <div id="editor-one" class="editor-wrapper editor-notes" contenteditable="true"></div>
                <input type="hidden" name="notes_cnr_no" id="notes_cnr_no" value=""/>
                <input type="hidden" name="current_url" id="current_url" value="<?php echo current_url(); ?>"/>
                <textarea name="notes_description" id="notes_description"  style="display:none;"></textarea>
            </div>
            <div class="modal-footer">
                <div class="left"><a class="btn btn-warning" data-dismiss="modal">Cancel</a></div>
                <div class="right"><button type="submit" class="btn btn-success" id="save_notes">Save Notes</button></div>
            </div>
            <div class="view_notes_data"></div>
            <?php echo form_close(); ?>  
        </div>
    </div>
</div>
<div class="modal fade" id="article_mail_modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" style="text-align: center;"><i class="fa fa-envelope"></i> Mail Case Details </h4>
                <div class="mail-response" id="mail_msg" ></div>
            </div>
            <?php
            $attribute = array('name' => 'send_mail_text', 'id' => 'send_mail_text', 'autocomplete' => 'off');
            echo form_open('#', $attribute);
            ?>
            <div class="modal-body">
                <div id="mail_response" style="display: none;">
                    <div class="thank-you-pop">
                        <img src="<?php echo base_url('assets/images/success_tick.png'); ?>" height="76px" width="76px" alt="Success">
                        <h2>Mail sent Successfully!</h2>
                        <p>Your submission is receive to recipient shortly!</p>
                        <p><a href="<?php echo current_url(); ?>" class="btn btn-default">Go Home</a></p>
                    </div>
                </div>
                <div class="row" id="mail_div_hide">
                    <div class="col-md-12 col-sm-12 col-xs-12">

                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="col-md-12 col-sm-12 col-xs-12" id="recipient_mail1"></div>
                        </div>
                        <div id="emailids" style="display: none;"></div>
                        <div class="clearfix"></div><br>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="col-md-12 col-sm-12 col-xs-12" >
                                <input type="text" id="recipient_mail" name="recipient_mail" class="form-control" maxlength="250" placeholder="Recipient's mail">
                                <p>( For more than one recipient, type comma after each email(Max 5 email ID) )</p>
                            </div>
                        </div>
                        <div class="clearfix"></div><br>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <input type="text" id="mail_subject" name="mail_subject" class="form-control" maxlength="100" placeholder="eMail Subject">
                            </div>
                        </div>
                        <div class="clearfix"></div><br>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <div class="x_panel" style="width: 528px; ">
                                    <span class="msg_text"></span>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div><br>

                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <div class="input-group">
                                    <img src="<?php echo base_url('captcha/' . $captcha['filename']); ?>" class="form-control captcha-img">
                                    <span class="input-group-addon" data-placement="bottom" data-toggle="popover">
                                        <div class="input-group-text">
                                            <img src="<?php echo base_url('assets/images/refresh.png') ?>" height="20px" width="20px"  alt="refresh" class="refresh_cap" />
                                        </div>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <input type="text" id="captcha_code" name="captcha_code" class="form-control" maxlength="6" placeholder="Captcha Text">
                            </div>
                        </div>

                        <input type="hidden" id="case_details_mail" name="case_details_mail">
                        <div class="clearfix"></div><br>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <input type="button"   class="cancel btn btn-danger btn-block" value="Cancel"> 
                            </div><div class="col-md-6 col-sm-6 col-xs-6"><input type="submit" id="send_mail" name="send_mail" class="btn btn-success btn-block" value="Submit"></div>
                        </div>

                    </div>
                </div>
            </div>
            <?php echo form_close(); ?>  
        </div>
    </div>
</div>

<div class="modal fade" id="add_contact_model" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" style="text-align: center;"><i class="fa fa-users"></i> Case Contacts </h4>
                <div class="contact-response" id="mail_msg" ></div>
            </div>
            <?php
            $attribute = array('name' => 'add_contact', 'id' => 'add_case_contact', 'autocomplete' => 'off');
            echo form_open('#', $attribute);
            ?>
            <div class="modal-body">

                <div class="row" id="contact_div_hide">
                    <div style="float: right"><span id="add_diary_contact"><i class="fa fa-plus"></i> Add New Contact</span></div>
                    <div class="col-md-12 col-sm-12 col-xs-12 add_diary_contact_form" style="display: none">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="col-md-12 col-sm-12 col-xs-12" id="contact"> </div>
                        </div>

                        <div class="col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="col-md-7 col-sm-12 col-xs-12">
                                    <label class="radio-inline"><input type="radio" name="contact" id="new" value="1" onclick="show_contact_list(1)" checked="">New</label>
                                    <label class="radio-inline"><input type="radio" name="contact" id="aor" value="3" onclick="show_contact_list(2)">AOR</label>
                                </div>
                            </div>
                        </div>

                        <div class="clearfix"></div><br>

                        <div id="aor_contact" style="display:none;">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="col-md-12 col-sm-12 col-xs-12" >
                                    <select name="aor_name" id="aor_name" class="form-control input-sm filter_select_dropdown" style="width: 100%">  
                                        <option>Select Contact</option>
                                    </select>
                                </div>
                            </div>
                        </div> 
                        <div class="clearfix"></div><br>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="col-md-12 col-sm-12 col-xs-12" >
                                <input type="text" id="name" name="name" class="form-control" maxlength="250" placeholder="Name">
                            </div>
                        </div>

                        <div class="clearfix"></div><br>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <input type="text" id="email_id" name="email_id" class="form-control" maxlength="250" placeholder="Email Id">
                            </div>

                        </div> <div class="clearfix"></div><br>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <input type="text" id="mobile_no" name="mobile_no" class="form-control" maxlength="10" placeholder="Mobile">
                            </div>
                        </div>
                        <div class="clearfix"></div><br>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <input type="text" id="o_contact" name="o_contact" class="form-control" maxlength="55" placeholder="Other contact">
                            </div>
                        </div>
                        <div class="clearfix"></div><br>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <input type="hidden" name="case_cnr_no" id="diary_number">
                                <input type="text" id="contact_type" name="contact_type" class="form-control" maxlength="10" placeholder="Petitioner, Respondent, Witness, Other">
                            </div>
                        </div>
                        <div class="clearfix"></div><br>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <input type="button"   class="cancel btn btn-danger btn-block" value="Cancel"> 
                            </div><div class="col-md-6 col-sm-6 col-xs-6"><input type="submit" id="send_mail" name="send_mail" class="btn btn-success btn-block" value="Submit"></div>
                        </div>
                    </div>
                </div>

                <div class="view_contact_list"></div>
            </div>
            <?php echo form_close(); ?>  
        </div>
    </div>
</div>
<div class="modal fade" id="edit_contact_model" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" style="text-align: center;"><i class="fa fa-envelope"></i> Case Contacts </h4>
                <div class="contact-response" id="mail_msg" ></div>
            </div>
            <?php
            $attribute = array('name' => 'edit_contact', 'id' => 'edit_contact', 'autocomplete' => 'off');
            echo form_open('#', $attribute);
            ?>
            <div class="modal-body">

                <div class="row" id="contact_div_hide">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="col-md-12 col-sm-12 col-xs-12" id="contact"></div>
                        </div>
                        <div id="contactids" style="display: none;"></div>
                        <div class="clearfix"></div><br>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="col-md-12 col-sm-12 col-xs-12" >
                                <input type="text" id="name" name="name" class="form-control" maxlength="250" placeholder="name">
                            </div>
                        </div>
                        <div class="clearfix"></div><br>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <input type="text" id="email_id" name="email_id" class="form-control" maxlength="250" placeholder="Email Id">
                            </div>

                        </div>
                        <div class="clearfix"></div><br>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <input type="text" id="mobile_no" name="mobile_no" class="form-control" maxlength="10" placeholder="mobile">
                            </div>
                        </div>
                        <div class="clearfix"></div><br>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <input type="text" id="o_contact" name="o_contact" class="form-control" maxlength="10" placeholder="Other contact">
                            </div>
                        </div>
                        <div class="clearfix"></div><br>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <input type="hidden" name="contact_id" id="case_cnr_no">
                                <input type="text" id="contact_type" name="contact_type" class="form-control" maxlength="10" placeholder="Petitioner, Respondent, Witness, Other">
                            </div>
                        </div>
                        <div class="clearfix"></div><br>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <input type="button"   class="cancel btn btn-danger btn-block" value="Cancel"> 
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <input type="submit" id="send_mail" name="send_mail" class="btn btn-success btn-block" value="Submit">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php echo form_close(); ?>  
        </div>
    </div>
</div>
<div class="modal fade" id="article_sms_modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" style="text-align: center;"><i class="fa fa-mobile"></i> SMS Case Details </h4>
                <div class="sms-response" id="sms_msg" ></div>
            </div>
            <?php
            $attribute = array('name' => 'send_sms_text', 'id' => 'send_sms_text', 'autocomplete' => 'off');
            echo form_open('#', $attribute);
            ?>
            <div class="modal-body">
                <div id="mobids" style="display: none;"></div>
                <div id="sms_response" style="display: none;">
                    <div class="thank-you-pop">
                        <img src="<?php echo base_url('assets/images/success_tick.png'); ?>" height="76px" width="76px" alt="Success">
                        <h2>SMS sent Successfully!</h2>
                        <p>Your submission is receive to recipient shortly!</p>
                        <p><a href="<?php echo current_url(); ?>" class="btn btn-default">Go Home</a></p>
                    </div>
                </div>

                <div class="row" id="sms_div_hide">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="col-md-12 col-sm-12 col-xs-12" id="recipient_mob1">

                            </div>
                        </div>
                        <div class="clearfix"></div><br>

                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="col-md-12 col-sm-12 col-xs-12" >
                                <input type="text" id="recipient_no" name="recipient_no" class="form-control" maxlength="54" placeholder="Recipient's Mobile">
                                <p>( For more than one recipient, type comma after each Mobile(Max 5 Mobile) )</p>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <div class="x_panel" style="width: 528px; ">
                                    <span class="msg_text"></span>

                                </div> </div>
                        </div>
                        <div class="clearfix"></div><br>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <input type="text" id="captcha_code" name="captcha_code" class="form-control" maxlength="6" placeholder="Captcha Text">
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <span class="captcha-img"> <?php echo $captcha['image']; ?></span>
                                <span><img src="<?php echo base_url('assets/images/refresh.png') ?>" height="20px" width="20px"  alt="refresh" class="refresh_cap" /></span>
                            </div>
                        </div>
                        <input type="hidden" id="case_details_sms" name="case_details_sms">
                        <div class="clearfix"></div><br>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <input type="button"   class="cancel btn btn-danger btn-block" value="Cancel"> 
                            </div><div class="col-md-6 col-sm-6 col-xs-6">
                                <input type="submit" id="send_sms" name="send_sms" class="btn btn-success btn-block" value="Submit">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php echo form_close(); ?>  
        </div>

    </div>
</div> 
<div class="modal fade" id="edit_contact_data" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>

<script type="text/javascript">
    $(document).ready(function () {

        $('#prodTabs a[href="#today"]').trigger('click');
        if (typeof (Chart) === 'undefined') {
            return;
        }

        $('#add_diary_contact').click(function () {
            $('.add_diary_contact_form').toggle();
        });
    });

    function show_contact_list(element) {
        if (element == 1) {
            $('#aor_contact').hide();
        } else if (element == 2) {
            $('#aor_contact').show();
            get_aor_contact_list()
        }

    }
    function get_efiling_cnr_details(cnr_number) {

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $("#getCnrModal").remove();
        $.ajax({
            type: "POST",
            url: '<?php echo base_url('Mycases/citation_notes/get_efile_cnr_data'); ?>',
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, cnr_number: cnr_number},
            success: function (data) {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {

                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                    var resArr = data.split('@@@');
                    $('#modal_loader').hide();
                    $("#getCnrModal").modal();

                    if (resArr[0] == 2) {

                        $('.body').append(resArr[1]);
                        $('#getCnrModal').modal();
                        $('#getCnrModal').modal("show");
                        $('#msg').hide();

                    } else if (resArr[0] == 1) {
                        $('#msg').show();
                        $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + " <span class='close' onclick=hideMessageDiv()>X</span></p>");
                    }
                });
            },
            error: function (result) {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
    }
    $('#listed_frm_dt,#listed_to_dt,#next_dt_frm,#next_dt_to').datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "dd-mm-yy"
    });
    /*$('input[type="checkbox"][name="purpose_filter[]"]').change(function () {
     document.forms['filter_data'].submit();
     });*/
    $('input[type="radio"][name="filter_case_type"]').change(function () {
        document.forms['filter_case_type'].submit();
    });
    $("#advance_show_button").click(function () {
        $("#advance_show_hide").toggle();
    });


    $(document).ready(function () {
        //-----Save Citation Details------//
        var cnr_number;
        var contact_id;
        $('.add_citation_value').click(function () {
            cnr_number = $(this).attr('data-id');
        });
        $('.add_contact_value').click(function () {
            cnr_number = $(this).attr('data-id');
        });

        /* $('.edit_contact_value').click(function () {
         contact_id = $(this).attr('data-id');
         alert(contact_id);
         });*/

        $('#add_citation_modal1').on('show.bs.modal', function (e) {
            $('#add_citation_text1').attr('action', '#');
            var case_data = cnr_number;
            var diary_no = case_data.replace(/[^a-z0-9\s]/gi, '').replace(/[/\s]/g, '');


            $('#citation_for_cnr').html(case_data);
            $('#citation_description').val("");
            $('.editor-citation').text("");
            $('#citation_cnr_no').val(diary_no);
            getCitation_n_NotesDetails(diary_no, 1);
        });
        $('#add_contact_model').on('show.bs.modal', function (e) {
            $('#case_cnr_no').val(cnr_number);
        });


        $('#add_case_contact').on('submit', function (e) {
            e.preventDefault();

            if ($('#add_case_contact').valid()) {

                var form_data = $(this).serialize();

                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

                $.ajax({
                    type: "POST",
                    data: form_data,
                    url: "<?php echo base_url('mycases/citation_notes/add_case_contact'); ?>",
                    success: function (data) {
                        $("#add_contact_model").modal("hide");
                        var resArr = data.split('@@@');
                        if (resArr[0] == 2) {

                            $('#msg').show();
                            $(".form-response").html("<p class='message valid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + " <span class='close' onclick=hideMessageDiv()>X</span></p>");

                        } else if (resArr[0] == 1) {
                            $('#msg').show();
                            $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + " <span class='close' onclick=hideMessageDiv()>X</span></p>");
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

            } else {
                return false;
            }

        });


        $('#add_citation_text1').on('submit', function (e) {

            e.preventDefault();
            var temp = $('.editor-citation').text();
            if (temp) {
                if (temp.length > 500) {
                    $('#add_citation_alerts').html('<div class="alert alert-danger">Maximum length 500 allowed.</div>');
                } else {
                    $('#citation_description').val($('.editor-citation').html());
                    var form_data = $(this).serialize();
                    var CSRF_TOKEN = 'CSRF_TOKEN';
                    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                    var diary_no = $('#citation_cnr_no').val();

                    $.ajax({
                        type: "POST",
                        data: form_data,
                        url: "<?php echo base_url('mycases/citation_notes/add_citation'); ?>",
                        success: function (data) {

                            var resArr = data.split('@@@');
                            getCitation_n_NotesDetails(diary_no, 1);
                            if (resArr[0] == 2) {
                                //$("#add_citation_modal1").modal("hide");
                                $('#msg').show();
                                $(".form-response").html("<p class='message valid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + " <span class='close' onclick=hideMessageDiv()>X</span></p>");


                            } else if (resArr[0] == 1) {
                                $('#msg').show();
                                $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + " <span class='close' onclick=hideMessageDiv()>X</span></p>");
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
            } else {
                $('#add_citation_alerts').html('<div class="alert alert-danger">Write Citation Details.</div>');
            }
        });

        //-----Save Notes Details------//

        var n_diary_number;
        $('.add_notes_value').click(function () {
            n_diary_number = $(this).attr('data-id');
        });
        $('#add_notes_modal').on('show.bs.modal', function (e) {
            $('#add_notes_text').attr('action', '#');
            var case_data = n_diary_number;
            $('#notes_for_cnr').html(case_data);
            var diary_no = case_data.replace(/[^a-z0-9\s]/gi, '').replace(/[/\s]/g, '');
            $('#notes_cnr_no').val(diary_no);
            getCitation_n_NotesDetails(diary_no, 2);
        });



        $('#add_notes_text').on('submit', function (e) {
            e.preventDefault();
            var diary_no = $('#notes_cnr_no').val();
            var temp = $('.editor-notes').text();
            if (temp) {
                if (temp.length > 500) {
                    $('#add_notes_alerts').html('<div class="alert alert-danger">Maximum length 500 allowed.</div>');
                } else {
                    $('#notes_description').val($('.editor-notes').html());
                    var form_data = $(this).serialize();
                    var CSRF_TOKEN = 'CSRF_TOKEN';
                    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

                    $.ajax({
                        type: "POST",
                        data: form_data,
                        url: "<?php echo base_url('mycases/citation_notes/add_notes'); ?>",
                        success: function (data) {
                            var resArr = data.split('@@@');
                            getCitation_n_NotesDetails(diary_no, 2);
                            if (resArr[0] == 2) {
                                //  $("#add_notes_modal").modal("hide");
                                $('#msg').show();
                                $(".form-response").html("<p class='message valid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + " <span class='close' onclick=hideMessageDiv()>X</span></p>");


                            } else if (resArr[0] == 1) {
                                $('#msg').show();
                                $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + " <span class='close' onclick=hideMessageDiv()>X</span></p>");
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
            } else {
                $('#add_notes_alerts').html('<div class="alert alert-danger">Write Notes Details.</div>');
            }
        });
    });
    function getCitation_n_NotesDetails(cnr_number, desc_type) {

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $("#getCodeModal").remove();
        $.ajax({
            type: "POST",
            url: '<?php echo base_url('mycases/citation_notes/get_citation_and_notes_list'); ?>',
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, type: desc_type, cnr_number: cnr_number},
            success: function (data) {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                    var resArr = data.split('@@@');

                    $(".view_citation_data").html(resArr[1]);
                    $(".view_notes_data").html(resArr[1]);


                });
            },
            error: function (result) {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
    }
    function getCitation_n_NotesDetails1(cnr_number, desc_type) {

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $("#getCodeModal").remove();
        $.ajax({
            type: "POST",
            url: '<?php echo base_url('mycases/citation_notes/get_citation_and_notes_list'); ?>',
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, type: desc_type, cnr_number: cnr_number},
            success: function (data) {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                    var resArr = data.split('@@@');
                    $('#modal_loader').hide();
                    $("#getCodeModal").modal();

                    if (resArr[0] == 2) {
                        $('.body').append(resArr[1]);
                        $('#getCodeModal').modal();
                        $('#getCodeModal').modal("show");
                        $('#msg').hide();

                    } else if (resArr[0] == 1) {
                        $('#msg').show();
                        $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + " <span class='close' onclick=hideMessageDiv()>X</span></p>");
                    }
                });
            },
            error: function (result) {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
    }

    function get_contact_details(cnr_number) {

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $("#getCodeModal").remove();
        $.ajax({
            type: "POST",
            url: '<?php echo base_url('mycases/citation_notes/get_contact_list'); ?>',
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, cnr_number: cnr_number},
            success: function (data) {

                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                    var resArr = data.split('@@@');
                    $('#modal_loader').hide();
                    $("#getCodeModal").modal();

                    if (resArr[0] == 2) {

                        $('.body').append(resArr[1]);
                        $('#getCodeModal').modal("show");
                        $('#msg').hide();

                    } else if (resArr[0] == 1) {
                        $('#msg').show();
                        $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + " <span class='close' onclick=hideMessageDiv()>X</span></p>");
                    }
                });
            },
            error: function (result) {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
    }

    function view_contact_details(diary_no) {

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $('#add_contact_model').modal("show");
        $('.view_contact_list').html("");

        $('#diary_number').val(diary_no);
        $.ajax({
            type: "POST",
            url: '<?php echo base_url('mycases/citation_notes/view_contact_list'); ?>',
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, diary_no: diary_no},
            success: function (data) {

                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    var resArr = data.split('@@@');

                    if (resArr[0] == 2) {
                        $('.view_contact_list').html(resArr[1]);
                        $('.add_diary_contact_form').hide();
                    } else if (resArr[0] == 1) {
                        $('.add_diary_contact_form').show();
                    }
                });
            },
            error: function (result) {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
    }

    function get_aor_contact_list() {

        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        $.ajax({
            type: "POST",
            url: '<?php echo base_url('mycases/citation_notes/aor_contact_list'); ?>',
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE},
            success: function (data) {
                $('#aor_name').html(data);
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            },
            error: function (result) {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
    }

    $('#aor_name').change(function () {

        var aor_name = $(this).val();
        var resArr = aor_name.split('#$');
        $('#name').val(resArr[0]);
        $('#mobile_no').val(resArr[1]);
        $('#email_id').val(resArr[2]);
        $('#contact_type').val('ADVOCATE');

    });

    function whatsapp_openWin_Citation(whatsapp_message) {
        var api_url = 'https://api.whatsapp.com/send?phone=&text=';
        myWindow = window.open(api_url + whatsapp_message, "_blank", "width=800,height=600");
    }

    function whatsapp_openWin_CitationNotes(whatsapp_message) {
        var api_url = 'https://api.whatsapp.com/send?phone=9582551896&text=';
        myWindow = window.open(api_url + whatsapp_message, "_blank", "width=800,height=600");
    }
    function whatsapp_openWin(id) {
        var api_url = 'https://api.whatsapp.com/send?phone=&text=';
        var whatsapp_message = document.getElementById("whats_msgid_" + id).value;
        myWindow = window.open(api_url + whatsapp_message, "_blank", "width=800,height=600");
    }
    function closeWin() {
        myWindow.close();
    }

    function delete_citation_n_notes(cino, date, type) {

        y = confirm("Are you sure want to delete ?");
        if (y == false)
        {
            return false;
        }
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $('.modal').remove();
        $('.modal-backdrop').remove();
        $('.body').removeClass("modal-open");
        $.ajax({
            type: 'POST',
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, cino: cino, date: date, type: type},
            url: '<?php echo base_url('mycases/citation_notes/delete_citation_n_notes'); ?>',
            success: function (data) {

                $("#getCodeModal").remove();
                var resArr = data.split('@@@');
                if (resArr[0] == 1) {
                    $('#msg_modal').show();
                    $(".form-response_modal").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                    setTimeout(function () {
                        $('#msg_modal').hide();
                    }, 3000);
                } else if (resArr[0] == 2) {
                    $('#msg_modal').show();
                    $(".form-response_modal").html("<p class='message valid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                    $("#getCodeModal").remove();
                    $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                        $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

                        $.ajax({
                            type: "POST",
                            url: '<?php echo base_url('mycases/citation_notes/get_citation_and_notes_list'); ?>',
                            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, after_delete: 'done', type: type, cnr_number: cino},
                            success: function (data) {

                                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                                    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                                    var resArr = data.split('@@@');

                                    if (resArr[0] == 2) {
                                        $('.body').append(resArr[1]);
                                        $('#getCodeModal').modal();
                                        $('#getCodeModal').modal("show");
                                        $('#msg').hide();

                                    } else if (resArr[0] == 1) {
                                        $('#msg').show();
                                        $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + " <span class='close' onclick=hideMessageDiv()>X</span></p>");
                                    }
                                });
                            },
                            error: function (result) {
                                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                                });
                            }
                        });
                    });
                }
            },
            error: function () {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
    }


    function get_message_data(id, type)
    {
        var type = type;
        var data = $('#case_details_' + id).val();
        var subject = $('#subject_case_details_' + id).val();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();

        $.ajax({
            type: 'POST',
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, subject: subject, type: type},
            url: '<?php echo base_url('mycases/citation_notes/get_recipients_mail'); ?>',
            success: function (data) {

                var resArr = data.split('@@@');
                $('#recipient_mail1').html(resArr[0]);
                $('#recipient_mob1').html(resArr[1]);
                $.ajax({
                    url: '<?= base_url('captcha_refresh') ?>',
                    success: function (data) {
                        $('.captcha-img').replaceWith('<span class="captcha-img">' + data + '</span>');
                        $('.inpt').val('');
                    }
                });
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
        if (type == 'mail') {
            $('#mail_subject').val("Case Details of Diary No " + subject);
            $('.msg_text').html(data);
            $('#case_details_mail').val(data);
        } else if (type == 'sms') {
            $('.msg_text').html(data);
            $('#case_details_sms').val(data);
        }

    }

    $('#article_mail_modal,#article_sms_modal').on('hidden.bs.modal', function (e) {
        $(this).find('form').trigger('reset');
        $('.error-tip').hide();
    });


    $(document).ready(function () {

        $('#send_mail_text').on('submit', function () {
            if ($('#send_mail_text').valid()) {
                var form_data = $(this).serialize();
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('mycases/citation_notes/send_sms_and_mail'); ?>",
                    data: form_data,
                    beforeSend: function () {
                        $('#send_mail').val('Please wait...');
                        $('#send_mail').prop('disabled', true);
                    },
                    success: function (data) {
                        $('#send_mail').val('Submit');
                        $('#send_mail').prop('disabled', false);
                        var resArr = data.split('@@@');
                        if (resArr[0] == 1) {
                            $('#mail_msg').show();
                            $(".mail-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                        } else if (resArr[0] == 2) {
                            $('#mail_msg').show();
                            $('#mail_response').show();
                            $('#mail_div_hide').hide();
                            setTimeout(function () {
                                $('#mail_msg').hide();
                                window.location.href = '<?php echo current_url(); ?>';
                            }, 3000);
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
            } else {
                return false;
            }
        });


        $('#send_sms_text').on('submit', function () {

            if ($('#send_sms_text').valid()) {
                var form_data = $(this).serialize();
                var CSRF_TOKEN = 'CSRF_TOKEN';
                var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('mycases/citation_notes/send_sms_and_mail'); ?>",
                    data: form_data,
                    beforeSend: function () {
                        $('#send_sms').val('Please wait...');
                        $('#send_sms').prop('disabled', true);
                    },
                    success: function (data) {

                        $('#send_sms').val('Submit');
                        $('#send_sms').prop('disabled', false);
                        var resArr = data.split('@@@');
                        if (resArr[0] == 1) {
                            $('#sms_msg').show();
                            $(".sms-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + "  <span class='close' onclick=hideMessageDiv()>X</span></p>");
                        } else if (resArr[0] == 2) {
                            $('#sms_msg').show();
                            $('#sms_response').show();
                            $('#sms_div_hide').hide();
                            setTimeout(function () {
                                $('#sms_msg').hide();
                                window.location.href = '<?php echo current_url(); ?>';
                            }, 3000);
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
            } else {
                return false;
            }
        });

    });
    $(".close").click(function () {
        $('#emailids').html("");
        $('#mobids').html("");
        $("#article_mail_modal").modal("hide");
        $("#article_sms_modal").modal("hide");
        $("#add_contact_model").modal("hide");
        $("#edit_contact_data").hide();
        $(".modal-backdrop").hide();
    });
    $(".cancel").click(function () {
        $('#emailids').html("");
        $('#mobids').html("");
        $("#article_mail_modal").modal("hide");
        $("#article_sms_modal").modal("hide");
        $("#add_contact_model").modal("hide");
        $("#edit_contact_data").hide();
        $(".modal-backdrop").hide();

    });



    function get_contacts(contact_id) {

        $("#edit_contact_data").show();
        $(".modal-backdrop").show();
        var CSRF_TOKEN = 'CSRF_TOKEN';
        var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
        $("#edit_contact_model").remove();
        $("#add_contact_model").modal("hide");

        $.ajax({
            type: "POST",
            url: '<?php echo base_url('mycases/citation_notes/case_contact'); ?>',
            data: {CSRF_TOKEN: CSRF_TOKEN_VALUE, id: contact_id},
            success: function (data) {

                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                    var CSRF_TOKEN_VALUE = $('[name="CSRF_TOKEN"]').val();
                    var resArr = data.split('@@@');
                    $('#modal_loader').hide();
                    $("#edit_contact_model").modal("");

                    if (resArr[0] == 2) {

                        $('#getCodeModal').modal("hide");
                        $('#edit_contact_data').html(resArr[1]);
                        $('#edit_contact_data').modal("show");
                        $('#msg').hide();

                    } else if (resArr[0] == 1) {
                        $('#msg').show();
                        $(".form-response").html("<p class='message invalid' id='msgdiv'>&nbsp;&nbsp;&nbsp; " + resArr[1] + " <span class='close' onclick=hideMessageDiv()>X</span></p>");
                    }
                });
            },
            error: function (result) {
                $.getJSON("<?php echo base_url('csrftoken'); ?>", function (result) {
                    $('[name="CSRF_TOKEN"]').val(result.CSRF_TOKEN_VALUE);
                });
            }
        });
    }
</script>


