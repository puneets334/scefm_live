<?php declare(strict_types=1); ?>
@extends('layout.advocateApp')
@section('title', 'Refile old e-filing case')
@section('heading', 'Refile old e-filing case')
@section('pinned-main-offcanvas') @endsection
@section('content-container-ribbon')@endsection
@section('content')
<div class="mainPanel ">
    <div class="panelInner">
        <div class="middleContent">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-12 sm-12 col-md-12 col-lg-12 middleContent-left">
                                <div class="center-content-inner comn-innercontent">
                                    <iframe name="case-document-crud-iframe" class="col-12 iframe-scroll-bar"  src="{{(empty(@$registration_id) ? base_url((@$tab == 'affirmation' ? @$tab : (@$tab == 'courtFee'?'oldCaseRefiling/'.@$tab:'newcase/'.@$tab) )) : base_url('oldCaseRefiling/defaultController/'.($registration_id)))}}"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script type="text/javascript">
    $(function(){
        $('#case-document-crud-form').submit();
    });
</script>
@endsection