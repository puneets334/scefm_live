@extends('responsive_variant.layouts.master.uikit_scutum_2.index')
@section('title', 'Add Clerk')
@section('heading', '')
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
                                    <iframe name="content-iframe" class="col-12 iframe-scroll-bar"  src="{{base_url('add/clerk')}}"></iframe>
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