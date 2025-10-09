
<!-- Welcome To Admin Dashboard -->

<!-- {{-- @extends('responsive_variant.layouts.master.uikit_scutum_2.index') -->
@section('title', 'Dashboard')
@section('pinned-main-offcanvas') @endsection
@section('content-container-ribbon')@endsection

@section('content')
<script type="text/javascript">
    if(window.name.endsWith("-iframe")) {
        parent.window.location.href=window.location.href;
    } else {
        window.location.href = '<?php echo base_url('dashboard_alt'); ?>';
    }

</script>
@endsection