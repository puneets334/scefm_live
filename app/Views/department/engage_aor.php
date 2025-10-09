
<div class="x_panel panel panel-default">
    <div class="panel-body">

        <!-- Main content -->
        <div class="uk-margin-small-top uk-border-rounded">
            <?php
            if(!empty($this->session->flashdata('success'))){
                echo "<span style='color:green;margin-left: 529px;'>".$this->session->flashdata('success')."</span>";
            }
            if(!empty($this->session->flashdata('error'))){
                echo "<span style='color:red;margin-left: 529px;'>".$this->session->flashdata('error')."</span>";
            }
            if(isset($arguingCounselDetails['registration_code']) && !empty($arguingCounselDetails['registration_code'])){
                $advocate_name = !empty($arguingCounselDetails['advocate_name']) ? $arguingCounselDetails['advocate_name'] : '';
                $mobile_number = !empty($arguingCounselDetails['mobile_number']) ? $arguingCounselDetails['mobile_number'] : '';
                $emailid = !empty($arguingCounselDetails['emailid']) ? $arguingCounselDetails['emailid'] : '';
                $bar_reg_no = !empty($arguingCounselDetails['bar_reg_no']) ? $arguingCounselDetails['bar_reg_no'] : '';
                $action="saveArguingCounselCompleteDetails";
                $attribute = array('class' => 'form-horizontal form-label-left', 'enctype'=>'multipart/form-data', 'id' => 'saveArguingCounselCompleteDetails', 'name' => 'saveArguingCounselCompleteDetails', 'autocomplete' => 'off');
                echo form_open($action, $attribute);
                ?>
                <div class="uk-child-width-1-1 uk-child-width-1-2@m uk-grid-small uk-grid" uk-grid="" uk-height-viewport="offset-top:true" style="min-height: calc(100vh - 60.4062px);">
                    <!-- left box start -->
                    <div class="uk-visible@m uk-first-column">
                        <div class="uk-margin">
                            <label class="uk-form-label" for="form-stacked-text">Name <span style="color: red">*</span> :</label>
                            <div class="uk-form-controls">
                                <input class="uk-input" type="text" name="name" id="name" placeholder="Name"  value="<?php echo set_value('name'); ?>" maxlength="100" minlength="3" tabindex="1">
                            </div>
                            <?php  if(form_error('name')){
                                echo "<span style='color:red'>".form_error('name')."</span>";
                            }
                            ?>
                            <div id="error_name"></div>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label" for="form-stacked-text">Mobile <span style="color: red">*</span> :</label>
                            <div class="uk-form-controls">
                                <input class="uk-input" type="text" name="mobile" id="mobile" value="<?php echo $mobile_number;?>" placeholder="9876543XXX" maxlength="10"   tabindex="3" readonly>
                            </div>
                            <?php  if(form_error('mobile')){
                                echo "<span style='color:red'>".form_error('mobile')."</span>";
                            }
                            if(!empty($this->session->flashdata('mobileExistMessage'))){
                                echo "<span style='color:red'>".$this->session->flashdata('mobileExistMessage')."</span>";
                            }
                            ?>
                            <div id="error_mobile"></div>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label" for="form-stacked-select">Relation <span style="color: red">*</span> :</label>
                            <div class="uk-form-controls">
                                <select class="uk-select" tabindex = '5' name="relation" id="relation"  >
                                    <option value="">Select Relation</option>
                                    <option value="S">Son Of</option>
                                    <option value="D">Daughter Of</option>
                                    <option value="W">Spouse Of</option>
                                    <option value="N">Not Available</option>
                                </select>
                                <?php  if(form_error('ralation')){
                                    echo "<span style='color:red'>".form_error('relation')."</span>";
                                }
                                ?>
                                <div id="error_relation"></div>
                            </div>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label" for="form-stacked-select">AOR <span style="color: red">*</span> :</label>
                            <div class="uk-form-controls">
                                <select class="uk-select"  tabindex = '5' name="aor" id="aor">
                                    <option value="" >Select AOR</option>
                                    <?php
                                    if(isset($aorList) && !empty($aorList)){
                                        foreach($aorList as $k=>$v) {
                                            $name = '';
                                            $name = $v['first_name'];
                                            echo '<option value="'.$v['id'].'">'.$name.'</option>';
                                        }
                                    }
                                    ?>
                                </select>
                                <?php  if(form_error('aor')){
                                    echo "<span style='color:red'>".form_error('aor')."</span>";
                                }
                                ?>
                                <div id="error_aor"></div>
                            </div>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label" for="form-stacked-text">Chamber Address :</label>
                            <div class="uk-form-controls">
                                <textarea class="uk-textarea" rows="2" name="c_address" id="c_address"  placeholder="Address" maxlength="250"  tabindex="7"><?php echo set_value('c_address');?></textarea>
                            </div>
                            <?php  if(form_error('c_address')){
                                echo "<span style='color:red'>".form_error('c_address')."</span>";
                            }
                            ?>
                            <div id="error_c_address"></div>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label" for="form-stacked-text">Pincode :</label>
                            <div class="uk-form-controls">
                                <input class="uk-input" type="text"  value="<?php echo set_value('c_pincode'); ?>" name="c_pincode" onkeyup="return isNumber(event)" id="c_pincode"  placeholder="Pincode" maxlength="6"   tabindex="8" />
                            </div>
                            <?php  if(form_error('c_pincode')){
                                echo "<span style='color:red'>".form_error('c_pincode')."</span>";
                            }
                            ?>
                            <div id="error_c_pincode"></div>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label" for="form-stacked-text">City :</label>
                            <div class="uk-form-controls">
                                <input class="uk-input" type="text" value="<?php echo set_value('c_city'); ?>" name="c_city" id="c_city"  placeholder="City" maxlength="25"  tabindex="9" />
                            </div>
                            <?php  if(form_error('c_city')){
                                echo "<span style='color:red'>".form_error('c_city')."</span>";
                            }
                            ?>
                            <div id="error_c_city"></div>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label" for="form-stacked-select">State :</label>
                            <div class="uk-form-controls">
                                <select class="uk-select" name="c_state"  id="c_state"   tabindex="10">
                                    <option value="">Select State</option>
                                    <?php
                                    $stateArr = array();
                                    if (isset($state_list) && !empty($state_list)){
                                        foreach ($state_list as $dataRes) {
                                            echo '<option value="'.url_encryption(trim($dataRes->cmis_state_id)).'">'.strtoupper($dataRes->agency_state).'</option>';
                                            $tempArr = array();
                                            $tempArr['id'] = url_encryption(trim($dataRes->cmis_state_id));
                                            $tempArr['state_name'] = strtoupper($dataRes->agency_state);
                                            $stateArr[] = (object)$tempArr;
                                        }
                                    }
                                    ?>
                                </select>
                                <?php  if(form_error('c_state')){
                                    echo "<span style='color:red'>".form_error('c_state')."</span>";
                                }
                                ?>
                                <div id="error_c_state"></div>
                            </div>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label" for="form-stacked-select">District :</label>
                            <div class="uk-form-controls">
                                <select class="uk-select" name="c_district" id="c_district"  tabindex="11">
                                    <option value="">Select District</option>
                                </select>
                                <?php  if(form_error('c_district')){
                                    echo "<span style='color:red'>".form_error('c_district')."</span>";
                                }
                                ?>
                                <div id="error_c_district"></div>
                            </div>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label" for="form-stacked-text">Registration Bar Id Card <span style="color: red">*</span>Size: (<?php echo (( BAR_ID_CARD_SIZE/1024)/1024);?>MB) Type:(pdf/jpeg/jpg):</label>
                            <div class="uk-form-controls">
                                <input class="uk-input" type="file" accept=".pdf,.jpeg,.jpg" name="bar_id_card" id="bar_id_card"   tabindex="14" />
                            </div>
                            <?php  if(form_error('bar_id_card')){
                                echo "<span style='color:red'>".form_error('bar_id_card')."</span>";
                            }
                            if(!empty($this->session->flashdata('bar_id_card_error'))){
                                echo "<span style='color:red'>".$this->session->flashdata('bar_id_card_error')."</span>";
                            }
                            ?>
                            <div id="error_bar_id_card"></div>
                        </div>
                    </div>
                    <!-- left box end -->
                    <!-- right box start -->
                    <div class="uk-visible@m uk-first-column">
                        <div class="uk-margin">
                            <label class="uk-form-label" for="form-stacked-text">Email <span style="color: red">*</span> :</label>
                            <div class="uk-form-controls">
                                <input class="uk-input" type="email" name="email" id="email" value="<?php echo $emailid;?>" maxlength="100" placeholder="admin@gmail.com "  tabindex="2" readonly/>
                            </div>
                            <?php  if(form_error('email')){
                                echo "<span style='color:red'>".form_error('email')."</span>";
                            }
                            ?>
                            <div id="error_email"></div>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label" for="form-stacked-text">Bar Registration No. :</label>
                            <div class="uk-form-controls">
                                <?php
                                $readony = "";
                                if(isset($bar_reg_no) && !empty($bar_reg_no)){
                                    $readony = "readonly";
                                }
                                ?>
                                <input class="uk-input" type="text" name="bar_reg_no"  value="<?php echo ($bar_reg_no) ?  $bar_reg_no : set_value('bar_reg_no');?>" id="bar_reg_no" maxlength="30" placeholder="abccd2314441"   tabindex="4" <?php echo $readony;?>/>
                            </div>
                            <?php  if(form_error('bar_reg_no')){
                                echo "<span style='color:red'>".form_error('bar_reg_no')."</span>";
                            }
                            ?>
                            <div id="error_bar_reg_no"></div>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label" for="form-stacked-text">Relative Name <span style="color: red">*</span>:</label>
                            <div class="uk-form-controls">
                                <input class="uk-input" type="text" value="<?php echo set_value('relation_name');?>"  name="relation_name" id="relation_name"  placeholder="Relative Name" maxlength="35" tabindex="6" />
                            </div>
                            <?php  if(form_error('relation_name')){
                                echo "<span style='color:red'>".form_error('relation_name')."</span>";
                            }
                            ?>
                            <div id="error_relation_name"></div>
                        </div>
                        <div class="uk-margin">
                            <label style="margin-top: 35px;margin-bottom: 9px;" class="uk-form-label"><input class="uk-checkbox" type="checkbox"  name="same_as" id="same_as"  tabindex="12" /> Same As Chamber Address :</label>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label" for="form-stacked-text">Residential Address :</label>
                            <div class="uk-form-controls">
                                <textarea class="uk-textarea" name="r_address" id="r_address"  placeholder="Address" maxlength="250" tabindex="13"><?php echo set_value('r_address');?> </textarea>
                            </div>
                            <?php  if(form_error('r_address')){
                                echo "<span style='color:red'>".form_error('r_address')."</span>";
                            }
                            ?>
                            <div id="error_r_address"></div>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label" for="form-stacked-text">Pincode :</label>
                            <div class="uk-form-controls">
                                <input class="uk-input" value="<?php echo set_value('r_pincode');?>" name="r_pincode" id="r_pincode"  placeholder="Pincode" maxlength="6"  tabindex="14" />
                            </div>
                            <?php  if(form_error('r_pincode')){
                                echo "<span style='color:red'>".form_error('r_pincode')."</span>";
                            }
                            ?>
                            <div id="error_r_pincode"></div>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label" for="form-stacked-text">City :</label>
                            <div class="uk-form-controls">
                                <input class="uk-input" value="<?php echo set_value('r_city');?>" name="r_city" id="r_city"  placeholder="City" maxlength="25" tabindex="15" />
                            </div>
                            <?php  if(form_error('r_city')){
                                echo "<span style='color:red'>".form_error('r_city')."</span>";
                            }
                            ?>
                            <div id="error_r_city"></div>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label" for="form-stacked-select">State :</label>
                            <div class="uk-form-controls">
                                <select class="uk-select" name="r_state"  id="r_state" tabindex="16">
                                    <option value="">Select State</option>
                                    <?php
                                    if (isset($state_list) && !empty($state_list)){
                                        foreach ($state_list as $dataRes) {
                                            echo '<option value="'.url_encryption(trim($dataRes->cmis_state_id)).'">'.strtoupper($dataRes->agency_state).'</option>';
                                        }
                                    }
                                    ?>
                                </select>
                                <?php  if(form_error('r_state')){
                                    echo "<span style='color:red'>".form_error('r_state')."</span>";
                                }
                                ?>
                                <div id="error_r_state"></div>
                            </div>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label" for="form-stacked-select">District :</label>
                            <div class="uk-form-controls">
                                <select class="uk-select" name="r_district" id="r_district"  tabindex="17">
                                    <option>Select District</option>
                                </select>
                                <?php  if(form_error('r_district')){
                                    echo "<span style='color:red'>".form_error('r_district')."</span>";
                                }
                                ?>
                                <div id="error_r_district"></div>
                            </div>
                        </div>

                    </div>
                    <!-- right box end -->
                    <div class="uk-margin" style="margin-left: auto;margin-right: auto;width: 130px;margin-bottom: 162px;">
                        <input type="submit" class="uk-button uk-button-primary" name="register" id="register" value="Register">
                    </div>
                    <?php echo form_close(); ?>
                </div>
            <?php }
            else if((isset($arguingCounselDetails) && !empty($arguingCounselDetails)) &&  (!isset($arguingCounselDetails['registration_code']) && empty($arguingCounselDetails['registration_code']))){
                ?>
                <div class="table-wrapper-scroll-y my-custom-scrollbar ">
                    <div class="x_content">
                    </div>
                </div>
            <?php }
            else if(!empty($this->session->userdata('login')['ref_m_usertype_id'])){
                $action="registerCounsel";
                $attribute = array('class' => 'form-horizontal form-label-left',  'id' => 'add_arguing_counsel', 'name' => 'add_arguing_counsel', 'autocomplete' => 'off');
                echo form_open($action, $attribute);
                ?>
                <div class="uk-child-width-1-1 uk-child-width-1-2@m uk-grid-small uk-grid">
                    <!-- left box start -->
                    <div class="uk-visible@m uk-first-column">
                        <div class="uk-margin">
                            <label class="uk-form-label" for="form-stacked-text">Name <span style="color: red">*</span> :</label>
                            <div class="uk-form-controls">
                                <input class="uk-input" type="text" name="name" id="name" placeholder="Name"  value="<?php echo set_value('name'); ?>" maxlength="100" minlength="3" tabindex="1">
                            </div>
                            <?php  if(form_error('name')){
                                echo "<span style='color:red'>".form_error('name')."</span>";
                            }
                            ?>
                            <div id="error_name"></div>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label" for="form-stacked-text">Mobile <span style="color: red">*</span> :</label>
                            <div class="uk-form-controls">
                                <input class="uk-input" type="text" name="mobile" id="mobile" value="<?php echo $mobile_no;?>" placeholder="9876543XXX" maxlength="10"   tabindex="3">
                            </div>
                            <?php  if(form_error('mobile')){
                                echo "<span style='color:red'>".form_error('mobile')."</span>";
                            }
                            if(!empty($this->session->flashdata('mobileExistMessage'))){
                                echo "<span style='color:red'>".$this->session->flashdata('mobileExistMessage')."</span>";
                            }
                            ?>
                            <div id="error_mobile"></div>
                        </div>
                    </div>
                    <!-- left box end -->
                    <!-- right box start -->
                    <div class="uk-visible@m uk-first-column">
                        <div class="uk-margin">
                            <label class="uk-form-label" for="form-stacked-text">Email <span style="color: red">*</span> :</label>
                            <div class="uk-form-controls">
                                <input class="uk-input" type="email" name="email" id="email" value="<?php echo $email_id;?>" maxlength="100" placeholder="admin@gmail.com "  tabindex="2"/>
                            </div>
                            <?php  if(form_error('email')){
                                echo "<span style='color:red'>".form_error('email')."</span>";
                            }
                            ?>
                            <div id="error_email"></div>
                        </div>
                        <div class="uk-margin">
                            <label class="uk-form-label" for="form-stacked-text">Bar Registration No. :</label>
                            <div class="uk-form-controls">
                                <?php
                                $readony = "";
                                if(isset($bar_reg_no) && !empty($bar_reg_no)){
                                    $readony = "readonly";
                                }
                                ?>
                                <input class="uk-input" type="text" name="bar_reg_no"  value="<?php echo ($bar_reg_no) ?  $bar_reg_no : set_value('bar_reg_no');?>" id="bar_reg_no" maxlength="30" placeholder="abccd2314441"   tabindex="4" <?php echo $readony;?>/>
                            </div>
                            <?php  if(form_error('bar_reg_no')){
                                echo "<span style='color:red'>".form_error('bar_reg_no')."</span>";
                            }
                            ?>
                            <div id="error_bar_reg_no"></div>
                        </div>
                        <div class="uk-margin">
                            <input type="submit" class="uk-button uk-button-primary" name="add_arguing_counsel_submit" id="add_arguing_counsel_submit" value="Register">
                        </div>
                    </div>
                    <!-- right box end -->

                    <?php echo form_close(); ?>
                </div>
            <?php  }
            else if(!empty($this->session->userdata('self_register_arguing_counsel')) &&  (!empty($this->session->userdata('self_arguing_counsel')))){
                ?>
                <div class="table-wrapper-scroll-y my-custom-scrollbar ">
                    <div class="x_content">
                    </div>
                </div>
            <?php }

            ?>

            <div class="x_content">
                <h2 class="textwhite uk-margin-remove uk-visible@m ukwidth-expand">Advocate Approval/Rejected</h2>
                <button class="uk-button-medium uk-button-primary actionType" id="rejected" style="float: right;">Rejected</button>
                <button class="uk-button-medium uk-button-primary actionType" id="approved" style="float: right;margin-right: 8px;margin-bottom: 12px;">Approved</button>
                <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                    <thead>
                    <tr class="success input-sm" role="row" >
                        <th>S.No.</th>
                        <th>Name</th>
                        <th>Mobile No.</th>
                        <th>Email</th>
                        <th>Bar Reg.No.</th>
                        <th><label class="control-label" for="selectAll"><input type="checkbox" name="selectAll" id="selectAll">
                                <span style="margin-left: 7px; font-size: 12px;" id="selectSpan">Select All</span></label></th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

        </div>




        <!-- /.content -->
    </div>
</div>






