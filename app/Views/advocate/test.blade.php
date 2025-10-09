@extends('admin.master')
@section('content')
<?php
//var_dump($list);
?>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="content-wrapper">

        <!-- Main content -->
        <div class="container">
            <div class="card">
                <div class="card-header">
                    Appearance Slip

                    <span class="float-right"> <strong>Status:</strong> Submitted Successfully</span>

                </div>
                <div class="card-body">
                    <div class="row justify-content-center col-sm-12">I certify that following Senior Advocates/Advocates will appear/appeared in the below mentioned matter.</div>
                    <div class="row mb-4">
                        <div class="col-sm-12">
                            <div>List Date : <strong>Webz Poland</strong></div>
                            <div>Court No. : <strong>Webz Poland</strong></div>
                            <div>Item No. : <strong>Webz Poland</strong></div>
                            <div>Case No. : <strong>Webz Poland</strong></div>
                            <div>Title : <strong>Webz Poland</strong></div>
                            <div>Appearing For : <strong>Webz Poland</strong></div>
                            Appearing For
                        </div>




                    </div>

                    <div class="table-responsive-sm">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th class="center">#</th>
                                <th>Name of Advocates</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td data-key="#" class="center">1</td>
                                <td data-key="Name of Advocates" class="left strong">Origin License</td>
                            </tr>
                            <tr>
                                <td data-key="#" class="center">1</td>
                                <td data-key="Name of Advocates" class="left strong">Origin License</td>
                            </tr>
                            <tr>
                                <td data-key="#" class="center">1</td>
                                <td data-key="Name of Advocates" class="left strong">Origin License</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">


                        <div class="col-lg-4 col-sm-5 ml-auto">
                            <table class="table table-clear">
                                <tbody>
                                <tr>
                                    <td class="right">
                                        <strong> {{ucwords(strtolower(session('user_title'))).' '.ucwords(strtolower(session('user_name')))}}
                                            <br>
                                            For the {{$posted_data['appearing_for'] == 'P' ? 'Petitioner' : 'Respondent'}}</strong>
                                    </td>

                                </tr>
                                </tbody>
                            </table>

                        </div>

                    </div>

                </div>
            </div>
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->



@endsection