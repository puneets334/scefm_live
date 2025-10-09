@extends('layout.advocateApp')
@section('title', 'Caveat Request in a Case')
@section('heading', 'Caveat Request in a Case')
@section('content')

<?php

$var = (empty(@$registration_id) ? base_url((@$tab == 'case/caveat/crud/' ? @$tab : 'caveat/'.@$tab)) : base_url('caveat/defaultController/processing/'.($registration_id)));
// zecho $var;die;
?>

<div class="mainPanel ">
    <div class="panelInner">
        <div class="middleContent">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">                     
                        <iframe name="content-iframe" class="col-12 iframe-scroll-bar" style="height: 100vh;" src="{{ $var }}"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
