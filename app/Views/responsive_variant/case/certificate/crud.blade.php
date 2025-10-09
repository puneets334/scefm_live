@extends('responsive_variant.layouts.master.uikit_scutum_2.index')

@section('title', 'Certificate Request in a Case')
@section('heading', 'Certificate Request in a case')
@section('pinned-main-offcanvas') @endsection
@section('content-container-ribbon')@endsection

@section('content')

@if(!empty(@$direct_access_params))
<!--<form id="case-certificate-crud-form" target="case-certificate-crud-iframe" method="POST" action="{{base_url('case/search/search_case_details')}}">-->
    <?php
    $attribute = array('id' => 'case-certificate-crud-form', 'target' => 'case-certificate-crud-iframe');
    echo form_open(base_url('case/search/search_case_details'), $attribute);
    ?>
    @foreach($direct_access_params as $key=>$value)
    <input type="hidden" name="{{$key}}" value="{{$value}}">
    @endforeach
</form>
@endif
<div class="uk-margin-small-top uk-border-rounded">
    <iframe name="case-certificate-crud-iframe" class="uk-width internal-content-iframe"  src="{{empty(@$direct_access_params) ? base_url('case/search/'.url_encryption('certificate')) : ''}}"></iframe>
</div>

<script type="text/javascript">
    $(function(){
        $('#case-certificate-crud-form').submit();
    });
</script>

@endsection
