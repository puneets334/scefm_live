@extends('responsive_variant.layouts.master.uikit_scutum_2.index')

@section('title', @$tab)
@section('heading', @$tab)
@section('pinned-main-offcanvas') @endsection
@section('content-container-ribbon')@endsection

@section('content')

<div class="uk-margin-small-top uk-border-rounded">
    <iframe name="content-iframe" scrolling="no" class="uk-width internal-content-iframe" style="overflow:hidden !important;height: 100vh" src="{{base_url('supplements/DefaultController/'.@$method)}}"></iframe>
</div>


@endsection
