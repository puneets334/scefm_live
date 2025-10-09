@extends('responsive_variant.layouts.master.uikit_scutum_2.index')

@section('title', 'Search')
@section('heading', 'Search')
@section('pinned-main-offcanvas') @endsection
@section('content-container-ribbon')@endsection

@section('content')

<div class="uk-margin-small-top uk-border-rounded">
    <iframe name="content-iframe" class="uk-width internal-content-iframe"  uk-height-viewport="offset-top:true;" src="{{base_url('dashboard/defaultController/showGlobalSearch')}}"></iframe>
</div>

@endsection