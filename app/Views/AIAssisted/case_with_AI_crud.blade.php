@extends('responsive_variant.layouts.master.uikit_scutum_2.index')

@section('title', ($_SESSION['efiling_details']['stage_id']==10 or $_SESSION['efiling_details']['stage_id']==11)?'Refile case':'Register case')
@section('heading', ($_SESSION['efiling_details']['stage_id']==10 or $_SESSION['efiling_details']['stage_id']==11)?'Refiling':'Register a case')
@section('pinned-main-offcanvas') @endsection
@section('content-container-ribbon')@endsection
@section('content')

<div class="uk-margin-small-top uk-border-rounded">
    <iframe name="content-iframe" class="uk-width internal-content-iframe"  src="{{(empty(@$registration_id) ? base_url((@$tab == 'affirmation' ? @$tab : 'AIAssisted/CaseWithAI/'.@$tab)) : base_url('AIAssisted/defaultController/'.($registration_id)))}}"></iframe>
</div>

@endsection
