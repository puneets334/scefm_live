@extends('layout.app')


<!-- @section('title', 'E-Filing Search')
@section('heading', 'E-Filing Search')
@section('pinned-main-offcanvas') @endsection
@section('content-container-ribbon')@endsection -->

@section('content')

<?php
$var =  base_url('efiling_search/get_view_data'); 
?>
 

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="col-12 sm-12 col-md-12 col-lg-12 middleContent-left">
                    <div class="center-content-inner comn-innercontent">
                        <iframe name="case-mentioning-crud-iframe" class="col-12 iframe-scroll-bar" src="{{$var}}"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
