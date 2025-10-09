@extends('layout.advocateApp')
@section('title', !empty(getSessionData('efiling_details')['stage_id']) ? ((getSessionData('efiling_details')['stage_id'] == 10 or
getSessionData('efiling_details')['stage_id'] == 11) ? 'Refile case' : 'Register case') : '')
@section('heading', !empty(getSessionData('efiling_details')['stage_id']) ? ((getSessionData('efiling_details')['stage_id'] == 10 or
getSessionData('efiling_details')['stage_id'] == 11) ? 'Refiling' : 'Register a case') : '')
@section('content')
<div class="mainPanel ">
    <div class="panelInner">
        <div class="middleContent">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-12 sm-12 col-md-12 col-lg-12 middleContent-left">
                                <div class="center-content-inner comn-innercontent">
                                    <iframe name="content-iframe" class="col-12 iframe-scroll-bar" src="{{(empty(@$registration_id) ? base_url((@$tab == 'affirmation' ? @$tab : 'newcase/'.@$tab)) : base_url('newcase/defaultController/'.($registration_id)))}}"></iframe>
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