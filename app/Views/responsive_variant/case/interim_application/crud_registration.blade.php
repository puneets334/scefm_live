@extends('layout.advocateApp')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="dashboard-section dashboard-tiles-area"></div>
                <div class="dashboard-section">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <!-- <div class="dash-card"> -->
                                {{-- Page Title Start --}}
                                <!-- <div class="title-sec">
                                    <h5 class="unerline-title">User File Transfer </h5>
                                </div> -->
                                {{-- Page Title End --}}
                                {{-- Main Start --}}
                                <div class="uk-margin-small-top uk-border-rounded">
                                    <iframe style="width: 100%; max-height: 100px !important;" name="case-ia-crud-iframe" class="uk-width internal-content-iframe iframe-scroll-bar" src="{{(empty(@$registration_id) ? base_url((@$tab == 'affirmation' ? @$tab : (@$tab == 'courtFee'?'IA/'.@$tab:'newcase/'.@$tab) )) : base_url('IA/defaultController/'.($registration_id)))}}"></iframe>
                                    <!-- <iframe style="width: 100%; height:100rem;" name="case-ia-crud-iframe" class="uk-width internal-content-iframe" src="{{(empty(@$registration_id) ? base_url((@$tab == 'affirmation' ? @$tab : (@$tab == 'courtFee'?'IA/'.@$tab:'newcase/'.@$tab) )) : base_url('IA/defaultController/'.($registration_id)))}}"></iframe> -->
                                </div>
                                {{-- Main End --}}
                            <!-- </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')

<script src="<?= base_url() . 'assets/newAdmin/' ?>js/jquery351.min.js"></script>
<script type="text/javascript">
    $(function(){
        $('#case-ia-crud-form').submit();
    });
</script>

@endpush