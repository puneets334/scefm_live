@extends('responsive_variant.layouts.master.uikit_scutum_2.index')

@section('title', 'Share Document(s)')
@section('heading', 'Share Document(s)')
@section('pinned-main-offcanvas') @endsection
@section('content-container-ribbon')@endsection

@section('content')

<!--@if(!empty(@$direct_access_params))
<form id="case-ia-crud-form" target="case-ia-crud-iframe" method="POST" action="{{base_url('case/search/search_case_details')}}">
    @foreach($direct_access_params as $key=>$value)
        <input type="hidden" name="{{$key}}" value="{{$value}}">
    @endforeach
</form>
@endif-->
<div class="uk-margin-small-top uk-border-rounded">
    <iframe name="case-ia_misc-doc_share-iframe" class="uk-width internal-content-iframe"  src="{{base_url('documentSharingWithProof')}}"></iframe>
</div>
@endsection
