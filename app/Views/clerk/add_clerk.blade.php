@extends('layout.advocateApp')
@section('content')
<style>
    .row{
        padding-top: 10px;
    }
    form label {
        font-size: 17px;
    }
    .uk-button-success {
        background-color: #82bb42;
        color: #fff;
        background-image: -webkit-linear-gradient(top,#9fd256,#6fac34);
        background-image: linear-gradient(to bottom,#9fd256,#6fac34);
        border-color: rgba(0,0,0,.2);
            border-bottom-color: rgba(0, 0, 0, 0.2);
        border-bottom-color: rgba(0,0,0,.4);
        text-shadow: 0 -1px 0 rgba(0,0,0,.2);
    }
</style>
<div class="container-fluid">
    <div class="row card">
        <div class="col-lg-12">
            <div class="card-body">
            <div class="dashboard-section">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="dash-card">
                            {{-- Page Title Start --}}
                            <div class="title-sec">
                                <h5 class="unerline-title">Add Clerk(s)</h5>
                                @if(!empty(getSessionData('login')))
                                    <a href="javascript:void(0)" onclick="window.history.back()" class="quick-btn pull-right"><span class="mdi mdi-chevron-double-left"></span>Back</a>
                                @endif
                            </div>
                            @if(!empty(getSessionData('success')))
                                <div class="alert alert-dismissible text-center flashmessage">
                                    <b style="color: green;">{{ getSessionData('success') }}</b>
                                </div>
                            @endif
                            @if(!empty(getSessionData('error')))
                                <div class="alert alert-dismissible text-center flashmessage">
                                    <b style="color: red;">{{ getSessionData('error') }}</b>
                                </div>
                            @endif
                            @if(!empty(getSessionData('input')))
                                <div class="alert alert-dismissible text-center flashmessage">
                                    <b style="color: red;">{{ getSessionData('input') }}</b>
                                </div>
                            @endif                                
                            {{-- Page Title End --}}
                            {{-- Main Start --}}
                            <div class="uk-margin-small-top uk-border-rounded">
                                <h3 class="uk-label-warning  uk-text-lighter" style="padding:5px;background-color:#EEEEEE;color:#000000;"><?php echo $add_limit_str; ?></h3>
                                <?php
                                $attribute = array('class' => 'form-horizontal form-label-left', 'id' => 'add_clerk_form', 'name' => 'add_clerk_form', 'autocomplete' => 'off');
                                $action="clerks/Clerk_Controller/add_clerk";
                                echo form_open($action, $attribute);
                                    if($can_add_clerk) {
                                        ?>
                                        <div class="row">
                                            <div class="col-sm-12 col-md-6">
                                                <label class="form-label" for="form-stacked-text">First Name <span style="color: red">*</span> :</label>
                                                <input class="form-control cus-form-ctrl" type="text" name="first_name" id="first_name" placeholder="First Name"  value="<?php echo set_value('first_name'); ?>" maxlength="100" minlength="3" tabindex="1">
                                                <div id="error_first_name"></div>
                                                <?php if (isset($validation) && $validation->hasError('first_name')): ?>
                                                    <div class="text-danger">
                                                        <?= $validation->getError('first_name'); ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="col-sm-12 col-md-6">
                                                <label class="form-label" for="form-stacked-text">Last Name : </label>
                                                <input class="form-control cus-form-ctrl" type="text" name="last_name" id="last_name" placeholder="Last Name" maxlength="100" tabindex="2"/>
                                                <div id="error_last_name"></div>
                                                <?php if (isset($validation) && $validation->hasError('last_name')): ?>
                                                    <div class="text-danger">
                                                        <?= $validation->getError('last_name'); ?>
                                                    </div>
                                                <?php endif; ?>   
                                            </div>
                                            <div class="col-sm-12 col-md-6">
                                                <label class="form-label" for="form-stacked-text">Email <span style="color: red">*</span> :</label>
                                                <input class="form-control cus-form-ctrl" type="email" name="email_id" id="email_id" placeholder="admin@gmail.com " value="<?php echo set_value('email'); ?>" maxlength="100" minlength="2" tabindex="3">
                                                <div id="error_email"></div>
                                                <?php if (isset($validation) && $validation->hasError('email')): ?>
                                                    <div class="text-danger">
                                                        <?= $validation->getError('email'); ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="col-sm-12 col-md-6">
                                                <label class="form-label" for="form-stacked-text">Mobile <span style="color: red">*</span> :</label>
                                                <input class="form-control cus-form-ctrl" type="text" name="mobile_no" id="mobile_no" placeholder="9876543XXX" maxlength="10" minlength="10" tabindex="4" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" />
                                                <div id="error_mobile_no"></div>
                                                <?php if (isset($validation) && $validation->hasError('mobile_no')): ?>
                                                    <div class="text-danger">
                                                        <?= $validation->getError('mobile_no'); ?>
                                                    </div>
                                                <?php endif; ?>   
                                            </div>                                        
                                            <div class="col-sm-12 col-md-12">
                                                <label class="form-label" for="form-stacked-text">Gender <span style="color: red">*</span> :</label>
                                                <label class="radio-inline"><input type="radio" id="gender" name="gender" value="M" required>Male</label>
                                                <label class="radio-inline"><input type="radio" id="gender" name="gender" value="F">Female</label>
                                                <label class="radio-inline"><input type="radio" id="gender" name="gender" value="O">Other</label>
                                                <div id="error_gender"></div>
                                                <?php if (isset($validation) && $validation->hasError('gender')): ?>
                                                    <div class="text-danger">
                                                        <?= $validation->getError('gender'); ?>
                                                    </div>
                                                <?php endif; ?>                                            
                                            </div>
                                        </div>
                                        <div style="text-align: center;">
                                            <!-- <input type="submit" class="btn btn-primary quick-btn mt-3" name="add_arguing_counsel_submit" id="add_arguing_counsel_submit" value="Register"> -->
                                            <Button type="submit" class="quick-btn mt-3" name="add_arguing_counsel_submit" id="add_arguing_counsel_submit">Register</Button>
                                            <button type="button" class=" gray-btn quick-btn mt-3" name="back" id="back" value="back" onclick="history.back()" >Back</button>
                                        </div>
                                        <?php
                                    }
                                echo form_close();
                                ?>
                                @if(count($clerks))
                                    <hr>
                                    <div class="x_content">
                                        <h2 class="textwhite uk-margin-remove uk-visible@m ukwidth-expand">Engage/Disengage Clerk(s)</h2>
                                        <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap uk-table" width="100%">
                                            <thead>
                                                <tr class="success input-sm" role="row" >
                                                    <th>#S.No.</th>
                                                    <th>Name</th>
                                                    <th>Mobile No.</th>
                                                    <th>Email</th>
                                                    <th>Gender</th>
                                                    <th>Created On</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($clerks as $key => $clerk)
                                                    <tr>
                                                        <td width="4%">{{$key+1}}</td>
                                                        <td width="10%">{{$clerk->first_name}}</td>
                                                        <td width="20%">{{$clerk->moblie_number}}</td>
                                                        <td width="10%">{{$clerk->emailid}}</td>
                                                        <td width="10%">
                                                            @if($clerk->gender=='M')
                                                                Male
                                                            @elseif($clerk->gender=='F')
                                                                Female
                                                            @elseif($clerk->gender=='O')
                                                                Other
                                                            @endif
                                                        </td>
                                                        <td>
                                                            {{date("d-m-Y h.i.s A", strtotime($clerk->created_on))}}
                                                        </td>
                                                        <td width="10%">
                                                            @php
                                                            $action="add/clerk/update_clerk";
                                                                $attribute = [
                                                                'class'        => 'form-horizontal form-label-left',  
                                                                'id'           => 'engaged_clerk_form', 
                                                                'name'         => 'engaged_clerk_form', 
                                                                'autocomplete' => 'off'
                                                                ];
                                                            @endphp
                                                            <?php echo form_open($action, $attribute); ?>
                                                                <input type="hidden" name="ref_user_id" value="{{$clerk->id}}">
                                                                @if(is_null($clerk->to_date))                                
                                                                    <input type="hidden" name="is_engaged" value="1">
                                                                    <button class="btn btn-success" type="submit">Disengage</button>
                                                                @else 
                                                                <input type="hidden" name="is_engaged" value="0">
                                                                <button class="btn btn-success" type="submit">Engage</button>
                                                                @endif
                                                                <?php echo form_close(); ?>
                                                        </td>
                                                    </tr>                    
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- </div> -->
@endsection