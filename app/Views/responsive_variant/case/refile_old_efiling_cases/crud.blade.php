@extends('layout.advocateApp')
@section('title', 'Refile old efiling cases')
@section('heading', 'Refile old efiling cases')
@section('pinned-main-offcanvas') @endsection
@section('content-container-ribbon')@endsection
@section('content')


@if(!empty(@$direct_access_params))

<?php
$attribute = array('id' => 'case-document-crud-form', 'target' => 'case-document-crud-iframe');
echo form_open(base_url('case/search/search_case_details'), $attribute);
?>
@foreach($direct_access_params as $key=>$value)
<input type="hidden" name="{{$key}}" value="{{$value}}">
@endforeach
</form>
@endif
<div class="mainPanel ">
    <div class="panelInner">
        <div class="middleContent">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-12 sm-12 col-md-12 col-lg-12 middleContent-left">
                                <div class="center-content-inner comn-innercontent">
                                    <?php
                                    $var = empty(@$direct_access_params) ? base_url('case/search/' . url_encryption('refile_old_efiling_cases')) : '';
                                    ?>
                                    <iframe name="case-document-crud-iframe" class="col-12 iframe-scroll-bar" src="{{$var}}"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<?php //pr($direct_access_params);?>
@section('script')
<script type="text/javascript">
    $(function() {
        $('#case-document-crud-form').submit();
    });
</script>

@endsection