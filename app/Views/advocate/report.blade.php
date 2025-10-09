@extends('layout.advocateApp')
@section('content')
    <link rel="shortcut icon" href="<?= base_url().'assets/newDesign/images/logo.png' ?>" type="image/png" />
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-datepicker.min.css">
    <style>
        .dateSymbol {
            position: relative;float: right;top: -25px;right: 8px;
        }
    </style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="dashboard-section dashboard-tiles-area"></div>
                <div class="dashboard-section">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="dash-card">
                                {{-- Page Title Start --}}
                                <div class="title-sec">
                                    <h5 class="unerline-title">Advocates Appearing</h5>
                                    <!-- <a href="javascript:void(0)" class="quick-btn pull-right mb-2" onclick="window.history.back()"><span class="mdi mdi-chevron-double-left"></span>Back</a> -->
                                    <a href="javascript:void(0)" onclick="window.history.back()" class="quick-btn pull-right"><span class="mdi mdi-chevron-double-left"></span>Back</a>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 mb-2">
                                        <div class="crnt-page-head mx-2 mb-3">
                                            <div class="current-pg-title">
                                             <h6>Search </h6>
                                            </div>
                                            <div class="current-pg-actions"> </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="panel panel-default">
                                        <form class="form-horizontal" method="POST" action="<?php echo base_url('advocate/report'); ?>">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <div class="row">
                                                <div class="col-md-3 col-sm-12 col-xs-12">
                                                    <div class="row">
                                                        <div class="row mb-3 w-100 align-items-center">
                                                            <div class="col-12">
                                                                <label for="inputPassword6" class="col-form-label" style="position:relative; top:-8px !important;">List Date</label>
                                                            </div>
                                                            <div class="col-12 pe-0">
                                                                <input class="form-control cus-form-ctrl datepick" id="cause_list_date"  name="cause_list_date" placeholder="DD-MM-YYYY" type="text" required />
                                                                <!-- <span class="fa fa-calendar-o form-control-feedback left dateSymbol" aria-hidden="true" ></span> -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                    <div class="save-form-details">
                                                        <div class="save-btns">
                                                            <button type="submit" class="quick-btn" id="search_sc_case" value="SEARCH">Search</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>  
                                        </form>
                                        @if(isset($list))
                                            <div class="row mt-3">
                                                <div class="col-12">
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <h3 class="card-title">Advocates Appearing for List Date {{$cause_list_date}}</h3>
                                                        </div>
                                                        <div class="card-body table-responsive p-0">
                                                        <table class="table table-striped custom-table first-th-left dt-responsive nowrap">
                                                                <thead>
                                                                <tr>
                                                                    <th>SNo.</th>
                                                                    <th>Listed On</th>
                                                                    <th>Court No.</th>
                                                                    <th>Item No.</th>
                                                                    <th>Case No.</th>
                                                                    <th>Cause Title</th>
                                                                    <th>Name of Advocates</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @php
                                                                        $sno = 1;
                                                                    @endphp
                                                                    @foreach($list as $advocate)     
                                                                        @if(isset($advocate['diary_details']->pno ) && $advocate['diary_details']->pno == 2)
                                                                            @php $pet_name = $advocate['diary_details']->pet_name." AND ANR."; @endphp
                                                                        @elseif(isset($advocate['diary_details']->pno ) && $advocate['diary_details']->pno > 2)
                                                                            @php $pet_name = $advocate['diary_details']->pet_name." AND ORS."; @endphp
                                                                        @else
                                                                            @php $pet_name = isset($advocate['diary_details']->pet_name)?$advocate['diary_details']->pet_name:''; @endphp
                                                                        @endif

                                                                        @if(isset($advocate['diary_details']->rno) && $advocate['diary_details']->rno == 2)
                                                                            @php $res_name = $advocate['diary_details']->res_name." AND ANR."; @endphp
                                                                        @elseif(isset($advocate['diary_details']->rno) &&  $advocate['diary_details']->rno > 2)
                                                                            @php $res_name = $advocate['diary_details']->res_name." AND ORS."; @endphp
                                                                        @else
                                                                            @php $res_name = isset($advocate['diary_details']->res_name)?$advocate['diary_details']->res_name:''; @endphp
                                                                        @endif
                                                                        <tr>
                                                                            <td data-key="SNo.">{{ $sno++ }}</td>
                                                                            <td data-key="Listed On">{{ date('d-m-Y', strtotime($advocate['list_date'])) }}</td>

                                                                            <td data-key="Court No.">{{ $advocate['court_no'] }}</td>
                                                                            <td data-key="Item No.">{{ $advocate['item_no'] }}</td>
                                                                            <td data-key="Case No."> <?php  echo isset($advocate['diary_details']->reg_no_display)? $advocate['diary_details']->diary_no:''; ?></td>                                                                          
                                                                            <td data-key="Cause Title">
                                                                                {{ $pet_name }}<br>
                                                                                Vs.
                                                                                <br>
                                                                                {{ $res_name }}
                                                                            </td>
                                                                            <td data-key="Name of Advocates">
                                                                                <div>
                                                                                    @if( $advocate['appearing_for'] == 'P')
                                                                                        <u><b>For Petitioner</b></u>
                                                                                    @else
                                                                                        <u><b>For Respondent</b></u>
                                                                                    @endif
                                                                                </div>

                                                                                <?php $sub_sno = 1; ?>
                                                                                @foreach($advocate['advocate_name'] as $key => $adv_name)
                                                                                    <div>{{ $sub_sno++.'. '. $adv_name->advocate_title .' '.$adv_name->advocate_name.', '.$adv_name->advocate_type.'' }}</div>                                                                                  
                                                                                @endforeach
                                                                            </td>
                                                                        </tr>                                                                                  
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif                                     
                                    </div>
                                </div>   
                            </div>
                        </div>
                    </div>
                    <div class="row"></div>
                </div>
            </div>
        </div>
    </div>
    @push('script')   
        <script src="<?= base_url() ?>assets/js/bootstrap-datepicker.js"></script>
        <script src="<?= base_url() ?>assets/js/bootstrap-datepicker.min.js"></script>  
        <script>
            $(document).ready(function() {
                // alert("SC");
                $('#cause_list_date').datepicker({
                changeMonth: true,
                changeYear: true,
                yearRange: "-100:-1",
                dateFormat: "dd/mm/yy",
                defaultDate: '-40y'
                });
                $(document).on('change','#cause_list_date', function(){
                    var value = $('#cause_list_date').val();
                    var parts = value.split("/");
                    var day = parts[0] && parseInt(parts[0], 10);
                    var month = parts[1] && parseInt(parts[1], 10);
                    var year = parts[2] && parseInt(parts[2], 10);
                    var str = day + '/' + month + '/' + year;
                    var today = new Date(),
                    dob = new Date(str),
                    age = new Date(today - dob).getFullYear() - 1970;
                    $('#pet_age').val(age);
                });
            })
        </script>
    @endpush
@endsection