@extends('responsive_variant.layouts.master.uikit_scutum_2.index')

@section('title', 'My Profile')
@section('heading', 'My Profile')
@section('pinned-main-offcanvas') @endsection
@section('content-container-ribbon')@endsection

@section('content')

<div class="uk-margin-small-top uk-border-rounded">
    <iframe name="content-iframe" class="uk-width internal-content-iframe"  src="{{base_url('profile')}}"></iframe>
</div>

@endsection
