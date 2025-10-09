@extends('responsive_variant.layouts.master.uikit_scutum_2.index')

@section('title', 'Certificate Request in a Case')
@section('heading', 'Certificate Request in a case')
@section('pinned-main-offcanvas') @endsection
@section('content-container-ribbon')@endsection

@section('content')

<div class="uk-margin-small-top uk-border-rounded">
    <iframe name="case-certificate-crud-iframe" class="uk-width internal-content-iframe" name="body-frame"  src="{{base_url('certificate/AddDetails')}}"></iframe>
</div>

@endsection
