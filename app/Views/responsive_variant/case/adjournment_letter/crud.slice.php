@extends('responsive_variant.layouts.master.uikit_scutum_2.index')

@section('title', 'Adjournment Letter in a Case')
@section('heading', 'Adjournment Letter in a case')
@section('pinned-main-offcanvas') @endsection
@section('content-container-ribbon')@endsection

@section('content')

@if(!empty(@$direct_access_params))
<form id="case-adjournment-letter-crud-form" target="case-citation-crud-iframe" method="POST" action="{{base_url('case/search/search_case_details')}}">
    @foreach($direct_access_params as $key=>$value)
        <input type="hidden" name="{{$key}}" value="{{$value}}">
    @endforeach
</form>
@endif
<div class="uk-margin-small-top uk-border-rounded">
    <iframe name="case-adjournment-letter-crud-iframe" class="uk-width internal-content-iframe"  src="{{empty(@$direct_access_params) ? base_url('case/search/'.url_encryption('adjournment_letter')) : ''}}"></iframe>
</div>

<script type="text/javascript">
    $(function(){
        $('#case-adjournment-letter-crud-form').submit();
    });
</script>

@endsection
