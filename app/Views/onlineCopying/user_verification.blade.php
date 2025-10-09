<?php
if(isset(session()->get('login')['impersonator_user']) && isset(session()->get('login')['impersonator_user']->is_active) && !empty(session()->get('login')['impersonator_user']) && session()->get('login')['impersonator_user']->is_active == 1){
    $extends = 'layout.advocateApp';
}else{
    $extends = 'layout.ecopyApp';
} ?>
@extends($extends)
@section('content')
        <div class="card">
            
            <div class="card-body">

            <div class="ryt-dash-breadcrumb">
                                <div class="btns-sec">
                                    <!-- <a href="javascript:void(0)" class="quick-btn pull-right mb-2" onclick="window.history.back()"><span class="mdi mdi-chevron-double-left"></span>Back</a> -->
                                    <a href="javascript:void(0)" onclick="window.history.back()" class="quick-btn pull-right"><span class="mdi mdi-chevron-double-left"></span>Back</a>
                                </div>
                            </div>
                <div class="form-row">
                <div class="card-header font-weight-bolder"><h6>This email address is requested to be registered for eCopying Services of Supreme Court of India for (User Name, Applicant Type)</h6>
            </div>
                </div>
            </div>
        </div>
        
@endsection
@push('script')
<script>
$(document).ready(function() {
            $('#loader-wrapper').show();
            var loaderTimeout = setTimeout(function() {
                $('#loader-wrapper').fadeOut('slow', function() {
                    $('#content').fadeIn('slow');
                });
            }, 1000);
            $(window).on('load', function() {
                clearTimeout(loaderTimeout);
                $('#loader-wrapper').fadeOut('slow', function() {
                    $('#content').fadeIn('slow');
                });
            });
        });
</script>
@endpush