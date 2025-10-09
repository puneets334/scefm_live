@extends('responsive_variant.layouts.master.uikit_scutum_2.index')

@section('title', 'Efiling Services')
@section('heading', 'E-Filing Services')
@section('pinned-main-offcanvas') @endsection
@section('content-container-ribbon')@endsection

@section('content')

<div class="uk-margin-small-top uk-border-rounded">


    <div class="uk-width-1-2">
        <div class="uk-card uk-margin">
            <div class="uk-card-header">
                <h2 class="uk-card-title">File A</h2>
            </div>
            <div class="uk-card-body">
                <div class="uk-grid-medium uk-width-auto uk-grid" data-uk-grid="">
                    <!--<div class="uk-first-column"><a class="sc-button sc-button-default sc-button-outline sc-js-button-wave waves-effect waves-button" href="#">Default</a></div>-->
                    <div><a class="sc-button sc-button-outline sc-button-outline-primary sc-js-button-wave-primary waves-effect waves-button waves-success" href="{{base_url('case/crud')}}">New case</a></div>
                    <div><a class="sc-button sc-button-outline sc-button-outline-primary sc-js-button-wave-warning waves-effect waves-button waves-warning" href="{{base_url('case/interim_application/crud')}}">Interlocatory Application (IA)</a></div>
                    <div><a class="sc-button sc-button-outline sc-button-outline-primary sc-js-button-wave-success waves-effect waves-button waves-success" href="{{base_url('case/document/crud')}}">Misc. Documents</a></div>
                    <div><a class="sc-button sc-button-outline sc-button-outline-primary sc-js-button-wave-danger waves-effect waves-button waves-danger" href="{{base_url('case/caveat/crud')}}">Caveat</a></div>
                    <!--<div class="uk-grid-margin uk-first-column"><a class="sc-button sc-button-default sc-button-outline sc-button-disabled" href="#">Citation</a></div>-->
                    <div><a class="sc-button sc-button-outline sc-button-outline-primary sc-js-button-wave-danger waves-effect waves-button waves-danger" href="{{base_url('deficitCourtFee')}}">Pay Deficit Court Fee</a></div>
                    <!--<div><a class="sc-button sc-button-outline sc-button-outline-primary sc-js-button-wave-danger waves-effect waves-button waves-danger" href="{{base_url('case/ancillary/form')}}"> Mandatory Proformas (Fetch, Generate and Save)</a></div>-->
                    <!--<div><a class="sc-button sc-button-outline sc-button-outline-primary sc-js-button-wave-danger waves-effect waves-button waves-danger" href="{{base_url('case/iamiscdocshare')}}"> Share & Serve</a></div>-->

                </div>
            </div>
        </div>
    </div>

   <!-- <div class="uk-width-1-2">
        <div class="uk-card uk-margin">
                <div class="uk-card-header">
                    <h2 class="uk-card-title">Request For</h2>
                </div>
            <div class="uk-card-body">
                <div class="uk-grid-small uk-width uk-grid" data-uk-grid="">
                    <div><a class="sc-button sc-button-outline sc-button-outline-primary sc-js-button-wave-primary waves-effect waves-button waves-primary" href="{{base_url('case/mentioning/crud')}}">Mentioning</a></div>
                    <div><a class="sc-button sc-button-outline sc-button-outline-primary sc-js-button-wave-warning waves-effect waves-button waves-warning" href="{{base_url('case/citation/crud')}}">Citation</a></div>
                    <div><a class="sc-button sc-button-outline sc-button-outline-primary sc-js-button-wave-success waves-effect waves-button waves-success" href="{{base_url('case/certificate/crud')}}">Certificate</a></div>
                    <div><a class="sc-button sc-button-outline sc-button-outline-primary sc-js-button-wave-danger waves-effect waves-button waves-danger" href="{{base_url('adjournment_letter')}}">Adjourment</a></div>
                    <div><a class="sc-button sc-button-outline sc-button-outline-primary sc-js-button-wave-danger waves-effect waves-button waves-danger" href="{{base_url('case/ancillary/Indexdocuments')}}">Prefilled Index </a></div>
                </div>
            </div>
        </div>
    </div>-->
    @if($this->session->userdata['login']['ref_m_usertype_id']==USER_ADVOCATE)
    <div class="uk-width-1-2">
        <div class="uk-card uk-margin">
            <div class="uk-card-header">
                <h2 class="uk-card-title">Add/Approve</h2>
            </div>
            <div class="uk-card-body">
                <div class="uk-grid-small uk-width uk-grid" data-uk-grid="">
                    <div><a class="sc-button sc-button-outline sc-button-outline-primary sc-js-button-wave-primary waves-effect waves-button waves-primary" href="{{base_url('register/arguingCounsel')}}">Add/ Approve Advocate(s)</a></div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>



@endsection