@extends('responsive_variant.layouts.master.uikit_scutum_2.index')

@section('title', 'Citation in a Case')
@section('heading', 'Citation in a case')
@section('pinned-main-offcanvas') @endsection
@section('content-container-ribbon')@endsection

@section('content')

<div class="uk-margin-small-top uk-border-rounded">
    <iframe name="case-citation-crud-iframe" class="uk-width internal-content-iframe" name="body-frame"  src="{{base_url('citation/citationController')}}"></iframe>
</div>

@endsection
