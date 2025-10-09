@extends('responsive_variant.layouts.master.uikit_scutum_2.index')

@section('title', 'Mention a Case')
@section('heading', 'Mention a case')
@section('pinned-main-offcanvas') @endsection
@section('content-container-ribbon')@endsection

@section('content')


<div class="uk-margin-small-top uk-border-rounded">
    <iframe name="case-mentioning-crud-iframe" class="uk-width internal-content-iframe" name="body-frame"  src="{{base_url('mentioning/listing')}}"></iframe>
</div>


@endsection
