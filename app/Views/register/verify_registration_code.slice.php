@extends('responsive_variant.layouts.master.uikit_scutum_2.index')

@section('title', 'Add/Approve Advocate(s)')
@section('heading', 'Add/Approve Advocate(s)')
@section('pinned-main-offcanvas') @endsection
@section('content-container-ribbon')@endsection

@section('content')

<div class="uk-margin-small-top uk-border-rounded">
                <div>
                    <?php
                    if(!empty($this->session->flashdata('success'))){
                        echo "<span style='color:green;margin-left: 341px;'>".$this->session->flashdata('success')."</span>";
                    }
                    if(!empty($this->session->flashdata('error'))){
                        echo "<span style='color:red;margin-left: 341px;'>".$this->session->flashdata('error')."</span>";
                    }
                    $action="matchRegistrationCode";
                    $attribute = array('class' => 'form-horizontal form-label-left',  'id' => 'matchRegistrationCode', 'name' => 'matchRegistrationCode', 'autocomplete' => 'off');
                    echo form_open($action, $attribute);
                    ?>
                </div>
    <div class="uk-child-width-1-1 uk-child-width-1-2@m uk-grid-small uk-grid" uk-grid="" uk-height-viewport="offset-top:true" style="min-height: calc(100vh - 60.4062px);">
        <!-- left box start -->
        <div class="uk-visible@m uk-first-column">
            <div class="uk-margin">
                <label class="uk-form-label" for="form-stacked-text">Registration Code <span style="color: red">*</span> :</label>
                <div class="uk-form-controls">
                    <input class="uk-input" type="text" name="registration_code" id="registration_code" placeholder="Registration Code"  maxlength="10"  tabindex="1" />
                </div>
                <?php  if(form_error('registration_code')){
                    echo "<span style='color:red'>".form_error('registration_code')."</span>";
                }
                ?>
                <div id="error_registration_code"></div>
            </div>
        </div>
        <!-- left box end -->
        <!-- right box start -->
        <div class="uk-visible@m uk-first-column">
            <div class="uk-margin">
                <input type="submit" style="margin-top: 28px;height: 38px;" name="verifyRegistrationCode" id="verifyRegistrationCode" value="Verify Registration Code" class="uk-button-medium uk-button-primary">
            </div>
        </div>
        <!-- right box end -->

        <?php echo form_close(); ?>
    </div>








<!--                <div class="x_panel">-->
<!--                    <div class="table-wrapper-scroll-y my-custom-scrollbar ">-->
<!--                        <div class="x_content">-->
<!--                            <div class="row">-->
<!--                                <div class="col-sm-6 col-xs-6">-->
<!--                                            <div class="form-group">-->
<!--                                                <label class="control-label col-sm-5 input-sm">Registration Code <span style="color: red">*</span> :</label>-->
<!--                                                <div class="col-sm-7">-->
<!--                                                    <div class="form-group">-->
<!--                                                        <input type="text" name="registration_code" id="registration_code" placeholder="Registration Code"  maxlength="10"  class="form-control input-sm"  tabindex="1" />-->
<!--                                                        --><?php // if(form_error('registration_code')){
//                                                            echo "<span style='color:red'>".form_error('registration_code')."</span>";
//                                                        }
//                                                        ?>
<!--                                                        <div id="error_registration_code"></div>-->
<!--                                                    </div>-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                </div>-->
<!--                                <div class="col-sm-6 col-xs-6">-->
<!--                                    <div class="form-group">-->
<!--                                        <label class="control-label col-sm-5 input-sm"></label>-->
<!--                                        <div class="col-sm-7">-->
<!--                                            <input type="submit" name="verifyRegistrationCode" id="verifyRegistrationCode" value="Verify Registration Code" class="uk-button-medium uk-button-primary">-->
<!--                                        </div>-->
<!---->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                        --><?php //echo form_close(); ?>
<!--                    </div>-->
<!--                </div>-->
</div>
<script>
    $(document).ready(function(){
        $(document).on('click','#verifyRegistrationCode',function(e){
            e.preventDefault();
            var barRegPattern = /^[a-zA-Z0-9]+$/i;
            var validation = true;
            var registration_code =  $.trim($("#registration_code").val());
            if(registration_code == ''){
                $("#registration_code").focus();
                $("#error_registration_code").text("Please fill registration code.");
                $("#error_registration_code").css({'color':'red'});
                alert("Please fill registration code.");
                validation = false;
                return false;
            }
            else if(!barRegPattern.test(registration_code)){
                $("#registration_code").focus();
                $("#error_registration_code").text("Please fill valid registration code.");
                $("#error_registration_code").css({'color':'red'});
                alert("Please fill valid registration code.");
                validation = false;
                return false;
            }
            else if(registration_code.length < 10){
                $("#registration_code").focus();
                $("#error_registration_code").text("Please fill registration code of 10 characters.");
                $("#error_registration_code").css({'color':'red'});
                alert("Please fill registration code of 10 characters.");
                validation = false;
                return false;
            }
            else if(validation){
                $("#matchRegistrationCode").submit();
            }
        });
    });
</script>

@endsection