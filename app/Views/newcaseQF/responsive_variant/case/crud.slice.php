@extends('responsive_variant.layouts.master.uikit_scutum_2.index')

@section('title', 'Register Case')
@section('heading', 'Register a case')
@section('pinned-main-offcanvas') @endsection
@section('content-container-ribbon')@endsection

@section('content')

<div class="uk-margin-small-top uk-border-rounded">
    <iframe name="content-iframe" class="uk-width internal-content-iframe"  src="{{(empty(@$registration_id) ? base_url((@$tab == 'affirmation' ? @$tab : 'newcaseQF/'.@$tab)) : base_url('newcaseQF/defaultController/'.($registration_id)))}}"></iframe>
</div>

@endsection
