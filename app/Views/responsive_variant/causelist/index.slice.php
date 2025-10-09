@extends('responsive_variant.layouts.master.uikit_scutum_2.index')

@section('title', 'Causelist')
@section('heading', 'Causelist')
@section('pinned-main-offcanvas') @endsection
@section('content-container-ribbon')@endsection

@section('content')

<div id="causelist-container" class="uk-margin-small-top uk-border-rounded">
    <iframe name="content-iframe" class="uk-width" src="https://registry.sci.gov.in/schedule?ifShowHeading=false&ifSkipDigitizedCasesStageComputation=true" uk-height-viewport="offset-top:true;"></iframe>
</div>

<script type="text/javascript">
    //$('#causelist-container').load('https://registry.sci.gov.in/schedule/');
</script>

@endsection
