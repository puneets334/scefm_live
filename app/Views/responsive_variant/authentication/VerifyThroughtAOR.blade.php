
@extends('layout.frontApp')
@section('content')
<!-- Login Area Start  -->
<div class="login-area">
    <div class="container">
        <div class="row" id="default">
        <div class="col-12 col-sm-12 col-md-6 col-lg-7 login-banner">
                <div class="login-banner-inner">
                    <div class="banimg-sec">
                       
                    </div>
                    <div class="banner-txts">
                        
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 login-section">
                <div class="login-s-inner">
                    <?php $session = session(); ?>
                    @if(isset($status) && $status=='Success')
                    <div class="text-success" style="border: 2px solid green; background-color: #e6ffe6; padding: 10px; border-radius: 5px;">
                        <b>Verification completed Successfully</b>
                    </div>
                    @endif
                    @if(isset($status) && $status=='Failed')
                    <div class="text-danger">
                        <b>Verfication Failed</b>
                    </div>
                    @endif  
                    @if(isset($status) && $status=='expired')
                    <div class="text-danger" style="border: 2px solid red; background-color:rgb(248, 165, 181); padding: 10px; border-radius: 5px;">
                        <b>Link Expired</b>
                    </div>
                    @endif
                    @if(isset($status) && $status=='approved')
                    <div class="text-danger" style="border: 2px solid red; background-color:rgb(248, 165, 181); padding: 10px; border-radius: 5px;">
                        <b>Already Verified</b>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
    </div>
</div>

<!-- Login Area End  -->
@endsection