@extends('layout.advocateApp')
@section('content')
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
                                    <h5 class="unerline-title">Cases Transferred by Vakalatnama </h5>
                                    <!-- <a href="javascript:void(0)" class="quick-btn pull-right" onclick="window.history.back()"><span class="mdi mdi-chevron-double-left"></span>Back</a> -->
                                    <a href="javascript:void(0)" onclick="window.history.back()" class="quick-btn pull-right"><span class="mdi mdi-chevron-double-left"></span>Back</a>
                                </div>
                                <div class="table-sec ">
                                    <div class="table-responsive w-100 ">
                                        <table id="datatable-responsive" class="table table-striped table-border custom-table mt-4">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>E-filing Number</th>
                                                    <th>Year</th>
                                                    <th>Diary No.</th>
                                                    <th>Case Transferred On</th>
                                                    <th>Transferred To AOR (AOR code)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if ( isset($get_transferred_cases) && is_array($get_transferred_cases) && count($get_transferred_cases) > 0)
                                                    @foreach ($get_transferred_cases as $index=>$case)
                                                    <tr>
                                                        <td data-key="#" >{{$index+1}}</td>
                                                        <td data-key="E-filing Number" >{{$case->efiling_no}}</td>
                                                        <td data-key="Year" >{{$case->efiling_year}}</td>
                                                        <td data-key="Diary No." >{{$case->diary_no}}</td>
                                                        <td data-key="Case Transferred On" >{{$case->updated_on}}</td>
                                                        <td data-key="Transferred To AOR (AOR code)" >{{$case->name}} ({{$case->aor_code }})</td>
                                                    </tr>
                                                    @endforeach
                                                @else
                                                <tr>
                                                    <td colspan="6" >
                                                        <span class="text-danger fw-bold">Record Not Found</span>
                                                    </td>
                                                </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#datatable-responsive').DataTable();
        });
    </script>
@endsection