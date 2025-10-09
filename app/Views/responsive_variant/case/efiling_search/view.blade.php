<!-- @extends('responsive_variant.layouts.master.uikit_scutum_2.index')
  -->
  @extends('layout.app')

<!-- @section('title', 'E-Filing View')
@section('heading', 'E-Filing View')
@section('pinned-main-offcanvas') @endsection
@section('content-container-ribbon')@endsection -->

@section('content')
<div class="uk-margin-small-top uk-border-rounded">
    <iframe name="case-document-crud-iframe" class="uk-width internal-content-iframe"  src="{{(empty(@$idss) ? base_url('efiling_search') : base_url('efiling_search/get_view_data/'.($idss)))}}"></iframe>
</div>
@endsection
