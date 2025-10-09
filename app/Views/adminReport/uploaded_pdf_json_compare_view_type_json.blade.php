@extends('layout.app')
@section('content')
<style>
    table {
        table-layout: fixed;
        word-wrap: break-word;
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
                                <h5 class="unerline-title"> Metadata Comparison Report </h5>
                                <a href="javascript:void(0)" onclick="window.history.back()" class="quick-btn pull-right"><span class="mdi mdi-chevron-double-left"></span>Back</a>
                            </div>
                            {{-- Page Title End --}}
                            {{-- Main Start --}}
                            <div class="right_col" role="main">
                                <div class="table-responsive">
                                    <table id="datatable-responsive" class="table table-striped table-border custom-table" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th scope="col">IITM JSON</th>
                                                <th scope="col">EFILING JSON</th>
                                                <th scope="col">ICMIS JSON</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="col-4">
                                                    <?php
                                                    echo "<pre>";
                                                    echo json_encode(json_decode($json_detail->iitm_api_json, true), JSON_PRETTY_PRINT);
                                                    echo "</pre>";
                                                    ?>
                                                </td>
                                                <td class="col-4">
                                                    <?php                                    
                                                    echo "<pre>";
                                                    echo json_encode(json_decode($json_detail->efiling_json, true), JSON_PRETTY_PRINT);
                                                    echo "</pre>";
                                                    ?>
                                                </td>
                                                <td class="col-4">
                                                    <?php
                                                    echo "<pre>";
                                                    echo json_encode(json_decode($json_detail->icmis_json, true), JSON_PRETTY_PRINT);
                                                    echo "</pre>";
                                                    ?>
                                                </td>
                                            </tr>
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
@endsection
@push('script')
<script>
    $(document).ready(function() {
        $('#from_date').datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd",
            maxDate: new Date
            //defaultDate: '-6d'
        });  
    });
</script>
@endpush