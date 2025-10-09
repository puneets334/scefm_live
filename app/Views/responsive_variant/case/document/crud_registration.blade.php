@extends('responsive_variant.layouts.master.uikit_scutum_2.index')
@section('title', isset($_SESSION['efiling_details']['stage_id']) && ($_SESSION['efiling_details']['stage_id'] == I_B_Defected_Stage) ? 'Refile case' : 'File a Document')
@section('heading', isset($_SESSION['efiling_details']['stage_id']) && ($_SESSION['efiling_details']['stage_id'] == I_B_Defected_Stage) ? 'Refiling' : 'File a Document')
@section('pinned-main-offcanvas') @endsection
@section('content-container-ribbon')@endsection
@section('content')
<!--@if(!empty(@$direct_access_params))
<form id="case-document-crud-form" target="case-document-crud-iframe" method="POST" action="{{base_url('case/search/search_case_details')}}">
    @foreach($direct_access_params as $key=>$value)
        <input type="hidden" name="{{$key}}" value="{{$value}}">
    @endforeach
</form>
@endif-->
<div class="container-fluid">
    <div class="row">
        <div class="col-12 sm-12 col-md-12 col-lg-12">
            <div class="center-content-inner comn-innercontent">
                <div class="uk-margin-small-top uk-border-rounded">
                    <iframe style="width:100%;max-height:100px!important;height:1679px!important;" name="case-document-crud-iframe" class="uk-width internal-content-iframe iframe-scroll-bar"  src="{{(empty(@$registration_id) ? base_url((@$tab == 'affirmation' ? @$tab : (@$tab == 'courtFee'?'miscellaneous_docs/'.@$tab:'newcase/'.@$tab) )) : base_url('miscellaneous_docs/defaultController/'.($registration_id)))}}"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function(){
        $('#case-document-crud-form').submit();
    });
</script>
@endsection