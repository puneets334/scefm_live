@extends('responsive_variant.layouts.master.uikit_scutum_2.index')

@section('title', 'E-Filing Vakalatnama')
@section('heading', 'E-Filing Vakalatnama Cases')
@section('pinned-main-offcanvas') @endsection
@section('content-container-ribbon')@endsection

@section('content')


<div class="uk-margin-small-top uk-border-rounded">
    <iframe name="case-mentioning-crud-iframe" class="uk-width internal-content-iframe" name="body-frame"  src="{{base_url('vakalatnama/dashboard/vakalatnama_list')}}"></iframe>
</div>


@endsection
